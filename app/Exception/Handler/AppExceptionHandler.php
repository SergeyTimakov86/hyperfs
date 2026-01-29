<?php

declare(strict_types=1);

namespace App\Exception\Handler;

use App\Shared\Domain\DomainError;
use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class AppExceptionHandler extends ExceptionHandler
{
    public function __construct(protected StdoutLoggerInterface $logger)
    {
    }

    public function handle(Throwable $throwable, ResponseInterface $response): ResponseInterface
    {
        $this->stopPropagation();

        if ($throwable instanceof DomainError) {
            return $this->jsonResponse($response, 422, $throwable->getMessage());
        }

        $this->logger->error(sprintf(
            '%s %s[%s] in %s',
            date('H:i:s'),
            $throwable->getMessage(),
            $throwable->getLine(),
            $throwable->getFile()
        ));
        $this->logger->error($throwable->getTraceAsString());

        return $this->jsonResponse($response, 500, 'Something went wrong..');
    }

    public function isValid(Throwable $throwable): bool
    {
        return true;
    }

    private function jsonResponse(ResponseInterface $response, int $status, string $message): ResponseInterface
    {
        $body = json_encode(['success' => false, 'message' => $message], JSON_UNESCAPED_UNICODE);

        return $response
            ->withStatus($status)
            ->withHeader('Content-Type', 'application/json')
            ->withBody(new SwooleStream($body));
    }
}
