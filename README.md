# Analisa DO & BOD — Laravel + MySQL

Aplikasi laboratorium untuk DO titrasi/yodometri modifikasi azida (SNI 06-6989.14-2004), BOD5 (SNI 6989.72:2009), dan estimasi ketidakpastian top-down.

## Formula
DO (mg/L) = V × N × 8000 × F / Va
F = Vbotol / (Vbotol − VMnSO4 − Valkali)

BOD5 = ((A1 − A2) − ((B1 − B2) / VB) × Vc) / P

Jika sampel tidak menggunakan bibit mikroba, VB = 0 dan koreksi bibit dianggap 0.

## Top-down uncertainty
Model awal:
u_c = sqrt(u_precision² + u_bias²)
U = k × u_c

u_precision berasal dari data precision laboratorium. u_bias dapat berasal dari estimasi bias/recovery/CRM sesuai model validasi yang ditetapkan laboratorium. Aplikasi tidak boleh mengarang data uncertainty.

## Quality control
DO: duplo dan RPD target 10%.
BOD5: air pengencer, blanko, kontrol glukosa-asam glutamat, dan RPD harus direkam.
Semua hasil final harus traceable ke input mentah dan versi metode.

## Instalasi
1. composer install
2. cp .env.example .env
3. konfigurasi DB_* untuk MySQL
4. php artisan key:generate
5. php artisan migrate
6. php artisan serve

Repository ini dimulai dari initial commit yang hanya berisi README, sehingga struktur Laravel dibuat dari awal.