<?php
session_start();
define('ROOT',dirname(__FILE__).'/');
require_once (ROOT.'/inc/lib.php');
error_reporting(E_ALL);
$_SESSION['pageadress']=$_SERVER['REQUEST_URI'];
$act=$_GET['act']?:'main';

include (ROOT.'/inc/db_connect.php');
$userCount=$mysqli->query('SELECT COUNT(*) FROM users');
$user_count=$userCount->fetch_assoc();
$userCount->close();
$mysqli->close();

include (ROOT."/inc/pages/$act.php");
include (ROOT.'/inc/page.php');

//var_dump($userCount);
//var_dump($_SESSION);
