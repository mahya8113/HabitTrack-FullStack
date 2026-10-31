# HabitTrack — Full-stack PHP

HabitTrack is a dark, responsive habit-tracking starter application built with Laravel, Vue 3, TypeScript, MySQL, Redis queues, Sanctum, Pest/PHPUnit, Docker, and an OpenAPI specification.

## Included
- Laravel REST API with Sanctum token authentication
- Habits: CRUD, frequency settings, daily completion records, streak/progress summary
- Goals and tasks: CRUD foundations and goal progress fields
- Focus sessions
- Dashboard and analytics endpoints
- Vue 3 + TypeScript + Vite frontend with a dark neon dashboard and pages for Dashboard, Habits, Calendar, Goals, Tasks, Focus, Analytics, Rewards, AI Coach, Notifications, Profile, Settings, Backup
- MySQL migrations, factories/seeders, API tests
- Redis queue configuration and Docker Compose
- OpenAPI specification at `docs/openapi.yaml`

## Requirements
PHP 8.4+, Composer, Node.js 20+, npm. For container mode, Docker Desktop.

## Quick start (local)
1. Copy `backend/.env.example` to `backend/.env`.
2. From `backend/`: `composer install`
3. `php artisan key:generate`
4. Configure MySQL credentials in `.env`, then create a database named `habittrack`.
5. `php artisan migrate --seed`
6. `php artisan serve`
7. In another terminal, from `frontend/`: `npm install`, then `npm run dev`.
8. Open the Vite URL shown in the terminal. The API defaults to `http://127.0.0.1:8000/api`.

For Sanctum token authentication, the starter frontend stores the token in sessionStorage. For production, review the deployment/security checklist below and prefer Sanctum's stateful cookie authentication where appropriate.

## Docker
From the project root: `docker compose up --build`. This starts the Laravel app, MySQL, Redis, and Vite frontend. First run `docker compose exec backend php artisan key:generate` and `docker compose exec backend php artisan migrate --seed`.

## Important notes
- This is a runnable starter project, not a fully audited production service.
- Update `APP_KEY`, database passwords, CORS/stateful domains, mail, queue and cache settings for your environment.
- Before production: configure HTTPS, backups, rate limits, log retention, secure cookie/token handling, authorization policies, monitoring, and test the full flows.
- AI Coach currently uses explainable local rules, not an external AI API.


## توسعه نسخه v4
APIهای گزارش‌گیری، یادداشت روزانه، مراحل هدف، پاداش XP، تنظیمات، اعلان‌ها و مربی قاعده‌محور در `backend/docs/IMPLEMENTATION_SCOPE_FA.md` مستند شده‌اند.


## توسعه نسخه v4
APIهای گزارش‌گیری، یادداشت روزانه، مراحل هدف، پاداش XP، تنظیمات، اعلان‌ها و مربی قاعده‌محور در `backend/docs/IMPLEMENTATION_SCOPE_FA.md` مستند شده‌اند.

### UI/API verification status
The latest frontend wiring uses authenticated API data for analytics, coach recommendations, notifications, settings, rewards, CSV reports and server-side backup export. PHP syntax lint has been run. Frontend dependency installation timed out in the build environment, so run `npm install` and `npm run build` in `frontend/`, and run `composer install` plus `php artisan test` in `backend/` before presenting this as fully verified.
