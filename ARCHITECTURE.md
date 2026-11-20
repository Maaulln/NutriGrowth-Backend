# NutriGrowth Backend — Architecture

## Gambaran Sistem

```
┌─────────────────────────────────────────────────┐
│               Klien Eksternal                   │
│       Flutter App     │     React Web           │
└────────────────┬───────────────────┬────────────┘
                 │ HTTP REST API     │
                 ▼                   ▼
┌─────────────────────────────────────────────────┐
│           NutriGrowth Backend                   │
│              Laravel 10 / PHP 8.1               │
│                                                 │
│  Routes → Controllers → Models → Eloquent ORM  │
│  Services/AiService → HTTP ke NutriGrowth AI    │
│                                                 │
│  Auth: Laravel Sanctum (Bearer token)           │
└──────────┬──────────────────────────┬───────────┘
           │ PostgreSQL (pgsql)       │ POST /analyze
           ▼                          ▼
┌────────────────────────┐   ┌────────────────────┐
│       Supabase         │   │  NutriGrowth AI    │
│  PostgreSQL Database   │   │  FastAPI (Python)  │
│  (db.<ref>.supabase.co)│   │  AI_SERVICE_URL    │
└────────────────────────┘   └────────────────────┘
```

---

## Pola Arsitektur

Backend mengikuti pola **MVC (Model-View-Controller)** bawaan Laravel, dengan penggunaan API-only (tidak ada Blade view yang aktif digunakan).

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php       — register, login, logout, me
│   │   ├── UserController.php       — CRUD user (admin)
│   │   ├── WaitlistController.php   — submit waitlist (publik), kelola (admin)
│   │   └── Api/
│   │       ├── AnalysisController.php     — POST /analyze, riwayat assessment
│   │       ├── ChildController.php        — CRUD anak (scoped per user), growth records
│   │       ├── AdminChildController.php   — data anak semua user (admin)
│   │       ├── FoodController.php         — daftar makanan (publik), CRUD (admin)
│   │       ├── MealPlanController.php     — CRUD rencana makan + item
│   │       └── NotificationController.php — daftar, tandai dibaca, hapus
│   └── Middleware/                  — Sanctum, CORS, EnsureUserIsAdmin (alias `admin`)
├── Models/
│   ├── User.php                     — HasMany children, HasMany tokens
│   ├── Child.php                    — BelongsTo user, HasMany growth records
│   ├── ChildGrowthRecord.php, StuntingAssessment.php
│   ├── RecommendationRequest.php / RecommendationResult.php / RecommendationItem.php
│   ├── MealPlan.php, MealPlanItem.php, AppNotification.php
│   ├── Food.php                     — katalog makanan
│   └── Waitlist.php                 — standalone
├── Services/
│   └── AiService.php                — HTTP client ke NutriGrowth AI (timeout 60 dtk)
├── Console/Commands/FetchFoodImages.php — isi image_url makanan
└── Providers/
    └── RouteServiceProvider.php     — API prefix + middleware
```

---

## Autentikasi

Menggunakan **Laravel Sanctum** dengan strategi token stateless:

```
POST /api/auth/login
  → Validasi kredensial
  → Hapus token lama ($user->tokens()->delete())
  → Buat token baru ($user->createToken('nutriGrowth-app'))
  → Return plainTextToken

Request berikutnya:
  Header: Authorization: Bearer <token>
  → Middleware auth:sanctum memvalidasi token via tabel personal_access_tokens
```

---

## Database Schema

### `users`

| Kolom                   | Tipe         | Keterangan |
| ----------------------- | ------------ | ---------- |
| id                      | bigint PK    |            |
| name                    | varchar(255) |            |
| email                   | varchar(255) | unique     |
| password                | varchar(255) | bcrypt     |
| role                    | varchar      | default `user`; `admin` untuk route admin |
| created_at / updated_at | timestamp    |            |

### `children`

| Kolom                   | Tipe         | Keterangan                             |
| ----------------------- | ------------ | -------------------------------------- |
| id                      | bigint PK    |                                        |
| user_id                 | bigint FK    | cascade delete                         |
| name                    | varchar(255) |                                        |
| gender                  | enum         | male / female                          |
| birth_date              | date         |                                        |
| image_url               | varchar      | nullable                               |
| weight_kg               | decimal(5,2) | nullable                               |
| height_cm               | decimal(5,2) | nullable                               |
| muac_cm                 | decimal(5,2) | nullable (Mid-Upper Arm Circumference) |
| allergies               | json         | nullable, daftar alergi                |
| exclusive_breastfeeding | boolean      | default true                           |
| supplement_intake       | varchar      | regular / irregular / none (default none) |
| illness_frequency       | varchar      | low / medium / high (default low)      |
| created_at / updated_at | timestamp    |                                        |

### `child_growth_records`

| Kolom                   | Tipe         | Keterangan         |
| ----------------------- | ------------ | ------------------ |
| id                      | bigint PK    |                    |
| child_id                | bigint FK    | cascade delete     |
| recorded_at             | date         | tanggal pengukuran |
| weight_kg               | decimal(5,2) | nullable           |
| height_cm               | decimal(5,2) | nullable           |
| muac_cm                 | decimal(5,2) | nullable           |
| notes                   | text         | nullable           |
| created_at / updated_at | timestamp    |                    |

### `recommendation_requests`

| Kolom                   | Tipe         | Keterangan                 |
| ----------------------- | ------------ | -------------------------- |
| id                      | bigint PK    |                            |
| user_id                 | bigint FK    | cascade delete             |
| child_id                | bigint FK    | nullable, null on delete   |
| payload                 | json         | input profil user ke AI    |
| status                  | varchar(255) | pending / success / error  |
| error_message           | text         | nullable                   |
| created_at / updated_at | timestamp    |                            |

### `recommendation_results`

| Kolom                     | Tipe      | Keterangan             |
| ------------------------- | --------- | ---------------------- |
| id                        | bigint PK |                        |
| recommendation_request_id | bigint FK | cascade delete         |
| summary                   | text      | ringkasan hasil AI     |
| budget_min                | integer   | batas bawah harga      |
| budget_max                | integer   | batas atas harga       |
| confidence_note           | text      | alasan atau catatan AI |
| raw_response              | json      | nullable, response AI penuh |
| created_at / updated_at   | timestamp |                        |

### `recommendation_items`

| Kolom                    | Tipe         | Keterangan                      |
| ------------------------ | ------------ | ------------------------------- |
| id                       | bigint PK    |                                 |
| recommendation_result_id | bigint FK    | cascade delete                  |
| food_id                  | bigint FK    | nullable, restrict delete       |
| food_name                | varchar(255) | nama makanan rekomendasi        |
| category                 | varchar(255) | kategori makanan                |
| serving_size             | varchar(255) | porsi yang disarankan           |
| estimated_price          | integer      | estimasi harga per item         |
| reason                   | text         | alasan makanan direkomendasikan |
| created_at / updated_at  | timestamp    |                                 |

### `foods`

| Kolom             | Tipe         | Keterangan                                  |
| ----------------- | ------------ | ------------------------------------------- |
| id                | bigint PK    |                                             |
| name              | varchar(255) |                                             |
| category          | varchar      | protein / carbo / vegetable / fruit / dairy |
| calories          | float        | per serving                                 |
| protein           | float        | gram                                        |
| fat               | float        | gram                                        |
| carbs             | float        | gram                                        |
| price_per_serving | integer      | rupiah                                      |
| serving_size      | varchar      | default '100g'                              |
| description       | text         | nullable                                    |
| image_url         | varchar      | nullable                                    |
| fiber_g           | float        | default 0 (field untuk AI)                  |
| serving_size_g    | float        | default 100                                 |
| price_min / price_max | integer  | default 0, rentang harga untuk AI           |
| allergens         | varchar      | nullable                                    |
| age_min_months    | integer      | default 0                                   |
| texture           | varchar      | default `lembut`                            |

### `stunting_assessments`

| Kolom           | Tipe      | Keterangan                              |
| --------------- | --------- | --------------------------------------- |
| id              | bigint PK |                                         |
| user_id         | bigint FK | cascade delete                          |
| child_id        | bigint FK | nullable, null on delete                |
| payload         | json      | input yang dikirim ke AI                |
| status_gizi     | varchar   | nullable (severely stunted / stunted / normal / tinggi) |
| risk_level      | varchar   | low / medium / high                     |
| risk_score      | integer   | 0-100                                   |
| summary         | text      | nullable                                |
| analysis / recommendations / warning_flags | json | nullable          |
| status          | varchar   | default `success`                       |
| error_message   | text      | nullable                                |

### `meal_plans` dan `meal_plan_items`

`meal_plans`: `user_id`, `child_id` (nullable), `plan_date`, `title`, `notes`.
`meal_plan_items`: `meal_plan_id`, `food_id`, `meal_type`, `serving_amount`, `notes`.

### `notifications`

`user_id`, `type` (`stunting_alert` / `analysis_done`), `title`, `message`, `is_read`.

### `waitlists`

| Kolom                   | Tipe         | Keterangan |
| ----------------------- | ------------ | ---------- |
| id                      | bigint PK    |            |
| name                    | varchar(255) |            |
| email                   | varchar(255) |            |
| created_at / updated_at | timestamp    |            |

---

## Relasi Eloquent

```
User  ──< Children    (hasMany / belongsTo)
Child ──< ChildGrowthRecords (hasMany / belongsTo)
User  ──< RecommendationRequests (hasMany / belongsTo)
RecommendationRequest ──< RecommendationResults (hasMany / belongsTo)
RecommendationResult ──< RecommendationItems (hasMany / belongsTo)
Food ──< RecommendationItems (hasMany / belongsTo, optional)
User  ──< StuntingAssessments (hasMany / belongsTo)
User  ──< MealPlans ──< MealPlanItems >── Food
User  ──< Notifications
User  ──< PersonalAccessTokens  (Sanctum)
```

`Food` dan `Waitlist` berdiri sendiri tanpa relasi ke `User`.

---

## Endpoint Utama

| Grup | Endpoint | Akses |
|---|---|---|
| Auth | `POST /api/auth/register`, `/login`; `POST /logout`, `GET /me` | publik / token |
| Makanan | `GET /api/foods`, `/foods/{id}` | publik |
| Waitlist | `POST /api/waitlist` | publik |
| Analisis | `POST /api/analyze`, `GET /api/analyses`, `GET /api/children/{id}/assessments` | token |
| Anak | `apiResource children`, `GET /api/children/{id}/growth-records` | token (milik sendiri) |
| Notifikasi | `GET/PATCH/DELETE /api/notifications...` | token |
| Meal plan | `/api/meal-plans` + `/items` (CRUD) | token |
| Admin | `users`, `admin/children`, `admin/users/{id}/children`, `admin/foods`, `GET/PUT/DELETE waitlist` | token + role `admin` |

---

## Integrasi AI (`POST /api/analyze`)

1. Validasi input (`child_id`, usia, gender, berat, tinggi, MUAC, alergi, budget, `nutrition_history`).
2. Ambil makanan dari `foods` sesuai `age_min_months` dan budget.
3. Simpan `recommendation_requests` (status `pending`).
4. `AiService::analyze()` memanggil `POST {AI_SERVICE_URL}/analyze` beserta `food_candidates`.
   Gagal → request ditandai `error`, response 502.
5. Simpan `stunting_assessments`, `child_growth_records` (updateOrCreate per hari),
   `recommendation_results` dan `recommendation_items`.
6. Tambahkan `image_url` makanan dari DB ke `food_items`.
7. Buat notifikasi (`stunting_alert` jika stunted / severely stunted, selain itu `analysis_done`).
8. Return `{ status, message, data: <response AI> }`.

Skema input/output AI: lihat `ARCHITECTURE.md` di repo NutriGrowth AI.

---

## Belum Ada

Tabel yang pernah direncanakan tetapi belum dibuat: `user_profiles`,
`contact_messages`, `website_contents`, `notification_tokens` (push),
`audit_logs`.

---

## Flow Request Tipikal

```
Flutter POST /api/auth/login
  → RouteServiceProvider (prefix: api)
  → AuthController::login()
  → Validator::make()
  → Auth::attempt()
  → User::tokens()->delete()
  → User::createToken()
  → Response::json(['token' => ...])

Flutter GET /api/children  (Authorization: Bearer xxx)
  → Middleware: auth:sanctum
  → ChildController::index()
  → Child::where('user_id', auth()->id())->get()
  → Response::json(['data' => ...])
```

---

## Supabase Integration

Supabase menyediakan PostgreSQL terkelola dengan dua mode koneksi:

| Mode                 | Host                                 | Port | Cocok untuk                 |
| -------------------- | ------------------------------------ | ---- | --------------------------- |
| Direct               | `db.<ref>.supabase.co`               | 5432 | Development, migration      |
| Pooler (Transaction) | `aws-0-<region>.pooler.supabase.com` | 6543 | Production (serverless/PHP) |

Laravel Eloquent menggunakan driver `pgsql` yang kompatibel penuh dengan PostgreSQL Supabase. Tidak ada perubahan kode ORM — hanya update nilai `DB_*` di `.env`.

Keunggulan Supabase vs MySQL lokal:

- Managed backups otomatis
- Row Level Security (RLS) opsional untuk keamanan tambahan
- Realtime subscription (jika dibutuhkan di masa mendatang)
- Dashboard UI untuk inspeksi tabel langsung

---

## CORS

Dikonfigurasi di `config/cors.php`. Origin yang diizinkan saat ini:

- `http://localhost:8000` (WebClient)
- `http://localhost:8001` (Admin)
- `http://localhost:5173` (React/Vite)

Aplikasi mobile native tidak terkena CORS. Untuk Flutter web atau domain
production, tambahkan origin terkait ke `allowed_origins`.
