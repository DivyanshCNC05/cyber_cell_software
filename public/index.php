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
        // Redirect based on role
        $target = '/index.php';
        switch ($user['role'] ?? '') {
            case 'ADMIN': $target = '/admin_dashboard.php'; break;
            case 'CEIR_USER': $target = '/ceir_dashboard.php'; break;
            case 'CYBER_USER': $target = '/cyber_dashboard.php'; break;
            default: $target = '/index.php'; flash_set('error', 'No dashboard configured for your role.'); break;
        }
        error_log("[cybercell] Login redirect target: " . $target . " for user_id=" . ($_SESSION['user_id'] ?? 'n/a'));
        error_log("[cybercell] Request URI: " . ($_SERVER['REQUEST_URI'] ?? '') . " Host: " . ($_SERVER['HTTP_HOST'] ?? ''));
        // store last redirect target for visibility after redirect
        $_SESSION['last_redirect_target'] = $target;
        // If debug=1 query parameter is present, output debug info instead of redirecting
        if (isset($_GET['debug']) && $_GET['debug'] == '1') {
            header('Content-Type: text/plain; charset=utf-8');
            echo "Login redirect target: " . $target . "\n\n";
            echo "Session variables:\n";
            print_r($_SESSION);
            exit;
        }
        flash_set('info', "Redirecting to " . $target . "; (last: " . ($_SESSION['last_redirect_target'] ?? '') . ")");
        redirect($target);
    } else {
        // login() sets a flash message for inactive accounts; otherwise show generic error
        if (!flash_get('error')) flash_set('error', 'Invalid credentials');
    }
}

$error = flash_get('error');
$info = flash_get('info');
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
          <?php if ($info): ?>
            <div class="alert alert-info"><?= sanitize($info) ?></div>
          <?php endif; ?>
          <?php if (isset($_GET['debug']) && $_GET['debug'] == '1'): ?>
            <div class="alert alert-warning"><pre><?php echo sanitize(print_r($_SESSION, true)); ?></pre></div>
            <div class="alert alert-secondary">Last redirect: <?= sanitize($_SESSION['last_redirect_target'] ?? '(none)') ?></div>
          <?php endif; ?>
          <form method="post" action="/index.php<?= (isset($_GET['debug']) && $_GET['debug']=='1') ? '?debug=1' : '' ?>">
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