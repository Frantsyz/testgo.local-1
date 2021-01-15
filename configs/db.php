<?php

//данные для подключения к базе данных
$server = "localhost";
$username = "root";
$password = "";
$dbname = "testgo";

//подклюкение к базе данных  chat
$connect = mysqli_connect($server, $username, $password, $dbname);

//фича для того что бы все символы отображались
mysqli_set_charset($connect, 'utf8');

?>