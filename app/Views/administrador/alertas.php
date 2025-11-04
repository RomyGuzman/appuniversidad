<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Centro de Alertas - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="<?= base_url('styles.css'); ?>">
    <style>
        .border-left-warning {
            border-left: 0.25rem solid #f6c23e !important;
        }
        .border-left-secondary {
            border-left: 0.25rem solid #858796 !important;
        }
        .consulta-resuelta {
            opacity: 0.65;
        }
        .text-right {
            text-align: right;
        }
    </style>
</head>
<body class="sb-nav-fixed">
    <?php 
        // Incluimos la barra de navegación del administrador.
        // Asumimos que se llama 'NavbarAdmin.php' y está en 'app/Views/templates/'
        // Si el nombre o la ruta es diferente, ajústalo aquí.
        echo view('templates/NavbarAdmin'); 
    ?>

    <header class="bg-dark text-white py-5">
        <div class="container text-center">
            <h1 class="display-4 fw-bold mb-4">Centro de Alertas y Consultas</h1>
            <p class="lead">Gestión de consultas de estudiantes y profesores</p>
        </div>
    </header>

    <main class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <?php if (empty($consultas)): ?>
                    <div class="card shadow mb-4">
                        <div class="card-body text-center py-5">
                            <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                            <h3 class="text-gray-800">¡Excelente! No hay consultas pendientes.</h3>
                            <p class="lead text-muted">Todo está al día.</p>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="card shadow">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col">Estado</th>
                                            <th scope="col">Asunto</th>
                                            <th scope="col">Remitente</th>
                                            <th scope="col">Rol</th>
                                            <th scope="col">Fecha</th>
                                            <th scope="col" class="text-center">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($consultas as $consulta): ?>
                                            <tr id="consulta-row-<?= esc($consulta->id_consulta) ?>" class="shadow-sm <?= $consulta->estado === 'resuelta' ? 'table-secondary text-muted' : '' ?>">
                                                <td>
                                                    <?php if ($consulta->estado === 'resuelta'): ?>
                                                        <i class="fas fa-check-circle text-success" title="Resuelta"></i>
                                                    <?php else: ?>
                                                        <span class="text-warning" title="Pendiente">Pendiente</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="fw-bold"><?= esc($consulta->asunto) ?></td>
                                                <td><?= esc($consulta->nombre_remitente ?? 'Usuario no identificado') ?></td>
                                                <td><?= esc($consulta->nombre_rol ?? 'Rol no definido') ?></td>
                                                <td><?= esc(date('d/m/Y H:i', strtotime($consulta->fecha_creacion))) ?></td>
                                                <td class="text-center">
                                                    <button class="btn btn-primary btn-sm view-details-btn"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#detalleConsultaModal"
                                                            data-asunto="<?= esc($consulta->asunto) ?>"
                                                            data-mensaje="<?= esc($consulta->mensaje) ?>"
                                                            data-remitente="<?= esc($consulta->nombre_remitente ?? 'N/A') ?>"
                                                            data-email="<?= esc($consulta->email_usuario) ?>"
                                                            data-fecha="<?= esc(date('d/m/Y H:i', strtotime($consulta->fecha_creacion))) ?>">
                                                        <i class="fas fa-eye me-1"></i> Ver
                                                    </button>
                                                    <?php if ($consulta->estado === 'pendiente'): ?>
                                                        <button class="btn btn-success btn-sm mark-as-read-btn" data-id="<?= esc($consulta->id_consulta) ?>">
                                                            <i class="fas fa-check-circle me-1"></i> Resolver
                                                        </button>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- Paginación -->
                            <div class="mt-4 d-flex justify-content-center">
                                <?= $pager->links() ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5><i class="fas fa-university me-2"></i>Instituto Superior 57</h5>
                    <p class="mb-2"><i class="fas fa-map-marker-alt me-2"></i>Av. Siempre Viva 742, Springfield</p>
                    <p class="mb-2"><i class="fas fa-phone me-2"></i>+54 11 3456-7890</p>
                    <p class="mb-0"><i class="fas fa-envelope me-2"></i>info@instituto57.edu.ar</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <h5>Formando profesionales desde 1990</h5>
                    <p class="mb-0">Excelencia académica e innovación tecnológica</p>
                </div>
            </div>
            <hr class="my-3" />
            <div class="text-center">
                <p class="mb-0">&copy; 2023 Gestión Instituto. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <!-- Modal para Ver Detalle de Consulta -->
    <div class="modal fade" id="detalleConsultaModal" tabindex="-1" aria-labelledby="detalleConsultaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detalleConsultaModalLabel">Detalle de la Consulta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h4 id="modal-asunto" class="mb-3"></h4>
                    <div class="mb-3">
                        <p class="mb-0"><strong>De:</strong> <span id="modal-remitente"></span></p>
                        <p class="mb-0"><strong>Email:</strong> <span id="modal-email"></span></p>
                        <p class="mb-0"><strong>Fecha:</strong> <span id="modal-fecha"></span></p>
                    </div>
                    <hr>
                    <div id="modal-mensaje" style="white-space: pre-wrap; word-wrap: break-word;">
                        <!-- El mensaje se insertará aquí -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <!-- Campo oculto para el token CSRF -->
    <input type="hidden" name="csrf_test_name" value="<?= csrf_hash() ?>" />

    <script>
        // Pasa la configuración de PHP a JavaScript
        window.APP_CONFIG = {
            baseUrl: "<?= base_url('/') ?>",
            flash: {
                success: "<?= session()->getFlashdata('success') ?>",
                error: "<?= session()->getFlashdata('error') ?>"
            }
        };
    </script>
    <script src="<?= base_url('app.js') ?>"></script>
</body>
</html>