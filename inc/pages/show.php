<?php
$title='Просмотр сообщения';
function page(){
    
	include (ROOT.'/inc/db_connect.php');
	$id=$_GET[article_id];
	$result=$mysqli->prepare("SELECT * FROM `articles` WHERE id=?");
	$result->bind_param('i', $id);
	$result->execute();
	$results=$result->get_result();
    $row=$results->fetch_assoc();
    echo "<div class='article_subject'>$row[subject]</div>";
	echo "<article>
            <!--<div class='publication_date'>Дата выезда: ".date('d-m-Y', strtotime($row[observation_date]))."</div>-->
            <div class='publication_date'>Добавлено: ".date('d-m-Y', strtotime($row[publication_date]))."</div>
            <div class='author'>$row[username]</div>
            <div class='article_content'>$row[content]</div>
        </article>";

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
		echo "<div class='comment'>
                <div class='comment_content'>$comment[comment]</div>
                <div class='publication_date'>$comment[publication_date]</div>
            </div>";
	}
	$result->close();
	$results->close();
	$mysqli->close();
}