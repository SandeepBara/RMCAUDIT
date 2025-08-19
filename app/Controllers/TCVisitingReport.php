<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_ward_mstr;
use App\Models\model_user_hierarchy;

class TCVisitingRepot extends AlphaController
{
    protected $db;
    protected $model_ward_mstr;
    protected $model_view_prop_dtl_demand;
    protected $model_view_prop_dtl_collection;
    public function __construct(){
        parent::__construct();
    	helper(['db_helper']);
        if($db_name = dbConfig("property")){
            $this->db = db_connect($db_name); 
        }
        if($db_name = dbSystem()){
            $this->dbSystem = db_connect($db_name); 
        }
        $this->model_ward_mstr = new model_ward_mstr($this->dbSystem);
        $this->model_user_hierarchy=new model_user_hierarchy($this->dbSystem);
        $this->model_tc_activity=new model_tc_activity($this->db);
        

    }
    public function report()
    {
        $data =(array)null;
        helper(['form']);
        $session = session();
        $ulb_dtl = $session->get('ulb_dtl');
        $ulb_mstr_id = $ulb_dtl['ulb_mstr_id'];
        //Transaction Mode List
        
        //$data['userlist']=$this->model_user_hierarchy->getUserList();

        if($this->request->getMethod()=='post')
        {
           
            
           // $data['userlogin']=$this->model_user_hierarchy->user_logins();
            
           // $data['activity_details']=$this->model_tc_activity->
        
        }       
         
         return view('report/tcvistingreport',$data);
            
            
       
      
    }
}
?>
