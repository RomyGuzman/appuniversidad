<?= $this->extend('Dashboard_Estudiantes/layout_estudiante') ?>

<?= $this->section('title') ?>
    Dashboard - <?= esc($estudiante['nombre_estudiante'] ?? 'Estudiante') ?>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Dashboard de estudiante cargado correctamente.');

    // Verificar notificaciones al cargar la página
    const notificaciones = <?= json_encode(session()->get('notificaciones') ?? []) ?>;
    const usuarioId = <?= esc($estudiante['id'] ?? 0) ?>;

    // Filtrar notificaciones para este usuario
    const notificacionesUsuario = notificaciones.filter(n => n.usuario_id == usuarioId && n.tipo === 'consulta_resuelta');

    if (notificacionesUsuario.length > 0) {
        // Mostrar la primera notificación (o la más reciente)
        const notif = notificacionesUsuario[0];
        Swal.fire({
            icon: 'info',
            title: notif.titulo,
            text: notif.mensaje,
            confirmButtonText: 'Aceptar'
        });

        // Limpiar notificaciones después de mostrar (eliminar de la sesión)
        // Esto se hace eliminando las notificaciones del usuario de la sesión
        fetch('<?= base_url('notificaciones/limpiar') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ usuario_id: usuarioId })
        }).catch(err => console.log('Error al limpiar notificaciones:', err));
    }
});
</script>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Header Principal -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="text-muted mb-1 small">Panel de Gestión Académica</p>
                    <h1 class="mb-2">Dashboard Estudiantil</h1>
                    <h3 class="text-primary mb-0">
                        <i class="fas fa-graduation-cap me-2"></i>
                        <?= esc($estudiante['nombre_carrera'] ?? 'Carrera no asignada') ?>
                    </h3>
                </div>
                <div class="text-end">
                    <h6 class="mb-2 text-muted fs-6">BIENVENIDO, <?= strtoupper(esc($estudiante['nombre_estudiante'] ?? 'Estudiante')) ?></h6>
                    <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#modalConsultaEstudiante">
                        <i class="fas fa-envelope me-2"></i>Contactar al Administrador
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Perfil y Estadísticas -->
    <div class="row mb-4">
        <div class="col-lg-4">
            <div class="row">
                <div class="col-12 mb-4">
                    <div class="card h-100">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="fas fa-user-graduate me-2"></i>Mi Perfil</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <strong class="text-muted d-block small">NOMBRE COMPLETO</strong>
                                <span class="fs-6"><?= esc($estudiante['nombre_estudiante'] ?? 'No disponible') ?></span>
                            </div>
                            <div class="mb-3">
                                <strong class="text-muted d-block small">DNI</strong>
                                <span class="fs-6"><?= esc($estudiante['dni'] ?? 'No disponible') ?></span>
                            </div>
                            <div class="mb-0">
                                <strong class="text-muted d-block small">CARRERA</strong>
                                <span class="fs-6 text-primary fw-bold"><?= esc($estudiante['nombre_carrera'] ?? 'No asignada') ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card h-100">
                        <div class="card-header bg-secondary text-white">
                            <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Estadísticas</h5>
                        </div>
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <h2 class="text-primary mb-1"><?= esc(number_format($estadisticas['promedio_general'] ?? 0, 2)) ?></h2>
                                <p class="small text-muted mb-0">Promedio General</p>
                            </div>
                            <hr class="my-2">
                            <div>
                                <h2 class="text-success mb-1"><?= esc($estadisticas['materias_aprobadas'] ?? 0) ?></h2>
                                <p class="small text-muted mb-0">Materias Aprobadas</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Columna Principal: Materias -->
        <div class="col-lg-8">
            <!-- Sección Unificada de Gestión de Materias -->
            <div class="materias-section">
                <!-- Header Principal de la Sección -->
                <div class="mb-4">
                    <h2 class="text-primary"><i class="fas fa-book-open me-2"></i>Gestión de Materias Académicas</h2>
                    <p class="text-muted">Aquí puedes inscribirte en nuevas materias y gestionar las que ya cursas.</p>
                </div>

                <!-- Materias Disponibles -->
                <div class="row">
                    <div class="col-12">
                        <div class="card mb-4">
                            <div class="card-header bg-info text-white">
                                <h5 class="mb-0"><i class="fas fa-plus-circle me-2"></i>Materias Disponibles para Inscripción</h5>
                            </div>
                            <div class="card-body">
                                <?php if (!empty($materias_disponibles)): ?>
                                    <div class="accordion" id="accordionDisponibles">
                                        <?php foreach ($materias_disponibles as $index => $materia): ?>
                                            <div class="accordion-item">
                                                <h2 class="accordion-header bg-light" id="headingDisp<?= $materia['id'] ?>">
                                                    <button class="accordion-button bg-light <?= $index > 0 ? 'collapsed' : '' ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapseDisp<?= $materia['id'] ?>" aria-expanded="<?= $index === 0 ? 'true' : 'false' ?>" aria-controls="collapseDisp<?= $materia['id'] ?>">
                                                        <div class="d-flex justify-content-between align-items-center w-100">
                                                            <div>
                                                                <strong class="fs-5 text-primary"><?= esc($materia['nombre_materia']) ?></strong>
                                                                <br>
                                                                <small class="text-muted">Código: <?= esc($materia['codigo_materia']) ?></small>
                                                            </div>
                                                        </div>
                                                    </button>
                                                </h2>
                                                <div id="collapseDisp<?= $materia['id'] ?>" class="accordion-collapse collapse <?= $index === 0 ? 'show' : '' ?>" aria-labelledby="headingDisp<?= $materia['id'] ?>" data-bs-parent="#accordionDisponibles">
                                                    <div class="accordion-body bg-white border-top">
                                                        <div class="row">
                                                            <div class="col-md-8">
                                                                <h6>Descripción</h6>
                                                                <p class="text-muted mb-3">
                                                                    <?= esc($materia['descripcion_materia'] ?? 'Sin descripción disponible') ?>
                                                                </p>

                                                                <h6>Información</h6>
                                                                <div class="row">
                                                                    <div class="col-sm-6">
                                                                        <p class="mb-1"><strong>Horas semanales:</strong> <?= esc($materia['horas_semanales'] ?? 'N/A') ?>h</p>
                                                                        <p class="mb-1"><strong>Modalidad:</strong> <?= esc($materia['modalidad'] ?? 'N/A') ?></p>
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                        <p class="mb-1"><strong>Cupo máximo:</strong> <?= esc($materia['cupo_maximo'] ?? 'N/A') ?> estudiantes</p>
                                                                        <p class="mb-1"><strong>Estado actual:</strong>
                                                                            <span class="badge bg-<?= str_replace(['inscribirme', 'ya_inscrito', 'no_curso'], ['primary', 'secondary', 'warning'], $materia['estado']) ?>">
                                                                                <?= esc($materia['boton_texto']) ?>
                                                                            </span>
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="card border-info">
                                                                    <div class="card-body text-center">
                                                                        <h6 class="card-title text-info">¿Qué hacer?</h6>
                                                                        <?php if ($materia['estado'] == 'inscribirme'): ?>
                                                                            <button type="button"
                                                                                    class="btn btn-primary btn-sm mb-2"
                                                                                    onclick="inscribirMateria(<?= $materia['id'] ?>, '<?= esc($materia['estado']) ?>')">
                                                                                <i class="fas fa-plus me-1"></i>Inscribirme
                                                                            </button>
                                                                            <p class="mb-0 small">Haz click para comenzar a cursar esta materia.</p>
                                                                        <?php elseif ($materia['estado'] == 'no_curso'): ?>
                                                                            <button type="button"
                                                                                    class="btn btn-warning btn-sm mb-2"
                                                                                    onclick="inscribirMateria(<?= $materia['id'] ?>, '<?= esc($materia['estado']) ?>')">
                                                                                <i class="fas fa-redo me-1"></i>Reintentar
                                                                            </button>
                                                                            <p class="mb-0 small">Puedes reintentar esta materia. ¡Ánimo!</p>
                                                                        <?php elseif ($materia['estado'] == 'ya_inscrito'): ?>
                                                                            <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                                                                            <p class="mb-0 small">Ya estás inscrito en esta materia.</p>
                                                                        <?php else: ?>
                                                                            <button type="button"
                                                                                    class="btn btn-primary btn-sm mb-2"
                                                                                    onclick="inscribirMateria(<?= $materia['id'] ?>, 'inscribirme')">
                                                                                <i class="fas fa-plus me-1"></i>Inscribirme
                                                                            </button>
                                                                            <p class="mb-0 small">Haz click para comenzar a cursar esta materia.</p>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <div class="alert alert-info text-center">
                                        <i class="fas fa-info-circle fa-2x mb-3"></i>
                                        <h5>No hay materias disponibles</h5>
                                        <p class="mb-0">No se encontraron materias para tu carrera en este momento.</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Materias Inscritas -->
                <div class="card mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-check-circle me-2"></i>Materias en las que estás inscrito</h5>
                    </div>
                    <div class="card-body">
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
                    <div class="alert alert-info text-center">
                        <i class="fas fa-info-circle fa-2x mb-3"></i>
                        <h5>No tienes materias inscritas</h5>
                        <p class="mb-0">Inscríbete en las materias disponibles arriba.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
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
            <form id="formConsultaEstudiante" action="<?= base_url('consultas/enviar') ?>" method="post">
                <?= csrf_field() ?>
                <!-- Usamos el ID del estudiante que viene del controlador -->
                <input type="hidden" name="usuario_id" value="<?= esc($estudiante['id'] ?? '') ?>">
                <input type="hidden" name="tipo_usuario" value="estudiante">
                <div class="modal-body">
                    <div id="consultaErrors" class="alert alert-danger d-none"></div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="tuemail@ejemplo.com" required>
                    </div>
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

// NUEVO: Función para inscribir materia
function inscribirMateria(materiaId, estado) {
    console.log('=== INICIANDO FUNCIÓN inscribirMateria ===');
    console.log('Materia ID:', materiaId, 'Estado:', estado);
    console.log('Base URL configurado:', '<?= base_url() ?>');

    // Mostrar confirmación
    Swal.fire({
        title: '¿Confirmar inscripción?',
        text: '¿Estás seguro de que quieres inscribirte en esta materia?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#007bff',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, inscribirme',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Mostrar loading
            Swal.fire({
                title: 'Procesando...',
                text: 'Inscribiendo materia',
                allowOutsideClick: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading();
                }
            });

            // Enviar petición AJAX
            fetch('<?= base_url('estudiantes/inscribir') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: 'materia_id=' + encodeURIComponent(materiaId)
            })
            .then(response => {
                console.log('Respuesta del servidor:', response);
                return response.json();
            })
            .then(data => {
                console.log('Datos recibidos:', data);

                if (data.success) {
                    // Éxito
                    Swal.fire({
                        icon: 'success',
                        title: '¡Inscripción exitosa!',
                        text: data.message || 'Te has inscrito correctamente en la materia.',
                        confirmButtonText: 'Aceptar'
                    }).then(() => {
                        // Recargar la página para actualizar el estado
                        location.reload();
                    });
                } else {
                    // Error
                    Swal.fire({
                        icon: 'error',
                        title: 'Error en la inscripción',
                        text: data.error || 'No se pudo completar la inscripción. Inténtalo de nuevo.',
                        confirmButtonText: 'Aceptar'
                    });
                }
            })
            .catch(error => {
                console.error('Error en la petición:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error de conexión',
                    text: 'No se pudo conectar con el servidor. Verifica tu conexión a internet.',
                    confirmButtonText: 'Aceptar'
                });
            });
        }
    });
}

// Manejar el envío del formulario de consulta
document.getElementById('formConsultaEstudiante').addEventListener('submit', function(e) {
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
            const modal = bootstrap.Modal.getInstance(document.getElementById('modalConsultaEstudiante'));
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
                const errorDiv = document.getElementById('consultaErrors');
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
<?= $this->endSection() ?>
