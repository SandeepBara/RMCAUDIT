<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_ulb_mstr;
use App\Models\model_g_saf;
use App\Models\model_govt_saf_officer_dtl;
use App\Models\model_govt_doc_dtl;
use App\Models\model_govt_saf_level_pending_dtl;
use App\Models\model_govt_saf_transaction;

use App\Models\model_datatable;


use Exception;

class GsafDocUpload extends AlphaController
{
    protected $db;
    protected $dbSystem;
	protected $emp_details_id;
	protected $model_g_saf;
	protected $model_ulb_mstr;
	protected $model_govt_saf_officer_dtl;
	protected $model_govt_doc_dtl;
	protected $model_govt_saf_level_pending_dtl;
	protected $model_govt_saf_transaction;
	protected $model_datatable;
	
     public function __construct()
	 {
     	$Session = Session();
     	$emp_mstr = $Session->get("emp_details");
        $this->emp_details_id = $emp_mstr["id"];
        $this->ip_address = $emp_mstr["ip_address"];
        
        $ulb_mstr = $Session->get("ulb_dtl");
        $this->ulb_mstr_id = $ulb_mstr["ulb_mstr_id"];
		$this->ulb_city_nm=$ulb_mstr['city'];
        parent::__construct();
     	helper(['db_helper', 'utility_helper']);
        if($db_name = dbConfig("property")){
            $this->db = db_connect($db_name);
        }
        if ($db_name = dbSystem()) {
            $this->dbSystem = db_connect($db_name);
        }
        $this->model_g_saf = new model_g_saf($this->db);
        $this->model_ulb_mstr=new model_ulb_mstr($this->dbSystem);
      	$this->model_govt_saf_officer_dtl=new model_govt_saf_officer_dtl($this->db);
      	$this->model_govt_doc_dtl=new model_govt_doc_dtl($this->db);
      	$this->model_govt_saf_level_pending_dtl=new model_govt_saf_level_pending_dtl($this->db);
		$this->model_govt_saf_transaction=new model_govt_saf_transaction($this->db);
		$this->model_datatable = new model_datatable($this->db);
    }

   
   	public function index()
   	{
   		$data=array();
   		if($this->request->getMethod()=='post')
   		{
   			$inputs= arrFilterSanitizeString($this->request->getVar());
   			$data['application_no']=$inputs['application_no'];
   			$data['application_detail']=$this->model_g_saf->getApplicationDetail(strtoupper($data['application_no']));
   			//print_r($data['application_detail']);
   		}
   		return view('property/gsaf/search_gbsaf', $data);

   	}
	
	
   	public function docUpload($govt_saf_dtl_id)
   	{
   	 	$data=array();
   	 	$data['application_detail']=$this->model_g_saf->getApplicationDetailbyId($govt_saf_dtl_id);
   	 	$data['owner_details']=$this->model_govt_saf_officer_dtl->getOwnerDetails($govt_saf_dtl_id);
   	 	//$data['owner_details']=$this->model_govt_saf_officer_dtl->getOwnerDetails($govt_saf_dtl_id);
   	 	$data['doc_detail']=$this->model_govt_doc_dtl->getDocumentDetails($govt_saf_dtl_id);
   	 	$data['checkExists_mail']=$this->model_govt_saf_level_pending_dtl->checkExists($govt_saf_dtl_id);
		if ($data['payment_dtl']=$this->model_govt_saf_transaction->getTransactionById($govt_saf_dtl_id)) {
			$data['application_detail']["is_transaction_done"] = true;
		}
		
   	 	
   	 	if($this->request->getMethod()=='post') {	
   	 		$inputs= arrFilterSanitizeString($this->request->getVar());
   	 		$gov_saf_id=$inputs['gov_saf_id'];
   	 		$gov_doc=array();
   	 		$gov_doc['govt_saf_dtl_id']=$gov_saf_id;
   	 		$gov_doc['document_name']='Application Form';
   	 		$gov_doc['uploaded_by_emp_details_id']=$this->emp_details_id;
   	 		$gov_doc['uploaded_ip_address']=$this->ip_address;
   	 		$gov_doc['created_on']=date('Y-m-d H:i:s');
   	 		
   	 		$checkExists_id=$this->model_govt_doc_dtl->checkExists($gov_saf_id);
   	 		if(empty($checkExists_id)) {
				$insert_id=$this->model_govt_doc_dtl->insertData($gov_doc);
				if($insert_id) {
					$rules = ['application_form' => 'uploaded[application_form]|max_size[application_form,1024]|ext_in[application_form,pdf,jpg,jpeg]'];
					if($this->validate($rules)) {
						$file = $this->request->getFile('application_form');
						$extension = $file->getExtension();
						$path = $this->ulb_city_nm."/"."gov_saf_doc/";
						$city = $this->model_ulb_mstr->getCity($this->ulb_mstr_id);

						if($file->isValid() && !$file->hasMoved()) {
							$newName = md5($insert_id).".".$extension;
							if($file->move(WRITEPATH.'uploads/'.$city['city'].'/gov_saf_doc',$newName)) {
								$newName=$path.$newName;
								$this->model_govt_doc_dtl->updateFileName($insert_id,$newName);
								$this->model_g_saf->updateAppStatus($gov_saf_id);
							}
							flashToast("message", "Document uploaded Successfully!!!");
							return $this->response->redirect(base_url('GsafDocUpload/docUpload/'.($govt_saf_dtl_id)));
						}
					}
				}
   	 		} else {
				$rules = ['application_form' => 'uploaded[application_form]|max_size[application_form,1024]|ext_in[application_form,pdf,jpg,jpeg]'];
				if($this->validate($rules)) {
					$file = $this->request->getFile('application_form');
					$extension = $file->getExtension();
					$path = $this->ulb_city_nm."/"."gov_saf_doc/";
					$city = $this->model_ulb_mstr->getCity($this->ulb_mstr_id);

					if($file->isValid() && !$file->hasMoved()) {
						$newName = md5($checkExists_id).".".$extension;

						if($file->move(WRITEPATH.'uploads/'.$city['city'].'/gov_saf_doc', $newName)) {
							$newName=$path.$newName;
							$this->model_govt_doc_dtl->updateFileName($checkExists_id, $newName);
							$this->model_g_saf->updateAppStatus($gov_saf_id);
						}
						flashToast("message", "Document uploaded Successfully!!!");
						return $this->response->redirect(base_url('GsafDocUpload/docUpload/'.($govt_saf_dtl_id)));
					}
				} else {
					$data["errors"]=$this->validator->getErrors();
					print_var($data["errors"]);
					flashToast("message", "Invalid file!!!");
				}
   	 		}
   	 	}
		return view('property/gsaf/gbsaf_doc_upload.php',$data);
   	}
 	
 	public function sendtoRMC($govt_saf_dtl_id)
 	{
		$session = session();
		$emp_details = $session->get("emp_details");
		$user_type_mstr_id = $emp_details["user_type_mstr_id"];
		$emp_details_id = $emp_details["id"];
 		$get_app_detail=$this->model_g_saf->getApplicationDetailbyId($govt_saf_dtl_id);
		 if ($data['payment_dtl']=$this->model_govt_saf_transaction->getTransactionById($govt_saf_dtl_id)) {
			$get_app_detail["is_transaction_done"] = true;
		}
 		$pay_done=$get_app_detail['is_transaction_done'];
 		$gov_saf_id=$get_app_detail['id'];
 		
 		if($pay_done==1) {
 			$level_array= [
				'govt_saf_dtl_id'=>$gov_saf_id,
				'sender_user_type_id'=>$user_type_mstr_id,
				'sender_emp_details_id'=>$emp_details_id,
				'receiver_user_type_id'=>9,// section incharge
				'created_on'=>date('Y-m-d H:i:s')
			 ];
			$this->model_govt_saf_level_pending_dtl->insertData($level_array);
 		}
 		return $this->response->redirect(base_url('GsafDocUpload/docUpload/'.md5($gov_saf_id)));
 	}

	public function backToCitizenList()
	{
		$data=(array)null;
		$sql="select * from view_gb_saf_receive_list where receiver_user_type_id=11 and verification_status=2 and level_pending_status=1";
		$data=$this->model_datatable->getDatatable($sql);
		//print_var($data);
		return view('government/backToCitizenList', $data);
	}

	public function GBSAFBackToCitizenView($govt_saf_dtl_id)
   	{
   	 	$data=array();
   	 	$data['application_detail']=$this->model_g_saf->getApplicationDetailbyId($govt_saf_dtl_id);
		//print_var($data['application_detail']);
   	 	$data['owner_details']=$this->model_govt_saf_officer_dtl->getOwnerDetails($govt_saf_dtl_id);
   	 	$data['doc_detail']=$this->model_govt_doc_dtl->getAllDocuments($govt_saf_dtl_id);

		
   	 	if($this->request->getMethod()=='post')
   	 	{
   	 		$inputs= arrFilterSanitizeString($this->request->getVar());
			$gov_saf_id=$inputs['gov_saf_id'];

   	 		$gov_doc=array();
   	 		$gov_doc['govt_saf_dtl_id']=$inputs['gov_saf_id'];
   	 		$gov_doc['document_name']=$inputs['document_name'];
   	 		$gov_doc['uploaded_by_emp_details_id']=$this->emp_details_id;
   	 		$gov_doc['uploaded_ip_address']=$this->ip_address;
   	 		$gov_doc['created_on']=date('Y-m-d H:i:s');
   	 		
   	 		$checkExists_id=$this->model_govt_doc_dtl->checkAlreadyUploaded($gov_saf_id, $gov_doc['document_name']);
			
			
   	 		if($checkExists_id=="" || $checkExists_id==null)
   	 		{
   	 			$insert_id=$this->model_govt_doc_dtl->insertData($gov_doc);
	   	 		if($insert_id)
	   	 		{
					$rules = ['application_form' => 'uploaded[application_form]|max_size[application_form,1024]|ext_in[application_form,pdf,jpg,jpeg]'];
					if($this->validate($rules))
					{
						$file = $this->request->getFile('application_form');
						$extension = $file->getExtension();
						$path = $this->ulb_city_nm."/"."gov_saf_doc/";
						$city = $this->model_ulb_mstr->getCity($this->ulb_mstr_id);
						
						if($file->isValid() && !$file->hasMoved())
						{
							$newName = md5($insert_id).".".$extension;
							if($file->move(WRITEPATH.'uploads/'.$city['city'].'/gov_saf_doc',$newName))
							{
								$newName=$path.$newName;
								$this->model_govt_doc_dtl->updateFileName($insert_id,$newName);
								$this->model_g_saf->updateAppStatus($gov_saf_id);
							}
							flashToast("message", "New Document uploaded Successfully!!!");
							return $this->response->redirect(base_url('GsafDocUpload/GBSAFBackToCitizenView/'.($govt_saf_dtl_id)));
						}
					}
	   	 		}
   	 		}
   	 		else
   	 		{
   	 			$rules = ['application_form' => 'uploaded[application_form]|max_size[application_form,1024]|ext_in[application_form,pdf,jpg,jpeg]'];
				if($this->validate($rules))
				{
					$file = $this->request->getFile('application_form');
					$extension = $file->getExtension();
					$path = $this->ulb_city_nm."/"."gov_saf_doc/";
					$city = $this->model_ulb_mstr->getCity($this->ulb_mstr_id);
			                
			                
					if($file->isValid() && !$file->hasMoved())
					{
			            $newName = md5($checkExists_id).".".$extension;
						if($file->move(WRITEPATH.'uploads/'.$city['city'].'/gov_saf_doc//', $newName))
						{
							$newName=$path.$newName;
							$this->model_govt_doc_dtl->updateFileName($checkExists_id, $newName);
							$this->model_g_saf->updateAppStatus($gov_saf_id);
						}
						flashToast("message", "Document uploaded and updated Successfully!!!");
                        return $this->response->redirect(base_url('GsafDocUpload/GBSAFBackToCitizenView/'.($govt_saf_dtl_id)));
			        }
			    }
				else
				{
					print_var($this->validator->listErrors());
					flashToast("message", "Document uploaded and updated Successfully!!!");
				}
   	 		}
   	 	}
		return view('government/GBSAFBackToCitizenView', $data);
   	}

	public function revertToOfficer($govt_saf_dtl_id_MD5)
	{
		$data=array();
   	 	$data['application_detail']=$this->model_g_saf->getApplicationDetailbyId($govt_saf_dtl_id_MD5);
		$govt_saf_dtl_id=$data['application_detail']['id'];
		$levelPending=$this->model_govt_saf_level_pending_dtl->getLastRecord(md5($govt_saf_dtl_id));

		// Deactivate Rejected Document
		$this->model_govt_doc_dtl->deactivateRejectedDocument($govt_saf_dtl_id);

		// Update Last Record Level Table
		$input=[
			"verification_status"=> 1,
			"status"=> 0,
			"msg_body"=> 'Application Re-sent to Officer from back office',
		];
		$this->model_govt_saf_level_pending_dtl->UpdateLevelTable($govt_saf_dtl_id, $input);


		// Insert In level Table for Back to citizen
		$level_array=array();
		$level_array['govt_saf_dtl_id']= $govt_saf_dtl_id;
		$level_array['sender_user_type_id']= 11;//Back Office
		$level_array['sender_emp_details_id']= $this->emp_details_id;
		$level_array['receiver_user_type_id']= $levelPending['sender_user_type_id'];
		$level_array['verification_status']= 0;// Not Verified
		$level_array['msg_body']= 'Application Re-sent to Officer from back office';
		$level_array['created_on']=date('Y-m-d H:i:s');
		
		
		$this->model_govt_saf_level_pending_dtl->insertData($level_array);

		
		return $this->response->redirect(base_url('GsafDocUpload/backToCitizenList'));
	}
}
