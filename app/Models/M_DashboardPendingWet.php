<?php 
namespace App\Models;
use CodeIgniter\Model;



class M_DashboardPendingWet extends Model
{
    public function __construct()
    {
        $this->db = \Config\Database::connect();

        $this->session = \Config\Services::session();
    }

    // public function get_data_reject_by_month($bulan) 
    // {
    //     $query = $this->db->query('SELECT MONTH(lhp_produksi2.tanggal_produksi), detail_pending_wet_finishing.jenis_pending, SUM(detail_pending_wet_finishing.qty_pending) as qty
    //                                 FROM detail_pending_wet_finishing
    //                                 RIGHT JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_pending_wet_finishing.id_lhp
    //                                 WHERE MONTH(lhp_produksi2.tanggal_produksi) = '.$bulan.'
    //                                 GROUP BY detail_pending_wet_finishing.jenis_pending, MONTH(lhp_produksi2.tanggal_produksi)
    //                                 ORDER BY MONTH(lhp_produksi2.tanggal_produksi)
    //                             ');
    //     return $query->getResultArray();
    // }

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

    public function get_data_reject_by_month($month, $line)
    {
        $bulan = idate('m', strtotime($month));
        $year = date('Y', strtotime($month));
        if ($line == null OR $line == 0) {
            $query = $this->db->query('SELECT detail_pending_wet_finishing.jenis_pending, SUM(detail_pending_wet_finishing.qty_pending) as qty, (
                                        SELECT SUM(total_aktual) 
                                        FROM lhp_produksi2
                                        WHERE MONTH(tanggal_produksi) = '.$bulan.' AND YEAR(tanggal_produksi) = '.$year.'
                                        AND line >= 8 AND line <= 9
                                        GROUP BY MONTH(tanggal_produksi)
                                    ) AS total_aktual
                                    FROM detail_pending_wet_finishing
                                    JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_pending_wet_finishing.id_lhp
                                    WHERE MONTH(lhp_produksi2.tanggal_produksi) = '.$bulan.' AND YEAR(lhp_produksi2.tanggal_produksi) = '.$year.'
                                    AND lhp_produksi2.line >= 8 AND lhp_produksi2.line <= 9
                                    AND detail_pending_wet_finishing.jenis_pending != \'SETTING\'
                                    GROUP BY detail_pending_wet_finishing.jenis_pending
                                    ORDER BY SUM(detail_pending_wet_finishing.qty_pending) DESC
                                ');
        } else {
            $query = $this->db->query('SELECT detail_pending_wet_finishing.jenis_pending, SUM(detail_pending_wet_finishing.qty_pending) as qty, (
                                        SELECT SUM(total_aktual) 
                                        FROM lhp_produksi2
                                        WHERE MONTH(tanggal_produksi) = '.$bulan.' AND YEAR(tanggal_produksi) = '.$year.'
                                        AND line = '.$line.'
                                        GROUP BY MONTH(tanggal_produksi)
                                    ) AS total_aktual
                                    FROM detail_pending_wet_finishing
                                    JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_pending_wet_finishing.id_lhp
                                    WHERE MONTH(lhp_produksi2.tanggal_produksi) = '.$bulan.' AND YEAR(lhp_produksi2.tanggal_produksi) = '.$year.'
                                    AND lhp_produksi2.line = '.$line.'
                                    AND detail_pending_wet_finishing.jenis_pending != \'SETTING\'
                                    GROUP BY detail_pending_wet_finishing.jenis_pending
                                    ORDER BY SUM(detail_pending_wet_finishing.qty_pending) DESC
                                ');
        }
        
        return $query->getResultArray();
    }

    // public function get_total_data_reject_by_month($bulan) 
    // {
    //     $query = $this->db->query('SELECT MONTH(lhp_produksi2.tanggal_produksi), SUM(detail_pending_wet_finishing.qty_pending) as qty
    //                                 FROM detail_pending_wet_finishing
    //                                 JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_pending_wet_finishing.id_lhp
    //                                 WHERE MONTH(lhp_produksi2.tanggal_produksi) = '.$bulan.'
    //                                 GROUP BY MONTH(lhp_produksi2.tanggal_produksi)
    //                                 ORDER BY MONTH(lhp_produksi2.tanggal_produksi)
    //                             ');
    //     return $query->getResultArray();
    // }

    // public function get_total_aktual_by_month($bulan) 
    // {
    //     $query = $this->db->query('SELECT MONTH(lhp_produksi2.tanggal_produksi), SUM(lhp_produksi2.total_aktual) as total_aktual
    //                                 FROM detail_pending_wet_finishing
    //                                 RIGHT JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_pending_wet_finishing.id_lhp
    //                                 WHERE MONTH(lhp_produksi2.tanggal_produksi) = '.$bulan.'
    //                                 GROUP BY MONTH(lhp_produksi2.tanggal_produksi)
    //                                 ORDER BY MONTH(lhp_produksi2.tanggal_produksi)
    //                             ');
    //     return $query->getResultArray();
    // }

    public function get_total_aktual_by_month($month, $line) 
    {
        $bulan = idate('m', strtotime($month));
        $year = date('Y', strtotime($month));
        if ($line == null OR $line == 0) {
            $query = $this->db->query('SELECT MONTH(tanggal_produksi), SUM(total_aktual) as total_aktual
                                        FROM lhp_produksi2
                                        WHERE MONTH(tanggal_produksi) = '.$bulan.' AND YEAR(tanggal_produksi) = '.$year.'
                                        AND line >= 8 AND line <= 9
                                        GROUP BY MONTH(tanggal_produksi)
                                    ');
        } else {
            $query = $this->db->query('SELECT MONTH(tanggal_produksi), SUM(total_aktual) as total_aktual
                                        FROM lhp_produksi2
                                        WHERE MONTH(tanggal_produksi) = '.$bulan.' AND YEAR(tanggal_produksi) = '.$year.'
                                        AND line = '.$line.'
                                        GROUP BY MONTH(tanggal_produksi)
                                    ');
        }

        return $query->getResultArray();
    }

    public function get_data_reject_by_date($tanggal, $jenis_reject, $line)
    {
        if ($line == null OR $line == 0) {
            $query = $this->db->query('SELECT		lhp_produksi2.tanggal_produksi, detail_pending_wet_finishing.jenis_pending, SUM(detail_pending_wet_finishing.qty_pending) as qty
                                        FROM		detail_pending_wet_finishing
                                        JOIN		lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_pending_wet_finishing.id_lhp
                                        WHERE		lhp_produksi2.tanggal_produksi = \''.$tanggal.'\'
                                        AND         detail_pending_wet_finishing.jenis_pending = \''.$jenis_reject.'\'
                                        AND         lhp_produksi2.line >= 8 AND lhp_produksi2.line <= 9
                                        AND         detail_pending_wet_finishing.jenis_pending != \'SETTING \'
                                        GROUP BY	detail_pending_wet_finishing.jenis_pending, lhp_produksi2.tanggal_produksi
                                    ');
        } else {
            $query = $this->db->query('SELECT		lhp_produksi2.tanggal_produksi, detail_pending_wet_finishing.jenis_pending, SUM(detail_pending_wet_finishing.qty_pending) as qty
                                        FROM		detail_pending_wet_finishing
                                        JOIN		lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_pending_wet_finishing.id_lhp
                                        WHERE		lhp_produksi2.tanggal_produksi = \''.$tanggal.'\'
                                        AND         detail_pending_wet_finishing.jenis_pending = \''.$jenis_reject.'\'
                                        AND         lhp_produksi2.line = '.$line.'
                                        AND         detail_pending_wet_finishing.jenis_pending != \'SETTING \'
                                        GROUP BY	detail_pending_wet_finishing.jenis_pending, lhp_produksi2.tanggal_produksi
                                    ');
        }
        
        return $query->getResultArray();
    }

    public function get_data_kategori_reject_by_date($tanggal, $jenis_reject, $kategori_reject)
    {
        $query = $this->db->query('SELECT		lhp_produksi2.tanggal_produksi, detail_pending_wet_finishing.jenis_pending, detail_pending_wet_finishing.kategori_pending, SUM(detail_pending_wet_finishing.qty_pending) as qty
                                    FROM		detail_pending_wet_finishing
                                    JOIN		lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_pending_wet_finishing.id_lhp
                                    WHERE		lhp_produksi2.tanggal_produksi = \''.$tanggal.'\'
                                    AND         detail_pending_wet_finishing.jenis_pending = \''.$jenis_reject.'\'
                                    AND         detail_pending_wet_finishing.kategori_pending = \''.$kategori_reject.'\'
                                    AND         lhp_produksi2.line >= 8 AND lhp_produksi2.line <= 9
                                    AND         detail_pending_wet_finishing.jenis_pending != \'SETTING\'
                                    GROUP BY	detail_pending_wet_finishing.kategori_pending, detail_pending_wet_finishing.jenis_pending, lhp_produksi2.tanggal_produksi
                                ');
        return $query->getResultArray();
    }

    public function get_jenis_reject_by_month($month, $line) 
    {
        $bulan = idate('m', strtotime($month));
        $year = date('Y', strtotime($month));
        if ($line == null OR $line == 0) {
            $query = $this->db->query('SELECT	DISTINCT(detail_pending_wet_finishing.jenis_pending)
                                    FROM	detail_pending_wet_finishing
                                    JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_pending_wet_finishing.id_lhp
                                    WHERE MONTH(lhp_produksi2.tanggal_produksi) = '.$bulan.' AND YEAR(lhp_produksi2.tanggal_produksi) = '.$year.'
                                    AND lhp_produksi2.line >= 8 AND lhp_produksi2.line <= 9
                                    AND detail_pending_wet_finishing.jenis_pending != \'SETTING\'
                                ');
        } else {
            $query = $this->db->query('SELECT	DISTINCT(detail_pending_wet_finishing.jenis_pending)
                                    FROM	detail_pending_wet_finishing
                                    JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_pending_wet_finishing.id_lhp
                                    WHERE MONTH(lhp_produksi2.tanggal_produksi) = '.$bulan.' AND YEAR(lhp_produksi2.tanggal_produksi) = '.$year.'
                                    AND lhp_produksi2.line = '.$line.'
                                    AND detail_pending_wet_finishing.jenis_pending != \'SETTING\'
                                ');
        }

        return $query->getResultArray();
    }

    public function get_kategori_reject_by_month($month, $jenis_reject) 
    {
        $bulan = idate('m', strtotime($month));
        $year = date('Y', strtotime($month));
        $query = $this->db->query('SELECT	DISTINCT(detail_pending_wet_finishing.kategori_pending)
                                    FROM	detail_pending_wet_finishing
                                    JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_pending_wet_finishing.id_lhp
                                    WHERE MONTH(lhp_produksi2.tanggal_produksi) = '.$bulan.' AND YEAR(lhp_produksi2.tanggal_produksi) = '.$year.'
                                    AND detail_pending_wet_finishing.jenis_pending = \''.$jenis_reject.'\'
                                    AND detail_pending_wet_finishing.jenis_pending != \'SETTING\'
                                    AND lhp_produksi2.line >= 8 AND lhp_produksi2.line <= 9
                                ');

        return $query->getResultArray();
    }

    public function get_data_total_reject_line_by_date($tanggal) 
    {
        // $query = $this->db->query('SELECT	line, SUM(lhp_produksi2.total_reject) as total_reject, SUM(lhp_produksi2.total_aktual) as total_aktual
        //                             FROM	lhp_produksi2
        //                             WHERE tanggal_produksi = \''.$tanggal.'\'
        //                             AND line != 10
        //                             GROUP BY lhp_produksi2.line
        //                             CASE
        //                                 WHEN SUM(lhp_produksi2.total_aktual) = 0 THEN 0
        //                                 ((SUM(lhp_produksi2.total_reject) / CAST(SUM(lhp_produksi2.total_aktual) as float)) * 100)
        //                             END DESC
        //                         ');

        $query = $this->db->query('SELECT line, total_reject, total_aktual
                                    FROM (
                                    SELECT
                                        line,
                                        SUM(lhp_produksi2.total_reject) as total_reject,
                                        SUM(lhp_produksi2.total_aktual) as total_aktual,
                                        CASE
                                        WHEN SUM(lhp_produksi2.total_aktual) = 0 THEN 0
                                        ELSE (SUM(lhp_produksi2.total_reject) / CAST(SUM(lhp_produksi2.total_aktual) as float)) * 100
                                        END AS reject_percentage
                                    FROM lhp_produksi2
                                    WHERE tanggal_produksi = \''.$tanggal.'\'
                                        AND line >= 8 AND line <= 9
                                    GROUP BY lhp_produksi2.line
                                    ) AS subquery
                                    ORDER BY reject_percentage DESC
                                ');

        return $query->getResultArray();
    }

    public function get_data_total_reject_line_by_month($month) 
    {
        $bulan = idate('m', strtotime($month));
        $year = date('Y', strtotime($month));
        // $query = $this->db->query('SELECT	lhp_produksi2.line, SUM(detail_pending_wet_finishing.qty_pending) as qty_reject, SUM(lhp_produksi2.total_aktual) as total_aktual, 
        //                                     ((SUM(detail_pending_wet_finishing.qty_pending) / CAST(SUM(lhp_produksi2.total_aktual) as float)) * 100) as persen
        //                             FROM	detail_pending_wet_finishing
        //                             RIGHT JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_pending_wet_finishing.id_lhp
        //                             WHERE MONTH(lhp_produksi2.tanggal_produksi) = '.$bulan.' 
        //                             AND lhp_produksi2.line != 10
        //                             GROUP BY lhp_produksi2.line
        //                             ORDER BY ((SUM(detail_pending_wet_finishing.qty_pending) / CAST(SUM(lhp_produksi2.total_aktual) as float)) * 100) DESC
        //                         ');

        // $query = $this->db->query('SELECT	line, SUM(lhp_produksi2.total_reject) as total_reject, SUM(lhp_produksi2.total_aktual) as total_aktual
        //                             FROM	lhp_produksi2
        //                             WHERE MONTH(tanggal_produksi) = '.$bulan.' 
        //                             AND line != 10
        //                             GROUP BY line
        //                             ORDER BY ((SUM(lhp_produksi2.total_reject) / CAST(SUM(lhp_produksi2.total_aktual) as float)) * 100) DESC
        //                         ');

        $query = $this->db->query('SELECT line, total_reject, total_aktual
                                    FROM (
                                    SELECT
                                        lhp_produksi2.line,
                                        SUM(detail_pending_wet_finishing.qty_pending) as total_reject,
                                        (
                                            SELECT SUM(total_aktual) 
                                            FROM lhp_produksi2
                                            WHERE MONTH(lhp_produksi2.tanggal_produksi) = '.$bulan.' AND YEAR(lhp_produksi2.tanggal_produksi) = ' . $year . '
                                            AND lhp_produksi2.line >= 8 AND lhp_produksi2.line <= 9
                                            GROUP BY MONTH(lhp_produksi2.tanggal_produksi)
                                        ) AS total_aktual,
                                        CASE
                                        WHEN (
                                            SELECT SUM(total_aktual) 
                                            FROM lhp_produksi2
                                            WHERE MONTH(lhp_produksi2.tanggal_produksi) = '.$bulan.' AND YEAR(lhp_produksi2.tanggal_produksi) = ' . $year . '
                                            AND lhp_produksi2.line >= 8 AND lhp_produksi2.line <= 9
                                            GROUP BY MONTH(lhp_produksi2.tanggal_produksi)
                                        ) = 0 THEN 0
                                        ELSE (SUM(detail_pending_wet_finishing.qty_pending) / CAST((
                                            SELECT SUM(total_aktual) 
                                            FROM lhp_produksi2
                                            WHERE MONTH(lhp_produksi2.tanggal_produksi) = '.$bulan.' AND YEAR(lhp_produksi2.tanggal_produksi) = ' . $year . '
                                            AND lhp_produksi2.line >= 8 AND lhp_produksi2.line <= 9
                                            GROUP BY MONTH(lhp_produksi2.tanggal_produksi)
                                        ) as float)) * 100
                                        END AS reject_percentage
                                    FROM lhp_produksi2
									JOIN detail_pending_wet_finishing ON lhp_produksi2.id_lhp_2 = detail_pending_wet_finishing.id_lhp
                                    WHERE MONTH(lhp_produksi2.tanggal_produksi) = ' . $bulan . ' AND YEAR(lhp_produksi2.tanggal_produksi) = ' . $year . '
                                        AND lhp_produksi2.line >= 8 AND lhp_produksi2.line <= 9
                                    GROUP BY lhp_produksi2.line
                                    ) AS subquery
                                    ORDER BY reject_percentage DESC
                                ');

        // $query = $this->db->query('');

        return $query->getResultArray();
    }

    // public function get_data_rejection_by_month($bulan, $line)
    // {
    //     if ($line == null || $line == 0) {
    //         $query = $this->db->query('SELECT	MONTH(lhp_produksi2.tanggal_produksi), SUM(detail_pending_wet_finishing.qty_pending) as total_reject, SUM(lhp_produksi2.total_aktual) as total_aktual
    //                                     FROM	detail_pending_wet_finishing
    //                                     RIGHT JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_pending_wet_finishing.id_lhp
    //                                     WHERE MONTH(lhp_produksi2.tanggal_produksi) = '.$bulan.'
    //                                     GROUP BY MONTH(lhp_produksi2.tanggal_produksi)
    //                                 ');
    //     } else {
    //         $query = $this->db->query('SELECT	MONTH(lhp_produksi2.tanggal_produksi), SUM(detail_pending_wet_finishing.qty_pending) as total_reject, SUM(lhp_produksi2.total_aktual) as total_aktual
    //                                     FROM	detail_pending_wet_finishing
    //                                     RIGHT JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_pending_wet_finishing.id_lhp
    //                                     WHERE MONTH(lhp_produksi2.tanggal_produksi) = '.$bulan.'
    //                                     AND lhp_produksi2.line = '.$line.'
    //                                     GROUP BY MONTH(lhp_produksi2.tanggal_produksi)
    //                                 ');
    //     }

    //     return $query->getResultArray();
    // }

    public function get_data_rejection_by_month($month, $line)
    {
        $bulan = idate('m', strtotime($month));
        $year = date('Y', strtotime($month));
        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT	MONTH(tanggal_produksi), SUM(total_reject) as total_reject, SUM(total_aktual) as total_aktual
                                        FROM	lhp_produksi2
                                        WHERE MONTH(tanggal_produksi) = '.$bulan.' AND YEAR(tanggal_produksi) = '.$year.'
                                        AND line >= 8 AND line <= 9
                                        GROUP BY MONTH(tanggal_produksi)
                                    ');
        } else {
            $query = $this->db->query('SELECT	MONTH(tanggal_produksi), SUM(total_reject) as total_reject, SUM(total_aktual) as total_aktual
                                        FROM	lhp_produksi2
                                        WHERE MONTH(tanggal_produksi) = '.$bulan.' AND YEAR(tanggal_produksi) = '.$year.'
                                        AND line = '.$line.'
                                        GROUP BY MONTH(tanggal_produksi)
                                    ');
        }

        return $query->getResultArray();
    }

    public function get_year_to_date_rejection($month, $line)
    {
        $year = date('Y', strtotime($month));

        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT YEAR(tanggal_produksi) AS month,SUM(total_reject) AS total_reject, SUM(total_aktual) AS total_aktual
                                        FROM lhp_produksi2 
                                        WHERE YEAR(tanggal_produksi) = '.$year.'
                                        AND line >= 8 AND line <= 9
                                        GROUP BY YEAR(tanggal_produksi)
                                        ORDER BY YEAR(tanggal_produksi)
                                    ');
        } else {
            $query = $this->db->query('SELECT YEAR(tanggal_produksi) AS month,SUM(total_reject) AS total_reject, SUM(total_aktual) AS total_aktual
                                        FROM lhp_produksi2 
                                        WHERE YEAR(tanggal_produksi) = '.$year.' AND line = '.$line.'
                                        GROUP BY YEAR(tanggal_produksi)
                                        ORDER BY YEAR(tanggal_produksi)
                                    ');
        }

        return $query->getResultArray();
    }

    public function get_detail_rejection_by_jenis($jenis_reject, $date, $line)
    {
        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT lhp_produksi2.tanggal_produksi, detail_pending_wet_finishing.jenis_pending, detail_pending_wet_finishing.kategori_pending, SUM(detail_pending_wet_finishing.qty_pending) as qty
                                        FROM detail_pending_wet_finishing
                                        JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_pending_wet_finishing.id_lhp
                                        WHERE lhp_produksi2.tanggal_produksi = \''.$date.'\'
                                        AND detail_pending_wet_finishing.jenis_pending = \''.$jenis_reject.'\'
                                        AND lhp_produksi2.line >= 8 AND lhp_produksi2.line <= 9
                                        AND detail_pending_wet_finishing.jenis_pending != \'SETTING \'
                                        GROUP BY detail_pending_wet_finishing.kategori_pending, detail_pending_wet_finishing.jenis_pending, lhp_produksi2.tanggal_produksi
                                        ORDER BY  SUM(detail_pending_wet_finishing.qty_pending) DESC
                                    ');
        } else {
            $query = $this->db->query('SELECT lhp_produksi2.tanggal_produksi, detail_pending_wet_finishing.jenis_pending, detail_pending_wet_finishing.kategori_pending, SUM(detail_pending_wet_finishing.qty_pending) as qty
                                        FROM detail_pending_wet_finishing
                                        JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_pending_wet_finishing.id_lhp
                                        WHERE lhp_produksi2.tanggal_produksi = \''.$date.'\'
                                        AND detail_pending_wet_finishing.jenis_pending = \''.$jenis_reject.'\'
                                        AND lhp_produksi2.line = '.$line.'
                                        AND detail_pending_wet_finishing.jenis_pending != \'SETTING \'
                                        GROUP BY detail_pending_wet_finishing.kategori_pending, detail_pending_wet_finishing.jenis_pending, lhp_produksi2.tanggal_produksi
                                        ORDER BY  SUM(detail_pending_wet_finishing.qty_pending) DESC
                                    ');
        }

        return $query->getResultArray();
    }

    public function get_detail_rejection_by_type_battery($jenis_reject, $date, $line)
    {
        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT lhp_produksi2.tanggal_produksi, detail_pending_wet_finishing.type_battery, detail_pending_wet_finishing.jenis_pending, detail_pending_wet_finishing.kategori_pending, SUM(detail_pending_wet_finishing.qty_pending) as qty
                                        FROM detail_pending_wet_finishing
                                        JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_pending_wet_finishing.id_lhp
                                        WHERE lhp_produksi2.tanggal_produksi = \''.$date.'\'
                                        AND detail_pending_wet_finishing.jenis_pending = \''.$jenis_reject.'\'
                                        AND lhp_produksi2.line >= 8 AND lhp_produksi2.line <= 9
                                        AND detail_pending_wet_finishing.jenis_pending != \'SETTING \'
                                        GROUP BY detail_pending_wet_finishing.type_battery, detail_pending_wet_finishing.kategori_pending, detail_pending_wet_finishing.jenis_pending, lhp_produksi2.tanggal_produksi
                                        ORDER BY  SUM(detail_pending_wet_finishing.qty_pending) DESC
                                    ');
        } else {
            $query = $this->db->query('SELECT lhp_produksi2.tanggal_produksi, detail_pending_wet_finishing.type_battery, detail_pending_wet_finishing.jenis_pending, detail_pending_wet_finishing.kategori_pending, SUM(detail_pending_wet_finishing.qty_pending) as qty
                                        FROM detail_pending_wet_finishing
                                        JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_pending_wet_finishing.id_lhp
                                        WHERE lhp_produksi2.tanggal_produksi = \''.$date.'\'
                                        AND detail_pending_wet_finishing.jenis_pending = \''.$jenis_reject.'\'
                                        AND lhp_produksi2.line = '.$line.'
                                        AND detail_pending_wet_finishing.jenis_pending != \'SETTING \'
                                        GROUP BY detail_pending_wet_finishing.type_battery, detail_pending_wet_finishing.kategori_pending, detail_pending_wet_finishing.jenis_pending, lhp_produksi2.tanggal_produksi
                                        ORDER BY  SUM(detail_pending_wet_finishing.qty_pending) DESC
                                    ');
        }

        return $query->getResultArray();
    }

    public function get_data_reject_all_line_by_date($tanggal, $line)
    {
        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT tanggal_produksi,
                                            (
                                                SELECT SUM(total_aktual) 
                                                FROM lhp_produksi2
                                                WHERE tanggal_produksi = \''.$tanggal.'\'
                                                AND line >= 8 AND line <= 9
                                                GROUP BY tanggal_produksi
                                            ) AS total_aktual,
                                            (
                                                SELECT SUM(detail_pending_wet_finishing.qty_pending)
                                                FROM lhp_produksi2
                                                JOIN detail_pending_wet_finishing ON lhp_produksi2.id_lhp_2 = detail_pending_wet_finishing.id_lhp
                                                WHERE lhp_produksi2.tanggal_produksi = \''.$tanggal.'\'
                                                AND lhp_produksi2.line >= 8 AND lhp_produksi2.line <= 9
                                                AND detail_pending_wet_finishing.jenis_pending != \'SETTING\'
                                                GROUP BY lhp_produksi2.tanggal_produksi
                                            ) AS total_reject
                                        FROM lhp_produksi2
                                        WHERE tanggal_produksi = \''.$tanggal.'\'
                                        GROUP BY tanggal_produksi');
        } else {
            $query = $this->db->query('SELECT tanggal_produksi,
                                            (
                                                SELECT SUM(total_aktual) 
                                                FROM lhp_produksi2
                                                WHERE tanggal_produksi = \''.$tanggal.'\'
                                                AND line = '.$line.'
                                                GROUP BY tanggal_produksi
                                            ) AS total_aktual,
                                            (
                                                SELECT SUM(detail_pending_wet_finishing.qty_pending)
                                                FROM lhp_produksi2
                                                JOIN detail_pending_wet_finishing ON lhp_produksi2.id_lhp_2 = detail_pending_wet_finishing.id_lhp
                                                WHERE lhp_produksi2.tanggal_produksi = \''.$tanggal.'\'
                                                AND lhp_produksi2.line = '.$line.'
                                                AND detail_pending_wet_finishing.jenis_pending != \'SETTING\'
                                                GROUP BY lhp_produksi2.tanggal_produksi
                                            ) AS total_reject
                                        FROM lhp_produksi2
                                        WHERE tanggal_produksi = \''.$tanggal.'\'
                                        GROUP BY tanggal_produksi');
        }
        
        return $query->getResultArray();
    }

    public function get_data_average_reject_by_month($month, $bulan, $line)
    {
        $year = date('Y', strtotime($month));
        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT
                                            MONTH(lp.tanggal_produksi) AS month, (
                                                SELECT SUM(total_aktual) 
                                                FROM lhp_produksi2
                                                WHERE MONTH(tanggal_produksi) = '.$bulan.' AND YEAR(tanggal_produksi) = '.$year.'
                                                AND line >= 8 AND line <= 9
                                                GROUP BY MONTH(tanggal_produksi)
                                            ) AS total_aktual,
                                            SUM(dp.qty_pending) AS total_reject
                                        FROM lhp_produksi2 lp
                                        JOIN detail_pending_wet_finishing dp ON lp.id_lhp_2 = dp.id_lhp
                                        WHERE MONTH(tanggal_produksi) = '.$bulan.' AND YEAR(tanggal_produksi) = '.$year.'
                                            AND lp.line >= 8 AND lp.line <= 9
                                        GROUP BY MONTH(lp.tanggal_produksi)
                                        ORDER BY MONTH(lp.tanggal_produksi);
                                    ');
        } else {
            $query = $this->db->query('SELECT
                                            MONTH(lp.tanggal_produksi) AS month, (
                                                SELECT SUM(total_aktual) 
                                                FROM lhp_produksi2
                                                WHERE MONTH(tanggal_produksi) = '.$bulan.' AND YEAR(tanggal_produksi) = '.$year.'
                                                AND line = '.$line.'
                                                GROUP BY MONTH(tanggal_produksi)
                                            ) AS total_aktual,
                                            SUM(dp.qty_pending) AS total_reject
                                        FROM lhp_produksi2 lp
                                        JOIN detail_pending_wet_finishing dp ON lp.id_lhp_2 = dp.id_lhp
                                        WHERE MONTH(tanggal_produksi) = '.$bulan.' AND YEAR(tanggal_produksi) = '.$year.'
                                            AND lp.line = '.$line.'
                                        GROUP BY MONTH(lp.tanggal_produksi)
                                        ORDER BY MONTH(lp.tanggal_produksi);
                                    ');
        }

        return $query->getResultArray();
    }

    public function get_data_reject_all_line($tanggal, $line)
    {
        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT
                                            lp.tanggal_produksi, (
                                                SELECT SUM(total_aktual) 
                                                FROM lhp_produksi2
                                                WHERE tanggal_produksi = \''.$tanggal.'\'
                                                AND line >= 8 AND line <= 9
                                                GROUP BY tanggal_produksi
                                            ) AS total_aktual,
                                            SUM(dp.qty_pending) AS total_reject
                                        FROM lhp_produksi2 lp
                                        JOIN detail_pending_wet_finishing dp ON lp.id_lhp_2 = dp.id_lhp
                                        WHERE tanggal_produksi = \''.$tanggal.'\'
                                            AND lp.line >= 8 AND lp.line <= 9
                                        GROUP BY lp.tanggal_produksi
                                        ORDER BY lp.tanggal_produksi');
        } else {
            $query = $this->db->query('SELECT
                                            lp.tanggal_produksi, (
                                                SELECT SUM(total_aktual) 
                                                FROM lhp_produksi2
                                                WHERE tanggal_produksi = \''.$tanggal.'\'
                                                AND line = '.$line.'
                                                GROUP BY tanggal_produksi
                                            ) AS total_aktual,
                                            SUM(dp.qty_pending) AS total_reject
                                        FROM lhp_produksi2 lp
                                        JOIN detail_pending_wet_finishing dp ON lp.id_lhp_2 = dp.id_lhp
                                        WHERE tanggal_produksi = \''.$tanggal.'\'
                                            AND lp.line = '.$line.'
                                        GROUP BY lp.tanggal_produksi
                                        ORDER BY lp.tanggal_produksi');
        }
        

        return $query->getResultArray();
    }

    public function get_data_reject_all_line_by_month($month, $bulan, $line)
    {
        $year = date('Y', strtotime($month));
        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT MONTH(lhp_produksi2.tanggal_produksi) AS month,SUM(detail_pending_wet_finishing.qty_pending) AS total_reject, (
                                            SELECT SUM(total_aktual) 
                                            FROM lhp_produksi2
                                            WHERE MONTH(tanggal_produksi) = '.$bulan.' AND YEAR(tanggal_produksi) = '.$year.'
                                            AND line >= 8 AND line <= 9
                                            GROUP BY MONTH(tanggal_produksi)
                                        ) AS total_aktual
                                        FROM lhp_produksi2
                                        JOIN detail_pending_wet_finishing on lhp_produksi2.id_lhp_2 = detail_pending_wet_finishing.id_lhp
                                        WHERE MONTH(lhp_produksi2.tanggal_produksi) = '.$bulan.' AND YEAR(lhp_produksi2.tanggal_produksi) = '.$year.'
                                        AND line >= 8 AND line <= 9
                                        GROUP BY MONTH(lhp_produksi2.tanggal_produksi)
                                        ORDER BY MONTH(lhp_produksi2.tanggal_produksi)
                                    ');
        } else {
            $query = $this->db->query('SELECT MONTH(lhp_produksi2.tanggal_produksi) AS month,SUM(detail_pending_wet_finishing.qty_pending) AS total_reject, (
                                            SELECT SUM(total_aktual) 
                                            FROM lhp_produksi2
                                            WHERE MONTH(tanggal_produksi) = '.$bulan.' AND YEAR(tanggal_produksi) = '.$year.'
                                            AND line = '.$line.'
                                            GROUP BY MONTH(tanggal_produksi)
                                        ) AS total_aktual
                                        FROM lhp_produksi2
                                        JOIN detail_pending_wet_finishing on lhp_produksi2.id_lhp_2 = detail_pending_wet_finishing.id_lhp
                                        WHERE MONTH(lhp_produksi2.tanggal_produksi) = '.$bulan.' AND YEAR(lhp_produksi2.tanggal_produksi) = '.$year.' AND line = '.$line.'
                                        GROUP BY MONTH(lhp_produksi2.tanggal_produksi)
                                        ORDER BY MONTH(lhp_produksi2.tanggal_produksi)
                                    ');
        }

        return $query->getResultArray();
    }

    public function get_qty_jenis_reject($tanggal, $line) 
    {
        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT lhp_produksi2.tanggal_produksi, detail_pending_wet_finishing.jenis_pending, SUM(detail_pending_wet_finishing.qty_pending) as qty
                                        FROM detail_pending_wet_finishing
                                        JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_pending_wet_finishing.id_lhp
                                        WHERE lhp_produksi2.tanggal_produksi = \''.$tanggal.'\'
                                        AND lhp_produksi2.line >= 8 AND lhp_produksi2.line <= 9
                                        AND detail_pending_wet_finishing.jenis_pending != \'SETTING\'
                                        GROUP BY detail_pending_wet_finishing.jenis_pending, lhp_produksi2.tanggal_produksi
                                        ORDER BY  SUM(detail_pending_wet_finishing.qty_pending) DESC
                                    ');
        } else {
            $query = $this->db->query('SELECT lhp_produksi2.tanggal_produksi, detail_pending_wet_finishing.jenis_pending, SUM(detail_pending_wet_finishing.qty_pending) as qty
                                        FROM detail_pending_wet_finishing
                                        JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_pending_wet_finishing.id_lhp
                                        WHERE lhp_produksi2.tanggal_produksi = \''.$tanggal.'\'
                                        AND lhp_produksi2.line = '.$line.'
                                        AND detail_pending_wet_finishing.jenis_pending != \'SETTING\'
                                        GROUP BY detail_pending_wet_finishing.jenis_pending, lhp_produksi2.tanggal_produksi
                                        ORDER BY SUM(detail_pending_wet_finishing.qty_pending) DESC
                                    ');
        }

        return $query->getResultArray();
    }

    public function get_detail_rejection_by_jenis_by_month($jenis_reject, $month, $line)
    {
        $bulan = idate('m', strtotime($month));
        $year = date('Y', strtotime($month));
        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT MONTH(lhp_produksi2.tanggal_produksi), detail_pending_wet_finishing.jenis_pending, detail_pending_wet_finishing.kategori_pending, SUM(detail_pending_wet_finishing.qty_pending) as qty, (
                                            SELECT SUM(total_aktual) 
                                            FROM lhp_produksi2
                                            WHERE MONTH(tanggal_produksi) = '.$bulan.' AND YEAR(tanggal_produksi) = '.$year.'
                                            AND line >= 8 AND line <= 9
                                            GROUP BY MONTH(tanggal_produksi)
                                        ) AS total_aktual
                                        FROM detail_pending_wet_finishing
                                        JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_pending_wet_finishing.id_lhp
                                        WHERE MONTH(lhp_produksi2.tanggal_produksi) = '.$bulan.' AND YEAR(lhp_produksi2.tanggal_produksi) = '.$year.'
                                        AND detail_pending_wet_finishing.jenis_pending = \''.$jenis_reject.'\'
                                        AND lhp_produksi2.line >= 8 AND lhp_produksi2.line <= 9
                                        GROUP BY detail_pending_wet_finishing.kategori_pending, detail_pending_wet_finishing.jenis_pending, MONTH(lhp_produksi2.tanggal_produksi)
                                        ORDER BY  SUM(detail_pending_wet_finishing.qty_pending) DESC
                                    ');
        } else {
            $query = $this->db->query('SELECT MONTH(lhp_produksi2.tanggal_produksi), detail_pending_wet_finishing.jenis_pending, detail_pending_wet_finishing.kategori_pending, SUM(detail_pending_wet_finishing.qty_pending) as qty, (
                                            SELECT SUM(total_aktual) 
                                            FROM lhp_produksi2
                                            WHERE MONTH(tanggal_produksi) = '.$bulan.' AND YEAR(tanggal_produksi) = '.$year.'
                                            AND line = '.$line.'
                                            GROUP BY MONTH(tanggal_produksi)
                                        ) AS total_aktual
                                        FROM detail_pending_wet_finishing
                                        JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_pending_wet_finishing.id_lhp
                                        WHERE MONTH(lhp_produksi2.tanggal_produksi) = '.$bulan.' AND YEAR(lhp_produksi2.tanggal_produksi) = '.$year.'
                                        AND detail_pending_wet_finishing.jenis_pending = \''.$jenis_reject.'\'
                                        AND lhp_produksi2.line = '.$line.'
                                        GROUP BY detail_pending_wet_finishing.kategori_pending, detail_pending_wet_finishing.jenis_pending, MONTH(lhp_produksi2.tanggal_produksi)
                                        ORDER BY  SUM(detail_pending_wet_finishing.qty_pending) DESC
                                    ');
        }

        return $query->getResultArray();
    }

    public function get_detail_rejection_by_type_battery_by_month($jenis_reject, $month, $line)
    {
        $bulan = idate('m', strtotime($month));
        $year = date('Y', strtotime($month));
        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT MONTH(lhp_produksi2.tanggal_produksi), detail_pending_wet_finishing.type_battery, detail_pending_wet_finishing.jenis_pending, detail_pending_wet_finishing.kategori_pending, SUM(detail_pending_wet_finishing.qty_pending) as qty
                                        FROM detail_pending_wet_finishing
                                        JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_pending_wet_finishing.id_lhp
                                        WHERE MONTH(lhp_produksi2.tanggal_produksi) = '.$bulan.' AND YEAR(lhp_produksi2.tanggal_produksi) = '.$year.'
                                        AND detail_pending_wet_finishing.jenis_pending = \''.$jenis_reject.'\'
                                        AND lhp_produksi2.line >= 8 AND lhp_produksi2.line <= 9
                                        GROUP BY detail_pending_wet_finishing.type_battery, detail_pending_wet_finishing.kategori_pending, detail_pending_wet_finishing.jenis_pending, lhp_produksi2.tanggal_produksi
                                        ORDER BY  SUM(detail_pending_wet_finishing.qty_pending) DESC
                                    ');
        } else {
            $query = $this->db->query('SELECT MONTH(lhp_produksi2.tanggal_produksi), detail_pending_wet_finishing.type_battery, detail_pending_wet_finishing.jenis_pending, detail_pending_wet_finishing.kategori_pending, SUM(detail_pending_wet_finishing.qty_pending) as qty
                                        FROM detail_pending_wet_finishing
                                        JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_pending_wet_finishing.id_lhp
                                        WHERE MONTH(lhp_produksi2.tanggal_produksi) = '.$bulan.' AND YEAR(lhp_produksi2.tanggal_produksi) = '.$year.'
                                        AND detail_pending_wet_finishing.jenis_pending = \''.$jenis_reject.'\'
                                        AND lhp_produksi2.line = '.$line.'
                                        GROUP BY detail_pending_wet_finishing.type_battery, detail_pending_wet_finishing.kategori_pending, detail_pending_wet_finishing.jenis_pending, lhp_produksi2.tanggal_produksi
                                        ORDER BY  SUM(detail_pending_wet_finishing.qty_pending) DESC
                                    ');
        }

        return $query->getResultArray();
    }

    public function get_all_detail_kategori_rejection_by_date($date, $line)
    {
        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT lhp_produksi2.tanggal_produksi, detail_pending_wet_finishing.kategori_pending, SUM(detail_pending_wet_finishing.qty_pending) as qty
                                        FROM detail_pending_wet_finishing
                                        JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_pending_wet_finishing.id_lhp
                                        WHERE lhp_produksi2.tanggal_produksi = \''.$date.'\'
                                        AND lhp_produksi2.line >= 8 AND lhp_produksi2.line <= 9
                                        AND detail_pending_wet_finishing.jenis_pending != \'SETTING\'
                                        GROUP BY detail_pending_wet_finishing.kategori_pending, lhp_produksi2.tanggal_produksi
                                        ORDER BY  SUM(detail_pending_wet_finishing.qty_pending) DESC
                                    ');
        } else {
            $query = $this->db->query('SELECT lhp_produksi2.tanggal_produksi, detail_pending_wet_finishing.kategori_pending, SUM(detail_pending_wet_finishing.qty_pending) as qty
                                        FROM detail_pending_wet_finishing
                                        JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_pending_wet_finishing.id_lhp
                                        WHERE lhp_produksi2.tanggal_produksi = \''.$date.'\'
                                        AND lhp_produksi2.line = '.$line.'
                                        AND detail_pending_wet_finishing.jenis_pending != \'SETTING\'
                                        GROUP BY detail_pending_wet_finishing.kategori_pending, detail_pending_wet_finishing.jenis_pending, lhp_produksi2.tanggal_produksi
                                        ORDER BY  SUM(detail_pending_wet_finishing.qty_pending) DESC
                                    ');
        }

        return $query->getResultArray();
    }

    public function get_all_detail_battery_rejection_by_date($date, $line)
    {
        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT lhp_produksi2.tanggal_produksi, detail_pending_wet_finishing.type_battery, SUM(detail_pending_wet_finishing.qty_pending) as qty
                                        FROM detail_pending_wet_finishing
                                        JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_pending_wet_finishing.id_lhp
                                        WHERE lhp_produksi2.tanggal_produksi = \''.$date.'\'
                                        AND lhp_produksi2.line >= 8 AND lhp_produksi2.line <= 9
                                        AND detail_pending_wet_finishing.jenis_pending != \'SETTING\'
                                        GROUP BY detail_pending_wet_finishing.type_battery, lhp_produksi2.tanggal_produksi
                                        ORDER BY SUM(detail_pending_wet_finishing.qty_pending) DESC
                                    ');
        } else {
            $query = $this->db->query('SELECT lhp_produksi2.tanggal_produksi, detail_pending_wet_finishing.type_battery, SUM(detail_pending_wet_finishing.qty_pending) as qty
                                        FROM detail_pending_wet_finishing
                                        JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_pending_wet_finishing.id_lhp
                                        WHERE lhp_produksi2.tanggal_produksi = \''.$date.'\'
                                        AND lhp_produksi2.line = '.$line.'
                                        AND detail_pending_wet_finishing.jenis_pending != \'SETTING\'
                                        GROUP BY detail_pending_wet_finishing.type_battery, lhp_produksi2.tanggal_produksi
                                        ORDER BY  SUM(detail_pending_wet_finishing.qty_pending) DESC
                                    ');
        }

        return $query->getResultArray();
    }

    public function get_all_detail_grup_rejection_by_date($date, $line)
    {
        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT lhp_produksi2.tanggal_produksi, lhp_produksi2.grup, master_pic_line.nama_pic, lhp_produksi2.shift, SUM(detail_pending_wet_finishing.qty_pending) as total_reject, (
                                            SELECT SUM(total_aktual) 
                                            FROM lhp_produksi2
                                            WHERE tanggal_produksi = \''.$date.'\'
                                            AND line >= 8 AND line <= 9
                                            GROUP BY tanggal_produksi
                                        ) AS total_aktual
                                        FROM detail_pending_wet_finishing
                                        JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_pending_wet_finishing.id_lhp
                                        JOIN master_pic_line on master_pic_line.id_pic = lhp_produksi2.grup
                                        WHERE lhp_produksi2.tanggal_produksi = \''.$date.'\'
                                        AND lhp_produksi2.line >= 8 AND lhp_produksi2.line <= 9
                                        AND detail_pending_wet_finishing.jenis_pending != \'SETTING\'
                                        GROUP BY lhp_produksi2.grup, master_pic_line.nama_pic, lhp_produksi2.shift, lhp_produksi2.tanggal_produksi
                                        ORDER BY SUM(detail_pending_wet_finishing.qty_pending) DESC
                                    ');
        } else {
            $query = $this->db->query('SELECT lhp_produksi2.tanggal_produksi, lhp_produksi2.grup, master_pic_line.nama_pic, lhp_produksi2.shift, SUM(detail_pending_wet_finishing.qty_pending) as total_reject, (
                                            SELECT SUM(total_aktual) 
                                            FROM lhp_produksi2
                                            WHERE tanggal_produksi = \''.$date.'\'
                                            AND line = '.$line.'
                                            GROUP BY tanggal_produksi
                                        ) AS total_aktual
                                        FROM detail_pending_wet_finishing
                                        JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_pending_wet_finishing.id_lhp
                                        JOIN master_pic_line on master_pic_line.id_pic = lhp_produksi2.grup
                                        WHERE lhp_produksi2.tanggal_produksi = \''.$date.'\'
                                        AND lhp_produksi2.line = '.$line.'
                                        AND detail_pending_wet_finishing.jenis_pending != \'SETTING\'
                                        GROUP BY lhp_produksi2.grup, master_pic_line.nama_pic, lhp_produksi2.shift, lhp_produksi2.tanggal_produksi
                                        ORDER BY SUM(detail_pending_wet_finishing.qty_pending) DESC
                                    ');
        }

        return $query->getResultArray();
    }

    public function get_all_detail_kategori_rejection_by_month($month, $line)
    {
        $bulan = idate('m', strtotime($month));
        $year = date('Y', strtotime($month));
        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT MONTH(lhp_produksi2.tanggal_produksi), detail_pending_wet_finishing.kategori_pending, SUM(detail_pending_wet_finishing.qty_pending) as qty
                                        FROM detail_pending_wet_finishing
                                        JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_pending_wet_finishing.id_lhp
                                        WHERE MONTH(lhp_produksi2.tanggal_produksi) = '.$bulan.' AND YEAR(lhp_produksi2.tanggal_produksi) = '.$year.'
                                        AND lhp_produksi2.line >= 8 AND lhp_produksi2.line <= 9
                                        AND detail_pending_wet_finishing.jenis_pending != \'SETTING \'
                                        GROUP BY detail_pending_wet_finishing.kategori_pending, MONTH(lhp_produksi2.tanggal_produksi)
                                        ORDER BY  SUM(detail_pending_wet_finishing.qty_pending) DESC
                                    ');
        } else {
            $query = $this->db->query('SELECT MONTH(lhp_produksi2.tanggal_produksi), detail_pending_wet_finishing.kategori_pending, SUM(detail_pending_wet_finishing.qty_pending) as qty
                                        FROM detail_pending_wet_finishing
                                        JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_pending_wet_finishing.id_lhp
                                        WHERE MONTH(lhp_produksi2.tanggal_produksi) = '.$bulan.' AND YEAR(lhp_produksi2.tanggal_produksi) = '.$year.'
                                        AND lhp_produksi2.line = '.$line.'
                                        AND detail_pending_wet_finishing.jenis_pending != \'SETTING \'
                                        GROUP BY detail_pending_wet_finishing.kategori_pending, MONTH(lhp_produksi2.tanggal_produksi)
                                        ORDER BY  SUM(detail_pending_wet_finishing.qty_pending) DESC
                                    ');
        }

        return $query->getResultArray();
    }

    public function get_all_detail_battery_rejection_by_month($month, $line)
    {
        $bulan = idate('m', strtotime($month));
        $year = date('Y', strtotime($month));
        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT MONTH(lhp_produksi2.tanggal_produksi), detail_pending_wet_finishing.type_battery, SUM(detail_pending_wet_finishing.qty_pending) as qty
                                        FROM detail_pending_wet_finishing
                                        JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_pending_wet_finishing.id_lhp
                                        WHERE MONTH(lhp_produksi2.tanggal_produksi) = '.$bulan.' AND YEAR(lhp_produksi2.tanggal_produksi) = '.$year.'
                                        AND lhp_produksi2.line >= 8 AND lhp_produksi2.line <= 9
                                        AND detail_pending_wet_finishing.jenis_pending != \'SETTING \'
                                        GROUP BY detail_pending_wet_finishing.type_battery, MONTH(lhp_produksi2.tanggal_produksi)
                                        ORDER BY SUM(detail_pending_wet_finishing.qty_pending) DESC
                                    ');
        } else {
            $query = $this->db->query('SELECT MONTH(lhp_produksi2.tanggal_produksi), detail_pending_wet_finishing.type_battery, SUM(detail_pending_wet_finishing.qty_pending) as qty
                                        FROM detail_pending_wet_finishing
                                        JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_pending_wet_finishing.id_lhp
                                        WHERE MONTH(lhp_produksi2.tanggal_produksi) = '.$bulan.' AND YEAR(lhp_produksi2.tanggal_produksi) = '.$year.'
                                        AND lhp_produksi2.line = '.$line.'
                                        AND detail_pending_wet_finishing.jenis_pending != \'SETTING \'
                                        GROUP BY detail_pending_wet_finishing.type_battery, MONTH(lhp_produksi2.tanggal_produksi)
                                        ORDER BY  SUM(detail_pending_wet_finishing.qty_pending) DESC
                                    ');
        }

        return $query->getResultArray();
    }

    public function get_all_detail_grup_rejection_by_month($month, $line)
    {
        $bulan = idate('m', strtotime($month));
        $year = date('Y', strtotime($month));
        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT MONTH(lhp_produksi2.tanggal_produksi), lhp_produksi2.grup, master_pic_line.nama_pic, lhp_produksi2.shift, SUM(detail_pending_wet_finishing.qty_pending) as total_reject, (
                                            SELECT SUM(total_aktual) 
                                            FROM lhp_produksi2
                                            WHERE MONTH(tanggal_produksi) = '.$bulan.' AND YEAR(tanggal_produksi) = '.$year.'
                                            AND line >= 8 AND line <= 9
                                            GROUP BY MONTH(tanggal_produksi)
                                        ) AS total_aktual
                                        FROM detail_pending_wet_finishing
                                        JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_pending_wet_finishing.id_lhp
                                        JOIN master_pic_line on master_pic_line.id_pic = lhp_produksi2.grup
                                        WHERE MONTH(lhp_produksi2.tanggal_produksi) = '.$bulan.' AND YEAR(lhp_produksi2.tanggal_produksi) = '.$year.'
                                        AND lhp_produksi2.line >= 8 AND lhp_produksi2.line <= 9
                                        AND detail_pending_wet_finishing.jenis_pending != \'SETTING \'
                                        GROUP BY lhp_produksi2.grup, master_pic_line.nama_pic, lhp_produksi2.shift, MONTH(lhp_produksi2.tanggal_produksi)
                                        ORDER BY SUM(detail_pending_wet_finishing.qty_pending) DESC
                                    ');
        } else {
            $query = $this->db->query('SELECT MONTH(lhp_produksi2.tanggal_produksi), lhp_produksi2.grup, master_pic_line.nama_pic, lhp_produksi2.shift, SUM(detail_pending_wet_finishing.qty_pending) as total_reject, (
                                            SELECT SUM(total_aktual) 
                                            FROM lhp_produksi2
                                            WHERE MONTH(tanggal_produksi) = '.$bulan.' AND YEAR(tanggal_produksi) = '.$year.'
                                            AND line = '.$line.'
                                            GROUP BY MONTH(tanggal_produksi)
                                        ) AS total_aktual
                                        FROM detail_pending_wet_finishing
                                        JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_pending_wet_finishing.id_lhp
                                        JOIN master_pic_line on master_pic_line.id_pic = lhp_produksi2.grup
                                        WHERE MONTH(lhp_produksi2.tanggal_produksi) = '.$bulan.' AND YEAR(lhp_produksi2.tanggal_produksi) = '.$year.'
                                        AND lhp_produksi2.line = '.$line.'
                                        AND detail_pending_wet_finishing.jenis_pending != \'SETTING \'
                                        GROUP BY lhp_produksi2.grup, master_pic_line.nama_pic, lhp_produksi2.shift, MONTH(lhp_produksi2.tanggal_produksi)
                                        ORDER BY SUM(detail_pending_wet_finishing.qty_pending) DESC
                                    ');
        }

        return $query->getResultArray();
    }

    public function get_detail_rejection_by_grup($jenis_reject,$date,$line)
    {
        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT lhp_produksi2.tanggal_produksi, lhp_produksi2.grup, master_pic_line.nama_pic, lhp_produksi2.shift, detail_pending_wet_finishing.jenis_pending, SUM(detail_pending_wet_finishing.qty_pending) as qty
                                        FROM detail_pending_wet_finishing
                                        JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_pending_wet_finishing.id_lhp
                                        JOIN master_pic_line on master_pic_line.id_pic = lhp_produksi2.grup
                                        WHERE lhp_produksi2.tanggal_produksi = \''.$date.'\'
                                        AND detail_pending_wet_finishing.jenis_pending = \''.$jenis_reject.'\'
                                        AND lhp_produksi2.line >= 8 AND lhp_produksi2.line <= 9
                                        AND detail_pending_wet_finishing.jenis_pending != \'SETTING \'
                                        GROUP BY lhp_produksi2.grup, master_pic_line.nama_pic, lhp_produksi2.shift, detail_pending_wet_finishing.jenis_pending, lhp_produksi2.tanggal_produksi
                                        ORDER BY SUM(detail_pending_wet_finishing.qty_pending) DESC
                                    ');
        } else {
            $query = $this->db->query('SELECT lhp_produksi2.tanggal_produksi, lhp_produksi2.grup, master_pic_line.nama_pic, lhp_produksi2.shift, detail_pending_wet_finishing.jenis_pending, SUM(detail_pending_wet_finishing.qty_pending) as qty
                                        FROM detail_pending_wet_finishing
                                        JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_pending_wet_finishing.id_lhp
                                        JOIN master_pic_line on master_pic_line.id_pic = lhp_produksi2.grup
                                        WHERE lhp_produksi2.tanggal_produksi = \''.$date.'\'
                                        AND detail_pending_wet_finishing.jenis_pending = \''.$jenis_reject.'\'
                                        AND lhp_produksi2.line = '.$line.'
                                        AND detail_pending_wet_finishing.jenis_pending != \'SETTING \'
                                        GROUP BY lhp_produksi2.grup, master_pic_line.nama_pic, lhp_produksi2.shift, detail_pending_wet_finishing.jenis_pending, lhp_produksi2.tanggal_produksi
                                        ORDER BY SUM(detail_pending_wet_finishing.qty_pending) DESC
                                        
                                    ');
        }

        return $query->getResultArray();
    }

    public function get_total_aktual_by_date($date, $line) 
    {
        if ($line == null OR $line == 0) {
            $query = $this->db->query('SELECT tanggal_produksi, SUM(total_aktual) as total_aktual
                                        FROM lhp_produksi2
                                        WHERE tanggal_produksi = \''.$date.'\'
                                        AND line >= 8 AND line <= 9
                                        GROUP BY tanggal_produksi
                                    ');
        } else {
            $query = $this->db->query('SELECT tanggal_produksi, SUM(total_aktual) as total_aktual
                                        FROM lhp_produksi2
                                        WHERE tanggal_produksi = \''.$date.'\'
                                        AND line = '.$line.'
                                        GROUP BY tanggal_produksi
                                    ');
        }
        
        return $query->getResultArray();
    }

    public function get_jenis_reject_by_type_battery($type_battery, $date, $line)
    {
        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT lhp_produksi2.tanggal_produksi, detail_pending_wet_finishing.type_battery, detail_pending_wet_finishing.jenis_pending, SUM(detail_pending_wet_finishing.qty_pending) as qty
                                        FROM detail_pending_wet_finishing
                                        JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_pending_wet_finishing.id_lhp
                                        WHERE lhp_produksi2.tanggal_produksi = \''.$date.'\'
                                        AND detail_pending_wet_finishing.type_battery = \''.$type_battery.'\'
                                        AND lhp_produksi2.line >= 8 AND lhp_produksi2.line <= 9
                                        AND detail_pending_wet_finishing.jenis_pending != \'SETTING \'
                                        GROUP BY detail_pending_wet_finishing.type_battery, detail_pending_wet_finishing.jenis_pending, lhp_produksi2.tanggal_produksi
                                        ORDER BY  SUM(detail_pending_wet_finishing.qty_pending) DESC
                                    ');
        } else {
            $query = $this->db->query('SELECT lhp_produksi2.tanggal_produksi, detail_pending_wet_finishing.type_battery, detail_pending_wet_finishing.jenis_pending, SUM(detail_pending_wet_finishing.qty_pending) as qty
                                        FROM detail_pending_wet_finishing
                                        JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_pending_wet_finishing.id_lhp
                                        WHERE lhp_produksi2.tanggal_produksi = \''.$date.'\'
                                        AND detail_pending_wet_finishing.type_battery = \''.$type_battery.'\'
                                        AND lhp_produksi2.line = '.$line.'
                                        AND detail_pending_wet_finishing.jenis_pending != \'SETTING \'
                                        GROUP BY detail_pending_wet_finishing.type_battery, detail_pending_wet_finishing.jenis_pending, lhp_produksi2.tanggal_produksi
                                        ORDER BY  SUM(detail_pending_wet_finishing.qty_pending) DESC
                                    ');
        }

        return $query->getResultArray();
    }

    public function get_kategori_reject_by_type_battery($type_battery, $date, $line)
    {
        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT lhp_produksi2.tanggal_produksi, detail_pending_wet_finishing.type_battery, detail_pending_wet_finishing.kategori_pending, SUM(detail_pending_wet_finishing.qty_pending) as qty
                                        FROM detail_pending_wet_finishing
                                        JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_pending_wet_finishing.id_lhp
                                        WHERE lhp_produksi2.tanggal_produksi = \''.$date.'\'
                                        AND detail_pending_wet_finishing.type_battery = \''.$type_battery.'\'
                                        AND lhp_produksi2.line >= 8 AND lhp_produksi2.line <= 9
                                        AND detail_pending_wet_finishing.jenis_pending != \'SETTING \'
                                        GROUP BY detail_pending_wet_finishing.type_battery, detail_pending_wet_finishing.kategori_pending, lhp_produksi2.tanggal_produksi
                                        ORDER BY  SUM(detail_pending_wet_finishing.qty_pending) DESC
                                    ');
        } else {
            $query = $this->db->query('SELECT lhp_produksi2.tanggal_produksi, detail_pending_wet_finishing.type_battery, detail_pending_wet_finishing.kategori_pending, SUM(detail_pending_wet_finishing.qty_pending) as qty
                                        FROM detail_pending_wet_finishing
                                        JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_pending_wet_finishing.id_lhp
                                        WHERE lhp_produksi2.tanggal_produksi = \''.$date.'\'
                                        AND detail_pending_wet_finishing.type_battery = \''.$type_battery.'\'
                                        AND lhp_produksi2.line = '.$line.'
                                        AND detail_pending_wet_finishing.jenis_pending != \'SETTING \'
                                        GROUP BY detail_pending_wet_finishing.type_battery, detail_pending_wet_finishing.kategori_pending, lhp_produksi2.tanggal_produksi
                                        ORDER BY  SUM(detail_pending_wet_finishing.qty_pending) DESC
                                    ');
        }

        return $query->getResultArray();
    }

    public function get_grup_reject_by_type_battery($type_battery,$date,$line)
    {
        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT lhp_produksi2.tanggal_produksi, lhp_produksi2.grup, master_pic_line.nama_pic, lhp_produksi2.shift, detail_pending_wet_finishing.type_battery, SUM(detail_pending_wet_finishing.qty_pending) as qty
                                        FROM detail_pending_wet_finishing
                                        JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_pending_wet_finishing.id_lhp
                                        JOIN master_pic_line on master_pic_line.id_pic = lhp_produksi2.grup
                                        WHERE lhp_produksi2.tanggal_produksi = \''.$date.'\'
                                        AND detail_pending_wet_finishing.type_battery = \''.$type_battery.'\'
                                        AND lhp_produksi2.line >= 8 AND lhp_produksi2.line <= 9
                                        AND detail_pending_wet_finishing.jenis_pending != \'SETTING \'
                                        GROUP BY lhp_produksi2.grup, master_pic_line.nama_pic, lhp_produksi2.shift, detail_pending_wet_finishing.type_battery, lhp_produksi2.tanggal_produksi
                                        ORDER BY SUM(detail_pending_wet_finishing.qty_pending) DESC
                                    ');
        } else {
            $query = $this->db->query('SELECT lhp_produksi2.tanggal_produksi, lhp_produksi2.grup, master_pic_line.nama_pic, lhp_produksi2.shift, detail_pending_wet_finishing.type_battery, SUM(detail_pending_wet_finishing.qty_pending) as qty
                                        FROM detail_pending_wet_finishing
                                        JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_pending_wet_finishing.id_lhp
                                        JOIN master_pic_line on master_pic_line.id_pic = lhp_produksi2.grup
                                        WHERE lhp_produksi2.tanggal_produksi = \''.$date.'\'
                                        AND detail_pending_wet_finishing.type_battery = \''.$type_battery.'\'
                                        AND lhp_produksi2.line = '.$line.'
                                        AND detail_pending_wet_finishing.jenis_pending != \'SETTING \'
                                        GROUP BY lhp_produksi2.grup, master_pic_line.nama_pic, lhp_produksi2.shift, detail_pending_wet_finishing.type_battery, lhp_produksi2.tanggal_produksi
                                        ORDER BY SUM(detail_pending_wet_finishing.qty_pending) DESC
                                        
                                    ');
        }

        return $query->getResultArray();
    }

    public function get_jenis_reject_by_grup_shift($grup,$shift,$date,$line)
    {
        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT lhp_produksi2.tanggal_produksi, lhp_produksi2.grup, master_pic_line.nama_pic, lhp_produksi2.shift, detail_pending_wet_finishing.jenis_pending, SUM(detail_pending_wet_finishing.qty_pending) as qty
                                        FROM detail_pending_wet_finishing
                                        JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_pending_wet_finishing.id_lhp
                                        JOIN master_pic_line on master_pic_line.id_pic = lhp_produksi2.grup
                                        WHERE lhp_produksi2.tanggal_produksi = \''.$date.'\'
                                        AND master_pic_line.nama_pic = \''.$grup.'\'
                                        AND lhp_produksi2.shift = \''.$shift.'\'
                                        AND lhp_produksi2.line >= 8 AND lhp_produksi2.line <= 9
                                        AND detail_pending_wet_finishing.jenis_pending != \'SETTING \'
                                        GROUP BY detail_pending_wet_finishing.jenis_pending, lhp_produksi2.grup, master_pic_line.nama_pic, lhp_produksi2.shift, lhp_produksi2.tanggal_produksi
                                        ORDER BY SUM(detail_pending_wet_finishing.qty_pending) DESC
                                    ');
        } else {
            $query = $this->db->query('SELECT lhp_produksi2.tanggal_produksi, lhp_produksi2.grup, master_pic_line.nama_pic, lhp_produksi2.shift, detail_pending_wet_finishing.jenis_pending, SUM(detail_pending_wet_finishing.qty_pending) as qty
                                        FROM detail_pending_wet_finishing
                                        JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_pending_wet_finishing.id_lhp
                                        JOIN master_pic_line on master_pic_line.id_pic = lhp_produksi2.grup
                                        WHERE lhp_produksi2.tanggal_produksi = \''.$date.'\'
                                        AND master_pic_line.nama_pic = \''.$grup.'\'
                                        AND lhp_produksi2.shift = \''.$shift.'\'
                                        AND lhp_produksi2.line = '.$line.'
                                        AND detail_pending_wet_finishing.jenis_pending != \'SETTING \'
                                        GROUP BY detail_pending_wet_finishing.jenis_pending, lhp_produksi2.grup, master_pic_line.nama_pic, lhp_produksi2.shift, lhp_produksi2.tanggal_produksi
                                        ORDER BY SUM(detail_pending_wet_finishing.qty_pending) DESC
                                        
                                    ');
        }

        return $query->getResultArray();
    }

    public function get_kategori_reject_by_grup_shift($grup,$shift,$date,$line)
    {
        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT lhp_produksi2.tanggal_produksi, lhp_produksi2.grup, master_pic_line.nama_pic, lhp_produksi2.shift, detail_pending_wet_finishing.kategori_pending, SUM(detail_pending_wet_finishing.qty_pending) as qty
                                        FROM detail_pending_wet_finishing
                                        JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_pending_wet_finishing.id_lhp
                                        JOIN master_pic_line on master_pic_line.id_pic = lhp_produksi2.grup
                                        WHERE lhp_produksi2.tanggal_produksi = \''.$date.'\'
                                        AND master_pic_line.nama_pic = \''.$grup.'\'
                                        AND lhp_produksi2.shift = \''.$shift.'\'
                                        AND lhp_produksi2.line >= 8 AND lhp_produksi2.line <= 9
                                        AND detail_pending_wet_finishing.jenis_pending != \'SETTING \'
                                        GROUP BY detail_pending_wet_finishing.kategori_pending, lhp_produksi2.grup, master_pic_line.nama_pic, lhp_produksi2.shift, lhp_produksi2.tanggal_produksi
                                        ORDER BY SUM(detail_pending_wet_finishing.qty_pending) DESC
                                    ');
        } else {
            $query = $this->db->query('SELECT lhp_produksi2.tanggal_produksi, lhp_produksi2.grup, master_pic_line.nama_pic, lhp_produksi2.shift, detail_pending_wet_finishing.kategori_pending, SUM(detail_pending_wet_finishing.qty_pending) as qty
                                        FROM detail_pending_wet_finishing
                                        JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_pending_wet_finishing.id_lhp
                                        JOIN master_pic_line on master_pic_line.id_pic = lhp_produksi2.grup
                                        WHERE lhp_produksi2.tanggal_produksi = \''.$date.'\'
                                        AND master_pic_line.nama_pic = \''.$grup.'\'
                                        AND lhp_produksi2.shift = \''.$shift.'\'
                                        AND lhp_produksi2.line = '.$line.'
                                        AND detail_pending_wet_finishing.jenis_pending != \'SETTING \'
                                        GROUP BY detail_pending_wet_finishing.kategori_pending, lhp_produksi2.grup, master_pic_line.nama_pic, lhp_produksi2.shift, lhp_produksi2.tanggal_produksi
                                        ORDER BY SUM(detail_pending_wet_finishing.qty_pending) DESC
                                        
                                    ');
        }

        return $query->getResultArray();
    }

    public function get_battery_reject_by_grup_shift($grup,$shift,$date,$line)
    {
        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT lhp_produksi2.tanggal_produksi, lhp_produksi2.grup, master_pic_line.nama_pic, lhp_produksi2.shift, detail_pending_wet_finishing.type_battery, SUM(detail_pending_wet_finishing.qty_pending) as qty
                                        FROM detail_pending_wet_finishing
                                        JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_pending_wet_finishing.id_lhp
                                        JOIN master_pic_line on master_pic_line.id_pic = lhp_produksi2.grup
                                        WHERE lhp_produksi2.tanggal_produksi = \''.$date.'\'
                                        AND master_pic_line.nama_pic = \''.$grup.'\'
                                        AND lhp_produksi2.shift = \''.$shift.'\'
                                        AND lhp_produksi2.line >= 8 AND lhp_produksi2.line <= 9
                                        AND detail_pending_wet_finishing.jenis_pending != \'SETTING \'
                                        GROUP BY detail_pending_wet_finishing.type_battery, lhp_produksi2.grup, master_pic_line.nama_pic, lhp_produksi2.shift, lhp_produksi2.tanggal_produksi
                                        ORDER BY SUM(detail_pending_wet_finishing.qty_pending) DESC
                                    ');
        } else {
            $query = $this->db->query('SELECT lhp_produksi2.tanggal_produksi, lhp_produksi2.grup, master_pic_line.nama_pic, lhp_produksi2.shift, detail_pending_wet_finishing.type_battery, SUM(detail_pending_wet_finishing.qty_pending) as qty
                                        FROM detail_pending_wet_finishing
                                        JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_pending_wet_finishing.id_lhp
                                        JOIN master_pic_line on master_pic_line.id_pic = lhp_produksi2.grup
                                        WHERE lhp_produksi2.tanggal_produksi = \''.$date.'\'
                                        AND master_pic_line.nama_pic = \''.$grup.'\'
                                        AND lhp_produksi2.shift = \''.$shift.'\'
                                        AND lhp_produksi2.line = '.$line.'
                                        AND detail_pending_wet_finishing.jenis_pending != \'SETTING \'
                                        GROUP BY detail_pending_wet_finishing.type_battery, lhp_produksi2.grup, master_pic_line.nama_pic, lhp_produksi2.shift, lhp_produksi2.tanggal_produksi
                                        ORDER BY SUM(detail_pending_wet_finishing.qty_pending) DESC
                                        
                                    ');
        }

        return $query->getResultArray();
    }

    public function get_detail_summary_rejection($jenis_reject, $date, $line)
    {
        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT lhp_produksi2.tanggal_produksi, detail_pending_wet_finishing.kategori_pending, detail_pending_wet_finishing.type_battery,master_pic_line.nama_pic, lhp_produksi2.shift, SUM(detail_pending_wet_finishing.qty_pending) as qty
                                        FROM detail_pending_wet_finishing
                                        JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_pending_wet_finishing.id_lhp
                                        JOIN master_pic_line on master_pic_line.id_pic = lhp_produksi2.grup
                                        WHERE lhp_produksi2.tanggal_produksi = \''.$date.'\'
                                        AND detail_pending_wet_finishing.jenis_pending = \''.$jenis_reject.'\'
                                        AND lhp_produksi2.line >= 8 AND lhp_produksi2.line <= 9
                                        AND detail_pending_wet_finishing.jenis_pending != \'SETTING \'
                                        GROUP BY detail_pending_wet_finishing.type_battery, detail_pending_wet_finishing.kategori_pending, detail_pending_wet_finishing.jenis_pending, lhp_produksi2.tanggal_produksi,master_pic_line.nama_pic, lhp_produksi2.shift
                                        ORDER BY detail_pending_wet_finishing.kategori_pending ASC, SUM(detail_pending_wet_finishing.qty_pending) DESC
                                    ');
        } else {
            $query = $this->db->query('SELECT lhp_produksi2.tanggal_produksi, detail_pending_wet_finishing.kategori_pending, detail_pending_wet_finishing.type_battery,master_pic_line.nama_pic, lhp_produksi2.shift, SUM(detail_pending_wet_finishing.qty_pending) as qty
                                        FROM detail_pending_wet_finishing
                                        JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_pending_wet_finishing.id_lhp
                                        JOIN master_pic_line on master_pic_line.id_pic = lhp_produksi2.grup
                                        WHERE lhp_produksi2.tanggal_produksi = \''.$date.'\'
                                        AND detail_pending_wet_finishing.jenis_pending = \''.$jenis_reject.'\'
                                        AND lhp_produksi2.line = '.$line.'
                                        AND detail_pending_wet_finishing.jenis_pending != \'SETTING \'
                                        GROUP BY detail_pending_wet_finishing.type_battery, detail_pending_wet_finishing.kategori_pending, detail_pending_wet_finishing.jenis_pending, lhp_produksi2.tanggal_produksi,master_pic_line.nama_pic, lhp_produksi2.shift
                                        ORDER BY detail_pending_wet_finishing.kategori_pending ASC, SUM(detail_pending_wet_finishing.qty_pending) DESC
                                    ');
        }

        return $query->getResultArray();
    }

    public function get_detail_summary_rejection_by_month($jenis_reject, $month, $line)
    {
        $bulan = idate('m', strtotime($month));
        $year = date('Y', strtotime($month));
        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT lhp_produksi2.tanggal_produksi, detail_pending_wet_finishing.kategori_pending, detail_pending_wet_finishing.type_battery,master_pic_line.nama_pic, lhp_produksi2.shift, SUM(detail_pending_wet_finishing.qty_pending) as qty
                                        FROM detail_pending_wet_finishing
                                        JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_pending_wet_finishing.id_lhp
                                        JOIN master_pic_line on master_pic_line.id_pic = lhp_produksi2.grup
                                        WHERE MONTH(lhp_produksi2.tanggal_produksi) = '.$bulan.' AND YEAR(lhp_produksi2.tanggal_produksi) = '.$year.'
                                        AND detail_pending_wet_finishing.jenis_pending = \''.$jenis_reject.'\'
                                        AND lhp_produksi2.line >= 8 AND lhp_produksi2.line <= 9
                                        AND detail_pending_wet_finishing.jenis_pending != \'SETTING \'
                                        GROUP BY detail_pending_wet_finishing.type_battery, detail_pending_wet_finishing.kategori_pending, detail_pending_wet_finishing.jenis_pending, lhp_produksi2.tanggal_produksi,master_pic_line.nama_pic, lhp_produksi2.shift
                                        ORDER BY detail_pending_wet_finishing.kategori_pending ASC, SUM(detail_pending_wet_finishing.qty_pending) DESC
                                    ');
        } else {
            $query = $this->db->query('SELECT lhp_produksi2.tanggal_produksi, detail_pending_wet_finishing.kategori_pending, detail_pending_wet_finishing.type_battery,master_pic_line.nama_pic, lhp_produksi2.shift, SUM(detail_pending_wet_finishing.qty_pending) as qty
                                        FROM detail_pending_wet_finishing
                                        JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_pending_wet_finishing.id_lhp
                                        JOIN master_pic_line on master_pic_line.id_pic = lhp_produksi2.grup
                                        WHERE MONTH(lhp_produksi2.tanggal_produksi) = '.$bulan.' AND YEAR(lhp_produksi2.tanggal_produksi) = '.$year.'
                                        AND detail_pending_wet_finishing.jenis_pending = \''.$jenis_reject.'\'
                                        AND lhp_produksi2.line = '.$line.'
                                        AND detail_pending_wet_finishing.jenis_pending != \'SETTING \'
                                        GROUP BY detail_pending_wet_finishing.type_battery, detail_pending_wet_finishing.kategori_pending, detail_pending_wet_finishing.jenis_pending, lhp_produksi2.tanggal_produksi,master_pic_line.nama_pic, lhp_produksi2.shift
                                        ORDER BY detail_pending_wet_finishing.kategori_pending ASC, SUM(detail_pending_wet_finishing.qty_pending) DESC
                                    ');
        }

        return $query->getResultArray();
    }

    public function get_data_grup_by_line($month, $line) 
    {
        $bulan = idate('m', strtotime($month));
        $year = date('Y', strtotime($month));
        if($line == '') {
            $query = $this->db->query('SELECT DISTINCT(master_pic_line.nama_pic)
                                        FROM		lhp_produksi2
                                        JOIN        master_pic_line ON master_pic_line.id_pic = lhp_produksi2.grup
                                        WHERE		MONTH(lhp_produksi2.tanggal_produksi) = '.$bulan.' AND YEAR(lhp_produksi2.tanggal_produksi) = '.$year.' AND (lhp_produksi2.line >= 8 AND lhp_produksi2.line <= 9)
                                        GROUP BY	master_pic_line.nama_pic');
        } else {
            $query = $this->db->query('SELECT DISTINCT(master_pic_line.nama_pic)
                                        FROM		lhp_produksi2
                                        JOIN        master_pic_line ON master_pic_line.id_pic = lhp_produksi2.grup
                                        WHERE		MONTH(lhp_produksi2.tanggal_produksi) = '.$bulan.' AND YEAR(lhp_produksi2.tanggal_produksi) = '.$year.' AND lhp_produksi2.line = '.$line.'
                                        GROUP BY	master_pic_line.nama_pic');

        }

        return $query->getResultArray();
    }

    public function get_data_grup_by_line_year($month, $line) 
    {
        $tahun = date('Y', strtotime($month));
        if($line == '') {
            $query = $this->db->query('SELECT DISTINCT(master_pic_line.nama_pic)
                                        FROM		lhp_produksi2
                                        JOIN        master_pic_line ON master_pic_line.id_pic = lhp_produksi2.grup
                                        WHERE		YEAR(lhp_produksi2.tanggal_produksi) = '.$tahun.' AND (lhp_produksi2.line >= 8 AND lhp_produksi2.line <= 9)
                                        GROUP BY	master_pic_line.nama_pic');
        } else {
            $query = $this->db->query('SELECT DISTINCT(master_pic_line.nama_pic)
                                        FROM		lhp_produksi2
                                        JOIN        master_pic_line ON master_pic_line.id_pic = lhp_produksi2.grup
                                        WHERE		YEAR(lhp_produksi2.tanggal_produksi) = '.$tahun.' AND lhp_produksi2.line = '.$line.'
                                        GROUP BY	master_pic_line.nama_pic');
        }

        return $query->getResultArray();
    }

    public function get_data_line_by_grup($tanggal, $line, $grup)
    {
        if($line == '') {
            $query = $this->db->query('SELECT lhp_produksi2.tanggal_produksi, master_pic_line.nama_pic ,SUM(detail_pending_wet_finishing.qty_pending) AS qty_pending,  (
                                            SELECT SUM(total_aktual) 
                                            FROM lhp_produksi2
                                            WHERE tanggal_produksi = \''.$tanggal.'\'
                                            AND line >= 8 AND line <= 9
                                            GROUP BY tanggal_produksi
                                        ) AS total_aktual
                                        FROM        lhp_produksi2
                                        JOIN        detail_pending_wet_finishing on lhp_produksi2.id_lhp_2 = detail_pending_wet_finishing.id_lhp
                                        JOIN        master_pic_line ON master_pic_line.id_pic = lhp_produksi2.grup
                                        WHERE		lhp_produksi2.tanggal_produksi = \''.$tanggal.'\' AND (lhp_produksi2.line >= 8 AND lhp_produksi2.line <= 9) AND master_pic_line.nama_pic = \''.$grup.'\'
                                        GROUP BY	lhp_produksi2.tanggal_produksi, master_pic_line.nama_pic');
        } else {
            $query = $this->db->query('SELECT lhp_produksi2.tanggal_produksi, lhp_produksi2.line, master_pic_line.nama_pic ,SUM(detail_pending_wet_finishing.qty_pending) AS qty_pending,  (
                                            SELECT SUM(total_aktual) 
                                            FROM lhp_produksi2
                                            WHERE tanggal_produksi = \''.$tanggal.'\'
                                            AND line = '.$line.'
                                            GROUP BY tanggal_produksi
                                        ) AS total_aktual
                                        FROM        lhp_produksi2
                                        JOIN        detail_pending_wet_finishing on lhp_produksi2.id_lhp_2 = detail_pending_wet_finishing.id_lhp
                                        JOIN        master_pic_line ON master_pic_line.id_pic = lhp_produksi2.grup
                                        WHERE		lhp_produksi2.tanggal_produksi = \''.$tanggal.'\' AND lhp_produksi2.line = '.$line.' AND master_pic_line.nama_pic = \''.$grup.'\'
                                        GROUP BY	lhp_produksi2.tanggal_produksi, lhp_produksi2.line, master_pic_line.nama_pic');
        }

        return $query->getResultArray();
    }

    public function get_data_line_by_grup_month($month, $bulan, $line, $grup)
    {
        $year = date('Y', strtotime($month));
        if($line == '') {
            $query = $this->db->query('SELECT MONTH(lhp_produksi2.tanggal_produksi), master_pic_line.nama_pic ,SUM(detail_pending_wet_finishing.qty_pending) AS qty_pending,  (
                                            SELECT SUM(total_aktual) 
                                            FROM lhp_produksi2
                                            WHERE MONTH(tanggal_produksi) = '.$bulan.' AND YEAR(tanggal_produksi) = '.$year.'
                                            AND line >= 8 AND line <= 9
                                            GROUP BY MONTH(tanggal_produksi)
                                        ) AS total_aktual
                                        FROM        lhp_produksi2
                                        JOIN        detail_pending_wet_finishing on lhp_produksi2.id_lhp_2 = detail_pending_wet_finishing.id_lhp
                                        JOIN        master_pic_line ON master_pic_line.id_pic = lhp_produksi2.grup
                                        WHERE		MONTH(lhp_produksi2.tanggal_produksi) = '.$bulan.' AND YEAR(lhp_produksi2.tanggal_produksi) = '.$year.' AND (lhp_produksi2.line >= 8 AND lhp_produksi2.line <= 9) AND master_pic_line.nama_pic = \''.$grup.'\'
                                        GROUP BY	lhp_produksi2.tanggal_produksi, master_pic_line.nama_pic');
        } else {
            $query = $this->db->query('SELECT MONTH(lhp_produksi2.tanggal_produksi), master_pic_line.nama_pic ,SUM(detail_pending_wet_finishing.qty_pending) AS qty_pending,  (
                                            SELECT SUM(total_aktual) 
                                            FROM lhp_produksi2
                                            WHERE MONTH(tanggal_produksi) = '.$bulan.' AND YEAR(tanggal_produksi) = '.$year.'
                                            AND line = '.$line.'
                                            GROUP BY MONTH(tanggal_produksi)
                                        ) AS total_aktual
                                        FROM        lhp_produksi2
                                        JOIN        detail_pending_wet_finishing on lhp_produksi2.id_lhp_2 = detail_pending_wet_finishing.id_lhp
                                        JOIN        master_pic_line ON master_pic_line.id_pic = lhp_produksi2.grup
                                        WHERE		MONTH(lhp_produksi2.tanggal_produksi) = '.$bulan.' AND YEAR(lhp_produksi2.tanggal_produksi) = '.$year.' AND lhp_produksi2.line = '.$line.' AND master_pic_line.nama_pic = \''.$grup.'\'
                                        GROUP BY	lhp_produksi2.tanggal_produksi, master_pic_line.nama_pic');
        }

        return $query->getResultArray();
    }

    public function get_data_kss_by_line($month, $line) 
    {
        $bulan = idate('m', strtotime($month));
        $year = date('Y', strtotime($month));
        $query = $this->db->query('SELECT DISTINCT(kasubsie)
                                    FROM		lhp_produksi2
                                    WHERE		MONTH(tanggal_produksi) = '.$bulan.' AND YEAR(tanggal_produksi) = '.$year.' AND line = '.$line);

        return $query->getResultArray();
    }

    public function get_data_kss_by_line_year($month, $line) 
    {
        $tahun = date('Y', strtotime($month));
        $query = $this->db->query('SELECT DISTINCT(kasubsie)
                                    FROM		lhp_produksi2
                                    WHERE		YEAR(tanggal_produksi) = '.$tahun.' AND line = '.$line);

        return $query->getResultArray();
    }

    public function get_data_line_by_kss($tanggal, $line, $kss)
    {
        $query = $this->db->query('SELECT tanggal_produksi, line, kasubsie ,SUM(detail_pending_wet_finishing.qty_pending) AS qty_pending,  (
                                        SELECT SUM(total_aktual) 
                                        FROM lhp_produksi2
                                        WHERE tanggal_produksi = \''.$tanggal.'\'
                                        AND line = '.$line.' AND kasubsie = \''.$kss.'\'
                                        GROUP BY MONTH(tanggal_produksi)
                                    ) AS total_aktual
                                    FROM        lhp_produksi2
                                    JOIN        detail_pending_wet_finishing on lhp_produksi2.id_lhp_2 = detail_pending_wet_finishing.id_lhp
                                    WHERE		tanggal_produksi = \''.$tanggal.'\' AND line = '.$line.' AND kasubsie = \''.$kss.'\'
                                    GROUP BY	tanggal_produksi, line, kasubsie');

        return $query->getResultArray();
    }

    public function get_data_line_by_kss_month($month, $bulan, $line, $kss)
    {
        $year = date('Y', strtotime($month));
        $query = $this->db->query('SELECT MONTH(tanggal_produksi), line, kasubsie ,SUM(detail_pending_wet_finishing.qty_pending) AS qty_pending,  (
                                        SELECT SUM(total_aktual) 
                                        FROM lhp_produksi2
                                        WHERE MONTH(tanggal_produksi) = '.$bulan.' AND YEAR(tanggal_produksi) = '.$year.'
                                        AND line = '.$line.' AND kasubsie = \''.$kss.'\'
                                        GROUP BY MONTH(tanggal_produksi)
                                    ) AS total_aktual
                                    FROM        lhp_produksi2
                                    JOIN        detail_pending_wet_finishing on lhp_produksi2.id_lhp_2 = detail_pending_wet_finishing.id_lhp
                                    WHERE		MONTH(tanggal_produksi) = \''.$bulan.'\' AND YEAR(tanggal_produksi) = \''.$year.'\' AND line = '.$line.' AND kasubsie = \''.$kss.'\'
                                    GROUP BY	tanggal_produksi, line, kasubsie');

        return $query->getResultArray();
    }
}