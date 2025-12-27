<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/helpers.php';

function current_user() {
    if (!empty($_SESSION['user_id'])) {
        $pdo = getPDO();
        $stmt = $pdo->prepare('SELECT * FROM login_credentials WHERE user_id = ?');
        $stmt->execute([$_SESSION['user_id']]);
        return $stmt->fetch();
    }
    return null;
}

function require_login() {
    if (empty($_SESSION['user_id'])) {
        flash_set('error', 'Please login to continue.');
        redirect('/index.php');
    }
}

function require_role($roles = []) {
    require_login();
    $user = current_user();
    if (!$user || !in_array($user['role'], (array)$roles)) {
        http_response_code(403);
        echo 'Forbidden: You do not have permission to access this page.';
        exit;
    }
}

function login($email, $password) {
    $pdo = getPDO();
    $stmt = $pdo->prepare('SELECT * FROM login_credentials WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    // Check user exists
    if ($user) {
        // Check active flag
        if (isset($user['is_active']) && !$user['is_active']) {
            flash_set('error', 'Account is inactive. Contact administrator.');
            return false;
        }
        if (password_verify($password, $user['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['user_id'];
            // store a couple of convenience values in session
            $_SESSION['role'] = $user['role'];
            $_SESSION['full_name'] = $user['full_name'];
            // record login if activity_logs exists
            log_activity($user['user_id'], 'login', 'Successful login');
            return true;
        }
    }
    return false;
}

function logout() {
    $user = current_user();
    if ($user) {
        log_activity($user['user_id'], 'logout', 'User logged out');
    }
    session_unset();
    session_destroy();
}

// Role helpers
function has_role($role) {
    if (!empty($_SESSION['role'])) return $_SESSION['role'] === $role;
    $u = current_user();
    return ($u && isset($u['role']) && $u['role'] === $role);
}
function is_admin() {
    return has_role('ADMIN');
}
function require_admin() {
    require_role(['ADMIN']);
}
?>