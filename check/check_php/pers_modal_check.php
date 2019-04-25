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