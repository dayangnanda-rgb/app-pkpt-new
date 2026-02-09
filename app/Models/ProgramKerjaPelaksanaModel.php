<?php

namespace App\Models;

use CodeIgniter\Model;

class ProgramKerjaPelaksanaModel extends Model
{
    protected $table            = 'program_kerja_pelaksana';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'program_kerja_id',
        'nama_pelaksana',
        'peran'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Ambil tim pelaksana berdasarkan ID Program Kerja
     * 
     * @param int $programKerjaId
     * @return array
     */
    public function getByProgramKerja($programKerjaId)
    {
        return $this->where('program_kerja_id', $programKerjaId)
                    ->orderBy('FIELD(peran, "Pengendali Teknis", "Ketua Tim", "Anggota", "Auditor Madya", "Auditor Muda")')
                    ->findAll();
    }
}
