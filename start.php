<?php
require_once('connect.php');
//Проверка куки и сессий
session_start();

//Если нет ни сессии ни куки, то выкидываем пользователя на login.php
if (!isset($_SESSION['id'])  && (!isset($_COOKIE['id']) && !isset($_COOKIE['email'])) ) {
	header("Location: login.php");
} else {
//проверяем есть ли куки, если да то сохраняем их
	if (isset($_COOKIE['id']) && isset($_COOKIE['email'])) {
		$_SESSION['id'] = $_COOKIE['id'];
		$_SESSION['email'] = $_COOKIE['email'];
	}
}


//Забираем из БД данные для пользователя с этой сессией

$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$query= "SELECT first_name,last_name FROM reg_users WHERE id= ". $_SESSION['id'] ." ";
$data = mysqli_query($dbc, $query);

$wow = mysqli_fetch_array($data);

?>
