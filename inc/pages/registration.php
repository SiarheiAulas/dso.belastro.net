<?php
$title='регистрация';//для отображения в заголовке страницы
function page(){
	include(ROOT.'/inc/forms/registration_form.php');
}
//вызов функции подключает форму регистрации в блок main шаблона страницы