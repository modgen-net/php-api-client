Php\Api\Enums\ExceptionsEnum
===============






* Class name: ExceptionsEnum
* Namespace: Php\Api\Enums



Constants
----------


### API_RESTFUL_METHOD_NOT_EXISTS

    const API_RESTFUL_METHOD_NOT_EXISTS = 'Api restful method "__method__" not exists'





### API_RESPONSE_FAIL

    const API_RESPONSE_FAIL = 'Api response fail for method "__method__". Response body: __body__'





### API_RESET_DATABASE_CONFIRMATION_FAIL

    const API_RESET_DATABASE_CONFIRMATION_FAIL = 'Reset database confirmation fail. Please confirm reset!'





### API_ITEMS_NO_ID_DEFINED

    const API_ITEMS_NO_ID_DEFINED = 'Identificator "__id__" of main element not exists in data set'





### API_PURCHASES_DETAILVIEW_REQUIRED_KEYS_NOT_EXISTS

    const API_PURCHASES_DETAILVIEW_REQUIRED_KEYS_NOT_EXISTS = 'Not all required keys was added in to data object! Required fields: __requiredkeys__, Present keys: __datakeys__'







Methods
-------



### getMessage
Return filled Exception message



    string Php\Api\Enums\ExceptionsEnum::getMessage(string $message, array $params)

* This method is **static**.


##### Arguments
  * $message **string** - API Exception message
  * $params **array** - associate array of param names and values to change


