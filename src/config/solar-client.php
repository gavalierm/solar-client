<?php

return [
         'default' => [
              'website_pk' => env('SOLAR_WEBSITE_PK'),
              'host' => env('SOLAR_HOST'),
              'user' => env('SOLAR_USER'),
              'pass' => env('SOLAR_PASS'),
              'redirect_url' => env('SOLAR_REDIRECT_URL', '/solar/callback')
         ],
         'dev' => [
              'website_pk' => env('SOLAR_DEV_WEBSITE_PK'),
              'host' => env('SOLAR_DEV_HOST'),
              'user' => env('SOLAR_DEV_USER'),
              'pass' => env('SOLAR_DEV_PASS'),
              'redirect_url' => env('SOLAR_DEV_REDIRECT_URL')
         ],
         'public' => [
              'website_pk' => env('SOLAR_PUBLIC_WEBSITE_PK'),
              'host' => env('SOLAR_PUBLIC_HOST'),
              'user' => env('SOLAR_PUBLIC_USER'),
              'pass' => env('SOLAR_PUBLIC_PASS'),
              'redirect_url' => env('SOLAR_PUBLIC_REDIRECT_URL')
         ],

         'order' => [
            'by' => 'DESC',
            'as' => 'created_at',
         ],
    ];
