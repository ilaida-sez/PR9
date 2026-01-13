<?php
    session_start();
    include("../settings/connect_datebase.php");

    function getUserIdFromToken() {
        $headers = getallheaders();
        if (!isset($headers['token'])) {
            if (isset($_SESSION['user']) && $_SESSION['user'] != -1) {
                return $_SESSION['user'];
            }
            return -1;
        }
        
        $token = $headers['token'];
        $parts = explode('.', $token);
        if (count($parts) !== 3) return -1;
        
        $payload = json_decode(base64_decode($parts[1]), true);
        
        if ($payload && isset($payload['userId'])) {
            $_SESSION['user'] = $payload['userId'];
            return $payload['userId'];
        }
        
        return -1;
    }

    $IdUser = getUserIdFromToken();

    if($IdUser == -1) {
        http_response_code(401);
        exit;
    }

    $Message = $_POST["Message"];
    $IdPost = $_POST["IdPost"];

    $mysqli->query("INSERT INTO `comments`(`IdUser`, `IdPost`, `Messages`) VALUES ({$IdUser}, {$IdPost}, '{$Message}');");
?>