<?php
$dsn = "mysql:host=192.168.10.145;dbname=kemenkopmk_db";
$user = "sipd";
$pass = 's1n3rgh1@';

try {
    $pdo = new PDO($dsn, $user, $pass);
    $stmt = $pdo->query("SELECT pegawai_id FROM users WHERE pegawai_id IS NOT NULL LIMIT 1");
    $row = $stmt->fetch();
    echo "SAMPLE PEGAWAI ID: " . ($row['pegawai_id'] ?? 'NONE');
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
