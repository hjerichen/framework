<?php

namespace HJerichen\Framework\Response;

use HJerichen\Framework\Mime\MimeType;
use HJerichen\Framework\Response\Exception\ResponseException;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class Response
{
    /**
     * @var string
     */
    private $content;
    /**
     * @var string
     */
    private $mimeType;
    /**
     * @var ResponseException
     */
    private $exception;

    public function __construct(string $content = '')
    {
        $this->content = $content;
        $this->mimeType = MimeType::TEXT_PlAIN;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getMimeType(): string
    {
        return $this->mimeType;
    }

    public function setMimeType(string $mimeType): void
    {
        $this->mimeType = $mimeType;
    }

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