<?php
    // Header określający co zwraca plik
    header('Content-Type: application/json');

    require_once 'jwt.php';

    require_once 'config.php';

    // Pobranie pliku db.php i jego zmiennych
    require_once 'db.php';

    // pobieranie body i jego danych
    $rawBody = file_get_contents('php://input');
    $data = json_decode($rawBody, true);

    // Weryfikacja zgodności danych
    if($data == null) {
        http_response_code(400);
        echo json_encode(['status'=>'error', 'message'=>'no data provided']);
        exit;
    }

    if(empty($data['email']) || empty($data['password'])) {
        http_response_code(422);
        echo json_encode(['status'=>'error', 'message'=>'not all fields are filled']);
        exit;
    }

    if(!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        http_response_code(422);
        echo json_encode(['status'=>'error', 'message'=>'wrong email format']);
        exit;
    }

    // Połączenie z DB
    $pdo = getConnection();

    // Przygotowanie statementu (zapytania do db)
    $stmt = $pdo->prepare(
        'SELECT id, email, password_hash, role , is_active from users where email = :email limit 1'
    );

    //  Przypisanie danych do zapytania i wykonanie (execute)
    $stmt->bindValue(':email', $data['email'], PDO::PARAM_STR);
    $stmt->execute();

    // Pobranie użytkownika (danych zwróconych z zapytania)
    $user = $stmt->fetch();

    // Weryfikacja istnienia użytkownika
    if($user === false) {
        http_response_code(401);
        echo json_encode(['status'=>'error', 'message'=>'Wrong email or password']);
        exit;
    }

    if((int)$user['is_active'] !== 1) {
        http_response_code(401);
        echo json_encode(['status'=>'error', 'message'=>'Wrong email or passwordd']);
        exit;
    }

    if(!password_verify($data['password'], $user['password_hash'])) {
        http_response_code(401);
        echo json_encode(['status'=>'error', 'message'=>'Wrong email or password']);
    }

    $jwt = generateJwt($user['id'],$user['email'],$user['role'], $_ENV['JWT_SECRET']);

    echo json_encode(['status'=>'success','message'=>'Token generated successfully', 'token'=>$jwt])
?>