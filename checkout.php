<?php
require_once('connect.php');

$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$rsErr="";
$bikErr="";

if (isset($_POST['submit_rs'])) { 

#БИК
	$rs = trim($_POST['rs']);
	$bik = trim($_POST['bik']);

	function validateBik($bik, &$bikErr = null) {
		$result = false;
		$bik = (string) $bik;
		if (!$bik) {
		} elseif (preg_match('/[^0-9]/', $bik)) {
			$bikErr = 'БИК может состоять только из цифр';
		} elseif (strlen($bik) !== 9) {
			$bikErr = 'БИК может состоять только из 9 цифр';
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
		} elseif (preg_match('/[^0-9]/', $rs)) {
			$rsErr = 'Р/С может состоять только из цифр';
		} elseif (strlen($rs) !== 20) {
			$rsErr = 'Р/С может состоять только из 20 цифр';
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
			}
		}
		return $result;
	}

	validateRs($rs,$bik,$rsErr);

}	

?>


<!--Модальное окно для банковского счета-->
<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" id="bank_account">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<form  method="POST" action="<?php htmlspecialchars($_SERVER['PHP_SELF']);?>">
				<!--Расчетный счет-->
				<div class="modal-header">
					<h5 class="modal-title">Банковский счет</h5>
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
											Для ошибок
										</small>
										<input type="text" name="rs" id="rs" class="form-control form-control-sm" maxlength="20">
										<small class="form-text text-muted">
											<?php echo $rsErr ?>
										</small>
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
											Для ошибок
										</small>
										<input type="text" name="bank" id="bank" class="form-control form-control-sm" maxlength="100">
										<small class="form-text text-muted">
											Для ошибок
										</small>
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
											Для ошибок
										</small>
										<input type="text" name="bik" id="bik" class="form-control form-control-sm" maxlength="9">
										<small class="form-text text-muted">
											<?php echo $bikErr ?>
										</small>
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
											Для ошибок
										</small>
										<input type="text" name="comment" id="comment" class="form-control form-control-sm" maxlength="200">
										<small class="form-text text-muted">
											Для ошибок
										</small>
									</div>
								</div>

							</div>

						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" name="submit_rs">Сохранить</button>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>			
				</div>
			</form>
		</div>
	</div>
</div>
