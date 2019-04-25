<?php
  // // Если пользователь вошел , удаляем сессию и перенаправляем пользователя обратно на логин
  session_start();
  if (isset($_SESSION['id'])) {
    // Удаление сессии путем очистки массива
    $_SESSION = array();

    //Удаляем куки
    if (isset($_COOKIE[session_name()])) {
      setcookie(session_name(), '', time() -(60 * 60 * 24 * 30));
    }

    // Закрываем сессию
    session_destroy();
  }

  //Удаляем пользовательские куки , у id и email, ставим время истечения сессии
  setcookie('id', '', time() - (60 * 60 * 24 * 30));
  setcookie('email', '', time() - (60 * 60 * 24 * 30));

  //Перенаправляем на пользовательскую страницу
  header("Location: login.php");

?>
