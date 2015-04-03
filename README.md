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

### Image helper
You can easily use the `ImageHelper` by using dependency injection. The following example shows how to show the poster image of the 20 most popular movies.

```php
namespace App\Http\Controllers;

use Tmdb\Helper\ImageHelper;
use Tmdb\Repository\MovieRepository;

class WelcomeController extends Controller {

    private $movies;
    private $helepr;

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
If you are a Laravel 5.1 (currently not released) user you could also use the blade's new `@inject` functionality,
```
@inject('image', 'Tmdb\Helper\ImageHelper')

@foreach ($movies as $movie)
    {{ $image->getHtml($movie->getPosterImage(), 'w154', 260, 420) }}
@endforeach
```

**For all all other interactions take a look at [wtfzdotnet/php-tmdb-api](https://github.com/wtfzdotnet/php-tmdb-api).**


## Work in progress
This package is still a work in progress.
- Documentation
- Plugins