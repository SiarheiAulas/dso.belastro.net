<?php
$title='Результаты поиска';//для заголовка
require (ROOT.'/inc/db_connect.php');//для подключения к БД (используется mysqli)
function page(){
	global $mysqli;
	if($_SERVER['REQUEST_METHOD']=='POST'){
		/*проверяет каким методом получена страница
		если пост-то в сессию записываются ид пользователя и поисковый запрос*/
		$user_id=$_POST['username'];
		$_SESSION['user_id']=$user_id;
		$search=$_POST['search'];
		$_SESSION['search']=$search;
	} else {
		//иначе (если метод гет) ид пользователя и поисковый запрос считываются из сессии
		$search=$_SESSION['search'];
		$user_id=$_SESSION['user_id'];}

	$_SESSION['tmp_search']=$search;// для отображения запроса в форме при перезагрузке
	
	//$search="%$search%";//строка запроса преобразуется для последующей вставки в шаблон запроса в оператор LIKE (не актуально, поиск заменен на полнотекстовый)

	//считает количество статей по запросу
	if($user_id) {//если выбран пользователь в форме поиска
		$count_articles=$mysqli->prepare("SELECT COUNT(*) FROM `articles` WHERE user_id=? AND MATCH (subject,content) AGAINST (?)");
		//шаблон запроса
		$count_articles->bind_param('ss',$user_id, $search);//привязка параметров 1
	} else { // если не выбран пользовател в форме поиска
		$count_articles=$mysqli->prepare("SELECT COUNT(*) FROM `articles` WHERE MATCH (subject,content) AGAINST (?)");
		//шаблон запроса без указания пользователя
		$count_articles->bind_param('s', $search);//привязка параметров 2 
	}
	$count_articles->execute();//исполнение с привязанными параметрами 1 или 2
	$res=$count_articles->get_result(); //результат запроса в виде объекта
	$count=$res->fetch_assoc();//преобразуется в асс. массив

	//пагинация
	$limit_per_page=10; //статей на странице
	$num_pages=ceil($count["COUNT(*)"]/$limit_per_page);//количество страниц в пагинации
	$page=intval($_GET['page']);//номер текущей страницы

	if (empty($page)||$page<=0)
		$page=1;
	if($page>$num_pages)
		$page=$num_pages;//валидация номера страницы
?>
<div class="pagenumber">
<?php
	for($i=1;$i<=$num_pages;$i++){
		echo "<span class=\"pageN\"><a href='index.php?act=search_proc&amp;page=$i'>$i</a></span>";}
	//выводит строчку с номерами страниц
?>
</div>
<?php	
	$start=($page-1)*$limit_per_page;//диапазон статей на странице

	if(strlen($search)<4||strlen($search)>128){ //если неправильная длинна запроса
		$_SESSION['error_search']='Запрос должен включать от 4 до 128 символов';
		header ('location:index.php?act=search');//редирект на форму поиска с отображением сообщения об ошибке
	} else {//если запрос ок
		$_SESSION['error_search']=''; //сообщение об ошибке содержит пустую строку
		
		//выбирает статьи по запросу
		if ($user_id){//если пользователь в форме запроса указан
			$request=$mysqli->prepare("SELECT * FROM `users` INNER JOIN `articles` ON `articles`.`user_id`=`users`.`id` 
				WHERE user_id=? AND MATCH (subject,content) AGAINST (?) LIMIT ?, ?");
			$request->bind_param('ssii', $user_id, $search, $start,$limit_per_page); //привязка параметров 1 
		} else { //пользователь не указан в форме запроса
			$request=$mysqli->prepare("SELECT * FROM `users` INNER JOIN `articles` ON `articles`.`user_id`=`users`.`id` 
				WHERE MATCH (subject,content) AGAINST (?) LIMIT ?, ?");
			$request->bind_param('sii', $search, $start, $limit_per_page);//привязка параметров 2
		}
		
		$request->execute();// выполнение запроса с параметрами 1 или 2
		$result=$request->get_result();//результат запроса (статьи по запросу) в виде объекта
	
		//цикл выводит статьи в виде блоков
		while ($row=$result->fetch_assoc()){
			echo "<article><div class='article_subject'>$row[subject]</div><div class='author'>$row[username]</div>
				<div class='article_content'>$row[content]</div><div class='publication_date'>$row[publication_date]</div></article>";
			//и добавляется ссылка на комментирование к каждой
			echo "<div class='add_comment'><a href='./index.php?act=comment&amp;article_id=$row[id]&amp;user_id=$row[user_id]'>Комментировать</a></div>";
			$comments=$mysqli->prepare("SELECT * FROM `comments` WHERE `article_id`=?");
			$comments->bind_param('i',$row['id']);
			$comments->execute();
			$result2=$comments->get_result();
		
			//вложенный цикл выводит комменты под каждую статью
			while ($comment=$result2->fetch_assoc()){
				echo "<div class='comment'><div class='comment_content'>$comment[comment]</div>
					<div class='publication_date'>$comment[publication_date]</div></div>";
			}
			$result2->close();//очистить объект комментов
		}
		$result->close();//очистить объект результата выбора статей
	}
}