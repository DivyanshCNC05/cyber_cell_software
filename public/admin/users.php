<?php
require __DIR__ . '/access.php';
require __DIR__ . '/../../includes/db.php';

$msg = '';
$err = '';

function p($k, $d=''){ return trim($_POST[$k] ?? $d); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $action = p('action');
  $user_id = (int)p('user_id', '0');

  if ($action === 'toggle_active') {
    $is_active = (int)p('is_active', '0');
    $stmt = $pdo->prepare("UPDATE login_credentials SET is_active = :a WHERE user_id = :id LIMIT 1");
    $stmt->execute([':a' => $is_active, ':id' => $user_id]);
    $msg = 'User status updated.';
  }

  if ($action === 'reset_password') {
    $newpass = p('new_password');
    if (strlen($newpass) < 4) {
      $err = 'Password too short.';
    } else {
      $hash = password_hash($newpass, PASSWORD_DEFAULT); // bcrypt [web:236]
      $stmt = $pdo->prepare("UPDATE login_credentials SET password_hash_new = :h WHERE user_id = :id LIMIT 1");
      $stmt->execute([':h' => $hash, ':id' => $user_id]);
      $msg = 'Password reset done.';
    }
  }

  if ($action === 'create_user') {
    $email = p('email');
    $full_name = p('full_name');
    $role = p('role');
    $user_number = p('user_number') !== '' ? (int)p('user_number') : null;
    $pass = p('password');

    if ($email === '' || $role === '' || $pass === '') {
      $err = 'Email, role, password are required.';
    } else {
      $hash = password_hash($pass, PASSWORD_DEFAULT); // [web:236]
      $stmt = $pdo->prepare("INSERT INTO login_credentials
        (email, password_hash, password_hash_new, role, full_name, is_active, user_number)
        VALUES (:email, '', :hash_new, :role, :full_name, 1, :user_number)");
      $stmt->execute([
        ':email' => $email,
        ':hash_new' => $hash,
        ':role' => $role,
        ':full_name' => ($full_name !== '' ? $full_name : null),
        ':user_number' => $user_number,
      ]);
      $msg = 'User created.';
    }
  }
}

// list users
$users = $pdo->query("SELECT user_id, email, role, full_name, is_active, user_number, created_at
                      FROM login_credentials
                      ORDER BY user_id DESC")->fetchAll();
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin - Users</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="b-light">
<div class="container py-4">

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">User Management</h3>
    <a class="btn btn-outline-secondary btn-sm" href="<?= BASE_PATH ?>/dashboards/admin.php">Back</a>
  </div>

  <?php if ($err): ?><div class="alert alert-danger"><?= htmlspecialchars($err) ?></div><?php endif; ?>
  <?php if ($msg): ?><div class="alert alert-success"><?= htmlspecialchars($msg) ?></div><?php endif; ?>

  <div class="card mb-4">
    <div class="card-header">Create User</div>
    <div class="card-body">
      <form method="post" class="row g-2">
        <input type="hidden" name="action" value="create_user">

        <div class="col-md-3">
          <input class="form-control" name="email" placeholder="Email" required>
        </div>

        <div class="col-md-3">
          <input class="form-control" name="full_name" placeholder="Full Name">
        </div>

        <div class="col-md-2">
          <select class="form-select" name="role" required>
            <option value="">Role</option>
            <option value="ADMIN">ADMIN</option>
            <option value="CYBER_USER">CYBER_USER</option>
            <option value="CEIR_USER">CEIR_USER</option>
          </select>
        </div>

        <div class="col-md-2">
          <select class="form-select" name="user_number">
            <option value="">User No</option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
          </select>
        </div>

        <div class="col-md-2">
          <input class="form-control" type="password" name="password" placeholder="Password" required>
        </div>

        <div class="col-12">
          <button class="btn btn-primary" type="submit">Create</button>
        </div>
      </form>
    </div>
  </div>

  <div class="table-responsive">
    <table class="table table-bordered table-striped align-middle">
      <thead class="table-dark">
        <tr>
          <th>ID</th><th>Email</th><th>Name</th><th>Role</th><th>User No</th><th>Active</th><th>Actions</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($users as $u): ?>
        <tr>
          <td><?= (int)$u['user_id'] ?></td>
          <td><?= htmlspecialchars($u['email']) ?></td>
          <td><?= htmlspecialchars($u['full_name'] ?? '') ?></td>
          <td><?= htmlspecialchars($u['role']) ?></td>
          <td><?= htmlspecialchars($u['user_number'] ?? '') ?></td>
          <td><?= (int)$u['is_active'] === 1 ? 'Yes' : 'No' ?></td>
          <td>
            <form method="post" class="d-inline">
              <input type="hidden" name="action" value="toggle_active">
              <input type="hidden" name="user_id" value="<?= (int)$u['user_id'] ?>">
              <input type="hidden" name="is_active" value="<?= (int)$u['is_active'] === 1 ? 0 : 1 ?>">
              <button class="btn btn-sm <?= (int)$u['is_active'] === 1 ? 'btn-secondary' : 'btn-success' ?>" type="submit">
                <?= (int)$u['is_active'] === 1 ? 'Deactivate' : 'Activate' ?>
              </button>
            </form>

            <button class="btn btn-sm btn-warning" type="button"
              onclick="document.getElementById('rp_id').value='<?= (int)$u['user_id'] ?>'; document.getElementById('rp_email').innerText='<?= htmlspecialchars($u['email']) ?>';">
              Reset Password
            </button>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <!-- Simple reset password box -->
  <div class="card mt-4">
    <div class="card-header">Reset Password: <span id="rp_email" class="text-muted"></span></div>
    <div class="card-body">
      <form method="post" class="row g-2">
        <input type="hidden" name="action" value="reset_password">
        <input type="hidden" name="user_id" id="rp_id" value="">
        <div class="col-md-4">
          <input class="form-control" type="password" name="new_password" placeholder="New password" required>
        </div>
        <div class="col-md-2">
          <button class="btn btn-danger" type="submit">Reset</button>
        </div>
        <div class="col-12">
          <div class="form-text">This writes bcrypt hash into password_hash_new.</div>
        </div>
      </form>
    </div>
  </div>

</div>
</body>
</html>
