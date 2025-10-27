# Flujo Detallado del Login en Arquitectura MVC de CodeIgniter 4

Este documento explica el flujo completo del proceso de login en la aplicación, estructurado según la arquitectura Model-View-Controller (MVC) de CodeIgniter 4. Se detalla qué hace primero cada componente, el orden de ejecución, las interacciones entre ellos, y cómo se maneja cada paso sin dejar nada a la imaginación. El flujo se basa en el controlador `Auth`, el modelo `UsuarioModel`, y las vistas relacionadas.

## Arquitectura MVC en CodeIgniter 4: Resumen Rápido
- **Model (Modelo)**: Gestiona los datos y la lógica relacionada con la base de datos. No interactúa directamente con el usuario.
- **View (Vista)**: Presenta la interfaz de usuario. Recibe datos del Controller y los muestra.
- **Controller (Controlador)**: Recibe las solicitudes HTTP, valida datos, interactúa con el Model para obtener/modificar datos, y decide qué View mostrar o adónde redirigir.

El flujo sigue el patrón: **Request → Router → Controller → (Model) → View/Redirect**.

## Flujo Paso a Paso del Login

### Paso 1: Llegada de la Solicitud HTTP (Request)
- **Qué hace primero**: El usuario envía una solicitud HTTP (GET o POST) desde el navegador, por ejemplo, accediendo a `/login` o enviando el formulario de login.
- **Componente involucrado**: Ninguno de MVC directamente; es el punto de entrada de CodeIgniter.
- **Detalles**: CodeIgniter recibe la URL y parámetros. Si es una ruta protegida, se activa el filtro de autenticación (definido en `app/Config/Filters.php`), que verifica la sesión antes de llegar al Controller.
- **Orden**: Esto ocurre antes de cualquier componente MVC. Si la sesión no existe o está expirada, se crea una nueva sesión automáticamente.
- **Interacción**: No hay interacción MVC aún; es el "puente" al sistema.

### Paso 2: Enrutamiento (Router)
- **Qué hace primero**: El Router (definido en `app/Config/Routes.php`) analiza la URL y mapea a un Controller específico.
- **Componente involucrado**: Router (parte del framework, no MVC puro).
- **Detalles**: Para `/login` (GET), se mapea a `Auth::login`. Para `/login` (POST), a `Auth::attemptLogin`. Si la ruta no existe, se lanza 404.
- **Orden**: Después de la Request, antes del Controller.
- **Interacción**: El Router decide qué método del Controller ejecutar. No involucra Model o View aún.

### Paso 3: Ejecución del Controller (Auth::login - GET)
- **Qué hace primero**: El método `login()` del Controller `Auth` se ejecuta. Este es el punto de entrada para mostrar el formulario de login.
- **Componente involucrado**: Controller (`app/Controllers/Auth.php`).
- **Detalles**:
  - Valida si el usuario ya está logueado (comprueba sesión). Si sí, redirige al dashboard.
  - Prepara datos para la View: mensajes flash (éxito/error), token CSRF.
  - No interactúa con Model aún, ya que solo muestra el formulario.
- **Orden**: Después del Router.
- **Interacción**:
  - Con View: Pasa datos a la vista `app/Views/auth/login.php`.
  - Con Model: Ninguna en este paso.
  - Con Session: Lee la sesión para verificar login previo.

### Paso 4: Renderizado de la View (Login Form)
- **Qué hace primero**: La View `login.php` se renderiza y se envía al navegador como HTML.
- **Componente involucrado**: View (`app/Views/auth/login.php`).
- **Detalles**:
  - Muestra el formulario con campos (email, password), token CSRF oculto.
  - Incluye mensajes de error previos (si los hay) y estilos/scripts.
  - No hace lógica; solo presenta datos pasados por el Controller.
- **Orden**: Después del Controller en el paso 3.
- **Interacción**:
  - Recibe datos del Controller (mensajes, CSRF).
  - Envía HTML al navegador; el usuario ve la página.
  - No interactúa con Model o Controller adicionalmente.

### Paso 5: Envío del Formulario (Request POST)
- **Qué hace primero**: El usuario llena y envía el formulario, generando una nueva Request POST a `/login`.
- **Componente involucrado**: Request HTTP (repetición del Paso 1).
- **Detalles**: Incluye datos del formulario (email, password, CSRF) y cookies de sesión.
- **Orden**: Después de que el usuario interactúa con la View.
- **Interacción**: Vuelve al Router, que mapea a `Auth::attemptLogin`.

### Paso 6: Validación y Lógica en el Controller (Auth::attemptLogin - POST)
- **Qué hace primero**: El método `attemptLogin()` valida los datos del formulario.
- **Componente involucrado**: Controller (`app/Controllers/Auth.php`).
- **Detalles**:
  - Valida CSRF, campos requeridos, formato de email.
  - Si falla validación, regresa errores a la View de login (sin Model).
  - Si pasa validación, interactúa con Model para verificar credenciales.
- **Orden**: Después del Router para POST.
- **Interacción**:
  - Con Model: Llama a `UsuarioModel::verificarCredenciales()` para buscar usuario y verificar password.
  - Con View: Si error, redirige de vuelta a `login.php` con mensajes flash.
  - Con Session: Si éxito, establece datos de sesión.

### Paso 7: Interacción con el Model (UsuarioModel)
- **Qué hace primero**: El Model recibe la llamada del Controller y ejecuta la lógica de datos.
- **Componente involucrado**: Model (`app/Models/UsuarioModel.php`).
- **Detalles**:
  - Método `verificarCredenciales($email, $password)`: Consulta la base de datos (`SELECT * FROM usuarios WHERE email = ?`), verifica password con `password_verify()`.
  - Retorna datos del usuario si válido, o null/false si no.
  - Maneja errores de base de datos (excepciones).
- **Orden**: Dentro del Controller (Paso 6), después de validación.
- **Interacción**:
  - Recibe parámetros del Controller.
  - Accede a la base de datos vía `$this->db`.
  - Retorna resultado al Controller; no interactúa con View directamente.

### Paso 8: Respuesta del Controller (Éxito o Error)
- **Qué hace primero**: Basado en el resultado del Model, el Controller decide la acción.
- **Componente involucrado**: Controller.
- **Detalles**:
  - Si Model retorna usuario válido: Establece sesión (regenera ID), redirige al dashboard (View protegida).
  - Si Model falla: Redirige a login con mensaje de error.
- **Orden**: Después del Model.
- **Interacción**:
  - Con Session: Escribe datos (user_id, rol, etc.).
  - Con View: Redirige a dashboard (ej: `app/Views/administrador/alertas.php`) o de vuelta a login.
  - No más Model después de esto.

### Paso 9: Renderizado del Dashboard (View Protegida)
- **Qué hace primero**: La View del dashboard se carga, mostrando contenido personalizado.
- **Componente involucrado**: View (ej: `app/Views/administrador/alertas.php`).
- **Detalles**:
  - Recibe datos del Controller (consultas, etc.).
  - Muestra interfaz basada en rol del usuario (de sesión).
- **Orden**: Después de redirección exitosa.
- **Interacción**:
  - Recibe datos del Controller.
  - El usuario puede interactuar (ej: marcar consulta), generando nuevas Requests.

### Paso 10: Logout (Opcional, para Completar el Ciclo)
- **Qué hace primero**: Usuario hace clic en logout, enviando Request a `/logout`.
- **Componente involucrado**: Controller (`Auth::logout`).
- **Detalles**:
  - Destruye sesión.
  - Redirige a login o inicio.
- **Orden**: En cualquier momento después de login.
- **Interacción**:
  - Con Session: Elimina datos.
  - Con View: Redirige a login.

## Orden General de Ejecución
1. Request → Router
2. Controller (login GET) → View (formulario)
3. Request POST → Router
4. Controller (attemptLogin) → Validación
5. Model (verificación) → Controller
6. Controller → Session + Redirect
7. View (dashboard) o vuelta a View (login)

## Consideraciones Importantes
- **Sesión**: Se maneja globalmente; el Controller la lee/escribe.
- **Filtros**: Pueden interceptar antes del Controller (ej: autenticación).
- **Errores**: Siempre redirigen con flash messages; no se renderiza View directamente en errores.
- **Seguridad**: CSRF, validación, hash de passwords.
- **Base de Datos**: Solo el Model accede; Controllers no hacen queries directas.

Este flujo es exhaustivo y explica cada interacción sin ambigüedades. Si necesitas diagramas o más código específico, dime.
