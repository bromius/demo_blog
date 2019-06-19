<?php

namespace Application\Core;

use Application\Core\Strings;
use Application\Core\Request;
use Application\Core\Exceptions\PublicException;

/**
 * Security core module
 */
class Security
{
    /**
     * Created CSRF token expires after defined seconds value
     * 
     * @var int
     */
    const CSRF_EXPIRE_SEC = 3600; // 1h
    
    /**
     * CSRF token parameter name
     * 
     * @var string
     */
    protected static $csrfTokenName = 'csrf_token';

    /**
     * Get CSRF token parameter name
     * 
     * @return string
     */
    public static function getCSRFParamName()
    {
        return static::$csrfTokenName;
    }

    /**
     * Generates CSRF token depending on salt, time and user IP
     * 
     * @param string $salt (optional) Hash salt
     * @param int $time (optional) Timestamp
     * @return string
     */
    public static function getCSRFToken($salt = '', $time = 0)
    {
        $salt = $salt ? : Strings::random(32);
        $time = $time ? : time();
        return $salt . ':' . $time . ':' . sha1($salt . $time . Request::ip() . cfg()->salt->csrf);
    }
    
    /**
     * Checks CSRF token (salt:hash)
     * 
     * @param string $token CSRF token with salt
     * @param bool $throwException (optional) Throws exception if TRUE, or returns FALSE
     * @return bool
     * @throws PublicException Error message if token not valid
     */
    public static function checkCSRFToken($token, $throwException = true)
    {
        if ($token) {
            list($salt, $time, $hash) = explode(':', $token);
            if ($time >= time() - static::CSRF_EXPIRE_SEC) {
                if (static::getCSRFToken($salt, $time) == $token) {
                    return true;
                }
            }
        }
        
        if ($throwException) {
            throw new PublicException('Invalid parameters. Please reload the page');
        }
        
        return false;
    }
}
