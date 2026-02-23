<?= $this->extend('layouts/utama') ?>

<?= $this->section('content') ?>

<div class="dashboard-wrapper">

    <!-- 4 Statistics Cards -->
    <div class="stats-container-modern">
        <div class="stat-card-modern card-total-modern">
            <div class="stat-icon-modern">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-info-modern">
                <div style="display: flex; align-items: baseline; gap: 4px;">
                    <h3 id="statPersentaseKinerjaMain"><?= number_format($statistik['persentase_pelaksanaan'], 0) ?></h3>
                    <span class="unit-percent" style="font-size: 1.2rem; color: #10b981; font-weight: 800;">%</span>
                </div>
                <p style="margin: 0; font-weight: 800; letter-spacing: 0.5px;">CAPAIAN KINERJA</p>
                <div style="font-size: 0.75rem; color: rgba(255,255,255,0.8); margin-top: 5px; font-weight: 600;">
                    <span id="statTerlaksanaCount"><?= number_format($statistik['total_terlaksana']) ?></span> dari <span id="statTotalProgram"><?= number_format($statistik['total_program']) ?></span> Selesai
                </div>
                <div class="stat-period-label period-label" style="margin-top: 2px;">Tahunan <?= $tahun_aktif ?></div>
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
            <!-- Left: Anggaran vs Realisasi (WIDENED) -->
            <div class="inner-card" style="grid-column: span 2;">
                <div class="inner-header">Ringkasan Eksekutif Anggaran & Realisasi (Tahun <?= $tahun_aktif ?>)</div>
                <div class="inner-body">
                    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 20px;">
                         <div class="budget-top-labels" style="margin-bottom: 0;">
                            <div style="font-size: 0.8rem; color: #64748b; font-weight: 600; text-transform: uppercase; margin-bottom: 5px;">Total Realisasi</div>
                            <span class="budget-curr">Rp</span>
                            <span class="budget-val" id="budgetValRealisasi" style="font-size: 2.5rem;"><?= number_format($statistik['total_realisasi'], 0, ',', '.') ?></span>
                        </div>
                        <div style="text-align: right;">
                             <span class="budget-badge-percent" id="budgetPercentRealisasi" style="padding: 8px 20px; font-size: 1rem; border-radius: 30px;"><?= number_format($statistik['persentase_realisasi'], 0) ?>% TERELISASI</span>
                        </div>
                    </div>

                    <div class="modern-progress-large" style="height: 14px; border-radius: 7px; background: #f1f5f9; margin-bottom: 30px;">
                        <div class="progress-fill-main" id="budgetProgressBar" style="width: <?= $statistik['persentase_realisasi'] ?>%; height: 100%; border-radius: 7px;"></div>
                    </div>

                    <!-- Ringkasan Eksekutif Anggaran KPI Cards (4 columns) -->
                    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px;">
                        <!-- KPI 1: % Realisasi -->
                        <div style="background: #f8fafc; padding: 20px; border-radius: 12px; border: 1px solid #e2e8f0; display: flex; align-items: center; gap: 15px;">
                            <div style="width: 45px; height: 45px; border-radius: 10px; background: rgba(16, 185, 129, 0.1); color: #10b981; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                                <i class="fas fa-chart-pie"></i>
                            </div>
                            <div>
                                <div style="font-size: 0.7rem; color: #64748b; font-weight: 700; text-transform: uppercase;">% Realisasi</div>
                                <div style="font-size: 1.25rem; font-weight: 800; color: #1e293b;" id="kpiPercent"><?= number_format($statistik['persentase_realisasi'], 0) ?>%</div>
                            </div>
                        </div>
                        
                        <!-- KPI 2: Sisa Anggaran -->
                        <div style="background: #f8fafc; padding: 20px; border-radius: 12px; border: 1px solid #e2e8f0; display: flex; align-items: center; gap: 15px;">
                            <div style="width: 45px; height: 45px; border-radius: 10px; background: rgba(239, 68, 68, 0.1); color: #ef4444; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                                <i class="fas fa-wallet"></i>
                            </div>
                            <div>
                                <div style="font-size: 0.7rem; color: #64748b; font-weight: 700; text-transform: uppercase;">Sisa Pagu</div>
                                <div style="font-size: 1.25rem; font-weight: 800; color: #1e293b;" id="kpiSisa">Rp <?= number_format($statistik['sisa_anggaran'] / 1000000, 1, ',', '.') ?> Jt</div>
                            </div>
                        </div>

                        <!-- KPI 3: Status Serapan -->
                        <?php 
                            $statusSerapan = 'Kurang';
                            $statusColor = '#ef4444'; $bgColor = 'rgba(239, 68, 68, 0.1)';
                            if($statistik['persentase_realisasi'] >= 90) { $statusSerapan = 'Sangat Baik'; $statusColor = '#10b981'; $bgColor = 'rgba(16, 185, 129, 0.1)'; }
                            else if($statistik['persentase_realisasi'] >= 75) { $statusSerapan = 'Baik'; $statusColor = '#0ea5e9'; $bgColor = 'rgba(14, 165, 233, 0.1)'; }
                            else if($statistik['persentase_realisasi'] >= 50) { $statusSerapan = 'Cukup'; $statusColor = '#f59e0b'; $bgColor = 'rgba(245, 158, 11, 0.1)'; }
                        ?>
                        <div style="background: #f8fafc; padding: 20px; border-radius: 12px; border: 1px solid #e2e8f0; display: flex; align-items: center; gap: 15px;">
                            <div style="width: 45px; height: 45px; border-radius: 10px; background: <?= $bgColor ?>; color: <?= $statusColor ?>; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div>
                                <div style="font-size: 0.7rem; color: #64748b; font-weight: 700; text-transform: uppercase;">Status Serapan</div>
                                <div style="font-size: 1.25rem; font-weight: 800; color: #1e293b;" id="kpiStatus"><?= $statusSerapan ?></div>
                            </div>
                        </div>

                        <!-- KPI 4: Deviasi -->
                        <?php $deviasi = 100 - $statistik['persentase_realisasi']; ?>
                        <div style="background: #f8fafc; padding: 20px; border-radius: 12px; border: 1px solid #e2e8f0; display: flex; align-items: center; gap: 15px;">
                            <div style="width: 45px; height: 45px; border-radius: 10px; background: rgba(100, 116, 139, 0.1); color: #64748b; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <div>
                                <div style="font-size: 0.7rem; color: #64748b; font-weight: 700; text-transform: uppercase;">Deviasi</div>
                                <div style="font-size: 1.25rem; font-weight: 800; color: #1e293b;" id="kpiDeviasi">±<?= number_format($deviasi, 1) ?>%</div>
                            </div>
                        </div>
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

                    <div class="recommendation-box" style="margin-top: 15px; padding: 12px; border-radius: 10px; border: 1px solid #e2e8f0; background: #ffffff;">
                         <button type="button" onclick="this.nextElementSibling.classList.toggle('hidden'); this.querySelector('i').classList.toggle('fa-chevron-down'); this.querySelector('i').classList.toggle('fa-chevron-up');" style="width: 100%; border: none; background: none; display: flex; justify-content: space-between; align-items: center; padding: 0; cursor: pointer; color: #475569; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;">
                            <span><i class="fas fa-lightbulb" style="color: #eab308; margin-right: 8px;"></i> Ringkasan Analisis Akuntabilitas</span>
                            <i class="fas fa-chevron-down" style="font-size: 0.7rem;"></i>
                        </button>
                        <div class="hidden" style="margin-top: 10px; border-top: 1px solid #f1f5f9; padding-top: 10px;">
                            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px; align-items: center;">
                                <ul style="margin: 0; padding-left: 20px; list-style-type: disc; font-size: 0.85rem; color: #475569; line-height: 1.5;">
                                    <li><strong>Opsi A (Utama):</strong> Memberikan rincian beban kerja riil dan perbandingan volume antar status bulanan secara absolut.</li>
                                    <li><strong>Opsi B (Pendukung):</strong> Digunakan untuk mendeteksi fluktuasi drastis atau laju penyelesaian kegiatan secara visual.</li>
                                    <li><strong>Rekomendasi:</strong> Gunakan Stacked Bar untuk pelaporan KPI dan Poligon untuk evaluasi tren musiman.</li>
                                </ul>
                                <div class="narrative-summary-stat" style="background: #f8fafc; padding: 10px; border-radius: 8px; border: 1px solid #e2e8f0; text-align: center;">
                                    <div style="font-size: 1.25rem; font-weight: 800; color: #10b981;" id="statusPercentMain"><?= number_format($statistik['persentase_pelaksanaan'], 0) ?>%</div>
                                    <div style="font-size: 0.65rem; color: #64748b; text-transform: uppercase; font-weight: 700;">Efektivitas</div>
                                    <div style="font-size: 0.75rem; color: #334155; margin-top: 3px;"><strong id="statusCountTerlaksana"><?= number_format($statistik['total_terlaksana'] ?? 0) ?></strong>/<span id="statusCountSub"><?= $statistik['total_program'] ?></span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <style>.hidden { display: none; }</style>
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
                    <!-- Narrative Analysis -->
                    <div class="analysis-box" style="margin-top: 15px; border-radius: 10px; border: 1px solid #bae6fd; background: #f0f9ff; overflow: hidden;">
                        <button type="button" onclick="this.nextElementSibling.classList.toggle('hidden'); this.querySelector('i').classList.toggle('fa-chevron-down'); this.querySelector('i').classList.toggle('fa-chevron-up');" style="width: 100%; border: none; background: none; display: flex; justify-content: space-between; align-items: center; padding: 10px 15px; cursor: pointer; color: #0369a1; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;">
                            <span><i class="fas fa-chart-line" style="margin-right: 8px;"></i> Insight Strategis (Tahun <?= $tahun_aktif ?>)</span>
                            <i class="fas fa-chevron-down" style="font-size: 0.7rem;"></i>
                        </button>
                        <div class="hidden" style="padding: 0 15px 12px 15px; border-top: 1px solid #bae6fd; padding-top: 10px;">
                            <ul style="margin: 0; padding-left: 20px; list-style-type: disc; font-size: 0.85rem; color: #0c4a6e; line-height: 1.5;">
                                <li><strong>Dominasi Realisasi:</strong> Mayoritas program terselesaikan sesuai target (Terlaksana).</li>
                                <li><strong>Efisiensi Tambahan:</strong> Penugasan tambahan diselesaikan 100% tanpa menghambat program utama.</li>
                                <li><strong>Penyebab Tertunda:</strong> Defisit capaian hanya berasal dari dinamika Program PKPT Utama.</li>
                            </ul>
                        </div>
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
