<?php
$title='Редактировать';
require './inc/db_connect.php';//коннект к БД
function page(){
	global $mysqli;
	$request=$mysqli->prepare("SELECT * FROM `articles` WHERE id=?");
	$request->bind_param('i',$_GET['article_id']);
	$request->execute();
	$rqst=$request->get_result();
	$request_article=$rqst->fetch_assoc();
	$_SESSION['edit']=array('subject'=>$request_article['subject'],'content'=>$request_article['content'], 'id'=>$_GET['article_id']);
	include (ROOT.'/inc/forms/edit_form.php');
	$request->close;
	$mysqli->close;
}