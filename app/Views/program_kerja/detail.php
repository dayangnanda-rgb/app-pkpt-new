<?= $this->extend('layouts/utama') ?>

<?= $this->section('content') ?>

<!-- Header Halaman -->
<div class="page-header">
    <div class="page-header-content">
        <h2 class="page-title">Detail Program Kerja</h2>
        <p class="page-subtitle">Informasi lengkap program kerja pengawasan</p>
    </div>
    <div class="page-header-actions">
        <a href="<?= base_url('program-kerja/edit/' . $program_kerja['id']) ?>" class="btn btn-primary">
            <span class="btn-icon">✎</span>
            Edit
        </a>
        <button onclick="konfirmasiHapus(<?= $program_kerja['id'] ?>, '<?= esc($program_kerja['nama_kegiatan']) ?>')" 
                class="btn btn-danger">
            <span class="btn-icon">🗑</span>
            Hapus
        </button>
        <a href="<?= base_url('program-kerja') ?>" class="btn btn-secondary">
            <span class="btn-icon">←</span>
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
                <h3 class="detail-section-title">📋 Informasi Dasar</h3>
                
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

                    <div class="detail-item">
                        <label class="detail-label">Pelaksana/PIC</label>
                        <div class="detail-value">
                            <?= !empty($program_kerja['pelaksana']) ? esc($program_kerja['pelaksana']) : '<span class="text-muted">-</span>' ?>
                        </div>
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
                        <label class="detail-label">Status</label>
                        <div class="detail-value">
                            <?php if (!empty($program_kerja['status'])): ?>
                                <span class="badge badge-<?= strtolower(str_replace(' ', '-', $program_kerja['status'])) ?>">
                                    <?= esc($program_kerja['status']) ?>
                                </span>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section: Rencana & Realisasi -->
            <div class="detail-section">
                <h3 class="detail-section-title">📝 Rencana & Realisasi</h3>
                
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
                <h3 class="detail-section-title">🎯 Sasaran Strategis</h3>
                
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
                <h3 class="detail-section-title">📁 Dokumen Output</h3>
                
                <div class="detail-item detail-item-full">
                    <div id="dokumen-list-preview">
                        <!-- Preview list will be loaded here -->
                        <div class="text-muted text-sm mb-3">Memuat dokumen...</div>
                    </div>
                    
                    <button type="button" class="btn btn-outline-primary w-full" onclick="bukaModalDokumen()">
                        <span class="btn-icon">📂</span>
                        Kelola Dokumen
                    </button>
                    
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
                <h3 class="detail-section-title">ℹ️ Informasi Sistem</h3>
                
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
<div id="modal-dokumen" class="modal-overlay">
    <div class="modal-container">
        <div class="modal-header">
            <h3 class="modal-title">Kelola Dokumen Output</h3>
            <button class="modal-close" onclick="tutupModalDokumen()">×</button>
        </div>
        
        <div class="modal-body">
            <!-- List Section -->
            <div id="modal-doc-list" class="doc-list-container">
                <!-- Content loaded via AJAX -->
            </div>

            <!-- Upload Section -->
            <div class="doc-upload-section">
                <div class="form-group mb-2">
                    <label class="text-sm font-medium mb-1 block">Jenis Dokumen</label>
                    <select id="upload-tipe" class="form-select text-sm h-9">
                        <option value="Surat Tugas">Surat Tugas</option>
                        <option value="Laporan">Laporan</option>
                        <option value="Dokumen Komunikasi">Dokumen Komunikasi</option>
                        <option value="Bukti Dukung">Bukti Dukung</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>
                <div class="form-group mb-0">
                    <label class="text-sm font-medium mb-1 block">File</label>
                    <div class="flex gap-2">
                        <input type="file" id="upload-file" class="form-file text-sm flex-1">
                        <button id="btn-upload" class="btn btn-primary" onclick="uploadDokumen()">Upload</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (csrfToken) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'csrf_token';
            input.value = csrfToken.content;
            form.appendChild(input);
        }
        
        document.body.appendChild(form);
        form.submit();
    }
}

// --- DOCUMENT MANAGEMENT JS ---
const PROGRAM_ID = <?= $program_kerja['id'] ?>;

function bukaModalDokumen() {
    document.getElementById('modal-dokumen').classList.add('show');
    loadDokumen();
}

function tutupModalDokumen() {
    document.getElementById('modal-dokumen').classList.remove('show');
}

async function loadDokumen() {
    const container = document.getElementById('modal-doc-list');
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
    const container = document.getElementById('modal-doc-list');
    if (docs.length === 0) {
        container.innerHTML = `
            <div class="empty-state-small py-5">
                <span class="empty-icon text-3xl">📂</span>
                <p class="text-muted mt-2">Belum ada dokumen yang diunggah</p>
            </div>
        `;
        return;
    }

    let html = '';
    docs.forEach(doc => {
        let icon = '📄';
        if(doc.nama_file.endsWith('.pdf')) icon = '📕';
        else if(doc.nama_file.endsWith('.doc') || doc.nama_file.endsWith('.docx')) icon = '📘';
        else if(doc.nama_file.endsWith('.xls') || doc.nama_file.endsWith('.xlsx')) icon = '📗';

        html += `
            <div class="doc-list-item">
                <div class="doc-icon">${icon}</div>
                <div class="doc-info">
                    <div class="doc-name">${doc.nama_file}</div>
                    <div class="doc-meta">
                        <span class="doc-badge">${doc.tipe_dokumen || 'Dokumen'}</span>
                        <span class="doc-date">• ${new Date(doc.created_at).toLocaleDateString('id-ID')}</span>
                    </div>
                </div>
                <div class="doc-actions">
                    <a href="<?= base_url('program-kerja/download-dokumen/') ?>${doc.id}" class="btn-icon-sm" title="Download">⬇</a>
                    <button onclick="hapusDokumen(${doc.id})" class="btn-icon-sm text-danger" title="Hapus">🗑</button>
                </div>
            </div>
        `;
    });
    container.innerHTML = html;
}

function updatePreview(docs) {
    const preview = document.getElementById('dokumen-list-preview');
    if (docs.length === 0) {
        preview.innerHTML = '<div class="text-muted text-sm mb-3">Belum ada dokumen</div>';
        return;
    }
    // Show only first 3 docs in preview
    let html = '<div class="preview-docs-list mb-3">';
    docs.slice(0, 3).forEach(doc => {
        html += `<div class="text-sm border-b py-1 truncate">📄 ${doc.nama_file}</div>`;
    });
    if(docs.length > 3) {
        html += `<div class="text-xs text-muted pt-1">+ ${docs.length - 3} dokumen lainnya</div>`;
    }
    html += '</div>';
    preview.innerHTML = html;
}

async function uploadDokumen() {
    const fileInput = document.getElementById('upload-file');
    const tipeInput = document.getElementById('upload-tipe');
    const btn = document.getElementById('btn-upload');

    if (!fileInput.files[0]) {
        alert('Pilih file terlebih dahulu');
        return;
    }

    const formData = new FormData();
    formData.append('file', fileInput.files[0]);
    formData.append('tipe_dokumen', tipeInput.value);
    
    // Add CSRF
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (csrfToken) formData.append('csrf_token', csrfToken.content);

    btn.disabled = true;
    btn.innerHTML = 'Mengupload...';

    try {
        const response = await fetch(`<?= base_url('program-kerja/upload-dokumen/') ?>${PROGRAM_ID}`, {
            method: 'POST',
            body: formData
        });
        const result = await response.json();

        if (result.sukses) {
            // Reset form
            fileInput.value = '';
            tipeInput.value = 'Surat Tugas';
            loadDokumen(); // Reload list
        } else {
            alert(result.pesan);
        }
    } catch (e) {
        alert('Gagal mengupload dokumen');
    } finally {
        btn.disabled = false;
        btn.innerHTML = 'Upload';
    }
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
});
</script>
<?= $this->endSection() ?>
