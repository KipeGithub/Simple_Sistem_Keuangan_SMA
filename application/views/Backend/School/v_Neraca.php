<div class="row">
  <div class="col-md-10 col-md-offset-1">
    <div class="box box-primary" style="border-top-color: #0F294A; border-radius: 8px;">
      <div class="box-header with-border">
        <h3 class="box-title" style="font-weight: 700; color: #0F294A;"><i class="fa fa-balance-scale"></i> Laporan Posisi Keuangan (Neraca) - Entitas Nonlaba ISAK 35</h3>
        <div class="box-tools pull-right">
          <button onclick="window.print()" class="btn btn-sm btn-default"><i class="fa fa-print"></i> Cetak Laporan</button>
        </div>
      </div>
      <div class="box-body" style="padding: 25px 30px;">
        <!-- Header Laporan Sekolah -->
        <div class="text-center" style="margin-bottom: 25px;">
          <h4 style="margin: 0; font-weight: 700; color: #0F294A; text-transform: uppercase;">SMK DUMMY KIPE</h4>
          <h3 style="margin: 4px 0; font-weight: 800; font-size: 20px;">LAPORAN POSISI KEUANGAN (NERACA)</h3>
          <p style="margin: 0; color: #64748b; font-size: 13px;">Per Tanggal: <strong><?= date('d F Y') ?></strong> (Standar Akuntansi Nonlaba ISAK 35)</p>
        </div>

        <div class="row">
          <!-- SISI KIRI: ASET -->
          <div class="col-md-6">
            <table class="table table-bordered">
              <thead style="background: #0F294A; color: #fff;">
                <tr><th colspan="2" style="font-size: 13px;">ASET (AKTIVA)</th></tr>
              </thead>
              <tbody>
                <tr style="background: #f8fafc;"><td colspan="2"><strong>ASET LANCAR</strong></td></tr>
                <tr>
                  <td style="padding-left: 20px;">Kas Tunai Bendahara Sekolah</td>
                  <td class="text-right"><?= rupiah($neraca['kas_tunai']) ?></td>
                </tr>
                <tr>
                  <td style="padding-left: 20px;">Bank Operasional SPP & Yayasan</td>
                  <td class="text-right"><?= rupiah($neraca['bank_spp']) ?></td>
                </tr>
                <tr>
                  <td style="padding-left: 20px;">Bank Rekening Khusus Dana BOS</td>
                  <td class="text-right"><?= rupiah($neraca['bank_bos']) ?></td>
                </tr>
                <tr>
                  <td style="padding-left: 20px;">Piutang Tagihan SPP Siswa</td>
                  <td class="text-right"><?= rupiah($neraca['piutang_spp']) ?></td>
                </tr>
                <tr style="background: #f1f5f9; font-weight: 600;">
                  <td>Jumlah Aset Lancar</td>
                  <td class="text-right"><?= rupiah($neraca['total_lancar']) ?></td>
                </tr>

                <tr style="background: #f8fafc;"><td colspan="2"><strong>ASET TIDAK LANCAR / TETAP</strong></td></tr>
                <tr>
                  <td style="padding-left: 20px;">Peralatan Laboratorium & Komputer SMK</td>
                  <td class="text-right"><?= rupiah($neraca['aset_tetap']) ?></td>
                </tr>
                <tr style="background: #f1f5f9; font-weight: 600;">
                  <td>Jumlah Aset Tidak Lancar</td>
                  <td class="text-right"><?= rupiah($neraca['aset_tetap']) ?></td>
                </tr>
              </tbody>
              <tfoot style="background: #e2e8f0; font-size: 14px; font-weight: 800;">
                <tr>
                  <th>TOTAL ASET (AKTIVA)</th>
                  <th class="text-right" style="color: #0F294A;"><?= rupiah($neraca['total_aset']) ?></th>
                </tr>
              </tfoot>
            </table>
          </div>

          <!-- SISI KANAN: LIABILITAS & ASET NETO -->
          <div class="col-md-6">
            <table class="table table-bordered">
              <thead style="background: #0D6E52; color: #fff;">
                <tr><th colspan="2" style="font-size: 13px;">LIABILITAS & ASET NETO (PASIVA)</th></tr>
              </thead>
              <tbody>
                <tr style="background: #f8fafc;"><td colspan="2"><strong>LIABILITAS (KEWAJIBAN)</strong></td></tr>
                <tr>
                  <td style="padding-left: 20px;">Liabilitas Titipan Tabungan Siswa</td>
                  <td class="text-right"><?= rupiah($neraca['tabungan_siswa']) ?></td>
                </tr>
                <tr>
                  <td style="padding-left: 20px;">Utang Biaya Operasional / Pengadaan</td>
                  <td class="text-right"><?= rupiah($neraca['utang_biaya']) ?></td>
                </tr>
                <tr style="background: #f1f5f9; font-weight: 600;">
                  <td>Jumlah Liabilitas</td>
                  <td class="text-right"><?= rupiah($neraca['total_liabilitas']) ?></td>
                </tr>

                <tr style="background: #f8fafc;"><td colspan="2"><strong>ASET NETO (EKUITAS NONLABA)</strong></td></tr>
                <tr>
                  <td style="padding-left: 20px;">Aset Neto Tanpa Pembatasan (Yayasan / SPP)</td>
                  <td class="text-right"><?= rupiah($neraca['aset_neto_yayasan']) ?></td>
                </tr>
                <tr>
                  <td style="padding-left: 20px;">Aset Neto Dengan Pembatasan (Dana BOS Pemerintah)</td>
                  <td class="text-right"><?= rupiah($neraca['aset_neto_bos']) ?></td>
                </tr>
                <tr style="background: #f1f5f9; font-weight: 600;">
                  <td>Jumlah Aset Neto</td>
                  <td class="text-right"><?= rupiah($neraca['total_aset_neto']) ?></td>
                </tr>
              </tbody>
              <tfoot style="background: #e2e8f0; font-size: 14px; font-weight: 800;">
                <tr>
                  <th>TOTAL LIABILITAS & ASET NETO</th>
                  <th class="text-right" style="color: #0D6E52;"><?= rupiah($neraca['total_pasiva']) ?></th>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>

        <!-- Validasi Keseimbangan Neraca -->
        <?php $balanceDiff = abs($neraca['total_aset'] - $neraca['total_pasiva']); ?>
        <div class="alert <?= ($balanceDiff < 0.01) ? 'alert-success' : 'alert-danger' ?>" style="margin-top: 15px; border-radius: 6px; text-align: center;">
          <?php if ($balanceDiff < 0.01): ?>
            <i class="fa fa-check-circle"></i> <strong>NERACA SEIMBANG (BALANCED):</strong> Total Aset (Aktiva) sama dengan Total Liabilitas & Aset Neto (Pasiva).
          <?php else: ?>
            <i class="fa fa-times-circle"></i> <strong>NERACA TIDAK SEIMBANG!</strong> Selisih: <?= rupiah($balanceDiff) ?>.
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>

