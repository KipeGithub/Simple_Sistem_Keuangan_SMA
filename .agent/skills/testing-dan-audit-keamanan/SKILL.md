---
name: testing-dan-audit-keamanan
description: Checklist pengujian fungsional akuntansi, integritas jurnal double-entry, audit keamanan web, dan prosedur dokumentasi milestone di docs/plan/.
---

# CHECKLIST PENGUJIAN FUNGSIONAL & AUDIT KEAMANAN KEUANGAN SEKOLAH

Dokumen ini menjadi standar verifikasi mutu dan audit keamanan sebelum setiap milestone kode diselesaikan.

---

## 1. PENGUJIAN FUNGSIONAL & INTEGRITAS AKUNTANSI
1. **Validasi Skema & Migrasi Database:**
   - Seluruh kolom finansial bertipe `DECIMAL(15,2)`.
   - Foreign key terindeks dengan benar dengan constraint referensial (`cascadeOnDelete` atau `restrictOnDelete` untuk data audit penting).
2. **Integritas Jurnal Berpasangan (Double-Entry Test):**
   - Setiap transaksi pembayaran SPP, penarikan tabungan, atau pengeluaran RKAS harus memicu pembuatan jurnal umum.
   - Pengecekan otomatis: Total debit jurnal sama dengan total kredit jurnal (`SUM(debit) == SUM(credit)`).
   - Pengujian isolasi sumber dana: Transaksi BOS tidak boleh mendebit/mengkredit akun kas non-BOS tanpa jurnal penyeimbang antar-rekening yang sah.
3. **Pengecekan Alur Pengguna & Rute HTTP:**
   - Uji respon HTTP pada rute kritis (status 200 untuk halaman valid, status 302 untuk redirect autentikasi, status 403 untuk akses tanpa izin peran).

---

## 2. AUDIT KEAMANAN WEB & VULNERABILITY CHECK
1. **Pemeriksaan Dependensi:**
   - Menjalankan `composer audit` untuk mendeteksi kerentanan yang terdaftar di database advisori keamanan PHP.
2. **Cross-Site Request Forgery (CSRF):**
   - Seluruh form mutasi data (`POST`, `PUT`, `PATCH`, `DELETE`) wajib menyertakan token `@csrf` atau header `X-CSRF-TOKEN`.
3. **Cross-Site Scripting (XSS) & Sanitasi Input:**
   - Seluruh data teks pengguna (nama siswa, catatan kuitansi, uraian belanja) di-escape secara aman melalui Blade `{{ $variable }}`.
   - Tidak menggunakan `{!! $raw !!}` tanpa sanitasi ketat.
4. **Mass Assignment Protection:**
   - Seluruh model Eloquent mendefinisikan `$fillable` secara eksplisit atau mengunci field sensitif (seperti status audit, hak akses peran, balance kas).
5. **Insecure Direct Object Reference (IDOR) & RBAC:**
   - Siswa / Wali Murid hanya dapat melihat data tagihan dan tabungan miliknya sendiri berdasarkan `parent_email` atau `student_id` yang terikat pada sesi pengguna.
   - Bendahara dan Admin TU dibatasi sesuai peran masing-masing dengan Laravel Policy / Gate.
6. **Keamanan OAuth 2.0 (Google Socialite):**
   - Validasi state token untuk mencegah login CSRF.
   - Pengikatan email Google terverifikasi (`email_verified == true`).
   - Penanganan kegagalan autentikasi dengan pesan kesalahan ramah pengguna dalam Bahasa Indonesia.

---

## 3. PROSEDUR DOKUMENTASI MILESTONE (`docs/plan/`)
Setiap kali pengujian dan audit selesai pada satu siklus perintah, asisten wajib menghasilkan laporan tertulis di folder `docs/plan/` dengan spesifikasi:
- **Format Nama File:** `docs/plan/m{N}-{slug-kegiatan}-{YYYY-MM-DD}.md`
- **Struktur Isi Laporan:**
  1. Ringkasan Eksekutif & Ruang Lingkup Milestone.
  2. Perubahan Kode & File yang Dibuat / Dimodifikasi.
  3. Hasil Pengujian Fungsional (Daftar uji yang lolos).
  4. Hasil Audit Keamanan (Temuan, mitigasi, status risiko).
  5. Checklist Integritas Akuntansi & Lokalisasi Bahasa.
  6. Rencana Kerja untuk Milestone Berikutnya.

