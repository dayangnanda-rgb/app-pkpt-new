<?php
$host = '192.168.10.145';
$dbname = 'kemenkopmk_db';
$user = 'sipd';
$pass = 's1n3rgh1@';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "ALTER TABLE program_kerja ADD COLUMN catatan_auditor TEXT NULL";
    
    $pdo->exec($sql);
    echo "Column catatan_auditor added successfully.";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
