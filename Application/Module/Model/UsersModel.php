<?php

namespace Application\Module\Model;

use Application\Core\Strings;
use Application\Core\Db;
use Application\Core\Registry;

/**
 * Users table model
 */
class UsersModel extends \Application\Core\Model
{
    /**
     * Table name
     * 
     * @var string
     */
    protected static $table = 'users';

    /**
     * Get user model
     * 
     * @param int $id User ID
     * @return UsersModel
     */
    public static function get($id = null)
    {
        if (Registry::get(__CLASS__ . ':' . __METHOD__ . ':' . $id)) {
            return Registry::get(__CLASS__ . ':' . __METHOD__ . ':' . $id);
        }

        if (!$id && isset($_COOKIE['uid'])) {
            $id = $_COOKIE['uid'];
        }

        return Registry::set(__CLASS__ . ':' . __METHOD__ . ':' . $id, parent::get($id));
    }

    /**
     * Get user model by Email
     * 
     * @param type $email
     * @return array
     */
    public static function getByEmail($email)
    {
        return static::select('
			SELECT *
			FROM `users`
			WHERE `email` = #s
		', $email);
    }

    /**
     * Check whether user is online
     * 
     * @return bool
     */
    public function isOnline()
    {
        if (empty($_COOKIE['skey'])) {
            return false;
        }
        
        return $this->id 
            && $_COOKIE['skey'] 
            && password_verify($this->password . cfg()->salt->common, $_COOKIE['skey']);
    }

}
