<?php
$title='админка';
function page(){
	echo '<div class="phpmyadmin"><a href="http://192.168.244.128:85/phpMyAdmin/index.php">PHP My Admin</a></div>';
	phpinfo();
}