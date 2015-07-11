Php\Api\Helpers\Debugger
===============






* Class name: Debugger
* Namespace: Php\Api\Helpers





Properties
----------


### $log

    private mixed $log = array()







Methods
-------



### getLog




    array Php\Api\Helpers\Debugger::getLog()






### clearLog
Method will clear log



    mixed Php\Api\Helpers\Debugger::clearLog()






### addLog




    mixed Php\Api\Helpers\Debugger::addLog(string $message, string $val, $method, $file, $line)



##### Arguments
  * $message **string** - message to add
  * $val **string** - value connected to message
  * $method **mixed**
  * $file **mixed**
  * $line **mixed**




### timestampToDateTime
Method will convert timestamp to DateTime format



    string Php\Api\Helpers\Debugger::timestampToDateTime(integer $timestamp)



##### Arguments
  * $timestamp **integer**




### __toJson
Method will convert messages list to JSON



    \Php\Api\Helpers\JSON Php\Api\Helpers\Debugger::__toJson()




