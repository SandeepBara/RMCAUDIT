<?php 
namespace App\Controllers;

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
use App\Models\water_transaction_fine_rebet_details_model;
use App\Models\WaterPenaltyInstallmentModel;
use App\Models\WaterPaymentModel;
use App\Models\WaterPenaltyModel;
use App\Models\model_water_sms_log;
use App\Models\model_view_ward_permission;
use App\Models\model_datatable;
use App\Models\model_view_water_level_pending;



class WaterfieldSiteInspection extends AlphaController
{	
	protected $db;
	protected $dbSystem;
	protected $db_property;
	
	//protected $db_name;
	protected $model_ward_mstr;
	protected $model_ulb_mstr;
	protected $modelUlb;
	protected $water_mobile_model;
	protected $conn_view_model;

	protected $conn_through_model;
	protected $conn_type_model;
	protected $pipeline_model;
	protected $ward_model;
	protected $water_property_model;
	protected $apply_waterconn_model;
	protected $site_ins_model;
	protected $conn_fee_model;
	protected $conn_charge_model;
	protected $ferrule_type_model;
	protected $model_applicant_doc;
	protected $water_applicant_details_model;
	protected $Water_Transaction_Model;
	protected $water_consumer_sms_details_model;
	protected $road_app_fee_model;
	protected $water_level_pending_model;
	protected $model_water_level_pending_dtl;
	protected $water_transaction_fine_rebet_details_model;
	protected $penalty_installment_model;
	protected $payment_model;
	protected $penalty_model;
	protected $model_water_sms_log;
	protected $model_datatable;
    protected $model_view_water_level_pending;	
	public $emp_details_id;
	protected $water_sms_log;
	protected $model_view_ward_permission;


	public function __construct()
    {

    	$Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
        $emp_details_id = $emp_mstr["id"];
		$this->emp_details_id = $emp_details_id;
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

        helper(['form','from_helper','sms_helper','utility_helper']);
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
		$this->water_transaction_fine_rebet_details_model=new water_transaction_fine_rebet_details_model($this->db);
   		$this->penalty_installment_model=new WaterPenaltyInstallmentModel($this->db);
   		$this->payment_model=new WaterPaymentModel($this->db);
   		$this->penalty_model=new WaterPenaltyModel($this->db);
		$this->water_sms_log = new model_water_sms_log($this->db);

		$this->model_view_ward_permission = new model_view_ward_permission($this->dbSystem);
		$this->model_datatable = new model_datatable($this->db);		
        $this->model_view_water_level_pending = new model_view_water_level_pending($this->db);		
    }

	public function __destruct()
	{
		if(!empty($this->db))
		{
			$this->db->close();
		}
		if(!empty( $this->db_property))
		{
			$this->db_property->close();
		}
		if(!empty( $this->dbSystem))
		{
			$this->dbSystem->close();
		}
		
	}

	public function search_consumer_for_siteInspection_copy()
	{
		
		$data=array();
		$Session=session();
		$emp_mstr = $Session->get("emp_details");
        $emp_details_id = $emp_mstr["id"];
        $user_type_mstr_id = $emp_mstr["user_type_mstr_id"];
		$receiver_user_type_id = $emp_mstr["user_type_mstr_id"];

        $wardList = $this->model_view_ward_permission->getPermittedWard($emp_details_id);
        $data['wardList'] = $wardList; 
		$wardIds='';
		$wardIds=array_map(function($val)
		{			
			return $val['ward_mstr_id'];
			
		},$data['wardList']);		
		$wardIds = implode(',',$wardIds);		
        $data['user_type']=$emp_mstr['user_type_mstr_id'];

		$w_l=array();
		if($user_type_mstr_id==13)
			$w_l = ['verified_by'=>'JuniorEngineer'];
		if($user_type_mstr_id==13)
			$w_l = ['verified_by'=>'AssistantEngineer'];
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
		$data['consumer_dtls']=$this->water_mobile_model->search_consumer($data,$w_l,$wardIds);
		return view('water/water_connection/search_consumer_for_siteInspection', $data);

	}
	public function search_consumer_for_siteInspection($mobile=null)
	{
		$data =(array)null;
		$data['mobile']=$mobile;
        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
        $login_emp_details_id = $emp_mstr["id"];
        $data['user_type']=$emp_mstr['user_type_mstr_id'];

        $receiver_user_type_id=$emp_mstr['user_type_mstr_id'];
        $user_type_mstr_id = $emp_mstr["user_type_mstr_id"];		

		$w_l=array();
		if($user_type_mstr_id==13)
			$w_l = 'JuniorEngineer';
		if($user_type_mstr_id!=13)
			$w_l = 'AssistantEngineer';

        $wardList = $this->model_view_ward_permission->getPermittedWard($login_emp_details_id);
        $data['wardList'] = $wardList;

        $sender_user_type_id = $emp_mstr["user_type_mstr_id"];
        $ward=""; 
        $ward_ids_array = array_map(function($val){
            return $val['ward_mstr_id'];
        },$wardList);
        $ward_ids_string = implode(',',$ward_ids_array); 

        $data['from_date'] = date('Y-m-d');
        $data['to_date'] = date('Y-m-d');

        if(!isset($_GET['page']) ||$_GET['page']=='clr')
        {
            session()->remove('temp');
        }

        if($this->request->getMethod()=='post')
        {
            $temp = $this->request->getVar(); 
            session()->set('temp',$temp);  
			       
        }

        
        $post_wher = " ";
		$data['keyword']='';
        $tempdata = session()->get('temp');
        if(!empty($tempdata))
        {
            $data['by_holding_owner_dtl'] = $tempdata['by_holding_owner_dtl'];                 
            $data['ward_mstr_id'] = $tempdata['ward_mstr_id'];            
            $data['keyword'] = $tempdata['keyword']??'';
            $data['from_date'] = $tempdata['from_date']??date('Y-m-d');
            $data['to_date'] = $tempdata['to_date']??date('Y-m-d'); 

            if(!in_array(strtoupper($data['ward_mstr_id']),["ALL",'']))
            {
                $ward_ids_string=$data['ward_mstr_id'];
            }            
            if($data['by_holding_owner_dtl']=='by_application_no' && trim($data['keyword'])!='')
            {	 
                $post_wher = " and view_water_application_details.application_no ilike('%".$data['keyword']."%')";
            }
            elseif($data['by_holding_owner_dtl']=='by_owner' && trim($data['keyword'])!='')
            {
                $post_wher = " and (view_water_application_details.applicant_name ilike('%".$data['keyword']."%')
                                OR view_water_application_details.mobile_no ilike('%".$data['keyword']."%')                                                                
                            )"; 
            }
            elseif($data['by_holding_owner_dtl']=='by_forward_date')
            {
                $post_wher= " and tbl_level_pending.forward_date ::date between '".$data['from_date']."' and '".$data['to_date']."' ";
				
            }

        }
		$join = ' LEFT JOIN ';
		if($mobile)
		{			       
        	//$join = " INNER JOIN ";
		}
		
        $select = "select view_water_application_details.*,tbl_level_pending.id as level_pending_dtl_id
					";
        $from = "   from view_water_application_details 
					join tbl_level_pending on tbl_level_pending.apply_connection_id=view_water_application_details.id 
					$join tbl_site_inspection on tbl_site_inspection.apply_connection_id=view_water_application_details.id 
						and scheduled_status=1 and verified_status isnull and verified_by::text = '$w_l'  
					 ";
        $where = " where  receiver_user_type_id=$receiver_user_type_id and verification_status=0 
						and tbl_level_pending.status=1 
						and view_water_application_details.ward_id IN ($ward_ids_string) $post_wher ";
        $order_by=" ORDER BY tbl_level_pending.forward_date DESC ,id desc ";

        $sql = $select.$from . $where . $order_by;
        $result = $this->model_datatable->getDatatable($sql);

        $data['consumer_dtls'] = $result['result']??null;
        $data['count']= $result['count']??0;
        $data['offset']= $result['offset']??0;
        // print_var($sql);//die;

        return view('water/water_connection/search_consumer_for_siteInspection_copy', $data);

	}
	
	public function field_verification($apply_water_conn_id, $level_pending_dtl_id,$mobile=null)
	{
		$data=array();
		$data['mobile']=$mobile;
		$mobi = $mobile;
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
		
		//$data['applicant_details']=$this->water_applicant_details_model->applicantDetails($data['connection_dtls']['id']);
		$data['applicant_details']=$this->apply_waterconn_model->water_owner_details($apply_water_conn_id);


		$data['applicant_doc_details']=$this->model_applicant_doc->getAllDocumentList($data['connection_dtls']['id']);
		$data['transaction_details']=$this->Water_Transaction_Model->get_all_transactions($apply_water_conn_id);
        $data['connection_charge']=$this->conn_charge_model->getAllConnectionCarge($apply_water_conn_id);

		$data['si_level_verify_dtls']=$this->model_water_level_pending_dtl->si_level_verify_dtls($apply_water_conn_id);
		
		$data['si_verify_dtls']=$this->site_ins_model->si_verify_dtls($apply_water_conn_id);
		if(!empty($data['si_verify_dtls']))
		{
			$si_verify_id = $data['si_verify_dtls']['id'];//print_var($data['si_verify_dtls']);
			$data['site_inspection_id']=$si_verify_id;
		}
		$data['getremarks']=$this->water_level_pending_model->getremarks($apply_water_conn_id);
		
		$data['SI_date_time']=$this->site_ins_model->SI_date_time($data['connection_dtls']['id'],array('verified_by'=>'JuniorEngineer','verified_status '=>null));
		// print_r($data['SI_date_time']);
		$data['isModalOpen']=false;

		$data['conn_fee_charge']=$this->conn_fee_model->conn_fee_charge($apply_water_conn_id);
		$data['penalty']=$this->penalty_installment_model->getUnpaidInstallmentSum($apply_water_conn_id);

        # cheque bounce penalty
        $data['other_penalty']=$this->penalty_model->getUnpaidPenaltySum($apply_water_conn_id);

        $rebate_details=$this->payment_model->get_rebate_details($apply_water_conn_id);

        // echo $data['rebate_details']['rebate'];
        $data['rebate']=$rebate_details['rebate'];
		$data['total_amount']=(($data['conn_fee_charge']['conn_fee']??0)+($data['penalty']??0)+($data['other_penalty']??0)-($data['rebate']??0));

		//----------------------sms data -----------------------
		$appliction = $data['connection_dtls'];
		$owners = $data['applicant_details'];
		
		if(!$level_pending_dtl_id)
        {
            flashToast("message", "Application Not Faund");
            return $this->response->redirect(base_url('waterfieldSiteInspection/search_consumer_for_siteInspection'));
        }
        $level_last_deta = $this->model_view_water_level_pending->waterlevelpendingdetailbyid($level_pending_dtl_id); 
        if(!$level_last_deta)  
        {
            flashToast("message", "Record Not Faund");
            return $this->response->redirect(base_url('waterfieldSiteInspection/search_consumer_for_siteInspection'));
        } 
        elseif($level_last_deta['verification_status']!=0) 
        {
            if($level_last_deta['verification_status']==2)
                flashToast("message", "Application Already BTC");
            elseif($level_last_deta['verification_status']==1)
                flashToast("message", "Application Already Verified");
				return $this->response->redirect(base_url('waterfieldSiteInspection/search_consumer_for_siteInspection'));
        } 
        $apply_connection_id = $level_last_deta['apply_connection_id'];
		$last_level_id=$level_last_deta['id'];
        $apply_connection_id_md5 = md5($apply_connection_id);
        $md5levelid = $level_pending_dtl_id;
		// print_var($level_last_deta);
		if($this->request->getMethod()=='post')
		{
			$inputs = arrFilterSanitizeString($this->request->getVar()); 
			// print_var(date('d-m-Y',strtotime($inputs['inspection_date']))== date('d-m-Y'));die;
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
						'verification_status' => 1,
						'receiver_user_id' =>$emp_details_id,
                        ];
				
				$data['level_pending_stts']=$this->apply_waterconn_model->update_level_pending_status($leveldata,$appliction['doc_verify_status']);
                $level_pending_updt=$this->model_water_level_pending_dtl->level_pending_updt($leveldata,array('id'=>$last_level_id));           
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
                        'remarks' => $inputs['remarks_si'],
                        'verification_status' => 3,	
						'receiver_user_id' =>$emp_details_id,					
                        ];
				
				$level_pending_updt=$this->model_water_level_pending_dtl->level_pending_updt($leveldata,array('id'=>$last_level_id));         
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
				if($data['mobile_no']!="")
				{
					$data['SI_sms_citizen']=$this->water_consumer_sms_details_model->SI_sms_citizen($data);
				}
				//-------------sms send ----------------------
				$time = explode(':',$inputs['inspection_time']);
				$amp = $time[0]>=12?'PM':'AM';
				$stime = ($time[0]>=12?($time[0]-12==0?12:($time[0]-12<10?('0'.($time[0]-12)):$time[0]-12)):$time[0]).":".$time[1].$amp;
				$sms = Water(['timestampe'=>date('d-m-Y',strtotime($inputs['inspection_date']))." ".$stime],'Site inspection set');//print_var($sms);die;		 
				if($sms['status']==true)
				{
					foreach ($owners as $val )
					{
						$mobile = '';
						$mobile=$val['mobile_no'];
						$message=$sms['sms']; 
						$templateid=$sms['temp_id'];
						$sms_log_data = ['emp_id'=>$emp_details_id,
										'ref_id'=>$appliction['id'],
										'ref_type'=>'tbl_apply_water_connection',
										'mobile_no'=>$mobile,
										'purpose'=>"Site inspection set",
										'template_id'=>$templateid,
										'message'=>$message
						];
						$sms_id =  $this->water_sms_log->insert_sms_log( $sms_log_data);
						$s = send_sms($mobile,$message, $templateid);
						
						if($s)
						{
							$update_sms_log = ['response'=>$s['response'],'smgid'=>$s['msg']];
							$up = $this->water_sms_log->update_sms_log(['id'=>$sms_id],$update_sms_log); 
							
						} 

					}
				}
				flashToast('isModalOpen',true);
				if(date('d-m-Y',strtotime($inputs['inspection_date']))== date('d-m-Y'))
				    return $this->response->redirect(base_url("waterfieldSiteInspection/field_verification/$apply_water_conn_id/$level_pending_dtl_id").($mobi?"/".$mobi:null));
				return $this->response->redirect(base_url('waterfieldSiteInspection/search_consumer_for_siteInspection'));
			}
			
			if(isset($_POST['update_si']))
			{
				$where='';
				 if($inputs['uses_type_id']==1)
                 {
                    $where=" and  (".$inputs['areasqft'].">=area_from_sqft and ".$inputs['areasqft']."<=area_upto_sqft)";
                 }
				 
				 $get_rate_dtls=$this->apply_waterconn_model->getNewRateId($inputs['uses_type_id'],$where);
                 $rate_id=$get_rate_dtls['id'];

                 $apply_water_conn['water_fee_mstr_id']=$rate_id;

				 	
                 if($get_rate_dtls['calculation_type']=='Fixed')
                 {
                    $conn_fee = $get_rate_dtls['conn_fee'];
                 }
                 else
                 {
                    $conn_fee = $get_rate_dtls['conn_fee']*$inputs['areasqft'];
                 }
                //echo $conn_fee;
				
				 #-------------------------------------------
				 
				 if($inputs['applicant_category']=="BPL")
				{
					$conn_fee=0;
				}
				else
				{
					$where = NULL;
					if(in_array($inputs['uses_type_id'],[1,7]))
					{
						$where=" and  (".$inputs['areasqft'].">=area_from_sqft and ".$inputs['areasqft']."<=area_upto_sqft)";
					}

					$get_rate_dtls=$this->apply_waterconn_model->getNewRateId($inputs['uses_type_id'], $where);
					$rate_id=$get_rate_dtls['id'];
					// print_r($get_rate_dtls);
					$apply_water_conn['water_fee_mstr_id']=$rate_id;


					if($get_rate_dtls['calculation_type']=='Fixed')
					{
						$conn_fee=$get_rate_dtls['conn_fee'];
					}
					else
					{
						$conn_fee=$get_rate_dtls['conn_fee']*$inputs['areasqft'];
					}
				}
				$effective_date= date('2021-01-01');
				$applicationStateDate = "";date('2022-04-12');
                $six_months_after=date('Y-m-d', strtotime($effective_date." + 6 months"));
				if($inputs['is_regularization']==2) // Regularization
				{
					if(date('Y-m-d')<$six_months_after && in_array($inputs['uses_type_id'],[1,7]))
					{
						$penalty=2000;
					}
					else if(in_array($inputs['uses_type_id'],[1,7]) && date('Y-m-d')>=$six_months_after)
					{
						$penalty=4000;
					}
					else if(!in_array($inputs['uses_type_id'],[1,7]) && date('Y-m-d')<$six_months_after)
					{
						$penalty=5000;
					}
					else
					{
						$penalty=10000;
					}

					// befor 2020-06-01
					if($appliction["apply_date"]<'2020-06-01'){
						$penalty = 1000;
						if(!in_array($inputs['uses_type_id'],[1,7])){
							$penalty = 2000;
						}
					}elseif($appliction["apply_date"]<='2020-12-31'){
						$penalty = 2000;
						if(!in_array($inputs['uses_type_id'],[1,7])){
							$penalty = 4000;
						}
					}
				}
				#-------------------------------------------
				if($appliction["apply_date"]<$effective_date) #old Application
				{
					$sql = "select * 
							from tbl_water_connection_fee_mstr 
							where property_type_id = ".($inputs["uses_type_id"])." 
								And  pipeline_type_id = ".$inputs["new_pipeline"]."
								AND connection_type_id = ".$inputs["is_regularization"]."
								AND connection_through_id = ".(isset($appliction["connection_through_id"])?$appliction["connection_through_id"]:1)."
								AND category = '".$inputs["applicant_category"]."'
								AND effect_date <='".$appliction["apply_date"]."'
							ORDER BY effect_date,id
							LIMIT 1 
							";
					$old_rate = $this->db->query($sql)->getFirstRow('array');
					$conn_fee = (
									($old_rate["proc_fee"]??0)
									+($old_rate["app_fee"]??0)
									+($old_rate["sec_fee"]??0)
									+($old_rate["conn_fee"]??0)
								);
					
					$penalty = ($old_rate["reg_fee"]??0);
				}

				if($appliction["apply_date"] < $applicationStateDate)
				{
					$conn_fee=0;
					$penalty=0;
				}
                
				
				$new_site_inspected_conn_charge = $conn_fee;
				$new_site_inspected_amount = $new_site_inspected_conn_charge+$penalty;				
				// $get_paid_amount = $this->conn_charge_model->get_connection_charge_paid_details($inputs['related_id']);
				$get_paid_amount = $this->conn_charge_model->get_All_connection_charge_paid_details($inputs['related_id']);
				
				$conn_fee_paid = $get_paid_amount['conn_fee'];
				$penalty_paid = $get_paid_amount['penalty'];

				$paid_amount = $conn_fee_paid + $penalty_paid;		

				$diff_conn_charge = $conn_fee - $conn_fee_paid;
				if($diff_conn_charge<0)
				{
					$diff_conn_charge=0;
				}

				
				$diff_penalty=$penalty-$penalty_paid;

				if($diff_penalty<0)
				{
					$diff_penalty=0;
				}
				
				// $diff_amount= $diff_conn_charge+$diff_penalty;
				$diff_amount = $new_site_inspected_amount - $paid_amount;
				
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
				
				// code for BPL application 
				// if(strtoupper($inputs['applicant_category'])=='BPL' && strtoupper($data['connection_dtls']['category'])=='BPL')
				// {
				// 	if($inputs['is_regularization']==$data['connection_dtls']['connection_type_id'])
				// 	{
				// 		$payment_status=1;
				// 		$diff_amount=0;
				// 		$diff_penalty=0;
				// 	}
				// }			
				
				
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
				$site_ins_arr['road_app_fee_id']=$road_app_fee_id??null;
				$site_ins_arr['payment_status']=$payment_status;
				$site_ins_arr['created_on']=date('Y-m-d H:i:s');
				$site_ins_arr['ward_id']=$data['connection_dtls']['ward_id'];
				$site_ins_arr['ts_map']=$inputs['map_type']??null;
				$si_where=array();
				if(!empty($si_verify_id))
					$si_where = array('id'=>$si_verify_id);
				
				$check_payment_done=$this->site_ins_model->checkPaymentDone(md5($inputs['related_id']),$si_where);
				
				if($check_payment_done==0)
				{
					// $si_where = array('id'=>$si_verify_id);
					$update=$this->site_ins_model->SI_date_timeupdt($site_ins_arr,$si_where);
				
					if($update)
					{
						if(($this->conn_charge_model->checkExists($inputs['related_id'])==0 and !empty($connection_charge) and $diff_amount>0) )
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
						
							
					}
					//print_var($diff_amount);die;
					//flashToast('isModalOpen',true);
					//echo'<script> window.location.reload();</script>';
					return $this->response->redirect($_SERVER['REQUEST_URI']);
				}
			}			

			if(isset($_POST['cancl_si']))
			{
				$data = [
                        'related_id' => $inputs['related_id'],
                        'created_on' =>date('Y-m-d H:i:s')
                    ];
				$si_where_c = array('id'=>$si_verify_id);
				$data['SI_date_timecancel']=$this->site_ins_model->SI_date_timecancel($data,$si_where_c);
				$sms = Water('','Site inspection cancelled');//print_var($sms);die;
				if($sms['status']==true)
				{
					foreach ($owners as $val )
					{
						$mobile = '';
						$mobile=$val['mobile_no'];
						$message=$sms['sms']; 
						$templateid=$sms['temp_id'];
						$sms_log_data = ['emp_id'=>$emp_details_id,
										'ref_id'=>$appliction['id'],
										'ref_type'=>'tbl_apply_water_connection',
										'mobile_no'=>$mobile,
										'purpose'=>"Site inspection cancelled",
										'template_id'=>$templateid,
										'message'=>$message
						];
						$sms_id =  $this->water_sms_log->insert_sms_log( $sms_log_data);
						$s = send_sms($mobile,$message, $templateid);
						
						if($s)
						{
							$update_sms_log = ['response'=>$s['response'],'smgid'=>$s['msg']];
							$up = $this->water_sms_log->update_sms_log(['id'=>$sms_id],$update_sms_log); 
							
						} 

					}
				}
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
				$backtocitizen_array['receiver_user_id']=$emp_details_id;
				
				$update_backtocitizen_status = $this->model_water_level_pending_dtl->updateRejectStatusById($backtocitizen_array);
				$this->apply_waterconn_model->updateLevelPendingStatus(['apply_connection_id'=> $data['connection_dtls']['id'], 'level_pending_status'=> 2]);
				$level_last_deta = $this->model_water_level_pending_dtl->getDataNew(['md5(id::text)'=>$level_pending_dtl_id],'*','tbl_level_pending');
				$btcdata = [
					'remarks' => $this->request->getVar('remarks'),
					'level_id' => $level_last_deta["id"],
					'apply_connection_id' => $data['connection_dtls']['id'],
					'sender_user_type_id' => $level_last_deta["sender_user_type_id"],
					'receiver_user_type_id'=> $level_last_deta["receiver_user_type_id"],
					'forward_date' =>$level_last_deta["forward_date"],
					'forward_time' => $level_last_deta["forward_time"],
					'created_on'=> $level_last_deta["created_on"],
					'verification_status'=> 2,
					'emp_details_id'=>$level_last_deta["emp_details_id"],
					'status'=>$level_last_deta["status"],
					'send_date' => $level_last_deta["send_date"], 
					'receiver_user_id' => $emp_details_id,
				];
				$this->model_water_level_pending_dtl->tbl_level_sent_back_dtl($btcdata);
				
				//--------------send sms --------
				$sms = Water(['application_no'=>$appliction['application_no']],'sent back');		 
				if($sms['status']==true)
				{
					foreach ($owners as $val )
					{
						$mobile = '';
						$mobile=$val['mobile_no'];
						$message=$sms['sms']; 
						$templateid=$sms['temp_id'];
						$sms_log_data = ['emp_id'=>$emp_details_id,
										'ref_id'=>$appliction['id'],
										'ref_type'=>'tbl_apply_water_connection',
										'mobile_no'=>$mobile,
										'purpose'=>"sent back",
										'template_id'=>$templateid,
										'message'=>$message
						];
						$sms_id =  $this->water_sms_log->insert_sms_log( $sms_log_data);
						$s = send_sms($mobile,$message, $templateid);
						
						if($s)
						{
							$update_sms_log = ['response'=>$s['response'],'smgid'=>$s['msg']];
							$up = $this->water_sms_log->update_sms_log(['id'=>$sms_id],$update_sms_log); 
							
						} 

					}
				}
				
				return $this->response->redirect(base_url('waterfieldSiteInspection/search_consumer_for_siteInspection'));
			}
			
		}

		if($mobile)
		{			
			return view('mobile/water/fieldVerification',$data);
		}
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
