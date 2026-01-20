<?= $this->extend('layouts/utama') ?>

<?= $this->section('content') ?>

<!-- Statistics Cards -->
<div class="stats-container">
    <div class="stat-card card-total">
        <div class="stat-icon">
            <i class="fas fa-tasks"></i>
        </div>
        <div class="stat-info">
            <h3><?= number_format($statistik['total_program']) ?></h3>
            <p>Total Program</p>
        </div>
    </div>

    <div class="stat-card card-budget">
        <div class="stat-icon">
            <i class="fas fa-money-bill-wave"></i>
        </div>
        <div class="stat-info">
            <h3>Rp <?= number_format($statistik['total_anggaran'], 0, ',', '.') ?></h3>
            <p>Total Anggaran</p>
        </div>
    </div>

    <div class="stat-card card-realization">
        <div class="stat-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="stat-info">
            <h3>Rp <?= number_format($statistik['total_realisasi'], 0, ',', '.') ?></h3>
            <p>Total Realisasi</p>
        </div>
    </div>

    <div class="stat-card card-percentage">
        <div class="stat-icon">
            <i class="fas fa-chart-line"></i>
        </div>
        <div class="stat-info">
            <h3><?= number_format($statistik['persentase_realisasi'], 2) ?>%</h3>
            <p>Persentase Realisasi</p>
            <div class="progress-bar">
                <div class="progress-fill" style="width: <?= $statistik['persentase_realisasi'] ?>%"></div>
            </div>
        </div>
    </div>
</div>

<!-- Main Content Grid -->
<div class="dashboard-grid">
    <!-- Calendar Section -->
    <div class="dashboard-card calendar-section">
        <div class="card-header">
            <h2><i class="fas fa-calendar-alt"></i> Kalender Kegiatan</h2>
            <div class="calendar-controls">
                <button id="prevMonth" class="btn-calendar"><i class="fas fa-chevron-left"></i></button>
                <span id="currentMonth">January 2026</span>
                <button id="nextMonth" class="btn-calendar"><i class="fas fa-chevron-right"></i></button>
            </div>
        </div>
        <div class="card-body">
            <div id="calendar"></div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="dashboard-card charts-section">
        <div class="card-header">
            <h2><i class="fas fa-chart-pie"></i> Distribusi Status Program</h2>
        </div>
        <div class="card-body">
            <canvas id="statusChart"></canvas>
        </div>
    </div>

    <div class="dashboard-card charts-section">
        <div class="card-header">
            <h2><i class="fas fa-chart-bar"></i> Anggaran vs Realisasi</h2>
        </div>
        <div class="card-body">
            <canvas id="budgetChart"></canvas>
        </div>
    </div>

    <div class="dashboard-card charts-section full-width">
        <div class="card-header">
            <h2><i class="fas fa-chart-area"></i> Tren Bulanan</h2>
        </div>
        <div class="card-body">
            <canvas id="trendChart"></canvas>
        </div>
    </div>
</div>

<!-- Upcoming Activities -->
<?php if (!empty($upcoming)): ?>
<div class="dashboard-card">
    <div class="card-header">
        <h2><i class="fas fa-calendar-check"></i> Kegiatan Mendatang</h2>
    </div>
    <div class="card-body">
        <div class="upcoming-list">
            <?php foreach ($upcoming as $activity): ?>
                <div class="upcoming-item status-<?= strtolower(str_replace(' ', '-', $activity['status'])) ?>">
                    <div class="upcoming-date">
                        <span class="day"><?= date('d', strtotime($activity['tanggal_mulai'])) ?></span>
                        <span class="month"><?= date('M', strtotime($activity['tanggal_mulai'])) ?></span>
                    </div>
                    <div class="upcoming-info">
                        <h4><?= esc($activity['nama_kegiatan']) ?></h4>
                        <p>
                            <i class="fas fa-user"></i> <?= esc($activity['pelaksana']) ?> |
                            <i class="fas fa-building"></i> <?= esc($activity['unit_kerja']) ?>
                        </p>
                    </div>
                    <div class="upcoming-budget">
                        <strong>Rp <?= number_format($activity['anggaran'], 0, ',', '.') ?></strong>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<!-- Dashboard JavaScript -->
<script src="<?= base_url('assets/js/dashboard.js') ?>"></script>

<?= $this->endSection() ?>
