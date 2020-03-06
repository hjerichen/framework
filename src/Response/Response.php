<?php

namespace HJerichen\Framework\Response;

use HJerichen\Framework\Response\Exception\ResponseException;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
abstract class Response
{
    /**
     * @var string
     */
    private $content;
    /**
     * @var ResponseException
     */
    private $exception;

    public function __construct(string $content = '')
    {
        $this->content = $content;
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
        return $this->exception;
    }

    public function hasException():bool
    {
        return $this->exception !== null;
    }
}