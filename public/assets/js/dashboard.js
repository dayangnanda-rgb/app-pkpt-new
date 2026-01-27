/**
 * Dashboard JavaScript
 * Handles calendar, charts, and dynamic data loading
 */

let currentYear = new Date().getFullYear();
let currentMonth = new Date().getMonth();
let charts = {};

// Initialize on page load
document.addEventListener('DOMContentLoaded', function () {
    initializeCalendar();
    initializeCharts();

    // Calendar navigation
    document.getElementById('prevMonth')?.addEventListener('click', () => {
        currentMonth--;
        if (currentMonth < 0) {
            currentMonth = 11;
            currentYear--;
        }
        updateCalendar();
    });

    document.getElementById('nextMonth')?.addEventListener('click', () => {
        currentMonth++;
        if (currentMonth > 11) {
            currentMonth = 0;
            currentYear++;
        }
        updateCalendar();
    });
});

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
    const monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
        'July', 'August', 'September', 'October', 'November', 'December'];

    document.getElementById('currentMonth').textContent = `${monthNames[currentMonth]} ${currentYear}`;

    // Fetch calendar data
    fetch(`${window.location.origin}/dashboard/calendar-data?year=${currentYear}&month=${currentMonth + 1}`)
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

    // Header
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
            const statusClass = event.status ?
                `status-${event.status.toLowerCase().replace(/ /g, '-')}` : '';
            calendarHtml += `<div class="calendar-event ${statusClass}" 
                                  title="${event.title}">
                                ${event.title}
                             </div>`;
        });

        calendarHtml += '</div></td>';
    }

    // Next month days
    const remainingCells = 42 - (firstDayIndex + lastDayDate);
    for (let day = 1; day <= remainingCells; day++) {
        if ((day + firstDayIndex + lastDayDate - 1) % 7 === 0) {
            calendarHtml += '</tr><tr>';
        }
        calendarHtml += `<td><div class="calendar-day other-month">${day}</div></td>`;
    }

    calendarHtml += '</tr></tbody></table>';

    document.getElementById('calendar').innerHTML = calendarHtml;
}

/**
 * Initialize Charts
 */
function initializeCharts() {
    fetch(`${window.location.origin}/dashboard/chart-data?year=${currentYear}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                createStatusChart(data.data.status_distribution);
                createBudgetChart(data.data.budget_comparison);
                createTrendChart(data.data.monthly_trend);
            }
        })
        .catch(error => console.error('Chart error:', error));
}

/**
 * Create Status Distribution Chart (Pie)
 */
function createStatusChart(data) {
    const ctx = document.getElementById('statusChart');
    if (!ctx) return;

    if (charts.statusChart) {
        charts.statusChart.destroy();
    }

    charts.statusChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: data.labels,
            datasets: [{
                data: data.data,
                backgroundColor: data.labels.map(label => {
                    const l = label.toLowerCase();
                    if (l.includes('terlaksana') && !l.includes('tidak')) return '#10b981'; // Green
                    if (l.includes('tidak terlaksana')) return '#ef4444'; // Red
                    if (l.includes('penugasan')) return '#FAC70B'; // Yellow/Gold
                    return '#94a3b8'; // Default gray
                }),
                borderWidth: 0,
                hoverOffset: 15
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        font: {
                            size: 12
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            const label = context.label || '';
                            const value = context.parsed || 0;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((value / total) * 100).toFixed(1);
                            return `${label}: ${value} (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });
}

/**
 * Create Budget Comparison Chart (Bar)
 */
function createBudgetChart(data) {
    const ctx = document.getElementById('budgetChart');
    if (!ctx) return;

    if (charts.budgetChart) {
        charts.budgetChart.destroy();
    }

    charts.budgetChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: data.labels,
            datasets: [
                {
                    label: 'Anggaran',
                    data: data.anggaran,
                    backgroundColor: '#1a1a2e',
                    borderRadius: 6,
                    barPercentage: 0.6
                },
                {
                    label: 'Realisasi',
                    data: data.realisasi,
                    backgroundColor: '#10b981',
                    borderRadius: 6,
                    barPercentage: 0.6
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function (value) {
                            return 'Rp ' + (value / 1000000).toFixed(0) + 'M';
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'bottom'
                },
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            label += 'Rp ' + formatRupiah(context.parsed.y);
                            return label;
                        }
                    }
                }
            }
        }
    });
}

/**
 * Create Monthly Trend Chart (Line)
 */
function createTrendChart(data) {
    const ctx = document.getElementById('trendChart');
    if (!ctx) return;

    if (charts.trendChart) {
        charts.trendChart.destroy();
    }

    charts.trendChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.labels,
            datasets: [
                {
                    label: 'Anggaran',
                    data: data.anggaran,
                    borderColor: '#1a1a2e',
                    backgroundColor: 'rgba(26, 26, 46, 0.1)',
                    borderWidth: 3,
                    pointBackgroundColor: '#1a1a2e',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: true,
                    tension: 0.4
                },
                {
                    label: 'Realisasi',
                    data: data.realisasi,
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    borderWidth: 3,
                    pointBackgroundColor: '#10b981',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: true,
                    tension: 0.4
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function (value) {
                            return 'Rp ' + (value / 1000000).toFixed(0) + 'M';
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'bottom'
                },
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            label += 'Rp ' + formatRupiah(context.parsed.y);
                            return label;
                        }
                    }
                }
            }
        }
    });
}

/**
 * Helper Functions
 */
function formatDate(date) {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
}

function truncateText(text, maxLength) {
    if (text.length <= maxLength) return text;
    return text.substring(0, maxLength) + '...';
}

function formatRupiah(number) {
    return new Intl.NumberFormat('id-ID').format(number);
}
