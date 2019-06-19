<?php

namespace Application\Module\Model;

use Application\Core\Strings;
use Application\Core\Db;
use Application\Core\Exceptions\PublicException;

/**
 * Posts model
 */
class PostsModel extends \Application\Core\Model
{
    /**
     * Table name
     * 
     * @var string
     */
    protected static $table = 'posts';

    const STATUS_PUBLISHED = 'published';
    const STATUS_REMOVED = 'removed';

    /**
     * Post title
     * 
     * @return string
     */
    public function title()
    {
        return Strings::escape($this->col(__FUNCTION__));
    }

    /**
     * Post content
     * 
     * @return string
     */
    public function content()
    {
        return Strings::text($this->col(__FUNCTION__));
    }

    /**
     * Post image
     * 
     * @return string
     */
    public function image()
    {
        return $this->img ? cfg()->hosts->static . '/upload/' . $this->img : null;
    }

    /**
     * Post updated timestamp/formated date
     * 
     * @param string $dateMask (optional) PHP date() function mask
     * @return string
     */
    public function updated($dateMask = null)
    {
        return $dateMask ? date($dateMask, strtotime($this->col(__FUNCTION__))) : $this->col(__FUNCTION__);
    }

    /**
     * Post created timestamp/formated date
     * 
     * @param string $dateMask (optional) PHP date() function mask
     * @return string
     */
    public function created($dateMask = null)
    {
        return $dateMask 
            ? date($dateMask, strtotime($this->col(__FUNCTION__))) 
            : $this->col(__FUNCTION__);
    }

    /**
     * Post author
     * 
     * @return string
     */
    public function user()
    {
        return UsersModel::get($this->user_id);
    }
    
    /**
     * Check whether post author is current user
     * 
     * @param bool $throwException Throws exception if not FALSE
     * @return boolean
     * @throws PublicException
     */
    public function isMine($throwException = true)
    {
        if ($this->user_id != UsersModel::get()->id) {
            if ($throwException) {
                throw new PublicException('У Вас недостаточно прав для доступа к этому материалу');
            } else {
                return false;
            }
        }
        return true;
    }

    /**
     * Check whether post is published (status check)
     * 
     * @param bool $throwException Throws exception if not FALSE
     * @return boolean
     * @throws PublicException
     */
    public function isPublished($throwException = true)
    {
        if ($this->status != static::STATUS_PUBLISHED) {
            if ($throwException) {
                throw new PublicException('Пост не найден');
            } else {
                return false;
            }
        }
        return true;
    }

    /**
     * Remove post (change status)
     */
    public function remove()
    {
        $this->update('status', static::STATUS_REMOVED);
    }

    /**
     * Get published posts
     * 
     * @param int $limit
     * @return array
     */
    public static function getList($limit = 10)
    {
        return static::getMultiple('
			SELECT *
			FROM `posts`
			WHERE `status` != #s
			ORDER BY `id` DESC
			LIMIT #d
		',
            static::STATUS_REMOVED,
            $limit
        );
    }
}
