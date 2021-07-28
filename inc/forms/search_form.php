<?php
	include (ROOT.'/inc/db_connect.php');//для подключения к БД
	$request=$mysqli->query("SELECT id, username FROM users ORDER BY username");//выбор id и имени пользователя для всех пользователей из БД, возвращает массив 
?>
<h1>Поиск</h1>
<div class="tooltip">Запрос должен включать не менее 4 и не более 128 символов</div>
<div class="form">
	<form class="search_form" action="index.php?act=search_proc" method="post" title="Введите поисковый запрос">
		<label id="lbl1" for="search" title="Введите поисковый запрос">Поисковый запрос:</label>
		<input class="formTextInput" id="search" type="text" name="search" value="<?=$_SESSION['tmp_search']?>" placeholder="Найти в названии или тексте статьи">
		<!--сохраняет поисковый запрос при обновлении страницы-->
		<label id="lbl2" for="username" title="Выберите автора"> Автор:</label>
		<select name="username" id="username">
			<option value="" disabled selected>Любой</option><!--по умолчанию-->
			<?php
			//цикл выводит список всех пользователей из БД
			while ($result=$request->fetch_assoc()){
				echo "<option value='$result[id]'>$result[username]</option>";
			}
			$request->close();//очистить объект
			$mysqli->close();//закрыть соединение с БД
			?>
		</select>
		<button class="searchbtn" type="submit">Поиск</button>
		<button class="searchbtn" type="reset">Очистить форму</button>
	</form>
</div>
<div class="errorMsg"><?=$_SESSION['error_search']?></div>