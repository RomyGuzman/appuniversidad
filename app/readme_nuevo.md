.
<div align="center">
  <img src="https://raw.githubusercontent.com/codeigniter4/CodeIgniter4/develop/public/assets/images/ci-logo-big.png" alt="CodeIgniter 4" width="150">
  <h1>Sistema de Gestión Académica - Instituto 57</h1>
  <p>Una aplicación web desarrollada con <strong>CodeIgniter 4</strong> para la administración integral de estudiantes, carreras y categorías académicas.</p>
</div>

---///

## 🚀 Características Principales

El sistema ofrece una interfaz de usuario moderna y dinámica, enfocada en la usabilidad y la eficiencia.

-   **Gestión CRUD Completa:**
    -   Administración de **Estudiantes**..
    -   Administración de **Carreras**.
    -   Administración de **Categorías** de carreras.
-   **Interfaz Dinámica (AJAX):**
    -   Edición de registros en **ventanas modales** sin recargar la página.
    -   Búsquedas por ID y por carrera con resultados instantáneos.
-   **Experiencia de Usuario Mejorada:**
    -   **Tablas Interactivas** con paginación, búsqueda y ordenamiento gracias a **DataTables.js**.
    -   **Notificaciones y Alertas** profesionales con **SweetAlert2**.
-   **Seguridad Robusta:**
    -   Protección **CSRF** habilitada en todos los formularios.
    -   Prevención de inyección SQL mediante el uso del Query Builder de CodeIgniter.
    -   Escapado de datos en las vistas para prevenir ataques XSS.
-   **Base de Datos Estructurada:**
    -   Uso de **Migraciones** para definir y versionar la estructura de la base de datos.
    -   **Seeders** para poblar la base de datos con datos de prueba iniciales.
-   **Pruebas Automatizadas:**
    -   Implementación de **Feature Tests** para garantizar la estabilidad y el correcto funcionamiento de las rutas clave.

## 🛠️ Tecnologías Utilizadas

| Categoría      | Tecnología                                                                                             |
| -------------- | ------------------------------------------------------------------------------------------------------ |
| **Backend**    | PHP 8.1+, [CodeIgniter 4](https://codeigniter.com/)                                                    |
| **Frontend**   | HTML5, CSS3, JavaScript, [Bootstrap 5](https://getbootstrap.com/), [jQuery](https://jquery.com/)        |
| **Base de Datos**| MySQL / MariaDB                                                                                        |
| **Librerías JS** | [DataTables.js](https://datatables.net/), [SweetAlert2](https://sweetalert2.github.io/)                  |
| **Dependencias** | [Composer](https://getcomposer.org/)                                                                   |

## 📋 Requisitos del Servidor

-   PHP 8.1 o superior
-   Extensiones de PHP: `intl`, `mbstring`, `json`, `mysqlnd`
-   Servidor web (Apache, Nginx) o el servidor de desarrollo de CodeIgniter
-   Composer

## ⚙️ Guía de Instalación Local

1.  **Clonar el repositorio:**
    ```bash
    git clone <URL_DEL_REPOSITORIO> instituto_57
    cd instituto_57
    ```

2.  **Instalar dependencias de PHP:**
    ```bash
    composer install
    ```

3.  **Configurar el entorno:**
    Copia el archivo `env` y renómbralo a `.env`. Luego, edita el archivo `.env` para configurar tu base de datos.
    ```dotenv
    # .env
    CI_ENVIRONMENT = development
    
    database.default.hostname = localhost
    database.default.database = base_instituto
    database.default.username = root
    database.default.password = 
    ```

4.  **Crear la base de datos:**
    Asegúrate de haber creado una base de datos vacía llamada `base_instituto` en tu gestor de base de datos (ej: phpMyAdmin).

5.  **Ejecutar las Migraciones:**
    Este comando creará todas las tablas en tu base de datos con la estructura correcta.
    ```bash
    php spark migrate
    ```

6.  **Poblar la base de datos:**
    Este comando llenará las tablas con datos de ejemplo (categorías, modalidades y carreras).
    ```bash
    php spark db:seed
    ```

7.  **Iniciar el servidor de desarrollo:**
    ```bash
    php spark serve
    ```
    La aplicación estará disponible en `http://localhost:8080`.

## ⚡ Comandos Útiles de Spark

-   `php spark serve`: Inicia el servidor de desarrollo.
-   `php spark migrate`: Aplica las migraciones pendientes a la base de datos.
-   `php spark migrate:rollback`: Revierte la última migración.
-   `php spark db:seed`: Ejecuta el seeder principal (`DatabaseSeeder`) para poblar la base de datos.
-   `php spark test`: Ejecuta el conjunto de pruebas automatizadas.

## 🗺️ Rutas Principales

-   `/`: Página de inicio institucional.
-   `/estudiantes`: Portal de gestión de Estudiantes.
-   `/registrarCarrera`: Portal de gestión de Carreras.
-   `/categorias`: Portal de gestión de Categorías.
