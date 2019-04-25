<?php
session_start();
?>

<!DOCTYPE html>
<!-- saved from url=(0059)https://getbootstrap.com/docs/4.3/examples/floating-labels/ -->
<html lang="en" class="gr__getbootstrap_com">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
  <meta name="generator" content="Jekyll v3.8.5">
  <title>Регистрация - WA DOCUMENTOR.</title>
  <!-- Custom styles for this page -->
  <link href="css/sb-admin-2.css" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="./css/floating-labels.css" rel="stylesheet">

  <!--Подключение jquery-->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>

  <style type="text/css">
  
  .invalid-feedback {
   display: block;
   width: 100%;
   margin-top: 0.3rem;
   font-size: 80%;
   color: #e74a3b;
   margin-bottom: 0.3rem;
 }
</style>
  <!--<script>
    $(document).ready(function() {
      //при нажатии на кнопку с submit будет вызываться функция
      $("#submit").click(function() {
        //значение полей по их id
          var first_name = $("#first_name").val ();
          var email =  $("#email").val ();
          var fail = "";

          if (first_name.length < 2) fail = "Имя не меньше двух символов";

          else if (email./^(?:[a-z0-9]+(?:[-_.]?[a-z0-9]+)?@[a-z0-9_.-]+(?:\.?[a-z0-9]+)?\.[a-z]{2,5})$/i) fail = "Некорректный emial";

          if (fail != ""){
            $('#error').html (fail + "<div class='clear'><br></div>");
            $('#error').show ();
            return false;
          } 

      });

    });

  </script>-->

</head>
<?php

require_once('connect.php');

$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

//Ошибки
$emailErr=$firstnameErr=$passErr=$phrazeErr=$lastnameErr"";

if (isset($_POST['submit'])) {

  // Берем данные
    $email = mysqli_real_escape_string($dbc, trim($_POST['email'])); //отдельная проверка 
    $first_name = mysqli_real_escape_string($dbc, trim($_POST['first_name']));
    $last_name = mysqli_real_escape_string($dbc, trim($_POST['last_name']));
    $password1 = mysqli_real_escape_string($dbc, trim($_POST['password1']));
    $password2 = mysqli_real_escape_string($dbc, trim($_POST['password2']));
    $user_pass_phrase = SHA1($_POST['verify']);
    $i = 0;

    if (empty($email)) {
     $emailErr = "Вы не указали email";
   } else if (!preg_match("/^(?:[a-z0-9]+(?:[-_.]?[a-z0-9]+)?@[a-z0-9_.-]+(?:\.?[a-z0-9]+)?\.[a-z]{2,5})$/i", $email)) {
     $emailErr = "email адрес указан неправильно.";
     $email ="";
   } else {
     $i++;
   }

   if (empty($first_name)) {
     $firstnameErr = "Вы не ввели имени";
   } else  if (!preg_match("/^[a-zA-Zа-яА-Я]*$/",$first_name)) {
    //Проверка на символы
    $firstnameErr = "Разрешены только буквы и пробелы";
    $first_name = "";
  } else{
    $i++;
  }

    if (empty($last_name)) {
     $lastnameErr = "Вы не ввели фамилию";
   } else  if (!preg_match("/^[a-zA-Zа-яА-Я]*$/",$last_name)) {
    //Проверка на символы
    $lastnameErr = "Разрешены только буквы и пробелы";
    $last_name = "";
  } else{
    $i++;
  }

//Проверка пароля
  if (empty($password1) || empty($password2)){
    $passErr = "введите пароль дважды";
  }  else if ($password1 !== $password2){
    $passErr = "Пароли не совпадают";
  } else {
   $i++;
 }

  //Проверка на идентификационную фразу
 if (empty($user_pass_phrase)){
  $phrazeErr = "вы не ввели код";
} else if ($_SESSION['pass_phrase'] !== $user_pass_phrase){
  $phrazeErr = "неверный код";
} else {
 $i++;
}  


if ($i==5) {
    // Убедимся, что поьзователя с такими данными не существует
  $query = "SELECT * FROM reg_users WHERE email = '$email'";
  $data = mysqli_query($dbc, $query);

  if (mysqli_num_rows($data) == 0) {
      //Адрес почты уникальные , регистрируем пользователя
    $query = "INSERT INTO reg_users (email,first_name,last_name password) VALUES ('$email','$first_name','$last_name',SHA1('$password1'))";
    mysqli_query($dbc, $query);

      //Получаем имя текущего рабочего каталога.
    $curdir = getcwd();

      //Создаем папку пользователя
    if (mkdir($curdir ."/templates/users_files/" . sha1($email). "/docs", 0777, true)) {
      mkdir($curdir ."/templates/users_files/" . sha1($email). "/docs/acts", 0777, true);
      mkdir($curdir ."/templates/users_files/" . sha1($email). "/docs/contracts", 0777, true);
      mkdir($curdir ."/templates/users_files/" . sha1($email). "/docs/invoices", 0777, true);
      mkdir($curdir ."/templates/users_files/" . sha1($email). "/docs/bills", 0777, true);  
      mkdir($curdir ."/templates/users_files/" . sha1($email). "/docs/custom", 0777, true); 
                //templates
      mkdir($curdir ."/templates/users_files/" . sha1($email). "/templates/acts", 0777, true);
      mkdir($curdir ."/templates/users_files/" . sha1($email). "/templates/contracts", 0777, true);
      mkdir($curdir ."/templates/users_files/" . sha1($email). "/templates/bills", 0777, true);
      mkdir($curdir ."/templates/users_files/" . sha1($email). "/templates/custom", 0777, true);

      echo "Директория успешно создана";
    } else {
      echo "Ошибка создания директории";
    }

    mysqli_close($dbc);
    header("Location: login.php");
    exit();
  }  else {

    // Пользователь с такими email существует
    $emailErr = "Пользователь с такими email существует. Пожалуйста введите другой адрес.";
    $first_name = "";
    $last_name = "";
  }
}
} 

mysqli_close($dbc);
?> 

<body data-gr-c-s-loaded="true" >
  <form class="form-signin" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <div class="text-center mb-4" class="back-back">
      <img class="mb-4" src="./img/pictures/documentor.png" alt="" width="300" height="100" style="position: center;">
      <p style="color: rgb(169, 169, 169); ">Создание и управление вашими документами стало удобнее </p>
      <hr>
    </div>
    <div class="text-center mb-4" class="back-back">
      <h1 class="h3 mb-3 font-weight-normal" >Регистрация</h1>  
      <hr>

      <div class="invalid-feedback">
        <?php echo $emailErr;?>
      </div>
      <div class="form-label-group">
        <input type="email" id="email" name="email" class="form-control" placeholder="Введите email" value="<?php if (!empty($email)) echo $email; ?>" >
        <label for="email">Электронная почта</label>  
      </div>

      <div class="invalid-feedback">
        <?php echo $firstnameErr;?>
      </div>
      <div class="form-label-group">
        <input type="text" id="first_name" name="first_name" class="form-control" placeholder="Имя" value="<?php if (!empty($first_name)) echo $first_name; ?>">
        <label for="first_name">Введите имя</label>
      </div>

      <div class="invalid-feedback">
        <?php echo $lastnameErr;?>
      </div>
      <div class="form-label-group">
        <input type="text" id="last_name" name="last_name" class="form-control" placeholder="Имя" value="<?php if (!empty($last_name)) echo $last_name; ?>">
        <label for="last_name">Введите фамилию</label>
      </div>

      <div class="invalid-feedback">
        <?php echo $passErr;?>
      </div>
      <div class="form-label-group"> 
        <input type="password" id="password1" name="password1" class="form-control" placeholder="Password">
        <label for="password1">Пароль</label>
      </div>

      <div class="form-label-group"> 
        <input type="password" id="password2" name="password2" class="form-control" placeholder="Password">
        <label for="password2">Повторите пароль</label>
      </div>

      <div class="invalid-feedback">
        <?php echo $phrazeErr;?>
      </div>
      <div class="captcha">
       <label for="verify">Анти-бот проверка:</label>
       <input type="text" id="verify" name="verify" placeholder="Введите код с картинки"> 
       <img src="captcha.php" alt="Verification pass-phrase">
       <hr>
     </div>


     <button class="btn btn-lg btn-primary btn-block" type="submit" name="submit">Зарегистрироваться</button><br>

     <div class="text-center mb-4" class="back-back">
      <p style="color: rgb(169, 169, 169); ">Уже зарегистрированы? <a href="login.php">Войти</a> </p>
      <p style="color: rgb(169, 169, 169); ">Регистрируясь вы соглашаетесь с <a href="">Лицензионным соглашением</a> </p>
    </div>

    <a href=" "><p class="mt-5 mb-3 text-muted text-center">Подробнее о проекте</p></a>
    <p class="mt-5 mb-3 text-muted text-center">© 2017-2019</p>


  </div>
</form>


</body>

</html>