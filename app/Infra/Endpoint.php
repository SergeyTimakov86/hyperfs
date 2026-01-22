<?php

declare(strict_types=1);

namespace App\Infra;

use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\View\RenderInterface;
use Psr\Http\Message\ResponseInterface;

abstract class Endpoint
{
    #[Inject]
    protected RequestInterface $request;

    #[Inject]
    protected RenderInterface $renderer;

    protected function isPost(): bool
    {
        return $this->request->getMethod() === 'POST';
    }

    protected function render(string $template, array $data = []): ResponseInterface
    {
        return $this->renderer->render($template, $data);
    }
}
