# Sistem Informasi Keuangan & Pembukuan Akuntansi SMA/SMK (SMK DUMMY KIPE)

[![PHP](https://img.shields.io/badge/PHP-8.2%2B-blue.svg)](https://www.php.net/)
[![CodeIgniter](https://img.shields.io/badge/Framework-CodeIgniter%203-red.svg)](https://codeigniter.com/)
[![Database](https://img.shields.io/badge/Database-MySQL-orange.svg)](https://www.mysql.com/)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)
[![Portfolio](https://img.shields.io/badge/Portfolio-Kipe-informational.svg)](https://kipe-portfolio.vercel.app/)

Sistem Informasi Manajemen Keuangan dan Pembukuan Akuntansi Sekolah Menengah Atas / Kejuruan (**SMK DUMMY KIPE**). Dibangun berbasis web menggunakan **PHP 8.2+** dan **CodeIgniter 3**, mengadopsi standar tata kelola keuangan sekolah dan prinsip akuntansi entitas nonlaba (**ISAK 35**).

Antarmuka dirancang sederhana, elegan, dan intuitif sehingga mudah dipahami oleh staf Tata Usaha, Guru, Bendahara, Kepala Sekolah, maupun Wali Murid tanpa latar belakang akuntansi yang rumit.

---

## 🚀 Fitur Utama & Modul Sistem

### 1. 👥 Multi-Role & Role-Based Access Control (RBAC) 4 Peran
Akses dan aksi dibatasi secara ketat berdasarkan peran pengguna:
* **Admin TU (Tata Usaha & IT)**: Pengelolaan data master siswa, rombel/kelas, guru, manajemen akun pengguna (/Pengguna), serta pengaturan sistem.
* **Bendahara Sekolah**: Pencatatan kas masuk (SPP, Tabungan, Sumbangan/Lainnya), kas keluar (Gaji Guru, Operasional), pembukuan Buku Kas Umum (BKU), dan cetak kuitansi resmi.
* **Kepala Sekolah**: Dasbor eksekutif, rekapitulasi tunggakan siswa, audit BKU (*read-only*), serta laporan pertanggungjawaban keuangan dan Rekap ISAK 35.
* **Wali Murid / Siswa**: Portal mandiri khusus wali murid (/School_finance/portal) untuk memantau status tagihan SPP anak, histori pembayaran, unduh kuitansi, dan cek saldo tabungan siswa.

---

### 2. 📊 Dasbor Eksekutif Keuangan
* **Kartu Ringkasan Realtime**: Penerimaan SPP Bulan Berjalan, Total Pengeluaran Sekolah, Sisa Pagu Anggaran RKAS, dan Total Tunggakan Siswa Belum Terbayar.
* **Pemisahan Saldo & Rekening**: Saldo Kas Tunai Bendahara, Bank Giro Operasional & SPP, serta Rekening Khusus Dana BOS.
* **Aktivitas Transaksi Terkini**: Tabel mutasi transaksi penerimaan dan pengeluaran terbaru.

---

### 3. 📂 Modul Transaksi & Pembukuan
* **Data Master**:
  * Data Siswa & Jurusan (NIS, NISN, Rombel, Orang Tua, Beasiswa/KIP).
  * Data Rombel / Kelas.
  * Data Guru & Tenaga Kependidikan.
  * Manajemen Pengguna / User RBAC (Akses khusus Admin TU).
* **Kas Masuk**:
  * **Tagihan & SPP**: Penerbitan tagihan siswa, verifikasi pembayaran bertahap atau lunas, serta cetak bukti kuitansi PDF.
  * **Tabungan Siswa**: Setoran, penarikan, dan histori mutasi saldo tabungan siswa.
  * **Kas Masuk Lainnya**: Penerimaan dana BOS, sumbangan yayasan, sewa kantin/aula, lengkap dengan input keterangan, fitur **Edit**, dan **Hapus**.
* **Kas Keluar**:
  * Pembayaran Gaji & Honor Guru/Karyawan.
  * Pengeluaran Operasional Sekolah (ATK, utilitas listrik/air, pemeliharaan sarana).
* **Laporan Keuangan**:
  * **Rekap ISAK (Entitas Nonlaba)**: 4 Tab Navigasi terpadu:
    1. *Laporan Posisi Keuangan (Neraca)*: Aset Lancar, Aset Tetap, Liabilitas, dan Aset Neto.
    2. *Laporan Aktivitas*: Pendapatan dengan pembatasan/tanpa pembatasan vs Beban operasional.
    3. *Buku Kas Umum (BKU)*: Rekapitulasi mutasi kas harian & bulanan.
    4. *Jurnal Double-Entry*: Rekapitulasi debit/kredit seimbang (*balanced entries*).
  * **Buku Kas Umum (BKU)**: Laporan kronologis kas masuk dan keluar.
  * **Rekap Tunggakan**: Monitoring siswa yang memiliki tunggakan untuk tindak lanjut cepat.

---

### 4. 🎨 Peningkatan Tampilan & Pengalaman Pengguna (UI/UX)
* **Branding Resmi**: Logo Tut Wuri Handayani beresolusi proporsional di sidebar topbar.
* **Top Navigation Bersih**: Menghilangkan teks bertumpuk, memberikan ruang fokus pada navigasi dan tahun ajaran aktif.
* **Sidebar User Panel Modern**: Nama pengguna tampil rapi tanpa terpotong, dilengkapi badge peran (*Admin TU*, *Bendahara*, *Kepala Sekolah*, *Wali Murid*) di bawah nama.
* **Konsistensi DataTables**: Seluruh tabel menggunakan jQuery DataTables dengan pagination, search bar, dan sorting dinamis.
* **Layout Responsive**: Container padding 25px menjaga konten tidak menabrak sidebar pada layar laptop maupun desktop.
* **Footer Profesional**: Terintegrasi Copyright © 2026 Kipe Portfolio dengan tautan langsung ke portfolio pengembang.

---

## 🔑 Akun Demo (Default Credentials)

Gunakan akun berikut untuk mencoba alur masing-masing peran:

| Peran Pengguna | Email Login | Password Default | Hak Akses Utama |
| :--- | :--- | :--- | :--- |
| **Admin TU** | dmin@smkn1akuntansi.sch.id | dmin123 | Full Master Data, User Management, Pengaturan |
| **Bendahara** | endahara@smkn1akuntansi.sch.id | endahara123 | Transaksi Kas Masuk/Keluar, SPP, BKU, Tabungan |
| **Kepala Sekolah** | kepsek@smkn1akuntansi.sch.id | kepsek123 | Dasbor Eksekutif, Laporan ISAK 35, Tunggakan, Audit BKU |
| **Wali Murid** | wali.rizky@gmail.com | wali123 | Portal Wali Murid, Cek SPP, Unduh Kuitansi, Saldo Tabungan |

---

## 💻 Panduan Instalasi Lokal (Localhost XAMPP)

### Prasyarat
* PHP >= 8.2
* MySQL / MariaDB
* Apache Web Server (XAMPP / Laragon)

### Langkah Pemasangan:
1. **Clone Repository**:
   `ash
   git clone https://github.com/KipeGithub/Simple_Sistem_Keuangan_SMA.git
   `
2. **Pindahkan Folder**:
   Pindahkan folder proyek ke dalam direktori htdocs/Simple_Akuntansi_Sekolah.

3. **Buat Database**:
   Buka phpMyAdmin (http://localhost/phpmyadmin), buat database baru bernama:
   `sql
   CREATE DATABASE si_keuangan;
   `

4. **Jalankan Migrasi Database Otomatis**:
   Buka terminal di root direktori proyek, lalu jalankan:
   `ash
   php migrate_si_keuangan.php
   `
   *Skrip migrasi ini secara otomatis membuat seluruh tabel, akun pengguna default, relasi data, serta data sampel.*

5. **Akses Aplikasi**:
   Buka browser Anda dan akses:
   `	ext
   http://localhost/Simple_Akuntansi_Sekolah
   `

---

## ☁️ Panduan Deployment ke Vercel

Proyek ini telah dilengkapi konfigurasi ercel.json dan dukungan environment variables database otomatis pada pplication/config/database.php.

### Langkah Deployment:
1. **Push ke GitHub**:
   Pastikan seluruh commit terbaru telah ter-push ke GitHub repository Anda.
2. **Siapkan Database MySQL Cloud**:
   Gunakan penyedia MySQL cloud gratis seperti **TiDB Cloud**, **Supabase MySQL**, **Aiven**, **Railway**, atau **Clever Cloud**.
3. **Import Proyek di Vercel**:
   * Buka [Vercel Dashboard](https://vercel.com/dashboard) -> **Add New Project** -> Pilih repositori Simple_Sistem_Keuangan_SMA.
4. **Konfigurasi Environment Variables di Vercel**:
   Tambahkan variabel lingkungan berikut di tab **Settings -> Environment Variables**:
   * DB_HOSTNAME : *Host database MySQL cloud Anda*
   * DB_USERNAME : *Username database*
   * DB_PASSWORD : *Password database*
   * DB_DATABASE : *Nama database*
   * DB_PORT : 3306
5. **Deploy**:
   Klik tombol **Deploy**. Vercel akan otomatis menyajikan aplikasi PHP dan me-routing file statis dari folder ssets/.

---

## 🛠️ Tech Stack & Library

* **Backend**: PHP 8.2+, CodeIgniter 3.1.11
* **Database**: MySQL 8.0 / MariaDB
* **UI Framework**: AdminLTE 2.4, Bootstrap 3.3.7
* **Plugins & Icons**: Font Awesome, jQuery DataTables, SweetAlert2, Select2
* **Serverless Runtime**: ercel-php@0.7.3

---

## 📄 Lisensi & Kontributor

Dikembangkan dan disempurnakan oleh **Kipe**.
* **Portfolio**: [https://kipe-portfolio.vercel.app/](https://kipe-portfolio.vercel.app/)
* **Copyright**: © 2026 Kipe Portfolio. All rights reserved.