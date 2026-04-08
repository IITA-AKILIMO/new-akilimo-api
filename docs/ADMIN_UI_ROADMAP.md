# Admin UI Roadmap

A management interface for users, lookup data (fertilizers, prices, factories, etc.), and operational monitoring.

---

## Overview

The admin UI will be an **Inertia.js + React** SPA served by Laravel, authenticated via Laravel sessions (separate from the API token/key auth used by mobile clients). Vite and Tailwind CSS 4 are already configured — Inertia.js slots in with minimal added complexity.

**Why Inertia.js over a standalone SPA?**
- Reuses existing Laravel routing, validation, and session auth — no separate admin API needed.
- No CORS, no token refresh logic, no duplicated auth layer.
- Blade-level simplicity for rendering, React-level richness for interactions.

---

## Current gaps

| Gap | What's needed |
|-----|--------------|
| All lookup data endpoints are GET-only | Add `POST`, `PUT`, `DELETE` for each resource |
| No user management endpoints | Add CRUD under `/v1/admin/users` or a dedicated web route |
| No admin ability scope | Add `admin` ability to `TokenAbility`; gate write endpoints |
| No web session auth | Add `POST /admin/login` + `auth:web` guard for Inertia routes |

---

## Phase 1 — Backend API Write Endpoints

All existing lookup controllers are read-only. Each needs full CRUD before the UI can manage data.

**Deliverables per resource (follow existing controller/repo/resource/request pattern):**

```
POST   /v1/admin/{resource}
PUT    /v1/admin/{resource}/{id}
DELETE /v1/admin/{resource}/{id}
```

**Resources to cover:**

| Resource | Key fields |
|----------|-----------|
| Fertilizers | `fertilizer_key`, `fertilizer_label`, `name`, `weight`, `country`, `available` |
| Fertilizer prices | `fertilizer_key`, `country`, `min_price`, `max_price` |
| Maize prices | `country`, `min_price`, `max_price` |
| Cassava prices | `country`, `min_price`, `max_price` |
| Potato prices | `country`, `min_price`, `max_price` |
| Starch prices | `country`, price fields |
| Default prices | `country`, price fields |
| Investment amounts | `country`, `min`, `max` |
| Operation costs | `country`, cost fields |
| Starch factories | `country`, `name`, `location` |
| Currencies | `code`, `name`, `symbol` |
| Cassava units | `unit`, `conversion_factor` |
| Translations | `locale`, `key`, `value` |

**Also add:**
- `GET/POST/PUT/DELETE /v1/admin/users` — user management
- `POST/DELETE /v1/admin/users/{id}/api-keys` — manage any user's API keys (admin only)

Gate all `/v1/admin/*` routes with a new `auth.token:admin` middleware check.

---

## Phase 2 — Web Session Auth & Inertia Scaffold

Install and wire Inertia.js alongside the existing API — they share the app but use different route groups.

**Steps:**
1. `composer require inertiajs/inertia-laravel`
2. `pnpm add @inertiajs/react react react-dom`
3. Add `HandleInertiaRequests` middleware to the `web` middleware group
4. Create `routes/web.php` with an `admin` prefix group using `auth:web`
5. Create `resources/js/app.tsx` as the Inertia entry point
6. Set up a shared layout: sidebar, breadcrumbs, flash notifications
7. Add `POST /admin/login` and `POST /admin/logout` web routes
8. Seed a default admin web session user (reuse `AdminUserSeeder` or extend it)

**Route structure:**
```
GET  /admin/login
POST /admin/login
POST /admin/logout

GET  /admin                       → Dashboard
GET  /admin/users
GET  /admin/fertilizers
GET  /admin/fertilizer-prices
GET  /admin/maize-prices
... etc
```

---

## Phase 3 — Core UI Components

Build the shared building blocks all modules will use.

| Component | Description |
|-----------|-------------|
| `DataTable` | Paginated, sortable, filterable table — wired to Inertia's `useForm` for filter state |
| `ResourceForm` | Generic create/edit form with server-side validation error display |
| `CountryFilter` | Dropdown to filter all country-scoped lookup tables |
| `ConfirmDialog` | Reusable delete confirmation modal |
| `FlashBanner` | Success/error flash message from Inertia's shared props |
| `Sidebar` | Nav with links to all resource sections |

---

## Phase 4 — User Management Module

**Screens:**
- **User list** — username, email, created date, active status; searchable
- **Create/edit user** — name, username, email, password (hashed), admin flag
- **User detail** — shows the user's active API keys; admin can revoke or delete them
- **Deactivate / delete** — soft-disable vs hard delete

**API calls:** `GET/POST/PUT/DELETE /v1/admin/users`

---

## Phase 5 — Lookup Data Modules

Each module follows an identical pattern: **list → create → edit → delete**. Use the `ResourceForm` and `DataTable` components from Phase 3.

### 5a — Fertilizers
- **Fertilizer master list** — filterable by country; toggle `available` inline
- **Fertilizer prices** — list per fertilizer/country; inline edit of min/max price

### 5b — Commodity Prices
Four separate modules sharing the same layout:
- Maize prices
- Cassava prices
- Potato prices
- Starch prices + default prices

Each: filterable by country, editable min/max price range.

### 5c — Supporting Data
- **Investment amounts** — per country, min/max
- **Operation costs** — per country
- **Starch factories** — per country, name, location
- **Currencies** — code, name, symbol
- **Cassava units** — unit name, conversion factor

### 5d — Translations
Bulk management view: locale selector, key search, inline value edit. Support CSV import/export for translators.

---

## Phase 6 — Monitoring & Observability

Read-only screens backed by existing data.

| Screen | Data source |
|--------|------------|
| **Dashboard** | `v_app_request_stats_view` — request counts, success rates, top countries |
| **Request log** | `api_requests` — paginated list; click to inspect `plumber_request` / `plumber_response` JSON |
| **User feedback** | `user_feedback` — filterable by `use_case`, `user_type`, country |

---

## Phase 7 — Hardening & Polish

| Item | Notes |
|------|-------|
| Role-based access | `admin` = full write; `editor` = write lookup data only; `viewer` = read-only |
| CSV import | Bulk upload for prices and translations (validated, transactional) |
| Audit log | Append-only `admin_audit_log` table — who changed what and when |
| Activity indicators | Highlight stale prices (e.g. not updated in >90 days) |
| CI: Vite build check | Add `npm run build` step to `unit-test.yml` to catch asset compilation failures |
| E2E tests | Playwright smoke tests for login, CRUD on one resource, logout |

---

## Suggested build order

```
Phase 1  Backend write endpoints + admin ability gate
Phase 2  Inertia scaffold + web session auth + login screen
Phase 3  Core UI components (DataTable, ResourceForm, layout)
Phase 4  User management
Phase 5a Fertilizer master + prices
Phase 5b Commodity prices
Phase 5c Supporting data (investments, costs, factories, currencies, units)
Phase 5d Translations
Phase 6  Monitoring dashboard + request log
Phase 7  Roles, CSV import, audit log, E2E tests
```

Phases 1–4 unblock the most critical admin operations. Phases 5–6 are parallelisable once the component library from Phase 3 exists.
