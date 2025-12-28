<?php
require __DIR__ . '/../../includes/auth.php';

require_role('ADMIN'); // if not admin => redirect to login
