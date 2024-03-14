<?php

return [
    'ADMIN'             => env('EASYADMIN.ADMIN', 'admin'),
    'CAPTCHA'           => env('EASYADMIN.CAPTCHA', false),
    'IS_DEMO'           => env('EASYADMIN.IS_DEMO', false),
    'CDN'               => env('EASYADMIN.CDN', ''),
    'EXAMPLE'           => env('EASYADMIN.EXAMPLE', true),
    'IS_CSRF'           => env('EASYADMIN.IS_CSRF', false),
    'STATIC_PATH'       => env('EASYADMIN.STATIC_PATH', '/static'),
    'OSS_STATIC_PREFIX' => env('EASYADMIN.OSS_STATIC_PREFIX', 'static_easyadmin'),
];
