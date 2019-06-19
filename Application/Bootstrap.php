<?php

namespace Application;

/**
 * Application directory
 */
define('APP_DIR', realpath(dirname(__FILE__)) . '/');

/**
 * Root directory
 */
define('ROOT_DIR', realpath(APP_DIR . '/..') . '/');

/**
 * Work mode (development|production)
 */
define('WORK_MODE', PHP_OS == 'WINNT' ? 'development' : 'production');

// Set working directory to Application
chdir(APP_DIR);

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', WORK_MODE == 'development');

require_once APP_DIR . 'Core/Autoload.php';

/**
 * Bootstrap
 */
final class Bootstrap implements Core\Interfaces\Initializable
{
    /**
     * Initialization
     */
    public static function init()
    {
        try {
            Core\Autoload::init();
            Core\Errors::init();
            Core\Config::init();
            Core\Request::init();

            print static::run();
        } catch (\Throwable $e) {
            try {
                // Flush previous output
                if (ob_get_length())
                    ob_end_clean();

                // Log system errors
                if (!($e instanceof Core\Exceptions\PublicException)) {
                    Core\Errors::handler(
                            $e->getCode(),
                            $e->getMessage(),
                            $e->getFile(),
                            $e->getLine(),
                            $e->getTraceAsString()
                    );
                    // No errors output for non-public exceptions in producation
                    $errorMessage = WORK_MODE == 'development' ? $e->getMessage() : '';
                } else {
                    // Output public error message
                    $errorMessage = $e->getMessage();
                }

                print \Application\Module\Controller\ErrorController::indexAction($errorMessage);
            } catch (Exception $e) {
                die('Error');
            }
        }
    }

    /**
     * Executes controller method
     * 
     * @return string
     */
    public static function run()
    {
        $router = Core\Router::init();
        return call_user_func([
            'Application\Module\Controller\\' . $router->controller(), 
            $router->action()
        ]);
    }

}
