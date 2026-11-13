<div class="row">
  <div class="col-xs-12">
    <div class="box box-primary" style="border-top-color: #0F294A; border-radius: 8px;">
      <div class="box-header with-border">
        <h3 class="box-title" style="font-weight: 700; color: #0F294A;"><i class="fa fa-money"></i> Tagihan SPP & Biaya Praktik Kejuruan Siswa</h3>
        <div class="box-tools pull-right">
          <?php if (in_array((int)$this->session->userdata('role'), [1, 2])): ?>
          <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#modal-generate-spp" style="background-color: #0D6E52; border-color: #0D6E52; border-radius: 6px;">
            <i class="fa fa-magic"></i> Terbitkan SPP Massal (1-Klik)
          </button>
          <?php endif; ?>
        </div>
      </div>
      <div class="box-body">
        <!-- Filter Form -->
        <form method="GET" action="<?= base_url('School_finance/tagihan') ?>" class="row" style="margin-bottom: 20px;">
          <div class="col-md-3">
            <label style="font-size: 12px;">Status Pembayaran:</label>
            <select name="status" class="form-control input-sm">
              <option value="">-- Semua Status --</option>
              <option value="UNPAID" <?= ($filter['status'] === 'UNPAID') ? 'selected' : '' ?>>Belum Lunas</option>
              <option value="PARTIAL" <?= ($filter['status'] === 'PARTIAL') ? 'selected' : '' ?>>Sebagian</option>
              <option value="PAID" <?= ($filter['status'] === 'PAID') ? 'selected' : '' ?>>Lunas</option>
            </select>
          </div>
          <div class="col-md-3">
            <label style="font-size: 12px;">Jenis Biaya:</label>
            <select name="bill_type" class="form-control input-sm">
              <option value="">-- Semua Biaya --</option>
              <option value="SPP" <?= ($filter['bill_type'] === 'SPP') ? 'selected' : '' ?>>SPP Bulanan</option>
              <option value="PRAKTIK_SMK" <?= ($filter['bill_type'] === 'PRAKTIK_SMK') ? 'selected' : '' ?>>Praktik Kejuruan SMK</option>
              <option value="PENDAFTARAN" <?= ($filter['bill_type'] === 'PENDAFTARAN') ? 'selected' : '' ?>>Pendaftaran / Pangkal</option>
            </select>
          </div>
          <div class="col-md-3">
            <label style="font-size: 12px;">Jurusan:</label>
            <select name="major_code" class="form-control input-sm">
              <option value="">-- Semua Jurusan --</option>
              <?php foreach ($majors as $m): ?>
                <option value="<?= $m['code'] ?>" <?= ($filter['major_code'] === $m['code']) ? 'selected' : '' ?>><?= $m['code'] ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-3" style="margin-top: 24px;">
            <button type="submit" class="btn btn-sm btn-default btn-block"><i class="fa fa-filter"></i> Saring Data</button>
          </div>
        </form>

        <div class="table-responsive">
          <table class="table table-bordered table-striped table-hover tabel">
            <thead style="background: #f8fafc; color: #334155; font-size: 12px;">
              <tr>
                <th width="40" class="text-center">No</th>
                <th>No. Tagihan</th>
                <th>Nama Siswa & Rombel</th>
                <th>Uraian Tagihan</th>
                <th>Jatuh Tempo</th>
                <th class="text-right">Nominal Bersih</th>
                <th class="text-right">Terbayar</th>
                <th class="text-center">Status</th>
                <th width="100" class="text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($bills)): ?>
                <tr><td colspan="9" class="text-center text-muted">Tidak ada tagihan yang sesuai filter.</td></tr>
              <?php else: ?>
                <?php $no = 1; foreach ($bills as $b): ?>
                <tr>
                  <td class="text-center"><?= $no++ ?></td>
                  <td><code><?= $b['bill_number'] ?></code></td>
                  <td>
                    <strong style="color: #0F294A;"><?= htmlspecialchars($b['student_name']) ?></strong><br>
                    <small><?= $b['major_code'] ?> - <?= $b['class_name'] ?: 'Kelas -' ?></small>
                  </td>
                  <td>
                    <strong><?= htmlspecialchars($b['title']) ?></strong><br>
                    <small class="text-muted"><?= $b['academic_year'] ?></small>
                  </td>
                  <td><?= date('d/m/Y', strtotime($b['due_date'])) ?></td>
                  <td class="text-right font-weight-bold" style="font-weight: 700; color: #0F294A;"><?= rupiah($b['net_amount']) ?></td>
                  <td class="text-right" style="color: #0D6E52; font-weight: 600;"><?= rupiah($b['paid_amount']) ?></td>
                  <td class="text-center">
                    <?php if ($b['status'] === 'PAID'): ?>
                      <span class="label label-success" style="background-color: #0D6E52;">LUNAS</span>
                    <?php elseif ($b['status'] === 'PARTIAL'): ?>
                      <span class="label label-warning">SEBAGIAN</span>
                    <?php else: ?>
                      <span class="label label-danger">BELUM LUNAS</span>
                    <?php endif; ?>
                  </td>
                  <td class="text-center">
                    <?php if ($b['status'] !== 'PAID'): ?>
                      <?php if ((int)$this->session->userdata('role') === 2): ?>
                      <a href="<?= base_url('School_finance/bayar/' . $b['id']) ?>" class="btn btn-xs btn-primary" style="background: #0F294A; border-color: #0F294A;">
                        <i class="fa fa-credit-card"></i> Bayar
                      </a>
                      <?php else: ?>
                      <span class="text-muted" style="font-size: 11px;"><i class="fa fa-clock-o"></i> Menunggu</span>
                      <?php endif; ?>
                    <?php else: ?>
                      <span class="text-success"><i class="fa fa-check-circle"></i> Selesai</span>
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
  </div>
</div>

<!-- Modal Terbitkan SPP Massal -->
<div class="modal fade" id="modal-generate-spp">
  <div class="modal-dialog">
    <div class="modal-content" style="border-radius: 10px;">
      <div class="modal-header" style="background: #0D6E52; color: #fff; border-radius: 9px 9px 0 0;">
        <button type="button" class="close" data-dismiss="modal" style="color: #fff;">&times;</button>
        <h4 class="modal-title"><i class="fa fa-magic"></i> Generator Tagihan SPP Bulanan Massal</h4>
      </div>
      <form action="<?= base_url('School_finance/generate_spp') ?>" method="POST">
        <div class="modal-body">
          <p class="text-muted" style="font-size: 13px;">
            Fitur ini secara otomatis menerbitkan tagihan SPP untuk seluruh siswa aktif sesuai biaya SPP per Jurusan SMK dan memperhitungkan potongan beasiswa/KIP masing-masing siswa.
          </p>
          <div class="form-group">
            <label>Tahun Ajaran *</label>
            <input type="text" name="academic_year" class="form-control" value="2026/2027" required>
          </div>
          <div class="row">
            <div class="col-md-6 form-group">
              <label>Bulan SPP *</label>
              <select name="month" class="form-control" required>
                <?php 
                  $months = [
                    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                    5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                    9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                  ];
                  foreach ($months as $num => $mName):
                ?>
                  <option value="<?= $num ?>" <?= ($num == date('n')) ? 'selected' : '' ?>><?= $mName ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-6 form-group">
              <label>Tahun Kalender *</label>
              <input type="number" name="year" class="form-control" value="<?= date('Y') ?>" required>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 form-group">
              <label>Saring Berdasarkan Jurusan (Opsional)</label>
              <select name="major_code" class="form-control">
                <option value="">-- Semua Jurusan (Semua Siswa) --</option>
                <?php foreach ($majors as $m): ?>
                  <option value="<?= $m['code'] ?>"><?= $m['code'] ?> - <?= $m['name'] ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-6 form-group">
              <label>Saring Berdasarkan Kelas (Opsional)</label>
              <select name="kelas" class="form-control">
                <option value="">-- Semua Kelas --</option>
                <?php foreach ($classes as $c): ?>
                  <option value="<?= $c['id'] ?>"><?= $c['nama'] ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-success" style="background: #0D6E52; border-color: #0D6E52;"><i class="fa fa-bolt"></i> Terbitkan Tagihan Sekarang</button>
        </div>
      </form>
    </div>
  </div>
</div>
