<?php
/**
 * @package php-tmdb\laravel
 * @author Mark Redeman <markredeman@gmail.com>
 * @copyright (c) 2014, Mark Redeman
 */
namespace Tmdb\Laravel\Adapters;

use Symfony\Component\EventDispatcher\EventDispatcherInterface as SymfonyDispatcher;
use Illuminate\Events\Dispatcher as LaravelDispatcher;

/**
 * This adapter provides a Laravel integration for applications
 * using the Symfony EventDispatcherInterface
 * It passes any request on to a Symfony Dispatcher and only
 * uses the Laravel Dispatcher only when dispatching events
 */
class EventDispatcherLaravel4 extends EventDispatcherAdapter
{
    /**
     * Forward all methods to the Laravel Events Dispatcher
     * @param LaravelDispatcher $laravelDispatcher
     * @param SymfonyDispatcher $symfonyDispatcher
     */
    public function __construct(LaravelDispatcher $laravelDispatcher, SymfonyDispatcher $symfonyDispatcher)
    {
        $this->laravelDispatcher = $laravelDispatcher;
        $this->symfonyDispatcher = $symfonyDispatcher;
    }
}
