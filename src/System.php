<?php
/**
 *  TODO: Add description
 * @author Abed Tshilombo
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


    /**
     * @param $error
     * @param $message
     * @param null $data
     * @return \stdClass
     */
    private function output($error, $message, $data = null)
    {
        $output = new \stdClass();

        $output->error = $error;
        $output->message = $message;

        if ($data) {
            $output->data = $data;
        }

        return $output;
    }


    /**
     * Get ZoneMinder load
     * @return \stdClass
     */
    public function getHostLoad()
    {
        $response = $this->client->get("api/host/getLoad.json");

        if (!$response) {
            $output = $this->output(true, "An unexpected error occurred. #S01");
        }
        else {
            $data = json_decode($response->getBody());

            if (!isset($data->load)) {
                $output = $this->output(true, "An unexpected error occurred. #S02");
            }
            else {
                $output = $this->output(false, null, $data->load);
            }
        }

        return $output;
    }

    /**
     * Retrieve monitor
     * @return int
     */
    public function daemonCheck()
    {
        $response = $this->client->get("api/host/daemonCheck.json");

        if (!$response) {
            $output = 0;
        }
        else {
            $data = json_decode($response->getBody());
            $output = (isset($data->result)) ? $data->result : 0;
        }

        return $output;
    }

    /**
     * Storage info
     * @return \stdClass
     */
    public function getStorage()
    {
        $response = $this->client->get("api/storage.json");

        if (!$response) {
            $output = $this->output(true, "An unexpected error occurred. #S05");
        }
        else {
            $data = json_decode($response->getBody());

            if (!isset($data->storage)) {
                $output = $this->output(true, "An unexpected error occurred. #S06");
            }
            else {
                $output = $this->output(false, null, $data->storage);
            }
        }

        return $output;
    }

    /**
     * Servers
     * @return \stdClass
     */
    public function getServers()
    {
        $response = $this->client->post("api/servers.json");

        if (!$response) {
            $output = $this->output(true, "An unexpected error occurred. #S07");
        }
        else {
            $data = json_decode($response->getBody());

            if (!isset($data->servers)) {
                $output = $this->output(true, "An unexpected error occurred. #S08");
            }
            else {
                $output = $this->output(false, null, $data->servers);
            }
        }

        return $output;
    }

    /**
     * returns list of run states
     */
    public function getStates()
    {
        $response = $this->client->get("api/states.json");

        if (!$response) {
            $output = $this->output(true, "An unexpected error occurred. #S09");
        }
        else {
            $data = json_decode($response->getBody());

            if (!isset($data->states)) {
                $output = $this->output(true, "An unexpected error occurred. #S10");
            }
            else {
                $output = $this->output(false, null, $data->states);
            }
        }

        return $output;
    }

    /**
     * Restarts ZM
     */
    public function restart()
    {
        $this->client->post("api/states/change/restart.json");
    }

    /**
     * Stops ZM
     */
    public function stop()
    {
        $this->client->post("api/states/change/stop.json");
    }

    /**
     * Starts ZM
     */
    public function start()
    {
        $this->client->post("api/states/change/start.json");
    }

    /**
     * Returns ZM configurations
     */
    public function getConfigs()
    {
        $response = $this->client->post("api/configs.json");

        if (!$response) {
            $output = $this->output(true, "An unexpected error occurred. #S11");
        }
        else {
            $data = json_decode($response->getBody());

            if (!isset($data->configs)) {
                $output = $this->output(true, "An unexpected error occurred. #S12");
            }
            else {
                $output = $this->output(false, null, $data->configs);
            }
        }

        return $output;
    }


}