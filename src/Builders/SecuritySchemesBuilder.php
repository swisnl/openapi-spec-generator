<?php

namespace LaravelJsonApi\OpenApiSpec\Builders;

use LaravelJsonApi\OpenApiSpec\Descriptors\Server;

class SecuritySchemesBuilder extends Builder
{
    /**
     * @return \GoldSpecDigital\ObjectOrientedOAS\Objects\SecurityScheme[]
     */
    public function build(): array
    {
        return (new Server($this->generator))->securitySchemes();
    }
}
