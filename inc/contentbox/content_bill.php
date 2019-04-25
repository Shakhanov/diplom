<div class="container-fluid">


	<form id="requiz" method="POST">
		<!-- Заглавие -->

		<div class="d-sm-flex align-items-center justify-content-between mb-4">
			<h1 class="h3 mb-0 text-gray-800">Выставление счета</h1>

		</div>

		<div class="d-sm-flex align-items-center justify-content-between mb-4">
			<span>Для генерации счета, выберите шаблон (или создайте свой) , ознакомтесь с реквизитами , измените их в случае необходимости и нажмите кнопку "Сгенерировать"</span>
		</div>

		<div class="row">

			<!--Отчетность-->
			<div class="col-xl-12 col-lg-12">

				<!--Параметры счета-->
				<div class="card shadow mb-4">
					<div class="card-header py-3">
						<h6 class="m-0 font-weight-bold text-primary">Параметры счета</h6>
					</div>
					<div class="card-body">

						<!--Поля  ФИО-->
						<div class="row">
							
							<div class="col-md-4 mb-3">
								<label for="contract_name">Наименование счета</label>
								<input type="text" name="contract_name" id="contract_name" class="form-control">
								<div class="invalid-feedback">
									Zip code required.
								</div>
							</div>

							<div class="col-md-4 mb-3">
								<label for="exampleFormControlSelect1">Шаблон документа</label>
								<select class="form-control" id="exampleFormControlSelect1">
									<option>1</option>
									<option>2</option>
									<option>3</option>
									<option>4</option>
									<option>5</option>
								</select>
							</div>

							<p class="text-center col-md-4 mb-3" style="padding-top: 35px;padding-bottom: 20px;"><a href="#">Добавить собственный шаблон</a>.</p>
						</div>
					</div>
				</div>

				<!--Подключение реквизитов-->
				<?php include_once('./inc/contentbox/requiz_connect.php'); ?>

			</div>


			<div class="col-xl-12 col-lg-12">
				<div class="card shadow mb-4">
					<div class="card-body">
						<input type="submit" name="submit_requiz" class="btn btn-success" value="Сгенерировать">
						<input type="button" class="btn btn-outline-dark" value="Отмена">
					</div>
				</div>
			</div>
		</div>

		<!--Подключение модальных окон-->
		<?php include_once('./inc/modal/requiz_modal.php'); ?>
		<?php include_once('./inc/modal/rs_modal_contr.php'); ?>
		<?php include_once('./inc/modal/rs_modal_pers.php'); ?>

	</form>



	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
	<script type="text/javascript" src="/js/jquery.maskedinput.min.js"></script>


	<script>
		$('.mask-inn-individual').mask('999999999999');
		$('.mask-inn-organization').mask('9999999999');
		$('.mask-ogrnip').mask('999999999999999');
		$('.mask-okpo-individual').mask('9999999999');
		$('.mask-okpo-organization').mask('9999999999');
		$('.mask-okved').mask('99.99.?99',{placeholder: "XX.XX.XX"});
	</script>

</div>