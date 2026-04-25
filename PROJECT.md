# نظام تسجيل الخريجين (Graduate Students)

هذا الملف يشرح **ماذا يفعل المشروع**، **التقنيات المستخدمة**، و**خطوات التشغيل** بعد استنساخه من GitHub (أو أي مستودع Git).

---

## نظرة عامة

تطبيق ويب مبني على **Laravel** لإدارة **طلبات التسجيل للخريجين**: يقدّم الزائر طلبًا عبر نموذج عام، ويقوم فريق إداري (مدير / مراجع) بمراجعة الطلبات والموافقة أو الرفض. بعد الموافقة يمكن للخريج تسجيل الدخول وتحديث ملفه الشخصي. لوحة تحكم للإدارة تشمل لوحة إحصائيات، تصدير بيانات الخريجين (Excel)، وإدارة الجامعات وسنوات التخرج والأقسام والتخصصات والمستخدمين والصلاحيات وسجل النشاط.

---

## التقنيات الرئيسية

| المكوّن | الإصدار / الملاحظة |
|--------|---------------------|
| PHP | ‎^8.3 |
| Laravel | ‎^13 |
| قاعدة البيانات الافتراضية في `.env.example` | SQLite |
| الواجهة الأمامية | Vite + Tailwind CSS 4 |
| الصلاحيات | Spatie Laravel Permission |
| سجل التغييرات | Spatie Activity Log |
| التصدير | Maatwebsite Excel |

---

## المتطلبات على الجهاز قبل التشغيل

1. **PHP 8.3+** مع الإضافات التي يحتاجها Laravel (مثل `openssl`, `pdo`, `mbstring`, `tokenizer`, `xml`, `ctype`, `json`, `fileinfo`).
2. **Composer** لإدارة حزم PHP.
3. **Node.js** (يفضّل LTS) و **npm** لبناء وتشغيل أصول Vite.
4. **قاعدة بيانات**: إما **SQLite** (الأسهل للتجربة السريعة) أو **MySQL/MariaDB** (مناسب مع Laragon).

---

## تثبيت المتطلبات على الجهاز (مرة واحدة)

### التحقق من أن الأدوات مثبّتة

```bash
php -v
composer -V
node -v
npm -v
```

يجب أن يظهر PHP **8.3 أو أحدث**، وإصدارات Composer و Node و npm بدون خطأ.

### Windows — باستخدام [Laragon](https://laragon.org/) (موصى به لهذا المشروع)

1. ثبّت Laragon واختر حزمة فيها **PHP 8.3+**.
2. من Laragon: **Menu → PHP → Extensions** وتأكد من تفعيل الامتدادات التي يحتاجها Laravel (`openssl`, `pdo_sqlite` أو `pdo_mysql`, `mbstring`, `tokenizer`, `xml`, `ctype`, `fileinfo`, `json`).
3. ثبّت **Composer** من [getcomposer.org/download](https://getcomposer.org/download/) إن لم يكن متوفرًا في PATH.
4. ثبّت **Node.js LTS** من [nodejs.org](https://nodejs.org/) أو عبر الأمر أدناه.

### Windows — تثبيت سريع عبر winget (بدون Laragon)

في **PowerShell** أو **Terminal**:

```powershell
winget install OpenJS.NodeJS.LTS --accept-package-agreements
winget install Composer.Composer --accept-package-agreements
```

لـ PHP يمكن تثبيت نسخة مدعومة (مثلاً عبر حزم المجتمع) أو الاعتماد على Laragon/XAMPP. بعد التثبيت أغلق الطرفية وافتحها من جديد ثم نفّذ أوامر التحقق أعلاه.

### Linux (مثال Debian/Ubuntu)

```bash
sudo apt update
sudo apt install -y php8.3 php8.3-cli php8.3-sqlite3 php8.3-mysql php8.3-xml php8.3-mbstring php8.3-curl php8.3-zip unzip
# Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
# Node.js LTS (عبر NodeSource أو مديرك المفضل)
```

### macOS (مثال Homebrew)

```bash
brew install php@8.3 composer node
```

---

## بعد تنزيل المشروع من GitHub

افتح الطرفية (Terminal) داخل مجلد المشروع، ثم نفّذ الخطوات التالية بالترتيب.

### أوامر تثبيت المشروع كاملة (نسخ ولصق)

استخدم المقطع المناسب لنظامك بعد الانتقال لمجلد المشروع (`cd graduate_students` أو المسار الفعلي).

**SQLite (كما في `.env.example`) — PowerShell (Windows):**

```powershell
composer install
Copy-Item .env.example .env
php artisan key:generate
New-Item -ItemType File -Path database\database.sqlite -Force
php artisan migrate
php artisan db:seed
php artisan storage:link
npm install
npm run build
php artisan serve
```

**SQLite — bash (Linux / macOS / Git Bash):**

```bash
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate
php artisan db:seed
php artisan storage:link
npm install
npm run build
php artisan serve
```

**MySQL (مثلاً Laragon) — بعد إنشاء قاعدة بيانات فارغة**

1. نفّذ الأوامر أدناه، ثم **قبل** `php artisan migrate` افتح `.env` واضبط `DB_CONNECTION=mysql` و `DB_DATABASE` و `DB_USERNAME` و `DB_PASSWORD`.
2. أو نفّذ حتى `php artisan key:generate` ثم عدّل `.env` ثم أكمل من `migrate`.

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan storage:link
npm install
npm run build
php artisan serve
```

على Windows: استبدل `cp .env.example .env` بـ `Copy-Item .env.example .env`.

**بديل أسرع — `composer run setup` (بعد إنشاء ملف SQLite فقط)**

يُنشئ `.env` و `APP_KEY` ويشغّل الهجرات و `npm install` و `npm run build` تلقائيًا؛ **لا** يشغّل السيدر.

PowerShell:

```powershell
New-Item -ItemType File -Path database\database.sqlite -Force
composer run setup
php artisan db:seed
php artisan storage:link
php artisan serve
```

bash:

```bash
touch database/database.sqlite
composer run setup
php artisan db:seed
php artisan storage:link
php artisan serve
```

---

### 1) استنساخ المستودع (إن لم يكن المجلد موجودًا)

```bash
git clone <رابط-المستودع-على-GitHub>.git
cd graduate_students
```

(استبدل اسم المجلد إذا كان مختلفًا.)

### 2) تثبيت حزم PHP

```bash
composer install
```

### 3) إعداد ملف البيئة

```bash
copy .env.example .env
```

على Linux أو macOS:

```bash
cp .env.example .env
```

ثم عدّل `.env` حسب قاعدة البيانات التي تستخدمها (انظر القسم التالي).

### 4) مفتاح التطبيق

```bash
php artisan key:generate
```

### 5) قاعدة البيانات

**خيار أ — SQLite (كما في `.env.example`):**

- تأكد أن السطر في `.env` هو: `DB_CONNECTION=sqlite`
- أنشئ ملف قاعدة البيانات الفارغ إن لم يكن موجودًا:

```bash
# PowerShell
New-Item -ItemType File -Path database\database.sqlite -Force

# أو في bash
touch database/database.sqlite
```

**خيار ب — MySQL (مثلاً مع Laragon):**

في `.env` عيّن:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=اسم_قاعدة_البيانات
DB_USERNAME=root
DB_PASSWORD=
```

أنشئ قاعدة البيانات من phpMyAdmin أو من الطرفية، ثم شغّل الهجرات.

### 6) تشغيل الهجرات (Migrations)

```bash
php artisan migrate
```

### 7) تعبئة بيانات أولية (مهم لدخول الإدارة)

```bash
php artisan db:seed
```

السيدر ينشئ:

- أدوارًا وصلاحيات (Spatie).
- مستخدم **مدير**: البريد `admin@graduate.local` — كلمة المرور `password`.
- مستخدم **مراجع**: البريد `reviewer@graduate.local` — كلمة المرور `password`.
- جامعة وسنوات تخرج وأقسام وتخصصات نموذجية.

**تحذير أمني:** غيّر كلمات المرور فورًا في أي بيئة غير محلية.

### 8) رابط التخزين للملفات العامة (إن كان المشروع يرفع ملفات للتخزين العام)

```bash
php artisan storage:link
```

(نفّذها إذا ظهرت مشاكل في عرض الملفات المرفوعة؛ حسب إعدادات المشروع.)

### 9) تثبيت أصول الواجهة وبناءها

```bash
npm install
npm run build
```

للتطوير مع إعادة التحميل السريعة:

```bash
npm run dev
```

في وضع التطوير يجب أن يعمل **Vite** (`npm run dev`) مع خادم PHP (مثل `php artisan serve` أو Virtual Host في Laragon)، وإلا قد لا تُحمَّل ملفات CSS/JS.

### 10) تشغيل الخادم

```bash
php artisan serve
```

ثم افتح المتصفح على العنوان الذي يظهر (عادة `http://127.0.0.1:8000`).

**مع Laragon:** يمكنك بدل `php artisan serve` ضبط Virtual Host يشير إلى مجلد `public` وتضبط `APP_URL` في `.env` ليطابق الرابط.

---

## اختصار عبر Composer (اختياري)

المشروع يعرّف سكربت `setup` في `composer.json` يقوم بجزء من الخطوات (بدون `db:seed`):

```bash
composer run setup
```

بعدها نفّذ يدويًا:

```bash
php artisan db:seed
```

---

## مسارات مهمة في التطبيق

- `/` يعيد التوجيه إلى نموذج التسجيل: `/register`
- `/login` — تسجيل الدخول (للمستخدمين الذين لديهم حساب بعد الموافقة على الطلب، وللإدارة)
- `/admin/dashboard` — لوحة الإدارة (يتطلب مستخدمًا بدور إداري وصلاحيات مناسبة)
- `/profile` — تعديل الملف الشخصي للخريج المسجّل

---

## أوامر مفيدة أخرى

| الأمر | الغرض |
|--------|--------|
| `php artisan migrate:fresh --seed` | إعادة إنشاء الجداول من الصفر مع إعادة السيدر (يحذف البيانات) |
| `php artisan config:clear` | مسح الكاش بعد تغيير `.env` |
| `composer run dev` | (إن وُجد) تشغيل عدة عمليات للتطوير حسب تعريف المشروع |

---

## ملخص سريع (قائمة تحقق)

للنسخ السريع انظر قسم **«أوامر تثبيت المشروع كاملة (نسخ ولصق)»** أعلاه.

1. تثبيت PHP 8.3+ و Composer و Node/npm (قسم **تثبيت المتطلبات على الجهاز**)
2. `git clone` ثم `cd` لمجلد المشروع
3. `composer install`
4. نسخ `.env.example` إلى `.env`
5. `php artisan key:generate`
6. إنشاء `database/database.sqlite` **أو** ضبط MySQL في `.env` وإنشاء القاعدة
7. `php artisan migrate`
8. `php artisan db:seed`
9. `php artisan storage:link`
10. `npm install` ثم `npm run build` (أو `npm run dev` أثناء التطوير)
11. `php artisan serve` (أو Virtual Host في Laragon)
12. تسجيل الدخول كمدير: `admin@graduate.local` / `password`

---

## الرخصة

إطار Laravel والهيكل الافتراضي مرخّصون تحت **MIT**؛ راجع ملف `LICENSE` في المستودع إن وُجد، وأي ترخيص إضافي يضيفه مالك المشروع.
