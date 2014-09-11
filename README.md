Description
----------------

A Laraval Package for use together with the [wtfzdotnet/php-tmdb-api](https://github.com/wtfzdotnet/php-tmdb-api) TMDB Wrapper.

Configuration
----------------
Add to your `app/config/app.php` the service provider:

```php
// Provider
'providers' => array(
    'Wtfz\TmdbPackage\TmdbServiceProvider',
)
```

Then publish the configuration file:

```
php artisan config:publish wtfz/tmdb
```

And modify the configuration file located at `app/config/packages/wtfz/tmdb/config.php` accordingly.

That's all! Fire away!

Usage
----------------

Obtaining the RAW data

```php
$client = Tmdb::getMoviesApi()->load(13);
```

Obtaining modeled data

```php
$movie = Tmdb::getMovieRepository()->load(13);
```

**For all all other interactions take a look at [wtfzdotnet/php-tmdb-api](https://github.com/wtfzdotnet/php-tmdb-api).**