<?php
$host = '192.168.10.145';
$user = 'sipd';
$pass = 's1n3rgh1@';
$db   = 'kemenkopmk_db';
$conn = new mysqli($host, $user, $pass, $db);
$sql = "SELECT DISTINCT created_by FROM program_kerja";
$result = $conn->query($sql);
$data = [];
while($row = $result->fetch_assoc()) { $data[] = $row['created_by']; }
echo json_encode($data);
$conn->close();
