<?php
use Application\Core\Request;
use Application\Core\String;
use Application\Module\Model\UsersModel;
use Application\Module\Model\PostsModel;

$this->css('index');
?>

<?php if ($this->get('rows')): ?>
	<div class="row">
		<?php foreach ($this->get('rows') as $row): ?>
			<div class="col-md-12 col-lg-6 col-xl-3">
				<div class="card flex-md-row mb-4 box-shadow post-shortcut">
					<div class="card-body d-flex flex-column align-items-start">
						<h2 class="mb-0">
							<a class="text-dark" href="<?= $this->url('/post/read/' . $row->id) ?>">
								<?= $row->title() ?>
							</a>
						</h2>
						
						<div class="mb-2 text-muted post-date">
							<?= date('d.m.Y H:i', strtotime($row->updated)) ?>
						</div>
						
						<div class="card-text mb-auto post-content">
							<?php if ($row->image()): ?>
								<a class="post-img float-right" href="<?= $this->url('/post/read/' . $row->id) ?>">
									<img src="<?= $row->image() ?>" alt="" />
								</a>
							<?php endif ?>
							
							<?= String::text($row->content(), 150) ?>
							
							<br />
							
							<a href="<?= $this->url('/post/read/' . $row->id) ?>">Читать &raquo;</a>
						</div>
					</div>
				</div>
			</div>
		<?php endforeach ?>
	</div>
<?php else: ?>
	<div class="empty_list">
		Записей пока нет
	</div>
<?php endif ?>