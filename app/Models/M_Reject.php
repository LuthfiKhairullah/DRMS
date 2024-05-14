<?php 
namespace App\Models;
use CodeIgniter\Model;



class M_Reject extends Model
{
    public function __construct()
    {
        $this->db = \Config\Database::connect();

        $this->session = \Config\Services::session();
    }

    public function get_data_reject_utama_amb()
    {
        $query = $this->db->query('SELECT * FROM data_reject_utama WHERE AMB=\'1\' ORDER BY jenis_reject ASC');

        return $query->getResultArray();
    }

    public function get_data_reject_utama()
    {
        $query = $this->db->query('SELECT * FROM data_reject_utama ORDER BY jenis_reject ASC');

        return $query->getResultArray();
    }

    public function get_data_reject()
    {
        $query = $this->db->query('SELECT * FROM data_reject WHERE AMB=\'1\' ORDER BY jenis_reject ASC, kategori_reject ASC');

        return $query->getResultArray();
    }

    public function add_reject_utama($data)
    {
        $query = $this->db->table('data_reject_utama')->insert($data);
    }

    public function add_reject($data)
    {
        $query = $this->db->table('data_reject')->insert($data);
    }

    public function get_data_reject_utama_by_id($id_reject_utama)
    {
        $query = $this->db->query('SELECT * FROM data_reject_utama WHERE id = \''.$id_reject_utama.'\'');

        return $query->getResultArray();
    }

    public function get_data_reject_by_id($id_reject)
    {
        $query = $this->db->query('SELECT * FROM data_reject WHERE id = \''.$id_reject.'\'');

        return $query->getResultArray();
    }

    public function update_reject_utama($data, $id_reject_utama, $jenis_reject)
    {
        $builder = $this->db->table('data_reject_utama');

        $builder->where('id_reject_utama', $id_reject_utama);
        $builder->update($data);

        $builder2 = $this->db->table('data_reject');

        $builder2->where('jenis_reject', $jenis_reject);
        $builder2->update($data);
    }

    public function update_reject($data, $id_reject)
    {
        $builder = $this->db->table('data_reject');

        $builder->where('id_reject', $id_reject);
        $builder->update($data);
    }

    public function delete_reject_utama($id_reject_utama, $jenis_reject, $data)
    {
        $builder = $this->db->table('data_reject_utama');

        $builder->where('id_reject_utama', $id_reject_utama);
        $builder->update($data);

        $builder2 = $this->db->table('data_reject');

        $builder2->where('jenis_reject', $jenis_reject);
        $builder2->delete();
    }

    public function delete_reject($id_reject)
    {
        $builder = $this->db->table('data_reject');

        $builder->where('id_reject', $id_reject);
        $builder->delete();
    }

    public function cek_reject($jenis_reject) {
        $query = $this->db->query('SELECT id_reject_utama FROM data_reject_utama WHERE jenis_reject = \''.$jenis_reject.'\'');

        return $query->getResultArray();
    }

    public function kategori_reject_assembly(){
        $query= $this->db->query('SELECT DISTINCT kategori_reject
        FROM production_control_v2.dbo.data_reject;');
        return $query->getResultArray();
    }
}