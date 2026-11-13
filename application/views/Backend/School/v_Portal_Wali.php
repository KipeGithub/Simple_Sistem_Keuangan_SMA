<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title><?= $title ?></title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="<?= base_url('assets/') ?>bower_components/bootstrap/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?= base_url('assets/') ?>bower_components/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="<?= base_url('assets/') ?>dist/css/AdminLTE.min.css">
  <link rel="shorcut icon" type="text/css" href="<?= base_url('assets/dist/img/tut_wuri_handayani.png') ?>">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  <style>
    body { background: #f0f4f8; font-family: 'Source Sans Pro', sans-serif; }
    .portal-header {
      background: linear-gradient(135deg, #0F294A 0%, #1e3a5f 100%);
      color: #fff; padding: 25px 0; border-bottom: 4px solid #0D6E52;
    }
    .portal-header .brand-title {
      font-size: 20px; font-weight: 700; margin: 0;
    }
    .portal-header .brand-sub {
      font-size: 13px; color: #cbd5e1; margin: 2px 0 0;
    }
    .card-smk {
      background: #fff; border-radius: 10px; box-shadow: 0 4px 15px rgba(15,41,74,0.08);
      margin-bottom: 25px; border: 1px solid #e2e8f0; overflow: hidden;
    }
    .card-smk-header {
      padding: 16px 20px; border-bottom: 1px solid #e2e8f0;
      font-weight: 700; font-size: 15px; color: #0F294A; display: flex;
      justify-content: space-between; align-items: center;
    }
    .card-smk-body { padding: 20px; }
  </style>
</head>
<body>

<header class="portal-header">
  <div class="container">
    <div class="row" style="display: flex; align-items: center; justify-content: space-between;">
      <div class="col-sm-8" style="display: flex; align-items: center; gap: 15px;">
        <img src="<?= base_url('assets/dist/img/tut_wuri_handayani.png') ?>" style="width: 50px; height: auto;" alt="Logo">
        <div>
          <h1 class="brand-title">PORTAL KEUANGAN SISWA & WALI MURID</h1>
          <p class="brand-sub">SMK DUMMY KIPE</p>
        </div>
      </div>
      <div class="col-sm-4 text-right">
        <span style="font-size: 13px; margin-right: 15px;">
          <i class="fa fa-user-circle"></i> <?= htmlspecialchars($this->session->userdata('name')) ?>
        </span>
        <a href="<?= base_url('Auth/logout') ?>" class="btn btn-sm btn-danger" style="background: #e11d48; border: none; border-radius: 6px;">
          <i class="fa fa-sign-out"></i> Keluar
        </a>
      </div>
    </div>
  </div>
</header>

<div class="container" style="margin-top: 25px; margin-bottom: 50px;">
  <?= $this->session->flashdata('message'); ?>

  <?php if (!$student): ?>
    <div class="alert alert-warning" style="border-radius: 8px;">
      <h4><i class="fa fa-warning"></i> Data Siswa Belum Terhubung!</h4>
      <p>Akun Anda (<code><?= htmlspecialchars($this->session->userdata('email')) ?></code>) belum terhubung secara otomatis ke data siswa mana pun. Silakan hubungi bagian Tata Usaha sekolah untuk mendaftarkan alamat email ini ke data orang tua siswa.</p>
    </div>
  <?php else: ?>
    <!-- 1. IDENTITAS SISWA & RINGKASAN SALDO TABUNGAN -->
    <div class="card-smk">
      <div style="background: #0F294A; color: #fff; padding: 20px 25px;">
        <div class="row" style="display: flex; align-items: center; justify-content: space-between;">
          <div class="col-sm-8">
            <span class="label label-success" style="background: #0D6E52; font-size: 11px;">SISWA AKTIF SMK</span>
            <h2 style="margin: 8px 0 4px; font-weight: 800; font-size: 24px; color: #fff;"><?= htmlspecialchars($student['name']) ?></h2>
            <p style="margin: 0; font-size: 13px; color: #cbd5e1;">
              NIS: <strong><?= $student['nis'] ?></strong> | NISN: <strong><?= $student['nisn'] ?: '-' ?></strong> | 
              Kelas: <strong><?= $student['class_name'] ?></strong> | Jurusan: <strong><?= $student['major_name'] ?> (<?= $student['major_code'] ?>)</strong>
            </p>
          </div>
          <div class="col-sm-4 text-right">
            <div style="background: rgba(255,255,255,0.1); padding: 12px 18px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.2); display: inline-block;">
              <span style="font-size: 11.5px; color: #cbd5e1; display: block;">Saldo Tabungan Siswa:</span>
              <span style="font-size: 22px; font-weight: 800; color: #6ee7b7; display: block;">
                <?= rupiah($savings['balance'] ?? 0) ?>
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- 2. DAFTAR TAGIHAN SPP & BIAYA SEKOLAH -->
    <div class="card-smk">
      <div class="card-smk-header">
        <span><i class="fa fa-money"></i> Informasi Tagihan SPP & Biaya Pendidikan</span>
      </div>
      <div class="card-smk-body no-padding">
        <div class="table-responsive">
          <table class="table table-striped table-hover" style="margin-bottom: 0;">
            <thead style="background: #f8fafc; color: #334155; font-size: 12px;">
              <tr>
                <th>Uraian Tagihan</th>
                <th>Jatuh Tempo</th>
                <th class="text-right">Nominal Tagihan</th>
                <th class="text-right">Sudah Terbayar</th>
                <th class="text-center">Status</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($bills)): ?>
                <tr><td colspan="5" class="text-center text-muted" style="padding: 20px;">Belum ada catatan tagihan biaya sekolah.</td></tr>
              <?php else: ?>
                <?php foreach ($bills as $b): ?>
                <tr>
                  <td>
                    <strong><?= htmlspecialchars($b['title']) ?></strong><br>
                    <small class="text-muted"><?= $b['academic_year'] ?></small>
                  </td>
                  <td><?= date('d/m/Y', strtotime($b['due_date'])) ?></td>
                  <td class="text-right font-weight-bold" style="font-weight: 700; color: #0F294A;"><?= rupiah($b['net_amount']) ?></td>
                  <td class="text-right" style="color: #0D6E52; font-weight: 600;"><?= rupiah($b['paid_amount']) ?></td>
                  <td class="text-center">
                    <?php if ($b['status'] === 'PAID'): ?>
                      <span class="label label-success" style="background-color: #0D6E52; padding: 4px 10px;">LUNAS</span>
                    <?php elseif ($b['status'] === 'PARTIAL'): ?>
                      <span class="label label-warning" style="padding: 4px 10px;">SEBAGIAN</span>
                    <?php else: ?>
                      <span class="label label-danger" style="padding: 4px 10px;">BELUM LUNAS</span>
                    <?php endif; ?>
                  </td>
                </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- 3. RIWAYAT PEMBAYARAN & KUITANSI RESMI -->
    <div class="card-smk">
      <div class="card-smk-header">
        <span><i class="fa fa-history"></i> Riwayat Pembayaran & Unduh Kuitansi Resmi</span>
      </div>
      <div class="card-smk-body no-padding">
        <div class="table-responsive">
          <table class="table table-striped table-hover" style="margin-bottom: 0;">
            <thead style="background: #f8fafc; color: #334155; font-size: 12px;">
              <tr>
                <th>No. Kuitansi Resmi</th>
                <th>Tanggal Pembayaran</th>
                <th>Uraian Pembayaran</th>
                <th>Metode Bayar</th>
                <th class="text-right">Nominal</th>
                <th class="text-center" width="130">Kuitansi</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($payments)): ?>
                <tr><td colspan="6" class="text-center text-muted" style="padding: 20px;">Belum ada riwayat pembayaran yang tercatat.</td></tr>
              <?php else: ?>
                <?php foreach ($payments as $p): ?>
                <tr>
                  <td><code><?= $p['receipt_number'] ?></code></td>
                  <td><?= date('d/m/Y', strtotime($p['payment_date'])) ?></td>
                  <td><strong><?= htmlspecialchars($p['bill_title']) ?></strong> (<?= htmlspecialchars($p['note']) ?>)</td>
                  <td><span class="label label-default"><?= $p['payment_method'] ?></span></td>
                  <td class="text-right font-weight-bold" style="color: #0D6E52; font-weight: 700;"><?= rupiah($p['amount']) ?></td>
                  <td class="text-center">
                    <a href="<?= base_url('School_finance/kuitansi/' . $p['id']) ?>" target="_blank" class="btn btn-xs btn-primary" style="background: #0F294A; border: none;">
                      <i class="fa fa-print"></i> Cetak Kuitansi
                    </a>
                  </td>
                </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  <?php endif; ?>
</div>

<script src="<?= base_url('assets/') ?>bower_components/jquery/dist/jquery.min.js"></script>
<script src="<?= base_url('assets/') ?>bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
</body>
</html>

