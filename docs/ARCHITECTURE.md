# InventoryOS — Architecture

## Application layers

1. **Presentation** — Blade views, CSS and JavaScript.
2. **HTTP** — routes, controllers and middleware.
3. **Business services** — inventory, sales and purchasing transaction logic.
4. **Domain/data models** — Eloquent models.
5. **Persistence** — MySQL/MariaDB.
6. **Cross-cutting security** — authentication, authorization, rate limiting, CORS, CSRF, headers, audit logging and request IDs.

## Request lifecycle

```text
HTTP request
  -> web/api middleware
  -> authentication
  -> authorization
  -> controller
  -> service
  -> database transaction
  -> audit event
  -> response
```

## Scalability target

The application should be deployable as multiple stateless web instances:

```text
Client
  |
  v
Load balancer
  |----> App instance A
  |----> App instance B
  `----> App instance C
           |
           +--> shared DB
           +--> shared cache/rate-limit store
           +--> shared queue
```

Supported algorithm concepts for v0.7:

- round robin
- weighted round robin
- least connections

The application must not rely on instance-local memory for state that needs to survive a load-balanced request.
