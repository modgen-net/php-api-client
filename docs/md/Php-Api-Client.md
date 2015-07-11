Php\Api\Client
===============






* Class name: Client
* Namespace: Php\Api





Properties
----------


### $host

    private mixed $host = 'https://rapi.modgen.net'







### $db

    private mixed $db = ''







### $key

    private mixed $key = ''







### $transport

    private mixed $transport







### $debug

    private mixed $debug







Methods
-------



### getHost




    string Php\Api\Client::getHost()






### setHost




    mixed Php\Api\Client::setHost(string $host)



##### Arguments
  * $host **string**




### getDb




    string Php\Api\Client::getDb()






### setDb




    mixed Php\Api\Client::setDb(string $db)



##### Arguments
  * $db **string**




### getKey




    string Php\Api\Client::getKey()






### setKey




    mixed Php\Api\Client::setKey(string $key)



##### Arguments
  * $key **string**




### getTransport




    mixed Php\Api\Client::getTransport()






### setTransport




    mixed Php\Api\Client::setTransport(mixed $transport)



##### Arguments
  * $transport **mixed**




### getDebug




    boolean Php\Api\Client::getDebug()






### setDebug




    mixed Php\Api\Client::setDebug(boolean $debug)



##### Arguments
  * $debug **boolean**




### __construct
Class constructor



    mixed Php\Api\Client::__construct(string $host, string $db, string $key, $debugMode)



##### Arguments
  * $host **string** - Modgen API host
  * $db **string** - DB ID where data will be stored
  * $key **string** - unique API key
  * $debugMode **mixed**




### addItem
Method will add item to DB



    string Php\Api\Client::addItem(string $itemId)



##### Arguments
  * $itemId **string** - item ID name




### listItems
Method will list items from DB



    array Php\Api\Client::listItems(string $filter)



##### Arguments
  * $filter **string** - An MQL expression to filter results. See API DOC -&gt; 2.1.2 LIST ITEMS




### deleteItem
Method will delete item from DB. Also other data will be deleted. See API DOCs for more info



    string Php\Api\Client::deleteItem(string $itemId)



##### Arguments
  * $itemId **string** - name of item ID




### deleteItems
Method will delete items defined in $itemsList as a array of its names



    array Php\Api\Client::deleteItems(array $itemsList)



##### Arguments
  * $itemsList **array** - items list to delete




### insertItem
Method will insert item with its values to DB



    string Php\Api\Client::insertItem(array $data, $idFiled, boolean $cascadeCreate)



##### Arguments
  * $data **array** - data to add
  * $idFiled **mixed**
  * $cascadeCreate **boolean** - create automatically item




### addItemProperty
Method will add one item property and its type to DB



    array Php\Api\Client::addItemProperty(string $propertyName, string $propertyType)



##### Arguments
  * $propertyName **string** - name of the property
  * $propertyType **string** - type of property (int, double, string, boolean, timestamp, set). See API DOCs for details.




### addItemProperties
Extra API CLient method will set item properties base on given list of propertyName=>propertyType and add necessary roles



    array Php\Api\Client::addItemProperties(array $properties)



##### Arguments
  * $properties **array** - list of properties to set (int, double, string, boolean, timestamp, set, price)




### getItemPropertyInfo
Method will get item property information



    \Php\Api\JSON Php\Api\Client::getItemPropertyInfo(string $propertyName)



##### Arguments
  * $propertyName **string** - property name to delete




### getItemProperties
Method will receive item properties



    array Php\Api\Client::getItemProperties()






### deleteItemProperty
Method will delete item property



    string Php\Api\Client::deleteItemProperty(string $propertyName)



##### Arguments
  * $propertyName **string** - property name to delete




### deleteItemProperties
Method will delete item properties defined in $propertiesNameList as a array of property names



    array Php\Api\Client::deleteItemProperties(array $propertiesNameList)



##### Arguments
  * $propertiesNameList **array** - properties list to delete




### deleteAllItemProperties
Method will delete all item properties



    mixed Php\Api\Client::deleteAllItemProperties()






### setItemValues
Method will set property role base on propertyName parameter



    string Php\Api\Client::setItemValues(array $itemId, array $data)



##### Arguments
  * $itemId **array** - item ID where its properties will be filled by values
  * $data **array** - property name =&gt; value data set




### deleteItemValues
Method will add item to DB



    string Php\Api\Client::deleteItemValues(string $itemId, array $properties)



##### Arguments
  * $itemId **string** - item ID to identify element where some properties will be deleted
  * $properties **array** - list of properties for which values will be deleted




### setItemRole
Method will set property role base on propertyName parameter



    string Php\Api\Client::setItemRole(string $propertyName)



##### Arguments
  * $propertyName **string** - property name needed to set role




### addSeries
Method will add series to DB



    string Php\Api\Client::addSeries(string $seriesId)



##### Arguments
  * $seriesId **string** - series ID




### listSeries
Method will list series



    array Php\Api\Client::listSeries()






### deleteSeries
Method will delete series



    string Php\Api\Client::deleteSeries(string $seriesId)



##### Arguments
  * $seriesId **string** - series ID




### addIntoSeries
Method will add series items



    string Php\Api\Client::addIntoSeries(string $seriesId, array $data, boolean $cascadeCreate)



##### Arguments
  * $seriesId **string** - series ID
  * $data **array** - list of elements required for series items
  * $cascadeCreate **boolean** - create automatically item with empty fields when purchase of this item exists




### listSeriesItems
Method will list series items



    array Php\Api\Client::listSeriesItems(string $seriesId)



##### Arguments
  * $seriesId **string** - series ID




### deleteSeriesItems
Method will delete series items



    string Php\Api\Client::deleteSeriesItems($seriesId, array $properties)



##### Arguments
  * $seriesId **mixed**
  * $properties **array** - list of elements required for series items to delete (below)




### addUser
Method will add user



    string Php\Api\Client::addUser(string $userId)



##### Arguments
  * $userId **string** - user id to add




### mergeUsers
Method will merge users



    string Php\Api\Client::mergeUsers(string $targetUserId, string $userId)



##### Arguments
  * $targetUserId **string** - ID of the target (final) user
  * $userId **string** - ID of the user which will be merget with $targetUserId. This user ID will be deleted (is merged) from DB




### listUsers
Method will list users



    array Php\Api\Client::listUsers()






### deleteUser
Method will delete user



    string Php\Api\Client::deleteUser(string $userId)



##### Arguments
  * $userId **string** - ID of the user which will be deleted




### addDetailView
Method will add detail views



    string Php\Api\Client::addDetailView(array $data, boolean $cascadeCreate)



##### Arguments
  * $data **array** - list of elements required for detailview (below)
  * $cascadeCreate **boolean** - create automatically item with empty fields when purchase of this item exists




### listItemDetailViews
Method will list item detail views



    array Php\Api\Client::listItemDetailViews(string $itemId)



##### Arguments
  * $itemId **string** - ID of the item




### listUserDetailViews
Method will list user detail views



    array Php\Api\Client::listUserDetailViews(string $userId)



##### Arguments
  * $userId **string** - ID of the user




### deleteDetailView
Method will delete detail views



    string Php\Api\Client::deleteDetailView(array $properties)



##### Arguments
  * $properties **array** - list of properties which will define detail view




### addPurchase
Method will add purchase



    string Php\Api\Client::addPurchase(array $data, boolean $cascadeCreate)



##### Arguments
  * $data **array** - list of elements required for purchase (below)
  * $cascadeCreate **boolean** - create automatically item with empty fields when purchase of this item exists




### listItemPurchases
Method will list item purchases



    mixed Php\Api\Client::listItemPurchases(string $itemId)



##### Arguments
  * $itemId **string** - ID of the item




### listUserPurchases
Method will list user purchases



    mixed Php\Api\Client::listUserPurchases(string $userId)



##### Arguments
  * $userId **string** - ID of the user




### deletePurchase
Method will delete purchase



    string Php\Api\Client::deletePurchase(array $properties)



##### Arguments
  * $properties **array** - list of properties which will define purchase




### addRating
Method will add rating



    string Php\Api\Client::addRating(array $data, boolean $cascadeCreate)



##### Arguments
  * $data **array** - list of elements required for detailview (below)
  * $cascadeCreate **boolean** - create automatically item with empty fields when purchase of this item exists




### listItemRatings
Method will list item ratings



    mixed Php\Api\Client::listItemRatings(string $itemId)



##### Arguments
  * $itemId **string** - ID of the item




### listUserRatings
Method will list user ratings



    mixed Php\Api\Client::listUserRatings(string $userId)



##### Arguments
  * $userId **string** - ID of the user




### deleteRating
Method will delete rating



    string Php\Api\Client::deleteRating(array $properties)



##### Arguments
  * $properties **array** - list of properties which will define rating




### addBoomark
Method will add boomark



    string Php\Api\Client::addBoomark(array $data, boolean $cascadeCreate)



##### Arguments
  * $data **array** - list of elements required for purchase (below)
  * $cascadeCreate **boolean** - create automatically item with empty fields when purchase of this item exists




### listItemBoomarks
Method will list item boomarks



    mixed Php\Api\Client::listItemBoomarks(string $itemId)



##### Arguments
  * $itemId **string** - ID of the item




### listUserBoomarks
Method will list user boomarks



    mixed Php\Api\Client::listUserBoomarks(string $userId)



##### Arguments
  * $userId **string** - ID of the user




### deleteBoomark
Method will delete boomark



    string Php\Api\Client::deleteBoomark(array $properties)



##### Arguments
  * $properties **array** - list of properties which will define boomark




### getUserBasedRecommendation
Method will return user base recommendations



    array Php\Api\Client::getUserBasedRecommendation(string $userId, integer $count, array $params)



##### Arguments
  * $userId **string** - id of the user
  * $count **integer** - number of items that will be received
  * $params **array** - list of parameters required for recommendation




### getItemBasedRecommendation
Method will return item base recommendations



    array Php\Api\Client::getItemBasedRecommendation(string $itemId, integer $count, array $params)



##### Arguments
  * $itemId **string** - id of the item
  * $count **integer** - number of items that will be received
  * $params **array** - list of parameters required for recommendation




### resetDatabase
Method will reset DB



    mixed Php\Api\Client::resetDatabase(boolean $confirm)



##### Arguments
  * $confirm **boolean** - delete confirmation


