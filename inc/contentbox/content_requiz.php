<?php 

require_once('connect.php');

$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$lastnameErr=$firstnameErr=$patronymicErr=$innErr=$okpoErr=$ogrnipErr=$dateErr=$okvedErr=$fiosignErr=$addressErr=$clientphoneErr=$clientemailErr=$websiteErr="";


if (isset($_POST['submit_requiz'])) {

	$admit = true;

//Проверка ФИО
	//Фамилия
	$last_name = mysqli_real_escape_string($dbc, trim($_POST['last_name']));
	if (empty($last_name)) {
		$lastnameErr = "Обязательное поле";
		$admit = false;
	}
	else if (!preg_match("/^[a-zа-яё\s]+$/iu",$last_name)) {
		$lastnameErr = "Разрешены только буквы и пробелы";
		$admit = false;
	} 
	else if ((strlen($last_name) > 100)) {
		$lastnameErr = "Не более 100 символов";
		$admit = false;
	}

	//Имя
	$first_name = mysqli_real_escape_string($dbc, trim($_POST['first_name']));
	if (empty($first_name)) {
		$lastnameErr = "Обязательное поле";
		$admit = false;
	} 
	else if (!preg_match("/^[a-zа-яё\s]+$/iu",$first_name)) {
		$firstnameErr = "Разрешены только буквы и пробелы";
		$admit = false;
	}
	else if ((strlen($first_name) > 100)) {
		$first_name = "Не более 100 символов";
		$admit = false;
	}

	//Отчество
	$patronymic = mysqli_real_escape_string($dbc, trim($_POST['patronymic']));
	if (!empty($patronymic)) {
		if (!preg_match("/^[a-zа-яё\s]+$/iu",$patronymic)) {
			$patronymicErr = "Разрешены только буквы и пробелы";
			$admit = false;
		} else if ((strlen($patronymicErr) > 100)) {
			$patronymicErr = "Не более 100 символов";
			$admit = false;
		}
	}


	//Проверка ИНН ИП
	$ip_inn = mysqli_real_escape_string($dbc, trim($_POST['ip_inn']));
	//вырезаем теги
	$ip_inn=strip_tags($ip_inn);
    //конвертируем специальные символы в мнемоники HTML
	$ip_inn=htmlspecialchars($ip_inn,ENT_QUOTES);
	/* на некоторых серверах
         * автоматически добавляются
         * обратные слеши к кавычкам, вырезаем их */
	$ip_inn=stripslashes($ip_inn);

	function validateInn($inn, &$innErr = null) {
		$result = false;
		$inn = (string) $inn;
		if (!$inn) {
		} else if (preg_match('/[^0-9]/', $inn)) {
			$innErr = 'ИНН может состоять только из цифр';
			$admit = false;
		} else if (!in_array($inn_length = strlen($inn), [12])) {
			$innErr = 'ИНН может состоять только из 12 цифр';
			$admit = false;
		} else {
			$check_digit = function($inn, $coefficients) {
				$n = 0;
				foreach ($coefficients as $i => $k) {
					$n += $k * (int) $inn{$i};
				}
				return $n % 11 % 10;
			};
			switch ($inn_length) {
				case 12:
				$n11 = $check_digit($inn, [7, 2, 4, 10, 3, 5, 9, 4, 6, 8]);
				$n12 = $check_digit($inn, [3, 7, 2, 4, 10, 3, 5, 9, 4, 6, 8]);
				if (($n11 === (int) $inn{10}) && ($n12 === (int) $inn{11})) {
					$result = true;
				}
				break;
			}
			if (!$result) {
				$innErr = 'Неправильное контрольное число';
				$admit = false;
			}
		}
		return $result;
	}

	validateInn($ip_inn,$innErr);


	//ОКПО
	$okpo = mysqli_real_escape_string($dbc, trim($_POST['okpo']));

	if (!empty($okpo)) {
		if (preg_match('/[^0-9]/', $okpo)) {
			$okpoErr = "Значение должно содержать только цифры";
			$admit = false;
		} else if ((strlen($okpo) < 8) || (strlen($okpo) > 10)) {
			$okpoErr = "Длина должна быть 8 или 10 символов";
			$admit = false;
		}
	}
	
	//ОРГНИП
	$ogrnip = mysqli_real_escape_string($dbc, trim($_POST['ogrnip']));

	function validateOgrnip($ogrnip, &$ogrnipErr = null) {
		$result = false;
		$ogrnip = (string) $ogrnip;
		if (!$ogrnip) {
		} else if (preg_match('/[^0-9]/', $ogrnip)) {
			$ogrnipErr = 'ОГРНИП может состоять только из цифр';
			$admit = false;
		} else if (strlen($ogrnip) !== 15) {
			$ogrnipErr = 'ОГРНИП может состоять только из 15 цифр';
			$admit = false;
		} else {
			$n15 = (int) substr(bcsub(substr($ogrnip, 0, -1), bcmul(bcdiv(substr($ogrnip, 0, -1), '13', 0), '13')), -1);
			if ($n15 === (int) $ogrnip{14}) {
				$result = true;
			} else {
				$ogrnipErr = 'Неправильное контрольное число';
				$admit = false;
				$ogrnip = "";
			}
		}
		return $result;
	}


	validateOgrnip($ogrnip,$ogrnipErr);

	//Дата регистрации
	$date_reg = $_POST['date_reg'];


	//ОКВЭД
	$okved = mysqli_real_escape_string($dbc, trim($_POST['okved']));
	$okved = str_replace('.','',$okved);

	if (!empty($okved)) {
		if (preg_match('/[^0-9]/', $okved)) {
			$okvedErr = "Значение должно содержать только цифры";
			$admit = false;
		} else if ((strlen($okved) < 4) || (strlen($okved) > 6)) {
			$okvedErr = "Длина должна быть 4 или 6 символов";
			$admit = false;
		} 
	}
	


	#ФИО для подписи
	$fio_sign = $_POST['fio_sign'];
	//вырезаем теги
	$fio_sign=strip_tags($fio_sign);
    //конвертируем специальные символы в мнемоники HTML
	$fio_sign=htmlspecialchars($fio_sign,ENT_QUOTES);
	/* на некоторых серверах
         * автоматически добавляются
         * обратные слеши к кавычкам, вырезаем их */
	$fio_sign=stripslashes($fio_sign);

	if (strlen($fio_sign) > 100) {
		$fiosignErr = "Недопустимое количество символов";
		$admit = false;
	}


	//Фактический адрес
	$address = $_POST['address'];
	//вырезаем теги
	$address=strip_tags($address);
    //конвертируем специальные символы в мнемоники HTML
	$address=htmlspecialchars($address,ENT_QUOTES);
	/* на некоторых серверах
         * автоматически добавляются
         * обратные слеши к кавычкам, вырезаем их */
	$address=stripslashes($address);
	if (strlen($address) > 100) {
		$addressErr = "Недопустимое количество символов";
		$admit = false;
	}


	//Телефон для клиентов
	$client_phone = $_POST['client_phone'];
	//вырезаем теги
	$client_phone=strip_tags($client_phone);
    //конвертируем специальные символы в мнемоники HTML
	$client_phone=htmlspecialchars($client_phone,ENT_QUOTES);
	/* на некоторых серверах
         * автоматически добавляются
         * обратные слеши к кавычкам, вырезаем их */
	$client_phone=stripslashes($client_phone);
	if (strlen($client_phone) > 100) {
		$clientphoneErr = "Недопустимое количество символов";
		$admit = false;
	}


	//Эл. почта для клиентов
	$client_email = $_POST['client_email'];
	//вырезаем теги
	$client_email=strip_tags($client_email);
    //конвертируем специальные символы в мнемоники HTML
	$client_email=htmlspecialchars($client_email,ENT_QUOTES);
	/* на некоторых серверах
         * автоматически добавляются
         * обратные слеши к кавычкам, вырезаем их */
	$client_email=stripslashes($client_email);
	if (strlen($client_email) > 100) {
		$clientemailErr = "Недопустимое количество символов";
		$admit = false;
	}


	//Сайт для ознакомления
	$website = $_POST['website'];
	//вырезаем теги
	$website=strip_tags($website);
    //конвертируем специальные символы в мнемоники HTML
	$website=htmlspecialchars($website,ENT_QUOTES);
	/* на некоторых серверах
         * автоматически добавляются
         * обратные слеши к кавычкам, вырезаем их */
	$website=stripslashes($website);	

	if (!empty($website)) {
		$website = $_POST["website"];
		if (strlen($website) > 100) {
			$websiteErr = "Недопустимое количество символов";
			$admit = false;
		}
   	 	// Проверим, является ли синтаксис URL адреса допустимым (это регулярное выражение также позволяет прочерк в URL)
		else if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$website)) {
			$websiteErr = "Неверный URL адрес";
			$admit = false;
		}
	}
	

	    /*Запрос на вставку данных в input'ы
	и вывод банков
	добавить банки users_banks (просто рядом к другим таблицам)
	Если не возникло ошибок отправляем данные
	Добавить банки*/
	if ($admit == true) {
		//update таблицы , вставка значений
		$insert_data = "UPDATE reg_users, users_organization SET 
		users_organization.ip_inn ='$ip_inn',
		users_organization.okpo ='$okpo',
		users_organization.ogrnip ='$ogrnip',
		users_organization.date_reg ='$date_reg',
		users_organization.okved ='$okved',
		users_organization.fio_sign ='$fio_sign',
		users_organization.address ='$address',
		users_organization.client_phone ='$client_phone',
		users_organization.client_email ='$client_email',
		users_organization.website ='$website',
		reg_users.first_name ='$first_name',
		reg_users.last_name ='$last_name',
		reg_users.patronymic ='$patronymic'
		WHERE reg_users.id = users_organization.user_id AND user_id = ". $_SESSION['id'] ."";

		//Выполняем запрос
		mysqli_query($dbc,$insert_data) or die("Неправильный запрос");

		//Добавить подключение
	}

} 

//Запрос на выбор всех данных контрагента без р/c
$show = "SELECT * FROM reg_users,users_organization WHERE reg_users.id = users_organization.user_id AND reg_users.id = ". $_SESSION['id'] ."";
$show_res = mysqli_query($dbc, $show) or trigger_error(mysql_error()." in ". $show);
$ammount_res = mysqli_num_rows($show_res);

//если в таблице не существует user_id , то добавляем его
if ($ammount_res == 0) {
	$paste =  "INSERT INTO users_organization (user_id) VALUES (". $_SESSION['id'] .") ";
	mysqli_query($dbc,$paste);
} else {
	//если существует , то берем массив данных
	$arr = mysqli_fetch_array($show_res);
}


//mysqli_close($dbc);

?>

<div class="container-fluid">


	<form id="requiz" method="POST" action="<?php htmlspecialchars($_SERVER['PHP_SELF']);?>">
		<!-- Заглавие -->

		<div class="d-sm-flex align-items-center justify-content-between mb-4">
			<h1 class="h3 mb-0 text-gray-800">Реквизиты</h1>

		</div>

		<div class="d-sm-flex align-items-center justify-content-between mb-4">
			<span>Заполните данные полей и сохраните их для дальнейшей работы с документами</span>
		</div>

		<div class="row">

			<!--Отчетность-->
			<div class="col-xl-12 col-lg-12">

				<div class="card shadow mb-4">
					<div class="card-header py-3">
						<h6 class="m-0 font-weight-bold text-primary">Отчетность</h6>
					</div>
					<div class="card-body">
						<!--Поля  ФИО-->
						<div class="row">
							<div class="col-md-4 mb-3">
								<label for="last_name">Фамилия</label>
								<input type="text" name="last_name" id="last_name" class="form-control " value="<?php echo $arr['last_name'];?>" maxlength="100">
								<div class="invalid-feedback">
									<?php echo $lastnameErr ?>
								</div>
							</div>

							<div class="col-md-4 mb-3">
								<label for="first_name">Имя</label>
								<input type="text" name="first_name" id="first_name" class="form-control" value="<?php echo $arr['first_name'];?>" maxlength="100">
								<div class="invalid-feedback">
									<?php echo $firstnameErr ?>
								</div>
							</div>
							
							<div class="col-md-4 mb-3">
								<label for="patronymic">Отчество</label>
								<input type="text" name="patronymic" id="patronymic" class="form-control" value="<?php echo $arr['patronymic'];?>" maxlength="100">
								<div class="invalid-feedback">
									<?php echo $patronymicErr ?>
								</div>
							</div>
						</div>

						<!--Поле ИНН-->
						<div class="row">
							<div class="col-md-4 mb-3">
								<label for="ip_inn">ИНН ИП</label>
								<input type="text" name="ip_inn" id="ip_inn" class="mask-inn-individual form-control" value="<?php echo $arr['ip_inn'];?>" maxlength="12" placeholder="12 цифр">
								<div class="invalid-feedback">
									<?php echo $innErr ?>
								</div>
							</div>
						</div>

						<!--Поле ОКПО-->
						<div class="row">
							<div class="col-md-4 mb-3">
								<label for="okpo">ОКПО</label>
								<input type="text" name="okpo" id="okpo" class="mask-okpo form-control" maxlength="10" value="<?php echo $arr['okpo'];?>" placeholder="10 или 8 цифр">
								<div class="invalid-feedback">
									<?php echo $okpoErr ?>
								</div>
							</div>
						</div>

						<!--Поле ОРГНИП-->
						<div class="row">
							<div class="col-md-4 mb-3">
								<label for="ogrnip">ОГРНИП</label>
								<input type="text" name="ogrnip" id="ogrnip" class="mask-ogrnip form-control" maxlength="15" value="<?php echo $arr['ogrnip'];?>" placeholder="номер из 15 цифр">
								<div class="invalid-feedback">
									<?php echo $ogrnipErr ?>
								</div>
							</div>
						</div>

						<!--Поле дата регистрации ИП-->
						<div class="row">
							<div class="col-md-4 mb-3">
								<label for="date_reg">Дата регистрации ИП</label>
								<input type="date" name="date_reg" id="date_reg" class="form-control" value="<?php echo $arr['date_reg'];?>">
								<div class="invalid-feedback">
									<?php echo $firstnameErr ?>
								</div>
							</div>
						</div>

						<!--Поле код ОКВЭД-->
						<div class="row">
							<div class="col-md-4 mb-3">
								<label for="okved">ОКВЭД</label>
								<input type="text" name="okved" id="okved" class="mask-okved form-control" maxlength="6" value="<?php echo $arr['okved'];?>" placeholder="XX.XX.XX">
								<div class="invalid-feedback">
									<?php echo $okvedErr ?>
								</div>
							</div>
						</div>

					</div>
				</div>

				<!--Расчетные счета-->
				<div class="card shadow mb-4">
					<div class="card-header py-3">
						<h6 class="m-0 font-weight-bold text-primary">Расчетные счета</h6>
					</div>

					<div class="card-body">
						<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#bank_account_pers">
							Добавить расчётный счет
						</button>

						<?php 

						$rs_info = "SELECT * FROM users_banks WHERE user_id = ". $_SESSION['id'] ."";
						$query = mysqli_query($dbc,$rs_info);
						$fetch_rs = mysqli_fetch_array($query);

						$rows_rs = mysqli_num_rows($query);

						for ($i=0; $i < $rows_rs ; $i++) { ?>
							<div id="rs">    
								<div class="row">
									<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
								</div>

								<div class="row">
									RS:<?= $fetch_rs['rs'];?>
								</div>

								<div class="row">
									Статус счета:<?= $fetch_rs['state'];?>
								</div>

								<div class="row">
									Наименование банка:<?= $fetch_rs['bank'];?>
								</div>

								<div class="row">
									БИК:<?= $fetch_rs['bik'];?>
								</div>

								<div class="row">
									Комментарий:<?= $fetch_rs['comment'];?>
								</div>

							</div>

						<?php } ?>

					</div>
				</div>

				<!--Документы и их поля-->
				<div class="card shadow mb-4">
					<div class="card-header py-3">
						<h6 class="m-0 font-weight-bold text-primary">Документы</h6>
					</div>
					<div class="card-body">
						<div class="col-md-8 mb-3">
							<label for="fio_sign">ФИО для подписи</label>
							<input type="text" name="fio_sign" id="fio_sign" placeholder="" class="form-control" value="<?php echo $arr['fio_sign'];?>" maxlength="100">
							<div class="invalid-feedback">
								<?php echo $fiosignErr ?>

							</div>
						</div>
						<div>
							Тут будет документ в который можно добавить логотипы , подписи и прочее,
							которые в последующем будут вставлятся в документ
						</div>
					</div>
				</div>

				<div class="card shadow mb-4">
					<div class="card-header py-3">
						<h6 class="m-0 font-weight-bold text-primary">Контактные данные</h6>
					</div>
					<div class="card-body">
						<p>Контактные данные</p>
						<p class="mb-0">Укажите свои контактные данные.Они могут не совпадать с реквизитами для отчетности, так как попадут в документы и реквизитку.</p>

						<div class="form-group">
							<label for="address">Фактический адрес</label>
							<textarea name="address" id="address" class="form-control" rows="3" maxlength="200"><?php echo $arr['address'];?></textarea>
							<div class="invalid-feedback">
								<?php echo $addressErr ?>
							</div>
						</div>

						<div class="form-group">
							<label for="client_phone">Телефон для клиентов</label>
							<textarea name="client_phone" id="client_phone" class="form-control" rows="3" maxlength="200"><?php echo $arr['client_phone'];?></textarea>
							<div class="invalid-feedback">
								<?php echo $clientphoneErr ?>
							</div>
						</div>

						<div class="form-group">
							<label for="client_email">Эл. почта для клиентов</label>
							<textarea name="client_email" id="client_email" class="form-control" rows="3" maxlength="200"><?php echo $arr['client_email'];?></textarea>
							<div class="invalid-feedback">
								<?php echo $clientemailErr ?>
							</div>
						</div>

						<div class="col-md-8 mb-3">
							<label for="website">Сайт для ознакомления</label>
							<input type="text" name="website" id="website" placeholder="" class="form-control" value="<?php echo $arr['website'];?>" maxlength="200">
							<div class="invalid-feedback">
								<?php echo $websiteErr ?>

							</div>
						</div>
					</div>
				</div>

				<div class="card shadow mb-4">
					<div class="card-body">
						<input type="submit" name="submit_requiz" class="btn btn-success" value="Сохранить">
						<input type="button" onclick="document.getElementById('requiz').reset()" class="btn btn-secondary" value="Очистить реквизиты">
					</div>
				</div>

			</div>
		</div>

	</form>


	<!--Подключение модальных окон-->
	<?php include_once('./inc/modal/rs_modal_pers.php'); ?>



	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
	<script type="text/javascript" src="/js/jquery.maskedinput.min.js"></script>


	<script>
		$('.mask-inn-individual').mask('999999999999');		
		$('.mask-okpo').mask('99999999?99');
		$('.mask-ogrnip').mask('999999999999999');
		$('.mask-okved').mask('99.99.?99',{placeholder: "XX.XX.XX"});
	</script>

</div>