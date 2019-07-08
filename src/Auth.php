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

    public function login($username, $password)
    {
        $output = null;

        $response = $this->client->request('POST', '/api/login.json', [
            'form_params' => [
                'user' => $username,
                'pass' => $password
            ]
        ]);

        $data = json_decode($response->getBody(), true);

        // login failed
        if (!$data->success || !$data->credentials) {
            $output = $this->output($data->success, $data->data->message);
        }
        // login success
        else {

            $this->setAuthCredentials($data->credentials);
            $output = $this->output(false, "You've been logged in");

        }

        return $output;

        /*

        $zone_client = new ZoneClient();
        $auth = new Auth($zone_client);

        $login = $auth->login($username, $password);
        if ($login->error) {

        }
        else {

        }
        $login->authCredentials;
        $login->message;
        $auth->getAuthCredentials();

        $auth->logout();

        $auth->isLoggedIn();

        */


    }

    public function logout()
    {
        if ($this->isLoggedIn()) {
            $this->clearAuthCredentials();
        }
    }

    private function setAuthCredentials($auth_val)
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION[self::AUTH_SESSION_KEY] = $auth_val;
    }

    private function clearAuthCredentials()
    {
        $this->client->clearCookies();
        unset($_SESSION[self::AUTH_SESSION_KEY]);
    }

    public function isLoggedIn()
    {
        return (bool) filter_input(INPUT_SESSION, self::AUTH_SESSION_KEY);
    }

    public function getAuthCredentials()
    {
        return filter_input(INPUT_SESSION, self::AUTH_SESSION_KEY);
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