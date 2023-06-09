<?php

namespace LaravelJsonApi\OpenApiSpec\Builders;

use LaravelJsonApi\OpenApiSpec\Descriptors\Server;

class SecurityBuilder extends Builder
{
    /**
     * @return \GoldSpecDigital\ObjectOrientedOAS\Objects\SecurityRequirement[]
     */
    public function build(): array
    {
        return (new Server($this->generator))->security();
    }
}
