<?= $this->extend('layouts/utama') ?>

<?= $this->section('content') ?>
<?php 
$role = session()->get('role');
$isAdmin = ($role === 'admin');
$userName = session()->get('user.pegawai_detail.nama') ?? session()->get('user.name');

$isAssigned = false;
if ($isAdmin) {
    $isAssigned = true;
} else {
    // Role User/Auditor: Pembuat (creator), Ketua Tim, atau listed in team
    if (($program_kerja['created_by'] ?? '') === $userName || ($program_kerja['ketua_tim'] ?? '') === $userName) {
        $isAssigned = true;
    } else {
        foreach ($tim_pelaksana as $tp) {
            if ($tp['nama_pelaksana'] === $userName) {
                $isAssigned = true;
                break;
            }
        }
    }
}

// Cek Penguncian Data (LOCKED jika sudah Approved Auditor)
$isLocked = false;
if (!$isAdmin && ($program_kerja['is_approved'] ?? 0) == 1) {
    $isLocked = true;
}

$canModify = ($isAdmin || ($isAssigned && !$isLocked));

// Akses upload dokumen: admin selalu bisa; user/pelaksana yang terlibat bisa
// Auditor yang tidak terlibat sebagai pelaksana tidak bisa upload
$canUpload = false;
if ($role === 'admin') {
    // Admin selalu bisa upload
    $canUpload = true;
} elseif ($role === 'user') {
    // User bisa upload jika terlibat & data belum dikunci
    if (!$isLocked) {
        if (!empty($program_kerja['created_by']) && $program_kerja['created_by'] === $userName) {
            $canUpload = true;
        } elseif (!empty($program_kerja['ketua_tim']) && $program_kerja['ketua_tim'] === $userName) {
            $canUpload = true;
        } else {
            foreach ($tim_pelaksana as $tp) {
                if ($tp['nama_pelaksana'] === $userName) {
                    $canUpload = true;
                    break;
                }
            }
        }
    }
}
// Catatan: role 'auditor' tidak mendapat akses upload, hanya bisa melihat daftar dokumen
?>

<!-- Header Halaman -->
<div class="page-header">
    <div class="page-header-content">
        <h2 class="page-title">Detail Program Kerja</h2>
        <p class="page-subtitle">Informasi lengkap program kerja pengawasan</p>
    </div>
    <div class="page-header-actions">
        <?php if ($canModify): ?>
        <a href="<?= base_url('program-kerja/edit/' . $program_kerja['id']) ?>" class="btn btn-primary">
            Edit
        </a>
        <?php endif; ?>
        
        <?php if ($canModify): ?>
        <button onclick="konfirmasiHapus(<?= $program_kerja['id'] ?>, '<?= esc($program_kerja['nama_kegiatan']) ?>')" 
                class="btn btn-danger">
            Hapus
        </button>
        <?php endif; ?>
        <a href="<?= base_url('program-kerja') ?>" class="btn btn-secondary">
            Kembali
        </a>
    </div>
</div>

<!-- Detail Content -->
<div class="detail-container">
    <!-- 2 Column Layout -->
    <div class="detail-two-column">
        <!-- Left Column -->
        <div class="detail-column-left">
            <!-- Section: Informasi Dasar -->
            <div class="detail-section">
                <h3 class="detail-section-title">Informasi Dasar</h3>
                
                <div class="detail-grid">
                    <div class="detail-item">
                        <label class="detail-label">Tahun</label>
                        <div class="detail-value"><?= esc($program_kerja['tahun']) ?></div>
                    </div>

                    <div class="detail-item">
                        <label class="detail-label">Unit Kerja</label>
                        <div class="detail-value">
                            <?= !empty($program_kerja['unit_kerja']) ? esc($program_kerja['unit_kerja']) : '<span class="text-muted">-</span>' ?>
                        </div>
                    </div>
                </div>

                <div class="detail-item detail-item-full">
                    <label class="detail-label">Nama Kegiatan</label>
                    <div class="detail-value"><?= esc($program_kerja['nama_kegiatan']) ?></div>
                </div>

                <div class="detail-grid">
                    <div class="detail-item">
                        <label class="detail-label">Rencana Pelaksanaan</label>
                        <div class="detail-value">
                            <?php if (!empty($program_kerja['tanggal_mulai']) && !empty($program_kerja['tanggal_selesai'])): ?>
                                <?= date('d F Y', strtotime($program_kerja['tanggal_mulai'])) ?> s/d 
                                <?= date('d F Y', strtotime($program_kerja['tanggal_selesai'])) ?>
                                <?php 
                                $start = new DateTime($program_kerja['tanggal_mulai']);
                                $end = new DateTime($program_kerja['tanggal_selesai']);
                                $diff = $start->diff($end);
                                $days = $diff->days + 1;
                                ?>
                                <br><small class="text-muted">(<?= $days ?> hari)</small>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="detail-item detail-item-full" style="background: #f8fafc; padding: 20px; border-radius: 12px; border: 1px solid #e2e8f0; margin-top: 15px;">
                        <label class="detail-label" style="display: flex; align-items: center; gap: 10px; margin-bottom: 15px; font-weight: 700; color: #1a2a44; font-size: 0.95rem;">
                            <i class="fas fa-users" style="color: #6366f1;"></i> Tim Pelaksana & Peran
                        </label>
                        
                        <?php if (!empty($tim_pelaksana)): ?>
                            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 12px;">
                                <?php foreach ($tim_pelaksana as $tp): ?>
                                    <div style="background: white; padding: 12px 15px; border-radius: 10px; border: 1px solid #edf2f7; display: flex; align-items: center; gap: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.02);">
                                        <div style="width: 38px; height: 38px; background: #e0e7ff; color: #4338ca; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; flex-shrink: 0;">
                                            <i class="fas fa-user-tie"></i>
                                        </div>
                                        <div style="min-width: 0;">
                                            <div style="font-size: 0.7rem; font-weight: 700; color: #6366f1; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">
                                                <?= esc($tp['peran']) ?>
                                            </div>
                                            <div style="font-size: 0.9rem; font-weight: 600; color: #1e293b; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="<?= esc($tp['nama_pelaksana']) ?>">
                                                <?= esc($tp['nama_pelaksana']) ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div style="text-align: center; padding: 20px; color: #94a3b8; font-style: italic;">
                                <i class="fas fa-info-circle mr-1"></i> Data tim pelaksana belum tersedia atau belum diatur.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="detail-grid">
                    <div class="detail-item">
                        <label class="detail-label">Anggaran</label>
                        <div class="detail-value detail-value-highlight">
                            Rp <?= number_format($program_kerja['anggaran'], 0, ',', '.') ?>
                        </div>
                    </div>

                    <div class="detail-item">
                        <label class="detail-label">Status Pelaksanaan</label>
                        <div class="detail-value mb-3">
                            <?php if (!empty($program_kerja['status'])): ?>
                                <span class="badge badge-<?= strtolower(str_replace(' ', '-', $program_kerja['status'])) ?>">
                                    <?= esc($program_kerja['status']) ?>
                                </span>
                                
                                <?php if (($program_kerja['status'] == 'Tidak Terlaksana' || $program_kerja['status'] == 'Dibatalkan') && !empty($program_kerja['alasan_tidak_terlaksana'])): ?>
                                    <div class="mt-2 p-3 bg-red-50 border-l-4 border-red-500 rounded text-sm text-red-700">
                                        <strong>Alasan:</strong><br>
                                        <?= nl2br(esc($program_kerja['alasan_tidak_terlaksana'])) ?>
                                    </div>
                                <?php endif; ?>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </div>

                        <label class="detail-label">Status Persetujuan (Auditor)</label>
                        <div class="detail-value mb-4">
                            <?php if ($program_kerja['is_approved']): ?>
                                <span class="badge badge-terlaksana">
                                    <i class="fas fa-check-circle mr-1"></i> Disetujui
                                </span>
                                <div class="text-xs text-gray-500 mt-1">
                                    Oleh: <?= esc($program_kerja['approved_by'] ?? 'Auditor') ?><br>
                                    Pd: <?= date('d/m/Y H:i', strtotime($program_kerja['approved_at'])) ?>
                                </div>
                                <?php if (session()->get('role') === 'auditor'): ?>
                                    <a href="<?= base_url('program-kerja/batalSetujui/'.$program_kerja['id']) ?>" class="btn btn-sm btn-outline-danger mt-2" onclick="return confirm('Batalkan persetujuan ini?')">
                                        <i class="fas fa-times mr-1"></i> Batalkan
                                    </a>
                                <?php endif; ?>
                            <?php else: ?>
                                <span class="badge badge-dibatalkan">
                                    <i class="fas fa-clock mr-1"></i> Belum Disetujui
                                </span>
                                <?php if (session()->get('role') === 'auditor' && $program_kerja['status'] !== 'Terlaksana'): ?>
                                    <div class="text-xs text-info mt-2">
                                        <i class="fas fa-info-circle mr-1"></i> Menunggu status 'Terlaksana' untuk disetujui.
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>

                        <!-- Review & Validasi Auditor -->
                        <label class="detail-label">Review & Validasi Auditor</label>
                        <div class="detail-value">
                            <?php if ($program_kerja['is_approved']): ?>
                                <div class="p-3 bg-blue-50 border-l-4 border-blue-500 rounded text-sm text-blue-800">
                                    <strong>Catatan Review:</strong><br>
                                    <?= nl2br(esc($program_kerja['catatan_auditor'] ?: 'Tidak ada catatan.')) ?>
                                </div>
                            <?php elseif (session()->get('role') === 'auditor'): ?>
                                <div id="approval-container">
                                    <form id="form-approval-auditor" action="<?= base_url('program-kerja/setujui/'.$program_kerja['id']) ?>" method="post">
                                        <?= csrf_field() ?>
                                        <textarea name="catatan_auditor" class="form-textarea text-sm w-full" rows="3" placeholder="Tambahkan catatan validasi atau review di sini..."><?= esc($program_kerja['catatan_auditor'] ?? '') ?></textarea>
                                        <?php if ($program_kerja['status'] === 'Terlaksana'): ?>
                                            <button type="submit" class="btn btn-primary btn-sm mt-2 w-full" style="position: relative;">
                                                <i class="fas fa-check-double mr-1"></i> Simpan & Setujui
                                                <?php if (!empty($program_kerja['catatan_auditor'])): ?>
                                                    <span style="position: absolute; top: -5px; right: -5px; width: 10px; height: 10px; background: #ef4444; border: 2px solid white; border-radius: 50%; display: block;" title="Memiliki catatan/review"></span>
                                                <?php endif; ?>
                                            </button>
                                        <?php else: ?>
                                            <div class="text-xs text-gray-500 mt-2">
                                                <em>Catatan dapat disimpan saat menyetujui program (setelah status 'Terlaksana').</em>
                                            </div>
                                        <?php endif; ?>
                                    </form>
                                </div>
                            <?php else: ?>
                                <div class="text-muted italic text-sm">Belum ada review dari auditor.</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section: Rencana & Realisasi -->
            <div class="detail-section">
                <h3 class="detail-section-title">Rencana & Realisasi</h3>
                
                <div class="detail-grid">
                    <div class="detail-item">
                        <label class="detail-label">Rencana Kegiatan</label>
                        <div class="detail-value detail-value-text">
                            <?= !empty($program_kerja['rencana_kegiatan']) ? nl2br(esc($program_kerja['rencana_kegiatan'])) : '<span class="text-muted">Belum ada rencana</span>' ?>
                        </div>
                    </div>

                    <div class="detail-item">
                        <label class="detail-label">Realisasi Kegiatan</label>
                        <div class="detail-value detail-value-text">
                            <?= !empty($program_kerja['realisasi_kegiatan']) ? nl2br(esc($program_kerja['realisasi_kegiatan'])) : '<span class="text-muted">Belum ada realisasi</span>' ?>
                        </div>
                    </div>
                </div>

                <div class="detail-item">
                    <div class="flex justify-between items-center mb-2">
                        <label class="detail-label">Realisasi Anggaran</label>
                    </div>

                    <div class="budget-card">
                        <div class="budget-header">
                            <span class="budget-label">Total Realisasi</span>
                            <span class="budget-amount text-primary">Rp <?= number_format($program_kerja['realisasi_anggaran'], 0, ',', '.') ?></span>
                        </div>
                        <?php 
                        $persentase = $program_kerja['anggaran'] > 0 
                            ? ($program_kerja['realisasi_anggaran'] / $program_kerja['anggaran']) * 100 
                            : 0;
                        ?>
                        <div class="detail-progress mb-4">
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: <?= min($persentase, 100) ?>%"></div>
                            </div>
                            <span class="progress-text"><?= number_format($persentase, 1) ?>% dari Pagu Rp <?= number_format($program_kerja['anggaran'], 0, ',', '.') ?></span>
                        </div>


                    </div>
                </div>
            </div>

            <!-- Section: Sasaran Strategis -->
            <div class="detail-section">
                <h3 class="detail-section-title">Sasaran Strategis</h3>
                
                <div class="detail-item detail-item-full">
                    <div class="detail-value detail-value-text">
                        <?= !empty($program_kerja['sasaran_strategis']) ? nl2br(esc($program_kerja['sasaran_strategis'])) : '<span class="text-muted">-</span>' ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="detail-column-right">
            <!-- Section: Dokumen Output -->
            <div class="detail-section">
                <h3 class="detail-section-title">Dokumen Output</h3>
                
                <div class="detail-item detail-item-full">
                    <div id="dokumen-list-preview">
                        <!-- Preview list will be loaded here -->
                        <div class="text-muted text-sm mb-3">Memuat dokumen...</div>
                    </div>
                    
                    <?php if ($canUpload): ?>
                    <button type="button" class="btn btn-primary mt-3" onclick="bukaModalDokumen()">
                        <i class="fas fa-folder-open mr-1"></i> Kelola Dokumen
                    </button>
                    <?php endif; ?>
                 </div>
                    
                    <!-- Legacy support (fallback) -->
                    <?php if (!empty($program_kerja['dokumen_output'])): ?>
                        <div class="mt-3 pt-3 border-t border-gray-200">
                             <small class="text-muted block mb-1">Dokumen Lama (Legacy):</small>
                             <div class="detail-file">
                                <span class="file-icon">📄</span>
                                <span class="file-name"><?= esc($program_kerja['dokumen_output']) ?></span>
                                <a href="<?= base_url('program-kerja/unduh-dokumen/' . $program_kerja['id']) ?>" class="btn btn-sm btn-primary">Unduh</a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Section: Informasi Sistem -->
            <div class="detail-section detail-section-muted">
                <h3 class="detail-section-title">Informasi Sistem</h3>
                
                <div class="detail-item">
                    <label class="detail-label">Dibuat Pada</label>
                    <div class="detail-value detail-value-sm">
                        <?= date('d F Y, H:i', strtotime($program_kerja['created_at'])) ?> WIB
                    </div>
                </div>

                <div class="detail-item">
                    <label class="detail-label">Terakhir Diperbarui</label>
                    <div class="detail-value detail-value-sm">
                        <?= date('d F Y, H:i', strtotime($program_kerja['updated_at'])) ?> WIB
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Document Management Modal -->
<?= $this->include('program_kerja/partials/modal_dokumen', ['canUpload' => $canUpload]) ?>
<?= $this->include('program_kerja/partials/modal_preview') ?>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
/**
 * Konfirmasi hapus program kerja
 */
function konfirmasiHapus(id, namaKegiatan) {
    if (confirm(`Apakah Anda yakin ingin menghapus program kerja:\n"${namaKegiatan}"?\n\nData yang dihapus tidak dapat dikembalikan.`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?= base_url('program-kerja/hapus/') ?>' + id;
        
        // CSRF Protection
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '<?= csrf_token() ?>';
        csrfInput.value = '<?= csrf_hash() ?>';
        form.appendChild(csrfInput);
        
        document.body.appendChild(form);
        form.submit();
    }
}

// --- DOCUMENT MANAGEMENT JS ---
const PROGRAM_ID = <?= $program_kerja['id'] ?>;

function getFileIcon(filename) {
    const ext = filename.split('.').pop().toLowerCase();
    let icon = 'fa-file'; 
    let color = 'text-gray-500';

    switch(ext) {
        case 'pdf': icon = 'fa-file-pdf'; color = 'text-red-500'; break;
        case 'doc': case 'docx': icon = 'fa-file-word'; color = 'text-blue-500'; break;
        case 'xls': case 'xlsx': icon = 'fa-file-excel'; color = 'text-green-500'; break;
        case 'ppt': case 'pptx': icon = 'fa-file-powerpoint'; color = 'text-orange-500'; break;
        case 'jpg': case 'jpeg': case 'png': icon = 'fa-file-image'; color = 'text-purple-500'; break;
        case 'zip': case 'rar': icon = 'fa-file-archive'; color = 'text-yellow-500'; break;
    }
    return `<i class="fas ${icon} ${color}"></i>`;
}

function bukaModalDokumen() {
    document.getElementById('modal-dokumen').classList.add('show');
    loadDokumen();
}

function tutupModalDokumen() {
    document.getElementById('modal-dokumen').classList.remove('show');
}

async function loadDokumen() {
    const container = document.getElementById('dm-doc-list');
    const preview = document.getElementById('dokumen-list-preview');
    
    // Only update modal container if it exists (modal might not be rendered yet)
    if (container) {
        container.innerHTML = '<div class="text-center p-4 text-muted">Memuat...</div>';
    }
    
    try {
        const response = await fetch(`<?= base_url('program-kerja/dokumen/') ?>${PROGRAM_ID}`);
        const result = await response.json();
        
        if (result.sukses) {
            // Update modal list only if modal is open
            if (container) {
                renderDocList(result.data);
            }
            // Always update preview card (this is what shows on the detail page)
            if (preview) {
                updatePreview(result.data);
            }
        } else {
            if (container) {
                container.innerHTML = '<div class="text-center p-4 text-danger">Gagal memuat dokumen</div>';
            }
            if (preview) {
                preview.innerHTML = '<div class="text-danger text-sm">Gagal memuat dokumen</div>';
            }
        }
    } catch (e) {
        console.error(e);
        if (container) {
            container.innerHTML = '<div class="text-center p-4 text-danger">Terjadi kesalahan koneksi</div>';
        }
        if (preview) {
            preview.innerHTML = '<div class="text-danger text-sm">Terjadi kesalahan koneksi</div>';
        }
    }
}

function renderDocList(docs) {
    const container = document.getElementById('dm-doc-list');
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
        const fileName = (doc.nama_asli && doc.nama_asli.trim() !== '') ? doc.nama_asli : (doc.display_name || doc.nama_file);

        html += `
            <div style="display: flex; align-items: center; justify-content: space-between; padding: 12px; background: white; border: 1px solid #e2e8f0; border-radius: 8px; margin-bottom: 8px;">
                <div style="display: flex; align-items: center; gap: 12px; overflow: hidden; flex: 1;">
                    <span style="font-size: 1.5rem; flex-shrink: 0; color: #374151;">${getFileIcon(fileName)}</span>
                    <div style="min-width: 0; display: flex; flex-direction: column; gap: 2px;">
                         <div style="font-size: 0.9rem; font-weight: 600; color: #1f2937; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; cursor: pointer;" 
                              onclick="bukaPreview(${doc.id}, '${fileName}')" title="Klik untuk preview: ${fileName}">${fileName}</div>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <span style="background: #e0f2fe; padding: 2px 8px; border-radius: 4px; color: #0284c7; font-size: 0.75rem; font-weight: 500;">${doc.tipe_dokumen || 'Dokumen'}</span>
                            <span style="font-size: 0.75rem; color: #9ca3af;">${sizeKB}</span>
                        </div>
                    </div>
                </div>
                <!-- Only Trash Icon -->
                <?php if ($canUpload): ?>
                <button onclick="hapusDokumen(${doc.id})" type="button" style="color: #ef4444; padding: 8px; border-radius: 4px; border: none; background: transparent; cursor: pointer; transition: color 0.2s;" title="Hapus">
                    <i class="fas fa-trash-alt"></i>
                </button>
                <?php endif; ?>
            </div>
        `;
    });
    html += '</div>';
    container.innerHTML = html;
}

/**
 * Fungsi untuk memperbarui tampilan preview dokumen di halaman detail.
 * Mengambil data dokumen dari server dan merendernya menjadi daftar vertikal.
 * 
 * @param {Array} docs Array objek dokumen dari respon AJAX
 */
function updatePreview(docs) {
    const preview = document.getElementById('dokumen-list-preview');
    
    // Cek jika tidak ada dokumen
    if (docs.length === 0) {
        preview.innerHTML = '<div class="text-muted text-sm mb-3">Belum ada dokumen yang diunggah</div>';
        return;
    }

    // Mulai membuat HTML string untuk daftar dokumen
    let html = '<div class="doc-preview-list" style="border: 1px solid #e5e7eb; border-radius: 8px; overflow: hidden;">';
    
    // Loop setiap dokumen untuk dibuatkan item list
    docs.forEach((doc, index) => {
        // Use shared getFileIcon function
        // Also prefer display_name if available
        const fileName = (doc.nama_asli && doc.nama_asli.trim() !== '') ? doc.nama_asli : (doc.display_name || doc.nama_file);
        const iconHtml = window.getFileIcon ? getFileIcon(fileName) : '<i class="fas fa-file"></i>';

        // Tambahkan garis pembatas kecuali untuk item terakhir
        const borderClass = index !== docs.length - 1 ? 'border-bottom: 1px solid #e5e7eb;' : '';
        
        // Template HTML untuk satu item dokumen (Compact)
        html += `
            <div class="doc-preview-item" style="display: flex; align-items: center; justify-content: space-between; padding: 8px 12px; background: #fff; ${borderClass}">
                <div style="display: flex; align-items: center; gap: 12px; flex: 1; min-width: 0;">
                    <div style="font-size: 1.25em; width: 24px; text-align: center;">${iconHtml}</div>
                    <div style="min-width: 0; flex: 1; width: 0;">
                        <div class="text-sm font-medium text-gray-900" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 100%;" title="${fileName}">${fileName}</div>
                        <div class="text-xs text-muted" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">${doc.tipe_dokumen || 'Dokumen'} • ${new Date(doc.created_at).toLocaleDateString('id-ID')}</div>
                    </div>
                </div>
                <button type="button" 
                   onclick="bukaPreview(${doc.id}, '${fileName.replace(/'/g, "\\'")}')"
                   class="btn btn-sm btn-outline-secondary" 
                   style="margin-left: 12px; white-space: nowrap; display: inline-flex; align-items: center; gap: 6px; padding: 4px 12px; border-radius: 4px; font-size: 0.85rem; border: 1px solid #d1d5db;">
                    <span>Lihat</span>
                    <i class="fas fa-eye" style="font-size: 0.8em;"></i>
                </button>
            </div>
        `;
    });
    html += '</div>';
    
    // Tampilkan total dokumen jika lebih dari 5
    if (docs.length > 5) {
        html += `<div class="text-xs text-muted mt-2 text-right">Total ${docs.length} dokumen</div>`;
    }
    
    // Render ke elemen HTML
    preview.innerHTML = html;
}

async function uploadDokumen() {
    const fileInput = document.getElementById('dm-file');
    const tipeInput = document.getElementById('dm-tipe');
    const btn = document.getElementById('dm-btn-upload');
    const progressContainer = document.getElementById('dm-upload-progress');
    const progressBar = progressContainer ? progressContainer.querySelector('.progress-fill-upload') : null;
    const progressText = progressContainer ? progressContainer.querySelector('.progress-text-upload') : null;

    if (!fileInput.files.length) {
        alert('Pilih file terlebih dahulu');
        return;
    }

    const files = Array.from(fileInput.files);
    const tipe = tipeInput.value;

    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Mengupload...';

    // Show progress
    if (progressContainer) {
        progressContainer.style.display = 'block';
    }

    let successCount = 0;
    let failCount = 0;
    const csrfToken = document.querySelector('meta[name="csrf-token"]');

    for (let i = 0; i < files.length; i++) {
        // Update progress
        if (progressBar) {
            const pct = ((i + 1) / files.length * 100).toFixed(0);
            progressBar.style.width = pct + '%';
        }
        if (progressText) {
            progressText.textContent = `Mengupload file ${i + 1} dari ${files.length}...`;
        }

        const formData = new FormData();
        formData.append('file', files[i]);
        formData.append('tipe_dokumen', tipe);
        
        // Append CSRF Token
        formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

        try {
            const response = await fetch(`<?= base_url('program-kerja/upload-dokumen/') ?>${PROGRAM_ID}`, {
                method: 'POST',
                body: formData
            });
            const result = await response.json();

            if (result.sukses) {
                successCount++;
            } else {
                failCount++;
                console.error(`Upload gagal untuk ${files[i].name}: ${result.pesan}`);
            }
        } catch (e) {
            failCount++;
            console.error(`Upload error untuk ${files[i].name}:`, e);
        }
    }

    // Reset form
    fileInput.value = '';
    tipeInput.value = 'Surat Tugas';
    btn.disabled = false;
    btn.innerHTML = '<i class="fas fa-cloud-upload-alt mr-1"></i> Upload';

    // Hide progress
    if (progressContainer) {
        progressContainer.style.display = 'none';
        if (progressBar) progressBar.style.width = '0%';
    }

    // Update file list display in modal
    updateSelectedFilesDisplay();

    if (failCount > 0 && successCount > 0) {
        alert(`${successCount} file berhasil diupload, ${failCount} file gagal.`);
    } else if (failCount > 0 && successCount === 0) {
        alert('Semua file gagal diupload.');
    }

    loadDokumen(); // Reload list
}

// Tampilkan daftar file yang dipilih di modal
function updateSelectedFilesDisplay() {
    const fileInput = document.getElementById('dm-file');
    const display = document.getElementById('dm-selected-files');
    if (!display) return;

    if (!fileInput || !fileInput.files.length) {
        display.innerHTML = `
            <div style="text-align: center; padding: 30px 20px; color: #9ca3af;">
                <i class="fas fa-cloud-upload-alt" style="font-size: 2.5rem; margin-bottom: 10px; display: block; color: #d1d5db;"></i>
                <span style="font-size: 0.9rem;">Belum ada file yang dipilih</span>
                <br><small style="color: #b0b8c4;">Klik "Pilih File" untuk memilih dokumen</small>
            </div>
        `;
        return;
    }

    const files = Array.from(fileInput.files);
    let html = `<div style="font-size: 0.8rem; color: #6b7280; margin-bottom: 8px; font-weight: 600;">${files.length} file dipilih:</div>`;
    html += '<div style="display: flex; flex-direction: column; gap: 6px;">';
    files.forEach((file, i) => {
        const sizeKB = (file.size / 1024).toFixed(1);
        const ext = file.name.split('.').pop().toLowerCase();
        const icon = getFileIcon(file.name);
        html += `
            <div style="display: flex; align-items: center; gap: 10px; padding: 8px 12px; background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 6px; font-size: 0.85rem;">
                <span style="font-size: 1.1rem; flex-shrink: 0;">${icon}</span>
                <div style="flex: 1; min-width: 0;">
                    <div style="font-weight: 500; color: #374151; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="${file.name}">${file.name}</div>
                    <div style="font-size: 0.75rem; color: #9ca3af;">${sizeKB} KB • ${ext.toUpperCase()}</div>
                </div>
            </div>
        `;
    });
    html += '</div>';
    display.innerHTML = html;
}

async function hapusDokumen(id) {
    if(!confirm('Hapus dokumen ini?')) return;

    try {
         // Using DELETE method if supported, otherwise POST
        const response = await fetch(`<?= base_url('program-kerja/hapus-dokumen/') ?>${id}`, {
            method: 'DELETE',
             headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        const result = await response.json();
        
        if (result.sukses) {
            loadDokumen();
        } else {
            alert(result.pesan);
        }
    } catch (e) {
        alert('Gagal menghapus dokumen');
    }
}

// Load preview on start
document.addEventListener('DOMContentLoaded', () => {
    loadDokumen();
    window.startUploadDokumen = uploadDokumen;

    // Update file list display when files are selected
    const fileInput = document.getElementById('dm-file');
    if (fileInput) {
        fileInput.addEventListener('change', updateSelectedFilesDisplay);
    }

    // AJAX Approval Handler for Detail Page
    const formApproval = document.getElementById('form-approval-auditor');
    if (formApproval) {
        formApproval.addEventListener('submit', async (e) => {
            e.preventDefault();
            if (!confirm('Pastikan data sudah valid. Setujui program kerja ini?')) return;

            const btn = formApproval.querySelector('button[type="submit"]');
            const originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Menyimpan...';

            try {
                const formData = new FormData(formApproval);
                const response = await fetch(formApproval.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const result = await response.json();

                if (result.success) {
                    // Update the entire view dynamically if needed, or just status area
                    location.reload(); // Still reloading for detail because it's safer for multiple state updates
                    // However, we can try to do it without reload if UX is better:
                    /*
                    document.getElementById('approval-container').innerHTML = `
                        <div class="p-3 bg-blue-50 border-l-4 border-blue-500 rounded text-sm text-blue-800">
                            <strong>Catatan Review:</strong><br>
                            ${formData.get('catatan_auditor') || 'Tidak ada catatan.'}
                        </div>
                    `;
                    // Need to update the status badge as well
                    */
                } else {
                    alert(result.message);
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                }
            } catch (err) {
                console.error(err);
                alert('Terjadi kesalahan saat memproses persetujuan');
                btn.disabled = false;
                btn.innerHTML = originalText;
            }
        });
    }
});
</script>
<?= $this->endSection() ?>
