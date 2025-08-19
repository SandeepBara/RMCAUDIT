<?php 
namespace App\Models\Water;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class TblRequestType  extends Model
{
    protected $db;
    protected $table = 'tbl_request_types';
    public function __construct(ConnectionInterface $db){
        $this->db = $db;
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

    public function getAllData(){
        return self::select("*")->where("status",1)->get()->getResultArray();
    }
}