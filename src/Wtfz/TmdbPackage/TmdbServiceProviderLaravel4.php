<?php
/**
 * @package Wtfz_TmdbPackage
 * @author Michael Roterman <michael@wtfz.net>
 * @copyright (c) 2014, Michael Roterman
 */
namespace Wtfz\TmdbPackage;

use Illuminate\Support\ServiceProvider;
use Symfony\Component\Process\Exception\RuntimeException;
use Tmdb\ApiToken;
use Tmdb\Client;

class TmdbServiceProviderLaravel4 extends ServiceProvider {

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->package('wtfzdotnet/tmdb-package');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('Tmdb\Client', function() {
            $config = $this->app['config']->get('tmdb-package::tmdb');

            // Register the client using the key and options from config
            $token = new ApiToken($config['api_key']);
            return new Client($token, $config['options']);
        });
    }
}
