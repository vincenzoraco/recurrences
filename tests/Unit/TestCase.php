<?php

namespace Tests\Unit;

use Orchestra\Testbench\TestCase as BaseTestCase;
use VincenzoRaco\Recurrences\RecurrencesServiceProvider;

abstract class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            RecurrencesServiceProvider::class,
        ];
    }
}
