# AGENTS.md

Project guide for Codex agents in this Laravel repo. Favor small, clear, verified changes; optimize existing code before adding new code.

## Stack

- Laravel 8, PHP `^7.3|^8.0`, Livewire 2, Jetstream 1, Fortify, Sanctum, MySQL.
- Custom RBAC, not Spatie: `User` <-> `Role` <-> `Ability`, checked with gates and `@can(...)`.
- Public landing: Astro 6 + Tailwind 4 in `resources/astro/src/`.
- Authenticated app: Blade/Livewire + `public/css/modern.css` and small static JS assets.
- Integrations/packages: Excel, DomPDF, Guzzle/Zoom.

## Operating Rules

- Read nearby files first and follow local patterns, names, helpers, and components.
- Reduce duplication and simplify before introducing abstractions.
- Keep PHP compatible with Laravel 8/PHP 7.3-era constraints unless the touched file already requires newer syntax.
- Do not add dependencies, build systems, or broad refactors unless the user asks.
- Use skills when relevant: Laravel/Livewire for backend, frontend-design/accessibility for UI, browser for visual checks.

## Frontend Map

| Area | Source | Build |
| --- | --- | --- |
| Landing | `resources/astro/src/` | `pnpm build` or `pnpm build:landing` |
| Dashboard/auth | `resources/views/`, `public/css/modern.css`, `public/js/semilla-admin.js` | No build |

Only rebuild the target you touched. Blade-only or `public/css/modern.css` changes do not require a frontend build.

## Auth, Access, UI

- Login uses `username`, not email. Email verification is enabled. Web routes use `auth:sanctum`.
- Important abilities: `edit_users`, `crud_drives`, `read_course_programs`.
- Livewire admin actions must authorize explicitly, usually with `AuthorizesLivewireActions` or gates.
- Brand is `Semilla Digital`; do not reintroduce old `Eus3`/`Preuniversitario` labels.
- Dashboard/auth logos live in `public/brand/` via `resources/views/components/brand/logo.blade.php`.
- Landing/Astro may still reference legacy `/storage/img/...` assets; update source and rebuild landing when changing that area.
- `public/css/modern.css` is the active design system. Reuse `eus-*` classes and `--eus-*` tokens; do not rename the prefix.
- Do not restore Bootstrap CDN or `public/css/custom.css`.
- For UX work, verify desktop/mobile, overflow, console errors, focus states, and text fitting.

## Layouts And Gotchas

- `<x-app-layout>` maps to `resources/views/layouts/admin.blade.php`; guest screens use `resources/views/layouts/guest.blade.php`.
- Sidebar navigation is permission-gated in the admin layout.
- `routes/web.php` has explicit routes and `$id` controller parameters; do not assume implicit model binding.
- `UserController` only keeps custom actions; removed CRUD stubs should stay removed.
- `Meetings\Show::check_payment()` currently always returns `true`.
- Keep deleted files deleted unless requested: `ContratoDigital.php`, `SimulatorController.php`, `Example.php`, `encuesta.blade.php`, `register-student.blade.php`, `two-factor-challenge.blade.php`, `navigation-dropdown.blade.php`, `layouts/app.blade.php`, `public/css/custom.css`.

## Commands

```bash
php artisan test
php artisan view:cache
php artisan view:clear
php artisan route:list
php artisan serve
pnpm build
```

Use `view:cache` to catch Blade errors, then `view:clear` after local verification.
