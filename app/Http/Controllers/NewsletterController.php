<?php

namespace App\Http\Controllers;

use App\Enums\SubscriberStatus;
use App\Models\Subscriber;

class NewsletterController extends Controller
{
    public function verify($token, $email)
    {
        $subscriber_data = Subscriber::where("email", $email)->first();
        if (
            !$subscriber_data or
            $subscriber_data->token and $subscriber_data->token != $token
        ) {
            return redirect()
                ->route("storefront.index")
                ->with(
                    "error",
                    __(
                        "store.An error occurred while verifying your subscription. Please contact our support team"
                    )
                );
        }
        if ($subscriber_data->status == SubscriberStatus::ACTIVE) {
            return redirect()
                ->route("storefront.index")
                ->with(
                    "success",
                    __(
                        "store.You are already on the list to receive our newsletter"
                    )
                );
        }
        $subscriber_data->token = "";
        $subscriber_data->status = SubscriberStatus::ACTIVE;
        $subscriber_data->update();

        return redirect()
            ->route("storefront.index")
            ->with("success", __("store.Your subscription is now active"));
    }
}
