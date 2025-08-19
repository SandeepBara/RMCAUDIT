<?php
namespace App\Controllers\Reports;
use Exception;

class PropReports
{
    public function __construct()
    {

    }

    public function addExtraQuote($stringhData) {
        return str_replace("'", "''", $stringhData);
    }
    public function safPropIndividualDemandAndCollecton($data) {
        try{
            $where = "";
            if($data['ward_mstr_id']!='') {
                $where .= " AND tbl_prop_dtl.ward_mstr_id=".$data['ward_mstr_id'];
            }
            if($data['search_param']!='') {
                $search_param = $data['search_param'];
                $where .= " AND (tbl_prop_dtl.holding_no ILIKE '%$search_param%'
                            OR tbl_prop_dtl.new_holding_no ILIKE '%$search_param%'
                            OR tbl_saf_dtl.saf_no ILIKE '%$search_param%'
                            OR tbl_prop_owner_detail.owner_name ILIKE '%$search_param%'
                            OR tbl_prop_owner_detail.mobile_no ILIKE '%$search_param%'
                            OR tbl_prop_dtl.prop_address ILIKE '%$search_param%'
                )";
            }

            $sql = "SELECT
                        view_ward_mstr.ward_no,
                        tbl_prop_dtl.holding_no,
                        (CASE WHEN tbl_prop_dtl.new_holding_no IS NULL OR tbl_prop_dtl.new_holding_no='' THEN 'N/A' ELSE tbl_prop_dtl.new_holding_no END) AS new_holding_no,
                        (CASE WHEN tbl_saf_dtl.saf_no IS NULL  THEN 'N/A' ELSE tbl_saf_dtl.saf_no END) AS saf_no,
                        tbl_prop_owner_detail.owner_name,
                        tbl_prop_owner_detail.mobile_no,
                        tbl_prop_dtl.prop_address,
                        (CASE WHEN tbl_saf_dtl.assessment_type IS NULL  THEN 'N/A' ELSE tbl_saf_dtl.assessment_type END) AS assessment_type,
                        (CASE WHEN tbl_prop_floor_details.usage_type IS NULL THEN 'N/A' ELSE tbl_prop_floor_details.usage_type END) AS usage_type,
                        (CASE WHEN tbl_prop_floor_details.construction_type IS NULL THEN 'N/A' ELSE tbl_prop_floor_details.construction_type END) AS construction_type,
                        (CASE WHEN tbl_super_arrear_demand.super_arrear_demand IS NULL THEN '0.00' ELSE tbl_super_arrear_demand.super_arrear_demand END) AS super_arrear_demand,
                        (CASE WHEN tbl_arrear_demand.arrear_demand IS NULL THEN '0.00' ELSE tbl_arrear_demand.arrear_demand END) AS arrear_demand,
                        (CASE WHEN tbl_current_demand.current_demand IS NULL THEN '0.00' ELSE tbl_current_demand.current_demand END) AS current_demand,
                        COALESCE(tbl_super_arrear_demand.super_arrear_demand, 0)+COALESCE(tbl_arrear_demand.arrear_demand, 0)+COALESCE(tbl_current_demand.current_demand, 0) as total_demand,
                        (CASE WHEN tbl_super_arrear_collection.super_arrear_collection IS NULL THEN '0.00' ELSE tbl_super_arrear_collection.super_arrear_collection END) AS super_arrear_collection,
                        (CASE WHEN tbl_arrear_collection.arrear_collection IS NULL THEN '0.00' ELSE tbl_arrear_collection.arrear_collection END) AS arrear_collection,
                        (CASE WHEN tbl_current_collection.current_collection IS NULL THEN '0.00' ELSE tbl_current_collection.current_collection END) AS current_collection,
                        COALESCE(tbl_super_arrear_collection.super_arrear_collection, 0)+COALESCE(tbl_arrear_collection.arrear_collection, 0)+COALESCE(tbl_current_collection.current_collection, 0) as total_collection,
                        (CASE WHEN tbl_penalty.penalty IS NULL THEN '0.00' ELSE tbl_penalty.penalty END) AS penalty,
                        (CASE WHEN tbl_rebate.rebate IS NULL THEN '0.00' ELSE tbl_rebate.rebate END) AS rebate,
                        '0.00' AS advance,
                        '0.00' AS adjust,
                        (COALESCE(tbl_super_arrear_demand.super_arrear_demand, 0)+COALESCE(tbl_arrear_demand.arrear_demand, 0)+COALESCE(tbl_current_demand.current_demand, 0))-(COALESCE(tbl_super_arrear_collection.super_arrear_collection, 0)+COALESCE(tbl_arrear_collection.arrear_collection, 0)+COALESCE(tbl_current_collection.current_collection, 0)) AS total_due
                    FROM tbl_prop_dtl
                    INNER JOIN view_ward_mstr ON view_ward_mstr.id=tbl_prop_dtl.ward_mstr_id
                    LEFT JOIN tbl_saf_dtl ON tbl_saf_dtl.id=tbl_prop_dtl.saf_dtl_id
                    INNER JOIN (SELECT
                                    prop_dtl_id,
                                    STRING_AGG(owner_name, ',') AS owner_name,
                                    STRING_AGG(mobile_no::TEXT, ',') AS mobile_no
                                FROM tbl_prop_owner_detail
                                WHERE status=1
                                GROUP BY prop_dtl_id) AS tbl_prop_owner_detail ON tbl_prop_owner_detail.prop_dtl_id=tbl_prop_dtl.id
                    LEFT JOIN (SELECT
                                    tbl_prop_floor_details.prop_dtl_id,
                                    STRING_AGG(tbl_usage_type_mstr.usage_type, ',') AS usage_type,
                                    STRING_AGG(tbl_const_type_mstr.construction_type, ',') AS construction_type
                                    FROM tbl_prop_floor_details
                                    INNER JOIN tbl_usage_type_mstr ON tbl_usage_type_mstr.id=tbl_prop_floor_details.usage_type_mstr_id
                                    INNER JOIN tbl_const_type_mstr ON tbl_const_type_mstr.id=tbl_prop_floor_details.const_type_mstr_id
                                WHERE tbl_prop_floor_details.status=1
                                GROUP BY tbl_prop_floor_details.prop_dtl_id) AS tbl_prop_floor_details ON tbl_prop_floor_details.prop_dtl_id=tbl_prop_dtl.id
                    LEFT JOIN (SELECT
                                prop_dtl_id,
                                SUM(amount) AS super_arrear_demand
                            FROM tbl_prop_demand
                            WHERE status=1 AND paid_status IN (0,1) AND fy_mstr_id<51
                            GROUP BY prop_dtl_id) AS tbl_super_arrear_demand ON tbl_super_arrear_demand.prop_dtl_id=tbl_prop_dtl.id
                    LEFT JOIN (SELECT
                                prop_dtl_id,
                                SUM(amount) AS arrear_demand
                            FROM tbl_prop_demand
                            WHERE status=1 AND paid_status IN (0,1) AND fy_mstr_id=51
                            GROUP BY prop_dtl_id) AS tbl_arrear_demand ON tbl_arrear_demand.prop_dtl_id=tbl_prop_dtl.id
                    LEFT JOIN (SELECT
                                prop_dtl_id,
                                SUM(amount) AS current_demand
                            FROM tbl_prop_demand
                            WHERE status=1 AND paid_status IN (0,1) AND fy_mstr_id=52
                            GROUP BY prop_dtl_id) AS tbl_current_demand ON tbl_current_demand.prop_dtl_id=tbl_prop_dtl.id
                    LEFT JOIN (SELECT
                                prop_dtl_id,
                                SUM(amount) AS super_arrear_collection
                            FROM tbl_prop_demand
                            WHERE status=1 AND paid_status=1 AND fy_mstr_id<51
                            GROUP BY prop_dtl_id) AS tbl_super_arrear_collection ON tbl_super_arrear_collection.prop_dtl_id=tbl_prop_dtl.id
                    LEFT JOIN (SELECT
                                prop_dtl_id,
                                SUM(amount) AS arrear_collection
                            FROM tbl_prop_demand
                            WHERE status=1 AND paid_status=1 AND fy_mstr_id=51
                            GROUP BY prop_dtl_id) AS tbl_arrear_collection ON tbl_arrear_collection.prop_dtl_id=tbl_prop_dtl.id
                    LEFT JOIN (SELECT
                                prop_dtl_id,
                                SUM(amount) AS current_collection
                            FROM tbl_prop_demand
                            WHERE status=1 AND paid_status=1 AND fy_mstr_id=52
                            GROUP BY prop_dtl_id) AS tbl_current_collection ON tbl_current_collection.prop_dtl_id=tbl_prop_dtl.id
                    LEFT JOIN (SELECT
                                tbl_transaction.prop_dtl_id AS prop_dtl_id,
                                SUM(tbl_transaction_fine_rebet_details.amount) AS penalty
                            FROM tbl_transaction_fine_rebet_details
                            INNER JOIN tbl_transaction ON tbl_transaction.id=tbl_transaction_fine_rebet_details.transaction_id
                            WHERE tbl_transaction.tran_type='Property'
                                    AND tbl_transaction_fine_rebet_details.status=1
                                    AND tbl_transaction_fine_rebet_details.value_add_minus='Minus'
                            GROUP BY tbl_transaction.prop_dtl_id
                    ) AS tbl_penalty ON tbl_penalty.prop_dtl_id=tbl_prop_dtl.id
                    LEFT JOIN (SELECT
                                tbl_transaction.prop_dtl_id AS prop_dtl_id,
                                SUM(tbl_transaction_fine_rebet_details.amount) AS rebate
                            FROM tbl_transaction_fine_rebet_details
                            INNER JOIN tbl_transaction ON tbl_transaction.id=tbl_transaction_fine_rebet_details.transaction_id
                            WHERE tbl_transaction.tran_type='Property'
                                    AND tbl_transaction_fine_rebet_details.status=1
                                    AND tbl_transaction_fine_rebet_details.value_add_minus='ADD'
                            GROUP BY tbl_transaction.prop_dtl_id
                    ) AS tbl_rebate ON tbl_rebate.prop_dtl_id=tbl_prop_dtl.id
                    WHERE
                        tbl_prop_dtl.status=1".$where;
                return $this->addExtraQuote($sql);
        } catch(Exception $e) {

        }
    }

    public function excelExportSAM($data) {
        try{
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

            $sql = "SELECT
                        ward_no,
                        tbl_saf_dtl.saf_no,
                        tbl_saf_dtl.assessment_type,
                        tbl_saf_memo_dtl.memo_no,
                        tbl_saf_memo_dtl.holding_no,
                        tbl_saf_owner_detail.owner_name,
                        tbl_saf_owner_detail.mobile_no,
                        tbl_saf_memo_dtl.created_on
                    FROM tbl_saf_memo_dtl
                    INNER JOIN tbl_saf_dtl ON tbl_saf_dtl.id=tbl_saf_memo_dtl.saf_dtl_id ".$wardWhere.$wherAll."
                    INNER JOIN view_ward_mstr on view_ward_mstr.id=tbl_saf_dtl.ward_mstr_id
                    JOIN (
                        SELECT saf_dtl_id,
                        STRING_AGG(owner_name, ', ') AS owner_name,
                        STRING_AGG(mobile_no::TEXT, ', ') AS mobile_no
                        FROM tbl_saf_owner_detail WHERE status=1
                        GROUP BY saf_dtl_id
                    ) AS tbl_saf_owner_detail ON tbl_saf_owner_detail.saf_dtl_id=tbl_saf_memo_dtl.saf_dtl_id
                    WHERE tbl_saf_memo_dtl.memo_type='SAM' AND tbl_saf_memo_dtl.status=1
                    ORDER BY tbl_saf_memo_dtl.created_on DESC";
                //return $this->addExtraQuote($sql);
                return $sql;
        } catch(Exception $e) {

        }
    }

    public function excelExportFAM($data) {
        try{
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

            $sql = "SELECT
                        CONCAT('`', ward_no) AS ward_no,
                        tbl_saf_dtl.saf_no,
                        tbl_saf_dtl.assessment_type,
                        tbl_saf_memo_dtl.memo_no,
                        CONCAT('`', tbl_saf_memo_dtl.holding_no) AS holding_no,
                        tbl_saf_owner_detail.owner_name,
                        CONCAT('`', tbl_saf_owner_detail.mobile_no) AS mobile_no,
                        tbl_saf_memo_dtl.created_on
                    FROM tbl_saf_memo_dtl
                    INNER JOIN tbl_saf_dtl ON tbl_saf_dtl.id=tbl_saf_memo_dtl.saf_dtl_id ".$wardWhere.$wherAll."
                    INNER JOIN view_ward_mstr on view_ward_mstr.id=tbl_saf_dtl.ward_mstr_id
                    JOIN (
                        SELECT saf_dtl_id,
                        STRING_AGG(owner_name, ', ') AS owner_name,
                        STRING_AGG(mobile_no::TEXT, ', ') AS mobile_no
                        FROM tbl_saf_owner_detail WHERE status=1
                        GROUP BY saf_dtl_id
                    ) AS tbl_saf_owner_detail ON tbl_saf_owner_detail.saf_dtl_id=tbl_saf_memo_dtl.saf_dtl_id
                    WHERE tbl_saf_memo_dtl.memo_type='FAM' AND tbl_saf_memo_dtl.status=1
                    ORDER BY tbl_saf_memo_dtl.created_on DESC";
                return $sql;
                //return $this->addExtraQuote($sql);
        } catch(Exception $e) {

        }
    }


    public function excelExportBTCList($data) {
        try{
            $wherePropType = "";
            $whereAssessmentType = "";
            $whereSearchPrm = "";
            $whereWard = "";
            $whereDateRange = "";
            if (isset($data["ward_mstr_id"]) && isset($data["search_param"]) && isset($data["prop_type_mstr_id"]) && isset($data["assessment_type"])) {
                if ($data["assessment_type"]!="") {
                    $whereAssessmentType = " AND tbl_saf_dtl.assessment_type='".$data["assessment_type"]."'";
                }
                if ($data["prop_type_mstr_id"]!="") {
                    $wherePropType = " AND tbl_saf_dtl.prop_type_mstr_id='".$data["prop_type_mstr_id"]."'";
                }
                if ($data["ward_mstr_id"]!="") {
                    $whereWard = " AND tbl_saf_dtl.ward_mstr_id='".$data["ward_mstr_id"]."'";
                }
                if ($data["search_param"]!="") {
                    $whereSearchPrm = " AND (tbl_saf_dtl.saf_no ~* '".$data["search_param"]."' 
                                            OR owner_dtl.owner_name ~* '".$data["search_param"]."' 
                                            OR owner_dtl.mobile_no ~* '".$data["search_param"]."')";
                }
                $from_date = $data["from_date"];
                $to_date = $data["to_date"];
                if ($from_date != "" && $to_date != "") {
                    $whereDateRange = " AND tbl_level_pending_dtl.forward_date between '".$from_date."' AND '".$to_date."'";
                }
            }
            // CONCAT('`', view_ward_mstr.ward_no) AS ward_no,
            $sql = "SELECT 
                        tbl_prop_type_mstr.property_type,
                        CONCAT('~', view_ward_mstr.ward_no) AS ward_no,
                        tbl_saf_dtl.saf_no,
                        owner_dtl.owner_name,
                        CONCAT('~', owner_dtl.mobile_no) AS mobile_no,
                        tbl_saf_dtl.assessment_type,
						tbl_saf_dtl.apply_date,
                        view_user_type_mstr.user_type as rejected_by,
                        tbl_level_pending_dtl.remarks,
                        tbl_level_pending_dtl.forward_date::text
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
            JOIN view_user_type_mstr ON view_user_type_mstr.id = tbl_level_pending_dtl.sender_user_type_id
                WHERE  tbl_saf_dtl.id NOT IN (select saf_dtl_id from tbl_btc_hide)
                    AND tbl_saf_dtl.status=1 AND tbl_saf_dtl.saf_pending_status!=1 AND
                    tbl_level_pending_dtl.verification_status=2
                    AND tbl_level_pending_dtl.receiver_user_type_id=11
                    AND tbl_level_pending_dtl.status=1 ".$whereSearchPrm.$whereAssessmentType.$wherePropType.$whereDateRange."
                    ORDER BY tbl_level_pending_dtl.id DESC";
                // return $this->addExtraQuote($sql); //for function export
                return $sql;
        } catch(Exception $e) {

        }
    }







    public function remainingDemandPropertyDtl($data) {
        try{
            $wardWhere = "";
            if (isset($data['ward_mstr_id'])) {
                if ($data['ward_mstr_id'] != null && $data['ward_mstr_id']!='') {
                    $wardWhere = " AND tbl_prop_dtl.ward_mstr_id='".$data['ward_mstr_id']."'";
                }
            }
            $sql = "SELECT
                        view_ward_mstr.ward_no,
                        tbl_prop_dtl.holding_no,
                        tbl_prop_dtl.new_holding_no,
                        owner_dtl.owner_name,
                        owner_dtl.mobile_no,
                        tbl_prop_dtl.prop_address,
                        (SELECT get_fy(demand_dtl.min_due_date)) AS from_fyear,
                        (SELECT get_qtr_by_date(demand_dtl.min_due_date)) AS from_qtr,
                        (SELECT get_fy(demand_dtl.max_due_date)) AS upto_fyear,
                        (SELECT get_qtr_by_date(demand_dtl.max_due_date)) AS upto_qtr,
                        demand_dtl.demand_amt
                    FROM tbl_prop_dtl
                    INNER JOIN view_ward_mstr ON view_ward_mstr.id=tbl_prop_dtl.ward_mstr_id ".$wardWhere."
                    INNER JOIN (
                        SELECT
                            prop_dtl_id,
                            STRING_AGG(owner_name, ',') AS owner_name,
                            STRING_AGG(mobile_no::TEXT, ',') AS mobile_no
                        FROM tbl_prop_owner_detail
                        WHERE status=1
                        GROUP BY prop_dtl_id
                    ) AS owner_dtl ON owner_dtl.prop_dtl_id=tbl_prop_dtl.id
                    INNER JOIN (
                        SELECT
                            prop_dtl_id,
                            MIN(due_date) AS min_due_date,
                            MAX(due_date) AS max_due_date,
                            SUM(amount-adjust_amt) AS demand_amt
                        FROM tbl_prop_demand
                        WHERE status=1 AND paid_status=0
                        GROUP BY prop_dtl_id
                    ) AS demand_dtl ON demand_dtl.prop_dtl_id=tbl_prop_dtl.id
                    WHERE tbl_prop_dtl.status=1 and char_length(tbl_prop_dtl.new_holding_no)>0
                    ORDER BY (substring(view_ward_mstr.ward_no, '^[0-9]+'))::int,view_ward_mstr.ward_no";
                //return $this->addExtraQuote($sql);
                return $sql;
        } catch(Exception $e) {

        }
    }


    public function outboxListUlbTcDtl($data) {
        try{
            $session = session();
            $emp_mstr = $session->get("emp_details");
            $login_user_type_mstr_id = $emp_mstr["user_type_mstr_id"];
            $login_emp_details_id = $emp_mstr["id"];
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
                    $whereSearchPrm = " AND (tbl_saf_dtl.saf_no ILIKE '%".$data["search_param"]."%'
                                            OR owner_dtl.owner_name ILIKE '%".$data["search_param"]."%'
                                            OR owner_dtl.mobile_no ILIKE '%".$data["search_param"]."%')";
                }
            }
            $sql = "SELECT
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
                        AND tbl_level_pending_dtl.status='1' ".$whereSearchPrm.$whereAssessmentType.$wherePropType."
                    ORDER BY tbl_level_pending_dtl.id DESC";
                return $this->addExtraQuote($sql);
        } catch(Exception $e) {

        }
    }

    public function inboxListUlbTcDtl($data) {
        try{
            $session = session();
            $emp_mstr = $session->get("emp_details");
            $login_user_type_mstr_id = $emp_mstr["user_type_mstr_id"];
            $login_emp_details_id = $emp_mstr["id"];
            $whereWard = "";
            $wherePropType = "";
            $whereAssessmentType = "";
            $whereSearchPrm = "";
            $where_old_new = "";
            if(isset($data["data_type"]) && $data["data_type"]=='index_ulb_tc')
            {
                $where_old_new = " AND tbl_saf_dtl.apply_date < '2022-08-05'";
            }
            if(isset($data["data_type"]) && $data["data_type"]=='index_ulb_tc_new')
            {
                $where_old_new = " AND tbl_saf_dtl.apply_date >='2022-08-05'";
            }

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
                        tbl_prop_type_mstr.property_type,
                        view_ward_mstr.ward_no,
                        tbl_saf_dtl.apply_date,
                        tbl_saf_dtl.saf_no,
                        owner_dtl.owner_name,
                        owner_dtl.mobile_no,
                        tbl_saf_dtl.assessment_type,
                        tbl_saf_dtl.prop_address,
                        tbl_level_pending_dtl.forward_date,
                        tbl_level_pending_dtl.forward_time
                    FROM tbl_level_pending_dtl
                    INNER JOIN tbl_saf_dtl ON tbl_level_pending_dtl.saf_dtl_id = tbl_saf_dtl.id ".$whereWard.$where_old_new."
                    INNER JOIN tbl_prop_type_mstr ON tbl_prop_type_mstr.id=tbl_saf_dtl.prop_type_mstr_id
                    INNER JOIN ( SELECT tbl_saf_owner_detail.saf_dtl_id,
                            string_agg(tbl_saf_owner_detail.owner_name::text, ', '::text) AS owner_name,
                            string_agg(tbl_saf_owner_detail.mobile_no::text, ', '::text) AS mobile_no
                        FROM tbl_saf_owner_detail
                        GROUP BY tbl_saf_owner_detail.saf_dtl_id
                    ) owner_dtl ON owner_dtl.saf_dtl_id = tbl_level_pending_dtl.saf_dtl_id
                    INNER JOIN view_ward_mstr ON view_ward_mstr.id = tbl_saf_dtl.ward_mstr_id
                    WHERE
                        receiver_user_type_id=".$login_user_type_mstr_id."
                        AND tbl_level_pending_dtl.verification_status=0
                        AND doc_upload_status='1'
                        AND tbl_level_pending_dtl.status='1' ".$whereSearchPrm.$whereAssessmentType.$wherePropType."
                    ORDER BY tbl_level_pending_dtl.forward_date DESC, tbl_level_pending_dtl.forward_time  DESC";
                return $this->addExtraQuote($sql);
        } catch(Exception $e) {

        }
    }
}
