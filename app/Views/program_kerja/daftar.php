<?= $this->extend('layouts/utama') ?>

<?= $this->section('content') ?>

<!-- Header Halaman -->
<div class="page-header">
    <div class="page-header-content">
        <h2 class="page-title">Program Kerja Pengawasan Tahunan (PKPT)</h2>
        <p class="page-subtitle">Kelola dan pantau program kerja pengawasan tahunan</p>
    </div>
</div>

<!-- Alert Messages -->
<?php if (session()->getFlashdata('sukses')): ?>
    <div class="alert alert-success">
        <span class="alert-icon">✓</span>
        <span class="alert-text"><?= session()->getFlashdata('sukses') ?></span>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('gagal')): ?>
    <div class="alert alert-error">
        <span class="alert-icon">✕</span>
        <span class="alert-text"><?= session()->getFlashdata('gagal') ?></span>
    </div>
<?php endif; ?>

<!-- Search & Actions Toolbar -->
<div class="toolbar-container">
    <form method="get" action="<?= base_url('program-kerja') ?>" class="search-form-flex">
        <div class="search-input-group">
            <span class="search-icon-inside">🔍</span>
            <input 
                type="text" 
                name="cari" 
                class="search-input-full" 
                placeholder="Cari nama kegiatan, pelaksana, atau keterangan..."
                value="<?= esc($keyword ?? '') ?>"
            >
        </div>
        <button type="submit" class="btn btn-secondary">Cari</button>
    </form>
    
    <div class="toolbar-actions">
        <a href="<?= base_url('program-kerja/tambah') ?>" class="btn btn-primary">
            <span class="btn-icon">+</span>
            Tambah Program
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
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($program_kerja)): ?>
                    <?php 
                    $no = 1 + (($pager->getCurrentPage() - 1) * $pager->getPerPage());
                    foreach ($program_kerja as $pk): 
                    ?>
                        <tr class="table-row-clickable" onclick="window.location='<?= base_url('program-kerja/lihat/' . $pk['id']) ?>'">
                            <td class="td-no"><?= $no++ ?></td>
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
                            <td class="td-pelaksana"><?= esc($pk['pelaksana']) ?: '-' ?></td>
                            <td class="td-dokumen">
                                <?php if (!empty($pk['dokumen_output'])): ?>
                                    <a href="<?= base_url('program-kerja/unduh-dokumen/' . $pk['id']) ?>" 
                                       class="btn-dokumen" 
                                       onclick="event.stopPropagation()"
                                       title="Unduh Dokumen">
                                        📄 Unduh
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
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
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="10" class="text-center">
                            <div class="empty-state">
                                <p class="empty-icon">📋</p>
                                <p class="empty-text">Belum ada data program kerja</p>
                                <a href="<?= base_url('program-kerja/tambah') ?>" class="btn btn-primary">
                                    <span class="btn-icon">+</span>
                                    Tambah Program Kerja
                                </a>
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
    <div class="pagination-container">
        <?= $pager->links() ?>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>

