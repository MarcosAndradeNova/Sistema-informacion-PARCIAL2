# Módulo 6: Reportes y Auditoría

## 1. Descripción del Módulo
Módulo gerencial de alto nivel diseñado para el control, monitoreo y exportación de información clave. Es esencial para la toma de decisiones, la transparencia del proceso y el registro histórico de las actividades de los administradores.

## 2. Componentes y Funcionalidades
- **Auditoría (Bitácora):** Un log transaccional que registra qué administrador hizo qué acción (aprobación, rechazo, envío de link de pago, borrado de usuarios), desde qué IP, y a qué hora. Esto garantiza trazabilidad y responsabilidad en el uso del sistema.
- **Generador de Reportes:** Permite exportar datos cruciales del sistema (Listas de Inscritos, Estudiantes Aprobados, Estudiantes Reprobados, Docentes, etc.) en formatos universales (.CSV, .PDF).
- **Control por Voz:** Implementación de accesibilidad que permite al administrador buscar información en la tabla de reportes mediante comandos dictados por voz.

## 3. Tablas Relacionadas
- `bitacora`: Almacena el `id`, la `accion` realizada, `fecha`, `hora`, `ip` y el identificador del administrador (`ciusuario`).
- (No crea tablas adicionales para reportes, ya que consulta `postulante`, `docente` y `resultadoexam` mediante joins).

## 4. Controladores Principales
- `Admin\BitacoraController`: Controlador exclusivo para extraer y paginar el histórico de seguridad.
- `Admin\ReporteController`: Construye las consultas compuestas y genera las respuestas en formato descarga.

## 5. Vistas e Interfaces
- `admin.bitacora.index`: Tabla inmutable para revisión visual del historial de actividades.
- `admin.reportes.index`: Dashboard con filtros de descarga, gráficos (opcional) y el botón de accesibilidad para búsqueda por dictado de voz.
