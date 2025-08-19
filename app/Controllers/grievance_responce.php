<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\PostModel;
use App\Models\model_ward_mstr;
use App\Models\model_ulb_mstr;
use App\Models\model_fy_mstr;
use App\Models\grievance_chat_details_model;
use App\Models\grievance_details_model;

class grievance_responce extends AlphaController
{
	protected $db;
	protected $dbSystem;
	protected $model_ward_mstr;
	protected $model_ulb_mstr;
	protected $model_fy_mstr;
	protected $grievance_chat_details_model;
	protected $grievance_details_model;
	

	public function __construct()
    {
        parent::__construct();
    	helper(['db_helper']);
        
        if($db_system = dbSystem())
		{
            $this->dbSystem = db_connect($db_system); 
        } 
		
        helper(['form']);
		//$db_name = db_connect("db_rmc_property");		
        $this->model = new model_ward_mstr($this->dbSystem);
		$this->modelUlb = new model_ulb_mstr($this->dbSystem);
		$this->modelfy = new model_fy_mstr($this->dbSystem);
		$this->grievance_chat_details_model = new grievance_chat_details_model($this->dbSystem);
		$this->grievance_details_model = new grievance_details_model($this->dbSystem);
    }
	
	
	public function grievance_list()
	{
		$data =(array)null;
		$Session = Session();
        helper(['form']);
        if($this->request->getMethod()=='post'){
			$data = [
						'from' => $this->request->getVar('from_date'),
						'to' => $this->request->getVar('to_date'),
						'type' => $this->request->getVar('grievance_type')
					];
					//print_r($data);
			if($data['type']!=""){
				$data['grievance_list'] = $this->grievance_details_model->grievance_list($data);
				//print_r($data['grievance_list']);
			}else{
				$data['grievance_list'] = $this->grievance_details_model->grievance_alllist($data);
				//print_r($data['grievance_list']);
			}
			return view('grievance/grievance_dtl_list', $data);
		}
		else{
			$data['from']= date("Y-m-d");
			$data['to']= date("Y-m-d");
			$data['grievance_list'] = $this->grievance_details_model->grievance_alllist($data);
			return view('grievance/grievance_dtl_list',$data);
		}
	}
	
	
	public function grievance_forwardTL_list()
	{
		$data =(array)null;
		$Session = Session();
		$ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
		$data['user_type'] = $emp_mstr["user_type_mstr_id"];
		//print_r($emp_mstr);
        helper(['form']);
		//$data['user_type']= 4;
		$data['grievance_tllist'] = $this->grievance_details_model->grievance_tllist($data);
		//print_r($data['grievance_tllist']);
		return view('grievance/grievance_frdLevel_list', $data);
		
	}
	
	
	
	public function grievance_forwardTl($token_no=null)
	{
		$data =(array)null;
		$Session = Session();
		$ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
        $data['emp_details_id'] = $emp_mstr["id"];
		$data['created_on']=date('Y-m-d H:i:s');
		$data['token_no'] = $token_no;
        
		$this->grievance_details_model->grievance_forwardTl($data);
		echo '<script>alert("Forward To Team Leader")</script>'; 
		return $this->response->redirect(base_url('grievance_responce/grievance_list/'));
	
	}
	
	public function grievance_forwardPM($token_no=null)
	{
		$data =(array)null;
		$Session = Session();
		$ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
        $data['emp_details_id'] = $emp_mstr["id"];
		$data['created_on']=date('Y-m-d H:i:s');
		$data['token_no'] = $token_no;
        
		$this->grievance_details_model->grievance_forwardPM($data);
		echo '<script>alert("Forward To Project Manager")</script>'; 
		return $this->response->redirect(base_url('grievance_responce/grievance_forwardTL_list/'));
	
	}
	
	public function grievance_replay()
	{
		$data =(array)null;
		$Session = Session();
		$ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
        
        helper(['form']);
        if($this->request->getMethod()=='post'){
			$data = [
						'token_no' => $this->request->getVar('token_no'),
						'query' => $this->request->getVar('query'),
						'reply' => $this->request->getVar('reply')
					];
			$data['date']=date('Y-m-d');
			$data['emp_details_id'] = $emp_mstr["id"];
			//print_r($data);
			$this->grievance_details_model->grievance_replay($data);
			echo '<script>alert("You Replied Successfully")</script>'; 
			return $this->response->redirect(base_url('grievance_responce/grievance_list/'));
            
		}
	}

}
	
