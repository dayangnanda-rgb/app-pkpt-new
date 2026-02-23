<?php
$host = '192.168.10.145';
$user = 'sipd';
$pass = 's1n3rgh1@';
$db   = 'kemenkopmk_db';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT u.id, u.username_ldap, u.role_id, p.nama 
        FROM users u 
        LEFT JOIN pegawai_view p ON u.pegawai_id = p.id 
        WHERE p.nama LIKE '%Andre Lesmana%' OR u.username_ldap LIKE '%andre%'";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "ID: " . $row["id"]. " - Username: " . $row["username_ldap"]. " - Role ID: " . $row["role_id"]. " - Nama: " . $row["nama"]. "\n";
    }
} else {
    echo "0 results";
}
$conn->close();
