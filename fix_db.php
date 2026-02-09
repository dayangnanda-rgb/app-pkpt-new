<?php
// Raw DB Fix Script for PKPT Team Table
$hostname = "192.168.10.145";
$username = "sipd";
$password = "s1n3rgh1@";
$database = "kemenkopmk_db";

$conn = new mysqli($hostname, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 1. Create the table
$sql = "CREATE TABLE IF NOT EXISTS `program_kerja_pelaksana` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `program_kerja_id` INT UNSIGNED NOT NULL,
    `nama_pelaksana` VARCHAR(255) NOT NULL,
    `peran` ENUM('Pengendali Teknis', 'Ketua Tim', 'Anggota', 'Auditor Madya', 'Auditor Muda') NOT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`program_kerja_id`) REFERENCES `program_kerja`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";

if ($conn->query($sql) === TRUE) {
    echo "Table program_kerja_pelaksana created successfully\n";
} else {
    echo "Error creating table: " . $conn->error . "\n";
}

// 2. Data Migration
// Check if columns exist in program_kerja
$res = $conn->query("SHOW COLUMNS FROM `program_kerja` LIKE 'pengendali_teknis'");
if ($res->num_rows > 0) {
    $conn->query("INSERT INTO program_kerja_pelaksana (program_kerja_id, nama_pelaksana, peran) 
                  SELECT id, pengendali_teknis, 'Pengendali Teknis' FROM program_kerja 
                  WHERE pengendali_teknis IS NOT NULL AND pengendali_teknis != ''");
    echo "Migrated Pengendali Teknis data\n";
}

$res = $conn->query("SHOW COLUMNS FROM `program_kerja` LIKE 'ketua_tim'");
if ($res->num_rows > 0) {
    $conn->query("INSERT INTO program_kerja_pelaksana (program_kerja_id, nama_pelaksana, peran) 
                  SELECT id, ketua_tim, 'Ketua Tim' FROM program_kerja 
                  WHERE ketua_tim IS NOT NULL AND ketua_tim != ''");
    echo "Migrated Ketua Tim data\n";
}

$res = $conn->query("SHOW COLUMNS FROM `program_kerja` LIKE 'anggota_tim'");
if ($res->num_rows > 0) {
    $result = $conn->query("SELECT id, anggota_tim FROM program_kerja WHERE anggota_tim IS NOT NULL AND anggota_tim != ''");
    while($row = $result->fetch_assoc()) {
        $names = preg_split('/[,\n\r]+/', $row['anggota_tim']);
        foreach ($names as $name) {
            $name = trim($name);
            if (!empty($name)) {
                $stmt = $conn->prepare("INSERT INTO program_kerja_pelaksana (program_kerja_id, nama_pelaksana, peran) VALUES (?, ?, 'Anggota')");
                $stmt->bind_param("is", $row['id'], $name);
                $stmt->execute();
            }
        }
    }
    echo "Migrated Anggota Tim data\n";
}

$conn->close();
?>
