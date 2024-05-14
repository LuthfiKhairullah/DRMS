<?php 
namespace App\Models;
use CodeIgniter\Model;



class M_DashboardWetFinishing extends Model
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

    public function get_data_all_line($tanggal, $line)
    {
        // $tanggal = date('Y-m-01');
        // $now = date('Y-m-d');

        $query = $this->db->query('SELECT tanggal_produksi, line, SUM(total_plan) AS total_plan, SUM(total_aktual) AS total_aktual
                                    FROM		lhp_produksi2
                                    WHERE		tanggal_produksi = \''.$tanggal.'\' AND line = '.$line.'
                                    GROUP BY	tanggal_produksi, line');

        return $query->getResultArray();
    }

    public function get_data_line($tanggal, $line, $shift)
    {
        $query = $this->db->query('SELECT tanggal_produksi, line, shift, SUM(total_plan) AS total_plan, SUM(total_aktual) AS total_aktual
                                    FROM		lhp_produksi2
                                    WHERE		tanggal_produksi = \''.$tanggal.'\' AND line = '.$line.' AND shift = '.$shift.'
                                    GROUP BY	shift, tanggal_produksi, line');

        return $query->getResultArray();
    }

    public function get_data_all_line_by_month_by_shift($month, $bulan, $line, $shift) 
    {
        $year = date('Y', strtotime($month));
        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT MONTH(tanggal_produksi) AS month,SUM(total_plan) AS total_plan, SUM(total_aktual) AS total_aktual
                                        FROM lhp_produksi2 
                                        WHERE MONTH(tanggal_produksi) = '.$bulan.' AND YEAR(tanggal_produksi) = '.$year.' AND shift = \'' . $shift . '\'
                                        line >= 8 AND line <= 9
                                        GROUP BY MONTH(tanggal_produksi)
                                        ORDER BY MONTH(tanggal_produksi)
                                    ');
        } else {
            $query = $this->db->query('SELECT MONTH(tanggal_produksi) AS month,SUM(total_plan) AS total_plan, SUM(total_aktual) AS total_aktual
                                        FROM lhp_produksi2 
                                        WHERE MONTH(tanggal_produksi) = '.$bulan.' AND YEAR(tanggal_produksi) = '.$year.' AND line = '.$line.' AND shift = \'' . $shift . '\'
                                        GROUP BY MONTH(tanggal_produksi)
                                        ORDER BY MONTH(tanggal_produksi)
                                    ');
        }

        return $query->getResultArray();
    }

    public function get_data_all_line_by_date($tanggal)
    {
        $query = $this->db->query('SELECT tanggal_produksi, SUM(total_plan) AS total_plan, SUM(total_aktual) AS total_aktual
                                    FROM		lhp_produksi2
                                    WHERE		tanggal_produksi = \''.$tanggal.'\' AND line >= 8 AND line <= 9
                                    GROUP BY	tanggal_produksi');

        return $query->getResultArray();
    }

    public function get_data_all_line_by_date_wet_a($tanggal)
    {
        $query = $this->db->query('SELECT tanggal_produksi, SUM(total_plan) AS total_plan, SUM(total_aktual) AS total_aktual
                                    FROM		lhp_produksi2
                                    WHERE		tanggal_produksi = \''.$tanggal.'\' AND line = 8
                                    GROUP BY	tanggal_produksi');

        return $query->getResultArray();
    }

    public function get_data_all_line_by_date_wet_f($tanggal)
    {
        $query = $this->db->query('SELECT tanggal_produksi, SUM(total_plan) AS total_plan, SUM(total_aktual) AS total_aktual
                                    FROM		lhp_produksi2
                                    WHERE		tanggal_produksi = \''.$tanggal.'\' AND line = 9
                                    GROUP BY	tanggal_produksi');

        return $query->getResultArray();
    }

    public function get_data_all_line_by_month($month, $bulan, $line) 
    {
        $year = date('Y', strtotime($month));
        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT MONTH(tanggal_produksi) AS month,SUM(total_plan) AS total_plan, SUM(total_aktual) AS total_aktual
                                        FROM lhp_produksi2 
                                        WHERE MONTH(tanggal_produksi) = '.$bulan.' AND YEAR(tanggal_produksi) = '.$year.'
                                        AND line >= 8 AND line <= 9
                                        GROUP BY MONTH(tanggal_produksi)
                                        ORDER BY MONTH(tanggal_produksi)
                                    ');
        } else {
            $query = $this->db->query('SELECT MONTH(tanggal_produksi) AS month,SUM(total_plan) AS total_plan, SUM(total_aktual) AS total_aktual
                                        FROM lhp_produksi2 
                                        WHERE MONTH(tanggal_produksi) = '.$bulan.' AND YEAR(tanggal_produksi) = '.$year.' AND line = '.$line.'
                                        GROUP BY MONTH(tanggal_produksi)
                                        ORDER BY MONTH(tanggal_produksi)
                                    ');
        }

        return $query->getResultArray();
    }

    public function get_data_all_line_by_month_wet_a($month, $bulan, $line) 
    {
        $year = date('Y', strtotime($month));
        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT MONTH(tanggal_produksi) AS month,SUM(total_plan) AS total_plan, SUM(total_aktual) AS total_aktual
                                        FROM lhp_produksi2 
                                        WHERE MONTH(tanggal_produksi) = '.$bulan.' AND YEAR(tanggal_produksi) = '.$year.' AND line = 8
                                        GROUP BY MONTH(tanggal_produksi)
                                        ORDER BY MONTH(tanggal_produksi)
                                    ');
        } else {
            $query = $this->db->query('SELECT MONTH(tanggal_produksi) AS month,SUM(total_plan) AS total_plan, SUM(total_aktual) AS total_aktual
                                        FROM lhp_produksi2 
                                        WHERE MONTH(tanggal_produksi) = '.$bulan.' AND YEAR(tanggal_produksi) = '.$year.' AND line = '.$line.'
                                        GROUP BY MONTH(tanggal_produksi)
                                        ORDER BY MONTH(tanggal_produksi)
                                    ');
        }
        
        return $query->getResultArray();
    }

    public function get_data_all_line_by_month_wet_f($month, $bulan, $line) 
    {
        $year = date('Y', strtotime($month));
        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT MONTH(tanggal_produksi) AS month,SUM(total_plan) AS total_plan, SUM(total_aktual) AS total_aktual
                                        FROM lhp_produksi2 
                                        WHERE MONTH(tanggal_produksi) = '.$bulan.' AND YEAR(tanggal_produksi) = '.$year.' AND line = 9
                                        GROUP BY MONTH(tanggal_produksi)
                                        ORDER BY MONTH(tanggal_produksi)
                                    ');
        } else {
            $query = $this->db->query('SELECT MONTH(tanggal_produksi) AS month,SUM(total_plan) AS total_plan, SUM(total_aktual) AS total_aktual
                                        FROM lhp_produksi2 
                                        WHERE MONTH(tanggal_produksi) = '.$bulan.' AND YEAR(tanggal_produksi) = '.$year.' AND line = '.$line.'
                                        GROUP BY MONTH(tanggal_produksi)
                                        ORDER BY MONTH(tanggal_produksi)
                                    ');
        }
        
        return $query->getResultArray();
    }

    public function get_data_all_line_by_year($month, $line) 
    {
        $tahun = date('Y', strtotime($month));

        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT YEAR(tanggal_produksi) AS month,SUM(total_plan) AS total_plan, SUM(total_aktual) AS total_aktual
                                        FROM lhp_produksi2 
                                        WHERE YEAR(tanggal_produksi) = '.$tahun.'
                                        AND line >= 8 AND line <= 9
                                        GROUP BY YEAR(tanggal_produksi)
                                        ORDER BY YEAR(tanggal_produksi)
                                    ');
        } else {
            $query = $this->db->query('SELECT YEAR(tanggal_produksi) AS month,SUM(total_plan) AS total_plan, SUM(total_aktual) AS total_aktual
                                        FROM lhp_produksi2 
                                        WHERE YEAR(tanggal_produksi) = '.$tahun.' AND line = '.$line.'
                                        GROUP BY YEAR(tanggal_produksi)
                                        ORDER BY YEAR(tanggal_produksi)
                                    ');
        }

        return $query->getResultArray();
    }

    public function get_data_all_line_by_year_wet_a($month, $line) 
    {
        $tahun = date('Y', strtotime($month));

        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT YEAR(tanggal_produksi) AS month,SUM(total_plan) AS total_plan, SUM(total_aktual) AS total_aktual
                                        FROM lhp_produksi2 
                                        WHERE YEAR(tanggal_produksi) = '.$tahun.' AND line = 8
                                        GROUP BY YEAR(tanggal_produksi)
                                        ORDER BY YEAR(tanggal_produksi)
                                    ');
        } else {
            $query = $this->db->query('SELECT YEAR(tanggal_produksi) AS month,SUM(total_plan) AS total_plan, SUM(total_aktual) AS total_aktual
                                        FROM lhp_produksi2 
                                        WHERE YEAR(tanggal_produksi) = '.$tahun.' AND line = '.$line.'
                                        GROUP BY YEAR(tanggal_produksi)
                                        ORDER BY YEAR(tanggal_produksi)
                                    ');
        }

        return $query->getResultArray();
    }

    public function get_data_all_line_by_year_wet_f($month, $line) 
    {
        $tahun = date('Y', strtotime($month));

        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT YEAR(tanggal_produksi) AS month,SUM(total_plan) AS total_plan, SUM(total_aktual) AS total_aktual
                                        FROM lhp_produksi2 
                                        WHERE YEAR(tanggal_produksi) = '.$tahun.' AND line = 9
                                        GROUP BY YEAR(tanggal_produksi)
                                        ORDER BY YEAR(tanggal_produksi)
                                    ');
        } else {
            $query = $this->db->query('SELECT YEAR(tanggal_produksi) AS month,SUM(total_plan) AS total_plan, SUM(total_aktual) AS total_aktual
                                        FROM lhp_produksi2 
                                        WHERE YEAR(tanggal_produksi) = '.$tahun.' AND line = '.$line.'
                                        GROUP BY YEAR(tanggal_produksi)
                                        ORDER BY YEAR(tanggal_produksi)
                                    ');
        }

        return $query->getResultArray();
    }

    public function get_data_line_stop($tanggal, $line)
    {
        // if (!empty($line)) {
        //     $sql = 'AND lhp_produksi2.line = '.$line;
        // } else {
        //     $sql = '';
        // }
        
        // $query = $this->db->query('SELECT * FROM lhp_produksi2
        //                             JOIN detail_breakdown ON detail_breakdown.id_lhp = lhp_produksi2.id_lhp_2
        //                             WHERE lhp_produksi2.tanggal_produksi = \''.$tanggal.'\''.$sql);
        
        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT * FROM lhp_produksi2
                                        JOIN detail_breakdown ON detail_breakdown.id_lhp = lhp_produksi2.id_lhp_2
                                        WHERE lhp_produksi2.tanggal_produksi = \''.$tanggal.'\'
                                        AND lhp_produksi2.line >= 8 AND lhp_produksi2.line <= 9');
        } else {
            $query = $this->db->query('SELECT * FROM lhp_produksi2
                                        JOIN detail_breakdown ON detail_breakdown.id_lhp = lhp_produksi2.id_lhp_2
                                        WHERE lhp_produksi2.tanggal_produksi = \''.$tanggal.'\' AND line = '.$line);
        }

        return $query->getResultArray();
    }

    public function get_data_line_stop_by_shift($tanggal, $line, $shift)
    {
        $query = $this->db->query('SELECT * FROM lhp_produksi2
                                    JOIN detail_breakdown ON detail_breakdown.id_lhp = lhp_produksi2.id_lhp_2
                                    WHERE lhp_produksi2.tanggal_produksi = \''.$tanggal.'\' AND lhp_produksi2.line = '.$line.' AND lhp_produksi2.shift = '.$shift);

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
                                        WHERE		MONTH(lhp_produksi2.tanggal_produksi) = '.$bulan.' AND YEAR(lhp_produksi2.tanggal_produksi) = '.$year.' AND (master_pic_line.id_line >= 8 AND master_pic_line.id_line <= 9)
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
                                        WHERE		YEAR(lhp_produksi2.tanggal_produksi) = '.$tahun.' AND (master_pic_line.id_line >= 8 AND master_pic_line.id_line <= 9)
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
            $query = $this->db->query('SELECT lhp_produksi2.tanggal_produksi, master_pic_line.nama_pic ,SUM(lhp_produksi2.total_plan) AS total_plan, SUM(lhp_produksi2.total_aktual) AS total_aktual
                                        FROM		lhp_produksi2
                                        JOIN        master_pic_line ON master_pic_line.id_pic = lhp_produksi2.grup
                                        WHERE		lhp_produksi2.tanggal_produksi = \''.$tanggal.'\' AND (master_pic_line.id_line >= 8 AND master_pic_line.id_line <= 9) AND master_pic_line.nama_pic = \''.$grup.'\'
                                        GROUP BY	lhp_produksi2.tanggal_produksi, master_pic_line.nama_pic');
        } else {
            $query = $this->db->query('SELECT lhp_produksi2.tanggal_produksi, lhp_produksi2.line, master_pic_line.nama_pic ,SUM(lhp_produksi2.total_plan) AS total_plan, SUM(lhp_produksi2.total_aktual) AS total_aktual
                                        FROM		lhp_produksi2
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
            $query = $this->db->query('SELECT MONTH(lhp_produksi2.tanggal_produksi), master_pic_line.nama_pic ,SUM(lhp_produksi2.total_plan) AS total_plan, SUM(lhp_produksi2.total_aktual) AS total_aktual
                                        FROM		lhp_produksi2
                                        JOIN        master_pic_line ON master_pic_line.id_pic = lhp_produksi2.grup
                                        WHERE		MONTH(lhp_produksi2.tanggal_produksi) = '.$bulan.' AND YEAR(lhp_produksi2.tanggal_produksi) = '.$year.' AND (master_pic_line.id_line >= 8 AND master_pic_line.id_line <= 9) AND master_pic_line.nama_pic = \''.$grup.'\'
                                        GROUP BY	lhp_produksi2.tanggal_produksi, master_pic_line.nama_pic');
        } else {
            $query = $this->db->query('SELECT MONTH(lhp_produksi2.tanggal_produksi), master_pic_line.nama_pic ,SUM(lhp_produksi2.total_plan) AS total_plan, SUM(lhp_produksi2.total_aktual) AS total_aktual
                                        FROM		lhp_produksi2
                                        JOIN        master_pic_line ON master_pic_line.id_pic = lhp_produksi2.grup
                                        WHERE		MONTH(lhp_produksi2.tanggal_produksi) = '.$bulan.' AND YEAR(lhp_produksi2.tanggal_produksi) = '.$year.' AND lhp_produksi2.line = '.$line.' AND master_pic_line.nama_pic = \''.$grup.'\'
                                        GROUP BY	lhp_produksi2.tanggal_produksi, master_pic_line.nama_pic');
        }

        return $query->getResultArray();
    }

    public function get_data_line_stop_by_grup($tanggal, $line, $grup)
    {
        $query = $this->db->query('SELECT * FROM lhp_produksi2
                                    JOIN detail_breakdown ON detail_breakdown.id_lhp = lhp_produksi2.id_lhp_2
                                    JOIN master_pic_line ON master_pic_line.id_pic = lhp_produksi2.grup
                                    WHERE lhp_produksi2.tanggal_produksi = \''.$tanggal.'\' AND lhp_produksi2.line = '.$line.' AND master_pic_line.nama_pic = \''.$grup.'\'');

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
        $query = $this->db->query('SELECT tanggal_produksi, line, kasubsie ,SUM(total_plan) AS total_plan, SUM(total_aktual) AS total_aktual
                                    FROM		lhp_produksi2
                                    WHERE		tanggal_produksi = \''.$tanggal.'\' AND line = '.$line.' AND kasubsie = \''.$kss.'\'
                                    GROUP BY	tanggal_produksi, line, kasubsie');

        return $query->getResultArray();
    }

    public function get_data_line_by_kss_month($month, $bulan, $line, $kss)
    {
        $year = date('Y', strtotime($month));
        $query = $this->db->query('SELECT MONTH(tanggal_produksi), line, kasubsie ,SUM(total_plan) AS total_plan, SUM(total_aktual) AS total_aktual
                                    FROM		lhp_produksi2
                                    WHERE		MONTH(tanggal_produksi) = \''.$bulan.'\' AND YEAR(tanggal_produksi) = \''.$year.'\' AND line = '.$line.' AND kasubsie = \''.$kss.'\'
                                    GROUP BY	tanggal_produksi, line, kasubsie');

        return $query->getResultArray();
    }

    public function get_data_line_stop_by_kss($tanggal, $line, $kss)
    {
        $query = $this->db->query('SELECT * FROM lhp_produksi2
                                    JOIN detail_breakdown ON detail_breakdown.id_lhp = lhp_produksi2.id_lhp_2
                                    WHERE lhp_produksi2.tanggal_produksi = \''.$tanggal.'\' AND lhp_produksi2.line = '.$line.' AND lhp_produksi2.kasubsie = \''.$kss.'\'');

        return $query->getResultArray();
    }

    public function get_data_all_line_by_jam($line, $jam, $tanggal)
    {
        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT SUM(detail_lhp_produksi2.plan_cap) AS total_plan, SUM(detail_lhp_produksi2.actual) AS total_aktual FROM detail_lhp_produksi2
                                    JOIN lhp_produksi2 ON detail_lhp_produksi2.id_lhp_2 = lhp_produksi2.id_lhp_2
                                    WHERE lhp_produksi2.tanggal_produksi = \''.$tanggal.'\'
                                    AND detail_lhp_produksi2.jam_end = \''.$jam.'\'
                                ');
        } else {
            $query = $this->db->query('SELECT SUM(detail_lhp_produksi2.plan_cap) AS total_plan, SUM(detail_lhp_produksi2.actual) AS total_aktual FROM detail_lhp_produksi2
                                    JOIN lhp_produksi2 ON detail_lhp_produksi2.id_lhp_2 = lhp_produksi2.id_lhp_2
                                    WHERE lhp_produksi2.tanggal_produksi = \''.$tanggal.'\'
                                    AND detail_lhp_produksi2.jam_end = \''.$jam.'\' 
                                    AND lhp_produksi2.line = '.$line);
        }   
        

        return $query->getResultArray();
    }

    public function get_data_all_line_by_jam_wet_a($line, $jam, $tanggal)
    {
        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT SUM(detail_lhp_produksi2.plan_cap) AS total_plan, SUM(detail_lhp_produksi2.actual) AS total_aktual FROM detail_lhp_produksi2
                                    JOIN lhp_produksi2 ON detail_lhp_produksi2.id_lhp_2 = lhp_produksi2.id_lhp_2
                                    WHERE lhp_produksi2.tanggal_produksi = \''.$tanggal.'\'
                                    AND lhp_produksi2.line = 8
                                    AND detail_lhp_produksi2.jam_end = \''.$jam.'\'
                                ');
        } else {
            $query = $this->db->query('SELECT SUM(detail_lhp_produksi2.plan_cap) AS total_plan, SUM(detail_lhp_produksi2.actual) AS total_aktual FROM detail_lhp_produksi2
                                    JOIN lhp_produksi2 ON detail_lhp_produksi2.id_lhp_2 = lhp_produksi2.id_lhp_2
                                    WHERE lhp_produksi2.tanggal_produksi = \''.$tanggal.'\'
                                    AND detail_lhp_produksi2.jam_end = \''.$jam.'\' 
                                    AND lhp_produksi2.line = '.$line);
        }   
        

        return $query->getResultArray();
    }

    public function get_data_all_line_by_jam_wet_f($line, $jam, $tanggal)
    {
        if ($line == null || $line == 0) {
            $query = $this->db->query('SELECT SUM(detail_lhp_produksi2.plan_cap) AS total_plan, SUM(detail_lhp_produksi2.actual) AS total_aktual FROM detail_lhp_produksi2
                                    JOIN lhp_produksi2 ON detail_lhp_produksi2.id_lhp_2 = lhp_produksi2.id_lhp_2
                                    WHERE lhp_produksi2.tanggal_produksi = \''.$tanggal.'\'
                                    AND lhp_produksi2.line = 9
                                    AND detail_lhp_produksi2.jam_end = \''.$jam.'\'
                                ');
        } else {
            $query = $this->db->query('SELECT SUM(detail_lhp_produksi2.plan_cap) AS total_plan, SUM(detail_lhp_produksi2.actual) AS total_aktual FROM detail_lhp_produksi2
                                    JOIN lhp_produksi2 ON detail_lhp_produksi2.id_lhp_2 = lhp_produksi2.id_lhp_2
                                    WHERE lhp_produksi2.tanggal_produksi = \''.$tanggal.'\'
                                    AND detail_lhp_produksi2.jam_end = \''.$jam.'\' 
                                    AND lhp_produksi2.line = '.$line);
        }   
        

        return $query->getResultArray();
    }

    public function get_data_top_grup_wet_a($bulan)
    {
        $query = $this->db->query('SELECT
                                        master_pic_line.nama_pic,
                                        CASE
                                        WHEN SUM(lhp_produksi2.total_aktual) = 0 THEN 0
                                        WHEN SUM(lhp_produksi2.total_plan) = 0 THEN 0
                                        ELSE (SUM(lhp_produksi2.total_aktual) / CAST(SUM(lhp_produksi2.total_plan) as float)) * 100
                                        END AS persen
                                    FROM
                                        lhp_produksi2
                                    JOIN
                                        master_pic_line ON master_pic_line.id_pic = lhp_produksi2.grup
                                    WHERE
                                        MONTH(lhp_produksi2.tanggal_produksi) = '.$bulan.' AND master_pic_line.id_line = 8
                                    GROUP BY
                                    master_pic_line.nama_pic
                                    ORDER BY
                                    persen DESC
                                ');

        return $query->getResultArray();
    }

    public function get_data_top_grup_wet_f($bulan)
    {
        $query = $this->db->query('SELECT
                                        master_pic_line.nama_pic,
                                        CASE
                                        WHEN SUM(lhp_produksi2.total_aktual) = 0 THEN 0
                                        WHEN SUM(lhp_produksi2.total_plan) = 0 THEN 0
                                        ELSE (SUM(lhp_produksi2.total_aktual) / CAST(SUM(lhp_produksi2.total_plan) as float)) * 100
                                        END AS persen
                                    FROM
                                        lhp_produksi2
                                    JOIN
                                        master_pic_line ON master_pic_line.id_pic = lhp_produksi2.grup
                                    WHERE
                                        MONTH(lhp_produksi2.tanggal_produksi) = '.$bulan.' AND master_pic_line.id_line = 9
                                    GROUP BY
                                    master_pic_line.nama_pic
                                    ORDER BY
                                    persen DESC
                                ');

        return $query->getResultArray();
    }

    public function get_data_top_grup_wet_a_daily($tanggal)
    {
        $query = $this->db->query('SELECT master_pic_line.nama_pic, 
                                        CASE
                                        WHEN SUM(lhp_produksi2.total_aktual) = 0 THEN 0
                                        ELSE (SUM(lhp_produksi2.total_aktual) / CAST(SUM(lhp_produksi2.total_plan) as float)) * 100
                                        END AS persen
                                -- (SUM(lhp_produksi2.total_aktual) / CAST(SUM(lhp_produksi2.total_plan) as float)) * 100 AS persen
                                    FROM lhp_produksi2
                                    JOIN master_pic_line ON master_pic_line.id_pic = lhp_produksi2.grup
                                    WHERE lhp_produksi2.tanggal_produksi = \''.$tanggal.'\' AND master_pic_line.id_line = 8
                                    GROUP BY master_pic_line.nama_pic
                                    ORDER BY persen DESC
                                ');

        return $query->getResultArray();
    }

    public function get_data_top_grup_wet_f_daily($tanggal)
    {
        $query = $this->db->query('SELECT master_pic_line.nama_pic, 
                                        CASE
                                        WHEN SUM(lhp_produksi2.total_aktual) = 0 THEN 0
                                        ELSE (SUM(lhp_produksi2.total_aktual) / CAST(SUM(lhp_produksi2.total_plan) as float)) * 100
                                        END AS persen
                                -- (SUM(lhp_produksi2.total_aktual) / CAST(SUM(lhp_produksi2.total_plan) as float)) * 100 AS persen
                                    FROM lhp_produksi2
                                    JOIN master_pic_line ON master_pic_line.id_pic = lhp_produksi2.grup
                                    WHERE lhp_produksi2.tanggal_produksi = \''.$tanggal.'\' AND master_pic_line.id_line = 9
                                    GROUP BY master_pic_line.nama_pic
                                    ORDER BY persen DESC
                                ');

        return $query->getResultArray();
    }

    public function get_data_top_grup_daily($tanggal)
    {
        $query = $this->db->query('SELECT master_pic_line.nama_pic, 
                                        CASE
                                        WHEN SUM(lhp_produksi2.total_aktual) = 0 THEN 0
                                        ELSE (SUM(lhp_produksi2.total_aktual) / CAST(SUM(lhp_produksi2.total_plan) as float)) * 100
                                        END AS persen
                                -- (SUM(lhp_produksi2.total_aktual) / CAST(SUM(lhp_produksi2.total_plan) as float)) * 100 AS persen
                                    FROM lhp_produksi2
                                    JOIN master_pic_line ON master_pic_line.id_pic = lhp_produksi2.grup
                                    WHERE lhp_produksi2.tanggal_produksi = \''.$tanggal.'\'
                                    GROUP BY master_pic_line.nama_pic
                                    ORDER BY persen DESC
                                ');

        return $query->getResultArray();
    }

    public function get_data_top_grup_monthly($month, $bulan)
    {
        $year = date('Y', strtotime($month));
        $query = $this->db->query('SELECT
                                        master_pic_line.nama_pic,
                                        CASE
                                        WHEN SUM(lhp_produksi2.total_aktual) = 0 THEN 0
                                        WHEN SUM(lhp_produksi2.total_plan) = 0 THEN 0
                                        ELSE (SUM(lhp_produksi2.total_aktual) / CAST(SUM(lhp_produksi2.total_plan) as float)) * 100
                                        END AS persen
                                    FROM
                                        lhp_produksi2
                                    JOIN
                                        master_pic_line ON master_pic_line.id_pic = lhp_produksi2.grup
                                    WHERE
                                        MONTH(lhp_produksi2.tanggal_produksi) = '.$bulan.' AND YEAR(lhp_produksi2.tanggal_produksi) = '.$year.' AND (master_pic_line.id_line >= 8 AND master_pic_line.id_line <= 9)
                                    GROUP BY
                                    master_pic_line.nama_pic
                                    ORDER BY
                                    persen DESC
                                ');

        return $query->getResultArray();
    }

    public function get_data_top_grup_yearly($tahun)
    {
        $query = $this->db->query('SELECT
                                        master_pic_line.nama_pic,
                                        CASE
                                        WHEN SUM(lhp_produksi2.total_aktual) = 0 THEN 0
                                        WHEN SUM(lhp_produksi2.total_plan) = 0 THEN 0
                                        ELSE (SUM(lhp_produksi2.total_aktual) / CAST(SUM(lhp_produksi2.total_plan) as float)) * 100
                                        END AS persen
                                    FROM
                                        lhp_produksi2
                                    JOIN
                                        master_pic_line ON master_pic_line.id_pic = lhp_produksi2.grup
                                    WHERE
                                        YEAR(lhp_produksi2.tanggal_produksi) = '.$tahun.' AND (master_pic_line.id_line >= 8 AND master_pic_line.id_line <= 9)
                                    GROUP BY
                                    master_pic_line.nama_pic
                                    ORDER BY
                                    persen DESC
                                ');

        return $query->getResultArray();
    }
}