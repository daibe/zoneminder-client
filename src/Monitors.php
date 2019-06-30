<?php
/**
 * Created by IntelliJ IDEA.
 * User: Suyo solutions
 * Date: 2019/06/29
 * Time: 21:33
 */

namespace Daibe\ZoneClient;


use GuzzleHttp\Psr7\Response;

class Monitors
{

    const FUNCTIONS = [
        'none', // The monitor is currently disabled. No streams can be viewed or events generated. Nothing is recorded.
        'monitor', // The monitor is only available for live streaming. No image analysis is done so no alarms or events will be generated, and nothing will be recorded.
        'modect', // MOtion DEteCTtion. All captured images will be analysed and events generated with recorded video where motion is detected.
        'record', // The monitor will be continuously recorded. Events of a fixed-length will be generated regardless of motion, analogous to a conventional time-lapse video recorder. No motion detection takes place in this mode.
        'mocord', // The monitor will be continuously recorded, with any motion being highlighted within those events.
        'nodect', // No DEteCTtion. This is a special mode designed to be used with external triggers. In Nodect no motion detection takes place but events are recorded if external triggers require it.
    ];

    private $client;


    public function __construct(ZoneClient $client)
    {
        $this->client = $client;
    }


    private function output(Response $response)
    {
        return json_decode($response->getBody(), true);
    }


    private function isValidFunction($function)
    {
        return (bool) (in_array(strtolower($function), self::FUNCTIONS));
    }


    /**
     * list all monistors
     */
    public function getAll()
    {
        $response = $this->client->get("/api/monitors.json");
        return $this->output($response);
    }

    /**
     * retrieve monitor
     * @param $monitor_id
     * @return mixed
     */
    public function get($monitor_id)
    {
        $response = $this->client->get("/api/monitors/{$monitor_id}.json");
        return $this->output($response);
    }

    /**
     * change monitor function
     * @param $monitor_id
     * @param $function
     * @return mixed|null
     */
    public function changeFunction($monitor_id, $function)
    {
        if (!$this->isValidFunction($function)) {
            return null;
        }

        $response = $this->client->post("/api/monitors/{$monitor_id}.json", [
            'form_params' => [
                'Monitor["Function"]' => ucfirst($function)
            ]
        ]);
        return $this->output($response);
    }

    /**
     * enable monitor
     * @param $monitor_id
     * @return mixed
     */
    public function enable($monitor_id)
    {
        $response = $this->client->post("/api/monitors/{$monitor_id}.json", [
            'form_params' => [
                'Monitor["Enabled"]' => 1
            ]
        ]);
        return $this->output($response);
    }

    /**
     * disable monitor
     * @param $monitor_id
     * @return mixed
     */
    public function disable($monitor_id)
    {
        $response = $this->client->post("/api/monitors/{$monitor_id}.json", [
            'form_params' => [
                'Monitor["Enabled"]' => 0
            ]
        ]);
        return $this->output($response);
    }

    /**
     * Change monitor state (enabled and function)
     * @param $monitor_id
     * @param $function
     * @param $enabled
     * @return mixed|null
     */
    public function changeState($monitor_id, $function, $enabled)
    {
        if (!$this->isValidFunction($function)) {
            return null;
        }

        $response = $this->client->post("/api/monitors/{$monitor_id}.json", [
            'form_params' => [
                'Monitor["Enabled"]' => $enabled,
                'Monitor["Function"]' => ucfirst($function)
            ]
        ]);
        return $this->output($response);
    }

    /**
     * get daemon status of monitor
     * @param $monitor_id
     * @return mixed
     */
    public function daemon($monitor_id)
    {
        $response = $this->client->get("/api/monitors/daemonStatus/id:{$monitor_id}/daemon:zmc.json");
        return $this->output($response);
    }

    /**
     * TODO:
     * add a monitor
     * @param $name
     * @param $function
     * @param $protocol
     * @param $method
     * @param $host
     * @param $port
     * @param $path
     * @param int $width
     * @param int $height
     * @param int $colors
     * @return mixed|null
     */
    public function create(
        $name,
        $function,
        $protocol,
        $method,
        $host,
        $port,
        $path,
        $width = 704,
        $height = 480,
        $colors = 4
    ) {
        if (!$this->isValidFunction($function)) {
            return null;
        }

        $response = $this->client->post("/api/monitors.json", [
            'form_params' => [
                'Monitor["Name"]' => $name,
                'Monitor["Function"]' => ucfirst($function),
                'Monitor["Protocol"]' => $protocol,
                'Monitor["Method"]' => $method,
                'Monitor["Host"]' => $host,
                'Monitor["Port"]' => $port,
                'Monitor["Path"]' => $path,
                'Monitor["Width"]' => $width,
                'Monitor["Height"]' => $height,
                'Monitor["Colours"]' => $colors,
            ]
        ]);
        return $this->output($response);
    }

    /**
     * TODO:
     * edit a monitor
     * @param $monitor_id
     */
    public function update($monitor_id)
    {

    }

    /**
     * Delete a monitor
     * @param $monitor_id
     * @return mixed
     */
    public function delete($monitor_id)
    {
        $response = $this->client->delete("/api/monitors/{$monitor_id}.json");
        return $this->output($response);
    }

    /**
     * Arm monitors
     * @param $monitor_id
     * @return mixed
     */
    public function arm($monitor_id)
    {
        $response = $this->client->get("/api/monitors/alarm/id:{$monitor_id}/command:on.json");
        return $this->output($response);
    }

    /**
     * disarm monitors
     * @param $monitor_id
     * @return mixed
     */
    public function disarm($monitor_id)
    {
        $response = $this->client->get("/api/monitors/alarm/id:{$monitor_id}/command:off.json");
        return $this->output($response);
    }

    /**
     * disarm monitors
     * @param $monitor_id
     * @return mixed
     */
    public function getAlarmStatus($monitor_id)
    {
        $response = $this->client->get("/api/monitors/alarm/id:{$monitor_id}/command:status.json");
        return $this->output($response);
    }


}