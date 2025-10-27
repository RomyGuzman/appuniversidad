<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Gestión de Estudiantes - Instituto Superior</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="<?= base_url('styles.css') ?>" /> 
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" />
</head>
<body class="estudiantes-page">
    <?= view('templates/NavbarAdmin') ?>
    <header class="bg-dark text-white py-5">
        <div class="container text-center">
            <h1 class="display-4 fw-bold mb-4">Gestión de Estudiantes</h1>
            <p class="lead">Sistema de administración integral de alumnos</p>
        </div>
    </header>
    <main class="container my-5">
        <section class="row justify-content-center mb-5">    
            <div class="col-lg-9">
                <div class="card shadow">
                    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                        <h2 class="h5 mb-0"><i class="fas fa-user-plus me-2"></i> Registrar Nuevo Estudiante</h2>
                    </div>
                    <div class="card-body">
                        <?php if (session()->has('errors')): ?>
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                <?php
                                $errors = session('errors');
                                if (is_array($errors)) {
                                    foreach ($errors as $error): ?>
                                        <li><?= esc($error) ?></li>
                                    <?php endforeach;
                                } else {
                                    echo '<li>' . esc($errors) . '</li>';
                                }
                                ?>
                                </ul>
                            </div>
                        <?php endif ?>
                        <form id="studentForm" method="post" action="<?= base_url('estudiantes/registrar') ?>">
                            <?= csrf_field() ?>
                            <div class="mb-3">
                                <label for="name" class="form-label">Nombre Completo</label>
                                <input type="text" class="form-control" id="name" name="nombre_estudiante" required />
                            </div>
                            <div class="mb-3">
                                <label for="dni" class="form-label">DNI</label>
                                <input type="text" class="form-control" id="dni" name="dni" pattern="\d{8}" maxlength="8" required />
                                <small class="text-muted">Debe tener 8 dígitos</small>
                            </div>
                            <div class="mb-3">
                                <label for="age" class="form-label">Edad</label>
                                <input type="text" class="form-control" id="age" name="edad" maxlength="2" required />
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required />
                            </div>
                            <div class="mb-3">
                                <label for="fecha_nac" class="form-label">Fecha de Nacimiento</label>
                                <input type="date" class="form-control" id="fecha_nac" name="fecha_nac" />
                            </div>
                            <div class="mb-3">
                                <label for="career" class="form-label">Carrera</label>
                                <select class="form-select" id="career" name="carrera_id">
                                    <option value="">Seleccione una carrera</option>
                                    <?php if(isset($carreras) && count($carreras) > 0): ?>
                                        <?php foreach($carreras as $car): ?>
                                            <option value="<?= esc($car['id']) ?>">
                                                <?= esc($car['nombre_carrera']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="d-flex justify-content-end mt-4">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save me-1"></i> Registrar Estudiante
                                </button>
                            </div>
                            <div id="validationAlert-studentForm" class="text-danger text-sm mt-2 d-none"></div>
                        </form>
                        <div id="registerResult" class="mt-3"></div>
                    </div>
                </div>
            </div>
        </section>
        <div class="row justify-content-center mt-4">
            <div class="col-lg-9">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="card shadow h-100">
                            <div class="card-header bg-info text-white">
                                <h2 class="h5 mb-0"><i class="fas fa-search me-2"></i> Buscar Estudiante por ID</h2>
                            </div>
                            <div class="card-body">
                                <form id="searchStudentForm">
                                    <div class="mb-3">
                                        <label for="searchStudentId" class="form-label">ID del Estudiante</label>
                                        <input type="number" class="form-control" id="searchStudentId" required min="1" />
                                    </div>
                                    <div class="d-flex justify-content-end mt-3">
                                        <button type="submit" class="btn btn-info">
                                            <i class="fas fa-search me-1"></i> Buscar
                                        </button>
                                    </div>
                                    <div id="validationAlert-searchStudentForm" class="text-danger text-sm mt-2 d-none"></div>
                                </form>
                                <div id="studentDetails" class="mt-3 p-3 border rounded d-none">
                                    <p class="mb-1"><strong>ID:</strong> <span id="detailId"></span></p>
                                    <p class="mb-1"><strong>Nombre:</strong> <span id="detailName"></span></p>
                                    <p class="mb-1"><strong>DNI:</strong> <span id="detailDni"></span></p>
                                    <p class="mb-0"><strong>Carrera:</strong> <span id="detailCareer"></span></p>
                                    <button id="clearStudentDetails" class="btn btn-sm btn-outline-secondary mt-3"><i class="fas fa-times me-1"></i> Limpiar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card shadow h-100">
                            <div class="card-header bg-primary text-white">
                                <h2 class="h5 mb-0"><i class="fas fa-search me-2"></i> Buscar Estudiante por Carrera</h2>
                            </div>
                            <div class="card-body">
                                <form id="searchStudentByCareerForm">
                                    <div class="mb-3">
                                        <label for="searchCareer" class="form-label">Carrera</label>
                                        <select class="form-select" id="searchCareer" required>
                                            <option value="" selected disabled>Seleccione una carrera</option>
                                            <?php if(isset($carreras) && count($carreras) > 0): ?>
                                                <?php foreach($carreras as $car): ?>
                                                    <option value="<?= esc($car['id']) ?>">
                                                        <?= esc($car['nombre_carrera']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                    <div class="d-flex justify-content-end mt-3">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-search me-1"></i> Buscar
                                        </button>
                                    </div>
                                    <div id="validationAlert-searchStudentByCareerForm" class="text-danger text-sm mt-2 d-none"></div>
                                </form>
                                <div id="clearCareerResultsContainer" class="text-center mt-3 d-none">
                                    <button id="clearCareerResultsBtn" class="btn btn-outline-secondary">
                                        <i class="fas fa-times me-1"></i> Limpiar Búsqueda
                                    </button>
                                </div>
                                <div id="studentsByCareerResults" class="row mt-4 g-3"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center mt-5">
            <div class="col-9">
                <div class="card shadow">
                    <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
                        <h2 class="h5 mb-0"><i class="fas fa-list me-2"></i> Listado de Estudiantes</h2>
                        <button id="refreshStudentsBtn" class="btn btn-sm btn-light float-end">
                            <i class="fas fa-sync-alt me-1"></i> Recargar Todos
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="studentsTable" class="table table-striped">
                                <thead class="table-dark">
                                    <tr>
                                       <th>ID</th>
                                            <th>Nombre</th>
                                            <th>DNI</th>
                                            <th>Edad</th>
                                            <th>Email</th>
                                            <th>Carrera</th>
                                           <th>Acciones</th>
                                    </tr>
                                </thead>
                                <!-- <tbody id="studentsTableBody">
                                </tbody> -->
                                <tbody id="studentsTableBody">
                                    <?php if(isset($estudiantes) && count($estudiantes) > 0): ?>
                                        <?php foreach($estudiantes as $est): ?>
                                            <tr>
                                                <td><?= esc($est['id']) ?></td>
                                                <td><?= esc($est['nombre_estudiante']) ?></td>
                                                <td><?= esc($est['dni']) ?></td>
                                                <td><?= esc($est['edad']) ?></td>
                                                <td><?= esc($est['email']) ?></td>
                                                <td><?= esc($est['nombre_carrera']) ?></td>
                                                <td>
                                                    <button class="btn btn-info btn-sm edit-btn" data-id="<?= esc($est['id']) ?>" data-bs-toggle="modal" data-bs-target="#editStudentModal">
                                                        <i class="fas fa-pencil-alt"></i>
                                                    </button>
                                                    <form action="<?= base_url('estudiantes/delete/' . $est['id']) ?>" method="post" class="d-inline delete-form">
                                                        <?= csrf_field() ?>
                                                        <button type="submit" class="btn btn-danger btn-sm" title="Eliminar">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr><td colspan="7" class="text-center">No hay estudiantes registrados.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Modal de Edición -->
    <div class="modal fade" id="editStudentModal" tabindex="-1" aria-labelledby="editStudentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editStudentModalLabel">Editar Estudiante</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editStudentForm" method="post">
                    <?= csrf_field() ?>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="edit_id">
                        <div class="mb-3">
                            <label for="edit_nest" class="form-label">Nombre Completo</label>
                            <input type="text" class="form-control" id="edit_nest" name="nombre_estudiante" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_dni" class="form-label">DNI</label>
                            <input type="text" class="form-control" id="edit_dni" name="dni" pattern="\d{8}" maxlength="8" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_edad" class="form-label">Edad</label>
                            <input type="text" class="form-control" id="edit_edad" name="edad" maxlength="2" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="edit_email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_fecha_nac" class="form-label">Fecha de Nacimiento</label>
                            <input type="date" class="form-control" id="edit_fecha_nac" name="fecha_nac">
                        </div>
                        <div class="mb-3">
                            <label for="edit_carrera_id" class="form-label">Carrera</label>
                            <select class="form-select" id="edit_carrera_id" name="carrera_id">
                                <option value="">Seleccione una carrera</option>
                                <?php if(isset($carreras) && count($carreras) > 0): ?>
                                    <?php foreach($carreras as $car): ?>
                                        <option value="<?= esc($car['id']) ?>">
                                            <?= esc($car['nombre_carrera']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>    
    
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