# NutriGrowth Backend — Architecture

## Gambaran Sistem

```
┌─────────────────────────────────────────────────┐
│               Klien Eksternal                   │
│  Flutter App (port 8080)  │  React Web (8080)   │
└────────────────┬───────────────────┬────────────┘
                 │ HTTP REST API     │
                 ▼                   ▼
┌─────────────────────────────────────────────────┐
│           NutriGrowth Backend                   │
│              Laravel 10 / PHP 8.1               │
│                                                 │
│  Routes → Controllers → Models → Eloquent ORM  │
│                                                 │
│  Auth: Laravel Sanctum (Bearer token)           │
└────────────────────────┬────────────────────────┘
                         │ PostgreSQL (pgsql driver)
                         ▼
┌─────────────────────────────────────────────────┐
│                  Supabase                       │
│           PostgreSQL Database                   │
│  (host: db.<ref>.supabase.co:5432)              │
└─────────────────────────────────────────────────┘
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
│   │   ├── WaitlistController.php   — submit waitlist
│   │   └── Api/
│   │       ├── ChildController.php  — CRUD anak (scoped per user)
│   │       └── FoodController.php   — read-only daftar makanan
│   └── Middleware/                  — Sanctum, CORS, dll.
├── Models/
│   ├── User.php                     — HasMany children, HasMany tokens
│   ├── Child.php                    — BelongsTo user
│   ├── Food.php                     — standalone catalog
│   └── Waitlist.php                 — standalone
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
| status                  | varchar(255) | pending / success / failed |
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
User  ──< PersonalAccessTokens  (Sanctum)
```

`Food` dan `Waitlist` berdiri sendiri tanpa relasi ke `User`.

---

## Analisis Kebutuhan Tabel

Bagian ini merangkum tabel yang sudah ada dan tabel tambahan yang disarankan untuk kebutuhan website serta aplikasi.

### Sudah Memadai untuk MVP

| Tabel                    | Fungsi                                  |
| ------------------------ | --------------------------------------- |
| `users`                  | Akun login dan identitas dasar pengguna |
| `children`               | Data anak milik user                    |
| `foods`                  | Katalog makanan dan nilai gizi          |
| `waitlists`              | Pendaftaran minat dari landing page     |
| `personal_access_tokens` | Token autentikasi Sanctum               |

### Prioritas Tinggi untuk Ditambahkan

| Tabel                                | Fungsi                                                                    | Dipakai oleh             |
| ------------------------------------ | ------------------------------------------------------------------------- | ------------------------ |
| `child_growth_records`               | Menyimpan riwayat pengukuran berat, tinggi, MUAC, dan tanggal pemeriksaan | Aplikasi + dashboard web |
| `recommendation_requests`            | Menyimpan input profil user yang dikirim ke AI                            | Aplikasi + website       |
| `recommendation_results`             | Menyimpan hasil rekomendasi AI, termasuk rentang harga                    | Aplikasi + website       |
| `recommendation_items`               | Detail makanan yang direkomendasikan AI                                   | Aplikasi + website       |
| `roles` atau kolom `role` di `users` | Membedakan admin, staf, dan user biasa                                    | Website admin + aplikasi |

### Prioritas Menengah

| Tabel                 | Fungsi                                                      | Dipakai oleh       |
| --------------------- | ----------------------------------------------------------- | ------------------ |
| `user_profiles`       | Menyimpan data tambahan seperti no. HP, alamat, foto profil | Website + aplikasi |
| `contact_messages`    | Menampung pesan dari form kontak website                    | Website            |
| `website_contents`    | Menyimpan banner, FAQ, testimoni, dan konten landing page   | Website            |
| `notification_tokens` | Menyimpan token push notification perangkat                 | Aplikasi           |
| `audit_logs`          | Mencatat perubahan data oleh admin                          | Website admin      |

### Rekomendasi Relasi

| Relasi                                                  | Keterangan                                                    |
| ------------------------------------------------------- | ------------------------------------------------------------- |
| `users` 1..n `children`                                 | Satu user dapat memiliki banyak anak                          |
| `children` 1..n `child_growth_records`                  | Satu anak punya banyak riwayat pengukuran                     |
| `users` 1..n `recommendation_requests`                  | Satu user bisa mengirim banyak request ke AI                  |
| `recommendation_requests` 1..n `recommendation_results` | Satu request bisa menghasilkan satu atau beberapa versi hasil |
| `recommendation_results` 1..n `recommendation_items`    | Satu hasil rekomendasi berisi banyak makanan                  |
| `users` 1..n `audit_logs`                               | Satu user/admin dapat menghasilkan banyak log perubahan       |

### Urutan Implementasi yang Disarankan

1. Tambah `child_growth_records` supaya data pertumbuhan tidak hilang dan bisa dibuat grafik.
2. Tambah `roles` atau `role` di `users` supaya website admin aman dan terstruktur.
3. Tambah `recommendation_requests`, `recommendation_results`, dan `recommendation_items` karena rekomendasi makanan datang dari AI.
4. Tambah `website_contents` dan `contact_messages` jika website akan dikelola via admin panel.
5. Tambah `notification_tokens` dan `audit_logs` jika butuh notifikasi dan jejak aktivitas.

### Bentuk Input dan Output AI

AI sebaiknya menerima profil terstruktur dari user, misalnya:

| Field            | Contoh                        |
| ---------------- | ----------------------------- |
| child_age_months | 36                            |
| gender           | male                          |
| weight_kg        | 12.4                          |
| height_cm        | 92                            |
| muac_cm          | 14.2                          |
| allergies        | telur, susu                   |
| budget_min       | 10000                         |
| budget_max       | 25000                         |
| preference       | tinggi protein, makanan lokal |

Output AI yang disimpan ke database sebaiknya mencakup:

| Field                  | Keterangan                           |
| ---------------------- | ------------------------------------ |
| recommendation_summary | Ringkasan singkat hasil AI           |
| budget_min             | Batas bawah harga rekomendasi        |
| budget_max             | Batas atas harga rekomendasi         |
| confidence_note        | Catatan atau alasan rekomendasi      |
| items                  | Daftar makanan yang direkomendasikan |

Dengan pola ini, website atau aplikasi cukup mengirim profil user ke AI, lalu menyimpan hasilnya di backend untuk riwayat, tampilan ulang, dan analisis berikutnya.

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

Dikonfigurasi di `config/cors.php`. Klien yang diizinkan:

- Flutter app (Android emulator: `10.0.2.2:8080`, iOS/web: `localhost:8080`)
- React Web (`localhost:5173` saat development)

Untuk production, set `allowed_origins` ke domain spesifik.
