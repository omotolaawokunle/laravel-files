<?php

namespace Omotolaawokunle\LaravelFiles\Tests;

use Omotolaawokunle\LaravelFiles\Providers\LaravelFilesServiceProvider;
use Orchestra\Testbench\TestCase as TestbenchTestCase;

class TestCase extends TestbenchTestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelFilesServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
    }
}
