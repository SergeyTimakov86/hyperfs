<?php

declare(strict_types=1);

use App\Shared\Middleware\RequestContext;
use Hyperf\Context\Context;

if (!function_exists('current_path')) {
    function current_path(): string
    {
        return Context::get(RequestContext::REQUEST_URI_PATH, '/');
    }
}
