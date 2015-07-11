Php\Api\Helpers\Curl
===============






* Class name: Curl
* Namespace: Php\Api\Helpers





Properties
----------


### $curl

    private mixed $curl = ''







### $postData

    private mixed $postData = ''







### $headerType

    private mixed $headerType = 'x-www-form-urlencoded'







### $debug

    private mixed $debug







Methods
-------



### getPostData




    string Php\Api\Helpers\Curl::getPostData()






### setPostData




    mixed Php\Api\Helpers\Curl::setPostData(string $postData)



##### Arguments
  * $postData **string**




### getHeaderType




    string Php\Api\Helpers\Curl::getHeaderType()






### setHeaderType




    mixed Php\Api\Helpers\Curl::setHeaderType(string $headerType)



##### Arguments
  * $headerType **string**




### getDebug




    boolean Php\Api\Helpers\Curl::getDebug()






### setDebug




    mixed Php\Api\Helpers\Curl::setDebug(boolean $debug)



##### Arguments
  * $debug **boolean**




### addCall
Method will add CURL request



    void Php\Api\Helpers\Curl::addCall(string $method, string $url)



##### Arguments
  * $method **string** - method to call
  * $url **string** - URL to call




### process
Method will execute CURL request



    \Php\Api\Helpers\CurlResult Php\Api\Helpers\Curl::process()




