# NutriGrowth Backend

REST API server untuk aplikasi NutriGrowth — platform pemantauan gizi dan pertumbuhan anak. Dibangun dengan **Laravel 10** dan menggunakan **Supabase (PostgreSQL)** sebagai database.

---

## Tech Stack

| Layer       | Teknologi               |
| ----------- | ----------------------- |
| Framework   | Laravel 10              |
| Language    | PHP 8.1+                |
| Database    | Supabase (PostgreSQL)   |
| Auth        | Laravel Sanctum (token) |
| HTTP Client | Guzzle                  |

---

## Prasyarat

- PHP >= 8.1
- Composer
- Akun [Supabase](https://supabase.com) (untuk database)

---

## Setup

### 1. Install dependencies

```bash
composer install
```

### 2. Salin dan konfigurasi `.env`

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` dan isi variabel Supabase:

```env
APP_NAME=NutriGrowth
APP_ENV=local
APP_URL=http://localhost:8080

DB_CONNECTION=pgsql
DATABASE_URL=postgresql://postgres.<project-ref>:<your-supabase-db-password>@aws-1-ap-southeast-2.pooler.supabase.com:6543/postgres

# Supabase client vars (opsional, bila ada frontend/mobile yang memakai project ini)
# NEXT_PUBLIC_SUPABASE_URL=https://<project-ref>.supabase.co
# NEXT_PUBLIC_SUPABASE_PUBLISHABLE_KEY=<your-supabase-publishable-key>

# Gunakan Supabase Pooler (Transaction mode) untuk production
# DATABASE_URL=postgresql://postgres.<project-ref>:<your-supabase-db-password>@aws-1-ap-southeast-2.pooler.supabase.com:6543/postgres
```

> Kredensial Supabase tersedia di: **Supabase Dashboard → Project Settings → Database → Connection string**

### 3. Jalankan migrasi dan seeder

```bash
php artisan migrate
php artisan db:seed
```

### 4. Jalankan server

```bash
php artisan serve --port=8080
```

---

## API Endpoints

### Auth

| Method | Endpoint             | Auth   | Deskripsi                   |
| ------ | -------------------- | ------ | --------------------------- |
| POST   | `/api/auth/register` | Tidak  | Daftar akun baru            |
| POST   | `/api/auth/login`    | Tidak  | Login, returns Bearer token |
| POST   | `/api/auth/logout`   | Bearer | Revoke token                |
| GET    | `/api/auth/me`       | Bearer | Data user aktif             |

### Children

| Method | Endpoint             | Auth   | Deskripsi            |
| ------ | -------------------- | ------ | -------------------- |
| GET    | `/api/children`      | Bearer | List anak milik user |
| POST   | `/api/children`      | Bearer | Tambah data anak     |
| GET    | `/api/children/{id}` | Bearer | Detail anak          |
| PUT    | `/api/children/{id}` | Bearer | Update data anak     |
| DELETE | `/api/children/{id}` | Bearer | Hapus data anak      |

### Foods

| Method | Endpoint          | Auth  | Deskripsi          |
| ------ | ----------------- | ----- | ------------------ |
| GET    | `/api/foods`      | Tidak | List semua makanan |
| GET    | `/api/foods/{id}` | Tidak | Detail makanan     |

### Users (Admin)

| Method | Endpoint          | Auth  | Deskripsi       |
| ------ | ----------------- | ----- | --------------- |
| GET    | `/api/users`      | Tidak | List semua user |
| POST   | `/api/users`      | Tidak | Buat user baru  |
| PUT    | `/api/users/{id}` | Tidak | Update user     |
| DELETE | `/api/users/{id}` | Tidak | Hapus user      |

### Waitlist

| Method | Endpoint        | Auth  | Deskripsi       |
| ------ | --------------- | ----- | --------------- |
| POST   | `/api/waitlist` | Tidak | Daftar waitlist |

---

## Database Schema

```text
users           — id, name, email, password, timestamps
├── children    — id, user_id, name, gender, birth_date, image_url,
│                 weight_kg, height_cm, muac_cm, timestamps
waitlists       — id, name, email, timestamps
foods           — id, name, category, calories, protein, fat, carbs,
                  price_per_serving, serving_size, description, image_url, timestamps
personal_access_tokens — (Sanctum)
```

---

## Migrasi ke Supabase

Supabase menggunakan PostgreSQL. Tidak ada perubahan kode karena Eloquent mendukung PostgreSQL secara native — cukup ganti nilai `DB_*` di `.env`.

Jika sebelumnya menggunakan MySQL lokal, ekspor data dulu lalu import ke Supabase via **SQL Editor** di dashboard Supabase.

---

## CORS

Konfigurasi CORS ada di [`config/cors.php`](config/cors.php). Secara default semua origin diizinkan untuk development. Atur `allowed_origins` saat deployment ke production.
