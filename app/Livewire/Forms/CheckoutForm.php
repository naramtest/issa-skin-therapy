<?php

namespace App\Livewire\Forms;

use App\Enums\AddressType;
use App\Enums\Checkout\PaymentMethod;
use App\Models\Country;
use App\Models\State;
use Livewire\Attributes\Rule;
use Livewire\Form;

class CheckoutForm extends Form
{
    //TODO: fix error messages (ex: billing first name -> first name , The shipping first name field is required when different shipping address is true -> remove when different shipping address is true)

    // Contact Information
    #[Rule("required|email|max:255")]
    public string $email = "";

    #[Rule("required|string|max:20")]
    public string $phone = "";

    // Billing Address
    #[Rule("required|string|max:255")]
    public string $billing_first_name = "";

    #[Rule("required|string|max:255")]
    public string $billing_last_name = "";

    #[Rule("required|string|max:255")]
    public string $billing_address = "";

    #[Rule("required|string|max:255")]
    public string $billing_city = "";

    #[Rule("required|string|max:255")]
    public string $billing_state = "";

    #[Rule("required|string|max:255")]
    public string $billing_country = "";

    #[Rule("required|string|max:20")]
    public string $billing_postal_code = "00000";

    #[Rule("required|string|max:255")]
    public string $billing_area = "";

    #[Rule("required|string|max:255")]
    public string $billing_building = "";

    #[Rule("required|string|max:255")]
    public string $billing_flat = "";

    // Shipping Address
    #[Rule("boolean")]
    public bool $different_shipping_address = false;

    #[Rule("required_if:different_shipping_address,true|string|max:255")]
    public string $shipping_first_name = "";

    #[Rule("required_if:different_shipping_address,true|string|max:255")]
    public string $shipping_last_name = "";

    #[Rule("required_if:different_shipping_address,true|string|max:255")]
    public string $shipping_address = "";

    #[Rule("required_if:different_shipping_address,true|string|max:255")]
    public string $shipping_city = "";

    #[Rule("required_if:different_shipping_address,true|string|max:255")]
    public string $shipping_state = "";

    #[Rule("required_if:different_shipping_address,true|string|max:255")]
    public string $shipping_country = "";

    #[Rule("required_if:different_shipping_address,true|string|max:20")]
    public string $shipping_postal_code = "";

    #[Rule("required_if:different_shipping_address,true|string|max:255")]
    public string $shipping_area = "";

    #[Rule("required_if:different_shipping_address,true|string|max:255")]
    public string $shipping_building = "";

    #[Rule("required_if:different_shipping_address,true|string|max:255")]
    public string $shipping_flat = "";

    // Additional Options
    #[Rule("boolean")]
    public bool $create_account = false;

    #[Rule("nullable|string|max:1000")]
    public ?string $order_notes = null;

    #[Rule("accepted")]
    public bool $terms_accepted = false;

    // Payment Information
    #[Rule("required|in:card,tabby")]
    public string $payment_method = PaymentMethod::CARD->value;

    #[Rule("nullable|string|max:50")]
    public ?string $coupon_code = null;

    public function setFromUser($user): void
    {
        if ($user) {
            $this->email = $user->email;
            $this->billing_first_name = $user->first_name ?? "";
            $this->billing_last_name = $user->last_name ?? "";
            $this->shipping_first_name = $user->first_name ?? "";
            $this->shipping_last_name = $user->last_name ?? "";

            if ($user->customer && $user->customer->defaultAddress) {
                $this->setFromAddress($user->customer->defaultAddress);
            }
        }
    }

    public function setFromAddress($address): void
    {
        $this->phone = $address->phone;

        $this->billing_first_name = $address->first_name;
        $this->billing_last_name = $address->last_name;
        $this->billing_address = $address->address;
        $this->billing_city = $address->city;
        $country = Country::select(["id", "iso2"])
            ->where("iso2", $address->country)
            ->first();
        $state = State::where("name", $address->state)
            ->where("country_id", $country->id)
            ->first();
        $this->billing_state = $state->id;
        $this->billing_country = $address->country;
        $this->billing_postal_code = $address->postal_code;
        $this->billing_area = $address->area;
        $this->billing_building = $address->building;
        $this->billing_flat = $address->flat;
    }

    public function getShippingAddressData(): array
    {
        if (!$this->different_shipping_address) {
            return $this->getBillingAddressData();
        }

        return [
            "type" => AddressType::SHIPPING->value,
            "first_name" => $this->shipping_first_name,
            "last_name" => $this->shipping_last_name,
            "phone" => $this->phone,
            "address" => $this->shipping_address,
            "city" => $this->shipping_city,
            "state" => $this->shipping_state,
            "country" => $this->shipping_country,
            "postal_code" => $this->shipping_postal_code,
            "area" => $this->shipping_area,
            "building" => $this->shipping_building,
            "flat" => $this->shipping_flat,
        ];
    }

    public function getBillingAddressData(): array
    {
        return [
            "type" => AddressType::BILLING->value,
            "first_name" => $this->billing_first_name,
            "last_name" => $this->billing_last_name,
            "phone" => $this->phone,
            "address" => $this->billing_address,
            "city" => $this->billing_city,
            "state" => $this->billing_state,
            "country" => $this->billing_country,
            "postal_code" => $this->billing_postal_code,
            "area" => $this->billing_area,
            "building" => $this->billing_building,
            "flat" => $this->billing_flat,
        ];
    }

    public function updated($field): void
    {
        // When different_shipping_address is set to false, copy billing address to shipping
        if (
            $field === "different_shipping_address" &&
            !$this->different_shipping_address
        ) {
            $this->shipping_first_name = $this->billing_first_name;
            $this->shipping_last_name = $this->billing_last_name;
            $this->shipping_address = $this->billing_address;
            $this->shipping_city = $this->billing_city;
            $this->shipping_state = $this->billing_state;
            $this->shipping_country = $this->billing_country;
            $this->shipping_postal_code = $this->billing_postal_code;
            $this->shipping_area = $this->billing_area;
            $this->shipping_building = $this->billing_building;
            $this->shipping_flat = $this->billing_flat;
        }
    }

    public function reset(...$properties): void
    {
        parent::reset(...$properties);

        if (empty($properties)) {
            $this->different_shipping_address = false;
            $this->create_account = false;
            $this->terms_accepted = false;
            $this->payment_method = "card";
            $this->coupon_code = null;
            $this->order_notes = null;
        }
    }
}
