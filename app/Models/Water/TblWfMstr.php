<?php 
namespace App\Models\Water;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class TblWfMstr  extends Model
{
    protected $db;
    protected $table = 'tbl_wf_mstr';
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
        return self::select("*")->orderBy("request_type_id","ASC")->orderBy("id","DESC")->get()->getResultArray();
    }

    public function getWf(int $request_type_id){
        return self::select("*")->where("request_type_id",$request_type_id)->where("status",1)->orderBy("id","DESC")->get()->getFirstRow("array");
    }
    
}