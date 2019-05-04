<?php
use Application\Core\Security;
use Application\Module\Model\UsersModel;
?><!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title><?= cfg()->title ?></title>
	
	<link rel="icon" href="<?= cfg()->hosts->static ?>/img/favicon.ico" type="image/x-icon">
	<link rel="shortcut icon" href="<?= cfg()->hosts->static ?>/img/favicon.ico" type="image/x-icon"> 
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
	
	<link rel="stylesheet" href="<?= cfg()->hosts->static ?>/css/styles.css">
	
	<?php foreach ($this->css() as $css): ?>
		<link rel="stylesheet" href="<?= $css ?>">
	<?php endforeach ?>
</head>
<body>
	<div class="d-flex flex-column flex-md-row align-items-center p-3 mb-3 bg-white border-bottom shadow-sm">
		<a class="logo font-weight-normal" href="/">
			<?= cfg()->title ?>
		</a>
        <div class="ml-5 mt-1 mr-md-auto">
            <?php if (!UsersModel::get()->isOnline()): ?>
                Demo: tester@testapp.com | 123456
            <?php endif ?>
        </div>
		<?php if (UsersModel::get()->isOnline()): ?>
			<nav class="my-2 my-md-0 mr-md-3">
				<a class="btn btn-primary js-post-add" href="#">Добавить пост</a>
			</nav>
			<a class="btn btn-secondary" href="/auth/logout">Выход</a>
		<?php else: ?>
			<a class="btn btn-success js-btn-login" href="#">Вход</a>
			<a class="btn btn-secondary m-l-1rem js-btn-signup" href="#">Регистрация</a>
		<?php endif ?>
    </div>
	
    <div class="content">
		<?= $this->get('content') ?>
	</div>
	
	<?= $this->tpl('index/_modals') ?>
	
	<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
	
	<script type="text/javascript" src="<?= cfg()->hosts->static ?>/js/lib.js"></script>
	<script type="text/javascript" src="<?= cfg()->hosts->static ?>/js/index.js"></script>
	
	<?php foreach ($this->js() as $js): ?>
		<script type="text/javascript" src="<?= $js ?>"></script>
	<?php endforeach ?>
        
    <script>
        $(function() {
            $('form').append('<input type="hidden" name="<?= Security::getCSRFParamName() ?>" value="<?= Security::getCSRFToken() ?>">');
        });
    </script>
</body>
</html>