<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/tables.php';
require_role(['ADMIN','CEIR_USER']);
$tables = get_ceir_tables();
?>
<!doctype html>
<html>
<body>
<h3>CEIR Form</h3>
<form method="post" action="/ceir_form_save.php" enctype="multipart/form-data">
  <label>Thana</label><br>
  <select name="thana_table" required>
    <?php foreach ($tables as $t): ?>
      <option value="<?= sanitize($t) ?>"><?= sanitize(pretty_thana($t)) ?></option>
    <?php endforeach; ?>
  </select><br>

  <label>Name</label><br>
  <input name="name" required><br>
  <label>IMEI</label><br>
  <input name="imei" required><br>
  <button>Save</button>
</form>
</body>
</html>