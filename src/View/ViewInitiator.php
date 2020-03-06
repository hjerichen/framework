<?php

namespace HJerichen\Framework\View;

use HJerichen\Framework\Response\HtmlResponse;
use HJerichen\Framework\Response\Response;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class ViewInitiator
{
    /**
     * @var View
     */
    private $view;
    /**
     * @var string
     */
    private $template;

    public function __construct(View $view, string $template)
    {
        $this->view = $view;
        $this->template = $template;
    }

    public function execute(): Response
    {
        $content = $this->view->parseTemplate($this->template);
        return new HtmlResponse($content);
    }
}