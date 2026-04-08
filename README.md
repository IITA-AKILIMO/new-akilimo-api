# AKILIMO API

Laravel 12 RESTful API that computes and delivers agricultural recommendations (fertilizer application, intercropping, scheduled planting) based on detailed farm data. Acts as an orchestration layer between clients and an external **Plumbr** computation service.

[![Tests](https://github.com/IITA-AKILIMO/new-akilimo-api/actions/workflows/unit-test.yml/badge.svg)](https://github.com/IITA-AKILIMO/new-akilimo-api/actions/workflows/unit-test.yml)
[![Build](https://github.com/IITA-AKILIMO/new-akilimo-api/actions/workflows/docker-build.yml/badge.svg)](https://github.com/IITA-AKILIMO/new-akilimo-api/actions/workflows/docker-build.yml)
[![codebeat badge](https://codebeat.co/badges/ba1363f9-5713-458c-bb1d-05fd1bb8b0fa)](https://codebeat.co/projects/github-com-iita-akilimo-new-akilimo-api-develop)

---

## Requirements

| Dependency | Version |
|---|---|
| PHP | 8.3+ |
| Laravel | 12.x |
| MariaDB | 11.2+ |
| Redis | 7.4+ |

---

## Local setup

```bash
git clone https://github.com/IITA-AKILIMO/new-akilimo-api.git
cd new-akilimo-api
composer install

cp .env.example .env
php artisan key:generate

php artisan migrate
php artisan db:seed          # seeds reference data + admin user
```

### Environment variables

```env
# External computation service
PLUMBR_BASE_URL=
PLUMBR_REC_ENDPOINT=/compute
PLUMBR_REQUEST_TIMEOUT=120

# Auth
AUTH_TOKEN_TTL_DAYS=30       # bearer token lifetime in days
DEFAULT_ADMIN_PASSWORD=      # used by AdminUserSeeder

# Cache
CACHE_STORE=redis
CACHE_TTL=1h                 # recommendation cache TTL (e.g. 30m, 1h, 24h)

APP_TIMEZONE=UTC             # affects ISO 8601 offsets in API responses
```

---

## Common commands

```bash
# Development
composer dev           # start dev server on port 8600
composer dev:all       # server + queue worker + Vite concurrently

# Testing
composer test          # run tests via artisan
composer tests         # run tests via Pest
composer pest:coverage # Pest with coverage report

# Code quality
composer lint:fix-all  # fix all code style issues (Pint / PSR-12)
composer lint:check    # check without fixing

# Models & IDE helpers
composer model:gen     # regenerate models from DB schema (after migrations)
```

---

## Docker

```bash
docker compose up        # API + MariaDB + Redis + Dozzle
```

The API is exposed on port **8600**. The multi-stage `Dockerfile` builds frontend assets with Node 20 Alpine then runs PHP 8.3-FPM Alpine. Supervisor manages PHP-FPM and the Laravel queue worker.

---

## Authentication

All write and domain endpoints require authentication. Two methods are supported and can be used interchangeably:

### 1 · Bearer token (dynamic)

```
POST /api/v1/auth/login
Content-Type: application/json

{ "username": "akilimo", "password": "..." }
```

Returns a token valid for `AUTH_TOKEN_TTL_DAYS` days. Use it in subsequent requests:

```
Authorization: Bearer <token>
```

Revoke with `POST /api/v1/auth/logout`.

### 2 · API key (long-lived)

Generate a key via `POST /api/v1/auth/api-keys` (requires an existing valid token). The full key is shown once and never stored.

```
X-Api-Key: ak_a1b2c3d4e5f6...
```

Keys support scoped abilities: `read`, `write`, `api-keys:manage`. A `null` abilities list grants everything (`*`).

### Admin setup

The `AdminUserSeeder` creates user `akilimo` / `akilimo@cgiar.org` and prints an initial wildcard API key to the console. To regenerate the key at any time:

```bash
php artisan admin:regenerate-api-key
php artisan admin:regenerate-api-key --force   # skip confirmation
```

---

## API routes

### Public (no auth)

| Method | Path | Description |
|---|---|---|
| `GET` | `/health` | Service health check |
| `POST` | `/api/v1/auth/login` | Obtain bearer token |
| `GET` | `/api/v1/currencies` | Currency reference data |
| `GET` | `/api/v1/fertilizers` | Fertilizer catalogue |
| `GET` | `/api/v1/fertilizer-prices` | Fertilizer prices |
| `GET` | `/api/v1/investment-amounts` | Investment amount options |
| `GET` | `/api/v1/operation-costs` | Operation cost options |
| `GET` | `/api/v1/starch-factories` | Starch factory list |
| `GET` | `/api/v1/starch-prices` | Starch prices |
| `GET` | `/api/v1/default-prices` | Default produce prices |
| `GET` | `/api/v1/cassava-units` | Cassava unit options |
| `GET` | `/api/v1/cassava-prices` | Cassava prices |
| `GET` | `/api/v1/potato-prices` | Potato prices |
| `GET` | `/api/v1/maize-prices` | Maize prices |

### Protected (requires `Authorization: Bearer` or `X-Api-Key`)

| Method | Path | Description |
|---|---|---|
| `POST` | `/api/v1/auth/logout` | Revoke current token |
| `GET` | `/api/v1/auth/api-keys` | List own API keys |
| `POST` | `/api/v1/auth/api-keys` | Generate API key |
| `PATCH` | `/api/v1/auth/api-keys/{id}/revoke` | Revoke API key |
| `DELETE` | `/api/v1/auth/api-keys/{id}` | Delete API key |
| `POST` | `/api/v1/recommendations/compute` | Compute recommendation |
| `GET` | `/api/v1/recommendations` | Request history |
| `GET` | `/api/v1/user-feedback` | List feedback |
| `POST` | `/api/v1/user-feedback` | Submit feedback |
| `GET` | `/api/v1/translations` | Translation strings |

---

## Scaffold command

Generate a full API slice (controller + repository + resource + collection + route) in one command:

```bash
php artisan make:api-scaffold Translation
php artisan make:api-scaffold StarchPrices --model=StarchPrice --prefix=starch-prices
php artisan make:api-scaffold Currency --force    # overwrite existing files
php artisan make:api-scaffold Currency --no-route # skip route registration
```

---

## Code quality & CI

- **CI:** GitHub Actions runs tests on every push (`unit-test.yml`) and builds the Docker image on `main`/`develop` (`docker-build.yml`).
- **Coverage + SonarQube:** runs on `develop` via `quality-checks.yml`.
- **Linting:** Laravel Pint (PSR-12). Run `composer lint:fix-all` before opening a PR.
