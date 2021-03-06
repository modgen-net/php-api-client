<?php
/**
 * Modgen API Client debugger
 * @copyright (c) Modgen s.r.o. 2015
 * @author Mirek Ratman
 * @since 2015-06-28
 * @license The MIT License (MIT)
 */

namespace Php\Api\Helpers;

class Debugger
{
    /*
     * @var array - list of messages
     */
    private $log = array();

    /**
     * @return array
     */
    public function getLog()
    {
        return $this->log;
    }

    /**
     * Method will clear log
     */
    public function clearLog()
    {
        $this->log = array();
    }

    /**
     * @param string $message - message to add
     * @param string $val - value connected to message
     */
    public function addLog($message, $val, $method = null, $file = null, $line = null)
    {
        $this->log[] = array(
            'message' => $message,
            'value' => $val,
            'method' => $method,
            'file' => $file,
            'line' => $line
        );
    }

    /**
     * Method will convert timestamp to DateTime format
     * @param integer $timestamp
     * @return string
     */
    public function timestampToDateTime($timestamp)
    {
        return date('Y-m-d h:m:s', $timestamp);
    }

    /**
     * Method will convert messages list to JSON
     * @return JSON
     */
    public function __toJson()
    {
        return json_encode($this->getLog());
    }
}
