<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller Manajemen Keuangan & Pembukuan Akuntansi SMA/SMK
 * SMK DUMMY KIPE
 */
class School_finance extends CI_Controller {

    private $schoolName = 'SMK DUMMY KIPE';

    public function __construct() {
        parent::__construct();
        is_login();
        get_breadcrumb();
        $this->load->model('M_School_Finance');
        $this->load->model('M_General');
    }

    /**
     * 1. Dasbor Eksekutif Keuangan SMA/SMK
     */
    public function index() {
        $this->dasbor();
    }

    public function dasbor() {
        // Jika peran adalah Wali Murid / Siswa (role 4), arahkan langsung ke Portal Siswa
        if ($this->session->userdata('role') == 4) {
            redirect('School_finance/portal');
            return;
        }

        $this->breadcrumb->append_crumb('Keuangan SMK', 'School_finance');
        $this->breadcrumb->append_crumb('Dasbor Keuangan', 'School_finance/dasbor');

        $data['title']   = 'Dasbor Eksekutif Keuangan | ' . $this->schoolName;
        $data['judul']   = 'Dasbor Eksekutif Keuangan';
        $data['icon']    = 'fa fa-dashboard';
        $data['summary'] = $this->M_School_Finance->get_dashboard_summary();

        $this->template->views('Backend/School/v_Dasbor', $data);
    }

    /**
     * 2. Manajemen Data Siswa & Jurusan SMK
     */
    public function siswa() {
        $this->breadcrumb->append_crumb('Keuangan SMK', 'School_finance');
        $this->breadcrumb->append_crumb('Data Siswa & Jurusan', 'School_finance/siswa');

        $filter['major_code'] = $this->input->get('major_code');
        $filter['kelas']      = $this->input->get('kelas');
        $filter['search']     = $this->input->get('search');

        $data['title']    = 'Data Siswa & Jurusan SMK | ' . $this->schoolName;
        $data['judul']    = 'Data Siswa & Jurusan';
        $data['icon']     = 'fa fa-users';
        $data['students'] = $this->M_School_Finance->get_students($filter);
        $data['majors']   = $this->db->get('jurusan')->result_array();
        $data['classes']  = $this->db->get('kelas')->result_array();
        $data['filter']   = $filter;

        $this->template->views('Backend/School/v_Siswa', $data);
    }

    public function simpan_siswa() {
        if ((int)$this->session->userdata('role') !== 1) {
            show_error('Akses Ditolak: Hanya Admin TU yang dapat mengelola data siswa.', 403, 'Akses Terbatas');
            return;
        }
        $data = [
            'name'               => $this->input->post('name', true),
            'nis'                => $this->input->post('nis', true),
            'nisn'               => $this->input->post('nisn', true),
            'major_code'         => $this->input->post('major_code', true),
            'sex'                => $this->input->post('sex', true),
            'status'             => 'Aktif',
            'wali'               => $this->input->post('wali', true),
            'parent_email'       => $this->input->post('parent_email', true),
            'scholarship_status' => $this->input->post('scholarship_status', true),
            'discount_percent'   => (float) $this->input->post('discount_percent'),
            'tempat'             => $this->input->post('tempat', true),
            'tanggal'            => $this->input->post('tanggal', true),
            'alamat'             => $this->input->post('alamat', true),
            'kelas'              => (int) $this->input->post('kelas'),
        ];

        $id = $this->input->post('id');
        if (!empty($id)) {
            $this->db->where('id', $id)->update('siswa', $data);
            $this->session->set_flashdata('success', 'Data siswa berhasil diperbarui!');
        } else {
            $this->db->insert('siswa', $data);
            $this->session->set_flashdata('success', 'Siswa baru berhasil ditambahkan!');
        }
        redirect('School_finance/siswa');
    }

    /**
     * 3. Tagihan SPP & Generator Massal
     */
    public function tagihan() {
        $this->breadcrumb->append_crumb('Keuangan SMK', 'School_finance');
        $this->breadcrumb->append_crumb('Tagihan & SPP Siswa', 'School_finance/tagihan');

        $filter['status']     = $this->input->get('status');
        $filter['bill_type']  = $this->input->get('bill_type');
        $filter['major_code'] = $this->input->get('major_code');

        $data['title']   = 'Tagihan SPP & Biaya Sekolah | ' . $this->schoolName;
        $data['judul']   = 'Tagihan SPP & Biaya Sekolah';
        $data['icon']    = 'fa fa-money';
        $data['bills']   = $this->M_School_Finance->get_bills($filter);
        $data['majors']  = $this->db->get('jurusan')->result_array();
        $data['classes'] = $this->db->get('kelas')->result_array();
        $data['filter']  = $filter;

        $this->template->views('Backend/School/v_Tagihan', $data);
    }

    public function generate_spp() {
        if (!in_array((int)$this->session->userdata('role'), [1, 2])) {
            show_error('Akses Ditolak: Hanya Admin TU dan Bendahara yang berwenang menerbitkan tagihan.', 403, 'Akses Terbatas');
            return;
        }
        $academicYear = $this->input->post('academic_year') ?: '2026/2027';
        $month        = (int) $this->input->post('month');
        $year         = (int) $this->input->post('year') ?: date('Y');
        $majorCode    = $this->input->post('major_code') ?: null;
        $kelas        = $this->input->post('kelas') ?: null;

        $count = $this->M_School_Finance->generate_spp_massal($academicYear, $month, $year, $majorCode, $kelas);

        $this->session->set_flashdata('success', "Berhasil menerbitkan $count tagihan SPP bulanan massal secara otomatis!");
        redirect('School_finance/tagihan');
    }

    public function bayar($billId) {
        if ((int)$this->session->userdata('role') !== 2) {
            show_error('Akses Ditolak: Hanya Bendahara yang berwenang mengakses modul pembayaran.', 403, 'Akses Terbatas');
            return;
        }
        $bill = $this->db->get_where('tagihan_siswa', ['id' => $billId])->row_array();
        if (!$bill) {
            redirect('School_finance/tagihan');
            return;
        }

        $student = $this->M_School_Finance->get_student_by_id($bill['student_id']);
        $cashAccounts = $this->db->get('rekening_kas')->result_array();

        $data['title']        = 'Pembayaran Tagihan Siswa | ' . $this->schoolName;
        $data['judul']        = 'Pembayaran Tagihan';
        $data['icon']         = 'fa fa-credit-card';
        $data['bill']         = $bill;
        $data['student']      = $student;
        $data['cashAccounts'] = $cashAccounts;

        $this->template->views('Backend/School/v_Bayar', $data);
    }

    public function proses_bayar() {
        if ((int)$this->session->userdata('role') !== 2) {
            show_error('Akses Ditolak: Hanya Bendahara yang berwenang memproses pembayaran tagihan.', 403, 'Akses Terbatas');
            return;
        }
        $billId          = (int) $this->input->post('bill_id');
        $amount          = (float) $this->input->post('amount');
        $cashAccountCode = $this->input->post('cash_account_code', true);
        $paymentMethod   = $this->input->post('payment_method', true) ?: 'TUNAI';
        $note            = $this->input->post('note', true);
        $receivedBy      = $this->session->userdata('name') ?: 'Bendahara Sekolah';

        $res = $this->M_School_Finance->bayar_tagihan($billId, $amount, $cashAccountCode, $paymentMethod, $receivedBy, $note);

        if ($res['status']) {
            $this->session->set_flashdata('success', $res['message']);
            redirect('School_finance/kuitansi/' . $res['payment_id']);
        } else {
            $this->session->set_flashdata('error', $res['message']);
            redirect('School_finance/tagihan');
        }
    }

    public function kuitansi($paymentId) {
        $payment = $this->M_School_Finance->get_payment_receipt($paymentId);
        if (!$payment) {
            redirect('School_finance/tagihan');
            return;
        }

        $data['title']   = 'Kuitansi Pembayaran Resmi - ' . $payment['receipt_number'];
        $data['payment'] = $payment;
        $this->load->view('Backend/School/v_Kuitansi', $data);
    }

    /**
     * 4. Tabungan Siswa (Liabilitas Titipan ISAK 35)
     */
    public function tabungan() {
        $this->breadcrumb->append_crumb('Keuangan SMK', 'School_finance');
        $this->breadcrumb->append_crumb('Tabungan Siswa', 'School_finance/tabungan');

        $data['title']        = 'Tabungan Siswa (Titipan ISAK 35) | ' . $this->schoolName;
        $data['judul']        = 'Tabungan Siswa';
        $data['icon']         = 'fa fa-archive';
        $data['accounts']     = $this->M_School_Finance->get_savings_accounts();
        $data['students']     = $this->db->order_by('name', 'ASC')->get('siswa')->result_array();
        $data['cashAccounts'] = $this->db->get('rekening_kas')->result_array();

        $this->template->views('Backend/School/v_Tabungan', $data);
    }

    public function proses_tabungan() {
        if ((int)$this->session->userdata('role') !== 2) {
            show_error('Akses Ditolak: Hanya Bendahara yang berwenang memproses transaksi tabungan.', 403, 'Akses Terbatas');
            return;
        }
        $studentId       = (int) $this->input->post('student_id');
        $type            = $this->input->post('transaction_type');
        $amount          = (float) $this->input->post('amount');
        $cashAccountCode = $this->input->post('cash_account_code', true) ?: '1-1101';
        $description     = $this->input->post('description', true);
        $createdBy       = $this->session->userdata('name') ?: 'Petugas Kasir';

        $res = $this->M_School_Finance->transaksi_tabungan($studentId, $type, $amount, $cashAccountCode, $createdBy, $description);

        if ($res['status']) {
            $this->session->set_flashdata('success', $res['message']);
        } else {
            $this->session->set_flashdata('error', $res['message']);
        }
        redirect('School_finance/tabungan');
    }

    public function buku_tabungan($studentId) {
        $student = $this->M_School_Finance->get_student_by_id($studentId);
        $mutations = $this->M_School_Finance->get_savings_mutations($studentId);
        $tabungan = $this->db->get_where('tabungan_siswa', ['student_id' => $studentId])->row_array();

        $data['title']     = 'Buku Mutasi Tabungan Siswa | ' . $this->schoolName;
        $data['judul']     = 'Mutasi Tabungan Siswa';
        $data['icon']      = 'fa fa-book';
        $data['student']   = $student;
        $data['tabungan']  = $tabungan;
        $data['mutations'] = $mutations;

        $this->template->views('Backend/School/v_Tabungan_Mutasi', $data);
    }

    /**
     * 5. Anggaran RKAS (8 SNP) & Belanja Dana BOS
     */
    public function rkas() {
        $this->breadcrumb->append_crumb('Keuangan SMK', 'School_finance');
        $this->breadcrumb->append_crumb('Anggaran RKAS & Belanja BOS', 'School_finance/rkas');

        $fiscalYear = $this->input->get('fiscal_year') ?: '2026/2027';

        $data['title']        = 'Pagu Anggaran RKAS & Realisasi Belanja BOS | ' . $this->schoolName;
        $data['judul']        = 'Anggaran RKAS 8 SNP';
        $data['icon']         = 'fa fa-pie-chart';
        $data['budgets']      = $this->M_School_Finance->get_rkas_budgets($fiscalYear);
        $data['expenses']     = $this->M_School_Finance->get_rkas_expenses();
        $data['cashAccounts'] = $this->db->get('rekening_kas')->result_array();
        $data['fiscalYear']   = $fiscalYear;

        $this->template->views('Backend/School/v_Rkas', $data);
    }

    public function proses_belanja_rkas() {
        if ((int)$this->session->userdata('role') !== 2) {
            show_error('Akses Ditolak: Hanya Bendahara yang berwenang memproses realisasi belanja.', 403, 'Akses Terbatas');
            return;
        }
        $budgetId        = (int) $this->input->post('budget_id');
        $amount          = (float) $this->input->post('amount');
        $cashAccountCode = $this->input->post('cash_account_code', true) ?: '1-1202';
        $recipient       = $this->input->post('recipient', true);
        $description     = $this->input->post('description', true);
        $createdBy       = $this->session->userdata('name') ?: 'Bendahara BOS';

        $res = $this->M_School_Finance->belanja_rkas($budgetId, $cashAccountCode, $amount, $recipient, $description, $createdBy);

        if ($res['status']) {
            $this->session->set_flashdata('success', $res['message']);
        } else {
            $this->session->set_flashdata('error', $res['message']);
        }
        redirect('School_finance/rkas');
    }

    /**
     * 6. Laporan Keuangan Nonlaba ISAK 35 & BKU
     */
    public function laporan_isak() {
        if ($this->session->userdata('role') == 4) {
            redirect('School_finance/portal');
            return;
        }

        $this->breadcrumb->append_crumb('Laporan Keuangan', 'School_finance/bku');
        $this->breadcrumb->append_crumb('Rekap ISAK (Entitas Nonlaba)', 'School_finance/laporan_isak');

        $tab = $this->input->get('tab') ?: 'neraca';
        $fundSource = $this->input->get('fund_source') ?: 'SEMUA';

        $data['title']      = 'Rekap Laporan ISAK 35 | ' . $this->schoolName;
        $data['judul']      = 'Rekap Laporan ISAK (Entitas Nonlaba)';
        $data['icon']       = 'fa fa-book';
        $data['tab']        = $tab;
        $data['fundSource'] = $fundSource;
        $data['neraca']     = $this->M_School_Finance->get_neraca_isak35();
        $data['aktivitas']  = $this->M_School_Finance->get_aktivitas_isak35();
        $data['bku']        = $this->M_School_Finance->get_bku($fundSource);
        $data['journals']   = $this->M_School_Finance->get_journals();

        $this->template->views('Backend/School/v_Laporan_Isak', $data);
    }

    public function neraca() {
        $this->breadcrumb->append_crumb('Keuangan SMK', 'School_finance');
        $this->breadcrumb->append_crumb('Laporan Posisi Keuangan (Neraca) ISAK 35', 'School_finance/neraca');

        $data['title']  = 'Laporan Posisi Keuangan (Neraca) ISAK 35 | ' . $this->schoolName;
        $data['judul']  = 'Laporan Posisi Keuangan (Neraca) ISAK 35';
        $data['icon']   = 'fa fa-balance-scale';
        $data['neraca'] = $this->M_School_Finance->get_neraca_isak35();

        $this->template->views('Backend/School/v_Neraca', $data);
    }

    public function aktivitas() {
        $this->breadcrumb->append_crumb('Keuangan SMK', 'School_finance');
        $this->breadcrumb->append_crumb('Laporan Aktivitas ISAK 35', 'School_finance/aktivitas');

        $data['title']     = 'Laporan Aktivitas ISAK 35 (Surplus/Defisit) | ' . $this->schoolName;
        $data['judul']     = 'Laporan Aktivitas ISAK 35';
        $data['icon']      = 'fa fa-line-chart';
        $data['aktivitas'] = $this->M_School_Finance->get_aktivitas_isak35();

        $this->template->views('Backend/School/v_Aktivitas', $data);
    }

    public function bku() {
        $this->breadcrumb->append_crumb('Keuangan SMK', 'School_finance');
        $this->breadcrumb->append_crumb('Buku Kas Umum (BKU)', 'School_finance/bku');

        $fundSource = $this->input->get('fund_source') ?: 'SEMUA';

        $data['title']      = 'Buku Kas Umum (BKU) Sekolah | ' . $this->schoolName;
        $data['judul']      = 'Buku Kas Umum (BKU) Sekolah';
        $data['icon']       = 'fa fa-table';
        $data['bku']        = $this->M_School_Finance->get_bku($fundSource);
        $data['fundSource'] = $fundSource;

        $this->template->views('Backend/School/v_Bku', $data);
    }

    public function tunggakan() {
        $this->breadcrumb->append_crumb('Keuangan SMK', 'School_finance');
        $this->breadcrumb->append_crumb('Rekapitulasi Tunggakan SPP', 'School_finance/tunggakan');

        $data['title']   = 'Rekapitulasi Tunggakan Siswa | ' . $this->schoolName;
        $data['judul']   = 'Rekapitulasi Tunggakan SPP & Biaya Sekolah';
        $data['icon']    = 'fa fa-exclamation-circle';
        $data['arrears'] = $this->M_School_Finance->get_arrears();

        $this->template->views('Backend/School/v_Tunggakan', $data);
    }

    public function jurnal() {
        $this->breadcrumb->append_crumb('Keuangan SMK', 'School_finance');
        $this->breadcrumb->append_crumb('Buku Jurnal Umum Double-Entry', 'School_finance/jurnal');

        $data['title']    = 'Buku Jurnal Umum Double-Entry | ' . $this->schoolName;
        $data['judul']    = 'Jurnal Umum (Double-Entry Bookkeeping)';
        $data['icon']     = 'fa fa-list-alt';
        $data['journals'] = $this->M_School_Finance->get_journals();

        $this->template->views('Backend/School/v_Jurnal', $data);
    }

    /**
     * 7. Portal Khusus Wali Murid / Siswa (Role 4)
     */
    public function portal() {
        $userId = $this->session->userdata('id');
        $userEmail = $this->session->userdata('email');

        // Cari siswa yang terhubung dengan akun ini
        $this->db->select('s.*, j.name as major_name, k.nama as class_name');
        $this->db->from('siswa s');
        $this->db->join('jurusan j', 'j.code = s.major_code', 'left');
        $this->db->join('kelas k', 'k.id = s.kelas', 'left');
        $this->db->group_start();
        $this->db->where('s.user_id', $userId);
        $this->db->or_where('s.parent_email', $userEmail);
        $this->db->group_end();
        $student = $this->db->get()->row_array();

        $bills = [];
        $payments = [];
        $savings = null;
        $savingsMutations = [];

        if ($student) {
            $bills = $this->db->where('student_id', $student['id'])->order_by('due_date', 'DESC')->get('tagihan_siswa')->result_array();
            
            $this->db->select('p.*, t.title as bill_title');
            $this->db->from('pembayaran_sekolah p');
            $this->db->join('tagihan_siswa t', 't.id = p.bill_id');
            $this->db->where('p.student_id', $student['id']);
            $this->db->order_by('p.payment_date', 'DESC');
            $payments = $this->db->get()->result_array();

            $savings = $this->db->get_where('tabungan_siswa', ['student_id' => $student['id']])->row_array();
            if ($savings) {
                $savingsMutations = $this->db->where('savings_id', $savings['id'])->order_by('transaction_date', 'DESC')->limit(10)->get('tabungan_mutasi')->result_array();
            }
        }

        $data['title']            = 'Portal Keuangan Siswa & Wali Murid | ' . $this->schoolName;
        $data['student']          = $student;
        $data['bills']            = $bills;
        $data['payments']         = $payments;
        $data['savings']          = $savings;
        $data['savingsMutations'] = $savingsMutations;

        $this->load->view('Backend/School/v_Portal_Wali', $data);
    }
}
