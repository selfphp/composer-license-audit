# 🎯 composer-license-audit

A simple and powerful CLI tool to **analyze Composer dependencies** for license compliance.  
Useful for companies, open-source maintainers, and CI/CD pipelines.

---

## 🚀 Features

- 🔍 Parses `composer.lock` and detects licenses
- ❌ Compares against a blacklist (`config/blacklist.json`)
- ✅ Supports per-package exceptions (`config/allowed-packages.json`)
- 📊 CSV and JSON export
- 🧪 CI-friendly: exits with code `1` on violations

---

## 📦 Installation

```bash
composer require --dev selfphp/composer-license-audit
```

Or install globally:

```bash
composer global require selfphp/composer-license-audit
```

---

## 🔧 Configuration

### `config/blacklist.json`

```json
{
  "forbidden": [
    "AGPL-3.0",
    "GPL-3.0-only",
    "GPL-3.0-or-later",
    "CC-BY-SA-4.0"
  ]
}
```

### `config/allowed-packages.json`

```json
{
  "exceptions": [
    "legacy/package",
    "acme/unstable-lib"
  ]
}
```

---

## 🧑‍💻 Usage

### Basic scan:

```bash
vendor/bin/license-audit
```

### With CSV + CI check:

```bash
vendor/bin/license-audit \
  --fail-on-blacklist \
  --csv=report/licenses.csv
```

### With custom paths:

```bash
vendor/bin/license-audit \
  --lockfile=/custom/path/composer.lock \
  --blacklist=config/blacklist.json
```

---

## 📊 Example Output

```
Package                        License                   Status
symfony/console                MIT                       OK
some/forbidden-lib             AGPL-3.0                  VIOLATION
```

---

## ✅ Exit Codes

| Code | Meaning                  |
|------|--------------------------|
| `0`  | No violations            |
| `1`  | At least one violation   |

---

## 🧪 CI/CD Integration

**GitHub Actions:**

```yaml
- name: Check Composer Licenses
  run: vendor/bin/license-audit --fail-on-blacklist
```

---

## 🧪 CI Integration Examples

Example configuration files for popular CI providers are available in [`docs/ci/`](docs/ci):

- [GitHub Actions](docs/ci/github-actions.yml)
- [GitLab CI](docs/ci/gitlab-ci.yml)
- [Bitbucket Pipelines](docs/ci/bitbucket-pipelines.yml)

---

## 👤 Author

**Damir Enseleit**  
GitHub: [@selfphp](https://github.com/selfphp)  
Website: [https://selfphp.de](https://selfphp.de)

## 🤝 Contributing

Found a bug or have a feature request?  
Feel free to open an issue or submit a pull request. Contributions are welcome!

## 📄 License

MIT – use it, fork it, improve it!

Feel free to contribute!