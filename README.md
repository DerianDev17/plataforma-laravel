# Semilla Digital

![Build Status](https://img.shields.io/badge/build-passing-brightgreen)
![Version](https://img.shields.io/badge/version-2.0.0-blue)
![License](https://img.shields.io/badge/license-MIT-green)
![Tests](https://img.shields.io/badge/tests-100%2F100-brightgreen)

Plataforma educativa construida sobre Laravel, disenada para la gestion academica de Semilla Digital. Incluye administracion de estudiantes, cursos, sesiones, asistencias, reuniones Zoom, recursos digitales y mas.

## Stack

- **Backend**: Laravel 11, PHP `^8.2`, MySQL
- **Autenticacion**: Jetstream 5 + Fortify + Sanctum 4 (login por `username`, verificacion de email)
- **Frontend autenticado**: Blade + Livewire 3 + `public/css/modern.css`
- **Landing publica**: Astro 6 + Tailwind CSS 4 (`resources/astro/src/`)
- **RBAC**: Custom `User` <-> `Role` <-> `Ability` con gates y `@can(...)` (no Spatie)
- **Integraciones**: Excel (Maatwebsite), DomPDF, Zoom API (Guzzle)
- **Deploy**: Vercel (proxy Express en `api/index.js`) + Docker (Sail)

## Caracteristicas principales

- Panel administrativo con control de acceso basado en roles y habilidades
- Gestion de estudiantes, cursos, sesiones y grupos
- Integracion con Zoom: creacion de meetings, registro de participantes, control de aprobacion
- Registro y consulta de asistencias por curso/sesion
- Carga masiva de estudiantes y actualizacion de cuentas via Excel
- Recursos digitales y material para estudiantes
- Programas de curso y oferta academica
- Clases grabadas
- Calculadora de carreras universitarias
- Encuestas para estudiantes
- Generacion de certificados PDF
- Envio masivo de correos de cuentas
- Contactos de estudiantes

## Requisitos

- PHP >= 8.2
- Composer
- Node.js >= 18 y pnpm
- MySQL 8.0
- (Opcional) Docker + Docker Compose para entorno Sail

## Instalacion

1. Clona el repositorio:
   ```bash
   git clone https://github.com/DerianDev17/plataforma-laravel.git
   cd plataforma-laravel
   ```

2. Instala dependencias PHP:
   ```bash
   composer install
   ```

3. Instala dependencias frontend y compila la landing:
   ```bash
   pnpm install
   pnpm build
   ```

4. Configura el entorno:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. Configura la base de datos en `.env` y ejecuta migraciones:
   ```bash
   php artisan migrate --seed
   ```

6. (Opcional) Crea un storage link para archivos:
   ```bash
   php artisan storage:link
   ```

## Desarrollo

Inicia el servidor de desarrollo:

```bash
php artisan serve
```

En otra terminal, para la landing (hot reload):

```bash
pnpm dev
```

### Con Docker (Sail)

```bash
./vendor/bin/sail up -d
./vendor/bin/sail artisan migrate --seed
```

Laravel Sail incluye MySQL, Redis y MailHog.

## Comandos utiles

```bash
php artisan test                  # Ejecutar pruebas (100/100)
php artisan view:cache            # Compilar vistas (detectar errores)
php artisan view:clear            # Limpiar cache de vistas
php artisan route:list            # Listar rutas registradas
pnpm build                        # Compilar landing (Astro)
```

## Habilidades (abilities) del sistema

| Habilidad | Uso |
|-----------|-----|
| `edit_users` | Gestion de usuarios, roles, asistencias, Zoom, Excel, encuestas |
| `crud_drives` | Gestion de drives, estudiantes y recursos de curso |
| `read_course_programs` | Acceso a programas de curso |

## Estructura del proyecto

```
app/
├── Actions/            # Acciones de Fortify/Jetstream (CreateNewUser, DeleteUser)
├── Exports/            # Exportaciones Excel
├── Http/
│   ├── Controllers/    # Controladores (Zoom, Asistencias, Updater, Encuesta)
│   ├── Livewire/       # Componentes Livewire (asistencias, meetings, drives, etc.)
│   └── Middleware/     # Middleware personalizados
├── Imports/            # Importaciones Excel (StudentsImport, StudentsImportar)
├── Models/             # Modelos Eloquent (User, Role, Ability, Course, etc.)
├── Providers/          # Service Providers (App, Auth, Fortify, Jetstream)
├── Services/           # Servicios de dominio (LiveClass)
└── View/Components/    # Componentes Blade (AppLayout, GuestLayout)
config/                 # Configuraciones (fortify, jetstream, sanctum, livewire, etc.)
database/
├── factories/          # Model factories
├── migrations/         # Migraciones de base de datos
└── seeders/            # Seeders (PermissionsSeeder, StudentGroupSeeder)
resources/
├── astro/src/          # Landing publica (Astro + Tailwind 4)
└── views/              # Vistas Blade (admin, auth, livewire, profile, etc.)
routes/
├── web.php             # Rutas protegidas con auth:sanctum y gates
├── api.php             # API para Zoom meetings
└── console.php         # Tareas programadas (Schedule)
tests/
├── Feature/
│   ├── Attendance/     # Tests de registro de asistencias
│   ├── Auth/           # Tests de login por username
│   ├── Imports/        # Tests de importacion Excel
│   ├── Roles/          # Tests de autorizacion RBAC
│   └── Zoom/           # Tests de sincronizacion Zoom
└── Unit/
    ├── User/           # Tests de username y payment deadline
    └── AbilityRoleTest # Tests de modelos Ability/Role
bootstrap/app.php       # Configuracion de la app (middleware, routing, rate limiting)
api/index.js            # Proxy Express para deploy en Vercel
```

## Tests

El proyecto tiene **100 tests** que cubren:

| Suite | Archivos | Tests | Cobertura |
|-------|----------|-------|-----------|
| `Unit/AbilityRoleTest` | 1 | 4 | Relaciones y creacion de modelos |
| `Unit/User/` | 2 | 29 | Generacion de username y estados de pago |
| `Feature/Auth/` | 2 | 17 | Login, dashboard, autenticacion |
| `Feature/Attendance/` | 2 | 11 | Registro de asistencias (Livewire) |
| `Feature/Roles/` | 1 | 12 | Autorizacion RBAC, gates, caché |
| `Feature/Imports/` | 1 | 7 | Importacion y actualizacion Excel |
| `Feature/Zoom/` | 1 | 7 | Sincronizacion de registrants |
| `Feature/*` restantes | 3 | 13 | CRUD usuarios, live class, asistencias admin |

```bash
php artisan test   # 100/100 tests pasan
```

## Notas de migracion (Laravel 8 -> 11)

La app fue migrada incrementalmente de Laravel 8 a 11. Cambios estructurales relevantes:

- **`bootstrap/app.php`**: Configuracion centralizada de middleware, routing y rate limiting (L11 no usa `app/Http/Kernel.php` ni `app/Console/Kernel.php`)
- **Livewire 3**: `$this->emit()` reemplazado por `$this->dispatch()`, namespace configurado en `config/livewire.php`
- **Jetstream 5**: Componentes `x-jet-*` renombrados a `x-*`
- **Paquetes removidos**: `fideloper/proxy`, `fruitcake/laravel-cors`, `facade/ignition`, `mediconesystems/livewire-datatables`, `tegimus/php-izitoast`, `doctrine/dbal`
- **Configs eliminados**: `config/cors.php`, `config/hooks.php`

## Deploy en Vercel

El proyecto incluye `vercel.json` configurado para usar `api/index.js` como proxy hacia el servidor Laravel. Asegurate de que las variables de entorno de Vercel coincidan con las de `.env.example`.

## Licencia

MIT
