<?php

namespace Application\Module\Controller;

use Application\Core\Db;
use Application\Core\Request;
use Application\Module\Model\PostsModel;

/**
 * Index page
 */
class IndexController extends \Application\Core\Controller
{
    /**
     * Max posts output limit
     */
    const POSTS_LIST_LIMIT = 10;

    /**
     * Default index page
     * 
     * @return string
     */
    public static function indexAction()
    {
        $rows = PostsModel::getList(static::POSTS_LIST_LIMIT);

        return static::view('index', [
            'content' => static::view('index/posts', [
                'rows' => $rows
            ])
        ]);
    }

}
