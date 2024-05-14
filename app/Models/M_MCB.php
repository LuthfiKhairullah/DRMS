<?php namespace App\Models;
use CodeIgniter\Model;



class M_MCB extends Model
{
    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->db5 = \Config\Database::connect('henkaten');

        $this->session = \Config\Services::session();
    }

    public function get_all_lhp_mcb($bulan)
    {
        $month = date('m', strtotime($bulan));
        $year = date('Y', strtotime($bulan));
        $builder = $this->db->table('lhp_produksi2');
        $builder->select('lhp_produksi2.*, master_pic_line.nama_pic');
        $builder->join('master_pic_line', 'master_pic_line.id_pic = lhp_produksi2.grup');
        $builder->where('line', 10);
        $builder->where('MONTH(tanggal_produksi) =', $month);
        $builder->where('YEAR(tanggal_produksi) =', $year);
        $builder->orderBy('tanggal_produksi', 'DESC');

        $query = $builder->get();

        return $query->getResultArray();
    }

    public function get_all_lhp_by_date($start_date, $end_date)
    {
        $builder = $this->db->table('lhp_produksi2');
        $builder->select('lhp_produksi2.*, master_pic_line.nama_pic');
        $builder->join('master_pic_line', 'master_pic_line.id_pic = lhp_produksi2.grup');
        $builder->where('line', 10);
        $builder->where('tanggal_produksi >= ', $start_date);
        $builder->where('tanggal_produksi <= ', $end_date);

        $query = $builder->get();

        if(count($query->getResultArray()) > 0) {
            return $query->getResultArray();
        } else {
            return;
        }
    }
    
    public function get_grup()
    {
        $query = $this->db->query('SELECT * FROM master_pic_line ORDER BY nama_pic ASC');

        return $query->getResultArray();
    }

    public function get_kasubsie()
    {
        $query = $this->db5->query('SELECT LOWER(nama) AS nama FROM master_data_karyawan WHERE id_departement = 32 AND kode_jabatan = 5 ORDER BY nama ASC');

        return $query->getResultArray();
    }

    public function get_line()
    {
        $query = $this->db->query('SELECT * FROM master_line');

        return $query->getResultArray();
    }

    public function get_all_detail_lhp_by_id_lhp($id_lhp)
    {
        $query = $this->db->query('SELECT * FROM detail_lhp_produksi2 WHERE id_lhp_2 = '.$id_lhp);

        if(count($query->getResultArray()) > 0) {
            return $query->getResultArray();
        } else {
            return;
        }
    }

    public function get_detail_breakdown_by_id($id_lhp)
    {
        $query = $this->db->query('SELECT * FROM detail_breakdown WHERE id_lhp = '.$id_lhp);

        return $query->getResultArray();
    }

    public function get_detail_reject_by_id($id_lhp)
    {
        $query = $this->db->query('SELECT * FROM detail_reject WHERE id_lhp = '.$id_lhp);

        return $query->getResultArray();
    }
}
