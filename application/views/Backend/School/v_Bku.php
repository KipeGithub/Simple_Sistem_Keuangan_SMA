<div class="row">
  <div class="col-xs-12">
    <div class="box box-primary" style="border-top-color: #0F294A; border-radius: 8px;">
      <div class="box-header with-border">
        <h3 class="box-title" style="font-weight: 700; color: #0F294A;"><i class="fa fa-table"></i> Buku Kas Umum (BKU) Sekolah</h3>
        <div class="box-tools pull-right">
          <button onclick="window.print()" class="btn btn-sm btn-default"><i class="fa fa-print"></i> Cetak BKU</button>
        </div>
      </div>
      <div class="box-body">
        <!-- Filter Sumber Dana -->
        <form method="GET" action="<?= base_url('School_finance/bku') ?>" class="form-inline" style="margin-bottom: 20px;">
          <div class="form-group">
            <label style="margin-right: 10px;">Filter Sumber Dana BKU:</label>
            <select name="fund_source" class="form-control input-sm" onchange="this.form.submit()">
              <option value="SEMUA" <?= ($fundSource === 'SEMUA') ? 'selected' : '' ?>>Semua Sumber Dana (Konsolidasi)</option>
              <option value="DANA_BOS" <?= ($fundSource === 'DANA_BOS' || $fundSource === 'BOS') ? 'selected' : '' ?>>Khusus Rekening Dana BOS</option>
              <option value="YAYASAN_SPP" <?= ($fundSource === 'YAYASAN_SPP') ? 'selected' : '' ?>>Khusus Operasional Yayasan / SPP</option>
            </select>
          </div>
        </form>

        <div class="table-responsive">
          <table class="table table-bordered table-striped table-hover tabel">
            <thead style="background: #0F294A; color: #fff; font-size: 12px;">
              <tr>
                <th width="40" class="text-center">No</th>
                <th>Tanggal</th>
                <th>No. Bukti / Jurnal</th>
                <th>Sumber Dana</th>
                <th>Uraian Transaksi Pembukuan</th>
                <th>Kode Akun</th>
                <th class="text-right">Penerimaan (Debit)</th>
                <th class="text-right">Pengeluaran (Kredit)</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($bku)): ?>
                <tr><td colspan="8" class="text-center text-muted">Belum ada transaksi pada BKU sumber dana ini.</td></tr>
              <?php else: ?>
                <?php 
                  $totalDebit = 0; 
                  $totalCredit = 0; 
                  $no = 1; 
                  foreach ($bku as $row): 
                    $totalDebit += $row['debit'];
                    $totalCredit += $row['credit'];
                ?>
                <tr>
                  <td class="text-center"><?= $no++ ?></td>
                  <td><?= date('d/m/Y', strtotime($row['transaction_date'])) ?></td>
                  <td><code><?= $row['entry_number'] ?></code></td>
                  <td>
                    <?php if ($row['fund_source'] === 'BOS'): ?>
                      <span class="label label-success">DANA BOS</span>
                    <?php elseif ($row['fund_source'] === 'YAYASAN_SPP'): ?>
                      <span class="label label-primary" style="background: #0F294A;">SPP/YAYASAN</span>
                    <?php else: ?>
                      <span class="label label-default"><?= $row['fund_source'] ?></span>
                    <?php endif; ?>
                  </td>
                  <td><?= htmlspecialchars($row['description']) ?></td>
                  <td><code><?= $row['account_code'] ?></code> <small class="text-muted">(<?= $row['account_name'] ?: 'Akun' ?>)</small></td>
                  <td class="text-right" style="color: #0D6E52; font-weight: 600;">
                    <?= ($row['debit'] > 0) ? rupiah($row['debit']) : '-' ?>
                  </td>
                  <td class="text-right" style="color: #e11d48; font-weight: 600;">
                    <?= ($row['credit'] > 0) ? rupiah($row['credit']) : '-' ?>
                  </td>
                </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
            <tfoot style="background: #f1f5f9; font-weight: bold; font-size: 13px;">
              <tr>
                <th colspan="6" class="text-right">TOTAL MUTASI BKU:</th>
                <th class="text-right" style="color: #0D6E52;"><?= rupiah($totalDebit ?? 0) ?></th>
                <th class="text-right" style="color: #e11d48;"><?= rupiah($totalCredit ?? 0) ?></th>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

