<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Pendaftaran Akun Wali Murid / Siswa | SMK DUMMY KIPE</title>
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
      padding: 30px 15px;
    }
    .register-container {
      width: 100%;
      max-width: 480px;
      margin: auto;
    }
    .register-card {
      background: #ffffff;
      padding: 32px 28px;
      border-radius: 14px;
      box-shadow: 0 20px 40px rgba(0,0,0,0.3);
      border-top: 5px solid #0D6E52;
    }
    .school-header {
      text-align: center;
      margin-bottom: 24px;
    }
    .school-header img {
      width: 65px;
      height: auto;
      margin-bottom: 12px;
      filter: drop-shadow(0 4px 6px rgba(0,0,0,0.15));
    }
    .school-title {
      font-size: 19px;
      font-weight: 700;
      color: #0F294A;
      margin: 0 0 4px 0;
      letter-spacing: -0.3px;
    }
    .school-subtitle {
      font-size: 12.5px;
      color: #64748b;
      margin: 0;
      line-height: 1.4;
    }
    .btn-google {
      background: #ffffff;
      color: #1e293b;
      border: 1px solid #cbd5e1;
      font-weight: 600;
      padding: 10px 14px;
      border-radius: 8px;
      font-size: 13.5px;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      transition: all 0.2s ease;
      text-decoration: none !important;
      box-shadow: 0 2px 4px rgba(0,0,0,0.04);
      margin-bottom: 18px;
    }
    .btn-google:hover {
      background: #f8fafc;
      border-color: #94a3b8;
      box-shadow: 0 4px 10px rgba(0,0,0,0.08);
      color: #0F294A;
    }
    .divider-text {
      text-align: center;
      position: relative;
      margin: 18px 0;
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
      padding: 0 14px;
      position: relative;
      z-index: 2;
      color: #94a3b8;
      font-size: 11.5px;
      font-weight: 600;
      text-transform: uppercase;
    }
    .form-control-custom {
      border-radius: 8px;
      height: 42px;
      border: 1px solid #cbd5e1;
      box-shadow: none;
      font-size: 13.5px;
    }
    .btn-submit {
      background: #0D6E52;
      color: #ffffff;
      border: none;
      padding: 11px;
      font-weight: 600;
      border-radius: 8px;
      font-size: 14px;
      transition: background 0.2s;
    }
    .btn-submit:hover {
      background: #0b5942;
      color: #ffffff;
    }
  </style>
</head>
<body>
<div class="register-container">
  <div class="register-card">
    <div class="school-header">
      <img src="<?= base_url('assets/dist/img/tut_wuri_handayani.png') ?>" alt="Logo Tut Wuri Handayani">
      <h1 class="school-title">SMK DUMMY KIPE</h1>
      <p class="school-subtitle">Pendaftaran Akun Mandiri Wali Murid / Siswa</p>
    </div>

    <?= $this->session->flashdata('message'); ?>

    <form action="<?= base_url('Auth/registrasi') ?>" method="post">
      <div class="form-group has-feedback">
        <label style="font-size: 12.5px; font-weight: 600; color: #334155;">Nama Lengkap Wali / Siswa</label>
        <input type="text" class="form-control form-control-custom" autocomplete="off" name="name" value="<?= set_value('name') ?>" placeholder="Nama Orang Tua atau Siswa" required>
        <span class="glyphicon glyphicon-user form-control-feedback" style="top: 25px;"></span>
        <?= form_error('name', '<small class="text-danger">', '</small>'); ?>
      </div>

      <div class="form-group has-feedback">
        <label style="font-size: 12.5px; font-weight: 600; color: #334155;">Alamat Email</label>
        <input type="email" class="form-control form-control-custom" autocomplete="off" name="email" value="<?= set_value('email') ?>" placeholder="wali.siswa@gmail.com" required>
        <span class="glyphicon glyphicon-envelope form-control-feedback" style="top: 25px;"></span>
        <?= form_error('email', '<small class="text-danger">', '</small>'); ?>
      </div>

      <div class="form-group has-feedback">
        <label style="font-size: 12.5px; font-weight: 600; color: #334155;">Kata Sandi</label>
        <input type="password" name="password" class="form-control form-control-custom" placeholder="Minimal 6 karakter" required>
        <span class="glyphicon glyphicon-lock form-control-feedback" style="top: 25px;"></span>
        <?= form_error('password', '<small class="text-danger">', '</small>'); ?>
      </div>

      <div class="form-group has-feedback">
        <label style="font-size: 12.5px; font-weight: 600; color: #334155;">Konfirmasi Kata Sandi</label>
        <input type="password" name="password2" class="form-control form-control-custom" placeholder="Ulangi kata sandi" required>
        <span class="glyphicon glyphicon-lock form-control-feedback" style="top: 25px;"></span>
        <?= form_error('password2', '<small class="text-danger">', '</small>'); ?>
      </div>

      <button type="submit" class="btn btn-submit btn-block">
        <i class="fa fa-user-plus"></i> Selesaikan Pendaftaran Akun
      </button>
    </form>

    <div style="margin-top: 18px; text-align: center; font-size: 13px; color: #64748b;">
      Sudah memiliki akun? <br>
      <a href="<?= base_url('Auth') ?>" style="color: #0F294A; font-weight: 700;">Masuk ke Sistem</a>
    </div>
  </div>
</div>

<script src="<?= base_url('assets/') ?>bower_components/jquery/dist/jquery.min.js"></script>
<script src="<?= base_url('assets/') ?>bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
</body>
</html>
