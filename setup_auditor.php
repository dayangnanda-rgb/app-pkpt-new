<?php
$dsn = "mysql:host=192.168.10.145;dbname=kemenkopmk_db";
$user = "sipd";
$pass = 's1n3rgh1@';

try {
    $pdo = new PDO($dsn, $user, $pass);
    
    $username = 'auditor';
    $password = password_hash('admin123', PASSWORD_DEFAULT);
    $role_id = 2; // Auditor
    
    // Check if exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username_ldap = ?");
    $stmt->execute([$username]);
    $existing = $stmt->fetch();
    
    if ($existing) {
        $stmt = $pdo->prepare("UPDATE users SET password = ?, role_id = ?, is_active = 1 WHERE username_ldap = ?");
        $stmt->execute([$password, $role_id, $username]);
        echo "User 'auditor' updated successfully.\n";
    } else {
        $stmt = $pdo->prepare("INSERT INTO users (username_ldap, password, role_id, is_active, created_at) VALUES (?, ?, ?, 1, NOW())");
        $stmt->execute([$username, $password, $role_id]);
        echo "User 'auditor' created successfully.\n";
    }
    
    // Also update 'user' to role 2 and hashed password if user wants to use that
    $username2 = 'user';
    $password2 = password_hash('user123', PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("UPDATE users SET password = ?, role_id = 2, is_active = 1 WHERE username_ldap = ?");
    $stmt->execute([$password2, $username2]);
    echo "User 'user' updated successfully (Pass: user123).\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
