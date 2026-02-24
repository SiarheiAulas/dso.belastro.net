<?php
session_start();//старт сессии
require_once 'lib.php';//подключить библиотеку функций

//получение и обработка пользовательсткого ввода
$user_id=$_POST['user_id'];
$article_id=$_POST['article_id'];
$comment=htmlspecialchars(trim($_POST['message']));
$_SESSION['comment']=$comment;

//валидация пользовательсткого ввода (тут только проверка на пустую строку)
if(empty($comment)){
	$_SESSION['error_comment']='Введите комментарий';
	redirect();
}else{ //если ошибок нет
	include 'db_connect.php'; //коннект с БД
	$x=$mysqli->prepare("INSERT INTO `comments` (`article_id`,`user_id`,`comment`) VALUES(?,?,?)");
	$x->bind_param('iis',$article_id,$user_id,$comment);
	$x->execute();//записать коммент
	$mysqli->close();//закрыть соединение с БД
	header('location:/index.php'); //редирект на главную
}