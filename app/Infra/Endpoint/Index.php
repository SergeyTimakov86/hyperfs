<?php

declare(strict_types=1);

namespace App\Infra\Endpoint;

use App\Infra\Endpoint;
use Psr\Http\Message\ResponseInterface;

final class Index extends Endpoint
{
    public function __invoke(): ResponseInterface
    {
        $user = $this->request->input('user', 'Hyperf');
        $method = $this->request->getMethod();

        return $this->render('index', []);
    }
}
