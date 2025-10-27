# Explicación Detallada del Flujo de Login

Este documento explica paso a paso cómo funciona el sistema de login en la aplicación CodeIgniter, desde la perspectiva del frontend hasta el backend, incluyendo el proceso de hashing de contraseñas.

## Arquitectura General del Login

El sistema de login sigue el patrón MVC (Modelo-Vista-Controlador) de CodeIgniter, con rutas definidas y funciones JavaScript auxiliares.

## 1. Vista (Frontend) - `app/Views/login.php`

**Ubicación:** `app/Views/login.php`

Esta es la interfaz que ve el usuario. Es un formulario HTML con Bootstrap para el diseño.

### Estructura del Formulario:
```php
<form action="<?= base_url('login') ?>" method="post" autocomplete="off">
    <?= csrf_field() ?>
    <div class="mb-3">
        <label for="login_identifier" class="form-label">Email o Nombre de Usuario</label>
        <input type="text" class="form-control" id="login_identifier" name="login_identifier" required readonly onfocus="this.removeAttribute('readonly');">
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Contraseña:</label>
        <input type="password" class="form-control" id="password" name="password" required readonly onfocus="this.removeAttribute('readonly');">
    </div>
    <button type="submit" class="btn btn-primary">Ingresar</button>
</form>
```

### Características de Seguridad:
- `autocomplete="off"` - Evita que el navegador complete automáticamente los campos
- `readonly onfocus="this.removeAttribute('readonly');"` - Previene autocompletado agresivo
- `<?= csrf_field() ?>` - Protección CSRF de CodeIgniter

## 2. Rutas - `app/Config/Routes.php`

**Ubicación:** `app/Config/Routes.php`

Las rutas definen cómo se accede al sistema de login:

```php
// --- RUTAS DE LOGIN (usando el controlador Auth inteligente) ---
$routes->get('login', 'Auth::login', ['as' => 'login']);
$routes->post('login', 'Auth::attemptLogin');
$routes->get('logout', 'Auth::logout', ['as' => 'logout']);
```

- **GET /login** → Muestra el formulario de login
- **POST /login** → Procesa el intento de login
- **GET /logout** → Cierra la sesión

## 3. Controlador - `app/Controllers/Auth.php`

**Ubicación:** `app/Controllers/Auth.php`

El controlador maneja toda la lógica del login.

### Método `login()` - Mostrar Formulario
```php
public function login()
{
    // Si el usuario ya está logueado, lo redirigimos a su dashboard.
    if (session()->get('isLoggedIn')) {
        return redirect()->to($this->getDashboardRedirect(session()->get('rol_id')));
    }
    return view('login'); // Asegúrate de tener una vista en app/Views/login.php
}
```

**Qué hace:**
- Verifica si el usuario ya está logueado
- Si está logueado, lo redirige a su dashboard según su rol
- Si no, muestra la vista `login.php`

### Método `attemptLogin()` - Procesar Login
```php
public function attemptLogin()
{
    // 1. Validar que los campos no estén vacíos
    $rules = [
        'login_identifier' => 'required',
        'password'         => 'required'
    ];

    if (!$this->validate($rules)) {
        return redirect()->to('/login')->with('errors', $this->validator->getErrors());
    }

    $identifier = $this->request->getPost('login_identifier');
    $password = $this->request->getPost('password');

    $usuarioModel = new UsuarioModel();

    // 2. Buscar usuario
    $usuario = $usuarioModel->where('usuario', $identifier)->first();

    // 3. Verificar contraseña y crear sesión
    if ($usuario && md5((string)$password) === $usuario['password']) {
        // ¡Login exitoso!
        $this->setUserSession($usuario);

        // Actualizar fecha de último ingreso
        $usuarioModel->update($usuario['id'], ['fecha_ultimo_ingreso' => date('Y-m-d H:i:s')]);

        // Redirigir al dashboard correspondiente
        return redirect()->to($this->getDashboardRedirect($usuario['rol_id']));
    }

    return redirect()->to('/login')->with('error', 'Usuario o contraseña incorrectos.');
}
```

**Flujo del método:**
1. **Validación**: Verifica que los campos no estén vacíos
2. **Búsqueda**: Busca el usuario en la base de datos por nombre de usuario
3. **Verificación de contraseña**: Compara el hash MD5 de la contraseña
4. **Sesión**: Si es correcto, crea la sesión del usuario
5. **Actualización**: Registra la fecha del último ingreso
6. **Redirección**: Envía al usuario a su dashboard según su rol

### Método `logout()` - Cerrar Sesión
```php
public function logout()
{
    session()->destroy();
    return redirect()->to('/')->with('success', 'Has cerrado sesión correctamente.');
}
```

**Qué hace:**
- Destruye toda la información de la sesión
- Redirige a la página de inicio

### Método `setUserSession()` - Crear Sesión
```php
private function setUserSession($usuario)
{
    $rolModel = new RolModel();
    $rol = $rolModel->find($usuario['rol_id']);

    $data = [
        'id_usuario' => $usuario['id'],
        'usuario'    => $usuario['usuario'],
        'rol_id'     => $usuario['rol_id'],
        'nombre_rol' => $rol ? $rol['nombre_rol'] : 'Desconocido',
        'isLoggedIn' => true,
    ];

    session()->set($data);
}
```

**Qué guarda en sesión:**
- ID del usuario
- Nombre de usuario
- ID del rol
- Nombre del rol
- Flag de login exitoso

### Método `getDashboardRedirect()` - Redirección por Rol
```php
private function getDashboardRedirect($rol_id)
{
    switch ($rol_id) {
        case 1: // admin
        case 4: // Superadmin
            return 'administrador/usuarios';
        case 2: // profesor
            return 'profesores/dashboard';
        case 3: // alumno
            return 'estudiantes/dashboard';
        default:
            return '/';
    }
}
```

**Redirecciones por rol:**
- Rol 1 y 4 (Admin/Superadmin) → `/administrador/usuarios`
- Rol 2 (Profesor) → `/profesores/dashboard`
- Rol 3 (Alumno) → `/estudiantes/dashboard`

## 4. Modelo - `app/Models/UsuarioModel.php`

**Ubicación:** `app/Models/UsuarioModel.php`

El modelo define cómo interactuar con la tabla de usuarios.

```php
class UsuarioModel extends Model
{
    protected $table      = 'Usuarios';
    protected $primaryKey = 'id';
    protected $allowedFields = ['usuario', 'password', 'fecha_registro', 'fecha_ultimo_ingreso', 'rol_id', 'activo'];
    protected $useTimestamps = false;

    protected $validationRules = [
        'usuario' => 'required|min_length[3]|max_length[50]',
        'password' => 'required|min_length[6]',
        'rol_id' => 'required|integer',
        'activo' => 'permit_empty|in_list[0,1]',
    ];
}
```

**Campos permitidos:**
- `usuario` - Nombre de usuario
- `password` - Contraseña hasheada
- `fecha_registro` - Fecha de registro
- `fecha_ultimo_ingreso` - Último login
- `rol_id` - ID del rol
- `activo` - Estado del usuario

## 5. JavaScript - `public/app.js`

**Ubicación:** `public/app.js`

El login usa muy poco JavaScript, solo para mejorar la UX.

### Función de Limpieza de Campos
```javascript
// Usamos el evento 'pageshow' que se dispara cada vez que la página se muestra,
// incluyendo cuando se usa el botón "Atrás" del navegador.
window.addEventListener('pageshow', function(event) {
    document.getElementById('login_identifier').value = '';
    document.getElementById('password').value = '';
});
```

**Qué hace:**
- Limpia los campos de usuario y contraseña cuando se carga la página
- Funciona incluso cuando el usuario usa el botón "Atrás" del navegador

### Función de Confirmación de Logout
```javascript
// --- Lógica para confirmación de cierre de sesión ---
// Usa delegación de eventos para funcionar en cualquier navbar
$('body').on('click', '.logout-btn', function(e) {
    e.preventDefault(); // Previene la navegación inmediata
    const href = $(this).attr('href'); // Obtiene la URL del enlace

    Swal.fire({
        title: '¿Estás seguro?',
        text: "Estás a punto de cerrar tu sesión.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, cerrar sesión',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = href; // Navega si el usuario confirma
        }
    });
});
```

**Qué hace:**
- Muestra una confirmación con SweetAlert antes de cerrar sesión
- Solo redirige si el usuario confirma

## 6. Proceso de Hashing de Contraseñas

### Cómo se Hashea

**En el Login (Verificación):**
```php
if ($usuario && md5((string)$password) === $usuario['password']) {
    // Login exitoso
}
```

**En el Registro (debe hacerse en otro controlador):**
```php
// Ejemplo de cómo debería hacerse (NO se hace en este código actual)
$hashedPassword = md5($password);
$usuarioModel->insert([
    'usuario' => $username,
    'password' => $hashedPassword,
    // ... otros campos
]);
```

### Problemas con MD5

**¿Por qué MD5 es inseguro?**
- Fue creado en 1991
- Es vulnerable a ataques de rainbow tables
- No usa salt (valor adicional que hace más seguro el hash)
- Hackers pueden crackearlo fácilmente

**¿Qué debería usarse en su lugar?**
```php
// Para hashear (registro)
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Para verificar (login)
if (password_verify($password, $hashedPassword)) {
    // Contraseña correcta
}
```

**Ventajas de `password_hash()`:**
- Algoritmo moderno (bcrypt por defecto)
- Incluye salt automáticamente
- Se puede actualizar cuando aparezcan vulnerabilidades
- Más lento (lo cual es bueno contra ataques de fuerza bruta)

## Flujo Completo Paso a Paso

1. **Usuario accede a `/login`** (GET)
   - Controlador `Auth::login()` verifica si ya está logueado
   - Si no, muestra `app/Views/login.php`

2. **Usuario llena el formulario y hace submit**
   - Form envía POST a `/login`
   - Controlador `Auth::attemptLogin()` recibe los datos

3. **Validación inicial**
   - Verifica que campos no estén vacíos
   - Si faltan campos, redirige con errores

4. **Búsqueda en base de datos**
   - `UsuarioModel` busca usuario por nombre de usuario
   - Si no existe, login falla

5. **Verificación de contraseña**
   - Hashea la contraseña con `md5($password)`
   - Compara con el hash guardado en BD
   - Si no coincide, login falla

6. **Login exitoso**
   - Crea sesión con `setUserSession()`
   - Actualiza `fecha_ultimo_ingreso`
   - Redirige según rol del usuario

7. **Logout**
   - Usuario hace clic en logout
   - JS muestra confirmación con SweetAlert
   - Si confirma, va a `/logout`
   - Controlador destruye sesión y redirige a `/`

## Archivos Involucrados

- **Vista:** `app/Views/login.php`
- **Controlador:** `app/Controllers/Auth.php`
- **Modelo:** `app/Models/UsuarioModel.php`
- **Rutas:** `app/Config/Routes.php`
- **JavaScript:** `public/app.js` (funciones auxiliares)
- **Modelo de Rol:** `app/Models/RolModel.php` (para obtener nombre del rol)

## Recomendaciones de Seguridad

1. **Cambiar MD5 por `password_hash()`** lo antes posible
2. **Implementar intentos limitados** (bloquear después de X fallos)
3. **Usar HTTPS** en producción
4. **Logs de seguridad** para intentos fallidos
5. **2FA** (autenticación de dos factores) para admins
6. **Validar contraseñas fuertes** en el registro

## Resumen Ejecutivo

El login funciona así:
- **Frontend:** Formulario en `login.php` con protección CSRF
- **Backend:** Controlador `Auth` valida, busca usuario, verifica hash MD5
- **Base de datos:** Tabla `Usuarios` con contraseñas hasheadas
- **Sesión:** CodeIgniter maneja la sesión del usuario
- **Redirección:** Según rol (admin/profesor/estudiante)
- **JS:** Solo UX (limpiar campos, confirmar logout)

**Punto crítico:** El uso de MD5 es inseguro para producción. Debe cambiarse a `password_hash()` inmediatamente.
