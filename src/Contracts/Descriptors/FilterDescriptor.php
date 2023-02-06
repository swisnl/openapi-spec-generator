<?php

namespace LaravelJsonApi\OpenApiSpec\Contracts\Descriptors;

interface FilterDescriptor extends Descriptor
{
    /**
     * @return \GoldSpecDigital\ObjectOrientedOAS\Objects\Parameter[]
     */
    public function filter(): array;
}
