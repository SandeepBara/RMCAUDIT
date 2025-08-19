<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\PostModel;
use App\Models\model_ward_mstr;
use App\Models\model_ulb_mstr;
use App\Models\model_saf_dtl;
use App\Models\model_saf_owner_detail;
use App\Models\model_saf_tax;
use App\Models\model_saf_demand;
use App\Models\model_transaction;
use App\Models\model_tran_mode_mstr;
use App\Models\model_payment_adjustment;
use App\Models\model_fy_mstr;
use App\Models\model_saf_floor_details;
use App\Models\model_cheque_details;
use App\Models\model_level_pending_dtl;
use App\Models\model_emp_details;
use App\Models\model_saf_collection;
use App\Models\model_transaction_fine_rebet_details;
use App\Models\model_bank_recancilation;
use App\Models\model_penalty_dtl;
use App\Models\model_view_ward_permission;
use App\Models\model_datatable;
use App\Controllers\BO_SAF;

class mobisafDemandPayment extends MobiController
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
	protected $model_transaction;
	protected $model_tran_mode_mstr;
	protected $model_payment_adjustment;
	protected $model_fy_mstr;
	protected $model_saf_floor_details;
	protected $model_cheque_details;
	protected $model_level_pending_dtl;
	protected $model_emp_details;
	protected $model_saf_collection;
	protected $model_transaction_fine_rebet_details;
	protected $model_bank_recancilation;
	protected $model_penalty_dtl;
	protected $model_view_ward_permission;
	protected $modeldemand;
	protected $modelsaf;
	protected $modelowner;
	protected $modelpay;
	protected $model_datatable;
	protected $BO_SAF_Controller;
	protected $modelflr;

	public function __construct()
    {
        parent::__construct();
    	helper(['db_helper', 'utility_helper']);
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
		$this->modelemp = new model_emp_details($this->dbSystem);
		$this->modelfy = new model_fy_mstr($this->dbSystem);
		$this->modelsaf = new model_saf_dtl($this->db);
		$this->modelowner = new model_saf_owner_detail($this->db);
		$this->modeltax = new model_saf_tax($this->db);
		$this->modeldemand = new model_saf_demand($this->db);
		$this->modelpay = new model_transaction($this->db);
		$this->modeltran = new model_tran_mode_mstr($this->db);
		$this->modeladjustment = new model_payment_adjustment($this->db);
		$this->modelflr = new model_saf_floor_details($this->db);
		$this->modelchqDD = new model_cheque_details($this->db);
		$this->model_level_pending_dtl = new model_level_pending_dtl($this->db);
		$this->modelsafcoll = new model_saf_collection($this->db);
		$this->modelpenalty = new model_transaction_fine_rebet_details($this->db);
		$this->model_bank_recancilation = new model_bank_recancilation($this->db);
		$this->model_penalty_dtl = new model_penalty_dtl($this->db);
		$this->model_view_ward_permission = new model_view_ward_permission($this->dbSystem);
		$this->model_datatable = new model_datatable($this->db);
		$this->BO_SAF_Controller = new BO_SAF($this->db);
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
		$Session = Session();
        $emp_mstr = $Session->get("emp_details");
        $emp_details_id = $emp_mstr["id"];  
        //print_r($emp_details_id);
        if($this->request->getMethod()=='post')
        {
			$inputs = filterSanitizeStringtoUpper($this->request->getVar());
			$data['keyword'] = $inputs['keyword'];
			$data['ward_mstr_id']= $inputs['ward_mstr_id'];

			if($data['ward_mstr_id']!="" AND $data['keyword']!="")
			{
				$where="ward_mstr_id=".$data['ward_mstr_id']." and (saf_no ilike '%".$data['keyword']."%' or owner_name ilike '%".$data['keyword']."%' or mobile_no::text = '%".$data['keyword']."%')";
			}
			else if($data['keyword']!="" AND $data['ward_mstr_id']=="")
			{
				$where="(saf_no ilike '%".$data['keyword']."%' or owner_name ilike '%".$data['keyword']."%' or mobile_no::text = '%".$data['keyword']."%')";
			}

			$Session->set("keyword", $data['keyword']);
			$Session->set("ward_mstr_id", $data['ward_mstr_id']);
			$Session->set('where', $where);
			return $this->response->redirect(base_url('mobisafDemandPayment/list_of_saf_Property'));
		}
        else
        {
            $data['ward'] = $this->model_view_ward_permission->getPermittedWard($emp_details_id);
            return view('mobile/property/saf/saf_Property_List', $data);
		}
	}
	
	public function list_of_saf_Property()
	{
		/* $data =(array)null;

		$Session = Session();
        $emp_mstr = $Session->get("emp_details");
        $emp_details_id = $emp_mstr["id"];  
        
		$where = $Session->get('where');
		
		$sql = "select * from view_saf_dtl_ward_ownership_type where ".$where;
		//print_r($where);
		//die();
		$result = $this->model_datatable->getDatatable($sql);
		$data['posts'] = $result['result'];
		$data['emp_details'] = $data['posts'];
		$data['pager'] = $result['count'];
		
		$data['ward'] = $this->model_view_ward_permission->getPermittedWard($emp_details_id);
		$data["ward_mstr_id"]=$Session->get("ward_mstr_id");
		$data["keyword"]=$Session->get("keyword");
		return view('mobile/property/saf/saf_Property_List', $data); */
		$session = session();
        $emp_mstr = $session->get("emp_details");
		$emp_details_id = $emp_mstr["id"];
        $data = arrFilterSanitizeString($this->request->getVar());
        $whereWard = "";
        $whereOwner = "";
        $whereApplication = "";
        if (isset($data["ward_mstr_id"]) && isset($data["by_application_owner_dtl"]) && isset($data["keyword"])) {
            if ($data["ward_mstr_id"]!="") {
                $whereWard = " AND tbl_saf_dtl.ward_mstr_id=".$data["ward_mstr_id"];
            }
            if ($data["by_application_owner_dtl"]=="by_owner") {
                $whereOwner = " AND (owner_name ILIKE '%".$data["keyword"]."%' OR mobile_no::TEXT ILIKE '%".$data["keyword"]."%')";
            } else if ($data["by_application_owner_dtl"]=="by_application") {
                $whereApplication = " AND saf_no ILIKE '%".$data["keyword"]."%'";
            }
            $sql ="SELECT tbl_saf_dtl.id AS saf_dtl_id, view_ward_mstr.ward_no AS ward_no, tbl_saf_dtl.saf_no AS saf_no, saf_owner_detail.owner_name AS owner_name, saf_owner_detail.guardian_name AS guardian_name, saf_owner_detail.mobile_no AS mobile_no, property_type,
                    assessment_type, apply_date, prop_address, khata_no, plot_no
                    FROM tbl_saf_dtl
                    JOIN (
                            SELECT saf_dtl_id, STRING_AGG(owner_name, ',<br/>') AS owner_name, STRING_AGG(guardian_name::text, ',<br/>') AS guardian_name, STRING_AGG(mobile_no::text, ',<br/>') AS mobile_no 
                            FROM tbl_saf_owner_detail
                            WHERE status=1 ".$whereOwner."
                            GROUP BY saf_dtl_id
                        ) AS saf_owner_detail ON saf_owner_detail.saf_dtl_id=tbl_saf_dtl.id
                    JOIN view_ward_mstr ON view_ward_mstr.id=tbl_saf_dtl.ward_mstr_id ".$whereWard."
                    join tbl_prop_type_mstr on tbl_prop_type_mstr.id=tbl_saf_dtl.prop_type_mstr_id
                    WHERE tbl_saf_dtl.status=1 ".$whereApplication." order by tbl_saf_dtl.id desc";
            $result = $this->model_datatable->getDatatable($sql);
			
            $data['emp_details'] = $result['result'];
            $data['pager'] = $result['count'];
            $data['offset'] = $result['offset'];
        }
        $data['ward'] = $this->model_view_ward_permission->getPermittedWard($emp_details_id);
		return view('mobile/property/saf/saf_Property_List', $data);
	}
	
	public function saf_due_details($id)
	{
		$data =(array)null;
		$data['id']=$id;
		//print_r($data);
		$data['basic_details'] = $this->modelsaf->basic_details($data);
		if($owner_details = $this->modelowner->ownerdetails_md5($data['id']))
		{
			$data['owner_details'] = $owner_details;
		}
		if($tax_list = $this->modeltax->tax_list($data['basic_details']['saf_dtl_id']))
		{
			$data['tax_list'] = $tax_list;
		}
		if($demand_detail = $this->modeldemand->demand_detail($data))
		{
			$data['demand_detail'] = $demand_detail;
		}
		$data['safdate'] = $this->modelflr->floordate_dtl($data['basic_details']['saf_dtl_id']);
		if ($payment_detail = $this->modelpay->payment_detail($data['basic_details']['saf_dtl_id']))
		{
			$data['payment_detail'] = $payment_detail;
			$data['Paysafpayment'] = $payment_detail[0];
		}

		$data['SAFLevelPending']=$this->BO_SAF_Controller->SAFStatus(["id"=> $id]);
		//print_var($data['Paysafpayment']);
		// print_var($data);
		// return;		
		$geoTag = $this->db->query("select distinct (direction_type) as direction_type from tbl_saf_geotag_upload_dtl where geotag_dtl_id = ".$data['basic_details']['saf_dtl_id']." AND status =1 ")->getResultArray();
		$data["is_geo_tag_done"] = sizeof($geoTag)>=3 ? true : false;
		$this->cachePage(120);
		return view('mobile/property/saf/saf_due_details', $data);
	}
	
	public function saf_property_details($id=null)
	{
		$data =(array)null;
		$data['id']=$id;
		$data['SAFLevelPending']=$this->BO_SAF_Controller->SAFStatus(["id"=> $id]);
		$data['basic_details'] = $this->modelsaf->basic_details($data);
		$data['owner_details'] = $this->modelowner->ownerdetails_md5($data['id']);
		$data['tax_list'] = $this->modeltax->tax_list($data['basic_details']['saf_dtl_id']);
		$data['demand_detail'] = $this->modeldemand->demand_detail($data);
		$data['safdate'] = $this->modelflr->floordate_dtl($data['basic_details']['saf_dtl_id']);
		$data['occupancy_detail'] = $this->modelflr->occupancy_detail($data['basic_details']['saf_dtl_id']);

		$data['payment_detail'] = $this->modelpay->payment_detail_saf($data['basic_details']['saf_dtl_id']);
		$geoTag = $this->db->query("select distinct (direction_type) as direction_type from tbl_saf_geotag_upload_dtl where geotag_dtl_id = ".$data['basic_details']['saf_dtl_id']." AND status =1 ")->getResultArray();
		$data["is_geo_tag_done"] = sizeof($geoTag)>=3 ? true : false;
		$this->cachePage(120);
		return view('mobile/property/saf/saf_property_details', $data);
	}
	
	public function saf_payment_details($id=null)
	{
		$data =(array)null;
		$data['id']=$id;
		$data['SAFLevelPending']=$this->BO_SAF_Controller->SAFStatus(["id"=> $id]);
		$data['basic_details'] = $this->modelsaf->basic_details($data);
		$data['owner_details']  = $this->modelowner->ownerdetails_md5($data['id']);
		$data['tax_list'] = $this->modeltax->tax_list($data['basic_details']['saf_dtl_id']);
		$data['payment_details'] = $this->modelpay->payment_detail_saf($data['basic_details']['saf_dtl_id']);

		$this->cachePage(60);
		return view('mobile/property/saf/saf_payment_details', $data);
	}
	
	public function saf_confirm_payment($id)
	{
		$data =(array)null;
		$Session = Session();

		$ulb_mstr = $Session->get("ulb_dtl");
		$data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
		$emp_mstr = $Session->get("emp_details");
		$data['emp_details_id'] = $emp_mstr["id"];
		$data['emp_type_id'] = $emp_mstr["user_type_mstr_id"];
		$data['id']=$id;
		
		$data['basic_details'] = $this->modelsaf->basic_details($data);
		$data['saf_dtl_id']=$data['basic_details']['saf_dtl_id'];
		$data['owner_details'] = $this->modelowner->ownerdetails_md5($data['id']);
		$data['tax_list'] = $this->modeltax->tax_list($data['basic_details']['saf_dtl_id']);
		$data["fy_demand"] = $this->modeldemand->fydemand($data['basic_details']["saf_dtl_id"]);
		$geoTag = $this->db->query("select distinct (direction_type) as direction_type from tbl_saf_geotag_upload_dtl where geotag_dtl_id = ".$data['basic_details']['saf_dtl_id']." AND status =1 ")->getResultArray();
		$data["is_geo_tag_done"] = sizeof($geoTag)>=3 ? true : false;
		// print_Var(!($data["is_geo_tag_done"]??true));
		return view('mobile/Property/saf/saf_confirm_payment', $data);
			
		
	}

	// By Manas Sir
	public function saf_confirm_payment_old($id)
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
				$data['date'] = date('Y-m-d');
				$data["tran_by_type"] = "TC";
				
				$date1 = strtotime($data['date']);
				
				
				$data['bank_reCancel'] = $this->model_bank_recancilation->bank_reCancel($data['custm_id']);
				if($data['bank_reCancel']['amount']!=""){
					$bank_reCancel = $data['bank_reCancel']['amount'];
					
				}else{ 
					$bank_reCancel = 0;
				}
				$land_occupancy_date = $this->modelsaf->land_occupancy_date($data["custm_id"]);
				
				if($land_occupancy_date['prop_type_mstr_id']==4){
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
				}else{
					$data['safdate'] = $this->modelflr->floordate_dtl($data['custm_id']);
					$data['floorresdtl'] = $this->modelflr->floorresdtl($data['custm_id']);
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
					
					$date3 = strtotime($data['safdate']['date_from']);
					$year1 = date('Y', $date3);
					$year2 = date('Y', $date1);

					$month1 = date('m', $date3);
					$month2 = date('m', $date1);

					$diffmonths = (($year2 - $year1) * 12) + ($month2 - $month1);
					if($diffmonths>3){
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
				//print_r($tol_mnths);
				$data["total_qrt_pnlty"] = 0;
				$data["deman_am"] = 0;
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
				
				if($data['emp_type_id']==8){
					$data["total_pa_onjsk"] = ($data['total_pabl']/100)*2.5;
					$data["total_payabl"] = $data["total_pabl"] - $data["total_pa_onjsk"];
					//echo "inh";
				}else if($data['payment_mode']==4){
					$data["total_pa_onjsk"] = ($data['total_pabl']/100)*5;
					$data["total_payabl"] = $data["total_pabl"] - $data["total_pa_onjsk"];
					//echo "hjg";
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
					return $this->response->redirect(base_url('mobisafDemandPayment/saf_confirm_payment/'.md5($data['custm_id'])));
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
									if($data["tol_pent"]<0){
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
					
					if($data['total_pa_onjsk']>0){
						$data['head_name'] = "Rebet From Jsk/Online Payment";
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
						return $this->response->redirect(base_url('mobisafDemandPayment/saf_confirm_payment/'.md5($data['custm_id'])));
					}else{
						$this->db->transCommit();
						return $this->response->redirect(base_url('mobisafDemandPayment/saf_payment_receipt/'.md5($data['insertPayment'])));
					}
				}
				
			}
			else
			{
			$data['basic_details'] = $this->modelsaf->basic_details($data);
			if ( $owner_details = $this->modelowner->ownerdetails_md5($data['id'])) {
				$data['owner_details'] = $owner_details;
			}
			$data['tax_list'] = $this->modeltax->tax_list($data['basic_details']['saf_dtl_id']);
			$data['demand_detail'] = $this->modeldemand->demand_detail($data);
			if ( $fydemand = $this->modeldemand->fydemand($data['basic_details']['saf_dtl_id']) ) {
				$data['fydemand'] = $fydemand;
			}
			//print_r($data['fydemand']);
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
			if ( $payDetails = $this->modelpay->payDetails($data['basic_details']['saf_dtl_id']) ) {
				$data['payDetails'] = $payDetails;
			}
			
			
			$data['SAFLevelPending']=$this->BO_SAF_Controller->SAFStatus(["id"=> $id]);

			if ( $bank_reCancel = $this->model_bank_recancilation->bank_reCancel($data['basic_details']['saf_dtl_id']) ) {
				$data['bank_reCancel'] = $bank_reCancel;
			}
			if ( $difference_Penalty = $this->model_penalty_dtl->difference_Penalty($data['basic_details']['saf_dtl_id']) ) {
				$data['difference_Penalty'] = $difference_Penalty;
			}
			$data['tran_mode'] = $this->modeltran->getTranModeList();
			return view('mobile/Property/saf/saf_confirm_payment', $data);
			}
		}
	}
	
	public function saf_payment_receipt($tran_no=null)
	{
		$data =(array)null;
		$data['tran_no']=$tran_no;
		$ulb_mstr_id = $_SESSION['ulb_dtl']['ulb_mstr_id'];	
		$data['ulb_mstr_name'] = $this->modelUlb->getulb_list($ulb_mstr_id);
		$data['tran_mode_dtl'] = $this->modelpay->getTrandtlList($data['tran_no']);
		$data['fyFrom'] = $this->modelfy->getfyFromList($data['tran_mode_dtl']['from_fy_mstr_id']);
		$data['fyUpto'] = $this->modelfy->getfyUptoList($data['tran_mode_dtl']['upto_fy_mstr_id']);
		$data['payMode'] = $this->modeltran->getpayModeList($data['tran_mode_dtl']['tran_mode_mstr_id']);
		$data['holdingward'] = $this->modelsaf->getholdWard(md5($data['tran_mode_dtl']['prop_dtl_id']));
		$data['citizenName'] = $this->modelowner->citizenName(md5($data['tran_mode_dtl']['prop_dtl_id']));
		if($data['payMode']['id']==2 || $data['payMode']['id']==3){
			$data['chqDD_details'] = $this->modelchqDD->mode_dtl($data['tran_no']);
		}
		$data['emp_dtls'] = $this->modelemp->emp_dtls($data['tran_mode_dtl']['tran_by_emp_details_id']);
		//print_var($data);
		return view('mobile/Property/saf/saf_payment_receipt', $data);
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
		if($this->request->getMethod()=='post'){
			$data = [
					'fyUpto' => $this->request->getVar('due_upto_year'),
					'prop_no' => $this->request->getVar('custm_id')
					];
			$result = $this->modeldemand->gateQuarter($data);
			$lastquatr = $this->modeldemand->gateQuarterlast($data);
			$totalQuarter = $this->modeldemand->gatetotalQuarter($data);
			//$lasttotalQuarter = $this->modeldemand->gatelasttotalQuarter($data);
			if(!empty($result)){
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
		}else{
			$response = ['response'=>false];
		}
		echo json_encode($response);
        
    }
	
	

}
