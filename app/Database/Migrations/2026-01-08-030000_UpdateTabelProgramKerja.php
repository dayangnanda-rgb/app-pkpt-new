<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Migration: Update Tabel Program Kerja
 * 
 * Mengubah struktur tabel program_kerja:
 * - Menambah kolom tahun
 * - Menambah kolom tanggal_mulai dan tanggal_selesai untuk rencana pelaksanaan
 * - Menambah kolom unit_kerja
 * - Mengubah kolom keterangan menjadi status dengan enum
 * - Mengubah rencana_kegiatan menjadi TEXT untuk deskripsi
 */
class UpdateTabelProgramKerja extends Migration
{
    public function up()
    {
        // Tambah kolom baru
        $fields = [
            'tahun' => [
                'type'       => 'INT',
                'constraint' => 4,
                'null'       => false,
                'default'    => date('Y'),
                'after'      => 'id',
                'comment'    => 'Tahun program kerja'
            ],
            'tanggal_mulai' => [
                'type'    => 'DATE',
                'null'    => true,
                'after'   => 'nama_kegiatan',
                'comment' => 'Tanggal mulai pelaksanaan'
            ],
            'tanggal_selesai' => [
                'type'    => 'DATE',
                'null'    => true,
                'after'   => 'tanggal_mulai',
                'comment' => 'Tanggal selesai pelaksanaan'
            ],
            'unit_kerja' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'tanggal_selesai',
                'comment'    => 'Unit kerja pelaksana'
            ],
        ];

        $this->forge->addColumn('program_kerja', $fields);

        // Ubah kolom keterangan menjadi status dengan enum
        $this->forge->modifyColumn('program_kerja', [
            'keterangan' => [
                'name'       => 'status',
                'type'       => 'ENUM',
                'constraint' => ['Terlaksana', 'Tidak Terlaksana', 'Penugasan Tambahan'],
                'null'       => true,
                'default'    => 'Terlaksana',
                'comment'    => 'Status pelaksanaan kegiatan'
            ]
        ]);

        // Ubah tipe data rencana_kegiatan untuk deskripsi yang lebih panjang
        $this->forge->modifyColumn('program_kerja', [
            'rencana_kegiatan' => [
                'type'    => 'TEXT',
                'null'    => true,
                'comment' => 'Deskripsi rencana kegiatan'
            ]
        ]);
    }

    public function down()
    {
        // Kembalikan perubahan
        $this->forge->dropColumn('program_kerja', ['tahun', 'tanggal_mulai', 'tanggal_selesai', 'unit_kerja']);

        // Kembalikan status ke keterangan
        $this->forge->modifyColumn('program_kerja', [
            'status' => [
                'name' => 'keterangan',
                'type' => 'TEXT',
                'null' => true
            ]
        ]);
    }
}
