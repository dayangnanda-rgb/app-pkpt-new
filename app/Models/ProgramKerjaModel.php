<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Model: Program Kerja
 * 
 * Model untuk mengelola data Program Kerja Pengawasan Tahunan (PKPT)
 * Menyediakan fungsi CRUD dan validasi data
 * 
 * @author  PKPT Development Team
 * @created 2026-01-08
 */
class ProgramKerjaModel extends Model
{
    protected $table            = 'program_kerja';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    
    // Field yang boleh diisi
    protected $allowedFields    = [
        'tahun',
        'nama_kegiatan',
        'tanggal_mulai',
        'tanggal_selesai',
        'unit_kerja',
        'rencana_kegiatan',
        'anggaran',
        'realisasi_kegiatan',
        'pelaksana',
        'pengendali_teknis',
        'ketua_tim',
        'anggota_tim',
        'dokumen_output',
        'realisasi_anggaran',
        'sasaran_strategis',
        'status',
        'alasan_tidak_terlaksana',
        'is_approved',
        'approved_by',
        'approved_at',
        'catatan_auditor',
        'created_by'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation rules
    protected $validationRules = [
        'tahun' => [
            'rules'  => 'required|integer|min_length[4]|max_length[4]',
            'errors' => [
                'required'   => 'Tahun harus diisi',
                'integer'    => 'Tahun harus berupa angka',
                'min_length' => 'Tahun harus 4 digit',
                'max_length' => 'Tahun harus 4 digit'
            ]
        ],
        'nama_kegiatan' => [
            'rules'  => 'required|max_length[500]',
            'errors' => [
                'required'   => 'Nama kegiatan harus diisi',
                'max_length' => 'Nama kegiatan maksimal 500 karakter'
            ]
        ],
        'anggaran' => [
            'rules'  => 'required|decimal|greater_than_equal_to[0]',
            'errors' => [
                'required'              => 'Anggaran harus diisi',
                'decimal'               => 'Anggaran harus berupa angka',
                'greater_than_equal_to' => 'Anggaran tidak boleh negatif'
            ]
        ],
        'realisasi_anggaran' => [
            'rules'  => 'permit_empty|decimal|greater_than_equal_to[0]',
            'errors' => [
                'decimal'               => 'Realisasi anggaran harus berupa angka',
                'greater_than_equal_to' => 'Realisasi anggaran tidak boleh negatif'
            ]
        ],
        'status' => [
            'rules'  => 'permit_empty|in_list[Terlaksana,Tidak Terlaksana,Penugasan Tambahan,Dibatalkan]',
            'errors' => [
                'in_list' => 'Status harus salah satu dari: Terlaksana, Tidak Terlaksana, Penugasan Tambahan, atau Dibatalkan'
            ]
        ]
    ];

    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Helper: Menerapkan filter berdasarkan user atau pembuat data.
     */
    private function applyUserFilter($query, $onlyForUser, $peran)
    {
        if ($onlyForUser) {
            $query->groupStart();
                // Selalu cek created_by
                $query->where('program_kerja.created_by', $onlyForUser);
                
                // Tambahkan pengecekan sebagai Ketua Tim
                $query->orWhere('program_kerja.ketua_tim', $onlyForUser);
                
                // Tambahkan pengecekan di tim pelaksana
                $query->orWhereIn('program_kerja.id', function($builder) use ($onlyForUser) {
                    $builder->select('program_kerja_id')
                            ->from('program_kerja_pelaksana')
                            ->where('nama_pelaksana', $onlyForUser);
                    return $builder;
                });
            $query->groupEnd();
        }
        return $query;
    }

    /**
     * Ambil semua data program kerja dengan pagination
     * 
     * @param int $perPage Jumlah data per halaman
     * @param int|null $tahun Filter tahun
     * @return array
     */
    /**
     * Mengambil semua data program kerja dengan fitur paginasi.
     * Termasuk subquery untuk mendapatkan daftar dokumen terkait dalam satu query.
     * 
     * @param int $perPage Jumlah data per halaman (default 10)
     * @param int|null $tahun Filter berdasarkan tahun (opsional)
     * @param string|null $onlyForUser Nama pelaksana (jika diisi, hanya tampilkan penugasan ybs)
     * @return array Data program kerja
     */
    public function ambilSemuaData($perPage = 10, $tahun = null, $onlyForUser = null, $peran = null)
    {
        /**
         * Subquery untuk mendapatkan SEMUA dokumen terkait.
         * 
         * LOGIKA:
         * 1. Menggunakan GROUP_CONCAT untuk menggabungkan banyak baris menjadi satu string.
         * 2. Format string: id:nama_file:tipe_dokumen
         * 3. Pemisah antar dokumen adalah karakter pipa '|'
         * 4. COALESCE(tipe_dokumen, 'Dokumen') digunakan untuk menangani jika tipe_dokumen kosong/NULL,
         *    sehingga struktur data tetap konsisten (3 bagian).
         */
        $subQuery = $this->db->table('program_kerja_dokumen')
            ->select("GROUP_CONCAT(CONCAT(id, ':', COALESCE(nama_asli, nama_file), ':', COALESCE(tipe_dokumen, 'Dokumen'), ':', nama_file) SEPARATOR '|')")
            ->where('program_kerja_id = program_kerja.id')
            ->orderBy('created_at', 'DESC')
            ->getCompiledSelect();

        $subQueryTeam = $this->db->table('program_kerja_pelaksana')
            ->select("GROUP_CONCAT(CONCAT(peran, ':', nama_pelaksana) SEPARATOR '|')")
            ->where('program_kerja_id = program_kerja.id')
            ->getCompiledSelect();

        $query = $this->select('program_kerja.*')
                      ->select("($subQuery) as dokumen_output") // Injeksi subquery ke main query
                      ->select("($subQueryTeam) as tim_pelaksana")
                      ->orderBy('created_at', 'DESC');
        
        if ($tahun) {
            $query->where('tahun', $tahun);
        }

        $this->applyUserFilter($query, $onlyForUser, $peran);
        
        return $query->paginate($perPage);
    }

    /**
     * Cari program kerja berdasarkan keyword dan tahun
     * 
     * @param string $keyword Kata kunci pencarian
     * @param int $perPage Jumlah data per halaman
     * @param int|null $tahun Filter tahun
     * @return array
     */
    /**
     * Mencari program kerja berdasarkan kata kunci (keyword) dan tahun.
     * Pencarian mencakup: Nama Kegiatan, Pelaksana, Unit Kerja, dan Status.
     * 
     * @param string $keyword Kata kunci pencarian
     * @param int $perPage Jumlah data per halaman
     * @param int|null $tahun Filter tahun
     * @param string|null $onlyForUser Nama pelaksana
     * @param string|null $peran Filter peran spesifik
     * @return array Data hasil pencarian
     */
    public function cariProgramKerja($keyword, $perPage = 10, $tahun = null, $onlyForUser = null, $peran = null)
    {
        // Subquery yang sama seperti di atas untuk pencarian
        $subQuery = $this->db->table('program_kerja_dokumen')
            ->select("GROUP_CONCAT(CONCAT(id, ':', COALESCE(nama_asli, nama_file), ':', COALESCE(tipe_dokumen, 'Dokumen'), ':', nama_file) SEPARATOR '|')")
            ->where('program_kerja_id = program_kerja.id')
            ->orderBy('created_at', 'DESC')
            ->getCompiledSelect();

        $subQueryTeam = $this->db->table('program_kerja_pelaksana')
            ->select("GROUP_CONCAT(CONCAT(peran, ':', nama_pelaksana) SEPARATOR '|')")
            ->where('program_kerja_id = program_kerja.id')
            ->getCompiledSelect();

        $query = $this->select('program_kerja.*')
                      ->select("($subQuery) as dokumen_output")
                      ->select("($subQueryTeam) as tim_pelaksana");

        if (!empty($keyword)) {
            $query->groupStart()
                ->like('nama_kegiatan', $keyword)
                ->orLike('pelaksana', $keyword)
                ->orLike('unit_kerja', $keyword)
                ->orLike('status', $keyword)
            ->groupEnd();
        }

        if ($tahun) {
            $query->where('tahun', $tahun);
        }

        $this->applyUserFilter($query, $onlyForUser, $peran);

        return $query->orderBy('created_at', 'DESC')
                     ->paginate($perPage);
    }

    /**
     * Ambil daftar tahun yang tersedia di database
     * 
     * @return array
     */
    /**
     * Mengambil daftar tahun yang tersedia di database.
     * Digunakan untuk opsi filter dropdown tahun.
     * 
     * @return array Daftar tahun unik (descending)
     */
    public function getYears()
    {
        $result = $this->select('tahun')
                       ->distinct()
                       ->orderBy('tahun', 'DESC')
                       ->findAll();
        
        $years = [];
        foreach ($result as $row) {
            $years[] = $row['tahun'];
        }
        
        return $years;
    }

    /**
     * Ambil data program kerja berdasarkan ID
     * 
     * @param int $id ID program kerja
     * @return array|null
     */
    public function ambilDataById($id)
    {
        return $this->find($id);
    }

    /**
     * Hitung total anggaran semua program
     * 
     * @return float
     */
    public function hitungTotalAnggaran($tahun = null, $onlyForUser = null, $peran = null)
    {
        $query = $this->selectSum('anggaran');
        if ($tahun) $query->where('tahun', $tahun);
        $this->applyUserFilter($query, $onlyForUser, $peran);
        
        $result = $query->first();
        return $result['anggaran'] ?? 0;
    }

    /**
     * Hitung total realisasi anggaran
     * 
     * @return float
     */
    public function hitungTotalRealisasi($tahun = null, $onlyForUser = null, $peran = null)
    {
        $query = $this->selectSum('realisasi_anggaran');
        if ($tahun) $query->where('tahun', $tahun);
        $query->where('is_approved', 1);
        $this->applyUserFilter($query, $onlyForUser, $peran);
        
        $result = $query->first();
        return $result['realisasi_anggaran'] ?? 0;
    }

    /**
     * Hitung jumlah program kerja
     * 
     * @return int
     */
    public function hitungJumlahProgram($tahun = null, $onlyForUser = null, $peran = null)
    {
        $query = $this->asArray();
        if ($tahun) $query->where('tahun', $tahun);
        $this->applyUserFilter($query, $onlyForUser, $peran);
        return $query->countAllResults();
    }

    /**
     * Ambil statistik dashboard
     * 
     * @return array
     */
    public function ambilStatistik($tahun = null, $onlyForUser = null, $peran = null)
    {
        $totalAnggaran = $this->hitungTotalAnggaran($tahun, $onlyForUser, $peran);
        $totalRealisasi = $this->hitungTotalRealisasi($tahun, $onlyForUser, $peran);
        $sisaAnggaran = $totalAnggaran - $totalRealisasi;

        // Separate counting for additional tasks (for informative separation)
        $queryAdd = $this->where('status', 'Penugasan Tambahan');
        if ($tahun) $queryAdd->where('tahun', $tahun);
        $this->applyUserFilter($queryAdd, $onlyForUser, $peran);
        $totalTambahan = $queryAdd->countAllResults();

        return [
            'total_program'          => $this->hitungJumlahProgram($tahun, $onlyForUser, $peran),
            'total_tambahan'         => $totalTambahan,
            'total_anggaran'         => $totalAnggaran,
            'total_realisasi'        => $totalRealisasi,
            'sisa_anggaran'          => $sisaAnggaran,
            'persentase_realisasi'   => $this->hitungPersentaseRealisasi($tahun, $onlyForUser, $peran),
            'persentase_capaian'     => $this->hitungPersentaseCapaian($tahun, $onlyForUser, $peran),
            'persentase_pelaksanaan' => $this->hitungPersentasePelaksanaan($tahun, $onlyForUser, $peran),
            'total_terlaksana'       => $this->hitungJumlahTerlaksana($tahun, $onlyForUser, $peran)
        ];
    }

    /**
     * Hitung jumlah kegiatan terlaksana (Global: Terlaksana + Penugasan Tambahan)
     * Hanya yang sudah DISETUJUI oleh Auditor
     */
    private function hitungJumlahTerlaksana($tahun = null, $onlyForUser = null, $peran = null)
    {
        $query = $this->whereIn('status', ['Terlaksana', 'Penugasan Tambahan'])
                      ->where('is_approved', 1);
        if ($tahun) $query->where('tahun', $tahun);
        $this->applyUserFilter($query, $onlyForUser, $peran);
        return $query->countAllResults();
    }

    /**
     * Hitung persentase realisasi anggaran
     * 
     * @return float
     */
    private function hitungPersentaseRealisasi($tahun = null, $onlyForUser = null, $peran = null)
    {
        $totalAnggaran = $this->hitungTotalAnggaran($tahun, $onlyForUser, $peran);
        $totalRealisasi = $this->hitungTotalRealisasi($tahun, $onlyForUser, $peran);
        
        if ($totalAnggaran > 0) {
            return round(($totalRealisasi / $totalAnggaran) * 100, 0);
        }
        
        return 0;
    }

    /**
     * Hitung persentase capaian PKPT inti (Terlaksana vs Tidak Terlaksana)
     * Hanya menghitung Terlaksana yang sudah DISETUJUI
     * 
     * @return float
     */
    private function hitungPersentaseCapaian($tahun = null, $onlyForUser = null, $peran = null)
    {
        $queryTerlaksana = $this->where('status', 'Terlaksana')->where('is_approved', 1);
        if ($tahun) $queryTerlaksana->where('tahun', $tahun);
        $this->applyUserFilter($queryTerlaksana, $onlyForUser, $peran);
        $terlaksana = $queryTerlaksana->countAllResults();

        $queryTidakTerlaksana = $this->where('status', 'Tidak Terlaksana');
        if ($tahun) $queryTidakTerlaksana->where('tahun', $tahun);
        $this->applyUserFilter($queryTidakTerlaksana, $onlyForUser, $peran);
        $tidakTerlaksana = $queryTidakTerlaksana->countAllResults();

        $totalCore = $terlaksana + $tidakTerlaksana;
        
        if ($totalCore > 0) {
            return round(($terlaksana / $totalCore) * 100, 0);
        }
        
        return 0;
    }

    /**
     * Hitung persentase realisasi pelaksanaan kegiatan secara total
     * (Semua Terlaksana + Tambahan / Semua Kegiatan)
     * 
     * @return float
     */
    private function hitungPersentasePelaksanaan($tahun = null, $onlyForUser = null, $peran = null)
    {
        $totalProgram = $this->hitungJumlahProgram($tahun, $onlyForUser, $peran);
        $terlaksana = $this->hitungJumlahTerlaksana($tahun, $onlyForUser, $peran);
        
        if ($totalProgram > 0) {
            return round(($terlaksana / $totalProgram) * 100, 0);
        }
        
        return 0;
    }

    /**
     * Ambil kegiatan berdasarkan bulan untuk kalender
     * 
     * @param int $year Tahun
     * @param int $month Bulan
     * @return array
     */
    public function getActivitiesByMonth($year, $month)
    {
        $startDate = sprintf('%04d-%02d-01', $year, $month);
        $endDate = date('Y-m-t', strtotime($startDate));
        
        // Query to get all events that overlap with the current month
        return $this->groupStart()
                        ->where('tanggal_mulai <=', $endDate)
                        ->where('tanggal_selesai >=', $startDate)
                    ->groupEnd()
                    ->findAll();
    }

    /**
     * Ambil distribusi status untuk pie chart
     * 
     * @return array
     */
    public function getStatusDistribution($tahun = null, $onlyForUser = null, $peran = null)
    {
        $query = $this->select('status, COUNT(*) as count');
        if ($tahun) $query->where('tahun', $tahun);
        $this->applyUserFilter($query, $onlyForUser, $peran);
        
        $result = $query->groupBy('status')->findAll();
        
        $groups = [
            'core' => [
                'labels' => [],
                'data' => []
            ],
            'additional' => [
                'labels' => [],
                'data' => []
            ]
        ];
        
        foreach ($result as $row) {
            $status = $row['status'] ?: 'Belum Ditentukan';
            if ($status === 'Penugasan Tambahan') {
                $groups['additional']['labels'][] = $status;
                $groups['additional']['data'][] = (int)$row['count'];
            } else {
                $groups['core']['labels'][] = $status;
                $groups['core']['data'][] = (int)$row['count'];
            }
        }
        
        return $groups;
    }

    /**
     * Ambil distribusi status bulanan untuk polygon frequency chart
     * 
     * @param int $year Tahun
     * @return array
     */
    public function getMonthlyStatusDistribution($year, $onlyForUser = null, $peran = null)
    {
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
        $terlaksana = [];
        $tidakTerlaksana = [];
        
        for ($i = 1; $i <= 12; $i++) {
            $startDate = sprintf('%04d-%02d-01', $year, $i);
            $endDate = date('Y-m-t', strtotime($startDate));
            
            // Count Terlaksana (Hanya yang Approved)
            $countTerlaksana = $this->where('tahun', $year)
                                    ->where('status', 'Terlaksana')
                                    ->where('is_approved', 1)
                                    ->where('tanggal_selesai >=', $startDate)
                                    ->where('tanggal_selesai <=', $endDate)
                                    ->countAllResults();
            $terlaksana[] = $countTerlaksana;
            
            // Count Tidak Terlaksana
            $countTidakTerlaksana = $this->where('tahun', $year)
                                         ->where('status', 'Tidak Terlaksana')
                                         ->where('tanggal_selesai >=', $startDate)
                                         ->where('tanggal_selesai <=', $endDate)
                                         ->countAllResults();
            $tidakTerlaksana[] = $countTidakTerlaksana;
        }
        
        return [
            'labels' => $months,
            'terlaksana' => $terlaksana,
            'tidak_terlaksana' => $tidakTerlaksana
        ];
    }

    /**
     * Ambil tren bulanan untuk line chart
     * 
     * @param int $year Tahun
     * @return array
     */
    public function getMonthlyTrend($year, $onlyForUser = null, $peran = null)
    {
        $months = [];
        $anggaran = [];
        $realisasi = [];
        
        for ($i = 1; $i <= 12; $i++) {
            $months[] = date('M', mktime(0, 0, 0, $i, 1));
            
            $startDate = sprintf('%04d-%02d-01', $year, $i);
            $endDate = date('Y-m-t', strtotime($startDate));
            
            // Total anggaran bulan ini
            $bQuery = $this->selectSum('anggaran')
                           ->where('tanggal_mulai >=', $startDate)
                           ->where('tanggal_mulai <=', $endDate);
            $this->applyUserFilter($bQuery, $onlyForUser, $peran);
            $budgetResult = $bQuery->first();
            $anggaran[] = $budgetResult['anggaran'] ?? 0;
            
            // Total realisasi bulan ini
            $rQuery = $this->selectSum('realisasi_anggaran')
                           ->where('tanggal_mulai >=', $startDate)
                           ->where('tanggal_mulai <=', $endDate);
            $this->applyUserFilter($rQuery, $onlyForUser, $peran);
            $realizationResult = $rQuery->first();
            $realisasi[] = $realizationResult['realisasi_anggaran'] ?? 0;
        }
        
        return [
            'labels' => $months,
            'anggaran' => $anggaran,
            'realisasi' => $realisasi
        ];
    }


    /**
     * Ambil perbandingan anggaran vs realisasi
     * 
     * @return array
     */
    public function getBudgetComparison($tahun = null, $onlyForUser = null, $peran = null)
    {
        $statuses = ['Terlaksana', 'Tidak Terlaksana', 'Penugasan Tambahan'];
        $anggaran = [];
        $realisasi = [];
        
        foreach ($statuses as $status) {
            $budgetQuery = $this->selectSum('anggaran')->where('status', $status);
            if ($tahun) $budgetQuery->where('tahun', $tahun);
            $this->applyUserFilter($budgetQuery, $onlyForUser, $peran);
            $budgetResult = $budgetQuery->first();
            $anggaran[] = $budgetResult['anggaran'] ?? 0;
            
            $realizationQuery = $this->selectSum('realisasi_anggaran')->where('status', $status);
            if ($tahun) $realizationQuery->where('tahun', $tahun);
            $this->applyUserFilter($realizationQuery, $onlyForUser, $peran);
            $realizationResult = $realizationQuery->first();
            $realisasi[] = $realizationResult['realisasi_anggaran'] ?? 0;
        }
        
        return [
            'labels' => $statuses,
            'anggaran' => $anggaran,
            'realisasi' => $realisasi
        ];
    }

    /**
     * Ambil kegiatan untuk notifikasi
     * - Kegiatan yang akan mulai dalam 7 hari
     * - Kegiatan yang sudah selesai tapi butuh pembaruan data (realisasi)
     * 
     * @return array
     */
    /**
     * Mengambil kegiatan yang membutuhkan perhatian pengguna (Notifikasi).
     * Meliputi:
     * 1. Kegiatan yang akan dimulai dalam 7 hari ke depan (Upcoming).
     * 2. Kegiatan yang sudah selesai tanggalnya tapi belum ditandai 'Terlaksana' (Needs Update).
     * 
     * @return array Daftar notifikasi
     */
    public function getNotificationActivities()
    {
        $today = date('Y-m-d');
        $sevenDaysLater = date('Y-m-d', strtotime('+7 days'));

        // 1. Upcoming within 7 days
        $upcoming = $this->where('tanggal_mulai >=', $today)
                         ->where('tanggal_mulai <=', $sevenDaysLater)
                         ->where('status !=', 'Terlaksana')
                         ->findAll();

        // 2. Needs modification (finished but no realization/status incomplete)
        // This is a simplified logic: if today > tanggal_selesai and status is not 'Terlaksana'
        $needsUpdate = $this->where('tanggal_selesai <', $today)
                            ->where('status !=', 'Terlaksana')
                            ->findAll();

        // Merge and tag them
        $notifications = [];
        foreach ($upcoming as $item) {
            $item['notif_type'] = 'upcoming';
            $notifications[] = $item;
        }
        foreach ($needsUpdate as $item) {
            $item['notif_type'] = 'needs_update';
            $notifications[] = $item;
        }

        // Sort by ID descending (newest first)
        usort($notifications, function($a, $b) {
            return $b['id'] - $a['id'];
        });

        return $notifications;
    }
}
