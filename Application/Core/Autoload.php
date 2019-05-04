<?php

namespace Application\Core;

require_once 'Interfaces/Initializable.php';
require_once 'Registry.php';

/**
 * Autoload core module
 */
class Autoload implements Interfaces\Initializable
{
    protected static $_autoloadPaths = [''];

    /**
     * Initialization
     * 
     * @throws Exceptions\SystemException
     */
    public static function init()
    {
        spl_autoload_register(function ($name) {
            $filePath = $name . '.php';

            foreach (static::$_autoloadPaths as $dir) {
                $completePath = ROOT_DIR . $dir . '/' . str_replace('\\', '/', $filePath);
                if (is_readable($completePath)) {
                    require_once $completePath;
                    return;
                }
            }

            throw new Exceptions\SystemException('Autoload failed for ' . $completePath);
        });
    }
}
