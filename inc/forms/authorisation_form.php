<h1>Авторизация пользователя</h1>
<div class="success"><?=$_SESSION['success']?></div>
<div class="form">
	<form action="/inc/auth_proc.php" method="post" title="Авторизуйтесь для доступа к дополнительным возмоностям сайта" autocomplete>
		<label for="username" title="Введите имя пользователя (логин)">Имя пользователя:</label><br>
		<input class="formTextInput" id="username" type="text" name="username", value="<?=$_SESSION['temp_username']?>" placeholder="Введите имя пользователя">
		<div class="errorMsg"><?=$_SESSION['err_username']?></div>
		<label for="password" title="Введите пароль">Пароль:</label><br>
		<input class="formTextInput" id="password" type="password" name="password" placeholder="Введите пароль"><br>
		<div class="buttons">
			<button class="button" type="submit">Войти</button>
			<button class="button" type="reset">Очистить форму</button>
		</div>
	</form>
</div>