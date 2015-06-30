<?php
/**
 * Class responsible of Modgen API HMAC hashing
 * @copyright (c) Modgen s.r.o. 2015
 * @author Mirek Ratman
 * @since 2015-06-21
 * @license The MIT License (MIT)
 */

namespace Php\Api\Helpers;

class Hmac
{
    /*
     * @const - HMAC sign key name
    */
    const HMAC_KEY_SIGN = 'hmac_sign';

    /*
     * @const - HMAC timestamp key name
    */
    const HMAC_KEY_TIMESTAMP = 'hmac_timestamp';

    /**
     * Class constructor
     * @param string $key - unique API key
     */
    public function __construct($key)
    {
        $this->key = $key;
    }

    /**
     * Hash given url using HMAC
     * @param string $url - URL to hash
     * @return string
     */
    public function hashQuery($url)
    {
        $url = $url . ( strpos($url,'?') ? '&' : '?') . self::HMAC_KEY_TIMESTAMP . '=' . time();
        $sign = hash_hmac("sha1", $url, $this->key);
        return $url . '&' . self::HMAC_KEY_SIGN . '=' . $sign;
    }

    /**
     * Hash given url using HMAC
     * @param string $url - URL to hash
     * @return string
     */
    public function checkAuth($string)
    {
        return hash_hmac("sha1", $string, $this->key);
    }
}
