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
use Tmdb\Laravel\Cache\DoctrineCacheBridge;

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
            if (!config()->has('tmdb.cache.handler')) {
                $repository = app('cache')->store(config('tmdb.cache_store'));

                if (!empty(config('tmdb.cache_tag'))) {
                    $repository = $repository->tags(config('tmdb.cache_tag'));
                }

                config()->set('tmdb.cache.handler', new DoctrineCacheBridge($repository));
            }

            $token = new ApiToken(config('tmdb.api_key'));
            return new Client($token, config('tmdb.options'));
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
