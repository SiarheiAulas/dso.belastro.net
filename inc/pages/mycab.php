<?php
$title='личный кабинет';//для отображения в заголовке
require (ROOT.'/inc/db_connect.php');// соединение с БД
function page(){
	global $mysqli;
	echo "<h1>Личный кабинет: $_SESSION[username]</h1>";
	//первая половина страницы содержит список всех статей авторизованного пользователя (с пагинацией)
	
	$count_articles=$mysqli->prepare("SELECT COUNT(*)FROM `users` INNER JOIN `articles` ON `articles`.`user_id`=`users`.`id` WHERE `username`=?");
	//шаблон запроса в БД
	$count_articles->bind_param('s',$_SESSION['username']);//привязка параметров
	$count_articles->execute();//исполнение запроса
	$result=$count_articles->get_result();//результат запроса (объект)
	$count=$result->fetch_assoc();//преобразует в асс массив
	$count['COUNT(*)'];//количество статей в базе
	
	$limit_per_page=10;//статей на странице
	
	$num_pages=ceil($count['COUNT(*)']/$limit_per_page);//нужное количество страниц (номер последней страницы)
	
	$page=$_GET['page'];
	$page=intval($page);//номер страницы (приведен к целочисленному типу)
?>
<div class="pagenumber">
<?php
	for ($i=1; $i<=$num_pages; $i++){
		echo "<span class='pageN'><a href='index.php?act=mycab&amp;page=$i' target='_self'>$i</a></span>";
	} 	// строчка с нужным количеством страниц
?>
</div>
<?php
	if (empty($page)||$page<=0)
		$page=1;
	if($page>$num_pages)
		$page=$num_pages;// проверка корректности значения номера страницы и его коррекция
	
	$start=($page-1)*$limit_per_page;//диапазон номеров статей на странице
	
	$articles=$mysqli->prepare("SELECT * FROM `users` INNER JOIN `articles` ON `articles`.`user_id`=`users`.`id` WHERE `username`=? 
		ORDER BY `publication_date` DESC LIMIT ?,?");//шаблон запроса в БД (объединение данных из 2 таблиц, сортировка в нисх.порядке)
	$articles->bind_param('sii',$_SESSION['username'],$start,$limit_per_page);//привязка параметров
	$articles->execute();//выполнение запроса
	$result_articles=$articles->get_result();//результат запроса в виде объекта
	
	//цикл выводит статьи на страницу в виде блоков
	while ($row_articles=$result_articles->fetch_assoc()){
		echo "<article><div class='article_subject'>$row_articles[subject]</div><div class='author'>$row_articles[username]</div>
			<div class='article_content'>$row_articles[content]</div><div class='publication_date'>$row_articles[publication_date]</div></article>";
		//для каждой статьи выбираются из БД комментарии
		$comments=$mysqli->prepare("SELECT * FROM `comments` INNER JOIN `articles` ON `comments`.`article_id`=`articles`.`id` WHERE `article_id`=?");
		//шаблон запроса (аналогичен предыдущему)
		$comments->bind_param('i',$row_articles['id']);
		$comments->execute();
		$result_comment=$comments->get_result();//получение результата запроса как объекта
			//вложенный цикл выводит комменты под каждой статьей
			while ($row_comments=$result_comment->fetch_assoc()){
				echo "<div class='comment'><div class='comment_content'>$row_comments[comment]</div>
					<div class='publication_date'>$row_comments[publication_date]</div></div>";
			}
		$result_comment->close();//очистить объект
	}
	
	//рисует кнопки навигации вперед-назад по страницам
	$next=$page+1;
	$previous=$page-1;
	echo "<div class='page_navigation'><span class='previous'><a href='index.php?act=mycab&amp;page=$previous'><<<Предыдущая </a></span>
		<span class='next'><a href='index.php?act=mycab&amp;page=$next'>Следующая>>></a></span></div>";
	//отображает ссылки в виде блока на странице
	
	$result_articles->close();//очистить объеки результата запроса
	
	//вторая половина страницы показывает все комментарии авторизованного пользователя
	
	$count_comments=$mysqli->prepare("SELECT COUNT(*) FROM `users` INNER JOIN `comments` ON `users`.`id`=`comments`.`user_id` WHERE `username`=?");
	//шаблон запроса
	$count_comments->bind_param('s',$_SESSION['username']);//привязка параметров
	$count_comments->execute();//выполнение
	$result_count_comments=$count_comments->get_result();//получение результата в виде объекта
	$count_comment=$result_count_comments->fetch_assoc(); //$count_comment['COUNT(*)'] считает количество статей в базе
	$result_count_comments->close();//очистить объект
	
	$comment_per_page=2;//статей на странице
	$num_comment_pages=ceil($count_comment['COUNT(*)']/$comment_per_page);//нужное количество страниц (номер последней страницы)
	
	$page_comment=$_GET['pagecomment'];
	$page_comment=intval($page_comment);//номер страницы
?>
<div class='pagenumber'>
<?php
	for ($j=1; $j<=$num_comment_pages; $j++){
		echo "<span class='pageN'><a href='index.php?act=mycab&amp;page=$page&amp;pagecomment=$j'>$j</a></span>";
	} 
	// строчка с нужным количеством страниц
?>
</div>
<?php
	if (empty($page_comment)||$page_comment<=0)
		$page_comment=1;
	if($page_comment>$num_comment_pages)
		$page_comment=$num_comment_pages;
	//проверка значения номера страницы и его коррекция
	
	$start_comment=($page_comment-1)*$comment_per_page;// диапазон комментов на данной странице
	$mycomments=$mysqli->prepare("SELECT * FROM `users` INNER JOIN `comments` ON `users`.`id`=`comments`.`user_id` WHERE `username`=? 
		ORDER BY `publication_date` LIMIT ?,?");//шаблон запроса
	$mycomments->bind_param('sii',$_SESSION['username'],$start_comment,$comment_per_page);//привязка параметров
	$mycomments->execute();//выполнение
	$result_mycomments=$mycomments->get_result();//получение результата в виде объекта
	while ($row_mycomments=$result_mycomments->fetch_assoc()){
		echo "<div class='comment'><div class='author'>$row_mycomments[username]</div><div class='publication_date'>$row_mycomments[publication_date]</div>
			<div class='comment_content'>$row_mycomments[comment]</div><div>";
	}
	//цикл выводит комментарии в виде блоков
	
	//рисует ссылки навигации
	$next2=$page_comment+1;
	$previous2=$page_comment-1;
	echo "<div class='page_navigation'><span class='previous'><a href='index.php?act=mycab&amp;page=$page&amp;pagecomment=$previous2'><<<Предыдущая</a>
	</span><span class='next'><a href='index.php?act=mycab&amp;page=$page&amp;pagecomment=$next2'>Следующая>>></a></span><div>";
	$result_mycomments->close();//очистить объект результата запроса
}