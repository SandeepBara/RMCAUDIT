<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\model_ulb_mstr;
use App\Models\model_emp_details;
use App\Models\grievance_details_model;
use App\Models\GrievanceModel;
use App\Models\PropertyModel;
use App\Models\model_saf_dtl;
use App\Models\WaterApplyNewConnectionModel;
use App\Models\model_water_consumer;


class grievance extends HomeController
{

    protected $db;
    protected $dbSystem;
    protected $model_ulb_mstr;
    protected $user_type;
    protected $model_emp_details;
    
    
    public function __construct()
    {   
        
        $session=session();
        $get_emp_details=$session->get('emp_details');
        $emp_id=$get_emp_details['id'];
        $this->user_type=$get_emp_details['user_type_mstr_id'];

        parent::__construct();
        helper(['db_helper', 'form', 'qr_code_generator_helper','sms_helper']);
        if($db_name = dbConfig("water")){
            //echo $db_name;
            $this->water_db = db_connect($db_name);            
        }
		if($db_name = dbConfig("trade")){
            //echo $db_name;
            $this->trade_db = db_connect($db_name);            
        }
        if($db_name = dbConfig("property"))
        {
            $this->property_db = db_connect($db_name);
        }
        if($db_system = dbSystem()){
            $this->dbSystem = db_connect($db_system); 
        }
        
        //$db_name = db_connect("db_rmc_property"); 
        
        $this->modelUlb = new model_ulb_mstr($this->dbSystem);
        $this->modelemp = new model_emp_details($this->dbSystem);
		$this->grievance_details_model = new grievance_details_model($this->dbSystem);
		$this->grievance_model=new GrievanceModel($this->dbSystem);
		$this->property_model=new PropertyModel($this->property_db);
        $this->saf_model=new model_saf_dtl($this->property_db);
        $this->water_conn_model=new WaterApplyNewConnectionModel($this->water_db);
        $this->consumer_model=new model_water_consumer($this->water_db);
		

    }
    
    public function grievance_insrt()
    {

        $data=array();
        helper(['form']);
        print_r($_POST);
		print_r($_FILES);
		if($this->request->getMethod()=='post')
		{
			if(isset($_POST['complainSave']))
			{
				
				$rules=[
					'upload_file'=> 'uploaded[upload_file]|max_size[upload_file,5120]|ext_in[upload_file,png,jpg,jpeg,pdf]'
				];
				
				if (!$this->validate($rules))
				{
					$doc_path_file=$this->request->getFile('upload_file');
					if ($doc_path_file->IsValid() && !$doc_path_file->hasMoved()) 
					{
						try {
							//$this->db->transBegin();
								$data = [
										'grievance_type' => $this->request->getVar('grievance_type'),
										'module' => $this->request->getVar('module'),
										'grievance_id' => $this->request->getVar('grievance_id'),
										'ward_id' => $this->request->getVar('ward_id'),
										'search' => $this->request->getVar('search'),
										'query' => $this->request->getVar('query'),
										'mobile_no' => $this->request->getVar('mobile_no')
									];
									
								$data['date']=date('Y-m-d');
								$data['created_on']=date('Y-m-d H:i:s');
							
							if ($grievance_details = $this->grievance_details_model->grievance_details($data)) {
								$newFileName = md5($grievance_details);
								$file_ext = $doc_path_file->getExtension();
								$path = $ulb_city_nm."/"."grievance";

								$doc_path_file->move(WRITEPATH.'uploads/'.$path.'/',$newFileName.'.'.$file_ext);
								$doc_path_file_save = $path."/".$newFileName.'.'.$file_ext;
								$updaterow = $this->grievance_details_model->update_grievance_doc($grievance_details, $doc_path_file_save);
							
							}
							$token_no =$grievance_details;
							return $this->response->redirect(base_url('grievance/grievance_token_no/'.$token_no));
				
						}
						catch (Exception $e)
						{

						}
					}
				}
				else
				{
					//echo 'here';
				}
			}
		}
		
    }
	
	public function grievance_query()
    {
        $data=array();
        helper(['form']);
        
		if($this->request->getMethod()=='post'){
			if(isset($_POST['querySave'])){
				$data = [
						'grievance_type' => $this->request->getVar('grievance_type'),
						'query' => $this->request->getVar('querys'),
						'mobile_no' => $this->request->getVar('mobile_nos')
					];
					
				$data['date']=date('Y-m-d');
				$data['created_on']=date('Y-m-d H:i:s');
				
				$data['grievance_query'] = $this->grievance_details_model->grievance_query($data);
				$token_no = $data['grievance_query'];
				return $this->response->redirect(base_url('grievance/grievance_token_no/'.$token_no));
				
			}
		}
	}
	
	
	public function grievance_token_no($token_no=null)
    {
        $data=array();
        
		$data['token_no'] = $token_no;
				
		return view('citizen/grievance_menu',$data);
			
	}
	
	
	public function grievance_status()
    {
        $data=array();
        helper(['form']);
		if($this->request->getMethod()=='post'){
			if(isset($_POST['view_status'])){
				$data = [
						'token' => $this->request->getVar('token_no')
					];
					
				$data['grievance_sts'] = $this->grievance_details_model->grievance_sts($data);
				$data['grievance_chat'] = $this->grievance_details_model->grievance_chat($data['grievance_sts']['id']);
				//print_r($data['grievance_chat']);
				return view('citizen/grievance_menu',$data);
			}
		}
	}
	
	public function getGrievanceList()
    {  
		if($this->request->getMethod()=='post')
		{
       		$module=$_POST['module'];
       		//$where=" where module='$module'";

       		if($result=$this->grievance_model->grievanceList($module))
       		{
       			$response=['response'=>true,'result'=>$result];
       		}
       		else
       		{
       			$response=['response'=>false];
       		}
		}
		else
		{
       		$response=['response'=>false];
		}
		
	    return json_encode($response);
    }

    public function validateSearchBox()
    {
		if($this->request->getMethod()=='post')
		{
			$module=$_POST['module'];
			$ward_id=$_POST['ward_id'];
			$search=$_POST['search'] ?? $_POST['search_box'];

			if($module=='Holding')
			{
				
				$result=$this->property_model->validate_holding($ward_id,$search);
				
			}
			else if($module=='SAF')
			{
				$result=$this->saf_model->validate_saf($ward_id, $search);
				if($result)
				{
					
					$data=$this->saf_model->GetSAFOwnerDetails($result);
					//print_r($data);
					$result=NULL;
					foreach($data as $row)
					$result.=$row['owner_name']. ' ';
				}
				

			}
			else if($module=='Water Connection')
			{
				$result=$this->water_conn_model->validate_application($ward_id, $search);
				if($result)
				{
					$data=$this->water_conn_model->GetWaterApplicantDetails($result);
					$result=NULL;
					foreach($data as $row)
					$result.=$row['owner_name']. ' ';
				}

			}
			else if($module=='Water Consumer')
			{
				$result=$this->consumer_model->validate_consumer($ward_id,$search);
				if($result)
				{
					$data=$this->water_conn_model->GetWaterApplicantDetails($result);
					$result=NULL;
					foreach($data as $row)
					$result.=$row['applicant_name']. ' ';
				}
			}
			else if($module=='Trade Application')
			{
				$result=$this->saf_model->validate_saf($ward_id,$search);
			}
			else if($module=='Trade License')
			{
				$result=$this->saf_model->validate_saf($ward_id,$search);
			}

			$response=['response'=>true,'result'=>$result];

		}
		else
		{
			$response=['response'=>false];
		}
        
		return json_encode($response);
    }
	
	
}
