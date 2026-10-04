# InventoryOS v1.0.0 Release Candidate

InventoryOS is a modern, responsive inventory, POS, purchasing, reporting and operations platform designed to run locally on Windows/XAMPP and remain ready for production deployment.

## Included
- Responsive application UI and dashboard
- Authentication and RBAC/permissions
- Products, categories, brands, units and warehouses
- Stock balances, adjustments and transfers
- POS, sales, payments and receipts
- Suppliers, purchase orders, receiving and purchase returns
- Customers, sales returns/refunds and expenses
- Reports and analytics
- Versioned API v1
- API keys, abilities and per-key rate limiting
- CORS, CSRF, request IDs and idempotency infrastructure
- Audit logging and security headers
- Load-balancing strategy abstractions
- XAMPP/Apache setup and backup tooling
- Consolidated project documentation

## Verification boundary
Source-level PHP syntax validation is performed during packaging. Full Laravel runtime validation must be completed on the target Windows/XAMPP environment because Composer dependencies, PHP extensions, Apache and MariaDB configuration are environment-specific.

## First local verification
```bash
composer install
copy .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
php artisan route:list
php artisan test
```

See `docs/MASTER.md` and `docs/RELEASE_CHECKLIST.md` for the complete project map and release procedure.

### v1.0.0 packaging correction
- Added the Laravel public front controller at `public/index.php`.
- Added the Apache `public/.htaccess` rewrite configuration.
- Added `public/robots.txt`.
- Ensured standard Laravel runtime/cache directories are represented in the archive.
