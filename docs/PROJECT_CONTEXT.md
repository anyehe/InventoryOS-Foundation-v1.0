# InventoryOS Continuation Context

## Purpose

InventoryOS is an independently structured Laravel inventory/POS platform inspired by the workflows visible in Stocky v5.0. Stocky is a reference for business capabilities, not a source tree to copy.

## Current version

**v1.0.0 — Advanced Operations

## Completed

- v0.1 foundation and responsive shell
- v0.2 identity/security foundation
- v0.3 inventory core
- v0.4 sales/POS
- v0.5 purchasing/suppliers
- v0.6 reports/analytics and consolidated docs
- v0.7 versioned API foundation, API abilities, per-key rate limiting, idempotency support, and load-balancing strategies
- v1.0.0 customers, customer-linked POS sales, returns/refunds, expense operations, and operational reporting signals

## Local target

Windows + XAMPP + Apache + MySQL/MariaDB. The project must remain runnable locally before production hardening.

## Non-negotiable security requirements

AuthN, AuthZ, input validation, SQL-injection resistance, per-key rate limiting, CORS allowlisting, CSRF protection, secret management, TLS in production, least privilege, audit logs, secure authentication lifecycle, and scalable/stateless design.

## Important implementation rule

Business state transitions must be server-side and transactional. The frontend is never trusted for prices, stock quantities, permissions, totals, or role claims.

## Next work

Complete authenticated write APIs with idempotency, API resource pagination/error conventions, integration tests, production cache/session/queue configuration, UI polish/accessibility, and deployment hardening.


## v1.0.0 hardening state
- Production configuration template added.
- Password policy strengthened for administrator-created users.
- API rate-limit window is configurable.
- CORS preflight/headers hardened.
- Local Apache virtual-host example added.
- Local database backup starter added.
- Production hardening and release checklists added.
- v1.0 should be treated as a release-candidate gate, not an automatic production claim.
