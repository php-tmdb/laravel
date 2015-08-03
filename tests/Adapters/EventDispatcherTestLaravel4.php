<?php
/**
 * @package php-tmdb\laravel
 * @author Mark Redeman <markredeman@gmail.com>
 * @copyright (c) 2014, Mark Redeman
 */
namespace Tmdb\Laravel\Adapters\Tests;

use Tmdb\Laravel\Adapters\EventDispatcherLaravel4 as AdapterDispatcher;
use Illuminate\Events\Dispatcher as LaravelDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcher as SymfonyDispatcher;

use Symfony\Component\EventDispatcher\Tests\AbstractEventDispatcherTest;
use Symfony\Component\EventDispatcher\Tests\TestEventListener;
use Symfony\Component\EventDispatcher\Tests\TestWithDispatcher;

class EventDispatcherTestLaravel4 extends AbstractEventDispatcherTest
{
    private $laravelDispatcher;
    private $symfonyDispatcher;
    private $dispatcher;

    protected function setUp()
    {
        parent::setUp();
        $this->dispatcher = $this->createEventDispatcher();
        $this->listener = new TestEventListener();
    }

    protected function createEventDispatcher()
    {
        $this->laravelDispatcher = new LaravelDispatcher;
        $this->symfonyDispatcher = new SymfonyDispatcher;
        return new AdapterDispatcher(
            $this->laravelDispatcher,
            $this->symfonyDispatcher
        );
    }

    public function testTheEventIsDispatchedTroughLaravel()
    {
        $dispatched = false;
        $this->laravelDispatcher->listen('test', function ($event) use (&$dispatched) {
            $dispatched = true;
        });
        $this->dispatcher->dispatch('test');
        $this->assertTrue($dispatched);
    }

    public function testItKnowsIfTheLaravelDispatcherHasListeners()
    {
        $this->laravelDispatcher->listen('pre.foo', function() {});
        $this->laravelDispatcher->listen('post.foo', function() {});

        $this->assertTrue($this->dispatcher->hasListeners('pre.foo'));
        $this->assertTrue($this->dispatcher->hasListeners('post.foo'));
    }


    /**
     * The following two tests are copies of the same tests from the
     * AbstractEventDispatcherTest, however instead of asserting
     * that the event's dispatcher is the adapter dispatcher, we
     * assert that it is the Symfony Dispatcher
     */

    public function testLegacyEventReceivesTheDispatcherInstance()
    {
        $dispatcher = null;
        $this->dispatcher->addListener('test', function ($event) use (&$dispatcher) {
            $dispatcher = $event->getDispatcher();
        });
        $this->dispatcher->dispatch('test');
        $this->assertSame($this->symfonyDispatcher, $dispatcher);
    }

    public function testEventReceivesTheDispatcherInstance()
    {
        $dispatcher = null;
        $this->dispatcher->addListener('test', function ($event) use (&$dispatcher) {
            $dispatcher = $event->getDispatcher();
        });
        $this->dispatcher->dispatch('test');
        $this->assertSame($this->symfonyDispatcher, $dispatcher);
    }

    public function testEventReceivesTheDispatcherInstanceAsArgument()
    {
        $listener = new TestWithDispatcher();
        $this->dispatcher->addListener('test', array($listener, 'foo'));
        $this->assertNull($listener->name);
        $this->assertNull($listener->dispatcher);
        $this->dispatcher->dispatch('test');
        $this->assertEquals('test', $listener->name);
        $this->assertSame($this->symfonyDispatcher, $listener->dispatcher);
    }

}