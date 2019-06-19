<?php

namespace Application\Core;

use Application\Core\Db;
use Application\Core\Exceptions\SystemException;
use Application\Core\Arrays;

/**
 * Database table model
 */
abstract class Model
{
    /**
     * Table name
     * @var string
     */
    protected static $table = '';

    /**
     * Associative array, table row data
     * @var array
     */
    protected $data = [];

    /**
     * Model table name
     * 
     * @return string
     */
    public static function table()
    {
        return static::$table;
    }

    /**
     * Get model's instance
     * 
     * @param array $data
     * @return $this
     */
    public static function instance($data)
    {
        return new static($data);
    }

    /**
     * Prepare fieldname => value for insertion or update
     * 
     * @param array $data Associative array, insert/update data
     * @return string
     * @throws SystemException
     */
    protected static function _escapeFieldsData(array $data)
    {
        if (!Arrays::isAssoc($data)) {
            throw new SystemException('Invalid fields data');
        }

        $insertSql = [];
        foreach ($data as $key => $value) {
            $insertSql[] = Db::init()->escape('`' . $key . '` = #s', $value);
        }

        return implode(",\n", $insertSql);
    }

    /**
     * Insert row into model's table
     * 
     * @param array $data Associative array, row data
     * @return $this
     * @throws SystemException
     */
    public static function insert(array $data)
    {
        $query = Db::init()->query('
			INSERT INTO `#p` SET
				#p
		',
            static::table(),
            static::_escapeFieldsData($data)
        );

        if (!$query) {
            throw new SystemException('DB insert failed');
        }

        $id = Db::init()->getInsertId();

        return static::get($id);
    }

    /**
     * Update row by it's ID
     * 
     * @param array $data Associative array, row data
     * @return resource
     */
    public function update($key, $value = null)
    {
        $data = is_array($key) ? $key : [$key => $value];

        Db::init()->query('
			UPDATE `#p` SET
				#p
			WHERE `id` = #d
		',
            static::table(),
            static::_escapeFieldsData($data),
            $this->id
        );

        return static::get($this->id);
    }

    /**
     * Get row instance using ID
     * 
     * @param int $id Table row ID
     * @return static
     */
    public static function get($id)
    {
        return static::select('
			SELECT *
			FROM `' . static::table() . '` 
			WHERE `id` = #d
		', $id);
    }

    /**
     * Get row instance using SELECT query 
     * 
     * @param string $sql SQL
     * @param mixed $args (optional) Arguments
     * @return static
     */
    public static function select($sql, $args = null)
    {
        $row = call_user_func_array([Db::init(), 'selectRow'], func_get_args());
        return static::instance($row);
    }

    /**
     * Get multiple rows instances as associative array
     * 
     * @param string $sql SQL
     * @param mixed $args (optional) Query parameters
     * @return array
     */
    public static function getMultiple($sql, $args = null)
    {
        if (!$rows = call_user_func_array([Db::init(), 'selectRows'], func_get_args())) {
            return [];
        }

        $models = [];
        foreach ($rows as $row) {
            $models[reset($row)] = static::instance($row);
        }

        return $models;
    }

    /**
     * Delete current row by ID
     * 
     * @return resource
     */
    public function delete()
    {
        return Db::init()->query('
			DELETE 
			FROM `' . static::table() . '` 
			WHERE id = #d
		', $this->data['id']);
    }

    /**
     * Checks whether row model exists
     * 
     * @return bool
     */
    public function exists()
    {
        return !empty($this->id);
    }

    /**
     * Get column value
     * 
     * @return type
     */
    public function col($colName)
    {
        return $this->$colName;
    }

    /**
     * Constructor
     * 
     * @param array $data Associative array, row data
     * @return $this
     */
    public function __construct($data = [])
    {
        if (!$data) {
            return $this;
        }
        $this->data = $data;
    }

    /**
     * Set data as key => value for model
     * 
     * @param string $key
     * @param mixed $value
     */
    public function __set($key, $value)
    {
        $this->data[$key] = $value;
    }

    /**
     * Get row data by key
     * 
     * @param string $key
     * @return mixed
     */
    public function __get($key)
    {
        return array_key_exists($key, $this->data) ? $this->data[$key] : null;
    }

    /**
     * Check if data key exists
     * 
     * @param string $key
     * @return bool
     */
    public function __isset($key)
    {
        return array_key_exists($key, $this->data);
    }
}
