<div class="row">
  <div class="col-xs-12">
    <div class="box box-danger" style="border-radius: 8px;">
      <div class="box-header with-border">
        <h3 class="box-title" style="font-weight: 700; color: #e11d48;"><i class="fa fa-exclamation-circle"></i> Rekapitulasi Siswa Menunggak SPP & Biaya Sekolah</h3>
        <div class="box-tools pull-right">
          <button onclick="window.print()" class="btn btn-sm btn-default"><i class="fa fa-print"></i> Cetak Rekap Tunggakan</button>
        </div>
      </div>
      <div class="box-body">
        <div class="table-responsive">
          <table class="table table-bordered table-striped table-hover tabel">
            <thead style="background: #fff1f2; color: #9f1239; font-size: 12px;">
              <tr>
                <th width="40" class="text-center">No</th>
                <th>NIS / NISN</th>
                <th>Nama Siswa</th>
                <th>Jurusan & Rombel</th>
                <th>Uraian Tagihan</th>
                <th>Jatuh Tempo</th>
                <th>Orang Tua / No. Kontak</th>
                <th class="text-right">Total Tagihan</th>
                <th class="text-right">Sisa Tunggakan</th>
                <th width="80" class="text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($arrears)): ?>
                <tr><td colspan="10" class="text-center text-success" style="padding: 20px; font-weight: bold;"><i class="fa fa-check-circle"></i> Luar biasa! Tidak ada siswa yang memiliki tunggakan biaya sekolah saat ini.</td></tr>
              <?php else: ?>
                <?php $totalTunggakanSemua = 0; $no = 1; foreach ($arrears as $arr): ?>
                <?php 
                  $sisa = $arr['net_amount'] - $arr['paid_amount'];
                  $totalTunggakanSemua += $sisa;
                ?>
                <tr>
                  <td class="text-center"><?= $no++ ?></td>
                  <td>
                    <strong><?= $arr['nis'] ?></strong><br>
                    <small class="text-muted"><?= $arr['nisn'] ?: '-' ?></small>
                  </td>
                  <td>
                    <strong style="color: #0F294A;"><?= htmlspecialchars($arr['student_name']) ?></strong>
                  </td>
                  <td>
                    <span class="label label-primary" style="background: #0F294A;"><?= $arr['major_code'] ?></span>
                    <span class="label label-default"><?= $arr['class_name'] ?></span>
                  </td>
                  <td>
                    <strong><?= htmlspecialchars($arr['title']) ?></strong><br>
                    <small class="text-muted"><?= $arr['academic_year'] ?></small>
                  </td>
                  <td><span class="text-danger"><?= date('d/m/Y', strtotime($arr['due_date'])) ?></span></td>
                  <td>
                    <strong><?= htmlspecialchars($arr['wali']) ?></strong><br>
                    <small><i class="fa fa-envelope-o"></i> <?= $arr['parent_email'] ?: '-' ?></small>
                  </td>
                  <td class="text-right"><?= rupiah($arr['net_amount']) ?></td>
                  <td class="text-right font-weight-bold" style="font-weight: 700; color: #e11d48; font-size: 13.5px;">
                    <?= rupiah($sisa) ?>
                  </td>
                  <td class="text-center">
                    <?php if ((int)$this->session->userdata('role') === 2): ?>
                    <a href="<?= base_url('School_finance/bayar/' . $arr['id']) ?>" class="btn btn-xs btn-success" title="Bayar Sekarang"><i class="fa fa-credit-card"></i> Bayar</a>
                    <?php else: ?>
                    <span class="label label-danger" style="font-size: 10px;">Menunggak</span>
                    <?php endif; ?>
                  </td>
                </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
            <tfoot style="background: #f1f5f9; font-weight: bold; font-size: 14px;">
              <tr>
                <th colspan="8" class="text-right">TOTAL KESELURUHAN PIUTANG TUNGGAKAN SISWA:</th>
                <th class="text-right" style="color: #e11d48;"><?= rupiah($totalTunggakanSemua ?? 0) ?></th>
                <th></th>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

