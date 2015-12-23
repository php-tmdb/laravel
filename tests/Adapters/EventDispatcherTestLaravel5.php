<?php
/**
 * @package php-tmdb\laravel
 * @author Mark Redeman <markredeman@gmail.com>
 * @copyright (c) 2014, Mark Redeman
 */
namespace Tmdb\Laravel\Adapters\Tests;

use Tmdb\Laravel\Adapters\EventDispatcherLaravel5 as AdapterDispatcher;

class EventDispatcherTestLaravel5 extends AbstractEventDispatcherTest
{
    protected function createEventDispatcher()
    {
        $this->laravel = $this->prophesize('Illuminate\Contracts\Events\Dispatcher');
        $this->symfony = $this->prophesize('Symfony\Component\EventDispatcher\EventDispatcher');

        return new AdapterDispatcher(
            $this->laravel->reveal(),
            $this->symfony->reveal()
        );
    }
}