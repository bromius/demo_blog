<?php
use Application\Module\Model\UsersModel;
use Application\Module\Model\PostsModel;
use Application\Module\Controller\AuthController;
?>
<div class="modal fade js-modal-post-edit">
	<form enctype="application/x-www-form-urlencoded">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Публикация</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label class="control-label" for="post_title">Заголовок</label>
						<input class="form-control" type="text" id="post_title" name="title" placeholder="Максимум 100 символов" required />
					</div>
					<div class="form-group">
						<label class="control-label" for="post_content">Описание</label>
						<textarea class="form-control" id="post_content" name="content" placeholder="Максимум 1000 символов" rows="5" required></textarea>
					</div>
					<div class="form-group">
						<label class="control-label" for="post_img">Изображение</label>
						<div class="clearfix"></div>
						<input type="file" id="post_img" name="img" />
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary">Сохранить</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
				</div>
			</div>
		</div>
		<input type="hidden" name="id" />
	</form>
</div>

<div class="modal fade modal-signup js-modal-signup">
	<form>
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="modal_signup_title">Регистрация</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="form-group row">
						<div class="col-3">
							<label for="signup_email" class="col-form-label">Email</label>
						</div>
						<div class="col-9">
							<div class="input-group">
								<span class="input-group-prepend">
									<span class="input-group-text">
										<i class="fa fa-at"></i>
									</span>
								</span>
                                <input type="email" class="form-control" id="signup_email" name="email" placeholder="Введите Ваш Email" required>
							</div>
						</div>
					</div>
					<div class="form-group row">
						<div class="col-3">
							<label for="signup_password" class="col-form-label">Пароль</label>
						</div>
						<div class="col-9">
							<div class="input-group">
								<span class="input-group-prepend">
									<span class="input-group-text">
										<i class="fa fa-key" aria-hidden="true"></i>
									</span>
								</span>
                                <input type="password" class="form-control" id="signup_password" name="password" placeholder="Мин. <?= AuthController::PASSWORD_MIN_LENGTH ?> символов" required>
							</div>
						</div>
					</div>
					<div class="form-group row">
						<div class="col-3">
							<label for="signup_password_confirm" class="col-form-label">и еще раз</label>
						</div>
						<div class="col-9">
							<div class="input-group">
								<span class="input-group-prepend">
									<span class="input-group-text">
										<i class="fa fa-key" aria-hidden="true"></i>
									</span>
								</span>
                                <input type="password" class="form-control" id="signup_password_confirm" name="password_confirm" placeholder="Повторите ввод пароля" required>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<input type="submit" class="btn btn-success" value="Продолжить"/>
				</div>
			</div>
		</div>
	</form>
</div>

<div class="modal fade js-modal-login">
	<form>
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="modal_login_title">Вход</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="form-group row">
						<div class="col-2">
							<label for="login_email" class="col-form-label">Email</label>
						</div>
						<div class="col-10">
							<div class="input-group">
								<span class="input-group-prepend">
									<span class="input-group-text">
										<i class="fa fa-at"></i>
									</span>
								</span>
								<input type="email" class="form-control" id="login_email" name="email" required>
							</div>
						</div>
					</div>
					<div class="form-group row">
						<div class="col-2">
							<label for="login_password" class="col-form-label">Пароль</label>
						</div>
						<div class="col-10">
							<div class="input-group">
								<span class="input-group-prepend">
									<span class="input-group-text">
										<i class="fa fa-key" aria-hidden="true"></i>
									</span>
								</span>
								<input type="password" class="form-control" id="login_password" name="password" required>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<input type="submit" class="btn btn-success" value="Продолжить"/>
				</div>
			</div>
		</div>
	</form>
</div>