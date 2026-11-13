# AGENT.md - KONTRAK TATA KELOLA & ARSITEKTUR WORKSPACE

> **STATUS WORKSPACE:** TERKUNCI & DIAWASI SISTEMATIS  
> **APLIKASI:** Sistem Informasi Manajemen Keuangan & Pembukuan Akuntansi Lengkap SMA/SMK  
> **NAMA SEKOLAH RESMI:** SMK DUMMY KIPE  
> **BASIS SISTEM:** PHP 8.2+ / PHP 8.4+, CodeIgniter 3.1.x, MySQL 8.0+ / MariaDB (`si_keuangan`)  
> **LOKASI PROYEK:** `c:\xampp_phpversi_8.2.12\htdocs\Simple_Akuntansi_Sekolah`  
> **URL UTAMA:** `http://localhost/Simple_Akuntansi_Sekolah/`
> **AUTENTIKASI:** Standar Email & Password Sekolah (Google OAuth dilepas total demi keamanan)
> **ROLE TERKUNCI 4:** 1. Admin TU, 2. Bendahara, 3. Kepala Sekolah, 4. Wali Murid

Dokumen ini adalah **kontrak arsitektur mutlak dan aturan keras (hard constraints)** yang WAJIB dibaca dan dipatuhi oleh asisten AI (Antigravity) setiap kali menerima perintah baru dari pengguna, baik pada sesi saat ini maupun sesi lanjutan.

---

## 1. TECH STACK LOCK (BATASAN TEKNOLOGI)
1. **PHP:** Wajib kompatibel penuh dengan **PHP 8.2+ / PHP 8.4+** (menggunakan runtime `C:\xampp_phpversi_8.2.12\php\php.exe`). Dilarang membiarkan peringatan *E_DEPRECATED* atau *Creation of dynamic property* merusak output HTML atau HTTP header.
2. **Database:** Wajib **MySQL / MariaDB** (`si_keuangan`, charset `utf8mb4`, collation `utf8mb4_unicode_ci`).
3. **Standar Presisi Uang:** Seluruh kolom moneter/nominal di seluruh tabel basis data wajib menggunakan tipe data **`DECIMAL(15,2)`** dengan nilai baku `0.00`. Dilarang keras menggunakan tipe `VARCHAR` atau `INT` untuk uang.
4. **Mata Uang Terkunci:** Terkunci 100% pada Rupiah Indonesia (**IDR** / `Rp`). Format tampilan angka baku: `Rp 1.500.000` (`number_format($amount, 0, ',', '.')`).

---

## 2. BAHASA LOCK (100% BAHASA INDONESIA)
1. **Frontend:** Seluruh label menu navigasi, judul halaman, tombol aksi, modal, pesan flash notifikasi, teks validasi formulir, dan kuitansi cetak PDF/tampilan cetak WAJIB menggunakan Bahasa Indonesia yang formal dan baku sesuai administrasi sekolah Indonesia.
2. **Backend:** Pesan error, exception, komentar kode operasional, enum status, seeder data, dan riwayat aktivitas wajib berbahasa Indonesia.
3. **Dilarang Teks Asing:** Dilarang menyisakan teks antarmuka bahasa Inggris generik seperti *Dashboard*, *Submit*, *Delete*, *Settings*, *Invoices*, dll. tanpa terjemahan kontekstual sekolah (*Dasbor Keuangan*, *Simpan Data*, *Hapus*, *Pengaturan*, *Tagihan SPP*).

---

## 3. SCOPE LOCK (ANTI-MELENCENG DARI KEUANGAN SEKOLAH SMA/SMK)
1. **Fokus Domain Mutlak:** Aplikasi ini eksklusif dan 100% ditujukan untuk **Pengelolaan Keuangan & Pembukuan Akuntansi SMA/SMK**:
   - Master Data: Siswa (NISN, NIS, Kelas, 4 Jurusan SMK: RPL, TKJ, AKL, TKR, Email Orang Tua/Wali), Guru/Pegawai, Rombel, Tahun Ajaran Aktif.
   - Tagihan & Penerimaan: SPP Bulanan Massal, Uang Pangkal / Pendaftaran, Biaya Praktik Kejuruan SMK, Ujian, Katering, Snack, dan Biaya Lainnya.
   - Kas & Bank Multi-Rekening: Pemisahan ketat antara **Kas Tunai Bendahara**, **Rekening Giro Operasional SPP/Yayasan**, dan **Rekening Khusus Dana BOS**.
   - Anggaran RKAS 8 Standar Nasional Pendidikan (SNP) & Realisasi Belanja BOS.
   - Tabungan Siswa: Setoran & penarikan tabungan yang secara akuntansi nonlaba (ISAK 35) diakui sebagai **Liabilitas / Dana Titipan**, bukan pendapatan sekolah.
   - Bagan Akun (CoA) ISAK 35 & Jurnal Otomatis Double-Entry berimbang (`SUM(debit) == SUM(credit)`).
   - 4 Laporan Keuangan Standar: Posisi Keuangan (Neraca) ISAK 35, Laporan Aktivitas (Surplus/Defisit), Buku Kas Umum (BKU) BOS vs Yayasan/SPP, serta Rekapitulasi Tunggakan SPP.
2. **Dilarang Fitur Retail Komersial:** Dilarang menambahkan modul e-commerce, marketplace publik, shipping/ongkir, multi-currency valuta asing, atau pajak PPN komersial pada tagihan siswa.

---

## 4. ATURAN ANTI-SLOP (ZERO-SLOP ENGINEERING)
1. **No Placeholders:** Dilarang keras menulis komentar placeholder seperti `// TODO`, `// implement later`, `// ... existing code`, atau membuat fungsi kosong tanpa implementasi nyata. Semua berkas kode harus utuh, lengkap, dan siap dieksekusi.
2. **No Generic AI Slop:** Dilarang menggunakan desain generik template AI (hindari gradasi ungu neon cerah, grafik palsu tanpa query riil, atau data dummy asal-asalan). Terapkan palet warna resmi pendidikan: **Biru Akademik (`#0F294A`)** dan **Hijau Zamrud (`#0D6E52`)**.
3. **Database Integrity:** Semua perubahan skema wajib didokumentasikan dalam skrip migrasi/patch SQL yang idempotent (`IF NOT EXISTS` / `ADD COLUMN IF NOT EXISTS`).

---

## 5. PROTOKOL WAJIB SETIAP PERINTAH BARU (SOP EKSEKUSI)
Setiap kali pengguna memberikan perintah baru, asisten WAJIB menjalankan 5 tahapan berurutan berikut sebelum mengakhiri giliran:
1. **Rencana Teknis Singkat:** Tinjau kebutuhan domain dan arsitektur kode.
2. **Implementasi Kode Utuh:** Tulis kode secara lengkap tanpa potongan.
3. **Pengujian Fungsional:** Jalankan uji coba controller/HTTP request, pengujian database, dan pastikan tidak ada error 500, error 404, atau notice PHP 8.2.
4. **Vulnerability & Security Audit:** Audit keamanan mencakup proteksi CSRF, sanitasi XSS (`xss_clean` / `htmlspecialchars`), pencegahan SQL Injection (CodeIgniter Query Builder / PDO Prepared Statements), validasi peran (RBAC), dan keamanan token OAuth.
5. **Dokumentasi Milestone:** Simpan hasil pengujian dan audit ke dalam berkas:
   `docs/plan/m{N}-{slug-kegiatan}-{YYYY-MM-DD}.md` (contoh: `docs/plan/m1-setup-fondasi-dan-migrasi-si-keuangan-2026-11-13.md`).
3