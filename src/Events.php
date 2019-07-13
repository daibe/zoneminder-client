<?php
/**
 *  TODO: Add description
 * @author Abed Tshilombo
 */

namespace Daibe\ZoneClient;


use GuzzleHttp\Psr7\Response;

class Events
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
     * Return a list of all events
     * @param int $page
     * @return mixed
     */
    public function getEvents($page = 1)
    {
        $response = $this->client->get("/api/events.json?page={$page}");
        return $this->output($response);
    }

    /**
     * Retrieve event Id
     * @param $event_id
     * @return mixed
     */
    public function get($event_id)
    {
        $response = $this->client->get("/api/events/{$event_id}.json");
        return $this->output($response);
    }

    /**
     * Edit event
     * @param $event_id
     * @param $name
     * @return mixed
     */
    public function update($event_id, $name)
    {
        $response = $this->client->put("/api/events/{$event_id}.json", [
            'form_params' => [
                'Event["Name"]' => $name
            ]
        ]);
        return $this->output($response);
    }

    /**
     * Delete event
     * @param $event_id
     * @return mixed
     */
    public function delete($event_id)
    {
        $response = $this->client->delete("/api/events/{$event_id}.json");
        return $this->output($response);
    }

    /**
     * Return a list of events for a specific monitor Id
     * @param $monitor_id
     * @return mixed
     */
    public function getByMonitorId($monitor_id)
    {
        $response = $this->client->get("/api/events/index/MonitorId:{$monitor_id}.json");
        return $this->output($response);
    }

    /**
     * Return a list of events for a specific monitor within a specific date/time range
     * @param $monitor_id
     * @param $datetimeStart
     * @param $datetimeEnd
     * @return mixed
     */
    public function getMonitorEventsByRange($monitor_id, $datetimeStart, $datetimeEnd)
    {
        $response = $this->client->get("/api/events/index/MonitorId:{$monitor_id}/StartTime%20>=:{$datetimeStart}/EndTime%20<=:{$datetimeEnd}.json");
        return $this->output($response);
    }

    /**
     * Return a list of events for all monitors within a specified date/time range
     * @param $datetimeStart
     * @param $datetimeEnd
     * @return mixed
     */
    public function getAlarmStatus($datetimeStart, $datetimeEnd)
    {
        $response = $this->client->get("/api/events/index/StartTime%20>=:{$datetimeStart}/EndTime%20<=:{$datetimeEnd}.json");
        return $this->output($response);
    }


    // TODO: Return event count based on times and conditions


}