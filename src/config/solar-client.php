<?php

return [
         'config' => [
                'host' => env('SOLAR_HOST', 'no_host'),
                'user' => env('SOLAR_USER', 'no_user'),
                'pass' => env('SOLAR_PASS', 'no_pass'),
                'redirect_url' => env('SOLAR_REDIRECT_URL', '/solar/callback')
         ],
         'order' => [
            'by' => 'DESC',
            'as' => 'created_at',
         ],
    ];
