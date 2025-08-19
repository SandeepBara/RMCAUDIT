<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use Exception;

class model_view_emp_details extends Model 
{
    protected $db;
    protected $table = 'view_emp_details';    
    public function __construct(ConnectionInterface $db){
        $this->db = $db;
    }
    public function empList($ulb_mstr_id )
    {
        try{
            $builder = $this->db->table('view_emp_details')
                    ->select('*')
                    ->where('status',1)
                    ->where('ulb_mstr_id', $ulb_mstr_id)
                    ->where('user_for','AGENCY')
                    ->orderBy('id','desc')
                    ->get();

            // echo $this->db->getLastQuery();
            //$query = $this->db->query($sql);
            //print_r($query);
            //$query->pg_num_rows();

            return $builder->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function empDetailsListById($id)
    {
        try{
            $builder = $this->db->table('view_emp_details')
                    ->select('*')
                    ->where('status',1)
                    ->where('id',$id)
                    ->orderBy('id','desc')
                    ->get();
            return $builder->getResultArray()[0];
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
    public function empUlbList($ulb_mstr_id)
    {
        try{
            $builder = $this->db->table('view_emp_details')
                    ->select('*')
                    ->where('status',1)
                    ->where('ulb_mstr_id',$ulb_mstr_id)
                    ->where('user_for','ULB')
                    ->orderBy('id','desc')
                    ->get();
            return $builder->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }

    public function getEmpListByAlTaxCollector($inputs)
    {
        try{
            $builder = $this->db->table($this->table)
                    ->select('id, emp_name, middle_name, last_name, user_type, status')
                    ->where('ulb_mstr_id', $inputs['ulb_mstr_id'])
                    ->where('lock_status', 0)
                    ->whereIN('user_type_mstr_id', [4, 5, 8])
                    ->orderBy('emp_name, middle_name, last_name','desc')
                    ->get();
                    //echo $this->db->getLastQuery();
            return $builder->getResultArray();
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }

    //get employee user type 
    public function getEmpListUserType($emp_id)
    {
        try{
            $builder = $this->db->table($this->table)
                    ->select('id,user_type,user_type_mstr_id')
                    ->where('id',$emp_id)
                     ->get();
                   // echo $this->db->getLastQuery();
            return $builder->getResultArray()[0];
        }catch(Exception $e){
            return $e->getMessage();   
        }
    }
	
	public function get_team_leader(){

          $result=$this->db->table($this->table)
                            ->select("id,emp_name")
                            ->where('status',1)
							->where('lock_status',0)
                            ->where('user_type_mstr_id',4)
                            ->get();    
                              //echo $this->db->getLastQuery();                      
          return $result->getResultArray();
    }
	
	public function get_tax_collector($report_to){

          $result=$this->db->table($this->table)
                            ->select("id,emp_name")
                            ->where('status',1)
							->where('lock_status',0)
                            ->where('report_to',$report_to)
                            ->get();    
                              //echo $this->db->getLastQuery();                      
          return $result->getResultArray();
    }
	
	public function get_tc_name(){

          $result=$this->db->table($this->table)
                            ->select("id,emp_name")
                            ->where('status',1)
							->where('lock_status',0)
							->where('user_type_mstr_id',5)
                            ->get();    
                              //echo $this->db->getLastQuery();                      
          return $result->getResultArray();
    }
}