<?php

namespace App\Livewire;

use App\Mail\ContactFormSubmission;
use App\Models\ContactMessage;
use App\Rules\TurnstileCheckRule;
use Livewire\Component;
use Mail;

class ContactForm extends Component
{
    public string $name = "";
    public string $email = "";
    public string $phone_number = "";
    public string $subject = "";
    public string $message = "";
    public string $turnstileToken = "";

    public bool $success = false;

    public function submitForm()
    {
        $this->validate([
            "name" => "required|string|max:100",
            "email" => "required|email|max:100",
            "phone_number" => "required|string|max:20",
            "subject" => "required|string|max:100",
            "message" => "required|string|max:1000",
            "turnstileToken" => ["required", new TurnstileCheckRule()],
        ]);

        // 1. Store in database
        $contactMessage = ContactMessage::create([
            "name" => $this->name,
            "email" => $this->email,
            "phone" => $this->phone_number,
            "subject" => $this->subject,
            "message" => $this->message,
            "ip_address" => request()->ip(),
        ]);

        // 2. Send email notification
        //TODO: update from admin panel
        if (\App::isProduction()) {
            Mail::to("info@issaskintherapy.com")->queue(
                new ContactFormSubmission($contactMessage)
            );
        }
        $this->dispatch(
            "fb-event",
            type: "Lead",
            params: [
                "content_name" => "Contact Form",
                "content_category" => "Contact",
                "status" => "complete",
            ]
        );

        // 3. Show success message
        $this->reset([
            "name",
            "email",
            "phone_number",
            "subject",
            "message",
            "turnstileToken",
        ]);
        $this->success = true;
    }

    public function render()
    {
        return view("livewire.contact-form");
    }
}
