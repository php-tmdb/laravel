<?php

namespace Tmdb\Laravel\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Tmdb\Laravel\Facades\Tmdb;
use Tmdb\Laravel\TmdbServiceProvider;

abstract class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            TmdbServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'Tmdb' => Tmdb::class,
        ];
    }
}
