<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Masuk Sistem Informasi Keuangan | SMK DUMMY KIPE</title>
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
    .login-container {
      width: 100%;
      max-width: 460px;
      margin: auto;
    }
    .login-card {
      background: #ffffff;
      padding: 32px 28px;
      border-radius: 14px;
      box-shadow: 0 20px 40px rgba(0,0,0,0.3);
      border-top: 5px solid #0F294A;
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
    .form-control-custom:focus {
      border-color: #0F294A;
      box-shadow: 0 0 0 2px rgba(15,41,74,0.15);
    }
    .btn-submit {
      background: #0F294A;
      color: #ffffff;
      border: none;
      padding: 11px;
      font-weight: 600;
      border-radius: 8px;
      font-size: 14px;
      transition: background 0.2s;
    }
    .btn-submit:hover {
      background: #1a3d68;
      color: #ffffff;
    }
    .demo-box {
      margin-top: 22px;
      background: #f8fafc;
      border: 1px solid #e2e8f0;
      border-radius: 8px;
      padding: 14px;
      font-size: 11.5px;
      color: #475569;
    }
    .demo-box strong { color: #0F294A; }
    .demo-btn {
      display: inline-block;
      padding: 2px 7px;
      margin: 2px;
      background: #e2e8f0;
      border-radius: 4px;
      cursor: pointer;
      font-size: 11px;
      color: #334155;
      font-weight: 600;
    }
    .demo-btn:hover { background: #cbd5e1; }
  </style>
</head>
<body>
<div class="login-container">
  <div class="login-card">
    <div class="school-header">
      <img src="<?= base_url('assets/dist/img/tut_wuri_handayani.png') ?>" alt="Logo Tut Wuri Handayani">
      <h1 class="school-title">SMK DUMMY KIPE</h1>
      <p class="school-subtitle">Sistem Informasi Keuangan & Pembukuan Akuntansi Sekolah</p>
    </div>

    <?= $this->session->flashdata('message'); ?>

    <!-- FORM LOGIN STANDAR SEKOLAH (EMAIL & PASSWORD) -->
    <form action="<?= base_url('Auth') ?>" method="post">
      <div class="form-group has-feedback">
        <label style="font-size: 12.5px; font-weight: 600; color: #334155;">Alamat Email</label>
        <input type="email" class="form-control form-control-custom" autocomplete="off" value="<?= set_value('email') ?>" name="email" id="email" placeholder="nama@sekolah.sch.id" required>
        <span class="glyphicon glyphicon-envelope form-control-feedback" style="top: 25px;"></span>
        <?= form_error('email', '<small class="text-danger">', '</small>'); ?>
      </div>
      <div class="form-group has-feedback">
        <div style="display: flex; justify-content: space-between; align-items: center;">
          <label style="font-size: 12.5px; font-weight: 600; color: #334155;">Kata Sandi</label>
          <a href="<?= base_url('Auth/lupa_password') ?>" style="font-size: 11.5px; color: #0D6E52; font-weight: 600;">Lupa Sandi?</a>
        </div>
        <input type="password" name="password" id="password" class="form-control form-control-custom" placeholder="••••••••" required>
        <span class="glyphicon glyphicon-lock form-control-feedback" style="top: 25px;"></span>
        <?= form_error('password', '<small class="text-danger">', '</small>'); ?>
      </div>

      <button type="submit" class="btn btn-submit btn-block">
        <i class="fa fa-sign-in"></i> Masuk ke Sistem
      </button>
    </form>

    <div style="margin-top: 18px; text-align: center; font-size: 13px; color: #64748b;">
      Belum memiliki akun Wali Murid? <br>
      <a href="<?= base_url('Auth/registrasi') ?>" style="color: #0F294A; font-weight: 700;">Daftar Akun Baru Siswa / Wali</a>
    </div>

    <!-- KARTU AKUN DEMO RESMI UNTUK UJI COBA -->
    <div class="demo-box">
      <strong><i class="fa fa-key"></i> Akun Pengujian Peran Sekolah (Password: <code>password</code>):</strong><br>
      <div style="margin-top: 5px;">
        <span class="demo-btn" onclick="fillLogin('admin@smkn1akuntansi.sch.id', 'password')">Admin TU</span>
        <span class="demo-btn" onclick="fillLogin('bendahara@smkn1akuntansi.sch.id', 'password')">Bendahara</span>
        <span class="demo-btn" onclick="fillLogin('kepsek@smkn1akuntansi.sch.id', 'password')">Kepala Sekolah</span>
        <span class="demo-btn" onclick="fillLogin('wali.rizky@gmail.com', 'password')">Wali Murid</span>
      </div>
    </div>
  </div>
</div>

<script src="<?= base_url('assets/') ?>bower_components/jquery/dist/jquery.min.js"></script>
<script src="<?= base_url('assets/') ?>bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script>
  function fillLogin(email, pass) {
    $('#email').val(email);
    $('#password').val(pass);
  }
</script>
</body>
</html>
