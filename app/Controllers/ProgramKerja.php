<?php

namespace App\Controllers;

use App\Models\ProgramKerjaModel;
use App\Models\DokumenModel;
use App\Models\ProgramKerjaPelaksanaModel;

use CodeIgniter\HTTP\ResponseInterface;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

/**
 * Controller: Program Kerja
 * 
 * Mengelola semua operasi terkait Program Kerja Pengawasan Tahunan (PKPT)
 * Termasuk CRUD, upload dokumen, dan pencarian
 * 
 * @author  PKPT Development Team
 * @created 2026-01-08
 */
class ProgramKerja extends BaseController
{
    protected $programKerjaModel;
    protected $dokumenModel;
    protected $pelaksanaModel;

    protected $validation;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->programKerjaModel = new ProgramKerjaModel();
        $this->dokumenModel = new DokumenModel();
        $this->pelaksanaModel = new ProgramKerjaPelaksanaModel();

        $this->validation = \Config\Services::validation();
    }

    // ... (kode lain tetap sama sampai method lihat)

    /**
     * Lihat detail program kerja (halaman detail)
     * 
     * @param int $id ID program kerja
     * @return string
     */
    public function lihat($id)
    {
        $programKerja = $this->programKerjaModel->ambilDataById($id);

        if (!$programKerja) {
            return redirect()->to(base_url('program-kerja'))
                ->with('gagal', 'Data program kerja tidak ditemukan');
        }

        $data = [
            'judul' => 'Detail Program Kerja',
            'program_kerja' => $programKerja,
            'tim_pelaksana' => $this->pelaksanaModel->getByProgramKerja($id)
        ];

        return view('program_kerja/detail', $data);
    }

    /**
     * Halaman utama - Daftar program kerja
     * 
     * @return string
     */
    public function index()
    {
        $keyword = $this->request->getGet('cari');
        $tahun = $this->request->getGet('tahun');
        
        // Save selected year to session for Dashboard synchronization
        if ($tahun && is_numeric($tahun)) {
            session()->set('pkpt_tahun_aktif', $tahun);
        }
        $perPage = 10;

        // Ambil daftar tahun available untuk dropdown
        $availableYears = $this->programKerjaModel->getYears();

        // Jika ada keyword pencarian atau filter tahun
        if ($keyword || $tahun) {
            $data['program_kerja'] = $this->programKerjaModel->cariProgramKerja($keyword, $perPage, $tahun);
        } else {
            $data['program_kerja'] = $this->programKerjaModel->ambilSemuaData($perPage);
        }

        $data['pager'] = $this->programKerjaModel->pager;
        $data['keyword'] = $keyword;
        $data['tahun_pilih'] = $tahun;
        $data['available_years'] = $availableYears;
        $data['judul'] = 'Program Kerja Pengawasan Tahunan (PKPT)';

        return view('program_kerja/daftar', $data);
    }

    /**
     * Halaman form tambah program kerja baru.
     * Mengambil data unit kerja dan pegawai dari sesi user jika tersedia untuk auto-fill.
     * 
     * @return string Halaman view form tambah
     */
    public function tambah()
    {
        $data = [
            'judul' => 'Tambah Program Kerja',
            'aksi' => 'tambah',
            'program_kerja' => [],
        ];

        // Auto-fill Logic
        $detail = session()->get('user.pegawai_detail');
        $defaultUnitKerja = '';
        $defaultPelaksana = '';

        if ($detail) {
            // 1. Pelaksana: Use 'nama' column
            $defaultPelaksana = $detail['nama'] ?? '';

            // 2. Unit Kerja: Diambil dari kolom 'unit_kerja_es_2' sesuai request
            if (!empty($detail['unit_kerja_es_2'])) {
                $defaultUnitKerja = $detail['unit_kerja_es_2'];
            } 
            // Fallback hanya jika es_2 kosong (untuk backward compatibility)
            elseif (!empty($detail['unit_kerja_es_3'])) {
                $defaultUnitKerja = $detail['unit_kerja_es_3'];
            } elseif (!empty($detail['unit_kerja'])) {
                $defaultUnitKerja = $detail['unit_kerja'];
            }
        }

        $data['defaultUnitKerja'] = $defaultUnitKerja;
        $data['defaultPelaksana'] = $defaultPelaksana;

        // Old auto-fill logic (removed as per instruction)
        // $userId = session()->get('user.id');
        // if ($userId) {
        //     $userModel = new \App\Models\UserModel();
        //     $user = $userModel->find($userId);
            
        //     // Default 1: Try from Users table (if columns manually added)
        //     $defaultUnitKerja = $user['unit_kerja'] ?? ''; 
        //     $defaultPelaksana = $user['nama_lengkap'] ?? $user['username_ldap'];

        //     // Default 2: Try from Session data (Pegawai View) - PRIORITY
        //     $userSession = session('user');
        //     if (!empty($userSession['pegawai_detail'])) {
        //         $detail = $userSession['pegawai_detail'];
                
        //         // Prioritize Eselon 2 unit, fallback to alias or base unit name
        //         if (!empty($detail['unit_kerja_es_2'])) {
        //             $defaultUnitKerja = $detail['unit_kerja_es_2'];
        //         } elseif (!empty($detail['unit_kerja_alias'])) {
        //             $defaultUnitKerja = $detail['unit_kerja_alias'];
        //         } elseif (!empty($detail['nama_unit_kerja'])) {
        //             $defaultUnitKerja = $detail['nama_unit_kerja'];
        //         }

        //         if (!empty($detail['nama'])) {
        //             $defaultPelaksana = $detail['nama'];
        //         }
        //     }
        //     // Fallback (Old Logic): DB Query if session empty
        //     elseif (!empty($user['pegawai_id'])) {
        //         $db = \Config\Database::connect();
        //         if ($db->tableExists('pegawai')) {
        //             $pegawai = $db->table('pegawai')->getWhere(['id' => $user['pegawai_id']])->getRowArray();
        //             if ($pegawai) {
        //                 if (!empty($pegawai['unit_kerja'])) $defaultUnitKerja = $pegawai['unit_kerja'];
        //                 if (!empty($pegawai['nama'])) $defaultPelaksana = $pegawai['nama'];
        //             }
        //         }
        //     }

        //     // Set defaults if not manually filled yet
        //     $data['program_kerja']['unit_kerja'] = $defaultUnitKerja;
        //     $data['program_kerja']['pelaksana']  = $defaultPelaksana;
        // }
        
        return view('program_kerja/form', $data);
    }

    /**
     * Proses simpan data program kerja baru
     * 
     * @return ResponseInterface
     */
    public function simpan()
    {
        // Validasi input
        $rules = $this->programKerjaModel->getValidationRules();
        $status = $this->request->getPost('status');
        if (in_array($status, ['Tidak Terlaksana', 'Dibatalkan'])) {
            $rules['alasan_tidak_terlaksana'] = [
                'rules'  => 'required',
                'errors' => ['required' => 'Alasan wajib diisi jika status tidak terlaksana atau dibatalkan']
            ];
        }

        if (!$this->validate($rules)) {
            return redirect()->back()
                           ->withInput()
                           ->with('errors', $this->validator->getErrors());
        }

        // Ambil data dari form
        $data = [
            'tahun'              => $this->request->getPost('tahun'),
            'nama_kegiatan'      => $this->request->getPost('nama_kegiatan'),
            'tanggal_mulai'      => $this->request->getPost('tanggal_mulai'),
            'tanggal_selesai'    => $this->request->getPost('tanggal_selesai'),
            'unit_kerja'         => $this->request->getPost('unit_kerja'),
            'rencana_kegiatan'   => $this->request->getPost('rencana_kegiatan'),
            'anggaran'           => $this->request->getPost('anggaran'),
            'realisasi_kegiatan' => $this->request->getPost('realisasi_kegiatan'),
            'pelaksana'          => $this->request->getPost('pelaksana'),
            'pengendali_teknis'  => $this->request->getPost('pengendali_teknis'),
            'ketua_tim'          => $this->request->getPost('ketua_tim'),
            'anggota_tim'        => $this->request->getPost('anggota_tim'),
            'realisasi_anggaran' => $this->request->getPost('realisasi_anggaran') ?? 0,
            'sasaran_strategis'  => $this->request->getPost('sasaran_strategis'),
            'status'             => $status,
            'alasan_tidak_terlaksana' => in_array($status, ['Tidak Terlaksana', 'Dibatalkan']) ? $this->request->getPost('alasan_tidak_terlaksana') : null,
        ];



        // Simpan ke database
        if ($this->programKerjaModel->insert($data)) {
            $newId = $this->programKerjaModel->getInsertID();

            // Handle Team Members (Dynamic)
            $timNama = $this->request->getPost('tim_nama');
            $timPeran = $this->request->getPost('tim_peran');

            if ($timNama && is_array($timNama)) {
                foreach ($timNama as $idx => $nama) {
                    if (!empty(trim($nama))) {
                        $this->pelaksanaModel->insert([
                            'program_kerja_id' => $newId,
                            'nama_pelaksana'   => trim($nama),
                            'peran'            => $timPeran[$idx] ?? 'Anggota'
                        ]);
                    }
                }
            }

            // Handle multi-upload dokumen
            $files = $this->request->getFiles();
            if ($files && isset($files['dokumen'])) {
                $tipeDokumenInput = $this->request->getPost('tipe_dokumen');
                
                foreach ($files['dokumen'] as $idx => $file) {
                    if ($file->isValid() && !$file->hasMoved()) {
                        $targetDir = rtrim(WRITEPATH, '/\\') . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'dokumen_output';
                        if (!is_dir($targetDir)) {
                            mkdir($targetDir, 0777, true);
                        }
                        
                        $namaFile = $file->getRandomName();
                        if ($file->move($targetDir, $namaFile)) {
                            // Determine type: if array use index, else use single value
                            $tipe = 'Lampiran';
                            if (is_array($tipeDokumenInput) && isset($tipeDokumenInput[$idx])) {
                                $tipe = $tipeDokumenInput[$idx];
                            } elseif (is_string($tipeDokumenInput) && !empty($tipeDokumenInput)) {
                                $tipe = $tipeDokumenInput;
                            }

                            $this->dokumenModel->insert([
                                'program_kerja_id' => $newId,
                                'nama_file'        => $namaFile,
                                'nama_asli'        => $file->getClientName(),
                                'tipe_dokumen'     => $tipe
                            ]);
                        }
                    }
                }
            }

            return redirect()->to('/program-kerja')
                           ->with('sukses', 'Program kerja berhasil ditambahkan');
        } else {
            return redirect()->back()
                           ->withInput()
                           ->with('gagal', 'Gagal menambahkan program kerja');
        }
    }

    /**
     * Halaman form edit program kerja
     * 
     * @param int $id ID program kerja
     * @return string|ResponseInterface
     */
    public function edit($id)
    {
        $programKerja = $this->programKerjaModel->ambilDataById($id);

        if (!$programKerja) {
            return redirect()->to('/program-kerja')
                           ->with('gagal', 'Data program kerja tidak ditemukan');
        }

        $data['judul'] = 'Edit Program Kerja';
        $data['aksi'] = 'edit';
        $data['program_kerja'] = $programKerja;
        $data['tim_pelaksana'] = $this->pelaksanaModel->getByProgramKerja($id);


        return view('program_kerja/form', $data);
    }

    /**
     * Proses update data program kerja
     * 
     * @param int $id ID program kerja
     * @return ResponseInterface
     */
    public function perbarui($id)
    {
        // Cek apakah data ada
        $programKerja = $this->programKerjaModel->ambilDataById($id);
        if (!$programKerja) {
            return redirect()->to('/program-kerja')
                           ->with('gagal', 'Data program kerja tidak ditemukan');
        }

        // Validasi input
        $rules = $this->programKerjaModel->getValidationRules();
        $status = $this->request->getPost('status');
        if (in_array($status, ['Tidak Terlaksana', 'Dibatalkan'])) {
            $rules['alasan_tidak_terlaksana'] = [
                'rules'  => 'required',
                'errors' => ['required' => 'Alasan wajib diisi jika status tidak terlaksana atau dibatalkan']
            ];
        }

        if (!$this->validate($rules)) {
            return redirect()->back()
                           ->withInput()
                           ->with('errors', $this->validator->getErrors());
        }

        // Ambil data dari form
        $data = [
            'tahun'              => $this->request->getPost('tahun'),
            'nama_kegiatan'      => $this->request->getPost('nama_kegiatan'),
            'tanggal_mulai'      => $this->request->getPost('tanggal_mulai'),
            'tanggal_selesai'    => $this->request->getPost('tanggal_selesai'),
            'unit_kerja'         => $this->request->getPost('unit_kerja'),
            'rencana_kegiatan'   => $this->request->getPost('rencana_kegiatan'),
            'anggaran'           => $this->request->getPost('anggaran'),
            'realisasi_kegiatan' => $this->request->getPost('realisasi_kegiatan'),
            'pelaksana'          => $this->request->getPost('pelaksana'),
            'pengendali_teknis'  => $this->request->getPost('pengendali_teknis'),
            'ketua_tim'          => $this->request->getPost('ketua_tim'),
            'anggota_tim'        => $this->request->getPost('anggota_tim'),
            'realisasi_anggaran' => $this->request->getPost('realisasi_anggaran') ?? 0,
            'sasaran_strategis'  => $this->request->getPost('sasaran_strategis'),
            'status'             => $status,
            'alasan_tidak_terlaksana' => in_array($status, ['Tidak Terlaksana', 'Dibatalkan']) ? $this->request->getPost('alasan_tidak_terlaksana') : null,
        ];



        // Update ke database
        if ($this->programKerjaModel->update($id, $data)) {

            // Update Team Members (Dynamic)
            // Simple approach: Delete existing and re-insert
            $this->pelaksanaModel->where('program_kerja_id', $id)->delete();
            
            $timNama = $this->request->getPost('tim_nama');
            $timPeran = $this->request->getPost('tim_peran');

            if ($timNama && is_array($timNama)) {
                foreach ($timNama as $idx => $nama) {
                    if (!empty(trim($nama))) {
                        $this->pelaksanaModel->insert([
                            'program_kerja_id' => $id,
                            'nama_pelaksana'   => trim($nama),
                            'peran'            => $timPeran[$idx] ?? 'Anggota'
                        ]);
                    }
                }
            }

            // Handle multi-upload dokumen baru
            $files = $this->request->getFiles();
            if ($files && isset($files['dokumen'])) {
                foreach ($files['dokumen'] as $file) {
                    if ($file->isValid() && !$file->hasMoved()) {
                        $targetDir = rtrim(WRITEPATH, '/\\') . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'dokumen_output';
                        if (!is_dir($targetDir)) {
                            mkdir($targetDir, 0777, true);
                        }

                        $namaFile = $file->getRandomName();
                        if ($file->move($targetDir, $namaFile)) {
                            $this->dokumenModel->insert([
                                'program_kerja_id' => $id,
                                'nama_file'        => $namaFile,
                                'nama_asli'        => $file->getClientName(),
                                'tipe_dokumen'     => 'Lampiran'
                            ]);
                        }
                    }
                }
            }

            return redirect()->to('/program-kerja')
                           ->with('sukses', 'Program kerja berhasil diperbarui');
        } else {
            return redirect()->back()
                           ->withInput()
                           ->with('gagal', 'Gagal memperbarui program kerja');
        }
    }

    /**
     * Hapus program kerja
     * 
     * @param int $id ID program kerja
     * @return ResponseInterface
     */
    public function hapus($id)
    {
        $programKerja = $this->programKerjaModel->ambilDataById($id);

        if (!$programKerja) {
            return redirect()->to('/program-kerja')
                           ->with('gagal', 'Data program kerja tidak ditemukan');
        }

        // Hapus file dokumen terkait (via DokumenModel)
        $dokumenTerkait = $this->dokumenModel->where('program_kerja_id', $id)->findAll();
        foreach ($dokumenTerkait as $doc) {
            $filePath = rtrim(WRITEPATH, '/\\') . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'dokumen_output' . DIRECTORY_SEPARATOR . $doc['nama_file'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        // Hapus dari database
        if ($this->programKerjaModel->delete($id)) {
            return redirect()->to('/program-kerja')
                           ->with('sukses', 'Program kerja berhasil dihapus');
        } else {
            return redirect()->to('/program-kerja')
                           ->with('gagal', 'Gagal menghapus program kerja');
        }
    }

    /**
     * Download dokumen output
     * 
     * @param int $id ID program kerja
     * @return ResponseInterface
     */
    public function unduhDokumen($id)
    {
        // Cari dokumen terbaru dari tabel referensi (ambil yang paling akhir diupload)
        $dokumen = $this->dokumenModel->where('program_kerja_id', $id)
                                    ->orderBy('created_at', 'DESC')
                                    ->first();

        if (!$dokumen) {
            return redirect()->to('/program-kerja')
                           ->with('gagal', 'Dokumen tidak ditemukan');
        }

        $path = rtrim(WRITEPATH, '/\\') . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'dokumen_output' . DIRECTORY_SEPARATOR . $dokumen['nama_file'];

        if (!file_exists($path)) {
            return redirect()->to('/program-kerja')
                           ->with('gagal', 'File fisik tidak ditemukan di server. Silakan hubungi administrator.');
        }

        return $this->response->download($path, null)->setFileName($dokumen['nama_asli'] ?? $dokumen['nama_file']);
    }

    /**
     * Lihat detail program kerja (halaman detail)
     * 
     * @param int $id ID program kerja
     * @return string
     */


    /**
     * Ambil detail program kerja (untuk AJAX)
     * 
     * @param int $id ID program kerja
     * @return ResponseInterface
     */
    public function detail($id)
    {
        $programKerja = $this->programKerjaModel->ambilDataById($id);

        if (!$programKerja) {
            return $this->response->setJSON([
                'sukses' => false,
                'pesan'  => 'Data tidak ditemukan'
            ]);
        }

        return $this->response->setJSON([
            'sukses' => true,
            'data'   => $programKerja
        ]);
    }

    /**
     * API AJAX: Mengambil daftar dokumen untuk program kerja tertentu.
     * Digunakan oleh Javascript di halaman detail/form untuk menampilkan list dokumen.
     * 
     * @param int $id ID Program Kerja
     * @return ResponseInterface JSON Data dokumen
     */
    public function dokumen($id)
    {
        // Ambil data dokumen dari DB
        $dokumen = $this->dokumenModel->where('program_kerja_id', $id)
                                     ->orderBy('created_at', 'DESC')
                                     ->findAll();
        
        // Format data untuk respon JSON
        $data = [];
        foreach ($dokumen as $doc) {
            $path = WRITEPATH . 'uploads/dokumen_output/' . $doc['nama_file'];
            // Cek fisik file untuk ukuran
            $doc['size'] = file_exists($path) ? filesize($path) : 0;
            // Gunakan nama asli jika ada, jika tidak gunakan nama sistem
            $doc['display_name'] = !empty($doc['nama_asli']) ? $doc['nama_asli'] : $doc['nama_file'];
            $data[] = $doc;
        }
        
        return $this->response->setJSON([
            'sukses' => true,
            'data'   => $data
        ]);
    }

    /**
     * Mengunggah dokumen baru untuk program kerja tertentu via AJAX.
     * 
     * @param int $id ID Program Kerja
     * @return ResponseInterface JSON Status upload
     */
    public function uploadDokumen($id)
    {
        $programKerja = $this->programKerjaModel->ambilDataById($id);
        if (!$programKerja) {
            return $this->response->setJSON(['sukses' => false, 'pesan' => 'Program kerja tidak ditemukan']);
        }

        $file = $this->request->getFile('file');
        $tipe = $this->request->getPost('tipe_dokumen');

        if (!$file->isValid()) {
            return $this->response->setJSON(['sukses' => false, 'pesan' => $file->getErrorString()]);
        }

        $targetDir = rtrim(WRITEPATH, '/\\') . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'dokumen_output';
        
        // Pastikan direktori ada
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $namaFile = $file->getRandomName();
        if ($file->move($targetDir, $namaFile)) {
            $this->dokumenModel->insert([
                'program_kerja_id' => $id,
                'nama_file'       => $namaFile,
                'nama_asli'       => $file->getClientName(),
                'tipe_dokumen'    => $tipe
            ]);

            return $this->response->setJSON(['sukses' => true, 'pesan' => 'Dokumen berhasil diupload']);
        }

        return $this->response->setJSON(['sukses' => false, 'pesan' => 'Gagal memindahkan file ke folder tujuan']);
    }

    /**
     * API AJAX: Menghapus dokumen tertentu.
     * Menghapus record di database dan file fisik di server.
     * 
     * @param int $id ID Dokumen
     * @return ResponseInterface JSON Status
     */
    public function hapusDokumen($id)
    {
        $dokumen = $this->dokumenModel->find($id);
        if (!$dokumen) {
            return $this->response->setJSON(['sukses' => false, 'pesan' => 'Dokumen tidak ditemukan']);
        }

        // Hapus file fisik
        $path = rtrim(WRITEPATH, '/\\') . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'dokumen_output' . DIRECTORY_SEPARATOR . $dokumen['nama_file'];
        if (file_exists($path)) {
            unlink($path);
        }

        $this->dokumenModel->delete($id);

        return $this->response->setJSON(['sukses' => true, 'pesan' => 'Dokumen berhasil dihapus']);
    }

    /**
     * Mendownload file dokumen.
     * Mengatur header agar browser mengunduh file, bukan hanya menampilkannya.
     * 
     * @param int $id ID Dokumen
     * @return ResponseInterface Download Stream
     */
    public function download($id)
    {
        $dokumen = $this->dokumenModel->find($id);
        if (!$dokumen) {
            return redirect()->back()->with('gagal', 'Dokumen tidak ditemukan');
        }

        $path = rtrim(WRITEPATH, '/\\') . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'dokumen_output' . DIRECTORY_SEPARATOR . $dokumen['nama_file'];

        if (!file_exists($path)) {
            return redirect()->back()->with('gagal', 'File fisik tidak ditemukan di server.');
        }

        return $this->response->download($path, null)->setFileName($dokumen['nama_asli'] ?? $dokumen['nama_file']);
    }

    /**
     * Preview dokumen di browser (Inline).
     * Berguna untuk menampilkan PDF atau gambar dalam Iframe/Tab baru tanpa download.
     * 
     * @param int $id ID Dokumen
     * @return ResponseInterface File Stream (Inline)
     */
    public function preview($id)
    {
        // Safe output buffering clear
        while (ob_get_level() > 0) {
            ob_end_clean();
        }
        
        $dokumen = $this->dokumenModel->find($id);
        if (!$dokumen) {
            header('HTTP/1.1 404 Not Found');
            echo 'Dokumen tidak ditemukan di database (ID: ' . $id . ')';
            exit;
        }

        $path = rtrim(WRITEPATH, '/\\') . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'dokumen_output' . DIRECTORY_SEPARATOR . $dokumen['nama_file'];
        
        // Log for debugging
        log_message('debug', 'Preview attempt - ID: ' . $id . ', nama_file: ' . $dokumen['nama_file'] . ', path: ' . $path);
        
        if (!file_exists($path)) {
            header('HTTP/1.1 404 Not Found');
            echo 'File fisik tidak ditemukan di server.<br>';
            echo 'Detail: ' . $dokumen['nama_file'] . '<br>';
            echo 'Silakan hubungi administrator untuk verifikasi data.';
            exit;
        }

        $mime = mime_content_type($path);
        $filename = $dokumen['nama_asli'] ?? $dokumen['nama_file'];
        $filesize = filesize($path);
        
        // Set headers for inline preview
        header('Content-Type: ' . $mime);
        header('Content-Disposition: inline; filename="' . $filename . '"');
        header('Content-Length: ' . $filesize);
        header('Cache-Control: public, max-age=3600');
        header('Accept-Ranges: bytes');
        
        // Output file content directly
        readfile($path);
        exit;
    }
    /**
     * Ekspor seluruh data program kerja ke Excel (.xlsx) dengan format profesional
     * 
     * @return void
     */
    public function exportExcel()
    {
        $keyword = $this->request->getGet('cari');
        $tahun = $this->request->getGet('tahun');

        // Ambil query dari model untuk mendapatkan dokumen_output juga
        $db = \Config\Database::connect();
        $subQuery = $db->table('program_kerja_dokumen')
            ->select("GROUP_CONCAT(CONCAT(id, ':', COALESCE(nama_asli, nama_file), ':', COALESCE(tipe_dokumen, 'Dokumen')) SEPARATOR '|')")
            ->where('program_kerja_id = program_kerja.id')
            ->orderBy('created_at', 'DESC')
            ->getCompiledSelect();

        $query = $this->programKerjaModel->select('program_kerja.*')
                      ->select("($subQuery) as dokumen_output");
        
        if ($keyword || $tahun) {
            if ($keyword) {
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
        }

        $data = $query->orderBy('created_at', 'DESC')->findAll();

        // Create new Spreadsheet object
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Program Kerja');

        // Header column
        $headers = [
            'No', 'Tahun', 'Nama Kegiatan', 'Tanggal Mulai', 'Tanggal Selesai', 
            'Unit Kerja', 'Rencana Kegiatan', 'Anggaran', 'Realisasi Kegiatan', 
            'Pelaksana', 'Dokumen', 'Realisasi Anggaran', 'Sasaran Strategis', 'Status', 'Keterangan'
        ];

        // Fill headers
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $col++;
        }

        // Style for Header
        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '28A745'], // Match user's green header request
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ];
        $sheet->getStyle('A1:O1')->applyFromArray($headerStyle);
        $sheet->getRowDimension(1)->setRowHeight(25);

        // Data rows
        $rowIdx = 2;
        $no = 1;
        $baseUrl = base_url('program-kerja/download-dokumen/');

        foreach ($data as $row) {
            // Process dokumen_output for readable text
            $dokumenList = [];
            if (!empty($row['dokumen_output'])) {
                $docs = explode('|', $row['dokumen_output']);
                foreach ($docs as $docStr) {
                    $parts = explode(':', $docStr);
                    if (count($parts) >= 2) {
                        $docType = $parts[2] ?? 'Dokumen';
                        $dokumenList[] = "[$docType] " . $parts[1];
                    }
                }
            }
            $dokumenText = implode("\n", $dokumenList);

            $sheet->setCellValue('A' . $rowIdx, $no++);
            $sheet->setCellValue('B' . $rowIdx, $row['tahun']);
            $sheet->setCellValue('C' . $rowIdx, $row['nama_kegiatan']);
            $sheet->setCellValue('D' . $rowIdx, $row['tanggal_mulai']);
            $sheet->setCellValue('E' . $rowIdx, $row['tanggal_selesai']);
            $sheet->setCellValue('F' . $rowIdx, $row['unit_kerja']);
            $sheet->setCellValue('G' . $rowIdx, $row['rencana_kegiatan']);
            $sheet->setCellValue('H' . $rowIdx, $row['anggaran']);
            $sheet->setCellValue('I' . $rowIdx, $row['realisasi_kegiatan']);
            $pelaksanaParts = [];
            if (!empty($row['pengendali_teknis'])) $pelaksanaParts[] = "PT: " . $row['pengendali_teknis'];
            
            $ketuaText = $row['ketua_tim'] ?: $row['pelaksana'];
            if (!empty($ketuaText)) $pelaksanaParts[] = "KT: " . $ketuaText;
            
            if (!empty($row['anggota_tim'])) $pelaksanaParts[] = "Anggota: " . str_replace(["\r\n", "\n", "\r"], ", ", $row['anggota_tim']);
            
            $pelaksanaText = implode("\n", $pelaksanaParts) ?: '-';

            $sheet->setCellValue('J' . $rowIdx, $pelaksanaText);
            $sheet->getStyle('J' . $rowIdx)->getAlignment()->setWrapText(true);
            $sheet->setCellValue('K' . $rowIdx, $dokumenText);
            $sheet->getStyle('K' . $rowIdx)->getAlignment()->setWrapText(true); // Wrap text for documents
            $sheet->setCellValue('L' . $rowIdx, $row['realisasi_anggaran']);
            $sheet->setCellValue('M' . $rowIdx, $row['sasaran_strategis']);
            $sheet->setCellValue('N' . $rowIdx, $row['status']);
            $sheet->setCellValue('O' . $rowIdx, $row['alasan_tidak_terlaksana']);

            // Style for data cell (borders)
            $sheet->getStyle('A' . $rowIdx . ':O' . $rowIdx)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            $rowIdx++;
        }

        // Auto column width & text wrapping
        foreach (range('A', 'O') as $colId) {
            if ($colId !== 'K') { // Don't auto-size documents column to avoid extreme width
                $sheet->getColumnDimension($colId)->setAutoSize(true);
            } else {
                $sheet->getColumnDimension($colId)->setWidth(30);
            }
        }

        // Specific formatting for currency (IDR)
        $idrFormat = '_-"Rp"* #,##0_-;-"Rp"* #,##0_-;_-"Rp"* "-"_-;_-@_-';
        $sheet->getStyle('H2:H' . ($rowIdx - 1))->getNumberFormat()->setFormatCode($idrFormat);
        $sheet->getStyle('L2:L' . ($rowIdx - 1))->getNumberFormat()->setFormatCode($idrFormat);

        // Set date format
        $sheet->getStyle('D2:E' . ($rowIdx - 1))->getNumberFormat()->setFormatCode('DD/MM/YYYY');

        // Center No and Tahun
        $sheet->getStyle('A2:B' . ($rowIdx - 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        // Wrap text for Keterangan
        $sheet->getStyle('O2:O' . ($rowIdx - 1))->getAlignment()->setWrapText(true);
        $sheet->getColumnDimension('O')->setWidth(30);

        // Header Filtering
        $sheet->setAutoFilter('A1:O1');

        // Redirect output to a client’s web browser (Xlsx)
        $yearPrefix = $tahun ? "Tahun_{$tahun}_" : "";
        $filename = "PKPT_{$yearPrefix}Export_" . date('Y-m-d_H_i_s') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit();
    }
}
