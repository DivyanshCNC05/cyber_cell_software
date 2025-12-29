<?php
require __DIR__ . '/../includes/db.php';
session_start();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $pass  = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("
        SELECT user_id, email, password_hash, password_hash_new, role, full_name, is_active, user_number
        FROM login_credentials
        WHERE email = :email
        LIMIT 1
    ");
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch();

    if (!$user) {
        $error = 'Email not found';
    } elseif ((int)$user['is_active'] !== 1) {
        $error = 'User is inactive';
    } else {
        $hash = $user['password_hash_new'] ?: $user['password_hash'];

        if (!$hash || !password_verify($pass, $hash)) {
            $error = 'Wrong password';
        } else {
            session_regenerate_id(true);

            $_SESSION['user_id']     = (int)$user['user_id'];
            $_SESSION['role']        = $user['role'];        // ADMIN / CYBER_USER / CEIR_USER
            $_SESSION['name']        = $user['full_name'];
            $_SESSION['user_number'] = $user['user_number']; // 1/2/3 for CYBER_USER

            if ($user['role'] === 'ADMIN') {
                $dest = BASE_PATH . '/dashboards/admin.php';
                error_log('DEBUG: redirecting to ' . $dest);
                header('Location: ' . $dest); exit;
            }
            if ($user['role'] === 'CEIR_USER') {
                $dest = BASE_PATH . '/dashboards/ceir.php';
                error_log('DEBUG: redirecting to ' . $dest);
                header('Location: ' . $dest); exit;
            }
            if ($user['role'] === 'CYBER_USER') {
                if ((int)$user['user_number'] === 1) { $dest = BASE_PATH . '/dashboards/user1.php'; error_log('DEBUG: redirecting to ' . $dest); header('Location: ' . $dest); exit; }
                if ((int)$user['user_number'] === 2) { $dest = BASE_PATH . '/dashboards/user2.php'; error_log('DEBUG: redirecting to ' . $dest); header('Location: ' . $dest); exit; }
                if ((int)$user['user_number'] === 3) { $dest = BASE_PATH . '/dashboards/user3.php'; error_log('DEBUG: redirecting to ' . $dest); header('Location: ' . $dest); exit; }
                $error = 'CYBER_USER must have user_number 1,2,3';
            } else {
                $error = 'Invalid role';
            }
        }
    }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login - Cyber Cell</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?= BASE_PATH ?>/assets/css/style.css" rel="stylesheet">
</head>
<body class="login-bg">
  <div class="login-overlay"></div>
  <div class="d-flex align-items-center justify-content-center min-vh-100">
    <div class="card login-card p-4 shadow-lg">
      <div class="text-center mb-3">
        <h2 class="h4 mb-0">Cyber Cell</h2>
        <div class="text-muted small">Secure Admin Console</div>
      </div>

      <?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>

      <form method="post" class="mb-0">
        <div class="mb-3">
          <label class="form-label">Email</label>
          <input class="form-control" type="email" name="email" required autofocus>
        </div>
        <div class="mb-3">
          <label class="form-label">Password</label>
          <input class="form-control" type="password" name="password" required>
        </div>
        <div class="d-grid">
          <button class="btn btn-primary btn-block" type="submit">Login</button>
        </div>
      </form>

      <div class="mt-3 text-center text-muted small">© <?= date('Y') ?> Cyber Cell</div>
    </div>
  </div>
</body>
</html>