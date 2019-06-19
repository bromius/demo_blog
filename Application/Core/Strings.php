<?php

namespace Application\Core;

/**
 * String core module
 */
class Strings
{
    /**
     * Escapes texts output
     * 
     * @param string $str
     * @return string
     */
    public static function text($str, $limit = 0)
    {
        $str = static::escape($str);

        if ($limit) {
            $str = mb_substr($str, 0, $limit - 3, 'utf-8') . '...';
        }

        return nl2br($str);
    }

    /**
     * String output escape
     * 
     * @param string $str
     * @return string
     */
    public static function escape($str)
    {
        return htmlspecialchars($str);
    }
    
    /**
     * Creates random string
     * 
     * @param int $length Output string length
     * @param bool $charsOnly (optional) Use only chars in output string
     * @param bool $lowercaseOnly (optional) Use only lowercase characters in output string
     * @return string
     */
    public static function random($length, $charsOnly = false, $lowercaseOnly = false) 
    {
		$alphaNumeric = ($lowercaseOnly ? '' : 'ABCDEFGHIJKLMNOPQRSTUVWXYZ') 
				. 'abcdefghijklmnopqrstuvwxyz' 
				. ($charsOnly ? '' : '0123456789');
        
		$result = '';
		while (strlen($result) < $length) {
			$result .= str_shuffle($alphaNumeric);
        }
        
		return substr($result, 0, $length);
	}
}
