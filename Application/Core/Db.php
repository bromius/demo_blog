<?php

namespace Application\Core;

use Application\Core\Exceptions\SystemException;
use Application\Core\Arrays;
use Application\Core\Pagination;

/**
 * DB core module
 */
class Db implements Interfaces\Initializable
{
    protected static $_instance;
    protected static $_connection;
    protected $_resource;

    public static function init()
    {
        if (static::$_instance)
            return static::$_instance;

        if (!static::$_connection = mysqli_connect(cfg()->db->host, cfg()->db->user, cfg()->db->password))
            throw new SystemException('DB connection failed');

        if (!mysqli_select_db(static::$_connection, cfg()->db->name))
            throw new SystemException('DB connection failed');

        // Set charset
        mysqli_set_charset(static::$_connection, cfg()->db->encoding);

        // Set timezone
        $dt = new \DateTime();
        $offset = $dt->format('P');
        static::$_connection->query('SET `time_zone` = "' . $offset . '";');

        return static::$_instance = new static();
    }

    public function escape($sql, $args = null)
    {
        $args = func_get_args();
        $queryString = array_shift($args);

        $index = 0;
        return preg_replace_callback('/#./u', function($matches) use ($args, &$index) {
            switch ($matches[0]) {
                // Bool
                case '#b':
                    return $args[$index++] ? 1 : 0;
                // Integer
                case '#d':
                    return intval($args[$index++]);
                // String
                case '#s':
                    return "'" . mysqli_real_escape_string(static::$_connection, $args[$index++]) . "'";
                // Plain query
                case '#q':
                case '#p':
                    return $args[$index++];
            }
        }, $queryString);
    }

    public function query($sql, $args = null)
    {
        $sql = call_user_func_array([$this, 'escape'], func_get_args());
        if (!$this->_resource = mysqli_query(static::$_connection, $sql))
            throw new SystemException('Query error: ' . print_r($sql, true) . PHP_EOL . mysqli_error(static::$_connection));
        return $this->_resource;
    }

    public function selectRow($sql, $args = null)
    {
        call_user_func_array([$this, 'query'], func_get_args());
        if ($this->_resource && mysqli_num_rows($this->_resource))
            return mysqli_fetch_assoc($this->_resource);
        return [];
    }

    public function selectObj($sql, $args = null)
    {
        $data = call_user_func_array([$this, 'selectRow'], func_get_args());
        return Arrays::toObject($data);
    }

    public function selectRows($sql, $args = null)
    {
        call_user_func_array([$this, 'query'], func_get_args());
        $rows = [];
        if ($this->_resource && mysqli_num_rows($this->_resource)) {
            while ($row = mysqli_fetch_assoc($this->_resource))
                $rows[reset($row)] = $row;
        }
        return $rows;
    }

    public function getInsertId()
    {
        return mysqli_insert_id(static::$_connection);
    }

    public function foundRows()
    {
        $query = $this->query('SELECT FOUND_ROWS()');
        $result = mysqli_fetch_array($query);
        return reset($result);
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
