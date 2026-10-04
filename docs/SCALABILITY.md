# Scalability & Load Balancing

InventoryOS is designed to run as one XAMPP instance during development and as multiple application instances in production.

## Load-balancing strategies

The project includes a strategy abstraction for:

- `round_robin`
- `weighted_round_robin`
- `least_connections`

Configured with `LB_ALGORITHM`.

These classes are an application-level abstraction for selecting a healthy server from a registry. In production, an infrastructure load balancer (Apache, Nginx, HAProxy, cloud LB, etc.) should normally perform the actual network routing.

## Horizontal-scaling requirements

When running multiple instances:

- sessions should use shared storage or stateless authentication
- rate limiting should use shared cache storage
- queues should use a shared queue backend
- uploaded files should use shared/object storage
- database remains the system of record
- request IDs and audit events remain available across instances

## Local XAMPP mode

One instance is sufficient. The load-balancing layer remains dormant and does not require multiple local Apache processes.
