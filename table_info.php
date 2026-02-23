<?php
$dsn = "mysql:host=192.168.10.145;dbname=kemenkopmk_db";
$user = "sipd";
$pass = 's1n3rgh1@';

function describe($pdo, $table) {
    echo "--- TABLE: $table ---\n";
    $stmt = $pdo->query("DESCRIBE $table");
    foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
        echo "{$row['Field']} ({$row['Type']}) | Null: {$row['Null']} | Key: {$row['Key']}\n";
    }
}

try {
    $pdo = new PDO($dsn, $user, $pass);
    describe($pdo, 'program_kerja');
    describe($pdo, 'program_kerja_pelaksana');
    describe($pdo, 'users');
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
