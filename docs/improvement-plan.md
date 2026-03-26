# Improvement Plan

This document tracks planned improvements derived from the [code review](./code-review.md).
Each item links to its corresponding GitHub issue and contains a checklist for tracking progress.

---

## Phase 1 — Critical (fix before high-traffic production use)

### #32 · Cache key excludes fertilizer list and user info
**Risk:** Two users with different fertilizer selections can receive each other's cached recommendation.

- [ ] Decide which fields must be in the cache key (at minimum: `fertilizer_list`)
- [ ] Update `RecommendationService::generateCacheKey()` to include those fields
- [ ] Add a test: two requests differing only in fertilizer selection → different cache keys
- [ ] Add a test: identical requests → cache hit on second call
- [ ] Add a code comment documenting the cache key design decision

---

### #33 · PlumberService timeout hardcoded to 5 s, ignoring the 120 s config value
**Risk:** Legitimate computation requests time out prematurely.

- [ ] Remove `protected int $timeout = 5` class property
- [ ] Read `config('services.plumbr.request_timeout')` in the constructor
- [ ] Add `PLUMBR_REQUEST_TIMEOUT` to `.env.example`
- [ ] Add a test asserting the HTTP client is built with the config value

---

### #34 · No rate limiting on any endpoint
**Risk:** Compute endpoint is unbounded — expensive external call + DB write per request.

- [ ] Apply `throttle:30,1` to `POST /v1/recommendations/compute`
- [ ] Apply `throttle:120,1` to all read-only list endpoints
- [ ] Apply `throttle:30,1` to `POST /v1/user-feedback`
- [ ] Verify `429 Too Many Requests` with `Retry-After` header is returned on breach
- [ ] Add a test asserting 429 after the limit is exceeded

---

### #35 · Unvalidated `per_page`, `order_by`, and `sort` parameters
**Risk:** Memory exhaustion; schema information leak via crafted sort values.

- [ ] Cap `per_page` at 100 (or a config-defined max)
- [ ] Validate `order_by` against a per-controller allowlist
- [ ] Validate `sort` to `asc` / `desc` only
- [ ] Apply to: `RecommendationController`, `FertilizerController`, `UserFeedBackController`, `OperationCostController`, `CurrencyController`
- [ ] Return `422` with a clear message on invalid values

---

## Phase 2 — High (fix within the next sprint)

### #36 · Generic `catch (Exception $ex)` in RecommendationService hides real bugs
- [ ] Remove or narrow the catch-all block
- [ ] If retained for graceful degradation, add `Log::error()` with full stack trace before wrapping
- [ ] Ensure `TypeError`, `ValueError`, and programming errors propagate to Laravel's exception handler
- [ ] Add a test verifying unexpected exceptions are logged and produce a 500

---

### #37 · No request latency tracking in `api_requests`
- [ ] Add `request_started_at` (timestamp) and `request_duration_ms` (integer) columns via migration
- [ ] Record start time before `PlumberService` call
- [ ] Record duration after the call resolves (success or failure)
- [ ] Expose `request_duration_ms` in `ApiRequestResource`

---

### #38 · `device_token` used as request correlation ID
- [ ] Generate `Str::uuid()` per request in `RecommendationService::compute()`
- [ ] Store UUID as `request_id`; keep `device_token` as a separate indexed column
- [ ] Return the UUID in the compute response
- [ ] Add a migration if schema changes are needed

---

### #39 · 60 hardcoded fertilizer properties in `PlumberComputeData`
- [ ] Replace hardcoded properties with a dynamic mapping (keyed array / collection)
- [ ] Verify Plumbr API payload format is preserved exactly
- [ ] Update `FertilizerData` and mapping logic
- [ ] Update fertilizer validation rules to validate dynamically
- [ ] Snapshot-test that all existing fertilizer types still serialize correctly
- [ ] Delete `app/Data/ComputeRequestDataOld.php`

---

### #40 · Near-zero test coverage on core business logic
- [ ] `RecommendationService`: happy path, Plumbr failure (connection), Plumbr failure (non-2xx), cache hit, cache miss, request logging
- [ ] `PlumberService`: timeout config, success deserialization, non-2xx → exception, timeout → exception, malformed JSON
- [ ] `ComputeRequest` validation: missing fields, invalid fertilizer, default email/phone fallback
- [ ] `UserFeedBackController`: valid store, missing required fields

---

## Phase 3 — Medium (within current quarter)

### #41 · Default cache store is the database, not Redis
- [ ] Change `CACHE_STORE` default to `redis` in `config/cache.php`
- [ ] Add `CACHE_STORE=redis` to `.env.example`
- [ ] Confirm `phpunit.xml` overrides to `array`

---

### #42 · No max-length validation on `FeedBackRequest` string fields
- [ ] Add `max:` rules to all string fields in `FeedBackRequest`
- [ ] Audit `UserInfoRules` and `ComputeFieldRules` for the same gap
- [ ] Add a test asserting oversized values return 422

---

### #43 · Cache TTL parse failures are silent
- [ ] Add `Log::warning()` before the 1-hour fallback
- [ ] Document accepted `CACHE_TTL` formats in `.env.example`
- [ ] Add a unit test verifying the warning is triggered for an invalid value

---

### #44 · Dead code: disabled commands and old DTO
- [ ] Delete `app/Console/Commands/Disabled/DisableDbCommand.php`
- [ ] Delete `app/Console/Commands/Disabled/DisableWipeCommand.php`
- [ ] Delete `app/Data/ComputeRequestDataOld.php`
- [ ] Confirm no references to these classes remain

---

### #45 · Missing env vars in `.env.example`
- [ ] Add `PLUMBR_BASE_URL`, `PLUMBR_REC_ENDPOINT`, `PLUMBR_REQUEST_TIMEOUT`
- [ ] Add `CACHE_TTL` with format examples
- [ ] Add `CACHE_STORE=redis`

---

### #46 · Inconsistent API response envelope across endpoints
- [ ] Define a standard response envelope
- [ ] Wrap compute response in a resource class
- [ ] Wrap feedback store response
- [ ] Standardise error response format via Laravel's exception handler

---

## Phase 4 — Low / Technical Debt (backlog)

### #47 · Rename `UserFeedBackController` → `UserFeedbackController`
- [ ] Rename file and class
- [ ] Update `routes/api.php`
- [ ] Update any tests or references

---

### #48 · Document or consolidate `BaseRepo::find` vs `BaseRepo::selectOne`
- [ ] Add docblocks explaining when each should be used
- [ ] Migrate any `selectOne`-by-ID calls to `find`
- [ ] Consolidate if possible without breaking call sites

---

## Issue Index

| # | Title | Severity | Status |
|---|-------|----------|--------|
| [#32](https://github.com/IITA-AKILIMO/new-akilimo-api/issues/32) | Cache key excludes fertilizer + user info | Critical | Open |
| [#33](https://github.com/IITA-AKILIMO/new-akilimo-api/issues/33) | PlumberService timeout hardcoded to 5 s | Critical | Open |
| [#34](https://github.com/IITA-AKILIMO/new-akilimo-api/issues/34) | No rate limiting on any endpoint | Critical | Open |
| [#35](https://github.com/IITA-AKILIMO/new-akilimo-api/issues/35) | Unvalidated per_page / order_by / sort | Critical | Open |
| [#36](https://github.com/IITA-AKILIMO/new-akilimo-api/issues/36) | Generic catch hides real bugs | High | Open |
| [#37](https://github.com/IITA-AKILIMO/new-akilimo-api/issues/37) | No request latency tracking | High | Open |
| [#38](https://github.com/IITA-AKILIMO/new-akilimo-api/issues/38) | device_token used as correlation ID | High | Open |
| [#39](https://github.com/IITA-AKILIMO/new-akilimo-api/issues/39) | 60 hardcoded fertilizer properties | High | Open |
| [#40](https://github.com/IITA-AKILIMO/new-akilimo-api/issues/40) | Near-zero test coverage | High | Open |
| [#41](https://github.com/IITA-AKILIMO/new-akilimo-api/issues/41) | Default cache store is database | Medium | Open |
| [#42](https://github.com/IITA-AKILIMO/new-akilimo-api/issues/42) | No max-length on FeedBackRequest fields | Medium | Open |
| [#43](https://github.com/IITA-AKILIMO/new-akilimo-api/issues/43) | Silent cache TTL fallback | Medium | Open |
| [#44](https://github.com/IITA-AKILIMO/new-akilimo-api/issues/44) | Dead code in repository | Medium | Open |
| [#45](https://github.com/IITA-AKILIMO/new-akilimo-api/issues/45) | Missing env vars in .env.example | Medium | Open |
| [#46](https://github.com/IITA-AKILIMO/new-akilimo-api/issues/46) | Inconsistent response envelope | Medium | Open |
| [#47](https://github.com/IITA-AKILIMO/new-akilimo-api/issues/47) | Rename UserFeedBackController | Low | Open |
| [#48](https://github.com/IITA-AKILIMO/new-akilimo-api/issues/48) | Document BaseRepo::find vs selectOne | Low | Open |
