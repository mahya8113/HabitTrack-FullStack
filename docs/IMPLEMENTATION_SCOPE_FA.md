# نقشه تکمیل HabitTrack بر اساس مشخصات کامل

این سند معیار پیاده‌سازی است؛ وجود صفحه یا endpoint به‌تنهایی به معنی تکمیل قابلیت نیست.

## موارد افزوده‌شده در این نسخه
- تنظیمات کاربر ذخیره‌شونده در MySQL: تم، رنگ تأکیدی، زبان، منطقه زمانی، ساعت شروع روز، تنظیمات اعلان/حریم خصوصی/دسترس‌پذیری.
- API تغییر رمز عبور با بررسی رمز فعلی و لغو توکن‌های قبلی؛ API حذف حساب با تأیید رمز.
- مرکز اعلان‌های داخل برنامه با صفحه‌بندی، فیلتر خوانده‌نشده، خواندن تکی/همه و حذف اعلان.
- مربی قاعده‌محور بدون API خارجی: تحلیل اولیه تاریخچه عادت‌ها، ذخیره پیشنهاد، دلیل قابل توضیح، پذیرش/رد و ارزیابی نتیجه.
- جداول پایه مأموریت‌ها برای توسعه سیستم مأموریت روزانه/هفتگی.

## APIهای جدید
- `GET /api/settings`, `PUT /api/settings`
- `PUT /api/auth/password`, `DELETE /api/auth/account`
- `GET /api/notifications?unread=1&per_page=20`
- `POST /api/notifications/read-all`, `POST /api/notifications/{id}/read`, `DELETE /api/notifications/{id}`
- `GET /api/coach/recommendations`
- `POST /api/coach/recommendations/{id}/decision`
- `POST /api/coach/recommendations/{id}/evaluate`

## مواردی که همچنان باید در فازهای بعدی پیاده‌سازی و آزمون شوند
- تکمیل همه قابلیت‌های ۲۴ بخش مشخصات، به‌ویژه بازیابی رمز عبور ایمیلی، حذف/خروج نشست‌ها در همه کلاینت‌ها، برنامه‌های تکرار سفارشی عادت، زمان‌بندی تطبیقی با حل تداخل، محاسبه دقیق streak با منطقه زمانی و روز استراحت، XP/مدال با جلوگیری از تکرار، تولید CSV/PDF، import/export و بازیابی امن، اعلان‌های زمان‌بندی‌شده با Scheduler/Queue و Push، ارزیابی واقعی پیشنهادهای مربی، و همه صفحات فهرست‌شده.
- تکمیل اتصال صفحات Vue به endpointهای جدید و طراحی تست‌های API/UI.
- اجرای `composer install`, `php artisan test`, `npm install`, `npm run build` و تست end-to-end در محیطی با وابستگی‌های نصب‌شده. این محیط فاقد vendor/composer و نصب کامل node_modules بوده؛ بنابراین موفقیت تست‌های runtime ادعا نمی‌شود.

## راهنمای ادامه
1. در backend اجرا کنید: `composer install`، ساخت `.env`، تنظیم MySQL و `php artisan migrate --seed`.
2. تست‌ها: `php artisan test`.
3. در frontend اجرا کنید: `npm install` و `npm run build`.
4. سپس جریان‌های ثبت‌نام، ورود، ساخت عادت، ثبت روزانه، آمار، اعلان‌ها و تنظیمات را با دیتابیس واقعی آزمایش کنید.
