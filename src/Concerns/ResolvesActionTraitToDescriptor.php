<?php

namespace LaravelJsonApi\OpenApiSpec\Concerns;

use LaravelJsonApi\OpenApiSpec\Route as SpecRoute;

trait ResolvesActionTraitToDescriptor
{
    /**
     * @todo Get descriptors from Attributes
     */
    protected function descriptorClass(SpecRoute $route): ?string
    {
        [$class, $method] = $route->controllerCallable();
        try {
            $reflection = new \ReflectionClass($class);
            $methodReflection = $reflection->getMethod($method);

            if ($methodReflection->getDeclaringClass()->name !== $reflection->name) {
                $reflection = $methodReflection->getDeclaringClass();
            }
            $traitMethod = collect($reflection->getTraits())
                ->map(function (\ReflectionClass $trait) {
                    return $trait->getMethods();
                })
                ->flatten()
                ->mapWithKeys(
                    fn (\ReflectionMethod $method) => [$method->name => $method])
                ->get($method);
        } catch (\ReflectionException $exception) {
            return null;
        }

        return $traitMethod !== null ? $traitMethod->getDeclaringClass()->name : null;
    }
}
