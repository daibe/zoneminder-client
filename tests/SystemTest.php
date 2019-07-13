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

class SystemTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Daibe\ZoneClient\Auth
     */
    public static $auth;
    /**
     * @var \Daibe\ZoneClient\System
     */
    public static $system;

    /**
     * @var \Daibe\ZoneClient\ZoneClient
     */
    public static $zoneClient;


    public static function setUpBeforeClass()
    {
        require_once __DIR__ . '/../vendor/autoload.php';

        self::$zoneClient = new \Daibe\ZoneClient\ZoneClient("192.168.1.16/zm/");
        self::$auth = new \Daibe\ZoneClient\Auth(self::$zoneClient);
        self::$system = new \Daibe\ZoneClient\System(self::$zoneClient);

        self::$auth->login("test", "test");
    }


    public function testHostLoad()
    {
        $this->assertTrue(self::$system->getHostLoad()->error);
    }


    public function testDaemonCheck()
    {
        $this->assertInternalType("int", self::$system->daemonCheck());
    }


    public function testStorage()
    {
        $this->assertTrue(self::$system->getStorage()->error);
    }


    public function testServers()
    {
        $this->assertTrue(self::$system->getServers()->error);
    }


    public function testStates()
    {
        $this->assertTrue(self::$system->getStates()->error);
    }





}