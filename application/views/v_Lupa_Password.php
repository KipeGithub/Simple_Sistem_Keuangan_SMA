<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Lupa Kata Sandi & Pemulihan Akun | SMK DUMMY KIPE</title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="<?= base_url('assets/') ?>bower_components/bootstrap/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?= base_url('assets/') ?>bower_components/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="<?= base_url('assets/') ?>dist/css/AdminLTE.min.css">
  <link rel="shorcut icon" type="text/css" href="<?= base_url('assets/dist/img/tut_wuri_handayani.png') ?>">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  <style>
    body {
      background: linear-gradient(135deg, #0F294A 0%, #1e3a5f 50%, #0D6E52 100%);
      font-family: 'Source Sans Pro', sans-serif;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0;
      padding: 20px 0;
    }
    .recovery-box {
      width: 100%;
      max-width: 480px;
      margin: auto;
    }
    .recovery-box-body {
      background: #ffffff;
      padding: 30px 25px;
      border-radius: 12px;
      box-shadow: 0 15px 35px rgba(0,0,0,0.25);
    }
    .school-logo {
      text-align: center;
      margin-bottom: 20px;
    }
    .school-logo img {
      width: 65px;
      height: auto;
      filter: drop-shadow(0 4px 6px rgba(0,0,0,0.15));
    }
    .school-title {
      font-size: 19px;
      font-weight: 700;
      color: #0F294A;
      margin-top: 10px;
      margin-bottom: 3px;
      letter-spacing: -0.3px;
      text-align: center;
    }
    .school-subtitle {
      font-size: 12px;
      color: #64748b;
      margin-bottom: 20px;
      text-align: center;
    }
    .btn-google-recovery {
      background: #ffffff;
      color: #1e293b;
      border: 1px solid #cbd5e1;
      font-weight: 600;
      padding: 12px;
      border-radius: 8px;
      font-size: 14px;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      transition: all 0.2s ease;
      text-decoration: none !important;
      box-shadow: 0 2px 4px rgba(0,0,0,0.05);
      margin-bottom: 20px;
    }
    .btn-google-recovery:hover {
      background: #f8fafc;
      border-color: #94a3b8;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
      color: #0F294A;
    }
    .btn-primary-custom {
      background-color: #0F294A;
      border-color: #0F294A;
      color: #ffffff;
      font-weight: 600;
      padding: 11px;
      border-radius: 8px;
      font-size: 14px;
      transition: all 0.2s ease;
    }
    .btn-primary-custom:hover {
      background-color: #1a3d68;
      border-color: #1a3d68;
      color: #ffffff;
    }
    .divider-text {
      text-align: center;
      position: relative;
      margin: 20px 0;
    }
    .divider-text:before {
      content: "";
      position: absolute;
      top: 50%;
      left: 0;
      right: 0;
      height: 1px;
      background: #e2e8f0;
      z-index: 1;
    }
    .divider-text span {
      background: #fff;
      padding: 0 15px;
      position: relative;
      z-index: 2;
      color: #94a3b8;
      font-size: 12px;
      font-weight: 600;
      text-transform: uppercase;
    }
  </style>
</head>
<body>
<div class="recovery-box">
  <div class="recovery-box-body">
    <div class="school-logo">
      <img src="<?= base_url('assets/dist/img/tut_wuri_handayani.png') ?>" alt="Logo Tut Wuri Handayani">
      <div class="school-title">SMK DUMMY KIPE</div>
      <div class="school-subtitle">Pemulihan Akses Akun & Pengaturan Ulang Kata Sandi</div>
    </div>

    <?= $this->session->flashdata('message'); ?>

    <!-- FORM PEMULIHAN STANDAR -->
    <form action="<?= base_url('Auth/proses_lupa_password') ?>" method="post">
      <div class="form-group has-feedback">
        <label style="font-size: 13px; color: #334155; font-weight: 600;">Alamat Email Akun Terdaftar</label>
        <input type="email" class="form-control" name="email" required placeholder="Contoh: nama@sekolah.sch.id" style="border-radius: 8px; height: 42px;">
        <span class="glyphicon glyphicon-envelope form-control-feedback" style="top: 25px;"></span>
      </div>
      <button type="submit" class="btn btn-primary-custom btn-block">
        <i class="fa fa-paper-plane"></i> Ajukan Pemulihan Kata Sandi
      </button>
    </form>

    <div style="text-align: center; margin-top: 25px;">
      <a href="<?= base_url('Auth') ?>" style="color: #0F294A; font-weight: 600; font-size: 13px;">
        <i class="fa fa-arrow-left"></i> Kembali ke Halaman Masuk
      </a>
    </div>
  </div>
</div>
</body>
</html>

