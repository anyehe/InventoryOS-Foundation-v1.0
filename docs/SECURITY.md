# Security Model

## Identity and authorization

Authentication is handled server-side. Authorization uses roles and explicit permissions for web actions. API keys use explicit API abilities.

## API controls

- API key hashing
- per-key rate limiting
- ability checks
- request IDs
- CORS allowlist
- idempotency support
- input validation
- ORM/parameterized database access

## Browser controls

- CSRF protection through Laravel web middleware
- secure session lifecycle
- security headers
- conditional HSTS for HTTPS

## Secrets

Secrets belong in environment configuration or a production secret manager. Never commit `.env` or real credentials.

## Audit

Security and business events are written through `AuditLogger`. Do not log passwords, full API keys, session secrets, or payment credentials.
