<?php

namespace App\Controllers;

use Predis\Client;
use App\Models\TradeApplicationTypeMstrModel;
use App\Controllers\Reports\MiniDashboardReport;
use App\Models\model_datatable;
use App\Models\model_fy_mstr;

class MiniDashboard extends AlphaController
{
	protected $dbtrade;
	protected $redis_client;
	protected $MiniDashboardReport;
	protected $tradeapplicationtypemstrmodel;
	protected $model_datatable;
	protected $model_fy_mstr;

	public function __construct()
	{
		// ini_set('display_errors', '1');
		// ini_set('display_startup_errors', '1');
		// error_reporting(E_ALL);

		parent::__construct();
		helper(['db_helper', 'form_helper', 'utility_helper']);
		if ($db_name = dbConfig("trade")) {
			$this->dbtrade = db_connect($db_name);
		}
		if($db_name = dbConfig("property")){
			$this->db = db_connect($db_name); 
		}
		$this->dbSystem = db_connect('db_system');

		$this->model_fy_mstr = new model_fy_mstr($this->dbSystem);

		$this->redis_client = new Client();
		$this->MiniDashboardReport = new MiniDashboardReport();
		$this->tradeapplicationtypemstrmodel =  new tradeapplicationtypemstrmodel($this->dbtrade);
		$this->model_datatable = new model_datatable($this->db);
	}

	function __destruct()
	{
		if ($this->dbtrade) $this->dbtrade->close();
	}

	public function index()
	{
		/* start propperty */

		$data["property_dcb"] = $this->MiniDashboardReport->getPropertyDCB();
		$data["property_pending_at_level_before_2022"] = $this->MiniDashboardReport->getPropertyPendingAtLevelBefore2022();
		
		//die();
		$data["property_pending_at_level_2022"] = $this->MiniDashboardReport->getPropertyPendingAtLevel2022();
		//print_var($data);
		/* end propperty */
		/* start Trade */

		$fyear = explode("-",getFY())[0];
		$privFyear = ($fyear-1)."-".$fyear;
		$data["privFyear"]=$privFyear;

		//$this->redis_client->del("mini_dashboard_trade_pending_at");
		$mini_dashboard_trade_pending_at = $this->redis_client->get("mini_dashboard_trade_pending_at");
		if (!$mini_dashboard_trade_pending_at) {
			if ($mini_dashboard_trade_pending_at = $this->tradeapplicationtypemstrmodel->getTradeLevelPendingReportFYear($privFyear)) {
				$this->redis_client->setEx("mini_dashboard_trade_pending_at", 3600, json_encode($mini_dashboard_trade_pending_at));
			}
		} else {
			$mini_dashboard_trade_pending_at = json_decode($mini_dashboard_trade_pending_at, true);
		}
		$data["trade_pending_at_level_21_22"] = $mini_dashboard_trade_pending_at;
		//$data["pending_report"] = $this->tradeapplicationtypemstrmodel->getTradeLevelPendingReport();
		//$this->redis_client->del("mini_dashboard_trade_total_application");
		$mini_dashboard_trade_total_application =  $this->redis_client->get("mini_dashboard_trade_total_application");
		if (!$mini_dashboard_trade_total_application) {
			if ($mini_dashboard_trade_total_application = $this->tradeapplicationtypemstrmodel->getTradeApplyCountOnFyear($privFyear)) {
				$this->redis_client->setEx("mini_dashboard_trade_total_application", 3600, json_encode($mini_dashboard_trade_total_application));
			}
		} else {
			$mini_dashboard_trade_total_application = json_decode($mini_dashboard_trade_total_application, true);
		}

		//2022-23 trade pending data
		//$this->redis_client->del("mini_dashboard_trade_pending_at_22_23");
		$mini_dashboard_trade_pending_at_22_23 =  $this->redis_client->get("mini_dashboard_trade_pending_at_22_23");
		if (!$mini_dashboard_trade_pending_at_22_23) {
			if ($mini_dashboard_trade_pending_at_22_23 = $this->tradeapplicationtypemstrmodel->getTradeLevelPendingReportFYear()) {
				$this->redis_client->setEx("mini_dashboard_trade_pending_at_22_23", 3600, json_encode($mini_dashboard_trade_pending_at_22_23));
			}
		} else {
			$mini_dashboard_trade_pending_at_22_23 = json_decode($mini_dashboard_trade_pending_at_22_23, true);
		}

		//$this->redis_client->del("mini_dashboard_trade_total_application_22_23");
		$mini_dashboard_trade_total_application_22_23 =  $this->redis_client->get("mini_dashboard_trade_total_application_22_23");
		if (!$mini_dashboard_trade_total_application_22_23) {
			if ($mini_dashboard_trade_total_application_22_23 = $this->tradeapplicationtypemstrmodel->getTradeApplyCountOnFyear()) {
				$this->redis_client->setEx("mini_dashboard_trade_total_application_22_23", 3600, json_encode($mini_dashboard_trade_total_application_22_23));
			}
		} else {
			$mini_dashboard_trade_total_application_22_23 = json_decode($mini_dashboard_trade_total_application_22_23, true);
		}





		$data["total_application_21_22"] = $mini_dashboard_trade_total_application;
		$data["trade_pending_at_level_22_23"] = $mini_dashboard_trade_pending_at_22_23;
		$data["total_application_22_23"] = $mini_dashboard_trade_total_application_22_23;
		/* end Trade */
		return view('property/dashboard/mini_index', $data);
	}
	
	
	function wardWiseSaf($fyear, $assessment_type)
	{
		$fyear_arra = explode("-", $fyear);
		helper(['form']);
		if($fyear_arra[1] >= '2023'){
			$fromdate = "2022"."-04-01";
			$toate = $fyear_arra[1]."-03-31";
		}else{
			$fromdate = "2010-04-01";
			$toate = $fyear_arra[1]."-03-31";
		}
		$assessment = "";
		if($assessment_type != "All")
			$assessment = " AND assessment_type='".$assessment_type."'";
			
		$sql = "SELECT COUNT(DISTINCT tbl_saf_dtl.id) AS saf_count,tbl_saf_dtl.ward_mstr_id,view_ward_mstr.ward_no
				FROM tbl_saf_dtl  
				JOIN tbl_transaction t ON t.prop_dtl_id = tbl_saf_dtl.id AND t.tran_type='Saf' AND (tbl_saf_dtl.apply_date between '".$fromdate."' AND '".$toate."') AND t.status IN (1,2)
				JOIN view_ward_mstr ON view_ward_mstr.id=tbl_saf_dtl.ward_mstr_id 
				WHERE tbl_saf_dtl.status=1 ".$assessment."
				GROUP BY tbl_saf_dtl.ward_mstr_id, view_ward_mstr.ward_no";
		
		if($result = $this->db->query($sql)->getResultArray()) {
			//print_var($result);
			$data["result"] = $result;
			$data["fyear"] = $fyear;
			$data["assessement"] = $assessment_type;
			$data["propcess"] = 'drildown';
		}

		return view('property/dashboard/ward_wise_saf', $data);
	}

	function drildown($fyear, $assessment_type, $wardId=null)
	{
		$fyear = explode("-", $fyear);
		helper(['form']);
		if($fyear[1] >= '2023'){
			$fromdate = "2022"."-04-01";
			$toate = $fyear[1]."-03-31";
		}else{
			$fromdate = "2010-04-01";
			$toate = $fyear[1]."-03-31";
		}
		$assessment = "";
		if($assessment_type != "All")
			$assessment = " AND assessment_type='".$assessment_type."'";
		$wardFilter = "";
		if($wardId)
			$wardFilter = "and md5(s.ward_mstr_id::text)='".$wardId."'";

		$sql = "SELECT DISTINCT s.id,ward_no,saf_no,owner_name,owner_mobile,s.prop_address,apply_date FROM tbl_saf_dtl s 
				JOIN tbl_transaction t ON t.prop_dtl_id = s.id AND t.tran_type='Saf' AND (s.apply_date between '".$fromdate."' AND '".$toate."') AND t.status IN (1,2)
				LEFT JOIN (
					SELECT saf_dtl_id,array_to_string(array_agg(owner_name), ',') as owner_name ,array_to_string(array_agg(mobile_no), ', ') as owner_mobile FROM tbl_saf_owner_detail where status=1 group by saf_dtl_id
					) w on w.saf_dtl_id = s.id
				JOIN view_ward_mstr ward ON s.ward_mstr_id=ward.id 
				WHERE s.status=1 ". $assessment . $wardFilter;
		//exit();
		$result = $this->model_datatable->getDatatable($sql);
		$result['data'] = $result['result'];
		$result['pager'] = $result['count'];
		
		return view('property/dashboard/drildown', $result);
	}

	function level_wardwise($fyear, $dataid)
	{
		$fyear_arra = explode("-", $fyear);
        helper(['form']);
        $session = session();
		if($fyear_arra[1] >= '2023'){
			$fromdate = "2022"."-04-01";
			$toate = $fyear_arra[1]."-03-31";
		}else{
			$fromdate = "2010-04-01";
			$toate = $fyear_arra[1]."-03-31";
		}

		$forTaxCollector = "";
		if(md5(7) == $dataid)
		{
			$forTaxCollector = "INNER JOIN (
				SELECT
					geotag_dtl_id
				FROM tbl_saf_geotag_upload_dtl
				WHERE status=1
				GROUP BY geotag_dtl_id
			) AS geotag_dtl ON geotag_dtl.geotag_dtl_id=s.id";
		}

        $sql="SELECT
				COUNT(DISTINCT s.id) AS saf_count,s.ward_mstr_id,view_ward_mstr.ward_no
			FROM tbl_saf_dtl s
			INNER JOIN tbl_level_pending_dtl ON tbl_level_pending_dtl.saf_dtl_id=s.id
			INNER JOIN (SELECT saf_dtl_id, MAX(id) AS level_pending_dtl_id FROM tbl_level_pending_dtl WHERE status=1 GROUP BY saf_dtl_id) AS level_pending_dtl ON level_pending_dtl.level_pending_dtl_id=tbl_level_pending_dtl.id
			INNER JOIN view_ward_mstr ON view_ward_mstr.id=s.ward_mstr_id ".$forTaxCollector."
			where tbl_level_pending_dtl.status=1 and s.status=1 and tbl_level_pending_dtl.verification_status=0 and md5(tbl_level_pending_dtl.receiver_user_type_id::text)='".$dataid."' AND (s.apply_date between '".$fromdate."' AND '".$toate."')
			GROUP BY s.ward_mstr_id,view_ward_mstr.ward_no";
				
		$result = $this->db->query($sql)->getResultArray();
		$data["result"] = $result;
		$data["fyear"] = $fyear;
		$data["assessement"] = $dataid;
		$data["propcess"] = 'level_drildown';
		return view('property/dashboard/ward_wise_saf', $data);
	}

	function level_drildown($fyear, $dataid , $wardId=null)
	{
		$fyear = explode("-", $fyear);
        helper(['form']);
        $session = session();
		if($fyear[1] >= '2023'){
			$fromdate = '2022'."-04-01";
			$toate = $fyear[1]."-03-31";
		}else{
			$fromdate = "2010-04-01";
			$toate = $fyear[1]."-03-31";
		}

		$forTaxCollector = "";
		if(md5(7) == $dataid)
		{
			$forTaxCollector = "INNER JOIN (
				SELECT
					geotag_dtl_id
				FROM tbl_saf_geotag_upload_dtl
				WHERE status=1
				GROUP BY geotag_dtl_id
			) AS geotag_dtl ON geotag_dtl.geotag_dtl_id=s.id";
		}

		$wardFilter = "";
		if($wardId)
			$wardFilter = "and md5(s.ward_mstr_id::text)='".$wardId."'";

        $sql="SELECT
				DISTINCT s.id,ward_no,saf_no,owner_name,owner_mobile,s.prop_address,apply_date
			FROM tbl_saf_dtl s
			LEFT JOIN tbl_level_pending_dtl ON tbl_level_pending_dtl.saf_dtl_id=s.id
			INNER JOIN (SELECT saf_dtl_id, MAX(id) AS level_pending_dtl_id FROM tbl_level_pending_dtl WHERE status=1 GROUP BY saf_dtl_id) AS level_pending_dtl ON level_pending_dtl.level_pending_dtl_id=tbl_level_pending_dtl.id
			INNER JOIN view_ward_mstr ON view_ward_mstr.id=s.ward_mstr_id ".$forTaxCollector."
			INNER JOIN (SELECT saf_dtl_id, STRING_AGG(owner_name, ',') AS owner_name, STRING_AGG(mobile_no::text, ',') AS owner_mobile FROM tbl_saf_owner_detail GROUP BY saf_dtl_id) AS saf_owner_detail ON saf_owner_detail.saf_dtl_id=s.id
			where tbl_level_pending_dtl.status=1 and s.status=1 and tbl_level_pending_dtl.verification_status=0 and md5(tbl_level_pending_dtl.receiver_user_type_id::text)='".$dataid."' AND (s.apply_date between '".$fromdate."' AND '".$toate."') ". $wardFilter;
				
		$result = $this->model_datatable->getDatatable($sql);
		$result['data'] = $result['result'];
		$result['pager'] = $result['count'];
		
		return view('property/dashboard/drildown',$result);
	}


	// NEW DASHBOARD 09-04-25 (MITHLESH)

	public function newDashboard()
	{
		
		return view('property/dashboard/new_dashboard');
	}

	 public function propertyDashboard()
	{
		$currentFY = getFY();
		$fy_mst_id = $this->model_fy_mstr->getFyByFy(['fy' => $currentFY])["id"];
		$_REQUEST["api"] = true;
		$_REQUEST["fy_mstr_id"] = $fy_mst_id;
		$_REQUEST["ward_mstr_id"] = "";

		$PROP_DCB = $this->redis_client->get('propReport');
		$GBSAF_DCB = $this->redis_client->get('gb_saf_dcb');
		$propReport = (new \App\Controllers\prop_report());
		$propReport->request = $this->request;


		if ($PROP_DCB) {
			$data['propReport'] = json_decode($PROP_DCB, true);
		} else {
			$propDCB = $propReport->wardWiseDCB();
			// print_var($propDCB);
			$data['propReport'] = [
				"current_demand" => array_sum(array_column($propDCB["report_list"], 'current_demand') ?? []),
				"arrear_demand" => array_sum(array_column($propDCB["report_list"], 'arrear_demand') ?? []),
				"arrear_collection" => array_sum(array_merge(array_column($propDCB["report_list"], 'arrear_collection_amount2') ?? [], array_column($propDCB["report_list"], 'arrear_collection_amount') ?? [])),
				"current_collection" => array_sum(array_merge(array_column($propDCB["report_list"], 'actual_collection_amount') ?? [], array_column($propDCB["report_list"], 'actual_collection_amount2') ?? []))
			];

			$data['propReport']["total_demand"] = ($data['propReport']["current_demand"] + $data['propReport']["arrear_demand"] ?? []);
			$this->redis_client->setEx("propReport", 300, json_encode($data['propReport']));
		}

		$GBSAF_DCB = $this->redis_client->get('gb_saf_dcb');
		if ($GBSAF_DCB) {
			$data['gbSafDCB'] = json_decode($GBSAF_DCB, true);
		} else {

			$gbSafDCB = $propReport->govtDCB();
			$gbSafDCB = $gbSafDCB["result"] ?? [];

			$data['gbSafDCB'] = [
				'current_demand' => $gbSafDCB['current_demand'] ?? 0,
				'arrear_demand' => $gbSafDCB['arrear_demand'] ?? 0,
				'total_demand' => ($gbSafDCB['current_demand'] ?? 0) + ($gbSafDCB['arrear_demand'] ?? 0),
				'arear_collection' => $gbSafDCB['arear_collection'] ?? 0,
				'current_collection' => $gbSafDCB['current_collection'] ?? 0,
				'current_balance' => $gbSafDCB['curbalance'] ?? 0,
				'arrear_balance' => $gbSafDCB['curbalance'] ?? 0
			];
			// Store in Redis for 5 minutes
			$this->redis_client->setEx("gb_saf_dcb", 300, json_encode($data['gbSafDCB']));

		}

		$data["property_pending_at_level_current_year"] = $this->MiniDashboardReport->getPropertyPendingAtLevelCurrentFinancialYear();
		$data["gbsaf"] = $this->MiniDashboardReport->totalGbSaf();
		$data['total_holding'] = $this->MiniDashboardReport->totalHolding();
		$data['geotag'] = $this->MiniDashboardReport->totalGeoTag();
		$data['notice'] = $this->MiniDashboardReport->noticeData();
		$data['stateandgbsaf'] = $this->MiniDashboardReport->stateAndCentralGbsafCount();
		$fyear = explode("-", getFY())[0];
		$privFyear = ($fyear - 1) . "-" . $fyear;
		$data["privFyear"] = $privFyear;
		return view('property/dashboard/new_property_dashboard', $data);
	}


	public function holdingList(){
		$result = [];
		$sql = "SELECT COUNT(*) 
				FROM tbl_prop_dtl  where status = '1' AND CHAR_LENGTH(tbl_prop_dtl.new_holding_no) > 0;
				WITH owner_details AS (
					SELECT
						prop_dtl_id,
						STRING_AGG(owner_name, ', ') AS owner_name,
						STRING_AGG(mobile_no::TEXT, ', ') AS mobile_no
					FROM tbl_prop_owner_detail
					GROUP BY prop_dtl_id
				)
				SELECT  
					distinct tbl_prop_dtl.id as id,
					view_ward_mstr.ward_no,
					new_ward.ward_no AS new_ward_no,
					tbl_prop_dtl.new_holding_no,
					tbl_prop_dtl.prop_address,
					owner_dtl.owner_name,
					owner_dtl.mobile_no
				FROM tbl_prop_dtl  
				INNER JOIN view_ward_mstr ON view_ward_mstr.id = tbl_prop_dtl.ward_mstr_id
				LEFT JOIN view_ward_mstr AS new_ward ON new_ward.id = tbl_prop_dtl.new_ward_mstr_id
				INNER JOIN owner_details AS owner_dtl ON owner_dtl.prop_dtl_id = tbl_prop_dtl.id
				where tbl_prop_dtl.status = '1' AND CHAR_LENGTH(tbl_prop_dtl.new_holding_no) > 0
				order by tbl_prop_dtl.id asc";
				// $result = $this->db->query($sql)->getResultArray();
				$result = $this->model_datatable->getDatatable($sql);
				$result['data'] = $result['result'];
				$result['pager'] = $result['count'];

				// dd($result);
				
				return view('property/reports/holding_list', $result);
	}

	function wardWiseSafCurrentFy($assessment_type)
	{
		$currentFy = getFY();
		list($yearStart, $yearEnd) = explode("-", $currentFy);
		$fromdate = $yearStart . "-04-01";
		$endDate = $yearEnd . "-03-31";
 
		$assessment = "";
 
		if ($assessment_type != "All")
			$assessment = " AND assessment_type='" . $assessment_type . "'";
		$sql = "SELECT COUNT(DISTINCT tbl_saf_dtl.id) AS saf_count,
						tbl_saf_dtl.ward_mstr_id,
						view_ward_mstr.ward_no
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
						AND Date(tbl_saf_dtl.apply_date) BETWEEN '$fromdate' AND '$endDate' 
						$assessment
					GROUP BY tbl_saf_dtl.ward_mstr_id, view_ward_mstr.ward_no";
 
		if ($result = $this->db->query($sql)->getResultArray()) {
			$data["result"] = $result;
			$data["assessement"] = $assessment_type;
			$data["propcess"] = 'drildownForCurrentFy';
		}
 
		return view('property/dashboard/ward_wise_saf', $data);
	}
	
	function drildownForCurrentFy($assessment_type, $wardId = null)
	{
		$currentFy = getFY();
 
		list($yearStart, $yearEnd) = explode("-", $currentFy);
		$fromdate = $yearStart . "-04-01";
		$uptoDate = $yearEnd . "-03-31";
 
		$assessment = "";
		if ($assessment_type != "All")
			$assessment = " AND assessment_type='" . $assessment_type . "'";
		$wardFilter = "";
		if ($wardId)
			$wardFilter = "and md5(s.ward_mstr_id::text)='" . $wardId . "'";
		$sql = "SELECT DISTINCT s.id,ward_no,saf_no,owner_name,owner_mobile,s.prop_address,apply_date
				FROM   tbl_saf_dtl s
					JOIN view_ward_mstr
						ON view_ward_mstr.id = s.ward_mstr_id
				LEFT JOIN (SELECT saf_dtl_id,
									Array_to_string(Array_agg(owner_name), ',') AS
									owner_name,
									Array_to_string(Array_agg(mobile_no), ', ') AS
									owner_mobile
							FROM   tbl_saf_owner_detail
							WHERE  status = 1
							GROUP  BY saf_dtl_id) w
						ON w.saf_dtl_id = s.id
				WHERE  s.status = 1
					AND Date(s.apply_date) BETWEEN '$fromdate' AND '$uptoDate'
					$assessment
					$wardFilter";
 
		$result = $this->model_datatable->getDatatable($sql);
		$result['data'] = $result['result'];
		$result['pager'] = $result['count'];
		return view('property/dashboard/drildown', $result);
	}

	function level_wardwise_for_currentfy($dataid)
	{
		$currentFy = getFY();
        list($yearStart,$yearEnd) = explode("-",$currentFy);
        $fromdate =  $yearStart."-04-01";
        $uptoDate =  $yearEnd."-03-31";

		$session = session();
		

		$forTaxCollector = "";
		if (md5(7) == $dataid) {
			$forTaxCollector = "INNER JOIN (
				SELECT
					geotag_dtl_id
				FROM tbl_saf_geotag_upload_dtl
				WHERE status=1
				GROUP BY geotag_dtl_id
			) AS geotag_dtl ON geotag_dtl.geotag_dtl_id=s.id";
		}

		$sql = "SELECT
				COUNT(DISTINCT s.id) AS saf_count,s.ward_mstr_id,view_ward_mstr.ward_no
			FROM tbl_saf_dtl s
			INNER JOIN tbl_level_pending_dtl ON tbl_level_pending_dtl.saf_dtl_id=s.id
			INNER JOIN (SELECT saf_dtl_id, MAX(id) AS level_pending_dtl_id FROM tbl_level_pending_dtl WHERE status=1 GROUP BY saf_dtl_id) AS level_pending_dtl ON level_pending_dtl.level_pending_dtl_id=tbl_level_pending_dtl.id
			INNER JOIN view_ward_mstr ON view_ward_mstr.id=s.ward_mstr_id " . $forTaxCollector . "
			where tbl_level_pending_dtl.status=1 and s.status=1 and tbl_level_pending_dtl.verification_status=0 and md5(tbl_level_pending_dtl.receiver_user_type_id::text)='" . $dataid . "' AND (s.apply_date between '" . $fromdate . "' AND '" . $uptoDate . "')
			GROUP BY s.ward_mstr_id,view_ward_mstr.ward_no";
			// return $sql;
		$result = $this->db->query($sql)->getResultArray();
		$data["result"] = $result;
		$data["fyear"] = $fyear;
		$data["assessement"] = $dataid;
		$data["propcess"] = 'level_drildown';
		return view('property/dashboard/ward_wise_saf', $data);
	}

	
}
