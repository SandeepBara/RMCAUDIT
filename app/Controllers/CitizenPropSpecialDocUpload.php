<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\model_prop_dtl;
use App\Models\model_ulb_mstr;
use App\Models\model_saf_doc_dtl;
use App\Models\model_level_pending_dtl;
use App\Models\model_prop_owner_detail;

use CodeIgniter\Session\Session;

class CitizenPropSpecialDocUpload extends HomeController
{
    protected $db;
    protected $dbSystem;
    protected $model_ulb_mstr;
    protected $model_ward_mstr;
    protected $model_prop_dtl;
    protected $model_saf_doc_dtl;
    protected $model_level_pending_dtl;
    protected $model_prop_owner_detail;



    public function __construct()
    {
		parent::__construct();
		helper(['db_helper', 'qr_code_generator_helper','utility_helper']);
		if ($db_name = dbConfig("property")) {
			//echo $db_name;
			$this->db = db_connect($db_name);
		}
		/*if ($db_system = dbSystem()) {
			$this->dbSystem = db_connect($db_system);
		}*/

        $this->model_prop_dtl = new model_prop_dtl($this->db);
        //$this->model_ulb_mstr = new model_ulb_mstr($this->dbSystem);
        $this->model_saf_doc_dtl = new model_saf_doc_dtl($this->db);
        $this->model_level_pending_dtl = new model_level_pending_dtl($this->db);
        $this->model_prop_owner_detail = new model_prop_owner_detail($this->db);

    }

    function __destruct() {
        if (isset($this->db)) $this->db->close();
        if (isset($this->dbSystem)) $this->dbSystem->close();
    }
    
    public function CitizenPropSpecialDocUpload($prop_dtl_id = null)
    {
        cSetCookie("heading_title", "Consession Category Details Update");
        if ($this->request->getMethod() == 'post') {

            $prop_dtl_id_raw['id'] = $this->model_prop_dtl->getIdFromMd5Id($prop_dtl_id);

            // print_var($data);
            // die;
            $ulb_mstr_id = 1;
            $uploaded_emp_dtl_id = 0;
            $sender_user_type_id = 0;

            $prop_owner_details_id = $this->request->getvar('prop_owner_details_id');

            $gender_status = $this->request->getvar('gender_status');
            $dob_status = $this->request->getvar('dob_status');
            $handicapped_status = $this->request->getvar('handicapped_status');
            $armed_status = $this->request->getvar('armed_status');
            // die;
            // $transgender_doc_status = $this->request->getvar('transgender_doc_status');


            $ulb_dtl = getUlbDtl();
           
                //direct upload
                $gender_type = $this->request->getVar('gender_type');
                $dob_type = $this->request->getVar('dob_input');
                $special_type = $this->request->getVar('handicapped_radio');
                $armed_type = $this->request->getVar('armed_radio');
               
                // if($special_type=='yes'){
                //     $special_type=true;
                // }else{
                //     $special_type='no';
                // }
                // if($armed_type=='yes'){
                //     $armed_type=true;
                // }else{
                //     $armed_type='no';
                // }
                
                $data_input=[
                    "gender"=>$gender_type,
                    "dob"=>$dob_type,
                    "is_specially_abled"=>$special_type,
                    "is_armed_force"=>$armed_type,
                ];
                // die;
                $this->model_prop_owner_detail->UpdateOwnerSpecialDataFull($prop_owner_details_id, $data_input);
                // flashToast('message', 'Data Saved Successfully !');

                //direct upload



            if ($gender_status == 1) {

                $rules = [

                    'gender_doc' => 'uploaded[gender_doc]|max_size[gender_doc,5120]|ext_in[gender_doc,pdf]'

                ];

                if ($this->validate($rules)) {
                    $gender_type = $this->request->getVar('gender_type');
                    $prev_doc_status_id = $this->request->getVar('prev_prop_special_gender_id');
                    $gender_doc_mstr_id = $this->model_saf_doc_dtl->get_doc_mstr_id('gender_document');
                    //2 insert to special_doc_tbl
                    $input = array(null);
                    $input = [
                        "prop_dtl_id" => $prop_dtl_id_raw['id'][0]['id'],
                        "prop_owner_details_id" => $prop_owner_details_id,
                        "doc_mstr_id" => $gender_doc_mstr_id['id'],
                        "other_doc" => "gender_document",
                        "uploaded_emp_dtl_id" => $uploaded_emp_dtl_id,
                        "created_on" => "now()",
                        "status" => 1,
                        "verify_status" => 0,
                        "gender" => $gender_type,
                        "dob" => "",
                        "is_specially_abled" => "",
                        "is_armed_force" => ""              
                    ];
                    //if previous doc present then update status=0 of privious in prop_doc_special_dtl
                    if ($prev_doc_status_id != '') {
                        // $check_prev_data_present = $this->model_prop_dtl->getPropDocPrevData($prev_doc_status_id);
                        // if (count($check_prev_data_present) > 0) {
                        //     //make status 0
                        $this->model_prop_dtl->updateSpecialDocStatus($prev_doc_status_id);
                        // }
                    }
                    // die;
                    if ($prop_special_doc_insert = $this->model_prop_dtl->insertSpecialDoc($input)) //id return
                    {
                        $input_level = [
                            "prop_dtl_id" => $prop_dtl_id_raw['id'][0]['id'],
                            "sender_user_type_id" => $sender_user_type_id,
                            "receiver_user_type_id" => 6,
                            "forward_date_time" => "now()",
                            "created_on" => "now()",
                            "sender_emp_details_id" => $uploaded_emp_dtl_id,
                            "status" => 1

                        ];
                        if ($level_doc_verify_insert = $this->model_prop_dtl->insertLevelDocVerify($input_level)) {
                            
                            $female_doc_file = $this->request->getFile('gender_doc');
                            $newFileName = md5($prop_special_doc_insert);
                            $file_ext = $female_doc_file->getExtension();

                            $path = $ulb_dtl['city'] . "/" . "prop_doc_dtl";
                            $female_doc_path = $path . "/" . $newFileName . '.' . $file_ext;
                            if ($female_doc_file->move(WRITEPATH . 'uploads/' . $path . '/', $newFileName . '.' . $file_ext)) {

                                $inputUpdate = [
                                    "level_doc_verify_dtl_id" => $level_doc_verify_insert,
                                    "doc_path" => $female_doc_path,
                                    "prop_special_doc_tbl_id" => $prop_special_doc_insert,
                                ];
                                $this->model_prop_dtl->updatePropDocDtl($inputUpdate);
                                flashToast('message', 'Document Uploaded Successfully !');
                            }
                        }
                    }
                }
            }

            //dob
            if ($dob_status == 1) {

                $rules = [

                    'dob_doc' => 'uploaded[dob_doc]|max_size[dob_doc,5120]|ext_in[dob_doc,pdf]'

                ];

                if ($this->validate($rules)) {
                     $gender_type = $this->request->getVar('dob_input');
                    
                    $prev_doc_status_id = $this->request->getVar('prev_prop_special_dob_id');

                    $gender_doc_mstr_id = $this->model_saf_doc_dtl->get_doc_mstr_id('dob_document');
                    //2 insert to special_doc_tbl
                    $input = array(null);
                    $input = [
                        "prop_dtl_id" => $prop_dtl_id_raw['id'][0]['id'],
                        "prop_owner_details_id" => $prop_owner_details_id,
                        "doc_mstr_id" => $gender_doc_mstr_id['id'],
                        "other_doc" => "dob_document",
                        "uploaded_emp_dtl_id" => $uploaded_emp_dtl_id,
                        "created_on" => "now()",
                        "status" => 1,
                        "verify_status" => 0,
                        "gender" => "",
                        "dob" => $gender_type,
                        "is_specially_abled" => "",
                        "is_armed_force" => ""
                    ];

                    if ($prev_doc_status_id != '') {
                        $this->model_prop_dtl->updateSpecialDocStatus($prev_doc_status_id);
                    }
                    // die;
                    if ($prop_special_doc_insert = $this->model_prop_dtl->insertSpecialDoc($input)) //id return
                    {
                        $input_level = [
                            "prop_dtl_id" => $prop_dtl_id_raw['id'][0]['id'],
                            "sender_user_type_id" => $sender_user_type_id,
                            "receiver_user_type_id" => 6,
                            "forward_date_time" => "now()",
                            "created_on" => "now()",
                            "sender_emp_details_id" => $uploaded_emp_dtl_id,
                            "status" => 1

                        ];
                        if ($level_doc_verify_insert = $this->model_prop_dtl->insertLevelDocVerify($input_level)) {

                            $female_doc_file = $this->request->getFile('dob_doc');
                            $newFileName = md5($prop_special_doc_insert);
                            $file_ext = $female_doc_file->getExtension();

                            $path = $ulb_dtl['city'] . "/" . "prop_doc_dtl";
                            $female_doc_path = $path . "/" . $newFileName . '.' . $file_ext;
                            if ($female_doc_file->move(WRITEPATH . 'uploads/' . $path . '/', $newFileName . '.' . $file_ext)) {

                                $inputUpdate = [
                                    "level_doc_verify_dtl_id" => $level_doc_verify_insert,
                                    "doc_path" => $female_doc_path,
                                    "prop_special_doc_tbl_id" => $prop_special_doc_insert,
                                ];
                                $this->model_prop_dtl->updatePropDocDtl($inputUpdate);
                                flashToast('message', 'Document Uploaded Successfully !');
                            }
                        }
                    }
                }
            }
            //handicapped
            if ($handicapped_status == 1) {
                $gender_type = $this->request->getVar('handicapped_radio');
                if ($gender_type == 'no') {
                    $sql_cond = "is_specially_abled='no'";
                    $this->model_prop_owner_detail->UpdateOwnerSpecialData($prop_owner_details_id, $sql_cond);
                    flashToast('message','Data Saved Successfully !');
                } else {
                    //direct update to tbl_prop_owner_detail
                    $sql_cond = "is_specially_abled='".$gender_type."'";
                    $this->model_prop_owner_detail->UpdateOwnerSpecialData($prop_owner_details_id, $sql_cond);
                    //direct upadet to

                    $rules = [

                        'handicapped_doc' => 'uploaded[handicapped_doc]|max_size[handicapped_doc,5120]|ext_in[handicapped_doc,pdf]'

                    ];
                    if ($this->validate($rules)) {

                        $prev_doc_status_id = $this->request->getVar('prev_prop_special_handicapped_id');

                        $gender_doc_mstr_id = $this->model_saf_doc_dtl->get_doc_mstr_id('handicaped_document');
                        //2 insert to special_doc_tbl
                        $input = array(null);
                        $input = [
                            "prop_dtl_id" => $prop_dtl_id_raw['id'][0]['id'],
                            "prop_owner_details_id" => $prop_owner_details_id,
                            "doc_mstr_id" => $gender_doc_mstr_id['id'],
                            "other_doc" => "handicaped_document",
                            "uploaded_emp_dtl_id" => $uploaded_emp_dtl_id,
                            "created_on" => "now()",
                            "status" => 1,
                            "verify_status" => 0,
                            "gender" => "",
                            "dob" => "",
                            "is_specially_abled" => $gender_type,
                            "is_armed_force" => ""
                        ];

                        if ($prev_doc_status_id != '') {
                            $this->model_prop_dtl->updateSpecialDocStatus($prev_doc_status_id);
                        }
                        // die;
                        if ($prop_special_doc_insert = $this->model_prop_dtl->insertSpecialDoc($input)) //id return
                        {
                            $input_level = [
                                "prop_dtl_id" => $prop_dtl_id_raw['id'][0]['id'],
                                "sender_user_type_id" => $sender_user_type_id,
                                "receiver_user_type_id" => 6,
                                "forward_date_time" => "now()",
                                "created_on" => "now()",
                                "sender_emp_details_id" => $uploaded_emp_dtl_id,
                                "status" => 1

                            ];
                            if ($level_doc_verify_insert = $this->model_prop_dtl->insertLevelDocVerify($input_level)) {

                                $female_doc_file = $this->request->getFile('handicapped_doc');
                                $newFileName = md5($prop_special_doc_insert);
                                $file_ext = $female_doc_file->getExtension();

                                $path = $ulb_dtl['city'] . "/" . "prop_doc_dtl";
                                $female_doc_path = $path . "/" . $newFileName . '.' . $file_ext;
                                if ($female_doc_file->move(WRITEPATH . 'uploads/' . $path . '/', $newFileName . '.' . $file_ext)) {

                                    $inputUpdate = [
                                        "level_doc_verify_dtl_id" => $level_doc_verify_insert,
                                        "doc_path" => $female_doc_path,
                                        "prop_special_doc_tbl_id" => $prop_special_doc_insert,
                                    ];
                                    $this->model_prop_dtl->updatePropDocDtl($inputUpdate);
                                    flashToast('message', 'Document Uploaded Successfully !');
                                }
                            }
                        }
                    }
                }
            }
            //armed force
            if ($armed_status == 1) {
                $gender_type = $this->request->getVar('armed_radio');
                if ($gender_type == 'no') {
                    $sql_cond = "is_armed_force='no'";
                    $this->model_prop_owner_detail->UpdateOwnerSpecialData($prop_owner_details_id, $sql_cond);
                    flashToast('message', 'Data Saved Successfully !');
                } else {
                    //direct update to tbl_prop_owner_detail
                    $sql_cond = "is_armed_force='".$gender_type."'";
                    $this->model_prop_owner_detail->UpdateOwnerSpecialData($prop_owner_details_id, $sql_cond);
                    //direct upadet to

                    $rules = [

                        'armed_doc' => 'uploaded[armed_doc]|max_size[armed_doc,5120]|ext_in[armed_doc,pdf]'

                    ];
                    if ($this->validate($rules)) {
                        $prev_doc_status_id = $this->request->getVar('prev_prop_special_armed_id');
                        $gender_doc_mstr_id = $this->model_saf_doc_dtl->get_doc_mstr_id('armed_force_document');
                        //2 insert to special_doc_tbl
                        $input = array(null);
                        $input = [
                            "prop_dtl_id" => $prop_dtl_id_raw['id'][0]['id'],
                            "prop_owner_details_id" => $prop_owner_details_id,
                            "doc_mstr_id" => $gender_doc_mstr_id['id'],
                            "other_doc" => "armed_force_document",
                            "uploaded_emp_dtl_id" => $uploaded_emp_dtl_id,
                            "created_on" => "now()",
                            "status" => 1,
                            "verify_status" => 0,
                            "gender" => "",
                            "dob" => "",
                            "is_specially_abled" => "",
                            "is_armed_force" => $gender_type
                        ];

                        if ($prev_doc_status_id != '') {
                            $this->model_prop_dtl->updateSpecialDocStatus($prev_doc_status_id);
                        }
                        // die;
                        if ($prop_special_doc_insert = $this->model_prop_dtl->insertSpecialDoc($input)) //id return
                        {
                            $input_level = [
                                "prop_dtl_id" => $prop_dtl_id_raw['id'][0]['id'],
                                "sender_user_type_id" => $sender_user_type_id,
                                "receiver_user_type_id" => 6,
                                "forward_date_time" => "now()",
                                "created_on" => "now()",
                                "sender_emp_details_id" => $uploaded_emp_dtl_id,
                                "status" => 1

                            ];
                            if ($level_doc_verify_insert = $this->model_prop_dtl->insertLevelDocVerify($input_level)) {

                                $female_doc_file = $this->request->getFile('armed_doc');
                                $newFileName = md5($prop_special_doc_insert);
                                $file_ext = $female_doc_file->getExtension();

                                $path = $ulb_dtl['city'] . "/" . "prop_doc_dtl";
                                $female_doc_path = $path . "/" . $newFileName . '.' . $file_ext;
                                if ($female_doc_file->move(WRITEPATH . 'uploads/' . $path . '/', $newFileName . '.' . $file_ext)) {

                                    $inputUpdate = [
                                        "level_doc_verify_dtl_id" => $level_doc_verify_insert,
                                        "doc_path" => $female_doc_path,
                                        "prop_special_doc_tbl_id" => $prop_special_doc_insert,
                                    ];
                                    $this->model_prop_dtl->updatePropDocDtl($inputUpdate);
                                    flashToast('message', 'Force Document Uploaded Successfully !');
                                }
                            }
                        }
                    }
                }
            }
        }


        if ($prop_dtl_id != null) {
            $data['owner_list'] = $this->model_prop_dtl->getOwnerDetailById($prop_dtl_id);
            $data['prop_dtl_basic'] = $this->model_prop_dtl->getPropDtlByMD5PropDtlId($prop_dtl_id);
        }

        //   print_var($data);
        //   return;
        return view('property/citizen_prop_special_doc_update2', $data);
    }
    public function CitizenPropDetailsWithoutDocUpload($prop_dtl_id = null)
    {

        if ($this->request->getMethod() == 'post') {

            $prop_dtl_id_raw['id'] = $this->model_prop_dtl->getIdFromMd5Id($prop_dtl_id);

            $prop_owner_details_id = $this->request->getvar('prop_owner_details_id');
            $gender_type = $this->request->getVar('gender_radio');
            $dob_type = $this->request->getVar('dob_input');
            $special_type = $this->request->getVar('handicapped_radio');
            $armed_type = $this->request->getVar('armed_radio');
           
            if($special_type=='yes'){
                $special_type=true;
            }else{
                $special_type='no';
            }
            if($armed_type=='yes'){
                $armed_type=true;
            }else{
                $armed_type='no';
            }
            
            $data_input=[
                "gender"=>$gender_type,
                "dob"=>$dob_type,
                "is_specially_abled"=>$special_type,
                "is_armed_force"=>$armed_type,
            ];
            // die;
            $this->model_prop_owner_detail->UpdateOwnerSpecialDataFull($prop_owner_details_id, $data_input);
           
            flashToast('message', 'Data Saved Successfully !');
           
           
           
        }


        if ($prop_dtl_id != null) {
            $data['owner_list'] = $this->model_prop_dtl->getOwnerDetailById($prop_dtl_id);
            $data['prop_dtl_basic'] = $this->model_prop_dtl->getPropDtlByMD5PropDtlId($prop_dtl_id);
        }
        
        return view('property/prop_special_doc_update', $data);
    }

    public function ajaxgetPropOwnerData()
    {



        if ($this->request->getMethod() == 'post') {
            // $prop_dtl_id = $this->request->getVar('ward_mstr_id');
            $ownerId = $_POST['ownerId'];
            $onwerSpecialDoc = $this->model_prop_dtl->getSpecialDocData($ownerId);
            $ownerData = $this->model_prop_dtl->getOwnerDetailByOwnerId($ownerId);
            return json_encode(["ownerData" => $ownerData, "ownerSpecialData" =>  $onwerSpecialDoc]);
        }
    }
}
