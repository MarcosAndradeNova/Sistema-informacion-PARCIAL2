# CARÁTULA

**TÍTULO DE LA APP WEB:** Sistema Web de Admisión Universitaria (CUP) para la FICCT
**NÚMERO DE GRUPO:** [Insertar Número]
**INTEGRANTES:**
1. [Nombre del Integrante 1]
2. [Nombre del Integrante 2]
**DOCENTE:** MSc. Ing. Angélica Garzón Cuéllar
**MATERIA:** Sistemas 1
**FECHA:** [Insertar Fecha]

---

# TABLA DE CONTENIDO

1. [PERFIL](#1-perfil)
   1.1 [Introducción](#11-introduccion)
   1.2 [Objetivos](#12-objetivos)
   1.3 [Descripción del Problema](#13-descripcion-del-problema)
   1.4 [Alcance](#14-alcance)
2. [MARCO TEÓRICO](#2-marco-teorico)
3. [MODELO DE NEGOCIO](#3-modelo-de-negocio)
4. [FT CAPTURA DE REQUISITOS](#4-ft-captura-de-requisitos)
5. [FT. ANÁLISIS](#5-ft-analisis)
6. [FT. DISEÑO](#6-ft-diseno)
   6.1 [Diseño de Arquitectura (Física y Lógica)](#61-diseno-de-arquitectura)
   6.2 [Diseñar Caso de Uso (Diagrama de Secuencia)](#62-diseno-caso-de-uso)
   6.3 [Diseño de Datos (Lógico y Físico)](#63-diseno-de-datos)
7. [FT. IMPLEMENTACIÓN](#7-ft-implementacion)
   7.1 [Herramientas de desarrollo de la aplicación WEB](#71-herramientas)
   7.2 [Implementación de la Arquitectura del Sistema](#72-implementacion-arquitectura)
   7.3 [Implementación de la Arquitectura del Subsistemas](#73-implementacion-subsistemas)
8. [CONCLUSIÓN](#8-conclusion)
9. [RECOMENDACIÓN](#9-recomendacion)
10. [BIBLIOGRAFÍA](#10-bibliografia)
11. [ANEXOS](#11-anexos)

---

## 1. PERFIL

### 1.1 INTRODUCCIÓN
El presente proyecto tiene como propósito sistematizar y optimizar el proceso de admisión universitaria (CUP) de la Facultad de Ingeniería de Ciencias de la Computación y Telecomunicaciones (FICCT). A través de una aplicación web moderna, se busca facilitar el registro de postulantes, la gestión de exámenes, el cálculo automático de promedios y la asignación eficiente de grupos.

### 1.2 OBJETIVOS

**Objetivo General:**
Desarrollar un sistema web integral para administrar el proceso de inscripción, evaluación y admisión universitaria del curso preuniversitario (CUP) de la FICCT.

**Objetivos Específicos:**
- Implementar un módulo de autenticación seguro para diferentes roles de usuario (Administrador, Docente, Autoridades).
- Desarrollar un módulo de registro para gestionar la información y requisitos de los postulantes.
- Automatizar el cálculo de notas y estado final (Aprobado/Reprobado) basado en 3 exámenes por materia.
- Implementar un algoritmo para la asignación automática de grupos (máximo 70 estudiantes por grupo).
- Generar reportes estadísticos y listas oficiales en formatos exportables.

### 1.3 DESCRIPCIÓN DEL PROBLEMA
Actualmente, el proceso de admisión de la FICCT involucra el manejo de grandes volúmenes de información de postulantes, control de requisitos, registro de notas de múltiples materias (Computación, Matemáticas, Inglés, Física) y la organización manual de grupos. Este proceso manual es propenso a errores, genera cuellos de botella administrativos y retrasa la publicación de resultados oficiales.

### 1.4 ALCANCE
El sistema abarcará el ciclo completo de admisión, desde el registro inicial del postulante hasta la publicación de sus notas finales y asignación de grupo. Incluirá la gestión de roles (Administrador, Docente), registro de notas, cálculo de grupos mediante algoritmos y generación de reportes gerenciales.

---

## 2. MARCO TEÓRICO
*([Nota para el estudiante: Aquí debes incluir definiciones de los lenguajes y frameworks utilizados: PHP, Laravel, PostgreSQL, HTML5, CSS3, Tailwind CSS, JavaScript, Modelo Vista Controlador (MVC), etc.])*

---

## 3. MODELO DE NEGOCIO
*([Nota: Describir cómo funciona el proceso real en la facultad: El estudiante llega, presenta requisitos, paga, se inscribe, da exámenes, aprueba/reprueba.])*

---

## 4. FT CAPTURA DE REQUISITOS
*(Ver sección de requerimientos funcionales del documento original).*

---

## 5. FT. ANÁLISIS
*(Modelado del sistema, identificación de entidades principales como Usuario, Postulante, Examen, Grupo, Materia).*

---

## 6. FT. DISEÑO

### 6.1 Diseño de Arquitectura (Física y Lógica)
- **Física:** Servidor en la nube (ej. AWS, Supabase para DB), cliente web (navegador).
- **Lógica:** Patrón MVC proporcionado por Laravel.

### 6.2 Diseñar Caso de Uso (Diagrama de Secuencia)
*([Nota: Insertar imagen del diagrama de casos de uso/secuencia aquí])*

### 6.3 Diseño de Datos (Lógico y Físico)
- Uso de PostgreSQL como motor de base de datos relacional.
- Diagrama Entidad-Relación (ER).

---

## 7. FT. IMPLEMENTACIÓN

### 7.1 Herramientas de desarrollo de la aplicación WEB
- Backend: PHP 8+, Laravel 10/11
- Frontend: Blade Templates, Tailwind CSS
- Base de Datos: PostgreSQL (Supabase)
- Entorno Local: Laragon

### 7.2 Implementación de la Arquitectura del Sistema
El sistema se divide en Módulos (Autenticación, Postulantes, Exámenes, Grupos, Reportes) interactuando a través de Controladores.

### 7.3 Implementación de la Arquitectura del Subsistemas
*([Nota: Detallar la implementación del cálculo del promedio y cálculo de grupos con la fórmula CEIL(TotalInscritos/70)])*

---

## 8. CONCLUSIÓN
El desarrollo de esta aplicación web permite a la FICCT modernizar su proceso de admisión, reduciendo errores humanos y tiempos de espera.

---

## 9. RECOMENDACIÓN
Se recomienda mantener actualizaciones constantes del framework Laravel para asegurar la protección contra vulnerabilidades y capacitar al personal administrativo en el uso del panel.

---

## 10. BIBLIOGRAFÍA
- Documentación oficial de Laravel: https://laravel.com/docs
- Documentación de PostgreSQL: https://www.postgresql.org/docs/
- Tailwind CSS: https://tailwindcss.com/docs

---

## 11. ANEXOS
- Repositorio GitHub: [Insertar URL]
- Código QR: [Insertar Imagen QR]
