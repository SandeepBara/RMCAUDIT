<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class water_new_connection_deactivation_model extends Model
{

    protected $table = 'tbl_new_connection_deactivation';
    protected $allowedFields = ['id','apply_water_connection_id','deactivation_date','remark','doc_path','emp_details_id','created_on','status','ward_mstr_id'];

	public function __construct(ConnectionInterface $db)
	{
	    $this->db = $db;
	}
    public function insertNewConnectionDeactivationData($input){
        try{
            $builder = $this->db->table($this->table)
                    ->insert([
                        "apply_water_connection_id"=>$input['id'],
                        "deactivation_date"=>$input['deactivation_date'],
                        "remark"=>$input['remark'],
                        "emp_details_id"=>$input['emp_details_id'],
                        "created_on"=>$input['created_on'],
                        "ward_mstr_id" =>$input['ward_mstr_id']
                  ]);
               //echo $this->db->getLastQuery();die;
        return $insert_id = $this->db->insertID();
        }catch(Exception $e){
            echo $this->getMessage();
        }
    }
    public function uploadDocument($newName,$id){
    try{
        return $builder = $this->db->table($this->table)
                        ->where('id',$id)
                        ->update([
                                  'doc_path'=>$newName
                                  ]);
                        echo $this->getLastQuery();
    }catch(Exception $e){
      echo $e->getMessage();
    }
  }
   
}