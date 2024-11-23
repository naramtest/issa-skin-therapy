<?php

namespace App\Services\Post;

use App;
use App\Helpers\Posts\PostRedis;
use App\Models\Post;
use Filament\Resources\Pages\EditRecord;
use Livewire\Attributes\On;
use Livewire\Attributes\Renderless;

class AutoEditPost extends EditRecord
{
    // TODO: add this to create record
    //    TODO: show an alert in the top of the page and let the user choose between switching to draft or remove it
    // TODO: if there is an old draft save the new one with different status or something
    public function mount(int|string $record): void
    {
        //        TODO : show when saved in the top of the form and check how to clear interval
        parent::mount($record);
        $this->startAutoSave();
    }

    public function startAutoSave(): void
    {
        if (App::isProduction()) {
            //        TODO: save only if user is typing
            $this->js("setInterval(function () {
                                    Livewire.dispatch('post-created');
                                }, 5000)");
        }
    }

    #[On("post-created")]
    public function postCreated(): void
    {
        $this->autoUpdate();

        $this->skipRender();
    }

    #[Renderless]
    public function autoUpdate(): void
    {
        /** @var Post $post */
        $post = $this->record;
        PostRedis::save($post, $this->data);
    }
}
