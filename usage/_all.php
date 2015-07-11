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

    //Items
    /*
    echo "\n\n### Adding item\n";
    print_r($classApiClient->addItem('item-1'));
    echo "\n\n### Listing items\n";
    print_r($classApiClient->listItems());
    echo "\n\n### Deleting item\n";
    print_r($classApiClient->deleteItem('item-1'));
    */

    //Item properties examples
    /*
    echo "\n\n### Adding item property\n";
    print_r($classApiClient->addItemProperty('extra_description', 'string'));
    echo "\n\n### Getting item property info\n";
    print_r($classApiClient->getItemPropertyInfo('extra_description'));
    echo "\n\n### Getting item properties\n";
    print_r($classApiClient->getItemProperties());
    echo "\n\n### Delete item property\n";
    print_r($classApiClient->deleteItemProperty('extra_description'));
    */

    /*
    echo "\n\n### Add item properties\n";
    print_r($classApiClient->addItemProperties(array(
        'name' => 'string',
        'available' => 'boolean',
        'description' => 'string',
        'price' => 'price',
        'added' => 'timestamp'
    )));
    echo "\n\n### Delete item properties\n";
    print_r($classApiClient->deleteItemProperties(array('name','available')));
    echo "\n\n### Delete all item properties\n";
    print_r($classApiClient->deleteAllItemProperties());
    */

    //Item values
    /*
    echo "\n\n### Adding item\n";
    print_r($classApiClient->addItem('item-1'));
    echo "\n\n### Delete item properties\n";
    print_r($classApiClient->addItemProperties(array(
        'name' => 'string',
        'available' => 'boolean',
        'description' => 'string',
        'tags' => 'set',
        'price' => 'price',
        'added' => 'timestamp'
    )));
    echo "\n\n### Setting item values\n";
    print_r($classApiClient->setItemValues('item-1',array(
        'name'          => 'Iphone 4s',
        'available'     => 1,
        'description'   => 'Iphone 4s 32GB white',
        'tags' 			=> json_encode(array('iphone,4s,phone')),
        'price'         => 1959,
        'added'         => 1435080580
    )));
    echo "\n\n### Delete item values\n";
    print_r($classApiClient->deleteItemValues('item-1',array(
        'name',
        'available',
        'description',
        'price',
        'added'
    )));
    echo "\n\n### Delete item properties\n";
    print_r($classApiClient->deleteAllItemProperties());
    echo "\n\n### Delete item\n";
    print_r($classApiClient->deleteItem('item-1'));
    */

    //Operation on Items - simplified way
    /*
    echo "\n\n### Adding item properties\n";
    print_r($classApiClient->addItemProperties(array(
        'name' => 'string',
        'available' => 'boolean',
        'description' => 'string',
        'tags' => 'set',
        'price' => 'price',
        'added' => 'timestamp'
    )));
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
    echo "\n\n### Deleting items (item-1,item-2)\n";
    print_r($classApiClient->deleteItems(array('item-1','item-2')));
    echo "\n\n### Delete item properties\n";
    print_r($classApiClient->deleteAllItemProperties());
    */

    //Series exampels
    /*
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
    */


    //User examples
    /*
    print_r($classApiClient->addUser('aot7sbaos7tasftasf')); 
    print_r($classApiClient->addUser('o7oaysdasn8f7af')); 
    print_r($classApiClient->mergeUsers('aot7sbaos7tasftasf', 'o7oaysdasn8f7af')); 
    print_r($classApiClient->listUsers());
    print_r($classApiClient->deleteUser('aot7sbaos7tasftasf'));
    */

    //Detail Views examples
    /*
    print_r($classApiClient->addDetailView(array(
            'userId' => 'aot7sbaos7tasftasf',
            'itemId' => 'item-234',
            'timestamp' => 1435080580
        )
    )); 
    print_r($classApiClient->addDetailView(array(
            'userId' => 'o7oaysdasn8f7af',
            'itemId' => 'item-200',
            'timestamp' => 1435080580
        )
    )); 
    print_r($classApiClient->listItemDetailViews('item-234'));
    print_r($classApiClient->listUserDetailViews('o7oaysdasn8f7af'));
    print_r($classApiClient->deleteDetailView(array(
            'userId' => 'o7oaysdasn8f7af',
            'itemId' => 'item-200',
            'timestamp' => 1435080580
        )
    ));
    */


    //Purchase examples
    /*
    print_r($classApiClient->addPurchase(array(
            'userId' => 'aot7sbaos7tasftasf',
            'itemId' => 'item-234',
            'timestamp' => 1435080580
        )
    )); 
    print_r($classApiClient->addPurchase(array(
            'userId' => 'o7oaysdasn8f7af',
            'itemId' => 'item-200',
            'timestamp' => 1435080580
        )
    )); 
    print_r($classApiClient->listItemPurchases('item-234')); 
    print_r($classApiClient->listUserPurchases('o7oaysdasn8f7af')); 
    print_r($classApiClient->deletePurchase(array(
            'userId' => 'o7oaysdasn8f7af',
            'itemId' => 'item-200',
            'timestamp' => 1435080580
        )
    ));
    */


    //Rating examples
    /*
    print_r($classApiClient->addRating(array(
            'userId' => 'aot7sbaos7tasftasf',
            'itemId' => 'item-234',
            'timestamp' => 1435080580,
            'rating' => '0.3'
        )
    )); 
    print_r($classApiClient->addRating(array(
            'userId' => 'o7oaysdasn8f7af',
            'itemId' => 'item-200',
            'timestamp' => 1435080580,
            'rating' => '0.3'
        )
    )); 
    print_r($classApiClient->listItemRatings('item-234')); 
    print_r($classApiClient->listUserRatings('o7oaysdasn8f7af')); 
    print_r($classApiClient->deleteRating(array(
            'userId' => 'o7oaysdasn8f7af',
            'itemId' => 'item-200',
            'timestamp' => 1435080580
        )
    ));
    */


    //Boomarks examples
    /*
    print_r($classApiClient->addBoomark(array(
            'userId' => 'aot7sbaos7tasftasf',
            'itemId' => 'item-234',
            'timestamp' => 1435080580
        )
    )); 
    print_r($classApiClient->addBoomark(array(
            'userId' => 'o7oaysdasn8f7af',
            'itemId' => 'item-200',
            'timestamp' => 1435080580
        )
    )); 
    print_r($classApiClient->listItemBoomarks('item-234')); 
    print_r($classApiClient->listUserBoomarks('o7oaysdasn8f7af')); 
    print_r($classApiClient->deleteBoomark(array(
            'userId' => 'aot7sbaos7tasftasf',
            'itemId' => 'item-234',
            'timestamp' => 1435080580
        )
    )); 
    print_r($classApiClient->deleteBoomark(array(
            'userId' => 'o7oaysdasn8f7af',
            'itemId' => 'item-200',
            'timestamp' => 1435080580
        )
    ));
    */


    //Recommendation examples
    /*
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
    */


    if ($debug = $classApiClient->getDebug()) {
        print_r($debug->__toJson());
    };


} catch (\Php\Api\ApiException $e) {
    print_r($e);
}


