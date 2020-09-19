<?php
/**
 * @package php-tmdb\laravel
 * @author Mark Redeman <markredeman@gmail.com>
 * @copyright (c) 2014, Mark Redeman
 */
return [
    /*
     * Api key
     */
    'api_key' => '',

    'cache_store' => 'file',
    'cache_tag' => '',

    /**
     * Client options
     *
     * @see https://github.com/php-tmdb/api/tree/3.0#constructing-the-client
     */
    'options' => [
        /**
         * Use https
         */
        'secure' => true,
    ],
];
