<h1>Регистрация нового пользователя</h1>
<div class="success"><?=$_SESSION['success']?></div>
<div class="form">
	<form action="/inc/reg_proc.php" method="post" title="Форма регистрации">
		<fieldset>
			<legend>Поля обязательны для заполнения</legend>
				<label for="username" title="Введите Ваше имя пользвателя (логин)">Имя пользователя:</label><br>	
				<input class="formTextInput" id="username" type="text" name="username" value="<?=$_SESSION['temp_username']?>"
					placeholder="Введите имя пользователя">
				<div class="errorMsg"> <?=$_SESSION['error_username']?></div>
				<label for="password" title="Введите Ваш пароль">Пароль:</label><br>
				<input class="formTextInput" id="password" type="password" name="password" placeholder="Введите пароль">
				<div class="errorMsg"><?=$_SESSION['error_password']?></div>
				<label for="password" title="Подтвердите Ваш пароль">Подтверждение пароля:</label><br>
				<input class="formTextInput" id="confirm_password" type="password" name="confirm_password" placeholder="Подтвердите пароль"><br>
				<label for="email" title="Введите Ваш адрес электронной почты">E-mail:</label><br>
				<input class="formTextInput" id="email" type="email" name="email" value="<?=$_SESSION['temp_email']?>" placeholder="Введите e-mail адрес">
				<div class="errorMsg"><?=$_SESSION['error_email']?></div>
		</fieldset>
		<fieldset>
			<legend>Дополнительная информация</legend>
				<label for="phone" title="Введите Ваш контактный номер">Контактный номер:</label><br>
				<input class="formTextInput" id="phone" type="phone" pattern="+[0-9]{3}([0-9]{2})-[0-9]{3}-[0-9]{2}-[0-9]{2}" name="phone" 
					placeholder="+375(xx)-xxx-xx-xx" value="<?=$_SESSION['reg_optional_fields']['phone']?>"><br>
				<label for="birthdate" title="Введите дату Вашего рождения">Дата рождения:</label><br>
				<input class="formTextInput" id="birthdate" type="date" name="birthdate" placeholder="Введите дату рождения"
					value="<?=$_SESSION['reg_optional_fields']['birthdate']?>"><br>
		</fieldset>
		<div class="buttons">
			<button class="button" type="submit">Зарегистрироваться</button>
			<button class="button" type="reset">Очистить форму</button>
		</div>
	</form>
</div>