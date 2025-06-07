# 📦 Changelog

All notable changes to `composer-license-audit` will be documented in this file.

## [1.0.0] – 2025-06-07

### 🎉 Initial Release

- Scans `composer.lock` for all dependencies
- Extracts and lists license types (MIT, GPL, AGPL, etc.)
- Supports license blacklisting via `config/blacklist.json`
- Allows package-level exceptions via `config/allowed-packages.json`
- Human-readable CLI output (`OK` / `VIOLATION`)
- CSV and JSON export of license data
- Exit with code `1` if any violation is found (for CI/CD)
- Built-in Symfony Console CLI interface
- Full PHPUnit test coverage (Blacklist + Scanner logic)
- GitHub Actions workflow included
