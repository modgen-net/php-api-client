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

require_once(__DIR__ . '/../test/auth.dev.php');

//$url, $db and $key are stored in auth.php file above.
//Please update the file using your access credentials
//and change auth.dev.php to auth.php in the line above.
$classApiClient = new Php\Api\Client($url, $db, $key, false);

echo "<pre>";

try {
    //Reset database example
    //Commented because of security reasons. This method under will erase whole data from DB !!!
    //Please use it carefully !
    //print_r($classApiClient->resetDatabase(true));


    //Series exampels
    print_r($classApiClient->addSeries('serie-1'));
    print_r($classApiClient->listSeries()); 
    print_r($classApiClient->addIntoSeries('serie-1', array(
            'itemType' => 'item',
            'itemId' => 'item-234',
            'time' => 1435080580
        )
    )); 
    print_r(json_encode($classApiClient->listSeriesItems('serie-1'))); 
    print_r($classApiClient->deleteSeriesItems('serie-1',array(
            'itemType' => 'item',
            'itemId' => 'item-234',
            'time' => 1435080580
        )
    )); 
    print_r($classApiClient->deleteSeries('serie-1'));


    if ($debug = $classApiClient->getDebug()) {
        print_r($debug->__toJson());
    };


} catch (\Php\Api\ApiException $e) {
    print_r($e);
}


