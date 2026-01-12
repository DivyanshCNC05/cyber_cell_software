<?php
require __DIR__ . '/../../includes/db.php';
require __DIR__ . '/../../includes/auth.php';
require __DIR__ . '/../../includes/thanas.php';
require __DIR__ . '/../../templates/header.php';

// Allow ADMIN to act as a specific user when as_user is provided
if (($_SESSION['role'] ?? '') === 'ADMIN' && isset($_REQUEST['as_user'])) {
  $acting_user = (int)($_REQUEST['as_user']);
} else {
  require_role('CYBER_USER');
  $acting_user = (int)($_SESSION['user_number'] ?? 0);
}

$allowed = cyber_allowed_thanas_for_logged_user();
if (!$allowed) {
  die('No thanas assigned to this user.');
}

// selected thana (default = first allowed)
$thana   = $_GET['thana'] ?? $allowed[0];
$deleted = isset($_GET['deleted']);
$updated = isset($_GET['updated']);

if (!in_array($thana, $allowed, true) || !isset($CYBER_TABLES[$thana])) {
  $thana = $allowed[0];
}

$table = $CYBER_TABLES[$thana];

// optional date filters
$from = $_GET['from'] ?? '';
$to   = $_GET['to'] ?? '';

// NEW: ack search
$ack = trim($_GET['ack'] ?? '');

// Function to format date dd-mm-yyyy
function formatDate($date) {
  if (empty($date) || $date === '0000-00-00') return '';
  return date('d-m-Y', strtotime($date));
}

// Build query
$where  = [];
$params = [];

if ($from !== '') { $where[] = "complaint_date >= :from"; $params[':from'] = $from; }
if ($to !== '')   { $where[] = "complaint_date <= :to";   $params[':to']   = $to; }

// NEW: search by acknowledgement number (partial match)
if ($ack !== '') {
  $where[] = "acknowledgement_number LIKE :ack";
  $params[':ack'] = '%' . $ack . '%';
}

$whereSql = $where ? ("WHERE " . implode(" AND ", $where)) : "";

// List last 200 records
$sql = "SELECT
          sno,
          complaint_number,
          applicant_name,
          acknowledgement_number,
          nature_of_fraud,
          incident_date,
          complaint_date,
          total_fraud,
          hold_date,
          hold_amount,
          court_order,
          cyber_cell,
          fraud_mobile_number,
          fraud_imei_number,
          block_or_unblock,
          digital_arrest,
          digital_amount,
          mobile_number,
          applicant_address
        FROM {$table}
        {$whereSql}
        ORDER BY sno
        LIMIT 200";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Cyber Complaints - <?= htmlspecialchars(cyber_thana_label($thana)) ?></h3>
    <div>
      <a class="btn btn-primary btn-sm" href="<?= BASE_PATH ?>/cyber/add.php?thana=<?= urlencode($thana) ?>&as_user=<?= (int)$acting_user ?>">Add New</a>
      <a class="btn btn-outline-secondary btn-sm" href="<?= BASE_PATH ?>/dashboards/user<?= (int)($_SESSION['user_number'] ?? 1) ?>.php">Back</a>
    </div>
  </div>

  <form class="row g-2 mb-3" method="get">
    <div class="col-md-3">
      <select class="form-select" name="thana">
        <?php foreach ($allowed as $k): ?>
          <option value="<?= htmlspecialchars($k) ?>" <?= $k === $thana ? 'selected' : '' ?>>
            <?= htmlspecialchars(cyber_thana_label($k)) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="col-md-3">
      <input class="form-control" name="ack" value="<?= htmlspecialchars($ack) ?>" placeholder="Ack No (search)">
    </div>

    <div class="col-md-2">
      <input type="date" class="form-control" name="from" value="<?= htmlspecialchars($from) ?>" placeholder="From">
    </div>

    <div class="col-md-2">
      <input type="date" class="form-control" name="to" value="<?= htmlspecialchars($to) ?>" placeholder="To">
    </div>

    <div class="col-md-2 d-grid">
      <button class="btn btn-dark" type="submit">Filter</button>
    </div>

    <!-- keep as_user when admin is acting -->
    <?php if (($_SESSION['role'] ?? '') === 'ADMIN' && !empty($acting_user)): ?>
      <input type="hidden" name="as_user" value="<?= (int)$acting_user ?>">
    <?php endif; ?>
  </form>

  <?php if ($updated): ?><div class="alert alert-success">Record updated.</div><?php endif; ?>
  <?php if ($deleted): ?><div class="alert alert-success">Record deleted.</div><?php endif; ?>

  <div class="table-responsive">
    <table class="table table-striped table-bordered align-middle">
      <thead class="table-dark">
        <tr>
          <th>S.No</th>
          <th>Applicant Name</th>
          <th>Acknowledgement Number</th>
          <th>Applicant Mobile Number</th>
          <th>Applicant Address</th>
          <th>Nature Of Fraud</th>
          <th>Incident Date</th>
          <th>Complaint Date</th>
          <th>Total Fraud</th>
          <th>Hold Date</th>
          <th>Hold Amount</th>
          <th>Cyber Cell</th>
          <th>Court Order</th>
          <th>Fraud Mobile Number</th>
          <th>Fraud IMEI Number</th>
          <th>Block/Unblock</th>
          <th>Digital Arrest</th>
          <th>Digital Amount</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
      <?php if (!$rows): ?>
        <tr><td colspan="19" class="text-center">No records found</td></tr>
      <?php else: ?>
        <?php foreach ($rows as $r): ?>
          <tr>
            <td><?= (int)$r['sno'] ?></td>
            <td><?= htmlspecialchars($r['applicant_name'] ?? '') ?></td>
            <td><?= htmlspecialchars($r['acknowledgement_number'] ?? '') ?></td>
            <td><?= htmlspecialchars($r['mobile_number'] ?? '') ?></td>
            <td><?= htmlspecialchars($r['applicant_address'] ?? '') ?></td>
            <td><?= htmlspecialchars($r['nature_of_fraud'] ?? '') ?></td>
            <td><?= formatDate($r['incident_date']) ?></td>
            <td><?= formatDate($r['complaint_date']) ?></td>
            <td><?= htmlspecialchars($r['total_fraud'] ?? '') ?></td>
            <td><?= formatDate($r['hold_date']) ?></td>
            <td><?= htmlspecialchars($r['hold_amount'] ?? '') ?></td>
            <td><?= htmlspecialchars($r['cyber_cell'] ?? '') ?></td>
            <td><?= htmlspecialchars($r['court_order'] ?? '') ?></td>
            <td><?= htmlspecialchars($r['fraud_mobile_number'] ?? '') ?></td>
            <td><?= htmlspecialchars($r['fraud_imei_number'] ?? '') ?></td>
            <td><?= htmlspecialchars($r['block_or_unblock'] ?? '') ?></td>
            <td><?= htmlspecialchars($r['digital_arrest'] ?? '') ?></td>
            <td><?= htmlspecialchars($r['digital_amount'] ?? '') ?></td>
            <td>
              <a class="btn btn-sm btn-warning"
                 href="<?= BASE_PATH ?>/cyber/edit.php?thana=<?= urlencode($thana) ?>&sno=<?= (int)$r['sno'] ?>&as_user=<?= (int)$acting_user ?>">Edit</a>
              <a class="btn btn-sm btn-danger"
                 href="<?= BASE_PATH ?>/cyber/delete.php?thana=<?= urlencode($thana) ?>&sno=<?= (int)$r['sno'] ?>&as_user=<?= (int)$acting_user ?>"
                 onclick="return confirm('Are you sure you want to delete this record?');">Delete</a>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
      </tbody>
    </table>
  </div>

  <p class="text-muted small mb-0">Showing latest 200 records (sorted by S.No descending).</p>
</div>

<?php require __DIR__ . '/../../templates/footer.php'; ?>
