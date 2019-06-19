<?php

namespace Application\Module\Controller;

use Application\Core\Request;

/**
 * Errors output
 */
class ErrorController extends \Application\Core\Controller
{

    /**
     * Default error page
     * 
     * @param string $message (optional) Error message
     * @return string
     */
    public static function indexAction($message = '')
    {
        if (Request::isAjax()) {
            return static::result(false, $message ?: 'Произошла ошибка');
        }

        return static::view('index', [
            'content' => static::view('index/error', [
                'message' => $message
            ])
        ]);
    }

}
