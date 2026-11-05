<?php

return [
    'base_url' => env('PARSER_BASE_URL', 'https://generativelanguage.googleapis.com/v1beta/openai'),
    'api_key'  => env('PARSER_API_KEY', ''),
    'model'    => env('PARSER_MODEL', 'gemini-2.0-flash'),
];
