<?php

namespace Application\Core;

/**
 * Registry core module
 */
class Registry
{
    protected static $data = [];

    public static function set($key, $value)
    {
        return static::$data[$key] = $value;
    }

    public static function get($key)
    {
        return isset(static::$data[$key]) ? static::$data[$key] : null;
    }

    public static function remove($key)
    {
        if (array_key_exists($key, static::$data))
            unset(static::$data[$key]);
    }
}
