<?php
require_once __DIR__ . '/db.php';

function report_cyber_summary($from = null, $to = null) {
    $pdo = getPDO();
    $sql = 'SELECT COUNT(*) AS total_complaints, SUM(total_fraud_amount) AS total_amount FROM complaints WHERE 1=1';
    $params = [];
    if ($from) { $sql .= ' AND complaint_date >= ?'; $params[] = $from; }
    if ($to) { $sql .= ' AND complaint_date <= ?'; $params[] = $to; }
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetch();
}

function report_ceir_summary($from = null, $to = null) {
    $pdo = getPDO();
    $sql = 'SELECT COUNT(*) AS total_forms, SUM(IF(action = "BLOCK",1,0)) AS total_block_actions FROM ceir_physical_forms WHERE 1=1';
    $params = [];
    if ($from) { $sql .= ' AND complaint_date >= ?'; $params[] = $from; }
    if ($to) { $sql .= ' AND complaint_date <= ?'; $params[] = $to; }
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetch();
}
?>