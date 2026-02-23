<?php
// Simple script to dump users Table without full framework boot if possible
// But CI4 DB needs Config, so...

require 'app/Config/Database.php';

// Actually, let's just use PDO for a quick check if we can get DB creds from .env
$env = file_get_contents('.env');
preg_match('/database.default.hostname = (.*)/', $env, $host);
preg_match('/database.default.database = (.*)/', $env, $db);
preg_match('/database.default.username = (.*)/', $env, $user);
preg_match('/database.default.password = (.*)/', $env, $pass);

$dsn = "mysql:host=" . trim($host[1]) . ";dbname=" . trim($db[1]);
try {
    $pdo = new PDO($dsn, trim($user[1]), trim($pass[1]));
    $stmt = $pdo->query("SELECT id, username_ldap, role_id, password FROM users");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "ID: {$row['id']} | User: {$row['username_ldap']} | Role: {$row['role_id']} | PassHash: " . substr($row['password'], 0, 10) . "...\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
