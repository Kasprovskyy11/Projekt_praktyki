<?php 
    header('Content-Type: application/json');
    require_once 'config.php';
    require_once 'db.php';

    $auth = $_SERVER['HTTP_AUTHORIZATION'];
    if(!$auth) {
        http_response_code(404);
        exit;
    }

    function getJti($auth) {
        $parts = explode(' ', $auth);
        [$bearer, $token] = $parts;
        $tokenParts = explode('.', $token);
        [$header, $payload, $signature] = $tokenParts;
        $data = json_decode(base64_decode($payload));
        return $data;
    }

    $data = getJti($auth);

    $pdo = getConnection();

    $stmt = $pdo->prepare(
        'INSERT INTO blacklist (jti, expires_at) values (:jti, :expires_at)'
    );

    $stmt->bindValue(':jti', $data->jti, PDO::PARAM_STR);
    $stmt->bindValue(':expires_at', date('Y-m-d H:i:s', $data->exp));
    $stmt->execute();
?>