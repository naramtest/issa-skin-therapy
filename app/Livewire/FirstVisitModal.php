<?php

namespace App\Livewire;

use App\Enums\SubscriberStatus;
use App\Mail\NewsletterEmail;
use App\Models\Subscriber;
use App\Rules\TurnstileCheckRule;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class FirstVisitModal extends Component
{
    public string $email = "";
    public string $turnstileToken = "";
    public bool $showModel = false;

    public function mount()
    {
        // Check if user is a first-time visitor
        if (!Cookie::has("visited")) {
            $this->showModel = true;
            // Set cookie for 30 days
            Cookie::queue("visited", true, 43200);
        }
    }

    public function subscribe()
    {
        $this->validate([
            "email" => "required|email",
            "turnstileToken" => ["required", new TurnstileCheckRule()],
        ]);

        $subscriber = Subscriber::where("email", $this->email)->first();

        if ($subscriber) {
            if ($subscriber->status == SubscriberStatus::ACTIVE) {
                $this->closeAndDispatch(
                    message: __(
                        "store.You are already on the list to receive our newsletter"
                    ),
                    type: "success"
                );
                return;
            } elseif ($this->isSubscriptionRateLimited($subscriber)) {
                $this->closeAndDispatch(
                    message: __(
                        "store.hitting the subscribe button a little too fast"
                    ),
                    type: "error"
                );
                return;
            }
        } else {
            $subscriber = $this->createNewSubscriber();
            if (!$subscriber->save()) {
                $this->closeAndDispatch(
                    message: __(
                        "store.There seems to be a temporary issue with subscriptions"
                    ),
                    type: "error"
                );
                return;
            }
        }

        $this->sendEmail($subscriber->email, $subscriber->token);
        $this->closeAndDispatch(
            message: __(
                "store.Check your email to activate and start receiving our newsletter"
            ),
            type: "success"
        );
    }

    /**
     * @param $message
     * @param $type
     * @return void
     */
    public function closeAndDispatch($message, $type): void
    {
        $this->dispatch("close-modal");
        $this->dispatch("alert", $type, $message);
    }

    protected function isSubscriptionRateLimited(Subscriber $subscriber): bool
    {
        return $subscriber->sent_at and
            now()->diffInHours($subscriber->sent_at) < 24 &&
                $subscriber->sent_attempts >= 3;
    }

    protected function createNewSubscriber(): ?Subscriber
    {
        $subscriber = new Subscriber();
        $token = hash("sha256", time());

        $subscriber->email = $this->email;
        $subscriber->token = $token;
        $subscriber->status = SubscriberStatus::PENDING;
        return $subscriber;
    }

    public function sendEmail($email, string $token): void
    {
        Mail::to($email)->queue(
            new NewsletterEmail(
                route("subscriber_verify", [
                    "email" => $email,
                    "token" => $token,
                ])
            )
        );
    }

    public function render()
    {
        return view("livewire.first-visit-modal");
    }
}
