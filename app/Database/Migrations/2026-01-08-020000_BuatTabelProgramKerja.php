<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Migration: Tabel Program Kerja
 * 
 * Membuat tabel program_kerja untuk menyimpan data
 * Program Kerja Pengawasan Tahunan (PKPT)
 * 
 * @author  PKPT Development Team
 * @created 2026-01-08
 */
class BuatTabelProgramKerja extends Migration
{
    /**
     * Jalankan migration - Buat tabel program_kerja
     */
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nama_kegiatan' => [
                'type'       => 'VARCHAR',
                'constraint' => '500',
                'comment'    => 'Nama kegiatan program kerja',
            ],
            'rencana_kegiatan' => [
                'type'    => 'TEXT',
                'null'    => true,
                'comment' => 'Rencana detail kegiatan',
            ],
            'anggaran' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0,
                'comment'    => 'Anggaran yang dialokasikan (dalam Rupiah)',
            ],
            'realisasi_kegiatan' => [
                'type'    => 'TEXT',
                'null'    => true,
                'comment' => 'Deskripsi realisasi kegiatan',
            ],
            'pelaksana' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
                'comment'    => 'Nama pelaksana/PIC kegiatan',
            ],
            'dokumen_output' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
                'comment'    => 'Path file dokumen output',
            ],
            'realisasi_anggaran' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0,
                'comment'    => 'Realisasi anggaran yang terpakai (dalam Rupiah)',
            ],
            'sasaran_strategis' => [
                'type'    => 'TEXT',
                'null'    => true,
                'comment' => 'Sasaran strategis kegiatan',
            ],
            'keterangan' => [
                'type'    => 'TEXT',
                'null'    => true,
                'comment' => 'Keterangan tambahan',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        // Set primary key
        $this->forge->addKey('id', true);
        
        // Buat tabel
        $this->forge->createTable('program_kerja', true);
    }

    /**
     * Rollback migration - Hapus tabel program_kerja
     */
    public function down()
    {
        $this->forge->dropTable('program_kerja', true);
    }
}
