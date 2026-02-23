<?php
$username = 'auditor';
$password = 'admin123';

$dsn = "mysql:host=192.168.10.145;dbname=kemenkopmk_db";
$user_db = "sipd";
$pass_db = 's1n3rgh1@';

try {
    $pdo = new PDO($dsn, $user_db, $pass_db);
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username_ldap = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        die("DEBUG: User not found in DB\n");
    }

    echo "DEBUG: User found. ID: " . $user['id'] . "\n";
    echo "DEBUG: DB Hash: " . $user['password'] . "\n";
    
    if (password_verify($password, $user['password'])) {
        echo "DEBUG: Password Verification SUCCESS\n";
    } else {
        echo "DEBUG: Password Verification FAILED\n";
    }

    if (isset($user['is_active']) && (int) $user['is_active'] === 1) {
        echo "DEBUG: Account is ACTIVE\n";
    } else {
        echo "DEBUG: Account is INACTIVE (Value: " . $user['is_active'] . ")\n";
    }

    $roleName = ((int)$user['role_id'] === 1) ? 'admin' : 'auditor';
    echo "DEBUG: Role Identified as: " . $roleName . "\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
