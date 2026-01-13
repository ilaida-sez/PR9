<?php
	session_start();
	include("../settings/connect_datebase.php");

	$login = $_POST['login'];
	$password = $_POST['password'];

	$query_user = $mysqli->query("SELECT * FROM `users` WHERE `login`='".$login."' AND `password`= '".$password."';");
	$id = -1;
	$role = 0;
	while($user_read = $query_user->fetch_row()) {
		$id = $user_read[0];
		$role = $user_read[3];
	}

	if($id != -1) {
		$_SESSION['user'] = $id;
		
		$SECRET_KEY = 'cAtwa1kkEy';
		
		$header = base64_encode(json_encode(['typ' => 'JWT', 'alg' => 'HS256']));
		$payload = base64_encode(json_encode([
			'userId' => $id,
			'login' => $login,
			'role' => $role
		]));
		
		$header = str_replace('=', '', $header);
		$payload = str_replace('=', '', $payload);
		
		$signature = hash_hmac('sha256', $header . '.' . $payload, $SECRET_KEY, true);
		$signature = base64_encode($signature);
		$signature = str_replace('=', '', $signature);
		
		$token = $header . '.' . $payload . '.' . $signature;
		
		echo $token;
	} else {
		echo "";
	}
?>