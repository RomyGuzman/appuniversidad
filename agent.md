la base de datos no se toca nunca . 
nunca se hacen migraciones , 
nunca se hacen seeders
nunca se crean migraciones, 
nunca se crean seeders

siempre contestas en español 
y antes de hacer algo me explicas lo que queres cambiar y porque 


public string $baseURL = 'http://localhost/appuniversidad/public/';
no se cambia nunca


@app/agent.md esto siempre es el contexto 














<!-- nalizando tu archivo appuniversidad.sql, te explico claramente para qué usas cada tabla y cómo se llenan, enfocándome en inscripcion, usuarios y estudiante:

Tabla estudiante
Para qué se usa: Almacena la información básica de cada estudiante registrado en el sistema.

Cómo se llena:

Se llena cuando un estudiante se registra en el formulario de registro (RegistroController::registrar()).
Campos: dni, nombre_estudiante, fecha_nacimiento, edad, email, carrera_id.
Ejemplo: Cuando alguien completa el formulario de registro, se inserta un registro aquí.
Tabla usuarios
Para qué se usa: Maneja la autenticación y roles de todos los usuarios del sistema (estudiantes, profesores, admins).

Cómo se llena:

Para estudiantes: Se llena automáticamente después de registrar en estudiante, en RegistroController::registrar().
Campos: usuario (email), password (md5 del dni), rol_id (3 para alumnos), activo.
También se llena manualmente para profesores y admins al crear sus cuentas.
Tabla inscripcion
Para qué se usa: Registra qué estudiantes están inscritos en qué materias. Es la tabla que conecta estudiantes con materias.

Cómo se llena:

Se llena cuando un estudiante se inscribe a una materia específica.
NO se llena automáticamente al registrar al estudiante.
Se llena desde el dashboard del estudiante cuando hace click en "Inscribirme" a una materia.
Campos: estudiante_id, materia_id, fecha_inscripcion, estado_inscripcion, observaciones, etc.
Ejemplo del flujo completo:

Estudiante se registra → se guarda en estudiante y usuarios.
Estudiante hace login → ve dashboard con materias de su carrera.
Estudiante selecciona "Inscribirme" en una materia → se guarda en inscripcion.
Por qué inscripcion es necesaria:

Un estudiante puede estar registrado pero no inscrito a ninguna materia aún.
Un estudiante puede inscribirse a múltiples materias.
Permite controlar estados (pendiente, confirmada, etc.).
Mantiene historial de inscripciones.
Sin inscripcion, no sabrías qué materias cursa cada estudiante. -->