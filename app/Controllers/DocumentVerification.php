<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\model_view_saf_receive_list;
use App\Models\model_ward_mstr;
use App\Models\model_view_saf_doc_dtl;
use App\Models\model_level_pending_dtl;
use App\Models\model_emp_dtl_permission;
use App\Models\model_saf_owner_detail;
use App\Models\model_saf_doc_dtl;
use App\Models\model_saf_dtl;
use App\Models\model_saf_memo_dtl;
use App\Models\model_saf_floor_details;
use App\Models\model_prop_dtl;
use App\Models\model_prop_owner_detail;
use App\Models\model_prop_floor_details;
use App\Models\model_saf_tax;
use App\Models\model_prop_tax;
use App\Models\model_saf_demand;
use App\Models\model_prop_demand;
use App\Models\model_transaction;
use App\Models\model_fy_mstr;
use App\Models\model_prop_type_mstr;
use App\Models\model_road_type_mstr;
use App\Models\model_view_saf_floor_details;
use App\Models\model_field_verification_dtl;
use App\Models\model_field_verification_floor_details;
use App\Models\model_collection;
use App\Models\model_ulb_mstr;
use App\Models\model_view_ward_permission;
use App\Models\model_user_type_mstr;
use App\Models\model_view_saf_dtl;
use App\Models\model_datatable;
use App\Controllers\Reports\PropReports;
use Exception;

class DocumentVerification extends AlphaController
{
    protected $db;
    protected $dbSystem;
    protected $model_ulb_mstr;
    protected $model_ward_mstr;
    protected $model_view_ward_permission;
    protected $model_emp_dtl_permission;
    protected $model_fy_mstr;
    protected $model_user_type_mstr;
    protected $model_view_saf_receive_list;
    protected $model_view_saf_doc_dtl;
    protected $model_level_pending_dtl;
    protected $model_saf_owner_detail;
    protected $model_saf_doc_dtl;
    protected $model_saf_dtl;
    protected $model_saf_memo_dtl;
    protected $model_saf_floor_details;
    protected $model_prop_dtl;
    protected $model_prop_owner_detail;
    protected $model_prop_floor_details;
    protected $model_saf_tax;
    protected $model_prop_tax;
    protected $model_saf_demand;
    protected $model_prop_demand;
    protected $model_transaction;
    protected $model_prop_type_mstr;
    protected $model_road_type_mstr;
    protected $model_view_saf_floor_details;
    protected $model_field_verification_dtl;
    protected $model_field_verification_floor_details;
    protected $model_collection;
    protected $model_view_saf_dtl;
    protected $model_datatable;
    protected $PropReports;

    public function __construct()
    {
        Parent::__construct();
        helper(['db_helper', 'qr_code_generator_helper', 'form', 'utility_helper']);
        if ($db_name = dbConfig("property")) {
            $this->db = db_connect($db_name);
        }
        if ($db_system = dbSystem()) {
            $this->dbSystem = db_connect($db_system);
        }
        $this->PropReports = new PropReports();
        $this->model_ulb_mstr = new model_ulb_mstr($this->dbSystem);
        $this->model_ward_mstr = new model_ward_mstr($this->dbSystem);
        $this->model_view_ward_permission = new model_view_ward_permission($this->dbSystem);
        $this->model_emp_dtl_permission = new model_emp_dtl_permission($this->dbSystem);
        $this->model_fy_mstr = new model_fy_mstr($this->dbSystem);
        $this->model_user_type_mstr = new model_user_type_mstr($this->dbSystem);
        $this->model_view_saf_receive_list = new model_view_saf_receive_list($this->db);
        $this->model_view_saf_doc_dtl = new model_view_saf_doc_dtl($this->db);
        $this->model_level_pending_dtl = new model_level_pending_dtl($this->db);
        $this->model_saf_owner_detail = new model_saf_owner_detail($this->db);
        $this->model_saf_doc_dtl = new model_saf_doc_dtl($this->db);
        $this->model_saf_dtl = new model_saf_dtl($this->db);
        $this->model_saf_memo_dtl = new model_saf_memo_dtl($this->db);
        $this->model_saf_floor_details = new model_saf_floor_details($this->db);
        $this->model_prop_dtl = new model_prop_dtl($this->db);
        $this->model_prop_owner_detail = new model_prop_owner_detail($this->db);
        $this->model_prop_floor_details = new model_prop_floor_details($this->db);
        $this->model_saf_tax = new model_saf_tax($this->db);
        $this->model_prop_tax = new model_prop_tax($this->db);
        $this->model_saf_demand = new model_saf_demand($this->db);
        $this->model_prop_demand = new model_prop_demand($this->db);
        $this->model_transaction = new model_transaction($this->db);
        $this->model_prop_type_mstr = new model_prop_type_mstr($this->db);
        $this->model_road_type_mstr = new model_road_type_mstr($this->db);
        $this->model_view_saf_floor_details = new model_view_saf_floor_details($this->db);
        $this->model_field_verification_dtl = new model_field_verification_dtl($this->db);
        $this->model_field_verification_floor_details = new model_field_verification_floor_details($this->db);
        $this->model_collection = new model_collection($this->db);
        $this->model_view_saf_dtl = new model_view_saf_dtl($this->db);
        $this->model_datatable = new model_datatable($this->db);
    }

    function __destruct() {
		$this->db->close();
		$this->dbSystem->close();
	}

    public function index()
    {
        $data = (array)null;
        $session = session();
        $ulb_mstr = $session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $session->get("emp_details");
        $login_emp_details_id = $emp_mstr["id"];

        //Transaction Mode List
        $wardList = $this->model_view_ward_permission->getPermittedWard($login_emp_details_id);
        $data['wardList'] = $wardList;
        // print_var($wardList);
        // return;
        $receiver_emp_details_id = $emp_mstr["user_type_mstr_id"];
        $ward = array(null);

        foreach ($wardList as $key => $value) {
            if ($key == 0) {
                $ward = array($value['ward_mstr_id']);
            } else {
                array_push($ward, $value['ward_mstr_id']);
            }
        }
        //print_r($ward);

        if ($this->request->getMethod() == 'post') {
            $data['ward_mstr_id'] = $this->request->getVar('ward_mstr_id');
            $data['from_date'] = $this->request->getVar('from_date');
            $data['to_date'] = $this->request->getVar('to_date');
            $data['search_param'] = $this->request->getVar('search_param');
            $whereSearch = "";
            if ($data['search_param'] != "") {
                $whereSearch = " AND (saf_no ILIKE '%" . $data['search_param'] . "%' OR owner_name ILIKE '%" . $data['search_param'] . "%' OR mobile_no='" . $data['search_param'] . "') ";
            }

            if ($data['ward_mstr_id'] != "") {
                $where = "ward_mstr_id=" . $data['ward_mstr_id'] . " AND date(created_on) >='" . $data['from_date'] . "'
				AND date(created_on) <='" . $data['to_date'] . "' AND receiver_user_type_id='" . $receiver_emp_details_id . "'
				AND sender_user_type_id='11' AND verification_status='0' AND doc_upload_status='1' AND status='1' " . $whereSearch . " ORDER BY id DESC";
            } else {
                $where = "ward_mstr_id in (" . implode(",", $ward) . ") AND date(created_on) >='" . $data['from_date'] . "'
				AND date(created_on) <='" . $data['to_date'] . "' AND receiver_user_type_id='" . $receiver_emp_details_id . "'
				AND sender_user_type_id='11' AND verification_status='0' AND doc_upload_status='1' AND status='1' " . $whereSearch . " ORDER BY id DESC";
            }
            $session->set('input_post', [
                'ward_mstr_id' => $this->request->getVar('ward_mstr_id'),
                'from_date' => $this->request->getVar('from_date'),
                'to_date' => $this->request->getVar('to_date'),
                'search_param' => $this->request->getVar('search_param')
            ]);
        } else {
            $data['from_date'] = date('Y-m-d');
            $data['to_date'] = date('Y-m-d');
            $where = "ward_mstr_id in (" . implode(",", $ward) . ") AND date(created_on) >='" . $data['from_date'] . "'
			AND date(created_on) <='" . $data['to_date'] . "' AND receiver_user_type_id='" . $receiver_emp_details_id . "'
			AND sender_user_type_id='11' AND verification_status='0' AND doc_upload_status='1' AND status='1' ORDER BY id DESC";
        }

        $session->set('where', $where);
        return $this->response->redirect(base_url('DocumentVerification/inbox_list/'));
    }

    public function inbox_list()
    {
        $session = session();
        $ulb_mstr = getUlbDtl();
        $emp_mstr = $session->get("emp_details");
        $receiver_emp_details_id = $emp_mstr["user_type_mstr_id"];
        $login_emp_details_id = $emp_mstr["id"];

        $data = arrFilterSanitizeString($this->request->getVar());
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];

        $data['wardList'] = $this->model_view_ward_permission->getPermittedWard($login_emp_details_id);
        $permittedWard = array_column($data['wardList'], 'ward_mstr_id');
        $whereWard = " AND tbl_saf_dtl.ward_mstr_id IN (".implode(',', $permittedWard).")";
        $wherePropType = "";
        $whereAssessmentType = "";
        $whereSearchPrm = "";
        if (isset($data["ward_mstr_id"]) && isset($data["search_param"]) && isset($data["prop_type_mstr_id"]) && isset($data["assessment_type"])) {
            if ($data["assessment_type"]!="") {
                $whereAssessmentType = " AND tbl_saf_dtl.assessment_type='".$data["assessment_type"]."'";
            }
            if ($data["prop_type_mstr_id"]!="") {
                $wherePropType = " AND tbl_saf_dtl.prop_type_mstr_id IN (".$data["prop_type_mstr_id"].")";
            }
            if ($data["ward_mstr_id"]!="") {
                $whereWard = " AND tbl_saf_dtl.ward_mstr_id IN (".$data["ward_mstr_id"].")";
            }
            if ($data["search_param"]!="") {
                $whereSearchPrm = " AND (tbl_saf_dtl.saf_no ILIKE '%".$data["search_param"]."%'
                                        OR owner_dtl.owner_name ILIKE '%".$data["search_param"]."%'
                                        OR owner_dtl.mobile_no ILIKE '%".$data["search_param"]."%')";
            }
        }

        $sql = "SELECT
                    tbl_level_pending_dtl.id,
                    tbl_level_pending_dtl.saf_dtl_id,
                    tbl_prop_type_mstr.property_type,
                    view_ward_mstr.ward_no,
                    tbl_saf_dtl.saf_no,
                    owner_dtl.owner_name,
                    owner_dtl.mobile_no,
                    tbl_saf_dtl.assessment_type,
                    tbl_level_pending_dtl.forward_date,
                    tbl_level_pending_dtl.forward_time,
                    tbl_level_pending_dtl.created_on,
                    tbl_level_pending_dtl.remarks
                FROM tbl_level_pending_dtl
                INNER JOIN tbl_saf_dtl ON tbl_level_pending_dtl.saf_dtl_id = tbl_saf_dtl.id AND tbl_saf_dtl.status=1 AND tbl_saf_dtl.apply_date < '2022-08-05' ".$whereWard."
                INNER JOIN tbl_prop_type_mstr ON tbl_prop_type_mstr.id=tbl_saf_dtl.prop_type_mstr_id
                INNER JOIN ( SELECT tbl_saf_owner_detail.saf_dtl_id,
                        string_agg(tbl_saf_owner_detail.owner_name::text, ', '::text) AS owner_name,
                        string_agg(tbl_saf_owner_detail.mobile_no::text, ', '::text) AS mobile_no
                    FROM tbl_saf_owner_detail
                    GROUP BY tbl_saf_owner_detail.saf_dtl_id
                ) owner_dtl ON owner_dtl.saf_dtl_id = tbl_level_pending_dtl.saf_dtl_id
                INNER JOIN view_ward_mstr ON view_ward_mstr.id = tbl_saf_dtl.ward_mstr_id
                WHERE
                    receiver_user_type_id=".$receiver_emp_details_id."
                    AND tbl_level_pending_dtl.verification_status='0'
                    AND doc_upload_status='1'
                    AND tbl_level_pending_dtl.status='1' ".$whereSearchPrm.$whereAssessmentType.$wherePropType."
                ORDER BY tbl_level_pending_dtl.id DESC";


        $result = $this->model_datatable->getDatatable($sql);
        $data['inboxList'] = isset($result['result']) ? $result['result'] : null;
        $data['pager'] = $result['count'];
        $data['offset'] = $result['offset'];
        return view('property/saf/saf_receive_list', $data);
    }

    public function inbox_list_new()
    {
        $session = session();
        $ulb_mstr = getUlbDtl();
        $emp_mstr = $session->get("emp_details");
        $receiver_emp_details_id = $emp_mstr["user_type_mstr_id"];
        $login_emp_details_id = $emp_mstr["id"];

        $data = arrFilterSanitizeString($this->request->getVar());
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];

        $data['wardList'] = $this->model_view_ward_permission->getPermittedWard($login_emp_details_id);
        $permittedWard = array_column($data['wardList'], 'ward_mstr_id');
        $whereWard = " AND tbl_saf_dtl.ward_mstr_id IN (".implode(',', $permittedWard).")";
        $wherePropType = "";
        $whereAssessmentType = "";
        $whereSearchPrm = "";
        if (isset($data["ward_mstr_id"]) && isset($data["search_param"]) && isset($data["prop_type_mstr_id"]) && isset($data["assessment_type"])) {
            if ($data["assessment_type"]!="") {
                $whereAssessmentType = " AND tbl_saf_dtl.assessment_type='".$data["assessment_type"]."'";
            }
            if ($data["prop_type_mstr_id"]!="") {
                $wherePropType = " AND tbl_saf_dtl.prop_type_mstr_id IN (".$data["prop_type_mstr_id"].")";
            }
            if ($data["ward_mstr_id"]!="") {
                $whereWard = " AND tbl_saf_dtl.ward_mstr_id IN (".$data["ward_mstr_id"].")";
            }
            if ($data["search_param"]!="") {
                $whereSearchPrm = " AND (tbl_saf_dtl.saf_no ILIKE '%".$data["search_param"]."%'
                                        OR owner_dtl.owner_name ILIKE '%".$data["search_param"]."%'
                                        OR owner_dtl.mobile_no ILIKE '%".$data["search_param"]."%')";
            }
        }

        $sql = "SELECT
                    tbl_level_pending_dtl.id,
                    tbl_level_pending_dtl.saf_dtl_id,
                    tbl_prop_type_mstr.property_type,
                    view_ward_mstr.ward_no,
                    tbl_saf_dtl.saf_no,
                    owner_dtl.owner_name,
                    owner_dtl.mobile_no,
                    tbl_saf_dtl.assessment_type,
                    tbl_level_pending_dtl.forward_date,
                    tbl_level_pending_dtl.forward_time,
                    tbl_level_pending_dtl.created_on,
                    tbl_level_pending_dtl.remarks
                FROM tbl_level_pending_dtl
                INNER JOIN tbl_saf_dtl ON tbl_level_pending_dtl.saf_dtl_id = tbl_saf_dtl.id AND tbl_saf_dtl.status=1 AND tbl_saf_dtl.apply_date>='2022-08-05' ".$whereWard."
                INNER JOIN tbl_prop_type_mstr ON tbl_prop_type_mstr.id=tbl_saf_dtl.prop_type_mstr_id
                INNER JOIN ( SELECT tbl_saf_owner_detail.saf_dtl_id,
                        string_agg(tbl_saf_owner_detail.owner_name::text, ', '::text) AS owner_name,
                        string_agg(tbl_saf_owner_detail.mobile_no::text, ', '::text) AS mobile_no
                    FROM tbl_saf_owner_detail
                    GROUP BY tbl_saf_owner_detail.saf_dtl_id
                ) owner_dtl ON owner_dtl.saf_dtl_id = tbl_level_pending_dtl.saf_dtl_id
                INNER JOIN view_ward_mstr ON view_ward_mstr.id = tbl_saf_dtl.ward_mstr_id
                WHERE
                    receiver_user_type_id=".$receiver_emp_details_id."
                    AND tbl_level_pending_dtl.verification_status='0'
                    AND doc_upload_status='1'
                    AND tbl_level_pending_dtl.status='1' ".$whereSearchPrm.$whereAssessmentType.$wherePropType."
                ORDER BY tbl_level_pending_dtl.id DESC";


        $result = $this->model_datatable->getDatatable($sql);
        //print_var($result);
        $data['inboxList'] = isset($result['result']) ? $result['result'] : null;
        $data['offset'] = $result['offset'];
        $data['pager'] = $result['count'];
        return view('property/saf/saf_receive_list_new', $data);
    }

     // PROJECT MANAGER DOCUMENT VERIFICATION LEVEL
     public function indexPM()
     {

         return $this->response->redirect(base_url('DocumentVerification/inbox_list_PM/'));
     }

     public function inbox_list_PM()
     {
         $session = session();
         $ulb_mstr = getUlbDtl();
         $emp_mstr = $session->get("emp_details");
         $receiver_emp_details_id = $emp_mstr["user_type_mstr_id"];
         $login_emp_details_id = $emp_mstr["id"];

         $data = arrFilterSanitizeString($this->request->getVar());
         $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];

         $data['wardList'] = $this->model_ward_mstr->getWardList($ulb_mstr);
         $whereWard = "";
         $wherePropType = "";
         $whereAssessmentType = "";
         $whereSearchPrm = "";
         if (isset($data["ward_mstr_id"]) && isset($data["search_param"]) && isset($data["prop_type_mstr_id"]) && isset($data["assessment_type"])) {
             if ($data["assessment_type"]!="") {
                 $whereAssessmentType = " AND tbl_saf_dtl.assessment_type='".$data["assessment_type"]."'";
             }
             if ($data["prop_type_mstr_id"]!="") {
                 $wherePropType = " AND tbl_saf_dtl.prop_type_mstr_id IN (".$data["prop_type_mstr_id"].")";
             }
             if ($data["ward_mstr_id"]!="") {
                 $whereWard = " AND tbl_saf_dtl.ward_mstr_id IN (".$data["ward_mstr_id"].")";
             }
             if ($data["search_param"]!="") {
                 $whereSearchPrm = " AND (tbl_saf_dtl.saf_no ILIKE '".$data["search_param"]."'
                                         OR owner_dtl.owner_name ILIKE '%".$data["search_param"]."%'
                                         OR owner_dtl.mobile_no ILIKE '%".$data["search_param"]."%')";
             }
         }

         $sql = "SELECT
                     tbl_level_pending_dtl.id,
                     tbl_level_pending_dtl.saf_dtl_id,
                     tbl_prop_type_mstr.property_type,
                     view_ward_mstr.ward_no,
                     tbl_saf_dtl.saf_no,
                     owner_dtl.owner_name,
                     owner_dtl.mobile_no,
                     tbl_saf_dtl.assessment_type,
                     tbl_level_pending_dtl.forward_date,
                     tbl_level_pending_dtl.forward_time,
                     tbl_level_pending_dtl.remarks
                 FROM tbl_level_pending_dtl
                 INNER JOIN tbl_saf_dtl ON tbl_level_pending_dtl.saf_dtl_id = tbl_saf_dtl.id ".$whereWard."
                 INNER JOIN tbl_prop_type_mstr ON tbl_prop_type_mstr.id=tbl_saf_dtl.prop_type_mstr_id
                 INNER JOIN ( SELECT tbl_saf_owner_detail.saf_dtl_id,
                         string_agg(tbl_saf_owner_detail.owner_name::text, ', '::text) AS owner_name,
                         string_agg(tbl_saf_owner_detail.mobile_no::text, ', '::text) AS mobile_no
                     FROM tbl_saf_owner_detail
                     GROUP BY tbl_saf_owner_detail.saf_dtl_id
                 ) owner_dtl ON owner_dtl.saf_dtl_id = tbl_level_pending_dtl.saf_dtl_id
                 INNER JOIN view_ward_mstr ON view_ward_mstr.id = tbl_saf_dtl.ward_mstr_id
                 WHERE
                     receiver_user_type_id=".$receiver_emp_details_id."
                     AND tbl_level_pending_dtl.verification_status='0'
                     AND doc_upload_status='1'
                     AND tbl_level_pending_dtl.status='1' ".$whereSearchPrm.$whereAssessmentType.$wherePropType."
                 ORDER BY tbl_level_pending_dtl.id DESC";


         //print_var($sql);
         $result = $this->model_datatable->getDatatable($sql);
         $data['inboxList'] = isset($result['result']) ? $result['result'] : null;
         $data['pager'] = $result['count'];

         // print_var($data);
         return view('property/saf/saf_receive_list_pm', $data);
     }

    //prop sepecial doc verification
    public function prop_inbox_list() {
        $session = session();
        $data = (array)null;
        if ($session->has('input_post')) {
            $data = $session->get('input_post');
        }
        $ulb_mstr = $session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $session->get("emp_details");
        $login_emp_details_id = $emp_mstr["id"];

        //Transaction Mode List
        $wardList = $this->model_view_ward_permission->getPermittedWardWithSession($login_emp_details_id);
        $data['wardList'] = $wardList;
        $where1 = $session->get('where1');
        $where2 = $session->get('where2');
        $data['from_date'] = $session->get('from_date');
        $data['to_date'] = $session->get('to_date');
        $sql = "SELECT tbl_prop_dtl.id as prop_dtl_id,tbl_prop_dtl.holding_no,tbl_prop_dtl.new_holding_no,
       tbl_prop_dtl.prop_address,view_ward_mstr.ward_no,tbl_prop_dtl.khata_no,tbl_prop_dtl.plot_no from tbl_prop_dtl
       inner join
       (select distinct(prop_dtl_id) from tbl_prop_doc_special_dtl where
       verify_status=0 and status=1 and doc_path!='' and $where1) prop_doc_dtl on
       tbl_prop_dtl.id=prop_doc_dtl.prop_dtl_id inner join view_ward_mstr
       on tbl_prop_dtl.ward_mstr_id=view_ward_mstr.id where $where2
       ";
        $result = $this->model_datatable->getDatatable($sql);
        $data['inboxList'] = isset($result['result']) ? $result['result'] : null;
        $data['pager'] = $result['count'];
        return view('property/saf/prop_doc_verify', $data);
    }
    public function prop_inbox_index()
    {
        $session = session();
        $emp_mstr = $session->get("emp_details");
        $login_emp_details_id = $emp_mstr["id"];
        $wardList = $this->model_view_ward_permission->getPermittedWard($login_emp_details_id);
        $ward = array(null);
        foreach ($wardList as $key => $value) {
            if ($key == 0) {
                $ward = array($value['ward_mstr_id']);
            } else {
                array_push($ward, $value['ward_mstr_id']);
            }
        }
        $where2 = " tbl_prop_dtl.ward_mstr_id in (" . implode(",", $ward) . ")";
        $from_date = $to_date = date('Y-m-d');
        $data = $this->request->getVar();
        if (isset($data["from_date"]) && isset($data["to_date"])) {
            $from_date = $data["from_date"];
            $to_date = $data["to_date"];
        }
        $where1 =" date(created_on) >='" . $from_date . "' AND date(created_on) <='" . $to_date . "'";
        $sql = "SELECT 
                    tbl_prop_dtl.id as prop_dtl_id,
                    tbl_prop_dtl.holding_no,
                    tbl_prop_dtl.new_holding_no,
                    tbl_prop_dtl.prop_address,
                    view_ward_mstr.ward_no,
                    tbl_prop_dtl.khata_no,
                    tbl_prop_dtl.plot_no 
                from tbl_prop_dtl
                inner join (
                        select 
                            distinct(prop_dtl_id) 
                        from tbl_prop_doc_special_dtl 
                        where
                            verify_status=0 
                            and status=1 
                            and doc_path!='' 
                            and $where1
                ) prop_doc_dtl on tbl_prop_dtl.id=prop_doc_dtl.prop_dtl_id 
                inner join view_ward_mstr on tbl_prop_dtl.ward_mstr_id=view_ward_mstr.id 
                where $where2";
        $result = $this->model_datatable->getDatatable($sql);
        $data['inboxList'] = isset($result['result']) ? $result['result'] : null;
        $data['pager'] = $result['count'];
        return view('property/saf/prop_doc_verify', $data);
    }

    public function ByDa($id = null)
    {
        $data = (array)null;
        $session = Session();
        $ulb_mstr = $session->get("ulb_dtl");

        $emp_mstr = $session->get("emp_details");
        $login_emp_details_id = $emp_mstr["id"];
        $sender_user_type_id = $emp_mstr["user_type_mstr_id"];



        $sender_emp_details_id = $login_emp_details_id;

        $level_dtl = $this->model_view_saf_receive_list->safreceivedetailbyid($id);
        if (!$level_dtl) {
            return view("errors/html/error_404");
        }

        if ($this->request->getMethod() == 'post') {
            if (isset($_POST['btn_back_to_citizen_submit'])) {
                $inputs = arrFilterSanitizeString($this->request->getVar());

                $input = [
                    'level_pending_dtl_id' => $id,
                    'remarks' => $inputs['super_remark'],
                    'verification_status' => 2,
                    'sender_emp_details_id' => $sender_emp_details_id
                ];
                if ($this->model_level_pending_dtl->updatebacktocitizenById($input)) {
                    $input = [
                        'saf_dtl_id' => $level_dtl['saf_dtl_id'],
                        'doc_verify_status' => 2,

                    ];
                    if ($this->model_saf_dtl->update_saf_pending_status($input)) {
                        if (isset($inputs['saf_doc_dtl_id'])) {
                            $len = sizeof($inputs['saf_doc_dtl_id']);
                            for ($i = 0; $i < $len; $i++) {
                                $input = [
                                    'level_pending_dtl_id' => $inputs['saf_doc_dtl_id'][$i],
                                    'remarks' => $inputs['remarks'][$i],
                                    'verify_status' => $inputs['saf_doc_verify_status'][$i],
                                    'verified_by_emp_id' => $login_emp_details_id,
                                    'verified_on' => date('Y-m-d H:i:s')
                                ];
                                $this->model_saf_doc_dtl->updateDocVerifiedStatusById($input);
                            }
                        }
                        flashToast('message', 'Application sent back to citizen successfully');
                        return $this->response->redirect(base_url('documentverification/index/'));
                    }
                }
            } else if (isset($_POST['btn_generate_memo_submit'])) {
                $created_on = date('Y-m-d H:i:s');
                $inputs = arrFilterSanitizeString($this->request->getVar());
                $input = ['saf_dtl_id' => $level_dtl['saf_dtl_id']];
                $saf_dtl = $this->model_saf_dtl->Saf_details($input);
                $ward_no = $this->model_ward_mstr->getdatabyid($saf_dtl['new_ward_mstr_id'])['ward_no'];
                $ward_no = str_pad($ward_no, 3, "0", STR_PAD_LEFT);

                /*
                1	SUPER STRUCTURE
                2	INDEPENDENT BUILDING
                3	FLATS / UNIT IN MULTI STORIED BUILDING
                4	VACANT LAND
                5	OCCUPIED PROPERTY
                */

                if ($saf_dtl['prop_type_mstr_id'] == '4') {
                    $usage_type_code = 'M';
                } else {
                    $count_usage_type = $this->model_saf_floor_details->countRowBySafDtlId($level_dtl['saf_dtl_id']);
                    if ($count_usage_type['count_row'] > '1') {
                        $usage_type_code = 'X';
                    } else {
                        $data['usage_type'] = $this->model_saf_floor_details->getusagecodeBySafId($level_dtl['saf_dtl_id']);
                        $usage_type_code = $data['usage_type']['usage_code'];
                    }
                }

                if ($saf_dtl['prop_type_mstr_id'] == '4') {
                    $const_type_code = '0';
                } elseif ($saf_dtl['prop_type_mstr_id'] == '2' || $saf_dtl['prop_type_mstr_id'] == '3') {
                    $count_usage_type = $this->model_saf_floor_details->countRowBySafDtlId($level_dtl['saf_dtl_id']);
                    if ($count_usage_type['count_row'] > 1) {
                        $const_type_code = '4';
                    } else {
                        $const_type = $this->model_saf_floor_details->getDataBySafDtlId($level_dtl);
                        $const_type_code = $const_type[0]['const_type_mstr_id'];
                    }
                } elseif ($saf_dtl['prop_type_mstr_id'] == '1') {
                    $count_usage_type = $this->model_saf_floor_details->countRowBySafDtlId($level_dtl['saf_dtl_id']);
                    if ($count_usage_type['count_row'] > '1') {
                        $const_type_code = '8';
                    } else {
                        $const_type = $this->model_saf_floor_details->getDataBySafDtlIdd($level_dtl['saf_dtl_id']);
                        if ($const_type[0]['const_type_mstr_id'] == "1") {
                            $const_type_code = '5';
                        } else if ($const_type[0]['const_type_mstr_id'] == "2") {
                            $const_type_code = '6';
                        }
                        if ($const_type[0]['const_type_mstr_id'] == "3") {
                            $const_type_code = '7';
                        }
                    }
                }

                if ($saf_dtl['assessment_type'] == "Reassessment") {
                    //legacy prop insert
                    $previousPropDtl = $this->model_prop_dtl->getPropdetailsbyid($saf_dtl['previous_holding_id']);
                    if ($previousPropDtl['new_holding_no'] == '') {
                        $road_type = "000";
                        $sub_holding_no = '000';
                        $serial_no = $this->model_prop_dtl->count_ward_by_wardid($saf_dtl['ward_mstr_id'])['ward_cnt'];
                        $serial_no = str_pad(++$serial_no, 4, "0", STR_PAD_LEFT);
                        $generated_holding_no = $ward_no . $road_type . $serial_no . $sub_holding_no . $usage_type_code . $const_type_code;
                        $input = [
                            'prop_dtl_id' => $previousPropDtl['id'],
                            'new_holding_no' => $generated_holding_no
                        ];
                        $this->model_prop_dtl->updateUniqueHoldingNoInPropDtlByDocVerifyTime($input);
                    }
                    $input = [
                        'saf_dtl_id' => $level_dtl['saf_dtl_id'],
                        'prop_dtl_id' => $previousPropDtl['id'],
                        'updated_on' => $created_on,
                        'new_holding_no' => $generated_holding_no
                    ];
                    if ($this->model_prop_dtl->updatePropDetByDocVerifyTime($input)) {
                        $prop_dtl_id = $previousPropDtl['id'];

                        $this->model_saf_memo_dtl->SAMDeactivatedBySafDtlId($level_dtl['saf_dtl_id']);
                        $this->model_prop_floor_details->floorDtlDeactivatedByPropDtlId($prop_dtl_id);
                        $this->model_prop_tax->taxDtlDeactivatedByPropDtlId($prop_dtl_id);

                        if ($saf_dtl['prop_type_mstr_id'] != 4) {
                            $this->model_prop_floor_details->insertpropfloordetbysafid($input, $prop_dtl_id);
                        }
                        $saf_tax_list = $this->model_saf_tax->getsaftaxbysafId($level_dtl['saf_dtl_id']); // all
                        foreach ($saf_tax_list as $key => $saf_tax) {
                            $saf_tax_details = $saf_tax;
                            $saf_tax_id = $saf_tax['id'];
                            $prop_tax_id = $this->model_prop_tax->insertpropaxdetbysafid(
                                $prop_dtl_id,
                                $saf_tax['fy_mstr_id'],
                                $saf_tax['qtr'],
                                $saf_tax['arv'],
                                $saf_tax['holding_tax'],
                                $saf_tax['water_tax'],
                                $saf_tax['education_cess'],
                                $saf_tax['health_cess'],
                                $saf_tax['latrine_tax'],
                                $saf_tax['additional_tax'],
                                $created_on
                            );
                            $this->model_prop_demand->selectInsertBySafDtlAndSafTaxDtlId($level_dtl['saf_dtl_id'], $saf_tax_id, $prop_dtl_id, $prop_tax_id, $created_on);
                        }
                    }
                    $saf_tax_list = $this->model_saf_tax->getsaftaxbysafId($level_dtl['saf_dtl_id']); // all
                    foreach ($saf_tax_list as $key => $saf_tax) {
                        $saf_tax_details = $saf_tax;
                    }
                    $quartely_tax = $saf_tax_details['holding_tax'] + $saf_tax_details['water_tax'] + $saf_tax_details['education_cess'] + $saf_tax_details['health_cess'] + $saf_tax_details['latrine_tax'] + $saf_tax_details['additional_tax'];
                    $memo_type = 'SAM';
                    $genmemo_last_id = $this->model_saf_memo_dtl->generatesafmemono(
                        $level_dtl['saf_dtl_id'],
                        $saf_tax_details['fy_mstr_id'],
                        $saf_tax_details['qtr'],
                        $saf_tax_details['arv'],
                        $quartely_tax,
                        $memo_type,
                        $login_emp_details_id,
                        $created_on
                    );

                    $fy_yr = $this->model_fy_mstr->getFiyrByid($saf_tax_details['fy_mstr_id']);
                    $ward_nmm = $this->model_ward_mstr->getWardNoByOldWardId($saf_dtl['new_ward_mstr_id'])['ward_no'];
                    $memo_no = 'SAM/' . $ward_nmm . '/' . $genmemo_last_id . $serial_no . '/' . $fy_yr['fy'];
                    $this->model_saf_memo_dtl->updatememonoById($genmemo_last_id, $memo_no);
                } else {
                    $sub_holding_no = '000';
                    if ($saf_dtl['assessment_type'] == "Mutation") {
                        $previous_holding_id = $saf_dtl['previous_holding_id'];
                        $sub_holding_no = $this->model_saf_dtl->countSubHoldingNoByPreviousHoldingId($previous_holding_id)['count'];
                        $sub_holding_no = str_pad($sub_holding_no, 3, "0", STR_PAD_LEFT);
                    }
                    $road_type = "000";

                    $serial_no = $this->model_prop_dtl->count_ward_by_wardid($saf_dtl['ward_mstr_id'])['ward_cnt'];
                    $serial_no = str_pad(++$serial_no, 4, "0", STR_PAD_LEFT);
                    $generated_holding_no = $ward_no . $road_type . $serial_no . $sub_holding_no . $usage_type_code . $const_type_code;

                    $input = [
                        'saf_dtl_id' => $level_dtl['saf_dtl_id'],
                        'generated_holding_no' => $generated_holding_no,
                        'emp_details_id' => $login_emp_details_id,
                        'created_on' => $created_on
                    ];
                    if ($prop_dtl_id = $this->model_prop_dtl->insertpropdetbysafid($input)) {
                        if ($saf_dtl['prop_type_mstr_id'] != 4) {
                            $this->model_prop_floor_details->insertpropfloordetbysafid($input, $prop_dtl_id);
                        }
                        $this->model_prop_owner_detail->insertpropownerdetbysafid($input, $prop_dtl_id);

                        $saf_tax_list = $this->model_saf_tax->getsaftaxbysafId($level_dtl['saf_dtl_id']); // all
                        foreach ($saf_tax_list as $key => $saf_tax) {
                            $saf_tax_details = $saf_tax;
                            $saf_tax_id = $saf_tax['id'];
                            $prop_tax_id = $this->model_prop_tax->insertpropaxdetbysafid(
                                $prop_dtl_id,
                                $saf_tax['fy_mstr_id'],
                                $saf_tax['qtr'],
                                $saf_tax['arv'],
                                $saf_tax['holding_tax'],
                                $saf_tax['water_tax'],
                                $saf_tax['education_cess'],
                                $saf_tax['health_cess'],
                                $saf_tax['latrine_tax'],
                                $saf_tax['additional_tax'],
                                $created_on
                            );
                            $this->model_prop_demand->selectInsertBySafDtlAndSafTaxDtlId($level_dtl['saf_dtl_id'], $saf_tax_id, $prop_dtl_id, $prop_tax_id, $created_on);
                        }
                    }

                    $quartely_tax = $saf_tax_details['holding_tax'] + $saf_tax_details['water_tax'] + $saf_tax_details['education_cess'] + $saf_tax_details['health_cess'] + $saf_tax_details['latrine_tax'] + $saf_tax_details['additional_tax'];

                    $memo_type = 'SAM';
                    $genmemo_last_id = $this->model_saf_memo_dtl->generatesafmemono(
                        $level_dtl['saf_dtl_id'],
                        $saf_tax_details['fy_mstr_id'],
                        $saf_tax_details['qtr'],
                        $saf_tax_details['arv'],
                        $quartely_tax,
                        $memo_type,
                        $login_emp_details_id,
                        $created_on,
                        $generated_holding_no,
                        $prop_dtl_id
                    );

                    $fy_yr = $this->model_fy_mstr->getFiyrByid($saf_tax_details['fy_mstr_id']);
                    $ward_nmm = $this->model_ward_mstr->getWardNoByOldWardId($saf_dtl['new_ward_mstr_id'])['ward_no'];
                    $memo_no = 'SAM/' . $ward_nmm . '/' . $genmemo_last_id . $serial_no . '/' . $fy_yr['fy'];
                    $this->model_saf_memo_dtl->updatememonoById($genmemo_last_id, $memo_no, $fy_yr['fy']);

                    $input = [
                        'prop_dtl_id' => $prop_dtl_id,
                        'new_holding_no' => $generated_holding_no
                    ];
                    $this->model_prop_dtl->updateUniqueHoldingNoInPropDtlByDocVerifyTime($input);
                }

                ///////////////////////////////
                $input = ['level_pending_dtl_id' => md5($level_dtl['id']), 'verification_status' => 1];
                if ($this->model_level_pending_dtl->updatelevelpendingById($input)) {
                    $input = [
                        "saf_dtl_id" => $level_dtl['saf_dtl_id'],
                        "sender_user_type_id" => $sender_user_type_id,
                        "receiver_user_type_id" => 5,
                        "forward_date" => date('Y-m-d'),
                        "forward_time" => date('H:i:s'),
                        "remarks" => $inputs["super_remark"],
                        "created_on" => date('Y-m-d H:i:s'),
                        "doc_verify_status" => 1,
                        'sender_emp_details_id' => $sender_emp_details_id
                    ];
                    if ($this->model_level_pending_dtl->insrtlevelpendingdtl($input)) {
                        if ($this->model_saf_dtl->update_doc_verify_status($input)) {

                            if (isset($inputs['saf_doc_dtl_id'])) {
                                $len = sizeof($inputs['saf_doc_dtl_id']);
                                for ($i = 0; $i < $len; $i++) {
                                    $input = [
                                        'level_pending_dtl_id' => $inputs['saf_doc_dtl_id'][$i],
                                        'remarks' => $inputs['remarks'][$i],
                                        'verify_status' => $inputs['saf_doc_verify_status'][$i],
                                        'verified_by_emp_id' => $login_emp_details_id,
                                        'verified_on' => date('Y-m-d H:i:s')
                                    ];
                                    $this->model_saf_doc_dtl->updateDocVerifiedStatusById($input);
                                }
                            }
                            return $this->response->redirect(base_url('citizenPaymentReceipt/da_eng_memo_receipt/' . md5($ulb_mstr['ulb_mstr_id']) . '/' . md5($genmemo_last_id)));
                        }
                    }
                }
            }
        }
        $data = $this->model_view_saf_dtl->getSafDtlByMD5SafDtlId(md5($level_dtl['saf_dtl_id']));
        $data['level_dtl'] = $level_dtl;
        $input['saf_dtl_id'] = $data['saf_dtl_id'];
        //print_r($data);
        $data['saf_owner_detail'] = $this->model_saf_owner_detail->getOwnerdtlBySAFId($input);

        $data['saf_floor_details'] = $this->model_view_saf_floor_details->getDataBySafDtlId($input);
        $data['saf_tax_list'] = $this->model_saf_tax->getSafTaxDtlBySafDtlId($input);
        $data['saf_demand_list'] = $this->model_saf_demand->getFullDemandDtlBySafDtlId($input);
        $input['memo_type'] = 'SAM';
        $data["memo"] = $this->model_saf_memo_dtl->getMemo($input);

        if ($payment_detail = $this->model_transaction->getTranDtlBySafDtlId($input)) {
            $data['payment_detail'] = $payment_detail;
        }

        foreach ($data['saf_owner_detail'] as $key => $value) {
            // applicant img
            if ($owner_image = $this->model_saf_doc_dtl->getOwnerDocDtlBySafIdAndOwnerDtlId($value['saf_dtl_id'], $value['id'], "applicant_image")) {
                $data['saf_owner_detail'][$key]['applicant_img'] = $owner_image['doc_path'];
                $data['saf_owner_detail'][$key]['applicant_img_id'] = $owner_image['id'];
                $data['saf_owner_detail'][$key]['applicant_img_created_on'] = $owner_image['created_on'];
                $data['saf_owner_detail'][$key]['applicant_img_remarks'] = $owner_image['remarks'];
                $data['saf_owner_detail'][$key]['applicant_img_verify_status'] = $owner_image['verify_status'];
            }

            // applicant doc
            if ($owner_doc = $this->model_view_saf_doc_dtl->getOwnerDocDtlBySafIdAndOwnerDtlId($data['level_dtl']['saf_dtl_id'], $value['id'])) {
                $data['saf_owner_detail'][$key]['applicant_doc'] = $owner_doc['doc_path'];
                $data['saf_owner_detail'][$key]['applicant_doc_name'] = $owner_doc['doc_name'];
                $data['saf_owner_detail'][$key]['applicant_doc_id'] = $owner_doc['id'];
                $data['saf_owner_detail'][$key]['applicant_doc_created_on'] = $owner_doc['created_on'];
                $data['saf_owner_detail'][$key]['applicant_doc_remarks'] = $owner_doc['remarks'];
                $data['saf_owner_detail'][$key]['applicant_doc_verify_status'] = $owner_doc['verify_status'];
            }
        }

        if ($data['emp_details_id'] != 0) {
            if ($saf_form_doc = $this->model_saf_doc_dtl->getSafFormDocBySafDtlId($data['level_dtl']['saf_dtl_id'])) {
                $data['saf_form_doc'] = $saf_form_doc;
            }
        }
        $data['saf_doc_dtl'] = $this->model_view_saf_doc_dtl->getDocDtlByDocTypeAndSafDtlId($data['level_dtl']['saf_dtl_id']);
        //print_r($data['saf_doc_dtl']);
        //print_var($data['saf_owner_detail']);
        return view("property/saf/saf_doc_verify_by_da", $data);
    }


    public function view($id)
    {
        $data = (array)null;
        $session = session();
        $ulb_mstr = $session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $session->get("emp_details");

        $login_emp_details_id = $emp_mstr["id"];
        $sender_user_type_id = $emp_mstr["user_type_mstr_id"];
        $sender_emp_details_id = $login_emp_details_id;
        $data['form'] = $this->model_view_saf_receive_list->safreceivedetailbyid($id);
        $data['prpSaf'] = $this->model_view_saf_dtl->getSafDtlByMD5Safno(md5($data['form']['saf_no']));
        $data['owner_list'] = $this->model_saf_owner_detail->ownerdetails($data['form']['saf_dtl_id']);
        /*********/
        //print_r($data['prpSaf']);
        $data['owner_img_cnt'] = $this->model_saf_doc_dtl->count_applicant_img_new_upload($data['form']['saf_dtl_id']);
        $data['owner_doc_cnt'] = $this->model_view_saf_doc_dtl->count_applicant_doc_new_upload($data['form']['saf_dtl_id']);
        $data['tr_doc_cnt'] = $this->model_view_saf_doc_dtl->count_tr_new_upload($data['form']['saf_dtl_id']);
        $data['pr_doc_cnt'] = $this->model_view_saf_doc_dtl->count_pr_new_upload($data['form']['saf_dtl_id']);
        $extraCnt = 0;
        if ($data['form']['no_electric_connection'] == 't') {
            //$extraCnt++;
            $data['no_electric_connection_doc_cnt'] = $this->model_view_saf_doc_dtl->count_no_electric_connection_new_upload($data['form']['saf_dtl_id']);
            $extraCnt += $data['no_electric_connection_doc_cnt']['no_electric_connection_doc_cnt'];
        }
        if ($data['prpSaf']['prop_type_mstr_id'] == 1) {
            $data['super_doc_cnt'] = $this->model_view_saf_doc_dtl->count_super_new_upload($data['form']['saf_dtl_id']);
            $extraCnt += $data['super_doc_cnt']['super_doc_cnt'];
        }
        if ($data['prpSaf']['prop_type_mstr_id'] == 3) {
            $data['flat_doc_cnt'] = $this->model_view_saf_doc_dtl->count_flat_new_upload($data['form']['saf_dtl_id']);
            $extraCnt += $data['flat_doc_cnt']['flat_doc_cnt'];
        }
        $data['app_cnt'] = $data['owner_img_cnt']['app_img_cnt'] + $data['owner_doc_cnt']['app_doc_cnt'] + $data['tr_doc_cnt']['tr_doc_cnt'] + $data['pr_doc_cnt']['pr_doc_cnt'] + $extraCnt;

        /********/
        $verified_status = '1';
        $verify_status = '0';
        foreach ($data['owner_list'] as $key => $value) {
            $app_other_doc = 'applicant_image';
            $app_doc_type = "other";

            /*****applicant img code*************/
            $app_img_verified = $this->model_saf_doc_dtl->get_details_by_safid($data['form']['saf_dtl_id'], $value['id'], $app_other_doc, $verified_status);
            if ($app_img_verified['saf_owner_dtl_id'] != "") {
                $data['owner_list'][$key]['img_stts'] = '1';
                $data['owner_list'][$key]['applicant_img'] = $app_img_verified['doc_path'];
                $data['owner_list'][$key]['applicant_img_id'] = $app_img_verified['id'];
                $data['owner_list'][$key]['applicant_img_verify_status'] = $app_img_verified['verify_status'];
            } else {
                $app_img = $this->model_saf_doc_dtl->get_details_by_safid($data['form']['saf_dtl_id'], $value['id'], $app_other_doc, $verify_status);
                if ($app_img['saf_owner_dtl_id'] != "") {
                    $data['owner_list'][$key]['img_stts'] = '1';
                    $data['owner_list'][$key]['applicant_img'] = $app_img['doc_path'];
                    $data['owner_list'][$key]['applicant_img_id'] = $app_img['id'];
                    $data['owner_list'][$key]['applicant_img_verify_status'] = $app_img['verify_status'];
                }
            }
            /*****applicant doc code*************/
            $app_doc_verified = $this->model_view_saf_doc_dtl->safownerdocnamebydoctype($data['form']['saf_dtl_id'], $value['id'], $app_doc_type, $verified_status);
            if ($app_doc_verified['saf_owner_dtl_id'] != "") {
                $data['owner_list'][$key]['doc_stts'] = '1';
                $data['owner_list'][$key]['applicant_doc'] = $app_doc_verified['doc_path'];
                $data['owner_list'][$key]['applicant_doc_name'] = $app_doc_verified['doc_name'];
                $data['owner_list'][$key]['applicant_doc_id'] = $app_doc_verified['id'];
                $data['owner_list'][$key]['applicant_doc_verify_status'] = $app_doc_verified['verify_status'];
            } else {
                $app_doc = $this->model_view_saf_doc_dtl->safownerdocnamebydoctype($data['form']['saf_dtl_id'], $value['id'], $app_doc_type, $verify_status);
                if ($app_doc['saf_owner_dtl_id'] != "") {
                    $data['owner_list'][$key]['doc_stts'] = '1';
                    $data['owner_list'][$key]['applicant_doc'] = $app_doc['doc_path'];
                    $data['owner_list'][$key]['applicant_doc_name'] = $app_doc['doc_name'];
                    $data['owner_list'][$key]['applicant_doc_id'] = $app_doc['id'];
                    $data['owner_list'][$key]['applicant_doc_verify_status'] = $app_doc['verify_status'];
                }
            }
        }

        /*****tr mode code**********/
        $tr_doc_type = "transfer_mode";
        $data['vprop_tr_mode_document'] = $this->model_view_saf_doc_dtl->safdocnamebydoctype($data['form']['saf_dtl_id'], $tr_doc_type, $verified_status);
        if ($data['vprop_tr_mode_document']['id'] != "") {
            $data['tr_stts'] = '1';
            $data['tr_doc_nm'] = $data['vprop_tr_mode_document']['doc_name'];
            $data['tr_doc_name'] = $data['vprop_tr_mode_document']['doc_path'];
            $data['tr_doc_id'] = $data['vprop_tr_mode_document']['id'];
            $data['tr_doc_verify_status'] = $data['vprop_tr_mode_document']['verify_status'];
        } else {
            $data['prop_tr_mode_document'] = $this->model_view_saf_doc_dtl->safdocnamebydoctype($data['form']['saf_dtl_id'], $tr_doc_type, $verify_status);
            if ($data['prop_tr_mode_document']['id'] != "") {
                $data['tr_stts'] = '1';
                $data['tr_doc_nm'] = $data['prop_tr_mode_document']['doc_name'];
                $data['tr_doc_name'] = $data['prop_tr_mode_document']['doc_path'];
                $data['tr_doc_id'] = $data['prop_tr_mode_document']['id'];
                $data['tr_doc_verify_status'] = $data['prop_tr_mode_document']['verify_status'];
            }
        }
        /*******pr mode code********/
        $pr_doc_type = "property_type";
        $data['vprop_pr_mode_document'] = $this->model_view_saf_doc_dtl->safdocnamebydoctype($data['form']['saf_dtl_id'], $pr_doc_type, $verified_status);
        if ($data['vprop_pr_mode_document']['id'] != "") {
            $data['pr_stts'] = '1';
            $data['pr_doc_nm'] = $data['vprop_pr_mode_document']['doc_name'];
            $data['pr_doc_name'] = $data['vprop_pr_mode_document']['doc_path'];
            $data['pr_doc_id'] = $data['vprop_pr_mode_document']['id'];
            $data['pr_doc_verify_status'] = $data['vprop_pr_mode_document']['verify_status'];
        } else {
            $data['prop_pr_mode_document'] = $this->model_view_saf_doc_dtl->safdocnamebydoctype($data['form']['saf_dtl_id'], $pr_doc_type, $verify_status);
            if ($data['prop_pr_mode_document']['id'] != "") {
                $data['pr_stts'] = '1';
                $data['pr_doc_nm'] = $data['prop_pr_mode_document']['doc_name'];
                $data['pr_doc_name'] = $data['prop_pr_mode_document']['doc_path'];
                $data['pr_doc_id'] = $data['prop_pr_mode_document']['id'];
                $data['pr_doc_verify_status'] = $data['prop_pr_mode_document']['verify_status'];
            }
        }

        $fr_doc_type = "saf_form";
        $data['vprop_saf_form_mode_document'] = $this->model_saf_doc_dtl->safFormDocNameByOtherDocAndSafDtlId($data['form']['saf_dtl_id'], $verified_status);
        if ($data['vprop_saf_form_mode_document']['id'] != "") {
            $data['saf_form_stts'] = '1';
            $data['saf_form_doc_nm'] = "SAF Form";
            $data['saf_form_doc_name'] = $data['vprop_saf_form_mode_document']['doc_path'];
            $data['saf_form_doc_id'] = $data['vprop_saf_form_mode_document']['id'];
            $data['saf_form_doc_verify_status'] = $data['vprop_saf_form_mode_document']['verify_status'];
        } else {
            $data['prop_saf_form_mode_document'] = $this->model_saf_doc_dtl->safFormDocNameByOtherDocAndSafDtlId($data['form']['saf_dtl_id'], $verify_status);
            //print_r($data['prop_saf_form_mode_document']);
            if ($data['prop_saf_form_mode_document']['id'] != "") {
                $data['saf_form_stts'] = '1';
                $data['saf_form_doc_nm'] = "SAF Form";
                $data['saf_form_doc_name'] = $data['prop_saf_form_mode_document']['doc_path'];
                $data['saf_form_doc_id'] = $data['prop_saf_form_mode_document']['id'];
                $data['saf_form_doc_verify_status'] = $data['prop_saf_form_mode_document']['verify_status'];
            }
        }
        /*****no_elect_connection mode code**********/
        if ($data['form']['no_electric_connection'] == 't') {
            $no_elect_connection_doc_type = "no_elect_connection";
            $data['vprop_no_elect_connection_mode_document'] = $this->model_view_saf_doc_dtl->safdocnamebydoctype($data['form']['saf_dtl_id'], $no_elect_connection_doc_type, $verified_status);
            if ($data['vprop_no_elect_connection_mode_document']['id'] != "") {
                $data['no_electric_connection_stts'] = '1';
                $data['no_electric_connection_doc_nm'] = $data['vprop_no_elect_connection_mode_document']['doc_name'];
                $data['no_electric_connection_doc_name'] = $data['vprop_no_elect_connection_mode_document']['doc_path'];
                $data['no_electric_connection_doc_id'] = $data['vprop_no_elect_connection_mode_document']['id'];
                $data['no_electric_connection_doc_verify_status'] = $data['vprop_no_elect_connection_mode_document']['verify_status'];
            } else {
                $data['prop_no_elect_connection_mode_document'] = $this->model_view_saf_doc_dtl->safdocnamebydoctype($data['form']['saf_dtl_id'], $no_elect_connection_doc_type, $verify_status);
                if ($data['prop_no_elect_connection_mode_document']['id'] != "") {
                    $data['no_electric_connection_stts'] = '1';
                    $data['no_electric_connection_doc_nm'] = $data['prop_no_elect_connection_mode_document']['doc_name'];
                    $data['no_electric_connection_doc_name'] = $data['prop_no_elect_connection_mode_document']['doc_path'];
                    $data['no_electric_connection_doc_id'] = $data['prop_no_elect_connection_mode_document']['id'];
                    $data['no_electric_connection_doc_verify_status'] = $data['prop_no_elect_connection_mode_document']['verify_status'];
                }
            }
        }

        //print_r($data['form']['prop_type_mstr_id']);
        if ($data['prpSaf']['prop_type_mstr_id'] == 1) {
            $super_doc_type = "super_structure_doc";
            $data['vprop_super_mode_document'] = $this->model_view_saf_doc_dtl->safdocnamebydoctype($data['form']['saf_dtl_id'], $super_doc_type, $verified_status);
            if ($data['vprop_super_mode_document']['id'] != "") {
                $data['super_stts'] = '1';
                $data['super_doc_nm'] = $data['vprop_super_mode_document']['doc_name'];
                $data['super_doc_name'] = $data['vprop_super_mode_document']['doc_path'];
                $data['super_doc_id'] = $data['vprop_super_mode_document']['id'];
                $data['super_doc_verify_status'] = $data['vprop_super_mode_document']['verify_status'];
            } else {
                $data['prop_super_mode_document'] = $this->model_view_saf_doc_dtl->safdocnamebydoctype($data['form']['saf_dtl_id'], $super_doc_type, $verify_status);
                if ($data['prop_super_mode_document']['id'] != "") {
                    $data['super_stts'] = '1';
                    $data['super_doc_nm'] = $data['prop_super_mode_document']['doc_name'];
                    $data['super_doc_name'] = $data['prop_super_mode_document']['doc_path'];
                    $data['super_doc_id'] = $data['prop_super_mode_document']['id'];
                    $data['super_doc_verify_status'] = $data['prop_super_mode_document']['verify_status'];
                }
            }
        }
        //print_r($data['form']);
        if ($data['prpSaf']['prop_type_mstr_id'] == 3) {
            $flat_doc_type = ["flat_doc", "transfer_mode"];
            $data['vprop_flat_mode_document'] = $this->model_view_saf_doc_dtl->safdocflatbydoctype($data['form']['saf_dtl_id'], $flat_doc_type, $verified_status);
            if ($data['vprop_flat_mode_document']['id'] != "") {
                $data['flat_stts'] = '1';
                $data['flat_doc_nm'] = $data['vprop_flat_mode_document']['doc_name'];
                $data['flat_doc_name'] = $data['vprop_flat_mode_document']['doc_path'];
                $data['flat_doc_id'] = $data['vprop_flat_mode_document']['id'];
                $data['flat_doc_verify_status'] = $data['vprop_flat_mode_document']['verify_status'];
            } else {
                $data['prop_flat_mode_document'] = $this->model_view_saf_doc_dtl->safdocflatbydoctype($data['form']['saf_dtl_id'], $flat_doc_type, $verify_status);
                if ($data['prop_flat_mode_document']['id'] != "") {
                    $data['flat_stts'] = '1';
                    $data['flat_doc_nm'] = $data['prop_flat_mode_document']['doc_name'];
                    $data['flat_doc_name'] = $data['prop_flat_mode_document']['doc_path'];
                    $data['flat_doc_id'] = $data['prop_flat_mode_document']['id'];
                    $data['flat_doc_verify_status'] = $data['prop_flat_mode_document']['verify_status'];
                }
            }
        }
        /***************/
        //print_r($data['applicant_document']);
        $data['ward'] = $this->model_ward_mstr->getdatabyid($data['form']['ward_mstr_id']);
        $data['form']['ward_no'] = $data['ward']['ward_no'];
        $saf_dtl_id = $data['form']['saf_dtl_id'];
        $data['tax_list'] = $this->model_saf_tax->tax_list($data['form']['saf_dtl_id']);
        $data['occupancy_detail'] = $this->model_saf_floor_details->occupancy_detail($data['form']['saf_dtl_id']);
        //print_r($data['occupancy_detail']);
        /****** verification code starts***********/
        $data["ward_list"] = $this->model_ward_mstr->getWardList($data);
        $data["Saf_detail"] = $this->model_saf_dtl->Saf_details_md5(md5($data['form']['saf_dtl_id']));
        $data["Saf_dtl_id"] = $data["Saf_detail"]["id"];
        $data["saf_no"] = $data["Saf_detail"]["saf_no"];
        $data["area_of_plot"] = $data["Saf_detail"]["area_of_plot"];
        $data["apply_date"] = $data["Saf_detail"]["apply_date"];
        $data["is_hoarding_board"] = $data["Saf_detail"]["is_hoarding_board"];
        $data["hoarding_area"] = $data["Saf_detail"]["hoarding_area"];
        $data["hoarding_installation_date"] = $data["Saf_detail"]["hoarding_installation_date"];
        $data["is_mobile_tower"] = $data["Saf_detail"]["is_mobile_tower"];
        $data["tower_area"] = $data["Saf_detail"]["tower_area"];
        $data["tower_installation_date"] = $data["Saf_detail"]["tower_installation_date"];
        $data["is_petrol_pump"] = $data["Saf_detail"]["is_petrol_pump"];
        $data["under_ground_area"] = $data["Saf_detail"]["under_ground_area"];
        $data["petrol_pump_completion_date"] = $data["Saf_detail"]["petrol_pump_completion_date"];
        $data["is_water_harvesting"] = $data["Saf_detail"]["is_water_harvesting"];
        $data["land_occupation_date"] = $data["Saf_detail"]["land_occupation_date"];
        $data["ward_detail"] = $this->model_ward_mstr->getdatabyid($data["Saf_detail"]["ward_mstr_id"]);
        $data["prop_type_detail"] = $this->model_prop_type_mstr->getdatabyid($data["Saf_detail"]["prop_type_mstr_id"]);
        $data["prop_type_list"] = $this->model_prop_type_mstr->getPropTypeList();
        $data["road_type_detail"] = $this->model_road_type_mstr->getdatabyid($data["Saf_detail"]["road_type_mstr_id"]);
        $data["road_type_list"] = $this->model_road_type_mstr->getRoadTypeList();
        $data["ward_no"] = $data["ward_detail"]["ward_no"];
        $data["property_type"] = $data["prop_type_detail"]["property_type"];
        $data["road_type"] = $data["road_type_detail"]["road_type"];
        $data["owner_detail"] = $this->model_saf_owner_detail->ownerdetails_md5(md5($data['form']['saf_dtl_id']));
        $data["floor_details"] = $this->model_view_saf_floor_details->getDataBySafDtlId_md5(md5($data['form']['saf_dtl_id']));

        //print_r($data['vfloor_details']);
        /******* verification code ends**********/
        if ($this->request->getMethod() == 'post') {
            if (isset($_POST['btn_app_submit'])) {
                $data = [
                    'tr_document_id' => $this->request->getVar('tr_document_id'),
                    'super_document_id' => $this->request->getVar('super_document_id'),
                    'flat_document_id' => $this->request->getVar('flat_document_id'),
                    'pr_document_id' => $this->request->getVar('pr_document_id'),
                    'tr_remarks' => $this->request->getVar('tr_remarks'),
                    'super_remarks' => $this->request->getVar('super_remarks'),
                    'flat_remarks' => $this->request->getVar('flat_remarks'),
                    'pr_remarks' => $this->request->getVar('pr_remarks'),
                    'tr_verify' => $this->request->getVar('tr_verify'),
                    'super_verify' => $this->request->getVar('super_verify'),
                    'flat_verify' => $this->request->getVar('flat_verify'),
                    'pr_verify' => $this->request->getVar('pr_verify'),
                    'remarks' => $this->request->getVar('remarks'),
                    'level_pending_dtl_id' => $id,
                    'saf_dtl_id' => $saf_dtl_id,
                    'emp_details_id' => $login_emp_details_id,
                    'created_on' => date('Y-m-d H:i:s'),
                    'verification_status' => 2,
                    'saf_pending_status' => 2,
                    'sender_emp_details_id' => $sender_emp_details_id

                ];

                if ($updatebacktocitizen = $this->model_level_pending_dtl->updatebacktocitizenById($data)) {
                    if ($updatesafpendingstts = $this->model_saf_dtl->update_saf_pending_status($data)) {
                        ///applicant image stts
                        $app_img_verify = $this->request->getVar('app_img_verify');
                        $app_img_remarks = $this->request->getVar('app_img_remarks');
                        $applicant_img_id = $this->request->getVar('applicant_img_id');
                        if (isset($applicant_img_id)) {
                            $app_img_len = sizeof($applicant_img_id);
                            for ($iv = 0; $iv < $app_img_len; $iv++) {
                                $data_up = [
                                    'applicant_img_id' => $applicant_img_id[$iv],
                                    'app_img_verify' => $app_img_verify[$iv],
                                    'app_img_remarks' => $app_img_remarks[$iv],
                                    'emp_details_id' => $login_emp_details_id,
                                    'created_on' => date('Y-m-d H:i:s')
                                ];

                                $updateappimgdoc = $this->model_saf_doc_dtl->updateappimgdocById($data_up);
                            }
                        }
                        ///applicant doc stts
                        $app_doc_verify = $this->request->getVar('app_doc_verify');
                        $app_doc_remarks = $this->request->getVar('app_doc_remarks');
                        $applicant_doc_id = $this->request->getVar('applicant_doc_id');
                        if (isset($applicant_doc_id)) {
                            $app_doc_len = sizeof($applicant_doc_id);

                            for ($ivn = 0; $ivn < $app_doc_len; $ivn++) {
                                $data_u = [
                                    'applicant_doc_id' => $applicant_doc_id[$ivn],
                                    'app_doc_verify' => $app_doc_verify[$ivn],
                                    'app_doc_remarks' => $app_doc_remarks[$ivn],
                                    'emp_details_id' => $login_emp_details_id,
                                    'created_on' => date('Y-m-d H:i:s')
                                ];
                                $updateappdoc = $this->model_saf_doc_dtl->updateappdocById($data_u);
                            }
                        }
                        $updatetrdoc = $this->model_saf_doc_dtl->updatetrdocById($data);
                        $updatesuperdocById = $this->model_saf_doc_dtl->updatesuperdocById($data);
                        $updateflatdocById = $this->model_saf_doc_dtl->updateflatdocById($data);
                        $updateprdoc = $this->model_saf_doc_dtl->updateprdocById($data);
                        return $this->response->redirect(base_url('documentverification/index/'));
                    }
                }
            }
            /**********/
            if (isset($_POST['btn_generate_memo_submit'])) {
                /*****Holding No. Generation code starts*************/
                $data['saf_dtl_id'] = $saf_dtl_id;
                $data['saf_dtl'] = $this->model_saf_dtl->Saf_details($data);
                //print_r($data['saf_dtl']);
                if (($data['saf_dtl']['assessment_type'] == "New Assessment") || ($data['saf_dtl']['assessment_type'] == "Mutation") || ($data['saf_dtl']['assessment_type'] == "Reassessment")) {

                    //die();
                    $data['ward'] = $this->model_ward_mstr->getdatabyid($data['saf_dtl']['ward_mstr_id']);


                    if ($data['saf_dtl']['prop_type_mstr_id'] == '4') {
                        $data['usage_type_code'] = 'M';
                    } else {
                        $data['count_usage_type'] = $this->model_saf_floor_details->countrowBySafDtlId($saf_dtl_id);
                        if ($data['count_usage_type']['count_row'] > '1') {
                            $data['usage_type_code'] = 'X';
                        } else {
                            $data['usage_type'] = $this->model_saf_floor_details->getusagecodeBySafId($saf_dtl_id);
                            $data['usage_type_code'] = $data['usage_type']['usage_code'];
                        }
                    }

                    ///contruction type code
                    if ($data['saf_dtl']['prop_type_mstr_id'] == '4') {
                        //if vacant land
                        $data['const_type_code'] = '0';
                    } else if (($data['saf_dtl']['prop_type_mstr_id'] == '2') || ($data['saf_dtl']['prop_type_mstr_id'] == '3')) {
                        //if indendendent or flat
                        $data['count_const_type'] = $this->model_saf_floor_details->countrowBySafDtlId($saf_dtl_id);
                        if ($data['count_const_type']['count_row'] > '1') {
                            $data['const_type_code'] = '4';
                        } else {
                            $data['const_type'] = $this->model_saf_floor_details->getDataBySafDtlId($data);
                            if ($data['const_type'][0]['const_type_mstr_id'] == "1") {
                                $data['const_type_code'] = '1';
                            } else if ($data['const_type'][0]['const_type_mstr_id'] == "2") {
                                $data['const_type_code'] = '2';
                            }
                            if ($data['const_type'][0]['const_type_mstr_id'] == "3") {
                                $data['const_type_code'] = '3';
                            }
                        }
                    } else if (($data['saf_dtl']['prop_type_mstr_id'] == '1')) {
                        //if super structured
                        $data['count_const_type'] = $this->model_saf_floor_details->countrowBySafDtlId($saf_dtl_id);
                        if ($data['count_const_type']['count_row'] > '1') {
                            $data['const_type_code'] = '8';
                        } else {
                            $data['const_type'] = $this->model_saf_floor_details->getDataBySafDtlIdd($saf_dtl_id);
                            if ($data['const_type'][0]['const_type_mstr_id'] == "1") {
                                $data['const_type_code'] = '5';
                            } else if ($data['const_type'][0]['const_type_mstr_id'] == "2") {
                                $data['const_type_code'] = '6';
                            }
                            if ($data['const_type'][0]['const_type_mstr_id'] == "3") {
                                $data['const_type_code'] = '7';
                            }
                        }
                    }


                    //$saf_dtl_id=$data['saf_dtl']['id'];
                    $ward_no = str_pad($data['ward']['ward_no'], 2, "0", STR_PAD_LEFT);
                    $ward_no = "A" . $ward_no;
                    $road_type = '000';
                    if ($data['saf_dtl']['assessment_type'] == "Mutation") {
                        $previous_holding_id = $data['saf_dtl']['previous_holding_id'];
                        $data['previous_prop_dtl'] = $this->model_prop_dtl->getPropdetailsbyid($previous_holding_id);
                        //echo $data['previous_prop_dtl']['for_sub_holding'];
                        $sub_holding_no_increment = $data['previous_prop_dtl']['for_sub_holding'] + 1;
                        $data['previous_sub_holding_upd'] = $this->model_prop_dtl->updateforsubholdingbypropid($previous_holding_id, $sub_holding_no_increment);
                        $sub_holding_no = str_pad($sub_holding_no_increment, 3, "0", STR_PAD_LEFT);
                    } else {
                        $sub_holding_no = '000';
                    }

                    //get max fy yr and max quarter
                    $max_fy_id = $this->model_saf_tax->getmaxfyidbysafid($saf_dtl_id);
                    $max_qtr = $this->model_saf_tax->getmaxfyqtridbysafid($saf_dtl_id, $max_fy_id['max_fy_id']);
                    $saf_tx = $this->model_saf_tax->getallmaxfyqtridbysafid($saf_dtl_id, $max_fy_id['max_fy_id'], $max_qtr['max_qtr']);
                    $fy_yr = $this->model_fy_mstr->getFiyrByid($saf_tx['fy_mstr_id']);

                    //count ward
                    $data['ward_count'] = $this->model_prop_dtl->count_ward_by_wardid($data['saf_dtl']['ward_mstr_id']);
                    $sl_no = $data['ward_count']['ward_cnt'];
                    $sl_noo = $sl_no + 1;
                    $serial_no = str_pad($sl_noo, 4, "0", STR_PAD_LEFT);



                    $usage_code = $data['usage_type_code'];
                    $cons_type = $data['const_type_code'];
                    $generated_holding_no = $ward_no . $road_type . $serial_no . $sub_holding_no . $usage_code . $cons_type;
                    $ward_nmm = $data['form']['ward_no'];
                    /*****Holding No. Generation code ends*************/
                    $data = [
                        'applicant_document_id' => $this->request->getVar('applicant_document_id'),
                        'tr_document_id' => $this->request->getVar('tr_document_id'),
                        'pr_document_id' => $this->request->getVar('pr_document_id'),
                        'app_remarks' => '',
                        'tr_remarks' => '',
                        'pr_remarks' => '',
                        'app_verify' => $this->request->getVar('app_verify'),
                        'tr_verify' => $this->request->getVar('tr_verify'),
                        'pr_verify' => $this->request->getVar('pr_verify'),
                        'remarks' => $this->request->getVar('remarks'),
                        'memo_no' => $memo_no,
                        'generated_holding_no' => $generated_holding_no,
                        'level_pending_dtl_id' => $id,
                        'saf_dtl_id' => $saf_dtl_id,
                        'emp_details_id' => $login_emp_details_id,
                        'created_on' => date('Y-m-d H:i:s'),
                        'forward_date' => date('Y-m-d'),
                        'forward_time' => date('H:i:s'),
                        'sender_user_type_id' => $sender_user_type_id,
                        'receiver_user_type_id' => 5,
                        'verification_status' => 1,
                        'saf_pending_status' => 0,
                        'sender_emp_details_id' => $sender_emp_details_id
                    ];



                    if ($propdtl = $this->model_prop_dtl->insertpropdetbysafid($data)) {

                        $propownerdtl = $this->model_prop_owner_detail->insertpropownerdetbysafid($data, $propdtl);
                        $propfloordtl = $this->model_prop_floor_details->insertpropfloordetbysafid($data, $propdtl);
                        //
                        $saf_tax_list = $this->model_saf_tax->getsaftaxbysafId($saf_dtl_id); // all
                        foreach ($saf_tax_list as $key => $saf_tax) {
                            $saf_tax_id = $saf_tax['id'];

                            $prop_tax_id = $this->model_prop_tax->insertpropaxdetbysafid($propdtl, $saf_tax['fy_mstr_id'], $saf_tax['qtr'], $saf_tax['arv'], $saf_tax['holding_tax'], $saf_tax['water_tax'], $saf_tax['education_cess'], $saf_tax['health_cess'], $saf_tax['latrine_tax'], $saf_tax['additional_tax'], $data['created_on']);
                            //echo $prop_tax_id;

                            $saf_demand_list = $this->model_saf_demand->getsaftaxnotpaidbyid($saf_dtl_id, $saf_tax_id); // all
                            //print_r($saf_demand_list);
                            foreach ($saf_demand_list as $key => $saf_demand) {

                                $prop_demand_id = $this->model_prop_demand->insrtpropdemand($propdtl, $prop_tax_id, $saf_demand['fy_mstr_id'], $saf_demand['qtr'], $saf_demand['amount'], $saf_demand['balance'], $saf_demand['fine_tax'], $data['created_on'], $saf_demand['ward_mstr_id']);
                                //$prop_coll_id = $this->model_collection->insertpropcolldetbysafid($saf_dtl_id,$propdtl,$prop_demand_id,$data['created_on']);
                                //echo $prop_coll_id.'<br/>';
                            }
                        }
                        //die();



                        $quartely_tax = $saf_tx['holding_tax'] + $saf_tx['water_tax'] + $saf_tx['education_cess'] + $saf_tx['health_cess'] + $saf_tx['latrine_tax'];
                        //die();
                        $memo_type = 'SAM';
                        $genmemo_last_id = $this->model_saf_memo_dtl->generatesafmemono($saf_dtl_id, $saf_tx['fy_mstr_id'], $saf_tx['qtr'], $saf_tx['arv'], $quartely_tax, $memo_type, $login_emp_details_id, $data['created_on']);
                        $memo_no = 'SAM/' . $ward_nmm . '/' . $genmemo_last_id . $serial_no . '/' . $fy_yr['fy'];
                        $updatememono = $this->model_saf_memo_dtl->updatememonoById($genmemo_last_id, $memo_no);


                        if ($updatelevelpending = $this->model_level_pending_dtl->updatelevelpendingById($data)) {
                            if ($insrtlevelpending = $this->model_level_pending_dtl->insrtlevelpendingdtl($data)) {
                                if ($updatesafpendingstts = $this->model_saf_dtl->update_saf_pending_status($data)) {
                                    ///applicant image stts
                                    $app_img_verify = $this->request->getVar('app_img_verify');
                                    $app_img_remarks = $this->request->getVar('app_img_remarks');
                                    $applicant_img_id = $this->request->getVar('applicant_img_id');
                                    if (isset($applicant_img_id)) {
                                        $app_img_len = sizeof($applicant_img_id);
                                        for ($iv = 0; $iv < $app_img_len; $iv++) {
                                            $data_up = [
                                                'applicant_img_id' => $applicant_img_id[$iv],
                                                'app_img_verify' => $app_img_verify[$iv],
                                                'app_img_remarks' => '',
                                                'emp_details_id' => $login_emp_details_id,
                                                'created_on' => date('Y-m-d H:i:s')
                                            ];
                                            $updateappimgdoc = $this->model_saf_doc_dtl->updateappimgdocById($data_up);
                                        }
                                    }
                                    ///applicant doc stts
                                    $app_doc_verify = $this->request->getVar('app_doc_verify');
                                    $app_doc_remarks = $this->request->getVar('app_doc_remarks');
                                    $applicant_doc_id = $this->request->getVar('applicant_doc_id');
                                    if (isset($applicant_doc_id)) {
                                        $app_doc_len = sizeof($applicant_doc_id);

                                        for ($ivn = 0; $ivn < $app_doc_len; $ivn++) {
                                            $data_u = [
                                                'applicant_doc_id' => $applicant_doc_id[$ivn],
                                                'app_doc_verify' => $app_doc_verify[$ivn],
                                                'app_doc_remarks' => '',
                                                'emp_details_id' => $login_emp_details_id,
                                                'created_on' => date('Y-m-d H:i:s')
                                            ];
                                            $updateappdoc = $this->model_saf_doc_dtl->updateappdocById($data_u);
                                        }
                                    }
                                    //$updateappdoc = $this->model_saf_doc_dtl->updateappdocById($data);
                                    $updatetrdoc = $this->model_saf_doc_dtl->updatetrdocById($data);
                                    $updateprdoc = $this->model_saf_doc_dtl->updateprdocById($data);


                                    $data_da = [
                                        'doc_verify_status' => 1,
                                        'doc_verify_date' => date('Y-m-d'),
                                        'doc_verify_emp_details_id' => $login_emp_details_id,
                                        'prop_dtl_id' => $propdtl
                                    ];

                                    $updatdastatus = $this->model_saf_dtl->update_da_verify_status($data_da, $saf_dtl_id);
                                    return $this->response->redirect(base_url('citizenPaymentReceipt/da_eng_memo_receipt/' . md5($ulb_mstr['ulb_mstr_id']) . '/' . md5($genmemo_last_id)));
                                }
                            }
                        }
                    }
                } else if ($data['saf_dtl']['assessment_type'] == "Reassessment") {
                    //reassessment code starts
                    /*$data['last_memo_no']=$this->model_saf_memo_dtl->get_last_memo_no();
                    //$data['last_memo_no']['memo_no']='0010';
                    if($data['last_memo_no'])
                    {
                        $sl_no = $data['last_memo_no']['memo_no'];
                        $sl_noo = $sl_no+1;
                        $memo_no = str_pad($sl_noo, 4, "0", STR_PAD_LEFT);

                    }
                    else{
                        $memo_no = '0001';
                    }*/
                    $ward_nmm = $data['form']['ward_no'];

                    $previous_holding_id = $data['saf_dtl']['previous_holding_id'];
                    $data = [
                        'tr_document_id' => $this->request->getVar('tr_document_id'),
                        'pr_document_id' => $this->request->getVar('pr_document_id'),
                        'tr_remarks' => '',
                        'pr_remarks' => '',
                        'tr_verify' => $this->request->getVar('tr_verify'),
                        'pr_verify' => $this->request->getVar('pr_verify'),
                        'remarks' => $this->request->getVar('remarks'),
                        'level_pending_dtl_id' => $id,
                        'saf_dtl_id' => $saf_dtl_id,
                        'emp_details_id' => $login_emp_details_id,
                        'created_on' => date('Y-m-d H:i:s'),
                        'forward_date' => date('Y-m-d'),
                        'forward_time' => date('H:i:s'),
                        'sender_user_type_id' => $sender_user_type_id,
                        'receiver_user_type_id' => 5,
                        'verification_status' => 1,
                        'saf_pending_status' => 0,
                        'sender_emp_details_id' => $sender_emp_details_id

                    ];
                    //legacy prop insert
                    $propentrydetl = $this->model_prop_dtl->getPropdetailsbyid($previous_holding_id);
                    if ($propentrydetl['entry_type'] == 'Legacy') {
                        /***holding no code generation code***/
                        $data['ward'] = $this->model_ward_mstr->getdatabyid($data['saf_dtl']['ward_mstr_id']);


                        if ($data['saf_dtl']['prop_type_mstr_id'] == '4') {
                            $data['usage_type_code'] = 'M';
                        } else {
                            $data['count_usage_type'] = $this->model_saf_floor_details->countrowBySafDtlId($saf_dtl_id);
                            if ($data['count_usage_type']['count_row'] > '1') {
                                $data['usage_type_code'] = 'X';
                            } else {
                                $data['usage_type'] = $this->model_saf_floor_details->getusagecodeBySafId($saf_dtl_id);
                                $data['usage_type_code'] = $data['usage_type']['usage_code'];
                            }
                        }

                        ///contruction type code
                        if ($data['saf_dtl']['prop_type_mstr_id'] == '4') {
                            //if vacant land
                            $data['const_type_code'] = '0';
                        } else if (($data['saf_dtl']['prop_type_mstr_id'] == '2') || ($data['saf_dtl']['prop_type_mstr_id'] == '3')) {
                            //if indendendent or flat
                            $data['count_const_type'] = $this->model_saf_floor_details->countrowBySafDtlId($saf_dtl_id);
                            if ($data['count_const_type']['count_row'] > '1') {
                                $data['const_type_code'] = '4';
                            } else {
                                $data['const_type'] = $this->model_saf_floor_details->getDataBySafDtlId($data);
                                if ($data['const_type'][0]['const_type_mstr_id'] == "1") {
                                    $data['const_type_code'] = '1';
                                } else if ($data['const_type'][0]['const_type_mstr_id'] == "2") {
                                    $data['const_type_code'] = '2';
                                }
                                if ($data['const_type'][0]['const_type_mstr_id'] == "3") {
                                    $data['const_type_code'] = '3';
                                }
                            }
                        } else if (($data['saf_dtl']['prop_type_mstr_id'] == '1')) {
                            //if super structured
                            $data['count_const_type'] = $this->model_saf_floor_details->countrowBySafDtlId($saf_dtl_id);
                            if ($data['count_const_type']['count_row'] > '1') {
                                $data['const_type_code'] = '8';
                            } else {
                                $data['const_type'] = $this->model_saf_floor_details->getDataBySafDtlIdd($saf_dtl_id);
                                if ($data['const_type'][0]['const_type_mstr_id'] == "1") {
                                    $data['const_type_code'] = '5';
                                } else if ($data['const_type'][0]['const_type_mstr_id'] == "2") {
                                    $data['const_type_code'] = '6';
                                }
                                if ($data['const_type'][0]['const_type_mstr_id'] == "3") {
                                    $data['const_type_code'] = '7';
                                }
                            }
                        }


                        //$saf_dtl_id=$data['saf_dtl']['id'];
                        $ward_no = str_pad($data['ward']['ward_no'], 3, "0", STR_PAD_LEFT);
                        $road_type = '000';
                        $sub_holding_no = '000';
                        //count ward
                        $data['ward_count'] = $this->model_prop_dtl->count_ward_by_wardid($data['saf_dtl']['ward_mstr_id']);
                        $sl_no = $data['ward_count']['ward_cnt'];
                        $sl_noo = $sl_no + 1;
                        $serial_no = str_pad($sl_noo, 4, "0", STR_PAD_LEFT);

                        $usage_code = $data['usage_type_code'];
                        $cons_type = $data['const_type_code'];
                        $generated_holding_no = $ward_no . $road_type . $serial_no . $sub_holding_no . $usage_code . $cons_type;
                        /******/
                        $legacy_data = [
                            'generated_holding_no' => $generated_holding_no,
                            'old_ward_mstr_id' => $propentrydetl['ward_mstr_id'],
                            'old_holding_no' => $propentrydetl['holding_no'],
                            'saf_dtl_id' => $saf_dtl_id,
                            'emp_details_id' => $login_emp_details_id,
                            'created_on' => date('Y-m-d H:i:s')
                        ];
                        $legacy_propdtl = $this->model_prop_dtl->insertlegacypropdetbysafid($legacy_data);
                        $this->model_prop_dtl->updateentry_type_statusbypropid($previous_holding_id);
                        //echo $legacy_propdtl;
                    }

                    // die();
                    //update saf hold stts

                    $propsafholdstts = $this->model_prop_dtl->updatesaf_hold_statusbypropid($previous_holding_id);
                    //update prop floor details

                    $propfloorupddtl = $this->model_prop_floor_details->updatepropfloorBypropdetId($previous_holding_id);
                    if ($propfloorupddtl) {
                        $propfloordtl = $this->model_prop_floor_details->insertpropfloordetbysafid($data, $previous_holding_id);
                    }
                    //update prop tax details
                    $proptaxupddtl = $this->model_prop_tax->updateproptaxBypropdetId($previous_holding_id);
                    if ($proptaxupddtl) {
                        //delete prop demand details
                        $propdemandupddtl = $this->model_prop_demand->deletepreviouspropdemandBypropdetId($previous_holding_id);
                        $saf_tax_list = $this->model_saf_tax->getsaftaxbysafId($saf_dtl_id); // all
                        //print_r($saf_tax_list);
                        foreach ($saf_tax_list as $key => $saf_tax) {
                            $saf_tax_id = $saf_tax['id'];

                            $prop_tax_id = $this->model_prop_tax->insertpropaxdetbysafid($previous_holding_id, $saf_tax['fy_mstr_id'], $saf_tax['qtr'], $saf_tax['arv'], $saf_tax['holding_tax'], $saf_tax['water_tax'], $saf_tax['education_cess'], $saf_tax['health_cess'], $saf_tax['latrine_tax'], $saf_tax['additional_tax'], $data['created_on']);
                            //echo $prop_tax_id;

                            $saf_demand_list = $this->model_saf_demand->getsaftaxnotpaidbyid($saf_dtl_id, $saf_tax_id); // all
                            //print_r($saf_demand_list);
                            foreach ($saf_demand_list as $key => $saf_demand) {

                                $prop_demand_id = $this->model_prop_demand->insrtpropdemand($previous_holding_id, $prop_tax_id, $saf_demand['fy_mstr_id'], $saf_demand['qtr'], $saf_demand['amount'], $saf_demand['balance'], $saf_demand['fine_tax'], $data['created_on'], $saf_demand['ward_mstr_id']);
                                //$prop_coll_id = $this->model_collection->insertpropcolldetbysafid($saf_dtl_id,$propdtl,$prop_demand_id,$data['created_on']);
                                //echo $prop_coll_id.'<br/>';
                            }
                        }
                        //get max fy yr and max quarter
                        $max_fy_id = $this->model_saf_tax->getmaxfyidbysafid($saf_dtl_id);
                        $max_qtr = $this->model_saf_tax->getmaxfyqtridbysafid($saf_dtl_id, $max_fy_id['max_fy_id']);
                        $saf_tx = $this->model_saf_tax->getallmaxfyqtridbysafid($saf_dtl_id, $max_fy_id['max_fy_id'], $max_qtr['max_qtr']);
                        $fy_yr = $this->model_fy_mstr->getFiyrByid($saf_tx['fy_mstr_id']);

                        //count ward
                        $data['ward_count'] = $this->model_prop_dtl->count_ward_by_wardid($data['saf_dtl']['ward_mstr_id']);
                        $sl_no = $data['ward_count']['ward_cnt'];
                        $sl_noo = $sl_no + 1;
                        $serial_no = str_pad($sl_noo, 4, "0", STR_PAD_LEFT);
                        //$memo_no = 'SAM/'.$data['form']['ward_no'].'/'.$serial_no.'/'.$data['fy_yr']['fy'];
                        //print_r($saf_tax);
                        $quartely_tax = $saf_tx['holding_tax'] + $saf_tx['water_tax'] + $saf_tx['education_cess'] + $saf_tx['health_cess'] + $saf_tx['latrine_tax'];
                        $memo_type = 'SAM';
                        $genmemo_last_id = $this->model_saf_memo_dtl->generatesafmemono($saf_dtl_id, $saf_tx['fy_mstr_id'], $saf_tx['qtr'], $saf_tx['arv'], $quartely_tax, $memo_type, $login_emp_details_id, $data['created_on']);
                        $memo_no = 'SAM/' . $ward_nmm . '/' . $genmemo_last_id . $serial_no . '/' . $fy_yr['fy'];
                        $updatememono = $this->model_saf_memo_dtl->updatememonoById($genmemo_last_id, $memo_no);
                        //$genmemo = $this->model_saf_memo_dtl->generatesafmemono($saf_dtl_id,$saf_tx['fy_mstr_id'],$saf_tx['qtr'],$saf_tx['arv'],$quartely_tax,$memo_no,$login_emp_details_id,$data['created_on']);
                        if ($updatelevelpending = $this->model_level_pending_dtl->updatelevelpendingById($data)) {
                            if ($insrtlevelpending = $this->model_level_pending_dtl->insrtlevelpendingdtl($data)) {
                                if ($updatesafpendingstts = $this->model_saf_dtl->update_saf_pending_status($data)) {
                                    ///applicant image stts
                                    $app_img_verify = $this->request->getVar('app_img_verify');
                                    $app_img_remarks = $this->request->getVar('app_img_remarks');
                                    $applicant_img_id = $this->request->getVar('applicant_img_id');
                                    if (isset($applicant_img_id)) {
                                        $app_img_len = sizeof($applicant_img_id);
                                        for ($iv = 0; $iv < $app_img_len; $iv++) {
                                            $data_up = [
                                                'applicant_img_id' => $applicant_img_id[$iv],
                                                'app_img_verify' => $app_img_verify[$iv],
                                                'app_img_remarks' => '',
                                                'emp_details_id' => $login_emp_details_id,
                                                'created_on' => date('Y-m-d H:i:s')
                                            ];
                                            $updateappimgdoc = $this->model_saf_doc_dtl->updateappimgdocById($data_up);
                                        }
                                    }
                                    ///applicant doc stts
                                    $app_doc_verify = $this->request->getVar('app_doc_verify');
                                    $app_doc_remarks = $this->request->getVar('app_doc_remarks');
                                    $applicant_doc_id = $this->request->getVar('applicant_doc_id');
                                    if (isset($applicant_doc_id)) {
                                        $app_doc_len = sizeof($applicant_doc_id);

                                        for ($ivn = 0; $ivn < $app_doc_len; $ivn++) {
                                            $data_u = [
                                                'applicant_doc_id' => $applicant_doc_id[$ivn],
                                                'app_doc_verify' => $app_doc_verify[$ivn],
                                                'app_doc_remarks' => '',
                                                'emp_details_id' => $login_emp_details_id,
                                                'created_on' => date('Y-m-d H:i:s')
                                            ];
                                            $updateappdoc = $this->model_saf_doc_dtl->updateappdocById($data_u);
                                        }
                                    }
                                    //$updateappdoc = $this->model_saf_doc_dtl->updateappdocById($data);
                                    $updatetrdoc = $this->model_saf_doc_dtl->updatetrdocById($data);
                                    $updateprdoc = $this->model_saf_doc_dtl->updateprdocById($data);

                                    $data_da = [
                                        'doc_verify_status' => 1,
                                        'doc_verify_date' => date('Y-m-d'),
                                        'doc_verify_emp_details_id' => $login_emp_details_id,
                                        'prop_dtl_id' => $previous_holding_id
                                    ];

                                    $updatdastatus = $this->model_saf_dtl->update_da_verify_status($data_da, $saf_dtl_id);
                                    return $this->response->redirect(base_url('ulb_mstr/da_eng_memo_receipt/' . md5($genmemo_last_id)));
                                }
                            }
                        }
                    }
                }
            }
            /**********/
        } else {
            return view('property/saf/saf_receive_view', $data);
        }
    }


    public function memo_receipt($id = null)
    {
        $data = (array)null;
        $data['id'] = $id;
        $Session = Session();

        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $ulb_mstr_id = $data['ulb_mstr_id'];
        $emp_mstr = $Session->get("emp_details");
        $path = base_url('citizenPaymentReceipt/memo_receipt/' . $ulb_mstr_id . '/' . $id);
        $data['ss'] = qrCodeGeneratorFun($path);
        $login_emp_details_id = $emp_mstr["id"];
        $sender_user_type_id = $emp_mstr["user_type_mstr_id"];
        $data['ulb'] = $this->model_ulb_mstr->getulb_list($data['ulb_mstr_id']);
        $data['form'] = $this->model_view_saf_receive_list->safreceivedetailbyid($id);
        //print_r($data['ulb']);

        $data['owner_list'] = $this->model_saf_owner_detail->ownerdetails($data['form']['saf_dtl_id']);
        $memo_type = 'FAM';
        $data['memo_no'] = $this->model_saf_memo_dtl->getmemonobysafid($data['form']['saf_dtl_id'], $memo_type);
        $data['holding_no'] = $this->model_prop_dtl->getholdingnobysafid($data['form']['saf_dtl_id']);
        //print_r($data['holding_no']);
        /****saf tax******/
        $max_fy_id = $this->model_saf_tax->getmaxfyidbysafid($data['form']['saf_dtl_id']);
        $max_qtr = $this->model_saf_tax->getmaxfyqtridbysafid($data['form']['saf_dtl_id'], $max_fy_id['max_fy_id']);
        $data['saf_tax'] = $this->model_saf_tax->getallmaxfyqtridbysafid($data['form']['saf_dtl_id'], $max_fy_id['max_fy_id'], $max_qtr['max_qtr']);
        /*********/
        /****prop tax******/
        $max_fy_id = $this->model_prop_tax->getmaxfyidbypropid($data['holding_no']['id']);
        $max_qtr = $this->model_prop_tax->getmaxfyqtridbypropid($data['holding_no']['id'], $max_fy_id['max_fy_id']);
        $data['prop_tax'] = $this->model_prop_tax->getallmaxfyqtridbypropid($data['holding_no']['id'], $max_fy_id['max_fy_id'], $max_qtr['max_qtr']);
        /*********/

        //print_r($data['prop_tax_dtl'] );

        //$data['prop_tax'] = $this->model_prop_tax->get_taxdl_bypropid($data['holding_no']['id']);
        $data['fy'] = $this->model_fy_mstr->getFiyrByid($data['prop_tax']['fy_mstr_id']);
        $data['ward'] = $this->model_ward_mstr->getdatabyid($data['form']['ward_mstr_id']);
        $data['form']['ward_no'] = $data['ward']['ward_no'];
        $data['old_ward'] = $this->model_ward_mstr->getdatabyid($data['holding_no']['ward_mstr_id']);
        $data['form']['old_ward_no'] = $data['old_ward']['ward_no'];
        // print_r($data['saf_tax']);


        return view('property/saf/memo_receipt', $data);
    }
    public function hmemo_receipt($id = null)
    {
        $data = (array)null;
        $data['id'] = $id;
        $Session = Session();

        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $ulb_mstr_id = $data['ulb_mstr_id'];
        $emp_mstr = $Session->get("emp_details");
        $path = base_url('citizenPaymentReceipt/hmemo_receipt/' . $ulb_mstr_id . '/' . $id);
        $data['ss'] = qrCodeGeneratorFun($path);

        $login_emp_details_id = $emp_mstr["id"];
        $sender_user_type_id = $emp_mstr["user_type_mstr_id"];
        $data['ulb'] = $this->model_ulb_mstr->getulb_list($data['ulb_mstr_id']);
        $data['form'] = $this->model_view_saf_receive_list->safreceivedetailbyid($id);
        //print_r($data['ulb']);

        $data['owner_list'] = $this->model_saf_owner_detail->ownerdetails($data['form']['saf_dtl_id']);
        $memo_type = 'FAM';
        $data['memo_no'] = $this->model_saf_memo_dtl->getmemonobysafid($data['form']['saf_dtl_id'], $memo_type);
        $data['holding_no'] = $this->model_prop_dtl->getholdingnobysafid($data['form']['saf_dtl_id']);

        /****saf tax******/
        $max_fy_id = $this->model_saf_tax->getmaxfyidbysafid($data['form']['saf_dtl_id']);
        $max_qtr = $this->model_saf_tax->getmaxfyqtridbysafid($data['form']['saf_dtl_id'], $max_fy_id['max_fy_id']);
        $data['saf_tax'] = $this->model_saf_tax->getallmaxfyqtridbysafid($data['form']['saf_dtl_id'], $max_fy_id['max_fy_id'], $max_qtr['max_qtr']);
        /*********/
        /****prop tax******/
        $max_fy_id = $this->model_prop_tax->getmaxfyidbypropid($data['holding_no']['id']);
        $max_qtr = $this->model_prop_tax->getmaxfyqtridbypropid($data['holding_no']['id'], $max_fy_id['max_fy_id']);
        $data['prop_tax'] = $this->model_prop_tax->getallmaxfyqtridbypropid($data['holding_no']['id'], $max_fy_id['max_fy_id'], $max_qtr['max_qtr']);
        /*********/
        $data['prop_tax_dtl'] = $this->model_prop_tax->getdetails_propdtlid($data['holding_no']['id']);
        foreach ($data['prop_tax_dtl'] as $key => $value) {
            $fy = $this->model_fy_mstr->getFiyrByid($value['fy_mstr_id']);
            $saftax = $this->model_saf_tax->getalltaxfyqtridbysafid($data['form']['saf_dtl_id'], $value['fy_mstr_id'], $value['qtr']);
            $data['prop_tax_dtl'][$key]['fyy'] = $fy['fy'];
            $data['prop_tax_dtl'][$key]['holding_tx'] = $saftax['holding_tax'];
        }

        //$data['prop_tax'] = $this->model_prop_tax->get_taxdl_bypropid($data['holding_no']['id']);
        $data['fy'] = $this->model_fy_mstr->getFiyrByid($data['prop_tax']['fy_mstr_id']);
        $data['ward'] = $this->model_ward_mstr->getdatabyid($data['form']['ward_mstr_id']);
        $data['form']['ward_no'] = $data['ward']['ward_no'];
        $data['old_ward'] = $this->model_ward_mstr->getdatabyid($data['holding_no']['old_ward_mstr_id']);
        $data['form']['old_ward_no'] = $data['old_ward']['ward_no'];
        //print_r($data['prop_tax']);


        return view('property/saf/hindi_memo_receipt', $data);
    }

    public function saf_generated_memo_list()
    {
        $data = (array)null;
        $Session = Session();
        $emp_mstr = $Session->get("emp_details");
        $ulb_dtl = $Session->get('ulb_dtl');
        $ulb_mstr_id = $ulb_dtl['ulb_mstr_id'];
        //Transaction Mode List
        $emp_mstr = $Session->get("emp_details");
        $user_type_mstr_id = $emp_mstr["user_type_mstr_id"];
        $login_emp_details_id = $emp_mstr["id"];

        if (in_array($user_type_mstr_id, [1, 2, 3])) {
            $wardList = $this->model_ward_mstr->getWardList(["ulb_mstr_id"=>$ulb_mstr_id]);
        } else {
            $wardList = $this->model_view_ward_permission->getPermittedWard($login_emp_details_id);
        }

        $data['wardList'] = $wardList;
        $imploded_ward_mstr_id = implode(', ', array_map(function ($entry) {
            return $entry['ward_mstr_id'];
        }, $data['wardList']));

        if ($this->request->getMethod() == 'post') {
            $ward = $this->request->getVar('ward_mstr_id');
            if ($ward != "All")
                $imploded_ward_mstr_id = $this->request->getVar('ward_mstr_id');
            $data['from_date'] = $this->request->getVar('from_date');
            $data['to_date'] = $this->request->getVar('to_date');
        } else {
            $data['from_date'] = date("Y-m-d");
            $data['to_date'] = date("Y-m-d");
        }
        $Session->set('from_date', $data['from_date']);
        $Session->set('to_date', $data['to_date']);
        $Session->set('fromWard', $imploded_ward_mstr_id);
        $Session->set('wardList', $data['wardList']);
        return $this->response->redirect(base_url('DocumentVerification/list_of_saf_generated_memo/'));
    }

    public function list_of_saf_generated_memo()
    {
        $data = (array)null;
        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");


        $data = arrFilterSanitizeString($this->request->getVar());
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $wardWhere = "";
        $wherAll = "";
        if (isset($data["ward_mstr_id"]) && isset($data["search_by_holding_no"]) && isset($data["search_by_saf_no"]) && isset($data["search_by_memo_no"])) {
            if ($data["ward_mstr_id"]!="" && $data["ward_mstr_id"]!="All") {
                $wardWhere = "AND tbl_saf_dtl.ward_mstr_id=".$data["ward_mstr_id"];
            }

            if ($data["search_by_memo_no"]!="") {
                $wherAll .= "AND tbl_saf_memo_dtl.memo_no='".$data["search_by_memo_no"]."'";
            } else if ($data["search_by_saf_no"]!="") {
                $wherAll .= "AND tbl_saf_dtl.saf_no='".$data["search_by_saf_no"]."'";
            } else if ($data["search_by_holding_no"]!="") {
                $wherAll .= "AND tbl_saf_memo_dtl.holding_no='".strtoupper($data["search_by_holding_no"])."'";
            }


        }

        $data["wardList"] = $Session->get('wardList');
        $sql = "SELECT
                    tbl_saf_memo_dtl.id,
                    ward_no,
                    tbl_saf_dtl.saf_no,
                    tbl_saf_dtl.assessment_type,
                    tbl_saf_memo_dtl.memo_no,
                    tbl_saf_memo_dtl.holding_no,
                    tbl_saf_owner_detail.owner_name,
                    tbl_saf_owner_detail.mobile_no,
                    tbl_saf_memo_dtl.created_on,
				    view_emp_details.emp_name
                FROM tbl_saf_memo_dtl
                INNER JOIN tbl_saf_dtl ON tbl_saf_dtl.id=tbl_saf_memo_dtl.saf_dtl_id ".$wardWhere.$wherAll."
                INNER JOIN view_ward_mstr on view_ward_mstr.id=tbl_saf_dtl.ward_mstr_id
                LEFT JOIN view_emp_details on view_emp_details.id = tbl_saf_memo_dtl.emp_details_id
                JOIN (
                    SELECT saf_dtl_id,
                    STRING_AGG(owner_name, ', ') AS owner_name,
                    STRING_AGG(mobile_no::TEXT, ', ') AS mobile_no
                    FROM tbl_saf_owner_detail WHERE status=1
                    GROUP BY saf_dtl_id
                ) AS tbl_saf_owner_detail ON tbl_saf_owner_detail.saf_dtl_id=tbl_saf_memo_dtl.saf_dtl_id
                WHERE tbl_saf_memo_dtl.memo_type='SAM' AND tbl_saf_memo_dtl.status=1
                ORDER BY tbl_saf_memo_dtl.created_on DESC";

                //print_r($sql);
        $result = $this->model_datatable->getDatatable($sql);
        $data['offset'] = $result['offset'];
        $data['posts'] = $result['result'];
        $data['memo_list'] = $data['posts'];
        $data['pager'] = $result['count'];
        $data['sql'] = base64_encode($sql);
        $data["daList"] = $this->db->query("select * from view_emp_details where user_mstr_lock_status = 0 AND user_type_id=6")->getResultArray();
        return view('property/saf/saf_generated_memo_list', $data);
    }

    public function list_of_saf_generated_memo_excel() {
        if($this->request->getMethod()=='post'){
			try{
                $data = arrFilterSanitizeString($this->request->getVar());
                // $sql = $this->PropReports->excelExportSAM($data);
                $sql = isset($data["encryptFrom"]) ? base64_decode($data["encryptFrom"])??$this->PropReports->excelExportSAM($data) : $this->PropReports->excelExportSAM($data);
                $result = $this->db->query($sql)->getResult('array');
                $filename = "writable\genexcel\ExcelExportSAM_".date('Y_m_d_H_I_s').".csv";
                $fp = fopen($filename, 'w');
  
                // Loop through file pointer and a line
                fputcsv($fp,array_keys($result[0]));
                foreach ($result as $fields) {
                    fputcsv($fp, $fields);
                }
                
                fclose($fp);
                return json_encode($filename);
                // $result = $this->db->query("SELECT generateCSVReports('".$sql."', 'excelExportSAM')");
                // $filename = $result->getFirstRow("array");
                // return json_encode($filename);
            } catch(Exception $e) {
                echo $e;
            }
        }
        

                //print_r($sql);
        //$result = $this->model_datatable->getDatatable($sql);
    }

    public function saf_back_to_citizen_list()
    {
        $data = (array)null;
        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
        $login_emp_details_id = $emp_mstr["id"];
        //print_r($emp_mstr);
        $data['wardList'] = $this->model_view_ward_permission->getPermittedWard($login_emp_details_id);

        $imploded_ward_mstr_id = implode(', ', array_map(function ($entry) {
            return $entry['ward_mstr_id'];
        }, $data['wardList']));


        if ($this->request->getMethod() == 'post') {

            $data['ward_mstr_id'] = $this->request->getVar('ward_mstr_id');
            $data['from_date'] = $this->request->getVar('from_date');
            $data['to_date'] = $this->request->getVar('to_date');
            if ($data['ward_mstr_id'] != "") {
                $where = " tbl_saf_dtl.ward_mstr_id=" . $data['ward_mstr_id'] . " and date(tbl_level_pending_dtl.forward_date)
                between '" . $data['from_date'] . "' and '" . $data['to_date'] . "' and tbl_saf_dtl.saf_pending_status=2 and tbl_saf_dtl.status=1 and tbl_level_pending_dtl.status=1 and tbl_level_pending_dtl.receiver_user_type_id=11 and tbl_level_pending_dtl.sender_user_type_id=10";
            } else {
                $where = " tbl_saf_dtl.ward_mstr_id in (" . $imploded_ward_mstr_id . ") and date(tbl_level_pending_dtl.forward_date)
                between '" . $data['from_date'] . "' and '" . $data['to_date'] . "' and tbl_saf_dtl.saf_pending_status=2 and tbl_saf_dtl.status=1 and tbl_level_pending_dtl.status=1 and tbl_level_pending_dtl.receiver_user_type_id=11 and tbl_level_pending_dtl.sender_user_type_id=10";
            }
        } else {
            $data['from_date'] = date('Y-m-d');
            $data['to_date'] = date('Y-m-d');
            $where = " tbl_saf_dtl.ward_mstr_id in (" . $imploded_ward_mstr_id . ") and date(tbl_level_pending_dtl.forward_date)
                between '" . $data['from_date'] . "' and '" . $data['to_date'] . "' and tbl_saf_dtl.saf_pending_status=2 and tbl_saf_dtl.status=1 and tbl_level_pending_dtl.status=1 and tbl_level_pending_dtl.receiver_user_type_id=11 and tbl_level_pending_dtl.sender_user_type_id=10";
        }

        $Session->set('where', $where);
        $Session->set('from_date', $data['from_date']);
        $Session->set('to_date', $data['to_date']);
        $Session->set('ward_mstr_id', $data['ward_mstr_id'] ?? NULL);
        $Session->set('wardList', $data['wardList']);

        return $this->response->redirect(base_url('DocumentVerification/list_of_saf_btc'));
    }

    public function list_of_saf_btc()
    {
        $data = (array)null;
        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];

        $data['wardList'] = $Session->get("wardList");
        //print_r($emp_mstr);
        $where = $Session->get('where');
        $sql = "SELECT tbl_saf_dtl.* , owner_name, guardian_name, mobile_no, ward_no
        FROM tbl_saf_dtl
        join view_saf_owner_detail on view_saf_owner_detail.saf_dtl_id=tbl_saf_dtl.id
        join view_ward_mstr on view_ward_mstr.id=tbl_saf_dtl.ward_mstr_id
		join tbl_level_pending_dtl on tbl_level_pending_dtl.saf_dtl_id=tbl_saf_dtl.id and tbl_level_pending_dtl.verification_status=2
        WHERE $where";
        //print_var($sql);
        $result = $this->model_datatable->getDatatable($sql);
        $data['posts'] = $result['result'];
        $data['inboxList'] = $data['posts'];
        $data['pager'] = $result['count'];
        $data['offset'] = $result['offset'];
        $data['from_date'] = $Session->get('from_date');
        $data['to_date'] = $Session->get('to_date');
        $data['ward_mstr_id'] = $Session->get('ward_mstr_id');

        return view('property/saf/saf_back_to_citizen_list', $data);
    }

    public function saf_document_verification_view($id)
    {
        $data = (array)null;
        $Session = Session();

        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");

        $login_emp_details_id = $emp_mstr["id"];
        $sender_user_type_id = $emp_mstr["user_type_mstr_id"];
        //$data['form'] = $this->model_view_saf_receive_list->safreceivedetailbyid($id);
        //print_r($data['form']);
        $data['form'] = $this->model_saf_dtl->Saf_details_md5($id);

        $data['owner_list'] = $this->model_saf_owner_detail->ownerdetails($data['form']['id']);
        $verify_status = '0';
        foreach ($data['owner_list'] as $key => $value) {
            $app_other_doc = 'applicant_image';
            $data['owner_list'][$key]['saf_owner_img_list'] = $this->model_saf_doc_dtl->get_ownerimgdetails_by_safid($data['form']['id'], $value['id'], $app_other_doc);
            $app_doc_type = "other";
            $data['owner_list'][$key]['saf_owner_doc_list'] = $this->model_view_saf_doc_dtl->safownerdocdetbyid($data['form']['id'], $value['id'], $app_doc_type);
        }

        $tr_doc_type = "transfer_mode";
        $data['prop_tr_mode_document'] = $this->model_view_saf_doc_dtl->uploaded_doc_list_by_safid($data['form']['id'], $tr_doc_type);
        $pr_doc_type = "property_type";
        $data['prop_pr_mode_document'] = $this->model_view_saf_doc_dtl->uploaded_doc_list_by_safid($data['form']['id'], $pr_doc_type);

        if ($data['form']['no_electric_connection'] == "t") {
            $pr_doc_type = "no_elect_connection";
            $data['no_electric_connection_mode_document'] = $this->model_view_saf_doc_dtl->uploaded_doc_list_by_safid($data['form']['id'], $pr_doc_type);
        }
        if ($data['form']['prop_type_mstr_id'] == 1) {
            $pr_doc_type = "super_structure_doc";
            $data['super_mode_document'] = $this->model_view_saf_doc_dtl->uploaded_doc_list_by_safid($data['form']['id'], $pr_doc_type);
            //print_r($data['flat_mode_document']);
        }
        if ($data['form']['prop_type_mstr_id'] == 3) {
            $pr_doc_type = "flat_doc";
            $data['flat_mode_document'] = $this->model_view_saf_doc_dtl->uploaded_doc_list_by_safid($data['form']['id'], $pr_doc_type);
            //print_r($data['flat_mode_document']);
        }

        /***************/
        //print_r($data['applicant_document']);
        $data['ward'] = $this->model_ward_mstr->getdatabyid($data['form']['ward_mstr_id']);
        $data['form']['ward_no'] = $data['ward']['ward_no'];
        $data['remark'] = $this->model_level_pending_dtl->backtocitizen_dl_remarks_by_saf_id($data['form']['id']);
        $data['form']['remarks'] = $data['remark']['remarks'];
        //$data['doc'] = $this->model_view_saf_doc_dtl->safdocListbysafid($id);
        //$data['tax_list'] = $this->model_saf_tax->tax_list($data['form']['id']);
        //$data['occupancy_detail'] = $this->model_saf_floor_details->occupancy_detail($data['form']['id']);
        //print_r($data['occupancy_detail']);

        return view('property/saf/saf_document_verification_view', $data);
    }

    public function saf_memo_view($id)
    {
        $data = (array)null;
        $Session = Session();

        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");

        $login_emp_details_id = $emp_mstr["id"];
        $sender_user_type_id = $emp_mstr["user_type_mstr_id"];
        $data['form'] = $this->model_view_saf_receive_list->safreceivedetailbyid($id);
        //print_r($data['form']);

        $data['owner_list'] = $this->model_saf_owner_detail->ownerdetails($data['form']['saf_dtl_id']);
        $data['memo_no'] = $this->model_saf_memo_dtl->getmemonobysafid($data['form']['saf_dtl_id']);
        $data['holding_no'] = $this->model_prop_dtl->getholdingnobysafid($data['form']['saf_dtl_id']);
        $data['prop_tax'] = $this->model_prop_tax->get_taxdl_bypropid($data['holding_no']['id']);
        $data['fy'] = $this->model_fy_mstr->getfyFromList($data['prop_tax']['fy_mstr_id']);
        //print_r($data['fy']);
        $data['applicant_image'] = $this->model_saf_doc_dtl->get_details_by_safid($data['form']['saf_dtl_id']);
        $app_doc_type = "other";
        $data['applicant_document'] = $this->model_view_saf_doc_dtl->safdocnamebydoctype($data['form']['saf_dtl_id'], $app_doc_type);
        $tr_doc_type = "transfer_mode";
        $data['prop_tr_mode_document'] = $this->model_view_saf_doc_dtl->safdocnamebydoctype($data['form']['saf_dtl_id'], $tr_doc_type);
        $pr_doc_type = "property_type";
        $data['prop_pr_mode_document'] = $this->model_view_saf_doc_dtl->safdocnamebydoctype($data['form']['saf_dtl_id'], $pr_doc_type);
        //print_r($data['applicant_document']);
        $data['ward'] = $this->model_ward_mstr->getdatabyid($data['form']['ward_mstr_id']);
        $data['form']['ward_no'] = $data['ward']['ward_no'];
        $data['doc'] = $this->model_view_saf_doc_dtl->safdocListbysafid($id);
        //print_r($data['doc']);

        return view('property/saf/saf_memo_view', $data);
    }

    public function forward_list()
    {
        $data = (array)null;
        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
        $login_emp_details_id = $emp_mstr["id"];

        $wardList = $this->model_view_ward_permission->getPermittedWard($login_emp_details_id);
        $data['wardList'] = $wardList;
        //print_r($emp_mstr);

        $sender_user_type_id = $emp_mstr["user_type_mstr_id"];
        $ward = "";

        $i = 0;
        foreach ($wardList as $key => $value) {
            if ($i == 0) {
                $ward = array($value['ward_mstr_id']);
            } else {
                array_push($ward, $value['ward_mstr_id']);
            }
            $i++;
        }

        helper(['form']);
        if ($this->request->getMethod() == 'post') {
            $data['ward_mstr_id'] = $this->request->getVar('ward_mstr_id');
            $data['from_date'] = $this->request->getVar('from_date');
            $data['to_date'] = $this->request->getVar('to_date');
            if ($data['ward_mstr_id'] != "") {
                $where = "ward_mstr_id=" . $data['ward_mstr_id'] . " AND date(created_on) >='" . $data['from_date'] . "'
				AND date(created_on) <='" . $data['to_date'] . "' AND sender_user_type_id='" . $sender_user_type_id . "'
				AND status='1' AND verification_status='0' ORDER BY id DESC";
            } else {
                $where = "ward_mstr_id in (" . implode(",", $ward) . ") AND date(created_on) >='" . $data['from_date'] . "'
				AND date(created_on) <='" . $data['to_date'] . "' AND sender_user_type_id='" . $sender_user_type_id . "'
				AND status='1' AND verification_status='0' ORDER BY id DESC";
            }
        } else {
            $data['from_date'] = date('Y-m-d');
            $data['to_date'] = date('Y-m-d');
            $where = "ward_mstr_id in (" . implode(",", $ward) . ") AND date(created_on) >='" . $data['from_date'] . "'
			AND date(created_on) <='" . $data['to_date'] . "' AND sender_user_type_id='" . $sender_user_type_id . "'
			AND status='1' AND verification_status='0' ORDER BY id DESC";
        }

        $Session->set('where', $where);
        return $this->response->redirect(base_url('DocumentVerification/Outbox_forward_list/'));
    }

    public function Outbox_forward_list()
    {
        $data = (array)null;
        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
        $login_emp_details_id = $emp_mstr["id"];

        $wardList = $this->model_view_ward_permission->getPermittedWard($login_emp_details_id);
        $data['wardList'] = $wardList;
        //print_r($emp_mstr);

        $sender_user_type_id = $emp_mstr["user_type_mstr_id"];
        $ward = "";

        $i = 0;
        foreach ($wardList as $key => $value) {
            if ($i == 0) {
                $ward = array($value['ward_mstr_id']);
            } else {
                array_push($ward, $value['ward_mstr_id']);
            }
            $i++;
        }

        helper(['form']);

        $where = $Session->get('where');
        $sql = "SELECT * FROM view_saf_receive_list WHERE $where";

        $result = $this->model_datatable->getDatatable($sql);
        $data['posts'] = $result['result'];

        $j = 0;
        foreach ($data['posts'] as $key => $value) {
            $previous_hold_id = $this->model_prop_dtl->getholdingnobysafid($value['saf_dtl_id']);
            $wardd = $this->model_ward_mstr->getdatabyid($value['ward_mstr_id']);
            $user_type_nm = $this->model_user_type_mstr->getdatabyid(md5($value['receiver_user_type_id']));
            $owner = $this->model_saf_owner_detail->ownerdetails($value['saf_dtl_id']);
            $j = 0;
            foreach ($owner as $keyy => $val) {
                //$ow[$key][$keyy]['owner']= $val["owner_name"];
                if ($j == 0) {
                    $data['posts'][$key]['owner_name'] = array($val["owner_name"]);
                    $data['posts'][$key]['mobile_no'] = array($val["mobile_no"]);
                } else {
                    array_push($data['posts'][$key]['owner_name'], $val["owner_name"]);
                    array_push($data['posts'][$key]['mobile_no'], $val["mobile_no"]);
                }
                $j++;
            }
            $data['posts'][$key]['ward_no'] = $wardd['ward_no'];
            $data['posts'][$key]['user_type'] = $user_type_nm['user_type'];
            $data['posts'][$key]['prop_holding_no'] = $previous_hold_id['holding_no'];
        }

        $data['outbox_forwardList'] = $data['posts'];
        $data['pager'] = $result['count'];
        return view('property/saf/saf_forward_list', $data);
    }

	public function outboxforwardlist()
    {
        $session = session();
        $ulb_mstr = getUlbDtl();
        $emp_mstr = $session->get("emp_details");
        //print_var($emp_mstr);
        $receiver_emp_details_id = $emp_mstr["user_type_mstr_id"];
        $login_emp_details_id = $emp_mstr["id"];

        $data = arrFilterSanitizeString($this->request->getVar());
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];

        $data['wardList'] = $this->model_view_ward_permission->getPermittedWardWithSession($login_emp_details_id);
        $permittedWard = array_column($data['wardList'], 'ward_mstr_id');
        $whereWard = " AND tbl_saf_dtl.ward_mstr_id IN (".implode(',', $permittedWard).")";
        $wherePropType = "";
        $whereAssessmentType = "";
        $whereSearchPrm = "";
        if (isset($data["ward_mstr_id"]) && isset($data["search_param"]) && isset($data["prop_type_mstr_id"]) && isset($data["assessment_type"])) {
            if ($data["assessment_type"]!="") {
                $whereAssessmentType = " AND tbl_saf_dtl.assessment_type='".$data["assessment_type"]."'";
            }
            if ($data["prop_type_mstr_id"]!="") {
                $wherePropType = " AND tbl_saf_dtl.prop_type_mstr_id IN (".$data["prop_type_mstr_id"].")";
            }
            if ($data["ward_mstr_id"]!="") {
                $whereWard = " AND tbl_saf_dtl.ward_mstr_id IN (".$data["ward_mstr_id"].")";
            }
            if ($data["search_param"]!="") {
                $whereSearchPrm = " AND (tbl_saf_dtl.saf_no ILIKE '%".$data["search_param"]."%'
                                        OR owner_dtl.owner_name ILIKE '%".$data["search_param"]."%'
                                        OR owner_dtl.mobile_no ILIKE '%".$data["search_param"]."%')";
            }
        }

        $sql = "SELECT
                    tbl_level_pending_dtl.id,
                    tbl_level_pending_dtl.saf_dtl_id,
                    tbl_prop_type_mstr.property_type,
                    view_ward_mstr.ward_no,
                    tbl_saf_dtl.saf_no,
                    owner_dtl.owner_name,
                    owner_dtl.mobile_no,
                    tbl_saf_dtl.assessment_type,
                    tbl_level_pending_dtl.forward_date,
                    tbl_level_pending_dtl.forward_time,
                    tbl_level_pending_dtl.remarks
                FROM tbl_level_pending_dtl
                INNER JOIN tbl_saf_dtl ON tbl_level_pending_dtl.saf_dtl_id = tbl_saf_dtl.id ".$whereWard."
                INNER JOIN tbl_prop_type_mstr ON tbl_prop_type_mstr.id=tbl_saf_dtl.prop_type_mstr_id
                INNER JOIN ( SELECT tbl_saf_owner_detail.saf_dtl_id,
                        string_agg(tbl_saf_owner_detail.owner_name::text, ', '::text) AS owner_name,
                        string_agg(tbl_saf_owner_detail.mobile_no::text, ', '::text) AS mobile_no
                    FROM tbl_saf_owner_detail
                    GROUP BY tbl_saf_owner_detail.saf_dtl_id
                ) owner_dtl ON owner_dtl.saf_dtl_id = tbl_level_pending_dtl.saf_dtl_id
                INNER JOIN view_ward_mstr ON view_ward_mstr.id = tbl_saf_dtl.ward_mstr_id
                WHERE
                     sender_emp_details_id=".$login_emp_details_id."
                    --AND tbl_level_pending_dtl.verification_status='1'
                    --AND doc_upload_status='1'
                    AND tbl_level_pending_dtl.status='1' ".$whereSearchPrm.$whereAssessmentType.$wherePropType."
                ORDER BY tbl_level_pending_dtl.id DESC";
        $result = $this->model_datatable->getDatatable($sql);
        $data['inboxList'] = isset($result['result']) ? $result['result'] : null;
        $data['pager'] = $result['count'];
        return view('property/saf/saf_forward_list', $data);
    }


    public function saf_forward_view($id)
    {
        $data = (array)null;
        $Session = Session();

        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");

        $login_emp_details_id = $emp_mstr["id"];
        $sender_user_type_id = $emp_mstr["user_type_mstr_id"];
        //$data['form'] = $this->model_view_saf_receive_list->safreceivedetailbyid($id);
        //print_r($data['form']);
        $data['form'] = $this->model_saf_dtl->Saf_details_md5($id);

        $data['owner_list'] = $this->model_saf_owner_detail->ownerdetails($data['form']['id']);
        $verify_status = '0';
        foreach ($data['owner_list'] as $key => $value) {
            $app_other_doc = 'applicant_image';
            $data['owner_list'][$key]['saf_owner_img_list'] = $this->model_saf_doc_dtl->get_ownerimgdetails_by_safid($data['form']['id'], $value['id'], $app_other_doc);
            $app_doc_type = "other";
            $data['owner_list'][$key]['saf_owner_doc_list'] = $this->model_view_saf_doc_dtl->safownerdocdetbyid($data['form']['id'], $value['id'], $app_doc_type);
        }

        $tr_doc_type = "transfer_mode";
        $data['prop_tr_mode_document'] = $this->model_view_saf_doc_dtl->uploaded_doc_list_by_safid($data['form']['id'], $tr_doc_type);
        $pr_doc_type = "property_type";
        $data['prop_pr_mode_document'] = $this->model_view_saf_doc_dtl->uploaded_doc_list_by_safid($data['form']['id'], $pr_doc_type);

        /***************/
        //print_r($data['applicant_document']);
        $data['ward'] = $this->model_ward_mstr->getdatabyid($data['form']['ward_mstr_id']);
        $data['form']['ward_no'] = $data['ward']['ward_no'];
        $data['remark'] = $this->model_level_pending_dtl->forward_remarks_by_saf_id($data['form']['id'], $sender_user_type_id);
        $data['form']['remarks'] = $data['remark']['remarks'];
        //$data['doc'] = $this->model_view_saf_doc_dtl->safdocListbysafid($id);
        $data['tax_list'] = $this->model_saf_tax->tax_list($data['form']['id']);
        $data['occupancy_detail'] = $this->model_saf_floor_details->occupancy_detail($data['form']['id']);
        //print_r($data['occupancy_detail']);

        return view('property/saf/saf_forward_view', $data);
    }

    public function verifyDocument($saf_dtl_id_MD5 = NULL)
    {
        if (strlen($saf_dtl_id_MD5) < 25) {
            return $this->response->redirect(base_url('err/page/'));
        }
        $data = (array)null;
        $session = session();
        $ulb_mstr = getUlbDtl();
        $emp_details = $session->get("emp_details");
        $login_emp_details_id = $emp_details["id"];

        $sender_emp_details_id = $login_emp_details_id;
        $basic_details_dt = $this->model_view_saf_dtl->getSafDtlByMD5SafDtlId($saf_dtl_id_MD5);
        $data = $basic_details_dt;
        $data["level_dtl"] = $this->model_level_pending_dtl->getLastRecord(["saf_dtl_id" => $data["saf_dtl_id"], "receiver_user_type_id" => 6]); //Propert Dealing Assisstant
        //print_var($data["level_dtl"]);
        if ($this->request->getMethod() == 'post') {
            $inputs = arrFilterSanitizeString($this->request->getVar());
            if (isset($inputs["btn_verify"])) {
                $input = [
                    "id" => $inputs["saf_doc_dtl_id"],
                    "verify_status" => 1, //Verified
                    "remarks" => "Verified",
                    "verified_by_emp_id" => $emp_details["id"],
                ];

                $this->model_saf_doc_dtl->verifyDocument($input);
                return $this->response->redirect(base_url("DocumentVerification/verifyDocument/" . $saf_dtl_id_MD5) . "#documen");
            }

            if (isset($inputs["btn_reject"])) {
                $input = [
                    "id" => $inputs["saf_doc_dtl_id"],
                    "verify_status" => 2, //Rejected
                    "remarks" => $inputs["remarks"],
                    "verified_by_emp_id" => $emp_details["id"],
                ];

                $this->model_saf_doc_dtl->verifyDocument($input);
                return $this->response->redirect(base_url("DocumentVerification/verifyDocument/" . $saf_dtl_id_MD5) . "#documen");
            }

            if (isset($inputs["btn_back_to_citizen"])) {

                $this->model_saf_dtl->update_saf_pending_status(["saf_dtl_id" => $data["saf_dtl_id"], "doc_verify_status" => 2]); //backtocitizen
                $leveldata = [
                    'id' => $data["level_dtl"]["id"],
                    'saf_dtl_id' => $data["saf_dtl_id"],
                    'sender_user_type_id' => 6,     //Propert Dealing Assisstant
                    'receiver_user_type_id' => 11, // Backoffice
                    'remarks' => $inputs["remarks"],
                    'sender_emp_details_id' => $sender_emp_details_id
                ];
                // $this->model_level_pending_dtl->sendBackToCitizen($leveldata);
                if ($this->model_level_pending_dtl->sendBackToCitizen($leveldata)) {
                    if (getenv('HTTP_CLIENT_IP'))
                    $ip_address = getenv('HTTP_CLIENT_IP');
                else if(getenv('HTTP_X_FORWARDED_FOR'))
                    $ip_address = getenv('HTTP_X_FORWARDED_FOR');
                else if(getenv('HTTP_X_FORWARDED'))
                    $ip_address = getenv('HTTP_X_FORWARDED');
                else if(getenv('HTTP_FORWARDED_FOR'))
                    $ip_address = getenv('HTTP_FORWARDED_FOR');
                else if(getenv('HTTP_FORWARDED'))
                   $ip_address = getenv('HTTP_FORWARDED');
                else if(getenv('REMOTE_ADDR'))
                    $ip_address = getenv('REMOTE_ADDR');
                else
                    $ip_address = 'UNKNOWN';

				$bub_fix = [
                    'saf_dtl_id' => $data["saf_dtl_id"],
                    'sender_user_type_id' => 6,
                    'receiver_user_type_id' => 11,
                    'forward_date' => "NOW()",
                    'forward_time' => "NOW()",
                    'created_on' => "NOW()",
                    'status' => 1,
                    'remarks' => $inputs["remarks"],
                    'sender_emp_details_id' => $sender_emp_details_id

                ];
                $this->model_level_pending_dtl->bugfix_level_pending($bub_fix);
				
                    $level_record = [
                        'saf_dtl_id' => $data["saf_dtl_id"],
                        'level_pending_dtl_id' => $data["level_dtl"]["id"],
                        'sender_user_type_id' => 6,     //Propert Dealing Assisstant
                        'sender_emp_details_id' => $sender_emp_details_id,
                        'sender_ip_address' => $ip_address, // Backoffice
                        'remarks' => $inputs["remarks"],
                        'status' => 1

                    ];

                    $this->model_level_pending_dtl->record_back_to_citizen($level_record);
                }
                flashToast("message", "Applicationn sent back to citizen successfully.");
                return $this->response->redirect(base_url("DocumentVerification/index/" . $saf_dtl_id_MD5));
            }

            if (isset($inputs["btn_generate_memo1"])) {
                $this->db->transBegin();
                $level_dtl = $data["level_dtl"];
                //$memo=$this->model_saf_memo_dtl->generate_assessment_memo($data['saf_dtl_id'], $emp_details["id"]);
                //$genmemo_last_id=$memo["generate_assessment_memo"];
                $input = [
                    "id" => $level_dtl["id"],
                    "saf_dtl_id" => $data["saf_dtl_id"],
                    "remarks" => $inputs["remarks"],
                    'receiver_emp_details_id' => $sender_emp_details_id
                ];
                if ($this->model_level_pending_dtl->updateLastRecord($input)) {

                    $input = [
                        "saf_dtl_id" => $data["saf_dtl_id"],
                        "sender_user_type_id" => 6,      // Property Dealing Asst.
                        //"receiver_user_type_id" => 5, // Agency tax collector
                        "receiver_user_type_id" => 7, // ULB tax collector
                        "forward_date" => date('Y-m-d'),
                        "forward_time" => date('H:i:s'),
                        "remarks" => $inputs["remarks"],
                        "created_on" => date('Y-m-d H:i:s'),
                        "doc_verify_status" => 1,
                        'sender_emp_details_id' => $sender_emp_details_id
                    ];
                    $this->model_level_pending_dtl->bugfix_level_pending($input); //Shashi
                    if ($this->model_level_pending_dtl->insrtlevelpendingdtl($input)) {
                        $input = [
                            "saf_dtl_id" => $data["saf_dtl_id"],
                            "doc_verify_status" => 1,
                            "doc_verify_emp_details_id" => $data["saf_dtl_id"],

                        ];
                        $this->model_saf_dtl->updateDocVerifyStatus($input);
                    }
                }

                if ($this->db->transStatus() === FALSE) {
                    $this->db->transRollback();
                    flashToast("message", "Oops, Something went wrong, Memo could not generate.");
                } else {
                    //$this->db->transRollback();
                    $this->db->transCommit();
                    flashToast("message", "SAM generated successfully.");
                    // return $this->response->redirect(base_url('DocumentVerification/verifyDocument/' . ($saf_dtl_id_MD5) . '?memo_id=' . md5($genmemo_last_id)));
                    return $this->response->redirect(base_url('DocumentVerification/inbox_list'));
                }

            }



            if (isset($_POST['btn_generate_memo'])) {
                $this->db->transBegin();

                $level_dtl = $data["level_dtl"];
                $created_on = date('Y-m-d H:i:s');
                $inputs = arrFilterSanitizeString($this->request->getVar());
                $input = ['saf_dtl_id' => $level_dtl['saf_dtl_id']];
                $saf_dtl = $this->model_saf_dtl->Saf_details($input);

                $ward_no = $this->model_ward_mstr->getdatabyid( $saf_dtl['ward_mstr_id']?? $saf_dtl['new_ward_mstr_id'])['ward_no'];
                $ward_no = str_pad($ward_no, 3, "0", STR_PAD_LEFT);

                /*
                1	SUPER STRUCTURE
                2	INDEPENDENT BUILDING
                3	FLATS / UNIT IN MULTI STORIED BUILDING
                4	VACANT LAND
                5	OCCUPIED PROPERTY
                */

                if ($saf_dtl['prop_type_mstr_id'] == '4') {
                    $usage_type_code = 'Z';
                } else {
                    $count_usage_type = $this->model_saf_floor_details->countRowBySafDtlId($level_dtl['saf_dtl_id']);
                    if ($count_usage_type['count_row'] > '1') {
                        $usage_type_code = 'X';
                    } else {
                        $data['usage_type'] = $this->model_saf_floor_details->getusagecodeBySafId($level_dtl['saf_dtl_id']);
                        $usage_type_code = $data['usage_type']['usage_code'];
                    }
                }

                if ($saf_dtl['prop_type_mstr_id'] == '4') {
                    $const_type_code = '0';
                } elseif ($saf_dtl['prop_type_mstr_id'] == '2' || $saf_dtl['prop_type_mstr_id'] == '3') {
                    $count_usage_type = $this->model_saf_floor_details->countRowBySafDtlId($level_dtl['saf_dtl_id']);
                    if ($count_usage_type['count_row'] > 1) {
                        $const_type_code = '4';
                    } else {
                        $const_type = $this->model_saf_floor_details->getDataBySafDtlId($level_dtl);
                        $const_type_code = $const_type[0]['const_type_mstr_id'];
                    }
                } elseif ($saf_dtl['prop_type_mstr_id'] == '1') {
                    $count_usage_type = $this->model_saf_floor_details->countRowBySafDtlId($level_dtl['saf_dtl_id']);
                    if ($count_usage_type['count_row'] > '1') {
                        $const_type_code = '8';
                    } else {
                        $const_type = $this->model_saf_floor_details->getDataBySafDtlIdd($level_dtl['saf_dtl_id']);
                        if ($const_type[0]['const_type_mstr_id'] == "1") {
                            $const_type_code = '5';
                        } else if ($const_type[0]['const_type_mstr_id'] == "2") {
                            $const_type_code = '6';
                        }
                        if ($const_type[0]['const_type_mstr_id'] == "3") {
                            $const_type_code = '7';
                        }
                    }
                }

                if ($saf_dtl['assessment_type'] == "Reassessment")
                {
                    //legacy prop insert
                    $previousPropDtl = $this->model_prop_dtl->getPropdetailsbyid($saf_dtl['previous_holding_id']);
                    if ($previousPropDtl['new_holding_no'] == '') {
                        $road_type = "000";
                        $sub_holding_no = '000';
                        $serial_no = $this->model_prop_dtl->count_ward_by_wardid($saf_dtl['ward_mstr_id'])['ward_cnt'];
                        $serial_no = str_pad(++$serial_no, 4, "0", STR_PAD_LEFT);
                        $generated_holding_no = $ward_no . $road_type . $serial_no . $sub_holding_no . $usage_type_code . $const_type_code;
                        $input = [
                            'prop_dtl_id' => $previousPropDtl['id'],
                            'new_holding_no' => $generated_holding_no
                        ];
                        $previousPropDtl["new_holding_no"] =$generated_holding_no;
                        $this->model_prop_dtl->updateUniqueHoldingNoInPropDtlByDocVerifyTime($input);
                    }


                    $input = [
                        'saf_dtl_id' => $level_dtl['saf_dtl_id'],
                        'prop_dtl_id' => $previousPropDtl['id'],
                        'updated_on' => $created_on,
                        'new_holding_no' => $generated_holding_no ?? $previousPropDtl['new_holding_no'],
                        'emp_details_id' => $login_emp_details_id,
                        'created_on' => date("Y-m-d"),
                    ];
                    if ($this->model_prop_dtl->updatePropDetByDocVerifyTime($input)) {
                        $prop_dtl_id = $previousPropDtl['id'];
                        $this->model_saf_memo_dtl->SAMDeactivatedBySafDtlId($level_dtl['saf_dtl_id']);
                        $this->model_prop_floor_details->floorDtlDeactivatedByPropDtlId($prop_dtl_id);
                        $this->model_prop_tax->taxDtlDeactivatedByPropDtlId($prop_dtl_id);

                        if ($saf_dtl['prop_type_mstr_id'] != 4) {
                            $this->model_prop_floor_details->insertpropfloordetbysafid($input, $prop_dtl_id);
                        }
                        $saf_tax_list = $this->model_saf_tax->getsaftaxbysafId($level_dtl['saf_dtl_id']); // all

                        foreach ($saf_tax_list as $key => $saf_tax) {
                            $saf_tax_details = $saf_tax;
                            $saf_tax_id = $saf_tax['id'];
                            $prop_tax_id = $this->model_prop_tax->insertpropaxdetbysafid($prop_dtl_id, $saf_tax['fy_mstr_id'], $saf_tax['qtr'], $saf_tax['arv'], $saf_tax['holding_tax'], $saf_tax['water_tax'], $saf_tax['education_cess'], $saf_tax['health_cess'], $saf_tax['latrine_tax'], $saf_tax['additional_tax'], $created_on, $saf_tax['fyear'],($saf_tax['quarterly_tax']??0));
                            $this->model_prop_demand->selectInsertBySafDtlAndSafTaxDtlId($level_dtl['saf_dtl_id'], $saf_tax_id, $prop_dtl_id, $prop_tax_id, $created_on);
                        }
                    }
                    $saf_tax_list = $this->model_saf_tax->getsaftaxbysafId($level_dtl['saf_dtl_id']); // all

                    foreach ($saf_tax_list as $key => $saf_tax) {
                        $saf_tax_details = $saf_tax;
                    }

                    $quartely_tax = $saf_tax_details['holding_tax'] + $saf_tax_details['water_tax'] + $saf_tax_details['education_cess'] + $saf_tax_details['health_cess'] + $saf_tax_details['latrine_tax'] + $saf_tax_details['additional_tax'];
                    $memo_type = 'SAM';
                    $genmemo_last_id = $this->model_saf_memo_dtl->generatesafmemono($level_dtl['saf_dtl_id'], $saf_tax_details['fy_mstr_id'],$saf_tax_details['fyear'], $saf_tax_details['qtr'], $saf_tax_details['arv'], $quartely_tax, $memo_type, $login_emp_details_id, $created_on, $previousPropDtl['new_holding_no'], $previousPropDtl['id']);

                    $fy_yr = $this->model_fy_mstr->getFiyrByid($saf_tax_details['fy_mstr_id']);
                    $ward_nmm = $this->model_ward_mstr->getWardNoByOldWardId($saf_dtl['new_ward_mstr_id'])['ward_no'];
                    $memo_no = 'SAM/' . $ward_nmm . '/' . $genmemo_last_id . $serial_no . '/' . $fy_yr['fy'];
                    $this->model_saf_memo_dtl->updatememonoById($genmemo_last_id, $memo_no, $fy_yr['fy']);
                }
                else {
                    $sub_holding_no = '000';
                    if ($saf_dtl['assessment_type'] == "Mutation") {
                        $previous_holding_id = $saf_dtl['previous_holding_id'];
                        $sub_holding_no = $this->model_saf_dtl->countSubHoldingNoByPreviousHoldingId($previous_holding_id)['count'];
                        $sub_holding_no = str_pad($sub_holding_no, 3, "0", STR_PAD_LEFT);
                    }
                    $road_type = "000";

                    // $serial_no = $this->model_prop_dtl->count_ward_by_wardid($saf_dtl['ward_mstr_id'])['ward_cnt'];
                    // $serial_no = str_pad(++$serial_no, 4, "0", STR_PAD_LEFT);
                    // $generated_holding_no = $ward_no . $road_type . $serial_no . $sub_holding_no . $usage_type_code . $const_type_code;


                    $increment = $this->model_prop_dtl->count_ward_by_wardid($saf_dtl['ward_mstr_id'])['ward_cnt'];
                    $serial_no = str_pad(++$increment, 4, "0", STR_PAD_LEFT);
                    $generated_holding_no = $ward_no . $road_type . $serial_no . $sub_holding_no . $usage_type_code . $const_type_code;
                    while($this->db->table('tbl_prop_dtl')->select('count(id) as count')->where("new_holding_no",$generated_holding_no)->get()->getFirstRow('array')['count']>0){
                        $increment += 1;
                        $serial = str_pad($increment, 4, "0", STR_PAD_LEFT) . "";
                        $generated_holding_no = $ward_no . $road_type . $serial_no . $sub_holding_no . $usage_type_code . $const_type_code;
                    }

                    $input = [
                        'saf_dtl_id' => $level_dtl['saf_dtl_id'],
                        'generated_holding_no' => $generated_holding_no,
                        'emp_details_id' => $emp_details["id"],
                        'created_on' => $created_on
                    ];
                    if ($prop_dtl_id = $this->model_prop_dtl->insertpropdetbysafid($input)) {
                        if ($saf_dtl['prop_type_mstr_id'] != 4) {
                            $this->model_prop_floor_details->insertpropfloordetbysafid($input, $prop_dtl_id);
                        }
                        $this->model_prop_owner_detail->insertpropownerdetbysafid($input, $prop_dtl_id);

                        $saf_tax_list = $this->model_saf_tax->getsaftaxbysafId($level_dtl['saf_dtl_id']); // all
                        foreach ($saf_tax_list as $key => $saf_tax) {
                            $saf_tax_details = $saf_tax;
                            $saf_tax_id = $saf_tax['id'];
                            $prop_tax_id = $this->model_prop_tax->insertpropaxdetbysafid($prop_dtl_id, $saf_tax['fy_mstr_id'], $saf_tax['qtr'], $saf_tax['arv'], $saf_tax['holding_tax'], $saf_tax['water_tax'], $saf_tax['education_cess'], $saf_tax['health_cess'], $saf_tax['latrine_tax'], $saf_tax['additional_tax'], $created_on, $saf_tax['fyear'], $saf_tax["quarterly_tax"]);
                            $this->model_prop_demand->selectInsertBySafDtlAndSafTaxDtlId($level_dtl['saf_dtl_id'], $saf_tax_id, $prop_dtl_id, $prop_tax_id, $created_on);
                        }
                    }

                    $quartely_tax = $saf_tax_details['quarterly_tax'];

                    $memo_type = 'SAM';
                    $genmemo_last_id = $this->model_saf_memo_dtl->generatesafmemono($level_dtl['saf_dtl_id'],  $saf_tax_details['fy_mstr_id'], $saf_tax_details['fyear'], $saf_tax_details['qtr'], $saf_tax_details['arv'], $quartely_tax, $memo_type, $emp_details["id"], $created_on, $generated_holding_no, $prop_dtl_id);

                    $fy_yr = $this->model_fy_mstr->getFiyrByid($saf_tax_details['fy_mstr_id']);
                    $ward_nmm = $this->model_ward_mstr->getWardNoByOldWardId($saf_dtl['new_ward_mstr_id'])['ward_no'];
                    $memo_no = 'SAM/' . $ward_nmm . '/' . $genmemo_last_id . $serial_no . '/' . $fy_yr['fy'];
                    $this->model_saf_memo_dtl->updatememonoById($genmemo_last_id, $memo_no, $fy_yr['fy']);

                    $input = [
                        'prop_dtl_id' => $prop_dtl_id,
                        'new_holding_no' => $generated_holding_no
                    ];
                    $this->model_prop_dtl->updateUniqueHoldingNoInPropDtlByDocVerifyTime($input);
                }

                ///////////////////////////////
                $input = [
                    "id" => $level_dtl["id"],
                    "saf_dtl_id" => $data["saf_dtl_id"],
                    "remarks" => $inputs["remarks"],
                    'receiver_emp_details_id' => $sender_emp_details_id
                ];
                if ($this->model_level_pending_dtl->updateLastRecord($input)) {

                    $input = [
                        "saf_dtl_id" => $data["saf_dtl_id"],
                        "sender_user_type_id" => 6,      // Property Dealing Asst.
                        "receiver_user_type_id" => 5, // Agency tax collector
                        "forward_date" => date('Y-m-d'),
                        "forward_time" => date('H:i:s'),
                        "remarks" => $inputs["remarks"],
                        "created_on" => date('Y-m-d H:i:s'),
                        "doc_verify_status" => 1,
                        'sender_emp_details_id' => $sender_emp_details_id
                    ];
                    $this->model_level_pending_dtl->bugfix_level_pending($input); //Shashi
                    if ($this->model_level_pending_dtl->insrtlevelpendingdtl($input)) {
                        $input = [
                            "saf_dtl_id" => $data["saf_dtl_id"],
                            "doc_verify_status" => 1,
                            "doc_verify_emp_details_id" => $data["saf_dtl_id"],

                        ];
                        $this->model_saf_dtl->updateDocVerifyStatus($input);
                    }
                }

                if ($this->db->transStatus() === FALSE) {
                    $this->db->transRollback();
                    flashToast("message", "Oops, Something went wrong, Memo could not generate.");
                } else {
                    $this->db->transCommit();
                    flashToast("message", "SAM generated successfully.");
                    return $this->response->redirect(base_url('DocumentVerification/verifyDocument/' . ($saf_dtl_id_MD5) . '?memo_id=' . md5($genmemo_last_id)));
                }
            }
        }


        $data['saf_owner_detail'] = $this->model_saf_owner_detail->getOwnerdtlBySAFId(["saf_dtl_id" => $data['saf_dtl_id']]);
        if ($data['has_previous_holding_no'] == 't' && $data['is_owner_changed'] == 't')
            $data['prev_saf_owner_detail'] = $this->model_prop_owner_detail->getPropOwnerDtlByProp_dtlId(['prop_dtl_id' => $data['previous_holding_id']]);

        $data["uploaded_doc_list"] = $this->model_saf_doc_dtl->getAllActiveDocuments($data['saf_dtl_id']);
        $data["memo"] = $this->model_saf_memo_dtl->getMemo(["memo_type" => "SAM", "saf_dtl_id" => $data["saf_dtl_id"]]);
        //print_var($data["uploaded_doc_list"]);
        $data["ulb_mstr_id"] = $ulb_mstr["ulb_mstr_id"];

        $data['basic_details_data'] = array(
            'saf_no' => isset($basic_details_dt['saf_no']) ? $basic_details_dt['saf_no'] : 'N/A',
            'apply_date' => isset($basic_details_dt['apply_date']) ? $basic_details_dt['apply_date'] : 'N/A',
            'ward_no' => isset($basic_details_dt['ward_no']) ? $basic_details_dt['ward_no'] : 'N/A',
            'new_holding_no' => isset($basic_details_dt['new_holding_no']) ? $basic_details_dt['new_holding_no'] : 'N/A',
            'new_ward_no' => isset($basic_details_dt['new_ward_no']) ? $basic_details_dt['new_ward_no'] : 'N/A',
            'holding_no' => isset($basic_details_dt['holding_no']) ? $basic_details_dt['holding_no'] : 'N/A',
            'assessment_type' => isset($basic_details_dt['assessment_type']) ? $basic_details_dt['assessment_type'] : 'N/A',
            'plot_no' => isset($basic_details_dt['plot_no']) ? $basic_details_dt['plot_no'] : 'N/A',
            'property_type' => isset($basic_details_dt['property_type']) ? $basic_details_dt['property_type'] : 'N/A',
            'area_of_plot' => isset($basic_details_dt['area_of_plot']) ? $basic_details_dt['area_of_plot'] : 'N/A',
            'ownership_type' => isset($basic_details_dt['ownership_type']) ? $basic_details_dt['ownership_type'] : 'N/A',
            'is_water_harvesting' => isset($basic_details_dt['is_water_harvesting']) ? $basic_details_dt['is_water_harvesting'] : 'N/A',
            'holding_type' => isset($basic_details_dt['holding_type']) ? $basic_details_dt['holding_type'] : 'N/A',
            'prop_address' => isset($basic_details_dt['prop_address']) ? $basic_details_dt['prop_address'] : 'N/A',
            'road_type' => isset($basic_details_dt['road_type']) ? $basic_details_dt['road_type'] : 'N/A',
            'zone_mstr_id' => isset($basic_details_dt['zone_mstr_id']) ? $basic_details_dt['zone_mstr_id'] : 'N/A',
            'entry_type' => isset($basic_details_dt['entry_type']) ? $basic_details_dt['entry_type'] : 'N/A',
            'flat_registry_date' => isset($basic_details_dt['flat_registry_date']) ? $basic_details_dt['flat_registry_date'] : 'N/A',
            'created_on' => isset($basic_details_dt['created_on']) ? $basic_details_dt['created_on'] : 'N/A',
            'prop_type_mstr_id' => isset($basic_details_dt['prop_type_mstr_id']) ? $basic_details_dt['prop_type_mstr_id'] : 'N/A',
            'appartment_name' => isset($basic_details['apartment_name']) ? $basic_details_dt['apartment_name'] : 'N/A',
            'apt_code' => isset($basic_details_dt['apt_code']) ? $basic_details_dt['apt_code'] : 'N/A',
            'prop_type' => 'saf'

        );
        $data['level']=$this->model_level_pending_dtl->getAllLevelDtl($data['saf_dtl_id']);
        // print_var($data['level']);
        // return;
        return view('property/Saf/verifyDocument', $data);
    }

    public function verifyDocumentNew($saf_dtl_id_MD5 = NULL)
    {
        if (strlen($saf_dtl_id_MD5) < 25) {
            return $this->response->redirect(base_url('err/page/'));
        }
        $data = (array)null;
        $session = session();
        $ulb_mstr = getUlbDtl();
        $emp_details = $session->get("emp_details");
        $login_emp_details_id = $emp_details["id"];

        $sender_emp_details_id = $login_emp_details_id;
        $basic_details_dt = $this->model_view_saf_dtl->getSafDtlByMD5SafDtlId($saf_dtl_id_MD5);
        $data = $basic_details_dt;
        $data["level_dtl"] = $this->model_level_pending_dtl->getLastRecord(["saf_dtl_id" => $data["saf_dtl_id"], "receiver_user_type_id" => 6]); //Propert Dealing Assisstant
        //print_var($data["level_dtl"]);
        $checkAgencyData = $this->model_field_verification_dtl->getulbdatabysafid($data["saf_dtl_id"], 'AGENCY TC');
        if ($this->request->getMethod() == 'post') {
            $inputs = arrFilterSanitizeString($this->request->getVar());
            if (isset($inputs["btn_verify"])) {
                $input = [
                    "id" => $inputs["saf_doc_dtl_id"],
                    "verify_status" => 1, //Verified
                    "remarks" => "Verified",
                    "verified_by_emp_id" => $emp_details["id"],
                ];

                $this->model_saf_doc_dtl->verifyDocument($input);
                return $this->response->redirect(base_url("DocumentVerification/verifyDocumentNew/" . $saf_dtl_id_MD5) . "#documen");
            }

            if (isset($inputs["btn_reject"])) {
                $input = [
                    "id" => $inputs["saf_doc_dtl_id"],
                    "verify_status" => 2, //Rejected
                    "remarks" => $inputs["remarks"],
                    "verified_by_emp_id" => $emp_details["id"],
                ];

                $this->model_saf_doc_dtl->verifyDocument($input);
                return $this->response->redirect(base_url("DocumentVerification/verifyDocumentNew/" . $saf_dtl_id_MD5) . "#documen");
            }

            if (isset($inputs["btn_back_to_citizen"])) {

                $this->model_saf_dtl->update_saf_pending_status(["saf_dtl_id" => $data["saf_dtl_id"], "doc_verify_status" => 2]); //backtocitizen
                $leveldata = [
                    'id' => $data["level_dtl"]["id"],
                    'saf_dtl_id' => $data["saf_dtl_id"],
                    'sender_user_type_id' => 6,     //Propert Dealing Assisstant
                    'receiver_user_type_id' => 11, // Backoffice
                    'remarks' => $inputs["remarks"],
                    'sender_emp_details_id' => $sender_emp_details_id
                ];
                // $this->model_level_pending_dtl->sendBackToCitizen($leveldata);
                if ($this->model_level_pending_dtl->sendBackToCitizen($leveldata)) {
                    if (getenv('HTTP_CLIENT_IP'))
                    $ip_address = getenv('HTTP_CLIENT_IP');
                else if(getenv('HTTP_X_FORWARDED_FOR'))
                    $ip_address = getenv('HTTP_X_FORWARDED_FOR');
                else if(getenv('HTTP_X_FORWARDED'))
                    $ip_address = getenv('HTTP_X_FORWARDED');
                else if(getenv('HTTP_FORWARDED_FOR'))
                    $ip_address = getenv('HTTP_FORWARDED_FOR');
                else if(getenv('HTTP_FORWARDED'))
                   $ip_address = getenv('HTTP_FORWARDED');
                else if(getenv('REMOTE_ADDR'))
                    $ip_address = getenv('REMOTE_ADDR');
                else
                    $ip_address = 'UNKNOWN';

				$bub_fix = [
                    'saf_dtl_id' => $data["saf_dtl_id"],
                    'sender_user_type_id' => 6,
                    'receiver_user_type_id' => 11,
                    'forward_date' => "NOW()",
                    'forward_time' => "NOW()",
                    'created_on' => "NOW()",
                    'status' => 1,
                    'remarks' => $inputs["remarks"],
                    'sender_emp_details_id' => $sender_emp_details_id

                ];
                $this->model_level_pending_dtl->bugfix_level_pending($bub_fix);
                    
					$level_record = [
                        'saf_dtl_id' => $data["saf_dtl_id"],
                        'level_pending_dtl_id' => $data["level_dtl"]["id"],
                        'sender_user_type_id' => 6,     //Propert Dealing Assisstant
                        'sender_emp_details_id' => $sender_emp_details_id,
                        'sender_ip_address' => $ip_address, // Backoffice
                        'remarks' => $inputs["remarks"],
                        'status' => 1

                    ];

                    $this->model_level_pending_dtl->record_back_to_citizen($level_record);
                }
                flashToast("message", "Applicationn sent back to citizen successfully.");
                return $this->response->redirect(base_url("DocumentVerification/inbox_list_new/" . $saf_dtl_id_MD5));
            }

            if (isset($inputs["btn_generate_memo1"])) {
                $this->db->transBegin();
                $level_dtl = $data["level_dtl"];
                $memo=$this->model_saf_memo_dtl->generate_assessment_memo($data['saf_dtl_id'], $emp_details["id"]);
                $genmemo_last_id=$memo["generate_assessment_memo"];
                $input = [
                    "id" => $level_dtl["id"],
                    "saf_dtl_id" => $data["saf_dtl_id"],
                    "remarks" => $inputs["remarks"],
                    'receiver_emp_details_id' => $sender_emp_details_id
                ];
                if ($this->model_level_pending_dtl->updateLastRecord($input)) {

                    $input = [
                        "saf_dtl_id" => $data["saf_dtl_id"],
                        "sender_user_type_id" => 6,      // Property Dealing Asst.
                        "receiver_user_type_id" => 5, // Agency tax collector
                       // "receiver_user_type_id" => 7, // ULB tax collector
                        "forward_date" => date('Y-m-d'),
                        "forward_time" => date('H:i:s'),
                        "remarks" => $inputs["remarks"],
                        "created_on" => date('Y-m-d H:i:s'),
                        "doc_verify_status" => 1,
                        'sender_emp_details_id' => $sender_emp_details_id
                    ];
                    if($checkAgencyData && !$data["doc_verify_status"]){
                        $input["receiver_user_type_id"] = 7;// ULB tax collector
                    }
					$this->model_level_pending_dtl->bugfix_level_pending($input); //Shashi
                    if ($this->model_level_pending_dtl->insrtlevelpendingdtl($input)) {
                        $input = [
                            "saf_dtl_id" => $data["saf_dtl_id"],
                            "doc_verify_status" => 1,
                            "doc_verify_emp_details_id" => $data["saf_dtl_id"],

                        ];
                        $this->model_saf_dtl->updateDocVerifyStatus($input);
                    }
                }

                if ($this->db->transStatus() === FALSE) {
                    $this->db->transRollback();
                    flashToast("message", "Oops, Something went wrong, Memo could not generate.");
                } else {
                    //$this->db->transRollback();
                    $this->db->transCommit();
                    flashToast("message", "SAM generated successfully.");
                    // return $this->response->redirect(base_url('DocumentVerification/verifyDocument/' . ($saf_dtl_id_MD5) . '?memo_id=' . md5($genmemo_last_id)));
                    return $this->response->redirect(base_url('DocumentVerification/inbox_list_new'));
                }

            }



            if (isset($_POST['btn_generate_memo'])) {
                $this->db->transBegin();

                $level_dtl = $data["level_dtl"];
                $created_on = date('Y-m-d H:i:s');
                $inputs = arrFilterSanitizeString($this->request->getVar());
                $input = ['saf_dtl_id' => $level_dtl['saf_dtl_id']];
                $saf_dtl = $this->model_saf_dtl->Saf_details($input);

                $ward_no = $this->model_ward_mstr->getdatabyid( $saf_dtl['ward_mstr_id']?? $saf_dtl['new_ward_mstr_id'])['ward_no'];
                $ward_no = str_pad($ward_no, 3, "0", STR_PAD_LEFT);

                /*
                1   SUPER STRUCTURE
                2   INDEPENDENT BUILDING
                3   FLATS / UNIT IN MULTI STORIED BUILDING
                4   VACANT LAND
                5   OCCUPIED PROPERTY
                */

                if ($saf_dtl['prop_type_mstr_id'] == '4') {
                    $usage_type_code = 'Z';
                } else {
                    $count_usage_type = $this->model_saf_floor_details->countRowBySafDtlId($level_dtl['saf_dtl_id']);
                    if ($count_usage_type['count_row'] > '1') {
                        $usage_type_code = 'X';
                    } else {
                        $data['usage_type'] = $this->model_saf_floor_details->getusagecodeBySafId($level_dtl['saf_dtl_id']);
                        $usage_type_code = $data['usage_type']['usage_code'];
                    }
                }

                if ($saf_dtl['prop_type_mstr_id'] == '4') {
                    $const_type_code = '0';
                } elseif ($saf_dtl['prop_type_mstr_id'] == '2' || $saf_dtl['prop_type_mstr_id'] == '3') {
                    $count_usage_type = $this->model_saf_floor_details->countRowBySafDtlId($level_dtl['saf_dtl_id']);
                    if ($count_usage_type['count_row'] > 1) {
                        $const_type_code = '4';
                    } else {
                        $const_type = $this->model_saf_floor_details->getDataBySafDtlId($level_dtl);
                        $const_type_code = $const_type[0]['const_type_mstr_id'];
                    }
                } elseif ($saf_dtl['prop_type_mstr_id'] == '1') {
                    $count_usage_type = $this->model_saf_floor_details->countRowBySafDtlId($level_dtl['saf_dtl_id']);
                    if ($count_usage_type['count_row'] > '1') {
                        $const_type_code = '8';
                    } else {
                        $const_type = $this->model_saf_floor_details->getDataBySafDtlIdd($level_dtl['saf_dtl_id']);
                        if ($const_type[0]['const_type_mstr_id'] == "1") {
                            $const_type_code = '5';
                        } else if ($const_type[0]['const_type_mstr_id'] == "2") {
                            $const_type_code = '6';
                        }
                        if ($const_type[0]['const_type_mstr_id'] == "3") {
                            $const_type_code = '7';
                        }
                    }
                }
                
                if ($saf_dtl['assessment_type'] == "Reassessment")
                {
                    //legacy prop insert
                    $previousPropDtl = $this->model_prop_dtl->getPropdetailsbyid($saf_dtl['previous_holding_id']);
                    if ($previousPropDtl['new_holding_no'] == '') {
                        $road_type = "000";
                        $sub_holding_no = '000';
                        $serial_no = $this->model_prop_dtl->count_ward_by_wardid($saf_dtl['ward_mstr_id'])['ward_cnt'];
                        $serial_no = str_pad(++$serial_no, 4, "0", STR_PAD_LEFT);
                        $generated_holding_no = $ward_no . $road_type . $serial_no . $sub_holding_no . $usage_type_code . $const_type_code;
                        $input = [
                            'prop_dtl_id' => $previousPropDtl['id'],
                            'new_holding_no' => $generated_holding_no
                        ];
                        $previousPropDtl["new_holding_no"] =$generated_holding_no;
                        $this->model_prop_dtl->updateUniqueHoldingNoInPropDtlByDocVerifyTime($input);
                    }


                    $input = [
                        'saf_dtl_id' => $level_dtl['saf_dtl_id'],
                        'prop_dtl_id' => $previousPropDtl['id'],
                        'updated_on' => $created_on,
                        'new_holding_no' => $generated_holding_no ?? $previousPropDtl['new_holding_no'],
                        'emp_details_id' => $login_emp_details_id,
                        'created_on' => date("Y-m-d"),
                    ];
                    if ($this->model_prop_dtl->updatePropDetByDocVerifyTime($input)) {
                        
                        $prop_dtl_id = $previousPropDtl['id'];
                        $this->model_saf_memo_dtl->SAMDeactivatedBySafDtlId($level_dtl['saf_dtl_id']);
                        $this->model_prop_floor_details->floorDtlDeactivatedByPropDtlId($prop_dtl_id);
                        $this->model_prop_tax->taxDtlDeactivatedByPropDtlId($prop_dtl_id);
                        
                        if ($saf_dtl['prop_type_mstr_id'] != 4) {
                            $this->model_prop_floor_details->insertpropfloordetbysafid($input, $prop_dtl_id);
                        }
                        $saf_tax_list = $this->model_saf_tax->getsaftaxbysafId($level_dtl['saf_dtl_id']); // all

                        foreach ($saf_tax_list as $key => $saf_tax) {
                            $saf_tax_details = $saf_tax;
                            $saf_tax_id = $saf_tax['id'];
                            $prop_tax_id = $this->model_prop_tax->insertpropaxdetbysafid($prop_dtl_id, $saf_tax['fy_mstr_id'], $saf_tax['qtr'], $saf_tax['arv'], $saf_tax['holding_tax'], $saf_tax['water_tax'], $saf_tax['education_cess'], $saf_tax['health_cess'], $saf_tax['latrine_tax'], $saf_tax['additional_tax'], $created_on, $saf_tax['fyear'], ($saf_tax['quarterly_tax']??0));
                            $this->model_prop_demand->selectInsertBySafDtlAndSafTaxDtlId($level_dtl['saf_dtl_id'], $saf_tax_id, $prop_dtl_id, $prop_tax_id, $created_on);
                        }
                    }
                    $saf_tax_list = $this->model_saf_tax->getsaftaxbysafId($level_dtl['saf_dtl_id']); // all

                    foreach ($saf_tax_list as $key => $saf_tax) {
                        $saf_tax_details = $saf_tax;
                    }

                    $quartely_tax = $saf_tax_details['holding_tax'] + $saf_tax_details['water_tax'] + $saf_tax_details['education_cess'] + $saf_tax_details['health_cess'] + $saf_tax_details['latrine_tax'] + $saf_tax_details['additional_tax'];
                    $memo_type = 'SAM';
                    $genmemo_last_id = $this->model_saf_memo_dtl->generatesafmemono($level_dtl['saf_dtl_id'], $saf_tax_details['fy_mstr_id'], $saf_tax_details['fyear'], $saf_tax_details['qtr'], $saf_tax_details['arv'], $quartely_tax, $memo_type, $login_emp_details_id, $created_on, $previousPropDtl['new_holding_no'], $previousPropDtl['id']);

                    $fy_yr = $this->model_fy_mstr->getFiyrByid($saf_tax_details['fy_mstr_id']);
                    $ward_nmm = $this->model_ward_mstr->getWardNoByOldWardId($saf_dtl['new_ward_mstr_id'])['ward_no'];
                    $memo_no = 'SAM/' . $ward_nmm . '/' . $genmemo_last_id . $serial_no . '/' . $fy_yr['fy'];
                    $this->model_saf_memo_dtl->updatememonoById($genmemo_last_id, $memo_no, $fy_yr['fy']);
                }
                else {
                    $sub_holding_no = '000';
                    if ($saf_dtl['assessment_type'] == "Mutation") {
                        $previous_holding_id = $saf_dtl['previous_holding_id'];
                        $sub_holding_no = $this->model_saf_dtl->countSubHoldingNoByPreviousHoldingId($previous_holding_id)['count'];
                        $sub_holding_no = str_pad($sub_holding_no, 3, "0", STR_PAD_LEFT);
                    }
                    $road_type = "000";

                    // $serial_no = $this->model_prop_dtl->count_ward_by_wardid($saf_dtl['ward_mstr_id'])['ward_cnt'];
                    // $serial_no = str_pad(++$serial_no, 4, "0", STR_PAD_LEFT);
                    // $generated_holding_no = $ward_no . $road_type . $serial_no . $sub_holding_no . $usage_type_code . $const_type_code;

                    $increment = $this->model_prop_dtl->count_ward_by_wardid($saf_dtl['ward_mstr_id'])['ward_cnt'];
                    $serial_no = str_pad(++$increment, 4, "0", STR_PAD_LEFT);
                    $generated_holding_no = $ward_no . $road_type . $serial_no . $sub_holding_no . $usage_type_code . $const_type_code;
                    while($this->db->table('tbl_prop_dtl')->select('count(id) as count')->where("new_holding_no",$generated_holding_no)->get()->getFirstRow('array')['count']>0){
                        $increment += 1;
                        $serial = str_pad($increment, 4, "0", STR_PAD_LEFT) . "";
                        $generated_holding_no = $ward_no . $road_type . $serial_no . $sub_holding_no . $usage_type_code . $const_type_code;
                    }

                    $input = [
                        'saf_dtl_id' => $level_dtl['saf_dtl_id'],
                        'generated_holding_no' => $generated_holding_no,
                        'emp_details_id' => $emp_details["id"],
                        'created_on' => $created_on
                    ];
                    if ($prop_dtl_id = $this->model_prop_dtl->insertpropdetbysafid($input)) {
                        if ($saf_dtl['prop_type_mstr_id'] != 4) {
                            $this->model_prop_floor_details->insertpropfloordetbysafid($input, $prop_dtl_id);
                        }
                        $this->model_prop_owner_detail->insertpropownerdetbysafid($input, $prop_dtl_id);

                        $saf_tax_list = $this->model_saf_tax->getsaftaxbysafId($level_dtl['saf_dtl_id']); // all
                        foreach ($saf_tax_list as $key => $saf_tax) {
                            $saf_tax_details = $saf_tax;
                            $saf_tax_id = $saf_tax['id'];
                            $prop_tax_id = $this->model_prop_tax->insertpropaxdetbysafid($prop_dtl_id, $saf_tax['fy_mstr_id'], $saf_tax['qtr'], $saf_tax['arv'], $saf_tax['holding_tax'], $saf_tax['water_tax'], $saf_tax['education_cess'], $saf_tax['health_cess'], $saf_tax['latrine_tax'], $saf_tax['additional_tax'], $created_on, $saf_tax['fyear'], $saf_tax["quarterly_tax"]);
                            $this->model_prop_demand->selectInsertBySafDtlAndSafTaxDtlId($level_dtl['saf_dtl_id'], $saf_tax_id, $prop_dtl_id, $prop_tax_id, $created_on);
                        }
                    }

                    $quartely_tax = $saf_tax_details['quarterly_tax'];

                    $memo_type = 'SAM';
                    $genmemo_last_id = $this->model_saf_memo_dtl->generatesafmemono($level_dtl['saf_dtl_id'],  $saf_tax_details['fy_mstr_id'], $saf_tax_details['fyear'], $saf_tax_details['qtr'], $saf_tax_details['arv'], $quartely_tax, $memo_type, $emp_details["id"], $created_on, $generated_holding_no, $prop_dtl_id);

                    $fy_yr = $this->model_fy_mstr->getFiyrByid($saf_tax_details['fy_mstr_id']);
                    $ward_nmm = $this->model_ward_mstr->getWardNoByOldWardId($saf_dtl['new_ward_mstr_id'])['ward_no'];
                    $memo_no = 'SAM/' . $ward_nmm . '/' . $genmemo_last_id . $serial_no . '/' . $fy_yr['fy'];
                    $this->model_saf_memo_dtl->updatememonoById($genmemo_last_id, $memo_no, $fy_yr['fy']);

                    $input = [
                        'prop_dtl_id' => $prop_dtl_id,
                        'new_holding_no' => $generated_holding_no
                    ];
                    $this->model_prop_dtl->updateUniqueHoldingNoInPropDtlByDocVerifyTime($input);
                }

                ///////////////////////////////
                $input = [
                    "id" => $level_dtl["id"],
                    "saf_dtl_id" => $data["saf_dtl_id"],
                    "remarks" => $inputs["remarks"],
                    'receiver_emp_details_id' => $sender_emp_details_id
                ];
                if ($this->model_level_pending_dtl->updateLastRecord($input)) {
                    $input = [
                        "saf_dtl_id" => $data["saf_dtl_id"],
                        "sender_user_type_id" => 6,      // Property Dealing Asst.
                        "receiver_user_type_id" => 5, // Agency tax collector
                        "forward_date" => date('Y-m-d'),
                        "forward_time" => date('H:i:s'),
                        "remarks" => $inputs["remarks"],
                        "created_on" => date('Y-m-d H:i:s'),
                        "doc_verify_status" => 1,
                        'sender_emp_details_id' => $sender_emp_details_id
                    ];
                    if($checkAgencyData && !$data["doc_verify_status"]){
                        $input["receiver_user_type_id"] = 7;// ULB tax collector
                    }
                    $this->model_level_pending_dtl->bugfix_level_pending($input); //Shashi
                    if ($this->model_level_pending_dtl->insrtlevelpendingdtl($input)) {
                        $input = [
                            "saf_dtl_id" => $data["saf_dtl_id"],
                            "doc_verify_status" => 1,
                            "doc_verify_emp_details_id" => $data["saf_dtl_id"],

                        ];
                        $this->model_saf_dtl->updateDocVerifyStatus($input);
                    }
                }
               

                if ($this->db->transStatus() === FALSE) {
                    $this->db->transRollback();
                    flashToast("message", "Oops, Something went wrong, Memo could not generate.");
                } else {
                    $this->db->transCommit();
                    flashToast("message", "SAM generated successfully.");
                    return $this->response->redirect(base_url('DocumentVerification/verifyDocumentNew/' . ($saf_dtl_id_MD5) . '?memo_id=' . md5($genmemo_last_id)));
                }
            }
        }


        $data['saf_owner_detail'] = $this->model_saf_owner_detail->getOwnerdtlBySAFId(["saf_dtl_id" => $data['saf_dtl_id']]);
        if ($data['has_previous_holding_no'] == 't' && $data['is_owner_changed'] == 't')
            $data['prev_saf_owner_detail'] = $this->model_prop_owner_detail->getPropOwnerDtlByProp_dtlId(['prop_dtl_id' => $data['previous_holding_id']]);

        $data["uploaded_doc_list"] = $this->model_saf_doc_dtl->getAllActiveDocuments($data['saf_dtl_id']);
        $data["memo"] = $this->model_saf_memo_dtl->getMemo(["memo_type" => "SAM", "saf_dtl_id" => $data["saf_dtl_id"]]);
        //print_var($data["uploaded_doc_list"]);
        $data["ulb_mstr_id"] = $ulb_mstr["ulb_mstr_id"];

        $data['basic_details_data'] = array(
            'saf_no' => isset($basic_details_dt['saf_no']) ? $basic_details_dt['saf_no'] : 'N/A',
            'apply_date' => isset($basic_details_dt['apply_date']) ? $basic_details_dt['apply_date'] : 'N/A',
            'ward_no' => isset($basic_details_dt['ward_no']) ? $basic_details_dt['ward_no'] : 'N/A',
            'new_holding_no' => isset($basic_details_dt['new_holding_no']) ? $basic_details_dt['new_holding_no'] : 'N/A',
            'new_ward_no' => isset($basic_details_dt['new_ward_no']) ? $basic_details_dt['new_ward_no'] : 'N/A',
            'holding_no' => isset($basic_details_dt['holding_no']) ? $basic_details_dt['holding_no'] : 'N/A',
            'assessment_type' => isset($basic_details_dt['assessment_type']) ? $basic_details_dt['assessment_type'] : 'N/A',
            'plot_no' => isset($basic_details_dt['plot_no']) ? $basic_details_dt['plot_no'] : 'N/A',
            'property_type' => isset($basic_details_dt['property_type']) ? $basic_details_dt['property_type'] : 'N/A',
            'area_of_plot' => isset($basic_details_dt['area_of_plot']) ? $basic_details_dt['area_of_plot'] : 'N/A',
            'ownership_type' => isset($basic_details_dt['ownership_type']) ? $basic_details_dt['ownership_type'] : 'N/A',
            'is_water_harvesting' => isset($basic_details_dt['is_water_harvesting']) ? $basic_details_dt['is_water_harvesting'] : 'N/A',
            'holding_type' => isset($basic_details_dt['holding_type']) ? $basic_details_dt['holding_type'] : 'N/A',
            'prop_address' => isset($basic_details_dt['prop_address']) ? $basic_details_dt['prop_address'] : 'N/A',
            'road_type' => isset($basic_details_dt['road_type']) ? $basic_details_dt['road_type'] : 'N/A',
            'zone_mstr_id' => isset($basic_details_dt['zone_mstr_id']) ? $basic_details_dt['zone_mstr_id'] : 'N/A',
            'entry_type' => isset($basic_details_dt['entry_type']) ? $basic_details_dt['entry_type'] : 'N/A',
            'flat_registry_date' => isset($basic_details_dt['flat_registry_date']) ? $basic_details_dt['flat_registry_date'] : 'N/A',
            'created_on' => isset($basic_details_dt['created_on']) ? $basic_details_dt['created_on'] : 'N/A',
            'prop_type_mstr_id' => isset($basic_details_dt['prop_type_mstr_id']) ? $basic_details_dt['prop_type_mstr_id'] : 'N/A',
            'appartment_name' => isset($basic_details['apartment_name']) ? $basic_details_dt['apartment_name'] : 'N/A',
            'apt_code' => isset($basic_details_dt['apt_code']) ? $basic_details_dt['apt_code'] : 'N/A',
            'prop_type' => 'saf'

        );
        $data['level']=$this->model_level_pending_dtl->getAllLevelDtl($data['saf_dtl_id']);
        // print_var($data['level']);
        // return;
        return view('property/Saf/verifyDocument', $data);
    }

    public function showAllDocumentsToDa($saf_dtl_id_MD5 = NULL)
    {
        if (strlen($saf_dtl_id_MD5) < 25) {
            return $this->response->redirect(base_url('err/page/'));
        }
        $data = (array)null;
        $session = session();
        $ulb_mstr = $session->get("ulb_dtl");
        $emp_details = $session->get("emp_details");
        $login_emp_details_id = $emp_details["id"];

        $sender_emp_details_id = $login_emp_details_id;
        $basic_details_dt = $this->model_view_saf_dtl->getSafDtlByMD5SafDtlId($saf_dtl_id_MD5);
        $data = $basic_details_dt;
        $data["level_dtl"] = $this->model_level_pending_dtl->getLastRecord(["saf_dtl_id" => $data["saf_dtl_id"], "receiver_user_type_id" => 6]); //Propert Dealing Assisstant



        $data['saf_owner_detail'] = $this->model_saf_owner_detail->getOwnerdtlBySAFId(["saf_dtl_id" => $data['saf_dtl_id']]);
        if ($data['has_previous_holding_no'] == 't' && $data['is_owner_changed'] == 't')
            $data['prev_saf_owner_detail'] = $this->model_prop_owner_detail->getPropOwnerDtlByProp_dtlId(['prop_dtl_id' => $data['previous_holding_id']]);

        $data["uploaded_doc_list"] = $this->model_saf_doc_dtl->getAllDocuments($data['saf_dtl_id']);
        $data["memo"] = $this->model_saf_memo_dtl->getMemo(["memo_type" => "SAM", "saf_dtl_id" => $data["saf_dtl_id"]]);
        //print_var($data["uploaded_doc_list"]);
        $data["ulb_mstr_id"] = $ulb_mstr["ulb_mstr_id"];

        $data['basic_details_data'] = array(
            'saf_no' => isset($basic_details_dt['saf_no']) ? $basic_details_dt['saf_no'] : 'N/A',
            'apply_date' => isset($basic_details_dt['apply_date']) ? $basic_details_dt['apply_date'] : 'N/A',
            'ward_no' => isset($basic_details_dt['ward_no']) ? $basic_details_dt['ward_no'] : 'N/A',
            'new_holding_no' => isset($basic_details_dt['new_holding_no']) ? $basic_details_dt['new_holding_no'] : 'N/A',
            'new_ward_no' => isset($basic_details_dt['new_ward_no']) ? $basic_details_dt['new_ward_no'] : 'N/A',
            'holding_no' => isset($basic_details_dt['holding_no']) ? $basic_details_dt['holding_no'] : 'N/A',
            'assessment_type' => isset($basic_details_dt['assessment_type']) ? $basic_details_dt['assessment_type'] : 'N/A',
            'plot_no' => isset($basic_details_dt['plot_no']) ? $basic_details_dt['plot_no'] : 'N/A',
            'property_type' => isset($basic_details_dt['property_type']) ? $basic_details_dt['property_type'] : 'N/A',
            'area_of_plot' => isset($basic_details_dt['area_of_plot']) ? $basic_details_dt['area_of_plot'] : 'N/A',
            'ownership_type' => isset($basic_details_dt['ownership_type']) ? $basic_details_dt['ownership_type'] : 'N/A',
            'is_water_harvesting' => isset($basic_details_dt['is_water_harvesting']) ? $basic_details_dt['is_water_harvesting'] : 'N/A',
            'holding_type' => isset($basic_details_dt['holding_type']) ? $basic_details_dt['holding_type'] : 'N/A',
            'prop_address' => isset($basic_details_dt['prop_address']) ? $basic_details_dt['prop_address'] : 'N/A',
            'road_type' => isset($basic_details_dt['road_type']) ? $basic_details_dt['road_type'] : 'N/A',
            'zone_mstr_id' => isset($basic_details_dt['zone_mstr_id']) ? $basic_details_dt['zone_mstr_id'] : 'N/A',
            'entry_type' => isset($basic_details_dt['entry_type']) ? $basic_details_dt['entry_type'] : 'N/A',
            'flat_registry_date' => isset($basic_details_dt['flat_registry_date']) ? $basic_details_dt['flat_registry_date'] : 'N/A',
            'created_on' => isset($basic_details_dt['created_on']) ? $basic_details_dt['created_on'] : 'N/A',
            'prop_type_mstr_id' => isset($basic_details_dt['prop_type_mstr_id']) ? $basic_details_dt['prop_type_mstr_id'] : 'N/A',
            'appartment_name' => isset($basic_details['apartment_name']) ? $basic_details_dt['apartment_name'] : 'N/A',
            'apt_code' => isset($basic_details_dt['apt_code']) ? $basic_details_dt['apt_code'] : 'N/A',
            'prop_type' => 'saf'

        );
        // print_var($data);
        // return;
        return view('property/Saf/showAllDocumentsToDa', $data);
    }

    public function verifySpecialDocument($prop_dtl_id = NULL, $owner_id = NULL, $prop_doc_id = NULL)
    {
        //for new pdf format
        // $prop_dtls = $this->model_prop_dtl->getHoldingPdfDetails($prop_dtl_id);

        $data = (array)null;
        $session = session();
        $ulb_mstr = $session->get("ulb_dtl");
        $emp_details = $session->get("emp_details");
        $login_emp_details_id = $emp_details["id"];


        //should use inner join for multiple document upload
        if ($prop_dtl_id != NULL) {
            // $data['owner_details_list'] = $this->model_prop_dtl->getOwnerDetailByOwnerId($owner_id); //prop dtl id
            $data['prop_dtl'] = $this->model_prop_dtl->getPropdetailsbyid($prop_dtl_id);
            $data["sepcial_doc_dtl_list"] = $this->model_prop_dtl->getSpecialDocDataPropDtlId($prop_dtl_id); //
        }
        // print_var($data);
        // return;

        if ($this->request->getMethod() == 'post') {
            $inputs = arrFilterSanitizeString($this->request->getVar());
            // print_var($inputs);
            // return;
            if (isset($inputs["btn_verify"])) {

                $input = [
                    "prop_doc_id" => $inputs["prop_doc_id"],
                    "verify_status" => 1, //Verified
                    "verified_by_emp_details_id" => $login_emp_details_id,
                    "verified_on" => "now()",
                    "remarks" => ""
                ];

                $this->model_prop_dtl->verifySpecialDocument($input);
                //update to actual table
                if($inputs['other_doc']=='gender_document'){
                    // $updateInput = [
                    //     "gender"=>$inputs['gender_value']
                    // ];
                    $sql_cond = "gender='".$inputs['gender_value']."'";

                }
                if($inputs['other_doc']=='dob_document'){
                    // $updateInput = [
                    //     "dob"=>$inputs['dob_value']
                    // ];
                    $sql_cond = "dob='".$inputs['dob_value']."'";

                }
                if($inputs['other_doc']=='handicaped_document'){
                    // $updateInput = [
                    //     "is_specially_abled"=>$inputs['handicapped_value']
                    // ];
                    $sql_cond = "is_specially_abled='".$inputs['handicapped_value']."'";

                }
                if($inputs['other_doc']=='armed_force_document'){
                    // $updateInput = [
                    //     "is_armed_force"=>$inputs['armed_value']
                    // ];
                    $sql_cond = "is_armed_force='".$inputs['armed_value']."'";

                }

               $this->model_prop_owner_detail->UpdateOwnerSpecialData($inputs['prop_owner_details_id'],$sql_cond);
            //    die;
                flashToast("message", "Document Verified Successfully !");
                return $this->response->redirect(base_url("DocumentVerification/verifySpecialDocument/".$prop_dtl_id."/".$owner_id."/".$prop_doc_id));
            }

            if (isset($inputs["btn_reject"])) {

                $input = [
                    "prop_doc_id" => $inputs["prop_doc_id"],
                    "verify_status" => 2, //rejected
                    "verified_by_emp_details_id" => $login_emp_details_id,
                    "verified_on" => "now()",
                    "remarks" => $inputs['remarks']
                ];

                $this->model_prop_dtl->verifySpecialDocument($input);
                flashToast("message", "Document Rejected !");

                return $this->response->redirect(base_url("DocumentVerification/verifySpecialDocument/".$prop_dtl_id."/".$owner_id."/".$prop_doc_id));
            }
        }

        // print_var($data);
        // return;
        return view('property/Saf/verifySpecialDocument', $data);
    }
}
