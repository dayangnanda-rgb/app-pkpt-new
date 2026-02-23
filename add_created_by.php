<?php
$host = '192.168.10.145';
$dbname = 'kemenkopmk_db';
$user = 'sipd';
$pass = 's1n3rgh1@';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "ALTER TABLE program_kerja ADD COLUMN created_by VARCHAR(255) NULL";
    
    $pdo->exec($sql);
    echo "Column created_by added successfully.";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
