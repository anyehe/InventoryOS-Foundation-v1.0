# Production Hardening

## Release gate

Before production, verify all items below.

- [ ] `APP_ENV=production`
- [ ] `APP_DEBUG=false`
- [ ] HTTPS enforced end-to-end
- [ ] HSTS enabled only after HTTPS is confirmed
- [ ] Secure, HttpOnly, SameSite cookies
- [ ] Production secrets supplied outside Git
- [ ] Database account uses least privilege
- [ ] Database backups tested with a restore
- [ ] CORS contains only trusted origins
- [ ] API keys have narrow abilities and expiry where appropriate
- [ ] API rate limiting uses shared storage when multiple instances run
- [ ] Sessions/cache/queues use shared infrastructure when required for horizontal scaling
- [ ] Audit logs are retained and protected from ordinary application users
- [ ] Password policy is enabled
- [ ] Authorization tests cover every privileged route
- [ ] SQL injection tests are clean
- [ ] CSRF tests are clean for browser mutations
- [ ] Error responses do not expose stack traces or secrets
- [ ] Dependency audit is clean
- [ ] Restore procedure has been rehearsed

## Local XAMPP

Local development may use HTTP and `SESSION_SECURE_COOKIE=false`. Do not carry that setting into production.

## Backups

Use `scripts/backup-database.bat` as a local starting point. Production backups should use an automated, encrypted, off-host backup system with retention and restore testing.

## Load balancing

The application is designed to be horizontally scalable, but TLS termination, health checks, and request distribution belong at the reverse proxy/load-balancer layer. Use shared Redis/cache/session infrastructure where required.
