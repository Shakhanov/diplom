 <?php 

 require_once('connect.php');

 $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

 ?>       

 <div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Мои документы</h1>
  </div>

  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <span>Добавляйте ,просматривайте и удаляйте в случае необходимости документы в удобном виде таблиц.</span>
  </div>

  <div class="row"> 

    <div class="col-lg-12 mb-4">
      <!-- DataTales -->
      <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Список документов</h6>
        </div>
        <div class="card-body">
          <?php 
          $check_docs = "SELECT * FROM user_docs WHERE user_id = ". $_SESSION['id'] ."";

          $result = mysqli_query($dbc,$check_docs);

          //Выбор всех документов пользователя
          $fetch_docs = mysqli_fetch_array($result); 

          if (!empty($row)): ?>
            <div class="table-responsive">
              <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th>чекбокс</th>
                    <th>Наименование документа</th>
                    <th>Дата создания</th>
                    <th>Сам доокумент с ссылкой</th>
                    <th>Тип документа</th>
                  </tr>
                </thead>
                <tfoot>
                  <tr>
                    <th>чекбокс</th>
                    <th>Наименование контрагента</th>
                    <th>Дата создания</th>
                    <th>Сам доокумент с ссылкой</th>
                    <th>Тип документа</th>
                  </tr>
                </tfoot>
                <tbody>
                  <?php 
                //Выводим документы пользователя в таблицу
                  while ($fetch_docs) {
                   echo '<tr>';
                   echo '<td>' .'<input type="checkbox" value="'. $fetch_docs['contractor_id'] . '" name="todelete[]">' . '</td>';
                   echo '<td>' . '<a href="#">' . $fetch_docs['doc_name'] . '</a>' . '</td>';
                   echo '<td>' . $fetch_docs['date_of_creation'] . '</td>';
                   echo '</tr>';
                 }
                 ?> 
               </tbody>
             </table>
           </div>
           <div class="row">
             <a href="#" class="btn btn-danger btn-icon-split">
              <span class="icon text-white-50">
                <i class="fas fa-trash"></i>
              </span>
              <span class="text" >Удалить выбранные документы</span>
            </a>
          </div> 

          <?php else: ?>
            <div class="text-center">
              <img class="img-fluid px-3 px-sm-4 mt-3 mb-4" style="width: 70em;" src="img/pictures/document_no.png" alt="Документы не найдены">
            </div>

            <div class="row">
              <div class="col-lg-4 mb-4">
                <div class="card bg-primary text-white shadow">
                  <div class="card-body">
                    Договор
                    <div class="text-white-50 small">#4e73df</div>
                  </div>
                </div>
              </div>
              <div class="col-lg-4 mb-4">
                <div class="card bg-success text-white shadow">
                  <div class="card-body">
                    Акт
                    <div class="text-white-50 small">#1cc88a</div>
                  </div>
                </div>
              </div>
              <div class="col-lg-4 mb-4">
                <div class="card bg-warning text-white shadow">
                  <div class="card-body">
                    Счет
                    <div class="text-white-50 small">#f6c23e</div>
                  </div>
                </div>
              </div>
            </div> 
            <?php 
          endif; 
          mysqli_close($dbc);
          ?>
        </div>
      </div>

    </div>

  </div>
</div>