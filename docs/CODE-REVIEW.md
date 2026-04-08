# Code Review: Improvements, Gaps & Pitfalls

Generated: 2026-03-26

---

## Critical

### 1. Cache key excludes user and fertilizer data
**File:** `app/Service/RecommendationService.php`

`user_info` and `fertilizer_list` are commented out of the cache key generation:

```php
$relevantData = [
    // 'user_info' => Arr::get($droidRequest, 'user_info', []),       // COMMENTED OUT
    'compute_request' => Arr::get($droidRequest, 'compute_request', []),
    // 'fertilizer_list' => Arr::get($droidRequest, 'fertilizer_list', []),  // COMMENTED OUT
];
```

Two users with identical `compute_request` fields but different fertilizer selections or user context will receive the same cached response. Re-evaluate what belongs in the cache key; at minimum, fertilizer selection should be included.

---

### 2. PlumberService timeout is hardcoded, ignoring config
**File:** `app/Service/PlumberService.php`

The class property `protected int $timeout = 5` sets a 5-second timeout. `config/services.php` defines `plumbr.request_timeout` defaulting to 120 seconds, but if the constructor doesn't read this config value, the hardcoded 5 seconds wins. Agricultural computations may genuinely take longer. Initialise the timeout from config in the constructor.

---

### ~~3. No rate limiting on any endpoint~~ ✅ Fixed
**File:** `routes/api.php`

Throttle middleware applied: `throttle:120,1` on all read-only reference data, `throttle:30,1` on compute and mutation endpoints, `throttle:10,1` on auth endpoints.

---

### ~~4. `per_page` and sort parameters are unvalidated~~ ✅ Fixed
**File:** `app/Http/Concerns/HasPaginationParams.php`

`HasPaginationParams` trait caps `per_page` at 100 via `getPerPage()`, validates `order_by` against a per-controller allowlist via `getOrderBy()`, and restricts `sort` to `asc`/`desc` via `getSortDirection()`. Applied to all list controllers.

---

## High

### 5. Hardcoded fallback email and phone in request preparation
**File:** `app/Http/Requests/ComputeRequest.php`

```php
'email_address' => filled(...) ? $userInfo['email_address'] : 'akilimo@cgiar.org',
'phone_number'  => filled(...) ? $userInfo['phone_number']  : '0000000000',
```

This makes it impossible to distinguish organic missing data from system defaults in `api_requests` logs, which breaks analytics and support queries. Consider keeping the original value (or `null`) and handling the default downstream only where it is actually needed.

---

### 6. Overly broad exception catch in RecommendationService
**File:** `app/Service/RecommendationService.php`

The final `catch (Exception $ex)` block converts every unhandled exception — including database errors, serialization failures, and programming mistakes — into a `RecommendationException`. This silences real bugs in production. Let unexpected exceptions propagate (or at least re-log them at `error` level with full stack trace) before wrapping them.

---

### 7. No request/response timing in the API request log
**File:** `app/Service/RecommendationService.php` → `logRequest` / `logResponse`

The log records the payloads but not the start time, end time, or duration of the Plumbr call. Without latency data, it is impossible to detect Plumbr degradation or set meaningful SLO thresholds.

---

### 8. PlumberComputeData has ~60 hardcoded fertilizer properties
**File:** `app/Data/PlumberComputeData.php` (423 lines)

Every fertilizer type is represented as three individual boolean/numeric properties (`ureaAvailable`, `ureaWeight`, `ureaPrice`, etc.) with `#[MapInputName]` attributes. Adding or removing a fertilizer type requires changes in at least four places (this DTO, `FertilizerData`, validation rules, mapping logic). A dynamic approach using a keyed array or collection would be more maintainable.

---

### 9. Near-zero test coverage on business logic
**Files:** `tests/Feature/ExampleTest.php`, `tests/Unit/ExampleTest.php`

Only the Laravel scaffolding example tests exist. There are no tests for:
- `RecommendationService::compute()` (happy path, Plumbr failure, cache hit, cache miss)
- `PlumberService` HTTP interactions
- `ComputeRequest` validation rules
- Cache key generation edge cases

At minimum, cover the compute endpoint with a mocked Plumbr client.

---

### 10. `ComputeRequestDataOld.php` left in production code
**File:** `app/Data/ComputeRequestDataOld.php`

A file named `*Old` creates ambiguity about which class is canonical. Delete it or archive it if it is needed for reference.

---

## Medium

### 11. Default cache store is the database
**File:** `config/cache.php`

```php
'default' => env('CACHE_STORE', 'database'),
```

Database-backed cache negates most caching benefit for a compute-heavy endpoint. The `docker-compose.yml` already runs Redis — the default should be `redis`. Keep `database` only as a documented fallback.

---

### 12. No string length limits in FeedBackRequest
**File:** `app/Http/Requests/FeedBackRequest.php`

Fields like `akilimo_usage`, `use_case`, and `additional_feedback` have no `max:` rule. An oversized payload can slow validation, inflate the database row, or exceed column limits silently (MySQL/MariaDB truncates on `TEXT` columns if strict mode is off).

---

### 13. `device_token` used as request correlation ID
**File:** `app/Service/RecommendationService.php`

`device_token` is client-supplied and non-unique per request, so multiple requests from the same device share the same identifier in `api_requests`. This makes log correlation ambiguous. Store a server-generated UUID per request alongside `device_token`.

---

### 14. Disabled commands committed to the repository
**Files:** `app/Console/Commands/Disabled/DisableDbCommand.php`, `DisableWipeCommand.php`

These are not registered but live in the codebase. They introduce confusion about what commands exist and carry risk if accidentally re-enabled. Remove them or move them to a documentation-only location outside `app/`.

---

### 15. No eager loading specified on list endpoints
**File:** `app/Http/Controllers/Api/RecommendationController.php` and related

`BaseRepo::paginateWithSort` accepts a `$with` parameter for eager loading, but controllers pass an empty array. If any resource class or policy accesses a relationship, this silently triggers N+1 queries. Audit each resource class and pass the needed relations explicitly.

---

### 16. `authorize()` always returns `true`
**Files:** `app/Http/Requests/ComputeRequest.php`, `app/Http/Requests/FeedBackRequest.php`

Currently this is intentional for a public API, but it is worth documenting explicitly as a conscious decision rather than leaving it as a security-review flag. Add an inline comment explaining the API is unauthenticated by design, or create a tracking issue for future auth work.

---

### 17. Cache TTL parse failures are silent
**File:** `app/Service/RecommendationService.php`

If the `CACHE_TTL` env value is unrecognised, the code silently falls back to 1 hour with no log entry. Add a `Log::warning` so configuration mistakes are visible in production logs.

---

## Low / Technical Debt

### 18. Missing Plumbr env vars in `.env.example`
**File:** `.env.example`

`PLUMBR_BASE_URL`, `PLUMBR_REC_ENDPOINT`, `PLUMBR_REQUEST_TIMEOUT`, and `CACHE_TTL` are all used in config or services but not listed in `.env.example`. New developers or CI environments will have no hint these exist.

---

### 19. Inconsistent API response shapes
Fertilizer and price endpoints return `JsonResource` / `ResourceCollection` objects; the compute endpoint returns a raw array from the service. Standardise on resource classes or at least ensure every endpoint returns a predictable envelope so clients don't need conditional parsing.

---

### 20. `BaseRepo` has two nearly identical single-record methods
**File:** `app/Repositories/BaseRepo.php`

`find(int|string $id)` and `selectOne(array $conditions)` overlap significantly. There is no documented guidance on which to prefer. Consolidate or add docblocks explaining when each should be used.

---

### 21. Controller naming is inconsistent
`RecommendationController`, `FertilizerController`, `CurrencyController` follow one pattern; `UserFeedBackController` uses camel-case mid-word. Standardise to `UserFeedbackController` (lowercase `b`).

---

## Summary

| Severity | Count | Resolved |
|----------|-------|---------|
| Critical | 4 | 2 ✅ |
| High     | 6 | 0 |
| Medium   | 7 | 0 |
| Low      | 4 | 0 |

The remaining critical risks are the **cache key bug** (can serve wrong recommendations) and the **PlumberService timeout misconfiguration** (will time out real requests). These should be addressed before high-traffic production use.
