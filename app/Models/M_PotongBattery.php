<?php

namespace App\Models;

use CodeIgniter\Model;

class M_PotongBattery extends Model
{
    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function getAll($bulan)
    {
        $month = date('m', strtotime($bulan));
        $year = date('Y', strtotime($bulan));
        $query = $this->db->table('lhp_potong_battery')->where('MONTH(tanggal_produksi)', $month)->where('YEAR(tanggal_produksi)', $year)->orderBy('tanggal_produksi', 'DESC')->get();
        return $query->getResultArray();
    }

    public function get_data_potong_battery_plate($start_date, $end_date)
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
        $query = $this->db->query('SELECT * FROM lhp_potong_battery lpb
                                    JOIN detail_lhp_potong_battery_plate dlpb ON dlpb.id_lhp_potong_battery = lpb.id_lhp_potong_battery '
            . $condition .
            ' ORDER BY tanggal_produksi ASC, shift ASC
                                ');
        return $query->getResultArray();
    }

    public function get_data_potong_battery_element_repair($start_date, $end_date)
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
        $query = $this->db->query('SELECT * FROM lhp_potong_battery lpb
                                    JOIN detail_lhp_potong_battery_element dlpbe ON dlpbe.id_lhp_potong_battery = lpb.id_lhp_potong_battery '
            . $condition .
            ' ORDER BY tanggal_produksi ASC, shift ASC
                                ');
        return $query->getResultArray();
    }

    public function save_data($data)
    {
        $this->db->table('lhp_potong_battery')->insert($data);
        return $this->db->insertID();
    }

    public function get_data_by_id($id)
    {
        $query = $this->db->query('SELECT * FROM lhp_potong_battery WHERE id_lhp_potong_battery = ' . $id);
        return $query->getResultArray();
    }

    public function get_data_plate()
    {
        $query = $this->db->query('SELECT * FROM plate');
        return $query->getResultArray();
    }

    public function get_data_plate_ng($id)
    {
        $query = $this->db->query('SELECT * FROM detail_lhp_potong_battery_plate WHERE id_lhp_potong_battery = ' . $id);
        return $query->getResultArray();
    }

    public function get_data_element($id)
    {
        $query = $this->db->query('SELECT * FROM detail_lhp_potong_battery_element WHERE id_lhp_potong_battery = ' . $id);
        return $query->getResultArray();
    }

    public function get_operator()
    {
        $query = $this->db->query('SELECT nama, status FROM master_operator WHERE mesin = \'Potong Battery\' ORDER BY nama ASC');
        return $query->getResultArray();
    }

    public function update_data($id, $data)
    {
        $this->db->table('lhp_potong_battery')->update($data, ['id_lhp_potong_battery' => $id]);
    }

    public function update_data_plate($id, $data)
    {
        if (!empty($id)) {
            $this->db->table('detail_lhp_potong_battery_plate')->update($data, ['id_detail_lhp_potong_battery_plate' => $id]);
        } else {
            $this->db->table('detail_lhp_potong_battery_plate')->insert($data);
        }
    }

    public function update_data_element($id, $data)
    {
        if (!empty($id)) {
            $this->db->table('detail_lhp_potong_battery_element')->update($data, ['id_detail_lhp_potong_battery_element' => $id]);
        } else {
            $this->db->table('detail_lhp_potong_battery_element')->insert($data);
        }
    }

    public function delete_data($id)
    {
        $this->db->table('detail_lhp_potong_battery_plate')->delete(['id_lhp_potong_battery' => $id]);
        $this->db->table('detail_lhp_potong_battery_element')->delete(['id_lhp_potong_battery' => $id]);
        $this->db->table('lhp_potong_battery')->delete(['id_lhp_potong_battery' => $id]);
    }
}
