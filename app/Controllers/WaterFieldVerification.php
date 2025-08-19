<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\PostModel;
use App\Models\model_ward_mstr;
use App\Models\model_ulb_mstr;
use App\Models\WaterMobileModel;
use App\Models\WaterConnectionDetailsViewModel;
use App\Models\WaterPropertyModel;
use App\Models\WaterConnectionThroughModel;
use App\Models\WaterConnectionTypeModel;
use App\Models\WaterPipelineModel;
use App\Models\PropertyModel;
use App\Models\WaterApplyNewConnectionModel;
use App\Models\WaterSiteInspectionModel;
use App\Models\WaterViewConnectionFeeModel;
use App\Models\WaterConnectionChargeModel;
use App\Models\WaterFerruleTypeModel;
use App\Models\model_water_level_pending_dtl;
use App\Models\WaterPenaltyInstallmentModel;
use App\Models\water_transaction_fine_rebet_details_model;
use App\Models\WaterPenaltyModel;
use App\Models\WaterPaymentModel;

class WaterFieldVerification extends MobiController
{
	protected $db;
	protected $dbSystem;
	protected $db_property;
	
	//protected $db_name;
	protected $model_ward_mstr;
	protected $model_ulb_mstr;
	protected $site_ins_model;
	protected $model_water_level_pending_dtl;
	protected $penalty_installment_model;
	protected $water_transaction_fine_rebet_details_model;
	protected $penalty_model;
	protected $conn_charge_model;
	protected $payment_model;
	protected $apply_waterconn_model;

	public function __construct()
    {

    	$Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
        $emp_details_id = $emp_mstr["id"];
        $user_type_mstr_id = $emp_mstr["user_type_mstr_id"];
        

        parent::__construct();
    	helper(['db_helper']);
        if($db_name = dbConfig("water")){
			//echo $db_name;
            $this->db = db_connect($db_name);            
        }
        if($db_name = dbConfig("property")){
			//echo $db_name;
            $this->db_property = db_connect($db_name);            
        }
        if($db_system = dbSystem()){
            $this->dbSystem = db_connect($db_system); 
        } 

        helper(['form']);
		//$db_name = db_connect("db_rmc_property");		
		$this->modelUlb = new model_ulb_mstr($this->dbSystem);
		$this->water_mobile_model=new WaterMobileModel($this->db);
		$this->conn_view_model=new WaterConnectionDetailsViewModel($this->db);

		$this->conn_through_model=new WaterConnectionThroughModel($this->db);
        $this->conn_type_model=new WaterConnectionTypeModel($this->db);
        $this->pipeline_model=new WaterPipelineModel($this->db);
        $this->ward_model=new model_ward_mstr($this->dbSystem);
        $this->water_property_model=new WaterPropertyModel($this->db);
        $this->apply_waterconn_model=new WaterApplyNewConnectionModel($this->db);
        $this->site_ins_model=new WaterSiteInspectionModel($this->db);
        $this->conn_fee_model= new WaterViewConnectionFeeModel($this->db);
        $this->conn_charge_model=new WaterConnectionChargeModel($this->db);
        $this->ferrule_type_model=new WaterFerruleTypeModel($this->db);
		$this->model_water_level_pending_dtl=new model_water_level_pending_dtl($this->db);

		$this->penalty_installment_model=new WaterPenaltyInstallmentModel($this->db);
		$this->water_transaction_fine_rebet_details_model=new water_transaction_fine_rebet_details_model($this->db);
		$this->penalty_model=new WaterPenaltyModel($this->db);
		$this->payment_model=new WaterPaymentModel($this->db);

    }

	
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
	public function index()
	{
		return view('mobile/water/index');

	}

	public function field_verification($apply_water_conn_id)
	{
		$data=array();
		
		$Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];

		$data['property_type_list']=$this->water_property_model->property_list();
        $data['conn_type_list']=$this->conn_type_model->conn_type_list();
        $data['conn_through_list']=$this->conn_through_model->conn_through_list();
        $data['pipeline_type_list']=$this->pipeline_model->pipeline_list();
        $data['ward_list']=$this->ward_model->getWardList($data);
        $data['ferrule_list']=$this->ferrule_type_model->ferrule_type_list();
        
       // print_r($data['ward_list']);
		$data['connection_dtls']=$this->apply_waterconn_model->water_conn_details($apply_water_conn_id);
		 //print_r($data['connection_dtls']);
		return view('mobile/water/fieldVerification',$data);

	}

	public function view_site_inspetion()
	{


			if($this->request->getMethod()=='post')
			{
				
				//echo "string";
				$inputs = arrFilterSanitizeString($this->request->getVar());  
				//print_var($inputs);

				$data['application_no']=$inputs['application_no'];
				$data['apply_date']=$inputs['apply_date'];
				$data['site_inspection_id']=$inputs['site_inspection_id'];
				
				$data['ward_mstr_id']=$inputs['corr_ward_mstr_id_t']==1?$inputs['ward_mstr_id_v']:$inputs['ward_id'];

				$get_ward_no=$this->ward_model->getWardNoById($data);

				$data['ward_no']=$get_ward_no['ward_no'];

				$data['pipeline_type_id']=$inputs['corr_pipeline_type_t']==1?$inputs['pipeline_type_id_v']:$inputs['pipeline_type_id'];


				$data['property_type_id']=$inputs['corr_property_type_t']==1?$inputs['property_type_id_v']:$inputs['property_type_id'];


				$data['connection_type_id']=$inputs['corr_connection_type_t']==1?$inputs['corr_connection_type_v']:$inputs['connection_type_id'];


				$data['connection_through_id']=$inputs['corr_connection_through']==1?$inputs['connection_through_v']:$inputs['connection_through_id'];


				$data['category']=$inputs['corr_category']==1?$inputs['corr_category_v']:$inputs['category'];


				 $get_pipeline_type=$this->pipeline_model->getPipelineTypebyId($data['pipeline_type_id']);

				 $data['pipeline_type']=$get_pipeline_type['pipeline_type'];

				 //echo ($data['pipeline_type']);

				 $get_property_type=$this->water_property_model->getPropertyTypebyId($data['property_type_id']);

				 $data['property_type']=$get_property_type['property_type'];

				 $get_connection_type=$this->conn_type_model->getConnectionTypebyId($data['connection_type_id']);

				 $data['connection_type']=$get_connection_type['connection_type'];

				 $get_connection_through=$this->conn_through_model->getConnectionThroughbyId($data['connection_through_id']);

				 $data['connection_through']=$get_connection_through['connection_through'];



				$data['area_sqft']=$inputs['corr_area_sqft_t']==1?$inputs['corr_area_sqft_v']:$inputs['area_sqft'];

				$data['water_conn_id']=$inputs['water_conn_id'];

				$data['corr_ward_mstr_id_t']=$inputs['corr_ward_mstr_id_t'];
				$data['ward_mstr_id_v']=$inputs['ward_mstr_id_v'];
				
				$data['corr_property_type_t']=$inputs['corr_property_type_t'];
				$data['property_type_id_v']=$inputs['property_type_id_v'];
				
				$data['corr_pipeline_type_t']=$inputs['corr_pipeline_type_t'];
				$data['pipeline_type_id_v']=$inputs['pipeline_type_id_v'];
				
				$data['corr_connection_type_t']=$inputs['corr_connection_type_t'];
				$data['corr_connection_type_v']=$inputs['corr_connection_type_v'];
				
				$data['corr_connection_through']=$inputs['corr_connection_through'];
				$data['connection_through_v']=$inputs['connection_through_v'];
				
				$data['corr_category']=$inputs['corr_category'];
				$data['corr_category_v']=$inputs['corr_category_v'];
				
				$data['corr_area_sqft_t']=$inputs['corr_area_sqft_t'];
				$data['corr_area_sqft_v']=$inputs['corr_area_sqft_v'];
				
					

				// self assessed APPLICATION DETAILS
				$data['sa_pipeline_type']=$inputs['sa_pipeline_type'];
				$data['sa_property_type']=$inputs['sa_property_type'];
				$data['sa_connection_type']=$inputs['sa_connection_type'];
				$data['sa_connection_through']=$inputs['sa_connection_through'];
				$data['sa_category']=$inputs['sa_category'];
				$data['sa_ward_no']=$inputs['sa_ward_no'];
				$data['sa_area_sqft']=$inputs['corr_area_sqft_v'];

				$get_ferrule_type=$this->ferrule_type_model->getData(md5($inputs['ferrule_type_id']));

				$ferrule_type=$get_ferrule_type['ferrule_type'];

				$data['pipeline_size']=$inputs['pipeline_size'];
				$data['pipeline_size_type']=$inputs['pipeline_size_type'];
				$data['permissible_pipe_dia']=$inputs['permissible_pipe_dia'];
				$data['permissible_pipe_qlty']=$inputs['permissible_pipe_qlty'];
				$data['road_type']=$inputs['road_type'];
				$data['ferrule_type_id']=$inputs['ferrule_type_id'];
				$data['ferrule_type']=$ferrule_type;
				


				return view('mobile/water/view_field_verification_new',$data);


			}


	}

	
	public function save_site_inspetion()
	{

			$data=array();

			$Session = Session();
       	 	$ulb_mstr = $Session->get("ulb_dtl");
        	$data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];

       		$emp_mstr = $Session->get("emp_details");
        	$emp_details_id = $emp_mstr["id"];
        	$user_type_mstr_id = $emp_mstr["user_type_mstr_id"];

			if($this->request->getMethod()=='post')
			{

				$inputs = arrFilterSanitizeString($this->request->getVar());
				//print_var($inputs);die;

				$data['property_type_list']=$this->water_property_model->property_list();
		        $data['conn_type_list']=$this->conn_type_model->conn_type_list();
		        $data['conn_through_list']=$this->conn_through_model->conn_through_list();
		        $data['pipeline_type_list']=$this->pipeline_model->pipeline_list();
		        $data['ward_list']=$this->ward_model->getWardList($data);
				// print_r($data['pipeline_type_list']);
		        $data['ferrule_list']=$this->ferrule_type_model->ferrule_type_list();
        
        		
		     	$apply_water_conn_id=$inputs['water_conn_id'];
				$data['connection_dtls']=$this->apply_waterconn_model->water_conn_details(md5($apply_water_conn_id));

				//print_r($data['connection_dtls']);


				/*	$data['pipeline_type_id']=$inputs['pipeline_type_id'];
				$data['property_type_id']=$inputs['property_type_id'];
				$data['connection_type_id']=$inputs['connection_type_id'];
				$data['connection_through_id']=$inputs['connection_through_id'];
				$data['category']=$inputs['category'];
				$data['ward_id']=$inputs['ward_id'];
				
				*/


				$data['ward_id']=$inputs['corr_ward_mstr_id_t']==1?$inputs['ward_mstr_id_v']:$inputs['ward_id'];


				$data['pipeline_type_id']=$inputs['corr_pipeline_type_t']==1?$inputs['pipeline_type_id_v']:$inputs['pipeline_type_id'];


				$data['property_type_id']=$inputs['corr_property_type_t']==1?$inputs['property_type_id_v']:$inputs['property_type_id'];


				$data['connection_type_id']=$inputs['corr_connection_type_t']==1?$inputs['corr_connection_type_v']:$inputs['connection_type_id'];

				
				$data['connection_through_id']=$inputs['corr_connection_through']==1?$inputs['connection_through_v']:$inputs['connection_through_id'];


				$data['category']=$inputs['corr_category']==1?$inputs['corr_category_v']:$inputs['category'];

				$data['area_sqft']=$inputs['corr_area_sqft_t']==1?$inputs['corr_area_sqft_v']:$inputs['area_sqft'];
				

			
				$data['corr_ward_mstr_id_t']=$inputs['corr_ward_mstr_id_t'];
				$data['ward_mstr_id_v']=$inputs['ward_mstr_id_v'];
				
				$data['corr_property_type_t']=$inputs['corr_property_type_t'];
				$data['property_type_id_v']=$inputs['property_type_id_v'];
				
				$data['corr_pipeline_type_t']=$inputs['corr_pipeline_type_t'];
				$data['pipeline_type_id_v']=$inputs['pipeline_type_id_v'];
				
				$data['corr_connection_type_t']=$inputs['corr_connection_type_t'];
				$data['corr_connection_type_v']=$inputs['corr_connection_type_v'];
				
				$data['corr_connection_through']=$inputs['corr_connection_through'];
				$data['connection_through_v']=$inputs['connection_through_v'];
				
				$data['corr_category']=$inputs['corr_category'];
				$data['corr_category_v']=$inputs['corr_category_v'];
				
				$data['corr_area_sqft_t']=$inputs['corr_area_sqft_t'];
				$data['corr_area_sqft_v']=$inputs['corr_area_sqft_v'];
				
				$data['pipeline_size']=$inputs['pipeline_size'];
				$data['pipeline_size_type']=$inputs['pipeline_size_type'];
				$data['permissible_pipe_dia']=$inputs['permissible_pipe_dia'];
				$data['permissible_pipe_qlty']=$inputs['permissible_pipe_qlty'];
				$data['road_type']=$inputs['road_type'];
				$data['ferrule_type_id']=$inputs['ferrule_type_id'];
				


				if($inputs['Save'])
				{					
					
					$area_sqmt=$data['area_sqft']*0.092903;
					//	$inputs = arrFilterSanitizeString($this->request->getVar());  
					//print_r($inputs);
					$get_rate_id=$this->apply_waterconn_model->get_rate_id($data['property_type_id'],$data['pipeline_type_id'],$data['connection_through_id'],$data['connection_type_id'],$data['category']);

					
					//print_r($get_rate_id);
					#---------------					

					$where='';
					if($inputs['property_type_id']==1)
					{
						$where=" and  (".$inputs['areasqft'].">=area_from_sqft and ".$inputs['areasqft']."<=area_upto_sqft)";
					}
					
					$get_rate_dtls=$this->apply_waterconn_model->getNewRateId($inputs['property_type_id'],$where);
					$rate_id=$get_rate_dtls['id'];
					
					//print_var($data['area_sqft']);die;

					$apply_water_conn['water_fee_mstr_id']=$rate_id;

						
					if($get_rate_dtls['calculation_type']=='Fixed')
					{
						$conn_fee=$get_rate_dtls['conn_fee'];
					}
					else
					{
						$conn_fee=$get_rate_dtls['conn_fee']*$data['area_sqft'];
					}
					//echo $conn_fee;die;
					

					$effective_date=date('2021-01-01');
					$six_months_after=date('Y-m-d', strtotime($effective_date." + 6 months"));
					
					$penalty=0;

					if($data['connection_type_id']==2 && $data['connection_dtls']['connection_type_id']!=2)
					{
							if($inputs['property_type_id']==1)
							{
								$penalty=5000;
							}
							else
							{
								$penalty=10000;
							}
					}
						
					
					

					$new_site_inspected_conn_charge=$conn_fee;
					$new_site_inspected_amount=$new_site_inspected_conn_charge+$penalty;
					
					$get_paid_amount=$this->conn_charge_model->get_connection_charge_paid_details($apply_water_conn_id);

					$conn_fee_paid=$get_paid_amount['conn_fee'];
					$penalty_paid=$get_paid_amount['penalty'];
					
					$paid_amount=$conn_fee_paid+$penalty_paid;
					//print_var($conn_fee);
					$diff_conn_charge=$conn_fee-$conn_fee_paid;
					if($diff_conn_charge<0)
					{
						$diff_conn_charge=0;
					}

					
					$diff_penalty=$penalty-$penalty_paid;
					if($diff_penalty<0)
					{
						$diff_penalty=0;
					}
					
					$diff_amount=$diff_conn_charge+$diff_penalty;
					//print_var($diff_amount);die;
					if($diff_amount>0)
					{	
						$payment_status=0;
						$connection_charge=array();
						$connection_charge['apply_connection_id']=$inputs['related_id'];
						$connection_charge['charge_for']='Site Inspection';
						$connection_charge['amount']=$diff_amount;
						$connection_charge['conn_fee']=$diff_conn_charge;
						$connection_charge['penalty']=$diff_penalty;
						$connection_charge['created_on']=date('Y-m-d');
						
					}
					else
					{	
						$payment_status=1;

					}
					if($data['flat_count']!="")
					{
						$flat_count=$data['flat_count'];
					}
					else
					{
						$flat_count=0;
					}

					#------------

					// $rate_id=$get_rate_id['id'];
					// $new_site_inspected_amount=$get_rate_id['proc_fee']+$get_rate_id['reg_fee']+$get_rate_id['app_fee']+$get_rate_id['sec_fee']+$get_rate_id['conn_fee'];
					


					// $get_paid_amount=$this->conn_charge_model->get_connection_charge_paid($apply_water_conn_id);

					

					// $paid_amount=$get_paid_amount['amount'];
					

					// echo $diff_amount=$new_site_inspected_amount-$paid_amount;
					// code for BPL application 
					if(strtoupper($inputs['applicant_category'])=='BPL' && strtoupper($data['connection_dtls']['category'])=='BPL')
					{
						if($inputs['is_regularization']==$data['connection_dtls']['connection_type_id'])
						{
							$payment_status=1;
							$diff_amount=0;
							$diff_penalty=0;
						}
					}
					// print_var($inputs);
					// print_var($data['connection_dtls']);
					// die;


					// if($diff_amount>0)
					// {
						
					// 	$connection_charge=array();
					// 	$connection_charge['apply_connection_id']=$apply_water_conn_id;
					// 	$connection_charge['charge_for']='Site Inspection';
					// 	$connection_charge['amount']=$diff_amount;
					// 	$connection_charge['created_on']=date('Y-m-d');
						
					// }

					//echo ($paid_amount);
					//exit;

					$site_ins_arr=array();
					$site_ins_arr['apply_connection_id']=$apply_water_conn_id;
					$site_ins_arr['property_type_id']=$data['property_type_id'];
					$site_ins_arr['pipeline_type_id']=$data['pipeline_type_id'];
					$site_ins_arr['connection_type_id']=$data['connection_type_id'];
					$site_ins_arr['connection_through_id']=$data['connection_through_id'];
					$site_ins_arr['category']=$data['category'];
					$site_ins_arr['rate_id']=$rate_id;
					$site_ins_arr['flat_count']=$flat_count;
					$site_ins_arr['ward_id']=$data['ward_id'];
					$site_ins_arr['area_sqft']=$data['area_sqft'];
					$site_ins_arr['area_sqmt']=$area_sqmt;
					$site_ins_arr['emp_details_id']=$emp_details_id;
					$site_ins_arr['pipeline_size']=$data['pipeline_size'];
					$site_ins_arr['pipeline_size_type']=$data['pipeline_size_type'];
					$site_ins_arr['pipe_size']=$data['permissible_pipe_dia'];
					$site_ins_arr['pipe_type']=$data['permissible_pipe_qlty'];
					$site_ins_arr['ferrule_type_id']=$data['ferrule_type_id'];
					$site_ins_arr['road_type']=$data['road_type'];
					$site_ins_arr['created_on']=date('Y-m-d H:i:s');
					$site_ins_arr['road_app_fee_id']=$road_app_fee_id??null;
					$site_ins_arr['rate_id']=$rate_id;
					$site_ins_arr['verified_status']=1;

					$level_pending_arr=array();
					if($diff_amount>0)
					{
						$site_ins_arr['payment_status']=0;
						$level_pending_arr['verification_status']=0;
					}
					else
					{
						$site_ins_arr['payment_status']=1;
						$level_pending_arr['verification_status']=1;
					}
					//print_r($site_ins_arr);

					// multiple time site inspection can be done if in case application is backwarded from executive officer to site inspection so check exist condition not checked , multiple entry can be done for same application 

					
					$level_pending_arr['apply_connection_id']=$apply_water_conn_id;
					$level_pending_arr['sender_user_type_id']=$user_type_mstr_id;
					$level_pending_arr['receiver_user_type_id']=14;
					$level_pending_arr['emp_details_id']=$emp_details_id;
					$level_pending_arr['created_on']=date('Y-m-d H:i:s');
					$level_pending_arr['receiver_user_id'] =$emp_details_id;
					$level_pending_arr['remarks']= 'Mobiles Verification';
					$level_pending_arr['forward_time'] = date('H:i:s');
					$level_pending_arr['created_on'] = date('Y-m-d H:i:s');

					$inputs['related_id']=$apply_water_conn_id;

					$leveldata = [
						'apply_connection_id' => $apply_water_conn_id,
						'sender_user_type_id' => 13,
						'receiver_user_type_id' => 14,
						'forward_date' => date('Y-m-d'),
						'forward_time' => date('H:i:s'),
						'created_on' =>date('Y-m-d H:i:s'),
						'level_pending_status'=>0,
						'emp_details_id' => $emp_details_id,
						//'remarks' => $inputs['remarks_si'],								
						'receiver_user_id' =>$emp_details_id,
						];		
					

					$check_exists=$this->site_ins_model->checkdata($apply_water_conn_id);
					$si_where=array();

					// print_var($inputs['related_id']);
					
					// die;					
					$last_level_id = $this->site_ins_model->insert_level_pending_last_id($apply_water_conn_id);
					if($last_level_id && !empty($last_level_id))
						$last_level_id=$last_level_id;
					if($check_exists==0)
					{
													
						//$check_payment_done=$this->site_ins_model->checkPaymentDone(md5($apply_water_conn_id),$si_where);	
						
						$site_ins_arr["inspection_date"]=date('Y-m-d');
						$site_ins_arr["inspection_time"]=date('H:i:s');
						$site_ins_arr["scheduled_status"]=1;
						$site_ins_arr["verified_by"]='JuniorEngineer';
						$insert_id=$this->site_ins_model->insertData($site_ins_arr);						
						
						if($insert_id)
						{
							$this->model_water_level_pending_dtl->level_pending_updt($level_pending_arr,array('id'=>$last_level_id));
							//$this->site_ins_model->update_level_pending_status($apply_water_conn_id,$user_type_mstr_id);

							$check=$this->site_ins_model->check_exists($level_pending_arr);
							

							//$count=$this->conn_charge_model->checkConnectionChargePaid($apply_connection_id);

							// $this->conn_charge_model->checkExists($apply_water_conn_id);
							//						

							if($this->conn_charge_model->checkExists($apply_water_conn_id)==0 and !empty($connection_charge))
							{
								//$this->conn_charge_model->insert_site_inspec_conn_charge($connection_charge);								
							}
							

							if($check==0 and $diff_amount==0)
							{	
													
								// $this->site_ins_model->insert_level_pending($level_pending_arr);
								$level_pending_insrt=$this->model_water_level_pending_dtl->level_pending_insrt($leveldata);
					
							}
							else
							{
								$level_pending_insrt=$this->model_water_level_pending_dtl->level_pending_insrt($leveldata);
							}
							#----------------------------
							if(($this->conn_charge_model->checkExists($apply_water_conn_id)==0 and !empty($connection_charge) and $diff_amount>0) )
							{
								

								$this->conn_charge_model->insert_site_inspec_conn_charge($connection_charge);
								
								if($diff_penalty>0)
								{
									$this->penalty_installment_model->deactivateUnpaidInstallment($inputs['related_id']);
									
									$paid_installment_rebate=$this->water_transaction_fine_rebet_details_model->getInstallmentRebatePaid($inputs['related_id']);


									$penalty_arr=array();
									$penalty_arr['related_id']=$inputs['related_id'];
									$penalty_arr['penalty_type']="Difference Penalty paid as Installment Rebate";
									$penalty_arr['penalty_amt']=$paid_installment_rebate;
									$penalty_arr['type']='applicant';
									$penalty_arr['created_on']=date('Y-m-d H:i:s');
									$penalty_arr['status']=1;
									
									$this->penalty_model->insertData($penalty_arr);
									
									


									$installment_amount1=($penalty*40)/100;
									$installment_amount2=($penalty*30)/100;
									

									for($j=1;$j<=3;$j++)
									{	
										
										if($j==1)
										{
											$paid_installment=$this->penalty_installment_model->paidInstallment($inputs['related_id']);
											$paid_installment_rebate=$this->water_transaction_fine_rebet_details_model->getInstallmentRebatePaid($inputs['related_id']);

											$installment_amount=$installment_amount1-$paid_installment;
											$rebate=$paid_installment_rebate;
										}
										else
										{
											$installment_amount=$installment_amount2;
											$rebate=0;
										}
										
										if($installment_amount>0)
										{
											$penalty_installment=array();
											$penalty_installment['apply_connection_id']=$inputs['related_id'];
											$penalty_installment['penalty_head']="$j"." Installment";
											$penalty_installment['installment_amount']=$installment_amount;
											$penalty_installment['balance_amount']=$installment_amount;
											$penalty_installment['rebate']=$rebate;
											$penalty_installment['paid_status']=0;
											
											$this->penalty_installment_model->insertData($penalty_installment);
										}
										

									}
									
								}

									
							}
							else
							{			
								if($diff_amount>0)
								{
									$this->conn_charge_model->update_connection_charge($connection_charge);
								}
								else
								{
									$this->conn_charge_model->deleteConnectionCharge(md5($inputs['related_id']));
									
								}
								
								$this->penalty_installment_model->deleteUnpaidInstallment($inputs['related_id']);

								if($diff_penalty>0)
								{	
									
									//	$this->penalty_installment_model->deactivateUnpaidInstallment($inputs['related_id']);
									$this->payment_model->deleteUnpaidDifferencePenalty(md5($inputs['related_id']));
									
									$paid_installment_rebate=$this->water_transaction_fine_rebet_details_model->getInstallmentRebatePaid($inputs['related_id']);
									
									
										$penalty_arr=array();
										$penalty_arr['related_id']=$inputs['related_id'];
										$penalty_arr['penalty_type']="Difference Penalty paid as Installment Rebate";
										$penalty_arr['penalty_amt']=$paid_installment_rebate;
										$penalty_arr['type']='applicant';
										$penalty_arr['created_on']=date('Y-m-d H:i:s');
										$penalty_arr['status']=1;
										
										$this->penalty_model->insertData($penalty_arr);
									

										$installment_amount1=($penalty*40)/100;
										$installment_amount2=($penalty*30)/100;
										

										for($j=1;$j<=3;$j++)
										{	
											
											if($j==1)
											{
												$paid_installment=$this->penalty_installment_model->paidInstallment($inputs['related_id']);													

												$installment_amount=$installment_amount1-$paid_installment;
												$rebate=$paid_installment_rebate;
											}
											else
											{
												$installment_amount=$installment_amount2;
												$rebate=0;
											}
											
											if($installment_amount>0)
											{
												$penalty_installment=array();
												$penalty_installment['apply_connection_id']=$inputs['related_id'];
												$penalty_installment['penalty_head']="$j"." Installment";
												$penalty_installment['installment_amount']=$installment_amount;
												$penalty_installment['balance_amount']=$installment_amount;
												$penalty_installment['rebate']=$rebate;
												$penalty_installment['paid_status']=0;
												
												$this->penalty_installment_model->insertData($penalty_installment);
											}
											

										}
										
								}
								else
								{	
									$this->penalty_installment_model->deleteUnpaidInstallment($inputs['related_id']);
									$this->payment_model->deleteUnpaidDifferencePenalty(md5($inputs['related_id']));
								}

							}
							#---------------------------
						
						}
						
						return $this->response->redirect(base_url('WaterFieldVerification/final_site_inspection_details/'.md5($apply_water_conn_id).'/'.md5($insert_id)));


					}
					else
					{
						$si_id = $this->site_ins_model->get_si_id($apply_water_conn_id);
						$si_where = array('id'=>$si_id);
						$update=$this->site_ins_model->SI_date_timeupdt($site_ins_arr,$si_where);
						$this->site_ins_model->update_level_pending_status($apply_water_conn_id,$user_type_mstr_id);
						$check=$this->site_ins_model->check_exists($level_pending_arr);
						if($this->conn_charge_model->checkExists($apply_water_conn_id)==0 and !empty($connection_charge))
						{
							//$this->conn_charge_model->insert_site_inspec_conn_charge($connection_charge);							
						}
						if($check==0 and $diff_amount==0)
						{
							$level_pending_insrt=$this->model_water_level_pending_dtl->level_pending_insrt($leveldata);				
						}
						else
						{
							$level_pending_insrt=$this->model_water_level_pending_dtl->level_pending_insrt($leveldata);
						}
						#---------------------------
						if(($this->conn_charge_model->checkExists($apply_water_conn_id)==0 and !empty($connection_charge) and $diff_amount>0) )
						{
							

							$this->conn_charge_model->insert_site_inspec_conn_charge($connection_charge);
							
							if($diff_penalty>0)
							{
								$this->penalty_installment_model->deactivateUnpaidInstallment($inputs['related_id']);
								
								$paid_installment_rebate=$this->water_transaction_fine_rebet_details_model->getInstallmentRebatePaid($inputs['related_id']);


								$penalty_arr=array();
								$penalty_arr['related_id']=$inputs['related_id'];
								$penalty_arr['penalty_type']="Difference Penalty paid as Installment Rebate";
								$penalty_arr['penalty_amt']=$paid_installment_rebate;
								$penalty_arr['type']='applicant';
								$penalty_arr['created_on']=date('Y-m-d H:i:s');
								$penalty_arr['status']=1;
								
								$this->penalty_model->insertData($penalty_arr);
								
								


								$installment_amount1=($penalty*40)/100;
								$installment_amount2=($penalty*30)/100;
								

								for($j=1;$j<=3;$j++)
								{	
									
									if($j==1)
									{
										$paid_installment=$this->penalty_installment_model->paidInstallment($inputs['related_id']);
										$paid_installment_rebate=$this->water_transaction_fine_rebet_details_model->getInstallmentRebatePaid($inputs['related_id']);

										$installment_amount=$installment_amount1-$paid_installment;
										$rebate=$paid_installment_rebate;
									}
									else
									{
										$installment_amount=$installment_amount2;
										$rebate=0;
									}
									
									if($installment_amount>0)
									{
										$penalty_installment=array();
										$penalty_installment['apply_connection_id']=$inputs['related_id'];
										$penalty_installment['penalty_head']="$j"." Installment";
										$penalty_installment['installment_amount']=$installment_amount;
										$penalty_installment['balance_amount']=$installment_amount;
										$penalty_installment['rebate']=$rebate;
										$penalty_installment['paid_status']=0;
										
										$this->penalty_installment_model->insertData($penalty_installment);
									}
									

								}
								
							}

								
						}
						else
						{			
							if($diff_amount>0)
							{
								$this->conn_charge_model->update_connection_charge($connection_charge);
							}
							else
							{
								$this->conn_charge_model->deleteConnectionCharge(md5($inputs['related_id']));
								
							}
							
							$this->penalty_installment_model->deleteUnpaidInstallment($inputs['related_id']);

							if($diff_penalty>0)
							{	
								
								//	$this->penalty_installment_model->deactivateUnpaidInstallment($inputs['related_id']);
								$this->payment_model->deleteUnpaidDifferencePenalty(md5($inputs['related_id']));
								
								$paid_installment_rebate=$this->water_transaction_fine_rebet_details_model->getInstallmentRebatePaid($inputs['related_id']);
								
								
									$penalty_arr=array();
									$penalty_arr['related_id']=$inputs['related_id'];
									$penalty_arr['penalty_type']="Difference Penalty paid as Installment Rebate";
									$penalty_arr['penalty_amt']=$paid_installment_rebate;
									$penalty_arr['type']='applicant';
									$penalty_arr['created_on']=date('Y-m-d H:i:s');
									$penalty_arr['status']=1;
									
									$this->penalty_model->insertData($penalty_arr);
								

									$installment_amount1=($penalty*40)/100;
									$installment_amount2=($penalty*30)/100;
									

									for($j=1;$j<=3;$j++)
									{	
										
										if($j==1)
										{
											$paid_installment=$this->penalty_installment_model->paidInstallment($inputs['related_id']);													

											$installment_amount=$installment_amount1-$paid_installment;
											$rebate=$paid_installment_rebate;
										}
										else
										{
											$installment_amount=$installment_amount2;
											$rebate=0;
										}
										
										if($installment_amount>0)
										{
											$penalty_installment=array();
											$penalty_installment['apply_connection_id']=$inputs['related_id'];
											$penalty_installment['penalty_head']="$j"." Installment";
											$penalty_installment['installment_amount']=$installment_amount;
											$penalty_installment['balance_amount']=$installment_amount;
											$penalty_installment['rebate']=$rebate;
											$penalty_installment['paid_status']=0;
											
											$this->penalty_installment_model->insertData($penalty_installment);
										}
										

									}
									
							}
							else
							{	
								$this->penalty_installment_model->deleteUnpaidInstallment($inputs['related_id']);
								$this->payment_model->deleteUnpaidDifferencePenalty(md5($inputs['related_id']));
							}

						}

						#----------------------------
						return $this->response->redirect(base_url('WaterFieldVerification/final_site_inspection_details/'.md5($apply_water_conn_id).'/'.md5($si_id)));


		               	flashToast("error", "Please Contact to Admin!!!");
		                return $this->response->redirect(base_url('WaterMobileIndex/search_consumer'));

					}
					

				}
				else
				{

					return view('mobile/water/fieldVerification',$data);
				}
			


				



			}


	}
	
	
	
	public function final_site_inspection_details($apply_water_conn_id,$site_inspection_id)
	{

		$data=array();
		
		$data['site_inspection_details']=$this->site_ins_model->getData($site_inspection_id);

		//print_r($data['site_inspection_details']);


		$data['connection_details']=$this->apply_waterconn_model->water_conn_details($apply_water_conn_id);

		//print_r($data['connection_details']);



		return view('mobile/water/view_final_field_verification_details',$data);


	}
	
	
	

}
