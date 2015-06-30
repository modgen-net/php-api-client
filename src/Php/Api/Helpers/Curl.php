<?php
/**
 * Class responsible of Modgen API calls through CURL
 * @copyright (c) Modgen s.r.o. 2015
 * @author Mirek Ratman
 * @since 2015-05-25
 * @license The MIT License (MIT)
 */

namespace Php\Api\Helpers;

use Php\Api\Helpers\CurlResult;

class Curl
{
    /*
     * @var mixed - Curl instance
    */
    private $curl = '';

    /*
     * @var mixed - POST data
    */
    private $postData = '';

    /*
     * @var string - type of header that needs to be set
    */
    private $headerType = 'x-www-form-urlencoded';

    /*
     * @var mixed
    */
    private $debug;

    /**
     * @return string
     */
    public function getPostData()
    {
        return $this->postData;
    }

    /**
     * @param string $postData
     */
    public function setPostData($postData)
    {
        $this->postData = $postData;
    }

    /**
     * @return string
     */
    public function getHeaderType()
    {
        return $this->headerType;
    }

    /**
     * @param string $headerType
     */
    public function setHeaderType($headerType)
    {
        $this->headerType = $headerType;
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
     * Method will add CURL request
     * @param string $method - method to call
     * @param string $url - URL to call
     * @return void
     */
    public function addCall($method, $url)
    {
        $this->curl = curl_init();

        switch ($method) {
            case "GET":
                break;
            case "HEAD":
                break;
            case "POST":
                curl_setopt($this->curl, CURLOPT_POST, 1);
                if ($this->getPostData()) {
                    curl_setopt($this->curl, CURLOPT_POSTFIELDS, $this->getPostData());
                }
                break;
            case "PUT":
                curl_setopt($this->curl, CURLOPT_PUT, 1);
                break;
            case "DELETE":
                curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, "DELETE");
                break;
            case "TRACE":
                break;
            case "OPTIONS":
                break;
            case "CONNECT":
                break;
            case "PATCH":
                break;
        }

        switch (strtolower(self::getHeaderType())) {
            case "json":
                curl_setopt($this->curl, CURLOPT_HTTPHEADER, array(
                        'Content-Type: application/json',
                        'Accept-Charset: utf-8',
                        'Content-Length: ' . strlen($this->getPostData())
                    )
                );
                break;
            case "x-www-form-urlencoded":
            default:
                curl_setopt($this->curl, CURLOPT_HTTPHEADER, array(
                        'Content-Type: application/x-www-form-urlencoded',
                        'Accept-Charset: utf-8'
                    )
                );
                break;
        }

        curl_setopt($this->curl, CURLOPT_URL, $url);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
    }

    /**
     * Method will execute CURL request
     * @return CurlResult
     */
    public function process()
    {
        if ($debug = $this->getDebug()) {
            $debug->addLog('Starting CURL request', $debug->timestampToDateTime(time()));
        }
        $result = new CurlResult();
        $result->setResponseBody(curl_exec($this->curl));
        $result->setResponseCode(curl_getinfo($this->curl, CURLINFO_HTTP_CODE));
        curl_close($this->curl);
        if ($debug = $this->getDebug()) {
            $debug->addLog('Ending CURL request', $debug->timestampToDateTime(time()));
        }

        return $result;
    }
}