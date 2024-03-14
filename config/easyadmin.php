<?php

return [
    'ADMIN'             => env('easyadmin.ADMIN', 'admin'),
    'CAPTCHA'           => env('easyadmin.CAPTCHA', false),
    'IS_DEMO'           => env('easyadmin.IS_DEMO', false),
    'CDN'               => env('easyadmin.CDN', ''),
    'EXAMPLE'           => env('easyadmin.EXAMPLE', true),
    'IS_CSRF'           => env('easyadmin.IS_CSRF', false),
    'STATIC_PATH'       => env('easyadmin.STATIC_PATH', '/static'),
    'OSS_STATIC_PREFIX' => env('easyadmin.OSS_STATIC_PREFIX', 'static_easyadmin'),
];
