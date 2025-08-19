<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class model_prop_saf_deactivation extends Model
{
	protected $db;
  protected $table = 'tbl_prop_saf_deactivation';
  protected $allowedFields = ['id','created_on','emp_details_id','deactivation_date','remark','doc_path','status','prop_type','prop_dtl_id','ward_mstr_id'];
  public function __construct(ConnectionInterface $db){
    $this->db = $db;
  }
  public function insertData($input){
    try
    {
      $builder = $this->db->table($this->table)
            ->insert([
                "created_on" => $input['created_on'],
                "emp_details_id" => $input['emp_details_id'],
                "prop_dtl_id" => $input['prop_dtl_id'],
                "remark" => $input['remark'],
                "deactivation_date" => $input['deactivation_date'],
                "prop_type" => $input['prop_type'],
                "ward_mstr_id" => $input['ward_mstr_id']
            ]);
           // echo $this->db->getLastQuery();
        return $insert_id = $this->db->insertId();
    }catch(Exception $e){
        echo $e->getMessage();
    }
  }

  public function insertDeactivationData($input){
    try
    {
      $builder = $this->db->table($this->table)
            ->insert([
                "prop_dtl_id" => $input['prop_dtl_id'],
                "prop_type" => $input['prop_type'],
                "deactivation_date" => $input['deactivation_date'],
                "remark" => $input['remark'],
                "emp_details_id" => $input['emp_details_id'],
                "created_on" => $input['created_on'],
                "status" => 1,
                "ward_mstr_id" => $input['ward_mstr_id'],
                "activate_deactivate_status" => $input['activate_deactivate_status']
            ]);
          //  echo $this->db->getLastQuery();
          // //  die;
          // echo "data is inserted";
          
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
                                  'doc_path'=>$newName
                                  ]);
                        //echo $this->getLastQuery();
    }catch(Exception $e){
      echo $e->getMessage();
    }
  }
  public function getHoldingDeactivationDetails($from_date,$to_date,$ward_mstr_id){
    try{
        $builder = $this->db->table($this->table)
                 ->select('*')
                 ->where('deactivation_date >=',$from_date)
                 ->where('deactivation_date <=',$to_date)
                 ->where('prop_type','Property')
                 ->where('ward_mstr_id',$ward_mstr_id)
                 ->where('status',1)
                 ->get();
                //echo $this->getLastQuery();
         return $builder->getResultArray();        
    }catch(Exception $e){
      echo $e->getMessage();
    }
  }
  public function getSafDeactivationDetails($from_date,$to_date,$ward_mstr_id){
    try{
        $builder = $this->db->table($this->table)
                 ->select('tbl_prop_saf_deactivation.*,emp_name')
                 ->join('view_emp_details','view_emp_details.id = tbl_prop_saf_deactivation.emp_details_id')
                 ->where('deactivation_date >=',$from_date)
                 ->where('deactivation_date <=',$to_date)
                 ->where('ward_mstr_id',$ward_mstr_id)
                 ->where('prop_type','Saf')
                 ->where('status',1)
                 ->get();
                //echo $this->getLastQuery();
         return $builder->getResultArray();        
    }catch(Exception $e){
      echo $e->getMessage();
    }
  }
  public function getAllHoldingDeactivationDetails($from_date,$to_date){
    try{
        $builder = $this->db->table($this->table)
                 ->select('*')
                 ->where('deactivation_date >=',$from_date)
                 ->where('deactivation_date <=',$to_date)
                 ->where('prop_type','Property')
                 ->where('status',1)
                 ->get();
                 //echo $this->getLastQuery();
         return $builder->getResultArray();        
    }catch(Exception $e){
      echo $e->getMessage();
    }
  }
  public function getAllSafDeactivationDetails($from_date,$to_date){
    try{
        $builder = $this->db->table($this->table)
                 ->select('tbl_prop_saf_deactivation.*,emp_name')
                 ->join('view_emp_details','view_emp_details.id = tbl_prop_saf_deactivation.emp_details_id')
                 ->where('deactivation_date >=',$from_date)
                 ->where('deactivation_date <=',$to_date)
                 ->where('prop_type','Saf')
                 ->where('status',1)
                 ->get();
                 // echo $this->getLastQuery();
         return $builder->getResultArray();        
    }catch(Exception $e){
      echo $e->getMessage();
    }
  }
  public function getPropertDeactivationPreventData($prop_dtl_id){
    try{
         $builder = $this->db->table($this->table)
                          ->select('id')
                          ->where('prop_dtl_id',$prop_dtl_id)
                          ->where('prop_type','Property')
                          ->where('status',1)
                          ->get();
          $builder = $builder->getFirstRow("array");
          return $builder['id'];
    }catch(Exception $e){
      echo $e->getMessage();
    }
  }
  public function getSafDeactivationPreventData($prop_dtl_id){
    try{
      $builder = $this->db->table($this->table)
                ->select('id')
                ->where('prop_dtl_id',$prop_dtl_id)
                ->where('prop_type','Saf')
                ->where('status',1)
                ->get();
        $builder = $builder->getFirstRow("array");
        return $builder['id'];
    }catch(Exception $e){
      echo $e->getMessage();
    }
  }
}
?>