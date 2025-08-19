<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_view_emp_details;
use App\Models\model_view_ulb_permission;
use App\Models\model_emp_details;

class Profile extends AlphaController
{
	protected $dbSystem;
	protected $model_view_emp_details;
	protected $model_view_ulb_permission;
	protected $model_emp_details;
    public function __construct(){
		parent::__construct();
    	helper(['db_helper']);
        if($db_name = dbSystem()){
            $this->dbSystem = db_connect($db_name); 
        }
        $this->model_view_emp_details = new model_view_emp_details($this->dbSystem);
        $this->model_view_ulb_permission = new model_view_ulb_permission($this->dbSystem);
        $this->model_emp_details = new model_emp_details($this->dbSystem);
        
    }
   public function profileDetails()
   {
   		$session = session();
		$emp_details = $session->get('emp_details');
		$id = $emp_details['id'];
		$ulb_mstr = $session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];

		$data['emp_details'] = $this->model_emp_details->profile_details($id,$data['ulb_mstr_id']);
		//ulb Permitted list
		$ulb = $this->model_view_ulb_permission->getPermittedUlb($id);
		$data['ulb'] = $ulb;
		//print_r($data);
		return view('users/edit_profile',$data);
   }
	
}
?>
