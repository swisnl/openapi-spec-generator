<?php

namespace LaravelJsonApi\OpenApiSpec\Contracts;

interface DescribesEndpoints
{
    public function describeEndpoint(string $endpoint): string;
}
