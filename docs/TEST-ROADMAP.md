# Test Roadmap

Current baseline: **98 tests, 204 assertions** (all passing).

---

## Priority 0 — Authentication (new — must pass before merging auth work)

### T0 · Login / Logout
**File:** `tests/Feature/Auth/LoginTest.php`

| # | Test case |
|---|-----------|
| 1 | `POST /v1/auth/login` with valid username returns 200, `token_type`, `token`, `expires_at`, `user` |
| 2 | `POST /v1/auth/login` with valid email returns 200 |
| 3 | `POST /v1/auth/login` with wrong password returns 401 |
| 4 | `POST /v1/auth/login` with unknown username returns 401 |
| 5 | `POST /v1/auth/login` missing `username` returns 422 |
| 6 | `POST /v1/auth/login` missing `password` returns 422 |
| 7 | `POST /v1/auth/logout` with valid bearer token returns 200 and deletes the token |
| 8 | `POST /v1/auth/logout` without token returns 401 |
| 9 | Token is not reusable after logout |

---

### T0b · API Key management
**File:** `tests/Feature/Auth/ApiKeyTest.php`

| # | Test case |
|---|-----------|
| 10 | `POST /v1/auth/api-keys` without auth returns 401 |
| 11 | `POST /v1/auth/api-keys` with valid bearer token creates key, returns `key` in response |
| 12 | `POST /v1/auth/api-keys` with `abilities` scoped to `['read']` stores abilities correctly |
| 13 | `POST /v1/auth/api-keys` with invalid ability value returns 422 |
| 14 | `POST /v1/auth/api-keys` with `expires_at` in the past returns 422 |
| 15 | `GET /v1/auth/api-keys` returns only keys belonging to authenticated user |
| 16 | Generated key can authenticate a request via `X-Api-Key` header |
| 17 | Revoked key (`PATCH .../revoke`) returns 401 on subsequent requests |
| 18 | Deleted key (`DELETE`) returns 401 on subsequent requests |
| 19 | Expired key returns 401 |
| 20 | Key with `abilities: ['read']` returns 403 on a route requiring `write` |

---

## Priority 1 — High value (behaviour that can silently break)

### T1 · Health Check
**File:** `tests/Feature/HealthCheckTest.php`
**Route:** `GET /health`

| # | Test case |
|---|-----------|
| 1 | Returns 200 with JSON body |
| 2 | Response contains keys: `status`, `checks` (or equivalent top-level keys) |
| 3 | `status` is `healthy` when all services are reachable |
| 4 | DB check passes (SQLite in-memory is present during tests) |
| 5 | `checks.akilimo-compute.status` is `UP` when the compute service responds 200 (Http::fake) |
| 6 | `checks.akilimo-compute.status` is `DOWN` and overall status is `unhealthy` when compute service is unreachable |
| 7 | `checks.akilimo-compute.status` is `DOWN` when `AKILIMO_COMPUTE_BASE_URL` is empty |
| 8 | `checks.akilimo-compute` includes `url` and `http_status` on a successful probe |

---

### T2 · Compute — untested response paths
**File:** `tests/Feature/ComputeRecommendationTest.php` (extend existing)
**Route:** `POST /api/v1/recommendations/compute`

| # | Test case |
|---|-----------|
| 5 | Cache hit: response body (`data` payload) is identical on second identical request |
| 6 | Cache hit: HTTP call to Plumbr is made exactly once across two identical requests |
| 7 | Response contains all expected top-level keys (`request_id`, and at least one recommendation key) |
| 8 | Different `country_code` produces a different fertilizer mapping sent to Plumbr |
| 9 | Plumbr 500 response maps to a 5xx from our API (not a 200 with error inside) |

---

### T3 · Recommendation history — missing scenarios
**File:** `tests/Feature/RecommendationHistoryTest.php` (extend existing)
**Route:** `GET /api/v1/recommendations`

| # | Test case |
|---|-----------|
| 10 | `?sort=asc` returns records oldest-first |
| 11 | `?per_page=0` or invalid value falls back to a sane default (does not 500) |
| 12 | `meta.last_page` is correct relative to `meta.total` and `meta.per_page` |

---

### T4 · User Feedback — missing scenarios
**File:** `tests/Feature/UserFeedbackTest.php` (extend existing)

| # | Test case |
|---|-----------|
| 13 | `GET /api/v1/user-feedback` `?per_page=1` returns exactly one record |
| 14 | `GET /api/v1/user-feedback` second page returns correct slice |
| 15 | `GET /api/v1/user-feedback` `?sort=asc` returns oldest record first |
| 16 | `POST /api/v1/user-feedback` rejects missing `use_case` field |
| 17 | `POST /api/v1/user-feedback` rejects missing `user_type` field |
| 18 | `POST /api/v1/user-feedback` accepts all valid `device_language` values: `en`, `fr`, `sw` (dataset) |

---

## Priority 2 — Reference data endpoints

All reference endpoints share the same `BaseRepo` pagination/sorting contract. One test per
*resource family* is sufficient — testing the same contract across every endpoint adds noise
without finding new bugs.

---

### T5 · Fertilizers
**File:** `tests/Feature/FertilizerTest.php`

| # | Test case |
|---|-----------|
| 19 | `GET /api/v1/fertilizers` returns 200 with `data/links/meta` structure |
| 20 | `GET /api/v1/fertilizers` each item contains `id`, `name`, `fertilizer_key`, `country_code` |
| 21 | `GET /api/v1/fertilizers?per_page=1` returns exactly 1 item |
| 22 | `GET /api/v1/fertilizers/country/NG` returns only Nigerian fertilizers |
| 23 | `GET /api/v1/fertilizers/country/ng` (lowercase) is accepted and returns same results as `NG` |
| 24 | `GET /api/v1/fertilizers/country/ZZ` (unknown country) returns 200 with empty `data` |

---

### T6 · Fertilizer Prices
**File:** `tests/Feature/FertilizerPriceTest.php`

| # | Test case |
|---|-----------|
| 25 | `GET /api/v1/fertilizer-prices` returns 200 with `data/links/meta` structure |
| 26 | `GET /api/v1/fertilizer-prices/urea` filters by fertilizer key |
| 27 | `GET /api/v1/fertilizer-prices/UREA` (uppercase key) returns same results as lowercase |
| 28 | `GET /api/v1/fertilizer-prices/country/NG` filters by country |
| 29 | `GET /api/v1/fertilizer-prices/country/ZZ` returns 200 with empty `data` |

---

### T7 · Produce Prices (Maize, Cassava, Potato)
**File:** `tests/Feature/ProducePricesTest.php`

| # | Test case |
|---|-----------|
| 30 | `GET /api/v1/maize-prices` returns 200 with `data/links/meta` |
| 31 | `GET /api/v1/maize-prices/country/NG` filters results to `NG` only |
| 32 | `GET /api/v1/maize-prices/country/ZZ` returns 200 with empty `data` |
| 33 | `GET /api/v1/cassava-prices` returns 200 with `data/links/meta` |
| 34 | `GET /api/v1/cassava-prices/country/NG` filters correctly |
| 35 | `GET /api/v1/potato-prices` returns 200 with `data/links/meta` |
| 36 | `GET /api/v1/potato-prices/country/NG` filters correctly |

---

### T8 · Other Reference Data
**File:** `tests/Feature/ReferenceDataTest.php`

| # | Test case |
|---|-----------|
| 37 | `GET /api/v1/currencies` returns 200 with `data/links/meta`; each item has `currency_code` |
| 38 | `GET /api/v1/investment-amounts` returns 200 with `data/links/meta` |
| 39 | `GET /api/v1/investment-amounts/country/NG` returns only active investment amounts for `NG` |
| 40 | `GET /api/v1/operation-costs` returns 200 with `data/links/meta` |
| 41 | `GET /api/v1/operation-costs/country/NG` filters by country |
| 42 | `GET /api/v1/starch-factories` returns 200 with `data/links/meta` |
| 43 | `GET /api/v1/starch-factories/country/NG` filters by country |
| 44 | `GET /api/v1/cassava-units` returns 200 with `data/links/meta` |

---

## Priority 3 — Cross-cutting / regression guards

### T9 · Pagination contract (BaseRepo)
**File:** `tests/Feature/PaginationContractTest.php`

These tests verify the shared pagination behaviour once, so we don't repeat it per resource.

| # | Test case |
|---|-----------|
| 45 | `?per_page=2` returns exactly 2 items and `meta.per_page` = 2 (use any seeded endpoint) |
| 46 | `?page=2&per_page=1` returns the second record, not the first |
| 47 | `meta` contains `current_page`, `last_page`, `per_page`, `total` |
| 48 | `links` contains `first`, `last`, `prev`, `next` |
| 49 | `?sort=asc` and `?sort=desc` return records in opposite order |

---

### T10 · Response format contract
**File:** `tests/Feature/ResponseFormatTest.php`

| # | Test case |
|---|-----------|
| 50 | Every endpoint returns `Content-Type: application/json` (sample 3–4 routes via dataset) |
| 51 | A `POST` to a GET-only endpoint returns 405, not 500 |
| 52 | A completely unknown route returns 404 in JSON (not HTML) |

---

## Summary

| Priority | File | New tests |
|----------|------|-----------|
| P0 | `Auth/LoginTest.php` (new) | 9 |
| P0 | `Auth/ApiKeyTest.php` (new) | 11 |
| P1 | `HealthCheckTest.php` (new) | 4 |
| P1 | `ComputeRecommendationTest.php` (extend) | 5 |
| P1 | `RecommendationHistoryTest.php` (extend) | 3 |
| P1 | `UserFeedbackTest.php` (extend) | 6 |
| P2 | `FertilizerTest.php` (new) | 6 |
| P2 | `FertilizerPriceTest.php` (new) | 5 |
| P2 | `ProducePricesTest.php` (new) | 7 |
| P2 | `ReferenceDataTest.php` (new) | 8 |
| P3 | `PaginationContractTest.php` (new) | 5 |
| P3 | `ResponseFormatTest.php` (new) | 3 |
| **Total** | | **~72 new tests** |

Implement in priority order. P1 items should land before any further feature work;
P2 and P3 can be batched into a single PR once P1 is done.
