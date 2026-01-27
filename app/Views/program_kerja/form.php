<?= $this->extend('layouts/utama') ?>

<?= $this->section('content') ?>

<!-- Header Halaman -->
<div class="page-header">
    <div class="page-header-content">
        <h2 class="page-title"><?= $judul ?></h2>
        <p class="page-subtitle">
            <?= $aksi === 'tambah' ? 'Formulir Program Kerja' : 'Edit Program Kerja' ?>
        </p>
    </div>
    <div class="page-header-actions">
        <a href="<?= base_url('program-kerja') ?>" class="btn btn-secondary">
            Kembali
        </a>
    </div>
</div>

<!-- Form -->
<div class="form-container">
    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-gagal">
            <span class="alert-icon">✕</span>
            <div>
                <strong>Terjadi kesalahan:</strong>
                <ul class="error-list">
                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <button class="alert-close" onclick="this.parentElement.remove()">×</button>
        </div>
    <?php endif; ?>

    <form 
        action="<?= $aksi === 'tambah' ? base_url('program-kerja/simpan') : base_url('program-kerja/perbarui/' . $program_kerja['id']) ?>" 
        method="post" 
        enctype="multipart/form-data"
        class="form"
    >
        <?= csrf_field() ?>

        <!-- Section: Informasi Dasar -->
        <div class="form-section">
            <h3 class="form-section-title">📋 Informasi Dasar</h3>
            
            <!-- Tahun & Unit Kerja -->
            <div class="form-grid">
                <div class="form-group">
                    <label for="tahun" class="form-label">Tahun <span class="required">*</span></label>
                    <input type="number" id="tahun" name="tahun" class="form-input"
                        value="<?= old('tahun', $program_kerja['tahun'] ?? date('Y')) ?>" 
                        required min="2020" max="2100" placeholder="<?= date('Y') ?>">
                </div>
                <div class="form-group">
                    <label for="unit_kerja" class="form-label">Unit Kerja</label>
                    <input type="text" id="unit_kerja" name="unit_kerja" class="form-input"
                        value="<?= old('unit_kerja', $program_kerja['unit_kerja'] ?? '') ?>" 
                        maxlength="255" placeholder="Nama unit kerja">
                </div>
            </div>

            <!-- Nama Kegiatan - Full Width -->
            <div class="form-group form-group-full">
                <label for="nama_kegiatan" class="form-label">
                    Nama Kegiatan <span class="required">*</span>
                </label>
                <input 
                    type="text" 
                    id="nama_kegiatan" 
                    name="nama_kegiatan" 
                    class="form-input"
                    value="<?= old('nama_kegiatan', $program_kerja['nama_kegiatan'] ?? '') ?>"
                    required
                    maxlength="500"
                    placeholder="Masukkan nama kegiatan"
                >
            </div>

            <!-- Rencana Pelaksanaan -->
            <div class="form-grid">
                <div class="form-group">
                    <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                    <input type="date" id="tanggal_mulai" name="tanggal_mulai" class="form-input"
                        value="<?= old('tanggal_mulai', $program_kerja['tanggal_mulai'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                    <input type="date" id="tanggal_selesai" name="tanggal_selesai" class="form-input"
                        value="<?= old('tanggal_selesai', $program_kerja['tanggal_selesai'] ?? '') ?>">
                </div>
            </div>

            <!-- 2 Column Grid -->
            <div class="form-grid">
                <!-- Anggaran -->
                <div class="form-group">
                    <label for="anggaran" class="form-label">
                        Anggaran <span class="required">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-prefix">Rp</span>
                        <input 
                            type="number" 
                            id="anggaran" 
                            name="anggaran" 
                            class="form-input"
                            value="<?= old('anggaran', $program_kerja['anggaran'] ?? '') ?>"
                            required
                            min="0"
                            step="0.01"
                            placeholder="0"
                        >
                    </div>
                </div>

                <!-- Pelaksana -->
                <div class="form-group">
                    <label for="pelaksana" class="form-label">
                        Pelaksana/PIC
                    </label>
                    <input 
                        type="text" 
                        id="pelaksana" 
                        name="pelaksana" 
                        class="form-input bg-gray-100"
                        value="<?= old('pelaksana', $program_kerja['pelaksana'] ?? '') ?>"
                        maxlength="255"
                        placeholder="Nama pelaksana atau PIC"
                        readonly
                    >
                </div>
            </div>
        </div>

        <!-- Section: Rencana & Realisasi -->
        <div class="form-section">
            <h3 class="form-section-title">📝 Rencana & Realisasi</h3>
            
            <div class="form-grid">
                <!-- Rencana Kegiatan -->
                <div class="form-group">
                    <label for="rencana_kegiatan" class="form-label">
                        Rencana Kegiatan
                    </label>
                    <textarea 
                        id="rencana_kegiatan" 
                        name="rencana_kegiatan" 
                        class="form-textarea"
                        rows="4"
                        placeholder="Jelaskan rencana detail kegiatan"
                    ><?= old('rencana_kegiatan', $program_kerja['rencana_kegiatan'] ?? '') ?></textarea>
                </div>

                <!-- Realisasi Kegiatan -->
                <div class="form-group">
                    <label for="realisasi_kegiatan" class="form-label">
                        Realisasi Kegiatan
                    </label>
                    <textarea 
                        id="realisasi_kegiatan" 
                        name="realisasi_kegiatan" 
                        class="form-textarea"
                        rows="4"
                        placeholder="Jelaskan realisasi kegiatan yang telah dilakukan"
                    ><?= old('realisasi_kegiatan', $program_kerja['realisasi_kegiatan'] ?? '') ?></textarea>
                </div>
            </div>

            <!-- Realisasi Anggaran (Simple) -->
            <div class="form-group" style="grid-column: 1 / -1;">
                <label for="realisasi_anggaran" class="form-label">
                    Realisasi Anggaran
                </label>
                <div class="input-group">
                    <span class="input-prefix">Rp</span>
                    <input 
                        type="number" 
                        id="realisasi_anggaran" 
                        name="realisasi_anggaran" 
                        class="form-input"
                        value="<?= old('realisasi_anggaran', $program_kerja['realisasi_anggaran'] ?? '') ?>"
                        min="0"
                        step="0.01"
                        placeholder="0"
                    >
                </div>
            </div>


        </div>

        <!-- Section: Dokumen & Sasaran -->
        <div class="form-section">
            <h3 class="form-section-title">📁 Dokumen & Sasaran</h3>
            
            <!-- Dokumen Output -->
            <div class="form-group" style="grid-column: 1 / -1;">
                <label class="form-label">Dokumen Output</label>
                
                <?php if ($aksi === 'tambah'): ?>
                    <!-- Mode Added: Button to Open Modal (Queue Mode) -->
                    <div class="bg-gray-50 border border-dashed border-gray-300 rounded p-4 text-center">
                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="bukaModalDokumen()">
                            Upload Dokumen
                        </button>
                        <div id="mini-doc-preview" class="mt-2 flex flex-wrap gap-2 justify-center"></div>
                    </div>

                <?php else: ?>
                    <!-- Mode Edit: Button to Open Modal -->
                    <div class="bg-gray-50 border border-dashed border-gray-300 rounded p-4 text-center">
                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="bukaModalDokumen()">
                            Kelola Dokumen
                        </button>
                        <div id="mini-doc-preview" class="mt-2 flex flex-wrap gap-2 justify-center"></div>
                    </div>
                <?php endif; ?>
            </div>

            <div class="form-grid">
                <!-- Sasaran Strategis -->
                <div class="form-group">
                    <label for="sasaran_strategis" class="form-label">
                        Sasaran Strategis
                    </label>
                    <textarea 
                        id="sasaran_strategis" 
                        name="sasaran_strategis" 
                        class="form-textarea"
                        rows="3"
                        placeholder="Jelaskan sasaran strategis kegiatan"
                    ><?= old('sasaran_strategis', $program_kerja['sasaran_strategis'] ?? '') ?></textarea>
                </div>

                <!-- Status -->
                <div class="form-group">
                    <label for="status" class="form-label">
                        Status
                    </label>
                    <select 
                        id="status" 
                        name="status" 
                        class="form-input">
                        <option value="">-- Pilih Status --</option>
                        <option value="Terlaksana" <?= (old('status', $program_kerja['status'] ?? '') == 'Terlaksana') ? 'selected' : '' ?>>Terlaksana</option>
                        <option value="Tidak Terlaksana" <?= (old('status', $program_kerja['status'] ?? '') == 'Tidak Terlaksana') ? 'selected' : '' ?>>Tidak Terlaksana</option>
                        <option value="Penugasan Tambahan" <?= (old('status', $program_kerja['status'] ?? '') == 'Penugasan Tambahan') ? 'selected' : '' ?>>Penugasan Tambahan</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="form-actions">
            <button type="submit" class="btn btn-primary btn-lg">
                <?= $aksi === 'tambah' ? 'Simpan' : 'Perbarui' ?>
            </button>
            <a href="<?= base_url('program-kerja') ?>" class="btn btn-secondary btn-lg">
                Batal
            </a>
        </div>
    </form>
</div>

<!-- Document Management Modal (Reusable) -->
<div id="modal-dokumen" class="modal-overlay">
    <div class="modal-container">
        <div class="modal-header">
            <h3 class="modal-title">Kelola Dokumen Output</h3>
            <button type="button" class="modal-close" onclick="tutupModalDokumen()">×</button>
        </div>
        
        <div class="modal-body">
            <!-- List Section -->
            <div id="form-doc-list" class="doc-list-container">
                <!-- Content loaded via AJAX -->
            </div>

            <!-- Upload Section -->
            <div class="doc-upload-section">
                <div class="form-group mb-2">
                    <label class="text-sm font-medium mb-1 block">Jenis Dokumen</label>
                    <select id="ajax-tipe-dokumen" class="form-select text-sm h-9">
                        <option value="Surat Tugas">Surat Tugas</option>
                        <option value="Laporan">Laporan</option>
                        <option value="Dokumen Komunikasi">Dokumen Komunikasi</option>
                        <option value="Bukti Dukung">Bukti Dukung</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>
                <div class="form-group mb-0">
                    <label class="text-sm font-medium mb-1 block">File</label>
                    <div class="flex gap-2 items-center">
                        <input type="file" id="ajax-file-input" class="form-file text-sm flex-1" multiple>
                        <button type="button" class="btn btn-primary" onclick="uploadDokumenAjax()">Upload</button>
                    </div>
                    <!-- Progress Bar -->
                    <div id="upload-progress" class="hidden mt-2">
                        <div class="w-full bg-gray-200 rounded-full h-1.5">
                            <div class="bg-blue-600 h-1.5 rounded-full" style="width: 0%"></div>
                        </div>
                        <div class="text-xs text-center mt-1 text-gray-500">Mengupload...</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    const MODE = '<?= $aksi ?>';
    const PROGRAM_ID = <?= isset($program_kerja['id']) ? $program_kerja['id'] : 'null' ?>;
    
    // Queue for Add Mode: [{file: File, tipe: 'String', id: timestamp}]
    let pendingFiles = [];

    document.addEventListener('DOMContentLoaded', () => {
        if (MODE === 'edit') {
            loadPreviewDokumen();
        }
        
        // Intercept form submit only for Add Mode to append files
        if (MODE === 'tambah') {
            const form = document.querySelector('form.form');
            form.addEventListener('submit', handleFormSubmit);
        }
    });

    async function handleFormSubmit(e) {
        if (pendingFiles.length === 0) return; // Normal submit if no files
        
        e.preventDefault();
        
        const form = e.target;
        const formData = new FormData(form);
        
        // Append pending files and their types
        pendingFiles.forEach((item, index) => {
            formData.append('dokumen[]', item.file);
            formData.append('tipe_dokumen[]', item.tipe);
        });

        const btn = form.querySelector('button[type="submit"]');
        const originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = 'Menyimpan...';

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            if (response.redirected) {
                window.location.href = response.url;
            } else {
                // If backend returns JSON with redirect URL
                 const text = await response.text();
                 try {
                     const json = JSON.parse(text);
                     if(json.redirect) window.location.href = json.redirect;
                     else window.location.href = '<?= base_url('program-kerja') ?>';
                 } catch(e) {
                     // Fallback if HTML response
                     window.location.href = '<?= base_url('program-kerja') ?>';
                 }
            }
        } catch (error) {
            console.error(error);
            alert('Gagal menyimpan data');
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
    }

    function bukaModalDokumen() {
        document.getElementById('modal-dokumen').classList.add('show');
        if (MODE === 'edit') {
            loadDokumenForm();
        } else {
            renderQueueList();
        }
    }

    function tutupModalDokumen() {
        document.getElementById('modal-dokumen').classList.remove('show');
        if(MODE === 'tambah') renderMiniQueuePreview();
    }

    /* --- ADD MODE: CLIENT SIDE QUEUE --- */
    
    function addToQueue() {
        const fileInput = document.getElementById('ajax-file-input');
        const tipeInput = document.getElementById('ajax-tipe-dokumen');
        
        if (fileInput.files.length === 0) {
            alert('Pilih file terlebih dahulu');
            return;
        }
        
        Array.from(fileInput.files).forEach(file => {
            pendingFiles.push({
                id: Date.now() + Math.random(),
                file: file,
                tipe: tipeInput.value
            });
        });
        
        fileInput.value = ''; 
        renderQueueList(); // Update list in modal
        
        // Visual feedback
        const btn = document.querySelector('.doc-upload-section button');
        const original = btn.innerText;
        btn.innerText = '✓ Ditambahkan';
        setTimeout(() => btn.innerText = original, 1000);
    }

    function removeFromQueue(id) {
        pendingFiles = pendingFiles.filter(item => item.id !== id);
        renderQueueList();
    }

    function renderQueueList() {
        const container = document.getElementById('form-doc-list');
        
        if (pendingFiles.length === 0) {
            container.innerHTML = `
                <div class="flex flex-col items-center justify-center py-6 text-gray-400 border-2 border-dashed rounded-lg">
                    <span class="text-xl mb-1">📂</span>
                    <span class="text-sm">Belum ada dokumen yang akan diupload</span>
                </div>
            `;
            return;
        }

        let html = '<div class="space-y-2">';
        pendingFiles.forEach(item => {
             html += `
                <div class="flex items-center justify-between p-2 bg-blue-50 rounded border border-blue-100">
                    <div class="flex items-center gap-3 overflow-hidden">
                        <span class="text-xl shrink-0">📄</span>
                        <div class="min-w-0">
                            <div class="text-sm font-medium truncate text-gray-700">${item.file.name}</div>
                            <div class="text-xs text-gray-500">
                                <span class="bg-white px-1.5 rounded border border-blue-100 text-blue-600">${item.tipe}</span>
                                <span class="ml-2">${(item.file.size / 1024).toFixed(1)} KB</span>
                            </div>
                        </div>
                    </div>
                    <button type="button" onclick="removeFromQueue(${item.id})" class="text-red-500 hover:text-red-700 p-1">✕</button>
                </div>
            `;
        });
        html += '</div>';
        container.innerHTML = html;
    }
    
    function renderMiniQueuePreview() {
        const container = document.getElementById('mini-doc-preview');
        if (!container) return;
        
        if (pendingFiles.length === 0) {
            container.innerHTML = '';
            return;
        }
        
        let html = '';
        pendingFiles.slice(0, 3).forEach(item => {
            html += `<span class="px-2 py-1 rounded bg-blue-50 text-blue-700 text-xs border border-blue-100 truncate max-w-[100px]">${item.file.name}</span>`;
        });
        
        if(pendingFiles.length > 3) html += `<span class="px-2 py-1 bg-gray-100 text-xs text-gray-600 rounded">+${pendingFiles.length - 3}</span>`;
        
        container.innerHTML = html;
    }


    /* --- EDIT MODE: AJAX --- */

    async function loadDokumenForm() {
        if(MODE !== 'edit') return;
        
        const container = document.getElementById('form-doc-list');
        container.innerHTML = '<div class="text-center p-4 text-muted">Memuat...</div>';

        try {
            const response = await fetch(`<?= base_url('program-kerja/dokumen/') ?>${PROGRAM_ID}`);
            const result = await response.json();

            if (result.sukses) {
                renderFormDocList(result.data);
                renderMiniPreview(result.data);
            } else {
                container.innerHTML = '<div class="text-danger">Gagal memuat</div>';
            }
        } catch (e) {
            container.innerHTML = '<div class="text-danger">Error koneksi</div>';
        }
    }

    // Unified upload function routed by MODE
    async function uploadDokumenAjax() {
        if (MODE === 'tambah') {
            addToQueue();
            return;
        }
    
        const fileInput = document.getElementById('ajax-file-input');
        const tipeInput = document.getElementById('ajax-tipe-dokumen');
        const progressBar = document.getElementById('upload-progress');
        const progressFill = progressBar.querySelector('.bg-blue-600');

        if (fileInput.files.length === 0) {
            alert('Pilih file dulu');
            return;
        }

        progressBar.classList.remove('hidden');
        progressFill.style.width = '50%';
        
        const files = Array.from(fileInput.files);
        let completed = 0;
        
        // Upload one by one for Edit Mode (to match existing backend endpoint)
        for (let file of files) {
            const formData = new FormData();
            formData.append('file', file);
            formData.append('tipe_dokumen', tipeInput.value);
            
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (csrfToken) formData.append('csrf_token', csrfToken.content);

            try {
                await fetch(`<?= base_url('program-kerja/upload-dokumen/') ?>${PROGRAM_ID}`, {
                    method: 'POST', body: formData
                });
            } catch (e) { console.error(e); }
            
            completed++;
            progressFill.style.width = Math.round((completed/files.length)*100) + '%';
        }

        progressBar.classList.add('hidden');
        fileInput.value = '';
        loadDokumenForm();
    }
    
    async function hapusDokumenAjax(id) {
        if (!confirm('Hapus file ini?')) return;
        try {
            await fetch(`<?= base_url('program-kerja/hapus-dokumen/') ?>${id}`, {method: 'DELETE', headers: {'X-Requested-With': 'XMLHttpRequest'}});
            loadDokumenForm();
        } catch(e) {alert('Gagal hapus');}
    }

    /* --- SHARED --- */
    
    // Preview for Edit Mode (Existing docs)
    async function loadPreviewDokumen() {
        try {
            const response = await fetch(`<?= base_url('program-kerja/dokumen/') ?>${PROGRAM_ID}`);
            const result = await response.json();
            if (result.sukses) renderMiniPreview(result.data);
        } catch(e) {}
    }
    
    // Render Mini Preview (Works for both, but usually called with DB data in EditMode or Queue in AddMode)
    function renderMiniPreview(docs) {
        const container = document.getElementById('mini-doc-preview');
        if (!container) return;
        let html = '';
        docs.slice(0, 5).forEach(doc => {
             // Handle both object formats (DB vs Queue)
            const name = doc.nama_file || doc.file.name;
            html += `<span class="px-2 py-1 rounded bg-gray-100 text-xs truncate max-w-[100px]">${name}</span>`;
        });
        container.innerHTML = html;
    }
    
    function renderFormDocList(docs) {
         const container = document.getElementById('form-doc-list');
         if (docs.length === 0) {
            container.innerHTML = `
                <div class="flex flex-col items-center justify-center py-6 text-gray-400 border-2 border-dashed rounded-lg">
                    <span class="text-2xl mb-1">📂</span>
                    <span class="text-sm">Belum ada dokumen</span>
                </div>
            `;
            return;
        }

        let html = '<div class="space-y-2">';
        docs.forEach(doc => {
            html += `
                <div class="flex items-center justify-between p-2 bg-white rounded border hover:bg-gray-50">
                    <div class="flex items-center gap-3 overflow-hidden">
                        <span class="text-xl shrink-0">📄</span>
                        <div class="min-w-0">
                            <div class="text-sm font-medium truncate">${doc.nama_file}</div>
                            <div class="text-xs text-gray-500">${doc.tipe_dokumen}</div>
                        </div>
                    </div>
                    <button onclick="hapusDokumenAjax(${doc.id})" type="button" class="text-red-500 p-1">🗑</button>
                </div>
            `;
        });
        html += '</div>';
        container.innerHTML = html;
    }
</script>
<?= $this->endSection() ?>
