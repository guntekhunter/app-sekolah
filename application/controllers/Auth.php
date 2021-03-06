<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
    }
    public function index()
    {
        //untuk membuat judul pada title halaman
        //$data dikirim ke setiap header agar halaman sesuai dengan title masing masing
        $data['title'] = 'Login user';
        $this->load->view('templates/auth_header', $data);
        $this->load->view('auth/login');
        $this->load->view('templates/auth_footer');
    }
    public function register()
    {
        //validasi data nama harus diisi didalam form
        $this->form_validation->set_rules('name', 'Name', 'required|trim');
        //untuk mengecek validasi data email unik atau tidak, dan emailnya sesuai dengan format email
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[user.email]', [
            'is_unique' => 'email ini sudah pernah digunakan'
        ]);
        //validasi untuk minimal panjang karakter dan kesamaan dengan password satunya 
        $this->form_validation->set_rules('password1', 'Password', 'required|trim|min_length[3]|matches[password2]', [
            'matches' => 'password tidak sama!',
            'min_length' => 'password terlalu pendek!'
        ]);
        $this->form_validation->set_rules('password2', 'Password', 'required|trim|matches[password1]');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Registrasi user';
            $this->load->view('templates/auth_header', $data);
            $this->load->view('auth/register');
            $this->load->view('templates/auth_footer');
        } else {
            $data =
                [
                    'name' => $this->input->post('name'),
                    'email' => $this->input->post('email'),
                    'image' => 'default.jpg',
                    'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),

                ];

            $this->db->insert('user', $data);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
            akun anda berhasil dibuat</div>');
            redirect('auth');
        }
    }
}
