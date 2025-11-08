# Documentación Completa de los Modelos en AppUniversidad

Este documento proporciona una explicación detallada y didáctica de cada modelo en la aplicación AppUniversidad, desarrollada con CodeIgniter 4. Cada modelo representa una entidad de la base de datos y encapsula la lógica de negocio relacionada con esa entidad. Se incluyen fundamentaciones teóricas, ejemplos prácticos y explicaciones de por qué se implementan ciertas decisiones de diseño.

## Índice de Modelos

1. [AdministradorModel](#1-administradormodel)
2. [AsistenciaModel](#2-asistenciamodel)
3. [CarreraModel](#3-carreramodel)
4. [CategoriaModel](#4-categoriamodel)
5. [ConsultaAdminModel](#5-consultaadminmodel)
6. [ConsultasModel](#6-consultasmodel)
7. [EstudianteModel](#7-estudiantemodel)
8. [MateriaModel](#8-materiamodel)
9. [ModalidadModel](#9-modalidadmodel)
10. [NotaModel](#10-notamodel)
11. [ProfesorModel](#11-profesormodel)
12. [RegistroEstudianteModel](#12-registroestudiantemodel)
13. [RolModel](#13-rolmodel)
14. [UsuarioModel](#14-usuariomodel)

## Arquitectura General de los Modelos

Los modelos en CodeIgniter 4 siguen el patrón MVC (Model-View-Controller) y heredan de la clase base `CodeIgniter\Model`. Esta arquitectura permite:

- **Separación de responsabilidades**: Los modelos manejan únicamente la lógica de datos y negocio.
- **Reutilización de código**: Métodos comunes se heredan de la clase base.
- **Validación automática**: Las reglas de validación se aplican antes de guardar datos.
- **Seguridad**: Los `allowedFields` previenen ataques de asignación masiva.

### Conceptos Fundamentales

1. **Allowed Fields**: Lista blanca de campos que pueden ser insertados/actualizados masivamente.
2. **Validation Rules**: Reglas que se ejecutan automáticamente antes de operaciones CRUD.
3. **Timestamps**: Campos automáticos para tracking de creación/modificación.
4. **Soft Deletes**: Eliminación lógica en lugar de física.

---

**************************************************************************************************************
## 1. AdministradorModel
**************************************************************************************************************

### Propósito
El modelo `AdministradorModel` gestiona la información de los administradores del sistema universitario. Representa la tabla 'Administrador' y proporciona métodos para operaciones CRUD básicas.

### Propiedades de Configuración

```php
protected $table = 'Administrador';
protected $primaryKey = 'id_admin';
protected $allowedFields = ['id_admin', 'dni', 'nadmin', 'fecha_nac', 'edad', 'email'];
protected $useTimestamps = false;
```

**Fundamentación**: 
- `useTimestamps = false`: En sistemas legacy, los timestamps pueden manejarse manualmente o no existir.
- `allowedFields`: Incluye todos los campos necesarios pero excluye campos sensibles o calculados.

### Reglas de Validación

```php
protected $validationRules = [
    'id_admin' => 'permit_empty|integer',
    'dni' => 'required|regex_match[/^[0-9]{8}$/]|is_unique[Administrador.dni,id_admin,{id_admin}]',
    'nadmin' => 'required|min_length[2]|max_length[80]',
    'edad' => 'required|regex_match[/^[0-9]{1,2}$/]',
    'email' => 'required|valid_email|max_length[50]',
];
```

**Fundamentación**: 
- **DNI único**: Previene duplicados de identidad.
- **Formato DNI**: Asegura integridad de datos (8 dígitos numéricos).
- **Email válido**: Importante para comunicaciones oficiales.

### Métodos Personalizados

#### `getAdministradores()`
```php
public function getAdministradores()
{
    return $this->findAll();
}
```
**Ejemplo de uso**:
```php
$adminModel = new AdministradorModel();
$admins = $adminModel->getAdministradores();
// Retorna: [['id_admin' => 1, 'dni' => '12345678', 'nadmin' => 'Juan Pérez', ...], ...]
```

#### `getAdministrador($id)`
```php
public function getAdministrador($id)
{
    return $this->find($id);
}
```
**Ejemplo de uso**:
```php
$admin = $adminModel->getAdministrador(1);
// Retorna: ['id_admin' => 1, 'dni' => '12345678', 'nadmin' => 'Juan Pérez', ...]
```

---

**************************************************************************************************************
## 2. AsistenciaModel
**************************************************************************************************************

### Propósito
Gestiona el registro de asistencia de estudiantes a clases. Es uno de los modelos más complejos debido a las múltiples consultas analíticas requeridas.

### Propiedades de Configuración

```php
protected $table = 'Asistencia';
protected $primaryKey = 'id';
protected $allowedFields = ['id', 'estudiante_id', 'materia_id', 'fecha', 'estado', 'observaciones', 'inscripcion_id'];
protected $useTimestamps = false;
```

### Reglas de Validación

```php
protected $validationRules = [
    'estudiante_id' => 'required|integer',
    'materia_id' => 'required|integer',
    'fecha' => 'required|valid_date',
    'estado' => 'required|in_list[Presente,Ausente,Tarde]',
];
```

**Fundamentación**: 
- **Estados limitados**: Solo permite valores predefinidos para mantener consistencia.
- **Fechas válidas**: Previene errores de formato.

### Métodos Analíticos Principales

#### `getAsistenciaPorFecha($materia_id, $start, $end)`
**Propósito**: Agrupa asistencia por fecha para análisis temporal.

```php
public function getAsistenciaPorFecha($materia_id, $start, $end)
{
    return $this->select('fecha,
                         SUM(CASE WHEN estado = "Presente" THEN 1 ELSE 0 END) as presentes,
                         SUM(CASE WHEN estado = "Ausente" THEN 1 ELSE 0 END) as ausentes,
                         SUM(CASE WHEN estado = "Tarde" THEN 1 ELSE 0 END) as justificados')
               ->where('materia_id', $materia_id)
               ->where('fecha >=', $start)
               ->where('fecha <=', $end)
               ->groupBy('fecha')
               ->orderBy('fecha', 'ASC')
               ->findAll();
}
```

**Ejemplo de uso**:
```php
$asistenciaModel = new AsistenciaModel();
$datos = $asistenciaModel->getAsistenciaPorFecha(1, '2024-01-01', '2024-01-31');
// Retorna: [['fecha' => '2024-01-15', 'presentes' => 25, 'ausentes' => 3, 'justificados' => 2], ...]
```

**Fundamentación**: Usa `SUM` con `CASE` para contar eficientemente sin múltiples consultas.

#### `getEstadisticasMes($materia_id)`
**Propósito**: Proporciona estadísticas mensuales rápidas.

```php
public function getEstadisticasMes($materia_id)
{
    $mes_actual = date('Y-m');
    return $this->select('COUNT(*) as total_clases,
                         SUM(CASE WHEN estado = "Presente" THEN 1 ELSE 0 END) as total_presentes,
                         ROUND((SUM(CASE WHEN estado = "Presente" THEN 1 ELSE 0 END) / COUNT(*)) * 100, 2) as porcentaje_presentes')
               ->where('materia_id', $materia_id)
               ->where('DATE_FORMAT(fecha, "%Y-%m")', $mes_actual)
               ->first();
}
```

**Fundamentación**: 
- **Cálculos en SQL**: Más eficiente que procesar en PHP.
- **Redondeo**: Mantiene precisión decimal controlada.

#### `guardarAsistenciasMensuales($materia_id, $asistencias)`
**Propósito**: Guarda múltiples registros de asistencia de forma transaccional.

```php
public function guardarAsistenciasMensuales($materia_id, $asistencias)
{
    $this->db->transStart();
    foreach ($asistencias as $asistencia) {
        // Lógica de insert/update
    }
    $this->db->transComplete();
    return $this->db->transStatus();
}
```

**Fundamentación**: 
- **Transacciones**: Garantiza atomicidad de operaciones múltiples.
- **Upsert logic**: Actualiza si existe, inserta si no.

---

**************************************************************************************************************
## 3. CarreraModel
**************************************************************************************************************

### Propósito
Gestiona las carreras académicas ofrecidas por la institución.

### Propiedades de Configuración

```php
protected $table = 'carrera';
protected $primaryKey = 'id';
protected $allowedFields = ['nombre_carrera', 'codigo_carrera', 'categoria_id', 'modalidad_id'];
protected $useTimestamps = false;
```

### Reglas de Validación

```php
protected $validationRules = [
    'id' => 'permit_empty|integer',
    'nombre_carrera' => 'required|min_length[2]|max_length[120]',
    'codigo_carrera' => 'required|min_length[2]|max_length[20]|is_unique[carrera.codigo_carrera,id,{id}]',
    'duracion' => 'permit_empty|is_natural_no_zero|less_than_equal_to[12]',
    'id_modalidad' => 'permit_empty|integer',
    'id_categoria' => 'permit_empty|integer',
];
```

### Métodos Personalizados

#### `getCarreraCompleta($id)`
**Propósito**: Obtiene carrera con información relacionada.

```php
public function getCarreraCompleta($id)
{
    $carrera = $this->find($id);
    if ($carrera) {
        // Carga información de categoría y modalidad
        $categoriaModel = new \App\Models\CategoriaModel();
        $categoria = $categoriaModel->find($carrera['categoria_id']);
        $carrera['nombre_categoria'] = $categoria ? $categoria['nombre_categoria'] : 'No encontrada';
        // Similar para modalidad
    }
    return $carrera;
}
```

**Fundamentación**: 
- **Lazy loading**: Carga datos relacionados solo cuando se necesitan.
- **Manejo de nulos**: Proporciona valores por defecto cuando faltan relaciones.

---

**************************************************************************************************************
## 4. CategoriaModel
**************************************************************************************************************

### Propósito
Gestiona las categorías de carreras (ej: Técnico, Profesional, etc.).

### Propiedades de Configuración

```php
protected $table = 'Categoria';
protected $primaryKey = 'id';
protected $allowedFields = ['codigo_categoria', 'nombre_categoria', 'carrera_id'];
protected $useTimestamps = false;
```

### Reglas de Validación

```php
protected $validationRules = [
    'id' => 'permit_empty|integer',
    'codigo_categoria' => 'required|is_unique[Categoria.codigo_categoria,id,{id}]|max_length[20]',
    'nombre_categoria' => 'required|min_length[2]|max_length[120]',
];
```

**Fundamentación**: 
- **Códigos únicos**: Identificadores únicos para integración con sistemas externos.
- **Longitudes limitadas**: Optimización de almacenamiento y UI.

---

**************************************************************************************************************
## 5. ConsultaAdminModel
**************************************************************************************************************

### Propósito
Maneja las consultas realizadas por administradores al sistema.

### Características Especiales

```php
public function getConsultasPaginadas(int $perPage = 10)
{
    $builder = $this->db->table('consultas_admin c');
    $builder->select('c.id_consulta, c.asunto, c.mensaje, c.estado, c.fecha_creacion, c.email_usuario,
                     r.nombre_rol,
                     COALESCE(e.nombre_estudiante, p.nombre_profesor) as nombre_remitente');
    $builder->join('rol r', 'r.id = c.rol_id', 'left');
    $builder->join('estudiante e', 'e.id = c.usuario_id AND c.rol_id = 3', 'left');
    $builder->join('profesor p', 'p.id = c.usuario_id AND c.rol_id = 2', 'left');
    // ...
}
```

**Fundamentación**: 
- **Query Builder**: Para consultas complejas con múltiples joins.
- **COALESCE**: Maneja diferentes tipos de usuarios remitentes.
- **Paginación**: Para manejo eficiente de grandes volúmenes de datos.

---

**************************************************************************************************************
## 6. ConsultasModel
**************************************************************************************************************

### Nota
Este modelo parece ser un duplicado o versión anterior de ConsultaAdminModel. Se recomienda consolidar en una sola implementación.

---

## 7. EstudianteModel

### Propósito
Modelo central para gestión de estudiantes, con métodos analíticos avanzados.

### Métodos Destacados

#### `getEstudiantesConCarrera()`
```php
public function getEstudiantesConCarrera()
{
    return $this->select('Estudiante.*, Carrera.nombre_carrera')
        ->join('Carrera', 'Carrera.id = Estudiante.carrera_id', 'left')
        ->findAll();
}
```

**Fundamentación**: 
- **Left join**: Incluye estudiantes sin carrera asignada.
- **Campos específicos**: Solo selecciona campos necesarios.

#### `getEstadisticas(array $notas, array $inscritas)`
**Propósito**: Calcula métricas académicas del estudiante.

```php
public function getEstadisticas(array $notas, array $inscritas): array
{
    $totalNotas = count($notas);
    $sumaNotas = 0;
    foreach ($notas as $nota) {
        $sumaNotas += $nota['calificacion'];
    }
    $promedio = $totalNotas > 0 ? round($sumaNotas / $totalNotas, 2) : 0;
    // ... más cálculos
    return [
        'promedio_general' => $promedio,
        'materias_aprobadas' => $aprobadas,
        'progreso' => $progreso
    ];
}
```

**Fundamentación**: 
- **Cálculos en PHP**: Para lógica compleja que SQL no maneja eficientemente.
- **Redondeo**: Precisión decimal consistente.

#### `getDatosAsistencia($inscripcion_id)`
**Propósito**: Calcula métricas de asistencia con reglas de negocio.

```php
public function getDatosAsistencia($inscripcion_id)
{
    // Obtiene inscripción y materia
    $inscripcion = $this->db->table('inscripcion i')
        ->join('materia m', 'm.id = i.materia_id')
        ->where('i.id', $inscripcion_id)
        ->select('i.id as inscripcion_id, m.id as materia_id, m.nombre_materia')
        ->get()->getRowArray();

    if (!$inscripcion) {
        return null;
    }

    // Cuenta asistencias
    $asistencias = $this->db->table('asistencia')
        ->where('inscripcion_id', $inscripcion_id)
        ->select("COUNT(CASE WHEN estado = 'presente' THEN 1 END) as presentes")
        ->select("COUNT(CASE WHEN estado = 'ausente' THEN 1 END) as ausentes")
        ->get()->getRowArray();

    // Cálculos de porcentajes y reglas de negocio
    $total_clases_registradas = ($asistencias['presentes'] ?? 0) + ($asistencias['ausentes'] ?? 0);
    $porcentaje_asistencia = ($total_clases_registradas > 0) ?
        (($asistencias['presentes'] ?? 0) / $total_clases_registradas) * 100 : 100;

    // Reglas de promoción y regularización
    $max_faltas_promocion = 5;
    $porcentaje_minimo_regular = 80;
    $max_faltas_regulares = floor($total_clases_registradas * (1 - ($porcentaje_minimo_regular / 100)));

    $faltas_restantes_promocion = $max_faltas_promocion - ($asistencias['ausentes'] ?? 0);
    $faltas_restantes_regular = $max_faltas_regulares - ($asistencias['ausentes'] ?? 0);

    $estado_asistencia = 'ok';
    if (($asistencias['ausentes'] ?? 0) > $max_faltas_promocion) {
        $estado_asistencia = 'solo_regular';
    }
    if ($porcentaje_asistencia < $porcentaje_minimo_regular && $total_clases_registradas > 0) {
        $estado_asistencia = 'libre';
    }

    return [
        'materia' => $inscripcion,
        'asistencias' => $asistencias,
        'porcentaje_asistencia' => round($porcentaje_asistencia, 2),
        'faltas_restantes_promocion' => $faltas_restantes_promocion > 0 ? $faltas_restantes_promocion : 0,
        'faltas_restantes_regular' => $faltas_restantes_regular > 0 ? $faltas_restantes_regular : 0,
        'estado_asistencia' => $estado_asistencia
    ];
}
```

**Fundamentación**: 
- **Reglas de negocio codificadas**: Lógica específica de la institución.
- **Cálculos dinámicos**: Basados en datos reales, no valores fijos.
- **Estados condicionales**: Determinación automática de situación académica.

---

## 8. MateriaModel

### Propósito
Gestiona las materias académicas del sistema.

### Propiedades de Configuración

```php
protected $table = 'Materia';
protected $primaryKey = 'id';
protected $allowedFields = ['nombre_materia', 'codigo_materia', 'carrera_id'];
protected $useTimestamps = false;
```

### Reglas de Validación

```php
protected $validationRules = [
    'nombre_materia' => 'required|min_length[2]|max_length[120]',
    'codigo_materia' => 'required|min_length[2]|max_length[20]|is_unique[Materia.codigo_materia,id,{id}]',
    'carrera_id' => 'required|integer',
];
```

### Métodos Personalizados

#### `getMateriales($id_mat)`
```php
public function getMateriales($id_mat)
{
    return $this->db->table('Material')
        ->where('materia_id', $id_mat)
        ->get()
        ->getResultArray();
}
```

**Fundamentación**: 
- **Relación uno a muchos**: Una materia puede tener múltiples materiales.
- **Query directa**: Para relaciones simples.

---

## 9. ModalidadModel

### Propósito
Gestiona las modalidades de estudio (Presencial, Virtual, Semi-presencial, etc.).

### Propiedades de Configuración

```php
protected $table = 'Modalidad';
protected $primaryKey = 'id';
protected $allowedFields = ['codigo_modalidad', 'nombre_modalidad', 'carrera_id'];
protected $useTimestamps = false;
```

### Reglas de Validación

```php
protected $validationRules = [
    'codigo_modalidad' => 'required|min_length[2]|max_length[20]|is_unique[Modalidad.codigo_modalidad,id,{id}]',
    'nombre_modalidad' => 'required|min_length[2]|max_length[120]',
];
```

---

## 10. NotaModel

### Propósito
Gestiona las calificaciones de estudiantes en materias.

### Propiedades de Configuración

```php
protected $table = 'Nota';
protected $primaryKey = 'id';
protected $allowedFields = ['id', 'estudiante_id', 'materia_id', 'calificacion', 'fecha_evaluacion', 'observaciones', 'inscripcion_id'];
protected $useTimestamps = false;
```

### Reglas de Validación

```php
protected $validationRules = [
    'estudiante_id' => 'required|integer',
    'materia_id' => 'required|integer',
    'calificacion' => 'required|decimal',
    'fecha_evaluacion' => 'permit_empty|valid_date',
];
```

### Métodos Personalizados

#### `getNotasPorMateria($materia_id)`
```php
public function getNotasPorMateria($materia_id)
{
    return $this->select('Nota.*, Estudiante.nombre_estudiante')
        ->join('Estudiante', 'Estudiante.id = Nota.estudiante_id')
        ->where('Nota.materia_id', $materia_id)
        ->findAll();
}
```

**Fundamentación**: 
- **Join con estudiante**: Para mostrar nombres en lugar de IDs.
- **Filtrado por materia**: Para vistas de profesor.

---

## 11. ProfesorModel

### Propósito
Modelo complejo para gestión de profesores con múltiples relaciones.

### Propiedades de Configuración

```php
protected $table = 'Profesor';
protected $primaryKey = 'id';
protected $allowedFields = ['id', 'legajo', 'nombre_profesor', 'carrera_id'];
protected $useTimestamps = false;
protected $returnType = 'array';
```

### Reglas de Validación

```php
protected $validationRules = [
    'id' => 'permit_empty|integer',
    'legajo' => 'required|integer|is_unique[Profesor.legajo,id,{id}]',
    'nombre_profesor' => 'required|min_length[2]|max_length[80]',
];
```

### Métodos Analíticos

#### `getMateriasDictadas($id_prof)`
```php
public function getMateriasDictadas($id_prof)
{
    return $this->db->table('Profesor_Materia')
        ->select('Materia.nombre_materia, Materia.codigo_materia, Materia.id, Materia.carrera_id, Carrera.nombre_carrera')
        ->join('Materia', 'Materia.id = Profesor_Materia.materia_id')
        ->join('Carrera', 'Carrera.id = Materia.carrera_id')
        ->where('Profesor_Materia.profesor_id', $id_prof)
        ->get()
        ->getResultArray();
}
```

**Fundamentación**: 
- **Tabla intermedia**: Relación muchos a muchos entre profesores y materias.
- **Múltiples joins**: Para obtener información completa.

#### `getEstadisticas($id_prof)`
```php
public function getEstadisticas($id_prof)
{
    $materias = $this->getMateriasDictadas($id_prof);
    $total_materias = count($materias);

    $total_estudiantes = 0;
    foreach ($materias as $materia) {
        $estudiantes = $this->db->table('Inscripcion')
            ->where('materia_id', $materia['id'])
            ->countAllResults();
        $total_estudiantes += $estudiantes;
    }

    $notas = $this->db->table('Nota')
        ->select('AVG(calificacion) as promedio')
        ->join('Materia', 'Materia.id = Nota.materia_id')
        ->join('Profesor_Materia', 'Profesor_Materia.materia_id = Materia.id')
        ->where('Profesor_Materia.profesor_id', $id_prof)
        ->get()
        ->getRowArray();

    $promedio_calificaciones = $notas['promedio'] ?? 0;

    return [
        'total_materias' => $total_materias,
        'total_estudiantes' => $total_estudiantes,
        'promedio_calificaciones' => round($promedio_calificaciones, 2)
    ];
}
```

**Fundamentación**: 
- **Métricas agregadas**: Proporciona KPIs importantes para evaluación docente.
- **Cálculos complejos**: Combina datos de múltiples tablas.

---

## 12. RegistroEstudianteModel

### Propósito
Modelo específico para operaciones de registro de estudiantes. Es un wrapper simplificado del EstudianteModel.

### Propiedades de Configuración

```php
protected $table = 'Estudiante';
protected $primaryKey = 'id';
protected $allowedFields = [
    'dni',
    'nombre_estudiante',
    'fecha_nacimiento',
    'edad',
    'email',
    'carrera_id'
];
```

**Fundamentación**: 
- **Campos limitados**: Solo incluye campos necesarios para registro inicial.
- **Principio de menor privilegio**: No permite modificar campos sensibles.

---

## 13. RolModel

### Propósito
Gestiona los roles de usuario en el sistema (Administrador, Profesor, Estudiante, etc.).

### Propiedades de Configuración

```php
protected $table = 'Rol';
protected $primaryKey = 'id';
protected $allowedFields = ['nombre_rol'];
protected $useTimestamps = false;
```

### Reglas de Validación

```php
protected $validationRules = [
    'nombre_rol' => 'required|min_length[2]|max_length[20]|is_unique[Rol.nombre_rol,id,{id}]',
];
```

**Fundamentación**: 
- **Nombres únicos**: Previene confusión en asignación de permisos.
- **Longitud limitada**: Para UI y consistencia.

---

## 14. UsuarioModel

### Propósito
Gestiona las credenciales de autenticación de usuarios.

### Propiedades de Configuración

```php
protected $table = 'Usuarios';
protected $primaryKey = 'id';
protected $allowedFields = [
    'usuario',
    'password',
    'rol_id',
    'activo',
    'fecha_ultimo_ingreso'
];
```

**Fundamentación**: 
- **Campos de seguridad**: Incluye estado activo y tracking de ingresos.
- **Separación de entidades**: Usuario separado de datos personales por seguridad.

---

## Patrones de Diseño Comunes

### 1. Validación Consistente
Todos los modelos usan reglas de validación consistentes:
- Campos únicos con `is_unique`
- Longitudes mínimas y máximas
- Tipos de datos específicos

### 2. Métodos de Consulta Eficientes
- **Joins apropiados**: Left joins para datos opcionales
- **Selección específica**: Solo campos necesarios
- **Agrupamiento**: Para estadísticas agregadas

### 3. Manejo de Relaciones
- **Carga lazy**: Datos relacionados se cargan bajo demanda
- **Queries optimizadas**: Joins en lugar de múltiples consultas
- **Manejo de nulos**: Valores por defecto para datos faltantes

### 4. Transacciones
Modelos complejos usan transacciones para operaciones múltiples garantizando atomicidad.

## Recomendaciones de Mejora

1. **Consolidar modelos duplicados**: Unificar ConsultaAdminModel y ConsultasModel
2. **Implementar soft deletes**: Para auditoría y recuperación de datos
3. **Agregar índices**: En campos frecuentemente consultados
4. **Implementar caching**: Para consultas de estadísticas
5. **Validaciones personalizadas**: Para reglas de negocio complejas
6. **Logging de operaciones**: Para auditoría de cambios

Esta documentación proporciona una base sólida para entender y mantener los modelos del sistema AppUniversidad.
