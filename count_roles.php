<?php
$host = '192.168.10.145';
$user = 'sipd';
$pass = 's1n3rgh1@';
$db   = 'kemenkopmk_db';
$conn = new mysqli($host, $user, $pass, $db);
$sql = "SELECT role_id, COUNT(*) as count FROM users GROUP BY role_id";
$result = $conn->query($sql);
$data = [];
while($row = $result->fetch_assoc()) { $data[] = $row; }
echo json_encode($data);
$conn->close();
