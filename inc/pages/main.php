<?php
$title='главная';
function page(){
	echo '<h1>Главная</h1>';
	include_once (ROOT.'/inc/db_connect.php');
	
	$count_articles=$mysqli->query("SELECT COUNT(*)FROM `articles`");
	$count=$count_articles->fetch_assoc();
	$count['COUNT(*)'];//считает количество статей в базе
	
	$limit_per_page=10;//статей на странице
	$num_pages=ceil($count['COUNT(*)']/$limit_per_page);//нужное количество страниц (номер последней страницы)
	
	$page=$_GET['page'];
	$page=intval($page);//номер страницы
?>
<div class='pagenumber'>
<?php
	for ($i=1; $i<=$num_pages; $i++){
		echo "<span class='pageN'><a href='index.php?act=main&amp;page=$i'>$i</a></span>";
	} // строчка с нужным количеством страниц
?>
</div>
<?php
	if (empty($page)||$page<=0)
		$page=1;
	if($page>$num_pages)
		$page=$num_pages;//проверка условий, хотя они и так нереальны
	
	$start=($page-1)*$limit_per_page;// для установления LIMIT в запросе
	$result=$mysqli->prepare("SELECT * FROM `users` INNER JOIN `articles` ON `articles`.`user_id`=`users`.`id` ORDER BY `publication_date` DESC LIMIT ?,?");
	$result->bind_param('ii',$start,$limit_per_page);
	$result->execute();
	$results=$result->get_result();
	while($row=$results->fetch_assoc()){
		// из объекта в массив
		echo "<article><div class='article_subject'>$row[subject]</div><div class='author'>$row[username]</div>
			<div class='article_content'>$row[content]</div><div class='publication_date'>$row[publication_date]</div></article>";
	
		if(isset($_SESSION['username'])){
			echo "<div class='add_comment'><a href='./index.php?act=comment&amp;article_id=$row[id]&amp;user_id=$row[user_id]'>Комментировать</a></div>";
		}
		if ($_SESSION['username']===$row["username"])
			echo "<div class='add_comment'><a href='./index.php?act=edit&amp;article_id=$row[id]&amp;user_id=$row[user_id]'>Редактировать</a></div>";

		echo "<div class='show_comments'>Комментарии к записи</div>";
		$comments=$mysqli->prepare("SELECT * FROM `comments` INNER JOIN `articles` ON `comments`.`article_id`=`articles`.`id` WHERE `article_id`=?");
		$comments->bind_param('i',$row['id']);
		$comments->execute();
		$result=$comments->get_result();
		while ($comment=$result->fetch_assoc()){
			echo "<div class='comment'><div class='comment_content'>$comment[comment]</div><div class='publication_date'>$comment[publication_date]</div></div>";
		}
		$result->close();
	}
	$next=$page+1;
	$previous=$page-1;
	echo "<div class='page_navigation'><span class='previous'><a href='index.php?act=main&amp;page=$previous'><<<Предыдущая</a></span>
		<span class='next'><a href='index.php?act=main&amp;page=$next'>Следующая>>></a></span></div>";
	$results->close();
	$mysqli->close();
}//выбирает все статьи и выводит на экран
