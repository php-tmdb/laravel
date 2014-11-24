Description
----------------

A Laraval Package for use together with the [wtfzdotnet/php-tmdb-api](https://github.com/wtfzdotnet/php-tmdb-api) TMDB Wrapper.

Installation
------------
Install Composer

```
$ curl -sS https://getcomposer.org/installer | php
$ sudo mv composer.phar /usr/local/bin/composer
```

Add the following to your require block in composer.json config

```
"wtfzdotnet/tmdb-package": "~0.1"
```

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
php artisan config:publish wtfzdotnet/tmdb-package
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
