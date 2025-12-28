<?php
require __DIR__ . '/../../includes/db.php';
require __DIR__ . '/../../includes/auth.php';
require __DIR__ . '/../../includes/ceir_thanas.php';

// Allow ADMIN to access CEIR pages
if (($_SESSION['role'] ?? '') === 'ADMIN') {
  // admin allowed
} else {
  require_role('CEIR_USER');
}

function p($k, $d='') { return trim($_POST[$k] ?? $d); }

$thana   = $_GET['thana'] ?? ($_POST['thana'] ?? '');
$ceir_id = isset($_GET['ceir_id']) ? (int)$_GET['ceir_id'] : (isset($_POST['ceir_id']) ? (int)$_POST['ceir_id'] : 0);

if (!isset($CEIR_TABLES[$thana]) || $ceir_id <= 0) {
  die('Invalid request.');
}

$table = $CEIR_TABLES[$thana];

function validate_pdf_upload(array $file): array {
  if (!isset($file['error'])) return [false, 'Upload error'];
  if ($file['error'] === UPLOAD_ERR_NO_FILE) return [true, 'NO_FILE']; // optional upload [web:424]
  if ($file['error'] !== UPLOAD_ERR_OK) return [false, 'File upload failed'];

  $maxBytes = 5 * 1024 * 1024;
  if (($file['size'] ?? 0) > $maxBytes) return [false, 'PDF must be <= 5MB'];

  $finfo = new finfo(FILEINFO_MIME_TYPE);
  $mime = $finfo->file($file['tmp_name']);
  if ($mime !== 'application/pdf') return [false, 'Only PDF allowed'];

  return [true, 'OK'];
}

/* Fetch row */
$stmt = $pdo->prepare("SELECT * FROM {$table} WHERE ceir_id = :id LIMIT 1");
$stmt->execute([':id' => $ceir_id]);
$row = $stmt->fetch();
if (!$row) die('Record not found.');

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $name = p('name');
  $father_name = p('father_name');
  $address = p('address');
  $date_of_complaint = p('date_of_complaint');
  $mobile_number = p('mobile_number');
  $imei = p('imei');
  $lost_found = p('lost_found');
  $block_unblock = p('block_unblock');

  if ($name === '' || $date_of_complaint === '' || $imei === '' || $lost_found === '' || $block_unblock === '') {
    $error = 'Please fill all required fields';
  } else {

    // keep old pdf by default
    $pdfPathDb = $row['pdf_attach'] ?? null;

    // optional replace PDF
    if (isset($_FILES['pdf'])) {
      [$ok, $msg] = validate_pdf_upload($_FILES['pdf']);
      if (!$ok) {
        $error = $msg;
      } elseif ($msg === 'OK') {

        $uploadDirFs = __DIR__ . '/../uploads/ceir_pdfs/';
        $uploadDirDb = '/uploads/ceir_pdfs/';

        if (!is_dir($uploadDirFs)) mkdir($uploadDirFs, 0775, true);

        $safeBase = preg_replace('/[^a-zA-Z0-9_-]/', '_', $imei);
        $newName = 'ceir_' . $safeBase . '_' . time() . '.pdf';

        $destFs = $uploadDirFs . $newName;

        if (!move_uploaded_file($_FILES['pdf']['tmp_name'], $destFs)) { // [web:261]
          $error = 'Could not save PDF on server';
        } else {
          $pdfPathDb = $uploadDirDb . $newName;

          // OPTIONAL: delete old pdf file when replaced (recommended)
          if (!empty($row['pdf_attach'])) {
            $oldFs = realpath(__DIR__ . '/..' . $row['pdf_attach']);
            $base  = realpath(__DIR__ . '/../uploads/ceir_pdfs');
            if ($oldFs && $base && strpos($oldFs, $base) === 0 && is_file($oldFs)) {
              @unlink($oldFs);
            }
          }
        }
      }
      // if NO_FILE => keep existing
    }

    if ($error === '') {
      $sql = "UPDATE {$table} SET
                name = :name,
                father_name = :father_name,
                address = :address,
                date_of_complaint = :date_of_complaint,
                mobile_number = :mobile_number,
                imei = :imei,
                lost_found = :lost_found,
                block_unblock = :block_unblock,
                pdf_attach = :pdf_attach
              WHERE ceir_id = :id
              LIMIT 1";

      $stmt = $pdo->prepare($sql);
      $stmt->execute([
        ':name' => $name,
        ':father_name' => ($father_name !== '' ? $father_name : null),
        ':address' => ($address !== '' ? $address : null),
        ':date_of_complaint' => $date_of_complaint,
        ':mobile_number' => ($mobile_number !== '' ? $mobile_number : null),
        ':imei' => $imei,
        ':lost_found' => $lost_found,
        ':block_unblock' => $block_unblock,
        ':pdf_attach' => $pdfPathDb,
        ':id' => $ceir_id,
      ]);

      $q = 'thana=' . urlencode($thana) . '&updated=1';
      header('Location: ' . BASE_PATH . '/ceir/list.php?' . $q);
      exit; 
    }
  }

  // if error, keep posted values in $row to re-render form
  $row = array_merge($row, [
    'name' => $name,
    'father_name' => $father_name,
    'address' => $address,
    'date_of_complaint' => $date_of_complaint,
    'mobile_number' => $mobile_number,
    'imei' => $imei,
    'lost_found' => $lost_found,
    'block_unblock' => $block_unblock,
  ]);
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Edit CEIR</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Edit CEIR - ID <?= (int)$ceir_id ?></h3>
    <a class="btn btn-outline-secondary btn-sm" href="<?= BASE_PATH ?>/ceir/list.php?thana=<?= urlencode($thana) ?>">Back</a>
  </div>

  <?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>

  <div class="card mb-3">
    <div class="card-body">
      <?php if (!empty($row['pdf_attach'])): ?>
        <a class="btn btn-sm btn-info" target="_blank"
           href="<?= BASE_PATH ?>/ceir/view_pdf.php?thana=<?= urlencode($thana) ?>&ceir_id=<?= (int)$ceir_id ?>">
          View current PDF
        </a>
      <?php else: ?>
        <span class="text-muted">No PDF attached</span>
      <?php endif; ?>
    </div>
  </div>

  <form method="post" enctype="multipart/form-data" class="row g-3">
    <input type="hidden" name="thana" value="<?= htmlspecialchars($thana) ?>">
    <input type="hidden" name="ceir_id" value="<?= (int)$ceir_id ?>">

    <div class="col-md-4">
      <label class="form-label">Name *</label>
      <input class="form-control" name="name" required value="<?= htmlspecialchars($row['name'] ?? '') ?>">
    </div>

    <div class="col-md-4">
      <label class="form-label">Father Name</label>
      <input class="form-control" name="father_name" value="<?= htmlspecialchars($row['father_name'] ?? '') ?>">
    </div>

    <div class="col-md-4">
      <label class="form-label">Date of Complaint *</label>
      <input type="date" class="form-control" name="date_of_complaint" required value="<?= htmlspecialchars($row['date_of_complaint'] ?? '') ?>">
    </div>

    <div class="col-12">
      <label class="form-label">Address</label>
      <textarea class="form-control" name="address" rows="2"><?= htmlspecialchars($row['address'] ?? '') ?></textarea>
    </div>

    <div class="col-md-4">
      <label class="form-label">Mobile Number</label>
      <input class="form-control" name="mobile_number" value="<?= htmlspecialchars($row['mobile_number'] ?? '') ?>">
    </div>

    <div class="col-md-4">
      <label class="form-label">IMEI *</label>
      <input class="form-control" name="imei" required value="<?= htmlspecialchars($row['imei'] ?? '') ?>">
    </div>

    <div class="col-md-4">
      <label class="form-label">Replace PDF (optional)</label>
      <input type="file" class="form-control" name="pdf" accept="application/pdf">
      <div class="form-text">Only PDF, max 5MB. Leave empty to keep old PDF.</div>
    </div>

    <div class="col-md-4">
      <label class="form-label">Lost/Found *</label>
      <select name="lost_found" class="form-select" required>
        <option value="">Select</option>
        <option value="LOST"  <?= (($row['lost_found'] ?? '') === 'LOST') ? 'selected' : '' ?>>LOST</option>
        <option value="FOUND" <?= (($row['lost_found'] ?? '') === 'FOUND') ? 'selected' : '' ?>>FOUND</option>
      </select>
    </div>

    <div class="col-md-4">
      <label class="form-label">Block/Unblock *</label>
      <select name="block_unblock" class="form-select" required>
        <option value="">Select</option>
        <option value="BLOCK"   <?= (($row['block_unblock'] ?? '') === 'BLOCK') ? 'selected' : '' ?>>BLOCK</option>
        <option value="UNBLOCK" <?= (($row['block_unblock'] ?? '') === 'UNBLOCK') ? 'selected' : '' ?>>UNBLOCK</option>
      </select>
    </div>

    <div class="col-12">
      <button class="btn btn-primary" type="submit">Update</button>
      <a class="btn btn-secondary" href="<?= BASE_PATH ?>/ceir/list.php?thana=<?= urlencode($thana) ?>">Cancel</a>
    </div>
  </form>

</div>
</body>
</html>
