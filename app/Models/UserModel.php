<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'pegawai_id',
        'username_ldap',
        'password',
        'role_id',
        'is_active',
        'created_at',
        'updated_at',
        'deleted_at',
        'username_m365',
    ];
}
