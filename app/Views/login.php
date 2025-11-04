<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Acceso General - Instituto Superior</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" />
    <style>
        body {
            background: linear-gradient(135deg, #6f42c1, #0d6efd);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        header {
            background: transparent;
            color: white;
            padding: 5rem 0 3rem;
            text-align: center;
        }
        main {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card {
            border-radius: 1.5rem;
            box-shadow: 0 0.5rem 2rem rgba(0,0,0,0.2);
            overflow: hidden;
        }
        .card-header {
            background-color: #0d6efd;
            color: white;
            font-size: 1.25rem;
        }
        .btn-primary {
            background-color: #6f42c1;
            border: none;
            transition: 0.3s;
        }
        .btn-primary:hover {
            background-color: #5a32a3;
        }
        .form-control:focus {
            border-color: #6f42c1;
            box-shadow: 0 0 0 0.25rem rgba(111,66,193,0.25);
        }
        .contact-link {
            color: #6f42c1;
            font-weight: 600;
        }
        .contact-link:hover {
            text-decoration: underline;
        }
        html {
            scroll-behavior: smooth; /* Scroll suave */
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
                            <form action="#" method="post" autocomplete="off">
                                <div class="mb-3">
                                    <label for="login_identifier" class="form-label">Email o Nombre de Usuario</label>
                                    <input type="text" class="form-control" id="login_identifier" name="login_identifier" required>
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Contraseña</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                                <div class="d-grid mt-4">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fas fa-paper-plane me-2"></i>Ingresar
                                    </button>
                                </div>
                                <div class="text-center mt-3">
                                    <p class="mb-0 text-muted">
                                        ¿Tienes problemas para ingresar?
                                        <a href="#contact" class="contact-link">
                                            Contactar al administrador
                                        </a>
                                    </p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Sección de Contacto -->
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
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
