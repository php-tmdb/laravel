<?php
/**
 * @package php-tmdb\laravel
 * @author Mark Redeman <markredeman@gmail.com>
 * @copyright (c) 2014, Mark Redeman
 */
namespace Tmdb\Laravel;

use Illuminate\Support\ServiceProvider;
use Tmdb\Laravel\TmdbServiceProviderLaravel4;
use Tmdb\Laravel\TmdbServiceProviderLaravel5;
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
        // Configure any bindings that are version dependent
        $this->provider->register();

        // Let the IoC container be able to make a Symfony event dispatcher
        $this->app->bind(
            'Symfony\Component\EventDispatcher\EventDispatcherInterface',
            'Symfony\Component\EventDispatcher\EventDispatcher'
        );

        // Setup default configurations for the Tmdb Client
        $this->app->singleton('Tmdb\Client', function() {
            $config = $this->provider->config();
            $options = $config['options'];

            // Use an Event Dispatcher that uses the Laravel event dispatcher
            $options['event_dispatcher'] = $this->app->make('Tmdb\Laravel\Adapters\EventDispatcherAdapter');

            // Register the client using the key and options from config
            $token = new ApiToken($config['api_key']);
            return new Client($token, $options);
        });

        // bind the configuration (used by the image helper)
        $this->app->bind('Tmdb\Model\Configuration', function() {
            $configuration = $this->app->make('Tmdb\Repository\ConfigurationRepository');
            return $configuration->load();
        });
    }

    /**
     * Register the ServiceProvider according to Laravel version
     *
     * @return \Tmdb\Laravel\Provider\ProviderInterface
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
