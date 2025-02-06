<?php

namespace App\Helpers\DHL;

use App\Models\CustomerAddress;
use App\Models\Order;

class DHLAddress
{
    public static function getIsDomestic(string $country): bool
    {
        return config("store.address.country") == $country;
    }

    public static function shipmentCustomerDetails(Order $order): array
    {
        $storeAddress = config("store.address");
        return [
            "shipperDetails" => [
                "postalAddress" => self::shipperAddress($storeAddress),
                "contactInformation" => self::shipperContactInfo($storeAddress),
            ],
            "receiverDetails" => [
                "postalAddress" => self::receiverAddress(
                    $order->shippingAddress
                ),
                "contactInformation" => self::receiverContactInfo($order),
            ],
        ];
    }

    public static function shipperAddress(?array $storeAddress = null): array
    {
        $storeAddress ??= config("store.address");

        $postalCode = self::formatUAEPostalCode($storeAddress["postal_code"]);
        return [
            "postalCode" => $postalCode,
            "cityName" => $storeAddress["city"],
            "countryCode" => $storeAddress["country"],
            "addressLine1" => substr($storeAddress["address"], 0, 45),
        ];
    }

    public static function formatUAEPostalCode(string $postalCode): string
    {
        // Clean up the postal code
        $cleanPostal = preg_replace("/[^0-9]/", "", $postalCode);

        // Pad with zeros if needed (UAE postal codes are 5 digits)
        return str_pad($cleanPostal, 5, "0", STR_PAD_LEFT);
    }

    protected static function shipperContactInfo(
        ?array $storeAddress = null
    ): array {
        $storeAddress ??= config("store.address");

        return [
            "email" => $storeAddress["email"],
            "phone" => $storeAddress["phone"],
            "companyName" => config("store.address.name"),
            "fullName" => config("store.address.name"),
        ];
    }

    protected static function receiverAddress(CustomerAddress $address): array
    {
        $postalCode = self::formatUAEPostalCode($address->postal_code);
        return [
            "postalCode" => $postalCode,
            "cityName" => $address->city,
            "countryCode" => $address->country,
            "addressLine1" => substr($address->address, 0, 45),
            "addressLine2" => substr($address->building ?? "Building 1", 0, 45),
            "addressLine3" => substr($address->flat ?? "Building 1", 0, 45),
        ];
    }

    protected static function receiverContactInfo(Order $order): array
    {
        return [
            "email" => $order->email,
            "phone" => $order->shippingAddress->phone,
            "companyName" => "Personal",
            "fullName" => $order->shippingAddress->full_name,
        ];
    }
}
