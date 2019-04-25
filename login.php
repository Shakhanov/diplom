<!DOCTYPE html>
<!-- saved from url=(0059)https://getbootstrap.com/docs/4.3/examples/floating-labels/ -->
<html lang="en" class="gr__getbootstrap_com">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
  <meta name="generator" content="Jekyll v3.8.5">
  <title>Авторизация - WA DOCUMENTOR.</title>

  <!-- Custom styles for this page -->
  <link href="css/sb-admin-2.css" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="./css/floating-labels.css" rel="stylesheet">

  <script src="./js/jquery-3.3.1.js"></script>
</head>
<?php  
require_once('connect.php');

require('savesession.php');
?>


<body data-gr-c-s-loaded="true" >
  <form class="form-signin" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <div class="text-center mb-4" class="back-back">
      <img class="mb-4" src="./img/pictures/documentor.png" alt="" width="300" height="100">
      <p style="color: rgb(169, 169, 169); ">Создание и управление вашими документами стало удобнее </p>
      <hr> 
    </div>

    <div class="text-center mb-4" class="back-back">
      <h1 class="h3 mb-3 font-weight-normal" >Вход в приложение</h1>  
      <div class="form-label-group">
        <input type="email" id="email" class="form-control" placeholder="Введите email" required="" autofocus="" name="email" value="<?php if (!empty($user_email)) echo $user_email; ?>">
        <label for="email">Электронная почта</label>
      </div>
      
      <div style="text-align: right; margin: 7px;">
        <a href="">Забыли пароль?</a>
      </div>

      
      <div class="form-label-group"> 
        <input type="password" id="password" class="form-control" placeholder="Введите пароль" required="" name="password">
        <label for="password">Пароль</label>
      </div>
    </div>

    <div class="checkbox mb-3">
      <label> 
        <input type="checkbox" name="remember-me"> Запомнить меня
      </label>
      
    </div>
    <button name="submit" id="submit" class="btn btn-lg btn-primary btn-block" type="submit ">Войти</button>
    <a href="registration.php"><p class="mt-5 mb-3 text-center">Зарегистрироваться</p></a>
    <a href=" "><p class="mt-5 mb-3 text-muted text-center">Подробнее о проекте</p></a>
    <p class="mt-5 mb-3 text-muted text-center">© 2017-2019</p>
    
  </form>

</body>

</html>