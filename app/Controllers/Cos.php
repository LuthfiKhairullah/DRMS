<?php

namespace App\Controllers;

use App\Models\M_Cos;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Cos extends BaseController
{
  public function __construct()
  {
    $this->M_Cos = new M_Cos();
    $this->session = \Config\Services::session();

    if ($this->session->get('is_login')) {
      return redirect()->to('login');
    }
  }

  public function cos_view($bulan = null)
  {
    if ($bulan == '') {
      $bulan = date('Y-m');
    }
    $model = new M_Cos();
    $data['data_cos'] = $model->get_all_lhp_cos($bulan);
    $data['data_line'] = $model->get_line();
    $data['data_team'] = $model->get_team();
    return view('pages/cos/cos_view', $data);
  }

  public function save()
  {
    $date = $this->request->getPost('date');
    $line = $this->request->getPost('line');
    $shift = $this->request->getPost('shift');
    $team = $this->request->getPost('team');
    $model = new M_Cos();
    $cek = $model->cek_lhp($date, $line, $shift, $team);
    if (count($cek) > 0) {
      $id_lhp_cos = $cek[0]['id_lhp_cos'];
      return redirect()->to(base_url('cos/detail_cos/' . $id_lhp_cos));
    } else {
      $data_lhp_cos = array(
        'tanggal_produksi' => $date,
        'line' => $line,
        'shift' => $shift,
        'team' => $team,
      );
      $save_data = $model->save_cos($data_lhp_cos);
      return redirect()->to(base_url('cos/detail_cos/' . $save_data));
    }
  }

  function edit()
  {
    $id = $this->request->getPost('id');
    $id_detail_cos = $this->request->getPost('id_detail_cos');
    $date = $this->request->getPost('date');
    $line = $this->request->getPost('line');
    $shift = $this->request->getPost('shift');
    $team = $this->request->getPost('team');
    $no_wo = $this->request->getPost('no_wo');
    $type_battery = $this->request->getPost('type_battery');
    $hasil = $this->request->getPost('hasil');
    $tersangkut = $this->request->getPost('tersangkut');
    $terbakar = $this->request->getPost('terbakar');
    $lug_lepas = $this->request->getPost('lug_lepas');
    $strap_tipis = $this->request->getPost('strap_tipis');
    $dross_1 = $this->request->getPost('dross_1');
    $dross_2 = $this->request->getPost('dross_2');
    $dross_3 = $this->request->getPost('dross_3');
    $timbangan_strap_1 = $this->request->getPost('timbangan_strap_1');
    $timbangan_strap_2 = $this->request->getPost('timbangan_strap_2');
    $timbangan_strap_3 = $this->request->getPost('timbangan_strap_3');
    $timbangan_strap_4 = $this->request->getPost('timbangan_strap_4');
    $id_detail_lhp_cos_Exist = [];
    $model = new M_Cos();
    $all_id_detail_lhp_cos = $model->get_id_detail_lhp_cos_by_id_lhp_cos($id);
    $data_lhp_cos = array(
      'tanggal_produksi' => $date,
      'line' => $line,
      'shift' => $shift,
      'team' => $team,
    );
    $update_data = $model->update_lhp_cos($id, $data_lhp_cos);
    if (($no_wo !== NULL ? count($no_wo) : 0)) {
      for ($i = 0; $i < ($id_detail_cos !== NULL ? count($id_detail_cos) : 0); $i++) {
        if ($id_detail_cos[$i] !== "") {
          $id_detail_lhp_cos_Exist[$id_detail_cos[$i]] = $id_detail_cos[$i];
        }
        if($no_wo[$i] != '') {
          $data_detail_lhp_cos = array(
            'id_lhp_cos' => $id,
            'no_wo' => $no_wo[$i] !== NULL ? $no_wo[$i] : 0,
            'type_battery' => $type_battery[$i] !== NULL ? $type_battery[$i] : 0,
            'hasil' => $hasil[$i] !== NULL ? $hasil[$i] : 0,
            'tersangkut' => $tersangkut[$i] !== NULL ? $tersangkut[$i] : 0,
            'terbakar' => $terbakar[$i] !== NULL ? $terbakar[$i] : 0,
            'lug_lepas' => $lug_lepas[$i] !== NULL ? $lug_lepas[$i] : 0,
            'strap_tipis' => $strap_tipis[$i] !== NULL ? $strap_tipis[$i] : 0,
            'dross_1' => $dross_1[$i] !== NULL ? $dross_1[$i] : 0,
            'dross_2' => $dross_2[$i] !== NULL ? $dross_2[$i] : 0,
            'dross_3' => $dross_3[$i] !== NULL ? $dross_3[$i] : 0,
            'timbangan_strap_1' => $timbangan_strap_1[$i] !== NULL ? $timbangan_strap_1[$i] : 0,
            'timbangan_strap_2' => $timbangan_strap_2[$i] !== NULL ? $timbangan_strap_2[$i] : 0,
            'timbangan_strap_3' => $timbangan_strap_3[$i] !== NULL ? $timbangan_strap_3[$i] : 0,
            'timbangan_strap_4' => $timbangan_strap_4[$i] !== NULL ? $timbangan_strap_4[$i] : 0,
          );
          $update_data = $model->update_detail_lhp_cos($id_detail_cos[$i], $data_detail_lhp_cos);
        }
      }
    }
    if (count($all_id_detail_lhp_cos) > 0) {
      foreach ($all_id_detail_lhp_cos as $idc) {
        if (!array_key_exists($idc['id_detail_lhp_cos'], $id_detail_lhp_cos_Exist)) {
          $model->delete_detail_lhp_cos($idc['id_detail_lhp_cos']);
        }
      }
    }
    return redirect()->to(base_url('cos/detail_cos/' . $id));
  }

  public function getPartNo()
  {
    $no_wo = $this->request->getPost('no_wo');
    $model = new M_Cos();
    echo json_encode($model->getPartNo($no_wo));
  }

  public function detail_cos($id)
  {
    $model = new M_Cos();
    $data['data_lhp_cos'] = $model->get_lhp_cos_by_id($id);
    $data['data_detail_lhp_cos'] = $model->get_detail_lhp_cos_by_id($id);
    $data['data_team'] = $model->get_team();
    $data['data_line'] = $model->get_line();
    $data['data_wo'] = $model->getDataWO();

    return view('pages/cos/cos_detail_view', $data);
  }

  public function delete_cos()
  {
    $id_lhp_cos = $this->request->getPost('id');
    $model = new M_Cos();
    $model->delete_cos($id_lhp_cos);

    return redirect()->to(base_url('cos'));
  }

  public function download()
  {
    $date = $this->request->getPost('date');
    $start_date = $this->request->getPost('start_date');
    $end_date = $this->request->getPost('end_date');
    $model = new M_Cos();
    $data_cos = $model->get_all_lhp_cos_by_date($start_date, $end_date);
    $data_detail_lhp_cos = [];
    if ($data_cos !== NULL) {
      foreach ($data_cos as $dc) {
        $temp = $model->get_all_detail_lhp_cos_by_id_lhp_cos($dc['id_lhp_cos']);
        if ($temp !== NULL) {
          foreach ($temp as $t) {
            array_push($data_detail_lhp_cos, $t);
          }
        }
      }
      $dates = array_column($data_cos, "tanggal_produksi");
      $lines = array_column($data_cos, "line");
      $shift = array_column($data_cos, "shift");
      array_multisort($dates, SORT_ASC, $shift, SORT_ASC, $lines, SORT_ASC,  $data_cos);
    }
    // Membuat objek Spreadsheet baru
    $spreadsheet = new Spreadsheet();

    // Menambahkan data ke worksheet
    $sheet = $spreadsheet->getActiveSheet();
    $data = array(
      array('Date', 'Shift', 'Line', 'Team', 'NO WO', 'Type Battery', 'Hasil', 'Tersangkut', 'Terbakar', 'Lug Lepas', 'Strap Tipis', 'Dross 1', 'Dross 2', 'Dross 3', 'Timbangan Strap 1', 'Timbangan Strap 2', 'Timbangan Strap 3'),
    );
    $isExist = [];
    if ($data_cos !== NULL) {
      foreach ($data_cos as $dc) {
        foreach ($data_detail_lhp_cos as $ddlc) {
          if ($dc['id_lhp_cos'] === $ddlc['id_lhp_cos']) {
            $data[] = array($dc['tanggal_produksi'], $dc['shift'], $dc['line'], $dc['team'], $ddlc['no_wo'], $ddlc['type_battery'], $ddlc['hasil'], $ddlc['tersangkut'], $ddlc['terbakar'], $ddlc['lug_lepas'], $ddlc['strap_tipis'], $ddlc['dross_1'], $ddlc['dross_2'], $ddlc['dross_3'], $ddlc['timbangan_strap_1'], $ddlc['timbangan_strap_2'], $ddlc['timbangan_strap_3']);
          };
        }
      }
    }

    // Memasukkan data array ke dalam worksheet
    $sheet->fromArray($data);


    // Mengatur header respons HTTP
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="data.xlsx"');
    header('Cache-Control: max-age=0');

    ob_end_clean();
    // Membuat objek Writer untuk menulis spreadsheet ke output
    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit();
  }
}
