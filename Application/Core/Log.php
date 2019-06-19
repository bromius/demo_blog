<?php

namespace Application\Core;

/**
 * Logs core module
 */
class Log
{
    /**
     * Write debug data
     * 
     * @param mixed $data Log data
     * @param bool $dayLog (optional) If true, stores log file into year/month/day directory
     */
    public static function debug($data, $dayLog = false)
    {
        $sysData = Request::ip(false) . (isset($_SERVER['SERVER_NAME']) ? ' | ' . $_SERVER['SERVER_NAME'] : '');

        $str = "= Start [" . date('d.m.Y H:i:s') . "] [" . $sysData . "] ================>\n";
        $str .= print_r($data, true);
        $str .= "\n< End =========================================\n\n";
        
        self::writeLog($str, 'debug' . $dayLog ? date('/Y/m/d') : '');
    }

    /**
     * Запись warning данных (уведомления админа о неисправностях)
     * 
     * @param mixed $data Log data
     * @param bool $dayLog (optional) If true, stores log file into year/month/day directory
     */
    public static function warning($data, $dayLog = false)
    {
        $sysData = Request::ip(false) . (isset($_SERVER['SERVER_NAME']) ? ' | ' . $_SERVER['SERVER_NAME'] : '');

        $str = "= Start [" . date('d.m.Y H:i:s') . "] [" . $sysData . "] ================>\n";
        $str .= print_r($data, true);
        $str .= "\n< End =========================================\n\n";
        
        self::writeLog($str, 'warnings' . $dayLog ? date('/Y/m/d') : '');
    }

    /**
     * Запись error данных
     * 
     * @param mixed $data Log data
     * @param bool $dayLog (optional) If true, stores log file into year/month/day directory
     */
    public static function error($data, $dayLog = false)
    {
        $sysData = Request::ip(false) . (isset($_SERVER['SERVER_NAME']) ? ' | ' . $_SERVER['SERVER_NAME'] : '');

        $str = "= Start [" . date('d.m.Y H:i:s') . "] [" . $sysData . "] ================>\n";
        $str .= print_r($data, true);
        $str .= "\n< End =========================================\n\n";
        
        self::writeLog($str, 'errors' . $dayLog ? date('/Y/m/d') : '');
    }

    /**
     * Запись данных в файл
     * 
     * @param string $str Log data
     * @param string $fileName File name
     * @param bool $refresh Refresh log file (removes previous log content)
     */
    public static function writeLog($str, $fileName, $refresh = false)
    {
        $path = ROOT_DIR . 'logs/' . $fileName . '.log';
        $pathDir = dirname($path);
        
        if (!is_dir($pathDir)) {
            mkdir($pathDir, 0755, true);
        }
        
        file_put_contents(
            $path,
            $str . "\n\r",
            $refresh ? null : FILE_APPEND
        );
    }
}
