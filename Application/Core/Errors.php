<?php

namespace Application\Core;

use Application\Core\Log;
use Application\Core\Exceptions\SystemException;

/**
 * Errors core module
 */
class Errors implements Interfaces\Initializable
{
    public static function init()
    {
        register_shutdown_function(function() {
            if (!$error = error_get_last())
                return;

            // register_shutdown_function can change current working directory.
            // Set working directory back to APP_DIR
            chdir(APP_DIR);

            $errorStr = static::_prepareMessage([
                        'error' => 'Shutdown Error',
                        'type' => $error['type'],
                        'message' => $error['message'],
                        'file' => $error['file'],
                        'line' => $error['line']
            ]);

            throw new SystemException($errorStr);
        });

        set_error_handler(function($errno, $message, $file, $line) {
            if (WORK_MODE == 'production' && in_array($errno, [E_WARNING, E_NOTICE]))
                return;

            $errorStr = static::_prepareMessage([
                        'error' => 'Error',
                        'errno' => $errno,
                        'message' => $message,
                        'file' => $file,
                        'line' => $line
            ]);

            throw new SystemException($errorStr);
        }, E_ALL | E_STRICT);
    }

    public static function handler($errno, $errstr, $errfile, $errline, $traceStr = null)
    {
        $s = '';

        if (!empty($_SERVER['HTTP_REFERER']))
            $s .= 'Referrer: ' . $_SERVER['HTTP_REFERER'] . "\n";

        if (!empty($_SERVER['REQUEST_URI']))
            $s .= 'URI: ' . $_SERVER['REQUEST_URI'] . "\n";

        $s = "Error: #" . $errno . " " . $errstr . " [" . $errfile . ":" . $errline . "]\n";
        $s .= "Backtrace:\n";

        if ($traceStr) {
            $s .= $traceStr;
        } else {
            $backtrace_array = debug_backtrace();
            $backtrace = '';

            foreach ($backtrace_array as $key => $record) {
                if ($key == 0)
                    continue;
                $backtrace .= '#' . $key . ': ' . $record['function'] . '(';
                if (isset($record['args']) && is_array($record['args'])) {
                    $args = [];
                    foreach ($record['args'] as &$arg) {
                        if (is_object($arg) && !method_exists($arg, '__toString'))
                            $args[] = 'Object';
                        else if (is_array($arg))
                            $args[] = 'Array';
                        else
                            $args[] = $arg;
                    }
                    unset($arg);
                    $backtrace .= implode(',', $args);
                }
                $backtrace .= ') called at [' . ( isset($record['file']) ? $record['file'] : '?') . ':' . ( isset($record['line']) ? $record['line'] : '?') . "]\n";
            }

            $s .= $backtrace;
        }

        if (in_array($errno, [E_WARNING, E_NOTICE]))
            Log::warning($s, false, true);
        else
            Log::error($s, false, true);
    }

    protected static function _prepareMessage($message)
    {
        $preparedMessage = '';

        if (!empty($_SERVER['HTTP_REFERER']))
            $preparedMessage .= 'Referrer: ' . $_SERVER['HTTP_REFERER'] . "\n";

        if (!empty($_SERVER['REQUEST_URI']))
            $preparedMessage .= 'URI: ' . $_SERVER['REQUEST_URI'] . "\n";

        $preparedMessage = is_array($message) ? json_encode($message, JSON_PRETTY_PRINT) : $message;

        return $preparedMessage;
    }
}
