<div class="row">
  <div class="col-xs-12">
    <div class="box box-primary" style="border-top-color: #0F294A; border-radius: 8px;">
      <div class="box-header with-border">
        <h3 class="box-title" style="font-weight: 700; color: #0F294A;"><i class="fa fa-list-alt"></i> Buku Jurnal Umum (Double-Entry Bookkeeping)</h3>
        <div class="box-tools pull-right">
          <button onclick="window.print()" class="btn btn-sm btn-default"><i class="fa fa-print"></i> Cetak Jurnal</button>
        </div>
      </div>
      <div class="box-body">
        <div class="alert alert-info" style="font-size: 12.5px; border-radius: 6px;">
          <i class="fa fa-info-circle"></i> Setiap transaksi pembayaran SPP, realisasi belanja BOS, dan transaksi tabungan siswa secara otomatis membentuk entri jurnal berpasangan yang dijamin selalu seimbang (<code>SUM(Debit) == SUM(Kredit)</code>).
        </div>

        <div class="table-responsive">
          <table class="table table-bordered table-striped table-hover">
            <thead style="background: #0F294A; color: #fff; font-size: 12px;">
              <tr>
                <th width="40" class="text-center">No</th>
                <th width="100">Tanggal</th>
                <th width="160">No. Bukti Jurnal</th>
                <th>Keterangan / Akun Perkiraan (CoA)</th>
                <th width="90">Ref Akun</th>
                <th width="140" class="text-right">Debit</th>
                <th width="140" class="text-right">Kredit</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($journals)): ?>
                <tr><td colspan="7" class="text-center text-muted">Belum ada catatan jurnal umum.</td></tr>
              <?php else: ?>
                <?php 
                  $grandDebit = 0; 
                  $grandCredit = 0; 
                  $no = 1; 
                  foreach ($journals as $j): 
                ?>
                  <tr style="background: #f8fafc; font-weight: bold;">
                    <td class="text-center"><?= $no++ ?></td>
                    <td><?= date('d/m/Y', strtotime($j['transaction_date'])) ?></td>
                    <td><code><?= $j['entry_number'] ?></code></td>
                    <td colspan="4" style="color: #0F294A;">
                      <?= htmlspecialchars($j['description']) ?>
                      <span class="label label-default pull-right"><?= $j['fund_source'] ?></span>
                    </td>
                  </tr>
                  <?php foreach ($j['details'] as $d): ?>
                  <?php 
                    $grandDebit += $d['debit']; 
                    $grandCredit += $d['credit']; 
                  ?>
                  <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td style="padding-left: <?= ($d['credit'] > 0) ? '40px' : '20px' ?>;">
                      <?= ($d['credit'] > 0) ? '↳ ' : '' ?>
                      <strong><?= $d['account_name'] ?: 'Akun' ?></strong>
                      <?php if (!empty($d['note'])): ?>
                        <small class="text-muted">(<?= $d['note'] ?>)</small>
                      <?php endif; ?>
                    </td>
                    <td><code><?= $d['account_code'] ?></code></td>
                    <td class="text-right" style="color: #0D6E52; font-weight: 600;">
                      <?= ($d['debit'] > 0) ? rupiah($d['debit']) : '-' ?>
                    </td>
                    <td class="text-right" style="color: #0F294A; font-weight: 600;">
                      <?= ($d['credit'] > 0) ? rupiah($d['credit']) : '-' ?>
                    </td>
                  </tr>
                  <?php endforeach; ?>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
            <tfoot style="background: #e2e8f0; font-weight: 800; font-size: 14px;">
              <tr>
                <th colspan="5" class="text-right">TOTAL KESELURUHAN DEBIT & KREDIT:</th>
                <th class="text-right" style="color: #0D6E52;"><?= rupiah($grandDebit ?? 0) ?></th>
                <th class="text-right" style="color: #0F294A;"><?= rupiah($grandCredit ?? 0) ?></th>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

