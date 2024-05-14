<?php

namespace App\Controllers;

use App\Models\M_PotongBattery;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class PotongBattery extends BaseController
{
  public function __construct()
  {
    $this->M_PotongBattery = new M_PotongBattery();
    $this->session = \Config\Services::session();
  }

  public function index($bulan = null)
  {
    if ($bulan == '') {
      $bulan = date('Y-m');
    }
    $data['potong_battery'] = $this->M_PotongBattery->getAll($bulan);
    $data['data_operator'] = $this->M_PotongBattery->get_operator();

    return view('pages/potong_battery/home', $data);
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

    $id_potong_battery = $this->M_PotongBattery->save_data($data);

    return redirect()->to(base_url('potong_battery/detail_potong_battery/' . $id_potong_battery));
  }

  public function detail_lhp_potong_battery($id)
  {
    $data['data_plate'] = $this->M_PotongBattery->get_data_plate();
    $data['data_potong_battery'] = $this->M_PotongBattery->get_data_by_id($id);

    $data['data_plate_positif'] = [];
    $data['data_plate_negatif'] = [];

    foreach ($data['data_plate'] as $dp) {
      if (strpos($dp['plate'], 'POS') != false) {
        array_push($data['data_plate_positif'], $dp);
      } else {
        array_push($data['data_plate_negatif'], $dp);
      }
    }

    $data['data_plate_ng'] = $this->M_PotongBattery->get_data_plate_ng($id);
    $data['data_element'] = $this->M_PotongBattery->get_data_element($id);
    $data['data_operator'] = $this->M_PotongBattery->get_operator();

    return view('pages/potong_battery/detail_potong_battery', $data);
  }

  public function update()
  {
    $id_lhp_potong_battery = $this->request->getPost('id_potong_battery');
    $tanggal_produksi = $this->request->getPost('tanggal_produksi');
    $shift = $this->request->getPost('shift');
    $operator = $this->request->getPost('operator');

    $data = [
      'tanggal_produksi' => $tanggal_produksi,
      'shift' => $shift,
      'operator' => $operator
    ];

    $this->M_PotongBattery->update_data($id_lhp_potong_battery, $data);

    $type_plate_ng = $this->request->getPost('type');
    if (!empty($type_plate_ng)) {
      for ($i = 0; $i < count($type_plate_ng); $i++) {
        $id_detail_lhp_potong_battery_plate = $this->request->getPost('id_detail_lhp_potong_battery_plate')[$i];

        $data_plate_ng = [
          'id_lhp_potong_battery' => $id_lhp_potong_battery,
          'type' => $type_plate_ng[$i],
          'bolong' => $this->request->getPost('bolong')[$i],
          'lug_pendek' => $this->request->getPost('lug_pendek')[$i],
          'patah_frame' => $this->request->getPost('patah_frame')[$i],
          'rontok' => $this->request->getPost('rontok')[$i],
          'other' => $this->request->getPost('other')[$i],
          'total' => $this->request->getPost('total')[$i],
        ];

        $this->M_PotongBattery->update_data_plate($id_detail_lhp_potong_battery_plate, $data_plate_ng);
      }
    }

    $type_element_positif = $this->request->getPost('type_element_positif');
    if (!empty($type_element_positif)) {
      for ($i = 0; $i < count($type_element_positif); $i++) {
        $id_detail_lhp_potong_battery_element = $this->request->getPost('id_detail_lhp_potong_battery_element')[$i];
        $data_element_positif = [
          'id_lhp_potong_battery' => $id_lhp_potong_battery,
          'type_positif' => $type_element_positif[$i],
          'pasangan_positif' => $this->request->getPost('pasangan_positif')[$i],
          'type_negatif' => $this->request->getPost('type_element_negatif')[$i],
          'pasangan_negatif' => $this->request->getPost('pasangan_negatif')[$i],
          'total' => $this->request->getPost('total_element')[$i],
          'keterangan' => $this->request->getPost('keterangan')[$i],
        ];

        $this->M_PotongBattery->update_data_element($id_detail_lhp_potong_battery_element, $data_element_positif);
      }
    }

    return redirect()->to(base_url('potong_battery/detail_potong_battery/' . $id_lhp_potong_battery));
  }

  public function delete($id)
  {
    $this->M_PotongBattery->delete_data($id);
    return redirect()->to(base_url('potong_battery'));
  }

  public function download()
  {
    $start_date = $this->request->getPost('start_date');
    $end_date = $this->request->getPost('end_date');

    //data sheet lhp
    $data_lhp = $this->M_PotongBattery->get_data_potong_battery_plate($start_date, $end_date);
    // $data_lhp = $model->get_all_lhp_by_month($date);
    // dd($fix_data_detail_lhp);
    // Membuat objek Spreadsheet baru
    $spreadsheet = new Spreadsheet();

    // Menambahkan data ke worksheet
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Plate NG');
    $data = array(
      array('Tanggal Produksi', 'Shift', 'Operator', 'Type', 'Bolong (Panel)', 'Lug Pendek (Panel)', 'Patah Frame (Panel)', 'Rontok (Panel)', 'Other (Panel)', 'Total (Kg)'),
    );
    $isExist = [];
    if ($data_lhp !== NULL) {
      foreach ($data_lhp as $dl) {
        $data[] = array($dl['tanggal_produksi'], $dl['shift'], $dl['operator'], $dl['type'], $dl['bolong'], $dl['lug_pendek'], $dl['patah_frame'], $dl['rontok'], $dl['other'], $dl['total']);
      };
    }

    // Memasukkan data array ke dalam worksheet
    $sheet->fromArray($data);

    $data_lhp = $this->M_PotongBattery->get_data_potong_battery_element_repair($start_date, $end_date);

    // Menambahkan data ke worksheet
    $sheet2 = $spreadsheet->createSheet();
    $sheet2->setTitle('Element Repair');

    $data_potong_battery = array(
      array('Tanggal Produksi', 'Shift', 'Operator', 'Type Positif', 'Pasangan Positif', 'Type Negatif', 'Pasangan Negatif', 'Total'),
    );
    $isExist = [];
    if ($data_lhp !== NULL) {
      foreach ($data_lhp as $dl) {
        $data[] = array($dl['tanggal_produksi'], $dl['shift'], $dl['operator'], $dl['type_positif'], $dl['pasangan_positif'], $dl['type_negatif'], $dl['pasangan_negatif'], $dl['total']);
      };
    }

    // Memasukkan data array ke dalam worksheet
    $sheet2->fromArray($data_potong_battery);


    // Mengatur header respons HTTP
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="Data Potong Battery.xlsx"');
    header('Cache-Control: max-age=0');

    ob_end_clean();
    // Membuat objek Writer untuk menulis spreadsheet ke output
    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit();
  }
}
