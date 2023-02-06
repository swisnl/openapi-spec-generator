<?php

namespace LaravelJsonApi\OpenApiSpec\Contracts\Descriptors;

use GoldSpecDigital\ObjectOrientedOAS\Objects\RequestBody;

interface RequestDescriptor extends Descriptor
{
    /**
     * @return \GoldSpecDigital\ObjectOrientedOAS\Objects\RequestBody
     */
    public function request(): RequestBody;
}
