<?php
/**
 * Class responsible of Modgen API communication
 * @copyright (c) Modgen s.r.o.
 * @author Mirek Ratman
 * @since 2015-06-23
 * @license The MIT License (MIT)
 * @todo handle better RestfulEnum strings that are replaced durring URL creation (example change __itemid__ to %__itemid__% to make it more unique)
 */

namespace Php\Api;

use Php\Api\Enums\RestfulEnum;
use Php\Api\Helpers\Property;
use Php\Api\Helpers\Roles;
use Php\Api\Helpers\Debugger;
use Php\Api\Transport\Direct;
use Php\Api\Transport\Transport;
use Php\Api\Enums\ExceptionsEnum;
use Php\Api\ApiException;
use Php\Api\Helpers\CurlResult;

class Client
{
    /*
     * @var string - Modgen API DB ID
     */
    private $host = 'https://rapi.modgen.net';

    /*
     * @var string - Modgen API DB ID
     */
    private $db = '';

    /*
     * @var string - unique API key
     */
    private $key = '';

    /*
     * @var mixed - Instance of Transport class
    */
    private $transport;

    /*
     * @var mixed - debug mode
    */
    private $debug;

    /**
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @param string $host
     */
    public function setHost($host)
    {
        $this->host = $host;
    }

    /**
     * @return string
     */
    public function getDb()
    {
        return $this->db;
    }

    /**
     * @param string $db
     */
    public function setDb($db)
    {
        $this->db = $db;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param string $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * @return mixed
     */
    public function getTransport()
    {
        return $this->transport;
    }

    /**
     * @param mixed $transport
     */
    public function setTransport($transport)
    {
        $transport->setDebug($this->getDebug());
        $transport->setDb($this->getDb());
        $transport->setKey($this->getKey());
        $transport->setHost($this->getHost());

        $this->transport = $transport;
    }

    /**
     * @return boolean
     */
    public function getDebug()
    {
        return $this->debug;
    }

    /**
     * @param boolean $debug
     */
    public function setDebug($debug)
    {
        $this->debug = $debug;
    }

    /**
     * Class constructor
     * @param string $host - Modgen API host
     * @param string $db - DB ID where data will be stored
     * @param string $key - unique API key
     * @param string $key - unique API key
     */
    public function __construct($host, $db, $key, $debugMode = false)
    {
        $this->setHost($host);
        $this->setDb($db);
        $this->setKey($key);
        if ($debugMode === true) {
            $this->setDebug(new Debugger());
        }

        self::setTransport(new Direct());
    }




    /* ITEMS Section */

    /**
     * Method will add item to DB
     * @param string $itemId - item ID name
     * @return string
     * @throws ApiException
     */
    public function addItem($itemId)
    {
        $transport = $this->getTransport();

        $method = RestfulEnum::getMethod(__FUNCTION__);
        $url = RestfulEnum::getUrl(__FUNCTION__, array('__itemid__' => $itemId));

        $transport->addCall($method, $url);

        $response = $transport->process();

        if ($response instanceof CurlResult && $response->getResponseCode() !== 201) {
            throw new ApiException(
                ExceptionsEnum::getMessage(
                    ExceptionsEnum::API_RESPONSE_FAIL,
                    array(
                        '__method__' => __FUNCTION__,
                        '__body__' => $response->getResponseBody()
                    )
                ),
                $response->getResponseCode()
            );
        }

        return $response->getResponseBody();
    }

    /**
     * Method will list items from DB
     * @param string $filter - An MQL expression to filter results. See API DOC -> 2.1.2 LIST ITEMS
     * @return array
     * @throws ApiException
     */
    public function listItems($filter = '')
    {
        $transport = $this->getTransport();

        $method = RestfulEnum::getMethod(__FUNCTION__);
        $url = RestfulEnum::getUrl(__FUNCTION__, array('__filter__' => $filter));

        $transport->addCall($method, $url);

        $response = $transport->process();

        if ($response instanceof CurlResult && $response->getResponseCode() !== 200) {
            throw new ApiException(
                ExceptionsEnum::getMessage(
                    ExceptionsEnum::API_RESPONSE_FAIL,
                    array(
                        '__method__' => __FUNCTION__,
                        '__body__' => $response->getResponseBody()
                    )
                ),
                $response->getResponseCode()
            );
        }

        return $response->getResponseBody();
    }

    /**
     * Method will delete item from DB. Also other data will be deleted. See API DOCs for more info
     * @param string $itemId - name of item ID
     * @return string
     * @throws ApiException
     */
    public function deleteItem($itemId)
    {
        $transport = $this->getTransport();

        $method = RestfulEnum::getMethod(__FUNCTION__);
        $url = RestfulEnum::getUrl(__FUNCTION__, array('__itemid__' => $itemId));
        $transport->addCall($method, $url);

        $response = $transport->process();

        if ($response instanceof CurlResult && $response->getResponseCode() !== 200) {
            throw new ApiException(
                ExceptionsEnum::getMessage(
                    ExceptionsEnum::API_RESPONSE_FAIL,
                    array(
                        '__method__' => __FUNCTION__,
                        '__body__' => $response->getResponseBody()
                    )
                ),
                $response->getResponseCode()
            );
        }

        return $response->getResponseBody();
    }

    /**
     * Method will delete items defined in $itemsList as a array of its names
     * @param array $itemsList - items list to delete
     * @return array
     */
    public function deleteItems(array $itemsList)
    {
        $resultList = array();
        foreach ($itemsList as $item) {
            $resultList[$item] = self::deleteItem($item);
        }

        return $resultList;
    }

    /**
     * Method will insert item with its values to DB
     * @param array $data - data to add
     * @param string $idField - definition of field name which represents main identificator
     * @param boolean $cascadeCreate - create automatically item
     * @return string
     * @throws ApiException
     */
    public function insertItem(array $data, $idFiled = 'id', $cascadeCreate = true)
    {
        if (array_key_exists($idFiled, $data)) {
            $transport = $this->getTransport();

            $method = RestfulEnum::getMethod(__FUNCTION__);
            $url = RestfulEnum::getUrl(__FUNCTION__, array('__itemid__' => $data[$idFiled]));

            //Remove id element from post data
            if (isset($data[$idFiled])) {
                unset($data[$idFiled]);
            }

            if ($cascadeCreate) {
                $data['!cascadeCreate'] = true;
            }

            //Set post data
            $postData = http_build_query($data);

            $transport->addCall($method, $url, $postData);

            $response = $transport->process();

            if ($response instanceof CurlResult && $response->getResponseCode() !== 201) {
                throw new ApiException(
                    ExceptionsEnum::getMessage(
                        ExceptionsEnum::API_RESPONSE_FAIL,
                        array(
                            '__method__' => __FUNCTION__,
                            '__body__' => $response->getResponseBody()
                        )
                    ),
                    $response->getResponseCode()
                );
            }

            return $response->getResponseBody();
        } else {
            throw new ApiException(
                ExceptionsEnum::getMessage(
                    ExceptionsEnum::API_ITEMS_NO_ID_DEFINED,
                    array(
                        '__id__' => $idFiled
                    )
                ),
                500
            );
        }
    }

    /**
     * Method will add one item property and its type to DB
     * @param string $propertyName - name of the property
     * @param string $propertyType - type of property (int, double, string, boolean, timestamp, set). See API DOCs for details.
     * @return array
     * @throws ApiException
     */
    public function addItemProperty($propertyName, $propertyType)
    {
        $transport = $this->getTransport();

        $method = RestfulEnum::getMethod(__FUNCTION__);
        $url = RestfulEnum::getUrl(__FUNCTION__, array(
            '__propertyname__' => $propertyName,
            '__type__' => $propertyType,
        ));

        $transport->addCall($method, $url);

        $response = $transport->process();

        if ($response instanceof CurlResult && $response->getResponseCode() !== 201) {
            throw new ApiException(
                ExceptionsEnum::getMessage(
                    ExceptionsEnum::API_RESPONSE_FAIL,
                    array(
                        '__method__' => __FUNCTION__,
                        '__body__' => json_encode($response->getResponseBody())
                    )
                ),
                $response->getResponseCode()
            );
        }

        return $response->getResponseBody();
    }

    /**
     * Extra API CLient method will set item properties base on given list of propertyName=>propertyType and add necessary roles
     * @param array $properties - list of properties to set (int, double, string, boolean, timestamp, set, price)
     * @return array
     */
    public function addItemProperties(array $properties)
    {
        $propResponseList = array();

        foreach ($properties as $key => $val) {

            $propertyType = $val;
            if ($val === 'price') {
                $propertyType = 'double';
            }

            $propertyResponse = self::addItemProperty($key, $propertyType);
            $roleResponse = self::setItemRole($key, $propertyType);

            $propResponseList[$key] = array(
                'property' => $propertyResponse,
                'role' => $roleResponse
            );
        }

        return $propResponseList;
    }

    /**
     * Method will get item property information
     * @param string $propertyName - property name to delete
     * @return JSON
     * @throws ApiException
     */
    public function getItemPropertyInfo($propertyName)
    {
        $transport = $this->getTransport();

        $method = RestfulEnum::getMethod(__FUNCTION__);
        $url = RestfulEnum::getUrl(__FUNCTION__, array('__propertyname__' => $propertyName));
        $transport->addCall($method, $url);

        $response = $transport->process();

        if ($response instanceof CurlResult && $response->getResponseCode() !== 200) {
            throw new ApiException(
                ExceptionsEnum::getMessage(
                    ExceptionsEnum::API_RESPONSE_FAIL,
                    array(
                        '__method__' => __FUNCTION__,
                        '__body__' => $response->getResponseBody()
                    )
                ),
                $response->getResponseCode()
            );
        }

        return $response->getResponseBody();
    }

    /**
     * Method will receive item properties
     * @return array
     * @throws ApiException
     */
    public function getItemProperties()
    {
        $transport = $this->getTransport();

        $method = RestfulEnum::getMethod(__FUNCTION__);
        $url = RestfulEnum::getUrl(__FUNCTION__);
        $transport->addCall($method, $url);

        $response = $transport->process();

        if ($response instanceof CurlResult && $response->getResponseCode() !== 200) {
            throw new ApiException(
                ExceptionsEnum::getMessage(
                    ExceptionsEnum::API_RESPONSE_FAIL,
                    array(
                        '__method__' => __FUNCTION__,
                        '__body__' => $response->getResponseBody()
                    )
                ),
                $response->getResponseCode()
            );
        }

        //Fix for empty response string "[]"
        return $response->getResponseBody() === '[]' ? false : $response->getResponseBody();
    }

    /**
     * Method will delete item property
     * @param string $propertyName - property name to delete
     * @return string
     * @throws ApiException
     */
    public function deleteItemProperty($propertyName)
    {
        $transport = $this->getTransport();

        $method = RestfulEnum::getMethod(__FUNCTION__);
        $url = RestfulEnum::getUrl(__FUNCTION__, array('__propertyname__' => $propertyName));
        $transport->addCall($method, $url);

        $response = $transport->process();

        if ($response instanceof CurlResult && $response->getResponseCode() !== 200) {
            throw new ApiException(
                ExceptionsEnum::getMessage(
                    ExceptionsEnum::API_RESPONSE_FAIL,
                    array(
                        '__method__' => __FUNCTION__,
                        '__body__' => $response->getResponseBody()
                    )
                ),
                $response->getResponseCode()
            );
        }

        return $response->getResponseBody();
    }

    /**
     * Method will delete item properties defined in $propertiesNameList as a array of property names
     * @param array $propertiesNameList - properties list to delete
     * @return array
     */
    public function deleteItemProperties(array $propertiesNameList)
    {
        $resultList = array();
        foreach ($propertiesNameList as $property) {
            $resultList[$property] = self::deleteItemProperty($property);
        }

        return $resultList;
    }

    /**
     * Method will delete all item properties
     * @param array $propertiesNameList - properties name list to delete
     * @return mixed
     */
    public function deleteAllItemProperties()
    {
        $resultList = array();
        if ($propertiesList = self::getItemProperties()) {
            foreach ($propertiesList as $property) {
                $resultList[$property->name] = self::deleteItemProperty($property->name);
            }

            return $resultList;
        }

        return false;
    }

    /**
     * Method will set property role base on propertyName parameter
     * @param array $itemId - item ID where its properties will be filled by values
     * @param array $data - property name => value data set
     * @return string
     * @throws ApiException
     */
    public function setItemValues($itemId, $data)
    {
        $transport = $this->getTransport();

        $method = RestfulEnum::getMethod(__FUNCTION__);
        $url = RestfulEnum::getUrl(__FUNCTION__, array('__itemid__' => $itemId));

        //Prepare POST data
        $postData = http_build_query($data);

        //Do call
        $transport->addCall($method, $url, $postData);

        $response = $transport->process();

        if ($response instanceof CurlResult && $response->getResponseCode() !== 200) {
            throw new ApiException(
                ExceptionsEnum::getMessage(
                    ExceptionsEnum::API_RESPONSE_FAIL,
                    array(
                        '__method__' => __FUNCTION__,
                        '__body__' => $response->getResponseBody()
                    )
                ),
                $response->getResponseCode()
            );
        }

        return $response->getResponseBody();
    }

    /**
     * Method will add item to DB
     * @param string $itemId - item ID to identify element where some properties will be deleted
     * @param array $properties - list of properties for which values will be deleted
     * @return string
     * @throws ApiException
     * @todo handle right deletion for properties defined as 'SET'
     */
    public function deleteItemValues($itemId, array $properties)
    {
        $transport = $this->getTransport();

        $method = RestfulEnum::getMethod(__FUNCTION__);
        $url = RestfulEnum::getUrl(__FUNCTION__, array('__itemid__' => $itemId));

        //Set post data
        $postData = http_build_query(array_fill_keys($properties, 'null'));

        $transport->addCall($method, $url, $postData);

        $response = $transport->process();

        if ($response instanceof CurlResult && $response->getResponseCode() !== 200) {
            throw new ApiException(
                ExceptionsEnum::getMessage(
                    ExceptionsEnum::API_RESPONSE_FAIL,
                    array(
                        '__method__' => __FUNCTION__,
                        '__body__' => $response->getResponseBody()
                    )
                ),
                $response->getResponseCode()
            );
        }

        return $response->getResponseBody();
    }

    /**
     * Method will set property role base on propertyName parameter
     * @param string $propertyName - property name needed to set role
     * @param string $roleType - force property role type. Roles are defined in Roles.php class
     * @return string
     * @throws ApiException
     * @todo Move role types definition to ENUM
     */
    public function setItemRole($propertyName, $roleType = null)
    {
        //Get right role name
        $role = Roles::getPropertyType($propertyName, $roleType);
        if ($role) {
            $transport = $this->getTransport();

            $method = RestfulEnum::getMethod(__FUNCTION__);
            $url = RestfulEnum::getUrl(__FUNCTION__, array('__rolename__' => $role));

            //Add url query data
            $url = $url . '?' . http_build_query(array('propertyName' => $propertyName));

            //Do call
            $transport->addCall($method, $url);

            $response = $transport->process();

            if ($response instanceof CurlResult && $response->getResponseCode() !== 200) {
                throw new ApiException(
                    ExceptionsEnum::getMessage(
                        ExceptionsEnum::API_RESPONSE_FAIL,
                        array(
                            '__method__' => __FUNCTION__,
                            '__body__' => $response->getResponseBody()
                        )
                    ),
                    $response->getResponseCode()
                );
            }

            return $response->getResponseBody();
        } else {
            return 'not_needed';
        }
    }

    /**
     * Method will add series to DB
     * @param string $seriesId - series ID
     * @return string
     * @throws ApiException
     */
    public function addSeries($seriesId)
    {
        $transport = $this->getTransport();

        $method = RestfulEnum::getMethod(__FUNCTION__);
        $url = RestfulEnum::getUrl(__FUNCTION__, array('__seriesid__' => $seriesId));
        $transport->addCall($method, $url);

        $response = $transport->process();

        if ($response instanceof CurlResult && $response->getResponseCode() !== 201) {
            throw new ApiException(
                ExceptionsEnum::getMessage(
                    ExceptionsEnum::API_RESPONSE_FAIL,
                    array(
                        '__method__' => __FUNCTION__,
                        '__body__' => $response->getResponseBody()
                    )
                ),
                $response->getResponseCode()
            );
        }

        return $response->getResponseBody();
    }

    /**
     * Method will list series
     * @return array
     * @throws ApiException
     */
    public function listSeries()
    {
        $transport = $this->getTransport();

        $method = RestfulEnum::getMethod(__FUNCTION__);
        $url = RestfulEnum::getUrl(__FUNCTION__);
        $transport->addCall($method, $url);

        $response = $transport->process();

        if ($response instanceof CurlResult && $response->getResponseCode() !== 200) {
            throw new ApiException(
                ExceptionsEnum::getMessage(
                    ExceptionsEnum::API_RESPONSE_FAIL,
                    array(
                        '__method__' => __FUNCTION__,
                        '__body__' => $response->getResponseBody()
                    )
                ),
                $response->getResponseCode()
            );
        }

        return $response->getResponseBody();
    }

    /**
     * Method will delete series
     * @param string $seriesId - series ID
     * @return string
     * @throws ApiException
     */
    public function deleteSeries($seriesId)
    {
        $transport = $this->getTransport();

        $method = RestfulEnum::getMethod(__FUNCTION__);
        $url = RestfulEnum::getUrl(__FUNCTION__, array('__seriesid__' => $seriesId));
        $transport->addCall($method, $url);

        $response = $transport->process();

        if ($response instanceof CurlResult && $response->getResponseCode() !== 200) {
            throw new ApiException(
                ExceptionsEnum::getMessage(
                    ExceptionsEnum::API_RESPONSE_FAIL,
                    array(
                        '__method__' => __FUNCTION__,
                        '__body__' => $response->getResponseBody()
                    )
                ),
                $response->getResponseCode()
            );
        }

        return $response->getResponseBody();
    }

    /**
     * Method will add series items
     * @param string $seriesId - series ID
     * @param array $data - list of elements required for series items
     * @description key  itemType (string) - type of item (item,series)
     * @description key  itemId (string) - id of the purchased item
     * @description key  time (integer) - timestamp
     * @param boolean $cascadeCreate - create automatically item with empty fields when purchase of this item exists
     * @return string
     * @throws ApiException
     * @todo Fix "cascadeCreate" param name. Other methods use "!cascadeCreate"
     */
    public function addIntoSeries($seriesId, array $data, $cascadeCreate = true)
    {
        $requiredKeys = array('itemType', 'itemId', 'time');

        if (count(array_intersect_key(array_flip($requiredKeys), $data)) === count($requiredKeys)) {

            $transport = $this->getTransport();

            $method = RestfulEnum::getMethod(__FUNCTION__);
            $url = RestfulEnum::getUrl(__FUNCTION__, array('__seriesid__' => $seriesId));

            if ($cascadeCreate) {
                $data['cascadeCreate'] = true;
            }

            //Set post data
            $postData = http_build_query($data);

            $transport->addCall($method, $url, $postData);

            $response = $transport->process();

            if ($response instanceof CurlResult && $response->getResponseCode() !== 200) {
                throw new ApiException(
                    ExceptionsEnum::getMessage(
                        ExceptionsEnum::API_RESPONSE_FAIL,
                        array(
                            '__method__' => __FUNCTION__,
                            '__body__' => $response->getResponseBody()
                        )
                    ),
                    $response->getResponseCode()
                );
            }

            return $response->getResponseBody();
        } else {
            throw new ApiException(
                ExceptionsEnum::getMessage(
                    ExceptionsEnum::API_PURCHASES_DETAILVIEW_REQUIRED_KEYS_NOT_EXISTS,
                    array(
                        '__requiredkeys__' => json_encode($requiredKeys),
                        '__datakeys__' => json_encode(array_keys($data))
                    )
                ),
                500
            );
        }
    }

    /**
     * Method will list series items
     * @param string $seriesId - series ID
     * @return array
     * @throws ApiException
     */
    public function listSeriesItems($seriesId)
    {
        $transport = $this->getTransport();

        $method = RestfulEnum::getMethod(__FUNCTION__);
        $url = RestfulEnum::getUrl(__FUNCTION__, array('__seriesid__' => $seriesId));
        $transport->addCall($method, $url);

        $response = $transport->process();

        if ($response instanceof CurlResult && $response->getResponseCode() !== 200) {
            throw new ApiException(
                ExceptionsEnum::getMessage(
                    ExceptionsEnum::API_RESPONSE_FAIL,
                    array(
                        '__method__' => __FUNCTION__,
                        '__body__' => $response->getResponseBody()
                    )
                ),
                $response->getResponseCode()
            );
        }

        return $response->getResponseBody();
    }

    /**
     * Method will delete series items
     * @param array $properties - list of elements required for series items to delete (below)
     * @description key seriesId (string) - id of the user
     * @description key  itemType (string) - type of item
     * @description key  itemId (string) - id of the purchased item
     * @description key  time (integer) - timestamp
     * @return string
     * @throws ApiException
     */
    public function deleteSeriesItems($seriesId, array $properties)
    {
        $requiredKeys = array('itemType', 'itemId', 'time');

        if (count(array_intersect_key(array_flip($requiredKeys), $properties)) === count($requiredKeys)) {

            $transport = $this->getTransport();

            $method = RestfulEnum::getMethod(__FUNCTION__);
            $url = RestfulEnum::getUrl(__FUNCTION__, array('__seriesid__' => $seriesId));

            //Add url query data
            $url = $url . '?' . http_build_query($properties);

            $transport->addCall($method, $url);

            $response = $transport->process();

            if ($response instanceof CurlResult && $response->getResponseCode() !== 200) {
                throw new ApiException(
                    ExceptionsEnum::getMessage(
                        ExceptionsEnum::API_RESPONSE_FAIL,
                        array(
                            '__method__' => __FUNCTION__,
                            '__body__' => $response->getResponseBody()
                        )
                    ),
                    $response->getResponseCode()
                );
            }
            return $response->getResponseBody();

        } else {
            throw new ApiException(
                ExceptionsEnum::getMessage(
                    ExceptionsEnum::API_PURCHASES_DETAILVIEW_REQUIRED_KEYS_NOT_EXISTS,
                    array(
                        '__requiredkeys__' => json_encode($requiredKeys),
                        '__datakeys__' => json_encode(array_keys($properties))
                    )
                ),
                500
            );
        }
    }



    /* USERS Section */

    /**
     * Method will add user
     * @param string $userId - user id to add
     * @return string
     * @throws ApiException
     */
    public function addUser($userId)
    {
        $transport = $this->getTransport();

        $method = RestfulEnum::getMethod(__FUNCTION__);
        $url = RestfulEnum::getUrl(__FUNCTION__, array('__userid__' => $userId));
        $transport->addCall($method, $url);

        $response = $transport->process();

        if ($response instanceof CurlResult && $response->getResponseCode() !== 201) {
            throw new ApiException(
                ExceptionsEnum::getMessage(
                    ExceptionsEnum::API_RESPONSE_FAIL,
                    array(
                        '__method__' => __FUNCTION__,
                        '__body__' => $response->getResponseBody()
                    )
                ),
                $response->getResponseCode()
            );
        }

        return $response->getResponseBody();
    }

    /**
     * Method will merge users
     * @param string $targetUserId - ID of the target (final) user
     * @param string $userId - ID of the user which will be merget with $targetUserId. This user ID will be deleted (is merged) from DB
     * @return string
     * @throws ApiException
     */
    public function mergeUsers($targetUserId, $userId)
    {
        $transport = $this->getTransport();

        $method = RestfulEnum::getMethod(__FUNCTION__);
        $url = RestfulEnum::getUrl(__FUNCTION__, array(
                '__targetuserid__' => $targetUserId,
                '__userid__' => $userId)
        );
        $transport->addCall($method, $url);

        $response = $transport->process();

        if ($response instanceof CurlResult && $response->getResponseCode() !== 200) {
            throw new ApiException(
                ExceptionsEnum::getMessage(
                    ExceptionsEnum::API_RESPONSE_FAIL,
                    array(
                        '__method__' => __FUNCTION__,
                        '__body__' => $response->getResponseBody()
                    )
                ),
                $response->getResponseCode()
            );
        }

        return $response->getResponseBody();
    }

    /**
     * Method will list users
     * @return array
     * @throws ApiException
     */
    public function listUsers()
    {
        $transport = $this->getTransport();

        $method = RestfulEnum::getMethod(__FUNCTION__);
        $url = RestfulEnum::getUrl(__FUNCTION__);
        $transport->addCall($method, $url);

        $response = $transport->process();

        if ($response instanceof CurlResult && $response->getResponseCode() !== 200) {
            throw new ApiException(
                ExceptionsEnum::getMessage(
                    ExceptionsEnum::API_RESPONSE_FAIL,
                    array(
                        '__method__' => __FUNCTION__,
                        '__body__' => $response->getResponseBody()
                    )
                ),
                $response->getResponseCode()
            );
        }

        return $response->getResponseBody();
    }

    /**
     * Method will delete user
     * @param string $userId - ID of the user which will be deleted
     * @return string
     * @throws ApiException
     */
    public function deleteUser($userId)
    {
        $transport = $this->getTransport();

        $method = RestfulEnum::getMethod(__FUNCTION__);
        $url = RestfulEnum::getUrl(__FUNCTION__, array('__userid__' => $userId));
        $transport->addCall($method, $url);

        $response = $transport->process();

        if ($response instanceof CurlResult && $response->getResponseCode() !== 200) {
            throw new ApiException(
                ExceptionsEnum::getMessage(
                    ExceptionsEnum::API_RESPONSE_FAIL,
                    array(
                        '__method__' => __FUNCTION__,
                        '__body__' => $response->getResponseBody()
                    )
                ),
                $response->getResponseCode()
            );
        }

        return $response->getResponseBody();
    }



    /* USER-ITEM interactions section */

    /**
     * Method will add detail views
     * @param array $data - list of elements required for detailview (below)
     * @description key  userId (string) - id of the user
     * @description key  itemId (string) - id of the purchased item
     * @description key  timestamp (integer) - timestamp
     * @description key  duration (integer) - duration
     * @param boolean $cascadeCreate - create automatically item with empty fields when purchase of this item exists
     * @return string
     * @throws ApiException
     * @todo Fix "cascadeCreate" param name. Other methods use "!cascadeCreate"
     */
    public function addDetailView(array $data, $cascadeCreate = true)
    {
        $requiredKeys = array('userId', 'itemId', 'timestamp');

        if (count(array_intersect_key(array_flip($requiredKeys), $data)) === count($requiredKeys)) {

            $transport = $this->getTransport();

            $method = RestfulEnum::getMethod(__FUNCTION__);
            $url = RestfulEnum::getUrl(__FUNCTION__);

            if ($cascadeCreate) {
                $data['cascadeCreate'] = true;
            }

            //Set post data
            $postData = http_build_query($data);

            $transport->addCall($method, $url, $postData);

            $response = $transport->process();

            if ($response instanceof CurlResult && $response->getResponseCode() !== 200) {
                throw new ApiException(
                    ExceptionsEnum::getMessage(
                        ExceptionsEnum::API_RESPONSE_FAIL,
                        array(
                            '__method__' => __FUNCTION__,
                            '__body__' => $response->getResponseBody()
                        )
                    ),
                    $response->getResponseCode()
                );
            }

            return $response->getResponseBody();
        } else {
            throw new ApiException(
                ExceptionsEnum::getMessage(
                    ExceptionsEnum::API_PURCHASES_DETAILVIEW_REQUIRED_KEYS_NOT_EXISTS,
                    array(
                        '__requiredkeys__' => json_encode($requiredKeys),
                        '__datakeys__' => json_encode(array_keys($data))
                    )
                ),
                500
            );
        }
    }

    /**
     * Method will list item detail views
     * @param string $itemId - ID of the item
     * @return array
     * @throws ApiException
     */
    public function listItemDetailViews($itemId)
    {
        $transport = $this->getTransport();

        $method = RestfulEnum::getMethod(__FUNCTION__);
        $url = RestfulEnum::getUrl(__FUNCTION__, array('__itemid__' => $itemId));
        $transport->addCall($method, $url);

        $response = $transport->process();

        if ($response instanceof CurlResult && $response->getResponseCode() !== 200) {
            throw new ApiException(
                ExceptionsEnum::getMessage(
                    ExceptionsEnum::API_RESPONSE_FAIL,
                    array(
                        '__method__' => __FUNCTION__,
                        '__body__' => $response->getResponseBody()
                    )
                ),
                $response->getResponseCode()
            );
        }

        return $response->getResponseBody();
    }

    /**
     * Method will list user detail views
     * @param string $userId - ID of the user
     * @return array
     * @throws ApiException
     */
    public function listUserDetailViews($userId)
    {
        $transport = $this->getTransport();

        $method = RestfulEnum::getMethod(__FUNCTION__);
        $url = RestfulEnum::getUrl(__FUNCTION__, array('__userid__' => $userId));
        $transport->addCall($method, $url);

        $response = $transport->process();

        if ($response instanceof CurlResult && $response->getResponseCode() !== 200) {
            throw new ApiException(
                ExceptionsEnum::getMessage(
                    ExceptionsEnum::API_RESPONSE_FAIL,
                    array(
                        '__method__' => __FUNCTION__,
                        '__body__' => $response->getResponseBody()
                    )
                ),
                $response->getResponseCode()
            );
        }

        return $response->getResponseBody();
    }

    /**
     * Method will delete detail views
     * @param array $properties - list of properties which will define detail view
     * @return string
     * @throws ApiException
     */
    public function deleteDetailView(array $properties)
    {
        $requiredKeys = array('userId', 'itemId', 'timestamp');

        if (count(array_intersect_key(array_flip($requiredKeys), $properties)) === count($requiredKeys)) {

            $transport = $this->getTransport();

            $method = RestfulEnum::getMethod(__FUNCTION__);
            $url = RestfulEnum::getUrl(__FUNCTION__);

            //Add url query data
            $url = $url . '?' . http_build_query($properties);

            $transport->addCall($method, $url);

            $response = $transport->process();

            if ($response instanceof CurlResult && $response->getResponseCode() !== 200) {
                throw new ApiException(
                    ExceptionsEnum::getMessage(
                        ExceptionsEnum::API_RESPONSE_FAIL,
                        array(
                            '__method__' => __FUNCTION__,
                            '__body__' => $response->getResponseBody()
                        )
                    ),
                    $response->getResponseCode()
                );
            }
            return $response->getResponseBody();

        } else {
            throw new ApiException(
                ExceptionsEnum::getMessage(
                    ExceptionsEnum::API_PURCHASES_DETAILVIEW_REQUIRED_KEYS_NOT_EXISTS,
                    array(
                        '__requiredkeys__' => json_encode($requiredKeys),
                        '__datakeys__' => json_encode(array_keys($data))
                    )
                ),
                500
            );
        }
    }



    // PURCHASES Section

    /**
     * Method will add purchase
     * @param array $data - list of elements required for purchase (below)
     * @description key  userId (string) - id of the user
     * @description key  itemId (string) - id of the purchased item
     * @description key  timestamp (integer) - timestamp
     * @param boolean $cascadeCreate - create automatically item with empty fields when purchase of this item exists
     * @return string
     * @throws ApiException
     * @todo Fix "cascadeCreate" param name. Other methods use "!cascadeCreate"
     */
    public function addPurchase(array $data, $cascadeCreate = true)
    {
        $requiredKeys = array('userId', 'itemId', 'timestamp');

        if (count(array_intersect_key(array_flip($requiredKeys), $data)) === count($requiredKeys)) {

            $transport = $this->getTransport();

            $method = RestfulEnum::getMethod(__FUNCTION__);
            $url = RestfulEnum::getUrl(__FUNCTION__);

            if ($cascadeCreate) {
                $data['cascadeCreate'] = true;
            }

            //Set post data
            $postData = http_build_query($data);

            $transport->addCall($method, $url, $postData);

            $response = $transport->process();

            if ($response instanceof CurlResult && $response->getResponseCode() !== 200) {
                throw new ApiException(
                    ExceptionsEnum::getMessage(
                        ExceptionsEnum::API_RESPONSE_FAIL,
                        array(
                            '__method__' => __FUNCTION__,
                            '__body__' => $response->getResponseBody()
                        )
                    ),
                    $response->getResponseCode()
                );
            }
            return $response->getResponseBody();

        } else {
            throw new ApiException(
                ExceptionsEnum::getMessage(
                    ExceptionsEnum::API_PURCHASES_DETAILVIEW_REQUIRED_KEYS_NOT_EXISTS,
                    array(
                        '__requiredkeys__' => json_encode($requiredKeys),
                        '__datakeys__' => json_encode(array_keys($data))
                    )
                ),
                500
            );
        }
    }

    /**
     * Method will list item purchases
     * @param string $itemId - ID of the item
     * @return mixed
     * @throws ApiException
     */
    public function listItemPurchases($itemId)
    {
        $transport = $this->getTransport();

        $method = RestfulEnum::getMethod(__FUNCTION__);
        $url = RestfulEnum::getUrl(__FUNCTION__, array('__itemid__' => $itemId));
        $transport->addCall($method, $url);

        $response = $transport->process();

        if ($response instanceof CurlResult && $response->getResponseCode() !== 200) {
            throw new ApiException(
                ExceptionsEnum::getMessage(
                    ExceptionsEnum::API_RESPONSE_FAIL,
                    array(
                        '__method__' => __FUNCTION__,
                        '__body__' => $response->getResponseBody()
                    )
                ),
                $response->getResponseCode()
            );
        }

        return $response->getResponseBody();
    }

    /**
     * Method will list user purchases
     * @param string $userId - ID of the user
     * @return mixed
     * @throws ApiException
     */
    public function listUserPurchases($userId)
    {
        $transport = $this->getTransport();

        $method = RestfulEnum::getMethod(__FUNCTION__);
        $url = RestfulEnum::getUrl(__FUNCTION__, array('__userid__' => $userId));
        $transport->addCall($method, $url);

        $response = $transport->process();

        if ($response instanceof CurlResult && $response->getResponseCode() !== 200) {
            throw new ApiException(
                ExceptionsEnum::getMessage(
                    ExceptionsEnum::API_RESPONSE_FAIL,
                    array(
                        '__method__' => __FUNCTION__,
                        '__body__' => $response->getResponseBody()
                    )
                ),
                $response->getResponseCode()
            );
        }

        return $response->getResponseBody();
    }

    /**
     * Method will delete purchase
     * @param array $properties - list of properties which will define purchase
     * @return string
     * @throws ApiException
     */
    public function deletePurchase(array $properties)
    {
        $requiredKeys = array('userId', 'itemId', 'timestamp');

        if (count(array_intersect_key(array_flip($requiredKeys), $properties)) === count($requiredKeys)) {

            $transport = $this->getTransport();

            $method = RestfulEnum::getMethod(__FUNCTION__);
            $url = RestfulEnum::getUrl(__FUNCTION__);

            //Add url query data
            $url = $url . '?' . http_build_query($properties);

            $transport->addCall($method, $url);

            $response = $transport->process();

            if ($response instanceof CurlResult && $response->getResponseCode() !== 200) {
                throw new ApiException(
                    ExceptionsEnum::getMessage(
                        ExceptionsEnum::API_RESPONSE_FAIL,
                        array(
                            '__method__' => __FUNCTION__,
                            '__body__' => $response->getResponseBody()
                        )
                    ),
                    $response->getResponseCode()
                );
            }
            return $response->getResponseBody();

        } else {
            throw new ApiException(
                ExceptionsEnum::getMessage(
                    ExceptionsEnum::API_PURCHASES_DETAILVIEW_REQUIRED_KEYS_NOT_EXISTS,
                    array(
                        '__requiredkeys__' => json_encode($requiredKeys),
                        '__datakeys__' => json_encode(array_keys($data))
                    )
                ),
                500
            );
        }
    }



    //RATING Section

    /**
     * Method will add rating
     * @param array $data - list of elements required for detailview (below)
     * @description key  userId (string) - id of the user
     * @description key  itemId (string) - id of the purchased item
     * @description key  timestamp (integer) - timestamp
     * @description key  rating (double) - rating (from -1.0 to 1.0)
     * @param boolean $cascadeCreate - create automatically item with empty fields when purchase of this item exists
     * @return string
     * @throws ApiException
     * @todo Fix "cascadeCreate" param name. Other methods use "!cascadeCreate"
     */
    public function addRating(array $data, $cascadeCreate = true)
    {
        $requiredKeys = array('userId', 'itemId', 'timestamp', 'rating');

        if (count(array_intersect_key(array_flip($requiredKeys), $data)) === count($requiredKeys)) {

            $transport = $this->getTransport();

            $method = RestfulEnum::getMethod(__FUNCTION__);
            $url = RestfulEnum::getUrl(__FUNCTION__);

            if ($cascadeCreate) {
                $data['cascadeCreate'] = true;
            }

            //Set post data
            $postData = http_build_query($data);

            $transport->addCall($method, $url, $postData);

            $response = $transport->process();

            if ($response instanceof CurlResult && $response->getResponseCode() !== 200) {
                throw new ApiException(
                    ExceptionsEnum::getMessage(
                        ExceptionsEnum::API_RESPONSE_FAIL,
                        array(
                            '__method__' => __FUNCTION__,
                            '__body__' => $response->getResponseBody()
                        )
                    ),
                    $response->getResponseCode()
                );
            }

            return $response->getResponseBody();
        } else {
            throw new ApiException(
                ExceptionsEnum::getMessage(
                    ExceptionsEnum::API_PURCHASES_DETAILVIEW_REQUIRED_KEYS_NOT_EXISTS,
                    array(
                        '__requiredkeys__' => json_encode($requiredKeys),
                        '__datakeys__' => json_encode(array_keys($data))
                    )
                ),
                500
            );
        }
    }

    /**
     * Method will list item ratings
     * @param string $itemId - ID of the item
     * @return mixed
     * @throws ApiException
     */
    public function listItemRatings($itemId)
    {
        $transport = $this->getTransport();

        $method = RestfulEnum::getMethod(__FUNCTION__);
        $url = RestfulEnum::getUrl(__FUNCTION__, array('__itemid__' => $itemId));
        $transport->addCall($method, $url);

        $response = $transport->process();

        if ($response instanceof CurlResult && $response->getResponseCode() !== 200) {
            throw new ApiException(
                ExceptionsEnum::getMessage(
                    ExceptionsEnum::API_RESPONSE_FAIL,
                    array(
                        '__method__' => __FUNCTION__,
                        '__body__' => $response->getResponseBody()
                    )
                ),
                $response->getResponseCode()
            );
        }

        return $response->getResponseBody();
    }

    /**
     * Method will list user ratings
     * @param string $userId - ID of the user
     * @return mixed
     * @throws ApiException
     */
    public function listUserRatings($userId)
    {
        $transport = $this->getTransport();

        $method = RestfulEnum::getMethod(__FUNCTION__);
        $url = RestfulEnum::getUrl(__FUNCTION__, array('__userid__' => $userId));
        $transport->addCall($method, $url);

        $response = $transport->process();

        if ($response instanceof CurlResult && $response->getResponseCode() !== 200) {
            throw new ApiException(
                ExceptionsEnum::getMessage(
                    ExceptionsEnum::API_RESPONSE_FAIL,
                    array(
                        '__method__' => __FUNCTION__,
                        '__body__' => $response->getResponseBody()
                    )
                ),
                $response->getResponseCode()
            );
        }

        return $response->getResponseBody();
    }

    /**
     * Method will delete rating
     * @param array $properties - list of properties which will define rating
     * @return string
     * @throws ApiException
     */
    public function deleteRating(array $properties)
    {
        $requiredKeys = array('userId', 'itemId', 'timestamp');

        if (count(array_intersect_key(array_flip($requiredKeys), $properties)) === count($requiredKeys)) {

            $transport = $this->getTransport();

            $method = RestfulEnum::getMethod(__FUNCTION__);
            $url = RestfulEnum::getUrl(__FUNCTION__);

            //Add url query data
            $url = $url . '?' . http_build_query($properties);

            $transport->addCall($method, $url);

            $response = $transport->process();

            if ($response instanceof CurlResult && $response->getResponseCode() !== 200) {
                throw new ApiException(
                    ExceptionsEnum::getMessage(
                        ExceptionsEnum::API_RESPONSE_FAIL,
                        array(
                            '__method__' => __FUNCTION__,
                            '__body__' => $response->getResponseBody()
                        )
                    ),
                    $response->getResponseCode()
                );
            }
            return $response->getResponseBody();

        } else {
            throw new ApiException(
                ExceptionsEnum::getMessage(
                    ExceptionsEnum::API_PURCHASES_DETAILVIEW_REQUIRED_KEYS_NOT_EXISTS,
                    array(
                        '__requiredkeys__' => json_encode($requiredKeys),
                        '__datakeys__' => json_encode(array_keys($data))
                    )
                ),
                500
            );
        }
    }



    //BOOMARK Section

    /**
     * Method will add boomark
     * @param array $data - list of elements required for purchase (below)
     * @description key  userId (string) - id of the user
     * @description key  itemId (string) - id of the purchased item
     * @description key  timestamp (integer) - timestamp
     * @param boolean $cascadeCreate - create automatically item with empty fields when purchase of this item exists
     * @return string
     * @throws ApiException
     * @todo Fix "cascadeCreate" param name. Other methods use "!cascadeCreate"
     */
    public function addBoomark(array $data, $cascadeCreate = true)
    {
        $requiredKeys = array('userId', 'itemId', 'timestamp');

        if (count(array_intersect_key(array_flip($requiredKeys), $data)) === count($requiredKeys)) {

            $transport = $this->getTransport();

            $method = RestfulEnum::getMethod(__FUNCTION__);
            $url = RestfulEnum::getUrl(__FUNCTION__);

            if ($cascadeCreate) {
                $data['cascadeCreate'] = true;
            }

            //Set post data
            $postData = http_build_query($data);

            $transport->addCall($method, $url, $postData);

            $response = $transport->process();

            if ($response instanceof CurlResult && $response->getResponseCode() !== 200) {
                throw new ApiException(
                    ExceptionsEnum::getMessage(
                        ExceptionsEnum::API_RESPONSE_FAIL,
                        array(
                            '__method__' => __FUNCTION__,
                            '__body__' => $response->getResponseBody()
                        )
                    ),
                    $response->getResponseCode()
                );
            }
            return $response->getResponseBody();

        } else {
            throw new ApiException(
                ExceptionsEnum::getMessage(
                    ExceptionsEnum::API_PURCHASES_DETAILVIEW_REQUIRED_KEYS_NOT_EXISTS,
                    array(
                        '__requiredkeys__' => json_encode($requiredKeys),
                        '__datakeys__' => json_encode(array_keys($data))
                    )
                ),
                500
            );
        }
    }

    /**
     * Method will list item boomarks
     * @param string $itemId - ID of the item
     * @return mixed
     * @throws ApiException
     */
    public function listItemBoomarks($itemId)
    {
        $transport = $this->getTransport();

        $method = RestfulEnum::getMethod(__FUNCTION__);
        $url = RestfulEnum::getUrl(__FUNCTION__, array('__itemid__' => $itemId));
        $transport->addCall($method, $url);

        $response = $transport->process();

        if ($response instanceof CurlResult && $response->getResponseCode() !== 200) {
            throw new ApiException(
                ExceptionsEnum::getMessage(
                    ExceptionsEnum::API_RESPONSE_FAIL,
                    array(
                        '__method__' => __FUNCTION__,
                        '__body__' => $response->getResponseBody()
                    )
                ),
                $response->getResponseCode()
            );
        }

        return $response->getResponseBody();
    }

    /**
     * Method will list user boomarks
     * @param string $userId - ID of the user
     * @return mixed
     * @throws ApiException
     */
    public function listUserBoomarks($userId)
    {
        $transport = $this->getTransport();

        $method = RestfulEnum::getMethod(__FUNCTION__);
        $url = RestfulEnum::getUrl(__FUNCTION__, array('__userid__' => $userId));
        $transport->addCall($method, $url);

        $response = $transport->process();

        if ($response instanceof CurlResult && $response->getResponseCode() !== 200) {
            throw new ApiException(
                ExceptionsEnum::getMessage(
                    ExceptionsEnum::API_RESPONSE_FAIL,
                    array(
                        '__method__' => __FUNCTION__,
                        '__body__' => $response->getResponseBody()
                    )
                ),
                $response->getResponseCode()
            );
        }

        return $response->getResponseBody();
    }

    /**
     * Method will delete boomark
     * @param array $properties - list of properties which will define boomark
     * @return string
     * @throws ApiException
     */
    public function deleteBoomark(array $properties)
    {
        $requiredKeys = array('userId', 'itemId', 'timestamp');

        if (count(array_intersect_key(array_flip($requiredKeys), $properties)) === count($requiredKeys)) {

            $transport = $this->getTransport();

            $method = RestfulEnum::getMethod(__FUNCTION__);
            $url = RestfulEnum::getUrl(__FUNCTION__);

            //Add url query data
            $url = $url . '?' . http_build_query($properties);

            $transport->addCall($method, $url);

            $response = $transport->process();

            if ($response instanceof CurlResult && $response->getResponseCode() !== 200) {
                throw new ApiException(
                    ExceptionsEnum::getMessage(
                        ExceptionsEnum::API_RESPONSE_FAIL,
                        array(
                            '__method__' => __FUNCTION__,
                            '__body__' => $response->getResponseBody()
                        )
                    ),
                    $response->getResponseCode()
                );
            }
            return $response->getResponseBody();

        } else {
            throw new ApiException(
                ExceptionsEnum::getMessage(
                    ExceptionsEnum::API_PURCHASES_DETAILVIEW_REQUIRED_KEYS_NOT_EXISTS,
                    array(
                        '__requiredkeys__' => json_encode($requiredKeys),
                        '__datakeys__' => json_encode(array_keys($data))
                    )
                ),
                500
            );
        }
    }



    //RECOMMENDATION Section

    /**
     * Method will return user base recommendations
     * @param string $userId - id of the user
     * @param integer $count - number of items that will be received
     * @param array $params - list of parameters required for recommendation
     * @description key  filterMql (string) - define MQL string for filtering - see API DOC
     * @description key  boosterMql (string) - define MQL string for boostering - see API DOC
     * @description key  $allowNonexistent (boolean) - see API DOC
     * @description key  $diversity (double) - see API DOC
     * @description key  $rotationRate (double) - see API DOC
     * @description key  $rotationTime (double) - see API DOC
     * @return array
     * @throws ApiException
     */
    public function getUserBasedRecommendation($userId, $count, array $params = array())
    {
        $transport = $this->getTransport();

        $method = RestfulEnum::getMethod(__FUNCTION__);
        $url = RestfulEnum::getUrl(__FUNCTION__, array(
            '__userid__' => $userId,
            '__count__' => $count
        ));

        //Add url query data
        $url = $url . (count($params) > 0 ? '&' . http_build_query($params) : '');

        $transport->addCall($method, $url);

        $response = $transport->process();

        if ($response instanceof CurlResult && $response->getResponseCode() !== 200) {
            throw new ApiException(
                ExceptionsEnum::getMessage(
                    ExceptionsEnum::API_RESPONSE_FAIL,
                    array(
                        '__method__' => __FUNCTION__,
                        '__body__' => $response->getResponseBody()
                    )
                ),
                $response->getResponseCode()
            );
        }
        return $response->getResponseBody();
    }

    /**
     * Method will return item base recommendations
     * @param string $itemId - id of the item
     * @param integer $count - number of items that will be received
     * @param array $params - list of parameters required for recommendation
     * @description key  filterMql (string) - define MQL string for filtering - see API DOC
     * @description key  boosterMql (string) - define MQL string for boostering - see API DOC
     * @description key  $allowNonexistent (boolean) - see API DOC
     * @description key  $diversity (double) - see API DOC
     * @description key  $rotationRate (double) - see API DOC
     * @description key  $rotationTime (double) - see API DOC
     * @return array
     * @throws ApiException
     */
    public function getItemBasedRecommendation($itemId, $count, array $params = array())
    {
        $transport = $this->getTransport();

        $method = RestfulEnum::getMethod(__FUNCTION__);
        $url = RestfulEnum::getUrl(__FUNCTION__, array(
            '__itemid__' => $itemId,
            '__count__' => $count
        ));

        //Add url query data
        $url = $url . (count($params) > 0 ? '&' . http_build_query($params) : '');

        $transport->addCall($method, $url);

        $response = $transport->process();

        if ($response instanceof CurlResult && $response->getResponseCode() !== 200) {
            throw new ApiException(
                ExceptionsEnum::getMessage(
                    ExceptionsEnum::API_RESPONSE_FAIL,
                    array(
                        '__method__' => __FUNCTION__,
                        '__body__' => $response->getResponseBody()
                    )
                ),
                $response->getResponseCode()
            );
        }
        return $response->getResponseBody();
    }



    /* MISCELLANEOUS Section */

    /**
     * Method will reset DB
     * @param boolean $confirm - delete confirmation
     * @return mixed
     * @throws ApiException
     */
    public function resetDatabase($confirm = false)
    {
        if ($confirm) {
            $transport = $this->getTransport();

            $method = RestfulEnum::getMethod(__FUNCTION__);
            $url = RestfulEnum::getUrl(__FUNCTION__);
            $transport->addCall($method, $url);

            $response = $transport->process();

            if ($response instanceof CurlResult && $response->getResponseCode() !== 200) {
                throw new ApiException(
                    ExceptionsEnum::getMessage(
                        ExceptionsEnum::API_RESPONSE_FAIL,
                        array(
                            '__method__' => __FUNCTION__,
                            '__body__' => $response->getResponseBody()
                        )
                    ),
                    $response->getResponseCode()
                );
            }

            return $response->getResponseBody();
        } else {
            throw new ApiException(
                ExceptionsEnum::getMessage(
                    ExceptionsEnum::API_RESET_DATABASE_CONFIRMATION_FAIL
                ),
                500
            );
        }
    }
}
