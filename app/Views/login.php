<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Acceso General - Instituto Superior</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" />
</head>
<body>
    <!-- Navbar simplificado para login -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top" id="mainNav" style="background-color: #000 !important;">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center text-white" href="<?= base_url(); ?>" style="color: white !important;">
                <i class="fas fa-university me-2"></i>
                <span>Instituto Superior 57</span>
            </a>
        </div>
    </nav>

    <style>
        body {
            background: var(--gradient-hero);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        header {
            background: #000;
            color: white;
            padding: 3rem 0 2rem;
            text-align: center;
        }
        main {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .contact-link {
            color: var(--secondary-color);
            font-weight: 600;
        }
        .contact-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <h1 class="display-4 fw-bold mb-3">Portal de Acceso</h1>
            <p class="lead">Ingresa tus credenciales para acceder a tu panel.</p>
        </div>
    </header>

    <main>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5 col-md-7">
                    <div class="card">
                        <div class="card-header text-center">
                            <i class="fas fa-sign-in-alt me-2"></i>Iniciar Sesión
                        </div>
                        <div class="card-body p-4">
                            <form action="<?= base_url('login') ?>" method="post" autocomplete="off">
                                <div class="mb-3">
                                    <label for="login_identifier" class="form-label">Email o Nombre de Usuario</label>
                                    <input type="text" class="form-control" id="login_identifier" name="login_identifier" value="" autocomplete="new-password" required>
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Contraseña</label>
                                    <input type="password" class="form-control" id="password" name="password" value="" autocomplete="new-password" required>
                                </div>
                                <?= csrf_field() ?>
                                <div class="d-grid mt-4">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fas fa-paper-plane me-2"></i>Ingresar
                                    </button>
                                </div>
                                <div class="text-center mt-3">
                                    <p class="mb-0 text-muted">
                                        ¿Tienes problemas para ingresar?
                                        <button type="button" class="btn btn-link contact-link p-0" data-bs-toggle="modal" data-bs-target="#modalConsultaLogin">
                                            Contactar al administrador
                                        </button>
                                    </p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Sección de Contacto
    <section id="contact" class="py-5 bg-light">
        <div class="container">
            <h2 class="display-5 fw-bold text-center mb-5 text-primary">Contáctanos</h2>
            <div class="row">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <h4 class="fw-bold mb-3">Envíanos un Mensaje</h4>
                    <form>
                        <div class="mb-3">
                            <label for="contactName" class="form-label">Nombre Completo</label>
                            <input type="text" class="form-control" id="contactName" placeholder="Tu Nombre">
                        </div>
                        <div class="mb-3">
                            <label for="contactEmail" class="form-label">Correo Electrónico</label>
                            <input type="email" class="form-control" id="contactEmail" placeholder="tu.email@ejemplo.com">
                        </div>
                        <div class="mb-3">
                            <label for="contactSubject" class="form-label">Asunto</label>
                            <input type="text" class="form-control" id="contactSubject" placeholder="Motivo de tu consulta">
                        </div>
                        <div class="mb-3">
                            <label for="contactMessage" class="form-label">Mensaje</label>
                            <textarea class="form-control" id="contactMessage" rows="5" placeholder="Escribe tu mensaje aquí..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg">Enviar Mensaje</button>
                    </form>
                </div>
                <div class="col-lg-6">
                    <h4 class="fw-bold mb-3">Encuéntranos</h4>
                    <p class="text-muted"><i class="fas fa-map-marker-alt me-2"></i> Calle Ficticia 123, Ciudad Ejemplo, Provincia XYZ</p>
                    <p class="text-muted"><i class="fas fa-phone me-2"></i> +54 9 11 1234-5678</p>
                    <p class="text-muted"><i class="fas fa-envelope me-2"></i> info@tuinstituto.com</p>
                    <div class="map-container mb-4 rounded shadow-sm">
                        <iframe src="https://www.google.com/maps/embed?pb=!4v1748301775086!6m8!1m7!1sSdL67OYbaIRBjxlWJP07cw!2m2!1d-35.57365481103263!2d-58.01375203290051!3f351.2763313649954!4f-8.335368344541308!5f0.7820865974627469" width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                    <div class="social-links text-center text-lg-start">
                        <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section> -->

    <!-- Modal para Contactar al Administrador (Login) -->
    <div class="modal fade" id="modalConsultaLogin" tabindex="-1" aria-labelledby="modalConsultaLoginLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalConsultaLoginLabel"><i class="fas fa-envelope-open-text me-2"></i>Enviar Consulta al Administrador</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formConsultaLogin" action="<?= base_url('consultas/enviar') ?>" method="post">
                    <?= csrf_field() ?>
                    <input type="hidden" name="tipo_usuario" value="estudiante">
                    <div class="modal-body">
                        <div id="consultaLoginErrors" class="alert alert-danger d-none"></div>
                        <div class="mb-3">
                            <label for="emailLogin" class="form-label">Email</label>
                            <input type="email" class="form-control" id="emailLogin" name="email" placeholder="tuemail@ejemplo.com" required>
                        </div>
                        <div class="mb-3">
                            <label for="asuntoLogin" class="form-label">Asunto</label>
                            <input type="text" class="form-control" id="asuntoLogin" name="asunto" placeholder="Ej: Problema con el login" required>
                        </div>
                        <div class="mb-3">
                            <label for="mensajeLogin" class="form-label">Mensaje</label>
                            <textarea class="form-control" id="mensajeLogin" name="mensaje" rows="5" placeholder="Describe tu consulta aquí..." required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane me-2"></i>Enviar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    // Limpiar campos del formulario al cargar la página
    window.onload = function() {
        setTimeout(function() {
            document.getElementById('login_identifier').value = '';
            document.getElementById('password').value = '';
        }, 100);
    };
    </script>
    <script>
    // Manejar el envío del formulario de consulta en login
    document.getElementById('formConsultaLogin').addEventListener('submit', function(e) {
        e.preventDefault();

        const form = this;
        const formData = new FormData(form);

        fetch('<?= base_url('consultas/enviar') ?>', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Cerrar modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('modalConsultaLogin'));
                modal.hide();

                // Mostrar mensaje de éxito
                Swal.fire({
                    icon: 'success',
                    title: 'Enviado con éxito',
                    text: 'A la brevedad recibirás la respuesta',
                    confirmButtonText: 'Aceptar'
                });

                // Limpiar formulario
                form.reset();
            } else {
                // Mostrar errores de validación
                if (data.errors) {
                    const errorDiv = document.getElementById('consultaLoginErrors');
                    let errorHtml = '<ul class="mb-0">';
                    for (const [field, message] of Object.entries(data.errors)) {
                        errorHtml += `<li>${message}</li>`;
                    }
                    errorHtml += '</ul>';
                    errorDiv.innerHTML = errorHtml;
                    errorDiv.classList.remove('d-none');
                } else {
                    // Mostrar mensaje de error general
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Error al enviar la consulta',
                        confirmButtonText: 'Aceptar'
                    });
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error al enviar la consulta. Inténtalo de nuevo.',
                confirmButtonText: 'Aceptar'
            });
        });
    });
    </script>
</body>
</html>
