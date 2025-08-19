<?php 
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_transaction;
use App\Models\model_emp_details;
use App\Models\Water_Transaction_Model;
use App\Models\TradeTransactionModel;
use App\Models\WaterCashVerificationModel;
use App\Models\model_govt_saf_transaction;
use Exception;
use JsonException;

class CashVerification extends AlphaController
{
    protected $db;
    protected $dbSystem;
    protected $water;
    protected $trade;
    protected $model_ward_mstr;

    protected $model_transaction;
    protected $Water_Transaction_Model;
    protected $TradeTransactionModel;
	protected $cash_verification;
	protected $model_emp_details;
    //protected $db_name;
    public function __construct()
    {
        parent::__construct();
        helper(['db_helper','form']);
        if($db_name = dbConfig("property")){
            //echo $db_name;
            $this->db = db_connect($db_name);            
        }
        if($db_system = dbSystem()){
            $this->dbSystem = db_connect($db_system); 
        }
        if($db_name = dbConfig("water")){
            //echo $db_name;
            $this->water = db_connect($db_name);            
        }
        if($db_name = dbConfig("trade")){
            //echo $db_name;
            $this->trade = db_connect($db_name);            
        }
        //$db_name = db_connect("db_rmc_property"); 
        $this->model_transaction = new model_transaction($this->db);
        $this->cash_verification = new WaterCashVerificationModel($this->db);
        $this->model_emp_details = new model_emp_details($this->dbSystem);
        $this->Water_Transaction_Model = new Water_Transaction_Model($this->water);
        $this->TradeTransactionModel= new TradeTransactionModel($this->trade);
      	$this->model_govt_saf_transaction=new model_govt_saf_transaction($this->db);
    }
   	

	public function List()
	{
		$data=(array)null;
		
		$data['tran_date']=date('Y-m-d');
		$data['employee_id']=null;
   		if($this->request->getMethod()=='post')
   		{
			$data['tran_date']=$this->request->getVar('tran_date');
			$data['employee_id']=$this->request->getVar('employee_id');
   		}
		
		$data['cash_verification_detals']=$this->cash_verification->TodaysCollection($data['tran_date'], $data['employee_id']);
		$data['todays_verified_collection']=$this->cash_verification->TodaysVerifiedCollection($data['tran_date'], $data['employee_id']);
		$data['emplist']=$this->model_emp_details->getTCList();
		// return view('property/cash_verification/List', $data);
		return view('property/cash_verification/List', $data);
	}

	public function View($tran_by_emp_details_id, $tran_date)
	{
		$data=(array)null;
		$data['tran_by_emp_details_id']=$tran_by_emp_details_id;
		$data['tran_date']=$tran_date;

		if($this->request->getMethod()=='post')
		{
			$Session=Session();
            $emp = $Session->get('emp_details');
            $verified_by = $emp['id'];

			$inputs = arrFilterSanitizeString($this->request->getVar());
			if($inputs["verify"]=="Verify Now")
			{
				$this->db->transBegin();
				$this->water->transBegin();
				$this->trade->transBegin();

				if(isset($inputs["property_trxn_id"])) // property and saf
				$this->model_transaction->CashVerify($inputs["property_trxn_id"], $verified_by);

				if(isset($inputs["gbsaf_trxn_id"]))// gbsaf
				$this->model_govt_saf_transaction->CashVerify($inputs["gbsaf_trxn_id"], $verified_by);

				
				if(isset($inputs["water_trxn_id"]))// water
				$this->Water_Transaction_Model->CashVerify($inputs["water_trxn_id"], $verified_by);

				if(isset($inputs["trade_trxn_id"]))// trade
				$this->TradeTransactionModel->CashVerify($inputs["trade_trxn_id"], $verified_by);


				if ($this->db->transStatus() === FALSE || $this->water->transStatus() === FALSE || $this->trade->transStatus() === FALSE)
				{
					$this->db->transRollback();
					$this->water->transRollback();
					$this->trade->transRollback();
					flashToast("message", "Something went wrong!!!");
				}
				else
				{
					//$this->db->transRollback();
					$this->db->transCommit();
					$this->water->transCommit();
					$this->trade->transCommit();
					flashToast("message", "Cash verified successfully!!!");
				}
			}
		}

		$data["prop_payment"]=$this->model_transaction->PropertyPaymentList($data['tran_by_emp_details_id'], $data['tran_date']);
		$data["saf_payment"]=$this->model_transaction->SafPaymentList($data['tran_by_emp_details_id'], $data['tran_date']);
		$data["gbsaf_payment"]=$this->model_govt_saf_transaction->GBSafPaymentList($data['tran_by_emp_details_id'], $data['tran_date']);
		$data["water_payment"]=$this->Water_Transaction_Model->WaterPaymentList($data['tran_by_emp_details_id'], $data['tran_date']);
		$data["water_conn_payment"]=$this->Water_Transaction_Model->WaterConnectionPaymentList($data['tran_by_emp_details_id'], $data['tran_date']);
		$data["trade_payment"]=$this->TradeTransactionModel->TradePaymentList($data['tran_by_emp_details_id'], $data['tran_date']);
		
		
		$data["emp"]=$this->model_emp_details->getEmpDetailsById(md5($tran_by_emp_details_id));
		
		return view('property/cash_verification/View', $data);
	}


   	public function details()
   	{
   		$data=(array)null;
   		$data['emplist']=$this->model_emp_details->getTCList();
		$data['tran_date']=date('Y-m-d');
   		if($this->request->getMethod()=='post')
   		{
			$data['tran_date']=$this->request->getVar('tran_date');
   		}
		
   		$data['cash_verification_detals']=$this->cash_verification->totalCollectionToday($data['tran_date']);
		//print_var(($data));
   		return view('property/cash_verification', $data);
   	}

   	public function verifyCash($emp_details_id,$trans_date)
   	{
   		$data['trans_date']=$trans_date;
   		$data['cash_verf_details']=$this->cash_verification->totalCashCollectedbyEmpIds($emp_details_id,$data['trans_date']);
   		$data['total_coll']=$data['cash_verf_details']['total'];
   		$data['cash_collected']=$this->cash_verification->totalCashCollectedbyEmpId($emp_details_id,$data['trans_date']);
		//print_var($data['cash_collected']);
   		$prop_where="  where md5(tran_by_emp_details_id::text)='".$emp_details_id."' and tran_date='".$trans_date."' and tran_mode_mstr_id in(2,3) and verify_status is NULL and tbl_transaction.status in(1,2)";
   		$water_where="  where md5(tbl_transaction.emp_details_id::text)='".$emp_details_id."' and transaction_date='".$trans_date."' and upper(payment_mode) in('CHEQUE','DD') and verify_status is NULL  and tbl_transaction.status in(1,2)";
   		$trade_where="  where md5(tbl_transaction.emp_details_id::text)='".$emp_details_id."' and transaction_date='".$trans_date."' and  upper(payment_mode) in('CHEQUE','DD') and verify_status is NULL  and tbl_transaction.status in(1,2)";
   		$gsaf_where="  where md5(tran_by_emp_details_id::text)='".$emp_details_id."' and tran_date='".$trans_date."' and  tran_mode_mstr_id in(2,3) and tran_verification_status is NULL  and tbl_govt_saf_transaction.status in(1,2)";
   		

   		$data['prop_cheque_dtls']=$this->model_transaction->getPropTransactionWithChequeDetails($prop_where);
   		$data['water_cheque_dtls']=$this->Water_Transaction_Model->getTransactionWithChequeDetails($water_where);
   		$data['trade_cheque_dtls']=$this->TradeTransactionModel->getTransactionWithChequeDetails($trade_where);
   		$data['gbsaf_cheque_details']=$this->model_govt_saf_transaction->getTransactionWithChequeDetails($gsaf_where);
   		

   		if($this->request->getMethod()=='post')
   		{
   			$session=session();
            $empDetails = $session->get('emp_details');
            $login_emp_id =$empDetails['id'];
            $verified_date = date('Y-m-d');
            $inputs = arrFilterSanitizeString($this->request->getVar());
			//print_var($inputs);print_var(!isset($inputs['amountVerify']));
            $len = isset($inputs['amountVerify']) && is_array($inputs['amountVerify'])?sizeof($inputs['amountVerify']):0;
            $emp_id=$inputs['employee_id'];
            if($len>0)
            {
            	for($i=0;$i<$len;$i++)
            	{
					
            		//$inputs['amountVerify'][$i];
            		$explode=explode('/', $inputs['amountVerify'][$i]);
            		$module=$explode[1];
            		$transaction_id=$explode[0];

            		if($module=='property')
            		{
            			 $this->model_transaction->updateVerificationStatus($login_emp_id,$transaction_id,$verified_date);
            		}
            		else if($module=='gbsaf')
            		{
            			$this->model_govt_saf_transaction->updateVerificationStatus($login_emp_id,$transaction_id,$verified_date);
            		}
            		else if($module=='water')
            		{
            			$this->Water_Transaction_Model->updateVerificationStatus($login_emp_id,$transaction_id,$verified_date);
            		}
            		else if($module=='trade')
            		{
            			$this->TradeTransactionModel->updateVerificationStatus($login_emp_id,$transaction_id,$verified_date);
            		}

            	}

       		
            }
            if(isset($inputs['cashVerify']) && $inputs['cashVerify']=='cashVerify')
            {
            	
            	$this->model_transaction->updateVerificationStatuCashCollection($emp_id,$verified_date,$login_emp_id,$trans_date);
            	$this->model_govt_saf_transaction->updateVerificationStatuCashCollection($emp_id,$verified_date,$login_emp_id,$trans_date);
            	
                $this->Water_Transaction_Model->updateVerificationStatuCashCollection($emp_id,$verified_date,$login_emp_id,$trans_date);
                $this->TradeTransactionModel->updateVerificationStatuCashCollection($emp_id,$verified_date,$login_emp_id,$trans_date);

            }

            flashToast("message", "Cash Verified Successfully!!!");
   			 return $this->response->redirect(base_url('CashVerification/details'));
   		}

		//print_var($data);
   		return view('property/collection_details',$data);
   		 
   	}
	
	public function update_pament_mode()
	{
		$data = array();	
		$data['transaction']=[];	
		//if()	
		return view('property/update_pament_mode', $data);
	}

	public function ChangePaymentMode_old()
	{
		$data = array();
		$data['transaction']=[];	
		if($this->request->getMethod()=="post")	
		{
			$inputs = arrFilterSanitizeString($this->request->getVar());
			
			//print_var($inputs);
			if(isset($inputs['btn_search']))
			{
				$data['module_name'] = $inputs['module'];
				if($inputs['module']=='property')
				{
					
					$where = "where tran_no = '".trim($inputs['tran_no'])."' and tbl_transaction.tran_type='Property' and tbl_transaction.status!=0" ;
					// $sql = "with owner as ( 
					// 			select tbl_transaction.id,
					// 				string_agg(case when tbl_transaction.tran_type='Property' 
					// 					then tbl_prop_owner_detail.owner_name 
					// 				when  tbl_transaction.tran_type='Saf' 
					// 				then tbl_saf_owner_detail.owner_name else 'xxxxx' end 
					// 						,', ')as applicant_name,
								
					// 				string_agg(case when tbl_transaction.tran_type='Property' 
					// 					then tbl_prop_owner_detail.guardian_name 
					// 				when tbl_transaction.tran_type='Saf' 
					// 				then tbl_saf_owner_detail.guardian_name else 'xxxxx' end 
					// 						,', ')as father_name,
								
					// 			string_agg(case when tbl_transaction.tran_type='Property'
					// 					then tbl_prop_owner_detail.mobile_no::text 
					// 				when tbl_transaction.tran_type='Saf' 
					// 				then tbl_saf_owner_detail.mobile_no::text else 'xxxxx' end 
					// 						,', ')as mobile_no,
								
					// 			string_agg(case when tbl_transaction.tran_type='Property' 
					// 					then tbl_prop_owner_detail.email
					// 				when tbl_transaction.tran_type='Saf' 
					// 				then tbl_saf_owner_detail.email::text else 'xxxxx' end 
					// 						,', ')as email_id
								
					// 			from tbl_transaction 
					// 			left join tbl_prop_owner_detail on tbl_prop_owner_detail.prop_dtl_id = tbl_transaction.prop_dtl_id 
					// 				and tbl_transaction.tran_type='Property' and tbl_prop_owner_detail.status = 1
					// 			left join tbl_saf_owner_detail on tbl_saf_owner_detail.saf_dtl_id = tbl_transaction.prop_dtl_id 
					// 				and tbl_transaction.tran_type='Saf' and tbl_saf_owner_detail.status = 1
					// 			$where							
					// 			group by tbl_transaction.id
					// 		)
					// 		select tbl_transaction.id, 'property' as transaction_type, 
					// 			view_emp_details.emp_name,
					// 			case when tbl_transaction.tran_type='Property' then tbl_prop_dtl.new_holding_no
					// 				when tbl_transaction.tran_type='Saf' then tbl_saf_dtl.saf_no else 'xxxxx' end as holding_no,
					// 			tbl_transaction.tran_no as transaction_no,tbl_transaction.tran_date as transaction_date,
					// 			tbl_transaction.tran_mode as payment_mode,tbl_transaction.payable_amt as paid_amount,
					// 			owner.applicant_name,owner.father_name,owner.mobile_no,owner.email_id
					// 		from tbl_transaction 						
					// 		join view_emp_details on view_emp_details.id = tbl_transaction.tran_by_emp_details_id
					// 		join owner on owner.id = tbl_transaction.id
					// 		left join tbl_prop_dtl on tbl_prop_dtl.id = tbl_transaction.prop_dtl_id and tbl_transaction.tran_type='Property'
					// 		left join tbl_saf_dtl on tbl_saf_dtl.id = tbl_transaction.prop_dtl_id and tbl_transaction.tran_type='Saf'
					// 		$where";

					$sql = "with owner as ( 
						select tbl_transaction.id,
							string_agg(tbl_prop_owner_detail.owner_name,', ')as applicant_name,
							string_agg(tbl_prop_owner_detail.guardian_name,', ')as father_name,
						string_agg(tbl_prop_owner_detail.mobile_no::text,', ')as mobile_no,
						string_agg(tbl_prop_owner_detail.email,', ')as email_id
						from tbl_transaction 
						left join tbl_prop_owner_detail on tbl_prop_owner_detail.prop_dtl_id = tbl_transaction.prop_dtl_id 
							and tbl_transaction.tran_type='Property' and tbl_prop_owner_detail.status = 1
						$where							
						group by tbl_transaction.id
					)
					select tbl_transaction.id, 'property' as transaction_type, 
						view_emp_details.emp_name,tbl_prop_dtl.new_holding_no
							as holding_no,
						tbl_transaction.tran_no as transaction_no,tbl_transaction.tran_date as transaction_date,
						tbl_transaction.tran_mode as payment_mode,tbl_transaction.payable_amt as paid_amount,
						owner.applicant_name,owner.father_name,owner.mobile_no,owner.email_id,tbl_cheque_details.cheque_no,tbl_cheque_details.id as cheque_tbl_id
					from tbl_transaction 
					left join tbl_cheque_details on tbl_transaction.id=tbl_cheque_details.transaction_id		
					join view_emp_details on view_emp_details.id = tbl_transaction.tran_by_emp_details_id
					join owner on owner.id = tbl_transaction.id
					left join tbl_prop_dtl on tbl_prop_dtl.id = tbl_transaction.prop_dtl_id and tbl_transaction.tran_type='Property'
					$where";
					$transaction = $this->model_transaction->row_sql($sql);							
					
				}else if($inputs['module']=='saf'){

					$where = "where tran_no = '".trim($inputs['tran_no'])."' and tbl_transaction.tran_type='Saf'" ;
					$sql = "with owner as ( 
								select tbl_transaction.id,
									string_agg(tbl_saf_owner_detail.owner_name,', ')as applicant_name,
								
									string_agg(tbl_saf_owner_detail.guardian_name,', ')as father_name,
								
								string_agg(tbl_saf_owner_detail.mobile_no::text,', ')as mobile_no,
								
								string_agg(tbl_saf_owner_detail.email::text,', ')as email_id
								
								from tbl_transaction
								left join tbl_saf_owner_detail on tbl_saf_owner_detail.saf_dtl_id = tbl_transaction.prop_dtl_id 
									and tbl_transaction.tran_type='Saf' and tbl_saf_owner_detail.status = 1
								$where							
								group by tbl_transaction.id
							)
							select tbl_transaction.id, 'property' as transaction_type, 
								view_emp_details.emp_name,tbl_saf_dtl.saf_no as holding_no,
								tbl_transaction.tran_no as transaction_no,tbl_transaction.tran_date as transaction_date,
								tbl_transaction.tran_mode as payment_mode,tbl_transaction.payable_amt as paid_amount,
								owner.applicant_name,owner.father_name,owner.mobile_no,owner.email_id,tbl_cheque_details.cheque_no,tbl_cheque_details.id as cheque_tbl_id
							from tbl_transaction
							left join tbl_cheque_details on tbl_transaction.id=tbl_cheque_details.transaction_id
							join view_emp_details on view_emp_details.id = tbl_transaction.tran_by_emp_details_id
							join owner on owner.id = tbl_transaction.id
							left join tbl_saf_dtl on tbl_saf_dtl.id = tbl_transaction.prop_dtl_id and tbl_transaction.tran_type='Saf'
							$where";

					
					$transaction = $this->model_transaction->row_sql($sql);	
				}
				elseif($inputs['module']=='gov_tr')
				{
					$where = "where tran_no = '".trim($inputs['tran_no'])."' ";
					$sql = "with owner as ( 
								select tbl_govt_saf_transaction.id,
									string_agg( tbl_govt_saf_officer_dtl.officer_name ,', ')as applicant_name,
									string_agg( tbl_govt_saf_officer_dtl.designation ,', ')as father_name,
									string_agg( tbl_govt_saf_officer_dtl.mobile_no::text ,', ')as mobile_no,
									string_agg(tbl_govt_saf_officer_dtl.email_id::text ,', ')as email_id
								from tbl_govt_saf_transaction 
								left join tbl_govt_saf_officer_dtl on tbl_govt_saf_officer_dtl.govt_saf_dtl_id = tbl_govt_saf_transaction.govt_saf_dtl_id 
									and tbl_govt_saf_officer_dtl.status =1
								$where
								group by tbl_govt_saf_transaction.id
							)
							select tbl_govt_saf_transaction.id, 'gov_tr' as transaction_type,								
								view_emp_details.emp_name,
								tbl_govt_saf_dtl.application_no as holding_no,
								tbl_govt_saf_transaction.tran_no as transaction_no,tbl_govt_saf_transaction.tran_date as transaction_date,
								tbl_govt_saf_transaction.tran_mode as payment_mode,tbl_govt_saf_transaction.payable_amt as paid_amount,
								owner.applicant_name,owner.father_name,owner.mobile_no,owner.email_id,tbl_govt_saf_transaction_details.cheque_no,tbl_govt_saf_transaction_details.id as cheque_tbl_id
							from tbl_govt_saf_transaction
							left join tbl_govt_saf_transaction_details on tbl_govt_saf_transaction.id=tbl_govt_saf_transaction_details.govt_saf_transaction_id
							join view_emp_details on view_emp_details.id = tbl_govt_saf_transaction.tran_by_emp_details_id
							join owner on owner.id = tbl_govt_saf_transaction.id
							left join tbl_govt_saf_dtl	on tbl_govt_saf_dtl.id = tbl_govt_saf_transaction.govt_saf_dtl_id
							$where ";
					$transaction = $this->model_transaction->row_sql($sql);
				}
				elseif($inputs['module']=='water')
				{
					$where = "where transaction_no = '".trim($inputs['tran_no'])."' ";
					$sql = " with owner as ( 
								select tbl_transaction.id,
									string_agg(case when tbl_transaction.transaction_type='Demand Collection' 
										then tbl_consumer_details.applicant_name 
									when tbl_transaction.transaction_type in('New Connection','Site Inspection','Penlaty Instalment') 
									then tbl_applicant_details.applicant_name else 'xxxxx' end 
											,', ')as applicant_name,
									string_agg(case when tbl_transaction.transaction_type='Demand Collection' 
										then tbl_consumer_details.father_name 
									when tbl_transaction.transaction_type in('New Connection','Site Inspection','Penlaty Instalment') 
									then tbl_applicant_details.father_name else 'xxxxx' end 
											,', ')as father_name,
								string_agg(case when tbl_transaction.transaction_type='Demand Collection' 
										then tbl_consumer_details.mobile_no::text 
									when tbl_transaction.transaction_type in('New Connection','Site Inspection','Penlaty Instalment') 
									then tbl_applicant_details.mobile_no::text else 'xxxxx' end 
											,', ')as mobile_no,
								string_agg(case when tbl_transaction.transaction_type='Demand Collection' 
										then 'xxxxx'::text 
									when tbl_transaction.transaction_type in('New Connection','Site Inspection','Penlaty Instalment') 
									then tbl_applicant_details.email_id::text else 'xxxxx' end 
											,', ')as email_id
								from tbl_transaction 
								left join tbl_consumer_details on tbl_consumer_details.consumer_id = tbl_transaction.related_id 
								and tbl_transaction.transaction_type='Demand Collection' 
								left join tbl_applicant_details on tbl_applicant_details.apply_connection_id = tbl_transaction.related_id 
								and tbl_transaction.transaction_type in('New Connection','Site Inspection','Penlaty Instalment')
								$where
								group by tbl_transaction.id
							)
							select tbl_transaction.id, 'water' as transaction_type,
								view_emp_details.emp_name,
								case when tbl_transaction.transaction_type='Demand Collection' then tbl_consumer.consumer_no
									when tbl_transaction.transaction_type in('New Connection','Site Inspection','Penlaty Instalment')
										then tbl_apply_water_connection.application_no else 'xxxxx' end as holding_no,
								tbl_transaction.transaction_no as transaction_no,tbl_transaction.transaction_date as transaction_date,
								tbl_transaction.payment_mode as payment_mode,tbl_transaction.paid_amount as paid_amount,
								owner.applicant_name,owner.father_name,owner.mobile_no,owner.email_id,tbl_cheque_details.cheque_no,tbl_cheque_details.id as cheque_tbl_id
							from tbl_transaction
							left join tbl_cheque_details on tbl_transaction.id=tbl_cheque_details.transaction_id 		
							join view_emp_details on view_emp_details.id = tbl_transaction.emp_details_id
							join owner on owner.id = tbl_transaction.id
							left join tbl_consumer on tbl_consumer.id = tbl_transaction.related_id and tbl_transaction.transaction_type='Demand Collection'
							left join tbl_apply_water_connection on tbl_apply_water_connection.id = tbl_transaction.related_id 
								and tbl_transaction.transaction_type in('New Connection','Site Inspection','Penlaty Instalment')
							$where ";
					$transaction = $this->Water_Transaction_Model->row_sql($sql);	
				}
				elseif($inputs['module']=='trade')
				{
					$where = "where transaction_no = '".trim($inputs['tran_no'])."' ";

					$sql = " with owner as ( 
								select tbl_transaction.id,
									string_agg( tbl_firm_owner_name.owner_name ,', ')as applicant_name,
									string_agg( tbl_firm_owner_name.guardian_name ,', ')as father_name,
									string_agg( tbl_firm_owner_name.mobile::text ,', ')as mobile_no,
									string_agg(tbl_firm_owner_name.emailid::text ,', ')as email_id
								from tbl_transaction 
								left join tbl_firm_owner_name on tbl_firm_owner_name.apply_licence_id = tbl_transaction.related_id 
								$where
								group by tbl_transaction.id
							)
							select tbl_transaction.id,  'trade' as transaction_type,
								view_emp_details.emp_name,
								tbl_apply_licence.application_no as holding_no,
								tbl_transaction.transaction_no as transaction_no,tbl_transaction.transaction_date as transaction_date,
								tbl_transaction.payment_mode as payment_mode,tbl_transaction.paid_amount as paid_amount,
								owner.applicant_name,owner.father_name,owner.mobile_no,owner.email_id,tbl_cheque_dtl.cheque_no,tbl_cheque_dtl.id as cheque_tbl_id
							from tbl_transaction
							left join tbl_cheque_dtl on tbl_transaction.id=tbl_cheque_dtl.transaction_id 		
							join view_emp_details on view_emp_details.id = tbl_transaction.emp_details_id
							join owner on owner.id = tbl_transaction.id
							left join tbl_apply_licence on tbl_apply_licence.id = tbl_transaction.related_id 
							left join tbl_firm_owner_name on tbl_firm_owner_name.apply_licence_id = tbl_transaction.related_id	
							$where ";
					$transaction = $this->TradeTransactionModel->row_query($sql);	
				}
				$data['transaction']=$transaction;
			}
			else if(isset($inputs['update'])) 
			{
				$table = "";
				$db = '';
				$clear = 1;
				if(in_array($inputs['payment_mode'],['CHEQUE','DD']))
				{
					$clear = 2;
				}
				if($inputs['type']=='property')
				{
					$table = " tbl_transaction ";
					$db=$this->db;
					$transe = $this->model_transaction;
					$column = 'tran_mode';
					$status = "status";
					$ch_tbl = "tbl_cheque_details";
					$foringe_key = "prop_dtl_id";
					$tr_column ="transaction_id";
					$ch_dtl = [
						'prop_dtl_id'=>'',
						'transaction_id'=>$inputs['transaction_id'],
						'cheque_date'=>$inputs['chq_date']??null,
						'bank_name'=>$inputs['bank_name']??null,
						'branch_name'=>$inputs['branch_name']??null,
						'status'=>2,
						'cheque_no'=>$inputs['chq_no']??null,
					];

				}
				elseif($inputs['type']=='gov_tr')
				{
					$table = " tbl_govt_saf_transaction ";
					$db=$this->db;
					$transe = $this->model_transaction;
					$column = 'tran_mode';
					$status = "status";
					$ch_tbl = "tbl_govt_saf_transaction";
					$foringe_key="govt_saf_dtl_id";
					$tr_column ="govt_saf_transaction_id";
					$ch_dtl = [
						'govt_saf_dtl_id'=>'',
						'govt_saf_transaction_id'=>$inputs['transaction_id'],
						'cheque_date'=>$inputs['chq_date']??null,
						'bank_name'=>$inputs['bank_name']??null,
						'branch_name'=>$inputs['branch_name']??null,
						'status'=>2,
						'cheque_no'=>$inputs['chq_no']??null,
					];
				}					
				elseif($inputs['type']=='water')
				{
					$table = " tbl_transaction ";
					$db=$this->water;
					$transe = $this->Water_Transaction_Model;
					$column = 'payment_mode';
					$status = "status";
					$ch_tbl = "tbl_cheque_details";
					$foringe_key="emp_details_id";
					$tr_column ="transaction_id";
					$ch_dtl = [						
						'transaction_id'=>$inputs['transaction_id'],
						'cheque_date'=>$inputs['chq_date']??null,
						'bank_name'=>$inputs['bank_name']??null,
						'branch_name'=>$inputs['branch_name']??null,
						'status'=>2,
						'cheque_no'=>$inputs['chq_no']??null,
						'emp_details_id'=>''
					];
				}					
				elseif($inputs['type']=='trade')
				{
					$table = " tbl_transaction ";
					$db=$this->trade;
					$transe = $this->TradeTransactionModel;
					$column = 'payment_mode';
					$status = "status";
					$ch_tbl = "tbl_cheque_dtl";
					$foringe_key="emp_details_id";
					$tr_column ="transaction_id";
					$ch_dtl = [						
						'transaction_id'=>$inputs['transaction_id'],
						'cheque_date'=>$inputs['chq_date']??null,
						'bank_name'=>$inputs['bank_name']??null,
						'branch_name'=>$inputs['branch_name']??null,
						'status'=>2,
						'cheque_no'=>$inputs['chq_no']??null,
						'created_on'=>'NOW()',
						'emp_details_id'=>''
					];
				}

				try{
					if($table=='')
					{
						throw new Exception('Some Error Ocurs Please Contact To Admin');
					}
					else
					{
						$db->transBegin();					
						$checksql = " select * from $table where id = ".$inputs['transaction_id']." and verify_status isnull ";						
						if(method_exists($transe, 'row_sql'))
						{
							$transaction = $transe->row_sql($checksql)[0]??array();
							//print_var($transaction);
						}
						else
						{
							$transaction = $transe->row_query($checksql)[0]??array();
							//print_var($transaction);
						}
						if($transaction && trim(strtoupper($transaction[$column]))!=trim(strtoupper($inputs['payment_mode'])))
						{	
							$updatsql = "update $table set $column = '".trim($inputs['payment_mode'])."',$status= '$clear', 
										remarks='".(in_array($inputs['type'],['property','gov_tr'])?($transaction['remarks']." (".$inputs['remarks'].")"):$inputs['remarks'])."' 
								where id = ".$inputs['transaction_id']." and verify_status isnull " ;
							//print_var($updatsql);
							if($clear==2)
							{
								$ch_dtl[$foringe_key]=$transaction[$foringe_key];
							}
							$i=1;
							$keys = "";
							$values = "";
							foreach($ch_dtl as $key => $val )
							{
								if($i==1)
								{
									$keys = "( $key ";
									$values = "( '$val'";
									$i=2;
								}
								else
								{
									$keys .= ", $key ";
									$values.= ", '$val'";
								}
							}
							$keys.=" ) ";
							$values.= " ) ";
							$ch_sql = " insert into $ch_tbl $keys values $values ;";

							if(method_exists($transe, 'row_sql'))
							{
								$transe->row_sql($updatsql);														
								if(in_array(trim(strtoupper($transaction[$column])),['CHEQUE','DD']))
								{
									$ch_update_sql = " update $ch_tbl set status = 0 where $tr_column = ".$inputs['transaction_id'];
									$transe->row_sql($ch_update_sql);
								}
								if($clear==2)
								{
									$transe->row_sql($ch_sql);
								}
							}
							else
							{
								$transe->row_query($updatsql);
								if(in_array(trim(strtoupper($transaction[$column])),['CHEQUE','DD']))
								{
									$ch_update_sql = " update $ch_tbl set status = 0 where $tr_column = ".$inputs['transaction_id'];
									$transe->row_query($ch_update_sql);
								}
								if($clear==2)
								{
									$transe->row_query($ch_sql);
								}
							}
						}
						else
						{ 
							throw new Exception('Cant Not Update Same Payment Mode');
						}						
						if ($db->transStatus() === FALSE)
						{
							$db->transRollback();
							flashToast("message", "Oops, Some Error Occures Please Contact To Admin .");
						}
						else
						{
							$db->transCommit();
							flashToast("message", "	Payment Mode Update successfully.");
						}


					}
					

				}
				catch (Exception $e)
				{					
					flashToast("message", $e->getMessage());

				}
					
			}			
			else if(isset($inputs['update_cheque_no'])) 
			{
				 $module = $this->request->getVar('module_name');
				 $cheque_tbl_id = $this->request->getVar('cheque_tbl_id');
				 $cheuqe_no_to_update = $this->request->getVar('cheque_dd_neft_no_value');

				if($module=='property')
				{
					$transaction = $this->model_transaction->update_cheque($cheque_tbl_id,$cheuqe_no_to_update);
					flashToast("message", "Cheque no. update successfully !!");
					
				}else if($module=='saf'){
					$transaction = $this->model_transaction->update_cheque($cheque_tbl_id,$cheuqe_no_to_update);
					flashToast("message", "Cheque no. update successfully !!");

				}
				elseif($module=='gov_tr')
				{
					$transaction = $this->model_transaction->update_gb_cheque($cheque_tbl_id,$cheuqe_no_to_update);
					flashToast("message", "Cheque no. update successfully !!");

					
				}
				elseif($module=='water')
				{
					$transaction = $this->Water_Transaction_Model->update_water_cheque($cheque_tbl_id,
					$cheuqe_no_to_update);	
					flashToast("message", "Cheque no. update successfully !!");

				}
				elseif($module=='trade')
				{
					$transaction = $this->TradeTransactionModel->update_trade_cheque($cheque_tbl_id,$cheuqe_no_to_update);
					flashToast("message", "Cheque no. update successfully !!");

						
				}
					
			}			

			
			

		}
		// print_var($data);
		// die;
		return view('property/update_pament_mode', $data);
	}

	public function ChangePaymentMode()
	{
		$data = array();
		$session = session();
		$emp_mstr = $session->get("emp_details");

		$user_mstr_id = $emp_mstr["user_type_mstr_id"];
		if($user_mstr_id=="4")
		{
			return redirect()->to('/home');
		}

		$data['transaction']=[];	
		if($this->request->getMethod()=="post")	
		{
			$inputs = arrFilterSanitizeString($this->request->getVar());
			
			//print_var($inputs);
			if(isset($inputs['btn_search']))
			{
				$data['module_name'] = $inputs['module'];
				if($inputs['module']=='property')
				{
					
					$where = "where tran_no = '".trim($inputs['tran_no'])."' and tbl_transaction.tran_type='Property' and tbl_transaction.status!=0" ;
					

					$sql = "with owner as ( 
						select tbl_transaction.id,
							string_agg(tbl_prop_owner_detail.owner_name,', ')as applicant_name,
							string_agg(tbl_prop_owner_detail.guardian_name,', ')as father_name,
						string_agg(tbl_prop_owner_detail.mobile_no::text,', ')as mobile_no,
						string_agg(tbl_prop_owner_detail.email,', ')as email_id
						from tbl_transaction 
						left join tbl_prop_owner_detail on tbl_prop_owner_detail.prop_dtl_id = tbl_transaction.prop_dtl_id 
							and tbl_transaction.tran_type='Property' and tbl_prop_owner_detail.status = 1
						$where							
						group by tbl_transaction.id
					)
					select tbl_transaction.id, 'property' as transaction_type, 
						view_emp_details.emp_name,tbl_prop_dtl.new_holding_no
							as holding_no,
						tbl_transaction.tran_no as transaction_no,tbl_transaction.tran_date as transaction_date,
						tbl_transaction.tran_mode as payment_mode,tbl_transaction.payable_amt as paid_amount,
						owner.applicant_name,owner.father_name,owner.mobile_no,owner.email_id,tbl_cheque_details.cheque_no,tbl_cheque_details.id as cheque_tbl_id,
						tbl_cheque_details.cheque_date,tbl_cheque_details.bank_name,tbl_cheque_details.branch_name
					from tbl_transaction 
					left join tbl_cheque_details on tbl_transaction.id=tbl_cheque_details.transaction_id		
					join view_emp_details on view_emp_details.id = tbl_transaction.tran_by_emp_details_id
					join owner on owner.id = tbl_transaction.id
					left join tbl_prop_dtl on tbl_prop_dtl.id = tbl_transaction.prop_dtl_id and tbl_transaction.tran_type='Property'
					$where";
					$transaction = $this->model_transaction->row_sql($sql);							
					
				}else if($inputs['module']=='saf'){

					$where = "where tran_no = '".trim($inputs['tran_no'])."' and tbl_transaction.tran_type='Saf'" ;
					$sql = "with owner as ( 
								select tbl_transaction.id,
									string_agg(tbl_saf_owner_detail.owner_name,', ')as applicant_name,
								
									string_agg(tbl_saf_owner_detail.guardian_name,', ')as father_name,
								
								string_agg(tbl_saf_owner_detail.mobile_no::text,', ')as mobile_no,
								
								string_agg(tbl_saf_owner_detail.email::text,', ')as email_id
								
								from tbl_transaction
								left join tbl_saf_owner_detail on tbl_saf_owner_detail.saf_dtl_id = tbl_transaction.prop_dtl_id 
									and tbl_transaction.tran_type='Saf' and tbl_saf_owner_detail.status = 1
								$where							
								group by tbl_transaction.id
							)
							select tbl_transaction.id, 'property' as transaction_type, 
								view_emp_details.emp_name,tbl_saf_dtl.saf_no as holding_no,
								tbl_transaction.tran_no as transaction_no,tbl_transaction.tran_date as transaction_date,
								tbl_transaction.tran_mode as payment_mode,tbl_transaction.payable_amt as paid_amount,
								owner.applicant_name,owner.father_name,owner.mobile_no,owner.email_id,tbl_cheque_details.cheque_no,tbl_cheque_details.id as cheque_tbl_id,
								tbl_cheque_details.cheque_date,tbl_cheque_details.bank_name,tbl_cheque_details.branch_name
							from tbl_transaction
							left join tbl_cheque_details on tbl_transaction.id=tbl_cheque_details.transaction_id
							join view_emp_details on view_emp_details.id = tbl_transaction.tran_by_emp_details_id
							join owner on owner.id = tbl_transaction.id
							left join tbl_saf_dtl on tbl_saf_dtl.id = tbl_transaction.prop_dtl_id and tbl_transaction.tran_type='Saf'
							$where";

					
					$transaction = $this->model_transaction->row_sql($sql);	
				}
				elseif($inputs['module']=='gov_tr')
				{
					$where = "where tran_no = '".trim($inputs['tran_no'])."' ";
					$sql = "with owner as ( 
								select tbl_govt_saf_transaction.id,
									string_agg( tbl_govt_saf_officer_dtl.officer_name ,', ')as applicant_name,
									string_agg( tbl_govt_saf_officer_dtl.designation ,', ')as father_name,
									string_agg( tbl_govt_saf_officer_dtl.mobile_no::text ,', ')as mobile_no,
									string_agg(tbl_govt_saf_officer_dtl.email_id::text ,', ')as email_id
								from tbl_govt_saf_transaction 
								left join tbl_govt_saf_officer_dtl on tbl_govt_saf_officer_dtl.govt_saf_dtl_id = tbl_govt_saf_transaction.govt_saf_dtl_id 
									and tbl_govt_saf_officer_dtl.status =1
								$where
								group by tbl_govt_saf_transaction.id
							)
							select tbl_govt_saf_transaction.id, 'gov_tr' as transaction_type,								
								view_emp_details.emp_name,
								tbl_govt_saf_dtl.application_no as holding_no,
								tbl_govt_saf_transaction.tran_no as transaction_no,tbl_govt_saf_transaction.tran_date as transaction_date,
								tbl_govt_saf_transaction.tran_mode as payment_mode,tbl_govt_saf_transaction.payable_amt as paid_amount,
								owner.applicant_name,owner.father_name,owner.mobile_no,owner.email_id,tbl_govt_saf_transaction_details.cheque_no,tbl_govt_saf_transaction_details.id as cheque_tbl_id,
								tbl_govt_saf_transaction_details.cheque_date,tbl_govt_saf_transaction_details.bank_name,tbl_govt_saf_transaction_details.branch_name
							from tbl_govt_saf_transaction
							left join tbl_govt_saf_transaction_details on tbl_govt_saf_transaction.id=tbl_govt_saf_transaction_details.govt_saf_transaction_id
							join view_emp_details on view_emp_details.id = tbl_govt_saf_transaction.tran_by_emp_details_id
							join owner on owner.id = tbl_govt_saf_transaction.id
							left join tbl_govt_saf_dtl	on tbl_govt_saf_dtl.id = tbl_govt_saf_transaction.govt_saf_dtl_id
							$where ";
					$transaction = $this->model_transaction->row_sql($sql);
				}
				elseif($inputs['module']=='water')
				{
					$where = "where transaction_no = '".trim($inputs['tran_no'])."' ";
					$sql = " with owner as ( 
								select tbl_transaction.id,
									string_agg(case when tbl_transaction.transaction_type='Demand Collection' 
										then tbl_consumer_details.applicant_name 
									when tbl_transaction.transaction_type in('New Connection','Site Inspection','Penlaty Instalment') 
									then tbl_applicant_details.applicant_name else 'xxxxx' end 
											,', ')as applicant_name,
									string_agg(case when tbl_transaction.transaction_type='Demand Collection' 
										then tbl_consumer_details.father_name 
									when tbl_transaction.transaction_type in('New Connection','Site Inspection','Penlaty Instalment') 
									then tbl_applicant_details.father_name else 'xxxxx' end 
											,', ')as father_name,
								string_agg(case when tbl_transaction.transaction_type='Demand Collection' 
										then tbl_consumer_details.mobile_no::text 
									when tbl_transaction.transaction_type in('New Connection','Site Inspection','Penlaty Instalment') 
									then tbl_applicant_details.mobile_no::text else 'xxxxx' end 
											,', ')as mobile_no,
								string_agg(case when tbl_transaction.transaction_type='Demand Collection' 
										then 'xxxxx'::text 
									when tbl_transaction.transaction_type in('New Connection','Site Inspection','Penlaty Instalment') 
									then tbl_applicant_details.email_id::text else 'xxxxx' end 
											,', ')as email_id
								from tbl_transaction 
								left join tbl_consumer_details on tbl_consumer_details.consumer_id = tbl_transaction.related_id 
								and tbl_transaction.transaction_type='Demand Collection' 
								left join tbl_applicant_details on tbl_applicant_details.apply_connection_id = tbl_transaction.related_id 
								and tbl_transaction.transaction_type in('New Connection','Site Inspection','Penlaty Instalment')
								$where
								group by tbl_transaction.id
							)
							select tbl_transaction.id, 'water' as transaction_type,
								view_emp_details.emp_name,
								case when tbl_transaction.transaction_type='Demand Collection' then tbl_consumer.consumer_no
									when tbl_transaction.transaction_type in('New Connection','Site Inspection','Penlaty Instalment')
										then tbl_apply_water_connection.application_no else 'xxxxx' end as holding_no,
								tbl_transaction.transaction_no as transaction_no,tbl_transaction.transaction_date as transaction_date,
								tbl_transaction.payment_mode as payment_mode,tbl_transaction.paid_amount as paid_amount,
								owner.applicant_name,owner.father_name,owner.mobile_no,owner.email_id,tbl_cheque_details.cheque_no,tbl_cheque_details.id as cheque_tbl_id,
								tbl_cheque_details.cheque_date,tbl_cheque_details.bank_name,tbl_cheque_details.branch_name
							from tbl_transaction
							left join tbl_cheque_details on tbl_transaction.id=tbl_cheque_details.transaction_id 		
							join view_emp_details on view_emp_details.id = tbl_transaction.emp_details_id
							join owner on owner.id = tbl_transaction.id
							left join tbl_consumer on tbl_consumer.id = tbl_transaction.related_id and tbl_transaction.transaction_type='Demand Collection'
							left join tbl_apply_water_connection on tbl_apply_water_connection.id = tbl_transaction.related_id 
								and tbl_transaction.transaction_type in('New Connection','Site Inspection','Penlaty Instalment')
							$where ";
					$transaction = $this->Water_Transaction_Model->row_sql($sql);	
				}
				elseif($inputs['module']=='trade')
				{
					$where = "where transaction_no = '".trim($inputs['tran_no'])."' ";

					$sql = " with owner as ( 
								select tbl_transaction.id,
									string_agg( tbl_firm_owner_name.owner_name ,', ')as applicant_name,
									string_agg( tbl_firm_owner_name.guardian_name ,', ')as father_name,
									string_agg( tbl_firm_owner_name.mobile::text ,', ')as mobile_no,
									string_agg(tbl_firm_owner_name.emailid::text ,', ')as email_id
								from tbl_transaction 
								left join tbl_firm_owner_name on tbl_firm_owner_name.apply_licence_id = tbl_transaction.related_id 
								$where
								group by tbl_transaction.id
							)
							select tbl_transaction.id,  'trade' as transaction_type,
								view_emp_details.emp_name,
								tbl_apply_licence.application_no as holding_no,
								tbl_transaction.transaction_no as transaction_no,tbl_transaction.transaction_date as transaction_date,
								tbl_transaction.payment_mode as payment_mode,tbl_transaction.paid_amount as paid_amount,
								owner.applicant_name,owner.father_name,owner.mobile_no,owner.email_id,tbl_cheque_dtl.cheque_no,tbl_cheque_dtl.id as cheque_tbl_id,
								tbl_cheque_dtl.cheque_date,tbl_cheque_dtl.bank_name,tbl_cheque_dtl.branch_name
							from tbl_transaction
							left join tbl_cheque_dtl on tbl_transaction.id=tbl_cheque_dtl.transaction_id 		
							join view_emp_details on view_emp_details.id = tbl_transaction.emp_details_id
							join owner on owner.id = tbl_transaction.id
							left join tbl_apply_licence on tbl_apply_licence.id = tbl_transaction.related_id 
							left join tbl_firm_owner_name on tbl_firm_owner_name.apply_licence_id = tbl_transaction.related_id	
							$where ";
					$transaction = $this->TradeTransactionModel->row_query($sql);	
				}
				$data['transaction']=$transaction;
			}
			else if(isset($inputs['update'])) 
			{
				$table = "";
				$db = '';
				$clear = 1;
				if(in_array($inputs['payment_mode'],['CHEQUE','DD']))
				{
					$clear = 2;
				}
				if($inputs['type']=='property')
				{
					$table = " tbl_transaction ";
					$db=$this->db;
					$transe = $this->model_transaction;
					$column = 'tran_mode';
					$status = "status";
					$ch_tbl = "tbl_cheque_details";
					$foringe_key = "prop_dtl_id";
					$tr_column ="transaction_id";
					$ch_dtl = [
						'prop_dtl_id'=>'',
						'transaction_id'=>$inputs['transaction_id'],
						'cheque_date'=>$inputs['chq_date']??null,
						'bank_name'=>$inputs['bank_name']??null,
						'branch_name'=>$inputs['branch_name']??null,
						'status'=>2,
						'cheque_no'=>$inputs['chq_no']??null,
					];

				}
				elseif($inputs['type']=='gov_tr')
				{
					$table = " tbl_govt_saf_transaction ";
					$db=$this->db;
					$transe = $this->model_transaction;
					$column = 'tran_mode';
					$status = "status";
					$ch_tbl = "tbl_govt_saf_transaction";
					$foringe_key="govt_saf_dtl_id";
					$tr_column ="govt_saf_transaction_id";
					$ch_dtl = [
						'govt_saf_dtl_id'=>'',
						'govt_saf_transaction_id'=>$inputs['transaction_id'],
						'cheque_date'=>$inputs['chq_date']??null,
						'bank_name'=>$inputs['bank_name']??null,
						'branch_name'=>$inputs['branch_name']??null,
						'status'=>2,
						'cheque_no'=>$inputs['chq_no']??null,
					];
				}					
				elseif($inputs['type']=='water')
				{
					$table = " tbl_transaction ";
					$db=$this->water;
					$transe = $this->Water_Transaction_Model;
					$column = 'payment_mode';
					$status = "status";
					$ch_tbl = "tbl_cheque_details";
					$foringe_key="emp_details_id";
					$tr_column ="transaction_id";
					$ch_dtl = [						
						'transaction_id'=>$inputs['transaction_id'],
						'cheque_date'=>$inputs['chq_date']??null,
						'bank_name'=>$inputs['bank_name']??null,
						'branch_name'=>$inputs['branch_name']??null,
						'status'=>2,
						'cheque_no'=>$inputs['chq_no']??null,
						'emp_details_id'=>''
					];
				}					
				elseif($inputs['type']=='trade')
				{
					$table = " tbl_transaction ";
					$db=$this->trade;
					$transe = $this->TradeTransactionModel;
					$column = 'payment_mode';
					$status = "status";
					$ch_tbl = "tbl_cheque_dtl";
					$foringe_key="emp_details_id";
					$tr_column ="transaction_id";
					$ch_dtl = [						
						'transaction_id'=>$inputs['transaction_id'],
						'cheque_date'=>$inputs['chq_date']??null,
						'bank_name'=>$inputs['bank_name']??null,
						'branch_name'=>$inputs['branch_name']??null,
						'status'=>2,
						'cheque_no'=>$inputs['chq_no']??null,
						'created_on'=>'NOW()',
						'emp_details_id'=>''
					];
				}

				try{
					if($table=='')
					{
						throw new Exception('Some Error Ocurs Please Contact To Admin');
					}
					else
					{
						$db->transBegin();					
						$checksql = " select * from $table where id = ".$inputs['transaction_id']." and verify_status isnull ";						
						if(method_exists($transe, 'row_sql'))
						{
							$transaction = $transe->row_sql($checksql)[0]??array();
							//print_var($transaction);
						}
						else
						{
							$transaction = $transe->row_query($checksql)[0]??array();
							//print_var($transaction);
						}
						if($transaction && trim(strtoupper($transaction[$column]))!=trim(strtoupper($inputs['payment_mode'])))
						{	
							$updatsql = "update $table set $column = '".trim($inputs['payment_mode'])."',$status= '$clear', 
										remarks='".(in_array($inputs['type'],['property','gov_tr'])?($transaction['remarks']." (".$inputs['remarks'].")"):$inputs['remarks'])."' 
								where id = ".$inputs['transaction_id']." and verify_status isnull " ;
							//print_var($updatsql);
							if($clear==2)
							{
								$ch_dtl[$foringe_key]=$transaction[$foringe_key];
							}
							$i=1;
							$keys = "";
							$values = "";
							foreach($ch_dtl as $key => $val )
							{
								if($i==1)
								{
									$keys = "( $key ";
									$values = "( '$val'";
									$i=2;
								}
								else
								{
									$keys .= ", $key ";
									$values.= ", '$val'";
								}
							}
							$keys.=" ) ";
							$values.= " ) ";
							$ch_sql = " insert into $ch_tbl $keys values $values ;";

							if(method_exists($transe, 'row_sql'))
							{
								$transe->row_sql($updatsql);														
								if(in_array(trim(strtoupper($transaction[$column])),['CHEQUE','DD']))
								{
									$ch_update_sql = " update $ch_tbl set status = 0 where $tr_column = ".$inputs['transaction_id'];
									$transe->row_sql($ch_update_sql);
								}
								if($clear==2)
								{
									$transe->row_sql($ch_sql);
								}
							}
							else
							{
								$transe->row_query($updatsql);
								if(in_array(trim(strtoupper($transaction[$column])),['CHEQUE','DD']))
								{
									$ch_update_sql = " update $ch_tbl set status = 0 where $tr_column = ".$inputs['transaction_id'];
									$transe->row_query($ch_update_sql);
								}
								if($clear==2)
								{
									$transe->row_query($ch_sql);
								}
							}
						}
						else
						{ 
							throw new Exception('Cant Not Update Same Payment Mode');
						}						
						if ($db->transStatus() === FALSE)
						{
							$db->transRollback();
							flashToast("message", "Oops, Some Error Occures Please Contact To Admin .");
						}
						else
						{
							$db->transCommit();
							flashToast("message", "	Payment Mode Update successfully.");
						}


					}
					

				}
				catch (Exception $e)
				{					
					flashToast("message", $e->getMessage());

				}
					
			}			
			else if(isset($inputs['update_cheque_no'])) 
			{
				$module = $this->request->getVar('module_name');
				$cheque_tbl_id = $this->request->getVar('cheque_tbl_id');
				$cheuqe_no_to_update = $this->request->getVar('cheque_dd_neft_no_value');
				$cheque_date = $this->request->getVar('cheque_date');
				$bank_name = $this->request->getVar('bank_name');
				$branch_name = $this->request->getVar('branch_name');
				$updateData=[
					"cheque_no"=>$cheuqe_no_to_update,
					"cheque_date"=>$cheque_date,
					"bank_name"=>$bank_name,
					"branch_name"=>$branch_name,
				];
				
				if($module=='property')
				{
					// $transaction = $this->model_transaction->update_cheque($cheque_tbl_id,$cheuqe_no_to_update);
					$transaction = $this->model_transaction->updateChequeData($cheque_tbl_id,$updateData);
					flashToast("message", "Cheque no. update successfully !!");
					
				}else if($module=='saf'){
					// $transaction = $this->model_transaction->update_cheque($cheque_tbl_id,$cheuqe_no_to_update);
					$transaction = $this->model_transaction->updateChequeData($cheque_tbl_id,$updateData);
					flashToast("message", "Cheque no. update successfully !!");

				}
				elseif($module=='gov_tr')
				{
					// $transaction = $this->model_transaction->update_gb_cheque($cheque_tbl_id,$cheuqe_no_to_update);
					$transaction = $this->model_transaction->updateGbSafChequeData($cheque_tbl_id,$updateData);
					flashToast("message", "Cheque no. update successfully !!");

					
				}
				elseif($module=='water')
				{
					// $transaction = $this->Water_Transaction_Model->update_water_cheque($cheque_tbl_id,$cheuqe_no_to_update);	
					$transaction = $this->Water_Transaction_Model->updateChequeData($cheque_tbl_id,$updateData);
					flashToast("message", "Cheque no. update successfully !!");

				}
				elseif($module=='trade')
				{
					// $transaction = $this->TradeTransactionModel->update_trade_cheque($cheque_tbl_id,$cheuqe_no_to_update);
					$transaction = $this->TradeTransactionModel->updateChequeData($cheque_tbl_id,$updateData);
					flashToast("message", "Cheque no. update successfully !!");

						
				}
					
			}			

			
			

		}
		// die;
		return view('property/update_pament_mode', $data);
	}

	// function ajaxGetChequeNo(){
	// 	if($this->request->getMethod()=='post')
   	// 	{
			
	// 		$transaction_id=$this->request->getVar('transaction_id');
	// 		$module=$this->request->getVar('module');

	// 		// if($module=='saf'){
	// 		// 	$this->db
	// 		// }
	// 		$sql = "select id,cheque_no from tbl_cheque_details where transaction_id=".$transaction_id."";
	// 		$run = $this->db->query($sql);
	// 		$result = $run->getResultArray();
	// 		return json_encode($result[0]);
			
   	// 	}
	// }


}
?>
