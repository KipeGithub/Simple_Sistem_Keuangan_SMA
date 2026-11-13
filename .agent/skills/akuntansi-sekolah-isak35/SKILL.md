---
name: akuntansi-sekolah-isak35
description: Panduan standar akuntansi keuangan sekolah SMA/SMK berbasis entitas nonlaba ISAK 35, double-entry bookkeeping, pemisahan sumber dana, dan BKU/SPJ.
---

# STANDAR AKUNTANSI KEUANGAN SEKOLAH SMA/SMK (ISAK 35)

Dokumen skill ini menjadi acuan utama perancangan model data, transaksi jurnal umum, dan penyusunan laporan keuangan untuk institusi pendidikan SMA/SMK.

---

## 1. PRINSIP DASAR PEMBUKUAN BERPASANGAN (DOUBLE-ENTRY BOOKKEEPING)
1. **Keseimbangan Jurnal Mutlak:**
   - Setiap transaksi keuangan sekolah WAJIB menghasilkan entri jurnal berpasangan di mana:
     $$\sum \text{Debit} = \sum \text{Kredit}$$
   - Sistem menolak transaksi (throw database transaction exception) jika selisih antara total debit dan total kredit tidak sama dengan nol (`diff != 0.00`).
2. **Aturan Saldo Normal Akun:**
   - **Aset (1-xxxx):** Bertambah di Debit, Berkurang di Kredit (Saldo Normal: Debit).
   - **Liabilitas / Kewajiban (2-xxxx):** Bertambah di Kredit, Berkurang di Debit (Saldo Normal: Kredit).
   - **Aset Neto / Ekuitas Yayasan (3-xxxx):** Bertambah di Kredit, Berkurang di Debit (Saldo Normal: Kredit).
   - **Pendapatan Pendidikan (4-xxxx):** Bertambah di Kredit, Berkurang di Debit (Saldo Normal: Kredit).
   - **Beban Operasional & Belanja Modal (5-xxxx):** Bertambah di Debit, Berkurang di Kredit (Saldo Normal: Debit).

---

## 2. PEMISAHAN KETAT SUMBER DANA (FUND ACCOUNTING)
Setiap transaksi wajib mencatat atribut `fund_source` untuk mencegah percampuran dana peruntukan khusus:
1. **`BOS` (Bantuan Operasional Satuan Pendidikan):**
   - Dana bantuan pemerintah untuk operasional sekolah.
   - Dikelola melalui rekening giro bank khusus BOS.
   - Pengeluaran mengacu pada 8 Standar Nasional Pendidikan (SNP) dan juknis BOS Kemendikbudristek.
   - Tidak boleh digunakan untuk belanja di luar ketentuan juknis.
2. **`YAYASAN_SPP` (Dana Komite / Sumbangan Pembinaan Pendidikan):**
   - Iuran bulanan siswa (SPP), uang pangkal gedung, dan sumbangan sukarela orang tua.
   - Digunakan untuk honorarium pendidik, pengembangan sarana prasarana sekolah, dan operasional yayasan/komite.
3. **`TABUNGAN_SISWA` (Titipan / Amanah):**
   - Uang titipan siswa yang disetorkan ke kas sekolah.
   - **HUKUM AKUNTANSI:** Tabungan siswa **BUKAN PENDAPATAN SEKOLAH**.
   - Saat siswa menabung:
     - Debit: Kas Tabungan Siswa (`1-1103`)
     - Kredit: Hutang Tabungan Siswa (`2-1101`)
   - Saat siswa menarik tabungan:
     - Debit: Hutang Tabungan Siswa (`2-1101`)
     - Kredit: Kas Tabungan Siswa (`1-1103`)

---

## 3. STRUKTUR BAGAN AKUN STANDAR (CHART OF ACCOUNTS - ISAK 35)

### 1-xxxx ASET (ASSETS)
- `1-1101` Kas Tunai Bendahara
- `1-1102` Kas Khusus Dana BOS
- `1-1103` Kas Khusus Tabungan Siswa
- `1-1201` Bank Mandiri / BNI / BRI - Rekening SPP Yayasan
- `1-1202` Bank Daerah (BPD/BJB/Bank Jatim dsb.) - Giro BOS
- `1-1301` Piutang SPP & Iuran Siswa
- `1-1302` Piutang Praktik Kejuruan SMK
- `1-1401` Perlengkapan Habis Pakai / ATK Sekolah
- `1-2101` Peralatan Praktik Kejuruan (TKJ / RPL / AKL / Mesin / Otomotif)
- `1-2102` Gedung & Bangunan Sekolah
- `1-2103` Akumulasi Penyusutan Aset Tetap

### 2-xxxx LIABILITAS (LIABILITIES)
- `2-1101` Titipan Tabungan Siswa (Liabilitas Jangka Pendek)
- `2-1102` Pendapatan Diterima di Muka (SPP Dibayar di Depan)
- `2-1103` Hutang Gaji & Honor Guru Tidak Tetap (GTT)
- `2-1201` Hutang Pengadaan Sarana / Vendor Laboratorium

### 3-xxxx ASET NETO (ISAK 35 NET ASSETS)
- `3-1101` Aset Neto Tanpa Pembatasan dari Pemberi Sumber Daya (Dana Yayasan & Akumulasi Surplus)
- `3-1201` Aset Neto Dengan Pembatasan (Sisa Dana Hibah / BOS Belum Terserap)

### 4-xxxx PENDAPATAN (REVENUE)
- `4-1101` Pendapatan SPP Siswa
- `4-1102` Pendapatan Uang Pangkal / Pendaftaran
- `4-1103` Pendapatan Biaya Praktik Kejuruan & PKL SMK
- `4-1104` Pendapatan Uji Kompetensi Keahlian (UKK)
- `4-2101` Pendapatan Hibah Dana BOS Reguler
- `4-2102` Pendapatan Hibah Dana BOS Kinerja / Afirmasi
- `4-3101` Pendapatan Bunga Bank & Jasa Giro

### 5-xxxx BEBAN PENDIDIKAN & OPERASIONAL (EXPENSES)
- `5-1101` Beban Gaji & Honorarium Pendidik / Tenaga Kependidikan
- `5-1201` Beban Praktik & Bahan Pembelajaran Bengkel/Lab SMK
- `5-1202` Beban Pelaksanaan Ujian (PTS, PAS, Asesmen Nasional, UKK)
- `5-1203` Beban Kegiatan Ekstrakurikuler & OSIS
- `5-1301` Beban Langganan Daya & Jasa (Listrik PLN, Air PDAM, Internet Fiber Optic)
- `5-1302` Beban Pemeliharaan Sarana Gedung & Komputer
- `5-1401` Beban Alat Tulis Kantor (ATK) & Fotokopi Soal
- `5-2101` Beban Penyusutan Peralatan & Gedung

---

## 4. BUKU KAS UMUM (BKU) DAN SPJ SEKOLAH
1. **Format Buku Kas Umum (BKU):**
   - Kolom: Nomor Bukti, Tanggal, Uraian Transaksi, Kode Rekening/Akun, Penerimaan (Debit), Pengeluaran (Kredit), dan Saldo Kumulatif.
   - Filter Wajib: Sumber Dana (`Semua`, `BOS`, `SPP_YAYASAN`), Periode Bulan/Tahun Ajaran.
   - Penandatanganan: Mengetahui Kepala Sekolah (NIP) dan Bendahara Sekolah (NIP).
2. **Surat Pertanggungjawaban (SPJ):**
   - Bukti rincian pengeluaran per kegiatan program RKAS lengkap dengan lampiran nota riil, kuitansi bermeterai (jika di atas batas ketentuan), dan berita acara serah terima barang (BAST).

