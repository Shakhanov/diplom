<?php 
  #Добавить проверки на валидность в js   
  #Изменение пароля

$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$emailErr=$passErr=$currentpassErr=$newpassErr="";

if (isset($_POST['change_pass'])) {
      //Шифруем введеный пользователем пароль, для последующей проверки

 $current_pass = mysqli_real_escape_string($dbc, trim(sha1($_POST['current_pass'])));
 $new_pass = mysqli_real_escape_string($dbc, trim(sha1($_POST['new_pass'])));

 if (empty($current_pass)) {
  $currentpassErr = "Вы не ввели текущий пароль";
} 

if (empty($new_pass)) {
  $newpassErr ="Вы не ввели новый пароль";
}

if (!empty($current_pass) && !empty($new_pass)) {

  $select = "SELECT password FROM reg_users WHERE id= ". $_SESSION['id'] ." ";
  $data = mysqli_query($dbc, $select);
  $result = mysqli_fetch_array($data);

  if ($current_pass == $result['password']) {

    $change = "UPDATE reg_users SET password = '$new_pass' WHERE id= ". $_SESSION['id'] ." ";
    $data1 = mysqli_query($dbc, $change);

    echo "Пароли совпадают";

  } else {
    $newpassErr = "Пароли не совпадают";
  }
}
}

    #Добавить проверки полей
    #Изменение email

if (isset($_POST['change_email'])) {
  $email_new = $_POST['email_new'];
  $current_pass = mysqli_real_escape_string($dbc, trim(sha1($_POST['current_pass'])));

  if (empty($email_new)) {
    $emailErr = "Вы не ввели e-mail";
  } 

  if (empty($current_pass)) {
    $passErr = "Вы не ввели текущий пароль";
  }

  if (!empty($email_new) && !empty($current_pass)) {
   $select = "SELECT password FROM reg_users WHERE id= ". $_SESSION['id'] ." ";
   $data = mysqli_query($dbc, $select);
   $result = mysqli_fetch_array($data);

   if ($current_pass == $result['password']) {
     $change = "UPDATE reg_users SET email = '$email_new' WHERE id= ". $_SESSION['id'] ." ";
     $data1 = mysqli_query($dbc, $change);

             #изменить email сессии
     $_SESSION['email'] = $email_new;

     echo "Email изменен";
   } else{
     $passErr = "Email не изменен";
   }
 }
}

    #удаление аккаунта
    /*
    if (isset($_POST['accept_del'])) {
      $select = "";
    }
    */

    mysqli_close($dbc);
    ?>

    <div class="container-fluid">
      <!-- Заглавие -->
      <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Настройки аккаунта</h1>
      </div>

      <div>
       <span>Ваш электронный адрес входа <?php echo $_SESSION['email'] ?></span>
       <a data-toggle="modal" href="#" data-target="#emailmodal">Изменить почту</a>
       <!--Создаем модальное окно изменения почты-->
     </div>

     <div>
       <h3>Пароль для входа</h3>
       <a data-toggle="modal" href="#" data-target="#passmodal">Изменить пароль</a>
       <!--Создаем модальное окно изменения пароля-->
     </div>

     <div>
       <h3>Удаление аккаунта</h3>
       <a data-toggle="modal" href="#" data-target="#deletemodal">Удалить аккаунт ×</a>
       <!--Создаем модальное окно подтверждения удаления аккаунта-->
     </div>


     <!--Модальное окно изменения email-->   
     <div class="modal" tabindex="-1" role="dialog" id="emailmodal" style="display: none;" aria-hidden="true">
       <div class="modal-dialog" role="document">
         <div class="modal-content">
           <div class="modal-header">
             <h5 class="modal-title">Изменение email</h5>
             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
           <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>"><!--action="checkmodal.php"-->
            <div class="form-group">
              <label for="email_new" class="col-form-label">Новый e-mail:</label>
              <input type="email" class="form-control" id="email_new" name="email_new">
            </div>
            <div class="invalid-feedback">
              <?php echo $emailErr?>
            </div>
            <div class="form-group">
              <label for="current_pass" class="col-form-label">Пароль:</label>
              <input type="text" class="form-control" id="current_pass" name="current_pass" placeholder="Текущий пароль">
            </div>
            <div class="invalid-feedback">
              <?php echo $passErr?>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary" name="change_email" >Изменить почту</button>
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>


  <!--Модальное окно изменения пароля-->   
  <div class="modal" tabindex="-1" role="dialog" id="passmodal" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
       <div class="modal-header">
        <h5 class="modal-title">Изменить пароль</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>"> <!--action="checkmodal.php"-->
          <div class="form-group">
            <label for="current_pass" class="col-form-label">Текущий пароль:</label>
            <input type="text" class="form-control" id="current_pass" name="current_pass">
          </div>
          <div class="invalid-feedback">
            <?php echo $currentpassErr?>
          </div>

          <div class="form-group">
            <label for="new_pass" class="col-form-label">Новый пароль:</label>
            <input type="text" class="form-control" id="new_pass" name="new_pass">
          </div>
          <div class="invalid-feedback">
            <?php echo $newpassErr?>
          </div>

          <div class="modal-footer">
            <button type="submit" class="btn btn-primary" name="change_pass">Изменить пароль</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>


<!--Модальное окно удаления аккаунта -->   
<div class="modal" tabindex="-1" role="dialog" id="deletemodal" style="display: none;" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
     <div class="modal-header">
      <h5 class="modal-title">Удалить аккаунт</h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
      </button>
    </div>
    <div class="modal-body">
      <p>Ваш аккаунт будет удален. Вместе с ним будут удалены:<br>
        -Документы<br>
        -Таблицы<br>
        -Контрагенты<br>
        -Реквизиты<br>
      </p>
      <div>
        <p>Сохраните данные , <a href="">архивировав их</a></p>
      </div>
      <div>
        <input type="checkbox" name="accept_del" id="accept_del" onchange="document.getElementById('submit').disabled = !this.checked;">
        <label for="accept_del">Я согласен удалить аккаунт</label>
      </div>
    </div>
    <div class="modal-footer">
      <input type="submit" class="btn btn-primary" disabled="disabled" name="submit" id="submit" value="Удалить безвозвратно">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
    </div>
  </div>
</div>
</div>

</div>