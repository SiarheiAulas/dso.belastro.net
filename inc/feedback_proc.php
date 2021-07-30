<?php
session_start();//старт сессии
require_once 'lib.php';//подключить файл с библиотекой функций
require_once 'PHPMailer.php';
require_once 'Exception.php';
require_once 'SMTP.php';

/*если в сессии установлено имя пользователя(пользователь авторизован),
 то используется его имя пользователя, если нет, то имя пользователя берется из формы (пост)*/
$username=(isset($_SESSION['username']))?$_SESSION['username']:htmlspecialchars(trim($_POST["username"]));

//передает данные из формы отдельным переменным и удаляет пробелы и теги
$email=htmlspecialchars(trim($_POST["email"]));
$subject=htmlspecialchars(trim($_POST["subject"]));
$message=htmlspecialchars(trim($_POST["message"]));

$_SESSION['feedback']=array('name'=>$username, 'email'=>$email, 'subject'=>$subject, 'message'=>$message);
//чтобы текст сохранялся в форме при обновлении страницы

$_SESSION["err_empty_username"]=(empty($username))?'Введите ваше имя':'';//проверяет каждое поле на пустоту и записывает сообение об ошибке в сессию
$_SESSION["err_empty_email"]=(empty($email))?'Введите ваш email':'';
$_SESSION["err_empty_subject"]=(empty($subject))?'Введите тему сообщения':'';

if(empty($message)){//если сообщение пустое 
	$_SESSION["err_message"]='Введите ваше сообщение';//сообщение об ошибке
	redirect();//редирект на страницу откуда вызван скрипт
} elseif(strlen($message)<=20){//если длинна сообщения меньше 20 знаков в сессию записывается сообщение об ошибке
	$_SESSION["err_message"]='Сообщение слишком короткое. Мин. 20 символов';
	redirect();//редирект на страницу откуда вызван скрипт
} else{ //если все ок
	$_SESSION["err_message"]=''; //сообщение об ошибке пустое
}
		
	//Настройки отправки почты через PHPMailer
	$mail = new PHPMailer\PHPMailer\PHPMailer();
	try {
		$mail->isSMTP();   
		$mail->CharSet = "UTF-8";
		$mail->SMTPAuth   = true;
		//$mail->SMTPDebug = 2;
		$mail->Debugoutput = function($str, $level) {$GLOBALS['status'][] = $str;};
	
		// Настройки вашей почты
		$mail->Host='smtp.mail.ru'; // SMTP сервера вашей почты
		$mail->Username='avlassergey@list.ru'; // Логин на почте
		$mail->Password='24107ac'; // Пароль на почте
		$mail->SMTPSecure='ssl';
		$mail->Port= 465;
		$mail->setFrom("$email","$username"); // Адрес самой почты и имя отправителя

		// Получатель письма
		$mail->addAddress('siarhei.aulas@gmail.com');  
	
		// Отправка сообщения
		$mail->isHTML(true);
		$mail->Subject=$subject;
		$mail->Body=$message;    
		
		// Проверяем отравленность сообщения
		if ($mail->send()&&$_SESSION["err_empty_username"]==''&&$_SESSION["err_empty_email"]==''&&$_SESSION["err_empty_subject"]==''&&$_SESSION["err_message"]==''&&check_valid_email($email)!=true&&check_valid_username($username)!=true){
			$result = "success";
			// если все проверки пройдены
			$_SESSION["email_success"]="$username, Ваше сообщение успешно отправлено";// сообщение об ошибке в сессию
			redirect();//редирект на страницу откуда вызван скрипт
		} else {
			$result = "error";
			redirect();//редирект на страницу откуда вызван скрипт
			}
	} catch (Exception $e) {
		$result="error";
		$status="Сообщение не было отправлено. Причина ошибки: {$mail->ErrorInfo}";
		$_SESSION["email_success"]=$status;
	}
