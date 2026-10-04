# InventoryOS v0.8

Modern responsive inventory/POS platform with a security-first API and scalable architecture.

## Start here

Read `docs/MASTER.md`, then `docs/PROJECT_CONTEXT.md`.

## XAMPP

Place the project in `C:\xampp\htdocs\inventory-platform`, create a MySQL/MariaDB database, copy `.env.example` to `.env`, run `composer install`, `php artisan key:generate`, then `php artisan migrate:fresh --seed`.

## API

Base path: `/api/v1`. See `docs/API.md`.

## Security

See `docs/SECURITY.md`.


## v0.9 production hardening
See `docs/PRODUCTION_HARDENING.md` and `docs/RELEASE_CHECKLIST.md` before deployment.
