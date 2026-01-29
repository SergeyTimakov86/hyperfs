<?php

declare(strict_types=1);

use App\Shared\Infra\Middleware\RequestContext;
use Hyperf\Context\Context;

if (!function_exists('current_path')) {
    function current_path(): string
    {
        return Context::get(RequestContext::REQUEST_URI_PATH, '/');
    }
}

function shortClassName(object|string $class): string
{
    $className = is_object($class) ? $class::class : $class;
    $pos = strrpos($className, '\\');

    return $pos === false ? $className : substr($className, $pos + 1);
}
