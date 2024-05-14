<?php

namespace App\Controllers;

use App\Models\M_MasterTypeBatterySawRepair;

class MasterTypeBatterySawRepair extends BaseController
{
  public function __construct()
  {
    $this->M_MasterTypeBatterySawRepair = new M_MasterTypeBatterySawRepair();
    $this->session = \Config\Services::session();
  }

  public function index()
  {
    $data['data_master_type_battery'] = $this->M_MasterTypeBatterySawRepair->get_data_master_type_battery();
    $data['data_type_battery'] = $this->M_MasterTypeBatterySawRepair->get_all_type_battery();
    $data['data_plate_pos'] = $this->M_MasterTypeBatterySawRepair->get_all_plate_pos();
    $data['data_plate_neg'] = $this->M_MasterTypeBatterySawRepair->get_all_plate_neg();
    return view('data_master/master_type_battery_saw_repair/home', $data);
  }

  public function update_type_battery()
  {
    $id_type_battery = $this->request->getPost('id_type_battery');
    $type_battery = $this->request->getPost('type_battery');
    $type_positif = $this->request->getPost('type_positif');
    $type_negatif = $this->request->getPost('type_negatif');
    $pasangan_positif = $this->request->getPost('pasangan_positif');
    $pasangan_negatif = $this->request->getPost('pasangan_negatif');

    if($id_type_battery == '') {
      if(count($this->M_MasterTypeBatterySawRepair->check_data_master_type_battery($type_battery)) > 0) {
        return json_encode('Data Type Battery Sudah Tersedia');
      } else {
        $data_master_type_battery = [
          'type_battery' => $type_battery,
          'type_positif' => $type_positif,
          'type_negatif' => $type_negatif,
          'pasangan_positif' => $pasangan_positif,
          'pasangan_negatif' => $pasangan_negatif,
        ];
        $this->M_MasterTypeBatterySawRepair->update_data_master_type_battery($id_type_battery, $data_master_type_battery);
        return json_encode('Success');
      }
    } else {
      $data_master_type_battery = [
        'type_battery' => $type_battery,
        'type_positif' => $type_positif,
        'type_negatif' => $type_negatif,
        'pasangan_positif' => $pasangan_positif,
        'pasangan_negatif' => $pasangan_negatif,
      ];
      $this->M_MasterTypeBatterySawRepair->update_data_master_type_battery($id_type_battery, $data_master_type_battery);
      return json_encode('Success');
    }
  }
  
  public function delete_type_battery()
  {
    $id_type_battery = $this->request->getPost('id_type_battery');
    $this->M_MasterTypeBatterySawRepair->delete_data_master_type_battery($id_type_battery);

    return redirect()->to(base_url('master_type_battery_saw_repair'));
  }
}
