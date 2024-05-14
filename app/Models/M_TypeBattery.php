<?php

namespace App\Models;

use CodeIgniter\Model;

class M_TypeBattery extends Model
{
    protected $table = 'data_type_battery';
    protected $allowedFields = ['id', 'type_battery'];
}
