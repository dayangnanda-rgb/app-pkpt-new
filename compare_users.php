<?php
$host = '192.168.10.145';
$user = 'sipd';
$pass = 's1n3rgh1@';
$db   = 'kemenkopmk_db';
$conn = new mysqli($host, $user, $pass, $db);
$sql = "SELECT username_ldap, role_id, pegawai_id FROM users WHERE role_id = 1";
$result = $conn->query($sql);
while($row = $result->fetch_assoc()) {
    if ($row['username_ldap'] == 'admin') {
        echo "ADMIN GENERIC: pegawai_id=" . $row['pegawai_id'] . "\n";
    }
    if ($row['username_ldap'] == 'andre.lesmana') {
        echo "ANDRE: pegawai_id=" . $row['pegawai_id'] . "\n";
    }
}
$conn->close();
