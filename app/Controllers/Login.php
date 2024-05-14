<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\M_Login;

class Login extends BaseController
{

    public function __construct()
    {
        $this->M_Login = new M_Login();
        $this->session = \Config\Services::session();
    }
    public function index()
    {
        return view('login');
    }

    public function proses_login()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $data = $this->M_Login->cek_login($username, $password);

        if (!empty($data)) {
            $session_data = [
                'id_user' => $data['id_user'],
                'username' => $data['username'],
                'nama' => $data['nama'],
                'npk' => $data['npk'],
                'departemen' => $data['departemen'],
                'seksi' => $data['seksi'],
                'line' => $data['line'],
                'level' => $data['level'],
                'otorisasi' => $data['otorisasi'],
                'id_divisi' => $data['id_divisi'],
                'id_departement' => $data['id_departement'],
                'id_section' => $data['id_section'],
                'id_sub_section' => $data['id_sub_section'],
                'is_login' => true,
                'type_user' => 'organik'
            ];
            $this->session->set($session_data);
            return redirect()->to(base_url('dashboard'));
        } else {
            $this->session->setFlashdata('error', 'Username atau Password Salah');
            return redirect()->to(base_url('login'));
        }
    }

    public function logout()
    {
        $this->session->destroy();
        return redirect()->to(base_url('login'));
    }
}
