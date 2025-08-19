<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\PostModel;
use App\Models\model_ward_mstr;
use App\Models\model_ulb_mstr;
use App\Models\model_saf_dtl;
use App\Models\model_saf_owner_detail;
use App\Models\model_saf_tax;
use App\Models\model_saf_demand;
use App\Models\model_payment_adjust;
use App\Models\model_transaction;
use App\Models\model_tran_mode_mstr;
use App\Models\model_payment_adjustment;
use App\Models\model_fy_mstr;
use App\Models\model_saf_floor_details;
use App\Models\model_level_pending_dtl;
use App\Models\model_saf_collection;
use App\Models\model_transaction_fine_rebet_details;
use App\Models\model_cheque_details;
use App\Models\model_system_name;
use App\Models\model_view_saf_dtl;
use Exception;

class CitizenPropertySAF extends HomeController
{
    protected $db;
	protected $dbSystem;
	//protected $db_name;
	protected $model_ward_mstr;
	protected $model_ulb_mstr;
	protected $model_saf_dtl;
	protected $model_saf_owner_detail;
	protected $model_saf_tax;
	protected $model_saf_demand;
	protected $model_payment_adjust;
	protected $model_transaction;
	protected $model_tran_mode_mstr;
	protected $model_payment_adjustment;
	protected $model_fy_mstr;
	protected $model_saf_floor_details;
	protected $model_level_pending_dtl;
	protected $model_saf_collection;
	protected $model_transaction_fine_rebet_details;
	protected $model_cheque_details;
	protected $model_system_name;
	protected $model_view_saf_dtl;
	
	public function __construct()
    {
        parent::__construct();
    	helper(['db_helper', 'qr_code_generator_helper']);
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
		$this->model_fy_mstr = new model_fy_mstr($this->dbSystem);
		$this->model_saf_dtl = new model_saf_dtl($this->db);
		$this->model_saf_owner_detail = new model_saf_owner_detail($this->db);
		$this->model_saf_tax = new model_saf_tax($this->db);
		$this->model_saf_demand = new model_saf_demand($this->db);
		$this->model_payment_adjust = new model_payment_adjust($this->db);
		$this->model_transaction = new model_transaction($this->db);
		$this->model_tran_mode_mstr = new model_tran_mode_mstr($this->db);
		$this->model_payment_adjustment = new model_payment_adjustment($this->db);
		$this->model_saf_floor_details = new model_saf_floor_details($this->db);
		$this->model_level_pending_dtl = new model_level_pending_dtl($this->db);
		$this->model_saf_collection = new model_saf_collection($this->db);
		$this->model_transaction_fine_rebet_details = new model_transaction_fine_rebet_details($this->db);
		$this->model_cheque_details = new model_cheque_details($this->db);
		$this->model_view_saf_dtl = new model_view_saf_dtl($this->db);
		$this->model_system_name = new model_system_name($this->dbSystem);
    }

    function __destruct() {
		if (isset($this->db)) $this->db->close();
		if (isset($this->dbSystem)) $this->dbSystem->close();
	}

	
	
	public function index($name=null)
    {
		try
		{
			$ulb_mstr_id = 1;
			$data =(array)null;
			$data['name']=$name;
			if($this->request->getMethod()=='post')
			{
				$inputs = filterSanitizeStringtoUpper($this->request->getVar());
				$data['ward_mstr_id']=$inputs['ward_mstr_id'];
				$data['keyword']=$inputs['keyword'];
				
				if($data['ward_mstr_id']!="" and $data['keyword']!="")
				{
					$where="ward_mstr_id='".$data['ward_mstr_id']."' and (saf_no = '".$data['keyword']."')";
				}
				$data['emp_details'] = $this->model_saf_dtl->citizen_details($where);
				$data['wardlist'] = $this->model->getWardList(['ulb_mstr_id'=>$ulb_mstr_id]);
				//print_r($data['emp_details']);
				return view('citizen/SAF/selectWardApplication_no', $data);
			}
			else
			{
				$data['wardlist'] = $this->model->getWardList(['ulb_mstr_id'=>$ulb_mstr_id]);
				return view('citizen/SAF/selectWardApplication_no', $data);
			}
		}
		catch (Exception $e) {

        }
		
    }
	
	
    
	public function citizen_saf_due_details($id=null)
	{
		$data =(array)null;
		$data['id']=$id;
		//print_r($data);
		$data['basic_details'] = $this->model_saf_dtl->basic_details($data);
		if ( $owner_details = $this->model_saf_owner_detail->ownerdetails_md5($data['id'])) {
			$data['owner_details'] = $owner_details;
		}
		if ( $tax_list = $this->model_saf_tax->tax_list($data['basic_details']['saf_dtl_id'])) {
			$data['tax_list'] = $tax_list;
		}
		if ( $demand_detail = $this->model_saf_demand->demand_detail($data) ) {
			$data['demand_detail'] = $demand_detail;
		}
		if ( $payment_detail = $this->model_transaction->payment_detail($data['basic_details']['saf_dtl_id'])) {
			$data['payment_detail'] = $payment_detail;
		}
		$data['msg'] = $this->model_saf_dtl->msg($data);
		//print_r($data['msg']);
		if($data['msg']['payment_status']==1 AND $data['msg']['doc_upload_status']==1){
			if($data['msg']['saf_pending_status']==0){
				$data['msglevelPending'] = $this->model_level_pending_dtl->msglevelPending($data);
				if($data['msglevelPending']['receiver_user_type_id']==6){
					$data['SAFLevelPending'] = "Pending At Dealing Assistant";
				}elseif($data['msglevelPending']['receiver_user_type_id']==5){
					$data['SAFLevelPending'] = "Pending At Tax Collector";
				}elseif($data['msglevelPending']['receiver_user_type_id']==7){
					$data['SAFLevelPending'] = "Pending At ULB Tax Collector";
				}elseif($data['msglevelPending']['receiver_user_type_id']==9){
					$data['SAFLevelPending'] = "Pending At Section Incharge";
				}elseif($data['msglevelPending']['receiver_user_type_id']==10){
					$data['SAFLevelPending'] = "Pending At Executive Officer";
				}
			}else{ $data['SAFLevelPending'] = "Form Fully Approved"; }
		}elseif($data['msg']['payment_status']==1 AND $data['msg']['doc_upload_status']==0){ 
			$data['SAFLevelPending'] = "Payment Done But Document Upload Is Pending"; 
		}elseif($data['msg']['payment_status']==0 AND $data['msg']['doc_upload_status']==1){ 
			$data['SAFLevelPending'] = "Document Upload Done But Payment Is Pending"; 
		}elseif($data['msg']['payment_status']==0 AND $data['msg']['doc_upload_status']==0){ 
			$data['SAFLevelPending'] = "Payment Pending And Document Upload Pending"; 
		}
		//print_r($data['basic_details']);
		return view('citizen/SAF/citizen_saf_due_details',$data);
	}
	
	public function citizen_saf_property_details($id=null)
	{
		$data =(array)null;
		$data['id']=$id;
		//print_r($data);
		$data['basic_details'] = $this->model_saf_dtl->basic_details($data);
		if ( $owner_details = $this->model_saf_owner_detail->ownerdetails_md5($data['id'])) {
			$data['owner_details'] = $owner_details;
		}
		if ( $tax_list = $this->model_saf_tax->tax_list($data['basic_details']['saf_dtl_id'])) {
			$data['tax_list'] = $tax_list;
		}
		if ( $demand_detail = $this->model_saf_demand->demand_detail($data) ) {
			$data['demand_detail'] = $demand_detail;
		}
		if ( $occupancy_detail = $this->model_saf_floor_details->occupancy_detail($data['basic_details']['saf_dtl_id']) ) {
			$data['occupancy_detail'] = $occupancy_detail;
		}
		if ( $payment_detail = $this->model_transaction->payment_detail($data['basic_details']['saf_dtl_id'])) {
			$data['payment_detail'] = $payment_detail;
		}
		$data['msg'] = $this->model_saf_dtl->msg($data);
		//print_r($data['msg']);
		if($data['msg']['payment_status']==1 AND $data['msg']['doc_upload_status']==1){
			if($data['msg']['saf_pending_status']==0){
				$data['msglevelPending'] = $this->model_level_pending_dtl->msglevelPending($data);
				if($data['msglevelPending']['receiver_user_type_id']==6){
					$data['SAFLevelPending'] = "Pending At Dealing Assistant";
				}elseif($data['msglevelPending']['receiver_user_type_id']==5){
					$data['SAFLevelPending'] = "Pending At Tax Collector";
				}elseif($data['msglevelPending']['receiver_user_type_id']==7){
					$data['SAFLevelPending'] = "Pending At ULB Tax Collector";
				}elseif($data['msglevelPending']['receiver_user_type_id']==9){
					$data['SAFLevelPending'] = "Pending At Section Incharge";
				}elseif($data['msglevelPending']['receiver_user_type_id']==10){
					$data['SAFLevelPending'] = "Pending At Executive Officer";
				}
			}else{ $data['SAFLevelPending'] = "Form Fully Approved"; }
		}elseif($data['msg']['payment_status']==1 AND $data['msg']['doc_upload_status']==0){ 
			$data['SAFLevelPending'] = "Payment Done But Document Upload Is Pending"; 
		}elseif($data['msg']['payment_status']==0 AND $data['msg']['doc_upload_status']==1){ 
			$data['SAFLevelPending'] = "Document Upload Done But Payment Is Pending"; 
		}elseif($data['msg']['payment_status']==0 AND $data['msg']['doc_upload_status']==0){ 
			$data['SAFLevelPending'] = "Payment Pending And Document Upload Pending"; 
		}
		return view('Citizen/SAF/citizen_saf_property_details', $data);
	}
	
	public function citizen_saf_payment_details($id=null)
	{
		$data =(array)null;
		$data['id']=$id;
		$data['basic_details'] = $this->model_saf_dtl->basic_details($data);
		if ( $owner_details = $this->model_saf_owner_detail->ownerdetails_md5($data['id'])) {
			$data['owner_details'] = $owner_details;
		}
		if ( $tax_list = $this->model_saf_tax->tax_list($data['basic_details']['saf_dtl_id'])) {
			$data['tax_list'] = $tax_list;
		}
		if ( $payment_detail = $this->model_transaction->payment_detail($data['basic_details']['saf_dtl_id'])) {
			$data['payment_detail'] = $payment_detail;
		}
		$data['msg'] = $this->model_saf_dtl->msg($data);
		//print_r($data['msg']);
		if($data['msg']['payment_status']==1 AND $data['msg']['doc_upload_status']==1){
			if($data['msg']['saf_pending_status']==0){
				$data['msglevelPending'] = $this->model_level_pending_dtl->msglevelPending($data);
				if($data['msglevelPending']['receiver_user_type_id']==6){
					$data['SAFLevelPending'] = "Pending At Dealing Assistant";
				}elseif($data['msglevelPending']['receiver_user_type_id']==5){
					$data['SAFLevelPending'] = "Pending At Tax Collector";
				}elseif($data['msglevelPending']['receiver_user_type_id']==7){
					$data['SAFLevelPending'] = "Pending At ULB Tax Collector";
				}elseif($data['msglevelPending']['receiver_user_type_id']==9){
					$data['SAFLevelPending'] = "Pending At Section Incharge";
				}elseif($data['msglevelPending']['receiver_user_type_id']==10){
					$data['SAFLevelPending'] = "Pending At Executive Officer";
				}
			}else{ $data['SAFLevelPending'] = "Form Fully Approved"; }
		}elseif($data['msg']['payment_status']==1 AND $data['msg']['doc_upload_status']==0){ 
			$data['SAFLevelPending'] = "Payment Done But Document Upload Is Pending"; 
		}elseif($data['msg']['payment_status']==0 AND $data['msg']['doc_upload_status']==1){ 
			$data['SAFLevelPending'] = "Document Upload Done But Payment Is Pending"; 
		}elseif($data['msg']['payment_status']==0 AND $data['msg']['doc_upload_status']==0){ 
			$data['SAFLevelPending'] = "Payment Pending And Document Upload Pending"; 
		}
        return view('Citizen/SAF/citizen_saf_payment_details', $data);
	}
	
	public function citizen_saf_confirm_payment($saf_dtl_id_MD5=null)
	{
		/*$data =(array)null;
        $data['id']=$id;
		$data['basic_details'] = $this->model_saf_dtl->basic_details($data);
		if ( $owner_details = $this->model_saf_owner_detail->ownerdetails_md5($data['id'])) {
			$data['owner_details'] = $owner_details;
		}
		if ( $tax_list = $this->model_saf_tax->tax_list($data['basic_details']['saf_dtl_id'])) {
			$data['tax_list'] = $tax_list;
		}
		if ( $demand_detail = $this->model_saf_demand->demand_detail($data) ) {
			$data['demand_detail'] = $demand_detail;
		}
		if ( $fydemand = $this->model_saf_demand->fydemand($data['basic_details']['saf_dtl_id']) ) {
			$data['fydemand'] = $fydemand;
		}
		$mnth = date("m");
		$from_year = date("Y");
		$to_year = $from_year + 01;
		if($mnth>=4 || $mnth<4){
			$fy = $from_year .'-'. $to_year;
			$data['fy_id'] = $this->model_fy_mstr->getFiidByfyyr($fy);
		}
		if($mnth>=4 AND $mnth<=6){
			$data['qtr'] = 1;
		}elseif($mnth>=7 AND $mnth<=9){
			$data['qtr'] = 2;
		}
		elseif($mnth>=10 AND $mnth<=12){
			$data['qtr'] = 3;
		}
		elseif($mnth>=1 AND $mnth<=3){
			$data['qtr'] = 4;
		}
		$data['full_penalty'] = $this->model_saf_demand->full_penalty($data);
		$data['land_occupancy_date'] = $this->model_saf_dtl->land_occupancy_date($data['basic_details']['saf_dtl_id']);
		if($data['land_occupancy_date']['prop_type_mstr_id']!=4)
		{
			$data['safdate'] = $this->model_saf_floor_details->floordate_dtl($data['basic_details']['saf_dtl_id']);
			$data['floorresdtl'] = $this->model_saf_floor_details->floorresdtl($data['basic_details']['saf_dtl_id']);
			$res=0;
			$rel=0;
			$com=0;
			if(!empty($data['floorresdtl'])){
				foreach ($data['floorresdtl'] as  $usagevalue) {
					
					if($usagevalue["usage_type_mstr_id"]==1){ 
						$res=1;
					}elseif($usagevalue["usage_type_mstr_id"]==11){
						$rel=1;
					}else{
						$com=1;
					}
				}
			}
			if($com==1){
				$data["latefine"]=5000;
			}elseif($res==1 and $rel==0 and $com==0){
				$data["latefine"]=2000;
			}elseif($res==0 and $rel==1 and $com==0){
				$data["latefine"]=0;
			}elseif($res==1 and $rel==1 and $com==0){
				$data["latefine"]=2000;
			}
		}*/
		
		$data =(array)null;
		$Session = Session();
		
		$ulb_mstr = $Session->get("ulb_dtl");
		$data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
		
		$data = $this->model_view_saf_dtl->getSafDtlByMD5SafDtlId($saf_dtl_id_MD5);
        $input['saf_dtl_id'] = $data['saf_dtl_id'];
        $data['saf_owner_detail'] = $this->model_saf_owner_detail->getOwnerdtlBySAFId($input);
		$data['saf_tax_list'] = $this->model_saf_tax->getSafTaxDtlBySafDtlId($input);

		$data["fy_demand"] = $this->model_saf_demand->fydemand($data["saf_dtl_id"]);
		
		$data['msg'] = $this->model_saf_dtl->msg($data);
		//print_r($data['full_penalty']);
		if($data['msg']['payment_status']==1 AND $data['msg']['doc_upload_status']==1){
			if($data['msg']['saf_pending_status']==0){
				$data['msglevelPending'] = $this->model_level_pending_dtl->msglevelPending($data);
				if($data['msglevelPending']['receiver_user_type_id']==6){
					$data['SAFLevelPending'] = "Pending At Dealing Assistant";
				}elseif($data['msglevelPending']['receiver_user_type_id']==5){
					$data['SAFLevelPending'] = "Pending At Tax Collector";
				}elseif($data['msglevelPending']['receiver_user_type_id']==7){
					$data['SAFLevelPending'] = "Pending At ULB Tax Collector";
				}elseif($data['msglevelPending']['receiver_user_type_id']==9){
					$data['SAFLevelPending'] = "Pending At Section Incharge";
				}elseif($data['msglevelPending']['receiver_user_type_id']==10){
					$data['SAFLevelPending'] = "Pending At Executive Officer";
				}
			}else{ $data['SAFLevelPending'] = "Form Fully Approved"; }
		}elseif($data['msg']['payment_status']==1 AND $data['msg']['doc_upload_status']==0){ 
			$data['SAFLevelPending'] = "Payment Done But Document Upload Is Pending"; 
		}elseif($data['msg']['payment_status']==0 AND $data['msg']['doc_upload_status']==1){ 
			$data['SAFLevelPending'] = "Document Upload Done But Payment Is Pending"; 
		}elseif($data['msg']['payment_status']==0 AND $data['msg']['doc_upload_status']==0){ 
			$data['SAFLevelPending'] = "Payment Pending And Document Upload Pending"; 
		}
		/*if ( $bank_reCancel = $this->model_bank_recancilation->bank_reCancel($data['basic_details']['saf_dtl_id']) ) {
			$data['bank_reCancel'] = $bank_reCancel;
		}*/
		$data['advance'] = $this->model_payment_adjustment->advance_amnt($data);
		
		$data['tran_mode'] = $this->model_tran_mode_mstr->getTranModeList();

		//print_var($data);
		return view('Citizen/SAF/citizen_saf_confirm_payment', $data);
	}
	
	
	public function citizen_saf_payment_receipt($tran_no=null,$ulb_mstr_id=null)
	{
		$data =(array)null;
		if($ulb_mstr_id==""){
			$ulb_mstr_id = $_SESSION['ulb_dtl']['ulb_mstr_id'];	
		}else{ $ulb_mstr_id = $ulb_mstr_id; }
		$path=base_url('citizenPropertySAF/citizen_saf_payment_receipt/'.$ulb_mstr_id.'/'.$tran_no);
		$data['ss']=qrCodeGeneratorFun($path);
		$data['tran_no']=$tran_no;
		$ulb_mstr_id = $_SESSION['ulb_dtl']['ulb_mstr_id'];	
		$data['ulb_mstr_name'] = $this->modelUlb->getulb_list($ulb_mstr_id);
		$data['tran_mode_dtl'] = $this->model_transaction->getTrandtlList($data['tran_no']);
		$data['coll_dtl'] = $this->model_saf_collection->collection_dtl($data['tran_mode_dtl']['id']);
		$data['fyFrom'] = $this->model_fy_mstr->getfyFromList($data['tran_mode_dtl']['from_fy_mstr_id']);
		$data['fyUpto'] = $this->model_fy_mstr->getfyUptoList($data['tran_mode_dtl']['upto_fy_mstr_id']);
		$data['payMode'] = $this->model_tran_mode_mstr->getpayModeList($data['tran_mode_dtl']['tran_mode_mstr_id']);
		$data['holdingward'] = $this->model_saf_dtl->getholdWard(md5($data['tran_mode_dtl']['prop_dtl_id']));
		$data['basic_details'] = $this->model_saf_dtl->basic_dtl(md5($data['tran_mode_dtl']['prop_dtl_id']));
		if($data['payMode']['id']==2 || $data['payMode']['id']==3){
			$data['chqDD_details'] = $this->model_cheque_details->mode_dtl($data['tran_no']);
		}
		$data['penalty_dtl'] = $this->model_transaction_fine_rebet_details->penalty_dtl($data['tran_mode_dtl']['id']);
		$data['system_name'] = $this->model_system_name->system_name($data['tran_mode_dtl']['tran_date']);
		//print_r($data['system_name']);
		return view('Citizen/SAF/citizen_saf_payment_receipt', $data);
	}
	
	public function ajax_gatequarter()
    {
		if($this->request->getMethod()=='post'){
			$data = [
					'fyUpto' => $this->request->getVar('due_upto_year'),
					'prop_no' => $this->request->getVar('custm_id')
					];
			$result = $this->model_saf_demand->gateQuarter($data);
			$totalQuarter = $this->model_saf_demand->gatetotalQuarter($data);
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
	
	public function ajax_rungatequarter()
    {
		if($this->request->getMethod()=='post'){
			$data = [
					'fyUpto' => $this->request->getVar('due_upto_year'),
					'prop_no' => $this->request->getVar('custm_id')
					];
			$result = $this->model_saf_demand->gateQuarter($data);
			$lastquatr = $this->model_saf_demand->gateQuarterlast($data);
			$totalQuarter = $this->model_saf_demand->gatetotalQuarter($data);
			//$lasttotalQuarter = $this->modeldemand->gatelasttotalQuarter($data);
			if(!empty($result)){
				$option = "";
				$option .= "<option value='".$lastquatr['qtr']."'>".$lastquatr['qtr']."</option>";
				foreach ($result as $value) {
					
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