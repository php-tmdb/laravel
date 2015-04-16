<?php
/**
 * @package php-tmdb\laravel
 * @author Mark Redeman <markredeman@gmail.com>
 * @copyright (c) 2014, Mark Redeman
 */
namespace Tmdb\Laravel;

use Illuminate\Support\ServiceProvider;

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

        $this->app->bind('Tmdb\Laravel\Adapters\EventDispatcherAdapter', 'Tmdb\Laravel\Adapters\EventDispatcherLaravel5');
    }

    /**
     * Get the TMDB configuration from the config repository
     *
     * @return array
     */
    public function config()
    {
        return $this->app['config']->get('tmdb');
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
     *
     * @return string
     */
    private function defaultConfig()
    {
        return __DIR__ . '/config/tmdb.php';
    }
}
