<?php
$mysqli = new mysqli('192.168.10.145', 'sipd', 's1n3rgh1@', 'kemenkopmk_db');
if ($mysqli->connect_error) {
    die('Connect Error: ' . $mysqli->connect_error);
}

$result = $mysqli->query('DESCRIBE program_kerja');
if ($result) {
    echo "Columns in 'program_kerja':\n";
    while ($row = $result->fetch_assoc()) {
        echo "- {$row['Field']} ({$row['Type']})\n";
    }
} else {
    echo "Error describing table: " . $mysqli->error;
}
$mysqli->close();
