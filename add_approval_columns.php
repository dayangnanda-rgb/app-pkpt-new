<?php
$host = '192.168.10.145';
$dbname = 'kemenkopmk_db';
$user = 'sipd';
$pass = 's1n3rgh1@';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "ALTER TABLE program_kerja 
            ADD COLUMN is_approved TINYINT(1) DEFAULT 0,
            ADD COLUMN approved_by VARCHAR(255) NULL,
            ADD COLUMN approved_at DATETIME NULL";
    
    $pdo->exec($sql);
    echo "Columns is_approved, approved_by, and approved_at added successfully.";
} catch (PDOException $e) {
    if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
        echo "Columns already exist.";
    } else {
        echo "Error: " . $e->getMessage();
    }
}
