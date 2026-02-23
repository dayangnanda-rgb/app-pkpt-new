<?php
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);
$loader = require FCPATH . 'vendor/autoload.php';

$app = require_once FCPATH . 'app/Config/Paths.php';
$paths = new Config\Paths();

// Load the framework bootstrap
require_once $paths->systemDirectory . '/bootstrap.php';

$db = \Config\Database::connect();
$users = $db->table('users')->get()->getResultArray();

header('Content-Type: application/json');
echo json_encode($users, JSON_PRETTY_PRINT);
