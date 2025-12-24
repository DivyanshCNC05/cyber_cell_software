<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_role(['ADMIN']);
$user = current_user();
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard - <?= htmlspecialchars(APP_NAME) ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2>Dashboard</h2>
      <div>
        <span class="me-3">Hello, <?= sanitize($user['full_name'] ?? $user['username']) ?></span>
        <a href="/logout.php" class="btn btn-sm btn-outline-secondary">Logout</a>
      </div>
    </div>

    <div class="row g-3">
      <div class="col-sm-6 col-lg-4">
        <div class="card p-3">
          <h5>Complaints</h5>
          <p class="mb-0">Manage complaints</p>
          <a class="stretched-link" href="/complaints/list.php"></a>
        </div>
      </div>

      <div class="col-sm-6 col-lg-4">
        <div class="card p-3">
          <h5>CEIR Forms</h5>
          <p class="mb-0">Manage CEIR physical forms</p>
          <a class="stretched-link" href="/ceir_form.php"></a>
        </div>
      </div>

      <div class="col-sm-6 col-lg-4">
        <div class="card p-3">
          <h5>Reports</h5>
          <p class="mb-0">Run system reports</p>
          <a class="stretched-link" href="/reports_admin.php"></a>
        </div>
      </div>

    </div>
  </div>
</body>
</html>