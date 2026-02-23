<?= $this->extend('layouts/utama') ?>

<?= $this->section('content') ?>

<!-- Header Halaman -->
<div class="page-header">
    <div class="page-header-content">
        <h2 class="page-title">Program Kerja Pengawasan Tahunan (PKPT)</h2>
        <p class="page-subtitle">Kelola dan pantau program kerja pengawasan tahunan</p>
    </div>
</div>


<!-- Search & Actions Toolbar -->
<div class="toolbar-container">
    <form method="get" action="<?= base_url('program-kerja') ?>" class="search-form-flex">
        <div class="search-input-group">
            <span class="search-icon-inside"><i class="fas fa-search" style="color: #6b7280;"></i></span>
            <input 
                type="text" 
                name="cari" 
                class="search-input-full" 
                placeholder="Cari nama kegiatan..."
                value="<?= esc($keyword ?? '') ?>"
            >
        </div>

        <div class="filter-group">
            <select name="tahun" class="filter-select" onchange="this.form.submit()">
                <option value="">Semua Tahun</option>
                <?php if (!empty($available_years)): ?>
                    <?php foreach ($available_years as $y): ?>
                        <option value="<?= $y ?>" <?= (isset($tahun_pilih) && $tahun_pilih == $y) ? 'selected' : '' ?>>
                            Tahun <?= $y ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>

        <button type="submit" class="btn btn-secondary">Cari</button>
    </form>
    
    <div class="toolbar-actions">
        <?php if (in_array(session()->get('role'), ['admin', 'user'])): ?>
        <a href="<?= base_url('program-kerja/tambah') ?>" class="btn btn-primary">
            <span class="btn-icon">
                <i class="fas fa-plus"></i>
            </span>
            Tambah Program
        </a>
        <?php endif; ?>
        <a href="<?= base_url('program-kerja/export-excel') . '?' . http_build_query(request()->getGet()) ?>" class="btn btn-success">
            <span class="btn-icon">
                <i class="fas fa-file-excel"></i>
            </span>
            Ekspor Excel
        </a>
    </div>
</div>

<!-- Tabel Program Kerja -->
<div class="table-container">
    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th class="th-no">No</th>
                    <th class="th-nama">Nama Kegiatan</th>
                    <th class="th-rencana">Rencana Kegiatan</th>
                    <th class="th-anggaran">Anggaran</th>
                    <th class="th-realisasi-kegiatan">Realisasi Kegiatan</th>
                    <th class="th-pelaksana">Pelaksana</th>
                    <th class="th-dokumen">Dokumen Output</th>
                    <th class="th-realisasi-anggaran">Realisasi Anggaran</th>
                    <th class="th-sasaran">Sasaran Strategis</th>
                    <th class="th-status">Status</th>
                    <th class="th-approval">Approval</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($program_kerja)): ?>
                    <?php 
                    // Reverse Numbering Logic
                    // Start number = Total Items - Offset
                    // Note: If getTotal() is not supported by custom pager, we might need a fallback, 
                    // but standard CI4 pager supports it.
                    $currentPage = $pager->getCurrentPage() ?: 1;
                    $perPage = $pager->getPerPage() ?: 10;
                    $total = $pager->getTotal() ?: count($program_kerja);
                    
                    $no = $total - (($currentPage - 1) * $perPage);
                    
                    foreach ($program_kerja as $pk): 
                    ?>
                        <tr class="table-row-clickable" onclick="window.location='<?= base_url('program-kerja/lihat/' . $pk['id']) ?>'">
                            <td class="td-no"><?= $no-- ?></td>
                            <td class="td-nama">
                                <div class="cell-content"><?= esc($pk['nama_kegiatan']) ?></div>
                            </td>
                            <td class="td-rencana">
                                <div class="cell-content">
                                    <?php if (!empty($pk['tanggal_mulai']) && !empty($pk['tanggal_selesai'])): ?>
                                        <?= date('d/m/Y', strtotime($pk['tanggal_mulai'])) ?> s/d 
                                        <?= date('d/m/Y', strtotime($pk['tanggal_selesai'])) ?>
                                    <?php else: ?>
                                        <?= esc($pk['rencana_kegiatan']) ?: '-' ?>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="td-anggaran">Rp <?= number_format($pk['anggaran'], 0, ',', '.') ?></td>
                            <td class="td-realisasi-kegiatan">
                                <div class="cell-content"><?= esc($pk['realisasi_kegiatan']) ?: '-' ?></div>
                            </td>
                            <td class="td-pelaksana">
                                <div class="cell-content" style="font-size: 0.85rem; line-height: 1.4;">
                                    <?php if (!empty($pk['tim_pelaksana'])): ?>
                                        <?php 
                                        $teamList = explode('|', $pk['tim_pelaksana']);
                                        foreach ($teamList as $tStr):
                                            $tParts = explode(':', $tStr);
                                            if (count($tParts) < 2) continue;
                                            $role = $tParts[0];
                                            $name = $tParts[1];
                                            
                                            // Mapping short roles for UI
                                            $shortRole = 'AGT';
                                            switch($role) {
                                                case 'Pengendali Teknis': $shortRole = 'PT'; break;
                                                case 'Ketua Tim': $shortRole = 'KT'; break;
                                                case 'Auditor Madya': $shortRole = 'MADYA'; break;
                                                case 'Auditor Muda': $shortRole = 'MUDA'; break;
                                            }
                                        ?>
                                            <div title="<?= esc($role) ?>"><span class="text-muted" style="font-size: 0.7rem; font-weight: bold;"><?= $shortRole ?>:</span> <?= esc($name) ?></div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="td-dokumen">
                                <?php if (!empty($pk['dokumen_output'])): ?>
                                    <div class="doc-list-stack">
                                        <?php 
                                        /**
                                         * LOGIKA TAMPILAN DOKUMEN:
                                         * 1. Data 'dokumen_output' berbentuk string gabungan: "id1:nama1:tipe1|id2:nama2:tipe2"
                                         * 2. Kita pecah (explode) berdasarkan '|' menjadi array dokumen.
                                         * 3. Loop setiap dokumen, pecah lagi berdasarkan ':' untuk dapat detailnya.
                                         * 4. Batasi tampilan hanya 3 dokumen agar tabel tidak terlalu panjang.
                                         */
                                        
                                        // 1. Pecah string menjadi array dokumen individu
                                        $docs = explode('|', $pk['dokumen_output']);
                                        $limit = 3; // Batas maksimal yang ditampilkan
                                        $count = 0;
                                        
                                        foreach ($docs as $docStr): 
                                            // 2. Pecah detail dokumen (ID : Nama File : Tipe Dokumen)
                                            $parts = explode(':', $docStr);
                                            
                                            // Validasi: pastikan minimal ada ID dan Nama File
                                            if (count($parts) < 2) continue;
                                            
                                            $docId   = $parts[0];
                                            $docName = $parts[1];
                                            // Jika tipe kosong, pakai default 'Dokumen'
                                            $docType = $parts[2] ?? 'Dokumen'; 
                                            
                                            $count++;
                                            
                                            // Hanya render jika belum mencapai limit
                                            if ($count <= $limit):
                                                // Logika pemilihan ikon berdasarkan ekstensi file (Gunakan FontAwesome)
                                                $iconClass = 'fas fa-file'; // Default
                                                $colorClass = 'text-secondary';
                                                
                                                if (str_ends_with($docName, '.pdf')) {
                                                    $iconClass = 'fas fa-file-pdf';
                                                    $colorClass = 'text-danger';
                                                }
                                                elseif (preg_match('/\.(doc|docx)$/', $docName)) {
                                                    $iconClass = 'fas fa-file-word';
                                                    $colorClass = 'text-primary';
                                                }
                                                elseif (preg_match('/\.(xls|xlsx)$/', $docName)) {
                                                    $iconClass = 'fas fa-file-excel';
                                                    $colorClass = 'text-success';
                                                }
                                                elseif (preg_match('/\.(jpg|jpeg|png)$/', $docName)) {
                                                    $iconClass = 'fas fa-image';
                                                    $colorClass = 'text-info';
                                                }
                                        ?>
                                            <div class="btn-group-dokumen">
                                                <button type="button" 
                                                   onclick="event.stopPropagation(); bukaPreview(<?= $docId ?>, '<?= esc($docName) ?>')"
                                                   class="btn-dokumen-main" 
                                                   title="Preview & Download: <?= esc($docName) ?>">
                                                    <i class="<?= $iconClass ?> <?= $colorClass ?> doc-icon"></i>
                                                    <span class="doc-name-truncate"><?= esc($docType) ?></span>
                                                    <!-- Optional: Chevron or eye icon to indicate action, but user asked for download style button triggering preview -->
                                                    <!-- We keep it looking like a file button -->
                                                </button>
                                            </div>
                                        <?php endif; endforeach; ?>
                                        
                                        <?php if ($count > $limit): ?>
                                            <span class="more-badge">+<?= $count - $limit ?> lainnya</span>
                                        <?php endif; ?>
                                    </div>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <style>
                            .doc-list-stack {
                                display: flex;
                                flex-direction: column;
                                gap: 4px;
                                align-items: flex-start;
                            }
                            .btn-group-dokumen {
                                display: flex;
                                align-items: center;
                                gap: 4px;
                            }
                            .btn-dokumen-action {
                                display: inline-flex;
                                align-items: center;
                                justify-content: center;
                                width: 24px;
                                height: 24px;
                                border: 1px solid #d1d5db;
                                border-radius: 4px;
                                background: #fff;
                                color: #374151;
                                transition: all 0.2s;
                            }
                            .btn-dokumen-action:hover {
                                background: #eff6ff;
                                border-color: #3b82f6;
                            }
                            .btn-dokumen-main {
                                display: inline-flex;
                                align-items: center;
                                gap: 8px;
                                padding: 4px 8px;
                                background: transparent;
                                border: 1px solid #d1d5db; /* Simple gray border */
                                border-radius: 4px;
                                color: #374151;
                                font-size: 0.8rem;
                                text-decoration: none;
                                max-width: 150px;
                                transition: background-color 0.2s;
                                flex-grow: 1;
                            }
                            .btn-dokumen-main:hover {
                                background: #f9fafb;
                                color: #111827;
                                border-color: #6b7280;
                            }
                            .doc-name-truncate {
                                max-width: 90px;
                                white-space: nowrap;
                                overflow: hidden;
                                text-overflow: ellipsis;
                                font-weight: normal;
                            }
                            .doc-icon {
                                font-size: 0.9em;
                            }
                            .icon-download {
                                font-size: 0.8em;
                                color: #9ca3af;
                            }
                            .more-badge {
                                font-size: 0.75rem;
                                color: #6b7280;
                                background: #f3f4f6;
                                padding: 2px 8px;
                                border-radius: 12px;
                            }
                            </style>
                            <td class="td-realisasi-anggaran">Rp <?= number_format($pk['realisasi_anggaran'], 0, ',', '.') ?></td>
                            <td class="td-sasaran">
                                <div class="cell-content"><?= esc($pk['sasaran_strategis']) ?: '-' ?></div>
                            </td>
                            <td class="td-status">
                                <?php if (!empty($pk['status'])): ?>
                                    <span class="badge badge-<?= strtolower(str_replace(' ', '-', $pk['status'])) ?>">
                                        <?= esc($pk['status']) ?>
                                    </span>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="td-approval">
                                <div class="cell-content text-center">
                                    <?php if ($pk['is_approved']): ?>
                                        <span class="badge badge-terlaksana" title="Disetujui oleh <?= esc($pk['approved_by']) ?> pada <?= date('d/m/Y', strtotime($pk['approved_at'])) ?><?= !empty($pk['catatan_auditor']) ? "\nCatatan: " . esc($pk['catatan_auditor']) : '' ?>">
                                            <i class="fas fa-check-circle"></i> Disetujui
                                            <?php if (!empty($pk['catatan_auditor'])): ?>
                                                <i class="fas fa-comment-dots ml-1" style="font-size: 0.85em;"></i>
                                            <?php endif; ?>
                                        </span>
                                    <?php else: ?>
                                        <?php if (session()->get('role') === 'auditor' && $pk['status'] === 'Terlaksana'): ?>
                                            <button type="button"
                                               class="btn btn-primary btn-approve-inline" 
                                               style="padding: 2px 8px; font-size: 0.75rem; position: relative;"
                                               onclick="event.stopPropagation(); setujuiProgram(<?= $pk['id'] ?>, this)">
                                                <i class="fas fa-check mr-1"></i> Approve
                                                <?php if (!empty($pk['catatan_auditor'])): ?>
                                                    <span style="position: absolute; top: -4px; right: -4px; width: 8px; height: 8px; background: #ef4444; border: 1px solid white; border-radius: 50%;" title="Memiliki catatan/review"></span>
                                                <?php endif; ?>
                                            </button>
                                        <?php else: ?>
                                            <span class="badge badge-dibatalkan" style="background: #f3f4f6; color: #6b7280; border: 1px solid #e5e7eb;">
                                                <i class="fas fa-clock mr-1"></i> Pending
                                            </span>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="11" class="text-center">
                            <div class="empty-state">
                                <p class="empty-icon">📋</p>
                                <p class="empty-text">Belum ada data program kerja</p>
                                <?php if (session()->get('role') === 'admin'): ?>
                                <a href="<?= base_url('program-kerja/tambah') ?>" class="btn btn-primary">
                                    <span class="btn-icon">+</span>
                                    Tambah Program Kerja
                                </a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
<?php if (!empty($program_kerja)): ?>
    <div class="pagination-wrapper">
        <div class="pagination-info">
            Menampilkan <?= count($program_kerja) ?> dari <?= $pager->getTotal() ?>
        </div>
        <div class="pagination-container">
            <?= $pager->links('default', 'bootstrap_full') ?>
        </div>
    </div>
<?php endif; ?>

<?= $this->include('program_kerja/partials/modal_preview') ?>

<script>
async function setujuiProgram(id, btn) {
    if (!confirm('Setujui program kerja ini?')) return;
    
    const originalContent = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Processing...';
    
    try {
        const response = await fetch('<?= base_url('program-kerja/setujui') ?>/' + id, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        const result = await response.json();
        
        if (result.success) {
            // Update the cell content to "Disetujui" badge
            const container = btn.closest('.cell-content');
            const catatan = result.catatan || '';
            const catatanText = catatan ? '\nCatatan: ' + catatan : '';
            
            container.innerHTML = `
                <span class="badge badge-terlaksana" title="Disetujui oleh ${result.approved_by} pada ${result.approved_at}${catatanText}">
                    <i class="fas fa-check-circle"></i> Disetujui
                    ${catatan ? '<i class="fas fa-comment-dots ml-1" style="font-size: 0.85em;"></i>' : ''}
                </span>
            `;
            
            // Optional: Show a small toast notification instead of popup
            // console.log(result.message);
        } else {
            alert(result.message || 'Gagal menyetujui program kerja');
            btn.disabled = false;
            btn.innerHTML = originalContent;
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan koneksi');
        btn.disabled = false;
        btn.innerHTML = originalContent;
    }
}
</script>

<?= $this->endSection() ?>

