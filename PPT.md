# NutriGrowth Backend — Presentasi Proyek

---

## SLIDE 1 — Cover

# NutriGrowth
### Platform Pemantauan Gizi & Pertumbuhan Anak Berbasis AI

**Backend REST API · Laravel 10 · Supabase · AI Integration**

---

## SLIDE 2 — Latar Belakang

### Masalah yang Dihadapi

- **Stunting** masih menjadi masalah gizi serius di Indonesia — prevalensi mencapai 21,6% pada 2022 (Kemenkes RI)
- Orang tua kesulitan memantau tumbuh kembang anak secara sistematis
- Minimnya akses informasi makanan bergizi yang **terjangkau dan sesuai usia**
- Tidak ada alat deteksi dini risiko stunting yang mudah digunakan oleh masyarakat umum
- Data pertumbuhan anak sering tidak terdokumentasi dengan baik

### Peluang

- Penetrasi smartphone yang tinggi di Indonesia membuka peluang solusi digital
- Teknologi AI dapat membantu analisis status gizi secara otomatis dan terpersonalisasi
- Rekam medis digital mempermudah pemantauan jangka panjang

---

## SLIDE 3 — Solusi & Tujuan Proyek

### Apa itu NutriGrowth?

**NutriGrowth** adalah platform digital berbasis mobile & web untuk:

| Tujuan | Deskripsi |
|--------|-----------|
| Pemantauan Pertumbuhan | Mencatat berat badan, tinggi badan, dan MUAC anak secara berkala |
| Deteksi Risiko Stunting | Analisis otomatis berbasis AI untuk mendeteksi risiko stunting dini |
| Rekomendasi Gizi | Saran makanan bergizi yang disesuaikan usia, alergi, dan budget keluarga |
| Meal Planning | Rencana makan harian untuk mendukung tumbuh kembang optimal |
| Riwayat Analisis | Menyimpan histori penilaian gizi untuk tracking jangka panjang |

### Target Pengguna
- **Orang tua** yang ingin memantau pertumbuhan anak usia 0–5 tahun
- **Tenaga kesehatan** yang mendampingi orang tua dalam pemantauan gizi
- **Administrator sistem** yang mengelola data dan katalog makanan

---

## SLIDE 4 — Tech Stack

### Teknologi yang Digunakan

| Layer | Teknologi | Keterangan |
|-------|-----------|------------|
| **Framework Backend** | Laravel 10 (PHP 8.1+) | MVC framework, Eloquent ORM |
| **Database** | Supabase (PostgreSQL) | Cloud-hosted, serverless-ready |
| **Autentikasi** | Laravel Sanctum | Token-based Bearer Auth |
| **HTTP Client** | Guzzle HTTP 7.x | Komunikasi ke AI Service |
| **AI Integration** | External AI Server (Python) | Analisis stunting & rekomendasi |
| **Build Tools** | Vite | Asset bundling |
| **Testing** | PHPUnit 10 | Unit & integration testing |
| **Dev Environment** | Laravel Sail (Docker) | Container-based development |

### Client Side (yang dilayani API ini)
- **Flutter** — Mobile App (Android/iOS)
- **React + Vite** — Web Client (localhost:5173)
- **React** — Admin Dashboard (localhost:8001)

---

## SLIDE 5 — Arsitektur Sistem

```
┌──────────────────────────────────────────────┐
│              CLIENT LAYER                    │
│   Flutter App │ React Web │ Admin Dashboard  │
└──────────────────────┬───────────────────────┘
                       │ HTTP REST API
                       │ Authorization: Bearer <token>
                       ▼
┌──────────────────────────────────────────────┐
│       NutriGrowth Backend (Laravel 10)       │
│                                              │
│  ┌─────────────────────────────────────────┐ │
│  │       Middleware Stack                  │ │
│  │  CORS │ Sanctum Auth │ Rate Limiting    │ │
│  └─────────────────────────────────────────┘ │
│                      ▼                       │
│  ┌─────────────────────────────────────────┐ │
│  │       Router (routes/api.php)           │ │
│  └─────────────────────────────────────────┘ │
│                      ▼                       │
│  ┌─────────────────────────────────────────┐ │
│  │           Controller Layer              │ │
│  │  Auth │ Child │ Food │ Analysis │ Admin  │ │
│  └─────────────────────────────────────────┘ │
│                      ▼                       │
│  ┌─────────────────────────────────────────┐ │
│  │         Service Layer (AiService)       │ │
│  └─────────────────────────────────────────┘ │
│                      ▼                       │
│  ┌─────────────────────────────────────────┐ │
│  │     Model Layer (Eloquent ORM)          │ │
│  └─────────────────────────────────────────┘ │
└──────────────────────┬───────────────────────┘
          ┌────────────┴──────────────┐
          ▼                           ▼
┌──────────────────┐       ┌──────────────────────┐
│  Supabase (PG)   │       │   AI Service (Python) │
│  - users         │       │  - Hitung Z-score      │
│  - children      │       │  - Deteksi stunting    │
│  - foods         │       │  - Rekomendasi makan   │
│  - assessments   │       │  POST /analyze         │
└──────────────────┘       └──────────────────────┘
```

---

## SLIDE 6 — Struktur Folder Proyek

```
NutriGrowth-Backend/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php           ← Register, Login, Logout
│   │   │   ├── UserController.php           ← CRUD User (Admin)
│   │   │   ├── WaitlistController.php       ← Landing Page Waitlist
│   │   │   └── Api/
│   │   │       ├── ChildController.php      ← CRUD Anak + Growth Records
│   │   │       ├── FoodController.php       ← CRUD Makanan
│   │   │       ├── AnalysisController.php   ← AI Analysis & Assessments
│   │   │       └── AdminChildController.php ← Admin: Lihat Semua Anak
│   │   └── Middleware/                      ← Sanctum, CORS, Auth
│   ├── Models/
│   │   ├── User.php
│   │   ├── Child.php
│   │   ├── ChildGrowthRecord.php
│   │   ├── Food.php
│   │   ├── MealPlan.php + MealPlanItem.php
│   │   ├── RecommendationRequest/Result/Item.php
│   │   ├── StuntingAssessment.php
│   │   └── Waitlist.php
│   └── Services/
│       └── AiService.php                    ← HTTP Client ke AI Server
├── database/
│   ├── migrations/                          ← 18 migration files
│   └── seeders/
├── routes/
│   └── api.php                              ← Semua API Routes
└── config/
    ├── cors.php
    ├── services.php                         ← AI_SERVICE_URL
    └── database.php
```

---

## SLIDE 7 — Entity Relationship (Data Model)

### Tabel & Relasi Utama

```
users (1) ─────────── (M) children
  │                          │
  │                          ├── (M) child_growth_records
  │                          ├── (M) stunting_assessments
  │                          └── (M) meal_plans
  │                                      │
  │                                      └── (M) meal_plan_items
  │                                                  │
  │                                                  └── (1) foods
  │
  ├── (M) recommendation_requests
  │              │
  │              └── (1) recommendation_results
  │                           │
  │                           └── (M) recommendation_items
  │                                           │
  │                                           └── (1?) foods
  │
  └── standalone: waitlists
```

### Entitas Utama

| Entitas | Kolom Kunci | Fungsi |
|---------|-------------|--------|
| `users` | id, name, email, role | Akun pengguna & autentikasi |
| `children` | id, user_id, name, gender, birth_date, weight_kg, height_cm, muac_cm | Profil anak |
| `child_growth_records` | id, child_id, recorded_at, weight_kg, height_cm, muac_cm | Riwayat pengukuran |
| `foods` | id, name, category, calories, protein, fat, carbs, price_min, price_max, age_min_months, texture | Katalog makanan + nutrisi |
| `meal_plans` | id, user_id, child_id, plan_date, title | Rencana makan |
| `stunting_assessments` | id, child_id, status_gizi, risk_level, risk_score, summary, recommendations | Hasil analisis AI |
| `recommendation_requests` | id, child_id, payload, status | Request ke AI service |
| `recommendation_results` | id, request_id, summary, budget_min, budget_max, raw_response | Hasil dari AI |

---

## SLIDE 8 — API Endpoints Overview

### Pengelompokan Endpoint

#### Autentikasi (Public)
```
POST /api/auth/register      ← Daftar akun baru
POST /api/auth/login         ← Login & dapat token
POST /api/auth/logout        ← Revoke token (Bearer)
GET  /api/auth/me            ← Profil user saat ini (Bearer)
```

#### Manajemen Anak (Bearer Required)
```
GET    /api/children                           ← Daftar anak milik user
POST   /api/children                           ← Tambah anak baru
GET    /api/children/{id}                      ← Detail anak
PUT    /api/children/{id}                      ← Update data anak
DELETE /api/children/{id}                      ← Hapus anak
GET    /api/children/{id}/growth-records       ← Riwayat pertumbuhan
GET    /api/children/{id}/assessments          ← Riwayat analisis gizi
```

#### Makanan (Public + Admin)
```
GET    /api/foods                  ← Katalog makanan (filter: category, search)
GET    /api/foods/{id}             ← Detail makanan
POST   /api/admin/foods            ← Tambah makanan (Bearer)
PUT    /api/admin/foods/{id}       ← Update makanan (Bearer)
DELETE /api/admin/foods/{id}       ← Hapus makanan (Bearer)
```

#### Analisis AI (Bearer Required)
```
POST /api/analyze              ← Analisis gizi + rekomendasi makanan
GET  /api/analyses             ← Riwayat analisis user (?child_id=X)
```

#### Admin
```
GET  /api/users                     ← Semua user
POST /api/users                     ← Tambah user
PUT  /api/users/{id}                ← Update user
DEL  /api/users/{id}                ← Hapus user
GET  /api/admin/children            ← Semua anak (all users)
GET  /api/admin/users/{id}/children ← Anak milik user tertentu
```

---

## SLIDE 9 — Alur Program: Registrasi & Login

```
┌─────────────────────────────────────────────────────────┐
│               FLOW 1: REGISTRASI & LOGIN                │
└─────────────────────────────────────────────────────────┘

1. Client → POST /api/auth/register
           { name, email, password, password_confirmation }
                        │
                        ▼
2. AuthController::register()
   ✓ Validasi input (email unik, password min 8 char)
   ✓ Hash password dengan bcrypt
   ✓ Simpan ke tabel users
   ✓ Generate Sanctum Token
                        │
                        ▼
3. Response ke Client:
   { "message": "...", "token": "...", "user": { ... } }
   HTTP 201

4. Client menyimpan token di LocalStorage / Secure Storage

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

5. Client → POST /api/auth/login
           { email, password }
                        │
                        ▼
6. AuthController::login()
   ✓ Validasi kredensial
   ✓ Buat token baru via User::createToken()
   ✓ Token disimpan di tabel personal_access_tokens
                        │
                        ▼
7. Response: { "token": "1|abc123...", "user": { ... } }

8. Seluruh request berikutnya:
   Authorization: Bearer 1|abc123...
```

---

## SLIDE 10 — Alur Program: Tambah & Pantau Anak

```
┌──────────────────────────────────────────────────────────┐
│          FLOW 2: TAMBAH ANAK & PANTAU PERTUMBUHAN        │
└──────────────────────────────────────────────────────────┘

POST /api/children (Bearer)
{ name, gender, birth_date, weight_kg, height_cm, muac_cm }
                     │
                     ▼
ChildController::store()
✓ Autentikasi via Sanctum
✓ Validasi input
✓ Auth::user()->children()->create(...)
✓ Return: { status, message, data: Child }
                     │
                     ▼
Anak tersimpan dengan user_id = authenticated user
(cascade delete: jika user dihapus, anak ikut terhapus)

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

GET /api/children/{id}/growth-records (Bearer)
                     │
                     ▼
ChildController::growthRecords()
✓ Verifikasi anak milik user yang login
✓ Query ChildGrowthRecord:
  WHERE child_id = {id}
  ORDER BY recorded_at ASC
  LIMIT 6
✓ Return: data array untuk plot grafik pertumbuhan

Client → Tampilkan grafik tren berat/tinggi/MUAC anak
```

---

## SLIDE 11 — Alur Program: Analisis AI (Core Feature)

```
┌──────────────────────────────────────────────────────────┐
│           FLOW 3: ANALISIS GIZI & STUNTING AI            │
└──────────────────────────────────────────────────────────┘

1. Client → POST /api/analyze (Bearer)
   {
     "child_id": 1,
     "child_age_months": 24,
     "gender": "male",
     "weight_kg": 12.5,
     "height_cm": 87,
     "muac_cm": 14.2,
     "allergies": ["telur"],
     "budget_min": 10000,
     "budget_max": 50000
   }

2. AnalysisController::analyze()
   ✓ Validasi request
   ✓ Query Foods dari DB:
       WHERE age_min_months <= 24
       AND price_min <= 50000
   ✓ Simpan RecommendationRequest (status='pending')

3. AiService::analyze(childData, foods)
   ✓ Format ulang foods → food_candidates array
   ✓ HTTP POST → http://localhost:8000/analyze (timeout 30s)
              │
              ▼
4. AI Server (Python)
   ✓ Hitung Z-score: weight-for-age, height-for-age
   ✓ Tentukan status gizi: normal/underweight/stunted
   ✓ Filter foods: usia, tekstur, alergi, budget
   ✓ Ranking makanan: nilai gizi vs kebutuhan anak
   ✓ Return JSON response

5. Backend (lanjutan)
   ✓ Update RecommendationRequest (status='success')
   ✓ Simpan StuntingAssessment:
       - status_gizi, risk_level, risk_score (0-100)
       - summary, analysis (JSON), recommendations (JSON)
       - warning_flags (JSON)
   ✓ Simpan RecommendationResult + RecommendationItems

6. Response → Client
   {
     "status_gizi": "normal",
     "risk_level": "low",
     "risk_score": 15,
     "summary": "Anak dalam kondisi gizi normal...",
     "food_items": [
       {
         "food_name": "Telur Ayam",
         "reason": "Protein tinggi untuk pertumbuhan",
         "estimated_price": 2500
       }
     ],
     "warning_flags": []
   }
```

---

## SLIDE 12 — Fitur-Fitur Utama

### 9 Fitur Inti NutriGrowth

| No | Fitur | Deskripsi |
|----|-------|-----------|
| 1 | **User Management** | Register/login, token auth, profil pengguna, admin CRUD users |
| 2 | **Child Profile** | CRUD data anak: nama, gender, tanggal lahir, foto |
| 3 | **Growth Monitoring** | Rekam berat/tinggi/MUAC berkala, tampilkan grafik tren pertumbuhan |
| 4 | **Food Database** | Katalog makanan lokal dengan info kalori, protein, lemak, karbohidrat, harga, alergen |
| 5 | **AI Stunting Analysis** | Deteksi risiko stunting otomatis berbasis Z-score dan AI |
| 6 | **Food Recommendations** | Rekomendasi makanan sesuai usia, alergi, dan budget keluarga |
| 7 | **Meal Planning** | Buat rencana makan harian (sarapan, makan siang, makan malam) |
| 8 | **Admin Dashboard** | Kelola semua user, anak, dan katalog makanan |
| 9 | **Waitlist** | Daftarkan email di landing page untuk akses awal |

---

## SLIDE 13 — Keamanan & Autentikasi

### Mekanisme Keamanan

#### Laravel Sanctum (Token-Based Auth)

```
1. User Login
   → User::createToken('api-token')
   → Token disimpan di personal_access_tokens table

2. Protected Request
   → Client: Authorization: Bearer <token>
   → Middleware auth:sanctum memvalidasi token
   → $request->user() tersedia di controller

3. Logout
   → $user->currentAccessToken()->delete()
   → Token ter-revoke, tidak bisa digunakan lagi
```

#### Role-Based Access Control

| Role | Akses |
|------|-------|
| `user` | CRUD anak sendiri, analisis, meal plan, lihat makanan |
| `admin` | Semua akses user + CRUD foods, lihat semua anak/user |

#### CORS Configuration

Hanya origins berikut yang diizinkan:
- `http://localhost:5173` — React/Vite Frontend
- `http://localhost:8000` — WebClient
- `http://localhost:8001` — Admin Dashboard

#### Rate Limiting
- 60 request per menit per user (Laravel default throttle)

---

## SLIDE 14 — Database Schema (Summary)

### 11 Tabel Database

```
┌─────────────────────────────────────────────────────────┐
│  Tabel                  │ Fungsi                        │
├─────────────────────────┼───────────────────────────────┤
│ users                   │ Akun & autentikasi            │
│ children                │ Profil anak                   │
│ foods                   │ Katalog makanan & nutrisi     │
│ child_growth_records    │ Riwayat pengukuran fisik      │
│ meal_plans              │ Rencana makan                 │
│ meal_plan_items         │ Item dalam meal plan          │
│ recommendation_requests │ Request analisis ke AI        │
│ recommendation_results  │ Hasil analisis dari AI        │
│ recommendation_items    │ Makanan yang direkomendasikan │
│ stunting_assessments    │ Penilaian status gizi & risiko│
│ waitlists               │ Pendaftaran landing page      │
│ personal_access_tokens  │ Sanctum auth tokens           │
└─────────────────────────────────────────────────────────┘
```

### Field Kunci StuntingAssessment
```
status_gizi      → "normal" | "underweight" | "stunted"
risk_level       → "low" | "medium" | "high"
risk_score       → 0 - 100
analysis         → JSON: detail analisis per parameter
recommendations  → JSON: array saran tindakan
warning_flags    → JSON: ["anemia", "gizi buruk", ...]
```

---

## SLIDE 15 — Integrasi AI Service

### Cara Backend Berkomunikasi dengan AI

```php
// app/Services/AiService.php

class AiService {
    public function analyze(array $childData, array $foods): array
    {
        $payload = array_merge($childData, [
            'food_candidates' => $this->mapFoodsToAiFormat($foods),
        ]);

        $response = Http::timeout(30)
            ->post("{$this->baseUrl}/analyze", $payload);

        if ($response->failed()) {
            throw new RuntimeException("AI server error");
        }

        return $response->json();
    }
}
```

### Format Input ke AI Server

```json
{
  "child_age_months": 24,
  "gender": "male",
  "weight_kg": 12.5,
  "height_cm": 87,
  "muac_cm": 14.2,
  "allergies": ["telur"],
  "budget_min": 10000,
  "budget_max": 50000,
  "food_candidates": [
    {
      "id": "5",
      "name": "Telur Ayam",
      "category": "protein",
      "calories_per_100g": 155,
      "protein_g": 13.0,
      "age_min_months": 8,
      "texture": "lembut",
      "price_min": 2000,
      "price_max": 3000,
      "allergens": "telur"
    }
  ]
}
```

---

## SLIDE 16 — Deployment & Konfigurasi

### Environment Variables (.env)

```env
APP_NAME=NutriGrowth
APP_ENV=production
APP_KEY=base64:...
APP_URL=https://api.nutrigrowth.id

DB_CONNECTION=pgsql
DATABASE_URL=postgresql://postgres.<ref>:<pass>@aws-1-ap-southeast-2.pooler.supabase.com:6543/postgres

SANCTUM_STATEFUL_DOMAINS=localhost:5173,localhost:8001

AI_SERVICE_URL=http://ai-service:8000
```

### Mode Koneksi Database

| Mode | Host | Port | Use Case |
|------|------|------|----------|
| Direct | `db.<ref>.supabase.co` | 5432 | Development lokal |
| Pooler | `aws-1-ap-southeast-2.pooler.supabase.com` | 6543 | Production / Serverless / PHP |

### Quick Start (Development)

```bash
composer install
cp .env.example .env
php artisan key:generate
# Edit .env: set DATABASE_URL dan AI_SERVICE_URL
php artisan migrate
php artisan serve --port=8080
```

---

## SLIDE 17 — Diagram Alur Data End-to-End

```
Orang Tua                     NutriGrowth App              Backend API           AI Service
    │                               │                           │                     │
    │── Buka App ──────────────────>│                           │                     │
    │                               │── GET /api/children ─────>│                     │
    │                               │<─ List anak ─────────────│                     │
    │                               │                           │                     │
    │── Pilih anak & klik ─────────>│                           │                     │
    │   "Analisis Gizi"             │── POST /api/analyze ─────>│                     │
    │                               │                           │── Query Foods ──────│
    │                               │                           │   (dari Supabase)   │
    │                               │                           │                     │
    │                               │                           │── HTTP POST ────────>│
    │                               │                           │   /analyze          │── Hitung Z-score
    │                               │                           │                     │── Tentukan status
    │                               │                           │                     │── Filter makanan
    │                               │                           │<─ AI Response ───────│
    │                               │                           │                     │
    │                               │                           │── Simpan assessment │
    │                               │                           │── Simpan rekomendasi│
    │                               │<─ Response JSON ──────────│                     │
    │                               │                           │                     │
    │<── Tampilkan hasil ───────────│                           │                     │
    │    - Status gizi: Normal      │                           │                     │
    │    - Risk score: 15           │                           │                     │
    │    - Rekomendasi 5 makanan    │                           │                     │
    │    - Saran tindakan           │                           │                     │
```

---

## SLIDE 18 — Penutup & Roadmap

### Apa yang Sudah Selesai

- [x] REST API Backend dengan Laravel 10
- [x] Autentikasi token dengan Laravel Sanctum
- [x] CRUD profil anak & riwayat pertumbuhan
- [x] Katalog makanan dengan info nutrisi lengkap
- [x] Integrasi AI untuk analisis stunting
- [x] Meal planning per anak
- [x] Admin dashboard API
- [x] Database Supabase (PostgreSQL)
- [x] 11 tabel dengan relasi lengkap

### Potensi Pengembangan

- [ ] Push notification untuk pengingat pengukuran berkala
- [ ] Export laporan PDF per anak
- [ ] Integrasi dengan data WHO growth standard secara realtime
- [ ] Analisis perbandingan antar anak dalam keluarga
- [ ] Konsultasi online dengan tenaga gizi (chat/video)
- [ ] Fitur scan barcode produk makanan

---

### Tim & Kontak

**Project:** NutriGrowth Backend  
**Stack:** Laravel 10 · Supabase · Laravel Sanctum · AI Service Integration  
**Repository:** NutriGrowth-Backend  

---

*Dibuat untuk presentasi proyek NutriGrowth — Platform Pemantauan Gizi & Pertumbuhan Anak Berbasis AI*
