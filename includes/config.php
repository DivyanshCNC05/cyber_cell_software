<?php
// Hostinger DB credentials
define('DB_HOST', '82.180.152.52');      // e.g. localhost or mysql.hostinger.com
define('DB_NAME', 'u868614356_cybercell_db');
define('DB_USER', 'u868614356_cybercell_db1');
define('DB_PASS', 'Cyber_cell_software_05');
define('DB_CHARSET', 'utf8mb4');

// App settings
define('APP_NAME', 'Cyber Cell');

// Base path: path up to '/public' (so links are rooted at the public webroot)
$script = str_replace('\\','/', $_SERVER['SCRIPT_NAME'] ?? '');
$pos = strpos($script, '/public');
if ($pos !== false) {
  $__base = substr($script, 0, $pos + strlen('/public'));
} else {
  $__base = dirname($script);
}
if ($__base === '/' || $__base === '\\') $__base = '';
define('BASE_PATH', rtrim($__base, '/'));
unset($script, $pos, $__base);

