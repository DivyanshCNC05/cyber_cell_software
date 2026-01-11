<?php
require __DIR__ . '/../../includes/auth.php';
require_role('ADMIN');

$title = "Admin Dashboard";
require __DIR__ . '/../../templates/header.php';
?>

<!-- Hero -->
<div class="admin-hero p-3 mb-4 rounded-3 text-white">
  <div class="d-flex flex-column flex-md-row justify-content-between align-items-start">
    <div>
      <h3 class="mb-1 admin-dash">Admin Dashboard</h3>
      <div class="text-light small">Welcome back<?php if (!empty($_SESSION['name'])): ?>, <?= htmlspecialchars($_SESSION['name']) ?><?php endif; ?> — manage users and reports from here.</div>
    </div>
    <div class="mt-3 mt-md-0">
      <a class="btn btn-outline-light btn-sm me-2" href="<?= BASE_PATH ?>/admin/users.php">User Management</a>
      <a class="btn btn-light btn-sm" href="<?= BASE_PATH ?>/admin/links_management.php">Links Management</a>
    </div>
  </div>
</div>

<!-- Cards grid -->
<div class="row g-3">

  <!-- Existing cards -->
<div class="col-sm-6 col-md-4">
   <a class="text-decoration-none" href="./user1.php?as_user=1">
      <div class="card h-100 admin-card card-hover">
        <div class="card-body d-flex align-items-start">
          <div class="card-icon bg-primary me-3">1</div>
          <div>
            <h5 class="card-title mb-1">Gitika's Dashboard</h5>
            <p class="card-text small text-muted mb-0">Manage Gitika Dashboard</p>
          </div>
        </div>
      </div>
    </a>
  </div>

  <div class="col-sm-6 col-md-4">
    <a class="text-decoration-none" href="./user2.php?as_user=2">
      <div class="card h-100 admin-card card-hover">
        <div class="card-body d-flex align-items-start">
          <div class="card-icon bg-info me-3">2</div>
          <div>
            <h5 class="card-title mb-1">Arti's Dashboard</h5>
            <p class="card-text small text-muted mb-0">Manage Arti's Dashboard</p>
          </div>
        </div>
      </div>
    </a>
  </div>

  <div class="col-sm-6 col-md-4">
    <a class="text-decoration-none" href="./user3.php?as_user=3">
      <div class="card h-100 admin-card card-hover">
        <div class="card-body d-flex align-items-start">
          <div class="card-icon bg-success me-3">3</div>
          <div>
            <h5 class="card-title mb-1">Nisha's Dashboard</h5>
            <p class="card-text small text-muted mb-0">Manage Nisha's Dashboard</p>
          </div>
        </div>
      </div>
    </a>
  </div>

  <div class="col-sm-6 col-md-4">
    <a class="text-decoration-none" href="<?= BASE_PATH ?>/admin/cyber_report.php">
      <div class="card h-100 admin-card card-hover">
        <div class="card-body d-flex align-items-start">
          <div class="card-icon bg-primary me-3">📊</div>
          <div>
            <h5 class="card-title mb-1">Cyber Report (All Thana)</h5>
            <p class="card-text small text-muted mb-0">Hold Table</p>
          </div>
        </div>
      </div>
    </a>
  </div>

  <div class="col-sm-6 col-md-4">
    <a class="text-decoration-none" href="<?= BASE_PATH ?>/admin/cyber_report_2.php">
      <div class="card h-100 admin-card card-hover">
        <div class="card-body d-flex align-items-start">
          <div class="card-icon bg-primary me-3">📊</div>
          <div>
            <h5 class="card-title mb-1">Cyber Report (All Thana)</h5>
            <p class="card-text small text-muted mb-0">Refund Table</p>
          </div>
        </div>
      </div>
    </a>
  </div>

  <div class="col-sm-6 col-md-4">
    <a class="text-decoration-none" href="<?= BASE_PATH ?>/admin/group_cyber_report.php">
      <div class="card h-100 admin-card card-hover">
        <div class="card-body d-flex align-items-start">
          <div class="card-icon bg-success me-3">💻</div>
          <div>
            <h5 class="card-title mb-1">Cyber Zone-wise Report</h5>
            <p class="card-text small text-muted mb-0">Hold Table</p>
          </div>
        </div>
      </div>
    </a>
  </div>

  <div class="col-sm-6 col-md-4">
    <a class="text-decoration-none" href="<?= BASE_PATH ?>/admin/group_cyber_report_2.php">
      <div class="card h-100 admin-card card-hover">
        <div class="card-body d-flex align-items-start">
          <div class="card-icon bg-success me-3">💻</div>
          <div>
            <h5 class="card-title mb-1">Cyber Zone-wise Report</h5>
            <p class="card-text small text-muted mb-0">Refund Table</p>
          </div>
        </div>
      </div>
    </a>
  </div>

  <div class="col-sm-6 col-md-4">
    <a class="text-decoration-none" href="./ceir.php">
      <div class="card h-100 admin-card card-hover">
        <div class="card-body d-flex align-items-start">
          <div class="card-icon bg-warning me-3">C</div>
          <div>
            <h5 class="card-title mb-1">CEIR Dashboard</h5>
            <p class="card-text small text-muted mb-0">CEIR data and operations</p>
          </div>
        </div>
      </div>
    </a>
  </div>

  <!-- <div class="col-sm-6 col-md-4">
    <a class="text-decoration-none" href="#">
      <div class="card h-100 admin-card card-hover">
        <div class="card-body d-flex align-items-start">
          <div class="card-icon bg-dark me-3">R</div>
          <div>
            <h5 class="card-title mb-1">Generate Report</h5>
            <p class="card-text small text-muted mb-0">Comming soon</p>
          </div>
        </div>
      </div>
    </a>
  </div> -->

  <!-- New admin cards -->
  <!-- <div class="col-sm-6 col-md-4">
    <a class="text-decoration-none" href="<?= BASE_PATH ?>/admin/users.php">
      <div class="card h-100 admin-card card-hover">
        <div class="card-body d-flex align-items-start">
          <div class="card-icon bg-secondary me-3">U</div>
          <div>
            <h5 class="card-title mb-1">User Management</h5>
            <p class="card-text small text-muted mb-0">Manage users and their permissions</p>
          </div>
        </div>
      </div>
    </a>
  </div> -->

  <div class="col-sm-6 col-md-4">
    <a class="text-decoration-none" href="<?= BASE_PATH ?>/admin/ceir_report.php">
      <div class="card h-100 admin-card card-hover">
        <div class="card-body d-flex align-items-start">
          <div class="card-icon bg-info me-3">🗂️</div>
          <div>
            <h5 class="card-title mb-1">CEIR Report (All Thana)</h5>
            <p class="card-text small text-muted mb-0">Admin view</p>
          </div>
        </div>
      </div>
    </a>
  </div>

</div>

<?php require __DIR__ . '/../../templates/footer.php'; ?>
