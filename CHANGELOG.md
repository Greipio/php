# Changelog

## 3.0.0 — 2026-03-19

### Breaking

- **Greip API v2** (`https://v2.greipapi.com`) for `GeoIP` and `Fraud` clients.
- Bearer token authorization and v2 `success` envelope checks.
- Updated endpoint URLs; profanity and payment fraud use POST where required by v2.
- Response arrays reflect v2 field naming (`is_valid`, nested geo/IP structures, etc.).

### Added

- `scripts/live_integration.php` for live smoke tests (`GREIP_TOKEN` + `Config::setToken`).
