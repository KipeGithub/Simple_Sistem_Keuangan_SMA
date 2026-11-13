<div class="row">
  <div class="col-xs-12">
    <?php if ($this->session->flashdata('success')): ?>
      <div class="alert alert-success alert-dismissible" style="border-radius: 6px;">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <i class="fa fa-check-circle"></i> <?= $this->session->flashdata('success'); ?>
      </div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('error')): ?>
      <div class="alert alert-danger alert-dismissible" style="border-radius: 6px;">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <i class="fa fa-times-circle"></i> <?= $this->session->flashdata('error'); ?>
      </div>
    <?php endif; ?>

    <div class="box box-primary" style="border-top-color: #0F294A; border-radius: 8px;">
      <div class="box-header with-border" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap;">
        <h3 class="box-title" style="font-weight: 700; color: #0F294A;">
          <i class="fa fa-user-circle-o"></i> Daftar Akun Pengguna Sekolah (SMK DUMMY KIPE)
        </h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modalTambahUser" style="background-color: #0F294A; border-color: #0F294A;">
            <i class="fa fa-plus-circle"></i> Tambah Pengguna Baru
          </button>
        </div>
      </div>

      <div class="box-body">
        <div class="callout callout-info" style="border-left-color: #0F294A; background-color: #f0f9ff; color: #0369a1; border-radius: 6px; padding: 10px 15px; margin-bottom: 20px;">
          <h5 style="margin: 0 0 5px 0; font-weight: 700;"><i class="fa fa-shield"></i> Pembagian 4 Hak Akses Resmi Sekolah:</h5>
          <span class="label label-primary" style="background-color: #0F294A;">1. Admin TU</span> Mengelola Data Master & Akun Pengguna &nbsp;|&nbsp;
          <span class="label label-success" style="background-color: #0D6E52;">2. Bendahara</span> Transaksi Kas Masuk (SPP/Tabungan) & Kas Keluar (Gaji/Operasional) &nbsp;|&nbsp;
          <span class="label label-warning" style="background-color: #d97706;">3. Kepala Sekolah</span> Monitoring Eksekutif & Laporan (Read-Only) &nbsp;|&nbsp;
          <span class="label label-default" style="background-color: #64748b;">4. Wali Murid</span> Portal Mandiri Tagihan Siswa
        </div>

        <div class="table-responsive">
          <table class="table table-bordered table-striped table-hover tabel">
            <thead style="background: #0F294A; color: #fff; font-size: 12.5px;">
              <tr>
                <th width="40" class="text-center">No</th>
                <th>Nama Lengkap</th>
                <th>Alamat Email</th>
                <th>Peran Akun</th>
                <th width="90" class="text-center">Status</th>
                <th width="140" class="text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php $no = 1; foreach ($users as $u): ?>
              <?php 
                $roleBadge = 'label-default';
                $roleLabel = 'Wali Murid';
                if ($u['role'] == 1) {
                  $roleBadge = 'label-primary';
                  $roleLabel = 'Admin TU';
                } elseif ($u['role'] == 2) {
                  $roleBadge = 'label-success';
                  $roleLabel = 'Bendahara';
                } elseif ($u['role'] == 3) {
                  $roleBadge = 'label-warning';
                  $roleLabel = 'Kepala Sekolah';
                }
              ?>
              <tr>
                <td class="text-center"><?= $no++ ?></td>
                <td>
                  <strong><?= htmlspecialchars($u['name']) ?></strong>
                  <?php if ($u['id'] == $this->session->userdata('id')): ?>
                    <span class="label label-info" style="font-size: 9px; margin-left: 5px;">Akun Anda</span>
                  <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($u['email']) ?></td>
                <td>
                  <span class="label <?= $roleBadge ?>" style="font-size: 11px; padding: 4px 8px;"><?= $roleLabel ?></span>
                </td>
                <td class="text-center">
                  <?php if ($u['active'] == '1'): ?>
                    <span class="label label-success"><i class="fa fa-check"></i> Aktif</span>
                  <?php else: ?>
                    <span class="label label-danger"><i class="fa fa-ban"></i> Nonaktif</span>
                  <?php endif; ?>
                </td>
                <td class="text-center">
                  <button type="button" class="btn btn-xs btn-default" onclick='editUser(<?= json_encode($u) ?>)' title="Edit Akun">
                    <i class="fa fa-edit text-blue"></i>
                  </button>
                  <button type="button" class="btn btn-xs btn-default" onclick='resetUserPassword(<?= $u['id'] ?>, "<?= htmlspecialchars($u['name']) ?>")' title="Atur Ulang Sandi">
                    <i class="fa fa-key text-yellow"></i>
                  </button>
                  <?php if ($u['id'] != $this->session->userdata('id')): ?>
                    <a href="<?= base_url('Pengguna/hapus/' . $u['id']) ?>" class="btn btn-xs btn-default" onclick="return confirm('Apakah Anda yakin ingin menghapus akun pengguna ini?')" title="Hapus Akun">
                      <i class="fa fa-trash text-red"></i>
                    </a>
                  <?php endif; ?>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- MODAL TAMBAH USER -->
<div class="modal fade" id="modalTambahUser" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content" style="border-radius: 8px;">
      <form action="<?= base_url('Pengguna/simpan') ?>" method="post">
        <div class="modal-header" style="background: #0F294A; color: #fff; border-radius: 8px 8px 0 0;">
          <button type="button" class="close" data-dismiss="modal" style="color: #fff;">&times;</button>
          <h4 class="modal-title"><i class="fa fa-user-plus"></i> Tambah Akun Pengguna Baru</h4>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Nama Lengkap Pegawai / Wali <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" required placeholder="Contoh: Bpk. Bambang Irawan, S.Kom">
          </div>
          <div class="form-group">
            <label>Alamat Email <span class="text-danger">*</span></label>
            <input type="email" name="email" class="form-control" required placeholder="nama@smkdummykipe.sch.id">
          </div>
          <div class="form-group">
            <label>Peran Sekolah (Role) <span class="text-danger">*</span></label>
            <select name="role" class="form-control" required>
              <option value="1">1. Admin TU (Kelola Data Master & Akun Pengguna)</option>
              <option value="2">2. Bendahara (Kelola Kas Masuk & Kas Keluar)</option>
              <option value="3">3. Kepala Sekolah (Monitoring & Laporan Keuangan)</option>
              <option value="4">4. Wali Murid (Akses Portal Tagihan Siswa)</option>
            </select>
          </div>
          <div class="form-group">
            <label>Kata Sandi Akun <span class="text-danger">*</span></label>
            <input type="password" name="password" class="form-control" required placeholder="Minimal 6 karakter" minlength="6">
          </div>
          <div class="form-group">
            <div class="checkbox">
              <label>
                <input type="checkbox" name="active" value="1" checked> Aktifkan akun langsung
              </label>
            </div>
          </div>
        </div>
        <div class="modal-footer" style="background: #f8fafc; border-radius: 0 0 8px 8px;">
          <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary" style="background: #0F294A; border-color: #0F294A;"><i class="fa fa-save"></i> Simpan Pengguna</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- MODAL EDIT USER -->
<div class="modal fade" id="modalEditUser" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content" style="border-radius: 8px;">
      <form action="<?= base_url('Pengguna/simpan') ?>" method="post">
        <input type="hidden" name="id" id="edit_id">
        <div class="modal-header" style="background: #0F294A; color: #fff; border-radius: 8px 8px 0 0;">
          <button type="button" class="close" data-dismiss="modal" style="color: #fff;">&times;</button>
          <h4 class="modal-title"><i class="fa fa-edit"></i> Edit Akun Pengguna</h4>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Nama Lengkap Pegawai / Wali <span class="text-danger">*</span></label>
            <input type="text" name="name" id="edit_name" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Alamat Email <span class="text-danger">*</span></label>
            <input type="email" name="email" id="edit_email" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Peran Sekolah (Role) <span class="text-danger">*</span></label>
            <select name="role" id="edit_role" class="form-control" required>
              <option value="1">1. Admin TU (Kelola Data Master & Akun Pengguna)</option>
              <option value="2">2. Bendahara (Kelola Kas Masuk & Kas Keluar)</option>
              <option value="3">3. Kepala Sekolah (Monitoring & Laporan Keuangan)</option>
              <option value="4">4. Wali Murid (Akses Portal Tagihan Siswa)</option>
            </select>
          </div>
          <div class="form-group">
            <label>Kata Sandi Baru <small class="text-muted">(Kosongkan jika tidak ingin mengubah sandi)</small></label>
            <input type="password" name="password" class="form-control" placeholder="Biarkan kosong jika tidak diubah" minlength="6">
          </div>
          <div class="form-group">
            <div class="checkbox">
              <label>
                <input type="checkbox" name="active" id="edit_active" value="1"> Status Akun Aktif
              </label>
            </div>
          </div>
        </div>
        <div class="modal-footer" style="background: #f8fafc; border-radius: 0 0 8px 8px;">
          <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary" style="background: #0F294A; border-color: #0F294A;"><i class="fa fa-save"></i> Perbarui Akun</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- MODAL RESET SANDI -->
<div class="modal fade" id="modalResetPassword" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content" style="border-radius: 8px;">
      <form action="<?= base_url('Pengguna/reset_password') ?>" method="post">
        <input type="hidden" name="id" id="reset_user_id">
        <div class="modal-header" style="background: #d97706; color: #fff; border-radius: 8px 8px 0 0;">
          <button type="button" class="close" data-dismiss="modal" style="color: #fff;">&times;</button>
          <h4 class="modal-title"><i class="fa fa-key"></i> Atur Ulang Sandi</h4>
        </div>
        <div class="modal-body">
          <p id="reset_user_info" style="font-size: 13px;"></p>
          <div class="form-group">
            <label>Kata Sandi Baru <span class="text-danger">*</span></label>
            <input type="password" name="new_password" class="form-control" required placeholder="Minimal 6 karakter" minlength="6">
          </div>
        </div>
        <div class="modal-footer" style="background: #f8fafc; border-radius: 0 0 8px 8px;">
          <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-warning btn-sm"><i class="fa fa-check"></i> Simpan Sandi</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  function editUser(user) {
    $('#edit_id').val(user.id);
    $('#edit_name').val(user.name);
    $('#edit_email').val(user.email);
    $('#edit_role').val(user.role);
    $('#edit_active').prop('checked', user.active == '1');
    $('#modalEditUser').modal('show');
  }

  function resetUserPassword(id, name) {
    $('#reset_user_id').val(id);
    $('#reset_user_info').html('Atur ulang kata sandi baru untuk pengguna: <strong>' + name + '</strong>');
    $('#modalResetPassword').modal('show');
  }
</script>

