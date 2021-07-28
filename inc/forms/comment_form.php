<h1>Добавление комментария</h1>
<div class="form">
	<form action="/inc/comment_proc.php" method="post" title="Добавить комментарий">
		<input type="hidden" name="article_id" value="<?=$_GET['article_id']?>">
		<input type="hidden" name="user_id" value="<?=$_GET['user_id']?>">
		<label for="addcomment" title="Введите текст Вашего комментария">Ваш комментарий:</label><br>
		<textarea class="textarea" id="addcomment" name="message" placeholder="Введите комментарий" cols="70" rows="10"><?=$_SESSION['comment']?></textarea>
		<div class="errorMsg"><?=$_SESSION['error_comment']?></div>
		<div class="buttons">
			<button class="button" type="submit">Добавить комментарий</button>
			<button class="button" type="reset">Очистить форму</button>
		</div>
	</form>
</div>