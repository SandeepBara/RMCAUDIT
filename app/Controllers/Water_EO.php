<?php 
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_ward_mstr;
use App\Models\model_ulb_mstr;
use App\Models\model_view_ward_permission;
use App\Models\model_view_water_level_pending;
use App\Models\model_applicant_details;
use App\Models\model_applicant_doc;
use App\Models\model_water_level_pending_dtl;
use App\Models\WaterApplyNewConnectionModel;
use App\Models\model_view_water_connection;
use App\Models\model_view_applicant_doc;
use App\Models\model_water_consumer;
use App\Models\model_water_consumer_initial_meter;
use App\Models\WaterMeterStatusModel;
use App\Models\WaterConsumerDetailsModel;
use App\Models\Water_Transaction_Model;
use App\Models\water_level_pending_model;
use App\Models\model_water_sms_log;
use App\Models\WaterSiteInspectionModel;
use App\Models\Citizensw_water_model;
use App\Models\Siginsw_water_model;

class Water_EO extends AlphaController
{
    protected $db;
    protected $dbSystem;
    protected $property_db;
    protected $model_ulb_mstr;
    protected $model_ward_mstr;
    protected $model_view_ward_permission;
    protected $model_view_water_level_pending;
    protected $model_applicant_details;
    protected $model_applicant_doc;
    protected $model_water_level_pending_dtl;
    protected $WaterApplyNewConnectionModel;
    protected $model_view_water_connection;
    protected $model_view_applicant_doc;
    protected $model_water_consumer;
    protected $model_water_consumer_initial_meter;
    protected $model_water_sms_log;
    protected $Citizensw_water_model;
    protected $Siginsw_water_model;


    public function __construct()
    {        
        parent::__construct();
    	helper(['db_helper','form_helper','sms_helper']);
        if($db_name = dbConfig("water")){
            //echo $db_name;
            $this->db = db_connect($db_name);            
        }
        if($db_name = dbConfig("property"))
        {
            $this->property_db = db_connect($db_name);
        }
        if($db_system = dbSystem()){
            $this->dbSystem = db_connect($db_system); 
        }
        $this->model_ulb_mstr = new model_ulb_mstr($this->dbSystem);
        $this->model_ward_mstr = new model_ward_mstr($this->dbSystem);
        $this->model_view_ward_permission = new model_view_ward_permission($this->dbSystem);
        $this->model_view_water_level_pending = new model_view_water_level_pending($this->db);
        $this->model_applicant_details = new model_applicant_details($this->db);
        $this->model_applicant_doc = new model_applicant_doc($this->db);
        $this->model_water_level_pending_dtl = new model_water_level_pending_dtl($this->db);
        $this->WaterApplyNewConnectionModel = new WaterApplyNewConnectionModel($this->db);
        $this->model_view_water_connection = new model_view_water_connection($this->db);
        $this->model_view_applicant_doc = new model_view_applicant_doc($this->db);
        $this->model_water_consumer = new model_water_consumer($this->db);
        $this->model_water_consumer_initial_meter = new model_water_consumer_initial_meter($this->db);
        $this->meter_status_model=new WaterMeterStatusModel($this->db);
        $this->consumer_details_model=new WaterConsumerDetailsModel($this->db);
        $this->Water_Transaction_Model=new Water_Transaction_Model($this->db);
        $this->water_level_pending_model=new water_level_pending_model($this->db);
        $this->water_sms_log = new model_water_sms_log($this->db);
        $this->site_ins_model=new WaterSiteInspectionModel($this->db);
        $this->Citizensw_water_model = new Citizensw_water_model($this->db);
        $this->Siginsw_water_model = new Siginsw_water_model($this->db);
    }

    public function index()
	{       
        $data =(array)null;
        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
        $login_emp_details_id = $emp_mstr["id"];

        //Transaction Mode List
        $wardList = $this->model_view_ward_permission->getPermittedWard($login_emp_details_id);
        $data['wardList'] = $wardList;

        $receiver_user_type_id = $emp_mstr["user_type_mstr_id"];
        $ward="";
       
        $i=0;
        foreach($wardList as $key => $value){
            if($i==0){
                $ward=array($value['ward_mstr_id']);
            }else{
                array_push($ward, $value['ward_mstr_id']);
            }
            $i++;
         }
        helper(['form']);
        if($this->request->getMethod()=='post')
        {
            $data['ward_mstr_id'] = $this->request->getVar('ward_mstr_id');
             $data['from_date'] = $this->request->getVar('from_date');
             $data['to_date'] = $this->request->getVar('to_date');

            if($data['ward_mstr_id']!="")
            {
                $data['posts'] = $this->model_view_water_level_pending->waterjereceivebywardidList($receiver_user_type_id,$data['from_date'],$data['to_date'],$data['ward_mstr_id']);
            }
            else
            {
                $data['posts'] = $this->model_view_water_level_pending->waterjereceiveList($receiver_user_type_id,$data['from_date'],$data['to_date'],$ward);
            }

            $j=0;
            foreach($data['posts'] as $key => $value)
            {
                $wardd = $this->model_ward_mstr->getdatabyid($value['ward_id']);
                $owner = $this->model_applicant_details->applicantdetails($value['apply_connection_id']);
                $j=0;
                foreach($owner as $keyy => $val)
                {
                    if($j==0){
                        $data['posts'][$key]['owner_name']=array($val["applicant_name"]);
                        $data['posts'][$key]['mobile_no']=array($val["mobile_no"]);
                    }
                    else
                    {
                        array_push($data['posts'][$key]['owner_name'], $val["applicant_name"]);
                        array_push($data['posts'][$key]['mobile_no'], $val["mobile_no"]);
                    }
                    $j++;
                }
                $data['posts'][$key]['ward_no'] = $wardd['ward_no'];
            }
            return view('water/water_connection/water_eo_list', $data);
        }
        else
        {
            $data['from_date'] = date('Y-m-d');
            $data['to_date'] = date('Y-m-d');
            $data['posts'] = $this->model_view_water_level_pending->waterjereceiveList($receiver_user_type_id,$data['from_date'],$data['to_date'],$ward);
            $j=0;
            foreach($data['posts'] as $key => $value){
                $wardd = $this->model_ward_mstr->getdatabyid($value['ward_id']);
                $owner = $this->model_applicant_details->applicantdetails($value['apply_connection_id']);
                $j=0;
                foreach($owner as $keyy => $val){
                    if($j==0){
                        $data['posts'][$key]['owner_name']=array($val["applicant_name"]);
                        $data['posts'][$key]['mobile_no']=array($val["mobile_no"]);
                    }else{
                        array_push($data['posts'][$key]['owner_name'], $val["applicant_name"]);
                        array_push($data['posts'][$key]['mobile_no'], $val["mobile_no"]);
                    }
                    $j++;
                }
                $data['posts'][$key]['ward_no'] = $wardd['ward_no'];
            }
            return view('water/water_connection/water_eo_list', $data);
        }
	}


    public function view($id)
	{
        $data =(array)null;
        $Session = Session();
        $leveid = $id;
        if(!$leveid)
        {
            flashToast("message", "Application Not Faund");
            return $this->response->redirect(base_url()."/water_da/index");
        }
        $level_last_deta = $this->model_view_water_level_pending->waterlevelpendingdetailbyid($leveid); 
        if(!$level_last_deta)  
        {
            flashToast("message", "Record Not Faund");
            return $this->response->redirect(base_url()."/water_da/index");
        } 
        elseif($level_last_deta['verification_status']!=0) 
        {
            if(in_array($level_last_deta['verification_status'],[2,3]))
                flashToast("message", "Application Already BTC");
            elseif($level_last_deta['verification_status']==1)
                flashToast("message", "Application Already Verified");
            return $this->response->redirect(base_url()."/water_da/index");
        }
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
        $data['ulb_dtl'] = $this->model_ulb_mstr->getulb_list($data['ulb_mstr_id']);
        $ulb_short_nm=$data['ulb_dtl']['short_ulb_name'];
        $ulb_nm = substr($ulb_short_nm, 0, 3);

        $login_emp_details_id = $emp_mstr["id"];
        $sender_user_type_id = $emp_mstr["user_type_mstr_id"];
        $data['form'] = $this->model_view_water_level_pending->waterlevelpendingdetailbyid($id);
        $apply_connection_id_md5= md5($data['form']["apply_connection_id"]);
        $data['consumer_details']=$this->WaterApplyNewConnectionModel->water_conn_details($apply_connection_id_md5);
        $consumer_details=$data['consumer_details'];
        $data['owner_details']=$this->WaterApplyNewConnectionModel->water_owner_details($apply_connection_id_md5);         
         $appliction_no = $data['consumer_details']['application_no'];
        
        $data['transaction_details']=$this->Water_Transaction_Model->get_all_transactions((md5($data['form']['apply_connection_id'])));

        $apply_connection_id=$data['form']['apply_connection_id'];
        $ward_id=$data['consumer_details']['ward_id'];
        $data["water_conn_id"]=$apply_connection_id;
        $data["doc_list"]=$this->model_applicant_doc->getAllDocumentList($apply_connection_id);

        $data['getremarks']=$this->water_level_pending_model->getremarks(md5($apply_connection_id));
        $data['site_inspection_details']=$this->site_ins_model->getAllRecords($data['consumer_details']["id"]);

        //----------------------sms data -----------------------
		 $appliction = $data['consumer_details'];//print_var($appliction);die;
		 $owners = $data['owner_details'];
         //print_var($this->site_ins_model->tbl_ae_meter_inspection_get(['water_connection_id'=>$apply_connection_id]));
         $redir='';
        if($this->request->getMethod()=='post')
        {             
            if(isset($_POST['btn_approved_submit']))
            { 
                $this->db->transBegin();

                $ward_nm=$data['consumer_details']['ward_no'];
                $data['basic_details']=$data['consumer_details'];
                $data['ward_count']=$this->model_water_consumer->count_ward_by_wardid($ward_id);
                $sl_no = $data['ward_count']['ward_cnt'];
                $sl_noo = $sl_no+1;
                $serial_no = str_pad($sl_noo, 4, "0", STR_PAD_LEFT);
                $ward_nmm = str_pad($ward_nm, 2, "0", STR_PAD_LEFT);
                $consumer_no=$ulb_nm.'WC'.$ward_nmm.$serial_no;
                        
                $data = [
                        'remarks' => $this->request->getVar('remarks'),
                        'level_pending_dtl_id' => $id,
                        'apply_connection_id' => $apply_connection_id,
                        'consumer_no' => $consumer_no,
                        'k_no' => $data['basic_details']['elec_k_no'],
                        'bind_book_no' => $data['basic_details']['elec_bind_book_no'],
                        'account_no' => $data['basic_details']['elec_account_no'],
                        'electric_category_type' => $data['basic_details']['elec_category'],
                        'forward_date' => date('Y-m-d'),
                        'forward_time' => date('H:i:s'),
                        'emp_details_id' => $login_emp_details_id,
                        'doc_verify_emp_details_id'=> $login_emp_details_id,
                        'created_on' => date('Y-m-d H:i:s'),
                        'sender_user_type_id' => $sender_user_type_id,
                        'receiver_user_type_id'=> 16,
                        'level_pending_status'=> 1,
                        'verification_status'=> 1,
                        'doc_verify_status'=> 1,
                        'receiver_user_id' =>$login_emp_details_id,
                    ];
                    
                // PRINT_VAR($data);die;
                if($updateverify = $this->model_water_level_pending_dtl->updatelevelpendingById($data))
                {
                    //$this->model_water_level_pending_dtl->level_pending_updt($data);
                    if($updatependingstts = $this->WaterApplyNewConnectionModel->update_level_pending_status($data,$appliction['doc_verify_status']))
                    {
                        $check_exists=$this->model_water_consumer->check_exists($apply_connection_id);
                        //$tbl_ae_meter_inspection =  $this->site_ins_model->tbl_ae_meter_inspection_get(['water_connection_id'=>$apply_connection_id]);
                        if($check_exists==0)
                        {   
                            $consumer_last_id = $this->model_water_consumer->insertconsumerdetbyconid($data);
                            $consumer_no = $ulb_nm.$consumer_last_id.date('dmyhis');
                            $consumer_no_updated = $this->model_water_consumer->UpdateConsumerNo($consumer_last_id, $consumer_no);

                            if($consumer_last_id)
                            {   
                                    
                                $this->consumer_details_model->insertWaterConsumerOwner($apply_connection_id,$consumer_last_id,$login_emp_details_id);
                                
                                $consumer_meter_status_array=array();
                                $consumer_meter_status_array['consumer_id']=$consumer_last_id;
                                $consumer_meter_status_array['connection_date']=date('Y-m-d',strtotime(date('Y-m-d')." +15 days"));
                                $consumer_meter_status_array['meter_status']=1;//meter working
                                $consumer_meter_status_array['emp_details_id']=$login_emp_details_id;
                                $consumer_meter_status_array['connection_type']=1;// default meter
                                // 1-meter 2-gallon 3-fixed
                                $this->meter_status_model->insertData($consumer_meter_status_array);
                                
                                //print_r($consumer_last_id);

                                //$update_con_no = $this->model_water_consumer->updateconsumernobyconid($consumer_last_id,$consumer_no);
                                $insrt_con_initial_dtl = $this->model_water_consumer_initial_meter->insertData($consumer_last_id,$data['emp_details_id'],$data['created_on']);
                                //--------------send sms --------
                                $appliction = $this->model_water_consumer->consumerDetailsbyid($consumer_last_id);
		                        //$owners = $data['owner_details'];
                                $sms = Water(['consumer_no'=>$appliction['consumer_no']],'Application Approved');		 
                                if($sms['status']==true)
                                {
                                    foreach ($owners as $val )
                                    {
                                        $mobile = '';
                                        $mobile=$val['mobile_no'];
                                        $message=$sms['sms']; 
                                        $templateid=$sms['temp_id'];
                                        $sms_log_data = ['emp_id'=>$login_emp_details_id,
                                                        'ref_id'=>$consumer_last_id,
                                                        'ref_type'=>'tbl_consumer',
                                                        'mobile_no'=>$mobile,
                                                        'purpose'=>"Application Approved",
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
                                #----------------------------------
                                #------------sws push------------------
                                $sws_whare = ['apply_connection_id'=>$apply_connection_id];
                                $sws = $this->Citizensw_water_model->getData($sws_whare);
                                if(!empty($sws) && in_array($consumer_details['apply_from'],['sws','swsc']))
                                {
                                    $sw = [];
                                    $sw['sw_stage']= 20 ; 
                                    $where_sw = ['apply_connection_id'=>$apply_connection_id,'id'=> $sws['id']];                            
                                    $this->Citizensw_water_model->updateData($sw,$where_sw);
                                    
                                    $ulb_mstr_id = $_SESSION['ulb_dtl']['ulb_mstr_id']; 
                                    $path=base_url('citizenPaymentReceipt/view_memo/'.md5($apply_connection_id).'/'.$ulb_mstr_id);
                                    if($consumer_details['apply_from']=='sws')
                                    {  
                                        $push_sw=array();
                                        $push_sw['application_stage']=20;
                                        $push_sw['status']='Application Approved';
                                        $push_sw['acknowledgment_no']=$appliction_no;
                                        $push_sw['service_type_id']=$sws['service_id'];
                                        $push_sw['caf_unique_no']=$sws['caf_no'];
                                        $push_sw['department_id']=$sws['department_id'];
                                        $push_sw['Swsregid']=$sws['cust_id'];
                                        $push_sw['payable_amount ']='';
                                        $push_sw['payment_validity']='';
                                        $push_sw['payment_other_details']='';
                                        $push_sw['certificate_url']=$path;
                                        $push_sw['approval_date']=date('Y-m-d H:i:s');
                                        $push_sw['expire_date']=date('Y-m-d H:i:s');
                                        $push_sw['licence_no']=$appliction['consumer_no'];
                                        $push_sw['certificate_no']=$appliction['consumer_no'];
                                        $push_sw['customer_id']=$sws['cust_id'];
                                        $post_url = getenv('single_indow_push_url');
                                        $http = getenv('single_indow_push_http');
                                        $resp = httpPostJson($post_url,$push_sw,$http);
                                        // print_var($resp);
                                        $respons_data=[];
                                        $respons_data['apply_connection_id']=$apply_connection_id;
                                        $respons_data['response_msg']= json_encode(['url'=>$http.'/'.$post_url,
                                                                                        'data'=>$push_sw]);
                                        $respons_data['tbl_single_window_id']=$sws['id'];
                                        $respons_data['emp_id']=$login_emp_details_id;
                                        $respons_data['response_status']=json_encode($resp);
                                        $this->Citizensw_water_model->insertResponse($respons_data);
                                    }
                                    elseif($consumer_details['apply_from']=='swsc')
                                    {
                                        $emp_id = $login_emp_details_id;
                                        $ip = $this->request->getIPAddress();
                                        $login = $this->Siginsw_water_model->loginSinglewindowCitizen($emp_id,$ip);
                                        if(isset($login['status']) && $login['status']=="Success")
                                        {
                                            $update_window_singin = [
                                                "apply_connection_id" =>$apply_connection_id,
                                                "tbl_single_window_id" => $sws['id'],                                    
            
                                            ];
                                            $where_sigin = [
                                                "id"=>$login['single_window_singin_id']
                                            ];
                                            $this->Siginsw_water_model->updateData($update_window_singin,$where_sigin);
            
                                            $push_sw=array();
                                            $push_sw['application_stage']=20;
                                            $push_sw['current_status']='Application Approved';
            
                                            $push_sw['caf_no']=$sws['caf_no'];
                                            $push_sw['sws_reference_no']=$sws['department_id'];
                                            $push_sw['dept_reference_no']=$appliction_no;
                                            $push_sw['service_id']=$sws['service_id'];
                                            $push_sw['submission_date']=date('Y-m-d');
                                            $push_sw['approval_no']=$appliction['consumer_no'];
                                            $push_sw['approval_date']=date('Y-m-d');
                                            $push_sw['certificate_type']='URL';
                                            $push_sw['certificate_url']=$path;
                                            $push_sw['valid_upto'] = date('Y-m-d',strtotime(date('Y-m-d')."+21 days"));
                                            
                                            $post_url =getenv('citizen_single_indow_push_url');
                                            $http = getenv('citizen_single_indow_push_http');
            
                                            $resp = httpPostHeaderJson($post_url,$push_sw,$login['token'],$http);  
                                            
                                            // print_var(json_encode($push_sw));
                                            // print_var($resp);
                                            // die;
                                            $respons_data=[];
                                            $respons_data['apply_connection_id']=$apply_connection_id;
                                            $respons_data['response_msg']= json_encode(['url'=>$http.'/'.$post_url,
                                                                                            'data'=>$push_sw]);
                                            $respons_data['tbl_single_window_id']=$sws['id'];
                                            $respons_data['emp_id']=null;
                                            $respons_data['response_status']=json_encode($resp);
                                            $respons_data['token']          = $login['token'];
                                            $respons_data['tbl_single_window_singin'] = $login['single_window_singin_id'];
                                            $this->Citizensw_water_model->insertResponse($respons_data);
                                        }
        
                                    }
                                }
                                #--------------------------------------
                            }
                            if($this->db->transStatus()===FALSE)
                            {
                                $this->db->transRollback();
                            }
                            else
                            {
                                $this->db->transCommit();
                                return $this->response->redirect(base_url('Water_EO/eo_approved_view/'.md5($apply_connection_id).''));
                            }
                        }
                        else
                        { 
                            $redir='return';
                        }

                    }
                    else
                    { 
                        $redir='return';
                    }
                }
                else
                {
                    $redir='return';
                   
                }
                if($redir=='return')
                {
                    $this->db->transRollback();
                    return $this->response->redirect(base_url('Water_EO/view/'.$id));
                }
            }

            if(isset($_POST['btn_backward_submit']))
            {
                $data = [
                        'remarks' => $this->request->getVar('remarks'),
                         'level_pending_dtl_id' => $id,
                         'apply_connection_id' => $apply_connection_id,
                         'emp_details_id' => $login_emp_details_id,
                         'created_on' =>date('Y-m-d H:i:s'),
                         'forward_date' =>date('Y-m-d'),
                        'forward_time' =>date('H:i:s'),
                         'sender_user_type_id' => $sender_user_type_id,
                         'receiver_user_type_id'=>15,
                        'verification_status'=>3,
                        'receiver_user_id' =>$login_emp_details_id,
                    ];
                if($updatebackward = $this->model_water_level_pending_dtl->updatelevelpendingById($data))
                {
                    if($insertbackward = $this->model_water_level_pending_dtl->insrtlevelpendingdtl($data))
                    {
                        return $this->response->redirect(base_url('Water_EO/index/'));
                    }
                }
            }


            if(isset($_POST['btn_reject_submit']))
            {
                $reject_array=array();
                $reject_array['level_pending_dtl_id']=$id;
                $reject_array['remarks']=$this->request->getVar('remarks');
                $reject_array['verification_status']=4;
                $reject_array['receiver_user_id'] =$login_emp_details_id;
                $update_reject_status = $this->model_water_level_pending_dtl->updateRejectStatusById($reject_array);

                return $this->response->redirect(base_url('Water_AE/index'));
            }

            if(isset($_POST['btn_backtocitizen_submit']))
            {
                $backtocitizen_array=array();
                $backtocitizen_array['level_pending_dtl_id']=$id;
                $backtocitizen_array['remarks']=$this->request->getVar('remarks');
                $backtocitizen_array['verification_status']=2;
                $backtocitizen_array['receiver_user_id'] =$login_emp_details_id;
                $update_backtocitizen_status = $this->model_water_level_pending_dtl->updateRejectStatusById($backtocitizen_array);
                
                $level_last_deta = $this->model_water_level_pending_dtl->getDataNew(['md5(id::text)'=>$id],'*','tbl_level_pending');
				$btcdata = [
					'remarks' => $this->request->getVar('remarks'),
					'level_id' => $level_last_deta["id"],
					'apply_connection_id' => $appliction['id'],
					'sender_user_type_id' => $level_last_deta["sender_user_type_id"],
					'receiver_user_type_id'=> $level_last_deta["receiver_user_type_id"],
					'forward_date' =>$level_last_deta["forward_date"],
					'forward_time' => $level_last_deta["forward_time"],
					'created_on'=> $level_last_deta["created_on"],
					'verification_status'=> 2,
					'emp_details_id'=>$level_last_deta["emp_details_id"],
					'status'=>$level_last_deta["status"],
					'send_date' => $level_last_deta["send_date"], 
					'receiver_user_id' => $login_emp_details_id,
                    'ip_address'=> $_SERVER['REMOTE_ADDR'],
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
						$sms_log_data = ['emp_id'=>$login_emp_details_id,
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
                return $this->response->redirect(base_url('Water_AE/index'));
            }
        }
        
        return view('water/water_connection/water_eo_view', $data);
        
    }
    public function eo_approved_view($id)
	{
        $data =(array)null;
        $Session = Session();
        
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");

        $login_emp_details_id = $emp_mstr["id"];
        $sender_user_type_id = $emp_mstr["user_type_mstr_id"];
        $data['basic_details'] = $this->model_view_water_connection->getDatabyid($id);
        $apply_connection_id=$data['basic_details']['id'];
        $data['consumer_details'] = $this->model_water_consumer->consumerDetails($apply_connection_id);//print_r($data['consumer_details']);
        $data['consumer_initial_details']=array();
        if(!empty($data['consumer_details']))
            $data['consumer_initial_details'] = $this->model_water_consumer_initial_meter->consumerinitialDetails($data['consumer_details']['id']);
        $data['ward']  = $this->model_ward_mstr->getdatabyid($data['basic_details']['ward_id']);
        $data['basic_details']['ward_no']=$data["ward"]['ward_no'];
        $data['owner_details'] = $this->model_applicant_details->applicantdetails($apply_connection_id);
        return view('water/water_connection/water_eo_approved_view', $data);
    }
    
    public function consumer_list()
	{
        return redirect()->to(base_url()."/water_da/forward_list2");
        $data =(array)null;
        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
        $login_emp_details_id = $emp_mstr["id"];

        //Transaction Mode List
        $wardList = $this->model_view_ward_permission->getPermittedWard($login_emp_details_id);
        $data['wardList'] = $wardList;

        $receiver_user_type_id = $emp_mstr["user_type_mstr_id"];
        $ward="";

        $i=0;
        foreach($wardList as $key => $value){
            if($i==0){
                $ward=array($value['ward_mstr_id']);
            }else{
                array_push($ward, $value['ward_mstr_id']);
            }
            $i++;
         }
        helper(['form']);
        if($this->request->getMethod()=='post'){
            $data['ward_mstr_id'] = $this->request->getVar('ward_mstr_id');
             $data['from_date'] = $this->request->getVar('from_date');
             $data['to_date'] = $this->request->getVar('to_date');

            if($data['ward_mstr_id']!="")
            {
                $data['posts'] = $this->model_view_water_level_pending->waterconsumerbywardidList($receiver_user_type_id,$data['from_date'],$data['to_date'],$data['ward_mstr_id']);
            }
            else{
                $data['posts'] = $this->model_view_water_level_pending->waterconsumerList($receiver_user_type_id,$data['from_date'],$data['to_date'],$ward);
            }

            $j=0;
            foreach($data['posts'] as $key => $value){
                $wardd = $this->model_ward_mstr->getdatabyid($value['ward_id']);
                $owner = $this->model_applicant_details->applicantdetails($value['apply_connection_id']);
                $j=0;
                foreach($owner as $keyy => $val){
                    if($j==0){
                        $data['posts'][$key]['owner_name']=array($val["applicant_name"]);
                        $data['posts'][$key]['mobile_no']=array($val["mobile_no"]);
                    }else{
                        array_push($data['posts'][$key]['owner_name'], $val["applicant_name"]);
                        array_push($data['posts'][$key]['mobile_no'], $val["mobile_no"]);
                    }
                    $j++;
                }
                $data['posts'][$key]['ward_no'] = $wardd['ward_no'];
            }
            return view('water/water_connection/water_consumer_list', $data);
        }
        else{
            $data['from_date'] = date('Y-m-d');
            $data['to_date'] = date('Y-m-d');
            $data['posts'] = $this->model_view_water_level_pending->waterconsumerList($receiver_user_type_id,$data['from_date'],$data['to_date'],$ward);
            $j=0;
            foreach($data['posts'] as $key => $value){
                $wardd = $this->model_ward_mstr->getdatabyid($value['ward_id']);
                $owner = $this->model_applicant_details->applicantdetails($value['apply_connection_id']);
                $j=0;
                foreach($owner as $keyy => $val){
                    if($j==0){
                        $data['posts'][$key]['owner_name']=array($val["applicant_name"]);
                        $data['posts'][$key]['mobile_no']=array($val["mobile_no"]);
                    }else{
                        array_push($data['posts'][$key]['owner_name'], $val["applicant_name"]);
                        array_push($data['posts'][$key]['mobile_no'], $val["mobile_no"]);
                    }
                    $j++;
                }
                $data['posts'][$key]['ward_no'] = $wardd['ward_no'];
            }
            return view('water/water_connection/water_consumer_list', $data);
        }
	}

}