 <?php 

 require_once('connect.php');

 $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)  or die ('Соединение с базой  данных не   удалось');;

//запрос на выбор всех шаблонов конкретного пользователя

"SELECT * FROM UNION SELECT * FROM "


 ?>     

 <div class="container-fluid">


  <form id="requiz" method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
    <!-- Заглавие -->

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
      <h1 class="h3 mb-0 text-gray-800">Шаблоны</h1>

    </div>

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
      <span>Добавляйте ваши собственные шаблоны и используйте их для формиравания документов</span>
    </div>

    <div class="row">

      <div class="col-xl-12 col-lg-12">

        <div class="container">

          <div class="card-deck mb-3 text-center">
     
           <div class="card mb-4 shadow-sm">
            <div class="card-header">
              <h4 class="my-0 font-weight-normal">Договоры</h4>
            </div>
            <div class="card-body">
              <div class="text-center">
                <a href="main_temp_contract.php">
                  <img class="img-fluid px-3 px-sm-4 mt-3 mb-4" style="width: 25rem;" src="img/pictures/template_contracts.png" alt="">
                </a>
              </div>
              <ul class="list-unstyled mt-3 mb-4">
                <li>+Добавить шаблон</li>
                <li>2 GB of storage</li>
                <li>Email support</li>
                <li>Help center access</li>
              </ul>
              <button type="button" class="btn btn-lg btn-block btn-outline-primary">+ Добавить шаблон</button>
            </div>
          </div>

          <div class="card mb-4 shadow-sm">
            <div class="card-header">
              <h4 class="my-0 font-weight-normal">Акты</h4>
            </div>
            <div class="card-body">
              <div class="text-center">
                <a href="main_temp_act.php">
                  <img class="img-fluid px-3 px-sm-4 mt-3 mb-4" style="width: 25rem;" src="img/pictures/template_acts.png" alt="">
                </a>
              </div>
              <ul class="list-unstyled mt-3 mb-4">
                <li>20 users included</li>
                <li>10 GB of storage</li>
                <li>Priority email support</li>
                <li>Help center access</li>
              </ul>
              <button type="button" class="btn btn-lg btn-block btn-outline-primary">+ Добавить шаблон</button>
            </div>
          </div>

          <div class="card mb-4 shadow-sm">
            <div class="card-header">
              <h4 class="my-0 font-weight-normal">Счета</h4>
            </div>
            <div class="card-body">
              <div class="text-center">
                <a href="main_temp_bill.php">
                  <img class="img-fluid px-3 px-sm-4 mt-3 mb-4" style="width: 25rem;" src="img/pictures/template_bills.png" alt="">
                </a>
              </div>
              <ul class="list-unstyled mt-3 mb-4">
                <li>30 users included</li>
                <li>15 GB of storage</li>
                <li>Phone and email support</li>
                <li>Help center access</li>
              </ul>

              <button type="button" class="btn btn-lg btn-block btn-outline-primary">+ Добавить шаблон</button>
            </div>
          </div>
        </div>

      </div>

    </div>

  </div>

</form>

</div>
     