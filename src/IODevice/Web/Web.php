<?php declare(strict_types=1);

namespace HJerichen\Framework\IODevice\Web;

use HJerichen\Collections\MixedCollection;
use HJerichen\Framework\IODevice\IODevice;
use HJerichen\Framework\Mime\MimeType;
use HJerichen\Framework\Request\Request;
use HJerichen\Framework\Response\Exception\ResponseException;
use HJerichen\Framework\Response\Response;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class Web implements IODevice
{

    public function getRequest(): Request
    {
        $uri = $this->getUri();
        $body = $this->getBody();
        $arguments = $this->getArguments();

        $response = new Request($uri, $body);
        $response->addArguments($arguments);
        return $response;
    }

    public function outputResponse(Response $response): void
    {
        $exception = $response->getException();
        if ($exception) {
            $this->outputResponseException($exception);
        } else {
            $this->outputResponseContent($response);
        }
    }

    private function getUri(): string
    {
        return parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH);
    }

    private function getArguments(): MixedCollection
    {
        $query = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_QUERY);
        $arguments = [];

        if ($query) {
            parse_str($query, $arguments);
        }

        $arguments = array_map(static fn($value) => $value === '' ? true : $value, $arguments);

        return new MixedCollection($arguments);
    }

    private function getBody(): string
    {
        return file_get_contents('php://input') ?: '';
    }

    private function outputResponseException(ResponseException $exception): void
    {
        header('HTTP/1.0 ' . $exception->getCode());
        header('Content-type: ' . MimeType::TEXT_PlAIN);
        echo $exception->getMessage();
    }

    private function outputResponseContent(Response $response): void
    {
        header('HTTP/1.0 200');
        header('Content-type: ' . $response->getMimeType());
        echo $response->getContent();
    }
}