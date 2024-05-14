<?php

namespace App\Models;

use CodeIgniter\Model;

class M_PlateCutting extends Model
{
    protected $table = 'platecutting';
    protected $allowedFields = ['id', 'date', 'line', 'shift', 'team', 'status'];
}
