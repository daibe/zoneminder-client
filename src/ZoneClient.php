<?php
/**
 * Created by IntelliJ IDEA.
 * User: Suyo solutions
 * Date: 2019/06/29
 * Time: 19:26
 */

namespace Daibe\ZoneClient;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class ZoneClient
{
    const DEFAULT_BASE_URI = 'https://127.0.0.1/zm';

    private $client;


    public function __construct($base_uri = null)
    {
        $options = [
            'cookies' => true, // This is needed to authenticate with zoneminder
            'timeout' => 3.5,
            'base_uri'=> ($base_uri) ? $base_uri : self::DEFAULT_BASE_URI
        ];


        $this->client = new Client($options);

    }

    public function clearCookies()
    {
        $cookie_jar = $this->client->getConfig('cookies');
        $cookie_jar->clear();
    }

    public function get() { }

    public function post() {}

    public function put() {}

    public function delete() {}



}