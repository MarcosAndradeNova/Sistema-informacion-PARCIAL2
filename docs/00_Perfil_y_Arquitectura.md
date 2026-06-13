# 00. Perfil y Arquitectura del Sistema

## 1. INTRODUCCIÓN
El presente proyecto tiene como propósito sistematizar y optimizar el proceso de admisión universitaria (CUP) de la Facultad de Ingeniería de Ciencias de la Computación y Telecomunicaciones (FICCT). A través de una aplicación web moderna, se busca facilitar el registro de postulantes, la gestión de exámenes, el cálculo automático de promedios y la asignación eficiente de grupos.

## 2. OBJETIVOS

**Objetivo General:**
Desarrollar un sistema web integral para administrar el proceso de inscripción, evaluación y admisión universitaria del curso preuniversitario (CUP) de la FICCT.

**Objetivos Específicos:**
- Implementar un módulo de autenticación seguro para diferentes roles de usuario (Administrador, Docente, Autoridades).
- Desarrollar un módulo de registro para gestionar la información y requisitos de los postulantes.
- Automatizar el cálculo de notas y estado final (Aprobado/Reprobado) basado en 3 exámenes por materia.
- Implementar un algoritmo para la asignación automática de grupos (máximo 70 estudiantes por grupo).
- Generar reportes estadísticos y listas oficiales en formatos exportables.

## 3. DESCRIPCIÓN DEL PROBLEMA
Actualmente, el proceso de admisión de la FICCT involucra el manejo de grandes volúmenes de información de postulantes, control de requisitos, registro de notas de múltiples materias (Computación, Matemáticas, Inglés, Física) y la organización manual de grupos. Este proceso manual es propenso a errores, genera cuellos de botella administrativos y retrasa la publicación de resultados oficiales.

## 4. ALCANCE
El sistema abarcará el ciclo completo de admisión, desde el registro inicial del postulante hasta la publicación de sus notas finales y asignación de grupo. Incluirá la gestión de roles (Administrador, Docente, Coordinador), registro de notas, cálculo de grupos mediante algoritmos y generación de reportes gerenciales.

## 5. ARQUITECTURA TÉCNICA

### 5.1 Diseño de Arquitectura (Física y Lógica)
- **Física:** Servidor en la nube (AWS / Supabase para base de datos PostgreSQL), cliente web (navegador).
- **Lógica:** Patrón MVC (Modelo-Vista-Controlador) proporcionado por Laravel.

### 5.2 Diseño de Datos
- Uso de PostgreSQL como motor de base de datos relacional.
- Integración de Eloquent ORM para manejo de relaciones (postulantes, grupos, materias, calificaciones, etc).

### 5.3 Herramientas de Desarrollo
- Backend: PHP 8.2+, Laravel 11.x
- Frontend: Blade Templates, Tailwind CSS, Alpine.js
- Base de Datos: PostgreSQL (Supabase)
- Entorno Local: Laragon

## 6. CONCLUSIÓN Y RECOMENDACIONES
El desarrollo de esta aplicación web permite a la FICCT modernizar su proceso de admisión, reduciendo errores humanos y tiempos de espera. Se recomienda mantener actualizaciones constantes del framework Laravel para asegurar la protección contra vulnerabilidades y capacitar al personal administrativo en el uso del panel.
