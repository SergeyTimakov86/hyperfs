<?php

declare(strict_types=1);

namespace App\Infra;

use Psr\Http\Message\ResponseInterface;

abstract class AdminEndpoint extends Endpoint
{
    public function __invoke(): array|ResponseInterface
    {
        $result = $this->payload();

        if (!static::rendering()) {
            return $result;
        }

        $path = strtolower(str_replace('App\\Infra\\Endpoint\\Admin\\', '', static::class));

        return $this->render(
            'admin/' . str_replace('\\', '/', $path),
            $result
        );
    }

    abstract protected function payload(): array;

    protected static function rendering(): bool
    {
        return false;
    }
}
