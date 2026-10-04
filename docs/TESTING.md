# Testing

## Static verification

Every release should receive a PHP syntax sweep and JSON/composer validation before packaging.

## Local Laravel verification

Run on the XAMPP machine:

```bash
composer install
php artisan key:generate
php artisan migrate:fresh --seed
php artisan route:list
php artisan test
```

## API smoke tests

1. `GET /api/v1/health` should return HTTP 200 when the database is reachable.
2. Protected endpoints without `X-API-Key` should return HTTP 401.
3. A key without the required ability should return HTTP 403.
4. Exceeding `API_RATE_LIMIT` should return HTTP 429.
5. Reusing an idempotency key with a different request body should return HTTP 409.
6. CORS should allow only configured origins.
7. Production HTTPS should enable HSTS.

## Security regression tests

Add feature tests before exposing new write APIs for authentication, authorization, validation, SQL injection resistance, CSRF, rate limits, idempotency, audit logging, and stock/payment transaction integrity.


## v0.8 scenarios

- Create and search a customer with validation failures for duplicate codes.
- Complete a POS sale linked to a customer.
- Return less than or equal to the remaining returnable quantity.
- Reject a return greater than the remaining quantity.
- Verify a restock return increases warehouse stock atomically.
- Verify a damaged return creates the return record without increasing sellable stock.
- Record an expense and verify the audit event.
