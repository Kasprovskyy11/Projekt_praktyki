<?php 
// Dane do połączenia z bazą danych
    function getConnection() {
        $host   = '192.168.100.28';
        $port   = 6033;
        $dbname = 'login_api';
        $user   = 'proxysql';
        $pass   = '123';
// Korzystanie z PDO
        try {
            $pdo = new PDO(
                "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4",
                $user,
                $pass,
                [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]
            );
            return $pdo;
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Failed to connect with database']);
            exit;
        }
    }
?>