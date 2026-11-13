<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title><?= $title ?></title>
  <link rel="stylesheet" href="<?= base_url('assets/') ?>bower_components/bootstrap/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?= base_url('assets/') ?>bower_components/font-awesome/css/font-awesome.min.css">
  <link rel="shorcut icon" type="text/css" href="<?= base_url('assets/dist/img/tut_wuri_handayani.png') ?>">
  <style>
    body {
      background: #f1f5f9;
      font-family: 'Times New Roman', Times, serif;
      color: #000;
      padding: 20px;
    }
    .kuitansi-wrapper {
      max-width: 800px;
      margin: 0 auto;
      background: #fff;
      padding: 40px 50px;
      border: 1px solid #cbd5e1;
      box-shadow: 0 4px 15px rgba(0,0,0,0.08);
      position: relative;
    }
    .kop-surat {
      display: flex;
      align-items: center;
      border-bottom: 3px double #000;
      padding-bottom: 12px;
      margin-bottom: 25px;
    }
    .kop-logo {
      width: 85px;
      height: auto;
      margin-right: 20px;
    }
    .kop-text {
      flex: 1;
      text-align: center;
    }
    .kop-text h4 {
      margin: 0;
      font-size: 14px;
      font-weight: normal;
      letter-spacing: 1px;
      text-transform: uppercase;
    }
    .kop-text h2 {
      margin: 3px 0;
      font-size: 20px;
      font-weight: bold;
      color: #0F294A;
      letter-spacing: 0.5px;
    }
    .kop-text p {
      margin: 2px 0 0;
      font-size: 11px;
      font-family: Arial, sans-serif;
      color: #333;
    }
    .kuitansi-title {
      text-align: center;
      margin-bottom: 25px;
    }
    .kuitansi-title h3 {
      margin: 0;
      font-size: 18px;
      font-weight: bold;
      text-decoration: underline;
      letter-spacing: 1px;
    }
    .kuitansi-title span {
      font-family: Arial, sans-serif;
      font-size: 12px;
      color: #333;
    }
    .table-data {
      width: 100%;
      font-size: 13.5px;
      line-height: 1.8;
      margin-bottom: 25px;
    }
    .table-data td {
      vertical-align: top;
      padding: 4px 0;
    }
    .nominal-box {
      border: 2px solid #0F294A;
      background: #f8fafc;
      padding: 10px 20px;
      display: inline-block;
      font-family: Arial, sans-serif;
      font-size: 20px;
      font-weight: bold;
      color: #0F294A;
      letter-spacing: 1px;
    }
    .terbilang-box {
      font-style: italic;
      background: #f1f5f9;
      padding: 6px 12px;
      border-radius: 4px;
      display: inline-block;
      font-size: 12.5px;
    }
    .signature-section {
      margin-top: 30px;
      display: flex;
      justify-content: space-between;
      text-align: center;
      font-size: 13px;
    }
    .signature-box {
      width: 250px;
    }
    .no-print {
      max-width: 800px;
      margin: 0 auto 20px;
      display: flex;
      justify-content: space-between;
    }
    @media print {
      body { background: #fff; padding: 0; }
      .kuitansi-wrapper { border: none; box-shadow: none; padding: 20px; }
      .no-print { display: none !important; }
    }
  </style>
</head>
<body>

<div class="no-print">
  <a href="<?= base_url('School_finance/tagihan') ?>" class="btn btn-default"><i class="fa fa-arrow-left"></i> Kembali ke Tagihan</a>
  <button onclick="window.print()" class="btn btn-primary" style="background: #0F294A; border-color: #0F294A;"><i class="fa fa-print"></i> Cetak Kuitansi Resmi (PDF)</button>
</div>

<div class="kuitansi-wrapper">
  <!-- KOP SURAT RESMI SEKOLAH -->
  <div class="kop-surat">
    <img src="<?= base_url('assets/dist/img/tut_wuri_handayani.png') ?>" class="kop-logo" alt="Tut Wuri Handayani">
    <div class="kop-text">
      <h4>DINAS PENDIDIKAN</h4>
      <h2>SMK DUMMY KIPE</h2>
      <p>Kompetensi Keahlian: Rekayasa Perangkat Lunak | Teknik Komputer & Jaringan | Akuntansi Lembaga | Otomotif</p>
      <p>Jl. Pendidikan Sekolah No. 10 | Telp: (021) 7890123 | Email: tatausaha@smkdummykipe.sch.id</p>
    </div>
  </div>

  <!-- JUDUL KUITANSI -->
  <div class="kuitansi-title">
    <h3>KUITANSI PEMBAYARAN PENDIDIKAN</h3>
    <span>Nomor Bukti Kas: <strong><?= $payment['receipt_number'] ?></strong></span>
  </div>

  <!-- ISI KUITANSI -->
  <table class="table-data">
    <tr>
      <td width="220">Telah Terima Dari</td>
      <td width="15">:</td>
      <td><strong><?= htmlspecialchars($payment['student_name']) ?></strong> (NIS: <?= $payment['nis'] ?> / NISN: <?= $payment['nisn'] ?: '-' ?>)</td>
    </tr>
    <tr>
      <td>Nama Orang Tua / Wali</td>
      <td>:</td>
      <td><?= htmlspecialchars($payment['wali'] ?: '-') ?></td>
    </tr>
    <tr>
      <td>Kelas / Kompetensi Keahlian</td>
      <td>:</td>
      <td><?= $payment['class_name'] ?> - <?= $payment['major_name'] ?> (<?= $payment['major_code'] ?>)</td>
    </tr>
    <tr>
      <td>Untuk Pembayaran</td>
      <td>:</td>
      <td><strong><?= htmlspecialchars($payment['bill_title']) ?></strong> (<?= htmlspecialchars($payment['note']) ?>)</td>
    </tr>
    <tr>
      <td>Metode Pembayaran</td>
      <td>:</td>
      <td><?= $payment['payment_method'] ?> - Disetor ke: <?= $payment['bank_account_name'] ?: 'Kas Sekolah' ?></td>
    </tr>
    <tr>
      <td>Jumlah Uang</td>
      <td>:</td>
      <td>
        <div class="nominal-box">
          <?= rupiah($payment['amount']) ?>
        </div>
      </td>
    </tr>
  </table>

  <!-- TANDA TANGAN BUKTI PEMBAYARAN -->
  <div class="signature-section">
    <div class="signature-box">
      <p>Mengetahui / Penyetor,<br>Siswa / Orang Tua Wali</p>
      <div style="height: 65px;"></div>
      <p><strong>( <?= htmlspecialchars($payment['student_name']) ?> )</strong></p>
    </div>

    <div class="signature-box">
      <p>Jakarta, <?= date('d F Y', strtotime($payment['payment_date'])) ?><br>Bendahara Penerima Sekolah,</p>
      <div style="height: 65px; position: relative;">
        <span style="display: inline-block; border: 2px solid #0D6E52; color: #0D6E52; padding: 2px 8px; font-weight: bold; font-size: 11px; transform: rotate(-8deg); position: absolute; left: 60px; top: 15px; border-radius: 4px;">
          LUNAS TERCATAT
        </span>
      </div>
      <p><strong>( <?= htmlspecialchars($payment['received_by']) ?> )</strong><br><small>NIP. 19850412 201001 2 025</small></p>
    </div>
  </div>

  <div style="margin-top: 35px; border-top: 1px dashed #cbd5e1; padding-top: 8px; font-family: Arial, sans-serif; font-size: 10px; color: #64748b; text-align: center;">
    <em>Kuitansi ini adalah bukti pembayaran yang sah yang diterbitkan oleh Sistem Informasi Keuangan SMK DUMMY KIPE.</em>
  </div>
</div>

</body>
</html>

