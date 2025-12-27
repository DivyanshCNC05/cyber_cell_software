<?php
// db.php - PDO connection using config constants
require_once __DIR__ . '/config.php';

function getPDO() {
    static $pdo = null;
    if ($pdo === null) {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
        if (defined('DB_PORT') && DB_PORT) {
            $dsn .= ';port=' . DB_PORT;
        }
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            http_response_code(500);
            echo "Database connection failed: " . htmlspecialchars($e->getMessage());
            exit;
        }
    }
    return $pdo;
}
?>