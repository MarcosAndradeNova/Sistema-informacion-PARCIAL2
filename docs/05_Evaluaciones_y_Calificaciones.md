# Módulo 5: Evaluaciones y Calificaciones

## 1. Descripción del Módulo
Este es el componente de control de rendimiento de los postulantes. Permite programar las fechas de los exámenes y registrar las notas de forma segura, bajo supervisión temporal y autorizada del administrador.

## 2. Componentes y Funcionalidades
- **Programación de Exámenes:** El administrador asigna la fecha y nombre a las evaluaciones (Ej. 1er Parcial, 2do Parcial, Final) en cada grupo.
- **Control de Registro de Notas:** El administrador debe "habilitar" un periodo de ingreso de notas (con límite de días). Si está cerrado, los docentes no pueden modificar el sistema.
- **Carga de Calificaciones:** Los docentes introducen las notas de los estudiantes correspondientes a su grupo y materia habilitada.
- **Consulta de Rendimiento:** Coordinadores y administradores pueden supervisar las notas globales, mientras que los postulantes visualizan exclusivamente sus resultados.

## 3. Tablas Relacionadas
- `examen`: Catálogo base de evaluaciones (1, 2, 3).
- `resultadoexam`: Guarda la calificación exacta obtenida por un `ciusuario` en un `nroexamen` para una `idmateria`.
- `configuraciones`: Tabla dinámica de sistema para controlar el estado global de la subida de notas (abierto/cerrado) y sus plazos.

## 4. Controladores Principales
- `Admin\ExamenController`: Gestiona la programación de fechas y la apertura/cierre de la plataforma de notas (control en tabla `configuraciones`).
- `DocenteDashboardController` (métodos de `calificaciones`): Validando el estado del sistema, procesa las actas y notas enviadas por el docente a la tabla `resultadoexam`.
- `Admin\EvaluacionController`: Para que la coordinación y dirección académica audite notas masivamente.

## 5. Vistas e Interfaces
- `admin.examenes.index`: Interfaz de configuración dual (Fechas de exámenes + Panel de Control de Habilitación de Notas).
- `docente.calificaciones`: Planilla digital donde el docente registra notas, la cual se bloquea dinámicamente si el registro está "Cerrado".
- `docente.cronograma`: Calendario institucional dinámico para que el docente vea cuándo debe evaluar.
- `estudiante.mis_examenes`: Pantalla informativa del postulante para ver sus notas parciales.
