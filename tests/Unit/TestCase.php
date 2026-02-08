<?php

namespace Tests\Unit;

use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            \VincenzoRaco\Recurrences\RecurrencesServiceProvider::class,
        ];
    }
}
