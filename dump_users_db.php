<?php
require 'app/Config/Paths.php';
$paths = new Config\Paths();
require $paths->systemDirectory . '/bootstrap.php';

$db = \Config\Database::connect();
$users = $db->table('users')->get()->getResultArray();
echo json_encode($users, JSON_PRETTY_PRINT);
