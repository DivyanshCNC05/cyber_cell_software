<?php
// helpers.php - small utility set (flash, redirect, sanitize, CSRF, logging)
require_once __DIR__ . '/db.php';

function flash_set($key, $msg) {
    $_SESSION['_flash'][$key] = $msg;
}
function flash_get($key) {
    $v = $_SESSION['_flash'][$key] ?? null;
    unset($_SESSION['_flash'][$key]);
    return $v;
}

function redirect($url) {
    header('Location: ' . $url);
    exit;
}

function sanitize($s) {
    return htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

// Minimal CSRF helpers
function csrf_token() {
    if (empty($_SESSION['_csrf'])) {
        $_SESSION['_csrf'] = bin2hex(random_bytes(16));
    }
    return $_SESSION['_csrf'];
}
function csrf_check($token) {
    return hash_equals($_SESSION['_csrf'] ?? '', $token);
}

function log_activity($user_id, $action, $details = null) {
    $pdo = getPDO();
    try {
        $stmt = $pdo->prepare('INSERT INTO activity_logs (user_id, action, details) VALUES (?, ?, ?)');
        $stmt->execute([$user_id, $action, $details]);
    } catch (PDOException $e) {
        // activity_logs table may not exist in the updated schema — ignore
    }
}
?>