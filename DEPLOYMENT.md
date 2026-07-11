# Deployment Guide — Prime Byte Accounting → samiulhimel.com (Hostinger)

Push to GitHub `main` → the live site updates automatically.

There are two parts:
- **A. One-time server setup** (done once, by hand).
- **B. Auto-deploy** (GitHub Action does this on every push, forever).

---

## Requirements checklist (what you provide)

Your Hostinger plan must have **SSH access** (Premium / Business / Cloud plans do; the
cheapest "Single" plan does **not** — if you're on Single, use the *hPanel Git webhook*
fallback at the bottom instead).

You will need, from **hPanel → Advanced → SSH Access**:

| Item              | Where to find it                                  | Example                     |
|-------------------|---------------------------------------------------|-----------------------------|
| SSH host          | SSH Access page ("IP address" / hostname)         | `82.180.xxx.xxx`            |
| SSH port          | SSH Access page (Hostinger uses a custom port)    | `65002`                     |
| SSH username      | SSH Access page                                   | `u123456789`                |
| Deploy path       | folder where the app lives (see step A2)          | `domains/samiulhimel.com/app` |
| A MySQL database  | hPanel → Databases → MySQL Databases              | name, user, password        |

---

## A. One-time server setup

### A1. Create the database
hPanel → **Databases → MySQL Databases** → create a database + user, note the name,
user and password.

### A2. SSH in and clone the repo
```bash
# connect (use YOUR port/user/host)
ssh -p 65002 u123456789@82.180.xxx.xxx

cd ~/domains/samiulhimel.com
git clone https://github.com/Himelsamiul/accounting-dashboard.git app
cd app
```

### A3. Install dependencies + create .env
```bash
composer install --no-dev --optimize-autoloader
cp .env.production.example .env
nano .env            # fill DB_*, MAIL_PASSWORD, APP_URL   (Ctrl+O, Enter, Ctrl+X to save)
php artisan key:generate
php artisan migrate --force
php artisan db:seed --force      # creates the super-admin + roles (run ONCE)
php artisan storage:link
php artisan config:cache && php artisan route:cache && php artisan view:cache
chmod -R 775 storage bootstrap/cache
```

### A4. Point the domain at Laravel's `public` folder
Laravel must serve from `app/public`, not the repo root. Easiest reliable way on
Hostinger — replace `public_html` with a symlink to the app's public folder:
```bash
cd ~/domains/samiulhimel.com
rm -rf public_html
ln -s app/public public_html
```
(If your plan lets you set the document root in hPanel instead, point it to
`domains/samiulhimel.com/app/public` — either approach works.)

Visit **https://www.samiulhimel.com** — the login page should load.
Enable **SSL** in hPanel → Security → SSL if it isn't already (needed for `https`).

---

## B. Turn on auto-deploy (GitHub Actions over SSH)

### B1. Make an SSH key for GitHub to use
On the server:
```bash
ssh-keygen -t ed25519 -C "github-deploy" -f ~/.ssh/github_deploy -N ""
cat ~/.ssh/github_deploy.pub >> ~/.ssh/authorized_keys
chmod 600 ~/.ssh/authorized_keys
cat ~/.ssh/github_deploy          # <-- copy this PRIVATE key (whole block)
```

### B2. Add GitHub repository secrets
GitHub repo → **Settings → Secrets and variables → Actions → New repository secret**.
Add these five:

| Secret name       | Value                                                      |
|-------------------|------------------------------------------------------------|
| `SSH_HOST`        | your SSH IP/host                                            |
| `SSH_PORT`        | `65002` (your port)                                        |
| `SSH_USERNAME`    | `u123456789` (your user)                                   |
| `SSH_PRIVATE_KEY` | the whole private key from B1 (`-----BEGIN ... END-----`)  |
| `DEPLOY_PATH`     | `domains/samiulhimel.com/app`                             |

### B3. Done
Every `git push` to `main` now runs `.github/workflows/deploy.yml`, which SSHes in and
runs `deploy.sh` (pull → composer → migrate → cache rebuild). Watch it under the repo's
**Actions** tab.

---

## Fallback: no SSH (Hostinger "Single" plan) — hPanel Git webhook

1. hPanel → **Advanced → GIT** → *Create a new repository*:
   - Repository: `https://github.com/Himelsamiul/accounting-dashboard.git`
   - Branch: `main`, Directory: `domains/samiulhimel.com/app`
2. Copy the **auto-deployment webhook URL** it shows.
3. GitHub repo → Settings → Webhooks → Add webhook → paste URL, content-type
   `application/json`, event = *push*.
Now a push triggers `git pull` on the server. Limitation: it only pulls files — after a
push that changes `composer.json` or adds a migration, you must still SSH/terminal in and
run `composer install` + `php artisan migrate --force`. (This is why SSH auto-deploy above
is preferred.)

---

## Security
`.env` is git-ignored and lives **only** on the server — it holds the DB password and the
Gmail app password. It is never committed or pushed. Keep it that way.
