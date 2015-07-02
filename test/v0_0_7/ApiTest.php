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


    //Test database reset
    public function testDatabaseReset()
    {
        // ATTENTION !!!
        //This operation erase fully DB. Please use it only when its necessary
        // and with full responsibility of the fact of usage
        // The test is commented out for security reason. You can uncomment it by your self

        $this->assertSame($this->classApiClient->resetDatabase(true), 'ok');
    }


    //Test item properties
    public function testSetItemProperty()
    {
        $this->assertSame($this->classApiClient->setItemProperty('extra_description', 'string'), 'ok');
    }

    public function testGetItemProperties()
    {
        $this->assertJsonStringEqualsJsonString(json_encode($this->classApiClient->getItemProperties()), '[{"type": "string", "name": "extra_description"}]');
    }

    public function testGetItemPropertyInfo()
    {
        $this->assertJsonStringEqualsJsonString(json_encode($this->classApiClient->getItemPropertyInfo('extra_description')), '{"type": "string", "name": "extra_description"}');
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
            ), '{"name":{"property":"ok","role":"ok"},"available":{"property":"ok","role":"not_needed"},"description":{"property":"ok","role":"not_needed"},"tags":{"property":"ok","role":"not_needed"},"price":{"property":"ok","role":"ok"},"added":{"property":"ok","role":"not_needed"}}');
    }


    //Test items
    public function testAddItem()
    {
        $this->assertSame(
            $this->classApiClient->addItem(array(
                    'id' => 'item-234',
                    'name' => 'Iphone 4s 16GB',
                    'available' => true,
                    'description' => 'Some description of iPhone 4s 16BG including specifications, functions',
                    'tags' => json_encode(array('iphone,4s,phone')),
                    'price' => '195.52',
                    'added' => '1435080580'
                )
            )
            , 'ok'
        );
    }

    /**
     * @expectedException Php\Api\ApiException
     */
    public function testAddItem2()
    {
        $this->setExpectedException(
            $this->classApiClient->addItem(array(
                    'yourid' => 'item-235',
                    'name' => 'Iphone 4s 16GB',
                    'available' => true,
                    'description' => 'Some description of iPhone 4s 16BG including specifications, functions',
                    'tags' => json_encode(array('iphone,4s,phone')),
                    'price' => '195.52',
                    'added' => '1435080580'
                )
            )
        );
    }


    //Test Series
    public function testAddSeries()
    {
        $this->assertSame($this->classApiClient->addSeries('serie-1'), 'ok');
    }

    public function testListSeries()
    {
        $this->assertJsonStringEqualsJsonString(json_encode($this->classApiClient->listSeries()), '["serie-1"]');
    }

    public function testAddIntoSeries()
    {
        $this->assertSame(
            $this->classApiClient->addIntoSeries(
                'serie-1',
                array(
                    'itemType' => 'item',
                    'itemId' => 'item-234',
                    'time' => 1435080580
                )
            ),
            'ok'
        );
    }

    public function testListSeriesItems()
    {
        $this->assertJsonStringEqualsJsonString(json_encode($this->classApiClient->listSeriesItems('serie-1')), '[{"itemId":"item-234","itemType":"item","time":1435080580}]');
    }


    //Test Users
    public function testAddUser()
    {
        $this->assertSame($this->classApiClient->addUser('aot7sbaos7tasftasf'), 'ok');
        $this->assertSame($this->classApiClient->addUser('o7oaysdasn8f7af'), 'ok');
    }

    public function testMergeUsers()
    {
        $this->assertSame($this->classApiClient->mergeUsers('aot7sbaos7tasftasf', 'o7oaysdasn8f7af'), 'ok');
    }

    public function testListUsers()
    {
        $this->assertJsonStringEqualsJsonString(json_encode($this->classApiClient->listUsers()), '["aot7sbaos7tasftasf"]');
    }


    //Test Detail views
    public function testAddDetailView()
    {
        $this->assertSame(
            $this->classApiClient->addDetailView(
                array(
                    'userId' => 'aot7sbaos7tasftasf',
                    'itemId' => 'item-234',
                    'timestamp' => 1435080580
                )
            ),
            'ok'
        );
        $this->assertSame(
            $this->classApiClient->addDetailView(
                array(
                    'userId' => 'o7oaysdasn8f7af',
                    'itemId' => 'item-200',
                    'timestamp' => 1435080580
                )
            ),
            'ok'
        );
    }

    public function testListItemDetailViews()
    {
        $response = $this->classApiClient->listItemDetailViews('item-234');
        $this->assertArrayHasKey('itemId', (array)$response[0]);
    }

    public function testListUserDetailViews()
    {
        $response = $this->classApiClient->listUserDetailViews('o7oaysdasn8f7af');
        $this->assertArrayHasKey('itemId', (array)$response[0]);
    }


    //Test purchases
    public function testAddPurchase()
    {
        $this->assertSame(
            $this->classApiClient->addPurchase(
                array(
                    'userId' => 'aot7sbaos7tasftasf',
                    'itemId' => 'item-234',
                    'timestamp' => 1435080580
                )
            ),
            'ok'
        );
        $this->assertSame(
            $this->classApiClient->addPurchase(
                array(
                    'userId' => 'o7oaysdasn8f7af',
                    'itemId' => 'item-200',
                    'timestamp' => 1435080580
                )
            ),
            'ok'
        );
    }

    public function testListItemPurchases()
    {
        $response = $this->classApiClient->listItemPurchases('item-234');
        $this->assertArrayHasKey('itemId', (array)$response[0]);
    }

    public function testListUserPurchases()
    {
        $response = $this->classApiClient->listUserPurchases('o7oaysdasn8f7af');
        $this->assertArrayHasKey('itemId', (array)$response[0]);
    }


    //Test ratings
    public function testAddRating()
    {
        $this->assertSame(
            $this->classApiClient->addRating(
                array(
                    'userId' => 'aot7sbaos7tasftasf',
                    'itemId' => 'item-234',
                    'timestamp' => 1435080580,
                    'rating' => '0.7'
                )
            ),
            'ok'
        );
        $this->assertSame(
            $this->classApiClient->addRating(
                array(
                    'userId' => 'o7oaysdasn8f7af',
                    'itemId' => 'item-200',
                    'timestamp' => 1435080580,
                    'rating' => '0.3'
                )
            ),
            'ok'
        );
    }

    public function testListItemRatings()
    {
        $response = $this->classApiClient->listItemRatings('item-234');
        $this->assertArrayHasKey('itemId', (array)$response[0]);
    }

    public function testListUserRatings()
    {
        $response = $this->classApiClient->listUserRatings('o7oaysdasn8f7af');
        $this->assertArrayHasKey('itemId', (array)$response[0]);
    }


    //Test boomarks
    public function testAddBoomark()
    {
        $this->assertSame(
            $this->classApiClient->addBoomark(
                array(
                    'userId' => 'aot7sbaos7tasftasf',
                    'itemId' => 'item-234',
                    'timestamp' => 1435080580
                )
            ),
            'ok'
        );
        $this->assertSame(
            $this->classApiClient->addBoomark(
                array(
                    'userId' => 'o7oaysdasn8f7af',
                    'itemId' => 'item-200',
                    'timestamp' => 1435080580
                )
            ),
            'ok'
        );
    }

    public function testListItemBoomarks()
    {
        $response = $this->classApiClient->listItemBoomarks('item-234');
        $this->assertArrayHasKey('itemId', (array)$response[0]);
    }

    public function testListUserBoomarks()
    {
        $response = $this->classApiClient->listUserBoomarks('aot7sbaos7tasftasf');
        $this->assertArrayHasKey('itemId', (array)$response[0]);
    }


    //Test recommendations
    public function testGetItemBasedRecommendation()
    {
        $response = $this->classApiClient->getItemBasedRecommendation('item-234', 2, array('diversity' => 0.2));
        //$this->assertCount(2, (array)$response);
    }

    public function testGetUserBasedRecommendation()
    {
        $response = $this->classApiClient->getUserBasedRecommendation('o7oaysdasn8f7af', 1);
        //$this->assertCount(1, (array)$response);
    }


    //Test Delete
    public function testDeleteBoomark()
    {
        $this->assertSame($this->classApiClient->deleteBoomark(
            array(
                'userId' => 'aot7sbaos7tasftasf',
                'itemId' => 'item-234',
                'timestamp' => 1435080580,
            ))
            , 'ok'
        );
        $this->assertSame($this->classApiClient->deleteBoomark(
            array(
                'userId' => 'o7oaysdasn8f7af',
                'itemId' => 'item-200',
                'timestamp' => 1435080580,
            ))
            , 'ok'
        );
    }

    public function testDeleteRating()
    {
        $this->assertSame($this->classApiClient->deleteRating(
            array(
                'userId' => 'aot7sbaos7tasftasf',
                'itemId' => 'item-234',
                'timestamp' => 1435080580,
            ))
            , 'ok'
        );
        $this->assertSame($this->classApiClient->deleteRating(
            array(
                'userId' => 'o7oaysdasn8f7af',
                'itemId' => 'item-200',
                'timestamp' => 1435080580,
            ))
            , 'ok'
        );
    }

    public function testDeletePurchase()
    {
        $this->assertSame($this->classApiClient->deletePurchase(
            array(
                'userId' => 'aot7sbaos7tasftasf',
                'itemId' => 'item-234',
                'timestamp' => 1435080580
            ))
            , 'ok'
        );
        $this->assertSame($this->classApiClient->deletePurchase(
            array(
                'userId' => 'o7oaysdasn8f7af',
                'itemId' => 'item-200',
                'timestamp' => 1435080580
            ))
            , 'ok'
        );
    }

    public function testDeleteDetailViews()
    {
        $this->assertSame($this->classApiClient->deleteDetailView(
            array(
                'userId' => 'aot7sbaos7tasftasf',
                'itemId' => 'item-234',
                'timestamp' => 1435080580
            ))
            , 'ok'
        );
        $this->assertSame($this->classApiClient->deleteDetailView(
            array(
                'userId' => 'o7oaysdasn8f7af',
                'itemId' => 'item-200',
                'timestamp' => 1435080580
            ))
            , 'ok'
        );
    }

    public function testDeleteUser()
    {
        $this->assertSame($this->classApiClient->deleteUser('aot7sbaos7tasftasf'), 'ok');
    }

    public function testDeleteSeriesItems()
    {
        $this->assertSame(
            $this->classApiClient->deleteSeriesItems(
                'serie-1',
                array(
                    'itemType' => 'item',
                    'itemId' => 'item-234',
                    'time' => 1435080580
                )
            ),
            'ok'
        );
    }

    public function testDeleteSeries()
    {
        $this->assertSame($this->classApiClient->deleteSeries('serie-1'), 'ok');
    }

    public function testDeleteItemValues()
    {
        $this->assertSame($this->classApiClient->deleteItemValues('item-234', array('description', 'available')), 'ok');
    }

    public function testDeleteItemProperties()
    {
        $this->assertJsonStringEqualsJsonString(
            json_encode(
                $this->classApiClient->deleteItemProperties(
                    array('name', 'available', 'description', 'tags', 'price', 'added')
                )
            )
            , '{"name":"ok","available":"ok","description":"ok","tags":"ok","price":"ok","added":"ok"}'
        );
    }

    public function testDeleteProperty()
    {
        $this->assertSame($this->classApiClient->deleteItemProperty('extra_description'), 'ok');
    }
}