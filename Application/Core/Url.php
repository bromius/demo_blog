<?php

namespace Application\Core;

/**
 * URL core module
 */
class Url
{
    /**
     * Parse URL into object
     * 
     * @param string $url URL
     * @return object
     */
    public static function parse($url)
    {
        $data = parse_url($url);
        return Arrays::toObject($data);
    }

    /**
     * Creates URL
     * 
     * @param string $path URL path
     * @param array $query Query params
     * @return string
     */
    public static function create($path = null, array $query = [])
    {
        return $path . ($query ? '?' . http_build_query($query) : '');
    }
}
