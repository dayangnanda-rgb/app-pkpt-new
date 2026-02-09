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
                <p>TOTAL PROGRAM</p>
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
            <!-- Right: Status Pelaksanaan Kegiatan -->
            <div class="inner-card">
                <div class="inner-header">Tren Frekuensi Status Pelaksanaan Kegiatan per Bulan</div>
                <div class="inner-body">
                    <div class="radar-chart-container">
                        <canvas id="executionBarChart"></canvas>
                    </div>
                    <div class="status-footer-info-modern" style="flex-direction: column; align-items: flex-start; gap: 8px;">
                        <div style="display: flex; align-items: baseline; gap: 8px; width: 100%;">
                            <span style="font-size: 1.5rem; font-weight: 700; color: #10b981;" id="statusPercentMain"><?= number_format($statistik['persentase_pelaksanaan'], 0) ?>%</span>
                            <span style="font-size: 0.95rem; color: #475569; font-weight: 500;">kegiatan terlaksana dari total <strong id="statusCountSub"><?= $statistik['total_program'] ?></strong> kegiatan tahun <strong><?= $tahun_aktif ?></strong></span>
                        </div>
                        <div class="note-sub" style="margin-top: 4px;">Grafik menampilkan tren frekuensi status pelaksanaan per bulan untuk program PKPT dan penugasan tambahan</div>
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
