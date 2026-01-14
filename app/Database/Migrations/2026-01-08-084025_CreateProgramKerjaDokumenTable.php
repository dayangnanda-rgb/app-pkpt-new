<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProgramKerjaDokumenTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'program_kerja_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'nama_file' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'tipe_dokumen' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
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
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('program_kerja_id', 'program_kerja', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('program_kerja_dokumen');
    }

    public function down()
    {
        $this->forge->dropTable('program_kerja_dokumen');
    }
}
