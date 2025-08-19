<?php 
namespace App\Controllers;
use App\Models\model_login_details;
use App\Models\model_emp_details;

class Dashboard extends AlphaController
{
    protected $db;
	protected $model_login_details;
	protected $model_emp_details;
		
    public function __construct()
	{
        parent::__construct();
    	helper(['db_helper']);		 
		if($db_system = dbSystem()){
            $this->dbSystem = db_connect($db_system); 
        } 
		$this->model_login_details = new model_login_details($this->dbSystem);
		$this->model_emp_details = new model_emp_details($this->dbSystem);
    }
    
	public function welcome()
	{
		$data =(array)null;
        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
        $data['emp_details_id'] = $emp_mstr["id"];
		$data['emp_details'] = $this->model_emp_details->profile_details($data['emp_details_id'], $data['ulb_mstr_id']);
		
		/* $data['crnt_date'] = date('Y-m-d');
		$data['login_details'] = $this->model_login_details->login_details($data['crnt_date'], $data['emp_details_id']); */
		$data['emp_details']['ward_id'] = explode(',',$data['emp_details']['ward_id']);
		//$this->cachePage(15);
		return view('users/welcome', $data);
	}	

	public function welcome2()
	{
		$emp_details = $_SESSION["emp_details"];
		$emp_name = $emp_details["emp_name"];
		$personal_phone_no = $emp_details["personal_phone_no"];
		$email_id = $emp_details["email_id"];
		$email_id = $emp_details["email_id"];
		print_var($emp_details);
		/* $data =(array)null;
        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
        $data['emp_details_id'] = $emp_mstr["id"];
		$data['emp_details'] = $this->model_emp_details->profile_details($data['emp_details_id'], $data['ulb_mstr_id']);
		
		/* $data['crnt_date'] = date('Y-m-d');
		$data['login_details'] = $this->model_login_details->login_details($data['crnt_date'], $data['emp_details_id']); 
		$data['emp_details']['ward_id'] = explode(',',$data['emp_details']['ward_id']);
		return view('users/welcome', $data); */
	}	
}