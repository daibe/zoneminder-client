<?php
/**
 * Created by IntelliJ IDEA.
 * User: Suyo solutions
 * Date: 2019/06/29
 * Time: 21:33
 */

namespace Daibe\ZoneClient;


use GuzzleHttp\Psr7\Response;

class System
{
    private $client;


    public function __construct(ZoneClient $client)
    {
        $this->client = $client;
    }


    private function output(Response $response)
    {
        return json_decode($response->getBody(), true);
    }


    /**
     * Get ZoneMinder load
     */
    public function getHostLoad()
    {
        $response = $this->client->get("/api/host/getLoad.json");
        return $this->output($response);
    }

    /**
     * retrieve monitor
     * @return mixed
     */
    public function daemonCheck()
    {
        $response = $this->client->get("/api/host/daemonCheck.json");
        return $this->output($response);
    }

    /**
     * Storage info
     */
    public function getStorage()
    {
        $response = $this->client->get("/api/storage.json");
        return $this->output($response);
    }

    /**
     * Servers
     */
    public function getServers()
    {
        $response = $this->client->post("/api/servers.json");
        return $this->output($response);
    }

    /**
     * returns list of run states
     */
    public function getStates()
    {
        $response = $this->client->get("/api/states.json");
        return $this->output($response);
    }

    /**
     * Restarts ZM
     */
    public function restart()
    {
        $response = $this->client->post("/api/states/change/restart.json");
        return $this->output($response);
    }

    /**
     * Stops ZM
     */
    public function stop()
    {
        $response = $this->client->post("/api/states/change/stop.json");
        return $this->output($response);
    }

    /**
     * Starts ZM
     */
    public function start()
    {
        $response = $this->client->post("/api/states/change/start.json");
        return $this->output($response);
    }

    /**
     * Returns ZM configurations
     */
    public function getConfigs()
    {
        $response = $this->client->post("/api/configs.json");
        return $this->output($response);
    }


}