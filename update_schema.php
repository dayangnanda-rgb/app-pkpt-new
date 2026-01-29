<?php
$mysqli = new mysqli('192.168.10.145', 'sipd', 's1n3rgh1@', 'kemenkopmk_db');
if ($mysqli->connect_error) {
    die('Connect Error: ' . $mysqli->connect_error);
}

$queries = [
    "ALTER TABLE program_kerja ADD COLUMN pengendali_teknis VARCHAR(255) NULL AFTER pelaksana",
    "ALTER TABLE program_kerja ADD COLUMN ketua_tim VARCHAR(255) NULL AFTER pengendali_teknis",
    "ALTER TABLE program_kerja ADD COLUMN anggota_tim TEXT NULL AFTER ketua_tim"
];

foreach ($queries as $sql) {
    if ($mysqli->query($sql)) {
        echo "Success: $sql\n";
    } else {
        echo "Error: " . $mysqli->error . " ($sql)\n";
    }
}

$mysqli->close();
