<?php

namespace App\Controllers;

use App\Models\M_SawRepair;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class SawRepair extends BaseController
{
  public function __construct()
  {
    $this->M_SawRepair = new M_SawRepair();
    $this->session = \Config\Services::session();
  }

  public function index($bulan = null)
  {
    if ($bulan == '') {
      $bulan = date('Y-m-d');
    }
    $data['data'] = $this->M_SawRepair->getAll($bulan);
    $data['data_operator'] = $this->M_SawRepair->get_operator();

    return view('pages/saw_repair/home', $data);
  }

  public function save_data()
  {
    $tanggal_produksi = $this->request->getPost('tanggal_produksi');
    $shift = $this->request->getPost('shift');
    $operator = $this->request->getPost('operator');

    $data = [
      'tanggal_produksi' => $tanggal_produksi,
      'shift' => $shift,
      'operator' => $operator
    ];

    $id_saw_repair = $this->M_SawRepair->save_data($data);

    return redirect()->to(base_url('saw_repair/detail_saw_repair/' . $id_saw_repair));
  }

  public function detail_lhp_saw_repair($id)
  {
    $data['data_type_battery'] = $this->M_SawRepair->get_data_type_battery();
    $data['data_saw_repair'] = $this->M_SawRepair->get_data_by_id($id);
    $data['detail_saw_repair'] = $this->M_SawRepair->get_data_detail_by_id($id);
    $data['data_operator'] = $this->M_SawRepair->get_operator();

    return view('pages/saw_repair/detail_saw_repair', $data);
  }

  public function update()
  {
    $id_lhp_saw_repair = $this->request->getPost('id_saw_repair');
    $tanggal_produksi = $this->request->getPost('tanggal_produksi');
    $shift = $this->request->getPost('shift');
    $operator = $this->request->getPost('operator');
    $keterangan = $this->request->getPost('keterangan');

    $data = [
      'tanggal_produksi' => $tanggal_produksi,
      'shift' => $shift,
      'operator' => $operator,
      'keterangan' => $keterangan
    ];

    $this->M_SawRepair->update_data($id_lhp_saw_repair, $data);

    $type_battery = $this->request->getPost('type_battery');
    for ($i = 0; $i < count($type_battery); $i++) {
      $data_detail = [
        'id_lhp_saw_repair' => $id_lhp_saw_repair,
        'type_battery' => $type_battery[$i],
        'qty' => $this->request->getPost('qty')[$i],
      ];
      $this->M_SawRepair->update_data_detail($this->request->getPost('id_detail_lhp_saw_repair')[$i], $data_detail);
    }

    return redirect()->to(base_url('saw_repair/detail_saw_repair/' . $id_lhp_saw_repair));
  }

  public function delete($id)
  {
    $this->M_SawRepair->delete_data($id);
    return redirect()->to(base_url('saw_repair'));
  }

  public function download()
  {
    $start_date = $this->request->getPost('start_date');
    $end_date = $this->request->getPost('end_date');

    //data sheet lhp
    $data_lhp = $this->M_SawRepair->get_data_saw_repair($start_date, $end_date);
    // $data_lhp = $model->get_all_lhp_by_month($date);
    // dd($fix_data_detail_lhp);
    // Membuat objek Spreadsheet baru
    $spreadsheet = new Spreadsheet();

    // Menambahkan data ke worksheet
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Saw Repair');
    $data = array(
      array('Tanggal Produksi', 'Shift', 'Operator', 'Type Battery', 'Qty Repair (Pcs)'),
    );
    $isExist = [];
    if ($data_lhp !== NULL) {
      foreach ($data_lhp as $dl) {
        $data[] = array($dl['tanggal_produksi'], $dl['shift'], $dl['operator'], $dl['type_battery'], $dl['qty']);
      };
    }

    // Memasukkan data array ke dalam worksheet
    $sheet->fromArray($data);

    // Mengatur header respons HTTP
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="Data Saw Repair.xlsx"');
    header('Cache-Control: max-age=0');

    ob_end_clean();
    // Membuat objek Writer untuk menulis spreadsheet ke output
    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit();
  }
}
