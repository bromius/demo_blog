<?php

namespace Application\Core;

use Application\Core\Exceptions\SystemException;

/**
 * Router core module
 */
class Router implements Interfaces\Initializable
{
    /**
     * Current Router instance
     *
     * @var Router 
     */
    protected static $instance;
    
    /**
     * Default controller
     *
     * @var string
     */
    protected $controller = 'IndexController';
    
    /**
     * Default action
     *
     * @var string
     */
    protected $action = 'indexAction';

    /**
     * Initialization
     * 
     * @return Router
     */
    public static function init()
    {
        if (static::$instance) {
            return static::$instance;
        }

        $url = Url::parse(Request::uri());
        $urlParts = explode('/', trim($url->path, '/'));

        static::$instance = new static();

        // Controller
        if (!empty($urlParts[0])) {
            static::$instance->controller = ucfirst(strtolower($urlParts[0])) . 'Controller';
        }

        // Action
        if (!empty($urlParts[1])) {
            static::$instance->action = lcfirst(strtolower($urlParts[1])) . 'Action';
        }

        return static::$instance;
    }

    /**
     * Returns current controller name
     * 
     * @return string
     */
    public function controller()
    {
        return $this->controller;
    }

    /**
     * Returns current action name
     * 
     * @return string
     */
    public function action()
    {
        return $this->action;
    }

    /**
     * Disables creation of new object
     */
    private function __construct()
    {
        
    }

    /**
     * Disables clone as new object
     */
    private function __clone()
    {
        
    }

    /**
     * Disables unserialize as new object
     */
    private function __wakeup()
    {
        
    }

}
