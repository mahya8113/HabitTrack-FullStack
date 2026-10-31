# دامنه پیاده‌سازی HabitTrack

این نسخه APIهای زیر را اضافه/گسترش می‌دهد: تنظیمات، تغییر پروفایل، تغییر رمز و حذف حساب، اعلان‌های درون‌برنامه‌ای، پیشنهادهای مربی قاعده‌محور و ثبت تصمیم/ارزیابی، گزارش آماری، CSV عادت‌ها، خروجی JSON پشتیبان، یادداشت روزانه، مراحل اهداف و API پایه XP/سطح/نشان.

## مسیرهای جدید (همگی به‌جز auth عمومی نیازمند Sanctum هستند)
- `PATCH /api/auth/profile`
- `GET|PUT /api/settings`, `PUT /api/settings/password`, `DELETE /api/account`
- `GET /api/notifications?unread=1`, `PATCH /api/notifications/{id}/read`, `POST /api/notifications/read-all`, `DELETE /api/notifications/{id}`
- `GET /api/coach/recommendations`, `POST /api/coach/recommendations/{id}/decision`, `POST /api/coach/recommendations/{id}/evaluate`
- `GET /api/reports/summary?from=YYYY-MM-DD&to=YYYY-MM-DD`, `GET /api/reports/habits.csv`, `GET /api/backup/export`
- `GET|PUT /api/daily-note`
- `GET /api/goals/{goal}/milestones`, `POST /api/goals/{goal}/milestones`, `PATCH|DELETE /api/goals/{goal}/milestones/{milestone}`
- `GET /api/rewards`, `POST /api/rewards/award`

## هنوز نیازمند کار جداگانه است
- رابط کاربری باید به این APIها متصل شود؛ اضافه شدن API به معنی تکمیل خودکار صفحه Vue نیست.
- اعلان زمان‌بندی‌شده به Scheduler/Queue و دستور زمان‌بندی‌شده نیاز دارد؛ این API فعلاً مرکز اعلان داخل برنامه را پشتیبانی می‌کند.
- پشتیبان‌گیری در این نسخه خروجی JSON می‌دهد؛ بازیابی باید با اعتبارسنجی کامل، تراکنش، گزارش نتیجه و جلوگیری از داده تکراری تکمیل شود.
- PDF، اعلان مرورگر/Push، همگام‌سازی تعارض‌محور، زمان‌بندی تطبیقی پیشرفته، آزمون‌های مرورگر و استقرار واقعی نیازمند تست و تکمیل جداگانه‌اند.
- XP endpoint از کلید یکتای رویداد برای جلوگیری از امتیاز تکراری استفاده می‌کند؛ اتصال رویدادهای واقعی عادت/هدف/جلسه به امتیازدهی باید در سرویس‌های دامنه انجام شود.
