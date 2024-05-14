<?php

namespace App\Models;

use CodeIgniter\Model;

class M_SawRepair extends Model
{
  public function __construct()
  {
    $this->db = \Config\Database::connect();
  }

  public function getAll($bulan)
  {
    $month = date('m', strtotime($bulan));
    $year = date('Y', strtotime($bulan));
    $query = $this->db->table('lhp_saw_repair')->where('MONTH(tanggal_produksi)', $month)->where('YEAR(tanggal_produksi)', $year)->orderBy('tanggal_produksi', 'DESC')->get();
    return $query->getResultArray();
  }

  public function get_data_saw_repair($start_date, $end_date)
  {
    $condition = '';
    if ($start_date == '' && $end_date == '') {
      $condition = '';
    } elseif ($start_date == '' && $end_date != '') {
      $condition = 'WHERE tanggal_produksi <= \'' . $end_date . '\'';
    } elseif ($start_date != '' && $end_date == '') {
      $condition = 'WHERE tanggal_produksi >= \'' . $start_date . '\'';
    } elseif ($start_date != '' && $end_date != '') {
      $condition = 'WHERE tanggal_produksi >= \'' . $start_date . '\' AND tanggal_produksi <= \'' . $end_date . '\'';
    }
    $query = $this->db->query('SELECT * FROM lhp_saw_repair lpb
                                  JOIN detail_lhp_saw_repair dlpbe ON dlpbe.id_lhp_saw_repair = lpb.id_lhp_saw_repair '
      . $condition .
      ' ORDER BY tanggal_produksi ASC, shift ASC
                              ');
    return $query->getResultArray();
  }

  public function save_data($data)
  {
    $this->db->table('lhp_saw_repair')->insert($data);
    return $this->db->insertID();
  }

  public function get_data_by_id($id)
  {
    $query = $this->db->query('SELECT * FROM lhp_saw_repair WHERE id_lhp_saw_repair = ' . $id);
    return $query->getResultArray();
  }

  public function get_data_detail_by_id($id)
  {
    $query = $this->db->query('SELECT * FROM detail_lhp_saw_repair WHERE id_lhp_saw_repair = ' . $id);
    return $query->getResultArray();
  }

  public function get_data_type_battery()
  {
    $query = $this->db->query('SELECT type_battery FROM master_data_repair ORDER BY type_battery ASC');
    return $query->getResultArray();
  }

  public function get_operator()
  {
    $query = $this->db->query('SELECT nama, status FROM master_operator WHERE mesin = \'Saw Repair\' ORDER BY nama ASC');
    return $query->getResultArray();
  }

  public function update_data($id, $data)
  {
    $this->db->table('lhp_saw_repair')->where('id_lhp_saw_repair', $id)->update($data);
  }

  public function update_data_detail($id, $data)
  {
    if (!empty($id)) {
      $this->db->table('detail_lhp_saw_repair')->where('id_detail_lhp_saw_repair', $id)->update($data);
    } else {
      $this->db->table('detail_lhp_saw_repair')->insert($data);
    }
  }

  public function delete_data($id)
  {
    $this->db->table('detail_lhp_saw_repair')->delete(['id_lhp_saw_repair' => $id]);
    $this->db->table('lhp_saw_repair')->delete(['id_lhp_saw_repair' => $id]);
  }
}
