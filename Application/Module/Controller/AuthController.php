<?php

namespace Application\Module\Controller;

use Application\Core\Security;
use Application\Core\Db;
use Application\Core\Request;
use Application\Module\Model\UsersModel;

/**
 * Authorization
 */
class AuthController extends \Application\Core\Controller
{
    /**
     * Password string minimal length
     */
    const PASSWORD_MIN_LENGTH = 6;
    
    /**
     * Sign up
     * 
     * @return string
     */
    public static function signupAction()
    {
        $email = Request::get('email');
        $password = Request::get('password');
        $passwordConfirm = Request::get('password_confirm');
        $csrfToken = Request::get('csrf_token');
        
        Security::checkCSRFToken($csrfToken);

        if (!mb_eregi("^([a-z0-9_\.\-]{1,25})@([a-z0-9\.\-]{1,20})\.([a-z]{2,4})$", $email))
            return static::result(false, 'Email введен неверно');

        if (!$password || !$passwordConfirm)
            return static::result(false, 'Необходимо ввести пароль');

        if (mb_strlen($password, 'utf8') <  static::PASSWORD_MIN_LENGTH)
            return static::result(false, 'Пароль должен содержать минимум ' . static::PASSWORD_MIN_LENGTH . ' символов');

        if ($password != $passwordConfirm)
            return static::result(false, 'Пароли не совпадают');

        if (UsersModel::getByEmail($email)->exists())
            return static::result(false, 'Этот Email уже занят. Пожалуйста, выберите другой');

        UsersModel::insert([
            'email' => $email,
            'password' => password_hash($password . cfg()->salt->common, PASSWORD_DEFAULT)
        ]);

        print static::result(true);
    }

    /**
     * Sign in
     * 
     * @return string
     */
    public static function signinAction()
    {
        $email = Request::get('email');
        $password = Request::get('password');
        $csrfToken = Request::get('csrf_token');
        
        Security::checkCSRFToken($csrfToken);

        $user = UsersModel::getByEmail($email);

        if (!$user || !password_verify($password . cfg()->salt->common, $user->password))
            return static::result(false, 'Логин или пароль введены неверно');

        setcookie('uid', $user->id, strtotime('+1 month'), '/', null, false, true);
        setcookie('skey', password_hash($user->password . cfg()->salt->common, PASSWORD_DEFAULT), strtotime('+1 month'), '/', null, false, true);

        print static::result(true);
    }

    /**
     * Logout
     */
    public static function logoutAction()
    {
        setcookie('uid', null, -1, '/', null, false, true);
        setcookie('skey', null, -1, '/', null, false, true);

        header('Location: /');
        exit;
    }

}
