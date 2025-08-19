<?php 
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\PostModel;
use App\Models\model_ward_mstr;
use App\Models\model_ulb_mstr;
use App\Models\model_fy_mstr;
use App\Models\model_govt_saf_dtl;
use App\Models\model_govt_saf_transaction_details;
use App\Models\model_bank_recancilation;
use App\Models\model_tran_mode_mstr;
use App\Models\model_govt_saf_transaction;
use App\Models\model_govt_saf_demand_dtl;
use App\Models\model_govt_saf_tax_dtl;
use App\Models\model_govt_saf_collection_dtl;
use App\Models\model_govt_saf_floor_dtl;
use App\Models\model_govt_saf_transaction_fine_rebet_details;
use App\Models\model_datatable;
use App\Models\model_saf_memo_dtl;
use App\Models\model_govt_saf_notice;
use Exception;

class govsafDetailPayment extends AlphaController
{
	protected $db;
	protected $dbSystem;
	protected $model_fy_mstr;
	protected $model_ward_mstr;
	protected $model_ulb_mstr;
	protected $model_govt_saf_dtl;
	protected $model_govt_saf_transaction_details;
	protected $model_bank_recancilation;
	protected $model_tran_mode_mstr;
	protected $model_govt_saf_transaction;
	protected $model_govt_saf_demand_dtl;
	protected $model_govt_saf_tax_dtl;
	protected $model_govt_saf_collection_dtl;
	protected $model_govt_saf_floor_dtl;
	protected $model_govt_saf_transaction_fine_rebet_details;
	protected $model_datatable;
	protected $model_saf_memo_dtl;

	protected $model_notice;
	protected $model;
	protected $modelfy;
	
	
	public function __construct()
    {
        parent::__construct();
    	helper(['db_helper', 'qr_code_generator_helper', 'utility_helper']);
        if($db_name = dbConfig("property")){
            $this->db = db_connect($db_name);            
        }

        if($db_system = dbSystem()){
            $this->dbSystem = db_connect($db_system); 
        }
		
        helper(['form']);
		//$db_name = db_connect("db_rmc_property");		
        $this->model = new model_ward_mstr($this->dbSystem);
		$this->model_ulb_mstr = new model_ulb_mstr($this->dbSystem);
		$this->modelfy = new model_fy_mstr($this->dbSystem);
		$this->model_govt_saf_dtl = new model_govt_saf_dtl($this->db);
		$this->model_tran_mode_mstr = new model_tran_mode_mstr($this->db);
		$this->model_bank_recancilation = new model_bank_recancilation($this->db);
		$this->model_govt_saf_transaction_details = new model_govt_saf_transaction_details($this->db);
		$this->model_govt_saf_transaction = new model_govt_saf_transaction($this->db);
		$this->model_govt_saf_demand_dtl = new model_govt_saf_demand_dtl($this->db);
		$this->model_govt_saf_tax_dtl = new model_govt_saf_tax_dtl($this->db);
		$this->model_govt_saf_collection_dtl = new model_govt_saf_collection_dtl($this->db);
		$this->model_govt_saf_floor_dtl = new model_govt_saf_floor_dtl($this->db);
		$this->model_govt_saf_transaction_fine_rebet_details = new model_govt_saf_transaction_fine_rebet_details($this->db);
		$this->model_datatable = new model_datatable($this->db);
		$this->model_saf_memo_dtl = new model_saf_memo_dtl($this->db);

		$this->model_notice = new model_govt_saf_notice($this->db);
    }
	
	public function gov_saf_application_details($id=null)
	{
		$data =(array)null;
		$Session= session();
		$data["ulb"] = $Session->get("ulb_dtl");
		$emp_mstr = $Session->get("emp_details");
		$data['user_type_mstr_id'] = $emp_mstr["user_type_mstr_id"];
		$data['id']=$id;
		//print_r($data);
		$data['appbasic_details'] = $this->model_govt_saf_dtl->appbasic_details($data['id']);
		$data['appbasic_details']['new_ward_no'] = $this->model_govt_saf_dtl->getWardDetail($data['appbasic_details']['id']);
		$data['paybasic_details'] = $this->model_govt_saf_dtl->paybasic_details($data['id']);
		
		$data['pymnt_detail'] = $this->model_govt_saf_transaction->payment_detail($data['appbasic_details']['id']);
		
		$data['tax_list'] = $this->model_govt_saf_tax_dtl->tax_list($data['appbasic_details']['id']);

		$data['floor_detail'] = $this->model_govt_saf_floor_dtl->appfloor_detail($data['appbasic_details']['id']);
		
		$data['Memo']=$this->model_saf_memo_dtl->getAllMemoByGovtSAFID(["govt_saf_dtl_id"=> $data['appbasic_details']["id"]]);
		
		return view('government/govsaf_application_details', $data);
	}

	public function gov_saf_due_details($id=null)
	{
		$data =(array)null;
		$Session= session();
		$data["ulb"] = $Session->get("ulb_dtl");
		$emp_mstr = $Session->get("emp_details");
		$data['user_type_mstr_id'] = $emp_mstr["user_type_mstr_id"];
		$data['id']=$id;
		$data['appbasic_details'] = $this->model_govt_saf_dtl->appbasic_details($data['id']);
		$data['paybasic_details'] = $this->model_govt_saf_dtl->paybasic_details($data['id']);
		$data['tax_list'] = $this->model_govt_saf_tax_dtl->tax_list($data['appbasic_details']['id']);
		$data['demand_detail'] = $this->model_govt_saf_demand_dtl->demand_detail($data['appbasic_details']['id']);
		
		return view('government/govsaf_due_details',$data);
	}

	public function govt_demand_receipt($govtSafDtlmd5Id=null) {
		if ($govtSafDtlmd5Id!=null) {
			$sql = "SELECT
						tbl_govt_saf_dtl.id,
						view_ward_mstr.ward_no,
						tbl_govt_saf_dtl.application_no,
						tbl_govt_saf_dtl.building_colony_name,
						tbl_govt_saf_dtl.building_colony_address,
						tbl_govt_saf_dtl.holding_no as new_holding_no,
						govt_tax_dtl.tax_dtl_temp,
						govt_demand_dtl.demand_dtl_temp,
						govt_demand_dtl.t_balance,
						govt_demand_dtl.t_additional_holding_tax,
						govt_demand_dtl.t_adjust_amount
					FROM tbl_govt_saf_dtl
					LEFT JOIN tbl_prop_dtl ON tbl_prop_dtl.govt_saf_dtl_id=tbl_govt_saf_dtl.id
					left JOIN view_ward_mstr ON view_ward_mstr.id=tbl_govt_saf_dtl.ward_mstr_id
					INNER JOIN (
						SELECT 
							govt_saf_dtl_id,
							json_agg(json_build_object('qtr', qtr, 'fy_mstr_id', fy_mstr_id, 'fyear', fyear, 'quarterly_tax', (holding_tax+water_tax+education_cess+health_cess+latrine_tax+additional_tax)) ORDER BY id ASC) AS tax_dtl_temp
						FROM tbl_govt_saf_tax_dtl
						GROUP BY govt_saf_dtl_id
					) AS govt_tax_dtl ON govt_tax_dtl.govt_saf_dtl_id=tbl_govt_saf_dtl.id
					LEFT JOIN (
						SELECT 
							govt_saf_dtl_id,
							json_agg(json_build_object('due_date', due_date, 'qtr', qtr, 'fy_mstr_id', fy_mstr_id, 'fyear', fyear, 'amount', amount, 'balance', balance, 'demand_amount', demand_amount, 'additional_holding_tax', additional_holding_tax, 'adjust_amount', adjust_amount) ORDER BY due_date ASC) AS demand_dtl_temp,
							SUM(balance) AS t_balance,
							SUM(additional_holding_tax) AS t_additional_holding_tax,
							SUM(adjust_amount) AS t_adjust_amount
						FROM tbl_govt_saf_demand_dtl
						WHERE status=1 AND paid_status=0
						GROUP BY govt_saf_dtl_id
					) AS govt_demand_dtl ON govt_demand_dtl.govt_saf_dtl_id=tbl_govt_saf_dtl.id
					WHERE 
						--tbl_govt_saf_dtl.id=421
						md5(tbl_govt_saf_dtl.id::TEXT)='".$govtSafDtlmd5Id."'";
			$result = $this->db->query($sql)->getFirstRow("array");
			$result["tax_dtl"] = json_decode($result["tax_dtl_temp"], true);
			$uptoIndex = count($result["tax_dtl"])-1;
			$result["quarterly_tax"] = $result["tax_dtl"][$uptoIndex]["quarterly_tax"];

			$result["demand_dtl"] = json_decode($result["demand_dtl_temp"], true);
			$result["demand_from_qtr"] = $result["demand_dtl"][0]["qtr"];
			$result["demand_from_fy"] = $result["demand_dtl"][0]["fyear"];
			$uptoIndex = count($result["demand_dtl"])-1;
			$result["demand_upto_qtr"] = $result["demand_dtl"][$uptoIndex]["qtr"];
			$result["demand_upto_fy"] = $result["demand_dtl"][$uptoIndex]["fyear"];
			$input = [
				'fy' => $result["demand_upto_fy"],
				'qtr' => $result["demand_upto_qtr"],
				'govt_saf_dtl_id' => $result["id"]
			];
			$result["payment_dtl"]=$this->model_govt_saf_demand_dtl->getGovtSAFDemandAmountDetails($input);
			//print_var($result);
			return view("property/gsaf/gbsaf_demand_receipt", $result);
		}
		
	}
	
	public function govt_proceedPayment($govt_saf_dtl_id_md5)
	{
		$data =(array)null;
		$Session= session();
		$data["ulb"] = $Session->get("ulb_dtl");
		$emp_mstr = $Session->get("emp_details");
		$data['user_type_mstr_id'] = $emp_mstr["user_type_mstr_id"];
		$data['govt_saf_dtl_id_md5']=$govt_saf_dtl_id_md5;
		$data['appbasic_details'] = $this->model_govt_saf_dtl->appbasic_details($data['govt_saf_dtl_id_md5']);
		$data['paybasic_details'] = $this->model_govt_saf_dtl->paybasic_details($data['govt_saf_dtl_id_md5']);
		$data["govt_saf_dtl_id"]=$data['appbasic_details']['id'];
		$data['tax_list'] = $this->model_govt_saf_tax_dtl->tax_list($data["govt_saf_dtl_id"]);
		$data['fy_demand'] = $this->model_govt_saf_demand_dtl->fydemand($data["govt_saf_dtl_id"]);
		
		return view('government/govt_proceedPayment',$data);
	}

	public function gov_saf_Property_Tax2()
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
			$where="1=1";
			if($data['ward_mstr_id']!="")
			{
				$where.=" and ward_mstr_id=".$data['ward_mstr_id'];
			}
			if($data['keyword']!="")
			{
				$where.=" and (application_no ilike '%".$data['keyword']."%' or building_colony_name ilike '%".$data['keyword']."%' or office_name ilike '%".$data['keyword']."%' or building_colony_address  ilike '%".$data['keyword']."%' or building_colony_address ilike '%".$data['keyword']."%')";
			}
			
			$session->set('where', $where);
			$session->set('keyword', $inputs['keyword']);
			$session->set('ward_mstr_id', $inputs['ward_mstr_id']);

			$data['ward'] = $this->model->getWardList(['ulb_mstr_id'=>$ulb_mstr_id]);
			
			return $this->response->redirect(base_url('govsafDetailPayment/gov_saf_search_Property/'));
		}
		else
		{
			$data['ward'] = $this->model->getWardList(['ulb_mstr_id'=> $ulb_mstr_id]);
			return view('government/govsaf_Property_List', $data);
		}
	}

	public function gov_saf_Property_Tax()
	{
		$ulb_mstr_id = $_SESSION['ulb_dtl']['ulb_mstr_id'];	
		$data =(array)null;

		$data = filterSanitizeStringtoUpper($this->request->getVar());

		$where=" 1=1";
		if (isset($data["by_app"]) && isset($data["ward_mstr_id"]) && isset($data["keyword"])) {
			if ($data["ward_mstr_id"]!="") {
				$where.=" and ward_mstr_id=".$data['ward_mstr_id'];
			}
			if ($data["keyword"]!="") {
				$where.=" and (application_no ilike '%".$data['keyword']."%' or building_colony_name ilike '%".$data['keyword']."%' or office_name ilike '%".$data['keyword']."%' or building_colony_address  ilike '%".$data['keyword']."%' or building_colony_address ilike '%".$data['keyword']."%')";
			}
			if (strtolower($data["by_app"])=="gbsaf") {
				 $sql="SELECT tbl_govt.id, tbl_govt.assessment_type, view_ward_mstr.ward_no, tbl_govt.application_no, tbl_govt.building_colony_name, tbl_govt.office_name, tbl_govt.building_colony_address, tbl_govt.application_type
					FROM tbl_govt_saf_dtl tbl_govt
					JOIN view_ward_mstr ON tbl_govt.ward_mstr_id = view_ward_mstr.id
					WHERE tbl_govt.status=1 and $where";
			} else if (strtolower($data["by_app"])=="csaf") {
				 $sql="SELECT tbl_govt.id, tbl_govt.assessment_type, view_ward_mstr.ward_no, tbl_govt.application_no, tbl_govt.building_colony_name, tbl_govt.office_name, tbl_govt.building_colony_address, tbl_govt.application_type
					FROM tbl_govt_saf_dtl tbl_govt
					JOIN view_ward_mstr ON tbl_govt.ward_mstr_id = view_ward_mstr.id
					WHERE tbl_govt.status=1 and $where"." AND tbl_govt.is_csaf2_generated=true";
			}
			$result = $this->model_datatable->getDatatable($sql);
			$data['govSaf_details'] = $result['result'];
			$data['pager'] = $result['count'];	
			$data['offset'] = $result['offset'];	
		}
		$data['ward'] = $this->model->getWardList(['ulb_mstr_id'=>$ulb_mstr_id]);
        return view('government/govsaf_Property_List2', $data);
	}
	
	public function gov_saf_search_Property() {
		$data =(array)null;
		$Session = session();
		$where = $Session->get('where');
		$ulb_mstr = $Session->get("ulb_dtl");
		$ulb_mstr_id = $ulb_mstr["ulb_mstr_id"];

        $sql="SELECT tbl_govt.id, tbl_govt.assessment_type, view_ward_mstr.ward_no, tbl_govt.application_no, tbl_govt.building_colony_name, tbl_govt.office_name, tbl_govt.building_colony_address, tbl_govt.application_type
				FROM tbl_govt_saf_dtl tbl_govt
				JOIN view_ward_mstr ON tbl_govt.ward_mstr_id = view_ward_mstr.id
				WHERE tbl_govt.status=1 and $where";
		$result = $this->model_datatable->getDatatable($sql);
		
		$govSaf_details = $result['result'];
		$data['govSaf_details'] = $govSaf_details;
		$data['pager'] = $result['count'];	
		$data['offset'] = $result['offset'];	
		
		$data['ward'] = $this->model->getWardList(['ulb_mstr_id'=> $ulb_mstr_id]);
		$data['keyword'] = $Session->get('keyword');
		$data['ward_mstr_id'] = $Session->get('ward_mstr_id');
		return view('government/govsaf_Property_List', $data);
	}
	
	
	
	public function govsaf_confirm_payment($id=null)
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
							'payblamnt' => $this->request->getVar('total_payabl_amnt'),
							'due_upto_year' => $this->request->getVar('due_upto_year'),
							'date_upto_qtr' => $this->request->getVar('date_upto_qtr'),
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
				
				$date1 = strtotime($data['date']);
				
				
				$data['bank_reCancel'] = $this->model_bank_recancilation->bank_reCancel($data['custm_id']);
				if($data['bank_reCancel']['amount']!=""){
					$bank_reCancel = $data['bank_reCancel']['amount'];
					
				}else{ 
					$bank_reCancel = 0;
				}
				
				// $data['basic_details'] = $this->model_govt_saf_dtl->basic_details($data['id']);
				$data['basic_details'] = $this->model_govt_saf_dtl->basic_details($id);
				
				$date3 = strtotime($data['basic_details']['apply_date']); 
				$date1 = strtotime(date('Y-m-d'));
				$year1 = date('Y', $date3);
				$year2 = date('Y', $date1);
				$month1 = date('m', $date3);
				$month2 = date('m', $date1);

				$diffmonths = (($year2 - $year1) * 12) + ($month2 - $month1);
				if($diffmonths>3){											
					if($data['basic_details']['prop_usage_type_mstr_id']==1){
						$data["latefine"]=5000;
					}elseif($data['basic_details']['prop_usage_type_mstr_id']==2){
						$data["latefine"]=2000;
					}else{
						$data["latefine"]=0;
					}
				}else{
					$data["latefine"]=0;
				}
				
				//print_var($data);die;
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
				$data['demand_amn'] = $this->model_govt_saf_demand_dtl->demand_amnt($data["custm_id"]);
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
				
				
				$crnt_dm_for_rdt=date('m');
				if($crnt_dm_for_rdt=='04' || $crnt_dm_for_rdt=='05' || $crnt_dm_for_rdt=='06'){
					$from_year = date("Y");
					$to_year = $from_year + 01;
					$fy = $from_year .'-'. $to_year;
					$data['fy_id'] = $this->modelfy->getFiidByfyyr($fy);
					if($data['date_upto_qtr']==4){
						$data['demand_rbt'] = $this->model_govt_saf_demand_dtl->demand_rebet($data["custm_id"],$data['fy_id']['id']);
						$rebate = ($data['demand_rbt']['sum']/100)*5;
					}
					else{ $rebate = 0; }
				}else{ $rebate = 0; }
				$data['rebate'] = $rebate;
				
				$data["total_payabl"] = ($data["deman_am"] + $data["latefine"] + $data['tol_pently'] + $bank_reCancel) - $data['rebate'];
				
				
				$round = round($data["total_payabl"]);
				$data["round_off"] = $round - $data["total_payabl"];
				
				$pable_amnt = $data["total_payabl"];
				
				
				$data['checkPayment'] = $this->model_govt_saf_transaction->checkPayment($data);
				
				if($data['checkPayment']){
					flashToast("govsaf_confirm_payment", "Same Day More Than One Time Payment Cann't Done!!!");
					return $this->response->redirect(base_url('govsafDetailPayment/govsaf_confirm_payment/'.md5($data['custm_id'])));
				}else{
					
					$data['insertPayment'] = $this->model_govt_saf_transaction->govsafinsertPayment($data);
			
					if($data['insertPayment']){
						if($data["payment_mode"]=='2' || $data["payment_mode"]=='3'){
							$chqDDdetails = $this->model_govt_saf_transaction_details->chqDDdetails($data);
							//die();
						}
						
						$data['paidsts'] = $this->model_govt_saf_dtl->paidsts($data);
						
						$data['demand_amnt'] = $this->model_govt_saf_demand_dtl->demand_amnt($data["custm_id"]);
						
							
						$data['demand_id'] = $this->model_govt_saf_demand_dtl->demand_id($data);
						$data['tol_pent'] = 0;
						$dif_qtr = 0;
						$data['balnc'] = $data['payblamnt'];
						for($i=1;$i<=$data['total_qrt'];$i++){
							$data['resultid'] = $data['demand_id'][$i-1];
							$dem_am = $data['demand_amn'][$i-1]["balance"];
							
							if($data['balnc']>0)
							{
							 
								$dif_qtr = $dif_qtr + 3;
								$dem_fyids = $data['demand_amn'][$i-1]["fy_mstr_id"];
								if($dem_fyids>=49)
								{
									if($tol_mnth>=3)
									{
										$each_penlty = ($dem_am/100)*($tol_mnths-$dif_qtr);
										if($each_penlty>0)
										{
											$data['tol_pent'] = $each_penlty;
											$data['mnth'] = $tol_mnths-$dif_qtr;
										}
										else 
										{ 
											$data['tol_pent'] = $data['tol_pent']; 
											$data['mnth']=0;
										}
									}
									else 
									{ 
										$data['tol_pent'] = $data['tol_pent']; 
										$data['mnth']=0;
									}
								}
								else 
								{ 
									$data['tol_pent'] = $data['tol_pent']; 
									$data['mnth']=0;
								}
								
								if($data['balnc']>=$dem_am){
									$payBalnc = $data['balnc'] - $dem_am;
									$data['dmblnc'] = 0;
									$data['paidStatus'] = 1;
									$data['balnc'] = $payBalnc;
								}else if($data['balnc']<$dem_am && $data['balnc']!= 0){
									$payBalnc = $dem_am - $data['balnc'];
									$data['dmblnc'] = $payBalnc;
									$data['paidStatus'] = 0;
									$data['balnc'] = 0;
								}
								if($data['mnth']>0){
									$data['pntmnth'] = $data['mnth'];
								}else{
									$data['pntmnth'] = 0;
								}
								
								$data["date_cls"]= date("Y-m-d H:i:s");
								$data['updatedemandPayment'] = $this->model_govt_saf_demand_dtl->updatedemandPayment($data);
								
								$data['demandCollection'] = $this->model_govt_saf_collection_dtl->demandCollection($data);
								
							}
						}
							
						
						
						if($data['rebate']>0){
							$data['head_name'] = "First Quartare Discount";
							$data['fine_rebet_amount'] = $data['rebate'];
							$data['add_minus'] = "Minus";
							
							$data['fine_rebet_details'] = $this->model_govt_saf_transaction_fine_rebet_details->fine_rebet_details($data);
						}
						if($data['latefine']>0){
							$data['head_name'] = "Late Assessment Fine(Rule 14.1)";
							$data['fine_rebet_amount'] = $data['latefine'];
							$data['add_minus'] = "Add";
							
							$data['fine_rebet_details'] = $this->model_govt_saf_transaction_fine_rebet_details->fine_rebet_details($data);
						}
						if($data['tol_pently']>0){
							$data['head_name'] = "1% Penalty On Demand Amount";
							$data['fine_rebet_amount'] = $data['tol_pently'];
							$data['add_minus'] = "Add";
							
							$data['fine_rebet_details'] = $this->model_govt_saf_transaction_fine_rebet_details->fine_rebet_details($data);
						}
						
						
						if($bank_reCancel>0){
							$data['chequePaymentDone'] = $this->model_bank_recancilation->chequePaymentDone($data);
						}
				
						
					}
				}
				
				if($this->db->transStatus() === FALSE){
					$this->db->transRollback();
					flashToast("govsaf_confirm_payment", "Something error due to payment!!!");
					return $this->response->redirect(base_url('govsafDetailPayment/govsaf_confirm_payment/'.md5($data['custm_id'])));
				}else{
					$this->db->transCommit();
					return $this->response->redirect(base_url('govsafDetailPayment/gov_saf_pymnt_details/'.md5($data['custm_id'])));
				}
				
			}
		
			else
			{
				$data['basic_details'] = $this->model_govt_saf_dtl->basic_details($data['id']);
				$data['tax_list'] = $this->model_govt_saf_tax_dtl->tax_list($data['basic_details']['id']);
				$data['payment_detail']  = $this->model_govt_saf_transaction->payment_detail($data['basic_details']['id']);

				$data['demand_detail'] = $this->model_govt_saf_demand_dtl->demand_detail($data['basic_details']['id']);
				if ( $fydemand = $this->model_govt_saf_demand_dtl->fydemand($data['basic_details']['id']) ) {
					$data['fydemand'] = $fydemand;
				}
				//print_r($data['demand_detail']);
				$mnth = date("m");
				$from_year = date("Y");
				$to_year = $from_year + 01;
				if($mnth>=4 || $mnth<4){
					$fy = $from_year .'-'. $to_year;
					$data['fy_id'] = $this->modelfy->getFiidByfyyr($fy);
				}
				
				$date3 = strtotime($data['basic_details']['apply_date']); 
				$date1 = strtotime(date('Y-m-d'));
				$year1 = date('Y', $date3);
				$year2 = date('Y', $date1);
				$month1 = date('m', $date3);
				$month2 = date('m', $date1);

				$diffmonths = (($year2 - $year1) * 12) + ($month2 - $month1);
				if($diffmonths>3){											
					if($data['basic_details']['prop_usage_type_mstr_id']==1){
						$data["latefine"]=5000;
					}else{
						$data["latefine"]=2000;
					}
				}
				
				
				$data['bank_reCancel'] = $this->model_bank_recancilation->bank_reCancel($data['basic_details']['id']);
				
				$data['tran_mode'] = $this->model_tran_mode_mstr->getTranModeList();
				//print_var($data);
				return view('government/govsaf_confirm_payment',$data);
			}
		}
	}
	
	
	
	public function gov_saf_pymnt_details($id=null)
	{
		$data =(array)null;
		$data['id']=$id;
		//print_r($data);
		$data['paybasic_details'] = $this->model_govt_saf_dtl->paybasic_details($data['id']);
		if ( $pymnt_detail = $this->model_govt_saf_transaction->payment_detail($data['paybasic_details']['id'])) {
			$data['pymnt_detail'] = $pymnt_detail;
		}
		//print_r($data);
		return view('government/govsaf_payment_details',$data);
	}
	
	
	
	
	
	public function Ajax_getQtr()
	{
		$response = ['response'=> false];
		if($this->request->getMethod()=='post')
		{
			$data = [
					'fy_mstr_id' => $this->request->getVar('fy_mstr_id'),
					'govt_saf_dtl_id' => $this->request->getVar('govt_saf_dtl_id'),
					];
			$result = $this->model_govt_saf_demand_dtl->getDistinctQtr($data);
			$option=null;
			if(!empty($result))
			{
				foreach($result as $value)
				{
					$option .= '<option value="'.$value['qtr'].'">'.$value['qtr'].'</option>';
				}
			}
			$response = ['response'=> true, 'data'=> $option];
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
					'govt_saf_dtl_id' => $this->request->getVar('govt_saf_dtl_id')
					];
			$data=$this->model_govt_saf_demand_dtl->getGovtSAFDemandAmountDetails($input);
			
			$out='<tr>
						<td class="pull-right">Demand Amount</td>
						<td>'.$data['DemandAmount'].'</td>
						<td class="pull-right">Rebate</td>
						<td>'.$data['RebateAmount'].'</td>
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
				
			// $out.='<tr>
			// 			<td class="pull-right">Advance</td>
			// 			<td>'.$data['AdvanceAmount'].'</td>
			// 			<td class="pull-right text-success">Total Paybale Amount</td>
			// 			<td class="text-success" id="total_payable_amount_temp">'.$data['PayableAmount'].'</td>
			// 		</tr>';

			$out.=' <tr>
					<td class="pull-right">	
						<i class="fa fa-info-circle" data-placement="bottom" data-toggle="modal" data-target="#forward_backward_model" title="Penalty Calculation Rule">
						</i> 							
						Notice Penalty 
					</td>
					<td>' . $data['noticePenalty'] . '</td>
					<td class="pull-right">2% Addition Penalty</td>
					<td>' . $data['noticePenaltyTwoPer'] . '</td>
					
				</tr>
				<tr>
					<td class="pull-right">Advance</td>
					<td>'.$data['AdvanceAmount'].'</td>
					<td class="pull-right text-success">Total Paybale Amount</td>
					<td class="text-success" id="total_payable_amount_temp">'.$data['PayableAmount'].'</td>
				</tr>';

			$model='
				<div style="font-size:small;">
					<!-- models -->
					
					<!-- ==================== -->
					<div id="forward_backward_model" class="modal fade" role="dialog" >
						<div class="modal-dialog text-center" style=" width:60%">
							<!-- Modal content-->
							<div class="modal-content">
								<div class="modal-header" style="background-color: #25476a;">
									<button type="button" class="close" style="color: white; font-size:30px;" data-dismiss="modal">&times;</button>
									<h4 class="modal-title" style="color: white;text-align:center;"><span id="action_title"> Notice Penalty Calculation</span> </h4>
								</div>
								
								<div class="modal-body">                               
									<div class="row">
										<div class="col-md-12 has-success pad-btm">
											<a class="btn btn-primary btn-sm pull-right" href="'.base_url("/public/assets/Rules/property_notice_rule.pdf").'" target="blank">Download</a>
											<br>
											<img style="width:100%; height:100%;" src="'.base_url("/public/assets/Rules/property_notice_rule.png").'"/>
										</div>
										<div class="col-md-12 has-success pad-btm">
											
											<table class="tbl tbl-border" style="width:100%;">
												<tr>
													<td class="pull-right">Arrear Demand : </td>
													<td> <strong>' . $data["noticePenaltyBifurcation"]["ArrearDemand"] . '</strong></td>
													<td class="pull-right">	1 % Interest : </td>
													<td> <strong>' . $data["noticePenaltyBifurcation"]['OnePercentPnalty'] . '</strong> </td>
												</tr>
												<tr>
													<td class="pull-right">Notice Receiving Date</td>
													<td> <strong>' . $data["noticePenaltyBifurcation"]["NoticeServedDate"] . '</strong></td>
													<td class="pull-right">Notice Date : </td>
													<td> <strong>' . $data["noticePenaltyBifurcation"]["NoticeDate"] . '</strong></td>
													
												</tr>
												<tr>
													<td class="pull-right">Day Diff : </td>
													<td> <strong>' . $data["noticePenaltyBifurcation"]["DayDiff"] . '</strong></td>
													<td class="pull-right">	Notice Penalty Apply : </td>
													<td> <strong>' . (($data["noticePenaltyBifurcation"]['noticePer']??0)*100) . ' % </strong></td>
												</tr>
												<tr>
													<td class="pull-right">	Notice Penalty : </td>
													<td> <strong>' . $data["noticePenaltyBifurcation"]['noticePenalty'] . '</strong></td>																
													<td class="pull-right"> 2% Addition Penalty	 : </td>
													<td><strong>' . $data["noticePenaltyBifurcation"]['noticePenaltyTwoPer'] . '</strong></td>	
												</tr>
											<table>
										</div>                                    
									</div>
									<div class="row">
										<div class="col-md-4">
										</div>
										<div class="col-md-4">
											
										</div>                                    
									</div>
								</div>

							</div>
						</div>
					</div>
					<!-- =================== -->
					
					<!-- end models -->
				</div>			
			';
			$response = ['response'=> true, 'data'=> $data, 'html_data'=> $out,"html_model_data"=>$model];
			// $response = ['response'=> true, 'data'=> $data, 'html_data'=> $out];
		}
		echo json_encode($response);
	}
	

	public function Ajax_govt_saf_pay_now()
	{
		
		$Session = Session();
		$emp_mstr = $Session->get("emp_details");
		
		$user_id = $emp_mstr["id"];

		$response = ['response'=> false];
		$inputs = filterSanitizeStringtoUpper($this->request->getVar());
		
		if($this->request->getMethod()=='post')
		{
			$cheque_dtl=(array)null;
			$data=[
				"govt_saf_dtl_id"=> $inputs["govt_saf_dtl_id"],
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
			$trxn_id=$this->model_govt_saf_demand_dtl->govt_saf_pay_now($data, $cheque_dtl);
			//$this->db->transRollback();
			
			
			
			$redirect_to=base_url()."/govsafDetailPayment/govsaf_payment_receipt/".md5($trxn_id);

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

	public function govsaf_payment_receipt($trxn_id_md5=null)
	{
		$data =(array)null;
		$Session= Session();
		$data["ulb_mstr_name"] = $Session->get("ulb_dtl");
		$ulb_mstr_id = $data["ulb_mstr_name"]["ulb_mstr_id"];

		$path=base_url('citizenPaymentReceipt/govsaf_payment_receipt/'.$ulb_mstr_id.'/'.$trxn_id_md5);
		$data['ss']=qrCodeGeneratorFun($path);
		$data['trxn_id_md5']=$trxn_id_md5;
		$data['tran_mode_dtl'] = $this->model_govt_saf_transaction->getTrandtlList($data['trxn_id_md5']);
		$data['coll_dtl'] = $this->model_govt_saf_collection_dtl->collection_dtl($data['tran_mode_dtl']['id']);

		$data['basic_details'] = $this->model_govt_saf_dtl->basic_details(md5($data['tran_mode_dtl']['govt_saf_dtl_id']));
	
		if(strtoupper($data['tran_mode_dtl']['tran_mode']) != 'CASH'){
			$data['chqDD_details'] = $this->model_govt_saf_transaction_details->mode_dtl($data['trxn_id_md5']);
		}

		$data['penalty_dtl'] = $this->model_govt_saf_transaction_fine_rebet_details->penalty_dtl($data['tran_mode_dtl']['id'], $data['tran_mode_dtl']['govt_saf_dtl_id']);
		return view('government/govsaf_payment_receipt', $data);
	}

	public function govsaf_payment_receipt2($trxn_id_md5=null)
	{
		$data =(array)null;
		$Session= Session();
		$data["ulb_mstr_name"] = $Session->get("ulb_dtl");
		$ulb_mstr_id = $data["ulb_mstr_name"]["ulb_mstr_id"];

		$path=base_url('citizenPaymentReceipt/govsaf_payment_receipt/'.$ulb_mstr_id.'/'.$trxn_id_md5);
		$data['ss']=qrCodeGeneratorFun($path);
		$data['trxn_id_md5']=$trxn_id_md5;
		$data['tran_mode_dtl'] = $this->model_govt_saf_transaction->getTrandtlList($data['trxn_id_md5']);
		//print_var($data['tran_mode_dtl']);
		$data['coll_demand_dtl'] = $this->model_govt_saf_collection_dtl->collection_demand_dtl($data['tran_mode_dtl']['id']);
		$data['coll_dtl'] = $this->model_govt_saf_collection_dtl->sum_collection_dtl($data['tran_mode_dtl']['id']);

		$data['basic_details'] = $this->model_govt_saf_dtl->basic_details(md5($data['tran_mode_dtl']['govt_saf_dtl_id']));
	
		if(strtoupper($data['tran_mode_dtl']['tran_mode']) != 'CASH'){
			$data['chqDD_details'] = $this->model_govt_saf_transaction_details->mode_dtl($data['trxn_id_md5']);
		}

		$data['penalty_dtl'] = $this->model_govt_saf_transaction_fine_rebet_details->penalty_dtl($data['tran_mode_dtl']['id'], $data['tran_mode_dtl']['govt_saf_dtl_id']);
		
		if ($already_paid = $this->model_govt_saf_transaction_fine_rebet_details->already_paid($data['tran_mode_dtl']['id'], $data['tran_mode_dtl']['govt_saf_dtl_id'])) {
			$data['already_paid'] = $already_paid["already_paid"];
		}
		//print_var($data);

		return view('government/govsaf_payment_receipt2', $data);
	}

	public function govSafList_old(){
		$ulb_mstr_id = $_SESSION['ulb_dtl']['ulb_mstr_id'];
		$data = $this->request->getVar();

		$where=" and 1=1";
		$sql="SELECT tbl_govt.id, tbl_govt.assessment_type, 
				view_ward_mstr.ward_no,new_ward.ward_no as new_ward_no, 
				tbl_govt.application_no, tbl_govt.building_colony_name, tbl_govt.office_name, 
				tbl_govt.building_colony_address, tbl_govt.application_type,
				tbl_govt_building_type_master.building_type , 
				CASE WHEN govt_building_type_mstr_id in(3,4) THEN 'CENTRAL GOVERNMENT' ELSE 'STATE GOVERNMENT' END AS gov_type,
				demand.balance
			FROM tbl_govt_saf_dtl tbl_govt
			JOIN view_ward_mstr ON tbl_govt.ward_mstr_id = view_ward_mstr.id
			LEFT JOIN view_ward_mstr AS new_ward ON tbl_govt.new_ward_mstr_id = new_ward.id
			LEFT JOIN tbl_govt_building_type_master on tbl_govt_building_type_master.id = tbl_govt.govt_building_type_mstr_id
			LEFT JOIN (
				SELECT sum(balance) as balance,govt_saf_dtl_id
				FROM tbl_govt_saf_demand_dtl tb1
				left join view_fy_mstr tb2 on tb2.id= tb1.fy_mstr_id
				where paid_status=0 AND tb1.status=1 
				GROUP by govt_saf_dtl_id
			)demand on demand.govt_saf_dtl_id = tbl_govt.id
			WHERE tbl_govt.status=1
		";
		if (isset($data["by_app"]) && isset($data["ward_mstr_id"]) && isset($data["keyword"]) && isset($data["government_type"])) {
			if ($data["ward_mstr_id"]!="") {
				$where.=" and ward_mstr_id=".$data['ward_mstr_id'];
			}
			if (($data["government_type"]??"")!="") {
				if($data["government_type"]=="CENTRAL GOVERNMENT"){
					$where.=" and govt_building_type_mstr_id in(3,4) ";
				}elseif($data["government_type"]=="STATE GOVERNMENT"){
					$where.=" and govt_building_type_mstr_id not in(3,4) ";
				}
			}
			if ($data["keyword"]!="") {
				$where.=" and (application_no ilike '%".$data['keyword']."%' or building_colony_name ilike '%".$data['keyword']."%' or office_name ilike '%".$data['keyword']."%' or building_colony_address  ilike '%".$data['keyword']."%' or building_colony_address ilike '%".$data['keyword']."%')";
			}
			if (strtolower($data["by_app"])=="gbsaf") {
				 
			} else if (strtolower($data["by_app"])=="csaf") {
				 $where.= " AND tbl_govt.is_csaf2_generated=true";
			}
			$sql = $sql.$where." ORDER BY (substring(view_ward_mstr.ward_no, '^[0-9]+'))::int,view_ward_mstr.ward_no";
			$result = $this->db->query($sql)->getResultArray();
			$data['govSaf_details'] = $result;	
		}
		$data['ward'] = $this->model->getWardList(['ulb_mstr_id'=>$ulb_mstr_id]);
		return view("government/govSafList",$data);
	}

	public function govSafList(){
		$ulb_mstr_id = $_SESSION['ulb_dtl']['ulb_mstr_id'];
		$data = $this->request->getVar();
		$currentFyear=getFY();
		list($fromYear,$uptoYear)=explode("-",$currentFyear);
		$fromDate = $fromYear."-04-01";
		$uptoDate = $uptoYear."-03-31";
		$data["fyear"]=$currentFyear;
	
		$where=" and 1=1";
		$sql="SELECT tbl_govt.id, tbl_govt.assessment_type, 
				tbl_govt.holding_no, CASE WHEN tbl_govt.prop_dtl_id is not null tHEN 'Yes' ELSE 'No' END AS is_holding_assessed,
				view_ward_mstr.ward_no,new_ward.ward_no as new_ward_no, 
				tbl_govt.application_no, tbl_govt.building_colony_name, tbl_govt.office_name, 
				tbl_govt.building_colony_address, tbl_govt.application_type,
				tbl_govt_building_type_master.building_type , 
				CASE WHEN govt_building_type_mstr_id in(3,4) THEN 'CENTRAL GOVERNMENT' ELSE 'STATE GOVERNMENT' END AS gov_type,
				demand.balance,(COALESCE(demand.arrear,0) + COALESCE(col.arrear_collect,0)) as arrear , (COALESCE(demand.current,0) + COALESCE(col.current_collect,0)) as current,
				col.current_year_collect,col.current_collect,col.arrear_collect,
				owner.owner_name,owner.mobile_no,
				CASE WHEN tbl_gbsaf_demand_reports.is_demand_served=true THEN 'Yes'
					WHEN tbl_gbsaf_demand_reports.is_demand_served=false THEN 'No'
					ELSE null end as  is_demand_served ,
				tbl_gbsaf_demand_reports.last_demand_served_date ,
				CASE WHEN tbl_gbsaf_demand_reports.is_demand_notice_served=true THEN 'Yes'
					WHEN tbl_gbsaf_demand_reports.is_demand_notice_served=false THEN 'No'
					ELSE null end as is_demand_notice_served,
				tbl_gbsaf_demand_reports.last_demand_notice_served_date ,
				CASE WHEN tbl_gbsaf_demand_reports.is_payment_received=true THEN 'Yes'
					WHEN tbl_gbsaf_demand_reports.is_payment_received=false THEN 'No'
					ELSE null end as is_payment_received,
				tbl_gbsaf_demand_reports.upload_receipt_info ,
				tbl_gbsaf_demand_reports.notice_path ,
				tbl_gbsaf_demand_reports.contact_person ,
				tbl_gbsaf_demand_reports.contact_no,
				(select count(id) from tbl_gbsaf_demand_reports where govt_saf_dtl_id = tbl_govt.id ) as total_notice
			FROM tbl_govt_saf_dtl tbl_govt
			JOIN view_ward_mstr ON tbl_govt.ward_mstr_id = view_ward_mstr.id
			LEFT JOIN tbl_gbsaf_demand_reports on tbl_gbsaf_demand_reports.govt_saf_dtl_id = tbl_govt.id and tbl_gbsaf_demand_reports.status=1
			LEFT JOIN view_ward_mstr AS new_ward ON tbl_govt.new_ward_mstr_id = new_ward.id
			LEFT JOIN tbl_govt_building_type_master on tbl_govt_building_type_master.id = tbl_govt.govt_building_type_mstr_id
			LEFT JOIN (
				SELECT sum(balance) as balance,
					SUM(CASE WHEN tb1.fyear<'$currentFyear' THEN balance END) AS arrear,
					SUM(CASE WHEN tb1.fyear='$currentFyear' THEN balance END) AS current,
					govt_saf_dtl_id
				FROM tbl_govt_saf_demand_dtl tb1
				left join view_fy_mstr tb2 on tb2.id= tb1.fy_mstr_id
				where paid_status=0 AND tb1.status=1 
				GROUP by govt_saf_dtl_id
			)demand on demand.govt_saf_dtl_id = tbl_govt.id
			LEFT JOIN (
				SELECT SUM(tbl_govt_saf_collection_dtl.amount) as current_year_collect,
						SUM(CASE WHEN fyear='$currentFyear' then tbl_govt_saf_collection_dtl.amount end) as current_collect,
						SUM(CASE WHEN fyear<'$currentFyear' then tbl_govt_saf_collection_dtl.amount end) as arrear_collect,
					tbl_govt_saf_transaction.govt_saf_dtl_id
				FROM tbl_govt_saf_collection_dtl
				JOIN tbl_govt_saf_transaction ON tbl_govt_saf_transaction.id = tbl_govt_saf_collection_dtl.govt_saf_transaction_id
				WHERE tbl_govt_saf_transaction.status in(1,2) AND tbl_govt_saf_collection_dtl.status =1 
					AND tbl_govt_saf_transaction.tran_date BETWEEN '$fromDate' and '$uptoDate'
				GROUP BY tbl_govt_saf_transaction.govt_saf_dtl_id
			) AS col on col.govt_saf_dtl_id = tbl_govt.id
			LEFT JOIN(
				select govt_saf_dtl_id,string_agg(officer_name,',') as owner_name,string_agg(mobile_no,',') as mobile_no
				from tbl_govt_saf_officer_dtl
				WHERE status=1
				GROUP BY govt_saf_dtl_id
			) as owner on owner.govt_saf_dtl_id = tbl_govt.id
			
			WHERE tbl_govt.status=1
		";
		if (isset($data["by_app"]) && isset($data["ward_mstr_id"]) && isset($data["keyword"]) && isset($data["government_type"])) {
			if ($data["ward_mstr_id"]!="") {
				$where.=" and ward_mstr_id=".$data['ward_mstr_id'];
			}
			if (($data["government_type"]??"")!="") {
				if($data["government_type"]=="CENTRAL GOVERNMENT"){
					$where.=" and govt_building_type_mstr_id in(3,4) ";
				}elseif($data["government_type"]=="STATE GOVERNMENT"){
					$where.=" and govt_building_type_mstr_id not in(3,4) ";
				}
			}
			if ($data["keyword"]!="") {
				$where.=" and (application_no ilike '%".$data['keyword']."%' or building_colony_name ilike '%".$data['keyword']."%' or office_name ilike '%".$data['keyword']."%' or building_colony_address  ilike '%".$data['keyword']."%' or building_colony_address ilike '%".$data['keyword']."%')";
			}
			if (strtolower($data["by_app"])=="gbsaf") {
				 
			} else if (strtolower($data["by_app"])=="csaf") {
				 $where.= " AND tbl_govt.is_csaf2_generated=true";
			}
			$sql = $sql.$where." ORDER BY (substring(view_ward_mstr.ward_no, '^[0-9]+'))::int,view_ward_mstr.ward_no";
	
			// print_var($sql);
	
			$result = $this->db->query($sql)->getResultArray();
			$data['govSaf_details'] = $result;	
		}
		$data['ward'] = $this->model->getWardList(['ulb_mstr_id'=>$ulb_mstr_id]);
		if(isset($data["pm"]) && $data["pm"]){
			return view("government/govSafListDemandStatus",$data);
		}
		return view("government/govSafList",$data);
	}
	
	public function serchGbSafDemandReport(){
		$data=$this->request->getVar();
		if($this->request->getMethod()=="post"){
			if(isset($data["search"]) && $data["gbsafNo"]??false){
				$sql="  SELECT tbl_gbsaf_demand_reports.*,tbl_govt.*, view_ward_mstr.ward_no,owner.owner_name,owner.mobile_no
						FROM tbl_govt_saf_dtl tbl_govt
						JOIN view_ward_mstr ON tbl_govt.ward_mstr_id = view_ward_mstr.id
						LEFT JOIN tbl_gbsaf_demand_reports on tbl_gbsaf_demand_reports.govt_saf_dtl_id = tbl_govt.id and tbl_gbsaf_demand_reports.status=1
						LEFT JOIN(
							select govt_saf_dtl_id,string_agg(officer_name,',') as owner_name,string_agg(mobile_no,',') as mobile_no
							from tbl_govt_saf_officer_dtl
							WHERE status=1
							GROUP BY govt_saf_dtl_id
						) as owner on owner.govt_saf_dtl_id = tbl_govt.id
						where tbl_govt.status=1
							and tbl_govt.application_no ilike '".trim($data["gbsafNo"])."'
					";
				$data["gbSaf"]=$this->db->query($sql)->getFirstRow("array");
			}
			elseif(isset($data["submit"])){
				$response = json_decode($this->updateGBSafDemandReports(),true);
				if($response["status"]){
					flashToast("message","Data Store");
					return redirect()->to(base_url("govsafDetailPayment/serchGbSafDemandReport"));
				}
				flashToast("message","Data Not Store");
				return redirect()->back();
			}
			
		}
		return view("government/serchGbSafDemandReport",$data);
	}
	
	public function updateGBSafDemandReports(){
		$response=["status"=>false,"message"=>"Server Error"];
		try{
			$Session = Session();
			$emp_details = $Session->get("emp_details");
			$input = $this->request->getVar();
			$file= $this->request->getFile("upload_receipt_info") ;
			$notice= $this->request->getFile("notice") ;
			$inputData=[
				"govt_saf_dtl_id"=>$input["id"],
				"contact_person"=>isset($input["contact_person"])?$input["contact_person"]:null,
				"contact_no"=>isset($input["contact_no"])?$input["contact_no"]:null,
				"is_demand_served"=>isset($input["is_demand_served"]) ? ($input["is_demand_served"]=="Yes"?true:false):null,
				"last_demand_served_date"=>isset($input["last_demand_served_date"]) && $input["last_demand_served_date"] ? ($input["last_demand_served_date"]):null,
				"is_demand_notice_served"=>isset($input["is_demand_notice_served"]) ? ($input["is_demand_notice_served"]=="Yes"?true:false):null,
				"last_demand_notice_served_date"=>isset($input["last_demand_notice_served_date"]) && $input["last_demand_notice_served_date"] ? ($input["last_demand_notice_served_date"]):null,
				"is_payment_received"=>isset($input["is_payment_received"]) ? ($input["is_payment_received"]=="Yes"?true:false):null,
				"user_id"=>$emp_details["id"],
			];
			if($file && $file->IsValid() && !$file->hasMoved()){
				$file_ext = $file->getExtension();
				$ulb_dtl = $Session->get('ulb_dtl');
				$city = $ulb_dtl['city'];
				$newFileNamee = $inputData["govt_saf_dtl_id"]."_".$emp_details["id"].".".$file_ext;
				$path = "$city/GbSafDemandStatus/";
				if ($file->move(WRITEPATH . "uploads/".$path, $newFileNamee )) {
					$doc_path = $path."/".$newFileNamee;
					$inputData["upload_receipt_info"]=$doc_path;
				}
	
			}
			if($notice && $notice->IsValid() && !$notice->hasMoved()){
				$file_ext = $notice->getExtension();
				$ulb_dtl = $Session->get('ulb_dtl');
				$city = $ulb_dtl['city'];
				$newFileNamee = $inputData["govt_saf_dtl_id"]."_".$emp_details["id"]."_notice.".$file_ext;
				$path = "$city/GbSafDemandStatus/";
				if ($notice->move(WRITEPATH . "uploads/".$path, $newFileNamee )) {
					$doc_path = $path."/".$newFileNamee;
					$inputData["notice_path"]=$doc_path;
				}
	
			}
			$this->db->transBegin();			
			$this->db->table("tbl_gbsaf_demand_reports")->where("govt_saf_dtl_id",$input["id"])->update(["status"=>0]);
			$this->db->table("tbl_gbsaf_demand_reports")->insert($inputData);
			
			if($this->db->transStatus() === TRUE){
				$this->db->transCommit();
				$response["status"]=true;
				$response["message"]="Data Saved";
			}else{
				$this->db->transRollback();
				$response["status"]=true;
				$response["message"]="Data Not Saved";
			}
			return json_encode($response);
		}catch(Exception $e){
			return json_encode($response);
		}
	}
	
	public function importExcel()
	{
		try {
			$session = session();
			$emp_details = $session->get("emp_details");
			$ulb_dtl = $session->get("ulb_dtl");
			$city = $ulb_dtl['city'];
	
			// Input variables
			$inputData = $this->request->getVar(); 
			$file = $this->request->getFile('file'); // Match the `name` from form input
			$ext = $file->getExtension();
			$ufileName = $inputData["fileName"] ?? "unknown";
			$fileName = $ufileName . "_" . $emp_details["id"] . "." . $ext;
			$path = $city . "/GBBSafDemand/";
	
			if ($file->move(WRITEPATH . "uploads/" . $path, $fileName)) {
				$doc_path = $path . $fileName;
				$insertData = [
					"user_id"=>$emp_details["id"],
					"upload_date"=>date("Y-m-d"),
					"file_name"=>$ufileName,
					"doc_path"=>$doc_path,
					"remarks"=>$inputData["remarks"]??null,
				];
				$this->db->table("tbl_gbsaf_import_excels")->insert($insertData);
				$id = $this->db->insertID();
				if($id){
					return $this->response->setJSON([
						"status" => true,
						"message" => "File uploaded successfully",
						"path" => $doc_path
					]);
				}else{
					throw new Exception("Server Error");
				}
			} else {
				return $this->response->setJSON([
					"status" => false,
					"message" => "Failed to upload the file"
				]);
			}
		} catch (\Exception $e) {
			return $this->response->setJSON([
				"status" => false,
				"message" => "Server Error: " . $e->getMessage()
			]);
		}
	}
	
	public function getExcelFileList(){
		try{
			$input = $this->request->getVar();
			$sql =" select * from tbl_gbsaf_import_excels where status=1 ";
			if(isset($input["updateDate"]) && $input["updateDate"]){
				$sql.=" and upload_date='".$input["updateDate"]."' ";
			}
			if(isset($input["file_name"]) && $input["file_name"]){
				$sql.=" and file_name ilike '%".$input["file_name"]."%' ";
			}
			$sql.=" order by id DESC ";
			$data = $this->db->query($sql)->getResultArray();
			$html="<table class='table table-bordered'>
				<thead>
					<tr>
						<th> Sl</th>
						<th> File Name</th>
						<th> Upload Date</th>
						<th> View</th>
					<tr>
				</thead>
				<tbody>
				";
			foreach($data as $key=> $val){
				$html.="<tr> 
						<td>".($key+1)."</td>
						<td> ". $val["file_name"]."</td>
						<td>". $val["upload_date"]."</td>
						<td> 
							<a class = 'btn btn-sm btn-info' href='".base_url("getImageLink_new.php?path=".$val["doc_path"])."' target='_blank'> Dounload</a>
							 <a class = 'btn btn-sm btn-primary' href='".base_url("/govsafDetailPayment/excelView/".$val['id'])."' target='_blank'> view</a></td>
					</tr>
				";
			}
			$html.="
				</tbody>
			</table>
			";
			return $this->response->setJSON([
						"status" => true,
						"message" => "File List",
						"html" => $html
					]);
		}catch(Exception $e){
			print_var($e);
			return $this->response->setJSON([
				"status" => false,
				"message" => "Server Error: " . $e->getMessage()
			]);
		}
	}
	
	public function excelView($id){
	
		$data = $this->db->table("tbl_gbsaf_import_excels");
		if(!is_numeric($id)){
			$data->where("md5(id::text)",$id);
		}else{
			$data->where("id",$id);
		}
		$response["excelInfo"]=$data->get()->getFirstRow("array");
		return view("government/excelView",$response);
	}

	public function gbSafNoticeGenerate($prop_id){
		try {
			$data = arrFilterSanitizeString($this->request->getVar());
			$fyear = getFY();
			list($from,$upto) = explode("-",$fyear);
			$privFyear = ($from-1)."-".$from;
			$result = $this->getDemand($prop_id,$privFyear);
			$emp_details_id = $_SESSION['emp_details']['id'];
			if (isset($_POST['gen_notice'])) {
				$propsql="SELECT tbl_govt_saf_dtl.id,view_ward_mstr.ward_no 
						FROM tbl_govt_saf_dtl
						INNER JOIN view_ward_mstr ON view_ward_mstr.id=tbl_govt_saf_dtl.ward_mstr_id
						where tbl_govt_saf_dtl.id=$prop_id
				";
				$propdata = $this->db->query($propsql)->getFirstRow("array");
				$sql = "SELECT * FROM tbl_govt_saf_notices 
						WHERE govt_saf_dtl_id='".trim($prop_id)."' AND fnyear='".getFY()."' AND notice_type='".$data['notice_type']."'";
				$checkdata = $this->db->query($sql)->getResultArray("array");
				$firstActiveEoSql = " select * from view_emp_details where lock_status=0 and user_type_mstr_id=10 order by id ASC limit 1 "; 
				$firstEo = $this->dbSystem->query($firstActiveEoSql)->getFirstRow("array");
				//dd(count($checkdata));
				if(count($checkdata)<1)
				{
					if($data["notice_type"] == 'Demand')
					{
						$noticeno=$this->generatenoticeno('11',$propdata['ward_no']);
						if($result["t_balance"] > 0){
							$input = [
								"govt_saf_dtl_id"=>$prop_id,
								//"notice_no"=>$data["notice_no"],
								"notice_no"=>$noticeno,
								"notice_date"=>$data["notice_date"],
								"notice_type"=>$data["notice_type"],
								"from_qtr"=>$result["demand_from_qtr"],
								"from_fyear"=>$result["demand_from_fy"],
								"upto_qtr"=>$result["demand_upto_qtr"],
								"upto_fyear"=>$result["demand_upto_fy"],
								"demand_amount"=>$result["t_balance"],
								"penalty"=>$result["payment_dtl"]["OnePercentPnalty"],
								"due_date"=>$result["due_date"],
								"generated_by_emp_details_id"=>$emp_details_id,
								"fnyear"=>getFY(),
								"print_status"=>1,
								"approved_by"=>$firstEo["id"]??null,
							];
						}else{
							$input = []; 
						}
						
					}
					if($data["notice_type"] == 'Assessment')
					{
						$noticeno=$this->generatenoticeno('12',$propdata['ward_no']);
						$input = [
							"govt_saf_dtl_id"=>$prop_id,
							//"notice_no"=>$data["notice_no"],
							"notice_no"=>$noticeno,
							"notice_date"=>$data["notice_date"],
							"notice_type"=>$data["notice_type"],
							"generated_by_emp_details_id"=>$emp_details_id,
							"fnyear"=>getFY(),
							"print_status"=>1,
							"approved_by"=>$firstEo["id"]??null,
						];
						
					}
					if($input)
					{
						$lastInsertId = $this->model_notice->insertNoticeData($input);
						$serial_no = "NOTICE/".$lastInsertId."/".$this->model_notice->getCount($prop_id)['serial'];
						// $notice_no = "NOTICE/".$lastInsertId."/".$this->model_notice->getCount($prop_id)['serial'];
						$this->model_notice->updateRecord(['serial_no'=>$serial_no], $lastInsertId);
					}
					if($this->request->getVar("ajaxNotice")){
						return $lastInsertId;
					}
				}else{
					flashToast('message','Maximum notice for current financial year has been reached.');
	
					$url=base_url('govsafDetailPayment/gbSafNoticeGenerate').'/'.$prop_id;
					return redirect()->to($url);
				}
			}
			$result['notice_dtl'] = $this->model_notice->getNotice($prop_id);
			// dd($result);
			return view('government/generate_notice', $result);
		} catch(Exception $e) {
			print_var($e->getMessage());
			exit();
		}
	}
	
	public function GeneratedNotice($notice_id) {
		try {
			
			$ulb_mstr_dtl = getUlbDtl();
			$noticeDtl = $this->model_notice->getNoticeById($notice_id);
			$sql = "SELECT
				tbl_govt_saf_dtl.id,
				view_ward_mstr.ward_no,
				new_ward.ward_no AS new_ward_no,				
				tbl_govt_saf_dtl.holding_no,
				tbl_govt_saf_dtl.application_no,
				tbl_govt_saf_dtl.building_colony_address,
				tbl_govt_saf_dtl.building_colony_name,
				tbl_govt_saf_dtl.office_name,
	
				officer_dtl.officer_name,
				officer_dtl.mobile_no,
	
				officer_dtl.email
			FROM tbl_govt_saf_dtl
			INNER JOIN view_ward_mstr ON view_ward_mstr.id=tbl_govt_saf_dtl.ward_mstr_id
			left JOIN view_ward_mstr AS new_ward ON new_ward.id=tbl_govt_saf_dtl.new_ward_mstr_id
			INNER JOIN (
				SELECT
					govt_saf_dtl_id,
					STRING_AGG(officer_name, ',') AS officer_name,
					STRING_AGG(mobile_no::TEXT, ',') AS mobile_no,
					STRING_AGG(email_id::TEXT, ',') AS email
				FROM tbl_govt_saf_officer_dtl
				GROUP BY govt_saf_dtl_id
			) AS officer_dtl ON officer_dtl.govt_saf_dtl_id=tbl_govt_saf_dtl.id           
			WHERE 
				tbl_govt_saf_dtl.id='".$noticeDtl["govt_saf_dtl_id"]."'";
			$result = $this->db->query($sql)->getFirstRow("array");
	
			$data['ulb']=$ulb_mstr_dtl;
			$data['notice']=$noticeDtl;
			$data['property']=$result;
			// dd($data['property'],$sql);

			$sign = "dmcsign.png";
			$degignation = "अपर प्रशासक";
            if($data['notice']["created_on"]<'2024-09-28'){
                $sign = "dmcsign.png";
            }
            if($data['notice']["created_on"]<'2024-02-15'){
                $sign = "rajnishkumar_sign.png";
            }
			if($noticeDtl["approved_by"]=='1661'){
                $degignation = "उप प्रशासक";
            }
            if($noticeDtl["approved_by"]=='1719'){
                $degignation = "अपर प्रशासक";
            }
			$data["degignation"]=$degignation;
            $data["signature_path"]=base_url('/public/assets/img/'.$sign);
            if($noticeDtl["approved_by"]){
                $empDtl = $this->dbSystem->query("select * from tbl_emp_details where id=".$noticeDtl["approved_by"])->getFirstRow("array");
				$data["signature_path"]= $empDtl && $empDtl["signature_path"]? base_url("/getImageLink.php?path=/emp_signature/".$empDtl["signature_path"]):$data["signature_path"] ;
            }
			
            // dd($data['ulb']);
            if($noticeDtl["notice_type"] == "Demand"){
                $data['notice']["noticePenalty"] = round((float)(($noticeDtl["demand_amount"] + $noticeDtl["penalty"]) * 0.01), 2) ;
                return view('government/demand_notice', $data);
            }
            if($noticeDtl["notice_type"] == "Assessment"){
                return view('government/assessment_notice', $data);
            }

			// if($noticeDtl["notice_type"] == "Demand"){
			// 	$data['notice']["noticePenalty"] = round((float)(($noticeDtl["demand_amount"] + $noticeDtl["penalty"]) * 0.01), 2) ;
			// 	return view('government/demand_notice', $data);
			// }
			// if($noticeDtl["notice_type"] == "Assessment"){
			// 	return view('government/assessment_notice', $data);
			// }
		} catch(Exception $e) {
			print_var($e);
		}
	}
	
	public function getDemand($prop_id,$fyear=null)
	{
		$sql = "SELECT
				tbl_govt_saf_dtl.id,
				view_ward_mstr.ward_no,
				new_ward.ward_no AS new_ward_no,
				tbl_govt_saf_dtl.holding_no,
				tbl_govt_saf_dtl.application_no,
				tbl_govt_saf_dtl.building_colony_address,
				tbl_govt_saf_dtl.building_colony_name,
				tbl_govt_saf_dtl.office_name,
	
				officer_dtl.officer_name,
				officer_dtl.mobile_no,
	
				tax_dtl.tax_dtl_temp,
	
				demand_dtl.demand_dtl_temp,
				demand_dtl.t_balance,
				demand_dtl.additional_holding_tax,
				demand_dtl.adjust_amount,
				demand_dtl.due_date
	
			FROM tbl_govt_saf_dtl
			INNER JOIN view_ward_mstr ON view_ward_mstr.id=tbl_govt_saf_dtl.ward_mstr_id
			left JOIN view_ward_mstr AS new_ward ON new_ward.id=tbl_govt_saf_dtl.new_ward_mstr_id
			INNER JOIN (
				SELECT
					govt_saf_dtl_id,
					STRING_AGG(officer_name, ',') AS officer_name,
					STRING_AGG(mobile_no::TEXT, ',') AS mobile_no
				FROM tbl_govt_saf_officer_dtl
				GROUP BY govt_saf_dtl_id
			) AS officer_dtl ON officer_dtl.govt_saf_dtl_id=tbl_govt_saf_dtl.id
			INNER JOIN (
				SELECT 
					govt_saf_dtl_id,
					json_agg(json_build_object('qtr', qtr, 'fy_mstr_id', fy_mstr_id, 'fyear', fyear, 'quarterly_tax', (holding_tax+water_tax+education_cess+health_cess+latrine_tax+additional_tax)) ORDER BY id ASC) AS tax_dtl_temp
				FROM tbl_govt_saf_tax_dtl
				GROUP BY govt_saf_dtl_id
			) AS tax_dtl ON tax_dtl.govt_saf_dtl_id=tbl_govt_saf_dtl.id
			LEFT JOIN (
				SELECT 
					govt_saf_dtl_id,
					json_agg(json_build_object('due_date', due_date, 'qtr', qtr, 'fy_mstr_id', fy_mstr_id, 'fyear', fyear, 'amount', amount, 'balance', balance, 'demand_amount', demand_amount, 'additional_holding_tax', additional_holding_tax, 'adjust_amount', adjust_amount) ORDER BY due_date ASC) AS demand_dtl_temp,
					SUM(balance) AS t_balance,
					SUM(additional_holding_tax) AS additional_holding_tax,
					SUM(adjust_amount) AS adjust_amount,
					max(due_date) AS due_date
				FROM tbl_govt_saf_demand_dtl
				WHERE status=1 AND paid_status=0 ".($fyear ? " AND tbl_govt_saf_demand_dtl.fyear<='$fyear' ":"")."
				GROUP BY govt_saf_dtl_id
			) AS demand_dtl ON demand_dtl.govt_saf_dtl_id=tbl_govt_saf_dtl.id
			WHERE 
				tbl_govt_saf_dtl.id='".$prop_id."'";
				
			if ($result = $this->db->query($sql)->getFirstRow("array")) {
				$result["tax_dtl"] = json_decode($result["tax_dtl_temp"], true);
				$uptoIndex = count($result["tax_dtl"])-1;
				$result["quarterly_tax"] = $result["tax_dtl"][$uptoIndex]["quarterly_tax"];
	
				$result["demand_dtl"] = isset($result["demand_dtl_temp"])?json_decode($result["demand_dtl_temp"], true):array();
				if($result["demand_dtl"]){
					$result["demand_from_qtr"] = $result["demand_dtl"][0]["qtr"];
					$result["demand_from_fy"] = $result["demand_dtl"][0]["fyear"];
					$uptoIndex = count($result["demand_dtl"])-1;
					$result["demand_upto_qtr"] = $result["demand_dtl"][$uptoIndex]["qtr"];
					$result["demand_upto_fy"] = $result["demand_dtl"][$uptoIndex]["fyear"];
					$result["prop_dtl_id"] = $result["id"];
					$input = [
						'fy' => $result["demand_upto_fy"],
						'qtr' => $result["demand_upto_qtr"],
						'govt_saf_dtl_id' => $result["id"],
						'user_id' => 2,
					];
					$input = [
						'fy' => $result["demand_upto_fy"],
						'qtr' => $result["demand_upto_qtr"],
						'govt_saf_dtl_id' => $result["id"]
					];
					$result["payment_dtl"] = $this->model_govt_saf_demand_dtl->getGovtSAFDemandAmountDetails($input);
				}
				return $result;
			}
	}
	
	public function generatenoticeno($ward,$wardno){
		$pad=str_pad(rand(999,999999),6,'0',STR_PAD_LEFT);
		$noticeno=$ward.'/REV/'.$wardno.'/'.$pad;
		$sql = "SELECT * FROM tbl_prop_notices 
						WHERE notice_no='".trim($noticeno)."'";
		$checkdata = $this->db->query($sql)->getResultArray("array");
		if(count($checkdata)>0)
		{
			return $this->generatenoticeno($ward,$wardno);
		}
		return $noticeno;
	}
}
