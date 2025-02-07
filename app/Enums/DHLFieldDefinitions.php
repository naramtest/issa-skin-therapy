<?php

namespace App\Enums;

enum DHLFieldDefinitions: string
{
    case ORDER_NUMBER = "Order Number";
    case DATE = "Date";
    case TO_NAME = "To Name";
    case DESTINATION_BUILDING = "Destination Building";
    case DESTINATION_STREET = "Destination Street";
    case DESTINATION_SUBURB = "Destination Suburb";
    case DESTINATION_CITY = "Destination City";
    case DESTINATION_POSTCODE = "Destination Postcode";
    case DESTINATION_STATE = "Destination State";
    case DESTINATION_COUNTRY = "Destination Country";
    case DESTINATION_EMAIL = "Destination Email";
    case DESTINATION_PHONE = "Destination Phone";
    case ITEM_NAME = "Item Name";
    case ITEM_PRICE = "Item Price";
    case INSTRUCTIONS = "Instructions";
    case WEIGHT = "Weight";
    case SHIPPING_METHOD = "Shipping Method";
    case REFERENCE = "Reference";
    case SKU = "SKU";
    case QTY = "Qty";
    case COMPANY = "Company";
    case SIGNATURE_REQUIRED = "Signature Required";
    case ATL = "ATL";
    case COUNTRY_CODE = "Country Code";
    case PACKAGE_HEIGHT = "Package Height";
    case PACKAGE_WIDTH = "Package Width";
    case PACKAGE_LENGTH = "Package Length";
    case CARRIER = "Carrier";
    case CARRIER_PRODUCT_CODE = "Carrier Product Code";
    case CARRIER_PRODUCT_UNIT_TYPE = "Carrier Product Unit Type";
    case DECLARED_VALUE_CURRENCY = "Declared Value Currency";
    case CODE = "Code";
    case COLOR = "Color";
    case SIZE = "Size";
    case CONTENTS = "Contents";
    case DANGEROUS_GOODS = "Dangerous Goods";
    case COUNTRY_OF_MANUFACTURER = "Country of Manufacturer";
    case DDP = "DDP";
    case RECEIVER_VAT = "ReceiverVAT";
    case RECEIVER_EORI = "ReceiverEORI";
    case SHIPPING_FREIGHT_VALUE = "ShippingFreightValue";
    case BRAND = "Brand";
    case USAGE = "Usage";
    case MATERIAL = "Material";
    case MODEL = "Model";
    case MID_CODE = "MID Code";
    case RECEIVER_NATIONAL_ID = "Receiver National ID";
    case RECEIVER_PASSPORT_NUMBER = "Receiver Passport Number";
    case RECEIVER_USCI = "Receiver USCI";
    case RECEIVER_CR = "Receiver CR";
    case RECEIVER_BRAZIL_CNP = "Receiver Brazil CNP";

    public function getMaxLength(): int
    {
        return match ($this) {
            self::ORDER_NUMBER => 50,
            self::DATE => 10,
            self::TO_NAME => 100,
            self::DESTINATION_BUILDING => 100,
            self::DESTINATION_STREET => 100,
            self::DESTINATION_SUBURB => 100,
            self::DESTINATION_CITY => 100,
            self::DESTINATION_POSTCODE => 10,
            self::DESTINATION_STATE => 100,
            self::DESTINATION_COUNTRY => 100,
            self::DESTINATION_EMAIL => 100,
            self::DESTINATION_PHONE => 100,
            self::ITEM_NAME => 100,
            self::ITEM_PRICE => 10,
            self::INSTRUCTIONS => 200,
            self::WEIGHT => 4,
            self::SHIPPING_METHOD => 100,
            self::REFERENCE => 50,
            self::SKU => 50,
            self::QTY => 50,
            self::COMPANY => 100,
            self::SIGNATURE_REQUIRED => 1,
            self::ATL => 1,
            self::COUNTRY_CODE => 2,
            self::PACKAGE_HEIGHT => 4,
            self::PACKAGE_WIDTH => 4,
            self::PACKAGE_LENGTH => 4,
            self::CARRIER => 100,
            self::CARRIER_PRODUCT_CODE => 3,
            self::CARRIER_PRODUCT_UNIT_TYPE => 50,
            self::DECLARED_VALUE_CURRENCY => 3,
            self::CODE => 50,
            self::COLOR => 50,
            self::SIZE => 50,
            self::CONTENTS => 50,
            self::DANGEROUS_GOODS => 1,
            self::COUNTRY_OF_MANUFACTURER => 50,
            self::DDP => 1,
            self::RECEIVER_EORI => 50,
            self::RECEIVER_VAT => 50,
            self::SHIPPING_FREIGHT_VALUE => 50,
            self::BRAND => 50,
            self::USAGE => 50,
            self::MATERIAL => 50,
            self::MODEL => 50,
            self::MID_CODE => 50,
            self::RECEIVER_NATIONAL_ID => 50,
            self::RECEIVER_PASSPORT_NUMBER => 50,
            self::RECEIVER_USCI => 50,
            self::RECEIVER_CR => 50,
            self::RECEIVER_BRAZIL_CNP => 50,
        };
    }

    public function isMandatory(): bool
    {
        return match ($this) {
            self::ORDER_NUMBER,
            self::DATE,
            self::TO_NAME,
            self::DESTINATION_STREET,
            self::DESTINATION_SUBURB,
            self::DESTINATION_CITY,
            self::DESTINATION_POSTCODE,
            self::DESTINATION_COUNTRY
                => true,
            default => false,
        };
    }
}
