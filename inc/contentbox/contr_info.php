<?php 
//Выбор данных выбранного контрагента



//Запрос к БД , где идет поиск имени контрагента

"SELECT * FROM reg_users,users_organization WHERE ";



?>

<?php

//если выбранн контрагент , выводим его ajax'ом 
if () {?>
<form>
<div id="contr_info" class="card shadow mb-4">
        <div class="card">
          <svg  class="bd-placeholder-img card-img-top" width="100%" height="225" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false" role="img" aria-label="Placeholder: Thumbnail"><title>Placeholder</title><rect fill="#55595c" width="100%" height="100%"></rect><text fill="#eceeef" dy=".3em" x="45%" y="45%">Thumbnail</text></svg>
          <div class="card-body">
            <h5 class="card-title">Исполнитель:</h5>
            <p class="card-text">Выберите контрагента и его расчетный счет.</p>
          </div>
          <ul class="list-group list-group-flush">
            <li class="list-group-item">Наименование контрагента:</li>
            <li class="list-group-item">ИНН:</li>
            <li class="list-group-item">ОГРН:</li>
            <li class="list-group-item">Адрес:</li>
            <li class="list-group-item">Р/с:</li>
            <li class="list-group-item">Банк:</li>
            <li class="list-group-item">БИК:</li>
            <li class="list-group-item">Комментарий счета: <?= $['comment'] ?></li>
            <li class="list-group-item">ФИОКонтрДляПодписи:</li>
          </ul>
          <div class="card-body">
            <a href="#" class="btn btn-info">Изменить данные</a>
            <a href="#" class="btn btn-danger">Удалить контрагента</a>
          </div>
        </div>
      </div>
</form>
<?php}?>