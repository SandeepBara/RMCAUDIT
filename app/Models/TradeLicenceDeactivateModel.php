<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class TradeLicenceDeactivateModel extends Model 
{
    protected $table = 'tbl_licence_deactivate_dtl';
    protected $allowedFields = ['id','licence_id','deactivated_by', 'reason', 'file_path','deactivate_date','created_on'];
    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    } 
    public function insertDeactivateData($input){
        $builder = $this->db->table($this->table)
                   ->insert([
                  "licence_id"=>$input["licence_id"],
                  "deactivated_by"=>$input["deactivated_by"],
                  "reason"=>$input["reason"],
                  "deactivate_date"=>$input["deactivate_date"],
                  "created_on"=>$input["created_on"]
          ]);
          echo $this->db->getLastQuery();
        return $insert_id = $this->db->insertID();
    }
    public function uploadDocument($newName,$id){
    try{
        return $builder = $this->db->table($this->table)
                        ->where('id',$id)
                        ->update([
                                  'file_path'=>$newName
                                  ]);
                        //echo $this->getLastQuery();
    }catch(Exception $e){
      echo $e->getMessage();
    }
  }
}
