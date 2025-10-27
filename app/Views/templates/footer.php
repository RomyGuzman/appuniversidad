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

    <!-- jQuery (NECESARIO para Bootstrap y app.js) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- SweetAlert2 JS (NECESARIO para las alertas de confirmación) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    
    <!-- AOS Library -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <!-- Custom JS -->
    <script>
        // Pasa la configuración de PHP a JavaScript para que app.js pueda usarla (ej: la URL base).
        window.APP_CONFIG = {
            baseUrl: "<?= base_url('/') ?>",
            flash: {
                success: "<?= session()->getFlashdata('success') ?>",
                error: "<?= session()->getFlashdata('error') ?>"
            }
        };

        // Inicializar AOS
        AOS.init({
            duration: 800,
            once: true,
            offset: 100
        });
    </script>
    <!-- Carga tu archivo de scripts principal -->
    <script src="<?= base_url('app.js'); ?>"></script>