<?php

namespace App\Controllers;

use App\Models\ProgramKerjaModel;
use App\Models\DokumenModel;

use CodeIgniter\HTTP\ResponseInterface;

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

    protected $validation;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->programKerjaModel = new ProgramKerjaModel();
        $this->dokumenModel = new DokumenModel();

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
            'program_kerja' => $programKerja
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
     * Halaman form tambah program kerja
     * 
     * @return string
     */
    public function tambah()
    {
        $data['judul'] = 'Tambah Program Kerja';
        $data['aksi'] = 'tambah';
        $data['program_kerja'] = []; 

        // Autofill Logic: Get user from session
        $userId = session()->get('user.id');
        if ($userId) {
            $userModel = new \App\Models\UserModel();
            $user = $userModel->find($userId);
            
            // Default 1: Try from Users table (if columns manually added)
            $defaultUnitKerja = $user['unit_kerja'] ?? ''; 
            $defaultPelaksana = $user['nama_lengkap'] ?? $user['username_ldap'];

            // Default 2: Try from Pegawai table (if linked)
            if (!empty($user['pegawai_id'])) {
                $db = \Config\Database::connect();
                if ($db->tableExists('pegawai')) {
                    $pegawai = $db->table('pegawai')->getWhere(['id' => $user['pegawai_id']])->getRowArray();
                    if ($pegawai) {
                        if (!empty($pegawai['unit_kerja'])) $defaultUnitKerja = $pegawai['unit_kerja'];
                        if (!empty($pegawai['nama'])) $defaultPelaksana = $pegawai['nama'];
                    }
                }
            }

            // Set defaults if not manually filled yet
            $data['program_kerja']['unit_kerja'] = $defaultUnitKerja;
            $data['program_kerja']['pelaksana']  = $defaultPelaksana;
        }
        
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
        if (!$this->validate($this->programKerjaModel->getValidationRules())) {
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
            'realisasi_anggaran' => $this->request->getPost('realisasi_anggaran') ?? 0,
            'sasaran_strategis'  => $this->request->getPost('sasaran_strategis'),
            'status'             => $this->request->getPost('status'),
        ];



        // Simpan ke database
        if ($this->programKerjaModel->insert($data)) {
            $newId = $this->programKerjaModel->getInsertID();

            // Handle multi-upload dokumen
            $files = $this->request->getFiles();
            if ($files && isset($files['dokumen'])) {
                $tipeDokumenInput = $this->request->getPost('tipe_dokumen');
                
                foreach ($files['dokumen'] as $idx => $file) {
                    if ($file->isValid() && !$file->hasMoved()) {
                        $namaFile = $file->getRandomName();
                        $file->move(WRITEPATH . 'uploads/dokumen_output', $namaFile);
                        
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
                            'tipe_dokumen'     => $tipe
                        ]);
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
        if (!$this->validate($this->programKerjaModel->getValidationRules())) {
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
            'realisasi_anggaran' => $this->request->getPost('realisasi_anggaran') ?? 0,
            'sasaran_strategis'  => $this->request->getPost('sasaran_strategis'),
            'status'             => $this->request->getPost('status'),
        ];



        // Update ke database
        if ($this->programKerjaModel->update($id, $data)) {

            // Handle multi-upload dokumen baru
            $files = $this->request->getFiles();
            if ($files && isset($files['dokumen'])) {
                foreach ($files['dokumen'] as $file) {
                    if ($file->isValid() && !$file->hasMoved()) {
                        $namaFile = $file->getRandomName();
                        $file->move(WRITEPATH . 'uploads/dokumen_output', $namaFile);
                        
                        $this->dokumenModel->insert([
                            'program_kerja_id' => $id,
                            'nama_file'        => $namaFile,
                            'tipe_dokumen'     => 'Lampiran'
                        ]);
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
            $filePath = WRITEPATH . 'uploads/dokumen_output/' . $doc['nama_file'];
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

        $filePath = WRITEPATH . 'uploads/dokumen_output/' . $dokumen['nama_file'];

        if (!file_exists($filePath)) {
            return redirect()->to('/program-kerja')
                           ->with('gagal', 'File dokumen tidak ditemukan di server');
        }

        return $this->response->download($filePath, null);
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
     * Ambil daftar dokumen (AJAX)
     */
    public function dokumen($id)
    {
        $dokumen = $this->dokumenModel->where('program_kerja_id', $id)
                                     ->orderBy('created_at', 'DESC')
                                     ->findAll();
        
        return $this->response->setJSON([
            'sukses' => true,
            'data'   => $dokumen
        ]);
    }

    /**
     * Upload dokumen (AJAX)
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

        $namaFile = $file->getRandomName();
        $file->move(WRITEPATH . 'uploads/dokumen_output', $namaFile);

        $this->dokumenModel->insert([
            'program_kerja_id' => $id,
            'nama_file'       => $namaFile,
            'tipe_dokumen'    => $tipe
        ]);

        return $this->response->setJSON(['sukses' => true, 'pesan' => 'Dokumen berhasil diupload']);
    }

    /**
     * Hapus dokumen (AJAX)
     */
    public function hapusDokumen($id)
    {
        $dokumen = $this->dokumenModel->find($id);
        if (!$dokumen) {
            return $this->response->setJSON(['sukses' => false, 'pesan' => 'Dokumen tidak ditemukan']);
        }

        // Hapus file fisik
        $path = WRITEPATH . 'uploads/dokumen_output/' . $dokumen['nama_file'];
        if (file_exists($path)) {
            unlink($path);
        }

        $this->dokumenModel->delete($id);

        return $this->response->setJSON(['sukses' => true, 'pesan' => 'Dokumen berhasil dihapus']);
    }

    /**
     * Download dokumen
     */
    public function download($id)
    {
        $dokumen = $this->dokumenModel->find($id);
        if (!$dokumen) {
            return redirect()->back()->with('gagal', 'Dokumen tidak ditemukan');
        }

        $path = WRITEPATH . 'uploads/dokumen_output/' . $dokumen['nama_file'];
        if (!file_exists($path)) {
            return redirect()->back()->with('gagal', 'File fisik tidak ditemukan');
        }

        return $this->response->download($path, null); 
    }
}
