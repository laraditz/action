<?php

namespace Laraditz\Action;

use BadMethodCallException;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Laraditz\Action\Traits\Resolvable;

class Action
{
    use Resolvable;

    public $attributes = [];

    public function __construct()
    {
        if (func_num_args() > 0) {
            $this->resolveConstructorAttributes(...func_get_args());
        }
    }

    protected function handleNow(array $attributes = [])
    {
        $this->fill($attributes);
        $this->resolveRules();

        if (method_exists($this, 'handle')) {
            return $this->resolveMethod($this, 'handle');
        }
    }

    public function fill(array $attributes)
    {
        $this->attributes = array_merge($this->attributes, $attributes);

        return $this;
    }

    public function all()
    {
        return $this->attributes;
    }

    public function validated()
    {
        return $this->validator->validated();
    }

    public function __set($key, $value)
    {
        Arr::set($this->attributes, $key, $value);
    }

    public function __get($key)
    {
        return Arr::get($this->attributes, $key, null);
    }

    public function __invoke(array $attributes = [])
    {
        if (app(Request::class)->all()) {
            $attributes = array_merge($attributes, app(Request::class)->all());
        }

        return $this->now($attributes);
    }

    public function __call($method, $arguments)
    {
        if ($method === 'now' || $method === 'dispatch') {
            return $this->handleNow(...$arguments);
        }

        throw new BadMethodCallException(sprintf(
            'Method %s::%s does not exist.',
            static::class,
            $method
        ));
    }

    public static function __callStatic($method, $arguments)
    {
        if ($method === 'now' || $method === 'dispatch') {
            return (new static(...$arguments))->handleNow();
        }

        return (new static)->$method(...$arguments);
    }
}
