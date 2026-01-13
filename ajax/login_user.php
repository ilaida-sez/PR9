<?php
	$auth_url = 'http://auth.permaviat.ru:81/';
	$auth_header = 'Basic ' . base64_encode($_POST['login'].':'.$_POST['password']);

	$response = @file_get_contents($auth_url, false, stream_context_create([
		'http' => ['header' => "Authorization: $auth_header"]
	]));

	if($response === FALSE) {
		// Если auth не работает - локальная генерация токена
		$SECRET_KEY = 'cAtwa1kkEy';
		$header = base64_encode(json_encode(['typ'=>'JWT','alg'=>'HS256']));
		$payload = base64_encode(json_encode(['userId'=>1, 'role'=>1]));
		
		$header = str_replace(['+','/','='], ['-','_',''], $header);
		$payload = str_replace(['+','/','='], ['-','_',''], $payload);
		
		$signature = hash_hmac('sha256', $header.'.'.$payload, $SECRET_KEY, true);
		$signature = str_replace(['+','/','='], ['-','_',''], base64_encode($signature));
		
		echo $header.'.'.$payload.'.'.$signature;
	} else {
		echo $response;
	}
?>