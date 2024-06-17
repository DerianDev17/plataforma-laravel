# Plataforma Laravel

![Build Status](https://img.shields.io/badge/build-passing-brightgreen)
![Latest Stable Version](https://img.shields.io/badge/version-1.0.0-blue)
![License](https://img.shields.io/badge/license-MIT-green)

## Descripción
Plataforma Laravel es un proyecto basado en el framework Laravel, diseñado para proporcionar una base sólida para el desarrollo de aplicaciones web. Este repositorio incluye una estructura de directorios bien organizada y varios componentes esenciales para facilitar el desarrollo.

## Características
- **Autenticación y Autorización**: Configuración básica para gestionar usuarios y roles.
- **Gestión de Base de Datos**: Migraciones, seeders, y factories para facilitar la creación y administración de la base de datos.
- **Frontend Integrado**: Uso de TailwindCSS para el diseño y Vue.js para interactividad.
- **Pruebas Automatizadas**: Conjunto de pruebas unitarias y funcionales utilizando PHPUnit.

## Requisitos
- PHP >= 7.4
- Composer
- Node.js y npm
- MySQL

## Instalación
1. Clona el repositorio:
    ```bash
    git clone https://github.com/DerianDev17/plataforma-laravel.git
    ```
2. Navega al directorio del proyecto:
    ```bash
    cd plataforma-laravel
    ```
3. Instala las dependencias de PHP:
    ```bash
    composer install
    ```
4. Instala las dependencias de JavaScript:
    ```bash
    npm install
    ```
5. Configura el archivo de entorno:
    ```bash
    cp .env.example .env
    ```
6. Genera la clave de la aplicación:
    ```bash
    php artisan key:generate
    ```
7. Configura la base de datos en el archivo `.env` y ejecuta las migraciones:
    ```bash
    php artisan migrate --seed
    ```
8. Compila los assets:
    ```bash
    npm run dev
    ```

## Uso
Para iniciar el servidor de desarrollo:
```bash
php artisan serve
