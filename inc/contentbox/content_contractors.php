 <?php 

 require_once('connect.php');

 $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)  or die ('Соединение с базой  данных не   удалось');

 ?>       

 <div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Контрагенты</h1>
  </div>

  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <span>Добавляйте и просматривайте контрагентов в удобном виде таблиц.</span>
  </div>

  <div class="row"> 

    <div class="col-lg-8 mb-4">
      <!-- DataTales -->
      <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Список контрагентов</h6>
        </div>
        <div class="card-body">
          <?php 
          //Выбрать всех контрагентов
          $query = "SELECT * FROM contractors WHERE user_id = ". $_SESSION['id'] ."";
          $perform = mysqli_query($dbc,$query) or die ("Ошибка запроса к базе данных");

          $row = mysqli_fetch_array($perform);

            //если контрагенты найдены
          if (!empty($row)): ?>

            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
              <?php 
              require_once('connect.php');

            //Удаление контрагентов , выделенных чекбоксами
              if (isset($_POST['delete'])) {
                foreach ($_POST['todelete'] as $delete_contr) {
                //Каскадное удаление со всех таблиц добавить
                  $delete = "DELETE FROM contractors WHERE id = $delete_contr";
                  mysqli_query($dbc,$delete) or die ('Ошибка запроса к базе данных');
                }

                echo "Контрагент(ты) удален(ны) <br>";
              }
              ?>

              <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>чекбокс</th>
                      <th>Наименование контрагента</th>
                      <th>Дата создания</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <th>чекбокс</th>
                      <th>Наименование контрагента</th>
                      <th>Дата создания</th>
                    </tr>
                  </tfoot>
                  <tbody>
                    <?php 
                //Выводим контрагентов в таблицу
                    while ($row = mysqli_fetch_array($perform)) {
                     echo '<tr>';
                     echo '<td>' .'<input type="checkbox" value="'. $row['contractor_id'] . '" name="todelete[]">' . '</td>';
                     echo '<td>' . '<a href="#">' . $row['contractor_name'] . '</a>' . '</td>';
                     echo '<td>' . $row['date_of_creation'] . '</td>';
                     echo '</tr>';
                   }
                   ?> 
                 </tbody>
               </table>
             </div>

            <div class="card-body">
              <a href="#" data-toggle="modal" data-target="#new_contrctor" class="btn btn-success btn-icon-split">
                <span class="icon text-white-50">
                  <i class="fas fa-check"></i>
                </span>
                <span class="text">Добавить контрагента</span>
              </a>

             

              <a href="#" class="btn btn-danger btn-icon-split">
                <span class="icon text-white-50">
                  <i class="fas fa-trash"></i>
                </span>
                <span class="text">Удалить выбранных контрагентов</span>
              </a>

            </div>

          </form> 
          <!---Контрагенты не найдены-->
          <?php else: ?>

            <div class="text-center">
              <a href="#" data-target="#new_contrctor" data-toggle="modal">
                <img class="img-fluid px-3 px-sm-4 mt-3 mb-4" style="width: 25rem;" src="img/pictures/Contractor.png" alt="">
              </a>
            </div>
            <?php 
          endif; 
          mysqli_close($dbc);
          ?>

        </div>
      </div>

    </div>

    <div class="col-lg-4 mb-4">

      <!-- Данные контрагента -->
      <div id="contr_info" class="card shadow mb-4">
        <div class="card">
          <svg  class="bd-placeholder-img card-img-top" width="100%" height="225" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false" role="img" aria-label="Placeholder: Thumbnail"><title>Лого контрагента</title><rect fill="#55595c" width="100%" height="100%"></rect></svg>
          <div class="card-body">
            <h5 class="card-title">Исполнитель:</h5>
            <p class="card-text">Выберите контрагента и его расчетный счет.</p>
          </div>
          <ul class="list-group list-group-flush">
            <li class="list-group-item">Наименование контрагента:{НазваниеКонтр}</li>
            <li class="list-group-item">ИНН:{ИННКонтр}</li>
            <li class="list-group-item">ОГРН:{ОГРНКонтр}</li>
            <li class="list-group-item">Адрес:{АдресКонтр}</li>
            <li class="list-group-item">Р/с:{РасчетныйСчетКонтр}</li>
            <li class="list-group-item">Банк:{НаименованиеБанкаКонтр}</li>
            <li class="list-group-item">БИК: {БИКБанкаКонтр}</li>
            <li class="list-group-item">Комментарий счета: {Комментарий счетаКонтр}</li>
            <li class="list-group-item">{ФИОКонтрДляПодписи}</li>
          </ul>
          <div class="card-body">
            <a href="#" class="btn btn-info">Изменить данные</a>
            <a href="#" class="btn btn-danger">Удалить контрагента</a>
          </div>
        </div>
      </div>

    </div>

    <!--Подключение модального окна добавление нового контрагента-->
    <?php include_once('./inc/modal/add_contractor_modal.php'); ?>

  </div>
</div>

<script>
//взять элемент

    var name = $('#contr_title').text();


    $(document).ready(function(){
    
      $('#contr_title').click(function(){
        $.ajax({
          url: "contr_info.php",
          cache: false,
          success: function(html){
            $("#content").html(html);
          }
        });
      });
      
    });
</script>