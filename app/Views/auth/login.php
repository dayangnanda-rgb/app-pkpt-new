<!doctype html>
<html lang="id">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk - APP PKPT</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
      :root {
        --glass-bg: rgba(255, 255, 255, 0.1);
        --glass-border: rgba(255, 255, 255, 0.15);
        --primary-blue: #0d6efd;
      }
      
      body, html {
        height: 100%;
        margin: 0;
        font-family: 'Inter', sans-serif;
        overflow: hidden;
      }

      .login-wrapper {
        background: radial-gradient(circle at top left, #1e3a8a, #0f172a);
        height: 100vh;
        width: 100vw;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
      }

      /* Glowing Blobs */
      .blob {
        position: absolute;
        border-radius: 50%;
        filter: blur(80px);
        z-index: 1;
        opacity: 0.6;
      }
      .blob-1 {
        width: 400px;
        height: 400px;
        background: #10b981; /* Greenish */
        top: 20%;
        left: 10%;
      }
      .blob-2 {
        width: 350px;
        height: 350px;
        background: #f43f5e; /* Reddish/Pink */
        bottom: 20%;
        right: 15%;
      }

      .login-card {
        background: var(--glass-bg);
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
        border: 1px solid var(--glass-border);
        border-radius: 24px;
        padding: 40px;
        width: 100%;
        max-width: 420px;
        z-index: 10;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        color: white;
        text-align: center;
      }

      .login-title {
        font-size: 1.75rem;
        font-weight: 700;
        margin-bottom: 5px;
      }

      .login-subtitle {
        font-size: 0.9rem;
        opacity: 0.7;
        margin-bottom: 30px;
      }

      .form-label {
        display: block;
        text-align: left;
        font-weight: 500;
        margin-bottom: 10px;
        font-size: 0.95rem;
      }

      .login-control {
        background: rgba(255, 255, 255, 0.1) !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        color: white !important;
        border-radius: 10px;
        padding: 12px 15px;
        margin-bottom: 20px;
      }
      .login-control::placeholder {
        color: rgba(255, 255, 255, 0.4);
      }
      .login-control:focus {
        box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.25);
        background: rgba(255, 255, 255, 0.15) !important;
      }

      .btn-masuk {
        background-color: var(--primary-blue);
        border: none;
        border-radius: 10px;
        padding: 12px;
        font-weight: 600;
        width: 100%;
        margin-bottom: 25px;
        transition: all 0.2s;
      }
      .btn-masuk:hover {
        background-color: #0b5ed7;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
      }

      .portal-options {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-bottom: 25px;
      }

      .btn-portal {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: rgba(255, 255, 255, 0.8);
        border-radius: 8px;
        padding: 8px 15px;
        font-size: 0.85rem;
        text-decoration: none;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
      }
      .btn-portal:hover {
        background: rgba(255, 255, 255, 0.1);
        color: white;
        border-color: rgba(255, 255, 255, 0.4);
      }

      .footer-text {
        font-size: 0.8rem;
        opacity: 0.5;
        margin-top: 10px;
      }
    </style>
  </head>
  <body>
    <div class="login-wrapper">
      <div class="blob blob-1"></div>
      <div class="blob blob-2"></div>

      <div class="login-card">
        <h1 class="login-title">Login</h1>
        <p class="login-subtitle">APP PKPT Login</p>

        <?php if (session()->getFlashdata('error')): ?>
          <div class="alert alert-danger py-2 px-3 small border-0 mb-3" style="background: rgba(220, 53, 69, 0.2); color: #ff8e98;">
            <?= esc(session()->getFlashdata('error')) ?>
          </div>
        <?php endif; ?>

        <form method="post" action="/login">
            <?= csrf_field() ?>
            <div class="text-start">
              <label for="username_ldap" class="form-label">Username</label>
              <input type="text" class="form-control login-control shadow-none" id="username_ldap" name="username_ldap" required placeholder="Username" value="<?= esc(old('username_ldap')) ?>">
            </div>
            
            <button type="submit" class="btn btn-primary btn-masuk">
                Masuk
            </button>
        </form>

        <div class="portal-options">
          <a href="/login/admin" class="btn-portal">
            <i class="fas fa-shield-alt"></i> Login Administrator
          </a>
          <a href="/login/auditor" class="btn-portal">
            <i class="fas fa-user-tie"></i> Login Auditor
          </a>
        </div>

        <div class="footer-text">
          &copy; <?= date('Y') ?> - Inspektorat Kemenko PMK.
        </div>
      </div>
    </div>
  </body>
</html>
