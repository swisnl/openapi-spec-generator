<?php

namespace LaravelJsonApi\OpenApiSpec\Contracts;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Parameter;

interface ExtendsParameters
{
    public function customParameters(string $endpoint): array;
}
