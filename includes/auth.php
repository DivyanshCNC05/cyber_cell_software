<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function require_login(): void {
    if (empty($_SESSION['user_id'])) {
        header('Location: /index.php');
        exit;
    }
}

function require_role(string $role): void {
    require_login();
    if (($_SESSION['role'] ?? '') !== $role) {
        header('Location: /index.php');
        exit;
    }
}

function require_cyber_user_number(int $n): void {
    require_role('CYBER_USER');
    if ((int)($_SESSION['user_number'] ?? 0) !== $n) {
        header('Location: /index.php');
        exit;
    }
}
