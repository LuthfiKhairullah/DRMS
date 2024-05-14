<?php

namespace App\Models;

use CodeIgniter\Model;

class M_Envelope extends Model
{
    protected $table = 'envelope';
    protected $allowedFields = ['id', 'date', 'line', 'shift', 'team', 'status'];
}
