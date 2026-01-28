<?php
$db = new mysqli('192.168.10.145', 'sipd', 's1n3rgh1@', 'kemenkopmk_db');

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

$result = $db->query("SHOW COLUMNS FROM program_kerja_dokumen");

if ($result) {
    echo "Columns in program_kerja_dokumen:\n";
    while ($row = $result->fetch_assoc()) {
        echo $row['Field'] . " - " . $row['Type'] . "\n";
    }
} else {
    echo "Error: " . $db->error;
}

$db->close();
