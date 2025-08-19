<?php 
namespace App\Models\DbSystem;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class TblOtp  extends Model{
    protected $db;
    protected $table = 'tbl_otp';    
    protected $allowedFields = [];
    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }

    public function generateOtp()
    {
        $otp = str_pad(rand(100000, 999999),6,"0");
        return $otp;
    }
    public function store(array $data)
    {
        $this->db->table($this->table)
                ->insert($data);
        $insert_id=$this->db->insertID();
        return $insert_id;
    }

    public function updateData(int $id,array $data){
        return $this->db->table($this->table)->where("id",$id)->update($data);
    }

    
}