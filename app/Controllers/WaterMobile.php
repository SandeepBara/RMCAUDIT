<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\PostModel;
use App\Models\model_ward_mstr;
use App\Models\model_ulb_mstr;



class WaterMobile extends MobiController
{
	protected $db;
	protected $dbSystem;
	//protected $db_name;
	protected $model_ward_mstr;
	protected $model_ulb_mstr;
	protected $model_prop_dtl;
	protected $model_prop_owner_detail;
	protected $model_prop_tax;
	protected $model_prop_demand;
	protected $model_prop_floor_details;
	protected $model_transaction;
	protected $model_tran_mode_mstr;
	protected $model_payment_adjustment;
	protected $model_fy_mstr;
	protected $model_cheque_details;
	protected $model_collection;
	protected $model_emp_details;
	protected $model_saf_dtl;
	protected $model_transaction_fine_rebet_details;


	public function __construct()
    {
        parent::__construct();
    	helper(['db_helper']);
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
		
    }

	/*public function index()
	{


		return view('mobile/login');
	}
*/

	public function home()
	{ 
		$data =(array)null;
        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
        $emp_details_id = $emp_mstr["id"];
        $user_type_mstr_id = $emp_mstr["user_type_mstr_id"];
        $data["user_type_mstr_id"]=$user_type_mstr_id;
		return view('mobile/index',$data);
	}

	public function search_Property_Tax()
	{
		$ulb_mstr_id = $_SESSION['ulb_dtl']['ulb_mstr_id'];	
		$data =(array)null;
		//print_r($data);
        helper(['form']);
        if($this->request->getMethod()=='post'){
			$data = [
                        /*'previous_ward_mstr_id' => $this->request->getVar('previous_ward_mstr_id'),*/
						'ward_mstr_id' => $this->request->getVar('ward_mstr_id'),
						'holding_no' => $this->request->getVar('holding_no')
						/*'house_no' => $this->request->getVar('house_no')*/
                    ];
				//print_r($data);
				$data['emp_details'] = $this->modelprop->consumer_details($data);
				/*if($data['previous_ward_mstr_id']!=""){
					if($data['holding_no']!=""){
						$data['emp_details'] = $this->modelprop->acc_prv_hld_emp_details($data);
						//print_r($data);
					}elseif($data['house_no']!=""){
						$data['emp_details'] = $this->modelprop->acc_prv_hus_emp_details($data);
					}else{
						echo "hjbfvjh";
					}
				}elseif($data['ward_mstr_id']!=""){
					if($data['holding_no']!=""){
						$data['emp_details'] = $this->modelprop->acc_wrd_hld_emp_details($data);
					}elseif($data['house_no']!=""){
						$data['emp_details'] = $this->modelprop->acc_wrd_hus_emp_details($data);
					}else{
						echo "gnghjvhmvh";
					}
				}*/
            //print_r($data);
			//$data['emp_details'] = $this->modelprop->emp_details($data);
			//print_r($data);
			$data['ward'] = $this->model->getWardList(['ulb_mstr_id'=>$ulb_mstr_id]);
			return view('mobile/Property/Property/Property_List', $data);
		} else{
		$data['ward'] = $this->model->getWardList(['ulb_mstr_id'=>$ulb_mstr_id]);
		return view('mobile/Property/Property/Property_List', $data);
		}
	}
	
	public function property_due_details($id=null)
	{
		$data =(array)null;
		$data['id']=$id;
		$data['basic_details'] = $this->modelprop->basic_details($data);
		if ( $owner_details = $this->modelowner->owner_details($data) ) {
			$data['owner_details'] = $owner_details;
		}
		if ( $tax_list = $this->modeltax->tax_list($data['basic_details']['prop_dtl_id']) ) {
			$data['tax_list'] = $tax_list;
		}
		if ( $demand_detail = $this->modeldemand->demand_detail($data) ) {
			$data['demand_detail'] = $demand_detail;
		}
		//print_r($data['owner_details']);
		return view('mobile/Property/Property/property_due_details',$data);
	}
	
	public function property_details($id=null)
	{
		$data =(array)null;
		$data['id']=$id;
		$data['basic_details'] = $this->modelprop->basic_details($data);
		if ( $owner_details = $this->modelowner->owner_details($data) ) {
			$data['owner_details'] = $owner_details;
		}
		if ( $tax_list = $this->modeltax->tax_list($data['basic_details']['prop_dtl_id']) ) {
			$data['tax_list'] = $tax_list;
		}
		if ( $demand_detail = $this->modeldemand->demand_detail($data) ) {
			$data['demand_detail'] = $demand_detail;
		}
		if ( $occupancy_detail = $this->modelfloor->occupancy_detail($data['basic_details']['prop_dtl_id']) ) {
			$data['occupancy_detail'] = $occupancy_detail;
		}
		if ( $payment_detail = $this->modelpay->jskProp_payment_detail($data['basic_details']['prop_dtl_id']) ) {
			$data['payment_detail'] = $payment_detail;
		}
		//print_r($data['payment_detail']);
		return view('mobile/Property/Property/property_details', $data);
	}
	
	public function payment_details($id=null)
	{
		$data =(array)null;
		$data['id']=$id;
		$data['basic_details'] = $this->modelprop->basic_details($data);
		$data['owner_details'] = $this->modelowner->owner_details($data);
		if ( $tax_list = $this->modeltax->tax_list($data['basic_details']['prop_dtl_id']) ) {
			$data['tax_list'] = $tax_list;
		}
		if ( $payment_detail = $this->modelpay->payment_detail($data['basic_details']['prop_dtl_id']) ) {
			$data['payment_detail'] = $payment_detail;
		}
		//print_r($data['payment_detail']);
		return view('mobile/Property/Property/payment_details', $data);
	}
	
	
	
	
	
	
	
	
	
	
	
	
	public function confirm_payment($id=null)
	{ 
		$data =(array)null;
        $Session = Session();
        
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
        $data['emp_details_id'] = $emp_mstr["id"];
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
							//'total_qrt_pnlty' => $this->request->getVar('total_qrt_pnlty'),
							'ful_qtr' => $this->request->getVar('ful_qtr'),
							'total_qrt' => $this->request->getVar('total_qrt'),
							'ward_mstr_id' => $this->request->getVar('ward_mstr_id'),
							'chq_date' => $this->request->getVar('chq_date'),
							'chq_no' => $this->request->getVar('chq_no'),
							'bank_name' => $this->request->getVar('bank_name'),
							'branch_name' => $this->request->getVar('branch_name'),
							'emp_details_id' => $emp_mstr["id"]
							
						];
						//print_r($data);
						
				$data['date'] = date('Y-m-d');
				
				$dif_qtr = 0;
				$tol_mnth = $data['ful_qtr']*3;
				$j=0;
				$crnt_dm= date('m');
				if($crnt_dm==01 || $crnt_dm==02 || $crnt_dm==03)
				{
					$crnt_dm = $crnt_dm+9;
					$crnt_dm=(12-$crnt_dm)+3;
					$tol_mnth = $tol_mnth-$crnt_dm;
				}else{
					$crnt_dm=(12-$crnt_dm)+3;
					$tol_mnth = $tol_mnth-$crnt_dm;
				}
				
				$data["total_qrt_pnlty"] = 0;
				$data["deman_am"] = 0;
				$data['demand_amn'] = $this->modeldemand->demand_amnt($data["custm_id"]);
				for($i=1;$i<=$data['total_qrt'];$i++){
					$dem_am = $data['demand_amn'][$i-1]["balance"];
					$dif_qtr = $dif_qtr + 3;
					$each_penlty = ($dem_am/100)*($tol_mnth-$dif_qtr);
					if($each_penlty>0){
						$data['tol_pent'] = $each_penlty;
					}else{
						$data['tol_pent'] = 0;
					}
					//print_r($tol_mnth);
					$data["total_qrt_pnlty"] = $data["total_qrt_pnlty"] + $data['tol_pent'];
					$data["deman_am"] = $data["deman_am"] + $data['demand_amn'][$i-1]["balance"];
				}
				//print_r($data["land_occupancy_delay_fine"]);
				$data["total_payabl"] = $data["deman_am"] + $data["total_qrt_pnlty"];
					
					
					
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
				
				$data['insertPayment'] = $this->modelpay->insertPayment($data);
				
				if($data['insertPayment']){
					if($data["payment_mode"]=='2' || $data["payment_mode"]=='3'){
						$chqDDdetails = $this->modelchqDD->chqDDdetails($data);
					}
					
					$data['paidsts'] = $this->modelprop->paidsts($data);
					
					$data['demand_amnt'] = $this->modeldemand->demand_amnt($data["custm_id"]);
					
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
						
						}
						else{
							$data['demand_id'] = $this->modeldemand->demand_id($data);
							for($i=1;$i<=$data['total_qrt'];$i++){
								$data['resultid'] = $data['demand_id'][$j++];
									//print_r($data['resultid']);
								$demand_am = $data['demand_amnt'][$i-1]["balance"];
							
								if($tol_mnth>=3){
									$dif_qtr = $dif_qtr + 3;
									$each_penlty = ($demand_am/100)*($tol_mnth-$dif_qtr);
									$data['tol_pent'] = $each_penlty;
									if($data['tol_pent']<0){
										$data['tol_pent']=0;
									}
									
									$data['updatedemandPayment'] = $this->modeldemand->updatedemandPayment($data);
											
									$data['demandCollection'] = $this->modelpropcoll->demandCollection($data);
								}
							}
						}
						
						if($data["total_qrt_pnlty"]>0){
							$data['head_name'] = "1% Monthly Penalty";
							$data['fine_rebet_amount'] = $data["total_qrt_pnlty"];
							$data['add_minus'] = "Add";
							
							$data['fine_rebet_details'] = $this->modelpenalty->fine_rebet_details($data);
						}
						if($data["total_rebate"]>0){
							$data['head_name'] = "First Quartare Discount";
							$data['fine_rebet_amount'] = $data["total_rebate"];
							$data['add_minus'] = "Minus";
							
							$data['fine_rebet_details'] = $this->modelpenalty->fine_rebet_details($data);
						}
				
					$data['assessment_type'] = $this->modelassess->assessment_type($data['custm_id']);
					if($data['assessment_type']=="Reassessment"){
						$data['prop_hold'] = $this->modelprop->prop_hold($data['custm_id']);
					}
				}
			if($this->db->transStatus() === FALSE){
				$this->db->transRollback();
				flashToast("confirm_payment", "Something errordue to payment!!!");
				return $this->response->redirect(base_url('mobi/confirm_payment/'.md5($data['custm_id'])));
			}else{
				$this->db->transCommit();
				return $this->response->redirect(base_url('mobi/payment_tc_receipt/'.md5($data['insertPayment'])));
			}
			
			
			
			
			//return view('property/citizen/pay_Property_Tax');
			
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
		$data['advance'] = $this->modeladjustment->advance_amnt($data);
		//print_r($data['advance']);
		$data['tran_mode'] = $this->modeltran->getTranModeList();
		return view('mobile/Property/Property/confirm_payment', $data);
		}
	}
	}
	
	
	
	public function payment_tc_receipt($tran_no=null)
	{
		$data =(array)null;
		$data['tran_no']=$tran_no;
		$ulb_mstr_id = $_SESSION['ulb_dtl']['ulb_mstr_id'];	
		$data['ulb_mstr_name'] = $this->modelUlb->getulb_list($ulb_mstr_id);
		$data['tran_mode_dtl'] = $this->modelpay->getTrandtlList($data);
		$data['fyFrom'] = $this->modelfy->getfyFromList($data['tran_mode_dtl']['from_fy_mstr_id']);
		$data['fyUpto'] = $this->modelfy->getfyUptoList($data['tran_mode_dtl']['upto_fy_mstr_id']);
		$data['payMode'] = $this->modeltran->getpayModeList($data['tran_mode_dtl']['tran_mode_mstr_id']);
		$data['holdingward'] = $this->modelprop->getholdWard(md5($data['tran_mode_dtl']['prop_dtl_id']));
		if($data['payMode']['id']==2 || $data['payMode']['id']==3){
			$data['chqDD_details'] = $this->modelchqDD->mode_dtl($data['tran_no']);
		}
		$data['emp_dtls'] = $this->modelemp->emp_dtls($data['tran_mode_dtl']['tran_by_emp_details_id']);
		//print_r($data);
		return view('mobile/Property/Property/payment_tax_receipt', $data);
	}


	public function reports_menu()
	{
		return view('mobile/report/reports_menu');
	}
	
	
	
	
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
