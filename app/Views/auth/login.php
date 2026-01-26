<!doctype html>
<html lang="id">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk - APP PKPT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/app.css" rel="stylesheet">
  </head>
  <body class="login-bg">
    <div class="shape shape-1"></div>
    <div class="shape shape-2"></div>
    <div class="card card-login shadow-lg p-4 mx-3">
      <div class="mb-3 text-center">
        <div class="title h5 mb-1">Login</div>
        <div class="muted small">APP PKPT Login</div>
      </div>
      <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger mb-3"><?= esc(session()->getFlashdata('error')) ?></div>
      <?php endif; ?>
      <form method="post" action="/login" class="d-grid gap-3">
        <div>

          <label for="username_ldap" class="form-label label">Username</label>
          <input type="text" class="form-control login-control" id="username_ldap" name="username_ldap" required placeholder="Username" value="<?= esc(old('username_ldap')) ?>">

        </div>
        <button type="submit" class="btn btn-primary w-100">Masuk</button>
      </form>
      <div class="text-center mt-3 muted small">&copy; <?= date('Y') ?> - Inspektorat Kemenko PMK.</div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>
