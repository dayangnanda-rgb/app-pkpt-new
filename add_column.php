<?php
require_once 'app/Config/Database.php';
$db = \Config\Database::connect();
try {
    $db->query("ALTER TABLE program_kerja ADD COLUMN alasan_tidak_terlaksana TEXT AFTER status;");
    echo "Success adding column alasan_tidak_terlaksana";
} catch (\Exception $e) {
    if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
        echo "Column already exists";
    } else {
        echo "Error: " . $e->getMessage();
    }
}
