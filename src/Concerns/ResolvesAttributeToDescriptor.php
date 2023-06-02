<?php

namespace LaravelJsonApi\OpenApiSpec\Concerns;

use LaravelJsonApi\OpenApiSpec\Attributes\DescriptorsAttribute;
use LaravelJsonApi\OpenApiSpec\Route as SpecRoute;

trait ResolvesAttributeToDescriptor
{
    private function descriptorAttribute(SpecRoute $route): ?DescriptorsAttribute
    {
        [$class, $method] = $route->controllerCallable();
        try {
            $reflection = new \ReflectionClass($class);
            $methodReflection = $reflection->getMethod($method);
        } catch (\ReflectionException $exception) {
            return null;
        }

        return collect($methodReflection->getAttributes())
            ->filter(fn(\ReflectionAttribute $attribute) => $attribute->getName() === DescriptorsAttribute::class)
            ->map(function (\ReflectionAttribute $attribute) {
                return $attribute->newInstance();
            })
            ->first();
    }

    protected function actionAttributeDescriptorClass(SpecRoute $route): ?string
    {
        return $this->descriptorAttribute($route)?->actionDescriptorClass;
    }

    protected function responseAttributeDescriptorClass(SpecRoute $route): ?string
    {
        return $this->descriptorAttribute($route)?->responseDescriptorClass;
    }

    protected function requestAttributeDescriptorClass(SpecRoute $route): ?string
    {
        return $this->descriptorAttribute($route)?->requestDescriptorClass;
    }
}
