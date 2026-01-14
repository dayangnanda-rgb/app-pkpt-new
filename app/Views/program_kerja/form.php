<?= $this->extend('layouts/utama') ?>

<?= $this->section('content') ?>

<!-- Header Halaman -->
<div class="page-header">
    <div class="page-header-content">
        <h2 class="page-title"><?= $judul ?></h2>
        <p class="page-subtitle">
            <?= $aksi === 'tambah' ? 'Tambahkan program kerja baru' : 'Perbarui informasi program kerja' ?>
        </p>
    </div>
    <div class="page-header-actions">
        <a href="<?= base_url('program-kerja') ?>" class="btn btn-secondary">
            <span class="btn-icon">←</span>
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
                    <small class="form-help">Tahun pelaksanaan program</small>
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
                <small class="form-help">Maksimal 500 karakter</small>
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
                    <small class="form-help">Masukkan anggaran dalam Rupiah</small>
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
                        class="form-input"
                        value="<?= old('pelaksana', $program_kerja['pelaksana'] ?? '') ?>"
                        maxlength="255"
                        placeholder="Nama pelaksana atau PIC"
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
                <small class="form-help">Total realisasi anggaran yang digunakan</small>
            </div>


        </div>

        <!-- Section: Dokumen & Sasaran -->
        <div class="form-section">
            <h3 class="form-section-title">📁 Dokumen & Sasaran</h3>
            
            <!-- Dokumen Output (Multi-upload) -->
            <div class="form-group" style="grid-column: 1 / -1;">
                <label for="dokumen" class="form-label">
                    Dokumen Output
                </label>
                <div class="form-help-box mb-2">
                    <p>💡 Bisa pilih banyak file sekaligus. Tipe dokumen otomatis diset sebagai <strong>"Lampiran"</strong> (bisa diubah nanti di Detail).</p>
                </div>
                
                <div class="input-group">
                    <div class="input-prefix">📎</div>
                    <input 
                        type="file" 
                        id="dokumen" 
                        name="dokumen[]" 
                        class="form-file"
                        accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png"
                        multiple
                    >
                </div>
                <small class="form-help">Format: PDF, Word, Excel, Gambar. Maks: 5MB per file.</small>
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
                    <small class="form-help">Status pelaksanaan kegiatan</small>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="form-actions">
            <button type="submit" class="btn btn-primary btn-lg">
                <span class="btn-icon">✓</span>
                <?= $aksi === 'tambah' ? 'Simpan Program Kerja' : 'Perbarui Program Kerja' ?>
            </button>
            <a href="<?= base_url('program-kerja') ?>" class="btn btn-secondary btn-lg">
                <span class="btn-icon">✕</span>
                Batal
            </a>
        </div>
    </form>
</div>

<?= $this->endSection() ?>
