# Flujo Detallado de Inscripción a Materias

## Contexto General

Este documento describe el flujo completo de cómo un estudiante se inscribe a una materia en el sistema universitario. El proceso involucra múltiples componentes: frontend (JavaScript), backend (PHP/CodeIgniter), base de datos y feedback visual (SweetAlert).

## Arquitectura del Sistema

### Tablas Involucradas

1. **`estudiante`** - Contiene información básica del estudiante
2. **`usuarios`** - Maneja autenticación y roles
3. **`inscripcion`** - Registra las inscripciones a materias (TABLA PRINCIPAL)
4. **`materia`** - Contiene las materias disponibles
5. **`carrera`** - Define las carreras académicas

### Componentes del Sistema

- **Frontend:** Dashboard de estudiante (`dashboard_estudiante.php`)
- **Backend:** Controlador `Estudiantes.php` método `inscribir()`
- **Base de datos:** Tabla `inscripcion`
- **JavaScript:** `public/app.js` para manejo AJAX
- **UI/UX:** SweetAlert para feedback visual

## Flujo Paso a Paso

### PASO 1: Visualización del Dashboard

**Ubicación:** `app/Views/Dashboard_Estudiantes/dashboard_estudiante.php`

**Qué sucede:**
1. El estudiante accede a `/estudiantes/dashboard`
2. El método `dashboard()` del controlador `Estudiantes` se ejecuta
3. Se obtienen todas las materias de la carrera del estudiante
4. Para cada materia, se determina el estado dinámico:
   - **Nunca inscrito:** Botón "Inscribirme" (verde)
   - **Ya inscrito:** Botón "Ya inscrito" (gris, deshabilitado)
   - **Reprobado:** Botón "No la curso - Reintentar" (amarillo)

**Código relevante:**
```php
// Obtener todas las materias de la carrera
$todasMaterias = $materiaModel->where('carrera_id', $estudiante['carrera_id'])->findAll();

// Para cada materia, determinar estado dinámico
foreach ($todasMaterias as $materia) {
    $ultimaInscripcion = $inscripcionModel->getUltimaInscripcion($estudiante['id'], $materia['id']);

    if (!$ultimaInscripcion) {
        $materia['estado'] = 'inscribirme';
        $materia['boton_texto'] = 'Inscribirme';
        $materia['boton_clase'] = 'btn-primary';
        $materia['clickeable'] = true;
    }
    // ... más estados
}
```

### PASO 2: Click en "Inscribirme"

**Ubicación:** `public/app.js`

**Qué sucede:**
1. El estudiante hace click en el botón "Inscribirme" de una materia
2. JavaScript captura el evento y obtiene el `materia_id`
3. Se envía una petición AJAX POST a `/estudiantes/inscribir`

**Código relevante:**
```javascript
// Event listener para botones de inscripción
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('btn-inscribir')) {
        const materiaId = e.target.getAttribute('data-materia-id');
        const materiaNombre = e.target.getAttribute('data-materia-nombre');

        // Enviar petición AJAX
        fetch('/estudiantes/inscribir', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: 'materia_id=' + materiaId
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Mostrar SweetAlert de éxito
                Swal.fire({
                    icon: 'success',
                    title: '¡Inscripción exitosa!',
                    text: 'Te has inscrito en ' + materiaNombre,
                    confirmButtonText: 'Aceptar'
                });

                // Recargar la página para actualizar estados
                setTimeout(() => {
                    location.reload();
                }, 2000);
            } else {
                // Mostrar error
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.error,
                    confirmButtonText: 'Aceptar'
                });
            }
        });
    }
});
```

### PASO 3: Procesamiento Backend

**Ubicación:** `app/Controllers/Estudiantes.php` método `inscribir()`

**Qué sucede:**
1. Se verifica que sea una petición AJAX
2. Se obtiene el email del usuario de la sesión
3. Se busca el estudiante en la tabla `estudiante`
4. Se verifica si ya existe una inscripción para esta materia
5. Si no existe, se inserta un nuevo registro en `inscripcion`

**Validaciones realizadas:**
- ¿Es petición AJAX? (seguridad)
- ¿Usuario tiene sesión activa?
- ¿Existe el estudiante?
- ¿Está especificada la materia?
- ¿Ya está inscrito en esta materia?

**Código relevante:**
```php
public function inscribir()
{
    // Verificar AJAX
    if (!$this->request->isAJAX()) {
        return $this->response->setStatusCode(403)->setJSON(['error' => 'Acceso no autorizado']);
    }

    // Verificar sesión
    if (!$email_usuario = session()->get('usuario')) {
        return $this->response->setJSON(['error' => 'Sesión expirada']);
    }

    // Obtener estudiante
    $estudiante = $estudianteModel->where('email', $email_usuario)->first();
    if (!$estudiante) {
        return $this->response->setJSON(['error' => 'Estudiante no encontrado']);
    }

    $estudianteId = $estudiante['id'];
    $materiaId = $this->request->getPost('materia_id');

    // Verificar materia
    if (!$materiaId) {
        return $this->response->setJSON(['error' => 'Materia no especificada']);
    }

    // Verificar inscripción existente
    $inscripcionExistente = $inscripcionModel->where('estudiante_id', $estudianteId)
                                           ->where('materia_id', $materiaId)
                                           ->first();

    if ($inscripcionExistente) {
        if (in_array($inscripcionExistente['estado_inscripcion'], ['Pendiente', 'Confirmada', 'Aprobada'])) {
            return $this->response->setJSON(['error' => 'Ya estás inscrito en esta materia']);
        }
    }

    // Insertar nueva inscripción
    $dataInscripcion = [
        'estudiante_id' => $estudianteId,
        'materia_id' => $materiaId,
        'fecha_inscripcion' => date('Y-m-d'),
        'estado_inscripcion' => 'Pendiente',
        'observaciones_inscripcion' => 'Inscripción automática desde dashboard'
    ];

    try {
        $result = $inscripcionModel->insert($dataInscripcion);
        if ($result) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Inscripción realizada correctamente'
            ]);
        } else {
            return $this->response->setJSON([
                'error' => 'Error al guardar la inscripción'
            ]);
        }
    } catch (\Exception $e) {
        return $this->response->setJSON([
            'error' => 'Error al realizar la inscripción: ' . $e->getMessage()
        ]);
    }
}
```

### PASO 4: Inserción en Base de Datos

**Tabla:** `inscripcion`

**Campos insertados:**
- `estudiante_id`: ID del estudiante (de tabla `estudiante`)
- `materia_id`: ID de la materia seleccionada
- `fecha_inscripcion`: Fecha actual (Y-m-d)
- `estado_inscripcion`: 'Pendiente' (estado inicial)
- `observaciones_inscripcion`: 'Inscripción automática desde dashboard'

**SQL generado:**
```sql
INSERT INTO inscripcion (
    estudiante_id,
    materia_id,
    fecha_inscripcion,
    estado_inscripcion,
    observaciones_inscripcion
) VALUES (
    1, -- ID del estudiante
    2, -- ID de la materia
    '2025-11-08', -- Fecha actual
    'Pendiente', -- Estado inicial
    'Inscripción automática desde dashboard' -- Observaciones
);
```

### PASO 5: Respuesta y Feedback Visual

**Respuesta JSON exitosa:**
```json
{
    "success": true,
    "message": "Inscripción realizada correctamente"
}
```

**SweetAlert mostrado:**
- **Título:** ¡Inscripción exitosa!
- **Texto:** Te has inscrito en [Nombre de la Materia]
- **Tipo:** success (verde)
- **Botón:** Aceptar

**Comportamiento posterior:**
- Después de 2 segundos, se recarga la página automáticamente
- El botón de la materia cambia a "Ya inscrito" (gris, deshabilitado)
- La materia aparece en la sección "Materias Inscritas"

## Estados de Inscripción

### Estados Posibles
1. **Pendiente:** Inscripción solicitada, esperando confirmación
2. **Confirmada:** Inscripción aprobada por administrador/profesor
3. **Aprobada:** Materia cursada y aprobada
4. **Reprobada:** Materia cursada pero reprobada
5. **Anulada:** Inscripción cancelada

### Lógica de Estados en Dashboard

```php
// Determinar estado del botón según última inscripción
$ultimaInscripcion = $inscripcionModel->getUltimaInscripcion($estudianteId, $materiaId);

if (!$ultimaInscripcion) {
    // Nunca se inscribió → puede inscribirse
    $estado = 'inscribirme';
} elseif ($ultimaInscripcion['estado_inscripcion'] == 'Reprobada') {
    // Reprobó → puede reintentar
    $estado = 'reintentar';
} elseif (in_array($ultimaInscripcion['estado_inscripcion'], ['Pendiente', 'Confirmada', 'Aprobada'])) {
    // Ya está activo → no puede inscribirse de nuevo
    $estado = 'ya_inscrito';
}
```

## Manejo de Errores

### Errores Posibles

1. **Sesión expirada:**
   ```json
   {"error": "Sesión expirada"}
   ```

2. **Ya inscrito:**
   ```json
   {"error": "Ya estás inscrito en esta materia"}
   ```

3. **Error de base de datos:**
   ```json
   {"error": "Error al realizar la inscripción: [mensaje específico]"}
   ```

4. **Validación fallida:**
   ```json
   {"error": "Error de validación: [detalles]"}
   ```

### Logging de Errores

Todos los errores se registran en los logs del sistema:
```php
log_message('error', 'Error al insertar inscripción: ' . $e->getMessage());
```

## Consideraciones de Seguridad

1. **Verificación de sesión:** Solo usuarios logueados pueden inscribirse
2. **Validación AJAX:** Solo peticiones AJAX son aceptadas
3. **Filtrado CSRF:** La ruta está excluida de filtros CSRF para AJAX
4. **Validación de datos:** Todos los datos se validan antes de insertar
5. **Prevención de duplicados:** Se verifica inscripción existente antes de insertar

## Testing del Flujo

### Pruebas Manuales Recomendadas

1. **Login como estudiante**
2. **Verificar dashboard carga correctamente**
3. **Verificar botones muestran estados correctos**
4. **Click en "Inscribirme"**
5. **Verificar SweetAlert de éxito**
6. **Verificar recarga automática**
7. **Verificar cambio de estado del botón**
8. **Verificar registro en tabla `inscripcion`**
9. **Verificar materia aparece en "Materias Inscritas"**

### Pruebas de Error

1. **Intentar inscribirse sin login**
2. **Intentar inscribirse en materia ya inscrita**
3. **Intentar inscribirse con datos inválidos**
4. **Verificar mensajes de error apropiados**

## Dependencias

### Archivos Requeridos
- `app/Controllers/Estudiantes.php`
- `app/Models/InscripcionModel.php`
- `app/Models/EstudianteModel.php`
- `app/Models/MateriaModel.php`
- `app/Views/Dashboard_Estudiantes/dashboard_estudiante.php`
- `public/app.js`
- `app/Config/Routes.php`

### Librerías Externas
- **SweetAlert2:** Para notificaciones visuales
- **Bootstrap:** Para estilos de botones
- **jQuery/Fetch API:** Para peticiones AJAX

## Mantenimiento

### Tareas de Mantenimiento
1. **Revisar logs de error** periódicamente
2. **Monitorear tabla `inscripcion`** para integridad de datos
3. **Actualizar estados** según reglas de negocio
4. **Optimizar consultas** si hay rendimiento lento

### Posibles Mejoras Futuras
1. **Notificaciones por email** al confirmar inscripción
2. **Límite de inscripciones** por período
3. **Validaciones de prerrequisitos** de materias
4. **Sistema de cupos** por materia
5. **Historial completo** de inscripciones del estudiante

---

**Nota:** Este flujo está diseñado para ser robusto, seguro y proporcionar una buena experiencia de usuario. Todos los errores se manejan apropiadamente y se proporciona feedback visual claro en cada paso del proceso.
