<?php 

namespace App\Models;

use CodeIgniter\Model;



class M_PendingAssy extends Model
{
    public function __construct()
    {
        $this->db = \Config\Database::connect();

        $this->session = \Config\Services::session();
    }

    public function get_data_pending()
    {
        $query = $this->db->query('SELECT * FROM master_pending_assy ORDER BY jenis_pending ASC, kategori_pending ASC');

        return $query->getResultArray();
    }

    public function get_all_jenis_pending_assy()
    {
        $query = $this->db->query('SELECT DISTINCT jenis_pending FROM master_pending_assy');

        return $query->getResultArray();
    }

    public function get_all_kategori_pending_assy()
    {
        $query = $this->db->query('SELECT DISTINCT kategori_pending FROM master_pending_assy');

        return $query->getResultArray();
    }

    public function check_data_pending($jenis_pending, $kategori_pending)
    {
      $query = $this->db->query('SELECT id_pending FROM master_pending_assy WHERE jenis_pending = \'' . $jenis_pending . '\' AND kategori_pending = \'' . $kategori_pending . '\'');
      return $query->getResultArray(); 
    }

    public function update_data_pending($id_pending, $data)
    {
      $builder = $this->db->table('master_pending_assy');
      if($id_pending == '') {
        $builder->insert($data);
        return $this->db->insertID();
      } else {
        $builder->where('id_pending', $id_pending);
        $builder->update($data);
        return $id_pending;
      }

      return $id_pending;
    }

    public function delete_data_pending($id_pending)
    {
      $this->db->query('DELETE FROM master_pending_assy WHERE id_pending = \'' . $id_pending . '\'');
    }
  }
  ?>