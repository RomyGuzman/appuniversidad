# Flujo de Login - Guía Didáctica

¡Hola! Esta guía explica paso a paso cómo funciona el sistema de login en tu aplicación CodeIgniter. Lo explicaré de manera simple, como si estuvieras aprendiendo por primera vez. Vamos a ver todas las partes involucradas: vistas, controladores, modelos, rutas y JavaScript.

## ¿Qué es el Login?

El login es el proceso donde un usuario ingresa su nombre de usuario y contraseña para acceder a la aplicación. Es como mostrar tu identificación en una puerta: si es correcta, entras; si no, te quedas afuera.

## Partes del Sistema de Login

### 1. La Vista (Lo que ve el usuario)

**Archivo: `app/Views/login.php`**

Esta es la página que ve el usuario. Es como el formulario de entrada a un edificio.

```php
<form action="<?= base_url('login') ?>" method="post" autocomplete="off">
    <input type="text" name="login_identifier" autocomplete="off" required>
    <input type="password" name="password" autocomplete="off" required>
    <button type="submit">Ingresar</button>
</form>
```

**¿Qué hace?**
- Muestra dos campos: uno para el usuario/email y otro para la contraseña
- Tiene `autocomplete="off"` para que el navegador no complete automáticamente los campos
- Cuando el usuario hace clic en "Ingresar", envía los datos al servidor

### 2. Las Rutas (El mapa de direcciones)

**Archivo: `app/Config/Routes.php`**

Las rutas son como las direcciones de una ciudad. Le dicen a CodeIgniter a dónde ir cuando alguien pide una página.

```php
$routes->get('login', 'Auth::login');           // Mostrar formulario
$routes->post('login', 'Auth::attemptLogin');   // Procesar login
$routes->get('logout', 'Auth::logout');         // Cerrar sesión
```

**¿Qué hace?**
- Cuando alguien va a `/login` con GET, muestra el formulario
- Cuando envían el formulario a `/login` con POST, procesa el login
- Cuando van a `/logout`, cierra la sesión

### 3. El Controlador (El portero)

**Archivo: `app/Controllers/Auth.php`**

El controlador es como el portero del edificio. Decide quién entra y quién no.

#### Método `login()`
```php
public function login()
{
    if (session()->get('isLoggedIn')) {
        return redirect()->to($this->getDashboardRedirect(session()->get('rol_id')));
    }
    return view('login');
}
```

**¿Qué hace?**
- Si el usuario ya está logueado, lo manda a su panel
- Si no, muestra la vista del login

#### Método `attemptLogin()`
```php
public function attemptLogin()
{
    // 1. Validar campos vacíos
    $rules = [
        'login_identifier' => 'required',
        'password' => 'required'
    ];

    if (!$this->validate($rules)) {
        return redirect()->to('/login')->with('errors', $this->validator->getErrors());
    }

    // 2. Buscar usuario
    $usuario = $usuarioModel->where('usuario', $identifier)->first();

    // 3. Verificar contraseña
    if ($usuario && md5($password) === $usuario['password']) {
        $this->setUserSession($usuario);
        return redirect()->to($this->getDashboardRedirect($usuario['rol_id']));
    }

    return redirect()->to('/login')->with('error', 'Usuario o contraseña incorrectos.');
}
```

**¿Qué hace?**
1. **Valida**: Comprueba que los campos no estén vacíos
2. **Busca**: Encuentra al usuario en la base de datos
3. **Verifica**: Compara la contraseña (ver más abajo sobre el hash)
4. **Crea sesión**: Si todo está bien, guarda datos en la sesión
5. **Redirige**: Manda al usuario a su panel correspondiente

#### Método `logout()`
```php
public function logout()
{
    session()->destroy();
    return redirect()->to('/');
}
```

**¿Qué hace?**
- Borra toda la información de la sesión
- Manda al usuario a la página de inicio

### 4. El Modelo (El archivo de registros)

**Archivo: `app/Models/UsuarioModel.php`**

El modelo es como el archivo donde están guardados todos los usuarios. Es la conexión con la base de datos.

```php
class UsuarioModel extends Model
{
    protected $table = 'usuarios';
    protected $primaryKey = 'id';
    protected $allowedFields = ['usuario', 'password', 'rol_id', 'activo', 'fecha_ultimo_ingreso'];
}
```

**¿Qué hace?**
- Define qué tabla usar (`usuarios`)
- Especifica qué campos se pueden modificar
- Proporciona métodos para buscar, guardar, actualizar usuarios

### 5. JavaScript (Funciones extra)

**Archivo: `public/app.js`**

Este archivo contiene funciones JavaScript que ayudan con las interacciones del usuario, especialmente en la gestión de usuarios (no directamente en el login, pero relacionado).

**¿Qué hace?**
- Maneja la edición de usuarios en la tabla
- Envía datos al servidor sin recargar la página
- Muestra mensajes de éxito o error

## El Proceso Completo Paso a Paso

1. **Usuario llega**: Va a `/login` → Controlador `Auth::login()` muestra el formulario

2. **Usuario ingresa datos**: Escribe usuario y contraseña, hace clic en "Ingresar"

3. **Servidor recibe**: Ruta POST `/login` → Controlador `Auth::attemptLogin()`

4. **Validación**: Controlador comprueba que los campos no estén vacíos

5. **Búsqueda**: Modelo `UsuarioModel` busca al usuario en la base de datos

6. **Verificación de contraseña**: Compara la contraseña hasheada (ver abajo)

7. **Éxito**: Si coincide, crea sesión y redirige al panel correcto
   - Admin (rol 1-2) → `/administrador/usuarios`
   - Profesor (rol 3) → `/profesores/dashboard`
   - Estudiante (rol 4) → `/estudiantes/dashboard`

8. **Fallo**: Si no coincide, vuelve al login con mensaje de error

## Sobre el Hash de Contraseñas

### ¿Qué es el Hash?

El hash es como convertir una contraseña en un código secreto. Por ejemplo:
- Contraseña: "123456"
- Hash MD5: "e10adc3949ba59abbe56e057f20f883e"

**¿Por qué se hace?**
- Si alguien roba la base de datos, no puede ver las contraseñas reales
- Dos contraseñas iguales dan el mismo hash
- No se puede "deshashear" (volver atrás)

### ¿Cuándo se hashea?

1. **Al registrar usuario**: La contraseña se hashea antes de guardarse en la base de datos
2. **Al hacer login**: La contraseña que ingresa el usuario se hashea y se compara con el hash guardado

### ¿Es MD5 la mejor manera?

**NO, MD5 NO es seguro.** Aquí te explico por qué:

**Problemas de MD5:**
- Es muy viejo (de 1991)
- Los hackers pueden "crackearlo" fácilmente con diccionarios rainbow
- No tiene "salt" (un valor extra que hace más seguro el hash)

**¿Qué deberías usar en su lugar?**
```php
// En lugar de md5($password)
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Para verificar
if (password_verify($password, $hashedPassword)) {
    // Contraseña correcta
}
```

**¿Por qué es mejor?**
- Es más moderno y seguro
- Incluye salt automáticamente
- Se puede actualizar fácilmente si aparecen nuevas vulnerabilidades

### ¿Está bien lo que tienes ahora?

**Para desarrollo/pruebas: SÍ, está bien.**
- Funciona para probar el flujo
- Es simple de entender

**Para producción: NO, cambia a `password_hash()` lo antes posible.**
- MD5 es inseguro para datos reales
- Podrías tener problemas legales si hay una brecha de seguridad

## Resumen

El flujo de login es:
1. **Vista** muestra formulario
2. **Usuario** ingresa datos
3. **Controlador** valida y busca usuario
4. **Modelo** consulta base de datos
5. **Hash** verifica contraseña
6. **Sesión** se crea si es correcto
7. **Redirección** al panel apropiado

¡Espero que esta explicación te haya ayudado a entender cómo funciona todo! Si tienes dudas sobre alguna parte específica, pregúntame.

************************************************************************************************************************************************************************************************************************************************************************************************************************

## RECOMENDACIONES Y CRÍTICAS SOBRE MD5

### ¿Es MD5 la mejor manera?

**NO, MD5 NO es seguro.** Aquí te explico por qué:

**Problemas de MD5:**
- Es muy viejo (de 1991)
- Los hackers pueden "crackearlo" fácilmente con diccionarios rainbow
- No tiene "salt" (un valor extra que hace más seguro el hash)

**¿Qué deberías usar en su lugar?**
```php
// En lugar de md5($password)
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Para verificar
if (password_verify($password, $hashedPassword)) {
    // Contraseña correcta
}
```

**¿Por qué es mejor?**
- Es más moderno y seguro
- Incluye salt automáticamente
- Se puede actualizar fácilmente si aparecen nuevas vulnerabilidades

### ¿Está bien lo que tienes ahora?

**Para desarrollo/pruebas: SÍ, está bien.**
- Funciona para probar el flujo
- Es simple de entender

**Para producción: NO, cambia a `password_hash()` lo antes posible.**
- MD5 es inseguro para datos reales
- Podrías tener problemas legales si hay una brecha de seguridad

### Consejos para Mejorar

1. **Cambia el hash**: Usa `password_hash()` y `password_verify()`
2. **Agrega intentos limitados**: Después de 3 intentos fallidos, bloquea por un tiempo
3. **Usa HTTPS**: Para que las contraseñas viajen encriptadas
4. **Logs de seguridad**: Registra intentos de login fallidos
5. **2FA**: Autenticación de dos factores para más seguridad
************************************************************************************************************************************************************************************************************************************************************************************************************************
