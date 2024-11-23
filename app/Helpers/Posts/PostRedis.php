<?php

namespace App\Helpers\Posts;

use App\Models\Post;
use Illuminate\Support\Facades\Redis;

class PostRedis
{
    public static function save(Post $post, array $data): bool
    {
        return Redis::set(
            config("const.redis.preview.key") . ":$post->id",
            json_encode($data),
            "EX",
            config("const.redis.expire")
        );
    }
}
