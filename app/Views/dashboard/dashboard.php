<?= $this->extend('layouts/utama') ?>

<?= $this->section('content') ?>

<div class="dashboard-wrapper">

    <!-- 4 Statistics Cards -->
    <div class="stats-container-modern">
        <div class="stat-card-modern card-total-modern">
            <div class="stat-icon-modern">
                <i class="fas fa-list-ul"></i>
            </div>
            <div class="stat-info-modern">
                <h3 id="statTotalProgram"><?= number_format($statistik['total_program']) ?></h3>
                <p style="margin: 0;">TOTAL PROGRAM</p>
                <div style="font-size: 0.65rem; color: #94a3b8; font-style: italic; margin-bottom: 5px;">Berdasarkan kegiatan PKPT</div>
                <div class="stat-period-label period-label">Tahunan <?= $tahun_aktif ?></div>
            </div>
            <div class="card-accent-line"></div>
        </div>

        <div class="stat-card-modern card-budget-modern">
            <div class="stat-icon-modern">
                <i class="fas fa-layer-group"></i>
            </div>
            <div class="stat-info-modern">
                <h3><span class="currency-prefix">Rp</span> <span id="statTotalAnggaran"><?= number_format($statistik['total_anggaran'], 0, ',', '.') ?></span></h3>
                <p>TOTAL ANGGARAN</p>
                <div class="stat-period-label period-label">Tahunan <?= $tahun_aktif ?></div>
            </div>
            <div class="card-accent-line" style="background: #FAC70B;"></div>
        </div>

        <div class="stat-card-modern card-realization-modern">
            <div class="stat-icon-modern">
                <i class="fas fa-hand-holding-usd"></i>
            </div>
            <div class="stat-info-modern">
                <h3><span class="currency-prefix">Rp</span> <span id="statTotalRealisasi"><?= number_format($statistik['total_realisasi'], 0, ',', '.') ?></span></h3>
                <p>TOTAL REALISASI</p>
                <div class="stat-period-label period-label">Tahunan <?= $tahun_aktif ?></div>
            </div>
            <div class="card-accent-line" style="background: #10b981;"></div>
        </div>

        <div class="stat-card-modern card-percentage-modern">
            <div class="stat-icon-modern">
                <i class="fas fa-chart-pie"></i>
            </div>
            <div class="stat-info-modern">
                <div class="percentage-header">
                    <h3 id="statPersentaseRealisasi"><?= number_format($statistik['persentase_realisasi'], 0) ?><span class="unit-percent">%</span></h3>
                    <p>REALISASI ANGGARAN</p>
                </div>
                <div class="stat-period-label period-label">Tahunan <?= $tahun_aktif ?></div>
                <div class="badge-pkpt-main mt-2">
                    <i class="fas fa-tag"></i> PKPT UTAMA
                </div>
            </div>
            <div class="card-accent-line" style="background: #10b981;"></div>
        </div>
    </div>

    <!-- Section 1: Kinerja & Anggaran -->
    <div class="modern-card-group">
        <div class="card-group-header">
            <i class="fas fa-tasks"></i> Kinerja & Anggaran
        </div>
        <div class="card-group-body grid-2">
            <!-- Left: Anggaran vs Realisasi -->
            <div class="inner-card">
                <div class="inner-header">Anggaran vs Realisasi</div>
                <div class="inner-body">
                    <div class="budget-summary-visual">
                        <div class="budget-top-labels">
                            <span class="budget-curr">Rp</span>
                            <span class="budget-val" id="budgetValRealisasi"><?= number_format($statistik['total_realisasi'], 0, ',', '.') ?></span>
                            <span class="budget-badge-percent" id="budgetPercentRealisasi"><?= number_format($statistik['persentase_realisasi'], 0) ?>% REALISASI</span>
                        </div>
                        <div class="modern-progress-large" title="Realisasi mencakup kontrak berjalan">
                            <div class="progress-fill-main" id="budgetProgressBar" style="width: <?= $statistik['persentase_realisasi'] ?>%"></div>
                        </div>
                        <div class="budget-scale-labels">
                            <span id="budgetScaleTotal" style="color: #000000; font-weight: bold;">Anggaran: Rp <?= number_format($statistik['total_anggaran'], 0, ',', '.') ?></span>
                            <span id="budgetScaleSisa" style="font-weight: 800; color: var(--navy-deep);">Sisa: Rp <?= number_format($statistik['sisa_anggaran'], 0, ',', '.') ?></span>
                        </div>
                    </div>
                    <div class="chart-legend-custom">
                        <div class="legend-item"><span class="legend-dot color-black"></span> Anggaran</div>
                        <div class="legend-item"><span class="legend-dot color-green"></span> Realisasi</div>
                    </div>
                </div>
            </div>
            <!-- Right: Status Pelaksanaan Kegiatan Comparison -->
            <div class="inner-card comparison-card" style="grid-column: span 2;">
                <div class="inner-header" style="display: flex; justify-content: space-between; align-items: center;">
                    <span>Perbandingan Visualisasi Tren Realisasi Pelaksanaan (Tahun <?= $tahun_aktif ?>)</span>
                    <span class="badge badge-primary-lite" style="background: #e0e7ff; color: #4338ca; padding: 4px 12px; border-radius: 20px; font-size: 0.75rem;">Analisis Dashboard</span>
                </div>
                <div class="inner-body">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
                        <!-- Comparison Item 1: Stacked Bar -->
                        <div>
                            <h5 style="text-align: center; color: #64748b; font-size: 0.85rem; margin-bottom: 15px; text-transform: uppercase; letter-spacing: 0.05em;">Opsi A: Stacked Bar Chart</h5>
                            <div style="height: 280px; position: relative;">
                                <canvas id="executionBarChart"></canvas>
                            </div>
                            <div style="margin-top: 15px; padding: 12px; background: #f0fdf4; border-radius: 8px; font-size: 0.85rem; color: #166534; border-left: 4px solid #10b981;">
                                <strong>Karakteristik:</strong> Menonjolkan total volume kegiatan bulanan dan persentase kontribusi status secara mutlak.
                            </div>
                        </div>

                        <!-- Comparison Item 2: Polygon -->
                        <div>
                            <h5 style="text-align: center; color: #64748b; font-size: 0.85rem; margin-bottom: 15px; text-transform: uppercase; letter-spacing: 0.05em;">Opsi B: Frequency Polygon</h5>
                            <div style="height: 280px; position: relative;">
                                <canvas id="executionPolygonChart"></canvas>
                            </div>
                            <div style="margin-top: 15px; padding: 12px; background: #fff1f2; border-radius: 8px; font-size: 0.85rem; color: #9f1239; border-left: 4px solid #f43f5e;">
                                <strong>Karakteristik:</strong> Menonjolkan perubahan drastis (laju) antar bulan, namun volume total sulit dibandingkan.
                            </div>
                            <p style="margin-top: 10px; font-size: 0.75rem; color: #94a3b8; font-style: italic; text-align: center;">
                                Catatan: Poligon frekuensi digunakan untuk analisis tren, bukan perbandingan volume absolut.
                            </p>
                        </div>
                    </div>

                    <div class="recommendation-box" style="margin-top: 30px; padding: 20px; background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 12px;">
                        <h4 style="margin: 0 0 10px 0; color: #1e293b; font-size: 1rem; display: flex; align-items: center; gap: 10px;">
                            <i class="fas fa-lightbulb" style="color: #eab308;"></i> Rekomendasi untuk Dashboard Pemerintahan
                        </h4>
                        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px; align-items: flex-start;">
                            <p style="margin: 0; font-size: 0.9rem; color: #475569; line-height: 1.6;">
                                Untuk kebutuhan pelaporan akuntabilitas (PKPT), <strong>Stacked Bar Chart (Opsi A)</strong> adalah pilihan paling tepat. Grafik ini secara jujur menunjukkan "beban kerja nyata" setiap bulan. Pimpinan dapat melihat total target (tinggi batang) sekaligus keberhasilan (warna hijau) tanpa perlu melakukan kalkulasi mental. Poligon (Opsi B) lebih cocok untuk melihat statistik abstrak atau tren jangka panjang yang sangat padat. <strong>Oleh karena itu, visual stacked bar digunakan sebagai tampilan utama dashboard, sementara grafik poligon bersifat opsional untuk analisis pendukung.</strong>
                            </p>
                            <div class="narrative-summary-stat" style="background: white; padding: 15px; border-radius: 10px; border: 1px solid #e2e8f0; text-align: center;">
                                <div style="font-size: 1.5rem; font-weight: 800; color: #10b981;" id="statusPercentMain"><?= number_format($statistik['persentase_pelaksanaan'], 0) ?>%</div>
                                <div style="font-size: 0.75rem; color: #64748b; text-transform: uppercase;">Total Keterlaksanaan</div>
                                 <div style="font-size: 0.6rem; color: #94a3b8; font-style: italic; margin-top: 2px;">Berdasarkan total kegiatan PKPT</div>
                                <div style="font-size: 0.8rem; color: #334155; margin-top: 5px;"><strong id="statusCountTerlaksana"><?= number_format($statistik['total_terlaksana'] ?? 0) ?></strong> dari <span id="statusCountSub"><?= $statistik['total_program'] ?></span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section 2: Aktivitas & Tren -->
    <div class="modern-card-group">
        <div class="card-group-header">
            <i class="fas fa-chart-line"></i> Aktivitas & Tren
        </div>
        <div class="card-group-body grid-2">
            <div class="inner-card">
                <div class="inner-header">Tren Bulanan Anggaran & Realisasi</div>
                <div class="inner-body">
                    <canvas id="trendChartModern"></canvas>
                    <div class="chart-legend-custom centered">
                        <div class="legend-item"><span class="legend-dot color-navy"></span> Anggaran (Rencana)</div>
                        <div class="legend-item"><span class="legend-dot color-green"></span> Realisasi (Actual)</div>
                        <div class="legend-item"><span class="legend-line dashed"></span> Proyeksi / Rencana</div>
                    </div>
                    <div class="chart-footer-info text-center">
                        <small id="trendChartSubtitle">Data Realisasi dan Proyeksi Tahun <?= date('Y') ?></small>
                    </div>
                </div>
            </div>
            <div class="inner-card">
                <div class="inner-header">Distribusi Status Kegiatan Program dan Penugasan Tambahan (Tahun <span id="freqChartYear"><?= $tahun_aktif ?></span>)</div>
                <div class="inner-body">
                    <p class="chart-subtitle-mini">Menampilkan jumlah kegiatan berdasarkan status pelaksanaan</p>
                    <canvas id="freqChartModern"></canvas>
                    <div class="chart-legend-custom centered mini">
                        <div class="legend-item"><span class="legend-dot color-navy"></span> Program PKPT Utama</div>
                        <div class="legend-item"><span class="legend-dot color-yellow"></span> Penugasan Tambahan</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section 3: Operasional -->
    <div class="modern-card-group">
        <div class="card-group-body calendar-main-container">
            <!-- Calendar Area -->
            <div class="inner-card calendar-wrapper-informative">
                <div class="inner-header flex-between">
                    <div class="calendar-header-left">
                        <div class="calendar-mini-legend">
                            <div class="legend-item">
                                <span class="legend-dot event-terlaksana"></span> <span>Terlaksana</span>
                            </div>
                            <div class="legend-item">
                                <span class="legend-dot event-penugasan-tambahan"></span> <span>Penugasan Tambahan</span>
                            </div>
                            <div class="legend-item">
                                <span class="legend-dot event-tidak-terlaksana"></span> <span>Tidak Terlaksana</span>
                            </div>
                        </div>
                    </div>
                    <div class="calendar-header-right">
                        <button id="prevMonth" class="btn-cal-modern"><i class="fas fa-chevron-left"></i></button>
                        <span class="calendar-current-period">
                            <span id="calendarLabelMonth">Februari</span> <span id="calendarLabelYear">2026</span>
                        </span>
                        <button id="nextMonth" class="btn-cal-modern"><i class="fas fa-chevron-right"></i></button>
                    </div>
                </div>
                <div class="inner-body no-padding">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<!-- Inject Active Year -->
<script>
    const pkptActiveYear = <?= json_encode((int)$tahun_aktif) ?>;
    const pkptApiUrls = {
        calendar: '<?= site_url('dashboard/calendar-data') ?>',
        charts: '<?= site_url('dashboard/chart-data') ?>',
        statistics: '<?= site_url('dashboard/statistics') ?>',
        upcoming: '<?= site_url('dashboard/upcoming') ?>'
    };
</script>
<!-- Dashboard JavaScript -->
<script src="<?= base_url('assets/js/dashboard.js?v=' . time()) ?>"></script>

<?= $this->endSection() ?>
