<?php
/**
 * @package php-tmdb\laravel
 * @author Mark Redeman <markredeman@gmail.com>
 * @copyright (c) 2014, Mark Redeman
 */

namespace Tmdb\Laravel;

use Illuminate\Support\ServiceProvider;
use Tmdb\ApiToken;
use Tmdb\Client;

class TmdbServiceProvider extends ServiceProvider
{
    protected const CONFIG_PATH = __DIR__ . '/../config/tmdb.php';

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            self::CONFIG_PATH => config_path('tmdb.php'),
        ], 'config');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(self::CONFIG_PATH, 'tmdb');

        $this->app->singleton(Client::class, function ($app) {
            $config = config('tmdb');
            $options = $config['options'];

            $token = new ApiToken($config['api_key']);
            return new Client($token, $options);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            Client::class,
        ];
    }
}
