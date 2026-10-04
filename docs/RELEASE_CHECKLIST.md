# InventoryOS Release Checklist

## Functional
- [ ] Authentication
- [ ] Roles/permissions
- [ ] Inventory
- [ ] POS/sales
- [ ] Purchasing
- [ ] Customers
- [ ] Returns/refunds
- [ ] Expenses
- [ ] Reports
- [ ] API v1

## Security
- [ ] AuthN/AuthZ tests
- [ ] Input validation
- [ ] SQL injection tests
- [ ] CSRF tests
- [ ] CORS tests
- [ ] Rate-limit tests
- [ ] API key lifecycle tests
- [ ] Audit log tests
- [ ] Secure headers
- [ ] HTTPS/TLS configuration
- [ ] Secret scanning

## Operations
- [ ] Database migration test
- [ ] Seed test
- [ ] Backup test
- [ ] Restore test
- [ ] Queue/cache/session configuration
- [ ] Health endpoint
- [ ] Load-balancer health checks
- [ ] Performance smoke test

## UI
- [ ] Desktop responsive
- [ ] Tablet responsive
- [ ] Mobile responsive
- [ ] Keyboard navigation
- [ ] Accessible labels/focus states
- [ ] Empty/loading/error states

## Release
- [ ] `APP_DEBUG=false`
- [ ] Production secrets externalized
- [ ] Composer dependencies installed from lock file
- [ ] Tests passing
- [ ] No known critical/high dependency vulnerabilities
- [ ] Documentation updated
