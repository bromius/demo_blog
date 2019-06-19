<?php

namespace Application\Core;

/**
 * Request core module
 */
class Request implements Interfaces\Initializable
{
    const POST = 1;
    const GET = 2;
    const BOTH = 3;

    /**
     * Current URL
     *
     * @var string 
     */
    public static $url = '';

    /**
     * Current request path
     *
     * @var string 
     */
    public static $path = '';

    /* Current GET & POST data */
    public static $request = [];

    public static function init()
    {
        static::$url = 'http'
                . (!empty($_SERVER['HTTPS']) ? 's' : '')
                . '://' . "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";

        $urlData = Url::parse(static::$url);

        static::$path = trim($urlData->path, '/');

        static::_set(array_merge($_GET, $_POST));
    }

    protected static function _set($name, $value = null)
    {
        if (!is_array($name)) {
            $name = [$name => $value];
        }
        foreach ($name as $key => $value) {
            if (is_scalar($value)) {
                $value = trim($value);
            }
            static::$request[$key] = $value;
        }
    }

    public static function get($name, $default = null, $type = self::BOTH)
    {
        if ($type != static::BOTH) {
            if ($type == static::POST) {
                $value = $_POST[$name] ?? null;
            } elseif ($type == static::GET) {
                $value = $_GET[$name] ?? null;
            }
        } else {
            $value = static::$request[$name] ?? null;
        }

        if ($value === '') {
            $value = null;
        }

        if ($default) {
            if (is_numeric($default)) {
                if ((int) $default == $default) {
                    $default = (int) $default;
                } else {
                    $default = (float) $default;
                }
            } else {
                $default = is_string($default) ? trim(urldecode($default)) : $default;
            }
        }

        if ($value) {
            if (is_numeric($value)) {
                if ((int) $value == $value) {
                    $value = (int) $value;
                } else {
                    $value = (float) $value;
                }
            } else {
                $value = is_string($value) ? trim(urldecode($value)) : $value;
            }
        }

        return !is_null($value) ? $value : $default;
    }

    public static function post($name, $default = null)
    {
        return static::get($name, $default, self::POST);
    }

    public static function files($name = null)
    {
        return $name && !empty($_FILES[$name]) ? $_FILES[$name] : $_FILES;
    }

    public static function exists($name)
    {
        return array_key_exists($name, static::$request);
    }

    public static function path($index = null)
    {
        return $index ? explode('/', static::$path)[$index] : static::$path;
    }

    public static function url()
    {
        return static::$url;
    }

    public static function uri()
    {
        return !empty($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
    }

    public static function isAjax()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) 
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }

    public static function getList()
    {
        return static::$request;
    }

    public static function ip($asInteger = true)
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        if ($asInteger) {
            $ip = (double) sprintf("%u\n", ip2long($ip));
        }
        return $ip;
    }

}
