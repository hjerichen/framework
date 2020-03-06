<?php

namespace HJerichen\Framework\Route;

use HJerichen\Framework\ObjectFactory;

interface RouteInterface
{
    public function getUri(): string;

    public function getInstantiatedClass(ObjectFactory $objectFactory): object;

    public function getMethod(): string;
}