<?php
session_start();//старт сессии
require_once 'lib.php';//подключить файл с библиотекой функций

/*если в сессии установлено имя пользователя(пользователь авторизован),
 то используется его имя пользователя, если нет, то имя пользователя берется из формы (пост)*/
$username=(isset($_SESSION['username']))?$_SESSION['username']:htmlspecialchars(trim($_POST["username"]));

//передает данные из формы отдельным переменным и удаляет пробелы и теги
$email=htmlspecialchars(trim($_POST["email"]));
$subject=htmlspecialchars(trim($_POST["subject"]));
$message=htmlspecialchars(trim($_POST["message"]));
define('MYEMAIL','avlassergey@list.ru');//для отправки писем и сайта

$_SESSION['feedback']=array('name'=>$username, 'email'=>$email, 'subject'=>$subject, 'message'=>$message);
//чтобы текст сохранялся в форме при обновлении страницы

$_SESSION["err_empty_username"]=(empty($username))?'Введите ваше имя':'';//проверяет каждое поле на пустоту и записывает сообение об ошибке в сессию
$_SESSION["err_empty_email"]=(empty($email))?'Введите ваш email':'';
$_SESSION["err_empty_subject"]=(empty($subject))?'Введите тему сообщения':'';

if(empty($message))//если сообщение пустое
	$_SESSION["err_message"]='Введите ваше сообщение';//сообщение об ошибке
elseif(strlen($message)<=20)//если длинна сообщения меньше 20 знаков в сессию записывается сообщение об ошибке
	$_SESSION["err_message"]='Сообщение слишком короткое. Мин. 20 символов';	
else //если все ок
	$_SESSION["err_message"]=''; //сообщение об ошибке пустое

//lib.php для проверки формата данных и редиректа

if($_SESSION["err_empty_username"]==''&&$_SESSION["err_empty_email"]==''&&$_SESSION["err_empty_subject"]==''&&$_SESSION["err_message"]==''&&check_valid_email($email)!=true&&check_valid_username($username)!=true){
	// если все проверки пройдены
	$_SESSION["email_success"]="$username, Ваше сообщение успешно отправлено";// сообщение об ошибке в сессию
	$subject="=?utf-8?b?".base64_encode($subject)."?=";//кодировка темы сообщения
	$headers="From: $email\r\nReply to: $email\r\nContent type:text/plain; charset=utf-8\r\n";
	mail(MYEMAIL,$subject,$message,$headers);// письмо с сайта мне
	redirect();//редирект на ту же страницу с формой отправки сообщения		
}else{ 
	redirect();//редирект на страницу откуда вызван скрипт
}