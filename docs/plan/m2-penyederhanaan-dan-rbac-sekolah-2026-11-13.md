# LAPORAN MILESTONE 2: PENYEDERHANAAN SISTEM, PENGUNCIAN 4 PERAN (RBAC), DAN REBRANDING SMK DUMMY KIPE

> **Tanggal:** 13 November 2026  
> **Aplikasi:** Sistem Informasi Keuangan & Pembukuan Akuntansi Sekolah  
> **Nama Sekolah:** SMK DUMMY KIPE  
> **Status:** 100% Selesai & Terverifikasi (29/29 Test Passed)  
> **Dokumen Terkait:** `docs/plan/m1-migrasi-dan-implementasi-si-keuangan-smk-2026-11-13.md`

---

## 1. RINGKASAN PERUBAHAN UTAMA

Berdasarkan arahan pengguna untuk menyederhanakan alur kerja, meningkatkan keamanan, dan memperbaiki estetika visual antarmuka:

1. **Pelepasan Total Fitur Google OAuth 2.0:**  
   Menghapus seluruh integrasi Google OAuth (Login, Registrasi, dan Pemulihan Akun via Google) demi menghilangkan potensi kerentanan keamanan dan risiko pengambilalihan akun sekolah. Autentikasi kini menggunakan sistem email dan kata sandi standar sekolah yang terenkripsi aman (`PASSWORD_DEFAULT` / BCRYPT).
2. **Penguncian Tepat 4 Peran Pengguna (RBAC Terisolasi):**  
   Sistem mengunci hak akses pengguna hanya pada 4 peran definitif:
   - **Role 1: Admin TU**
   - **Role 2: Bendahara**
   - **Role 3: Kepala Sekolah**
   - **Role 4: Wali Murid**
3. **Penyederhanaan Menu & Bahasa untuk Orang Awam / Guru:**  
   Menghapus istilah akuntansi rumit (*ISAK 35*, *RKAS 8 SNP*, *SPP & Kas Masuk*) dan menyusun ulang sidebar ke dalam 5 kelompok menu yang mudah dimengerti orang awam:
   - **Dasbor**
   - **Data Master** (Data Siswa, Data Kelas, Data Guru, *Manajemen Pengguna*)
   - **Kas Masuk** (Pembayaran SPP, Tabungan Siswa, Uang Pendaftaran, Kas Masuk Lainnya)
   - **Kas Keluar** (Gaji / Honor Guru, Pengeluaran Operasional)
   - **Laporan Keuangan** (Rekap Kas Masuk & Keluar / BKU, Rekap Tunggakan Siswa)
4. **Rebranding Nama Sekolah Resmi:**  
   Seluruh nama sekolah di seluruh halaman, kop surat kuitansi PDF, judul dokumen, dan metadata sistem diubah dari `SMK Negeri 1 Akuntansi & Teknologi` menjadi **`SMK DUMMY KIPE`**.
5. **Logo Top Navbar Bersih (Tanpa Teks):**  
   Teks tulisan `SMKN 1 AKUNTANSI` di pojok kiri atas top navbar sidebar telah dihapus dan digantikan murni oleh logo gambar resmi Tut Wuri Handayani (`assets/dist/img/tut_wuri_handayani.png`).
6. **Perbaikan Tampilan Antarmuka (Anti-Double Title & Anti-Nabrak):**  
   - Menghapus bug duplikasi judul seperti *"Data Data Siswa & Jurusan"* dan *"Data Beranda"*.
   - Menyempurnakan spasi tabel, padding sel, kontras font, dan sudut kartu (*border-radius*) agar responsif serta rapi di layar ponsel maupun desktop.
7. **Modul Eksklusif Admin TU: Manajemen Pengguna (`Pengguna.php`):**  
   Menambahkan controller `Pengguna.php` dan tampilan `v_Pengguna.php` di mana Admin TU dapat menambah akun pegawai/wali baru, mengedit data dan peran akun, mengatur ulang sandi (*reset password*), dan menghapus pengguna dengan proteksi ketat (*forbidden* 403 untuk peran lain).

---

## 2. MATRIKS HAK AKSES & TINDAKAN PER PERAN (RBAC MATRIX)

| Fitur / Modul | Admin TU (Role 1) | Bendahara (Role 2) | Kepala Sekolah (Role 3) | Wali Murid (Role 4) |
| :--- | :---: | :---: | :---: | :---: |
| **Dasbor Keuangan** | Baca (View) | Baca (View) | Baca (View) | Dialihkan ke Portal |
| **Data Siswa, Kelas, Guru** | **Kelola Penuh** (Tambah/Edit/Hapus) | Baca Saja (Read-Only) | Baca Saja (Read-Only) | Ditolak |
| **Manajemen Pengguna (`users`)** | **Eksklusif** (Tambah/Edit/Hapus/Reset) | **Ditolak (403 Forbidden)** | **Ditolak (403 Forbidden)** | **Ditolak (403 Forbidden)** |
| **Terbitkan Tagihan SPP Massal** | **Bisa Eksekusi** | **Bisa Eksekusi** | Sembunyi (Read-Only) | Ditolak |
| **Bayar Tagihan SPP & Cetak Kuitansi** | Baca Saja | **Bisa Transaksi & Cetak** | Baca Saja (Tombol Bayar Sembunyi) | Ditolak |
| **Tabungan Siswa (Setor/Tarik)** | Baca Saja | **Bisa Transaksi Setor/Tarik** | Baca Saja (Monitoring) | Ditolak |
| **Kas Keluar (Gaji Guru & Operasional)** | Baca Saja | **Bisa Catat Pengeluaran** | Baca Saja (Monitoring) | Ditolak |
| **Laporan BKU & Rekap Tunggakan** | Baca & Cetak | Baca & Cetak | **Fokus Utama Monitoring** | Ditolak |
| **Portal Mandiri Siswa / Wali** | Ditolak | Ditolak | Ditolak | **Akses Utama** (Lihat SPP & Tabungan Sendiri) |

---

## 3. ANALOGI SEDERHANA ALUR KEUANGAN SEKOLAH (UNTUK ORANG AWAM & GURU)

Untuk mempermudah pemahaman tanpa istilah perbankan yang rumit, alur sistem ini bekerja seperti **Buku Kas Rukun Tetangga (RT) yang Sangat Disiplin dan Otomatis**:

```
[1. TAHUN AJARAN BARU] 
Admin TU memasukkan Data Siswa & Kelas
             │
             ▼
[2. SETIAP AWAL BULAN] 
Admin TU / Bendahara klik "Terbitkan SPP Massal" (1-Klik)
Sistem otomatis membuatkan catatan kewajiban iuran untuk seluruh siswa
             │
             ▼
[3. SISWA / WALI DATANG MEMBAYAR]
Bendahara membuka menu "Kas Masuk -> Pembayaran SPP"
Cari nama siswa -> Masukkan jumlah uang -> Klik "Bayar"
             │
             ├──► 1. Status tagihan siswa otomatis berganti menjadi LUNAS
             ├──► 2. Uang fisik/transfer otomatis menambah Saldo Kas Sekolah
             ├──► 3. Catatan pembukuan kas otomatis bertambah di Buku Kas
             └──► 4. Kuitansi resmi ber-kop "SMK DUMMY KIPE" langsung tercetak
             │
             ▼
[4. OPERASIONAL & GAJI]
Bendahara mengeluarkan uang untuk Gaji Guru atau Belanja ATK Sekolah
Saldo kas sekolah otomatis berkurang secara transparan
             │
             ▼
[5. PENGAWASAN KEPALA SEKOLAH]
Kepala Sekolah membuka Dasbor & Laporan dari mejanya:
Bisa melihat sisa saldo kas saat ini, total uang masuk, total uang keluar, 
serta daftar siapa saja siswa yang masih menunggak SPP tanpa bisa mengubah-ubah data.
```

---

## 4. KLARIFIKASI REKAP TUNGGAKAN SISWA & DETIK DEMI DETIK TOMBOL "BAYAR"

### A. Dari Mana Angka Rekap Tunggakan Siswa Diperoleh?
Sistem menghitung tunggakan dengan rumus sederhana:
$$\text{Sisa Tunggakan} = \text{Kewajiban Tagihan (Net Amount)} - \text{Jumlah yang Sudah Dibayar (Paid Amount)}$$
- **Contoh Kasus:** Siswa A memiliki tagihan SPP bulan November sebesar **Rp 350.000**.
  - Jika belum bayar sama sekali (`Paid Amount = 0`), statusnya `BELUM LUNAS` dan menunggak Rp 350.000.
  - Jika siswa mencicil **Rp 150.000**, statusnya `SEBAGIAN` dan sisa tunggakannya otomatis menjadi **Rp 200.000**.
  - Menu **Rekap Tunggakan Siswa** secara otomatis menyaring seluruh tagihan yang statusnya belum `LUNAS` (`UNPAID` atau `PARTIAL`). Jika siswa sudah bayar lunas, namanya otomatis **hilang** dari daftar tunggakan.

### B. Apa yang Terjadi Detik Demi Detik Saat Tombol "Bayar" Diklik?
Ketika Bendahara menekan tombol **"Simpan & Cetak Kuitansi"** pada formulir bayar:
1. **Langkah 1 (Update Status Siswa):** Nilai `paid_amount` pada tabel `tagihan_siswa` bertambah sebesar nominal yang disetor. Jika sudah melunasi seluruh kewajiban, status tagihan otomatis berubah menjadi `PAID` (Lunas).
2. **Langkah 2 (Kas Bertambah):** Saldo pada rekening kas sekolah yang dipilih (`rekening_kas` Tunai / Bank) otomatis bertambah sebesar nominal yang dibayar.
3. **Langkah 3 (Penerbitan Nomor Bukti Sah):** Sistem otomatis membuat nomor kuitansi unik berformat resmi: `KWT/SMK/{TAHUN}/{BULAN}/{NOMOR_ACAK}`.
4. **Langkah 4 (Pencatatan Buku Kas):** Sistem otomatis membukukan transaksi ke Buku Kas Umum (BKU) dan rekap harian kas sekolah.
5. **Langkah 5 (Tampilan Kuitansi Siap Cetak):** Layar Bendahara langsung dialihkan ke lembar Kuitansi Pembayaran resmi ber-kop **SMK DUMMY KIPE** lengkap dengan stempel digital lunas, rincian pembayaran, dan tanda tangan penerima untuk diserahkan ke wali murid.

---

## 5. HASIL PENGUJIAN FUNGSIONAL & KEAMANAN RBAC (29/29 PASS)

Pengujian otomatis dijalankan secara langsung via PHP 8.2 (`C:\xampp_phpversi_8.2.12\php\php.exe test_live_http_roles.php`) terhadap server Apache localhost:

```
====================================================================
 LIVE HTTP & RBAC TESTING: 4 PERAN SEKOLAH (SMK DUMMY KIPE)
====================================================================

[TEST PERAN] 1. Admin TU (admin@smkn1akuntansi.sch.id)
  [PASS] Halaman Login dapat diakses (HTTP 200)
  [PASS] Tombol Google OAuth telah bersih/dilepas dari Form Login
  [PASS] Autentikasi berhasil, redirect ke: .../School_finance/dasbor
  [PASS] Akses /School_finance/dasbor -> HTTP 200 OK
  [PASS] Akses /School_finance/siswa -> HTTP 200 OK
  [PASS] Verifikasi UI: Tidak ada judul berulang 'Data Data Siswa'
  [PASS] Akses /Pengguna -> HTTP 200 OK (Eksklusif Admin TU)
  [PASS] Akses /Kelas -> HTTP 200 OK
  [PASS] Akses /Guru -> HTTP 200 OK

[TEST PERAN] 2. Bendahara (bendahara@smkn1akuntansi.sch.id)
  [PASS] Halaman Login dapat diakses (HTTP 200)
  [PASS] Tombol Google OAuth telah bersih/dilepas dari Form Login
  [PASS] Autentikasi berhasil, redirect ke: .../School_finance/dasbor
  [PASS] Akses /School_finance/dasbor -> HTTP 200 OK
  [PASS] Akses /School_finance/tagihan -> HTTP 200 OK
  [PASS] Akses /School_finance/tabungan -> HTTP 200 OK
  [PASS] Akses /School_finance/bku -> HTTP 200 OK
  [PASS] Proteksi Keamanan RBAC: /Pengguna DITOLAK (HTTP 403 Forbidden)

[TEST PERAN] 3. Kepala Sekolah (kepsek@smkn1akuntansi.sch.id)
  [PASS] Halaman Login dapat diakses (HTTP 200)
  [PASS] Tombol Google OAuth telah bersih/dilepas dari Form Login
  [PASS] Autentikasi berhasil, redirect ke: .../School_finance/dasbor
  [PASS] Akses /School_finance/dasbor -> HTTP 200 OK
  [PASS] Akses /School_finance/tunggakan -> HTTP 200 OK
  [PASS] Akses /School_finance/bku -> HTTP 200 OK
  [PASS] Proteksi Keamanan RBAC: /Pengguna DITOLAK (HTTP 403 Forbidden)

[TEST PERAN] 4. Wali Murid / Siswa (wali.rizky@gmail.com)
  [PASS] Halaman Login dapat diakses (HTTP 200)
  [PASS] Tombol Google OAuth telah bersih/dilepas dari Form Login
  [PASS] Autentikasi berhasil, redirect ke: .../School_finance/portal
  [PASS] Akses /School_finance/portal -> HTTP 200 OK
  [PASS] Proteksi Keamanan RBAC: /Pengguna DITOLAK (HTTP 403 Forbidden)

====================================================================
 REKAPITULASI HASIL TESTING: 29 / 29 PENGUJIAN BERHASIL (100% PASS)
====================================================================
```

---

## 6. AUDIT KERENTANAN & KEAMANAN WEB (VULNERABILITY AUDIT)

1. **Proteksi IDOR & Privilege Escalation:**  
   Percobaan akses ke rute sensitif Admin TU (`/Pengguna`) oleh Bendahara, Kepala Sekolah, maupun Wali Murid berhasil ditolak secara mutlak pada level backend dengan status kode `HTTP 403 Forbidden`.
2. **Proteksi Password Hashing:**  
   Seluruh kata sandi pengguna baru maupun proses reset sandi menggunakan fungsi hashing bawaan `password_hash($pass, PASSWORD_DEFAULT)` yang menghasilkan hash bcrypt kuat.
3. **Pelepasan Serangan OAuth & Token Hijacking:**  
   Seluruh rute Google OAuth dinonaktifkan permanen dan dialihkan langsung kembali ke halaman login standar, sehingga sistem imun dari potensi pencurian access token atau manipulasi state parameter.
4. **Proteksi XSS & Input Sanitization:**  
   Seluruh input teks pada data siswa, kuitansi, dan pengguna diproses melalui fungsi `htmlspecialchars()` dan filter sanitasi CodeIgniter untuk mencegah injeksi script berbahaya.
5. **Integritas Database MySQL:**  
   Seluruh kolom moneter tetap terkunci pada format presisi tinggi `DECIMAL(15,2)` dengan integritas pembukuan kas yang konsisten.

