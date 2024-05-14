<?php

namespace App\Controllers;

use App\Models\M_LineStopMCB;

class LineStopMCB extends BaseController
{
  public function __construct()
  {
    $this->M_LineStopMCB = new M_LineStopMCB();
    $this->session = \Config\Services::session();
  }

  public function index()
  {
    $data['data_breakdown'] = $this->M_LineStopMCB->get_data_breakdown();
    $data['data_jenis_breakdown'] = $this->M_LineStopMCB->get_data_jenis_line_stop();
    $data['data_dept_in_charge'] = $this->M_LineStopMCB->get_data_dept_in_charge();
    $data['data_perhitungan'] = $this->M_LineStopMCB->get_data_perhitungan();
    return view('data_master/master_line_stop_MCB/home', $data);
  }

  public function save()
  {
    $jenis_breakdown = $this->request->getPost('jenis_breakdown');
    $proses_breakdown = $this->request->getPost('proses_breakdown');
    $dept_in_charge = $this->request->getPost('dept_in_charge');
    $perhitungan = $this->request->getPost('perhitungan');

    $data_breakdown = [
      'jenis_breakdown' => $jenis_breakdown,
      'proses_breakdown' => $proses_breakdown,
      'dept_in_charge' => $dept_in_charge,
      'perhitungan' => $perhitungan,
      'status' => 'waiting',
      'MCB' => 1
    ];
    $model = new M_LineStopMCB();
    $model->save_data_breakdown($data_breakdown);
    return redirect()->to(base_url('line_stop_mcb'));
  }

  public function edit($id_breakdown)
  {
    $data['data_detail_breakdown'] = $this->M_LineStopMCB->get_detail_data_breakdown_by_id($id_breakdown);
    $data['data_jenis_breakdown'] = $this->M_LineStopMCB->get_data_jenis_line_stop();
    $data['data_dept_in_charge'] = $this->M_LineStopMCB->get_data_dept_in_charge();
    $data['data_perhitungan'] = $this->M_LineStopMCB->get_data_perhitungan();
    $session = \Config\Services::session();
    $data['session'] = $session->get('level');
    return view('data_master/master_line_stop_MCB/detail_line_stop', $data);
  }

  public function update_data_breakdown()
  {
    $id_breakdown = $this->request->getPost('id_breakdown');
    $approved = $this->request->getPost('approved');
    $model = new M_LineStopMCB();
    $jenis_breakdown = $this->request->getPost('jenis_breakdown');
    $proses_breakdown = $this->request->getPost('proses_breakdown');
    $dept_in_charge = $this->request->getPost('dept_in_charge');
    $perhitungan = $this->request->getPost('perhitungan');
    if ($approved === NULL) {
      $status = 'waiting';
    } else if ($approved === 'approved') {
      $status = 'approved';
    }
    $data_breakdown = [
      'jenis_breakdown' => $jenis_breakdown,
      'proses_breakdown' => $proses_breakdown,
      'dept_in_charge' => $dept_in_charge,
      'perhitungan' => $perhitungan,
      'status' => $status,
      'MCB' => 1
    ];
    $model->update_data_breakdown($id_breakdown, $data_breakdown);
    return redirect()->to(base_url('line_stop_mcb'));
  }
  
  public function delete_data_breakdown()
  {
    $id_breakdown = $this->request->getPost('id_breakdown');
    $model = new M_LineStopMCB();
    $model->delete_data_breakdown($id_breakdown);

    return redirect()->to(base_url('line_stop_mcb'));
  }
}
