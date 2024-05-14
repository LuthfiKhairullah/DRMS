<?php

namespace App\Models;

use CodeIgniter\Model;

class M_Team extends Model
{
    protected $table = 'master_pic_line';
    protected $allowedFields = ['nama_pic', 'status'];
}
