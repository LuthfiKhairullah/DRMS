<?php

namespace App\Controllers;

use App\Models\M_MasterOperator;

class MasterOperator extends BaseController
{
  public function __construct()
  {
    $this->M_MasterOperator = new M_MasterOperator();
    $this->session = \Config\Services::session();
  }

  public function index()
  {
    $data['data_operator'] = $this->M_MasterOperator->get_data_operator();
    $data['data_karyawan'] = $this->M_MasterOperator->get_data_karyawan();
    $data['data_mesin'] = $this->M_MasterOperator->get_data_mesin();
    return view('pages/master_operator/home', $data);
  }

  public function add_operator()
  {
    $npk = $this->request->getPost('nama');
    $mesin = $this->request->getPost('mesin');
    if ($npk > 0) {
      $nama = $this->M_MasterOperator->get_nama_by_npk($npk);
      $cek_npk_operator = $this->M_MasterOperator->check_data_operator_by_npk($npk);
      $nama = ucwords(strtolower($nama[0]['nama']));
      $data_operator = [
        'nama' => $nama,
        'npk' => $npk,
        'mesin' => $mesin,
        'status' => 'Aktif',
      ];
      if (count($cek_npk_operator) > 0) {
        $save_data = $this->M_MasterOperator->update_data_operator($npk, $data_operator);
      } else {
        $save_data = $this->M_MasterOperator->update_data_operator('', $data_operator);
      }
      $this->session->setFlashdata('success', 'Group Leader berhasil ditambahkan');
    } else {
      $this->session->setFlashdata('failed', 'NPK Harus Diisi');
    }
    return redirect()->to(base_url('master_operator'));
  }

  public function update_operator()
  {
    $nama = $this->request->getPost('nama_edit');
    $npk = $this->request->getPost('npk_edit');
    $mesin = $this->request->getPost('mesin_edit');
    $data_operator = [
      'mesin' => $mesin,
    ];
    $this->M_MasterOperator->update_data_operator($npk, $data_operator);
    $this->session->setFlashdata('success', 'Group Leader berhasil diperbaharui');
    return redirect()->to(base_url('master_operator'));
  }

  public function delete_operator()
  {
    $npk = (int) $this->request->getPost('npk_delete');
    if ($npk != '') {
      $data = [
        'status' => 'Non Aktif'
      ];
      $this->M_MasterOperator->update_data_operator($npk, $data);
    }

    return redirect()->to(base_url('master_operator'));
  }
}
