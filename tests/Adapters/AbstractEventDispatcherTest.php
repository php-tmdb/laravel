<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tmdb\Laravel\Adapters\Tests;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use Prophecy\Prophecy\MethodProphecy;

abstract class AbstractEventDispatcherTest extends \PHPUnit_Framework_TestCase
{
    const EVENT = 'foo';

    /**
     * @var EventDispatcher
     */
    protected $dispatcher;

    protected $laravel;
    protected $symfony;

    private $listener;

    protected function setUp()
    {
        $this->dispatcher = $this->createEventDispatcher();
    }

    abstract protected function createEventDispatcher();

    /** @test */
    public function it_dispatches_events_through_both_laravel_and_symfony()
    {
        $this->laravel->dispatch(static::EVENT, null)->shouldBeCalled();
        $this->symfony->dispatch(static::EVENT, null)->shouldBeCalled();

        $this->dispatcher->dispatch(static::EVENT);
    }

    /** @test */
    public function it_returns_the_event_returned_by_the_symfony_dispatcher()
    {
        $this->symfony->dispatch(static::EVENT, null)->willReturn('bar');
        $this->assertEquals('bar', $this->dispatcher->dispatch(static::EVENT));
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
