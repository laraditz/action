<?php

namespace Laraditz\Action;

use BadMethodCallException;
use Illuminate\Foundation\Bus\PendingDispatch;

class Action
{
    public static function dispatch(mixed ...$args): PendingDispatch
    {
        return dispatch(new static(...$args));
    }

    public function data(): array
    {
        $body = [];
        $class = new \ReflectionClass(static::class);
        $constructor = $class->getConstructor();

        if ($constructor === null) {
            return $body;
        }

        foreach ($constructor->getParameters() as $property) {
            if ($property->allowsNull() === false || $this->{$property->name}) {
                $body += [$property->name => $this->{$property->name}];
            }
        }

        return $body;
    }

    public function __call(string $method, array $arguments): mixed
    {
        if ($method === 'run') {
            return app()->call([$this, 'handle']);
        }

        throw new BadMethodCallException(sprintf(
            'Method %s::%s does not exist.',
            static::class,
            $method
        ));
    }

    public static function __callStatic(string $method, array $arguments): mixed
    {
        if ($method === 'run') {
            return (new static(...$arguments))->run();
        }

        throw new BadMethodCallException(sprintf(
            'Method %s::%s does not exist.',
            static::class,
            $method
        ));
    }
}
