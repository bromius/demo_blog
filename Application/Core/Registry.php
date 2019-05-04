<?php

namespace Application\Core;

/**
 * Registry core module
 */
class Registry
{
    protected static $_data = [];

    public static function set($key, $value)
    {
        return static::$_data[$key] = $value;
    }

    public static function get($key)
    {
        return isset(static::$_data[$key]) ? static::$_data[$key] : null;
    }

    public static function remove($key)
    {
        if (array_key_exists($key, static::$_data))
            unset(static::$_data[$key]);
    }
}
