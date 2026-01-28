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
            Edit
        </a>
        <button onclick="konfirmasiHapus(<?= $program_kerja['id'] ?>, '<?= esc($program_kerja['nama_kegiatan']) ?>')" 
                class="btn btn-danger">
            Hapus
        </button>
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
                    
                    <button type="button" class="btn btn-primary mt-3" onclick="bukaModalDokumen()">
                        Kelola Dokumen
                    </button>
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
<?= $this->include('program_kerja/partials/modal_dokumen') ?>
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
            <div class="flex flex-col items-center justify-center py-6 text-gray-400 border-2 border-dashed rounded-lg">
                <span class="text-4xl mb-2">📂</span>
                <span class="text-sm">Belum ada dokumen yang diunggah</span>
            </div>
        `;
        return;
    }

    let html = '<div class="space-y-2">';
    docs.forEach(doc => {
        const sizeKB = doc.size ? (doc.size / 1024).toFixed(1) + ' KB' : '';
        const fileName = doc.display_name || doc.nama_file;

        html += `
            <div style="display: flex; align-items: center; justify-content: space-between; padding: 12px; background: white; border: 1px solid #e2e8f0; border-radius: 8px; margin-bottom: 8px;">
                <div style="display: flex; align-items: center; gap: 12px; overflow: hidden; flex: 1;">
                    <span style="font-size: 1.5rem; flex-shrink: 0; color: #374151;">${getFileIcon(fileName)}</span>
                    <div style="min-width: 0; display: flex; flex-direction: column; gap: 2px;">
                        <div style="font-size: 0.9rem; font-weight: 600; color: #1f2937; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="${fileName}">${fileName}</div>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <span style="background: #e0f2fe; padding: 2px 8px; border-radius: 4px; color: #0284c7; font-size: 0.75rem; font-weight: 500;">${doc.tipe_dokumen || 'Dokumen'}</span>
                            <span style="font-size: 0.75rem; color: #9ca3af;">${sizeKB}</span>
                        </div>
                    </div>
                </div>
                <!-- Only Trash Icon -->
                <button onclick="hapusDokumen(${doc.id})" type="button" style="color: #ef4444; padding: 8px; border-radius: 4px; border: none; background: transparent; cursor: pointer; transition: color 0.2s;" title="Hapus">
                    <i class="fas fa-trash-alt"></i>
                </button>
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
        const fileName = doc.display_name || doc.nama_file;
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
                   onclick="bukaPreview(${doc.id}, '${fileName}')"
                   class="btn btn-sm btn-outline-secondary" 
                   style="margin-left: 12px; white-space: nowrap; display: inline-flex; align-items: center; gap: 6px; padding: 4px 12px; border-radius: 4px; font-size: 0.85rem; border: 1px solid #d1d5db;">
                    <span>Unduh</span>
                    <i class="fas fa-download" style="font-size: 0.8em;"></i>
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
    window.startUploadDokumen = uploadDokumen;
});
</script>
<?= $this->endSection() ?>
