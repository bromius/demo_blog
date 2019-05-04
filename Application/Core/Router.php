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
    protected static $_instance;
    
    /**
     * Default controller
     *
     * @var string
     */
    protected $_controller = 'IndexController';
    
    /**
     * Default action
     *
     * @var string
     */
    protected $_action = 'indexAction';

    /**
     * Initialization
     * 
     * @return Router
     */
    public static function init()
    {
        if (static::$_instance)
            return static::$_instance;

        $url = Url::parse(Request::uri());
        $urlParts = explode('/', trim($url->path, '/'));

        static::$_instance = new static();

        // Controller
        if (!empty($urlParts[0]))
            static::$_instance->_controller = ucfirst(strtolower($urlParts[0])) . 'Controller';

        // Action
        if (!empty($urlParts[1]))
            static::$_instance->_action = lcfirst(strtolower($urlParts[1])) . 'Action';

        return static::$_instance;
    }

    /**
     * Returns current controller name
     * 
     * @return string
     */
    public function controller()
    {
        return $this->_controller;
    }

    /**
     * Returns current action name
     * 
     * @return string
     */
    public function action()
    {
        return $this->_action;
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
