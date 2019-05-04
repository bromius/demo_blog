<?php

namespace Application\Core;

/**
 * Arrays core module
 */
class Arrays
{
    /**
     * Converts array into object
     * 
     * @param array $array Array
     * @return object
     */
    public static function toObject($array)
    {
        if (!is_array($array) || !static::isAssoc($array))
            return $array;
        return (object) array_map(['static', __FUNCTION__], $array);
    }

    /**
     * Checks whether array is associative
     * 
     * @param array $array Array
     * @return bool
     */
    public static function isAssoc($array)
    {
        return !empty($array) 
            && is_array($array) 
            && count(array_filter(array_keys($array), 'is_string'));
    }
}
