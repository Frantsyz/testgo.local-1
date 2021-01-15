<?php 
//  Подключаємо БД !!
include 'configs/db.php';
include "configs/settings.php";
$page = 'create';

 ?>
<!-- 

!!! надо сделать 2 страницы:
1 - create.php - вводим название темы тестирования и генерим код теста/таблицы в БД; создаём таблицу в БД с именем = сгенерированному коду

2 - add.php - страничка с формой для добавления в только что созданную таблицу вопросов 

СОЗДАНИЕ ТЕСТА

create.php - страница написания заголовка теста (тематика) и генерации кода названия таблицы теста

JS генерит рандомный 6-значный ключ 
На странице отображаем 
- этот ключ и надпись "Скопируйте и сохраните сначала этот ключ к Вашему тесту"
- надпись "Введите тему тестирования" и текстовое поле ввода названия (обязательно для заполнения - проверять)
- кнопка "Создать"

При нажатии на кнопку "Создать"
- в БД создаём НОВУЮ таблицу с именем = 6-значный ключ и полями id, question, answer_1, ..., answer_4, answ_right
- в БД в таблицу tests сохраняем в test_id 6-значный ключ, а в test_name значение test_name из инпута
- переходим на страницу добавления вопросов add.php


add.php - страничка с формой для добавления в только что созданную таблицу вопросов 
Содержит форму с:
- текстовое поле для ввода вопроса
- 4 текстовых поля для ввода вариантов ответов и радиобаттонов перед ними для обозначения правильного варианта ответа
- кнопка "Добавить"

При нажатии на кнопку "Добавить"
- в БД в таблицу с именем = 6-значный ключ отправляем данные из формы по всем столбцам (вопрос, ответы и номер правильного ответа)
- если в таблице записей меньше 10, то обновляем страничку для ввода следующего вопроса
- иначе выводим сообщение "10 вопросов сохранено" и кнопку "Закончить" с выходом на главную страницу.


-->
<?php 
/*if(isset($_POST["go_sms"]) && $_POST["go_sms"] != ""){*/
	if(isset($_POST["name_of_table"]) && $_POST["name_of_table"] != "" && isset($_POST["go_sms"])) 	{
	

		// INSERT INTO---добавляю даннные нового пользователя (to_user_id ---игнорю)

		$random_num = $_POST["name_of_table"];
		$test_name = $_POST["test_name"];

		$sql_table = "CREATE TABLE `$random_num` ( `id` INT NOT NULL AUTO_INCREMENT , `question` TEXT NOT NULL, `images` VARCHAR(255) NOT NULL , `answer_1` VARCHAR(255) NOT NULL , `answer_2` VARCHAR(255) NOT NULL , `answer_3` VARCHAR(255) NOT NULL , `answer_4` VARCHAR(255) NOT NULL , `answ_right` VARCHAR(255) NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB";
		
		/*$sql_table = "CREATE  TABLE `$random_num` ( `id` INT NOT NULL AUTO_INCREMENT , `title` VARCHAR(255) NOT NULL , `images` VARCHAR(255) NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB";*/
		//------------------------------------------------------------------------------

		
		/*mysqli_query($connect,$sql_table);*/

		/*  $_POST["name_of_table"];
			$_POST["test_name"];
		$sql_mes_text = "INSERT INTO `messanges`(`text`, `from_user_id`, `to_user_id`) 
		VALUES ('".$_POST["text"]."', '".$_POST["from_user_id"]."', '".$_POST["to_user_id"]."')";


		mysqli_query($connect,$sql_mes_text);*/

		if(mysqli_query($connect, $sql_table)){
			$sql_tests = "INSERT INTO `tests`(`test_id`, `test_name`) 
				VALUES ('".$random_num."', '".$test_name."')";

			mysqli_query($connect, $sql_tests);

			echo "<h2> Текст внесли</h2>";
			
		}
		else{
			echo "<h2>Произошла ошибка текста</h2>";
			printf("Connect failed: %s\n", mysqli_connect_error());
			printf("Errormessage: %s\n", mysqli_error($connect));
		}
			/*echo "<h2> Текст внесли</h2>";*/
	/*}*/
 }
 ?>
 <!DOCTYPE html>
<html>
<head>
	<title><?php echo $nameSite; ?> Creating test</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<?php

	//  шапка для сайта подключина
	include "parts/head.php";

	?>
<!-- 

!!! надо сделать 2 страницы:
1 - create.php - вводим название темы тестирования и генерим код теста/таблицы в БД; создаём таблицу в БД с именем = сгенерированному коду

2 - add.php - страничка с формой для добавления в только что созданную таблицу вопросов 

-->
	
	<main>
		<script type="text/javascript">
			function getRandom(max){
        		return Math.floor(1 + Math.random() * (max + 1));
    		}


    		function getRnd(){
	    		var testRnd = document.querySelector("#test_rendom");
				    r = Math.floor(Math.random() * (256)),
				    g = Math.floor(Math.random() * (256)),
				    b = Math.floor(Math.random() * (256)),
				    getRandomNumber = 'r' + r.toString(16) + g.toString(16) + 'b'+ b.toString(16);
	    		//$test_id = function getRandom(max);
	    		testRnd.value = getRandomNumber;
    		}
		</script>
		
		
			<h3>Скопируйте и сохраните сначала этот ключ к Вашему тесту: </h3>
				
				<!-- ВРЕМЕННАЯ КНОПКА ЗАПУСКА ГЕНЕРАТОРА (ФУНКЦИИ) РАНДОМНОГО НОМЕРА ТЕСТА (ТАБЛИЦЫ) -->
<!-- ключ надо генерить при помощи JS -->
				<button class="btn" id="show" onclick="getRnd();" >
	              Тицькай сюди
	          	</button>
<!-- ключ надо генерить при помощи JS -->

		<form id="form" method="POST">
				<div id="title_color">Введите тему тестирования</div>
		<?php 
				/*if(isset($_GET["user"])){*/
		?>
				<!-- НОМЕР ТЕСТА (ТАБЛИЦЫ) -----результат-function getRnd()----->
				<input id="test_rendom" name="name_of_table" type="text" value="тут буде номер тесту"> 
				<!------------------ -->
				
				
				<textarea name="test_name"></textarea>
				<button type="submit" name="go_sms">
					<img src="image/send.png">
				</button>
		</form>
		<?php 
		/*}*/
		?>


	</main>

<script src="script.js"></script>
</body>
</html>