/**
 * Dashboard JavaScript - Modern Redesign
 * Handles calendar, charts, and dynamic data loading
 */

let currentYear = (typeof pkptActiveYear !== 'undefined') ? pkptActiveYear : new Date().getFullYear();
let lastYear = currentYear;
let currentMonth = new Date().getMonth();
let charts = {};

// Initialize on page load
document.addEventListener('DOMContentLoaded', function () {
    initializeCalendar();
    initializeCharts();
    updateStatistics(currentYear);

    // Calendar navigation
    document.getElementById('prevMonth')?.addEventListener('click', () => {
        currentMonth--;
        if (currentMonth < 0) {
            currentMonth = 11;
            currentYear--;
        }
        handleDateChange();
    });

    document.getElementById('nextMonth')?.addEventListener('click', () => {
        currentMonth++;
        if (currentMonth > 11) {
            currentMonth = 0;
            currentYear++;
        }
        handleDateChange();
    });
});

/**
 * Handle change in calendar date (Month or Year)
 */
function handleDateChange() {
    updateCalendar();

    // If year changed, update the whole dashboard
    if (currentYear !== lastYear) {
        lastYear = currentYear;
        updateCharts(currentYear);
        updateStatistics(currentYear);
        updateStatistics(currentYear);
    }
}

/**
 * Initialize Calendar
 */
function initializeCalendar() {
    updateCalendar();
}

/**
 * Update Calendar Display
 */
function updateCalendar() {
    const monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

    const monthLabel = document.getElementById('calendarLabelMonth');
    if (monthLabel) monthLabel.textContent = monthNames[currentMonth];

    const yearLabel = document.getElementById('calendarLabelYear');
    if (yearLabel) yearLabel.textContent = currentYear;

    // Fetch calendar data
    const url = (typeof pkptApiUrls !== 'undefined') ? pkptApiUrls.calendar : `${window.location.origin}/dashboard/calendar-data`;
    fetch(`${url}?year=${currentYear}&month=${currentMonth + 1}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                renderCalendar(data.data);
            }
        })
        .catch(error => console.error('Calendar error:', error));
}

/**
 * Render Calendar
 */
function renderCalendar(events) {
    const firstDay = new Date(currentYear, currentMonth, 1);
    const lastDay = new Date(currentYear, currentMonth + 1, 0);
    const prevLastDay = new Date(currentYear, currentMonth, 0);

    const firstDayIndex = firstDay.getDay();
    const lastDayDate = lastDay.getDate();
    const prevLastDayDate = prevLastDay.getDate();

    let calendarHtml = '<table class="calendar-table"><thead><tr>';

    // Header (English - as per user request image)
    const days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
    days.forEach(day => {
        calendarHtml += `<th>${day}</th>`;
    });
    calendarHtml += '</tr></thead><tbody><tr>';

    // Previous month days
    for (let x = firstDayIndex; x > 0; x--) {
        calendarHtml += `<td><div class="calendar-day other-month">${prevLastDayDate - x + 1}</div></td>`;
    }

    // Current month days
    const today = new Date();
    for (let day = 1; day <= lastDayDate; day++) {
        if ((day + firstDayIndex - 1) % 7 === 0 && day !== 1) {
            calendarHtml += '</tr><tr>';
        }

        const currentDate = new Date(currentYear, currentMonth, day);
        const dateString = formatDate(currentDate);
        const isToday = (day === today.getDate() &&
            currentMonth === today.getMonth() &&
            currentYear === today.getFullYear());

        // Find events for this day
        const dayEvents = events.filter(event => {
            const eventStart = event.start.split(' ')[0];
            const eventEnd = event.end.split(' ')[0];
            return dateString >= eventStart && dateString <= eventEnd;
        });

        calendarHtml += `<td>
            <div class="calendar-day ${isToday ? 'today' : ''}">${day}</div>
            <div class="calendar-events">`;

        dayEvents.forEach(event => {
            let statusClass = 'event-default';
            if (event.status === 'Terlaksana') statusClass = 'event-terlaksana';
            else if (event.status === 'Penugasan Tambahan' || event.status === 'Penambahan Penugasan') statusClass = 'event-penugasan-tambahan';
            else if (event.status === 'Tidak Terlaksana') statusClass = 'event-tidak-terlaksana';

            const dateRange = (event.start !== event.end) ? `<br/><small>${event.start} s.d. ${event.end}</small>` : '';
            calendarHtml += `<div class="calendar-event-block ${statusClass}" title="${event.title}">
                                ${event.title}
                                <div class="event-tooltip-content" style="display:none;">
                                    <strong>${event.title}</strong><br/>
                                    Status: ${event.status}${dateRange}
                                </div>
                             </div>`;
        });

        calendarHtml += '</div></td>';
    }

    // Next month days
    const remainingCells = (7 - ((firstDayIndex + lastDayDate) % 7)) % 7;
    for (let day = 1; day <= remainingCells; day++) {
        calendarHtml += `<td><div class="calendar-day other-month">${day}</div></td>`;
    }

    calendarHtml += '</tr></tbody></table>';

    const calEl = document.getElementById('calendar');
    if (calEl) calEl.innerHTML = calendarHtml;
}

/**
 * Initialize Charts
 */
function initializeCharts() {
    updateCharts(currentYear);
}

/**
 * Update Chart Data
 */
function updateCharts(year) {
    const freqYearEl = document.getElementById('freqChartYear');
    if (freqYearEl) freqYearEl.textContent = year;

    const url = (typeof pkptApiUrls !== 'undefined') ? pkptApiUrls.charts : `${window.location.origin}/dashboard/chart-data`;
    fetch(`${url}?year=${year}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                renderTrendChart(data.data.monthly_trend);
                renderFrequencyChart(data.data.status_distribution);
                renderMonthlyStatusPolygon(data.data.monthly_status_distribution);
            }
        })
        .catch(error => console.error('Chart error:', error));
}

/**
 * Render Modern Trend Chart
 */
function renderTrendChart(data) {
    const ctx = document.getElementById('trendChartModern');
    if (!ctx) return;

    if (charts.trend) charts.trend.destroy();

    const currentYear = new Date().getFullYear();
    const currentMonth = new Date().getMonth(); // 0-11
    const selectedYear = parseInt(document.getElementById('dashboardYear')?.value || currentYear);

    // Segment data for solid vs dashed
    const isCurrentYear = (selectedYear === currentYear);

    charts.trend = new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.labels,
            datasets: [
                {
                    label: 'Anggaran (Rencana)',
                    data: data.anggaran,
                    borderColor: '#1a2a44',
                    backgroundColor: 'transparent',
                    borderWidth: 2,
                    pointRadius: 4,
                    pointBackgroundColor: '#1a2a44',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 1,
                    tension: 0.4,
                    fill: false,
                    segment: {
                        borderDash: (ctx) => isCurrentYear && ctx.p0DataIndex >= currentMonth ? [5, 5] : []
                    }
                },
                {
                    label: 'Realisasi (Actual)',
                    data: data.realisasi,
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    borderWidth: 3,
                    pointRadius: 5,
                    pointBackgroundColor: '#10b981',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    tension: 0.4,
                    fill: true,
                    segment: {
                        borderDash: (ctx) => isCurrentYear && ctx.p0DataIndex >= currentMonth ? [5, 5] : []
                    }
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            aspectRatio: 2.5,
            plugins: {
                legend: { display: false },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                        label: function (context) {
                            let label = context.dataset.label || '';
                            if (label) label += ': ';
                            if (context.parsed.y !== null) {
                                label += 'Rp ' + formatNumber(context.parsed.y);
                            }
                            return label;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#f1f5f9' },
                    ticks: {
                        font: { size: 11, family: 'Arial', weight: '500' },
                        callback: value => (value / 1000000) + 'M',
                        color: '#64748b'
                    }
                },
                x: {
                    grid: { display: false },
                    ticks: {
                        font: { size: 11, family: 'Arial', weight: '500' },
                        color: '#64748b'
                    }
                }
            }
        }
    });

    // Update the subtitle based on current context
    const subtitle = document.getElementById('trendChartSubtitle');
    if (subtitle) {
        if (isCurrentYear) {
            subtitle.innerHTML = `Data Realisasi dan Proyeksi Tahun ${currentYear}`;
        } else {
            subtitle.innerHTML = `Laporan Tahunan ${selectedYear} | Anggaran vs Realisasi Total`;
        }
    }
}

/**
 * Render Frequency Stacked Bar Chart
 */
function renderFrequencyChart(dist) {
    const ctx = document.getElementById('freqChartModern');
    if (!ctx) return;

    if (charts.freq) charts.freq.destroy();

    const labels = dist.core.labels; // Status labels: Terlaksana, etc

    charts.freq = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Program PKPT Utama',
                    data: dist.core.data,
                    backgroundColor: '#1a2a44',
                    borderRadius: 4,
                    barThickness: 32
                },
                {
                    label: 'Penugasan Tambahan',
                    data: dist.additional.data,
                    backgroundColor: '#FAC70B',
                    borderRadius: 4,
                    barThickness: 32
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            aspectRatio: 2.5,
            plugins: {
                legend: { display: false },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    backgroundColor: 'rgba(255, 255, 255, 0.95)',
                    titleColor: '#1e293b',
                    bodyColor: '#475569',
                    borderColor: '#e2e8f0',
                    borderWidth: 1,
                    padding: 12,
                    callbacks: {
                        label: function (context) {
                            const label = context.dataset.label || '';
                            const value = context.parsed.y;
                            return ` ${label}: ${value} kegiatan`;
                        },
                        afterBody: function (context) {
                            const chartData = context[0].chart.data;
                            const index = context[0].dataIndex;
                            let total = 0;
                            chartData.datasets.forEach(ds => {
                                total += ds.data[index] || 0;
                            });
                            return `\nTotal: ${total} kegiatan`;
                        }
                    }
                }
            },
            scales: {
                y: {
                    stacked: true,
                    beginAtZero: true,
                    grid: { color: '#f1f5f9' },
                    ticks: {
                        stepSize: 2,
                        font: { size: 10, family: 'Arial', weight: '500' },
                        color: '#64748b'
                    }
                },
                x: {
                    stacked: true,
                    grid: { display: false },
                    ticks: {
                        font: { size: 10, family: 'Arial', weight: '500' },
                        color: '#64748b'
                    }
                }
            }
        }
    });
}

/**
 * Render Side Frequency Bar Chart
 */
function renderSideFrequencyChart(dist) {
    const ctx = document.getElementById('sideFreqChart');
    if (!ctx) return;

    if (charts.sideFreq) charts.sideFreq.destroy();

    charts.sideFreq = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: dist.core.labels,
            datasets: [
                {
                    label: 'PKPT Utama',
                    data: dist.core.data,
                    backgroundColor: '#10b981',
                    borderRadius: 4,
                    barThickness: 12
                },
                {
                    label: 'Penugasan Tambahan',
                    data: dist.additional.data,
                    backgroundColor: '#FAC70B',
                    borderRadius: 4,
                    barThickness: 12
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            aspectRatio: 1.8,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#f1f5f9' },
                    ticks: { stepSize: 3, font: { size: 9 } }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 9 } }
                }
            }
        }
    });
}

/**
 * Update Statistics Cards
 */
function updateStatistics(year) {
    const url = (typeof pkptApiUrls !== 'undefined') ? pkptApiUrls.statistics : `${window.location.origin}/dashboard/statistics`;
    fetch(`${url}?year=${year}`)
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                const data = result.data;

                // Update Period Labels
                document.querySelectorAll('.period-label').forEach(el => {
                    el.textContent = `Tahunan ${year}`;
                });

                const totalProgramEl = document.getElementById('statTotalProgram');
                const totalAnggaranEl = document.getElementById('statTotalAnggaran');
                const totalRealisasiEl = document.getElementById('statTotalRealisasi');
                const persentaseRealisasiEl = document.getElementById('statPersentaseRealisasi');

                if (totalProgramEl) totalProgramEl.textContent = formatNumber(data.total_program);
                if (totalAnggaranEl) totalAnggaranEl.textContent = formatNumber(data.total_anggaran);
                if (totalRealisasiEl) totalRealisasiEl.textContent = formatNumber(data.total_realisasi);
                if (persentaseRealisasiEl) persentaseRealisasiEl.innerHTML = Math.round(data.persentase_realisasi) + '<span class="unit-percent">%</span>';

                // Update "Anggaran vs Realisasi" section

                // Update "Anggaran vs Realisasi" section
                const budgetValRealisasi = document.getElementById('budgetValRealisasi');
                const budgetPercentRealisasi = document.getElementById('budgetPercentRealisasi');
                const budgetProgressBar = document.getElementById('budgetProgressBar');
                const budgetScaleTotal = document.getElementById('budgetScaleTotal');
                const budgetScaleSisa = document.getElementById('budgetScaleSisa');
                const statusPercentMain = document.getElementById('statusPercentMain');
                const statusCountSub = document.getElementById('statusCountSub');
                const statusCountTerlaksana = document.getElementById('statusCountTerlaksana');

                if (budgetValRealisasi) budgetValRealisasi.textContent = formatNumber(Math.round(data.total_realisasi / 1000)) + '.000';
                if (budgetPercentRealisasi) budgetPercentRealisasi.textContent = Math.round(data.persentase_realisasi) + '% REALISASI';
                if (budgetProgressBar) budgetProgressBar.style.width = data.persentase_realisasi + '%';
                if (budgetScaleTotal) budgetScaleTotal.textContent = 'Anggaran: Rp ' + formatNumber(data.total_anggaran);
                if (budgetScaleSisa) budgetScaleSisa.textContent = 'Sisa: Rp ' + formatNumber(data.sisa_anggaran);
                if (statusPercentMain) statusPercentMain.textContent = Math.round(data.persentase_pelaksanaan) + '%';
                if (statusCountSub) statusCountSub.textContent = formatNumber(data.total_program);
                if (statusCountTerlaksana) statusCountTerlaksana.textContent = formatNumber(data.total_terlaksana || 0);

                // Update Mini Budget Chart
                renderBudgetMiniChart(data);
            }
        })
        .catch(error => console.error('Statistics error:', error));
}

/**
 * Render Mini Doughnut for Budget Card
 */
function renderBudgetMiniChart(data) {
    const ctx = document.getElementById('budgetDoughnutChart');
    if (!ctx) return;

    if (charts.budgetMini) charts.budgetMini.destroy();

    const percent = Math.min(100, Math.round(data.persentase_realisasi));
    const sisa = Math.max(0, 100 - percent);

    charts.budgetMini = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Terelaisasi', 'Sisa'],
            datasets: [{
                data: [percent, sisa],
                backgroundColor: ['#10b981', '#f1f5f9'],
                borderWidth: 0,
                hoverOffset: 0,
                cutout: '80%',
                borderRadius: percent >= 100 ? 0 : 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    enabled: true,
                    callbacks: {
                        label: (ctx) => ` ${ctx.label}: ${ctx.raw}%`
                    }
                }
            },
            animation: {
                duration: 1500,
                easing: 'easeOutQuart'
            }
        }
    });

    // Update center text (just in case)
    const percentEl = document.getElementById('budgetMiniPercent');
    if (percentEl) percentEl.textContent = percent + '%';
}

/**
 * Render Monthly Status Charts Comparison (Bar & Polygon)
 */
function renderMonthlyStatusPolygon(data) {
    const ctxBar = document.getElementById('executionBarChart');
    const ctxPoly = document.getElementById('executionPolygonChart');

    if (!ctxBar || !ctxPoly) return;

    if (charts.executionBar) charts.executionBar.destroy();
    if (charts.executionPoly) charts.executionPoly.destroy();

    const commonScales = {
        y: {
            stacked: true,
            beginAtZero: true,
            grid: { color: '#f1f5f9', drawBorder: false },
            ticks: { stepSize: 1, precision: 0, font: { size: 10 }, color: '#64748b' },
            afterDataLimits: (scale) => { if (scale.max < 5) scale.max = 5; }
        },
        x: {
            stacked: true,
            grid: { display: false },
            ticks: { font: { size: 10, weight: '500' }, color: '#64748b' }
        }
    };

    const commonPlugins = {
        legend: {
            display: true,
            position: 'top',
            align: 'start',
            labels: { usePointStyle: true, padding: 15, font: { size: 11, weight: '600' } }
        },
        tooltip: {
            mode: 'index',
            intersect: false,
            backgroundColor: '#ffffff',
            titleColor: '#1e293b',
            bodyColor: '#475569',
            borderColor: '#e2e8f0',
            borderWidth: 1,
            padding: 12,
            usePointStyle: true,
            callbacks: {
                label: (ctx) => ` ${ctx.dataset.label}: ${ctx.parsed.y || 0} kegiatan`,
                afterBody: (items) => {
                    const idx = items[0].dataIndex;
                    const tot = (data.terlaksana[idx] || 0) + (data.tidak_terlaksana[idx] || 0);
                    return tot === 0 ? '\n(Tidak ada kegiatan)' : `\nTotal: ${tot} kegiatan`;
                }
            }
        }
    };

    // Custom plugin for empty marker and total labels
    const chartEnhancements = {
        id: 'chartEnhancements',
        afterDatasetsDraw: (chart) => {
            const { ctx, data, scales: { x, y } } = chart;
            ctx.save();
            data.labels.forEach((_, i) => {
                const terlaksana = (data.datasets[0] && data.datasets[0].data[i]) || 0;
                const tidak = (data.datasets[1] && data.datasets[1].data[i]) || 0;
                const total = terlaksana + tidak;
                const xPos = x.getPixelForTick(i);

                // 1. Label untuk tiap segmen di dalam batang
                if (chart.config.type === 'bar' && total > 0) {
                    let cumulativeY = 0;
                    chart.data.datasets.forEach((dataset) => {
                        const val = dataset.data[i] || 0;
                        if (val > 0) {
                            cumulativeY += val;
                            // Hitung koordinat Y tepat di tengah segmen (koordinat pixel berbanding terbalik dengan nilai Y)
                            const segmentCenterY = y.getPixelForValue(cumulativeY - (val / 2));

                            ctx.fillStyle = '#ffffff';
                            ctx.font = 'bold 10px Arial';
                            ctx.textAlign = 'center';
                            ctx.textBaseline = 'middle';
                            ctx.fillText(val, xPos, segmentCenterY);
                        }
                    });
                }

                // 2. Label Total di atas Batang (Hanya untuk Bar Chart)
                if (chart.config.type === 'bar' && total > 0) {
                    const yPos = y.getPixelForValue(total);
                    ctx.fillStyle = '#1e293b';
                    ctx.font = 'bold 11px Arial';
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'bottom';
                    ctx.fillText(total, xPos, yPos - 8);
                }

                // 3. Marker untuk bulan kosong
                if (total === 0) {
                    ctx.fillStyle = '#cbd5e1';
                    ctx.beginPath();
                    ctx.arc(xPos, y.getPixelForValue(0) - 5, 2, 0, 2 * Math.PI);
                    ctx.fill();
                }
            });
            ctx.restore();
        }
    };

    // 1. Render Bar Chart (Stacked)
    charts.executionBar = new Chart(ctxBar, {
        type: 'bar',
        data: {
            labels: data.labels,
            datasets: [
                { label: 'Terlaksana', data: data.terlaksana, backgroundColor: '#10b981', borderRadius: 4, barThickness: 20 },
                { label: 'Tidak Terlaksana', data: data.tidak_terlaksana, backgroundColor: '#ef4444', borderRadius: 4, barThickness: 20 }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: commonPlugins,
            scales: {
                ...commonScales,
                y: { ...commonScales.y, grace: '15%' }
            }
        },
        plugins: [chartEnhancements]
    });

    // 2. Render Polygon Chart (Area)
    charts.executionPoly = new Chart(ctxPoly, {
        type: 'line',
        data: {
            labels: data.labels,
            datasets: [
                { label: 'Terlaksana', data: data.terlaksana, borderColor: '#10b981', backgroundColor: 'rgba(16, 185, 129, 0.1)', fill: true, tension: 0.4, pointRadius: 4 },
                { label: 'Tidak Terlaksana', data: data.tidak_terlaksana, borderColor: '#ef4444', backgroundColor: 'rgba(239, 68, 68, 0.1)', fill: true, tension: 0.4, pointRadius: 4 }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: commonPlugins,
            scales: {
                ...commonScales,
                y: { ...commonScales.y, stacked: false },
                x: { ...commonScales.x, stacked: false }
            }
        },
        plugins: [chartEnhancements]
    });
}

/**
 * Helpers
 */

function formatDate(date) {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
}

function formatNumber(num) {
    if (num === null || num === undefined) return '0';
    return new Intl.NumberFormat('id-ID').format(num);
}
