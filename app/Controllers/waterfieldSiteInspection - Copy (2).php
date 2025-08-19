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
use App\Models\model_applicant_doc;
use App\Models\water_applicant_details_model;
use App\Models\Water_Transaction_Model;
use App\Models\water_consumer_sms_details_model;
use App\Models\WaterRoadAppartmentFeeModel;
use App\Models\water_level_pending_model;
use App\Models\model_water_level_pending_dtl;



class WaterfieldSiteInspection extends AlphaController
{
	protected $db;
	protected $dbSystem;
	protected $db_property;
	
	//protected $db_name;
	protected $model_ward_mstr;
	protected $model_ulb_mstr;


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
		$this->model_applicant_doc=new model_applicant_doc($this->db);
		$this->water_applicant_details_model=new water_applicant_details_model($this->db);
		$this->Water_Transaction_Model=new Water_Transaction_Model($this->db);
		$this->water_consumer_sms_details_model=new water_consumer_sms_details_model($this->db);
		$this->road_app_fee_model=new WaterRoadAppartmentFeeModel($this->db);
		$this->water_level_pending_model=new water_level_pending_model($this->db);
		$this->model_water_level_pending_dtl=new model_water_level_pending_dtl($this->db);
    }


	public function search_consumer_for_siteInspection()
	{
		
		$data=array();
		$Session=session();
		$emp_mstr = $Session->get("emp_details");
        $emp_details_id = $emp_mstr["id"];
        $user_type_mstr_id = $emp_mstr["user_type_mstr_id"];

		if(isset($_POST['search']))
		{
			$_SESSION['from_date']=$data['from_date']=$_POST['from_date'];
			$_SESSION['upto_date']=$data['upto_date']=$_POST['upto_date'];
		}
		else
		{
			$_SESSION['from_date']=$data['from_date']=date("Y-m-d");	
			$_SESSION['upto_date']=$data['upto_date']=date("Y-m-d");	
		}
		$data['user_type_mstr_id']=$user_type_mstr_id;
		$data['consumer_dtls']=$this->water_mobile_model->search_consumer($data);
		return view('water/water_connection/search_consumer_for_siteInspection',$data);

	}
	
	
	
	public function field_verification($apply_water_conn_id,$level_pending_dtl_id)
	{
		$data=array();

		$Session = Session();
		$emp_mstr = $Session->get("emp_details");
        $emp_details_id = $emp_mstr["id"];
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
		$data['app_id']=$apply_water_conn_id;
		$data['connection_dtls']=$this->apply_waterconn_model->water_conn_details($apply_water_conn_id);
		$data['property_type_list']=$this->water_property_model->property_list();
        $data['conn_type_list']=$this->conn_type_model->conn_type_list();
        $data['conn_through_list']=$this->conn_through_model->conn_through_list();
        $data['pipeline_type_list']=$this->pipeline_model->pipeline_list();
        $data['ward_list']=$this->ward_model->getWardList($data);
        $data['ferrule_list']=$this->ferrule_type_model->ferrule_type_list();
		$data['applicant_details']=$this->water_applicant_details_model->applicantDetails($data['connection_dtls']['id']);
		//print_r($data['applicant_details']);

		$data['applicant_doc_details']=$this->model_applicant_doc->applicantDocDetails($data['connection_dtls']['id']);
		 $data['transaction_details']=$this->Water_Transaction_Model->get_all_transactions($apply_water_conn_id);
        
		$data['si_level_verify_dtls']=$this->model_water_level_pending_dtl->si_level_verify_dtls($apply_water_conn_id);
		
		$data['si_verify_dtls']=$this->site_ins_model->si_verify_dtls($apply_water_conn_id);
		
		$data['getremarks']=$this->water_level_pending_model->getremarks($apply_water_conn_id);
		
		$data['SI_date_time']=$this->site_ins_model->SI_date_time($data['connection_dtls']['id']);
		//print_r($data['si_level_verify_dtls']);
		$data['isModalOpen']=false;
		if($this->request->getMethod()=='post'){
			$inputs = arrFilterSanitizeString($this->request->getVar());  
			
			if(isset($_POST['Forward_si']))
			{  
				$leveldata = [
                        'apply_connection_id' => $data['connection_dtls']['id'],
                        'sender_user_type_id' => 13,
                        'receiver_user_type_id' => 14,
                        'forward_date' => date('Y-m-d'),
                        'forward_time' => date('H:i:s'),
                        'created_on' =>date('Y-m-d H:i:s'),
                        'level_pending_status'=>0,
                        'emp_details_id' => $emp_details_id,
                        'remarks' => $inputs['remarks_si'],
						'verification_status' => 1
                        ];
						
				$data['level_pending_stts']=$this->apply_waterconn_model->update_level_pending_status($leveldata);
                $level_pending_updt=$this->model_water_level_pending_dtl->level_pending_updt($leveldata);           
				$level_pending_insrt=$this->model_water_level_pending_dtl->level_pending_insrt($leveldata);
				return $this->response->redirect(base_url('waterfieldSiteInspection/search_consumer_for_siteInspection'));
			}
			
			if(isset($_POST['Backward_si']))
			{  
				$leveldata = [
                        'apply_connection_id' => $data['connection_dtls']['id'],
                        'sender_user_type_id' => 13,
                        'receiver_user_type_id' => 12,
                        'forward_date' => date('Y-m-d'),
                        'forward_time' => date('H:i:s'),
                        'created_on' =>date('Y-m-d H:i:s'),
                        'level_pending_status'=>0,
                        'emp_details_id' => $emp_details_id,
                        'remarks' => '',
                        'verification_status' => 3
                        ];
						
				$level_pending_updt=$this->model_water_level_pending_dtl->level_pending_updt($leveldata);           
				$level_pending_insrt=$this->model_water_level_pending_dtl->level_pending_insrt($leveldata);
				return $this->response->redirect(base_url('waterfieldSiteInspection/search_consumer_for_siteInspection'));
			}
			
			if(isset($_POST['set_si']))
			{
				$data = [
                        'date' => $inputs['inspection_date'],
                        'time' => $inputs['inspection_time'],
                        'related_id' => $inputs['related_id'],
						'sms' => "Your Site inspection Date is set on DATE ".$inputs['inspection_date']." and TIME ".$inputs['inspection_time']." Please be there around the time. ". $ulb_mstr['city'] ."",
						'type' => "Applicant",
						'message_type' => "Site Inspection",
						'mobile_no' => $inputs['mobile_no'],
                        'user_id' => $emp_details_id,
                        'created_on' =>date('Y-m-d H:i:s')
                    ];
				

				$data['SI_date_timeins']=$this->site_ins_model->SI_date_timeins($data);
				if($data['mobile_no']!=""){
					$data['SI_sms_citizen']=$this->water_consumer_sms_details_model->SI_sms_citizen($data);
				}
				//$data['isModalOpen']=true;
				flashToast('isModalOpen',true);
				return $this->response->redirect(base_url('waterfieldSiteInspection/search_consumer_for_siteInspection'));
			}
			
			if(isset($_POST['update_si']))
			{
				

				
				if($inputs['road_type']=='RMC' or $inputs['uses_type_id']==7) // 7 is apartment so app fee will be valid or if road type is RMC then road fee is valid
				{	
					$get_road_app_dtls=$this->road_app_fee_model->getLastRow();
					$road_app_fee_id=$get_road_app_dtls['id'];
					if($inputs['road_type']=='RMC' and $inputs['uses_type_id']!=7)
					{
						$road_fee=$get_road_app_dtls['road_fee'];
						$app_fee=0;
						$total_road_app_fee=$road_fee;
					}
					else if($inputs['road_type']!='RMC' and $inputs['uses_type_id']==7)
					{
						$road_fee=0;
						$app_fee=$get_road_app_dtls['appartment_fee'];
						$total_road_app_fee=$app_fee;
					}
					else if($inputs['road_type']=='RMC' and $inputs['uses_type_id']==7)
					{
						
						$road_fee=$get_road_app_dtls['road_fee'];
						$app_fee=$get_road_app_dtls['appartment_fee'];
						$count_flats=$inputs['flat_count'];
						$total_road_app_fee=$count_flats*$app_fee+$road_fee;

					}
				}
				else
				{
					$road_app_fee_id=0;
				}


				$get_rate_id=$this->apply_waterconn_model->get_rate_id($inputs['new_pipeline'],$inputs['uses_type_id'],$inputs['connection_through_id'],$inputs['is_regularization'],$inputs['applicant_category']);

				//print_r($get_rate_id);
				

				$rate_id=$get_rate_id['id'];

				$new_site_inspected_conn_charge=$get_rate_id['proc_fee']+$get_rate_id['reg_fee']+$get_rate_id['app_fee']+$get_rate_id['sec_fee']+$get_rate_id['conn_fee'];
				

				 $new_site_inspected_amount=$new_site_inspected_conn_charge+$total_road_app_fee;
				

				$get_paid_amount=$this->conn_charge_model->get_connection_charge_paid($inputs['related_id']);

				

				$paid_amount=$get_paid_amount['amount'];

				
				 $diff_amount=$new_site_inspected_amount-$paid_amount;
				
				if($diff_amount>0)
				{
					$payment_status=0;
					$connection_charge=array();
					$connection_charge['apply_connection_id']=$inputs['related_id'];
					$connection_charge['charge_for']='Site Inspection';
					$connection_charge['amount']=$diff_amount;
					$connection_charge['created_on']=date('Y-m-d');
					
				}
				else
				{
					$payment_status=1;
				}	
				
				//echo $payment_status;
				//print_r($get_rate_id);
				
				

				if($inputs['flat_count']!="")
				{
					$flat_count=$inputs['flat_count'];
				}
				else
				{
					$flat_count=0;
				}

                $site_ins_arr=array();
				$site_ins_arr['apply_connection_id']=$inputs['related_id'];
				$site_ins_arr['property_type_id']=$inputs['uses_type_id'];
				$site_ins_arr['pipeline_type_id']=$inputs['new_pipeline'];
				$site_ins_arr['connection_type_id']=$inputs['is_regularization'];
				$site_ins_arr['connection_through_id']=$inputs['connection_through_id'];
				$site_ins_arr['category']=$inputs['applicant_category'];
				$site_ins_arr['rate_id']=$rate_id;
				$site_ins_arr['flat_count']=$flat_count;
				$site_ins_arr['area_sqft']=$inputs['areasqft'];
				$site_ins_arr['area_sqmt']=$inputs['areasqft']*0.092903;
				$site_ins_arr['emp_details_id']=$this->emp_details_id;
				$site_ins_arr['pipeline_size']=$inputs['pipelinesize'];
				$site_ins_arr['pipeline_size_type']=$inputs['pipe_type'];
				$site_ins_arr['pipe_size']=$inputs['permissible_pipe_dia'];
				$site_ins_arr['pipe_type']=$inputs['permissible_pipe_qlty'];
				$site_ins_arr['ferrule_type_id']=$inputs['ferrule_size'];
				$site_ins_arr['road_type']=$inputs['road_type'];
				$site_ins_arr['road_app_fee_id']=$road_app_fee_id;
				$site_ins_arr['payment_status']=$payment_status;
				$site_ins_arr['created_on']=date('Y-m-d H:i:s');

				//	print_r($site_ins_arr);



				$update=$this->site_ins_model->SI_date_timeupdt($site_ins_arr);
				
					if($update)
					{
					
						if($this->conn_charge_model->checkExists($inputs['related_id'])==0 and !empty($connection_charge) and $diff_amount>0)
						{

							$this->conn_charge_model->insert_site_inspec_conn_charge($connection_charge);
						}
					
					}
			
				flashToast('isModalOpen',true);
				return $this->response->redirect(base_url('waterfieldSiteInspection/search_consumer_for_siteInspection'));
			}
			
			
			if(isset($_POST['cancl_si']))
			{
				$data = [
                        'related_id' => $inputs['related_id'],
                        'created_on' =>date('Y-m-d H:i:s')
                    ];
				
				$data['SI_date_timecancel']=$this->site_ins_model->SI_date_timecancel($data);
				
				flashToast('isModalOpen',true);
				return $this->response->redirect(base_url('waterfieldSiteInspection/search_consumer_for_siteInspection'));
			}
			
			if(isset($_POST['reject']))
			{
				$reject_array=array();
				$reject_array['level_pending_dtl_id']=$level_pending_dtl_id;
				$reject_array['remarks']=$inputs['remarks_si'];
				$reject_array['verification_status']=4;
				
				

				$update_reject_status = $this->model_water_level_pending_dtl->updateRejectStatusById($reject_array);

				return $this->response->redirect(base_url('waterfieldSiteInspection/search_consumer_for_siteInspection'));

			}
			if(isset($_POST['backtocitizen']))
			{
				
				$backtocitizen_array=array();
				$backtocitizen_array['level_pending_dtl_id']=$level_pending_dtl_id;
				$backtocitizen_array['remarks']=$inputs['remarks_si'];
				$backtocitizen_array['verification_status']=2;
				
				$update_backtocitizen_status = $this->model_water_level_pending_dtl->updateRejectStatusById($backtocitizen_array);

				return $this->response->redirect(base_url('waterfieldSiteInspection/search_consumer_for_siteInspection'));
			}
			
			
		}
		
		
		//print_r($data['applicant_pay_details']);
		//print_r($data['getremarks']);
		
		return view('water/water_connection/fieldVerification',$data);

	}

	public function view_site_inspetion()
	{
		if($this->request->getMethod()=='post')
		{
			
			$inputs = arrFilterSanitizeString($this->request->getVar());  
			
			$data['application_no']=$inputs['application_no'];
			$data['apply_date']=$inputs['apply_date'];
			
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
				//print_r($inputs);

				$data['property_type_list']=$this->water_property_model->property_list();
		        $data['conn_type_list']=$this->conn_type_model->conn_type_list();
		        $data['conn_through_list']=$this->conn_through_model->conn_through_list();
		        $data['pipeline_type_list']=$this->pipeline_model->pipeline_list();
		        $data['ward_list']=$this->ward_model->getWardList($data);
				// print_r($data['pipeline_type_list']);
		        $data['ferrule_list']=$this->ferrule_type_model->ferrule_type_list();
        
        		
		     	$apply_water_conn_id=$inputs['water_conn_id'];
				$data['connection_dtls']=$this->apply_waterconn_model->water_conn_details(md5($apply_water_conn_id));

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

					$rate_id=$get_rate_id['id'];
					$new_site_inspected_amount=$get_rate_id['proc_fee']+$get_rate_id['reg_fee']+$get_rate_id['app_fee']+$get_rate_id['sec_fee']+$get_rate_id['conn_fee'];
					


					$get_paid_amount=$this->conn_charge_model->get_connection_charge_paid($apply_water_conn_id);

					

					$paid_amount=$get_paid_amount['amount'];

					echo $diff_amount=$new_site_inspected_amount-$paid_amount;

					if($diff_amount>0)
					{
						
						$connection_charge=array();
						$connection_charge['apply_connection_id']=$apply_water_conn_id;
						$connection_charge['charge_for']='Site Inspection';
						$connection_charge['amount']=$diff_amount;
						$connection_charge['created_on']=date('Y-m-d');
						
					}
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
					$site_ins_arr['flat_count']=$data['flat_count'];
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
					
					if($diff_amount>0)
					{
						$site_ins_arr['payment_status']=0;
					}
					//print_r($site_ins_arr);

					// multiple time site inspection can be done if in case application is backwarded from executive officer to site inspection so check exist condition not checked , multiple entry can be done for same application 



					$level_pending_arr=array();
					$level_pending_arr['apply_connection_id']=$apply_water_conn_id;
					$level_pending_arr['sender_user_type_id']=$user_type_mstr_id;
					$level_pending_arr['receiver_user_type_id']=14;
					$level_pending_arr['emp_details_id']=$emp_details_id;
					$level_pending_arr['created_on']=date('Y-m-d H:i:s');
					

					$check_exists=$this->site_ins_model->checkdata($apply_water_conn_id);

					

					if($check_exists==0)
					{
							
						
					$insert_id=$this->site_ins_model->insertData($site_ins_arr);

					
					
					if($insert_id)
					{

						$this->site_ins_model->update_level_pending_status($apply_water_conn_id,$user_type_mstr_id);

						$check=$this->site_ins_model->check_exists($level_pending_arr);
						

						//$count=$this->conn_charge_model->checkConnectionChargePaid($apply_connection_id);

						// $this->conn_charge_model->checkExists($apply_water_conn_id);
						//						

						if($this->conn_charge_model->checkExists($apply_water_conn_id)==0 and !empty($connection_charge))
						{

							

							$this->conn_charge_model->insert_site_inspec_conn_charge($connection_charge);
							
						}
						

						if($check==0 and $diff_amount==0)
						{
						
							$this->site_ins_model->insert_level_pending($level_pending_arr);
				
						}
						
					
					}
					
					return $this->response->redirect(base_url('WaterFieldVerification/final_site_inspection_details/'.md5($apply_water_conn_id).'/'.md5($insert_id)));


					}
					else
					{

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
