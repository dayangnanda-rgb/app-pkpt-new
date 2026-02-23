<?php
$host = '192.168.10.145';
$user = 'sipd';
$pass = 's1n3rgh1@';
$db   = 'kemenkopmk_db';
$conn = new mysqli($host, $user, $pass, $db);
$sql = "SELECT username_ldap, role_id, pegawai_id FROM users WHERE username_ldap = 'auditor'";
$result = $conn->query($sql);
while($row = $result->fetch_assoc()) {
    echo "AUDITOR GENERIC: role_id=" . $row['role_id'] . " pegawai_id=" . $row['pegawai_id'] . "\n";
}
$conn->close();
