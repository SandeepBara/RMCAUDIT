<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class Trade_update_dtl_model extends Model
{
	protected $db;
    protected $table = 'tbl_update_dtl';
    protected $allowedFields = ['id','apply_id', 'updated_by', 'reason', 'file_path', 'updated_date', 'created_on', 'status'];

    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }
	 public function insertUpdateDtl($data){
    try
    {
      $builder = $this->db->table($this->table)
            ->insert([
                "apply_id" => $data['apply_id'],
                "updated_by" => $data['updated_by'],
                "reason" => $data['reason'],
                "updated_date" => $data['updated_date'],
                "created_on" => $data['created_on']
            ]);
            //echo $this->db->getLastQuery();
        return $insert_id = $this->db->insertId();
    }catch(Exception $e){
        echo $e->getMessage();
    }
  }

  public function uploadDocument($newName,$id){
    try{
        return $builder = $this->db->table($this->table)
                        ->where('id',$id)
                        ->update([
                                  'file_path'=>$newName
                                  ]);
                        //echo $this->db->getLastQuery();
    }catch(Exception $e){
      echo $e->getMessage();
    }
  }
}
?>