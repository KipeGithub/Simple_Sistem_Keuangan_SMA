<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller Autentikasi Pengguna & Sesi (SMK DUMMY KIPE)
 */
class Auth extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('M_General');
	}

	public function index() {
		if ($this->session->userdata('id')) {
			if ($this->session->userdata('role') == 4) {
				redirect('School_finance/portal');
			} else {
				redirect('School_finance/dasbor');
			}
			return;
		}

		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
		$this->form_validation->set_rules('password', 'Password', 'trim|required');

		if ($this->form_validation->run() == false) {
			$data['title'] = 'Masuk Sistem Informasi Keuangan | SMK DUMMY KIPE';
			$this->load->view('v_Login', $data);
		} else {
			$this->_login();
		}
	}

	private function _login() {
		$email = $this->input->post('email', true);
		$password = $this->input->post('password');

		$user = $this->db->get_where('users', ['email' => $email])->row_array();

		if ($user) {
			if ($user['active'] == '1') {
				if (password_verify($password, $user['password'])) {
					$sessionData = [
						'id'            => $user['id'],
						'role'          => (int) $user['role'],
						'name'          => $user['name'],
						'email'         => $user['email'],
						'avatar'        => $user['avatar'] ?: 'user.png',
						'auth_provider' => $user['auth_provider'] ?: 'local',
					];
					$this->session->set_userdata($sessionData);
					
					// Update catatan rekap kas harian
					$this->M_General->cek_laporan();

					// Arahkan ke landing page sesuai peran sekolah
					if ($user['role'] == 4) {
						redirect('School_finance/portal');
					} else {
						redirect('School_finance/dasbor');
					}
				} else {
					$this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><i class="fa fa-times-circle"></i> Kata sandi yang Anda masukkan salah.</div>');
					redirect('Auth');
				}
			} else {
				$this->session->set_flashdata('message', '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><i class="fa fa-clock-o"></i> Akun Anda belum diaktifkan oleh pihak sekolah. Silakan hubungi bagian Tata Usaha.</div>');
				redirect('Auth');
			}
		} else {
			$this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><i class="fa fa-exclamation-triangle"></i> Alamat email tidak terdaftar dalam sistem sekolah.</div>');
			redirect('Auth');
		}
	}

	public function registrasi() {
		if ($this->session->userdata('id')) {
			redirect('School_finance/dasbor');
			return;
		}

		$this->form_validation->set_rules('name', 'Nama Lengkap', 'trim|required');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[users.email]');
		$this->form_validation->set_rules('password', 'Kata Sandi', 'trim|required|min_length[6]|matches[password2]');
		$this->form_validation->set_rules('password2', 'Konfirmasi Kata Sandi', 'trim|required|matches[password]');

		if ($this->form_validation->run() == false) {
			$data['title'] = 'Pendaftaran Akun Wali Murid / Siswa | SMK DUMMY KIPE';
			$this->load->view('v_Registrasi', $data);
		} else {
			$email = $this->input->post('email', true);
			$name = $this->input->post('name', true);
			$pass = password_hash($this->input->post('password'), PASSWORD_DEFAULT);

			$newUser = [
				'email'         => $email,
				'name'          => $name,
				'password'      => $pass,
				'role'          => 4, // Wali Murid / Siswa
				'active'        => '1',
				'gambar'        => 'user.png',
				'auth_provider' => 'local',
				'parent_email'  => $email,
				'created_on'    => date('Y-m-d H:i:s'),
			];

			$this->db->insert('users', $newUser);
			$newUserId = $this->db->insert_id();

			// Otomatis kaitkan data siswa jika email cocok dengan parent_email
			$this->db->where('parent_email', $email)->update('siswa', ['user_id' => $newUserId]);

			$this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><i class="fa fa-check-circle"></i> Pendaftaran berhasil! Akun Wali Murid / Siswa Anda telah aktif. Silakan masuk di bawah ini.</div>');
			redirect('Auth');
		}
	}

	public function lupa_password() {
		$data['title'] = 'Lupa Kata Sandi & Pemulihan Akun | SMK DUMMY KIPE';
		$this->load->view('v_Lupa_Password', $data);
	}

	public function proses_lupa_password() {
		$email = $this->input->post('email', true);
		$user = $this->db->get_where('users', ['email' => $email])->row_array();

		if ($user) {
			$this->session->set_flashdata('message', '<div class="alert alert-info"><i class="fa fa-info-circle"></i> Permintaan Anda telah dicatat. Demi keamanan data sekolah, silakan hubungi Staf Tata Usaha (Admin TU) untuk mereset kata sandi akun Anda.</div>');
		} else {
			$this->session->set_flashdata('message', '<div class="alert alert-danger"><i class="fa fa-times-circle"></i> Alamat email tidak terdaftar dalam sistem sekolah.</div>');
		}
		redirect('Auth/lupa_password');
	}

	public function reset_password() {
		$data['title'] = 'Atur Ulang Kata Sandi Baru | SMK DUMMY KIPE';
		$this->load->view('v_Reset_Password', $data);
	}

	public function logout() {
		$this->session->sess_destroy();
		redirect('Auth');
	}
}
