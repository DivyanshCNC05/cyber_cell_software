<?php
require __DIR__ . '/../../includes/auth.php';
require_cyber_user_number(3);
// debug log
error_log('DEBUG: user3.php requested, REQUEST_URI=' . ($_SERVER['REQUEST_URI'] ?? '')); 
$title = "User3 Dashboard";
require __DIR__ . '/../../templates/header.php';
echo '<!-- DEBUG: user3_requested: ' . ($_SERVER['REQUEST_URI'] ?? '') . ' -->';
?> 

<h3 class="mb-3">User-3 Dashboard</h3>

<div class="row g-3">
  <div class="col-md-6">
    <a class="text-decoration-none" href="<?= BASE_PATH ?>/cyber/add.php?as_user=3">
      <div class="card h-100"><div class="card-body">
        <h5 class="card-title">Add new cyber complaint</h5>
        <!-- <p class="card-text">Add new cyber complaint</p> -->
      </div></div>
    </a>
  </div>

  <div class="col-md-6">
    <a class="text-decoration-none" href="<?= BASE_PATH ?>/cyber/list.php?as_user=3">
      <div class="card h-100"><div class="card-body">
        <h5 class="card-title">Update and search complaint</h5>
        <!-- <p class="card-text">Search and update complaint</p> -->
      </div></div>
    </a>
  </div>

  <div class="col-md-6">
    <a class="text-decoration-none" href="<?= BASE_PATH ?>/cyber/list.php?as_user=3">
      <div class="card h-100"><div class="card-body">
        <h5 class="card-title">Delete Complaint</h5>
        <!-- <p class="card-text">Search and delete complaint</p> -->
      </div></div>
    </a>
  </div>

  <div class="col-md-6">
    <a class="text-decoration-none" href="<?= BASE_PATH ?>/cyber/report.php?as_user=3">
      <div class="card h-100"><div class="card-body">
        <h5 class="card-title">Generate Report (From date to date)</h5>
        <!-- <p class="card-text">From date to date</p> -->
      </div></div>
    </a>
  </div>
</div>

<!-- Footer -->
