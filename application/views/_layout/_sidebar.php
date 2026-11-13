<?php 
  $aktif = $this->uri->segment(1);
  $sub   = $this->uri->segment(2);
  $role  = (int) $this->session->userdata('role');
?>
<ul class="sidebar-menu" data-widget="tree">

  <?php if ($role == 4): ?>
  <!-- KHUSUS PERAN WALI MURID / SISWA -->
  <li class="header" style="color: #94a3b8; font-weight: 700; font-size: 11px;">PORTAL SISWA & WALI</li>
  <li class="<?= ($aktif == 'School_finance' && $sub == 'portal') ? 'active' : '' ?>">
    <a href="<?= base_url('School_finance/portal') ?>">
      <i class="fa fa-user-circle" style="color: #38bdf8;"></i> <span>Tagihan & Tabungan Saya</span>
    </a>
  </li>

  <?php else: ?>
  <!-- PERAN SEKOLAH (ADMIN TU, BENDAHARA, KEPALA SEKOLAH) -->
  <li class="header" style="color: #94a3b8; font-weight: 700; font-size: 11px;">MENU UTAMA SEKOLAH</li>

  <!-- 1. DASBOR -->
  <li class="<?= ($aktif == 'School_finance' && ($sub == 'dasbor' || empty($sub))) || $aktif == 'Beranda' ? 'active' : '' ?>">
    <a href="<?= base_url('School_finance/dasbor') ?>">
      <i class="fa fa-dashboard" style="color: #38bdf8;"></i> <span>Dasbor</span>
    </a>
  </li>

  <!-- 2. DATA MASTER -->
  <li class="treeview <?= ($aktif == 'School_finance' && $sub == 'siswa') || $aktif == 'Guru' || $aktif == 'Kelas' || $aktif == 'Pengguna' ? 'active' : '' ?>">
    <a href="#">
      <i class="fa fa-database" style="color: #4ade80;"></i> <span>Data Master</span>
      <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
    </a>
    <ul class="treeview-menu">
      <li class="<?= ($aktif == 'School_finance' && $sub == 'siswa') ? 'active' : '' ?>">
        <a href="<?= base_url('School_finance/siswa') ?>"><i class="fa fa-circle-o"></i> Data Siswa</a>
      </li>
      <li class="<?= ($aktif == 'Kelas') ? 'active' : '' ?>">
        <a href="<?= base_url('Kelas') ?>"><i class="fa fa-circle-o"></i> Data Kelas</a>
      </li>
      <li class="<?= ($aktif == 'Guru') ? 'active' : '' ?>">
        <a href="<?= base_url('Guru') ?>"><i class="fa fa-circle-o"></i> Data Guru</a>
      </li>
      <?php if ($role == 1): ?>
      <!-- Khusus Admin TU: Manajemen Akun Pengguna -->
      <li class="<?= ($aktif == 'Pengguna') ? 'active' : '' ?>">
        <a href="<?= base_url('Pengguna') ?>" style="color: #facc15;"><i class="fa fa-user-plus"></i> Manajemen Pengguna</a>
      </li>
      <?php endif; ?>
    </ul>
  </li>

  <!-- 3. KAS MASUK (Semua Pemasukan Sekolah) -->
  <li class="treeview <?= ($aktif == 'School_finance' && ($sub == 'tagihan' || $sub == 'bayar' || $sub == 'tabungan' || $sub == 'buku_tabungan')) || $aktif == 'Pendaftaran' || $aktif == 'Lainnya' ? 'active' : '' ?>">
    <a href="#">
      <i class="fa fa-arrow-circle-down" style="color: #22c55e;"></i> <span>Kas Masuk</span>
      <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
    </a>
    <ul class="treeview-menu">
      <li class="<?= ($aktif == 'School_finance' && ($sub == 'tagihan' || $sub == 'bayar')) ? 'active' : '' ?>">
        <a href="<?= base_url('School_finance/tagihan') ?>"><i class="fa fa-circle-o"></i> Pembayaran SPP Siswa</a>
      </li>
      <li class="<?= ($aktif == 'School_finance' && ($sub == 'tabungan' || $sub == 'buku_tabungan')) ? 'active' : '' ?>">
        <a href="<?= base_url('School_finance/tabungan') ?>"><i class="fa fa-circle-o"></i> Tabungan Siswa</a>
      </li>
      <li class="<?= ($aktif == 'Pendaftaran') ? 'active' : '' ?>">
        <a href="<?= base_url('Pendaftaran') ?>"><i class="fa fa-circle-o"></i> Uang Pendaftaran</a>
      </li>
      <li class="<?= ($aktif == 'Lainnya') ? 'active' : '' ?>">
        <a href="<?= base_url('Lainnya') ?>"><i class="fa fa-circle-o"></i> Kas Masuk Lainnya</a>
      </li>
    </ul>
  </li>

  <!-- 4. KAS KELUAR (Semua Pengeluaran Sekolah) -->
  <li class="treeview <?= $aktif == 'Gaji' || $aktif == 'Pengeluaran' ? 'active' : '' ?>">
    <a href="#">
      <i class="fa fa-arrow-circle-up" style="color: #f87171;"></i> <span>Kas Keluar</span>
      <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
    </a>
    <ul class="treeview-menu">
      <li class="<?= ($aktif == 'Gaji') ? 'active' : '' ?>">
        <a href="<?= base_url('Gaji') ?>"><i class="fa fa-circle-o"></i> Gaji & Honor Guru</a>
      </li>
      <li class="<?= ($aktif == 'Pengeluaran') ? 'active' : '' ?>">
        <a href="<?= base_url('Pengeluaran') ?>"><i class="fa fa-circle-o"></i> Pengeluaran Operasional</a>
      </li>
    </ul>
  </li>

  <!-- 5. LAPORAN KEUANGAN -->
  <li class="treeview <?= ($aktif == 'School_finance' && ($sub == 'bku' || $sub == 'tunggakan' || $sub == 'laporan_isak')) || $aktif == 'Laporan' ? 'active' : '' ?>">
    <a href="#">
      <i class="fa fa-file-text-o" style="color: #fb923c;"></i> <span>Laporan Keuangan</span>
      <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
    </a>
    <ul class="treeview-menu">
      <li class="<?= ($aktif == 'School_finance' && $sub == 'laporan_isak') ? 'active' : '' ?>">
        <a href="<?= base_url('School_finance/laporan_isak') ?>"><i class="fa fa-circle-o"></i> Rekap ISAK (Entitas Nonlaba)</a>
      </li>
      <li class="<?= ($aktif == 'School_finance' && $sub == 'bku') ? 'active' : '' ?>">
        <a href="<?= base_url('School_finance/bku') ?>"><i class="fa fa-circle-o"></i> Rekap Kas Masuk & Keluar</a>
      </li>
      <li class="<?= ($aktif == 'School_finance' && $sub == 'tunggakan') ? 'active' : '' ?>">
        <a href="<?= base_url('School_finance/tunggakan') ?>"><i class="fa fa-circle-o"></i> Rekap Tunggakan Siswa</a>
      </li>
    </ul>
  </li>
  <?php endif; ?>

  <li class="header" style="color: #94a3b8; font-weight: 700; font-size: 11px;">AKUN</li>
  <li>
    <a href="<?= base_url('Auth/logout') ?>" style="color: #f87171;">
      <i class="fa fa-sign-out"></i> <span>Keluar Sistem</span>
    </a>
  </li>
</ul>
