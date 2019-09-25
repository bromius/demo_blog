<?php

namespace Application\Module\Controller;

use Application\Core\Security;
use Application\Core\Db;
use Application\Core\Request;
use Application\Core\Img;
use Application\Module\Model\PostsModel;
use Application\Module\Model\UsersModel;

/**
 * Posts
 */
class PostController extends \Application\Core\Controller
{
    /**
     * Article page
     * 
     * @return string
     */
    public static function readAction()
    {
        $id = Request::path(2);

        $model = PostsModel::get($id);
        $model->isPublished();

        return static::view('index', [
            'content' => static::view('post/read', [
                'post' => $model
            ])
        ]);
    }

    /**
     * Save article
     * 
     * @return string
     */
    public static function saveAction()
    {
        $id = Request::get('id');
        $userId = UsersModel::get()->id;

        $title = Request::get('title');
        $content = Request::get('content');
        $img = Request::files('img');
        
        $csrfToken = Request::get('csrf_token');
        
        Security::checkCSRFToken($csrfToken);

        if (!preg_match('/.{1,100}/ui', $title)) {
            return static::result(false, 'Заголовок должен содержать от 1 до 100 символов');
        }

        if (!preg_match('/.{1,1000}/ui', $content)) {
            return static::result(false, 'Текст должен содержать от 1 до 1000 символов');
        }

        if ($img) {
            list ($width, $height, $fileType) = getimagesize($img['tmp_name']);

            if (!in_array($fileType, [IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG])) {
                return static::result(false, 'Неверный формат файла. Необходимо загрузить изображение');
            }

            $image = new Img();
            $image->load($img['tmp_name']);

            if ($width > 320) {
                $image->width(320);
            }
            
            if ($height > 240) {
                $image->width(240);
            }

            $imgFileName = uniqid() . '.jpg';

            $image->save(cfg()->paths->upload . $imgFileName, IMAGETYPE_PNG);
        } else {
            $imgFileName = '';
        }

        $data = [
            'title' => $title,
            'content' => $content
        ];

        if ($imgFileName) {
            $data['img'] = $imgFileName;
        }

        if ($id) {
            $post = PostsModel::get($id);
            $post->isMine();
            $post = $post->update($data);
        } else {
            $post = PostsModel::insert(array_merge($data, [
                'user_id' => $userId
            ]));
        }

        return static::result(true, static::url('/post/read/' . $post->id));
    }

    /**
     * Get article data by ID
     * 
     * @return string
     */
    public static function getAction()
    {
        $model = PostsModel::get(Request::get('id'));
        $model->isMine();

        return static::result(true, [
            'id' => $model->id,
            'title' => $model->title,
            'content' => $model->content
        ]);
    }

    /**
     * Remove article
     * 
     * @return string
     */
    public static function removeAction()
    {
        $model = PostsModel::get(Request::get('id'));
        $model->isMine();
        $model->remove();

        return static::result(true);
    }

}
