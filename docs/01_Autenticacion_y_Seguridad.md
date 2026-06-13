# Módulo 1: Autenticación y Seguridad

## 1. Descripción del Módulo
Este módulo se encarga de gestionar el acceso de los diferentes actores del sistema a sus respectivos paneles de control. Asegura la confidencialidad de la información y restringe funciones operativas basado en el rol asignado a cada cuenta.

## 2. Componentes y Funcionalidades
- **Registro de Cuentas:** Permite a los usuarios crear sus credenciales (correo y contraseña).
- **Inicio de Sesión (Login):** Validar credenciales y redirigir al dashboard correspondiente.
- **Gestión de Perfil:** Permite a los usuarios actualizar sus datos básicos (nombre, correo) y cambiar su contraseña. Sincroniza automáticamente los cambios de correo con la tabla principal de `usuario` para evitar pérdida de datos relacionales.
- **Roles y Permisos:** Define si el usuario es `admin`, `docente`, `coordinador` o `postulante` (user genérico).

## 3. Tablas Relacionadas (Base de Datos)
- `users`: Tabla nativa de Laravel para autenticación (email, password).
- `usuario`: Tabla de la lógica de negocio (ci, nombre, apellidos, tipo).

## 4. Controladores Principales
- `Auth\AuthenticatedSessionController`: Gestiona el Login y Logout.
- `Auth\RegisteredUserController`: Gestiona el registro inicial.
- `ProfileController`: Actualiza los datos del perfil y contraseña, y sincroniza correos.
- `Admin\RolesController`: (Para administradores) Asignación dinámica de roles en el sistema.

## 5. Vistas e Interfaces
- `auth.login`: Pantalla de inicio de sesión.
- `auth.register`: Formulario de creación de cuenta.
- `profile.edit`: Interfaz de gestión de cuenta y contraseña personal.
- `dashboard.blade.php`: Redireccionador visual del inicio.
