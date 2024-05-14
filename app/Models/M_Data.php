<?php

namespace App\Models;

use CodeIgniter\Model;
use Config\Services;

class M_Data extends Model
{
    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->db5 = \Config\Database::connect('henkaten');

        $this->session = \Config\Services::session();
    }

    public function getDataWO()
    {
        return [
            ["PDNO" => "PKAB09028"], ["PDNO" => "PKAB09029"], ["PDNO" => "PKAB09294"], ["PDNO" => "PKAB09295"], ["PDNO" => "PKAB09296"], ["PDNO" => "PKAB09297"], ["PDNO" => "PKAB09298"], ["PDNO" => "PKAB09299"], ["PDNO" => "PKAB09300"], ["PDNO" => "PKAB09301"], ["PDNO" => "PKAB09302"], ["PDNO" => "PKAB09303"], ["PDNO" => "PKAB09304"], ["PDNO" => "PKAB09305"],
            ["PDNO" => "PKAS03434"], ["PDNO" => "PKAS03435"], ["PDNO" => "PKAS03436"], ["PDNO" => "PKAS03437"], ["PDNO" => "PKAS03438"], ["PDNO" => "PKAS03439"], ["PDNO" => "PKAS03440"], ["PDNO" => "PKAS03441"], ["PDNO" => "PKAS03442"], ["PDNO" => "PKAS03443"], ["PDNO" => "PKAS03444"], ["PDNO" => "PKAS03445"], ["PDNO" => "PKAS03446"], ["PDNO" => "PKAS03447"],
            ["PDNO" => "PKLC06294"], ["PDNO" => "PKLC06295"], ["PDNO" => "PKLC06296"], ["PDNO" => "PKLC06297"], ["PDNO" => "PKLC06298"], ["PDNO" => "PKLC06299"], ["PDNO" => "PKLC06300"], ["PDNO" => "PKLC06301"], ["PDNO" => "PKLC06302"], ["PDNO" => "PKLC06303"], ["PDNO" => "PKLC06304"], ["PDNO" => "PKLC06305"], ["PDNO" => "PKLC06306"], ["PDNO" => "PKLC06307"],
            ["PDNO" => "PKAV00132"], ["PDNO" => "PKAV00133"], ["PDNO" => "PKAV00135"], ["PDNO" => "PKAV00136"], ["PDNO" => "PKAV00137"], ["PDNO" => "PKAV00138"], ["PDNO" => "PKAV00139"], ["PDNO" => "PKAV00140"], ["PDNO" => "PKAV00141"], ["PDNO" => "PKAV00142"], ["PDNO" => "PKAV00143"], ["PDNO" => "PKAV00144"], ["PDNO" => "PKAV00145"], ["PDNO" => "PKAV00146"]
        ];
    }

    public function get_line()
    {
        $query = $this->db->query('SELECT * FROM master_line');

        return $query->getResultArray();
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

    public function getPartNo($no_wo)
    {
        return [];
    }

    public function getCT($part_no, $line)
    {
        $partno = "'" . "%" . $part_no . "'";

        $query = $this->db->query('
            SELECT first_cycle_time as cycle_time FROM master_cycle_time_infor
            WHERE part_number LIKE ' . $partno . ' AND first_line = \'' . $line . '\' ORDER BY id DESC
        ');

        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            $query = $this->db->query('
                SELECT second_cycle_time as cycle_time FROM master_cycle_time_infor
                WHERE part_number LIKE ' . $partno . ' AND second_line = \'' . $line . '\' ORDER BY id DESC
            ');

            return $query->getResultArray();
        }
    }

    public function getListBreakdown($line)
    {
        $query = $this->db->query('SELECT DISTINCT jenis_breakdown FROM data_breakdown WHERE ' . $line . '= \'1\'');

        return $query->getResultArray();
    }

    public function getListReject($line)
    {
        $query = $this->db->query('SELECT DISTINCT jenis_reject FROM data_reject WHERE ' . $line . '= \'1\'');

        return $query->getResultArray();
    }

    public function getListPending()
    {
        $query = $this->db->query('SELECT DISTINCT jenis_pending FROM master_pending_assy');

        return $query->getResultArray();
    }

    public function getProsesBreakdown($jenis_breakdown)
    {
        $query = $this->db->query('SELECT * FROM data_breakdown WHERE jenis_breakdown = \'' . $jenis_breakdown . '\'');

        return $query->getResultArray();
    }

    public function getKategoriReject($jenis_reject)
    {
        $query = $this->db->query('SELECT * FROM data_reject WHERE jenis_reject = \'' . $jenis_reject . '\'');

        return $query->getResultArray();
    }

    public function getKategoriPending($jenis_pending)
    {
        $query = $this->db->query('SELECT * FROM master_pending_assy WHERE jenis_pending = \'' . $jenis_pending . '\'');

        return $query->getResultArray();
    }

    public function save_lhp($data)
    {
        $this->db->table('lhp_produksi2')->insert($data);

        return $this->db->insertID();
    }

    public function save_detail_lhp($data)
    {
        $builder = $this->db->table('detail_lhp_produksi2');
        $builder->insert($data);

        return $this->db->insertID();
    }

    public function save_detail_breakdown($id, $data)
    {
        $builder = $this->db->table('detail_breakdown');

        if ($id != '') {
            $builder->where('id_breakdown', $id);
            $builder->update($data);
            return $id;
        } else {
            $builder->insert($data);
            return $this->db->insertID();
        }
    }

    public function save_detail_reject($id, $data)
    {
        $builder = $this->db->table('detail_reject');

        if ($id != '') {
            $builder->where('id_reject', $id);
            $builder->update($data);
            return $id;
        } else {
            $builder->insert($data);
            return $this->db->insertID();
        }
    }

    public function save_detail_pending($id, $data)
    {
        $builder = $this->db->table('detail_pending_assy');

        if ($id != '') {
            $builder->where('id_pending', $id);
            $builder->update($data);
            return $id;
        } else {
            $builder->insert($data);
            return $this->db->insertID();
        }
    }

    public function get_all_lhp($bulan)
    {
        $month = date('m', strtotime($bulan));
        $year = date('Y', strtotime($bulan));
        $builder = $this->db->table('lhp_produksi2');
        $builder->select('lhp_produksi2.id_lhp_2, lhp_produksi2.tanggal_produksi, lhp_produksi2.shift, lhp_produksi2.line, lhp_produksi2.kasubsie, master_pic_line.nama_pic, lhp_produksi2.total_line_stop, SUM(detail_breakdown.menit_breakdown) as detail_line_stop');
        $builder->join('master_pic_line', 'master_pic_line.id_pic = lhp_produksi2.grup');
        $builder->join('detail_breakdown', 'detail_breakdown.id_lhp = lhp_produksi2.id_lhp_2', 'left');
        $builder->where('MONTH(tanggal_produksi) =', $month);
        $builder->where('YEAR(tanggal_produksi) =', $year);

        if ($this->session->get('line') != NULL) {
            $builder->where('line', $this->session->get('line'));
        }
        $builder->groupBy('lhp_produksi2.id_lhp_2, lhp_produksi2.tanggal_produksi, lhp_produksi2.shift, lhp_produksi2.line, lhp_produksi2.kasubsie, master_pic_line.nama_pic, lhp_produksi2.total_line_stop');

        $builder->orderBy('tanggal_produksi', 'DESC');

        $query = $builder->get();

        return $query->getResultArray();
    }

    public function get_lhp_by_id($id_lhp)
    {
        $query = $this->db->query('SELECT * FROM lhp_produksi2 WHERE id_lhp_2 = ' . $id_lhp);

        return $query->getResultArray();
    }

    public function get_all_lhp_by_date($start_date, $end_date)
    {
        $builder = $this->db->table('lhp_produksi2');
        $builder->select('lhp_produksi2.*, master_pic_line.nama_pic');
        $builder->join('master_pic_line', 'master_pic_line.id_pic = lhp_produksi2.grup');
        $builder->where('line >= ', 1);
        $builder->where('line <= ', 7);
        $builder->where('tanggal_produksi >= ', $start_date);
        $builder->where('tanggal_produksi <= ', $end_date);

        $query = $builder->get();

        if (count($query->getResultArray()) > 0) {
            return $query->getResultArray();
        } else {
            return;
        }
    }

    public function get_detail_lhp_by_id($id_lhp)
    {
        $query = $this->db->query('SELECT * FROM detail_lhp_produksi2 WHERE id_lhp_2 = ' . $id_lhp);

        return $query->getResultArray();
    }

    public function get_all_detail_lhp()
    {
        $query = $this->db->query('SELECT * FROM detail_lhp_produksi2');

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

    public function update_lhp($id_lhp, $data)
    {
        $builder = $this->db->table('lhp_produksi2');
        $builder->where('id_lhp_2', $id_lhp);
        $builder->update($data);

        return $this->db->affectedRows();
    }

    public function update_detail_lhp($id_detail_lhp, $data)
    {
        $builder = $this->db->table('detail_lhp_produksi2');

        if ($id_detail_lhp != '') {
            $builder->where('id_detail_lhp', $id_detail_lhp);
            $builder->update($data);
            return $id_detail_lhp;
        } else {
            $builder->insert($data);
            return $this->db->insertID();
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

    public function get_detail_pending_by_id($id_lhp)
    {
        $query = $this->db->query('SELECT * FROM detail_pending_assy WHERE id_lhp = ' . $id_lhp);

        return $query->getResultArray();
    }

    public function cek_lhp($tanggal_produksi, $line, $shift, $grup)
    {
        $query = $this->db->query('SELECT * FROM lhp_produksi2 WHERE tanggal_produksi = \'' . $tanggal_produksi . '\' AND line = \'' . $line . '\' AND shift = \'' . $shift . '\' AND grup = \'' . $grup . '\'');

        return $query->getResultArray();
    }

    public function update_detail_breakdown($id_breakdown, $data)
    {
        $builder = $this->db->table('detail_breakdown');

        if ($id_breakdown != '') {
            $builder->where('id_breakdown', $id_breakdown);
            $builder->update($data);
            return $this->db->affectedRows();
        } else {
            $builder->insert($data);
            return $this->db->insertID();
        }
    }

    public function update_detail_reject($id_reject, $data)
    {
        $builder = $this->db->table('detail_reject');

        if ($id_reject != '') {
            $builder->where('id_reject', $id_reject);
            $builder->update($data);
            return $this->db->affectedRows();
        } else {
            $builder->insert($data);
            return $this->db->insertID();
        }
    }

    public function update_detail_pending($id_pending, $data)
    {
        $builder = $this->db->table('detail_pending_assy');

        if ($id_pending != '') {
            $builder->where('id_pending', $id_pending);
            $builder->update($data);
            return $this->db->affectedRows();
        } else {
            $builder->insert($data);
            return $this->db->insertID();
        }
    }

    public function get_data_grup_pic($id_grup)
    {
        $query = $this->db->query('SELECT * FROM master_pic_line WHERE id_pic = ' . $id_grup);

        return $query->getResultArray();
    }

    public function get_data_line($id_line)
    {
        $query = $this->db->query('SELECT * FROM master_line WHERE id_line = ' . $id_line);

        return $query->getResultArray();
    }

    public function hapus_lhp($id)
    {
        $this->db->query('DELETE FROM lhp_produksi2 WHERE id_lhp_2 = ' . $id);
        $this->db->query('DELETE FROM detail_lhp_produksi2 WHERE id_lhp_2 = ' . $id);
        $this->db->query('DELETE FROM detail_breakdown WHERE id_lhp = ' . $id);
        $this->db->query('DELETE FROM detail_reject WHERE id_lhp = ' . $id);
        $this->db->query('DELETE FROM detail_pending_assy WHERE id_lhp = ' . $id);
    }

    public function delete_line_stop($id_line_stop)
    {
        $this->db->query('DELETE FROM detail_breakdown WHERE id_breakdown = ' . $id_line_stop);
    }

    public function delete_reject($id_reject)
    {
        $this->db->query('DELETE FROM detail_reject WHERE id_reject = ' . $id_reject);
    }

    public function delete_pending($id_pending)
    {
        $this->db->query('DELETE FROM detail_pending_assy WHERE id_pending = ' . $id_pending);
    }

    public function get_total_menit_breakdown($id_lhp)
    {
        $query = $this->db->query('SELECT SUM(menit_breakdown) AS total_menit FROM detail_breakdown WHERE id_lhp = ' . $id_lhp);

        return $query->getResultArray();
    }

    public function get_all_tiket_andon()
    {
        $query = $this->db->query('SELECT tiket_andon FROM detail_breakdown WHERE jenis_breakdown = \'ANDON\' AND (kategori_andon IS NULL OR kategori_andon = \'\')');

        return $query->getResultArray();
    }

    public function update_kategori_andon($id, $data)
    {
        $builder = $this->db->table('detail_breakdown');
        $builder->where('tiket_andon', $id);
        $builder->update($data);

        return $this->db->affectedRows();
    }
}
