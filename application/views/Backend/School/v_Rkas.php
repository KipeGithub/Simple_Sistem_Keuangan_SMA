<div class="row">
  <div class="col-xs-12">
    <!-- Pagu Anggaran RKAS 8 SNP -->
    <div class="box box-primary" style="border-top-color: #0F294A; border-radius: 8px;">
      <div class="box-header with-border">
        <h3 class="box-title" style="font-weight: 700; color: #0F294A;"><i class="fa fa-pie-chart"></i> Rencana Kegiatan & Anggaran Sekolah (RKAS 8 SNP) - TA <?= htmlspecialchars($fiscalYear) ?></h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#modal-belanja-bos" style="border-radius: 6px;">
            <i class="fa fa-credit-card"></i> Catat Belanja BOS & SPJ
          </button>
        </div>
      </div>
      <div class="box-body">
        <div class="table-responsive">
          <table class="table table-bordered table-striped table-hover">
            <thead style="background: #f8fafc; color: #334155; font-size: 12px;">
              <tr>
                <th width="40" class="text-center">No</th>
                <th>Kode & Standar SNP</th>
                <th>Kode & Uraian Kegiatan RKAS</th>
                <th>Sumber Dana</th>
                <th class="text-right">Pagu Alokasi</th>
                <th class="text-right">Realisasi Belanja</th>
                <th class="text-right">Sisa Pagu</th>
                <th class="text-center">Status</th>
              </tr>
            </thead>
            <tbody>
              <?php $totalPagu = 0; $totalRealisasi = 0; $totalSisa = 0; ?>
              <?php $no = 1; foreach ($budgets as $b): ?>
              <?php 
                $totalPagu += $b['allocated_amount'];
                $totalRealisasi += $b['realized_amount'];
                $totalSisa += $b['remaining_amount'];
              ?>
              <tr>
                <td class="text-center"><?= $no++ ?></td>
                <td>
                  <span class="label label-primary" style="background: #0F294A;"><?= $b['snp_code'] ?></span><br>
                  <strong style="font-size: 11.5px;"><?= $b['snp_name'] ?></strong>
                </td>
                <td>
                  <code><?= $b['activity_code'] ?></code><br>
                  <strong><?= htmlspecialchars($b['activity_name']) ?></strong>
                </td>
                <td><span class="label label-info"><?= $b['fund_source'] ?></span></td>
                <td class="text-right font-weight-bold" style="font-weight: 600; color: #0F294A;"><?= rupiah($b['allocated_amount']) ?></td>
                <td class="text-right" style="color: #0D6E52; font-weight: 600;"><?= rupiah($b['realized_amount']) ?></td>
                <td class="text-right font-weight-bold" style="font-weight: 700; color: <?= ($b['remaining_amount'] < 0) ? '#e11d48' : '#0284c7' ?>;">
                  <?= rupiah($b['remaining_amount']) ?>
                </td>
                <td class="text-center">
                  <span class="label label-success"><?= $b['status'] ?></span>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
            <tfoot style="background: #f1f5f9; font-weight: bold;">
              <tr>
                <th colspan="4" class="text-right">TOTAL PAGU & REALISASI KESELURUHAN:</th>
                <th class="text-right" style="color: #0F294A;"><?= rupiah($totalPagu) ?></th>
                <th class="text-right" style="color: #0D6E52;"><?= rupiah($totalRealisasi) ?></th>
                <th class="text-right" style="color: #0284c7;"><?= rupiah($totalSisa) ?></th>
                <th></th>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
    </div>

    <!-- Riwayat Belanja BOS & Dokumen SPJ -->
    <div class="box box-success" style="border-top-color: #0D6E52; border-radius: 8px;">
      <div class="box-header with-border">
        <h3 class="box-title" style="font-weight: 700; color: #0D6E52;"><i class="fa fa-file-text-o"></i> Riwayat Realisasi Belanja & Nomor Bukti SPJ Dana BOS</h3>
      </div>
      <div class="box-body">
        <div class="table-responsive">
          <table class="table table-bordered table-striped table-hover">
            <thead style="background: #f8fafc; color: #334155; font-size: 12px;">
              <tr>
                <th width="40" class="text-center">No</th>
                <th>No. SPJ / Belanja</th>
                <th>Tanggal</th>
                <th>Kegiatan RKAS</th>
                <th>Penerima Dana</th>
                <th>Rekening Bank Penampung</th>
                <th class="text-right">Nominal Belanja</th>
                <th>Petugas Pencatat</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($expenses)): ?>
                <tr><td colspan="8" class="text-center text-muted">Belum ada catatan realisasi belanja BOS.</td></tr>
              <?php else: ?>
                <?php $no = 1; foreach ($expenses as $e): ?>
                <tr>
                  <td class="text-center"><?= $no++ ?></td>
                  <td><code><?= $e['expense_number'] ?></code></td>
                  <td><?= date('d/m/Y', strtotime($e['expense_date'])) ?></td>
                  <td>
                    <strong><?= htmlspecialchars($e['activity_name']) ?></strong><br>
                    <small class="text-muted"><?= htmlspecialchars($e['description']) ?></small>
                  </td>
                  <td><strong><?= htmlspecialchars($e['recipient']) ?></strong></td>
                  <td><small><?= $e['bank_name'] ?: 'Rekening Dana BOS' ?></small></td>
                  <td class="text-right font-weight-bold" style="font-weight: 700; color: #0D6E52; font-size: 14px;">
                    <?= rupiah($e['amount']) ?>
                  </td>
                  <td><small><?= htmlspecialchars($e['created_by']) ?></small></td>
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

<!-- Modal Catat Belanja BOS & SPJ -->
<div class="modal fade" id="modal-belanja-bos">
  <div class="modal-dialog">
    <div class="modal-content" style="border-radius: 10px;">
      <div class="modal-header" style="background: #0F294A; color: #fff; border-radius: 9px 9px 0 0;">
        <button type="button" class="close" data-dismiss="modal" style="color: #fff;">&times;</button>
        <h4 class="modal-title"><i class="fa fa-credit-card"></i> Catat Realisasi Belanja BOS & Terbitkan SPJ</h4>
      </div>
      <form action="<?= base_url('School_finance/proses_belanja_rkas') ?>" method="POST">
        <div class="modal-body">
          <div class="form-group">
            <label>Pilih Pos Kegiatan RKAS *</label>
            <select name="budget_id" class="form-control" required>
              <option value="">-- Pilih Mata Anggaran --</option>
              <?php foreach ($budgets as $b): ?>
                <option value="<?= $b['id'] ?>">
                  <?= $b['snp_code'] ?> - <?= htmlspecialchars($b['activity_name']) ?> (Sisa: <?= rupiah($b['remaining_amount']) ?>)
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="row">
            <div class="col-md-6 form-group">
              <label>Nominal Pengeluaran Belanja (Rp) *</label>
              <input type="number" name="amount" class="form-control" required min="1000" placeholder="Contoh: 2500000">
            </div>
            <div class="col-md-6 form-group">
              <label>Rekening Bank Penampung *</label>
              <select name="cash_account_code" class="form-control" required>
                <?php foreach ($cashAccounts as $ca): ?>
                  <option value="<?= $ca['code'] ?>" <?= ($ca['code'] === '1-1202') ? 'selected' : '' ?>>
                    <?= $ca['code'] ?> - <?= $ca['name'] ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label>Penerima Dana / Pihak Ketiga (Vendor / Toko / Guru) *</label>
            <input type="text" name="recipient" class="form-control" required placeholder="Contoh: CV. Mandiri Jaya Teknik">
          </div>
          <div class="form-group">
            <label>Uraian Belanja / SPJ Lengkap *</label>
            <textarea name="description" class="form-control" rows="2" required placeholder="Rincian barang/jasa yang dibeli sesuai dokumen SPJ..."></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary" style="background: #0F294A; border-color: #0F294A;">
            <i class="fa fa-save"></i> Bukukan Belanja & Bentuk Jurnal Otomatis
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

