<?php
/**
 * Created by PhpStorm.
 * User: mirekratman
 * Date: 26/06/15
 * Time: 13:09
 */

ERROR_REPORTING(E_ALL);
require_once(__DIR__ . '/../../src/loader.php');

class ApiTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var $classApiClient - API Client instance
     */
    protected $classApiClient;

    public function __construct()
    {
        date_default_timezone_set('UTC');

        $url = 'http://rapi-dev.modgen.net';
        $db = 'shopexpo-test';
        $key = 'DyioS5vct4fyqbjjr7Yno8dUFALYjAZe0JP3yR65aCNdtbjk92F9gxU1yDAVR7QS';

        $this->classApiClient = new Php\Api\Client($url, $db, $key);
    }
    public function testDatabaseReset()
    {
        $this->assertSame( $this->classApiClient->resetDatabase(true), 'ok' );
    }
    public function testSetItemProperty()
    {
        $this->assertSame( $this->classApiClient->setItemProperty('extra_description', 'string'), 'ok' );
    }
    public function testGetItemProperties()
    {
        $this->assertJsonStringEqualsJsonString( $this->classApiClient->getItemProperties(), '[{"type": "string", "name": "extra_description"}]' );
    }
    public function testGetItemPropertyInfo()
    {
        $this->assertJsonStringEqualsJsonString( $this->classApiClient->getItemPropertyInfo('extra_description'), '{"type": "string", "name": "extra_description"}' );
    }
    public function testDeleteProperty()
    {
        $this->assertSame( $this->classApiClient->deleteItemProperty('extra_description'), 'ok' );
    }
    public function testSetItemProperties()
    {
        $this->assertJsonStringEqualsJsonString(
            json_encode(
                $this->classApiClient->setItemProperties(array(
                    'name' => 'string',
                    'available' => 'boolean',
                    'description' => 'string',
                    'tags' => 'set',
                    'price' => 'price',
                    'added' => 'timestamp'
                )
            )
        ), '{"name":{"property":"ok","role":"ok"},"available":{"property":"ok","role":"not_needed"},"description":{"property":"ok","role":"not_needed"},"tags":{"property":"ok","role":"not_needed"},"price":{"property":"ok","role":"ok"},"added":{"property":"ok","role":"not_needed"}}' );
    }


}