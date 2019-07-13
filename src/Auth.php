<?php namespace Daibe\ZoneClient;

/**
*  Authentication
*
*  Provides authentication methods to zoneminder version 1.32
*  @author Abed Tshilombo
*/
class Auth
{
    const AUTH_SESSION_KEY = "zm_auth";

    private $client;


    public function __construct(ZoneClient $client)
    {
        $this->client = $client;
    }

    /**
     *
     *
     * @param $username
     * @param $password
     * @return \stdClass|null
     */
    public function login($username, $password)
    {
        $output = null;

        $response = $this->client->post('api/host/login.json', [
            'form_params' => [
                'user' => $username,
                'pass' => $password
            ]
        ]);

        // Handle null response
        if (!$response) {
            $output = $this->output(true, "An unexpected error occurred. #A01");
        }
        else {
            $data = json_decode($response->getBody());

            // login failed
            if (isset($data->success) && !$data->success) {
                $output = $this->output($data->success, $data->data->message);
            }
            // login success
            elseif (isset($data->credentials) && $data->credentials != null) {
                $this->setAuthCredentials($data->credentials);
                $output = $this->output(false, "You've been logged in");
            }
            //
            else {
                $output = $this->output(true, "An unexpected error occurred. #A02");
            }
        }

        return $output;
    }

    /**
     * @return \stdClass
     */
    public function logout()
    {
        $output = $this->output(true, "Your request failed. #A03");

        if ($this->isLoggedIn()) {

            $response = $this->client->post('api/host/logout.json', []);

            if ($response) {
                $data = json_decode($response->getBody());

                // login failed
                if (isset($data->result) && !$data->result == "ok") {
                    $output = $this->output(true, "You've been logged out");
                }
            }

            $this->clearAuthCredentials();
        }

        return $output;
    }

    private function setAuthCredentials($auth_val)
    {
        $_SESSION[self::AUTH_SESSION_KEY] = $auth_val;
    }

    public function clearAuthCredentials()
    {
        $this->client->clearCookies();
        if (isset($_SESSION[self::AUTH_SESSION_KEY])) {
            unset($_SESSION[self::AUTH_SESSION_KEY]);
        }
    }

    public function isLoggedIn()
    {

        $system = new System($this->client);
        return $system->getHostLoad()->error;

    }

    public function getAuthCredentials()
    {
        $key = (isset($_SESSION[self::AUTH_SESSION_KEY])) ? $_SESSION[self::AUTH_SESSION_KEY] : null;
        return filter_var($key);
    }

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

}