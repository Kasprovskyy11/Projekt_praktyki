<?php 
    header('Content-Type: application/json');
    require_once 'jwt.php';
    require_once 'config.php';
    $auth = $_SERVER['HTTP_AUTHORIZATION'];
    if($auth == null) {
        http_response_code(401);
        echo json_encode(['status'=>'error', 'message'=>'failed to verify token']);
        exit;
    }
    function cutBearer($auth) {
        $parts = explode(' ', $auth);
        [$bearer, $token] = $parts;
        return $token;
    }

    $token =  cutBearer($auth);
    $verifiedUser = verifyJwt($token, $_ENV['JWT_SECRET']);
    if(!$verifiedUser) {
        http_response_code(401);
        echo json_encode(['status'=>'error', 'message'=>'failed to verify token']);
        exit;
    }

    echo json_encode($verifiedUser);
?>