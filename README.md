# Laravel Package for TMDB API Wrapper

[![License](https://poser.pugx.org/php-tmdb/laravel/license.png)](https://packagist.org/packages/php-tmdb/laravel)
[![Build Status](https://travis-ci.org/php-tmdb/laravel.svg)](https://travis-ci.org/php-tmdb/laravel)
[![Code Coverage](https://scrutinizer-ci.com/g/php-tmdb/laravel/badges/coverage.png)](https://scrutinizer-ci.com/g/php-tmdb/laravel/)
[![PHP & HHVM](https://php-eye.com/badge/php-tmdb/laravel/tested.svg)](https://php-eye.com/package/php-tmdb/laravel)

A Laravel package that provides easy access to the [php-tmdb/api](https://github.com/php-tmdb/api) TMDB (The Movie Database) API wrapper.
This package comes with a service provider that configures the `Tmdb\Client` and registers it to the IoC container.
Both Laravel 5 and 4 are supported.

[![Latest Stable Version](https://poser.pugx.org/php-tmdb/laravel/v/stable.svg)](https://packagist.org/packages/php-tmdb/laravel)
[![Latest Unstable Version](https://poser.pugx.org/php-tmdb/laravel/v/unstable.svg)](https://packagist.org/packages/php-tmdb/laravel)
[![Dependency Status](https://www.versioneye.com/php/php-tmdb:laravel/badge?style=flat)](https://www.versioneye.com/php/php-tmdb:laravel)
[![Total Downloads](https://poser.pugx.org/php-tmdb/laravel/downloads.svg)](https://packagist.org/packages/php-tmdb/laravel)

## Looking for maintainers

*We are urgently looking for new mainteners of this library, we need someone that can steer this package in the right direction for the Laravel community, we do not currently have anybody on the `php-tmdb` team that uses laravel on a daily basis. We want the default standards to be met and unit tests to be available just to verify the part of the integration with the framework works. Send an email to `php-laravel@wtfz.net` if you are interested, or are willing to help out.*

## Installation

Install Composer

```
$ curl -sS https://getcomposer.org/installer | php
$ sudo mv composer.phar /usr/local/bin/composer
```

Add the following to your require block in `composer.json` config

```
"php-tmdb/laravel": "~1.0"
```

or just run the following command in your project:

```
composer require php-tmdb/laravel
```

## Configuration

Add to your `app/config/app.php` (Laravel 4) or `config/app.php` (Laravel <5.5) the service provider:

```php
'providers' => array(
    // other service providers

    'Tmdb\Laravel\TmdbServiceProvider',
)
```

Then publish the configuration file:

### Laravel 4

```
php artisan config:publish php-tmdb/laravel
```

### Laravel 5

```
php artisan vendor:publish --provider="Tmdb\Laravel\TmdbServiceProviderLaravel5"
```

Next you can modify the generated configuration file `tmdb.php` accordingly.

That's all! Fire away!

## Usage

We can choose to either use the `Tmdb` Facade, or to use dependency injection.

### Facade example

The example below shows how you can use the `Tmdb` facade.
If you don't want to keep adding the `use Tmdb\Laravel\Facades\Tmdb;` statement in your files, then you can also add the facade as an alias in `config/app.php` file.

```php
use Tmdb\Laravel\Facades\Tmdb; // optional for Laravel ≥5.5

class MoviesController {

    function show($id)
    {
        // returns information of a movie
        return Tmdb::getMoviesApi()->getMovie($id);
    }
}
```

### Dependency injection example

```php
use Tmdb\Repository\MovieRepository;

class MoviesController {

    private $movies;

    function __construct(MovieRepository $movies)
    {
        $this->movies = $movies;
    }

    function index()
    {
        // returns information of a movie
        return $this->movies->getPopular();
    }
}
```

### Listening to events

We can easily listen to events that are dispatched using the Laravel event dispatcher that we're familiar with.
The following example listens to any request that is made and logs a message.

```php
use Log;
use Event;
use Tmdb\Event\TmdbEvents;
use Tmdb\Event\RequestEvent;

Event::listen(TmdbEvents::REQUEST, function(RequestEvent $event) {
    Log::info("A request was made to TMDB");
    // do stuff with $event
});
```

In Laravel 5 instead of using the `Event` facade we could also have used the `EventServiceProvider` to register our event listener.

### Image helper

You can easily use the `ImageHelper` by using dependency injection. The following example shows how to show the poster image of the 20 most popular movies.

```php
namespace App\Http\Controllers;

use Tmdb\Helper\ImageHelper;
use Tmdb\Repository\MovieRepository;

class WelcomeController extends Controller {

    private $movies;
    private $helper;

    public function __construct(MovieRepository $movies, ImageHelper $helper)
    {
        $this->movies = $movies;
        $this->helper = $helper;
    }

    /**
     * Show the application welcome screen to the user.
     *
     * @return Response
     */
    public function index()
    {
        $popular = $this->movies->getPopular();

        foreach ($popular as $movie)
        {
            $image = $movie->getPosterImage();
            echo ($this->helper->getHtml($image, 'w154', 260, 420));
        }
    }

}
```

The `Configuration` used by the `Tmdb\Helper\ImageHelper` is automatically loaded by the IoC container.
If you are a Laravel 5.1 user you could also use the blade's `@inject` functionality,

```
@inject('image', 'Tmdb\Helper\ImageHelper')

@foreach ($movies as $movie)
    {!! $image->getHtml($movie->getPosterImage(), 'w154', 260, 420) !!}
@endforeach
```

### Registering plugins

Plugins can be registered in a service provider using the `boot()` method.

```php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Tmdb\HttpClient\Plugin\LanguageFilterPlugin;

class TmdbServiceProvider extends ServiceProvider {

    /**
     * Add a Dutch language filter to the Tmdb client
     *
     * @return void
     */
    public function boot()
    {
        $plugin = new LanguageFilterPlugin('nl');
        $client = $this->app->make('Tmdb\Client');
        $client->getHttpClient()->addSubscriber($plugin);
    }

    /**
     * Register services
     * @return void
     */
    public function register()
    {
        // register any services that you need
    }
}
```

**For all all other interactions take a look at [php-tmdb/api](https://github.com/php-tmdb/api).**
