<?php

namespace App\Controllers;

use App\Models\M_Pw;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Pw extends BaseController
{
  public function __construct()
  {
    $this->M_Pw = new M_Pw();
    $this->session = \Config\Services::session();

    if ($this->session->get('is_login')) {
      return redirect()->to('login');
    }
  }

  public function pw_view($bulan = null)
  {
    if ($bulan == '') {
      $bulan = date('Y-m');
    }
    $model = new M_Pw();
    $data['data_pw'] = $model->get_all_lhp_pw($bulan);
    $data['data_team'] = $model->get_team();
    $data['data_line'] = $model->get_line();
    return view('pages/pw/pw_view', $data);
  }

  public function save()
  {
    $date = $this->request->getPost('date');
    $line = $this->request->getPost('line');
    $shift = $this->request->getPost('shift');
    $team = $this->request->getPost('team');
    $model = new M_Pw();
    $cek = $model->cek_lhp($date, $line, $shift, $team);
    if (count($cek) > 0) {
      $id_lhp_pw = $cek[0]['id_lhp_pw'];
      return redirect()->to(base_url('pw/detail_pw/' . $id_lhp_pw));
    } else {
      $data_lhp_pw = array(
        'tanggal_produksi' => $date,
        'line' => $line,
        'shift' => $shift,
        'team' => $team,
      );
      $save_data = $model->save_pw($data_lhp_pw);
      return redirect()->to(base_url('pw/detail_pw/' . $save_data));
    }
  }

  function edit()
  {
    $id = $this->request->getPost('id');
    $id_detail_pw = $this->request->getPost('id_detail_pw');
    $date = $this->request->getPost('date');
    $line = $this->request->getPost('line');
    $shift = $this->request->getPost('shift');
    $team = $this->request->getPost('team');
    $no_wo = $this->request->getPost('no_wo');
    $type_battery = $this->request->getPost('type_battery');
    $hasil = $this->request->getPost('hasil');
    $mentah = $this->request->getPost('mentah');
    $flashing_hole = $this->request->getPost('flashing_hole');
    $flashing_zig_zag = $this->request->getPost('flashing_zig_zag');
    $id_detail_lhp_pw_Exist = [];
    $model = new M_Pw();
    $all_id_detail_lhp_pw = $model->get_id_detail_lhp_pw_by_id_lhp_pw($id);
    $data_lhp_pw = array(
      'tanggal_produksi' => $date,
      'line' => $line,
      'shift' => $shift,
      'team' => $team,
    );
    $update_data = $model->update_lhp_pw($id, $data_lhp_pw);
    if (($no_wo !== NULL ? count($no_wo) : 0)) {
      for ($i = 0; $i < ($id_detail_pw !== NULL ? count($id_detail_pw) : 0); $i++) {
        if ($id_detail_pw[$i] !== "") {
          $id_detail_lhp_pw_Exist[$id_detail_pw[$i]] = $id_detail_pw[$i];
        }
        if($no_wo[$i] != '') {
          $data_detail_lhp_pw = array(
            'id_lhp_pw' => $id,
            'no_wo' => $no_wo[$i] !== NULL ? $no_wo[$i] : 0,
            'type_battery' => $type_battery[$i] !== NULL ? $type_battery[$i] : 0,
            'hasil' => $hasil[$i] !== NULL ? $hasil[$i] : 0,
            'mentah' => $mentah[$i] !== NULL ? $mentah[$i] : 0,
            'flashing_hole' => $flashing_hole[$i] !== NULL ? $flashing_hole[$i] : 0,
            'flashing_zig_zag' => $flashing_zig_zag[$i] !== NULL ? $flashing_zig_zag[$i] : 0,
          );
          $update_data = $model->update_detail_lhp_pw($id_detail_pw[$i], $data_detail_lhp_pw);
        }
      }
    }
    if (count($all_id_detail_lhp_pw) > 0) {
      foreach ($all_id_detail_lhp_pw as $idc) {
        if (!array_key_exists($idc['id_detail_lhp_pw'], $id_detail_lhp_pw_Exist)) {
          $model->delete_detail_lhp_pw($idc['id_detail_lhp_pw']);
        }
      }
    }
    return redirect()->to(base_url('pw/detail_pw/' . $id));
  }

  public function getPartNo()
  {
    $no_wo = $this->request->getPost('no_wo');
    $model = new M_Pw();
    echo json_encode($model->getPartNo($no_wo));
  }

  public function detail_pw($id)
  {
    $model = new M_Pw();
    $data['data_lhp_pw'] = $model->get_lhp_pw_by_id($id);
    $data['data_detail_lhp_pw'] = $model->get_detail_lhp_pw_by_id($id);
    $data['data_team'] = $model->get_team();
    $data['data_line'] = $model->get_line();
    $data['data_wo'] = $model->getDataWO();
    return view('pages/pw/pw_detail_view', $data);
  }

  public function delete_pw()
  {
    $id_lhp_pw = $this->request->getPost('id');
    $model = new M_Pw();
    $model->delete_pw($id_lhp_pw);

    return redirect()->to(base_url('pw'));
  }

  public function download()
  {
    $date = $this->request->getPost('date');
    $start_date = $this->request->getPost('start_date');
    $end_date = $this->request->getPost('end_date');
    $model = new M_Pw();
    $data_pw = $model->get_all_lhp_pw_by_date($start_date, $end_date);
    $data_detail_lhp_pw = [];
    if ($data_pw !== NULL) {
      foreach ($data_pw as $dc) {
        $temp = $model->get_all_detail_lhp_pw_by_id_lhp_pw($dc['id_lhp_pw']);
        if ($temp !== NULL) {
          foreach ($temp as $t) {
            array_push($data_detail_lhp_pw, $t);
          }
        }
      }
      $dates = array_column($data_pw, "tanggal_produksi");
      $lines = array_column($data_pw, "line");
      $shift = array_column($data_pw, "shift");
      array_multisort($dates, SORT_ASC, $shift, SORT_ASC, $lines, SORT_ASC,  $data_pw);
    }
    // Membuat objek Spreadsheet baru
    $spreadsheet = new Spreadsheet();

    // Menambahkan data ke worksheet
    $sheet = $spreadsheet->getActiveSheet();
    $data = array(
      array('Date', 'Shift', 'Line', 'Team', 'NO WO', 'Type Battery', 'Hasil', 'Mentah', 'Flashing Hole', 'Flashing Zig-zag'),
    );
    $isExist = [];
    if ($data_pw !== NULL) {
      foreach ($data_pw as $dc) {
        foreach ($data_detail_lhp_pw as $ddlc) {
          if ($dc['id_lhp_pw'] === $ddlc['id_lhp_pw']) {
            $data[] = array($dc['tanggal_produksi'], $dc['shift'], $dc['line'], $dc['team'], $ddlc['no_wo'], $ddlc['type_battery'], $ddlc['hasil'], $ddlc['mentah'], $ddlc['flashing_hole'], $ddlc['flashing_zig_zag']);
          };
        }
      }
    }

    // Memasukkan data array ke dalam worksheet
    $sheet->fromArray($data);


    // Mengatur header respons HTTP
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="Data PW.xlsx"');
    header('Cache-Control: max-age=0');

    ob_end_clean();
    // Membuat objek Writer untuk menulis spreadsheet ke output
    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit();
  }
}
