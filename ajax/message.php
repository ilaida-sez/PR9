<?php
    $SECRET_KEY = 'cAtwa1kkEy';
    $headers = getallheaders();
    $token = $headers['token'] ?? '';

    $parts = explode('.', $token);
    if(count($parts) === 3) {
        $payload = json_decode(base64_decode($parts[1]), true);
        $IdUser = $payload['userId'] ?? -1;
        
        if($IdUser != -1) {
            include("../settings/connect_datebase.php");
            $Message = $_POST["Message"];
            $IdPost = $_POST["IdPost"];
            $mysqli->query("INSERT INTO `comments`(`IdUser`, `IdPost`, `Messages`) VALUES ({$IdUser}, {$IdPost}, '{$Message}');");
            exit;
        }
    }

    http_response_code(401);
?>