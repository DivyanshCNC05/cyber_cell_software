<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/tables.php';
require_once __DIR__ . '/../includes/auth.php';

$report = $_GET['report'] ?? '';
$format = $_GET['format'] ?? 'pdf';
$from = $_GET['from'] ?? null;
$to = $_GET['to'] ?? null;

// Permission checks
if ($report === 'cyber') {
    require_role(['ADMIN','CYBER_USER']);
    $list = report_cyber_by_thana($from, $to);
    $title = 'Cyber_Report';
} elseif ($report === 'ceir') {
    require_role(['ADMIN','CEIR_USER']);
    $list = report_ceir_by_month_thana($from, $to);
    $title = 'CEIR_Report';
} elseif ($report === 'admin') {
    require_role(['ADMIN']);
    $list = report_admin_by_thana($from, $to);
    $title = 'Admin_Report';
} else {
    http_response_code(400);
    echo 'Invalid report type';
    exit;
}

// Check composer autoload
$autoload = __DIR__ . '/../vendor/autoload.php';
if (!file_exists($autoload)) {
    // Friendly message with exact command to run
    header('Content-Type: text/html; charset=utf-8');
    echo '<h3>Export dependencies missing</h3>';
    echo '<p>To enable PDF/XLSX exports, run the following in project root:</p>';
    echo '<pre>composer require dompdf/dompdf phpoffice/phpspreadsheet</pre>';
    echo '<p>After installing, retry the export.</p>';
    exit;
}
require_once $autoload;

$filename = $title . '_' . ($from ?: 'all') . '_' . ($to ?: 'all');

if ($format === 'pdf') {
    // Build simple HTML depending on report
    ob_start();
    ?>
    <!doctype html>
    <html>
    <head>
      <meta charset="utf-8">
      <style>
        body { font-family: DejaVu Sans, sans-serif; font-size:12px }
        table { border-collapse: collapse; width:100% }
        th, td { border: 1px solid #333; padding:6px }
        th { background:#eee }
        .right { text-align:right }
      </style>
    </head>
    <body>
    <h3><?= htmlspecialchars($title) ?></h3>
    <p>From: <?= htmlspecialchars($from ?: 'All') ?> To: <?= htmlspecialchars($to ?: 'All') ?></p>
    <table>
      <thead>
      <?php if ($report === 'cyber'): ?>
        <tr>
          <th>S.No.</th>
          <th>Thana</th>
          <th>Total complaint</th>
          <th>Total Acknowledgement/NCRP</th>
          <th>Total fraud amount</th>
          <th>Total hold amount</th>
          <th>Total refund amount</th>
          <th>Total digital arrest</th>
          <th>Total digital amount</th>
          <th>Total mobile number</th>
        </tr>
      <?php elseif ($report === 'ceir'): ?>
        <tr>
          <th>S.No.</th>
          <th>Month</th>
          <th>Thana</th>
          <th>Total Lost mobiles</th>
          <th>Total found mobile</th>
          <th>Block/unblock</th>
        </tr>
      <?php else: ?>
        <tr>
          <th>S.No.</th>
          <th>Thana</th>
          <th>Total complaint number</th>
          <th>Total fraud amount</th>
          <th>Total hold amount</th>
          <th>Total hold amount (%)</th>
        </tr>
      <?php endif; ?>
      </thead>
      <tbody>
      <?php $i=1; foreach ($list as $r): ?>
        <tr>
          <td><?= $i++ ?></td>
          <?php if ($report === 'cyber'): ?>
            <td><?= htmlspecialchars(pretty_thana($r['thana'])) ?></td>
            <td><?= htmlspecialchars($r['complaints'] ?? 0) ?></td>
            <td><?= htmlspecialchars($r['acknowledgements'] ?? 0) ?></td>
            <td class="right"><?= htmlspecialchars(number_format((float)($r['total_fraud'] ?? 0),2)) ?></td>
            <td class="right"><?= htmlspecialchars(number_format((float)($r['total_hold'] ?? 0),2)) ?></td>
            <td class="right"><?= htmlspecialchars(number_format((float)($r['total_refund'] ?? 0),2)) ?></td>
            <td><?= htmlspecialchars($r['total_digital_arrest'] ?? 0) ?></td>
            <td class="right"><?= htmlspecialchars(number_format((float)($r['total_digital_amount'] ?? 0),2)) ?></td>
            <td><?= htmlspecialchars($r['total_mobile_numbers'] ?? 0) ?></td>
          <?php elseif ($report === 'ceir'): ?>
            <td><?= htmlspecialchars($r['ym'] ?? '') ?></td>
            <td><?= htmlspecialchars(pretty_thana($r['thana'])) ?></td>
            <td><?= htmlspecialchars($r['lost'] ?? 0) ?></td>
            <td><?= htmlspecialchars($r['found'] ?? 0) ?></td>
            <td><?= htmlspecialchars($r['blocks'] ?? 0) ?></td>
          <?php else: ?>
            <td><?= htmlspecialchars(pretty_thana($r['thana'])) ?></td>
            <td><?= htmlspecialchars($r['complaint_count'] ?? 0) ?></td>
            <td class="right"><?= htmlspecialchars(number_format((float)($r['total_fraud'] ?? 0),2)) ?></td>
            <td class="right"><?= htmlspecialchars(number_format((float)($r['total_hold'] ?? 0),2)) ?></td>
            <td class="right"><?= htmlspecialchars(number_format((float)($r['hold_percentage'] ?? 0),2)) ?>%</td>
          <?php endif; ?>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
    </body>
    </html>
    <?php
    $html = ob_get_clean();

    // Generate PDF
    $dompdf = new Dompdf\Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'landscape');
    $dompdf->render();
    $outname = $filename . '.pdf';
    $dompdf->stream($outname, ['Attachment' => 1]);
    exit;

} elseif ($format === 'xlsx' || $format === 'xls') {
    // Generate XLSX using PhpSpreadsheet
    $spreadsheet = new PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    if ($report === 'cyber') {
        $headers = ['S.No.','Thana','Total complaint','Total Acknowledgement/NCRP','Total fraud amount','Total hold amount','Total refund amount','Total digital arrest','Total digital amount','Total mobile number'];
    } elseif ($report === 'ceir') {
        $headers = ['S.No.','Month','Thana','Total Lost mobiles','Total found mobile','Block/unblock'];
    } else {
        $headers = ['S.No.','Thana','Total complaint number','Total fraud amount','Total hold amount','Total hold amount (%)'];
    }

    // Header row
    $col = 'A';
    foreach ($headers as $h) {
        $sheet->setCellValue($col . '1', $h);
        $col++;
    }

    $rownum = 2;
    $i = 1;
    foreach ($list as $r) {
        $col = 'A';
        $sheet->setCellValue($col++ . $rownum, $i++);
        if ($report === 'cyber') {
            $sheet->setCellValue($col++ . $rownum, pretty_thana($r['thana']));
            $sheet->setCellValue($col++ . $rownum, (int)$r['complaints']);
            $sheet->setCellValue($col++ . $rownum, (int)$r['acknowledgements']);
            $sheet->setCellValue($col++ . $rownum, (float)$r['total_fraud']);
            $sheet->setCellValue($col++ . $rownum, (float)$r['total_hold']);
            $sheet->setCellValue($col++ . $rownum, (float)$r['total_refund']);
            $sheet->setCellValue($col++ . $rownum, (int)$r['total_digital_arrest']);
            $sheet->setCellValue($col++ . $rownum, (float)$r['total_digital_amount']);
            $sheet->setCellValue($col++ . $rownum, (int)$r['total_mobile_numbers']);
        } elseif ($report === 'ceir') {
            $sheet->setCellValue($col++ . $rownum, $r['ym']);
            $sheet->setCellValue($col++ . $rownum, pretty_thana($r['thana']));
            $sheet->setCellValue($col++ . $rownum, (int)$r['lost']);
            $sheet->setCellValue($col++ . $rownum, (int)$r['found']);
            $sheet->setCellValue($col++ . $rownum, (int)$r['blocks']);
        } else {
            $sheet->setCellValue($col++ . $rownum, pretty_thana($r['thana']));
            $sheet->setCellValue($col++ . $rownum, (int)$r['complaint_count']);
            $sheet->setCellValue($col++ . $rownum, (float)$r['total_fraud']);
            $sheet->setCellValue($col++ . $rownum, (float)$r['total_hold']);
            $sheet->setCellValue($col++ . $rownum, (float)$r['hold_percentage']);
        }
        $rownum++;
    }

    // Auto-size columns
    foreach (range('A', $sheet->getHighestColumn()) as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    $writer = new PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    $outname = $filename . '.xlsx';
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . $outname . '"');
    $writer->save('php://output');
    exit;
}

http_response_code(400);
echo 'Unsupported format';
