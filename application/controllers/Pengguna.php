<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller Manajemen Akun Pengguna Sekolah (Khusus Admin TU)
 * SMK DUMMY KIPE
 */
class Pengguna extends CI_Controller {

    private $schoolName = 'SMK DUMMY KIPE';

    public function __construct() {
        parent::__construct();
        is_login();

        // Hak Akses Ketat: Hanya Admin TU (Role 1) yang diizinkan mengelola akun pengguna
        if ((int) $this->session->userdata('role') !== 1) {
            http_response_code(403);
            header("HTTP/1.1 403 Forbidden");
            show_error('Akses Ditolak: Fitur Manajemen Pengguna hanya dapat diakses oleh Admin TU.', 403, 'Akses Terbatas');
            exit;
        }

        get_breadcrumb();
    }

    public function index() {
        $this->breadcrumb->append_crumb('Data Master', 'School_finance/siswa');
        $this->breadcrumb->append_crumb('Manajemen Pengguna', 'Pengguna');

        $data['title'] = 'Manajemen Pengguna | ' . $this->schoolName;
        $data['judul'] = 'Manajemen Akun Pengguna';
        $data['icon']  = 'fa fa-user-plus';
        $data['users'] = $this->db->order_by('role', 'ASC')->order_by('name', 'ASC')->get('users')->result_array();

        $this->template->views('Backend/Pengguna/v_Pengguna', $data);
    }

    public function simpan() {
        $this->load->library('form_validation');

        $id = $this->input->post('id');
        $isEdit = !empty($id);

        $this->form_validation->set_rules('name', 'Nama Lengkap', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('role', 'Peran Akun', 'trim|required|in_list[1,2,3,4]');

        if (!$isEdit) {
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[users.email]');
            $this->form_validation->set_rules('password', 'Kata Sandi', 'trim|required|min_length[6]');
        }

        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('error', validation_errors('<p>', '</p>'));
            redirect('Pengguna');
            return;
        }

        $email = $this->input->post('email', true);
        $name  = $this->input->post('name', true);
        $role  = (int) $this->input->post('role');
        $active = $this->input->post('active') ? '1' : '0';

        // Cek duplikasi email saat edit
        if ($isEdit) {
            $existing = $this->db->get_where('users', ['email' => $email, 'id !=' => $id])->row();
            if ($existing) {
                $this->session->set_flashdata('error', 'Alamat email tersebut telah digunakan oleh pengguna lain!');
                redirect('Pengguna');
                return;
            }
        }

        $userData = [
            'name'   => $name,
            'email'  => $email,
            'role'   => $role,
            'active' => $active,
        ];

        // Jika password diisi
        $password = $this->input->post('password');
        if (!empty($password)) {
            $userData['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        if ($isEdit) {
            $this->db->where('id', $id)->update('users', $userData);
            $this->session->set_flashdata('success', 'Data akun pengguna "' . htmlspecialchars($name) . '" berhasil diperbarui!');
        } else {
            $userData['gambar'] = 'user.png';
            $userData['created_on'] = date('Y-m-d H:i:s');
            $this->db->insert('users', $userData);
            $this->session->set_flashdata('success', 'Akun pengguna baru "' . htmlspecialchars($name) . '" berhasil ditambahkan!');
        }

        redirect('Pengguna');
    }

    public function reset_password() {
        $id = $this->input->post('id');
        $newPass = $this->input->post('new_password');

        if (empty($id) || strlen($newPass) < 6) {
            $this->session->set_flashdata('error', 'Kata sandi baru minimal harus 6 karakter.');
            redirect('Pengguna');
            return;
        }

        $user = $this->db->get_where('users', ['id' => $id])->row_array();
        if (!$user) {
            $this->session->set_flashdata('error', 'Pengguna tidak ditemukan.');
            redirect('Pengguna');
            return;
        }

        $this->db->where('id', $id)->update('users', [
            'password' => password_hash($newPass, PASSWORD_DEFAULT)
        ]);

        $this->session->set_flashdata('success', 'Kata sandi untuk akun ' . htmlspecialchars($user['name']) . ' berhasil diatur ulang!');
        redirect('Pengguna');
    }

    public function hapus($id) {
        $currentUserId = (int) $this->session->userdata('id');
        if ((int) $id === $currentUserId) {
            $this->session->set_flashdata('error', 'Anda tidak dapat menghapus akun Anda sendiri yang sedang aktif digunakan!');
            redirect('Pengguna');
            return;
        }

        $user = $this->db->get_where('users', ['id' => $id])->row_array();
        if ($user) {
            $this->db->where('id', $id)->delete('users');
            $this->session->set_flashdata('success', 'Akun pengguna "' . htmlspecialchars($user['name']) . '" berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Data pengguna tidak ditemukan.');
        }

        redirect('Pengguna');
    }
}
