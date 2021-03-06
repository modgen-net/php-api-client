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

    //Recommendation examples
    echo "\n\n### Adding user ID aot7sbaos7tasftasf\n";
    print_r($classApiClient->addUser('aot7sbaos7tasftasf'));
    echo "\n\n### Adding user ID o7oaysdasn8f7af\n";
    print_r($classApiClient->addUser('o7oaysdasn8f7af'));

    echo "\n\n### Adding item-1\n";
    print_r($classApiClient->insertItem(array(
            'id' 			=> 'item-1',
            'name' 			=> 'Iphone 4s 16GB',
            'available' 	=> true,
            'description' 	=> 'Some description of iPhone 4s 16BG including specifications, functions',
            'tags' 			=> json_encode(array('iphone,4s,phone')),
            'price' 		=> '195.52',
            'added'		    => '1435080580'
        )
    ));
    echo "\n\n### Adding item-2\n";
    print_r($classApiClient->insertItem(array(
            'yourid'	    => 'item-2',
            'name' 			=> 'Iphone 4s 16GB',
            'available' 	=> true,
            'description' 	=> 'Some description of iPhone 4s 16BG including specifications, functions',
            'tags' 			=> json_encode(array('iphone,4s,phone')),
            'price' 		=> '195.52',
            'added'		    => '1435080580'
        ),
        'yourid'
    ));

    echo "\n\n### Adding purchase of item-1\n";
    print_r($classApiClient->addPurchase(array(
            'userId' => 'aot7sbaos7tasftasf',
            'itemId' => 'item-1',
            'timestamp' => 1435080580
        )
    ));
    echo "\n\n### Adding purchase of item-2\n";
    print_r($classApiClient->addPurchase(array(
            'userId' => 'o7oaysdasn8f7af',
            'itemId' => 'item-2',
            'timestamp' => 1435080580
        )
    ));

    echo "\n\n### Getting recommendation for user aot7sbaos7tasftasf\n";
    print_r($classApiClient->getUserBasedRecommendation('aot7sbaos7tasftasf',1));
    echo "\n\n### Getting recommendation for user aot7sbaos7tasftasf with MQL\n";
    print_r($classApiClient->getUserBasedRecommendation('aot7sbaos7tasftasf',1,array('diversity'=>1.0)));
    echo "\n\n### Getting recommendation for item item-2\n";
    print_r($classApiClient->getItemBasedRecommendation('item-2',10,array('diversity'=>0.2)));

    echo "\n\n### Deleting purchase item-1\n";
    print_r($classApiClient->deletePurchase(array(
            'userId' => 'aot7sbaos7tasftasf',
            'itemId' => 'item-1',
            'timestamp' => 1435080580
        )
    ));
    echo "\n\n### Deleting purchase item-2\n";
    print_r($classApiClient->deletePurchase(array(
            'userId' => 'o7oaysdasn8f7af',
            'itemId' => 'item-2',
            'timestamp' => 1435080580
        )
    ));
    echo "\n\n### Deleting items (item-1,item-2)\n";
    print_r($classApiClient->deleteItems(array('item-1','item-2')));
    echo "\n\n### Deleting user aot7sbaos7tasftasf\n";
    print_r($classApiClient->deleteUser('aot7sbaos7tasftasf'));
    echo "\n\n### Deleting user o7oaysdasn8f7af\n";
    print_r($classApiClient->deleteUser('o7oaysdasn8f7af'));



    if ($debug = $classApiClient->getDebug()) {
        print_r($debug->__toJson());
    };


} catch (\Php\Api\ApiException $e) {
    print_r($e);
}


