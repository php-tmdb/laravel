<?php

namespace Tmdb\Laravel\Tests\EventDispatcher;

use Illuminate\Contracts\Events\Dispatcher;
use Prophecy\PhpUnit\ProphecyTrait;
use stdClass;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Tmdb\Laravel\EventDispatcher\EventDispatcherBridge;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use PHPUnit\Framework\TestCase;

class EventDispatcherBridgeTest extends TestCase
{
    use ProphecyTrait;

    const EVENT = 'foo';

    /**
     * @var EventDispatcher
     */
    protected $dispatcher;

    /**
     * The Laravel Events Dispatcher
     * @var Dispatcher or \Illuminate\Events\Dispatcher
     */
    protected $laravel;

    /**
     * The Symfony Event Dispatcher
     * @var  EventDispatcherInterface
     */
    protected $symfony;

    private $listener;

    protected function setUp(): void
    {
        $this->dispatcher = $this->createEventDispatcher();
    }

    protected function createEventDispatcher()
    {
        $this->laravel = $this->prophesize('Illuminate\Events\Dispatcher');
        $this->symfony = $this->prophesize('Symfony\Component\EventDispatcher\EventDispatcher');

        return new EventDispatcherBridge(
            $this->laravel->reveal(),
            $this->symfony->reveal()
        );
    }

    /** @test */
    public function it_dispatches_events_through_both_laravel_and_symfony()
    {
        $event = new stdClass();

        $this->laravel->dispatch(static::EVENT, $event)->shouldBeCalled();
        $this->symfony->dispatch($event, static::EVENT)->shouldBeCalled();

        $this->dispatcher->dispatch($event, static::EVENT);
    }

    /** @test */
    public function it_returns_the_event_returned_by_the_symfony_dispatcher()
    {
        $event = new stdClass();

        $this->symfony->dispatch($event, static::EVENT)->willReturn($event);
        $this->assertEquals($event, $this->dispatcher->dispatch($event, static::EVENT));
    }

    /** @test */
    public function it_adds_listeners_to_the_symfony_dispatcher()
    {
        $this->dispatcher->addListener(static::EVENT, 'listener', 1);
        $this->symfony->addListener(static::EVENT, 'listener', 1)->shouldHaveBeenCalled();
    }

    /** @test */
    public function it_adds_a_subscriber_to_the_symfony_dispatcher()
    {
        $subscriber = $this->prophesize('Symfony\Component\EventDispatcher\EventSubscriberInterface');
        $this->dispatcher->addSubscriber($subscriber->reveal());
        $this->symfony->addSubscriber($subscriber->reveal())->shouldHaveBeenCalled();
    }

    /** @test */
    public function it_removes_listeners_from_the_symfony_dispathcer()
    {
        $this->dispatcher->removeListener(static::EVENT, 'listener');
        $this->symfony->removeListener(static::EVENT, 'listener')->shouldHaveBeenCalled();
    }

    /** @test */
    public function it_removes_subscriptions_from_the_symfony_dispathcer()
    {
        $subscriber = $this->prophesize('Symfony\Component\EventDispatcher\EventSubscriberInterface');
        $this->dispatcher->removeSubscriber($subscriber->reveal());
        $this->symfony->removeSubscriber($subscriber->reveal())->shouldHaveBeenCalled();
    }

    /**
     * @test
     * We are not checking Laravel's listeners as its interface does not contain a getListeners function
     */
    public function it_gets_listeners_from_the_symfony_dispatcher()
    {
        $this->symfony->getListeners(static::EVENT)->willReturn(['bar']);
        $this->assertEquals(['bar'], $this->dispatcher->getListeners(static::EVENT));
    }

    /** @test */
    public function it_asks_the_symfony_dispatcher_if_it_has_a_listener()
    {
        $this->symfony->hasListeners(static::EVENT)->willReturn(true);
        $this->assertTrue($this->dispatcher->hasListeners(static::EVENT));
    }

    /** @test */
    public function it_asks_the_laravel_dispatcher_if_it_has_a_listener()
    {
        $this->symfony->hasListeners(static::EVENT)->willReturn(false);
        $this->laravel->hasListeners(static::EVENT)->willReturn(true);
        $this->assertTrue($this->dispatcher->hasListeners(static::EVENT));
    }

    /** @test */
    public function it_asks_both_the_symfony_and_laravel_dispatcher_if_it_has_a_listener()
    {
        $this->symfony->hasListeners(static::EVENT)->willReturn(false);
        $this->laravel->hasListeners(static::EVENT)->willReturn(false);
        $this->assertFalse($this->dispatcher->hasListeners(static::EVENT));
    }

    /**
     * @test
     * We are not checking Laravel's listeners as its interface does not contain a getListenerPriority function
     */
    public function it_asks_the_symfony_dispatcher_for_a_listeners_priority()
    {
        $this->symfony->getListenerPriority(static::EVENT, 'listener')->willReturn(100);
        $this->assertEquals(100, $this->dispatcher->getListenerPriority(static::EVENT, 'listener'));
    }
}
