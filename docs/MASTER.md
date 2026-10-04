# InventoryOS — Master Project Document

**Version:** 0.8

## 1. What this project is

InventoryOS is a modern responsive inventory and POS platform rebuilt independently from Stocky v5.0's concepts. It is intentionally not a copy of Stocky's project structure.

## 2. Runtime target

Development target: Windows + XAMPP + Apache + PHP 8.2+ + MySQL/MariaDB.

Production target: HTTPS/TLS, externalized secrets, shared cache/session/queue infrastructure where required, and multiple application instances behind an infrastructure load balancer.

## 3. Completed modules

| Version | Module | Status |
|---|---|---|
| v0.1 | Foundation/UI shell | Complete |
| v0.2 | Identity & Security | Complete |
| v0.3 | Inventory Core | Complete |
| v0.4 | Sales & POS | Complete |
| v0.5 | Purchasing & Suppliers | Complete |
| v0.6 | Reports & Analytics | Complete |
| v0.7 | API & Scalability | Complete |
| v1.0.0 | Advanced Operations | Complete |

## 4. Architecture

```text
Browser / POS / External API Client
              |
           HTTPS
              |
       Apache / Load Balancer
              |
        Laravel Application
              |
   +----------+----------+
   |          |          |
 Security  Business    API v1
   |        Services      |
   +----------+----------+
              |
          MySQL/MariaDB
```

## 5. Security baseline

- Authentication and session protection
- Role/permission authorization
- API keys stored as hashes
- Explicit API abilities
- Per-key rate limiting
- Request IDs
- CORS allowlist
- CSRF for browser sessions
- Input validation
- ORM/parameterized queries
- Audit logs
- Secret isolation
- TLS/HSTS in production
- Least privilege
- Idempotency support for API mutations

## 6. API v1

Base path: `/api/v1`.

Current read endpoints: health, key identity, products, inventory, and report summary. See `API.md`.

## 7. Advanced operations

- Customer directory and customer-linked POS sales
- Sales returns and refunds with restock/damaged disposition
- Expense recording and expense categories
- Operational alerts via low-stock/reporting surfaces

## 8. Inventory integrity

Stock adjustments, transfers, sales, receiving, and returns use database transactions and row locking where concurrent changes could create inconsistent balances.

## 9. Documentation map

- `MASTER.md` — start here
- `PROJECT_CONTEXT.md` — continuation state and rules
- `ARCHITECTURE.md` — structure and boundaries
- `SECURITY.md` — controls and threat-oriented rules
- `DATABASE.md` — schema/domain reference
- `API.md` — API v1 contract
- `SCALABILITY.md` — horizontal scaling and load balancing
- `TESTING.md` — verification approach
- `FILE_MAP.md` — source tree map
- `CHANGELOG.md` — version history
