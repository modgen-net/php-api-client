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
     * @param string $name - property name
     * @param string $forceType - force role type
     * @return string
     */
    public static function getPropertyType($name, $roleType = null)
    {
        $role = false;

        $types = array(
            'price' => 'income',
            'name' => 'name'
        );

        if (array_key_exists($name, $types)) {
            $role = $types[$name];
        }
        if (array_key_exists($roleType, $types)) {
            $role = $types[$roleType];
        }
        if (in_array($roleType, $types)) {
            $role = $roleType;
        }

        return $role;
    }
}
