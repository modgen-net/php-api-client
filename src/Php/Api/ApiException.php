<?php
/**
 * Class responsible of API Exception handling
 * @copyright (c) Modgen s.r.o.
 * @author Mirek Ratman
 * @since 2015-06-22
 * @license The MIT License (MIT)
 */

namespace Php\Api;

class ApiException extends \Exception
{
    /**
     * Class constructor
     * @param string $message
     * @param integer $code
     * @param \Exception $previous
     */
    public function __construct($message, $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous === null ? $this->getPrevious() : null);
    }

    public function __toString()
    {
        return "[{$this->code}]: {$this->message}, {$this->file}, {$this->line}\n";
    }

    public function __toJson()
    {
        $out = array(
            'code' => $this->code,
            'message' => $this->message,
            'file' => $this->file,
            'line' => $this->line
        );
        return json_encode($out);
    }

}