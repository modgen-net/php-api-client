Php\Api\Helpers\Hmac
===============






* Class name: Hmac
* Namespace: Php\Api\Helpers



Constants
----------


### HMAC_KEY_SIGN

    const HMAC_KEY_SIGN = 'hmac_sign'





### HMAC_KEY_TIMESTAMP

    const HMAC_KEY_TIMESTAMP = 'hmac_timestamp'







Methods
-------



### __construct
Class constructor



    mixed Php\Api\Helpers\Hmac::__construct(string $key)



##### Arguments
  * $key **string** - unique API key




### hashQuery
Hash given url using HMAC



    string Php\Api\Helpers\Hmac::hashQuery(string $url)



##### Arguments
  * $url **string** - URL to hash




### checkAuth
Hash given url using HMAC



    string Php\Api\Helpers\Hmac::checkAuth($string)



##### Arguments
  * $string **mixed**


