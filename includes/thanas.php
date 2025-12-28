<?php

$CYBER_TABLES = [
  'kotwali'           => 'kotwali_cyber',
  'industrial_area'   => 'industrial_area_cyber',
  'bank_note_press'   => 'bank_note_press_cyber',
  'civil_line'        => 'civil_line_cyber',
  'nahar_darwaja'     => 'nahar_darwaja_cyber',
  'vijayganj_mandi'   => 'vijayganj_mandi_cyber',
  'sonkatch'          => 'sonkatch_cyber',
  'pipalrawan'        => 'pipalrawan_cyber',
  'bhaurasa'          => 'bhaurasa_cyber',
  'tonkkhurd'         => 'tonkkhurd_cyber',
  'bagli'             => 'bagli_cyber',
  'hatpiplya'         => 'hatpiplya_cyber',
  'barotha'           => 'barotha_cyber',
  'udai_nagar'        => 'udai_nagar_cyber',
  'khategaon'         => 'khategaon_cyber',
  'kannod'            => 'kannod_cyber',
  'kantaphod'         => 'kantaphod_cyber',
  'nemawar'           => 'nemawar_cyber',
  'harangaon'         => 'harangaon_cyber',
  'satwas'            => 'satwas_cyber',
  'kamlapur'          => 'kamlapur_cyber',
];

// Optional: show names in UI
$CYBER_THANA_LABELS = [
  'kotwali'         => 'Kotwali',
  'industrial_area' => 'Industrial Area',
  'bank_note_press' => 'Bank Note Press',
  'civil_line'      => 'Civil Line',
  'nahar_darwaja'   => 'Nahar Darwaja',
  'vijayganj_mandi' => 'VijayGanj Mandi',
  'sonkatch'        => 'Sonkatch',
  'pipalrawan'      => 'Pipalrawan',
  'bhaurasa'        => 'Bhaurasa',
  'tonkkhurd'       => 'Tonkkhurd',
  'bagli'           => 'Bagli',
  'hatpiplya'       => 'Hatpiplya',
  'barotha'         => 'Barotha',
  'udai_nagar'      => 'Udai Nagar',
  'khategaon'       => 'Khategaon',
  'kannod'          => 'Kannod',
  'kantaphod'       => 'Kantaphod',
  'nemawar'         => 'Nemawar',
  'harangaon'       => 'Harangaon',
  'satwas'          => 'Satwas',
  'kamlapur'        => 'Kamlapur',
];

// 7 thanas per cyber user_number (1/2/3)
$CYBER_ALLOWED_BY_USER = [
  // gitika (User-1)
  1 => ['vijayganj_mandi', 'hatpiplya', 'barotha', 'industrial_area', 'kannod', 'tonkkhurd', 'harangaon'],

  // arti (User-2)
  2 => ['bank_note_press', 'bhaurasa', 'khategaon', 'nahar_darwaja', 'sonkatch', 'udai_nagar', 'nemawar'],

  // nisha (User-3)
  3 => ['kotwali', 'civil_line', 'pipalrawan', 'satwas', 'bagli', 'kantaphod', 'kamlapur'],
];

function cyber_allowed_thanas_for_logged_user(): array {
  global $CYBER_ALLOWED_BY_USER;
  // If ADMIN is acting as a specific user, prefer the `as_user` request param
  if (($_SESSION['role'] ?? '') === 'ADMIN' && isset($_REQUEST['as_user'])) {
    $n = (int)$_REQUEST['as_user'];
  } else {
    $n = (int)($_SESSION['user_number'] ?? 0);
  }
  return $CYBER_ALLOWED_BY_USER[$n] ?? [];
}

function cyber_thana_label(string $key): string {
  global $CYBER_THANA_LABELS;
  return $CYBER_THANA_LABELS[$key] ?? strtoupper(str_replace('_', ' ', $key));
}