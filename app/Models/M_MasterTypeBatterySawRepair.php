<?php 

namespace App\Models;

use CodeIgniter\Model;

class M_MasterTypeBatterySawRepair extends Model
{
    public function __construct()
    {
        $this->db = \Config\Database::connect();

        $this->session = \Config\Services::session();
    }

    public function get_data_master_type_battery()
    {
        $query = $this->db->query('SELECT * FROM master_data_repair ORDER BY type_battery ASC');

        return $query->getResultArray();
    }

    public function get_all_type_battery()
    {
        $query = $this->db->query('SELECT DISTINCT type_battery FROM master_data_repair ORDER BY type_battery ASC');

        return $query->getResultArray();
    }

    public function get_all_plate_pos()
    {
        $query = $this->db->query('SELECT DISTINCT plate FROM plate WHERE plate LIKE \'%POS%\' ORDER BY plate ASC');

        return $query->getResultArray();
    }

    public function get_all_plate_neg()
    {
        $query = $this->db->query('SELECT DISTINCT plate FROM plate WHERE plate LIKE \'%NEG%\' ORDER BY plate ASC');

        return $query->getResultArray();
    }

    public function check_data_master_type_battery($type_battery)
    {
      $query = $this->db->query('SELECT id FROM master_data_repair WHERE type_battery = \'' . $type_battery . '\'');
      return $query->getResultArray();
    }

    public function update_data_master_type_battery($id, $data)
    {
      $builder = $this->db->table('master_data_repair');
      if($id == '') {
        $builder->insert($data);
        return $this->db->insertID();
      } else {
        $builder->where('id', $id);
        $builder->update($data);
        return $id;
      }

      return $id;
    }

    public function delete_data_master_type_battery($id)
    {
      $this->db->query('DELETE FROM master_data_repair WHERE id = \'' . $id . '\'');
    }
  }
  ?>