<?php
/**
 * @package Wtfz_TmdbPackage
 * @author Michael Roterman <michael@wtfz.net>
 * @copyright (c) 2014, Michael Roterman
 */
return [
    /*
     * Api key
     */
    'api_key' => '',

    /*
     * Cache
     */
    'cache' => [
        'enabled' => true,
        // Keep the path empty or remove it entirely to default to app/storage/tmdb
        'path'    => ''
    ],

    /*
     * Log
     */
    'log' => [
        'enabled' => true,
        // Keep the path empty or remove it entirely to default to app/storage/logs/tmdb.log
        'path'    => ''
    ]
];
