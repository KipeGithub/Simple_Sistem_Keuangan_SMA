<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Atur Ulang Kata Sandi Baru | SMK DUMMY KIPE</title>
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
    .recovery-box { width: 100%; max-width: 480px; margin: auto; }
    .recovery-box-body {
      background: #ffffff; padding: 30px 25px;
      border-radius: 12px; box-shadow: 0 15px 35px rgba(0,0,0,0.25);
    }
    .school-logo { text-align: center; margin-bottom: 20px; }
    .school-logo img { width: 65px; height: auto; }
    .school-title { font-size: 19px; font-weight: 700; color: #0F294A; margin-top: 10px; margin-bottom: 3px; text-align: center; }
    .school-subtitle { font-size: 12px; color: #64748b; margin-bottom: 20px; text-align: center; }
    .btn-primary-custom {
      background-color: #0D6E52; border-color: #0D6E52; color: #ffffff;
      font-weight: 600; padding: 11px; border-radius: 8px; font-size: 14px;
    }
    .btn-primary-custom:hover { background-color: #0b5942; border-color: #0b5942; color: #fff; }
  </style>
</head>
<body>
<div class="recovery-box">
  <div class="recovery-box-body">
    <div class="school-logo">
      <img src="<?= base_url('assets/dist/img/tut_wuri_handayani.png') ?>" alt="Logo Tut Wuri Handayani">
      <div class="school-title">SMK DUMMY KIPE</div>
      <div class="school-subtitle">Buat Kata Sandi Baru Akun Anda</div>
    </div>

    <?= $this->session->flashdata('message'); ?>

    <form action="<?= base_url('Auth/simpan_reset_password') ?>" method="post">
      <div class="form-group has-feedback">
        <label style="font-size: 13px; color: #334155; font-weight: 600;">Kata Sandi Baru</label>
        <input type="password" name="password" required minlength="6" class="form-control" placeholder="Minimal 6 karakter..." style="border-radius: 8px; height: 42px;">
        <span class="glyphicon glyphicon-lock form-control-feedback" style="top: 25px;"></span>
      </div>
      <div class="form-group has-feedback">
        <label style="font-size: 13px; color: #334155; font-weight: 600;">Konfirmasi Kata Sandi Baru</label>
        <input type="password" name="password2" required minlength="6" class="form-control" placeholder="Ketik ulang kata sandi baru..." style="border-radius: 8px; height: 42px;">
        <span class="glyphicon glyphicon-lock form-control-feedback" style="top: 25px;"></span>
      </div>
      <button type="submit" class="btn btn-primary-custom btn-block">
        <i class="fa fa-save"></i> Simpan Kata Sandi Baru & Masuk
      </button>
    </form>

    <div style="text-align: center; margin-top: 25px;">
      <a href="<?= base_url('Auth') ?>" style="color: #0F294A; font-weight: 600; font-size: 13px;">
        <i class="fa fa-arrow-left"></i> Batal & Kembali ke Halaman Masuk
      </a>
    </div>
  </div>
</div>
</body>
</html>

