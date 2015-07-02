<?php
/**
 * Definition of RESTful methods and URL of Modgen API
 * @copyright (c) Modgen s.r.o.
 * @author Mirek Ratman
 * @since 2015-06-22
 * @license The MIT License (MIT)
 */

namespace Php\Api\Enums;

use Php\Api\ApiException;
use Php\Api\Enums\ExceptionsList;

class RestfulEnum
{

    /**
     * Definition of RESTful urls and methods
     * @var array
     */
    private static $restfulDefinition = array(
        'addItem' => array(
            'method' => 'POST',
            'url' => '/items/__itemid__'
        ),
        'addItemId' => array(
            'method' => 'PUT',
            'url' => '/items/__itemid__'
        ),
        'setItemProperty' => array(
            'method' => 'PUT',
            'url' => '/items/properties/__propertyname__?type=__type__'
        ),
        'deleteItemProperty' => array(
            'method' => 'DELETE',
            'url' => '/items/properties/__propertyname__'
        ),
        'getItemPropertyInfo' => array(
            'method' => 'GET',
            'url' => '/items/properties/__propertyname__'
        ),
        'getItemProperties' => array(
            'method' => 'GET',
            'url' => '/items/properties/list/'
        ),
        'setItemRole' => array(
            'method' => 'PUT',
            'url' => '/items/properties/roles/__rolename__'
        ),
        'deleteItemValues' => array(
            'method' => 'POST',
            'url' => '/items/__itemid__'
        ),


        'addSeries' => array(
            'method' => 'PUT',
            'url' => '/series/__seriesid__'
        ),
        'listSeries' => array(
            'method' => 'GET',
            'url' => '/series/list/'
        ),
        'deleteSeries' => array(
            'method' => 'DELETE',
            'url' => '/series/__seriesid__'
        ),
        'addIntoSeries' => array(
            'method' => 'POST',
            'url' => '/series/__seriesid__/items/'
        ),
        'listSeriesItems' => array(
            'method' => 'GET',
            'url' => '/series/__seriesid__/items/'
        ),
        'deleteSeriesItems' => array(
            'method' => 'DELETE',
            'url' => '/series/__seriesid__/items/'
        ),

        'addUser' => array(
            'method' => 'PUT',
            'url' => '/users/__userid__'
        ),
        'mergeUsers' => array(
            'method' => 'PUT',
            'url' => '/users/__targetuserid__/merge/__userid__'
        ),
        'listUsers' => array(
            'method' => 'GET',
            'url' => '/users/list/'
        ),
        'deleteUser' => array(
            'method' => 'DELETE',
            'url' => '/users/__userid__'
        ),

        'addDetailView' => array(
            'method' => 'POST',
            'url' => '/detailviews/'
        ),
        'listItemDetailViews' => array(
            'method' => 'GET',
            'url' => '/items/__itemid__/detailviews/'
        ),
        'listUserDetailViews' => array(
            'method' => 'GET',
            'url' => '/users/__userid__/detailviews/'
        ),
        'deleteDetailView' => array(
            'method' => 'DELETE',
            'url' => '/detailviews/'
        ),

        'addPurchase' => array(
            'method' => 'POST',
            'url' => '/purchases/'
        ),
        'listItemPurchases' => array(
            'method' => 'GET',
            'url' => '/items/__itemid__/purchases/'
        ),
        'listUserPurchases' => array(
            'method' => 'GET',
            'url' => '/users/__userid__/purchases/'
        ),
        'deletePurchase' => array(
            'method' => 'DELETE',
            'url' => '/purchases/'
        ),

        'addRating' => array(
            'method' => 'POST',
            'url' => '/ratings/'
        ),
        'listItemRatings' => array(
            'method' => 'GET',
            'url' => '/items/__itemid__/ratings/'
        ),
        'listUserRatings' => array(
            'method' => 'GET',
            'url' => '/users/__userid__/ratings/'
        ),
        'deleteRating' => array(
            'method' => 'DELETE',
            'url' => '/ratings/'
        ),

        'addBoomark' => array(
            'method' => 'POST',
            'url' => '/bookmarks/'
        ),
        'listItemBoomarks' => array(
            'method' => 'GET',
            'url' => '/items/__itemid__/bookmarks/'
        ),
        'listUserBoomarks' => array(
            'method' => 'GET',
            'url' => '/users/__userid__/bookmarks/'
        ),
        'deleteBoomark' => array(
            'method' => 'DELETE',
            'url' => '/bookmarks/'
        ),

        'getUserBasedRecommendation' => array(
            'method' => 'GET',
            'url' => '/users/__userid__/recomms/?count=__count__'
        ),

        'getItemBasedRecommendation' => array(
            'method' => 'GET',
            'url' => '/items/__itemid__/recomms/?count=__count__'
        ),

        'resetDatabase' => array(
            'method' => 'DELETE',
            'url' => '/'
        )
    );

    /**
     * Add/Modify RESTful method extend url for particular method for API call
     * @param string $functionName - name of the function which use definition
     * @param string $definition - RESTful method/url definition
     */
    public static function extendDefinition($functionName, array $definition)
    {
        self::$restfulDefinition[$functionName] = $definition;
    }

    /**
     * Get RESTful definition
     * @param string $functionName - name of the function which use definition
     * @return array
     */
    public static function getDefinition($functionName)
    {
        return self::$restfulDefinition[$functionName];
    }

    /**
     * Return RESTful method for API call
     * @param string $apiMethod - name of the API method in $restfulDefinition
     * @return string
     */
    public static function getMethod($apiMethod)
    {
        if (array_key_exists($apiMethod, self::$restfulDefinition)) {
            return self::$restfulDefinition[$apiMethod]['method'];
        } else {
            throw new ApiException(
                ExceptionsEnum::getMessage(
                    ExceptionsEnum::API_RESTFUL_METHOD_NOT_EXISTS,
                    array('__method__' => $apiMethod)
                ),
                500
            );
        }
    }

    /**
     * Return RESTful url for API call with filled params
     * @param string $apiMethod - name of the API method in $restfulDefinition
     * @param array $params - associate array of param names and values to change
     * @return string
     */
    public static function getUrl($apiMethod, array $params = array())
    {
        if (array_key_exists($apiMethod, self::$restfulDefinition)) {
            $url = strtr(self::$restfulDefinition[$apiMethod]['url'], $params);
            return preg_replace('/__.*__/','',$url);
        } else {
            throw new ApiException(
                ExceptionsEnum::getMessage(
                    ExceptionsEnum::API_RESTFUL_METHOD_NOT_EXISTS,
                    array('__method__' => $apiMethod)
                ),
                500
            );
        }
    }

}
