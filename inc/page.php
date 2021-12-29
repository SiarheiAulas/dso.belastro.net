<!DOCTYPE html>
<html lang="ru-BY">
<head>
	<title>
		<?php
		echo "Журнал наблюдений: ".$title;
		?>
	</title>
	<meta charset="utf-8">
	<meta name="keywords" content="astronomy, amateur astronomy, deepsky, sketch, DSO, planetary, любительская астрономия, дипскай, глубокий космос, визуальные наблюдения, зарисовки">
	<meta name="description" content="Журнал астрономических наблюдений">
	<meta name="author" content="Serhei@BelastroNetTeam">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatiple" content="ie=edge">
	<link rel="stylesheet" type="text/css" href="css/flex.css?wrup">
	<link rel="shortcut icon" type="image/x-icon" href="https://forum.belastro.net/favicon.ico">
	
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Kurale&display=swap">
</head>
<body>
	<header>
		<div class="banner">Дневник наблюдателя</div>
		<!--
		<nav id="navbar1">
			<a href="index.php" title="На главную" target="_self">Главная</a>
			<a href="index.php?act=about" title="О проекте" target="_self">О проекте</a>
			<a href="index.php?act=feedback" title="Отправить сообщение администратору" target="_self">Обратная связь</a>
			<a href="index.php?act=refer" title="Полезные ссылки" target="_self">Ссылки</a>
			<a href="index.php?act=search" title="Найти на сайте" target="_self">Поиск</a>
		</nav>
		-->
		<div class="authorizedUserNavbar" id="navbar2">
			<div class="container">
            <?php
			//показать разные меню в хедере для авторизованных и неавторизованных пользователей
			if(isset($_SESSION['username']))
				echo '<span class="navLink" id=\"personalCab\"><a href=index.php?act=mycab> Личный кабинет</a></span>
					<span class="navLink" id="exit"><a href=index.php?act=exit>Выйти</a></span>
					<span class="navLink" id="addArticle"><a href=index.php?act=add>Добавить запись</a></span>';
			else
				echo '<span class="navLink" id="register"><a href=index.php?act=registration>Зарегистрироваться</a></span>
					<span class="navLink" id="login"><a href=index.php?act=authorisation>Войти</a></span>';
			?>
            </div>
		</div>
			<?php
			if ($_SESSION['username']=='Serhei')
				echo '<div class="adminNavbar" id="navbar3"><span class=\"navLink\" id=\"admin\"><a href=index.php?act=admin> Админка</a></span></div>';
			//если это я, показать админку
			?>
	</header>
	<main>
		<aside>
			<div class="kostyl_bg">
                <div class="container">
                <div class="contain-1"><a href="index.php" title="На главную" target="_self">Главная</a></div>
                <div class="contain-1"><a href="index.php?act=about" title="О проекте" target="_self">О проекте</a></div>
                <div class="contain-1"><a href="index.php?act=feedback" title="Отправить сообщение администратору" target="_self">Обратная связь</a></div>
                <div class="contain-1"><a href="index.php?act=refer" title="Полезные ссылки" target="_self">Ссылки</a></div>
                <div class="contain-1"><a href="index.php?act=search" title="Найти на сайте" target="_self">Поиск</a></div>
                </div>
			</div>
		</aside>
		<section class="page" id="mainPage">
			<?php
			page();//основной блок контента
			?>
		</section>
	</main>
	<footer>
		<p>&copy; BelAstroNet Team <?=date('Y')?></p>
		<p> К проекту присоединилось <?=$user_count['COUNT(*)']?> ЛА</p>
	</footer>
</body>
</html>