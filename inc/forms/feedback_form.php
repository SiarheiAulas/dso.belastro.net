<h1> Обратная связь</h1>
<div class="success"><?=$_SESSION["email_success"]?></div>
<div class="form">
	<form action="/inc/feedback_proc.php" method="post" title="Форма обратной связи">
		<label for="username" title="Введите Ваше имя">Ваше имя:</label><br>
		<input class="formTextInput" id="username" type="text" name="username" value="<?=$_SESSION["username"] ?? $_SESSION['feedback']['name']?>" 
			placeholder="Введите ваше имя:">
		<div class="errorMsg"><?=$_SESSION["err_empty_username"]?></div>
		<label for="email" title="Введите Ваш адрес электронной почты">Ваш e-mail:</label><br>
		<input class="formTextInput" id="email" type="email" name="email" value="<?=$_SESSION['feedback']['email']?>" placeholder="Введите ваш e-mail:">
		<div class="errorMsg"><?=$_SESSION["err_empty_email"]?></div>
		<label for="subject" title="Введите тему сообщения">Тема сообщения:</label><br>
		<input class="formTextInput" id="subject" type="text" name="subject" value="<?=$_SESSION['feedback']['subject']?>" placeholder="Тема:">
		<div class="errorMsg"><?=$_SESSION["err_empty_subject"]?></div>
		<label for="message" title="Введите текст сообщения">Ваше сообщение:</label><br>
		<textarea class="textarea" id="message" name="message" placeholder="Текст сообщения:"><?=$_SESSION['feedback']['message']?></textarea>
		<div class="errorMsg"><?=$_SESSION["err_message"]?></div>
		<div class="buttons">
			<button class="button" type="submit">Отправить</button>
			<button class="button" type="reset">Очистить форму</button>
		</div>
	</form>
</div>