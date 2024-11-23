<?php

namespace App\Services\Post;

use App\Models\Post;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileCannotBeAdded;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;
use Spatie\MediaLibrary\MediaCollections\Exceptions\InvalidBase64Data;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class PostImageManagerService
{
    public function cleanupUnusedImages(Post $post): void
    {
        $mediaItems = $post->getMedia(config("const.media.post.body"));
        foreach ($mediaItems as $image) {
            $availableUrl = $image->getAvailableUrl([
                config("const.media.optimized"),
            ]);
            if (!Str::contains($post->getOriginal("body"), $availableUrl)) {
                $image->delete();
            }
        }
    }

    public function editBody(Post $post): string
    {
        try {
            return app(DOMManipulator::class)->processImages(
                $post->getOriginal("body"),
                function ($img) use ($post) {
                    $url = $img->getAttribute("src");
                    if (Str::contains($url, "body-attachments")) {
                        $newUrl = $this->getNewUrl($url);
                        $media = $this->addMediaFromUrl($post, $newUrl);
                        $this->updateImageSrc($img, $media);
                    } elseif (Str::contains($url, "base64")) {
                        $media = $this->addMediaFromBase64($post, $url);
                        $this->updateImageSrc($img, $media);
                    }
                }
            );
        } catch (FileCannotBeAdded | FileDoesNotExist | FileIsTooBig | InvalidBase64Data $e) {
            logger($e);
            return $post->body;
        }
    }

    private function getNewUrl(string $url): string
    {
        return storage_path(
            str_replace("/storage", "app/public", parse_url($url)["path"])
        );
    }

    /**
     * @throws FileIsTooBig
     * @throws FileDoesNotExist
     */
    private function addMediaFromUrl(Post $post, string $url): Media
    {
        return $post
            ->addMedia($url)
            ->toMediaCollection(config("const.media.post.body"));
    }

    private function updateImageSrc($img, ?Media $media): void
    {
        if ($media) {
            $img->setAttribute("src", $media->getUrl());
        }
    }

    /**
     * @throws FileCannotBeAdded
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     * @throws InvalidBase64Data
     */
    private function addMediaFromBase64(Post $post, string $data): Media
    {
        return $post
            ->addMediaFromBase64($data)
            ->toMediaCollection(config("const.media.post.body"));
    }
}
