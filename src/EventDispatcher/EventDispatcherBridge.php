<?php

namespace Tmdb\Laravel\EventDispatcher;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface as SymfonyDispatcher;
use Illuminate\Contracts\Events\Dispatcher as LaravelDispatcher;

/**
 * This adapter provides a Laravel integration for applications
 * using the Symfony EventDispatcherInterface
 * It passes any request on to a Symfony Dispatcher and only
 * uses the Laravel Dispatcher when dispatching events
 */
class EventDispatcherBridge implements SymfonyDispatcher
{
    /**
     * The Laravel Events Dispatcher
     * @var \Illuminate\Contracts\Events\Dispatcher or \Illuminate\Events\Dispatcher
     */
    protected $laravelDispatcher;

    /**
     * The Symfony Event Dispatcher
     * @var  \Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    protected $symfonyDispatcher;

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

    public function addListener(string $eventName, $listener, int $priority = 0)
    {
        $this->symfonyDispatcher->addListener($eventName, $listener, $priority);
    }

    public function addSubscriber(EventSubscriberInterface $subscriber)
    {
        $this->symfonyDispatcher->addSubscriber($subscriber);
    }

    public function removeListener(string $eventName, $listener)
    {
        $this->symfonyDispatcher->removeListener($eventName, $listener);
    }

    public function removeSubscriber(EventSubscriberInterface $subscriber)
    {
        $this->symfonyDispatcher->removeSubscriber($subscriber);
    }

    public function getListeners(string $eventName = null)
    {
        return $this->symfonyDispatcher->getListeners($eventName);
    }

    public function dispatch(object $event, string $eventName = null): object
    {
        $this->laravelDispatcher->dispatch($eventName, $event);
        return $this->symfonyDispatcher->dispatch($event, $eventName);
    }

    public function getListenerPriority(string $eventName, $listener)
    {
        return $this->symfonyDispatcher->getListenerPriority($eventName, $listener);
    }

    public function hasListeners(string $eventName = null)
    {
        return ($this->symfonyDispatcher->hasListeners($eventName) ||
            $this->laravelDispatcher->hasListeners($eventName));
    }
}
