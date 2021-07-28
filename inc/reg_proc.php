<?php
session_start();// старт сессии
require_once 'lib.php';//подключить библиотеку функций
require_once 'db_connect.php';
$username=htmlspecialchars(trim($_POST['username']));//убрать теги и пробелы из пользовательского ввода и записать в переменные
$password=md5($_POST['password']); //хэшировать пользовательский ввод
$confirm_password=md5($_POST['confirm_password']);
$email=htmlspecialchars(trim($_POST['email']));
$phone=htmlspecialchars(trim($_POST['phone']));
$birthdate=htmlspecialchars(trim($_POST['birthdate']));
$_SESSION['reg_optional_fields']=array('phone'=>$phone, 'birthdate'=>$birthdate);
$birthdate=date('Y-m-d',strtotime($birthdate));

//для валидации используются функции из подключенной библиотеки, если проверка не пройдена в сессию записывается сообщение об ошибке
if (check_empty($username))
	$_SESSION['error_username']='Введите имя пользователя';
elseif (check_valid_username($username))
	$_SESSION['error_username']='Недопустимое имя пользователя';
elseif (check_exist($username))
	$_SESSION['error_username']='Пользователь с данным именем уже зарегистрирован';
else{// если все проверки пройдены, то в сессию записывается временно имя пользователя (для отображения в форме) и пустое сообщение об ошибке
	$_SESSION['error_username']='';
	$_SESSION['temp_username']=$username;
}

//аналогичная валидация для пароля и эмейла
if (check_empty($password))
	$_SESSION['error_password']='Введите пароль';
elseif (check_match($password,$confirm_password))
	$_SESSION['error_password']='Введенные пароли не совпадают';
else
	$_SESSION['error_password']='';
	
if (check_empty($email))
	$_SESSION['error_email']='Введите адрес e-mail';
elseif (check_valid_email($email))
	$_SESSION['error_email']='Некорректный адрес e-mail';
elseif (check_exist($email))
	$_SESSION['error_email']='Данный адрес e-mail уже зарегистрирован';
else
	$_SESSION ['error_email']='';

$_SESSION['temp_email']=$email;//временное значение эмейла в сессии для сохранения значения в форме

if ($_SESSION['error_username']||$_SESSION['error_password']||$_SESSION['error_email']){
	redirect();//если сообщения об ошибках не пустые, то редирект обратно на страницу с формой, где будут показаны сообщения об ошибках
}else{//если сообщения об ошибках пустые
	$_SESSION['username']=$username;//в сессию записывается имя пользователя
	$request=$mysqli->prepare("INSERT INTO `users` (`username`,`password`,`email`,`phone`,`birthdate`) VALUES (?,?,?,?,?)");
	$request->bind_param('sssss',$username,$password,$email,$phone,$birthdate);
	$request->execute();//запись в поля БД
	$_SESSION['success']=($mysqli->error=='')?"Вы успешно зарегистрировались как $_SESSION[username]":'Ошибка регистрации. 
			Проверьте корректность всех введенных данных';
	//если объект результата запроса не содержит ошибок, то в сессию пишем сообщение об успехе, если ошибки еть, то пишем сообщение об ошибке
	$mysqli->close();//закрыть соединение с БД
	redirect();//редирект назад
}