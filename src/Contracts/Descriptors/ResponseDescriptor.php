<?php

namespace LaravelJsonApi\OpenApiSpec\Contracts\Descriptors;

interface ResponseDescriptor extends Descriptor
{
    /**
     * @return \GoldSpecDigital\ObjectOrientedOAS\Objects\Response[]
     */
    public function response(): array;
}
