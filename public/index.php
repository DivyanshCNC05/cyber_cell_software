<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_check($_POST['csrf_token'] ?? '')) {
        flash_set('error', 'Invalid CSRF token');
        redirect('/index.php');
    }
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    if (login($email, $password)) {
        $user = current_user();
        switch ($user['role'] ?? '') {
            case 'ADMIN': redirect('/dashboard.php'); break;
            case 'CEIR_USER': redirect('/dashboard.php'); break;
            case 'CYBER_USER': redirect('/dashboard.php'); break;
            default: redirect('/index.php'); break;
        }
    } else {
        flash_set('error', 'Invalid credentials');
    }
}

$error = flash_get('error');
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars(APP_NAME) ?> - Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card shadow-sm">
        <div class="card-body">
          <h4 class="card-title">Login</h4>
          <?php if ($error): ?>
            <div class="alert alert-danger"><?= sanitize($error) ?></div>
          <?php endif; ?>
          <form method="post" action="/index.php">
            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
            <div class="mb-3">
              <label class="form-label">Email</label>
              <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Password</label>
              <input type="password" name="password" class="form-control" required>
            </div>
            <div class="mb-3">
              <button class="btn btn-primary">Login</button>
            </div>
          </form>
          <hr>
          <small class="text-muted">Use seeded admin after you set a secure password via the setup steps in README.</small>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>