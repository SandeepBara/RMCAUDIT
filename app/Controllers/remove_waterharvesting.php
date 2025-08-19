<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\PostModel;
use App\Models\model_ward_mstr;
use App\Models\model_ulb_mstr;
use App\Models\model_prop_dtl;
use App\Models\model_prop_owner_detail;
use App\Models\model_prop_tax;
use App\Models\model_prop_demand;
use App\Models\model_remove_water_harvesting_dtl;
use App\Models\model_fy_mstr;

class remove_waterharvesting extends AlphaController
{
	protected $db;
	protected $dbSystem;
	protected $model_ward_mstr;
	protected $model_ulb_mstr;
	protected $model_prop_dtl;
	protected $model_prop_owner_detail;
	protected $model_prop_tax;
	protected $model_prop_demand;
	protected $model_remove_water_harvesting_dtl;
	protected $model_fy_mstr;
	

	public function __construct()
    {
        parent::__construct();
    	helper(['db_helper']);
        if($db_name = dbConfig("property"))
		{
            $this->db = db_connect($db_name);            
        }
        if($db_system = dbSystem())
		{
            $this->dbSystem = db_connect($db_system); 
        } 
		
        helper(['form']);
		//$db_name = db_connect("db_rmc_property");		
        $this->model = new model_ward_mstr($this->dbSystem);
		$this->modelUlb = new model_ulb_mstr($this->dbSystem);
		$this->modelfy = new model_fy_mstr($this->dbSystem);
		$this->modelprop = new model_prop_dtl($this->db);
		$this->modelowner = new model_prop_owner_detail($this->db);
		$this->model_prop_tax = new model_prop_tax($this->db);
		$this->modeldemand = new model_prop_demand($this->db);
		$this->model_remove_water_harvesting_dtl = new model_remove_water_harvesting_dtl($this->db);
		
    }
	
	
	public function waterharvest_Property_Tax()
	{
		
		$ulb_mstr_id = $_SESSION['ulb_dtl']['ulb_mstr_id'];	
		$data =(array)null;
        helper(['form']);
        if($this->request->getMethod()=='post'){
			$inputs = filterSanitizeStringtoUpper($this->request->getVar());
		
			$data['keyword']=$inputs['keyword'];
			$data['ward_mstr_id']=$inputs['ward_mstr_id'];
			if($data['ward_mstr_id']!="" AND $data['keyword']!=""){
				$where="ward_mstr_id='".$data['ward_mstr_id']."' and (mobile_no ilike '%".$data['keyword']."%' or holding_no ilike '%".$data['keyword']."%' or new_holding_no ilike '%".$data['keyword']."%' or owner_name ilike '%".$data['keyword']."%')";
			}else if($data['ward_mstr_id']=="" AND $data['keyword']!=""){
				$where="(mobile_no ilike '%".$data['keyword']."%' or holding_no ilike '%".$data['keyword']."%' or new_holding_no ilike '%".$data['keyword']."%' or owner_name ilike '%".$data['keyword']."%')";
			}
			$data['emp_details'] = $this->modelprop->consumer_details($where);
			$data['ward'] = $this->model->getWardList(['ulb_mstr_id'=>$ulb_mstr_id]);
			//print_r($data);
			return view('remove_waterHarvesting/waterharvest_Property_List', $data);
		
		} else{
		$data['ward'] = $this->model->getWardList(['ulb_mstr_id'=>$ulb_mstr_id]);
		return view('remove_waterHarvesting/waterharvest_Property_List', $data);
		}
	}

	
	
	
	public function remove_additionaltax_details($id=null)
	{
		$data =(array)null;
		$Session = Session();
        
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
		$ulb_city_nm = $ulb_mstr['city'];
        $emp_mstr = $Session->get("emp_details");
		
		$data['id']=$id;
		
		if($this->request->getMethod()=='post'){
			if(isset($_POST['add_additional_tax'])){
				
				$rules=[
					'doc_path'=>'uploaded[doc_path]|max_size[doc_path,5120]|ext_in[doc_path,png,jpg,jpeg]'
				];
				
				if (!$this->validate($rules)) {
					
					$doc_path_file=$this->request->getFile('doc_path');
					if ($doc_path_file->IsValid() 
						&& !$doc_path_file->hasMoved()) 
					{
						try {
							$this->db->transBegin();
								$data = [
										'custm_id' => $this->request->getVar('custm_id'),
										'due_upto_year' => $this->request->getVar('due_upto_year'),
										'date_upto_qtr' => $this->request->getVar('date_upto_qtr'),
										'remarks' => $this->request->getVar('remarks'),
										'emp_details_id' => $emp_mstr["id"]
									];
									
								$data['crntdate']=date('Y-m-d');
								$data['created_on']=date('Y-m-d H:i:s');
							
							if ($remove_additionaltax = $this->model_remove_water_harvesting_dtl->remove_additionaltax($data)) {
								$newFileName = md5($remove_additionaltax);
								$file_ext = $doc_path_file->getExtension();
								$path = $ulb_city_nm."/"."rain_water_harvesting_declearation";

								$doc_path_file->move(WRITEPATH.'uploads/'.$path.'/',$newFileName.'.'.$file_ext);
								$doc_path_file_save = $path."/".$newFileName.'.'.$file_ext;
								$updaterow = $this->model_remove_water_harvesting_dtl->update_remove_additionaltax($remove_additionaltax, $doc_path_file_save);
							
								$update_tax_dtl = $this->model_prop_tax->update_add_tax_dtl($data);
								if($update_tax_dtl){
									$update_prop_dtl = $this->modelprop->update_add_prop_dtl($data['custm_id']);
								}
							}
							
							if ($this->db->transStatus() === FALSE) {
								$this->db->transRollback();
							} else {
								$this->db->transCommit();
								return $this->response->redirect(base_url('remove_waterHarvesting/waterharvest_Property_Tax'));
							}
						} catch (Exception $e) { }
					}
				}
			}
			else if(isset($_POST['remove_additional_tax'])){
				
				$rules=[
					'doc_path'=>'uploaded[doc_path]|max_size[doc_path,5120]|ext_in[doc_path,png,jpg,jpeg]'
				];
				
				if (!$this->validate($rules)) {
					
					$doc_path_file=$this->request->getFile('doc_path');
					if ($doc_path_file->IsValid() 
						&& !$doc_path_file->hasMoved()) 
					{
						try {
							$this->db->transBegin();
								$data = [
										'custm_id' => $this->request->getVar('custm_id'),
										'due_upto_year' => $this->request->getVar('due_upto_year'),
										'date_upto_qtr' => $this->request->getVar('date_upto_qtr'),
										'remarks' => $this->request->getVar('remarks'),
										'emp_details_id' => $emp_mstr["id"]
									];
									
								$data['crntdate']=date('Y-m-d');
								$data['created_on']=date('Y-m-d H:i:s');
							
							if ($remove_additionaltax = $this->model_remove_water_harvesting_dtl->remove_additionaltax($data)) {
								$newFileName = md5($remove_additionaltax);
								$file_ext = $doc_path_file->getExtension();
								$path = $ulb_city_nm."/"."rain_water_harvesting_declearation";

								$doc_path_file->move(WRITEPATH.'uploads/'.$path.'/',$newFileName.'.'.$file_ext);
								$doc_path_file_save = $path."/".$newFileName.'.'.$file_ext;
								if($updaterow = $this->model_remove_water_harvesting_dtl->update_remove_additionaltax($remove_additionaltax, $doc_path_file_save))
								{
									$update_tax_dtl = $this->model_prop_tax->update_remove_tax_dtl($data);
									if($update_tax_dtl){
										$update_prop_dtl = $this->modelprop->update_remove_prop_dtl($data['custm_id']);
									}
								}
							}
							
							if ($this->db->transStatus() === FALSE) {
								$this->db->transRollback();
							} else {
								$this->db->transCommit();
								return $this->response->redirect(base_url('remove_waterHarvesting/waterharvest_Property_Tax'));
							}
						} catch (Exception $e) { }
					}
				}
			}
		}
		
		$data['basic_details'] = $this->modelprop->basic_details($data);
		
		if ( $owner_details = $this->modelowner->owner_details($data) ) {
			$data['owner_details'] = $owner_details;
		}
		if ( $tax_list = $this->model_prop_tax->tax_list($data['basic_details']['prop_dtl_id']) ) {
			$data['tax_list'] = $tax_list;
		}
		if ( $demand_detail = $this->modeldemand->demand_detail($data) ) {
			$data['demand_detail'] = $demand_detail;
		}
		if ( $fydemand = $this->modeldemand->fydemand($data['basic_details']['prop_dtl_id']) ) {
			$data['fydemand'] = $fydemand;
		}
		
		return view('remove_waterHarvesting/remove_additional_tax', $data);
	}
	
	
	public function ajax_gatequarter()
    {
		if($this->request->getMethod()=='post'){
			$data = [
					'fyUpto' => $this->request->getVar('due_upto_year'),
					'prop_no' => $this->request->getVar('custm_id')
					];
			$result = $this->modeldemand->gateQuarter($data);
			
			if(!empty($result)){
				$option = "";
				$option .= "<option value=''>Select Quarter</option>";
				foreach ($result as $value) {
					$option .= "<option value='".$value['qtr']."'>".$value['qtr']."</option>";
				}
				$lasttotalQuarter = $value['qtr'];
				$response = ['response'=>true, 'data'=>$option];
			}else{
				$response = ['response'=>false];
			}
		}else{
			$response = ['response'=>false];
		}
		echo json_encode($response);
        
    }
	
	
	public function waterharvest_Property_reports()
	{
		
		$ulb_mstr_id = $_SESSION['ulb_dtl']['ulb_mstr_id'];	
		$data =(array)null;
        helper(['form']);
        if($this->request->getMethod()=='post'){
			$inputs = filterSanitizeStringtoUpper($this->request->getVar());
			
			$data['from']=$inputs['from_date'];
			$data['to']=$inputs['to_date'];
			$data['waterharvest_reports'] = $this->model_remove_water_harvesting_dtl->waterharvest_reports($data['from'],$data['to']);
			
			return view('remove_waterHarvesting/waterharvest_Property_report', $data);
			
		}
		else{
			
			$data['from']=date('Y-m-d');
			$data['to']=date('Y-m-d');
			$data['waterharvest_reports'] = $this->model_remove_water_harvesting_dtl->waterharvest_reports($data['from'],$data['to']);
			
			return view('remove_waterHarvesting/waterharvest_Property_report', $data);
		}
	}

}
	
