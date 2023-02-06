<?php

namespace LaravelJsonApi\OpenApiSpec\Contracts\Descriptors;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Operation;

interface ActionDescriptor extends Descriptor
{
    public function action(): Operation;
}
