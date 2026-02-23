<?php
$dsn = "mysql:host=192.168.10.145;dbname=kemenkopmk_db";
$user = "sipd";
$pass = 's1n3rgh1@';

try {
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $password = password_hash('admin123', PASSWORD_DEFAULT);
    
    $stmt = $pdo->prepare("UPDATE users SET password = ?, role_id = 1, is_active = 1 WHERE id = 1");
    $stmt->execute([$password]);
    
    echo "SUCCESS: User 'tri.ardiansyah' is now Admin with password 'admin123'.\n";

} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
