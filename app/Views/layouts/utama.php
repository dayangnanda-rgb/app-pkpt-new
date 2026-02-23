<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $judul ?? 'PKPT - Kemenko PMK' ?></title>
    <?= csrf_meta() ?>
    
    <!-- CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/program-kerja.css?v=' . time()) ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/footer.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/dashboard.css') ?>">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Hide Scrollbar for Cleaner Look -->
    <style>
        /* Hide scrollbar for Chrome, Safari and Opera */
        ::-webkit-scrollbar {
            display: none;
        }
        /* Hide scrollbar for IE, Edge and Firefox */
        html {
            -ms-overflow-style: none;  /* IE and Edge */
            scrollbar-width: none;  /* Firefox */
        }
    </style>
</head>
<body>
    <!-- Global Toast Notifications -->
    <div class="toast-container">
        <?php if (session()->getFlashdata('sukses')): ?>
            <div class="alert alert-sukses">
                <span class="alert-icon">✓</span>
                <span class="alert-text"><?= session()->getFlashdata('sukses') ?></span>
                <button class="alert-close" onclick="this.parentElement.remove()">×</button>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('gagal') || session()->getFlashdata('error')): ?>
            <div class="alert alert-gagal">
                <span class="alert-icon">✕</span>
                <span class="alert-text"><?= session()->getFlashdata('gagal') ?? session()->getFlashdata('error') ?></span>
                <button class="alert-close" onclick="this.parentElement.remove()">×</button>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-gagal">
                <span class="alert-icon">✕</span>
                <div>
                    <strong>Terjadi kesalahan:</strong>
                    <ul class="error-list" style="margin: 5px 0 0 15px; font-size: 0.85rem;">
                        <?php foreach (session()->getFlashdata('errors') as $error): ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <button class="alert-close" onclick="this.parentElement.remove()">×</button>
            </div>
        <?php endif; ?>
    </div>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="header-content">
                <div class="logo-section">
                    <img src="<?= base_url('assets/images/logo-kemenko-pmk.png') ?>" alt="Logo Kemenko PMK" class="logo-image">
                    <div class="logo-text-wrapper">
                        <h1 class="logo-title">KEMENTERIAN KOORDINATOR BIDANG<br>PEMBANGUNAN MANUSIA DAN KEBUDAYAAN</h1>
                        <p class="logo-subtitle">REPUBLIK INDONESIA</p>
                    </div>
                </div>
                <div class="header-info">
                    <p class="header-ministry">PKPT<br>Program Kerja Pengawasan Tahunan</p>
                </div>
            </div>
        </div>
    </header>

    <!-- Navigation Menu -->
    <nav class="navigation">
        <div class="container">
            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="<?= base_url('/') ?>" class="nav-link <?= (current_url() == base_url('/') || current_url() == base_url('/dashboard')) ? 'active' : '' ?>">
                        <i class="fas fa-home"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('/program-kerja') ?>" class="nav-link <?= (strpos(current_url(), 'program-kerja') !== false) ? 'active' : '' ?>">
                        <i class="fas fa-tasks"></i> Program Kerja
                    </a>
                </li>
                <?= view_cell('App\Cells\NotificationCell::show') ?>
                <li class="nav-item user-info-item" style="display: flex; align-items: center; padding: 0 15px; color: rgba(255, 255, 255, 0.9); border-left: 1px solid rgba(255,255,255,0.2); margin-left: 5px; height: 100%;">
                    <div style="display: flex; align-items: center; gap: 12px; padding: 5px 10px; border-radius: 8px; transition: background 0.2s;">
                        <div style="text-align: right;">
                            <div style="font-weight: 700; font-size: 0.85rem; line-height: 1.1; color: #ffffff; white-space: nowrap;">
                                <?php 
                                    $username = session()->get('user.username_ldap');
                                    $personalName = session()->get('user.pegawai_detail.nama');

                                    if ($personalName): 
                                        echo $personalName;
                                    elseif ($username === 'admin'): 
                                        echo 'ADMINISTRATOR';
                                    elseif ($username === 'auditor'): 
                                        echo 'AUDITOR';
                                    else: 
                                        echo session()->get('user.name') ?? 'User';
                                    endif;
                                ?>
                            </div>
                            <div style="font-size: 0.65rem; color: #fac70b; font-weight: 800; text-transform: uppercase; letter-spacing: 0.8px; margin-top: 3px; opacity: 0.9;">
                                <?= session()->get('role') ?>
                            </div>
                        </div>
                        <div style="width: 32px; height: 32px; background: rgba(255,255,255,0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 1px solid rgba(255,255,255,0.1);">
                            <?php 
                                $role = session()->get('role');
                                $icon = 'fa-user';
                                if ($role === 'admin') $icon = 'fa-user-shield';
                                elseif ($role === 'auditor') $icon = 'fa-user-check';
                            ?>
                            <i class="fas <?= $icon ?>" style="font-size: 1rem; color: rgba(255, 255, 255, 0.8);"></i>
                        </div>
                    </div>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('/logout') ?>" class="nav-link logout-link" style="padding: 15px 20px; color: #ff6b6b; font-size: 1.2rem;" title="Logout">
                        <i class="fas fa-power-off"></i>
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <?= $this->renderSection('content') ?>
        </div>
    </main>

    <!-- Announcement Popover -->
    <div id="announcementPopover" class="announcement-popover">
        <div class="popover-content">
            <div class="popover-header">
                <i class="fas fa-bullhorn animated-bell"></i>
                <h2>Pengumuman Penting!</h2>
                <button id="closeAnnouncement" class="btn-close-popover">&times;</button>
            </div>
            <div class="popover-body text-center">
                <?php 
                $userRole = session()->get('role');
                if ($userRole === 'admin'): ?>
                    <p>Selamat datang, <strong>Administrator</strong>. Pantau konsistensi data antara Rencana dan Realisasi pada Dashboard dan pastikan seluruh pelaporan unit kerja berjalan sesuai jadwal.</p>
                <?php elseif ($userRole === 'auditor'): ?>
                    <p>Selamat datang, <strong>Auditor</strong>. Mohon segera melakukan review pada kegiatan dengan status 'Terlaksana'. Kecepatan validasi Anda menentukan keakuratan capaian kinerja kementerian.</p>
                <?php else: ?>
                    <p>Selamat datang, <strong>Pelaksana</strong>. Mohon segera mengunggah dokumen bukti realisasi untuk kegiatan yang telah selesai. Pastikan data realisasi anggaran akurat dan sesuai kuitansi.</p>
                <?php endif; ?>
                
                <div class="popover-action">
                    <a href="<?= base_url('program-kerja') ?>" class="btn-check-data-small">
                        Cek Program Kerja
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <!-- Column 1: Branding -->
                <div class="footer-branding">
                    <div class="logo-section">
                        <img src="<?= base_url('assets/images/logo-kemenko-pmk.png') ?>" alt="Logo Kemenko PMK" class="logo-image-footer">
                        <div class="logo-text-wrapper">
                            <h1 class="logo-title">KEMENTERIAN KOORDINATOR BIDANG<br>PEMBANGUNAN MANUSIA DAN KEBUDAYAAN</h1>
                            <p class="logo-subtitle">REPUBLIK INDONESIA</p>
                        </div>
                    </div>
                    <p class="footer-description">Aplikasi Program Kerja Pengawasan Tahunan (PKPT) untuk mendukung tata kelola pemerintahan yang transparan dan akuntabel di lingkungan Kemenko PMK.</p>
                </div>

                <!-- Column 2: Quick Links -->
                <div class="footer-links">
                    <h3>Tautan Langsung</h3>
                    <ul class="footer-menu">
                        <li><a href="<?= base_url('/') ?>"><i class="fas fa-chevron-right"></i> Dashboard</a></li>
                        <li><a href="<?= base_url('/program-kerja') ?>"><i class="fas fa-chevron-right"></i> Program Kerja</a></li>
                        <li><a href="https://www.kemenkopmk.go.id" target="_blank"><i class="fas fa-chevron-right"></i> Website Kemenko PMK</a></li>
                    </ul>
                </div>

                <!-- Column 3: Contact -->
                <div class="footer-contact">
                    <h3>Hubungi Kami</h3>
                    <div class="contact-info">
                        <p><i class="fas fa-map-marker-alt"></i> <span>Jl. Medan Merdeka Barat No. 3. Jakarta Pusat</span></p>
                        <p><i class="fas fa-phone"></i> <span>(+62) 21 345 9444</span></p>
                        <p><i class="fas fa-envelope"></i> <span>Informasi umum: roinfohumas@kemenkopmk.go.id</span></p>
                        <p><i class="fas fa-envelope"></i> <span>Persuratan: kearsipan@kemenkopmk.go.id</span></p>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; <?= date('Y') ?> Kementerian Koordinator Bidang Pembangunan Manusia dan Kebudayaan. <span class="footer-rights">All rights reserved.</span></p>
            </div>
        </div>
    </footer>
    
    <!-- Policy Modal -->
    <div id="policyModal" class="modal-overlay">
        <div class="modal-content-wrapper policy-modal-wrapper">
            <div class="modal-header-policy">
                <i class="fas fa-shield-halved animated-icon"></i>
                <h2>KETENTUAN & KEBIJAKAN:<br>KOMITMEN PENUGASAN PKPT</h2>
            </div>
            <div class="modal-body-policy">
                <div class="policy-alert-box">
                    <?php 
                    $userRole = session()->get('role');
                    if ($userRole === 'admin'): ?>
                        <div style="margin-bottom: 15px; padding-bottom: 15px; border-bottom: 2px dashed #e2e8f0; color: #1e293b;">
                            <span style="background: #1e293b; color: #fff; padding: 2px 10px; border-radius: 4px; font-size: 0.75rem; font-weight: 700; margin-bottom: 10px; display: inline-block;">HAK AKSES: ADMINISTRATOR</span>
                            <p>Sebagai <strong>Administrator</strong>, Anda bertanggung jawab atas pengawasan integritas data seluruh kementerian. Pastikan target minimal pimpinan tetap terjaga dan kelola setiap usulan revisi dengan ketat sesuai regulasi yang berlaku.</p>
                        </div>
                    <?php elseif ($userRole === 'auditor'): ?>
                        <div style="margin-bottom: 15px; padding-bottom: 15px; border-bottom: 2px dashed #e2e8f0; color: #1e293b;">
                            <span style="background: #1e293b; color: #fff; padding: 2px 10px; border-radius: 4px; font-size: 0.75rem; font-weight: 700; margin-bottom: 10px; display: inline-block;">HAK AKSES: AUDITOR / PEMERIKSA</span>
                            <p>Sebagai <strong>Auditor</strong>, tugas Anda adalah melakukan <strong>Review & Validasi</strong>. Berikan persetujuan hanya pada kegiatan yang telah memenuhi standar kualitas dan selaras dengan <strong>Komitmen Baku</strong> yang telah ditetapkan pimpinan.</p>
                        </div>
                    <?php else: ?>
                        <div style="margin-bottom: 15px; padding-bottom: 15px; border-bottom: 2px dashed #e2e8f0; color: #1e293b;">
                            <span style="background: #1e293b; color: #fff; padding: 2px 10px; border-radius: 4px; font-size: 0.75rem; font-weight: 700; margin-bottom: 10px; display: inline-block;">HAK AKSES: PELAKSANA</span>
                            <p>Sebagai <strong>Pelaksana</strong>, Anda wajib melaporkan realisasi tepat waktu. Data rencana merupakan <strong>Target Minimal</strong> yang tidak diperkenankan diubah secara sepihak tanpa melalui mekanisme <strong>Mekanisme Revisi Resmi</strong>.</p>
                        </div>
                    <?php endif; ?>

                    <p style="font-size: 0.9rem; color: #64748b; font-style: italic; margin-top: 10px;">
                        <i class="fas fa-info-circle mr-1"></i> Seluruh penyesuaian data wajib menyertakan otorisasi dari <strong>Inspektur</strong> demi menjaga akurasi pelaporan kinerja tahunan.
                    </p>
                </div>
            </div>
            <div class="modal-footer-policy">
                <button id="confirmPolicy" class="btn-confirm-policy">
                    <i class="fas fa-check-double"></i> Saya Memahami & Menyetujui Ketentuan
                </button>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="<?= base_url('assets/js/program-kerja.js?v=' . time()) ?>"></script>
    <?= $this->renderSection('scripts') ?>
</body>
</html>
