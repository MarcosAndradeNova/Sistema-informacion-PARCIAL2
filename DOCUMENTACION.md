# Documentación General: Sistema Web de Admisión Universitaria (CUP) FICCT

Este proyecto ha sido estructurado en una arquitectura modular para facilitar su comprensión, mantenimiento y escalabilidad. Toda la documentación ha sido reorganizada en base a los **Módulos Funcionales** del sistema, evitando duplicidad de información y asegurando consistencia entre la base de datos, los controladores y las interfaces de usuario.

A continuación, se presenta el Índice General de la documentación. Haga clic en cualquiera de los enlaces para acceder a los detalles técnicos y procesos de negocio de cada módulo específico.

---

## 📑 ÍNDICE DE MÓDULOS DEL SISTEMA

### [00. Perfil y Arquitectura del Sistema](docs/00_Perfil_y_Arquitectura.md)
Documento base que contiene la introducción, objetivos del proyecto, descripción del problema, alcance y el planteamiento de la arquitectura técnica (herramientas, base de datos y tecnologías involucradas).

### [Módulo 1: Autenticación y Seguridad](docs/01_Autenticacion_y_Seguridad.md)
Detalla el sistema de protección, inicio de sesión, creación de cuentas, gestión de perfiles de usuario, cambio de contraseñas y el manejo de roles en la plataforma.

### [Módulo 2: Gestión de Personal](docs/02_Gestion_Personal.md)
Describe el flujo de postulación docente, la revisión de currículums, el proceso de aprobación o rechazo por parte del administrador y el registro de coordinadores.

### [Módulo 3: Inscripción y Postulantes](docs/03_Inscripcion_y_Postulantes.md)
Contiene la lógica de negocio del proceso de inscripción: carga de documentos, revisión administrativa, pasarela de validación de pago bancario y la matriculación automática final del estudiante.

### [Módulo 4: Organización Académica](docs/04_Organizacion_Academica.md)
Abarca la creación de carreras, materias, turnos, grupos (aulas físicas) y el enlazamiento (asignación) de los docentes aprobados a dichos grupos, además de la consulta de horarios.

### [Módulo 5: Evaluaciones y Calificaciones](docs/05_Evaluaciones_y_Calificaciones.md)
Explica la configuración del calendario de exámenes, el panel de control administrativo para "Habilitar/Cerrar" el ingreso de notas y la interfaz del docente para cargar calificaciones de manera segura y auditable.

### [Módulo 6: Reportes y Auditoría](docs/06_Reportes_y_Auditoria.md)
Engloba la extracción de datos gerenciales en PDF/CSV, la integración de búsqueda mediante comandos de voz y el historial de bitácora (Log transaccional) para rastrear acciones administrativas.

---

> **Nota para Desarrolladores:**
> Todo el desarrollo backend está estructurado bajo el patrón MVC usando Laravel 11. Cada módulo detallado arriba mapea directamente a un conjunto específico de Controladores (dentro de `app/Http/Controllers`), Modelos (`app/Models`) y Vistas (`resources/views`). Consultar los documentos respectivos para conocer las tablas SQL exactas involucradas.
