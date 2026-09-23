# Analisa DO & BOD — Laravel + React + MySQL

Laboratory Information System untuk analisis DO, BOD5, QC, ketidakpastian pengukuran top-down, audit trail, review/approval, dan multi-laboratorium.

## Cakupan

- DO titrasi Winkler azide — SNI 06-6989.14-2004
- BOD5 — SNI 6989.72:2009
- Top-down measurement uncertainty
- QC BOD: blank, depletion, residual DO, inkubasi, GGA, RPD
- Traceability input → calculation → QC → approval → report
- Workflow DRAFT → READY_FOR_REVIEW → APPROVED / REVISION_REQUIRED / REJECTED
- Audit trail setiap perubahan workflow dan data
- UUID untuk primary key aplikasi
- Shared database multi-tenant dengan `laboratory_id`
- Super Admin dengan context switching antar laboratorium
- RBAC: super_admin, admin, supervisor, analyst, viewer
- Frontend React + Vite, mobile responsive
- Report print A4 dan browser “Save as PDF”

## Formula

DO (mg/L) = V × N × 8000 × F / Va

F = Vbotol / (Vbotol − VMnSO4 − Valkali)

BOD5 = ((A1 − A2) − ((B1 − B2) / VB) × Vc) / P

Jika sampel tidak menggunakan bibit mikroba, VB = 0 dan koreksi bibit dianggap 0.

## Top-down uncertainty

u_c = sqrt(u_precision² + u_bias²)

U = k × u_c

Aplikasi hanya menghitung berdasarkan data precision/bias yang diberikan dan tidak mengarang nilai uncertainty.

## Instalasi

1. `composer install`
2. `cp .env.example .env`
3. Konfigurasikan `DB_*` untuk MySQL.
4. `php artisan key:generate`
5. `php artisan migrate --seed`
6. `npm install`
7. `npm run build`
8. `php artisan serve`

Untuk development frontend: `npm run dev`.

## Konfigurasi multi-laboratorium

Gunakan variabel pada `.env`:

- `LAB_NAME`
- `LAB_ADMIN_NAME`
- `LAB_ADMIN_EMAIL`
- `LAB_ADMIN_PASSWORD`
- `LAB_SUPERADMIN_NAME`
- `LAB_SUPERADMIN_EMAIL`
- `LAB_SUPERADMIN_PASSWORD`

Password jangan disimpan di repository. Seeder hanya membuat Super Admin jika password Super Admin di environment diisi.

## Alur operasional

1. Login.
2. Pilih laboratorium jika menggunakan Super Admin.
3. Input DO atau BOD5.
4. Sistem menghitung hasil dan menyimpan input mentah.
5. Untuk BOD5, masukkan dilution/QC/GGA.
6. Analyst submit ke review.
7. Supervisor/Admin melakukan review.
8. Hasil approved dapat dicetak melalui report A4 dan disimpan sebagai PDF dari dialog print browser.
9. Semua perubahan tercatat pada audit trail.

## Catatan deployment

Build frontend harus dijalankan sebelum deployment production:

```bash
npm install
npm run build
php artisan optimize
```

Jalankan migrasi pada database deployment:

```bash
php artisan migrate --force
```

Repository ini menggunakan session-based laboratory context untuk memastikan akses data terisolasi per laboratorium. Legacy API routes juga diproteksi dengan middleware laboratory context.

## Status

Core laboratory workflow, React frontend, responsive UI, UUID, multi-tenancy, RBAC, audit trail, QC BOD, review/approval, dan printable report sudah diimplementasikan pada branch `feature/laravel-do-bod`.

