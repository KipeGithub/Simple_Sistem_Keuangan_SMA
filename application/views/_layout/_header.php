<?php
$masuk = $this->db->get_where('users', ['id' => $this->session->userdata('id')])->row_array();
$roleName = 'Wali Murid';
if ($masuk['role'] == 1)
  $roleName = 'Admin TU';
elseif ($masuk['role'] == 2)
  $roleName = 'Bendahara';
elseif ($masuk['role'] == 3)
  $roleName = 'Kepala Sekolah';
?>
<a href="<?= base_url('School_finance/dasbor') ?>" class="logo"
  style="background-color: #0B2039; display: flex; align-items: center; justify-content: center; height: 50px; padding: 0;">
  <!-- Logo mini untuk sidebar collapsed -->

  <!-- Logo reguler untuk sidebar expanded (proporsional dan lebih besar) -->
  <span class="logo-lg" style="display: flex; align-items: center; justify-content: center; width: 100%; height: 100%;">
    <img src="<?= base_url('assets/dist/img/tut_wuri_handayani.png') ?>" alt="Logo Tut Wuri Handayani"
      style="height: 48px; max-height: 48px; width: auto; object-fit: contain;">
  </span>
</a>
<nav class="navbar navbar-static-top" style="background-color: #0F294A;">
  <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
    <span class="sr-only">Toggle navigation</span>
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
  </a>

  <!-- Badge Tahun Ajaran Aktif (Tulisan SMK DUMMY KIPE dihapus sesuai instruksi) -->
  <div class="navbar-custom-menu pull-left" style="padding-top: 13px; margin-left: 10px;">
    <span class="label"
      style="background: rgba(255,255,255,0.15); font-size: 12px; font-weight: 600; padding: 6px 14px; border-radius: 20px; border: 1px solid rgba(255,255,255,0.25);">
      <i class="fa fa-calendar-check-o"></i> Tahun Ajaran: 2026/2027 (Ganjil)
    </span>
  </div>

  <div class="navbar-custom-menu">
    <ul class="nav navbar-nav">
      <li class="dropdown user user-menu">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
          <img src="<?= base_url('assets/dist/img/user.png') ?>" class="user-image" alt="User Image">
          <span class="hidden-xs" style="font-weight: 600;">
            <?= htmlspecialchars($masuk['name'] ?? 'Pengguna') ?></span>
          <i class="fa fa-angle-down" style="margin-left: 4px;"></i>
        </a>
        <ul class="dropdown-menu" style="border-radius: 8px; box-shadow: 0 4px 20px rgba(0,0,0,0.15);">
          <li class="user-header" style="background-color: #0F294A; border-radius: 8px 8px 0 0;">
            <img src="<?= base_url('assets/dist/img/user.png') ?>" class="img-circle" alt="User Image">
            <p style="color: #fff; font-weight: 600;">
              <?= htmlspecialchars($masuk['name'] ?? '') ?><br>
              <small style="color: #94a3b8;"><?= $roleName ?> (<?= htmlspecialchars($masuk['email'] ?? '') ?>)</small>
            </p>
          </li>
          <li class="user-footer" style="background-color: #f8fafc; border-radius: 0 0 8px 8px;">
            <div class="pull-right">
              <a href="<?= base_url('Auth/logout') ?>" class="btn btn-default btn-flat"
                style="border-radius: 4px; font-weight: 600; color: #dc2626;"><i class="fa fa-sign-out"></i> Keluar
                Sistem</a>
            </div>
          </li>
        </ul>
      </li>
    </ul>
  </div>
</nav>