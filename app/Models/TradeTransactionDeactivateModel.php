<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class TradeTransactionDeactivateModel extends Model 
{
    protected $table = 'tbl_transaction_deactivate_dtl';
    protected $allowedFields = ['id', 'transaction_id','deactivated_by', 'reason', 'file_path','deactive_date','created_on'];
    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    } 
    public function insertData($input){
        $builder = $this->db->table($this->table)
                   ->insert([
                  "transaction_id"=>$input["transaction_id"],
                  "deactivated_by"=>$input["deactivated_by"],
                  "reason"=>$input["remark"],
                  "deactive_date"=>$input["deactive_date"],
                  "created_on"=>$input["created_on"]
          ]);
         // echo $this->db->getLastQuery();
        return $insert_id = $this->db->insertID();
    }
    public function uploadDocument($newName,$id){
    try{
        return $builder = $this->db->table($this->table)
                        ->where('id',$id)
                        ->update([
                                  'file_path'=>$newName
                                  ]);
                      //  echo $this->getLastQuery();
    }catch(Exception $e){
      echo $e->getMessage();
    }
  }
  public function getAllDeactivatedTransaction($data){
    try{
          $builder = $this->db->table($this->table)
                    ->select('*')
                    ->where('deactive_date>=',$data['from_date'])
                    ->where('deactive_date<=',$data['to_date'])
                    ->orderBy('deactive_date','ASC')
                    ->get();
                 // echo $this->db->getLastQuery();
          return $builder->getResultArray();
    }catch(Exception $e){
            echo $e->getMessage();
      }
  }
}
