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
  <title><?= htmlspecialchars(APP_NAME) ?> - Admin Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container-fluid">
    <a class="navbar-brand" href="#"><?= htmlspecialchars(APP_NAME) ?> - Admin</a>
    <div class="d-flex">
      <span class="navbar-text me-3"><?= sanitize($user['full_name'] ?? '') ?></span>
      <a class="btn btn-light" href="/logout.php">Logout</a>
    </div>
  </div>
</nav>
<div class="container py-5">
  <h3>Admin Dashboard</h3>
  <div class="row gy-3 mt-3">
    <div class="col-md-4">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">User1 Dashboard</h5>
          <p class="card-text">Quick link to User1 (Cyber) dashboard</p>
          <a href="/cyber_dashboard.php" class="btn btn-primary">Open</a>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">User2 Dashboard</h5>
          <p class="card-text">Quick link to User2 (Cyber) dashboard</p>
          <a href="/cyber_dashboard.php" class="btn btn-primary">Open</a>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">User3 Dashboard</h5>
          <p class="card-text">Quick link to User3 (Cyber) dashboard</p>
          <a href="/cyber_dashboard.php" class="btn btn-primary">Open</a>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">CEIR Dashboard</h5>
          <p class="card-text">Open CEIR dashboard</p>
          <a href="/ceir_dashboard.php" class="btn btn-primary">Open</a>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Generate Report</h5>
          <p class="card-text">Open the admin report generator</p>
          <a href="/reports_admin.php" class="btn btn-primary">Open</a>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>