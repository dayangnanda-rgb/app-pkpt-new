<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Auditor - APP PKPT</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="/css/app.css" rel="stylesheet">
    <style>
        :root {
            --auditor-primary: #3b82f6;
            --auditor-dark: #0f172a;
            --glass-bg: rgba(255, 255, 255, 0.03);
            --glass-border: rgba(255, 255, 255, 0.1);
        }

        body {
            font-family: 'Inter', sans-serif;
            background: radial-gradient(circle at top left, #0f172a, #020617);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow-x: hidden;
        }

        .login-container {
            width: 100%;
            max-width: 420px;
            padding: 20px;
            position: relative;
            z-index: 10;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 28px;
            padding: 40px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            transition: transform 0.3s ease;
        }

        .login-header {
            text-align: center;
            margin-bottom: 35px;
        }

        .badge-role {
            background: var(--auditor-primary);
            color: #fff;
            font-weight: 700;
            padding: 6px 16px;
            border-radius: 50px;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: inline-block;
            margin-bottom: 15px;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
        }

        .app-name {
            font-size: 2rem;
            font-weight: 800;
            color: #fff;
            margin-bottom: 5px;
            letter-spacing: -0.5px;
        }

        .app-subtitle {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.6);
            font-weight: 400;
        }

        .form-label {
            color: rgba(255, 255, 255, 0.85);
            font-weight: 500;
            font-size: 0.85rem;
            margin-left: 4px;
            margin-bottom: 8px;
        }

        .input-group-custom {
            position: relative;
            margin-bottom: 20px;
        }

        .input-group-custom i {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.4);
            font-size: 1.1rem;
            z-index: 5;
        }

        .form-control-custom {
            background: rgba(255, 255, 255, 0.07);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 14px;
            padding: 14px 14px 14px 50px;
            color: #fff !important;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .form-control-custom:focus {
            background: rgba(255, 255, 255, 0.12);
            border-color: var(--auditor-primary);
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15);
            outline: none;
        }

        .form-control-custom::placeholder {
            color: rgba(255, 255, 255, 0.3);
        }

        .btn-submit {
            background: var(--auditor-primary);
            color: #fff;
            border: none;
            border-radius: 14px;
            padding: 16px;
            font-weight: 700;
            font-size: 0.95rem;
            width: 100%;
            margin-top: 10px;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-submit:hover {
            background: #2563eb;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(59, 130, 246, 0.2);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        .login-footer {
            margin-top: 30px;
            text-align: center;
        }

        .footer-link {
            color: rgba(255, 255, 255, 0.5);
            text-decoration: none;
            font-size: 0.85rem;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .footer-link:hover {
            color: #fff;
        }

        .footer-divider {
            color: rgba(255, 255, 255, 0.2);
            margin: 0 12px;
        }

        .copyright {
            margin-top: 25px;
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.3);
        }

        /* Blobs for background */
        .blob {
            position: absolute;
            width: 300px;
            height: 300px;
            background: var(--auditor-primary);
            filter: blur(100px);
            border-radius: 50%;
            z-index: 0;
            opacity: 0.15;
        }

        .blob-1 { top: -100px; right: -100px; }
        .blob-2 { bottom: -100px; left: -100px; background: #10b981; }

        .alert-custom {
            background: rgba(220, 53, 69, 0.1);
            border: 1px solid rgba(220, 53, 69, 0.2);
            color: #ff8e98;
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 0.85rem;
            margin-bottom: 25px;
        }
    </style>
</head>
<body>
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>

    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="badge-role">Portal Auditor</div>
                <h1 class="app-name">APP PKPT</h1>
                <p class="app-subtitle">Inspektorat Kemenko PMK</p>
            </div>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert-custom">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <?= esc(session()->getFlashdata('error')) ?>
                </div>
            <?php endif; ?>

            <form method="post" action="/login/auditor">
                <?= csrf_field() ?>
                
                <div class="form-group mb-3">
                    <label for="username_ldap" class="form-label">Username</label>
                    <div class="input-group-custom">
                        <i class="fas fa-user-tie"></i>
                        <input type="text" 
                               class="form-control-custom w-100" 
                               id="username_ldap" 
                               name="username_ldap" 
                               required 
                               placeholder="Masukkan username" 
                               value="<?= esc(old('username_ldap')) ?>">
                    </div>
                </div>

                <div class="form-group mb-4">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group-custom">
                        <i class="fas fa-key"></i>
                        <input type="password" 
                               class="form-control-custom w-100" 
                               id="password" 
                               name="password" 
                               required 
                               placeholder="Masukkan password">
                    </div>
                </div>

                <button type="submit" class="btn-submit">
                    Masuk ke Sistem
                </button>
            </form>

            <div class="login-footer">
                <a href="/login" class="footer-link">
                    <i class="fas fa-arrow-left"></i> Kembali ke Utama
                </a>
                <span class="footer-divider">|</span>
                <a href="/login/admin" class="footer-link">
                    Portal Admin <i class="fas fa-arrow-right"></i>
                </a>
                
                <div class="copyright">
                    &copy; <?= date('Y') ?> &bull; Inspektorat Kemenko PMK
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
