<?php

namespace App\Models;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class model_water_reading_doc extends Model
{
    protected $db;
    protected $table = 'tbl_meter_reading_doc';

    protected $primaryKey = 'id';

    public function __construct(ConnectionInterface $db)
    {
        $this->db = $db;
    }
    public function insert_meter_reading_doc(array $data)
    {   
        try
        {
            $this->db->table($this->table)->insert($data);            
            return $this->db->insertID();

        }
        catch(Exception $e)
        {
            print_var($e->getMessage());
            return false;
        }
        
    }

    public function update_meter_reading_doc(array $where,array $data)
    {   
        try
        {
            $this->db->table($this->table)->where($where)->update($data);
            //print_var($db->affectedRows());
            return$this->db->affectedRows();

        }
        catch(Exception $e)
        {
            print_var($e->getMessage());
            return false;
        }
        
    }
}