<?php

namespace LaravelJsonApi\OpenApiSpec\Builders;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Info;
use LaravelJsonApi\OpenApiSpec\Descriptors\Server;

class InfoBuilder extends Builder
{
    public function build(): Info
    {
        return (new Server($this->generator))->info();
    }
}
