<?php
$hostname = "192.168.10.145";
$username = "sipd";
$password = "s1n3rgh1@";
$database = "kemenkopmk_db";

$conn = new mysqli($hostname, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "ALTER TABLE program_kerja ADD COLUMN alasan_tidak_terlaksana TEXT AFTER status";

if ($conn->query($sql) === TRUE) {
    echo "Column 'alasan_tidak_terlaksana' added successfully";
} else {
    if (strpos($conn->error, 'Duplicate column name') !== false) {
        echo "Column already exists";
    } else {
        echo "Error adding column: " . $conn->error;
    }
}

$conn->close();
