# InventoryOS API v1

Base path: `/api/v1`

## Authentication

Protected endpoints require `X-API-Key` (configurable via `API_KEY_HEADER`). Keys are stored as SHA-256 hashes and can expire or be revoked.

Each key carries explicit read abilities. A request with a valid key but insufficient ability receives `403`.

## Rate limiting

API requests are rate-limited per API key using Laravel's rate limiter. Configure with `API_RATE_LIMIT` (requests/minute). A `429` response means the key exceeded its allowance.

## Idempotency

Mutating endpoints use the `Idempotency-Key` header. The middleware is ready for transactional POST/PUT/PATCH/DELETE endpoints and rejects reuse of the same key with a different request body.

## Endpoints

| Method | Endpoint | Ability | Purpose |
|---|---|---|---|
| GET | `/api/v1/health` | public | Application/database health |
| GET | `/api/v1/me` | key | Key identity and abilities |
| GET | `/api/v1/products` | products:read | Paginated active products |
| GET | `/api/v1/products/{product}` | products:read | Product detail and stock |
| GET | `/api/v1/inventory` | inventory:read | Warehouse stock |
| GET | `/api/v1/reports/summary` | reports:read | Sales/purchase/inventory summary |

## Response convention

Successful resources use:

```json
{
  "data": {},
  "meta": {
    "api_version": "v1",
    "request_id": "..."
  }
}
```

Errors use a JSON `message`, optional `errors`, and an HTTP status appropriate to the failure.

## CORS and TLS

CORS is allowlist-based through `CORS_ALLOWED_ORIGINS`. Local development can use HTTP; production must use HTTPS/TLS and enable HSTS.


## v0.8 API roadmap

Operational write endpoints are intentionally being added after the domain workflows are stable. Customer, return/refund, and expense mutations should use the same API-key ability model, validation, idempotency, audit logging, and transactional rules as the existing API.
