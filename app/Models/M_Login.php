<?php

namespace App\Models;

use CodeIgniter\Model;

class M_Login extends Model
{
    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function cek_login($username, $password)
    {
        $query = $this->db->query('SELECT * FROM users WHERE username = \'' . $username . '\' AND password = \'' . $password . '\'');

        return $query->getRowArray();
    }
}
