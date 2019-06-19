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
    protected static $instance;
    protected static $connection;
    protected $resource;

    public static function init()
    {
        if (static::$instance)
            return static::$instance;

        if (!static::$connection = mysqli_connect(cfg()->db->host, cfg()->db->user, cfg()->db->password))
            throw new SystemException('DB connection failed');

        if (!mysqli_select_db(static::$connection, cfg()->db->name))
            throw new SystemException('DB connection failed');

        // Set charset
        mysqli_set_charset(static::$connection, cfg()->db->encoding);

        // Set timezone
        $dt = new \DateTime();
        $offset = $dt->format('P');
        static::$connection->query('SET `time_zone` = "' . $offset . '";');

        return static::$instance = new static();
    }

    public function escape($sql, $args = null)
    {
        $args = func_get_args();
        $queryString = array_shift($args);

        $index = 0;
        return preg_replace_callback('/#./u', function ($matches) use ($args, &$index) {
            switch ($matches[0]) {
                // Bool
                case '#b':
                    return $args[$index++] ? 1 : 0;
                // Integer
                case '#d':
                    return intval($args[$index++]);
                // String
                case '#s':
                    return "'" . mysqli_real_escape_string(static::$connection, $args[$index++]) . "'";
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
        if (!$this->resource = mysqli_query(static::$connection, $sql))
            throw new SystemException('Query error: ' . print_r($sql, true) . PHP_EOL . mysqli_error(static::$connection));
        return $this->resource;
    }

    public function selectRow($sql, $args = null)
    {
        call_user_func_array([$this, 'query'], func_get_args());
        if ($this->resource && mysqli_num_rows($this->resource))
            return mysqli_fetch_assoc($this->resource);
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
        if ($this->resource && mysqli_num_rows($this->resource)) {
            while ($row = mysqli_fetch_assoc($this->resource))
                $rows[reset($row)] = $row;
        }
        return $rows;
    }

    public function getInsertId()
    {
        return mysqli_insert_id(static::$connection);
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
