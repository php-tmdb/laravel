## Description
A Laraval Package for use together with the [php-tmdb/api](https://github.com/php-tmdb/api) TMDB Wrapper.
This package comes with a service provider that configures the `Tmdb\Client` and registers it to the IoC container.
Both Laravel 5 and 4 are supported.


## Installation
Install Composer

```
$ curl -sS https://getcomposer.org/installer | php
$ sudo mv composer.phar /usr/local/bin/composer
```

Add the following to your require block in composer.json config

```
"php-tmdb/laravel": "~1.0"
```

## Configuration
Add to your `app/config/app.php` (Laravel 4) or 'config/app.php' (Laravel 5) the service provider:

```php
'providers' => array(
    // other service providers

    'Tmdb\Laravel\TmdbServiceProvider',
)
```

Then publish the configuration file:

#### Laravel 4:
```
php artisan config:publish php-tmdb/laravel
```

#### Laravel 5:
```
php artisan vendor:publish --provider=php-tmdb/laravel
```

Next you can modify the generated configuration file `tmdb.php` accordingly.

That's all! Fire away!

## Usage
We can choose to either use the `Tmdb` Facade, or to use dependency injection.

### Facade example
The example below shows how you can use the `Tmdb` facade.
If you don't want to keep adding the `use Tmdb\Laravel\Facades\Tmdb;` statement in your files, then you can also add the facade as an alias in `config/app.php` file.
```php
use Tmdb\Laravel\Facades\Tmdb;

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
    {!! $image->getHtml($movie->getPosterImage(), 'w154', 260, 420) !!}
@endforeach
```

### Registering plugins
Plugins can be registered in a service provider using the boot method.
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
}
```

**For all all other interactions take a look at [php-tmdb/api](https://github.com/php-tmdb/api).**