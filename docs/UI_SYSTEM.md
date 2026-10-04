# InventoryOS UI System

## Direction
InventoryOS uses a dark, operations-focused SaaS interface inspired by the supplied dashboard and responsive sidebar references. The design is applied globally rather than only to the dashboard.

## Global shell
- Dark navy application background with cyan primary accent.
- Collapsible desktop sidebar and mobile off-canvas navigation.
- InventoryOS branded logo/mark.
- Global search with `Ctrl + K` focus shortcut.
- Persistent user/security context in the top bar.
- Consistent cards, tables, forms, badges, buttons, empty states and notifications.

## Authentication
The login screen uses the same visual language as the application shell: branded identity, operational value statement, security status, accessible form controls, password visibility toggle, remember-session option, and clear validation errors.

## Dashboard
The dashboard uses live application data for sales, purchases, gross profit, inventory valuation, customers, returns, expenses, low-stock records, warehouse value, top products and stock movements. Reference imagery is used for layout and visual direction only; sample numbers are not copied into production data.

## Responsive behavior
- Desktop: full navigation and multi-column analytics.
- Tablet: reduced columns and stacked operational panels.
- Mobile: off-canvas sidebar, single-column forms, responsive POS, readable tables via horizontal scrolling, and touch-sized controls.

## Accessibility
Focus states, semantic labels, keyboard shortcuts, visible validation messages, sufficient contrast, and responsive controls are included in the shared visual system.

## Reports & Analytics Layout

The Reports page follows the same information hierarchy as the rest of InventoryOS:

1. Page heading and export action
2. Date-range filter controls
3. Four-column KPI grid on desktop
4. Two-column analytics panels
5. Operational tables and movement summaries

The report page uses responsive grids so metrics collapse to two columns and then one column on smaller screens. Date controls remain aligned and readable on mobile. Empty chart states are presented as deliberate states rather than large unstructured blank areas.
