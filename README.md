# PetCertify — ESA Suite

A full-stack monorepo demonstration project simulating an **Emotional Support Animal (ESA) certificate** platform. Users submit a multi-step application, a licensed therapist reviews and approves it, and an approved user can download a generated PDF certificate.

> **Disclaimer:** This is a demonstration project. All data is fictitious and has no legal value.

---

## Architecture Overview

The project follows a **decoupled monorepo** pattern: a dedicated REST API and a separate web front-end, each as an independent Laravel application, sharing the same infrastructure.

```
esa-suite/
├── api/          # Laravel 13 REST API (Sanctum auth, business logic, PDF generation)
├── web/          # Laravel 13 + Livewire front-end (server-rendered SPA-like UI)
├── docker/       # Dockerfiles and Nginx configs for both apps
└── .github/      # CI/CD pipeline (GitHub Actions → SSH deploy)
```

```
Browser → Nginx (web-nginx :8080) → PHP-FPM (web-app)
                                         ↓ HTTP (Guzzle)
                              Nginx (api-nginx :8081) → PHP-FPM (api-app)
                                         ↓
                                      MySQL + Redis
```

---

## Tech Stack

### API (`api/`)

| Layer | Technology |
|---|---|
| Runtime | PHP 8.4, Laravel 13 |
| Authentication | Laravel Sanctum 4.x (token-based) |
| Authorization | Spatie Laravel Permission 7.x (roles & permissions) |
| PDF Generation | barryvdh/laravel-dompdf 3.x |
| Database | MySQL 8.0 (Eloquent ORM) |
| Cache / Session | Redis |
| HTTP Server | Nginx + PHP-FPM |

### Web (`web/`)

| Layer | Technology |
|---|---|
| Runtime | PHP 8.4, Laravel 13 |
| UI Framework | Livewire 4.x (reactive components, no custom JS) |
| Styling | Tailwind CSS 4.x (via browser CDN build) |
| Interactivity | Alpine.js (bundled with Livewire 4 — no separate import) |
| API Client | Guzzle 7.x (server-side HTTP calls to the API) |
| i18n | Laravel JSON translations (English, Portuguese, Spanish) |
| HTTP Server | Nginx + PHP-FPM |

### Infrastructure

| Component | Technology |
|---|---|
| Containerisation | Docker + Docker Compose |
| Database | MySQL 8.0 (persistent named volume) |
| Cache | Redis Alpine |
| DB Admin | Adminer |
| CI/CD | GitHub Actions → SSH deploy (multi-environment: dev, stage, prod) |

---

## Key Features

- **Multi-step wizard** — 6-step guided application flow with per-step validation, auto-save, and resume from any step
- **Pet management** — inline CRUD (add, edit, delete) inside the wizard with live Livewire modals
- **Role-based access control** — `user`, `therapist`, and `admin` roles via Spatie Permission
- **Therapist review queue** — paginated table of pending applications with a detail modal for approve/reject
- **PDF certificate download** — dynamically generated with DomPDF, served directly from the API with Sanctum token auth via query parameter (suitable for browser `window.open`)
- **Admin user management** — ban/unban users, assign roles, wrapped in a database transaction
- **Internationalisation** — all UI strings translated to EN / PT-BR / ES with a runtime language switcher
- **User banning** — `banned_at` timestamp, enforced by a dedicated middleware on every protected route

---

## User Roles

| Role | Access |
|---|---|
| `user` | Submit ESA application, manage own pets, download approved certificate |
| `therapist` | View and approve/reject pending applications |
| `admin` | Manage all users (roles, ban status) |

---

## Project Structure

### API

```
api/
├── app/
│   ├── Http/
│   │   ├── Controllers/Api/V1/
│   │   │   ├── EsaRequestController.php   # wizard save, pet sync
│   │   │   ├── PetController.php
│   │   │   ├── Admin/UserController.php   # user management
│   │   │   └── Therapist/RequestController.php  # queue + PDF download
│   │   └── Middleware/
│   │       ├── Authenticate.php           # overrides redirectTo() → null (pure API)
│   │       ├── ForceTokenFromQuery.php    # injects ?token= into Authorization header
│   │       └── EnsureUserIsNotBanned.php
│   ├── Models/
│   │   ├── User.php, Pet.php, EsaRequest.php
│   └── Providers/
│       └── AppServiceProvider.php        # Sanctum::getAccessTokenFromRequestUsing()
├── database/migrations/
└── routes/api.php
```

### Web

```
web/
├── app/
│   ├── Livewire/
│   │   ├── Request/
│   │   │   ├── Wizard.php      # 6-step form with pet CRUD
│   │   │   └── Status.php      # approved/pending/rejected status + download
│   │   ├── Therapist/
│   │   │   └── RequestQueue.php
│   │   ├── Admin/Users/
│   │   │   └── Index.php
│   │   └── Pets/
│   ├── Services/ApiClient.php   # Guzzle wrapper (authed + unauthed requests)
│   └── Http/Middleware/WebAuth.php
├── lang/
│   ├── en.json
│   ├── pt_BR.json
│   └── es.json
└── resources/views/
```

---

## Getting Started

### Prerequisites

- Docker & Docker Compose
- Git

### Local Setup

```bash
git clone <repo-url> esa-suite
cd esa-suite

# Copy environment files
cp api/.env.example api/.env
cp web/.env.example web/.env

# Build and start all containers
docker compose -f docker-compose.yml -f docker-compose.override.yml up -d --build

# Install dependencies and run migrations
docker exec <project>_api_app composer install
docker exec <project>_api_app php artisan migrate --seed

docker exec <project>_web_app composer install
docker exec <project>_web_app npm install && npm run build
```

| Service | URL |
|---|---|
| Web app | http://localhost:8080 |
| API | http://localhost:8081 |
| Adminer | http://localhost:8082 |

### Environment Variables

Each app has its own `.env`. Key variables for the web app:

```env
API_URL=http://api-nginx          # internal Docker network URL
API_PUBLIC_URL=http://localhost:8081  # browser-facing URL (used for PDF download links)
```

---

## CI/CD

GitHub Actions workflow (`.github/workflows/ci-cd.yml`) triggers on push to `main` (production) or `dev`/`stage` branches.

**Pipeline steps:**
1. Checkout code
2. Resolve target environment (path, env secrets)
3. SSH into server
4. Pull latest code (`git pull`)
5. Rebuild Docker images (`docker compose build --no-cache`)
6. Set storage permissions (`chown 33:33` for Debian `www-data`)
7. Run `composer install`, `php artisan migrate --force`, `npm run build`
8. Restart containers (`docker compose up -d --force-recreate`)

---

## API Reference (v1)

All protected endpoints require `Authorization: Bearer <token>`.

```
POST   /api/v1/auth/register
POST   /api/v1/auth/login
POST   /api/v1/auth/logout            [auth]

GET    /api/v1/esa-request/active     [auth]
PUT    /api/v1/esa-request/:id        [auth]
POST   /api/v1/esa-request/:id/pets   [auth]
GET    /api/v1/esa-request/:id/download?token=  [auth via query param]

GET    /api/v1/pets                   [auth]
POST   /api/v1/pets                   [auth]
PUT    /api/v1/pets/:id               [auth]
DELETE /api/v1/pets/:id               [auth]

GET    /api/v1/therapist/requests     [auth + permission: requests.view.assigned]
POST   /api/v1/therapist/requests/:id/approve  [auth + permission]

GET    /api/v1/admin/users            [auth + permission: admin.users.manage]
GET    /api/v1/admin/users/:id        [auth + permission]
PUT    /api/v1/admin/users/:id        [auth + permission]
```

---

## License

MIT — demonstration purposes only.
