<?php

return [
    "media" => [
        "optimized" => "optimized",
        "thumbnail" => "thumbnail",
        "featured" => "featured",
        "gallery" => "gallery",

        "product" => [
            "cover" => "cover",
        ],
        "post" => [
            "body" => "body",
            "cover" => "cover",
        ],
    ],
    "redis" => [
        "preview" => [
            "key" => "preview",
        ],
        "expire" => 60 * 60 * 24 * 30,
    ],
];
