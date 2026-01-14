<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $judul ?? 'PKPT - Kemenko PMK' ?></title>
    
    <!-- CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/program-kerja.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/dashboard.css') ?>">
    
    <!-- Font Google -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="header-content">
                <div class="logo-section">
                    <img src="<?= base_url('assets/images/logo-kemenko-pmk.png') ?>" alt="Logo Kemenko PMK" class="logo-image">
                    <div class="logo-text-wrapper">
                        <h1 class="logo-title">Kementerian Koordinator Bidang<br>Pembangunan Manusia dan Kebudayaan</h1>
                        <p class="logo-subtitle">Republik Indonesia</p>
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
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <?= $this->renderSection('content') ?>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; <?= date('Y') ?> - Kementerian Koordinator Bidang Pembangunan Manusia dan Kebudayaan Republik Indonesia.</p>
        </div>
    </footer>

    <!-- JavaScript -->
    <script src="<?= base_url('assets/js/program-kerja.js') ?>"></script>
    <?= $this->renderSection('scripts') ?>
</body>
</html>
