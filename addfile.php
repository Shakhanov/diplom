<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title>WA Documentor . Добавление стандартных файлов</title>
  <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
  <h2>WA Documentor . Добавление стандартных файлов</h2>
<!--move_uploaded_file() дает возможность перемещать файлы на сервере и играет ключеую роль в обработке загружаемых на сервер файлов-->
<?php

//require_once добавляет совместно используемый код в другие сценарии
require_once('connect.php');//включить однажды, если файл не будет найден, будет сообщ об ошибке.

//isset - необходима дляпроверки существуют ли данные
  if (isset($_POST['submit'])) {
  // Соединение с базой данных
  //данные констант для подключения обявленны в файле connectvars.php
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    //trim -удаляет (только) пробелы в начале и в конце данных этих форм
    //встроенная функция mysqli_real_escape_string($dbc,) удаляет все остальные символы, которые могут повлиять на SQL запрос (они могут быть использлованны при вводе данных , просто не будут изменять содержимое запросов). $dbc - первый аргумент ф-ции , соединяющий с БД , так как это функция БД.
    //Извлечение данных из суперглобального массива $_POST
    $contract = mysqli_real_escape_string($dbc,trim($_FILES['contract']['name']));
    $contract_size = $_FILES['contract']['size'];
    $contract_type = $_FILES['contract']['type'];

     //FILES - суперглобальная переменная для сохранения информации о загруженных на сервер файлов
    
         if (!empty($contract)) {
        if (($contract_size > 0) && ($contract_size <= WA_MAXFILESIZE)){
            if ($_FILES['contract']['error'] == 0) { //Проверка на ошибки


              //Перемещение файла в постоянный каталог для файлов изображений
              $target = WA_CONTR_UPLOADPATH . $contract;  //time - добавляет к имени файла текущего времени 
              if (move_uploaded_file($_FILES['contract']['tmp_name'], $target)) { 
                //Аргументы move_uploaded_file(Исходное место расположения файла изображения включ. имя временного каталога и имя самого файла .;;Место  назначения файла включаюшее имя постояного каталога и имя самого файла)
                
                 // Запись данных в базу данных
                 //мы убрали колонки id и approved т.к. так более безопасней и значения этих полей задаются автоматически
                 $query = "INSERT INTO common_templates (date,contracts) VALUES (NOW(),'$contract')";
                 mysqli_query($dbc, $query);

                 // Вывод пользователю подтверждения в получении данных
                  echo '<p>Спасибо за то, что добавили договор!</p>';
                 // Очистка полей ввода данных формы
                  $contract = "";

                  mysqli_close($dbc);
              } 
            }  else {
                 echo '<p class="error">Извините, возникла ошибка при загрузке файла.</p>';
              }
          } else {
              echo '<p class="error"> Размер файла НЕ должен превышать ' . (WA_MAXFILESIZE / 1024) . 'Кб.</p>';
            }
          //Попытка удалить временный файл изображения, подтвержд.рейт. пользователя
          //@uplink - файл удаляется с сервера . "@"" подавление сообщения об ошибке , если загрузка файла не удалась
            @unlink($_FILES['contract']['tmp_file']);
      } else {
         echo '<p class="error"> Введите, пожалуйста, всю информацию для добавления вашего рейтинга.</p>';
        }
  }
?>

  <hr>
  <form enctype="multipart/form-data" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>"> 
    <!--entype трибут говорящий при загрузке изображений использовать спец.кодирование данных-->
    <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo WA_MAXFILESIZE; ?>"> <!--Устанавливает макс размер файла загрузки-->
    <hr>
    <label for="contract">Добавить договор:</label>
    <input type="file" id="contract" name="contract">
    <input type="submit" value="Добавить" name="submit">
  </form>
</body> 
</html>


