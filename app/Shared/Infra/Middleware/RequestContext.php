<?php

declare(strict_types=1);

namespace App\Shared\Infra\Middleware;

use Hyperf\Context\Context;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class RequestContext implements MiddlewareInterface
{
    public const string REQUEST_URI_PATH = 'request.uri.path';

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        Context::set(self::REQUEST_URI_PATH, $request->getUri()->getPath());

        return $handler->handle($request);
    }
}
