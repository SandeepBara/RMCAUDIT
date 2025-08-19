<?php 
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Controllers\BO_SAF;
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
use App\Models\model_bank_recancilation;
use App\Models\model_penalty_dtl;
use App\Models\model_datatable;
use App\Models\model_system_name;
use App\Models\model_view_saf_dtl;
use App\Models\model_visiting_dtl;

class safDemandPayment extends AlphaController
{
	protected $db;
	protected $dbSystem;
	//protected $db_name;
	protected $model;
	protected $modelUlb;
	protected $modelfy;
	protected $modelsaf;
	protected $modelowner;
	protected $modeltax;
	protected $modeldemand;
	protected $model_payment_adjust;
	protected $modelpay;
	protected $modeltran;
	protected $modeladjustment;
	protected $modelflr;
	protected $model_level_pending_dtl;
	protected $modelsafcoll;
	protected $modelpenalty;
	protected $modelchqDD;
	protected $model_bank_recancilation;
	protected $model_penalty_dtl;
	protected $model_datatable;
	protected $model_system_name;
	protected $BO_SAF_Controller;
	protected $model_view_saf_dtl;
	protected $model_visiting_dtl;
	
	public function __construct()
    {
        parent::__construct();
    	helper(['db_helper', 'qr_code_generator_helper', 'utility_helper','form_helper']);
        if($db_name = dbConfig("property")){
            $this->db = db_connect($db_name);            
        }
        if($db_system = dbSystem()){
            $this->dbSystem = db_connect($db_system); 
        } 

        $this->model = new model_ward_mstr($this->dbSystem);
		$this->modelUlb = new model_ulb_mstr($this->dbSystem);
		$this->modelfy = new model_fy_mstr($this->dbSystem);
		$this->modelsaf = new model_saf_dtl($this->db);
		$this->modelowner = new model_saf_owner_detail($this->db);
		$this->modeltax = new model_saf_tax($this->db);
		$this->modeldemand = new model_saf_demand($this->db);
		$this->model_payment_adjust = new model_payment_adjust($this->db);
		$this->modelpay = new model_transaction($this->db);
		$this->modeltran = new model_tran_mode_mstr($this->db);
		$this->modeladjustment = new model_payment_adjustment($this->db);
		$this->modelflr = new model_saf_floor_details($this->db);
		$this->model_level_pending_dtl = new model_level_pending_dtl($this->db);
		$this->modelsafcoll = new model_saf_collection($this->db);
		$this->modelpenalty = new model_transaction_fine_rebet_details($this->db);
		$this->modelchqDD = new model_cheque_details($this->db);
		$this->model_bank_recancilation = new model_bank_recancilation($this->db);
		$this->model_penalty_dtl = new model_penalty_dtl($this->db);
		$this->model_datatable = new model_datatable($this->db);
		$this->model_system_name = new model_system_name($this->dbSystem);
		$this->BO_SAF_Controller = new BO_SAF($this->db);
		$this->model_view_saf_dtl = new model_view_saf_dtl($this->db);
		$this->model_visiting_dtl = new model_visiting_dtl($this->dbSystem);
    }

	function __destruct() {
		$this->db->close();
		$this->dbSystem->close();
	}
	
	public function saf_Property_Tax()
	{
		$ulb_mstr_id = $_SESSION['ulb_dtl']['ulb_mstr_id'];	
		$data =(array)null;
        helper(['form']);
		$session = session();
        if($this->request->getMethod()=='post')
        {
			$inputs = filterSanitizeStringtoUpper($this->request->getVar());
			$data['keyword']=$inputs['keyword'];
			$data['ward_mstr_id']=$inputs['ward_mstr_id'];
			if($data['ward_mstr_id']!="" AND $data['keyword']!="")
			{
				$where="ward_mstr_id=".$data['ward_mstr_id']." and (saf_no ilike '%".$data['keyword']."%' or owner_name ilike '%".$data['keyword']."%' or mobile_no::text like '%".$data['keyword']."%')";
			}
			else if($data['keyword']!="" AND $data['ward_mstr_id']=="")
			{
				$where="(saf_no ilike '%".$data['keyword']."%' or owner_name ilike '%".$data['keyword']."%' or mobile_no::text like '%".$data['keyword']."%')";
			}
			$session->set('where', $where);
			return $this->response->redirect(base_url('safDemandPayment/saf_Search_Property/'));
		}
		else
		{
			$data['ward'] = $this->model->getWardList(['ulb_mstr_id'=>$ulb_mstr_id]);
			//$data['emp_details'] = $this->modelsaf->ConsumerDetails();
			return view('property/saf/saf_Property_List', $data);
		}
	}
	
	public function saf_Search_Property()
	{
		$ulb_mstr_id = $_SESSION['ulb_dtl']['ulb_mstr_id'];	
		$data =(array)null;
        helper(['form']);
        $session = session();
		$where = $session->get('where');
		$sql = "select * from view_saf_dtl_ward_ownership_type where status=1 and ".$where;
		$result = $this->model_datatable->getDatatable($sql);
		$emp_details = $result['result'];

		
		$data['emp_details'] = $emp_details;
		$data['pager'] = $result['count'];
		
		$data['ward'] = $this->model->getWardList(['ulb_mstr_id'=>$ulb_mstr_id]);
		return view('property/saf/saf_Property_List', $data);
	}
	
	
	public function saf_due_details($id=null)
	{
		$data =(array)null;
		$Session = Session();
			
		$ulb_mstr = $Session->get("ulb_dtl");
		$data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
		$emp_mstr = $Session->get("emp_details");
		$data['user_type_id'] = $emp_mstr["user_type_mstr_id"];
		
		$data['id']=$id;
		//print_r($data);
		$data['basic_details'] = $this->modelsaf->basic_details($data);
		if ( $owner_details = $this->modelowner->ownerdetails_md5($data['id'])) {
			$data['owner_details'] = $owner_details;
		}
		if ( $tax_list = $this->modeltax->tax_list($data['basic_details']['saf_dtl_id'])) {
			$data['tax_list'] = $tax_list;
		}
		if ( $demand_detail = $this->modeldemand->demand_detail($data) ) {
			$data['demand_detail'] = $demand_detail;
		}
		$data['safdate'] = $this->modelflr->floordate_dtl($data['basic_details']['saf_dtl_id']);
		if ( $payment_detail = $this->modelpay->payment_detailsaf($data['basic_details']['saf_dtl_id'])) {
			$data['payment_detail'] = $payment_detail;
			$data['Paysafpayment'][0] = $payment_detail;
		}
		
		$data['msg'] = $this->modelsaf->msg($data);
		//print_r($data['msg']);
		if($data['msg']['payment_status']==1 AND $data['msg']['doc_upload_status']==1){
			if($data['msg']['saf_pending_status']==0){
				$data['msglevelPending'] = $this->model_level_pending_dtl->msglevelPending($data);
				//print_r($data['msglevelPending']);
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
			}elseif($data['msg']['saf_pending_status']==1){ $data['SAFLevelPending'] = "Form Fully Approved"; }
			elseif($data['msg']['saf_pending_status']==2){ $data['SAFLevelPending'] = "Back To Citizen"; }
		}elseif($data['msg']['payment_status']==1 AND $data['msg']['doc_upload_status']==0){ 
			$data['SAFLevelPending'] = "Payment Done But Document Upload Is Pending"; 
		}elseif($data['msg']['payment_status']==0 AND $data['msg']['doc_upload_status']==1){ 
			$data['SAFLevelPending'] = "Document Upload Done But Payment Is Pending"; 
		}elseif($data['msg']['payment_status']==0 AND $data['msg']['doc_upload_status']==0){ 
			$data['SAFLevelPending'] = "Payment Pending And Document Upload Pending"; 
		}elseif($data['msg']['payment_status']==2 AND $data['msg']['doc_upload_status']==1){ 
			$data['SAFLevelPending'] = "Document Upload Done But Cheque Not Cleared"; 
		}elseif($data['msg']['payment_status']==2 AND $data['msg']['doc_upload_status']==0){ 
			$data['SAFLevelPending'] = "Cheque Not Cleared And Document Upload Pending"; 
		}
		//print_r($data['owner_details']);
		return view('property/saf/saf_due_details', $data);
	}
	
	public function saf_property_details($id=null)
	{
		$Session = Session();
        $emp_details=$Session->get("emp_details");
		$data =(array)null;
		$data['id']=$id;
		//print_r($data);
		if (is_numeric($data['id'])) {
			$basic_details = $this->modelsaf->basic_details_by_id($data);
		} else {
			$basic_details = $this->modelsaf->basic_details($data);	
		}
		//print_r($basic_details);
		$data['basic_details'] = $basic_details;
		$data['owner_details'] = $this->modelowner->ownerdetailsBySafId($basic_details['saf_dtl_id']);
		$data['tax_list'] = $this->modeltax->tax_list($data['basic_details']['saf_dtl_id']);
		$data['demand_detail'] = $this->modeldemand->demand_detail_by_id($basic_details['saf_dtl_id']);
		
		$data['basic_details_data']=array(
			'saf_no'=> isset($basic_details['saf_no'])?$basic_details['saf_no']:'N/A',
            'apply_date'=> isset($basic_details['apply_date'])?$basic_details['apply_date']:'N/A',
            'ward_no'=> isset($basic_details['ward_no'])?$basic_details['ward_no']:'N/A',
            'new_holding_no'=> isset($basic_details['new_holding_no'])?$basic_details['new_holding_no']:'N/A',
            'new_ward_no'=> isset($basic_details['new_ward_no'])?$basic_details['new_ward_no']:'N/A',
            'holding_no'=> isset($basic_details['holding_no'])?$basic_details['holding_no']:'N/A',
            'assessment_type'=> isset($basic_details['assessment_type'])?$basic_details['assessment_type']:'N/A',
            'plot_no'=> isset($basic_details['plot_no'])?$basic_details['plot_no']:'N/A',
            'property_type'=> isset($basic_details['property_type'])?$basic_details['property_type']:'N/A',
            'area_of_plot'=> isset($basic_details['area_of_plot'])?$basic_details['area_of_plot']:'N/A',
            'ownership_type'=> isset($basic_details['ownership_type'])?$basic_details['ownership_type']:'N/A',
            'is_water_harvesting'=> isset($basic_details['is_water_harvesting'])?$basic_details['is_water_harvesting']:'N/A',
            'holding_type'=> isset($basic_details['holding_type'])?$basic_details['holding_type']:'N/A',
            'prop_address'=> isset($basic_details['prop_address'])?$basic_details['prop_address']:'N/A',
            'road_type'=> isset($basic_details['road_type'])?$basic_details['road_type']:'N/A',
            'zone_mstr_id'=> isset($basic_details['zone_mstr_id'])?$basic_details['zone_mstr_id']:'N/A',
            'entry_type'=> isset($basic_details['entry_type'])?$basic_details['entry_type']:'N/A',
            'flat_registry_date'=> isset($basic_details['flat_registry_date'])?$basic_details['flat_registry_date']:'N/A',
            'created_on'=> isset($basic_details['created_on'])?$basic_details['created_on']:'N/A',
            'prop_type_mstr_id'=> isset($basic_details['prop_type_mstr_id'])?$basic_details['prop_type_mstr_id']:'N/A',
            'appartment_name'=> isset($basic_details['apartment_name'])?$basic_details['apartment_name']:'',
            'apt_code'=> isset($basic_details['apt_code'])?$basic_details['apt_code']:'N/A',
            'prop_type'=> 'saf'

        );
		
		$data["emp_details"]=$emp_details;
		return view('property/saf/saf_property_details', $data);
	}
	
	public function saf_payment_details($id=null)
	{
		$data =(array)null;
		$data['id']=$id;
		$data['basic_details'] = $this->modelsaf->basic_details($data);
		if ( $owner_details = $this->modelowner->ownerdetails_md5($data['id'])) {
			$data['owner_details'] = $owner_details;
		}
		if ( $tax_list = $this->modeltax->tax_list($data['basic_details']['saf_dtl_id'])) {
			$data['tax_list'] = $tax_list;
		}
		if ( $payment_detail = $this->modelpay->payment_detail($data['basic_details']['prop_dtl_id'],$data['basic_details']['saf_dtl_id'])) {
			$data['payment_detail'] = $payment_detail;
		}
		
		$data['msg'] = $this->modelsaf->msg($data);
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
			}elseif($data['msg']['saf_pending_status']==1){ $data['SAFLevelPending'] = "Form Fully Approved"; }
			elseif($data['msg']['saf_pending_status']==2){ $data['SAFLevelPending'] = "Back To Citizen"; }
		}elseif($data['msg']['payment_status']==1 AND $data['msg']['doc_upload_status']==0){ 
			$data['SAFLevelPending'] = "Payment Done But Document Upload Is Pending"; 
		}elseif($data['msg']['payment_status']==0 AND $data['msg']['doc_upload_status']==1){ 
			$data['SAFLevelPending'] = "Document Upload Done But Payment Is Pending"; 
		}elseif($data['msg']['payment_status']==0 AND $data['msg']['doc_upload_status']==0){ 
			$data['SAFLevelPending'] = "Payment Pending And Document Upload Pending"; 
		}elseif($data['msg']['payment_status']==2 AND $data['msg']['doc_upload_status']==1){ 
			$data['SAFLevelPending'] = "Document Upload Done But Cheque Not Cleared"; 
		}elseif($data['msg']['payment_status']==2 AND $data['msg']['doc_upload_status']==0){ 
			$data['SAFLevelPending'] = "Cheque Not Cleared And Document Upload Pending"; 
		}
		return view('property/saf/saf_payment_details', $data);
	}
	
	public function saf_confirm_payment($id=null)
	{
		$data =(array)null;
		$Session = Session();
		
		$ulb_mstr = $Session->get("ulb_dtl");
		$data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
		$emp_mstr = $Session->get("emp_details");
		$data['emp_details_id'] = $emp_mstr["id"];
		$data['emp_type_id'] = $emp_mstr["user_type_mstr_id"];
		$data['id']=$id;

		if($id<>null)
		{
			if($this->request->getMethod()=='post')
			{
				$this->db->transBegin();	
				$data = [
							'custm_id' => $this->request->getVar('custm_id'),
							'due_upto_year' => $this->request->getVar('due_upto_year'),
							'date_upto_qtr' => $this->request->getVar('date_upto_qtr'),
							//'total_rebate' => $this->request->getVar('total_rebate'),
							//'total_payabl' => $this->request->getVar('total_payabl'),
							'payment_mode' => $this->request->getVar('payment_mode'),
							'from_fy_year' => $this->request->getVar('from_fy_year'),
							'from_fy_qtr' => $this->request->getVar('from_fy_qtr'),
							'ward_mstr_id' => $this->request->getVar('ward_mstr_id'),
							'total_qrt' => $this->request->getVar('total_qrt'),
							'ful_qtr' => $this->request->getVar('ful_qtr'),
							'chq_date' => $this->request->getVar('chq_date'),
							'chq_no' => $this->request->getVar('chq_no'),
							'bank_name' => $this->request->getVar('bank_name'),
							'branch_name' => $this->request->getVar('branch_name'),
							'emp_details_id' => $emp_mstr["id"]
						];
									
				$session = session();
				$data['emp_details'] = $session->get('emp_details');
				$data['emp_type_id'] = $data['emp_details']["user_type_mstr_id"];
				$data['date'] = date('Y-m-d');
				$data["tran_by_type"] = "JSK";
				
				$date1 = strtotime($data['date']);
				
				$from_fyear = $this->modelfy->getFyearByFyid($data['from_fy_year']);
				$data['from_fyear'] = $from_fyear['fy'];
				$upto_fyear = $this->modelfy->getFyearByFyid($data['due_upto_year']);
				$data['upto_fyear'] = $upto_fyear['fy'];
				if($data['payment_mode']==1){
					$data['tran_mode']= "CASH";
				}elseif($data['payment_mode']==2){
					$data['tran_mode']= "CHEQUE";
				}elseif($data['payment_mode']==3){
					$data['tran_mode']= "DD";
				}
				
				$data['bank_reCancel'] = $this->model_bank_recancilation->bank_reCancel($data['custm_id']);
				if($data['bank_reCancel']['amount']!=""){
					$bank_reCancel = $data['bank_reCancel']['amount'];
					
				}else{
					$bank_reCancel = 0;
				}
				$land_occupancy_date = $this->modelsaf->land_occupancy_date($data["custm_id"]);
				
				if($land_occupancy_date['prop_type_mstr_id']==4)
				{
					$date2 = strtotime($land_occupancy_date['land_occupation_date']);
					$year1 = date('Y', $date2);
					$year2 = date('Y', $date1);

					$month1 = date('m', $date2);
					$month2 = date('m', $date1);

					$months = (($year2 - $year1) * 12) + ($month2 - $month1);
					if($months>3){
						if($land_occupancy_date['is_mobile_tower']=='t' ||$land_occupancy_date['is_hoarding_board']=='t')
						{
							$land_occupancy_delay_fine=5000;
						}else{
							$land_occupancy_delay_fine=2000;
						}
					}else{
						$land_occupancy_delay_fine=0;
					}
				}
				else
				{
					$data['safdate'] = $this->modelflr->floordate_dtl($data['custm_id']);
					$data['floorresdtl'] = $this->modelflr->floorresdtl($data['custm_id']);
					$res=0;
					$rel=0;
					$com=0;
					if(!empty($data['floorresdtl']))
					{
						foreach ($data['floorresdtl'] as  $usagevalue)
						{
							
							if($usagevalue["usage_type_mstr_id"]==1)
							{
								$res=1;
							}
							elseif($usagevalue["usage_type_mstr_id"]==11)
							{
								$rel=1;
							}
							else
							{
								$com=1;
							}
						}
					}


					if($com==1)
					{
						$data["latefine"]=5000;
					}
					elseif($res==1 and $rel==0 and $com==0)
					{
						$data["latefine"]=2000;
					}
					elseif($res==0 and $rel==1 and $com==0)
					{
						$data["latefine"]=0;
					}
					elseif($res==1 and $rel==1 and $com==0)
					{
						$data["latefine"]=2000;
					}
					
					$date3 = strtotime($data['safdate']['date_from']);
					$year1 = date('Y', $date3);
					$year2 = date('Y', $date1);

					$month1 = date('m', $date3);
					$month2 = date('m', $date1);

					$diffmonths = (($year2 - $year1) * 12) + ($month2 - $month1);
					if($diffmonths>3)
					{
						$land_occupancy_delay_fine=$data["latefine"];
					}
				}
					
				$data['land_occupancy_delay_fine'] = $land_occupancy_delay_fine;
				
				
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
				//print_r($data['ful_qtr']);
				$data["total_qrt_pnlty"] = 0;
				$data["deman_am"] = 0;
				$j = 0;
				$data['tol_pently'] = 0;
				$data['demand_amn'] = $this->modeldemand->demand_amnt($data["custm_id"]);
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
					$from_year = date("Y");
					$to_year = $from_year + 01;
					$fy = $from_year .'-'. $to_year;
					$data['fy_id'] = $this->modelfy->getFiidByfyyr($fy);
					if($data['date_upto_qtr']==4){
						$data['demand_rbt'] = $this->modeldemand->demand_rebet($data["custm_id"],$data['fy_id']['id']);
						$rebate = ($data['demand_rbt']['sum']/100)*5;
					}
					else{ $rebate = 0; }
				}else{ $rebate = 0; }
				$data['rebate'] = $rebate;
				//print_r($data['land_occupancy_delay_fine']);
				$data["total_pabl"] = ($data["deman_am"] + $data["land_occupancy_delay_fine"] + $data['tol_pently'] + $bank_reCancel) - $data['rebate'];
				
				if($data['emp_type_id']!=4){
					$data["total_pa_onjsk"] = ($data['total_pabl']/100)*2.5;
					$data["total_payabl"] = $data["total_pabl"] - $data["total_pa_onjsk"];
					echo "inh";
				}else if($data['payment_mode']==4){
					$data["total_pa_onjsk"] = ($data['total_pabl']/100)*5;
					$data["total_payabl"] = $data["total_pabl"] - $data["total_pa_onjsk"];
					echo "hjg";
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
				
				$data['checkPayment'] = $this->modelpay->checkPayment($data);
				if($data['checkPayment']){
					flashToast("saf_confirm_payment", "Same Day More Than One Time Payment Cann't Done!!!");
					return $this->response->redirect(base_url('safDemandPayment/saf_confirm_payment/'.md5($data['custm_id'])));
				}else{
				$data['insertPayment'] = $this->modelpay->safinsertPayment($data);
				
				if($data['insertPayment']){
					if($data["payment_mode"]=='2' || $data["payment_mode"]=='3'){
						$chqDDdetails = $this->modelchqDD->chqDDdetails($data);
						//die();
					}
					
					$data['paidsts'] = $this->modelsaf->paidsts($data);
					
						$data['demand_amnt'] = $this->modeldemand->demand_amnt($data["custm_id"]);
						
						if($pable_amnt>$data["total_payabl"]){
							$data['advance_amount'] = $pable_amnt-$data["total_payabl"];
							$data['payment_adjustment'] = $this->modeladjustment->payment_adjustment($data);
							$data['demand_id'] = $this->modeldemand->demand_id($data);
							for($i=1;$i<=$data['total_qrt'];$i++){
								
								$data['resultid'] = $data['demand_id'][$j++];
							
								$demand_am = $data['demand_amnt'][$i-1]["balance"];
								//print_r($demand_am);
								$dif_qtr = $dif_qtr + 3;
								$each_penlty = ($demand_am/100)*($tol_mnths-$dif_qtr);
								if($each_penlty>0){
									$data['tol_pent'] = $each_penlty;
								}else{
									$data['tol_pent'] = 0;
								}
								
								$data['updatedemandPayment'] = $this->modeldemand->updatedemandPayment($data);
								
								$data['demandCollection'] = $this->modelsafcoll->demandCollection($data);
								
								
							}
						}
						
						elseif($pable_amnt<$data["total_payabl"]){
							$pay_amount = $pable_amnt;
							$data['demand_id'] = $this->modeldemand->demand_id($data);	
							for($i=1;$i<=$data['total_qrt'];$i++){
								
								$data['resultid'] = $data['demand_id'][$j++];
									
								$demand_am = $data['demand_amnt'][$i-1]["balance"];
									
								if($tol_mnths>=3){
									$dif_qtr = $dif_qtr + 3;
									$each_penlty = ($demand_am/100)*($tol_mnths-$dif_qtr);
									$data['tol_pent'] = $each_penlty;
									if($tol_pent<0){
										$data['tol_pent']=0;
									}
								}else{ $data['tol_pent']=0; }	
								
									if($pay_amount>0){		
										if($pay_amount>$demand_am){
											$pay_amount = $pay_amount-($demand_am+$data['tol_pent']);
											
											$data['updatedemandPayment'] = $this->modeldemand->updatedemandPayment($data);
										
											$data['demandCollection'] = $this->modelsafcoll->demandCollection($data);
										}
									}
									else{
										
										$al_tax_id = $this->modeltax->al_tax_id($data);
										
										/*$percent_balance = ($pay_amount/$demand_am)*100;
										$holding_tax = ($al_tax_id['holding_tax']/100)*$percent_balance;
										$water_tax = ($al_tax_id['water_tax']/100)*$percent_balance;
										$education_cess = ($al_tax_id['education_cess']/100)*$percent_balance;
										$health_cess = ($al_tax_id['health_cess']/100)*$percent_balance;
										//$lighting_tax = ($al_tax_id['lighting_tax']/100)*$percent_balance;
										$latrine_tax = ($al_tax_id['latrine_tax']/100)*$percent_balance;
										$harvest_tax = ($al_tax_id['additional_tax']/100)*$percent_balance;
										//print_r($pay_amount);*/
										$data['balance'] = ($demand_am+$data['tol_pent'])-$pay_amount;
										$pay_amount=0;
										
										$data['updatedemandPayment'] = $this->modeldemand->updatedemandPaymentblnc($data);
										
										$data['demandCollection'] = $this->modelsafcoll->demandCollection($data);
										
									}
							}
						
						}
						
						else{
							
							$data['demand_id'] = $this->modeldemand->demand_id($data);
							$data['tol_pents'] = 0;
							$dif_qtr = 0;
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
										}else { $data['tol_pent'] = $data['tol_pents']; }
									}else { $data['tol_pent'] = $data['tol_pents']; }
								}else { $data['tol_pent'] = $data['tol_pents']; }
								
								$data['pntmnth'] = $tol_mnths-$dif_qtr;
								$data["date_cls"]= date("Y-m-d H:i:s");
								$data['updatedemandPayment'] = $this->modeldemand->updatedemandPayment($data);
										
								$data['demandCollection'] = $this->modelsafcoll->demandCollection($data);
							}
						}
					
					
					if($data['rebate']>0){
						$data['head_name'] = "First Quartare Discount";
						$data['fine_rebet_amount'] = $data['rebate'];
						$data['add_minus'] = "Minus";
						
						$data['fine_rebet_details'] = $this->modelpenalty->fine_rebet_details($data);
					}
					if($data['land_occupancy_delay_fine']>0){
						$data['head_name'] = "Late Assessment Fine(Rule 14.1)";
						$data['fine_rebet_amount'] = $data['land_occupancy_delay_fine'];
						$data['add_minus'] = "Add";
						
						$data['fine_rebet_details'] = $this->modelpenalty->fine_rebet_details($data);
					}
					if($data['tol_pently']>0){
						$data['head_name'] = "1% Penalty On Demand Amount";
						$data['fine_rebet_amount'] = $data['tol_pently'];
						$data['add_minus'] = "Add";
						
						$data['fine_rebet_details'] = $this->modelpenalty->fine_rebet_details($data);
					}
					
					if($data['total_pa_onjsk']>0)
					{
						$data['head_name'] = "Rebate From Jsk Payment";
						$data['fine_rebet_amount'] = $data['total_pa_onjsk'];
						$data['add_minus'] = "Minus";
						
						$data['fine_rebet_details'] = $this->modelpenalty->fine_rebet_details($data);
					}
					
					if($bank_reCancel>0){
						$data['chequePaymentDone'] = $this->model_bank_recancilation->chequePaymentDone($data);
					}					
				}
				
				$data['docstatus'] = $this->modelsaf->docstatus($data['custm_id']);
				//print_r($data['docstatus']);
				
				if($data['docstatus']['doc_upload_status']==1){
					$leveldata = [
							'saf_dtl_id' => $data['custm_id'],
							'sender_user_type_id' => 0,
							'receiver_user_type_id' => 6,
							'forward_date' => date('Y-m-d'),
							'forward_time' => date('H:i:s'),
							'created_on' =>date('Y-m-d H:i:s'),
							'remarks' => 'Payment Done And Document Uploaded',
							'verification_status' => 0
							];
					//print_r($leveldata);
					$level_pending_insrt=$this->model_level_pending_dtl->insrtlevelpendingdtl($leveldata);
				}
					
					if($this->db->transStatus() === FALSE){
						$this->db->transRollback();
						flashToast("saf_confirm_payment", "Something error due to payment!!!");
						return $this->response->redirect(base_url('safDemandPayment/saf_confirm_payment/'.md5($data['custm_id'])));
					}else{
						$this->db->transCommit();
						return $this->response->redirect(base_url('safDemandPayment/saf_payment_receipt/'.md5($data['insertPayment'])));
					}
				}
				
				//return $this->response->redirect(base_url('safDemandPayment/saf_payment_receipt/'.md5($data['insertPayment'])));
				//echo "dhfvbj";
				
				
				
			}
			else
			{
				$data['basic_details'] = $this->modelsaf->basic_details($data);
				if($owner_details = $this->modelowner->ownerdetails_md5($data['id']))
				{
					$data['owner_details'] = $owner_details;
				}
				$data['tax_list'] = $this->modeltax->tax_list($data['basic_details']['saf_dtl_id']);
				$data['demand_detail'] = $this->modeldemand->demand_detail($data);
				if($fydemand = $this->modeldemand->fydemand($data['basic_details']['saf_dtl_id']))
				{
					$data['fydemand'] = $fydemand;
				}
				
				$mnth = date("m");
				$from_year = date("Y");
				$to_year = $from_year + 01;
				if($mnth>=4 || $mnth<4){
					$fy = $from_year .'-'. $to_year;
					$data['fy_id'] = $this->modelfy->getFiidByfyyr($fy);
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
				$data['full_penalty'] = $this->modeldemand->full_penalty($data);
				$data['land_occupancy_date'] = $this->modelsaf->land_occupancy_date($data['basic_details']['saf_dtl_id']);
				if($data['land_occupancy_date']['prop_type_mstr_id']!=4)
				{
					$data['safdate'] = $this->modelflr->floordate_dtl($data['basic_details']['saf_dtl_id']);
					$data['floorresdtl'] = $this->modelflr->floorresdtl($data['basic_details']['saf_dtl_id']);
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
				}
				if ($payDetails = $this->modelpay->payDetails($data['basic_details']['saf_dtl_id']))
				{
					$data['payDetails'] = $payDetails;
				}
				
				
				//print_r($data);
				//print_r($data['payDetails']);
				if ( $bank_reCancel = $this->model_bank_recancilation->bank_reCancel($data['basic_details']['saf_dtl_id']) ) {
					$data['bank_reCancel'] = $bank_reCancel;
				}
				if ( $difference_Penalty = $this->model_penalty_dtl->difference_Penalty($data['basic_details']['saf_dtl_id']) ) {
					$data['difference_Penalty'] = $difference_Penalty;
				}
				$data['tran_mode'] = $this->modeltran->getTranModeList();
				return view('Property/saf/saf_confirm_payment', $data);
			}
		
		}
	}
	
	public function safPaymentProceed($saf_dtl_id_MD5)
	{
		$data =(array)null;
		$Session = Session();
		
		$ulb_mstr = $Session->get("ulb_dtl");
		$data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
		$emp_mstr = $Session->get("emp_details");
		$data['emp_details_id'] = $emp_mstr["id"];
		$data['emp_type_id'] = $emp_mstr["user_type_mstr_id"];

		if (is_numeric($saf_dtl_id_MD5)) {
			$basic_details_dt = $this->model_view_saf_dtl->getSafDtlBySafDtlId($saf_dtl_id_MD5);
		} else {
			$basic_details_dt = $this->model_view_saf_dtl->getSafDtlByMD5SafDtlId($saf_dtl_id_MD5);
		}
		$data = $basic_details_dt;

        $input['saf_dtl_id'] = $data['saf_dtl_id'];
        $data['saf_owner_detail'] = $this->modelowner->getOwnerdtlBySAFId($input);
		$data['saf_tax_list'] = $this->modeltax->getSafTaxDtlBySafDtlId($input);

		$data["fy_demand"] = $this->modeldemand->fydemand($data["saf_dtl_id"]);

		$data['basic_details_data']=array(
			'saf_no'=> isset($basic_details_dt['saf_no'])?$basic_details_dt['saf_no']:'N/A',
            'apply_date'=> isset($basic_details_dt['apply_date'])?$basic_details_dt['apply_date']:'N/A',
            'ward_no'=> isset($basic_details['ward_no'])?$basic_details['ward_no']:'N/A',
            'new_holding_no'=> isset($basic_details['new_holding_no'])?$basic_details['new_holding_no']:'N/A',
            'new_ward_no'=> isset($basic_details['new_ward_no'])?$basic_details['new_ward_no']:'N/A',
            'holding_no'=> isset($basic_details['holding_no'])?$basic_details['holding_no']:'N/A',
            'assessment_type'=> isset($basic_details['assessment_type'])?$basic_details['assessment_type']:'N/A',
            'plot_no'=> isset($basic_details['plot_no'])?$basic_details['plot_no']:'N/A',
            'property_type'=> isset($basic_details['property_type'])?$basic_details['property_type']:'N/A',
            'area_of_plot'=> isset($basic_details['area_of_plot'])?$basic_details['area_of_plot']:'N/A',
            'ownership_type'=> isset($basic_details['ownership_type'])?$basic_details['ownership_type']:'N/A',
            'is_water_harvesting'=> isset($basic_details['is_water_harvesting'])?$basic_details['is_water_harvesting']:'N/A',
            'holding_type'=> isset($basic_details['holding_type'])?$basic_details['holding_type']:'N/A',
            'prop_address'=> isset($basic_details['prop_address'])?$basic_details['prop_address']:'N/A',
            'road_type'=> isset($basic_details['road_type'])?$basic_details['road_type']:'N/A',
            'zone_mstr_id'=> isset($basic_details['zone_mstr_id'])?$basic_details['zone_mstr_id']:'N/A',
            'entry_type'=> isset($basic_details['entry_type'])?$basic_details['entry_type']:'N/A',
            'flat_registry_date'=> isset($basic_details['flat_registry_date'])?$basic_details['flat_registry_date']:'N/A',
            'created_on'=> isset($basic_details['created_on'])?$basic_details['created_on']:'N/A',
            'prop_type_mstr_id'=> isset($basic_details['prop_type_mstr_id'])?$basic_details['prop_type_mstr_id']:'N/A',
            'appartment_name'=> isset($basic_details['appartment_name'])?$basic_details['appartment_name']:'N/A',
            'apt_code'=> isset($basic_details['apt_code'])?$basic_details['apt_code']:'N/A',
            'prop_type'=> 'saf'

        );
		//print_var($data["fy_demand"]);
		//$this->cachePage(10);

		$data["is_tc_apply"] = false;
		if($data["emp_details_id"]??false){
			$applyEmp = $this->db->query("select * from view_emp_details where id = ".$data["emp_details_id"]." AND user_type_id in(5,7)")->getFirstRow("array");
			$data["is_tc_apply"] = $applyEmp ? true : false;
		}
		$geoTag = $this->db->query("select distinct (direction_type) as direction_type from tbl_saf_geotag_upload_dtl where geotag_dtl_id = ".$data['saf_dtl_id']." AND status =1 ")->getResultArray();
		$data["is_geo_tag_done"] = sizeof($geoTag)>=3 ? true : false;

		return view('Property/saf/safPaymentProceed', $data);
	}

	public function Ajax_getQtr()
	{
		$response = ['response'=> false];
		if($this->request->getMethod()=='post')
		{
			$data = [
					'fy_mstr_id' => $this->request->getVar('fy_mstr_id'),
					'saf_dtl_id' => $this->request->getVar('saf_dtl_id'),
					];
			$result = $this->modeldemand->getDistinctQtr($data);
			$option=null;
			if(!empty($result))
			{
				foreach($result as $value)
				{
					$option .= '<option value="'.$value['qtr'].'">'.$value['qtr'].'</option>';
					//break;
				}
			}
			
			$response = ['response'=> true, 'data'=> $option];
		}else{
			$response = ['response'=> false, "Only POST method allowed"];
		}
		echo json_encode($response);
	}

	public function Ajax_getSAFPayableAmount()
	{
		$Session = Session();
		$emp_mstr = $Session->get("emp_details");
		$user_id = $emp_mstr["id"];

		$response = ['response'=> false];
		if($this->request->getMethod()=='post')
		{
			$input = [
					'fy' => $this->request->getVar('fy'),
					'qtr' => $this->request->getVar('qtr'),
					'saf_dtl_id' => $this->request->getVar('saf_dtl_id'),
					'user_id'=> $user_id,
					];
			$data=$this->modeldemand->getSAFDemandAmountDetails($input);
			
			$out='<tr>
						<td class="pull-right">Demand Amount</td>
						<td>'.$data['DemandAmount'].'</td>
						<td class="pull-right">Rebate</td>
						<td>'.$data['RebateAmount'].'</td>
					</tr>
					<tr>
						<td class="pull-right"></td>
						<td></td>
						<td class="pull-right">Special Rebate</td>
						<td>'.$data['SpecialRebateAmount'].'</td>
					</tr>
					<tr>
						<td class="pull-right">Late Assessment Penalty</td>
						<td>'.$data['LateAssessmentPenalty'].'</td>
						<td class="pull-right">	1 % Interest </td>
						<td>'.$data['OnePercentPnalty'].'</td>
					</tr>';
			if($data["OtherPenalty"])
			$out.='<tr>
						<td class="pull-right">Other Penalty</td>
						<td colspan="3">'.$data['OtherPenalty'].'</td>
					</tr>';
				
			$out.='<tr>
						<td class="pull-right">Advance</td>
						<td>'.$data['AdvanceAmount'].'</td>
						<td class="pull-right text-success">Total Paybale Amount</td>
						<td class="text-success" id="total_payable_amount_temp">'.$data['PayableAmount'].'</td>
					</tr>';
			$response = ['response'=> true, 'data'=> $data, 'html_data'=> $out];
		}
		echo json_encode($response);
	}

	public function Ajax_saf_pay_now()
	{
		
		$Session = Session();
		$emp_mstr = $Session->get("emp_details");
		
		$user_id = $emp_mstr["id"];
		$user_type_mstr_id = $emp_mstr["user_type_mstr_id"];

		$response = ['response'=> false];
		$inputs = filterSanitizeStringtoUpper($this->request->getVar());
		
		if($this->request->getMethod()=='post')
		{
			
			$cheque_dtl=(array)null;
			$data=[
				"saf_dtl_id"=> $inputs["saf_dtl_id"],
				"fy"=> $inputs["fy"],
				"qtr"=> $inputs["qtr"],
				"user_id"=> $user_id,
				"payment_mode"=> $inputs["payment_mode"],
				"remarks"=> $inputs["remarks"],
				"total_payable_amount"=> (isset($inputs["total_payable_amount"]) && $inputs["total_payable_amount"]=="") ? 0.00 : $inputs["total_payable_amount"],
			];
			
			
			
			if(in_array($inputs["payment_mode"], ["CHEQUE", "DD"]))
			$cheque_dtl=[
				"bank_name"=> $inputs["bank_name"],
				"branch_name"=> $inputs["branch_name"],
				"cheque_no"=> $inputs["cheque_no1"],
				"cheque_date"=> $inputs["cheque_date"],
			];

			
			//$this->db->transBegin();
			$trxn_id=$this->modelpay->saf_pay_now($data, $cheque_dtl);
			//$this->db->transRollback();
			
			
			//Agency Tax Collector
			if($user_type_mstr_id==5)
			{
				$application = $this->modelsaf->SafDetailsById($inputs["saf_dtl_id"]);							
				$vistingRepostInput = safTranVisit($application,$trxn_id,$this->request->getVar());           
				$visiting_dtl_id = $this->model_visiting_dtl->insertdetail($vistingRepostInput);
				$redirect_to=base_url()."/mobisafDemandPayment/saf_payment_receipt/".md5($trxn_id);
			}
			else
			$redirect_to=base_url()."/safDemandPayment/saf_payment_receipt/".md5($trxn_id);

			if($trxn_id)
			$response = [
							'response'=> true,
							'url'=> $redirect_to,
						];
		}
		else
		{
			
		}
		echo json_encode($response);
	}

	
	public function saf_payment_receipt($trxn_id_md5)
	{
		$data=(array)null;
        $Session = Session();
        $ulb = $Session->get('ulb_dtl');
        $emp_details=$Session->get("emp_details");

        $path=base_url('citizenPaymentReceipt/saf_payment_receipt/'.$ulb['ulb_mstr_id'].'/'.$trxn_id_md5);
		
		$trxn = $this->modelpay->getTrandtlList($trxn_id_md5);
        $saf = $this->model_view_saf_dtl->get_saf_full_details(md5($trxn["prop_dtl_id"]));
		
        $saf = $saf['get_saf_full_details'];
        $data=json_decode($saf, true);
		$data["tran_mode_dtl"]=$trxn;
        $data['ss']=qrCodeGeneratorFun($path);

        $data['coll_dtl'] = $this->modelsafcoll->collection_dtl($trxn['id']);
		
		$data['penalty_dtl'] = $this->modelpenalty->penalty_dtl($trxn['id']);

		if($trxn['tran_mode_mstr_id']==2 || $trxn['tran_mode_mstr_id']==3) // DD, Cheque
		{
			$data['chqDD_details'] = $this->modelchqDD->mode_dtl(md5($trxn['id']));
		}
		
        $data["ulb"]=$ulb;
        $data["emp_details"]=$emp_details;
		$data['basic_receipt_details'] = $this->modelsaf->basic_receipt_dtl(md5($trxn["prop_dtl_id"]));

		
		// print_var($data['chqDD_details']);
		// return;
		// print_var($data);
		return view('Property/saf/saf_payment_receipt', $data);
		
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
	
	public function ajax_rungatequarter()
    {
		if($this->request->getMethod()=='post')
		{
			$data = [
					'fyUpto' => $this->request->getVar('due_upto_year'),
					'prop_no' => $this->request->getVar('custm_id')
					];
			$result = $this->modeldemand->gateQuarter($data);
			$lastquatr = $this->modeldemand->gateQuarterlast($data);
			$totalQuarter = $this->modeldemand->gatetotalQuarter($data);
			//$lasttotalQuarter = $this->modeldemand->gatelasttotalQuarter($data);
			if(!empty($result))
			{
				$option = "";
				$option .= "<option value='".$lastquatr['qtr']."'>".$lastquatr['qtr']."</option>";
				foreach ($result as $value) {
					$option .= "<option value='".$value['qtr']."'>".$value['qtr']."</option>";
				}
				$lasttotalQuarter = $value['qtr'];
				$response = ['response'=>true, 'data'=>$option, 'val'=>$totalQuarter['totalqtr'], 'last'=>$lasttotalQuarter];
			}else{
				$response = ['response'=>false];
			}
		}
		else
		{
			$response = ['response'=>false];
		}
		echo json_encode($response);
        
    }
	
	
	
	public function dashupdate()
	{
		
		$Session = Session();
		$emp_mstr = $Session->get("emp_details");
		
		$user_id = $emp_mstr["id"];
		$user_type_mstr_id = $emp_mstr["user_type_mstr_id"];

		
		$trxn_id=$this->modelpay->dashupdate();
		print_r($trxn_id);
	}

}
