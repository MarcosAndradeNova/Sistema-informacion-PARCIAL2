# Módulo 3: Inscripción y Postulantes

## 1. Descripción del Módulo
Controla todo el flujo por el que pasa un estudiante desde que entra al sistema hasta que es oficialmente reconocido como "Inscrito" en el curso preuniversitario. Este módulo es el núcleo transaccional del lado del estudiante.

## 2. Componentes y Funcionalidades
- **Ficha de Inscripción (Postulación):** Formulario para que el postulante ingrese datos de contacto, suba sus documentos (Carnet, Título Bachiller) y seleccione la Carrera a la que desea aspirar.
- **Validación Documental:** El administrador revisa los documentos subidos y los "Aprueba" o "Rechaza".
- **Pasarela de Pago:** Si los documentos son aprobados, el estudiante puede subir el comprobante de pago bancario (o pagar en línea) y este se enlaza a su postulación.
- **Inscripción Automática:** Una vez verificado el pago, el sistema inscribe al postulante, le asigna automáticamente un grupo disponible y cambia su rol oficial a `Postulante`.

## 3. Tablas Relacionadas
- `postulante`: Almacena documentos (URL de imagen de CI, título) y estado general del documento.
- `postulacion`: Relaciona al postulante con la gestión actual, grupo asignado, pago y fecha.
- `pago`: Almacena la evidencia de transferencia o depósito.
- `carrera`: Catálogo de carreras disponibles para la elección del postulante.

## 4. Controladores Principales
- `InscripcionController`: Maneja el registro inicial de datos y documentos del estudiante.
- `PagoController`: Controla la carga y validación de comprobantes de pago.
- `AdminController`: Maneja la verificación administrativa de postulantes y envíos de enlaces de pago.

## 5. Vistas e Interfaces
- `inscripcion.estado`: Flujo paso a paso para el estudiante (Pendiente -> Documentos -> Pago -> Inscrito).
- `admin.postulantes`: Dashboard del administrador con tabla interactiva para evaluar miles de postulantes, con filtrado rápido.
- `pago.pasarela`: Vista que simula la validación bancaria de la transferencia.
