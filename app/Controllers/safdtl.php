<?php
namespace App\Controllers;
use CodeIgniter\Controller;

use App\Controllers\SAF\SAFCal;
use App\Controllers\BO_SAF;
use App\Models\model_ulb_mstr;
use App\Models\model_fy_mstr;
use App\Models\model_ward_mstr;
use App\Models\model_saf_dtl;
use App\Models\model_view_saf_dtl;
use App\Models\model_prop_dtl;
use App\Models\model_saf_owner_detail;
use App\Models\model_view_saf_floor_details;
use App\Models\model_saf_floor_arv_dtl;
use App\Models\model_saf_tax;
use App\Models\model_saf_demand;
use App\Models\model_saf_doc_dtl;
use App\Models\model_saf_distributed_dtl;
use App\Models\model_transaction;
use App\Models\model_level_pending_dtl;
use App\Models\model_field_verification_dtl;
use App\Models\model_field_verification_floor_details;
use App\Models\model_saf_geotag_upload_dtl;
use App\Models\model_saf_memo_dtl;
use App\Models\model_view_saf_receive_list;
use App\Models\model_prop_owner_detail;
use App\Models\model_datatable;
use App\Controllers\SAF\DocumentStatus;

class safdtl extends AlphaController
{
	protected $db;
	protected $dbSystem;
	protected $model_ulb_mstr;
    protected $model_ward_mstr;
	protected $model_fy_mstr;
    protected $model_saf_dtl;
    protected $model_view_saf_dtl;
    protected $model_prop_dtl;
	protected $model_saf_owner_detail;
	protected $model_view_saf_floor_details;
	protected $model_saf_floor_arv_dtl;
	protected $model_saf_tax;
    protected $model_saf_demand;
    protected $model_saf_doc_dtl;
	protected $model_saf_distributed_dtl;
    protected $model_transaction;
    protected $model_level_pending_dtl;
    protected $model_field_verification_dtl;
    protected $model_field_verification_floor_details;
    protected $model_saf_geotag_upload_dtl;
    protected $model_saf_memo_dtl;
	protected $model_view_saf_receive_list;
    protected $BO_SAF_Controller;
    protected $model_prop_owner_detail;
	protected $model_datatable;

    public function __construct()
    {
        /*ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);*/

        parent::__construct();
    	helper(['url', 'db_helper', 'validation_helper', 'validate_saf_helper']);
        if($db_name = dbConfig("property")){
            $this->db = db_connect($db_name);
        }
        if ($db_name = dbSystem()) {
            $this->dbSystem = db_connect($db_name);
        }
		$this->model_fy_mstr = new model_fy_mstr($this->dbSystem);
        $this->model_ulb_mstr = new model_ulb_mstr($this->dbSystem);
        $this->model_ward_mstr = new model_ward_mstr($this->dbSystem);
        $this->model_prop_dtl = new model_prop_dtl($this->db);
        $this->model_saf_dtl = new model_saf_dtl($this->db);
        $this->model_view_saf_dtl = new model_view_saf_dtl($this->db);
        $this->model_saf_owner_detail = new model_saf_owner_detail($this->db);
		$this->model_view_saf_floor_details = new model_view_saf_floor_details($this->db);
		$this->model_saf_floor_arv_dtl = new model_saf_floor_arv_dtl($this->db);
        $this->model_saf_tax = new model_saf_tax($this->db);
        $this->model_saf_demand = new model_saf_demand($this->db);
        $this->model_saf_doc_dtl = new model_saf_doc_dtl($this->db);
        $this->model_transaction = new model_transaction($this->db);
        $this->model_level_pending_dtl = new model_level_pending_dtl($this->db);
        $this->model_field_verification_dtl = new model_field_verification_dtl($this->db);
        $this->model_field_verification_floor_details = new model_field_verification_floor_details($this->db);
        $this->model_saf_geotag_upload_dtl = new model_saf_geotag_upload_dtl($this->db);
        $this->model_saf_memo_dtl = new model_saf_memo_dtl($this->db);
		$this->model_view_saf_receive_list = new model_view_saf_receive_list($this->db);
        $this->BO_SAF_Controller = new BO_SAF($this->db);
        $this->model_prop_owner_detail = new model_prop_owner_detail($this->db);
		$this->model_datatable = new model_datatable($this->db);
    }

    function __destruct() {
		if(isset($this->db)) $this->db->close();
		if(isset($this->dbSystem)) $this->dbSystem->close();
	}

    public function full($saf_dtl_id_md5)
    {
        $data=(array)null;
        $ulb = getUlbDtl();
        $Session = Session();
        $emp_details=$Session->get("emp_details");
        $saf = $this->model_view_saf_dtl->get_saf_full_details($saf_dtl_id_md5);
        $saf = $saf['get_saf_full_details'];
        $data=json_decode($saf, true);
        $basic_details_dt = $data;
		$saf_dtl_id = $basic_details_dt['id'];

        $sql ="SELECT 
            tbl_level_pending_dtl.id, 
            tbl_level_pending_dtl.saf_dtl_id, 
            tbl_level_pending_dtl.sender_user_type_id,  
            tbl_level_pending_dtl.forward_date, 
            tbl_level_pending_dtl.forward_time, 
            tbl_level_pending_dtl.created_on, 
            tbl_level_pending_dtl.status, 
            tbl_level_pending_dtl.remarks, 
            tbl_level_pending_dtl.status,
            tbl_level_pending_dtl.verification_status,
            tbl_level_pending_dtl.sender_emp_details_id	, 
            view_user_type_mstr.user_type, view_emp_details.emp_name
            FROM (
                SELECT
                tbl_bugfix_level_pending_dtl.id, 
                tbl_bugfix_level_pending_dtl.saf_dtl_id, 
                tbl_bugfix_level_pending_dtl.sender_user_type_id, 
                tbl_bugfix_level_pending_dtl.forward_date, 
                tbl_bugfix_level_pending_dtl.forward_time, 
                tbl_bugfix_level_pending_dtl.created_on, 
                tbl_bugfix_level_pending_dtl.status, 
                tbl_bugfix_level_pending_dtl.remarks, 
                tbl_bugfix_level_pending_dtl.verification_status,
                tbl_bugfix_level_pending_dtl.sender_emp_details_id
            FROM tbl_bugfix_level_pending_dtl WHERE saf_dtl_id = ".$saf_dtl_id."
        )  AS tbl_level_pending_dtl
        JOIN view_user_type_mstr ON view_user_type_mstr.id = tbl_level_pending_dtl.sender_user_type_id
        LEFT JOIN view_emp_details ON view_emp_details.id=tbl_level_pending_dtl.sender_emp_details_id
        ORDER BY forward_date ASC, tbl_level_pending_dtl.created_on ASC";

        $data['level'] = $this->db->query($sql)->getResultArray();

        $data['application_status'] = $this->model_view_saf_dtl->get_saf_status($saf_dtl_id_md5);

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
        //$this->cachePage(30);
        return view('property/saf/saf_dtl', $data);
	}

    public function fullDocStatus($saf_dtl_id_md5)
	{
        $documentStatus = new DocumentStatus($this->db);
        if($documentStatus->isFullDocUploaded($saf_dtl_id_md5)) {
            echo "Full Uploaded";
        } else {
            echo "Not Full Uploaded";
        }
    }

	public function fulltest($saf_dtl_id_md5)
	{
			$data=(array)null;
			$Session = Session();
			$ulb = $Session->get('ulb_dtl');
			$emp_details=$Session->get("emp_details");

			$sql = "WITH tbl_saf_dtl AS (
                        SELECT
                            *
                        FROM tbl_saf_dtl
                        WHERE id=".$saf_dtl_id_md5."
                    ),
                    prev_prop_dtl AS (
                        SELECT
                            tbl_saf_dtl.id AS saf_dtl_id,
                            CASE WHEN tbl_prop_dtl.new_holding_no!='' THEN tbl_prop_dtl.new_holding_no ELSE tbl_prop_dtl.holding_no END AS old_holding_no,
                            prev_owner_dtl.prev_saf_owner_detail
                        FROM tbl_saf_dtl
                        INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_saf_dtl.previous_holding_id::BIGINT AND tbl_saf_dtl.id=".$saf_dtl_id_md5."
                        INNER JOIN (
                            SELECT
                                prop_dtl_id,
                                json_agg(json_build_object('owner_name', owner_name, 'guardian_name', guardian_name, 'relation_type', relation_type, 'mobile_no', mobile_no, 'email', email, 'pan_no', pan_no, 'aadhar_no', aadhar_no, 'gender', gender, 'dob', dob, 'is_specially_abled', is_specially_abled, 'is_armed_force', is_armed_force) ORDER BY tbl_prop_owner_detail.id ASC
                                ) AS prev_saf_owner_detail
                            FROM tbl_prop_owner_detail
                            WHERE status=1
                            GROUP BY prop_dtl_id
                        ) AS prev_owner_dtl ON prev_owner_dtl.prop_dtl_id=tbl_prop_dtl.id
                    ),
                    owner_dtl AS (
                        SELECT
                            saf_dtl_id,
                            json_agg(json_build_object('owner_name', owner_name, 'guardian_name', guardian_name, 'relation_type', relation_type, 'mobile_no', mobile_no, 'email', email, 'pan_no', pan_no, 'aadhar_no', aadhar_no, 'gender', gender, 'dob', dob, 'is_specially_abled', is_specially_abled, 'is_armed_force', is_armed_force) ORDER BY tbl_saf_owner_detail.id ASC
                            ) AS saf_owner_detail
                        FROM tbl_saf_owner_detail
                        WHERE status=1 AND saf_dtl_id=".$saf_dtl_id_md5."
                        GROUP BY saf_dtl_id
                    ),
                    floor_dtl AS (
                        SELECT
                            saf_dtl_id,
                            json_agg(json_build_object('floor_name', tbl_floor_mstr.floor_name, 'usage_type', tbl_usage_type_mstr.usage_type, 'construction_type', tbl_const_type_mstr.construction_type, 'occupancy_name', tbl_occupancy_type_mstr.occupancy_name, 'builtup_area', tbl_saf_floor_details.builtup_area, 'carpet_area', tbl_saf_floor_details.carpet_area, 'date_from', tbl_saf_floor_details.date_from, 'date_upto', tbl_saf_floor_details.date_upto) ORDER BY tbl_saf_floor_details.date_from ASC, tbl_saf_floor_details.date_upto ASC
                            ) AS saf_floor_details
                        FROM tbl_saf_floor_details
                        INNER JOIN tbl_floor_mstr ON tbl_floor_mstr.id=tbl_saf_floor_details.floor_mstr_id
                        INNER JOIN tbl_usage_type_mstr ON tbl_usage_type_mstr.id=tbl_saf_floor_details.usage_type_mstr_id
                        INNER JOIN tbl_const_type_mstr ON tbl_const_type_mstr.id=tbl_saf_floor_details.const_type_mstr_id
                        INNER JOIN tbl_occupancy_type_mstr ON tbl_occupancy_type_mstr.id=tbl_saf_floor_details.occupancy_type_mstr_id
                        WHERE tbl_saf_floor_details.status=1 AND saf_dtl_id=".$saf_dtl_id_md5."
                        GROUP BY saf_dtl_id
                    ),
                    tax_dtl AS (
                        SELECT 
                            saf_dtl_id,
                            json_agg(json_build_object('id', id, 'fy_mstr_id', fy_mstr_id, 'fyear', fyear, 'qtr', qtr, 'arv', arv, 'holding_tax', holding_tax, 'water_tax', water_tax, 'education_cess', education_cess, 'health_cess', health_cess, 'latrine_tax', latrine_tax, 'additional_tax', additional_tax, 'quarterly_tax', quarterly_tax, 'created_on', created_on) ORDER BY tbl_saf_tax.fyear ASC, tbl_saf_tax.qtr ASC
                            ) AS saf_tax_list
                        FROM tbl_saf_tax
                        WHERE  saf_dtl_id=".$saf_dtl_id_md5."
                        GROUP BY saf_dtl_id
                    ),
                    tran_dtl AS (
                        SELECT 
                            prop_dtl_id,
                            json_agg(json_build_object('id', id,'tran_date', tran_date, 'tran_no', tran_no, 'tran_mode', tran_mode, 'penalty_amt', penalty_amt, 'discount_amt', discount_amt, 'demand_amt', demand_amt, 'payable_amt', payable_amt, 'round_off', round_off, 'from_fyear', from_fyear, 'from_qtr', from_qtr, 'upto_fyear', upto_fyear, 'upto_qtr', upto_qtr, 'remarks', remarks, 'created_on', created_on, 'status', status, 'deactive_status', deactive_status) ORDER BY tbl_transaction.tran_date ASC, tbl_transaction.created_on
                            ) AS payment_detail
                        FROM  tbl_transaction
                        WHERE tran_type='Saf' AND status IN (1,2) AND prop_dtl_id=".$saf_dtl_id_md5."
                        GROUP BY prop_dtl_id
                    ),
                    field_dtl AS (
                        SELECT
                            saf_dtl_id,
                            json_agg(
                                    json_build_object('id', id, 'verified_by', verified_by, 'created_on', tbl_field_verification_dtl.created_on, 'status', tbl_field_verification_dtl.status) ORDER BY tbl_field_verification_dtl.id ASC
                            ) AS verification
                        FROM tbl_field_verification_dtl
                        WHERE tbl_field_verification_dtl.status=1 AND saf_dtl_id=".$saf_dtl_id_md5."
                        GROUP BY saf_dtl_id
                    ),
                    memo_dtl AS (
                        SELECT
                            saf_dtl_id,
                            json_agg(json_build_object('id', tbl_saf_memo_dtl.id, 'memo_no', memo_no, 'memo_type', memo_type, 'holding_no', holding_no, 'fy_mstr_id', fy_mstr_id, 'fy', fy, 'effect_quarter', effect_quarter, 'arv', arv, 'quarterly_tax', quarterly_tax, 'created_on', created_on, 'status', tbl_saf_memo_dtl.status, 'emp_name', view_emp_details.emp_name) ORDER BY tbl_saf_memo_dtl.id ASC
                            ) AS memo
                        FROM tbl_saf_memo_dtl
                        INNER JOIN view_emp_details ON view_emp_details.id=tbl_saf_memo_dtl.emp_details_id
                        WHERE tbl_saf_memo_dtl.status=1 AND saf_dtl_id=".$saf_dtl_id_md5."
                        GROUP BY saf_dtl_id
                    ),
                    level_dtl AS (
                        SELECT 
                                tbl_level_pending_dtl.saf_dtl_id,
                                json_agg(json_build_object(
                                    'sender_user_type_id', tbl_level_pending_dtl.sender_user_type_id,  
                                    'forward_date', tbl_level_pending_dtl.forward_date, 
                                    'forward_time', tbl_level_pending_dtl.forward_time, 
                                    'created_on', tbl_level_pending_dtl.created_on, 
                                    'status', tbl_level_pending_dtl.status, 
                                    'remarks', tbl_level_pending_dtl.remarks, 
                                    'status', tbl_level_pending_dtl.status,
                                    'verification_status', tbl_level_pending_dtl.verification_status,
                                    'sender_emp_details_id', tbl_level_pending_dtl.sender_emp_details_id, 
                                    'user_type', view_user_type_mstr.user_type, 
                                    'emp_name', view_emp_details.emp_name
                                   )
                                ) AS level
                                FROM (
                                    SELECT
                                    tbl_level_pending_dtl.saf_dtl_id, 
                                    tbl_level_pending_dtl.sender_user_type_id, 
                                    tbl_level_pending_dtl.forward_date, 
                                    tbl_level_pending_dtl.forward_time, 
                                    tbl_level_pending_dtl.created_on, 
                                    tbl_level_pending_dtl.status, 
                                    tbl_level_pending_dtl.remarks, 
                                    tbl_level_pending_dtl.verification_status,
                                    tbl_level_pending_dtl.sender_emp_details_id
                                FROM tbl_level_pending_dtl
                                    WHERE saf_dtl_id=".$saf_dtl_id_md5."
                                UNION ALL
                                SELECT
                                    tbl_level_sent_back_dtl.saf_dtl_id,
                                    tbl_level_sent_back_dtl.sender_user_type_id,
                                    tbl_level_sent_back_dtl.created_on::DATE AS forward_date,
                                    tbl_level_sent_back_dtl.created_on::TIME AS forward_time,
                                    tbl_level_sent_back_dtl.created_on,
                                    tbl_level_sent_back_dtl.status,
                                    tbl_level_sent_back_dtl.remarks,
                                    1 AS verification_status,
                                    tbl_level_sent_back_dtl.sender_emp_details_id
                                FROM tbl_level_sent_back_dtl WHERE tbl_level_sent_back_dtl.status=1 AND saf_dtl_id=".$saf_dtl_id_md5."
                            )  AS tbl_level_pending_dtl
                            JOIN view_user_type_mstr ON view_user_type_mstr.id = tbl_level_pending_dtl.sender_user_type_id
                            LEFT JOIN view_emp_details ON view_emp_details.id=tbl_level_pending_dtl.sender_emp_details_id
                            GROUP BY tbl_level_pending_dtl.saf_dtl_id
                    )
                    SELECT
                        tbl_saf_dtl.id,
                        tbl_saf_dtl.id AS saf_dtl_id,
                        has_previous_holding_no,
                        previous_holding_id,
                        CASE WHEN tbl_prop_dtl.new_holding_no IS NOT NULL THEN tbl_prop_dtl.new_holding_no ELSE tbl_prop_dtl.holding_no END AS old_holding_no,
                        is_owner_changed,
                        tbl_transfer_mode_mstr.transfer_mode,
                        transfer_mode_mstr_id,
                        tbl_prop_type_mstr.property_type,
                        tbl_saf_dtl.prop_type_mstr_id,
                        tbl_road_type_mstr.road_type,
                        tbl_saf_dtl.road_type_mstr_id,
                        view_ward_mstr.ward_no,
                        tbl_saf_dtl.ward_mstr_id,
                        new_ward.ward_no AS new_ward_no,
                        tbl_saf_dtl.new_ward_mstr_id,
                        tbl_ownership_type_mstr.ownership_type,
                        tbl_saf_dtl.ownership_type_mstr_id,
                        tbl_apartment_details.apartment_name,
                        tbl_saf_dtl.apartment_details_id,
                        tbl_apartment_details.apt_code,
                        tbl_apartment_details.apartment_address,
                        tbl_saf_dtl.zone_mstr_id,
                        tbl_saf_dtl.flat_registry_date,
                        tbl_saf_dtl.saf_no,
                        tbl_saf_dtl.no_electric_connection,
                        tbl_saf_dtl.elect_consumer_no,
                        tbl_saf_dtl.elect_acc_no,
                        tbl_saf_dtl.elect_bind_book_no,
                        tbl_saf_dtl.elect_cons_category,
                        tbl_saf_dtl.building_plan_approval_no,
                        tbl_saf_dtl.building_plan_approval_date,
                        tbl_saf_dtl.water_conn_no,
                        tbl_saf_dtl.water_conn_date,
                        tbl_saf_dtl.khata_no,
                        tbl_saf_dtl.plot_no,
                        tbl_saf_dtl.village_mauja_name,
                        tbl_saf_dtl.area_of_plot,
                        tbl_saf_dtl.prop_address,
                        tbl_saf_dtl.prop_city,
                        tbl_saf_dtl.prop_dist,
                        tbl_saf_dtl.prop_pin_code,
                        tbl_saf_dtl.prop_state,
                        tbl_saf_dtl.corr_address,
                        tbl_saf_dtl.corr_city,
                        tbl_saf_dtl.corr_dist,
                        tbl_saf_dtl.corr_pin_code,
                        tbl_saf_dtl.corr_state,
                        tbl_saf_dtl.is_mobile_tower,
                        tbl_saf_dtl.tower_area,
                        tbl_saf_dtl.tower_installation_date,
                        tbl_saf_dtl.is_hoarding_board,
                        tbl_saf_dtl.hoarding_area,
                        tbl_saf_dtl.hoarding_installation_date,
                        tbl_saf_dtl.is_petrol_pump,
                        tbl_saf_dtl.under_ground_area,
                        tbl_saf_dtl.petrol_pump_completion_date,
                        tbl_saf_dtl.is_water_harvesting,
                        tbl_saf_dtl.land_occupation_date,
                        tbl_saf_dtl.apply_date,
                        tbl_saf_dtl.created_on,
                        tbl_saf_dtl.assessment_type,
                        tbl_saf_dtl.holding_type,
                        tbl_saf_dtl.doc_upload_status,
                        tbl_saf_dtl.payment_status,
                        tbl_saf_dtl.doc_verify_status,
                        tbl_saf_dtl.emp_details_id,
                        tbl_saf_dtl.status,
                        prev_prop_dtl.pre
                        owner_dtl.saf_owner_detail,
                        floor_dtl.saf_floor_details,
                        tax_dtl.saf_tax_list,
                        tran_dtl.payment_detail,
                        memo_dtl.memo,
                        field_dtl.verification,
                        prev_prop_dtl.old_holding_no,
                        prev_prop_dtl.prev_saf_owner_detail
                    FROM tbl_saf_dtl
                    LEFT JOIN prev_prop_dtl ON prev_prop_dtl.saf_dtl_id=tbl_saf_dtl.id
                    INNER JOIN tbl_prop_type_mstr ON tbl_prop_type_mstr.id=tbl_saf_dtl.prop_type_mstr_id
                    INNER JOIN tbl_road_type_mstr ON tbl_road_type_mstr.id=tbl_saf_dtl.road_type_mstr_id
                    INNER JOIN view_ward_mstr ON view_ward_mstr.id=tbl_saf_dtl.ward_mstr_id
                    LEFT JOIN view_ward_mstr AS new_ward ON new_ward.id=tbl_saf_dtl.new_ward_mstr_id
                    LEFT JOIN tbl_ownership_type_mstr ON tbl_ownership_type_mstr.id=tbl_saf_dtl.ownership_type_mstr_id
                    LEFT JOIN tbl_transfer_mode_mstr ON tbl_transfer_mode_mstr.id=tbl_saf_dtl.transfer_mode_mstr_id
                    LEFT JOIN tbl_apartment_details ON tbl_apartment_details.id=tbl_saf_dtl.apartment_details_id
                    LEFT JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_saf_dtl.previous_holding_id::BIGINT
                    INNER JOIN owner_dtl ON owner_dtl.saf_dtl_id=tbl_saf_dtl.id
                    LEFT JOIN floor_dtl ON floor_dtl.saf_dtl_id=tbl_saf_dtl.id
                    INNER JOIN tax_dtl ON tax_dtl.saf_dtl_id=tbl_saf_dtl.id
                    LEFT JOIN tran_dtl ON tran_dtl.prop_dtl_id=tbl_saf_dtl.id
                    LEFT JOIN field_dtl ON field_dtl.saf_dtl_id=tbl_saf_dtl.id
                    LEFT JOIN memo_dtl ON memo_dtl.saf_dtl_id=tbl_saf_dtl.id
                    LEFT JOIN level_dtl ON level_dtl.saf_dtl_id=tbl_saf_dtl.id";
			$data = $this->db->query($sql)->getFirstRow("array");
            $data["saf_owner_detail"] = json_decode($data["saf_owner_detail"], true);
            $data["saf_owner_detail"] = json_decode($data["saf_owner_detail"], true);
            $data["saf_floor_details"] = json_decode($data["saf_floor_details"], true);
            $data["saf_tax_list"] = json_decode($data["saf_tax_list"], true);
            $data["payment_detail"] = json_decode($data["payment_detail"], true);
            $data["verification"] = json_decode($data["verification"], true);
            $data["memo"] = json_decode($data["memo"], true);
            print_var($data);
            //die();

			//$data=json_decode($saf, true);
			//print_var($data);
			//$basic_details_dt = $data;
			$data['application_status'] = $this->model_view_saf_dtl->get_saf_status(md5($saf_dtl_id_md5));
			$data["ulb_mstr_id"]=$ulb["ulb_mstr_id"];
			$data["emp_details"]=$emp_details;

			/*$data['basic_details_data'] = array(
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
			);*/
			return view('property/saf/saf_dtl', $data);
}


    public function full_old($saf_dtl_id)
    {
        $data=(array)null;
        $Session = Session();
        $ulb = $Session->get('ulb_dtl');
        $emp_details=$Session->get("emp_details");

        $data = $this->model_view_saf_dtl->getSafDtlByMD5SafDtlId($saf_dtl_id);
        $data['application_status']=$this->BO_SAF_Controller->SAFStatus(["id"=> $saf_dtl_id]);
        $input['saf_dtl_id'] = $data['saf_dtl_id'];
		//$data['form'] = $this->model_view_saf_receive_list->safrecptid($saf_dtl_id);
        $data['saf_owner_detail'] = $this->model_saf_owner_detail->getOwnerdtlBySAFId($input);
        $data['saf_floor_details'] = $this->model_view_saf_floor_details->getDataBySafDtlId($input);
        $data['saf_tax_list'] = $this->model_saf_tax->getSafTaxDtlBySafDtlId($input);
        //$data['saf_demand_list'] = $this->model_saf_demand->getFullDemandDtlBySafDtlId($input);
        $data['payment_detail'] = $this->model_transaction->getTranDtlBySafDtlId($input);

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
        if($data['has_previous_holding_no']=='t' && $data['is_owner_changed']=='t')
        $data['prev_saf_owner_detail']=$this->model_prop_owner_detail->getPropOwnerDtlByProp_dtlId(['prop_dtl_id'=> $data['previous_holding_id']]);

        if($data["doc_verify_status"]==1)
        {
            $data['Verification']=$this->model_field_verification_dtl->getAllFieldVerification($input);
            $data['Memo']=$this->model_saf_memo_dtl->getAllMemo($input);
        }
        $data['level']=$this->model_level_pending_dtl->getAllRecords($data['saf_dtl_id']);
        $data["ulb_mstr_id"]=$ulb["ulb_mstr_id"];
        $data["emp_details"]=$emp_details;
        //print_var($data['saf_owner_detail']);
        return view('property/saf/saf_dtl', $data);
	}

    public function sendForReVerification($saf_dtl_id_MD5)
    {
        $data = $this->model_view_saf_dtl->getSafDtlByMD5SafDtlId($saf_dtl_id_MD5);
        $input['saf_dtl_id'] = $data['saf_dtl_id'];
        $data['Memo']=$this->model_saf_memo_dtl->getAllMemo($input);


        $fam = array_filter($data['Memo'], function ($var) {
            return ($var['memo_type'] == 'FAM');
        });

        if(is_array($fam) && !empty($fam))
        {
            //print_var($fam);
            $this->model_saf_memo_dtl->DeactivateByMemoType($data['saf_dtl_id'], "FAM");
            $this->model_level_pending_dtl->DeactivateAll($data['saf_dtl_id']);
            $this->model_field_verification_dtl->DeactivateAll($data['saf_dtl_id']);

            $input=[
                    "saf_dtl_id"=> $data['saf_dtl_id'],
                    "sender_user_type_id"=> 11,
                    "receiver_user_type_id"=> 5,
                    "forward_date"=> "NOW()",
                    "forward_time"=> "NOW()",
                    "remarks"=> "Application resent to tc for re-verification",
                    "created_on"=> "NOW()",
                ];
            $this->model_level_pending_dtl->insrtlevelpendingdtl($input);
            $this->model_saf_dtl->update_saf_pending_status(["saf_dtl_id"=> $data['saf_dtl_id'], "doc_verify_status"=> 0]);

            flashToast('message', "Application sent to agency tc for re-verification.");
            return $this->response->redirect(base_url("safdtl/full/".$saf_dtl_id_MD5));

        }
        else
        {
            flashToast('message', "FAM is not generated, So, you cant send for re-verification");
            return $this->response->redirect(base_url("safdtl/full/".$saf_dtl_id_MD5));
        }
    }

    public function search()
    {
        $Session = Session();
        $ulb=$Session->get('ulb_dtl');
        $ward_list = $this->model_ward_mstr->getWardList(['ulb_mstr_id'=> $ulb["ulb_mstr_id"]]);
		$Session->set('ward_list', $ward_list);

        if($this->request->getVar('cmd') && $this->request->getVar('cmd')=="clr")
        {
            $Session->remove('postData');
            $Session->remove('where');
        }

        $where="where tbl_saf_dtl.status=1 ";
        if($this->request->getMethod()=='post')
        {
            $input = arrFilterSanitizeString($this->request->getVar());
            $Session->set('postData', $input);

			if($input['ward_mstr_id']!='') {
				$where .= " and tbl_saf_dtl.ward_mstr_id=".$input['ward_mstr_id'];
			}

			if($input['saf_no']!='') {
				$where .= " and tbl_saf_dtl.saf_no ILIKE ('%".$input['saf_no']."%')";
			}

			if($input['ward_mstr_id']!='' && $input['saf_no']!='') {
				$where .= " and tbl_saf_dtl.ward_mstr_id=".$input['ward_mstr_id']." and tbl_saf_dtl.saf_no='".$input['saf_no']."'";
			}

			if($input['ward_mstr_id']!='' && $input['owner_name']!='') {
				$where .= " and tbl_saf_dtl.ward_mstr_id=".$input['ward_mstr_id']." and saf_owner_detail.owner_name ILIKE '%".$input['owner_name']."%'";
			}

			if($input['ward_mstr_id']!='' && $input['mobile_no']!='') {
				$where .= " and tbl_saf_dtl.ward_mstr_id=".$input['ward_mstr_id']." and saf_owner_detail.mobile_no='".$input['mobile_no']."'";
			}
			if($input['ward_mstr_id']=='' && $input['owner_name']!='') {
				$where .= " and tbl_saf_dtl.ward_mstr_id=".$input['ward_mstr_id']." and saf_owner_detail.owner_name ILIKE '%".$input['owner_name']."%'";
			}

			if($input['ward_mstr_id']=='' && $input['mobile_no']!='') {
				$where = " and saf_owner_detail.mobile_no='".$input['mobile_no']."'";
			}
        }

        $Session->set('where', $where);
		return $this->response->redirect(base_url('safdtl/searchApplication'));
    }

	public function searchApplication() {

        $ulb= getUlbDtl();
        $ward_list = $this->model_ward_mstr->getWardList(['ulb_mstr_id'=>$ulb["ulb_mstr_id"]]);

        $data = arrFilterSanitizeString($this->request->getVar());
        $whereWard = "";
        $whereOwner = "";
        $whereApplication = "";
        if (isset($data["ward_mstr_id"]) && isset($data["by_application_owner_dtl"]) && isset($data["keyword"])) {
            if ($data["ward_mstr_id"]!="") {
                $whereWard = " AND tbl_saf_dtl.ward_mstr_id=".$data["ward_mstr_id"];
            }
            if ($data["by_application_owner_dtl"]=="by_owner") {
                $whereOwner = " AND (owner_name ILIKE '%".$data["keyword"]."%' OR mobile_no::TEXT ILIKE '%".$data["keyword"]."%')";
            } else if ($data["by_application_owner_dtl"]=="by_application") {
                $whereApplication = " AND saf_no ILIKE '%" . $data["keyword"] . "%' ";
            }
            $sql ="SELECT tbl_saf_dtl.id, view_ward_mstr.ward_no AS ward_no, tbl_saf_dtl.saf_no AS saf_no, saf_owner_detail.owner_name AS owner_name, saf_owner_detail.guardian_name AS guardian_name, saf_owner_detail.mobile_no AS mobile_no, property_type,
                    assessment_type, apply_date, prop_address
                    FROM tbl_saf_dtl
                    JOIN (
                            SELECT saf_dtl_id, STRING_AGG(owner_name, ',<br/>') AS owner_name, STRING_AGG(guardian_name::text, ',<br/>') AS guardian_name, STRING_AGG(mobile_no::text, ',<br/>') AS mobile_no
                            FROM tbl_saf_owner_detail
                            WHERE status=1 ".$whereOwner."
                            GROUP BY saf_dtl_id
                        ) AS saf_owner_detail ON saf_owner_detail.saf_dtl_id=tbl_saf_dtl.id
                    JOIN view_ward_mstr ON view_ward_mstr.id=tbl_saf_dtl.ward_mstr_id ".$whereWard."
                    join tbl_prop_type_mstr on tbl_prop_type_mstr.id=tbl_saf_dtl.prop_type_mstr_id
                    WHERE tbl_saf_dtl.status=1 ".$whereApplication." order by tbl_saf_dtl.id desc";
            $result = $this->model_datatable->getDatatable($sql);

            $result_list = $result['result'];
            $data['result_list'] = $result_list;
            $data['pager'] = $result['count'];
            $data['offset'] = $result['offset'];
        }
        $data['ward_list'] = $ward_list;
        return view('property/saf/safSearch', $data);

	}

    public function searchDtl(){
        $ulb = getUlbDtl();
        $ward_list = $this->model_ward_mstr->getWardList(['ulb_mstr_id' => $ulb["ulb_mstr_id"]]);
        $data =$this->request->getVar();
        $data["ward_list"] = $ward_list;
        $sql = "select tbl_saf_dtl.id, tbl_saf_dtl.saf_no,tbl_saf_dtl.assessment_type, tbl_prop_type_mstr.property_type,
	                owners.owner_name,owners.mobile_no,tbl_prop_dtl.holding_no,tbl_prop_dtl.id as prop_id,
                    view_ward_mstr.ward_no AS ward_no
                from tbl_saf_dtl
                left join tbl_prop_dtl on tbl_prop_dtl.saf_dtl_id =  tbl_saf_dtl.id                
                left join(
                    select saf_dtl_id,
                        string_agg(owner_name,',')owner_name,
                        string_agg(mobile_no::text,',')mobile_no
                    from tbl_saf_owner_detail
                    where status =1
                    group by saf_dtl_id
                )owners on owners.saf_dtl_id = tbl_saf_dtl.id
                join view_ward_mstr on view_ward_mstr.id = tbl_saf_dtl.ward_mstr_id
                join tbl_prop_type_mstr on tbl_prop_type_mstr.id=tbl_saf_dtl.prop_type_mstr_id
                where tbl_saf_dtl.status =1
            ";
        if(isset($data["ward_id"]) && trim($data["ward_id"])!=""){
            $sql.=" AND tbl_saf_dtl.ward_mstr_id = ".$data["ward_id"];
        }
        
        if(isset($data["saf_no"]) && trim($data["saf_no"])!=""){
            $sql.=" AND tbl_saf_dtl.saf_no = '".$data["saf_no"]."'";
        }
        
        if(isset($data["holding_no"]) && trim($data["holding_no"])!=""){
            $sql.=" AND (tbl_prop_dtl.holding_no = '".$data["holding_no"]."' 
                         OR tbl_prop_dtl.new_holding_no = '".$data["holding_no"]."'
                         OR tbl_saf_dtl.holding_no = '".$data["holding_no"]."'
                        )
            ";
        }        

        if(isset($data["fromDate"]) && trim($data["fromDate"])!="" && isset($data["uptoDate"]) && trim($data["uptoDate"])!="" ){
            $sql.=" AND tbl_saf_dtl.apply_date BETWEEN '".$data["fromDate"]."' AND '".$data["uptoDate"]."'";
        }

        if($this->request->getMethod()=="get"){
            $result = $this->model_datatable->getDatatable($sql);
            $result_list = $result['result'];
            $data['result_list'] = $result_list;
            $data['pager'] = $result['count'];
            $data['offset'] = $result['offset'];
        }
        return view("property/saf/bulkSearch",$data);
    }

    public function verificationDtl($safId){
        $Session = Session();
        $ulb = $Session->get('ulb_dtl');
        $tcVerificationSql = "SELECT id 
                              FROM tbl_field_verification_dtl
                              WHERE saf_dtl_id = $safId AND status = 1 AND verified_by ='AGENCY TC' ";
        $utcVerificationSql = "SELECT id 
                              FROM tbl_field_verification_dtl
                              WHERE saf_dtl_id = $safId AND status = 1 AND verified_by ='ULB TC'";
        $tranDtlSql = "SELECT id, prop_dtl_id,tran_no	,from_qtr	,tran_by_emp_details_id	,tran_mode_mstr_id	,upto_qtr,payable_amt	,
							tran_date	,tran_type, 0 as ward_id,tran_mode as transaction_mode	, from_fyear AS fy	, upto_fyear AS upto_fy	,status	
                        FROM tbl_transaction
                        WHERE prop_dtl_id = $safId and tbl_transaction.tran_type='Saf' AND tbl_transaction.status in (1, 2)";
        $data["safId"] = $safId;
        $data["ulb_mstr_id"] = $ulb["ulb_mstr_id"]??1;
        $data["tranDtl"] = $this->db->query($tranDtlSql)->getResultArray();
        $data["sizeOfTran"] = sizeof($data["tranDtl"]);
        $data["tcVerification"] = $this->db->query($tcVerificationSql)->getResultArray();
        $data["sizeOfTc"] = sizeof($data["tcVerification"]);
        $data["utcVerification"] = $this->db->query($utcVerificationSql)->getResultArray();
        $data["sizeOfUtc"] = sizeof($data["utcVerification"]);
        $data["sizeOfUtc"] = sizeof($data["utcVerification"]);
        foreach($data["tcVerification"] as $id){
            $data["tcVerificationCompDtl"][]=$this->makeVerificationComp($id["id"]);
        }
        foreach($data["utcVerification"] as $id){
            $data["utcVerificationCompDtl"][]=$this->makeVerificationComp($id["id"]);
        }
        // dd($data["tcVerificationCompDtl"][0]["capData"],$data["tcVerificationCompDtl"][0]["capData"]["floorComp"]["floors"][0]["assessedVal"]);
        return view("property/saf/safVerificationDtls",$data);
    }

    private function makeVerificationComp($id){
        $safHelper = new \App\Controllers\SAF\SAFHelper($this->db);
        $field_verification_id = md5($id);
        $data['verification_data'] = $this->model_field_verification_dtl->getFieldVerificationDtlBySafDtlIdAndVerifiedBy($field_verification_id);
        $data['assessment_data'] = $this->model_view_saf_dtl->getSafDtlByMD5SafDtlId(md5($data['verification_data']['saf_dtl_id']));   
        $data['saf_owner_detail'] = $this->model_saf_owner_detail->getOwnerdtlBySAFId($data['verification_data']);
        $data['saf_floor_data'] = $this->model_view_saf_floor_details->getDataBySafDtlId($data['verification_data']);
        $data["verification_floor_data"] = $this->model_field_verification_floor_details->getagencyDataBymstrId($data["verification_data"]["id"], $data['verification_data']["verified_by"]);
        $data["ExtraFloorAddedByTC"] = $this->model_field_verification_floor_details->getExtraFloorAddedByTC($data["verification_data"]["id"], $data['verification_data']["verified_by"]);
        $data["safGeoTaggingDtl"] = $this->model_saf_geotag_upload_dtl->getAllGeoTagImgDtlBySafDtlId($data['verification_data']);
        $data["verification_data"]["application_no"] = $data['assessment_data']["saf_no"]??"";
        $data["verification_data"]["apply_date"] = $data['assessment_data']["apply_date"]??"";
        $data["verification_data"]["assessment_type"] = $data['assessment_data']["assessment_type"]??"";
        $capData["safComp"]=[
            [
                "key"=>"Ward No",
                "assetsVal"=>$data["assessment_data"]["ward_no"],
                "verificationVal"=>$data["verification_data"]["ward_no"],
                "flag"=>$data["assessment_data"]["ward_no"]==$data["verification_data"]["ward_no"]?true:false,
            ],
            [
                "key"=>"Property Type",
                "assetsVal"=>$data["assessment_data"]["property_type"],
                "verificationVal"=>$data["verification_data"]["property_type"],
                "flag"=>$data["assessment_data"]["property_type"]==$data["verification_data"]["property_type"]?true:false,
            ],
            [
                "key"=>"Area of Plot",
                "assetsVal"=>$data["assessment_data"]["area_of_plot"],
                "verificationVal"=>$data["verification_data"]["area_of_plot"],
                "flag"=>$data["assessment_data"]["area_of_plot"]==$data["verification_data"]["area_of_plot"]?true:false,
            ],
            [
                "key"=>"Street Type",
                "assetsVal"=>$data["assessment_data"]["road_type"],
                "verificationVal"=>$data["verification_data"]["road_type"],
                "flag"=>$data["assessment_data"]["road_type"]==$data["verification_data"]["road_type"]?true:false,
            ],
            [
                "key"=>"Mobile Tower(s) ?",
                "assetsVal"=>$data["assessment_data"]["is_mobile_tower"] =="t" ?"Yes":"No",
                "verificationVal"=>$data["verification_data"]["is_mobile_tower"] =="t" ? "Yes":"No",
                "flag"=>$data["assessment_data"]["is_mobile_tower"]==$data["verification_data"]["is_mobile_tower"]?true:false,
            ],
            [
                "key"=>"Hoarding Board(s) ?",
                "assetsVal"=>$data["assessment_data"]["is_hoarding_board"] =="t" ?"Yes":"No",
                "verificationVal"=>$data["verification_data"]["is_hoarding_board"] =="t" ?"Yes":"No",
                "flag"=>$data["assessment_data"]["is_hoarding_board"]==$data["verification_data"]["is_hoarding_board"]?true:false,
            ],
            [
                "key"=>"Is Petrol Pump ?",
                "assetsVal"=>$data["assessment_data"]["is_petrol_pump"] =="t" ?"Yes":"No",
                "verificationVal"=>$data["verification_data"]["is_petrol_pump"] =="t" ?"Yes":"No",
                "flag"=>$data["assessment_data"]["is_petrol_pump"]==$data["verification_data"]["is_petrol_pump"]?true:false,
            ],
            [
                "key"=>"Water Harvesting Provision ?",
                "assetsVal"=>$data["assessment_data"]["is_water_harvesting"] =="t" ?"Yes":"No",
                "verificationVal"=>$data["verification_data"]["is_water_harvesting"] =="t" ?"Yes":"No",
                "flag"=>$data["assessment_data"]["is_water_harvesting"]==$data["verification_data"]["is_water_harvesting"]?true:false,
            ],
        ];
        if(isset($data["saf_floor_data"])){
            $safFloar = $data["saf_floor_data"];
            $vefyFloar = $data["verification_floor_data"];            
            $capData["floorComp"]["head"]=[
                " ","Floor No.","Usage Type","Occupancy Type","Construction Type","Built Up Area (in Sq. Ft.)","Carpet Area (in Sq. Ft.)","Date of Completion"
            ];
            foreach($safFloar as $val){
                $sFloor = $val;
                $vFloor = isset($val["field_verification_dtl_id"]) ? $val : array_values(array_filter($vefyFloar,function($floar)use($val){
                    return ($floar["saf_floor_dtl_id"]==$val["id"]);
                })??[])[0]??[];
                $capData["floorComp"]["floors"][] = [
                        "assessedVal"=>[
                            "val_type"=>"Self Assessed",
                            "floor_name"=>$sFloor["floor_name"]??"",
                            "usage_type"=>$sFloor["usage_type"]??"",
                            "occupancy_name"=>$sFloor["occupancy_name"]??"",
                            "construction_type"=>$sFloor["construction_type"]??"",
                            "builtup_area"=>$sFloor["builtup_area"]??"",
                            "carpet_area"=>$sFloor["carpet_area"]??"",
                        ],
                        "VerificationVal"=>[
                            "val_type"=>"Verification",
                            "floor_name"=>$vFloor["floor_name"]??"",
                            "usage_type"=>$vFloor["usage_type"]??"",
                            "occupancy_name"=>$vFloor["occupancy_name"]??"",
                            "construction_type"=>$vFloor["construction_type"]??"",
                            "builtup_area"=>$vFloor["builtup_area"]??"",
                            "carpet_area"=>$vFloor["carpet_area"]??"",
                        ],
                        "flag"=>[
                            "val_type"=>"check",
                            "floor_name"=>($sFloor["floor_name"]??"")==($vFloor["floor_name"]??"")?true:false,
                            "usage_type"=>($sFloor["usage_type"]??"") == ($vFloor["usage_type"]??"") ?true:false,
                            "occupancy_name"=>($sFloor["occupancy_name"]??"") == ($vFloor["occupancy_name"]??"") ?true:false,
                            "construction_type"=>($sFloor["construction_type"]??"") == ($vFloor["construction_type"]??"") ?true:false,
                            "builtup_area"=>($sFloor["builtup_area"]??"") == ($vFloor["builtup_area"]??"") ?true:false,
                            "carpet_area"=>($sFloor["carpet_area"]??"") == ($vFloor["carpet_area"]??"") ?true:false,
                        ],
                ];
            }
        }
        if(isset($data["verification_floor_data"])){
            $extraFloor = array_filter($data["verification_floor_data"],function($val){
                return !$val["saf_floor_dtl_id"];
            });
            if($extraFloor){
                $capData["extraFloor"]["head"]=[
                    " ","Floor No.","Usage Type","Occupancy Type","Construction Type","Built Up Area (in Sq. Ft.)","Carpet Area (in Sq. Ft.)","Date of Completion"
                ];
            }
            foreach($extraFloor as $val){
                $sFloor = [];
                $vFloor = $val;
                $capData["extraFloor"]["floors"][] = $vFloor;[
                    [
                        "assessedVal"=>[
                            "val_type"=>"Self Assessed",
                            "floor_name"=>$sFloor["floor_name"]??"",
                            "usage_type"=>$sFloor["usage_type"]??"",
                            "occupancy_name"=>$sFloor["occupancy_name"]??"",
                            "construction_type"=>$sFloor["construction_type"]??"",
                            "builtup_area"=>$sFloor["builtup_area"]??"",
                            "carpet_area"=>$sFloor["carpet_area"]??"",
                        ],
                        "VerificationVal"=>[
                            "val_type"=>"Verification",
                            "floor_name"=>$vFloor["floor_name"]??"",
                            "usage_type"=>$vFloor["usage_type"]??"",
                            "occupancy_name"=>$vFloor["occupancy_name"]??"",
                            "construction_type"=>$vFloor["construction_type"]??"",
                            "builtup_area"=>$vFloor["builtup_area"]??"",
                            "carpet_area"=>$vFloor["carpet_area"]??"",
                        ],
                        "flag"=>[
                            "val_type"=>"check",
                            "floor_name"=>($sFloor["floor_name"]??"")==($vFloor["floor_name"]??"")?true:false,
                            "usage_type"=>($sFloor["usage_type"]??"") == ($vFloor["usage_type"]??"") ?true:false,
                            "occupancy_name"=>($sFloor["occupancy_name"]??"") == ($vFloor["occupancy_name"]??"") ?true:false,
                            "construction_type"=>($sFloor["construction_type"]??"") == ($vFloor["construction_type"]??"") ?true:false,
                            "builtup_area"=>($sFloor["builtup_area"]??"") == ($vFloor["builtup_area"]??"") ?true:false,
                            "carpet_area"=>($sFloor["carpet_area"]??"") == ($vFloor["carpet_area"]??"") ?true:false,
                        ],
                    ],
                ];
            }
        }
        $data["capData"]=$capData;//dd($capData["extraFloor"]["floors"][0]["floor_name"]);
        return $data;
    }
	
}
