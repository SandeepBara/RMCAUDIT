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
use App\Models\Citizensw_water_model;
use Exception;

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
    protected $Citizensw_water_model;

    public function __construct()
    {
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
        $this->Citizensw_water_model = new Citizensw_water_model($this->db);
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
        foreach($data['posts'] as $key => $value)
        {
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


    public function doc_upload($id=null)
    {
        $pay_receipt="";
        $meter_bill="";
        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $ulb_mstr_id = $ulb_mstr["ulb_mstr_id"];
        //$ulb_shrt_nm = $ulb_mstr["short_ulb_name"];
        $emp_mstr = $Session->get("emp_details");
        $login_emp_details_id = $emp_mstr["id"];
        $data =(array)null;
        $data['ulb_dtl'] = $this->model_ulb_mstr->getulb_list($ulb_mstr_id);
        $ulb_city_nm=$data['ulb_dtl']['city'];
        $address_proof_doc_for='Address Proof';
        $photo_id_proof_doc_for='ID Proof';
        $data['address_proof_document_list'] = $this->model_document_mstr->getDocumentList($address_proof_doc_for);
        $data['photo_id_proof_document_list'] = $this->model_document_mstr->getDocumentList($photo_id_proof_doc_for);
        //print_r($data['photo_id_proof_document_list']);
        $data['water_conn_dtl'] = $this->WaterApplyNewConnectionModel->getData($id);
        $water_conn = $data['water_conn_dtl'];
        //print_r($data['water_conn_dtl']);
        $data['connection_dtls']=$this->WaterApplyNewConnectionModel->water_conn_details($id);
        // print_r($data['connection_dtls']);
        $apply_connection_id=$data['water_conn_dtl']['id'];
        $data['owner_list'] = $this->WaterApplyNewConnectionModel->water_owner_details($id);
		
		foreach($data['owner_list'] as $key => $value){
			if(!empty($conn_owner_id=$this->model_applicant_doc->check_owner_img($apply_connection_id,$value['id']))){
				$data['owner_list'][$key]['conn_owner_id']=$conn_owner_id;
			}
			if(!empty($conn_owner_doc=$this->model_applicant_doc->check_owner_doc($apply_connection_id,$value['id']))){
				$data['owner_list'][$key]['conn_owner_doc']=$conn_owner_doc;
			}
			
		}
		//print_var($data['owner_list']);
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
        
        if($this->request->getMethod()=='post')
        {
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
                else
                {
                    $data['err_msg']='Error Occurs!!';
                    return view('water/water_connection/water_document_upload', $data);
                }

                /**********applicant image upload ends***********/

            }
			
            # print_r($_POST);print_r($_FILES);
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
                if($this->validate($rules))
                {
                    if($add_insert_last_id = $this->model_applicant_doc->insertData($data))
                    {
                        $add_proof_doc_file=$this->request->getFile('address_proof_doc_path');
                        if($add_proof_doc_file->IsValid() && !$add_proof_doc_file->hasMoved())
                        {
                            $newFileName = md5($add_insert_last_id);
                            $file_ext = $add_proof_doc_file->getExtension();
                            $path = $ulb_city_nm."/"."water_doc_dtl";

                            if($add_proof_doc_file->move(WRITEPATH.'uploads/'.$path.'/',$newFileName.'.'.$file_ext))
                            {
                                $add_proof_doc_path = $path."/".$newFileName.'.'.$file_ext;
                                if($updaterow = $this->model_applicant_doc->updatedocpathById($add_insert_last_id,$add_proof_doc_path))
                                {
                                    return $this->response->redirect(base_url('waterdocument/doc_upload/'.$id.''));
                                }
                            }
                        }
                    }
                }
                else
                {
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
                else
                {
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
                else
                {
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
                if($this->validate($rules))
                { 
                    if($bpl_insert_last_id = $this->model_applicant_doc->insertData($data))
                    {
                        $bpl_doc_file=$this->request->getFile('bpl_doc_path');
                        if($bpl_doc_file->IsValid() && !$bpl_doc_file->hasMoved())
                        {
                             $newFileName = md5($bpl_insert_last_id);
                            $file_ext = $bpl_doc_file->getExtension();
                            $path = $ulb_city_nm."/"."water_doc_dtl";

                            if($bpl_doc_file->move(WRITEPATH.'uploads/'.$path.'/',$newFileName.'.'.$file_ext))
                            {
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
            else
            {
                $data['owner_doc_upload_stts']='false';
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
                
                # Id Proof
                if($data['water_conn_dtl']['connection_through_id']!=6)
                {
                    // if applied by holding or SAF than payment receipt is required
                    $pay_receipt=$data['payment_receipt_doc_exists'];
                }
                else
                {
                    $pay_receipt=1;
                }


                if($data['owner_doc_upload_stts'] && $pay_receipt && $data['address_proof_doc_exists']  && $data['connection_doc_exists'] && $data['electricity_doc_exists']   && $data['bpl_doc_exists'] && $data['meter_bill_doc_exists'])
                {   
                    if($doc_status=='0')
                    {
                        $data['doc_upload_stts']=$this->WaterApplyNewConnectionModel->update_doc_status($leveldata);
                        if($payment_status=='1')
                        {
                            $level_pending_insrt=$this->model_water_level_pending_dtl->insrtlevelpendingdtl($leveldata);
                            # ---------swm_push---------
                            if($water_conn['apply_from']=='sws')
                            {
                                $sws_whare = ['apply_connection_id'=>$apply_connection_id];
                                $sws = $this->Citizensw_water_model->getData($sws_whare);
                                if(!empty($sws))
                                {
                                    $sw = [];
                                    $sw['sw_stage']= 3 ;
                                    $where_sw = ['apply_connection_id'=>$apply_connection_id,'id'=> $sws['id']];                            
                                    $this->Citizensw_water_model->updateData($sw,$where_sw);
                                    
                                    $push_sw=array();
                                    $ulb_mstr_id = $_SESSION['ulb_dtl']['ulb_mstr_id']; 
                                    $path='';
                                    $push_sw['application_stage']=12;
                                    $push_sw['status']='Application sent to Dealing Assistant';
                                    $push_sw['acknowledgment_no']=$water_conn['water_conn_dtl']['application_no'];
                                    $push_sw['service_type_id']=$sws['service_id'];
                                    $push_sw['caf_unique_no']=$sws['caf_no'];
                                    $push_sw['department_id']=$sws['department_id'];
                                    $push_sw['Swsregid']=$sws['cust_id'];
                                    $push_sw['payable_amount ']='';
                                    $push_sw['payment_validity']='';
                                    $push_sw['payment_other_details']='';
                                    $push_sw['certificate_url']=$path;
                                    $push_sw['approval_date']='';
                                    $push_sw['expire_date']='';
                                    $push_sw['licence_no']='';
                                    $push_sw['certificate_no']='';
                                    $push_sw['customer_id']=$sws['cust_id'];
                                    $post_url =getenv('single_indow_push_url');
                                    $http = getenv('single_indow_push_http');
                                    $resp = httpPostJson($post_url,$push_sw,$http);
                                    //print_var($resp);
                                    $respons_data=[];
                                    $respons_data['apply_connection_id']=$apply_connection_id;
                                    $respons_data['response_msg']= json_encode(['url'=>$http.'/'.$post_url,
                                                                                    'data'=>$push_sw]);
                                    $respons_data['tbl_single_window_id']=$sws['id'];
                                    $respons_data['emp_id']=null;
                                    $respons_data['response_status']=json_encode($resp);
                                    $this->Citizensw_water_model->insertResponse($respons_data); 
                                    // print_var($resp);
                                    // die;
                                }
                            }
                            # ---------swm_push end ----
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
                # Id Proof
                if($data['water_conn_dtl']['connection_through_id']!=6)
                {
                    // if applied by holding or SAF than payment receipt is required
                    $pay_receipt=$data['payment_receipt_doc_exists'];
                }
                else
                {
                    $pay_receipt=1;
                }

                if($data['owner_doc_upload_stts'] && $pay_receipt && $data['address_proof_doc_exists']  && $data['connection_doc_exists'] && $data['electricity_doc_exists']  && $data['meter_bill_doc_exists'])
                {

                    if($doc_status=='0')
                    { 
                        $data['doc_upload_stts']=$this->WaterApplyNewConnectionModel->update_doc_status($leveldata);
                        if($payment_status=='1')
                        {
                            $level_pending_insrt=$this->model_water_level_pending_dtl->insrtlevelpendingdtl($leveldata);
                            # ---------swm_push---------
                            if($water_conn['apply_from']=='sws')
                            {
                                $sws_whare = ['apply_connection_id'=>$apply_connection_id];
                                $sws = $this->Citizensw_water_model->getData($sws_whare);
                                if(!empty($sws))
                                {
                                    $sw = [];
                                    $sw['sw_stage']= 3 ;
                                    $where_sw = ['apply_connection_id'=>$apply_connection_id,'id'=> $sws['id']];                            
                                    $this->Citizensw_water_model->updateData($sw,$where_sw);
                                    
                                    $push_sw=array();
                                    $ulb_mstr_id = $_SESSION['ulb_dtl']['ulb_mstr_id']; 
                                    $path='';
                                    $push_sw['application_stage']=12;
                                    $push_sw['status']='Application sent to Dealing Assistant';
                                    $push_sw['acknowledgment_no']=$water_conn['water_conn_dtl']['application_no'];
                                    $push_sw['service_type_id']=$sws['service_id'];
                                    $push_sw['caf_unique_no']=$sws['caf_no'];
                                    $push_sw['department_id']=$sws['department_id'];
                                    $push_sw['Swsregid']=$sws['cust_id'];
                                    $push_sw['payable_amount ']='';
                                    $push_sw['payment_validity']='';
                                    $push_sw['payment_other_details']='';
                                    $push_sw['certificate_url']=$path;
                                    $push_sw['approval_date']='';
                                    $push_sw['expire_date']='';
                                    $push_sw['licence_no']='';
                                    $push_sw['certificate_no']='';
                                    $push_sw['customer_id']=$sws['cust_id'];
                                    $post_url =getenv('single_indow_push_url');
                                    $http = getenv('single_indow_push_http');
                                    $resp = httpPostJson($post_url,$push_sw,$http);
                                    //print_var($resp);
                                    $respons_data=[];
                                    $respons_data['apply_connection_id']=$apply_connection_id;
                                    $respons_data['response_msg']= json_encode(['url'=>$http.'/'.$post_url,
                                                                                    'data'=>$push_sw]);
                                    $respons_data['tbl_single_window_id']=$sws['id'];
                                    $respons_data['emp_id']=null;
                                    $respons_data['response_status']=json_encode($resp);
                                    $this->Citizensw_water_model->insertResponse($respons_data); 
                                    // print_var($resp);
                                    // die;
                                }
                            }
                            # ---------swm_push end ----
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

    public function ViewDocument($water_conn_id_md5)
    {
        $data=(array)null;
        $data['consumer_details']=$this->WaterApplyNewConnectionModel->water_conn_details($water_conn_id_md5);
        $apply_connection_id = $data["consumer_details"]["id"];
        $data["uploaded_doc_list"]=$this->model_applicant_doc->getAllActiveDocuments($apply_connection_id);
        // applicant img & document
        $data['owner_details']=$this->WaterApplyNewConnectionModel->water_owner_details($water_conn_id_md5);
        foreach($data['owner_details'] as $key => $owner_detail)
        {
            $data['owner_details'][$key]['applicant_img_dtl'] = $this->model_applicant_doc->check_owner_img($apply_connection_id, $owner_detail['id']);
            $data['owner_details'][$key]['applicant_doc_dtl'] = $this->model_applicant_doc->check_owner_doc($apply_connection_id, $owner_detail['id']);
        }

        return view('water/water_connection/ViewWaterDocument', $data);
    }

    public function WaterdocumentUpload($water_conn_id_md5)
	{ 
		$data=(array)null;
		$session = session();
		$ulb_mstr = $session->get("ulb_dtl");
		$emp_details = $session->get("emp_details");
        $data['connection_dtls']=$this->WaterApplyNewConnectionModel->water_conn_details($water_conn_id_md5);
        $apply_connection_id = $data["connection_dtls"]["id"];
        //print_var( $data['connection_dtls']);die;
		//If Application not updated then update first
		if($data["connection_dtls"]["elec_category"]==null || $data["connection_dtls"]["elec_category"]=="")
		{
			flashToast("message", "Application is not updated, So, Please update application first");
			return $this->response->redirect(base_url('WaterUpdateApplicationNew/index/'.$water_conn_id_md5));
		}
		if($this->request->getMethod()=='post' && $data['connection_dtls']['doc_verify_status']!=1 )
		{
			$ulb_dtl = $this->model_ulb_mstr->getulb_list($ulb_mstr["ulb_mstr_id"]);
			$inputs = arrFilterSanitizeString($this->request->getVar());
            
			if(isset($inputs['btn_owner_doc_upload']))
			{
				$rules=[
					'applicant_image_file'=>'uploaded[applicant_image_file]|max_size[applicant_image_file,5120]|ext_in[applicant_image_file,png,jpg,jpeg]',
					'applicant_doc_file'=>'uploaded[applicant_doc_file]|max_size[applicant_doc_file,5120]|ext_in[applicant_doc_file,png,jpg,jpeg,pdf]',
				];
				
				if ($this->validate($rules))
				{ 
					$applicant_image_file = $this->request->getFile('applicant_image_file');
					$applicant_doc_file = $this->request->getFile('applicant_doc_file');
					if ($applicant_image_file->IsValid() && !$applicant_image_file->hasMoved() && $applicant_doc_file->IsValid() && !$applicant_doc_file->hasMoved())
					{
						try
						{
							$this->db->transBegin();
                            $input = [
                                'applicant_detail_id' => $this->request->getVar('owner_dtl_id'),
                                'owner_doc_mstr_id' => $this->request->getVar('owner_doc_mstr_id'),
                                'apply_connection_id' => $apply_connection_id,
                                'emp_details_id' => $emp_details['id'],
                                'created_on' => "NOW()",
                            ];
							
							if($wtr_doc_dtl_id = $this->model_applicant_doc->check_doc_exist($apply_connection_id, "CONSUMER_PHOTO",array('applicant_detail_id'=>$input['applicant_detail_id'])))
							{
								$delete_path = WRITEPATH.'uploads/'.$wtr_doc_dtl_id['doc_path'];
								if(file_exists($delete_path) && $wtr_doc_dtl_id['doc_path']!=null)
                                // @unlink($delete_path);
                                deleteFile($delete_path);
                                
								$newFileName = md5($wtr_doc_dtl_id['id']);
								$file_ext = $applicant_image_file->getExtension();

								$path = $ulb_dtl['city']."/"."water_doc_dtl/owner_document/";
								$owner_img_path = $path."/".$newFileName.'.'.$file_ext;
                                
								if($applicant_image_file->move(WRITEPATH.'uploads/'.$path.'/', $newFileName.'.'.$file_ext))
								{
                                    $this->model_applicant_doc->updatedocpathById($wtr_doc_dtl_id['id'], $owner_img_path);
                                    flashToast("message", "Owner Image Updated successfully");
								}
							}
							else
							{
                                $wtr_doc_dtl_id = $this->model_applicant_doc->insertOwnerImgData($input);
								$newFileName = md5($wtr_doc_dtl_id);
								$file_ext = $applicant_image_file->getExtension();

								$path = $ulb_dtl['city']."/"."water_doc_dtl/owner_document/";
								$owner_img_path = $path."/".$newFileName.'.'.$file_ext;
								if($applicant_image_file->move(WRITEPATH.'uploads/'.$path.'/',$newFileName.'.'.$file_ext))
                                {
									$this->model_applicant_doc->updatedocpathById($wtr_doc_dtl_id, $owner_img_path);
                                    flashToast("message", "Owner Image saved successfully");
								}
							}
							
							if($wtr_doc_dtl_id = $this->model_applicant_doc->check_doc_exist($apply_connection_id, "ID Proof",array('applicant_detail_id'=>$input['applicant_detail_id'])))
							{
								$delete_path = WRITEPATH.'uploads/'.$wtr_doc_dtl_id['doc_path'];
                                if(file_exists($delete_path) && $wtr_doc_dtl_id['doc_path']!=null)
								// @unlink($delete_path);
                                deleteFile($delete_path);

								$newFileName = md5($wtr_doc_dtl_id['id']);
								$file_ext = $applicant_doc_file->getExtension();

								$path = $ulb_dtl['city']."/"."water_doc_dtl";
								$owner_doc_path = $path."/".$newFileName.'.'.$file_ext;
								if($applicant_doc_file->move(WRITEPATH.'uploads/'.$path.'/', $newFileName.'.'.$file_ext))
                                {
                                    $this->model_applicant_doc->updatedocpathById($wtr_doc_dtl_id["id"], $owner_doc_path, $input['owner_doc_mstr_id']);
								}
							}
							else
							{
                                $wtr_doc_dtl_id = $this->model_applicant_doc->insertOwnerData($input);
								$newFileName = md5($wtr_doc_dtl_id);
								$file_ext = $applicant_doc_file->getExtension();

								$path = $ulb_dtl['city']."/"."water_doc_dtl";
								$owner_doc_path = $path."/".$newFileName.'.'.$file_ext;
								if($applicant_doc_file->move(WRITEPATH.'uploads/'.$path.'/',$newFileName.'.'.$file_ext))
                                {
                                    $this->model_applicant_doc->updatedocpathById($wtr_doc_dtl_id, $owner_doc_path, $input['owner_doc_mstr_id']);
								}
							}
							
							if ($this->db->transStatus() === FALSE)
							{
								$this->db->transRollback();
								flashToast("message", "Oops, Owner document not uploaded.");
							}
							else
							{
								$this->db->transCommit();
								flashToast("message", "Owner Document uploaded successfully.");
							}
						}
						catch (Exception $e)
						{
							
							flashToast("message", $e->getMessage());
						}
					}
					else
					{
						flashToast("message", "something errors in owner details.");
					}
				}
				else
				{  
					
					$errMsg = $this->validator->listErrors();
					flashToast("message", $errMsg);
                    
				}
			}

			if (isset($inputs['btn_upload']))
			{
				$rules = [
					'upld_doc_path'=>'uploaded[upld_doc_path]|max_size[upld_doc_path,5120]|ext_in[upld_doc_path,pdf]',
					
				];
				if($this->validate($rules))
				{
					$upld_doc_path = $this->request->getFile('upld_doc_path');
					if ($upld_doc_path->IsValid()  && !$upld_doc_path->hasMoved())
					{
						try
						{
							$this->db->transBegin();
                            $input = [
                                    'apply_connection_id' => $apply_connection_id,
                                    'doc_for' => $inputs['other_doc'],
                                    'document_id' => $inputs['doc_mstr_id'],
                                    'emp_details_id' => $emp_details['id'],
                                    'created_on' => "NOW()",
                                ];

							if($wtr_doc_dtl_id = $this->model_applicant_doc->check_doc_exist($apply_connection_id, $inputs['other_doc']))
							{
								$delete_path = WRITEPATH.'uploads/'.$wtr_doc_dtl_id['doc_path'];
                                if(file_exists($delete_path) && $wtr_doc_dtl_id['doc_path']!=null)
                                // @unlink($delete_path);
                                deleteFile($delete_path);

								$newFileName = md5($wtr_doc_dtl_id['id']);
								$file_ext = $upld_doc_path->getExtension();

								$path = $ulb_dtl['city']."/"."water_doc_dtl";
								$upld_doc_path->move(WRITEPATH.'uploads/'.$path.'/',$newFileName.'.'.$file_ext);
								$upld_doc_path_save = $path."/".$newFileName.'.'.$file_ext;
								$this->model_applicant_doc->updatedocpathById($wtr_doc_dtl_id["id"], $upld_doc_path_save, $input['document_id']);
							
							}
							else if ($wtr_doc_dtl_id = $this->model_applicant_doc->insertData($input))
							{
								$newFileName = md5($wtr_doc_dtl_id);
								$file_ext = $upld_doc_path->getExtension();
								$path = $ulb_dtl['city']."/"."water_doc_dtl";

								$upld_doc_path->move(WRITEPATH.'uploads/'.$path.'/',$newFileName.'.'.$file_ext);
								$upld_doc_path_save = $path."/".$newFileName.'.'.$file_ext;
								$this->model_applicant_doc->updatedocpathById($wtr_doc_dtl_id, $upld_doc_path_save, $input['document_id']);
							}

							if ($this->db->transStatus() === FALSE)
							{
								$this->db->transRollback();
								flashToast("message", "Oops, Document not uploaded.");
							}
							else
							{
								$this->db->transCommit();
								flashToast("message", "Document uploaded successfully.");
							}
						}
						catch (Exception $e)
						{                            
							flashToast("message", $e->getMessage());
						}
					}
				}
				else
				{
					
					$errMsg = $this->validator->listErrors();
					flashToast("message", $errMsg);
				}
			}
		}
        elseif($this->request->getMethod()=='post' && $data['connection_dtls']['doc_verify_status']==1)
        { 
            
            flashToast("message", "Document Already Verified");
        }
        
		$data['owner_details']=$this->WaterApplyNewConnectionModel->water_owner_details($water_conn_id_md5);
       
		// applicant img & document
        foreach($data['owner_details'] as $key => $owner_detail)
        {
            $data['owner_details'][$key]['applicant_img_dtl'] = $this->model_applicant_doc->check_owner_img($apply_connection_id, $owner_detail['id']);
            $data['owner_details'][$key]['applicant_doc_dtl'] = $this->model_applicant_doc->check_owner_doc($apply_connection_id, $owner_detail['id']);
        }
        
        
		$data['owner_doc_list'] = $this->model_document_mstr->getDocumentList('ID Proof');
		$data['water_doc_list'] = $this->HaveToUploadDoc($data['connection_dtls']);
        
		$data["uploaded_doc_list"]=$this->model_applicant_doc->getAllActiveDocuments($apply_connection_id);
		# print_var($data['connection_dtls']);
		$data["ulb_mstr_id"]=$ulb_mstr["ulb_mstr_id"];
        $data["apply_connection_id"] = $data["connection_dtls"]["id"];  
        // echo"<pre>";print_r($data['owner_details']);echo"</pre>";
        $data["everyDocUploaded"]="";
		return view('water/water_connection/WaterdocumentUpload', $data);
	}

    public function send_rmc($apply_connection_id_md5)
    {
        $data=(array)null;
        $session = session();
		$emp_details = $session->get("emp_details");
        $data = $this->WaterApplyNewConnectionModel->water_conn_details($apply_connection_id_md5);
        
        $sataus = null;
        if(isset($data['doc_verify_status']) && $data['doc_verify_status']==1)
        {
            $sataus = 1; 
        }
        //print_var($sataus);die;
        if($data["level_pending_status"]==2)
        {
            $level=$this->model_water_level_pending_dtl->getLevelBtcz($data["id"]);
            $leveldata = [
                'level_pending_dtl_id' => md5($level["id"]),
                'verification_status' => 0,
                'remarks'=> 'Application sent to officer',
                'apply_connection_id'=> $data["id"],
                'level_pending_status'=> 0,
                'doc_verify_status'=> 0,
                'doc_verify_emp_details_id'=> $emp_details["id"],

               ];
            $this->model_water_level_pending_dtl->updatelevelpendingById($leveldata);
            $this->WaterApplyNewConnectionModel->update_level_pending_status($leveldata,$sataus);
            
            flashToast("Application sent to officer");
        }
        else
        {
            $leveldata = [
                'apply_connection_id' => $data["id"],
                'sender_user_type_id' => 0,
                'receiver_user_type_id' => 12,
                'forward_date' => date('Y-m-d'),
                'forward_time' => date('H:i:s'),
                'created_on' => date('Y-m-d H:i:s'),
                'remarks' => 'Application sent from back office to dealing officer',
                'emp_details_id' => $emp_details["id"],
                'verification_status' => 0
               ];
            $data['doc_upload_stts']=$this->WaterApplyNewConnectionModel->update_doc_status($leveldata);
            $this->model_water_level_pending_dtl->insrtlevelpendingdtl($leveldata);
            flashToast("Application sent to dealing officer");
        }
        
        return $this->response->redirect(base_url('WaterApplyNewConnection/water_connection_view/'.$apply_connection_id_md5));
    }

    public function HaveToUploadDoc($data)
	{
		$return=(array)null;
		if(in_array($data["connection_through_id"], [1, 5]))	// Holding No, SAF No
		{
            $return[]=$this->model_document_mstr->getDocumentList("HOLDING PROOF");
		}

        if($data["user_id"]!=0) // Online
		{
			$return[]=$this->model_document_mstr->getDocumentList('Form(Scan Copy)');
		}
		
		$return[]=$this->model_document_mstr->getDocumentList('METER BILL');
        $return[]=$this->model_document_mstr->getDocumentList('ELECTRICITY_NEW');
        $return[]=$this->model_document_mstr->getDocumentList('Address Proof');
        $return[]=$this->model_document_mstr->getDocumentList("OTHER_DOC");
		return $return;
	}
}