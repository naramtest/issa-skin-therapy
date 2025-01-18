<?php

namespace App\Services;

use App\Models\City;
use App\Models\Country;
use App\Models\State;
use Exception;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Str;

class LocationService
{
    protected array $urls = [
        "countries" =>
            "https://raw.githubusercontent.com/dr5hn/countries-states-cities-database/refs/heads/master/json/countries.json",
        "states" =>
            "https://raw.githubusercontent.com/dr5hn/countries-states-cities-database/refs/heads/master/json/states.json",
        "cities" =>
            "https://raw.githubusercontent.com/dr5hn/countries-states-cities-database/refs/heads/master/json/cities.json",
    ];
    protected string $backupPath = "locations/";
    protected int $chunkSize = 1000;

    /**
     * @throws Exception
     */
    public function importLocationsData(): void
    {
        try {
            if (!Storage::exists($this->backupPath)) {
                Storage::makeDirectory($this->backupPath);
            }

            // Import in stages
            $this->importCountries();
            $this->importStates();
            $this->importCities();

            $this->clearCache();

            Log::info("Location import completed successfully");
        } catch (Exception $e) {
            Log::error("Location import failed", [
                "error" => $e->getMessage(),
                "trace" => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    /**
     * @throws Exception
     */
    protected function importCountries(): void
    {
        $countries = $this->fetchData("countries");

        // Process countries in chunks
        collect($countries)
            ->chunk($this->chunkSize)
            ->each(function ($chunk) {
                foreach ($chunk as $countryData) {
                    Country::updateOrCreate(
                        ["iso2" => $countryData["iso2"]],
                        [
                            "name" => $countryData["name"],
                            "iso3" => $countryData["iso3"],
                            "native" => $countryData["native"] ?? null,
                            "currency" => $countryData["currency"] ?? null,
                            "currency_name" =>
                                $countryData["currency_name"] ?? null,
                            "currency_symbol" =>
                                $countryData["currency_symbol"] ?? null,
                            "phone_code" => $countryData["phone_code"] ?? null,
                            "region" => $countryData["region"] ?? null,
                            "subregion" => $countryData["subregion"] ?? null,
                            "latitude" => $countryData["latitude"] ?? null,
                            "longitude" => $countryData["longitude"] ?? null,
                            "emoji" => $countryData["emoji"] ?? null,
                            "is_active" => true,
                        ]
                    );
                }
            });

        Log::info("Countries import completed", ["count" => count($countries)]);
    }

    /**
     * @throws ConnectionException
     */
    protected function fetchData(string $type): array
    {
        $backupFile = $this->backupPath . $type . ".json";

        try {
            $response = Http::timeout(120)->get($this->urls[$type]);

            if ($response->successful()) {
                $data = $response->json();
                Storage::put($backupFile, json_encode($data));
                return $data;
            }

            if (Storage::exists($backupFile)) {
                Log::warning("Using backup file for $type import");
                return json_decode(Storage::get($backupFile), true);
            }

            throw new Exception(
                "Failed to download $type data and no backup available"
            );
        } catch (Exception $e) {
            Log::error("Error fetching $type data", [
                "error" => $e->getMessage(),
            ]);

            if (Storage::exists($backupFile)) {
                Log::info("Using backup file for $type import after error");
                return json_decode(Storage::get($backupFile), true);
            }

            throw $e;
        }
    }

    /**
     * @throws ConnectionException
     */
    protected function importStates(): void
    {
        $states = $this->fetchData("states");
        $countryIds = Country::pluck("id", "iso2")->toArray();

        // Process states in chunks
        collect($states)
            ->chunk($this->chunkSize)
            ->each(function ($chunk) use ($countryIds) {
                foreach ($chunk as $stateData) {
                    // Skip if country not found or missing required data
                    if (
                        !isset($countryIds[$stateData["country_code"]]) ||
                        empty($stateData["name"])
                    ) {
                        continue;
                    }

                    // Generate state code if not present
                    $stateCode =
                        $stateData["state_code"] ??
                        Str::slug($stateData["name"]);

                    State::updateOrCreate(
                        [
                            "country_id" =>
                                $countryIds[$stateData["country_code"]],
                            "state_code" => $stateCode,
                        ],
                        [
                            "name" => $stateData["name"],
                            "type" => $stateData["type"] ?? null,
                            "latitude" => $stateData["latitude"] ?? null,
                            "longitude" => $stateData["longitude"] ?? null,
                            "is_active" => true,
                        ]
                    );
                }
            });

        Log::info("States import completed", ["count" => count($states)]);
    }

    /**
     * @throws ConnectionException
     */
    protected function importCities(): void
    {
        $cities = $this->fetchData("cities");
        $stateIds = State::pluck("id", "state_code")->toArray();

        // Process cities in chunks
        collect($cities)
            ->chunk($this->chunkSize)
            ->each(function ($chunk) use ($stateIds) {
                foreach ($chunk as $cityData) {
                    if (!isset($stateIds[$cityData["state_code"]])) {
                        continue;
                    }

                    City::updateOrCreate(
                        [
                            "state_id" => $stateIds[$cityData["state_code"]],
                            "name" => $cityData["name"],
                        ],
                        [
                            "latitude" => $cityData["latitude"] ?? null,
                            "longitude" => $cityData["longitude"] ?? null,
                            "is_active" => true,
                        ]
                    );
                }
            });

        Log::info("Cities import completed", ["count" => count($cities)]);
    }

    protected function clearCache(): void
    {
        Cache::tags(["locations"])->flush();
    }

    public function getCountries(): Collection
    {
        return Cache::tags(["locations"])->remember(
            "countries.all",
            now()->addDay(),
            function () {
                return Country::active()
                    ->orderBy("name")
                    ->select(["id", "name", "iso2", "emoji"])
                    ->get();
            }
        );
    }

    public function getStates(string $countryId): Collection
    {
        return Cache::tags(["locations"])->remember(
            "states.$countryId",
            now()->addDay(),
            function () use ($countryId) {
                return State::active()
                    ->where("country_id", $countryId)
                    ->orderBy("name")
                    ->select(["id", "name", "state_code"])
                    ->get();
            }
        );
    }

    public function getCities(string $stateId): Collection
    {
        return Cache::tags(["locations"])->remember(
            "cities.$stateId",
            now()->addDay(),
            function () use ($stateId) {
                $cities = City::active()
                    ->where("state_id", $stateId)
                    ->orderBy("name")
                    ->select(["id", "name"])
                    ->get();

                // If no cities found, create a virtual city with the state name
                if ($cities->isEmpty()) {
                    $state = State::find($stateId);
                    if ($state) {
                        return collect([
                            (object) [
                                "id" => $stateId,
                                "name" => $state->name,
                            ],
                        ]);
                    }
                }

                return $cities;
            }
        );
    }

    public function getPopularCountries(): Collection
    {
        $popularCodes = [
            "US",
            "GB",
            "CA",
            "AU",
            "FR",
            "DE",
            "IT",
            "ES",
            "JP",
            "AE",
            "SA",
            "KW",
        ];

        return Country::active()
            ->whereIn("iso2", $popularCodes)
            ->orderByRaw('FIELD(iso2, "' . implode('","', $popularCodes) . '")')
            ->get();
    }
}
