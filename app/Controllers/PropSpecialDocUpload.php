<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\model_prop_dtl;
use App\Models\model_ulb_mstr;
use App\Models\model_saf_doc_dtl;
use App\Models\model_level_pending_dtl;
use App\Models\model_prop_owner_detail;

use CodeIgniter\Session\Session;

class PropSpecialDocUpload extends AlphaController
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
        helper(['url', 'db_helper', 'validation_helper', 'validate_saf_helper']);
        if ($db_name = dbConfig("property")) {
            $this->db = db_connect($db_name);
        }
        if ($db_name = dbSystem()) {
            $this->dbSystem = db_connect($db_name);
        }

        $this->model_prop_dtl = new model_prop_dtl($this->db);
        $this->model_ulb_mstr = new model_ulb_mstr($this->dbSystem);
        $this->model_saf_doc_dtl = new model_saf_doc_dtl($this->db);
        $this->model_level_pending_dtl = new model_level_pending_dtl($this->db);
        $this->model_prop_owner_detail = new model_prop_owner_detail($this->db);
    }

    public function index()
    {
        return view('property/prop_special_doc_update');
    }

    public function PropSpecialDocUpload($prop_dtl_id = null)
    {
      
        if ($this->request->getMethod() == 'post') {

            $prop_dtl_id_raw['id'] = $this->model_prop_dtl->getIdFromMd5Id($prop_dtl_id);

            // print_var($data);
            // die;
            $ulb_mstr_id = $_SESSION['ulb_dtl']['ulb_mstr_id'];
            $uploaded_emp_dtl_id = $_SESSION['emp_details']['user_mstr_id'];
            $sender_user_type_id = $_SESSION['emp_details']['user_type_mstr_id'];

            $prop_owner_details_id = $this->request->getvar('prop_owner_details_id');

            $gender_status = $this->request->getvar('gender_status');
            $dob_status = $this->request->getvar('dob_status');
            $handicapped_status = $this->request->getvar('handicapped_status');
            $armed_status = $this->request->getvar('armed_status');
            // $transgender_doc_status = $this->request->getvar('transgender_doc_status');


            $ulb_dtl = $this->model_ulb_mstr->getulb_list($ulb_mstr_id);
            // $inputs = arrFilterSanitizeString($this->request->getVar());
            // $rules = [
            //     'female_doc' => 'uploaded[female_doc]|max_size[female_doc,5120]|ext_in[female_doc,pdf]',
            //     'senior_doc' => 'uploaded[senior_doc]|max_size[senior_doc,5120]|ext_in[senior_doc,pdf]',
            //     'is_special_doc' => 'uploaded[is_special_doc]|max_size[is_special_doc,5120]|ext_in[is_special_doc,pdf]',
            //     'armed_force_doc' => 'uploaded[armed_force_doc]|max_size[armed_force_doc,5120]|ext_in[armed_force_doc,pdf]',
            //     'transgender_doc_status' => 'uploaded[transgender_doc_status]|max_size[transgender_doc_status,5120]|ext_in[transgender_doc_status,pdf]',

            // ];




            if ($gender_status == 1) {
                $gender_type = $this->request->getVar('gender_type');
                $sql_cond = "gender='".$gender_type."'";
                $this->model_prop_owner_detail->UpdateOwnerSpecialData($prop_owner_details_id, $sql_cond);
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
                $dob_value = $this->request->getVar('dob_input');
                $sql_cond = "dob='".$dob_value."'";
                $this->model_prop_owner_detail->UpdateOwnerSpecialData($prop_owner_details_id, $sql_cond);
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
                    flashToast('message', 'Data Saved Successfully !');
                } else {
                    $sql_cond = "is_specially_abled='".$gender_type."'";
                    $this->model_prop_owner_detail->UpdateOwnerSpecialData($prop_owner_details_id, $sql_cond);
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
                    $sql_cond = "is_armed_force='".$gender_type."'";
                    $this->model_prop_owner_detail->UpdateOwnerSpecialData($prop_owner_details_id, $sql_cond);
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
        return view('property/prop_special_doc_update2', $data);
    }
    //direct hit to tbl_prop_owner_details also insert into level
    public function PropDetailsWithoutDocUpload($prop_dtl_id = null)
    {


        if ($this->request->getMethod() == 'post') {

            $prop_dtl_id_raw['id'] = $this->model_prop_dtl->getIdFromMd5Id($prop_dtl_id);

            // print_var($data);
            // die;
            $ulb_mstr_id = $_SESSION['ulb_dtl']['ulb_mstr_id'];
            $uploaded_emp_dtl_id = 0;
            $sender_user_type_id = $_SESSION['emp_details']['user_type_mstr_id'];

            $prop_owner_details_id = $this->request->getvar('prop_owner_details_id');


            // die;
            // $transgender_doc_status = $this->request->getvar('transgender_doc_status');


            $ulb_dtl = $this->model_ulb_mstr->getulb_list($ulb_mstr_id);

            //direct upload
            $gender_type = $this->request->getVar('gender_radio');
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

            $data_input = [
                "gender" => $gender_type,
                "dob" => $dob_type,
                "is_specially_abled" => $special_type,
                "is_armed_force" => $armed_type,
            ];
            // die;
            $this->model_prop_owner_detail->UpdateOwnerSpecialDataFull($prop_owner_details_id, $data_input);
            // flashToast('message', 'Data Saved Successfully !');

            //direct upload




            $gender_type = $this->request->getVar('gender_radio');
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
                    $inputUpdate = [
                        "level_doc_verify_dtl_id" => $level_doc_verify_insert,
                        // "doc_path" => $female_doc_path,
                        "prop_special_doc_tbl_id" => $prop_special_doc_insert,
                    ];
                    $this->model_prop_dtl->updatePropDocDtl($inputUpdate);
                   
                    flashToast('message', 'Date saved Successfully !');
                }
            }



            //dob
            $gender_type = $this->request->getVar('dob_input');
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
                    $inputUpdate = [
                        "level_doc_verify_dtl_id" => $level_doc_verify_insert,
                        // "doc_path" => $female_doc_path,
                        "prop_special_doc_tbl_id" => $prop_special_doc_insert,
                    ];
                    $this->model_prop_dtl->updatePropDocDtl($inputUpdate);
                    flashToast('message', 'Data Saved Successfully !');
                }
            }


            //handicapped

            $gender_type = $this->request->getVar('handicapped_radio');
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
                        $inputUpdate = [
                            "level_doc_verify_dtl_id" => $level_doc_verify_insert,
                            // "doc_path" => $female_doc_path,
                            "prop_special_doc_tbl_id" => $prop_special_doc_insert,
                        ];
                        $this->model_prop_dtl->updatePropDocDtl($inputUpdate);
                        flashToast('message', 'Data Saved Successfully !');
                    }
                }
            

            //armed force

            $gender_type = $this->request->getVar('armed_radio');
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
                        $inputUpdate = [
                            "level_doc_verify_dtl_id" => $level_doc_verify_insert,
                            // "doc_path" => $female_doc_path,
                            "prop_special_doc_tbl_id" => $prop_special_doc_insert,
                        ];
                        $this->model_prop_dtl->updatePropDocDtl($inputUpdate);
                        flashToast('message', 'Data Saved Successfully !');
                    }
                }
            
        }


        if ($prop_dtl_id != null) {
            $data['owner_list'] = $this->model_prop_dtl->getOwnerDetailById($prop_dtl_id);
            $data['prop_dtl_basic'] = $this->model_prop_dtl->getPropDtlByMD5PropDtlId($prop_dtl_id);
        }

        //   print_var($data);
        //   return;

        return view('property/prop_special_doc_update', $data);
    }
    public function PropDetailsWithoutDocWithLevel($prop_dtl_id = null)
    {

        if ($this->request->getMethod() == 'post') {

            $prop_dtl_id_raw['id'] = $this->model_prop_dtl->getIdFromMd5Id($prop_dtl_id);

            $prop_owner_details_id = $this->request->getvar('prop_owner_details_id');
            $gender_type = $this->request->getVar('gender_radio');
            $dob_type = $this->request->getVar('dob_input');
            $special_type = $this->request->getVar('handicapped_radio');
            $armed_type = $this->request->getVar('armed_radio');

            if ($special_type == 'yes') {
                $special_type = true;
            } else {
                $special_type = 'no';
            }
            if ($armed_type == 'yes') {
                $armed_type = true;
            } else {
                $armed_type = 'no';
            }

            $data_input = [
                "gender" => $gender_type,
                "dob" => $dob_type,
                "is_specially_abled" => $special_type,
                "is_armed_force" => $armed_type,
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
