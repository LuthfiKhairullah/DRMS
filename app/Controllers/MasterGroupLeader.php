<?php

namespace App\Controllers;

use App\Models\M_MasterGroupLeader;

class MasterGroupLeader extends BaseController
{
  public function __construct()
  {
    $this->M_MasterGroupLeader = new M_MasterGroupLeader();
    $this->session = \Config\Services::session();
  }

  public function index()
  {
    $data['data_group_leader'] = $this->M_MasterGroupLeader->get_data_group_leader();
    $data['data_pic_line'] = $this->M_MasterGroupLeader->get_data_pic_line();
    $data['data_line'] = $this->M_MasterGroupLeader->get_data_line();
    return view('pages/master_group_leader/home', $data);
  }

  public function add_group_leader()
  {
    $npk = $this->request->getPost('nama_pic');
    $id_line = $this->request->getPost('id_line');
    if ($npk > 0) {
      $nama_pic = $this->M_MasterGroupLeader->get_nama_by_npk($npk);
      if (count($nama_pic) > 0) $nama_pic = ucwords(strtolower($nama_pic[0]['nama']));
      $cek_npk_group_leader = $this->M_MasterGroupLeader->check_data_group_leader_by_npk($npk);
      $data_group_leader = [
        'nama_pic' => $nama_pic,
        'npk' => $npk,
        'id_line' => $id_line,
        'status' => 'Aktif',
      ];
      if (count($cek_npk_group_leader) > 0) {
        $save_data = $this->M_MasterGroupLeader->update_data_group_leader($npk, $data_group_leader);
      } else {
        $save_data = $this->M_MasterGroupLeader->update_data_group_leader('', $data_group_leader);
      }
      $this->session->setFlashdata('success', 'Group Leader berhasil ditambahkan');
    } else {
      $this->session->setFlashdata('failed', 'NPK Harus Diisi');
    }
    return redirect()->to(base_url('master_group_leader'));
  }

  public function update_group_leader()
  {
    $nama_pic = $this->request->getPost('nama_pic_edit');
    $npk = $this->request->getPost('npk_edit');
    $id_line = $this->request->getPost('id_line_edit');
    $data_group_leader = [
      'id_line' => $id_line,
    ];
    $this->M_MasterGroupLeader->update_data_group_leader($npk, $data_group_leader);
    $this->session->setFlashdata('success', 'Group Leader berhasil diperbaharui');
    return redirect()->to(base_url('master_group_leader'));
  }

  public function delete_group_leader()
  {
    $npk = (int) $this->request->getPost('npk_delete');
    if ($npk != '') {
      $data = [
        'status' => 'Non Aktif'
      ];
      $this->M_MasterGroupLeader->update_data_group_leader($npk, $data);
    }

    return redirect()->to(base_url('master_group_leader'));
  }
}
