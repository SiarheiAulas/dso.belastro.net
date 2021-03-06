<h1>Редактирование записи в журнале наблюдений</h1>
<div class="success"><?=$_SESSION['edit_success']?></div>
<div class="form">
	<form action="/inc/edit_proc.php" method="post" enctype="multipart/form-data" title="Редактировать запись в журнале наблюдений">
		<label for="subject" title="Введите тему Вашего сообщения">Тема вашего сообщения:</label><br>
		<input class="formTextInput" id="subject" type="text" name="subject" value="<?=$_SESSION['edit']['subject']?>" placeholder="Тема сообщения">
		<div class="errorMsg"><?=$_SESSION['error_subject']?></div>
        <label for="observation_date" title="Введите дату наблюдения">Дата наблюдения</label><br>
        <input type="date" class="formTextInput" id="observation_date" name="observation_date" placeholder="Дата наблюдения" value="<?=$_SESSION['edit']['observation_date']?>">
        <div class="errorMsg"><?=$_SESSION['error_observation_date']?></div>
		<label for="content" title="Введите такст вашего сообщения">Ваше сообщение:</label>
		<div class="tooltip">Допустимые теги для форматирования:&lt;b&gt; &lt;i&gt; &lt;br&gt; &lt;img&gt; &lt;a&gt; &lt;p&gt;</div>
		<textarea class="textarea" id="content" name="content" placeholder="Ваше сообщение"><?=$_SESSION['edit']['content']?></textarea>
		<div class="errorMsg"><?=$_SESSION['error_content']?></div>
		<input type="hidden" name="article_id" value="<?=$_GET['article_id']?>">
		<div id="addfile">
			<div class="label" title="Прикрепить файл к записи">Добавить файл:</div>
			<div class="upload">
				<input type="file" name="upload[0]"><br>
				<input type="file" name="upload[1]"><br>
				<input type="file" name="upload[2]"><br>
				<input type="file" name="upload[3]"><br>
				<input type="file" name="upload[4]"><br>
			</div>
			<div class="tooltip">Допустимые расширения:jpg, jpeg, bmp, png. Максимальный размер 500 кБ.</div>
		</div>
		<div class="buttons">
			<button class="button" type="submit">Изменить запись</button>
			<button class="button" type="reset">Очистить форму</button>
		</div>
	</form>
</div>
	