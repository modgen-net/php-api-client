

CLASS

$classApi = new ModgenApi($db, $key);

	$db - Modgen client database name
	$key - Modgen client db key (hash)



ITEMS

classApi->setItemProperties(array $properties);

	$properties = array(
		'id' 			=> 'string',
		'name' 			=> 'string',
		'available' 	=> 'boolean',
		'description' 	=> 'string',
		'tags' 			=> 'set',
		'price' 		=> 'price',
		'added'			=> 'timestamp',
		......
	)

	Array of item properties needed to create proper DB definition


classApi->getItemProperties($itemId);

	$itemId - ID of the item to get properties


classApi->deleteItemProperty($propertyName);

	$property - name of property to remove


classApi->getItemPropertyInfo($propertyName);

	$property - name of property to receive information 


classApi->addItem(array $elements);

	$elements = array(
		'id' 			=> 'item-234',
		'name' 			=> 'Iphone 4s 16GB',
		'available' 	=> true,
		'description' 	=> 'Some description of iPhone 4s 16BG including specifications, functions',
		'tags' 			=> 'iphone,4s,phone',
		'price' 		=> '195.52',
		'timestamp'		=> '1435080580'
		......
	)


classApi->deleteItemValues(array $properties);

	$properties = array(
		'available',
		'description',
		......
	)




SERIES

classApi->addSeries($seriesId);

	$seriesId - ID of the series


classApi->listSeries();


classApi->deleteUser($seriesId);

	$seriesId - ID of the series


classApi->addToSeries($seriesId, $itemType, $itemId, $time, $duration = null, $autoCreate = true);

	$seriesId - ID of the series
	$itemType - type of item (item,series)
	$itemId - ID of the item
	$time - see API DOC
	$autoCreate - create automatically item with empty fields when purchase of this item exists


classApi->listSeriesItems($seriesId);

	$seriesId - ID of the series


classApi->deleteFromSeries($seriesId, $itemType, $itemId, $time);

	$seriesId - ID of the series
	$itemType - type of item (item,series)
	$itemId - ID of the item
	$time - see API DOC





USERS

classApi->addUser($userId);

	$userId - ID of the user


classApi->mergeUsers($targetUserId, $userId);

	$targetUserId - ID of the target (final) user
	$userId - ID of the user which will be merget with $targetUserId


classApi->listUsers();


classApi->deleteUser($confirm = false);

	$confirm - delete confirmation





PURCHASES

classApi->addDetailView($userId, $itemId, $timestamp, $duration = null, $autoCreate = true);

	$userId - id of the user
	$itemId - id of the purchased item
	$timestamp
	$duration - duration
	$autoCreate - create automatically item with empty fields when purchase of this item exists


classApi->listItemDetailViews($itemId);

	$itemId - id of the purchased item


classApi->listUserDetailView($userId);

	$userId - id of the user


classApi->addPurchase($userId, $itemId, $timestamp, $autoCreate = true);

	$userId - id of the user
	$itemId - id of the purchased item
	$timestamp
	$autoCreate - create automatically item with empty fields when purchase of this item exists


classApi->listItemPurchases($itemId);

	$itemId - id of the purchased item


classApi->listUserPurchases($userId);

	$userId - id of the user


classApi->deletePurchase($userId, $itemId, $timestamp);

	$userId - id of the user
	$itemId - id of the purchased item
	$timestamp




RATINGS

classApi->addRating($userId, $itemId, $rating, $timestamp, $autoCreate = true);

	$userId - id of the user
	$itemId - id of the item
	$rating - rating
	$timestamp
	$autoCreate - create automatically item with empty fields when purchase of this item exists


classApi->listItemRatings($itemId);

	$itemId - id of the rated item


classApi->listUserRatings($userId);

	$userId - id of the user


classApi->deleteRating($userId, $itemId, $timestamp);

	$userId - id of the user
	$itemId - id of the item
	$timestamp




RECOMMENDATION

classApi->getUserBasedRecommendation($userId, $itemCount, $filterMql = null, $boosterMql = null, $allowNonexistent = false, $diversity = 0, $rotationRate = 0, $rotationTime = 0);

	$userId - id of the user
	$itemCount - number of items which will be recommended (returned back)
	$filterMql - define MQL string for filtering - see API DOC
	$boosterMql - define MQL string for boostering - see API DOC
	$allowNonexistent - see API DOC
	$diversity - see API DOC
	$rotationRate - see API DOC
	$rotationTime - see API DOC


classApi->getItemBasedRecommendation($itemId, $itemCount, $filterMql = null, $boosterMql = null, $allowNonexistent = false, $diversity = 0, $rotationRate = 0, $rotationTime = 0);

	$itemId - id of the item
	$itemCount - number of items which will be recommended (returned back)
	$filterMql - define MQL string for filtering - see API DOC
	$boosterMql - define MQL string for boostering - see API DOC
	$allowNonexistent - see API DOC
	$diversity - see API DOC
	$rotationRate - see API DOC
	$rotationTime - see API DOC




MISCELLANEOUS

classApi->resetDatabase($confirm = false);

	$confirm = DB delete confirmation



	