<?php

namespace App\Livewire;

use Livewire\Component;

class FirstVisitModal extends Component
{
    public bool $showModal = true;
    public string $email = "";

    public function mount()
    {
        // Check if user is a first-time visitor
        //        if (!Cookie::has("visited")) {
        //            $this->showModal = true;
        //            // Set cookie for 30 days
        //            Cookie::queue("visited", true, 43200);
        //        }
    }

    public function subscribe()
    {
        $this->validate([
            "email" => "required|email|unique:subscribers,email",
        ]);

        // Add subscriber to your database
        //        \App\Models\Subscriber::create([
        //            'email' => $this->email
        //        ]);

        // Close modal and show success message
        $this->showModal = false;
        $this->dispatch("notify", [
            "message" =>
                "Successfully subscribed! Check your email for the discount code.",
            "type" => "success",
        ]);
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function render()
    {
        return view("livewire.first-visit-modal");
    }
}
