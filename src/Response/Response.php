<?php declare(strict_types=1);

namespace HJerichen\Framework\Response;

use HJerichen\Framework\Response\Exception\ResponseException;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
abstract class Response
{
    private ResponseException $exception;

    public function __construct(
        private readonly string $content = ''
    ) {
    }

    public function getContent(): string
    {
        return $this->content;
    }

    abstract public function getMimeType(): string;

    public function setException(ResponseException $exception): void
    {
        $this->exception = $exception;
    }

    public function getException(): ?ResponseException
    {
        return $this->exception ?? null;
    }
}