# Módulo 4: Organización Académica

## 1. Descripción del Módulo
Maneja la estructura interna de la educación en el CUP. Aquí se definen las materias que se impartirán, la capacidad de estudiantes por aula, la creación de grupos en sus respectivos turnos y la asignación de docentes para dictar dichas clases.

## 2. Componentes y Funcionalidades
- **Gestión de Carreras y Materias:** Definición de la currícula académica. Se pueden definir puntos y cupos máximos por carrera.
- **Generación de Grupos:** Creación de espacios académicos (Ej. G1, G2, G3) indicando su capacidad y turno (Mañana, Tarde, Noche).
- **Asignación Docente-Grupo:** El administrador designa a un docente aprobado para que imparta una materia específica en un grupo determinado, asignándole un horario y aula física.
- **Gestión del Horario:** Interfaces para que tanto estudiantes como docentes puedan consultar su horario de clases.
- **Enlace de Comunicación (WhatsApp):** Herramienta para que el docente comparta el link de su grupo oficial a sus estudiantes.

## 3. Tablas Relacionadas
- `carrera` y `materia`: Tablas base de la currícula.
- `grupo` y `turno`: Espacios académicos y horarios de asistencia.
- `horario`: Días de la semana, horas de inicio/fin y aulas.
- `grupodocente`: Tabla puente fundamental que vincula a un docente, con una materia, con un grupo y con un horario.

## 4. Controladores Principales
- `Admin\CarreraController` y `Admin\GrupoController`: ABM de espacios y currícula.
- `Admin\AsignacionController` y métodos en `Admin\DocenteController` para vincular maestros a grupos.
- `EstudianteDashboardController`: Provee al estudiante la vista de "Mi Grupo" y "Mis Materias".
- `DocenteDashboardController`: Provee al docente las vistas de "Mis Grupos", "Mi Horario" y actualización de sílabo.

## 5. Vistas e Interfaces
- `admin.grupos.index`, `admin.carreras.index`: Configuraciones académicas para admin.
- `admin.asignacion.index`: Drag & Drop o selectores visuales para enlazar maestros a clases.
- `docente.mi_horario`, `docente.mis_grupos`: Dashboards de información académica para el profesor.
- `estudiante.mi_grupo`, `estudiante.mis_materias`: Tableros para los postulantes.
