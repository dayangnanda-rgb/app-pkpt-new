<?php
$host = '192.168.10.145';
$user = 'sipd';
$pass = 's1n3rgh1@';
$db   = 'kemenkopmk_db';
$conn = new mysqli($host, $user, $pass, $db);
$name = 'Agung Gumilar Triyanto, S.ST, M.Si';
$sql = "SELECT id, nama_kegiatan, ketua_tim, created_by FROM program_kerja WHERE ketua_tim LIKE '%Agung Gumilar%'";
$result = $conn->query($sql);
$data = [];
while($row = $result->fetch_assoc()) { $data[] = $row; }
echo json_encode($data);
$conn->close();
