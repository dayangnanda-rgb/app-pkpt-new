<?php
$dsn = "mysql:host=192.168.10.145;dbname=kemenkopmk_db";
$user = "sipd";
$pass = 's1n3rgh1@';

try {
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $password = password_hash('admin123', PASSWORD_DEFAULT);
    
    // 1. Create 'auditor' with pegawai_id 184
    $stmt = $pdo->prepare("INSERT INTO users (username_ldap, password, role_id, is_active, pegawai_id, created_at) VALUES (?, ?, 2, 1, 184, NOW()) ON DUPLICATE KEY UPDATE password = VALUES(password), role_id = 2, is_active = 1");
    $stmt->execute(['auditor', $password]);
    echo "User 'auditor' is READY (Pass: admin123)\n";
    
    // 2. Update 'user' just in case
    $stmt = $pdo->prepare("UPDATE users SET password = ?, role_id = 2, is_active = 1 WHERE username_ldap = 'user'");
    $stmt->execute([$password]);
    echo "User 'user' is READY (Pass: admin123)\n";

} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
