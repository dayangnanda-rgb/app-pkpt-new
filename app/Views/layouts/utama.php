<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $judul ?? 'PKPT - Kemenko PMK' ?></title>
    
    <!-- CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/program-kerja.css') ?>">
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
                <li class="nav-item">
                    <a href="<?= base_url('/logout') ?>" class="nav-link">
                        <i class="fas fa-sign-out-alt"></i> Logout
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

    <!-- Announcement Modal -->
    <div id="announcementModal" class="modal-overlay">
        <div class="modal-content-wrapper">
            <div class="modal-header-announcement">
                <i class="fas fa-bullhorn animated-bell"></i>
                <h2>Pengumuman Penting!</h2>
            </div>
            <div class="modal-body-announcement text-center">
                <p>Selamat datang di Aplikasi PKPT.</p>
                <p>Terdapat beberapa kegiatan yang akan segera dilaksanakan dalam waktu dekat. 
                   Mohon untuk mengecek kembali kesiapan data dan rencana kegiatannya.</p>
                
                <div class="announcement-action">
                    <a href="<?= base_url('program-kerja') ?>" class="btn-check-data">
                        <i class="fas fa-arrow-right"></i> Cek Data Program Kerja
                    </a>
                </div>
            </div>
            <div class="modal-footer-announcement">
                <button id="closeAnnouncement" class="btn-close-announcement">Saya Mengerti</button>
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

    <!-- JavaScript -->
    <script src="<?= base_url('assets/js/program-kerja.js') ?>"></script>
    <?= $this->renderSection('scripts') ?>
</body>
</html>
