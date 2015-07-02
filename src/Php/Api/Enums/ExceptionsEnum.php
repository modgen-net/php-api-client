<?php
/**
 * Definition of API Exceptions messages
 * @copyright (c) Modgen s.r.o.
 * @author Mirek Ratman
 * @since 2015-06-22
 * @license The MIT License (MIT)
 */

namespace Php\Api\Enums;

class ExceptionsEnum
{
    const API_RESTFUL_METHOD_NOT_EXISTS = 'Api restful method "__method__" not exists';
    const API_RESPONSE_FAIL = 'Api response fail for method "__method__". Response body: __body__';
    const API_RESET_DATABASE_CONFIRMATION_FAIL = 'Reset database confirmation fail. Please confirm reset!';
    const API_ITEMS_NO_ID_DEFINED = 'Identificator "__id__" of main element not exists in data set';
    const API_PURCHASES_DETAILVIEW_REQUIRED_KEYS_NOT_EXISTS = 'Not all required keys was added in to data object! Required fields: __requiredkeys__, Present keys: __datakeys__';

    /**
     * Return filled Exception message
     * @param string $message - API Exception message
     * @param array $params - associate array of param names and values to change
     * @return string
     */
    public static function getMessage($message, array $params = array())
    {
        $msg = strtr($message, $params);
        return preg_replace('/__.*__/', '', $msg);
    }
}

