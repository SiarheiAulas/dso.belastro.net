<?php
session_start();
define('ROOT',dirname(__FILE__).'/');
require_once (ROOT.'/inc/lib.php');
error_reporting(E_ALL);
$_SESSION['pageadress']=$_SERVER['REQUEST_URI'];
$act=$_GET['act']?:'main';
include (ROOT."/inc/pages/$act.php");
include (ROOT.'/inc/page.php');

//var_dump($_COOKIE);
//var_dump($_SESSION);
