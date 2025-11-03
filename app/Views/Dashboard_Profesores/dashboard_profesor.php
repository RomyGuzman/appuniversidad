<?= $this->extend('Dashboard_Profesores/layout_profesor') ?>

<?= $this->section('title') ?>
    Dashboard - <?= esc($profesor['nombre_profesor'] ?? 'Profesor') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <p class="text-muted mb-0 small">Panel de Gestión</p>
                <h2 class="mb-0">Mi Dashboard</h2>
            </div>
            <div class="d-flex align-items-center gap-3">
                <h5 class="mb-0 text-muted">Bienvenido, Prof. <?= esc($profesor['nombre_profesor']) ?></h5>
                <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#modalConsultaProfesor">
                    <i class="fas fa-envelope me-2"></i>Contactar al Administrador
                </button>
            </div>
        </div>

        <!-- Acordeón para Perfil y Materias -->
        <div class="col-12">
            <div class="accordion" id="accordionDashboard">
                <!-- Perfil del Profesor -->
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingPerfil">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePerfil" aria-expanded="true" aria-controls="collapsePerfil">
                            <div class="d-flex justify-content-between w-100 align-items-center pe-3">
                                <span class="fw-bold fs-5">
                                    <i class="fas fa-user me-2"></i>Mi Perfil
                                </span>
                                <span class="badge bg-secondary rounded-pill p-2">
                                    <i class="fas fa-id-card me-1"></i>
                                    Información Personal
                                </span>
                            </div>
                        </button>
                    </h2>
                    <div id="collapsePerfil" class="accordion-collapse collapse show" aria-labelledby="headingPerfil" data-bs-parent="#accordionDashboard">
                        <div class="accordion-body">
                            <div class="row">
                                <!-- Datos del Profesor -->
                                <div class="col-md-6">
                                    <h6 class="text-primary mb-3">
                                        <i class="fas fa-user-circle me-2"></i>Datos Personales
                                    </h6>
                                    <div class="mb-2">
                                        <strong>Nombre:</strong> <?= esc($profesor['nombre_profesor'] ?? 'No disponible') ?>
                                    </div>
                                    <div class="mb-2">
                                        <strong>ID:</strong> <?= esc($profesor['id'] ?? 'No disponible') ?>
                                    </div>
                                    <div class="mb-2">
                                        <strong>Legajo:</strong> <?= esc($profesor['legajo'] ?? 'No disponible') ?>
                                    </div>
                                </div>
                                <!-- Estadísticas -->
                                <div class="col-md-6">
                                    <h6 class="text-success mb-3">
                                        <i class="fas fa-chart-bar me-2"></i>Estadísticas
                                    </h6>
                                    <div class="row">
                                        <div class="col-6 mb-3">
                                            <div class="card border-0 shadow-sm text-center">
                                                <div class="card-body">
                                                    <div class="display-4 text-primary">
                                                        <?= count($materias ?? []) ?>
                                                    </div>
                                                    <div class="text-muted">Materias Asignadas</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6 mb-3">
                                            <div class="card border-0 shadow-sm text-center">
                                                <div class="card-body">
                                                    <div class="display-4 text-success">
                                                        <!-- Aquí podrías calcular total de estudiantes si está disponible -->
                                                        <?= array_reduce($materias ?? [], function($carry, $materia) {
                                                            return $carry + (isset($materia['total_estudiantes']) ? $materia['total_estudiantes'] : 0);
                                                        }, 0) ?>
                                                    </div>
                                                    <div class="text-muted">Estudiantes Totales</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Resumen de Materias -->
                                    <div class="mt-3">
                                        <h6 class="text-muted mb-2">Materias Asignadas</h6>
                                        <ul class="list-unstyled">
                                            <?php foreach ($materias ?? [] as $materia): ?>
                                            <li class="mb-1">
                                                <i class="fas fa-book text-info me-2"></i>
                                                <?= esc($materia['nombre_materia']) ?> (<?= esc($materia['codigo_materia']) ?>)
                                            </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

        <!-- Materias - Full Width -->
        <div class="col-12">
            <h3 class="mb-4">Materias Inscritas</h3>
            <div class="accordion" id="accordionMaterias">
                <?php if (!empty($materias)): ?>
                    <?php foreach ($materias as $index => $materia): ?>
                        <div class="accordion-item mb-3">
                            <h2 class="accordion-header" id="heading<?= $materia['id'] ?>">
                                <button class="accordion-button <?= $index > 0 ? 'collapsed' : '' ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $materia['id'] ?>" aria-expanded="<?= $index === 0 ? 'true' : 'false' ?>" aria-controls="collapse<?= $materia['id'] ?>">
                                    <div class="d-flex justify-content-between w-100 align-items-center pe-3">
                                        <span class="fw-bold fs-5"><?= esc($materia['nombre_materia']) ?></span>
                                        <span class="badge bg-primary rounded-pill p-2">
                                            <i class="fas fa-book me-1"></i>
                                            Código: <?= esc($materia['codigo_materia']) ?>
                                        </span>
                                    </div>
                                </button>
                            </h2>
                            <div id="collapse<?= $materia['id'] ?>" class="accordion-collapse collapse show" aria-labelledby="heading<?= $materia['id'] ?>" data-bs-parent="#accordionMaterias">
                                <div class="accordion-body">
                                    <?= view('Dashboard_Profesores/_asistencia_table', ['materia_id' => $materia['id']]) ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="alert alert-info">No tienes materias asignadas todavía.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Contactar al Administrador (Profesor) -->
<div class="modal fade" id="modalConsultaProfesor" tabindex="-1" aria-labelledby="modalConsultaProfLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalConsultaProfLabel"><i class="fas fa-envelope-open-text me-2"></i>Enviar Consulta al Administrador</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('consultas/enviar') ?>" method="post">
                <?= csrf_field() ?>
                <!-- Usamos el ID del profesor que viene del controlador -->
                <input type="hidden" name="usuario_id" value="<?= esc($profesor['id']) ?>">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="asunto" class="form-label">Asunto</label>
                        <input type="text" class="form-control" id="asunto" name="asunto" placeholder="Ej: Problema con una materia" required>
                    </div>
                    <div class="mb-3">
                        <label for="mensaje" class="form-label">Mensaje</label>
                        <textarea class="form-control" id="mensaje" name="mensaje" rows="5" placeholder="Describe tu consulta aquí..." required></textarea>
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
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    var baseUrl = '<?= base_url() ?>';
    var siteUrl = '<?= site_url() ?>';
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="<?= base_url('js/dashboard_asistencia.js') ?>"></script>
<?= $this->endSection() ?>
