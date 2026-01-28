<?php

namespace App\Models;

use CodeIgniter\Model;

class PegawaiViewModel extends Model
{
    protected $table            = 'pegawai_view';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $protectFields    = false;

    /**
     * Ambil detail pegawai berdasarkan ID
     */
    public function getDetail($id)
    {
        return $this->where('id', $id)->first();
    }
}
