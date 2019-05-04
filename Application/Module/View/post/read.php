<?php
use Application\Module\Model\UsersModel;
use Application\Module\Model\PostsModel;

$this->css('post');

/* @var Application\Module\Model\PostsModel $post */
$post = $this->get('post');
?>

<div class="post">
	<h1><?= $post->title() ?></h1>
	
	<div class="post-date">
		<?= explode('@', $post->user()->email)[0] ?> | <?= $post->created('d.m.Y H:i') ?> 
		<?php if ($post->created != $post->updated): ?>
			<i>(обновлено <?= $post->updated('d.m.Y H:i:s') ?>)</i>
		<?php endif ?>
	</div>

	<div class="post-content">
		<?php if ($post->image()): ?>
			<img class="float-right" src="<?= $post->image() ?>" alt="" />
		<?php endif; ?>
		<?= $post->content() ?>
	</div>
	
	<?php if ($post->isMine(false)): ?>
		<div class="control-panel">
			<button type="button" class="btn btn-sm btn-secondary js-post-edit" data-id="<?= $post->id ?>">Редактировать</button>
			<button type="button" class="btn btn-sm btn-danger js-post-remove" data-id="<?= $post->id ?>">Удалить</button>
		</div>
	<?php endif ?>
</div>