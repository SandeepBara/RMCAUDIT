<?php
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Controllers\Reports\PropReports;
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
use App\Models\model_prop_dtl;
use App\Models\model_prop_owner_detail;
use App\Models\model_prop_floor_details;
use App\Models\model_saf_tax;
use App\Models\model_prop_tax;
use App\Models\model_saf_demand;
use App\Models\model_prop_demand;
use App\Models\model_fy_mstr;
use App\Models\model_prop_type_mstr;
use App\Models\model_road_type_mstr;
use App\Models\model_view_saf_floor_details;
use App\Models\model_field_verification_dtl;
use App\Models\model_field_verification_floor_details;
use App\Models\model_view_ward_permission;
use App\Models\model_datatable;
use App\Models\model_govt_saf_dtl;
use App\Models\model_govt_saf_tax_dtl;
use App\Models\model_g_saf;
use App\Models\model_govt_saf_officer_dtl;
use App\Models\model_govt_doc_dtl;
use App\Models\model_govt_saf_level_pending_dtl;
use App\Models\model_view_saf_dtl;
use App\Models\model_prop_saf_deactivation;
use Exception;
use Config\Services;

use function PHPSTORM_META\elementType;

class SI_SAF extends AlphaController
{
    protected $db;
    protected $dbSystem;
    protected $PropReports;
    protected $model_datatable;
    protected $model_ward_mstr;
    protected $model_emp_dtl_permission;
    protected $model_view_ward_permission;
    protected $model_fy_mstr;
    protected $model_view_saf_receive_list;
    protected $model_view_saf_doc_dtl;
    protected $model_level_pending_dtl;
    protected $model_saf_owner_detail;
    protected $model_saf_doc_dtl;
    protected $model_saf_dtl;
    protected $model_saf_memo_dtl;
    protected $model_saf_floor_details;
    protected $model_prop_dt;
    protected $model_prop_owner_detail;
    protected $model_prop_floor_details;
    protected $model_saf_tax;
    protected $model_prop_tax;
    protected $model_saf_demand;
    protected $model_prop_demand;
    protected $model_prop_type_mstr;
    protected $model_road_type_mstr;
    protected $model_view_saf_floor_details;
    protected $model_field_verification_dtl;
    protected $model_field_verification_floor_details;
    protected $model_govt_saf_dtl;
    protected $model_govt_saf_tax_dtl;

    protected $model_g_saf;
    protected $model_govt_saf_officer_dtl;
    protected $model_govt_doc_dtl;
    protected $model_govt_saf_level_pending_dtl;
    protected $model_view_saf_dtl;
    protected $model_prop_dtl;
    protected $model_prop_saf_deactivation;

    public function __construct()
    {
        parent::__construct();
        helper(['db_helper', 'utility_helper']);

        if($db_name = dbConfig("property"))
        {
            $this->db = db_connect($db_name);
        }
        if($db_system = dbSystem())
        {
            $this->dbSystem = db_connect($db_system);
        }
        $this->PropReports = new PropReports();
        $this->model_ward_mstr = new model_ward_mstr($this->dbSystem);
        $this->model_emp_dtl_permission = new model_emp_dtl_permission($this->dbSystem);
        $this->model_view_ward_permission = new model_view_ward_permission($this->dbSystem);
        $this->model_fy_mstr = new model_fy_mstr($this->dbSystem);
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
        $this->model_prop_type_mstr = new model_prop_type_mstr($this->db);
        $this->model_road_type_mstr = new model_road_type_mstr($this->db);
        $this->model_view_saf_floor_details = new model_view_saf_floor_details($this->db);
        $this->model_field_verification_dtl = new model_field_verification_dtl($this->db);
        $this->model_field_verification_floor_details = new model_field_verification_floor_details($this->db);
        $this->model_datatable = new model_datatable($this->db);
        $this->model_govt_saf_dtl = new model_govt_saf_dtl($this->db);
        $this->model_govt_saf_tax_dtl = new model_govt_saf_tax_dtl($this->db);


        $this->model_g_saf = new model_g_saf($this->db);
        $this->model_govt_saf_officer_dtl=new model_govt_saf_officer_dtl($this->db);
        $this->model_govt_doc_dtl=new model_govt_doc_dtl($this->db);
        $this->model_govt_saf_level_pending_dtl=new model_govt_saf_level_pending_dtl($this->db);
        $this->model_view_saf_dtl = new model_view_saf_dtl($this->db);
        $this->model_prop_saf_deactivation = new model_prop_saf_deactivation($this->db);

    }

    function __destruct() {
        $this->db->close();
        $this->dbSystem->close();
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
        $data['wardList'] = $this->model_view_ward_permission->getPermittedWard($login_emp_details_id);

        $imploded_ward_mstr_id= implode(', ', array_map(function ($entry) {
            return $entry['ward_mstr_id'];
          }, $data['wardList']));


        if($this->request->getMethod()=='post')
        {

            $data['ward_mstr_id'] = $this->request->getVar('ward_mstr_id');
            $data['from_date'] = $this->request->getVar('from_date');
            $data['to_date'] = $this->request->getVar('to_date');
            if($data['ward_mstr_id']!="")
            {
                $where = "ward_mstr_id=".$data['ward_mstr_id']." AND date(forward_date) between '".$data["from_date"]."'
                AND '".$data['to_date']."' AND receiver_user_type_id=9
                AND verification_status=0 AND view_saf_receive_list.status=1 ORDER BY id DESC";
            }
            else
            {
                $where = "ward_mstr_id in (".$imploded_ward_mstr_id.") AND date(forward_date) between '".$data['from_date']."'
                AND '".$data['to_date']."' AND receiver_user_type_id=9
                AND verification_status=0 AND view_saf_receive_list.status=1 ORDER BY id DESC";
            }
        }
        else
        {
            $data['from_date'] = date('Y-m-d');
            $data['to_date'] = date('Y-m-d');
            $where = "ward_mstr_id in (".$imploded_ward_mstr_id.") AND date(forward_date) between '".$data['from_date']."'
            AND '".$data['to_date']."' AND receiver_user_type_id=9
            AND verification_status=0 AND view_saf_receive_list.status=1 ORDER BY id DESC";
        }

        $Session->set('where', $where);
        $Session->set('from_date', $data['from_date']);
        $Session->set('to_date', $data['to_date']);
        $Session->set('ward_mstr_id', $data['ward_mstr_id'] ?? NULL);
        $Session->set('wardList', $data['wardList']);

        return $this->response->redirect(base_url('SI_SAF/inbox_list/'));

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
                    tbl_level_pending_dtl.forward_time
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

        //print_var($sql);
        $result = $this->model_datatable->getDatatable($sql);
        $data['inboxList'] = isset($result['result']) ? $result['result'] : null;
        $data['pager'] = $result['count'];
        return view('property/saf/si_saf_list', $data);
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
                    tbl_level_pending_dtl.forward_time
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


        $result = $this->model_datatable->getDatatable($sql);
        $data['inboxList'] = isset($result['result']) ? $result['result'] : null;
        $data['pager'] = $result['count'];
        $data['offset'] = $result['offset'];
        return view('property/saf/si_saf_list_new', $data);
    }

    public function outbox_list()
    {
        $session = session();
        $ulb_mstr = $session->get("ulb_dtl");
        $emp_mstr = $session->get("emp_details");
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
                    sender_user_type_id=".$receiver_emp_details_id."
                    AND tbl_level_pending_dtl.verification_status='1'
                    AND tbl_level_pending_dtl.status='1' ".$whereSearchPrm.$whereAssessmentType.$wherePropType."
                ORDER BY tbl_level_pending_dtl.id DESC";


        $result = $this->model_datatable->getDatatable($sql);
        $data['inboxList'] = isset($result['result']) ? $result['result'] : null;
        $data['pager'] = $result['count'];
        return view('property/saf/saf_si_forward_list', $data);
    }

    public function utc_saf_view($level_id) {

        $data=(array)null;
        $Session = Session();
        $ulb = $Session->get('ulb_dtl');
        $emp_details=$Session->get("emp_details");

        $login_emp_dtl_id = $emp_details["id"];
        $user_type_mstr_id = $emp_details["user_type_mstr_id"];

        if ($this->request->getMethod()=='post') {
            try {
                $data = arrFilterSanitizeString($this->request->getVar());
                if(isset($data['btn_back_to_citizen']))
                {
                    $updateInsertInput = [
                        "sender_user_type_id"=>$user_type_mstr_id,
                        "sender_emp_details_id"=>$login_emp_dtl_id,
                        "receiver_user_type_id"=>11,
                        "forward_date"=>date("Y-m-d"),
                        "forward_time"=>date("H:i:s"),
                        "remarks"=>$data["remarks"],
                        "status"=>1,
                        "verification_status"=>2
                    ];
                    $builder = $this->db->table("tbl_level_pending_dtl")
                                ->where('id', $level_id)
                                ->update($updateInsertInput);
                    if ($builder) {
                        $this->db->table("tbl_saf_dtl")
                                ->where('id', $data["saf_dtl_id"])
                                ->update(["saf_pending_status"=>2]);

                        $updateInsertInput['saf_dtl_id'] = $data["saf_dtl_id"];
                        $updateInsertInput['created_on'] = "NOW()";
                        $this->model_level_pending_dtl->bugfix_level_pending($updateInsertInput); //Shashi
                        
                        $updateInsertInput = [
                            "saf_dtl_id"=>$data["saf_dtl_id"],
                            "level_pending_dtl_id"=>$level_id,
                            "sender_user_type_id"=>$user_type_mstr_id,
                            "sender_emp_details_id"=>$login_emp_dtl_id,
                            "sender_ip_address"=>get_client_ip(),
                            "remarks"=>$data["remarks"],
                            "created_on"=>date("Y-m-d H:i:s"),
                            "status"=>1
                        ];
                        $this->db->table("tbl_level_sent_back_dtl")
                                    ->insert($updateInsertInput);;

                        return $this->response->redirect(base_url('SI_SAF/index_ulb_tc'));
                    }
                }
                
                if(isset($data['btn_backward_submit']))
                {
                    $level_data=[
                        'level_pending_dtl_id'=> md5($level_id),
                        'remarks' => $data['remarks'],
                        'verification_status'=> 1,
                        'status'=> 0,
                        'sender_emp_details_id' => $login_emp_dtl_id,
                        'receiver_emp_details_id' => $login_emp_dtl_id
                        ];
                        
                    if($updatebackward = $this->model_level_pending_dtl->updatelevelpendingById($level_data))
                    {
                        $level_data = [
                            'remarks' => $data['remarks'],
                            'level_pending_dtl_id' => $level_id,
                            'saf_dtl_id' => $data['saf_dtl_id'],
                            'emp_details_id' => $login_emp_dtl_id,
                            'created_on' =>date('Y-m-d H:i:s'),
                            'forward_date' =>date('Y-m-d'),
                            'forward_time' =>date('H:i:s'),
                            'sender_user_type_id' => 7,// ULB Tax Collector
                            'receiver_user_type_id'=> 6,// Dealing
                            'verification_status'=> 0,
                            'status'=> 1,
                            'sender_emp_details_id' => $login_emp_dtl_id
                        ];
                        $this->model_level_pending_dtl->bugfix_level_pending($level_data); //Shashi
                        if($insertbackward = $this->model_level_pending_dtl->insrtlevelpendingdtl($level_data))
                        {
                            flashToast("message", "Application sent back to Dealing");
                            return $this->response->redirect(base_url('SI_SAF/index_ulb_tc/'));
                        }
                    }
                }
            } catch(Exception $e) {
                echo $e;
            }
        }

        $sql = "SELECT * FROM tbl_level_pending_dtl WHERE id=".$level_id;
        if ($level_result = $this->db->query($sql)->getFirstRow("array")) {
            $sql = "SELECT * FROM get_saf_full_details(".(int)$level_result["saf_dtl_id"].")";
            if ($result = $this->db->query($sql)->getFirstRow("array")) {
                $data = $basic_details_dt = json_decode($result["get_saf_full_details"], true);
                $data["ulb_mstr_id"]=$ulb["ulb_mstr_id"];
                $data["emp_details"]=$emp_details;
                $data['basic_details_data'] = array(
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
                $sql = "SELECT 
                                tbl_saf_doc_dtl.*,
                                CASE WHEN tbl_doc_mstr.doc_name IS NULL THEN tbl_saf_doc_dtl.other_doc ELSE tbl_doc_mstr.doc_name END AS doc_name,
                                tbl_doc_mstr.doc_type,
                                tbl_saf_owner_detail.owner_name
                            FROM tbl_saf_doc_dtl 
                            LEFT JOIN tbl_saf_owner_detail ON tbl_saf_owner_detail.id=tbl_saf_doc_dtl.saf_owner_dtl_id
                            LEFT JOIN tbl_doc_mstr ON tbl_doc_mstr.id=tbl_saf_doc_dtl.doc_mstr_id
                            WHERE 
                                tbl_saf_doc_dtl.saf_dtl_id=".(int)$level_result["saf_dtl_id"]." 
                                AND tbl_saf_doc_dtl.status=1 AND tbl_saf_doc_dtl.verify_status=1";
                if ($doc_dtl_list = $this->db->query($sql)->getResultArray()) {
                    //$data["doc_list"] = $this->model_saf_doc_dtl->getAllDocumentsWithOwner2((int)$level_result["saf_dtl_id"]);
                    $data["doc_dtl_list"] = $doc_dtl_list;
                }
                $data["doc_list"] = $this->model_saf_doc_dtl->getAllDocumentsWithOwner2((int)$level_result["saf_dtl_id"]);
                //$data["basic_details_data"] = $data;
                $data["level_result"] = $level_result;
                //print_var($data);

                return view('property/Saf/ulb_tc_mis_verification', $data);
            }
        }
    }


    public function index_ulb_tc()
    {
        $Session = Session();
        $emp_mstr = $Session->get("emp_details");
        $emp_details_id = $emp_mstr["id"];
        $user_type_mstr_id = $emp_mstr["user_type_mstr_id"];
        $data = arrFilterSanitizeString($this->request->getVar());

        $data['wardList'] = $this->model_view_ward_permission->getPermittedWard($emp_details_id);
        $permittedWard = array_column($data['wardList'], 'ward_mstr_id');
        $whereWard = " AND tbl_saf_dtl.ward_mstr_id IN (".implode(',', $permittedWard).")";
        $wherePropType = "";
        $whereAssessmentType = "";
        $whereSearchPrm = "";
        $whereDateRange = "";
        if (isset($data["from_date"]) && isset($data["upto_date"]) && isset($data["ward_mstr_id"]) && isset($data["search_param"]) && isset($data["prop_type_mstr_id"]) && isset($data["assessment_type"])) {
            if ($data["from_date"]!="" && $data["upto_date"]!="") {
                $whereDateRange = " AND tbl_level_pending_dtl.forward_date::DATE BETWEEN '".$data["from_date"]."' AND '".$data["upto_date"]."'";
            }
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
                    tbl_saf_dtl.prop_address,
                    tbl_saf_dtl.apply_date,
                    tbl_prop_dtl.new_holding_no,
                    tbl_level_pending_dtl.forward_date,
                    tbl_level_pending_dtl.forward_time,
                    tbl_level_pending_dtl.remarks,
                    geotag_dtl.saf_geotag_dtl
                FROM tbl_level_pending_dtl
                INNER JOIN tbl_saf_dtl ON tbl_level_pending_dtl.saf_dtl_id = tbl_saf_dtl.id AND tbl_saf_dtl.status=1 AND tbl_level_pending_dtl.verification_status=0 ".$whereWard." AND tbl_saf_dtl.apply_date < '2022-08-05'
                LEFT JOIN (
                    SELECT
                        geotag_dtl_id,
                        json_agg(json_build_object('direction_type', direction_type, 'image_path', image_path, 'latitude', latitude, 'longitude', longitude) ORDER BY direction_type) AS saf_geotag_dtl
                    FROM tbl_saf_geotag_upload_dtl
                    WHERE status=1
                    GROUP BY geotag_dtl_id
                ) AS geotag_dtl ON geotag_dtl.geotag_dtl_id=tbl_saf_dtl.id
                LEFT JOIN tbl_prop_dtl ON tbl_prop_dtl.saf_dtl_id=tbl_saf_dtl.id
                INNER JOIN tbl_prop_type_mstr ON tbl_prop_type_mstr.id=tbl_saf_dtl.prop_type_mstr_id
                INNER JOIN ( SELECT tbl_saf_owner_detail.saf_dtl_id,
                        string_agg(tbl_saf_owner_detail.owner_name::text, ', '::text) AS owner_name,
                        string_agg(tbl_saf_owner_detail.mobile_no::text, ', '::text) AS mobile_no
                    FROM tbl_saf_owner_detail
                    GROUP BY tbl_saf_owner_detail.saf_dtl_id
                ) owner_dtl ON owner_dtl.saf_dtl_id = tbl_level_pending_dtl.saf_dtl_id
                INNER JOIN view_ward_mstr ON view_ward_mstr.id = tbl_saf_dtl.ward_mstr_id
                WHERE
                    tbl_level_pending_dtl.receiver_user_type_id=".$user_type_mstr_id."
                    AND tbl_level_pending_dtl.status='1'
                    AND doc_upload_status='1' ".$whereSearchPrm.$whereAssessmentType.$wherePropType.$whereDateRange."
                ORDER BY tbl_level_pending_dtl.forward_date DESC, tbl_level_pending_dtl.forward_time  DESC";

            //print_var($sql);
            $result = $this->model_datatable->getDatatable($sql);
            $data['inboxList'] = $result['result'];
            $data['pager'] = $result['count'];
            $data['offset'] = $result['offset'];
            $data['view']="index_ulb_tc";
            $data['permitted_ward']=implode(',', $permittedWard);
        return view('property/saf/saf_ulb_tc_inbox_list', $data);
    }
    public function index_ulb_tc_new()
    { 
        $Session = Session();
        $emp_mstr = $Session->get("emp_details");
        $emp_details_id = $emp_mstr["id"];
        $user_type_mstr_id = $emp_mstr["user_type_mstr_id"];
        $data = arrFilterSanitizeString($this->request->getVar());

        $data['wardList'] = $this->model_view_ward_permission->getPermittedWard($emp_details_id);
        $permittedWard = array_column($data['wardList'], 'ward_mstr_id');
        $whereWard = " AND tbl_saf_dtl.ward_mstr_id IN (".implode(',', $permittedWard).")";
        $wherePropType = "";
        $whereAssessmentType = "";
        $whereSearchPrm = "";
        $whereDateRange = "";
        if (isset($data["from_date"]) && isset($data["upto_date"]) && isset($data["ward_mstr_id"]) && isset($data["search_param"]) && isset($data["prop_type_mstr_id"]) && isset($data["assessment_type"])) {
            if ($data["from_date"]!="" && $data["upto_date"]!="") {
                $whereDateRange = " AND tbl_level_pending_dtl.forward_date::DATE BETWEEN '".$data["from_date"]."' AND '".$data["upto_date"]."'";
            }
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
                    tbl_saf_dtl.prop_address,
                    tbl_saf_dtl.apply_date,
                    tbl_prop_dtl.new_holding_no,
                    tbl_level_pending_dtl.forward_date,
                    tbl_level_pending_dtl.forward_time,
                    tbl_level_pending_dtl.remarks,
                    geotag_dtl.saf_geotag_dtl
                FROM tbl_level_pending_dtl
                INNER JOIN tbl_saf_dtl ON tbl_level_pending_dtl.saf_dtl_id = tbl_saf_dtl.id AND tbl_saf_dtl.status=1 AND tbl_level_pending_dtl.verification_status=0 ".$whereWard." AND tbl_saf_dtl.apply_date >='2022-08-05'
                LEFT JOIN (
                    SELECT
                        geotag_dtl_id,
                        json_agg(json_build_object('direction_type', direction_type, 'image_path', image_path, 'latitude', latitude, 'longitude', longitude) ORDER BY direction_type) AS saf_geotag_dtl
                    FROM tbl_saf_geotag_upload_dtl
                    WHERE status=1
                    GROUP BY geotag_dtl_id
                ) AS geotag_dtl ON geotag_dtl.geotag_dtl_id=tbl_saf_dtl.id
                LEFT JOIN tbl_prop_dtl ON tbl_prop_dtl.saf_dtl_id=tbl_saf_dtl.id
                INNER JOIN tbl_prop_type_mstr ON tbl_prop_type_mstr.id=tbl_saf_dtl.prop_type_mstr_id
                INNER JOIN ( SELECT tbl_saf_owner_detail.saf_dtl_id,
                        string_agg(tbl_saf_owner_detail.owner_name::text, ', '::text) AS owner_name,
                        string_agg(tbl_saf_owner_detail.mobile_no::text, ', '::text) AS mobile_no
                    FROM tbl_saf_owner_detail
                    GROUP BY tbl_saf_owner_detail.saf_dtl_id
                ) owner_dtl ON owner_dtl.saf_dtl_id = tbl_level_pending_dtl.saf_dtl_id
                INNER JOIN view_ward_mstr ON view_ward_mstr.id = tbl_saf_dtl.ward_mstr_id
                WHERE
                    tbl_level_pending_dtl.receiver_user_type_id=".$user_type_mstr_id."
                    AND tbl_level_pending_dtl.status='1'
                    AND doc_upload_status='1' ".$whereSearchPrm.$whereAssessmentType.$wherePropType.$whereDateRange."
                ORDER BY tbl_level_pending_dtl.forward_date DESC, tbl_level_pending_dtl.forward_time  DESC
        ";

        if($data["export"]??false){
			$result = $this->model_datatable->getRecords($sql, false);
			$records = [];
			if ($result) {
				foreach ($result as $key => $tran_dtl) {
					$records[] = [
						's_no' => $key+1,
						"ward_no" => $tran_dtl["ward_no"],
						'apply_date' => $tran_dtl['apply_date'],
						'assessment_type' => $tran_dtl['assessment_type'],
						'property_type' => $tran_dtl['property_type'],
						'saf_no' => $tran_dtl['saf_no'],
						'owner_name' => $tran_dtl['owner_name'],
						'mobile_no' => $tran_dtl['mobile_no'],
						'prop_address' => $tran_dtl['prop_address'],
						'forward_date' => $tran_dtl['forward_date']." ".$tran_dtl['forward_time'],
					];
				}
			}
			phpOfficeLoad();
			$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
			$activeSheet = $spreadsheet->getActiveSheet();
			$activeSheet->setCellValue('A1', 'Sl No.');
			$activeSheet->setCellValue('B1', 'Ward No');
			$activeSheet->setCellValue('C1', 'Apply Date');
			$activeSheet->setCellValue('D1', 'Assessment Type');
			$activeSheet->setCellValue('E1', 'Property Type');
			$activeSheet->setCellValue('F1', 'SAF No.');
			$activeSheet->setCellValue('G1', 'Owner Name');
			$activeSheet->setCellValue('H1', 'Mobile No.');
			$activeSheet->setCellValue('I1', 'Address');
			$activeSheet->setCellValue('J1', 'Forward At');


			$activeSheet->fromArray($records, NULL, 'A3');

			$filename = "ULB_TC_LIST" . date('Ymd-hisa') . ".xlsx";
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="' . $filename . '"');
			header('Cache-Control: max-age=0');
			$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
			$writer->save('php://output');
			exit();
		}

        // print_var($sql);
        $result = $this->model_datatable->getDatatable($sql);
        $data['inboxList'] = $result['result'];
        $data['pager'] = $result['count'];
        $data['offset'] = $result['offset'];
        //print_var($result);
        $data['view']="index_ulb_tc_new";
        $data['permitted_ward']=implode(',', $permittedWard);
        return view('property/saf/saf_ulb_tc_inbox_list', $data);
    }

    public function outbox_list_ulb_tc()
    {
        $session = session();
        $ulb_mstr = $session->get("ulb_dtl");
        $emp_mstr = $session->get("emp_details");
        $login_user_type_mstr_id = $emp_mstr["user_type_mstr_id"];
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
                    sender_user_type_id=".$login_user_type_mstr_id."
                    AND sender_emp_details_id=".$login_emp_details_id."
                    AND tbl_level_pending_dtl.verification_status IN (0, 1)
                    --AND tbl_level_pending_dtl.status='1' 
                    ".
                    $whereSearchPrm.$whereAssessmentType.$wherePropType."
                ORDER BY tbl_level_pending_dtl.id DESC";


        //print_var($sql);
        $result = $this->model_datatable->getDatatable($sql);
        $data['inboxList'] = isset($result['result']) ? $result['result'] : null;
        $data['pager'] = $result['count'];
        $data['permitted_ward']=implode(',', $permittedWard);
        return view('property/saf/saf_ulb_tc_forward_list', $data);
    }
    
    public function inbox_list_ulb_tc_ajax() {
        if($this->request->getMethod()=='post'){
            try{

                $data = arrFilterSanitizeString($this->request->getVar());
                $sql = $this->PropReports->inboxListUlbTcDtl($data);
                $result = $this->db->query("SELECT generateCSVReports('".$sql."', 'ulbtc_inbox_dtl')");
                $filename = $result->getFirstRow("array");
                return json_encode($filename);
            } catch(Exception $e) {
                echo $e;
            }
        }
    }

    public function outbox_list_ulb_tc_ajax() {
        if($this->request->getMethod()=='post'){
            try{

                $data = arrFilterSanitizeString($this->request->getVar());
                $sql = $this->PropReports->outboxListUlbTcDtl($data);
                $result = $this->db->query("SELECT generateCSVReports('".$sql."', 'ulbtc_outbox_dtl')");
                $filename = $result->getFirstRow("array");
                return json_encode($filename);
            } catch(Exception $e) {
                echo $e;
            }
        }
    }

    public function view($saf_dtl_id)
    {
        $data =(array)null;
        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $emp_mstr = $Session->get("emp_details");
        $login_emp_details_id = $emp_mstr["id"];
        $sender_emp_details_id = $login_emp_details_id;
        $basic_details_dt = $this->model_view_saf_dtl->getSafDtlByMD5SafDtlId($saf_dtl_id);
        $data = $basic_details_dt;
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $data['previous_prop_dtl']=$this->model_prop_dtl->getholdingnobysafid($data['saf_dtl_id']);

        $data['saf_owner_detail'] = $this->model_saf_owner_detail->getOwnerdtlBySAFId(['saf_dtl_id'=> $data['saf_dtl_id']]);

        // applicant img & document
        foreach($data['saf_owner_detail'] as $key => $owner_detail)
        {
            $input = [
                'saf_dtl_id'=> $data['saf_dtl_id'],
                'saf_owner_dtl_id'=> $owner_detail['id'],
            ];
            $data['saf_owner_detail'][$key]['applicant_img_dtl'] = $this->model_saf_doc_dtl->getApplicantImgBySafDtlAndSafOwnerDtlId($input);
            $data['saf_owner_detail'][$key]['applicant_doc_dtl'] = $this->model_saf_doc_dtl->getApplicantDocBySafDtlAndSafOwnerDtlId($input);
        }
        $data['saf_tax_list'] = $this->model_saf_tax->getSafTaxDtlBySafDtlId(['saf_dtl_id'=> $data['saf_dtl_id']]);
        $data['document_list'] = $this->model_saf_doc_dtl->getAllVerifiedDocuments(['saf_dtl_id'=> $data['saf_dtl_id']]);
        $data['latest_document_list'] = $this->model_saf_doc_dtl->getLatestUploadedDocuments(['saf_dtl_id'=> $data['saf_dtl_id']]);
        $data['form'] = $this->model_level_pending_dtl->getLastRecord(['saf_dtl_id'=> $data['saf_dtl_id'], 'receiver_user_type_id'=> 9]);//Property Section Incharge

        //print_var($data['document_list']);
        if($this->request->getMethod()=='post')
        {
            $inputs = arrFilterSanitizeString($this->request->getVar());

            if (isset($inputs["btn_verify"])) {
                $input = [
                    "id" => $inputs["saf_doc_dtl_id"],
                    "verify_status" => 1, //Verified
                    "remarks" => "Verified",
                    "verified_by_emp_id" => $login_emp_details_id,
                ];

                $this->model_saf_doc_dtl->verifyDocument($input);
                return $this->response->redirect(base_url("si_saf/view/" . $saf_dtl_id) . "#documen");
            }

            if (isset($inputs["btn_reject"])) {
                $input = [
                    "id" => $inputs["saf_doc_dtl_id"],
                    "verify_status" => 2, //Rejected
                    "remarks" => $inputs["remarks"],
                    "verified_by_emp_id" => $login_emp_details_id,
                ];

                $this->model_saf_doc_dtl->verifyDocument($input);
                return $this->response->redirect(base_url("si_saf/view/" . $saf_dtl_id) . "#documen");
            }

            if(isset($inputs['btn_backward_submit']))
            {
                $level_data=[
                    'level_pending_dtl_id'=> md5($data['form']['id']),
                    'remarks' => $inputs['remarks'],
                    'verification_status'=> 1,
                    'status'=> 0,
                    'sender_emp_details_id' => $sender_emp_details_id,
                    'receiver_emp_details_id' => $sender_emp_details_id
                    ];
                if($updatebackward = $this->model_level_pending_dtl->updatelevelpendingById($level_data))
                {
                    $level_data = [
                        'remarks' => $inputs['remarks'],
                        'level_pending_dtl_id' => $data['form']['id'],
                        'saf_dtl_id' => $data['saf_dtl_id'],
                        'emp_details_id' => $login_emp_details_id,
                        'created_on' =>date('Y-m-d H:i:s'),
                        'forward_date' =>date('Y-m-d'),
                        'forward_time' =>date('H:i:s'),
                        'sender_user_type_id' => 9,//Property Section Incharge
                        'receiver_user_type_id'=> 7,//ULB Tax Collector
                        'verification_status'=> 0,
                        'status'=> 1,
                        'sender_emp_details_id' => $sender_emp_details_id
                    ];
					
					$this->model_level_pending_dtl->bugfix_level_pending($level_data); //Shashi
					
                    if($insertbackward = $this->model_level_pending_dtl->insrtlevelpendingdtl($level_data))
                    {
                        flashToast("message", "Application sent back to ULB TC");
                        return $this->response->redirect(base_url('SI_SAF/index/'));
                    }
                }
            }

            if(isset($inputs['btn_back_to_citizen_submit']))
            {

                $data = [

                        'id' =>$data['form']['id'],
                        'saf_dtl_id' => $data['saf_dtl_id'],
                        'remarks' => $inputs['remarks'],
                        'sender_user_type_id' => 9,//Property Section Incharge
                        'receiver_user_type_id' => 11,//Back Office
                        'sender_emp_details_id' => $sender_emp_details_id
                    ];
                if($updatebacktocitizen = $this->model_level_pending_dtl->sendBackToCitizen($data))
                {

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
                    'sender_user_type_id' => 9,     //Propert Dealing Assisstant
                    'sender_emp_details_id' => $data["sender_emp_details_id"],
                    'sender_ip_address' => $ip_address,
                    'remarks' => $data["remarks"],
                    'status' => 1

                ];
				
				$bub_fix = [
					'saf_dtl_id' => $data["saf_dtl_id"],
					'sender_user_type_id' => 9,
					'receiver_user_type_id' => 11,
					'forward_date' => "NOW()",
					'forward_time' => "NOW()",
					'created_on' => "NOW()",
					'status' => 1,
					'remarks' => $data["remarks"],
					'sender_emp_details_id' => $sender_emp_details_id
            
				];
				$this->model_level_pending_dtl->bugfix_level_pending($bub_fix); //Shashi

                $this->model_level_pending_dtl->record_back_to_citizen($level_record);

                    $this->model_saf_dtl->update_saf_pending_status(["saf_dtl_id"=> $data['saf_dtl_id'], "doc_verify_status"=> 2]);//backtocitizen
                    flashToast("message", "Applicationn sent back to citizen successfully.");
                    return $this->response->redirect(base_url('si_saf/index'));
                }
            }

            if(isset($inputs['btn_verify_submit']))
            {
                if ($basic_details_dt["assessment_type"]=="Reassessment") {
                    $safHelper = new SAFHelper($this->db);
                    
                    //$this->db->transBegin();
                    if ($FV_Saf_Dtl = $this->model_field_verification_dtl->getUlbDataBySafDtlId($input)) {
                        $remarksBYSI = $inputs['remarks'];
                        $inputs = $FV_Saf_Dtl;
                        // FAM GENERATTION in case if Reassessment
                        $memo = $this->model_saf_memo_dtl->generate_assessment_final_memo($inputs['saf_dtl_id'], $login_emp_details_id);
                        $genmemo_last_id=$memo["generate_assessment_final_memo"];
                        // END FAM GENERATTION in case if Reassessment
                        // End Different Calculation
                        

                        $input = ['saf_dtl_id' => $inputs['saf_dtl_id']];
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
                            if ($FV_Saf_floor_Dtl = $this->model_field_verification_floor_details->getUlbDataBySafDtlId($inputs)) {
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

                            $safTaxDtl = $this->model_saf_tax->getJoinBySafDtlIdMd5(md5($inputs["saf_dtl_id"]));
                            if ($diffTax = $safHelper->calcDiffPanelty($safTaxDtl, $newSafTaxDtl)) {
                                $this->model_prop_tax->updateSafDeactiveStatus(['prop_dtl_id'=>$prop_dtl_id]);
                                $safHelper->calDiffSafDemand($newSafTaxDtl, $inputs["saf_dtl_id"], $prop_dtl_id);
                            }
                        }
                        // End Different Calculation
                        $level_data=[
                            "level_pending_dtl_id"=> md5($data["form"]["id"]),
                            "status"=> 0,
                            "verification_status"=> 1,
                            'sender_emp_details_id' => $sender_emp_details_id,
                            'receiver_emp_details_id' => $sender_emp_details_id
                        ];
                        
                        if($this->model_level_pending_dtl->updatelevelpendingById($level_data)) {
                            $checkAgencyData = $this->model_field_verification_dtl->getulbdatabysafid($data['saf_dtl_id'], 'AGENCY TC');
                            $recv_user_type_id=5;
                            $status=1;
                            $vstatus=0;
                            if($checkAgencyData){
                                $recv_user_type_id=9;
                                $status=0;
                                $vstatus=1;
                            }
                            $level_data = [
                                'remarks' => $remarksBYSI,
                                'saf_dtl_id' => $data['saf_dtl_id'],
                                'emp_details_id' => $login_emp_details_id,
                                'created_on' => "NOW()",
                                'forward_date' => "NOW()",
                                'forward_time' => "NOW()",
                                'sender_user_type_id' => 9,//Property Section Incharge
                                'receiver_user_type_id'=> $recv_user_type_id,//Agency Tc
                                'verification_status'=> $vstatus,
                                'status'=> $status,
                                'sender_emp_details_id' => $login_emp_details_id,
                                'receiver_emp_details_id' => $login_emp_details_id
                            ];
							$this->model_level_pending_dtl->bugfix_level_pending_new($level_data); //Shashi

                            if($this->model_level_pending_dtl->insrtSILevelFinaldtl($level_data)) {
                                $inputData = [
                                    "saf_dtl_id"=>$data['saf_dtl_id'],
                                    "doc_verify_status"=>1
                                ];
                                $this->model_saf_dtl->update_saf_pending_status($inputData);
                                $this->db->transCommit();
                                flashToast("message", "FAM generated successfully.");
                                return $this->response->redirect(base_url('SI_SAF/index'));
                            }
                        }
                    }
                } else if ($basic_details_dt["assessment_type"]!="Reassessment") {
                    $level_data=[
                        "level_pending_dtl_id"=> md5($data["form"]["id"]),
                        "status"=> 0,
                        "verification_status"=> 1,
                        'sender_emp_details_id' => $sender_emp_details_id,
                        'receiver_emp_details_id' => $sender_emp_details_id
                    ];
                    
                    if($this->model_level_pending_dtl->updatelevelpendingById($level_data))
                    {
                        $level_data = [
                            'remarks' => $inputs['remarks'],
                            'saf_dtl_id' => $data['saf_dtl_id'],
                            'emp_details_id' => $login_emp_details_id,
                            'created_on' => "NOW()",
                            'forward_date' => "NOW()",
                            'forward_time' => "NOW()",
                            'sender_user_type_id' => 9,//Property Section Incharge
                            'receiver_user_type_id'=> 10,//Executive Officer
                            'verification_status'=> 0,
                            'status'=> 1,
                            'sender_emp_details_id' => $login_emp_details_id
                        ];
						
						$this->model_level_pending_dtl->bugfix_level_pending($level_data); //Shashi
                        if($this->model_level_pending_dtl->insrtlevelpendingdtl($level_data))
                        {
                            flashToast("message", "Application forwarded to exective officer");
                            return $this->response->redirect(base_url('SI_SAF/index'));
                        }
                    }
                }
            }

            if(isset($inputs['btn_reject_submit']))
            {
                $level_data = [
                    "level_pending_dtl_id"=> md5($data["form"]["id"]),
                    "status"=> 0,
                    "verification_status"=> 1,
                    'sender_emp_details_id' => $sender_emp_details_id,
                    'receiver_emp_details_id' => $sender_emp_details_id
                ];
                
                if($this->model_level_pending_dtl->updatelevelpendingById($level_data)) {
 
                    $level_data = [
                        'remarks' => $inputs['remarks'],
                        'saf_dtl_id' => $data['saf_dtl_id'],
                        'emp_details_id' => $login_emp_details_id,
                        'created_on' => "NOW()",
                        'forward_date' => "NOW()",
                        'forward_time' => "NOW()",
                        'sender_user_type_id' => 9,//Property Section Incharge
                        'receiver_user_type_id'=> 9,//Property Section Incharge
                        'verification_status'=> 1,
                        'status'=> 0,
                        'sender_emp_details_id' => $login_emp_details_id,
                        'receiver_emp_details_id' => $login_emp_details_id
                    ];
                    
                    $this->model_level_pending_dtl->bugfix_level_pending_new($level_data); //Shashi

                    if($this->model_level_pending_dtl->insrtSILevelFinaldtl($level_data)) {

                        $deactivate_data = [
                            'prop_dtl_id' => $data['saf_dtl_id'],
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
                            $this->model_saf_dtl->updateSafDtlStatus($data['saf_dtl_id']);
                            flashToast("message", "Application Rejected");
                            return $this->response->redirect(base_url('SI_SAF/inbox_list_new'));
                        }
                        
                    }
                }
            }


        }
        else
        {
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
            if ($fieldResult = $this->model_field_verification_dtl->getFieldDtlBYSAFId($basic_details_dt["saf_dtl_id"])) {
                $data['verification'] = $fieldResult;
            }
            $data['level']=$this->model_level_pending_dtl->getAllLevelDtl($basic_details_dt["saf_dtl_id"]);
            // print_var($data);
            // return;
            return view('property/saf/si_saf_view', $data);
        }
    }

    public function view_new($saf_dtl_id)
    {
        if(!$this->request){
            if (is_cli()) {
                $this->request = Services::request();  // Manually get the request service
                $this->request->setMethod("post");
                $this->request->setGlobal('post', $_REQUEST);
            } 
        }
        $data =(array)null;
        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $emp_mstr = $Session->get("emp_details");
        $login_emp_details_id = $emp_mstr["id"];
        $sender_emp_details_id = $login_emp_details_id;
        $basic_details_dt = $this->model_view_saf_dtl->getSafDtlByMD5SafDtlId($saf_dtl_id);
        $data = $basic_details_dt;
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $data['previous_prop_dtl']=$this->model_prop_dtl->getholdingnobysafid($data['saf_dtl_id']);

        $data['saf_owner_detail'] = $this->model_saf_owner_detail->getOwnerdtlBySAFId(['saf_dtl_id'=> $data['saf_dtl_id']]);

        // applicant img & document
        foreach($data['saf_owner_detail'] as $key => $owner_detail)
        {
            $input = [
                'saf_dtl_id'=> $data['saf_dtl_id'],
                'saf_owner_dtl_id'=> $owner_detail['id'],
            ];
            $data['saf_owner_detail'][$key]['applicant_img_dtl'] = $this->model_saf_doc_dtl->getApplicantImgBySafDtlAndSafOwnerDtlId($input);
            $data['saf_owner_detail'][$key]['applicant_doc_dtl'] = $this->model_saf_doc_dtl->getApplicantDocBySafDtlAndSafOwnerDtlId($input);
        }
        $data['saf_tax_list'] = $this->model_saf_tax->getSafTaxDtlBySafDtlId(['saf_dtl_id'=> $data['saf_dtl_id']]);
        $data['document_list'] = $this->model_saf_doc_dtl->getAllVerifiedDocuments(['saf_dtl_id'=> $data['saf_dtl_id']]);
        $data['latest_document_list'] = $this->model_saf_doc_dtl->getLatestUploadedDocuments(['saf_dtl_id'=> $data['saf_dtl_id']]);
        $data['form'] = $this->model_level_pending_dtl->getLastRecord(['saf_dtl_id'=> $data['saf_dtl_id'], 'receiver_user_type_id'=> 9]);//Property Section Incharge

        //print_var($data['document_list']);
        if($this->request->getMethod()=='post')
        {
            $inputs = arrFilterSanitizeString($this->request->getVar());

            if (isset($inputs["btn_verify"])) {
                $input = [
                    "id" => $inputs["saf_doc_dtl_id"],
                    "verify_status" => 1, //Verified
                    "remarks" => "Verified",
                    "verified_by_emp_id" => $login_emp_details_id,
                ];

                $this->model_saf_doc_dtl->verifyDocument($input);
                return $this->response->redirect(base_url("si_saf/view/" . $saf_dtl_id) . "#documen");
            }

            if (isset($inputs["btn_reject"])) {
                $input = [
                    "id" => $inputs["saf_doc_dtl_id"],
                    "verify_status" => 2, //Rejected
                    "remarks" => $inputs["remarks"],
                    "verified_by_emp_id" => $login_emp_details_id,
                ];

                $this->model_saf_doc_dtl->verifyDocument($input);
                return $this->response->redirect(base_url("si_saf/view/" . $saf_dtl_id) . "#documen");
            }

            if(isset($inputs['btn_backward_submit']))
            {
                $level_data=[
                    'level_pending_dtl_id'=> md5($data['form']['id']),
                    'remarks' => $inputs['remarks'],
                    'verification_status'=> 1,
                    'status'=> 0,
                    'sender_emp_details_id' => $sender_emp_details_id,
                    'receiver_emp_details_id' => $sender_emp_details_id
                    ];
                if($updatebackward = $this->model_level_pending_dtl->updatelevelpendingById($level_data))
                {
                    $level_data = [
                        'remarks' => $inputs['remarks'],
                        'level_pending_dtl_id' => $data['form']['id'],
                        'saf_dtl_id' => $data['saf_dtl_id'],
                        'emp_details_id' => $login_emp_details_id,
                        'created_on' =>date('Y-m-d H:i:s'),
                        'forward_date' =>date('Y-m-d'),
                        'forward_time' =>date('H:i:s'),
                        'sender_user_type_id' => 9,//Property Section Incharge
                        'receiver_user_type_id'=> 7,//ULB Tax Collector
                        'verification_status'=> 0,
                        'status'=> 1,
                        'sender_emp_details_id' => $sender_emp_details_id
                    ];
					$this->model_level_pending_dtl->bugfix_level_pending($level_data); //Shashi
                    if($insertbackward = $this->model_level_pending_dtl->insrtlevelpendingdtl($level_data))
                    {
                        flashToast("message", "Application sent back to ULB TC");
                        return $this->response->redirect(base_url('SI_SAF/inbox_list_new/'));
                    }
                }
            }

            if(isset($inputs['btn_back_to_citizen_submit']))
            {

                $data = [

                        'id' =>$data['form']['id'],
                        'saf_dtl_id' => $data['saf_dtl_id'],
                        'remarks' => $inputs['remarks'],
                        'sender_user_type_id' => 9,//Property Section Incharge
                        'receiver_user_type_id' => 11,//Back Office
                        'sender_emp_details_id' => $sender_emp_details_id
                    ];
                if($updatebacktocitizen = $this->model_level_pending_dtl->sendBackToCitizen($data))
                {

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
                    'sender_user_type_id' => 9,     //Propert Dealing Assisstant
                    'sender_emp_details_id' => $data["sender_emp_details_id"],
                    'sender_ip_address' => $ip_address,
                    'remarks' => $data["remarks"],
                    'status' => 1

                ];
				
				$bub_fix = [
                        'saf_dtl_id' => $data["saf_dtl_id"],
                        'sender_user_type_id' => 9,
                        'receiver_user_type_id' => 11,
                        'forward_date' => "NOW()",
                        'forward_time' => "NOW()",
                        'created_on' => "NOW()",
                        'status' => 1,
                        'remarks' => $data["remarks"],
                        'sender_emp_details_id' => $sender_emp_details_id
            
                    ];
                $this->model_level_pending_dtl->bugfix_level_pending($bub_fix); //Shashi

                $this->model_level_pending_dtl->record_back_to_citizen($level_record);

                    $this->model_saf_dtl->update_saf_pending_status(["saf_dtl_id"=> $data['saf_dtl_id'], "doc_verify_status"=> 2]);//backtocitizen
                    flashToast("message", "Applicationn sent back to citizen successfully.");
                    return $this->response->redirect(base_url('si_saf/inbox_list_new'));
                }
            }

            if(isset($inputs['btn_verify_submit']))
            {
                if ($basic_details_dt["assessment_type"]=="Reassessment") {
                    $safHelper = new SAFHelper($this->db);
                    
                    //$this->db->transBegin();
                    if ($FV_Saf_Dtl = $this->model_field_verification_dtl->getUlbDataBySafDtlId($input)) {
                        $remarksBYSI = $inputs['remarks'];
                        $inputs = $FV_Saf_Dtl;
                        // FAM GENERATTION in case if Reassessment
                        $memo = $this->model_saf_memo_dtl->generate_assessment_final_memo($inputs['saf_dtl_id'], $login_emp_details_id);
                        $genmemo_last_id=$memo["generate_assessment_final_memo"];
                        // END FAM GENERATTION in case if Reassessment
                        // End Different Calculation
                        

                        $input = ['saf_dtl_id' => $inputs['saf_dtl_id']];
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
                            if ($FV_Saf_floor_Dtl = $this->model_field_verification_floor_details->getUlbDataBySafDtlId($inputs)) {
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

                            $safTaxDtl = $this->model_saf_tax->getJoinBySafDtlIdMd5(md5($inputs["saf_dtl_id"]));
                            if ($diffTax = $safHelper->calcDiffPanelty($safTaxDtl, $newSafTaxDtl)) {
                                $this->model_prop_tax->updateSafDeactiveStatus(['prop_dtl_id'=>$prop_dtl_id]);
                                $safHelper->calDiffSafDemand($newSafTaxDtl, $inputs["saf_dtl_id"], $prop_dtl_id);
                            }
                        }
                        // End Different Calculation
                        $level_data = [
                            "level_pending_dtl_id"=> md5($data["form"]["id"]),
                            "status"=> 0,
                            "verification_status"=> 1,
                            'sender_emp_details_id' => $sender_emp_details_id,
                            'receiver_emp_details_id' => $sender_emp_details_id
                        ];
                        
                        if($this->model_level_pending_dtl->updatelevelpendingById($level_data)) {
                            $checkAgencyData = $this->model_field_verification_dtl->getulbdatabysafid($data['saf_dtl_id'], 'AGENCY TC');
                            $recv_user_type_id=5;
                            $status=1;
                            $vstatus=0;
                            if($checkAgencyData){
                                $recv_user_type_id=9;
                                $status=0;
                                $vstatus=1;
                            }
                            $level_data = [
                                'remarks' => $remarksBYSI,
                                'saf_dtl_id' => $data['saf_dtl_id'],
                                'emp_details_id' => $login_emp_details_id,
                                'created_on' => "NOW()",
                                'forward_date' => "NOW()",
                                'forward_time' => "NOW()",
                                'sender_user_type_id' => 9,//Property Section Incharge
                                'receiver_user_type_id'=> $recv_user_type_id,//Agency Tc
                                'verification_status'=> $vstatus,
                                'status'=> $status,
                                'sender_emp_details_id' => $login_emp_details_id,
                                'receiver_emp_details_id' => $login_emp_details_id
                            ];
							
							$this->model_level_pending_dtl->bugfix_level_pending_new($level_data); //Shashi

                            if($this->model_level_pending_dtl->insrtSILevelFinaldtl($level_data)) {
                                $inputData = [
                                    "saf_dtl_id"=>$data['saf_dtl_id'],
                                    "doc_verify_status"=>1
                                ];
                                $this->model_saf_dtl->update_saf_pending_status($inputData);
                                $this->db->transCommit();
                                if($this->request->getVar("cmd")){
                                    return true;
                                }
                                flashToast("message", "FAM generated successfully.");
                                return $this->response->redirect(base_url('SI_SAF/inbox_list_new'));
                            }
                        }
                    }
                } else if ($basic_details_dt["assessment_type"]!="Reassessment") {
                    $level_data=[
                        "level_pending_dtl_id"=> md5($data["form"]["id"]),
                        "status"=> 0,
                        "verification_status"=> 1,
                        'sender_emp_details_id' => $sender_emp_details_id,
                        'receiver_emp_details_id' => $sender_emp_details_id
                    ];
                    
                    if($this->model_level_pending_dtl->updatelevelpendingById($level_data))
                    {
                        $level_data = [
                            'remarks' => $inputs['remarks'],
                            'saf_dtl_id' => $data['saf_dtl_id'],
                            'emp_details_id' => $login_emp_details_id,
                            'created_on' => "NOW()",
                            'forward_date' => "NOW()",
                            'forward_time' => "NOW()",
                            'sender_user_type_id' => 9,//Property Section Incharge
                            'receiver_user_type_id'=> 10,//Executive Officer
                            'verification_status'=> 0,
                            'status'=> 1,
                            'sender_emp_details_id' => $login_emp_details_id
                        ];
						$this->model_level_pending_dtl->bugfix_level_pending($level_data); //Shashi
                        if($this->model_level_pending_dtl->insrtlevelpendingdtl($level_data))
                        {
                            if($this->request->getVar("cmd")){
                                return true;
                            }
                            flashToast("message", "Application forwarded to exective officer");
                            return $this->response->redirect(base_url('SI_SAF/inbox_list_new'));
                        }
                    }
                }
            }

            if(isset($inputs['btn_reject_submit']))
            {
                $level_data = [
                    "level_pending_dtl_id"=> md5($data["form"]["id"]),
                    "status"=> 0,
                    "verification_status"=> 1,
                    'sender_emp_details_id' => $sender_emp_details_id,
                    'receiver_emp_details_id' => $sender_emp_details_id
                ];
                
                if($this->model_level_pending_dtl->updatelevelpendingById($level_data)) {
                    
                    $level_data = [
                        'remarks' => $inputs['remarks'],
                        'saf_dtl_id' => $data['saf_dtl_id'],
                        'emp_details_id' => $login_emp_details_id,
                        'created_on' => "NOW()",
                        'forward_date' => "NOW()",
                        'forward_time' => "NOW()",
                        'sender_user_type_id' => 9,//Property Section Incharge
                        'receiver_user_type_id'=> 9,//Agency Tc
                        'verification_status'=> 1,
                        'status'=> 0,
                        'sender_emp_details_id' => $login_emp_details_id,
                        'receiver_emp_details_id' => $login_emp_details_id
                    ];
                    
                    $this->model_level_pending_dtl->bugfix_level_pending_new($level_data); //Shashi

                    if($this->model_level_pending_dtl->insrtSILevelFinaldtl($level_data)) {

                        $deactivate_data = [
                            'prop_dtl_id' => $data['saf_dtl_id'],
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
                            $this->model_saf_dtl->updateSafDtlStatus($data['saf_dtl_id']);
                            flashToast("message", "Application Rejected");
                            return $this->response->redirect(base_url('SI_SAF/inbox_list_new'));
                        }
                        
                    }
                }
            }


        }
        else
        {
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
            if ($fieldResult = $this->model_field_verification_dtl->getFieldDtlBYSAFId($basic_details_dt["saf_dtl_id"])) {
                $data['verification'] = $fieldResult;
            }
            $data['level']=$this->model_level_pending_dtl->getAllLevelDtl($basic_details_dt["saf_dtl_id"]);
            // print_var($data);
            // return;
            return view('property/saf/si_saf_view', $data);
        }
    }

    public function gb_index() {
        $session = session();
        $ulb_mstr = $session->get("ulb_dtl");
        $emp_mstr = $session->get("emp_details");
        $user_type_mstr_id = $emp_mstr["user_type_mstr_id"];
        $login_emp_details_id = $emp_mstr["id"];

        $data = arrFilterSanitizeString($this->request->getVar());
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];

        $data['wardList'] = $this->model_view_ward_permission->getPermittedWardWithSession($login_emp_details_id);
        $permittedWard = array_column($data['wardList'], 'ward_mstr_id');
        $whereWard = " AND tbl_govt_saf_dtl.ward_mstr_id IN (".implode(',', $permittedWard).")";
        $whereSearchPrm = "";
        if (isset($data["ward_mstr_id"]) && isset($data["search_param"])) {
            if ($data["ward_mstr_id"]!="") {
                $whereWard = " AND tbl_govt_saf_dtl.ward_mstr_id IN (".$data["ward_mstr_id"].")";
            }
            if ($data["search_param"]!="") {
                $whereSearchPrm = " AND (tbl_govt_saf_dtl.application_no ILIKE '".$data["search_param"]."'
                                        OR tbl_govt_saf_dtl.office_name ILIKE '%".$data["search_param"]."%'
                                        OR tbl_govt_saf_dtl.building_colony_name ILIKE '%".$data["search_param"]."%')";
            }
        }

        $sql = "SELECT tbl_govt_saf_dtl.id,
                    tbl_govt_saf_dtl.application_no,
                    view_ward_mstr.ward_no,
                    tbl_govt_saf_dtl.building_colony_name,
                    tbl_govt_saf_dtl.office_name,
                    tbl_govt_saf_dtl.address,
                    tbl_govt_saf_dtl.ip_address,
                    tbl_govt_saf_dtl.building_colony_address
                FROM tbl_govt_saf_level_pending_dtl
                JOIN tbl_govt_saf_dtl ON tbl_govt_saf_level_pending_dtl.govt_saf_dtl_id::BIGINT=tbl_govt_saf_dtl.id ".$whereWard."
                JOIN view_ward_mstr ON view_ward_mstr.id = tbl_govt_saf_dtl.ward_mstr_id
                WHERE tbl_govt_saf_level_pending_dtl.receiver_user_type_id=".$user_type_mstr_id."
                AND tbl_govt_saf_level_pending_dtl.verification_status=0".$whereSearchPrm;

        $result = $this->model_datatable->getDatatable($sql);
        $data['inboxList'] = isset($result['result']) ? $result['result'] : null;
        $data['pager'] = $result['count'];
        return view('property/saf/si_gb_saf_list', $data);
    }

    public function gb_outbox() {
        $session = session();
        $ulb_mstr = $session->get("ulb_dtl");
        $emp_mstr = $session->get("emp_details");
        $user_type_mstr_id = $emp_mstr["user_type_mstr_id"];
        $login_emp_details_id = $emp_mstr["id"];
        
        if($emp_mstr['id']!="2" && $emp_mstr['id']!="1"){
            return redirect()->to('/home');
        }
        $data = arrFilterSanitizeString($this->request->getVar());
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];

        $data['wardList'] = $this->model_view_ward_permission->getPermittedWardWithSession($login_emp_details_id);
        $permittedWard = array_column($data['wardList'], 'ward_mstr_id');
        $whereWard = " AND tbl_govt_saf_dtl.ward_mstr_id IN (".implode(',', $permittedWard).")";
        $whereSearchPrm = "";
        if (isset($data["ward_mstr_id"]) && isset($data["search_param"])) {
            if ($data["ward_mstr_id"]!="") {
                $whereWard = " AND tbl_govt_saf_dtl.ward_mstr_id IN (".$data["ward_mstr_id"].")";
            }
            if ($data["search_param"]!="") {
                $whereSearchPrm = " AND (tbl_govt_saf_dtl.application_no ILIKE '".$data["search_param"]."'
                                        OR tbl_govt_saf_dtl.office_name ILIKE '%".$data["search_param"]."%'
                                        OR tbl_govt_saf_dtl.building_colony_name ILIKE '%".$data["search_param"]."%')";
            }
        }

        $sql = "SELECT tbl_govt_saf_dtl.id,
                    tbl_govt_saf_dtl.application_no,
                    view_ward_mstr.ward_no,
                    tbl_govt_saf_dtl.building_colony_name,
                    tbl_govt_saf_dtl.office_name,
                    tbl_govt_saf_dtl.address,
                    tbl_govt_saf_dtl.ip_address,
                    tbl_govt_saf_dtl.building_colony_address,
                    tbl_govt_saf_level_pending_dtl.forward_date
                FROM tbl_govt_saf_level_pending_dtl
                JOIN tbl_govt_saf_dtl ON tbl_govt_saf_level_pending_dtl.govt_saf_dtl_id::BIGINT=tbl_govt_saf_dtl.id ".$whereWard."
                JOIN view_ward_mstr ON view_ward_mstr.id = tbl_govt_saf_dtl.ward_mstr_id
                WHERE tbl_govt_saf_level_pending_dtl.sender_user_type_id=".$user_type_mstr_id."
                AND tbl_govt_saf_level_pending_dtl.verification_status=1".$whereSearchPrm;


        $result = $this->model_datatable->getDatatable($sql);
        //print_var($result);
        //die();
        $data['inboxList'] = isset($result['result']) ? $result['result'] : null;
        $data['pager'] = $result['count'];
        return view('property/saf/si_gb_saf_outbox_list', $data);
    }

    /* public function gb_index()
    {
        $data =(array)null;
        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
        $login_emp_details_id = $emp_mstr["id"];

        $wardList = $this->model_view_ward_permission->getPermittedWard($login_emp_details_id);
        $data['wardList'] = $wardList;

        $receiver_emp_details_id = $emp_mstr["user_type_mstr_id"];
        //print_r($ward_permission);
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
                $where = "ward_mstr_id=".$data['ward_mstr_id']." AND date(created_on) >='".$data['from_date']."'
                AND date(created_on) <='".$data['to_date']."' AND receiver_user_type_id='".$receiver_emp_details_id."'
                AND verification_status='0' and level_pending_status=1 ORDER BY id DESC";
            }
            else
            {
                $where = "ward_mstr_id in (".implode(",",$ward).") AND date(created_on) >='".$data['from_date']."'
                AND date(created_on) <='".$data['to_date']."' AND receiver_user_type_id='".$receiver_emp_details_id."'
                AND verification_status='0' and level_pending_status=1 ORDER BY id DESC";
            }
        }
        else
        {
            $data['from_date'] = date('Y-m-d');
            $data['to_date'] = date('Y-m-d');
            $data['ward_mstr_id'] = NULL;
            $where = "ward_mstr_id in (".implode(",",$ward).") AND date(created_on) >='".$data['from_date']."'
            AND date(created_on) <='".$data['to_date']."' AND receiver_user_type_id='".$receiver_emp_details_id."'
            AND verification_status='0' and level_pending_status=1 ORDER BY id DESC";
        }

        $Session->set('wardList', $wardList);
        $Session->set('ward_mstr_id', $data['ward_mstr_id']);
        $Session->set('from_date', $data['from_date']);
        $Session->set('to_date', $data['to_date']);

        $Session->set('where', $where);
        return $this->response->redirect(base_url('SI_SAF/gb_saf_inbox_list/'));
    } */

    public function gb_saf_inbox_list()
    {
        $data =(array)null;
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
        return view('property/saf/si_gb_saf_list', $data);

    }

    function verifyGBSaf($govt_saf_dtl_id)
    {
        //echo $govt_saf_dtl_id;
        $Session = Session();
        $emp_mstr = $Session->get("emp_details");
        $login_emp_details_id = $emp_mstr["id"];

        $data =(array)null;
        $data['application_detail']=$this->model_g_saf->getApplicationDetailbyId($govt_saf_dtl_id);
        $data['owner_details']=$this->model_govt_saf_officer_dtl->getOwnerDetails($govt_saf_dtl_id);
        //$data['owner_details']=$this->model_govt_saf_officer_dtl->getOwnerDetails($govt_saf_dtl_id);
        $data['doc_details']=$this->model_govt_doc_dtl->getAllDocuments($govt_saf_dtl_id);
        $data['tax_list'] = $this->model_govt_saf_tax_dtl->tax_list($data['application_detail']['id']);

        if($this->request->getMethod()=='post')
        {
            # Reject/Verify Document
            if(isset($_POST['btn_reject']) || isset($_POST['btn_verify']))
            {
                if(isset($_POST['btn_reject']))
                $verify_status=2; //2 rejected

                if(isset($_POST['btn_verify']))
                $verify_status=1; // 1 Verified

                $rules=['rejectedremarks'=>'required', ];

                if(!$this->validate($rules))
                {
                    $data['validation']=$this->validator;
                    return view('trade/Connection/trade_da_view', $data);
                }
                else
                {
                    $doc_dtl_id=$_POST['doc_dtl_id'];
                    $inputs=array(
                                    'verify_status'=> $verify_status,
                                    'remarks'=> $_POST['rejectedremarks'],
                                    'verified_by_emp_details_id'=> $login_emp_details_id,
                                );
                    $this->model_govt_doc_dtl->VerifyDocument($doc_dtl_id, $inputs);

                    if($verify_status==1)
                    $massage="Document Verified!!!";
                    else
                    $massage="Document Rejected!!!";

                    flashToast('message', $massage);
                    return $this->response->redirect(base_url('SI_SAF/verifyGBSaf/'.$govt_saf_dtl_id));
                }
            }

            # Back to Citizen
            if(isset($_POST['send_application']) && $_POST['send_application']=="Back to citizen")
            {
                $levelPending=$this->model_govt_saf_level_pending_dtl->getLastRecord($govt_saf_dtl_id);
                // Update Last Record Level Table
                $level=array(
                        "verification_status"=> 2,
                        "status"=> 0,
                        'msg_body' => $_POST['remarks'],
                    );
                $this->model_govt_saf_level_pending_dtl->UpdateLevelTable($levelPending['govt_saf_dtl_id'], $level);


                // Insert In level Table for Back to citizen
                $level_array=array();
                $level_array['govt_saf_dtl_id']= $data['application_detail']['id'];
                $level_array['sender_user_type_id']= 9;//section Incharge
                $level_array['sender_emp_details_id']= $login_emp_details_id;
                $level_array['receiver_user_type_id']= 11;// backoffice
                $level_array['verification_status']= 2;// back to citizen
                $level_array['msg_body']= $_POST['remarks'];
                $level_array['created_on']=date('Y-m-d H:i:s');

                $this->model_govt_saf_level_pending_dtl->insertData($level_array);

                flashToast('message', 'Application sent back to citizen successfully');
                return $this->response->redirect(base_url('SI_SAF/gb_index'));
            }

            # Forward
            if(isset($_POST['send_application']) && $_POST['send_application']=="Forward")
            {
                $levelPending=$this->model_govt_saf_level_pending_dtl->getLastRecord($govt_saf_dtl_id);
                // Update Last Record Level Table
                $level=array(
                        "verification_status"=> 1,
                        "status"=> 0,
                        'msg_body' => $_POST['remarks'],
                    );
                $this->model_govt_saf_level_pending_dtl->UpdateLevelTable($levelPending['govt_saf_dtl_id'], $level);


                // Insert In level Table for Back to citizen
                $level_array=array();
                $level_array['govt_saf_dtl_id']= $data['application_detail']['id'];
                $level_array['sender_user_type_id']= 9;//section Incharge
                $level_array['sender_emp_details_id']= $login_emp_details_id;
                $level_array['receiver_user_type_id']= 10;// Property Executive Officer
                $level_array['verification_status']= 0;// back to citizen
                $level_array['msg_body']= $_POST['remarks'];
                $level_array['created_on']=date('Y-m-d H:i:s');

                $this->model_govt_saf_level_pending_dtl->insertData($level_array);

                flashToast('message', 'Application forwarded to exective officer successfully');
                return $this->response->redirect(base_url('SI_SAF/gb_index'));
            }

        }
        //print_var($data['application_detail']);
        return view('property/gsaf/verifyGBSaf', $data);
    }
}