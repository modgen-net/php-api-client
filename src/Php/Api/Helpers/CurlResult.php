<?php
/**
 * Class define structure and methods of CurlResponse object
 * @copyright (c) Modgen s.r.o. 2015
 * @author Mirek Ratman
 * @since 2015-05-25
 * @license The MIT License (MIT)
 */

namespace Php\Api\Helpers;

class CurlResult
{
    /*
     * @var integer
    */
    private $responseCode = '0';

    /*
     * @var string
    */
    private $responseBody = null;

    /**
     * @return string
     */
    public function getResponseCode()
    {
        return $this->responseCode;
    }

    /**
     * @param string $responseCode
     */
    public function setResponseCode($responseCode)
    {
        $this->responseCode = $responseCode;
    }

    /**
     * @return null
     */
    public function getResponseBody()
    {
        return $this->responseBody;
    }

    /**
     * @param null $responseBody
     */
    public function setResponseBody($responseBody)
    {
        $this->responseBody = trim($responseBody,'"');
    }

    /**
     * Method will convert response object to associated array
     * @return array
     */
    public function __toArray()
    {
        return (array) get_object_vars($this);
    }

    /**
     * Method will convert response object to JSON
     * @return JSON
     */
    public function __toJson()
    {
        return json_encode( get_object_vars($this) );
    }

}