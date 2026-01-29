<?php

declare(strict_types=1);

use App\Shared\Infra\Middleware\RequestContext;

return [
    'http' => [
        RequestContext::class,
    ],
];
