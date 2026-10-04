# File Map

```text
app/
├── Http/Controllers/Api/V1/      # Versioned API controllers
├── Http/Controllers/Admin/       # User/API-key/audit administration
├── Http/Controllers/Inventory/   # Inventory web controllers
├── Http/Controllers/Sales/       # POS/sales web controllers
├── Http/Controllers/Purchasing/  # Purchasing web controllers
├── Http/Controllers/Reports/     # Reporting web controllers
├── Http/Middleware/               # Auth, security, CORS, API controls
├── Models/                        # Eloquent domain models
└── Services/                     # Business logic and load-balancing abstractions

database/
├── migrations/                   # Schema history
└── seeders/                      # Local demo data
resources/views/                  # Blade UI
routes/
├── web.php                       # Browser routes
└── api.php                       # `/api/v1` routes
docs/                             # Canonical project documentation
scripts/                          # XAMPP setup helpers
tests/                            # Automated test suite
```
