## Description
A Laraval Package for use together with the [wtfzdotnet/php-tmdb-api](https://github.com/wtfzdotnet/php-tmdb-api) TMDB Wrapper.
This package comes with a service provider that configures the `TMDB\Client` and registers it to the IoC container.
Both Laravel 5 and 4 are supported.


## Installation
Install Composer

```
$ curl -sS https://getcomposer.org/installer | php
$ sudo mv composer.phar /usr/local/bin/composer
```

Add the following to your require block in composer.json config

```
"wtfzdotnet/tmdb-package": "~0.1"
```

## Configuration
Add to your `app/config/app.php` (Laravel 4) or 'config/app.php' (Laravel 5) the service provider:

```php
'providers' => array(
    // other service providers

    'Wtfz\TmdbPackage\TmdbServiceProvider',
)
```

Then publish the configuration file:

#### Laravel 4:
```
php artisan config:publish wtfzdotnet/tmdb-package
```

#### Laravel 5:
```
php artisan vendor:publish --provider=wtfzdotnet/tmdb-package
```

Next you can modify the generated configuration file `tmdb.php` accordingly.
<!-- TODO: add documentation about security, cache, log -->

That's all! Fire away!

## Usage
We can choose to either use the `Tmdb` Facade, or to use dependency injection.

### Facade example
```php
use Wtfz\TmdbPackage\Facades\Tmdb;

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

**For all all other interactions take a look at [wtfzdotnet/php-tmdb-api](https://github.com/wtfzdotnet/php-tmdb-api).**


## Work in progress
This package is still a work in progress.
- Caching settings
- Logging settings
- Documentation
- Plugins
- Image helper (`tmdb_image()`)