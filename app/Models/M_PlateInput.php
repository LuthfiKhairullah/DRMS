<?php

namespace App\Models;

use CodeIgniter\Model;

class M_PlateInput extends Model
{
    protected $table = 'plateinput';
    protected $allowedFields = ['id', 'id_platecutting', 'plate', 'barcode', 'act', 'deviasi', 'hasil_produksi', 'terpotong_panel', 'tersangkut_panel', 'overbrush_panel', 'rontok_panel', 'lug_patah_panel', 'patah_kaki_panel', 'patah_frame_panel', 'bolong_panel', 'bending_panel', 'lengket_terpotong_panel', 'terpotong_kg', 'tersangkut_kg', 'overbrush_kg', 'rontok_kg', 'lug_patah_kg', 'patah_kaki_kg', 'patah_frame_kg', 'bolong_kg', 'bending_kg', 'lengket_terpotong_kg', 'persentase_reject_internal', 'persentase_reject_eksternal', 'persentase_reject_akumulatif'];
}
