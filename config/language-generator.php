<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Google Translate API Endpoint
    |--------------------------------------------------------------------------
    |
    | This value determines the endpoint used to communicate with the Google
    | Translate API. You may change this value to any other endpoint if necessary.
    |
    */
    'api_endpoint' => 'https://translate.googleapis.com/translate_a/single?client=gtx',

    /*
    |--------------------------------------------------------------------------
    | Default Source Language
    |--------------------------------------------------------------------------
    |
    | This value determines the default source language code that will be used
    | when generating and translating language files if no source language is specified.
    |
    */
    'default_source_language' => 'en',

    /*
    |--------------------------------------------------------------------------
    | Default Target Languages
    |--------------------------------------------------------------------------
    |
    | This value determines the default target languages that will be used
    | when generating and translating language files if no target languages are specified.
    |
    */
    'default_target_languages' => ['fr', 'es', 'de'],

    /*
    |--------------------------------------------------------------------------
    | Retry Settings
    |--------------------------------------------------------------------------
    |
    | These values determine the retry settings when making requests to the Google
    | Translate API. You can specify the number of retries and the interval between retries.
    |
    */
    'retry_attempts' => 3,
    'retry_interval' => 100, // in milliseconds

    /*
    |--------------------------------------------------------------------------
    | Progress Bar Settings
    |--------------------------------------------------------------------------
    |
    | This value determines the format of the progress bar displayed during the
    | translation process.
    |
    */
    'progress_bar_format' => ' %current%/%max% [%bar%] %percent:3s%% -- %message%',
];
