<?php

namespace Wtfz\TmdbPackage;

use Illuminate\Support\ServiceProvider;

use Wtfz\TmdbPackage\TmdbServiceProviderLaravel4;
use Wtfz\TmdbPackage\TmdbServiceProviderLaravel5;

use Tmdb\ApiToken;
use Tmdb\Client;

class TmdbServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Actual provider
     *
     * @var \Illuminate\Support\ServiceProvider
     */
    protected $provider;

    /**
     * Construct the TMDB service provider
     *
     * @return  void
     */
    public function __construct()
    {
        // Call the parent constructor with all provided arguments
        $arguments = func_get_args();
        call_user_func_array(
            [$this, 'parent::' . __FUNCTION__],
            $arguments
        );

        $this->registerProvider();
    }
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        return $this->provider->boot();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->provider->register();

        $this->app->bind('Tmdb\Client', function() {
            $config = $this->provider->config();

            // Register the client using the key and options from config
            $token = new ApiToken($config['api_key']);
            return new Client($token, $config['options']);
        });
    }

    /**
     * Register the ServiceProvider according to Laravel version
     *
     * @return \Wtfz\TmdbPackage\Provider\ProviderInterface
     */
    private function registerProvider()
    {
        $app = $this->app;

        // Pick the correct service provider for the current verison of Laravel
        $this->provider = (version_compare($app::VERSION, '5.0', '<'))
            ? new TmdbServiceProviderLaravel4($app)
            : new TmdbServiceProviderLaravel5($app);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array('tmdb');
    }
}
