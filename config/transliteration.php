<?php

// config/transliteration.php

return [
    /*
    |--------------------------------------------------------------------------
    | Custom Arabic to English Mappings
    |--------------------------------------------------------------------------
    |
    | Add your business-specific transliterations here. These will override
    | the default mappings in the service.
    |
    */
    "custom_mappings" => [
        // Add your specific customer names, addresses, or terms here
        // 'نارام' => 'Naram',
        // 'شركة النور' => 'Al Noor Company',
        // Add more as needed...
    ],

    /*
    |--------------------------------------------------------------------------
    | Field Type Formatting
    |--------------------------------------------------------------------------
    |
    | Configure how different field types should be formatted after transliteration
    |
    */
    "formatting" => [
        "name" => [
            "capitalize_words" => true,
            "handle_prefixes" => true, // Handle Al, El, Bin, Ibn
        ],
        "address" => [
            "capitalize_words" => true,
        ],
        "city" => [
            "capitalize_words" => true,
        ],
    ],
];
