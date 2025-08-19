<?php 
namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\model_datatable;
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
use App\Models\model_user_type_mstr;
use App\Models\water_level_pending_model;

use App\Models\model_water_sms_log;

class Water_DA extends AlphaController
{
    protected $db;
    protected $dbSystem;
    protected $property_db;
    protected $model_ward_mstr;
    protected $model_ulb_mstr;
    protected $model_view_ward_permission;
    protected $model_view_water_level_pending;
    protected $model_applicant_details;
    protected $model_applicant_doc;
    protected $model_water_level_pending_dtl;
    protected $WaterApplyNewConnectionModel;
    protected $model_view_water_connection;
    protected $model_view_applicant_doc;
    protected $model_user_type_mstr;
    protected $water_level_pending_model;
    protected $model_datatable;
    protected $model_water_sms_log;

    public function __construct()
    {
        parent::__construct();
    	helper(['db_helper', 'utility_helper','form_helper','sms_helper']);
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
        $this->model_user_type_mstr = new model_user_type_mstr($this->dbSystem);
        $this->water_level_pending_model=new water_level_pending_model($this->db);
        $this->model_datatable = new model_datatable($this->db);
        $this->water_sms_log = new model_water_sms_log($this->db);
    }


    public function index()
    {
        $data =(array)null;
        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
        $login_emp_details_id = $emp_mstr["id"];
        $receiver_user_type_id = $emp_mstr["user_type_mstr_id"];
        $wardList = $this->model_view_ward_permission->getPermittedWard($login_emp_details_id);//print_r($login_emp_details_id);
        $data['wardList'] = $wardList;
        $data['user_type']=$emp_mstr['user_type_mstr_id'];
        $ward_ids_array = array_map(function($val){
            return $val['ward_mstr_id'];
        },$wardList);
        $ward_ids_string = implode(',',$ward_ids_array); 

        $user_type_nm = $this->model_user_type_mstr->getdatabyid(MD5($receiver_user_type_id));
        $data['user_type_nm'] = $user_type_nm['user_type'];

        if(!isset($_GET['page']) ||$_GET['page']=='clr')
        {
            session()->remove('temp');
        }

        if($this->request->getMethod()=='post')
        {
            $temp = $this->request->getVar(); 
            session()->set('temp',$temp);           
        }
        $tempdata = session()->get('temp');
        if(!empty($tempdata))
        {
            $data['by_holding_owner_dtl'] = $tempdata['by_holding_owner_dtl'];                 
            $data['ward_mstr_id'] = $tempdata['ward_mstr_id'];
            $data['keyword'] = $tempdata['keyword'];  
                     
            if($data['ward_mstr_id']!="All")
            {
                $ward_ids_string=$data['ward_mstr_id'];
            }
            $where ='';
            if($data['by_holding_owner_dtl']=='by_application_no' && trim($data['keyword'])!='')
            {
                $where = " and application_no ilike('%".$data['keyword']."%')";
            }
            elseif($data['by_holding_owner_dtl']=='by_owner' && trim($data['keyword'])!='')
            {
                $where = " and (applicant_name ilike('%".$data['keyword']."%')
                                OR father_name ilike('%".$data['keyword']."%')
                                OR mobile_no ilike('%".$data['keyword']."%')                                
                            )"; 
            }
            
            # Pagination
            {
                $sql="SELECT * from view_water_level_pending 
                WHERE receiver_user_type_id=$receiver_user_type_id  and ward_id in ($ward_ids_string) and
                verification_status=0 and status=1 $where ";
                //print_var($sql);
                $data['posts'] = $this->model_datatable->getDatatable($sql);
                //print_var( $data['posts']);

            }
        }
        else
        {
            $sql="SELECT * from view_water_level_pending 
            WHERE receiver_user_type_id=$receiver_user_type_id and ward_id in ($ward_ids_string) and
            verification_status=0 and status=1";
            $data['posts'] = $this->model_datatable->getDatatable($sql);
        }
        // print_var($sql);
        return view('water/water_connection/water_da_list', $data);
    }
    
    public function view($leveid)
	{
        $data =(array)null;
        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
        $login_emp_details_id = $emp_mstr["id"];
        $sender_user_type_id = $emp_mstr["user_type_mstr_id"];
        $data['user_type']=$emp_mstr['user_type_mstr_id'];        
        
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
            if($level_last_deta['verification_status']==2)
                flashToast("message", "Application Already BTC");
            elseif($level_last_deta['verification_status']==1)
                flashToast("message", "Application Already Verified");
            return $this->response->redirect(base_url()."/water_da/index");
        } 
        $apply_connection_id = $level_last_deta['apply_connection_id'];
        $apply_connection_id_md5 = md5($apply_connection_id);
        $md5levelid = md5($level_last_deta['id']);
        $data['consumer_details']=$this->WaterApplyNewConnectionModel->water_conn_details(md5($apply_connection_id));        
        if($this->request->getMethod()=='post')
        {
            $inputs = filterSanitizeStringtoUpper($this->request->getVar());
            
            # Document Reject
            if(isset($inputs["btn_reject"]))
            {
                $uupdate_arr=[
                    'applicant_img_id'=> $inputs["applicant_doc_id"],
                    'app_img_remarks'=> $inputs['remarks'],
                    'app_img_verify'=> 2, //Rejected
                    'emp_details_id'=> $login_emp_details_id,
                    'created_on'=> "NOW()",
                ];
                $this->model_applicant_doc->updateappimgdocById($uupdate_arr);
                flashToast("message", "Document rejected successfully");
            }

            # Document Verify
            if(isset($inputs["btn_verify"]))
            {
                $uupdate_arr=[
                    'applicant_img_id'=> $inputs["applicant_doc_id"],
                    'app_img_remarks'=> 'Approved',
                    'app_img_verify'=> 1, //Verify
                    'emp_details_id'=> $login_emp_details_id,
                    'created_on'=> "NOW()",
                ];
                $this->model_applicant_doc->updateappimgdocById($uupdate_arr);
                flashToast("message", "Document approved successfully");
            }

            # Approve Application
            if(isset($inputs["btn_approve_submit"]))
            {
                $data = [
                    'remarks' => $this->request->getVar('remarks'),
                    'level_pending_dtl_id' => $md5levelid,
                    'apply_connection_id' => $apply_connection_id,
                    'emp_details_id' => $login_emp_details_id,
                    'sender_user_type_id' => $sender_user_type_id,
                    'receiver_user_type_id'=> 13,
                    'created_on' => date('Y-m-d H:i:s'),
                    'forward_date' => date('Y-m-d'),
                    'forward_time' => date('H:i:s'),
                    'verification_status'=> 1,
                    'level_pending_status'=> 0,
                    'doc_verify_status' => 1,
                    'doc_verify_date' => date('Y-m-d'),
                    'doc_verify_emp_details_id' => $login_emp_details_id,
                    'receiver_user_id'=>$login_emp_details_id
                ];
               
                if($updatelevelpending = $this->model_water_level_pending_dtl->updatelevelpendingById($data))
                {
                    if($updatesafpendingstts = $this->WaterApplyNewConnectionModel->update_level_pending_status($data))
                    {
                        $insrtlevelpending = $this->model_water_level_pending_dtl->insrtlevelpendingdtl($data);
                        flashToast("message", "Application approved successfully");
                        return $this->response->redirect(base_url('Water_DA/index'));
                    }
                }
            }
            
            # Back to Citizen
            if(isset($inputs["btn_app_submit"]))
            {                
                $data = [
                        'remarks' => $this->request->getVar('remarks'),
                        'level_pending_dtl_id' => $md5levelid,
                        'apply_connection_id' => $apply_connection_id,
                        'emp_details_id' => $login_emp_details_id,
                        'doc_verify_emp_details_id'=> $login_emp_details_id,
                        'created_on' =>date('Y-m-d H:i:s'),
                        'sender_user_type_id' => $sender_user_type_id,
                        'verification_status'=> 2,
                        'level_pending_status'=> 2,
                        'doc_verify_status'=> 2,
                        'receiver_user_id'=>$login_emp_details_id,
                    ];
                    $btcdata = [
                        'remarks' => $this->request->getVar('remarks'),
                        'level_id' => $leveid,
                        'apply_connection_id' => $apply_connection_id,
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
                    if($updatebacktocitizen = $this->model_water_level_pending_dtl->updatebacktocitizenById($data))
                    {
                        $this->model_water_level_pending_dtl->tbl_level_sent_back_dtl($btcdata);
                        if($updatesafpendingstts = $this->WaterApplyNewConnectionModel->update_level_pending_status($data))
                        {
                            //----------------------sms send -----------------------
                            $appliction = $this->WaterApplyNewConnectionModel->getData($apply_connection_id_md5);
                            $owners = $this->WaterApplyNewConnectionModel->water_owner_details(md5($appliction['id']));
                            $sms = Water(['application_no'=>$appliction['application_no']],'sent back');//print_var($owners);die;
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
                            
                            flashToast("message", "Application sent back to citizen");
                            return $this->response->redirect(base_url('water_da/index'));
                        }
                    }
            }
        }        
        $data['owner_details']=$this->WaterApplyNewConnectionModel->water_owner_details($apply_connection_id_md5);
        $data['getremarks']=$this->water_level_pending_model->getremarks($apply_connection_id_md5);
        $apply_connection_id=$data['consumer_details']["id"];
        $data["doc_list"]=$this->model_applicant_doc->getAllDocumentList($apply_connection_id);

        //print_var($data["doc_list"]);
        //echo"<pre>";print_var($data["getremarks"]);echo"</pre>";
        return view('water/water_connection/view_da', $data);
        
    }
    
    public function view_copy($apply_connection_id_md5)
	{
        $data =(array)null;
        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
        $login_emp_details_id = $emp_mstr["id"];
        $sender_user_type_id = $emp_mstr["user_type_mstr_id"];
        $data['user_type']=$emp_mstr['user_type_mstr_id'];
        if($this->request->getMethod()=='post')
        {
            $inputs = filterSanitizeStringtoUpper($this->request->getVar());
            
            # Document Reject
            if(isset($inputs["btn_reject"]))
            {
                $uupdate_arr=[
                    'applicant_img_id'=> $inputs["applicant_doc_id"],
                    'app_img_remarks'=> $inputs['remarks'],
                    'app_img_verify'=> 2, //Rejected
                    'emp_details_id'=> $login_emp_details_id,
                    'created_on'=> "NOW()",
                ];
                $this->model_applicant_doc->updateappimgdocById($uupdate_arr);
                flashToast("message", "Document rejected successfully");
            }

            # Document Verify
            if(isset($inputs["btn_verify"]))
            {
                $uupdate_arr=[
                    'applicant_img_id'=> $inputs["applicant_doc_id"],
                    'app_img_remarks'=> 'Approved',
                    'app_img_verify'=> 1, //Verify
                    'emp_details_id'=> $login_emp_details_id,
                    'created_on'=> "NOW()",
                ];
                $this->model_applicant_doc->updateappimgdocById($uupdate_arr);
                flashToast("message", "Document approved successfully");
            }

            # Approve Application
            if(isset($inputs["btn_approve_submit"]))
            {
                $data['lastRecord'] = $this->model_view_water_level_pending->getLastRecord($apply_connection_id_md5);
                
                $apply_connection_id=$data['lastRecord']["apply_connection_id"];
                $level_id_where=array('apply_connection_id' => $apply_connection_id,'id'=>138);
                $level_id_column=array('id');
                $last_level_id = $this->model_water_level_pending_dtl->getDataNew($level_id_where,$level_id_column);
                //echo"<pre>";print_r($apply_connection_id);print_r($data['lastRecord']["id"]); echo"</pre>";die();

                $data = [
                    'remarks' => $this->request->getVar('remarks'),
                    'level_pending_dtl_id' => md5($data['lastRecord']["id"]),
                    'apply_connection_id' => $apply_connection_id,
                    'emp_details_id' => $login_emp_details_id,
                    'sender_user_type_id' => $sender_user_type_id,
                    'receiver_user_type_id'=> 13,
                    'created_on' => date('Y-m-d H:i:s'),
                    'forward_date' => date('Y-m-d'),
                    'forward_time' => date('H:i:s'),
                    'verification_status'=> 1,
                    'level_pending_status'=> 0,
                    'doc_verify_status' => 1,
                    'doc_verify_date' => date('Y-m-d'),
                    'doc_verify_emp_details_id' => $login_emp_details_id,
                    'receiver_user_id'=>$login_emp_details_id
                ];
               
                if($updatelevelpending = $this->model_water_level_pending_dtl->updatelevelpendingById($data))
                {
                    if($updatesafpendingstts = $this->WaterApplyNewConnectionModel->update_level_pending_status($data)){
                        $insrtlevelpending = $this->model_water_level_pending_dtl->insrtlevelpendingdtl($data);
                        //$this->model_water_level_pending_dtl->level_pending_updt($data);

                        flashToast("message", "Application approved successfully");
                        return $this->response->redirect(base_url('Water_DA/index'));
                    }
                }
            }
            
            # Back to Citizen
            if(isset($inputs["btn_app_submit"]))
            {
                $data['lastRecord'] = $this->model_view_water_level_pending->getLastRecord($apply_connection_id_md5);
                $apply_connection_id=$data['lastRecord']["apply_connection_id"];
                $level_last_deta = $this->model_water_level_pending_dtl->getDataNew(['id'=>$data['lastRecord']['id']],'*','tbl_level_pending');
                
                
                $data = [
                        'remarks' => $this->request->getVar('remarks'),
                        'level_pending_dtl_id' => md5($data['lastRecord']["id"]),
                        'apply_connection_id' => $apply_connection_id,
                        'emp_details_id' => $login_emp_details_id,
                        'doc_verify_emp_details_id'=> $login_emp_details_id,
                        'created_on' =>date('Y-m-d H:i:s'),
                        'sender_user_type_id' => $sender_user_type_id,
                        'verification_status'=> 2,
                        'level_pending_status'=> 2,
                        'doc_verify_status'=> 2,
                        'receiver_user_id'=>$login_emp_details_id,
                    ];
                    $btcdata = [
                        'remarks' => $this->request->getVar('remarks'),
                        'level_id' => $level_last_deta["id"],
                        'apply_connection_id' => $apply_connection_id,
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
                    if($updatebacktocitizen = $this->model_water_level_pending_dtl->updatebacktocitizenById($data))
                    {
                        $this->model_water_level_pending_dtl->tbl_level_sent_back_dtl($btcdata);
                        if($updatesafpendingstts = $this->WaterApplyNewConnectionModel->update_level_pending_status($data))
                        {
                            //----------------------sms send -----------------------
                            $appliction = $this->WaterApplyNewConnectionModel->getData($apply_connection_id_md5);
                            $owners = $this->WaterApplyNewConnectionModel->water_owner_details(md5($appliction['id']));
                            $sms = Water(['application_no'=>$appliction['application_no']],'sent back');//print_var($owners);die;
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
                            
                            flashToast("message", "Application sent back to citizen");
                            return $this->response->redirect(base_url('water_da/index'));
                        }
                    }
            }
        }
        $data['consumer_details']=$this->WaterApplyNewConnectionModel->water_conn_details($apply_connection_id_md5);
        $data['owner_details']=$this->WaterApplyNewConnectionModel->water_owner_details($apply_connection_id_md5);
        $data['getremarks']=$this->water_level_pending_model->getremarks($apply_connection_id_md5);
        $apply_connection_id=$data['consumer_details']["id"];
        $data["doc_list"]=$this->model_applicant_doc->getAllDocumentList($apply_connection_id);

        //print_var($data["doc_list"]);
        //echo"<pre>";print_var($data["getremarks"]);echo"</pre>";
        return view('water/water_connection/view_da', $data);
        
    }

    # By Vijaya Mam
    public function view_old($id)
	{
        $data =(array)null;
        
        $Session = Session();
        
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
        $data['user_type']=$emp_mstr['user_type_mstr_id'];
        $login_emp_details_id = $emp_mstr["id"];
        $sender_user_type_id = $emp_mstr["user_type_mstr_id"];
        $data['form'] = $this->model_view_water_level_pending->waterlevelpendingdetailbyid($id);
        
        $data['consumer_details']=$this->WaterApplyNewConnectionModel->water_conn_details(md5($data['form']['apply_connection_id']));
        
        $data['getremarks']=$this->water_level_pending_model->getremarks(md5($data['form']['apply_connection_id']));
        $ward = $this->model_ward_mstr->getdatabyid($data['form']['ward_id']);
        $data['form']['ward_no']=$ward['ward_no'];
        $data['owner_list'] = $this->model_applicant_details->applicantdetails($data['form']['apply_connection_id']);
        //print_r($data['form']);
        //documents
        $verified_status='1';
        $verify_status='0';
        foreach($data['owner_list'] as $key => $value)
        {
                $app_other_doc='CONSUMER_PHOTO';
                $app_doc_type="ID Proof";
                
                /*****applicant img code*************/
                $app_img_verified = $this->model_applicant_doc->get_details_by_connid($data['form']['apply_connection_id'],$value['id'],$app_other_doc,$verified_status);
                
                if($app_img_verified['applicant_detail_id']!="")
                {
                   $data['owner_list'][$key]['img_stts']='1';
                   $data['owner_list'][$key]['applicant_img'] = $app_img_verified['document_path'];
                   $data['owner_list'][$key]['applicant_img_id'] = $app_img_verified['id'];
                   $data['owner_list'][$key]['applicant_img_verify_status'] = $app_img_verified['verify_status'];
                }
                else
                {
                	
                     $app_img = $this->model_applicant_doc->get_details_by_connid($data['form']['apply_connection_id'],$value['id'],$app_other_doc,$verify_status);
                    if($app_img['applicant_detail_id']!="")
                    {
                        $data['owner_list'][$key]['img_stts']='1';
                        $data['owner_list'][$key]['applicant_img'] = $app_img['document_path'];
                        $data['owner_list'][$key]['applicant_img_id'] = $app_img['id'];
                        $data['owner_list'][$key]['applicant_img_verify_status'] = $app_img['verify_status'];
                    }
                }
                /*****applicant doc code*************/
                $app_doc_verified = $this->model_applicant_doc->conownerdocnamebydoctype($data['form']['apply_connection_id'],$value['id'],$app_doc_type,$verified_status);
                if($app_doc_verified['applicant_detail_id']!="")
                {
                   $data['owner_list'][$key]['doc_stts']='1';
                   $data['owner_list'][$key]['applicant_doc'] = $app_doc_verified['document_path'];
                   $data['owner_list'][$key]['applicant_doc_name'] = $app_doc_verified['document_name'];
                   $data['owner_list'][$key]['applicant_doc_id'] = $app_doc_verified['id'];
                   $data['owner_list'][$key]['applicant_doc_verify_status'] = $app_doc_verified['verify_status'];
                }
                else{
                     $app_doc = $this->model_applicant_doc->conownerdocnamebydoctype($data['form']['apply_connection_id'],$value['id'],$app_doc_type,$verify_status);
                    //print_r($app_doc);
                    if($app_doc['applicant_detail_id']!="")
                    {
                        $data['owner_list'][$key]['doc_stts']='1';
                        $data['owner_list'][$key]['applicant_doc'] = $app_doc['document_path'];
                        $data['owner_list'][$key]['applicant_doc_name'] = $app_doc['document_name'];
                        $data['owner_list'][$key]['applicant_doc_id'] = $app_doc['id'];
                        $data['owner_list'][$key]['applicant_doc_verify_status'] = $app_doc['verify_status'];
                    }
                }

                /******************/

            }

        //payment receipt code
        $payment_doc="HOLDING PROOF";
        $data['payment_receipt_doc'] = $this->model_applicant_doc->getdocumentdet_by_conid($data['form']['apply_connection_id'],$payment_doc,$verified_status);
        if($data['payment_receipt_doc']['id']!="")
        {
            $data['pr_stts']='1';
            $data['pr_doc_nm'] = 'Last Payment Receipt';
            $data['pr_doc_name'] = $data['payment_receipt_doc']['document_path'];
            $data['pr_doc_id'] = $data['payment_receipt_doc']['id'];
            $data['pr_doc_verify_status'] = $data['payment_receipt_doc']['verify_status'];
        }
        else
        {
             $data['payment_receipt_docc'] = $this->model_applicant_doc->getdocumentdet_by_conid($data['form']['apply_connection_id'],$payment_doc,$verify_status);
            if($data['payment_receipt_docc']['id']!="")
            {
                $data['pr_stts']='1';
                $data['pr_doc_nm'] = 'Last Payment Receipt';
                $data['pr_doc_name'] = $data['payment_receipt_docc']['document_path'];
                $data['pr_doc_id'] = $data['payment_receipt_docc']['id'];
                $data['pr_doc_verify_status'] = $data['payment_receipt_docc']['verify_status'];
            }
        }
        //address proof code
        $add_doc="Address Proof";
        $data['address_proof_doc'] = $this->model_applicant_doc->getdocumentnamedet_by_conid($data['form']['apply_connection_id'],$add_doc,$verified_status);
        if($data['address_proof_doc']['id']!="")
        {
            $data['ap_stts']='1';
            $data['ap_doc_nm'] = 'Address Proof';
            $data['ap_document_name'] = $data['address_proof_doc']['document_name'];
            $data['ap_doc_name'] = $data['address_proof_doc']['document_path'];
            $data['ap_doc_id'] = $data['address_proof_doc']['id'];
            $data['ap_doc_verify_status'] = $data['address_proof_doc']['verify_status'];
        }
        else
        {

            $data['address_proof_docc'] = $this->model_applicant_doc->getdocumentnamedet_by_conid($data['form']['apply_connection_id'],$add_doc,$verify_status);

            if($data['address_proof_docc']['id']!="")
            {
                $data['ap_stts']='1';
                $data['ap_doc_nm'] = 'Address Proof';
                $data['ap_document_name'] = $data['address_proof_docc']['document_name'];
                $data['ap_doc_name'] = $data['address_proof_docc']['document_path'];
                $data['ap_doc_id'] = $data['address_proof_docc']['id'];
                $data['ap_doc_verify_status'] = $data['address_proof_docc']['verify_status'];
            }
        }

        //Connection Form code
        $connection_doc="Form(Scan Copy)";
        $data['connection_doc'] = $this->model_applicant_doc->getdocumentdet_by_conid($data['form']['apply_connection_id'],$connection_doc,$verified_status);
        if($data['connection_doc']['id']!="")
        {
            $data['cf_stts']='1';
            $data['cf_doc_nm'] = 'Connection Form';
            $data['cf_doc_name'] = $data['connection_doc']['document_path'];
            $data['cf_doc_id'] = $data['connection_doc']['id'];
            $data['cf_doc_verify_status'] = $data['connection_doc']['verify_status'];
        }
        else
        {
            $data['connection_docc'] = $this->model_applicant_doc->getdocumentdet_by_conid($data['form']['apply_connection_id'],$connection_doc,$verify_status);
            if($data['connection_docc']['id']!="")
            {
                $data['cf_stts']='1';
                $data['cf_doc_nm'] = 'Connection Form';
                $data['cf_doc_name'] = $data['connection_docc']['document_path'];
                $data['cf_doc_id'] = $data['connection_docc']['id'];
                $data['cf_doc_verify_status'] = $data['connection_docc']['verify_status'];
            }
        }
        //Electricity Bill code
        $electricity_doc="ELECTRICITY_NEW";
        $data['electricity_doc'] = $this->model_applicant_doc->getdocumentdet_by_conid($data['form']['apply_connection_id'],$electricity_doc,$verified_status);
        if($data['electricity_doc']['id']!="")
        {
            $data['ed_stts']='1';
            $data['ed_doc_nm'] = 'Electricity Bill';
            $data['ed_doc_name'] = $data['electricity_doc']['document_path'];
            $data['ed_doc_id'] = $data['electricity_doc']['id'];
            $data['ed_doc_verify_status'] = $data['electricity_doc']['verify_status'];
        }
        else
        {
            $data['electricity_docc'] = $this->model_applicant_doc->getdocumentdet_by_conid($data['form']['apply_connection_id'],$electricity_doc,$verify_status);
            if($data['electricity_docc']['id']!="")
            {
                $data['ed_stts']='1';
                $data['ed_doc_nm'] = 'Electricity Bill';
                $data['ed_doc_name'] = $data['electricity_docc']['document_path'];
                $data['ed_doc_id'] = $data['electricity_docc']['id'];
                $data['ed_doc_verify_status'] = $data['electricity_docc']['verify_status'];
            }
        }

        //Meter Bill code
        $meter_bill_doc="METER BILL";
        $data['meter_bill_doc'] = $this->model_applicant_doc->getdocumentdet_by_conid($data['form']['apply_connection_id'],$meter_bill_doc,$verified_status);
        if($data['meter_bill_doc']['id']!="")
        {	
            $data['mb_stts']='1';
            $data['mb_doc_nm'] = 'Meter Bill';
            $data['mb_doc_name'] = $data['meter_bill_doc']['document_path'];
            $data['mb_doc_id'] = $data['meter_bill_doc']['id'];
            $data['mb_doc_verify_status'] = $data['meter_bill_doc']['verify_status'];
        }
        else{
             $data['meter_bill_docc'] = $this->model_applicant_doc->getdocumentdet_by_conid($data['form']['apply_connection_id'],$meter_bill_doc,$verify_status);
            if($data['meter_bill_docc']['id']!="")
            {
                $data['mb_stts']='1';
                $data['mb_doc_nm'] = 'Meter Bill';
                $data['mb_doc_name'] = $data['meter_bill_docc']['document_path'];
                $data['mb_doc_id'] = $data['meter_bill_docc']['id'];
                $data['mb_doc_verify_status'] = $data['meter_bill_docc']['verify_status'];
            }
        }
        //BPL code
        $bpl_doc="BPL";
        $data['bpl_doc'] = $this->model_applicant_doc->getdocumentdet_by_conid($data['form']['apply_connection_id'],$bpl_doc,$verified_status);
        if($data['bpl_doc']['id']!="")
        {
            $data['bpl_stts']='1';
            $data['bpl_doc_nm'] = 'BPL';
            $data['bpl_doc_name'] = $data['bpl_doc']['document_path'];
            $data['bpl_doc_id'] = $data['bpl_doc']['id'];
            $data['bpl_doc_verify_status'] = $data['bpl_doc']['verify_status'];
        }
        else{
             $data['bpl_docc'] = $this->model_applicant_doc->getdocumentdet_by_conid($data['form']['apply_connection_id'],$bpl_doc,$verify_status);
            if($data['bpl_docc']['id']!="")
            {
                $data['bpl_stts']='1';
                $data['bpl_doc_nm'] = 'BPL';
                $data['bpl_doc_name'] = $data['bpl_docc']['document_path'];
                $data['bpl_doc_id'] = $data['bpl_docc']['id'];
                $data['bpl_doc_verify_status'] = $data['bpl_docc']['verify_status'];
            }
        }

        //count document
        $photo_doc='CONSUMER_PHOTO';
        $consumer_photo_doc="ID Proof";
        $data['payment_doc_cnt'] = $this->model_applicant_doc->count_uploaded_document($data['form']['apply_connection_id'],$payment_doc);
        $data['add_doc_cnt'] = $this->model_applicant_doc->count_uploaded_document($data['form']['apply_connection_id'],$add_doc);
        $data['photo_doc_cnt'] = $this->model_applicant_doc->count_uploaded_document($data['form']['apply_connection_id'],$photo_doc);
        $data['connection_doc_cnt'] = $this->model_applicant_doc->count_uploaded_document($data['form']['apply_connection_id'],$connection_doc);
        $data['electricity_doc_cnt'] = $this->model_applicant_doc->count_uploaded_document($data['form']['apply_connection_id'],$electricity_doc);
        $data['consumer_photo_doc_cnt'] = $this->model_applicant_doc->count_uploaded_document($data['form']['apply_connection_id'],$consumer_photo_doc);
        $data['meter_bill_doc_cnt'] = $this->model_applicant_doc->count_uploaded_document($data['form']['apply_connection_id'],$meter_bill_doc);
        $data['bpl_cnt'] = $this->model_applicant_doc->count_uploaded_document($data['form']['apply_connection_id'],$bpl_doc);
        $apply_connection_id=$data['form']['apply_connection_id'];
        
        //echo $photo_doc;
        //print_r($data['electricity_doc_cnt']);
        if($data['form']['category']=='BPL')
        {
            $data['app_cnt']=$data['payment_doc_cnt']['doc_cnt'] + $data['add_doc_cnt']['doc_cnt'] + $data['photo_doc_cnt']['doc_cnt'] + $data['connection_doc_cnt']['doc_cnt'] + $data['electricity_doc_cnt']['doc_cnt'] + $data['consumer_photo_doc_cnt']['doc_cnt'] + $data['meter_bill_doc_cnt']['doc_cnt'] + $data['bpl_cnt']['doc_cnt'];

        }
        else
        {
            $data['app_cnt']=$data['payment_doc_cnt']['doc_cnt'] + $data['add_doc_cnt']['doc_cnt'] + $data['photo_doc_cnt']['doc_cnt'] + $data['connection_doc_cnt']['doc_cnt'] + $data['electricity_doc_cnt']['doc_cnt'] + $data['consumer_photo_doc_cnt']['doc_cnt'] + $data['meter_bill_doc_cnt']['doc_cnt'];
        }
        //echo $data['app_cnt'];

        if($this->request->getMethod()=='post')
        {

        	if(isset($_POST['reject']))
        	{

                $data = [
                        'pr_document_id' => $this->request->getVar('pr_document_id'),
                        'ap_document_id' => $this->request->getVar('ap_document_id'),
                        'cf_document_id' => $this->request->getVar('cf_document_id'),
                        'ed_document_id' => $this->request->getVar('ed_document_id'),
                        'mb_document_id' => $this->request->getVar('mb_document_id'),
                        'bpl_document_id' => $this->request->getVar('bpl_document_id'),
                        'pr_remarks' => $this->request->getVar('pr_remarks'),
                        'ap_remarks' => $this->request->getVar('ap_remarks'),
                        'cf_remarks' => $this->request->getVar('cf_remarks'),
                        'ed_remarks' => $this->request->getVar('ed_remarks'),
                        'mb_remarks' => $this->request->getVar('mb_remarks'),
                        'bpl_remarks' => $this->request->getVar('bpl_remarks'),
                        'pr_verify' => $this->request->getVar('pr_verify'),
                        'ap_verify' => $this->request->getVar('ap_verify'),
                        'cf_verify' => $this->request->getVar('cf_verify'),
                        'ed_verify' => $this->request->getVar('ed_verify'),
                        'mb_verify' => $this->request->getVar('mb_verify'),
                        'bpl_verify' => $this->request->getVar('bpl_verify'),
                        'remarks' => $this->request->getVar('remarks'),
                        'level_pending_dtl_id' => $id,
                        'apply_connection_id' => $apply_connection_id,
                        'emp_details_id' => $login_emp_details_id,
                        'created_on' =>date('Y-m-d H:i:s'),
                        'sender_user_type_id' => $sender_user_type_id,
                        'verification_status'=>4,
                        'level_pending_status'=>4
                    ];
                  
                    
                if($updatebacktocitizen = $this->model_water_level_pending_dtl->updateRejectStatusById($data))
                {
                    if($updatesafpendingstts = $this->WaterApplyNewConnectionModel->update_level_pending_status($data))
                    {
                        ///applicant image stts
                        $app_img_verify = $this->request->getVar('app_img_verify');
                        $app_img_remarks = $this->request->getVar('app_img_remarks');
                        $applicant_img_id = $this->request->getVar('applicant_img_id');
                        if(isset($applicant_img_id))
                        {
                            $app_img_len = sizeof($applicant_img_id);
                            for($iv=0;$iv<$app_img_len;$iv++)
                            {
                                $data_up = [
                                    'applicant_img_id' => $applicant_img_id[$iv],
                                    'app_img_verify' => $app_img_verify[$iv],
                                    'app_img_remarks' => $app_img_remarks[$iv],
                                    'emp_details_id'=>$login_emp_details_id,
                                    'created_on'=>date('Y-m-d H:i:s')
                                ];

                                 $updateappimgdoc = $this->model_applicant_doc->updateappimgdocById($data_up);
                            }
                        }
                        ///applicant doc stts
                        $app_doc_verify = $this->request->getVar('app_doc_verify');
                        $app_doc_remarks = $this->request->getVar('app_doc_remarks');
                        $applicant_doc_id = $this->request->getVar('applicant_doc_id');
                        if(isset($applicant_doc_id))
                        {
                            $app_doc_len = sizeof($applicant_doc_id);

                            for($ivn=0;$ivn<$app_doc_len;$ivn++)
                            {
                                $data_u = [
                                    'applicant_doc_id' => $applicant_doc_id[$ivn],
                                    'app_doc_verify' => $app_doc_verify[$ivn],
                                    'app_doc_remarks' => $app_doc_remarks[$ivn],
                                    'emp_details_id'=>$login_emp_details_id,
                                    'created_on'=>date('Y-m-d H:i:s')
                                ];
                                $updateappdoc = $this->model_applicant_doc->updateappdocById($data_u);
                            }
                        }
                        $updateprdoc = $this->model_applicant_doc->updateprdocById($data);
                        $updateapdoc = $this->model_applicant_doc->updateapdocById($data);
                        $updatecfdoc = $this->model_applicant_doc->updatecfdocById($data);
                        $updateeddoc = $this->model_applicant_doc->updateeddocById($data);
                        $updatembdoc = $this->model_applicant_doc->updatembdocById($data);
                        $updatebpldoc = $this->model_applicant_doc->updatebpldocById($data);
                        return $this->response->redirect(base_url('water_da/index/'));
                    }
                }
        	}

            // Back to Citizen
            if(isset($_POST['btn_app_submit']))
            {

                $data = [
                        'pr_document_id' => $this->request->getVar('pr_document_id'),
                        'ap_document_id' => $this->request->getVar('ap_document_id'),
                        'cf_document_id' => $this->request->getVar('cf_document_id'),
                        'ed_document_id' => $this->request->getVar('ed_document_id'),
                        'mb_document_id' => $this->request->getVar('mb_document_id'),
                        'bpl_document_id' => $this->request->getVar('bpl_document_id'),
                        'pr_remarks' => $this->request->getVar('pr_remarks'),
                        'ap_remarks' => $this->request->getVar('ap_remarks'),
                        'cf_remarks' => $this->request->getVar('cf_remarks'),
                        'ed_remarks' => $this->request->getVar('ed_remarks'),
                        'mb_remarks' => $this->request->getVar('mb_remarks'),
                        'bpl_remarks' => $this->request->getVar('bpl_remarks'),
                        'pr_verify' => $this->request->getVar('pr_verify'),
                        'ap_verify' => $this->request->getVar('ap_verify'),
                        'cf_verify' => $this->request->getVar('cf_verify'),
                        'ed_verify' => $this->request->getVar('ed_verify'),
                        'mb_verify' => $this->request->getVar('mb_verify'),
                        'bpl_verify' => $this->request->getVar('bpl_verify'),
                        'remarks' => $this->request->getVar('remarks'),
                        'level_pending_dtl_id' => $id,
                        'apply_connection_id' => $apply_connection_id,
                        'emp_details_id' => $login_emp_details_id,
                        'created_on' =>date('Y-m-d H:i:s'),
                        'sender_user_type_id' => $sender_user_type_id,
                        'verification_status'=> 2,
                        'level_pending_status'=> 2
                    ];
                 

                if($updatebacktocitizen = $this->model_water_level_pending_dtl->updatebacktocitizenById($data))
                {
                    if($updatesafpendingstts = $this->WaterApplyNewConnectionModel->update_level_pending_status($data))
                    {
                        ///applicant image stts
                        $app_img_verify = $this->request->getVar('app_img_verify');
                        $app_img_remarks = $this->request->getVar('app_img_remarks');
                        $applicant_img_id = $this->request->getVar('applicant_img_id');
                        if(isset($applicant_img_id))
                        {
                            $app_img_len = sizeof($applicant_img_id);
                            for($iv=0;$iv<$app_img_len;$iv++)
                            {
                                $data_up = [
                                    'applicant_img_id' => $applicant_img_id[$iv],
                                    'app_img_verify' => $app_img_verify[$iv],
                                    'app_img_remarks' => $app_img_remarks[$iv],
                                    'emp_details_id'=>$login_emp_details_id,
                                    'created_on'=>date('Y-m-d H:i:s')
                                ];
                                $updateappimgdoc = $this->model_applicant_doc->updateappimgdocById($data_up);
                            }
                        }
                        ///applicant doc stts
                        $app_doc_verify = $this->request->getVar('app_doc_verify');
                        $app_doc_remarks = $this->request->getVar('app_doc_remarks');
                        $applicant_doc_id = $this->request->getVar('applicant_doc_id');
                        if(isset($applicant_doc_id))
                        {
                            $app_doc_len = sizeof($applicant_doc_id);

                            for($ivn=0;$ivn<$app_doc_len;$ivn++)
                            {
                                $data_u = [
                                    'applicant_doc_id' => $applicant_doc_id[$ivn],
                                    'app_doc_verify' => $app_doc_verify[$ivn],
                                    'app_doc_remarks' => $app_doc_remarks[$ivn],
                                    'emp_details_id'=>$login_emp_details_id,
                                    'created_on'=>date('Y-m-d H:i:s')
                                ];
                                $updateappdoc = $this->model_applicant_doc->updateappdocById($data_u);
                            }
                        }
                        $updateprdoc = $this->model_applicant_doc->updateprdocById($data);
                        $updateapdoc = $this->model_applicant_doc->updateapdocById($data);
                        $updatecfdoc = $this->model_applicant_doc->updatecfdocById($data);
                        $updateeddoc = $this->model_applicant_doc->updateeddocById($data);
                        $updatembdoc = $this->model_applicant_doc->updatembdocById($data);
                        $updatebpldoc = $this->model_applicant_doc->updatebpldocById($data);

                        flashToast("message", "Application sent back to citizen");
                        return $this->response->redirect(base_url('water_da/index/'));
                    }
                }
            }
            /**********/

            //Approve
            if(isset($_POST['btn_approve_submit']))
            {
                $data = [
                        'pr_document_id' => $this->request->getVar('pr_document_id'),
                        'ap_document_id' => $this->request->getVar('ap_document_id'),
                        'cf_document_id' => $this->request->getVar('cf_document_id'),
                        'ed_document_id' => $this->request->getVar('ed_document_id'),
                        'mb_document_id' => $this->request->getVar('mb_document_id'),
                        'bpl_document_id' => $this->request->getVar('bpl_document_id'),
                         'pr_remarks' => $this->request->getVar('pr_remarks'),
                        'ap_remarks' => $this->request->getVar('ap_remarks'),
                        'cf_remarks' => $this->request->getVar('cf_remarks'),
                        'ed_remarks' => $this->request->getVar('ed_remarks'),
                        'mb_remarks' => $this->request->getVar('mb_remarks'),
                        'bpl_remarks' => $this->request->getVar('bpl_remarks'),
                        'pr_verify' => $this->request->getVar('pr_verify'),
                        'ap_verify' => $this->request->getVar('ap_verify'),
                        'cf_verify' => $this->request->getVar('cf_verify'),
                        'ed_verify' => $this->request->getVar('ed_verify'),
                        'mb_verify' => $this->request->getVar('mb_verify'),
                        'bpl_verify' => $this->request->getVar('bpl_verify'),
                        'remarks' => $this->request->getVar('remarks'),
                         'level_pending_dtl_id' => $id,
                         'apply_connection_id' => $apply_connection_id,
                         'emp_details_id' => $login_emp_details_id,
                        'sender_user_type_id' => $sender_user_type_id,
                         'receiver_user_type_id'=>13,
                         'created_on' =>date('Y-m-d H:i:s'),
                        'forward_date' => date('Y-m-d'),
                         'forward_time' => date('H:i:s'),
                         'verification_status'=>1,
                        'level_pending_status'=>0,
                        'doc_verify_status' => 1,
                        'doc_verify_date' => date('Y-m-d'),
                        'doc_verify_emp_details_id' => $login_emp_details_id
                    ];
                   
                if($updatelevelpending = $this->model_water_level_pending_dtl->updatelevelpendingById($data))
                {
                    if($updatesafpendingstts = $this->WaterApplyNewConnectionModel->update_level_pending_status($data)){
                        $insrtlevelpending = $this->model_water_level_pending_dtl->insrtlevelpendingdtl($data);
                        $this->model_water_level_pending_dtl->level_pending_updt($data);
                        $updateverifystts = $this->WaterApplyNewConnectionModel->update_verify_status($data);
                        ///applicant image stts
                        $app_img_verify = $this->request->getVar('app_img_verify');
                        $app_img_remarks = $this->request->getVar('app_img_remarks');
                        $applicant_img_id = $this->request->getVar('applicant_img_id');
                        if(isset($applicant_img_id)){
                        $app_img_len = sizeof($applicant_img_id);
                        for($iv=0;$iv<$app_img_len;$iv++)
                        {
                            $data_up = [
                                'applicant_img_id' => $applicant_img_id[$iv],
                                'app_img_verify' => $app_img_verify[$iv],
                                'app_img_remarks' => $app_img_remarks[$iv],
                                'emp_details_id'=>$login_emp_details_id,
                                'created_on'=>date('Y-m-d H:i:s')
                            ];

                             $updateappimgdoc = $this->model_applicant_doc->updateappimgdocById($data_up);
                        }
                            }
                        ///applicant doc stts
                        $app_doc_verify = $this->request->getVar('app_doc_verify');
                        $app_doc_remarks = $this->request->getVar('app_doc_remarks');
                        $applicant_doc_id = $this->request->getVar('applicant_doc_id');
                        if(isset($applicant_doc_id)){
                        $app_doc_len = sizeof($applicant_doc_id);

                        for($ivn=0;$ivn<$app_doc_len;$ivn++)
                        {
                            $data_u = [
                                'applicant_doc_id' => $applicant_doc_id[$ivn],
                                'app_doc_verify' => $app_doc_verify[$ivn],
                                'app_doc_remarks' => $app_doc_remarks[$ivn],
                                'emp_details_id'=>$login_emp_details_id,
                                'created_on'=>date('Y-m-d H:i:s')
                            ];
                            $updateappdoc = $this->model_applicant_doc->updateappdocById($data_u);
                        }
                            }
                         $updateprdoc = $this->model_applicant_doc->updateprdocById($data);
                         $updateapdoc = $this->model_applicant_doc->updateapdocById($data);
                         $updatecfdoc = $this->model_applicant_doc->updatecfdocById($data);
                         $updateeddoc = $this->model_applicant_doc->updateeddocById($data);
                         $updatembdoc = $this->model_applicant_doc->updatembdocById($data);
                         $updatebpldoc = $this->model_applicant_doc->updatebpldocById($data);
                        
                        flashToast("message", "Application approved successfully");
                        return $this->response->redirect(base_url('water_da/index/'));
                    }
                }

            }            

        }
        else
        {
             return view('water/water_connection/water_da_view', $data);
        }
    }

    public function da_back_to_citizen_list()
	{
        $data =(array)null;
        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
        //print_r($emp_mstr);
        $login_emp_details_id = $emp_mstr["id"];
        $wardList = $this->model_view_ward_permission->getPermittedWard($login_emp_details_id);
        $data['wardList'] = $wardList;
        $receiver_user_type_id = $emp_mstr["user_type_mstr_id"];

        if($this->request->getMethod()=='post')
        {
            $data['ward_mstr_id'] = $this->request->getVar('ward_mstr_id');
            $data['from_date'] = $this->request->getVar('from_date');
            $data['to_date'] = $this->request->getVar('to_date');

            if($data['ward_mstr_id']=="All")
            {
                $ward=(array)null;
                foreach($data['wardList'] as $row)
                    $ward[]=$row["ward_mstr_id"];
                $ward=implode(",", $ward);
            }
            else
            {
                $ward=$data['ward_mstr_id'];
            }

            # Pagination
            {
                $sql="SELECT * from view_water_level_pending 
                WHERE receiver_user_type_id=$receiver_user_type_id and date(created_on) between '$data[from_date]' and  '$data[to_date]' and ward_id in ($ward) and
                verification_status=2 and status=1";
                //print_var($sql);
                $data['posts'] = $this->model_datatable->getDatatable($sql);

            }
        }

        return view('water/water_connection/da_back_to_citizen_list', $data);
    }

    public function boc_document_verification_view($id)
	{
        $data =(array)null;
        $Session = Session();
        
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
        $data['user_type']=$emp_mstr['user_type_mstr_id'];
        $login_emp_details_id = $emp_mstr["id"];
        $sender_user_type_id = $emp_mstr["user_type_mstr_id"];
        $data['basic_details'] = $this->model_view_water_connection->getDatabyid($id);
        $data['ward'] = $this->model_ward_mstr->getdatabyid($data['basic_details']['ward_id']);
        $data['owner_details'] = $this->model_applicant_details->applicantdetails($data['basic_details']['id']);
        $verify_status='0';
        foreach($data['owner_details'] as $key => $value){
                $app__doc='CONSUMER_PHOTO';
                $data['owner_details'][$key]['saf_owner_img_list'] = $this->model_applicant_doc->get_ownerimgdetails_by_safid($data['basic_details']['id'],$value['id'],$app__doc);

                $app_doc_type="ID Proof";
                $data['owner_details'][$key]['saf_owner_doc_list'] = $this->model_applicant_doc->conownerdocdetbyid($data['basic_details']['id'],$value['id'],$app_doc_type);

            }
        //print_r($data['owner_details']);
        $apply_connection_id=$data['basic_details']['id'];

        $payment_doc="HOLDING PROOF";
        $data['payment_receipt_doc']=$this->model_applicant_doc->getdocdet_by_conid($apply_connection_id,$payment_doc);
        $add_doc="Address Proof";
        $data['address_proof_doc']=$this->model_applicant_doc->getdocnamedet_by_conid($apply_connection_id,$add_doc);
        $photo_doc="ID Proof";
        $data['photo_id_proof_doc']=$this->model_applicant_doc->getdocnamedet_by_conid($apply_connection_id,$photo_doc);
        $connection_doc="Form(Scan Copy)";
        $data['connection_doc']=$this->model_applicant_doc->getdocdet_by_conid($apply_connection_id,$connection_doc);
        $electricity_doc="ELECTRICITY_NEW";
        $data['electricity_doc']=$this->model_applicant_doc->getdocdet_by_conid($apply_connection_id,$electricity_doc);
        $consumer_photo_doc="CONSUMER_PHOTO";
        $data['consumer_photo_doc']=$this->model_applicant_doc->getdocdet_by_conid($apply_connection_id,$consumer_photo_doc);
        $meter_bill_doc="METER BILL";
        $data['meter_bill_doc']=$this->model_applicant_doc->getdocdet_by_conid($apply_connection_id,$meter_bill_doc);
        $bpl_doc="BPL";
        $data['bpl_doc']=$this->model_applicant_doc->getdocdet_by_conid($apply_connection_id,$bpl_doc);

        $data['remark'] = $this->model_water_level_pending_dtl->backtocitizen_dl_remarks_by_con_id($apply_connection_id);
        $data['basic_details']['remarks'] = $data['remark']['remarks'];

        
        return view('water/water_connection/da_back_to_citizen_view', $data);
    }


    public function da_approved_list()
	{
        $data =(array)null;
        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
        $login_emp_details_id = $emp_mstr["id"];
        $data['user_type']=$emp_mstr['user_type_mstr_id'];
        $wardList = $this->model_view_ward_permission->getPermittedWard($login_emp_details_id);
        $data['wardList'] = $wardList;
        //print_r($emp_mstr);

        $sender_user_type_id = $emp_mstr["user_type_mstr_id"];
        $ward="";

        $i=0;
        foreach($wardList as $key => $value)
        {
            if($i==0)
            {
                $ward=array($value['ward_mstr_id']);
            }
            else
            {
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

                $data['posts'] = $this->model_view_water_level_pending->wardwise_daapprovedList($sender_user_type_id,$data['from_date'],$data['to_date'],$data['ward_mstr_id']);
            }
            else
            {
            
                $data['posts'] = $this->model_view_water_level_pending->daapprovedList($sender_user_type_id,$data['from_date'],$data['to_date'],$ward);
            }

        //print_r($data['posts']);
        $j=0;
        foreach($data['posts'] as $key => $value){
                   $wardd = $this->model_ward_mstr->getdatabyid($value['ward_id']);
                   $owner = $this->model_applicant_details->applicantdetails($value['apply_connection_id']);
                    $j=0;
                   foreach($owner as $keyy => $val){
                        //$ow[$key][$keyy]['owner']= $val["owner_name"];
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
            return view('water/water_connection/da_approved_list', $data);
            }
        else
            {
            $data['from_date'] = date('Y-m-d');
            $data['to_date'] = date('Y-m-d');
            $data['posts'] = $this->model_view_water_level_pending->daapprovedList($sender_user_type_id,$data['from_date'],$data['to_date'],$ward);
        //print_r($data['posts']);
        $j=0;
        foreach($data['posts'] as $key => $value){
                   $wardd = $this->model_ward_mstr->getdatabyid($value['ward_id']);
                  $owner = $this->model_applicant_details->applicantdetails($value['apply_connection_id']);
                    $j=0;
                   foreach($owner as $keyy => $val){
                        //$ow[$key][$keyy]['owner']= $val["owner_name"];
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
            return view('water/water_connection/da_approved_list', $data);
        }
       // print_r($data['posts']);
	}

    public function da_approved_view($id=null)
	{
        $data =(array)null;
        $Session = Session();
        
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
        $data['user_type']=$emp_mstr['user_type_mstr_id'];
        $login_emp_details_id = $emp_mstr["id"];
        $sender_user_type_id = $emp_mstr["user_type_mstr_id"];
        $data['form'] = $this->model_view_water_level_pending->waterlevelpendingdetailbyid($id);
        $data['basic_details'] = $this->model_view_water_connection->getDatabyid(md5($data['form']['apply_connection_id']));
        $data['ward']  = $this->model_ward_mstr->getdatabyid($data['form']['ward_id']);
        $data['form']['ward_no']=$data['ward']['ward_no'];
        $data['owner_details'] = $this->model_applicant_details->applicantdetails($data['form']['apply_connection_id']);

        $verify_status='1';
        foreach($data['owner_details'] as $key => $value){
                $app__doc='consumer_photo';
                $data['owner_details'][$key]['saf_owner_img_list'] = $this->model_applicant_doc->get_verifiedownerimgdetails_by_safid($data['basic_details']['id'],$value['id'],$app__doc);

                $app_doc_type="ID Proof";
                $data['owner_details'][$key]['saf_owner_doc_list'] = $this->model_view_applicant_doc->get_verifiedownerdocdetails_by_conid($data['basic_details']['id'],$value['id'],$app_doc_type);

            }
        //print_r($data['owner_details']);
        $apply_connection_id=$data['basic_details']['id'];

        $payment_doc="payment_receipt";
        $data['payment_receipt_doc']=$this->model_applicant_doc->getverifieddocdet_by_conid($apply_connection_id,$payment_doc);
        $add_doc="address_proof";
        $data['address_proof_doc']=$this->model_view_applicant_doc->get_verifiedaddressdocdetails_by_conid($apply_connection_id,$add_doc);
        $connection_doc="connection_form";
        $data['connection_doc']=$this->model_applicant_doc->getverifieddocdet_by_conid($apply_connection_id,$connection_doc);
        $electricity_doc="electricity_bill";
        $data['electricity_doc']=$this->model_applicant_doc->getverifieddocdet_by_conid($apply_connection_id,$electricity_doc);
        $meter_bill_doc="meter_bill";
        $data['meter_bill_doc']=$this->model_applicant_doc->getverifieddocdet_by_conid($apply_connection_id,$meter_bill_doc);
        $bpl_doc="bpl";
        $data['bpl_doc']=$this->model_applicant_doc->getverifieddocdet_by_conid($apply_connection_id,$bpl_doc);

        $data['remark'] = $this->model_water_level_pending_dtl->approved_dl_remarks_by_con_id($apply_connection_id);
        $data['basic_details']['remarks'] = $data['remark']['remarks'];

        return view('water/water_connection/da_approved_view', $data);
    }


    public function forward_list()
	{
        $data =(array)null;
        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
        $login_emp_details_id = $emp_mstr["id"];
        $data['user_type']=$emp_mstr['user_type_mstr_id'];
        $wardList = $this->model_view_ward_permission->getPermittedWard($login_emp_details_id);
        $data['wardList'] = $wardList;
        //print_r($emp_mstr);

        $sender_user_type_id = $emp_mstr["user_type_mstr_id"];
        $ward="";

        $i=0;
        foreach($wardList as $key => $value)
        {
            if($i==0)
            {
                $ward=array($value['ward_mstr_id']);
            }
            else
            {
                array_push($ward, $value['ward_mstr_id']);
            }
            $i++;
        }

        if($this->request->getMethod()=='post')
        {
            $data['ward_mstr_id'] = $this->request->getVar('ward_mstr_id');
            $data['from_date'] = $this->request->getVar('from_date');
            $data['to_date'] = $this->request->getVar('to_date');
            if($data['ward_mstr_id']!="")
            {
                $data['posts'] = $this->model_view_water_level_pending->wardwise_forwardList($sender_user_type_id,$data['from_date'],$data['to_date'],$data['ward_mstr_id']);
            }
            else
            {
                $data['posts'] = $this->model_view_water_level_pending->forwardList($sender_user_type_id,$data['from_date'],$data['to_date'],$ward);
            }

            //print_r($data['posts']);
            $j=0;
            foreach($data['posts'] as $key => $value)
            {
                $wardd = $this->model_ward_mstr->getdatabyid($value['ward_id']);
                $user_type_nm = $this->model_user_type_mstr->getdatabyid(md5($value['receiver_user_type_id']));
                $owner = $this->model_applicant_details->applicantdetails($value['apply_connection_id']);
                $j=0;
                foreach($owner as $keyy => $val)
                {
                    //$ow[$key][$keyy]['owner']= $val["owner_name"];
                    if($j==0)
                    {
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
                $data['posts'][$key]['user_type'] = $user_type_nm['user_type'];
            }
            
            return view('water/water_connection/water_forward_list', $data);
        }
        else
        {
            $data['from_date'] = date('Y-m-d');
            $data['to_date'] = date('Y-m-d');
            $data['posts'] = $this->model_view_water_level_pending->forwardList($sender_user_type_id,$data['from_date'],$data['to_date'],$ward);
            //print_r($data['posts']);
            $j=0;
            foreach($data['posts'] as $key => $value)
            {
                   $wardd = $this->model_ward_mstr->getdatabyid($value['ward_id']);
                   $user_type_nm = $this->model_user_type_mstr->getdatabyid(md5($value['receiver_user_type_id']));
                  $owner = $this->model_applicant_details->applicantdetails($value['apply_connection_id']);
                    $j=0;
                   foreach($owner as $keyy => $val)
                   {
                        //$ow[$key][$keyy]['owner']= $val["owner_name"];
                       if($j==0)
                       {
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
                   $data['posts'][$key]['user_type'] = $user_type_nm['user_type'];

            }
            
            return view('water/water_connection/water_forward_list', $data);
        }
       // print_r($data['posts']);
	}

    public function forward_list2()
	{
        $data =(array)null;
        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
        $login_emp_details_id = $emp_mstr["id"];
        $data['user_type']=$emp_mstr['user_type_mstr_id'];
        $receiver_user_type_id=$emp_mstr['user_type_mstr_id'];

        $wardList = $this->model_view_ward_permission->getPermittedWard($login_emp_details_id);
        $data['wardList'] = $wardList;

        $sender_user_type_id = $emp_mstr["user_type_mstr_id"];
        $ward="";
        if($data['user_type']==12)
        {
            $send_to = 13;// D.A TO J.E
        }
        elseif($data['user_type']==13)
        {
            $send_to = 14;// J.E TO S.H
        }
        elseif($data['user_type']==14)
        {
            $send_to = 15;// S.H TO A.E
        }
        elseif($data['user_type']==15)
        {
            $send_to = 16;// A.E TO E.O
        }
        elseif($data['user_type']==16)
        {
            $send_to = 16;//  E.O to Final Approved
        }
        
        $user_type_nm = $this->model_user_type_mstr->getdatabyid(MD5($send_to));
        $data['user_type_nm'] = $user_type_nm['user_type'];

        $i=0;
        foreach($wardList as $key => $value)
        {
            if($i==0)
            {
                $ward=array($value['ward_mstr_id']);
            }
            else
            {
                array_push($ward, $value['ward_mstr_id']);
            }
            $i++;
        }       

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
                $post_wher = " and application_no ilike('%".$data['keyword']."%')";
            }
            elseif($data['by_holding_owner_dtl']=='by_owner' && trim($data['keyword'])!='')
            {
                $post_wher = " and (applicant_name ilike('%".$data['keyword']."%')
                                OR father_name ilike('%".$data['keyword']."%')
                                OR mobile_no ilike('%".$data['keyword']."%')                                
                            )"; 
            }
            elseif($data['by_holding_owner_dtl']=='by_forward_date')
            {
                $post_wher= " and created_on ::date between '".$data['from_date']."' and '".$data['to_date']."' ";
            }

        }        
        
        $select = " SELECT view_water_level_pending.* , 
                    case when view_water_level_pending.receiver_user_type_id=0 then 'Back Citizen'
                    else view_user_type_mstr.user_type end as user_type ";
        $from = " FROM view_water_level_pending 
                  left join view_user_type_mstr on view_user_type_mstr.id = view_water_level_pending.receiver_user_type_id";
         $where = '';
        if($data['user_type']!=16)
        {
            $where = " WHERE sender_user_type_id = $sender_user_type_id AND verification_status = 0 
                        AND view_water_level_pending.status = 1 AND ward_id IN ($ward_ids_string) $post_wher ";

        }
        elseif($data['user_type']==16)
        {
            $where = " WHERE receiver_user_type_id = $sender_user_type_id AND verification_status = 1 
                        AND view_water_level_pending.status = 1 AND ward_id IN ($ward_ids_string) $post_wher ";

        }
        $order_by=" ORDER BY created_on DESC ,id desc ";
        $sql = $select.$from . $where . $order_by;
        $result = $this->model_datatable->getDatatable($sql);

        $data['posts'] = $result['result']??null;
        $data['count']= $result['count']??0;
        $data['offset']= $result['offset']??0;
        // print_var($data['count']);

        return view('water/water_connection/water_forward_list2', $data);

       
	}




    public function forward_view($id)
	{
        $data =(array)null;
        $Session = Session();
        
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
        $data['user_type']=$emp_mstr['user_type_mstr_id'];
        $login_emp_details_id = $emp_mstr["id"];
        $sender_user_type_id = $emp_mstr["user_type_mstr_id"];
        $data['form'] = $this->model_view_water_level_pending->waterlevelpendingdetailbyid($id);
        $data['basic_details'] = $this->model_view_water_connection->getDatabyid(md5($data['form']['apply_connection_id']));
        
        
        $data['ward']  = $this->model_ward_mstr->getdatabyid($data['form']['ward_id']);
        $data['form']['ward_no']=$data['ward']['ward_no'];
        $data['owner_details'] = $this->model_applicant_details->applicantdetails($data['form']['apply_connection_id']);

        $verify_status='1';
        foreach($data['owner_details'] as $key => $value){
                $app__doc='consumer_photo';
                $data['owner_details'][$key]['saf_owner_img_list'] = $this->model_applicant_doc->get_verifiedownerimgdetails_by_safid($data['basic_details']['id'],$value['id'],$app__doc);

                $app_doc_type="ID Proof";
                $data['owner_details'][$key]['saf_owner_doc_list'] = $this->model_view_applicant_doc->get_verifiedownerdocdetails_by_conid($data['basic_details']['id'],$value['id'],$app_doc_type);

            }
        //print_r($data['owner_details']);
        $apply_connection_id=$data['basic_details']['id'];

        $payment_doc="payment_receipt";
        $data['payment_receipt_doc']=$this->model_applicant_doc->getverifieddocdet_by_conid($apply_connection_id,$payment_doc);
        $add_doc="address_proof";
        $data['address_proof_doc']=$this->model_view_applicant_doc->get_verifiedaddressdocdetails_by_conid($apply_connection_id,$add_doc);
        $connection_doc="connection_form";
        $data['connection_doc']=$this->model_applicant_doc->getverifieddocdet_by_conid($apply_connection_id,$connection_doc);
        $electricity_doc="electricity_bill";
        $data['electricity_doc']=$this->model_applicant_doc->getverifieddocdet_by_conid($apply_connection_id,$electricity_doc);
        $meter_bill_doc="meter_bill";
        $data['meter_bill_doc']=$this->model_applicant_doc->getverifieddocdet_by_conid($apply_connection_id,$meter_bill_doc);
        $bpl_doc="bpl";
        $data['bpl_doc']=$this->model_applicant_doc->getverifieddocdet_by_conid($apply_connection_id,$bpl_doc);

        $data['remark'] = $this->model_water_level_pending_dtl->forward_remarks_by_con_id($apply_connection_id,$sender_user_type_id);
        $data['basic_details']['remarks'] = $data['remark']['remarks'];

        return view('water/water_connection/water_forward_view', $data);
    }

}
