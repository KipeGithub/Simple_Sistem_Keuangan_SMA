---
name: ui-branding-smk
description: Panduan identitas visual dan pengalaman pengguna (UI/UX) resmi sekolah SMA/SMK Indonesia, palet warna Biru Akademik dan Hijau Zamrud, kop surat kuitansi PDF, dan navigasi peran sekolah.
---

# PANDUAN IDENTITAS VISUAL & UI/UX SEKOLAH SMA/SMK

Pedoman ini mengatur standardisasi tampilan visual antarmuka web, tipografi, palet warna, kop surat kuitansi/laporan, dan tata letak navigasi pengguna.

---

## 1. PALET WARNA RESMI (OFFICIAL COLOR PALETTE)
- **Primary / Biru Akademik (Wibawa & Kepercayaan Pendidikan):**
  - Utama: `#0F294A` (Deep Academic Blue)
  - Interaksi Hover / Aksen: `#1B3B6F`
  - Ringan / Background Badge: `#EBF3FB`
- **Secondary / Hijau Zamrud (Kesejahteraan & Finansial Terpercaya):**
  - Utama: `#0D6E52` (Emerald Green)
  - Hover: `#0A5841`
  - Light Accent: `#E6F5F0`
- **Netral & Permukaan (Neutral & Surface):**
  - Latar Belakang Aplikasi: `#F8FAFC` (Slate 50)
  - Kartu & Kontainer: `#FFFFFF` (Pure White)
  - Garis Tepi / Border: `#E2E8F0` (Slate 200)
  - Teks Utama (Headings/Body): `#0F172A` (Slate 900)
  - Teks Sekunder (Keterangan/Meta): `#64748B` (Slate 500)
- **Status Indikator Administrasi:**
  - Lunas / Terverifikasi: `#059669` (Hijau Sukses)
  - Belum Lunas / Peringatan: `#D97706` (Kuning Amber)
  - Menunggak / Batal / Bahaya: `#DC2626` (Merah Peringatan)
  - Draf / Informasi: `#2563EB` (Biru Info)

---

## 2. TIPOGRAFI & KOMPONEN
1. **Font Family:** Inter, Plus Jakarta Sans, atau sistem font sans-serif modern (`ui-sans-serif, system-ui, sans-serif`).
2. **Keterbacaan Finansial:**
   - Kolom nominal moneter selalu rata kanan (`text-right`) dengan font monospace atau tabular figures (`font-mono tracking-tight font-medium`).
   - Seluruh nominal didahului dengan `Rp` dan pemisah ribuan titik (`Rp 450.000`).
3. **Kartu Statistik Ringkas:**
   - Statistik dashboard wajib menampilkan data agregat riil: Total Penerimaan SPP Bulan Ini, Realisasi Belanja BOS, Sisa Pagu RKAS, dan Total Tunggakan Siswa Aktif.

---

## 3. FORMAT KOP SURAT RESMI (LAPORAN & KUITANSI PDF)
Setiap dokumen cetak resmi (Kuitansi SPP, Formulir BKU, Rekap Tunggakan, Laporan Neraca) wajib memiliki struktur Kop Surat Standar:

```
+-------------------------------------------------------------------------------+
| [LOGO DINAS/YAYASAN]       PEMERINTAH PROVINSI / YAYASAN PENDIDIKAN          |
|                             SMK NEGERI / SWASTA TEKNOLOGI                   |
|                        NPSN: 12345678 | NSS: 401026001001                   |
|        Jl. Pendidikan Vokasi No. 45, Telp: (021) 7654321, Kode Pos 14250     |
|                   Website: www.smk-kejuruan.sch.id | Email: info@smk.sch.id   |
+-------------------------------------------------------------------------------+
=================================================================================
                               (Garis Tebal Ganda)
```

### Elemen Wajib Kuitansi Siswa:
1. Nomor Transaksi Kuitansi Unik (Contoh: `KW/2026/XI/SPP-00124`).
2. Telah Diterima Dari: Nama Siswa, NISN/NIS, Kelas, dan Program Keahlian/Jurusan.
3. Rincian Pembayaran: Periode Bulan SPP / Jenis Biaya Kejuruan.
4. Terbilang: Konversi nominal ke kata-kata bahasa Indonesia (Contoh: *"Dua Ratus Lima Puluh Ribu Rupiah"*).
5. Tanda Tangan Digital / QR Code Verifikasi Keaslian Kuitansi.
6. Cap Sekolah & Nama Terang Bendahara Penerima.

---

## 4. STRUKTUR NAVIGASI PENGGUNA (ROLE-BASED UX)
1. **Header Bar:**
   - Pemilih **Tahun Ajaran Aktif** (Contoh: `2026/2027 Ganjil` / `2026/2027 Genap`).
   - Profil Pengguna (Nama, Peran: Bendahara/Admin TU/Kepala Sekolah/Wali Murid).
2. **Sidebar Menu Bendahara / Admin TU:**
   - **Dashboard Keuangan:** Ringkasan kas, realisasi, dan grafik pemasukan.
   - **Kesiswaan & Tagihan:**
     - Data Siswa & Rombel (Kelas/Jurusan SMK).
     - Generator SPP Massal.
     - Pembayaran & Riwayat Kuitansi.
     - Rekap Siswa Menunggak.
   - **Buku Kas & Bank:**
     - Kas Tunai Bendahara.
     - Rekening Giro BOS.
     - Rekening SPP / Komite.
   - **Anggaran RKAS & Belanja:**
     - Pagu Anggaran RKAS.
     - Pengeluaran / SPJ Belanja Sekolah.
   - **Tabungan Siswa:** Setoran, Penarikan, dan Buku Tabungan.
   - **Akuntansi & Laporan (ISAK 35):**
     - Jurnal Umum.
     - Buku Kas Umum (BKU).
     - Laporan Posisi Keuangan & Laporan Aktivitas.
3. **Portal Orang Tua / Siswa:**
   - Ringkasan Tagihan Aktif.
   - Riwayat Pembayaran & Cetak Ulang Kuitansi.
   - Saldo & Riwayat Tabungan Siswa.

