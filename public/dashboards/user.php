<?php
require __DIR__ . '/../../includes/db.php';
require __DIR__ . '/../../includes/auth.php';
require __DIR__ . '/../../includes/thanas.php';

require_role(['ADMIN', 'CYBER_USER']); // CEIR_USER gets different dashboard

$user_number = (int)($_SESSION['user_number'] ?? 0);
$role = $_SESSION['role'] ?? '';
?>

<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - <?= htmlspecialchars($_SESSION['name'] ?? 'User') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container-fluid py-4">
    
    <!-- Header -->
    <div class="row mb-4">
        <div class="col">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2><i class="fas fa-tachometer-alt text-primary me-2"></i>
                        <?= $role === 'ADMIN' ? 'Admin Dashboard' : 'Cyber Dashboard' ?>
                    </h2>
                    <small class="text-muted">
                        Welcome, <?= htmlspecialchars($_SESSION['name'] ?? 'User') ?> 
                        (<?= htmlspecialchars($role) ?> <?= $role === 'CYBER_USER' ? "#$user_number" : '' ?>)
                    </small>
                </div>
                <div>
                    <a href="<?= BASE_PATH ?>/logout.php" class="btn btn-outline-danger">
                        <i class="fas fa-sign-out-alt me-1"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </div>

    <?php if ($role === 'ADMIN'): ?>
        <!-- ADMIN DASHBOARD -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <a href="<?= BASE_PATH ?>/admin/users.php" class="text-decoration-none">
                    <div class="card bg-primary text-white">
                        <div class="card-body text-center">
                            <i class="fas fa-users fa-2x mb-2"></i>
                            <h3>User Management</h3>
                            <small>Manage users & roles</small>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3 mb-3">
                <a href="<?= BASE_PATH ?>/cyber/report.php" class="text-decoration-none">
                    <div class="card bg-success text-white">
                        <div class="card-body text-center">
                            <i class="fas fa-chart-bar fa-2x mb-2"></i>
                            <h3>Cyber Reports</h3>
                            <small>Thana-wise analytics</small>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-secondary text-white">
                    <div class="card-body text-center">
                        <i class="fas fa-database fa-2x mb-2"></i>
                        <h3>Database</h3>
                        <small>Manage tables</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-info text-white">
                    <div class="card-body text-center">
                        <i class="fas fa-cog fa-2x mb-2"></i>
                        <h3>Settings</h3>
                        <small>System config</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Admin Quick Stats -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5>System Overview</h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-3">
                                <h4 class="text-primary">15</h4>
                                <small>Total Users</small>
                            </div>
                            <div class="col-md-3">
                                <h4 class="text-success">127</h4>
                                <small>Cyber Complaints</small>
                            </div>
                            <div class="col-md-3">
                                <h4 class="text-warning">₹4.2 Cr</h4>
                                <small>Total Fraud</small>
                            </div>
                            <div class="col-md-3">
                                <h4 class="text-danger">89%</h4>
                                <small>Active Users</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <?php elseif ($role === 'CYBER_USER'): ?>
        <?php 
        $allowed = cyber_allowed_thanas_for_logged_user();
        $thana_labels = array_map('cyber_thana_label', $allowed);
        ?>

        <!-- CYBER USER DASHBOARD -->
        <div class="row mb-4">
            <?php foreach ($allowed as $thana): ?>
            <div class="col-md-6 col-lg-3 mb-3">
                <a href="<?= BASE_PATH ?>/cyber/list.php?thana=<?= urlencode($thana) ?>" class="text-decoration-none">
                    <div class="card h-100 border-0 shadow-sm hover-shadow">
                        <div class="card-body text-center p-4">
                            <div class="bg-primary bg-opacity-10 p-3 rounded-circle d-inline-block mb-3" style="width:80px;height:80px;">
                                <i class="fas fa-building fa-2x text-primary"></i>
                            </div>
                            <h5><?= htmlspecialchars(cyber_thana_label($thana)) ?></h5>
                            <small class="text-muted">Manage complaints</small>
                            <div class="mt-3">
                                <i class="fas fa-arrow-right text-primary"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>

            <!-- Quick Actions -->
            <div class="col-md-6 col-lg-3 mb-3">
                <a href="<?= BASE_PATH ?>/cyber/report.php" class="text-decoration-none">
                    <div class="card h-100 bg-warning text-dark border-0 shadow-sm hover-shadow">
                        <div class="card-body text-center p-4">
                            <div class="bg-warning bg-opacity-20 p-3 rounded-circle d-inline-block mb-3" style="width:80px;height:80px;">
                                <i class="fas fa-chart-pie fa-2x text-warning"></i>
                            </div>
                            <h5>Reports</h5>
                            <small class="text-muted">Analytics & Export</small>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Recent Activity (dummy) -->
            <div class="col-md-6 col-lg-3 mb-3">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h6 class="text-muted mb-3">Recent Activity</h6>
                        <div class="d-flex align-items-center mb-2">
                            <div class="bg-success bg-opacity-20 p-2 rounded-circle me-3">
                                <i class="fas fa-plus fa-sm text-success"></i>
                            </div>
                            <div>
                                <small>New complaint added</small>
                                <div class="text-muted small">2 mins ago</div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="bg-primary bg-opacity-20 p-2 rounded-circle me-3">
                                <i class="fas fa-download fa-sm text-primary"></i>
                            </div>
                            <div>
                                <small>Report exported</small>
                                <div class="text-muted small">1 hour ago</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card text-center bg-primary text-white">
                    <div class="card-body">
                        <i class="fas fa-file-alt fa-3x mb-3 opacity-75"></i>
                        <h3>247</h3>
                        <small>Total Complaints</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card text-center bg-success text-white">
                    <div class="card-body">
                        <i class="fas fa-rupee-sign fa-3x mb-3 opacity-75"></i>
                        <h3>₹12.4 Cr</h3>
                        <small>Total Fraud</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card text-center bg-info text-white">
                    <div class="card-body">
                        <i class="fas fa-shield-alt fa-3x mb-3 opacity-75"></i>
                        <h3>89</h3>
                        <small>Digital Arrests</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card text-center bg-warning text-dark">
                    <div class="card-body">
                        <i class="fas fa-phone fa-3x mb-3 opacity-75"></i>
                        <h3>156</h3>
                        <small>Mobile Numbers</small>
                    </div>
                </div>
            </div>
        </div>

    <?php endif; ?>

</div>

<style>
.hover-shadow {
    transition: all 0.3s ease;
}
.hover-shadow:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.1) !important;
}
.card {
    transition: all 0.3s ease;
}
</style>

</body>
</html>
