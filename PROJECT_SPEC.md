# Spesifikasi Aplikasi Analisa DO & BOD

## Tujuan
Aplikasi web Laravel + MySQL untuk membantu pencatatan, perhitungan, evaluasi, dan pelaporan:
1. DO metode titrasi/yodometri modifikasi azida mengacu SNI 06-6989.14-2004.
2. BOD5 mengacu SNI 6989.72:2009.
3. Estimasi ketidakpastian pengukuran DO dan BOD secara top-down.

## Status repository saat mulai
Repository sebelumnya hanya berisi README.md (initial commit), sehingga aplikasi dimulai dari struktur Laravel baru.

## Modul
- Dashboard laboratorium
- Master metode
- Master parameter/peralatan/bahan
- Analisis DO
- Analisis BOD5
- Precision/validation data
- Uncertainty budget DO
- Uncertainty budget BOD
- Laporan hasil
- Audit trail

## DO
Prinsip: oksigen terlarut mengoksidasi mangan(II) dalam suasana basa; setelah pengasaman, iodin yang terbentuk dititrasi dengan Na2S2O3 menggunakan indikator amilum.

Input minimal:
- nomor contoh
- tanggal analisis
- volume botol Winkler
- volume reagen yang relevan
- volume titran Na2S2O3
- normalitas titran
- faktor titran
- blank/koreksi sesuai SOP laboratorium
- pengulangan

Output:
- DO mg O2/L
- mean, SD, RSD/CV
- status QC
- uncertainty standard, expanded uncertainty, coverage factor

## BOD5
Input mengikuti lembar kerja SNI:
- kode contoh
- volume contoh uji
- volume air pengencer
- volume suspensi mikroba
- DO hari 0 (A1)
- DO hari 5 (A2)
- DO blanko hari 0 (B1)
- DO blanko hari 5 (B2)
- volume mikroba Vc
- volume blanko/suspensi bila digunakan Vb/Vs sesuai model laboratorium
- kebutuhan faktor pengenceran dan kontrol

Perhitungan inti diimplementasikan sebagai formula terkonfigurasi, bukan hard-code di Blade, agar dapat diaudit dan diuji.

## Top-down uncertainty
Pendekatan utama:
- repeatability/internal precision dari data QC atau validasi
- intermediate precision bila data tersedia
- bias/CRM atau recovery/control sample bila tersedia
- komponen dari precision dan bias digabungkan sesuai model yang terdokumentasi
- standard uncertainty
- combined standard uncertainty
- expanded uncertainty (default k=2, configurable)
- kontribusi tiap sumber ditampilkan dalam budget dan grafik Pareto

Aplikasi wajib menyimpan:
- data mentah
- data statistik
- asumsi distribusi
- degrees of freedom bila relevan
- sensitivity/contribution
- versi metode
- operator dan timestamp

## Arsitektur
- Laravel
- MySQL
- Blade + Bootstrap/Tailwind
- Service layer untuk kalkulasi
- Form Request validation
- Policies/roles
- PHPUnit feature/unit tests
- migration + seeders
- export PDF/Excel

## Struktur domain yang disarankan
tests/sampling_batches
analysis_runs
do_results
bod_results
do_replicates
bod_dilutions
qc_results
uncertainty_models
uncertainty_components
uncertainty_observations
instruments
reagents
method_versions
audit_logs

## Prinsip implementasi
- Jangan menyimpan hanya hasil akhir; seluruh input dan formula harus dapat ditelusuri.
- Rumus SNI dipisahkan dari presentasi.
- Perubahan faktor/metode harus terversi.
- Tampilkan peringatan jika input berada di luar kondisi metode.
- SNI tidak disalin verbatim sebagai konten berhak cipta; aplikasi hanya menyebut metode, parameter, dan formula yang diperlukan untuk implementasi serta SOP internal laboratorium.
