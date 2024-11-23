<?php

namespace App\Helpers\Posts;

use App\Models\Post;
use Filament\Actions\Action;

class AutoSaveActions
{
    public static function publish()
    {
        return Action::make("Publish")
            ->label(__("dashboard.Publish"))
            ->color("success");
    }

    public static function show()
    {
        return Action::make("show")
            ->label(__("dashboard.Show"))
            ->color("success")
            ->url(
                fn(Post $record) => route("post.show", [
                    "post" => $record->slug,
                ]),
                true
            );
    }

    public static function save()
    {
        return Action::make("Save")->label(__("dashboard.Save"));
    }
}
