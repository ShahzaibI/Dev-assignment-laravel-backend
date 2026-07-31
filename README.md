# Nexus CMS — Laravel API

A headless CMS REST API built with Laravel 12, Sanctum authentication, Spatie role/permission management, and Swagger documentation.

---

## Requirements

- PHP 8.2+
- Composer
- MySQL 8.0+
- Node.js 18+ (for asset compilation)

---

## Stack

| Layer | Technology |
|---|---|
| Framework | Laravel 12 |
| Authentication | Laravel Sanctum |
| Roles & Permissions | Spatie Laravel Permission |
| API Documentation | L5-Swagger (swagger-php v6) |
| Testing | Pest PHP |
| Database | MySQL (production / tests) |

---

## Getting Started

### 1. Clone and install dependencies

```bash
git clone <repository-url>
cd <project-name>
composer install
```

### 2. Configure environment

```bash
cp .env.example .env
php artisan key:generate
```

Open `.env` and set your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cms_assignment
DB_USERNAME=root
DB_PASSWORD=your_password
```

Also set the Swagger host to match your local server URL:

```env
L5_SWAGGER_CONST_HOST=http://localhost:8000
L5_SWAGGER_GENERATE_ALWAYS=true
```

### 3. Create the database

```bash
mysql -u root -p -e "CREATE DATABASE cms_assignment CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

### 4. Run migrations and seed

```bash
php artisan migrate
php artisan db:seed
```

### 5. Link storage

```bash
php artisan storage:link
```

### 6. Start the server

```bash
php artisan serve
```

The API will be available at `http://localhost:8000`.

---

## Seeded Login Credentials

| Role | Email | Password |
|---|---|---|
| Admin | `admin@cms.test` | `Option101#` |
| Admin | `marcus.webb@cms.test` | `Option101#` |
| Admin | `priya.nair@cms.test` | `Option101#` |
| Moderator | `moderator@cms.test` | `Option101#` |
| Moderator | `sofia.reyes@cms.test` | `Option101#` |
| Moderator | `daniel.okafor@cms.test` | `Option101#` |
| Moderator | `emma.hartmann@cms.test` | `Option101#` |
| Moderator | `liam.fitzgerald@cms.test` | `Option101#` |

> The seeder also downloads cover images from Unsplash for each page and stores them in `storage/app/public/covers/`. Ensure the server has outbound internet access when running `db:seed`, or the pages will be created without cover images (gracefully handled).

---

## Swagger Documentation

Once the server is running, open:

```
http://localhost:8000/api/documentation
```

Docs are auto-generated on every request (`L5_SWAGGER_GENERATE_ALWAYS=true`).

To authenticate in Swagger UI:

1. Call `POST /api/login` with your credentials
2. Copy the `token` from the response
3. Click the **Authorize** button (top right)
4. Enter `Bearer <your-token>` and click **Authorize**

Tests run against a dedicated MySQL database (`cms_assignment_test`)

```bash
php artisan test
```

Expected output:

```
Tests: 23 passed (33 assertions)
```

---

## API Overview

### Public (no authentication required)

| Method | Endpoint | Description |
|---|---|---|
| POST | `/api/login` | Login and receive a Sanctum token |
| GET | `/api/public/menus` | Get the full menu tree |
| GET | `/api/public/pages/{slug}` | Get a published page by slug |

### Auth

| Method | Endpoint | Permission |
|---|---|---|
| POST | `/api/logout` | Authenticated |
| GET | `/api/me` | Authenticated |

### Pages

| Method | Endpoint | Permission |
|---|---|---|
| GET | `/api/pages` | `pages.list` |
| POST | `/api/pages` | `pages.create` |
| GET | `/api/pages/{id}` | `pages.list` |
| POST | `/api/pages/{id}` | `pages.edit` |
| DELETE | `/api/pages/{id}` | `pages.delete` |
| POST | `/api/pages/{id}/restore` | `pages.restore` |

> Page update uses `POST` (not `PUT`) to support `multipart/form-data` file uploads.

### Menus

| Method | Endpoint | Permission |
|---|---|---|
| GET | `/api/menus` | `menus.list` |
| POST | `/api/menus` | `menus.create` |
| PUT | `/api/menus/reorder` | `menus.edit` |
| PUT | `/api/menus/{id}` | `menus.edit` |
| DELETE | `/api/menus/{id}` | `menus.delete` |

### Users

| Method | Endpoint | Permission |
|---|---|---|
| GET | `/api/users` | `users.list` |
| POST | `/api/users` | `users.create` |
| GET | `/api/users/{id}` | `users.list` |
| PUT | `/api/users/{id}` | `users.edit` |
| DELETE | `/api/users/{id}` | `users.delete` |

### Roles & Permissions

| Method | Endpoint | Permission |
|---|---|---|
| GET | `/api/permissions` | `roles.list` |
| GET | `/api/roles` | `roles.list` |
| POST | `/api/roles` | `roles.create` |
| PUT | `/api/roles/{id}` | `roles.edit` |
| DELETE | `/api/roles/{id}` | `roles.delete` |

---

## Role Permissions

| Permission | Admin | Moderator |
|---|---|---|
| `pages.list` | ✅ | ✅ |
| `pages.create` | ✅ | ✅ |
| `pages.edit` | ✅ | ✅ |
| `pages.delete` | ✅ | ❌ |
| `pages.restore` | ✅ | ❌ |
| `menus.list` | ✅ | ✅ |
| `menus.create` | ✅ | ❌ |
| `menus.edit` | ✅ | ❌ |
| `menus.delete` | ✅ | ❌ |
| `users.*` | ✅ | ❌ |
| `roles.*` | ✅ | ❌ |

---

## Architecture

```
app/
├── Http/
│   ├── Controllers/Api/   # Thin controllers, try/catch + service call only
│   ├── Requests/          # Form request validation with custom messages
│   └── Resources/         # API resource transformers
├── Services/              # Business logic
├── Repositories/          # All Eloquent / DB queries
├── Models/                # Eloquent models
└── Helpers/helpers.php    # api_response(), is_published()
```

All API responses follow a consistent envelope:

```json
{
  "success": true,
  "message": "OK",
  "data": { ... }
}
```

---

## Scheduled Publishing (Artisan Command)

Pages can be set to `scheduled` status with a future `publish_date`. The Artisan command flips them to `published` when their time arrives.

### Run manually

```bash
php artisan pages:publish-scheduled
```

### Run via the Laravel Scheduler (recommended)

The command is registered to run **every minute** in `routes/console.php`.

For local development you can keep the scheduler running in the foreground:

```bash
php artisan schedule:work
```

---

## Arabic / RTL Support

The public site supports bilingual **English / Arabic** content with full right-to-left layout.

### How it works on the public site

- A language toggle button (**EN ↔ العربية**) appears in the public site header
- Switching to Arabic sets `dir="rtl"` and `lang="ar"` on the `<html>` element and loads the **Noto Sans Arabic** font
- Navigation menu names switch to `name_ar` when Arabic is active
- Page titles on cards and detail pages switch to `title_ar`
- Page body switches to `body_ar` and is rendered with `dir="rtl"` so prose flows right-to-left
- The selected language is persisted in `localStorage`

### Adding Arabic content

All Arabic fields are optional. If left empty the public site falls back to the English content silently.

- **Pages** — populate `title_ar` and `body_ar` in the admin panel (side-by-side EN/AR inputs) or via the API
- **Menus** — populate `name_ar` in the admin panel (side-by-side EN/AR inputs) or via the API

---
