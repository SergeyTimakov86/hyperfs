<?php

declare(strict_types=1);

use App\Shared\Middleware\RequestContext;

return [
    'http' => [
        RequestContext::class,
    ],
];
