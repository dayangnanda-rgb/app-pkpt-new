<?php
$host = '192.168.10.145';
$user = 'sipd';
$pass = 's1n3rgh1@';
$db   = 'kemenkopmk_db';
$conn = new mysqli($host, $user, $pass, $db);
$sql = "SHOW TABLES LIKE 'roles'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $res = $conn->query("SELECT * FROM roles");
    $data = [];
    while($row = $res->fetch_assoc()) { $data[] = $row; }
    echo json_encode($data);
} else {
    echo "No roles table";
}
$conn->close();
