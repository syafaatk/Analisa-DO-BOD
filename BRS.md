# BRS — Business Requirements Specification
## Laboratory Information System — Analisa DO & BOD5

**Repository:** syafaatk/Analisa-DO-BOD  
**Branch:** feature/laravel-do-bod  
**Dokumen:** BRS.md  
**Versi:** 1.0  
**Tanggal baseline:** 2026-09-21  
**Status:** Living Document  
**Platform:** Laravel + React + Vite + MySQL

> **Aturan pemeliharaan:** Dokumen ini merupakan *living BRS*. Setiap perubahan requirement, workflow, menu, formula, validasi bisnis, role, integrasi, struktur data yang berdampak pada perilaku pengguna, atau perubahan besar pada modul harus disertai pembaruan BRS.md pada perubahan kode yang sama.

---

# 1. Tujuan Dokumen

BRS ini mendefinisikan kebutuhan bisnis dan perilaku utama Laboratory Information System (LIS) untuk pengelolaan analisis Dissolved Oxygen (DO), Biochemical Oxygen Demand 5 days (BOD5), Quality Control (QC), ketidakpastian pengukuran top-down, review/approval, audit trail, reporting, client portal, dan multi-laboratorium.

Dokumen menjadi acuan:
- pengembangan aplikasi;
- validasi kebutuhan pengguna;
- penyusunan test case/UAT;
- review perubahan requirement;
- traceability antara kebutuhan, implementasi, dan hasil pengujian;
- dokumentasi operasional laboratorium.

# 2. Latar Belakang

Laboratorium membutuhkan sistem terintegrasi untuk memastikan proses analisis dari input data sampai laporan dapat ditelusuri.

Sistem harus menjaga:
1. integritas data analisis;
2. konsistensi perhitungan;
3. penerapan aturan QC;
4. pemisahan data antar laboratorium;
5. pemisahan hak akses berdasarkan role;
6. traceability perubahan;
7. proses review dan approval;
8. akses laporan bagi client;
9. penyimpanan input mentah dan hasil kalkulasi.

# 3. Tujuan Bisnis

| ID | Tujuan |
|---|---|
| BO-01 | Menyediakan pencatatan analisis DO secara terstruktur. |
| BO-02 | Menyediakan pencatatan dan perhitungan BOD5. |
| BO-03 | Menyediakan evaluasi QC analisis. |
| BO-04 | Menyediakan perhitungan ketidakpastian pengukuran top-down. |
| BO-05 | Menjamin setiap hasil dapat ditelusuri dari input hingga laporan. |
| BO-06 | Menyediakan workflow review dan approval. |
| BO-07 | Menyediakan audit trail perubahan dan keputusan. |
| BO-08 | Memisahkan data berdasarkan laboratorium. |
| BO-09 | Menyediakan portal client untuk laporan yang telah disetujui. |
| BO-10 | Menghasilkan laporan yang dapat dicetak/disimpan sebagai PDF. |

# 4. Ruang Lingkup

## 4.1 In Scope
- Authentication laboratorium.
- Role-based access control.
- Multi-laboratorium.
- Laboratory context.
- Master sample.
- Master reagent.
- Master instrument.
- Master client/perusahaan.
- User & role management.
- Analisis DO.
- Analisis BOD5.
- Dilution BOD.
- QC BOD.
- GGA control.
- RPD/duplo DO.
- Top-down uncertainty.
- Analysis record.
- Edit dan recalculation.
- Submit for review.
- Review/approval.
- Audit trail.
- Report.
- Client portal.
- Client report access logging.
- Notification laporan approved.
- Browser print / Save as PDF.
- UUID primary key.

## 4.2 Out of Scope Saat Ini
- Instrument integration/LIMS analyzer interface.
- Automatic import hasil alat laboratorium.
- Server-side PDF engine.
- Electronic signature tersertifikasi.
- Payment/billing.
- Inventory management lengkap.
- Purchasing/procurement.
- External regulatory submission.
- Mobile native application.

# 5. Stakeholder dan Aktor

| Aktor | Deskripsi |
|---|---|
| Super Admin | Mengelola platform dan laboratorium serta context switching. |
| Admin | Mengelola user, client, dan fungsi administratif laboratorium. |
| Supervisor | Melakukan review/approval hasil analisis. |
| Analyst | Menginput, menghitung, mengedit, dan submit analisis. |
| Viewer | Akses baca sesuai kewenangan. |
| Client Admin | Mengakses laporan approved milik perusahaannya. |
| Client User | Pengguna portal client sesuai akses yang diberikan. |

# 6. Matriks Hak Akses

| Fitur | Super Admin | Admin | Supervisor | Analyst | Viewer | Client |
|---|---:|---:|---:|---:|---:|---:|
| Context laboratorium | ✓ | - | - | - | - | - |
| Analisis | ✓ | ✓ | ✓ | ✓ | Read | - |
| Edit analisis | ✓ | ✓ | ✓* | ✓ | - | - |
| Submit review | ✓ | ✓ | - | ✓ | - | - |
| Review | ✓ | ✓ | ✓ | - | - | - |
| Approve | ✓ | ✓ | ✓ | - | - | - |
| Master sample | ✓ | ✓ | ✓ | sesuai role | Read | - |
| Master reagent | ✓ | ✓ | ✓ | sesuai role | Read | - |
| Master instrument | ✓ | ✓ | ✓ | sesuai role | Read | - |
| User management | ✓ | ✓ | - | - | - | - |
| Client management | ✓ | ✓ | - | - | - | - |
| Laboratory management | ✓ | - | - | - | - | - |
| Client report | - | - | - | - | - | ✓ |

`*` bergantung pada aturan workflow dan implementasi role.

# 7. Arsitektur Bisnis

## 7.1 Konsep Tenant
Aplikasi menggunakan shared database dengan laboratory_id.
Data tenant utama harus terikat pada laboratorium:
- AnalysisRun
- Sample
- Reagent
- Instrument
- UncertaintyModel
- UncertaintyComponent
- BodDilution
- BodControl
- AnalysisAudit
- AnalysisReview
- LabUser
- Client
- ClientUser
- ClientReportAccess

Akses data harus mengikuti laboratory context aktif.

## 7.2 Identitas Data
Primary key aplikasi menggunakan UUID.
Tujuannya:
- menghindari ketergantungan ID sequential;
- menjaga konsistensi relasi;
- mendukung multi-tenant;
- mengurangi risiko collision ketika data dipindahkan antar environment.

# 8. Modul Authentication
User laboratorium dapat login, logout, menggunakan session laboratory, mendapatkan role, dan mengakses modul sesuai role.
Route utama: GET /login, POST /login, POST /logout.

# 9. Modul Laboratory Management
**Aktor:** Super Admin.
Fungsi: melihat laboratorium, membuat laboratorium, membuat admin laboratorium, mengubah informasi, mengaktifkan/nonaktifkan, dan context switching.
Data minimum: kode, nama, alamat, telepon, email, status aktif, admin laboratorium.

# 10. Modul User Management
**Aktor:** Admin.
Fungsi: melihat user, membuat user, menetapkan role, mengubah user, menonaktifkan/menghapus user sesuai aturan.
Role: admin, supervisor, analyst, viewer. Super Admin adalah role platform.

# 11. Modul Client / Perusahaan
**Aktor:** Admin.
Fungsi: membuat perusahaan/client, membuat user portal, menyimpan kode, nama, alamat, telepon, email, status aktif.
Kode client harus unik dalam laboratorium terkait.

# 12. Modul Analisis DO
**Metode:** SNI 06-6989.14-2004.
Input: client_id, sample_code, thiosulfate_ml, thiosulfate_duplo_ml, normality, winkler_volume_ml, reagent_mnso4_ml, reagent_alkali_ml, aliquot_ml.

Formula:
DO (mg/L) = V × N × 8000 × F / Va
F = Vbotol / (Vbotol − VMnSO4 − Valkali)

Validasi: volume titran > 0; normalitas > 0; volume Winkler > 2 mL; aliquot > 0; denominator correction factor > 0; client bila dipilih harus aktif.

Jika duplo tersedia: RPD = |x1 - x2| / ((x1 + x2)/2) × 100.
Implementasi saat ini: RPD <= 10% PASS; RPD > 10% REVIEW.

# 13. Modul Analisis BOD5
**Metode:** SNI 6989.72:2009.
Input utama: client_id, sample_code, A1, A2, B1, B2, VB, Vc, P, sample_ph, sample_temperature, sampling_at, bod_incubation_start, bod_incubation_end, storage_temperature, storage_hours, interference_treatment, gga_bod.

Formula: BOD5 = ((A1-A2) - ((B1-B2)/VB) × Vc) / P.
Jika VB = 0, koreksi seed dianggap 0.
Validasi: P > 0; VB >= 0; Vc >= 0; pH 0–14 jika diisi; data dilution mengikuti validasi field; client harus aktif jika dipilih.

# 14. QC BOD5
Sample depletion: A1 - A2 >= 2 mg/L → PASS.
Blank depletion: BLANK_MAX = 0.4 mg/L; PASS jika 0 <= B1-B2 < 0.4.
Residual DO dilution: DO akhir >= 1 mg/L.
Depletion dilution: DO awal - DO akhir >= 2 mg/L.
Inkubasi saat ini: suhu 19–21 °C dan waktu 114–126 jam.
GGA saat ini: target 198 mg/L, tolerance ±30.5 mg/L, range 167.5–228.5 mg/L.
Storage saat ini: <=24 jam PASS, >24 jam FAIL, tidak dicatat NOT_RECORDED.

> Nilai QC merupakan aturan implementasi saat ini. Jika SOP laboratorium atau interpretasi standar berubah, requirement dan BRS harus diperbarui bersama kode dan test case.

# 15. Dilution BOD
Setiap dilution dapat menyimpan sample volume, final volume, DO initial, DO final, incubation temperature, incubation hours, timestamp dilution/DO, dilution factor, depletion, dan selection status.
Dilution dievaluasi untuk menentukan apakah memenuhi kriteria QC.

# 16. Top-Down Measurement Uncertainty
Input: precision SD, precision N, bias SD, bias N, coverage factor.
Formula: u_precision = precision SD; u_bias = bias SD / sqrt(bias N); u_c = sqrt(u_precision² + u_bias²); U = k × u_c.
Jika data bias tidak diberikan lengkap, kontribusi bias dihitung 0 oleh implementasi saat ini. Sistem tidak boleh mengarang nilai precision/bias.

# 17. Analysis Record
Setiap analisis menyimpan UUID, laboratory_id, client_id, sample_code, parameter, method_version, analyst, analysed_at, raw inputs, calculation result, status, workflow timestamps, dan approval information.
Input mentah dan hasil kalkulasi harus dipertahankan untuk traceability.

# 18. Workflow Analisis
Status: DRAFT → READY_FOR_REVIEW → APPROVED.
Alternatif dari READY_FOR_REVIEW: APPROVED, REVISION_REQUIRED, atau REJECTED.

Rules:
- DRAFT: Analyst/Admin dapat mengedit sesuai permission.
- READY_FOR_REVIEW: analisis telah dikirim untuk pemeriksaan reviewer.
- APPROVED: hasil disetujui dan tidak boleh diedit melalui workflow edit biasa.
- REVISION_REQUIRED: reviewer meminta perubahan.
- REJECTED: reviewer menolak hasil.

# 19. Audit Trail
Audit mencatat submit, review, approval, revision, rejection, edit, delete, dan perubahan status.
Audit minimal: analysis_run_id, actor, action, from_status, to_status, notes/data perubahan, timestamp.

# 20. Review dan Approval
Aktor: Supervisor, Admin, dan Super Admin sesuai permission.
Decision: APPROVED, REVISION_REQUIRED, REJECTED.
Reviewer dapat memberikan komentar.
Jika APPROVED: status menjadi APPROVED, approved_by diisi, approved_at diisi, audit dicatat, dan notifikasi laporan dapat dikirim.

# 21. Reporting
Report menampilkan nomor laporan, sample, parameter, metode, analyst, tanggal analisis, status, hasil, QC, dilution BOD, control, uncertainty, dan approval.
Format saat ini menggunakan browser Print → Save as PDF. Server-side PDF engine bukan requirement saat ini.

# 22. Client Portal
Authentication melalui /client/login.
Client dapat login, melihat daftar report approved, membuka detail, dan mencetak/simpan PDF.
Security rule: analysis.client_id == authenticated_client.client_id AND analysis.status == APPROVED.
Setiap akses report dicatat melalui ClientReportAccess, termasuk VIEW dan PRINT serta metadata client user, IP, user agent, timestamp.

# 23. Master Data
Sample: sample code, sample name, matrix, received_at, customer, notes.
Reagent: code, name, lot number, concentration, unit, expiry date.
Instrument: code, name, serial number, calibration due, status.

# 24. Notification
Saat laporan menjadi APPROVED, sistem memiliki mekanisme notifikasi laporan approved. Informasi dapat mencakup client, nomor laporan, sample, parameter, dan link laporan.
Email saat ini menggunakan mekanisme aplikasi dan dapat dialihkan ke queue untuk production.

# 25. Requirement Fungsional
| ID | Requirement | Status |
|---|---|---|
| FR-001 | User dapat login laboratorium | Implemented |
| FR-002 | User dapat logout | Implemented |
| FR-003 | Sistem menerapkan RBAC | Implemented |
| FR-004 | Sistem mendukung multi-laboratorium | Implemented |
| FR-005 | Super Admin dapat mengelola laboratorium | Implemented |
| FR-006 | Admin dapat mengelola user | Implemented |
| FR-007 | Admin dapat mengelola client | Implemented |
| FR-008 | Sistem menghitung DO | Implemented |
| FR-009 | Sistem mendukung DO duplo/RPD | Implemented |
| FR-010 | Sistem menghitung BOD5 | Implemented |
| FR-011 | Sistem mencatat dilution BOD | Implemented |
| FR-012 | Sistem mengevaluasi QC BOD | Implemented |
| FR-013 | Sistem mengevaluasi GGA | Implemented |
| FR-014 | Sistem menghitung top-down uncertainty | Implemented |
| FR-015 | Sistem menyimpan raw input dan calculation | Implemented |
| FR-016 | Analyst dapat submit review | Implemented |
| FR-017 | Reviewer dapat approve/reject/revision | Implemented |
| FR-018 | Sistem menyediakan audit trail | Implemented |
| FR-019 | Sistem menyediakan report | Implemented |
| FR-020 | Client dapat melihat report approved | Implemented |
| FR-021 | Client report access diaudit | Implemented |
| FR-022 | Report dapat dicetak/disimpan PDF | Implemented |
| FR-023 | UUID digunakan sebagai primary key | Implemented |
| FR-024 | Data tenant diisolasi berdasarkan laboratory context | Implemented |
| FR-025 | Approved report memicu notifikasi | Implemented |

# 26. Requirement Non-Fungsional
**NFR-001 Security:** password di-hash, CSRF protection, role middleware, client ownership check, tenant context.
**NFR-002 Traceability:** input, calculation, workflow, approval, dan audit dapat ditelusuri.
**NFR-003 Data Integrity:** relasi menggunakan UUID dan foreign key.
**NFR-004 Usability:** frontend responsive desktop/mobile.
**NFR-005 Maintainability:** business calculation dipisahkan ke service.
**NFR-006 Deployment:** mendukung Docker dan Railway.

# 27. Use Case Utama
## UC-01 Login Laboratorium
Actor: User Laboratorium. Login → validasi credential → session → analysis.

## UC-02 Membuat Analisis DO
Actor: Analyst. Pilih client → input sample/data titrasi → validasi → hitung DO → hitung RPD jika ada duplo → simpan DRAFT.

## UC-03 Membuat Analisis BOD5
Actor: Analyst. Input sample dan A1/A2/B1/B2/VB/Vc/P → input QC/dilution/GGA → hitung → evaluasi QC → simpan.

## UC-04 Submit Review
Actor: Analyst/Admin. Buka detail → validasi kelengkapan → submit → READY_FOR_REVIEW → audit.

## UC-05 Review
Actor: Supervisor/Admin. Buka READY_FOR_REVIEW → periksa calculation/QC → pilih decision → simpan → audit.

## UC-06 Client Melihat Report
Actor: Client. Login → daftar approved report → pilih report → ownership check → tampilkan → catat akses.

# 28. Business Rules
| ID | Rule |
|---|---|
| BR-001 | Client inactive tidak boleh digunakan untuk analisis baru. |
| BR-002 | Analysis harus memiliki sample code. |
| BR-003 | DO titrant volume harus > 0. |
| BR-004 | DO normality harus > 0. |
| BR-005 | DO denominator correction factor harus > 0. |
| BR-006 | BOD P harus > 0. |
| BR-007 | VB dan Vc tidak boleh negatif. |
| BR-008 | VB=0 berarti seed correction = 0. |
| BR-009 | DO duplo menghasilkan RPD bila diisi. |
| BR-010 | Analysis approved tidak boleh diedit melalui edit biasa. |
| BR-011 | Hanya role berwenang yang dapat submit/review. |
| BR-012 | Client hanya melihat report APPROVED miliknya. |
| BR-013 | Setiap perubahan workflow harus diaudit. |
| BR-014 | Tenant data harus mengikuti laboratory context. |
| BR-015 | UUID menjadi primary key aplikasi. |

# 29. Traceability
Alur traceability: BRS Requirement → Route/Controller → Service/Model → Database → UI → Test/UAT.

| Requirement | Implementasi |
|---|---|
| FR-008 DO | AnalysisController + LaboratoryCalculationService |
| FR-010 BOD5 | AnalysisController + LaboratoryCalculationService |
| FR-012 QC BOD | BodEvaluationService |
| FR-016 Submit | AnalysisRecordController + AnalysisWorkflowService |
| FR-017 Review | ReviewController + ApprovalService |
| FR-018 Audit | AnalysisAuditService |
| FR-020 Client report | ClientPortalController |
| FR-025 Notification | ApprovalService + ReportNotificationService |

# 30. UAT Acceptance Criteria
## DO
- [ ] User dapat memasukkan data DO.
- [ ] Sistem menolak titran <= 0.
- [ ] Sistem menolak normality <= 0.
- [ ] Sistem menghitung DO sesuai formula.
- [ ] Duplo menghasilkan RPD.
- [ ] Hasil tersimpan pada AnalysisRun.
- [ ] Raw input tersimpan.

## BOD5
- [ ] User dapat memasukkan A1/A2/B1/B2.
- [ ] User dapat memasukkan VB/Vc/P.
- [ ] VB=0 diproses sebagai tanpa seed correction.
- [ ] Sistem menghitung BOD5.
- [ ] Dilution dapat ditambahkan.
- [ ] QC dilution dihitung.
- [ ] GGA dapat dievaluasi.
- [ ] Storage status dapat dievaluasi.

## Workflow
- [ ] DRAFT dapat diedit oleh role yang berwenang.
- [ ] Analyst dapat submit.
- [ ] Status menjadi READY_FOR_REVIEW.
- [ ] Supervisor dapat review.
- [ ] APPROVED mengisi approval metadata.
- [ ] REVISION_REQUIRED dapat dikembalikan ke proses revisi.
- [ ] REJECTED tercatat.
- [ ] Audit trail tersedia.

## Client Portal
- [ ] Client dapat login.
- [ ] Client hanya melihat report approved.
- [ ] Client tidak dapat melihat report milik client lain.
- [ ] VIEW tercatat.
- [ ] PRINT tercatat.

# 31. Risiko dan Kontrol
| Risiko | Kontrol |
|---|---|
| Data antar lab tercampur | laboratory_id + tenant scope/context |
| User tanpa hak melakukan approval | role middleware |
| Hasil berubah setelah approval | Approved record tidak dapat diedit normal |
| Perubahan tidak terlacak | Audit trail |
| Client melihat report client lain | Ownership check |
| Calculation tidak dapat ditelusuri | Raw input + calculation JSON |
| UUID hilang pada seeder | Seeder UUID review + HasUuids |
| Konfigurasi production salah | Environment variables |
| Email notification gagal | Notification service; queue dapat digunakan |

# 32. Perubahan dan Versioning
Dokumen ini harus diperbarui apabila terjadi:
1. penambahan modul;
2. perubahan field input;
3. perubahan formula;
4. perubahan QC threshold;
5. perubahan workflow;
6. perubahan role/permission;
7. perubahan tenant isolation;
8. perubahan report;
9. perubahan client portal;
10. perubahan integration;
11. perubahan acceptance criteria;
12. perubahan requirement yang memengaruhi UAT.

## Changelog
### v1.0 — 2026-09-21
Baseline BRS dibuat berdasarkan implementasi branch feature/laravel-do-bod.

# 33. Catatan Pengembangan
Setiap perubahan kode yang memengaruhi requirement harus mengikuti pola:
1. Update code.
2. Update BRS.md.
3. Update test/UAT bila relevan.
4. Commit dalam perubahan yang sama atau commit dokumentasi terkait.

**BRS.md adalah sumber dokumentasi requirement bisnis yang harus tetap sinkron dengan implementasi.**