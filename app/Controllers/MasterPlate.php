<?php

namespace App\Controllers;

use App\Models\M_MasterPlate;

class MasterPlate extends BaseController
{
  public function __construct()
  {
    $this->M_MasterPlate = new M_MasterPlate();
    $this->session = \Config\Services::session();
  }

  public function index()
  {
    $data['data_master_plate'] = $this->M_MasterPlate->get_data_master_plate();
    $data['data_plate'] = $this->M_MasterPlate->get_all_plate();
    return view('data_master/master_plate/home', $data);
  }

  public function update_plate()
  {
    $id_plate = $this->request->getPost('id_plate');
    $plate = $this->request->getPost('plate');
    $berat = $this->request->getPost('berat');
    if($id_plate == '') {
      if(count($this->M_MasterPlate->check_data_master_plate($plate)) > 0) {
        return json_encode('Data Plate Sudah Tersedia');
      } else {
        $data_master_plate = [
          'plate' => $plate,
          'berat' => $berat,
        ];
        $this->M_MasterPlate->update_data_master_plate($id_plate, $data_master_plate);
        return json_encode('Success');
      }
    } else {
      $data_master_plate = [
        'plate' => $plate,
        'berat' => $berat,
      ];
      $this->M_MasterPlate->update_data_master_plate($id_plate, $data_master_plate);
      return json_encode('Success');
    }
  }
  
  public function delete_plate()
  {
    $id_plate = $this->request->getPost('id_plate');
    $this->M_MasterPlate->delete_data_master_plate($id_plate);

    return redirect()->to(base_url('master_plate'));
  }
}
