<?php 

namespace App\Models;

use CodeIgniter\Model;



class M_MasterPlate extends Model
{
    public function __construct()
    {
        $this->db = \Config\Database::connect();

        $this->session = \Config\Services::session();
    }

    public function get_data_master_plate()
    {
        $query = $this->db->query('SELECT * FROM plate ORDER BY plate ASC');

        return $query->getResultArray();
    }

    public function get_all_plate()
    {
        $query = $this->db->query('SELECT DISTINCT plate FROM plate');

        return $query->getResultArray();
    }

    public function check_data_master_plate($plate)
    {
      $query = $this->db->query('SELECT id FROM plate WHERE plate = \'' . $plate . '\'');
      return $query->getResultArray(); 
    }

    public function update_data_master_plate($id, $data)
    {
      $builder = $this->db->table('plate');
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

    public function delete_data_master_plate($id)
    {
      $this->db->query('DELETE FROM plate WHERE id = \'' . $id . '\'');
    }
  }
  ?>