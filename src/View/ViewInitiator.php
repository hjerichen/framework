<?php

namespace HJerichen\Framework\View;

use HJerichen\Framework\Response\HtmlResponse;
use HJerichen\Framework\Response\Response;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 * @psalm-api
 */
class ViewInitiator
{
    public function __construct(
        private readonly View $view,
        private readonly string $template
    ) {
    }

    public function execute(): Response
    {
        $content = $this->view->parseTemplate($this->template);
        return new HtmlResponse($content);
    }
}