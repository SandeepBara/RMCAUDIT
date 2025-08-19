<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class model_firm_owner_name extends Model 
{
    protected $db;
    protected $table = 'tbl_firm_owner_name';

    protected $primaryKey = 'id';

    protected $allowedFields = ['id', 'apply_licence_id', 'owner_name', 'address', 'city', 'district', 'state', 'mobile', 'email_id','emp_details_id','created_on','status'];

    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }

    public function applicantdetails($trade_conn_id)
    {      
      try{
        $builder = "select tbl_firm_owner_name.*,tbl_document.doc_name FROM tbl_firm_owner_name
        left join tbl_document on tbl_document.id=tbl_firm_owner_name.document_id
        where tbl_firm_owner_name.apply_licence_id=".$trade_conn_id." and tbl_firm_owner_name.status=1";
			$ql= $this->query($builder);
			//echo $this->getLastQuery();
			return $ql->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
	
	
	public function firm_ownerdetails($trade_conn_id)
    {    
		try{
            $builder = $this->db->table($this->table)
                        ->select('*')
                        //->where('status', 1)
                        ->where('apply_licence_id', $trade_conn_id)
                        ->get(); 
           return $builder->getResultArray();

        }catch(Exception $e){
            return $e->getMessage();   
        }
      
    }
	
	
	public function applicantdetl($trade_conn_id)
    {      //print_r($trade_conn_id);
      try{
            $builder = $this->db->table($this->table)
                        ->select('*')
                        ->where('status', 1)
                        ->where('md5(apply_licence_id::text)', $trade_conn_id)
                        ->get(); 
						//echo $this->db->getLastQuery();
           return $builder->getResultArray();

        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
	
    public function applicantdetails_md5($trade_conn_id)
    {      //print_r($trade_conn_id);
      try{
            $builder = $this->db->table($this->table)
                        ->select('*')
                        ->where('status', 1)
                        ->where('md5(apply_licence_id::text)', $trade_conn_id)
                        ->get(); 
           return $builder->getResultArray();

        }catch(Exception $e){
            return $e->getMessage();   
        }
    }

    //owner document list count 
    public function count_doc_list_owner($trade_conn_id)
    {     
      try{
            $builder = $this->db->table($this->table)
                        ->select('count(id)')
                        ->where('status', 1)
                        ->where('md5(apply_licence_id::text)', $trade_conn_id)
                        ->get(); 
                        //echo$this->db->getLastQuery();
           return $builder->getResultArray()['0'];

        }catch(Exception $e){
            return $e->getMessage();   
        }
    }


     public function getOwnerDetails($trade_conn_id)
    {      
      try{
            $builder = $this->db->table($this->table)
                        ->select('*')
                        ->where('id',1)
                        ->orderBy('id')
                        ->get(); 
           return $builder->getResultArray();

        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function getFirmOwnerDetailsByApplyId($id){
        try{
            $builder = $this->db->table($this->table)
                      ->select('*')
                      ->where('md5(apply_licence_id::text)',$id)
                      ->where('status',1)
                      ->get();
            return $builder->getResultArray();
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function updateRejectStatus($apply_licence_id,$emp_details_id){
        try{
            $builder = $this->db->table($this->table)
                     ->where('md5(apply_licence_id::text)',$apply_licence_id)
                     ->update([
                                "status"=>0,
                                "emp_details_id"=>$emp_details_id

                            ]);
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
    

}