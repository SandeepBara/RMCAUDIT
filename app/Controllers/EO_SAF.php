<?php

namespace App\Controllers;
use CodeIgniter\Controller;
use App\Controllers\SAF\SAFHelper;
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
use App\Models\model_govt_saf_floor_dtl;
use App\Models\model_saf_floor_arv_dtl;
use App\Models\model_prop_dtl;
use App\Models\model_prop_owner_detail;
use App\Models\model_prop_floor_details;
use App\Models\model_saf_tax;
use App\Models\model_prop_tax;
use App\Models\model_prop_tax_print;
use App\Models\model_saf_demand;
use App\Models\model_prop_demand;
use App\Models\model_fy_mstr;
use App\Models\model_prop_type_mstr;
use App\Models\model_road_type_mstr;
use App\Models\model_view_saf_floor_details;
use App\Models\model_field_verification_dtl;
use App\Models\model_field_verification_floor_details;

use App\Models\model_ulb_mstr;
use App\Models\model_usage_type_mstr;
use App\Models\model_usage_type_dtl;
use App\Models\model_occupancy_type_mstr;
use App\Models\model_const_type_mstr;
use App\Models\model_arr_old_building_mstr;
use App\Models\model_arr_building_mstr;
use App\Models\model_arr_vacant_mstr;
use App\Models\model_view_saf_dtl_demand;
use App\Models\model_view_ward_permission;
use App\Models\model_penalty_dtl;
use App\Models\model_datatable;

use App\Models\model_g_saf;
use App\Models\model_govt_saf_officer_dtl;
use App\Models\model_govt_doc_dtl;
use App\Models\model_govt_saf_level_pending_dtl;
use App\Models\model_govt_saf_tax_dtl;
use App\Models\model_view_saf_dtl;
use App\Models\ObjectionModel;
use App\Controllers\Reports\PropReports;
use App\Models\model_prop_saf_deactivation;
use Config\Services;
use Exception;

class EO_SAF extends AlphaController
{
    protected $db;
    protected $dbSystem;
    protected $model_saf_dtl;
    protected $model_saf_floor_details;
    protected $model_saf_floor_arv_dtl;
    protected $model_field_verification_dtl;
    protected $model_field_verification_floor_details;
    protected $model_saf_tax;
    protected $model_prop_tax;
    protected $model_prop_tax_print;
    protected $model_saf_demand;
    protected $model_prop_demand;

    protected $model_ward_mstr;
    protected $model_prop_dtl;
    protected $model_fy_mstr;
    protected $model_ulb_mstr;
    protected $model_usage_type_mstr;
    protected $model_usage_type_dtl;
    protected $model_occupancy_type_mstr;
    protected $model_const_type_mstr;
    protected $model_arr_old_building_mstr;
    protected $model_arr_building_mstr;
    protected $model_arr_vacant_mstr;
    protected $model_view_saf_dtl_demand;
    protected $model_view_ward_permission;
    protected $model_penalty_dtl;
    protected $model_level_pending_dtl;
    protected $model_saf_memo_dtl;
    protected $model_datatable;
    protected $model_g_saf;
    protected $model_govt_saf_officer_dtl;
    protected $model_govt_doc_dtl;
    protected $model_govt_saf_tax_dtl;
    protected $model_prop_floor_details;
    protected $model_govt_saf_level_pending_dtl;
    protected $model_prop_owner_detail;
    protected $model_view_saf_dtl;
    protected $model_view_saf_receive_list;
    protected $ObjectionModel;
    protected $PropReports;
    protected $model_govt_saf_floor_dtl;
    protected $model_prop_saf_deactivation;

    public function __construct()
    {
		/*ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);*/
        parent::__construct();
        helper(['db_helper', 'form', 'utility_helper']);
        if ($db_name = dbConfig("property")) {
            $this->db = db_connect($db_name);
        }
        if ($db_system = dbSystem()) {
            $this->dbSystem = db_connect($db_system);
        }
        $this->PropReports = new PropReports();
        $this->model_ward_mstr = new model_ward_mstr($this->dbSystem);
        $this->model_emp_dtl_permission = new model_emp_dtl_permission($this->dbSystem);
        $this->model_fy_mstr = new model_fy_mstr($this->dbSystem);
        $this->model_view_ward_permission = new model_view_ward_permission($this->dbSystem);
        $this->model_view_saf_receive_list = new model_view_saf_receive_list($this->db);
        $this->model_view_saf_doc_dtl = new model_view_saf_doc_dtl($this->db);
        $this->model_level_pending_dtl = new model_level_pending_dtl($this->db);
        $this->model_saf_owner_detail = new model_saf_owner_detail($this->db);
        $this->model_saf_doc_dtl = new model_saf_doc_dtl($this->db);
        $this->model_saf_dtl = new model_saf_dtl($this->db);
        $this->model_saf_memo_dtl = new model_saf_memo_dtl($this->db);
        $this->model_saf_floor_details = new model_saf_floor_details($this->db);
        $this->model_saf_floor_arv_dtl = new model_saf_floor_arv_dtl($this->db);
        $this->model_prop_dtl = new model_prop_dtl($this->db);
        $this->model_prop_owner_detail = new model_prop_owner_detail($this->db);
        $this->model_prop_floor_details = new model_prop_floor_details($this->db);
        $this->model_saf_tax = new model_saf_tax($this->db);
        $this->model_prop_tax = new model_prop_tax($this->db);
        $this->model_prop_tax_print = new model_prop_tax_print($this->db);
        $this->model_saf_demand = new model_saf_demand($this->db);
        $this->model_prop_demand = new model_prop_demand($this->db);
        $this->model_prop_type_mstr = new model_prop_type_mstr($this->db);
        $this->model_road_type_mstr = new model_road_type_mstr($this->db);
        $this->model_view_saf_floor_details = new model_view_saf_floor_details($this->db);
        $this->model_field_verification_dtl = new model_field_verification_dtl($this->db);
        $this->model_field_verification_floor_details = new model_field_verification_floor_details($this->db);

        $this->model_ulb_mstr = new model_ulb_mstr($this->dbSystem);
        $this->model_usage_type_mstr = new model_usage_type_mstr($this->db);
        $this->model_usage_type_dtl = new model_usage_type_dtl($this->db);
        $this->model_occupancy_type_mstr = new model_occupancy_type_mstr($this->db);
        $this->model_const_type_mstr = new model_const_type_mstr($this->db);
        $this->model_arr_old_building_mstr = new model_arr_old_building_mstr($this->db);
        $this->model_arr_building_mstr = new model_arr_building_mstr($this->db);
        $this->model_arr_vacant_mstr = new model_arr_vacant_mstr($this->db);
        $this->model_view_saf_dtl_demand = new model_view_saf_dtl_demand($this->db);
        $this->model_penalty_dtl = new model_penalty_dtl($this->db);
        $this->model_datatable = new model_datatable($this->db);


        $this->model_g_saf = new model_g_saf($this->db);
        $this->model_govt_saf_officer_dtl = new model_govt_saf_officer_dtl($this->db);
        $this->model_govt_doc_dtl = new model_govt_doc_dtl($this->db);
        $this->model_govt_saf_level_pending_dtl = new model_govt_saf_level_pending_dtl($this->db);
        $this->model_govt_saf_tax_dtl = new model_govt_saf_tax_dtl($this->db);
        $this->model_view_saf_dtl = new model_view_saf_dtl($this->db);
        $this->ObjectionModel = new ObjectionModel($this->db);
        $this->model_govt_saf_floor_dtl = new model_govt_saf_floor_dtl($this->db);
        $this->model_prop_saf_deactivation = new model_prop_saf_deactivation($this->db);
    }

    function __destruct() {
		$this->db->close();
		$this->dbSystem->close();
	}

    public function index()
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
                $where = "ward_mstr_id=" . $data['ward_mstr_id'] . " AND date(forward_date) between '" . $data['from_date'] . "' 
				AND '" . $data['to_date'] . "' AND receiver_user_type_id=10 
				AND verification_status=0 AND view_saf_receive_list.status=1 ORDER BY id DESC";
            } else {
                $where = "ward_mstr_id in (" . $imploded_ward_mstr_id . ") AND date(forward_date) between '" . $data['from_date'] . "' 
				AND '" . $data['to_date'] . "' AND receiver_user_type_id=10 
				AND verification_status=0 AND view_saf_receive_list.status=1 ORDER BY id DESC";
            }
        } else {
            $data['from_date'] = date('Y-m-d');
            $data['to_date'] = date('Y-m-d');
            $where = "ward_mstr_id in (" . $imploded_ward_mstr_id . ") AND date(forward_date) between '" . $data['from_date'] . "' 
			AND '" . $data['to_date'] . "' AND receiver_user_type_id=10 
			AND verification_status=0 AND view_saf_receive_list.status=1 ORDER BY id DESC";
        }

        $Session->set('where', $where);
        $Session->set('from_date', $data['from_date']);
        $Session->set('to_date', $data['to_date']);
        $Session->set('ward_mstr_id', $data['ward_mstr_id'] ?? NULL);
        $Session->set('wardList', $data['wardList']);

        return $this->response->redirect(base_url('EO_SAF/inbox_list'));
    }

    public function inbox_list()
	{
        $session = session();
        $ulb_mstr = $session->get("ulb_dtl");
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
                    tbl_level_pending_dtl.remarks
                FROM tbl_level_pending_dtl
                INNER JOIN tbl_saf_dtl ON tbl_level_pending_dtl.saf_dtl_id = tbl_saf_dtl.id AND tbl_saf_dtl.status=1 AND tbl_saf_dtl.apply_date<='2022-08-05' ".$whereWard."
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
        return view('property/saf/eo_saf_list', $data);            
	}

    public function inbox_list_new()
    {
        $session = session();
        $ulb_mstr = $session->get("ulb_dtl");
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
                    tbl_level_pending_dtl.remarks
                FROM tbl_level_pending_dtl
                INNER JOIN tbl_saf_dtl ON tbl_level_pending_dtl.saf_dtl_id = tbl_saf_dtl.id AND tbl_saf_dtl.status=1 AND tbl_saf_dtl.apply_date>'2022-08-05' ".$whereWard."
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
        $data['offset'] = $result['offset'];
        return view('property/saf/eo_saf_list_new', $data);            
    }

    public function inbox_list2()
    {
        $data = (array)null;
        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];

        $data['wardList'] = $Session->get("wardList");
        //print_r($emp_mstr);
        $where = $Session->get('where');
        $sql = "SELECT view_saf_receive_list.*
        FROM view_saf_receive_list
        WHERE $where";

        if ($result = $this->model_datatable->getDatatable($sql)) {
            $data['posts'] = $result['result'];
            $data['inboxList'] = $data['posts'];
            $data['pager'] = $result['count'];
            $data['from_date'] = $Session->get('from_date');
            $data['to_date'] = $Session->get('to_date');
            $data['ward_mstr_id'] = $Session->get('ward_mstr_id');
        }
        return view('property/saf/eo_saf_list2', $data);
    }

    // Some changes by hayat
    public function view2($saf_dtl_id_MD5) {
        
        $data = (array)null;
        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");

        $emp_mstr = $Session->get("emp_details");

        $login_emp_details_id = $emp_mstr["id"];
        $basic_details_dt = $this->model_view_saf_dtl->getSafDtlByMD5SafDtlId($saf_dtl_id_MD5);
        $data = $basic_details_dt;
        
        $saf_dtl_id = $data["saf_dtl_id"];

        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $data['previous_prop_dtl'] = $this->model_prop_dtl->getholdingnobysafid($data['saf_dtl_id']);

        $data['saf_owner_detail'] = $this->model_saf_owner_detail->getOwnerdtlBySAFId(['saf_dtl_id' => $data['saf_dtl_id']]);

        // applicant img & document
        foreach ($data['saf_owner_detail'] as $key => $owner_detail) {
            $input = [
                'saf_dtl_id' => $data['saf_dtl_id'],
                'saf_owner_dtl_id' => $owner_detail['id'],
            ];
            $data['saf_owner_detail'][$key]['applicant_img_dtl'] = $this->model_saf_doc_dtl->getApplicantImgBySafDtlAndSafOwnerDtlId($input);
            $data['saf_owner_detail'][$key]['applicant_doc_dtl'] = $this->model_saf_doc_dtl->getApplicantDocBySafDtlAndSafOwnerDtlId($input);
        }

        if ($data['has_previous_holding_no'] == 't' && $data['is_owner_changed'] == 't')
            $data['prev_saf_owner_detail'] = $this->model_prop_owner_detail->getPropOwnerDtlByProp_dtlId(['prop_dtl_id' => $data['previous_holding_id']]);
        //print_var($data['prev_saf_owner_detail']);

        $data['Verification'] = $this->model_field_verification_dtl->getAllFieldVerification($input);
        $data['Memo'] = $this->model_saf_memo_dtl->getAllMemo($input);
        $data['saf_tax_list'] = $this->model_saf_tax->getSafTaxDtlBySafDtlId(['saf_dtl_id' => $data['saf_dtl_id']]);
        $data['form'] = $this->model_level_pending_dtl->getLastRecord(['saf_dtl_id' => $data['saf_dtl_id'], 'receiver_user_type_id' => 10]); //Property Executive Officer

        if ($this->request->getMethod() == 'post') {
            
            $safHelper = new SAFHelper($this->db);
            $inputs = arrFilterSanitizeString($this->request->getVar());
            $inputs["saf_dtl_id"] = $basic_details_dt["saf_dtl_id"];
            $created_on = date("Y-m-d H:i:s");
            if (isset($inputs['btn_approved_submit'])) {
                if ($FV_Saf_Dtl = $this->model_field_verification_dtl->getUlbDataBySafDtlId($input)) {
                    $this->db->transBegin();
                    $inputs = $FV_Saf_Dtl;

                    $memo=$this->model_saf_memo_dtl->generate_assessment_final_memo($inputs['saf_dtl_id'], $login_emp_details_id);
                    $genmemo_last_id=$memo["generate_assessment_final_memo"];

                    $input = ['saf_dtl_id' => $saf_dtl_id];
                    $propDtlId = $this->model_prop_dtl->getPropDtlIdBySafDtlId($input);
                    $prop_dtl_id = $propDtlId['id'];

                    $inputs["new_ward_mstr_id"] = $inputs["ward_mstr_id"];
                    if ($inputs["is_mobile_tower"]=="t") $inputs["is_mobile_tower"] = 1; else $inputs["is_mobile_tower"] = 0;
                    if ($inputs["is_hoarding_board"]=="t") $inputs["is_hoarding_board"] = 1; else $inputs["is_hoarding_board"] = 0;
                    if ($inputs["is_petrol_pump"]=="t") $inputs["is_petrol_pump"] = 1; else $inputs["is_petrol_pump"] = 0;
                    if ($inputs["is_water_harvesting"]=="t") $inputs["is_water_harvesting"] = 1; else $inputs["is_water_harvesting"] = 0;
                    if ($FV_Saf_Dtl['prop_type_mstr_id'] == 4) {
                        $inputs["land_occupation_date"] = $propDtlId["land_occupation_date"];

                        if ($inputs['road_type_mstr_id']!=4) {
                            $vacantDtlArr = $safHelper->makeVacantFloorDtlArr($inputs);
                        } else {
                            $vacantDtlArr = $safHelper->makeVacantWithNoRoadFloorDtlArr($inputs);
                        }                    
                        list($newSafTaxDtl, $old_rule_arv_sub, $new_rule_arv_sub, $cv_rule_arv_sub) = $safHelper->calVacantTaxDtl($vacantDtlArr);
                        $safTaxDtl = $this->model_saf_tax->getJoinBySafDtlIdMd5(md5($saf_dtl_id));
                        if ($diffTax = $safHelper->calcDiffPanelty($safTaxDtl, $newSafTaxDtl)) {
                            $this->model_prop_tax->updateSafDeactiveStatus(['prop_dtl_id'=>$prop_dtl_id]);
                            $safHelper->calDiffSafDemand($newSafTaxDtl, $saf_dtl_id, $prop_dtl_id);
                        }
                    } else {
                        if ($FV_Saf_floor_Dtl = $this->model_field_verification_floor_details->getUlbDataBySafDtlId($input)) {
                            $floorKey = 0;
                            foreach ($FV_Saf_floor_Dtl as $key => $value) {
                                $inputs["floor_mstr_id"][$floorKey] = $value["floor_mstr_id"];
                                $inputs["usage_type_mstr_id"][$floorKey] = $value["usage_type_mstr_id"];
                                $inputs["const_type_mstr_id"][$floorKey] = $value["const_type_mstr_id"];
                                $inputs["occupancy_type_mstr_id"][$floorKey] = $value["occupancy_type_mstr_id"];
                                $inputs["builtup_area"][$floorKey] = $value["builtup_area"];
                                $inputs["date_from"][$floorKey] = date("Y-m", strtotime($value["date_from"]));
                                $inputs["date_upto"][$floorKey] = "";
                                if ($value["date_upto"]!="" && $value["date_upto"]!="null") {
                                    $inputs["date_upto"][$floorKey] = date("Y-m", strtotime($value["date_upto"]));
                                }
                                $floorKey++;
                            }
                        }
                        $floorDtlArr = $safHelper->makeBuildingFloorDtlArr($inputs);
                        $data['floorDtlArr'] = $floorDtlArr;
                        $isAdditionaTaxImplemented = $safHelper->checkIsAdditionaTaxImplemented($inputs["is_water_harvesting"], $inputs["prop_type_mstr_id"], $inputs["area_of_plot"]);
                        list($newSafTaxDtl, $old_rule_arv_sub, $new_rule_arv_sub, $cv_rule_arv_sub) = $safHelper->calBuildingTaxDtl($floorDtlArr, $inputs['prop_type_mstr_id'], $isAdditionaTaxImplemented);

                        $safTaxDtl = $this->model_saf_tax->getJoinBySafDtlIdMd5(md5($saf_dtl_id));
                        if ($diffTax = $safHelper->calcDiffPanelty($safTaxDtl, $newSafTaxDtl)) {
                            $this->model_prop_tax->updateSafDeactiveStatus(['prop_dtl_id'=>$prop_dtl_id]);
                            $safHelper->calDiffSafDemand($newSafTaxDtl, $saf_dtl_id, $prop_dtl_id);
                        }
                    }
                    # Update Property as per ULB Verification
                    $data["ufieldVerificationmstr_detail"] = $this->model_field_verification_dtl->getulbdatabysafidobjection($data["saf_dtl_id"], ["ULB TC", "Objection"]);
                    $data["Saf_detail"] = $data;

                    $verification_data = array(
                        "prop_type_mstr_id" => $data["ufieldVerificationmstr_detail"]["prop_type_mstr_id"],
                        "road_type_mstr_id" => $data["ufieldVerificationmstr_detail"]["road_type_mstr_id"],
                        "area_of_plot" => $data["ufieldVerificationmstr_detail"]["area_of_plot"],
                        "zone_mstr_id" => $data["ufieldVerificationmstr_detail"]["zone_mstr_id"],
                        "ward_mstr_id" => $data["ufieldVerificationmstr_detail"]["ward_mstr_id"],
                        "assessment_type" => $data["Saf_detail"]["assessment_type"]
                    );
                    $this->model_prop_dtl->updatePropertyByEO($saf_dtl_id, $verification_data);

                    $inputs = arrFilterSanitizeString($this->request->getVar());
                    /* $prop_ward_id = $data['ufieldVerificationmstr_detail']['ward_mstr_id'];
                    $wardd = $this->model_ward_mstr->getdatabyid($data['ufieldVerificationmstr_detail']['ward_mstr_id']);
                    $ward_nmm = $wardd['ward_no']; */
                    $Session = Session();
                    $emp_mstr = $Session->get("emp_details");
                    $sender_emp_details_id = $emp_mstr["id"];
                    $data = [
                        'remarks' => $inputs['remarks'],
                        'level_pending_dtl_id' => md5($data['form']['id']),
                        "status"=> 0,
                        'saf_dtl_id' => $saf_dtl_id,
                        'emp_details_id' => $login_emp_details_id,
                        'created_on' => date('Y-m-d H:i:s'),
                        'verification_status' => 1, //Verfied
                        'saf_pending_status' => 1, // Approved by EO
                        'doc_verify_status' => 1, // Approved by EO
                        'receiver_emp_details_id' => $sender_emp_details_id
                    ];
                    $sql = "SELECT 
                                * 
                            FROM tbl_saf_memo_dtl 
                            WHERE 
                                saf_dtl_id=".$saf_dtl_id." 
                                AND memo_type='FAM'
                                AND status=1";
                    $memoDtl = $this->db->query($sql)->getFirstRow("array");
                    $genmemo_last_id = $memoDtl["id"];

                    /* $assessment_memo = $this->model_saf_memo_dtl->getMemo(["saf_dtl_id" => $saf_dtl_id, "memo_type" => "SAM"]);
                    $lastTaxEffectedDtl = $this->model_prop_tax->getLastEffecredTax($prop_dtl_id);
                    $lastTaxEffectedDtl['fy_mstr_id'] = $this->model_fy_mstr->getFyByFy(["fy"=>$lastTaxEffectedDtl['fyear']])["id"];
                    
                    //count ward
                    $ward_count = $this->model_prop_dtl->count_ward_by_wardid($prop_ward_id);
                    $sl_no = $ward_count['ward_cnt'];
                    $sl_noo = $sl_no + 1;
                    $serial_no = str_pad($sl_noo, 4, "0", STR_PAD_LEFT);
                    $quartely_tax = $lastTaxEffectedDtl['holding_tax'] + $lastTaxEffectedDtl['water_tax'] + $lastTaxEffectedDtl['education_cess'] + $lastTaxEffectedDtl['health_cess'] + $lastTaxEffectedDtl['latrine_tax'];
                     */

                    /* $genmemo_last_id = $this->model_saf_memo_dtl->generatesafmemono(
                        $saf_dtl_id,
                        $lastTaxEffectedDtl['fy_mstr_id'],
                        $lastTaxEffectedDtl['fyear'],
                        $lastTaxEffectedDtl['qtr'],
                        $lastTaxEffectedDtl['arv'],
                        $quartely_tax,
                        "FAM",
                        $login_emp_details_id,
                        date('Y-m-d H:i:s'),
                        $assessment_memo["holding_no"],
                        $prop_dtl_id
                    );
                    
                    $memo_no = 'FAM/' . $ward_nmm . '/' . $genmemo_last_id . $serial_no . '/' . $lastTaxEffectedDtl['fyear'];
                    $this->model_saf_memo_dtl->updatememonoById($genmemo_last_id, $memo_no, $lastTaxEffectedDtl['fyear']); */
                    
                    #====deactivate old property when 100% mutation                    
                    if(isset($basic_details_dt["assessment_type"]) && ($basic_details_dt["previous_holding_id"]??0) && in_array($basic_details_dt["assessment_type"],["MUTATION","MUTATION WITH REASSESSMENT"]) && ($FV_Saf_Dtl["percentage_of_property_transfer"]??0)>=100){
                        $this->model_prop_dtl->where("id",$basic_details_dt["previous_holding_id"])->update(["status"=>0]);
                    }
                    if ($this->model_level_pending_dtl->updatesisafById($data)) {
                        $Session = Session();
                        $emp_mstr = $Session->get("emp_details");
                        $sender_emp_details_id = $emp_mstr["id"];

                        $checkAgencyData = $this->model_field_verification_dtl->getulbdatabysafid($saf_dtl_id, 'AGENCY TC');
                        $recv_user_type_id=5;
                        $status=1;
                        $vstatus=0;
                        if($checkAgencyData){
                            $recv_user_type_id=10;
                            $status=0;
                            $vstatus=1;
                        }

                        $last_level_data = [
                            'remarks' => $inputs['remarks'],
                            'saf_dtl_id' => $saf_dtl_id,
                            'created_on' => "NOW()",
                            'forward_date' => "NOW()",
                            'forward_time' => "NOW()",
                            'sender_user_type_id' => 10,//Property Section Incharge
                            'receiver_user_type_id'=> $recv_user_type_id,//Executive Officer
                            'final_verification_status'=> $vstatus,
                            'status'=> $status,
                            'sender_emp_details_id' => $sender_emp_details_id,
                            'receiver_emp_details_id' => $sender_emp_details_id
                        ];
                        //last time level data insertion
                        $this->model_level_pending_dtl->bugfix_level_pending_new($last_level_data); //Shashi
                        if($insertverify = $this->model_level_pending_dtl->insrtFinalEo_new($last_level_data))
                        {
                            $this->model_saf_dtl->update_saf_pending_status($data);
                            
                            // If approving from objection page
                            $objection = $this->ObjectionModel->GetObjectionByPropId($propDtlId["id"]);
                            if($objection){
                                $arr = [
                                    "id" => $objection["id"],
                                    "sh_remarks" => $inputs["remarks"],
                                    "level_status" => 5, //Approved
                                ];
                                $this->ObjectionModel->UpdateObjection($arr);
                                flashToast("message", "Objection Approved successfully.");
                                $url=base_url('propDtl/ViewObjectionEO/'.md5($objection["id"]));
                                
                            }
                            else
                            {
                                
                                flashToast("message", "FAM generated successfully.");
                                $url=base_url('EO_SAF/view2/'.($saf_dtl_id_MD5).'?memo_id='.md5($genmemo_last_id));
                            }
                            
                            $this->db->transCommit();
                            return $this->response->redirect($url);
                        }
                       
                    }
                    $this->db->transRollback();
                }
            }

            $Session = Session();
            $emp_mstr = $Session->get("emp_details");
            $sender_emp_details_id = $emp_mstr["id"];
            if (isset($inputs['btn_backward_submit'])) {
                $level_data = [
                    'level_pending_dtl_id' => md5($data['form']['id']),
                    'remarks' => $inputs['remarks'],
                    'verification_status' => 1,
                    'status' => 0,
                    'sender_emp_details_id' => $sender_emp_details_id,
                    'receiver_emp_details_id' => $sender_emp_details_id
                ];
                if ($updatebackward = $this->model_level_pending_dtl->updatelevelpendingById($level_data)) {
                    $level_data = [
                        'remarks' => $inputs['remarks'],
                        'level_pending_dtl_id' => $data['form']['id'],
                        'saf_dtl_id' => $data['saf_dtl_id'],
                        'emp_details_id' => $login_emp_details_id,
                        'created_on' => date('Y-m-d H:i:s'),
                        'forward_date' => date('Y-m-d'),
                        'forward_time' => date('H:i:s'),
                        'sender_user_type_id' => 10, //Property Executive Officer
                        'receiver_user_type_id' => 9, // Section Incharge
                        'verification_status' => 0,
                        'status' => 1,
                        'sender_emp_details_id' => $sender_emp_details_id
                    ];
                    $this->model_level_pending_dtl->bugfix_level_pending($level_data); //Shashi
                    if ($insertbackward = $this->model_level_pending_dtl->insrtlevelpendingdtl($level_data)) {
                        flashToast("message", "Application sent back to section head");
                        return $this->response->redirect(base_url('EO_SAF/index/'));
                    }
                }
            }
            if (isset($inputs['btn_back_to_citizen_submit'])) {

                $data = [

                    'id' => $data['form']['id'],
                    'saf_dtl_id' => $data['saf_dtl_id'],
                    'remarks' => $inputs['remarks'],
                    'sender_user_type_id' => 10, //Property Executive Officer
                    'receiver_user_type_id' => 11, //Back Office
                    'sender_emp_details_id' => $sender_emp_details_id
                ];
                if ($updatebacktocitizen = $this->model_level_pending_dtl->sendBackToCitizen($data)) {

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
                    
                    $level_record = [
                        'saf_dtl_id' => $data["saf_dtl_id"],
                        'level_pending_dtl_id' => $data['id'],
                        'sender_user_type_id' => 10,     //Propert Dealing Assisstant
                        'sender_emp_details_id' => $data["sender_emp_details_id"],
                        'sender_ip_address' => $ip_address, 
                        'remarks' => $data["remarks"],
                        'status' => 1
                        
                    ];

                    $bub_fix = [
                        'saf_dtl_id' => $data["saf_dtl_id"],
                        'sender_user_type_id' => 10,
                        'receiver_user_type_id' => 11,
                        'forward_date' => "NOW()",
                        'forward_time' => "NOW()",
                        'created_on' => "NOW()",
                        'status' => 1,
                        'remarks' => $data["remarks"],
                        'sender_emp_details_id' => $data["sender_emp_details_id"]
            
                    ];

                    $this->model_level_pending_dtl->bugfix_level_pending($bub_fix); //Shashi
    
                    $this->model_level_pending_dtl->record_back_to_citizen($level_record);

                    $this->model_saf_dtl->update_saf_pending_status(["saf_dtl_id" => $data['saf_dtl_id'], "doc_verify_status" => 2]); //backtocitizen
                    flashToast("message", "Applicationn sent back to citizen successfully.");
                    return $this->response->redirect(base_url('EO_SAF/index'));
                }
            }
            if(isset($inputs['btn_reject_submit']))
            {
                $leveldata = [
                    'remarks' => $inputs['remarks'],
                    'level_pending_dtl_id' => md5($data['form']['id']),
                    "status"=> 0,
                    'saf_dtl_id' => $saf_dtl_id,
                    'emp_details_id' => $login_emp_details_id,
                    'created_on' => date('Y-m-d H:i:s'),
                    'verification_status' => 1, //Verfied
                    'receiver_emp_details_id' => $sender_emp_details_id
                ];

                if ($this->model_level_pending_dtl->updatesisafById($leveldata)) {
                    
                    $last_level_data = [
                        'remarks' => $inputs['remarks'],
                        'saf_dtl_id' => $saf_dtl_id,
                        'created_on' => "NOW()",
                        'forward_date' => "NOW()",
                        'forward_time' => "NOW()",
                        'sender_user_type_id' => 10,//Executive Officer
                        'receiver_user_type_id'=> 10,//Executive Officer
                        'final_verification_status'=> 1,
                        'status'=> 0,
                        'sender_emp_details_id' => $login_emp_details_id,
                        'receiver_emp_details_id' => $login_emp_details_id
                    ];
                    
                    //last time level data insertion
                    $this->model_level_pending_dtl->bugfix_level_pending_new($last_level_data); //Shashi
                    //print_var($this->model_level_pending_dtl->insrtFinalEo($last_level_data));
                    
                    if($insertverify = $this->model_level_pending_dtl->insrtFinalEo_new($last_level_data))
                    {
                        $deactivate_data = [
                            'prop_dtl_id' => $saf_dtl_id,
                            'prop_type' => 'Saf',
                            'deactivation_date' => 'NOW()',
                            'remark' => $inputs['remarks'],
                            'emp_details_id' => $login_emp_details_id,
                            'created_on' => 'NOW()',
                            'ward_mstr_id' => $data['ward_mstr_id'],
                            'activate_deactivate_status' => 'Deactivated'
                        ];

                        if($this->model_prop_saf_deactivation->insertDeactivationData($deactivate_data))
                        {
                            $this->model_saf_dtl->updateSafDtlStatus($saf_dtl_id);
                            flashToast("message", "Application Rejected");
                            return $this->response->redirect(base_url('EO_SAF/index'));
                        }
                    }
                }
            }
        } else {
            $data['basic_details_data']=array(
                'saf_no'=> isset($basic_details_dt['saf_no'])?$basic_details_dt['saf_no']:'N/A',
                'apply_date'=> isset($basic_details_dt['apply_date'])?$basic_details_dt['apply_date']:'N/A',
                'ward_no'=> isset($basic_details_dt['ward_no'])?$basic_details_dt['ward_no']:'N/A',
                'new_holding_no'=> isset($basic_details_dt['new_holding_no'])?$basic_details_dt['new_holding_no']:'N/A',
                'new_ward_no'=> isset($basic_details_dt['new_ward_no'])?$basic_details_dt['new_ward_no']:'N/A',
                'holding_no'=> isset($basic_details_dt['holding_no'])?$basic_details_dt['holding_no']:'N/A',
                'assessment_type'=> isset($basic_details_dt['assessment_type'])?$basic_details_dt['assessment_type']:'N/A',
                'plot_no'=> isset($basic_details_dt['plot_no'])?$basic_details_dt['plot_no']:'N/A',
                'property_type'=> isset($basic_details_dt['property_type'])?$basic_details_dt['property_type']:'N/A',
                'area_of_plot'=> isset($basic_details_dt['area_of_plot'])?$basic_details_dt['area_of_plot']:'N/A',
                'ownership_type'=> isset($basic_details_dt['ownership_type'])?$basic_details_dt['ownership_type']:'N/A',
                'is_water_harvesting'=> isset($basic_details_dt['is_water_harvesting'])?$basic_details_dt['is_water_harvesting']:'N/A',
                'holding_type'=> isset($basic_details_dt['holding_type'])?$basic_details_dt['holding_type']:'N/A',
                'prop_address'=> isset($basic_details_dt['prop_address'])?$basic_details_dt['prop_address']:'N/A',
                'road_type'=> isset($basic_details_dt['road_type'])?$basic_details_dt['road_type']:'N/A',
                'zone_mstr_id'=> isset($basic_details_dt['zone_mstr_id'])?$basic_details_dt['zone_mstr_id']:'N/A',
                'entry_type'=> isset($basic_details_dt['entry_type'])?$basic_details_dt['entry_type']:'N/A',
                'flat_registry_date'=> isset($basic_details_dt['flat_registry_date'])?$basic_details_dt['flat_registry_date']:'N/A',
                'created_on'=> isset($basic_details_dt['created_on'])?$basic_details_dt['created_on']:'N/A',
                'prop_type_mstr_id'=> isset($basic_details_dt['prop_type_mstr_id'])?$basic_details_dt['prop_type_mstr_id']:'N/A',
                'appartment_name'=> isset($basic_details_dt['apartment_name'])?$basic_details_dt['apartment_name']:'N/A',
                'apt_code'=> isset($basic_details_dt['apt_code'])?$basic_details_dt['apt_code']:'N/A',
                'prop_type'=> 'saf'
    
            );
            // print_var($data);
            // return;
            return view('property/saf/eo_saf_view', $data);
        }
    }

    public function view_new2($saf_dtl_id_MD5) {
        if(!$this->request){
            if (is_cli()) {
                $this->request = Services::request();  // Manually get the request service
                $this->request->setMethod("post");
                $this->request->setGlobal('post', $_REQUEST);
            } 
        }
        $data = (array)null;
        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");

        $emp_mstr = $Session->get("emp_details");

        $login_emp_details_id = $emp_mstr["id"];
        $basic_details_dt = $this->model_view_saf_dtl->getSafDtlByMD5SafDtlId($saf_dtl_id_MD5);
        $data = $basic_details_dt;
        
        $saf_dtl_id = $data["saf_dtl_id"];

        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $data['previous_prop_dtl'] = $this->model_prop_dtl->getholdingnobysafid($data['saf_dtl_id']);

        $data['saf_owner_detail'] = $this->model_saf_owner_detail->getOwnerdtlBySAFId(['saf_dtl_id' => $data['saf_dtl_id']]);

        // applicant img & document
        foreach ($data['saf_owner_detail'] as $key => $owner_detail) {
            $input = [
                'saf_dtl_id' => $data['saf_dtl_id'],
                'saf_owner_dtl_id' => $owner_detail['id'],
            ];
            $data['saf_owner_detail'][$key]['applicant_img_dtl'] = $this->model_saf_doc_dtl->getApplicantImgBySafDtlAndSafOwnerDtlId($input);
            $data['saf_owner_detail'][$key]['applicant_doc_dtl'] = $this->model_saf_doc_dtl->getApplicantDocBySafDtlAndSafOwnerDtlId($input);
        }

        if ($data['has_previous_holding_no'] == 't' && $data['is_owner_changed'] == 't')
            $data['prev_saf_owner_detail'] = $this->model_prop_owner_detail->getPropOwnerDtlByProp_dtlId(['prop_dtl_id' => $data['previous_holding_id']]);
        //print_var($data['prev_saf_owner_detail']);

        $data['Verification'] = $this->model_field_verification_dtl->getAllFieldVerification($input);
        $data['Memo'] = $this->model_saf_memo_dtl->getAllMemo($input);
        $data['saf_tax_list'] = $this->model_saf_tax->getSafTaxDtlBySafDtlId(['saf_dtl_id' => $data['saf_dtl_id']]);
        $data['form'] = $this->model_level_pending_dtl->getLastRecord(['saf_dtl_id' => $data['saf_dtl_id'], 'receiver_user_type_id' => 10]); //Property Executive Officer

        if ($this->request->getMethod() == 'post') {
            
            $safHelper = new SAFHelper($this->db);
            $inputs = arrFilterSanitizeString($this->request->getVar());
            $inputs["saf_dtl_id"] = $basic_details_dt["saf_dtl_id"];
            $created_on = date("Y-m-d H:i:s");
            if (isset($inputs['btn_approved_submit'])) {
                if ($FV_Saf_Dtl = $this->model_field_verification_dtl->getUlbDataBySafDtlId($input)) {
                    $this->db->transBegin();
                    $inputs = $FV_Saf_Dtl;

                    $memo=$this->model_saf_memo_dtl->generate_assessment_final_memo($inputs['saf_dtl_id'], $login_emp_details_id);
                    $genmemo_last_id=$memo["generate_assessment_final_memo"];

                    $input = ['saf_dtl_id' => $saf_dtl_id];
                    $propDtlId = $this->model_prop_dtl->getPropDtlIdBySafDtlId($input);
                    $prop_dtl_id = $propDtlId['id'];

                    $inputs["new_ward_mstr_id"] = $inputs["ward_mstr_id"];
                    if ($inputs["is_mobile_tower"]=="t") $inputs["is_mobile_tower"] = 1; else $inputs["is_mobile_tower"] = 0;
                    if ($inputs["is_hoarding_board"]=="t") $inputs["is_hoarding_board"] = 1; else $inputs["is_hoarding_board"] = 0;
                    if ($inputs["is_petrol_pump"]=="t") $inputs["is_petrol_pump"] = 1; else $inputs["is_petrol_pump"] = 0;
                    if ($inputs["is_water_harvesting"]=="t") $inputs["is_water_harvesting"] = 1; else $inputs["is_water_harvesting"] = 0;
                    if ($FV_Saf_Dtl['prop_type_mstr_id'] == 4) {
                        $inputs["land_occupation_date"] = $propDtlId["land_occupation_date"];

                        if ($inputs['road_type_mstr_id']!=4) {
                            $vacantDtlArr = $safHelper->makeVacantFloorDtlArr($inputs);
                        } else {
                            $vacantDtlArr = $safHelper->makeVacantWithNoRoadFloorDtlArr($inputs);
                        }                   
                        list($newSafTaxDtl, $old_rule_arv_sub, $new_rule_arv_sub, $cv_rule_arv_sub) = $safHelper->calVacantTaxDtl($vacantDtlArr);
                        $safTaxDtl = $this->model_saf_tax->getJoinBySafDtlIdMd5(md5($saf_dtl_id));
                        if ($diffTax = $safHelper->calcDiffPanelty($safTaxDtl, $newSafTaxDtl)) {
                            $this->model_prop_tax->updateSafDeactiveStatus(['prop_dtl_id'=>$prop_dtl_id]);
                            $safHelper->calDiffSafDemand($newSafTaxDtl, $saf_dtl_id, $prop_dtl_id);
                        }
                    } else {
                        if ($FV_Saf_floor_Dtl = $this->model_field_verification_floor_details->getUlbDataBySafDtlId($input)) {
                            $floorKey = 0;
                            foreach ($FV_Saf_floor_Dtl as $key => $value) {
                                $inputs["floor_mstr_id"][$floorKey] = $value["floor_mstr_id"];
                                $inputs["usage_type_mstr_id"][$floorKey] = $value["usage_type_mstr_id"];
                                $inputs["const_type_mstr_id"][$floorKey] = $value["const_type_mstr_id"];
                                $inputs["occupancy_type_mstr_id"][$floorKey] = $value["occupancy_type_mstr_id"];
                                $inputs["builtup_area"][$floorKey] = $value["builtup_area"];
                                $inputs["date_from"][$floorKey] = date("Y-m", strtotime($value["date_from"]));
                                $inputs["date_upto"][$floorKey] = "";
                                if ($value["date_upto"]!="" && $value["date_upto"]!="null") {
                                    $inputs["date_upto"][$floorKey] = date("Y-m", strtotime($value["date_upto"]));
                                }
                                $floorKey++;
                            }
                        }
                        $floorDtlArr = $safHelper->makeBuildingFloorDtlArr($inputs);
                        $data['floorDtlArr'] = $floorDtlArr;
                        $isAdditionaTaxImplemented = $safHelper->checkIsAdditionaTaxImplemented($inputs["is_water_harvesting"], $inputs["prop_type_mstr_id"], $inputs["area_of_plot"]);
                        list($newSafTaxDtl, $old_rule_arv_sub, $new_rule_arv_sub, $cv_rule_arv_sub) = $safHelper->calBuildingTaxDtl($floorDtlArr, $inputs['prop_type_mstr_id'], $isAdditionaTaxImplemented);

                        $safTaxDtl = $this->model_saf_tax->getJoinBySafDtlIdMd5(md5($saf_dtl_id));
                        if ($diffTax = $safHelper->calcDiffPanelty($safTaxDtl, $newSafTaxDtl)) {
                            $this->model_prop_tax->updateSafDeactiveStatus(['prop_dtl_id'=>$prop_dtl_id]);
                            $safHelper->calDiffSafDemand($newSafTaxDtl, $saf_dtl_id, $prop_dtl_id);
                        }
                    }
                    # Update Property as per ULB Verification
                    $data["ufieldVerificationmstr_detail"] = $this->model_field_verification_dtl->getulbdatabysafidobjection($data["saf_dtl_id"], ["ULB TC", "Objection"]);
                    $data["Saf_detail"] = $data;

                    $verification_data = array(
                        "prop_type_mstr_id" => $data["ufieldVerificationmstr_detail"]["prop_type_mstr_id"],
                        "road_type_mstr_id" => $data["ufieldVerificationmstr_detail"]["road_type_mstr_id"],
                        "area_of_plot" => $data["ufieldVerificationmstr_detail"]["area_of_plot"],
                        "zone_mstr_id" => $data["ufieldVerificationmstr_detail"]["zone_mstr_id"],
                        "ward_mstr_id" => $data["ufieldVerificationmstr_detail"]["ward_mstr_id"],
                        "assessment_type" => $data["Saf_detail"]["assessment_type"]
                    );
                    $this->model_prop_dtl->updatePropertyByEO($saf_dtl_id, $verification_data);

                    $inputs = arrFilterSanitizeString($this->request->getVar());
                    /* $prop_ward_id = $data['ufieldVerificationmstr_detail']['ward_mstr_id'];
                    $wardd = $this->model_ward_mstr->getdatabyid($data['ufieldVerificationmstr_detail']['ward_mstr_id']);
                    $ward_nmm = $wardd['ward_no']; */
                    $Session = Session();
                    $emp_mstr = $Session->get("emp_details");
                    $sender_emp_details_id = $emp_mstr["id"];
                    $data = [
                        'remarks' => $inputs['remarks'],
                        'level_pending_dtl_id' => md5($data['form']['id']),
                        "status"=> 0,
                        'saf_dtl_id' => $saf_dtl_id,
                        'emp_details_id' => $login_emp_details_id,
                        'created_on' => date('Y-m-d H:i:s'),
                        'verification_status' => 1, //Verfied
                        'saf_pending_status' => 1, // Approved by EO
                        'doc_verify_status' => 1, // Approved by EO
                        'receiver_emp_details_id' => $sender_emp_details_id
                    ];
                    $sql = "SELECT 
                                * 
                            FROM tbl_saf_memo_dtl 
                            WHERE 
                                saf_dtl_id=".$saf_dtl_id." 
                                AND memo_type='FAM'
                                AND status=1";
                    $memoDtl = $this->db->query($sql)->getFirstRow("array");
                    $genmemo_last_id = $memoDtl["id"];

                    #====deactivate old property when 100% mutation                    
                    if(isset($basic_details_dt["assessment_type"]) && ($basic_details_dt["previous_holding_id"]??0) && in_array($basic_details_dt["assessment_type"],["MUTATION","MUTATION WITH REASSESSMENT"]) && ($FV_Saf_Dtl["percentage_of_property_transfer"]??0)>=100){
                        $this->model_prop_dtl->where("id",$basic_details_dt["previous_holding_id"])->update(["status"=>0]);
                    }
                    if ($this->model_level_pending_dtl->updatesisafById($data)) {
                        $checkAgencyData = $this->model_field_verification_dtl->getulbdatabysafid($saf_dtl_id, 'AGENCY TC');
                        $recv_user_type_id=5;
                        $status=1;
                        $vstatus=0;
                        if($checkAgencyData){
                            $recv_user_type_id=10;
                            $status=0;
                            $vstatus=1;
                        }

                        $Session = Session();
                        $emp_mstr = $Session->get("emp_details");
                        $sender_emp_details_id = $emp_mstr["id"];
                        $last_level_data = [
                            'remarks' => $inputs['remarks'],
                            'saf_dtl_id' => $saf_dtl_id,
                            'created_on' => "NOW()",
                            'forward_date' => "NOW()",
                            'forward_time' => "NOW()",
                            'sender_user_type_id' => 10,//Property Section Incharge
                            'receiver_user_type_id'=> $recv_user_type_id,//Executive Officer
                            'final_verification_status'=> $vstatus,
                            'status'=> $status,
                            'sender_emp_details_id' => $sender_emp_details_id,
                            'receiver_emp_details_id' => $sender_emp_details_id
                        ];
                        //last time level data insertion
						$this->model_level_pending_dtl->bugfix_level_pending_new($last_level_data); //Shashi
                        if($insertverify = $this->model_level_pending_dtl->insrtFinalEo_new($last_level_data))
                        {
                            $this->model_saf_dtl->update_saf_pending_status($data);
                            
                            // If approving from objection page
                            $objection = $this->ObjectionModel->GetObjectionByPropId($propDtlId["id"]);
                            if($objection){
                                $arr = [
                                    "id" => $objection["id"],
                                    "sh_remarks" => $inputs["remarks"],
                                    "level_status" => 5, //Approved
                                ];
                                $this->ObjectionModel->UpdateObjection($arr);
                                flashToast("message", "Objection Approved successfully.");
                                $url=base_url('propDtl/ViewObjectionEO/'.md5($objection["id"]));
                                
                            }
                            else
                            {
                                
                                flashToast("message", "FAM generated successfully.");
                                $url=base_url('EO_SAF/view_new2/'.($saf_dtl_id_MD5).'?memo_id='.md5($genmemo_last_id));
                            }
                            $this->db->transCommit();
                            if($this->request->getVar("cmd")){
                                return true;
                            }
                            return $this->response->redirect($url);
                        }
                       
                    }
                    $this->db->transRollback();
                    if($this->request->getVar("cmd")){
                        return false;
                    }
                }
            }

            $Session = Session();
            $emp_mstr = $Session->get("emp_details");
            $sender_emp_details_id = $emp_mstr["id"];
            if (isset($inputs['btn_backward_submit'])) {
                $level_data = [
                    'level_pending_dtl_id' => md5($data['form']['id']),
                    'remarks' => $inputs['remarks'],
                    'verification_status' => 1,
                    'status' => 0,
                    'sender_emp_details_id' => $sender_emp_details_id,
                    'receiver_emp_details_id' => $sender_emp_details_id
                ];
                if ($updatebackward = $this->model_level_pending_dtl->updatelevelpendingById($level_data)) {
                    $level_data = [
                        'remarks' => $inputs['remarks'],
                        'level_pending_dtl_id' => $data['form']['id'],
                        'saf_dtl_id' => $data['saf_dtl_id'],
                        'emp_details_id' => $login_emp_details_id,
                        'created_on' => date('Y-m-d H:i:s'),
                        'forward_date' => date('Y-m-d'),
                        'forward_time' => date('H:i:s'),
                        'sender_user_type_id' => 10, //Property Executive Officer
                        'receiver_user_type_id' => 9, // Section Incharge
                        'verification_status' => 0,
                        'status' => 1,
                        'sender_emp_details_id' => $sender_emp_details_id
                    ];
					$this->model_level_pending_dtl->bugfix_level_pending($level_data); //Shashi
                    if ($insertbackward = $this->model_level_pending_dtl->insrtlevelpendingdtl($level_data)) {
                        flashToast("message", "Application sent back to section head");
                        return $this->response->redirect(base_url('EO_SAF/inbox_list_new/'));
                    }
                }
            }
            if (isset($inputs['btn_back_to_citizen_submit'])) {

                $data = [

                    'id' => $data['form']['id'],
                    'saf_dtl_id' => $data['saf_dtl_id'],
                    'remarks' => $inputs['remarks'],
                    'sender_user_type_id' => 10, //Property Executive Officer
                    'receiver_user_type_id' => 11, //Back Office
                    'sender_emp_details_id' => $sender_emp_details_id
                ];
                if ($updatebacktocitizen = $this->model_level_pending_dtl->sendBackToCitizen($data)) {

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
                    
                    $level_record = [
                        'saf_dtl_id' => $data["saf_dtl_id"],
                        'level_pending_dtl_id' => $data['id'],
                        'sender_user_type_id' => 10,     //Propert Dealing Assisstant
                        'sender_emp_details_id' => $data["sender_emp_details_id"],
                        'sender_ip_address' => $ip_address, 
                        'remarks' => $data["remarks"],
                        'status' => 1
                        
                    ];
					
					$bub_fix = [
                        'saf_dtl_id' => $data["saf_dtl_id"],
                        'sender_user_type_id' => 10,
                        'receiver_user_type_id' => 11,
                        'forward_date' => "NOW()",
                        'forward_time' => "NOW()",
                        'created_on' => "NOW()",
                        'status' => 1,
                        'remarks' => $data["remarks"],
                        'sender_emp_details_id' => $data["sender_emp_details_id"]
            
                    ];
                    $this->model_level_pending_dtl->bugfix_level_pending($bub_fix); //Shashi
    
                    $this->model_level_pending_dtl->record_back_to_citizen($level_record);

                    $this->model_saf_dtl->update_saf_pending_status(["saf_dtl_id" => $data['saf_dtl_id'], "doc_verify_status" => 2]); //backtocitizen
                    flashToast("message", "Applicationn sent back to citizen successfully.");
                    return $this->response->redirect(base_url('EO_SAF/inbox_list_new'));
                }
            }
            if(isset($inputs['btn_reject_submit']))
            {
                $leveldata = [
                    'remarks' => $inputs['remarks'],
                    'level_pending_dtl_id' => md5($data['form']['id']),
                    "status"=> 0,
                    'saf_dtl_id' => $saf_dtl_id,
                    'emp_details_id' => $login_emp_details_id,
                    'created_on' => date('Y-m-d H:i:s'),
                    'verification_status' => 1, //Verfied
                    'receiver_emp_details_id' => $sender_emp_details_id
                ];

                if ($this->model_level_pending_dtl->updatesisafById($leveldata)) {
                    
                    $last_level_data = [
                        'remarks' => $inputs['remarks'],
                        'saf_dtl_id' => $saf_dtl_id,
                        'created_on' => "NOW()",
                        'forward_date' => "NOW()",
                        'forward_time' => "NOW()",
                        'sender_user_type_id' => 10,//Executive Officer
                        'receiver_user_type_id'=> 10,//Executive Officer
                        'final_verification_status'=> 1,
                        'status'=> 0,
                        'sender_emp_details_id' => $login_emp_details_id,
                        'receiver_emp_details_id' => $login_emp_details_id
                    ];
                    
                    //last time level data insertion
                    $this->model_level_pending_dtl->bugfix_level_pending_new($last_level_data); //Shashi
                    //print_var($this->model_level_pending_dtl->insrtFinalEo($last_level_data));
                    
                    if($insertverify = $this->model_level_pending_dtl->insrtFinalEo_new($last_level_data))
                    {
                        $deactivate_data = [
                            'prop_dtl_id' => $saf_dtl_id,
                            'prop_type' => 'Saf',
                            'deactivation_date' => 'NOW()',
                            'remark' => $inputs['remarks'],
                            'emp_details_id' => $login_emp_details_id,
                            'created_on' => 'NOW()',
                            'ward_mstr_id' => $data['ward_mstr_id'],
                            'activate_deactivate_status' => 'Deactivated'
                        ];

                        if($this->model_prop_saf_deactivation->insertDeactivationData($deactivate_data))
                        {
                            $this->model_saf_dtl->updateSafDtlStatus($saf_dtl_id);
                            flashToast("message", "Application Rejected");
                            return $this->response->redirect(base_url('EO_SAF/inbox_list_new'));
                        }
                    }
                }
            }
        } else {
            $data['basic_details_data']=array(
                'saf_no'=> isset($basic_details_dt['saf_no'])?$basic_details_dt['saf_no']:'N/A',
                'apply_date'=> isset($basic_details_dt['apply_date'])?$basic_details_dt['apply_date']:'N/A',
                'ward_no'=> isset($basic_details_dt['ward_no'])?$basic_details_dt['ward_no']:'N/A',
                'new_holding_no'=> isset($basic_details_dt['new_holding_no'])?$basic_details_dt['new_holding_no']:'N/A',
                'new_ward_no'=> isset($basic_details_dt['new_ward_no'])?$basic_details_dt['new_ward_no']:'N/A',
                'holding_no'=> isset($basic_details_dt['holding_no'])?$basic_details_dt['holding_no']:'N/A',
                'assessment_type'=> isset($basic_details_dt['assessment_type'])?$basic_details_dt['assessment_type']:'N/A',
                'plot_no'=> isset($basic_details_dt['plot_no'])?$basic_details_dt['plot_no']:'N/A',
                'property_type'=> isset($basic_details_dt['property_type'])?$basic_details_dt['property_type']:'N/A',
                'area_of_plot'=> isset($basic_details_dt['area_of_plot'])?$basic_details_dt['area_of_plot']:'N/A',
                'ownership_type'=> isset($basic_details_dt['ownership_type'])?$basic_details_dt['ownership_type']:'N/A',
                'is_water_harvesting'=> isset($basic_details_dt['is_water_harvesting'])?$basic_details_dt['is_water_harvesting']:'N/A',
                'holding_type'=> isset($basic_details_dt['holding_type'])?$basic_details_dt['holding_type']:'N/A',
                'prop_address'=> isset($basic_details_dt['prop_address'])?$basic_details_dt['prop_address']:'N/A',
                'road_type'=> isset($basic_details_dt['road_type'])?$basic_details_dt['road_type']:'N/A',
                'zone_mstr_id'=> isset($basic_details_dt['zone_mstr_id'])?$basic_details_dt['zone_mstr_id']:'N/A',
                'entry_type'=> isset($basic_details_dt['entry_type'])?$basic_details_dt['entry_type']:'N/A',
                'flat_registry_date'=> isset($basic_details_dt['flat_registry_date'])?$basic_details_dt['flat_registry_date']:'N/A',
                'created_on'=> isset($basic_details_dt['created_on'])?$basic_details_dt['created_on']:'N/A',
                'prop_type_mstr_id'=> isset($basic_details_dt['prop_type_mstr_id'])?$basic_details_dt['prop_type_mstr_id']:'N/A',
                'appartment_name'=> isset($basic_details_dt['apartment_name'])?$basic_details_dt['apartment_name']:'N/A',
                'apt_code'=> isset($basic_details_dt['apt_code'])?$basic_details_dt['apt_code']:'N/A',
                'prop_type'=> 'saf'
    
            );
            // print_var($data);
            // return;
            return view('property/saf/eo_saf_view', $data);
        }
    }


    // Some changes by hayat
    public function view($saf_dtl_id_MD5)
    {
        // echo "entering btn backward post";
        //     return;
        $data = (array)null;
        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");

        $emp_mstr = $Session->get("emp_details");

        $login_emp_details_id = $emp_mstr["id"];
        $data = $this->model_view_saf_dtl->getSafDtlByMD5SafDtlId($saf_dtl_id_MD5);
        $saf_dtl_id = $data["saf_dtl_id"];

        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $data['previous_prop_dtl'] = $this->model_prop_dtl->getholdingnobysafid($data['saf_dtl_id']);

        $data['saf_owner_detail'] = $this->model_saf_owner_detail->getOwnerdtlBySAFId(['saf_dtl_id' => $data['saf_dtl_id']]);

        // applicant img & document
        foreach ($data['saf_owner_detail'] as $key => $owner_detail) {
            $input = [
                'saf_dtl_id' => $data['saf_dtl_id'],
                'saf_owner_dtl_id' => $owner_detail['id'],
            ];
            $data['saf_owner_detail'][$key]['applicant_img_dtl'] = $this->model_saf_doc_dtl->getApplicantImgBySafDtlAndSafOwnerDtlId($input);
            $data['saf_owner_detail'][$key]['applicant_doc_dtl'] = $this->model_saf_doc_dtl->getApplicantDocBySafDtlAndSafOwnerDtlId($input);
        }

        if ($data['has_previous_holding_no'] == 't' && $data['is_owner_changed'] == 't')
            $data['prev_saf_owner_detail'] = $this->model_prop_owner_detail->getPropOwnerDtlByProp_dtlId(['prop_dtl_id' => $data['previous_holding_id']]);
        //print_var($data['prev_saf_owner_detail']);

        $data['Verification'] = $this->model_field_verification_dtl->getAllFieldVerification($input);
        $data['Memo'] = $this->model_saf_memo_dtl->getAllMemo($input);
        $data['saf_tax_list'] = $this->model_saf_tax->getSafTaxDtlBySafDtlId(['saf_dtl_id' => $data['saf_dtl_id']]);
        $data['form'] = $this->model_level_pending_dtl->getLastRecord(['saf_dtl_id' => $data['saf_dtl_id'], 'receiver_user_type_id' => 10]); //Property Executive Officer

        if ($this->request->getMethod() == 'post') {
            $inputs = arrFilterSanitizeString($this->request->getVar());
            $created_on = date("Y-m-d H:i:s");
            // echo "entering btn backward post";
            // return;
            if (isset($inputs['btn_approved_submit'])) {
                $isCalParaSame = true;
                $saf_dtl = null;
                $FV_Saf_Dtl = null;
                $saf_floor_dtl = null;
                $FV_Saf_floor_Dtl = null;

                $input = ['saf_dtl_id' => $saf_dtl_id];
                if ($saf_dtl = $this->model_saf_dtl->getSafDtlById($input)) {
                    $data["application_detail"] = $saf_dtl;
                    if ($FV_Saf_Dtl = $this->model_field_verification_dtl->getUlbDataBySafDtlId($input)) {
                        if ($data["application_detail"]["prop_type_mstr_id"] != $FV_Saf_Dtl['prop_type_mstr_id']) {
                            $isCalParaSame = false;
                        }
                        if ($saf_dtl['road_type_mstr_id'] != $FV_Saf_Dtl['road_type_mstr_id']) {
                            $isCalParaSame = false;
                        }
                        if ($saf_dtl['zone_mstr_id'] != $FV_Saf_Dtl['zone_mstr_id']) {
                            $isCalParaSame = false;
                        }
                        if ($saf_dtl['area_of_plot'] != $FV_Saf_Dtl['area_of_plot']) {
                            $isCalParaSame = false;
                        }
                        if ($saf_dtl['is_mobile_tower'] != $FV_Saf_Dtl['is_mobile_tower']) {
                            $isCalParaSame = false;
                        }
                        if ($saf_dtl['is_mobile_tower'] == 't') {
                            if ($saf_dtl['tower_area'] != $FV_Saf_Dtl['tower_area']) {
                                $isCalParaSame = false;
                            }
                            if (date("Y-m-01", strtotime($saf_dtl['tower_installation_date'])) != date("Y-m-01", strtotime($FV_Saf_Dtl['tower_installation_date']))) {
                                $isCalParaSame = false;
                            }
                        }
                        if ($saf_dtl['is_hoarding_board'] != $FV_Saf_Dtl['is_hoarding_board']) {
                            $isCalParaSame = false;
                        }
                        if ($saf_dtl['is_hoarding_board'] == 't') {
                            if ($saf_dtl['hoarding_area'] != $FV_Saf_Dtl['hoarding_area']) {
                                $isCalParaSame = false;
                            }
                            if (date("Y-m-01", strtotime($saf_dtl['hoarding_installation_date'])) != date("Y-m-01", strtotime($FV_Saf_Dtl['hoarding_installation_date']))) {
                                $isCalParaSame = false;
                            }
                        }
                        if ($saf_dtl['is_petrol_pump'] != $FV_Saf_Dtl['is_petrol_pump']) {
                            $isCalParaSame = false;
                        }
                        if ($saf_dtl['is_petrol_pump'] == 't') {
                            if ($saf_dtl['under_ground_area'] != $FV_Saf_Dtl['under_ground_area']) {
                                $isCalParaSame = false;
                            }
                            if (date("Y-m-01", strtotime($saf_dtl['petrol_pump_completion_date'])) != date("Y-m-01", strtotime($FV_Saf_Dtl['petrol_pump_completion_date']))) {
                                $isCalParaSame = false;
                            }
                        }
                        if ($saf_dtl['is_water_harvesting'] != $FV_Saf_Dtl['is_water_harvesting']) {
                            $isCalParaSame = false;
                        }
                    }
                    if ($saf_dtl['prop_type_mstr_id'] != 4 && $isCalParaSame == true) {
                        $saf_Floor_Len = 0;
                        $FV_Saf_Floor_Len = 0;
                        if ($saf_floor_dtl = $this->model_saf_floor_details->getDataBySafDtlId($input)) {
                            $saf_Floor_Len = sizeof($saf_floor_dtl);
                        }

                        if ($FV_Saf_floor_Dtl = $this->model_field_verification_floor_details->getUlbDataBySafDtlId($input)) {
                            $FV_Saf_Floor_Len = sizeof($FV_Saf_floor_Dtl);
                        }

                        if ($saf_Floor_Len == $FV_Saf_Floor_Len) {
                            foreach ($saf_floor_dtl as $key => $floor_dtl) {
                                $input = [
                                    'field_verification_dtl_id' => $FV_Saf_Dtl['id'],
                                    'saf_dtl_id' => $saf_dtl['id'],
                                    'saf_floor_dtl_id' => $floor_dtl['id'],
                                    'floor_mstr_id' => $floor_dtl['floor_mstr_id'],
                                    'usage_type_mstr_id' => $floor_dtl['usage_type_mstr_id'],
                                    'const_type_mstr_id' => $floor_dtl['const_type_mstr_id'],
                                    'occupancy_type_mstr_id' => $floor_dtl['occupancy_type_mstr_id'],
                                    'builtup_area' => $floor_dtl['builtup_area'],
                                    'date_from' => $floor_dtl['date_from'],
                                    'date_upto' => $floor_dtl['date_upto'],
                                ];
                                if (!$this->model_field_verification_floor_details->CheckUlbDataIsSameBySafFloorDtl($input)) {
                                    $isCalParaSame = false;
                                }
                            }
                        } else {
                            $isCalParaSame = false;
                        }
                    }
                }

                $this->db->transBegin();
                if ($isCalParaSame == false) {
                    //print_var($data["ufieldVerificationmstr_detail"]);
                    //print_var($FV_Saf_Dtl);
                    //print_var($inputs);

                    $inputs = $FV_Saf_Dtl;
                    $input = ['saf_dtl_id' => $saf_dtl_id];
                    $propDtlId = $this->model_prop_dtl->getPropDtlIdBySafDtlId($input);
                    $inputs['prop_dtl_id'] = $propDtlId['id'];

                    if ($FV_Saf_floor_Dtl = $this->model_field_verification_floor_details->getUlbDataBySafDtlId($input)) {
                        $inputs['floor_dtl'] = $FV_Saf_floor_Dtl;
                    }

                    // ARV & TAX CALCULATION of Vacant Land
                    if ($inputs['prop_type_mstr_id'] == 4) {
                        // current fimamcial year
                        $currentFY = getFY();
                        $currentFyID = $this->model_fy_mstr->getFyByFy(['fy' => $currentFY])['id'];

                        // date of effect
                        $yrOfEffect_16_17_FY = getFY("2016-04-01");
                        $yrOfEffect_16_17_FyID = $this->model_fy_mstr->getFyByFy(['fy' => $yrOfEffect_16_17_FY])['id'];

                        // vacant land details
                        $vacantlandArea = ($inputs['area_of_plot'] * 40.5);
                        $mobileTowerArea =  $hoardingBoardArea = 0;

                        $inputs['land_occupation_date'] = $data['land_occupation_date'];

                        $isVacantLand = $isMobileTower = $isHoldingBoard = false;
                        $vacand_land_qtr = $mobile_tower_qtr = $hoarding_board_qtr = 0;
                        // date of effect
                        //$yrOfEffectFY = getFY("2016-04-01");
                        //$yrOfEffectFyID = $this->model_fy_mstr->getFyByFy(['fy'=>$yrOfEffectFY])['id'];
                        $FromFixEffectFyID =  $yrOfEffect_16_17_FyID;



                        // acquisition fy
                        $acquisitionFY = getFY($inputs['land_occupation_date']);
                        $acquisitionFyID = $this->model_fy_mstr->getFyByFy(['fy' => $acquisitionFY])['id'];
                        if ($yrOfEffect_16_17_FyID > $acquisitionFyID) {
                            $acquisitionFyID = $yrOfEffect_16_17_FyID;
                            $inputs['land_occupation_date'] = "2016-04-01";
                        }
                        if ($yrOfEffect_16_17_FyID < $acquisitionFyID) {
                            $FromFixEffectFyID = $acquisitionFyID;
                        }

                        $MM = date("m", strtotime($inputs['land_occupation_date']));
                        if ($MM >= 1 && 3 >= $MM) { // X1
                            $temp_qtr = 4;
                        } else if ($MM >= 4 && 6 >= $MM) { // X4
                            $temp_qtr = 1;
                        } else if ($MM >= 7 && 9 >= $MM) { // X3
                            $temp_qtr = 2;
                        } else if ($MM >= 10 && 12 >= $MM) { // X2
                            $temp_qtr = 3;
                        } else {
                        }
                        $isVacantLand = true;
                        $vacand_land_qtr = $temp_qtr;

                        if ($inputs['is_mobile_tower'] == 1) {

                            $mobileTowerFY = getFY($inputs['tower_installation_date']);
                            $mobileTowerFyID = $this->model_fy_mstr->getFyByFy(['fy' => $mobileTowerFY])['id'];
                            if ($FromFixEffectFyID > $mobileTowerFyID) {
                                $mobileTowerFyID = $FromFixEffectFyID;
                                $inputs['tower_installation_date'] = "2016-04-01";
                            }
                            $MM = date("m", strtotime($inputs['tower_installation_date']));
                            if ($MM >= 1 && 3 >= $MM) { // X1
                                $temp_qtr = 4;
                            } else if ($MM >= 4 && 6 >= $MM) { // X4
                                $temp_qtr = 1;
                            } else if ($MM >= 7 && 9 >= $MM) { // X3
                                $temp_qtr = 2;
                            } else if ($MM >= 10 && 12 >= $MM) { // X2
                                $temp_qtr = 3;
                            } else {
                            }
                            $mobileTowerArea = $inputs['tower_area'] * 0.092903;
                            $isMobileTower = true;
                            $mobile_tower_qtr = $temp_qtr;
                        }
                        if ($inputs['is_hoarding_board'] == 1) {
                            $hoardinBoardFY = getFY($inputs['hoarding_installation_date']);
                            $hoardinBoardFyID = $this->model_fy_mstr->getFyByFy(['fy' => $hoardinBoardFY])['id'];
                            if ($yrOfEffect_16_17_FyID > $hoardinBoardFyID) {
                                $hoardinBoardFyID = $FromFixEffectFyID;
                                $inputs['hoarding_installation_date'] = "2016-04-01";
                            }
                            $MM = date("m", strtotime($inputs['hoarding_installation_date']));
                            if ($MM >= 1 && 3 >= $MM) { // X1
                                $temp_qtr = 4;
                            } else if ($MM >= 4 && 6 >= $MM) { // X4
                                $temp_qtr = 1;
                            } else if ($MM >= 7 && 9 >= $MM) { // X3
                                $temp_qtr = 2;
                            } else if ($MM >= 10 && 12 >= $MM) { // X2
                                $temp_qtr = 3;
                            } else {
                            }
                            $hoardingBoardArea = $inputs['hoarding_area'] * 0.092903;
                            $isHoldingBoard = true;
                            $hoarding_board_qtr = $temp_qtr;
                        }

                        $getFyList = $this->model_fy_mstr->getFyByIdRangeAsc(['fromId' => $FromFixEffectFyID, 'toId' => $currentFyID]);

                        $vacantLandDtl = [];
                        $isSafVacantLandMH = false;
                        $vacantLandMHDtl = [];
                        $vacantLandMHDtlIncreament = 0;
                        $safTaxDtl = [];
                        $safTaxIncreament = 0;

                        $mobileTowerOneTimeImpliment = false;
                        $hoadingBoardOneTimeImpliment = false;
                        foreach ($getFyList as $fyVal) {
                            $dateOfEffect = (explode("-", $fyVal['fy'])[1]) . "-04-01";

                            $vacand_land_qtr_temp = $mobile_tower_qtr_temp = $hoarding_board_qtr_temp = 0;
                            $isVacantLandTemp = $isMobileTowerTemp = $isHoldingBoardTemp = false;
                            $isMobileTowerIncreaseTemp = $isHoldingBoardIncreaseTemp = false;

                            $isExist = false;
                            $totalTax = 0;

                            $lastArvDtl = [];
                            $lastIncreament = -1;

                            // vacand land
                            if ($fyVal['id'] == $acquisitionFyID) {
                                $isVacantLandTemp = true;
                                $vacand_land_qtr_temp = $vacand_land_qtr;
                            }
                            // mobile tower
                            if ($isMobileTower == true) {
                                if ($fyVal['id'] == $mobileTowerFyID) {
                                    $isMobileTowerTemp = true;
                                    $mobile_tower_qtr_temp = $mobile_tower_qtr;
                                }
                                if ($fyVal['id'] > $mobileTowerFyID) {
                                    $isMobileTowerIncreaseTemp = true;
                                }
                            }
                            // Hording Board
                            if ($isHoldingBoard == true) {
                                if ($fyVal['id'] == $hoardinBoardFyID) {
                                    $isHoldingBoardTemp = true;
                                    $hoarding_board_qtr_temp = $hoarding_board_qtr;
                                }
                                if ($fyVal['id'] > $hoardinBoardFyID) {
                                    $isHoldingBoardIncreaseTemp = true;
                                }
                            }

                            if ($isVacantLandTemp || $isMobileTowerTemp || $isHoldingBoardTemp) {
                                $sendInput = ['road_type_mstr_id' => $inputs['road_type_mstr_id'], 'date_of_effect' => $dateOfEffect];
                                $mrr = $this->model_arr_vacant_mstr->getMRRCalRate($sendInput)['rate'];
                                if (!$mrr) {
                                    $mrr = 0;
                                }

                                $arrShort = array('vacand' => $vacand_land_qtr_temp, 'mobile' => $mobile_tower_qtr_temp, 'hording' => $hoarding_board_qtr_temp);
                                asort($arrShort);

                                foreach ($arrShort as $keyy => $x_Qtr) {
                                    if ($keyy == "vacand" && $x_Qtr != 0) {
                                        $isExist = true;
                                        $calVacandLandArea = $vacantlandArea;
                                        $vacandLandTax = 0;
                                        if ($isMobileTowerTemp == true && $x_Qtr == $mobile_tower_qtr_temp) {
                                            $calVacandLandArea = $calVacandLandArea - $mobileTowerArea;
                                        }
                                        if ($isHoldingBoardTemp == true && $x_Qtr == $hoarding_board_qtr_temp) {
                                            $calVacandLandArea = $calVacandLandArea - $hoardingBoardArea;
                                        }
                                        $vacantLandTax = $calVacandLandArea * $mrr;
                                        $totalTax += $vacantLandTax;

                                        $vacantLandDtl = [
                                            'vacant_land_area_sqm' => $vacantlandArea,
                                            'applied_rate' => $mrr,
                                            'yearly_holding_tax' => round($vacantlandArea * $mrr),
                                            'qtr_holding_tax' => round(($vacantlandArea * $mrr) / 4),
                                            'vacant_land_area_sqft' => ($vacantlandArea * 0.092903),
                                            'fy' => $fyVal['fy'],
                                            'qtr' => $x_Qtr,
                                        ];

                                        $lastIncreament++;
                                        $lastArvDtl[$lastIncreament] = [
                                            'fyID' => $fyVal['id'],
                                            'fy' => $fyVal['fy'],
                                            'qtr' => $x_Qtr,
                                            'arv' => 0,
                                            'holding_tax_yearly' => $totalTax
                                        ];
                                    }

                                    if ($keyy == "mobile" && $x_Qtr != 0) {
                                        if (!$mobileTowerOneTimeImpliment) {
                                            $mobileTowerOneTimeImpliment = true;
                                            $hordingBoardTax = 0;
                                            if ($isVacantLandTemp == false || $x_Qtr != $vacand_land_qtr_temp) {
                                                $calVacandLandArea = $vacantlandArea;
                                                if ($isHoldingBoardIncreaseTemp == true && $x_Qtr != $mobile_tower_qtr_temp && $hoadingBoardOneTimeImpliment == true) {
                                                    $calVacandLandArea = $calVacandLandArea - $hoardingBoardArea;
                                                    $hordingBoardTax = $hoardingBoardArea * $mrr * 1.5;
                                                }
                                                $calVacandLandArea = $calVacandLandArea - $mobileTowerArea;
                                                $vacantLandTax = $calVacandLandArea * $mrr * 1;
                                                $totalTax = $vacantLandTax;
                                            }
                                            $mobileTowerTax = $mobileTowerArea * $mrr * 1.5;
                                            $totalTax += $mobileTowerTax + $hordingBoardTax;

                                            $isExist = true;
                                            foreach ($lastArvDtl as $key => $mobileVal) {
                                                if (
                                                    $mobileVal['fyID'] == $fyVal['id']
                                                    && $mobileVal['qtr'] == $x_Qtr
                                                ) {

                                                    $isExist = false;
                                                    $lastArvDtl[$lastIncreament] = [
                                                        'fyID' => $fyVal['id'],
                                                        'fy' => $fyVal['fy'],
                                                        'qtr' => $x_Qtr,
                                                        'arv' => 0,
                                                        'holding_tax_yearly' => $totalTax
                                                    ];
                                                }
                                            }
                                            if ($isExist) {
                                                $lastIncreament++;
                                                $lastArvDtl[$lastIncreament] = [
                                                    'fyID' => $fyVal['id'],
                                                    'fy' => $fyVal['fy'],
                                                    'qtr' => $x_Qtr,
                                                    'arv' => 0,
                                                    'holding_tax_yearly' => $totalTax
                                                ];
                                            }

                                            $isSafVacantLandMH = true;
                                            $vacantLandMHDtl[$vacantLandMHDtlIncreament] = [
                                                'type' => 'MOBILE TOWER',
                                                'area_sqm' => $mobileTowerArea,
                                                'usage_factor' => $mrr,
                                                'occupancy_factor' => 1.5,
                                                'fy' => $fyVal['fy'],
                                                'qtr' => $x_Qtr,
                                                'yearly_tax' => $mobileTowerTax
                                            ];
                                            $vacantLandMHDtlIncreament++;
                                        }
                                    }

                                    if ($keyy == "hording" && $x_Qtr != 0) {
                                        if (!$hoadingBoardOneTimeImpliment) {
                                            $hoadingBoardOneTimeImpliment = true;
                                            $mobileTowerTax = 0;
                                            if ($isVacantLandTemp == false || $x_Qtr != $vacand_land_qtr_temp) {
                                                $calVacandLandArea = $vacantlandArea;
                                                if ($isMobileTowerIncreaseTemp == true && $x_Qtr != $mobile_tower_qtr_temp && $mobileTowerOneTimeImpliment == true) {
                                                    $calVacandLandArea = $calVacandLandArea - $mobileTowerArea;
                                                    $mobileTowerTax = $mobileTowerArea * $mrr * 1.5;
                                                }
                                                $calVacandLandArea = $calVacandLandArea - $hoardingBoardArea;
                                                $vacantLandTax = $calVacandLandArea * $mrr * 1;
                                                $totalTax = $vacantLandTax;
                                            }
                                            $hordingBoardTax = $hoardingBoardArea * $mrr * 1.5;
                                            $totalTax += $hordingBoardTax + $mobileTowerTax;

                                            $isExist = true;
                                            foreach ($lastArvDtl as $key => $mobileVal) {
                                                if (
                                                    $mobileVal['fyID'] == $fyVal['id']
                                                    && $mobileVal['qtr'] == $x_Qtr
                                                ) {

                                                    $isExist = false;
                                                    $lastArvDtl[$lastIncreament] = [
                                                        'fyID' => $fyVal['id'],
                                                        'fy' => $fyVal['fy'],
                                                        'qtr' => $x_Qtr,
                                                        'arv' => 0,
                                                        'holding_tax_yearly' => $totalTax
                                                    ];
                                                }
                                            }
                                            if ($isExist) {
                                                $lastIncreament++;
                                                $lastArvDtl[$lastIncreament] = [
                                                    'fyID' => $fyVal['id'],
                                                    'fy' => $fyVal['fy'],
                                                    'qtr' => $x_Qtr,
                                                    'arv' => 0,
                                                    'holding_tax_yearly' => $totalTax
                                                ];
                                            }
                                            $isSafVacantLandMH = true;
                                            $vacantLandMHDtl[$vacantLandMHDtlIncreament] = [
                                                'type' => 'HOARDING BOARD',
                                                'area_sqm' => $hoardingBoardArea,
                                                'usage_factor' => $mrr,
                                                'occupancy_factor' => 1.5,
                                                'fy' => $fyVal['fy'],
                                                'qtr' => $x_Qtr,
                                                'yearly_tax' => $hordingBoardTax
                                            ];
                                            $vacantLandMHDtlIncreament++;
                                        }
                                    }
                                }
                            }

                            foreach ($lastArvDtl as $key => $value) {
                                $safTaxDtl[$safTaxIncreament] = [
                                    'fyID' => $fyVal['id'],
                                    'fy' => $fyVal['fy'],
                                    'qtr' => $x_Qtr,
                                    'arv' => 0,
                                    'holding_tax_yearly' => round($totalTax, 2),
                                    'holding_tax_qtr' => round(($totalTax / 4), 2)
                                ];
                                $safTaxIncreament++;
                            }
                        } // end financial year foreach
                        $data['vacantLandDtl'] = $vacantLandDtl;
                        $data['safTaxDtl'] = $safTaxDtl;
                        $data['isSafVacantLandMH'] = $isSafVacantLandMH;
                        $data['vacantLandMHDtl'] = $vacantLandMHDtl;

                        // insert tax details
                        $currentFY = getFY();
                        $currentFyId = $this->model_fy_mstr->getFyByFy(['fy' => $currentFY])['id'];
                        $safTaxDtlLen = sizeof($safTaxDtl);
                        $i = 0;

                        $last_fy_mstr_id = 0;
                        $last_qtr = 0;
                        $last_holding_tax = 0;

                        $next_fy_mstr_id = 0;
                        $next_qtr = 0;
                        $next_holding_tax = 0;
                        $holding_tax = 0;

                        $penalty_amount = 0;

                        $input = [
                            'prop_dtl_id' => $inputs['prop_dtl_id'],
                        ];
                        $this->model_prop_tax->updateSafDeactiveStatus($input);


                        $prop_dtl_tax_temp = $this->model_prop_tax->getdetails_propdtlid($inputs['prop_dtl_id']);
                        $diffTax = [];
                        foreach ($safTaxDtl as $key => $safTax) {
                            $_fy_id = $safTax['fyID'];
                            $_fy = $safTax['fy'];
                            $_qtr = $safTax['qtr'];
                            $_arv = $safTax['arv'];
                            $_holding_tax = $safTax['holding_tax'];
                            $_water_tax = $safTax['water_tax'];
                            $_education_cess = $safTax['education_cess'];
                            $_health_cess = $safTax['health_cess'];
                            $_latrine_tax = $safTax['latrine_tax'];
                            $_additional_tax = $safTax['additional_tax'];

                            foreach ($prop_dtl_tax_temp as $keyy => $safTaxTemp) {
                                if ($safTaxTemp['fy_mstr_id'] == $safTax['fy_mstr_id'] && $safTaxTemp['qtr'] == $safTax['qtr']) {
                                    $_arv -= $safTaxTemp['arv'];
                                    $_holding_tax -= $safTaxTemp['holding_tax'];
                                    $_water_tax -= $safTaxTemp['water_tax'];
                                    $_education_cess -= $safTaxTemp['education_cess'];
                                    $_health_cess -= $safTaxTemp['health_cess'];
                                    $_latrine_tax -= $safTaxTemp['latrine_tax'];
                                    $_additional_tax -= $safTaxTemp['additional_tax'];
                                }
                            }
                            if ($_arv > 0) {
                                $diffTax['fy_id'] = $_fy_id;
                                $diffTax['fy'] = $_fy;
                                $diffTax['qtr'] = $_qtr;
                                $diffTax['arv'] = $_arv;
                                $diffTax['holding_tax'] = $_holding_tax;
                                $diffTax['water_tax'] = $_water_tax;
                                $diffTax['education_cess'] = $_education_cess;
                                $diffTax['health_cess'] = $_health_cess;
                                $diffTax['latrine_tax'] = $_latrine_tax;
                            }
                        }


                        foreach ($safTaxDtl as $key => $safTax) {
                            $i++;
                            $input = [
                                'prop_dtl_id' => $inputs['prop_dtl_id'],
                                'fy_mstr_id' => $safTax['fyID'],
                                'qtr' => $safTax['qtr'],
                                'arv' => 0,
                                'holding_tax' => $safTax['holding_tax_qtr'],
                                'water_tax' => 0,
                                'education_cess' => 0,
                                'health_cess' => 0,
                                'latrine_tax' => 0,
                                'created_on' => $created_on,
                                'status' => 1
                            ];
                            $prop_tax_id = $this->model_prop_tax->insertData($input);

                            $input['saf_dtl_id'] = $inputs['saf_dtl_id'];
                            $prop_tax_print_id = $this->model_prop_tax_print->insertData($input);

                            $holding_tax_qtr = $safTax['holding_tax_qtr'];
                            $amount_qtr = $holding_tax_qtr;

                            if ($safTaxDtlLen == $i) {
                                $next_fy_mstr_id = $currentFyId;
                                $next_qtr = 4;
                            } else {
                                $next_fy_mstr_id = $safTaxDtl[$key + 1]['fyID'];
                                $next_qtr = $safTaxDtl[$key + 1]['qtr'];
                            }
                            for ($j = $safTax['fyID']; $j <= $next_fy_mstr_id; $j++) {
                                $zz = 1;
                                if ($j == $safTax['fyID']) {
                                    $zz = $safTax['qtr'];
                                }
                                $zzz = 4;
                                if ($j == $next_qtr) {
                                    $zzz = $safTax['qtr'];
                                }
                                for ($z = $zz; $z <= $zzz; $z++) {
                                    if ($next_fy_mstr_id == $j && $z == $next_qtr) {
                                        if ($next_fy_mstr_id == $currentFyID && $next_qtr == 4) {
                                            $inputCheckTotal = [
                                                'prop_dtl_id' => $inputs['prop_dtl_id'],
                                                'fy_mstr_id' => $j,
                                                'qtr' => $z
                                            ];

                                            $saf_prop_demand = 0;

                                            if ($saf_demand_dtl = $this->model_view_saf_dtl_demand->getSumDemandBySAFDtlIdFyIdQtr($inputCheckTotal)) {
                                                $saf_prop_demand += $saf_demand_dtl['amount'];
                                            }

                                            if ($prop_demand_dtl = $this->model_prop_demand->getSumDemandByPropDtlIdFyIdQtr($inputCheckTotal)) {
                                                $saf_prop_demand += $prop_demand_dtl['amount'];
                                            }

                                            if ($saf_prop_demand < $amount_qtr) {
                                                $penalty_amount += $amount_qtr - $saf_prop_demand;
                                                // greater
                                                if ($this->model_prop_demand->checkDemandPaidStatusZeroByPropDtlIdFyIdQtr($inputCheckTotal)) {

                                                    $this->model_prop_demand->deleteDemandByPropDtlIdFyIdQtr($inputCheckTotal);

                                                    $input = [
                                                        'prop_dtl_id' => $inputs['prop_dtl_id'],
                                                        'prop_tax_id' => $prop_tax_id,
                                                        'fy_mstr_id' => $j,
                                                        'qtr' => $z,
                                                        'amount' => $amount_qtr,
                                                        'balance' => $amount_qtr,
                                                        'fine_tax' => 0,
                                                        'created_on' => $created_on,
                                                        'status' => 1
                                                    ];
                                                    $this->model_prop_demand->insertData($input);
                                                } else {
                                                    if ($this->model_view_saf_dtl_demand->checkDemandIsExistBySafDtlIdFyIdQtr($inputCheckTotal)) {
                                                        $remaining_amt = $amount_qtr - $saf_prop_demand;
                                                        $input = [
                                                            'prop_dtl_id' => $inputs['prop_dtl_id'],
                                                            'prop_tax_id' => $prop_tax_id,
                                                            'fy_mstr_id' => $j,
                                                            'qtr' => $z,
                                                            'amount' => $remaining_amt,
                                                            'balance' => $remaining_amt,
                                                            'fine_tax' => 0,
                                                            'created_on' => $created_on,
                                                            'status' => 1
                                                        ];
                                                        $this->model_prop_demand->insertData($input);
                                                    } else {
                                                        $input = [
                                                            'prop_dtl_id' => $inputs['prop_dtl_id'],
                                                            'prop_tax_id' => $prop_tax_id,
                                                            'fy_mstr_id' => $j,
                                                            'qtr' => $z,
                                                            'amount' => $amount_qtr,
                                                            'balance' => $amount_qtr,
                                                            'fine_tax' => 0,
                                                            'created_on' => $created_on,
                                                            'status' => 1
                                                        ];
                                                        $this->model_prop_demand->insertData($input);
                                                    }
                                                }
                                            } else if ($saf_prop_demand > $amount_qtr) {
                                                // smallest
                                                $remaining_amt = round($saf_prop_demand - $amount_qtr);
                                            }
                                        } else {
                                            break;
                                        }
                                    } else {
                                        $inputCheckTotal = [
                                            'saf_dtl_id' => $inputs['saf_dtl_id'],
                                            'prop_dtl_id' => $inputs['prop_dtl_id'],
                                            'fy_mstr_id' => $j,
                                            'qtr' => $z
                                        ];

                                        $demand_status = true;
                                        $saf_prop_demand = 0;

                                        if ($saf_demand_dtl = $this->model_view_saf_dtl_demand->getSumDemandBySAFDtlIdFyIdQtr($inputCheckTotal)) {
                                            $saf_prop_demand += $saf_demand_dtl['amount'];
                                        }

                                        if ($prop_demand_dtl = $this->model_prop_demand->getSumDemandByPropDtlIdFyIdQtr($inputCheckTotal)) {
                                            $saf_prop_demand += $prop_demand_dtl['amount'];
                                        }
                                        if ($saf_prop_demand < $amount_qtr) {
                                            $penalty_amount += $amount_qtr - $saf_prop_demand;
                                            // greater
                                            if ($this->model_prop_demand->checkDemandPaidStatusZeroByPropDtlIdFyIdQtr($inputCheckTotal)) {

                                                $this->model_prop_demand->deleteDemandByPropDtlIdFyIdQtr($inputCheckTotal);

                                                $input = [
                                                    'prop_dtl_id' => $inputs['prop_dtl_id'],
                                                    'prop_tax_id' => $prop_tax_id,
                                                    'fy_mstr_id' => $j,
                                                    'qtr' => $z,
                                                    'amount' => $amount_qtr,
                                                    'balance' => $amount_qtr,
                                                    'fine_tax' => 0,
                                                    'created_on' => $created_on,
                                                    'status' => 1
                                                ];
                                                $this->model_prop_demand->insertData($input);
                                            } else {
                                                if ($this->model_view_saf_dtl_demand->checkDemandIsExistBySafDtlIdFyIdQtr($inputCheckTotal)) {
                                                    $remaining_amt = $amount_qtr - $saf_prop_demand;
                                                    $input = [
                                                        'prop_dtl_id' => $inputs['prop_dtl_id'],
                                                        'prop_tax_id' => $prop_tax_id,
                                                        'fy_mstr_id' => $j,
                                                        'qtr' => $z,
                                                        'amount' => $remaining_amt,
                                                        'balance' => $remaining_amt,
                                                        'fine_tax' => 0,
                                                        'created_on' => $created_on,
                                                        'status' => 1
                                                    ];
                                                    $this->model_prop_demand->insertData($input);
                                                } else {
                                                    $input = [
                                                        'prop_dtl_id' => $inputs['prop_dtl_id'],
                                                        'prop_tax_id' => $prop_tax_id,
                                                        'fy_mstr_id' => $j,
                                                        'qtr' => $z,
                                                        'amount' => $amount_qtr,
                                                        'balance' => $amount_qtr,
                                                        'fine_tax' => 0,
                                                        'created_on' => $created_on,
                                                        'status' => 1
                                                    ];
                                                    $this->model_prop_demand->insertData($input);
                                                }
                                            }
                                        } else if ($prop_demand_dtl['amount'] > $amount_qtr) {
                                            // smallest
                                            //$remaining_amt = ($prop_demand_dtl['amount'] - $amount_qtr);
                                        }
                                    }
                                }
                            }
                        }

                        if (isset($diffTax) && !empty($diffTax)) {
                            if ($diffTax['holding_tax'] > 0) {
                                $input = [
                                    'prop_dtl_id' => $inputs['prop_dtl_id'],
                                    'penalty_amt' => round($diffTax['holding_tax'] * 4, 2),
                                    'penalty_type' => 'difference_penalty',
                                    'created_on' => $created_on,
                                    'status' => 1
                                ];
                                $this->model_penalty_dtl->insertData($input);
                            }
                        }
                    } else {
                        // building
                        $currentFY = getFY();
                        $currentFyID = $this->model_fy_mstr->getFyByFy(['fy' => $currentFY])['id'];

                        $taxEffectedFrom = date('Y-04-01', strtotime('-12 year'));
                        $taxEffectedFromFY = getFY($taxEffectedFrom);
                        $taxEffectedFromFyID = $this->model_fy_mstr->getFyByFy(['fy' => $taxEffectedFromFY])['id'];

                        $yrOfEffect_16_17_FY = getFY("2016-04-01");
                        $yrOfEffect_16_17_FyID = $this->model_fy_mstr->getFyByFy(['fy' => $yrOfEffect_16_17_FY])['id'];

                        $is_16_17_1st_qtr_tax_implement = false;

                        $floorDtlArr = [];
                        $j = 0;


                        foreach ($inputs['floor_dtl'] as $key => $floor_dtl) {
                            $floorDateFromFY = getFY($floor_dtl['date_from']);
                            $floorDateFromFyID = $this->model_fy_mstr->getFyByFy(['fy' => $floorDateFromFY])['id'];
                            $MM = date("m", strtotime($floor_dtl['date_from']));
                            if ($MM >= 1 && 3 >= $MM) { // X1
                                $temp_qtr = 4;
                            } else if ($MM >= 4 && 6 >= $MM) { // X4
                                $temp_qtr = 1;
                            } else if ($MM >= 7 && 9 >= $MM) { // X3
                                $temp_qtr = 2;
                            } else if ($MM >= 10 && 12 >= $MM) { // X2
                                $temp_qtr = 3;
                            }

                            $floorDateUptoFyID = 0;
                            $floorDateUptoQtr = 0;
                            $floorDateUptoQtrTemp = 0;

                            if ($floor_dtl['date_upto'] <> "") {
                                $floorDateUptoFY = getFY($floor_dtl['date_upto']);
                                $floorDateUptoFyID = $this->model_fy_mstr->getFyByFy(['fy' => $floorDateUptoFY])['id'];
                                $MM = date("m", strtotime($floor_dtl['date_upto']));
                                if ($MM >= 1 && 3 >= $MM) { // X1
                                    $floorDateUptoQtr = 4;
                                } else if ($MM >= 4 && 6 >= $MM) { // X4
                                    $floorDateUptoQtr = 1;
                                } else if ($MM >= 7 && 9 >= $MM) { // X3
                                    $floorDateUptoQtr = 2;
                                } else if ($MM >= 10 && 12 >= $MM) { // X2
                                    $floorDateUptoQtr = 3;
                                }
                                $floorDateUptoQtrTemp = $floorDateUptoQtr;
                            }

                            if ($floor_dtl['date_from'] . "-01" < $taxEffectedFrom) {
                                $floorDateFromFyID = $taxEffectedFromFyID;
                            }
                            $floorDtlArr[$j] = [
                                'type' => 'floor',
                                'floor_mstr_id' => $floor_dtl['floor_mstr_id'],
                                'usage_type_mstr_id' => $floor_dtl['usage_type_mstr_id'],
                                'occupancy_type_mstr_id' => $floor_dtl['occupancy_type_mstr_id'],
                                'const_type_mstr_id' => $floor_dtl['const_type_mstr_id'],
                                'builtup_area' => $floor_dtl['builtup_area'],
                                'date_from' => $floor_dtl['date_from'],
                                'date_upto' => $floor_dtl['date_upto'],
                                'fy_mstr_id' => $floorDateFromFyID,
                                'qtr' => $temp_qtr,
                                'upto_fy_mstr_id' => $floorDateUptoFyID,
                                'upto_qtr' => $floorDateUptoQtr,
                                'operator' => '+'
                            ];
                            $j++;

                            if ($floorDateUptoFyID <> 0 && $floorDateUptoQtr <> 0) {
                                if ($floorDateUptoQtr == 4) {
                                    $floorDateUptoQtr = 1;
                                    $floorDateUptoFyID = $floorDateUptoFyID + 1;
                                } else {
                                    $floorDateUptoQtr = $floorDateUptoQtr + 1;
                                }
                                $date_upto = $floor_dtl['date_upto'];
                                if ($floorDateUptoQtrTemp == 1) {
                                    $date_upto = date("Y", strtotime($inputs['date_upto'][$i])) . "-09";
                                } else if ($floorDateUptoQtrTemp == 2) {
                                    $date_upto = date("Y", strtotime($inputs['date_upto'][$i])) . "-12";
                                } else if ($floorDateUptoQtrTemp == 3) {
                                    $YYYY = date("Y", strtotime($inputs['date_upto'][$i]));
                                    $YYYY = $YYYY + 1;
                                    $date_upto = $YYYY . "-03";
                                } else if ($floorDateUptoQtrTemp == 4) {
                                    $date_upto = date("Y", strtotime($inputs['date_upto'][$i])) . "-06";
                                }
                                $floorDtlArr[$j] = [
                                    'type' => 'floor',
                                    'floor_mstr_id' => $floor_dtl['floor_mstr_id'],
                                    'usage_type_mstr_id' => $floor_dtl['usage_type_mstr_id'],
                                    'occupancy_type_mstr_id' => $floor_dtl['occupancy_type_mstr_id'],
                                    'const_type_mstr_id' => $floor_dtl['const_type_mstr_id'],
                                    'builtup_area' => $floor_dtl['builtup_area'][$i],
                                    'date_from' => $date_upto,
                                    'date_upto' => $date_upto,
                                    'fy_mstr_id' => $floorDateUptoFyID,
                                    'qtr' => $floorDateUptoQtr,
                                    'upto_fy_mstr_id' => $floorDateUptoFyID,
                                    'upto_qtr' => $floorDateUptoQtr,
                                    'operator' => '-'
                                ];
                                $j++;
                            }
                        }

                        $mobileTowerFyID = 0;
                        $mobileTowerQtr = 0;
                        if ($inputs['is_mobile_tower'] == true) {
                            $mobileTowerFY = getFY($inputs['tower_installation_date']);
                            $mobileTowerFyID = $this->model_fy_mstr->getFyByFy(['fy' => $mobileTowerFY])['id'];
                            $MM = date("m", strtotime($inputs['tower_installation_date']));
                            if ($MM >= 1 && 3 >= $MM) { // X1
                                $temp_qtr = 4;
                            } else if ($MM >= 4 && 6 >= $MM) { // X4
                                $temp_qtr = 1;
                            } else if ($MM >= 7 && 9 >= $MM) { // X3
                                $temp_qtr = 2;
                            } else if ($MM >= 10 && 12 >= $MM) { // X2
                                $temp_qtr = 3;
                            }
                            $mobileTowerQtr = $temp_qtr;
                            if ($yrOfEffect_16_17_FyID == $mobileTowerFyID && $temp_qtr == 1) {
                                $is_16_17_1st_qtr_tax_implement = true;
                            }
                            $date_from = "2016-04";
                            if (date("Y-m-01", strtotime($inputs['tower_installation_date'])) > "2016-04-01") {
                                $date_from = date("Y-m", strtotime($inputs['tower_installation_date']));
                            } else {
                                $mobileTowerFyID = $yrOfEffect_16_17_FyID;
                                $mobileTowerQtr = 1;
                            }

                            $floorDtlArr[$j] = [
                                'type' => 'mobile',
                                'floor_mstr_id' => 0,
                                'usage_type_mstr_id' => 0,
                                'occupancy_type_mstr_id' => 0,
                                'const_type_mstr_id' => 1,
                                'builtup_area' => $inputs['tower_area'],
                                'date_from' => $date_from,
                                'date_upto' => "",
                                'fy_mstr_id' => $mobileTowerFyID,
                                'qtr' => $mobileTowerQtr,
                                'upto_fy_mstr_id' => 0,
                                'upto_qtr' => 0,
                                'operator' => '+'
                            ];
                            $j++;
                        }

                        $hoardinBoardFyID = 0;
                        $hoardinBoardQtr = 0;
                        if ($inputs['is_hoarding_board'] == TRUE) {
                            $hoardinBoardFY = getFY($inputs['hoarding_installation_date']);
                            $hoardinBoardFyID = $this->model_fy_mstr->getFyByFy(['fy' => $hoardinBoardFY])['id'];
                            $MM = date("m", strtotime($inputs['hoarding_installation_date']));
                            if ($MM >= 1 && 3 >= $MM) { // X1
                                $temp_qtr = 4;
                            } else if ($MM >= 4 && 6 >= $MM) { // X4
                                $temp_qtr = 1;
                            } else if ($MM >= 7 && 9 >= $MM) { // X3
                                $temp_qtr = 2;
                            } else if ($MM >= 10 && 12 >= $MM) { // X2
                                $temp_qtr = 3;
                            }
                            $hoardinBoardQtr = $temp_qtr;


                            $date_from = "2016-04";
                            if (date("Y-m-01", strtotime($inputs['hoarding_installation_date'])) > "2016-04-01") {
                                $date_from = date("Y-m", strtotime($inputs['hoarding_installation_date']));
                            } else {
                                $hoardinBoardFyID = $yrOfEffect_16_17_FyID;
                                $hoardinBoardQtr = 1;
                            }

                            $floorDtlArr[$j] = [
                                'type' => 'hoarding',
                                'floor_mstr_id' => 0,
                                'usage_type_mstr_id' => 0,
                                'occupancy_type_mstr_id' => 0,
                                'const_type_mstr_id' => 1,
                                'builtup_area' => $inputs['hoarding_area'],
                                'date_from' => $date_from,
                                'date_upto' => "",
                                'fy_mstr_id' => $hoardinBoardFyID,
                                'qtr' => $hoardinBoardQtr,
                                'upto_fy_mstr_id' => 0,
                                'upto_qtr' => 0,
                                'operator' => '+'
                            ];
                            $j++;
                        }

                        $petrolPumpFyID = 0;
                        $petrolPumpQtr = 0;
                        if ($inputs['is_petrol_pump'] == TRUE && $inputs['prop_type_mstr_id'] != 4) {
                            $petrolPumpFY = getFY($inputs['petrol_pump_completion_date']);
                            $petrolPumpFyID = $this->model_fy_mstr->getFyByFy(['fy' => $petrolPumpFY])['id'];
                            $MM = date("m", strtotime($inputs['petrol_pump_completion_date']));
                            if ($MM >= 1 && 3 >= $MM) { // X1
                                $temp_qtr = 4;
                            } else if ($MM >= 4 && 6 >= $MM) { // X4
                                $temp_qtr = 1;
                            } else if ($MM >= 7 && 9 >= $MM) { // X3
                                $temp_qtr = 2;
                            } else if ($MM >= 10 && 12 >= $MM) { // X2
                                $temp_qtr = 3;
                            }
                            $petrolPumpQtr = $temp_qtr;

                            $date_from = "2016-04";
                            if (date("Y-m-01", strtotime($inputs['petrol_pump_completion_date'])) > "2016-04-01") {
                                $date_from = date("Y-m", strtotime($inputs['petrol_pump_completion_date']));
                            } else {
                                $petrolPumpFyID = $yrOfEffect_16_17_FyID;
                                $petrolPumpQtr = 1;
                            }

                            $floorDtlArr[$j] = [
                                'type' => 'petrol',
                                'floor_mstr_id' => 0,
                                'usage_type_mstr_id' => 0,
                                'occupancy_type_mstr_id' => 0,
                                'const_type_mstr_id' => 1,
                                'builtup_area' => $inputs['under_ground_area'],
                                'date_from' => $date_from,
                                'date_upto' => "",
                                'fy_mstr_id' => $petrolPumpFyID,
                                'qtr' => $petrolPumpQtr,
                                'upto_fy_mstr_id' => 0,
                                'upto_qtr' => 0,
                                'operator' => '+'
                            ];
                            $j++;
                        }

                        usort($floorDtlArr, 'floor_date_compare');

                        $isWaterHarvesting = false;
                        $area_of_plot = ($inputs['area_of_plot'] * 40.5);
                        if ($area_of_plot > 300) {
                            $isWaterHarvesting = true;
                            if ($inputs['is_water_harvesting'] == 1) {
                                $isWaterHarvesting = false;
                            }
                        }

                        $FromEffectFYID = 0;
                        $prop_type_mstr_arr = array(1, 5);
                        if (in_array($inputs["prop_type_mstr_id"], $prop_type_mstr_arr)) {
                            $FromEffectFYID = $yrOfEffect_16_17_FyID;
                        } else {
                            $FromEffectFYID = $currentFyID - 12;
                        }
                        $getFyList = $this->model_fy_mstr->getFyByIdRangeAsc(['fromId' => $FromEffectFYID, 'toId' => $currentFyID]);

                        $safTaxDtl = [];
                        $safTaxIncreament = 0;


                        foreach ($getFyList as $fyVal) {
                            $totalArv = 0;
                            $totalArvReduce = 0;
                            $dateOfEffect = (explode("-", $fyVal['fy'])[1]) . "-04-01";

                            $lastArvDtl = [];
                            $lastIncreament = -1;
                            $lastQtr = 0;
                            $jj = 0;
                            foreach ($floorDtlArr as $key => $floorDtl) {

                                $floorDateFromFyID = $floorDtl['fy_mstr_id'];

                                if ($fyVal['id'] >= $floorDateFromFyID) {
                                    $floorDateUptoFyID = $currentFyID;
                                    if ($floorDtl['date_upto'] != "") {
                                        $floorDateUptoFY = getFY($floorDtl['date_upto']);
                                        $floorDateUptoFyID = $this->model_fy_mstr->getFyByFy(['fy' => $floorDateUptoFY])['id'];
                                    }

                                    if ($fyVal['id'] <= $floorDateUptoFyID) {
                                        $isArrear = false;
                                        if ($yrOfEffect_16_17_FyID > $fyVal['id']) {
                                            if ($floorDtl['type'] == "floor") {
                                                $isArrear = true;
                                                $carperArea = $floorDtl['builtup_area'];

                                                $usage_type_mstr_id = 2;
                                                if (
                                                    $floorDtl['occupancy_type_mstr_id'] == 1
                                                    && $floorDtl['usage_type_mstr_id'] == 1
                                                ) {
                                                    $usage_type_mstr_id = 1;
                                                }
                                                $sendInput = [
                                                    'usage_type_mstr_id' => $usage_type_mstr_id,
                                                    'const_type_mstr_id' => $floorDtl['const_type_mstr_id'],
                                                    'zone_mstr_id' => $inputs['zone_mstr_id']
                                                ];
                                                $mrrDtl = $this->model_arr_old_building_mstr->getMRRCalRate($sendInput);
                                                $mrr = 0;
                                                $arr_building_id = 0;
                                                if ($mrrDtl) {
                                                    $mrr = $mrrDtl['rate'];
                                                    $arr_building_id = $mrrDtl['id'];
                                                }
                                                $arv = $carperArea * $mrr;

                                                //echo $carperArea."x".$mrr." = ".$arv." => ".$floorDtl['date_from']."<br />";

                                                $arvRebate = 0;
                                                if ($floorDtl['type'] == "floor") {
                                                    if ($usage_type_mstr_id == 1) {
                                                        $arvRebate += ($arv * 30) / 100;
                                                    } else if ($usage_type_mstr_id == 2) {
                                                        $arvRebate += ($arv * 15) / 100;
                                                    }
                                                    if (
                                                        $inputs["prop_type_mstr_id"] == 2
                                                        && $floorDtl['occupancy_type_mstr_id'] == 1
                                                        && $floorDtl['usage_type_mstr_id'] == 1
                                                    ) {
                                                        $rebate_date = $floorDtl['date_from'] . "-01";
                                                        if ("1942-04-01" > $rebate_date) {
                                                            if ($arv != 0) {
                                                                $arvRebate += (($arv * 10) / 100);
                                                            }
                                                        }
                                                    }
                                                }
                                                $arv -= $arvRebate;
                                                if ($floorDtl['operator'] == "+") {
                                                    $totalArv += $arv;
                                                } else if ($floorDtl['operator'] == "-") {
                                                    $totalArv -= $arv;
                                                }

                                                if ($fyVal['id'] == $floorDateFromFyID) {

                                                    if ($floorDtl['type'] == "floor" && $floorDtl['operator'] == "+") {
                                                        $inputDtl = [
                                                            'saf_dtl_id' => $inputs['saf_dtl_id'],
                                                            'floor_mstr_id' => $floorDtl['floor_mstr_id'],
                                                            'usage_type_mstr_id' => $floorDtl['usage_type_mstr_id'],
                                                            'const_type_mstr_id' => $floorDtl['const_type_mstr_id'],
                                                            'occupancy_type_mstr_id' => $floorDtl['occupancy_type_mstr_id'],
                                                            'builtup_area' => $floorDtl['builtup_area'],
                                                            'carpet_area' => $carperArea,
                                                            'date_from' => $floorDtl['date_from'],
                                                            'date_upto' => ($floorDtl['date_upto'] != "") ? $floorDtl['date_upto'] : null,
                                                            'arr_building_type' => 'tbl_arr_old_building_mstr',
                                                            'arr_building_id' => $arr_building_id,
                                                            'arr_building_rate' => $mrr,
                                                            'usage_type_dtl_id' => 0,
                                                            'usage_type_rate' => 0,
                                                            'occupancy_type_rate' => 0,
                                                            'arv' => $arv,
                                                            'fy_mstr_id' => $fyVal['id'],
                                                            'qtr' => $temp_qtr,
                                                            'emp_details_id' => $login_emp_details_id,
                                                            'entry_type' => 'FROM_EO',
                                                            'created_on' => $created_on,
                                                            'status' => 1
                                                        ];
                                                        $this->model_saf_floor_arv_dtl->insertData($inputDtl);
                                                    }

                                                    $temp_qtr = $floorDtl['qtr'];

                                                    if ($lastQtr != $temp_qtr) {
                                                        $lastQtr = $temp_qtr;
                                                        $lastIncreament++;
                                                        $lastArvDtl[$lastIncreament] = [
                                                            'fyID' => $fyVal['id'],
                                                            'arv' => round($totalArv, 2),
                                                            'qtr' => $temp_qtr
                                                        ];
                                                    } else {
                                                        $lastArvDtl[$lastIncreament] = [
                                                            'fyID' => $fyVal['id'],
                                                            'arv' => round($totalArv, 2),
                                                            'qtr' => $temp_qtr
                                                        ];
                                                    }
                                                }
                                            } // only floor effected
                                        } // old rule effected if condition

                                        if ($yrOfEffect_16_17_FyID <= $fyVal['id']) {
                                            if (!$isArrear) {
                                                if ($fyVal['id'] == $yrOfEffect_16_17_FyID) {

                                                    if (!$is_16_17_1st_qtr_tax_implement) {
                                                        $oldARVTotal = 0;
                                                        foreach ($floorDtlArr as $key => $floorDtlTemp) {
                                                            if ($floorDtlTemp['type'] == "floor") {
                                                                $floorDateFromFyIDTemp = $floorDtlTemp['fy_mstr_id'];

                                                                if ($yrOfEffect_16_17_FyID > $floorDateFromFyIDTemp) {

                                                                    $floorDateUptoFyID = $currentFyID;
                                                                    if ($floorDtlTemp['date_upto'] != "") {
                                                                        $floorDateUptoFY = getFY($floorDtlTemp['date_upto']);
                                                                        $floorDateUptoFyID = $this->model_fy_mstr->getFyByFy(['fy' => $floorDateUptoFY])['id'];
                                                                    }
                                                                    if ($yrOfEffect_16_17_FyID <= $floorDateUptoFyID) {

                                                                        $afr = $this->model_occupancy_type_mstr->getOccupancyMultFactById($floorDtlTemp['occupancy_type_mstr_id'])['mult_factor'];
                                                                        if (!$afr) {
                                                                            $afr = 0;
                                                                        }
                                                                        if ($floorDtlTemp['usage_type_mstr_id'] == 1) {
                                                                            $carperArea = (($floorDtlTemp['builtup_area'] * 70) / 100);
                                                                        } else {
                                                                            $carperArea = (($floorDtlTemp['builtup_area'] * 80) / 100);
                                                                        }
                                                                        $sendInput = ['usage_type_mstr_id' => $floorDtlTemp['usage_type_mstr_id']];
                                                                        $mfDtl = $this->model_usage_type_dtl->getUsageTypeMultFact($sendInput);
                                                                        $mf = 0;
                                                                        $usage_type_dtl_id = 0;
                                                                        if ($mfDtl) {
                                                                            $mf = $mfDtl['mult_factor'];
                                                                            $usage_type_dtl_id = $mfDtl['id'];
                                                                        }

                                                                        $sendInput = ['road_type_mstr_id' => $inputs['road_type_mstr_id'], 'const_type_mstr_id' => $floorDtlTemp['const_type_mstr_id'], 'date_of_effect' => $dateOfEffect];
                                                                        $mrrDtl = $this->model_arr_building_mstr->getMRRCalRate($sendInput);
                                                                        $mrr = 0;
                                                                        $arr_building_id = 0;
                                                                        if ($mrrDtl) {
                                                                            $mrr = $mrrDtl['cal_rate'];
                                                                            $arr_building_id = $mrrDtl['id'];
                                                                        }

                                                                        $arv = $afr * $mf * $carperArea * $mrr;
                                                                        //echo $afr."x".$mf."x".$carperArea."x".$mrr." = ".$arv." => ".$floorDtlTemp['date_from']."<br />";
                                                                        if ($floorDtl['type'] == "floor") {
                                                                            if (
                                                                                $inputs["prop_type_mstr_id"] == 2
                                                                                && $floorDtlTemp['occupancy_type_mstr_id'] == 1
                                                                                && $floorDtlTemp['usage_type_mstr_id'] == 1
                                                                            ) {
                                                                                $rebate_date = $floorDtlTemp['date_from'] . "-01";
                                                                                if ("1942-04-01" > $rebate_date) {
                                                                                    if ($arv != 0) {
                                                                                        $arvRebate = (($arv * 10) / 100);
                                                                                        $arv = $arv - $arvRebate;
                                                                                    }
                                                                                }
                                                                            }
                                                                        }
                                                                        if ($arv != 0) {
                                                                            if ($floorDtl['type'] == "floor" && $floorDtlTemp['operator'] == "+") {
                                                                                $inputDtl = [
                                                                                    'saf_dtl_id' => $inputs['saf_dtl_id'],
                                                                                    'floor_mstr_id' => $floorDtl['floor_mstr_id'],
                                                                                    'usage_type_mstr_id' => $floorDtl['usage_type_mstr_id'],
                                                                                    'const_type_mstr_id' => $floorDtl['const_type_mstr_id'],
                                                                                    'occupancy_type_mstr_id' => $floorDtl['occupancy_type_mstr_id'],
                                                                                    'builtup_area' => $floorDtl['builtup_area'],
                                                                                    'carpet_area' => $carperArea,
                                                                                    'date_from' => $floorDtl['date_from'],
                                                                                    'date_upto' => ($floorDtl['date_upto'] != "") ? $floorDtl['date_upto'] : null,
                                                                                    'arr_building_type' => 'tbl_arr_building_mstr',
                                                                                    'arr_building_id' => $arr_building_id,
                                                                                    'arr_building_rate' => $mrr,
                                                                                    'usage_type_dtl_id' => $usage_type_dtl_id,
                                                                                    'usage_type_rate' => $mf,
                                                                                    'occupancy_type_rate' => $afr,
                                                                                    'arv' => $arv,
                                                                                    'fy_mstr_id' => $fyVal['id'],
                                                                                    'qtr' => 1,
                                                                                    'emp_details_id' => $login_emp_details_id,
                                                                                    'entry_type' => 'FROM_EO',
                                                                                    'created_on' => $created_on,
                                                                                    'status' => 1
                                                                                ];
                                                                                $this->model_saf_floor_arv_dtl->insertData($inputDtl);
                                                                                //die();
                                                                            }
                                                                            if ($floorDtlTemp['operator'] == "+") {
                                                                                $oldARVTotal += $arv;
                                                                            } else if ($floorDtlTemp['operator'] == "-") {
                                                                                $oldARVTotal -= $arv;
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
                                                        if ($oldARVTotal > 0) {
                                                            $safTax = $oldARVTotal;
                                                            $holding_tax = $safTax * 0.02;
                                                            $additional_tax = 0;
                                                            if ($isWaterHarvesting == 0) {
                                                                $waterHarvestingTax = $holding_tax * 1.5;
                                                                $additional_tax = $waterHarvestingTax - $holding_tax;
                                                                if ($additional_tax != 0) {
                                                                    $additional_tax = round(($additional_tax / 4), 2);
                                                                }
                                                            }
                                                            if ($holding_tax != 0) {
                                                                $holding_tax = round(($holding_tax / 4), 2);
                                                            }

                                                            $lastIncreament++;
                                                            $lastArvDtl[$lastIncreament] = [
                                                                'fyID' => $fyVal['id'],
                                                                'arv' => $oldARVTotal,
                                                                'qtr' => 1
                                                            ];

                                                            $is_16_17_1st_qtr_tax_implement = true;
                                                        }
                                                    } // if new rule is implimented or not
                                                } // end if old rule is not implimented in new rule

                                                if ($floorDtl['type'] == "floor") {
                                                    $afr = $this->model_occupancy_type_mstr->getOccupancyMultFactById($floorDtl['occupancy_type_mstr_id'])['mult_factor'];
                                                    if (!$afr) {
                                                        $afr = 0;
                                                    }
                                                } else {
                                                    $afr = 1.5;
                                                }

                                                if ($floorDtl['type'] == "floor") {
                                                    if ($floorDtl['usage_type_mstr_id'] == 1) {
                                                        $carperArea = (($floorDtl['builtup_area'] * 70) / 100);
                                                    } else {
                                                        $carperArea = (($floorDtl['builtup_area'] * 80) / 100);
                                                    }
                                                } else {
                                                    $carperArea = $floorDtl['builtup_area'];
                                                }
                                                if ($floorDtl['type'] == "floor") {
                                                    $sendInput = ['usage_type_mstr_id' => $floorDtl['usage_type_mstr_id']];
                                                    $mfDtl = $this->model_usage_type_dtl->getUsageTypeMultFact($sendInput);
                                                    $mf = 0;
                                                    $usage_type_dtl_id = 0;
                                                    if ($mfDtl) {
                                                        $mf = $mfDtl['mult_factor'];
                                                        $usage_type_dtl_id = $mfDtl['id'];
                                                    }
                                                } else {
                                                    $mf = 1.5;
                                                    $usage_type_dtl_id = 13;
                                                }

                                                $sendInput = ['road_type_mstr_id' => $inputs['road_type_mstr_id'], 'const_type_mstr_id' => $floorDtl['const_type_mstr_id'], 'date_of_effect' => $dateOfEffect];
                                                $mrrDtl = $this->model_arr_building_mstr->getMRRCalRate($sendInput);
                                                $mrr = 0;
                                                $arr_building_id = 0;
                                                if ($mrrDtl) {
                                                    $mrr = $mrrDtl['cal_rate'];
                                                    $arr_building_id = $mrrDtl['id'];
                                                }

                                                $arv = $afr * $mf * $carperArea * $mrr;
                                                //echo $afr."x".$mf."x".$carperArea."x".$mrr." = ".$arv." => ".$floorDtl['date_from']."<br />";
                                                if ($floorDtl['type'] == "floor") {
                                                    if (
                                                        $inputs["prop_type_mstr_id"] == 2
                                                        && $floorDtl['occupancy_type_mstr_id'] == 1
                                                        && $floorDtl['usage_type_mstr_id'] == 1
                                                    ) {
                                                        $rebate_date = $floorDtl['date_from'] . "-01";
                                                        if ("1942-04-01" > $rebate_date) {
                                                            if ($arv != 0) {
                                                                $arvRebate = (($arv * 10) / 100);
                                                                $arv = $arv - $arvRebate;
                                                            }
                                                        }
                                                    }
                                                }
                                                if ($arv != 0) {
                                                    if ($floorDtl['operator'] == "+") {
                                                        $totalArv += $arv;
                                                    } else if ($floorDtl['operator'] == "-") {
                                                        $totalArv -= $arv;
                                                    }
                                                }

                                                if ($fyVal['id'] == $floorDateFromFyID) {

                                                    $temp_qtr = $floorDtl['qtr'];

                                                    if ($floorDtl['type'] == "floor" && $floorDtl['operator'] == "+") {
                                                        $inputDtl = [
                                                            'saf_dtl_id' => $inputs['saf_dtl_id'],
                                                            'floor_mstr_id' => $floorDtl['floor_mstr_id'],
                                                            'usage_type_mstr_id' => $floorDtl['usage_type_mstr_id'],
                                                            'const_type_mstr_id' => $floorDtl['const_type_mstr_id'],
                                                            'occupancy_type_mstr_id' => $floorDtl['occupancy_type_mstr_id'],
                                                            'builtup_area' => $floorDtl['builtup_area'],
                                                            'carpet_area' => $carperArea,
                                                            'date_from' => $floorDtl['date_from'],
                                                            'date_upto' => ($floorDtl['date_upto'] != "") ? $floorDtl['date_upto'] : null,
                                                            'arr_building_type' => 'tbl_arr_building_mstr',
                                                            'arr_building_id' => $arr_building_id,
                                                            'arr_building_rate' => $mrr,
                                                            'usage_type_dtl_id' => $usage_type_dtl_id,
                                                            'usage_type_rate' => $mf,
                                                            'occupancy_type_rate' => $afr,
                                                            'arv' => $arv,
                                                            'fy_mstr_id' => $fyVal['id'],
                                                            'qtr' => $temp_qtr,
                                                            'emp_details_id' => $login_emp_details_id,
                                                            'entry_type' => 'FROM_EO',
                                                            'created_on' => $created_on,
                                                            'status' => 1
                                                        ];
                                                        $this->model_saf_floor_arv_dtl->insertData($inputDtl);
                                                    }

                                                    $isExist = true;
                                                    foreach ($lastArvDtl as $key => $tempLastArvDtl) {
                                                        if (
                                                            $tempLastArvDtl['fyID'] == $fyVal['id']
                                                            && $tempLastArvDtl['qtr'] == $temp_qtr
                                                        ) {

                                                            $isExist = false;
                                                            $lastArvDtl[$key] = [
                                                                'fyID' => $fyVal['id'],
                                                                'arv' => $totalArv,
                                                                'qtr' => $temp_qtr
                                                            ];
                                                        }
                                                    }
                                                    if ($isExist) {
                                                        $lastIncreament++;
                                                        $lastArvDtl[$lastIncreament] = [
                                                            'fyID' => $fyVal['id'],
                                                            'arv' => $totalArv,
                                                            'qtr' => $temp_qtr
                                                        ];
                                                    }
                                                }
                                            }
                                        } // new rule effected
                                    }
                                }
                            } //end floorDtlArr foreach loop

                            foreach ($lastArvDtl as $key => $value) {
                                $holding_tax = 0;
                                $water_tax = 0;
                                $education_cess = 0;
                                $health_cess = 0;
                                $latrine_tax = 0;
                                $additional_tax = 0;
                                $safTaxQtr = $value['arv'];
                                if ($yrOfEffect_16_17_FyID > $fyVal['id']) {
                                    $holding_tax = $safTaxQtr * 0.125;
                                    if ($holding_tax != 0) {
                                        $holding_tax = round(($holding_tax / 4), 2);
                                    }
                                    $water_tax = $safTaxQtr * 0.075;
                                    if ($water_tax != 0) {
                                        $water_tax = round(($water_tax / 4), 2);
                                    }
                                    $education_cess = $safTaxQtr * 0.05;
                                    if ($education_cess != 0) {
                                        $education_cess = round(($education_cess / 4), 2);
                                    }
                                    $health_cess = $safTaxQtr * 0.0625;
                                    if ($health_cess != 0) {
                                        $health_cess = round(($health_cess / 4), 2);
                                    }
                                    $latrine_tax = $safTaxQtr * 0.075;
                                    if ($latrine_tax != 0) {
                                        $latrine_tax = round(($latrine_tax / 4), 2);
                                    }
                                } else {
                                    $holding_tax = $safTaxQtr * 0.02;
                                    if ($isWaterHarvesting) {
                                        $waterHarvestingTax = $holding_tax * 1.5;
                                        $additional_tax = $waterHarvestingTax - $holding_tax;
                                        if ($additional_tax != 0) {
                                            $additional_tax = round(($additional_tax / 4), 2);
                                        }
                                    }
                                    if ($holding_tax != 0) {
                                        $holding_tax = round(($holding_tax / 4), 2);
                                    }
                                }
                                if ($value['arv'] > 0) {
                                    $safTaxDtl[$safTaxIncreament] = [
                                        'fyID' => $fyVal['id'],
                                        'fy' => $fyVal['fy'],
                                        'arv' => round($value['arv'], 2),
                                        'qtr' => $value['qtr'],
                                        'holding_tax' => $holding_tax,
                                        'water_tax' => $water_tax,
                                        'education_cess' => $education_cess,
                                        'health_cess' => $health_cess,
                                        'latrine_tax' => $latrine_tax,
                                        'additional_tax' => $additional_tax
                                    ];
                                    $safTaxIncreament++;
                                }
                            }
                        } // end foreach loop to financial year
                        // insert tax details

                        $safTaxDtlLen = sizeof($safTaxDtl);

                        $i = 0;
                        $next_fy_mstr_id = 0;
                        $next_qtr = 0;
                        $holding_tax = 0;
                        $penalty_amount = 0;

                        $last_prop_tax_id = 0;
                        $last_amount = 0;
                        $input = [
                            'prop_dtl_id' => $inputs['prop_dtl_id'],
                        ];
                        $this->model_prop_tax->updateSafDeactiveStatus($input);

                        $prop_dtl_tax_temp = $this->model_prop_tax->getdetails_propdtlid($inputs['prop_dtl_id']);
                        $diffTax = [];

                        foreach ($safTaxDtl as $key => $safTax) {
                            $_fy_id = $safTax['fyID'];
                            $_fy = $safTax['fy'];
                            $_qtr = $safTax['qtr'];
                            $_arv = $safTax['arv'];
                            $_holding_tax = $safTax['holding_tax'];
                            $_water_tax = $safTax['water_tax'];
                            $_education_cess = $safTax['education_cess'];
                            $_health_cess = $safTax['health_cess'];
                            $_latrine_tax = $safTax['latrine_tax'];
                            $_additional_tax = $safTax['additional_tax'];

                            foreach ($prop_dtl_tax_temp as $keyy => $safTaxTemp) {
                                if ($safTaxTemp['fy_mstr_id'] == $safTax['fyID'] && $safTaxTemp['qtr'] == $safTax['qtr']) {
                                    $_arv -= $safTaxTemp['arv'];
                                    $_holding_tax -= $safTaxTemp['holding_tax'];
                                    $_water_tax -= $safTaxTemp['water_tax'];
                                    $_education_cess -= $safTaxTemp['education_cess'];
                                    $_health_cess -= $safTaxTemp['health_cess'];
                                    $_latrine_tax -= $safTaxTemp['latrine_tax'];
                                    $_additional_tax -= $safTaxTemp['additional_tax'];
                                }
                            }

                            if ($_arv > 0) {
                                $diffTax['fy_id'] = $_fy_id;
                                $diffTax['fy'] = $_fy;
                                $diffTax['qtr'] = $_qtr;
                                $diffTax['arv'] = $_arv;
                                $diffTax['holding_tax'] = $_holding_tax;
                                $diffTax['water_tax'] = $_water_tax;
                                $diffTax['education_cess'] = $_education_cess;
                                $diffTax['health_cess'] = $_health_cess;
                                $diffTax['latrine_tax'] = $_latrine_tax;
                            }
                        }

                        foreach ($safTaxDtl as $key => $safTax) {
                            if ($safTax['arv'] == 0) {
                                $prop_tax_id = $last_prop_tax_id;
                                $amount = $last_amount;
                            } else {
                                $i++;
                                $input = [
                                    'prop_dtl_id' => $inputs['prop_dtl_id'],
                                    'fy_mstr_id' => $safTax['fyID'],
                                    'qtr' => $safTax['qtr'],
                                    'arv' => $safTax['arv'],
                                    'holding_tax' => $safTax['holding_tax'],
                                    'water_tax' => $safTax['water_tax'],
                                    'education_cess' => $safTax['education_cess'],
                                    'health_cess' => $safTax['health_cess'],
                                    'latrine_tax' => $safTax['latrine_tax'],
                                    'additional_tax' => $safTax['additional_tax'],
                                    'created_on' => $created_on,
                                    'status' => 1
                                ];

                                $last_prop_tax_id = $prop_tax_id = $this->model_prop_tax->insertData($input);

                                $input['saf_dtl_id'] = $inputs['saf_dtl_id'];
                                $this->model_prop_tax_print->insertData($input);

                                $last_amount = $amount = $safTax['holding_tax'] + $safTax['water_tax'] + $safTax['education_cess'] + $safTax['health_cess'] + $safTax['latrine_tax'] + $safTax['additional_tax'];
                            }
                            $amount_qtr = $amount;

                            if ($safTaxDtlLen == $i) {
                                $next_fy_mstr_id = $currentFyID;
                                $next_qtr = 4;
                            } else {
                                $next_fy_mstr_id = $safTaxDtl[$key + 1]['fyID'];
                                $next_qtr = $safTaxDtl[$key + 1]['qtr'];
                            }
                            for ($j = $safTax['fyID']; $j <= $next_fy_mstr_id; $j++) {
                                $zz = 1;
                                if ($j == $safTax['fyID']) {
                                    $zz = $safTax['qtr'];
                                }
                                $zzz = 4;
                                if ($j == $next_qtr) {
                                    $zzz = $safTax['qtr'];
                                }
                                for ($z = $zz; $z <= $zzz; $z++) {
                                    if ($next_fy_mstr_id == $j && $z == $next_qtr) {
                                        if ($next_fy_mstr_id == $currentFyID && $next_qtr == 4) {
                                            $inputCheckTotal = [
                                                'prop_dtl_id' => $inputs['prop_dtl_id'],
                                                'fy_mstr_id' => $j,
                                                'qtr' => $z
                                            ];

                                            $saf_prop_demand = 0;

                                            if ($saf_demand_dtl = $this->model_view_saf_dtl_demand->getSumDemandBySAFDtlIdFyIdQtr($inputCheckTotal)) {
                                                $saf_prop_demand += $saf_demand_dtl['amount'];
                                            }

                                            if ($prop_demand_dtl = $this->model_prop_demand->getSumDemandByPropDtlIdFyIdQtr($inputCheckTotal)) {
                                                $saf_prop_demand += $prop_demand_dtl['amount'];
                                            }

                                            if ($saf_prop_demand < $amount_qtr) {
                                                //$penalty_amount += $amount_qtr - $saf_prop_demand;
                                                // greater
                                                if ($this->model_prop_demand->checkDemandPaidStatusZeroByPropDtlIdFyIdQtr($inputCheckTotal)) {

                                                    $this->model_prop_demand->deleteDemandByPropDtlIdFyIdQtr($inputCheckTotal);

                                                    $input = [
                                                        'prop_dtl_id' => $inputs['prop_dtl_id'],
                                                        'prop_tax_id' => $prop_tax_id,
                                                        'fy_mstr_id' => $j,
                                                        'qtr' => $z,
                                                        'amount' => $amount_qtr,
                                                        'balance' => $amount_qtr,
                                                        'fine_tax' => 0,
                                                        'created_on' => $created_on,
                                                        'status' => 1
                                                    ];
                                                    $this->model_prop_demand->insertData($input);
                                                } else {
                                                    if ($this->model_view_saf_dtl_demand->checkDemandIsExistBySafDtlIdFyIdQtr($inputCheckTotal)) {
                                                        $remaining_amt = $amount_qtr - $saf_prop_demand;
                                                        $input = [
                                                            'prop_dtl_id' => $inputs['prop_dtl_id'],
                                                            'prop_tax_id' => $prop_tax_id,
                                                            'fy_mstr_id' => $j,
                                                            'qtr' => $z,
                                                            'amount' => $remaining_amt,
                                                            'balance' => $remaining_amt,
                                                            'fine_tax' => 0,
                                                            'created_on' => $created_on,
                                                            'status' => 1
                                                        ];
                                                        $this->model_prop_demand->insertData($input);
                                                    } else {
                                                        $input = [
                                                            'prop_dtl_id' => $inputs['prop_dtl_id'],
                                                            'prop_tax_id' => $prop_tax_id,
                                                            'fy_mstr_id' => $j,
                                                            'qtr' => $z,
                                                            'amount' => $amount_qtr,
                                                            'balance' => $amount_qtr,
                                                            'fine_tax' => 0,
                                                            'created_on' => $created_on,
                                                            'status' => 1
                                                        ];
                                                        $this->model_prop_demand->insertData($input);
                                                    }
                                                }
                                                //die();
                                            } else if ($saf_prop_demand > $amount_qtr) {
                                                // smallest
                                                $remaining_amt = round($saf_prop_demand - $amount_qtr);
                                            }
                                        } else {
                                            break;
                                        }
                                    } else {
                                        $inputCheckTotal = [
                                            'saf_dtl_id' => $inputs['saf_dtl_id'],
                                            'prop_dtl_id' => $inputs['prop_dtl_id'],
                                            'fy_mstr_id' => $j,
                                            'qtr' => $z
                                        ];

                                        $saf_prop_demand = 0;
                                        if ($saf_demand_dtl = $this->model_view_saf_dtl_demand->getSumDemandBySAFDtlIdFyIdQtr($inputCheckTotal)) {
                                            $saf_prop_demand += $saf_demand_dtl['amount'];
                                        }

                                        if ($prop_demand_dtl = $this->model_prop_demand->getSumDemandByPropDtlIdFyIdQtr($inputCheckTotal)) {
                                            $saf_prop_demand += $prop_demand_dtl['amount'];
                                        }
                                        if ($saf_prop_demand < $amount_qtr) {
                                            //$penalty_amount += $amount_qtr - $saf_prop_demand;
                                            // greater
                                            if ($this->model_prop_demand->checkDemandPaidStatusZeroByPropDtlIdFyIdQtr($inputCheckTotal)) {
                                                $this->model_prop_demand->deleteDemandByPropDtlIdFyIdQtr($inputCheckTotal);
                                                $input = [
                                                    'prop_dtl_id' => $inputs['prop_dtl_id'],
                                                    'prop_tax_id' => $prop_tax_id,
                                                    'fy_mstr_id' => $j,
                                                    'qtr' => $z,
                                                    'amount' => $amount_qtr,
                                                    'balance' => $amount_qtr,
                                                    'fine_tax' => 0,
                                                    'created_on' => $created_on,
                                                    'status' => 1
                                                ];
                                                $this->model_prop_demand->insertData($input);
                                            } else {
                                                if ($this->model_view_saf_dtl_demand->checkDemandIsExistBySafDtlIdFyIdQtr($inputCheckTotal)) {
                                                    $remaining_amt = $amount_qtr - $saf_prop_demand;
                                                    $input = [
                                                        'prop_dtl_id' => $inputs['prop_dtl_id'],
                                                        'prop_tax_id' => $prop_tax_id,
                                                        'fy_mstr_id' => $j,
                                                        'qtr' => $z,
                                                        'amount' => $remaining_amt,
                                                        'balance' => $remaining_amt,
                                                        'fine_tax' => 0,
                                                        'created_on' => $created_on,
                                                        'status' => 1
                                                    ];
                                                    $this->model_prop_demand->insertData($input);
                                                } else {
                                                    $input = [
                                                        'prop_dtl_id' => $inputs['prop_dtl_id'],
                                                        'prop_tax_id' => $prop_tax_id,
                                                        'fy_mstr_id' => $j,
                                                        'qtr' => $z,
                                                        'amount' => $amount_qtr,
                                                        'balance' => $amount_qtr,
                                                        'fine_tax' => 0,
                                                        'created_on' => $created_on,
                                                        'status' => 1
                                                    ];
                                                    $this->model_prop_demand->insertData($input);
                                                }
                                            }
                                        } else if ($prop_demand_dtl['amount'] > $amount_qtr) {
                                            // smallest
                                            $remaining_amt = ($prop_demand_dtl['amount'] - $amount_qtr);
                                        }
                                    }
                                }
                            }
                        }
                        if (isset($diffTax) && !empty($diffTax)) {
                            if ($diffTax['holding_tax'] > 0) {
                                $input = [
                                    'prop_dtl_id' => $inputs['prop_dtl_id'],
                                    'penalty_amt' => round($diffTax['holding_tax'] * 4, 2),
                                    'penalty_type' => 'difference_penalty',
                                    'created_on' => $created_on,
                                    'status' => 1
                                ];
                                $this->model_penalty_dtl->insertData($input);
                            }
                        }
                    } // end building calculation details
                }

                # Update Property as per ULB Verification
                $data["ufieldVerificationmstr_detail"] = $this->model_field_verification_dtl->getulbdatabysafidobjection($data["saf_dtl_id"], ["ULB TC", "Objection"]);
                $data["Saf_detail"] = $data;

                $verification_data = array(
                    "prop_type_mstr_id" => $data["ufieldVerificationmstr_detail"]["prop_type_mstr_id"],
                    "road_type_mstr_id" => $data["ufieldVerificationmstr_detail"]["road_type_mstr_id"],
                    "area_of_plot" => $data["ufieldVerificationmstr_detail"]["area_of_plot"],
                    "zone_mstr_id" => $data["ufieldVerificationmstr_detail"]["zone_mstr_id"],
                    "ward_mstr_id" => $data["ufieldVerificationmstr_detail"]["ward_mstr_id"],
                    "assessment_type" => $data["Saf_detail"]["assessment_type"],
                );
                $this->model_prop_dtl->updatePropertyByEO($data["saf_dtl_id"], $verification_data);

                $inputs = arrFilterSanitizeString($this->request->getVar());
                $prop_ward_id = $data['ufieldVerificationmstr_detail']['ward_mstr_id'];
                $wardd = $this->model_ward_mstr->getdatabyid($data['ufieldVerificationmstr_detail']['ward_mstr_id']);
                $ward_nmm = $wardd['ward_no'];
                $data = [
                    'remarks' => $inputs['remarks'],
                    'level_pending_dtl_id' => md5($data['form']['id']),
                    'saf_dtl_id' => $saf_dtl_id,
                    'emp_details_id' => $login_emp_details_id,
                    'created_on' => date('Y-m-d H:i:s'),
                    'verification_status' => 1, //Verfied
                    'saf_pending_status' => 1, // Approved by EO
                    'doc_verify_status' => 1, // Approved by EO
                ];

                $assessment_memo = $this->model_saf_memo_dtl->getMemo(["saf_dtl_id" => $saf_dtl_id, "memo_type" => "SAM"]);
                //print_var($assessment_memo);
                /********generate memo no*******/
                //get max fy yr and max quarter
                $max_fy_id = $this->model_saf_tax->getmaxfyidbysafid($saf_dtl_id);
                $max_qtr = $this->model_saf_tax->getmaxfyqtridbysafid($saf_dtl_id, $max_fy_id['max_fy_id']);
                $saf_tx = $this->model_saf_tax->getallmaxfyqtridbysafid($saf_dtl_id, $max_fy_id['max_fy_id'], $max_qtr['max_qtr']);
                $fy_yr = $this->model_fy_mstr->getFiyrByid($saf_tx['fy_mstr_id']);
                //count ward 
                $ward_count = $this->model_prop_dtl->count_ward_by_wardid($prop_ward_id);
                $sl_no = $ward_count['ward_cnt'];
                $sl_noo = $sl_no + 1;
                $serial_no = str_pad($sl_noo, 4, "0", STR_PAD_LEFT);
                $quartely_tax = $saf_tx['holding_tax'] + $saf_tx['water_tax'] + $saf_tx['education_cess'] + $saf_tx['health_cess'] + $saf_tx['latrine_tax'];

                $memo_type = 'FAM';
                $genmemo_last_id = $this->model_saf_memo_dtl->generatesafmemono(
                    $saf_dtl_id,
                    $saf_tx['fy_mstr_id'],
                    $saf_tx['qtr'],
                    $saf_tx['arv'],
                    $quartely_tax,
                    $memo_type,
                    $login_emp_details_id,
                    $data['created_on'],
                    $assessment_memo["holding_no"],
                    $assessment_memo["prop_dtl_id"]
                );

                $memo_no = 'FAM/' . $ward_nmm . '/' . $genmemo_last_id . $serial_no . '/' . $fy_yr['fy'];
                $this->model_saf_memo_dtl->updatememonoById($genmemo_last_id, $memo_no, $fy_yr['fy']);

                ($this->model_level_pending_dtl->updatesisafById($data)); {
                    ($this->model_saf_dtl->update_saf_pending_status($data)); {
                        if ($this->db->transStatus() === FALSE) {
                            $this->db->transRollback();
                            flashToast('message', 'Application could not approved');
                        } else {

                            
                            $this->db->transCommit();
                            flashToast('message', 'Application approved successfully');
                            return $this->response->redirect(base_url('citizenPaymentReceipt/da_eng_memo_receipt/' . md5($ulb_mstr["ulb_mstr_id"]) . '/' . md5($genmemo_last_id)));
                        }
                    }
                }
            }

            $Session = Session();
            $emp_mstr = $Session->get("emp_details");
            $sender_emp_details_id = $emp_mstr["id"];
            if (isset($inputs['btn_backward_submit'])) {
            //    echo "entering btn backward";
            //    return;
                $level_data = [
                    'level_pending_dtl_id' => md5($data['form']['id']),
                    'remarks' => $inputs['remarks'],
                    'verification_status' => 1,
                    'status' => 0,
                    'sender_emp_details_id' => $sender_emp_details_id
                ];
                if ($updatebackward = $this->model_level_pending_dtl->updatelevelpendingById($level_data)) {
                    $level_data = [
                        'remarks' => $inputs['remarks'],
                        'level_pending_dtl_id' => $data['form']['id'],
                        'saf_dtl_id' => $data['saf_dtl_id'],
                        'emp_details_id' => $login_emp_details_id,
                        'created_on' => date('Y-m-d H:i:s'),
                        'forward_date' => date('Y-m-d'),
                        'forward_time' => date('H:i:s'),
                        'sender_user_type_id' => 10, //Property Executive Officer
                        'receiver_user_type_id' => 9, // Section Incharge
                        'verification_status' => 0,
                        'status' => 1,
                        'sender_emp_details_id' => $sender_emp_details_id
                    ];
                    if ($insertbackward = $this->model_level_pending_dtl->insrtlevelpendingdtl($level_data)) {
                        flashToast("message", "Application sent back to section head");
                        return $this->response->redirect(base_url('EO_SAF/index/'));
                    }
                }
            }

            if (isset($inputs['btn_back_to_citizen_submit'])) {

                $data = [

                    'id' => $data['form']['id'],
                    'saf_dtl_id' => $data['saf_dtl_id'],
                    'remarks' => $inputs['remarks'],
                    'sender_user_type_id' => 10, //Property Executive Officer
                    'receiver_user_type_id' => 11, //Back Office
                    'sender_emp_details_id' => $sender_emp_details_id
                ];
                if ($updatebacktocitizen = $this->model_level_pending_dtl->sendBackToCitizen($data)) {
                    $this->model_saf_dtl->update_saf_pending_status(["saf_dtl_id" => $data['saf_dtl_id'], "doc_verify_status" => 2]); //backtocitizen
                    flashToast("message", "Applicationn sent back to citizen successfully.");
                    return $this->response->redirect(base_url('EO_SAF/index'));
                }
            }
        } else {
            return view('property/saf/eo_saf_view', $data);
        }
    }

    public function saf_final_memo()
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
                $where = "view_saf_dtl.ward_mstr_id=1 and date(tbl_saf_memo_dtl.created_on) between '" . $data['from_date'] . "' and '" . $data['to_date'] . "' 
                and tbl_saf_memo_dtl.status=1 and memo_type='FAM' order by tbl_saf_memo_dtl.id desc";
            } else {
                $where = "view_saf_dtl.ward_mstr_id in (" . $imploded_ward_mstr_id . ") and date(tbl_saf_memo_dtl.created_on) between '" . $data['from_date'] . "' and '" . $data['to_date'] . "' 
                and tbl_saf_memo_dtl.status=1 and memo_type='FAM' order by tbl_saf_memo_dtl.id desc";
            }
        } else {
            $data['from_date'] = date('Y-m-d');
            $data['to_date'] = date('Y-m-d');
            $where = "view_saf_dtl.ward_mstr_id in (" . $imploded_ward_mstr_id . ") and date(tbl_saf_memo_dtl.created_on) between '" . $data['from_date'] . "' and '" . $data['to_date'] . "' 
            and tbl_saf_memo_dtl.status=1 and memo_type='FAM' order by tbl_saf_memo_dtl.id desc";
        }

        $Session->set('where', $where);
        $Session->set('from_date', $data['from_date']);
        $Session->set('to_date', $data['to_date']);
        $Session->set('ward_mstr_id', $data['ward_mstr_id'] ?? NULL);
        $Session->set('wardList', $data['wardList']);

        return $this->response->redirect(base_url('EO_SAF/saf_final_memo_list'));
    }

    public function saf_final_memo_list()
    {
        $data = (array)null;
        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");


        $data = arrFilterSanitizeString($this->request->getVar());
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $wardWhere = "";
        $wherAll = "";
        if (isset($data["ward_mstr_id"]) && isset($data["search_by_holding_no"]) && isset($data["search_by_saf_no"]) && isset($data["search_by_memo_no"])) {
            if ($data["ward_mstr_id"]!="") {
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
                    tbl_saf_memo_dtl.id AS memo_id,
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
                WHERE tbl_saf_memo_dtl.memo_type='FAM' AND tbl_saf_memo_dtl.status=1
                ORDER BY tbl_saf_memo_dtl.created_on DESC";

        $result = $this->model_datatable->getDatatable($sql);
        $data['offset'] = $result['offset'];
        $data['posts'] = $result['result'];
        $data['memo_list'] = $data['posts'];
        $data['pager'] = $result['count'];
        $data['sql'] = base64_encode($sql);
        $data["daList"] = $this->db->query("select * from view_emp_details where user_mstr_lock_status = 0 AND user_type_id in (9,10)")->getResultArray();
        return view('property/saf/saf_final_memo_list', $data);
    }

    public function saf_final_memo_list_excel() {
        if($this->request->getMethod()=='post'){
			try{
                $data = arrFilterSanitizeString($this->request->getVar());
                // $sql = $this->PropReports->excelExportFAM($data);
                $sql = isset($data["encryptFrom"]) ? base64_decode($data["encryptFrom"])??$this->PropReports->excelExportFAM($data):$this->PropReports->excelExportFAM($data);
                //print_var($sql);
                $result = $this->db->query($sql)->getResult('array');
                $filename = "writable\genexcel\ExcelExportFAM_".date('Y_m_d_H_I_s').".csv";
                $fp = fopen($filename, 'w');
  
                // Loop through file pointer and a line
                fputcsv($fp,array_keys($result[0]));
                foreach ($result as $fields) {
                    fputcsv($fp, $fields);
                }
                
                fclose($fp);
                return json_encode($filename);
                // $result = $this->db->query("SELECT generateCSVReports('".$sql."', 'excelExportFAM')");
                // $filename = $result->getFirstRow("array");
                // return json_encode($filename);
            } catch(Exception $e) {
                echo $e;
            }
        }
    }

    public function ddd()
    {
        if ($this->request->getMethod() == 'post') {
        }
    }

    public function gb_index()
    {
        $data = (array)null;
        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
        $login_emp_details_id = $emp_mstr["id"];

        $wardList = $this->model_view_ward_permission->getPermittedWard($login_emp_details_id);
        $data['wardList'] = $wardList;

        $receiver_emp_details_id = $emp_mstr["user_type_mstr_id"];
        //print_r($ward_permission);
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
				AND date(created_on) <='" . $data['to_date'] . "' AND receiver_user_type_id='" . $receiver_emp_details_id . "' 
				AND verification_status='0' and level_pending_status=1 ORDER BY id DESC";
            } else {
                $where = "ward_mstr_id in (" . implode(",", $ward) . ") AND date(created_on) >='" . $data['from_date'] . "' 
				AND date(created_on) <='" . $data['to_date'] . "' AND receiver_user_type_id='" . $receiver_emp_details_id . "' 
				AND verification_status='0' and level_pending_status=1 ORDER BY id DESC";
            }
        } else {
            $data['from_date'] = date('Y-m-d');
            $data['to_date'] = date('Y-m-d');
            $data['ward_mstr_id'] = NULL;
            $where = "ward_mstr_id in (" . implode(",", $ward) . ") AND date(created_on) >='" . $data['from_date'] . "' 
			AND date(created_on) <='" . $data['to_date'] . "' AND receiver_user_type_id='" . $receiver_emp_details_id . "' 
			AND verification_status='0' and level_pending_status=1 ORDER BY id DESC";
        }

        $Session->set('wardList', $wardList);
        $Session->set('ward_mstr_id', $data['ward_mstr_id']);
        $Session->set('from_date', $data['from_date']);
        $Session->set('to_date', $data['to_date']);

        $Session->set('where', $where);
        return $this->response->redirect(base_url('EO_SAF/gb_saf_inbox_list/'));
    }

    public function gb_saf_inbox_list()
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

        $receiver_emp_details_id = $emp_mstr["user_type_mstr_id"];
        //print_r($ward_permission);

		
        $where = $Session->get('where');
        $sql = "SELECT * FROM view_gb_saf_receive_list WHERE $where";

        $result = $this->model_datatable->getDatatable($sql);
        $data['posts'] = $result['result'];


        $data['inboxList'] = $data['posts'];
        $data['pager'] = $result['count'];

        $data['wardList'] = $Session->get('wardList');
        $data['ward_mstr_id'] = $Session->get('ward_mstr_id');
        $data['from_date'] = $Session->get('from_date');
        $data['to_date'] = $Session->get('to_date');
        return view('property/saf/eo_gb_saf_list', $data);
    }


    function verifyGBSaf($govt_saf_dtl_id)
    {
        //echo $govt_saf_dtl_id;
        $Session = Session();
        $emp_mstr = $Session->get("emp_details");
        $login_emp_details_id = $emp_mstr["id"];
        $ulb = $Session->get("ulb_dtl");

        $data = (array)null;
        $data['ulb'] = $ulb;
        $data['application_detail'] = $this->model_g_saf->getApplicationDetailbyId($govt_saf_dtl_id);
        $data['owner_details'] = $this->model_govt_saf_officer_dtl->getOwnerDetails($govt_saf_dtl_id);
        //$data['owner_details']=$this->model_govt_saf_officer_dtl->getOwnerDetails($govt_saf_dtl_id);
        $data['doc_details'] = $this->model_govt_doc_dtl->getAllDocuments($govt_saf_dtl_id);
        $data['tax_list'] = $this->model_govt_saf_tax_dtl->tax_list($data['application_detail']['id']);
        $data["memo_list"] = $this->model_saf_memo_dtl->getAllGovtMemo(['govt_saf_dtl_id' => $data['application_detail']['id']]);

        if ($this->request->getMethod() == 'post') {
            # Generate Memo & Approve
            if (isset($_POST['genertaeMemo']) && $_POST['genertaeMemo'] == "Generate Memo & Approve") {
                $level_dtl = $this->model_govt_saf_level_pending_dtl->getLastRecord($govt_saf_dtl_id);
                $created_on = date('Y-m-d H:i:s');
                $prop_data = [
                    "holding_no" => NULL, // not generated
                    "ward_mstr_id" => $data["application_detail"]["ward_mstr_id"],
                    "ownership_type_mstr_id" => $data["application_detail"]["ownership_type_mstr_id"],
                    "prop_type_mstr_id" => $data["application_detail"]["prop_type_mstr_id"],
                    "appartment_name" => $data["application_detail"]["colony_name"],
                    //"no_electric_connection"=> NULL,
                    //"elect_consumer_no"=> NULL,
                    //"elect_acc_no"=> NULL,
                    //"elect_bind_book_no"=> NULL,
                    //"elect_cons_category"=> NULL,
                    //"building_plan_approval_no"=> NULL,
                    //"building_plan_approval_date"=> NULL,
                    //"water_conn_no"=> NULL,
                    //"water_conn_date"=> NULL,
                    //"khata_no"=> NULL,
                    //"plot_no"=> NULL,
                    "village_mauja_name" => $data["application_detail"]["building_colony_address"],
                    "road_type_mstr_id" => $data["application_detail"]["road_type_mstr_id"],
                    //"area_of_plot"=> NULL,
                    "prop_address" => $data["application_detail"]["address"],
                    "prop_city" => $data["application_detail"]["city"],
                    "prop_dist" => $data["application_detail"]["district"],
                    "prop_pin_code" => $data["application_detail"]["pin_no"],

                    "corr_address" => $data["application_detail"]["address"],
                    "corr_city" => $data["application_detail"]["city"],
                    "corr_dist" => $data["application_detail"]["district"],
                    "corr_pin_code" => $data["application_detail"]["pin_no"],

                    "is_mobile_tower" => $data["application_detail"]["is_mobile_tower"],
                    "tower_area" => $data["application_detail"]["tower_area"],
                    "tower_installation_date" => $data["application_detail"]["tower_installation_date"],

                    "is_hoarding_board" => $data["application_detail"]["is_hoarding_board"],
                    "hoarding_area" => $data["application_detail"]["hoarding_area"],
                    "hoarding_installation_date" => $data["application_detail"]["hoarding_installation_date"],

                    "is_petrol_pump" => $data["application_detail"]["is_petrol_pump"],
                    "under_ground_area" => $data["application_detail"]["under_ground_area"],
                    "petrol_pump_completion_date" => $data["application_detail"]["petrol_pump_completion_date"],

                    "is_water_harvesting" => $data["application_detail"]["is_water_harvesting"],

                    "occupation_date" => $data["application_detail"]["ownership_type_mstr_id"],
                    "emp_details_id" => $login_emp_details_id,
                    "created_on" => date('Y-m-d H:i:s'),
                    "status" => 1,
                    //"for_sub_holding"=> $data["application_detail"]["ownership_type_mstr_id"],
                    //"saf_hold_status"=> $data["application_detail"]["ownership_type_mstr_id"],
                    "new_ward_mstr_id" => $data["application_detail"]["ward_mstr_id"],

                    "entry_type" => $data["application_detail"]["assessment_type"],
                    "prop_state" => "JHARKHAND",
                    "corr_state" => "JHARKHAND",
                    "zone_mstr_id" => $data["application_detail"]["zone_mstr_id"],
                    "new_holding_no" => NULL,
                    //"flat_registry_date"=> NULL,
                    "assessment_type" => $data["application_detail"]["assessment_type"],
                    "holding_type" => "PURE_GOVERNMENT",
                    "is_old" => 0,
                    "govt_saf_dtl_id" => $data["application_detail"]["id"],
                ];



                /*
                1	SUPER STRUCTURE
                2	INDEPENDENT BUILDING
                3	FLATS / UNIT IN MULTI STORIED BUILDING
                4	VACANT LAND
                5	OCCUPIED PROPERTY
                */
                $ward_no = str_pad($data["application_detail"]["ward_no"], 3, "0", STR_PAD_LEFT);
                if ($data["application_detail"]["prop_type_mstr_id"] == '4') {
                    $usage_type_code = 'M';
                } else {
                    $count_usage_type = $this->model_govt_saf_floor_dtl->countRowByGBSafDtlId($level_dtl['govt_saf_dtl_id']);
                    if ($count_usage_type['count_row'] > '1') {
                        $usage_type_code = 'X';
                    } else {
                        $data['usage_type'] = $this->model_govt_saf_floor_dtl->getusagecodeByGBSafId($level_dtl['govt_saf_dtl_id']);
                        $usage_type_code = $data['usage_type']['usage_code'];
                    }
                }

                if ($data["application_detail"]["prop_type_mstr_id"] == '4') {
                    $const_type_code = '0';
                } elseif ($data["application_detail"]["prop_type_mstr_id"] == '2' || $data["application_detail"]["prop_type_mstr_id"] == '3') {
                    $count_usage_type = $this->model_govt_saf_floor_dtl->countRowByGBSafDtlId($level_dtl['govt_saf_dtl_id']);
                    if ($count_usage_type['count_row'] > 1) {
                        $const_type_code = '4';
                    } else {
                        $const_type = $this->model_govt_saf_floor_dtl->getDataByGBSafDtlId($level_dtl['govt_saf_dtl_id']);
                        $const_type_code = $const_type[0]['const_type_mstr_id'];
                    }
                } elseif ($data["application_detail"]["prop_type_mstr_id"] == '1') {
                    $count_usage_type = $this->model_govt_saf_floor_dtl->countRowByGBSafDtlId($level_dtl['govt_saf_dtl_id']);
                    if ($count_usage_type['count_row'] > '1') {
                        $const_type_code = '8';
                    } else {
                        $const_type = $this->model_govt_saf_floor_dtl->getDataByGBSafDtlId($level_dtl['govt_saf_dtl_id']);
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
                

                if ($data["application_detail"]['assessment_type'] == "Reassessment") {
                    //legacy prop insert
                    $previousPropDtl = $this->model_prop_dtl->getPropdetailsbyid($data["application_detail"]['prop_dtl_id']);
                    if ($previousPropDtl['new_holding_no'] == '') {
                        $road_type = "000";
                        $sub_holding_no = '000';
                        $serial_no = $this->model_prop_dtl->count_ward_by_wardid($data["application_detail"]['ward_mstr_id'])['ward_cnt'];
                        $serial_no = str_pad(++$serial_no, 4, "0", STR_PAD_LEFT);
                        $generated_holding_no = $ward_no . $road_type . $serial_no . $sub_holding_no . $usage_type_code . $const_type_code;
                        $input = [
                            'prop_dtl_id' => $previousPropDtl['id'],
                            'new_holding_no' => $generated_holding_no
                        ];
                        $this->model_prop_dtl->updateUniqueHoldingNoInPropDtlByDocVerifyTime($input);
                    }
                    $input = [
                        'govt_saf_dtl_id' => $level_dtl['govt_saf_dtl_id'],
                        'prop_dtl_id' => $previousPropDtl['id'],
                        'updated_on' => $created_on,
                        'new_holding_no' => $generated_holding_no
                    ];

                    //if($this->model_prop_dtl->updatePropDetByDocVerifyTime($input))
                    if (1) {
                        $prop_dtl_id = $previousPropDtl['id'];

                        $this->model_saf_memo_dtl->SAMDeactivatedByGBSafDtlId($level_dtl['govt_saf_dtl_id']);
                        $this->model_prop_floor_details->floorDtlDeactivatedByPropDtlId($prop_dtl_id);
                        $this->model_prop_tax->taxDtlDeactivatedByPropDtlId($prop_dtl_id);

                        if ($data["application_detail"]["prop_type_mstr_id"] != 4) {
                            $input["emp_details_id"] = $login_emp_details_id;
                            $input["created_on"] = $created_on;
                            $this->model_prop_floor_details->insertpropfloordetbygbsafid($input, $prop_dtl_id);
                        }
                        $saf_tax_list = $this->model_saf_tax->getsaftaxbygbsafId($level_dtl['govt_saf_dtl_id']); // all
                        foreach ($saf_tax_list as $key => $saf_tax) {
                            $saf_tax_details = $saf_tax;
                            $govt_saf_tax_id = $saf_tax['id'];
                            $fy_yr = $this->model_fy_mstr->getFiyrByid($saf_tax['fy_mstr_id'])['fy'];
                            $quartely_tax = $saf_tax_details['holding_tax'] + $saf_tax_details['water_tax'] + $saf_tax_details['education_cess'] + $saf_tax_details['health_cess'] + $saf_tax_details['latrine_tax'] + $saf_tax_details['additional_tax'];
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
                                $created_on,
                                $fy_yr,
                                $quartely_tax
                            );
                            $this->model_prop_demand->selectInsertByGBSafDtlAndSafTaxDtlId($level_dtl['govt_saf_dtl_id'], $govt_saf_tax_id, $prop_dtl_id, $prop_tax_id, $created_on);
                        }
                    }
                    $saf_tax_list = $this->model_saf_tax->getsaftaxbygbsafId($level_dtl['govt_saf_dtl_id']); // all
                    foreach ($saf_tax_list as $key => $saf_tax) {
                        $saf_tax_details = $saf_tax;
                    }
                    $quartely_tax = $saf_tax_details['holding_tax'] + $saf_tax_details['water_tax'] + $saf_tax_details['education_cess'] + $saf_tax_details['health_cess'] + $saf_tax_details['latrine_tax'] + $saf_tax_details['additional_tax'];
                    $memo_type = 'SAM';
                    $genmemo_last_id = $this->model_saf_memo_dtl->generategbsafmemono(
                        $level_dtl['govt_saf_dtl_id'],
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
                    $ward_nmm = $this->model_ward_mstr->getWardNoByOldWardId($data["application_detail"]['ward_mstr_id'])['ward_no'];
                    $memo_no = 'SAM/' . $ward_nmm . '/' . $genmemo_last_id . $serial_no . '/' . $fy_yr['fy'];
                    $this->model_saf_memo_dtl->updatememonoById($genmemo_last_id, $memo_no, $fy_yr['fy']);
                } else {
                    $sub_holding_no = '000';
                    $road_type = "000";

                    $serial_no = $this->model_prop_dtl->count_ward_by_wardid($data["application_detail"]['ward_mstr_id'])['ward_cnt'];
                    $serial_no = str_pad(++$serial_no, 4, "0", STR_PAD_LEFT);
                    $generated_holding_no = $ward_no . $road_type . $serial_no . $sub_holding_no . $usage_type_code . $const_type_code;

                    $input = [
                        'govt_saf_dtl_id' => $level_dtl['govt_saf_dtl_id'],
                        'generated_holding_no' => $generated_holding_no,
                        'emp_details_id' => $login_emp_details_id,
                        'created_on' => $created_on
                    ];
                    $prop_data["new_holding_no"] = $generated_holding_no;
                    if ($prop_dtl_id = $this->model_prop_dtl->insertData($prop_data)) {
                        if ($data["application_detail"]["prop_type_mstr_id"] != 4) {
                            $this->model_prop_floor_details->insertpropfloordetbygbsafid($input, $prop_dtl_id);
                        }

                        $owner = [
                            "prop_dtl_id" => $prop_dtl_id,
                            "owner_name" => !empty($data["owner_details"]['officer_name'])?$data["owner_details"]['officer_name']:'NA',
                            "mobile_no" => !empty($data["owner_details"]['mobile_no'])?$data["owner_details"]['mobile_no']:0,
                            "email" => $data["owner_details"]['email_id'],
                        ];
        
                        $this->model_prop_owner_detail->insertData($owner);

                        foreach ($data['tax_list'] as $key => $saf_tax) {
                            $saf_tax_details = $saf_tax;
                            $govt_saf_tax_id = $saf_tax['id'];
                            $fy_yr = $this->model_fy_mstr->getFiyrByid($saf_tax['fy_mstr_id'])['fy'];
                            $quartely_tax = $saf_tax_details['holding_tax'] + $saf_tax_details['water_tax'] + $saf_tax_details['education_cess'] + $saf_tax_details['health_cess'] + $saf_tax_details['latrine_tax'] + $saf_tax_details['additional_tax'];
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
                                $created_on,
                                $fy_yr,
                                $quartely_tax
                            );
                            $this->model_prop_demand->selectInsertByGBSafDtlAndSafTaxDtlId($level_dtl['govt_saf_dtl_id'], $govt_saf_tax_id, $prop_dtl_id, $prop_tax_id, $created_on);
                        }
                    }

                    $quartely_tax = $saf_tax_details['holding_tax'] + $saf_tax_details['water_tax'] + $saf_tax_details['education_cess'] + $saf_tax_details['health_cess'] + $saf_tax_details['latrine_tax'] + $saf_tax_details['additional_tax'];

                    $memo_type = 'FAM';
                    $genmemo_last_id = $this->model_saf_memo_dtl->generategbsafmemono(
                        $level_dtl['govt_saf_dtl_id'],
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
                    $ward_nmm = $this->model_ward_mstr->getWardNoByOldWardId($data["application_detail"]['ward_mstr_id'])['ward_no'];
                    $memo_no = 'FAM/' . $ward_nmm . '/' . $genmemo_last_id . $serial_no . '/' . $fy_yr['fy'];
                    $this->model_saf_memo_dtl->updatememonoById($genmemo_last_id, $memo_no, $fy_yr['fy']);

                    if ($genmemo_last_id) {
                        # Update Last Record Level Table
                        $level = array(
                            "verification_status" => 1,
                            "status" => 1,
                            'msg_body' => $_POST['remarks'],
                        );
                        $this->model_govt_saf_level_pending_dtl->UpdateLevelTable($level_dtl['govt_saf_dtl_id'], $level);

                        flashToast('message', 'Memo generated successfully');
                        return $this->response->redirect(base_url("EO_SAF/verifyGBSaf/$govt_saf_dtl_id"));
                    }
                }
            }

            # Back to Citizen
            if (isset($_POST['back_to_citizen']) && $_POST['back_to_citizen'] == "Back to citizen") {
                $levelPending = $this->model_govt_saf_level_pending_dtl->getLastRecord($govt_saf_dtl_id);
                // Update Last Record Level Table
                $level = array(
                    "verification_status" => 2,
                    "status" => 0,
                    'msg_body' => $_POST['remarks'],
                );
                $this->model_govt_saf_level_pending_dtl->UpdateLevelTable($levelPending['govt_saf_dtl_id'], $level);


                // Insert In level Table for Back to citizen
                $level_array = array();
                $level_array['govt_saf_dtl_id'] = $data['application_detail']['id'];
                $level_array['sender_user_type_id'] = 10; //property executive officer
                $level_array['sender_emp_details_id'] = $login_emp_details_id;
                $level_array['receiver_user_type_id'] = 11; // backoffice
                $level_array['verification_status'] = 2; // back to citizen
                $level_array['msg_body'] = $_POST['remarks'];
                $level_array['created_on'] = date('Y-m-d H:i:s');

                $this->model_govt_saf_level_pending_dtl->insertData($level_array);

                flashToast('message', 'Application sent back to citizen successfully');
                return $this->response->redirect(base_url('SI_EO/gb_index'));
            }
        }
        //print_var($data['application_detail']);
        return view('property/gsaf/approveGBSaf', $data);
    }
}
