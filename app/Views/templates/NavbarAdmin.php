<!-- Navbar Administrador -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top py-4 border-0" id="mainNav">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center w-100">
            <!-- Lado Izquierdo: Instituto Superior 57 -->
            <a class="navbar-brand d-flex align-items-center text-white" href="<?= base_url('/') ?>">
                <i class="fas fa-university me-2"></i>
                <span>Instituto Superior 57</span>
            </a>

            <!-- Derecha: Panel de Administrador -->
            <div class="navbar-text ms-auto d-flex align-items-center">
                <a href="<?= base_url('administrador/administradores'); ?>" class="text-white text-decoration-none">
                    <span class="h5 mb-0">Panel de Administrador</span>
                </a>
                <a href="<?= base_url('logout'); ?>" class="btn btn-danger btn-sm ms-4 fw-bold logout-btn">
                    <i class="fas fa-sign-out-alt me-1"></i> Cerrar Sesión
                </a>
            </div>
        </div>
    </div>
</nav>

<!-- Pestañas debajo del navbar -->
<style>
#adminTabs .nav-link {
    border: none !important;
    font-size: 1.1rem !important;
    border-radius: 8px 8px 0 0 !important;
    margin: 0 2px;
}
#adminTabs .nav-link.active {
    border: 1px solid #ccc !important;
    border-bottom: none !important;
    background-color: #f8f9fa !important;
    color: #000 !important;
    border-radius: 8px 8px 0 0 !important;
    margin-bottom: -1px;
}
</style>
<nav class="pb-4 bg-dark">
    <div class="container-fluid bg-dark">
        <ul class="nav justify-content-center bg-dark text-white" id="adminTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link text-white <?= (uri_string() == 'administrador/estudiantes') ? 'active bg-light text-dark' : '' ?>" href="<?= base_url('administrador/estudiantes'); ?>" role="tab" aria-selected="false">Estudiantes</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link text-white <?= (uri_string() == 'administrador/profesores') ? 'active bg-light text-dark' : '' ?>" href="<?= base_url('administrador/profesores'); ?>" role="tab" aria-selected="false">Profesores</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link text-white <?= (uri_string() == 'administrador/carreras') ? 'active bg-light text-dark' : '' ?>" href="<?= base_url('administrador/carreras'); ?>" role="tab" aria-selected="false">Carreras</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link text-white <?= (uri_string() == 'administrador/categorias') ? 'active bg-light text-dark' : '' ?>" href="<?= base_url('administrador/categorias'); ?>" role="tab" aria-selected="false">Categorías</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link text-white <?= (uri_string() == 'administrador/modalidades') ? 'active bg-light text-dark' : '' ?>" href="<?= base_url('administrador/modalidades'); ?>" role="tab" aria-selected="false">Modalidades</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link text-white <?= (uri_string() == 'administrador/materias') ? 'active bg-light text-dark' : '' ?>" href="<?= base_url('administrador/materias'); ?>" role="tab" aria-selected="false">Materias</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link text-white <?= (uri_string() == 'administrador/rol') ? 'active bg-light text-dark' : '' ?>" href="<?= base_url('administrador/rol'); ?>" role="tab" aria-selected="false">Roles</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link text-white <?= (uri_string() == 'administrador/usuarios') ? 'active bg-light text-dark' : '' ?>" href="<?= base_url('administrador/usuarios'); ?>" role="tab" aria-selected="false">Usuarios</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link text-white <?= (uri_string() == 'administrador/alertas') ? 'active bg-light text-dark' : '' ?>" href="<?= base_url('administrador/alertas'); ?>" role="tab" aria-selected="false">
                    Alertas
                    <span class="badge bg-danger ms-1 d-none" id="alerta-contador">0</span>
                </a>
            </li>
        </ul>
    </div>
</nav>
