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

class TmdbServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

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
        $this->app->booting(function() {
            $loader = \Illuminate\Foundation\AliasLoader::getInstance();
            $loader->alias('Tmdb', 'Wtfz\TmdbPackage\Facades\Tmdb');
        });

        $this->app['Tmdb'] = $this->app->share(function($app) {
            $config = $app['config']->get('tmdb-package::config');

            if (!array_key_exists('api_key', $config) || empty($config['api_key'])) {
                throw new \RuntimeException('The TMDB api_key should be set in your configuration.');
            }

            $token  = new ApiToken($config['api_key']);
            $client = new Tmdb($token);

            if ($config['cache']['enabled']) {
                $client->setCaching(
                    true,
                    isset($config['cache']['path']) && !empty($config['cache']['path']) ?
                        $config['cache']['path'] :
                        storage_path('tmdb')
                );
            }

            if ($config['log']['enabled']) {
                $client->setLogging(
                    true,
                    isset($config['log']['path']) && !empty($config['log']['path']) ?
                        $config['log']['path'] :
                        storage_path('logs/tmdb.log')
                );
            }

            return $client;
        });
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return ['tmdb'];
	}
}
