<?php
//Сохранение сессии и куки
//Начало сессии
session_start();

//Если есть куки или сессия создана (и то и другое), по отправляем обратно на пользовательскую страницу
//index.php
if (isset($_SESSION['id']) || (isset($_COOKIE['id']) && isset($_COOKIE['email']))) {
  header("Location: index.php");
  echo "Сессия или куки создано";
}  else {

  //Очищаем сообщение об ошибке
  //$error_msg = "";

  if (isset($_POST['submit'])) {

      // Соединяемся с БД
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

      //Очистка от лишних символов
      $user_email = mysqli_real_escape_string($dbc, trim($_POST['email'])); //будет своя проверка

      $user_password = mysqli_real_escape_string($dbc, trim($_POST['password']));

      if (!empty($user_email) && !empty($user_password)) {
        //Смотрим существуют ли пользователи с данными именами и фамилями
        $query= "SELECT id,email from  reg_users WHERE email = '$user_email' and password = SHA1('$user_password')";
        $data = mysqli_query($dbc, $query);

        //если запись найдена то сохраем данные в сессии и перенаправляем пользователя на его страницу
        if (mysqli_num_rows($data) == 1) {
            //mysqli_fetch_array — Выбирает одну строку из результирующего набора и помещает ее в ассоциативный массив, обычный массив или в оба
            //$row = mysqli_fetch_array($data);
            //$_SESSION['user_id'] = $row['user_id'];
            //$_SESSION['email'] = $row['email'];
            //setcookie('user_id', $row['user_id'], time() + (60 * 60 * 24 * 30));    // expires in 30 days
            //setcookie('email', $row['email'], time() + (60 * 60 * 24 * 30));  // expires in 30 days
            //$home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/index.php'; //Переадресация с помощью абсолютного url index.php
            //header("Location: index.php");


            // Если не был отмечен чекбокс , то сохраняем только сессию
             //mysqli_fetch_array — Выбирает одну строку из результирующего набора и помещает ее в ассоциативный массив, обычный массив или в оба
          if ($_POST['remember-me'] == ''){
                //(не отмечен)
            $row = mysqli_fetch_array($data);
            $_SESSION['id'] = $row['id'];
            $_SESSION['email'] = $row['email'];
            header("Location: index.php");

          } else {
                //(отмечен)
            $row = mysqli_fetch_array($data);
            $_SESSION['id'] = $row['id'];
            $_SESSION['email'] = $row['email'];
                setcookie('id', $row['id'], time() + (60 * 60 * 24 * 30));    // expires in 30 days
                setcookie('email', $row['email'], time() + (60 * 60 * 24 * 30));  // expires in 30 days
                //$home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/index.php'; 
                //Переадресация с помощью    абсолютного url index.php
                header("Location: index.php");
              }


            } else {
              echo "Вы ввели неправильный адрес эл.почты или пароль"; 
            }

          }  else {
           echo "Вы не ввели данных";
         } 
       } 
     }     
     ?>