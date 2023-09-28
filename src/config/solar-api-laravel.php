<?php

return [
    /*
     * Using environment variables is the recommended way of
     * storing your user and pass secret. Make sure to update
     * your /.env file with your user and pass secret.
     */
    'solar_config' => [
        'host' => env('SOLAR_HOST', 'no_host'),
        'user' => env('SOLAR_USER', 'no_user'),
        'pass' => env('SOLAR_PASS', 'no_pass')
    ],

    /*
     * The default endpoint that Facebook will redirect to after
     * an authentication attempt.
     */
    'default_redirect_uri' => '/solar/callback',
    ];
