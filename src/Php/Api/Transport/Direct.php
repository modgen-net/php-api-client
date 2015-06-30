<?php
/**
 * Class responsible of Modgen API calls through CURL
 * @package Recommender/Api/Helpers
 * @copyright (c) Modgen s.r.o. 2015
 * @author Mirek Ratman
 * @version 1.0
 * @since 2015-05-25
 * @license
 */

namespace Php\Api\Transport;

use Php\Api\Helpers\Curl;
use Php\Api\Helpers\Hmac;

class Direct
{

    /*
     * @var string - Modgen API DB ID
     */
    private $db = '';

    /*
     * @var string - unique API key
     */
    private $key = '';

    /*
     * @var string - Modgen API Host
    */
    private $host = '';

    /*
     * @var mixed
    */
    private $result;

    /*
     * @var mixed
    */
    private $debug;

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
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param mixed $result
     */
    public function setResult($result)
    {
        $this->result = $result;
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
     * Method will prepare CURL request
     * @param string $method - method to call
     * @param string $url - URL to call
     * @param string $postData - POST data
     * @return void
     */
    public function addCall($method, $url, $postData = null)
    {
        $curl = new Curl();
        $urlDbPrefix = '/' . $this->getDb();

        $hmac = new Hmac($this->getKey());
        $urlHashed = $this->getHost() . $hmac->hashQuery($urlDbPrefix . $url);

        $curl->setPostData($postData);
        $curl->addCall(
            $method,
            $urlHashed
        );

        self::setResult($curl->process());
    }

    /**
     * Method will return result of API call
     * @return mixed
     */
    public function process()
    {
        return self::getResult();
    }
}