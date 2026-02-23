<?= $this->extend('layouts/utama') ?>

<?= $this->section('content') ?>
<?php 
$role = session()->get('role');
$isAdmin = ($role === 'admin');
$isEdit = ($aksi !== 'tambah');

// Data dikunci jika sudah Disetujui Auditor
$isLocked = false;
if (!$isAdmin && $isEdit && ($program_kerja['is_approved'] ?? 0) == 1) {
    $isLocked = true;
}

// Akses upload dokumen: Khusus Admin dan User
$canUpload = false;
if ($role === 'admin') {
    $canUpload = true;
} elseif ($role === 'user' && !$isLocked) {
    $canUpload = true;
}

// Global permission for UI elements
$canModify = !$isLocked || $isAdmin;
?>

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

    <form 
        action="<?= $aksi === 'tambah' ? base_url('program-kerja/simpan') : base_url('program-kerja/perbarui/' . $program_kerja['id']) ?>" 
        method="post" 
        enctype="multipart/form-data"
        class="form"
    >
        <?= csrf_field() ?>

        <!-- Section: Perencanaan (Planning) -->
        <div class="form-section <?= $isLocked ? 'section-locked' : '' ?>">
            <div class="flex justify-between items-center mb-4">
                <h3 class="form-section-title mb-0">
                    Perencanaan PKPT 
                    <?php if ($isLocked): ?>
                        <i class="fas fa-lock ml-2 text-warning" title="Data sudah terkunci (Terlaksana & Disetujui Auditor)"></i>
                    <?php endif; ?>
                </h3>
            </div>
            
            <!-- Tahun & Unit Kerja -->
            <div class="form-grid">
                <div class="form-group">
                    <label for="tahun" class="form-label">Tahun <span class="required">*</span></label>
                    <input type="number" id="tahun" name="tahun" class="form-input <?= $isLocked ? 'bg-gray-100' : '' ?>"
                        value="<?= old('tahun', $program_kerja['tahun'] ?? date('Y')) ?>" 
                        required min="2020" max="2100" placeholder="<?= date('Y') ?>"
                        <?= $isLocked ? 'readonly' : '' ?>>
                </div>
                <div class="form-group">
                    <label for="unit_kerja" class="form-label">Unit Kerja</label>
                    <input type="text" id="unit_kerja" name="unit_kerja" class="form-input bg-gray-100"
                        value="<?= old('unit_kerja', $defaultUnitKerja ?? ($program_kerja['unit_kerja'] ?? '')) ?>" 
                        maxlength="255" placeholder="Nama unit kerja" readonly>
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
                    class="form-input <?= $isLocked ? 'bg-gray-100' : '' ?>"
                    value="<?= old('nama_kegiatan', $program_kerja['nama_kegiatan'] ?? '') ?>"
                    required
                    maxlength="500"
                    placeholder="Masukkan nama kegiatan"
                    <?= $isLocked ? 'readonly' : '' ?>
                >
            </div>

            <!-- Rencana Pelaksanaan & Anggaran -->
            <div class="form-grid">
                <div class="form-group">
                    <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                    <input type="date" id="tanggal_mulai" name="tanggal_mulai" class="form-input <?= $isLocked ? 'bg-gray-100' : '' ?>"
                        value="<?= old('tanggal_mulai', $program_kerja['tanggal_mulai'] ?? '') ?>"
                        <?= $isLocked ? 'readonly' : '' ?>>
                </div>
                <div class="form-group">
                    <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                    <input type="date" id="tanggal_selesai" name="tanggal_selesai" class="form-input <?= $isLocked ? 'bg-gray-100' : '' ?>"
                        value="<?= old('tanggal_selesai', $program_kerja['tanggal_selesai'] ?? '') ?>"
                        <?= $isLocked ? 'readonly' : '' ?>>
                </div>
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
                            class="form-input <?= $isLocked ? 'bg-gray-100' : '' ?>"
                            value="<?= old('anggaran', $program_kerja['anggaran'] ?? '') ?>"
                            required
                            min="0"
                            step="0.01"
                            placeholder="0"
                            <?= $isLocked ? 'readonly' : '' ?>
                        >
                    </div>
                </div>
            </div>

            <!-- Rencana Kegiatan & Sasaran Strategis -->
            <div class="form-grid">
                <div class="form-group">
                    <label for="rencana_kegiatan" class="form-label">
                        Rencana Kegiatan
                    </label>
                    <textarea 
                        id="rencana_kegiatan" 
                        name="rencana_kegiatan" 
                        class="form-textarea <?= $isLocked ? 'bg-gray-100' : '' ?>"
                        rows="4"
                        placeholder="Jelaskan rencana detail kegiatan"
                        <?= $isLocked ? 'readonly' : '' ?>
                    ><?= old('rencana_kegiatan', $program_kerja['rencana_kegiatan'] ?? '') ?></textarea>
                </div>
                <div class="form-group">
                    <label for="sasaran_strategis" class="form-label">
                        Sasaran Strategis
                    </label>
                    <textarea 
                        id="sasaran_strategis" 
                        name="sasaran_strategis" 
                        class="form-textarea <?= $isLocked ? 'bg-gray-100' : '' ?>"
                        rows="4"
                        placeholder="Jelaskan sasaran strategis kegiatan"
                        <?= $isLocked ? 'readonly' : '' ?>
                    ><?= old('sasaran_strategis', $program_kerja['sasaran_strategis'] ?? '') ?></textarea>
                </div>
            </div>

            <!-- Pelaksana Section -->
            <div class="form-group-full" style="margin-top: 10px;">
                <div style="background: #f8fafc; padding: 20px; border-radius: 12px; border: 1px solid #e2e8f0;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                        <h4 style="margin: 0; color: #1e293b; font-size: 0.95rem; font-weight: 700; display: flex; align-items: center; gap: 10px;">
                            <i class="fas fa-users" style="color: #1a2a44;"></i> Tim Pelaksana
                        </h4>
                        <?php if ($isAdmin || (!$isLocked && $role === 'user')): ?>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="tambahBarisPelaksana()">
                            <i class="fas fa-plus mr-1"></i> Tambah Pelaksana
                        </button>
                        <?php endif; ?>
                    </div>

                    <div class="table-responsive">
                        <table class="table" id="table-tim">
                            <thead>
                                <tr>
                                    <th style="width: 30%;">Peran</th>
                                    <th>Nama Pelaksana</th>
                                    <th style="width: 50px;"></th>
                                </tr>
                            </thead>
                            <tbody id="container-pelaksana">
                                <?php 
                                $roles = ['Pengendali Teknis', 'Ketua Tim', 'Anggota', 'Auditor Madya', 'Auditor Muda'];
                                $timData = isset($tim_pelaksana) ? $tim_pelaksana : [];
                                
                                // If empty (new form), add one default row
                                if (empty($timData)): ?>
                                    <tr class="row-pelaksana">
                                        <td>
                                            <select name="tim_peran[]" class="form-input <?= $isLocked ? 'bg-gray-100' : '' ?>" <?= $isLocked ? 'disabled' : '' ?>>
                                                <?php foreach ($roles as $role_choice): ?>
                                                    <option value="<?= $role_choice ?>" <?= $role_choice === 'Ketua Tim' ? 'selected' : '' ?>><?= $role_choice ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <?php if ($isLocked): ?><input type="hidden" name="tim_peran[]" value="Ketua Tim"><?php endif; ?>
                                        </td>
                                        <td>
                                            <input type="text" name="tim_nama[]" class="form-input <?= $isLocked ? 'bg-gray-100' : '' ?>" placeholder="Nama Pelaksana" value="<?= $defaultPelaksana ?? '' ?>" <?= $isLocked ? 'readonly' : '' ?>>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($isAdmin): ?>
                                            <button type="button" class="btn-remove-row" onclick="hapusBarisPelaksana(this)" title="Hapus">
                                                <i class="fas fa-times"></i>
                                            </button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($timData as $tp): ?>
                                        <tr class="row-pelaksana">
                                            <td>
                                                <select name="tim_peran[]" class="form-input <?= $isLocked ? 'bg-gray-100' : '' ?>" <?= $isLocked ? 'disabled' : '' ?>>
                                                    <?php foreach ($roles as $role_choice): ?>
                                                        <option value="<?= $role_choice ?>" <?= ($tp['peran'] == $role_choice) ? 'selected' : '' ?>><?= $role_choice ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <?php if ($isLocked): ?><input type="hidden" name="tim_peran[]" value="<?= esc($tp['peran']) ?>"><?php endif; ?>
                                            </td>
                                            <td>
                                                <input type="text" name="tim_nama[]" class="form-input <?= $isLocked ? 'bg-gray-100' : '' ?>" placeholder="Nama Pelaksana" value="<?= esc($tp['nama_pelaksana']) ?>" <?= $isLocked ? 'readonly' : '' ?>>
                                            </td>
                                            <td class="text-center">
                                                <?php if ($isAdmin || (!$isLocked && $role === 'user')): ?>
                                                <button type="button" class="btn-remove-row" onclick="hapusBarisPelaksana(this)" title="Hapus">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <style>
                        .btn-remove-row {
                            background: #fee2e2;
                            color: #ef4444;
                            border: none;
                            width: 28px;
                            height: 28px;
                            border-radius: 6px;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            cursor: pointer;
                            transition: all 0.2s;
                        }
                        .btn-remove-row:hover {
                            background: #fecaca;
                            transform: scale(1.1);
                        }
                        #table-tim th {
                            background: #f1f5f9;
                            padding: 10px;
                            font-size: 0.8rem;
                            color: #64748b;
                            text-transform: uppercase;
                            letter-spacing: 0.025em;
                        }
                        #table-tim td {
                            padding: 8px 5px;
                            vertical-align: middle;
                        }
                    </style>
                </div>
            </div>
        </div>

        <!-- Section: Pelaporan & Realisasi (Execution) -->
        <div class="form-section">
            <h3 class="form-section-title">Pelaporan & Realisasi</h3>
            
            <div class="form-grid">
                <!-- Realisasi Kegiatan -->
                <div class="form-group">
                    <label for="realisasi_kegiatan" class="form-label">
                        Realisasi Kegiatan
                    </label>
                    <textarea 
                        id="realisasi_kegiatan" 
                        name="realisasi_kegiatan" 
                        class="form-textarea <?= $isLocked ? 'bg-gray-100' : '' ?>"
                        rows="4"
                        placeholder="Jelaskan realisasi kegiatan yang telah dilakukan"
                        <?= $isLocked ? 'readonly' : '' ?>
                    ><?= old('realisasi_kegiatan', $program_kerja['realisasi_kegiatan'] ?? '') ?></textarea>
                </div>

                <!-- Status & Alasan Group -->
                <div class="form-group">
                    <label for="status" class="form-label">Status</label>
                    <select id="status" name="status" class="form-input mb-3 <?= $isLocked ? 'bg-gray-100' : '' ?>" <?= $isLocked ? 'disabled' : '' ?>>
                        <option value="">-- Pilih Status --</option>
                        <option value="Terlaksana" <?= (old('status', $program_kerja['status'] ?? '') == 'Terlaksana') ? 'selected' : '' ?>>Terlaksana</option>
                        <option value="Tidak Terlaksana" <?= (old('status', $program_kerja['status'] ?? '') == 'Tidak Terlaksana') ? 'selected' : '' ?>>Tidak Terlaksana</option>
                        <option value="Penugasan Tambahan" <?= (old('status', $program_kerja['status'] ?? '') == 'Penugasan Tambahan') ? 'selected' : '' ?>>Penugasan Tambahan</option>
                        <option value="Dibatalkan" <?= (old('status', $program_kerja['status'] ?? '') == 'Dibatalkan') ? 'selected' : '' ?>>Dibatalkan</option>
                    </select>
                    <?php if ($isLocked): ?><input type="hidden" name="status" value="<?= esc($program_kerja['status']) ?>"><?php endif; ?>

                    <div id="group-alasan" style="display: <?= in_array(old('status', $program_kerja['status'] ?? ''), ['Tidak Terlaksana', 'Dibatalkan']) ? 'block' : 'none' ?>;">
                        <label for="alasan_tidak_terlaksana" class="form-label">
                            <span id="label-alasan">Alasan Tidak Terlaksana</span> <span class="required">*</span>
                        </label>
                        <textarea 
                            id="alasan_tidak_terlaksana" 
                            name="alasan_tidak_terlaksana" 
                            class="form-textarea <?= $isLocked ? 'bg-gray-100' : '' ?>"
                            rows="2"
                            placeholder="Berikan alasan"
                            <?= $isLocked ? 'readonly' : '' ?>
                        ><?= old('alasan_tidak_terlaksana', $program_kerja['alasan_tidak_terlaksana'] ?? '') ?></textarea>
                        <div id="error-alasan" class="text-xs text-red-500 mt-1" style="display: none;">
                            <i class="fas fa-exclamation-circle mr-1"></i> Alasan wajib diisi.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Realisasi Anggaran -->
            <div class="form-group" style="margin-top: 15px;">
                <label for="realisasi_anggaran" class="form-label">
                    Realisasi Anggaran
                </label>
                <div class="input-group">
                    <span class="input-prefix">Rp</span>
                    <input 
                        type="number" 
                        id="realisasi_anggaran" 
                        name="realisasi_anggaran" 
                        class="form-input <?= $isLocked ? 'bg-gray-100' : '' ?>"
                        value="<?= old('realisasi_anggaran', $program_kerja['realisasi_anggaran'] ?? '') ?>"
                        min="0"
                        step="0.01"
                        placeholder="0"
                        <?= $isLocked ? 'readonly' : '' ?>
                    >
                </div>
            </div>

            <!-- Dokumen Output -->
            <div class="form-group" style="margin-top: 25px;">
                <label class="form-label mb-0">Dokumen Output</label>
                <div class="text-xs text-gray-500 mb-2">
                    Unggah dokumen output terkait kegiatan ini.
                </div>
                
                <div class="bg-gray-50 border border-dashed border-gray-300 rounded-lg p-6 text-center">
                    <?php if ($canUpload): ?>
                    <button type="button" class="btn btn-outline-primary px-6 py-2" onclick="bukaModalDokumen()">
                        <i class="fas <?= $aksi === 'tambah' ? 'fa-cloud-upload-alt' : 'fa-folder-open' ?> mr-2"></i> 
                        <?= $aksi === 'tambah' ? 'Upload Dokumen' : 'Kelola Dokumen' ?>
                    </button>
                    <?php else: ?>
                    <div class="text-muted text-sm"><i class="fas fa-lock mr-2"></i> Dokumen dikunci atau akses terbatas</div>
                    <?php endif; ?>
                    <div id="mini-doc-preview" style="display: flex; flex-direction: column; gap: 10px; width: 100%; margin-top: 20px;"></div>
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
<?= $this->include('program_kerja/partials/modal_dokumen', ['canUpload' => $canUpload]) ?>
<?= $this->include('program_kerja/partials/modal_preview') ?>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    const MODE = '<?= $aksi ?>';
    const PROGRAM_ID = <?= isset($program_kerja['id']) ? $program_kerja['id'] : 'null' ?>;
    
    // Queue for Add Mode: [{file: File, tipe: 'String', id: timestamp}]
    let pendingFiles = [];

    document.addEventListener('DOMContentLoaded', () => {
        // Toggle Alasan Tidak Terlaksana / Dibatalkan
        const statusSelect = document.getElementById('status');
        const groupAlasan = document.getElementById('group-alasan');
        const inputAlasan = document.getElementById('alasan_tidak_terlaksana');
        const labelAlasan = document.getElementById('label-alasan');

        function updateAlasanField() {
            const status = statusSelect.value;
            if (status === 'Tidak Terlaksana' || status === 'Dibatalkan') {
                groupAlasan.style.display = 'block';
                inputAlasan.setAttribute('required', 'required');
                
                if (status === 'Tidak Terlaksana') {
                    labelAlasan.textContent = 'Alasan Tidak Terlaksana';
                    inputAlasan.placeholder = 'Berikan alasan mengapa kegiatan ini tidak terlaksana';
                } else {
                    labelAlasan.textContent = 'Alasan Dibatalkan';
                    inputAlasan.placeholder = 'Berikan alasan mengapa kegiatan ini dibatalkan';
                }
            } else {
                groupAlasan.style.display = 'none';
                inputAlasan.removeAttribute('required');
                inputAlasan.value = ''; // CLEAR THE VALUE
                document.getElementById('error-alasan').style.display = 'none';
            }
        }

        if (statusSelect) {
            statusSelect.addEventListener('change', updateAlasanField);
            // Run on load to handle edit mode or back button
            updateAlasanField();
        }

        const form = document.querySelector('form.form');
        if (form) {
            form.addEventListener('submit', handleFormSubmit);
        }

        if (MODE === 'edit') {
            loadPreviewDokumen();
        }
        
        // Bind the partial's upload button handler
        window.startUploadDokumen = uploadDokumenAjax;
    });

    async function handleFormSubmit(e) {
        // Form Validation for Alasan Tidak Terlaksana
        const status = document.getElementById('status').value;
        const alasan = document.getElementById('alasan_tidak_terlaksana').value.trim();
        const errorMsg = document.getElementById('error-alasan');

        if ((status === 'Tidak Terlaksana' || status === 'Dibatalkan') && alasan === '') {
            e.preventDefault();
            errorMsg.style.display = 'block';
            document.getElementById('alasan_tidak_terlaksana').focus();
            return;
        }

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
        const fileInput = document.getElementById('dm-file');
        const tipeInput = document.getElementById('dm-tipe');
        
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
        fileInput.value = ''; 
        renderQueueList(); // Update list in modal
        
        // Visual feedback
        const btn = document.getElementById('dm-btn-upload');
        const original = btn.innerText;
        btn.innerText = '✓ Ditambahkan';
        setTimeout(() => btn.innerText = original, 1000);
    }

    function removeFromQueue(id) {
        pendingFiles = pendingFiles.filter(item => item.id !== id);
        renderQueueList();
    }

    function getFileIcon(filename) {
        const ext = filename.split('.').pop().toLowerCase();
        let icon = 'fa-file';
        let color = 'text-gray-500';

        switch(ext) {
            case 'pdf': icon = 'fa-file-pdf'; color = 'text-red-500'; break;
            case 'doc': case 'docx': icon = 'fa-file-word'; color = 'text-blue-500'; break;
            case 'xls': case 'xlsx': icon = 'fa-file-excel'; color = 'text-green-500'; break;
            case 'jpg': case 'jpeg': case 'png': icon = 'fa-file-image'; color = 'text-purple-500'; break;
            case 'zip': case 'rar': icon = 'fa-file-archive'; color = 'text-yellow-500'; break;
        }
        return `<i class="fas ${icon} ${color}"></i>`;
    }

    function renderQueueList() {
        const container = document.getElementById('dm-doc-list');
        
        if (pendingFiles.length === 0) {
            container.innerHTML = `
                <div style="display: flex; align-items: center; gap: 8px; color: #475569; font-size: 0.95rem;">
                    <span style="font-size: 1.2rem;">📂</span>
                    <span>Belum ada dokumen yang akan diupload</span>
                </div>
            `;
            return;
        }

        let html = '<div class="space-y-2">';
        pendingFiles.forEach(item => {
             html += `
                <div style="display: flex; align-items: center; justify-content: space-between; padding: 12px; background: white; border: 1px solid #e2e8f0; border-radius: 8px; margin-bottom: 8px;">
                     <div style="display: flex; align-items: center; gap: 12px; overflow: hidden; flex: 1;">
                         <span style="font-size: 1.5rem; flex-shrink: 0; color: #374151;">${getFileIcon(item.file.name)}</span>
                         <div style="min-width: 0; display: flex; flex-direction: column; gap: 2px;">
                             <div style="font-size: 0.9rem; font-weight: 600; color: #1f2937; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="${item.file.name}">${item.file.name}</div>
                             <div style="display: flex; align-items: center; gap: 8px;">
                                 <span style="background: #e0f2fe; padding: 2px 8px; border-radius: 4px; color: #0284c7; font-size: 0.75rem; font-weight: 500;">${item.tipe}</span>
                                 <span style="font-size: 0.75rem; color: #9ca3af;">${(item.file.size / 1024).toFixed(1)} KB</span>
                             </div>
                         </div>
                     </div>
                     <button type="button" onclick="removeFromQueue(${item.id})" style="color: #ef4444; padding: 8px; border: none; background: transparent; cursor: pointer; transition: color 0.2s;" title="Hapus">
                         <i class="fas fa-trash-alt"></i>
                     </button>
                 </div>
            `;
        });
        html += '</div>';
        container.innerHTML = html;
    }
    
    // --- UPDATED PREVIEW LOGIC: Vertical List with Icons ---
    function renderMiniQueuePreview() {
        const container = document.getElementById('mini-doc-preview');
        if (!container) return;
        
        if (pendingFiles.length === 0) {
            container.innerHTML = '';
            return;
        }
        
        let html = '';
        pendingFiles.forEach(item => {
            html += `
            <div style="display: flex; align-items: center; gap: 12px; padding: 10px; background: white; border: 1px solid #e5e7eb; border-radius: 6px; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                <div style="font-size: 1.25rem; width: 32px; text-align: center; flex-shrink: 0;">
                    ${getFileIcon(item.file.name)}
                </div>
                <div style="flex: 1; min-width: 0; text-align: left;">
                    <div style="font-size: 0.875rem; font-weight: 500; color: #1f2937; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="${item.file.name}">${item.file.name}</div>
                    <div style="font-size: 0.75rem; color: #6b7280;">${item.tipe} • ${(item.file.size / 1024).toFixed(1)} KB</div>
                </div>
                <div style="color: #10b981; font-size: 0.75rem; background: #ecfdf5; padding: 2px 8px; border-radius: 4px; display: none;">
                    <i class="fas fa-check"></i> Siap
                </div>
            </div>`;
        });
        
        container.innerHTML = html;
        // Show checkmark on larger screens using JS or just keep hidden for now to match screenshot "Siap" text
        // Actually screenshot showed "Siap", so I'll enable it with inline-block
        const badges = container.querySelectorAll('div[style*="background: #ecfdf5"]');
        badges.forEach(b => b.style.display = 'block');
    }


    /* --- EDIT MODE: AJAX --- */

    async function loadDokumenForm() {
        if(MODE !== 'edit') return;
        
        if(MODE !== 'edit') return;
        
        const container = document.getElementById('dm-doc-list');
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
    
        const fileInput = document.getElementById('dm-file');
        const tipeInput = document.getElementById('dm-tipe');
        const progressBar = document.getElementById('dm-upload-progress');
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
            
            // CSRF Token
            formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

            try {
                const response = await fetch(`<?= base_url('program-kerja/upload-dokumen/') ?>${PROGRAM_ID}`, {
                    method: 'POST', 
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                const result = await response.json();
                if (!result.sukses) {
                    alert('Gagal upload ' + file.name + ': ' + result.pesan);
                }
            } catch (e) { 
                console.error(e);
                alert('Terjadi kesalahan saat mengupload file ' + file.name);
            }
            
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
             // In edit mode we just fetch and render
            loadDokumenForm(); 
        } catch(e) {}
    }
    
    // Render Mini Preview (Works for both, but usually called with DB data in EditMode or Queue in AddMode)
    function renderMiniPreview(docs) {
        const container = document.getElementById('mini-doc-preview');
        if (!container) return;
        
        if (docs.length === 0) {
            container.innerHTML = '';
            return;
        }

        let html = '';
        docs.forEach(doc => {
             // Handle both object formats (DB vs Queue)
            const name = doc.nama_file || doc.file.name;
            html += `
            <div style="display: flex; align-items: center; gap: 12px; padding: 10px; background: white; border: 1px solid #e5e7eb; border-radius: 6px; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                 <div style="font-size: 1.25rem; width: 32px; text-align: center; flex-shrink: 0;">
                    ${getFileIcon(name)}
                </div>
                    <div class="text-xs text-gray-500">${doc.tipe_dokumen || 'Dokumen'}</div>
                </div>
            </div>`;
        });
        container.innerHTML = html;
    }
    
    function renderFormDocList(docs) {
        const container = document.getElementById('dm-doc-list');
        if (!container) return;

        if (docs.length === 0) {
            container.innerHTML = `
                <div style="display: flex; align-items: center; gap: 8px; color: #475569; font-size: 0.95rem;">
                    <span style="font-size: 1.2rem;">📂</span>
                    <span>Belum ada dokumen yang akan diupload</span>
                </div>
            `;
            return;
        }

        let html = '<div class="space-y-2">';
        docs.forEach(doc => {
            const sizeKB = doc.size ? (doc.size / 1024).toFixed(1) + ' KB' : '';
            // Prioritize nama_asli, then display_name, then nama_file
            const fileName = (doc.nama_asli && doc.nama_asli.trim() !== '') ? doc.nama_asli : (doc.display_name || doc.nama_file);
            const escapedFileName = fileName.replace(/'/g, "\\'"); // Escape single quotes for JS string literal
            
            html += `
            <div style="display: flex; align-items: center; justify-content: space-between; padding: 12px; background: white; border: 1px solid #e2e8f0; border-radius: 8px; margin-bottom: 8px;">
                <div style="display: flex; align-items: center; gap: 12px; overflow: hidden; flex: 1;">
                    <span style="font-size: 1.5rem; flex-shrink: 0; color: #374151;">${getFileIcon(fileName)}</span>
                    <div style="min-width: 0; display: flex; flex-direction: column; gap: 2px;">
                        <div style="font-size: 0.9rem; font-weight: 600; color: #1f2937; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; cursor: pointer;" 
                             onclick="bukaPreview(${doc.id}, '${escapedFileName}')" title="Klik untuk preview: ${fileName}">${fileName}</div>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <span style="background: #e0f2fe; padding: 2px 8px; border-radius: 4px; color: #0284c7; font-size: 0.75rem; font-weight: 500;">${doc.tipe_dokumen || 'Dokumen'}</span>
                            <span style="font-size: 0.75rem; color: #9ca3af;">${sizeKB}</span>
                        </div>
                    </div>
                </div>
                <div style="display: flex; align-items: center; gap: 4px;">
                    <!-- Preview Icon -->
                    <button onclick="bukaPreview(${doc.id}, '${fileName}')" type="button" style="color: #6366f1; padding: 8px; border-radius: 4px; border: none; background: transparent; cursor: pointer; transition: color 0.2s;" title="Preview">
                        <i class="fas fa-eye"></i>
                    </button>
                    <!-- Trash Icon -->
                    <button onclick="hapusDokumenAjax(${doc.id})" type="button" style="color: #ef4444; padding: 8px; border-radius: 4px; border: none; background: transparent; cursor: pointer; transition: color 0.2s;" title="Hapus">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>
            </div>`;
        });
        html += '</div>';
        container.innerHTML = html;
    }
    function tambahBarisPelaksana() {
        const container = document.getElementById('container-pelaksana');
        const roles = ['Pengendali Teknis', 'Ketua Tim', 'Anggota', 'Auditor Madya', 'Auditor Muda'];
        
        const tr = document.createElement('tr');
        tr.className = 'row-pelaksana';
        
        let optionsHtml = '';
        roles.forEach(role => {
            optionsHtml += `<option value="${role}">${role}</option>`;
        });
        
        tr.innerHTML = `
            <td>
                <select name="tim_peran[]" class="form-input">
                    ${optionsHtml}
                </select>
            </td>
            <td>
                <input type="text" name="tim_nama[]" class="form-input" placeholder="Nama Pelaksana">
            </td>
            <td class="text-center">
                <button type="button" class="btn-remove-row" onclick="hapusBarisPelaksana(this)" title="Hapus">
                    <i class="fas fa-times"></i>
                </button>
            </td>
        `;
        container.appendChild(tr);
    }

    function hapusBarisPelaksana(btn) {
        const row = btn.closest('tr');
        const totalRows = document.querySelectorAll('.row-pelaksana').length;
        if (totalRows > 1) {
            row.remove();
        } else {
            // Just clear the inputs if it's the last row
            row.querySelector('input').value = '';
            row.querySelector('select').selectedIndex = 0;
        }
    }
</script>
<?= $this->endSection() ?>
