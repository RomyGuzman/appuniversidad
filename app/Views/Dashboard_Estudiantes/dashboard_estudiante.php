<?= $this->extend('Dashboard_Estudiantes/layout_estudiante') ?>

<?= $this->section('title') ?>
    Dashboard - <?= esc($estudiante['nombre_estudiante'] ?? 'Estudiante') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <p class="text-muted mb-0 small">Panel de Gestión</p>
            <h2 class="mb-0">Mis Materias</h2>
        </div>
        <div class="d-flex align-items-center gap-3">
            <h5 class="mb-0 text-muted">Bienvenido, <?= esc($estudiante['nombre_estudiante'] ?? 'Estudiante') ?></h5>
            <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#modalConsultaEstudiante">
                <i class="fas fa-envelope me-2"></i>Contactar al Administrador
            </button>
        </div>
    </div>

    <div class="row">
        <!-- Columna Lateral: Perfil y Estadísticas -->
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-user-graduate me-2"></i>Mi Perfil</h5>
                </div>
                <div class="card-body">
                    <p><strong>Nombre:</strong><br><?= esc($estudiante['nombre_estudiante'] ?? 'No disponible') ?></p>
                    <p><strong>DNI:</strong><br><?= esc($estudiante['dni'] ?? 'No disponible') ?></p>
                    <p><strong>Carrera:</strong><br><?= esc($estudiante['nombre_carrera'] ?? 'No asignada') ?></p>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Mis Estadísticas</h5>
                </div>
                <div class="card-body text-center">
                    <h3 class="text-primary"><?= esc(number_format($estadisticas['promedio_general'] ?? 0, 2)) ?></h3>
                    <p class="small text-muted mb-0">Promedio General</p>
                    <hr>
                    <h3 class="text-success"><?= esc($estadisticas['materias_aprobadas'] ?? 0) ?></h3>
                    <p class="small text-muted mb-0">Materias Aprobadas</p>
                </div>
            </div>
        </div>

        <!-- Columna Principal: Materias -->
        <div class="col-lg-8">
            <h3 class="mb-4">Materias Inscritas</h3>
            <div class="accordion" id="accordionMaterias">
                <?php if (!empty($materias_inscritas)): ?>
                    <?php foreach ($materias_inscritas as $index => $materia): ?>
                        <div class="accordion-item mb-3">
                            <h2 class="accordion-header" id="heading<?= $materia['materia_id'] ?>">
                                <button class="accordion-button <?= $index > 0 ? 'collapsed' : '' ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $materia['materia_id'] ?>" aria-expanded="<?= $index === 0 ? 'true' : 'false' ?>" aria-controls="collapse<?= $materia['materia_id'] ?>">
                                    <div class="d-flex justify-content-between w-100 align-items-center pe-3">
                                        <span class="fw-bold fs-5"><?= esc($materia['nombre_materia']) ?></span>
                                        <span class="badge bg-primary rounded-pill p-2">
                                            <i class="fas fa-book me-1"></i>
                                            Código: <?= esc($materia['codigo_materia']) ?>
                                        </span>
                                    </div>
                                </button>
                            </h2>
                            <div id="collapse<?= $materia['materia_id'] ?>" class="accordion-collapse collapse <?= $index === 0 ? 'show' : '' ?>" aria-labelledby="heading<?= $materia['materia_id'] ?>" data-bs-parent="#accordionMaterias">
                                <div class="accordion-body">
                                    <!-- Pestañas para Notas, Asistencia y Materiales -->
                                    <ul class="nav nav-tabs" id="tabs-<?= $materia['materia_id'] ?>" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link active" id="notas-tab-<?= $materia['materia_id'] ?>" data-bs-toggle="tab" data-bs-target="#notas-<?= $materia['materia_id'] ?>" type="button" role="tab" aria-controls="notas-<?= $materia['materia_id'] ?>" aria-selected="true">
                                                <i class="fas fa-clipboard-list me-1"></i>Notas
                                            </button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="asistencia-tab-<?= $materia['materia_id'] ?>" data-bs-toggle="tab" data-bs-target="#asistencia-<?= $materia['materia_id'] ?>" type="button" role="tab" aria-controls="asistencia-<?= $materia['materia_id'] ?>" aria-selected="false">
                                                <i class="fas fa-calendar-check me-1"></i>Asistencia
                                            </button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="materiales-tab-<?= $materia['materia_id'] ?>" data-bs-toggle="tab" data-bs-target="#materiales-<?= $materia['materia_id'] ?>" type="button" role="tab" aria-controls="materiales-<?= $materia['materia_id'] ?>" aria-selected="false">
                                                <i class="fas fa-book-open me-1"></i>Materiales
                                            </button>
                                        </li>
                                    </ul>
                                    <div class="tab-content mt-3" id="tabs-content-<?= $materia['materia_id'] ?>">
                                        <!-- Pestaña de Notas -->
                                        <div class="tab-pane fade show active" id="notas-<?= $materia['materia_id'] ?>" role="tabpanel" aria-labelledby="notas-tab-<?= $materia['materia_id'] ?>">
                                            <h6 class="text-muted">Mis Notas en esta Materia</h6>
                                            <?php
                                            $notas_materia = array_filter($notas, function($nota) use ($materia) {
                                                return $nota['materia_id'] == $materia['materia_id'];
                                            });
                                            ?>
                                            <?php if (!empty($notas_materia)): ?>
                                                <div class="table-responsive">
                                                    <table class="table table-sm table-striped">
                                                        <thead>
                                                            <tr class="text-center">
                                                                <th class="text-start"><i class="fas fa-star me-1"></i>Nota</th>
                                                                <th><i class="fas fa-calendar me-1"></i>Fecha Evaluación</th>
                                                                <th><i class="fas fa-comment me-1"></i>Observaciones</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="align-middle">
                                                            <?php foreach ($notas_materia as $nota): ?>
                                                                <tr>
                                                                    <td class="text-start">
                                                                        <span class="badge bg-<?= $nota['calificacion'] >= 7 ? 'success' : ($nota['calificacion'] >= 4 ? 'warning' : 'danger') ?> fs-6">
                                                                            <?= esc($nota['calificacion']) ?>/10
                                                                        </span>
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <?= esc(date('d/m/Y', strtotime($nota['fecha_evaluacion']))) ?>
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <?= esc($nota['observaciones'] ?? 'Sin observaciones') ?>
                                                                    </td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            <?php else: ?>
                                                <p class="text-muted small">Aún no tienes notas registradas en esta materia.</p>
                                            <?php endif; ?>
                                        </div>

                                        <!-- Pestaña de Asistencia -->
                                        <div class="tab-pane fade" id="asistencia-<?= $materia['materia_id'] ?>" role="tabpanel" aria-labelledby="asistencia-tab-<?= $materia['materia_id'] ?>">
                                            <h6 class="text-muted">Mi Asistencia en esta Materia</h6>
                                            <?php
                                            $asistencia_materia = $asistencias_por_materia[$materia['materia_id']] ?? [];
                                            $total_clases = count($asistencia_materia);
                                            $clases_presentes = count(array_filter($asistencia_materia, function($a) { return $a['estado'] == 'Presente'; }));
                                            $porcentaje = $total_clases > 0 ? round(($clases_presentes / $total_clases) * 100, 1) : 0;
                                            ?>
                                            <div class="row mb-4">
                                                <div class="col-md-6">
                                                    <div class="card border-primary">
                                                        <div class="card-body text-center">
                                                            <h4 class="text-primary"><?= $porcentaje ?>%</h4>
                                                            <p class="small text-muted mb-0">Asistencia General</p>
                                                            <div class="progress mt-2" style="height: 8px;">
                                                                <div class="progress-bar bg-primary" role="progressbar" style="width: <?= $porcentaje ?>%" aria-valuenow="<?= $porcentaje ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="card border-success">
                                                        <div class="card-body text-center">
                                                            <h4 class="text-success"><?= $clases_presentes ?>/<?= $total_clases ?></h4>
                                                            <p class="small text-muted mb-0">Clases Asistidas</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <?php if (!empty($asistencia_materia)): ?>
                                                <div class="table-responsive">
                                                    <table class="table table-sm table-striped">
                                                        <thead>
                                                            <tr class="text-center">
                                                                <th><i class="fas fa-calendar me-1"></i>Fecha</th>
                                                                <th><i class="fas fa-check-circle me-1"></i>Estado</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="align-middle">
                                                            <?php foreach ($asistencia_materia as $asistencia): ?>
                                                                <tr>
                                                                    <td class="text-center">
                                                                        <?= esc(date('d/m/Y', strtotime($asistencia['fecha']))) ?>
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <span class="badge bg-<?= $asistencia['estado'] == 'Presente' ? 'success' : ($asistencia['estado'] == 'Ausente' ? 'danger' : 'warning') ?>">
                                                                            <i class="fas fa-<?= $asistencia['estado'] == 'Presente' ? 'check' : ($asistencia['estado'] == 'Ausente' ? 'times' : 'clock') ?> me-1"></i>
                                                                            <?= ucfirst($asistencia['estado']) ?>
                                                                        </span>
                                                                    </td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            <?php else: ?>
                                                <p class="text-muted small">No hay registros de asistencia para esta materia.</p>
                                            <?php endif; ?>
                                        </div>

                                        <!-- Pestaña de Materiales -->
                                        <div class="tab-pane fade" id="materiales-<?= $materia['materia_id'] ?>" role="tabpanel" aria-labelledby="materiales-tab-<?= $materia['materia_id'] ?>">
                                            <h6 class="text-muted">Materiales de Estudio</h6>
                                            <?php $materiales = $materiales_por_materia[$materia['materia_id']] ?? []; ?>
                                            <?php if (!empty($materiales)): ?>
                                                <div class="row">
                                                    <?php foreach ($materiales as $material): ?>
                                                        <div class="col-md-6 mb-3">
                                                            <div class="card h-100">
                                                                <div class="card-body">
                                                                    <h6 class="card-title">
                                                                        <i class="fas fa-file-<?= strpos($material['tipo'], 'pdf') !== false ? 'pdf' : (strpos($material['tipo'], 'video') !== false ? 'video' : 'alt') ?> me-2"></i>
                                                                        <?= esc($material['titulo']) ?>
                                                                    </h6>
                                                                    <p class="card-text small text-muted">
                                                                        <?= esc($material['descripcion'] ?? 'Sin descripción') ?>
                                                                    </p>
                                                                    <a href="<?= esc($material['url']) ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                                                        <i class="fas fa-download me-1"></i>Descargar
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            <?php else: ?>
                                                <p class="text-muted small">No hay materiales disponibles para esta materia.</p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="alert alert-info">No te has inscrito a ninguna materia todavía.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Contactar al Administrador (Estudiante) -->
<div class="modal fade" id="modalConsultaEstudiante" tabindex="-1" aria-labelledby="modalConsultaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalConsultaLabel"><i class="fas fa-envelope-open-text me-2"></i>Enviar Consulta al Administrador</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('consultas/enviar') ?>" method="post">
                <?= csrf_field() ?>
                <!-- Usamos el ID del estudiante que viene del controlador -->
                <input type="hidden" name="usuario_id" value="<?= esc($estudiante['id'] ?? '') ?>">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="asunto" class="form-label">Asunto</label>
                        <input type="text" class="form-control" id="asunto" name="asunto" placeholder="Ej: Problema con una inscripción" required>
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
document.addEventListener('DOMContentLoaded', function() {
    console.log('Dashboard de estudiante cargado correctamente.');
});
</script>
<?= $this->endSection() ?>
