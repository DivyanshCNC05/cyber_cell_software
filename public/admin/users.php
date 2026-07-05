<?php
require __DIR__ . '/../../includes/db.php';
require __DIR__ . '/../../templates/header.php';

$msg = '';
$err = '';

function p($k, $d=''){ return trim($_POST[$k] ?? $d); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $action  = p('action');
  $user_id = (int)p('user_id', '0');

  // Toggle Active
  if ($action === 'toggle_active') {
    $is_active = (int)p('is_active', '0');
    $stmt = $pdo->prepare("UPDATE login_credentials SET is_active = :a WHERE user_id = :id LIMIT 1");
    $stmt->execute([':a' => $is_active, ':id' => $user_id]);
    $msg = 'User status updated.';
  }

  // Reset Password
  if ($action === 'reset_password') {
    $newpass = p('new_password');
    if (strlen($newpass) < 4) {
      $err = 'Password too short.';
    } else {
      $hash = password_hash($newpass, PASSWORD_DEFAULT);
      $stmt = $pdo->prepare("UPDATE login_credentials SET password_hash_new = :h WHERE user_id = :id LIMIT 1");
      $stmt->execute([':h' => $hash, ':id' => $user_id]);
      $msg = 'Password reset done.';
    }
  }

  // Create User (uncomment if needed)
  if ($action === 'create_user') {
    $email = p('email');
    $full_name = p('full_name');
    $role = p('role');
    $user_number = p('user_number') !== '' ? (int)p('user_number') : null;
    $pass = p('password');

    if ($email === '' || $role === '' || $pass === '') {
      $err = 'Email, role, password are required.';
    } else {
      if ($role !== 'CYBER_USER') $user_number = null;
      $hash = password_hash($pass, PASSWORD_DEFAULT);

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

  // Update User (EDIT)
  if ($action === 'update_user') {
    $email = p('email');
    $full_name = p('full_name');
    $role = p('role');
    $user_number = p('user_number') !== '' ? (int)p('user_number') : null;
    $is_active = (int)p('is_active', '1');

    if ($user_id <= 0) {
      $err = 'Invalid user.';
    } elseif ($email === '' || $role === '') {
      $err = 'Email and role are required.';
    } else {
      if ($role !== 'CYBER_USER') $user_number = null;

      $stmt = $pdo->prepare("UPDATE login_credentials
        SET email = :email,
            full_name = :full_name,
            role = :role,
            user_number = :user_number,
            is_active = :is_active
        WHERE user_id = :id
        LIMIT 1");
      $stmt->execute([
        ':email' => $email,
        ':full_name' => ($full_name !== '' ? $full_name : null),
        ':role' => $role,
        ':user_number' => $user_number,
        ':is_active' => $is_active,
        ':id' => $user_id,
      ]);
      $msg = 'User updated.';
    }
  }
}

// list users
$users = $pdo->query("SELECT user_id, email, role, full_name, is_active, user_number, created_at
                      FROM login_credentials
                      ORDER BY user_id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin - Users</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">User Management</h3>
    <a class="btn btn-outline-secondary btn-sm" href="<?= BASE_PATH ?>/dashboards/admin.php">Back</a>
  </div>

  <?php if ($err): ?><div class="alert alert-danger"><?= htmlspecialchars($err) ?></div><?php endif; ?>
  <?php if ($msg): ?><div class="alert alert-success"><?= htmlspecialchars($msg) ?></div><?php endif; ?>

  <!-- Users Table -->
  <div class="table-responsive">
    <table class="table table-bordered table-striped align-middle">
      <thead class="table-dark">
        <tr>
          <th>ID</th>
          <th>Email</th>
          <th>Name</th>
          <th>Role</th>
          <th>User No</th>
          <th>Active</th>
          <th style="min-width:280px;">Actions</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($users as $u): ?>
        <tr>
          <td><?= (int)$u['user_id'] ?></td>
          <td><?= htmlspecialchars($u['email']) ?></td>
          <td><?= htmlspecialchars($u['full_name'] ?? '') ?></td>
          <td><span class="badge bg-<?= $u['role'] === 'ADMIN' ? 'primary' : ($u['role'] === 'CYBER_USER' ? 'success' : 'info') ?>"><?= htmlspecialchars($u['role']) ?></span></td>
          <td><?= htmlspecialchars($u['user_number'] ?? '') ?></td>
          <td><span class="badge bg-<?= (int)$u['is_active'] === 1 ? 'success' : 'danger' ?>"><?= (int)$u['is_active'] === 1 ? 'Yes' : 'No' ?></span></td>
          <td>
            <!-- Toggle active -->
            <form method="post" class="d-inline">
              <input type="hidden" name="action" value="toggle_active">
              <input type="hidden" name="user_id" value="<?= (int)$u['user_id'] ?>">
              <input type="hidden" name="is_active" value="<?= (int)$u['is_active'] === 1 ? 0 : 1 ?>">
              <button class="btn btn-sm <?= (int)$u['is_active'] === 1 ? 'btn-secondary' : 'btn-success' ?>" type="submit">
                <?= (int)$u['is_active'] === 1 ? 'Deactivate' : 'Activate' ?>
              </button>
            </form>

            <!-- Edit (WORKING) -->
            <button type="button" class="btn btn-sm btn-info text-white"
              data-user-id="<?= (int)$u['user_id'] ?>"
              data-email="<?= htmlspecialchars($u['email'], ENT_QUOTES) ?>"
              data-full-name="<?= htmlspecialchars($u['full_name'] ?? '', ENT_QUOTES) ?>"
              data-role="<?= htmlspecialchars($u['role'], ENT_QUOTES) ?>"
              data-user-number="<?= htmlspecialchars((string)($u['user_number'] ?? ''), ENT_QUOTES) ?>"
              data-is-active="<?= (int)$u['is_active'] ?>"
              onclick="fillEditUser(this)">
              Edit
            </button>

            <!-- Reset Password (WORKING - data-* attributes) -->
            <button type="button" class="btn btn-sm btn-warning"
              data-user-id="<?= (int)$u['user_id'] ?>"
              data-email="<?= htmlspecialchars($u['email'], ENT_QUOTES) ?>"
              onclick="fillResetPassword(this)">
              Reset Password
            </button>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <!-- Edit User -->
  <div class="card mt-4" id="editBox">
    <div class="card-header d-flex justify-content-between align-items-center">
      <span>Edit User</span>
      <button class="btn btn-sm btn-outline-secondary" onclick="clearEditUser()">Clear</button>
    </div>
    <div class="card-body">
      <form method="post" class="row g-2">
        <input type="hidden" name="action" value="update_user">
        <input type="hidden" name="user_id" id="eu_id" value="">

        <div class="col-md-3">
          <label class="form-label">Email</label>
          <input class="form-control" name="email" id="eu_email" required>
        </div>

        <div class="col-md-3">
          <label class="form-label">Full Name</label>
          <input class="form-control" name="full_name" id="eu_full_name">
        </div>

        <div class="col-md-2">
          <label class="form-label">Role</label>
          <select class="form-select" name="role" id="eu_role" required>
            <option value="ADMIN">ADMIN</option>
            <option value="CYBER_USER">CYBER_USER</option>
            <option value="CEIR_USER">CEIR_USER</option>
          </select>
        </div>

        <div class="col-md-2">
          <label class="form-label">User No</label>
          <select class="form-select" name="user_number" id="eu_user_number">
            <option value="">None</option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
          </select>
        </div>

        <div class="col-md-2">
          <label class="form-label">Active</label>
          <select class="form-select" name="is_active" id="eu_is_active">
            <option value="1">Yes</option>
            <option value="0">No</option>
          </select>
        </div>

        <div class="col-12">
          <button class="btn btn-success" type="submit">Save Changes</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Reset Password -->
  <div class="card mt-4" id="resetBox">
    <div class="card-header">
      Reset Password: <span id="rp_email" class="text-muted fw-bold"></span>
    </div>
    <div class="card-body">
      <form method="post" class="row g-2">
        <input type="hidden" name="action" value="reset_password">
        <input type="hidden" name="user_id" id="rp_id" value="">
        <div class="col-md-4">
          <input class="form-control" type="password" name="new_password" placeholder="New password (min 4 chars)" required>
        </div>
        <div class="col-md-2">
          <button class="btn btn-danger" type="submit">Reset Password</button>
        </div>
        <div class="col-12">
          <div class="form-text">Writes bcrypt hash to password_hash_new field.</div>
        </div>
      </form>
    </div>
  </div>

</div>

<script>
function fillEditUser(btn) {
  document.getElementById('eu_id').value = btn.dataset.userId || '';
  document.getElementById('eu_email').value = btn.dataset.email || '';
  document.getElementById('eu_full_name').value = btn.dataset.fullName || '';
  document.getElementById('eu_role').value = btn.dataset.role || 'ADMIN';
  document.getElementById('eu_user_number').value = btn.dataset.userNumber || '';
  document.getElementById('eu_is_active').value = btn.dataset.isActive || '1';
  document.getElementById('editBox').scrollIntoView({behavior: 'smooth'});
}

function fillResetPassword(btn) {
  document.getElementById('rp_id').value = btn.dataset.userId || '';
  document.getElementById('rp_email').textContent = btn.dataset.email || '';
  document.getElementById('resetBox').scrollIntoView({behavior: 'smooth'});
}

function clearEditUser() {
  document.getElementById('eu_id').value = '';
  document.getElementById('eu_email').value = '';
  document.getElementById('eu_full_name').value = '';
  document.getElementById('eu_role').value = 'ADMIN';
  document.getElementById('eu_user_number').value = '';
  document.getElementById('eu_is_active').value = '1';
}
</script>

</body>
</html>
