Php\Api\Enums\RestfulEnum
===============






* Class name: RestfulEnum
* Namespace: Php\Api\Enums





Properties
----------


### $restfulDefinition

    private array $restfulDefinition = array('addItem' => array('method' => 'PUT', 'url' => '/items/__itemid__'), 'listItems' => array('method' => 'GET', 'url' => '/items/list/__filter__'), 'deleteItem' => array('method' => 'DELETE', 'url' => '/items/__itemid__'), 'addItemProperty' => array('method' => 'PUT', 'url' => '/items/properties/__propertyname__?type=__type__'), 'getItemPropertyInfo' => array('method' => 'GET', 'url' => '/items/properties/__propertyname__'), 'getItemProperties' => array('method' => 'GET', 'url' => '/items/properties/list/'), 'deleteItemProperty' => array('method' => 'DELETE', 'url' => '/items/properties/__propertyname__'), 'setItemValues' => array('method' => 'POST', 'url' => '/items/__itemid__'), 'setItemRole' => array('method' => 'PUT', 'url' => '/items/properties/roles/__rolename__'), 'deleteItemValues' => array('method' => 'POST', 'url' => '/items/__itemid__'), 'addSeries' => array('method' => 'PUT', 'url' => '/series/__seriesid__'), 'listSeries' => array('method' => 'GET', 'url' => '/series/list/'), 'deleteSeries' => array('method' => 'DELETE', 'url' => '/series/__seriesid__'), 'addIntoSeries' => array('method' => 'POST', 'url' => '/series/__seriesid__/items/'), 'listSeriesItems' => array('method' => 'GET', 'url' => '/series/__seriesid__/items/'), 'deleteSeriesItems' => array('method' => 'DELETE', 'url' => '/series/__seriesid__/items/'), 'addUser' => array('method' => 'PUT', 'url' => '/users/__userid__'), 'mergeUsers' => array('method' => 'PUT', 'url' => '/users/__targetuserid__/merge/__userid__'), 'listUsers' => array('method' => 'GET', 'url' => '/users/list/'), 'deleteUser' => array('method' => 'DELETE', 'url' => '/users/__userid__'), 'addDetailView' => array('method' => 'POST', 'url' => '/detailviews/'), 'listItemDetailViews' => array('method' => 'GET', 'url' => '/items/__itemid__/detailviews/'), 'listUserDetailViews' => array('method' => 'GET', 'url' => '/users/__userid__/detailviews/'), 'deleteDetailView' => array('method' => 'DELETE', 'url' => '/detailviews/'), 'addPurchase' => array('method' => 'POST', 'url' => '/purchases/'), 'listItemPurchases' => array('method' => 'GET', 'url' => '/items/__itemid__/purchases/'), 'listUserPurchases' => array('method' => 'GET', 'url' => '/users/__userid__/purchases/'), 'deletePurchase' => array('method' => 'DELETE', 'url' => '/purchases/'), 'addRating' => array('method' => 'POST', 'url' => '/ratings/'), 'listItemRatings' => array('method' => 'GET', 'url' => '/items/__itemid__/ratings/'), 'listUserRatings' => array('method' => 'GET', 'url' => '/users/__userid__/ratings/'), 'deleteRating' => array('method' => 'DELETE', 'url' => '/ratings/'), 'addBoomark' => array('method' => 'POST', 'url' => '/bookmarks/'), 'listItemBoomarks' => array('method' => 'GET', 'url' => '/items/__itemid__/bookmarks/'), 'listUserBoomarks' => array('method' => 'GET', 'url' => '/users/__userid__/bookmarks/'), 'deleteBoomark' => array('method' => 'DELETE', 'url' => '/bookmarks/'), 'getUserBasedRecommendation' => array('method' => 'GET', 'url' => '/users/__userid__/recomms/?count=__count__'), 'getItemBasedRecommendation' => array('method' => 'GET', 'url' => '/items/__itemid__/recomms/?count=__count__'), 'resetDatabase' => array('method' => 'DELETE', 'url' => '/'), 'insertItem' => array('method' => 'PUT', 'url' => '/items/__itemid__'))

Definition of RESTful urls and methods



* This property is **static**.


Methods
-------



### getDefinitionsList
return RESTful definitions list



    array Php\Api\Enums\RestfulEnum::getDefinitionsList()

* This method is **static**.





### getDefinition
Get RESTful definition



    array Php\Api\Enums\RestfulEnum::getDefinition(string $functionName)

* This method is **static**.


##### Arguments
  * $functionName **string** - name of the function which use definition




### getMethod
Return RESTful method for API call



    string Php\Api\Enums\RestfulEnum::getMethod(string $apiMethod)

* This method is **static**.


##### Arguments
  * $apiMethod **string** - name of the API method in $restfulDefinition




### getUrl
Return RESTful url for API call with filled params



    string Php\Api\Enums\RestfulEnum::getUrl(string $apiMethod, array $params)

* This method is **static**.


##### Arguments
  * $apiMethod **string** - name of the API method in $restfulDefinition
  * $params **array** - associate array of param names and values to change


