<?php

namespace App\Console\Commands;

use App\Models\Post;
use App\Services\Dom\PostBody;
use App\Services\Post\PostImageManagerService;
use Illuminate\Console\Command;

class ChangeImagesInPostBody extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "post-body:change";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Command description";

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $posts = Post::select(["id", "edited", "body", "title", "status"])
            ->with(["media"])
            ->where("edited", true)
            ->get();
        if (empty($posts)) {
            return;
        }
        foreach ($posts as $post) {
            $postImageManagerService = app(PostImageManagerService::class);
            $post->body = $postImageManagerService->editBody($post);
            $post->edited = false;
            $postImageManagerService->cleanupUnusedImages($post);
            $post->save();
        }
    }
}
