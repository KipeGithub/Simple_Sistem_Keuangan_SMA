<div class="row">
  <div class="col-md-10 col-md-offset-1">
    <div class="box box-primary" style="border-top-color: #0F294A; border-radius: 8px;">
      <div class="box-header with-border">
        <h3 class="box-title" style="font-weight: 700; color: #0F294A;"><i class="fa fa-line-chart"></i> Laporan Aktivitas (Surplus / Defisit) - Entitas Nonlaba ISAK 35</h3>
        <div class="box-tools pull-right">
          <button onclick="window.print()" class="btn btn-sm btn-default"><i class="fa fa-print"></i> Cetak Laporan</button>
        </div>
      </div>
      <div class="box-body" style="padding: 25px 30px;">
        <div class="text-center" style="margin-bottom: 25px;">
          <h4 style="margin: 0; font-weight: 700; color: #0F294A; text-transform: uppercase;">SMK DUMMY KIPE</h4>
          <h3 style="margin: 4px 0; font-weight: 800; font-size: 20px;">LAPORAN AKTIVITAS KEUANGAN</h3>
          <p style="margin: 0; color: #64748b; font-size: 13px;">Periode Berjalan Tahun Ajaran 2026/2027 (Standar Entitas Nonlaba ISAK 35)</p>
        </div>

        <table class="table table-bordered">
          <tbody>
            <!-- PENDAPATAN -->
            <tr style="background: #0F294A; color: #fff;">
              <th colspan="2" style="font-size: 13px;">I. PENDAPATAN SEKOLAH</th>
            </tr>
            <tr>
              <td style="padding-left: 25px;">Pendapatan SPP & Biaya Praktik Siswa</td>
              <td class="text-right" width="220"><?= rupiah($aktivitas['pendapatan_spp']) ?></td>
            </tr>
            <tr>
              <td style="padding-left: 25px;">Pendapatan Dana BOS Reguler Pemerintah</td>
              <td class="text-right"><?= rupiah($aktivitas['pendapatan_bos']) ?></td>
            </tr>
            <tr style="background: #f1f5f9; font-weight: bold;">
              <td>JUMLAH PENDAPATAN SEKOLAH</td>
              <td class="text-right" style="color: #0F294A; font-size: 14px;"><?= rupiah($aktivitas['total_pendapatan']) ?></td>
            </tr>

            <!-- BEBAN -->
            <tr style="background: #0D6E52; color: #fff;">
              <th colspan="2" style="font-size: 13px;">II. BEBAN OPERASIONAL PENDIDIKAN</th>
            </tr>
            <tr>
              <td style="padding-left: 25px;">Realisasi Belanja BOS Program RKAS (8 SNP)</td>
              <td class="text-right"><?= rupiah($aktivitas['beban_bos']) ?></td>
            </tr>
            <tr>
              <td style="padding-left: 25px;">Beban Gaji & Honor Guru / Pegawai</td>
              <td class="text-right"><?= rupiah($aktivitas['beban_honor']) ?></td>
            </tr>
            <tr>
              <td style="padding-left: 25px;">Beban Pengeluaran Operasional Lainnya</td>
              <td class="text-right"><?= rupiah($aktivitas['beban_lainnya']) ?></td>
            </tr>
            <tr style="background: #f1f5f9; font-weight: bold;">
              <td>JUMLAH BEBAN OPERASIONAL SEKOLAH</td>
              <td class="text-right" style="color: #e11d48; font-size: 14px;"><?= rupiah($aktivitas['total_beban']) ?></td>
            </tr>

            <!-- PERUBAHAN ASET NETO (SURPLUS / DEFISIT) -->
            <tr style="background: #e2e8f0; font-size: 15px; font-weight: 800;">
              <th>KENAIKAN / (PENURUNAN) ASET NETO (SURPLUS / DEFISIT BERJALAN)</th>
              <th class="text-right" style="color: <?= ($aktivitas['surplus_defisit'] >= 0) ? '#0D6E52' : '#e11d48' ?>;">
                <?= rupiah($aktivitas['surplus_defisit']) ?>
              </th>
            </tr>
          </tbody>
        </table>

        <div style="margin-top: 25px; padding: 15px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 12px; color: #475569;">
          <strong>Catatan ISAK 35:</strong> Seluruh surplus operasional tidak dibagikan kepada pemilik/pengurus, melainkan diakumulasikan ke dalam <em>Aset Neto Tanpa Pembatasan</em> untuk reinvestasi sarana dan mutu pendidikan kejuruan SMK.
        </div>
      </div>
    </div>
  </div>
</div>

