<?php
require __DIR__ . '/../../includes/auth.php';
require_role('ADMIN');

$title = "Admin Dashboard";
require __DIR__ . '/../../templates/header.php';
?>

<h3 class="mb-3">Admin Dashboard</h3>

<div class="row g-3">

  <!-- Existing cards -->
  <div class="col-md-4">
    <a class="text-decoration-none" href="./user1.php">
      <div class="card h-100">
        <div class="card-body">
          <h5 class="card-title">User1 Dashboard</h5>
        </div>
      </div>
    </a>
  </div>

  <div class="col-md-4">
    <a class="text-decoration-none" href="./user2.php?as_user=2">
      <div class="card h-100">
        <div class="card-body">
          <h5 class="card-title">User2 Dashboard</h5>
        </div>
      </div>
    </a>
  </div>

  <div class="col-md-4">
    <a class="text-decoration-none" href="./user3.php?as_user=3">
      <div class="card h-100">
        <div class="card-body">
          <h5 class="card-title">User3 Dashboard</h5>
        </div>
      </div>
    </a>
  </div>

  <div class="col-md-4">
    <a class="text-decoration-none" href="./ceir.php">
      <div class="card h-100">
        <div class="card-body">
          <h5 class="card-title">CEIR Dashboard</h5>
        </div>
      </div>
    </a>
  </div>

  <div class="col-md-4">
    <a class="text-decoration-none" href="../admin/report.php">
      <div class="card h-100">
        <div class="card-body">
          <h5 class="card-title">Generate Report</h5>
          <p class="card-text">All thanas</p>
        </div>
      </div>
    </a>
  </div>

  <!-- New admin cards -->
  <div class="col-md-4">
    <a class="text-decoration-none" href="<?= BASE_PATH ?>/admin/users.php">
      <div class="card h-100">
        <div class="card-body">
          <h5 class="card-title">User Management</h5>
          <p class="card-text">Create / activate / reset password</p>
        </div>
      </div>
    </a>
  </div>

  <div class="col-md-4">
    <a class="text-decoration-none" href="<?= BASE_PATH ?>/admin/cyber_report.php">
      <div class="card h-100">
        <div class="card-body">
          <h5 class="card-title">Cyber Report (All Thana)</h5>
          <p class="card-text">Admin view</p>
        </div>
      </div>
    </a>
  </div>

  <div class="col-md-4">
    <a class="text-decoration-none" href="<?= BASE_PATH ?>/admin/ceir_report.php">
      <div class="card h-100">
        <div class="card-body">
          <h5 class="card-title">CEIR Report (All Thana)</h5>
          <p class="card-text">Admin view</p>
        </div>
      </div>
    </a>
  </div>

  <div class="col-md-4">
    <a class="text-decoration-none" href="<?= BASE_PATH ?>/admin/group_cyber_report.php">
      <div class="card h-100">
        <div class="card-body">
          <h5 class="card-title">Cyber Group-wise Report</h5>
          <p class="card-text">CSP/SDOP/DSP + Hold %</p>
        </div>
      </div>
    </a>
  </div>

</div>

<?php require __DIR__ . '/../../templates/footer.php'; ?>
