<?php
// Initialize CI4 Autoloader
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR);
require __DIR__ . '/vendor/autoload.php';

// Fix for CI4 environment
$env_path = __DIR__ . '/.env';
if (file_exists($env_path)) {
    $lines = file($env_path);
    foreach ($lines as $line) {
        if (preg_match('/^([^#\s][^=]*)\s*=\s*(.*)$/', $line, $matches)) {
            putenv(trim($matches[1]) . '=' . trim($matches[2], " \"'"));
        }
    }
}

$db = \Config\Database::connect();

try {
    // 1. Create table program_kerja_pelaksana
    $db->query("CREATE TABLE IF NOT EXISTS `program_kerja_pelaksana` (
        `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        `program_kerja_id` INT UNSIGNED NOT NULL,
        `nama_pelaksana` VARCHAR(255) NOT NULL,
        `peran` ENUM('Pengendali Teknis', 'Ketua Tim', 'Anggota', 'Auditor Madya', 'Auditor Muda') NOT NULL,
        `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
        `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (`program_kerja_id`) REFERENCES `program_kerja`(`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;");

    echo "Tabel program_kerja_pelaksana berhasil dibuat.\n";

    // 2. Migration: Move existing data from program_kerja columns if they exist
    $cols = $db->getFieldNames('program_kerja');
    
    if (in_array('pengendali_teknis', $cols)) {
        $db->query("INSERT INTO program_kerja_pelaksana (program_kerja_id, nama_pelaksana, peran) 
                    SELECT id, pengendali_teknis, 'Pengendali Teknis' FROM program_kerja 
                    WHERE pengendali_teknis IS NOT NULL AND pengendali_teknis != '';");
        echo "Data Pengendali Teknis berhasil dimigrasi.\n";
    }

    if (in_array('ketua_tim', $cols)) {
        $db->query("INSERT INTO program_kerja_pelaksana (program_kerja_id, nama_pelaksana, peran) 
                    SELECT id, ketua_tim, 'Ketua Tim' FROM program_kerja 
                    WHERE ketua_tim IS NOT NULL AND ketua_tim != '';");
        echo "Data Ketua Tim berhasil dimigrasi.\n";
    }

    // Handle anggota_tim
    if (in_array('anggota_tim', $cols)) {
        $results = $db->query("SELECT id, anggota_tim FROM program_kerja WHERE anggota_tim IS NOT NULL AND anggota_tim != '';")->getResultArray();
        foreach ($results as $row) {
            // Split by comma or newline
            $names = preg_split('/[,\n\r]+/', $row['anggota_tim']);
            foreach ($names as $nama) {
                $nama = trim($nama);
                if (!empty($nama)) {
                    $db->query("INSERT INTO program_kerja_pelaksana (program_kerja_id, nama_pelaksana, peran) VALUES (?, ?, 'Anggota')", [$row['id'], $nama]);
                }
            }
        }
        echo "Data Anggota Tim berhasil dimigrasi.\n";
    }

} catch (\Exception $e) {
    echo "Error: " . $e->getMessage();
}
