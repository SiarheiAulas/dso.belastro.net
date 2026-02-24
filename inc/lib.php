<?php
//функция проверяет переменную на пустую строку
function check_empty($x){
	$err=(empty($x))?true:false;//если пусто true, иначе false
	return $err;//выводит true-false
}

//проверяет переменную на наличие такого значения в БД (в полях имя пользователя или эмэйл)
function check_exist($x){
	global $mysqli;
	$request=$mysqli->prepare("SELECT * FROM users WHERE username=? OR email=?");
	$request->bind_param("ss",$x,$x);
	$request->execute();
	$result=$request->get_result();
	$num=$result->num_rows;
	$result->free();
	$request->close();
	
	$err=($num!=0)?true:false;
	return $err;// возвращает true-false
}

//проверяет имя пользователя на соответствие регулярному выражению
function check_valid_username($username){
	$err=(!preg_match ("/^[a-zA-Z-' ]*$/",$username))?true:false;
	return $err;
	//вернет true-false
}

//проверяет эмейл на соответствие фильтру
function check_valid_email($email){
	$err=(!filter_var($email,FILTER_VALIDATE_EMAIL))?true:false;
	return $err;
	//вернет true-false
}

//проверяет совпадение паролей в двух полях формы
function check_match($password,$confirm_password){
	$err=($password!=$confirm_password)?true:false;
	return $err;
	//вернет true-false
}

//редирект по адресу страницы с которой вызывается скрипт
function redirect(){
	header('location:'.$_SESSION['pageadress']);
	exit();
}

//проверяет при авторизации есть ли пользователь с таким именем и паролем в БД
function check_exist_auth($x,$y){
	global $mysqli;
	$request=$mysqli->prepare("SELECT * FROM users WHERE username=? AND password=?");
	$request->bind_param("ss",$x,$y);
	$request->execute();
	$result=$request->get_result();
	$num=$result->num_rows;
	$result->free();
	$request->close();
	
	$err=($num!=0)?true:false;
	return $err;
}