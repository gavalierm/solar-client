<?php

return [
     'credentials' => [
          'website_pk' => env('SOLAR_WEBSITE_PK'),
          'host' => (env('SOLAR_DEV_HOST')) ? env('SOLAR_DEV_HOST') : env('SOLAR_HOST', null),
          'user' => (env('SOLAR_DEV_USER')) ? env('SOLAR_DEV_USER') : env('SOLAR_USER', null),
          'pass' => (env('SOLAR_DEV_PASS')) ? env('SOLAR_DEV_PASS') : env('SOLAR_PASS', null),
          'redirect_url' => (env('SOLAR_DEV_REDIRECT_URL')) ? env('SOLAR_DEV_REDIRECT_URL') : env('SOLAR_REDIRECT_URL', '/solar/callback')
     ],
     'order' => [
          'by' => 'DESC',
          'as' => 'created_at',
     ],
];
