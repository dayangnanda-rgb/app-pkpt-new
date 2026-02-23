<?php
$dsn = "mysql:host=192.168.10.145;dbname=kemenkopmk_db";
$user = "sipd";
$pass = 's1n3rgh1@';

try {
    $pdo = new PDO($dsn, $user, $pass);
    $stmt = $pdo->query("SELECT id, username_ldap, role_id, password FROM users ORDER BY id DESC LIMIT 5");
    echo "LATEST 5 USERS:\n";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "ID: {$row['id']} | User: {$row['username_ldap']} | Role: {$row['role_id']} | PassHash: " . $row['password'] . "\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
