/**
 * JavaScript: Program Kerja PKPT
 * 
 * Script untuk interaksi dan validasi form
 * Program Kerja Pengawasan Tahunan (PKPT)
 * 
 * @author  PKPT Development Team
 * @created 2026-01-08
 */

document.addEventListener('DOMContentLoaded', function () {

    /**
     * Format input angka dengan pemisah ribuan
     */
    const anggaranInputs = document.querySelectorAll('input[name="anggaran"], input[name="realisasi_anggaran"]');

    anggaranInputs.forEach(input => {
        input.addEventListener('blur', function () {
            if (this.value) {
                // Format dengan pemisah ribuan saat blur
                const value = parseFloat(this.value.replace(/,/g, ''));
                if (!isNaN(value)) {
                    this.value = value;
                }
            }
        });
    });

    /**
     * Validasi file upload
     */
    const fileInput = document.querySelector('input[name="dokumen_output"]');

    if (fileInput) {
        fileInput.addEventListener('change', function () {
            const file = this.files[0];

            if (file) {
                // Validasi ukuran file (maksimal 5MB)
                const maxSize = 5 * 1024 * 1024; // 5MB dalam bytes

                if (file.size > maxSize) {
                    alert('Ukuran file terlalu besar! Maksimal 5MB.');
                    this.value = '';
                    return;
                }

                // Validasi tipe file
                const allowedTypes = [
                    'application/pdf',
                    'application/msword',
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                    'application/vnd.ms-excel',
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                ];

                if (!allowedTypes.includes(file.type)) {
                    alert('Format file tidak didukung! Gunakan PDF, Word, atau Excel.');
                    this.value = '';
                    return;
                }

                console.log('File valid:', file.name);
            }
        });
    }

    /**
     * Konfirmasi sebelum submit form
     */
    const forms = document.querySelectorAll('form.form');

    forms.forEach(form => {
        form.addEventListener('submit', function (e) {
            const namaKegiatan = this.querySelector('input[name="nama_kegiatan"]');

            if (namaKegiatan && !namaKegiatan.value.trim()) {
                e.preventDefault();
                alert('Nama kegiatan harus diisi!');
                namaKegiatan.focus();
                return false;
            }
        });
    });

    /**
     * Auto-hide alerts
     */
    const alerts = document.querySelectorAll('.alert');

    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => {
                alert.remove();
            }, 300);
        }, 5000);
    });

    /**
     * Smooth scroll untuk navigasi
     */
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const href = this.getAttribute('href');
            if (href !== '#') {
                e.preventDefault();
                const target = document.querySelector(href);
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            }
        });
    });

    /**
     * Highlight baris tabel saat diklik
     */
    const tableRows = document.querySelectorAll('.table tbody tr');

    tableRows.forEach(row => {
        row.addEventListener('click', function (e) {
            // Jangan highlight jika klik button/link
            if (e.target.tagName === 'BUTTON' || e.target.tagName === 'A') {
                return;
            }

            // Remove highlight dari semua baris
            tableRows.forEach(r => r.style.backgroundColor = '');

            // Highlight baris yang diklik
            this.style.backgroundColor = 'var(--bg-tertiary)';
        });
    });

    /**
     * Character counter untuk textarea
     */
    const textareas = document.querySelectorAll('textarea');

    textareas.forEach(textarea => {
        const maxLength = textarea.getAttribute('maxlength');

        if (maxLength) {
            const counter = document.createElement('small');
            counter.className = 'form-help';
            counter.style.float = 'right';

            const updateCounter = () => {
                const remaining = maxLength - textarea.value.length;
                counter.textContent = `${remaining} karakter tersisa`;

                if (remaining < 50) {
                    counter.style.color = 'var(--danger)';
                } else {
                    counter.style.color = 'var(--text-muted)';
                }
            };

            textarea.addEventListener('input', updateCounter);
            textarea.parentElement.appendChild(counter);
            updateCounter();
        }
    });

    /**
     * Print functionality (untuk masa depan)
     */
    window.printTable = function () {
        window.print();
    };

    /**
     * Export functionality placeholder (untuk masa depan)
     */
    window.exportData = function (format) {
        console.log('Export data dalam format:', format);
        alert('Fitur export akan segera tersedia!');
    };

    /**
     * Notification Dropdown Toggle & Read Status
     */
    const notificationBell = document.getElementById('notificationBell');
    const notificationMenu = document.getElementById('notificationMenu');
    const notificationItems = document.querySelectorAll('.notification-item');

    // Load read status from localStorage
    let readNotifications = JSON.parse(localStorage.getItem('readNotifications') || '[]');

    // Apply read status on load
    notificationItems.forEach(item => {
        const id = item.dataset.id;
        if (readNotifications.includes(id)) {
            item.classList.remove('unread');
            item.classList.add('read');
        }

        // Handle click on notification item
        item.addEventListener('click', function (e) {
            const id = this.dataset.id;
            if (!readNotifications.includes(id)) {
                readNotifications.push(id);
                localStorage.setItem('readNotifications', JSON.stringify(readNotifications));

                this.classList.remove('unread');
                this.classList.add('read');

                // Update badge count
                updateNotificationBadge();
            }
        });
    });

    // Update count on load
    updateNotificationBadge();

    function updateNotificationBadge() {
        const badge = document.querySelector('.notification-badge');
        const headerBadge = document.querySelector('.notification-menu .dropdown-header .badge');

        const unreadItems = document.querySelectorAll('.notification-item.unread');
        const unreadCount = unreadItems.length;

        if (badge) {
            if (unreadCount > 0) {
                badge.textContent = unreadCount;
            } else {
                badge.style.display = 'none';
            }
        }

        if (headerBadge) {
            headerBadge.textContent = `${unreadCount} Baru`;
            if (unreadCount === 0) {
                headerBadge.classList.replace('badge-primary', 'badge-secondary');
            }
        }
    }

    if (notificationBell && notificationMenu) {
        notificationBell.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            notificationMenu.classList.toggle('show');
        });

        // Tutup dropdown saat klik di luar
        document.addEventListener('click', function (e) {
            if (!notificationMenu.contains(e.target) && !notificationBell.contains(e.target)) {
                notificationMenu.classList.remove('show');
            }
        });
    }

    /**
     * Welcome Announcement Popover Logic
     */
    const announcementPopover = document.getElementById('announcementPopover');
    const closeAnnouncement = document.getElementById('closeAnnouncement');
    const announcementPulseDot = document.getElementById('announcementPulseDot');
    // notificationBell is already declared elsewhere in the file

    if (announcementPopover && closeAnnouncement) {
        // Updated session key for testing
        const isShown = sessionStorage.getItem('pkpt_announcement_v4_shown');

        if (!isShown) {
            setTimeout(() => {
                announcementPopover.classList.add('show');
                if (announcementPulseDot) announcementPulseDot.classList.add('show');
            }, 800);
        }

        closeAnnouncement.addEventListener('click', function () {
            announcementPopover.classList.remove('show');
            sessionStorage.setItem('pkpt_announcement_v4_shown', 'true');
            if (announcementPulseDot) announcementPulseDot.classList.remove('show');
        });

        // Toggle popover from Bell if it's still unacknowledged
        if (notificationBell) {
            notificationBell.addEventListener('click', function (e) {
                if (announcementPulseDot && announcementPulseDot.classList.contains('show')) {
                    e.preventDefault();
                    e.stopPropagation();
                    announcementPopover.classList.add('show');
                    notificationMenu.classList.remove('show'); // Hide main menu if showing popover
                }
            });
        }
    }

    /**
     * Recurring Policy Modal Logic
     * Restriction: Only show on the initial "Program Kerja" listing page
     */
    const policyModal = document.getElementById('policyModal');
    const confirmPolicy = document.getElementById('confirmPolicy');

    if (policyModal) {
        // Show only on the main listing page (ends with /program-kerja or /program-kerja/)
        const path = window.location.pathname;
        const isMainListing = path.endsWith('/program-kerja') || path.endsWith('/program-kerja/');

        if (isMainListing) {
            setTimeout(() => {
                policyModal.classList.add('show');
            }, 500);
        }

        // Close logic
        if (confirmPolicy) {
            confirmPolicy.addEventListener('click', function () {
                policyModal.classList.remove('show');
            });
        }

        // Close on overlay click
        policyModal.addEventListener('click', function (e) {
            if (e.target === policyModal) {
                policyModal.classList.remove('show');
            }
        });
    }

});

/**
 * Fungsi helper: Format angka ke Rupiah
 */
function formatRupiah(angka) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(angka);
}

/**
 * Fungsi helper: Format tanggal ke format Indonesia
 */
function formatTanggal(tanggal) {
    return new Intl.DateTimeFormat('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    }).format(new Date(tanggal));
}
