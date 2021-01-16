<?php 
//  Подключаємо БД !!
include 'configs/db.php';
include "configs/settings.php";
$page = 'add';

		session_start();
		// имя таблицы - рандомной таблицы
		$randoms = $_SESSION["name_of_table"];
    	/*echo "<pre>";
		print_r($_SESSION["name_of_table"]);
		echo "</pre>";*/

// Пути загрузки файлов

$path = 'img/';
$tmp_path = 'ting/';
// создаю фальш переменную что-бы правильно отображать путь к файлу в БД 
/*$path_new = str_replace('../../../i','i',$path);*/

// Массив допустимых значений типа файла
$types = array('image/gif', 'image/png', 'image/jpeg');
// Максимальный размер файла
$size = 1024000;


 ?>
 <?php 

/*$sql_add_prod = "SELECT * FROM products";
                                        	
$res_add_prod = $conn->query($sql_add_prod);

$add_prod = 0;

$count_prod = mysqli_num_rows($res_add_prod);

while($add_prod < $count_prod){*/


    if(isset($_POST["question"]) && isset($_POST["answer_1"]) && isset($_POST["answer_2"]) && isset($_POST["answer_3"]) && isset($_POST["answer_4"]) && isset($_POST["answ_right"]) && $_POST["question"]!="" && $_POST["answer_1"]!="" && $_POST["answer_2"]!="" && $_POST["answer_4"]!="" && $_POST["answ_right"]!=""){
/*    if(isset($_POST["question"]) && isset($_POST["images"]) && isset($_POST["answ_right"])  && isset($_FILES['picture'])&& $_POST["question"]!="" && $_POST["answer_1"]!="" && $_POST["answer_2"]!="" && $_POST["answer_4"]!="" && $_POST["answ_right"]!=""&& $_FILES['picture']!=""){*/
    
    /*var_dump($_POST["title"]);*/
  

    // INSERT INTO---добавляю даннные 
    $sql_push = "INSERT INTO $randoms (question, images, answer_1, answer_2, answer_3, answer_4, answ_right) VALUES ('".$_POST["question"]."', '".$path. $_FILES['picture']['name']."', '".$_POST["answer_1"]."', '".$_POST["answer_2"]."', '".$_POST["answer_3"]."', '".$_POST["answer_4"]."','".$_POST["answ_right"]."' )";
        /*$sql_push = "INSERT INTO $randoms (question, images, answ_right) VALUES ('".$_POST["question"]."', '".$path_new . $_FILES['picture']['name']."','".$_POST["answ_right"]."' )";*/
        // кусок кода загадка $conn->query($sql_push)
        if (mysqli_query($connect,$sql_push) === TRUE) {
      echo "New record created successfully";

    } else {
      echo "Error: " . $sql_push . "<br>" ;
    }

    mysqli_close($connect);


}
 
/* if(isset($_FILES['picture']) && $_FILES['picture']['type']!=""){
*/
// Обработка запроса---Что-то пошло не так.
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    // Проверяем тип файла
    if (!in_array($_FILES['picture']['type'], $types))
        /*die('<p>Запрещённый тип файла. <a href="?">Попробовать другой файл?</a></p>');*/
        die( header("Location: /create.php"));

    // Проверяем размер файла
    if ($_FILES['picture']['size'] > $size)
        die('<p>Слишком большой размер файла. <a href="?">Попробовать другой файл?</a></p>');

    // Функция изменения размера ---resize
    // Изменяет размер изображения в зависимости от type:
    //  type = 1 - эскиз
    //  type = 2 - большое изображение
    //  rotate - поворот на количество градусов (желательно использовать значение 90, 180, 270)
    //  quality - качество изображения (по умолчанию 75%)
    function resize($file, $type = 1, $rotate = null, $quality = null){

        global $tmp_path;

        // Ограничение по ширине в пикселях
        //type = 1 - эскиз
        $max_thumb_size = 300;
        //  type = 2 - большое изображение
        $max_size = 600;
    
        // Качество изображения по умолчанию
        if ($quality == null)
            $quality = 75;

        // Cоздаём исходное изображение на основе исходного файла jpeg
        if ($file['type'] == 'image/jpeg')
            $source = imagecreatefromjpeg($file['tmp_name']);
        elseif ($file['type'] == 'image/png')
            $source = imagecreatefrompng($file['tmp_name']);
        elseif ($file['type'] == 'image/gif')
            $source = imagecreatefromgif($file['tmp_name']);
        else
            return false;
            
        // Поворачиваем изображение
        if ($rotate != null)
            $src = imagerotate($source, $rotate, 0);
        else
            $src = $source;

        // Определяем ширину и высоту изображения
        $w_src = imagesx($src); 
        $h_src = imagesy($src);

        // В зависимости от типа (эскиз или большое изображение) устанавливаем ограничение по ширине.
        if ($type == 1)
            $w = $max_thumb_size;
        elseif ($type == 2)
            $w = $max_size;

        // Если ширина больше заданной
        if ($w_src > $w)
        {
            // Вычисление пропорций
            $ratio = $w_src/$w;
            $w_dest = round($w_src/$ratio);
            $h_dest = round($h_src/$ratio);

            // Создаём пустую картинку
            $dest = imagecreatetruecolor($w_dest, $h_dest);
            
            // Копируем старое изображение в новое с изменением параметров
            imagecopyresampled($dest, $src, 0, 0, 0, 0, $w_dest, $h_dest, $w_src, $h_src);

            // Вывод картинки и очистка памяти
            imagejpeg($dest, $tmp_path . $file['name'], $quality);
            imagedestroy($dest);
            imagedestroy($src);

            return $file['name'];
        }
        else
        {
            // Вывод картинки и очистка памяти
            imagejpeg($src, $tmp_path . $file['name'], $quality);
            imagedestroy($src);

            return $file['name'];
        }
    }
//---------------End ---function resize--------------------
    
    $name = resize($_FILES['picture'], $_POST['file_type'], $_POST['file_rotate']);

    // Загрузка файла и вывод сообщения
       /* if (isset($_POST["images"])){
            copy($tmp_path . $name, $path . $name);
            if (!in_array($_FILES['picture']['type'], $types)){
                die(' unlink($tmp_path . $name);');
            }
        }*/
    
    // Загрузка файла и вывод сообщения
        /*if (isset($_POST["images"])){*/
            if (!@copy($tmp_path . $name, $path . $name))
                echo '<p>Что-то пошло не так.</p>';
            else
                /*echo '<p>Загрузка прошла удачно <a href="' . $path . $_FILES['picture']['name'] . '">Посмотреть</a>.</p>';*/
                echo '<p>Загрузка прошла удачно.</p>';
                /*die( header("Location: /create.php"));*/
        /*}*/
    // Удаляем временный файл
    unlink($tmp_path . $name);

    }
/*}*/

                                        	
 ?>

<!DOCTYPE html>
<html>
<head>
	<title><?php echo $nameSite;?>ADD test</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<?php

	//  шапка для сайта подключина
	include "parts/head.php";
	 /*echo "<h2>Есть Продукт - $randoms</h2>";*/
    /*var_dump($randoms);*/
    echo ($randoms);
	?>
<!-- 

!!! надо сделать 2 страницы:
1 - create.php - вводим название темы тестирования и генерим код теста/таблицы в БД; создаём таблицу в БД с именем = сгенерированному коду

2 - add.php - страничка с формой для добавления в только что созданную таблицу вопросов 

-->
	<main>
		<h2>Введите вопрос с вариантами ответов</h2>
		
		<form id="form" method="POST" enctype="multipart/form-data">
			<div>Введите текст вопроса</div>
			<textarea type="textarea" name="question" required="" placeholder="вопрос"></textarea>
			<!-- <br> -->
			<div>Загрузите Фото (если необходимо)</div>
			<div>				
		<input id="picture" type="file" value="Load the foto" name="picture">
			</div>
			 <div class="pic">
                <label for="picture">Тип загрузки</label>
                <br>
                <select name="file_type">
                    <option value="1">Ескиз</option>
                    <option value="2">Большое фото</option>
                </select>
            </div>
            <br>
            <div class="pic">
                <label for="file_rotate">Повернуть на 90, 180, 270 грд.</label>
                <br>
                <input type="text" name="file_rotate">
                <br>
                
            </div>


			<div>Введите варианты ответов и отметьте правильный</div>
			<div>
				<input type="radio" name="answ_right" value="1" required>
				<input type="text" name="answer_1" required="" placeholder="ответ 1">
			</div>
			<div>
				<input type="radio" name="answ_right" value="2" required>
				<input type="text" name="answer_2" required="" placeholder="ответ 2">
			</div>
			<div>
				<input type="radio" name="answ_right" value="3" required>
				<input type="text" name="answer_3" required="" placeholder="ответ 3">
			</div>
			<div>
				<input type="radio" name="answ_right" value="4" required>
				<input type="text" name="answer_4" required="" placeholder="ответ 4">
			</div>
			
			<input type="submit" name="" value="Сохранить">
			<!-- <button type="submit" src="index.php">Сохранить</button> -->
		</form>

	</main>

<script src="script.js"></script>
</body>
</html>