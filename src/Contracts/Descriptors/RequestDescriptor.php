<?php

namespace LaravelJsonApi\OpenApiSpec\Contracts\Descriptors;

use GoldSpecDigital\ObjectOrientedOAS\Objects\RequestBody;

interface RequestDescriptor extends Descriptor
{
    public function request(): RequestBody;
}
