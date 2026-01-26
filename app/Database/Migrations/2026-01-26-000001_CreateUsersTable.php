<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsersTable extends Migration
{
    public function up()
    {
        // Cek jika tabel users sudah ada
        if (!$this->db->tableExists('users')) {
            $this->forge->addField([
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],
                'pegawai_id' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'null'       => true,
                    'default'    => null,
                    'comment'    => 'Relasi ke table pegawai jika ada',
                ],
                'username_ldap' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '100',
                    'null'       => false,
                    'comment'    => 'Username untuk login',
                ],
                'username_m365' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '150',
                    'null'       => true,
                    'default'    => null,
                    'comment'    => 'Email/UPN untuk SSO Microsoft',
                ],
                'password' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '255',
                    'null'       => true,
                    'default'    => null,
                    'comment'    => 'Opsional jika full SSO/LDAP',
                ],
                'role_id' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'default'    => 2,
                    'comment'    => '1=Admin, 2=User',
                ],
                'is_active' => [
                    'type'       => 'TINYINT',
                    'constraint' => 1,
                    'default'    => 1,
                    'comment'    => '1=Aktif, 0=Nonaktif',
                ],
                'created_at' => [
                    'type'    => 'DATETIME',
                    'null'    => true,
                    'default' => null, // Menggunakan CURRENT_TIMESTAMP di handle database level biasanya, tapi CI Forge default null ok
                ],
                'updated_at' => [
                    'type'    => 'DATETIME',
                    'null'    => true,
                    'default' => null,
                ],
                'deleted_at' => [
                    'type'    => 'DATETIME',
                    'null'    => true,
                    'default' => null,
                ],
            ]);
            
            $this->forge->addKey('id', true);
            $this->forge->addUniqueKey('username_ldap');
            $this->forge->addKey('username_m365');
            $this->forge->createTable('users', true);
        }
    }

    public function down()
    {
        // Kita tidak drop table users karena mungkin shared
        // $this->forge->dropTable('users');
    }
}
