<?php
$host = 'MySQL-5.5';  
$user = 'root';   
$pass = ''; 
$db_name = 'blog';   

$link = mysqli_connect($host, $user, $pass, $db_name); 

// Проверка соединения
if (!$link) {
    die('Не могу соединиться с БД. Код ошибки: ' . mysqli_connect_errno() . ', ошибка: ' . mysqli_connect_error());
}

mysqli_set_charset($link, "utf8");
?>
