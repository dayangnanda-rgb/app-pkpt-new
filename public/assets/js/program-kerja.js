/**
 * JavaScript: Program Kerja PKPT
 * 
 * Script untuk interaksi dan validasi form
 * Program Kerja Pengawasan Tahunan (PKPT)
 * 
 * @author  PKPT Development Team
 * @created 2026-01-08
 */

document.addEventListener('DOMContentLoaded', function() {
    
    /**
     * Format input angka dengan pemisah ribuan
     */
    const anggaranInputs = document.querySelectorAll('input[name="anggaran"], input[name="realisasi_anggaran"]');
    
    anggaranInputs.forEach(input => {
        input.addEventListener('blur', function() {
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
        fileInput.addEventListener('change', function() {
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
        form.addEventListener('submit', function(e) {
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
        anchor.addEventListener('click', function(e) {
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
        row.addEventListener('click', function(e) {
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
    window.printTable = function() {
        window.print();
    };

    /**
     * Export functionality placeholder (untuk masa depan)
     */
    window.exportData = function(format) {
        console.log('Export data dalam format:', format);
        alert('Fitur export akan segera tersedia!');
    };

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
