<?php

namespace App\Controllers;

use App\Models\M_RejectWET;

class RejectWET extends BaseController
{
    public function __construct()
    {
        $this->M_RejectWET = new M_RejectWET();
        $this->session = \Config\Services::session();
    }

    public function index()
    {
        $data['data_reject_utama'] = $this->M_RejectWET->get_data_reject_utama();
        $data['data_reject_utama_wet'] = $this->M_RejectWET->get_data_reject_utama_wet();
        $data['data_reject'] = $this->M_RejectWET->get_data_reject();
        return view('data_master/master_rejection_WET/home', $data);
    }

    public function add_reject_utama() 
    {
        $jenis_reject = $this->request->getPost('jenis_reject_utama');
        $value_btn = $this->request->getPost('value_btn');
        $data = [
            'jenis_reject' => strtoupper($jenis_reject),
            'WET' => 1
        ];
        if($value_btn === 'add') {
            $save_data = $this->M_RejectWET->add_reject_utama($data);
        } else {
            $cek_reject = $this->M_RejectWET->cek_reject(strtoupper($jenis_reject));
            if(count($cek_reject) > 0)
                $update = $this->M_RejectWET->update_reject_utama($data, $cek_reject[0]['id_reject_utama'], $jenis_reject);
            else
                $save_data = $this->M_RejectWET->add_reject_utama($data);
        }


        return redirect()->to(base_url('reject_wet'));
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
            'WET' => 1
        ];

        $save_data = $this->M_RejectWET->add_reject($data);

        return redirect()->to(base_url('reject_wet'));
    }

    public function update_reject_utama()
    {
        $id_reject_utama = $this->request->getPost('edit_id_reject_utama');
        $jenis_reject = $this->request->getPost('edit_reject_utama');
        $jenis_reject_utama = $this->request->getPost('edit_jenis_reject_utama');

        $data = [
            'jenis_reject' => $jenis_reject_utama
        ];

        $update_data = $this->M_RejectWET->update_reject_utama($data, $id_reject_utama, $jenis_reject);

        return redirect()->to(base_url('reject_wet'));
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
            'WET' => 1
        ];

        $save_data = $this->M_RejectWET->update_reject($data, $id_reject);

        return redirect()->to(base_url('reject_wet'));
    }

    public function delete_reject_utama($id_reject_utama, $jenis_reject)
    {
        $data = [
            'WET' => 0
        ];
        $delete_data = $this->M_RejectWET->delete_reject_utama($id_reject_utama, $jenis_reject, $data);

        return redirect()->to(base_url('reject_wet'));
    }

    public function delete_reject($id_reject)
    {
        $delete_data = $this->M_RejectWET->delete_reject($id_reject);

        return redirect()->to(base_url('reject_wet'));
    }
}