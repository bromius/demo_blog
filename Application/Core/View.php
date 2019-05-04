<?php

namespace Application\Core;

use Application\Core\Exceptions\SystemException;
use Application\Core\Request;

/**
 * View core module
 */
class View
{
    /**
     * Included CSSs
     * 
     * @var array 
     */
    protected static $_css = [];
    
    /**
     * Included JSs
     * 
     * @var array 
     */
    protected static $_js = [];
    
    /**
     * Current template directory (set in constructor)
     *
     * @var string
     */
    protected $_curDir = '';
    
    /**
     * Template content
     * 
     * @var string
     */
    protected $_content = '';
    
    /**
     * Defined template variables
     * 
     * @var array
     */
    protected $_data = [];

    /**
     * Constructor
     * 
     * @param string $path Template path
     * @param array $data (optional) Parameters passed to template
     * @throws SystemException
     */
    public function __construct($path, array $data = [])
    {
        $filePath = APP_DIR . 'Module/View/' . $path . '.php';

        if (!is_file($filePath))
            throw new SystemException('View not found');

        $this->_curDir = dirname($filePath) . '/';

        if ($data)
            $this->set($data);

        ob_start();

        require $filePath;

        $this->_content = ob_get_clean();
    }

    /**
     * Set key => value variable for template
     * 
     * @param string|int $key Key
     * @param mixed $value Value
     * @return $this
     */
    public function set($key, $value = null)
    {
        if (is_array($key))
            $this->_data = array_merge($key);
        else
            $this->_data[$key] = $value;

        return $this;
    }

    /**
     * Get stored variables data
     * 
     * @param string|int $key Key
     * @param mixed $default Default value
     * @return type
     */
    public function get($key, $default = null)
    {
        return isset($this->_data[$key]) ? $this->_data[$key] : $default;
    }

    /**
     * Includes CSS into template or get list of included CSS files
     * 
     * @param string $path (optional) CSS file path
     * @return array|null List of included CSS files if $path not defined
     */
    public function css($path = null)
    {
        if (!$path)
            return array_unique(static::$_css);

        if (is_array($path)) {
            foreach ($path as $value) {
                $value = cfg()->hosts->static . '/css/' . $value . '.css';
                static::$_css[] = $value;
            }
        } else {
            array_push(static::$_css, cfg()->hosts->static . '/css/' . $path . '.css');
        }
    }

    /**
     * Includes JS into template or get list of included JS files
     * 
     * @param string $path (optional) JS file path
     * @return array|null List of included JS files if $path not defined
     */
    public function js($path = null)
    {
        if (!$path)
            return array_unique(static::$_js);

        if (is_array($path)) {
            foreach ($path as $value) {
                $value = cfg()->hosts->static . '/js/' . $value . '.js';
                static::$_js[] = $value;
            }
        } else {
            array_push(static::$_js, cfg()->hosts->static . '/js/' . $path . '.js');
        }
    }

    /**
     * Creates internal URL
     * 
     * @param string $path URL path
     * @param array $query Query params
     * @return string
     */
    public static function url($path = null, array $query = [])
    {
        return Url::create($path, $query);
    }

    /**
     * Includes external template parts
     * 
     * @param string $path Template path, relative to ROOT_DIR
     * @return string Template content
     */
    public function tpl($path)
    {
        ob_start();
        require $this->_curDir . $path . '.php';
        return ob_get_clean();
    }

    /**
     * Returns template content
     * 
     * @return string
     */
    public function render()
    {
        return $this->_content;
    }

    /**
     * Returns teplate content
     * 
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }
}
