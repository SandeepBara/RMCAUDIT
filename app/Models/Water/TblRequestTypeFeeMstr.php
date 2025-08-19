<?php 
namespace App\Models\Water;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class TblRequestTypeFeeMstr  extends Model
{
    protected $db;
    protected $table = 'tbl_request_type_fee_mstr';
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
        return self::select("*")->orderBy("request_type_id","ASC")->orderBy("effective_from","DESC")->get()->getResultArray();
    }

    public function getRate($request_type_id, $currentDate =null){
        if(!$currentDate){
            $currentDate= date("Y-m-d");
        }
        $rate = self::select("*")->where("request_type_id",$request_type_id)
            ->where("effective_from <=",$currentDate)
            ->where("status",1)
            ->orderBy("effective_from","DESC")
            ->get()
            ->getFirstRow("array");
        if(!$rate){
            $rate = self::select("*")->where("request_type_id",$request_type_id)
                ->where("effective_from",">",$currentDate)
                ->where("status",1)
                ->orderBy("effective_from","DESC")
                ->get()
                ->getFirstRow("array");

        }
        return $rate;
    }
}