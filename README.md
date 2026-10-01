# Mirada Salvaje — Sistema de Gestión Zoológica

Proyecto del curso **Análisis de Sistemas II** (UMG), a cargo del Ing. Carlos Valentín Valenzuela. Sistema de control interno para el zoológico "Mirada Salvaje", desarrollado como prototipo funcional que cubre gestión de limpieza, alimentación, control clínico, y entradas/promociones al público.

## Integrantes

| Nombre | Carné |
|---|---|
| Blanky Marisol López Marroquín (Coordinadora) | 0905-23-5227 |
| Melki Bladimir Ortiz Martínez | 0905-23-6329 |
| Yenci María Hernández Martínez | 0905-23-6756 |
| Alan Steven Marroquín Villaseñor | 0905-23-15264 |
| Carlos Daniel Ramos Morán | 0905-23-14141 |


## Objetivo

Aplicar los conocimientos de análisis, diseño, arquitectura y seguridad de software en una propuesta profesional de sistema, con un prototipo funcional (no de producción) que demuestre el flujo completo de datos y la arquitectura elegida.

## Módulos del sistema

| Módulo | Descripción | Responsable |
|---|---|---|
| Gestión de limpieza | Jaulas, sanitarios, jardines, área de juegos y oficinas. Turnos y verificación de tareas | Melki |
| Gestión de alimentación | Horarios, dietas e inventario de alimentos por especie/individuo | Yenci |
| Control clínico | Medicamentos, vacunas y vitaminas por animal, con alertas de refuerzo | Alan |
| Entradas y promociones | Venta y consulta de entradas y promociones, cara al público | Carlos |
| Autenticación e integración | Login, roles, arquitectura general y documento final | Blanky |

## Stack tecnológico

- **Backend:** Laravel 13 (PHP 8.3)
- **Base de datos:** MySQL
- **Frontend:** Blade (plantillas del lado del servidor)
- **Autenticación:** Laravel Auth (sesiones), con control de acceso por rol mediante middleware propio (`rol:...`)
- **Control de versiones:** Git / GitHub

## Roles del sistema

El acceso está controlado por rol de usuario, verificado en cada módulo con middleware de autenticación (`auth`) más rol:

- `admin` — acceso total
- `veterinario` — módulo de control clínico
- `cuidador` — módulos de alimentación y limpieza (lectura)
- `limpieza` — módulo de gestión de limpieza
- `recepcion` — panel administrativo de entradas y promociones

## Instalación y ejecución local

```bash
git clone https://github.com/blankymarisol/mirada-salvaje.git
cd mirada-salvaje
composer install
cp .env.example .env
php artisan key:generate
# configurar credenciales de base de datos en .env
php artisan migrate:fresh --seed
php artisan serve
```

## Usuarios de prueba

El seeder crea un usuario por rol, todos con la contraseña `password`:

| Rol | Correo |
|---|---|
| Admin | admin@mirada-salvaje.test |
| Veterinario | veterinario@mirada-salvaje.test |
| Cuidador | cuidador@mirada-salvaje.test |
| Limpieza | limpieza@mirada-salvaje.test |
| Recepción | recepcion@mirada-salvaje.test |

Inicia sesión desde `/login` con cualquiera de estos usuarios para probar el sistema según el rol.

## Estructura del proyecto

app/Http/Controllers/ Controladores por módulo (uno por dominio)
app/Http/Controllers/Admin/ Controladores del panel administrativo de entradas
app/Http/Controllers/Auth/ LoginController (login/logout)
app/Models/ Modelos Eloquent (uno por entidad)
database/migrations/ Migraciones versionadas de cada tabla
database/seeders/ Seeders, incluyendo usuarios de prueba por rol
resources/views/ Vistas Blade, organizadas por módulo
routes/web.php Rutas de la aplicación, agrupadas por módulo y protegidas por rol

## Estado del proyecto

Prototipo funcional en desarrollo activo.
