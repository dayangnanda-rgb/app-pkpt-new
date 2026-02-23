<?php
$dsn = "mysql:host=192.168.10.145;dbname=kemenkopmk_db";
$user = "sipd";
$pass = 's1n3rgh1@';

try {
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $password = password_hash('admin123', PASSWORD_DEFAULT);
    
    // Update existing user ID 412 to be our 'auditor'
    // This ensures all NOT NULL fields are already satisfied
    $stmt = $pdo->prepare("UPDATE users SET username_ldap = 'auditor', password = ?, role_id = 2, is_active = 1 WHERE id = 412");
    $stmt->execute([$password]);
    
    echo "SUCCESS: User ID 412 has been transformed into 'auditor' with password 'admin123'.\n";

} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
