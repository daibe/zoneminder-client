<?php
/**
 *  TODO: Add description
 * @author Abed Tshilombo
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


    private function isFunctionValid($function)
    {
        return (bool) (in_array(strtolower($function), self::FUNCTIONS));
    }


    /**
     * list all monitors
     */
    public function getMonitors()
    {
        $response = $this->client->get("api/monitors.json");

        $monitors = [];

        if ($response) {
            $data = json_decode($response->getBody());
            $monitors = (isset($data->monitors)) ? $data->monitors : [];
        }

        return $this->output(!((bool) $monitors), null, $monitors);
    }

    /**
     * retrieve monitor
     * @param $monitor_id
     * @return mixed
     */
    public function getMonitor($monitor_id)
    {
        $response = $this->client->get("api/monitors/{$monitor_id}.json");

        $monitor = [];

        if ($response) {
            $data = json_decode($response->getBody());
            $monitor = (isset($data->monitor)) ? $data->monitor : [];
        }

        return $this->output(!((bool) $monitor), null, $monitor);
    }

    /**
     * change monitor function
     * @param $monitor_id
     * @param $function
     * @return mixed|null
     */
    public function changeFunction($monitor_id, $function)
    {
        if (!$this->isFunctionValid($function)) {
            return null;
        }

        $response = $this->client->post("api/monitors/{$monitor_id}.json", [
            'form_params' => [
                'Monitor["Function"]' => ucfirst($function)
            ]
        ]);

        $is_saved = false;

        if ($response) {
            $data = json_decode($response->getBody());
            $is_saved = (isset($data->message) && $data->message == "Saved") ? true : false;
        }

        return $this->output(!$is_saved, null);
    }

    /**
     * enable monitor
     * @param $monitor_id
     * @return mixed
     */
    public function enable($monitor_id)
    {
        $response = $this->client->post("api/monitors/{$monitor_id}.json", [
            'form_params' => [
                'Monitor["Enabled"]' => 1
            ]
        ]);

        $is_saved = false;

        if ($response) {
            $data = json_decode($response->getBody());
            $is_saved = (isset($data->message) && $data->message == "Saved") ? true : false;
        }

        return $this->output(!$is_saved, null);
    }

    /**
     * disable monitor
     * @param $monitor_id
     * @return mixed
     */
    public function disable($monitor_id)
    {
        $response = $this->client->post("api/monitors/{$monitor_id}.json", [
            'form_params' => [
                'Monitor["Enabled"]' => 0
            ]
        ]);

        $is_saved = false;

        if ($response) {
            $data = json_decode($response->getBody());
            $is_saved = (isset($data->message) && $data->message == "Saved") ? true : false;
        }

        return $this->output(!$is_saved, null);
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
        if (!$this->isFunctionValid($function)) {
            return null;
        }

        $response = $this->client->post("api/monitors/{$monitor_id}.json", [
            'form_params' => [
                'Monitor["Enabled"]' => $enabled,
                'Monitor["Function"]' => ucfirst($function)
            ]
        ]);

        $is_saved = false;

        if ($response) {
            $data = json_decode($response->getBody());
            $is_saved = (isset($data->message) && $data->message == "Saved") ? true : false;
        }

        return $this->output(!$is_saved, null);
    }

    /**
     * get daemon status of monitor
     * @param $monitor_id
     * @return mixed
     */
    public function daemon($monitor_id)
    {
        $response = $this->client->get("api/monitors/daemonStatus/id:{$monitor_id}/daemon:zmc.json");

        $daemon = [];

        if ($response) {
            $data = json_decode($response->getBody());
            $daemon = (isset($data->status)) ? $data : false;
        }

        return $this->output(!((bool) $daemon), null, $daemon);
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
        if (!$this->isFunctionValid($function)) {
            return null;
        }

        $response = $this->client->post("api/monitors.json", [
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
        // return $this->output($response);
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
        $response = $this->client->delete("api/monitors/{$monitor_id}.json");
        // return $this->output($response);
    }

    /**
     * Arm monitors
     * @param $monitor_id
     * @return mixed
     */
    public function arm($monitor_id)
    {
        $response = $this->client->get("api/monitors/alarm/id:{$monitor_id}/command:on.json");
        // return $this->output($response);
    }

    /**
     * disarm monitors
     * @param $monitor_id
     */
    public function disarm($monitor_id)
    {
        $response = $this->client->get("api/monitors/alarm/id:{$monitor_id}/command:off.json");
        // return $this->output($response);
    }

    /**
     * disarm monitors
     * @param $monitor_id
     * @return mixed
     */
    public function getAlarmStatus($monitor_id)
    {
        $response = $this->client->get("api/monitors/alarm/id:{$monitor_id}/command:status.json");
        // return $this->output($response);
    }


}