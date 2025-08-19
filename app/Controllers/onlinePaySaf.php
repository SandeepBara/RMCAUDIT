<?php 
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\PostModel;
use App\Models\model_ward_mstr;
use App\Models\model_ulb_mstr;
use App\Models\model_fy_mstr;
use App\Models\model_transaction;
use App\Models\model_saf_demand;
use App\Models\model_saf_collection;
use App\Models\model_saf_dtl;
use App\Models\model_transaction_fine_rebet_details;
use App\Models\model_razor_pay_response;
use App\Models\model_razor_pay_request;
use App\Models\model_view_saf_dtl;
use CodeIgniter\Session\Session;

use App\Models\model_ccavanue_pay_request;
use App\Models\model_ccavanue_pay_response;
use Exception;

class onlinePaySaf extends HomeController
{
	protected $db;
	protected $dbSystem;
	protected $db_name;
	protected $model_ward_mstr;
	protected $model_ulb_mstr;
	protected $model_fy_mstr;
	protected $model_transaction;
	protected $model_saf_demand;
	protected $model_saf_collection;
	protected $model_saf_dtl;
	protected $model_transaction_fine_rebet_details;
	protected $model_razor_pay_response;
	protected $model_razor_pay_request;
	protected $model_view_saf_dtl;

	protected $model_ccavanue_pay_request;
	protected $model_ccavanue_pay_response;

	//protected $api_key_id = "rzp_test_trroh9p49WXNDv";
	//protected $api_secret = "PqVzNrZZQcyMPKYK1EhN1aMo";

	public function __construct()
    {

        parent::__construct();
    	helper(['db_helper', 'qr_code_generator_helper', 'ccavanue_helper']);

        if($db_name = dbConfig("property")){
            $this->db = db_connect($db_name);            
        }

        if($db_system = dbSystem()){
            $this->dbSystem = db_connect($db_system); 
        } 
		
        helper(['form', 'razorpay_helper']);
		//includeRazorLibrary();
		//$this->api = new \Razorpay\Api\Api($this->api_key_id, $this->api_secret);

		$this->model_view_saf_dtl = new model_view_saf_dtl($this->db);
        $this->model = new model_ward_mstr($this->dbSystem);
		$this->modelUlb = new model_ulb_mstr($this->dbSystem);
		$this->modelfy = new model_fy_mstr($this->dbSystem);
		$this->model_transaction = new model_transaction($this->db);
		$this->model_saf_demand = new model_saf_demand($this->db);
		$this->model_saf_collection = new model_saf_collection($this->db);
		$this->model_saf_dtl = new model_saf_dtl($this->db);
		$this->modelpenalty = new model_transaction_fine_rebet_details($this->db);
		$this->model_razor_pay_response = new model_razor_pay_response($this->db);
		$this->model_razor_pay_request = new model_razor_pay_request($this->db);

		$this->model_ccavanue_pay_request = new model_ccavanue_pay_request($this->db);
		$this->model_ccavanue_pay_response = new model_ccavanue_pay_response($this->db);

		$this->CCA_MERCHANT_ID = getenv("CCA_MERCHANT_ID");
		$this->CCA_ACCESS_CODE = getenv("CCA_ACCESS_CODE");
        $this->CCA_WORKING_KEY = getenv("CCA_WORKING_KEY");
		
    }

	function __destruct() {
		if(isset($this->db)) $this->db->close();
		if(isset($this->dbSystem)) $this->dbSystem->close();
	}
	
	
	public function safPaymentProceed()
	{
		
		$data =(array)null;
		$Session = Session();
		$ulb = $Session->get('ulb_dtl');
		$saf_dtl = $Session->get('saf_dtl');
		$data = $Session->get('get_saf_full_details');
		
		$data["DuesYear"]=$this->model_saf_demand->geDuesYear($saf_dtl["saf_dtl_id"]);
		
		
		if($data["DuesYear"])
		{
			$input = [
				'fy' => $data["DuesYear"]["max_year"],
				'qtr' => $data["DuesYear"]["max_quarter"],
				'saf_dtl_id' => $saf_dtl['saf_dtl_id'],
				'user_id'=> 0,
				];
			$data["DuesDetails"]=$this->model_saf_demand->getSAFDemandAmountDetails($input);
						
			$order_id = $this->api->order->create(array(
					'receipt' => '123',
					'amount' => $data["DuesDetails"]["PayableAmount"]*100,
					'currency' => 'INR'
				)
			);
			
			$input = [
					"prop_dtl_id"=> $saf_dtl["saf_dtl_id"],
					"module"=> "Saf",
					"merchant_id"=> 0,
					"from_fy_mstr_id"=> $data["DuesYear"]["min_fy_id"],
					"from_fy"=> $data["DuesYear"]["min_year"],
					"from_qtr"=> $data["DuesYear"]["min_quarter"],
					"upto_fy_mstr_id"=> $data["DuesYear"]["max_fy_id"],
					"upto_fy"=> $data["DuesYear"]["max_year"],
					"upto_qtr"=> $data["DuesYear"]["max_quarter"],
					"demand_amt"=> $data["DuesDetails"]["DemandAmount"],
					"penalty_amt"=> ($data["DuesDetails"]["OnePercentPnalty"] + $data["DuesDetails"]["OtherPenalty"]),
					"discount"=> ($data["DuesDetails"]["RebateAmount"] + $data["DuesDetails"]["AdvanceAmount"]),
					"payable_amt"=> $data["DuesDetails"]["PayableAmount"],
					"order_id"=> $order_id["id"],
				];
			$data["pg_mas_id"]=$this->model_razor_pay_request->pay_request($input);

			$data["razorpay_param"]=[
					"key"=> $this->api_key_id,
					"order_id"=> $order_id["id"],
					"amount"=> $data["DuesDetails"]["PayableAmount"]*100,
					"currency"=> "INR",
					"name"=> $ulb["ulb_name"],
					"description"=> "SAF Tax Payment",
					"owner_name"=> $data["saf_owner_detail"][0]["owner_name"],
					"owner_email"=> $data["saf_owner_detail"][0]["email"],
					"owner_contact"=> $data["saf_owner_detail"][0]["mobile_no"],
			];
		}

		
		$data['demand_detail'] = $this->model_saf_demand->demand_detail(["id"=> md5($saf_dtl["saf_dtl_id"])]);
		$data["ulb_name"]=$ulb["ulb_name"];
		return view('Citizen/SAF/pay_demand_online', $data);
	}


	


	public function citizen_payment_saf($id=null)
	{
		$data =(array)null;
        helper(['form']);
		
		$data['api_key_id'] = "rzp_test_trroh9p49WXNDv";
		$data['api_secret'] = "PqVzNrZZQcyMPKYK1EhN1aMo";
		$data['appa_id'] = $id; 
        if($this->request->getMethod()=='post')
		{
			$inputs = filterSanitizeStringtoUpper($this->request->getVar());
			$data['fy_id'] = $inputs['due_upto_year'];
			$data['qtr'] = $inputs['date_upto_qtr'];
			$data['ful_qtr'] = $inputs['ful_qtr'];
			$data['total_qrt'] = $inputs['total_qrt'];
			
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
			
			$data["deman_am"] = 0;
			$data['tol_pently'] = 0;
			
			$data['demand_amn'] = $this->model_saf_demand->citizen_caldemand_amount($data["appa_id"]);
			$data['custm_id'] = $data['demand_amn'][0]['saf_dtl_id'];
			//print_r($tol_mnths);
			for($i=1;$i<=$data['total_qrt'];$i++){
				$dem_am = $data['demand_amn'][$i-1]["balance"];
				$dif_qtr = $dif_qtr + 3;
				$dem_fyids = $data['demand_amn'][$i-1]["fy_mstr_id"];
				if($dem_fyids>=49){
					if($tol_mnths>=3){
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
					$data['demand_rbt'] = $this->model_saf_demand->demand_rebet($data["custm_id"],$data['fy_id']['id']);
					$rebate = ($data['demand_rbt']['sum']/100)*5;
				}
				else{ $rebate = 0; }
			}else{ $rebate = 0; }
			$data['rebate'] = $rebate;
			
			$data["total_pabl"] = ($data["deman_am"] + $data['tol_pently']) - $data['rebate'];
			
			$data["total_pa_onlin"] = ($data['total_pabl']/100)*5;
			$data["total_payabl"] = $data["total_pabl"] - $data["total_pa_onlin"];
			
			$data['amount']=round($data['total_payabl'],2);
			//print_r($data["amount"]);
			return view('Citizen/pay_demand_online', $data);
			
		}
	}
	
	
	public function citizen_rzpay_saf($pay_id=null,$ord_id=null,$signtr=null,$id=null,$flqtr=null,$tltqtr=null)
	{
		$data =(array)null;
        helper(['form']);
		$data['ip_address'] = $_SERVER['REMOTE_ADDR'];
		$data['api_key_id'] = "rzp_test_trroh9p49WXNDv";
		$data['api_secret'] = "PqVzNrZZQcyMPKYK1EhN1aMo";
		$data['pay_id'] = $pay_id; 
		$data['ord_id'] = $ord_id; 
		$data['signtr'] = $signtr; 
		$data['appa_id'] = $id; 
		$data['flqtr'] = $flqtr; 
		$data['tltqtr'] = $tltqtr; 
		
		$rebate_demand = 0;
		$dif_qtr = 0;
		$tol_mnth = $data['flqtr']*3;
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
		
		$data['demand_amn'] = $this->model_saf_demand->citizen_caldemand_amount($data["appa_id"]);
		$data['custm_id'] = $data['demand_amn'][0]['prop_dtl_id'];
		//print_r($tol_mnths);
		$data['from_fy_year'] = $data['demand_amn'][0]["fy_mstr_id"];
		$data['from_fy_qtr'] = $data['demand_amn'][0]["qtr"];
		for($i=1;$i<=$data['tltqtr'];$i++){
			$dem_am = $data['demand_amn'][$i-1]["balance"];
			$dif_qtr = $dif_qtr + 3;
			$dem_fyids = $data['demand_amn'][$i-1]["fy_mstr_id"];
			if($dem_fyids>=49){
				if($tol_mnths>=3){
					$each_penlty = ($dem_am/100)*($tol_mnths-$dif_qtr);
					if($each_penlty>0){
						$data['tol_pently'] = $data['tol_pently'] + $each_penlty;
					}else { $data['tol_pently'] = $data['tol_pently']; }
				}else { $data['tol_pently'] = $data['tol_pently']; }
			}else { $data['tol_pently'] = $data['tol_pently']; }
				
			$data["deman_am"] = $data["deman_am"] + $data['demand_amn'][$i-1]["balance"];
			$data['due_upto_year'] = $data['demand_amn'][$i-1]["fy_mstr_id"];
			$data['date_upto_qtr'] = $data['demand_amn'][$i-1]["qtr"];
		}
		
		$crnt_dm_for_rdt= date('m');
		if($crnt_dm_for_rdt=='04' || $crnt_dm_for_rdt=='05' || $crnt_dm_for_rdt=='06'){
			$from_year = date("Y");
			$to_year = $from_year + 01;
			$fy = $from_year .'-'. $to_year;
			$data['fy_id'] = $this->modelfy->getFiidByfyyr($fy);
			if($data['date_upto_qtr']==4){
				$data['demand_rbt'] = $this->model_saf_demand->demand_rebet($data["custm_id"],$data['fy_id']['id']);
				$rebate = ($data['demand_rbt']['sum']/100)*5;
			}
			else{ $rebate = 0; }
		}else{ $rebate = 0; }
		$data['rebate'] = $rebate;
		
		$data["total_pabl"] = ($data["deman_am"] + $data['tol_pently']) - $data['rebate'];
		
		$data["total_pa_onlin"] = ($data['total_pabl']/100)*5;
		$data["total_payabl"] = $data["total_pabl"] - $data["total_pa_onlin"];
		
		
		$round = round($data["total_payabl"]);
		$data["round_off"] = $round - $data["total_payabl"];
		
		
		$data['current_date'] = date("Y-m-d");
		$data['checkPayment'] = $this->model_transaction->checkpropPayment($data);
		
		if($data['checkPayment']){
			flashToast("citizen_confirm_payment", "Same Day More Than One Time Payment Cann't Done!!!");
			return $this->response->redirect(base_url('CitizenProperty/citizen_saf_confirm_payment/'.$data['appa_id']));
		}else{
			$data['insertPayment'] = $this->model_transaction->onlineinsertPayment($data);
			
			if($data['insertPayment']){
				$data['pay_request'] = $this->model_razor_pay_request->pay_request($data);
				$data['pay_response'] = $this->model_razor_pay_response->pay_response($data);
				//die();
				$data['demand_id'] = $this->model_saf_demand->demand_id($data);
				$dif_qtr = 0;
				$data['tol_pent'] = 0;
				for($i=1;$i<=$data['tltqtr'];$i++){
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
					
					$data['pntmnth'] = $tol_mnths-$dif_qtr;
					$data["date_cls"]= date("Y-m-d H:i:s");
					$data['updatedemandPayment'] = $this->model_saf_demand->updatedemandPayment($data);
							
					$data['demandCollection'] = $this->model_saf_collection->demandCollection($data);
				}
				
				$data["date"] = date("Y-m-d");
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
				
				if($data['total_pa_onlin']>0){
					$data['head_name'] = "Rebet From Online Payment";
					$data['fine_rebet_amount'] = $data['total_pa_onlin'];
					$data['add_minus'] = "Minus";
					
					$data['fine_rebet_details'] = $this->modelpenalty->fine_rebet_details($data);
				}
				
			}
		}
		if($this->db->transStatus() === FALSE){
			$this->db->transRollback();
			flashToast("citizen_confirm_payment", "Something error into payment process!!!");
			return $this->response->redirect(base_url('CitizenProperty/Citizen_saf_confirm_payment/'.$data['appa_id']));
		}else{
			$this->db->transCommit();
			return $this->response->redirect(base_url('onlinePaySaf/payment_online_receipt/'.md5($data['insertPayment'])));
		}
	}
	
	public function safPaymentProceedCC()
	{
		
		try
		{
			$data =(array)null;
			$Session = Session();
			$ulb = $Session->get('ulb_dtl');
			$saf_dtl = cGetCookie('saf_dtl');
			$data = $Session->get('get_saf_full_details');
			
			$currentTime = date("Y-m-d H:i:s");
            $fiveMituts = date("Y-m-d H:i:s",strtotime($currentTime."-60 seconds "));
            
            $sql = "SELECT * FROM tbl_hdfc_request WHERE prop_dtl_id = '".$saf_dtl["saf_dtl_id"]."'
                        AND module = 'Saf'
                        AND created_on >= '".$fiveMituts."'";
            $checkRequest = $this->db->query($sql)->getFirstRow("array");  
            if($checkRequest)
            {
                throw new Exception("Please Wait ".date("s",(strtotime($checkRequest["created_on"])-strtotime($fiveMituts)))." Seconds After first transection");
            }
			
			$data["DuesYear"]=$this->model_saf_demand->geDuesYear($saf_dtl["saf_dtl_id"]);
			
			
			
			if($data["DuesYear"])
			{
				$input = [
					'fy' => $data["DuesYear"]["max_year"],
					'qtr' => $data["DuesYear"]["max_quarter"],
					'saf_dtl_id' => $saf_dtl['saf_dtl_id'],
					'user_id'=> 0,
					];
				$data["DuesDetails"]=$this->model_saf_demand->getSAFDemandAmountDetails($input);

				if($data["DuesDetails"]["PayableAmount"]<=0)
				{
					throw new Exception("Payable amount should be atleast 1 rupee");
				}

				$ccRevenue = getOderId(1);
				
				$orderId = $ccRevenue["orderId"];
				$merchent_id = $ccRevenue["merchantId"];
				$working_key = getWorkingKey(1);
				$access_code = getAccessCode(1);
				$redirectUrl = base_url('onlinePaySaf/paymentSuccess/'.md5($saf_dtl["saf_dtl_id"]));
				$cancelUrl = base_url('onlinePaySaf/paymentFailed/'.md5($saf_dtl["saf_dtl_id"]));
				$billing_mobile_no = '';


				$input = [
					"order_id" => $orderId,
					"merchant_id"=> $merchent_id,
					"prop_dtl_id"=> $saf_dtl["saf_dtl_id"],
					"module"=> "Saf",
					"from_fy_mstr_id"=> $data["DuesYear"]["min_fy_id"],
					"from_fy"=> $data["DuesYear"]["min_year"],
					"from_qtr"=> $data["DuesYear"]["min_quarter"],
					"upto_fy_mstr_id"=> $data["DuesYear"]["max_fy_id"],
					"upto_fy"=> $data["DuesYear"]["max_year"],
					"upto_qtr"=> $data["DuesYear"]["max_quarter"],
					"demand_amt"=> $data["DuesDetails"]["DemandAmount"],
					"penalty_amt"=> ($data["DuesDetails"]["OnePercentPnalty"] + $data["DuesDetails"]["OtherPenalty"]),
					"discount"=> ($data["DuesDetails"]["RebateAmount"] + $data["DuesDetails"]["AdvanceAmount"]),
					"payable_amt"=> round($data["DuesDetails"]["PayableAmount"]),
					"redirect_url" => $redirectUrl,
					"failure_url" => $cancelUrl,
					"status" => 1

				];
				
				$data["pg_mas_id"]=$this->model_ccavanue_pay_request->pay_request($input);

				CCAvanuePay($merchent_id, $access_code, $working_key, $orderId, round($data["DuesDetails"]["PayableAmount"]), $redirectUrl, $cancelUrl, $billing_mobile_no);

				// $data["razorpay_param"]=[
				// 		"key"=> $this->api_key_id,
				// 		"order_id"=> $order_id["id"],
				// 		"amount"=> $data["DuesDetails"]["PayableAmount"]*100,
				// 		"currency"=> "INR",
				// 		"name"=> $ulb["ulb_name"],
				// 		"description"=> "SAF Tax Payment",
				// 		"owner_name"=> $data["saf_owner_detail"][0]["owner_name"],
				// 		"owner_email"=> $data["saf_owner_detail"][0]["email"],
				// 		"owner_contact"=> $data["saf_owner_detail"][0]["mobile_no"],
				// ];
			}

			
			$data['demand_detail'] = $this->model_saf_demand->demand_detail(["id"=> md5($saf_dtl["saf_dtl_id"])]);
			$data["ulb_name"]=$ulb["ulb_name"];
			return view('Citizen/SAF/pay_demand_online', $data);
		}
		catch (\Exception $e) {
			flashToast("payment", $e->getMessage());
			flashToast("message", $e->getMessage());
			return redirect()->back()->with('error', $e->getMessage());
		}
	}


	public function paymentSuccess($saf_dtl_id_MD5)
	{
		$data =(array)null;

		try
		{
			$decript = decrypt($this->request->getVar("encResp"),getWorkingKey(1));
			
			$order_status= $decript["order_status"];
			if($order_status != 'Success')
			{
				return $this->paymentFailed($saf_dtl_id_MD5);
			}

			$where = [
					"order_id" => $decript["order_id"],
					"md5(prop_dtl_id::text)" => $saf_dtl_id_MD5,
					"module" => 'Saf'
			];

			$request=$this->model_ccavanue_pay_request->getRecord($where);

			if(!$request || round($decript['amount']) != round($request['payable_amt'])){
				throw new Exception("Payment gateway order Id or amount is not matching");
			}

			$input=[
					"request_id"=> $request['id'],
					"prop_dtl_id"=> $request['prop_dtl_id'],
					"module"=> $request['module'],
					"payable_amt"=> $request["payable_amt"],
					"ip_address"=> get_client_ip(),
					"merchant_id"=> $request["merchant_id"],
					"order_id"=> $request["order_id"],
					"tracking_id"=> $decript["tracking_id"],
					"bank_ref_no"=> $decript["bank_ref_no"],
					"error_code"=> $decript["status_code"],
					"error_desc" => $decript['failure_message'],
					"error_source"=> null,
					"error_step"=> null,
					"error_reason"=> $decript['status_message'],
					"respons_data"=> json_encode($decript),
					"status"=> 1
			];


			$data=[
				"saf_dtl_id"=> $request['prop_dtl_id'],
				"fy"=> $request["upto_fy"],
				"qtr"=> $request["upto_qtr"],
				"user_id"=> 0,
				"payment_mode"=> "Online",
				"remarks"=> null,
				"total_payable_amount"=> $request["payable_amt"],
			];

			$this->db->transBegin();
			$this->model_ccavanue_pay_response->pay_response($input);
			$trxn_id = $this->model_transaction->saf_pay_now($data, []);

			if($this->db->transStatus() === FALSE)
			{
				$this->db->transRollback();
				flashToast("message", "Some error occured, Transaction process has bee rollback!!!");
				return $this->response->redirect(base_url('CitizenDtl/citizen_saf_payment_details'));
			}
			else
			{
				$this->db->transCommit();

				// Update saf full detail in session
				$Session = Session();
				$saf_dtl = $Session->get('saf_dtl');
				$saf = $this->model_view_saf_dtl->get_saf_full_details(md5($saf_dtl["saf_dtl_id"]));
				$saf = $saf['get_saf_full_details'];
				$data=json_decode($saf, true);
				$Session->set('get_saf_full_details', $data);

				$message = "Order No.: ".$request["order_id"].", Amount: ".$request["payable_amt"];
				flashToast("message",  $message. " Payment successfully done!!!");
				return $this->response->redirect(base_url('CitizenDtl/citizen_saf_payment_details'));
			}

		}
		catch (Exception $e)
        {
            flashToast("message", $e->getMessage());
            return $this->response->redirect(base_url('CitizenDtl/citizen_saf_confirm_payment'));
        }
	}


	public function paymentFailed($saf_dtl_id_MD5)
	{
		$decript = decrypt($this->request->getVar("encResp"),getWorkingKey(1));
		$order_status= $decript["order_status"];
		if($order_status == 'Success')
		{
			return $this->paymentSuccess($saf_dtl_id_MD5);
		}

		$where = [
				"order_id" => $decript["order_id"],
				"md5(prop_dtl_id::text)" => $saf_dtl_id_MD5,
				"module" => 'Saf'
		];
		$request=$this->model_ccavanue_pay_request->getRecord($where);
		$input=[
				"request_id"=> $request['id'],
				"prop_dtl_id"=> $request['prop_dtl_id'],
				"module"=> $request['module'],
				"payable_amt"=> $request["payable_amt"],
				"ip_address"=> get_client_ip(),
				"merchant_id"=> $request["merchant_id"],
				"order_id"=> $request["order_id"],
				"tracking_id"=> $decript["tracking_id"],
				"bank_ref_no"=> $decript["bank_ref_no"],
				"error_code"=> $decript["status_code"],
				"error_desc" => $decript['failure_message'],
				"error_source"=> null,
				"error_step"=> null,
				"error_reason"=> $decript['status_message'],
				"respons_data"=> json_encode($decript),
				"status"=> 1
		];


		$this->db->transBegin();
		$this->model_ccavanue_pay_response->pay_response($input);
		flashToast("message", "Oops, Something went wrong, payment failed");

		return $this->response->redirect(base_url('CitizenDtl/citizen_saf_payment_details'));
			
	}
	
	public function payment_online_receipt($tran_no=null)
	{
		
		$data =(array)null;
		$ulb_mstr_id = $_SESSION['ulb_dtl']['ulb_mstr_id'];	
		$path=base_url('citizenPaymentReceipt/payment_online_receipt/'.$ulb_mstr_id.'/'.$tran_no);
		$data['ss']=qrCodeGeneratorFun($path);
		$data['tran_no']=$tran_no;
		$ulb_mstr_id = $_SESSION['ulb_dtl']['ulb_mstr_id'];	
		$data['ulb_mstr_name'] = $this->modelUlb->getulb_list($ulb_mstr_id);
		$data['tran_mode_dtl'] = $this->model_transaction->getTrandtlList($data['tran_no']);
		//$data['coll_dtl'] = $this->modelsafcoll->collection_dtl($data['tran_no']);
		$data['fyFrom'] = $this->modelfy->getfyFromList($data['tran_mode_dtl']['from_fy_mstr_id']);
		$data['fyUpto'] = $this->modelfy->getfyUptoList($data['tran_mode_dtl']['upto_fy_mstr_id']);
		$data['holdingward'] = $this->model_saf_dtl->getholdWard(md5($data['tran_mode_dtl']['prop_dtl_id']));
		$data['basic_details'] = $this->model_saf_dtl->basic_dtl(md5($data['tran_mode_dtl']['prop_dtl_id']));
		
		$data['coll_dtl'] = $this->model_saf_collection->collection_propdtl($data['tran_mode_dtl']['id']);
		$data['penalty_dtl'] = $this->modelpenalty->penalty_dtl($data['tran_mode_dtl']['id']);
		//print_r($data);
		//die();
		return view('citizen/payment_Onlinereceipt', $data);
	}
}