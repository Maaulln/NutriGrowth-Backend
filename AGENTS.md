# AGENTS.md

## Konteks Project

- Nama project: NutriGrowth Backend (Laravel)
- Path project: /Users/maaullntech/Documents/COde/nutrigrowth-backend

## Path Ekosistem NutriGrowth

- Aplikasi mobile Flutter: /Users/maaullntech/Documents/COde/NutriGrowth-app
- Backend Laravel (repo ini): /Users/maaullntech/Documents/COde/nutrigrowth-backend
- Frontend Web: /Users/maaullntech/Documents/COde/nutrigrowth-web
- AI model API (referensi integrasi): /Users/maaullntech/Documents/COde/nutrigrowth-ai

## Tanggung Jawab Repo Ini

- Menyediakan API bisnis aplikasi (auth, users, children, dan resource terkait aplikasi).
- Menjadi sumber data utama untuk entitas aplikasi berbasis database.
- Menjaga konsistensi schema, validasi request, dan response contract.

## Aturan Integrasi

- Jangan memindahkan logic model AI ke Laravel.
- Laravel fokus pada auth, user management, child management, dan orkestrasi data aplikasi.
- Gunakan API resource/response yang konsisten agar klien Flutter/Web mudah parsing.
- Pastikan route sensitif dilindungi auth (Sanctum/JWT sesuai implementasi).

## Aturan Coding

- Gunakan bahasa Indonesia untuk komentar dan dokumentasi.
- Setiap fungsi/metode harus memiliki docstring/JSDoc atau PHPDoc yang jelas.
- Gunakan pola kode sederhana, terbaca, dan mudah diuji.
- Semua pemanggilan API eksternal wajib error handling.
- Jangan hardcode secret atau URL sensitif; gunakan environment variable.

## Catatan Database

- Jika data aplikasi tidak sinkron, cek berurutan:
    1. `.env` koneksi database aktif,
    2. migration schema,
    3. query/controller/transform response,
    4. data sebenarnya di database.
