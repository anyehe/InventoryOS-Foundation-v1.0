# InventoryOS — Database Reference

## Identity

- `users`
- `roles`
- `permissions`
- role/permission pivot
- `sessions`
- `api_keys`
- `audit_logs`

## Catalog

- `categories`
- `brands`
- `units`
- `products`

## Inventory

- `warehouses`
- `warehouse_stocks`
- `stock_movements`
- `stock_transfers`

## Sales

- `sales`
- `sale_items`
- `payments`

## Purchasing

- `suppliers`
- `purchases`
- `purchase_items`
- `purchase_returns`
- `purchase_return_items`

## Core relationships

```text
Product 1---* WarehouseStock *---1 Warehouse
Product 1---* StockMovement *---1 Warehouse
Product 1---* SaleItem *---1 Sale
Product 1---* PurchaseItem *---1 Purchase
Supplier 1---* Purchase
Purchase 1---* PurchaseItem
Purchase 1---* PurchaseReturn
PurchaseReturn 1---* PurchaseReturnItem
```

## Stock invariant

For each product and warehouse:

```text
warehouse_stocks.quantity >= 0
```

Normal stock changes must create a corresponding movement record.

## Transaction rule

A multi-record operation that changes money or stock must use a database transaction and lock the rows needed to make the decision.


## v0.8 operational tables

- `customers` — customer profiles and account limits.
- `sales.customer_id` — optional customer relationship for POS transactions.
- `sale_returns` — return/refund header, disposition, refund method and audit context.
- `sale_return_items` — item-level return quantities linked to original sale items.
- `expense_categories` — controlled operating expense categories.
- `expenses` — recorded operating expenses with payment method, date and warehouse context.

Return quantities are checked against the original sale quantity minus previously returned quantities. Restock returns increase warehouse stock inside the same transaction as the return record.
