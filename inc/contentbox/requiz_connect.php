<?php 
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

	$pers_query = "SELECT * FROM reg_users,users_organization WHERE reg_users.id = users_organization.user_id AND reg_users.id = ". $_SESSION['id'] ."";
	$pers_result = mysqli_query($dbc,$pers_query);

	$fetch_arr = mysqli_fetch_array($pers_result);

?>

<div class="row">

<form method="POST" action="<?php htmlspecialchars($_SERVER['PHP_SELF']);?>">
	<div class="col-lg-6 mb-4">

		<!-- Реквизиты пользователя -->
		<div class="card shadow mb-4">
			<div class="card-header py-3">
				<h6 class="m-0 font-weight-bold text-primary">Ваши реквизиты</h6>
			</div>
			<div class="card-body">
				<div class="col-xl-12 col-lg-7">
					<p class="mb-0">Для формирования документа вам необходимо выбрать расчетный счет и сверить реквизиты</p>      
					<br>
				</div>
				<h5 class="card-title">Заказчик:</h5>
				<p class="card-text">ФИО:<?php echo $fetch_arr['last_name'] . ' ' . $fetch_arr['first_name'] . ' ' .  $fetch_arr['patronymic']  ?></p>
				<p class="card-text">ИНН: <?php echo $fetch_arr['ip_inn']?></p>
				<p class="card-text">ОКПО: <?php echo $fetch_arr['okpo']?></p>
				<p class="card-text">ОГРНИП: <?php echo $fetch_arr['ogrnip']?></p>
				<p class="card-text">Адрес: <?php echo $fetch_arr['address']?></p>
				<p class="card-text">Р/с: {РасчетныйСчет}</p>
				<p class="card-text">БИК: {БИК}</p>
				<p class="card-text">Банк:{НаименованиеБанкаИГородБанка}</p>
				<p class="card-text">Комментарий счета: {Комментарий счетаСчет}</p>
				<p class="card-text">ФИО для подписи: <?php echo $fetch_arr['fio_sign']?></p>
				<hr>
				<div class="col-md-12 mb-3" id="here">
					<label for="">Расчетный счет</label>
					<input type="search" name="search" id="search" class="form-control">
					<div class="invalid-feedback">
						Ошибка
					</div>
				</div>
				<a href="#" class="btn btn-warning" data-target="#pers_requiz" data-toggle="modal">Изменить реквизиты</a>
			</div>
		</div>

	</div>
</form>

	<div class="col-lg-6 mb-4">

		<!-- Реквизиты контрагента -->
		<div class="card shadow mb-4">
			<div class="card-header py-3">
				<h6 class="m-0 font-weight-bold text-primary">Реквизиты контрагента</h6>
			</div>
			<div class="card-body">

				<div class="col-md-12 mb-3" id="here">
					<label for="">Введите название контрагента</label>
					<input type="search" name="search" id="search" class="form-control">
					<div class="invalid-feedback">
						Ошибка
					</div>
				</div>
				<div class="col-md-12 mb-3" id="here">
					<label for="">Расчетный счет контрагента</label>
					<input type="search" name="search" id="search" class="form-control">
					<div class="invalid-feedback">
						Ошибка
					</div>
				</div>
				<hr>
				<h5 class="card-title">Исполнитель:</h5>
				<p class="card-text">Наименование контрагента:{НазваниеКонтр}</p>
				<p class="card-text">ИНН:{ИННКонтр}</p>
				<p class="card-text">ОГРН:{ОГРНКонтр}</p>
				<p class="card-text">Адрес:{АдресКонтр}</p>
				<p class="card-text">Р/с:{РасчетныйСчетКонтр}</p>
				<p class="card-text">Банк:{НаименованиеБанкаКонтр}</p>
				<p class="card-text">БИК: {БИКБанкаКонтр}</p>
				<p class="card-text">Комментарий счета: {Комментарий счетаКонтр}</p>
				<p class="card-text">{ФИОКонтрДляПодписи}</p>
				<a href="#" class="btn btn-warning" data-target="#client_requiz" data-toggle="modal">Изменить реквизиты</a>
			</div>
		</div>

	</div>
</div>