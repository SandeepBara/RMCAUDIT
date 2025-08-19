<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class trade_view_application_doc_model extends Model 
{
    protected $db;
    protected $table = 'view_application_doc';

    protected $primaryKey = 'id';

    protected $allowedFields = ['id','apply_licence_id','doc_for','document_id','document_path','emp_details_id','remarks','verify_status','verified_by_emp_id','verified_on','created_on','status'];

    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }

    public function getdoc_by_appid($apply_licence_id)
    {
        try
        { 
            $data= $this->db->table($this->table)
                        ->select('*')
                        ->where('apply_licence_id', $apply_licence_id)
                        ->where('status', 1)
                        //->where('verify_status <>', 2)
                        ->orderBy('id')
                        ->get()
                        ->getResultArray();
            //echo $this->db->getLastQuery();
            return $data;
        }
        catch(Exception $e)
        {
            return $e->getMessage();
        }
    }

    public function getdocdet_by_appid($apply_licence_id)
    {
        try{
             $data = $this->db->table($this->table)
                        ->select('*')
                        ->where('apply_licence_id',$apply_licence_id)                        
                        ->where('status',1)
                       // ->where('verify_status<>',2)
                        ->orderBy('id')
                        ->get()
                        ->getResultArray(); 
                        //echo $this->db->getLastQuery();
             return $data;
        }catch(Exception $e){
            return $e->getMessage();
        }
    }

    public function get_doc_det_by_appid($apply_licence_id)
    {
        try{
             return $this->db->table($this->table)
                        ->select('*')
                        ->where('apply_licence_id',$apply_licence_id)                        
                        ->where('status',1)
                        ->where('verify_status<>', 2)
                        ->orderBy('id')
                        ->get()
                        ->getResultArray(); 
                        //echo $this->db->getLastQuery();
        }catch(Exception $e){
            return $e->getMessage();
        }
    }

    public function getAllActiveDocuments($apply_licence_id)
    {
        try
        {
             $builder= $this->db->table($this->table)
                        ->select('*')
                        ->where('apply_licence_id',$apply_licence_id)                        
                        ->where('status',1)
                        //->where('verify_status <>',2)
                        ->orderBy('id')
                        ->get();
            
            //echo $this->db->getLastQuery();
            return $builder->getResultArray(); 
        }
        catch(Exception $e)
        {
            return $e->getMessage();
        }
    }
    
    public function deactivateRejectedDocument($apply_licence_id)
    {
        try
        {
            $builder = $this->db->table('tbl_application_doc')
                            ->where('apply_licence_id', $apply_licence_id)
                            ->where('verify_status', 2)
                            ->where('status', 1)
                            ->update([
                                    'status'=> 0                                  
                                    ]);
            
            //echo $this->db->getLastQuery();
            return $builder; 
        }
        catch(Exception $e)
        {
            return $e->getMessage();
        }
    }
    

    public function updateverifystatusById($lvl_pending_id, $login_emp_details_id, $app_doc_id, $status, $remarks=null){
          $builder = $this->db->table('tbl_application_doc')
                            ->where('id', $app_doc_id)
                            ->update([
                                    'verify_status'=>$status,
                                    'remarks'=>$remarks, 
                                    'verified_by_emp_id'=>$login_emp_details_id,
                                    'lvl_pending_id'=>$lvl_pending_id,
                                    'verified_on'=> "NOW()"                                   
                                    ]);
        //echo $this->db->getLastQuery();exit;
        return $builder;
    }

    public function VerifyDocument($app_doc_id, $inputs)
    {
        $builder = $this->db->table('tbl_application_doc')
                          ->where('id', $app_doc_id)
                          ->update([
                                  'verify_status'=> $inputs["verify_status"],
                                  'remarks'=> $inputs["remarks"], 
                                  'verified_by_emp_id'=> $inputs["verified_by_emp_id"],
                                  'lvl_pending_id'=> $inputs["lvl_pending_id"],
                                  'verified_on'=> "NOW()",                    
                                  ]);
      //echo $this->db->getLastQuery();exit;
      return $builder;
  }

public function VerifyAllDocument($apply_licence_id, $inputs)
{
    $builder = $this->db->table('tbl_application_doc')
                        ->where('apply_licence_id', $apply_licence_id)
                        ->where('verify_status', 0)
                        ->where('status', 1)
                        ->update([
                                'verify_status'=> $inputs["verify_status"],
                                'remarks'=> $inputs["remarks"], 
                                'verified_by_emp_id'=> $inputs["verified_by_emp_id"],
                                'lvl_pending_id'=> $inputs["lvl_pending_id"],
                                'verified_on'=> "NOW()",                    
                            ]);
    //echo $this->db->getLastQuery();exit;
    return $builder;
}

    

}                
