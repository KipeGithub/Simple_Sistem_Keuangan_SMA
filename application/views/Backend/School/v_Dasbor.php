<div class="row">
  <!-- 1. SPP Bulan Ini -->
  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box" style="border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.08);">
      <span class="info-box-icon" style="background-color: #0F294A; color: #fff; border-radius: 8px 0 0 8px;"><i class="fa fa-money"></i></span>
      <div class="info-box-content">
        <span class="info-box-text" style="font-size: 11px; font-weight: 600; text-transform: uppercase; color: #64748b;">Penerimaan SPP Bulan Ini</span>
        <span class="info-box-number" style="font-size: 16px; color: #0F294A;"><?= rupiah($summary['spp_bulan_ini']) ?></span>
        <div class="progress" style="height: 3px; margin: 5px 0;">
          <div class="progress-bar" style="width: 100%; background-color: #0F294A;"></div>
        </div>
        <span class="progress-description" style="font-size: 10px; color: #94a3b8;">Bulan <?= date('F Y') ?></span>
      </div>
    </div>
  </div>

  <!-- 2. Realisasi Belanja BOS -->
  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box" style="border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.08);">
      <span class="info-box-icon" style="background-color: #0D6E52; color: #fff; border-radius: 8px 0 0 8px;"><i class="fa fa-shopping-cart"></i></span>
      <div class="info-box-content">
        <span class="info-box-text" style="font-size: 11px; font-weight: 600; text-transform: uppercase; color: #64748b;">Pengeluaran Sekolah</span>
        <span class="info-box-number" style="font-size: 16px; color: #0D6E52;"><?= rupiah($summary['belanja_bos']) ?></span>
        <div class="progress" style="height: 3px; margin: 5px 0;">
          <div class="progress-bar" style="width: 100%; background-color: #0D6E52;"></div>
        </div>
        <span class="progress-description" style="font-size: 10px; color: #94a3b8;">Operasional & Honor</span>
      </div>
    </div>
  </div>

  <!-- 3. Sisa Pagu Anggaran RKAS -->
  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box" style="border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.08);">
      <span class="info-box-icon bg-aqua" style="border-radius: 8px 0 0 8px;"><i class="fa fa-pie-chart"></i></span>
      <div class="info-box-content">
        <span class="info-box-text" style="font-size: 11px; font-weight: 600; text-transform: uppercase; color: #64748b;">Sisa Pagu RKAS</span>
        <span class="info-box-number" style="font-size: 16px; color: #0284c7;"><?= rupiah($summary['sisa_pagu']) ?></span>
        <div class="progress" style="height: 3px; margin: 5px 0;">
          <div class="progress-bar bg-aqua" style="width: 100%;"></div>
        </div>
        <span class="progress-description" style="font-size: 10px; color: #94a3b8;">Dari total <?= rupiah($summary['total_pagu']) ?></span>
      </div>
    </div>
  </div>

  <!-- 4. Total Tunggakan Siswa -->
  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box" style="border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.08);">
      <span class="info-box-icon bg-red" style="border-radius: 8px 0 0 8px;"><i class="fa fa-exclamation-triangle"></i></span>
      <div class="info-box-content">
        <span class="info-box-text" style="font-size: 11px; font-weight: 600; text-transform: uppercase; color: #64748b;">Total Tunggakan Siswa</span>
        <span class="info-box-number" style="font-size: 16px; color: #e11d48;"><?= rupiah($summary['total_tunggakan']) ?></span>
        <div class="progress" style="height: 3px; margin: 5px 0;">
          <div class="progress-bar bg-red" style="width: 100%;"></div>
        </div>
        <span class="progress-description" style="font-size: 10px; color: #94a3b8;">Tagihan Belum Terbayar</span>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <!-- Saldo Rekening Kas & Bank -->
  <div class="col-md-6">
    <div class="box box-primary" style="border-top-color: #0F294A; border-radius: 8px;">
      <div class="box-header with-border">
        <h3 class="box-title" style="font-weight: 700; color: #0F294A; font-size: 15px;"><i class="fa fa-university"></i> Saldo Kas & Rekening Bank Sekolah</h3>
      </div>
      <div class="box-body no-padding">
        <table class="table table-striped table-hover">
          <thead>
            <tr style="background: #f8fafc; color: #475569; font-size: 12px;">
              <th>Kode Akun</th>
              <th>Nama Rekening</th>
              <th>Bank / No. Rek</th>
              <th class="text-right">Saldo Saat Ini</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($summary['rekening_kas'] as $rk): ?>
            <tr>
              <td><code><?= $rk['code'] ?></code></td>
              <td><strong><?= $rk['name'] ?></strong></td>
              <td><small><?= $rk['bank_name'] ?> (<?= $rk['account_number'] ?>)</small></td>
              <td class="text-right font-weight-bold" style="font-weight: 700; color: #0D6E52;"><?= rupiah($rk['balance']) ?></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <div class="box-footer text-muted" style="font-size: 11.5px;">
        <i class="fa fa-info-circle"></i> Pemisahan rekening ketat antara Kas Tunai Bendahara, Operasional SPP/Yayasan, dan Rekening Khusus Dana BOS.
      </div>
    </div>
  </div>

  <!-- 5 Pembayaran Terkini & Akses Cepat -->
  <div class="col-md-6">
    <div class="box box-success" style="border-top-color: #0D6E52; border-radius: 8px;">
      <div class="box-header with-border">
        <h3 class="box-title" style="font-weight: 700; color: #0D6E52; font-size: 15px;"><i class="fa fa-history"></i> Pembayaran SPP & Biaya Sekolah Terkini</h3>
        <div class="box-tools pull-right">
          <a href="<?= base_url('School_finance/tagihan') ?>" class="btn btn-xs btn-default">Lihat Semua Tagihan</a>
        </div>
      </div>
      <div class="box-body no-padding">
        <table class="table table-striped table-hover">
          <thead>
            <tr style="background: #f8fafc; color: #475569; font-size: 12px;">
              <th>Tanggal</th>
              <th>Nama Siswa</th>
              <th>Uraian</th>
              <th class="text-right">Nominal</th>
              <th class="text-center">Kuitansi</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($summary['recent_payments'])): ?>
              <tr><td colspan="5" class="text-center text-muted">Belum ada riwayat pembayaran.</td></tr>
            <?php else: ?>
              <?php foreach ($summary['recent_payments'] as $rp): ?>
              <tr>
                <td><small><?= date('d/m/Y', strtotime($rp['payment_date'])) ?></small></td>
                <td><strong><?= htmlspecialchars($rp['student_name']) ?></strong> <span class="label label-default"><?= $rp['major_code'] ?></span></td>
                <td><small><?= htmlspecialchars($rp['bill_title']) ?></small></td>
                <td class="text-right" style="font-weight: 700; color: #0D6E52;"><?= rupiah($rp['amount']) ?></td>
                <td class="text-center">
                  <a href="<?= base_url('School_finance/kuitansi/' . $rp['id']) ?>" target="_blank" class="btn btn-xs btn-primary" style="background: #0F294A; border: none;" title="Cetak Kuitansi Resmi"><i class="fa fa-print"></i></a>
                </td>
              </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
      <div class="box-footer" style="background: #fafafa;">
        <div class="row text-center">
          <div class="col-xs-4">
            <a href="<?= base_url('School_finance/tagihan') ?>" class="btn btn-sm btn-block btn-default" style="font-size: 11px;"><i class="fa fa-plus-circle"></i> Terbitkan Tagihan</a>
          </div>
          <div class="col-xs-4">
            <a href="<?= base_url('School_finance/tabungan') ?>" class="btn btn-sm btn-block btn-default" style="font-size: 11px;"><i class="fa fa-archive"></i> Tabungan Siswa</a>
          </div>
          <div class="col-xs-4">
            <a href="<?= base_url('School_finance/bku') ?>" class="btn btn-sm btn-block btn-default" style="font-size: 11px;"><i class="fa fa-book"></i> Rekap Kas Sekolah</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

