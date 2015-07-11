Php\Api\Transport\Direct
===============






* Class name: Direct
* Namespace: Php\Api\Transport





Properties
----------


### $db

    private mixed $db = ''







### $key

    private mixed $key = ''







### $host

    private mixed $host = ''







### $result

    private mixed $result







### $debug

    private mixed $debug







### $headerType

    private mixed $headerType = 'x-www-form-urlencoded'







Methods
-------



### getDb




    string Php\Api\Transport\Direct::getDb()






### setDb




    mixed Php\Api\Transport\Direct::setDb(string $db)



##### Arguments
  * $db **string**




### getKey




    string Php\Api\Transport\Direct::getKey()






### setKey




    mixed Php\Api\Transport\Direct::setKey(string $key)



##### Arguments
  * $key **string**




### getHost




    string Php\Api\Transport\Direct::getHost()






### setHost




    mixed Php\Api\Transport\Direct::setHost(string $host)



##### Arguments
  * $host **string**




### getResult




    mixed Php\Api\Transport\Direct::getResult()






### setResult




    mixed Php\Api\Transport\Direct::setResult(mixed $result)



##### Arguments
  * $result **mixed**




### getDebug




    boolean Php\Api\Transport\Direct::getDebug()






### setDebug




    mixed Php\Api\Transport\Direct::setDebug(boolean $debug)



##### Arguments
  * $debug **boolean**




### getHeaderType




    string Php\Api\Transport\Direct::getHeaderType()






### setHeaderType




    mixed Php\Api\Transport\Direct::setHeaderType(string $headerType)



##### Arguments
  * $headerType **string**




### addCall
Method will prepare CURL request



    void Php\Api\Transport\Direct::addCall(string $method, string $url, string $postData)



##### Arguments
  * $method **string** - method to call
  * $url **string** - URL to call
  * $postData **string** - POST data




### process
Method will return result of API call



    mixed Php\Api\Transport\Direct::process()




