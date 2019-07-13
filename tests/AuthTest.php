<?php
/**
 *  TODO: Add description
 * @author Abed Tshilombo
 */

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// phpunit backward compatibility
if (!class_exists('\PHPUnit\Framework\TestCase') && class_exists('\PHPUnit_Framework_TestCase')) {
    class_alias('\PHPUnit_Framework_TestCase', '\PHPUnit\Framework\TestCase');
}

class AuthTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Daibe\ZoneClient\Auth
     */
    public static $auth;

    /**
     * @var \Daibe\ZoneClient\ZoneClient
     */
    public static $zoneClient;


    public static function setUpBeforeClass()
    {
        require_once __DIR__ . '/../vendor/autoload.php';


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


        self::$zoneClient = new \Daibe\ZoneClient\ZoneClient("192.168.1.16/zm/");
        self::$auth = new \Daibe\ZoneClient\Auth(self::$zoneClient);

        // Clear previous cookies
        self::$auth->clearAuthCredentials();
    }


    public function testLogin()
    {
        // Successful login
        $this->assertFalse(self::$auth->login("test", 'test')->error);

        // Failed login: incorrect credentials
        $this->assertTrue(self::$auth->login("XXXX", "YYYY")->error);

    }


    /**
     * @depends testLogin
     */
    public function testLogout()
    {

        if (!self::$auth->isLoggedIn()) {
            $this->assertFalse(self::$auth->logout()->error);
        }
        else {
            $this->assertTrue(self::$auth->logout()->error);
        }

    }


    /**
     * @depends testLogin
     */
    public function testIsLoggedIn()
    {
        // Failed: is not logged in
        if (!self::$auth->getAuthCredentials()) {
            $this->assertFalse(self::$auth->isLoggedIn());
        }
        // Success: is logged in
        else {
            $this->assertTrue(self::$auth->isLoggedIn());
        }
    }


    public function testGetAuthCredentials()
    {
        // Failed: ...
        if (!self::$auth->getAuthCredentials()) {
            $this->assertEmpty(self::$auth->getAuthCredentials());
        }
        // Success: ...
        else {
            $this->assertNotEmpty(self::$auth->getAuthCredentials());
        }
    }


}