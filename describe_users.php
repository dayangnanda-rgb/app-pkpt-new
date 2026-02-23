<?php
$dsn = "mysql:host=192.168.10.145;dbname=kemenkopmk_db";
$user = "sipd";
$pass = 's1n3rgh1@';

try {
    $pdo = new PDO($dsn, $user, $pass);
    $stmt = $pdo->query("DESCRIBE users");
    print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
