<?php

namespace HJerichen\Framework\TestHelpers;

use HJerichen\Framework\Response\Response;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class TestController
{
    public function __construct(TestControllerDependency $dependency)
    {

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
}