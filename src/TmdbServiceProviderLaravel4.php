<?php
/**
 * @package php-tmdb\laravel
 * @author Mark Redeman <markredeman@gmail.com>
 * @copyright (c) 2014, Mark Redeman
 */
namespace Tmdb\Laravel;

use Illuminate\Support\ServiceProvider;

class TmdbServiceProviderLaravel4 extends ServiceProvider {

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->package('wtfzdotnet/tmdb-package', null, __DIR__);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('Tmdb\Laravel\Adapters\EventDispatcherAdapter', 'Tmdb\Laravel\Adapters\EventDispatcherLaravel4');
    }

    public function config()
    {
        return $this->app['config']->get('tmdb-package::tmdb');
    }
}
