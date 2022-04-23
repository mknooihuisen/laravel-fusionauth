<?php

/*
 * Configuration for the Laravel-FusionAuth authentication scaffolding
 */
return [

    /* FusionsAuth's base url is the path to the root of the FusionAuth server */
    'base_url' => env('FUSIONAUTH_BASE_URL'),

    /* The API Key is the Client Secret of the OAuth application in FusionAuth */
    'api_key' => env('FUSIONAUTH_API_KEY'),

    /* The App ID is the Client ID for the  */
    'app_id' => env('FUSIONAUTH_APP_ID'),
];
