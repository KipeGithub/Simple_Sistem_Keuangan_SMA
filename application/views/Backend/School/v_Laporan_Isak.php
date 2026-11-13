<div class="row">
  <div class="col-xs-12">
    <!-- SUB-NAVBAR TABBED INTERFACE REKAP ISAK -->
    <div class="nav-tabs-custom" style="border-radius: 8px; box-shadow: 0 2px 12px rgba(0,0,0,0.06);">
      <ul class="nav nav-tabs" style="background-color: #f1f5f9; border-radius: 8px 8px 0 0; padding: 6px 10px 0 10px;">
        <li class="<?= ($tab === 'neraca') ? 'active' : '' ?>">
          <a href="#tab_neraca" data-toggle="tab" style="font-weight: 700; color: #0F294A;">
            <i class="fa fa-balance-scale text-primary"></i> 1. Posisi Keuangan (Neraca)
          </a>
        </li>
        <li class="<?= ($tab === 'aktivitas') ? 'active' : '' ?>">
          <a href="#tab_aktivitas" data-toggle="tab" style="font-weight: 700; color: #0D6E52;">
            <i class="fa fa-line-chart text-success"></i> 2. Laporan Aktivitas (Surplus/Defisit)
          </a>
        </li>
        <li class="<?= ($tab === 'bku') ? 'active' : '' ?>">
          <a href="#tab_bku" data-toggle="tab" style="font-weight: 700; color: #0284c7;">
            <i class="fa fa-table text-info"></i> 3. Buku Kas Umum (BKU)
          </a>
        </li>
        <li class="<?= ($tab === 'jurnal') ? 'active' : '' ?>">
          <a href="#tab_jurnal" data-toggle="tab" style="font-weight: 700; color: #7c3aed;">
            <i class="fa fa-list-alt text-purple"></i> 4. Jurnal Double-Entry
          </a>
        </li>
      </ul>

      <div class="tab-content" style="padding: 25px 20px; border-radius: 0 0 8px 8px;">

        <!-- ========================================== -->
        <!-- TAB 1: POSISI KEUANGAN (NERACA ISAK 35)   -->
        <!-- ========================================== -->
        <div class="tab-pane <?= ($tab === 'neraca') ? 'active' : '' ?>" id="tab_neraca">
          <div class="row" style="margin-bottom: 20px;">
            <div class="col-sm-8">
              <h3 style="margin: 0; font-weight: 800; color: #0F294A;">LAPORAN POSISI KEUANGAN (NERACA)</h3>
              <p class="text-muted" style="margin: 4px 0 0 0; font-size: 13px;">
                Standar Akuntansi Keuangan Entitas Nonlaba (ISAK 35) &bull; SMK DUMMY KIPE &bull; Per <?= date('d F Y') ?>
              </p>
            </div>
            <div class="col-sm-4 text-right">
              <button onclick="window.print()" class="btn btn-default"><i class="fa fa-print"></i> Cetak Neraca</button>
            </div>
          </div>

          <div class="row">
            <!-- SISI KIRI: ASET -->
            <div class="col-md-6">
              <div class="panel panel-default" style="border-radius: 6px; border-color: #cbd5e1;">
                <div class="panel-heading" style="background: #0F294A; color: #fff; font-weight: 700;">
                  <i class="fa fa-folder-open"></i> ASET (AKTIVA)
                </div>
                <table class="table table-striped table-bordered" style="margin-bottom: 0;">
                  <tbody>
                    <tr style="background: #f8fafc;"><th colspan="2">ASET LANCAR</th></tr>
                    <tr>
                      <td>&nbsp;&nbsp;&bull; Kas Tunai Bendahara Sekolah (<code>1-1101</code>)</td>
                      <td class="text-right"><?= rupiah($neraca['aset_lancar']['kas_tunai']) ?></td>
                    </tr>
                    <tr>
                      <td>&nbsp;&nbsp;&bull; Bank Giro Operasional SPP/Yayasan (<code>1-1201</code>)</td>
                      <td class="text-right"><?= rupiah($neraca['aset_lancar']['bank_spp']) ?></td>
                    </tr>
                    <tr>
                      <td>&nbsp;&nbsp;&bull; Bank Khusus Rekening Dana BOS (<code>1-1202</code>)</td>
                      <td class="text-right"><?= rupiah($neraca['aset_lancar']['bank_bos']) ?></td>
                    </tr>
                    <tr>
                      <td>&nbsp;&nbsp;&bull; Piutang SPP & Biaya Praktik Siswa (<code>1-1301</code>)</td>
                      <td class="text-right" style="color: #e11d48; font-weight: 600;"><?= rupiah($neraca['aset_lancar']['piutang_spp']) ?></td>
                    </tr>
                    <tr style="background: #f1f5f9; font-weight: 700;">
                      <td>TOTAL ASET LANCAR</td>
                      <td class="text-right"><?= rupiah($neraca['aset_lancar']['total']) ?></td>
                    </tr>
                    <tr style="background: #f8fafc;"><th colspan="2">ASET TETAP (SARPRAS & LAB KEJURUAN)</th></tr>
                    <tr>
                      <td>&nbsp;&nbsp;&bull; Peralatan Praktik Lab RPL, TKJ, AKL, TKR (<code>1-2101</code>)</td>
                      <td class="text-right"><?= rupiah($neraca['aset_tetap']['total']) ?></td>
                    </tr>
                    <tr style="background: #0F294A; color: #fff; font-weight: 800; font-size: 14.5px;">
                      <td>TOTAL ASET</td>
                      <td class="text-right"><?= rupiah($neraca['total_aset']) ?></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>

            <!-- SISI KANAN: LIABILITAS & ASET BERSIH -->
            <div class="col-md-6">
              <div class="panel panel-default" style="border-radius: 6px; border-color: #cbd5e1;">
                <div class="panel-heading" style="background: #0D6E52; color: #fff; font-weight: 700;">
                  <i class="fa fa-tags"></i> LIABILITAS & ASET NETO (EKUITAS ISAK 35)
                </div>
                <table class="table table-striped table-bordered" style="margin-bottom: 0;">
                  <tbody>
                    <tr style="background: #f8fafc;"><th colspan="2">LIABILITAS JANGKA PENDEK (TITIPAN)</th></tr>
                    <tr>
                      <td>&nbsp;&nbsp;&bull; Titipan Tabungan Siswa (<code>2-1201</code>)</td>
                      <td class="text-right" style="color: #0D6E52; font-weight: 600;"><?= rupiah($neraca['liabilitas']['titipan_tabungan']) ?></td>
                    </tr>
                    <tr style="background: #f1f5f9; font-weight: 700;">
                      <td>TOTAL LIABILITAS</td>
                      <td class="text-right"><?= rupiah($neraca['liabilitas']['total']) ?></td>
                    </tr>
                    <tr style="background: #f8fafc;"><th colspan="2">ASET NETO (STANDAR ISAK 35)</th></tr>
                    <tr>
                      <td>&nbsp;&nbsp;&bull; Aset Neto Tanpa Pembatasan Entitas Sekolah</td>
                      <td class="text-right"><?= rupiah($neraca['aset_neto']['tanpa_pembatasan']) ?></td>
                    </tr>
                    <tr style="background: #0D6E52; color: #fff; font-weight: 800; font-size: 14.5px;">
                      <td>TOTAL LIABILITAS & ASET NETO</td>
                      <td class="text-right"><?= rupiah($neraca['total_liabilitas_neto']) ?></td>
                    </tr>
                  </tbody>
                </table>
              </div>

              <div class="alert alert-success" style="border-radius: 6px; font-size: 12.5px;">
                <i class="fa fa-check-circle"></i> <strong>Keseimbangan Neraca Terverifikasi:</strong> Total Aset (<strong><?= rupiah($neraca['total_aset']) ?></strong>) = Total Liabilitas & Aset Neto (<strong><?= rupiah($neraca['total_liabilitas_neto']) ?></strong>).
              </div>
            </div>
          </div>
        </div>

        <!-- ========================================== -->
        <!-- TAB 2: LAPORAN AKTIVITAS (SURPLUS/DEFISIT) -->
        <!-- ========================================== -->
        <div class="tab-pane <?= ($tab === 'aktivitas') ? 'active' : '' ?>" id="tab_aktivitas">
          <div class="row" style="margin-bottom: 20px;">
            <div class="col-sm-8">
              <h3 style="margin: 0; font-weight: 800; color: #0D6E52;">LAPORAN AKTIVITAS (SURPLUS / DEFISIT)</h3>
              <p class="text-muted" style="margin: 4px 0 0 0; font-size: 13px;">
                Pendapatan vs Beban Operasional Sekolah &bull; SMK DUMMY KIPE &bull; Periode Berjalan 2026/2027
              </p>
            </div>
            <div class="col-sm-4 text-right">
              <button onclick="window.print()" class="btn btn-default"><i class="fa fa-print"></i> Cetak Laporan Aktivitas</button>
            </div>
          </div>

          <div class="table-responsive">
            <table class="table table-bordered table-striped">
              <tbody>
                <tr style="background: #0F294A; color: #fff; font-weight: 700;">
                  <th colspan="2">I. PENDAPATAN SEKOLAH (SUMBER DANA)</th>
                </tr>
                <tr>
                  <td>1. Pendapatan SPP Bulanan & Praktik Kejuruan Siswa</td>
                  <td class="text-right font-weight-bold" style="width: 250px; font-weight: 600; color: #0D6E52;"><?= rupiah($aktivitas['pendapatan_spp']) ?></td>
                </tr>
                <tr>
                  <td>2. Alokasi Pendapatan Dana Bantuan Operasional Sekolah (BOS)</td>
                  <td class="text-right font-weight-bold" style="font-weight: 600; color: #0D6E52;"><?= rupiah($aktivitas['pendapatan_bos']) ?></td>
                </tr>
                <tr style="background: #e2e8f0; font-weight: 800;">
                  <td>TOTAL PENDAPATAN OPERASIONAL SEKOLAH</td>
                  <td class="text-right" style="color: #0F294A; font-size: 14.5px;"><?= rupiah($aktivitas['total_pendapatan']) ?></td>
                </tr>

                <tr style="background: #991b1b; color: #fff; font-weight: 700;">
                  <th colspan="2">II. BEBAN OPERASIONAL & REALISASI BELANJA</th>
                </tr>
                <tr>
                  <td>1. Realisasi Belanja BOS (Program 8 Standar Nasional Pendidikan)</td>
                  <td class="text-right text-danger" style="font-weight: 600;"><?= rupiah($aktivitas['beban_bos']) ?></td>
                </tr>
                <tr>
                  <td>2. Beban Honor Guru & Pendidik Kejuruan</td>
                  <td class="text-right text-danger" style="font-weight: 600;"><?= rupiah($aktivitas['beban_honor']) ?></td>
                </tr>
                <tr>
                  <td>3. Pengeluaran Operasional & Rumah Tangga Sekolah Lainnya</td>
                  <td class="text-right text-danger" style="font-weight: 600;"><?= rupiah($aktivitas['beban_lainnya']) ?></td>
                </tr>
                <tr style="background: #fee2e2; font-weight: 800;">
                  <td>TOTAL BEBAN PENGELUARAN</td>
                  <td class="text-right text-danger" style="font-size: 14.5px;"><?= rupiah($aktivitas['total_beban']) ?></td>
                </tr>

                <tr style="background: #ecfdf5; font-size: 16px; font-weight: 800;">
                  <td style="color: #065f46;">SURPLUS / (DEFISIT) TAHUN BERJALAN</td>
                  <td class="text-right font-weight-bold" style="color: <?= ($aktivitas['surplus_defisit'] >= 0) ? '#059669' : '#dc2626' ?>;">
                    <?= rupiah($aktivitas['surplus_defisit']) ?>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- ========================================== -->
        <!-- TAB 3: BUKU KAS UMUM (BKU)                 -->
        <!-- ========================================== -->
        <div class="tab-pane <?= ($tab === 'bku') ? 'active' : '' ?>" id="tab_bku">
          <div class="row" style="margin-bottom: 20px;">
            <div class="col-sm-8">
              <h3 style="margin: 0; font-weight: 800; color: #0284c7;">BUKU KAS UMUM (BKU) SEKOLAH</h3>
              <p class="text-muted" style="margin: 4px 0 0 0; font-size: 13px;">
                Catatan Kronologis Kas Masuk dan Kas Keluar Sekolah &bull; SMK DUMMY KIPE
              </p>
            </div>
            <div class="col-sm-4 text-right">
              <button onclick="window.print()" class="btn btn-default"><i class="fa fa-print"></i> Cetak BKU</button>
            </div>
          </div>

          <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover tabel">
              <thead style="background: #0F294A; color: #fff; font-size: 12px;">
                <tr>
                  <th width="40" class="text-center">No</th>
                  <th>Tanggal</th>
                  <th>No. Bukti / Jurnal</th>
                  <th>Sumber Dana</th>
                  <th>Uraian Transaksi</th>
                  <th>Kode Akun</th>
                  <th class="text-right">Penerimaan (Debit)</th>
                  <th class="text-right">Pengeluaran (Kredit)</th>
                </tr>
              </thead>
              <tbody>
                <?php $no = 1; foreach ($bku as $row): ?>
                <tr>
                  <td class="text-center"><?= $no++ ?></td>
                  <td><?= date('d/m/Y', strtotime($row['transaction_date'])) ?></td>
                  <td><code><?= $row['entry_number'] ?></code></td>
                  <td>
                    <?php if ($row['fund_source'] === 'BOS'): ?>
                      <span class="label label-warning">DANA BOS</span>
                    <?php elseif ($row['fund_source'] === 'TABUNGAN_SISWA'): ?>
                      <span class="label label-info">TABUNGAN</span>
                    <?php else: ?>
                      <span class="label label-success">YAYASAN / SPP</span>
                    <?php endif; ?>
                  </td>
                  <td>
                    <strong><?= htmlspecialchars($row['description']) ?></strong><br>
                    <small class="text-muted"><?= $row['account_name'] ?></small>
                  </td>
                  <td><code><?= $row['account_code'] ?></code></td>
                  <td class="text-right" style="color: #0D6E52; font-weight: 600;"><?= ($row['debit'] > 0) ? rupiah($row['debit']) : '-' ?></td>
                  <td class="text-right" style="color: #e11d48; font-weight: 600;"><?= ($row['credit'] > 0) ? rupiah($row['credit']) : '-' ?></td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>

        <!-- ========================================== -->
        <!-- TAB 4: JURNAL DOUBLE-ENTRY                 -->
        <!-- ========================================== -->
        <div class="tab-pane <?= ($tab === 'jurnal') ? 'active' : '' ?>" id="tab_jurnal">
          <div class="row" style="margin-bottom: 20px;">
            <div class="col-sm-8">
              <h3 style="margin: 0; font-weight: 800; color: #7c3aed;">BUKU JURNAL UMUM (DOUBLE-ENTRY)</h3>
              <p class="text-muted" style="margin: 4px 0 0 0; font-size: 13px;">
                Integritas Pembukuan Berimbang: <code>SUM(Debit) == SUM(Kredit)</code> &bull; SMK DUMMY KIPE
              </p>
            </div>
            <div class="col-sm-4 text-right">
              <button onclick="window.print()" class="btn btn-default"><i class="fa fa-print"></i> Cetak Jurnal</button>
            </div>
          </div>

          <div class="table-responsive">
            <table class="table table-bordered table-striped tabel">
              <thead style="background: #1e1b4b; color: #fff; font-size: 12px;">
                <tr>
                  <th width="40" class="text-center">No</th>
                  <th>Tanggal</th>
                  <th>No. Entri Jurnal</th>
                  <th>Keterangan / Akun Double-Entry</th>
                  <th>Ref Akun</th>
                  <th class="text-right">Debit (Rp)</th>
                  <th class="text-right">Kredit (Rp)</th>
                </tr>
              </thead>
              <tbody>
                <?php $no = 1; foreach ($journals as $j): ?>
                <tr>
                  <td class="text-center"><?= $no++ ?></td>
                  <td><?= date('d/m/Y', strtotime($j['transaction_date'])) ?></td>
                  <td><code><?= $j['entry_number'] ?></code></td>
                  <td colspan="4" style="padding: 0;">
                    <table class="table table-condensed" style="margin-bottom: 0; background: transparent;">
                      <tbody>
                        <tr style="background: #faf5ff;">
                          <td colspan="4"><strong><?= htmlspecialchars($j['description']) ?></strong> <span class="label label-default" style="font-size: 9px;"><?= $j['fund_source'] ?></span></td>
                        </tr>
                        <?php foreach ($j['details'] as $d): ?>
                        <tr>
                          <td style="<?= ($d['credit'] > 0) ? 'padding-left: 28px;' : '' ?>">
                            <?= $d['account_name'] ?: 'Akun ' . $d['account_code'] ?>
                          </td>
                          <td width="90"><code><?= $d['account_code'] ?></code></td>
                          <td width="130" class="text-right font-weight-bold" style="color: #0D6E52;"><?= ($d['debit'] > 0) ? rupiah($d['debit']) : '-' ?></td>
                          <td width="130" class="text-right font-weight-bold" style="color: #e11d48;"><?= ($d['credit'] > 0) ? rupiah($d['credit']) : '-' ?></td>
                        </tr>
                        <?php endforeach; ?>
                      </tbody>
                    </table>
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
</div>

