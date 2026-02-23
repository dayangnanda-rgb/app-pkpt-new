<?php
require 'public/index.php';
$db = \Config\Database::connect();
$users = $db->table('users')->get()->getResultArray();
foreach ($users as $user) {
    echo "ID: " . $user['id'] . " | Username: " . $user['username_ldap'] . " | Role ID: " . $user['role_id'] . "\n";
}
