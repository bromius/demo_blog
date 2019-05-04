<?php

namespace 
{
    /**
     * Global shortcut for config instance
     * 
     * @return \Application\Core\Config
     */
    function cfg()
    {
        return \Application\Core\Config::get();
    }
}

namespace Application\Core 
{
    /**
     * Config core module
     */
    class Config implements Interfaces\Initializable
    {
        /**
         * Config data
         *
         * @var object
         */
        protected static $_data;

        /**
         * Initialization
         */
        public static function init()
        {
            $data = require_once APP_DIR . 'config/' . WORK_MODE . '.php';

            static::$_data = Arrays::toObject($data);

            date_default_timezone_set(static::get()->timezone);
            mb_internal_encoding(static::get()->encoding);
            mb_regex_encoding(static::get()->encoding);
        }

        /**
         * Returns config data object
         * 
         * @return object
         */
        public static function get()
        {
            if (!static::$_data)
                static::init();
            return isset(static::$_data) ? static::$_data : null;
        }
    }
}