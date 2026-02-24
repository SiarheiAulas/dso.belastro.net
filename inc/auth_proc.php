<?php
session_start();//старт сессии
require_once 'lib.php';//подключить библиотеку функций
require_once 'db_connect.php';
//удалить пробелы и теги из пользовательсткго ввода имени
$username=htmlspecialchars(trim($_POST['username']));
//хэширование введенного пароля
$password=md5($_POST['password']);

//используются функции из библиотеки для валидации пользовательсткого ввода
if (check_empty($username))
	$_SESSION['err_username']='Введите имя пользователя';
elseif (!check_exist_auth($username, $password))
	$_SESSION['err_username']='Пользователь с данным именем и паролем не найден';
else{
	$_SESSION['err_username']='';
	$_SESSION['temp_username']=$username;
}
	
if ($_SESSION['err_username']){
	redirect();//если есть сообщения об ошибках, то редирект назад, где они будут отображены в форме
}else{//если нет  ошибок
	$_SESSION['username']=$username;//имя пользователя пишется в сессию
	$_SESSION['success']='Вы вошли как '.$_SESSION['username'];//сообщение об успехе пишется в сессию и будет отображено в форме
	redirect();//редирект назад
}