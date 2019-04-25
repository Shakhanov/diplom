<?php
require_once('connect.php');

$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$rsErr=$bikErr=$bankErr=$commentErr="";

if (isset($_POST['submit_rs'])) { 

	$admit_modal = true;
	#Наименование банка
	$bank = $_POST['bank'];
	//вырезаем теги
	$bank=strip_tags($bank);
    //конвертируем специальные символы в мнемоники HTML
	$bank=htmlspecialchars($bank,ENT_QUOTES);
	/* на некоторых серверах
         * автоматически добавляются
         * обратные слеши к кавычкам, вырезаем их */
	$bank=stripslashes($bank);
	if (empty($bank)) {
		$bankErr = "Обязательное поле";
		$admit_modal = false;
	} 
	else if (!preg_match("/^[a-zа-яё\s]+$/iu",$bank)) {
		$bankErr = "Разрешены только буквы и пробелы";
		$admit_modal = false;
	} 
	else if ((strlen($bank) > 100)) {
		$bankErr = "Не более 100 символов";
		$admit_modal = false;
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
		$admit_modal = false;
	}

#БИК
	$rs = mysqli_real_escape_string($dbc, trim($_POST['rs']));
	$bik = mysqli_real_escape_string($dbc, trim($_POST['bik']));

	if (empty($rs)) {
		$rsErr = "Обязательное поле";
		$admit_modal = false;
	}

	if (empty($bik)) {
		$bikErr = "Обязательное поле";
		$admit_modal = false;
	}

	function validateBik($bik, &$bikErr = null) {
		$result = false;
		$bik = (string) $bik;
		if (!$bik) {
		} else if (preg_match('/[^0-9]/', $bik)) {
			$bikErr = 'БИК может состоять только из цифр';
			$admit_modal = false;
		} else if (strlen($bik) !== 9) {
			$bikErr = 'БИК может состоять только из 9 цифр';
			$admit_modal = false;
		} else {
			$result = true;
		}
		return $result;
	}

	validateBik($bik,$bikErr);

#Р/С


	function validateRs($rs, $bik, &$rsErr = null) {
		$result = false;
		$rs = (string) $rs;
		if (!$rs) {
		} else if (preg_match('/[^0-9]/', $rs)) {
			$rsErr = 'Р/С может состоять только из цифр';
			$admit_modal = false;
		} else if (strlen($rs) !== 20) {
			$rsErr = 'Р/С может состоять только из 20 цифр';
			$admit_modal = false;
		} else {
			$bik_rs = substr((string) $bik, -3) . $rs;
			$checksum = 0;
			foreach ([7, 1, 3, 7, 1, 3, 7, 1, 3, 7, 1, 3, 7, 1, 3, 7, 1, 3, 7, 1, 3, 7, 1] as $i => $k) {
				$checksum += $k * ((int) $bik_rs{$i} % 10);
			}
			if ($checksum % 10 === 0) {
				$result = true;
			} else {
				$rsErr = 'Неправильное контрольное число';
				$admit_modal = false;
			}
		}
		return $result;
	}

	validateRs($rs,$bik,$rsErr);


	//Добавить маски в р/c

	//Если введенный rs существует ,уведомляем пользователя, если нет создаем новую запись
	if ($admit_modal === true) {
		$select_id ="SELECT * FROM users_banks WHERE user_id ='". $_SESSION['id'] ."' AND rs = '$rs'";
		$select_rs = mysqli_query($dbc,$select_id) or die("Неправильный запрос");
		$fetch = mysqli_fetch_array($select_rs);

		if (mysqli_num_rows($select_rs) == 0) {
		//У контрагента нет такого р/c , создаем новую запись
			//добавить state
			$insert_rs = "INSERT INTO users_banks (user_id,rs,state,bank,bik,comment) VALUES ('". $_SESSION['id'] ."','$rs','$bank','$bik','$comment')";
		} else if (mysqli_num_rows($second) == 1) {
		//Р/c найден , уведомляем пользователя 
			echo "указанный rs существует";
		}
		$updaters = mysqli_query($dbc,$query);
	}

}	

mysqli_close($dbc);
?>


<!--Модальное окно для банковского счета-->
<form  method="POST" action="<?php htmlspecialchars($_SERVER['PHP_SELF']);?>">
	<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" id="bank_account_pers">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<!--Расчетный счет-->
				<div class="modal-header">
					<h5 class="modal-title">Банковский счет пользователя</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
				</div>
				<div class="modal-body">
					<div class="container-fluid bd-example-row">
						<div class="row">
							<div class="modal-body">


								<!--Расчетный счет-->
								<div class="form-group row">
									<div class="col-sm-3">
										<small class="form-text inner-block">
										</small>
										<label for="rs" class="col-form-label col-form-label-sm">Расчетный счет</label>
									</div>
									<div class="col-sm-6">
										<small class="form-text text-muted">
											Состоит из 20 цифр
										</small>
										<input type="text" name="rs" id="rs" class="form-control form-control-sm" maxlength="20" value="<?php if(!empty($rs)) echo $fetch['rs']?>">
										<div class="invalid-feedback">
											<?php echo $rsErr ?>
										</div>
									</div>

									<div class="col-sm-3">
										<small class="form-text inner-block">
										</small>
										<select class="form-control form-control-sm">
											<option>Открыт</option>
											<option>Закрыт</option>
										</select>
										<small class="form-text inner-block">
										</small>
									</div>
								</div>



								<!--Банк-->
								<div class="form-group row">
									<div class="col-sm-3">
										<small class="form-text inner-block">
										</small>
										<label for="bank" class="col-form-label col-form-label-sm">Наименование банка</label>
									</div>
									<div class="col-sm-6">
										<small class="form-text text-muted">
											Наименование банка рассчетного счета 
										</small>
										<input type="text" name="bank" id="bank" class="form-control form-control-sm" maxlength="100" value="<?php if(!empty($rs)) echo $fetch['bank']?>">
										<div class="invalid-feedback">
											<?php echo $bankErr ?>
										</div>
									</div>
								</div>

								<!--БИК-->
								<div class="form-group row">
									<div class="col-sm-3">
										<small class="form-text inner-block">
										</small>
										<label for="bik" class="col-form-label col-form-label-sm">БИК</label>
									</div>
									<div class="col-sm-6">
										<small class="form-text text-muted">
											Состоит из 9 цифр
										</small>
										<input type="text" name="bik" id="bik" class="form-control form-control-sm" maxlength="9" value="<?php if(!empty($rs)) echo $fetch['bik']?>">
										<div class="invalid-feedback">
											<?php echo $bikErr ?>
										</div>
									</div>
								</div>

								<!--Комментарий-->
								<div class="form-group row">
									<div class="col-sm-3">
										<small class="form-text inner-block">
										</small>
										<label for="comment" class="col-form-label col-form-label-sm">Комментарий</label>
									</div>
									<div class="col-sm-6">
										<small class="form-text text-muted">
											Ваш коментарий к счету
										</small>
										<input type="text" name="comment" id="comment" class="form-control form-control-sm" maxlength="200" value="<?php if(!empty($rs)) echo $fetch['comment']?>">
										<div class="invalid-feedback">
											<?php echo $commentErr ?>
										</div>
									</div>
								</div>

							</div>

						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary" name="submit_rs">Сохранить</button>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>			
				</div>
			</div>
		</div>
	</div>
</form>
