<?php

namespace App\Models;

use CodeIgniter\Model;

class M_MasterLine extends Model
{
    protected $table = 'master_line';
    protected $allowedFields = ['id_line', 'nama_line'];
}
