<?php
/**
 * Created by PhpStorm.
 * User: mirekratman
 * Date: 25/05/15
 * Time: 10:33
 */

ERROR_REPORTING(E_ALL);
require __DIR__ . "/../src/loader.php";

date_default_timezone_set('UTC');

$url = 'http://rapi-dev.modgen.net';
$db = 'shopexpo-test';
$key = 'DyioS5vct4fyqbjjr7Yno8dUFALYjAZe0JP3yR65aCNdtbjk92F9gxU1yDAVR7QS';

$classApiClient = new Php\Api\Client($url, $db, $key, false);

echo "<pre>";

try {

    //$classApiClient->resetDatabase(true); //ok

    print_r($classApiClient->setItemProperty('extra_description', 'string'));  //ok
    //print_r($classApiClient->getItemProperties());  //ok
    //print_r($classApiClient->getItemPropertyInfo('extra_description')); //ok
    print_r($classApiClient->deleteItemProperty('extra_description')); //ok


    /*
    print_r(json_encode($classApiClient->setItemProperties(array(
        'name' => 'string',
        'available' => 'boolean',
        'description' => 'string',
        'tags' => 'set',
        'price' => 'price',
        'added' => 'timestamp'
    )))); //ok
    print_r($classApiClient->deleteItemProperties(array('name', 'available', 'description', 'tags', 'price', 'added'))); //ok
    */

    /*
    print_r($classApiClient->addItem(array(
            'id' 			=> 'item-234',
            'name' 			=> 'Iphone 4s 16GB',
            'available' 	=> true,
            'description' 	=> 'Some description of iPhone 4s 16BG including specifications, functions',
            'tags' 			=> json_encode(array('iphone,4s,phone')),
            'price' 		=> '195.52',
            'added'		    => '1435080580'
        )
    )); //ok

    print_r($classApiClient->addItem(array(
            'yourid'	    => 'item-235',
            'name' 			=> 'Iphone 4s 16GB',
            'available' 	=> true,
            'description' 	=> 'Some description of iPhone 4s 16BG including specifications, functions',
            'tags' 			=> json_encode(array('iphone,4s,phone')),
            'price' 		=> '195.52',
            'added'		    => '1435080580'
        ),
        'yourid'
    )); //ok
    */
    //print_r($classApiClient->deleteItemValues('item-235',array('description','available'))); //ok

    //print_r($classApiClient->addSeries('serie-1')); //ok
    //print_r($classApiClient->listSeries()); //ok
    //print_r($classApiClient->deleteSeries('serie-1')); //ok
    /*
    print_r($classApiClient->addIntoSeries('serie-1', array(
            'itemType' => 'item',
            'itemId' => 'item-234',
            'time' => 1435080580
        )
    )); //ok
    */
    //print_r($classApiClient->listSeriesItems('serie-1')); //ok
    //print_r($classApiClient->deleteSeries('serie-1')); //ok


    //print_r($classApiClient->addUser('aot7sbaos7tasftasf')); //ok
    //print_r($classApiClient->addUser('o7oaysdasn8f7af')); //ok
    //print_r($classApiClient->mergeUsers('aot7sbaos7tasftasf', 'o7oaysdasn8f7af')); //ok
    //print_r($classApiClient->listUsers()); //ok
    //print_r($classApiClient->deleteUser('aot7sbaos7tasftasf')); //ok


    /*
    print_r($classApiClient->addDetailView(array(
            'userId' => 'aot7sbaos7tasftasf',
            'itemId' => 'item-234',
            'timestamp' => 1435080580
        )
    )); //ok
    print_r($classApiClient->addDetailView(array(
            'userId' => 'o7oaysdasn8f7af',
            'itemId' => 'item-200',
            'timestamp' => 1435080580
        )
    )); //ok
    */
    //print_r($classApiClient->listItemDetailViews('item-234')); //ok
    //print_r($classApiClient->listUserDetailViews('o7oaysdasn8f7af')); //ok
    /*
    print_r($classApiClient->deleteDetailView(array(
            'userId' => 'o7oaysdasn8f7af',
            'itemId' => 'item-200',
            'timestamp' => 1435080580
        )
    )); //ok
    */


    /*
    print_r($classApiClient->addPurchase(array(
            'userId' => 'aot7sbaos7tasftasf',
            'itemId' => 'item-234',
            'timestamp' => 1435080580
        )
    )); //ok
    print_r($classApiClient->addPurchase(array(
            'userId' => 'o7oaysdasn8f7af',
            'itemId' => 'item-200',
            'timestamp' => 1435080580
        )
    )); //ok
    */
    //print_r($classApiClient->listItemPurchases('item-234')); //ok
    //print_r($classApiClient->listUserPurchases('o7oaysdasn8f7af')); //ok
    /*
    print_r($classApiClient->deletePurchase(array(
            'userId' => 'o7oaysdasn8f7af',
            'itemId' => 'item-200',
            'timestamp' => 1435080580
        )
    )); //ok
    */


    /*
    print_r($classApiClient->addRating(array(
            'userId' => 'aot7sbaos7tasftasf',
            'itemId' => 'item-234',
            'timestamp' => 1435080580,
            'rating' => '0.3'
        )
    )); //ok
    print_r($classApiClient->addRating(array(
            'userId' => 'o7oaysdasn8f7af',
            'itemId' => 'item-200',
            'timestamp' => 1435080580,
            'rating' => '0.3'
        )
    )); //ok
    */
    //print_r($classApiClient->listItemRatings('item-234')); //ok
    //print_r($classApiClient->listUserRatings('o7oaysdasn8f7af')); //ok
    /*
    print_r($classApiClient->deleteRating(array(
            'userId' => 'o7oaysdasn8f7af',
            'itemId' => 'item-200',
            'timestamp' => 1435080580
        )
    )); //ok
    */


    /*
    print_r($classApiClient->addBoomark(array(
            'userId' => 'aot7sbaos7tasftasf',
            'itemId' => 'item-234',
            'timestamp' => 1435080580
        )
    )); //ok
    print_r($classApiClient->addBoomark(array(
            'userId' => 'o7oaysdasn8f7af',
            'itemId' => 'item-200',
            'timestamp' => 1435080580
        )
    )); //ok
    */
    //print_r($classApiClient->listItemBoomarks('item-234')); //ok
    //print_r($classApiClient->listUserBoomarks('o7oaysdasn8f7af')); //ok
    /*
    print_r($classApiClient->deleteBoomark(array(
            'userId' => 'o7oaysdasn8f7af',
            'itemId' => 'item-200',
            'timestamp' => 1435080580
        )
    )); //ok
    */

    //print_r($classApiClient->getUserBasedRecommendation('o7oaysdasn8f7af',2)); //ok
    //print_r($classApiClient->getUserBasedRecommendation('o7oaysdasn8f7af',2,array('diversity'=>1.0))); //ok
    //print_r($classApiClient->getItemBasedRecommendation('item-234',10,array('diversity'=>0.2))); //ok


    if ($debug = $classApiClient->getDebug()) {
        print_r($debug->__toJson());
    };


} catch (\Php\Api\ApiException $e) {
    print_r($e);
}


