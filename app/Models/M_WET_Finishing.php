<?php

namespace App\Models;

use CodeIgniter\Model;

class M_WET_Finishing extends Model
{
    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->db5 = \Config\Database::connect('henkaten');

        $this->session = \Config\Services::session();
    }

    public function get_all_lhp_wet($bulan)
    {
        $month = date('m', strtotime($bulan));
        $year = date('Y', strtotime($bulan));
        $builder = $this->db->table('lhp_produksi2');
        $builder->select('lhp_produksi2.*, master_pic_line.nama_pic');
        $builder->join('master_pic_line', 'master_pic_line.id_pic = lhp_produksi2.grup');
        $builder->where('MONTH(tanggal_produksi) =', $month);
        $builder->where('YEAR(tanggal_produksi) =', $year);
        $builder->where('line >=', 8);
        $builder->where('line <=', 9);
        $builder->orderBy('tanggal_produksi', 'DESC');

        $query = $builder->get();

        return $query->getResultArray();
    }

    public function get_all_lhp_by_date($start_date, $end_date)
    {
        $builder = $this->db->table('lhp_produksi2');
        $builder->select('lhp_produksi2.*, master_pic_line.nama_pic');
        $builder->join('master_pic_line', 'master_pic_line.id_pic = lhp_produksi2.grup');
        $builder->groupStart();
        $builder->where('line', 8);
        $builder->orWhere('line', 9);
        $builder->groupEnd();
        $builder->where('tanggal_produksi >= ', $start_date);
        $builder->where('tanggal_produksi <= ', $end_date);

        $query = $builder->get();

        if (count($query->getResultArray()) > 0) {
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
        $query = $this->db->query('SELECT * FROM detail_lhp_produksi2 WHERE id_lhp_2 = ' . $id_lhp);

        if (count($query->getResultArray()) > 0) {
            return $query->getResultArray();
        } else {
            return;
        }
    }

    public function get_detail_breakdown_by_id($id_lhp)
    {
        $query = $this->db->query('SELECT * FROM detail_breakdown WHERE id_lhp = ' . $id_lhp);

        return $query->getResultArray();
    }

    public function get_detail_reject_by_id($id_lhp)
    {
        $query = $this->db->query('SELECT * FROM detail_reject WHERE id_lhp = ' . $id_lhp);

        return $query->getResultArray();
    }

    public function get_pending()
    {
        $query = $this->db->query('SELECT DISTINCT(jenis_pending) AS jenis_pending FROM master_pending_wet');

        return $query->getResultArray();
    }

    public function save_detail_pending($id, $data)
    {
        $builder = $this->db->table('detail_pending_wet_finishing');

        if ($id != '') {
            $builder->where('id_pending', $id);
            $builder->update($data);
            return $id;
        } else {
            $builder->insert($data);
            return $this->db->insertID();
        }
    }

    public function getKategoriPending($jenis_pending)
    {
        $query = $this->db->query('SELECT * FROM master_pending_wet WHERE jenis_pending = \'' . $jenis_pending . '\'');

        return $query->getResultArray();
    }

    public function get_detail_pending_by_id($id_lhp)
    {
        $query = $this->db->query('SELECT * FROM detail_pending_wet_finishing WHERE id_lhp = ' . $id_lhp);

        return $query->getResultArray();
    }

    public function get_total_pending($id_lhp)
    {
        $query = $this->db->query('SELECT SUM(qty_pending) AS total_pending FROM detail_pending_wet_finishing WHERE id_lhp = ' . $id_lhp);

        return $query->getResultArray();
    }
}
