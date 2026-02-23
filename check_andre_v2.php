<?php
$host = '192.168.10.145';
$user = 'sipd';
$pass = 's1n3rgh1@';
$db   = 'kemenkopmk_db';

$conn = new mysqli($host, $user, $pass, $db);
$sql = "SELECT u.id, u.username_ldap, u.role_id, p.nama FROM users u LEFT JOIN pegawai_view p ON u.pegawai_id = p.id WHERE p.nama LIKE '%Andre Lesmana%'";
$result = $conn->query($sql);
$data = [];
while($row = $result->fetch_assoc()) { $data[] = $row; }
echo json_encode($data);
$conn->close();
