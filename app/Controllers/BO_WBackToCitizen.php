<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\model_ulb_mstr;
use App\Models\model_view_ward_permission;
use App\Models\model_ward_mstr;
use App\Models\WaterApplyNewConnectionModel;
use App\Models\model_applicant_details;
use App\Models\model_applicant_doc;
use App\Models\model_view_applicant_doc;
use App\Models\model_document_mstr;
use App\Models\model_water_level_pending_dtl;


class BO_WBackToCitizen extends AlphaController
{
    protected $db;
    protected $dbSystem;
    public function __construct(){
        parent::__construct();
    	helper(['db_helper']);
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
        $this->WaterApplyNewConnectionModel = new WaterApplyNewConnectionModel($this->db);
        $this->model_applicant_details = new model_applicant_details($this->db);
        $this->model_applicant_doc = new model_applicant_doc($this->db);
        $this->model_view_applicant_doc = new model_view_applicant_doc($this->db);
        $this->model_document_mstr = new model_document_mstr($this->db);
        $this->model_water_level_pending_dtl = new model_water_level_pending_dtl($this->db);
    }

    public function index()
	{
        $data =(array)null;
        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
        $login_emp_details_id = $emp_mstr["id"];
        //print_r($emp_mstr);
        $wardList = $this->model_view_ward_permission->getPermittedWard($login_emp_details_id);
        $data['wardList'] = $wardList;
        
        $receiver_emp_details_id = $emp_mstr["user_type_mstr_id"];
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
                $data['posts'] = $this->WaterApplyNewConnectionModel->wardwisebo_backtocitizen_list($data['from_date'],$data['to_date'],$data['ward_mstr_id']);
            }
            else
            {
                $data['posts'] = $this->WaterApplyNewConnectionModel->bo_backtocitizen_list($data['from_date'],$data['to_date'],$ward);
            }

        $j=0;
        foreach($data['posts'] as $key => $value)
        {
                   $wardd = $this->model_ward_mstr->getdatabyid($value['ward_id']);
                   $owner = $this->model_applicant_details->applicantdetails($value['id']);
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

               }
            return view('water/water_connection/bo_backtocitizen_list', $data);
            }
        else
            {
            $data['from_date'] = date('Y-m-d');
            $data['to_date'] = date('Y-m-d');
            $data['posts'] = $this->WaterApplyNewConnectionModel->bo_backtocitizen_list($data['from_date'],$data['to_date'],$ward);

        $j=0;
        foreach($data['posts'] as $key => $value){
                   $wardd = $this->model_ward_mstr->getdatabyid($value['ward_id']);
                   $owner = $this->model_applicant_details->applicantdetails($value['id']);
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
            return view('water/water_connection/bo_backtocitizen_list', $data);
            }


	}
    public function view($id=null)
	{
        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $ulb_mstr_id = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
        $login_emp_details_id = $emp_mstr["id"];
        date_default_timezone_set('Asia/Kolkata');
        $data =(array)null;
		$data['id']=$id;
        $data['ulb_dtl'] = $this->model_ulb_mstr->getulb_list($ulb_mstr_id);
        $ulb_city_nm=$data['ulb_dtl']['city'];
		//$data['form'] = $this->model_view_saf_receive_list->safreceivedetailbyid($id);

        $data['water_con_dtl'] = $this->WaterApplyNewConnectionModel->getData($id);

        //print_r($data['water_con_dtl']);

        $apply_connection_id=$data['water_con_dtl']['id'];
        $category=$data['water_con_dtl']['category'];
        $level_pending_status=$data['water_con_dtl']['level_pending_status'];
        $payment_status=$data['water_con_dtl']['payment_status'];
        $data['owner_list'] = $this->model_applicant_details->applicantdetails_md5($id);
        //$data['owner_list'] = $this->model_saf_owner_detail->ownerdetails_md5($id);
        $iw=0;
        $iwk=0;
        foreach($data['owner_list'] as $key => $value){
                $data['owner_list'][$key]['saf_owner_img_list']=$this->model_applicant_doc->verified_rej_owner_img_details($apply_connection_id,$value['id']);
                $data['owner_list'][$key]['saf_owner_doc_list']=$this->model_view_applicant_doc->verified_reject_owner_doc_dtl($apply_connection_id,$value['id']);
                /***img msg****/
                $saf_owner_img_id=$this->model_applicant_doc->check_ownerdtl_img_details($apply_connection_id,$value['id']);
                if($saf_owner_img_id['applicant_detail_id']!="")
                {
                   $data['owner_list'][$key]['img_stts']='1';
                   $data['owner_list'][$key]['applicant_img'] = $saf_owner_img_id['document_path'];
                }
                else{
                     $saf_ownerdet_img_id=$this->model_applicant_doc->check_newownerdtl_img_details($apply_connection_id,$value['id']);
                     if($saf_ownerdet_img_id['applicant_detail_id']!="")
                     {
                        $data['owner_list'][$key]['img_stts']='1';
                        $data['owner_list'][$key]['applicant_img'] = $saf_ownerdet_img_id['document_path'];
                     }
                }
            //print_r($data['owner_list']);
                /***doc msg****/
                $saf_owner_doc_id=$this->model_view_applicant_doc->check_ownerdtl_doc_details($apply_connection_id,$value['id']);
            //print_r($saf_owner_doc_id);
                if($saf_owner_doc_id['applicant_detail_id']!="")
                {
                   $data['owner_list'][$key]['doc_stts']='1';
                   $data['owner_list'][$key]['applicant_doc'] = $saf_owner_doc_id['document_path'];

                }
                else{
                     $saf_ownerdet_doc_id=$this->model_view_applicant_doc->check_ownerdtll_doc_details($apply_connection_id,$value['id']);
                    if($saf_ownerdet_doc_id['applicant_detail_id']!="")
                    {
                        $data['owner_list'][$key]['doc_stts']='1';
                        $data['owner_list'][$key]['applicant_doc'] = $saf_ownerdet_doc_id['document_path'];

                    }
                }
                if(($data['owner_list'][$key]['img_stts']=='1') && ($data['owner_list'][$key]['doc_stts']=='1'))
                {
                    $iwk++;
                }
            $iw++;
            }
       // echo $iwk;
            //echo $iw;
            if($iwk==$iw)
            {
                 $data['owner_doc_upload_stts']='true';
            }
        //payment receipt
        $payment_doc="payment_receipt";
        $data['payment_receipt_doc']=$this->model_applicant_doc->verified_rej_document_details($apply_connection_id,$payment_doc);
        $data['payment_receipt_doc_exists']=$this->model_applicant_doc->check_verified_doc_exist($apply_connection_id,$payment_doc);
        if($data['payment_receipt_doc_exists']['id']!="")
        {
            $data['pr_stts']='1';
            $data['pr_doc_name'] = $data['payment_receipt_doc_exists']['document_path'];
        }
        else{
             $data['payment_receipt_new_doc_exists']=$this->model_applicant_doc->check_new_doc_exist($apply_connection_id,$payment_doc);
            if($data['payment_receipt_new_doc_exists']['id']!="")
            {
                $data['pr_stts']='1';
                $data['pr_doc_name'] = $data['payment_receipt_new_doc_exists']['document_path'];
            }
        }

        //address proof
        $add_doc="address_proof";
        $address_proof_doc_for='Address Proof';
        //$photo_id_proof_doc_for='Photo ID Proof';
        $photo_id_proof_doc_for='ID Proof';
        
        $data['address_proof_document_list'] = $this->model_document_mstr->getDocumentList($address_proof_doc_for);
        $data['photo_id_proof_document_list'] = $this->model_document_mstr->getDocumentList($photo_id_proof_doc_for);
        $data['address_proof_doc']=$this->model_view_applicant_doc->verified_rej_tr_doc_details($apply_connection_id,$add_doc);
        $data['address_proof_doc_exists']=$this->model_applicant_doc->check_verified_doc_exist($apply_connection_id,$add_doc);
        if($data['address_proof_doc_exists']['id']!="")
        {
            $data['ap_stts']='1';
            $data['ap_doc_name'] = $data['address_proof_doc_exists']['document_path'];
        }
        else{
             $data['address_proof_new_doc_exists']=$this->model_applicant_doc->check_new_doc_exist($apply_connection_id,$add_doc);
            if($data['address_proof_new_doc_exists']['id']!="")
            {
                $data['ap_stts']='1';
                $data['ap_doc_name'] = $data['address_proof_new_doc_exists']['document_path'];
            }
        }
        //connection form
        $connection_doc="connection_form";
        $data['connection_doc']=$this->model_applicant_doc->verified_rej_document_details($apply_connection_id,$connection_doc);
        $data['connection_doc_exists']=$this->model_applicant_doc->check_verified_doc_exist($apply_connection_id,$connection_doc);
        if($data['connection_doc_exists']['id']!="")
        {
            $data['cf_stts']='1';
            $data['cf_doc_name'] = $data['connection_doc_exists']['document_path'];
        }
        else{
             $data['connection_new_doc_exists']=$this->model_applicant_doc->check_new_doc_exist($apply_connection_id,$connection_doc);
            if($data['connection_new_doc_exists']['id']!="")
            {
                $data['cf_stts']='1';
                $data['cf_doc_name'] = $data['connection_new_doc_exists']['document_path'];
            }
        }
        //electricity
        $electricity_doc="electricity_bill";
        $data['electricity_doc']=$this->model_applicant_doc->verified_rej_document_details($apply_connection_id,$electricity_doc);
        $data['electricity_doc_exists']=$this->model_applicant_doc->check_verified_doc_exist($apply_connection_id,$electricity_doc);
        if($data['electricity_doc_exists']['id']!="")
        {
            $data['ed_stts']='1';
            $data['ed_doc_name'] = $data['electricity_doc_exists']['document_path'];
        }
        else{
             $data['electricity_new_doc_exists']=$this->model_applicant_doc->check_new_doc_exist($apply_connection_id,$electricity_doc);
            if($data['electricity_new_doc_exists']['id']!="")
            {
                $data['ed_stts']='1';
                $data['ed_doc_name'] = $data['electricity_new_doc_exists']['document_path'];
            }
        }

        //meter bill
        $meter_bill_doc="meter_bill";
        $data['meter_bill_doc']=$this->model_applicant_doc->verified_rej_document_details($apply_connection_id,$meter_bill_doc);
        $data['meter_bill_doc_exists']=$this->model_applicant_doc->check_verified_doc_exist($apply_connection_id,$meter_bill_doc);
        if($data['meter_bill_doc_exists']['id']!="")
        {
            $data['mb_stts']='1';
            $data['mb_doc_name'] = $data['meter_bill_doc_exists']['document_path'];
        }
        else{
             $data['meter_bill_new_doc_exists']=$this->model_applicant_doc->check_new_doc_exist($apply_connection_id,$meter_bill_doc);
            if($data['meter_bill_new_doc_exists']['id']!="")
            {
                $data['mb_stts']='1';
                $data['mb_doc_name'] = $data['meter_bill_new_doc_exists']['document_path'];
            }
        }

        $bpl_doc="bpl";
        $data['bpl_doc']=$this->model_applicant_doc->verified_rej_document_details($apply_connection_id,$bpl_doc);
        //print_r($data['bpl_doc']);
        $data['bpl_doc_exists']=$this->model_applicant_doc->check_verified_doc_exist($apply_connection_id,$bpl_doc);
        if($data['bpl_doc_exists']['id']!="")
        {
            $data['bpl_stts']='1';
            $data['bpl_doc_name'] = $data['bpl_doc_exists']['document_path'];
        }
        else{
             $data['bpl_new_doc_exists']=$this->model_applicant_doc->check_new_doc_exist($apply_connection_id,$bpl_doc);
            if($data['bpl_new_doc_exists']['id']!="")
            {
                $data['bpl_stts']='1';
                $data['bpl_doc_name'] = $data['bpl_new_doc_exists']['document_path'];
            }
        }

                ////////
                $leveldata = [
                         'apply_connection_id' => $apply_connection_id,
                         'sender_user_type_id' => 0,
                         'receiver_user_type_id' => 12,
                         'forward_date' => date('Y-m-d'),
                         'forward_time' => date('H:i:s'),
                         'created_on' =>date('Y-m-d H:i:s'),
                         'level_pending_status'=>0,
                         'emp_details_id' => $login_emp_details_id,
                         'remarks' => '',
                        'verification_status' => 0
                        ];
            //print_r($leveldata);
                  if($category=='BPL')
                  {

                      $pay_receipt=1;
                      $meter_bill=1;
                       if($data['water_conn_dtl']['connection_through_id']==1)
                        {
                            $pay_receipt=$data['pr_stts'];
                        }
                        if($data['water_conn_dtl']['connection_through_id']==2)
                        {
                            $meter_bill=$data['mb_stts'];
                        }


                    if($data['owner_doc_upload_stts'] && $pay_receipt && $data['ap_stts'] && $data['cf_stts'] && $data['ed_stts'] && $meter_bill && $data['bpl_stts'])
                     {
                        //echo "hi";
                        if($level_pending_status=='2')
                        { 

                            $data['level_pending_stts']=$this->WaterApplyNewConnectionModel->update_level_pending_status($leveldata);
                            if($payment_status=='1')
                            {
                                $level_pending_insrt=$this->model_water_level_pending_dtl->insrtlevelpendingdtl($leveldata);
                                return $this->response->redirect(base_url('waterdocument/docview/'.$id.''));
                            }
                        }
                    }
                  }
                  else{

                      $pay_receipt=1;
                      $meter_bill=1;
                      
                       if($data['water_conn_dtl']['connection_through_id']==1)
                        {
                            $pay_receipt=$data['pr_stts'];
                        }
                        if($data['water_conn_dtl']['connection_through_id']==2)
                        {
                            $meter_bill=$data['mb_stts'];
                        }

                        if($data['owner_doc_upload_stts'] && $pay_receipt && $data['ap_stts'] && $data['cf_stts'] && $data['ed_stts'] && $meter_bill)
                        {
                            if($level_pending_status=='2')
                            { 
                                $data['level_pending_stts']=$this->WaterApplyNewConnectionModel->update_level_pending_status($leveldata);
                                if($payment_status=='1')
                                {
                                    $level_pending_insrt=$this->model_water_level_pending_dtl->insrtlevelpendingdtl($leveldata);
                                    return $this->response->redirect(base_url('waterdocument/docview/'.$id.''));
                                }
                            }
                        }
                  }


            if($this->request->getMethod()=='post'){

            //Owner Img
            if(isset($_POST['btn_owner_img']))
            {
                $data = [
                        'applicant_detail_id' => $this->request->getVar('saf_own_dtl_id'),
                         'apply_connection_id' => $apply_connection_id,
                         'emp_details_id' => $login_emp_details_id,
                         'created_on' =>date('Y-m-d H:i:s')
                    ];
                $rules=[
                        'applicant_image_path'=>'uploaded[applicant_image_path]|max_size[applicant_image_path,10240]|ext_in[applicant_image_path,png,jpg,jpeg]',                        
                ];

                if($this->validate($rules)){ 
                    $applicant_image_file=$this->request->getFile('applicant_image_path');
                    if(isset($applicant_image_file))
                    {
                        if($ownerimginsert_last_idd = $this->model_applicant_doc->insertOwnerImgData($data)){
                            if($applicant_image_file->IsValid() && !$applicant_image_file->hasMoved()){
                                $newFileName = md5($ownerimginsert_last_idd);
                                $file_ext = $applicant_image_file->getExtension();
                                $path = $ulb_city_nm."/"."water_doc_dtl";

                                if($applicant_image_file->move(WRITEPATH.'uploads/'.$path.'/',$newFileName.'.'.$file_ext)){

                                    $owner_img_path = $path."/".$newFileName.'.'.$file_ext;
                                    if($updaterowimg = $this->model_applicant_doc->updatedocpathById($ownerimginsert_last_idd,$owner_img_path))
                                    {
                                        return $this->response->redirect(base_url('BO_WBackToCitizen/view/'.$id.''));
                                    }
                                }
                            }
                        }
                    }
                }
                else{
                    $data['err_msg']='Error Occurs!!';
                    return view('water/water_connection/bo_document_upload', $data);
                }
            }
                
            //Other doc
            if(isset($_POST['btn_owner_doc']))
            {
                $data = [
                        'applicant_detail_id' => $this->request->getVar('saf_owner_dtl_id'),
                        'owner_doc_mstr_id' => $this->request->getVar('owner_doc_mstr_id'),
                         'apply_connection_id' => $apply_connection_id,
                         'emp_details_id' => $login_emp_details_id,
                         'created_on' =>date('Y-m-d H:i:s')
                    ];
                $rules=[
                        'owner_doc_path'=>'uploaded[owner_doc_path]|max_size[owner_doc_path,1024000]|ext_in[owner_doc_path,pdf]'
                ];

                $owner_doc_file=$this->request->getFile('owner_doc_path');
                    if($this->validate($rules)){ 
                    if(isset($owner_doc_file))
                    {
                        if($ownerinsert_last_idd = $this->model_applicant_doc->insertOwnerData($data)){
                            if($owner_doc_file->IsValid() && !$owner_doc_file->hasMoved()){
                                $newFileNamee = md5($ownerinsert_last_idd);
                                $file_extt = $owner_doc_file->getExtension();
                                $path = $ulb_city_nm."/"."water_doc_dtl";

                                if($owner_doc_file->move(WRITEPATH.'uploads/'.$path.'/',$newFileNamee.'.'.$file_extt)){

                                    $owner_doc_path = $path."/".$newFileNamee.'.'.$file_extt;
                                   if($updaterow = $this->model_applicant_doc->updatedocpathById($ownerinsert_last_idd,$owner_doc_path))
                                    {
                                       return $this->response->redirect(base_url('BO_WBackToCitizen/view/'.$id.''));
                                   }

                                }
                            }
                        }
                    }
                }
                else{
                    $data['err_msg']='Error Occurs!!';
                    return view('water/water_connection/bo_document_upload', $data);
                }                  
            }

                //Payment Receipt doc
            if(isset($_POST['btn_pr_doc']))
            {
                $data = [
                         'apply_connection_id' => $apply_connection_id,
                         'document_id' => 0,
                         'doc_for' => 'payment_receipt',
                        'emp_details_id' => $login_emp_details_id,
                         'created_on' =>date('Y-m-d H:i:s')
                    ];
                $rules=[
                        'pr_doc_path'=>'uploaded[pr_doc_path]|max_size[pr_doc_path,1024000]|ext_in[pr_doc_path,pdf]',
                ];
                if($this->validate($rules)){                
                    if($insert_last_id = $this->model_applicant_doc->insertData($data)){
                        $file=$this->request->getFile('pr_doc_path');
                        if($file->IsValid() && !$file->hasMoved()){
                            $newFileName = md5($insert_last_id);
                            //$file_ext = pathinfo($_FILES["trans_doc_path"]["name"],PATHINFO_EXTENSION);
                            $file_ext = $file->getExtension();
                            $path = $ulb_city_nm."/"."water_doc_dtl";

                            if($file->move(WRITEPATH.'uploads/'.$path.'/',$newFileName.'.'.$file_ext)){

                                $trans_doc_path = $path."/".$newFileName.'.'.$file_ext;
                                if($updaterow = $this->model_applicant_doc->updatedocpathById($insert_last_id,$trans_doc_path))
                                {
                                    return $this->response->redirect(base_url('BO_WBackToCitizen/view/'.$id.''));
                                }
                            }
                        }
                    }
                }
                else{
                    $data['err_msg']='Error Occurs!!';
                    return view('water/water_connection/bo_document_upload', $data);
                }
            }
            //Address Proof doc
            if(isset($_POST['btn_ap_doc']))
            {
                $ap_doc_for='address_proof';
                $data = [
                        'document_id' => $this->request->getVar('ap_doc_mstr_id'),
                         'apply_connection_id' => $apply_connection_id,
                        'doc_for' => $ap_doc_for,
                         'emp_details_id' => $login_emp_details_id,
                         'created_on' =>date('Y-m-d H:i:s')
                    ];

                $rules=[
                        'ap_doc_path'=>'uploaded[ap_doc_path]|max_size[ap_doc_path,1024000]|ext_in[ap_doc_path,pdf]',
                ];

                if($this->validate($rules)){ 
                    if($pr_insert_last_id = $this->model_applicant_doc->insertData($data)){
                        $prop_doc_file=$this->request->getFile('ap_doc_path');
                        if($prop_doc_file->IsValid() && !$prop_doc_file->hasMoved()){
                            $newFileName = md5($pr_insert_last_id);
                            $file_ext = $prop_doc_file->getExtension();
                            $path = $ulb_city_nm."/"."water_doc_dtl";

                            if($prop_doc_file->move(WRITEPATH.'uploads/'.$path.'/',$newFileName.'.'.$file_ext)){

                                $prop_doc_path = $path."/".$newFileName.'.'.$file_ext;
                                if($updaterow = $this->model_applicant_doc->updatedocpathById($pr_insert_last_id,$prop_doc_path))
                                {
                                    return $this->response->redirect(base_url('BO_WBackToCitizen/view/'.$id.''));
                                }
                            }
                        }
                    }
                }
                else{
                    $data['err_msg']='Error Occurs!!';
                    return view('water/water_connection/bo_document_upload', $data);
                }
            }
                //Connection Form doc
            if(isset($_POST['btn_cf_doc']))
            {
                $data = [
                         'apply_connection_id' => $apply_connection_id,
                         'document_id' => 0,
                         'doc_for' => 'connection_form',
                        'emp_details_id' => $login_emp_details_id,
                         'created_on' =>date('Y-m-d H:i:s')
                    ];
                $rules=[
                        'cf_doc_path'=>'uploaded[cf_doc_path]|max_size[cf_doc_path,1024000]|ext_in[cf_doc_path,pdf]',
                ];
                if($this->validate($rules)){                
                    if($insert_last_id = $this->model_applicant_doc->insertData($data)){
                        $file=$this->request->getFile('cf_doc_path');
                        if($file->IsValid() && !$file->hasMoved()){
                            $newFileName = md5($insert_last_id);
                            //$file_ext = pathinfo($_FILES["trans_doc_path"]["name"],PATHINFO_EXTENSION);
                            $file_ext = $file->getExtension();
                            $path = $ulb_city_nm."/"."water_doc_dtl";

                            if($file->move(WRITEPATH.'uploads/'.$path.'/',$newFileName.'.'.$file_ext)){

                                $trans_doc_path = $path."/".$newFileName.'.'.$file_ext;
                                if($updaterow = $this->model_applicant_doc->updatedocpathById($insert_last_id,$trans_doc_path))
                                {
                                    return $this->response->redirect(base_url('BO_WBackToCitizen/view/'.$id.''));
                                }
                            }
                        }
                    }
                }
                else{
                    $data['err_msg']='Error Occurs!!';
                    return view('water/water_connection/bo_document_upload', $data);
                }
            }
                            //Electricity Bill doc
            if(isset($_POST['btn_ed_doc']))
            {
                $data = [
                         'apply_connection_id' => $apply_connection_id,
                         'document_id' => 0,
                         'doc_for' => 'electricity_bill',
                        'emp_details_id' => $login_emp_details_id,
                         'created_on' =>date('Y-m-d H:i:s')
                    ];
                $rules=[
                        'ed_doc_path'=>'uploaded[ed_doc_path]|max_size[ed_doc_path,1024000]|ext_in[ed_doc_path,pdf]',
                ];
                if($this->validate($rules)){                
                    if($insert_last_id = $this->model_applicant_doc->insertData($data)){
                        $file=$this->request->getFile('ed_doc_path');
                        if($file->IsValid() && !$file->hasMoved()){
                            $newFileName = md5($insert_last_id);
                            //$file_ext = pathinfo($_FILES["trans_doc_path"]["name"],PATHINFO_EXTENSION);
                            $file_ext = $file->getExtension();
                            $path = $ulb_city_nm."/"."water_doc_dtl";

                            if($file->move(WRITEPATH.'uploads/'.$path.'/',$newFileName.'.'.$file_ext)){

                                $trans_doc_path = $path."/".$newFileName.'.'.$file_ext;
                                if($updaterow = $this->model_applicant_doc->updatedocpathById($insert_last_id,$trans_doc_path))
                                {
                                    return $this->response->redirect(base_url('BO_WBackToCitizen/view/'.$id.''));
                                }
                            }
                        }
                    }
                }
                else{
                    $data['err_msg']='Error Occurs!!';
                    return view('water/water_connection/bo_document_upload', $data);
                }
            }
             //Meter Bill doc
            if(isset($_POST['btn_mb_doc']))
            {
                $data = [
                         'apply_connection_id' => $apply_connection_id,
                         'document_id' => 0,
                         'doc_for' => 'meter_bill',
                        'emp_details_id' => $login_emp_details_id,
                         'created_on' =>date('Y-m-d H:i:s')
                    ];
                $rules=[
                        'mb_doc_path'=>'uploaded[mb_doc_path]|max_size[mb_doc_path,1024000]|ext_in[mb_doc_path,pdf]',
                ];
                if($this->validate($rules)){                
                    if($insert_last_id = $this->model_applicant_doc->insertData($data)){
                        $file=$this->request->getFile('mb_doc_path');
                        if($file->IsValid() && !$file->hasMoved()){
                            $newFileName = md5($insert_last_id);
                            //$file_ext = pathinfo($_FILES["trans_doc_path"]["name"],PATHINFO_EXTENSION);
                            $file_ext = $file->getExtension();
                            $path = $ulb_city_nm."/"."water_doc_dtl";

                            if($file->move(WRITEPATH.'uploads/'.$path.'/',$newFileName.'.'.$file_ext)){

                                $trans_doc_path = $path."/".$newFileName.'.'.$file_ext;
                                if($updaterow = $this->model_applicant_doc->updatedocpathById($insert_last_id,$trans_doc_path))
                                {
                                    return $this->response->redirect(base_url('BO_WBackToCitizen/view/'.$id.''));
                                }
                            }
                        }
                    }
                }
                else{
                    $data['err_msg']='Error Occurs!!';
                    return view('water/water_connection/bo_document_upload', $data);
                }
            }
            //BPL doc
            if(isset($_POST['btn_bpl_doc']))
            {
                $data = [
                         'apply_connection_id' => $apply_connection_id,
                         'document_id' => 0,
                         'doc_for' => 'bpl',
                        'emp_details_id' => $login_emp_details_id,
                         'created_on' =>date('Y-m-d H:i:s')
                    ];
                $rules=[
                        'bpl_doc_path'=>'uploaded[bpl_doc_path]|max_size[bpl_doc_path,1024000]|ext_in[bpl_doc_path,pdf]',
                ];
                if($this->validate($rules)){                
                    if($insert_last_id = $this->model_applicant_doc->insertData($data)){
                        $file=$this->request->getFile('bpl_doc_path');
                        if($file->IsValid() && !$file->hasMoved()){
                            $newFileName = md5($insert_last_id);
                            //$file_ext = pathinfo($_FILES["trans_doc_path"]["name"],PATHINFO_EXTENSION);
                            $file_ext = $file->getExtension();
                            $path = $ulb_city_nm."/"."water_doc_dtl";

                            if($file->move(WRITEPATH.'uploads/'.$path.'/',$newFileName.'.'.$file_ext)){

                                $trans_doc_path = $path."/".$newFileName.'.'.$file_ext;
                                if($updaterow = $this->model_applicant_doc->updatedocpathById($insert_last_id,$trans_doc_path))
                                {
                                    return $this->response->redirect(base_url('BO_WBackToCitizen/view/'.$id.''));
                                }
                            }
                        }
                    }
                }
                else{
                    $data['err_msg']='Error Occurs!!';
                    return view('water/water_connection/bo_document_upload', $data);
                }
            }
        }
        else
        {
             return view('water/water_connection/bo_document_upload', $data);
        }
    }


}