# Feature map

## Implemented API foundations
- Sanctum token registration/login/current-user/logout
- User-owned habit CRUD and daily entry upsert
- Goals CRUD
- Tasks CRUD
- Focus session start/list/finish
- Dashboard aggregates and last-seven-days analytics
- MySQL migrations and demo seeder
- Ownership checks on individual resources
- PHPUnit-compatible feature tests
- Redis queue worker configuration
- OpenAPI 3.0 document

## Frontend screens included
Dashboard, Habits, Calendar, Goals, Tasks, Focus Timer, Analytics, Rewards, AI Coach, Notifications, Profile, Settings, Backup, Login and demo preview.

## Frontend limitations to be aware of
The starter UI includes fully styled navigable screen concepts and working local interactions for habit creation/completion in demo mode, JSON export, and login/API-backed dashboard data. Some secondary screens (reminders, timer controls, rewards, coach recommendations, preference persistence, goal/task creation forms) are visual starter surfaces and need their UI/API flows completed before calling the product feature-complete. The API endpoints and schema foundations are included where described above.

## Functional UI flows added in this iteration
- Account registration and login use the Sanctum API.
- Goal creation, progress updates and deletion use API CRUD (or in-memory demo state).
- Task creation, completion toggling and deletion use API CRUD (or in-memory demo state).
- Pomodoro timer supports 25/45/60-minute presets, starts a focus session on the API, and saves actual duration when stopped or completed.
- Demo mode is in-memory only and does not persist across reloads.

## Employer-ready UI/API wiring update (v5)
- Analytics page now requests `/analytics` for the selected date range and renders returned daily/per-habit data instead of hard-coded sample bars.
- Analytics page can download the authenticated CSV report from `/reports/habits.csv`.
- Habit coach page loads stored rule-based recommendations from `/coach/recommendations`, shows the reason and lets the user accept/reject/evaluate each recommendation.
- Notifications page loads the signed-in user's in-app notifications and supports read-one/read-all actions.
- Settings page loads and persists theme, locale, timezone, day-start hour, accent color and notification preferences through `/settings`.
- Backup action downloads the complete authenticated server export from `/backup/export` rather than exporting only whatever happens to be loaded in the UI.
- Rewards page reads current XP, level, badges and XP events from `/rewards`.
- Duplicate route declarations in `routes/api.php` were removed.

Verification note: PHP syntax checks pass. `npm install --no-audit --no-fund` timed out in the build environment, so the Vue typecheck/build and authenticated end-to-end tests have not been verified here. Run those checks locally after dependencies install.
