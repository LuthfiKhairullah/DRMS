<?php 
namespace App\Models;
use CodeIgniter\Model;



class M_DashboardAssyLineStop extends Model
{
    public function __construct()
    {
        $this->db = \Config\Database::connect();

        $this->session = \Config\Services::session();
    }

    // public function get_data_line_stop_by_month($bulan) 
    // {
    //     $query = $this->db->query('SELECT MONTH(lhp_produksi2.tanggal_produksi), detail_breakdown.jenis_breakdown, SUM(detail_breakdown.menit_breakdown) as qty
    //                                 FROM detail_breakdown 
    //                                 RIGHT JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_breakdown.id_lhp
    //                                 WHERE MONTH(lhp_produksi2.tanggal_produksi) = '.$bulan.'
    //                                 GROUP BY detail_breakdown.jenis_breakdown, MONTH(lhp_produksi2.tanggal_produksi)
    //                                 ORDER BY MONTH(lhp_produksi2.tanggal_produksi)
    //                             ');
    //     return $query->getResultArray();
    // }

    public function get_data_total_line_stop_by_month($month, $line)
    {
        $bulan = idate('m', strtotime($month));
        $year = date('Y', strtotime($month));
        if ($line == null OR $line == 0) {
            $query = $this->db->query('SELECT detail_breakdown.jenis_breakdown, SUM(detail_breakdown.menit_breakdown) as qty,
                                        (
                                            SELECT SUM(lhp_produksi2.loading_time)
                                            FROM lhp_produksi2
                                            WHERE MONTH(lhp_produksi2.tanggal_produksi) = '.$bulan.' AND YEAR(lhp_produksi2.tanggal_produksi) = '.$year.'
                                            AND lhp_produksi2.line <= 7
                                        ) AS loading_time
                                    FROM detail_breakdown 
                                    JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_breakdown.id_lhp
                                    WHERE MONTH(lhp_produksi2.tanggal_produksi) = '.$bulan.' AND YEAR(lhp_produksi2.tanggal_produksi) = '.$year.'
                                    AND lhp_produksi2.line <= 7
                                    GROUP BY detail_breakdown.jenis_breakdown
                                    ORDER BY SUM(detail_breakdown.menit_breakdown) DESC
                                ');
        } else {
            $query = $this->db->query('SELECT detail_breakdown.jenis_breakdown, SUM(detail_breakdown.menit_breakdown) as qty,
                                        (
                                            SELECT SUM(lhp_produksi2.loading_time)
                                            FROM lhp_produksi2
                                            WHERE MONTH(lhp_produksi2.tanggal_produksi) = '.$bulan.' AND YEAR(lhp_produksi2.tanggal_produksi) = '.$year.'
                                            AND lhp_produksi2.line = '.$line.'
                                        ) AS loading_time
                                    FROM detail_breakdown 
                                    JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_breakdown.id_lhp
                                    WHERE MONTH(lhp_produksi2.tanggal_produksi) = '.$bulan.' AND YEAR(lhp_produksi2.tanggal_produksi) = '.$year.'
                                    AND lhp_produksi2.line = '.$line.'
                                    GROUP BY detail_breakdown.jenis_breakdown
                                    ORDER BY SUM(detail_breakdown.menit_breakdown) DESC
                                ');
        }
        
        return $query->getResultArray();
    }

    // public function get_total_data_line_stop_by_month($bulan) 
    // {
    //     $query = $this->db->query('SELECT MONTH(lhp_produksi2.tanggal_produksi), SUM(detail_breakdown.menit_breakdown) as qty
    //                                 FROM detail_breakdown
    //                                 JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_breakdown.id_lhp
    //                                 WHERE MONTH(lhp_produksi2.tanggal_produksi) = '.$bulan.'
    //                                 GROUP BY MONTH(lhp_produksi2.tanggal_produksi)
    //                                 ORDER BY MONTH(lhp_produksi2.tanggal_produksi)
    //                             ');
    //     return $query->getResultArray();
    // }

    // public function get_loading_time_by_month($bulan) 
    // {
    //     $query = $this->db->query('SELECT MONTH(lhp_produksi2.tanggal_produksi), SUM(lhp_produksi2.loading_time) as loading_time
    //                                 FROM detail_breakdown
    //                                 RIGHT JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_breakdown.id_lhp
    //                                 WHERE MONTH(lhp_produksi2.tanggal_produksi) = '.$bulan.'
    //                                 GROUP BY MONTH(lhp_produksi2.tanggal_produksi)
    //                                 ORDER BY MONTH(lhp_produksi2.tanggal_produksi)
    //                             ');
    //     return $query->getResultArray();
    // }

    public function get_loading_time_by_month($month, $line) 
    {
        $bulan = idate('m', strtotime($month));
        $year = date('Y', strtotime($month));
        if ($line == null OR $line == 0) {
            $query = $this->db->query('SELECT MONTH(tanggal_produksi), SUM(loading_time) as loading_time
                                        FROM lhp_produksi2
                                        WHERE MONTH(tanggal_produksi) = '.$bulan.' AND YEAR(tanggal_produksi) = '.$year.'
                                        AND line <= 7
                                        GROUP BY MONTH(tanggal_produksi)
                                    ');
        } else {
            $query = $this->db->query('SELECT MONTH(tanggal_produksi), SUM(loading_time) as loading_time
                                        FROM lhp_produksi2
                                        WHERE MONTH(tanggal_produksi) = '.$bulan.' AND YEAR(tanggal_produksi) = '.$year.'
                                        AND line = '.$line.'
                                        GROUP BY MONTH(tanggal_produksi)
                                    ');
        }

        return $query->getResultArray();
    }

    public function get_data_line_stop_by_date($tanggal, $jenis_breakdown, $line)
    {
        if ($line == null OR $line == 0) {
            $query = $this->db->query('SELECT		lhp_produksi2.tanggal_produksi, detail_breakdown.jenis_breakdown, SUM(detail_breakdown.menit_breakdown) as qty
                                        FROM		detail_breakdown
                                        JOIN		lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_breakdown.id_lhp
                                        WHERE		lhp_produksi2.tanggal_produksi = \''.$tanggal.'\'
                                        AND         detail_breakdown.jenis_breakdown = \''.$jenis_breakdown.'\'
                                        AND         lhp_produksi2.line <= 7
                                        GROUP BY	detail_breakdown.jenis_breakdown, lhp_produksi2.tanggal_produksi
                                    ');
        } else {
            $query = $this->db->query('SELECT		lhp_produksi2.tanggal_produksi, detail_breakdown.jenis_breakdown, SUM(detail_breakdown.menit_breakdown) as qty
                                        FROM		detail_breakdown
                                        JOIN		lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_breakdown.id_lhp
                                        WHERE		lhp_produksi2.tanggal_produksi = \''.$tanggal.'\'
                                        AND         detail_breakdown.jenis_breakdown = \''.$jenis_breakdown.'\'
                                        AND         lhp_produksi2.line = '.$line.'
                                        GROUP BY	detail_breakdown.jenis_breakdown, lhp_produksi2.tanggal_produksi
                                    ');
        }
        
        return $query->getResultArray();
    }

    public function get_data_proses_breakdown_by_date($tanggal, $jenis_breakdown, $proses_breakdown)
    {
        $query = $this->db->query('SELECT		lhp_produksi2.tanggal_produksi, detail_breakdown.jenis_breakdown, detail_breakdown.proses_breakdown, SUM(detail_breakdown.menit_breakdown) as qty
                                    FROM		detail_breakdown
                                    JOIN		lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_breakdown.id_lhp
                                    WHERE		lhp_produksi2.tanggal_produksi = \''.$tanggal.'\'
                                    AND         detail_breakdown.jenis_breakdown = \''.$jenis_breakdown.'\'
                                    AND         detail_breakdown.proses_breakdown = \''.$proses_breakdown.'\'
                                    GROUP BY	detail_breakdown.proses_breakdown, detail_breakdown.jenis_breakdown, lhp_produksi2.tanggal_produksi
                                ');
        return $query->getResultArray();
    }

    public function get_jenis_line_stop_by_month($month, $line) 
    {
        $bulan = idate('m', strtotime($month));
        $year = date('Y', strtotime($month));
        if ($line == null OR $line == 0) {
            $query = $this->db->query('SELECT	DISTINCT(detail_breakdown.jenis_breakdown)
                                    FROM	detail_breakdown
                                    JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_breakdown.id_lhp
                                    AND lhp_produksi2.line <= 7
                                    WHERE MONTH(lhp_produksi2.tanggal_produksi) = '.$bulan.'
                                ');
        } else {
            $query = $this->db->query('SELECT	DISTINCT(detail_breakdown.jenis_breakdown)
                                    FROM	detail_breakdown
                                    JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_breakdown.id_lhp
                                    WHERE MONTH(lhp_produksi2.tanggal_produksi) = '.$bulan.'
                                    AND lhp_produksi2.line = '.$line.'
                                ');
        }

        return $query->getResultArray();
    }

    public function get_proses_breakdown_by_month($month, $jenis_breakdown) 
    {
        $bulan = idate('m', strtotime($month));
        $query = $this->db->query('SELECT	DISTINCT(detail_breakdown.proses_breakdown)
                                    FROM	detail_breakdown
                                    JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_breakdown.id_lhp
                                    WHERE MONTH(lhp_produksi2.tanggal_produksi) = '.$bulan.'
                                    AND detail_breakdown.jenis_breakdown = \''.$jenis_breakdown.'\'
                                ');

        return $query->getResultArray();
    }

    public function get_data_total_line_stop_line_by_month($month) 
    {
        $bulan = idate('m', strtotime($month));
        $year = date('Y', strtotime($month));
        $query = $this->db->query('SELECT	lhp_produksi2.line, SUM(lhp_produksi2.total_line_stop) as total_line_stop, SUM(lhp_produksi2.loading_time) as loading_time, 
                                            ((SUM(lhp_produksi2.total_line_stop) / CAST(SUM(lhp_produksi2.loading_time) as float)) * 100) as persen
                                    FROM	detail_breakdown
                                    RIGHT JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_breakdown.id_lhp
                                    WHERE MONTH(lhp_produksi2.tanggal_produksi) = '.$bulan.' AND YEAR(lhp_produksi2.tanggal_produksi) = '.$year.' 
                                    AND lhp_produksi2.line <= 7
                                    GROUP BY lhp_produksi2.line
                                    ORDER BY ((SUM(lhp_produksi2.total_line_stop) / CAST(SUM(lhp_produksi2.loading_time) as float)) * 100) DESC
                                ');

        return $query->getResultArray();
    }

    public function get_data_line_stop_by_month($month, $bulan, $line)
    {
        $year = date('Y', strtotime($month));
        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT	MONTH(lhp_produksi2.tanggal_produksi), SUM(lhp_produksi2.total_line_stop) as total_line_stop, SUM(lhp_produksi2.loading_time) as loading_time
                                        FROM	detail_breakdown
                                        RIGHT JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_breakdown.id_lhp
                                        WHERE MONTH(lhp_produksi2.tanggal_produksi) = '.$bulan.' AND YEAR(lhp_produksi2.tanggal_produksi) = '.$year.'
                                        AND lhp_produksi2.line <= 7
                                        GROUP BY MONTH(lhp_produksi2.tanggal_produksi)
                                    ');
        } else {
            $query = $this->db->query('SELECT	MONTH(lhp_produksi2.tanggal_produksi), SUM(lhp_produksi2.total_line_stop) as total_line_stop, SUM(lhp_produksi2.loading_time) as loading_time
                                        FROM	detail_breakdown
                                        RIGHT JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_breakdown.id_lhp
                                        WHERE MONTH(lhp_produksi2.tanggal_produksi) = '.$bulan.' AND YEAR(lhp_produksi2.tanggal_produksi) = '.$year.'
                                        AND lhp_produksi2.line = '.$line.'
                                        GROUP BY MONTH(lhp_produksi2.tanggal_produksi)
                                    ');
        }

        return $query->getResultArray();
    }

    public function get_year_to_date_line_stop($month, $line)
    {
        $tahun = date('Y', strtotime($month));

        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT YEAR(tanggal_produksi) AS month,SUM(total_line_stop) AS total_line_stop, SUM(loading_time) AS loading_time
                                        FROM lhp_produksi2 
                                        WHERE YEAR(tanggal_produksi) = '.$tahun.'
                                        AND line <= 7
                                        GROUP BY YEAR(tanggal_produksi)
                                        ORDER BY YEAR(tanggal_produksi)
                                    ');
        } else {
            $query = $this->db->query('SELECT YEAR(tanggal_produksi) AS month,SUM(total_line_stop) AS total_line_stop, SUM(loading_time) AS loading_time
                                        FROM lhp_produksi2 
                                        WHERE YEAR(tanggal_produksi) = '.$tahun.' AND line = '.$line.'
                                        GROUP BY YEAR(tanggal_produksi)
                                        ORDER BY YEAR(tanggal_produksi)
                                    ');
        }

        return $query->getResultArray();
    }

    public function get_detail_line_stop_by_jenis($jenis_breakdown, $date, $line)
    {
        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT lhp_produksi2.tanggal_produksi, detail_breakdown.jenis_breakdown, 
                                        CASE
											WHEN CHARINDEX(\',\', SUBSTRING(detail_breakdown.proses_breakdown, CHARINDEX(\'-\', detail_breakdown.proses_breakdown, CHARINDEX(\'-\', detail_breakdown.proses_breakdown, CHARINDEX(\'-\', detail_breakdown.proses_breakdown) + 1) + 1) + 1, LEN(detail_breakdown.proses_breakdown))) > 0 
											THEN \'Dandori\'
											ELSE SUBSTRING(detail_breakdown.proses_breakdown, CHARINDEX(\'-\', detail_breakdown.proses_breakdown, CHARINDEX(\'-\', detail_breakdown.proses_breakdown, CHARINDEX(\'-\', detail_breakdown.proses_breakdown) + 1) + 1) + 1, LEN(detail_breakdown.proses_breakdown))
										END AS proses_breakdown, SUM(detail_breakdown.menit_breakdown) as qty
                                        FROM detail_breakdown
                                        JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_breakdown.id_lhp
                                        WHERE lhp_produksi2.tanggal_produksi = \''.$date.'\'
                                        AND detail_breakdown.jenis_breakdown = \''.$jenis_breakdown.'\'
                                        AND lhp_produksi2.line <= 7
                                        GROUP BY 
                                        CASE
											WHEN CHARINDEX(\',\', SUBSTRING(detail_breakdown.proses_breakdown, CHARINDEX(\'-\', detail_breakdown.proses_breakdown, CHARINDEX(\'-\', detail_breakdown.proses_breakdown, CHARINDEX(\'-\', detail_breakdown.proses_breakdown) + 1) + 1) + 1, LEN(detail_breakdown.proses_breakdown))) > 0 
											THEN \'Dandori\'
											ELSE SUBSTRING(detail_breakdown.proses_breakdown, CHARINDEX(\'-\', detail_breakdown.proses_breakdown, CHARINDEX(\'-\', detail_breakdown.proses_breakdown, CHARINDEX(\'-\', detail_breakdown.proses_breakdown) + 1) + 1) + 1, LEN(detail_breakdown.proses_breakdown))
										END, detail_breakdown.jenis_breakdown, lhp_produksi2.tanggal_produksi
                                        ORDER BY  SUM(detail_breakdown.menit_breakdown) DESC
                                    ');
        } else {
            $query = $this->db->query('SELECT lhp_produksi2.tanggal_produksi, detail_breakdown.jenis_breakdown,
                                        CASE
											WHEN CHARINDEX(\',\', SUBSTRING(detail_breakdown.proses_breakdown, CHARINDEX(\'-\', detail_breakdown.proses_breakdown, CHARINDEX(\'-\', detail_breakdown.proses_breakdown, CHARINDEX(\'-\', detail_breakdown.proses_breakdown) + 1) + 1) + 1, LEN(detail_breakdown.proses_breakdown))) > 0 
											THEN \'Dandori\'
											ELSE SUBSTRING(detail_breakdown.proses_breakdown, CHARINDEX(\'-\', detail_breakdown.proses_breakdown, CHARINDEX(\'-\', detail_breakdown.proses_breakdown, CHARINDEX(\'-\', detail_breakdown.proses_breakdown) + 1) + 1) + 1, LEN(detail_breakdown.proses_breakdown))
										END AS proses_breakdown, SUM(detail_breakdown.menit_breakdown) as qty
                                        FROM detail_breakdown
                                        JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_breakdown.id_lhp
                                        WHERE lhp_produksi2.tanggal_produksi = \''.$date.'\'
                                        AND detail_breakdown.jenis_breakdown = \''.$jenis_breakdown.'\'
                                        AND lhp_produksi2.line = '.$line.'
                                        GROUP BY
                                        CASE
											WHEN CHARINDEX(\',\', SUBSTRING(detail_breakdown.proses_breakdown, CHARINDEX(\'-\', detail_breakdown.proses_breakdown, CHARINDEX(\'-\', detail_breakdown.proses_breakdown, CHARINDEX(\'-\', detail_breakdown.proses_breakdown) + 1) + 1) + 1, LEN(detail_breakdown.proses_breakdown))) > 0 
											THEN \'Dandori\'
											ELSE SUBSTRING(detail_breakdown.proses_breakdown, CHARINDEX(\'-\', detail_breakdown.proses_breakdown, CHARINDEX(\'-\', detail_breakdown.proses_breakdown, CHARINDEX(\'-\', detail_breakdown.proses_breakdown) + 1) + 1) + 1, LEN(detail_breakdown.proses_breakdown))
										END, detail_breakdown.jenis_breakdown, lhp_produksi2.tanggal_produksi
                                        ORDER BY  SUM(detail_breakdown.menit_breakdown) DESC
                                    ');
        }

        return $query->getResultArray();
    }

    public function get_detail_line_stop_by_type_battery($jenis_breakdown, $date, $line)
    {
        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT lhp_produksi2.tanggal_produksi, detail_breakdown.type_battery, detail_breakdown.jenis_breakdown, detail_breakdown.proses_breakdown, SUM(detail_breakdown.menit_breakdown) as qty
                                        FROM detail_breakdown
                                        JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_breakdown.id_lhp
                                        WHERE lhp_produksi2.tanggal_produksi = \''.$date.'\'
                                        AND detail_breakdown.jenis_breakdown = \''.$jenis_breakdown.'\'
                                        AND lhp_produksi2.line <= 7
                                        GROUP BY detail_breakdown.type_battery, detail_breakdown.proses_breakdown, detail_breakdown.jenis_breakdown, lhp_produksi2.tanggal_produksi
                                        ORDER BY  SUM(detail_breakdown.menit_breakdown) DESC
                                    ');
        } else {
            $query = $this->db->query('SELECT lhp_produksi2.tanggal_produksi, detail_breakdown.type_battery, detail_breakdown.jenis_breakdown, detail_breakdown.proses_breakdown, SUM(detail_breakdown.menit_breakdown) as qty
                                        FROM detail_breakdown
                                        JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_breakdown.id_lhp
                                        WHERE lhp_produksi2.tanggal_produksi = \''.$date.'\'
                                        AND detail_breakdown.jenis_breakdown = \''.$jenis_breakdown.'\'
                                        AND lhp_produksi2.line = '.$line.'
                                        GROUP BY detail_breakdown.type_battery, detail_breakdown.proses_breakdown, detail_breakdown.jenis_breakdown, lhp_produksi2.tanggal_produksi
                                        ORDER BY  SUM(detail_breakdown.menit_breakdown) DESC
                                    ');
        }

        return $query->getResultArray();
    }

    public function get_data_line_stop_all_line_by_date($tanggal, $line)
    {
        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT tanggal_produksi, SUM(total_line_stop) AS total_line_stop, SUM(loading_time) AS loading_time
                                    FROM		lhp_produksi2
                                    WHERE		tanggal_produksi = \''.$tanggal.'\'
                                    AND         line <= 7
                                    GROUP BY	tanggal_produksi');
        } else {
            $query = $this->db->query('SELECT tanggal_produksi, SUM(total_line_stop) AS total_line_stop, SUM(loading_time) AS loading_time
                                    FROM		lhp_produksi2
                                    WHERE		tanggal_produksi = \''.$tanggal.'\' AND line = '.$line.'
                                    GROUP BY	tanggal_produksi');
        }
        
        return $query->getResultArray();
    }

    public function get_data_average_line_stop_by_month($month, $bulan, $line)
    {
        $year = date('Y', strtotime($month));
        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT MONTH(tanggal_produksi) AS month,SUM(total_line_stop) AS total_line_stop, SUM(loading_time) AS loading_time
                                        FROM lhp_produksi2 
                                        WHERE MONTH(tanggal_produksi) = '.$bulan.' AND YEAR(tanggal_produksi) = '.$year.'
                                        AND line <= 7
                                        GROUP BY MONTH(tanggal_produksi)
                                        ORDER BY MONTH(tanggal_produksi)
                                    ');
        } else {
            $query = $this->db->query('SELECT MONTH(tanggal_produksi) AS month,SUM(total_line_stop) AS total_line_stop, SUM(loading_time) AS loading_time
                                        FROM lhp_produksi2 
                                        WHERE MONTH(tanggal_produksi) = '.$bulan.' AND YEAR(tanggal_produksi) = '.$year.' AND line = '.$line.'
                                        GROUP BY MONTH(tanggal_produksi)
                                        ORDER BY MONTH(tanggal_produksi)
                                    ');
        }

        return $query->getResultArray();
    }

    public function get_data_line_stop_all_line($tanggal, $line)
    {
        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT tanggal_produksi, line, SUM(total_line_stop) AS total_line_stop, SUM(loading_time) AS loading_time
                                    FROM		lhp_produksi2
                                    WHERE		tanggal_produksi = \''.$tanggal.'\'
                                    AND         line <= 7
                                    GROUP BY	tanggal_produksi, line');
        } else {
            $query = $this->db->query('SELECT tanggal_produksi, line, SUM(total_line_stop) AS total_line_stop, SUM(loading_time) AS loading_time
                                    FROM		lhp_produksi2
                                    WHERE		tanggal_produksi = \''.$tanggal.'\' AND line = '.$line.'
                                    GROUP BY	tanggal_produksi, line');
        }
        

        return $query->getResultArray();
    }

    public function get_data_line_stop_all_line_by_month($month, $bulan, $line)
    {
        $year = date('Y', strtotime($month));
        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT MONTH(tanggal_produksi) AS month,SUM(total_line_stop) AS total_line_stop, SUM(loading_time) AS loading_time
                                        FROM lhp_produksi2 
                                        WHERE MONTH(tanggal_produksi) = '.$bulan.' AND YEAR(tanggal_produksi) = '.$year.'
                                        AND line <= 7
                                        GROUP BY MONTH(tanggal_produksi)
                                        ORDER BY MONTH(tanggal_produksi)
                                    ');
        } else {
            $query = $this->db->query('SELECT MONTH(tanggal_produksi) AS month,SUM(total_line_stop) AS total_line_stop, SUM(loading_time) AS loading_time
                                        FROM lhp_produksi2 
                                        WHERE MONTH(tanggal_produksi) = '.$bulan.' AND YEAR(tanggal_produksi) = '.$year.' AND line = '.$line.'
                                        GROUP BY MONTH(tanggal_produksi)
                                        ORDER BY MONTH(tanggal_produksi)
                                    ');
        }

        return $query->getResultArray();
    }

    public function get_qty_jenis_line_stop($tanggal, $line) 
    {
        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT lhp_produksi2.tanggal_produksi, detail_breakdown.jenis_breakdown, SUM(detail_breakdown.menit_breakdown) as qty
                                        FROM detail_breakdown
                                        JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_breakdown.id_lhp
                                        WHERE lhp_produksi2.tanggal_produksi = \''.$tanggal.'\'
                                        AND lhp_produksi2.line <= 7
                                        GROUP BY detail_breakdown.jenis_breakdown, lhp_produksi2.tanggal_produksi
                                        ORDER BY  SUM(detail_breakdown.menit_breakdown) DESC
                                    ');
        } else {
            $query = $this->db->query('SELECT lhp_produksi2.tanggal_produksi, detail_breakdown.jenis_breakdown, SUM(detail_breakdown.menit_breakdown) as qty
                                        FROM detail_breakdown
                                        JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_breakdown.id_lhp
                                        WHERE lhp_produksi2.tanggal_produksi = \''.$tanggal.'\'
                                        AND lhp_produksi2.line = '.$line.'
                                        GROUP BY detail_breakdown.jenis_breakdown, lhp_produksi2.tanggal_produksi
                                        ORDER BY SUM(detail_breakdown.menit_breakdown) DESC
                                    ');
        }

        return $query->getResultArray();
    }

    public function get_detail_line_stop_by_jenis_by_month($jenis_breakdown, $date, $line)
    {
        $bulan = idate('m', strtotime($date));
        $year = date('Y', strtotime($date));
        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT MONTH(lhp_produksi2.tanggal_produksi), detail_breakdown.jenis_breakdown,
                                        CASE
											WHEN CHARINDEX(\',\', SUBSTRING(detail_breakdown.proses_breakdown, CHARINDEX(\'-\', detail_breakdown.proses_breakdown, CHARINDEX(\'-\', detail_breakdown.proses_breakdown, CHARINDEX(\'-\', detail_breakdown.proses_breakdown) + 1) + 1) + 1, LEN(detail_breakdown.proses_breakdown))) > 0 
											THEN \'Dandori\'
											ELSE SUBSTRING(detail_breakdown.proses_breakdown, CHARINDEX(\'-\', detail_breakdown.proses_breakdown, CHARINDEX(\'-\', detail_breakdown.proses_breakdown, CHARINDEX(\'-\', detail_breakdown.proses_breakdown) + 1) + 1) + 1, LEN(detail_breakdown.proses_breakdown))
										END AS proses_breakdown, SUM(detail_breakdown.menit_breakdown) as qty
                                        FROM detail_breakdown
                                        JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_breakdown.id_lhp
                                        WHERE MONTH(lhp_produksi2.tanggal_produksi) = '.$bulan.' AND YEAR(lhp_produksi2.tanggal_produksi) = '.$year.'
                                        AND detail_breakdown.jenis_breakdown = \''.$jenis_breakdown.'\'
                                        AND lhp_produksi2.line <= 7
                                        GROUP BY CASE 
											WHEN CHARINDEX(\',\', SUBSTRING(detail_breakdown.proses_breakdown, CHARINDEX(\'-\', detail_breakdown.proses_breakdown, CHARINDEX(\'-\', detail_breakdown.proses_breakdown, CHARINDEX(\'-\', detail_breakdown.proses_breakdown) + 1) + 1) + 1, LEN(detail_breakdown.proses_breakdown))) > 0 
											THEN \'Dandori\'
											ELSE SUBSTRING(detail_breakdown.proses_breakdown, CHARINDEX(\'-\', detail_breakdown.proses_breakdown, CHARINDEX(\'-\', detail_breakdown.proses_breakdown, CHARINDEX(\'-\', detail_breakdown.proses_breakdown) + 1) + 1) + 1, LEN(detail_breakdown.proses_breakdown))
										END, detail_breakdown.jenis_breakdown, MONTH(lhp_produksi2.tanggal_produksi)
                                        ORDER BY  SUM(detail_breakdown.menit_breakdown) DESC
                                    ');
        } else {
            $query = $this->db->query('SELECT MONTH(lhp_produksi2.tanggal_produksi), detail_breakdown.jenis_breakdown,
                                        CASE
											WHEN CHARINDEX(\',\', SUBSTRING(detail_breakdown.proses_breakdown, CHARINDEX(\'-\', detail_breakdown.proses_breakdown, CHARINDEX(\'-\', detail_breakdown.proses_breakdown, CHARINDEX(\'-\', detail_breakdown.proses_breakdown) + 1) + 1) + 1, LEN(detail_breakdown.proses_breakdown))) > 0 
											THEN \'Dandori\'
											ELSE SUBSTRING(detail_breakdown.proses_breakdown, CHARINDEX(\'-\', detail_breakdown.proses_breakdown, CHARINDEX(\'-\', detail_breakdown.proses_breakdown, CHARINDEX(\'-\', detail_breakdown.proses_breakdown) + 1) + 1) + 1, LEN(detail_breakdown.proses_breakdown))
										END AS proses_breakdown, SUM(detail_breakdown.menit_breakdown) as qty
                                        FROM detail_breakdown
                                        JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_breakdown.id_lhp
                                        WHERE MONTH(lhp_produksi2.tanggal_produksi) = '.$bulan.' AND YEAR(lhp_produksi2.tanggal_produksi) = '.$year.'
                                        AND detail_breakdown.jenis_breakdown = \''.$jenis_breakdown.'\'
                                        AND lhp_produksi2.line = '.$line.'
                                        GROUP BY CASE 
											WHEN CHARINDEX(\',\', SUBSTRING(detail_breakdown.proses_breakdown, CHARINDEX(\'-\', detail_breakdown.proses_breakdown, CHARINDEX(\'-\', detail_breakdown.proses_breakdown, CHARINDEX(\'-\', detail_breakdown.proses_breakdown) + 1) + 1) + 1, LEN(detail_breakdown.proses_breakdown))) > 0 
											THEN \'Dandori\'
											ELSE SUBSTRING(detail_breakdown.proses_breakdown, CHARINDEX(\'-\', detail_breakdown.proses_breakdown, CHARINDEX(\'-\', detail_breakdown.proses_breakdown, CHARINDEX(\'-\', detail_breakdown.proses_breakdown) + 1) + 1) + 1, LEN(detail_breakdown.proses_breakdown))
										END, detail_breakdown.jenis_breakdown, MONTH(lhp_produksi2.tanggal_produksi)
                                        ORDER BY  SUM(detail_breakdown.menit_breakdown) DESC
                                    ');
        }

        return $query->getResultArray();
    }

    public function get_detail_line_stop_by_type_battery_by_month($jenis_breakdown, $date, $line)
    {
        $bulan = idate('m', strtotime($date));
        $year = date('Y', strtotime($date));
        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT MONTH(lhp_produksi2.tanggal_produksi), detail_breakdown.type_battery, detail_breakdown.jenis_breakdown, detail_breakdown.proses_breakdown, SUM(detail_breakdown.menit_breakdown) as qty
                                        FROM detail_breakdown
                                        JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_breakdown.id_lhp
                                        WHERE MONTH(lhp_produksi2.tanggal_produksi) = '.$bulan.' AND YEAR(lhp_produksi2.tanggal_produksi) = '.$year.' 
                                        AND detail_breakdown.jenis_breakdown = \''.$jenis_breakdown.'\'
                                        AND lhp_produksi2.line <= 7
                                        GROUP BY detail_breakdown.type_battery, detail_breakdown.proses_breakdown, detail_breakdown.jenis_breakdown, lhp_produksi2.tanggal_produksi
                                        ORDER BY  SUM(detail_breakdown.menit_breakdown) DESC
                                    ');
        } else {
            $query = $this->db->query('SELECT MONTH(lhp_produksi2.tanggal_produksi), detail_breakdown.type_battery, detail_breakdown.jenis_breakdown, detail_breakdown.proses_breakdown, SUM(detail_breakdown.menit_breakdown) as qty
                                        FROM detail_breakdown
                                        JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_breakdown.id_lhp
                                        WHERE MONTH(lhp_produksi2.tanggal_produksi) = '.$bulan.' AND YEAR(lhp_produksi2.tanggal_produksi) = '.$year.' 
                                        AND detail_breakdown.jenis_breakdown = \''.$jenis_breakdown.'\'
                                        AND lhp_produksi2.line = '.$line.'
                                        GROUP BY detail_breakdown.type_battery, detail_breakdown.proses_breakdown, detail_breakdown.jenis_breakdown, lhp_produksi2.tanggal_produksi
                                        ORDER BY  SUM(detail_breakdown.menit_breakdown) DESC
                                    ');
        }

        return $query->getResultArray();
    }

    public function get_all_detail_proses_breakdown_by_date($date, $line)
    {
        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT lhp_produksi2.tanggal_produksi, detail_breakdown.proses_breakdown, SUM(detail_breakdown.menit_breakdown) as qty
                                        FROM detail_breakdown
                                        JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_breakdown.id_lhp
                                        WHERE lhp_produksi2.tanggal_produksi = \''.$date.'\'
                                        AND lhp_produksi2.line <= 7
                                        GROUP BY detail_breakdown.proses_breakdown, lhp_produksi2.tanggal_produksi
                                        ORDER BY  SUM(detail_breakdown.menit_breakdown) DESC
                                    ');
        } else {
            $query = $this->db->query('SELECT lhp_produksi2.tanggal_produksi, detail_breakdown.proses_breakdown, SUM(detail_breakdown.menit_breakdown) as qty
                                        FROM detail_breakdown
                                        JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_breakdown.id_lhp
                                        WHERE lhp_produksi2.tanggal_produksi = \''.$date.'\'
                                        AND lhp_produksi2.line = '.$line.'
                                        GROUP BY detail_breakdown.proses_breakdown, detail_breakdown.jenis_breakdown, lhp_produksi2.tanggal_produksi
                                        ORDER BY  SUM(detail_breakdown.menit_breakdown) DESC
                                    ');
        }

        return $query->getResultArray();
    }

    public function get_all_detail_battery_line_stop_by_date($date, $line)
    {
        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT lhp_produksi2.tanggal_produksi, detail_breakdown.type_battery, SUM(detail_breakdown.menit_breakdown) as qty
                                        FROM detail_breakdown
                                        JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_breakdown.id_lhp
                                        WHERE lhp_produksi2.tanggal_produksi = \''.$date.'\'
                                        AND lhp_produksi2.line <= 7
                                        GROUP BY detail_breakdown.type_battery, lhp_produksi2.tanggal_produksi
                                        ORDER BY SUM(detail_breakdown.menit_breakdown) DESC
                                    ');
        } else {
            $query = $this->db->query('SELECT lhp_produksi2.tanggal_produksi), detail_breakdown.type_battery, SUM(detail_breakdown.menit_breakdown) as qty
                                        FROM detail_breakdown
                                        JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_breakdown.id_lhp
                                        WHERE lhp_produksi2.tanggal_produksi = \''.$date.'\'
                                        AND lhp_produksi2.line = '.$line.'
                                        GROUP BY detail_breakdown.type_battery, lhp_produksi2.tanggal_produksi
                                        ORDER BY  SUM(detail_breakdown.menit_breakdown) DESC
                                    ');
        }

        return $query->getResultArray();
    }

    public function get_all_detail_grup_line_stop_by_date($date, $line)
    {
        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT lhp_produksi2.tanggal_produksi, lhp_produksi2.grup, master_pic_line.nama_pic, lhp_produksi2.shift, SUM(lhp_produksi2.total_line_stop) as total_line_stop, SUM(lhp_produksi2.loading_time) as loading_time
                                        FROM detail_breakdown
                                        JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_breakdown.id_lhp
                                        JOIN master_pic_line on master_pic_line.id_pic = lhp_produksi2.grup
                                        WHERE lhp_produksi2.tanggal_produksi = \''.$date.'\'
                                        AND lhp_produksi2.line <= 7
                                        GROUP BY lhp_produksi2.grup, master_pic_line.nama_pic, lhp_produksi2.shift, lhp_produksi2.tanggal_produksi
                                        ORDER BY SUM(detail_breakdown.menit_breakdown) DESC
                                    ');
        } else {
            $query = $this->db->query('SELECT lhp_produksi2.tanggal_produksi, lhp_produksi2.grup, master_pic_line.nama_pic, lhp_produksi2.shift, SUM(lhp_produksi2.total_line_stop) as total_line_stop, SUM(lhp_produksi2.loading_time) as loading_time
                                        FROM detail_breakdown
                                        JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_breakdown.id_lhp
                                        JOIN master_pic_line on master_pic_line.id_pic = lhp_produksi2.grup
                                        WHERE lhp_produksi2.tanggal_produksi = \''.$date.'\'
                                        AND lhp_produksi2.line = '.$line.'
                                        GROUP BY lhp_produksi2.grup, master_pic_line.nama_pic, lhp_produksi2.shift, lhp_produksi2.tanggal_produksi
                                        ORDER BY SUM(detail_breakdown.menit_breakdown) DESC
                                    ');
        }

        return $query->getResultArray();
    }

    public function get_all_detail_proses_breakdown_by_month($date, $line)
    {
        $bulan = idate('m', strtotime($date));
        $year = date('Y', strtotime($date));
        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT MONTH(lhp_produksi2.tanggal_produksi), detail_breakdown.proses_breakdown, SUM(detail_breakdown.menit_breakdown) as qty
                                        FROM detail_breakdown
                                        JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_breakdown.id_lhp
                                        WHERE MONTH(lhp_produksi2.tanggal_produksi) = '.$bulan.' AND YEAR(lhp_produksi2.tanggal_produksi) = '.$year.'
                                        AND lhp_produksi2.line <= 7
                                        GROUP BY detail_breakdown.proses_breakdown, MONTH(lhp_produksi2.tanggal_produksi)
                                        ORDER BY  SUM(detail_breakdown.menit_breakdown) DESC
                                    ');
        } else {
            $query = $this->db->query('SELECT lhp_produksi2.tanggal_produksi, detail_breakdown.proses_breakdown, SUM(detail_breakdown.menit_breakdown) as qty
                                        FROM detail_breakdown
                                        JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_breakdown.id_lhp
                                        WHERE MONTH(lhp_produksi2.tanggal_produksi) = '.$bulan.' AND YEAR(lhp_produksi2.tanggal_produksi) = '.$year.'
                                        AND lhp_produksi2.line = '.$line.'
                                        GROUP BY detail_breakdown.proses_breakdown, lhp_produksi2.tanggal_produksi
                                        ORDER BY  SUM(detail_breakdown.menit_breakdown) DESC
                                    ');
        }

        return $query->getResultArray();
    }

    public function get_all_detail_battery_line_stop_by_month($date, $line)
    {
        $bulan = idate('m', strtotime($date));
        $year = date('Y', strtotime($date));
        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT MONTH(lhp_produksi2.tanggal_produksi), detail_breakdown.type_battery, SUM(detail_breakdown.menit_breakdown) as qty
                                        FROM detail_breakdown
                                        JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_breakdown.id_lhp
                                        WHERE MONTH(lhp_produksi2.tanggal_produksi) = '.$bulan.' AND YEAR(lhp_produksi2.tanggal_produksi) = '.$year.'
                                        AND lhp_produksi2.line <= 7
                                        GROUP BY detail_breakdown.type_battery, MONTH(lhp_produksi2.tanggal_produksi)
                                        ORDER BY SUM(detail_breakdown.menit_breakdown) DESC
                                    ');
        } else {
            $query = $this->db->query('SELECT MONTH(lhp_produksi2.tanggal_produksi), detail_breakdown.type_battery, SUM(detail_breakdown.menit_breakdown) as qty
                                        FROM detail_breakdown
                                        JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_breakdown.id_lhp
                                        WHERE MONTH(lhp_produksi2.tanggal_produksi) = '.$bulan.' AND YEAR(lhp_produksi2.tanggal_produksi) = '.$year.'
                                        AND lhp_produksi2.line = '.$line.'
                                        GROUP BY detail_breakdown.type_battery, MONTH(lhp_produksi2.tanggal_produksi)
                                        ORDER BY  SUM(detail_breakdown.menit_breakdown) DESC
                                    ');
        }

        return $query->getResultArray();
    }

    public function get_all_detail_grup_line_stop_by_month($date, $line)
    {
        $bulan = idate('m', strtotime($date));
        $year = date('Y', strtotime($date));
        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT MONTH(lhp_produksi2.tanggal_produksi), lhp_produksi2.grup, master_pic_line.nama_pic, lhp_produksi2.shift, SUM(lhp_produksi2.total_line_stop) as total_line_stop, SUM(lhp_produksi2.loading_time) as loading_time
                                        FROM detail_breakdown
                                        JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_breakdown.id_lhp
                                        JOIN master_pic_line on master_pic_line.id_pic = lhp_produksi2.grup
                                        WHERE MONTH(lhp_produksi2.tanggal_produksi) = '.$bulan.' AND YEAR(lhp_produksi2.tanggal_produksi) = '.$year.'
                                        AND lhp_produksi2.line <= 7
                                        GROUP BY lhp_produksi2.grup, master_pic_line.nama_pic, lhp_produksi2.shift, MONTH(lhp_produksi2.tanggal_produksi)
                                        ORDER BY SUM(detail_breakdown.menit_breakdown) DESC
                                    ');
        } else {
            $query = $this->db->query('SELECT MONTH(lhp_produksi2.tanggal_produksi), lhp_produksi2.grup, master_pic_line.nama_pic, lhp_produksi2.shift, SUM(lhp_produksi2.total_line_stop) as total_line_stop, SUM(lhp_produksi2.loading_time) as loading_time
                                        FROM detail_breakdown
                                        JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_breakdown.id_lhp
                                        JOIN master_pic_line on master_pic_line.id_pic = lhp_produksi2.grup
                                        WHERE MONTH(lhp_produksi2.tanggal_produksi) = '.$bulan.' AND YEAR(lhp_produksi2.tanggal_produksi) = '.$year.'
                                        AND lhp_produksi2.line = '.$line.'
                                        GROUP BY lhp_produksi2.grup, master_pic_line.nama_pic, lhp_produksi2.shift, MONTH(lhp_produksi2.tanggal_produksi)
                                        ORDER BY SUM(detail_breakdown.menit_breakdown) DESC
                                    ');
        }

        return $query->getResultArray();
    }

    public function get_detail_line_stop_by_grup($jenis_breakdown,$date,$line)
    {
        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT lhp_produksi2.tanggal_produksi, lhp_produksi2.grup, master_pic_line.nama_pic, lhp_produksi2.shift, detail_breakdown.jenis_breakdown, SUM(detail_breakdown.menit_breakdown) as qty
                                        FROM detail_breakdown
                                        JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_breakdown.id_lhp
                                        JOIN master_pic_line on master_pic_line.id_pic = lhp_produksi2.grup
                                        WHERE lhp_produksi2.tanggal_produksi = \''.$date.'\'
                                        AND detail_breakdown.jenis_breakdown = \''.$jenis_breakdown.'\'
                                        AND lhp_produksi2.line <= 7
                                        GROUP BY lhp_produksi2.grup, master_pic_line.nama_pic, lhp_produksi2.shift, detail_breakdown.jenis_breakdown, lhp_produksi2.tanggal_produksi
                                        ORDER BY SUM(detail_breakdown.menit_breakdown) DESC
                                    ');
        } else {
            $query = $this->db->query('SELECT lhp_produksi2.tanggal_produksi, lhp_produksi2.grup, master_pic_line.nama_pic, lhp_produksi2.shift, detail_breakdown.jenis_breakdown, SUM(detail_breakdown.menit_breakdown) as qty
                                        FROM detail_breakdown
                                        JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_breakdown.id_lhp
                                        JOIN master_pic_line on master_pic_line.id_pic = lhp_produksi2.grup
                                        WHERE lhp_produksi2.tanggal_produksi = \''.$date.'\'
                                        AND detail_breakdown.jenis_breakdown = \''.$jenis_breakdown.'\'
                                        AND lhp_produksi2.line = '.$line.'
                                        GROUP BY lhp_produksi2.grup, master_pic_line.nama_pic, lhp_produksi2.shift, detail_breakdown.jenis_breakdown, lhp_produksi2.tanggal_produksi
                                        ORDER BY SUM(detail_breakdown.menit_breakdown) DESC
                                        
                                    ');
        }

        return $query->getResultArray();
    }

    public function get_loading_time_by_date($date, $line) 
    {
        if ($line == null OR $line == 0) {
            $query = $this->db->query('SELECT tanggal_produksi, SUM(loading_time) as loading_time
                                        FROM lhp_produksi2
                                        WHERE tanggal_produksi = \''.$date.'\'
                                        AND line <= 7
                                        GROUP BY tanggal_produksi
                                    ');
        } else {
            $query = $this->db->query('SELECT tanggal_produksi, SUM(loading_time) as loading_time
                                        FROM lhp_produksi2
                                        WHERE tanggal_produksi = \''.$date.'\'
                                        AND line = '.$line.'
                                        GROUP BY tanggal_produksi
                                    ');
        }
        
        return $query->getResultArray();
    }

    public function get_jenis_line_stop_by_type_battery($type_battery, $date, $line)
    {
        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT lhp_produksi2.tanggal_produksi, detail_breakdown.type_battery, detail_breakdown.jenis_breakdown, SUM(detail_breakdown.menit_breakdown) as qty
                                        FROM detail_breakdown
                                        JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_breakdown.id_lhp
                                        WHERE lhp_produksi2.tanggal_produksi = \''.$date.'\'
                                        AND detail_breakdown.type_battery = \''.$type_battery.'\'
                                        AND lhp_produksi2.line <= 7
                                        GROUP BY detail_breakdown.type_battery, detail_breakdown.jenis_breakdown, lhp_produksi2.tanggal_produksi
                                        ORDER BY  SUM(detail_breakdown.menit_breakdown) DESC
                                    ');
        } else {
            $query = $this->db->query('SELECT lhp_produksi2.tanggal_produksi, detail_breakdown.type_battery, detail_breakdown.jenis_breakdown, SUM(detail_breakdown.menit_breakdown) as qty
                                        FROM detail_breakdown
                                        JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_breakdown.id_lhp
                                        WHERE lhp_produksi2.tanggal_produksi = \''.$date.'\'
                                        AND detail_breakdown.type_battery = \''.$type_battery.'\'
                                        AND lhp_produksi2.line = '.$line.'
                                        GROUP BY detail_breakdown.type_battery, detail_breakdown.jenis_breakdown, lhp_produksi2.tanggal_produksi
                                        ORDER BY  SUM(detail_breakdown.menit_breakdown) DESC
                                    ');
        }

        return $query->getResultArray();
    }

    public function get_proses_breakdown_by_type_battery($type_battery, $date, $line)
    {
        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT lhp_produksi2.tanggal_produksi, detail_breakdown.type_battery, detail_breakdown.proses_breakdown, SUM(detail_breakdown.menit_breakdown) as qty
                                        FROM detail_breakdown
                                        JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_breakdown.id_lhp
                                        WHERE lhp_produksi2.tanggal_produksi = \''.$date.'\'
                                        AND detail_breakdown.type_battery = \''.$type_battery.'\'
                                        AND lhp_produksi2.line <= 7
                                        GROUP BY detail_breakdown.type_battery, detail_breakdown.proses_breakdown, lhp_produksi2.tanggal_produksi
                                        ORDER BY  SUM(detail_breakdown.menit_breakdown) DESC
                                    ');
        } else {
            $query = $this->db->query('SELECT lhp_produksi2.tanggal_produksi, detail_breakdown.type_battery, detail_breakdown.proses_breakdown, SUM(detail_breakdown.menit_breakdown) as qty
                                        FROM detail_breakdown
                                        JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_breakdown.id_lhp
                                        WHERE lhp_produksi2.tanggal_produksi = \''.$date.'\'
                                        AND detail_breakdown.type_battery = \''.$type_battery.'\'
                                        AND lhp_produksi2.line = '.$line.'
                                        GROUP BY detail_breakdown.type_battery, detail_breakdown.proses_breakdown, lhp_produksi2.tanggal_produksi
                                        ORDER BY  SUM(detail_breakdown.menit_breakdown) DESC
                                    ');
        }

        return $query->getResultArray();
    }

    public function get_grup_line_stop_by_type_battery($type_battery,$date,$line)
    {
        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT lhp_produksi2.tanggal_produksi, lhp_produksi2.grup, master_pic_line.nama_pic, lhp_produksi2.shift, detail_breakdown.type_battery, SUM(detail_breakdown.menit_breakdown) as qty
                                        FROM detail_breakdown
                                        JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_breakdown.id_lhp
                                        JOIN master_pic_line on master_pic_line.id_pic = lhp_produksi2.grup
                                        WHERE lhp_produksi2.tanggal_produksi = \''.$date.'\'
                                        AND detail_breakdown.type_battery = \''.$type_battery.'\'
                                        AND lhp_produksi2.line <= 7
                                        GROUP BY lhp_produksi2.grup, master_pic_line.nama_pic, lhp_produksi2.shift, detail_breakdown.type_battery, lhp_produksi2.tanggal_produksi
                                        ORDER BY SUM(detail_breakdown.menit_breakdown) DESC
                                    ');
        } else {
            $query = $this->db->query('SELECT lhp_produksi2.tanggal_produksi, lhp_produksi2.grup, master_pic_line.nama_pic, lhp_produksi2.shift, detail_breakdown.type_battery, SUM(detail_breakdown.menit_breakdown) as qty
                                        FROM detail_breakdown
                                        JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_breakdown.id_lhp
                                        JOIN master_pic_line on master_pic_line.id_pic = lhp_produksi2.grup
                                        WHERE lhp_produksi2.tanggal_produksi = \''.$date.'\'
                                        AND detail_breakdown.type_battery = \''.$type_battery.'\'
                                        AND lhp_produksi2.line = '.$line.'
                                        GROUP BY lhp_produksi2.grup, master_pic_line.nama_pic, lhp_produksi2.shift, detail_breakdown.type_battery, lhp_produksi2.tanggal_produksi
                                        ORDER BY SUM(detail_breakdown.menit_breakdown) DESC
                                        
                                    ');
        }

        return $query->getResultArray();
    }

    public function get_jenis_line_stop_by_grup_shift($grup,$shift,$date,$line)
    {
        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT lhp_produksi2.tanggal_produksi, lhp_produksi2.grup, master_pic_line.nama_pic, lhp_produksi2.shift, detail_breakdown.jenis_breakdown, SUM(detail_breakdown.menit_breakdown) as qty
                                        FROM detail_breakdown
                                        JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_breakdown.id_lhp
                                        JOIN master_pic_line on master_pic_line.id_pic = lhp_produksi2.grup
                                        WHERE lhp_produksi2.tanggal_produksi = \''.$date.'\'
                                        AND master_pic_line.nama_pic = \''.$grup.'\'
                                        AND lhp_produksi2.shift = \''.$shift.'\'
                                        AND lhp_produksi2.line <= 7
                                        GROUP BY detail_breakdown.jenis_breakdown, lhp_produksi2.grup, master_pic_line.nama_pic, lhp_produksi2.shift, lhp_produksi2.tanggal_produksi
                                        ORDER BY SUM(detail_breakdown.menit_breakdown) DESC
                                    ');
        } else {
            $query = $this->db->query('SELECT lhp_produksi2.tanggal_produksi, lhp_produksi2.grup, master_pic_line.nama_pic, lhp_produksi2.shift, detail_breakdown.jenis_breakdown, SUM(detail_breakdown.menit_breakdown) as qty
                                        FROM detail_breakdown
                                        JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_breakdown.id_lhp
                                        JOIN master_pic_line on master_pic_line.id_pic = lhp_produksi2.grup
                                        WHERE lhp_produksi2.tanggal_produksi = \''.$date.'\'
                                        AND master_pic_line.nama_pic = \''.$grup.'\'
                                        AND lhp_produksi2.shift = \''.$shift.'\'
                                        AND lhp_produksi2.line = '.$line.'
                                        GROUP BY detail_breakdown.jenis_breakdown, lhp_produksi2.grup, master_pic_line.nama_pic, lhp_produksi2.shift, lhp_produksi2.tanggal_produksi
                                        ORDER BY SUM(detail_breakdown.menit_breakdown) DESC
                                        
                                    ');
        }

        return $query->getResultArray();
    }

    public function get_proses_breakdown_by_grup_shift($grup,$shift,$date,$line)
    {
        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT lhp_produksi2.tanggal_produksi, lhp_produksi2.grup, master_pic_line.nama_pic, lhp_produksi2.shift, detail_breakdown.proses_breakdown, SUM(detail_breakdown.menit_breakdown) as qty
                                        FROM detail_breakdown
                                        JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_breakdown.id_lhp
                                        JOIN master_pic_line on master_pic_line.id_pic = lhp_produksi2.grup
                                        WHERE lhp_produksi2.tanggal_produksi = \''.$date.'\'
                                        AND master_pic_line.nama_pic = \''.$grup.'\'
                                        AND lhp_produksi2.shift = \''.$shift.'\'
                                        AND lhp_produksi2.line <= 7
                                        GROUP BY detail_breakdown.proses_breakdown, lhp_produksi2.grup, master_pic_line.nama_pic, lhp_produksi2.shift, lhp_produksi2.tanggal_produksi
                                        ORDER BY SUM(detail_breakdown.menit_breakdown) DESC
                                    ');
        } else {
            $query = $this->db->query('SELECT lhp_produksi2.tanggal_produksi, lhp_produksi2.grup, master_pic_line.nama_pic, lhp_produksi2.shift, detail_breakdown.proses_breakdown, SUM(detail_breakdown.menit_breakdown) as qty
                                        FROM detail_breakdown
                                        JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_breakdown.id_lhp
                                        JOIN master_pic_line on master_pic_line.id_pic = lhp_produksi2.grup
                                        WHERE lhp_produksi2.tanggal_produksi = \''.$date.'\'
                                        AND master_pic_line.nama_pic = \''.$grup.'\'
                                        AND lhp_produksi2.shift = \''.$shift.'\'
                                        AND lhp_produksi2.line = '.$line.'
                                        GROUP BY detail_breakdown.proses_breakdown, lhp_produksi2.grup, master_pic_line.nama_pic, lhp_produksi2.shift, lhp_produksi2.tanggal_produksi
                                        ORDER BY SUM(detail_breakdown.menit_breakdown) DESC
                                        
                                    ');
        }

        return $query->getResultArray();
    }

    public function get_battery_line_stop_by_grup_shift($grup,$shift,$date,$line)
    {
        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT lhp_produksi2.tanggal_produksi, lhp_produksi2.grup, master_pic_line.nama_pic, lhp_produksi2.shift, detail_breakdown.type_battery, SUM(detail_breakdown.menit_breakdown) as qty
                                        FROM detail_breakdown
                                        JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_breakdown.id_lhp
                                        JOIN master_pic_line on master_pic_line.id_pic = lhp_produksi2.grup
                                        WHERE lhp_produksi2.tanggal_produksi = \''.$date.'\'
                                        AND master_pic_line.nama_pic = \''.$grup.'\'
                                        AND lhp_produksi2.shift = \''.$shift.'\'
                                        AND lhp_produksi2.line <= 7
                                        GROUP BY detail_breakdown.type_battery, lhp_produksi2.grup, master_pic_line.nama_pic, lhp_produksi2.shift, lhp_produksi2.tanggal_produksi
                                        ORDER BY SUM(detail_breakdown.menit_breakdown) DESC
                                    ');
        } else {
            $query = $this->db->query('SELECT lhp_produksi2.tanggal_produksi, lhp_produksi2.grup, master_pic_line.nama_pic, lhp_produksi2.shift, detail_breakdown.type_battery, SUM(detail_breakdown.menit_breakdown) as qty
                                        FROM detail_breakdown
                                        JOIN lhp_produksi2 on lhp_produksi2.id_lhp_2 = detail_breakdown.id_lhp
                                        JOIN master_pic_line on master_pic_line.id_pic = lhp_produksi2.grup
                                        WHERE lhp_produksi2.tanggal_produksi = \''.$date.'\'
                                        AND master_pic_line.nama_pic = \''.$grup.'\'
                                        AND lhp_produksi2.shift = \''.$shift.'\'
                                        AND lhp_produksi2.line = '.$line.'
                                        GROUP BY detail_breakdown.type_battery, lhp_produksi2.grup, master_pic_line.nama_pic, lhp_produksi2.shift, lhp_produksi2.tanggal_produksi
                                        ORDER BY SUM(detail_breakdown.menit_breakdown) DESC
                                        
                                    ');
        }

        return $query->getResultArray();
    }
}