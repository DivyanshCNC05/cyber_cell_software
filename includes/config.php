<?php
// config.php - reads .env (if present) and defines constants

// Simple .env reader (very small, no dependencies)
$env_path = __DIR__ . '/../.env';
if (file_exists($env_path)) {
    $lines = file($env_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        if (strpos($line, '=') === false) continue;
        list($k, $v) = array_map('trim', explode('=', $line, 2));
        $v = trim($v, "\"'");
        if (!getenv($k)) putenv("$k=$v");
        if (!defined($k)) define($k, $v);
    }
}

// Fallback constants if not defined via .env or environment
if (!defined('DB_HOST')) define('DB_HOST', getenv('DB_HOST') ?: '127.0.0.1');
if (!defined('DB_NAME')) define('DB_NAME', getenv('DB_NAME') ?: 'cybercell_db');
if (!defined('DB_USER')) define('DB_USER', getenv('DB_USER') ?: 'root');
if (!defined('DB_PASS')) define('DB_PASS', getenv('DB_PASS') ?: '');
if (!defined('APP_NAME')) define('APP_NAME', getenv('APP_NAME') ?: 'Cybercell');
if (!defined('BASE_PATH')) define('BASE_PATH', getenv('BASE_PATH') ?: '/');

// Basic security and session
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
session_start();

?>