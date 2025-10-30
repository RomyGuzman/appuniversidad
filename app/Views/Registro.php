<?= $this->extend('templates/layout') ?>  <!-- Cambia a 'templates/layout' si está en subcarpeta -->

<?= $this->section('content') ?>
<style>
    /* Estilos personalizados basados en tu styles.css, adaptados para el formulario */
    :root {
        --primary-color: #3498db;
        --secondary-color: #2980b9;
        --success-color: #2ecc71;
        --danger-color: #e74c3c;
        --light-color: #f8f9fa;
        --border-color: #dee2e6;
    }

    .registro-container {
        max-width: 800px;
        margin: 0 auto;
        background-color: white;
        border-radius: 6px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        padding: 20px;
    }

    .registro-form {
        background-color: var(--light-color);
        padding: 20px;
        border-radius: 6px;
        border: 1px solid var(--border-color);
    }

    .registro-form .form-label {
        font-weight: 600;
        color: #555;
    }

    .registro-form .form-control, .registro-form .form-select {
        border: 1px solid var(--border-color);
        border-radius: 4px;
    }

    .registro-form .btn-primary {
        background-color: var(--primary-color);
        border: none;
        transition: background-color 0.3s;
    }

    .registro-form .btn-primary:hover {
        background-color: var(--secondary-color);
    }

    .alert {
        border-radius: 4px;
    }

    @media (max-width: 768px) {
        .registro-container {
            padding: 15px;
        }
        .registro-form {
            padding: 15px;
        }
    }
</style>

<div class="registro-container mt-5">
    <h1 class="text-center mb-4" style="color: var(--primary-color);">Registro de Estudiante</h1>
    <?php if (session()->has('errors')): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach (session('errors') as $error): ?>
                    <li><?= $error ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    <?php if (session()->has('success')): ?>
        <div class="alert alert-success">
            <?= session('success') ?>
        </div>
    <?php endif; ?>
    <form action="<?= base_url('registro') ?>" method="post" class="registro-form">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="dni" class="form-label">DNI (8 dígitos):</label>
                <input type="text" name="dni" id="dni" class="form-control" pattern="[0-9]{8}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="nombre_estudiante" class="form-label">Nombre Completo:</label>
                <input type="text" name="nombre_estudiante" id="nombre_estudiante" class="form-control" required>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento:</label>
                <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>
        </div>
        <div class="mb-3">
            <label for="carrera" class="form-label">Carrera:</label>
            <select name="carrera_id" id="carrera" class="form-select" required>
                <option value="">Selecciona una carrera</option>
                <?php foreach ($carreras as $carrera): ?>
                    <option value="<?= $carrera['id'] ?>"><?= $carrera['nombre_carrera'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="modalidad" class="form-label">Modalidad:</label>
                <select name="modalidad_id" id="modalidad" class="form-select" required>
                    <option value="">Selecciona una modalidad</option>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label for="categoria" class="form-label">Categoría:</label>
                <select name="categoria_id" id="categoria" class="form-select" required>
                    <option value="">Selecciona una categoría</option>
                </select>
            </div>
        </div>
        <button type="submit" class="btn btn-primary w-100">Registrar</button>
    </form>
</div>

<script>
    document.getElementById('carrera').addEventListener('change', function() {
        const carreraId = this.value;
        // Cargar modalidades
        fetch(`<?= base_url('registro/get-modalidades') ?>/${carreraId}`)
            .then(response => response.json())
            .then(data => {
                const select = document.getElementById('modalidad');
                select.innerHTML = '<option value="">Selecciona una modalidad</option>';
                data.forEach(item => {
                    select.innerHTML += `<option value="${item.id}">${item.nombre_modalidad}</option>`;
                });
            })
            .catch(error => console.error('Error cargando modalidades:', error));
        // Cargar categorías
        fetch(`<?= base_url('registro/get-categorias') ?>/${carreraId}`)
            .then(response => response.json())
            .then(data => {
                const select = document.getElementById('categoria');
                select.innerHTML = '<option value="">Selecciona una categoría</option>';
                data.forEach(item => {
                    select.innerHTML += `<option value="${item.id}">${item.nombre_categoria}</option>`;
                });
            })
            .catch(error => console.error('Error cargando categorías:', error));
    });
</script>
<?= $this->endSection() ?>