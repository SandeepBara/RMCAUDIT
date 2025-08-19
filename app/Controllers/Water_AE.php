<?php namespace App\Controllers;

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
use App\Models\WaterSiteInspectionModel;
use App\Models\Water_Transaction_Model;
use App\Models\water_level_pending_model;
use App\Models\model_water_sms_log;

class Water_AE extends AlphaController
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
    protected $model_water_sms_log;

    public function __construct(){
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
        $this->site_ins_model=new WaterSiteInspectionModel($this->db);
        $this->Water_Transaction_Model=new Water_Transaction_Model($this->db);
        $this->water_level_pending_model=new water_level_pending_model($this->db);
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
        $data['user_type']=$emp_mstr['user_type_mstr_id'];
        //Transaction Mode List
        $wardList = $this->model_view_ward_permission->getPermittedWard($login_emp_details_id);
        $data['wardList'] = $wardList;
        //print($wardList);
        $receiver_user_type_id = $emp_mstr["user_type_mstr_id"];
        $ward="";

        $i=0;
        foreach($wardList as $key => $value)
        {
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
            return view('water/water_connection/water_ae_list', $data);
        }
        else
        {
            $data['from_date'] = date('Y-m-d');
            $data['to_date'] = date('Y-m-d');
            $data['posts'] = $this->model_view_water_level_pending->waterjereceiveList($receiver_user_type_id,$data['from_date'],$data['to_date'],$ward);
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
            return view('water/water_connection/water_ae_list', $data);
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
        date_default_timezone_set('Asia/Kolkata');
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");

        $login_emp_details_id = $emp_mstr["id"];
        $sender_user_type_id = $emp_mstr["user_type_mstr_id"];

        $data['user_type']=$emp_mstr['user_type_mstr_id'];
        $data['form'] = $this->model_view_water_level_pending->waterlevelpendingdetailbyid($id);
        $data['water_conn_id']=!empty($data['form'])?$data['form']['apply_connection_id']:null;
        $data['basic_details'] = $this->model_view_water_connection->getDatabyid(md5($data['form']['apply_connection_id']));

         $data['check_exists_verification_by_ae']=$this->site_ins_model->check_exists_verification_by_ae($data['basic_details']['id']);
            
         
        $data['ward']  = $this->model_ward_mstr->getdatabyid($data['form']['ward_id']);
        //$data['form']['ward_no']=$ward['ward_no'];
        $data['owner_details'] = $this->model_applicant_details->applicantdetails($data['form']['apply_connection_id']);
        $data['transaction_details']=$this->Water_Transaction_Model->get_all_transactions((md5($data['form']['apply_connection_id'])));
        

        $verify_status='1';
        foreach($data['owner_details'] as $key => $value)
        {
            $app__doc='CONSUMER_PHOTO';
            $data['owner_details'][$key]['saf_owner_img_list'] = $this->model_applicant_doc->get_verifiedownerimgdetails_by_safid($data['basic_details']['id'],$value['id'],$app__doc);

            $app_doc_type="ID Proof";
            $data['owner_details'][$key]['saf_owner_doc_list'] = $this->model_view_applicant_doc->get_verifiedownerdocdetails_by_conid($data['basic_details']['id'],$value['id'],$app_doc_type);

        }

        $apply_connection_id=$data['basic_details']['id'];

        $payment_doc="HOLDING PROOF";
        $data['payment_receipt_doc']=$this->model_applicant_doc->getverifieddocdet_by_conid($apply_connection_id,$payment_doc);
        $add_doc="Address Proof";
        $data['address_proof_doc']=$this->model_view_applicant_doc->get_verifiedaddressdocdetails_by_conid($apply_connection_id,$add_doc);
        $connection_doc="Form(Scan Copy)";
        $data['connection_doc']=$this->model_applicant_doc->getverifieddocdet_by_conid($apply_connection_id,$connection_doc);
        $electricity_doc="ELECTRICITY_NEW";
        $data['electricity_doc']=$this->model_applicant_doc->getverifieddocdet_by_conid($apply_connection_id,$electricity_doc);
        $meter_bill_doc="METER BILL";
        $data['meter_bill_doc']=$this->model_applicant_doc->getverifieddocdet_by_conid($apply_connection_id,$meter_bill_doc);
        $bpl_doc="BPL";
        $data['bpl_doc']=$this->model_applicant_doc->getverifieddocdet_by_conid($apply_connection_id,$bpl_doc);

        $data['remark'] = $this->model_water_level_pending_dtl->approved_dl_remarks_by_con_id($apply_connection_id);
        $data['basic_details']['remarks'] = $data['remark']['remarks']??'N/A';

        $data['jeremark'] = $this->model_water_level_pending_dtl->approved_je_remarks_by_con_id($apply_connection_id);
        $data['basic_details']['jeremarks'] = $data['jeremark']['remarks']??'N/A';

        $data['shremark'] = $this->model_water_level_pending_dtl->approved_sh_remarks_by_con_id($apply_connection_id);
        $data['basic_details']['shremarks'] = $data['shremark']['remarks']??'N/A';
        $data['getremarks']=$this->water_level_pending_model->getremarks(md5($apply_connection_id));

        //----------------------sms data -----------------------
		 $appliction = $data['basic_details'];
		 $owners = $data['owner_details'];

        if($this->request->getMethod()=='post')
        {
            if(isset($_POST['btn_verify_submit']))
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
                         'receiver_user_type_id'=>16,
                        'verification_status'=>1,
                        'receiver_user_id' =>$login_emp_details_id,
                    ];
                if($updateverify = $this->model_water_level_pending_dtl->updatelevelpendingById($data))
                {
                    $level_where = ['md5(id::text)'=>$id];
                    //$this->model_water_level_pending_dtl->level_pending_updt($data,$level_where);
                    if($insertverify = $this->model_water_level_pending_dtl->insrtlevelpendingdtl($data))
                    {
                        return $this->response->redirect(base_url('Water_AE/index/'));
                    }
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
                         'receiver_user_type_id'=>14,
                        'verification_status'=>3,
                        'receiver_user_id' =>$login_emp_details_id,
                    ];
                if($updatebackward = $this->model_water_level_pending_dtl->updatelevelpendingById($data)){
                    if($insertbackward = $this->model_water_level_pending_dtl->insrtlevelpendingdtl($data)){
                        return $this->response->redirect(base_url('Water_AE/index/'));
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
                $this->WaterApplyNewConnectionModel->updateLevelPendingStatus(['apply_connection_id'=> $data['form']['apply_connection_id'], 'level_pending_status'=> 2]);
                
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
                flashToast("message", "Application sent back to citizen");
                return $this->response->redirect(base_url('Water_AE/index'));
            }


        }
        else
        {   //echo"<pre>";print_r($data);echo"</pre>";
             return view('water/water_connection/water_ae_view', $data);
        }


    }
    }
