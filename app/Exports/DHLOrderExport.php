<?php

namespace App\Exports;

use App\Enums\DHLFieldDefinitions;
use App\Models\Order;
use App\Services\Currency\CurrencyHelper;
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

    public function __construct(Collection $orders)
    {
        $this->orders = $orders;
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
        // We'll need to create multiple rows if order has multiple items
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
                DHLFieldDefinitions::TO_NAME->value => Str::limit(
                    $order->shippingAddress->full_name,
                    DHLFieldDefinitions::TO_NAME->getMaxLength()
                ),
                DHLFieldDefinitions::DESTINATION_BUILDING->value => Str::limit(
                    $order->shippingAddress->building,
                    DHLFieldDefinitions::DESTINATION_BUILDING->getMaxLength()
                ),
                DHLFieldDefinitions::DESTINATION_STREET->value => Str::limit(
                    $order->shippingAddress->address .
                        "Flat: " .
                        $order->shippingAddress->flat,
                    DHLFieldDefinitions::DESTINATION_STREET->getMaxLength()
                ),
                DHLFieldDefinitions::DESTINATION_SUBURB->value => Str::limit(
                    $order->shippingAddress->area,
                    DHLFieldDefinitions::DESTINATION_SUBURB->getMaxLength()
                ),
                DHLFieldDefinitions::DESTINATION_CITY->value => Str::limit(
                    $order->shippingAddress->city,
                    DHLFieldDefinitions::DESTINATION_CITY->getMaxLength()
                ),
                DHLFieldDefinitions::DESTINATION_POSTCODE->value => Str::limit(
                    $order->shippingAddress->postal_code,
                    DHLFieldDefinitions::DESTINATION_POSTCODE->getMaxLength()
                ),
                DHLFieldDefinitions::DESTINATION_STATE->value => Str::limit(
                    $order->shippingAddress->state,
                    DHLFieldDefinitions::DESTINATION_STATE->getMaxLength()
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

                // Item specific fields
                DHLFieldDefinitions::ITEM_NAME->value => Str::limit(
                    $purchasable->name,
                    DHLFieldDefinitions::ITEM_NAME->getMaxLength()
                ),
                DHLFieldDefinitions::ITEM_PRICE
                    ->value => CurrencyHelper::decimalFormatter(
                    $item->money_unit_price
                ),
                DHLFieldDefinitions::INSTRUCTIONS->value => Str::limit(
                    $order->notes,
                    DHLFieldDefinitions::INSTRUCTIONS->getMaxLength()
                ),
                DHLFieldDefinitions::WEIGHT->value => number_format(
                    $purchasable->weight ?? 0,
                    2
                ),
                //TODO: check to see if it will work like this
                //                DHLFieldDefinitions::SHIPPING_METHOD->value => Str::limit(
                //                    $order->shipping_method,
                //                    DHLFieldDefinitions::SHIPPING_METHOD->getMaxLength()
                //                ),
                DHLFieldDefinitions::REFERENCE->value => Str::limit(
                    $order->id,
                    DHLFieldDefinitions::REFERENCE->getMaxLength()
                ),
                DHLFieldDefinitions::SKU->value => Str::limit(
                    $purchasable->sku,
                    DHLFieldDefinitions::SKU->getMaxLength()
                ),
                DHLFieldDefinitions::QTY->value => $item->quantity,
                //                DHLFieldDefinitions::COMPANY->value => Str::limit(
                //                    config("app.name"),
                //                    DHLFieldDefinitions::COMPANY->getMaxLength()
                //                ),

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
                //                DHLFieldDefinitions::CARRIER_PRODUCT_UNIT_TYPE->value =>
                //                    "metric",
                DHLFieldDefinitions::DECLARED_VALUE_CURRENCY->value =>
                    $order->currency_code,
                DHLFieldDefinitions::CODE->value => $purchasable->hs_code ?? "",
                //                DHLFieldDefinitions::COLOR->value => "",
                //                DHLFieldDefinitions::SIZE->value => "",
                //                DHLFieldDefinitions::CONTENTS->value => "Commercial Sample",
                DHLFieldDefinitions::DANGEROUS_GOODS->value => "N",
                DHLFieldDefinitions::COUNTRY_OF_MANUFACTURER->value =>
                    $purchasable->country_of_origin ?? "AE",
                //                DHLFieldDefinitions::DDP->value => "N",
                //                DHLFieldDefinitions::RECEIVER_EORI->value => "",
                //                DHLFieldDefinitions::RECEIVER_VAT->value => "",
                DHLFieldDefinitions::SHIPPING_FREIGHT_VALUE
                    ->value => CurrencyHelper::decimalFormatter(
                    $order->money_shipping_cost
                ),
            ];

            $rows[] = $baseRow;
        }

        return $rows;
    }
}
