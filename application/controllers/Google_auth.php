<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Dinonaktifkan: Otentikasi Google telah dilepas per kebijakan keamanan sekolah.
 */
class Google_auth extends CI_Controller {

    public function index() {
        redirect('Auth');
    }

    public function login() {
        redirect('Auth');
    }

    public function register() {
        redirect('Auth/registrasi');
    }

    public function recovery() {
        redirect('Auth/lupa_password');
    }

    public function callback() {
        redirect('Auth');
    }
}
