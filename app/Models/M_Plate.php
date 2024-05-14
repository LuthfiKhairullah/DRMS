<?php

namespace App\Models;

use CodeIgniter\Model;

class M_Plate extends Model
{
    protected $table = 'plate';
    protected $allowedFields = ['plate', 'berat'];
}
