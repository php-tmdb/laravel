<?php
/**
 * @package Wtfz_TmdbPackage
 * @author Michael Roterman <michael@wtfz.net>
 * @copyright (c) 2014, Michael Roterman
 */
namespace Wtfz\TmdbPackage;

use Illuminate\Support\ServiceProvider;
use Tmdb\ApiToken;
use Tmdb\Client;

class TmdbServiceProviderLaravel5 extends ServiceProvider {

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
        $this->publishes([
            $this->defaultConfig() => config_path('tmdb.php'),
        ]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->setupConfiguration();

        $this->app->bind('Tmdb\Client', function() {
            $config = $this->app['config']->get('tmdb');

            // Register the client using the key and options from config
            $token = new ApiToken($config['api_key']);
            return new Client($token, $config['options']);
        });
	}

    /**
     * Setup configuration
     *
     * @return  void
     */
    private function setupConfiguration()
    {
        $config = $this->defaultConfig();
        $this->mergeConfigFrom($config, 'tmdb');
    }

    /**
     * Returns the default configuration path
     * @return string
     */
    private function defaultConfig()
    {
        return __DIR__.'/../../config/tmdb.php';
    }
}
