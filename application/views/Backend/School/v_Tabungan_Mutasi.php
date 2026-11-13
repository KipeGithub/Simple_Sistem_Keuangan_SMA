<div class="row">
  <div class="col-xs-12">
    <!-- Header Profil Tabungan Siswa -->
    <div class="box box-primary" style="border-top-color: #0F294A; border-radius: 8px;">
      <div class="box-body" style="padding: 20px;">
        <div class="row">
          <div class="col-md-8">
            <span class="label label-primary" style="background: #0F294A;">Rekening Tabungan Siswa</span>
            <h3 style="margin: 8px 0 4px; font-weight: 700; color: #0F294A;"><?= htmlspecialchars($student['name']) ?></h3>
            <p style="margin: 0; color: #64748b; font-size: 13px;">
              NIS: <strong><?= $student['nis'] ?></strong> | NISN: <strong><?= $student['nisn'] ?: '-' ?></strong> | 
              Jurusan: <strong><?= $student['major_name'] ?> (<?= $student['major_code'] ?>)</strong> | Kelas: <strong><?= $student['class_name'] ?></strong>
            </p>
          </div>
          <div class="col-md-4 text-right">
            <span style="font-size: 12px; color: #64748b; display: block;">Total Saldo Titipan Tabungan:</span>
            <span style="font-size: 24px; font-weight: 800; color: #0D6E52; display: block;">
              <?= rupiah($tabungan['balance'] ?? 0) ?>
            </span>
            <small class="text-muted">No. Rek: <code><?= $tabungan['account_number'] ?? '-' ?></code></small>
          </div>
        </div>
      </div>
    </div>

    <!-- Riwayat Mutasi Tabungan -->
    <div class="box box-success" style="border-top-color: #0D6E52; border-radius: 8px;">
      <div class="box-header with-border">
        <h3 class="box-title" style="font-weight: 700; color: #0D6E52;"><i class="fa fa-list"></i> Buku Riwayat Mutasi Tabungan (Setor & Tarik)</h3>
        <div class="box-tools pull-right">
          <a href="<?= base_url('School_finance/tabungan') ?>" class="btn btn-xs btn-default"><i class="fa fa-arrow-left"></i> Kembali ke Daftar Tabungan</a>
        </div>
      </div>
      <div class="box-body">
        <div class="table-responsive">
          <table class="table table-bordered table-striped table-hover">
            <thead style="background: #f8fafc; color: #334155; font-size: 12px;">
              <tr>
                <th width="40" class="text-center">No</th>
                <th>Tanggal Transaksi</th>
                <th>No. Bukti Mutasi</th>
                <th>Uraian / Keterangan</th>
                <th class="text-right">Setoran (Masuk)</th>
                <th class="text-right">Penarikan (Keluar)</th>
                <th class="text-right">Saldo Akhir</th>
                <th>Petugas Kasir</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($mutations)): ?>
                <tr><td colspan="8" class="text-center text-muted">Belum ada catatan mutasi tabungan siswa ini.</td></tr>
              <?php else: ?>
                <?php $no = 1; foreach ($mutations as $m): ?>
                <tr>
                  <td class="text-center"><?= $no++ ?></td>
                  <td><?= date('d/m/Y H:i', strtotime($m['transaction_date'])) ?></td>
                  <td><code><?= $m['receipt_number'] ?></code></td>
                  <td><?= htmlspecialchars($m['description']) ?></td>
                  <td class="text-right" style="color: #0D6E52; font-weight: 600;">
                    <?= ($m['transaction_type'] === 'SETOR') ? rupiah($m['amount']) : '-' ?>
                  </td>
                  <td class="text-right" style="color: #e11d48; font-weight: 600;">
                    <?= ($m['transaction_type'] === 'TARIK') ? rupiah($m['amount']) : '-' ?>
                  </td>
                  <td class="text-right font-weight-bold" style="font-weight: 700; color: #0F294A;">
                    <?= rupiah($m['balance_after']) ?>
                  </td>
                  <td><small><?= htmlspecialchars($m['created_by']) ?></small></td>
                </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

