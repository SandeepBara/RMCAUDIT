<?php 
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_ulb_mstr;
use App\Models\model_view_ward_permission;
use App\Models\WaterApplyNewConnectionModel;
use App\Models\model_ward_mstr;
use App\Models\model_applicant_details;
use App\Models\model_applicant_doc;
use App\Models\model_water_level_pending_dtl;
use App\Models\model_view_water_connection;
use App\Models\model_document_mstr;

class WaterDocument extends AlphaController
{
    protected $db;
    protected $property_db;
    protected $dbSystem;
    protected $model_ulb_mstr;
    protected $model_view_ward_permission;
    protected $WaterApplyNewConnectionModel;
    protected $model_ward_mstr;
    protected $model_applicant_details;
    protected $model_applicant_doc;
    protected $model_water_level_pending_dtl;
    protected $model_view_water_connection;
    protected $model_document_mstr;
    
    public function __construct(){
        parent::__construct();
    	helper(['db_helper', 'upload_helper']);
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
        $this->model_water_level_pending_dtl = new model_water_level_pending_dtl($this->db);
        $this->model_view_water_connection = new model_view_water_connection($this->db);
        $this->model_document_mstr = new model_document_mstr($this->db);
    }

    public function index()
	{
        $data =(array)null;
        helper(['form']);
        $session = session();
        $ulb_dtl = $session->get('ulb_dtl');
        $ulb_mstr_id = $ulb_dtl['ulb_mstr_id'];
        //Transaction Mode List
        $emp_mstr = $session->get("emp_details");
        $login_emp_details_id = $emp_mstr["id"];

        $wardList = $this->model_view_ward_permission->getPermittedWard($login_emp_details_id);
        $data['wardList'] = $wardList;

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

        if($this->request->getMethod()=='post'){

            $data['ward_mstr_id'] = $this->request->getVar('ward_mstr_id');
             $data['from_date'] = $this->request->getVar('from_date');
             $data['to_date'] = $this->request->getVar('to_date');
            if($data['ward_mstr_id']!="")
            {
                $data['posts'] = $this->WaterApplyNewConnectionModel->wardwise_water_con_list($data['from_date'],$data['to_date'],$data['ward_mstr_id']);
            }
            else{
                $data['posts'] = $this->WaterApplyNewConnectionModel->water_con_list($data['from_date'],$data['to_date'],$ward);
            }


        $j=0;
        foreach($data['posts'] as $key => $value){
                    $wardd = $this->model_ward_mstr->getdatabyid($value['ward_id']);
                   $owner = $this->model_applicant_details->applicantdetails($value['id']);
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
            return view('water/water_connection/water_conn_list', $data);
            }
        else
            {
            $data['from_date'] = date('Y-m-d');
            $data['to_date'] = date('Y-m-d');


            $data['wardList'];
            $data['posts'] = $this->WaterApplyNewConnectionModel->water_con_list($data['from_date'],$data['to_date'],$ward);
        //print_r($data['posts']);

        $j=0;
        foreach($data['posts'] as $key => $value){
                   $wardd = $this->model_ward_mstr->getdatabyid($value['ward_id']);
                   $owner = $this->model_applicant_details->applicantdetails($value['id']);
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
           // print_r($data['posts']);
            return view('water/water_connection/water_conn_list', $data);
            }
	}

    public function doc_upload($id=null){

        $pay_receipt="";
        $meter_bill="";
        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $ulb_mstr_id = $ulb_mstr["ulb_mstr_id"];
        //$ulb_shrt_nm = $ulb_mstr["short_ulb_name"];
        $emp_mstr = $Session->get("emp_details");
        $login_emp_details_id = $emp_mstr["id"];
        date_default_timezone_set('Asia/Kolkata');
        $data =(array)null;
        $data['ulb_dtl'] = $this->model_ulb_mstr->getulb_list($ulb_mstr_id);
        $ulb_city_nm=$data['ulb_dtl']['city'];
        $address_proof_doc_for='Address Proof';
        $photo_id_proof_doc_for='ID Proof';
        $data['address_proof_document_list'] = $this->model_document_mstr->getDocumentList($address_proof_doc_for);
        $data['photo_id_proof_document_list'] = $this->model_document_mstr->getDocumentList($photo_id_proof_doc_for);
        //print_r($data['address_proof_document_list']);
        $data['water_conn_dtl'] = $this->WaterApplyNewConnectionModel->getData($id);
        //print_r($data['water_conn_dtl']);
        $data['connection_dtls']=$this->WaterApplyNewConnectionModel->water_conn_details($id);
        // print_r($data['connection_dtls']);
        $apply_connection_id=$data['water_conn_dtl']['id'];
        $data['owner_list'] = $this->model_applicant_details->applicantdetails_md5($id);
		
		foreach($data['owner_list'] as $key => $value){
			if(!empty($conn_owner_id=$this->model_applicant_doc->check_owner_img($apply_connection_id,$value['id']))){
				$data['owner_list'][$key]['conn_owner_id']=$conn_owner_id;
			}
			if(!empty($conn_owner_doc=$this->model_applicant_doc->check_owner_doc($apply_connection_id,$value['id']))){
				$data['owner_list'][$key]['conn_owner_doc']=$conn_owner_doc;
			}
			
		}
		
		$doc_nam = "Address Proof";
		$data['address_prf_doc']=$this->model_applicant_doc->check_other_doc($apply_connection_id,$doc_nam);
		$doc_nam = "Form(Scan Copy)";
		$data['connection_form_doc']=$this->model_applicant_doc->check_other_doc($apply_connection_id,$doc_nam);
		$doc_nam = "ELECTRICITY_NEW";
		$data['electricity_bill_doc']=$this->model_applicant_doc->check_other_doc($apply_connection_id,$doc_nam);
		$doc_nam = "HOLDING PROOF";
		$data['payment_receipt_doc']=$this->model_applicant_doc->check_other_doc($apply_connection_id,$doc_nam);
		$doc_nam = "METER BILL";
		$data['meter_bill_doc']=$this->model_applicant_doc->check_other_doc($apply_connection_id,$doc_nam);
		$doc_nam = "BPL";
		$data['bpl_doc']=$this->model_applicant_doc->check_other_doc($apply_connection_id,$doc_nam);
        //print_r($data['address_prf_doc']);
        
        $category=$data['water_conn_dtl']['category'];
        $doc_status=$data['water_conn_dtl']['doc_status'];
        $payment_status=$data['water_conn_dtl']['payment_status'];
        
        if($this->request->getMethod()=='post'){
			if(isset($_POST['btn_owner_doc']))
            {
                $data = [
                        'applicant_detail_id' => $this->request->getVar('owner_dtl_id'),
                        'owner_doc_mstr_id' => $this->request->getVar('owner_doc_mstr_id'),
                        'apply_connection_id' => $apply_connection_id,
                        'emp_details_id' => $login_emp_details_id,
                        'created_on' =>date('Y-m-d H:i:s')
                    ];

                /********applicant image upload starts***************/
                $rules=[
                        'consumer_photo_doc_path'=>'uploaded[consumer_photo_doc_path]|max_size[consumer_photo_doc_path,10240]|ext_in[consumer_photo_doc_path,png,jpg,jpeg]',
                        'photo_id_proof_doc_path'=>'uploaded[photo_id_proof_doc_path]|max_size[photo_id_proof_doc_path,1024000]|ext_in[photo_id_proof_doc_path,pdf]',
                ];
                $applicant_image_file=$this->request->getFile('consumer_photo_doc_path');
                $owner_doc_file=$this->request->getFile('photo_id_proof_doc_path');
                if($this->validate($rules)){ 
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
                                                    return $this->response->redirect(base_url('waterdocument/doc_upload/'.$id.''));
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
                }
                else{
                    $data['err_msg']='Error Occurs!!';
                    return view('water/water_connection/water_document_upload', $data);
                }

                /**********applicant image upload ends***********/

            }
			
			if(isset($_POST['btn_address_proof']))
            {
                $data = [
                        'apply_connection_id' => $apply_connection_id,
                        'doc_for' => 'Address Proof',
                        'document_id' => $this->request->getVar('address_proof_type'),
                        'emp_details_id' => $login_emp_details_id,
                        'created_on' =>date('Y-m-d H:i:s')
                    ];                
                $rules=[
                        'address_proof_doc_path'=>'uploaded[address_proof_doc_path]|max_size[address_proof_doc_path,10240]|ext_in[address_proof_doc_path,pdf]',
                ];
                if($this->validate($rules)){ 
                    if($add_insert_last_id = $this->model_applicant_doc->insertData($data)){
                        $add_proof_doc_file=$this->request->getFile('address_proof_doc_path');
                        if($add_proof_doc_file->IsValid() && !$add_proof_doc_file->hasMoved()){
                             $newFileName = md5($add_insert_last_id);
                            $file_ext = $add_proof_doc_file->getExtension();
                            $path = $ulb_city_nm."/"."water_doc_dtl";

                            if($add_proof_doc_file->move(WRITEPATH.'uploads/'.$path.'/',$newFileName.'.'.$file_ext)){
                                $add_proof_doc_path = $path."/".$newFileName.'.'.$file_ext;
                                if($updaterow = $this->model_applicant_doc->updatedocpathById($add_insert_last_id,$add_proof_doc_path))
                                {
                                    return $this->response->redirect(base_url('waterdocument/doc_upload/'.$id.''));
                                }
                            }
                        }
                    }
                }
                else{
                    $data['err_msg']='Error Occurs!!';
                    return view('water/water_connection/water_document_upload', $data);
                }
            }
			
			
			if(isset($_POST['btn_connection_form']))
            {
                $data = [
                        'apply_connection_id' => $apply_connection_id,
                        'doc_for' => 'Form(Scan Copy)',
                        'document_id' => 19,
                        'emp_details_id' => $login_emp_details_id,
                        'created_on' =>date('Y-m-d H:i:s')
                    ];
                $rules=[
                        'connection_form_doc_path'=>'uploaded[connection_form_doc_path]|max_size[connection_form_doc_path,10240]|ext_in[connection_form_doc_path,pdf]',
                ];
                if($this->validate($rules)){ 
                    if($conn_insert_last_id = $this->model_applicant_doc->insertData($data)){
                        $conn_form_doc_file=$this->request->getFile('connection_form_doc_path');
                        if($conn_form_doc_file->IsValid() && !$conn_form_doc_file->hasMoved()){
                             $newFileName = md5($conn_insert_last_id);
                            $file_ext = $conn_form_doc_file->getExtension();
                            $path = $ulb_city_nm."/"."water_doc_dtl";

                            if($conn_form_doc_file->move(WRITEPATH.'uploads/'.$path.'/',$newFileName.'.'.$file_ext)){
                                $connection_form_doc_path = $path."/".$newFileName.'.'.$file_ext;
                                if($updaterow = $this->model_applicant_doc->updatedocpathById($conn_insert_last_id,$connection_form_doc_path))
                                {
                                    return $this->response->redirect(base_url('waterdocument/doc_upload/'.$id.''));
                                }
                            }
                        }
                    }
                }
                else{
                    $data['err_msg']='Error Occurs!!';
                    return view('water/water_connection/water_document_upload', $data);
                }
            }
			
			
			if(isset($_POST['btn_electricity_bill']))
            {
                $data = [
                        'apply_connection_id' => $apply_connection_id,
                        'doc_for' => 'ELECTRICITY_NEW',
                        'document_id' => 20,
                        'emp_details_id' => $login_emp_details_id,
                        'created_on' =>date('Y-m-d H:i:s')
                    ];
                $rules=[
                        'electricity_bill_doc_path'=>'uploaded[electricity_bill_doc_path]|max_size[electricity_bill_doc_path,10240]|ext_in[electricity_bill_doc_path,pdf]',
                ];
                if($this->validate($rules)){ 
                    if($electric_insert_last_id = $this->model_applicant_doc->insertData($data)){
                        $electric_form_doc_file=$this->request->getFile('electricity_bill_doc_path');
                        if($electric_form_doc_file->IsValid() && !$electric_form_doc_file->hasMoved()){
                             $newFileName = md5($electric_insert_last_id);
                            $file_ext = $electric_form_doc_file->getExtension();
                            $path = $ulb_city_nm."/"."water_doc_dtl";

                            if($electric_form_doc_file->move(WRITEPATH.'uploads/'.$path.'/',$newFileName.'.'.$file_ext)){
                                $electricity_bill_doc_path = $path."/".$newFileName.'.'.$file_ext;
                                if($updaterow = $this->model_applicant_doc->updatedocpathById($electric_insert_last_id,$electricity_bill_doc_path))
                                {
                                    return $this->response->redirect(base_url('waterdocument/doc_upload/'.$id.''));
                                }
                            }
                        }
                    }
                }
                else{
                    $data['err_msg']='Error Occurs!!';
                    return view('water/water_connection/water_document_upload', $data);
                }
            }
			
			
			//Last payment receipt doc
            if(isset($_POST['btn_payment_receipt']))
            {
                $data = [
                        'apply_connection_id' => $apply_connection_id,
                        'doc_for' => 'HOLDING PROOF',
                        'document_id' => 6,
                        'emp_details_id' => $login_emp_details_id,
                        'created_on' =>date('Y-m-d H:i:s')
                    ];
                $rules=[
                        'last_payment_doc_path'=>'uploaded[last_payment_doc_path]|max_size[last_payment_doc_path,10240]|ext_in[last_payment_doc_path,pdf]',
                ];
                if($this->validate($rules)){ 
                    if($insert_last_idd = $this->model_applicant_doc->insertData($data)){
                        $last_payment_doc_file=$this->request->getFile('last_payment_doc_path');
                        if($last_payment_doc_file->IsValid() && !$last_payment_doc_file->hasMoved()){
                             $newFileName = md5($insert_last_idd);
                            $file_ext = $last_payment_doc_file->getExtension();
                            $path = $ulb_city_nm."/"."water_doc_dtl";

                            if($last_payment_doc_file->move(WRITEPATH.'uploads/'.$path.'/',$newFileName.'.'.$file_ext)){

                                $last_payment_doc_path = $path."/".$newFileName.'.'.$file_ext;
                                if($updaterow = $this->model_applicant_doc->updatedocpathById($insert_last_idd,$last_payment_doc_path))
                                {
                                    return $this->response->redirect(base_url('waterdocument/doc_upload/'.$id.''));
                                }
                            }
                        }
                    }
                }
                else{
                    $data['err_msg']='Error Occurs!!';
                    return view('water/water_connection/water_document_upload', $data);
                }
            }
			
			//Meter Bill doc
            if(isset($_POST['btn_meter_bill']))
            {
                $data = [
                        'apply_connection_id' => $apply_connection_id,
                        'doc_for' => 'METER BILL',
                        'document_id' => 26,
                        'emp_details_id' => $login_emp_details_id,
                        'created_on' =>date('Y-m-d H:i:s')
                    ];
                $rules=[
                        'meter_bill_doc_path'=>'uploaded[meter_bill_doc_path]|max_size[meter_bill_doc_path,10240]|ext_in[meter_bill_doc_path,pdf]',
                ];
                if($this->validate($rules)){ 
                    if($meter_bill_insert_last_id = $this->model_applicant_doc->insertData($data)){
                        $meter_bill_doc_file=$this->request->getFile('meter_bill_doc_path');
                        if($meter_bill_doc_file->IsValid() && !$meter_bill_doc_file->hasMoved()){
                             $newFileName = md5($meter_bill_insert_last_id);
                            $file_ext = $meter_bill_doc_file->getExtension();
                            $path = $ulb_city_nm."/"."water_doc_dtl";

                            if($meter_bill_doc_file->move(WRITEPATH.'uploads/'.$path.'/',$newFileName.'.'.$file_ext)){
                                $meter_bill_doc_path = $path."/".$newFileName.'.'.$file_ext;
                                if($updaterow = $this->model_applicant_doc->updatedocpathById($meter_bill_insert_last_id,$meter_bill_doc_path))
                                {
                                    return $this->response->redirect(base_url('waterdocument/doc_upload/'.$id.''));
                                }
                            }
                        }
                    }
                }
                else{
                    $data['err_msg']='Error Occurs!!';
                    return view('water/water_connection/water_document_upload', $data);
                }
            }
            //BPL doc
            if(isset($_POST['btn_bpl']))
            {
                $data = [
                        'apply_connection_id' => $apply_connection_id,
                        'doc_for' => 'BPL',
                        'document_id' => 18,
                        'emp_details_id' => $login_emp_details_id,
                        'created_on' =>date('Y-m-d H:i:s')
                    ];
                $rules=[
                        'bpl_doc_path'=>'uploaded[bpl_doc_path]|max_size[bpl_doc_path,10240]|ext_in[bpl_doc_path,pdf]',
                ];
                if($this->validate($rules)){ 
                    if($bpl_insert_last_id = $this->model_applicant_doc->insertData($data)){
                        $bpl_doc_file=$this->request->getFile('bpl_doc_path');
                        if($bpl_doc_file->IsValid() && !$bpl_doc_file->hasMoved()){
                             $newFileName = md5($bpl_insert_last_id);
                            $file_ext = $bpl_doc_file->getExtension();
                            $path = $ulb_city_nm."/"."water_doc_dtl";

                            if($bpl_doc_file->move(WRITEPATH.'uploads/'.$path.'/',$newFileName.'.'.$file_ext)){
                                $bpl_doc_path = $path."/".$newFileName.'.'.$file_ext;
                                if($updaterow = $this->model_applicant_doc->updatedocpathById($bpl_insert_last_id,$bpl_doc_path))
                                {
                                    return $this->response->redirect(base_url('waterdocument/doc_upload/'.$id.''));
                                }
                            }
                        }
                    }
                }
                else{
                    $data['err_msg']='Error Occurs!!';
                    return view('water/water_connection/water_document_upload', $data);
                }
            }
			
			
			
		}
		
		else
		{
            $data['owner_doc_exists'] = $this->model_applicant_details->applicantdetails_md5($id);
            $iw=0;
            foreach($data['owner_doc_exists'] as $key => $value){
                $conn_owner_id=$this->model_applicant_doc->check_owner_details($apply_connection_id,$value['id']);
                $data['owner_doc_exists'][$key]['applicant_detail_id'] = $conn_owner_id['applicant_detail_id'];
                //echo $data['owner_document_exists'][$key]['saf_owner_dtl_id'];
                if($data['owner_doc_exists'][$key]['applicant_detail_id']!='')
                {
                    $iw++;
                }

            }
            //print_r($data['owner_doc_exists'] );
            $count_owner_doc=count($data['owner_doc_exists']);
            //echo $iw;
            if($count_owner_doc==$iw)
            {
                $data['owner_doc_upload_stts']='true';
            }

            $payment_doc="HOLDING PROOF";
            $data['payment_receipt_doc_exists']=$this->model_applicant_doc->check_doc_exist($apply_connection_id,$payment_doc);
            $add_doc="Address Proof";
            $data['address_proof_doc_exists']=$this->model_applicant_doc->check_doc_exist($apply_connection_id,$add_doc);
            $photo_doc="ID Proof";
            $data['photo_id_proof_doc_exists']=$this->model_applicant_doc->check_doc_exist($apply_connection_id,$photo_doc);
            $connection_doc="Form(Scan Copy)";
            $data['connection_doc_exists']=$this->model_applicant_doc->check_doc_exist($apply_connection_id,$connection_doc);
            $electricity_doc="ELECTRICITY_NEW";
            $data['electricity_doc_exists']=$this->model_applicant_doc->check_doc_exist($apply_connection_id,$electricity_doc);
            $consumer_photo_doc="CONSUMER_PHOTO";
            $data['consumer_photo_doc_exists']=$this->model_applicant_doc->check_doc_exist($apply_connection_id,$consumer_photo_doc);
            $meter_bill_doc="METER BILL";
            $data['meter_bill_doc_exists']=$this->model_applicant_doc->check_doc_exist($apply_connection_id,$meter_bill_doc);
            $bpl_doc="BPL";
            $data['bpl_doc_exists']=$this->model_applicant_doc->check_doc_exist($apply_connection_id,$bpl_doc);
			//print_r($data['owner_doc_upload_stts']);

			
            $leveldata = [
                         'apply_connection_id' => $apply_connection_id,
                         'sender_user_type_id' => 0,
                         'receiver_user_type_id' => 12,
                         'forward_date' => date('Y-m-d'),
                         'forward_time' => date('H:i:s'),
                         'created_on' =>date('Y-m-d H:i:s'),
                         'remarks' => '',
                         'emp_details_id' => $login_emp_details_id,
                         'verification_status' => 0
                        ];

            if($category=="BPL")
            {   
                $pay_receipt=1;
                $meter_bill=1;

                if($data['water_conn_dtl']['connection_through_id']==1)
                {
                    $pay_receipt=$data['payment_receipt_doc_exists'];
                }
                if($data['water_conn_dtl']['connection_through_id']==2)
                {
                    $meter_bill=$data['meter_bill_doc_exists'];
                }

                 /*if($data['owner_doc_upload_stts'] && $data['payment_receipt_doc_exists'] && $data['address_proof_doc_exists']  && $data['connection_doc_exists'] && $data['electricity_doc_exists']  && $data['meter_bill_doc_exists'] && $data['bpl_doc_exists'])*/

                if($data['owner_doc_upload_stts'] && $pay_receipt && $data['address_proof_doc_exists']  && $data['connection_doc_exists'] && $data['electricity_doc_exists']   && $data['bpl_doc_exists'] && $meter_bill)
                {   
                    if($doc_status=='0')
                    { 
                       // $data['doc_upload_stts']=$this->WaterApplyNewConnectionModel->update_doc_status($leveldata);
                        if($payment_status=='1')
                        {
                            $level_pending_insrt=$this->model_water_level_pending_dtl->insrtlevelpendingdtl($leveldata);
                            return $this->response->redirect(base_url('waterdocument/docview/'.$id.''));
                        }
                        else
                        {

                            return $this->response->redirect(base_url('waterdocument/docview/'.$id.''));
                        }
                    }

                }
            }
            else
            {
                $pay_receipt=1;
                $meter_bill=1;
                if($data['water_conn_dtl']['connection_through_id']==1)
                {
                    $pay_receipt=$data['payment_receipt_doc_exists'];
                }
                if($data['water_conn_dtl']['connection_through_id']==2)
                {
                    $meter_bill=$data['meter_bill_doc_exists'];
                }

                
                if($data['owner_doc_upload_stts'] && $pay_receipt && $data['address_proof_doc_exists']  && $data['connection_doc_exists'] && $data['electricity_doc_exists']  && $meter_bill)
                {
                    if($doc_status=='0')
                    { 
                        $data['doc_upload_stts']=$this->WaterApplyNewConnectionModel->update_doc_status($leveldata);
                        if($payment_status=='1')
                        {
                            $level_pending_insrt=$this->model_water_level_pending_dtl->insrtlevelpendingdtl($leveldata);
                            return $this->response->redirect(base_url('waterdocument/docview/'.$id.''));
                        }
                        else
                        {
                            return $this->response->redirect(base_url('waterdocument/docview/'.$id.''));
                        }
                    }

                }
				
				else if($data['owner_doc_upload_stts'] && $data['address_proof_doc_exists']  && $data['connection_doc_exists'] && $data['electricity_doc_exists'] )
                {
                    if($doc_status=='0')
                    { 
                        $data['doc_upload_stts']=$this->WaterApplyNewConnectionModel->update_doc_status($leveldata);
                        if($payment_status=='1')
                        {
                            $level_pending_insrt=$this->model_water_level_pending_dtl->insrtlevelpendingdtl($leveldata);
                            return $this->response->redirect(base_url('waterdocument/docview/'.$id.''));
                        }
                        else
                        {
                            return $this->response->redirect(base_url('waterdocument/docview/'.$id.''));
                        }
                    }

                }
            }

            return view('water/water_connection/water_document_upload', $data);
        }
        
        
    }

    public function view($id=null)
	{   
        $data =(array)null;
        $data['id']=$id;
        $Session = Session();
        date_default_timezone_set('Asia/Kolkata');
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");

        $login_emp_details_id = $emp_mstr["id"];
        $sender_user_type_id = $emp_mstr["user_type_mstr_id"];
        $data['basic_details'] = $this->model_view_water_connection->getDatabyid($id);
        //print_r($data['basic_details']);
        $data['ward'] = $this->model_ward_mstr->getdatabyid($data['basic_details']['ward_id']);
        $data['owner_details'] = $this->model_applicant_details->applicantdetails($data['basic_details']['id']);


        return view('water/water_connection/water_conn_view', $data);
        
    }
    public function docview($id=null)
	{
        $data =(array)null;
        $data['id']=$id;
        $Session = Session();
        date_default_timezone_set('Asia/Kolkata');
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");

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


        return view('water/water_connection/water_doc_view', $data);
    }

}