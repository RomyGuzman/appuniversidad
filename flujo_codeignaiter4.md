# Flujo MVC en CodeIgniter 4 - Guía Didáctica

¡Hola! Esta guía explica paso a paso cómo funciona el patrón MVC (Modelo-Vista-Controlador) en tu aplicación CodeIgniter 4. Lo explicaré de manera simple y detallada, usando ejemplos de tu proyecto. Vamos a ver cómo interactúan los controladores, modelos y vistas para crear una aplicación web completa.

## ¿Qué es el Patrón MVC?

MVC es como organizar una cocina:
- **Modelo (Model)**: Es el refrigerador y la despensa. Guarda y administra los datos (como los ingredientes).
- **Vista (View)**: Es la presentación de los platos. Muestra la información al usuario (como servir la comida).
- **Controlador (Controller)**: Es el chef. Recibe las órdenes del cliente, usa los ingredientes del modelo y decide cómo presentar el plato en la vista.

## Partes del Sistema MVC en CodeIgniter 4

### 1. Las Rutas (El Menú del Restaurante)

**Archivo: `app/Config/Routes.php`**

Las rutas son como el menú del restaurante. Le dicen a CodeIgniter qué controlador usar para cada "plato" (página) que pide el usuario.

```php
// Ejemplo de rutas para usuarios
$routes->get('administrador/usuarios', 'Usuarios::index');        // Mostrar lista
$routes->post('usuarios/registrar', 'Usuarios::registrar');       // Crear usuario
$routes->get('usuarios/edit/(:num)', 'Usuarios::edit/$1');        // Obtener datos para editar
$routes->post('usuarios/update/(:num)', 'Usuarios::update/$1');   // Actualizar usuario
$routes->post('usuarios/delete/(:num)', 'Usuarios::delete/$1');   // Eliminar usuario
```

**¿Qué hace cada ruta?**
- `get` para mostrar páginas (como leer el menú)
- `post` para enviar formularios (como hacer un pedido)
- Los `(:num)` son parámetros variables (como elegir el número de plato)

### 2. El Controlador (El Chef)

**Archivo: `app/Controllers/Usuarios.php`**

El controlador es el chef que recibe el pedido y coordina todo.

```php
<?php
namespace App\Controllers;

use App\Models\UsuarioModel;
use App\Models\RolModel;

class Usuarios extends BaseController
{
    protected $usuarioModel;
    protected $rolModel;

    public function __construct()
    {
        $this->usuarioModel = new UsuarioModel();
        $this->rolModel = new RolModel();
    }

    public function index()
    {
        // 1. Pedir datos al modelo
        $data['usuarios'] = $this->usuarioModel->findAll();
        $data['roles'] = $this->rolModel->findAll();

        // 2. Pasar datos a la vista
        return view('administrador/usuarios', $data);
    }

    public function registrar()
    {
        // 1. Recibir datos del formulario
        $data = [
            'usuario' => $this->request->getPost('usuario'),
            'password' => md5($this->request->getPost('password')),
            'rol_id' => $this->request->getPost('rol_id'),
            'activo' => $this->request->getPost('activo') ? 1 : 0,
        ];

        // 2. Validar con el modelo
        $existingUser = $this->usuarioModel->where('usuario', $data['usuario'])->first();
        if ($existingUser) {
            return redirect()->back()->withInput()->with('errors', ['usuario' => 'El nombre de usuario ya existe.']);
        }

        // 3. Guardar en el modelo
        if ($this->usuarioModel->insert($data)) {
            return redirect()->to('/administrador/usuarios')->with('success', 'Usuario registrado exitosamente.');
        } else {
            return redirect()->back()->withInput()->with('errors', $this->usuarioModel->errors());
        }
    }
}
```

**¿Qué hace el controlador?**
1. **Recibe la petición**: Del navegador a través de las rutas
2. **Coordina**: Habla con los modelos para obtener/guardar datos
3. **Decide**: Qué vista mostrar y qué datos enviarle
4. **Responde**: Devuelve la vista renderizada al navegador

### 3. El Modelo (La Despensa y Refrigerador)

**Archivo: `app/Models/UsuarioModel.php`**

El modelo es donde están guardados todos los datos. Es la conexión con la base de datos.

```php
<?php
namespace App\Models;

use CodeIgniter\Model;

class UsuarioModel extends Model
{
    protected $table      = 'Usuarios';  // Nombre de la tabla
    protected $primaryKey = 'id';       // Clave primaria
    protected $allowedFields = [        // Campos que se pueden modificar
        'usuario',
        'password',
        'fecha_registro',
        'fecha_ultimo_ingreso',
        'rol_id',
        'activo'
    ];
    protected $useTimestamps = false;   // No usar timestamps automáticos

    // Reglas de validación
    protected $validationRules = [
        'usuario' => 'required|min_length[3]|max_length[50]',
        'password' => 'required|min_length[6]',
        'rol_id' => 'required|integer',
        'activo' => 'permit_empty|in_list[0,1]',
    ];

    protected $validationMessages = [
        'rol_id' => [
            'integer' => 'El rol debe ser un número entero.',
        ],
    ];
}
```

**¿Qué hace el modelo?**
- **Define la tabla**: Especifica con qué tabla de la base de datos trabajar
- **Campos permitidos**: Lista qué campos se pueden insertar/actualizar
- **Validación**: Reglas para comprobar que los datos sean correctos
- **Métodos CRUD**: `findAll()`, `insert()`, `update()`, `delete()`, etc.

### 4. La Vista (El Plato Servido)

**Archivo: `app/Views/administrador/usuarios.php`**

La vista es lo que ve el usuario final. Es el HTML que se envía al navegador.

```php
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Gestión de Usuarios</title>
    <!-- CSS y JavaScript -->
</head>
<body>
    <!-- Navbar -->
    <?= view('templates/NavbarAdmin') ?>

    <!-- Formulario de registro -->
    <form id="usuarioForm" method="post" action="<?= base_url('usuarios/registrar') ?>">
        <?= csrf_field() ?>
        <div class="mb-3">
            <label for="usuario">Nombre de Usuario</label>
            <input type="text" class="form-control" id="usuario" name="usuario" required>
        </div>
        <!-- Más campos... -->
        <button type="submit" class="btn btn-success">Registrar Usuario</button>
    </form>

    <!-- Tabla de usuarios -->
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Usuario</th>
                <th>Rol</th>
                <th>Activo</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if(isset($usuarios) && count($usuarios) > 0): ?>
                <?php foreach($usuarios as $usuario): ?>
                    <tr>
                        <td><?= esc($usuario['id']) ?></td>
                        <td><?= esc($usuario['usuario']) ?></td>
                        <!-- Más columnas... -->
                        <td>
                            <button class="btn btn-info btn-sm edit-btn" data-id="<?= esc($usuario['id']) ?>">
                                <i class="fas fa-pencil-alt"></i>
                            </button>
                            <form action="<?= base_url('usuarios/delete/' . $usuario['id']) ?>" method="post" class="d-inline">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="5" class="text-center">No hay usuarios registrados.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- JavaScript -->
    <script src="<?= base_url('app.js') ?>"></script>
</body>
</html>
```

**¿Qué hace la vista?**
- **Muestra datos**: Usa PHP para mostrar la información que envió el controlador
- **Formularios**: Para enviar datos de vuelta al controlador
- **Interactividad**: JavaScript para mejorar la experiencia del usuario
- **Plantillas**: Puede incluir otras vistas (como `NavbarAdmin`)

## El Proceso Completo MVC Paso a Paso

Vamos a seguir el flujo completo usando el ejemplo de "Mostrar Lista de Usuarios":

### 1. El Usuario Hace una Petición
- Usuario escribe en navegador: `http://localhost/administrador/usuarios`
- Navegador envía petición GET a CodeIgniter

### 2. CodeIgniter Busca la Ruta
- Revisa `Routes.php` y encuentra:
  ```php
  $routes->get('administrador/usuarios', 'Usuarios::index');
  ```
- Sabe que debe llamar al método `index()` del controlador `Usuarios`

### 3. El Controlador Toma el Control
```php
public function index()
{
    // Pide datos al modelo
    $data['usuarios'] = $this->usuarioModel->findAll();
    $data['roles'] = $this->rolModel->findAll();

    // Envía datos a la vista
    return view('administrador/usuarios', $data);
}
```

### 4. El Modelo Consulta la Base de Datos
- `UsuarioModel::findAll()` ejecuta: `SELECT * FROM Usuarios`
- `RolModel::findAll()` ejecuta: `SELECT * FROM Roles`
- Devuelve los datos al controlador

### 5. La Vista Renderiza el HTML
- CodeIgniter carga `app/Views/administrador/usuarios.php`
- PHP procesa el código y genera HTML
- Se incluyen los datos de `$data['usuarios']` y `$data['roles']`
- Se genera el HTML final

### 6. Respuesta al Usuario
- CodeIgniter envía el HTML completo al navegador
- El usuario ve la página con la lista de usuarios

## Flujo para Crear un Nuevo Usuario

### 1. Usuario Llena el Formulario
- En la vista `usuarios.php`, hay un formulario que apunta a `/usuarios/registrar`

### 2. Envío del Formulario
- Usuario hace clic en "Registrar Usuario"
- Formulario envía POST a `/usuarios/registrar`

### 3. Controlador Procesa
```php
public function registrar()
{
    $data = [
        'usuario' => $this->request->getPost('usuario'),
        'password' => md5($this->request->getPost('password')),
        // ...
    ];

    // Validar
    $existingUser = $this->usuarioModel->where('usuario', $data['usuario'])->first();

    // Guardar
    if ($this->usuarioModel->insert($data)) {
        return redirect()->to('/administrador/usuarios')->with('success', 'Usuario registrado exitosamente.');
    }
}
```

### 4. Modelo Valida y Guarda
- Verifica reglas de validación
- Si pasa, ejecuta `INSERT INTO Usuarios ...`
- Devuelve éxito o errores

### 5. Controlador Responde
- Si éxito: Redirige a la lista con mensaje de éxito
- Si error: Vuelve al formulario con errores

## Estructura de Archivos en tu Proyecto

```
app/
├── Controllers/          # Los chefs
│   ├── Usuarios.php
│   ├── Estudiantes.php
│   └── Profesores.php
├── Models/              # La despensa
│   ├── UsuarioModel.php
│   ├── EstudianteModel.php
│   └── ProfesorModel.php
├── Views/               # Los platos
│   ├── administrador/
│   │   └── usuarios.php
│   ├── estudiantes/
│   └── profesores/
└── Config/
    └── Routes.php       # El menú
```

## Conceptos Importantes

### BaseController
Todos tus controladores heredan de `BaseController`. Es como tener un chef maestro que enseña las técnicas básicas.

### Namespaces
```php
namespace App\Controllers;  // Estamos en la carpeta Controllers de App
use App\Models\UsuarioModel; // Importamos el modelo
```

### Inyección de Dependencias
```php
public function __construct()
{
    $this->usuarioModel = new UsuarioModel();
}
```
El controlador "inyecta" el modelo cuando se crea.

### Validación
Los modelos tienen reglas de validación para asegurar que los datos sean correctos antes de guardarlos.

### Sesiones y Flash Data
```php
return redirect()->to('/admin/usuarios')->with('success', 'Usuario registrado exitosamente.');
```
Guarda mensajes temporales en la sesión para mostrarlos después de redirigir.

## Resumen del Flujo MVC

1. **Petición** → Usuario pide una URL
2. **Ruta** → CodeIgniter encuentra qué controlador usar
3. **Controlador** → Coordina la lógica de negocio
4. **Modelo** → Accede a la base de datos
5. **Vista** → Genera el HTML para el usuario
6. **Respuesta** → Usuario ve la página

¡El MVC mantiene todo organizado y separado! Cada parte tiene su responsabilidad específica, lo que hace el código más fácil de mantener y entender.

¿Te gustaría que explique algún flujo específico de tu proyecto con más detalle?
