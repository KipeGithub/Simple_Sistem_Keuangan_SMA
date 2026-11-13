<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model Akuntansi Keuangan Sekolah ISAK 35 & Administrasi SMA/SMK
 * SMK DUMMY KIPE
 */
class M_School_Finance extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    // ==========================================
    // 1. DASBOR EKSEKUTIF KEUANGAN SEKOLAH
    // ==========================================
    public function get_dashboard_summary() {
        $curMonth = date('n');
        $curYear  = date('Y');

        // Total Penerimaan SPP & Biaya Sekolah Bulan Ini
        $this->db->select_sum('amount');
        $this->db->where('MONTH(payment_date)', $curMonth);
        $this->db->where('YEAR(payment_date)', $curYear);
        $sppBulanIni = $this->db->get('pembayaran_sekolah')->row()->amount ?: 0.00;

        // Total Realisasi Belanja BOS
        $this->db->select_sum('realized_amount');
        $belanjaBos = $this->db->get('rkas_anggaran')->row()->realized_amount ?: 0.00;

        // Pagu RKAS & Sisa Pagu
        $this->db->select_sum('allocated_amount');
        $totalPagu = $this->db->get('rkas_anggaran')->row()->allocated_amount ?: 0.00;

        $this->db->select_sum('remaining_amount');
        $sisaPagu = $this->db->get('rkas_anggaran')->row()->remaining_amount ?: 0.00;

        // Total Tunggakan Siswa Aktif
        $this->db->select_sum('net_amount');
        $this->db->where_in('status', ['UNPAID', 'PARTIAL']);
        $totalTagihanTunggakan = $this->db->get('tagihan_siswa')->row()->net_amount ?: 0.00;

        $this->db->select_sum('paid_amount');
        $this->db->where_in('status', ['UNPAID', 'PARTIAL']);
        $totalTerbayarTunggakan = $this->db->get('tagihan_siswa')->row()->paid_amount ?: 0.00;
        $totalTunggakan = $totalTagihanTunggakan - $totalTerbayarTunggakan;

        // Total Titipan Tabungan Siswa
        $this->db->select_sum('balance');
        $this->db->where('status', 'AKTIF');
        $totalTabungan = $this->db->get('tabungan_siswa')->row()->balance ?: 0.00;

        // Saldo Rekening Kas & Bank
        $rekeningKas = $this->db->get('rekening_kas')->result_array();

        // 5 Pembayaran Terkini
        $this->db->select('p.*, s.name as student_name, s.nis, s.major_code, t.title as bill_title');
        $this->db->from('pembayaran_sekolah p');
        $this->db->join('siswa s', 's.id = p.student_id');
        $this->db->join('tagihan_siswa t', 't.id = p.bill_id');
        $this->db->order_by('p.payment_date', 'DESC');
        $this->db->limit(5);
        $recentPayments = $this->db->get()->result_array();

        return [
            'spp_bulan_ini'    => (float) $sppBulanIni,
            'belanja_bos'      => (float) $belanjaBos,
            'total_pagu'       => (float) $totalPagu,
            'sisa_pagu'        => (float) $sisaPagu,
            'total_tunggakan'  => (float) $totalTunggakan,
            'total_tabungan'   => (float) $totalTabungan,
            'rekening_kas'     => $rekeningKas,
            'recent_payments'  => $recentPayments,
        ];
    }

    // ==========================================
    // 2. DATA SISWA & JURUSAN SMK
    // ==========================================
    public function get_students($filter = []) {
        $this->db->select('s.*, j.name as major_name, k.nama as class_name');
        $this->db->from('siswa s');
        $this->db->join('jurusan j', 'j.code = s.major_code', 'left');
        $this->db->join('kelas k', 'k.id = s.kelas', 'left');

        if (!empty($filter['major_code'])) {
            $this->db->where('s.major_code', $filter['major_code']);
        }
        if (!empty($filter['kelas'])) {
            $this->db->where('s.kelas', $filter['kelas']);
        }
        if (!empty($filter['search'])) {
            $this->db->group_start();
            $this->db->like('s.name', $filter['search']);
            $this->db->or_like('s.nis', $filter['search']);
            $this->db->or_like('s.nisn', $filter['search']);
            $this->db->or_like('s.parent_email', $filter['search']);
            $this->db->group_end();
        }

        $this->db->order_by('s.name', 'ASC');
        return $this->db->get()->result_array();
    }

    public function get_student_by_id($id) {
        $this->db->select('s.*, j.name as major_name, k.nama as class_name');
        $this->db->from('siswa s');
        $this->db->join('jurusan j', 'j.code = s.major_code', 'left');
        $this->db->join('kelas k', 'k.id = s.kelas', 'left');
        $this->db->where('s.id', $id);
        return $this->db->get()->row_array();
    }

    // ==========================================
    // 3. TAGIHAN SPP & GENERATOR MASSAL
    // ==========================================
    public function get_bills($filter = []) {
        $this->db->select('t.*, s.name as student_name, s.nis, s.nisn, s.major_code, k.nama as class_name');
        $this->db->from('tagihan_siswa t');
        $this->db->join('siswa s', 's.id = t.student_id');
        $this->db->join('kelas k', 'k.id = s.kelas', 'left');

        if (!empty($filter['status'])) {
            $this->db->where('t.status', $filter['status']);
        }
        if (!empty($filter['bill_type'])) {
            $this->db->where('t.bill_type', $filter['bill_type']);
        }
        if (!empty($filter['student_id'])) {
            $this->db->where('t.student_id', $filter['student_id']);
        }
        if (!empty($filter['major_code'])) {
            $this->db->where('s.major_code', $filter['major_code']);
        }

        $this->db->order_by('t.due_date', 'DESC');
        return $this->db->get()->result_array();
    }

    /**
     * Generator Tagihan SPP Bulanan Massal
     */
    public function generate_spp_massal($academicYear, $month, $year, $majorCode = null, $kelas = null) {
        $monthNames = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        $monthName = $monthNames[(int)$month] ?? "Bulan $month";

        $this->db->select('s.*, j.monthly_spp');
        $this->db->from('siswa s');
        $this->db->join('jurusan j', 'j.code = s.major_code', 'left');
        $this->db->where('s.status', 'Aktif');

        if (!empty($majorCode)) {
            $this->db->where('s.major_code', $majorCode);
        }
        if (!empty($kelas)) {
            $this->db->where('s.kelas', $kelas);
        }

        $students = $this->db->get()->result_array();
        $generatedCount = 0;

        foreach ($students as $stu) {
            // Cek apakah tagihan bulan ini sudah pernah dibuat
            $exists = $this->db->get_where('tagihan_siswa', [
                'student_id' => $stu['id'],
                'bill_type'  => 'SPP',
                'month'      => $month,
                'year'       => $year,
            ])->row();

            if (!$exists) {
                $baseAmount = $stu['monthly_spp'] ?: 350000.00;
                $discountPercent = (float) $stu['discount_percent'];
                $discountAmount = ($baseAmount * $discountPercent) / 100;
                $netAmount = $baseAmount - $discountAmount;

                $billNumber = 'SPP-' . $year . str_pad($month, 2, '0', STR_PAD_LEFT) . '-' . $stu['id'];
                $dueDate = date('Y-m-10', strtotime("$year-$month-01"));

                $billTitle = "SPP $monthName $year";
                if ($discountPercent > 0) {
                    $billTitle .= " (Potongan {$discountPercent}%)";
                }

                $this->db->insert('tagihan_siswa', [
                    'bill_number'     => $billNumber,
                    'student_id'      => $stu['id'],
                    'academic_year'   => $academicYear,
                    'bill_type'       => 'SPP',
                    'month'           => $month,
                    'year'            => $year,
                    'title'           => $billTitle,
                    'amount'          => $baseAmount,
                    'discount_amount' => $discountAmount,
                    'net_amount'      => $netAmount,
                    'paid_amount'     => 0.00,
                    'status'          => 'UNPAID',
                    'due_date'        => $dueDate,
                ]);
                $generatedCount++;
            }
        }

        return $generatedCount;
    }

    /**
     * Proses Pembayaran Tagihan & Pembuatan Kuitansi + Jurnal Otomatis Double-Entry
     */
    public function bayar_tagihan($billId, $payAmount, $cashAccountCode, $paymentMethod, $receivedBy, $note = '') {
        $bill = $this->db->get_where('tagihan_siswa', ['id' => $billId])->row_array();
        if (!$bill) {
            return ['status' => false, 'message' => 'Tagihan tidak ditemukan'];
        }

        $sisaTagihan = $bill['net_amount'] - $bill['paid_amount'];
        if ($payAmount > $sisaTagihan) {
            $payAmount = $sisaTagihan;
        }

        $newPaidAmount = $bill['paid_amount'] + $payAmount;
        $newStatus = ($newPaidAmount >= $bill['net_amount']) ? 'PAID' : 'PARTIAL';

        // 1. Update status tagihan
        $this->db->where('id', $billId)->update('tagihan_siswa', [
            'paid_amount' => $newPaidAmount,
            'status'      => $newStatus,
        ]);

        // 2. Catat pembayaran & nomor kuitansi
        $receiptNumber = 'KWT/SMK/' . date('Y/m/') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
        $this->db->insert('pembayaran_sekolah', [
            'receipt_number'    => $receiptNumber,
            'bill_id'           => $billId,
            'student_id'        => $bill['student_id'],
            'cash_account_code' => $cashAccountCode,
            'payment_date'      => date('Y-m-d'),
            'amount'            => $payAmount,
            'payment_method'    => $paymentMethod,
            'note'              => $note ?: "Pembayaran {$bill['title']}",
            'received_by'       => $receivedBy,
        ]);
        $paymentId = $this->db->insert_id();

        // 3. Tambah saldo rekening kas/bank yang dipilih
        $this->db->query("UPDATE rekening_kas SET balance = balance + ? WHERE code = ?", [$payAmount, $cashAccountCode]);

        // 4. Catat ke rekap kas umum bawaan (laporan)
        $this->M_General->update_kas('kas_masuk', $payAmount);

        // 5. JURNAL OTOMATIS DOUBLE-ENTRY BERIMBANG (SUM(Debit) == SUM(Kredit))
        // Debit: Rekening Kas/Bank yang menerima uang
        // Kredit: Akun Pendapatan (Pendapatan SPP / Biaya Praktik)
        $kreditAccount = ($bill['bill_type'] === 'PRAKTIK_SMK') ? '4-1201' : '4-1101';
        $kreditLabel   = ($bill['bill_type'] === 'PRAKTIK_SMK') ? 'Pendapatan Biaya Praktik Kejuruan' : 'Pendapatan SPP Bulanan Siswa';

        $entryNumber = 'JUR-' . date('Ymd') . '-' . str_pad($paymentId, 4, '0', STR_PAD_LEFT);
        $journalDesc = "Penerimaan {$bill['title']} (Kuitansi: $receiptNumber)";

        $this->db->insert('jurnal_umum', [
            'entry_number'     => $entryNumber,
            'transaction_date' => date('Y-m-d'),
            'reference_type'   => 'PEMBAYARAN_SPP',
            'reference_id'     => $paymentId,
            'fund_source'      => 'YAYASAN_SPP',
            'description'      => $journalDesc,
            'total_amount'     => $payAmount,
            'created_by'       => $receivedBy,
        ]);
        $journalId = $this->db->insert_id();

        // Detail Jurnal Berpasangan
        $this->db->insert('jurnal_detail', [
            'journal_id'   => $journalId,
            'account_code' => $cashAccountCode,
            'debit'        => $payAmount,
            'credit'       => 0.00,
            'note'         => 'Penerimaan Kas/Bank dari Siswa',
        ]);
        $this->db->insert('jurnal_detail', [
            'journal_id'   => $journalId,
            'account_code' => $kreditAccount,
            'debit'        => 0.00,
            'credit'       => $payAmount,
            'note'         => $kreditLabel,
        ]);

        return [
            'status'         => true,
            'payment_id'     => $paymentId,
            'receipt_number' => $receiptNumber,
            'message'        => 'Pembayaran berhasil dicatat dan kuitansi telah terbit.',
        ];
    }

    public function get_payment_receipt($paymentId) {
        $this->db->select('p.*, t.title as bill_title, t.net_amount as total_bill, s.name as student_name, s.nis, s.nisn, s.wali, s.major_code, j.name as major_name, k.nama as class_name, rk.name as bank_account_name');
        $this->db->from('pembayaran_sekolah p');
        $this->db->join('tagihan_siswa t', 't.id = p.bill_id');
        $this->db->join('siswa s', 's.id = p.student_id');
        $this->db->join('jurusan j', 'j.code = s.major_code', 'left');
        $this->db->join('kelas k', 'k.id = s.kelas', 'left');
        $this->db->join('rekening_kas rk', 'rk.code = p.cash_account_code', 'left');
        $this->db->where('p.id', $paymentId);
        return $this->db->get()->row_array();
    }

    // ==========================================
    // 4. TABUNGAN SISWA (LIABILITAS TITIPAN ISAK 35)
    // ==========================================
    public function get_savings_accounts() {
        $this->db->select('ts.*, s.name as student_name, s.nis, s.major_code, k.nama as class_name');
        $this->db->from('tabungan_siswa ts');
        $this->db->join('siswa s', 's.id = ts.student_id');
        $this->db->join('kelas k', 'k.id = s.kelas', 'left');
        $this->db->order_by('s.name', 'ASC');
        return $this->db->get()->result_array();
    }

    public function transaksi_tabungan($studentId, $type, $amount, $cashAccountCode, $createdBy, $description = '') {
        $tabungan = $this->db->get_where('tabungan_siswa', ['student_id' => $studentId])->row_array();
        if (!$tabungan) {
            // Buat rekening baru jika belum ada
            $accNumber = 'TAB-SMK-' . date('Y') . '-' . str_pad($studentId, 4, '0', STR_PAD_LEFT);
            $this->db->insert('tabungan_siswa', [
                'student_id'     => $studentId,
                'account_number' => $accNumber,
                'balance'        => 0.00,
                'status'         => 'AKTIF',
                'opened_at'      => date('Y-m-d'),
            ]);
            $tabungan = $this->db->get_where('tabungan_siswa', ['student_id' => $studentId])->row_array();
        }

        $currentBalance = (float) $tabungan['balance'];

        if ($type === 'TARIK' && $amount > $currentBalance) {
            return ['status' => false, 'message' => 'Saldo tabungan tidak mencukupi untuk melakukan penarikan!'];
        }

        $newBalance = ($type === 'SETOR') ? ($currentBalance + $amount) : ($currentBalance - $amount);
        $this->db->where('id', $tabungan['id'])->update('tabungan_siswa', ['balance' => $newBalance]);

        // Catat mutasi
        $receiptNumber = ($type === 'SETOR' ? 'SETOR-TAB-' : 'TARIK-TAB-') . date('Ymd') . '-' . mt_rand(100, 999);
        $this->db->insert('tabungan_mutasi', [
            'savings_id'        => $tabungan['id'],
            'receipt_number'    => $receiptNumber,
            'transaction_type'  => $type,
            'amount'            => $amount,
            'balance_after'     => $newBalance,
            'cash_account_code' => $cashAccountCode,
            'description'       => $description ?: ($type === 'SETOR' ? 'Setoran Tabungan Siswa' : 'Penarikan Tabungan Siswa'),
            'transaction_date'  => date('Y-m-d H:i:s'),
            'created_by'        => $createdBy,
        ]);
        $mutasiId = $this->db->insert_id();

        // Update saldo rekening kas
        if ($type === 'SETOR') {
            $this->db->query("UPDATE rekening_kas SET balance = balance + ? WHERE code = ?", [$amount, $cashAccountCode]);
            $this->M_General->update_kas('kas_masuk', $amount);
        } else {
            $this->db->query("UPDATE rekening_kas SET balance = balance - ? WHERE code = ?", [$amount, $cashAccountCode]);
            $this->M_General->update_kas('kas_keluar', $amount);
        }

        // JURNAL ISAK 35 (TABUNGAN = TITIPAN / LIABILITAS)
        $entryNumber = 'JUR-TAB-' . date('Ymd') . '-' . $mutasiId;
        $student = $this->db->get_where('siswa', ['id' => $studentId])->row_array();
        $journalDesc = ($type === 'SETOR' ? 'Penerimaan Setoran Tabungan' : 'Penarikan Tabungan') . " a.n " . ($student['name'] ?? 'Siswa');

        $this->db->insert('jurnal_umum', [
            'entry_number'     => $entryNumber,
            'transaction_date' => date('Y-m-d'),
            'reference_type'   => 'TABUNGAN_SISWA',
            'reference_id'     => $mutasiId,
            'fund_source'      => 'TABUNGAN_SISWA',
            'description'      => $journalDesc,
            'total_amount'     => $amount,
            'created_by'       => $createdBy,
        ]);
        $journalId = $this->db->insert_id();

        if ($type === 'SETOR') {
            // Debit: Kas Tunai, Kredit: Liabilitas Titipan Tabungan Siswa (2-1201)
            $this->db->insert('jurnal_detail', ['journal_id' => $journalId, 'account_code' => $cashAccountCode, 'debit' => $amount, 'credit' => 0.00, 'note' => 'Kas Bertambah']);
            $this->db->insert('jurnal_detail', ['journal_id' => $journalId, 'account_code' => '2-1201', 'debit' => 0.00, 'credit' => $amount, 'note' => 'Titipan Tabungan Siswa Bertambah (Liabilitas)']);
        } else {
            // Debit: Liabilitas Titipan Tabungan Siswa (2-1201), Kredit: Kas Tunai
            $this->db->insert('jurnal_detail', ['journal_id' => $journalId, 'account_code' => '2-1201', 'debit' => $amount, 'credit' => 0.00, 'note' => 'Titipan Tabungan Siswa Berkurang (Liabilitas)']);
            $this->db->insert('jurnal_detail', ['journal_id' => $journalId, 'account_code' => $cashAccountCode, 'debit' => 0.00, 'credit' => $amount, 'note' => 'Kas Berkurang']);
        }

        return ['status' => true, 'receipt_number' => $receiptNumber, 'balance' => $newBalance, 'message' => 'Transaksi tabungan berhasil dicatat.'];
    }

    public function get_savings_mutations($studentId) {
        $tabungan = $this->db->get_where('tabungan_siswa', ['student_id' => $studentId])->row_array();
        if (!$tabungan) return [];

        $this->db->where('savings_id', $tabungan['id']);
        $this->db->order_by('transaction_date', 'DESC');
        return $this->db->get('tabungan_mutasi')->result_array();
    }

    // ==========================================
    // 5. ANGGARAN RKAS (8 SNP) & BELANJA BOS
    // ==========================================
    public function get_rkas_budgets($fiscalYear = '2026/2027') {
        $this->db->where('fiscal_year', $fiscalYear);
        $this->db->order_by('snp_code', 'ASC');
        return $this->db->get('rkas_anggaran')->result_array();
    }

    public function belanja_rkas($budgetId, $cashAccountCode, $amount, $recipient, $description, $createdBy) {
        $budget = $this->db->get_where('rkas_anggaran', ['id' => $budgetId])->row_array();
        if (!$budget) {
            return ['status' => false, 'message' => 'Mata anggaran RKAS tidak ditemukan!'];
        }

        if ($amount > $budget['remaining_amount']) {
            return ['status' => false, 'message' => 'Realisasi belanja melebihi sisa pagu anggaran (' . rupiah($budget['remaining_amount']) . ')!'];
        }

        // Cek saldo kas/bank BOS
        $kas = $this->db->get_where('rekening_kas', ['code' => $cashAccountCode])->row_array();
        if (!$kas || $kas['balance'] < $amount) {
            return ['status' => false, 'message' => 'Saldo rekening penampung tidak mencukupi untuk pembayaran belanja ini!'];
        }

        // 1. Update pagu anggaran
        $newRealized  = $budget['realized_amount'] + $amount;
        $newRemaining = $budget['allocated_amount'] - $newRealized;
        $this->db->where('id', $budgetId)->update('rkas_anggaran', [
            'realized_amount'  => $newRealized,
            'remaining_amount' => $newRemaining,
        ]);

        // 2. Catat belanja BOS & SPJ
        $expenseNumber = 'SPJ-BOS/' . date('Y/m/') . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
        $this->db->insert('rkas_belanja', [
            'expense_number'    => $expenseNumber,
            'budget_id'         => $budgetId,
            'cash_account_code' => $cashAccountCode,
            'expense_date'      => date('Y-m-d'),
            'amount'            => $amount,
            'recipient'         => $recipient,
            'description'       => $description,
            'created_by'        => $createdBy,
        ]);
        $expenseId = $this->db->insert_id();

        // 3. Potong saldo rekening kas/bank
        $this->db->query("UPDATE rekening_kas SET balance = balance - ? WHERE code = ?", [$amount, $cashAccountCode]);
        $this->M_General->update_kas('kas_keluar', $amount);

        // 4. JURNAL DOUBLE-ENTRY ISAK 35
        // Debit: Beban Operasional / Praktik (5-1101 atau 5-1201), Kredit: Bank BOS (1-1202)
        $expenseAccountCode = '5-1101'; // Default Beban Operasional/Praktik
        if (strpos($budget['snp_code'], 'SNP-06') !== false) {
            $expenseAccountCode = '5-1201'; // Beban Sarpras
        } elseif (strpos($budget['snp_code'], 'SNP-05') !== false) {
            $expenseAccountCode = '5-1301'; // Beban Honor Guru
        }

        $entryNumber = 'JUR-BOS-' . date('Ymd') . '-' . $expenseId;
        $this->db->insert('jurnal_umum', [
            'entry_number'     => $entryNumber,
            'transaction_date' => date('Y-m-d'),
            'reference_type'   => 'BELANJA_BOS',
            'reference_id'     => $expenseId,
            'fund_source'      => 'BOS',
            'description'      => "Belanja SPJ BOS: {$budget['activity_name']} (Penerima: $recipient)",
            'total_amount'     => $amount,
            'created_by'       => $createdBy,
        ]);
        $journalId = $this->db->insert_id();

        $this->db->insert('jurnal_detail', ['journal_id' => $journalId, 'account_code' => $expenseAccountCode, 'debit' => $amount, 'credit' => 0.00, 'note' => 'Beban Realisasi Belanja RKAS']);
        $this->db->insert('jurnal_detail', ['journal_id' => $journalId, 'account_code' => $cashAccountCode, 'debit' => 0.00, 'credit' => $amount, 'note' => 'Pengeluaran Rekening Dana BOS']);

        return ['status' => true, 'expense_number' => $expenseNumber, 'message' => 'Belanja RKAS & SPJ Dana BOS berhasil dibukukan.'];
    }

    public function get_rkas_expenses() {
        $this->db->select('b.*, a.activity_name, a.snp_name, rk.name as bank_name');
        $this->db->from('rkas_belanja b');
        $this->db->join('rkas_anggaran a', 'a.id = b.budget_id');
        $this->db->join('rekening_kas rk', 'rk.code = b.cash_account_code', 'left');
        $this->db->order_by('b.expense_date', 'DESC');
        return $this->db->get()->result_array();
    }

    // ==========================================
    // 6. LAPORAN KEUANGAN NONLABA ISAK 35
    // ==========================================
    public function get_neraca_isak35() {
        // ASET LANCAR
        $kasTunai = (float) $this->db->get_where('rekening_kas', ['code' => '1-1101'])->row()->balance ?: 0.00;
        $bankSpp  = (float) $this->db->get_where('rekening_kas', ['code' => '1-1201'])->row()->balance ?: 0.00;
        $bankBos  = (float) $this->db->get_where('rekening_kas', ['code' => '1-1202'])->row()->balance ?: 0.00;

        // Piutang SPP
        $tagihan = (float) $this->db->select_sum('net_amount')->where_in('status', ['UNPAID', 'PARTIAL'])->get('tagihan_siswa')->row()->net_amount ?: 0.00;
        $terbayar = (float) $this->db->select_sum('paid_amount')->where_in('status', ['UNPAID', 'PARTIAL'])->get('tagihan_siswa')->row()->paid_amount ?: 0.00;
        $piutangSpp = $tagihan - $terbayar;

        $totalAsetLancar = $kasTunai + $bankSpp + $bankBos + $piutangSpp;

        // ASET TETAP
        $asetTetap = 185000000.00; // Peralatan Lab & Sarpras SMK
        $totalAset = $totalAsetLancar + $asetTetap;

        // LIABILITAS
        $titipanTabungan = (float) $this->db->select_sum('balance')->get('tabungan_siswa')->row()->balance ?: 0.00;
        $utangBiaya = 5000000.00;
        $totalLiabilitas = $titipanTabungan + $utangBiaya;

        // ASET NETO
        $asetNetoBos = $bankBos; // Pembatasan program BOS pemerintah
        $asetNetoYayasan = $totalAset - $totalLiabilitas - $asetNetoBos; // Tanpa pembatasan
        $totalAsetNeto = $asetNetoYayasan + $asetNetoBos;

        $totalLiabilitasDanAsetNeto = $totalLiabilitas + $totalAsetNeto;

        return [
            'kas_tunai'       => $kasTunai,
            'bank_spp'        => $bankSpp,
            'bank_bos'        => $bankBos,
            'piutang_spp'     => $piutangSpp,
            'total_lancar'    => $totalAsetLancar,
            'aset_tetap'      => $asetTetap,
            'total_aset'      => $totalAset,
            'tabungan_siswa'  => $titipanTabungan,
            'utang_biaya'     => $utangBiaya,
            'total_liabilitas'=> $totalLiabilitas,
            'aset_neto_yayasan'=> $asetNetoYayasan,
            'aset_neto_bos'   => $asetNetoBos,
            'total_aset_neto' => $totalAsetNeto,
            'total_pasiva'    => $totalLiabilitasDanAsetNeto,
        ];
    }

    public function get_aktivitas_isak35() {
        // PENDAPATAN
        $pendSpp = (float) $this->db->select_sum('amount')->get('pembayaran_sekolah')->row()->amount ?: 0.00;
        $pendBos = (float) $this->db->select_sum('allocated_amount')->get('rkas_anggaran')->row()->allocated_amount ?: 0.00;
        $totalPendapatan = $pendSpp + $pendBos;

        // BEBAN
        $bebanBos = (float) $this->db->select_sum('realized_amount')->get('rkas_anggaran')->row()->realized_amount ?: 0.00;
        $bebanHonor = (float) $this->db->select_sum('nominal')->get('gaji')->row()->nominal ?: 0.00;
        $bebanLainnya = (float) $this->db->select_sum('nominal')->get('pengeluaran')->row()->nominal ?: 0.00;
        $totalBeban = $bebanBos + $bebanHonor + $bebanLainnya;

        $surplusDefisit = $totalPendapatan - $totalBeban;

        return [
            'pendapatan_spp' => $pendSpp,
            'pendapatan_bos' => $pendBos,
            'total_pendapatan'=> $totalPendapatan,
            'beban_bos'      => $bebanBos,
            'beban_honor'    => $bebanHonor,
            'beban_lainnya'  => $bebanLainnya,
            'total_beban'    => $totalBeban,
            'surplus_defisit'=> $surplusDefisit,
        ];
    }

    public function get_bku($fundSource = 'SEMUA') {
        $this->db->select('j.*, jd.account_code, jd.debit, jd.credit, ca.name as account_name');
        $this->db->from('jurnal_umum j');
        $this->db->join('jurnal_detail jd', 'jd.journal_id = j.id');
        $this->db->join('coa_akun ca', 'ca.code = jd.account_code', 'left');

        if ($fundSource === 'DANA_BOS' || $fundSource === 'BOS') {
            $this->db->where('j.fund_source', 'BOS');
        } elseif ($fundSource === 'YAYASAN_SPP') {
            $this->db->where('j.fund_source', 'YAYASAN_SPP');
        }

        $this->db->order_by('j.transaction_date', 'ASC');
        $this->db->order_by('j.id', 'ASC');
        return $this->db->get()->result_array();
    }

    public function get_arrears() {
        $this->db->select('t.*, s.name as student_name, s.nis, s.nisn, s.wali, s.parent_email, s.major_code, j.name as major_name, k.nama as class_name');
        $this->db->from('tagihan_siswa t');
        $this->db->join('siswa s', 's.id = t.student_id');
        $this->db->join('jurusan j', 'j.code = s.major_code', 'left');
        $this->db->join('kelas k', 'k.id = s.kelas', 'left');
        $this->db->where_in('t.status', ['UNPAID', 'PARTIAL']);
        $this->db->order_by('t.due_date', 'ASC');
        return $this->db->get()->result_array();
    }

    public function get_journals() {
        $journals = $this->db->order_by('transaction_date', 'DESC')->get('jurnal_umum')->result_array();
        foreach ($journals as &$j) {
            $this->db->select('jd.*, ca.name as account_name');
            $this->db->from('jurnal_detail jd');
            $this->db->join('coa_akun ca', 'ca.code = jd.account_code', 'left');
            $this->db->where('jd.journal_id', $j['id']);
            $j['details'] = $this->db->get()->result_array();
        }
        return $journals;
    }
}
