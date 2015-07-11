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


    //User examples
    print_r($classApiClient->addUser('aot7sbaos7tasftasf'));
    print_r($classApiClient->addUser('o7oaysdasn8f7af')); 
    print_r($classApiClient->mergeUsers('aot7sbaos7tasftasf', 'o7oaysdasn8f7af')); 
    print_r($classApiClient->listUsers());
    print_r($classApiClient->deleteUser('aot7sbaos7tasftasf'));



    if ($debug = $classApiClient->getDebug()) {
        print_r($debug->__toJson());
    };


} catch (\Php\Api\ApiException $e) {
    print_r($e);
}


