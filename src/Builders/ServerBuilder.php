<?php

namespace LaravelJsonApi\OpenApiSpec\Builders;

use LaravelJsonApi\OpenApiSpec\Descriptors\Server;

class ServerBuilder extends Builder
{
    /**
     * @return \GoldSpecDigital\ObjectOrientedOAS\Objects\Server[]
     */
    public function build(): array
    {
        return (new Server($this->generator))->servers();
    }
}
