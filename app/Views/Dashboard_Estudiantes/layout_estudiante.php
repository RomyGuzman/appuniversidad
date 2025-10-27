<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->renderSection('title') ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
  <div class="container">
    <a class="navbar-brand" href="<?= base_url('/') ?>">
        <i class="fas fa-university me-2"></i>Instituto Superior 57
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavEstudiante" aria-controls="navbarNavEstudiante" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavEstudiante">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="<?= base_url('estudiantes/dashboard') ?>">Mi Dashboard</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-danger fw-bold logout-btn" href="<?= base_url('logout') ?>">Cerrar Sesión</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<main class="container py-4">
    <?= $this->renderSection('content') ?>
</main>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Pasa la configuración de PHP a JavaScript para que app.js pueda usarla
    window.APP_CONFIG = {
        baseUrl: "<?= base_url('/') ?>",
        flash: {
            success: "<?= session()->getFlashdata('success') ?>",
            error: "<?= session()->getFlashdata('error') ?>"
        }
    };
</script>
<script src="<?= base_url('app.js'); ?>"></script>
<?= $this->renderSection('scripts') ?>
</body>
</html>
