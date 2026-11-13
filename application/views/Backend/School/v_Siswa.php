<div class="row">
  <div class="col-xs-12">
    <div class="box box-primary" style="border-top-color: #0F294A; border-radius: 8px;">
      <div class="box-header with-border">
        <h3 class="box-title" style="font-weight: 700; color: #0F294A;"><i class="fa fa-users"></i> Data Induk Siswa & 4 Kompetensi Keahlian SMK</h3>
        <div class="box-tools pull-right">
          <?php if ($this->session->userdata('role') == 1): ?>
          <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modal-tambah-siswa" style="background-color: #0F294A; border-color: #0F294A; border-radius: 6px;">
            <i class="fa fa-user-plus"></i> Tambah Siswa Baru
          </button>
          <?php endif; ?>
        </div>
      </div>
      <div class="box-body">
        <!-- Filter Form -->
        <form method="GET" action="<?= base_url('School_finance/siswa') ?>" class="row" style="margin-bottom: 20px;">
          <div class="col-md-3">
            <label style="font-size: 12px;">Jurusan SMK:</label>
            <select name="major_code" class="form-control input-sm">
              <option value="">-- Semua Jurusan --</option>
              <?php foreach ($majors as $m): ?>
                <option value="<?= $m['code'] ?>" <?= ($filter['major_code'] == $m['code']) ? 'selected' : '' ?>>
                  <?= $m['code'] ?> - <?= $m['name'] ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-3">
            <label style="font-size: 12px;">Kelas / Tingkat:</label>
            <select name="kelas" class="form-control input-sm">
              <option value="">-- Semua Kelas --</option>
              <?php foreach ($classes as $c): ?>
                <option value="<?= $c['id'] ?>" <?= ($filter['kelas'] == $c['id']) ? 'selected' : '' ?>>
                  <?= $c['nama'] ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-4">
            <label style="font-size: 12px;">Pencarian (Nama / NIS / NISN / Email):</label>
            <input type="text" name="search" class="form-control input-sm" value="<?= htmlspecialchars($filter['search'] ?? '') ?>" placeholder="Ketik kata kunci...">
          </div>
          <div class="col-md-2" style="margin-top: 24px;">
            <button type="submit" class="btn btn-sm btn-default btn-block"><i class="fa fa-filter"></i> Saring Data</button>
          </div>
        </form>

        <div class="table-responsive">
          <table class="table table-bordered table-striped table-hover tabel">
            <thead style="background: #f8fafc; color: #334155; font-size: 12px;">
              <tr>
                <th width="40" class="text-center">No</th>
                <th>NIS / NISN</th>
                <th>Nama Lengkap</th>
                <th>Jurusan & Kelas</th>
                <th>Orang Tua / Wali & Email</th>
                <th>Status Beasiswa / KIP</th>
                <th width="90" class="text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($students)): ?>
                <tr><td colspan="7" class="text-center text-muted">Tidak ada data siswa ditemukan.</td></tr>
              <?php else: ?>
                <?php $no = 1; foreach ($students as $s): ?>
                <tr>
                  <td class="text-center"><?= $no++ ?></td>
                  <td>
                    <strong><?= $s['nis'] ?></strong><br>
                    <small class="text-muted">NISN: <?= $s['nisn'] ?: '-' ?></small>
                  </td>
                  <td>
                    <strong style="color: #0F294A;"><?= htmlspecialchars($s['name']) ?></strong><br>
                    <small><?= $s['sex'] ?> | <?= $s['tempat'] ?>, <?= $s['tanggal'] ?></small>
                  </td>
                  <td>
                    <span class="label label-primary" style="background: #0F294A;"><?= $s['major_code'] ?></span>
                    <span class="label label-default"><?= $s['class_name'] ?: 'Kelas ' . $s['kelas'] ?></span>
                  </td>
                  <td>
                    <strong><?= htmlspecialchars($s['wali']) ?></strong><br>
                    <small class="text-info"><i class="fa fa-envelope-o"></i> <?= $s['parent_email'] ?: '-' ?></small>
                  </td>
                  <td>
                    <?php if ($s['scholarship_status'] === 'KIP_SEKOLAH'): ?>
                      <span class="label label-warning">KIP Sekolah (Diskon <?= $s['discount_percent'] ?>%)</span>
                    <?php elseif ($s['scholarship_status'] === 'PRESTASI'): ?>
                      <span class="label label-success">Prestasi (Diskon <?= $s['discount_percent'] ?>%)</span>
                    <?php else: ?>
                      <span class="label label-default">Reguler</span>
                    <?php endif; ?>
                  </td>
                  <td class="text-center">
                    <a href="<?= base_url('School_finance/buku_tabungan/' . $s['id']) ?>" class="btn btn-xs btn-info" title="Buku Tabungan"><i class="fa fa-book"></i></a>
                    <a href="<?= base_url('School_finance/tagihan?student_id=' . $s['id']) ?>" class="btn btn-xs btn-success" title="Tagihan SPP"><i class="fa fa-money"></i></a>
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

<!-- Modal Tambah Siswa -->
<div class="modal fade" id="modal-tambah-siswa">
  <div class="modal-dialog modal-lg">
    <div class="modal-content" style="border-radius: 10px;">
      <div class="modal-header" style="background: #0F294A; color: #fff; border-radius: 9px 9px 0 0;">
        <button type="button" class="close" data-dismiss="modal" style="color: #fff;">&times;</button>
        <h4 class="modal-title"><i class="fa fa-user-plus"></i> Tambah Data Siswa SMK Baru</h4>
      </div>
      <form action="<?= base_url('School_finance/simpan_siswa') ?>" method="POST">
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6 form-group">
              <label>Nama Lengkap Siswa *</label>
              <input type="text" name="name" class="form-control" required placeholder="Contoh: Muhammad Rizky Pratama">
            </div>
            <div class="col-md-3 form-group">
              <label>Nomor Induk Siswa (NIS) *</label>
              <input type="text" name="nis" class="form-control" required placeholder="242510001">
            </div>
            <div class="col-md-3 form-group">
              <label>NISN Kemendikbud</label>
              <input type="text" name="nisn" class="form-control" placeholder="0061234567">
            </div>
          </div>
          <div class="row">
            <div class="col-md-4 form-group">
              <label>Jurusan SMK *</label>
              <select name="major_code" class="form-control" required>
                <?php foreach ($majors as $m): ?>
                  <option value="<?= $m['code'] ?>"><?= $m['code'] ?> - <?= $m['name'] ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-4 form-group">
              <label>Rombel / Kelas *</label>
              <select name="kelas" class="form-control" required>
                <?php foreach ($classes as $c): ?>
                  <option value="<?= $c['id'] ?>"><?= $c['nama'] ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-4 form-group">
              <label>Jenis Kelamin</label>
              <select name="sex" class="form-control">
                <option value="Pria">Pria</option>
                <option value="Wanita">Wanita</option>
              </select>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 form-group">
              <label>Nama Orang Tua / Wali *</label>
              <input type="text" name="wali" class="form-control" required placeholder="Nama Ayah/Ibu/Wali">
            </div>
            <div class="col-md-6 form-group">
              <label>Email Orang Tua / Akun Google Wali</label>
              <input type="email" name="parent_email" class="form-control" placeholder="wali.siswa@gmail.com (Untuk login portal)">
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 form-group">
              <label>Status Beasiswa / KIP</label>
              <select name="scholarship_status" class="form-control">
                <option value="REGULER">Reguler (Biaya Penuh)</option>
                <option value="KIP_SEKOLAH">KIP Sekolah (Bantuan Kemendikbud)</option>
                <option value="PRESTASI">Beasiswa Prestasi Akademik/Non-Akademik</option>
                <option value="YATIM_PIATU">Keringanan Yatim / Piatu</option>
              </select>
            </div>
            <div class="col-md-6 form-group">
              <label>Persentase Potongan SPP (%)</label>
              <input type="number" name="discount_percent" class="form-control" value="0" min="0" max="100" step="5">
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 form-group">
              <label>Tempat Lahir</label>
              <input type="text" name="tempat" class="form-control" placeholder="Jakarta">
            </div>
            <div class="col-md-6 form-group">
              <label>Tanggal Lahir</label>
              <input type="date" name="tanggal" class="form-control" value="2008-01-01">
            </div>
          </div>
          <div class="form-group">
            <label>Alamat Tempat Tinggal</label>
            <textarea name="alamat" class="form-control" rows="2" placeholder="Alamat domisili lengkap..."></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary" style="background: #0F294A; border-color: #0F294A;"><i class="fa fa-save"></i> Simpan Siswa Baru</button>
        </div>
      </form>
    </div>
  </div>
</div>
