<?php namespace App\Controllers;


use CodeIgniter\Controller;
use App\Models\PostModel;
use App\Models\model_ward_mstr;
use App\Models\model_ulb_mstr;
use App\Models\model_prop_dtl;
use App\Models\model_prop_owner_detail;
use App\Models\model_fy_mstr;
use App\Models\model_saf_dtl;
use App\Models\model_saf_demand;
use App\Models\model_saf_floor_details;
use App\Models\model_saf_owner_detail;
use App\Models\model_saf_tax;
use App\Models\model_saf_deactv;
use App\Models\model_prop_demand;
use App\Models\model_prop_actv_deactv;
use App\Models\model_prop_update_basic_details;
use App\Models\model_prop_update_owner_details;



class update_tool extends AlphaController
{
	protected $db;
	protected $dbSystem;
	//protected $db_name;
	protected $model_ward_mstr;
	protected $model_ulb_mstr;
	protected $model_fy_mstr;
	protected $model_prop_dtl;
	protected $model_prop_owner_detail;
	protected $model_saf_dtl;
	protected $model_saf_demand;
	protected $model_saf_floor_details;
	protected $model_saf_owner_detail;
	protected $model_saf_tax;
	protected $model_saf_deactv;
	protected $model_prop_demand;
	protected $model_prop_actv_deactv;
	protected $model_prop_update_basic_details;
	protected $model_prop_update_owner_details;
	
	
	public function __construct()
    {
        parent::__construct();
    	helper(['db_helper', 'upload_helper', 'form', 'url']);
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
        $this->model_ward_mstr = new model_ward_mstr($this->dbSystem);
		$this->model_ulb_mstr = new model_ulb_mstr($this->dbSystem);
		$this->model_fy_mstr = new model_fy_mstr($this->dbSystem);
		$this->model_prop_dtl = new model_prop_dtl($this->db);
		$this->model_prop_owner_detail = new model_prop_owner_detail($this->db);
		$this->model_saf_dtl = new model_saf_dtl($this->db);
		$this->model_saf_demand = new model_saf_demand($this->db);
		$this->model_saf_floor_details = new model_saf_floor_details($this->db);
		$this->model_saf_owner_detail = new model_saf_owner_detail($this->db);
		$this->model_saf_tax = new model_saf_tax($this->db);
		$this->model_saf_deactv = new model_saf_deactv($this->db);
		$this->model_prop_demand = new model_prop_demand($this->db);
		$this->model_prop_actv_deactv = new model_prop_actv_deactv($this->db);
		$this->model_prop_update_owner_details = new model_prop_update_owner_details($this->db);
		$this->model_prop_update_basic_details = new model_prop_update_basic_details($this->db);
		
		
    }
	
	
	
	public function basic_dtl_update()
	{
		$data =(array)null;
        helper(['form']);
        if($this->request->getMethod()=='post'){
			$data = [
                       'type' => $this->request->getVar('type'),
						'saf' => $this->request->getVar('saf_no'),
						'holding' => $this->request->getVar('holding_no')
                    ];
			if($data['type']=="Saf"){
				$data['saf']=$data['saf'];
				$data['saf_details'] = $this->model_saf_dtl->safUpdate_details($data['saf']);
				return view('update tool/basic_dtl_update_saf_prop', $data);
			}else if($data['type']=="Property"){
				$data['holding']=$data['holding'];
				$data['prop_details'] = $this->model_prop_dtl->prop_details($data['holding']);
				return view('update tool/basic_dtl_update_saf_prop', $data);
			}
		} else{
			return view('update tool/basic_dtl_update_saf_prop');
		}
	}
	
	public function update_prop_basic_details($id=null)
	{
		$data =(array)null;
        $Session = Session();
        
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
		$data['id']=$id;
		
		if($this->request->getMethod()=='post'){
			$data = [
						'prop_dtl_id' => $this->request->getVar('prop_dtl_id'),
						'old_ward_mstr_id' => $this->request->getVar('old_ward_mstr_id'),
						'old_new_ward_mstr_id' => $this->request->getVar('old_new_ward_mstr_id'),
						'old_plot_no' => $this->request->getVar('old_plot_no'),
						'old_khata_no' => $this->request->getVar('old_khata_no'),
						'old_mauja_name' => $this->request->getVar('old_mauja_name'),
						'old_prop_address' => $this->request->getVar('old_prop_address'),
						'ward_mstr_id' => $this->request->getVar('ward_mstr_id'),
						'new_ward_mstr_id' => $this->request->getVar('new_ward_mstr_id'),
						'plot_no' => $this->request->getVar('plot_no'),
						'khata_no' => $this->request->getVar('khata_no'),
						'mauja_name' => $this->request->getVar('mauja_name'),
						'prop_address' => $this->request->getVar('prop_address'),
						'loc_remark' => $this->request->getVar('loc_remark'),
						'emp_dtl_id' => $emp_mstr["id"]
                    ];
			$prop_detail_update = $this->model_prop_dtl->pro_detail_update($data);
			if($prop_detail_update){
				$prop_detail_insold = $this->model_prop_update_basic_details->prop_detail_insold($data);
				$prop_detail_insnew = $this->model_prop_update_basic_details->prop_detail_insnew($data);
			}
			
			flashToast("update_prop_basicDtl", "Details updated successfully!!!");
			return $this->response->redirect(base_url('update_tool/update_prop_basic_details/'.md5($data['prop_dtl_id'])));
					
		}

		
		$data['prop_details'] = $this->model_prop_dtl->pro_det($data['id']);
		$data['owner_details'] = $this->model_prop_owner_detail->owner_det($data['id']);
		//print_r($data['prop_details']);
		$data['ward'] = $this->model_ward_mstr->getWardList($ulb_mstr);
		return view('update tool/update_prop_basicDtl',$data);
	}
	
	
	
	public function update_prop_owner($id=null)
	{
		$data =(array)null;
        $Session = Session();
        
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
		$data['id']=$id;
		
		if($this->request->getMethod()=='post'){
			$data = [
						'prop_dtl_id' => $this->request->getVar('prop_dtl_id'),
						'old_owner_name' => $this->request->getVar('old_owner_name'),
						'old_relation_type' => $this->request->getVar('old_relation_type'),
						'old_mobile_no' => $this->request->getVar('old_mobile_no'),
						'old_guardian_name' => $this->request->getVar('old_guardian_name'),
						'owner_name' => $this->request->getVar('owner_name'),
						'relation_type' => $this->request->getVar('relation_type'),
						'mobile_no' => $this->request->getVar('mobile_no'),
						'guardian_name' => $this->request->getVar('guardian_name'),
						'owner_remark' => $this->request->getVar('owner_remark'),
						'emp_dtl_id' => $emp_mstr["id"]
                    ];
					
			$prop_ownerdetail_update = $this->model_prop_owner_detail->pro_ownerdetail_update($data);
			if($prop_ownerdetail_update){
				$prop_ownerdetail_insold = $this->model_prop_update_owner_details->prop_ownerdetail_insold($data);
				$prop_ownerdetail_insnew = $this->model_prop_update_owner_details->prop_ownerdetail_insnew($data);
			}
			
			flashToast("update_prop_basicDtl", "Owner Details updated successfully!!!");
			return $this->response->redirect(base_url('update_tool/update_prop_owner/'.md5($data['prop_dtl_id'])));
					
		}

		
		$data['prop_details'] = $this->model_prop_dtl->pro_det($data['id']);
		$data['owner_details'] = $this->model_prop_owner_detail->owner_det($data['id']);
		//print_r($data['owner_details']);
		$data['ward'] = $this->model_ward_mstr->getWardList($ulb_mstr);
		return view('update tool/update_prop_basicDtl',$data);
	}
	
	

	public function saf_deactivate($id=null)
	{
		$data =(array)null;
        $Session = Session();
        
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
        helper(['form']);
        if($this->request->getMethod()=='post'){
			if($id!=""){
				$this->db->transBegin();
				$data = [
						'saf_dtl_id' => $this->request->getVar('saf_dtl_id'),
						'ward_no' => $this->request->getVar('saf_ward_no'),
						'saf_no' => $this->request->getVar('saf_no'),
						'remarks' => $this->request->getVar('remarks'),
						'prop_type_mstr_id' => $this->request->getVar('prop_type_mstr_id'),
						'emp_dtl_id' => $emp_mstr["id"]
                    ];
				
				$rollbackFun=false;
				$temp1 = false;
				$temp2 = false;
				$temp3 = false;
				$temp4 = false;
				$temp5 = false;
				if($this->model_saf_dtl->safdtl_deactive($data['saf_dtl_id'])){
					$temp1 = true;
				}
				if ($this->model_saf_demand->safdemand_deactive($data['saf_dtl_id'])) {
					$temp2 = true;
				}
				if($this->model_saf_owner_detail->safowndtl_deactive($data['saf_dtl_id'])){
					$temp3 = true;
				}
				if ($this->model_saf_tax->saftax_deactive($data['saf_dtl_id'])) {
					$temp4 = true;
				}
				if($data['prop_type_mstr_id']!=4) {
					if ($this->model_saf_floor_details->safflrdtl_deactive($data['saf_dtl_id'])) {
						$temp5 = true;
					}
				} else {
					$temp5 = true;
				}
				
				if ($temp1==false
						|| $temp2==false
						|| $temp3==false	
						|| $temp4==false
						|| $temp5==false)
				{
					//not deactivate
					$rollbackFun=true;
				} else {
					$saf_deactive = $this->model_saf_deactv->saf_deactive($data);
				}
				
				if($this->db->transStatus() === FALSE || $rollbackFun==true){
					$this->db->transRollback();
					flashToast("SAF_deactivate", "SAF not deactivated, due to getting some error!!!");
					return $this->response->redirect(base_url('update_tool/SAF_deactivate'));
				}else{
					$this->db->transCommit();
					flashToast("SAF_deactivate", "SAF deactivated successfully!!!");
					return $this->response->redirect(base_url('update_tool/SAF_deactivate'));
				}
				
				
			}else{
				$data = [
						'saf' => $this->request->getVar('saf_no')
                    ];
				$data['saf_details'] = $this->model_saf_dtl->safUpdate_details($data['saf']);
				//print_r($data['saf_details']);
			}
			$data['saf']=$data['saf'];
			return view('update tool/SAF_deactivate', $data);
			
		} else{
			return view('update tool/SAF_deactivate');
		}
	}

	public function prop_active_deactive($id=null)
	{
		$data =(array)null;
        $Session = Session();
        
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
        helper(['form']);
		if($id!=""){
				$data['pro_det'] = $this->model_prop_dtl->pro_det($id);
				//print_r($data['pro_det']);
				return view('update tool/prop_active_deactive_remarks', $data);
		}
		else{
			if($this->request->getMethod()=='post'){
				$data = [
							'holding' => $this->request->getVar('holding_no')
						];
				
				$data['holding']=$data['holding'];
				$data['prop_details'] = $this->model_prop_dtl->prop_details($data['holding']);
				return view('update tool/prop_active_deactive', $data);
				
			} else{
				return view('update tool/prop_active_deactive');
			}
		}
	}
	
	public function prop_active_deactive_remark()
	{
		$data =(array)null;
        $Session = Session();
        
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
        helper(['form']);
		
		if($this->request->getMethod()=='post'){
			$this->db->transBegin();
			$data = [
						'prop_dtl_id' => $this->request->getVar('prop_dtl_id'),
						'ward_no' => $this->request->getVar('prop_ward_no'),
						'holding_no' => $this->request->getVar('holding_no'),
						'remarks' => $this->request->getVar('remarks'),
						'reason_doc' => $this->request->getVar('reason_doc'),
						'action_type' => $this->request->getVar('action_type'),
						'emp_dtl_id' => $emp_mstr["id"]
                    ];
					
			 
			if($data['action_type']=="Deactivate"){
				
				$rollbackFun=false;
				$temp1 = false;
				$temp2 = false;
				
				if($this->model_prop_dtl->propdtl_deactive($data['prop_dtl_id'])){
					$temp1 = true;
				}
				if ($this->model_prop_demand->propdemand_deactive($data['prop_dtl_id'])) {
					$temp2 = true;
				}
				
				if ($temp1==false
						|| $temp2==false)
				{
					//not deactivate
					$rollbackFun=true;
				} else {
					$rules=[
                        'reason_doc_path'=>'uploaded[reason_doc]|max_size[reason_doc,10240]|ext_in[reason_doc,png,jpg,jpeg,pdf]',
					];
					
					if($this->validate($rules)){ 
						if($prop_deactive = $this->model_prop_actv_deactv->prop_deactive_active($data)){
							$reason_doc_file=$this->request->getFile('reason_doc_path');
							if($reason_doc_file->IsValid() && !$reason_doc_file->hasMoved()){
								 $newFileName = md5($prop_deactive);
								$file_ext = $reason_doc_file->getExtension();
								$path = $ulb_city_nm;

								if($reason_doc_file->move(WRITEPATH.'uploads/'.$path.'/',$newFileName.'.'.$file_ext)){
									$reason_doc_file_path = $path."/".$newFileName.'.'.$file_ext;
									$updaterow = $this->model_prop_actv_deactv->updatedocpathById($prop_deactive,$reason_doc_file_path);
								}
							}
						}
					}
					
				}
				
				if($this->db->transStatus() === FALSE || $rollbackFun==true){
					$this->db->transRollback();
					flashToast("prop_active_deactive", "Property not deactivated, due to getting some error!!!");
					return $this->response->redirect(base_url('update_tool/prop_active_deactive'));
				}else{
					$this->db->transCommit();
					flashToast("prop_active_deactive", "Property deactivated successfully!!!");
					return $this->response->redirect(base_url('update_tool/prop_active_deactive'));
				}
				
			}
			else if($data['action_type']=="Activate"){
				
				$rollbackFun=false;
				$temp1 = false;
				$temp2 = false;
				
				if($this->model_prop_dtl->propdtl_active($data['prop_dtl_id'])){
					$temp1 = true;
				}
				if ($this->model_prop_demand->propdemand_active($data['prop_dtl_id'])) {
					$temp2 = true;
				}
				
				if ($temp1==false
						|| $temp2==false)
						
				{
					//not deactivate
					$rollbackFun=true;
				} else {
					
					$rules=[
                        'reason_doc_path'=>'uploaded[reason_doc]|max_size[reason_doc,10240]|ext_in[reason_doc,png,jpg,jpeg,pdf]',
					];
					if($this->validate($rules)){ 
						if($prop_deactive = $this->model_prop_actv_deactv->prop_deactive_active($data)){
							$reason_doc_file=$this->request->getFile('reason_doc_path');
							if($reason_doc_file->IsValid() && !$reason_doc_file->hasMoved()){
								 $newFileName = md5($prop_deactive);
								$file_ext = $reason_doc_file->getExtension();
								$path = $ulb_city_nm;

								if($reason_doc_file->move(WRITEPATH.'uploads/'.$path.'/',$newFileName.'.'.$file_ext)){
									$reason_doc_file_path = $path."/".$newFileName.'.'.$file_ext;
									$updaterow = $this->model_prop_actv_deactv->updatedocpathById($prop_deactive,$reason_doc_file_path);
								}
							}
						}
					}else{
						$errors = $validation->getErrors();
						print_r($errors);
					}
				}
				
				if($this->db->transStatus() === FALSE || $rollbackFun==true){
					$this->db->transRollback();
					flashToast("prop_active_deactive", "Property not Activated, due to getting some error!!!");
					return $this->response->redirect(base_url('update_tool/prop_active_deactive'));
				}else{
					$this->db->transCommit();
					flashToast("prop_active_deactive", "Property Activated successfully!!!");
					return $this->response->redirect(base_url('update_tool/prop_active_deactive'));
				}
				
			}
			
			
		}
	}
	
	
	
	public function transaction_deactivate($id=null)
	{
		$data =(array)null;
        helper(['form']);
        if($this->request->getMethod()=='post'){
			$data = [
						'saf' => $this->request->getVar('saf_no')
                    ];
			
			$data['saf']=$data['saf'];
			$data['saf_details'] = $this->model_saf_dtl->safUpdate_details($data['saf']);
			return view('update tool/transaction_deactivate', $data);
			
		} else{
			return view('update tool/transaction_deactivate');
		}
	}
	
	
	
	public function jsk_confirm_payment($id=null)
	{ 
		$data =(array)null;
        $Session = Session();
        
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
        $data['emp_details_id'] = $emp_mstr["id"];
		$data['emp_type_id'] = $emp_mstr["user_type_mstr_id"];
        $data['id']=$id;

		if($id<>null){
		helper(['form']);
		if($this->request->getMethod()=='post'){
			
			$this->db->transBegin();	
				$data = [
							'custm_id' => $this->request->getVar('custm_id'),
							'due_upto_year' => $this->request->getVar('due_upto_year'),
							'date_upto_qtr' => $this->request->getVar('date_upto_qtr'),
							'total_rebate' => $this->request->getVar('total_rebate'),
							//'total_payabl' => $this->request->getVar('total_payabl'),
							'payment_mode' => $this->request->getVar('payment_mode'),
							'from_fy_year' => $this->request->getVar('from_fy_year'),
							'from_fy_qtr' => $this->request->getVar('from_fy_qtr'),
							'ful_qtr' => $this->request->getVar('ful_qtr'),
							'total_qrt' => $this->request->getVar('total_qrt'),
							'ward_mstr_id' => $this->request->getVar('ward_mstr_id'),
							'chq_date' => $this->request->getVar('chq_date'),
							'chq_no' => $this->request->getVar('chq_no'),
							'bank_name' => $this->request->getVar('bank_name'),
							'branch_name' => $this->request->getVar('branch_name'),
							'emp_details_id' => $emp_mstr["id"]
							
						];
									
				$session = session();
				$data['emp_details'] = $session->get('emp_details');
				$data['date'] = date('Y-m-d');
				
				$difference_Penalty = $this->model_penalty_dtl->difference_Penalty($data['custm_id']);
				if($difference_Penalty['penalty_amt']!=""){
					$difference_Penalty = $difference_Penalty['penalty_amt'];
				}else{ 
					$difference_Penalty = 0;
				}
				
				$data['difference_Penalty'] = $difference_Penalty;
				$data['bank_reCancel'] = $this->model_bank_recancilation->bank_reCancel($data['custm_id']);
				if($data['bank_reCancel']['amount']!=""){
					$bank_reCancel = $data['bank_reCancel']['amount'];
					
				}else{ 
					$bank_reCancel = 0;
				}
				
				$rebate_demand = 0;
				$dif_qtr = 0;
				$tol_mnth = $data['ful_qtr']*3;
				$j=0;
				$crnt_dm= date('m');
				if($crnt_dm==01 || $crnt_dm==02 || $crnt_dm==03)
				{
					$crnt_dm = $crnt_dm+9;
					$crnt_dm=(12-$crnt_dm);
					$tol_mnth = $tol_mnth-$crnt_dm;
				}else{
					$crnt_dm=(12-$crnt_dm)+3;
					$tol_mnth = $tol_mnth-$crnt_dm;
				}
				$tol_mnths = $tol_mnth;
				//print_r($tol_mnths);
				//$data["total_qrt_pnlty"] = 0;
				$data["deman_am"] = 0;
				$data['tol_pently'] = 0;
				
				$data['demand_amn'] = $this->modeldemand->demand_amnt($data["custm_id"]);
				//print_r($data['demand_amn']);
				for($i=1;$i<=$data['total_qrt'];$i++){
					$dem_am = $data['demand_amn'][$i-1]["balance"];
					$dif_qtr = $dif_qtr + 3;
					$dem_fyids = $data['demand_amn'][$i-1]["fy_mstr_id"];
					if($dem_fyids>=49){
						if($tol_mnth>=3){
							$each_penlty = ($dem_am/100)*($tol_mnths-$dif_qtr);
							if($each_penlty>0){
								$data['tol_pently'] = $data['tol_pently'] + $each_penlty;
							}else { $data['tol_pently'] = $data['tol_pently']; }
						}else { $data['tol_pently'] = $data['tol_pently']; }
					}else { $data['tol_pently'] = $data['tol_pently']; }
						
					$data["deman_am"] = $data["deman_am"] + $data['demand_amn'][$i-1]["balance"];
				}
				
				$crnt_dm_for_rdt= date('m');
				if($crnt_dm_for_rdt=='04' || $crnt_dm_for_rdt=='05' || $crnt_dm_for_rdt=='06'){
					if($data['date_upto_qtr']==4){
						$data['demand_rbt'] = $this->modeldemand->demand_rebet($data["custm_id"]);
						$rebate = ($data['demand_rbt']['sum']/100)*5;
					}
					else{ $rebate = 0; }
				}else{ $rebate = 0; }
				$data['rebate'] = $rebate;
				
				$data["total_pabl"] = ($data["deman_am"] + $data['tol_pently'] + $difference_Penalty + $bank_reCancel) - $data['rebate'];
				
				if($data['emp_type_id']==8){
					$data["total_pa_onjsk"] = ($data['total_pabl']/100)*2.5;
					$data["total_payabl"] = $data["total_pabl"] - $data["total_pa_onjsk"];
				}else if($data['payment_mode']==4){
					$data["total_pa_onjsk"] = ($data['total_pabl']/100)*5;
					$data["total_payabl"] = $data["total_pabl"] - $data["total_pa_onjsk"];
				}else {
					$data["total_payabl"] = $data["total_pabl"];
				}
				
				$round = round($data["total_payabl"]);
				$data["round_off"] = $round - $data["total_payabl"];
				$advn = $this->modeladjustment->advance($data["custm_id"]);
				$advc =$advn['arear_amt'];
				$pable_amnt = $advc+$data["total_payabl"];
				if($advc>0){
					if($advc>$data["total_payabl"]){
						$data['advc_adjst'] = $data["total_payabl"];
						$advn = $this->model_payment_adjust->advance_adjst($data);
						
					}else{
						$data['advc_adjst'] = $advc;
						$advn = $this->model_payment_adjust->advance_adjst($data);
					}
				}
				
				$data['current_date'] = date("Y-m-d");
				$data['checkPayment'] = $this->modelpay->checkpropPayment($data);
				if($data['checkPayment']){
					flashToast("jsk_confirm_payment", "Same Day More Than One Time Payment Cann't Done!!!");
					return $this->response->redirect(base_url('jsk/jsk_confirm_payment/'.md5($data['custm_id'])));
				}else{
				$data['insertPayment'] = $this->modelpay->insertPayment($data);
				//print_r($data["insertPayment"]);
				//die();
				if($data['insertPayment']){
					if($data["payment_mode"]=='2' || $data["payment_mode"]=='3'){
						$chqDDdetails = $this->modelchqDD->chqDDdetails($data);
					}
					
					if($pable_amnt>$data["total_payabl"]){
						$data['advance_amount'] = $pable_amnt-$data["total_payabl"];
						$data['payment_adjustment'] = $this->modeladjustment->payment_adjustment($data);
						$data['demand_id'] = $this->modeldemand->demand_id($data);
						for($i=1;$i<=$data['total_qrt'];$i++){
							$data['resultid'] = $data['demand_id'][$j++];
							$demand_am = $demand_amnt[$i-1]["balance"];
							$dif_qtr = $dif_qtr + 3;
							$each_penlty = ($demand_am/100)*($tol_mnth-$dif_qtr);
							if($each_penlty>0){
								$data['tol_pent'] = $each_penlty;
							}else{
								$data['tol_pent'] = 0;
							}
							
							$data['updatedemandPayment'] = $this->modeldemand->updatedemandPayment($data);
							
							$data['demandCollection'] = $this->modelpropcoll->demandCollection($data);
															
						}
					}
					
					elseif($pable_amnt<$data["total_payabl"]){
						$pay_amount = $pable_amnt;
						$data['demand_id'] = $this->modeldemand->demand_id($data);	
						for($i=1;$i<=$data['total_qrt'];$i++){
							$data['resultid'] = $data['demand_id'][$j++];
							$demand_am = $data['demand_amnt'][$i-1]["balance"];
									
							if($tol_mnth>=3){
								$dif_qtr = $dif_qtr + 3;
								$each_penlty = ($demand_am/100)*($tol_mnth-$dif_qtr);
								$data['tol_pent'] = $each_penlty;
								if($tol_pent<0){
									$data['tol_pent']=0;
								}
							}
							else{$data['tol_pent']=0;}
							
							if($pay_amount>0){	

							
								if($pay_amount>$demand_am){
									$pay_amount = $pay_amount-($demand_am+$data['tol_pent']);
									
									$data['updatedemandPayment'] = $this->modeldemand->updatedemandPayment($data);
								
									$data['demandCollection'] = $this->modelpropcoll->demandCollection($data);
								}
							}
							else{
								$al_tax_id = $this->modeltax->al_tax_id($data);
										
								$percent_balance = ($pay_amount/$demand_am)*100;
								$holding_tax = ($al_tax_id['holding_tax']/100)*$percent_balance;
								$water_tax = ($al_tax_id['water_tax']/100)*$percent_balance;
								$education_cess = ($al_tax_id['education_cess']/100)*$percent_balance;
								$health_cess = ($al_tax_id['health_cess']/100)*$percent_balance;
								$lighting_tax = ($al_tax_id['lighting_tax']/100)*$percent_balance;
								$latrine_tax = ($al_tax_id['latrine_tax']/100)*$percent_balance;
								$harvest_tax = ($al_tax_id['additional_tax']/100)*$percent_balance;
								//print_r($pay_amount);
								$data['balance'] = ($demand_am+$tol_pent)-$pay_amount;
								$pay_amount=0;
								
								$data['updatedemandPayment'] = $this->modeldemand->updatedemandPaymentblnc($data);
									
								$data['demandCollection'] = $this->modelpropcoll->demandCollection($data);
								
							
								
							}
							
						}
					
					}
					
					else{ 
					
						$data['demand_id'] = $this->modeldemand->demand_id($data);
						$dif_qtr = 0;
						$data['tol_pent'] = 0;
						for($i=1;$i<=$data['total_qrt'];$i++){
							$data['resultid'] = $data['demand_id'][$i-1];
							$dem_am = $data['demand_amn'][$i-1]["balance"];
							$dif_qtr = $dif_qtr + 3;
							$dem_fyids = $data['demand_amn'][$i-1]["fy_mstr_id"];
							if($dem_fyids>=49){
								if($tol_mnth>=3){
									$each_penlty = ($dem_am/100)*($tol_mnths-$dif_qtr);
									if($each_penlty>0){
										$data['tol_pent'] = $each_penlty;
									}else { $data['tol_pent'] = $data['tol_pent']; }
								}else { $data['tol_pent'] = $data['tol_pent']; }
							}else { $data['tol_pent'] = $data['tol_pent']; }
								
							$data["date_cls"]= date("Y-m-d H:i:s");
							$data['updatedemandPayment'] = $this->modeldemand->updatedemandPayment($data);
									
							$data['demandCollection'] = $this->modelpropcoll->demandCollection($data);
						}
						
						
					}
					
					if($data["tol_pently"]>0){
						$data['head_name'] = "1% Monthly Penalty";
						$data['fine_rebet_amount'] = $data["tol_pently"];
						$data['add_minus'] = "Add";
						
						$data['fine_rebet_details'] = $this->modelpenalty->fine_rebet_details($data);
						//print_r($data['fine_rebet_details']);
						
					}
					if($data["rebate"]>0){
						$data['head_name'] = "First Quartare Discount";
						$data['fine_rebet_amount'] = $data["rebate"];
						$data['add_minus'] = "Minus";
						
						$data['fine_rebet_details'] = $this->modelpenalty->fine_rebet_details($data);
					}
					
					if($data['total_pa_onjsk']>0){
						$data['head_name'] = "Rebet From Jsk/Online Payment";
						$data['fine_rebet_amount'] = $data['total_pa_onjsk'];
						$data['add_minus'] = "Minus";
						
						$data['fine_rebet_details'] = $this->modelpenalty->fine_rebet_details($data);
					}
					
					if($difference_Penalty>0){
						$data['head_name'] = "Difference Penalty";
						$data['fine_rebet_amount'] = $difference_Penalty;
						$data['add_minus'] = "Add";
						
						$data['fine_rebet_details'] = $this->modelpenalty->fine_rebet_details($data);
						//print_r($data['fine_rebet_details']);
						$this->model_penalty_dtl->Updatedifference_Penalty($data);
						
					}
					if($bank_reCancel>0){
						$data['chequePaymentDone'] = $this->model_bank_recancilation->chequePaymentDone($data);
					}
					
					$data['assessment_type'] = $this->modelassess->assessment_type($data['custm_id']);
					if($data['assessment_type']=="Reassessment"){
						$data['prop_hold'] = $this->modelprop->prop_hold($data['custm_id']);
					}
					
					
				}
				}
				if($this->db->transStatus() === FALSE){
					$this->db->transRollback();
					flashToast("jsk_confirm_payment", "Something errordue to payment!!!");
					return $this->response->redirect(base_url('jsk/jsk_confirm_payment/'.md5($data['custm_id'])));
				}else{
					$this->db->transCommit();
					return $this->response->redirect(base_url('jsk/payment_jsk_receipt/'.md5($data['insertPayment'])));
				}
				
		}
		else{ 
		$data['basic_details'] = $this->modelprop->basic_details($data);
		$data['owner_details'] = $this->modelowner->owner_details($data);
		if ( $tax_list = $this->modeltax->tax_list($data['basic_details']['prop_dtl_id']) ) {
			$data['tax_list'] = $tax_list;
		}
		$data['demand_detail'] = $this->modeldemand->demand_detail($data);
		if ( $fydemand = $this->modeldemand->fydemand($data['basic_details']['prop_dtl_id']) ) {
			$data['fydemand'] = $fydemand;
		}
		if ( $bank_reCancel = $this->model_bank_recancilation->bank_reCancel($data['basic_details']['prop_dtl_id']) ) {
			$data['bank_reCancel'] = $bank_reCancel;
			
		}
		
		if ( $difference_Penalty = $this->model_penalty_dtl->difference_Penalty($data['basic_details']['prop_dtl_id']) ) {
			$data['difference_Penalty'] = $difference_Penalty;
		}
		$data['advance'] = $this->modeladjustment->advance_amnt($data);
		//print_r($data['basic_details']);
		$data['tran_mode'] = $this->modeltran->getTranModeList();
		return view('Property/jsk/jsk_confirm_payment', $data);
		}
	}
	}
	
	public function payment_jsk_receipt($tran_no=null)
	{
		
		$data =(array)null;
		$ulb_mstr_id = $_SESSION['ulb_dtl']['ulb_mstr_id'];	
		$path=base_url('citizenPaymentReceipt/payment_jsk_receipt/'.$ulb_mstr_id.'/'.$tran_no);
		$data['ss']=qrCodeGeneratorFun($path);
		$data['tran_no']=$tran_no;
		$ulb_mstr_id = $_SESSION['ulb_dtl']['ulb_mstr_id'];	
		$data['ulb_mstr_name'] = $this->modelUlb->getulb_list($ulb_mstr_id);
		$data['tran_mode_dtl'] = $this->modelpay->getTrandtlList($data['tran_no']);
		//$data['coll_dtl'] = $this->modelsafcoll->collection_dtl($data['tran_no']);
		$data['fyFrom'] = $this->modelfy->getfyFromList($data['tran_mode_dtl']['from_fy_mstr_id']);
		$data['fyUpto'] = $this->modelfy->getfyUptoList($data['tran_mode_dtl']['upto_fy_mstr_id']);
		$data['payMode'] = $this->modeltran->getpayModeList($data['tran_mode_dtl']['tran_mode_mstr_id']);
		$data['holdingward'] = $this->modelprop->getholdWard(md5($data['tran_mode_dtl']['prop_dtl_id']));
		$data['basic_details'] = $this->modelprop->basic_dtl(md5($data['tran_mode_dtl']['prop_dtl_id']));
		if($data['payMode']['id']==2 || $data['payMode']['id']==3){
			$data['chqDD_details'] = $this->modelchqDD->mode_dtl($data['tran_no']);
		}
		$data['coll_dtl'] = $this->modelpropcoll->collection_propdtl($data['tran_mode_dtl']['id']);
		$data['penalty_dtl'] = $this->modelpenalty->penalty_dtl($data['tran_mode_dtl']['id']);
		//print_r($data);
		return view('Property/jsk/payment_receipt', $data);
	}
    
	//--------------------------------------------------------------------
	
	public function ajax_gatequarter()
    {
		if($this->request->getMethod()=='post'){
			$data = [
					'fyUpto' => $this->request->getVar('due_upto_year'),
					'prop_no' => $this->request->getVar('custm_id')
					];
			$result = $this->modeldemand->gateQuarter($data);
			$totalQuarter = $this->modeldemand->gatetotalQuarter($data);
			//$lasttotalQuarter = $this->modeldemand->gatelasttotalQuarter($data);
			if(!empty($result)){
				$option = "";
				$option .= "<option value=''>Select Quarter</option>";
				foreach ($result as $value) {
					$option .= "<option value='".$value['qtr']."'>".$value['qtr']."</option>";
				}
				$lasttotalQuarter = $value['qtr'];
				$response = ['response'=>true, 'data'=>$option, 'val'=>$totalQuarter['totalqtr'], 'last'=>$lasttotalQuarter];
			}else{
				$response = ['response'=>false];
			}
		}else{
			$response = ['response'=>false];
		}
		echo json_encode($response);
        
    }
	
	
	

}
