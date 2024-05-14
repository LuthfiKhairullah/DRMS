<?php

namespace App\Models;

use CodeIgniter\Model;

class M_MasterOperator extends Model
{
  public function __construct()
  {
    $this->db = \Config\Database::connect();
    $this->db2 = \Config\Database::connect('henkaten');

    $this->session = \Config\Services::session();
  }

  public function get_data_operator()
  {
    $query = $this->db->query('SELECT * FROM master_operator
                              WHERE status IS NULL OR status != \'Non Aktif\' ORDER BY nama ASC
                            ');

    return $query->getResultArray();
  }

  public function get_data_mesin()
  {
    return ['Potong Battery', 'Saw Repair'];
  }

  public function get_nama_by_npk($npk)
  {
    $query = $this->db2->query('SELECT TOP 1 nama FROM master_data_karyawan
                              WHERE npk = ' . $npk . '
                            ');

    return $query->getResultArray();
  }

  public function get_data_karyawan()
  {
    $query = $this->db2->query('SELECT nama, npk FROM master_data_karyawan
                                WHERE (id_departement = 32 OR id_departement = 14)
                                ORDER BY nama ASC
                              ');

    return $query->getResultArray();
  }

  public function check_data_operator_by_npk($npk)
  {
    $query = $this->db->query('SELECT * FROM master_operator WHERE npk = ' . $npk);

    return $query->getResultArray();
  }

  public function update_data_operator($npk, $data)
  {
    $builder = $this->db->table('master_operator');
    if ($npk != '') {
      $builder->where('npk', $npk);
      $builder->update($data);
      return $npk;
    } else {
      $builder->insert($data);
      return $this->db->insertID();
    }
  }
}
