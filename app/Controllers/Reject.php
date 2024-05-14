<?php

namespace App\Controllers;

use App\Models\M_Reject;

class Reject extends BaseController
{
    public function __construct()
    {
        $this->M_Reject = new M_Reject();
        $this->session = \Config\Services::session();
    }

    public function index()
    {
        $data['data_reject_utama'] = $this->M_Reject->get_data_reject_utama();
        $data['data_reject_utama_amb'] = $this->M_Reject->get_data_reject_utama_amb();
        $data['data_reject'] = $this->M_Reject->get_data_reject();
        return view('data_master/master_rejection/home', $data);
    }

    public function add_reject_utama() 
    {
        $jenis_reject = $this->request->getPost('jenis_reject_utama');
        $value_btn = $this->request->getPost('value_btn');
        $data = [
            'jenis_reject' => strtoupper($jenis_reject),
            'AMB' => 1
        ];
        if($value_btn === 'add') {
            $save_data = $this->M_Reject->add_reject_utama($data);
        } else {
            $cek_reject = $this->M_Reject->cek_reject(strtoupper($jenis_reject));
            if(count($cek_reject) > 0)
                $update = $this->M_Reject->update_reject_utama($data, $cek_reject[0]['id_reject_utama'], $jenis_reject);
            else
                $save_data = $this->M_Reject->add_reject_utama($data);
        }

        return redirect()->to(base_url('reject'));
    }

    public function add_reject() 
    {
        $jenis_reject = $this->request->getPost('jenis_reject');
        $kategori_reject = $this->request->getPost('kategori_reject');
        $dashboard = $this->request->getPost('dashboard');

        $data = [
            'jenis_reject' => $jenis_reject,
            'kategori_reject' => $kategori_reject,
            'dashboard' => $dashboard,
            'AMB' => 1
        ];

        $save_data = $this->M_Reject->add_reject($data);

        return redirect()->to(base_url('reject'));
    }

    public function update_reject_utama()
    {
        $id_reject_utama = $this->request->getPost('edit_id_reject_utama');
        $jenis_reject = $this->request->getPost('edit_reject_utama');
        $jenis_reject_utama = $this->request->getPost('edit_jenis_reject_utama');

        $data = [
            'jenis_reject' => $jenis_reject_utama
        ];

        $update_data = $this->M_Reject->update_reject_utama($data, $id_reject_utama, $jenis_reject);

        return redirect()->to(base_url('reject'));
    }

    public function update_reject()
    {
        $id_reject = $this->request->getPost('edit_id_ketegori_reject');
        $jenis_reject = $this->request->getPost('edit_jenis_reject');
        $kategori_reject = $this->request->getPost('edit_kategori_reject');
        $dashboard = $this->request->getPost('edit_dashboard');

        $data = [
            'jenis_reject' => $jenis_reject,
            'kategori_reject' => $kategori_reject,
            'dashboard' => $dashboard,
            'AMB' => 1
        ];

        $save_data = $this->M_Reject->update_reject($data, $id_reject);

        return redirect()->to(base_url('reject'));
    }

    public function delete_reject_utama($id_reject_utama, $jenis_reject)
    {
        $data = [
            'AMB' => 0
        ];
        $delete_data = $this->M_Reject->delete_reject_utama($id_reject_utama, $jenis_reject, $data);

        return redirect()->to(base_url('reject'));
    }

    public function delete_reject($id_reject)
    {
        $delete_data = $this->M_Reject->delete_reject($id_reject);

        return redirect()->to(base_url('reject'));
    }
}