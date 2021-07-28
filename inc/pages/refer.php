<?php
$title='ссылки';//для отображения в заголовке страницы
//функция формирует основную часть страницы, которая отображается в блоке main шаблона страницы
function page(){
	echo '<h1>Ссылки</h1>';
	include_once (ROOT.'/inc/db_connect.php');//подключает к бд

	$count_refer=$mysqli->query("SELECT COUNT(*)FROM `refer`");//возвращает объект
	$count=$count_refer->fetch_assoc();//преобразует его в ассоциативный массив
	$count['COUNT(*)'];//количество ссылок в базе
	
	$limit_per_page=10; // задает количество ссылок на странице
	$num_pages=ceil($count['COUNT(*)']/$limit_per_page);//считает количество страниц (округление в сторону большего целого)
	$page=intval($_GET['page']);// номер текущей страницы из url
	
?>
<div class="pagenumber">
<?php

	for ($i=1; $i<=$num_pages; $i++){
		echo "<span class=\"pageN\"><a href='index.php?act=refer&amp;page=$i'>$i</a></span>";
		}
	// выводит строчку с нужным количеством страниц
?>
</div>
<?php

	if (empty($page)||$page<=0)
		$page=1;
	if($page>$num_pages)
		$page=$num_pages;// проверка корректности значения номера страницы и исправление в случае некорректных значений
	
	$start=($page-1)*$limit_per_page;//чтобы передать в качестве аргумента в LIMIT запроса
	$result=$mysqli->query("SELECT * FROM `refer` ORDER BY `id` ASC LIMIT $start, $limit_per_page");//запрос всех ссылок из бд (выводит объект)
	while ($row=$result->fetch_assoc()){
		//преобразует из объекта в ассоциативный массив
		echo "<article><div class='linkUrl'>$row[id] <a href='$row[url]'>$row[name]</a></div>
			<div class='article_content'>$row[description]</div><hr>";
		}
		//выводит построчно значения из бд
		
	//кнопки навигации вперед-назад по страницам
	$next=$page+1;
	$previous=$page-1;//значения кнопок (ссылок)
	echo "<div class='page_navigation'><span class='previous'><a href='index.php?act=refer&amp;page=$previous'><<<Предыдущая</a></span>
		<span class='next'><a href='index.php?act=refer&amp;page=$next'>Следующая>>></a></span></div>";
	//выводит строчку (блок) со ссылками "предыдущая" и "следующая"
	$result->close();//очистить объект
	$mysqli->close();//закрыть соединение с БД
}//функция будет вызываться в page.php
