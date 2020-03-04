<?php
/** @noinspection PhpUnused */
/** @noinspection PhpUnusedParameterInspection */

namespace HJerichen\Framework\TestHelpers;

use HJerichen\Framework\Response\Response;
use HJerichen\Framework\View\View;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class TestController
{
    /**
     * @var View
     */
    private $view;

    public function __construct(View $view)
    {
        $this->view = $view;
    }

    public function emptyResponse(): Response
    {
        return new Response();
    }

    public function simpleResponse(TestControllerDependency $dependency): Response
    {
        return new Response('simple');
    }

    public function testParameterResponse(int $id): Response
    {
        return new Response($id);
    }

    public function testTemplateParsing(): Response
    {
        $content = $this->view->parseTemplate('test');
        return new Response($content);
    }

    public function testTemplateParsingWithParameter(string $name): Response
    {
        $parameters = ['name' => $name];
        $content = $this->view->parseTemplate('test', $parameters);
        return new Response($content);
    }
}