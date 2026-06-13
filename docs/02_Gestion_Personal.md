# Módulo 2: Gestión de Personal

## 1. Descripción del Módulo
Permite a la institución académica organizar y validar a sus docentes y autoridades (como el Coordinador). Este módulo es vital para evitar accesos no autorizados al manejo de calificaciones.

## 2. Componentes y Funcionalidades
- **Registro de Docentes (Postulación):** Interfaz para que un usuario normal envíe su currículum, profesión y especialidad para ser considerado docente del CUP.
- **Aprobación de Docentes:** Panel administrativo para revisar los datos del docente, su profesión, y cambiar su estado de `PENDIENTE` a `APROBADO` o `RECHAZADO`.
- **Gestión de Coordinadores:** Interfaz administrativa para registrar directamente al coordinador académico del CUP.

## 3. Tablas Relacionadas
- `docente`: Almacena información curricular y estado de aprobación (`ciusuario`, `profesion`, `especialidad`, `curriculum_url`, `estado`).
- `coordinador`: Vincula a un usuario con el cargo de coordinación.

## 4. Controladores Principales
- `Auth\DocenteRegistrationController`: Procesa el formulario público de postulación a docente.
- `Admin\DocenteController`: Funciones CRUD del administrador para verificar, aprobar, rechazar o eliminar registros docentes.
- `Admin\CoordinadorController`: Funciones CRUD para coordinadores.

## 5. Vistas e Interfaces
- `docente.pendiente`: Vista de bloqueo que ve un docente mientras su solicitud no es aprobada.
- `docente.inscripcion`: Formulario de postulación (subir CV y datos profesionales).
- `admin.docentes.*`: Panel tabular para que el administrador gestione a los docentes.
- `admin.coordinadores.*`: Panel de control de coordinadores.
