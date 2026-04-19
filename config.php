<?php 
    function loadEnv($path) {
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if(strpos($line, '=')!== false && strpos($line, '#') !== 0) {
                [$key, $value] = explode('=', $line, 2);
                $_ENV[trim($key)] = trim($value);
            }
        }
    }
    loadEnv(__DIR__ . '/.env');
?>