<div class="row">
  <div class="col-md-8 col-md-offset-2">
    <div class="box box-primary" style="border-top-color: #0F294A; border-radius: 8px;">
      <div class="box-header with-border">
        <h3 class="box-title" style="font-weight: 700; color: #0F294A;"><i class="fa fa-credit-card"></i> Formulir Pembayaran Biaya Pendidikan</h3>
      </div>
      <form action="<?= base_url('School_finance/proses_bayar') ?>" method="POST">
        <input type="hidden" name="bill_id" value="<?= $bill['id'] ?>">
        <div class="box-body">
          <!-- Detail Siswa & Tagihan -->
          <div class="well" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px;">
            <div class="row">
              <div class="col-sm-6">
                <span class="text-muted" style="font-size: 11px; text-transform: uppercase;">Informasi Siswa:</span>
                <h4 style="margin: 3px 0 0; font-weight: 700; color: #0F294A;"><?= htmlspecialchars($student['name']) ?></h4>
                <p style="margin: 0; font-size: 12px; color: #64748b;">
                  NIS: <strong><?= $student['nis'] ?></strong> | Kelas: <strong><?= $student['class_name'] ?></strong> (<?= $student['major_code'] ?>)
                </p>
              </div>
              <div class="col-sm-6 text-right">
                <span class="text-muted" style="font-size: 11px; text-transform: uppercase;">Tagihan Pendidikan:</span>
                <h4 style="margin: 3px 0 0; font-weight: 700; color: #334155;"><?= htmlspecialchars($bill['title']) ?></h4>
                <p style="margin: 0; font-size: 12px; color: #64748b;">
                  Jatuh Tempo: <strong><?= date('d/m/Y', strtotime($bill['due_date'])) ?></strong>
                </p>
              </div>
            </div>
            <hr style="margin: 12px 0;">
            <div class="row text-center">
              <div class="col-xs-4">
                <span style="font-size: 11px; color: #64748b;">Total Tagihan</span>
                <div style="font-weight: 700; color: #0F294A; font-size: 15px;"><?= rupiah($bill['net_amount']) ?></div>
              </div>
              <div class="col-xs-4">
                <span style="font-size: 11px; color: #64748b;">Sudah Terbayar</span>
                <div style="font-weight: 700; color: #0D6E52; font-size: 15px;"><?= rupiah($bill['paid_amount']) ?></div>
              </div>
              <div class="col-xs-4">
                <span style="font-size: 11px; color: #64748b;">Sisa Tagihan</span>
                <div style="font-weight: 700; color: #e11d48; font-size: 15px;"><?= rupiah($bill['net_amount'] - $bill['paid_amount']) ?></div>
              </div>
            </div>
          </div>

          <!-- Form Input Pembayaran -->
          <div class="form-group">
            <label style="font-weight: 600;">Nominal Pembayaran (Rp) *</label>
            <input type="number" name="amount" class="form-control input-lg" value="<?= ($bill['net_amount'] - $bill['paid_amount']) ?>" max="<?= ($bill['net_amount'] - $bill['paid_amount']) ?>" min="1000" required style="font-size: 18px; font-weight: 700; color: #0D6E52;">
            <small class="text-muted">Dapat dibayar lunas atau sebagian (angsuran).</small>
          </div>

          <div class="row">
            <div class="col-md-6 form-group">
              <label style="font-weight: 600;">Rekening Kas / Bank Penerima *</label>
              <select name="cash_account_code" class="form-control" required>
                <?php foreach ($cashAccounts as $ca): ?>
                  <option value="<?= $ca['code'] ?>" <?= ($ca['code'] === '1-1101') ? 'selected' : '' ?>>
                    <?= $ca['code'] ?> - <?= $ca['name'] ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-6 form-group">
              <label style="font-weight: 600;">Metode Pembayaran *</label>
              <select name="payment_method" class="form-control" required>
                <option value="TUNAI">Tunai di Kasir Bendahara</option>
                <option value="TRANSFER_BANK">Transfer Bank (BNI/BJB)</option>
                <option value="QRIS">QRIS / Dompet Digital</option>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label style="font-weight: 600;">Catatan / Keterangan Kuitansi</label>
            <input type="text" name="note" class="form-control" placeholder="Contoh: Pembayaran SPP Bulan Juli 2026 Lunas">
          </div>
        </div>
        <div class="box-footer">
          <a href="<?= base_url('School_finance/tagihan') ?>" class="btn btn-default">Kembali</a>
          <button type="submit" class="btn btn-primary pull-right" style="background-color: #0F294A; border-color: #0F294A; font-weight: 600;">
            <i class="fa fa-check"></i> Proses Pembayaran & Terbitkan Kuitansi
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

