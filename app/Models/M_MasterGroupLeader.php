<?php

namespace App\Models;

use CodeIgniter\Model;

class M_MasterGroupLeader extends Model
{
  public function __construct()
  {
    $this->db = \Config\Database::connect();
    $this->db2 = \Config\Database::connect('henkaten');

    $this->session = \Config\Services::session();
  }

  public function get_data_group_leader()
  {
    $query = $this->db->query('SELECT mpl.*, ml.nama_line FROM master_pic_line mpl
                              LEFT JOIN master_line ml ON mpl.id_line = ml.id_line
                              WHERE status IS NULL OR status != \'Non Aktif\' ORDER BY nama_pic ASC, mpl.id_line ASC
                            ');

    return $query->getResultArray();
  }

  public function get_data_line()
  {
    $query = $this->db->query('SELECT * FROM master_line ORDER BY id_line ASC');

    return $query->getResultArray();
  }

  public function get_nama_by_npk($npk)
  {
    $query = $this->db2->query('SELECT TOP 1 nama FROM master_data_karyawan
                              WHERE npk = ' . $npk . '
                            ');

    return $query->getResultArray();
  }

  public function get_data_pic_line()
  {
    $query = $this->db2->query('SELECT nama, npk FROM master_data_karyawan
                                WHERE id_departement = 32
                                ORDER BY nama ASC
                              ');

    return $query->getResultArray();
  }

  public function check_data_group_leader_by_npk($npk)
  {
    $query = $this->db->query('SELECT * FROM master_pic_line WHERE npk = ' . $npk);

    return $query->getResultArray();
  }

  public function update_data_group_leader($npk, $data)
  {
    $builder = $this->db->table('master_pic_line');
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
