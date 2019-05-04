<?php

namespace Application\Core;

/**
 * Controller core module
 */
abstract class Controller
{
    public static function indexAction()
    {
        
    }

    public static function view($path, array $data = [])
    {
        return new View($path, $data);
    }

    public static function url($path = null, array $query = [])
    {
        return Url::create($path, $query);
    }

    public static function result($status, $data = null)
    {
        return json_encode([
            'result' => $status,
            'data' => $data
        ]);
    }
}
