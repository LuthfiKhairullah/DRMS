<?php

namespace App\Models;

use CodeIgniter\Model;
use Config\Services;

class M_Cos extends Model
{
    public function __construct()
    {
        $this->db = \Config\Database::connect();

        $this->session = \Config\Services::session();
    }

    public function get_all_lhp_cos($bulan)
    {
        $month = date('m', strtotime($bulan));
        $year = date('Y', strtotime($bulan));
        $query = $this->db->query('SELECT * FROM lhp_cos
                                WHERE MONTH(tanggal_produksi) = \'' . $month . '\' AND YEAR(tanggal_produksi) = \'' . $year . '\'
                                ORDER BY tanggal_produksi DESC
                            ');

        return $query->getResultArray();
    }

    public function get_all_lhp_cos_by_date($start_date, $end_date)
    {
        $query = $this->db->query('SELECT * FROM lhp_cos WHERE tanggal_produksi >= \'' . $start_date . '\' AND tanggal_produksi <= \'' . $end_date . '\'');
        if (count($query->getResultArray()) > 0) {
            return $query->getResultArray();
        } else {
            return;
        }
    }

    public function get_all_detail_lhp_cos_by_id_lhp_cos($id_lhp_cos)
    {
        $query = $this->db->query('SELECT * FROM detail_lhp_cos WHERE id_lhp_cos = \'' . $id_lhp_cos . '\'');
        if (count($query->getResultArray()) > 0) {
            return $query->getResultArray();
        } else {
            return;
        }
    }

    public function get_team()
    {
        $query = $this->db->query('SELECT UPPER(nama_pic) AS team, status FROM master_pic_line ORDER BY nama_pic ASC');

        return $query->getResultArray();
    }

    public function get_line()
    {
        $query = $this->db->query('SELECT * FROM master_line WHERE (id_line >= 1 AND id_line <= 7) OR id_line = 10');

        return $query->getResultArray();
    }

    public function cek_lhp($tanggal_produksi, $line, $shift, $team)
    {
        $query = $this->db->query('SELECT * FROM lhp_cos WHERE tanggal_produksi = \'' . $tanggal_produksi . '\' AND line = \'' . $line . '\' AND shift = \'' . $shift . '\' AND team = \'' . $team . '\'');

        return $query->getResultArray();
    }

    public function save_cos($data)
    {
        $this->db->table('lhp_cos')->insert($data);

        return $this->db->insertID();
    }

    public function getDataWO()
    {
        $client = Services::curlrequest();
        $attempt = 0;
        do {
            $url = "https://portal3.incoe.astra.co.id/production_control_v2/public/api/list_wo_assy";
            $response = $client->request('GET', $url);
            $attempt++;
        } while (($response->getStatusCode() != 200 || $response->getBody() == '') && $attempt < 5);
        return json_decode($response->getBody(), true);
    }

    public function getPartNo($no_wo)
    {
        $client = Services::curlrequest();
        $attempt = 0;
        do {
            $url = "https://portal3.incoe.astra.co.id/production_control_v2/public/api/get_status_no_wo/$no_wo";
            $response = $client->request('GET', $url);
            $attempt++;
        } while (($response->getStatusCode() != 200 || $response->getBody() == '') && $attempt < 5);
        return json_decode($response->getBody(), true);
    }

    public function save_detail_lhp($data)
    {
        $builder = $this->db->table('detail_lhp_cos');
        $builder->insert($data);

        return $this->db->insertID();
    }

    public function get_lhp_cos_by_id($id_lhp_cos)
    {
        $query = $this->db->query('SELECT * FROM lhp_cos WHERE id_lhp_cos = ' . $id_lhp_cos);

        return $query->getResultArray();
    }

    public function get_detail_lhp_cos_by_id($id_lhp_cos)
    {
        $query = $this->db->query('SELECT * FROM detail_lhp_cos WHERE id_lhp_cos = ' . $id_lhp_cos);

        return $query->getResultArray();
    }

    public function update_lhp_cos($id_lhp_cos, $data)
    {
        $builder = $this->db->table('lhp_cos');
        $builder->where('id_lhp_cos', $id_lhp_cos);
        $builder->update($data);

        return $this->db->affectedRows();
    }

    public function update_detail_lhp_cos($id_detail_lhp_cos, $data)
    {
        $builder = $this->db->table('detail_lhp_cos');
        if ($id_detail_lhp_cos != '') {
            $builder->where('id_detail_lhp_cos', $id_detail_lhp_cos);
            $builder->update($data);
            return $id_detail_lhp_cos;
        } else {
            $builder->insert($data);
            return $this->db->insertID();
        }
    }

    public function get_id_detail_lhp_cos_by_id_lhp_cos($id)
    {
        $query = $this->db->query('SELECT id_detail_lhp_cos FROM detail_lhp_cos WHERE id_lhp_cos=\'' . $id . '\'');

        return $query->getResultArray();
    }

    public function delete_cos($id)
    {
        $this->db->query('DELETE FROM lhp_cos WHERE id_lhp_cos = ' . $id);
        $this->db->query('DELETE FROM detail_lhp_cos WHERE id_lhp_cos = ' . $id);
    }

    public function delete_detail_lhp_cos($id)
    {
        $this->db->query('DELETE FROM detail_lhp_cos WHERE id_detail_lhp_cos = ' . $id);
    }
}
