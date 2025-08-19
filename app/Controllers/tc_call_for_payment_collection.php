<?php namespace App\Controllers;


use CodeIgniter\Controller;
use App\Models\PostModel;
use App\Models\model_ward_mstr;
use App\Models\model_ulb_mstr;
use App\Models\model_prop_dtl;
use App\Models\model_prop_owner_detail;
use App\Models\model_prop_demand;
use App\Models\model_ward_permission;
use App\Models\model_tc_call;


class tc_call_for_payment_collection extends AlphaController
{
	protected $db;
	protected $dbSystem;
	//protected $db_name;
	protected $model_ward_mstr;
	protected $model_ulb_mstr;
	protected $model_prop_dtl;
	protected $model_prop_owner_detail;
	protected $model_prop_demand;
	protected $model_ward_permission;
	protected $model_tc_call;


	public function __construct()
    {
        parent::__construct();
    	helper(['db_helper']);
    	//	$this->load->library('phpqrcode/qrlib');
        if($db_name = dbConfig("property")){
			//echo $db_name;
            $this->db = db_connect($db_name);            
        }
        if($db_system = dbSystem()){
            $this->dbSystem = db_connect($db_system); 
        } 
		
        helper(['form']);
		//$db_name = db_connect("db_rmc_property");		
        $this->model = new model_ward_mstr($this->dbSystem);
		$this->modelUlb = new model_ulb_mstr($this->dbSystem);
		$this->model_prop_dtl = new model_prop_dtl($this->db);
		$this->modelowner = new model_prop_owner_detail($this->db);
		$this->modeldemand = new model_prop_demand($this->db);
		$this->model_ward_permission = new model_ward_permission($this->dbSystem);
		$this->model_tc_call = new model_tc_call($this->dbSystem);
		
    }
	
	
	
	public function citizenRequest_list()
	{
		
		$data =(array)null;
		$Session = Session();
        
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
        $data['emp_details_id'] = $emp_mstr["id"];
		$data['emp_type_id'] = $emp_mstr["user_type_mstr_id"];
        helper(['form']);
        if($this->request->getMethod()=='post'){
			$inputs = filterSanitizeStringtoUpper($this->request->getVar());
			$data['from_date']=$inputs['from_date'];
			$data['to_date']=$inputs['upto_date'];
			$data['callTcCitizen_list'] = $this->model_tc_call->callTcCitizen_list($data['emp_details_id'],$data['from_date'],$data['to_date'],$data['emp_type_id']);
			
			return view('tc_call_to_visit', $data);
		
		} else{
			$data['from_date'] = date('Y-m-d');
			$data['to_date'] = date('Y-m-d');
			$data['callTcCitizen_list'] = $this->model_tc_call->callTcCitizen_list($data['emp_details_id'],$data['from_date'],$data['to_date'],$data['emp_type_id']);			//$data['emp_ward'] = $this->model_ward_permission->emp_ward($data['emp_details_id']);
			
			return view('tc_call_to_visit', $data);
		}
	}
	
	
	

}
