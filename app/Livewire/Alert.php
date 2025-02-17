<?php
// app/Livewire/Alert.php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;

class Alert extends Component
{
    public bool $show = false;
    public string $type = "info";
    public string $message = "";

    public function mount()
    {
        $this->checkFlashMessage();
    }

    protected function checkFlashMessage(): void
    {
        if (session()->has("error")) {
            $this->showAlert("error", session("error"));
        }
        if (session()->has("success")) {
            $this->showAlert("success", session("success"));
        }
        if (session()->has("info")) {
            $this->showAlert("info", session("info"));
        }
        if (session()->has("warning")) {
            $this->showAlert("warning", session("warning"));
        }
    }

    #[On("alert")]
    public function showAlert(string $type, string $message): void
    {
        $this->show = true;
        $this->type = $type;
        $this->message = $message;
    }

    #[On("hideAlert")]
    public function hideAlert(): void
    {
        $this->show = false;
    }

    public function render()
    {
        return view("livewire.alert");
    }
}
