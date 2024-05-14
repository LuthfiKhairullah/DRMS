<?php namespace App\Models;
use CodeIgniter\Model;



class M_Log extends Model
{
    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->db2 = \Config\Database::connect('sqlsrv');
        $this->db3 = \Config\Database::connect('baan');
        $this->db4 = \Config\Database::connect('prod_control');

        $this->session = \Config\Services::session();
    }

    public function save_log($data)
    {
        $this->db->table('log_lhp')->insert($data);
    }
}
