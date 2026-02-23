<?php
// Standalone script to fetch users using CI4 DB
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR);
chdir(FCPATH);
require FCPATH . '../app/Config/Paths.php';
$paths = new Config\Paths();
require $paths->systemDirectory . '/Boot.php';
$app = \CodeIgniter\Boot::bootWeb($paths); // Or bootSpark

$db = \Config\Database::connect();
$query = $db->table('users')->select('id, username_ldap, role_id, is_active')->get();
$results = $query->getResultArray();

echo "USER LIST:\n";
foreach ($results as $row) {
    echo "ID: " . $row['id'] . " | Username: " . $row['username_ldap'] . " | Role: " . ($row['role_id'] == 1 ? 'Admin' : 'Auditor') . " | Active: " . $row['is_active'] . "\n";
}
