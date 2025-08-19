<?php 
namespace App\Controllers\Reports;
use CodeIgniter\Controller;
use Predis\Client;
use App\Models\model_fy_mstr;
class MiniDashboardReport extends Controller
{
	protected $db;
    protected $redis_client;
	protected $model_fy_mstr;
	public function __construct() {
        helper(['db_helper','form_helper']);
		$this->dbSystem = db_connect('db_system');
		if($db_name = dbConfig("property")){
			$this->db = db_connect($db_name); 
		}
        $this->redis_client = new Client();
		$this->model_fy_mstr = new model_fy_mstr($this->dbSystem);
    }

    function __destruct() {
    	if ($this->db) $this->db->close();
    }

    public function getPropertyDCB() {
        $currentFY = getFY();
        $fy_mst_id = $this->model_fy_mstr->getFyByFy(['fy' => $currentFY])["id"];
        
        $sql = "WITH total_prop_demand AS (
                    SELECT SUM(amount-adjust_amt) AS total_demand FROM tbl_prop_demand 
                    INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id 
                    WHERE paid_status IN (0,1) AND tbl_prop_demand.status=1 AND tbl_prop_dtl.status=1 AND fy_mstr_id=$fy_mst_id 
                    AND tbl_prop_demand.due_date is not null
                    and char_length(tbl_prop_dtl.new_holding_no)>0
                ),
                total_prop_collection AS (
                    SELECT SUM(amount-adjust_amt) AS total_collection FROM tbl_prop_demand 
                    INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id
                    WHERE paid_status=1 AND tbl_prop_demand.status=1 AND tbl_prop_dtl.status=1 AND fy_mstr_id=$fy_mst_id AND tbl_prop_demand.due_date is not null
                    and char_length(tbl_prop_dtl.new_holding_no)>0
                ),
                total_saf_collection AS (
                    SELECT SUM(amount) AS collection FROM tbl_saf_demand WHERE paid_status=1 AND status=1 AND fy_mstr_id=$fy_mst_id
                )
                SELECT
                    total_prop_demand.total_demand, 
                    (total_prop_collection.total_collection+total_saf_collection.collection) as total_collection,  
                    (total_prop_demand.total_demand-total_prop_collection.total_collection+total_saf_collection.collection) AS total_due
                FROM total_prop_demand, total_prop_collection, total_saf_collection";

                //$this->redis_client->del("mini_dashboard_property_dbc");
                $property_detail = $this->redis_client->get("mini_dashboard_property_dbc");
                if (!$property_detail) {
                    if ($property_detail = $this->db->query($sql)->getFirstRow("array")) {
                        $this->redis_client->setEx("mini_dashboard_property_dbc", 300, json_encode($property_detail));
                    }
                } else {
                    $property_detail = json_decode($property_detail, true);
                }
        return $property_detail;
    }

    

    public function getPropertyPendingAtLevelBefore2022() {
        $sql = "WITH total_saf_count AS (
                    SELECT 
						json_agg(json_build_object('assessment_type', assessment_type, 'total_count', total_count)) AS before2022_total_saf
					FROM (
						SELECT * FROM(
                        SELECT
                            tbl_saf_dtl.assessment_type,
                            COUNT(DISTINCT(tbl_saf_dtl.id)) AS total_count
                        FROM tbl_saf_dtl 
                        INNER JOIN tbl_transaction ON tbl_transaction.prop_dtl_id = tbl_saf_dtl.id 
                                    AND tbl_saf_dtl.apply_date<'2022-04-01'
                                    AND tbl_transaction.tran_type='Saf'
                                    AND tbl_transaction.status IN (1,2)
						WHERE tbl_saf_dtl.status=1
                        GROUP BY tbl_saf_dtl.assessment_type
						) as raw
                        ORDER BY 
                        CASE 
                        WHEN  assessment_type='New Assessment' THEN 1
                        WHEN  assessment_type='Reassessment' THEN 2
                        WHEN  assessment_type='Mutation' THEN 3
                        ELSE 4 END asc 
					) tbl
                ),
                total_dealing_assistant_pending AS (
                    SELECT 
                        COUNT(DISTINCT(tbl_level_pending_dtl.saf_dtl_id)) AS no_of_pending_by_dealing_assistant 
                    FROM tbl_saf_dtl
                    INNER JOIN tbl_level_pending_dtl ON tbl_level_pending_dtl.saf_dtl_id=tbl_saf_dtl.id
                    INNER JOIN (SELECT saf_dtl_id, MAX(id) AS level_pending_dtl_id FROM tbl_level_pending_dtl WHERE status=1 GROUP BY saf_dtl_id) AS level_pending_dtl ON level_pending_dtl.level_pending_dtl_id=tbl_level_pending_dtl.id
                    WHERE 
                        tbl_saf_dtl.status=1 
                        AND tbl_saf_dtl.apply_date<'2022-04-01'
                        AND tbl_level_pending_dtl.receiver_user_type_id=6 
                        AND tbl_level_pending_dtl.status=1 
                        AND tbl_level_pending_dtl.verification_status=0
                ), 
                total_ulb_tc_pending AS (
                    SELECT 
                        COUNT(DISTINCT(tbl_level_pending_dtl.saf_dtl_id)) AS no_of_pending_by_ulb_tc 
                    FROM tbl_saf_dtl
                    INNER JOIN tbl_level_pending_dtl ON tbl_level_pending_dtl.saf_dtl_id=tbl_saf_dtl.id
                    INNER JOIN (SELECT saf_dtl_id, MAX(id) AS level_pending_dtl_id FROM tbl_level_pending_dtl WHERE status=1 GROUP BY saf_dtl_id) AS level_pending_dtl ON level_pending_dtl.level_pending_dtl_id=tbl_level_pending_dtl.id
                    INNER JOIN (
                        SELECT
                            geotag_dtl_id
                        FROM tbl_saf_geotag_upload_dtl
                        WHERE status=1
                        GROUP BY geotag_dtl_id
                    ) AS geotag_dtl ON geotag_dtl.geotag_dtl_id=tbl_saf_dtl.id
                    WHERE 
                        tbl_saf_dtl.status=1 
                        AND tbl_saf_dtl.apply_date<'2022-04-01'
                        AND tbl_level_pending_dtl.receiver_user_type_id=7 
                        AND tbl_level_pending_dtl.status=1 
                        AND tbl_level_pending_dtl.verification_status=0
                ),
                total_section_incharge_pending AS (
                    SELECT 
                        COUNT(DISTINCT(tbl_level_pending_dtl.saf_dtl_id)) AS no_of_pending_by_section_incharge 
                    FROM tbl_saf_dtl
                    INNER JOIN tbl_level_pending_dtl ON tbl_level_pending_dtl.saf_dtl_id=tbl_saf_dtl.id
                    WHERE 
                        tbl_saf_dtl.status=1 
                        AND tbl_saf_dtl.apply_date<'2022-04-01'
                        AND tbl_level_pending_dtl.receiver_user_type_id=9 
                        AND tbl_level_pending_dtl.status=1 
                        AND tbl_level_pending_dtl.verification_status=0
                ),
                total_executive_officer_pending AS (
                    SELECT 
                        COUNT(DISTINCT(tbl_level_pending_dtl.saf_dtl_id)) AS no_of_pending_by_executive_officer 
                    FROM tbl_saf_dtl
                    INNER JOIN tbl_level_pending_dtl ON tbl_level_pending_dtl.saf_dtl_id=tbl_saf_dtl.id
                    WHERE 
                        tbl_saf_dtl.status=1 
                        AND tbl_saf_dtl.apply_date<'2022-04-01'
                        AND tbl_level_pending_dtl.receiver_user_type_id=10 
                        AND tbl_level_pending_dtl.status=1 
                        AND tbl_level_pending_dtl.verification_status=0
                )
                SELECT
                    before2022_total_saf,
                    no_of_pending_by_dealing_assistant,
                    no_of_pending_by_ulb_tc,
                    no_of_pending_by_section_incharge,
                    no_of_pending_by_executive_officer
                FROM total_saf_count, total_dealing_assistant_pending, total_ulb_tc_pending, total_section_incharge_pending, total_executive_officer_pending";

        //$this->redis_client->del("property_pending_at_level_before_2022");
        $property_pending_at_level_before_2022 = $this->redis_client->get("property_pending_at_level_before_2022");
        if (!$property_pending_at_level_before_2022) {
            if ($property_pending_at_level_before_2022 = $this->db->query($sql)->getFirstRow("array")) {
                $this->redis_client->setEx("property_pending_at_level_before_2022", 300, json_encode($property_pending_at_level_before_2022));
            }
        } else {
            $property_pending_at_level_before_2022 = json_decode($property_pending_at_level_before_2022, true);
        }
        $property_pending_at_level_before_2022["before2022_total_saf"] = json_decode($property_pending_at_level_before_2022["before2022_total_saf"], true);
        return $property_pending_at_level_before_2022;
    }

    public function getPropertyPendingAtLevel2022() {
        $sql = "WITH total_saf_count AS (
                    SELECT 
						json_agg(json_build_object('assessment_type', assessment_type, 'total_count', total_count)) AS current_2022_total_saf
					FROM (
						SELECT * FROM(
                        SELECT
                            tbl_saf_dtl.assessment_type,
                            COUNT(DISTINCT(tbl_saf_dtl.id)) AS total_count
                        FROM tbl_saf_dtl 
                        INNER JOIN tbl_transaction ON tbl_transaction.prop_dtl_id = tbl_saf_dtl.id 
                                    AND tbl_saf_dtl.apply_date>='2022-04-01'
                                    AND tbl_transaction.tran_type='Saf'
                                    AND tbl_transaction.status IN (1,2)
						WHERE tbl_saf_dtl.status=1
                        GROUP BY tbl_saf_dtl.assessment_type
                        ) as raw
                        ORDER BY 
                        CASE 
                        WHEN  assessment_type='New Assessment' THEN 1
                        WHEN  assessment_type='Reassessment' THEN 2
                        WHEN  assessment_type='Mutation' THEN 3
                        ELSE 4 END asc  
					) tbl
                ),
                total_dealing_assistant_pending AS (
                    SELECT 
                        COUNT(DISTINCT(tbl_level_pending_dtl.saf_dtl_id)) AS no_of_pending_by_dealing_assistant 
                    FROM tbl_saf_dtl
                    INNER JOIN tbl_level_pending_dtl ON tbl_level_pending_dtl.saf_dtl_id=tbl_saf_dtl.id
                    INNER JOIN (SELECT saf_dtl_id, MAX(id) AS level_pending_dtl_id FROM tbl_level_pending_dtl WHERE status=1 GROUP BY saf_dtl_id) AS level_pending_dtl ON level_pending_dtl.level_pending_dtl_id=tbl_level_pending_dtl.id
                    WHERE 
                        tbl_saf_dtl.status=1 
                        AND tbl_saf_dtl.apply_date>='2022-04-01'
                        AND tbl_level_pending_dtl.receiver_user_type_id=6 
                        AND tbl_level_pending_dtl.status=1 
                        AND tbl_level_pending_dtl.verification_status=0
                ), 
                total_ulb_tc_pending AS (
                    SELECT 
                        COUNT(DISTINCT(tbl_level_pending_dtl.saf_dtl_id)) AS no_of_pending_by_ulb_tc 
                    FROM tbl_saf_dtl
                    INNER JOIN tbl_level_pending_dtl ON tbl_level_pending_dtl.saf_dtl_id=tbl_saf_dtl.id
                    INNER JOIN (SELECT saf_dtl_id, MAX(id) AS level_pending_dtl_id FROM tbl_level_pending_dtl WHERE status=1 GROUP BY saf_dtl_id) AS level_pending_dtl ON level_pending_dtl.level_pending_dtl_id=tbl_level_pending_dtl.id
                    INNER JOIN (
                        SELECT
                            geotag_dtl_id
                        FROM tbl_saf_geotag_upload_dtl
                        WHERE status=1
                        GROUP BY geotag_dtl_id
                    ) AS geotag_dtl ON geotag_dtl.geotag_dtl_id=tbl_saf_dtl.id
                    WHERE 
                        tbl_saf_dtl.status=1 
                        AND tbl_saf_dtl.apply_date>='2022-04-01'
                        AND tbl_level_pending_dtl.receiver_user_type_id=7 
                        AND tbl_level_pending_dtl.status=1 
                        AND tbl_level_pending_dtl.verification_status=0
                ),
                total_section_incharge_pending AS (
                    SELECT 
                        COUNT(DISTINCT(tbl_level_pending_dtl.saf_dtl_id)) AS no_of_pending_by_section_incharge 
                    FROM tbl_saf_dtl
                    INNER JOIN tbl_level_pending_dtl ON tbl_level_pending_dtl.saf_dtl_id=tbl_saf_dtl.id
                    WHERE 
                        tbl_saf_dtl.status=1 
                        AND tbl_saf_dtl.apply_date>='2022-04-01'
                        AND tbl_level_pending_dtl.receiver_user_type_id=9 
                        AND tbl_level_pending_dtl.status=1 
                        AND tbl_level_pending_dtl.verification_status=0
                ),
                total_executive_officer_pending AS (
                    SELECT 
                        COUNT(DISTINCT(tbl_level_pending_dtl.saf_dtl_id)) AS no_of_pending_by_executive_officer 
                    FROM tbl_saf_dtl
                    INNER JOIN tbl_level_pending_dtl ON tbl_level_pending_dtl.saf_dtl_id=tbl_saf_dtl.id
                    WHERE 
                        tbl_saf_dtl.status=1 
                        AND tbl_saf_dtl.apply_date>='2022-04-01'
                        AND tbl_level_pending_dtl.receiver_user_type_id=10 
                        AND tbl_level_pending_dtl.status=1 
                        AND tbl_level_pending_dtl.verification_status=0
                )
                SELECT
                    current_2022_total_saf,
                    no_of_pending_by_dealing_assistant,
                    no_of_pending_by_ulb_tc,
                    no_of_pending_by_section_incharge,
                    no_of_pending_by_executive_officer
                FROM total_saf_count, total_dealing_assistant_pending, total_ulb_tc_pending, total_section_incharge_pending, total_executive_officer_pending";

        //$this->redis_client->del("property_pending_at_level_2022");
        $property_pending_at_level_2022 = $this->redis_client->get("property_pending_at_level_2022");
        if (!$property_pending_at_level_2022) {
            if ($property_pending_at_level_2022 = $this->db->query($sql)->getFirstRow("array")) {
                $this->redis_client->setEx("property_pending_at_level_2022", 300, json_encode($property_pending_at_level_2022));
            }
        } else {
            $property_pending_at_level_2022 = json_decode($property_pending_at_level_2022, true);
        }
        $property_pending_at_level_2022["current_2022_total_saf"] = json_decode($property_pending_at_level_2022["current_2022_total_saf"], true);
        return $property_pending_at_level_2022;
    }


    // 09-04-25 (NEW DASHBOARD)
    
	public function getPropertyDCB_NEW()
    {
        $currentFY = getFY();
        $fy_mst_id = $this->model_fy_mstr->getFyByFy(['fy' => $currentFY])["id"];

        $sql = "WITH total_prop_demand AS (
                    SELECT SUM(amount-adjust_amt) AS total_demand FROM tbl_prop_demand 
                    INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id 
                    WHERE paid_status IN (0,1) AND tbl_prop_demand.status=1 AND tbl_prop_dtl.status=1 AND fy_mstr_id=$fy_mst_id 
                    AND tbl_prop_demand.due_date is not null
                    and char_length(tbl_prop_dtl.new_holding_no)>0
                ),
                total_prop_collection AS (
                    SELECT SUM(amount-adjust_amt) AS total_collection FROM tbl_prop_demand 
                    INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id
                    WHERE paid_status=1 AND tbl_prop_demand.status=1 AND tbl_prop_dtl.status=1 AND fy_mstr_id=$fy_mst_id AND tbl_prop_demand.due_date is not null
                    and char_length(tbl_prop_dtl.new_holding_no)>0
                ),
                total_saf_collection AS (
                    SELECT SUM(amount) AS collection FROM tbl_saf_demand WHERE paid_status=1 AND status=1 AND fy_mstr_id=$fy_mst_id
                )
                SELECT
                    total_prop_demand.total_demand, 
                    (total_prop_collection.total_collection+total_saf_collection.collection) as total_collection,  
                    (total_prop_demand.total_demand-total_prop_collection.total_collection+total_saf_collection.collection) AS total_due
                FROM total_prop_demand, total_prop_collection, total_saf_collection";

        //$this->redis_client->del("mini_dashboard_property_dbc");
        $property_detail = $this->redis_client->get("mini_dashboard_property_dbc");
        if (!$property_detail) {
            if ($property_detail = $this->db->query($sql)->getFirstRow("array")) {
                $this->redis_client->setEx("mini_dashboard_property_dbc", 300, json_encode($property_detail));
            }
        } else {
            $property_detail = json_decode($property_detail, true);
        }
        return $property_detail;
    }
	
	
	public function getPropertyPendingAtLevelCurrentFinancialYear()
    {
        $currentFy = getFY();
        list($yearStart,$yearEnd) = explode("-",$currentFy);
        $startDate =  $yearStart."-04-01";
        $endDate =  $yearEnd."-03-31";

        $sql = "WITH total_saf_count AS (
                    SELECT 
                        json_agg(
                            json_build_object('assessment_type', assessment_type, 'total_count', total_count)
                        ) AS current_year_total_saf
                    FROM (
                        SELECT
                            tbl_saf_dtl.assessment_type,
                            COUNT(DISTINCT tbl_saf_dtl.id) AS total_count
                        FROM tbl_saf_dtl
                        INNER JOIN tbl_prop_type_mstr
                            ON tbl_prop_type_mstr.id = tbl_saf_dtl.prop_type_mstr_id
                        JOIN view_saf_owner_detail
                            ON view_saf_owner_detail.saf_dtl_id = tbl_saf_dtl.id
                        JOIN view_ward_mstr
                            ON view_ward_mstr.id = tbl_saf_dtl.ward_mstr_id
                        LEFT JOIN view_emp_details
                            ON view_emp_details.id = tbl_saf_dtl.emp_details_id
                        WHERE tbl_saf_dtl.status = 1
                            AND Date(tbl_saf_dtl.apply_date) BETWEEN '$startDate' AND '$endDate'
                        GROUP BY tbl_saf_dtl.assessment_type
                    ) AS tbl
                ),
                total_dealing_assistant_pending AS (
                    SELECT 
                        COUNT(DISTINCT tbl_level_pending_dtl.saf_dtl_id) AS no_of_pending_by_dealing_assistant 
                    FROM tbl_saf_dtl
                    INNER JOIN tbl_level_pending_dtl 
                        ON tbl_level_pending_dtl.saf_dtl_id = tbl_saf_dtl.id
                    INNER JOIN (
                        SELECT saf_dtl_id, MAX(id) AS level_pending_dtl_id 
                        FROM tbl_level_pending_dtl 
                        WHERE status = 1 
                        GROUP BY saf_dtl_id
                    ) AS level_pending_dtl 
                        ON level_pending_dtl.level_pending_dtl_id = tbl_level_pending_dtl.id
                    WHERE 
                        tbl_saf_dtl.status = 1 
                        AND tbl_level_pending_dtl.receiver_user_type_id = 6 
                        AND tbl_level_pending_dtl.status = 1 
                        AND tbl_level_pending_dtl.verification_status = 0
                ), 
                total_ulb_tc_pending AS (
                    SELECT 
                        COUNT(DISTINCT tbl_level_pending_dtl.saf_dtl_id) AS no_of_pending_by_ulb_tc 
                    FROM tbl_saf_dtl
                    INNER JOIN tbl_level_pending_dtl 
                        ON tbl_level_pending_dtl.saf_dtl_id = tbl_saf_dtl.id
                    INNER JOIN (
                        SELECT geotag_dtl_id
                        FROM tbl_saf_geotag_upload_dtl
                        WHERE status = 1
                        GROUP BY geotag_dtl_id
                    ) AS geotag_dtl 
                        ON geotag_dtl.geotag_dtl_id = tbl_saf_dtl.id
                    WHERE 
                        tbl_saf_dtl.status = 1
                        AND tbl_level_pending_dtl.receiver_user_type_id = 7 
                        AND tbl_level_pending_dtl.status = 1 
                        AND tbl_level_pending_dtl.verification_status = 0
                ),
                total_section_incharge_pending AS (
                    SELECT 
                        COUNT(DISTINCT tbl_level_pending_dtl.saf_dtl_id) AS no_of_pending_by_section_incharge 
                    FROM tbl_saf_dtl
                    INNER JOIN tbl_level_pending_dtl 
                        ON tbl_level_pending_dtl.saf_dtl_id = tbl_saf_dtl.id
                    WHERE 
                        tbl_saf_dtl.status = 1 
                        AND tbl_level_pending_dtl.receiver_user_type_id = 9 
                        AND tbl_level_pending_dtl.status = 1 
                        AND tbl_level_pending_dtl.verification_status = 0
                ),
                total_executive_officer_pending AS (
                    SELECT 
                        COUNT(DISTINCT tbl_level_pending_dtl.saf_dtl_id) AS no_of_pending_by_executive_officer 
                    FROM tbl_saf_dtl
                    INNER JOIN tbl_level_pending_dtl 
                        ON tbl_level_pending_dtl.saf_dtl_id = tbl_saf_dtl.id
                    WHERE 
                        tbl_saf_dtl.status = 1 
                        AND tbl_level_pending_dtl.receiver_user_type_id = 10 
                        AND tbl_level_pending_dtl.status = 1 
                        AND tbl_level_pending_dtl.verification_status = 0
                )
                SELECT
                    current_year_total_saf,
                    no_of_pending_by_dealing_assistant,
                    no_of_pending_by_ulb_tc,
                    no_of_pending_by_section_incharge,
                    no_of_pending_by_executive_officer
                FROM 
                    total_saf_count, 
                    total_dealing_assistant_pending, 
                    total_ulb_tc_pending, 
                    total_section_incharge_pending, 
                    total_executive_officer_pending;
                ";

        // dd($sql);

        $property_pending_at_level_currentFinancialYear = $this->redis_client->get("property_pending_at_level_currentFinancialYear");
        if (!$property_pending_at_level_currentFinancialYear) {
            if ($property_pending_at_level_currentFinancialYear = $this->db->query($sql)->getFirstRow("array")) {
                $this->redis_client->setEx("property_pending_at_level_currentFinancialYear", 300, json_encode($property_pending_at_level_currentFinancialYear));
            }
        } else {
            $property_pending_at_level_currentFinancialYear = json_decode($property_pending_at_level_currentFinancialYear, true);
        }
        $property_pending_at_level_currentFinancialYear["current_year_total_saf"] = json_decode($property_pending_at_level_currentFinancialYear["current_year_total_saf"], true);
        return $property_pending_at_level_currentFinancialYear;
    }
	
	
	public function totalGbSaf()
    {
        $sql = "SELECT count(tbl_govt.id) as govt_saf_count
					FROM tbl_govt_saf_dtl tbl_govt
					JOIN view_ward_mstr ON tbl_govt.ward_mstr_id = view_ward_mstr.id
					WHERE tbl_govt.status=1 and  1=1";
        // return $sql;
        $result = $this->db->query($sql)->getRowArray();

        return $result ?? ['govt_saf_count' => 0];

    }
	
	
	 public function totalHolding()
    {
        $sql = "SELECT COUNT(*) as no_of_holding 
        FROM tbl_prop_dtl  where status = '1' AND CHAR_LENGTH(tbl_prop_dtl.new_holding_no) > 0;";
            $result = $this->db->query($sql)->getRowArray();
        // return $sql;
        return $result ?? ['no_of_holding' => 0];
    }
	
	
	public function gbSafDemandWithCollectionAndDues()
    {
        $currentFy = getFY();
        list($yearStart,$yearEnd) = explode("-",$currentFy);
        $startDate =  $yearStart."-04-01";
        $endDate =  $yearEnd."-03-31";

        $sql = "
            SELECT SUM(balance) AS total_demand ,
                SUM(payable_amt) AS total_collection,
                SUM(balance) - SUM(coalesce(payable_amt,0)) AS total_dues
            FROM(	
                SELECT SUM(balance) AS balance , govt_saf_dtl_id
                FROM tbl_govt_saf_demand_dtl
                    WHERE status = 1 AND paid_status = 0 and fyear<='$currentFy'
                GROUP BY govt_saf_dtl_id
            ) AS tbl_govt_saf_demand_dtl
            LEFT JOIN (
                SELECT SUM(payable_amt) AS payable_amt,govt_saf_dtl_id
                FROM tbl_govt_saf_transaction
                WHERE tbl_govt_saf_transaction.status IN (1, 2) and tran_date between '$startDate' and '$endDate'
                GROUP BY govt_saf_dtl_id
            ) AS tbl_govt_saf_transaction ON tbl_govt_saf_demand_dtl.govt_saf_dtl_id = tbl_govt_saf_transaction.govt_saf_dtl_id";
        $result = $this->db->query($sql)->getRowArray();

        return $result;
    }
	
	public function totalGeoTag()
    {

        $currentFy = getFY();
        list($yearStart,$yearEnd) = explode("-",$currentFy);
        $startDate =  $yearStart."-04-01";
        $endDate =  $yearEnd."-03-31";

        $sql = "SELECT Count(DISTINCT tbl_saf_dtl.id)
                    FROM   tbl_saf_dtl
                        inner join tbl_saf_geotag_upload_dtl
                                ON tbl_saf_geotag_upload_dtl.geotag_dtl_id = tbl_saf_dtl.id
                    WHERE  tbl_saf_geotag_upload_dtl.created_on :: DATE BETWEEN
                        '$startDate' AND '$endDate'";
        $result = $this->db->query($sql)->getRowArray();

        return $result;
    }
	
	
	public function noticeData()
    {
        $currentFy = getFY();        
        list($yearStart,$yearEnd) = explode("-",$currentFy);
        $startDate =  $yearStart."-04-01";
        $endDate =  $yearEnd."-03-31";

        $sql = "WITH demands_
                                AS (SELECT prop_dtl_id,
                                            SUM(balance - ( balance * 10.9 / 100 )) AS balance
                                    FROM   tbl_prop_demand
                                            join(SELECT DISTINCT( prop_dtl_id ) AS temp_prop_id
                                                FROM   tbl_prop_demand
                                                WHERE  status = 1
                                                        AND paid_status = 0
                                                        AND fyear < '$currentFy'
                                                        AND balance > 0)arrear
                                            ON arrear.temp_prop_id = tbl_prop_demand.prop_dtl_id
                                    WHERE  status = 1
                                            AND paid_status = 0
                                            AND balance > 0
                                            AND fyear < '$currentFy'
                                    GROUP  BY prop_dtl_id),
                                demands
                                AS (SELECT prop_dtl_id,
                                            SUM(balance) AS balance
                                    FROM   ((SELECT DISTINCT( prop_dtl_id )                         AS
                                                            prop_dtl_id,
                                                            SUM(balance - ( balance * 10.9 / 100 )) AS
                                                            balance
                                            FROM   tbl_prop_demand
                                            WHERE  status = 1
                                                    AND paid_status = 0
                                                    AND fyear < '$currentFy'
                                                    AND balance > 0
                                            GROUP  BY prop_dtl_id)
                                            UNION
                                            (SELECT DISTINCT prop_dtl_id                           AS
                                                            prop_dtl_id,
                                                            SUM(amount - ( amount * 10.9 / 100 )) AS
                                                            balance
                                            FROM   tbl_collection
                                            WHERE  fyear < '$currentFy'
                                                    AND status = 1
                                                    AND created_on :: DATE BETWEEN
                                                        '$startDate' AND '$endDate'
                                            GROUP  BY prop_dtl_id))arrear
                                    WHERE  balance > 0
                                    GROUP  BY prop_dtl_id),
                                remaining_defaulter
                                AS (SELECT DISTINCT( tbl_prop_demand.prop_dtl_id )
                                                    AS
                                                    prop_dtl_id,
                                                    SUM(tbl_prop_demand.balance - (
                                                        tbl_prop_demand.balance * 10.9 / 100 )) AS
                                                    balance
                                    FROM   tbl_prop_demand
                                            left join demands
                                                ON demands.prop_dtl_id = tbl_prop_demand.prop_dtl_id
                                    WHERE  status = 1
                                            AND paid_status = 0
                                            AND fyear < '$currentFy'
                                            AND tbl_prop_demand.balance > 0
                                    GROUP  BY tbl_prop_demand.prop_dtl_id),
                                notices
                                AS (SELECT tbl_prop_notices.*
                                    FROM   tbl_prop_notices
                                            join(SELECT Max(id)     AS max_id,
                                                        prop_dtl_id AS max_prop_dtl_id
                                                FROM   tbl_prop_notices
                                                WHERE  status != 0
                                                        AND fnyear = '$currentFy'
                                                        AND notice_type = 'Demand'
                                                GROUP  BY prop_dtl_id) AS last_notice
                                            ON last_notice.max_id = tbl_prop_notices.id
                                            left join demands
                                                ON demands.prop_dtl_id = tbl_prop_notices.prop_dtl_id
                                    WHERE  1 = 1),
                                defaulter_pay_without_notice
                                AS (SELECT collection_from.prop_dtl_id,
                                            collection_from.balance
                                    FROM   (SELECT DISTINCT prop_dtl_id                           AS
                                                            prop_dtl_id
                                                            ,
                                                            SUM(amount -
                                                            ( amount * 10.9 / 100 )) AS balance
                                            FROM   tbl_collection
                                            WHERE  fyear < '$currentFy'
                                                    AND status = 1
                                                    AND created_on :: DATE BETWEEN
                                                        '$startDate' AND '$endDate'
                                            GROUP  BY prop_dtl_id)collection_from
                                            join demands
                                            ON demands.prop_dtl_id = collection_from.prop_dtl_id
                                            left join notices
                                                ON notices.prop_dtl_id = collection_from.prop_dtl_id
                                            left join remaining_defaulter
                                                ON remaining_defaulter.prop_dtl_id =
                                                    collection_from.prop_dtl_id
                                    WHERE  notices.id IS NULL
                                            AND remaining_defaulter.prop_dtl_id IS NULL),
                                records
                                AS (SELECT Count(tbl_prop_dtl.id)                 AS total,
                                            SUM(Coalesce(tbl_prop_dtl.balance, 0)) AS balance,
                                            Count(CASE
                                                    WHEN tbl_prop_dtl.remaing_prop_dtl_id IS NOT NULL THEN
                                                    tbl_prop_dtl.id
                                                END)                             AS
                                            total_remaining_defaulter,
                                            Count(CASE
                                                    WHEN tbl_prop_dtl.withot_prop_dtl_id IS NOT NULL THEN
                                                    tbl_prop_dtl.id
                                                END)                             AS
                                            total_defaulter_pay_without_notice,
                                            Count(CASE
                                                    WHEN notices.id IS NOT NULL THEN tbl_prop_dtl.id
                                                END)                             AS total_notice_generated
                                            ,
                                            Count(CASE
                                                    WHEN notices.notice_served_by IS NOT NULL THEN
                                                    tbl_prop_dtl.id
                                                END)                             AS total_notice_served,
                                            Count(CASE
                                                    WHEN notices.id IS NOT NULL
                                                        AND notices.clear_by_id IS NOT NULL
                                                        AND tbl_prop_dtl.remaing_prop_dtl_id IS NULL THEN
                                                    tbl_prop_dtl.id
                                                END)                             AS
                                            total_payment_received_from_notice,
                                            Count(CASE
                                                    WHEN notices.id IS NOT NULL
                                                        AND notices.clear_by_id IS NOT NULL
                                                        AND tbl_prop_dtl.remaing_prop_dtl_id IS NOT NULL
                                                THEN
                                                    tbl_prop_dtl.id
                                                END)                             AS
                                            top_prop_receive_payment_and_regen_demand
                                    FROM   view_ward_mstr
                                            left join (SELECT tbl_prop_dtl.ward_mstr_id,
                                                            tbl_prop_dtl.id,
                                                            demands.balance,
                                                            CASE
                                                                WHEN remaining_defaulter.prop_dtl_id IS NOT
                                                                    NULL
                                                            THEN
                                                                tbl_prop_dtl.id
                                                                ELSE NULL
                                                            END AS remaing_prop_dtl_id,
                                                            CASE
                                                                WHEN
                            defaulter_pay_without_notice.prop_dtl_id IS
                            NOT
                            NULL
                                            THEN
                                                                tbl_prop_dtl.id
                                                                ELSE NULL
                                                            END AS withot_prop_dtl_id
                                                    FROM   tbl_prop_dtl
                                                            join tbl_prop_type_mstr
                                                                ON tbl_prop_type_mstr.id =
                                                                tbl_prop_dtl.prop_type_mstr_id
                                                            join demands
                                                                ON demands.prop_dtl_id = tbl_prop_dtl.id
                                                            left join remaining_defaulter
                                                                    ON remaining_defaulter.prop_dtl_id =
                                                                        tbl_prop_dtl.id
                                                            left join defaulter_pay_without_notice
                                                                    ON
                            defaulter_pay_without_notice.prop_dtl_id
                            =
                            tbl_prop_dtl.id
                                                    WHERE  tbl_prop_dtl.status = 1
                                                            AND demands.balance > 0
                                                            AND tbl_prop_dtl.status = 1
                                                            AND
                                                    Char_length(tbl_prop_dtl.new_holding_no) > 0
                                                            AND ( tbl_prop_dtl.govt_saf_dtl_id IS NULL
                                                                    OR tbl_prop_dtl.govt_saf_dtl_id = 0 ))
                                                    tbl_prop_dtl
                                                ON view_ward_mstr.id = tbl_prop_dtl.ward_mstr_id
                                            left join notices
                                                ON notices.prop_dtl_id = tbl_prop_dtl.id
                                    WHERE  1 = 1)
                            SELECT records.*
                            FROM   records ";

        $result = $this->db->query($sql)->getRowArray();
        return $result;
    }
	
	 public function stateAndCentralGbsafCount()
    {
        $sql = "SELECT count(id) FROM tbl_govt_saf_dtl tbl_govt where status = 1";
        $result = $this->db->query($sql)->getRowArray();

        return $result;
    }
	

}