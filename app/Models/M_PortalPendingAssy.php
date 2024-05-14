<?php 

namespace App\Models;

use CodeIgniter\Model;
use Config\Services;

class M_PortalPendingAssy extends Model
{
    public function __construct()
    {
        $this->db = \Config\Database::connect();

        $this->session = \Config\Services::session();
    }

    public function get_all_pending_assy($bulan)
    {
        $query = $this->db->query('SELECT *, dpa.status AS status_pending FROM detail_pending_assy dpa
                                  JOIN lhp_produksi2 lp ON lp.id_lhp_2 = dpa.id_lhp
                                  JOIN master_pic_line mpl ON mpl.id_pic = lp.grup
                                  WHERE MONTH(tanggal_produksi) = \'' . $bulan . '\'
                                  ORDER BY tanggal_produksi DESC
                                  ');

        return $query->getResultArray();
    }

    public function get_grup()
    {
        $query = $this->db->query('SELECT * FROM master_pic_line');

        return $query->getResultArray();
    }

    public function update_detail_pending($id_pending, $data)
    {
      $builder = $this->db->table('detail_pending_assy');
      $builder->where('id_pending', $id_pending);
      $builder->update($data);

      return $id_pending;
    }

    public function get_status_no_wo($id_pending, $no_wo)
    {
        $client = Services::curlrequest();
        $url = "https://portal3.incoe.astra.co.id/production_control_v2/api/get_status_no_wo/$no_wo";
        $response = $client->request('GET', $url);
        $data_api = json_decode($response->getBody(), true);

        if($data_api[0]['STATUS_WO'] >= 8) {
            $data = [
                'status' => 'closed'
            ];
            $builder = $this->db->table('detail_pending_assy');
            $builder->where('id_pending', $id_pending);
            $builder->update($data);
        }

        return $data_api;
    }

    public function get_no_rfq_no_wo($id_pending, $no_wo)
    {
        $client = Services::curlrequest();
        $url = "https://portal3.incoe.astra.co.id/production_control_v2/api/get_status_no_wo/$no_wo";
        $response = $client->request('GET', $url);
        $data_api = json_decode($response->getBody(), true);

        $data = [
            'no_rfq' => $data_api[0]['NO_RFQ']
        ];
        $builder = $this->db->table('detail_pending_assy');
        $builder->where('id_pending', $id_pending);
        $builder->update($data);

        return $data_api;
    }
  }
  ?>