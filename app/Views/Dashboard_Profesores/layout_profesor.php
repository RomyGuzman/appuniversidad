<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->renderSection('title') ?> | Panel Profesor</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- FullCalendar CSS -->
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/main.min.css' rel='stylesheet' />

    <!-- Estilos personalizados -->
    <style>
        body {
            background-color: #f0f2f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .navbar {
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
        }
        .navbar-brand img {
            max-height: 40px;
        }
        .main-content {
            padding-top: 2rem;
            padding-bottom: 2rem;
        }
        .card {
            border: none;
            border-radius: .75rem;
            box-shadow: 0 4px 6px rgba(0,0,0,.05);
        }
        .accordion-button:not(.collapsed) {
            color: #fff;
            background-color: #0d6efd;
        }
        .accordion-button:not(.collapsed)::after {
            filter: brightness(0) invert(1);
        }
        .accordion-item {
            border-radius: .75rem;
            overflow: hidden;
            border: none;
            box-shadow: 0 4px 6px rgba(0,0,0,.05);
        }
        .nav-tabs .nav-link {
            color: #6c757d;
        }
        .nav-tabs .nav-link.active {
            color: #0d6efd;
            border-color: #dee2e6 #dee2e6 #fff;
            font-weight: bold;
        }
    </style>
    <link rel="stylesheet" href="<?= base_url('styles.css') ?>" />
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
  <div class="container">
    <a class="navbar-brand" href="<?= base_url('profesores/carreras') ?>">
        <i class="fas fa-university me-2"></i>Instituto Superior 57
    </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#" id="profile-toggle"><i class="fas fa-user me-1"></i>Perfil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('profesores/carreras') ?>"><i class="fas fa-chalkboard-teacher me-1"></i>Mis Materias</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('logout') ?>" id="logout-button"><i class="fas fa-sign-out-alt me-1"></i>Salir</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<main class="container py-4">
    <?= $this->renderSection('content') ?>
</main>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6 mb-3 mb-md-0">
                    <h5 class="mb-3">Instituto Superior 57</h5>
                    <p class="mb-2"><i class="fas fa-map-marker-alt me-2"></i>Av. Siempre Viva 742, Springfield</p>
                    <p class="mb-2"><i class="fas fa-phone me-2"></i>+54 11 3456-7890</p>
                    <p class="mb-0"><i class="fas fa-envelope me-2"></i>info@instituto57.edu.ar</p>
                </div>
                <div class="col-md-3 mb-3 mb-md-0">
                    <h5 class="mb-3">Enlaces rápidos</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="<?= base_url(); ?>" class="text-white-50 text-decoration-none">Inicio</a></li>
                        <li class="mb-2"><a href="<?= base_url(); ?>#about" class="text-white-50 text-decoration-none">Quiénes Somos</a></li>
                        <li class="mb-2"><a href="<?= base_url(); ?>#careers" class="text-white-50 text-decoration-none">Carreras</a></li>
                        <li><a href="<?= base_url(); ?>#contact" class="text-white-50 text-decoration-none">Contacto</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5 class="mb-3">Seguinos</h5>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-white-50"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-white-50"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-white-50"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-white-50"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#" class="text-white-50"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
            </div>
            <hr class="my-4">
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-0 small">&copy; 2023 Instituto Superior 57. Todos los derechos reservados.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0 small"><a href="#" class="text-white-50 text-decoration-none">Política de privacidad</a> | <a href="#" class="text-white-50 text-decoration-none">Términos y condiciones</a></p>
                </div>
            </div>
        </div>
    </footer>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/main.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar/locales/es.js'></script>

<script src="<?= base_url('app.js') ?>"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const profileToggle = document.getElementById('profile-toggle');
    const profileAccordion = document.getElementById('profileAccordion');

    if (profileToggle && profileAccordion) {
        profileToggle.addEventListener('click', function(e) {
            e.preventDefault();
            if (profileAccordion.style.display === 'none' || profileAccordion.style.display === '') {
                profileAccordion.style.display = 'block';
                // Trigger the collapse to show
                const collapseElement = profileAccordion.querySelector('#collapseProfile');
                if (collapseElement) {
                    const bsCollapse = new bootstrap.Collapse(collapseElement, {
                        show: true
                    });
                }
            } else {
                profileAccordion.style.display = 'none';
            }
        });
    }
});
</script>

<?= $this->renderSection('scripts') ?>

</body>
</html>