# Flujo Detallado del Login: Desde la Creación hasta el Acceso Exitoso

## Introducción
Este documento describe en detalle el flujo completo del proceso de login en la aplicación, desde el momento en que se crea la sesión hasta que el usuario se loguea exitosamente y puede acceder al sistema. Como un "dato" (un punto de referencia detallado), explico paso a paso por dónde voy (el flujo), cómo cambio de un lado a otro (transiciones entre componentes), cómo vuelvo (retrocesos o redirecciones en caso de error), y adónde (los destinos finales o intermedios).

El flujo se basa en el controlador `Auth` de CodeIgniter, que maneja la autenticación de manera inteligente. Asumimos que el usuario intenta acceder a una página protegida o directamente a la ruta de login.

## Paso 1: Inicio del Flujo - Creación de la Sesión
- **Dónde estoy**: El usuario llega a la aplicación, por ejemplo, intentando acceder a `/administrador/alertas` o cualquier ruta protegida.
- **Qué sucede**: CodeIgniter inicia automáticamente la sesión si no existe. La sesión se crea en el servidor (usando el driver configurado, como archivos o base de datos).
- **Cómo cambio**: Si la sesión no existe, se crea una nueva. Si existe pero está expirada, se regenera.
- **Adónde voy**: A la verificación de autenticación en el filtro o controlador.
- **Cómo vuelvo**: Si hay un error en la creación de la sesión (raro, pero posible por configuración), se redirige a una página de error 500.


***************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************


## Paso 2: Verificación de Autenticación
- **Dónde estoy**: En el filtro de autenticación (si está configurado en `app/Config/Filters.php`) o directamente en el controlador que requiere login.
- **Qué sucede**: Se verifica si el usuario está logueado. Esto se hace comprobando si existe una clave en la sesión (ej: `user_id` o `is_logged_in`).
- **Cómo cambio**: Si no está logueado, se redirige a la ruta de login (`/login`). Si está logueado, continúa al controlador solicitado.
- **Adónde voy**: Si no logueado, a la página de login. Si logueado, al dashboard correspondiente (ej: `/administrador/alertas`).
- **Cómo vuelvo**: Si el filtro falla (ej: error de base de datos), se redirige a una página de error. Si el usuario intenta acceder directamente a login estando logueado, se redirige al dashboard.


***************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************



## Paso 3: Página de Login - Carga de la Vista
- **Dónde estoy**: En la ruta `/login` (GET), manejada por `Auth::login`.
- **Qué sucede**: Se carga la vista de login (`app/Views/auth/login.php`). Se pasan datos como mensajes flash (éxito/error) y el token CSRF.
- **Cómo cambio**: Desde el navegador, el usuario ve el formulario de login. Si hay datos previos (ej: email recordado), se rellenan.
- **Adónde voy**: El usuario permanece en la página de login hasta que envíe el formulario.
- **Cómo vuelvo**: Si el usuario hace clic en "volver" o accede a otra URL, se redirige. Si hay un error de carga de vista, se muestra error 500.




***************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************



## Paso 4: Envío del Formulario de Login - Intento de Autenticación
- **Dónde estoy**: En la ruta `/login` (POST), manejada por `Auth::attemptLogin`.
- **Qué sucede**: Se valida el formulario (email, password, token CSRF). Se busca al usuario en la base de datos (usando `UsuarioModel`). Se verifica la contraseña con `password_verify`.
- **Cómo cambio**: Si la validación falla (ej: campos vacíos, CSRF inválido), se regresa a la vista de login con errores. Si el usuario no existe o la contraseña es incorrecta, se muestra mensaje de error. Si es exitoso, se establece la sesión.
- **Adónde voy**: En caso de éxito, se redirige al dashboard correspondiente (basado en el rol: estudiante, profesor, administrador). En caso de error, se regresa a la página de login.
- **Cómo vuelvo**: Si hay error, se redirige de vuelta a `/login` con mensajes flash. Si el usuario cancela (ej: cierra navegador), la sesión queda pendiente.


***************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************



## Paso 5: Establecimiento de la Sesión - Login Exitoso
- **Dónde estoy**: Dentro de `Auth::attemptLogin`, después de la verificación exitosa.
- **Qué sucede**: Se establece la sesión con datos del usuario (id, rol, nombre, etc.). Se regenera el ID de sesión por seguridad. Se establece un tiempo de expiración si está configurado.
- **Cómo cambio**: Desde la verificación de credenciales, se pasa a la configuración de sesión. Luego, se redirige.
- **Adónde voy**: Al dashboard del usuario (ej: `/estudiantes/dashboard` para estudiantes, `/administrador/alertas` para administradores).
- **Cómo vuelvo**: Si hay un error al guardar la sesión (ej: problema de almacenamiento), se redirige a login con error. No hay vuelta atrás una vez logueado exitosamente.


***************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************



## Paso 6: Acceso al Dashboard - Usuario Logueado
- **Dónde estoy**: En el dashboard correspondiente (ej: `/administrador/alertas`).
- **Qué sucede**: Se carga la vista del dashboard. La sesión mantiene al usuario logueado. Se pueden realizar acciones protegidas.
- **Cómo cambio**: Desde el login exitoso, se llega aquí. Si el usuario navega a otras páginas protegidas, permanece logueado.
- **Adónde voy**: El usuario puede navegar libremente dentro de las rutas permitidas para su rol.
- **Cómo vuelvo**: Si la sesión expira (por inactividad), se redirige automáticamente a login. Si el usuario hace logout, se destruye la sesión y se redirige a login.


***************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************



## Paso 7: Logout - Cierre de Sesión
- **Dónde estoy**: En la ruta `/logout`, manejada por `Auth::logout`.
- **Qué sucede**: Se destruye la sesión completamente. Se redirige a la página de inicio o login.
- **Cómo cambio**: Desde cualquier página, al hacer clic en logout, se ejecuta esto.
- **Adónde voy**: A la página de inicio (`/`) o login (`/login`).
- **Cómo vuelvo**: Una vez destruida la sesión, no hay vuelta; el usuario debe loguearse de nuevo.

## Consideraciones Adicionales
- **Transiciones**: Todas las transiciones usan redirecciones HTTP (`redirect()`) para evitar problemas de recarga.
- **Errores**: En caso de error (validación, base de datos), se usa `session()->setFlashdata()` para mensajes.
- **Seguridad**: Se usa CSRF en formularios, sesiones regeneradas, y verificación de roles.
- **Flujo Alterno**: Si el usuario intenta acceder a una ruta no autorizada, se redirige a login o a una página de acceso denegado.

Este flujo es cíclico: login -> acceso -> logout -> login, etc. Si necesitas más detalles en alguna parte, avísame.

¿Entendiste? Sí, he creado el documento detallado como pediste.
