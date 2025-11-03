<?php include APPPATH . 'Views/templates/oferta_academica.php'; ?>

        <section id="student-life" class="py-5 bg-secondary-subtle">
            <div class="container">
                <h2 class="display-5 fw-bold text-center mb-5 text-secondary">Vida Estudiantil</h2>
                <p class="text-center lead mb-5 text-muted">Más allá del aula, un ambiente vibrante para crecer, conectar y prosperar.</p>
                <div class="row g-4">
                    <div class="col-lg-4 col-md-6">
                        <div class="card h-100 shadow-sm border-0 student-life-card">
                            <div class="card-body text-center">
                                <i class="fas fa-handshake fa-3x mb-3 text-info"></i>
                                <h5 class="card-title fw-bold text-secondary">Clubes y Organizaciones</h5>
                                <p class="card-text text-muted">Únete a grupos de interés, desarrolla tus pasiones y haz nuevos amigos.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="card h-100 shadow-sm border-0 student-life-card">
                            <div class="card-body text-center">
                                <i class="fas fa-laptop-code fa-3x mb-3 text-info"></i>
                                <h5 class="card-title fw-bold text-secondary">Talleres y Seminarios</h5>
                                <p class="card-text text-muted">Amplía tus conocimientos con actividades extracurriculares y profesionales.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="card h-100 shadow-sm border-0 student-life-card">
                            <div class="card-body text-center">
                                <i class="fas fa-dumbbell fa-3x mb-3 text-info"></i>
                                <h5 class="card-title fw-bold text-secondary">Deportes y Actividades Físicas</h5>
                                <p class="card-text text-muted">Mantente activo y forma parte de nuestros equipos deportivos.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="card h-100 shadow-sm border-0 student-life-card">
                            <div class="card-body text-center">
                                <i class="fas fa-users-cog fa-3x mb-3 text-info"></i>
                                <h5 class="card-title fw-bold text-secondary">Servicios de Apoyo al Estudiante</h5>
                                <p class="card-text text-muted">Accede a tutorías, orientación vocacional y apoyo psicológico.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="card h-100 shadow-sm border-0 student-life-card">
                            <div class="card-body text-center">
                                <i class="fas fa-briefcase fa-3x mb-3 text-info"></i>
                                <h5 class="card-title fw-bold text-secondary">Bolsa de Empleo y Prácticas</h5>
                                <p class="card-text text-muted">Conectamos tu talento con oportunidades laborales y de pasantías.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="card h-100 shadow-sm border-0 student-life-card">
                            <div class="card-body text-center">
                                <i class="fas fa-globe fa-3x mb-3 text-info"></i>
                                <h5 class="card-title fw-bold text-secondary">Intercambios Internacionales</h5>
                                <p class="card-text text-muted">Vive una experiencia global y enriquece tu formación académica.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="students-access" class="py-5 bg-primary-subtle d-none">
            <div class="container">
                <h2 class="display-5 fw-bold text-center mb-5 text-primary">Portal de Alumnos</h2>
                <p class="text-center lead mb-5 text-muted">Accede a tus calificaciones, material de estudio y calendario académico.</p>
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="card h-100 shadow-sm border-0 student-portal-card">
                            <div class="card-body text-center">
                                <i class="fas fa-graduation-cap fa-3x mb-3 text-primary"></i>
                                <h5 class="card-title text-secondary fw-bold">Mis Calificaciones</h5>
                                <p class="card-text text-muted">Consulta tu historial académico y el progreso de tus cursos.</p>
                                <a href="#" class="btn btn-outline-primary btn-sm mt-2">Ver Calificaciones</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card h-100 shadow-sm border-0 student-portal-card">
                            <div class="card-body text-center">
                                <i class="fas fa-book-open fa-3x mb-3 text-primary"></i>
                                <h5 class="card-title fw-bold text-secondary">Material de Estudio</h5>
                                <p class="card-text text-muted">Accede a apuntes, lecturas y recursos complementarios de tus materias.</p>
                                <a href="#" class="btn btn-outline-danger btn-sm mt-2">Acceder Material</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card h-100 shadow-sm border-0 student-portal-card">
                            <div class="card-body text-center">
                                <i class="fas fa-calendar-alt fa-3x mb-3 text-success"></i>
                                <h5 class="card-title fw-bold text-secondary">Calendario Académico</h5>
                                <p class="card-text text-muted">Mantente al tanto de fechas importantes, exámenes y eventos.</p>
                                <a href="#" class="btn btn-outline-success btn-sm mt-2">Ver Calendario</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 text-center mt-4">
                        <button class="btn btn-secondary" onclick="window.location.href='#general-access'">Volver al Acceso General</button>
                    </div>
                </div>
            </div>
        </section>

        <section id="admin-access" class="py-5 bg-secondary-subtle d-none">
            <div class="container">
                <h2 class="display-5 fw-bold text-center mb-5 text-secondary">Panel de Administración</h2>
                <p class="text-center lead mb-5 text-muted">Gestiona el instituto eficientemente con estas herramientas administrativas.</p>
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="card h-100 shadow-sm border-0 admin-portal-card">
                            <div class="card-body text-center">
                                <i class="fas fa-user-tie fa-3x mb-3 text-secondary"></i>
                                <h5 class="card-title fw-bold text-secondary">Gestión de Profesores</h5>
                                <p class="card-text text-muted">Administra datos y horarios de los docentes del instituto.</p>
                                <a href="#" class="btn btn-outline-secondary btn-sm mt-2">Acceder</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card h-100 shadow-sm border-0 admin-portal-card">
                            <div class="card-body text-center">
                                <i class="fas fa-users fa-3x mb-3 text-secondary"></i>
                                <h5 class="card-title fw-bold text-secondary">Gestión de Estudiantes</h5>
                                <p class="card-text text-muted">Controla matrículas, asistencias y rendimiento académico.</p>
                                <a href="#" class="btn btn-outline-secondary btn-sm mt-2">Acceder</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card h-100 shadow-sm border-0 admin-portal-card">
                            <div class="card-body text-center">
                                <i class="fas fa-book fa-3x mb-3 text-secondary"></i>
                                <h5 class="card-title fw-bold text-secondary">Gestión de Cursos</h5>
                                <p class="card-text text-muted">Crea y actualiza cursos, asignaturas y horarios de clases.</p>
                                <a href="#" class="btn btn-outline-secondary btn-sm mt-2">Acceder</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 text-center mt-4">
                        <button class="btn btn-secondary" onclick="window.location.href='#general-access'">Volver al Acceso General</button>
                    </div>
                </div>
            </div>
        </section>

        <section id="contact" class="py-5 bg-light-gray">
    <div class="container">
        <h2 class="display-5 fw-bold text-center mb-5 text-primary">Contáctanos</h2>
        <div class="row">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <h4 class="fw-bold mb-3">Envíanos un Mensaje</h4>
                <form id="contactForm" method="POST">

    <!-- Campo CSRF CORRECTO -->
   <?= csrf_field() ?>

    <div class="mb-3">
        <label for="contactName" class="form-label">Nombre Completo</label>
        <input type="text" class="form-control" id="contactName" name="nombre" placeholder="Tu Nombre" required>
    </div>

    <div class="mb-3">
        <label for="contactEmail" class="form-label">Correo Electrónico</label>
        <input type="email" class="form-control" id="contactEmail" name="email" placeholder="tu.email@ejemplo.com" required>
    </div>

    <div class="mb-3">
        <label for="contactSubject" class="form-label">Asunto</label>
        <input type="text" class="form-control" id="contactSubject" name="asunto" placeholder="Motivo de tu consulta" maxlength="80" required>
    </div>

    <div class="mb-3">
        <label for="contactMessage" class="form-label">Mensaje</label>
        <textarea class="form-control" id="contactMessage" name="mensaje" rows="5" placeholder="Escribe tu mensaje aquí..." maxlength="300" required></textarea>
    </div>

    <button type="button" class="btn btn-primary btn-lg" id="submitBtn">Enviar Mensaje</button>
</form>


                <!-- Incluye SweetAlert si no está en tu layout -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Script para el formulario -->
<script>
document.getElementById('submitBtn').addEventListener('click', function() {
    const form = document.getElementById('contactForm');
    const formData = new FormData(form);

    // ✅ Obtener token actual del input
    const csrfName = form.querySelector('input[type=hidden]').getAttribute('name');
    const csrfValue = form.querySelector('input[type=hidden]').value;

    formData.append(csrfName, csrfValue);

    fetch('<?= base_url('consultas/enviar') ?>', {
        method: 'POST',
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(response => response.json())
    .then(data => {

        // ✅ Actualizar token para el próximo envío
        form.querySelector('input[type=hidden]').value = data.csrf_token;

        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Mensaje enviado',
                text: data.message
            });
            form.reset();
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message
            });
        }
    })
    .catch(() => {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Error al enviar la consulta.'
        });
    });
});

</script>
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
