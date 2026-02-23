<?php
$dsn = "mysql:host=192.168.10.145;dbname=kemenkopmk_db";
$user = "sipd";
$pass = 's1n3rgh1@';

try {
    $pdo = new PDO($dsn, $user, $pass);
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username_ldap = ?");
    $stmt->execute(['auditor']);
    $res = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($res) {
        echo "FOUND AUDITOR:\n";
        print_r($res);
    } else {
        echo "AUDITOR NOT FOUND!\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
