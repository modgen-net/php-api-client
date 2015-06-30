<?php
/**
 * Class responsible of property role definition
 * @copyright (c) Modgen s.r.o. 2015
 * @author Mirek Ratman
 * @since 2015-05-25
 * @license The MIT License (MIT)
 */

namespace Php\Api\Helpers;

class Roles
{
    /**
     * Return right property type
     * @param string $value - property to check
     * @param boolean $forceSetType - force type of value to "set"
     * @return string
     */
    public static function getPropertyType($name, $forceSetType = false)
    {
        switch ($name) {
            case 'price':
                return 'income';
                break;
            case 'name':
                return 'name';
                break;
            default:
                return false;
                break;
        }
    }
}
