<?php

namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_ward_mstr;
use App\Models\model_ulb_mstr;
use App\Models\model_field_verification_dtl;
use App\Models\model_field_verification_floor_details;
use App\Models\model_saf_dtl;
use App\Models\model_saf_owner_detail;
use App\Models\model_saf_floor_details;
use App\Models\model_view_saf_floor_details;
use App\Models\model_saf_tax;
use App\Models\model_prop_type_mstr;
use App\Models\model_road_type_mstr;
use App\Models\model_usage_type_mstr;
use App\Models\model_const_type_mstr;
use App\Models\model_occupancy_type_mstr;
use App\Models\model_floor_mstr;
use App\Models\model_level_pending_dtl;
use App\Models\model_saf_geotag_upload_dtl;
use App\Models\model_ward_permission;
use App\Models\model_view_ward_permission;
use App\Models\model_datatable;
use App\Models\ObjectionModel;
use App\Models\model_prop_dtl;
use App\Models\model_prop_owner_detail;
use App\Models\model_transfer_mode_mstr;
use App\Models\PropertyTypeModel;
use App\Models\model_view_ward_mapping_mstr;

use App\Models\model_visiting_dtl;

class SafVerification extends MobiController
{
    protected $db;
    protected $dbSystem;
    protected $model_ward_mstr;
    protected $model_ward_permission;
    protected $model_view_ward_permission;
    protected $model_transfer_mode_mstr;
    protected $model_prop_type_mstr;
    protected $model_field_verification_dtl;
    protected $model_field_verification_floor_details;
    protected $model_saf_owner_detail;
    protected $model_saf_dtl;
    protected $model_saf_tax;
    protected $model_saf_floor_details;
    protected $model_road_type_mstr;
    protected $model_usage_type_mstr;
    protected $model_const_type_mstr;
    protected $model_occupancy_type_mstr;
    protected $model_floor_mstr;
    protected $model_view_saf_floor_details;
    protected $model_level_pending_dtl;
    protected $model_saf_geotag_upload_dtl;
    protected $model_ulb_mstr;
	protected $model_datatable;
    protected $ObjectionModel;
    protected $model_prop_dtl;
    protected $model_prop_owner_detail;
    protected $PropertyTypeModel;
    protected $model_view_ward_mapping_mstr;
    protected $model_visiting_dtl;

    public function __construct()
    {
        /* ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL); */
        parent::__construct();

        helper(['db_helper', 'geotagging_helper', 'utility_helper','form_helper']);
        if ($db_name = dbConfig("property")) {
            $this->db = db_connect($db_name);
        }
        if ($db_system = dbSystem()) {
            $this->dbSystem = db_connect($db_system);
        }

        $this->model_ward_mstr = new model_ward_mstr($this->dbSystem);
        $this->model_ward_permission = new model_ward_permission($this->dbSystem);
		$this->model_view_ward_permission = new model_view_ward_permission($this->dbSystem);
        $this->model_transfer_mode_mstr = new model_transfer_mode_mstr($this->db);
        $this->model_prop_type_mstr = new model_prop_type_mstr($this->db);
        $this->model_field_verification_dtl = new model_field_verification_dtl($this->db);
        $this->model_field_verification_floor_details = new model_field_verification_floor_details($this->db);
        $this->model_saf_owner_detail = new model_saf_owner_detail($this->db);
        $this->model_saf_dtl = new model_saf_dtl($this->db);
        $this->model_saf_tax = new model_saf_tax($this->db);
        $this->model_saf_floor_details = new model_saf_floor_details($this->db);
        $this->model_road_type_mstr = new model_road_type_mstr($this->db);
        $this->model_usage_type_mstr = new model_usage_type_mstr($this->db);
        $this->model_const_type_mstr = new model_const_type_mstr($this->db);
        $this->model_occupancy_type_mstr = new model_occupancy_type_mstr($this->db);
        $this->model_floor_mstr = new model_floor_mstr($this->db);
        $this->model_view_saf_floor_details = new model_view_saf_floor_details($this->db);
        $this->model_level_pending_dtl = new model_level_pending_dtl($this->db);
        $this->model_saf_geotag_upload_dtl = new model_saf_geotag_upload_dtl($this->db);
        $this->model_ulb_mstr = new model_ulb_mstr($this->dbSystem);
		$this->model_datatable = new model_datatable($this->db);
        $this->ObjectionModel = new ObjectionModel($this->db);
        $this->model_prop_dtl = new model_prop_dtl($this->db);
        $this->model_prop_owner_detail = new model_prop_owner_detail($this->db);
        $this->PropertyTypeModel = new PropertyTypeModel($this->db);
        $this->model_view_ward_mapping_mstr = new model_view_ward_mapping_mstr($this->dbSystem);

        $this->model_visiting_dtl = new model_visiting_dtl($this->dbSystem);
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
        $emp_details_id = $emp_mstr["id"];
        $user_type_mstr_id = $emp_mstr["user_type_mstr_id"];

        $empward = $this->model_ward_permission->getWardDataByEmpdetailsId($emp_details_id);

        if ($this->request->getMethod() == 'post')
        {
            $data["fromdate"] = $this->request->getVar('fromdate');
            $data["todate"] = $this->request->getVar('todate');
            $data["keyword"] = $this->request->getVar('keyword');

            $inputs = [
                'fromdate' => $this->request->getVar('fromdate'),
                'todate' => $this->request->getVar('todate'),
                'keyword' => $this->request->getVar('keyword')
            ];

        }
        else
        {
            $data["fromdate"] = date('Y-m-d');
            $data["todate"] = date('Y-m-d');
            $data["keyword"] = '';
            $inputs = [
                'fromdate' => date('Y-m-d'),
                'todate' => date('Y-m-d'),
                'keyword' => ''
            ];
        }
		$Session->set('inputs', $inputs);
		return $this->response->redirect(base_url('SafVerification/field_verification_list/'));
    }

    public function atc_missing_geotag()
    {
        $data = (array)null;
        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
        $emp_details_id = $emp_mstr["id"];
        $user_type_mstr_id = $emp_mstr["user_type_mstr_id"];

        $data = arrFilterSanitizeString($this->request->getVar());
        //$empward = $this->model_ward_permission->getWardDataByEmpdetailsId($emp_details_id);
        $data['wardList'] = $this->model_view_ward_permission->getPermittedWard($emp_details_id);
        $permittedWard = array_column($data['wardList'], 'ward_mstr_id');
        $whereWard = " AND tbl_saf_dtl.ward_mstr_id IN (".implode(',', $permittedWard).")";

        $whereSearchPrm = "";
        if (isset($data["ward_mstr_id"]) && isset($data["search_param"])) {
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
                    tbl_level_pending_dtl.id AS level_pending_dtl_id,
                    tbl_level_pending_dtl.forward_date,
                    tbl_prop_type_mstr.property_type,
                    view_ward_mstr.ward_no,
                    tbl_saf_dtl.id AS saf_dtl_id,
                    tbl_saf_dtl.assessment_type,
                    tbl_saf_dtl.apply_date,
                    tbl_saf_dtl.saf_no,
                    tbl_saf_dtl.prop_address,
                    owner_dtl.owner_name,
                    owner_dtl.mobile_no
                FROM tbl_level_pending_dtl
                INNER JOIN tbl_saf_dtl ON tbl_saf_dtl.id = tbl_level_pending_dtl.saf_dtl_id ".$whereWard."
                INNER JOIN (
                    SELECT
                        saf_dtl_id,
                        STRING_AGG(owner_name, ', ') owner_name,
                        STRING_AGG(mobile_no::TEXT, ', ') mobile_no
                    FROM tbl_saf_owner_detail
                        WHERE status=1
                    GROUP BY saf_dtl_id
                ) owner_dtl ON owner_dtl.saf_dtl_id = tbl_saf_dtl.id
                INNER JOIN view_ward_mstr ON view_ward_mstr.id=tbl_saf_dtl.ward_mstr_id
                INNER JOIN tbl_prop_type_mstr ON tbl_prop_type_mstr.id = tbl_saf_dtl.prop_type_mstr_id
                LEFT JOIN (
                    SELECT
                        geotag_dtl_id
                    FROM tbl_saf_geotag_upload_dtl
                    WHERE status=1
                    GROUP BY geotag_dtl_id
                ) geotag_dtl ON geotag_dtl.geotag_dtl_id = tbl_saf_dtl.id
                WHERE
                    tbl_level_pending_dtl.sender_user_type_id=5
                    AND tbl_level_pending_dtl.verification_status=0
                    AND geotag_dtl.geotag_dtl_id IS NULL ".$whereSearchPrm."";
        if ($result = $this->model_datatable->getDatatable($sql)) {
            $data['posts'] = $result['result'];
            $data['leveldetails'] = $data['posts'];
            $data['pager'] = $result['count'];
        }

        return view('mobile/Property/FieldVerification/missingGeotagSearchSafATC', $data);
    }

    public function atc_field_verification_list2()
    {
        $data = (array)null;
        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
        $emp_details_id = $emp_mstr["id"];
        $user_type_mstr_id = $emp_mstr["user_type_mstr_id"];


        $data = arrFilterSanitizeString($this->request->getVar());
        //$empward = $this->model_ward_permission->getWardDataByEmpdetailsId($emp_details_id);
        $data['wardList'] = $this->model_view_ward_permission->getPermittedWard($emp_details_id);
        $permittedWard = array_column($data['wardList'], 'ward_mstr_id');
        $whereDateRange = "";
        $whereWard = " AND tbl_saf_dtl.ward_mstr_id IN (".implode(',', $permittedWard).")";

        $whereSearchPrm = "";
        if (isset($data["from_date"]) && isset($data["upto_date"]) && isset($data["ward_mstr_id"]) && isset($data["search_param"])) {
            if ($data["from_date"]!="" && $data["upto_date"]!="") {
                $whereDateRange = " AND tbl_level_pending_dtl.forward_date BETWEEN '".$data["from_date"]."' AND '".$data["upto_date"]."'";
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
                    tbl_saf_dtl.prop_address,
                    tbl_saf_dtl.apply_date,
                    tbl_prop_dtl.new_holding_no,
                    tbl_level_pending_dtl.forward_date,
                    tbl_level_pending_dtl.forward_time,
                    tbl_level_pending_dtl.remarks
                FROM tbl_level_pending_dtl
                INNER JOIN tbl_saf_dtl ON tbl_level_pending_dtl.saf_dtl_id = tbl_saf_dtl.id ".$whereWard."
                INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.saf_dtl_id=tbl_saf_dtl.id
                INNER JOIN tbl_prop_type_mstr ON tbl_prop_type_mstr.id=tbl_saf_dtl.prop_type_mstr_id
                INNER JOIN ( SELECT tbl_saf_owner_detail.saf_dtl_id,
                        string_agg(tbl_saf_owner_detail.owner_name::text, ', '::text) AS owner_name,
                        string_agg(tbl_saf_owner_detail.mobile_no::text, ', '::text) AS mobile_no
                    FROM tbl_saf_owner_detail
                    GROUP BY tbl_saf_owner_detail.saf_dtl_id
                ) owner_dtl ON owner_dtl.saf_dtl_id = tbl_level_pending_dtl.saf_dtl_id
                INNER JOIN view_ward_mstr ON view_ward_mstr.id = tbl_saf_dtl.ward_mstr_id
                WHERE
                    receiver_user_type_id=".$user_type_mstr_id."
                    AND tbl_level_pending_dtl.verification_status='0'
                    AND doc_upload_status='1'
                    AND tbl_level_pending_dtl.status='1' ".$whereSearchPrm.$whereDateRange."
                ORDER BY tbl_level_pending_dtl.id DESC";



		//print_var($sql);
		$result = $this->model_datatable->getDatatable($sql);
		$data['posts'] = $result['result'];
		$data['leveldetails'] = $data['posts'];
		$data['pager'] = $result['count'];

        // print_var($data);
        // return;
        return view('mobile/Property/FieldVerification/fieldVerificationSearchSafATC', $data);
    }

    public function atc_field_verification_list()
    {
        $data = (array)null;
        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
        $emp_details_id = $emp_mstr["id"];
        $user_type_mstr_id = $emp_mstr["user_type_mstr_id"];


        $data = arrFilterSanitizeString($this->request->getVar());
        //$empward = $this->model_ward_permission->getWardDataByEmpdetailsId($emp_details_id);
        $show_data = limitInPagination();
        if(isset($_GET['page'])) {
            $page = intval($_GET['page'])-1;
            if($page<0) $page = 0;
        } else {
            $page = 0;
        }
        $start_page = $page*$show_data;

        $limitSql = " LIMIT $show_data OFFSET $start_page";

        $data['wardList'] = $this->model_view_ward_permission->getPermittedWard($emp_details_id);
        $permittedWard = array_column($data['wardList'], 'ward_mstr_id');
        $whereDateRange = "";
        $whereWard = " AND tbl_saf_dtl.ward_mstr_id IN (".implode(',', $permittedWard).")";

        $whereSearchPrm = "";
        if (isset($data["from_date"]) && isset($data["upto_date"]) && isset($data["ward_mstr_id"]) && isset($data["search_param"])) {
            if ($data["from_date"]!="" && $data["upto_date"]!="") {
                $whereDateRange = " AND tbl_level_pending_dtl.forward_date BETWEEN '".$data["from_date"]."' AND '".$data["upto_date"]."'";
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

        $sql = "WITH level_dtl AS (
                    SELECT
                        tbl_level_pending_dtl.id,
                        tbl_level_pending_dtl.saf_dtl_id,
                        tbl_saf_dtl.ward_mstr_id,
                        tbl_saf_dtl.saf_no,
                        tbl_saf_dtl.prop_type_mstr_id,
                        owner_dtl.owner_name,
                        owner_dtl.mobile_no,
                        tbl_saf_dtl.assessment_type,
                        tbl_saf_dtl.prop_address,
                        tbl_saf_dtl.apply_date,
                        tbl_level_pending_dtl.forward_date,
                        tbl_level_pending_dtl.forward_time,
                        tbl_level_pending_dtl.remarks
                    FROM tbl_level_pending_dtl
                    INNER JOIN tbl_saf_dtl ON tbl_level_pending_dtl.saf_dtl_id = tbl_saf_dtl.id AND tbl_saf_dtl.status=1 ".$whereWard."
                    INNER JOIN ( SELECT tbl_saf_owner_detail.saf_dtl_id,
                            string_agg(tbl_saf_owner_detail.owner_name::text, ', '::text) AS owner_name,
                            string_agg(tbl_saf_owner_detail.mobile_no::text, ', '::text) AS mobile_no
                        FROM tbl_saf_owner_detail
                        GROUP BY tbl_saf_owner_detail.saf_dtl_id
                    ) owner_dtl ON owner_dtl.saf_dtl_id = tbl_level_pending_dtl.saf_dtl_id
                    WHERE
                        receiver_user_type_id=".$user_type_mstr_id."
                        AND tbl_level_pending_dtl.verification_status='0'
                        AND doc_upload_status='1'
                        AND tbl_level_pending_dtl.status='1' ".$whereSearchPrm.$whereDateRange."
                    ORDER BY tbl_level_pending_dtl.id DESC ".$limitSql."
                )
                SELECT
                    level_dtl.*,
                    tbl_prop_type_mstr.property_type,
                    tbl_prop_dtl.new_holding_no,
                    view_ward_mstr.ward_no
                FROM level_dtl
                INNER JOIN view_ward_mstr ON view_ward_mstr.id = level_dtl.ward_mstr_id
                LEFT JOIN tbl_prop_dtl ON tbl_prop_dtl.saf_dtl_id=level_dtl.saf_dtl_id
                INNER JOIN tbl_prop_type_mstr ON tbl_prop_type_mstr.id=level_dtl.prop_type_mstr_id";
        //print_var($sql);
        $resultBuilder = $this->db->query($sql);

        $sql = "WITH level_dtl AS (
                    SELECT
                        COUNT(*) AS total_count
                    FROM tbl_level_pending_dtl
                    INNER JOIN tbl_saf_dtl ON tbl_level_pending_dtl.saf_dtl_id = tbl_saf_dtl.id ".$whereWard."
                    INNER JOIN ( SELECT tbl_saf_owner_detail.saf_dtl_id,
                            string_agg(tbl_saf_owner_detail.owner_name::text, ', '::text) AS owner_name,
                            string_agg(tbl_saf_owner_detail.mobile_no::text, ', '::text) AS mobile_no
                        FROM tbl_saf_owner_detail
                        GROUP BY tbl_saf_owner_detail.saf_dtl_id
                    ) owner_dtl ON owner_dtl.saf_dtl_id = tbl_level_pending_dtl.saf_dtl_id
                    WHERE
                        receiver_user_type_id=".$user_type_mstr_id."
                        AND tbl_level_pending_dtl.verification_status='0'
                        AND doc_upload_status='1'
                        AND tbl_level_pending_dtl.status='1' ".$whereSearchPrm.$whereDateRange."
                )
                SELECT
                    level_dtl.*
                FROM level_dtl";
        $builder = $this->db->query($sql);
        $total_count = $builder->getFirstRow("array")['total_count'];
        $result = [
            'result' => $resultBuilder->getResultArray(),
            'count' => $total_count,
            'offset' => $start_page,
        ];
		$data['posts'] = $result['result'];
		$data['leveldetails'] = $data['posts'];
		$data['pager'] = $result['count'];

        // print_var($data);
        // return;
        return view('mobile/Property/FieldVerification/fieldVerificationSearchSafATC', $data);
    }


	public function field_verification_list()
    {
        $data = (array)null;
        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
        $emp_details_id = $emp_mstr["id"];
        $user_type_mstr_id = $emp_mstr["user_type_mstr_id"];
        //$whereJoin = "INNER";
        $whereJoin = "LEFT";
        //if ($user_type_mstr_id==5) $whereJoin = "LEFT";
        

        $data = arrFilterSanitizeString($this->request->getVar());
        //$empward = $this->model_ward_permission->getWardDataByEmpdetailsId($emp_details_id);
        $data['wardList'] = $this->model_view_ward_permission->getPermittedWard($emp_details_id);
        //print_var($data['wardList']);
        $permittedWard = array_column($data['wardList'], 'ward_mstr_id');
        $whereDateRange = "";
        $whereWard = " AND tbl_saf_dtl.ward_mstr_id IN (".implode(',', $permittedWard).")";

        $whereSearchPrm = "";
        $with_where = "";        
        if (isset($data["from_date"]) && isset($data["upto_date"]) && isset($data["ward_mstr_id"]) && isset($data["search_param"])) 
        {
            if ($data["from_date"]!="" && $data["upto_date"]!="") {
                if(isset($data["date_type"]) && $data["date_type"]=='apply_date')
                {
                    $with_where .=  " AND tbl_saf_dtl.apply_date BETWEEN '".$data["from_date"]."' AND '".$data["upto_date"]."'";
                }
                else
                {                 
                    $whereDateRange = " AND tbl_level_pending_dtl.forward_date BETWEEN '".$data["from_date"]."' AND '".$data["upto_date"]."'";
                    $with_where .= $whereDateRange;
                }
            }
            if ($data["ward_mstr_id"]!="") {
                $whereWard = " AND tbl_saf_dtl.ward_mstr_id=".$data["ward_mstr_id"];
                $with_where .= $whereWard ;
            } else {
                $with_where .= $whereWard ;
            }
            if ($data["search_param"]!="") {
                $whereSearchPrm = " AND (tbl_saf_dtl.saf_no ILIKE '%".$data["search_param"]."%'
                                        OR owner_dtl.owner_name ILIKE '%".$data["search_param"]."%'
                                        OR owner_dtl.mobile_no ILIKE '%".$data["search_param"]."%')";
                $with_where .= $whereSearchPrm ;
                          
            }
        } else {
            $with_where .= $whereWard ;
        }

        // $sql = "SELECT
        //             tbl_level_pending_dtl.id,
        //             tbl_level_pending_dtl.saf_dtl_id,
        //             tbl_prop_type_mstr.property_type,
        //             view_ward_mstr.ward_no,
        //             tbl_saf_dtl.saf_no,
        //             owner_dtl.owner_name,
        //             owner_dtl.mobile_no,
        //             tbl_saf_dtl.assessment_type,
        //             tbl_saf_dtl.prop_address,
        //             tbl_saf_dtl.apply_date,
        //             tbl_prop_dtl.new_holding_no,
        //             tbl_level_pending_dtl.forward_date,
        //             tbl_level_pending_dtl.forward_time,
        //             tbl_level_pending_dtl.remarks,
        //             geotag_dtl.saf_geotag_dtl
        //         FROM tbl_level_pending_dtl
        //         INNER JOIN tbl_saf_dtl ON tbl_level_pending_dtl.saf_dtl_id = tbl_saf_dtl.id ".$whereWard."
        //         ".$whereJoin." JOIN (
        //             SELECT
        //                 geotag_dtl_id,
        //                 json_agg(json_build_object('direction_type', direction_type, 'image_path', image_path, 'latitude', latitude, 'longitude', longitude) ORDER BY direction_type) AS saf_geotag_dtl
        //             FROM tbl_saf_geotag_upload_dtl
        //             WHERE status=1
        //             GROUP BY geotag_dtl_id
        //         ) AS geotag_dtl ON geotag_dtl.geotag_dtl_id=tbl_saf_dtl.id
        //         LEFT JOIN tbl_prop_dtl ON tbl_prop_dtl.saf_dtl_id=tbl_saf_dtl.id
        //         INNER JOIN tbl_prop_type_mstr ON tbl_prop_type_mstr.id=tbl_saf_dtl.prop_type_mstr_id
        //         INNER JOIN ( SELECT tbl_saf_owner_detail.saf_dtl_id,
        //                 string_agg(tbl_saf_owner_detail.owner_name::text, ', '::text) AS owner_name,
        //                 string_agg(tbl_saf_owner_detail.mobile_no::text, ', '::text) AS mobile_no
        //             FROM tbl_saf_owner_detail
        //             GROUP BY tbl_saf_owner_detail.saf_dtl_id
        //         ) owner_dtl ON owner_dtl.saf_dtl_id = tbl_level_pending_dtl.saf_dtl_id
        //         INNER JOIN view_ward_mstr ON view_ward_mstr.id = tbl_saf_dtl.ward_mstr_id
        //         WHERE
        //             receiver_user_type_id=".$user_type_mstr_id."
        //             AND tbl_level_pending_dtl.verification_status='0'
        //             AND doc_upload_status='1'
        //             AND tbl_level_pending_dtl.status='1' ".$whereSearchPrm.$whereDateRange."
        //         ORDER BY tbl_level_pending_dtl.id DESC";



		// //print_var($sql);
		// $result = $this->model_datatable->getDatatable($sql);
		// $data['posts'] = $result['result'];
		// $data['leveldetails'] = $data['posts'];
		// $data['pager'] = $result['count'];

        $with= " SELECT
                    tbl_level_pending_dtl.id,
                    tbl_level_pending_dtl.saf_dtl_id,        
                    tbl_saf_dtl.saf_no,
                    tbl_saf_dtl.assessment_type,
                    tbl_saf_dtl.prop_address,
                    tbl_saf_dtl.apply_date,        
                    tbl_level_pending_dtl.forward_date,
                    tbl_level_pending_dtl.forward_time,
                    tbl_level_pending_dtl.remarks,
                    tbl_saf_dtl.prop_type_mstr_id,
                    tbl_saf_dtl.ward_mstr_id,
                    owner_dtl.owner_name,
                    owner_dtl.mobile_no
                from tbl_level_pending_dtl 
                INNER JOIN tbl_saf_dtl ON tbl_level_pending_dtl.saf_dtl_id = tbl_saf_dtl.id AND tbl_saf_dtl.status=1
                INNER JOIN ( 
                    SELECT tbl_saf_owner_detail.saf_dtl_id,
                        string_agg(tbl_saf_owner_detail.owner_name::text, ', '::text) AS owner_name,
                        string_agg(tbl_saf_owner_detail.mobile_no::text, ', '::text) AS mobile_no
                    FROM tbl_saf_owner_detail        
                    GROUP BY tbl_saf_owner_detail.saf_dtl_id
                ) owner_dtl ON owner_dtl.saf_dtl_id = tbl_level_pending_dtl.saf_dtl_id
                WHERE
                    receiver_user_type_id=$user_type_mstr_id
                    AND tbl_level_pending_dtl.verification_status='0'
                    AND doc_upload_status='1'
                    AND tbl_level_pending_dtl.status='1' 
                    AND tbl_saf_dtl.apply_date < '2022-08-05'
                    $with_where
        ";
        $aliase = " level_pending_dtl ";
        $select = "SELECT 
            tbl_level_pending_dtl.id,
            tbl_level_pending_dtl.saf_dtl_id,
            tbl_prop_type_mstr.property_type,
            view_ward_mstr.ward_no,
            tbl_level_pending_dtl.saf_no,
            tbl_level_pending_dtl.owner_name,
            tbl_level_pending_dtl.mobile_no,
            tbl_level_pending_dtl.assessment_type,
            tbl_level_pending_dtl.prop_address,
            tbl_level_pending_dtl.apply_date,
            tbl_prop_dtl.new_holding_no,
            tbl_level_pending_dtl.forward_date,
            tbl_level_pending_dtl.forward_time,
            tbl_level_pending_dtl.remarks,
            geotag_dtl.saf_geotag_dtl
        ";  
        $from =" tbl_level_pending_dtl
                $whereJoin JOIN (
                    SELECT
                        geotag_dtl_id,
                        json_agg(json_build_object('direction_type', direction_type, 'image_path', image_path, 'latitude', latitude, 'longitude', longitude) ORDER BY direction_type) AS saf_geotag_dtl
                    FROM tbl_saf_geotag_upload_dtl
                    INNER JOIN level_pending_dtl on level_pending_dtl.saf_dtl_id = tbl_saf_geotag_upload_dtl.geotag_dtl_id
                    WHERE status=1
                    GROUP BY geotag_dtl_id
                ) AS geotag_dtl ON geotag_dtl.geotag_dtl_id=tbl_level_pending_dtl.saf_dtl_id
                
                LEFT JOIN tbl_prop_dtl ON tbl_prop_dtl.saf_dtl_id=tbl_level_pending_dtl.saf_dtl_id
                INNER JOIN tbl_prop_type_mstr ON tbl_prop_type_mstr.id= tbl_level_pending_dtl.prop_type_mstr_id
                INNER JOIN view_ward_mstr ON view_ward_mstr.id = tbl_level_pending_dtl.ward_mstr_id
                ORDER BY tbl_level_pending_dtl.id DESC";
        $result = $this->model_datatable->single_with_limit($with,$aliase,$select,$from);
		$data['posts'] = $result['result'];
		$data['leveldetails'] = $data['posts'];
		$data['pager'] = $result['count'];
        $data['view']="field_verification_list";
        // print_var($data);
        // return;
        return view('mobile/Property/FieldVerification/fieldVerificationSearchSafTC', $data);
    }
    public function field_verification_list_new()
    {
        $data = (array)null;
        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
        $emp_details_id = $emp_mstr["id"];
        $user_type_mstr_id = $emp_mstr["user_type_mstr_id"];
        //$whereJoin = "INNER";
        $whereJoin = "LEFT";
        //if ($user_type_mstr_id==5) $whereJoin = "LEFT";
        

        $data = arrFilterSanitizeString($this->request->getVar());
        //$empward = $this->model_ward_permission->getWardDataByEmpdetailsId($emp_details_id);
        $data['wardList'] = $this->model_view_ward_permission->getPermittedWard($emp_details_id);
        //print_var($data['wardList']);
        $permittedWard = array_column($data['wardList'], 'ward_mstr_id');
        $whereDateRange = "";
        $whereWard = " AND tbl_saf_dtl.ward_mstr_id IN (".implode(',', $permittedWard).")";

        $whereSearchPrm = "";
        $with_where = "";        
        if (isset($data["from_date"]) && isset($data["upto_date"]) && isset($data["ward_mstr_id"]) && isset($data["search_param"])) 
        {
            if ($data["from_date"]!="" && $data["upto_date"]!="") {
                if(isset($data["date_type"]) && $data["date_type"]=='apply_date')
                {
                    $with_where .=  " AND tbl_saf_dtl.apply_date BETWEEN '".$data["from_date"]."' AND '".$data["upto_date"]."'";
                }
                else
                {                 
                    $whereDateRange = " AND tbl_level_pending_dtl.forward_date BETWEEN '".$data["from_date"]."' AND '".$data["upto_date"]."'";
                    $with_where .= $whereDateRange;
                }
            }
            if ($data["ward_mstr_id"]!="") {
                $whereWard = " AND tbl_saf_dtl.ward_mstr_id=".$data["ward_mstr_id"];
                $with_where .= $whereWard ;
            } else {
                $with_where .= $whereWard ;
            }
            if ($data["search_param"]!="") {
                $whereSearchPrm = " AND (tbl_saf_dtl.saf_no ILIKE '%".$data["search_param"]."%'
                                        OR owner_dtl.owner_name ILIKE '%".$data["search_param"]."%'
                                        OR owner_dtl.mobile_no ILIKE '%".$data["search_param"]."%')";
                $with_where .= $whereSearchPrm ;
                          
            }
        } else {
            $with_where .= $whereWard ;
        }
        $with= " SELECT
                    tbl_level_pending_dtl.id,
                    tbl_level_pending_dtl.saf_dtl_id,        
                    tbl_saf_dtl.saf_no,
                    tbl_saf_dtl.assessment_type,
                    tbl_saf_dtl.prop_address,
                    tbl_saf_dtl.apply_date,        
                    tbl_level_pending_dtl.forward_date,
                    tbl_level_pending_dtl.forward_time,
                    tbl_level_pending_dtl.remarks,
                    tbl_saf_dtl.prop_type_mstr_id,
                    tbl_saf_dtl.ward_mstr_id,
                    owner_dtl.owner_name,
                    owner_dtl.mobile_no
                from tbl_level_pending_dtl 
                INNER JOIN tbl_saf_dtl ON tbl_level_pending_dtl.saf_dtl_id = tbl_saf_dtl.id AND tbl_saf_dtl.status=1
                INNER JOIN ( 
                    SELECT tbl_saf_owner_detail.saf_dtl_id,
                        string_agg(tbl_saf_owner_detail.owner_name::text, ', '::text) AS owner_name,
                        string_agg(tbl_saf_owner_detail.mobile_no::text, ', '::text) AS mobile_no
                    FROM tbl_saf_owner_detail 
					WHERE tbl_saf_owner_detail.status=1 					
                    GROUP BY tbl_saf_owner_detail.saf_dtl_id
                ) owner_dtl ON owner_dtl.saf_dtl_id = tbl_level_pending_dtl.saf_dtl_id
                WHERE
                    receiver_user_type_id=$user_type_mstr_id
                    AND tbl_level_pending_dtl.verification_status='0'
                    AND doc_upload_status='1'
                    AND tbl_level_pending_dtl.status='1' 
                    AND tbl_saf_dtl.apply_date >='2022-08-05'
                    $with_where
        ";
        $aliase = " level_pending_dtl ";
        $select = "SELECT 
            tbl_level_pending_dtl.id,
            tbl_level_pending_dtl.saf_dtl_id,
            tbl_prop_type_mstr.property_type,
            view_ward_mstr.ward_no,
            tbl_level_pending_dtl.saf_no,
            tbl_level_pending_dtl.owner_name,
            tbl_level_pending_dtl.mobile_no,
            tbl_level_pending_dtl.assessment_type,
            tbl_level_pending_dtl.prop_address,
            tbl_level_pending_dtl.apply_date,
            tbl_prop_dtl.new_holding_no,
            tbl_level_pending_dtl.forward_date,
            tbl_level_pending_dtl.forward_time,
            tbl_level_pending_dtl.remarks,
            geotag_dtl.saf_geotag_dtl
        ";  
        $from =" tbl_level_pending_dtl
                $whereJoin JOIN (
                    SELECT
                        geotag_dtl_id,
                        json_agg(json_build_object('direction_type', direction_type, 'image_path', image_path, 'latitude', latitude, 'longitude', longitude) ORDER BY direction_type) AS saf_geotag_dtl
                    FROM tbl_saf_geotag_upload_dtl
                    INNER JOIN level_pending_dtl on level_pending_dtl.saf_dtl_id = tbl_saf_geotag_upload_dtl.geotag_dtl_id
                    WHERE status=1
                    GROUP BY geotag_dtl_id
                ) AS geotag_dtl ON geotag_dtl.geotag_dtl_id=tbl_level_pending_dtl.saf_dtl_id
                
                LEFT JOIN tbl_prop_dtl ON tbl_prop_dtl.saf_dtl_id=tbl_level_pending_dtl.saf_dtl_id
                INNER JOIN tbl_prop_type_mstr ON tbl_prop_type_mstr.id= tbl_level_pending_dtl.prop_type_mstr_id
                INNER JOIN view_ward_mstr ON view_ward_mstr.id = tbl_level_pending_dtl.ward_mstr_id
                ORDER BY tbl_level_pending_dtl.id DESC";
        $result = $this->model_datatable->single_with_limit($with,$aliase,$select,$from);
		$data['posts'] = $result['result'];
		$data['leveldetails'] = $data['posts'];
		$data['pager'] = $result['count'];
        $data['offset'] = $result['offset'];
        $data['view']="field_verification_list_new";
        // print_var($result);
        // return;
        return view('mobile/Property/FieldVerification/fieldVerificationSearchSafTC', $data);
    }

    public function field_verification_old($id = null, $levelid = null)
    {
        $data = (array)null;
        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
        $emp_details_id = $emp_mstr["id"];

        $sender_emp_details_id = $emp_details_id;

        $user_type_mstr_id = $emp_mstr["user_type_mstr_id"];
        $data["id"] = $id;
        $data["levelid"] = $levelid;
        $data["user_type_mstr_id"] = $user_type_mstr_id;

        if($id <> null && $levelid <> null)
        {
            if($this->request->getMethod() == 'post')
            {
                $inputs = arrFilterSanitizeString($this->request->getVar());
                
                $data["ward_list"] = $this->model_view_ward_permission->getPermittedWard($emp_details_id);
                $data["Saf_detail"] = $this->model_saf_dtl->SafDetailsById($id);
                $data["Saf_dtl_id"] = $data["Saf_detail"]["id"];
                $data["saf_no"] = $data["Saf_detail"]["saf_no"];
                $data["assessment_type"]= $data["Saf_detail"]["assessment_type"];
                $data["zone_id"] = $data["Saf_detail"]["zone_mstr_id"];
                $data["apply_date"] = $data["Saf_detail"]["apply_date"];
                $data["ward_mstr_id"] = $data["Saf_detail"]["ward_mstr_id"];

                $data["ward_list_mapp"] = $this->model_view_ward_mapping_mstr->getNewWardLIstByOldWardMstrId($data["ward_mstr_id"]);
                $mapp = array_map(function($val)
                        {
                            $val['ward_mstr_id'] = $val['id'];
                            return  $val;                            
                        },$data["ward_list_mapp"]);
                $data["ward_list_mapp"]=$mapp;

                $data["new_ward_mstr_id"] = $data["Saf_detail"]["new_ward_mstr_id"];
                $data["prop_type_mstr_id"] = $data["Saf_detail"]["prop_type_mstr_id"];
                $data["road_type_mstr_id"] = $data["Saf_detail"]["road_type_mstr_id"];
                $data["apply_date"] = $data["Saf_detail"]["apply_date"];
                $data["plot_no"] = $data["Saf_detail"]["plot_no"];
                $data["area_of_plot"] = $data["Saf_detail"]["area_of_plot"];
                $data["Khata_no"] = $data["Saf_detail"]["khata_no"];
                $data["village_mauja_name"] = $data["Saf_detail"]["village_mauja_name"];
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
                $data["prop_type_list"] = $this->model_prop_type_mstr->getPropTypeList();
                $data["road_type_list"] = $this->model_road_type_mstr->getRoadTypeList();
                $data["usage_list"] = $this->model_usage_type_mstr->getUsageTypeList();
                $data["occupancy_list"] = $this->model_occupancy_type_mstr->getOccupancyTypeList();
                $data["const_type_list"] = $this->model_const_type_mstr->getConstTypeList();
                $data["floor_list"] = $this->model_floor_mstr->getFloorList();


                /****************  Ward ***************/
                $data["ward_detail"] = $this->model_ward_mstr->getdatabyid($data["ward_mstr_id"]);
                $data["ward_no"] = $data["ward_detail"]["ward_no"];
                if ($inputs["rdo_ward_no"] == 1) {
                    $assess_ward_status = 'Correct';
                    $assess_ward_id = $data["ward_mstr_id"];
                } else {
                    $assess_ward_status = 'Incorrect';
                    $assess_ward_id = $inputs["ward_id"];
                }
                $data["assess_ward_status"] = $assess_ward_status;
                $data["assess_ward_detail"] = $this->model_ward_mstr->getdatabyid($assess_ward_id);
                $data["assess_ward_no"] = $data["assess_ward_detail"]["ward_no"];
                $data["assess_ward_id"] = $assess_ward_id;
                /**************   End Ward ************/

                /****************  New Ward ***************/
                $data["new_ward_detail"] = $this->model_ward_mstr->getdatabyid($data["new_ward_mstr_id"]);
                $data["new_ward_no"] = $data["ward_detail"]["ward_no"];
                if ($inputs["rdo_new_ward_no"] == 1) {
                    $assess_new_ward_status = 'Correct';
                    $assess_new_ward_id = $data["new_ward_mstr_id"];
                } else {
                    $assess_new_ward_status = 'Incorrect';
                    $assess_new_ward_id = $inputs["new_ward_id"];
                }
                $data["assess_new_ward_status"] = $assess_new_ward_status;
                $data["assess_new_ward_detail"] = $this->model_ward_mstr->getdatabyid($assess_new_ward_id);
                $data["assess_new_ward_no"] = $data["assess_new_ward_detail"]["ward_no"];
                $data["assess_new_ward_id"] = $assess_new_ward_id;
                /**************   End New Ward ************/

                /****************  Zone ***************/

                if ($inputs["rdo_zone"] == 1) {
                    $assess_zone_status = 'Correct';
                    $assess_zone_id = $data["zone_id"];
                } else {
                    $assess_ward_status = 'Incorrect';
                    $assess_zone_id = $inputs["zone"];
                }
                $data["assess_zone_status"] = $assess_ward_status;

                $data["assess_zone_id"] = $assess_zone_id;
                /**************   End Zone ************/

                /**************   Property Type ************/

                $data["prop_type_detail"] = $this->model_prop_type_mstr->getdatabyid($data["prop_type_mstr_id"]);
                $data["property_type"] = $data["prop_type_detail"]["property_type"];
                if ($inputs["rdo_property_type"] == 1) {
                    $assess_proptype_status = 'Correct';
                    $assess_property_type_id = $data["prop_type_mstr_id"];
                } else {
                    $assess_proptype_status = 'Incorrect';
                    $assess_property_type_id = $inputs["property_type_id"];
                }
                $data["assess_proptype_status"] = $assess_proptype_status;
                $data["assess_prop_type_detail"] = $this->model_prop_type_mstr->getdatabyid($assess_property_type_id);
                $data["assess_property_type"] = $data["assess_prop_type_detail"]["property_type"];
                $data["assess_property_type_id"] = $assess_property_type_id;

                /**************  End Property Type ************/

                /**************   Area Of Plot ************/
                if ($inputs["rdo_area_of_plot"] == 1) {
                    $assess_areaplot_status = 'Correct';
                    $assess_area_of_plot = $data["area_of_plot"];
                } else {
                    $assess_areaplot_status = 'Incorrect';
                    $assess_area_of_plot = $inputs["area_of_plot"];
                }
                $data["assess_areaplot_status"] = $assess_areaplot_status;
                $data["assess_area_of_plot"] = $assess_area_of_plot;

                /**************   End Area Of Plot ************/

                /**************   Road Type ************/
                $data["road_type_detail"] = $this->model_road_type_mstr->getdatabyid($data["road_type_mstr_id"]);
                $data["road_type"] = $data["road_type_detail"]["road_type"];

                if ($inputs["rdo_street_type"] == 1) {
                    $assess_roadtype_status = 'Correct';
                    $assess_road_type_id = $data["road_type_mstr_id"];
                } else {
                    $assess_roadtype_status = 'Incorrect';
                    $assess_road_type_id = $inputs["street_type_id"];
                }
                $data["assess_roadtype_status"] = $assess_roadtype_status;
                $data["assess_road_type_detail"] = $this->model_road_type_mstr->getdatabyid($assess_road_type_id);
                $data["assess_road_type"] = $data["assess_road_type_detail"]["road_type"];
                $data["assess_road_type_id"] = $assess_road_type_id;

                /**************  End Road Type ************/

                /**************   Floor Details ************/
                $data["floor_details"] = $this->model_view_saf_floor_details->getDataBySafDtlId_md5($id);
                $data["no_of_floor"] = sizeof($data["floor_details"]);
                // if ($inputs["rdo_total_floors"] == 1) {
                //     $assess_nofloor_status = 'Correct';
                //     $assess_no_of_floor = $data["no_of_floor"];
                // } else {
                //     $assess_nofloor_status = 'Incorrect';
                //     $assess_no_of_floor = $inputs["total_floors"];
                // }
                // $data["assess_nofloor_status"] = $assess_nofloor_status;
                // $data["assess_no_of_floor"] = $assess_no_of_floor;



                $ctr = 0;
                 //print_var($data["assess_usage_type_detail"]);
                               
                if((isset($inputs['rdo_property_type']) && $inputs['rdo_property_type']==1))
                { 
                    foreach ($data["floor_details"] as $key => $value) {
                        $ctr++;
    
                        /**********  Usage Type     *************/
                        if ($inputs["rdo_usage_type" . $ctr] == 1) {
                            $assess_usage_type_status = 'Correct';
                            $assess_usage_type_id = $value["usage_type_mstr_id"];
                        } else {
                            $assess_usage_type_status = 'Incorrect';
                            $assess_usage_type_id = $inputs["usagetypeid_" . $ctr];
                        }
                        $data["floor_details"][$key]["assess_usage_type_status"] = $assess_usage_type_status;
                        $data["assess_usage_type_detail"] = $this->model_usage_type_mstr->getolddatabyid($assess_usage_type_id);
                        $data["floor_details"][$key]["assess_usage_type"] = $data["assess_usage_type_detail"]["usage_type"];
                        $data["floor_details"][$key]["assess_usage_type_id"] = $data["assess_usage_type_detail"]["id"];
    
                        /**********  End Usage Type     *************/
    
                        /**********   Occupency Type     *************/
    
                        if ($inputs["rdo_occupancy_type" . $ctr] == 1) {
                            $assess_occupancy_type_status = 'Correct';
                            $assess_occupancy_type_id = $value["occupancy_type_mstr_id"];
                        } else {
                            $assess_occupancy_type_status = 'Incorrect';
                            $assess_occupancy_type_id = $inputs["occupancytypeid_" . $ctr];
                        }
                        $data["floor_details"][$key]["assess_occupancy_type_status"] = $assess_occupancy_type_status;
                        $data["assess_occupancy_type_detail"] = $this->model_occupancy_type_mstr->getdatabyid($assess_occupancy_type_id);
                        $data["floor_details"][$key]["assess_occupancy_type"] = $data["assess_occupancy_type_detail"]["occupancy_name"];
                        $data["floor_details"][$key]["assess_occupancy_type_id"] = $data["assess_occupancy_type_detail"]["id"];
    
                        /**********  End Occupency Type     *************/
    
                        /**********  Construction Type     *************/
    
                        if ($inputs["rdo_construction_type" . $ctr] == 1) {
                            $assess_const_type_status = 'Correct';
                            $assess_const_type_id = $value["const_type_mstr_id"];
                        } else {
                            $assess_const_type_status = 'Incorrect';
                            $assess_const_type_id = $inputs["consttypeid" . $ctr];
                        }
                        $data["floor_details"][$key]["assess_const_type_status"] = $assess_usage_type_status;
                        $data["assess_const_type_detail"] = $this->model_const_type_mstr->getdatabyid($assess_const_type_id);
                        /********** End Construction Type     *************/
    
                        /********** Buildup Area     *************/
                        $data["floor_details"][$key]["assess_const_type"] = $data["assess_const_type_detail"]["construction_type"];
                        $data["floor_details"][$key]["assess_const_type_id"] = $data["assess_const_type_detail"]["id"];
    
                        if ($inputs["rdo_builtup_area" . $ctr] == 1) {
                            $assess_builtup_area_status = 'Correct';
                            $assess_builtup_area = $value["builtup_area"];
                        } else {
                            $assess_builtup_area_status = 'Incorrect';
                            $assess_builtup_area = $inputs["builtuparea_" . $ctr];
                        }
                        $data["floor_details"][$key]["assess_builtup_area_status"] = $assess_builtup_area_status;
                        $data["floor_details"][$key]["assess_builtup_area"] = $assess_builtup_area;
    
                        if ($inputs["rdo_completion_date" . $ctr] == 1) {
                            $assess_completion_date_status = 'Correct';
                            $assess_completion_date = $value["date_from"];
                        } else {
                            $assess_completion_date_status = 'Incorrect';
                            $assess_completion_date = $inputs["completion_date" . $ctr];
                        }
                        $data["floor_details"][$key]["assess_completion_date_status"] = $assess_completion_date_status;
                        $data["floor_details"][$key]["assess_completion_date"] = $assess_completion_date;
                        /********** End Buildup Area     *************/
                    }

                }
                elseif((isset($inputs['rdo_property_type']) && $inputs['rdo_property_type']==0) && (isset($inputs['hid_property_type']) && $inputs['hid_property_type']!=4) && (isset($inputs['property_type_id']) && $inputs['property_type_id']!=4))
                {  
                    foreach ($data["floor_details"] as $key => $value) {
                        $ctr++;
    
                        /**********  Usage Type     *************/
                        if ($inputs["rdo_usage_type" . $ctr] == 1) {
                            $assess_usage_type_status = 'Correct';
                            $assess_usage_type_id = $value["usage_type_mstr_id"];
                        } else {
                            $assess_usage_type_status = 'Incorrect';
                            $assess_usage_type_id = $inputs["usagetypeid_" . $ctr];
                        }
                        $data["floor_details"][$key]["assess_usage_type_status"] = $assess_usage_type_status;
                        $data["assess_usage_type_detail"] = $this->model_usage_type_mstr->getolddatabyid($assess_usage_type_id);
                        $data["floor_details"][$key]["assess_usage_type"] = $data["assess_usage_type_detail"]["usage_type"];
                        $data["floor_details"][$key]["assess_usage_type_id"] = $data["assess_usage_type_detail"]["id"];
    
                        /**********  End Usage Type     *************/
    
                        /**********   Occupency Type     *************/
    
                        if ($inputs["rdo_occupancy_type" . $ctr] == 1) {
                            $assess_occupancy_type_status = 'Correct';
                            $assess_occupancy_type_id = $value["occupancy_type_mstr_id"];
                        } else {
                            $assess_occupancy_type_status = 'Incorrect';
                            $assess_occupancy_type_id = $inputs["occupancytypeid_" . $ctr];
                        }
                        $data["floor_details"][$key]["assess_occupancy_type_status"] = $assess_occupancy_type_status;
                        $data["assess_occupancy_type_detail"] = $this->model_occupancy_type_mstr->getdatabyid($assess_occupancy_type_id);
                        $data["floor_details"][$key]["assess_occupancy_type"] = $data["assess_occupancy_type_detail"]["occupancy_name"];
                        $data["floor_details"][$key]["assess_occupancy_type_id"] = $data["assess_occupancy_type_detail"]["id"];
    
                        /**********  End Occupency Type     *************/
    
                        /**********  Construction Type     *************/
    
                        if ($inputs["rdo_construction_type" . $ctr] == 1) {
                            $assess_const_type_status = 'Correct';
                            $assess_const_type_id = $value["const_type_mstr_id"];
                        } else {
                            $assess_const_type_status = 'Incorrect';
                            $assess_const_type_id = $inputs["consttypeid" . $ctr];
                        }
                        $data["floor_details"][$key]["assess_const_type_status"] = $assess_usage_type_status;
                        $data["assess_const_type_detail"] = $this->model_const_type_mstr->getdatabyid($assess_const_type_id);
                        /********** End Construction Type     *************/
    
                        /********** Buildup Area     *************/
                        $data["floor_details"][$key]["assess_const_type"] = $data["assess_const_type_detail"]["construction_type"];
                        $data["floor_details"][$key]["assess_const_type_id"] = $data["assess_const_type_detail"]["id"];
    
                        if ($inputs["rdo_builtup_area" . $ctr] == 1) {
                            $assess_builtup_area_status = 'Correct';
                            $assess_builtup_area = $value["builtup_area"];
                        } else {
                            $assess_builtup_area_status = 'Incorrect';
                            $assess_builtup_area = $inputs["builtuparea_" . $ctr];
                        }
                        $data["floor_details"][$key]["assess_builtup_area_status"] = $assess_builtup_area_status;
                        $data["floor_details"][$key]["assess_builtup_area"] = $assess_builtup_area;
    
                        /********** End Buildup Area     *************/
                    } 
                }
                
                


                /********** End Floor Detalis     *************/
                /********** New Floor Added Details ***************/

                $newfloor = [];
                if (isset($inputs["chkfloor"]) && $inputs["chkfloor"] == 'on')
                {
                    $data["chkfloor"] = $inputs["chkfloor"];
                    $data["countarea"] = sizeof($inputs["floor_id"]);

                    for ($cnt = 0; $cnt < sizeof($inputs["floor_id"]); $cnt++)
                    {
                        $newfloor[$cnt]["floor_id"] = $inputs["floor_id"][$cnt];
                        $newfloor[$cnt]["use_type_id"] = $inputs["use_type_id"][$cnt];
                        $newfloor[$cnt]["occupancy_type_id"] = $inputs["occupancy_type_id"][$cnt];
                        $newfloor[$cnt]["construction_type_id"] = $inputs["construction_type_id"][$cnt];
                        $newfloor[$cnt]["builtup_area"] = $inputs["builtup_area"][$cnt];

                        // 2 TENANTED, 1 Residential
                        if ($inputs["use_type_id"] == 1)
                        {
                            $ncarpet_area = $inputs["builtup_area"][$cnt] * 0.7;
                        } else {
                            $ncarpet_area = $inputs["builtup_area"][$cnt] * 0.8;
                        }


                        $newfloor[$cnt]["carpet_area"] = $ncarpet_area;
                        $newfloor[$cnt]["occ_mm"] = $inputs["occ_mm"][$cnt];
                        $newfloor[$cnt]["occ_yyyy"] = explode("-", $inputs["occ_mm"][$cnt])[0];
                        $floor_list = $this->model_floor_mstr->getdatabyid($inputs["floor_id"][$cnt]);
                        $newfloor[$cnt]["floor_name"] = $floor_list["floor_name"];
                        $usage_type_list = $this->model_usage_type_mstr->getdatabyid($inputs["use_type_id"][$cnt]);
                        $newfloor[$cnt]["usage_type"] = $usage_type_list["usage_type"];
                        $occupancy_type_list = $this->model_occupancy_type_mstr->getdatabyid($inputs["occupancy_type_id"][$cnt]);
                        $newfloor[$cnt]["occupancy_name"] = $occupancy_type_list["occupancy_name"];
                        $construction_type_list = $this->model_const_type_mstr->getdatabyid($inputs["construction_type_id"][$cnt]);
                        $newfloor[$cnt]["construction_type"] = $construction_type_list["construction_type"];
                    }

                }
                $data["newfloor"] = $newfloor;

                /********** End New Floor Added Details ***************/

                /***************   hoarding          *******************/

                if ($data["is_hoarding_board"] == 't') {
                    $assessed_has_hording = 'Yes';
                    $data["assess_hoarding_area"] = $data["hoarding_area"];
                    $data["assess_hoarding_installation_date"] = $data["hoarding_installation_date"];
                } else {
                    $assessed_has_hording = 'No';
                }
                $data["assessed_has_hording"] = $assessed_has_hording;

                if ($inputs["has_hording"] == 1) {
                    $assess_has_hording = 'Yes';
                    $data["assess_hoarding_area"] = $inputs["total_hording_area"];
                    $data["assess_hoarding_installation_date"] = $inputs["hording_installation_date"];
                } else {
                    $assess_has_hording = 'No';
                }
                $data["assess_has_hording"] = $assess_has_hording;
                $data["assess_has_hording_status"] = $inputs["has_hording"];

                /************** end hording            *************/

                /*********       mobile tower         *****/
                if ($data["is_mobile_tower"] == 't') {
                    $assessed_is_mobile_tower = 'Yes';
                    $data["assess_mobile_tower_area"] = $data["tower_area"];
                    $data["assess_mobile_tower_installation_date"] = $data["tower_installation_date"];
                } else {
                    $assessed_is_mobile_tower = 'No';
                }
                $data["assessed_has_mobile_tower"] = $assessed_is_mobile_tower;

                if ($inputs["has_mobile_tower"] == 1) {
                    $assess_has_mobile_tower = 'Yes';
                    $data["assess_mobile_tower_area"] = $inputs["total_tower_area"];
                    $data["assess_mobile_tower_installation_date"] = $inputs["tower_installation_date"];
                } else {
                    $assess_has_mobile_tower = 'No';
                }
                $data["assess_has_mobile_tower"] = $assess_has_mobile_tower;
                $data["assess_has_mobile_tower_status"] = $inputs["has_mobile_tower"];

                /*********        end mobile tower           *********/
                /*************** petrol Pump      ***************/
                $data["is_petrol_pump"] = $data["Saf_detail"]["is_petrol_pump"];
                $data["under_ground_area"] = $data["Saf_detail"]["under_ground_area"];
                $data["petrol_pump_completion_date"] = $data["Saf_detail"]["petrol_pump_completion_date"];

                if ($data["is_petrol_pump"] == 't') {
                    $assessed_is_petrol_pump = 'Yes';
                    $data["assess_petrol_pump_area"] = $data["under_ground_area"];
                    $data["assess_petrol_pump_installation_date"] = $data["petrol_pump_completion_date"];
                } else {
                    $assessed_is_petrol_pump = 'No';
                }
                $data["assessed_is_petrol_pump"] = $assessed_is_petrol_pump;


                if (isset($inputs["is_petrol_pump"]) && $inputs["is_petrol_pump"] == 1) {
                    $assess_is_petrol_pump = 'Yes';
                    $data["assess_petrol_pump_area"] = $inputs["under_ground_area"];
                    $data["assess_petrol_pump_installation_date"] = $inputs["petrol_pump_completion_date"];
                } else {
                    $assess_is_petrol_pump = 'No';
                    $inputs["is_petrol_pump"]=0;
                }

                $data["assess_is_petrol_pump"] = $assess_is_petrol_pump;
                $data["assess_is_petrol_pump_status"] = $inputs["is_petrol_pump"];

                /**************   end petrol pump    ****************/

                /*************** RainWater Harveting    ***************/
                if ($data["is_water_harvesting"] == 't') {
                    $assessed_water_harvesting = 'Yes';
                } else {
                    $assessed_water_harvesting = 'No';
                }
                $data["assessed_water_harvesting"] = $assessed_water_harvesting;

                if (isset($inputs["rdo_water_harvesting"]) && $inputs["rdo_water_harvesting"] == 1)
                {
                    $assess_water_harvesting_select = 'Correct';
                    if ($inputs["hid_water_harvesting"] == 1) {
                        $assess_water_harvesting = 'Yes';
                    } else {
                        $assess_water_harvesting = 'No';
                    }
                }
                else
                {
                    $assess_water_harvesting_select = 'Incorrect';

                    // if (isset($inputs["water_harvesting"]) && $inputs["water_harvesting"] == 1) 
                    if(isset($inputs["rdo_water_harvesting"]) && $inputs["hid_water_harvesting"] != 1) 
                    {
                        $assess_water_harvesting = 'Yes';
                    }
                    else
                    {
                        $assess_water_harvesting = 'No';

                        # In case of vacant land
                        $inputs["rdo_water_harvesting"]=0;
                        $inputs["hid_water_harvesting"]=0;
                    }
                }

                $data["assess_water_harvesting_select"] = $assess_water_harvesting_select;
                $data["assess_water_harvesting_status_select"] = $inputs["rdo_water_harvesting"];


                $data["assess_water_harvesting"] = $assess_water_harvesting;
                // $data["assess_water_harvesting_status"] = $inputs["hid_water_harvesting"];
                $data["assess_water_harvesting_status"] = ($assess_water_harvesting=='Yes'?1:0)??0;



                /**************   end RainWater Harveting    ****************/

                /******************** Added Floor Details*********************/
                $data["fieldVerificationmstr_detail"] = $this->model_field_verification_dtl->getdatabysafid($data["Saf_dtl_id"]);
                if($data["fieldVerificationmstr_detail"])
                {
                    $data["vward_detail"] = $this->model_ward_mstr->getdatabyid($data["fieldVerificationmstr_detail"]["ward_mstr_id"]);
                    $data["vnew_ward_detail"] = $this->model_ward_mstr->getdatabyid($data["fieldVerificationmstr_detail"]["new_ward_mstr_id"]);
                    $data["vprop_type_detail"] = $this->model_prop_type_mstr->getdatabyid($data["fieldVerificationmstr_detail"]["prop_type_mstr_id"]);
                    $data["vzone_id"] = $data["fieldVerificationmstr_detail"]["zone_mstr_id"];
                    $data["vroad_type_detail"] = $this->model_road_type_mstr->getdatabyid($data["fieldVerificationmstr_detail"]["road_type_mstr_id"]);
                    $data["vward_no"] = $data["vward_detail"]["ward_no"];
                    $data["vward_mstr_id"] = $data["vward_detail"]["id"];
                    $data["vnew_ward_no"] = $data["vnew_ward_detail"]["ward_no"];
                    $data["vnew_ward_mstr_id"] = $data["vnew_ward_detail"]["id"];
                    $data["vproperty_type"] = $data["vprop_type_detail"]["property_type"];
                    $data["vroad_type"] = $data["vroad_type_detail"]["road_type"];
                    $data["varea_of_plot"] = $data["fieldVerificationmstr_detail"]["area_of_plot"];
                    $data["vis_hoarding_board"] = $data["fieldVerificationmstr_detail"]["is_hoarding_board"];
                    $data["vhoarding_area"] = $data["fieldVerificationmstr_detail"]["hoarding_area"];
                    $data["vhoarding_installation_date"] = $data["fieldVerificationmstr_detail"]["hoarding_installation_date"];
                    $data["vis_mobile_tower"] = $data["fieldVerificationmstr_detail"]["is_mobile_tower"];
                    $data["vtower_area"] = $data["fieldVerificationmstr_detail"]["tower_area"];
                    $data["vtower_installation_date"] = $data["fieldVerificationmstr_detail"]["tower_installation_date"];
                    $data["vis_petrol_pump"] = $data["fieldVerificationmstr_detail"]["is_petrol_pump"];
                    //dd($data["fieldVerificationmstr_detail"]["is_petrol_pump"]);
                    $data["vunder_ground_area"] = $data["fieldVerificationmstr_detail"]["under_ground_area"];
                    $data["vpetrol_pump_completion_date"] = $data["fieldVerificationmstr_detail"]["petrol_pump_completion_date"];
                    $data["vis_water_harvesting"] = $data["fieldVerificationmstr_detail"]["is_water_harvesting"];
                    //$data["vland_occupation_date"] = $data["fieldVerificationmstr_detail"]["land_occupation_date"];
                }

                foreach ($data["floor_details"] as $key => $value)
                {
                    $data["floor_details"][$key]["vfloor_details"] = $this->model_field_verification_floor_details->getDataBymstsaffloorId($data["fieldVerificationmstr_detail"]["id"], $value["id"]);
                }
                $data["vfloor_details_added"] = $this->model_field_verification_floor_details->getDataBymstsaffloorId($data["fieldVerificationmstr_detail"]["id"], 0);
                $data["no_of_addedfloor"] = sizeof($data["vfloor_details_added"]);
                /*******************End Added Floor Details*******************/



               // dd($data,$inputs);
                if (isset($inputs["btn_submit"]))
                {
                    $geoTag = $this->db->query("select distinct (direction_type) as direction_type from tbl_saf_geotag_upload_dtl where geotag_dtl_id = ".$data['saf_dtl_id']." AND status =1 ")->getResultArray();
		            $data["is_geo_tag_done"] = sizeof($geoTag)>=3 ? true : false;
                    return view('mobile/Property/FieldVerification/verificationDetails', $data);
                }
                if (isset($inputs["back"]))
                {
                    return view('mobile/Property/FieldVerification/fieldVerification', $data);
                }

                if (isset($inputs["Save"]))
                {
                    // start check level details is already send in level
                    $sql = "SELECT * FROM tbl_level_pending_dtl WHERE id='".$levelid."' AND verification_status!=0";

                    if ($checkLevelDtl = $this->db->query($sql)->getFirstRow("array")) {
                        if ($user_type_mstr_id == 7) {
                            return $this->response->redirect(base_url('SafVerification/verificationview/' . $id . '/' . $checkLevelDtl["id"]));
                        } else {

                            return $this->response->redirect(base_url('SafVerification/verificationview/' . $id . '/' . $checkLevelDtl["id"]));

                           // return $this->response->redirect(base_url('SafVerification/verificationUpload/' . $id . '/' . $checkLevelDtl["id"]));
                        }
                    }

                    // end check level details is already send in level
                    $this->db->transBegin();
                    $checkAgencyData = $this->model_field_verification_dtl->getulbdatabysafid($data["Saf_dtl_id"], 'ULB TC');
                    if ($user_type_mstr_id == 7)
                    {
                        $tcType = 'ULB TC';
                        $receiver_user_type_id = 9;
                    }
                    else
                    {
                        $tcType = 'AGENCY TC';
                        $receiver_user_type_id = 7;

                        if($checkAgencyData){
                            $receiver_user_type_id=5;
                        }
                    }


                    $insert = [
                        'saf_dtl_id' => $data["Saf_dtl_id"],
                        'ward_mstr_id' => $assess_ward_id,
                        'new_ward_mstr_id' => $assess_new_ward_id,
                        'zone_mstr_id' => $assess_zone_id,
                        'prop_type_mstr_id' => $assess_property_type_id,
                        'area_of_plot' => $assess_area_of_plot,
                        'road_type_mstr_id' => $assess_road_type_id,
                        'is_mobile_tower' => $inputs["has_mobile_tower"] == 1 ? 't' : 'f',
                        'tower_area' => $inputs["total_tower_area"] ?? NULL,
                        'tower_installation_date' => $inputs["tower_installation_date"] ?? NULL,
                        'is_hoarding_board' => $inputs["has_hording"] == 1 ? 't' : 'f',
                        'hoarding_area' => (isset($data["assess_hoarding_area"]) && $data["assess_hoarding_area"]!="") ? $data["assess_hoarding_area"] :NULL,
                        'hoarding_installation_date' => (isset($data["assess_hoarding_installation_date"]) && $data["assess_hoarding_installation_date"]!="") ? $data["assess_hoarding_installation_date"] :NULL,
                        'is_petrol_pump' => $inputs["is_petrol_pump"] == 1 ? 't' : 'f',
                        'under_ground_area' => $data["assess_petrol_pump_area"] ?? NULL,
                        'petrol_pump_completion_date' => $data["assess_petrol_pump_installation_date"] ?? NULL,
                        'is_water_harvesting' =>  (isset($inputs["water_harvesting"]) && ($inputs["water_harvesting"] == 1)) ? 't' : 'f',
                        'verified_by_emp_details_id' => $emp_details_id,
                        'created_on' => date('Y-m-d H:i:s'),
                        'verified_by' => $tcType,
                        "percentage_of_property_transfer" => $inputs["percentage_of_property"] ?? NULL,
                    ];

                    $field_mstr_id = $this->model_field_verification_dtl->insertData($insert);

                    if (isset($field_mstr_id))
                    {
                        $ctr = $inputs["countdata"] ?? 0;
                        for ($i = 1; $i < $ctr; $i++)
                        {
                            if ($inputs["usagetypeid_" . $i] == 1) {
                                $carpet_area = $inputs["builtuparea_" . $i] * 0.7;
                            } else {
                                $carpet_area = $inputs["builtuparea_" . $i] * 0.8;
                            }

                            if ($inputs["completiondate_upto" . $i] == '')
                                $date_upto = NULL;
                            else
                                $date_upto = $inputs["completiondate_upto" . $i];


                            $insertfloor = [
                                'field_verification_dtl_id' => $field_mstr_id,
                                'saf_dtl_id' => $data["Saf_dtl_id"],
                                'saf_floor_dtl_id' => $inputs["saf_floor_dtl_id" . $i],
                                'floor_mstr_id' => $inputs["floorid" . $i],
                                'usage_type_mstr_id' => $inputs["usagetypeid_" . $i],
                                'const_type_mstr_id' => $inputs["consttypeid" . $i],
                                'occupancy_type_mstr_id' => $inputs["occupancytypeid_" . $i],
                                'builtup_area' => $inputs["builtuparea_" . $i],
                                'carpet_area' => $carpet_area,
                                'date_from' => $inputs["completion_date" . $i]."-01",
                                'date_upto' => $date_upto,
                                'emp_details_id' => $emp_details_id,
                                'created_on' => date('Y-m-d H:i:s'),
                                'verified_by' => $tcType
                            ];

                            $this->model_field_verification_floor_details->insertData($insertfloor);
                        }

                        if ($inputs["chkfloor"] == 'on')
                        {
                            for ($cntq = 0; $cntq < sizeof($inputs["floor_id"]); $cntq++)
                            {
                                if ($inputs["use_type_id"][$cntq] == 1)
                                {
                                    $carpet_area = $inputs["builtup_area"][$cntq] * 0.7;
                                }
                                else
                                {
                                    $carpet_area = $inputs["builtup_area"][$cntq] * 0.8;
                                }

                                $yy = $inputs["occ_yyyy"][$cntq];
                                $mm = $inputs["occ_mm"][$cntq];
                                $data_fromas = $mm . '-01';


                                $ninsertfloor = [
                                    'field_verification_dtl_id' => $field_mstr_id,
                                    'saf_dtl_id' => $data["Saf_dtl_id"],
                                    'saf_floor_dtl_id' => 0,
                                    'floor_mstr_id' => $inputs["floor_id"][$cntq],
                                    'usage_type_mstr_id' => $inputs["use_type_id"][$cntq],
                                    'const_type_mstr_id' => $inputs["construction_type_id"][$cntq],
                                    'occupancy_type_mstr_id' => $inputs["occupancy_type_id"][$cntq],
                                    'builtup_area' => $inputs["builtup_area"][$cntq],
                                    'carpet_area' => $carpet_area,
                                    'date_from' => $data_fromas,
                                    'date_upto' => NULL,
                                    'emp_details_id' => $emp_details_id,
                                    'created_on' => date('Y-m-d H:i:s'),
                                    'verified_by' => $tcType
                                ];

                                $this->model_field_verification_floor_details->insertData($ninsertfloor);
                            }
                        }


                        $leveldata = [
                            'remarks' => 'Verified',
                            'level_pending_dtl_id' => $levelid,
                            'saf_dtl_id' => $data["Saf_dtl_id"],
                            'emp_details_id' => $emp_details_id,
                            'created_on' => date('Y-m-d H:i:s'),
                            'sender_user_type_id' => $user_type_mstr_id,
                            'receiver_user_type_id' => $receiver_user_type_id,
                            'created_on' => date('Y-m-d H:i:s'),
                            'forward_date' => date('Y-m-d'),
                            'forward_time' => date('H:i:s'),
                            'verification_status' => 1,
                            'status'=> 0,
                            'sender_emp_details_id' => $sender_emp_details_id,
                            'receiver_emp_details_id' => $sender_emp_details_id,

                        ];
                        // start water harvesting image upload 
                        if (isset($inputs["water_harvesting"]) && ($inputs["water_harvesting"] == 1)) {
                            $ulb_folder_name = "RANCHI";
                            $upload_type = ($user_type_mstr_id == 7)?"UTC Field Verification":"Field Verification";
                            $destination_path = "field_verification";
                            $WHfile = $this->request->getFile('water_harvesting_image_path');//print_var($leftfile);//move_uploaded_file($_FILES['file']['tmp_name'], WRITEPATH . 'uploads/' . $ulb_folder_name . '/' . $temp_path . "/" . $tmp_file_name . '.' . $leftfile->getExtension());return;
                            $validated = $this->validate([
                                'file' => [
                                    'uploaded[water_harvesting_image_path]',
                                    'mime_in[water_harvesting_image_path,image/jpeg,image/jpg]',
                                    'max_size[water_harvesting_image_path,5242880]',
                                    'ext_in[water_harvesting_image_path,jpg,jpeg]',
                                ],
                            ]);
                            if ($validated && !$WHfile->hasMoved())
                            { 
                                $inputsData = [
                                    "geotag_dtl_id" => $data["Saf_dtl_id"],
                                    "latitude" => $this->request->getVar("water_harvesting_image_path_latitude_text"),
                                    "longitude" => $this->request->getVar("water_harvesting_image_path_longitude_text"),
                                    "direction_type" => "Water Harvesting",
                                    "upload_type" => $upload_type,
                                    "created_by_emp_details_id" => $sender_emp_details_id,
                                    "created_on" => date('Y-m-d H:i:s'),
                                    "status" => 1,
                                ];
                                if ($this->db->table("tbl_saf_geotag_upload_dtl")->insert($inputsData)) {
                                    $saf_geotag_upload_dtl_id = $this->db->insertId();
                                    $tmp_file_name = md5($saf_geotag_upload_dtl_id);
                                    $file_ext = $WHfile->getExtension();
                                    $ftPath = '/' . $ulb_folder_name . '/' . $destination_path . "/" . $tmp_file_name . '.' . $file_ext;

                                    if ($WHfile->move(WRITEPATH . 'uploads/' . $ulb_folder_name . '/' . $destination_path . '/', $tmp_file_name . '.' . $file_ext))
                                    {
                                        $this->db->table("tbl_saf_geotag_upload_dtl")
                                                    ->where("id", $saf_geotag_upload_dtl_id)
                                                    ->update(["image_path"=>$ftPath]);
                                    }
                                }
                            }
                        }
                        // end water harvesting image upload 
                        if(isset($_POST["remarks"]) && $_POST["remarks"]!="")
                        {
                            $leveldata['remarks'] = trim($_POST["remarks"]);
                        }

						if($checkAgencyData && $user_type_mstr_id == 5){
                            $this->model_level_pending_dtl->bugfix_level_pending_new($leveldata); //Shashi
                        }else{
                            $this->model_level_pending_dtl->bugfix_level_pending($leveldata); //Shashi
                        } 

                        if ($updatelevelpending = $this->model_level_pending_dtl->updatelevelpendingById($leveldata))
                        {
                            // $leveldata['remarks'] =null;
                            if (($checkAgencyData && $user_type_mstr_id == 5)?$this->model_level_pending_dtl->insrtSILevelFinaldtl($leveldata):$this->model_level_pending_dtl->insrtlevelpendingdtl($leveldata))
                            {
                                if($this->db->transStatus() === false)
                                {
                                    flashToast("message", "Something went wrong");
                                    $this->db->transRollback();
                                }
                                else
                                {
                                    $this->db->transCommit();
                                    flashToast("message", "Verified Successfully");

                                    if ($user_type_mstr_id == 7)
                                    {
                                        return $this->response->redirect(base_url('SafVerification/verificationview/' . $id . '/' . $levelid));
                                    }
                                    else
                                    {
                                       // dd($data["Saf_detail"]);
                                        $application = $data["Saf_detail"];

                                        $vistingRepostInput = safGeoVisit($application,$this->request->getVar());
                                        $visiting_dtl_id = $this->model_visiting_dtl->insertdetail($vistingRepostInput);
                                        return $this->response->redirect(base_url('SafVerification/verificationview/' . $id . '/' . $levelid));

                                       //old// return $this->response->redirect(base_url('SafVerification/verificationUpload/' . $id . '/' . $levelid));
                                    }
                                }
                            }
                        }
                    }
                }

                return view('mobile/Property/FieldVerification/verificationDetails', $data);
            }
            else
            {
                $data["ward_list"] = $this->model_view_ward_permission->getPermittedWardWithSession($emp_details_id);
                $data["Saf_detail"] = $this->model_saf_dtl->SafDetailsById($id);
                if ($data["Saf_detail"]["previous_holding_id"]!="" && $data["Saf_detail"]["assessment_type"]=="Mutation") {
                    $transferModeList = $this->model_transfer_mode_mstr->getTransferModeList();
                    $transferModeId = array_search($data["Saf_detail"]["transfer_mode_mstr_id"], array_column($transferModeList, 'id'));
                    $data["transfer_mode"] = $transferModeList[$transferModeId]["transfer_mode"];
                    $sql = "SELECT * FROM tbl_prop_owner_detail WHERE prop_dtl_id=".$data["Saf_detail"]["previous_holding_id"];
                    if($prev_prop_owner_result = $this->db->query($sql)->getResultArray()) {
                        $data["prev_prop_owner_dtl"] = $prev_prop_owner_result;
                    }
                }

                $data["Saf_dtl_id"] = $data["Saf_detail"]["id"];
                $data["saf_no"] = $data["Saf_detail"]["saf_no"];
                $data["assessment_type"]= $data["Saf_detail"]["assessment_type"];
                $data["zone_id"] = $data["Saf_detail"]["zone_mstr_id"];
                $data["ward_mstr_id"] = $data["Saf_detail"]["ward_mstr_id"];
                $data["ward_list_mapp"] = $this->model_view_ward_mapping_mstr->getNewWardLIstByOldWardMstrId($data["ward_mstr_id"]);
                $mapp = array_map(function($val)
                        {
                            $val['ward_mstr_id'] = $val['id'];
                            return  $val;                            
                        },$data["ward_list_mapp"]);
                $data["ward_list_mapp"]=$mapp;
                $data["new_ward_mstr_id"] = $data["Saf_detail"]["new_ward_mstr_id"];
                $data["prop_type_mstr_id"] = $data["Saf_detail"]["prop_type_mstr_id"];
                $data["road_type_mstr_id"] = $data["Saf_detail"]["road_type_mstr_id"];
                $data["apply_date"] = $data["Saf_detail"]["apply_date"];
                $data["plot_no"] = $data["Saf_detail"]["plot_no"];
                $data["area_of_plot"] = $data["Saf_detail"]["area_of_plot"];
                $data["Khata_no"] = $data["Saf_detail"]["khata_no"];
                $data["village_mauja_name"] = $data["Saf_detail"]["village_mauja_name"];
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
                $data["ward_detail"] = $this->model_ward_mstr->getdatabyid($data["ward_mstr_id"]);
                if ($new_ward_dtl = $this->model_ward_mstr->getdatabyid($data["new_ward_mstr_id"])) {
                    $data["new_ward_no"] = $new_ward_dtl["ward_no"];
                } else {
                    $data["new_ward_no"] = "N/A";
                }
                $data["prop_type_detail"] = $this->model_prop_type_mstr->getdatabyid($data["prop_type_mstr_id"]);
                $data["prop_type_list"] = $this->model_prop_type_mstr->getPropTypeList();
                $data["road_type_detail"] = $this->model_road_type_mstr->getdatabyid($data["road_type_mstr_id"]);
                $data["road_type_list"] = $this->model_road_type_mstr->getRoadTypeList();
                $data["ward_no"] = $data["ward_detail"]["ward_no"];
                $data["property_type"] = $data["prop_type_detail"]["property_type"];
                $data["road_type"] = $data["road_type_detail"]["road_type"];
                $data["owner_detail"] = $this->model_saf_owner_detail->ownerdetails_md5($id);
                $data["floor_details"] = $this->model_view_saf_floor_details->getDataBySafDtlId_md5($id);
                $data["usage_list"] = $this->model_usage_type_mstr->getUsageTypeList();
                $data["occupancy_list"] = $this->model_occupancy_type_mstr->getOccupancyTypeList();
                $data["const_type_list"] = $this->model_const_type_mstr->getConstTypeList();
                $data["floor_list"] = $this->model_floor_mstr->getFloorList();
                $data["no_of_floor"] = sizeof($data["floor_details"]);
                $data["tax_details"] = $this->model_saf_tax->getDataBySafDtlId_md5($id);


                /******************** Added Floor Details*********************/
                $data["fieldVerificationmstr_detail"] = $this->model_field_verification_dtl->getdatabysafid($data["Saf_dtl_id"]);

                if($data["fieldVerificationmstr_detail"])
                {
                    //print_r($data["fieldVerificationmstr_detail"]);
                    $data["vward_detail"] = $this->model_ward_mstr->getdatabyid($data["fieldVerificationmstr_detail"]["ward_mstr_id"]);
                    //$data["vnew_ward_detail"] = $this->model_ward_mstr->getdatabyid($data["fieldVerificationmstr_detail"]["ward_mstr_id"]);
                    if ($new_ward_dtl = $this->model_ward_mstr->getdatabyid($data["fieldVerificationmstr_detail"]["new_ward_mstr_id"])) {
                        $data["vnew_ward_no"] = $new_ward_dtl["ward_no"];
                    } else {
                        $data["vnew_ward_no"] = "N/A";
                    }
                    $data["vprop_type_detail"] = $this->model_prop_type_mstr->getdatabyid($data["fieldVerificationmstr_detail"]["prop_type_mstr_id"]);
                    $data["vroad_type_detail"] = $this->model_road_type_mstr->getdatabyid($data["fieldVerificationmstr_detail"]["road_type_mstr_id"]);
                    $data["vward_no"] = $data["vward_detail"]["ward_no"];
                    $data["vward_mstr_id"] = $data["vward_detail"]["id"];
                    $data["vproperty_type"] = $data["vprop_type_detail"]["property_type"];
                    $data["vroad_type"] = $data["vroad_type_detail"]["road_type"];
                    $data["vzone_id"] = $data["fieldVerificationmstr_detail"]["zone_mstr_id"];
                    $data["varea_of_plot"] = $data["fieldVerificationmstr_detail"]["area_of_plot"];
                    $data["vis_hoarding_board"] = $data["fieldVerificationmstr_detail"]["is_hoarding_board"];
                    $data["vhoarding_area"] = $data["fieldVerificationmstr_detail"]["hoarding_area"];
                    $data["vhoarding_installation_date"] = $data["fieldVerificationmstr_detail"]["hoarding_installation_date"];
                    $data["vis_mobile_tower"] = $data["fieldVerificationmstr_detail"]["is_mobile_tower"];
                    $data["vtower_area"] = $data["fieldVerificationmstr_detail"]["tower_area"];
                    $data["vtower_installation_date"] = $data["fieldVerificationmstr_detail"]["tower_installation_date"];
                    $data["vis_petrol_pump"] = $data["fieldVerificationmstr_detail"]["is_petrol_pump"];
                    $data["vunder_ground_area"] = $data["fieldVerificationmstr_detail"]["under_ground_area"];
                    $data["vpetrol_pump_completion_date"] = $data["fieldVerificationmstr_detail"]["petrol_pump_completion_date"];
                    $data["vis_water_harvesting"] = $data["fieldVerificationmstr_detail"]["is_water_harvesting"];
                    //$data["vland_occupation_date"] = $data["fieldVerificationmstr_detail"]["land_occupation_date"];
                    foreach ($data["floor_details"] as $key => $value) {
                        $data["floor_details"][$key]["vfloor_details"] = $this->model_field_verification_floor_details->getDataBymstsaffloorId($data["fieldVerificationmstr_detail"]["id"], $value["id"]);
                    }

                    $data["vfloor_details_added"] = $this->model_field_verification_floor_details->getDataBymstsaffloorId($data["fieldVerificationmstr_detail"]["id"], 0);

                    $data["no_of_addedfloor"] = sizeof($data["vfloor_details_added"]);
                    /*******************End Added Floor Details*******************/
                }

                return view('mobile/Property/FieldVerification/fieldVerification', $data);
            }
        }
    }

    public function field_verification($id = null, $levelid = null)
    {
        $data = (array)null;
        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $Session->get("emp_details");
        $emp_details_id = $emp_mstr["id"];

        $sender_emp_details_id = $emp_details_id;

        $user_type_mstr_id = $emp_mstr["user_type_mstr_id"];
        $data["id"] = $id;
        $data["levelid"] = $levelid;
        $data["user_type_mstr_id"] = $user_type_mstr_id;

        if($id <> null && $levelid <> null)
        {
            if($this->request->getMethod() == 'post')
            {
                $inputs = arrFilterSanitizeString($this->request->getVar());
                
                $data["ward_list"] = $this->model_view_ward_permission->getPermittedWard($emp_details_id);
                $data["Saf_detail"] = $this->model_saf_dtl->SafDetailsById($id);
                $data["Saf_dtl_id"] = $data["Saf_detail"]["id"];
                $data["saf_no"] = $data["Saf_detail"]["saf_no"];
                $data["assessment_type"]= $data["Saf_detail"]["assessment_type"];
                $data["zone_id"] = $data["Saf_detail"]["zone_mstr_id"];
                $data["apply_date"] = $data["Saf_detail"]["apply_date"];
                $data["ward_mstr_id"] = $data["Saf_detail"]["ward_mstr_id"];

                $data["ward_list_mapp"] = $this->model_view_ward_mapping_mstr->getNewWardLIstByOldWardMstrId($data["ward_mstr_id"]);
                $mapp = array_map(function($val)
                        {
                            $val['ward_mstr_id'] = $val['id'];
                            return  $val;                            
                        },$data["ward_list_mapp"]);
                $data["ward_list_mapp"]=$mapp;

                $data["new_ward_mstr_id"] = $data["Saf_detail"]["new_ward_mstr_id"];
                $data["prop_type_mstr_id"] = $data["Saf_detail"]["prop_type_mstr_id"];
                $data["road_type_mstr_id"] = $data["Saf_detail"]["road_type_mstr_id"];
                $data["apply_date"] = $data["Saf_detail"]["apply_date"];
                $data["plot_no"] = $data["Saf_detail"]["plot_no"];
                $data["area_of_plot"] = $data["Saf_detail"]["area_of_plot"];
                $data["Khata_no"] = $data["Saf_detail"]["khata_no"];
                $data["village_mauja_name"] = $data["Saf_detail"]["village_mauja_name"];
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
                $data["prop_type_list"] = $this->model_prop_type_mstr->getPropTypeList();
                $data["road_type_list"] = $this->model_road_type_mstr->getRoadTypeList();
                $data["usage_list"] = $this->model_usage_type_mstr->getUsageTypeList();
                $data["occupancy_list"] = $this->model_occupancy_type_mstr->getOccupancyTypeList();
                $data["const_type_list"] = $this->model_const_type_mstr->getConstTypeList();
                $data["floor_list"] = $this->model_floor_mstr->getFloorList();


                /****************  Ward ***************/
                $data["ward_detail"] = $this->model_ward_mstr->getdatabyid($data["ward_mstr_id"]);
                $data["ward_no"] = $data["ward_detail"]["ward_no"];
                if ($inputs["rdo_ward_no"] == 1) {
                    $assess_ward_status = 'Correct';
                    $assess_ward_id = $data["ward_mstr_id"];
                } else {
                    $assess_ward_status = 'Incorrect';
                    $assess_ward_id = $inputs["ward_id"];
                }
                $data["assess_ward_status"] = $assess_ward_status;
                $data["assess_ward_detail"] = $this->model_ward_mstr->getdatabyid($assess_ward_id);
                $data["assess_ward_no"] = $data["assess_ward_detail"]["ward_no"];
                $data["assess_ward_id"] = $assess_ward_id;
                /**************   End Ward ************/

                /****************  New Ward ***************/
                $data["new_ward_detail"] = $this->model_ward_mstr->getdatabyid($data["new_ward_mstr_id"]);
                $data["new_ward_no"] = $data["ward_detail"]["ward_no"];
                if ($inputs["rdo_new_ward_no"] == 1) {
                    $assess_new_ward_status = 'Correct';
                    $assess_new_ward_id = $data["new_ward_mstr_id"];
                } else {
                    $assess_new_ward_status = 'Incorrect';
                    $assess_new_ward_id = $inputs["new_ward_id"];
                }
                $data["assess_new_ward_status"] = $assess_new_ward_status;
                $data["assess_new_ward_detail"] = $this->model_ward_mstr->getdatabyid($assess_new_ward_id);
                $data["assess_new_ward_no"] = $data["assess_new_ward_detail"]["ward_no"];
                $data["assess_new_ward_id"] = $assess_new_ward_id;
                /**************   End New Ward ************/

                /****************  Zone ***************/

                if ($inputs["rdo_zone"] == 1) {
                    $assess_zone_status = 'Correct';
                    $assess_zone_id = $data["zone_id"];
                } else {
                    $assess_ward_status = 'Incorrect';
                    $assess_zone_id = $inputs["zone"];
                }
                $data["assess_zone_status"] = $assess_ward_status;

                $data["assess_zone_id"] = $assess_zone_id;
                /**************   End Zone ************/

                /**************   Property Type ************/

                $data["prop_type_detail"] = $this->model_prop_type_mstr->getdatabyid($data["prop_type_mstr_id"]);
                $data["property_type"] = $data["prop_type_detail"]["property_type"];
                if ($inputs["rdo_property_type"] == 1) {
                    $assess_proptype_status = 'Correct';
                    $assess_property_type_id = $data["prop_type_mstr_id"];
                } else {
                    $assess_proptype_status = 'Incorrect';
                    $assess_property_type_id = $inputs["property_type_id"];
                }
                $data["assess_proptype_status"] = $assess_proptype_status;
                $data["assess_prop_type_detail"] = $this->model_prop_type_mstr->getdatabyid($assess_property_type_id);
                $data["assess_property_type"] = $data["assess_prop_type_detail"]["property_type"];
                $data["assess_property_type_id"] = $assess_property_type_id;

                /**************  End Property Type ************/

                /**************   Area Of Plot ************/
                if ($inputs["rdo_area_of_plot"] == 1) {
                    $assess_areaplot_status = 'Correct';
                    $assess_area_of_plot = $data["area_of_plot"];
                } else {
                    $assess_areaplot_status = 'Incorrect';
                    $assess_area_of_plot = $inputs["area_of_plot"];
                }
                $data["assess_areaplot_status"] = $assess_areaplot_status;
                $data["assess_area_of_plot"] = $assess_area_of_plot;

                /**************   End Area Of Plot ************/

                /**************   Road Type ************/
                $data["road_type_detail"] = $this->model_road_type_mstr->getdatabyid($data["road_type_mstr_id"]);
                $data["road_type"] = $data["road_type_detail"]["road_type"];

                if ($inputs["rdo_street_type"] == 1) {
                    $assess_roadtype_status = 'Correct';
                    $assess_road_type_id = $data["road_type_mstr_id"];
                } else {
                    $assess_roadtype_status = 'Incorrect';
                    $assess_road_type_id = $inputs["street_type_id"];
                }
                $data["assess_roadtype_status"] = $assess_roadtype_status;
                $data["assess_road_type_detail"] = $this->model_road_type_mstr->getdatabyid($assess_road_type_id);
                $data["assess_road_type"] = $data["assess_road_type_detail"]["road_type"];
                $data["assess_road_type_id"] = $assess_road_type_id;

                /**************  End Road Type ************/

                /**************   Floor Details ************/
                $data["floor_details"] = $this->model_view_saf_floor_details->getDataBySafDtlId_md5($id);
                $data["no_of_floor"] = sizeof($data["floor_details"]);
                // if ($inputs["rdo_total_floors"] == 1) {
                //     $assess_nofloor_status = 'Correct';
                //     $assess_no_of_floor = $data["no_of_floor"];
                // } else {
                //     $assess_nofloor_status = 'Incorrect';
                //     $assess_no_of_floor = $inputs["total_floors"];
                // }
                // $data["assess_nofloor_status"] = $assess_nofloor_status;
                // $data["assess_no_of_floor"] = $assess_no_of_floor;



                $ctr = 0;
                //print_var($data["assess_usage_type_detail"]);
                            
                if((isset($inputs['rdo_property_type']) && $inputs['rdo_property_type']==1))
                { 
                    foreach ($data["floor_details"] as $key => $value) {
                        $ctr++;

                        /**********  Usage Type     *************/
                        if ($inputs["rdo_usage_type" . $ctr] == 1) {
                            $assess_usage_type_status = 'Correct';
                            $assess_usage_type_id = $value["usage_type_mstr_id"];
                        } else {
                            $assess_usage_type_status = 'Incorrect';
                            $assess_usage_type_id = $inputs["usagetypeid_" . $ctr];
                        }
                        $data["floor_details"][$key]["assess_usage_type_status"] = $assess_usage_type_status;
                        $data["assess_usage_type_detail"] = $this->model_usage_type_mstr->getolddatabyid($assess_usage_type_id);
                        $data["floor_details"][$key]["assess_usage_type"] = $data["assess_usage_type_detail"]["usage_type"];
                        $data["floor_details"][$key]["assess_usage_type_id"] = $data["assess_usage_type_detail"]["id"];

                        /**********  End Usage Type     *************/

                        /**********   Occupency Type     *************/

                        if ($inputs["rdo_occupancy_type" . $ctr] == 1) {
                            $assess_occupancy_type_status = 'Correct';
                            $assess_occupancy_type_id = $value["occupancy_type_mstr_id"];
                        } else {
                            $assess_occupancy_type_status = 'Incorrect';
                            $assess_occupancy_type_id = $inputs["occupancytypeid_" . $ctr];
                        }
                        $data["floor_details"][$key]["assess_occupancy_type_status"] = $assess_occupancy_type_status;
                        $data["assess_occupancy_type_detail"] = $this->model_occupancy_type_mstr->getdatabyid($assess_occupancy_type_id);
                        $data["floor_details"][$key]["assess_occupancy_type"] = $data["assess_occupancy_type_detail"]["occupancy_name"];
                        $data["floor_details"][$key]["assess_occupancy_type_id"] = $data["assess_occupancy_type_detail"]["id"];

                        /**********  End Occupency Type     *************/

                        /**********  Construction Type     *************/

                        if ($inputs["rdo_construction_type" . $ctr] == 1) {
                            $assess_const_type_status = 'Correct';
                            $assess_const_type_id = $value["const_type_mstr_id"];
                        } else {
                            $assess_const_type_status = 'Incorrect';
                            $assess_const_type_id = $inputs["consttypeid" . $ctr];
                        }
                        $data["floor_details"][$key]["assess_const_type_status"] = $assess_usage_type_status;
                        $data["assess_const_type_detail"] = $this->model_const_type_mstr->getdatabyid($assess_const_type_id);
                        /********** End Construction Type     *************/

                        /********** Buildup Area     *************/
                        $data["floor_details"][$key]["assess_const_type"] = $data["assess_const_type_detail"]["construction_type"];
                        $data["floor_details"][$key]["assess_const_type_id"] = $data["assess_const_type_detail"]["id"];

                        if ($inputs["rdo_builtup_area" . $ctr] == 1) {
                            $assess_builtup_area_status = 'Correct';
                            $assess_builtup_area = $value["builtup_area"];
                        } else {
                            $assess_builtup_area_status = 'Incorrect';
                            $assess_builtup_area = $inputs["builtuparea_" . $ctr];
                        }
                        $data["floor_details"][$key]["assess_builtup_area_status"] = $assess_builtup_area_status;
                        $data["floor_details"][$key]["assess_builtup_area"] = $assess_builtup_area;

                        if ($inputs["rdo_completion_date" . $ctr] == 1) {
                            $assess_completion_date_status = 'Correct';
                            $assess_completion_date = $value["date_from"];
                        } else {
                            $assess_completion_date_status = 'Incorrect';
                            $assess_completion_date = $inputs["completion_date" . $ctr];
                        }
                        $data["floor_details"][$key]["assess_completion_date_status"] = $assess_completion_date_status;
                        $data["floor_details"][$key]["assess_completion_date"] = $assess_completion_date;
                        /********** End Buildup Area     *************/
                    }

                }
                elseif((isset($inputs['rdo_property_type']) && $inputs['rdo_property_type']==0) && (isset($inputs['hid_property_type']) && $inputs['hid_property_type']!=4) && (isset($inputs['property_type_id']) && $inputs['property_type_id']!=4))
                {  
                    foreach ($data["floor_details"] as $key => $value) {
                        $ctr++;

                        /**********  Usage Type     *************/
                        if ($inputs["rdo_usage_type" . $ctr] == 1) {
                            $assess_usage_type_status = 'Correct';
                            $assess_usage_type_id = $value["usage_type_mstr_id"];
                        } else {
                            $assess_usage_type_status = 'Incorrect';
                            $assess_usage_type_id = $inputs["usagetypeid_" . $ctr];
                        }
                        $data["floor_details"][$key]["assess_usage_type_status"] = $assess_usage_type_status;
                        $data["assess_usage_type_detail"] = $this->model_usage_type_mstr->getolddatabyid($assess_usage_type_id);
                        $data["floor_details"][$key]["assess_usage_type"] = $data["assess_usage_type_detail"]["usage_type"];
                        $data["floor_details"][$key]["assess_usage_type_id"] = $data["assess_usage_type_detail"]["id"];

                        /**********  End Usage Type     *************/

                        /**********   Occupency Type     *************/

                        if ($inputs["rdo_occupancy_type" . $ctr] == 1) {
                            $assess_occupancy_type_status = 'Correct';
                            $assess_occupancy_type_id = $value["occupancy_type_mstr_id"];
                        } else {
                            $assess_occupancy_type_status = 'Incorrect';
                            $assess_occupancy_type_id = $inputs["occupancytypeid_" . $ctr];
                        }
                        $data["floor_details"][$key]["assess_occupancy_type_status"] = $assess_occupancy_type_status;
                        $data["assess_occupancy_type_detail"] = $this->model_occupancy_type_mstr->getdatabyid($assess_occupancy_type_id);
                        $data["floor_details"][$key]["assess_occupancy_type"] = $data["assess_occupancy_type_detail"]["occupancy_name"];
                        $data["floor_details"][$key]["assess_occupancy_type_id"] = $data["assess_occupancy_type_detail"]["id"];

                        /**********  End Occupency Type     *************/

                        /**********  Construction Type     *************/

                        if ($inputs["rdo_construction_type" . $ctr] == 1) {
                            $assess_const_type_status = 'Correct';
                            $assess_const_type_id = $value["const_type_mstr_id"];
                        } else {
                            $assess_const_type_status = 'Incorrect';
                            $assess_const_type_id = $inputs["consttypeid" . $ctr];
                        }
                        $data["floor_details"][$key]["assess_const_type_status"] = $assess_usage_type_status;
                        $data["assess_const_type_detail"] = $this->model_const_type_mstr->getdatabyid($assess_const_type_id);
                        /********** End Construction Type     *************/

                        /********** Buildup Area     *************/
                        $data["floor_details"][$key]["assess_const_type"] = $data["assess_const_type_detail"]["construction_type"];
                        $data["floor_details"][$key]["assess_const_type_id"] = $data["assess_const_type_detail"]["id"];

                        if ($inputs["rdo_builtup_area" . $ctr] == 1) {
                            $assess_builtup_area_status = 'Correct';
                            $assess_builtup_area = $value["builtup_area"];
                        } else {
                            $assess_builtup_area_status = 'Incorrect';
                            $assess_builtup_area = $inputs["builtuparea_" . $ctr];
                        }
                        $data["floor_details"][$key]["assess_builtup_area_status"] = $assess_builtup_area_status;
                        $data["floor_details"][$key]["assess_builtup_area"] = $assess_builtup_area;

                        /********** End Buildup Area     *************/
                    } 
                }
                
                


                /********** End Floor Detalis     *************/
                /********** New Floor Added Details ***************/

                $newfloor = [];
                if (isset($inputs["chkfloor"]) && $inputs["chkfloor"] == 'on')
                {
                    $data["chkfloor"] = $inputs["chkfloor"];
                    $data["countarea"] = sizeof($inputs["floor_id"]);

                    for ($cnt = 0; $cnt < sizeof($inputs["floor_id"]); $cnt++)
                    {
                        $newfloor[$cnt]["floor_id"] = $inputs["floor_id"][$cnt];
                        $newfloor[$cnt]["use_type_id"] = $inputs["use_type_id"][$cnt];
                        $newfloor[$cnt]["occupancy_type_id"] = $inputs["occupancy_type_id"][$cnt];
                        $newfloor[$cnt]["construction_type_id"] = $inputs["construction_type_id"][$cnt];
                        $newfloor[$cnt]["builtup_area"] = $inputs["builtup_area"][$cnt];

                        // 2 TENANTED, 1 Residential
                        if ($inputs["use_type_id"] == 1)
                        {
                            $ncarpet_area = $inputs["builtup_area"][$cnt] * 0.7;
                        } else {
                            $ncarpet_area = $inputs["builtup_area"][$cnt] * 0.8;
                        }


                        $newfloor[$cnt]["carpet_area"] = $ncarpet_area;
                        $newfloor[$cnt]["occ_mm"] = $inputs["occ_mm"][$cnt];
                        $newfloor[$cnt]["occ_yyyy"] = explode("-", $inputs["occ_mm"][$cnt])[0];
                        $floor_list = $this->model_floor_mstr->getdatabyid($inputs["floor_id"][$cnt]);
                        $newfloor[$cnt]["floor_name"] = $floor_list["floor_name"];
                        $usage_type_list = $this->model_usage_type_mstr->getdatabyid($inputs["use_type_id"][$cnt]);
                        $newfloor[$cnt]["usage_type"] = $usage_type_list["usage_type"];
                        $occupancy_type_list = $this->model_occupancy_type_mstr->getdatabyid($inputs["occupancy_type_id"][$cnt]);
                        $newfloor[$cnt]["occupancy_name"] = $occupancy_type_list["occupancy_name"];
                        $construction_type_list = $this->model_const_type_mstr->getdatabyid($inputs["construction_type_id"][$cnt]);
                        $newfloor[$cnt]["construction_type"] = $construction_type_list["construction_type"];
                    }

                }
                $data["newfloor"] = $newfloor;

                /********** End New Floor Added Details ***************/

                /***************   hoarding          *******************/

                if ($data["is_hoarding_board"] == 't') {
                    $assessed_has_hording = 'Yes';
                    $data["assess_hoarding_area"] = $data["hoarding_area"];
                    $data["assess_hoarding_installation_date"] = $data["hoarding_installation_date"];
                } else {
                    $assessed_has_hording = 'No';
                }
                $data["assessed_has_hording"] = $assessed_has_hording;

                if ($inputs["has_hording"] == 1) {
                    $assess_has_hording = 'Yes';
                    $data["assess_hoarding_area"] = $inputs["total_hording_area"];
                    $data["assess_hoarding_installation_date"] = $inputs["hording_installation_date"];
                } else {
                    $assess_has_hording = 'No';
                }
                $data["assess_has_hording"] = $assess_has_hording;
                $data["assess_has_hording_status"] = $inputs["has_hording"];

                /************** end hording            *************/

                /*********       mobile tower         *****/
                if ($data["is_mobile_tower"] == 't') {
                    $assessed_is_mobile_tower = 'Yes';
                    $data["assess_mobile_tower_area"] = $data["tower_area"];
                    $data["assess_mobile_tower_installation_date"] = $data["tower_installation_date"];
                } else {
                    $assessed_is_mobile_tower = 'No';
                }
                $data["assessed_has_mobile_tower"] = $assessed_is_mobile_tower;

                if ($inputs["has_mobile_tower"] == 1) {
                    $assess_has_mobile_tower = 'Yes';
                    $data["assess_mobile_tower_area"] = $inputs["total_tower_area"];
                    $data["assess_mobile_tower_installation_date"] = $inputs["tower_installation_date"];
                } else {
                    $assess_has_mobile_tower = 'No';
                }
                $data["assess_has_mobile_tower"] = $assess_has_mobile_tower;
                $data["assess_has_mobile_tower_status"] = $inputs["has_mobile_tower"];

                /*********        end mobile tower           *********/
                /*************** petrol Pump      ***************/
                $data["is_petrol_pump"] = $data["Saf_detail"]["is_petrol_pump"];
                $data["under_ground_area"] = $data["Saf_detail"]["under_ground_area"];
                $data["petrol_pump_completion_date"] = $data["Saf_detail"]["petrol_pump_completion_date"];

                if ($data["is_petrol_pump"] == 't') {
                    $assessed_is_petrol_pump = 'Yes';
                    $data["assess_petrol_pump_area"] = $data["under_ground_area"];
                    $data["assess_petrol_pump_installation_date"] = $data["petrol_pump_completion_date"];
                } else {
                    $assessed_is_petrol_pump = 'No';
                }
                $data["assessed_is_petrol_pump"] = $assessed_is_petrol_pump;


                if (isset($inputs["is_petrol_pump"]) && $inputs["is_petrol_pump"] == 1) {
                    $assess_is_petrol_pump = 'Yes';
                    $data["assess_petrol_pump_area"] = $inputs["under_ground_area"];
                    $data["assess_petrol_pump_installation_date"] = $inputs["petrol_pump_completion_date"];
                } else {
                    $assess_is_petrol_pump = 'No';
                    $inputs["is_petrol_pump"]=0;
                }

                $data["assess_is_petrol_pump"] = $assess_is_petrol_pump;
                $data["assess_is_petrol_pump_status"] = $inputs["is_petrol_pump"];

                /**************   end petrol pump    ****************/

                /*************** RainWater Harveting    ***************/
                if ($data["is_water_harvesting"] == 't') {
                    $assessed_water_harvesting = 'Yes';
                } else {
                    $assessed_water_harvesting = 'No';
                }
                $data["assessed_water_harvesting"] = $assessed_water_harvesting;

                if (isset($inputs["rdo_water_harvesting"]) && $inputs["rdo_water_harvesting"] == 1)
                {
                    $assess_water_harvesting_select = 'Correct';
                    if ($inputs["hid_water_harvesting"] == 1) {
                        $assess_water_harvesting = 'Yes';
                    } else {
                        $assess_water_harvesting = 'No';
                    }
                }
                else
                {
                    $assess_water_harvesting_select = 'Incorrect';

                    // if (isset($inputs["water_harvesting"]) && $inputs["water_harvesting"] == 1) 
                    if(isset($inputs["rdo_water_harvesting"]) && $inputs["hid_water_harvesting"] != 1) 
                    {
                        $assess_water_harvesting = 'Yes';
                    }
                    else
                    {
                        $assess_water_harvesting = 'No';

                        # In case of vacant land
                        $inputs["rdo_water_harvesting"]=0;
                        $inputs["hid_water_harvesting"]=0;
                    }
                }

                $data["assess_water_harvesting_select"] = $assess_water_harvesting_select;
                $data["assess_water_harvesting_status_select"] = $inputs["rdo_water_harvesting"];


                $data["assess_water_harvesting"] = $assess_water_harvesting;
                // $data["assess_water_harvesting_status"] = $inputs["hid_water_harvesting"];
                $data["assess_water_harvesting_status"] = ($assess_water_harvesting=='Yes'?1:0)??0;



                /**************   end RainWater Harveting    ****************/

                /******************** Added Floor Details*********************/
                $data["fieldVerificationmstr_detail"] = $this->model_field_verification_dtl->getdatabysafid($data["Saf_dtl_id"]);
                if($data["fieldVerificationmstr_detail"])
                {
                    $data["vward_detail"] = $this->model_ward_mstr->getdatabyid($data["fieldVerificationmstr_detail"]["ward_mstr_id"]);
                    $data["vnew_ward_detail"] = $this->model_ward_mstr->getdatabyid($data["fieldVerificationmstr_detail"]["new_ward_mstr_id"]);
                    $data["vprop_type_detail"] = $this->model_prop_type_mstr->getdatabyid($data["fieldVerificationmstr_detail"]["prop_type_mstr_id"]);
                    $data["vzone_id"] = $data["fieldVerificationmstr_detail"]["zone_mstr_id"];
                    $data["vroad_type_detail"] = $this->model_road_type_mstr->getdatabyid($data["fieldVerificationmstr_detail"]["road_type_mstr_id"]);
                    $data["vward_no"] = $data["vward_detail"]["ward_no"];
                    $data["vward_mstr_id"] = $data["vward_detail"]["id"];
                    $data["vnew_ward_no"] = $data["vnew_ward_detail"]["ward_no"];
                    $data["vnew_ward_mstr_id"] = $data["vnew_ward_detail"]["id"];
                    $data["vproperty_type"] = $data["vprop_type_detail"]["property_type"];
                    $data["vroad_type"] = $data["vroad_type_detail"]["road_type"];
                    $data["varea_of_plot"] = $data["fieldVerificationmstr_detail"]["area_of_plot"];
                    $data["vis_hoarding_board"] = $data["fieldVerificationmstr_detail"]["is_hoarding_board"];
                    $data["vhoarding_area"] = $data["fieldVerificationmstr_detail"]["hoarding_area"];
                    $data["vhoarding_installation_date"] = $data["fieldVerificationmstr_detail"]["hoarding_installation_date"];
                    $data["vis_mobile_tower"] = $data["fieldVerificationmstr_detail"]["is_mobile_tower"];
                    $data["vtower_area"] = $data["fieldVerificationmstr_detail"]["tower_area"];
                    $data["vtower_installation_date"] = $data["fieldVerificationmstr_detail"]["tower_installation_date"];
                    $data["vis_petrol_pump"] = $data["fieldVerificationmstr_detail"]["is_petrol_pump"];
                    //dd($data["fieldVerificationmstr_detail"]["is_petrol_pump"]);
                    $data["vunder_ground_area"] = $data["fieldVerificationmstr_detail"]["under_ground_area"];
                    $data["vpetrol_pump_completion_date"] = $data["fieldVerificationmstr_detail"]["petrol_pump_completion_date"];
                    $data["vis_water_harvesting"] = $data["fieldVerificationmstr_detail"]["is_water_harvesting"];
                    //$data["vland_occupation_date"] = $data["fieldVerificationmstr_detail"]["land_occupation_date"];
                }
                //dd($data["floor_details"]);
                foreach ($data["floor_details"] as $key => $value)
                {
                    $data["floor_details"][$key]["vfloor_details"] = $this->model_field_verification_floor_details->getDataBymstsaffloorId($data["fieldVerificationmstr_detail"]["id"], $value["id"]);
                }
                $data["vfloor_details_added"] = $this->model_field_verification_floor_details->getDataBymstsaffloorId($data["fieldVerificationmstr_detail"]["id"], 0);
                $data["no_of_addedfloor"] = sizeof($data["vfloor_details_added"]);
                /*******************End Added Floor Details*******************/



                // dd($data,$inputs);
                if (isset($inputs["btn_submit"]))
                {
                    $geoTag = $this->db->query("select distinct (direction_type) as direction_type from tbl_saf_geotag_upload_dtl where geotag_dtl_id = ".$data['Saf_dtl_id']." AND status =1 ")->getResultArray();
                    $data["is_geo_tag_done"] = sizeof($geoTag)>=3 ? true : false;
                    return view('mobile/Property/FieldVerification/verificationDetails', $data);
                }
                if (isset($inputs["back"]))
                {
                    return view('mobile/Property/FieldVerification/fieldVerification', $data);
                }

                if (isset($inputs["Save"]))
                {
                    // start check level details is already send in level
                    $sql = "SELECT * FROM tbl_level_pending_dtl WHERE id='".$levelid."' AND verification_status!=0";

                    if ($checkLevelDtl = $this->db->query($sql)->getFirstRow("array")) {
                        if ($user_type_mstr_id == 7) {
                            return $this->response->redirect(base_url('SafVerification/verificationview/' . $id . '/' . $checkLevelDtl["id"]));
                        } else {

                            return $this->response->redirect(base_url('SafVerification/verificationview/' . $id . '/' . $checkLevelDtl["id"]));

                        // return $this->response->redirect(base_url('SafVerification/verificationUpload/' . $id . '/' . $checkLevelDtl["id"]));
                        }
                    }

                    // end check level details is already send in level
                    $this->db->transBegin();
                    $checkAgencyData = $this->model_field_verification_dtl->getulbdatabysafid($data["Saf_dtl_id"], 'ULB TC');
                    if ($user_type_mstr_id == 7)
                    {
                        $tcType = 'ULB TC';
                        $receiver_user_type_id = 9;
                    }
                    else
                    {
                        $tcType = 'AGENCY TC';
                        $receiver_user_type_id = 7;
                        if(!$checkAgencyData && $data["Saf_detail"]["apply_date"]>='2025-01-14' && $data["Saf_detail"]["doc_verify_status"]==0){
                            $receiver_user_type_id = 11;
                        }
                        elseif($checkAgencyData){
                            $receiver_user_type_id=5;
                        }
                    }

                    $insert = [
                        'saf_dtl_id' => $data["Saf_dtl_id"],
                        'ward_mstr_id' => $assess_ward_id,
                        'new_ward_mstr_id' => $assess_new_ward_id,
                        'zone_mstr_id' => $assess_zone_id,
                        'prop_type_mstr_id' => $assess_property_type_id,
                        'area_of_plot' => $assess_area_of_plot,
                        'road_type_mstr_id' => $assess_road_type_id,
                        'is_mobile_tower' => $inputs["has_mobile_tower"] == 1 ? 't' : 'f',
                        'tower_area' => $inputs["total_tower_area"] ?? NULL,
                        'tower_installation_date' => $inputs["tower_installation_date"] ?? NULL,
                        'is_hoarding_board' => $inputs["has_hording"] == 1 ? 't' : 'f',
                        'hoarding_area' => (isset($data["assess_hoarding_area"]) && $data["assess_hoarding_area"]!="") ? $data["assess_hoarding_area"] :NULL,
                        'hoarding_installation_date' => (isset($data["assess_hoarding_installation_date"]) && $data["assess_hoarding_installation_date"]!="") ? $data["assess_hoarding_installation_date"] :NULL,
                        'is_petrol_pump' => $inputs["is_petrol_pump"] == 1 ? 't' : 'f',
                        'under_ground_area' => $data["assess_petrol_pump_area"] ?? NULL,
                        'petrol_pump_completion_date' => $data["assess_petrol_pump_installation_date"] ?? NULL,
                        'is_water_harvesting' =>  (isset($inputs["water_harvesting"]) && ($inputs["water_harvesting"] == 1)) ? 't' : 'f',
                        'verified_by_emp_details_id' => $emp_details_id,
                        'created_on' => date('Y-m-d H:i:s'),
                        'verified_by' => $tcType,
                        "percentage_of_property_transfer" => $inputs["percentage_of_property"] ?? NULL,
                    ];

                    $field_mstr_id = $this->model_field_verification_dtl->insertData($insert);

                    if (isset($field_mstr_id))
                    {
                        $ctr = $inputs["countdata"] ?? 0;
                        for ($i = 1; $i < $ctr; $i++)
                        {
                            if ($inputs["usagetypeid_" . $i] == 1) {
                                $carpet_area = $inputs["builtuparea_" . $i] * 0.7;
                            } else {
                                $carpet_area = $inputs["builtuparea_" . $i] * 0.8;
                            }

                            if ($inputs["completiondate_upto" . $i] == '')
                                $date_upto = NULL;
                            else
                                $date_upto = $inputs["completiondate_upto" . $i];


                            $insertfloor = [
                                'field_verification_dtl_id' => $field_mstr_id,
                                'saf_dtl_id' => $data["Saf_dtl_id"],
                                'saf_floor_dtl_id' => $inputs["saf_floor_dtl_id" . $i],
                                'floor_mstr_id' => $inputs["floorid" . $i],
                                'usage_type_mstr_id' => $inputs["usagetypeid_" . $i],
                                'const_type_mstr_id' => $inputs["consttypeid" . $i],
                                'occupancy_type_mstr_id' => $inputs["occupancytypeid_" . $i],
                                'builtup_area' => $inputs["builtuparea_" . $i],
                                'carpet_area' => $carpet_area,
                                'date_from' => $inputs["completion_date" . $i]."-01",
                                'date_upto' => $date_upto,
                                'emp_details_id' => $emp_details_id,
                                'created_on' => date('Y-m-d H:i:s'),
                                'verified_by' => $tcType
                            ];

                            $this->model_field_verification_floor_details->insertData($insertfloor);
                        }

                        if ($inputs["chkfloor"] == 'on')
                        {
                            for ($cntq = 0; $cntq < sizeof($inputs["floor_id"]); $cntq++)
                            {
                                if ($inputs["use_type_id"][$cntq] == 1)
                                {
                                    $carpet_area = $inputs["builtup_area"][$cntq] * 0.7;
                                }
                                else
                                {
                                    $carpet_area = $inputs["builtup_area"][$cntq] * 0.8;
                                }

                                $yy = $inputs["occ_yyyy"][$cntq];
                                $mm = $inputs["occ_mm"][$cntq];
                                $data_fromas = $mm . '-01';


                                $ninsertfloor = [
                                    'field_verification_dtl_id' => $field_mstr_id,
                                    'saf_dtl_id' => $data["Saf_dtl_id"],
                                    'saf_floor_dtl_id' => 0,
                                    'floor_mstr_id' => $inputs["floor_id"][$cntq],
                                    'usage_type_mstr_id' => $inputs["use_type_id"][$cntq],
                                    'const_type_mstr_id' => $inputs["construction_type_id"][$cntq],
                                    'occupancy_type_mstr_id' => $inputs["occupancy_type_id"][$cntq],
                                    'builtup_area' => $inputs["builtup_area"][$cntq],
                                    'carpet_area' => $carpet_area,
                                    'date_from' => $data_fromas,
                                    'date_upto' => NULL,
                                    'emp_details_id' => $emp_details_id,
                                    'created_on' => date('Y-m-d H:i:s'),
                                    'verified_by' => $tcType
                                ];

                                $this->model_field_verification_floor_details->insertData($ninsertfloor);
                            }
                        }


                        $leveldata = [
                            'remarks' => 'Verified',
                            'level_pending_dtl_id' => $levelid,
                            'saf_dtl_id' => $data["Saf_dtl_id"],
                            'emp_details_id' => $emp_details_id,
                            'created_on' => date('Y-m-d H:i:s'),
                            'sender_user_type_id' => $user_type_mstr_id,
                            'receiver_user_type_id' => $receiver_user_type_id,
                            'forward_date' => date('Y-m-d'),
                            'forward_time' => date('H:i:s'),
                            'verification_status' => 1,
                            'status'=> 0,
                            'sender_emp_details_id' => $sender_emp_details_id,
                            'receiver_emp_details_id' => $sender_emp_details_id,

                        ];
                        // start water harvesting image upload 
                        if (isset($inputs["water_harvesting"]) && ($inputs["water_harvesting"] == 1)) {
                            $ulb_folder_name = "RANCHI";
                            $upload_type = ($user_type_mstr_id == 7)?"UTC Field Verification":"Field Verification";
                            $destination_path = "field_verification";
                            $WHfile = $this->request->getFile('water_harvesting_image_path');//print_var($leftfile);//move_uploaded_file($_FILES['file']['tmp_name'], WRITEPATH . 'uploads/' . $ulb_folder_name . '/' . $temp_path . "/" . $tmp_file_name . '.' . $leftfile->getExtension());return;
                            $validated = $this->validate([
                                'file' => [
                                    'uploaded[water_harvesting_image_path]',
                                    'mime_in[water_harvesting_image_path,image/jpeg,image/jpg]',
                                    'max_size[water_harvesting_image_path,5242880]',
                                    'ext_in[water_harvesting_image_path,jpg,jpeg]',
                                ],
                            ]);
                            if ($validated && !$WHfile->hasMoved())
                            { 
                                $inputsData = [
                                    "geotag_dtl_id" => $data["Saf_dtl_id"],
                                    "latitude" => $this->request->getVar("water_harvesting_image_path_latitude_text"),
                                    "longitude" => $this->request->getVar("water_harvesting_image_path_longitude_text"),
                                    "direction_type" => "Water Harvesting",
                                    "upload_type" => $upload_type,
                                    "created_by_emp_details_id" => $sender_emp_details_id,
                                    "created_on" => date('Y-m-d H:i:s'),
                                    "status" => 1,
                                ];
                                if ($this->db->table("tbl_saf_geotag_upload_dtl")->insert($inputsData)) {
                                    $saf_geotag_upload_dtl_id = $this->db->insertId();
                                    $tmp_file_name = md5($saf_geotag_upload_dtl_id);
                                    $file_ext = $WHfile->getExtension();
                                    $ftPath = '/' . $ulb_folder_name . '/' . $destination_path . "/" . $tmp_file_name . '.' . $file_ext;

                                    if ($WHfile->move(WRITEPATH . 'uploads/' . $ulb_folder_name . '/' . $destination_path . '/', $tmp_file_name . '.' . $file_ext))
                                    {
                                        $this->db->table("tbl_saf_geotag_upload_dtl")
                                                    ->where("id", $saf_geotag_upload_dtl_id)
                                                    ->update(["image_path"=>$ftPath]);
                                    }
                                }
                            }
                        }
                        // end water harvesting image upload 
                        if(isset($_POST["remarks"]) && $_POST["remarks"]!="")
                        {
                            $leveldata['remarks'] = trim($_POST["remarks"]);
                        }

                        if($checkAgencyData && $user_type_mstr_id == 5){
                            $this->model_level_pending_dtl->bugfix_level_pending_new($leveldata); //Shashi
                        }else{
                            $this->model_level_pending_dtl->bugfix_level_pending($leveldata); //Shashi
                        } 

                        if ($updatelevelpending = $this->model_level_pending_dtl->updatelevelpendingById($leveldata))
                        {
                            // $leveldata['remarks'] =null;
                            if (($checkAgencyData && $user_type_mstr_id == 5)?$this->model_level_pending_dtl->insrtSILevelFinaldtl($leveldata):$this->model_level_pending_dtl->insrtlevelpendingdtl($leveldata))
                            {
                                if($this->db->transStatus() === false)
                                {
                                    flashToast("message", "Something went wrong");
                                    $this->db->transRollback();
                                }
                                else
                                {
                                    $this->db->transCommit();
                                    flashToast("message", "Verified Successfully");

                                    if ($user_type_mstr_id == 7)
                                    {
                                        return $this->response->redirect(base_url('SafVerification/verificationview/' . $id . '/' . $levelid));
                                    }
                                    else
                                    {
                                    // dd($data["Saf_detail"]);
                                        $application = $data["Saf_detail"];

                                        $vistingRepostInput = safGeoVisit($application,$this->request->getVar());
                                        $visiting_dtl_id = $this->model_visiting_dtl->insertdetail($vistingRepostInput);
                                        return $this->response->redirect(base_url('SafVerification/verificationview/' . $id . '/' . $levelid));

                                    //old// return $this->response->redirect(base_url('SafVerification/verificationUpload/' . $id . '/' . $levelid));
                                    }
                                }
                            }
                        }
                        
                    }
                }

                return view('mobile/Property/FieldVerification/verificationDetails', $data);
            }
            else
            {
                $data["ward_list"] = $this->model_view_ward_permission->getPermittedWardWithSession($emp_details_id);
                $data["Saf_detail"] = $this->model_saf_dtl->SafDetailsById($id);
                if ($data["Saf_detail"]["previous_holding_id"]!="" && $data["Saf_detail"]["assessment_type"]=="Mutation") {
                    $transferModeList = $this->model_transfer_mode_mstr->getTransferModeList();
                    $transferModeId = array_search($data["Saf_detail"]["transfer_mode_mstr_id"], array_column($transferModeList, 'id'));
                    $data["transfer_mode"] = $transferModeList[$transferModeId]["transfer_mode"];
                    $sql = "SELECT * FROM tbl_prop_owner_detail WHERE prop_dtl_id=".$data["Saf_detail"]["previous_holding_id"];
                    if($prev_prop_owner_result = $this->db->query($sql)->getResultArray()) {
                        $data["prev_prop_owner_dtl"] = $prev_prop_owner_result;
                    }
                }

                $data["Saf_dtl_id"] = $data["Saf_detail"]["id"];
                $data["saf_no"] = $data["Saf_detail"]["saf_no"];
                $data["assessment_type"]= $data["Saf_detail"]["assessment_type"];
                $data["zone_id"] = $data["Saf_detail"]["zone_mstr_id"];
                $data["ward_mstr_id"] = $data["Saf_detail"]["ward_mstr_id"];
                $data["ward_list_mapp"] = $this->model_view_ward_mapping_mstr->getNewWardLIstByOldWardMstrId($data["ward_mstr_id"]);
                $mapp = array_map(function($val)
                        {
                            $val['ward_mstr_id'] = $val['id'];
                            return  $val;                            
                        },$data["ward_list_mapp"]);
                $data["ward_list_mapp"]=$mapp;
                $data["new_ward_mstr_id"] = $data["Saf_detail"]["new_ward_mstr_id"];
                $data["prop_type_mstr_id"] = $data["Saf_detail"]["prop_type_mstr_id"];
                $data["road_type_mstr_id"] = $data["Saf_detail"]["road_type_mstr_id"];
                $data["apply_date"] = $data["Saf_detail"]["apply_date"];
                $data["plot_no"] = $data["Saf_detail"]["plot_no"];
                $data["area_of_plot"] = $data["Saf_detail"]["area_of_plot"];
                $data["Khata_no"] = $data["Saf_detail"]["khata_no"];
                $data["village_mauja_name"] = $data["Saf_detail"]["village_mauja_name"];
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
                $data["ward_detail"] = $this->model_ward_mstr->getdatabyid($data["ward_mstr_id"]);
                if ($new_ward_dtl = $this->model_ward_mstr->getdatabyid($data["new_ward_mstr_id"])) {
                    $data["new_ward_no"] = $new_ward_dtl["ward_no"];
                } else {
                    $data["new_ward_no"] = "N/A";
                }
                $data["prop_type_detail"] = $this->model_prop_type_mstr->getdatabyid($data["prop_type_mstr_id"]);
                $data["prop_type_list"] = $this->model_prop_type_mstr->getPropTypeList();
                $data["road_type_detail"] = $this->model_road_type_mstr->getdatabyid($data["road_type_mstr_id"]);
                $data["road_type_list"] = $this->model_road_type_mstr->getRoadTypeList();
                $data["ward_no"] = $data["ward_detail"]["ward_no"];
                $data["property_type"] = $data["prop_type_detail"]["property_type"];
                $data["road_type"] = $data["road_type_detail"]["road_type"];
                $data["owner_detail"] = $this->model_saf_owner_detail->ownerdetails_md5($id);
                $data["floor_details"] = $this->model_view_saf_floor_details->getDataBySafDtlId_md5($id);
                $data["usage_list"] = $this->model_usage_type_mstr->getUsageTypeList();
                $data["occupancy_list"] = $this->model_occupancy_type_mstr->getOccupancyTypeList();
                $data["const_type_list"] = $this->model_const_type_mstr->getConstTypeList();
                $data["floor_list"] = $this->model_floor_mstr->getFloorList();
                $data["no_of_floor"] = sizeof($data["floor_details"]);
                $data["tax_details"] = $this->model_saf_tax->getDataBySafDtlId_md5($id);


                /******************** Added Floor Details*********************/
                $data["fieldVerificationmstr_detail"] = $this->model_field_verification_dtl->getdatabysafid($data["Saf_dtl_id"]);

                if($data["fieldVerificationmstr_detail"])
                {
                    //print_r($data["fieldVerificationmstr_detail"]);
                    $data["vward_detail"] = $this->model_ward_mstr->getdatabyid($data["fieldVerificationmstr_detail"]["ward_mstr_id"]);
                    //$data["vnew_ward_detail"] = $this->model_ward_mstr->getdatabyid($data["fieldVerificationmstr_detail"]["ward_mstr_id"]);
                    if ($new_ward_dtl = $this->model_ward_mstr->getdatabyid($data["fieldVerificationmstr_detail"]["new_ward_mstr_id"])) {
                        $data["vnew_ward_no"] = $new_ward_dtl["ward_no"];
                    } else {
                        $data["vnew_ward_no"] = "N/A";
                    }
                    $data["vprop_type_detail"] = $this->model_prop_type_mstr->getdatabyid($data["fieldVerificationmstr_detail"]["prop_type_mstr_id"]);
                    $data["vroad_type_detail"] = $this->model_road_type_mstr->getdatabyid($data["fieldVerificationmstr_detail"]["road_type_mstr_id"]);
                    $data["vward_no"] = $data["vward_detail"]["ward_no"];
                    $data["vward_mstr_id"] = $data["vward_detail"]["id"];
                    $data["vproperty_type"] = $data["vprop_type_detail"]["property_type"];
                    $data["vroad_type"] = $data["vroad_type_detail"]["road_type"];
                    $data["vzone_id"] = $data["fieldVerificationmstr_detail"]["zone_mstr_id"];
                    $data["varea_of_plot"] = $data["fieldVerificationmstr_detail"]["area_of_plot"];
                    $data["vis_hoarding_board"] = $data["fieldVerificationmstr_detail"]["is_hoarding_board"];
                    $data["vhoarding_area"] = $data["fieldVerificationmstr_detail"]["hoarding_area"];
                    $data["vhoarding_installation_date"] = $data["fieldVerificationmstr_detail"]["hoarding_installation_date"];
                    $data["vis_mobile_tower"] = $data["fieldVerificationmstr_detail"]["is_mobile_tower"];
                    $data["vtower_area"] = $data["fieldVerificationmstr_detail"]["tower_area"];
                    $data["vtower_installation_date"] = $data["fieldVerificationmstr_detail"]["tower_installation_date"];
                    $data["vis_petrol_pump"] = $data["fieldVerificationmstr_detail"]["is_petrol_pump"];
                    $data["vunder_ground_area"] = $data["fieldVerificationmstr_detail"]["under_ground_area"];
                    $data["vpetrol_pump_completion_date"] = $data["fieldVerificationmstr_detail"]["petrol_pump_completion_date"];
                    $data["vis_water_harvesting"] = $data["fieldVerificationmstr_detail"]["is_water_harvesting"];
                    //$data["vland_occupation_date"] = $data["fieldVerificationmstr_detail"]["land_occupation_date"];
                    foreach ($data["floor_details"] as $key => $value) {
                        $data["floor_details"][$key]["vfloor_details"] = $this->model_field_verification_floor_details->getDataBymstsaffloorId($data["fieldVerificationmstr_detail"]["id"], $value["id"]);
                    }

                    $data["vfloor_details_added"] = $this->model_field_verification_floor_details->getDataBymstsaffloorId($data["fieldVerificationmstr_detail"]["id"], 0);

                    $data["no_of_addedfloor"] = sizeof($data["vfloor_details_added"]);
                    /*******************End Added Floor Details*******************/
                }

                return view('mobile/Property/FieldVerification/fieldVerification', $data);
            }
        }
    }


    public function verificationUpload($id = null, $levelid = null)
    {
        $data = (array)null;
        $session = session();

        $ulb_mstr = $session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $emp_mstr = $session->get("emp_details");
        $data['ulb_mstr_name'] = $this->model_ulb_mstr->getulb_list($data['ulb_mstr_id']);

        $ulb_folder_name = $data["ulb_mstr_name"]["city"];
        $login_emp_details_id = $emp_mstr["id"];
        if (is_numeric($id)) {
            $data["Saf_detail"] = $this->model_saf_dtl->SafDetailsById($id);
        } else {
            $data["Saf_detail"] = $this->model_saf_dtl->Saf_details_md5($id);
        }
        $data["Saf_dtl_id"] = $data["Saf_detail"]["id"];
        $checkAgencyData = $this->model_field_verification_dtl->getulbdatabysafid($data["Saf_dtl_id"], 'ULB TC');
        if(!$checkAgencyData && $data["Saf_detail"]["apply_date"]>='2025-01-14'){
            // return redirect("SafVerification/field_verification/".$data["Saf_dtl_id"]."/0");
            return $this->response->redirect(base_url('SafVerification/field_verification/' .$data["Saf_dtl_id"]."/0"));
        }

        $data["fieldVerificationmstr_detail"] = $this->model_field_verification_dtl->getdatabysafid($data["Saf_dtl_id"]);            //    print_r($data["fieldVerificationmstr_detail"]);
        $data["vis_hoarding_board"] = $data["fieldVerificationmstr_detail"]["is_hoarding_board"];
        $data["vis_mobile_tower"] = $data["fieldVerificationmstr_detail"]["is_mobile_tower"];
        $data["vis_petrol_pump"] = $data["fieldVerificationmstr_detail"]["is_petrol_pump"];
        $data["vis_water_harvesting"] = $data["fieldVerificationmstr_detail"]["is_water_harvesting"];

        $upload_type = 'Field Verification';

        $left_direction_type = 'Left';
        $data['left_image_exists'] = $this->model_saf_geotag_upload_dtl->check_distributed_image_details($id, $upload_type, $left_direction_type);
        $right_direction_type = 'Right';
        $data['right_image_exists'] = $this->model_saf_geotag_upload_dtl->check_distributed_image_details($id, $upload_type, $right_direction_type);
        $front_direction_type = 'Front';
        $data['front_image_exists'] = $this->model_saf_geotag_upload_dtl->check_distributed_image_details($id, $upload_type, $front_direction_type);
        $hoarding_direction_type = 'Hoarding';
        $data['hoarding_image_exists'] = $this->model_saf_geotag_upload_dtl->check_distributed_image_details($id, $upload_type, $hoarding_direction_type);
        $tower_direction_type = 'Mobile Tower';
        $data['tower_image_exists'] = $this->model_saf_geotag_upload_dtl->check_distributed_image_details($id, $upload_type, $tower_direction_type);
        $petrol_pump_direction_type = 'Pertol petrol_pump_direction_type';
        $data['petrol_pump_image_exists'] = $this->model_saf_geotag_upload_dtl->check_distributed_image_details($id, $upload_type, $petrol_pump_direction_type);
        $harvesting_direction_type = 'Water Harvesting';
        $data['harvesting_image_exists'] = $this->model_saf_geotag_upload_dtl->check_distributed_image_details($id, $upload_type, $harvesting_direction_type);



        if ($this->request->getMethod() == 'post') {
            $application = $data["Saf_detail"];							
            $vistingRepostInput = safGeoVisit($application,$this->request->getVar());     
            $visiting_dtl_id = $this->model_visiting_dtl->insertdetail($vistingRepostInput);
            return $this->response->redirect(base_url('SafVerification/verificationview/' . $id . '/' . $levelid));
        }
        return view('mobile/Property/FieldVerification/VerificationUpload', $data);

    }

    public function uploadGeoTagImg_Ajax()
    {
        $out=["status"=> false, "message"=> null];
        if($this->request->getMethod()=='post')
        {
            $Session = Session();
            $data["emp_details"]=$Session->get("emp_details");
            $data["ulb_dtl"]=$Session->get("ulb_dtl");

            $inputs = arrFilterSanitizeString($this->request->getVar());
            $direction_type=$inputs["direction_type"];
            $saf_dtl_id=$inputs["saf_dtl_id"];
            $login_emp_details_id=$data["emp_details"]["id"];
            $latlong=[
                "latitude"=> $inputs["latitude"],
                "longitude"=> $inputs["longitude"],
            ];

            $ulb_folder_name=$data["ulb_dtl"]["district"];
            $upload_type = 'Field Verification';
            $left_dt = date('dmYHis');
            $ltrand = mt_rand();
            $tmp_file_name = md5($left_dt . $ltrand);
            $temp_path = "field_verification_tmp";
            $destination_path = "field_verification";

            $leftfile = $this->request->getFile('file');//print_var($leftfile);//move_uploaded_file($_FILES['file']['tmp_name'], WRITEPATH . 'uploads/' . $ulb_folder_name . '/' . $temp_path . "/" . $tmp_file_name . '.' . $leftfile->getExtension());return;
            //if ($leftfile->IsValid() && !$leftfile->hasMoved())
            $validated = $this->validate([
                'file' => [
                    'uploaded[file]',
                    'mime_in[file,image/jpeg,image/jpg]',
                    'max_size[file,5242880]',
                    'ext_in[file,jpg,jpeg]',
                ],
            ]);
            if ($validated && !$leftfile->hasMoved())
            {
                $file_ext = $leftfile->getExtension();
                if ($leftfile->move(WRITEPATH . 'uploads/' . $ulb_folder_name . '/' . $temp_path . '/', $tmp_file_name . '.' . $file_ext))
                {
                    $file_path = WRITEPATH . 'uploads/' . $ulb_folder_name . '/' . $temp_path . "/" . $tmp_file_name . '.' . $file_ext;
                    $in= $this->latlonginsert($ulb_folder_name, $destination_path, $file_ext, $file_path, $tmp_file_name, $upload_type, $direction_type, $saf_dtl_id, $login_emp_details_id, $latlong);
                    // $out=["status"=> true, "message"=> "Image uploaded successfully"];
                    $out=["status"=> true, "message"=> "Image uploaded successfully",'path'=>$file_path,'insert_id'=>$in];
                }
            }
            elseif(!$validated)
            {
                $out=["status"=> false, "message"=>$leftfile->getSize()];
            }

        }
        echo json_encode($out);
    }
    public function latlonginsert($ulb_folder_name, $destination_path, $htfile_ext, $hoarding_image_temp_path, $lttmpFileName, $upload_type, $direction_type, $Saf_dtl_id, $login_emp_details_id, $imgltLocation)
    {
        //latitude & longitude

        $ftimgLat = $imgltLocation['latitude'];
        $ftimgLng = $imgltLocation['longitude'];
        $destinationFilePath = WRITEPATH . 'uploads/' . $ulb_folder_name . '/' . $destination_path . "/" . $lttmpFileName . '.' . $htfile_ext;
        $ftPath = '/' . $ulb_folder_name . '/' . $destination_path . "/" . $lttmpFileName . '.' . $htfile_ext;

        $server = (DocServer($hoarding_image_temp_path));
        $target_url =$server["server"] ."rename";
        $drive = $server["drive"];        
        $postData = ["destinationFilePath"=>((explode(WRITEPATH,$destinationFilePath))[1]??""),"hoarding_image_temp_path"=>((explode(WRITEPATH,$hoarding_image_temp_path))[1]??""),"drive"=>$drive];        
        $curl = curl_init();		
        curl_setopt($curl, CURLOPT_URL, $target_url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response =  json_decode(curl_exec($curl),true)??"";
        #if (rename($hoarding_image_temp_path, $destinationFilePath)) 
        if(isset($response["status"]) && $response["status"])
        {
            $data = [
                'geotag_dtl_id' => $Saf_dtl_id,
                'image_path' => $ftPath,
                'latitude' => $ftimgLat,
                'longitude' => $ftimgLng,
                'direction_type' => $direction_type,
                'upload_type' => $upload_type,
                'created_by_emp_details_id' => $login_emp_details_id,
                'created_on' => date('Y-m-d H:i:s')
            ];
            //print_r($data);
            return $insert_last_id = $this->model_saf_geotag_upload_dtl->insertData($data);
        }
    }



    public function verificationview($id = null, $levelid = null)
    {
        $data = (array)null;
        $Session = Session();
        $ulb_mstr = $Session->get("ulb_dtl");
        $data['ulb_mstr_id'] = $ulb_mstr["ulb_mstr_id"];
        $data["levelId"]=$levelid;
        if ($id <> null && $levelid <> null)
        {
            $data["ward_list"] = $this->model_ward_mstr->getWardList($data);
            if (!is_numeric($id)) {
                $data["Saf_detail"] = $this->model_saf_dtl->Saf_details_md5($id);
            } else {
                $data["Saf_detail"] = $this->model_saf_dtl->SafDetailsById($id);
            }
            
            $data["Saf_dtl_id"] = $data["Saf_detail"]["id"];
            $data["saf_no"] = $data["Saf_detail"]["saf_no"];
            $data["zone_id"] = $data["Saf_detail"]["zone_mstr_id"];
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
            $data["owner_detail"] = $this->model_saf_owner_detail->ownerdetails_md5($id);
            $data["floor_details"] = $this->model_view_saf_floor_details->getDataBySafDtlId_md5($id);

            $data["fieldVerificationmstr_detail"] = $this->model_field_verification_dtl->getdatabysafid($data["Saf_dtl_id"]);
            $data["vward_detail"] = $this->model_ward_mstr->getdatabyid($data["fieldVerificationmstr_detail"]["ward_mstr_id"]);
            $data["vzone_id"] = $data["fieldVerificationmstr_detail"]["zone_mstr_id"];
            $data["vprop_type_detail"] = $this->model_prop_type_mstr->getdatabyid($data["fieldVerificationmstr_detail"]["prop_type_mstr_id"]);
            $data["vroad_type_detail"] = $this->model_road_type_mstr->getdatabyid($data["fieldVerificationmstr_detail"]["road_type_mstr_id"]);

            $data["vward_no"] = $data["vward_detail"]["ward_no"];
            $data["vproperty_type"] = $data["vprop_type_detail"]["property_type"];
            $data["vroad_type"] = $data["vroad_type_detail"]["road_type"];
            $data["varea_of_plot"] = $data["fieldVerificationmstr_detail"]["area_of_plot"];
            $data["vis_mobile_tower"] = $data["fieldVerificationmstr_detail"]["is_mobile_tower"];
            $data["vtower_area"] = $data["fieldVerificationmstr_detail"]["tower_area"];
            $data["vtower_installation_date"] = $data["fieldVerificationmstr_detail"]["tower_installation_date"];
            $data["vis_petrol_pump"] = $data["fieldVerificationmstr_detail"]["is_petrol_pump"];
            $data["vunder_ground_area"] = $data["fieldVerificationmstr_detail"]["under_ground_area"];
            $data["vpetrol_pump_completion_date"] = $data["fieldVerificationmstr_detail"]["petrol_pump_completion_date"];

            $data["vis_hoarding_board"] = $data["fieldVerificationmstr_detail"]["is_hoarding_board"];
            $data["vhoarding_area"] = $data["fieldVerificationmstr_detail"]["hoarding_area"];
            $data["vhoarding_installation_date"] = $data["fieldVerificationmstr_detail"]["hoarding_installation_date"];

            $data["vis_water_harvesting"] = $data["fieldVerificationmstr_detail"]["is_water_harvesting"];
            //$data["vland_occupation_date"] = $data["fieldVerificationmstr_detail"]["land_occupation_date"];
            //print_r($data["fieldVerificationmstr_detail"]["id"]);
            $data["vfloor_details"] = $this->model_field_verification_floor_details->getDataBymstrId($data["fieldVerificationmstr_detail"]["id"]);
        }
        return view('mobile/Property/FieldVerification/fieldVerificationView', $data);
    }

    public function objectionMail()
    {
        $data=(array)null;
        $data["objectionList"]=$this->ObjectionModel->ObjectionMailListTC();
        return view('mobile/Property/FieldVerification/objectionMail', $data);
    }

    public function fieldVerifyObj($objection_id_MD5)
    {
        $data=(array)null;
        $objection=$this->ObjectionModel->GetObjection($objection_id_MD5);
        $data = $this->model_prop_dtl->getPropDtlByMD5PropDtlId(md5($objection["prop_dtl_id"]));
        //print_var($data);
        $data["objection"]=$objection;
        $data["objection_status"]=$this->ObjectionModel->ObjectionStatus($objection_id_MD5);
        $data["objection_detail"] = $this->ObjectionModel->GetObjectionDetails($objection["id"]);
        $data["assessment_floor_detail"] = $this->ObjectionModel->GetObjectionFloorDetailsAssessment($objection["id"]);
        $data["objection_floor_detail"] = $this->ObjectionModel->GetObjectionFloorDetailsCitizen($objection["id"]);


        $data['prop_owner_detail'] = $this->model_prop_owner_detail->getPropOwnerDtlByProp_dtlId(['prop_dtl_id'=> $data['prop_dtl_id']]);
        $data["roadTypeList"] = $this->model_road_type_mstr->getRoadTypeList();
        $data["propertyTypeList"] = $this->PropertyTypeModel->getPropertyTypeList();

        $data["usageTypeList"] = $this->model_usage_type_mstr->getUsageTypeList();
		$data["occupancyTypeList"] = $this->model_occupancy_type_mstr->getOccupancyTypeList();
		$data["constTypeList"] = $this->model_const_type_mstr->getConstTypeList();
        //print_var($data);

        if($this->request->getMethod()=='post')
        {
            //print_var($data);
            $Session = Session();
            $data["emp_details"]=$Session->get("emp_details");
            $inputs = arrFilterSanitizeString($this->request->getVar());

            $arr=[
                    "saf_dtl_id"=> $data["saf_dtl_id"],
                    "prop_type_mstr_id"=> $inputs["prop_type_mstr_id"] ?? $data["prop_type_mstr_id"],
                    "road_type_mstr_id"=> $inputs["road_type_mstr_id"] ?? $data["road_type_mstr_id"],
                    "area_of_plot"=> $inputs["area_of_plot"] ?? $data["area_of_plot"],
                    "verified_by_emp_details_id"=> $data["emp_details"]["id"],
                    //"created_on"=> ,
                    //"status"=> ,
                    "ward_mstr_id"=> $data["ward_mstr_id"],
                    "is_mobile_tower"=> $inputs["is_mobile_tower"] ?? $data["is_mobile_tower"],
                    "tower_area"=> $objection["tower_area"] ?? $data["tower_area"],
                    "tower_installation_date"=> $objection["tower_installation_date"] ?? $data["tower_installation_date"],
                    "is_hoarding_board"=> $inputs["is_hoarding_board"] ?? $data["is_hoarding_board"],
                    "hoarding_area"=> $objection["hoarding_area"] ?? $data["hoarding_area"],
                    "hoarding_installation_date"=> $objection["hoarding_installation_date"] ?? $data["hoarding_installation_date"],
                    "is_petrol_pump"=> $data["is_petrol_pump"],
                    "under_ground_area"=> $data["under_ground_area"],
                    "petrol_pump_completion_date"=> $data["petrol_pump_completion_date"],
                    "is_water_harvesting"=> $inputs["is_water_harvesting"] ?? $data["is_water_harvesting"],
                    "verified_by"=> "Objection"
            ];

            $this->db->transBegin();
            $field_verification_dtl_id = $this->model_field_verification_dtl->insertData($arr);

            foreach($data["assessment_floor_detail"] as $row)
            {
                $floor_id=$row["prop_floor_dtl_id"];
                if($inputs[$floor_id."_usage_type_mstr_id"]==1)
                $carpet_area=$inputs[$floor_id."_builtup_area"]*0.7;
                else
                $carpet_area=$inputs[$floor_id."_builtup_area"]*0.8;


                $floor_dtl=[
                        "field_verification_dtl_id"=> $field_verification_dtl_id,
                        "saf_dtl_id"=> $data["saf_dtl_id"],
                        "saf_floor_dtl_id"=> 0,// N/A
                        "floor_mstr_id"=> $row["floor_mstr_id"],
                        "usage_type_mstr_id"=> $inputs[$floor_id."_usage_type_mstr_id"],
                        "const_type_mstr_id"=> $inputs[$floor_id."_const_type_mstr_id"],
                        "occupancy_type_mstr_id"=> $inputs[$floor_id."_occupancy_type_mstr_id"],
                        "builtup_area"=> $inputs[$floor_id."_builtup_area"],
                        "carpet_area"=> $carpet_area,
                        "date_from"=> $row["date_from"],
                        "date_upto"=> $row["date_upto"],
                        "emp_details_id"=> $data["emp_details"]["id"],
                        "verified_by"=> "Objection",
                ];
                $this->model_field_verification_floor_details->insertData($floor_dtl);

                $floor_dtl=[
                    "prop_dtl_id"=> $data["prop_dtl_id"],
                    "objection_id"=> $objection["id"],
                    "objection_type_id"=> 9,//Floor Objection (tbl_objection_type_mstr.id)
                    "floor_mstr_id"=> $row["floor_mstr_id"],
                    "usage_type_mstr_id"=> $inputs[$floor_id."_usage_type_mstr_id"],
                    "const_type_mstr_id"=> $inputs[$floor_id."_const_type_mstr_id"],
                    "occupancy_type_mstr_id"=> $inputs[$floor_id."_occupancy_type_mstr_id"],
                    "builtup_area"=> $inputs[$floor_id."_builtup_area"],
                    "carpet_area"=> $carpet_area,
                    "date_from"=> $row["date_from"],
                    "date_upto"=> $row["date_upto"],
                    "objection_by"=> "Verification",
                ];
                $this->ObjectionModel->InsertFloorObjectionDetails($floor_dtl);



            }

            $arr=[
                "id"=> $objection["id"],
                "level_status"=> 3,//Section Head
            ];
            $this->ObjectionModel->UpdateObjection($arr);

            if($this->db->transStatus() === false)
            {
                flashToast("message", "Something went wrong");
                $this->db->transRollback();
            }
            else
            {
                $this->db->transCommit();
                flashToast("message", "Verified Successfully");
                $this->response->redirect(base_url("SafVerification/ObjectionMail"));

            }

        }
        return view('mobile/Property/FieldVerification/fieldVerifyObj', $data);
    }
}
