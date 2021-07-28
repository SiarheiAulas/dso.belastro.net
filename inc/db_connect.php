<?php
$mysqli=new mysqli('localhost','admin','admin','observerlog');//создает объект класса mysqli
if ($mysqli->connect_error){ //если не удалось подключиться
	echo $mysqli->connect_errno;
	echo $mysqli->connect_error;
} //если не коннектится выводит ошибку и ее код
$mysqli->query("SET NAMES 'utf8'"); //устанавливет кодировку