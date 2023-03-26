<?php
/** @noinspection PhpUnused */
/** @noinspection PhpUnusedParameterInspection */
declare(strict_types=1);


namespace HJerichen\Framework\Test\Helpers;

use HJerichen\Framework\Request\Request;
use HJerichen\Framework\Response\HtmlResponse;
use HJerichen\Framework\Response\Response;
use HJerichen\Framework\View\View;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 * @psalm-api
 */
class TestController
{
    public function __construct(
        private readonly View $view
    ) {
    }

    public function emptyResponse(): Response
    {
        return new HtmlResponse();
    }

    /** @psalm-suppress PossiblyUnusedParam */
    public function simpleResponse(TestControllerDependency $dependency): Response
    {
        return new HtmlResponse('simple');
    }

    public function testParameterResponse(int $id): Response
    {
        return new HtmlResponse((string)$id);
    }

    public function testGetsRequest(Request $request): Response
    {
        $arguments = $request->getArguments();
        return new HtmlResponse("success - {$arguments['id']}");
    }

    public function testTemplateParsing(): Response
    {
        $content = $this->view->parseTemplate('test');
        return new HtmlResponse($content);
    }

    public function testTemplateParsingWithParameter(string $name): Response
    {
        $parameters = ['name' => $name];
        $content = $this->view->parseTemplate('test', $parameters);
        return new HtmlResponse($content);
    }

    public function wrongResponse(): string
    {
        return '';
    }
}