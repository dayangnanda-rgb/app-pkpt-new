<li class="nav-item dropdown notification-dropdown">
    <a href="javascript:void(0)" class="nav-link" id="notificationBell">
        <i class="fas fa-bell"></i>
        <?php if ($count > 0): ?>
            <span class="notification-badge"><?= $count ?></span>
        <?php endif; ?>
    </a>
    
    <div class="dropdown-menu notification-menu" id="notificationMenu">
        <div class="dropdown-header">
            <span>Notifikasi</span>
            <span class="badge badge-primary"><?= $count ?> Baru</span>
        </div>
        
        <div class="notification-list">
            <?php if ($count > 0): ?>
                <?php foreach ($notifications as $notif): ?>
                    <a href="<?= base_url('program-kerja/lihat/' . $notif['id']) ?>" class="notification-item unread <?= $notif['notif_type'] ?>" data-id="<?= $notif['id'] ?>">
                        <div class="notif-icon">
                            <?php if ($notif['notif_type'] == 'upcoming'): ?>
                                <i class="fas fa-calendar-alt text-warning"></i>
                            <?php else: ?>
                                <i class="fas fa-edit text-info"></i>
                            <?php endif; ?>
                        </div>
                        <div class="notif-content">
                            <p class="notif-title"><?= esc($notif['nama_kegiatan']) ?></p>
                            <p class="notif-desc">
                                <?php if ($notif['notif_type'] == 'upcoming'): ?>
                                    Kegiatan akan segera dimulai (<?= date('d M', strtotime($notif['tanggal_mulai'])) ?>)
                                <?php else: ?>
                                    Kegiatan selesai. Mohon lengkapi data realisasi.
                                <?php endif; ?>
                            </p>
                        </div>
                        <div class="unread-dot"></div>
                    </a>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="notification-empty">
                    <p>Tidak ada notifikasi baru</p>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="dropdown-footer">
            <a href="<?= base_url('/program-kerja') ?>">Lihat Semua Program</a>
        </div>
    </div>
</li>
