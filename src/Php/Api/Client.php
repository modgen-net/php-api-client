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
     * Method will set item properties base on given list of propertyName=>propertyType
     * @param array $properties - list of properties to set (int, double, string, boolean, timestamp, set, price)
     * @return array
     */
    public function setItemProperties(array $properties)
    {
        $propResponseList = array();

        foreach ($properties as $key => $val) {

            $propertyResponse = self::setItemProperty($key, $val);
            $roleResponse = self::setItemRole($key, $val);

            $propResponseList[$key] = array(
                'property' => $propertyResponse,
                'role' => $roleResponse
            );
        }

        return $propResponseList;
    }

    /**
     * Method will set one item property (and type)
     * @param array $properties - list of properties to set (int, double, string, boolean, timestamp, set, price)
     * @return array
     * @throws ApiException
     */
    public function setItemProperty($propertyName, $propertyType)
    {
        $transport = $this->getTransport();

        if ($propertyType === 'price') {
            $propertyType = 'double';
        }

        $method = RestfulEnum::getMethod(__FUNCTION__);
        $url = RestfulEnum::getUrl(__FUNCTION__, array(
            '__propertyname__' => $propertyName,
            '__type__' => $propertyType,
        ));

        $transport->addCall($method, $url);

        $response = $transport->process();

        if ($response->getResponseCode() !== 201) {
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
     * Method will set property role base on propertyName parameter
     * @param string $propertyName - property name needed to set role
     * @return string
     * @throws ApiException
     */
    public function setItemRole($propertyName)
    {
        //Get right role name
        $role = Roles::getPropertyType($propertyName);
        if ($role) {
            $transport = $this->getTransport();

            $method = RestfulEnum::getMethod(__FUNCTION__);
            $url = RestfulEnum::getUrl(__FUNCTION__, array('__rolename__' => $role));

            //Add url query data
            $url = $url . '?' . http_build_query(array('propertyName' => $propertyName));

            //Do call
            $transport->addCall($method, $url);

            $response = $transport->process();

            if ($response->getResponseCode() !== 200) {
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

        if ($response->getResponseCode() !== 200) {
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
     * Method will delete item property
     * @param array $propertiesNameList - properties name list to delete
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

        if ($response->getResponseCode() !== 200) {
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

        if ($response->getResponseCode() !== 200) {
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
     * @param array $data - data to add
     * @param string $idField - definition of field name which represents main identificator
     * @param boolean $autoCreate - create automatically item
     * @return string
     * @throws ApiException
     */
    public function addItem(array $data, $idFiled = 'id', $autoCreate = true)
    {
        if (array_key_exists($idFiled, $data)) {
            $transport = $this->getTransport();

            $method = RestfulEnum::getMethod(__FUNCTION__);
            $url = RestfulEnum::getUrl(__FUNCTION__, array('__itemid__' => $data[$idFiled]));

            //Remove id element from post data
            if (isset($data[$idFiled])) {
                unset($data[$idFiled]);
            }

            if ($autoCreate) {
                $data['!cascadeCreate'] = true;
            }

            //Set post data
            $postData = http_build_query($data);

            $transport->addCall($method, $url, $postData);

            $response = $transport->process();

            if ($response->getResponseCode() !== 200) {
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
     * Method will add item to DB
     * @param string $name - item name
     * @return string
     * @throws ApiException
     */
    public function addItemId($name)
    {
        $transport = $this->getTransport();

        $method = RestfulEnum::getMethod(__FUNCTION__);
        $url = RestfulEnum::getUrl(__FUNCTION__, array('__itemid__' => $name));
        $transport->addCall($method, $url);

        $response = $transport->process();

        if ($response->getResponseCode() !== 200) {
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

        if ($response->getResponseCode() !== 200) {
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

        if ($response->getResponseCode() !== 201) {
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

        if ($response->getResponseCode() !== 200) {
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

        if ($response->getResponseCode() !== 200) {
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
     * key itemType (string) - type of item (item,series)
     * key itemId (string) - id of the purchased item
     * key time (integer) - timestamp
     * @param boolean $autoCreate - create automatically item with empty fields when purchase of this item exists
     * @return string
     * @throws ApiException
     * @todo Fix "cascadeCreate" param name. Other methods use "!cascadeCreate"
     */
    public function addIntoSeries($seriesId, array $data, $autoCreate = true)
    {
        $requiredKeys = array('itemType', 'itemId', 'time');

        if (count(array_intersect_key(array_flip($requiredKeys), $data)) === count($requiredKeys)) {

            $transport = $this->getTransport();

            $method = RestfulEnum::getMethod(__FUNCTION__);
            $url = RestfulEnum::getUrl(__FUNCTION__, array('__seriesid__' => $seriesId));

            if ($autoCreate) {
                $data['cascadeCreate'] = true;
            }

            //Set post data
            $postData = http_build_query($data);

            $transport->addCall($method, $url, $postData);

            $response = $transport->process();

            if ($response->getResponseCode() !== 200) {
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

        if ($response->getResponseCode() !== 200) {
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
     * key seriesId (string) - id of the user
     * key itemType (string) - type of item
     * key itemId (string) - id of the purchased item
     * key time (integer) - timestamp
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

            if ($response->getResponseCode() !== 200) {
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

        if ($response->getResponseCode() !== 201) {
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

        if ($response->getResponseCode() !== 200) {
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

        if ($response->getResponseCode() !== 200) {
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

        if ($response->getResponseCode() !== 200) {
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




    /* PURCHASES Section */

    /**
     * Method will add detail views
     * @param array $data - list of elements required for detailview (below)
     * key userId (string) - id of the user
     * key itemId (string) - id of the purchased item
     * key timestamp (integer) - timestamp
     * key duration (integer) - duration
     * @param boolean $autoCreate - create automatically item with empty fields when purchase of this item exists
     * @return string
     * @throws ApiException
     * @todo Fix "cascadeCreate" param name. Other methods use "!cascadeCreate"
     */
    public function addDetailView(array $data, $autoCreate = true)
    {
        $requiredKeys = array('userId', 'itemId', 'timestamp');

        if (count(array_intersect_key(array_flip($requiredKeys), $data)) === count($requiredKeys)) {

            $transport = $this->getTransport();

            $method = RestfulEnum::getMethod(__FUNCTION__);
            $url = RestfulEnum::getUrl(__FUNCTION__);

            if ($autoCreate) {
                $data['cascadeCreate'] = true;
            }

            //Set post data
            $postData = http_build_query($data);

            $transport->addCall($method, $url, $postData);

            $response = $transport->process();

            if ($response->getResponseCode() !== 200) {
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

        if ($response->getResponseCode() !== 200) {
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

        if ($response->getResponseCode() !== 200) {
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

            if ($response->getResponseCode() !== 200) {
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
     * Method will add purchase
     * @param array $data - list of elements required for purchase (below)
     * key userId (string) - id of the user
     * key itemId (string) - id of the purchased item
     * key timestamp (integer) - timestamp
     * @param boolean $autoCreate - create automatically item with empty fields when purchase of this item exists
     * @return string
     * @throws ApiException
     * @todo Fix "cascadeCreate" param name. Other methods use "!cascadeCreate"
     */
    public function addPurchase(array $data, $autoCreate = true)
    {
        $requiredKeys = array('userId', 'itemId', 'timestamp');

        if (count(array_intersect_key(array_flip($requiredKeys), $data)) === count($requiredKeys)) {

            $transport = $this->getTransport();

            $method = RestfulEnum::getMethod(__FUNCTION__);
            $url = RestfulEnum::getUrl(__FUNCTION__);

            if ($autoCreate) {
                $data['cascadeCreate'] = true;
            }

            //Set post data
            $postData = http_build_query($data);

            $transport->addCall($method, $url, $postData);

            $response = $transport->process();

            if ($response->getResponseCode() !== 200) {
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

        if ($response->getResponseCode() !== 200) {
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

        if ($response->getResponseCode() !== 200) {
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

            if ($response->getResponseCode() !== 200) {
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
     * Method will add rating
     * @param array $data - list of elements required for detailview (below)
     * key userId (string) - id of the user
     * key itemId (string) - id of the purchased item
     * key timestamp (integer) - timestamp
     * key rating (double) - rating (from -1.0 to 1.0)
     * @param boolean $autoCreate - create automatically item with empty fields when purchase of this item exists
     * @return string
     * @throws ApiException
     * @todo Fix "cascadeCreate" param name. Other methods use "!cascadeCreate"
     */
    public function addRating(array $data, $autoCreate = true)
    {
        $requiredKeys = array('userId', 'itemId', 'timestamp', 'rating');

        if (count(array_intersect_key(array_flip($requiredKeys), $data)) === count($requiredKeys)) {

            $transport = $this->getTransport();

            $method = RestfulEnum::getMethod(__FUNCTION__);
            $url = RestfulEnum::getUrl(__FUNCTION__);

            if ($autoCreate) {
                $data['cascadeCreate'] = true;
            }

            //Set post data
            $postData = http_build_query($data);

            $transport->addCall($method, $url, $postData);

            $response = $transport->process();

            if ($response->getResponseCode() !== 200) {
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

        if ($response->getResponseCode() !== 200) {
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

        if ($response->getResponseCode() !== 200) {
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

            if ($response->getResponseCode() !== 200) {
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
     * Method will add boomark
     * @param array $data - list of elements required for purchase (below)
     * key userId (string) - id of the user
     * key itemId (string) - id of the purchased item
     * key timestamp (integer) - timestamp
     * @param boolean $autoCreate - create automatically item with empty fields when purchase of this item exists
     * @return string
     * @throws ApiException
     * @todo Fix "cascadeCreate" param name. Other methods use "!cascadeCreate"
     */
    public function addBoomark(array $data, $autoCreate = true)
    {
        $requiredKeys = array('userId', 'itemId', 'timestamp');

        if (count(array_intersect_key(array_flip($requiredKeys), $data)) === count($requiredKeys)) {

            $transport = $this->getTransport();

            $method = RestfulEnum::getMethod(__FUNCTION__);
            $url = RestfulEnum::getUrl(__FUNCTION__);

            if ($autoCreate) {
                $data['cascadeCreate'] = true;
            }

            //Set post data
            $postData = http_build_query($data);

            $transport->addCall($method, $url, $postData);

            $response = $transport->process();

            if ($response->getResponseCode() !== 200) {
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

        if ($response->getResponseCode() !== 200) {
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

        if ($response->getResponseCode() !== 200) {
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

            if ($response->getResponseCode() !== 200) {
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
     * Method will return user base recommendations
     * @param string $userId - id of the user
     * @param integer $count - number of items that will be received
     * @param array $params - list of parameters required for recommendation
     * key filterMql (string) - define MQL string for filtering - see API DOC
     * key boosterMql (string) - define MQL string for boostering - see API DOC
     * key $allowNonexistent (boolean) - see API DOC
     * key $diversity (double) - see API DOC
     * key $rotationRate (double) - see API DOC
     * key $rotationTime (double) - see API DOC
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

        if ($response->getResponseCode() !== 200) {
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
     * key filterMql (string) - define MQL string for filtering - see API DOC
     * key boosterMql (string) - define MQL string for boostering - see API DOC
     * key $allowNonexistent (boolean) - see API DOC
     * key $diversity (double) - see API DOC
     * key $rotationRate (double) - see API DOC
     * key $rotationTime (double) - see API DOC
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

        if ($response->getResponseCode() !== 200) {
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

            if ($response->getResponseCode() !== 200) {
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
