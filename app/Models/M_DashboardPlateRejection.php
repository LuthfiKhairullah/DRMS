<?php 
namespace App\Models;
use CodeIgniter\Model;



class M_DashboardPlateRejection extends Model
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

    //MODAL POTONG BATTERY

    public function get_data_plate_ng_by_month($bulan, $tahun, $shift, $operator)
    {
        $year = date('Y', strtotime($tahun));
        $query = $this->db->query('SELECT MONTH(lpb.tanggal_produksi) AS month, SUM(dlpbp.bolong) + SUM(dlpbp.lug_pendek) + SUM(dlpbp.patah_frame) + SUM(dlpbp.rontok) + SUM(dlpbp.other) as panel, SUM(dlpbp.total) as kg
                              FROM detail_lhp_potong_battery_plate dlpbp 
                              JOIN lhp_potong_battery lpb on lpb.id_lhp_potong_battery = dlpbp.id_lhp_potong_battery
                              WHERE MONTH(lpb.tanggal_produksi) = \'' . $bulan . '\' AND YEAR(lpb.tanggal_produksi) = \'' . $year . '\''
                              . (($shift != NULL && $shift != '') ? 'AND shift = \'' . $shift . '\'' : '')
                              . (($operator != NULL && $operator != '') ? 'AND operator = \'' . $operator . '\'' : '') . '
                              GROUP BY MONTH(lpb.tanggal_produksi)
                          ');
        
        return $query->getResultArray();
    }

    public function get_data_plate_ng_by_date($tanggal, $shift, $operator)
    {
        $query = $this->db->query('SELECT lpb.tanggal_produksi, SUM(dlpbp.bolong) + SUM(dlpbp.lug_pendek) + SUM(dlpbp.patah_frame) + SUM(dlpbp.rontok) + SUM(dlpbp.other) as panel, SUM(dlpbp.total) as kg
                                    FROM detail_lhp_potong_battery_plate dlpbp
                                    JOIN lhp_potong_battery lpb on lpb.id_lhp_potong_battery = dlpbp.id_lhp_potong_battery
                                    WHERE lpb.tanggal_produksi = \'' . $tanggal . '\''
                                    . (($shift != NULL && $shift != '') ? 'AND shift = \'' . $shift . '\'' : '')
                                    . (($operator != NULL && $operator != '') ? 'AND operator = \'' . $operator . '\'' : '') . '
                                    GROUP BY lpb.tanggal_produksi
                                ');
        
        return $query->getResultArray();
    }

    public function get_data_plate_ng_type_by_month($bulan, $shift, $operator)
    {
        $month = date('m', strtotime($bulan));
        $year = date('Y', strtotime($bulan));
        $query = $this->db->query('SELECT dlpbp.type, SUM(dlpbp.bolong) + SUM(dlpbp.lug_pendek) + SUM(dlpbp.patah_frame) + SUM(dlpbp.rontok) + SUM(dlpbp.other) AS panel, SUM(dlpbp.total) AS kg
                              FROM detail_lhp_potong_battery_plate dlpbp
                              JOIN lhp_potong_battery lpb on lpb.id_lhp_potong_battery = dlpbp.id_lhp_potong_battery
                              WHERE MONTH(lpb.tanggal_produksi) = \'' . $month . '\' AND YEAR(lpb.tanggal_produksi) = \'' . $year . '\''
                              . (($shift != NULL && $shift != '') ? 'AND shift = \'' . $shift . '\'' : '')
                              . (($operator != NULL && $operator != '') ? 'AND operator = \'' . $operator . '\'' : '') . '
                              GROUP BY dlpbp.type
                              ORDER BY SUM(dlpbp.bolong) + SUM(dlpbp.lug_pendek) + SUM(dlpbp.patah_frame) + SUM(dlpbp.rontok) + SUM(dlpbp.other) DESC
                          ');
        
        return $query->getResultArray();
    }

    public function get_data_plate_ng_type_by_date($date, $shift, $operator)
    {
        $query = $this->db->query('SELECT dlpbp.type, SUM(dlpbp.bolong) + SUM(dlpbp.lug_pendek) + SUM(dlpbp.patah_frame) + SUM(dlpbp.rontok) + SUM(dlpbp.other) AS panel, SUM(dlpbp.total) AS kg
                              FROM detail_lhp_potong_battery_plate dlpbp
                              JOIN lhp_potong_battery lpb on lpb.id_lhp_potong_battery = dlpbp.id_lhp_potong_battery
                              WHERE lpb.tanggal_produksi = \'' . $date . '\''
                              . (($shift != NULL && $shift != '') ? 'AND shift = \'' . $shift . '\'' : '')
                              . (($operator != NULL && $operator != '') ? 'AND operator = \'' . $operator . '\'' : '') . '
                              GROUP BY dlpbp.type
                              ORDER BY SUM(dlpbp.bolong) + SUM(dlpbp.lug_pendek) + SUM(dlpbp.patah_frame) + SUM(dlpbp.rontok) + SUM(dlpbp.other) DESC
                          ');
        
        return $query->getResultArray();
    }

    public function get_data_element_repair_by_month($bulan, $tahun, $shift, $operator)
    {
        $year = date('Y', strtotime($tahun));
        $query = $this->db->query('SELECT MONTH(lpb.tanggal_produksi) AS month, SUM(dlpbe.total) as total
                              FROM detail_lhp_potong_battery_element dlpbe
                              JOIN lhp_potong_battery lpb on lpb.id_lhp_potong_battery = dlpbe.id_lhp_potong_battery
                              WHERE MONTH(lpb.tanggal_produksi) = \'' . $bulan . '\' AND YEAR(lpb.tanggal_produksi) = \'' . $year . '\''
                              . (($shift != NULL && $shift != '') ? 'AND shift = \'' . $shift . '\'' : '')
                              . (($operator != NULL && $operator != '') ? 'AND operator = \'' . $operator . '\'' : '') . '
                              GROUP BY MONTH(lpb.tanggal_produksi)
                          ');
        
        return $query->getResultArray();
    }

    public function get_data_element_repair_by_date($tanggal, $shift, $operator)
    {
        $query = $this->db->query('SELECT lpb.tanggal_produksi, SUM(dlpbe.total) as total
                                    FROM detail_lhp_potong_battery_element dlpbe
                                    JOIN lhp_potong_battery lpb on lpb.id_lhp_potong_battery = dlpbe.id_lhp_potong_battery
                                    WHERE lpb.tanggal_produksi = \'' . $tanggal . '\''
                                    . (($shift != NULL && $shift != '') ? 'AND shift = \'' . $shift . '\'' : '')
                                    . (($operator != NULL && $operator != '') ? 'AND operator = \'' . $operator . '\'' : '') . '
                                    GROUP BY lpb.tanggal_produksi
                                ');
        
        return $query->getResultArray();
    }

    public function get_data_element_repair_type_by_month($bulan, $shift, $operator)
    {
        $month = date('m', strtotime($bulan));
        $year = date('Y', strtotime($bulan));
        $query = $this->db->query('SELECT dlpbe.type_positif, dlpbe.pasangan_positif, dlpbe.type_negatif, dlpbe.pasangan_negatif, SUM(dlpbe.total) AS total
                              FROM detail_lhp_potong_battery_element dlpbe
                              JOIN lhp_potong_battery lpb on lpb.id_lhp_potong_battery = dlpbe.id_lhp_potong_battery
                              WHERE MONTH(lpb.tanggal_produksi) = \'' . $month . '\' AND YEAR(lpb.tanggal_produksi) = \'' . $year . '\''
                              . (($shift != NULL && $shift != '') ? 'AND shift = \'' . $shift . '\'' : '')
                              . (($operator != NULL && $operator != '') ? 'AND operator = \'' . $operator . '\'' : '') . '
                              GROUP BY dlpbe.type_positif, dlpbe.pasangan_positif, dlpbe.type_negatif, dlpbe.pasangan_negatif
                          ');
        
        return $query->getResultArray();
    }

    public function get_data_element_repair_type_by_date($date, $shift, $operator)
    {
        $query = $this->db->query('SELECT dlpbe.type_positif, dlpbe.pasangan_positif, dlpbe.type_negatif, dlpbe.pasangan_negatif, SUM(dlpbe.total) AS total
                              FROM detail_lhp_potong_battery_element dlpbe
                              JOIN lhp_potong_battery lpb on lpb.id_lhp_potong_battery = dlpbe.id_lhp_potong_battery
                              WHERE lpb.tanggal_produksi = \'' . $date . '\''
                              . (($shift != NULL && $shift != '') ? 'AND shift = \'' . $shift . '\'' : '')
                              . (($operator != NULL && $operator != '') ? 'AND operator = \'' . $operator . '\'' : '') . '
                              GROUP BY dlpbe.type_positif, dlpbe.pasangan_positif, dlpbe.type_negatif, dlpbe.pasangan_negatif
                          ');
        
        return $query->getResultArray();
    }

    public function get_data_operator_by_year($month)
    {
        $year = date('Y', strtotime($month));
        $query = $this->db->query('SELECT DISTINCT operator
                              FROM lhp_potong_battery
                              WHERE YEAR(tanggal_produksi) = \'' . $year . '\'
                          ');
        
        return $query->getResultArray();
    }

    //MODAL SAW

    public function get_team()
    {
        $query = $this->db->query('SELECT * FROM team');

        return $query->getResultArray();
    }

    public function get_data_saw_by_date($tanggal, $shift, $team)
    {
        $query = $this->db->query('SELECT ls.tanggal_produksi, dls.type_battery, SUM(dls.kejepit) + SUM(dls.ketarik) + SUM(dls.terbakar) + SUM(dls.rontok) as reject, SUM(dls.hasil) as total
                                    FROM detail_lhp_saw dls
                                    JOIN lhp_saw ls on ls.id_lhp_saw = dls.id_lhp_saw
                                    WHERE ls.tanggal_produksi = \'' . $tanggal . '\''
                                    . (($shift != NULL && $shift != '') ? 'AND shift = \'' . $shift . '\'' : '')
                                    . (($team != NULL && $team != '') ? 'AND team = \'' . $team . '\'' : '') . '
                                    GROUP BY ls.tanggal_produksi, dls.type_battery
                                ');
        
        return $query->getResultArray();
    }

    public function get_data_saw_by_month($bulan, $tahun, $shift, $team)
    {
        $year = date('Y', strtotime($tahun));
        $query = $this->db->query('SELECT MONTH(ls.tanggal_produksi) AS month, dls.type_battery, SUM(dls.kejepit) + SUM(dls.ketarik) + SUM(dls.terbakar) + SUM(dls.rontok) as reject, SUM(dls.hasil) as total
                              FROM detail_lhp_saw dls 
                              JOIN lhp_saw ls on ls.id_lhp_saw = dls.id_lhp_saw
                              WHERE MONTH(ls.tanggal_produksi) = \'' . $bulan . '\' AND YEAR(ls.tanggal_produksi) = \'' . $year . '\''
                              . (($shift != NULL && $shift != '') ? 'AND shift = \'' . $shift . '\'' : '')
                              . (($team != NULL && $team != '') ? 'AND team = \'' . $team . '\'' : '') . '
                              GROUP BY MONTH(ls.tanggal_produksi), dls.type_battery
                          ');
        
        return $query->getResultArray();
    }

    public function get_detail_saw_by_month($bulan, $shift, $team)
    {
        $month = date('m', strtotime($bulan));
        $year = date('Y', strtotime($bulan));
        $query = $this->db->query('SELECT dls.type_battery, SUM(dls.kejepit) + SUM(dls.ketarik) + SUM(dls.terbakar) + SUM(dls.rontok) as reject, SUM(dls.hasil) as total
                              FROM detail_lhp_saw dls
                              JOIN lhp_saw ls on ls.id_lhp_saw = dls.id_lhp_saw
                              WHERE MONTH(ls.tanggal_produksi) = \'' . $month . '\' AND YEAR(ls.tanggal_produksi) = \'' . $year . '\''
                              . (($shift != NULL && $shift != '') ? 'AND shift = \'' . $shift . '\'' : '')
                              . (($team != NULL && $team != '') ? 'AND team = \'' . $team . '\'' : '') . '
                              GROUP BY dls.type_battery
                              ORDER BY SUM(dls.kejepit) + SUM(dls.ketarik) + SUM(dls.terbakar) + SUM(dls.rontok) DESC
                          ');
        
        return $query->getResultArray();
    }

    public function get_detail_saw_by_date($date, $shift, $team)
    {
        $query = $this->db->query('SELECT dls.type_battery, SUM(dls.kejepit) + SUM(dls.ketarik) + SUM(dls.terbakar) + SUM(dls.rontok) as reject, SUM(dls.hasil) as total
                              FROM detail_lhp_saw dls
                              JOIN lhp_saw lpb on lpb.id_lhp_saw = dls.id_lhp_saw
                              WHERE lpb.tanggal_produksi = \'' . $date . '\''
                              . (($shift != NULL && $shift != '') ? 'AND shift = \'' . $shift . '\'' : '')
                              . (($team != NULL && $team != '') ? 'AND team = \'' . $team . '\'' : '') . '
                              GROUP BY dls.type_battery
                              ORDER BY SUM(dls.kejepit) + SUM(dls.ketarik) + SUM(dls.terbakar) + SUM(dls.rontok) DESC
                          ');
        
        return $query->getResultArray();
    }

    //MODAL ENVELOPE

    public function get_year_to_date_envelope($month, $line)
    {
        $tahun = date('Y', strtotime($month));

        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT subquery.tahun, SUM(total_panel) AS total_panel, SUM(total_produksi) AS total_produksi
                                      FROM (
                                          SELECT YEAR(e.date) AS tahun, SUM(ei.hasil_produksi) AS total_produksi, SUM(ei.melintir_bending_panel) + SUM(ei.terpotong_panel) + SUM(ei.rontok_panel) + SUM(ei.tersangkut_panel) AS total_panel
                                          FROM envelopeinput ei
                                          JOIN envelope e ON ei.id_envelope = e.id
                                          WHERE YEAR(e.date) = \'' . $tahun . '\'
                                          AND e.line != 10
                                          GROUP BY YEAR(e.date)
                                      ) AS subquery
                                      GROUP BY subquery.tahun
                                      ORDER BY subquery.tahun
                                      ');
        } else {
            $query = $this->db->query('SELECT subquery.tahun, SUM(total_panel) AS total_panel, SUM(total_produksi) AS total_produksi
                                      FROM (
                                          SELECT YEAR(e.date) AS tahun, SUM(ei.hasil_produksi) AS total_produksi, SUM(ei.melintir_bending_panel) + SUM(ei.terpotong_panel) + SUM(ei.rontok_panel) + SUM(ei.tersangkut_panel) AS total_panel
                                          FROM envelopeinput ei
                                          JOIN envelope e ON ei.id_envelope = e.id
                                          WHERE YEAR(e.date) = \'' . $tahun . '\' AND e.line = \'' . $line . '\'
                                          GROUP BY YEAR(e.date)
                                      ) AS subquery
                                      GROUP BY subquery.tahun
                                      ORDER BY subquery.tahun
                                      ');
        }

        return $query->getResultArray();
    }

    public function get_data_envelope_by_month($bulan, $month, $line)
    {
        $year = date('Y', strtotime($month));
        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT MONTH(e.date) AS bulan, SUM(ei.hasil_produksi) AS total_produksi, SUM(ei.melintir_bending_panel) + SUM(ei.terpotong_panel) + SUM(ei.rontok_panel) + SUM(ei.tersangkut_panel) AS total_panel
                                          FROM envelopeinput ei
                                          JOIN envelope e ON ei.id_envelope = e.id
                                          WHERE MONTH(e.date) = \'' . $bulan . '\' AND YEAR(e.date) = \'' . $year . '\'
                                          AND e.line != 10
                                          GROUP BY MONTH(e.date)
                                    ');
        } else {
            $query = $this->db->query('SELECT MONTH(e.date) AS bulan, SUM(ei.hasil_produksi) AS total_produksi, SUM(ei.melintir_bending_panel) + SUM(ei.terpotong_panel) + SUM(ei.rontok_panel) + SUM(ei.tersangkut_panel) AS total_panel
                                          FROM envelopeinput ei
                                          JOIN envelope e ON ei.id_envelope = e.id
                                          WHERE MONTH(e.date) = \'' . $bulan . '\' AND YEAR(e.date) = \'' . $year . '\' AND e.line = \'' . $line . '\'
                                          GROUP BY MONTH(e.date)
                                    ');
        }

        return $query->getResultArray();
    }

    public function get_data_average_envelope_by_month($bulan, $month, $line)
    {
        $year = date('Y', strtotime($month));
        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT subquery.bulan,
                                      COALESCE(SUM(total_panel) / NULLIF(SUM(total_produksi), 0), 0) AS persentase,
                                      SUM(total_kg) AS kg
                                      FROM (
                                          SELECT MONTH(e.date) AS bulan, SUM(ei.hasil_produksi) AS total_produksi, SUM(ei.melintir_bending_panel) + SUM(ei.terpotong_panel) + SUM(ei.rontok_panel) + SUM(ei.tersangkut_panel) AS total_panel, SUM(ei.melintir_bending) + SUM(ei.terpotong) + SUM(ei.rontok) + SUM(ei.tersangkut) AS total_kg
                                          FROM envelopeinput ei
                                          JOIN envelope e ON ei.id_envelope = e.id
                                          WHERE MONTH(e.date) = \'' . $bulan . '\' AND YEAR(e.date) = \'' . $year . '\'
                                          AND e.line != 10
                                          GROUP BY MONTH(e.date)
                                      ) AS subquery
									                    GROUP BY subquery.bulan
                                      ORDER BY subquery.bulan
                                      ');
        } else {
            $query = $this->db->query('SELECT subquery.bulan,
                                      COALESCE(SUM(total_panel) / NULLIF(SUM(total_produksi), 0), 0) AS persentase,
                                      SUM(total_kg) AS kg
                                      FROM (
                                          SELECT MONTH(e.date) AS bulan, SUM(ei.hasil_produksi) AS total_produksi, SUM(ei.melintir_bending_panel) + SUM(ei.terpotong_panel) + SUM(ei.rontok_panel) + SUM(ei.tersangkut_panel) AS total_panel, SUM(ei.melintir_bending) + SUM(ei.terpotong) + SUM(ei.rontok) + SUM(ei.tersangkut) AS total_kg
                                          FROM envelopeinput ei
                                          JOIN envelope e ON ei.id_envelope = e.id
                                          WHERE MONTH(e.date) = \'' . $bulan . '\' AND YEAR(e.date) = \'' . $year . '\' AND e.line = \'' . $line . '\'
                                          GROUP BY MONTH(e.date)
                                      ) AS subquery
									                    GROUP BY subquery.bulan
                                      ORDER BY subquery.bulan
                                      ');
        }

        return $query->getResultArray();
    }

    public function get_data_envelope_all_line($tanggal, $line)
    {
        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT subquery.date, subquery.line, SUM(total_panel) AS total_panel, SUM(total_produksi) AS total_produksi
                                    FROM (
                                        SELECT e.date, e.line, SUM(ei.hasil_produksi) AS total_produksi, SUM(ei.melintir_bending_panel) + SUM(ei.terpotong_panel) + SUM(ei.rontok_panel) + SUM(ei.tersangkut_panel) AS total_panel
                                        FROM envelopeinput ei
                                        JOIN envelope e ON ei.id_envelope = e.id
                                        WHERE e.date = \'' . $tanggal . '\'
                                        AND e.line != 10
                                        GROUP BY e.date, e.line
                                    ) AS subquery
                                    GROUP BY subquery.date, subquery.line');
        } else {
            $query = $this->db->query('SELECT subquery.date, subquery.line, SUM(total_panel) AS total_panel, SUM(total_produksi) AS total_produksi
                                    FROM (
                                        SELECT e.date, e.line, SUM(ei.hasil_produksi) AS total_produksi, SUM(ei.melintir_bending_panel) + SUM(ei.terpotong_panel) + SUM(ei.rontok_panel) + SUM(ei.tersangkut_panel) AS total_panel
                                        FROM envelopeinput ei
                                        JOIN envelope e ON ei.id_envelope = e.id
                                        WHERE e.date = \'' . $tanggal . '\' AND e.line = \'' . $line . '\'
                                        GROUP BY e.date, e.line
                                    ) AS subquery
                                    GROUP BY subquery.date, subquery.line');
        }
        

        return $query->getResultArray();
    }

    public function get_data_envelope_all_line_by_month($bulan, $line)
    {
        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT subquery.bulan, subquery.line, SUM(total_panel) AS total_panel, SUM(total_produksi) AS total_produksi
                                    FROM (
                                        SELECT MONTH(e.date) AS bulan, e.line, SUM(ei.hasil_produksi) AS total_produksi, SUM(ei.melintir_bending_panel) + SUM(ei.terpotong_panel) + SUM(ei.rontok_panel) + SUM(ei.tersangkut_panel) AS total_panel
                                        FROM envelopeinput ei
                                        JOIN envelope e ON ei.id_envelope = e.id
                                        WHERE MONTH(e.date) = \''.$bulan.'\'
                                        AND e.line != 10
                                        GROUP BY e.date, e.line
                                    ) AS subquery
                                    GROUP BY subquery.bulan, subquery.line
                                    ');
        } else {
            $query = $this->db->query('SELECT subquery.bulan, subquery.line, SUM(total_panel) AS total_panel, SUM(total_produksi) AS total_produksi
                                    FROM (
                                        SELECT MONTH(e.date) AS bulan, e.line, SUM(ei.hasil_produksi) AS total_produksi, SUM(ei.melintir_bending_panel) + SUM(ei.terpotong_panel) + SUM(ei.rontok_panel) + SUM(ei.tersangkut_panel) AS total_panel
                                        FROM envelopeinput ei
                                        JOIN envelope e ON ei.id_envelope = e.id
                                        WHERE MONTH(e.date) = \''.$bulan.'\' AND e.line = \'' . $line . '\'
                                        GROUP BY e.date, e.line
                                    ) AS subquery
                                    GROUP BY subquery.bulan, subquery.line
                                    ');
        }

        return $query->getResultArray();
    }

    public function get_data_envelope_all_line_by_date($tanggal, $line)
    {
        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT subquery.date,
                                        COALESCE(SUM(total_panel) / NULLIF(SUM(total_produksi), 0), 0) AS persentase,
                                        SUM(total_kg) AS kg
                                        FROM (
                                            SELECT
                                                e.date,
                                                SUM(ei.hasil_produksi) AS total_produksi,
                                                SUM(ei.melintir_bending_panel) + SUM(ei.terpotong_panel) + SUM(ei.rontok_panel) + SUM(ei.tersangkut_panel) AS total_panel,
                                                SUM(ei.melintir_bending) + SUM(ei.terpotong) + SUM(ei.rontok) + SUM(ei.tersangkut) AS total_kg
                                            FROM envelopeinput ei
                                            JOIN envelope e ON ei.id_envelope = e.id
                                            WHERE e.date = \'' . $tanggal . '\'
                                            AND e.line != 10
                                            GROUP BY e.date
                                        ) AS subquery
                                        GROUP BY subquery.date');
                                      
        } else {
            $query = $this->db->query('SELECT subquery.date,
                                        COALESCE(SUM(total_panel) / NULLIF(SUM(total_produksi), 0), 0) AS persentase,
                                        SUM(total_kg) AS kg
                                        FROM (
                                            SELECT
                                                e.date,
                                                SUM(ei.hasil_produksi) AS total_produksi,
                                                SUM(ei.melintir_bending_panel) + SUM(ei.terpotong_panel) + SUM(ei.rontok_panel) + SUM(ei.tersangkut_panel) AS total_panel,
                                                SUM(ei.melintir_bending) + SUM(ei.terpotong) + SUM(ei.rontok) + SUM(ei.tersangkut) AS total_kg
                                            FROM envelopeinput ei
                                            JOIN envelope e ON ei.id_envelope = e.id
                                            WHERE e.date = \'' . $tanggal . '\' AND e.line = \'' . $line . '\'
                                            GROUP BY e.date
                                        ) AS subquery
                                        GROUP BY subquery.date');
        }
        
        return $query->getResultArray();
    }

    public function get_data_total_envelope_line_by_month($month) 
    {
        $bulan = idate('m', strtotime($month));
        $year = idate('Y', strtotime($month));
        $query = $this->db->query('SELECT subquery.line,
                                (COALESCE(SUM(total_panel) / NULLIF(SUM(total_produksi), 0), 0) * 100) AS persen,
                                SUM(kg) AS kg
                                FROM (
                                    SELECT e.line, SUM(ei.melintir_bending_panel) + SUM(ei.terpotong_panel) + SUM(ei.rontok_panel) + SUM(ei.tersangkut_panel) AS total_panel, SUM(ei.hasil_produksi) AS total_produksi, SUM(ei.melintir_bending) + SUM(ei.terpotong) + SUM(ei.rontok) + SUM(ei.tersangkut) AS kg
                                    FROM envelopeinput ei
                                    JOIN envelope e ON ei.id_envelope = e.id
                                    WHERE MONTH(e.date) = \'' . $bulan . '\' AND YEAR(e.date) = \'' . $year . '\' AND e.line != 10
                                    GROUP BY e.line
                                ) AS subquery
                                GROUP BY subquery.line
                                HAVING SUM(total_produksi) > 0
                                ORDER BY (COALESCE(SUM(total_panel) / NULLIF(SUM(total_produksi), 0), 0) * 100) DESC
                                ');

        return $query->getResultArray();
    }

    public function get_qty_jenis_envelope($tanggal, $line) 
    {
        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT SUM(ei.melintir_bending_panel) AS melintir_bending, SUM(ei.terpotong_panel) AS terpotong, SUM(ei.rontok_panel) AS rontok, SUM(ei.tersangkut_panel) AS tersangkut
                                        FROM envelopeinput ei
                                        JOIN envelope e ON ei.id_envelope = e.id
                                        WHERE e.date = \'' . $tanggal . '\'
                                        AND e.line != 10
                                    ');
        } else {
            $query = $this->db->query('SELECT SUM(ei.melintir_bending_panel) AS melintir_bending, SUM(ei.terpotong_panel) AS terpotong, SUM(ei.rontok_panel) AS rontok, SUM(ei.tersangkut_panel) AS tersangkut
                                        FROM envelopeinput ei
                                        JOIN envelope e ON ei.id_envelope = e.id
                                        WHERE e.date = \'' . $tanggal . '\' AND e.line = \'' . $line . '\'
                                    ');
        }

        return $query->getResultArray();
    }

    public function get_kg_jenis_envelope($tanggal, $line) 
    {
        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT SUM(ei.melintir_bending) AS melintir_bending, SUM(ei.terpotong) AS terpotong, SUM(ei.rontok) AS rontok, SUM(ei.tersangkut) AS tersangkut
                                        FROM envelopeinput ei
                                        JOIN envelope e ON ei.id_envelope = e.id
                                        WHERE e.date = \'' . $tanggal . '\'
                                        AND e.line != 10
                                    ');
        } else {
            $query = $this->db->query('SELECT SUM(ei.melintir_bending) AS melintir_bending, SUM(ei.terpotong) AS terpotong, SUM(ei.rontok) AS rontok, SUM(ei.tersangkut) AS tersangkut
                                        FROM envelopeinput ei
                                        JOIN envelope e ON ei.id_envelope = e.id
                                        WHERE p.date = \'' . $tanggal . '\' AND p.line = \'' . $line . '\'
                                    ');
        }

        return $query->getResultArray();
    }

    public function get_jenis_envelope_by_date($tanggal, $line) 
    {
        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT ei.plate, SUM(ei.hasil_produksi) AS total_produksi, SUM(ei.melintir_bending_panel) AS melintir_bending_panel, SUM(ei.terpotong_panel) AS terpotong_panel, SUM(ei.rontok_panel) AS rontok_panel, SUM(ei.tersangkut_panel) AS tersangkut_panel, SUM(ei.melintir_bending) AS melintir_bending_kg, SUM(ei.terpotong) AS terpotong_kg, SUM(ei.rontok) AS rontok_kg, SUM(ei.tersangkut) AS tersangkut_kg
                                        FROM envelopeinput ei
                                        JOIN envelope e ON ei.id_envelope = e.id
                                        WHERE e.date = \'' . $tanggal . '\'
                                        AND e.line != 10
										GROUP BY ei.plate
                                    ');
        } else {
            $query = $this->db->query('SELECT ei.plate, SUM(ei.hasil_produksi) AS total_produksi, SUM(ei.melintir_bending_panel) AS melintir_bending_panel, SUM(ei.terpotong_panel) AS terpotong_panel, SUM(ei.rontok_panel) AS rontok_panel, SUM(ei.tersangkut_panel) AS tersangkut_panel, SUM(ei.melintir_bending) AS melintir_bending_kg, SUM(ei.terpotong) AS terpotong_kg, SUM(ei.rontok) AS rontok_kg, SUM(ei.tersangkut) AS tersangkut_kg
                                        FROM envelopeinput ei
                                        JOIN envelope e ON ei.id_envelope = e.id
                                        WHERE e.date = \'' . $tanggal . '\' AND e.line = \'' . $line . '\'
										GROUP BY ei.plate
                                    ');
        }

        return $query->getResultArray();
    }

    public function get_jenis_envelope_by_month($tanggal, $line) 
    {
        $month = date('m', strtotime($tanggal));
        $year = date('Y', strtotime($tanggal));
        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT ei.plate, SUM(ei.hasil_produksi) AS total_produksi, SUM(ei.melintir_bending_panel) AS melintir_bending_panel, SUM(ei.terpotong_panel) AS terpotong_panel, SUM(ei.rontok_panel) AS rontok_panel, SUM(ei.tersangkut_panel) AS tersangkut_panel, SUM(ei.melintir_bending) AS melintir_bending_kg, SUM(ei.terpotong) AS terpotong_kg, SUM(ei.rontok) AS rontok_kg, SUM(ei.tersangkut) AS tersangkut_kg
                                        FROM envelopeinput ei
                                        JOIN envelope e ON ei.id_envelope = e.id
                                        WHERE MONTH(e.date) = \'' . $month . '\' AND YEAR(e.date) = \'' . $year . '\'
                                        AND e.line != 10
										GROUP BY ei.plate
                                    ');
        } else {
            $query = $this->db->query('SELECT ei.plate, SUM(ei.hasil_produksi) AS total_produksi, SUM(ei.melintir_bending_panel) AS melintir_bending_panel, SUM(ei.terpotong_panel) AS terpotong_panel, SUM(ei.rontok_panel) AS rontok_panel, SUM(ei.tersangkut_panel) AS tersangkut_panel, SUM(ei.melintir_bending) AS melintir_bending_kg, SUM(ei.terpotong) AS terpotong_kg, SUM(ei.rontok) AS rontok_kg, SUM(ei.tersangkut) AS tersangkut_kg
                                        FROM envelopeinput ei
                                        JOIN envelope e ON ei.id_envelope = e.id
                                        WHERE MONTH(e.date) = \'' . $month . '\' AND YEAR(e.date) = \'' . $year . '\' AND e.line = \'' . $line . '\'
										GROUP BY ei.plate
                                    ');
        }

        return $query->getResultArray();
    }

    public function get_data_qty_envelope_by_month($bulan, $line) 
    {
        $month = date('m', strtotime($bulan));
        $year = date('Y', strtotime($bulan));
        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT SUM(ei.melintir_bending_panel) AS melintir_bending, SUM(ei.terpotong_panel) AS terpotong, SUM(ei.rontok_panel) AS rontok, SUM(ei.tersangkut_panel) AS tersangkut
                                        FROM envelopeinput ei
                                        JOIN envelope e ON ei.id_envelope = e.id
                                        WHERE MONTH(e.date) = \'' . $month . '\' AND YEAR(e.date) = \'' . $year . '\'
                                        AND e.line != 10
                                    ');
        } else {
            $query = $this->db->query('SELECT SUM(ei.melintir_bending_panel) AS melintir_bending, SUM(ei.terpotong_panel) AS terpotong, SUM(ei.rontok_panel) AS rontok, SUM(ei.tersangkut_panel) AS tersangkut
                                        FROM envelopeinput ei
                                        JOIN envelope e ON ei.id_envelope = e.id
                                        WHERE MONTH(e.date) = \'' . $month . '\' AND YEAR(e.date) = \'' . $year . '\' AND e.line = \'' . $line . '\'
                                    ');
        }

        return $query->getResultArray();
    }

    public function get_data_kg_envelope_by_month($bulan, $line) 
    {
        $month = date('m', strtotime($bulan));
        $year = date('Y', strtotime($bulan));
        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT SUM(ei.melintir_bending) AS melintir_bending, SUM(ei.terpotong) AS terpotong, SUM(ei.rontok) AS rontok, SUM(ei.tersangkut) AS tersangkut
                                        FROM envelopeinput ei
                                        JOIN envelope e ON ei.id_envelope = e.id
                                        WHERE MONTH(e.date) = \'' . $month . '\' AND YEAR(e.date) = \'' . $year . '\'
                                        AND e.line != 10
                                    ');
        } else {
            $query = $this->db->query('SELECT SUM(ei.melintir_bending) AS melintir_bending, SUM(ei.terpotong) AS terpotong, SUM(ei.rontok) AS rontok, SUM(ei.tersangkut) AS tersangkut
                                        FROM envelopeinput ei
                                        JOIN envelope e ON ei.id_envelope = e.id
                                        WHERE MONTH(e.date) = \'' . $month . '\' AND YEAR(e.date) = \'' . $year . '\' AND e.line = \'' . $line . '\'
                                    ');
        }

        return $query->getResultArray();
    }

    //MODAL COS

    public function get_year_to_date_cos($month, $line)
    {
        $tahun = date('Y', strtotime($month));

        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT YEAR(lhp.tanggal_produksi) AS tahun, SUM(dlc.hasil) AS total_produksi, (SUM(dlc.tersangkut) + SUM(dlc.terbakar) + SUM(dlc.lug_lepas) + SUM(dlc.strap_tipis)) / 6 AS total_plate
                                        FROM detail_lhp_cos dlc
                                        JOIN lhp_cos lhp ON dlc.id_lhp_cos = lhp.id_lhp_cos
                                        WHERE YEAR(lhp.tanggal_produksi) = \'' . $tahun . '\'
                                        AND lhp.line != 10
                                        GROUP BY YEAR(lhp.tanggal_produksi)
                                      ');
        } else {
            $query = $this->db->query('SELECT YEAR(lhp.tanggal_produksi) AS tahun, SUM(dlc.hasil) AS total_produksi, (SUM(dlc.tersangkut) + SUM(dlc.terbakar) + SUM(dlc.lug_lepas) + SUM(dlc.strap_tipis)) / 6 AS total_plate
                                        FROM detail_lhp_cos dlc
                                        JOIN lhp_cos lhp ON dlc.id_lhp_cos = lhp.id_lhp_cos
                                        WHERE YEAR(lhp.tanggal_produksi) = \'' . $tahun . '\' AND lhp.line = \'' . $line . '\'
                                        GROUP BY YEAR(lhp.tanggal_produksi)
                                      ');
        }

        return $query->getResultArray();
    }

    public function get_data_cos_by_month($bulan, $month, $line)
    {
        $year = date('Y', strtotime($month));
        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT MONTH(lhp.tanggal_produksi) AS bulan, SUM(dlc.hasil) AS total_produksi, (SUM(dlc.tersangkut) + SUM(dlc.terbakar) + SUM(dlc.lug_lepas) + SUM(dlc.strap_tipis)) / 6 AS total_plate
                                        FROM detail_lhp_cos dlc
                                        JOIN lhp_cos lhp ON dlc.id_lhp_cos = lhp.id_lhp_cos
                                        WHERE MONTH(lhp.tanggal_produksi) = \'' . $bulan . '\' AND YEAR(lhp.tanggal_produksi) = \'' . $year . '\'
                                        AND lhp.line != 10
                                        GROUP BY MONTH(lhp.tanggal_produksi)
                                    ');
        } else {
            $query = $this->db->query('SELECT MONTH(lhp.tanggal_produksi) AS bulan, SUM(dlc.hasil) AS total_produksi, (SUM(dlc.tersangkut) + SUM(dlc.terbakar) + SUM(dlc.lug_lepas) + SUM(dlc.strap_tipis)) / 6 AS total_plate
                                        FROM detail_lhp_cos dlc
                                        JOIN lhp_cos lhp ON dlc.id_lhp_cos = lhp.id_lhp_cos
                                        WHERE MONTH(lhp.tanggal_produksi) = \'' . $bulan . '\' AND YEAR(lhp.tanggal_produksi) = \'' . $year . '\' AND lhp.line = \'' . $line . '\'
                                        GROUP BY MONTH(lhp.tanggal_produksi)
                                    ');
        }

        return $query->getResultArray();
    }

    public function get_data_average_cos_by_month($bulan, $month, $line)
    {
        $year = date('Y', strtotime($month));
        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT subquery.bulan,
                                      SUM(total_plate) / 6 AS total_plate, SUM(total_produksi) AS total_produksi
                                      FROM (
                                          SELECT MONTH(lhp.tanggal_produksi) AS bulan, SUM(dlc.hasil) AS total_produksi, SUM(dlc.tersangkut) + SUM(dlc.terbakar) + SUM(dlc.lug_lepas) + SUM(dlc.strap_tipis) AS total_plate
                                          FROM detail_lhp_cos dlc
                                          JOIN lhp_cos lhp ON dlc.id_lhp_cos = lhp.id_lhp_cos
                                          WHERE MONTH(lhp.tanggal_produksi) = \'' . $bulan . '\' AND YEAR(lhp.tanggal_produksi) = \'' . $year . '\'
                                          GROUP BY MONTH(lhp.tanggal_produksi)
                                      ) AS subquery
									                    GROUP BY subquery.bulan
                                      ORDER BY subquery.bulan
                                      ');
        } else {
            $query = $this->db->query('SELECT subquery.bulan,
                                      SUM(total_plate) / 6 AS total_plate, SUM(total_produksi) AS total_produksi
                                      FROM (
                                          SELECT MONTH(lhp.tanggal_produksi) AS bulan, SUM(dlc.hasil) AS total_produksi, SUM(dlc.tersangkut) + SUM(dlc.terbakar) + SUM(dlc.lug_lepas) + SUM(dlc.strap_tipis) AS total_plate
                                          FROM detail_lhp_cos dlc
                                          JOIN lhp_cos lhp ON dlc.id_lhp_cos = lhp.id_lhp_cos
                                          WHERE MONTH(lhp.tanggal_produksi) = \'' . $bulan . '\' AND YEAR(lhp.tanggal_produksi) = \'' . $year . '\' AND lhp.line = \'' . $line . '\'
                                          GROUP BY MONTH(lhp.tanggal_produksi)
                                      ) AS subquery
									                    GROUP BY subquery.bulan
                                      ORDER BY subquery.bulan
                                      ');
        }

        return $query->getResultArray();
    }

    public function get_data_cos_all_line($tanggal, $line)
    {
        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT subquery.tanggal_produksi, subquery.line, SUM(total_plate) / 6 AS total_plate, SUM(total_produksi) AS total_produksi
                                    FROM (
                                        SELECT lhp.tanggal_produksi, lhp.line, SUM(dlc.hasil) AS total_produksi, SUM(dlc.tersangkut) + SUM(dlc.terbakar) + SUM(dlc.lug_lepas) + SUM(dlc.strap_tipis) AS total_plate
                                        FROM detail_lhp_cos dlc
                                        JOIN lhp_cos lhp ON dlc.id_lhp_cos = lhp.id_lhp_cos
                                        WHERE lhp.tanggal_produksi = \'' . $tanggal . '\'
                                        AND lhp.line != 10
                                        GROUP BY lhp.tanggal_produksi, lhp.line
                                    ) AS subquery
                                    GROUP BY subquery.tanggal_produksi, subquery.line');
        } else {
            $query = $this->db->query('SELECT subquery.tanggal_produksi, subquery.line, SUM(total_plate) / 6 AS total_plate, SUM(total_produksi) AS total_produksi
                                    FROM (
                                        SELECT lhp.tanggal_produksi, lhp.line, SUM(dlc.hasil) AS total_produksi, SUM(dlc.tersangkut) + SUM(dlc.terbakar) + SUM(dlc.lug_lepas) + SUM(dlc.strap_tipis) AS total_plate
                                        FROM detail_lhp_cos dlc
                                        JOIN lhp_cos lhp ON dlc.id_lhp_cos = lhp.id_lhp_cos
                                        WHERE lhp.tanggal_produksi = \'' . $tanggal . '\' AND lhp.line = \'' . $line . '\'
                                        GROUP BY lhp.tanggal_produksi, lhp.line
                                    ) AS subquery
                                    GROUP BY subquery.tanggal_produksi, subquery.line');
        }
        

        return $query->getResultArray();
    }

    public function get_data_cos_all_line_by_month($bulan, $line)
    {
        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT subquery.bulan, subquery.line, SUM(total_plate) / 6 AS total_plate, SUM(total_produksi) AS total_produksi
                                    FROM (
                                        SELECT MONTH(lhp.tanggal_produksi) AS bulan, lhp.line, SUM(dlc.hasil) AS total_produksi, SUM(dlc.tersangkut) + SUM(dlc.terbakar) + SUM(dlc.lug_lepas) + SUM(dlc.strap_tipis) AS total_plate
                                        FROM detail_lhp_cos dlc
                                        JOIN lhp_cos lhp ON dlc.id_lhp_cos = lhp.id_lhp_cos
                                        WHERE MONTH(lhp.tanggal_produksi) = \''.$bulan.'\'
                                        AND lhp.line != 10
                                        GROUP BY lhp.tanggal_produksi, lhp.line
                                    ) AS subquery
                                    GROUP BY subquery.bulan, subquery.line
                                    ');
        } else {
            $query = $this->db->query('SELECT subquery.bulan, subquery.line, SUM(total_plate) / 6 AS total_plate, SUM(total_produksi) AS total_produksi
                                    FROM (
                                        SELECT MONTH(lhp.tanggal_produksi) AS bulan, lhp.line, SUM(dlc.hasil) AS total_produksi, SUM(dlc.tersangkut) + SUM(dlc.terbakar) + SUM(dlc.lug_lepas) + SUM(dlc.strap_tipis) AS total_plate
                                        FROM detail_lhp_cos dlc
                                        JOIN lhp_cos lhp ON dlc.id_lhp_cos = lhp.id_lhp_cos
                                        WHERE MONTH(lhp.tanggal_produksi) = \''.$bulan.'\' AND lhp.line = \'' . $line . '\'
                                        AND lhp.line != 10
                                        GROUP BY lhp.tanggal_produksi, lhp.line
                                    ) AS subquery
                                    GROUP BY subquery.bulan, subquery.line
                                    ');
        }

        return $query->getResultArray();
    }

    public function get_data_cos_all_line_by_date($tanggal, $line)
    {
        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT subquery.tanggal_produksi,
                                        SUM(total_plate) / 6 AS total_plate, SUM(total_produksi) AS total_produksi
                                        FROM (
                                            SELECT
                                                lhp.tanggal_produksi,
                                                SUM(dlc.hasil) AS total_produksi,
                                                SUM(dlc.tersangkut) + SUM(dlc.terbakar) + SUM(dlc.lug_lepas) + SUM(dlc.strap_tipis) AS total_plate
                                            FROM detail_lhp_cos dlc
                                            JOIN lhp_cos lhp ON dlc.id_lhp_cos = lhp.id_lhp_cos
                                            WHERE lhp.tanggal_produksi = \'' . $tanggal . '\'
                                            AND lhp.line != 10
                                            GROUP BY lhp.tanggal_produksi
                                        ) AS subquery
                                        GROUP BY subquery.tanggal_produksi');
        } else {
            $query = $this->db->query('SELECT subquery.tanggal_produksi,
                                        SUM(total_plate) / 6 AS total_plate, SUM(total_produksi) AS total_produksi
                                        FROM (
                                            SELECT
                                                lhp.tanggal_produksi,
                                                SUM(dlc.hasil) AS total_produksi,
                                                SUM(dlc.tersangkut) + SUM(dlc.terbakar) + SUM(dlc.lug_lepas) + SUM(dlc.strap_tipis) AS total_plate
                                            FROM detail_lhp_cos dlc
                                            JOIN lhp_cos lhp ON dlc.id_lhp_cos = lhp.id_lhp_cos
                                            WHERE lhp.tanggal_produksi = \'' . $tanggal . '\' AND lhp.line = \'' . $line . '\'
                                            GROUP BY lhp.tanggal_produksi
                                        ) AS subquery
                                        GROUP BY subquery.tanggal_produksi');
        }
        
        return $query->getResultArray();
    }

    public function get_data_total_cos_line_by_month($month) 
    {
        $bulan = idate('m', strtotime($month));
        $year = idate('Y', strtotime($month));
        $query = $this->db->query('SELECT subquery.line,
                                SUM(total_plate) / 6 AS total_plate, SUM(total_produksi) AS total_produksi
                                FROM (
                                    SELECT lhp.line, SUM(dlc.tersangkut) + SUM(dlc.terbakar) + SUM(dlc.lug_lepas) + SUM(dlc.strap_tipis) AS total_plate, SUM(dlc.hasil) AS total_produksi
                                    FROM detail_lhp_cos dlc
                                    JOIN lhp_cos lhp ON dlc.id_lhp_cos = lhp.id_lhp_cos
                                    WHERE MONTH(lhp.tanggal_produksi) = \'' . $bulan . '\' AND YEAR(lhp.tanggal_produksi) = \'' . $year . '\' AND lhp.line != 10
                                    GROUP BY lhp.line
                                ) AS subquery
                                GROUP BY subquery.line
                                ');

        return $query->getResultArray();
    }

    public function get_qty_jenis_cos($tanggal, $line) 
    {
        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT SUM(dlc.tersangkut) AS tersangkut, SUM(dlc.terbakar) AS terbakar, SUM(dlc.lug_lepas) AS lug_lepas, SUM(dlc.strap_tipis) AS strap_tipis
                                        FROM detail_lhp_cos dlc
                                        JOIN lhp_cos lhp ON lhp.id_lhp_cos = dlc.id_lhp_cos
                                        WHERE lhp.tanggal_produksi = \'' . $tanggal . '\'
                                        AND lhp.line != 10
                                    ');
        } else {
            $query = $this->db->query('SELECT SUM(dlc.tersangkut) AS tersangkut, SUM(dlc.terbakar) AS terbakar, SUM(dlc.lug_lepas) AS lug_lepas, SUM(dlc.strap_tipis) AS strap_tipis
                                        FROM detail_lhp_cos dlc
                                        JOIN lhp_cos lhp ON lhp.id_lhp_cos = dlc.id_lhp_cos
                                        WHERE lhp.tanggal_produksi = \'' . $tanggal . '\' AND lhp.line = \'' . $lhp . '\'
                                    ');
        }

        return $query->getResultArray();
    }

    public function get_data_qty_cos_by_month($bulan, $line) 
    {
        $month = date('m', strtotime($bulan));
        $year = date('Y', strtotime($bulan));
        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT SUM(dlc.tersangkut) AS tersangkut, SUM(dlc.terbakar) AS terbakar, SUM(dlc.lug_lepas) AS lug_lepas, SUM(dlc.strap_tipis) AS strap_tipis
                                        FROM detail_lhp_cos dlc
                                        JOIN lhp_cos lhp ON dlc.id_lhp_cos = lhp.id_lhp_cos
                                        WHERE MONTH(lhp.tanggal_produksi) = \'' . $month . '\' AND YEAR(lhp.tanggal_produksi) = \'' . $year . '\'
                                        AND lhp.line != 10
                                    ');
        } else {
            $query = $this->db->query('SELECT SUM(dlc.tersangkut) AS tersangkut, SUM(dlc.terbakar) AS terbakar, SUM(dlc.lug_lepas) AS lug_lepas, SUM(dlc.strap_tipis) AS strap_tipis
                                        FROM detail_lhp_cos dlc
                                        JOIN lhp_cos lhp ON dlc.id_lhp_cos = lhp.id_lhp_cos
                                        WHERE MONTH(lhp.tanggal_produksi) = \'' . $month . '\' AND YEAR(lhp.tanggal_produksi) = \'' . $year . '\' AND lhp.line = \'' . $line . '\'
                                    ');
        }

        return $query->getResultArray();
    }

    public function get_jenis_cos_by_date($tanggal, $line) 
    {
        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT dlc.type_battery, SUM(dlc.hasil) AS total_produksi, SUM(dlc.tersangkut) AS tersangkut, SUM(dlc.terbakar) AS terbakar, SUM(dlc.lug_lepas) AS lug_lepas, SUM(dlc.strap_tipis) AS strap_tipis
                                        FROM detail_lhp_cos dlc
                                        JOIN lhp_cos lhp ON dlc.id_lhp_cos = lhp.id_lhp_cos
                                        WHERE lhp.tanggal_produksi = \'' . $tanggal . '\'
                                        AND lhp.line != 10
										GROUP BY dlc.type_battery
                                    ');
        } else {
            $query = $this->db->query('SELECT dlc.type_battery, SUM(dlc.hasil) AS total_produksi, SUM(dlc.tersangkut) AS tersangkut, SUM(dlc.terbakar) AS terbakar, SUM(dlc.lug_lepas) AS lug_lepas, SUM(dlc.strap_tipis) AS strap_tipis
                                        FROM detail_lhp_cos dlc
                                        JOIN lhp_cos lhp ON dlc.id_lhp_cos = lhp.id_lhp_cos
                                        WHERE lhp.tanggal_produksi = \'' . $tanggal . '\' AND lhp.line =  \'' . $line . '\'
										GROUP BY dlc.type_battery
                                    ');
        }

        return $query->getResultArray();
    }

    public function get_jenis_cos_by_month($tanggal, $line) 
    {
        $month = date('m', strtotime($tanggal));
        $year = date('Y', strtotime($tanggal));
        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT dlc.type_battery, SUM(dlc.hasil) AS total_produksi, SUM(dlc.tersangkut) AS tersangkut, SUM(dlc.terbakar) AS terbakar, SUM(dlc.lug_lepas) AS lug_lepas, SUM(dlc.strap_tipis) AS strap_tipis
                                        FROM detail_lhp_cos dlc
                                        JOIN lhp_cos lhp ON dlc.id_lhp_cos = lhp.id_lhp_cos
                                        WHERE MONTH(lhp.tanggal_produksi) = \'' . $month . '\' AND YEAR(lhp.tanggal_produksi) = \'' . $year . '\'
                                        AND lhp.line != 10
										GROUP BY dlc.type_battery
                                    ');
        } else {
            $query = $this->db->query('SELECT dlc.type_battery, SUM(dlc.hasil) AS total_produksi, SUM(dlc.tersangkut) AS tersangkut, SUM(dlc.terbakar) AS terbakar, SUM(dlc.lug_lepas) AS lug_lepas, SUM(dlc.strap_tipis) AS strap_tipis
                                        FROM detail_lhp_cos dlc
                                        JOIN lhp_cos lhp ON dlc.id_lhp_cos = lhp.id_lhp_cos
                                        WHERE MONTH(lhp.tanggal_produksi) = \'' . $month . '\' AND YEAR(lhp.tanggal_produksi) = \'' . $year . '\' AND lhp.line = \'' . $line . '\'
										GROUP BY dlc.type_battery
                                    ');
        }

        return $query->getResultArray();
    }

    //MODAL REJECT PLATE CUTTING

    public function get_data_qty_reject_internal_by_month($bulan, $line)
    {
        $month = date('m', strtotime($bulan));
        $year = date('Y', strtotime($bulan));
        if ($line == null OR $line == 0) {
            $query = $this->db->query('SELECT SUM(pi.terpotong_panel) AS terpotong, SUM(pi.tersangkut_panel) AS tersangkut, SUM(pi.overbrush_panel) AS overbrush
                                        FROM plateinput pi
                                        JOIN platecutting p ON pi.id_platecutting = p.id
                                        WHERE MONTH(p.date) = \'' . $month . '\' AND YEAR(p.date) = \'' . $year . '\'
                                        AND p.line != 10
                                ');
        } else {
            $query = $this->db->query('SELECT SUM(pi.terpotong_panel) AS terpotong, SUM(pi.tersangkut_panel) AS tersangkut, SUM(pi.overbrush_panel) AS overbrush
                                        FROM plateinput pi
                                        JOIN platecutting p ON pi.id_platecutting = p.id
                                        WHERE MONTH(p.date) = \'' . $month . '\' AND YEAR(p.date) = \'' . $year . '\' AND p.line = \'' . $line . '\'
                                        AND p.line != 10
                                ');
        }
        
        return $query->getResultArray();
    }

    public function get_data_kg_reject_internal_by_month($bulan, $line)
    {
        $month = date('m', strtotime($bulan));
        $year = date('Y', strtotime($bulan));
        if ($line == null OR $line == 0) {
            $query = $this->db->query('SELECT SUM(pi.terpotong_kg) AS terpotong, SUM(pi.tersangkut_kg) AS tersangkut, SUM(pi.overbrush_kg) AS overbrush
                                        FROM plateinput pi
                                        JOIN platecutting p ON pi.id_platecutting = p.id
                                        WHERE MONTH(p.date) = \'' . $month . '\' AND YEAR(p.date) = \'' . $year . '\'
                                        AND p.line != 10
                                ');
        } else {
            $query = $this->db->query('SELECT SUM(pi.terpotong_kg) AS terpotong, SUM(pi.tersangkut_kg) AS tersangkut, SUM(pi.overbrush_kg) AS overbrush
                                        FROM plateinput pi
                                        JOIN platecutting p ON pi.id_platecutting = p.id
                                        WHERE MONTH(p.date) = \'' . $month . '\' AND YEAR(p.date) = \'' . $year . '\' AND p.line = \'' . $line . '\'
                                ');
        }
        
        return $query->getResultArray();
    }

    public function get_data_qty_reject_eksternal_by_month($bulan, $line)
    {
        $month = date('m', strtotime($bulan));
        $year = date('Y', strtotime($bulan));
        if ($line == null OR $line == 0) {
            $query = $this->db->query('SELECT SUM(pi.rontok_panel) AS rontok, SUM(pi.lug_patah_panel) AS lug_patah, SUM(pi.patah_kaki_panel) AS patah_kaki, SUM(pi.patah_frame_panel) AS patah_frame, SUM(pi.bolong_panel) AS bolong, SUM(pi.bending_panel) AS bending, SUM(pi.lengket_terpotong_panel) AS lengket_terpotong
                                        FROM plateinput pi
                                        JOIN platecutting p ON pi.id_platecutting = p.id
                                        WHERE MONTH(p.date) = \'' . $month . '\' AND YEAR(p.date) = \'' . $year . '\'
                                        AND p.line != 10
                                ');
        } else {
            $query = $this->db->query('SELECT SUM(pi.rontok_panel) AS rontok, SUM(pi.lug_patah_panel) AS lug_patah, SUM(pi.patah_kaki_panel) AS patah_kaki, SUM(pi.patah_frame_panel) AS patah_frame, SUM(pi.bolong_panel) AS bolong, SUM(pi.bending_panel) AS bending, SUM(pi.lengket_terpotong_panel) AS lengket_terpotong
                                        FROM plateinput pi
                                        JOIN platecutting p ON pi.id_platecutting = p.id
                                        WHERE MONTH(p.date) = \'' . $month . '\' AND YEAR(p.date) = \'' . $year . '\' AND p.line = \'' . $line . '\'
                                ');
        }
        
        return $query->getResultArray();
    }

    public function get_data_kg_reject_eksternal_by_month($bulan, $line)
    {
        $month = date('m', strtotime($bulan));
        $year = date('Y', strtotime($bulan));
        if ($line == null OR $line == 0) {
            $query = $this->db->query('SELECT SUM(pi.rontok_kg) AS rontok, SUM(pi.lug_patah_kg) AS lug_patah, SUM(pi.patah_kaki_kg) AS patah_kaki, SUM(pi.patah_frame_kg) AS patah_frame, SUM(pi.bolong_kg) AS bolong, SUM(pi.bending_kg) AS bending, SUM(pi.lengket_terpotong_kg) AS lengket_terpotong
                                        FROM plateinput pi
                                        JOIN platecutting p ON pi.id_platecutting = p.id
                                        WHERE MONTH(p.date) = \'' . $month . '\' AND YEAR(p.date) = \'' . $year . '\'
                                        AND p.line != 10
                                ');
        } else {
            $query = $this->db->query('SELECT SUM(pi.rontok_kg) AS rontok, SUM(pi.lug_patah_kg) AS lug_patah, SUM(pi.patah_kaki_kg) AS patah_kaki, SUM(pi.patah_frame_kg) AS patah_frame, SUM(pi.bolong_kg) AS bolong, SUM(pi.bending_kg) AS bending, SUM(pi.lengket_terpotong_kg) AS lengket_terpotong
                                        FROM plateinput pi
                                        JOIN platecutting p ON pi.id_platecutting = p.id
                                        WHERE MONTH(p.date) = \'' . $month . '\' AND YEAR(p.date) = \'' . $year . '\' AND p.line = \'' . $line . '\'
                                ');
        }
        
        return $query->getResultArray();
    }

    public function get_data_total_reject_line_by_month($month) 
    {
        $bulan = idate('m', strtotime($month));
        $year = idate('Y', strtotime($month));
        $query = $this->db->query('SELECT subquery.line, ((SUM(total_reject) / SUM(total_produksi)) * 100) AS persen, SUM(kg) AS kg
                                FROM (
                                    SELECT p.line, SUM(pi.terpotong_panel) + SUM(pi.tersangkut_panel) + SUM(pi.overbrush_panel) + SUM(pi.rontok_panel) + SUM(pi.lug_patah_panel) + SUM(pi.patah_kaki_panel) + SUM(pi.patah_frame_panel) + SUM(pi.bolong_panel) + SUM(pi.bending_panel) + SUM(pi.lengket_terpotong_panel) AS total_reject, SUM(pi.hasil_produksi) AS total_produksi, SUM(pi.terpotong_kg) + SUM(pi.tersangkut_kg) + SUM(pi.overbrush_kg) + SUM(pi.rontok_kg) + SUM(pi.lug_patah_kg) + SUM(pi.patah_kaki_kg) + SUM(pi.patah_frame_kg) + SUM(pi.bolong_kg) + SUM(pi.bending_kg) + SUM(pi.lengket_terpotong_kg) AS kg
                                    FROM plateinput pi
                                    JOIN platecutting p ON pi.id_platecutting = p.id
                                    WHERE MONTH(p.date) = \'' . $bulan . '\' AND YEAR(p.date) = \'' . $year . '\' AND p.line != 10
                                    GROUP BY p.line
                                ) AS subquery
                                GROUP BY subquery.line
                                HAVING SUM(total_produksi) > 0
                                ORDER BY ((SUM(total_reject) / SUM(total_produksi)) * 100) DESC
                                ');

        return $query->getResultArray();
    }

    public function get_data_rejection_by_month($bulan, $month, $line)
    {
        $year = date('Y', strtotime($month));
        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT subquery.bulan, SUM(total_reject) AS total_reject, SUM(total_produksi) AS total_produksi
                                      FROM (
                                          SELECT MONTH(p.date) AS bulan, SUM(pi.hasil_produksi) AS total_produksi, SUM(pi.terpotong_panel) + SUM(pi.tersangkut_panel) + SUM(pi.overbrush_panel) + SUM(pi.rontok_panel) + SUM(pi.lug_patah_panel) + SUM(pi.patah_kaki_panel) + SUM(pi.patah_frame_panel) + SUM(pi.bolong_panel) + SUM(pi.bending_panel) + SUM(pi.lengket_terpotong_panel) AS total_reject
                                          FROM plateinput pi
                                          JOIN platecutting p ON pi.id_platecutting = p.id
                                          WHERE MONTH(p.date) = \'' . $bulan . '\' AND YEAR(p.date) = \'' . $year . '\'
                                          AND p.line != 10
                                          GROUP BY MONTH(p.date)
                                      ) AS subquery
									                    GROUP BY subquery.bulan
                                    ');
        } else {
            $query = $this->db->query('SELECT subquery.bulan, SUM(total_reject) AS total_reject, SUM(total_produksi) AS total_produksi
                                      FROM (
                                          SELECT MONTH(p.date) AS bulan, SUM(pi.hasil_produksi) AS total_produksi, SUM(pi.terpotong_panel) + SUM(pi.tersangkut_panel) + SUM(pi.overbrush_panel) + SUM(pi.rontok_panel) + SUM(pi.lug_patah_panel) + SUM(pi.patah_kaki_panel) + SUM(pi.patah_frame_panel) + SUM(pi.bolong_panel) + SUM(pi.bending_panel) + SUM(pi.lengket_terpotong_panel) AS total_reject
                                          FROM plateinput pi
                                          JOIN platecutting p ON pi.id_platecutting = p.id
                                          WHERE MONTH(p.date) = \'' . $bulan . '\' AND YEAR(p.date) = \'' . $year . '\' AND p.line = \'' . $line . '\'
                                          GROUP BY MONTH(p.date)
                                      ) AS subquery
									                    GROUP BY subquery.bulan
                                    ');
        }

        return $query->getResultArray();
    }

    public function get_year_to_date_rejection($month, $line)
    {
        $tahun = date('Y', strtotime($month));

        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT subquery.tahun, SUM(total_reject) AS total_reject, SUM(total_produksi) AS total_produksi
                                      FROM (
                                          SELECT YEAR(p.date) AS tahun, SUM(pi.hasil_produksi) AS total_produksi, SUM(pi.terpotong_panel) + SUM(pi.tersangkut_panel) + SUM(pi.overbrush_panel) + SUM(pi.rontok_panel) + SUM(pi.lug_patah_panel) + SUM(pi.patah_kaki_panel) + SUM(pi.patah_frame_panel) + SUM(pi.bolong_panel) + SUM(pi.bending_panel) + SUM(pi.lengket_terpotong_panel) AS total_reject
                                          FROM plateinput pi
                                          JOIN platecutting p ON pi.id_platecutting = p.id
                                          WHERE YEAR(p.date) = \'' . $tahun . '\'
                                          AND p.line != 10
                                          GROUP BY YEAR(p.date)
                                      ) AS subquery
                                      GROUP BY subquery.tahun
                                      ORDER BY subquery.tahun
                                      ');
        } else {
            $query = $this->db->query('SELECT subquery.tahun, SUM(total_reject) AS total_reject, SUM(total_produksi) AS total_produksi
                                      FROM (
                                          SELECT YEAR(p.date) AS tahun, SUM(pi.hasil_produksi) AS total_produksi, SUM(pi.terpotong_panel) + SUM(pi.tersangkut_panel) + SUM(pi.overbrush_panel) + SUM(pi.rontok_panel) + SUM(pi.lug_patah_panel) + SUM(pi.patah_kaki_panel) + SUM(pi.patah_frame_panel) + SUM(pi.bolong_panel) + SUM(pi.bending_panel) + SUM(pi.lengket_terpotong_panel) AS total_reject
                                          FROM plateinput pi
                                          JOIN platecutting p ON pi.id_platecutting = p.id
                                          WHERE YEAR(p.date) = \'' . $tahun . '\' AND p.line = \'' . $line . '\'
                                          GROUP BY YEAR(p.date)
                                      ) AS subquery
                                      GROUP BY subquery.tahun
                                      ORDER BY subquery.tahun
                                      ');
        }

        return $query->getResultArray();
    }

    public function get_data_reject_all_line_by_date($tanggal, $line)
    {
        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT subquery.date,
                                        COALESCE(SUM(total_reject_panel_internal) / NULLIF(SUM(total_produksi), 0), 0) AS persentase_internal,
                                        COALESCE(SUM(total_reject_panel_eksternal) / NULLIF(SUM(total_produksi), 0), 0) AS persentase_eksternal,
                                        SUM(total_reject_kg) AS kg
                                        FROM (
                                            SELECT
                                                p.date,
                                                SUM(pi.hasil_produksi) AS total_produksi,
                                                SUM(pi.terpotong_panel) + SUM(pi.tersangkut_panel) + SUM(pi.overbrush_panel) AS total_reject_panel_internal,
                                                SUM(pi.rontok_panel) + SUM(pi.lug_patah_panel) + SUM(pi.patah_kaki_panel) + SUM(pi.patah_frame_panel) + SUM(pi.bolong_panel) + SUM(pi.bending_panel) + SUM(pi.lengket_terpotong_panel) AS total_reject_panel_eksternal,
                                                SUM(pi.terpotong_kg) + SUM(pi.tersangkut_kg) + SUM(pi.overbrush_kg) + SUM(pi.rontok_kg) + SUM(pi.lug_patah_kg) + SUM(pi.patah_kaki_kg) + SUM(pi.patah_frame_kg) + SUM(pi.bolong_kg) + SUM(pi.bending_kg) + SUM(pi.lengket_terpotong_kg) AS total_reject_kg
                                            FROM plateinput pi
                                            JOIN platecutting p ON pi.id_platecutting = p.id
                                            WHERE p.date = \'' . $tanggal . '\'
                                            AND p.line != 10
                                            GROUP BY p.date
                                        ) AS subquery
                                        GROUP BY subquery.date');
                                      
        } else {
            $query = $this->db->query('SELECT subquery.date,
                                        COALESCE(SUM(total_reject_panel_internal) / NULLIF(SUM(total_produksi), 0), 0) AS persentase_internal,
                                        COALESCE(SUM(total_reject_panel_eksternal) / NULLIF(SUM(total_produksi), 0), 0) AS persentase_eksternal,
                                        -- COALESCE(SUM(total_reject_panel) / NULLIF(SUM(total_produksi), 0), 0) AS persentase,
                                        SUM(total_reject_kg) AS kg
                                        FROM (
                                            SELECT
                                                p.date,
                                                SUM(pi.hasil_produksi) AS total_produksi,
                                                SUM(pi.terpotong_panel) + SUM(pi.tersangkut_panel) + SUM(pi.overbrush_panel) AS total_reject_panel_internal,
                                                SUM(pi.rontok_panel) + SUM(pi.lug_patah_panel) + SUM(pi.patah_kaki_panel) + SUM(pi.patah_frame_panel) + SUM(pi.bolong_panel) + SUM(pi.bending_panel) + SUM(pi.lengket_terpotong_panel) AS total_reject_panel_eksternal,
                                                SUM(pi.terpotong_kg) + SUM(pi.tersangkut_kg) + SUM(pi.overbrush_kg) + SUM(pi.rontok_kg) + SUM(pi.lug_patah_kg) + SUM(pi.patah_kaki_kg) + SUM(pi.patah_frame_kg) + SUM(pi.bolong_kg) + SUM(pi.bending_kg) + SUM(pi.lengket_terpotong_kg) AS total_reject_kg
                                                -- SUM(pi.hasil_produksi) AS total_produksi,
                                                -- SUM(pi.terpotong_panel) + SUM(pi.tersangkut_panel) + SUM(pi.overbrush_panel) + SUM(pi.rontok_panel) + SUM(pi.lug_patah_panel) + SUM(pi.patah_kaki_panel) + SUM(pi.patah_frame_panel) + SUM(pi.bolong_panel) + SUM(pi.bending_panel) + SUM(pi.lengket_terpotong_panel) AS total_reject_panel,
                                                -- SUM(pi.terpotong_kg) + SUM(pi.tersangkut_kg) + SUM(pi.overbrush_kg) + SUM(pi.rontok_kg) + SUM(pi.lug_patah_kg) + SUM(pi.patah_kaki_kg) + SUM(pi.patah_frame_kg) + SUM(pi.bolong_kg) + SUM(pi.bending_kg) + SUM(pi.lengket_terpotong_kg) AS total_reject_kg
                                            FROM plateinput pi
                                            JOIN platecutting p ON pi.id_platecutting = p.id
                                            WHERE p.date = \'' . $tanggal . '\' AND line = \'' . $line . '\'
                                            AND p.line != 10
                                            GROUP BY p.date
                                        ) AS subquery
                                        GROUP BY subquery.date');
        }
        
        return $query->getResultArray();
    }

    public function get_data_average_reject_by_month($bulan, $month, $line)
    {
        $year = date('Y', strtotime($month));
        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT subquery.bulan, SUM(total_reject_panel_internal)/SUM(total_produksi) AS persentase_internal, SUM(total_reject_panel_eksternal)/SUM(total_produksi) AS persentase_eksternal,  SUM(total_reject_kg) AS kg
                                      FROM (
                                          SELECT MONTH(p.date) AS bulan,
                                                SUM(pi.hasil_produksi) AS total_produksi,
                                                SUM(pi.terpotong_panel) + SUM(pi.tersangkut_panel) + SUM(pi.overbrush_panel) AS total_reject_panel_internal,
                                                SUM(pi.rontok_panel) + SUM(pi.lug_patah_panel) + SUM(pi.patah_kaki_panel) + SUM(pi.patah_frame_panel) + SUM(pi.bolong_panel) + SUM(pi.bending_panel) + SUM(pi.lengket_terpotong_panel) AS total_reject_panel_eksternal,
                                                SUM(pi.terpotong_kg) + SUM(pi.tersangkut_kg) + SUM(pi.overbrush_kg) + SUM(pi.rontok_kg) + SUM(pi.lug_patah_kg) + SUM(pi.patah_kaki_kg) + SUM(pi.patah_frame_kg) + SUM(pi.bolong_kg) + SUM(pi.bending_kg) + SUM(pi.lengket_terpotong_kg) AS total_reject_kg
                                          FROM plateinput pi
                                          JOIN platecutting p ON pi.id_platecutting = p.id
                                          WHERE MONTH(p.date) = \'' . $bulan . '\' AND YEAR(p.date) = \'' . $year . '\'
                                          AND p.line != 10
                                          GROUP BY MONTH(p.date)
                                      ) AS subquery
									                    GROUP BY subquery.bulan
                                      ORDER BY subquery.bulan
                                      ');
        } else {
            $query = $this->db->query('SELECT subquery.bulan, SUM(total_reject_panel_internal)/SUM(total_produksi) AS persentase_internal, SUM(total_reject_panel_eksternal)/SUM(total_produksi) AS persentase_eksternal,  SUM(total_reject_kg) AS kg
                                      --   SELECT subquery.bulan, SUM(total_reject_panel)/SUM(total_produksi) AS persentase,  SUM(total_reject_kg) AS kg
                                      FROM (
                                          SELECT MONTH(p.date) AS bulan,
                                          SUM(pi.hasil_produksi) AS total_produksi,
                                          SUM(pi.terpotong_panel) + SUM(pi.tersangkut_panel) + SUM(pi.overbrush_panel) AS total_reject_panel_internal,
                                          SUM(pi.rontok_panel) + SUM(pi.lug_patah_panel) + SUM(pi.patah_kaki_panel) + SUM(pi.patah_frame_panel) + SUM(pi.bolong_panel) + SUM(pi.bending_panel) + SUM(pi.lengket_terpotong_panel) AS total_reject_panel_eksternal,
                                          SUM(pi.terpotong_kg) + SUM(pi.tersangkut_kg) + SUM(pi.overbrush_kg) + SUM(pi.rontok_kg) + SUM(pi.lug_patah_kg) + SUM(pi.patah_kaki_kg) + SUM(pi.patah_frame_kg) + SUM(pi.bolong_kg) + SUM(pi.bending_kg) + SUM(pi.lengket_terpotong_kg) AS total_reject_kg
                                        --   SUM(pi.terpotong_panel) + SUM(pi.tersangkut_panel) + SUM(pi.overbrush_panel) + SUM(pi.rontok_panel) + SUM(pi.lug_patah_panel) + SUM(pi.patah_kaki_panel) + SUM(pi.patah_frame_panel) + SUM(pi.bolong_panel) + SUM(pi.bending_panel) + SUM(pi.lengket_terpotong_panel) AS total_reject_panel, SUM(pi.terpotong_kg) + SUM(pi.tersangkut_kg) + SUM(pi.overbrush_kg) + SUM(pi.rontok_kg) + SUM(pi.lug_patah_kg) + SUM(pi.patah_kaki_kg) + SUM(pi.patah_frame_kg) + SUM(pi.bolong_kg) + SUM(pi.bending_kg) + SUM(pi.lengket_terpotong_kg) AS total_reject_kg
                                          FROM plateinput pi
                                          JOIN platecutting p ON pi.id_platecutting = p.id
                                          WHERE MONTH(p.date) = \'' . $bulan . '\' AND YEAR(p.date) = \'' . $year . '\' AND p.line = \'' . $line . '\'
                                          GROUP BY MONTH(p.date)
                                      ) AS subquery
									                    GROUP BY subquery.bulan
                                      ORDER BY subquery.bulan
                                      ');
        }

        return $query->getResultArray();
    }

    public function get_data_reject_all_line($tanggal, $line)
    {
        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT subquery.date, subquery.line, SUM(total_reject) AS total_reject, SUM(total_produksi) AS total_produksi
                                    FROM (
                                        SELECT p.date, p.line, SUM(pi.hasil_produksi) AS total_produksi, SUM(pi.terpotong_panel) + SUM(pi.tersangkut_panel) + SUM(pi.overbrush_panel) + SUM(pi.rontok_panel) + SUM(pi.lug_patah_panel) + SUM(pi.patah_kaki_panel) + SUM(pi.patah_frame_panel) + SUM(pi.bolong_panel) + SUM(pi.bending_panel) + SUM(pi.lengket_terpotong_panel) AS total_reject
                                        FROM plateinput pi
                                        JOIN platecutting p ON pi.id_platecutting = p.id
                                        WHERE p.date = \''.$tanggal.'\'
                                        AND p.line != 10
                                        GROUP BY p.date, p.line
                                    ) AS subquery
                                    GROUP BY subquery.date, subquery.line');
        } else {
            $query = $this->db->query('SELECT subquery.date, subquery.line, SUM(total_reject) AS total_reject, SUM(total_produksi) AS total_produksi
                                    FROM (
                                        SELECT p.date, p.line, SUM(pi.hasil_produksi) AS total_produksi, SUM(pi.terpotong_panel) + SUM(pi.tersangkut_panel) + SUM(pi.overbrush_panel) + SUM(pi.rontok_panel) + SUM(pi.lug_patah_panel) + SUM(pi.patah_kaki_panel) + SUM(pi.patah_frame_panel) + SUM(pi.bolong_panel) + SUM(pi.bending_panel) + SUM(pi.lengket_terpotong_panel) AS total_reject
                                        FROM plateinput pi
                                        JOIN platecutting p ON pi.id_platecutting = p.id
                                        WHERE p.date = \''.$tanggal.'\' AND line = '.$line.'
                                        GROUP BY p.date, p.line
                                    ) AS subquery
                                    GROUP BY subquery.date, subquery.line');
        }
        

        return $query->getResultArray();
    }

    public function get_data_reject_all_line_by_month($bulan, $line)
    {
        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT subquery.bulan, subquery.line, SUM(total_reject) AS total_reject, SUM(total_produksi) AS total_produksi
                                    FROM (
                                        SELECT MONTH(p.date) AS bulan, p.line, SUM(pi.hasil_produksi) AS total_produksi, SUM(pi.terpotong_panel) + SUM(pi.tersangkut_panel) + SUM(pi.overbrush_panel) + SUM(pi.rontok_panel) + SUM(pi.lug_patah_panel) + SUM(pi.patah_kaki_panel) + SUM(pi.patah_frame_panel) + SUM(pi.bolong_panel) + SUM(pi.bending_panel) + SUM(pi.lengket_terpotong_panel) AS total_reject
                                        FROM plateinput pi
                                        JOIN platecutting p ON pi.id_platecutting = p.id
                                        WHERE MONTH(p.date) = \''.$bulan.'\'
                                        AND p.line != 10
                                        GROUP BY p.date, p.line
                                    ) AS subquery
                                    GROUP BY subquery.bulan, subquery.line
                                    ');
        } else {
            $query = $this->db->query('SELECT subquery.bulan, subquery.line, SUM(total_reject) AS total_reject, SUM(total_produksi) AS total_produksi
                                    FROM (
                                        SELECT MONTH(p.date) AS bulan, p.line, SUM(pi.hasil_produksi) AS total_produksi, SUM(pi.terpotong_panel) + SUM(pi.tersangkut_panel) + SUM(pi.overbrush_panel) + SUM(pi.rontok_panel) + SUM(pi.lug_patah_panel) + SUM(pi.patah_kaki_panel) + SUM(pi.patah_frame_panel) + SUM(pi.bolong_panel) + SUM(pi.bending_panel) + SUM(pi.lengket_terpotong_panel) AS total_reject
                                        FROM plateinput pi
                                        JOIN platecutting p ON pi.id_platecutting = p.id
                                        WHERE MONTH(p.date) = \''.$bulan.'\' AND line = '.$line.'
                                        GROUP BY p.date, p.line
                                    ) AS subquery
                                    GROUP BY subquery.bulan, subquery.line
                                    ');
        }

        return $query->getResultArray();
    }

    public function get_qty_jenis_reject_internal($tanggal, $line) 
    {
        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT SUM(pi.terpotong_panel) AS terpotong, SUM(pi.tersangkut_panel) AS tersangkut, SUM(pi.overbrush_panel) AS overbrush
                                        FROM plateinput pi
                                        JOIN platecutting p ON pi.id_platecutting = p.id
                                        WHERE p.date = \'' . $tanggal . '\'
                                        AND p.line != 10
                                    ');
        } else {
            $query = $this->db->query('SELECT SUM(pi.terpotong_panel) AS terpotong, SUM(pi.tersangkut_panel) AS tersangkut, SUM(pi.overbrush_panel) AS overbrush
                                        FROM plateinput pi
                                        JOIN platecutting p ON pi.id_platecutting = p.id
                                        WHERE p.date = \'' . $tanggal . '\' AND p.line = \'' . $line . '\'
                                    ');
        }

        return $query->getResultArray();
    }

    public function get_kg_jenis_reject_internal($tanggal, $line) 
    {
        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT SUM(pi.terpotong_kg) AS terpotong, SUM(pi.tersangkut_kg) AS tersangkut, SUM(pi.overbrush_kg) AS overbrush
                                        FROM plateinput pi
                                        JOIN platecutting p ON pi.id_platecutting = p.id
                                        WHERE p.date = \'' . $tanggal . '\'
                                        AND p.line != 10
                                    ');
        } else {
            $query = $this->db->query('SELECT SUM(pi.terpotong_kg) AS terpotong, SUM(pi.tersangkut_kg) AS tersangkut, SUM(pi.overbrush_kg) AS overbrush
                                        FROM plateinput pi
                                        JOIN platecutting p ON pi.id_platecutting = p.id
                                        WHERE p.date = \'' . $tanggal . '\' AND p.line = \'' . $line . '\'
                                    ');
        }

        return $query->getResultArray();
    }

    public function get_qty_jenis_reject_eksternal($tanggal, $line) 
    {
        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT SUM(pi.rontok_panel) AS rontok, SUM(pi.lug_patah_panel) AS lug_patah, SUM(pi.patah_kaki_panel) AS patah_kaki, SUM(pi.patah_frame_panel) AS patah_frame, SUM(pi.bolong_panel) AS bolong, SUM(pi.bending_panel) AS bending, SUM(pi.lengket_terpotong_panel) AS lengket_terpotong
                                        FROM plateinput pi
                                        JOIN platecutting p ON pi.id_platecutting = p.id
                                        WHERE p.date = \'' . $tanggal . '\'
                                        AND p.line != 10
                                    ');
        } else {
            $query = $this->db->query('SELECT SUM(pi.rontok_panel) AS rontok, SUM(pi.lug_patah_panel) AS lug_patah, SUM(pi.patah_kaki_panel) AS patah_kaki, SUM(pi.patah_frame_panel) AS patah_frame, SUM(pi.bolong_panel) AS bolong, SUM(pi.bending_panel) AS bending, SUM(pi.lengket_terpotong_panel) AS lengket_terpotong
                                        FROM plateinput pi
                                        JOIN platecutting p ON pi.id_platecutting = p.id
                                        WHERE p.date = \'' . $tanggal . '\' AND p.line = \'' . $line . '\'
                                    ');
        }

        return $query->getResultArray();
    }

    public function get_kg_jenis_reject_eksternal($tanggal, $line) 
    {
        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT SUM(pi.rontok_kg) AS rontok, SUM(pi.lug_patah_kg) AS lug_patah, SUM(pi.patah_kaki_kg) AS patah_kaki, SUM(pi.patah_frame_kg) AS patah_frame, SUM(pi.bolong_kg) AS bolong, SUM(pi.bending_kg) AS bending, SUM(pi.lengket_terpotong_kg) AS lengket_terpotong
                                        FROM plateinput pi
                                        JOIN platecutting p ON pi.id_platecutting = p.id
                                        WHERE p.date = \'' . $tanggal . '\'
                                        AND p.line != 10
                                    ');
        } else {
            $query = $this->db->query('SELECT SUM(pi.rontok_kg) AS rontok, SUM(pi.lug_patah_kg) AS lug_patah, SUM(pi.patah_kaki_kg) AS patah_kaki, SUM(pi.patah_frame_kg) AS patah_frame, SUM(pi.bolong_kg) AS bolong, SUM(pi.bending_kg) AS bending, SUM(pi.lengket_terpotong_kg) AS lengket_terpotong
                                        FROM plateinput pi
                                        JOIN platecutting p ON pi.id_platecutting = p.id
                                        WHERE p.date = \'' . $tanggal . '\' AND p.line = \'' . $line . '\'
                                    ');
        }

        return $query->getResultArray();
    }

    public function get_jenis_reject_by_date($tanggal, $line) 
    {
        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT pi.plate, SUM(pi.hasil_produksi) AS total_produksi, SUM(pi.terpotong_panel) AS terpotong_panel, SUM(pi.tersangkut_panel) AS tersangkut_panel, SUM(pi.overbrush_panel) AS overbrush_panel, SUM(pi.rontok_panel) AS rontok_panel, SUM(pi.lug_patah_panel) AS lug_patah_panel, SUM(pi.patah_kaki_panel) AS patah_kaki_panel, SUM(pi.patah_frame_panel) AS patah_frame_panel, SUM(pi.bolong_panel) AS bolong_panel, SUM(pi.bending_panel) AS bending_panel, SUM(pi.lengket_terpotong_panel) AS lengket_terpotong_panel, SUM(pi.terpotong_kg) AS terpotong_kg, SUM(pi.tersangkut_kg) AS tersangkut_kg, SUM(pi.overbrush_kg) AS overbrush_kg, SUM(pi.rontok_kg) AS rontok_kg, SUM(pi.lug_patah_kg) AS lug_patah_kg, SUM(pi.patah_kaki_kg) AS patah_kaki_kg, SUM(pi.patah_frame_kg) AS patah_frame_kg, SUM(pi.bolong_kg) AS bolong_kg, SUM(pi.bending_kg) AS bending_kg, SUM(pi.lengket_terpotong_kg) AS lengket_terpotong_kg
                                        FROM plateinput pi
                                        JOIN platecutting p ON pi.id_platecutting = p.id
                                        WHERE p.date = \'' . $tanggal . '\'
                                        AND p.line != 10
										GROUP BY pi.plate
                                    ');
        } else {
            $query = $this->db->query('SELECT pi.plate, SUM(pi.hasil_produksi) AS total_produksi, SUM(pi.terpotong_panel) AS terpotong_panel, SUM(pi.tersangkut_panel) AS tersangkut_panel, SUM(pi.overbrush_panel) AS overbrush_panel, SUM(pi.rontok_panel) AS rontok_panel, SUM(pi.lug_patah_panel) AS lug_patah_panel, SUM(pi.patah_kaki_panel) AS patah_kaki_panel, SUM(pi.patah_frame_panel) AS patah_frame_panel, SUM(pi.bolong_panel) AS bolong_panel, SUM(pi.bending_panel) AS bending_panel, SUM(pi.lengket_terpotong_panel) AS lengket_terpotong_panel, SUM(pi.terpotong_kg) AS terpotong_kg, SUM(pi.tersangkut_kg) AS tersangkut_kg, SUM(pi.overbrush_kg) AS overbrush_kg, SUM(pi.rontok_kg) AS rontok_kg, SUM(pi.lug_patah_kg) AS lug_patah_kg, SUM(pi.patah_kaki_kg) AS patah_kaki_kg, SUM(pi.patah_frame_kg) AS patah_frame_kg, SUM(pi.bolong_kg) AS bolong_kg, SUM(pi.bending_kg) AS bending_kg, SUM(pi.lengket_terpotong_kg) AS lengket_terpotong_kg
                                        FROM plateinput pi
                                        JOIN platecutting p ON pi.id_platecutting = p.id
                                        WHERE p.date = \'' . $tanggal . '\' AND p.line = \'' . $line . '\'
										GROUP BY pi.plate
                                    ');
        }

        return $query->getResultArray();
    }

    public function get_jenis_reject_by_month($tanggal, $line) 
    {
        $month = date('m', strtotime($tanggal));
        $year = date('Y', strtotime($tanggal));
        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT pi.plate, SUM(pi.hasil_produksi) AS total_produksi, SUM(pi.terpotong_panel) AS terpotong_panel, SUM(pi.tersangkut_panel) AS tersangkut_panel, SUM(pi.overbrush_panel) AS overbrush_panel, SUM(pi.rontok_panel) AS rontok_panel, SUM(pi.lug_patah_panel) AS lug_patah_panel, SUM(pi.patah_kaki_panel) AS patah_kaki_panel, SUM(pi.patah_frame_panel) AS patah_frame_panel, SUM(pi.bolong_panel) AS bolong_panel, SUM(pi.bending_panel) AS bending_panel, SUM(pi.lengket_terpotong_panel) AS lengket_terpotong_panel, SUM(pi.terpotong_kg) AS terpotong_kg, SUM(pi.tersangkut_kg) AS tersangkut_kg, SUM(pi.overbrush_kg) AS overbrush_kg, SUM(pi.rontok_kg) AS rontok_kg, SUM(pi.lug_patah_kg) AS lug_patah_kg, SUM(pi.patah_kaki_kg) AS patah_kaki_kg, SUM(pi.patah_frame_kg) AS patah_frame_kg, SUM(pi.bolong_kg) AS bolong_kg, SUM(pi.bending_kg) AS bending_kg, SUM(pi.lengket_terpotong_kg) AS lengket_terpotong_kg
                                        FROM plateinput pi
                                        JOIN platecutting p ON pi.id_platecutting = p.id
                                        WHERE MONTH(p.date) = \'' . $month . '\' AND YEAR(p.date) = \'' . $year . '\'
                                        AND p.line != 10
										GROUP BY pi.plate
                                    ');
        } else {
            $query = $this->db->query('SELECT pi.plate, SUM(pi.hasil_produksi) AS total_produksi, SUM(pi.terpotong_panel) AS terpotong_panel, SUM(pi.tersangkut_panel) AS tersangkut_panel, SUM(pi.overbrush_panel) AS overbrush_panel, SUM(pi.rontok_panel) AS rontok_panel, SUM(pi.lug_patah_panel) AS lug_patah_panel, SUM(pi.patah_kaki_panel) AS patah_kaki_panel, SUM(pi.patah_frame_panel) AS patah_frame_panel, SUM(pi.bolong_panel) AS bolong_panel, SUM(pi.bending_panel) AS bending_panel, SUM(pi.lengket_terpotong_panel) AS lengket_terpotong_panel, SUM(pi.terpotong_kg) AS terpotong_kg, SUM(pi.tersangkut_kg) AS tersangkut_kg, SUM(pi.overbrush_kg) AS overbrush_kg, SUM(pi.rontok_kg) AS rontok_kg, SUM(pi.lug_patah_kg) AS lug_patah_kg, SUM(pi.patah_kaki_kg) AS patah_kaki_kg, SUM(pi.patah_frame_kg) AS patah_frame_kg, SUM(pi.bolong_kg) AS bolong_kg, SUM(pi.bending_kg) AS bending_kg, SUM(pi.lengket_terpotong_kg) AS lengket_terpotong_kg
                                        FROM plateinput pi
                                        JOIN platecutting p ON pi.id_platecutting = p.id
                                        WHERE MONTH(p.date) = \'' . $month . '\' AND YEAR(p.date) = \'' . $year . '\' AND p.line = \'' . $line . '\'
										GROUP BY pi.plate
                                    ');
        }

        return $query->getResultArray();
    }
}