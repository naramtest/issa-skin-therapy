<?php

namespace App\Exports;

use App\Enums\DHLFieldDefinitions;
use App\Models\Order;
use App\Services\Currency\CurrencyHelper;
use App\Services\Utils\ArabicTransliterationService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class DHLOrderExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithColumnFormatting
{
    protected Collection $orders;
    protected array $headings;
    protected ArabicTransliterationService $transliterationService;

    public function __construct(Collection $orders)
    {
        $this->orders = $orders;
        $this->transliterationService = app(
            ArabicTransliterationService::class
        );
        $this->headings = array_map(
            fn($field) => $field->value,
            DHLFieldDefinitions::cases()
        );
    }

    public function collection(): Collection
    {
        return $this->orders;
    }

    public function headings(): array
    {
        return $this->headings;
    }

    public function columnFormats(): array
    {
        return [
            "B" => NumberFormat::FORMAT_DATE_DDMMYYYY, // Date
            "N" => NumberFormat::FORMAT_NUMBER_00, // ItemPrice
            "P" => NumberFormat::FORMAT_NUMBER_00, // Weight
        ];
    }

    /** @var Order $order */
    public function map($order): array
    {
        $rows = [];

        foreach ($order->items as $item) {
            $purchasable = $item->purchasable;

            // Map basic order fields that will be the same for all rows
            $baseRow = [
                DHLFieldDefinitions::ORDER_NUMBER->value => Str::limit(
                    $order->order_number,
                    DHLFieldDefinitions::ORDER_NUMBER->getMaxLength()
                ),
                DHLFieldDefinitions::DATE->value => $order->created_at->format(
                    "Y-m-d"
                ),

                // Transliterate Arabic names and addresses
                DHLFieldDefinitions::TO_NAME
                    ->value => $this->transliterateAndLimit(
                    $order->shippingAddress->full_name,
                    DHLFieldDefinitions::TO_NAME->getMaxLength(),
                    "name"
                ),
                DHLFieldDefinitions::DESTINATION_BUILDING
                    ->value => $this->transliterateAndLimit(
                    $order->shippingAddress->building .
                        " Flat: " .
                        $order->shippingAddress->flat,
                    DHLFieldDefinitions::DESTINATION_BUILDING->getMaxLength(),
                    "address"
                ),
                DHLFieldDefinitions::DESTINATION_STREET
                    ->value => $this->transliterateAndLimit(
                    $order->shippingAddress->address,
                    DHLFieldDefinitions::DESTINATION_STREET->getMaxLength(),
                    "address"
                ),
                DHLFieldDefinitions::DESTINATION_SUBURB
                    ->value => $this->transliterateAndLimit(
                    $order->shippingAddress->area,
                    DHLFieldDefinitions::DESTINATION_SUBURB->getMaxLength(),
                    "address"
                ),
                DHLFieldDefinitions::DESTINATION_CITY
                    ->value => $this->transliterateAndLimit(
                    $order->shippingAddress->city,
                    DHLFieldDefinitions::DESTINATION_CITY->getMaxLength(),
                    "city"
                ),
                DHLFieldDefinitions::DESTINATION_POSTCODE->value => Str::limit(
                    $order->shippingAddress->postal_code,
                    DHLFieldDefinitions::DESTINATION_POSTCODE->getMaxLength()
                ),
                DHLFieldDefinitions::DESTINATION_STATE
                    ->value => $this->transliterateAndLimit(
                    $order->shippingAddress->state,
                    DHLFieldDefinitions::DESTINATION_STATE->getMaxLength(),
                    "address"
                ),
                DHLFieldDefinitions::DESTINATION_COUNTRY->value => Str::limit(
                    $order->shippingAddress->country,
                    DHLFieldDefinitions::DESTINATION_COUNTRY->getMaxLength()
                ),
                DHLFieldDefinitions::DESTINATION_EMAIL->value => Str::limit(
                    $order->email,
                    DHLFieldDefinitions::DESTINATION_EMAIL->getMaxLength()
                ),
                DHLFieldDefinitions::DESTINATION_PHONE->value => Str::limit(
                    $order->shippingAddress->phone,
                    DHLFieldDefinitions::DESTINATION_PHONE->getMaxLength()
                ),

                // Item specific fields - transliterate product names
                DHLFieldDefinitions::ITEM_NAME
                    ->value => $this->transliterateAndLimit(
                    $purchasable->name,
                    DHLFieldDefinitions::ITEM_NAME->getMaxLength()
                ),
                DHLFieldDefinitions::ITEM_PRICE
                    ->value => CurrencyHelper::decimalFormatter(
                    $item->money_unit_price
                ),
                DHLFieldDefinitions::INSTRUCTIONS
                    ->value => $this->transliterateAndLimit(
                    $order->notes,
                    DHLFieldDefinitions::INSTRUCTIONS->getMaxLength()
                ),
                DHLFieldDefinitions::WEIGHT->value => number_format(
                    $purchasable->weight ?? 0,
                    2
                ),
                DHLFieldDefinitions::SHIPPING_METHOD->value => null,
                DHLFieldDefinitions::REFERENCE->value => Str::limit(
                    $order->id,
                    DHLFieldDefinitions::REFERENCE->getMaxLength()
                ),
                DHLFieldDefinitions::SKU->value => Str::limit(
                    $purchasable->sku,
                    DHLFieldDefinitions::SKU->getMaxLength()
                ),
                DHLFieldDefinitions::QTY->value => $item->quantity,
                DHLFieldDefinitions::COMPANY->value => null,

                // DHL specific fields
                DHLFieldDefinitions::SIGNATURE_REQUIRED->value => "N",
                DHLFieldDefinitions::ATL->value => "N",
                DHLFieldDefinitions::COUNTRY_CODE->value => Str::limit(
                    $order->shippingAddress->country,
                    DHLFieldDefinitions::COUNTRY_CODE->getMaxLength()
                ),
                DHLFieldDefinitions::PACKAGE_HEIGHT->value =>
                    $purchasable->height ?? 1,
                DHLFieldDefinitions::PACKAGE_WIDTH->value =>
                    $purchasable->width ?? 1,
                DHLFieldDefinitions::PACKAGE_LENGTH->value =>
                    $purchasable->length ?? 1,
                DHLFieldDefinitions::CARRIER->value => "DHL",
                DHLFieldDefinitions::CARRIER_PRODUCT_CODE->value =>
                    $order->dhl_product?->getCommerceCode() ?? "",
                DHLFieldDefinitions::CARRIER_PRODUCT_UNIT_TYPE->value => null,
                DHLFieldDefinitions::DECLARED_VALUE_CURRENCY->value =>
                    $order->currency_code,
                DHLFieldDefinitions::CODE->value => $purchasable->hs_code ?? "",
                DHLFieldDefinitions::COLOR->value => "",
                DHLFieldDefinitions::SIZE->value => "",
                DHLFieldDefinitions::CONTENTS->value => "",
                DHLFieldDefinitions::DANGEROUS_GOODS->value => "N",
                DHLFieldDefinitions::COUNTRY_OF_MANUFACTURER->value =>
                    $purchasable->country_of_origin ?? "AE",
                DHLFieldDefinitions::DDP->value => "",
                DHLFieldDefinitions::RECEIVER_EORI->value => "",
                DHLFieldDefinitions::RECEIVER_VAT->value => "",
                DHLFieldDefinitions::SHIPPING_FREIGHT_VALUE
                    ->value => CurrencyHelper::decimalFormatter(
                    $order->money_shipping_cost
                ),
            ];

            $rows[] = $baseRow;
        }

        return $rows;
    }

    /**
     * Transliterate Arabic text and limit to maximum length
     */
    protected function transliterateAndLimit(
        ?string $text,
        int $maxLength,
        string $fieldType = "default"
    ): string {
        if (empty($text)) {
            return "";
        }

        $transliterated = $this->transliterationService->transliterateForField(
            $text,
            $fieldType
        );
        return Str::limit($transliterated, $maxLength);
    }
}
