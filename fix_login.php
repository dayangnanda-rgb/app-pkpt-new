<?php
$dsn = "mysql:host=192.168.10.145;dbname=kemenkopmk_db";
$user = "sipd";
$pass = 's1n3rgh1@';

try {
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $username = 'auditor';
    $password = password_hash('admin123', PASSWORD_DEFAULT);
    
    // First, check if 'auditor' exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username_ldap = ?");
    $stmt->execute([$username]);
    $res = $stmt->fetch();
    
    if ($res) {
        $stmt = $pdo->prepare("UPDATE users SET password = ?, role_id = 2, is_active = 1 WHERE id = ?");
        $stmt->execute([$password, $res['id']]);
        echo "Updated existing 'auditor' (ID: {$res['id']})\n";
    } else {
        // Try to insert with minimum required fields
        $stmt = $pdo->prepare("INSERT INTO users (username_ldap, password, role_id, is_active, created_at) VALUES (?, ?, 2, 1, NOW())");
        $stmt->execute([$username, $password]);
        echo "Created new 'auditor'\n";
    }
    
    // Also update 'user' just in case
    $stmt = $pdo->prepare("UPDATE users SET password = ?, role_id = 2, is_active = 1 WHERE username_ldap = 'user'");
    $stmt->execute([$password]);
    echo "Updated 'user' password to 'admin123'\n";

} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
