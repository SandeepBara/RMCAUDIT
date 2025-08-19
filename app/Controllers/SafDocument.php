<?php 
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_transfer_mode_mstr;
use App\Models\model_prop_type_mstr;
use App\Models\model_doc_mstr;
use App\Models\model_saf_owner_detail;
use App\Models\model_saf_doc_dtl;
use App\Models\model_saf_dtl;
use App\Models\model_level_pending_dtl;
use App\Models\model_view_saf_doc_dtl;
use App\Models\model_ulb_mstr;
use App\Models\model_transaction;

class SafDocument extends AlphaController
{
    protected $db;
    protected $dbSystem;
    protected $model_transfer_mode_mstr;
    protected $model_prop_type_mstr;
    protected $model_doc_mstr;
    protected $model_saf_owner_detail;
    protected $model_saf_doc_dtl;
    protected $model_saf_dtl;
    protected $model_level_pending_dtl;
    protected $model_view_saf_doc_dtl;
    protected $model_ulb_mstr;
    protected $model_transaction;

    public function __construct(){
        parent::__construct();
    	helper(['db_helper', 'upload_helper']);
        if($db_name = dbConfig("property")){
            $this->db = db_connect($db_name);            
        }
        if($db_system = dbSystem()){
            $this->dbSystem = db_connect($db_system); 
        }       
        $this->model_ulb_mstr = new model_ulb_mstr($this->dbSystem);
        $this->model_transfer_mode_mstr = new model_transfer_mode_mstr($this->db);
        $this->model_prop_type_mstr = new model_prop_type_mstr($this->db);
        $this->model_doc_mstr = new model_doc_mstr($this->db);
        $this->model_saf_owner_detail = new model_saf_owner_detail($this->db);
        $this->model_saf_doc_dtl = new model_saf_doc_dtl($this->db);
        $this->model_saf_dtl = new model_saf_dtl($this->db);
        $this->model_level_pending_dtl = new model_level_pending_dtl($this->db);
        $this->model_view_saf_doc_dtl = new model_view_saf_doc_dtl($this->db);
        $this->model_transaction = new model_transaction($this->db);

    }
    public function index($id=null) {

        $session = session();
        $ulb_mstr = $session->get("ulb_dtl");
        $ulb_mstr_id = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $session->get("emp_details");
        $login_emp_details_id = $emp_mstr["id"];
        date_default_timezone_set('Asia/Kolkata');
        $data = (array)null;
        $data['ulb_dtl'] = $this->model_ulb_mstr->getulb_list($ulb_mstr_id);
        $ulb_city_nm = $data['ulb_dtl']['city'];
        $data['saf_dtl'] = $this->model_saf_dtl->Saf_details_md5($id);
        //print_r($data['saf_dtl']);
        $saf_id = $data['saf_dtl']['id'];
        $payment_status = $data['saf_dtl']['payment_status'];
        $data['owner_list'] = $this->model_saf_owner_detail->ownerdetails_md5($id);
        foreach($data['owner_list'] as $key => $value){
            $saf_owner_id=$this->model_saf_doc_dtl->check_owner_details($saf_id, $value['id']);
            $data['owner_list'][$key]['saf_owner_dtl_id'] = $saf_owner_id['saf_owner_dtl_id'];
        }
        //Other Document code
        $data['other_doc'] = $this->model_doc_mstr->getdatabyother();
        //Transfer Mode code
        $transfer_mode['doc_type'] = "transfer_mode";
        $data['transfer_mode'] = $this->model_doc_mstr->getdatabydoc_type($transfer_mode);
        //Property Type code
        $property_type['doc_type'] = "property_type";
        $data['property_type'] = $this->model_doc_mstr->getdatabydoc_type($property_type);
        $data['no_electric_connection_doc_list'] = $this->model_doc_mstr->getDataByNoElectConnection();
        $data['flat_doc_list'] = $this->model_doc_mstr->getFlatDocListData();

        if ($this->request->getMethod()=='post') {
            //SAF Form doc
            if (isset($_POST['btn_fr_doc'])) {
                $data = [
                    'saf_form_doc_mstr_id' => $this->request->getVar('saf_form_doc_mstr_id'),
                    'saf_dtl_id' => $saf_id,
                    'emp_details_id' => $login_emp_details_id,
                    'created_on' =>date('Y-m-d H:i:s')
                ];
                $rules=[
                        'saf_form_doc_path'=>'uploaded[saf_form_doc_path]|max_size[saf_form_doc_path,5120]|ext_in[saf_form_doc_path,png,jpg,jpeg,pdf]',
                ];
                if ($this->validate($rules)) { 
                    if ($insert_last_idd = $this->model_saf_doc_dtl->insertFrData($data)) {
                        $saf_form_doc_file=$this->request->getFile('saf_form_doc_path');
                        if ($saf_form_doc_file->IsValid() && !$saf_form_doc_file->hasMoved()) {
                            $newFileName = md5($insert_last_idd);
                            $file_ext = $saf_form_doc_file->getExtension();
                            $path = $ulb_city_nm."/"."saf_doc_dtl";

                            if ($saf_form_doc_file->move(WRITEPATH.'uploads/'.$path.'/',$newFileName.'.'.$file_ext)) {
                                $saf_form_doc_path = $path."/".$newFileName.'.'.$file_ext;
                                if ($updaterow = $this->model_saf_doc_dtl->updatetransdocpathById($insert_last_idd,$saf_form_doc_path)) {
                                    return $this->response->redirect(base_url('safdocument/index/'.$id.''));
                                }
                            }
                        }
                    }
                } else {
                    $data['err_msg']='Error Occurs!!';
                    return view('property/Saf/saf_document_upload', $data);
                }
            }
            //Transfer mode doc
            if (isset($_POST['btn_tr_doc'])) {
                $data = [
                    'trans_doc_mstr_id' => $this->request->getVar('trans_doc_mstr_id'),
                    'saf_dtl_id' => $saf_id,
                    'emp_details_id' => $login_emp_details_id,
                    'created_on' =>date('Y-m-d H:i:s')
                ];
                $rules=[
                    'trans_doc_path'=>'uploaded[trans_doc_path]|max_size[trans_doc_path,5120]|ext_in[trans_doc_path,png,jpg,jpeg,pdf]',
                ];
                if ($this->validate($rules)) {                
                    if ($insert_last_id = $this->model_saf_doc_dtl->insertData($data)) {
                        $file=$this->request->getFile('trans_doc_path');
                        if($file->IsValid() && !$file->hasMoved()){
                            $newFileName = md5($insert_last_id);
                            //$file_ext = pathinfo($_FILES["trans_doc_path"]["name"],PATHINFO_EXTENSION);
                            $file_ext = $file->getExtension();
                            $path = $ulb_city_nm."/"."saf_doc_dtl";

                            if($file->move(WRITEPATH.'uploads/'.$path.'/',$newFileName.'.'.$file_ext)){

                                $trans_doc_path = $path."/".$newFileName.'.'.$file_ext;
                                if($updaterow = $this->model_saf_doc_dtl->updatetransdocpathById($insert_last_id,$trans_doc_path))
                                {
                                    return $this->response->redirect(base_url('safdocument/index/'.$id.''));
                                }
                            }
                        }
                    }
                } else {
                    $data['err_msg']='Error Occurs!!';
                    return view('property/Saf/saf_document_upload', $data);
                }
            }
            //Property type doc
            if (isset($_POST['btn_pr_doc'])) {
                $data = [
                    'prop_doc_mstr_id' => $this->request->getVar('prop_doc_mstr_id'),
                    'saf_dtl_id' => $saf_id,
                    'emp_details_id' => $login_emp_details_id,
                    'created_on' =>date('Y-m-d H:i:s')
                ];
                $rules=[
                    'prop_doc_path'=>'uploaded[prop_doc_path]|max_size[prop_doc_path,5120]|ext_in[prop_doc_path,png,jpg,jpeg,pdf]',
                ];

                if ($this->validate($rules)) {
                    if ($pr_insert_last_id = $this->model_saf_doc_dtl->insertPrData($data)) {
                        $prop_doc_file=$this->request->getFile('prop_doc_path');
                        if($prop_doc_file->IsValid() && !$prop_doc_file->hasMoved()){
                            $newFileName = md5($pr_insert_last_id);
                            $file_ext = $prop_doc_file->getExtension();
                            $path = $ulb_city_nm."/"."saf_doc_dtl";

                            if($prop_doc_file->move(WRITEPATH.'uploads/'.$path.'/',$newFileName.'.'.$file_ext)){

                                $prop_doc_path = $path."/".$newFileName.'.'.$file_ext;
                                if($updaterow = $this->model_saf_doc_dtl->updatetransdocpathById($pr_insert_last_id,$prop_doc_path))
                                {
                                    return $this->response->redirect(base_url('safdocument/index/'.$id.''));
                                }
                            }
                        }
                    }
                } else {
                    $data['err_msg']='Error Occurs!!';
                    return view('property/Saf/saf_document_upload', $data);
                }
            }





            //no electric connection mode doc
            if (isset($_POST['btn_no_electric_connection_upload'])) {
                $this->request->getVar('no_electric_connection_doc_id');
                $data = [
                    'doc_mstr_id' => $this->request->getVar('no_electric_connection_doc_id'),
                    'saf_dtl_id' => $saf_id,
                    'emp_details_id' => $login_emp_details_id,
                    'created_on' =>date('Y-m-d H:i:s')
                ];
                $rules=[
                    'no_electric_connection_doc_path'=>'uploaded[no_electric_connection_doc_path]|max_size[no_electric_connection_doc_path,5120]|ext_in[no_electric_connection_doc_path,png,jpg,jpeg,pdf]',
                ];
                if ($this->validate($rules)) {               
                    if ($insert_last_id = $this->model_saf_doc_dtl->insertNoElectricConnectionData($data)) {
                        $file=$this->request->getFile('no_electric_connection_doc_path');
                        if($file->IsValid() && !$file->hasMoved()){
                            $newFileName = md5($insert_last_id);
                            //$file_ext = pathinfo($_FILES["trans_doc_path"]["name"],PATHINFO_EXTENSION);
                            $file_ext = $file->getExtension();
                            $path = $ulb_city_nm."/"."saf_doc_dtl";

                            if($file->move(WRITEPATH.'uploads/'.$path.'/',$newFileName.'.'.$file_ext)){

                                $no_electric_connection_doc_path = $path."/".$newFileName.'.'.$file_ext;
                                if($updaterow = $this->model_saf_doc_dtl->updatetransdocpathById($insert_last_id,$no_electric_connection_doc_path))
                                {
                                    return $this->response->redirect(base_url('safdocument/index/'.$id.''));
                                }
                            }
                        }
                    }
                } else {
                    $data['err_msg']='Error Occurs!!';
                    return view('property/Saf/saf_document_upload', $data);
                }
            }
            //flat mode doc
            if (isset($_POST['btn_flat_upload'])) {
                $this->request->getVar('flat_doc_id');
                $data = [
                    'doc_mstr_id' => $this->request->getVar('flat_doc_id'),
                    'saf_dtl_id' => $saf_id,
                    'emp_details_id' => $login_emp_details_id,
                    'created_on' =>date('Y-m-d H:i:s')
                ];
                $rules = [
                    'flat_doc_path'=>'uploaded[flat_doc_path]|max_size[flat_doc_path,5120]|ext_in[flat_doc_path,png,jpg,jpeg,pdf]',
                ];
                if ($this->validate($rules)) {
                    if ($insert_last_id = $this->model_saf_doc_dtl->insertNoElectricConnectionData($data)) {
                        $file=$this->request->getFile('flat_doc_path');
                        if($file->IsValid() && !$file->hasMoved()){
                            $newFileName = md5($insert_last_id);
                            //$file_ext = pathinfo($_FILES["trans_doc_path"]["name"],PATHINFO_EXTENSION);
                            $file_ext = $file->getExtension();
                            $path = $ulb_city_nm."/"."saf_doc_dtl";

                            if($file->move(WRITEPATH.'uploads/'.$path.'/',$newFileName.'.'.$file_ext)){

                                $flat_doc_path = $path."/".$newFileName.'.'.$file_ext;
                                if($updaterow = $this->model_saf_doc_dtl->updatetransdocpathById($insert_last_id,$flat_doc_path))
                                {
                                    return $this->response->redirect(base_url('safdocument/index/'.$id.''));
                                }
                            }
                        }
                    }
                } else {
                    $data['err_msg']='Error Occurs!!';
                    return view('property/Saf/saf_document_upload', $data);
                }
            }




            //Other doc
            if(isset($_POST['btn_owner_doc'])) {
                $data = [
                    'saf_owner_dtl_id' => $this->request->getVar('saf_owner_dtl_id'),
                    'owner_doc_mstr_id' => $this->request->getVar('owner_doc_mstr_id'),
                    'saf_dtl_id' => $saf_id,
                    'emp_details_id' => $login_emp_details_id,
                    'created_on' =>date('Y-m-d H:i:s')
                ];
                $rules=[
                    'applicant_image_path'=>'uploaded[applicant_image_path]|max_size[applicant_image_path,5120]|ext_in[applicant_image_path,png,jpg,jpeg]',
                    'owner_doc_path'=>'uploaded[owner_doc_path]|max_size[owner_doc_path,5120]|ext_in[owner_doc_path,png,jpg,jpeg,pdf]',
                ];
                /********applicant image upload starts***************/
                $applicant_image_file=$this->request->getFile('applicant_image_path');
                $owner_doc_file=$this->request->getFile('owner_doc_path');
                if($this->validate($rules)){ 
                    if(isset($applicant_image_file)) {
                        if($ownerimginsert_last_idd = $this->model_saf_doc_dtl->insertOwnerImgData($data)){
                            if($applicant_image_file->IsValid() && !$applicant_image_file->hasMoved()){
                                $newFileName = md5($ownerimginsert_last_idd);
                                $file_ext = $applicant_image_file->getExtension();

                                $path = $ulb_city_nm."/"."applicant_image";

                                if($applicant_image_file->move(WRITEPATH.'uploads/'.$path.'/',$newFileName.'.'.$file_ext)){
                                    
                                    $owner_img_path = $path."/".$newFileName.'.'.$file_ext;
                                    if($updaterowimg = $this->model_saf_doc_dtl->updatetransdocpathById($ownerimginsert_last_idd,$owner_img_path)) {
                                        if(isset($owner_doc_file)) {
                                            if($ownerinsert_last_idd = $this->model_saf_doc_dtl->insertOwnerData($data)){
                                                if($owner_doc_file->IsValid() && !$owner_doc_file->hasMoved()){
                                                    $newFileNamee = md5($ownerinsert_last_idd);
                                                    $file_extt = $owner_doc_file->getExtension();
                                                    $path = $ulb_city_nm."/"."saf_doc_dtl";

                                                    if($owner_doc_file->move(WRITEPATH.'uploads/'.$path.'/',$newFileNamee.'.'.$file_extt)){

                                                        $owner_doc_path = $path."/".$newFileNamee.'.'.$file_extt;
                                                        if($updaterow = $this->model_saf_doc_dtl->updatetransdocpathById($ownerinsert_last_idd,$owner_doc_path))
                                                        {
                                                            return $this->response->redirect(base_url('safdocument/index/'.$id.''));
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                } else {
                    $data['err_msg']='Error Occurs!!';
                    return view('property/Saf/saf_document_upload', $data);
                }
                /**********applicant image upload ends***********/
            }
        } else {
            //owner_id
            $data['owner_document_exists'] = $this->model_saf_owner_detail->ownerdetails_md5($id);
            $iw=0;
            foreach ($data['owner_document_exists'] as $key => $value) {
                $saf_owner_id=$this->model_saf_doc_dtl->check_owner_details($saf_id,$value['id']);
                $data['owner_document_exists'][$key]['saf_owner_dtl_id'] = $saf_owner_id['saf_owner_dtl_id'];
                //echo $data['owner_document_exists'][$key]['saf_owner_dtl_id'];
                if ($data['owner_document_exists'][$key]['saf_owner_dtl_id']!='') {
                    $iw++;
                }
            }
            $count_owner_doc=count($data['owner_document_exists']);
            if($count_owner_doc==$iw) {
                $data['owner_doc_upload_stts']='true';
            }

            $fr_doc="saf_form";
            $data['owner_fr_document_exists']=$this->model_saf_doc_dtl->check_fr_doc($saf_id, $fr_doc);
            //print_r($data['owner_fr_document_exists']);

            $i=0;
            foreach($data['transfer_mode'] as $key => $value) {
                if($i==0) {
                    $tr_doc=array($value['id']);
                } else {
                    array_push($tr_doc, $value['id']);
                }
                $i++;
            }

            $data['owner_tr_document_exists']=$this->model_saf_doc_dtl->check_tr_doc($saf_id,$tr_doc);

            $ik=0;
            foreach ($data['property_type'] as $key => $value) {
                if ($ik==0) {
                    $pr_doc=array($value['id']);
                } else {
                    array_push($pr_doc, $value['id']);
                }
                $ik++;
            }

            $data['owner_pr_document_exists']=$this->model_saf_doc_dtl->check_pr_doc($saf_id,$pr_doc);
            $leveldata = [
                'saf_dtl_id' => $saf_id,
                'sender_user_type_id' => 0,
                'receiver_user_type_id' => 6,
                'forward_date' => date('Y-m-d'),
                'forward_time' => date('H:i:s'),
                'created_on' =>date('Y-m-d H:i:s'),
                'remarks' => '',
                'verification_status' => 0
            ];

            $i=0;
            foreach($data['no_electric_connection_doc_list'] as $key => $value) {
                if($i==0) {
                    $no_elect_connection_doc=array($value['id']);
                } else {
                    array_push($no_elect_connection_doc, $value['id']);
                }
                $i++;
            }
            $data['no_elect_connection_exists'] = false;
            if ($data['saf_dtl']['no_electric_connection']=='f') {
                $data['no_elect_connection_exists'] = true;
            }else if ($this->model_saf_doc_dtl->check_no_elect_connection_exists($saf_id, $no_elect_connection_doc)) {
                $data['no_elect_connection_exists'] = true;
            }

            
            $i=0;
            foreach($data['flat_doc_list'] as $key => $value) {
                if($i==0) {
                    $flat_doc=array($value['id']);
                } else {
                    array_push($flat_doc, $value['id']);
                }
                $i++;
            }
            $data['flat_exists'] = false;
            if ($data['saf_dtl']['prop_type_mstr_id']!=3) {
                $data['flat_exists'] = true;
            }else if ($this->model_saf_doc_dtl->check_flat_exists($saf_id, $flat_doc)) {
                $data['flat_exists'] = true;
            }
            
            if ($data['owner_doc_upload_stts'] && $data['owner_tr_document_exists'] && $data['owner_pr_document_exists'] && $data['owner_fr_document_exists'] && $data['no_elect_connection_exists'] && $data['flat_exists']) {
                if($data['saf_dtl']['doc_upload_status']=='0') {
                    $data['doc_upload_stts']=$this->model_saf_dtl->update_doc_upload_status($leveldata);
                    if($data['saf_dtl']['payment_status']=='1') {
                        $level_pending_insrt=$this->model_level_pending_dtl->insrtlevelpendingdtl($leveldata);
                        return $this->response->redirect(base_url('safdocument/view/'.$id.''));
                    } else {
                        return $this->response->redirect(base_url('safdocument/view/'.$id.''));
                    }
                }
            }
            return view('property/Saf/saf_document_upload', $data);
        }
    }

    public function view($id=null) {
        $data =(array)null;
        $data['id']=$id;
        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");

        $login_emp_details_id = $emp_mstr["id"];
        $sender_user_type_id = $emp_mstr["user_type_mstr_id"];
        $data['basic_details'] = $this->model_saf_dtl->basic_details($data);
        $data['form'] = $this->model_saf_dtl->Saf_details_md5($id);

        $data['owner_list'] = $this->model_saf_owner_detail->ownerdetails($data['form']['id']);
        $verify_status='0';
        foreach($data['owner_list'] as $key => $value){
            $app_other_doc='applicant_image';
            $data['owner_list'][$key]['saf_owner_img_list'] = $this->model_saf_doc_dtl->get_ownerimgdetails_by_safid($data['form']['id'],$value['id'],$app_other_doc);
            $app_doc_type="other";
            $data['owner_list'][$key]['saf_owner_doc_list'] = $this->model_view_saf_doc_dtl->safownerdocdetbyid($data['form']['id'],$value['id'],$app_doc_type);
        }
        $fr_doc_type = "saf_form";
        $data['owner_saf_form'] = $this->model_saf_doc_dtl->get_ownersafform_by_safid($data['form']['id'],$fr_doc_type);
        //print_r($data['owner_saf_form']);
        $tr_doc_type="transfer_mode";
        $data['prop_tr_mode_document'] = $this->model_view_saf_doc_dtl->uploaded_doc_list_by_safid($data['form']['id'],$tr_doc_type);
        $pr_doc_type="property_type";
        $data['prop_pr_mode_document'] = $this->model_view_saf_doc_dtl->uploaded_doc_list_by_safid($data['form']['id'],$pr_doc_type);
        
        if ($data['form']['no_electric_connection']=="t") {
            echo "asdasd";
            $pr_doc_type="no_elect_connection";
            $data['no_electric_connection_mode_document'] = $this->model_view_saf_doc_dtl->uploaded_doc_list_by_safid($data['form']['id'],$pr_doc_type);
        }
        if ($data['form']['prop_type_mstr_id']==3) {
            $pr_doc_type="flat_doc";
            $data['flat_mode_document'] = $this->model_view_saf_doc_dtl->uploaded_doc_list_by_safid($data['form']['id'],$pr_doc_type);
            //print_r($data['flat_mode_document']);
        }
        $data['dl_remarks'] = $this->model_level_pending_dtl->dl_remarks_by_saf_id($data['form']['saf_dtl_id']);
		
		$data['msg'] = $this->model_saf_dtl->msg($data);
		if($data['msg']['payment_status']==1 AND $data['msg']['doc_upload_status']==1){
			if($data['msg']['saf_pending_status']==0){
				$data['msglevelPending'] = $this->model_level_pending_dtl->msglevelPending($data);
				if($data['msglevelPending']['receiver_user_type_id']==6){
					$data['SAFLevelPending'] = "Pending At Dealing Assistant";
				}elseif($data['msglevelPending']['receiver_user_type_id']==5){
					$data['SAFLevelPending'] = "Pending At Tax Collector";
				}elseif($data['msglevelPending']['receiver_user_type_id']==7){
					$data['SAFLevelPending'] = "Pending At ULB Tax Collector";
				}elseif($data['msglevelPending']['receiver_user_type_id']==9){
					$data['SAFLevelPending'] = "Pending At Section Incharge";
				}elseif($data['msglevelPending']['receiver_user_type_id']==10){
					$data['SAFLevelPending'] = "Pending At Executive Officer";
				}
			}elseif($data['msg']['saf_pending_status']==1){ $data['SAFLevelPending'] = "Form Fully Approved"; }
			elseif($data['msg']['saf_pending_status']==2){ $data['SAFLevelPending'] = "Back To Citizen"; }
		}elseif($data['msg']['payment_status']==1 AND $data['msg']['doc_upload_status']==0){ 
			$data['SAFLevelPending'] = "Payment Done But Document Upload Is Pending"; 
		}elseif($data['msg']['payment_status']==0 AND $data['msg']['doc_upload_status']==1){ 
			$data['SAFLevelPending'] = "Document Upload Done But Payment Is Pending"; 
		}elseif($data['msg']['payment_status']==0 AND $data['msg']['doc_upload_status']==0){ 
			$data['SAFLevelPending'] = "Payment Pending And Document Upload Pending"; 
		}

        /******* verification code ends**********/
        return view('property/saf/bo_doc_upload_saf_view', $data);
    }

}