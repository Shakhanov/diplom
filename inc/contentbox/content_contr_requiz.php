<?php 

require_once('connect.php');

$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$contnameErr=$orgnameErr=$addressErr=$phoneErr=$emailErr=$contsignErr=$contrinnErr=$okpoErr=$ogrnErr=$kppErr=$positionErr=$passErr=$commentErr="";



#Наименование контрагента
if (isset($_POST['submit_contractor'])) {

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

#наименование организации
	$org_name = $_POST['org_name'];
	#наименование организации
	$org_name=strip_tags($org_name);
    //конвертируем специальные символы в мнемоники HTML
	$org_name=htmlspecialchars($org_name,ENT_QUOTES);
	/* на некоторых серверах
         * автоматически добавляются
         * обратные слеши к кавычкам, вырезаем их */
	$org_name=stripslashes($org_name);
	if ((strlen($org_name) > 100)) {
		$orgnameErr = "Не более 100 символов";
		$admit = false;
	}

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

	//Потом поменяю
	#Телефон
	$phone = $_POST['phone'];



	#Электронная почта
	$contractor_email = $_POST['contractor_email'];
	if (!empty($contractor_email)) {
		if (!preg_match("/^(?:[a-z0-9]+(?:[-_.]?[a-z0-9]+)?@[a-z0-9_.-]+(?:\.?[a-z0-9]+)?\.[a-z]{2,5})$/i", $contractor_email)) {
			$emailErr = "email адрес указан неправильно.";
			$contractor_email = "";
			$admit = false;
		}
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
		$contsignErr = "Недопустимое количество символов";
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


	#Должность
	$position = $_POST['position'];
	//вырезаем теги
	$position=strip_tags($position);
    //конвертируем специальные символы в мнемоники HTML
	$position=htmlspecialchars($position,ENT_QUOTES);
	/* на некоторых серверах
         * автоматически добавляются
         * обратные слеши к кавычкам, вырезаем их */
	$position=stripslashes($position);
	if (strlen($position) > 100) {
		$positionErr = "Недопустимое количество символов";
		$admit = false;
	}



#Паспортные данные
	$pass_details = $_POST['pass_details'];
	//вырезаем теги
	$pass_details=strip_tags($pass_details);
    //конвертируем специальные символы в мнемоники HTML
	$pass_details=htmlspecialchars($pass_details,ENT_QUOTES);
	/* на некоторых серверах
         * автоматически добавляются
         * обратные слеши к кавычкам, вырезаем их */
	$pass_details=stripslashes($pass_details);
	if (strlen($pass_details) > 200) {
		$passErr = "Недопустимое количество символов";
		$admit = false;
	} 

#Комментарий
	$comment = $_POST['comment'];
	//вырезаем теги
	$comment=strip_tags($comment);
    //конвертируем специальные символы в мнемоники HTML
	$comment=htmlspecialchars($comment,ENT_QUOTES);
	/* на некоторых серверах
         * автоматически добавляются
         * обратные слеши к кавычкам, вырезаем их */
	$comment=stripslashes($comment);
	if (strlen($comment) > 200) {
		$commentErr = "Недопустимое количество символов";
		$admit = false;
	} 


	if ($admit == true) {

		$insert_data = "UPDATE reg_users,contractors SET 
		contractors.contractor_name = '$contractor_name',
		contractors.org_name = '$org_name',
		contractors.address = '$address',
		contractors.phone = '$phone',
		contractors.contractor_email = '$contractor_email',
		contractors.contractor_sign_name = '$contractor_sign_name',
		contractors.contractor_inn = '$contractor_inn',
		contractors.contractor_okpo = '$contractor_okpo',
		contractors.contractor_ogrn = '$contractor_ogrn',
		contractors.contractor_kpp = '$contractor_kpp',
		contractors.position = '$position',
		contractors.pass_details = '$pass_details',
		contractors.comment = '$comment'
		WHERE contractors.user_id = ". $_SESSION['id'] ." AND contractors.contractor_name = '$contractor_name' AND  reg_users.id = contractors.user_id";
		//правильно сохранить имя контрагента
		mysqli_query($dbc,$insert_data);
	}

}//submit

	//SELECT * FROM reg_users,contractors WHERE contractors.user_id = ". $_SESSION['id'] ." AND reg_users.id = contractors.user_id AND contractors.contractor_name = '$contractor_name'

	//Запрос на выбор всех р/с контрагента
	$get_contr_requiz = "SELECT * FROM contractors WHERE contractors.user_id = ". $_SESSION['id'] ." AND contractors.contractor_name = 'Krosh'";//изменить запрос
	$show_requiz = mysqli_query($dbc, $get_contr_requiz) or trigger_error(mysql_error()." in ". $get_contr_requiz);
	$takerequiz = mysqli_fetch_array($show_requiz);

	mysqli_close($dbc);
	?>

	<div class="container-fluid">


		<form id="requiz" method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
			<!-- Заглавие -->

			<div class="d-sm-flex align-items-center justify-content-between mb-4">
				<h1 class="h3 mb-0 text-gray-800">Реквизиты контрагента + наименование контрагента</h1>

			</div>

			<div class="d-sm-flex align-items-center justify-content-between mb-4">
				<span>Заполните данные полей и сохраните их для дальнейшей работы с контрагентом</span>
			</div>

			<div class="row">

				<div class="col-xl-12 col-lg-12">

					<div class="card shadow mb-4">
						<div class="card-header py-3">
							<h6 class="m-0 font-weight-bold text-primary">Отчетность</h6>
						</div>
						<div class="card-body">

							<div class="row">
								<div class="col-md-4 mb-3">
									<label for="contractor_name">Наименование контрагента</label> 
									<input type="text" name="contractor_name" id="contractor_name" class="form-control" maxlength="100" value="<?php echo $takerequiz['contractor_name']?>" >
									<div class="invalid-feedback">
										<?php echo $contnameErr?>
									</div>
								</div>
							</div>


							<div class="row">
								<div class="col-md-4 mb-3">
									<label for="org_name">Наименование организации</label>
									<input type="text" name="org_name" id="org_name" class="form-control" maxlength="100" value="<?php echo $takerequiz['org_name']?>">
									<div class="invalid-feedback">
										<?php echo $orgnameErr?>
									</div>
								</div>
							</div>

							<div class="col-md-8 mb-3">
								<label for="address">Адрес</label>
								<textarea name="address" id="address" class="form-control" rows="3" maxlength="200"><?php echo $takerequiz['address']?></textarea>
								<div class="invalid-feedback">
									<?php echo $addressErr?>
								</div>
							</div>


							<div class="row">
								<div class="col-md-4 mb-3">
									<label for="phone">Регион: </label>
									<select id="country" class="form-control">
										<option value="ru">Россия +7</option>
										<option value="ua">Украина +380</option>
										<option value="by">Белоруссия +375</option>
									</select>
								</div>
								<div class="col-md-4 mb-3">
									<label for="phone">Телефон: </label>
									<input type="text" name="phone" id="phone" class="form-control" value="<?php echo $takerequiz['phone']?>">
									<div class="invalid-feedback">
										<?php echo $phoneErr?>
									</div>
								</div>
							</div>


							<div class="row">
								<div class="col-md-4 mb-3">
									<label for="contractor_email">Электронная почта</label>
									<input type="email" name="contractor_email" id="contractor_email" class="form-control" maxlength="100" value="<?php echo $takerequiz['contractor_email']?>">
									<div class="invalid-feedback">
										<?php echo $emailErr?>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-8 mb-3">
									<label for="contractor_sign_name">ФИО для подписи</label>
									<input type="text" name="contractor_sign_name" id="contractor_sign_name" placeholder="" class="form-control" maxlength="100" value="<?php echo $takerequiz['contractor_sign_name']?>">
									<div class="invalid-feedback"><?php echo $contsignErr?></div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-4 mb-3">
									<label for="contractor_inn">ИНН</label>
									<input type="text" name="contractor_inn" id="contractor_inn" class="mask-inn form-control" maxlength="12" placeholder="10 или 12 цифр" value="<?php echo $takerequiz['contractor_inn']?>">
									<div class="invalid-feedback">
										<?php echo $contrinnErr?>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-4 mb-3">
									<label for="contractor_okpo">ОКПО</label>
									<input type="text" name="contractor_okpo" id="contractor_okpo" class="mask-okpo form-control" maxlength="10" placeholder="8-10 цифр" value="<?php echo $takerequiz['contractor_okpo']?>">
									<div class="invalid-feedback">
										<?php echo $okpoErr?>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-4 mb-3">
									<label for="contractor_ogrn">ОГРН</label>
									<input type="text" name="contractor_ogrn" id="contractor_ogrn" class="mask-ogrn form-control" maxlength="13" placeholder="13 цифр" value="<?php echo $takerequiz['contractor_ogrn']?>">
									<div class="invalid-feedback">
										<?php echo $ogrnErr?>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-4 mb-3">
									<label for="contractor_kpp">КПП</label>
									<input type="text" name="contractor_kpp" id="contractor_kpp" class="mask-kpp form-control" maxlength="9" placeholder="13 цифр" value="<?php echo $takerequiz['contractor_kpp']?>">
									<div class="invalid-feedback">
										<?php echo $kppErr?>
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
							<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#bank_account_contr">
								Добавить расчётный счет
							</button>
						</div>
					</div>


					<div class="card shadow mb-4">
						<div class="card-header py-3">
							<h6 class="m-0 font-weight-bold text-primary">Контактные данные</h6>
						</div>
						<div class="card-body">
							<p>Контактные данные</p>
							<p class="mb-0">Укажите свои контактные данные.Они могут не совпадать с реквизитами для отчетности, так как попадут в документы и реквизитку.</p>

							<div class="col-md-8 mb-3">
								<label for="position">Должность</label>
								<input type="text" name="position" id="position" placeholder="" class="form-control" maxlength="100" value="<?php echo $takerequiz['position']?>">
								<div class="invalid-feedback"><?php echo $positionErr ?></div>
							</div>

							<div class="col-md-8 mb-3">
								<label for="pass_details">Паспортные данные</label>
								<textarea name="pass_details" id="pass_details" class="form-control" rows="3" maxlength="200"><?php echo $takerequiz['pass_details']?></textarea>
								<div class="invalid-feedback"><?php echo $passErr?></div>
							</div>

							<div class="col-md-8 mb-3">
								<label for="comment">Комментарий</label>
								<textarea name="comment" id="comment" class="form-control" rows="3" maxlength="200"><?php echo $takerequiz['comment']?></textarea>
								<div class="invalid-feedback"><?php echo $commentErr?></div>
							</div>

						</div>
					</div>

					<div class="card shadow mb-4">
						<div class="card-body">
							<input type="submit" name="submit_contractor" class="btn btn-success" value="Сохранить">
							<input type="button" onclick="document.getElementById('requiz').reset()" class="btn btn-secondary" value="Очистить реквизиты">
						</div>
					</div>

				</div>
			</div>

		</form>


		<!--Подключение модальных окон-->
		<?php include_once('./inc/modal/rs_modal_contr.php'); ?>


		<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
		<script type="text/javascript" src="/js/jquery.maskedinput.min.js"></script>


		<script>
		//Маски
		$('.mask-inn').mask('9999999999?99');
		$('.mask-okpo').mask('99999999?99');
		$('.mask-ogrn').mask('9999999999999');
		$('.mask-kpp').mask('999999999');


		//Для телефона
		jQuery (function ($) {  
			$(function() {
				function maskPhone() {
					var country = $('#country option:selected').val();
					switch (country) {
						case "ru":
						$("#phone").mask("+7(999) 999-99-99");
						break;
						case "ua":
						$("#phone").mask("+380(999) 999-99-99");
						break;
						case "by":
						$("#phone").mask("+375(999) 999-99-99");
						break;          
					}    
				}
				maskPhone();
				$('#country').change(function() {
					maskPhone();
				});
			});
		});
	</script>

</div>