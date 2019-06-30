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

    /**
     * @var Client
     */
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

    /**
     * Clear cookies
     */
    public function clearCookies()
    {
        $cookie_jar = $this->client->getConfig('cookies');
        $cookie_jar->clear();
    }

    /**
     * @param $url
     * @return mixed|\Psr\Http\Message\ResponseInterface|null
     */
    public function get($url)
    {
        try {
            $output = $this->client->request('GET', $url);
        } catch (GuzzleException $e) {
            $output = null;
        }
        return $output;
    }

    /**
     * @param $url
     * @param array $params
     * @return mixed|\Psr\Http\Message\ResponseInterface|null
     */
    public function post($url, $params = [])
    {
        try {
            $output = $this->client->request('POST', $url, $params);
        } catch (GuzzleException $e) {
            $output = null;
        }
        return $output;
    }

    /**
     * @param $url
     * @param array $params
     * @return mixed|\Psr\Http\Message\ResponseInterface|null
     */
    public function put($url, $params = [])
    {
        try {
            $output = $this->client->request('PUT', $url, $params);
        } catch (GuzzleException $e) {
            $output = null;
        }
        return $output;
    }

    /**
     *
     * @param $url
     * @param array $params
     * @return mixed|\Psr\Http\Message\ResponseInterface|null
     */
    public function delete($url, $params = [])
    {
        try {
            $output = $this->client->request('DELETE', $url, $params);
        } catch (GuzzleException $e) {
            $output = null;
        }
        return $output;
    }



}