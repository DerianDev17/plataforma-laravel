# Semilla Digital

![Build Status](https://img.shields.io/badge/build-passing-brightgreen)
![Version](https://img.shields.io/badge/version-1.0.0-blue)
![License](https://img.shields.io/badge/license-MIT-green)

Plataforma educativa construida sobre Laravel, diseñada para la gestion academica de Semilla Digital. Incluye administracion de estudiantes, cursos, sesiones, asistencias, reuniones Zoom, recursos digitales y mas.

## Stack

- **Backend**: Laravel 8, PHP `^7.3|^8.0`, MySQL
- **Autenticacion**: Jetstream 1 + Fortify + Sanctum (login por `username`, verificacion de email)
- **Frontend autenticado**: Blade + Livewire 2 + `public/css/modern.css`
- **Landing publica**: Astro 6 + Tailwind CSS 4 (`resources/astro/src/`)
- **RBAC**: Custom `User` <-> `Role` <-> `Ability` con gates y `@can(...)` (no Spatie)
- **Integraciones**: Excel (Maatwebsite), DomPDF, Zoom API (Guzzle), Livewire Datatables
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

- PHP >= 7.3
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
php artisan test                  # Ejecutar pruebas
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
├── Http/Livewire/     # Componentes Livewire (usuarios, asistencias, drives, etc.)
├── Models/            # Modelos Eloquent (User, Role, Ability, Course, etc.)
├── Utils/             # Utilidades (validacion cedula, horarios)
config/                # Configuraciones (fortify, jetstream, sanctum, zoom, etc.)
resources/
├── astro/src/         # Landing publica (Astro + Tailwind 4)
├── views/             # Vistas Blade (admin, users, meetings, pages, etc.)
routes/web.php         # Rutas protegidas con auth:sanctum y gates
api/index.js           # Proxy Express para deploy en Vercel
```

## Deploy en Vercel

El proyecto incluye `vercel.json` configurado para usar `api/index.js` como proxy hacia el servidor Laravel. Asegurate de que las variables de entorno de Vercel coincidan con las de `.env.example`.

## Licencia

MIT
