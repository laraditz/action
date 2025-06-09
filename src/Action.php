<?php

namespace Laraditz\Action;

use BadMethodCallException;

abstract class Action
{
    public function data(): array
    {
        $body = [];
        $class = new \ReflectionClass(static::class);

        $constructor = $class->getConstructor();

        foreach ($constructor->getParameters() as $property) {

            if ($property->allowsNull() === false || $this->{$property->name}) {

                $body += [$property->name => $this->{$property->name}];
            }
        }

        return $body;
    }

    public function __call($method, $arguments)
    {
        if ($method === 'run') {
            return $this->handle(...$arguments);
        }

        throw new BadMethodCallException(sprintf(
            'Method %s::%s does not exist.',
            static::class,
            $method
        ));
    }


    public static function __callStatic($method, $arguments)
    {
        if ($method === 'run') {
            return (new static(...$arguments))->handle();
        }

        return (new static)->$method(...$arguments);
    }
}
