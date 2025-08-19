<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\model_transaction;
use App\Models\model_emp_details;
use App\Models\model_view_tc_transaction_details;
use App\Models\model_cash_verification_mstr;
use App\Models\model_cash_verification_details;
use App\Models\model_user_hierarchy;
use App\Models\MasterModel;


class collection_Verification extends AlphaController
{
	protected $db;
	protected $dbSystem;
	protected $model_transaction;
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
		//$db_name = db_connect("db_rmc_property");	
		$this->modelpay = new model_transaction($this->db);
		$this->modelemp = new model_emp_details($this->dbSystem);
		$this->model_view_tc_transaction_details = new model_view_tc_transaction_details($this->db);
		$this->model_cash_verification_mstr = new model_cash_verification_mstr($this->db);
		$this->model_cash_verification_details = new model_cash_verification_details($this->db);
		$this->user_details = new model_user_hierarchy($this->dbSystem);
		$this->master=new MasterModel($this->dbSystem);

        
    }
	public function account_Verification()
	{

		if(isset($_POST['Search']))
		{
			$_SESSION['date_from']=$data['date_from']=$_POST['date_from'];
			$employee_id=$_POST['employee_id'];
		}
		else
		{
			$_SESSION['date_from']=$data['date_from']=date("Y-m-d");	
			$employee_id="";
		}

		$data['ward_list']=$this->master->ward_list();

		$ulb_mstr_id = $_SESSION['ulb_dtl']['ulb_mstr_id'];	
		//$data =(array)null;
		$data['curr_date']=date('Y-m-d');
		//print_r($data);
		if($employee_id!="")
		{
			$where=" and emp.id=".$employee_id;
		}
		else
		{
			$where="";

		}
		$data['where']=$where;

		$data['emp_id'] = $this->modelpay->emp_list($data);
		$data['emplist']=$this->user_details->getTCList();
		//print_r($data['emplist']);;

		//print_r($data);
		//$this->user_details->get_project_manager_by_user($data['depositor_id']);
		return view('property/account_Verification', $data);
		
	}
	
	/*public function tc_Collection_details($id=null)
	{

		$ulb_mstr_id = $_SESSION['ulb_dtl']['ulb_mstr_id'];	
		$data =(array)null;
		$data['id']=$id;
		$data['date']= date("Y-m-d");
		$data['date_from']=$_SESSION['date_from'];
		

		$data['stampdate']=date('Y-m-d H:i:s');
		
		$data['tc_collection_details'] = $this->model_view_tc_transaction_details->tc_collection($data);
		$data['tc_coll_by_cheque']=$this->model_view_tc_transaction_details->tc_collection_by_cheque($data);
		$data['tc_collection_by_dd']=$this->model_view_tc_transaction_details->tc_collection_by_dd($data);
		
		//print_r($data['tc_coll_by_cheque']);		
		$data['depositor_id'] = $data['tc_collection_details']["tran_by_emp_details_id"];
		$data['tc_name'] = $data['tc_collection_details']["tran_by_emp_details_name"];
		$data['collection_amount'] = $data['tc_collection_details']["collection_amount"];		
		$data['cash_amount'] = $this->model_view_tc_transaction_details->tc_collection_by_cash($data);

		$reported_emp_id=$this->user_details->get_project_manager_by_user($data['depositor_id']);
		//print_r($data);
		if($id<>null){
			
			 if($this->request->getMethod()=='post'){

			 	$inputs = arrFilterSanitizeString($this->request->getVar());  

			 	//print_r($inputs);
			 	//print_r($_POST['remarks']);
			 	$cash_verified_amt=$inputs['cash_verify_amt']!=""?$inputs['cash_verify_amt']:0;
			 	if($inputs['Verified_amount']!="")
			 	{

			 		$cash_verified_amt=$inputs['Verified_amount'];
			 	}
				
				$data['cash_verified_amt']=$cash_verified_amt;
				$verified_status_cash=$inputs['cashVerify']=='agreed'?1:0;
				
				


				if($cash_verified_amt>$inputs['collection_amount'] || $inputs['Verified_amount']>$inputs['collection_amount'])
				{

					
					$data['error_message']="Verified_amount can't be greater than collection amount";
					return view('property/tc_Collection_details',$data);

				}
				elseif($cash_verified_amt=="" || ($inputs['Verified_amount']=="" and $cash_verified_amt==""))
				{
					$data['error_message']="Enter Verified Amount";
					return view('property/tc_Collection_details',$data);
				}
				elseif($inputs['remarks']=="" and $inputs['allVerified']!="agreed")
				{
					$data['error_message']="Enter Remarks";
					return view('property/tc_Collection_details',$data);
				}
				else
				{
					
					$tamt=0;
					$verified_amount=0;

					if($inputs['amountVerify'])
					{
						foreach ($inputs['amountVerify'] as $key => $value) {
						$get_amt=explode('-', $value);
						$amt=$get_amt[1];
						$tamt=$tamt+$amt;
						

						}
					}
					$verified_amount=$tamt+$cash_verified_amt;

						$verfify_mstr_sts=$verified_amount==$inputs['total_collection']?1:0;

				 		$cash_verification_mstr_data = [
				 			'tc_id' => $inputs['tran_by_emp_details_id'],
				 			'collected_amount' => $inputs['total_collection'],
				 			'verified_amount'=>$verified_amount,
				 			'verification_date' => date('Y-m-d'),
				 			'verify_status' => $verfify_mstr_sts,
				 			'emp_details_id' => $data['depositor_id'],
				 			'created_on' =>date('Y-m-d H:i:s'),
				 			'trans_date'=>$inputs['trans_date']

				 		];
				 	

				 
			 		 $insert_id=$this->model_cash_verification_mstr->insert_data($cash_verification_mstr_data,$data);

			 		 //echo $verified_status_cash;
					if($inputs['collection_amount']>0)
				 	{
				 		
				 		$cash_verification_dtls_data=array();
				 		$cash_verification_dtls_data['cash_verification_mstr_id']= $insert_id;
				 		$cash_verification_dtls_data['cash_mode_id']= 1;
				 		$cash_verification_dtls_data['cheque_detail_id']= 0;
				 		$cash_verification_dtls_data['amount']= $cash_verified_amt;
				 		$cash_verification_dtls_data['verified_status']= $verified_status_cash;

				 		$cash_verification_dtls_data['verified_by_emp_id']= $_SESSION['user_id'];
				 		$cash_verification_dtls_data['emp_details_id']= $inputs['tran_by_emp_details_id'];
				 		$cash_verification_dtls_data['verified_date']= date('Y-m-d');
				 		$cash_verification_dtls_data['created_on']= $data['stampdate'];

				 		$this->model_cash_verification_details->insert_cash_data($cash_verification_dtls_data);
				 		

				 	}

				 	if($cash_verified_amt==$inputs['total_collection'])
				 	{

				 		$this->model_cash_verification_details->update_cash_transaction($insert_id,$id);

				 	}

			 		$chk_dtl_id="";

				 	if($inputs['amountVerify'])
				 	{


				 		foreach ($inputs['amountVerify'] as $key => $value) {
				 		
				 			$get_chk_dtl_id=explode('-',$value);
				 			//print_r($value);
				 			if($get_chk_dtl_id['2']!="")
				 			{
				 				$chk_dtl_id.=$get_chk_dtl_id['2'].',';
				 			}

				 				

				 		}
				 		
						}


				 		$count_not_verified=$this->model_cash_verification_details->update_data($chk_dtl_id,$data);
				 		if($count_not_verified>0 || $cash_verified_amt<$inputs['collection_amount'])
				 		{

				 			$session=session();
				 			$user_details=$session->get('emp_details');

				 			$verified_status=$count_not_verified>=0?0:1;
				 			$notification=array();
					 		$notification['subject']=$inputs['remarks'];
					 		$notification['sender_id']=$user_details['id'];
					 		$notification['receiver_id']=$reported_emp_id;
					 		$notification['cash_verification_mstr_id']=$insert_id;
					 		$notification['verified_status']=$verified_status;
					 		$notification['emp_id']=$data['depositor_id'];
					 		$notification['created_on']=$data['stampdate'];
				 		
				 		
				 			$this->model_cash_verification_details->insert_notification($notification);

				 		}
				 			
				 	
				 		return view('property/account_Verification',$data);
				 }
				 		}


			 	//return $this->response->redirect(base_url('collection_Verification/tc_Collection_details/'.$id));

			 
		}

		return view('property/tc_Collection_details',$data);
		
	}*/



	public function tc_Collection_details($id=null,$ward_id=null)
	{

		$ulb_mstr_id = $_SESSION['ulb_dtl']['ulb_mstr_id'];	
		$data =(array)null;
		$data['id']=$id;
		$data['date']= date("Y-m-d");
		$data['date_from']=$_SESSION['date_from'];
		

		$session=session();
		$user_details=$session->get('emp_details');
		$data['stampdate']=date('Y-m-d H:i:s');
		$data['tc_collection_details'] = $this->model_view_tc_transaction_details->tc_collection($data);
		$data['tc_coll_by_cheque']=$this->model_view_tc_transaction_details->tc_collection_by_cheque($data);
		$data['tc_collection_by_dd']=$this->model_view_tc_transaction_details->tc_collection_by_dd($data);
		
		//print_r($data['tc_collection_details']);	

		$data['depositor_id'] = $data['tc_collection_details'][0]["tran_by_emp_details_id"];
		$data['tc_name'] = $data['tc_collection_details'][0]["tran_by_emp_details_name"];
		$data['collection_amount'] = $data['tc_collection_details'][0]["collection_amount"];	
		$data['cash_amount'] = $this->model_view_tc_transaction_details->tc_collection_by_cash($data);
		$reported_emp_id=$this->user_details->get_project_manager_by_user($data['depositor_id']);
	
		//print_r($reported_emp_id);
		$trim=rtrim($reported_emp_id,',');
		$val=explode(',', $trim);
		//print_r($val);
		 $lastindex=end($val);
		$lastindex=key($val);
		 $project_manager_id=$val[$lastindex];

		if($id<>null){
			
			 if($this->request->getMethod()=='post'){

			 	$inputs = arrFilterSanitizeString($this->request->getVar());  

			 	//print_r($inputs);
			 	//print_r($_POST['remarks']);
			 	$data['trans_date']=$inputs['trans_date'];
			 
			 	$data['tran_by_emp_details_id']=$inputs['tran_by_emp_details_id'];
			 	$data['user_id']=$user_details['id'];

				if($inputs['remarks']=="" and $inputs['allVerified']!="agreed")
				{
					$data['error_message']="Enter Remarks";
					return view('property/tc_Collection_details',$data);
				}
				else
				{
			 		 $insert_id=$this->model_cash_verification_details->update_cash_verification($data);
				 	if($inputs['amountVerify'])
				 	{
				 		foreach ($inputs['amountVerify'] as $key => $value)
				 		{
				 			//print_r($value);
				 			$get_trans_id=$value;
				 			//print_r($value);
				 			if($get_trans_id!="")
				 			{
				 				$transaction_id.=$get_trans_id.',';
				 			}
				 		}
				 		
				 		$transaction_id=rtrim($transaction_id,',');
					
				 		$data['transaction_id']=$transaction_id;

					}

					$this->model_cash_verification_details->update_cheque_verification($data);


					//$count_not_verified=$this->model_cash_verification_details->check_verification_pending($data);

					//print_r($count_not_verified);
					//echo "//".$count_not_verified[0]['cnt'];

			 		if($inputs['remarks']!="")
			 		{
			 			

			 			$notification=array();
				 		$notification['remarks']=$inputs['remarks'];
				 		$notification['subject']="Cash Verification";
				 		$notification['sender_id']=$user_details['id'];
				 		$notification['receiver_id']=$project_manager_id;
				 		$notification['status']=1;
				 		$notification['related_id']=$data['depositor_id'];
				 		$notification['created_on']=$data['stampdate'];
			 			$notification['ulb_id']=$ulb_mstr_id;
			 			$notification['link']="collection_Verification/cash_verf_pending_details";
			 			
			 				$notification_id=$this->user_details->insert_notification($notification);

			 		    $data['notification_id']=$notification_id;

			 			$this->model_cash_verification_details->update_cheque_notverified_status($data);

			 		}
				 	//return view('property/account_Verification',$data);
					return $this->response->redirect(base_url('collection_Verification/account_Verification'));
				 }
			}


			 	//return $this->response->redirect(base_url('collection_Verification/tc_Collection_details/'.$id));

			 
		}

		return view('property/tc_Collection_details',$data);
		
	}

	public function cash_verf_pending_lists()
	{

			$data=array();
			$data['curr_date']=date('Y-m-d');
			$session=session();
			$emp_details=$session->get('emp_details');
			$user_type_mstr_id=$emp_details['user_type_mstr_id'];
			$employee_id=$emp_details['id'];

			if($user_type_mstr_id!=3)
			{
				$reported_emp_id=$this->user_details->get_project_manager_by_user($employee_id);
				
				$rtrim=rtrim($reported_emp_id,',');
				$explode=explode(',', $rtrim);
				//print_r($explode);
				end($explode);
				$lastkey=key($explode);
				$project_manager_id=$explode[$lastkey];
				$session_user_id=$project_manager_id;
				$data['session_user_id']=$session_user_id;


			}else
			{
				$session_user_id=$employee_id;
				$data['session_user_id']=$employee_id;
				
			}
			if(isset($_POST['Search']))
			{
				$_SESSION['date_from']=$data['date_from']=$_POST['date_from'];
				
				
			}
			else
			{

				$_SESSION['date_from']=$data['date_from']=date("Y-m-d");
				
			}


			$data['pending_list']=$this->user_details->cash_verf_pending_list($data);
			//print_r($data['pending_list']);

			return view('property/cash_verification_pending_list',$data);
	}

	
	public function cash_verf_pending_details($id=null,$emp_id=null)
	{

			// $id is tbl_notification's id

			if($id!=null and $emp_id!="")
			{

				$session=session();
				$emp_details=$session->get('emp_details');
				$user_type_mstr_id=$emp_details['user_type_mstr_id'];
				$employee_id=$emp_details['id'];

				$data=array();
				$data['tc_name']=$this->user_details->get_user_details($emp_id);
				$total_pending_by_tc=$this->user_details->gettotal_pending_amount($id);

				$data['id']=$id;
				$data['date_from']=$_SESSION['date_from'];
				$curr_date=date('Y-m-d');
				$data['payable_amt']=$total_pending_by_tc['payable_amt'];
				
				$data['chq_details_prop']=$this->user_details->cash_vef_chq_list($data);
				$data['user_type_mstr_id']=$user_type_mstr_id;
				$data['chq_details_saf']=$this->user_details->cash_vef_chq_list_saf($data);

				//$data['chq_details_prop']=$this->user_details->cash_vef_chq_list($data);


				$prop_dtl_id=$data['prop_dtl_id']['prop_dtl_id'];
				$tran_type=$data['tran_type']['tran_type'];


				
				//print_r($data['chq_details']);

				if($this->request->getMethod()=='post')
				{

					$inputs = arrFilterSanitizeString($this->request->getVar());  
					$notification_id=$inputs['notification_id'];
				
					$session=session();
					$emp_dtls=$session->get('emp_details');
					//print_r($emp_dtls);
					$session_id=$emp_dtls['id'];

					$data['notification_id']=$notification_id;
					$data['curr_user_id']=$session_id;


					$this->model_cash_verification_details->verify_pending_cheques($data);

					$this->user_details->update_notification_status($data);

						return $this->response->redirect(base_url('collection_Verification/cash_verf_pending_lists'));
				}
				return view('property/cash_verf_pending_details',$data);

		}
	}
	
	  public function notification()
      {

      	$session=session();
      	$empdtls=$session->get('emp_details');
      	$session_user_id=$empdtls['id'];

        $result=$this->user_details->get_notification($session_user_id);
        //print_r($result);
        $html = "";
        foreach ($result as $key => $value) {

            $html .= "<li><a href='".base_url($value["link"]."/".md5($value["id"]).'/'.md5($value["related_id"]))."'>".$value['subject']."</a></li>";
        }
      

       return json_encode(["html"=>$html, "count"=>sizeof($result)]);

    }
	//--------------------------------------------------------------------

}
