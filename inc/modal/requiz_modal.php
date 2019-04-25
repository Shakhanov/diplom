<?php 

require_once('connect.php');

$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$lastnameErr=$firstnameErr=$patronymicErr=$innErr=$okpoErr=$ogrnipErr=$dateErr=$fiosignErr="";


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
			}
		}
		return $result;
	}


	validateOgrnip($ogrnip,$ogrnipErr);

	//Дата регистрации
	$date_reg = $_POST['date_reg'];

	
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
		users_organization.fio_sign ='$fio_sign',
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







<!--Модальное окно для реквизитов пользователя-->
<form method="POST" action="<?php htmlspecialchars($_SERVER['PHP_SELF']);?>">
<div class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true" id="pers_requiz">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Ваши реквизиты</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
			</div>
			<div class="modal-body">
				<div class="container-fluid bd-example-row">
					<div class="row">
						<div class="col-md-12 ml-auto">
							<!--ФИО-->
							<div class="form-group row">
								<div class="col-sm-2">
									<small class="form-text inner-block">
									</small>
									<label for="last_name" class="col-form-label col-form-label-sm">ФИО</label>
								</div>
								<div class="col-sm-3">
									<small class="form-text text-muted">
										Фамилия
									</small>
									<input type="text" name="last_name" id="last_name" class="form-control form-control-sm" maxlength="100" value="<?php echo $arr['first_name']?>">
									<div class="invalid-feedback">
										<?php echo $lastnameErr ?>
									</div>
								</div>
								<div class="col-sm-3">
									<small class="form-text text-muted">
										Имя
									</small>
									<input type="text" name="first_name" id="first_name" class="form-control form-control-sm" maxlength="100" value="<?php echo $arr['last_name']?>">
									<div class="invalid-feedback">
										<?php echo $firstnameErr ?>
									</div>
								</div>
								<div class="col-sm-3">
									<small class="form-text text-muted">
										Отчество
									</small>
									<input type="text" name="patronymic" id="patronymic"  class="form-control form-control-sm" maxlength="100" value="<?php echo $arr['patronymic']?>">
									<div class="invalid-feedback">
										<?php echo $patronymicErr ?>
									</div>
								</div>
							</div>

							<!--ИНН ИП-->
							<div class="form-group row">
								<div class="col-sm-2">
									<small class="form-text inner-block">
									</small>
									<label for="ip_inn" class="col-form-label col-form-label-sm">ИНН ИП</label>
								</div>
								<div class="col-sm-6">
									<small class="form-text text-muted">
										12 цифр
									</small>
									<input type="text" name="ip_inn" id="ip_inn" class="form-control form-control-sm" maxlength="12" value="<?php echo $arr['ip_inn']?>">
									<div class="invalid-feedback">
										<?php echo $innErr ?>
									</div>
								</div>
							</div>


							<!--ОКПО-->
							<div class="form-group row">
								<div class="col-sm-2">
									<small class="form-text inner-block">
									</small>
									<label for="okpo" class="col-form-label col-form-label-sm">ОКПО</label>
								</div>
								<div class="col-sm-6">
									<small class="form-text text-muted">
										10 цифр
									</small>
									<input type="text" name="okpo" id="okpo" class="form-control form-control-sm" maxlength="10" value="<?php echo $arr['okpo']?>">
									<div class="invalid-feedback">
										<?php echo $okpoErr ?>
									</div>
								</div>
							</div>

							<!--ОГРНИП-->
							<div class="form-group row">
								<div class="col-sm-2">
									<small class="form-text inner-block">
									</small>
									<label for="ogrnip" class="col-form-label col-form-label-sm">ОГРНИП</label>
								</div>
								<div class="col-sm-6">
									<small class="form-text text-muted">
										15 цифр
									</small>
									<input type="text" name="ogrnip" id="ogrnip" class="form-control form-control-sm" maxlength="15" value="<?php echo $arr['ogrnip']?>">
									<div class="invalid-feedback">
										<?php echo $ogrnipErr ?>
									</div>
								</div>
							</div>


							<!--Дата регистрации-->
							<div class="form-group row">
								<div class="col-sm-2">
									<small class="form-text inner-block">
									</small>
									<label for="date_reg" class="col-form-label col-form-label-sm">Дата регистрации</label>
								</div>
								<div class="col-sm-4">
									<small class="form-text text-muted">
										Дата регистрации ИП
									</small>
									<input type="date" name="date_reg" id="date_reg" class="form-control form-control-sm" value="<?php echo $arr['date_reg']?>">
									<div class="invalid-feedback">
										<?php echo $dateErr ?>
									</div>
								</div>
							</div>

							<!--ОКВЭД-->


							<!--Расчетный счет-->
							<div class="form-group row">
								<div class="col-sm-2">
									<label class="col-form-label col-form-label-sm">Расчетный счет</label>
								</div>
								<div class="col-sm-6">
									<a data-toggle="modal" href="#" data-target="#bank_account_pers">Указать расчетный счет</a>
								</div>
							</div>

							<!--Фио для подписи-->
							<div class="form-group row">
								<div class="col-sm-2">
									<small class="form-text inner-block">
									</small>
									<label for="fio_sign" class="col-form-label col-form-label-sm">ФИО для подписи</label>
								</div>
								<div class="col-sm-6">
									<small class="form-text text-muted">
										Подставляется в качестве расшифровки подписи
									</small>
									<input type="text" name="fio_sign" id="fio_sign" class="form-control form-control-sm" maxlength="100" value="<?php echo $arr['fio_sign']?>">
									<div class="invalid-feedback">
										<?php echo $fiosignErr ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="submit_requiz" class="btn btn-primary">Сохранить</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>			
			</div>
		</div>
	</div>
</div>
</form>

<!--Модальное окно для реквизитов контрагента-->
<form method="POST" action="">
<div class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true" id="client_requiz">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Реквизиты контрагента</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
			</div>
			<div class="modal-body">
				<div class="container-fluid bd-example-row">
					<div class="row">
						<div class="col-md-12 ml-auto">

							<!--Наименование контрагента-->
							<div class="form-group row">
								<div class="col-sm-2">
									<small class="form-text inner-block">
									</small>
									<label for="contractor_name" class="col-form-label col-form-label-sm">Наименование контрагента</label>
								</div>
								<div class="col-sm-6">
									<small class="form-text text-muted">
										Введите наименование
									</small>
									<input type="text" name="contractor_name" id="contractor_name" class="form-control form-control-sm" maxlength="100">
									<div class="invalid-feedback">
										<?php echo "Ошибка" ?>
									</div>
								</div>
							</div>


							<!--ИНН контрагента-->
							<div class="form-group row">
								<div class="col-sm-2">
									<small class="form-text inner-block">
									</small>
									<label for="contractor_inn" class="col-form-label col-form-label-sm">ИНН</label>
								</div>

								<div class="col-sm-3">
									<small class="form-text text-muted">
										12 цифр
									</small>
									<input type="text" name="contractor_inn" id="contractor_inn" class="form-control form-control-sm" maxlength="12">
									<div class="invalid-feedback">
										<?php echo "Ошибка" ?>
									</div>
								</div>

								<!--КПП-->
								<div class="col-sm-1">
									<small class="form-text inner-block">
									</small>
									<label for="contractor_kpp" class="col-form-label col-form-label-sm">КПП</label>
								</div>

								<div class="col-sm-3">
									<small class="form-text text-muted">
										9 цифр
									</small>
									<input type="text" name="contractor_kpp" id="contractor_kpp" class="form-control form-control-sm" maxlength="9">
									<div class="invalid-feedback">
										<?php echo "Ошибка" ?>
									</div>
								</div>

							</div>



							<!--ОКПО контрагента-->
							<div class="form-group row">
								<div class="col-sm-2">
									<small class="form-text inner-block">
									</small>
									<label for="contractor_okpo" class="col-form-label col-form-label-sm">ОКПО</label>
								</div>

								<div class="col-sm-3">
									<small class="form-text text-muted">
										10 цифр
									</small>
									<input type="text" name="contractor_okpo" id="contractor_okpo" class="form-control form-control-sm" maxlength="10">
									<div class="invalid-feedback">
										<?php echo "Ошибка" ?>
									</div>
								</div>

								<!--ОГРН-->
								<div class="col-sm-1">
									<small class="form-text inner-block">
									</small>
									<label for="contractor_ogrn" class="col-form-label col-form-label-sm">ОГРН</label>
								</div>

								<div class="col-sm-3">
									<small class="form-text text-muted">
										13 цифр
									</small>
									<input type="text" name="contractor_ogrn" id="contractor_ogrn" class="form-control form-control-sm" maxlength="13">
									<div class="invalid-feedback">
										<?php echo "Ошибка" ?>
									</div>
								</div>

							</div>


							<!--Адрес-->
							<div class="form-group row">
								<div class="col-sm-2">
									<small class="form-text inner-block">
									</small>
									<label for="address" class="col-form-label col-form-label-sm">Адрес:</label>
								</div>
								<div class="col-sm-6">
									<textarea class="form-control"  name="address" id="address" rows="3" spellcheck="false" maxlength="200"></textarea>
									<div class="invalid-feedback">
										<?php echo "Ошибка" ?>
									</div>
								</div>
							</div>

							<!--Расчетный счет-->
							<div class="form-group row">
								<label class="col-sm-2 col-form-label col-form-label-sm">Расчетный счет</label>
								<div class="col-sm-6">
									<a data-toggle="modal" href="#" data-target="#bank_account_contr">Указать расчетный счет</a>
								</div>
							</div>

							<!--Фио для подписи-->
							<div class="form-group row">
								<div class="col-sm-2">
									<small class="form-text inner-block">
									</small>
									<label for="contractor_sign_name" class="col-form-label col-form-label-sm">ФИО для подписи</label>
								</div>
								<div class="col-sm-6">
									<small class="form-text text-muted">
										Подставляется в качестве расшифровки подписи
									</small>
									<input type="text" name="contractor_sign_name" id="contractor_sign_name" class="form-control form-control-sm" maxlength="100">
									<div class="invalid-feedback">
										<?php echo "Ошибка" ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary">Сохранить</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>			
			</div>
		</div>
	</div>
</div>
</form>