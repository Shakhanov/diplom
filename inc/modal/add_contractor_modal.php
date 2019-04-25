<?php 
require_once('connect.php');

$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);


$contnameErr=$addressErr=$fiosignErr=$contrinnErr=$okpoErr=$ogrnErr=$kppErr="";


#Наименование контрагента
if (isset($_POST['submit_contractor_modal'])) {

	$admit = true;

	$contractor_name = $_POST['contractor_name'];
	$contractor_name=strip_tags($contractor_name);
    //конвертируем специальные символы в мнемоники HTML
	$contractor_name=htmlspecialchars($contractor_name,ENT_QUOTES);
	/* на некоторых серверах
         * автоматически добавляются
         * обратные слеши к кавычкам, вырезаем их */
	$contractor_name=stripslashes($contractor_name);
	if (empty($contractor_name)){
		$contnameErr = "Имя контрагента обязательно";
		$admit = false;
	}	
	else if ((strlen($contractor_name) > 100)) {
		$contnameErr = "Не более 100 символов";
		$admit = false;
	} 


#Проверка ИНН 

	$contractor_inn = mysqli_real_escape_string($dbc, trim($_POST['contractor_inn']));
	//вырезаем теги
	$contractor_inn=strip_tags($contractor_inn);
    //конвертируем специальные символы в мнемоники HTML
	$contractor_inn=htmlspecialchars($contractor_inn,ENT_QUOTES);
	/* на некоторых серверах
         * автоматически добавляются
         * обратные слеши к кавычкам, вырезаем их */
	$contractor_inn=stripslashes($contractor_inn);

	function validateInn($inn, &$contrinnErr = null) {
		$result = false;
		$inn = (string) $inn;
		if (!$inn) {
		} elseif (preg_match('/[^0-9]/', $inn)) {
			$contrinnErr = 'ИНН может состоять только из цифр';
			$admit = false;
		} elseif (!in_array($inn_length = strlen($inn), [10, 12])) {
			$contrinnErr = 'ИНН может состоять только из 10 или 12 цифр';
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
				case 10:
				$n10 = $check_digit($inn, [2, 4, 10, 3, 5, 9, 4, 6, 8]);
				if ($n10 === (int) $inn{9}) {
					$result = true;
				}
				break;
				case 12:
				$n11 = $check_digit($inn, [7, 2, 4, 10, 3, 5, 9, 4, 6, 8]);
				$n12 = $check_digit($inn, [3, 7, 2, 4, 10, 3, 5, 9, 4, 6, 8]);
				if (($n11 === (int) $inn{10}) && ($n12 === (int) $inn{11})) {
					$result = true;
				}
				break;
			}
			if (!$result) {
				$contrinnErr = 'Неправильное контрольное число';
				$admit = false;
			}
		}
		return $result;
	}
	
	validateInn($contractor_inn,$contrinnErr);



	#ОКПО
	$contractor_okpo = trim($_POST['contractor_okpo']);
	if (!empty($contractor_okpo)) {
		if (preg_match('/[^0-9]/', $contractor_okpo)) {
			$okpoErr = "Значение должно содержать только цифры";
			$admit = false;
		} else if ((strlen($contractor_okpo) < 8) || (strlen($contractor_okpo) > 10)) {
			$okpoErr = "Длина должна быть 8 или 10 символов";
			$admit = false;
		} 
	}


	#ОРГН
	$contractor_ogrn = trim($_POST['contractor_ogrn']);


	function validateOgrn($ogrn, &$ogrnErr = null) {
		$result = false;
		$ogrn = (string) $ogrn;
		if (!$ogrn) {
		} else if (preg_match('/[^0-9]/', $ogrn)) {
			$ogrnErr = 'ОГРН может состоять только из цифр';
			$admit = false;
		} elseif (strlen($ogrn) !== 13) {
			$ogrnErr = 'ОГРН может состоять только из 13 цифр';
			$admit = false;
		} else {
			$n13 = (int) substr(bcsub(substr($ogrn, 0, -1), bcmul(bcdiv(substr($ogrn, 0, -1), '11', 0), '11')), -1);
			if ($n13 === (int) $ogrn{12}) {
				$result = true;
			} else {
				$ogrnErr = 'Неправильное контрольное число';
				$admit = false;
			}
		}
		return $result;
	}

	validateOgrn($contractor_ogrn,$ogrnErr);


	#КПП
	$contractor_kpp = trim($_POST['contractor_kpp']);

	function validateKpp($kpp, &$kppErr = null) {
		$result = false;
		$kpp = (string) $kpp;
		if (!$kpp) {
		} elseif (strlen($kpp) !== 9) {
			$kppErr = 'КПП может состоять только из 9 знаков (цифр или заглавных букв латинского алфавита от A до Z)';
			$admit = false;
		} elseif (!preg_match('/^[0-9]{4}[0-9A-Z]{2}[0-9]{3}$/', $kpp)) {
			$kppErr = 'Неправильный формат КПП';
			$admit = false;
		} else {
			$result = true;
		}
		return $result;
	}

	validateKpp($contractor_kpp,$kppErr);


	#Адрес
	$address = $_POST['address'];
	//вырезаем теги
	$address=strip_tags($address);
    //конвертируем специальные символы в мнемоники HTML
	$address=htmlspecialchars($address,ENT_QUOTES);
	/* на некоторых серверах
         * автоматически добавляются
         * обратные слеши к кавычкам, вырезаем их */
	$address=stripslashes($address);
	if (strlen($address) > 200) {
		$addressErr = "Недопустимое количество символов";
		$admit = false;
	}

	#ФИО для подписи
	$contractor_sign_name = $_POST['contractor_sign_name'];
	//вырезаем теги
	$contractor_sign_name=strip_tags($contractor_sign_name);
    //конвертируем специальные символы в мнемоники HTML
	$contractor_sign_name=htmlspecialchars($contractor_sign_name,ENT_QUOTES);
	/* на некоторых серверах
         * автоматически добавляются
         * обратные слеши к кавычкам, вырезаем их */
	$contractor_sign_name=stripslashes($contractor_sign_name);

	if (strlen($contractor_sign_name) > 100) {
		$fiosignErr = "Недопустимое количество символов";
		$admit = false;
	}


//если нет ошибок , и название контрагента не совпадает с существующим , для этого пользователя, то добавляем запись

	if ($admit === true ) {
		$check_entry = "SELECT * FROM contractors WHERE contractor_name = '$contractor_name' ";
		$check_result = mysqli_query($dbc,$check_entry) or die("Неправильный запрос");
		$row = mysqli_num_rows($check_result);
	//такого контрагента нет, добавляем запись
		if ($row == 0 ) {
			$insert_into = "INSERT INTO  reg_users, contractors (contractor_name,contractor_inn,contractor_kpp,contractor_ogrn,contractor_okpo,address,contractor_sign_name,date_of_creation) VALUES ('$contractor_name','$contractor_inn','$contractor_kpp','$contractor_ogrn','$contractor_okpo','$address','$contractor_sign_name','NOW()') WHERE reg_users.id = contractors.user_id";

			mysqli_query($dbc,$insert_into);
		} else {
		//контрагент существует , уведомляем пользователя
			echo "Контрагент существует";
		}
	}

	print_r($insert_into);

}
//добавить запрос к rs



//mysqli_close($dbc);
?>


<!--Модальное окно для реквизитов контрагента-->
<form id="requiz" method="POST" action="<?php htmlspecialchars($_SERVER['PHP_SELF']);?>">
	<div class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true" id="new_contrctor">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Новый контрагент</h5>
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
											<?php echo $contnameErr ?>
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
											<?php echo $contrinnErr ?>
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
											<?php echo $kppErr ?>
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
											<?php echo $okpoErr ?>
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
											<?php echo $ogrnErr ?>
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
											<?php echo $addressErr ?>
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
											<?php echo $fiosignErr ?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary" name="submit_contractor_modal">Добавить</button>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>			
				</div>
			</div>
		</div>
	</div>
</form>