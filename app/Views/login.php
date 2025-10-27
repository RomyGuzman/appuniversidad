<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Acceso General - Instituto Superior</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="<?= base_url('styles.css') ?>" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" />
</head>
<body class="login-page">
    <?= view('templates/Navbar') ?>
    <header class="bg-primary text-white py-5">
        <div class="container text-center">
            <h1 class="display-4 fw-bold mb-4">Portal de Acceso</h1>
            <p class="lead">Ingresa tus credenciales para acceder a tu panel.</p>
        </div>
    </header>

    <main class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white text-center">
                        <h2 class="h5 mb-0"><i class="fas fa-sign-in-alt me-2"></i>Iniciar Sesión</h2>
                    </div>
                    <div class="card-body p-4">
                        <?php if (session()->getFlashdata('error')): ?>
                            <div class="alert alert-danger">
                                <?= session()->getFlashdata('error') ?>
                            </div>
                        <?php endif; ?>
                        <form action="<?= base_url('login') ?>" method="post" autocomplete="off">
                            <?= csrf_field() ?>
                            <div class="mb-3">
                                <label for="login_identifier" class="form-label">Email o Nombre de Usuario</label>
                                <input type="text" class="form-control" id="login_identifier" name="login_identifier" required readonly onfocus="this.removeAttribute('readonly');">
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Contraseña:</label>
                                <input type="password" class="form-control" id="password" name="password" required readonly onfocus="this.removeAttribute('readonly');">
                            </div>
                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-paper-plane me-2"></i>Ingresar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
      // Usamos el evento 'pageshow' que se dispara cada vez que la página se muestra,
        // incluyendo cuando se usa el botón "Atrás" del navegador.
        window.addEventListener('pageshow', function(event) {
            document.getElementById('login_identifier').value = '';
            document.getElementById('password').value = '';
        });
    </script>
</body>
</html>
