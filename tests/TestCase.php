<?php

namespace Laraditz\Action\Tests;

use Laraditz\Action\ActionServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [ActionServiceProvider::class];
    }
}
