<?php
session_start();//старт сессии
require_once 'lib.php';//подключение библиотеки функций

//получение и обработка пользовательсткого ввода
$subject=stripslashes(htmlspecialchars(trim($_POST['subject']))); //удалить слэши
$content=strip_tags(trim($_POST['content']),'<b><i><a><img><br><p>');// удалить тэги кроме разрешенных
$article_id=$_POST['article_id'];
$_SESSION['edit']=array('subject'=>$subject,'content'=>$content);
//валидация пользовательсткого ввода (проверка на пустую строку). Если пусто, то записывает в сессию сообщение об ошибке
if(empty($subject)){
	$_SESSION['error_subject']='Введите тему сообщения';
	$_SESSION['edit_success']='';
	redirect();
}elseif (empty($content)){
	$_SESSION['error_content']='Введите сообщение';
	$_SESSION['edit_success']='';
	redirect();
}else{ // если ошибок нет
	include 'db_connect.php';//коннект к БД
	/*$userid=$mysqli->prepare("SELECT `id` FROM `users` WHERE `username`=?");
	$userid->bind_param('s',$username);
	$userid->execute();
	$user=$userid->get_result(); // получаем из БД ид пользователя по имени польователя
	$id=$user->fetch_assoc();
	$userid->close();//очищаем объект*/
	$edit=$mysqli->prepare("UPDATE `articles` SET `subject`=?,`content`=? WHERE `id`=?");
	//шаблон запроса на добавление статьи в БД
	$img_dir=$_SERVER['DOCUMENT_ROOT'].'/img/';//определяет директорию для картинок
	for ($i=0; $i<5; $i++){//для каждого выбранного файла
		if ($_FILES["upload"]["name"]["$i"]){ //если выбран файл в поле с номером i
			$uploading_file=$img_dir.basename($_FILES["upload"]['name']["$i"]);//имя файла
			$filetype=strtolower(pathinfo($uploading_file, PATHINFO_EXTENSION));//расширение файла
			$isvalid=getimagesize($_FILES["upload"]['tmp_name']["$i"]);//проверяет является ли файл картинкой
			if (file_exists($uploading_file)){//проверяет не загружен ли уже файл с таким именем, если да, то пишет ошибку
				$_SESSION['error_content']='Файл с данным именем уже загружен';
			}elseif (!$isvalid){// проверяет является ли файл изображением
				$_SESSION['error_content']='Выбранный файл не является изображением';
			}elseif ($filetype!='jpg'&&$filetype!='jpeg'&&$filetype!='bmp'&&$filetype!='png'){ //проверет расширение файла
			$_SESSION['error_content']='Недопустимое расширение файла';
			}elseif ($_FILES['upload']['size']["$i"]>512000){ //проверяет максимальный размер файла
				$_SESSION['error_content']='Превышен максимальный размер файла';
			}else{	
				$_SESSION['error_content']='';
				$content=$content."<div class='illustration'><img src='./img/".basename($_FILES[upload][name][$i])."alt='illustration' title='Illustration'></div>"; //обавляет строчку с имг тегом к записи в БД
				move_uploaded_file($_FILES["upload"]['tmp_name']["$i"], $uploading_file);//перемещает файл из временной папки по указанному адресу
			}
		}
	}
	$edit->bind_param('ssi',$subject,$content,$article_id);
	$edit->execute();//добавляет запись в БД
	$edit->close();//очищает объект
	$_SESSION['edit_success']='Запись обновлена'; //в сессию пишется сообщение об успехе
	$_SESSION['error_subject']=$_SESSION['error_content']='';//и пустое сообщение об ошибке
	$mysqli->close();//закрыть соединение с БД
	redirect();//редирект назад
}