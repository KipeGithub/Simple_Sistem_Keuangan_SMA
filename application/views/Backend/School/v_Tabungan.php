<div class="row">
  <div class="col-xs-12">
    <div class="box box-primary" style="border-top-color: #0F294A; border-radius: 8px;">
      <div class="box-header with-border">
        <h3 class="box-title" style="font-weight: 700; color: #0F294A;"><i class="fa fa-archive"></i> Tabungan Siswa</h3>
        <div class="box-tools pull-right">
          <?php if ((int)$this->session->userdata('role') === 2): ?>
          <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#modal-transaksi-tabungan" style="background-color: #0D6E52; border-color: #0D6E52; border-radius: 6px;">
            <i class="fa fa-plus-circle"></i> Transaksi Setor / Tarik Tabungan
          </button>
          <?php endif; ?>
        </div>
      </div>
      <div class="box-body">
        <div class="alert alert-info" style="font-size: 12.5px; border-radius: 6px;">
          <i class="fa fa-info-circle"></i> Seluruh saldo tabungan siswa disimpan aman sebagai dana titipan siswa dan dapat ditarik sewaktu-waktu oleh siswa atau wali murid.
        </div>

        <div class="table-responsive">
          <table class="table table-bordered table-striped table-hover tabel">
            <thead style="background: #f8fafc; color: #334155; font-size: 12px;">
              <tr>
                <th width="40" class="text-center">No</th>
                <th>No. Rekening Tabungan</th>
                <th>Nama Siswa & NIS</th>
                <th>Jurusan & Rombel</th>
                <th class="text-right">Saldo Tabungan Saat Ini</th>
                <th class="text-center">Status</th>
                <th width="120" class="text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($accounts)): ?>
                <tr><td colspan="7" class="text-center text-muted">Belum ada data rekening tabungan siswa.</td></tr>
              <?php else: ?>
                <?php $no = 1; foreach ($accounts as $a): ?>
                <tr>
                  <td class="text-center"><?= $no++ ?></td>
                  <td><code><?= $a['account_number'] ?></code></td>
                  <td>
                    <strong style="color: #0F294A;"><?= htmlspecialchars($a['student_name']) ?></strong><br>
                    <small class="text-muted">NIS: <?= $a['nis'] ?></small>
                  </td>
                  <td>
                    <span class="label label-primary" style="background: #0F294A;"><?= $a['major_code'] ?></span>
                    <span class="label label-default"><?= $a['class_name'] ?></span>
                  </td>
                  <td class="text-right font-weight-bold" style="font-weight: 700; color: #0D6E52; font-size: 14px;">
                    <?= rupiah($a['balance']) ?>
                  </td>
                  <td class="text-center">
                    <span class="label label-success"><?= $a['status'] ?></span>
                  </td>
                  <td class="text-center">
                    <a href="<?= base_url('School_finance/buku_tabungan/' . $a['student_id']) ?>" class="btn btn-xs btn-info" style="border-radius: 4px;">
                      <i class="fa fa-book"></i> Mutasi Buku
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
  </div>
</div>

<!-- Modal Transaksi Tabungan -->
<div class="modal fade" id="modal-transaksi-tabungan">
  <div class="modal-dialog">
    <div class="modal-content" style="border-radius: 10px;">
      <div class="modal-header" style="background: #0D6E52; color: #fff; border-radius: 9px 9px 0 0;">
        <button type="button" class="close" data-dismiss="modal" style="color: #fff;">&times;</button>
        <h4 class="modal-title"><i class="fa fa-exchange"></i> Transaksi Setor / Tarik Tabungan Siswa</h4>
      </div>
      <form action="<?= base_url('School_finance/proses_tabungan') ?>" method="POST">
        <div class="modal-body">
          <div class="form-group">
            <label>Pilih Siswa *</label>
            <select name="student_id" class="form-control" required>
              <option value="">-- Pilih Siswa --</option>
              <?php foreach ($students as $stu): ?>
                <option value="<?= $stu['id'] ?>">
                  <?= htmlspecialchars($stu['name']) ?> (NIS: <?= $stu['nis'] ?> - <?= $stu['major_code'] ?>)
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="row">
            <div class="col-md-6 form-group">
              <label>Jenis Transaksi *</label>
              <select name="transaction_type" class="form-control" required>
                <option value="SETOR">SETOR (Penerimaan Titipan)</option>
                <option value="TARIK">TARIK (Penarikan Titipan)</option>
              </select>
            </div>
            <div class="col-md-6 form-group">
              <label>Nominal (Rp) *</label>
              <input type="number" name="amount" class="form-control" required min="5000" step="1000" placeholder="Contoh: 50000">
            </div>
          </div>
          <div class="form-group">
            <label>Kas Operasional Bendahara *</label>
            <select name="cash_account_code" class="form-control" required>
              <?php foreach ($cashAccounts as $ca): ?>
                <option value="<?= $ca['code'] ?>"><?= $ca['code'] ?> - <?= $ca['name'] ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group">
            <label>Keterangan Transaksi</label>
            <input type="text" name="description" class="form-control" placeholder="Contoh: Setoran tabungan mingguan siswa">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-success" style="background: #0D6E52; border-color: #0D6E52;">
            <i class="fa fa-save"></i> Eksekusi Transaksi Tabungan
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

