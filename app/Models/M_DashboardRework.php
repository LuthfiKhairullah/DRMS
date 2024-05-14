<?php

namespace App\Models;

use CodeIgniter\Model;



class M_DashboardRework extends Model
{
    public function __construct()
    {
        $this->db = \Config\Database::connect();

        $this->session = \Config\Services::session();
    }

    public function get_data_target_first_year($category, $name_target, $bulan)
    {
        $year = date('Y', strtotime($bulan));
        $query = $this->db->query('SELECT TOP(1) target FROM data_target WHERE category = \'' . $category . '\' AND name_target = \'' . $name_target . '\' AND tahun > \'' . $year . '\' ORDER BY tahun ASC');

        return $query->getResultArray();
    }
    
    public function get_data_target_last_year($category, $name_target, $bulan)
    {
        $year = date('Y', strtotime($bulan));
        $query = $this->db->query('SELECT TOP(1) target FROM data_target WHERE category = \'' . $category . '\' AND name_target = \'' . $name_target . '\' AND tahun < \'' . $year . '\' ORDER BY tahun DESC');

        return $query->getResultArray();
    }

    public function get_data_target_by_year($category, $name_target, $bulan)
    {
        $year = date('Y', strtotime($bulan));
        $query = $this->db->query('SELECT TOP(1) target FROM data_target WHERE category = \'' . $category . '\' AND name_target = \'' . $name_target . '\' AND tahun = \'' . $year . '\'');

        return $query->getResultArray();
    }

    // SAW REPAIR

    public function get_data_saw_repair_by_month($bulan, $tahun, $shift, $operator)
    {
        $year = date('Y', strtotime($tahun));
        $query = $this->db->query('SELECT MONTH(lsr.tanggal_produksi) AS month, SUM(dlsr.qty) as qty
                              FROM detail_lhp_saw_repair dlsr
                              JOIN lhp_saw_repair lsr on lsr.id_lhp_saw_repair = dlsr.id_lhp_saw_repair
                              WHERE MONTH(lsr.tanggal_produksi) = \'' . $bulan . '\' AND YEAR(lsr.tanggal_produksi) = \'' . $year . '\''
            . (($shift != NULL && $shift != '') ? 'AND shift = \'' . $shift . '\'' : '')
            . (($operator != NULL && $operator != '') ? 'AND operator = \'' . $operator . '\'' : '') . '
                              GROUP BY MONTH(lsr.tanggal_produksi)
                          ');

        return $query->getResultArray();
    }

    public function get_data_saw_repair_by_date($tanggal, $shift, $operator)
    {
        $query = $this->db->query('SELECT lsr.tanggal_produksi, SUM(dlsr.qty) as qty
                                    FROM detail_lhp_saw_repair dlsr
                                    JOIN lhp_saw_repair lsr on lsr.id_lhp_saw_repair = dlsr.id_lhp_saw_repair
                                    WHERE lsr.tanggal_produksi = \'' . $tanggal . '\''
            . (($shift != NULL && $shift != '') ? 'AND shift = \'' . $shift . '\'' : '')
            . (($operator != NULL && $operator != '') ? 'AND operator = \'' . $operator . '\'' : '') . '
                                    GROUP BY lsr.tanggal_produksi
                                ');

        return $query->getResultArray();
    }

    public function get_data_type_battery_by_month($bulan, $shift, $operator)
    {
        $month = date('m', strtotime($bulan));
        $year = date('Y', strtotime($bulan));
        $query = $this->db->query('SELECT dlsr.type_battery, SUM(dlsr.qty) AS qty
                              FROM detail_lhp_saw_repair dlsr
                              JOIN lhp_saw_repair lsr on lsr.id_lhp_saw_repair = dlsr.id_lhp_saw_repair
                              WHERE MONTH(lsr.tanggal_produksi) = \'' . $month . '\' AND YEAR(lsr.tanggal_produksi) = \'' . $year . '\''
            . (($shift != NULL && $shift != '') ? 'AND shift = \'' . $shift . '\'' : '')
            . (($operator != NULL && $operator != '') ? 'AND operator = \'' . $operator . '\'' : '') . '
                              GROUP BY dlsr.type_battery
                              ORDER BY SUM(dlsr.qty) DESC
                          ');

        return $query->getResultArray();
    }

    public function get_data_type_battery_by_date($date, $shift, $operator)
    {
        $query = $this->db->query('SELECT dlsr.type_battery, SUM(dlsr.qty) AS qty
                              FROM detail_lhp_saw_repair dlsr
                              JOIN lhp_saw_repair lsr on lsr.id_lhp_saw_repair = dlsr.id_lhp_saw_repair
                              WHERE lsr.tanggal_produksi = \'' . $date . '\''
            . (($shift != NULL && $shift != '') ? 'AND shift = \'' . $shift . '\'' : '')
            . (($operator != NULL && $operator != '') ? 'AND operator = \'' . $operator . '\'' : '') . '
                              GROUP BY dlsr.type_battery
                              ORDER BY SUM(dlsr.qty) DESC
                          ');

        return $query->getResultArray();
    }

    public function get_data_element_repair_type_by_month($bulan, $shift, $operator)
    {
        $date = date('Y-m-t', strtotime($bulan));
        $query = $this->db->query('SELECT dlpbe.*
                              FROM detail_lhp_potong_battery_element dlpbe
                              JOIN lhp_potong_battery lpb on lpb.id_lhp_potong_battery = dlpbe.id_lhp_potong_battery
                              WHERE lpb.tanggal_produksi <= \'' . $date . '\'
                          ');

        return $query->getResultArray();
    }

    public function get_data_saw_repair_type_by_month($bulan, $shift, $operator)
    {
        $date = date('Y-m-t', strtotime($bulan));
        $query = $this->db->query('SELECT dlsr.type_battery, mdr.type_positif, mdr.type_negatif, mdr.pasangan_positif, mdr.pasangan_negatif,  SUM(qty) * 6 as total
                              FROM detail_lhp_saw_repair dlsr
                              JOIN lhp_saw_repair lpb on lpb.id_lhp_saw_repair = dlsr.id_lhp_saw_repair
							  JOIN master_data_repair mdr on mdr.type_battery = dlsr.type_battery
                              WHERE lpb.tanggal_produksi <= \'' . $date . '\''
            . (($shift != NULL && $shift != '') ? ' AND shift = \'' . $shift . '\'' : '')
            . (($operator != NULL && $operator != '') ? ' AND operator = \'' . $operator . '\'' : '') . '
							  group by dlsr.type_battery, mdr.type_positif, mdr.type_negatif, mdr.pasangan_positif, mdr.pasangan_negatif
                          ');

        return $query->getResultArray();
    }

    public function get_data_adjustment_stock_saw_repair()
    {
        $query = $this->db->query('SELECT type_positif, type_negatif, SUM(total_positif) AS total_positif, SUM(total_negatif) AS total_negatif, assr.status FROM adjustment_stock_saw_repair assr
                                LEFT JOIN detail_adjustment_stock_saw_repair dassr ON assr.id_adjustment = dassr.id_adjustment
                                GROUP BY type_positif, type_negatif, assr.status
                            ');
        return $query->getResultArray();
    }

    public function get_data_element_repair_type_by_date($date, $shift, $operator)
    {
        $query = $this->db->query('SELECT dlpbe.*
                              FROM detail_lhp_potong_battery_element dlpbe
                              JOIN lhp_potong_battery lpb on lpb.id_lhp_potong_battery = dlpbe.id_lhp_potong_battery
                              WHERE lpb.tanggal_produksi <= \'' . $date . '\''
            . (($shift != NULL && $shift != '') ? 'AND shift = \'' . $shift . '\'' : '')
            . (($operator != NULL && $operator != '') ? 'AND operator = \'' . $operator . '\'' : '') . '
                          ');

        return $query->getResultArray();
    }

    public function get_data_operator_saw_repair_by_year($month)
    {
        $year = date('Y', strtotime($month));
        $query = $this->db->query('SELECT DISTINCT operator
                              FROM lhp_saw_repair
                              WHERE YEAR(tanggal_produksi) = \'' . $year . '\'
                          ');

        return $query->getResultArray();
    }

    // FINISHING REPAIR

    public function get_data_finishing_repair_by_month($bulan, $month, $line)
    {
        $year = date('Y', strtotime($month));
        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT MONTH(lfsr.tanggal_produksi) AS bulan, SUM(lfsr.total_aktual) AS total_aktual
                                          FROM lhp_finishing_saw_repair lfsr
                                          WHERE MONTH(lfsr.tanggal_produksi) = \'' . $bulan . '\' AND YEAR(lfsr.tanggal_produksi) = \'' . $year . '\'
                                          GROUP BY MONTH(lfsr.tanggal_produksi)
                                    ');
        } else {
            $query = $this->db->query('SELECT MONTH(lfsr.tanggal_produksi) AS bulan, SUM(lfsr.total_aktual) AS total_aktual
                                          FROM lhp_finishing_saw_repair lfsr
                                          WHERE MONTH(lfsr.tanggal_produksi) = \'' . $bulan . '\' AND YEAR(lfsr.tanggal_produksi) = \'' . $year . '\' AND lfsr.line = \'' . $line . '\'
                                          GROUP BY MONTH(lfsr.tanggal_produksi)
                                    ');
        }

        return $query->getResultArray();
    }

    public function get_data_total_finishing_repair_line_by_month($month)
    {
        $bulan = idate('m', strtotime($month));
        $year = idate('Y', strtotime($month));
        $query = $this->db->query('SELECT subquery.line,
                                SUM(total_aktual) AS total_aktual
                                FROM (
                                    SELECT lfsr.line, SUM(lfsr.total_aktual) AS total_aktual
                                    FROM lhp_finishing_saw_repair lfsr
                                    WHERE MONTH(lfsr.tanggal_produksi) = \'' . $bulan . '\' AND YEAR(lfsr.tanggal_produksi) = \'' . $year . '\'
                                    GROUP BY lfsr.line
                                ) AS subquery
                                GROUP BY subquery.line
                                ORDER BY SUM(total_aktual) DESC
                                ');

        return $query->getResultArray();
    }

    public function get_data_finishing_repair_all_line_by_date($tanggal, $line)
    {
        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT subquery.tanggal_produksi,
                                        SUM(total_aktual) AS total_aktual
                                        FROM (
                                            SELECT
                                                lfsr.tanggal_produksi,
                                                SUM(lfsr.total_aktual) AS total_aktual
                                            FROM lhp_finishing_saw_repair lfsr
                                            WHERE lfsr.tanggal_produksi = \'' . $tanggal . '\'
                                            GROUP BY lfsr.tanggal_produksi
                                        ) AS subquery
                                        GROUP BY subquery.tanggal_produksi');
        } else {
            $query = $this->db->query('SELECT subquery.tanggal_produksi,
                                        SUM(total_aktual) AS total_aktual
                                        FROM (
                                            SELECT
                                                lfsr.tanggal_produksi,
                                                SUM(lfsr.total_aktual) AS total_aktual
                                            FROM lhp_finishing_saw_repair lfsr
                                            WHERE lfsr.tanggal_produksi = \'' . $tanggal . '\'
                                            AND lfsr.line = \'' . $line . '\'
                                            GROUP BY lfsr.tanggal_produksi
                                        ) AS subquery
                                        GROUP BY subquery.tanggal_produksi');
        }

        return $query->getResultArray();
    }

    public function get_data_average_finishing_repair_by_month($bulan, $month, $line)
    {
        $year = date('Y', strtotime($month));
        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT subquery.bulan,
                                      SUM(total_aktual) AS total_aktual
                                      FROM (
                                          SELECT MONTH(lfsr.tanggal_produksi) AS bulan, SUM(lfsr.total_aktual) AS total_aktual
                                          FROM lhp_finishing_saw_repair lfsr
                                          WHERE MONTH(lfsr.tanggal_produksi) = \'' . $bulan . '\' AND YEAR(lfsr.tanggal_produksi) = \'' . $year . '\'
                                          GROUP BY MONTH(lfsr.tanggal_produksi)
                                      ) AS subquery
									                    GROUP BY subquery.bulan
                                      ORDER BY subquery.bulan
                                      ');
        } else {
            $query = $this->db->query('SELECT subquery.bulan,
                                      SUM(total_aktual) AS total_aktual
                                      FROM (
                                          SELECT MONTH(lfsr.tanggal_produksi) AS bulan, SUM(lfsr.total_aktual) AS total_aktual
                                          FROM lhp_finishing_saw_repair lfsr
                                          WHERE MONTH(lfsr.tanggal_produksi) = \'' . $bulan . '\' AND YEAR(lfsr.tanggal_produksi) = \'' . $year . '\' AND lfsr.line = \'' . $line . '\'
                                          GROUP BY MONTH(lfsr.tanggal_produksi)
                                      ) AS subquery
									                    GROUP BY subquery.bulan
                                      ORDER BY subquery.bulan
                                      ');
        }

        return $query->getResultArray();
    }

    public function get_data_finishing_repair_all_line($tanggal, $line)
    {
        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT subquery.tanggal_produksi, subquery.line, SUM(total_aktual) AS total_aktual
                                    FROM (
                                        SELECT lfsr.tanggal_produksi, lfsr.line, SUM(lfsr.total_aktual) AS total_aktual
                                        FROM lhp_finishing_saw_repair lfsr
                                        WHERE lfsr.tanggal_produksi = \'' . $tanggal . '\'
                                        GROUP BY lfsr.tanggal_produksi, lfsr.line
                                    ) AS subquery
                                    GROUP BY subquery.tanggal_produksi, subquery.line');
        } else {
            $query = $this->db->query('SELECT subquery.tanggal_produksi, subquery.line, SUM(total_aktual) AS total_aktual
                                    FROM (
                                        SELECT lfsr.tanggal_produksi, lfsr.line, SUM(lfsr.total_aktual) AS total_aktual
                                        FROM lhp_finishing_saw_repair lfsr
                                        WHERE lfsr.tanggal_produksi = \'' . $tanggal . '\' AND lfsr.line = \'' . $line . '\'
                                        GROUP BY lfsr.tanggal_produksi, lfsr.line
                                    ) AS subquery
                                    GROUP BY subquery.tanggal_produksi, subquery.line');
        }


        return $query->getResultArray();
    }

    public function get_data_finishing_repair_all_line_by_month($bulan, $line)
    {
        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT subquery.bulan, subquery.line, SUM(total_aktual) AS total_aktual
                                    FROM (
                                        SELECT MONTH(lfsr.tanggal_produksi) AS bulan, lfsr.line, SUM(lfsr.total_aktual) AS total_aktual
                                        FROM lhp_finishing_saw_repair lfsr
                                        WHERE MONTH(lfsr.tanggal_produksi) = \'' . $bulan . '\'
                                        GROUP BY lfsr.tanggal_produksi, lfsr.line
                                    ) AS subquery
                                    GROUP BY subquery.bulan, subquery.line
                                    ');
        } else {
            $query = $this->db->query('SELECT subquery.bulan, subquery.line, SUM(total_aktual) AS total_aktual
                                    FROM (
                                        SELECT MONTH(lfsr.tanggal_produksi) AS bulan, lfsr.line, SUM(lfsr.total_aktual) AS total_aktual
                                        FROM lhp_finishing_saw_repair lfsr
                                        WHERE MONTH(lfsr.tanggal_produksi) = \'' . $bulan . '\' AND lfsr.line = \'' . $line . '\'
                                        GROUP BY lfsr.tanggal_produksi, lfsr.line
                                    ) AS subquery
                                    GROUP BY subquery.bulan, subquery.line
                                    ');
        }

        return $query->getResultArray();
    }

    public function get_data_type_battery_saw_repair_type_by_month($bulan, $line)
    {
        $date = date('Y-m-t', strtotime($bulan));
        $query = $this->db->query('SELECT dlsr.type_battery, SUM(qty) AS total
                              FROM detail_lhp_saw_repair dlsr
                              JOIN lhp_saw_repair lpb on lpb.id_lhp_saw_repair = dlsr.id_lhp_saw_repair
                              WHERE lpb.tanggal_produksi <= \'' . $date . '\'
                              GROUP BY dlsr.type_battery
                          ');

        return $query->getResultArray();
    }

    public function get_data_finishing_repair_type_by_month($bulan, $line)
    {
        $date = date('Y-m-t', strtotime($bulan));
        $query = $this->db->query('SELECT dlsr.type_battery, SUM(dlsr.actual) as total
                              FROM detail_lhp_finishing_saw_repair dlsr
                              JOIN lhp_finishing_saw_repair lfsr on lfsr.id_finishing_saw_repair = dlsr.id_finishing_saw_repair
                              WHERE lfsr.tanggal_produksi <= \'' . $date . '\''
            . (($line != NULL && $line != '') ? ' AND line = \'' . $line . '\'' : '') . '
							  group by dlsr.type_battery
                          ');

        return $query->getResultArray();
    }

    public function get_data_element_finishing_repair_by_type_by_month($type_battery)
    {
        $query = $this->db->query('SELECT * FROM master_data_repair WHERE type_battery = \'' . $type_battery . '\'');

        return $query->getResultArray();
    }

    public function get_data_element_finishing_repair_type_by_month($bulan, $line)
    {
        $month = date('m', strtotime($bulan));
        $year = date('Y', strtotime($bulan));
        $query = $this->db->query('SELECT dlsr.type_battery, mdr.type_positif, mdr.type_negatif, mdr.pasangan_positif, mdr.pasangan_negatif,  SUM(actual) as total
                              FROM detail_lhp_finishing_saw_repair dlsr
                              JOIN lhp_finishing_saw_repair lfsr on lfsr.id_finishing_saw_repair = dlsr.id_finishing_saw_repair
							  JOIN master_data_repair mdr on mdr.type_battery = dlsr.type_battery
                              WHERE MONTH(lfsr.tanggal_produksi) <= \'' . $month . '\' AND YEAR(lfsr.tanggal_produksi) <= \'' . $year . '\'
							  group by dlsr.type_battery, mdr.type_positif, mdr.type_negatif, mdr.pasangan_positif, mdr.pasangan_negatif
                          ');

        return $query->getResultArray();
    }

    public function get_data_type_battery_finishing_repair_by_date($date, $line)
    {
        $query = $this->db->query('SELECT dlsr.type_battery, SUM(dlsr.actual) AS total
                              FROM detail_lhp_finishing_saw_repair dlsr
                              JOIN lhp_finishing_saw_repair lsr on lsr.id_finishing_saw_repair = dlsr.id_finishing_saw_repair
                              WHERE lsr.tanggal_produksi = \'' . $date . '\''
            . (($line != NULL && $line != 0) ? ' AND line = \'' . $line . '\'' : '') . '
                              GROUP BY dlsr.type_battery
                              ORDER BY SUM(dlsr.actual) DESC
                          ');

        return $query->getResultArray();
    }

    public function get_data_type_battery_finishing_repair_by_month($bulan, $line)
    {
        $month = date('m', strtotime($bulan));
        $year = date('Y', strtotime($bulan));
        $query = $this->db->query('SELECT dlsr.type_battery, SUM(dlsr.actual) AS total
                              FROM detail_lhp_finishing_saw_repair dlsr
                              JOIN lhp_finishing_saw_repair lsr on lsr.id_finishing_saw_repair = dlsr.id_finishing_saw_repair
                              WHERE MONTH(lsr.tanggal_produksi) = \'' . $month . '\' AND YEAR(lsr.tanggal_produksi) = \'' . $year . '\''
            . (($line != NULL && $line != 0) ? ' AND line = \'' . $line . '\'' : '') . '
                              GROUP BY dlsr.type_battery
                              ORDER BY SUM(dlsr.actual) DESC
                          ');

        return $query->getResultArray();
    }

    public function get_data_adjustment_stock_finishing_repair()
    {
        $query = $this->db->query('SELECT type_battery, SUM(total_battery) AS total_battery, asfr.status, asfr.id_adjustment FROM adjustment_stock_finishing_repair asfr
                                LEFT JOIN detail_adjustment_stock_finishing_repair dasfr ON asfr.id_adjustment = dasfr.id_adjustment
                                GROUP BY type_battery, asfr.status, asfr.id_adjustment
                                ORDER BY status ASC');
        return $query->getResultArray();
    }
}
