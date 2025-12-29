<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/config.php';


if (!function_exists('require_login')) {
    function require_login(): void {
        if (empty($_SESSION['user_id'])) {
            header('Location: ' . BASE_PATH . '/index.php');
            exit;
        }
    }

    function require_role(string $role): void {
        require_login();
        if (($_SESSION['role'] ?? '') !== $role) {
            header('Location: ' . BASE_PATH . '/index.php');
            exit;
        }
    }

    function require_cyber_user_number(int $n): void {
        // Allow admins to access any user dashboard
        if (($_SESSION['role'] ?? '') === 'ADMIN') {
            return;
        }

        require_role('CYBER_USER');
        if ((int)($_SESSION['user_number'] ?? 0) !== $n) {
            header('Location: ' . BASE_PATH . '/index.php');
            exit;
        }
    }
}
