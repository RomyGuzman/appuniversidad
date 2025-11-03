<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Registro y Gestión de Carreras</title>

  <!-- CSS de terceros -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" />

  <!-- DataTables Bootstrap 5 CSS -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" />
  
  <!-- Tu propio CSS -->
  <link rel="stylesheet" href="<?= base_url('styles.css') ?>" />
</head>

<body class="profesores-page">
  <!-- NAVBAR -->
  <?= view('templates/NavbarAdmin') ?>





  <!-- CABECERA -->
  <header class="bg-dark text-white py-5">
    <div class="container text-center ">
      <h1 class="display-4 fw-bold mb-4">Registro y Gestión de Carreras</h1>
      <p class="lead">Sistema de Gestión Académica Integral</p>
    </div>
  </header>

  <!-- CONTENIDO PRINCIPAL -->
  <main class="container my-5">
    <!-- FORMULARIO ALTA -->
    <section class="row justify-content-center mb-5">
      <div class="col-lg-9">
        <div class="card shadow">
          <div class="card-header bg-success text-white">
            <h2 class="h4 mb-0"><i class="fas fa-plus-circle me-2"></i>Registro de Carrera</h2>
          </div>
          <div class="card-body">
            <?php if (session()->has('errors')): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                    <?php foreach (session('errors') as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach ?>
                    </ul>
                </div>
            <?php endif ?>
            <form id="careerForm" action="<?= base_url('administrador/carreras/registrar') ?>" method="POST">
              <?= csrf_field() ?>
              <input type="hidden" id="careerId" />
              <div class="mb-3">
                <label for="registerName" class="form-label">Nombre de la Carrera</label>
                <input type="text" class="form-control" id="registerName" name="nombre_carrera" required />
              </div>
              <div class="row g-3">
                <div class="col-sm-6">
                  <label for="careerCode" class="form-label">Código de Carrera</label>
                  <input type="text" class="form-control" id="careerCode" name="codigo_carrera" placeholder="Se generará automáticamente" readonly /> 
                </div>
                <div class="col-sm-6">
                  <label for="careerDuration" class="form-label">Duración (años)</label>
                  <input type="number" class="form-control" id="careerDuration" name="duracion" min="1" />
                </div>
              </div>
              <div class="row g-3 mt-3">
                <div class="col-sm-6">
                  <label for="careerCategory" class="form-label">Categoría</label>
                  <!-- CORRECCIÓN: Se ajusta el 'name' a 'id_categoria' y los campos del bucle a 'id_cat' y 'ncat' -->
                  <select id="careerCategory" name="id_categoria" class="form-select" required>
                    <option value="">-- Seleccionar Categoría --</option>
                    <?php if (!empty($categorias)): ?>
                        <?php foreach ($categorias as $categoria): ?>
                            <option value="<?= esc($categoria['id']) ?>">
                                <?= esc($categoria['nombre_categoria']) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                  </select>
                </div>
                <div class="col-sm-6">
                  <label for="careerModality" class="form-label">Modalidad</label>
                  <!-- CORRECCIÓN: Se ajusta el 'name' a 'id_modalidad' y los campos del bucle a 'id_mod' y 'nombre_modalidad' -->
                  <select id="careerModality" name="id_modalidad" class="form-select" required>
                    <option value="">-- Seleccionar Modalidad --</option>
                    <?php if (!empty($modalidades)): ?>
                        <?php foreach ($modalidades as $modalidad): ?>
                            <option value="<?= esc($modalidad['id']) ?>">
                                <?= esc($modalidad['nombre_modalidad']) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                  </select>
                </div>
              </div>

              <div class="text-end mt-4">
                <button type="submit" class="btn btn-success">
                  <i class="fas fa-save me-1"></i>Registrar
                </button>
                <!-- Botón cancelar eliminado -->
              </div>
            </form>
          </div>
        </div>
      </div>
    </section>

    <!-- BÚSQUEDA -->
    <section class="row justify-content-center mb-5">
      <div class="col-lg-9">
        <div class="card shadow">
          <div class="card-header bg-info text-white">
            <h2 class="h4 mb-0"><i class="fas fa-search me-2"></i>Buscar Carrera por ID</h2>
          </div>
          <div class="card-body">
            <form id="searchCareerForm" class="mb-3">
              <div class="input-group">
                  <input type="number" id="searchCareerId" class="form-control" placeholder="ID de la carrera" required>
                  <button type="submit" class="btn btn-primary">Buscar</button>
              </div>
            </form>
             
            <div id="getResult" class="mt-4"></div>
          </div>
        </div>
      </div>
    </section>

    <!-- LISTADO -->
    <section class="row justify-content-center">
      <div class="col-lg-9">
        <div class="card shadow">
          <div class="card-header bg-secondary text-white">
            <h3 class="h5 mb-0"><i class="fas fa-list me-2"></i>Carreras Registradas</h3>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover align-middle table-striped" id="careersTable">
                <thead class="table-dark">
                  <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Código</th>
                    <th>Acciones</th>
                  </tr>
                </thead>
                <tbody id="careersTableBody">
                    <?php if(isset($carreras) && count($carreras) > 0): ?>
                        <?php foreach($carreras as $car): ?>
                            <!-- CORRECCIÓN: Se usan los nombres de columna correctos de la BD ('id', 'nombre_carrera') -->
                            <tr>
                                <td><?= esc($car['id']) ?></td>
                                <td><?= esc($car['nombre_carrera']) ?></td>
                                <td><?= esc($car['codigo_carrera']) ?></td>
                                 <td>
                                    <button class="btn btn-warning btn-sm edit-car-btn" data-id="<?= esc($car['id']) ?>" data-bs-toggle="modal" data-bs-target="#editCareerModal">
                                        <i class="fas fa-pencil-alt"></i>
                                    </button>
                                    <form action="<?= base_url('carreras/delete/' . $car['id']) ?>" method="POST" class="d-inline delete-form">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">No hay carreras registradas.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>

  <!-- Modal de Edición de Carrera -->
    <div class="modal fade" id="editCareerModal" tabindex="-1" aria-labelledby="editCareerModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCareerModalLabel">Editar Carrera</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editCareerForm" method="post">
                    <?= csrf_field() ?>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="edit_id_car">
                        <div class="mb-3">
                            <label for="edit_ncar" class="form-label">Nombre de la Carrera</label>
                            <input type="text" class="form-control" id="edit_ncar" name="nombre_carrera" required>
                        </div>
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <label for="edit_codcar" class="form-label">Código</label> <!-- CORRECCIÓN: Código no editable -->
                                <input type="text" class="form-control" id="edit_codcar" name="codigo_carrera" readonly>
                            </div>
                            <div class="col-sm-6">
                                <label for="edit_duracion" class="form-label">Duración (años)</label>
                                <input type="number" class="form-control" id="edit_duracion" name="duracion" min="1">
                            </div>
                        </div>
                        <div class="row g-3 mt-3">
                            <div class="col-sm-6">
                                <label for="edit_categoria" class="form-label">Categoría</label>
                                <select id="edit_categoria" name="id_categoria" class="form-select">
                                    <option value="">-- Seleccionar Categoría --</option>
                                    <?php if (!empty($categorias)): ?>
                                        <?php foreach ($categorias as $categoria): ?>
                                            <option value="<?= esc($categoria['id']) ?>">
                                                <?= esc($categoria['nombre_categoria']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label for="edit_modalidad" class="form-label">Modalidad</label>
                                <select id="edit_modalidad" name="id_modalidad" class="form-select">
                                    <option value="">-- Seleccionar Modalidad --</option>
                                    <?php if (!empty($modalidades)): ?>
                                        <?php foreach ($modalidades as $modalidad): ?>
                                            <option value="<?= esc($modalidad['id']) ?>">
                                                <?= esc($modalidad['nombre_modalidad']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
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


  <!-- FOOTER -->
  <footer class="bg-dark text-white py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5><i class="fas fa-university me-2"></i>Instituto Superior 57</h5>
                    <p class="mb-0">Formando profesionales desde 1990</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <h5>Contacto</h5>
                    <p class="mb-1"><i class="fas fa-phone me-2"></i> (123) 456-7890</p>
                    <p class="mb-0"><i class="fas fa-envelope me-2"></i> info@instituto.edu</p>
                </div>
            </div>
            <hr class="my-3" />
            <div class="text-center">
                <p class="mb-0">&copy; 2023 Gestión Instituto. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

  <!-- SCRIPTS externos -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


  <!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<!-- DataTables JS -->
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
