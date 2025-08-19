<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Controllers\Reports\PropReports;
use App\Models\model_ward_mstr;
use App\Models\model_ward_permission;
use App\Models\model_view_emp_details;
use App\Models\model_fy_mstr;
use App\Models\model_tran_mode_mstr;
use App\Models\model_datatable;
use App\Models\model_prop_type_mstr;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Exception;

//include APPPATH . './Libraries/phpoffice/autoload.php';

class prop_report extends AlphaController
{
	protected $db;
	protected $dbSystem;
    protected $PropReports;
    protected $model_ward_mstr;
	protected $model_ward_permission;
    protected $model_view_emp_details;
    protected $model_fy_mstr;
    protected $model_tran_mode_mstr;
    protected $model_datatable;
	protected $model_prop_type_mstr;

    public function __construct()
    {
        // ini_set('memory_limit', '-1');
        // error_reporting(-1);
        parent::__construct();
    	helper(['url', 'db_helper', 'validation_helper', 'validate_saf_helper', 'sms_helper', 'php_office_helper', 'utility_helper']);
        if($db_name = dbConfig("property")){  $this->db = db_connect($db_name); }
        if ($db_name = dbSystem()) {
             $this->dbSystem = db_connect($db_name); 
            
            }

            if ($db_name = dbConfig("water")) {
                $this->dbWater = db_connect($db_name);
            }
    
            if ($db_name = dbConfig("trade")) {
                $this->dbTrade = db_connect($db_name);
            }

        /* $this->db = db_connect('db_rmc_property');
        $this->dbSystem = db_connect('db_system');  */
        $this->PropReports = new PropReports();
        $this->model_ward_mstr = new model_ward_mstr($this->dbSystem);
		$this->model_ward_permission = new model_ward_permission($this->dbSystem);
        $this->model_view_emp_details = new model_view_emp_details($this->dbSystem);
        $this->model_fy_mstr = new model_fy_mstr($this->dbSystem);
        $this->model_tran_mode_mstr = new model_tran_mode_mstr($this->db);
        $this->model_datatable = new model_datatable($this->db);
		$this->model_prop_type_mstr = new model_prop_type_mstr($this->db);
        helper('form_helper');
    }

    function __destruct() {
		$this->db->close();
		$this->dbSystem->close();
	}


    public function noOfSafApply() {        
        $sql = "SELECT
                    tbl_saf_dtl.assessment_type,
                    COUNT(*) AS no_of_saf
                FROM tbl_saf_dtl
                INNER JOIN tbl_transaction ON tbl_transaction.prop_dtl_id=tbl_saf_dtl.id
                WHERE 
                    tbl_transaction.tran_type='Saf'
                    AND tbl_saf_dtl.assessment_type IN ('New Assessment', 'Reassessment', 'Mutation')
                GROUP BY tbl_saf_dtl.assessment_type";
        $data["apply_saf_dtl"] = $this->db->query($sql)->getResultArray();
        return view('report/no_of_saf_apply', $data);
    }
	
	public function getEmpListByWardPermissionAndUlb() {
        if($this->request->getMethod()=='post'){
			try{
                $ward_mstr_id = sanitizeString($this->request->getVar('ward_mstr_id'));
                $ulb_mstr_id = 1;
                if ($report_list = $this->model_ward_permission->getEmpListByWardPermissionAndUlb($ward_mstr_id, $ulb_mstr_id)) {
                    $option = "<option value=''>ALL</option>";
                    foreach ($report_list AS $list) {
                        $style = ($list['status']==1)?"":"style='color:red'";
                        $option .= '<option value="'.$list['id'].'" '.$style.'>'.$list['emp_name'].'</option>';
                    }
                    echo json_encode(['response'=>true, 'data'=>$option]);
                } else {
                    echo json_encode(['response'=>false]);
                }
            }catch(Exception $e){}
        }
    }
	
    public function SAFcollectionReport() {
        $ulb_mstr_id = 1;
        
        $wardList = $this->model_ward_mstr->getWardListWithSession(['ulb_mstr_id'=>$ulb_mstr_id]);
        $empDtlList = $this->model_view_emp_details->getEmpListByAlTaxCollector(['ulb_mstr_id'=>$ulb_mstr_id]);
        $data['wardList'] = $wardList;
        $data['empDtlList'] = $empDtlList;
        return view('property/reports/saf_collection_report', $data);
    }

    public function SAFcollectionReportAjax()
    {
        if($this->request->getMethod()=='post')
        {
			try
            {

                ## Read value
                $start = sanitizeString($this->request->getVar('start'));
                
                $rowperpage = sanitizeString($this->request->getVar('length')); // Rows display per page

                $columnIndex = sanitizeString($this->request->getVar('order')[0]['column']); // Column index
                $columnName = sanitizeString($this->request->getVar('columns')[$columnIndex]['data']); // Column name
                if ($columnName=="s_no" || $columnName=="tran_date")
                    $columnName = 'tbl_transaction.tran_date';
                if ($columnName=="holding_no")
                    $columnName = 'tbl_prop_dtl.holding_no';
                if ($columnName=="ward_no")
                    $columnName = 'view_ward_mstr.ward_no';
                if ($columnName=="owner_name")
                    $columnName = 'prop_owner_detail.owner_name';
                if ($columnName=="mobile_no")
                    $columnName = 'prop_owner_detail.mobile_no';
                if ($columnName=="from_upto_fy_qtr")
                    $columnName = 'tbl_transaction.tran_date';
                if ($columnName=="tran_no")
                    $columnName = 'tbl_transaction.tran_no';
                if ($columnName=="transaction_mode")
                    $columnName = 'tbl_transaction.transaction_mode';
                if ($columnName=="cheque_no")
                    $columnName = 'tbl_cheque_details.cheque_no';
                if ($columnName=="bank_name")
                    $columnName = 'tbl_cheque_details.bank_name';
                if ($columnName=="branch_name")
                    $columnName = 'tbl_cheque_details.branch_name';
                if ($columnName=="payable_amt")
                    $columnName = 'tbl_transaction.payable_amt';
                if ($columnName=="emp_name")
                    $columnName = 'view_emp_details.emp_name';

                //$columnName = "tbl_transaction.tran_date";
                $columnSortOrder = sanitizeString($this->request->getVar('order')[0]['dir']); // asc or desc
                $searchValue = sanitizeString($this->request->getVar('search')['value']); // Search value
                
                // Date filter
                $search_from_date = sanitizeString($this->request->getVar('search_from_date'));
                $search_upto_date = sanitizeString($this->request->getVar('search_upto_date'));
                $search_ward_mstr_id = sanitizeString($this->request->getVar('search_ward_mstr_id'));
                $search_collector_id = sanitizeString($this->request->getVar('search_collector_id'));
                $search_tran_mode_mstr_id = sanitizeString($this->request->getVar('search_tran_mode_mstr_id'));
                $whereQuery = "";
                
                $orderBY = " ORDER BY ".$columnName." ".$columnSortOrder;
                $limit = " LIMIT ".$rowperpage." OFFSET ".$start;
                
                $whereQuery .= " AND tbl_transaction.tran_date BETWEEN '".$search_from_date."' AND '".$search_upto_date."'";
                if ($search_ward_mstr_id != '') {
                    $whereQuery .= " AND  tbl_saf_dtl.ward_mstr_id='".$search_ward_mstr_id."'";
                }
                if ($search_collector_id != '') {
                    $whereQuery .= " AND  tbl_transaction.tran_by_emp_details_id='".$search_collector_id."'";
                }
                if ($search_tran_mode_mstr_id != '') {
                    $whereQuery .= " AND  tbl_transaction.tran_mode='".$search_tran_mode_mstr_id."'";
                }
               
                $whereQueryWithSearch = "";
                if ($searchValue!='') {
                    $whereQueryWithSearch = " AND (saf_owner_detail.owner_name ILIKE '%".$searchValue."%'
                                    OR saf_owner_detail.mobile_no ILIKE '%".$searchValue."%'
                                    OR tbl_saf_dtl.holding_no ILIKE '%".$searchValue."%'
                                    OR tbl_transaction.tran_no ILIKE '%".$searchValue."%'
                                    OR tbl_transaction.payable_amt::TEXT ILIKE '%".$searchValue."%')";
                }
                $base_url = base_url();
                $selectStatement = "SELECT 
                                    ROW_NUMBER () OVER (ORDER BY ".$columnName." ".$columnSortOrder.") AS s_no,
                                    view_ward_mstr.ward_no,
                                    CONCAT('<a href=', chr(39), '".$base_url."/safdtl/full/', tbl_saf_dtl.id, '/backbtnhide', chr(39), ' target=', chr(39), 'blank', chr(39), '><b><u>', tbl_saf_dtl.saf_no, '</u></b></a>') AS saf_no,
                                    tbl_saf_dtl.holding_no,
                                    saf_owner_detail.owner_name,
                                    saf_owner_detail.mobile_no,
                                    CONCAT(tbl_transaction.from_fyear, '(', tbl_transaction.from_qtr, ')', ' / ', tbl_transaction.upto_fyear, '(', tbl_transaction.upto_qtr, ')') AS from_upto_fy_qtr,
                                    tbl_transaction.tran_date,
                                    tbl_transaction.tran_mode as transaction_mode,
                                    tbl_transaction.payable_amt,
                                    (CASE WHEN view_emp_details.emp_name IS NOT NULL THEN view_emp_details.emp_name ELSE 'N/A' END) AS emp_name,
                                    tbl_transaction.tran_no,
                                    (CASE WHEN tbl_cheque_details.cheque_no IS NULL THEN 'N/A' ELSE tbl_cheque_details.cheque_no END) AS cheque_no,
                                    (CASE WHEN tbl_cheque_details.bank_name IS NULL THEN 'N/A' ELSE tbl_cheque_details.bank_name END) AS bank_name,
                                    (CASE WHEN tbl_cheque_details.branch_name IS NULL THEN 'N/A' ELSE tbl_cheque_details.branch_name END) AS branch_name";
                $sql =  " FROM tbl_transaction
                INNER JOIN tbl_saf_dtl ON tbl_saf_dtl.id=tbl_transaction.prop_dtl_id and tbl_transaction.tran_type='Saf'
                INNER JOIN (
                        SELECT STRING_AGG(owner_name, ', ') AS owner_name, STRING_AGG(mobile_no::TEXT, ', ') AS mobile_no, saf_dtl_id 
                    FROM tbl_saf_owner_detail GROUP BY saf_dtl_id
                ) saf_owner_detail ON saf_owner_detail.saf_dtl_id=tbl_transaction.prop_dtl_id
                LEFT JOIN view_emp_details ON view_emp_details.id=tbl_transaction.tran_by_emp_details_id
                LEFT JOIN view_ward_mstr ON view_ward_mstr.id=tbl_saf_dtl.ward_mstr_id
                LEFT JOIN (
					SELECT a.* from tbl_cheque_details a
					JOIN (SELECT max(id) as max_id,transaction_id from tbl_cheque_details group by transaction_id) b on b.max_id=a.id
				) tbl_cheque_details ON tbl_cheque_details.transaction_id=tbl_transaction.id 
                WHERE tbl_transaction.tran_type='Saf' and tbl_transaction.status in (1, 2) ".$whereQuery;
                
                $selectSumStatement = "SELECT COALESCE(SUM(tbl_transaction.payable_amt), 0) AS total_payable_amt";
                //return json_encode($selectStatement.$sql.$whereQueryWithSearch.$orderBY.$limit);
                ## Total number of records without filtering
                # print_var($sql);
                $totalRecords = $this->model_datatable->getTotalRecords($sql);
                $total_collection = 0;
                if ($totalRecords>0) {
                    ## Total number of records with filtering
                    $totalRecordwithFilter = $this->model_datatable->getTotalRecordwithFilter($sql.$whereQueryWithSearch);
                    ## Fetch records
                    $fetchSql = $selectStatement.$sql.$whereQueryWithSearch.$orderBY.$limit;
                    $fetchSumSql = $selectSumStatement.$sql;
                    
                    $records = $this->model_datatable->getRecords($fetchSql,false);
                    $total_collection = $this->model_datatable->getSumRecords($fetchSumSql)['total_payable_amt'];
                    //$records['totall'] = 1245;
                } else {
                    $totalRecordwithFilter = 0;
                    $records = [];
                }
                //echo $this->db->getLastQuery();
                $response = array(
                    "draw" => 0,
                    "total_collection" => $total_collection,
                    "recordsTotal" => $totalRecords,
                    "recordsFiltered" => $totalRecordwithFilter,
                    "data" => $records,
                );
                return json_encode($response);
            }catch(Exception $e){

            }
        }
    }
	
	public function SAFcollectionReportExcel($search_from_date = null, $search_upto_date = null, $search_ward_mstr_id = null, $search_collector_id = null, $search_tran_mode = null) {
        try
        {
            $whereQuery = " AND tbl_transaction.tran_date BETWEEN '".$search_from_date."' AND '".$search_upto_date."'";
                if ($search_ward_mstr_id != 'ALL') {
                    $whereQuery .= " AND  tbl_prop_dtl.ward_mstr_id='".$search_ward_mstr_id."'";
                }
                if ($search_collector_id != 'ALL') {
                    $whereQuery .= " AND  tbl_transaction.tran_by_emp_details_id='".$search_collector_id."'";
                }
                if ($search_tran_mode != 'ALL') {
                    $whereQuery .= " AND  tbl_transaction.tran_mode='".$search_tran_mode."'";
                }
                
                $selectStatement = "SELECT
                                    view_ward_mstr.ward_no,
                                    tbl_saf_dtl.saf_no,
                                    saf_owner_detail.owner_name,
                                    saf_owner_detail.mobile_no,
                                    CONCAT(tbl_transaction.from_fyear, '(', tbl_transaction.from_qtr, ')', ' / ', tbl_transaction.upto_fyear, '(', tbl_transaction.upto_qtr, ')') AS from_upto_fy_qtr,
                                    tbl_transaction.tran_date,
                                    tbl_transaction.tran_mode AS transaction_mode,
                                    COALESCE(tbl_transaction.payable_amt, 0) AS payable_amt,
                                    (CASE WHEN view_emp_details.emp_name IS NOT NULL THEN view_emp_details.emp_name ELSE 'N/A' END) AS emp_name,
                                    tbl_transaction.tran_no,
                                    (CASE WHEN tbl_cheque_details.cheque_no IS NULL THEN 'N/A' ELSE tbl_cheque_details.cheque_no END) AS cheque_no,
                                    (CASE WHEN tbl_cheque_details.bank_name IS NULL THEN 'N/A' ELSE tbl_cheque_details.bank_name END) AS bank_name,
                                    (CASE WHEN tbl_cheque_details.branch_name IS NULL THEN 'N/A' ELSE tbl_cheque_details.branch_name END) AS branch_name";
                $sql =  " FROM tbl_transaction
                            INNER JOIN tbl_saf_dtl ON tbl_saf_dtl.id=tbl_transaction.prop_dtl_id
                            INNER JOIN (SELECT STRING_AGG(owner_name, ', ') AS owner_name, STRING_AGG(mobile_no::TEXT, ', ') AS mobile_no, saf_dtl_id FROM tbl_saf_owner_detail GROUP BY saf_dtl_id) saf_owner_detail ON saf_owner_detail.saf_dtl_id=tbl_transaction.prop_dtl_id
                            LEFT JOIN view_emp_details ON view_emp_details.id=tbl_transaction.tran_by_emp_details_id
                            INNER JOIN view_ward_mstr ON view_ward_mstr.id=tbl_saf_dtl.ward_mstr_id
                            LEFT JOIN (
								SELECT a.* from tbl_cheque_details a
								JOIN (SELECT max(id) as max_id,transaction_id from tbl_cheque_details group by transaction_id) b on b.max_id=a.id
							) tbl_cheque_details ON tbl_cheque_details.transaction_id=tbl_transaction.id 
                            WHERE tbl_transaction.tran_type='Saf' and tbl_transaction.status in (1, 2) ".$whereQuery;

            $fetchSql = $selectStatement.$sql;
            $records = $this->model_datatable->getRecords($fetchSql);
            
            //phpOfficeLoad();
            $spreadsheet = new Spreadsheet();
            
            $activeSheet = $spreadsheet->getActiveSheet();
                            $activeSheet->setCellValue('A1', 'Ward No');
                            $activeSheet->setCellValue('B1', 'Saf No');
                            $activeSheet->setCellValue('C1', 'Owner Name');
                            $activeSheet->setCellValue('D1', 'Mobile No.');
                            $activeSheet->setCellValue('E1', 'From/Upto');
                            $activeSheet->setCellValue('F1', 'Tran. Date');
                            $activeSheet->setCellValue('G1', 'Mode');
                            $activeSheet->setCellValue('H1', 'Amount');
                            $activeSheet->setCellValue('I1', 'Tax Collector');
                            $activeSheet->setCellValue('J1', 'Tran. No.');
                            $activeSheet->setCellValue('K1', 'Check/DD No');
                            $activeSheet->setCellValue('L1', 'Bank');
                            $activeSheet->setCellValue('M1', 'Branch');
                            $activeSheet->fromArray($records, NULL, 'A2');
            $spreadsheet->getActiveSheet()->getStyle('B')->getNumberFormat()
                        ->setFormatCode(NumberFormat::FORMAT_TEXT);
            $filename = "saf_collection_report_".date('Ymd-hisa').".xlsx";
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="'.$filename.'"');
            header('Cache-Control: max-age=0');
            $writer = new Xlsx($spreadsheet);
            //$writer->save(APPPATH.'/hello world.xlsx');
            ob_end_clean();
            $writer->save('php://output');
        }catch(Exception $e){
            print_r($e);
        }
    }

    public function SAFcollectionReportExtra() {
        $ulb_mstr_id = 1;
        
        $wardList = $this->model_ward_mstr->getWardListWithSession(['ulb_mstr_id'=>$ulb_mstr_id]);
        $data['wardList'] = $wardList;
		$data['propertytypeList'] = $this->model_prop_type_mstr->getPropTypeList();
        return view('property/reports/saf_collection_report_extra', $data);
    }

    public function SAFcollectionReportAjaxExtra()
    {
        if($this->request->getMethod()=='post')
        {
			try
            {

                ## Read value
                $start = sanitizeString($this->request->getVar('start'));
                
                $rowperpage = sanitizeString($this->request->getVar('length')); // Rows display per page

                $columnIndex = sanitizeString($this->request->getVar('order')[0]['column']); // Column index
                $columnName = sanitizeString($this->request->getVar('columns')[$columnIndex]['data']); // Column name
                if ($columnName=="s_no" || $columnName=="tran_date")
                    $columnName = 'tbl_transaction.tran_date';
                if ($columnName=="holding_no")
                    $columnName = 'tbl_prop_dtl.holding_no';
                if ($columnName=="ward_no")
                    $columnName = 'view_ward_mstr.ward_no';
                if ($columnName=="owner_name")
                    $columnName = 'prop_owner_detail.owner_name';
                if ($columnName=="mobile_no")
                    $columnName = 'prop_owner_detail.mobile_no';
                if ($columnName=="from_upto_fy_qtr")
                    $columnName = 'tbl_transaction.tran_date';
                if ($columnName=="tran_no")
                    $columnName = 'tbl_transaction.tran_no';
                if ($columnName=="transaction_mode")
                    $columnName = 'tbl_transaction.transaction_mode';
                if ($columnName=="payable_amt")
                    $columnName = 'tbl_transaction.payable_amt';

                //$columnName = "tbl_transaction.tran_date";
                $columnSortOrder = sanitizeString($this->request->getVar('order')[0]['dir']); // asc or desc
                $searchValue = sanitizeString($this->request->getVar('search')['value']); // Search value
                
                // Date filter
                $search_from_date = sanitizeString($this->request->getVar('search_from_date'));
                $search_upto_date = sanitizeString($this->request->getVar('search_upto_date'));
                $search_ward_mstr_id = sanitizeString($this->request->getVar('search_ward_mstr_id'));
				$search_property_type_id = sanitizeString($this->request->getVar('search_property_type_id'));
                $search_tran_mode_mstr_id = sanitizeString($this->request->getVar('search_tran_mode_mstr_id'));
                $whereQuery = "";
                
                $orderBY = " ORDER BY ".$columnName." ".$columnSortOrder;
                $limit = " LIMIT ".$rowperpage." OFFSET ".$start;
                
                $whereQuery .= " AND tbl_transaction.tran_date BETWEEN '".$search_from_date."' AND '".$search_upto_date."'";
                if ($search_ward_mstr_id != '') {
                    $whereQuery .= " AND  tbl_saf_dtl.ward_mstr_id='".$search_ward_mstr_id."'";
                }
				if ($search_property_type_id != '') {
                    $whereQuery .= " AND  tbl_saf_dtl.prop_type_mstr_id='" . $search_property_type_id . "'";
                }
                if ($search_tran_mode_mstr_id != '') {
                    $whereQuery .= " AND  tbl_transaction.tran_mode='".$search_tran_mode_mstr_id."'";
                }
               
                $whereQueryWithSearch = "";
                if ($searchValue!='') {
                    $whereQueryWithSearch = " AND (saf_owner_detail.owner_name ILIKE '%".$searchValue."%'
                                    OR saf_owner_detail.mobile_no ILIKE '%".$searchValue."%'
                                    OR tbl_saf_dtl.holding_no ILIKE '%".$searchValue."%'
                                    OR tbl_transaction.tran_no ILIKE '%".$searchValue."%'
                                    OR tbl_transaction.payable_amt::TEXT ILIKE '%".$searchValue."%')";
                }
                $base_url = base_url();
                $selectStatement = "SELECT 
                                    ROW_NUMBER () OVER (ORDER BY ".$columnName." ".$columnSortOrder.") AS s_no,
                                    view_ward_mstr.ward_no,
                                    CONCAT('<a href=', chr(39), '".$base_url."/safdtl/full/', tbl_saf_dtl.id, '/backbtnhide', chr(39), ' target=', chr(39), 'blank', chr(39), '><b><u>', tbl_saf_dtl.saf_no, '</u></b></a>') AS saf_no,
                                    saf_owner_detail.owner_name,
                                    saf_owner_detail.mobile_no,
                                    CONCAT(tbl_transaction.from_fyear, '(', tbl_transaction.from_qtr, ')', ' / ', tbl_transaction.upto_fyear, '(', tbl_transaction.upto_qtr, ')') AS from_upto_fy_qtr,
                                    tbl_transaction.tran_date,
                                    tbl_transaction.tran_mode as transaction_mode,
                                    tbl_transaction.payable_amt,
                                    tbl_transaction.tran_no,
                                    COALESCE(rebate_dtl.rebate_amt, 0) as rebate_amt,
	                                COALESCE(penalty_dtl.penalty_amt, 0) as penalty_amt,
                                    COALESCE(late_penalty_dtl.late_penalty_amt, 0) as late_penalty_amt";
                $sql =  " FROM tbl_transaction
                INNER JOIN tbl_saf_dtl ON tbl_saf_dtl.id=tbl_transaction.prop_dtl_id and tbl_transaction.tran_type='Saf'
                INNER JOIN (
                        SELECT STRING_AGG(owner_name, ', ') AS owner_name, STRING_AGG(mobile_no::TEXT, ', ') AS mobile_no, saf_dtl_id 
                    FROM tbl_saf_owner_detail GROUP BY saf_dtl_id
                ) saf_owner_detail ON saf_owner_detail.saf_dtl_id=tbl_transaction.prop_dtl_id
                LEFT JOIN view_ward_mstr ON view_ward_mstr.id=tbl_saf_dtl.ward_mstr_id
                LEFT JOIN (
                    SELECT
                        transaction_id,
                        SUM(amount) AS rebate_amt
                    from tbl_transaction_fine_rebet_details
                    where status=1 AND head_name in ('First Qtr Rebate', 'JSK (2.5%) Rebate', 'Rebate Amount', 'Rebate From JSK', 'Rebate From Jsk/Online Payment', 'Special Rebate', 'Online Rebate') 
                    GROUP BY transaction_id
                ) AS rebate_dtl ON rebate_dtl.transaction_id=tbl_transaction.id
                LEFT JOIN (
                    SELECT
                        transaction_id,
                        SUM(amount) AS penalty_amt
                    from tbl_transaction_fine_rebet_details
                    where status=1 AND head_name='1% Monthly Penalty'
                    GROUP BY transaction_id
                ) AS penalty_dtl ON penalty_dtl.transaction_id=tbl_transaction.id
                LEFT JOIN (
                    SELECT
                        transaction_id,
                        SUM(amount) AS late_penalty_amt
                    from tbl_transaction_fine_rebet_details
                    where status=1 AND head_name='Late Assessment Fine(Rule 14.1)'
                    GROUP BY transaction_id
                ) AS late_penalty_dtl ON late_penalty_dtl.transaction_id=tbl_transaction.id
                WHERE tbl_transaction.tran_type='Saf' and tbl_transaction.status in (1, 2) ".$whereQuery;
                
                $selectSumStatement = "SELECT COALESCE(SUM(tbl_transaction.payable_amt), 0) AS total_payable_amt, COALESCE(SUM(rebate_dtl.rebate_amt), 0) AS total_rebate_amt, COALESCE(SUM(penalty_dtl.penalty_amt + late_penalty_dtl.late_penalty_amt), 0) AS total_penalty_amt";
                //return json_encode($selectStatement.$sql.$whereQueryWithSearch.$orderBY.$limit);
                ## Total number of records without filtering
                # print_var($sql);
                $totalRecords = $this->model_datatable->getTotalRecords($sql);
                $total_collection = 0;
                if ($totalRecords>0) {
                    ## Total number of records with filtering
                    $totalRecordwithFilter = $this->model_datatable->getTotalRecordwithFilter($sql.$whereQueryWithSearch);
                    ## Fetch records
                    $fetchSql = $selectStatement.$sql.$whereQueryWithSearch.$orderBY.$limit;
                    $fetchSumSql = $selectSumStatement.$sql;
                    
                    $records = $this->model_datatable->getRecords($fetchSql,false);
                    $total_collectd_amt = $this->model_datatable->getSumRecords($fetchSumSql);
                    $total_collection = $total_collectd_amt['total_payable_amt'];
                    $total_rebate = $total_collectd_amt['total_rebate_amt'];
                    $total_penalty = $total_collectd_amt['total_penalty_amt'];
                    //$records['totall'] = 1245;
                } else {
                    $totalRecordwithFilter = 0;
                    $records = [];
                }
                //echo $this->db->getLastQuery();
                $response = array(
                    "draw" => 0,
                    "total_collection" => $total_collection,
					"total_rebate" => $total_rebate,
                    "total_penalty" => $total_penalty,
                    "recordsTotal" => $totalRecords,
                    "recordsFiltered" => $totalRecordwithFilter,
                    "data" => $records,
                );
                return json_encode($response);
            }catch(Exception $e){

            }
        }
    }
	
	public function SAFcollectionReportExcelExtra($search_from_date = null, $search_upto_date = null, $search_ward_mstr_id = null, $search_tran_mode = null) {
        try
        {
            $whereQuery = " AND tbl_transaction.tran_date BETWEEN '".$search_from_date."' AND '".$search_upto_date."'";
                if ($search_ward_mstr_id != 'ALL') {
                    $whereQuery .= " AND  tbl_prop_dtl.ward_mstr_id='".$search_ward_mstr_id."'";
                }
                if ($search_tran_mode != 'ALL') {
                    $whereQuery .= " AND  tbl_transaction.tran_mode='".$search_tran_mode."'";
                }
                
                $selectStatement = "SELECT
                                    view_ward_mstr.ward_no,
                                    tbl_saf_dtl.saf_no,
                                    saf_owner_detail.owner_name,
                                    saf_owner_detail.mobile_no,
                                    CONCAT(tbl_transaction.from_fyear, '(', tbl_transaction.from_qtr, ')', ' / ', tbl_transaction.upto_fyear, '(', tbl_transaction.upto_qtr, ')') AS from_upto_fy_qtr,
                                    tbl_transaction.tran_date,
                                    tbl_transaction.tran_mode AS transaction_mode,
                                    COALESCE(tbl_transaction.payable_amt, 0) AS payable_amt,
                                    tbl_transaction.tran_no,
                                    COALESCE(rebate_dtl.rebate_amt, 0) as rebate_amt,
	                                COALESCE(penalty_dtl.penalty_amt, 0) as penalty_amt,
                                    COALESCE(late_penalty_dtl.late_penalty_amt, 0) as late_penalty_amt";
                $sql =  " FROM tbl_transaction
                            INNER JOIN tbl_saf_dtl ON tbl_saf_dtl.id=tbl_transaction.prop_dtl_id
                            INNER JOIN (SELECT STRING_AGG(owner_name, ', ') AS owner_name, STRING_AGG(mobile_no::TEXT, ', ') AS mobile_no, saf_dtl_id FROM tbl_saf_owner_detail GROUP BY saf_dtl_id) saf_owner_detail ON saf_owner_detail.saf_dtl_id=tbl_transaction.prop_dtl_id
                            LEFT JOIN view_ward_mstr ON view_ward_mstr.id=tbl_saf_dtl.ward_mstr_id
                            LEFT JOIN (
                                SELECT
                                    transaction_id,
                                    SUM(amount) AS rebate_amt
                                from tbl_transaction_fine_rebet_details
                                where status=1 AND head_name in ('First Qtr Rebate', 'JSK (2.5%) Rebate', 'Rebate Amount', 'Rebate From JSK', 'Rebate From Jsk/Online Payment', 'Special Rebate', 'Online Rebate') 
                                GROUP BY transaction_id
                            ) AS rebate_dtl ON rebate_dtl.transaction_id=tbl_transaction.id
                            LEFT JOIN (
                                SELECT
                                    transaction_id,
                                    SUM(amount) AS penalty_amt
                                from tbl_transaction_fine_rebet_details
                                where status=1 AND head_name='1% Monthly Penalty'
                                GROUP BY transaction_id
                            ) AS penalty_dtl ON penalty_dtl.transaction_id=tbl_transaction.id
                            LEFT JOIN (
                                SELECT
                                    transaction_id,
                                    SUM(amount) AS late_penalty_amt
                                from tbl_transaction_fine_rebet_details
                                where status=1 AND head_name='Late Assessment Fine(Rule 14.1)'
                                GROUP BY transaction_id
                            ) AS late_penalty_dtl ON late_penalty_dtl.transaction_id=tbl_transaction.id
                            WHERE tbl_transaction.tran_type='Saf' and tbl_transaction.status in (1, 2) ".$whereQuery;

            $fetchSql = $selectStatement.$sql;
            $records = $this->model_datatable->getRecords($fetchSql);
            
            //phpOfficeLoad();
            $spreadsheet = new Spreadsheet();
            
            $activeSheet = $spreadsheet->getActiveSheet();
                            $activeSheet->setCellValue('A1', 'Ward No');
                            $activeSheet->setCellValue('B1', 'Saf No');
                            $activeSheet->setCellValue('C1', 'Owner Name');
                            $activeSheet->setCellValue('D1', 'Mobile No.');
                            $activeSheet->setCellValue('E1', 'From/Upto');
                            $activeSheet->setCellValue('F1', 'Tran. Date');
                            $activeSheet->setCellValue('G1', 'Mode');
                            $activeSheet->setCellValue('H1', 'Amount');
                            $activeSheet->setCellValue('I1', 'Tran. No.');
                            $activeSheet->setCellValue('J1', 'Rebate Amount');
                            $activeSheet->setCellValue('K1', 'Penalty Amount');
                            $activeSheet->setCellValue('L1', 'Late Penalty Amount');
                            $activeSheet->fromArray($records, NULL, 'A2');
            $spreadsheet->getActiveSheet()->getStyle('B')->getNumberFormat()
                        ->setFormatCode(NumberFormat::FORMAT_TEXT);
            $filename = "saf_collection_report_extra_".date('Ymd-hisa').".xlsx";
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="'.$filename.'"');
            header('Cache-Control: max-age=0');
            $writer = new Xlsx($spreadsheet);
            //$writer->save(APPPATH.'/hello world.xlsx');
            ob_end_clean();
            $writer->save('php://output');
        }catch(Exception $e){
            print_r($e);
        }
    }

    public function GBSAFcollectionReport() {
        $ulb_mstr_id = 1;
        $wardList = $this->model_ward_mstr->getWardListWithSession(['ulb_mstr_id'=>$ulb_mstr_id]);
        $data['wardList'] = $wardList;
        return view('property/reports/gbsaf_collection_report', $data);
    }

    public function GBSAFcollectionReportAjax()
    {
        if($this->request->getMethod()=='post')
        {
			try
            {

                ## Read value
                $start = sanitizeString($this->request->getVar('start'));
                
                $rowperpage = sanitizeString($this->request->getVar('length')); // Rows display per page

                $columnIndex = sanitizeString($this->request->getVar('order')[0]['column']); // Column index
                $columnName = sanitizeString($this->request->getVar('columns')[$columnIndex]['data']); // Column name
                if ($columnName=="s_no" || $columnName=="tran_date")
                    $columnName = 'tbl_govt_saf_transaction.tran_date';
                if ($columnName=="application_no")
                    $columnName = 'tbl_govt_saf_dtl.application_no';
                if ($columnName=="ward_no")
                    $columnName = 'view_ward_mstr.ward_no';
                if ($columnName=="building_colony_address")
                    $columnName = 'tbl_govt_saf_dtl.building_colony_address';
                if ($columnName=="from_upto_fy_qtr")
                    $columnName = 'tbl_govt_saf_transaction.tran_date';
                if ($columnName=="tran_no")
                    $columnName = 'tbl_govt_saf_transaction.tran_no';
                if ($columnName=="transaction_mode")
                    $columnName = 'tbl_govt_saf_transaction.transaction_mode';
                if ($columnName=="cheque_no")
                    $columnName = 'tbl_govt_saf_transaction_details.cheque_no';
                if ($columnName=="bank_name")
                    $columnName = 'tbl_govt_saf_transaction_details.bank_name';
                if ($columnName=="branch_name")
                    $columnName = 'tbl_govt_saf_transaction_details.branch_name';
                if ($columnName=="payable_amt")
                    $columnName = 'tbl_govt_saf_transaction.payable_amt';
                if ($columnName=="emp_name")
                    $columnName = 'view_emp_details.emp_name';

                //$columnName = "tbl_govt_saf_transaction.tran_date";
                $columnSortOrder = sanitizeString($this->request->getVar('order')[0]['dir']); // asc or desc
                $searchValue = sanitizeString($this->request->getVar('search')['value']); // Search value
                
                // Date filter
                $search_from_date = sanitizeString($this->request->getVar('search_from_date'));
                $search_upto_date = sanitizeString($this->request->getVar('search_upto_date'));
                $search_ward_mstr_id = sanitizeString($this->request->getVar('search_ward_mstr_id'));
                $search_collector_id = sanitizeString($this->request->getVar('search_collector_id'));
                $search_tran_mode_mstr_id = sanitizeString($this->request->getVar('search_tran_mode_mstr_id'));
                $whereQuery = "";
                
                $orderBY = " ORDER BY ".$columnName." ".$columnSortOrder;
                $limit = " LIMIT ".$rowperpage." OFFSET ".$start;
                
                $whereQuery .= " AND tbl_govt_saf_transaction.tran_date BETWEEN '".$search_from_date."' AND '".$search_upto_date."'";
                if ($search_ward_mstr_id != '') {
                    $whereQuery .= " AND  tbl_govt_saf_dtl.ward_mstr_id='".$search_ward_mstr_id."'";
                }
                if ($search_collector_id != '') {
                    $whereQuery .= " AND  tbl_govt_saf_transaction.tran_by_emp_details_id='".$search_collector_id."'";
                }
                if ($search_tran_mode_mstr_id != '') {
                    $whereQuery .= " AND  tbl_govt_saf_transaction.tran_mode='".$search_tran_mode_mstr_id."'";
                }
               
                $whereQueryWithSearch = "";
                if ($searchValue!='') {
                    $whereQueryWithSearch = " AND (tbl_govt_saf_dtl.building_colony_address ILIKE '%".$searchValue."%'
                                    OR tbl_govt_saf_dtl.application_no ILIKE '%".$searchValue."%'
                                    OR tbl_govt_saf_transaction.tran_no ILIKE '%".$searchValue."%'
                                    OR tbl_govt_saf_transaction.payable_amt::TEXT ILIKE '%".$searchValue."%')";
                }
                $base_url = base_url();
                $selectStatement = "SELECT 
                                    ROW_NUMBER () OVER (ORDER BY ".$columnName." ".$columnSortOrder.") AS s_no,
                                    view_ward_mstr.ward_no,
                                    tbl_govt_saf_dtl.application_no,
                                    tbl_govt_saf_dtl.building_colony_name, tbl_govt_saf_dtl.office_name,
                                    tbl_govt_saf_dtl.building_colony_address,
                                    CONCAT(tbl_govt_saf_transaction.from_fyear, '(', tbl_govt_saf_transaction.from_qtr, ')', ' / ', tbl_govt_saf_transaction.upto_fyear, '(', tbl_govt_saf_transaction.upto_qtr, ')') AS from_upto_fy_qtr,
                                    tbl_govt_saf_transaction.tran_date,
                                    tbl_govt_saf_transaction.tran_mode as transaction_mode,
                                    tbl_govt_saf_transaction.payable_amt,
                                    (CASE WHEN view_emp_details.emp_name IS NOT NULL THEN view_emp_details.emp_name ELSE 'N/A' END) AS emp_name,
                                    tbl_govt_saf_transaction.tran_no,
                                    (CASE WHEN tbl_govt_saf_transaction_details.cheque_no IS NULL THEN 'N/A' ELSE tbl_govt_saf_transaction_details.cheque_no END) AS cheque_no,
                                    (CASE WHEN tbl_govt_saf_transaction_details.bank_name IS NULL THEN 'N/A' ELSE tbl_govt_saf_transaction_details.bank_name END) AS bank_name,
                                    (CASE WHEN tbl_govt_saf_transaction_details.branch_name IS NULL THEN 'N/A' ELSE tbl_govt_saf_transaction_details.branch_name END) AS branch_name";
                $sql =  " FROM tbl_govt_saf_transaction
                INNER JOIN tbl_govt_saf_dtl ON tbl_govt_saf_dtl.id=tbl_govt_saf_transaction.govt_saf_dtl_id
                LEFT JOIN view_emp_details ON view_emp_details.id=tbl_govt_saf_transaction.tran_by_emp_details_id
                LEFT JOIN view_ward_mstr ON view_ward_mstr.id=tbl_govt_saf_dtl.ward_mstr_id
                LEFT JOIN tbl_govt_saf_transaction_details ON tbl_govt_saf_transaction_details.govt_saf_transaction_id=tbl_govt_saf_transaction.id
                WHERE tbl_govt_saf_transaction.status in (1, 2) ".$whereQuery;
                
                $selectSumStatement = "SELECT COALESCE(SUM(tbl_govt_saf_transaction.payable_amt), 0) AS total_payable_amt";
                //return json_encode($selectStatement.$sql.$whereQueryWithSearch.$orderBY.$limit);
                ## Total number of records without filtering
                $fetchSql = $selectStatement.$sql.$whereQueryWithSearch.$orderBY.$limit;
                //print_var($fetchSql);

                $totalRecords = $this->model_datatable->getTotalRecords($sql);
                $total_collection = 0;
                if ($totalRecords>0) {
                    ## Total number of records with filtering
                    $totalRecordwithFilter = $this->model_datatable->getTotalRecordwithFilter($sql.$whereQueryWithSearch);
                    ## Fetch records
                    $fetchSql = $selectStatement.$sql.$whereQueryWithSearch.$orderBY.$limit;
                    $fetchSumSql = $selectSumStatement.$sql;
                    
                    $records = $this->model_datatable->getRecords($fetchSql,false);
                    $total_collection = $this->model_datatable->getSumRecords($fetchSumSql)['total_payable_amt'];
                    //$records['totall'] = 1245;
                } else {
                    $totalRecordwithFilter = 0;
                    $records = [];
                }
                //echo $this->db->getLastQuery();
                $response = array(
                    "draw" => 0,
                    "total_collection" => $total_collection,
                    "recordsTotal" => $totalRecords,
                    "recordsFiltered" => $totalRecordwithFilter,
                    "data" => $records,
                );
                return json_encode($response);
            }catch(Exception $e){

            }
        }
    }

    public function GBSAFcollectionReportExcel($search_from_date = null, $search_upto_date = null, $search_ward_mstr_id = null, $search_tran_mode = null) {
        try
        {
            $whereQuery = " AND tbl_govt_saf_transaction.tran_date BETWEEN '".$search_from_date."' AND '".$search_upto_date."'";
                if ($search_ward_mstr_id != 'ALL') {
                    $whereQuery .= " AND  tbl_govt_saf_dtl.ward_mstr_id='".$search_ward_mstr_id."'";
                }
                if ($search_tran_mode != 'ALL') {
                    $whereQuery .= " AND  tbl_govt_saf_transaction.tran_mode='".$search_tran_mode."'";
                }
                
                $selectStatement = "SELECT
                                        view_ward_mstr.ward_no,
                                        tbl_govt_saf_dtl.application_no,
                                        tbl_govt_saf_dtl.building_colony_name, tbl_govt_saf_dtl.office_name,
                                        tbl_govt_saf_dtl.building_colony_address,
                                        CONCAT(tbl_govt_saf_transaction.from_fyear, '(', tbl_govt_saf_transaction.from_qtr, ')', ' / ', tbl_govt_saf_transaction.upto_fyear, '(', tbl_govt_saf_transaction.upto_qtr, ')') AS from_upto_fy_qtr,
                                        tbl_govt_saf_transaction.tran_date,
                                        tbl_govt_saf_transaction.tran_mode as transaction_mode,
                                        tbl_govt_saf_transaction.payable_amt,
                                        (CASE WHEN view_emp_details.emp_name IS NOT NULL THEN view_emp_details.emp_name ELSE 'N/A' END) AS emp_name,
                                        tbl_govt_saf_transaction.tran_no,
                                        (CASE WHEN tbl_govt_saf_transaction_details.cheque_no IS NULL THEN 'N/A' ELSE tbl_govt_saf_transaction_details.cheque_no END) AS cheque_no,
                                        (CASE WHEN tbl_govt_saf_transaction_details.bank_name IS NULL THEN 'N/A' ELSE tbl_govt_saf_transaction_details.bank_name END) AS bank_name,
                                        (CASE WHEN tbl_govt_saf_transaction_details.branch_name IS NULL THEN 'N/A' ELSE tbl_govt_saf_transaction_details.branch_name END) AS branch_name";
                $sql =  " FROM tbl_govt_saf_transaction
                INNER JOIN tbl_govt_saf_dtl ON tbl_govt_saf_dtl.id=tbl_govt_saf_transaction.govt_saf_dtl_id
                LEFT JOIN view_emp_details ON view_emp_details.id=tbl_govt_saf_transaction.tran_by_emp_details_id
                LEFT JOIN view_ward_mstr ON view_ward_mstr.id=tbl_govt_saf_dtl.ward_mstr_id
                LEFT JOIN tbl_govt_saf_transaction_details ON tbl_govt_saf_transaction_details.govt_saf_transaction_id=tbl_govt_saf_transaction.id
                WHERE tbl_govt_saf_transaction.status in (1, 2) ".$whereQuery;

            $fetchSql = $selectStatement.$sql;
            //die();
            $records = $this->model_datatable->getRecords($fetchSql);
            
            //phpOfficeLoad();
            $spreadsheet = new Spreadsheet();
            
            $activeSheet = $spreadsheet->getActiveSheet();
                            $activeSheet->setCellValue('A1', 'Ward No');
                            $activeSheet->setCellValue('B1', 'Application No.');
                            $activeSheet->setCellValue('C1', 'Building Colony Name');
                            $activeSheet->setCellValue('D1', 'Office Name');
                            $activeSheet->setCellValue('E1', 'Address');
                            $activeSheet->setCellValue('F1', 'From/Upto');
                            $activeSheet->setCellValue('G1', 'Tran. Date');
                            $activeSheet->setCellValue('H1', 'Mode');
                            $activeSheet->setCellValue('I1', 'Amount');
                            $activeSheet->setCellValue('J1', 'Tax Collector');
                            $activeSheet->setCellValue('K1', 'Tran. No.');
                            $activeSheet->setCellValue('L1', 'Check/DD No');
                            $activeSheet->setCellValue('M1', 'Bank');
                            $activeSheet->setCellValue('N1', 'Branch');
                            $activeSheet->fromArray($records, NULL, 'A2');
            $spreadsheet->getActiveSheet()->getStyle('B')->getNumberFormat()
                        ->setFormatCode(NumberFormat::FORMAT_TEXT);
            $filename = "gb_saf_collection_report_".date('Ymd-hisa').".xlsx";
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="'.$filename.'"');
            header('Cache-Control: max-age=0');
            $writer = new Xlsx($spreadsheet);
            //$writer->save(APPPATH.'/hello world.xlsx');
            ob_end_clean();
            $writer->save('php://output');
        }catch(Exception $e){
            print_r($e);
        }
    }

    public function collectionReport() {
        $ulb_mstr_id = 1;
        $wardList = $this->model_ward_mstr->getWardList(['ulb_mstr_id'=>$ulb_mstr_id]);
        //$wardList = $this->model_ward_mstr->getWardListWithSession(['ulb_mstr_id'=>$ulb_mstr_id], $session);
        $empDtlList = $this->model_view_emp_details->getEmpListByAlTaxCollector(['ulb_mstr_id'=>$ulb_mstr_id]);
        $data['wardList'] = $wardList;
        $data['empDtlList'] = $empDtlList;
        return view('property/reports/collection_report', $data);
    }
	
	public function collectionReportAjax() {
        if($this->request->getMethod()=='post'){
			try{
                ## Read value
                $start = sanitizeString($this->request->getVar('start'));
                
                $rowperpage = sanitizeString($this->request->getVar('length')); // Rows display per page

                $columnIndex = sanitizeString($this->request->getVar('order')[0]['column']); // Column index
                $columnName = sanitizeString($this->request->getVar('columns')[$columnIndex]['data']); // Column name
                if ($columnName=="s_no" || $columnName=="tran_date")
                    $columnName = 'tbl_transaction.tran_date';
                if ($columnName=="holding_no")
                    $columnName = 'tbl_prop_dtl.holding_no';
                if ($columnName=="ward_no")
                    $columnName = 'view_ward_mstr.ward_no';
                if ($columnName=="owner_name")
                    $columnName = 'prop_owner_detail.owner_name';
                if ($columnName=="mobile_no")
                    $columnName = 'prop_owner_detail.mobile_no';
                if ($columnName=="from_upto_fy_qtr")
                    $columnName = 'tbl_transaction.tran_date';
                if ($columnName=="tran_no")
                    $columnName = 'tbl_transaction.tran_no';
                if ($columnName=="transaction_mode")
                    $columnName = 'tbl_transaction.transaction_mode';
                if ($columnName=="cheque_no")
                    $columnName = 'tbl_cheque_details.cheque_no';
                if ($columnName=="bank_name")
                    $columnName = 'tbl_cheque_details.bank_name';
                if ($columnName=="branch_name")
                    $columnName = 'tbl_cheque_details.branch_name';
                if ($columnName=="payable_amt")
                    $columnName = 'tbl_transaction.payable_amt';
                if ($columnName=="emp_name")
                    $columnName = 'view_emp_details.emp_name';

                //$columnName = "tbl_transaction.tran_date";
                $columnSortOrder = sanitizeString($this->request->getVar('order')[0]['dir']); // asc or desc
                $searchValue = sanitizeString($this->request->getVar('search')['value']); // Search value
                
                // Date filter
                $search_from_date = sanitizeString($this->request->getVar('search_from_date'));
                $search_upto_date = sanitizeString($this->request->getVar('search_upto_date'));
                $search_ward_mstr_id = sanitizeString($this->request->getVar('search_ward_mstr_id'));
                $search_collector_id = sanitizeString($this->request->getVar('search_collector_id'));
                $search_tran_mode_mstr_id = sanitizeString($this->request->getVar('search_tran_mode_mstr_id'));
                $searchQuery = "";
                $whereQuery = "";
                
                $orderBY = " ORDER BY ".$columnName." ".$columnSortOrder;
                $limit = " LIMIT ".$rowperpage." OFFSET ".$start;
                
                $whereQuery .= " AND tbl_transaction.tran_date BETWEEN '".$search_from_date."' AND '".$search_upto_date."'";
                if ($search_ward_mstr_id != '') {
                    $whereQuery .= " AND  tbl_prop_dtl.ward_mstr_id='".$search_ward_mstr_id."'";
                }
                if ($search_collector_id != '') {
                    $whereQuery .= " AND  tbl_transaction.tran_by_emp_details_id='".$search_collector_id."'";
                }
                if ($search_tran_mode_mstr_id != '') {
                    $whereQuery .= " AND  tbl_transaction.tran_mode='".$search_tran_mode_mstr_id."'";
                }
                $whereQueryWithSearch = "";
                if ($searchValue!='') {
                    $whereQueryWithSearch = " AND (prop_owner_detail.owner_name ILIKE '%".$searchValue."%'
                                    OR prop_owner_detail.mobile_no ILIKE '%".$searchValue."%'
                                    OR tbl_prop_dtl.holding_no ILIKE '%".$searchValue."%'
                                    OR tbl_transaction.tran_no ILIKE '%".$searchValue."%'
                                    OR tbl_transaction.payable_amt::TEXT ILIKE '%".$searchValue."%')";
                }
                $base_url = base_url();
                $selectStatement = "SELECT 
                                    ROW_NUMBER () OVER (ORDER BY ".$columnName." ".$columnSortOrder.") AS s_no,
                                    view_ward_mstr.ward_no,
                                    CONCAT('<a href=', chr(39), '".$base_url."/propdtl/full/', tbl_prop_dtl.id, '/backbtnhide', chr(39), ' target=', chr(39), '_blank', chr(39), '><b><u>', tbl_prop_dtl.holding_no, '</u></b></a>') AS holding_no,
                                    (CASE WHEN tbl_prop_dtl.new_holding_no='' OR tbl_prop_dtl.new_holding_no IS NULL THEN 'N/A' ELSE tbl_prop_dtl.new_holding_no END) AS new_holding_no,
                                    prop_owner_detail.owner_name,
                                    prop_owner_detail.mobile_no,
                                    CONCAT(tbl_transaction.from_fyear, '(', tbl_transaction.from_qtr, ')', ' / ', tbl_transaction.upto_fyear, '(', tbl_transaction.upto_qtr, ')') AS from_upto_fy_qtr,
                                    tbl_transaction.tran_date,
                                    tbl_transaction.tran_mode AS transaction_mode,
                                    tbl_transaction.payable_amt,
                                    (CASE WHEN view_emp_details.emp_name IS NOT NULL THEN view_emp_details.emp_name ELSE 'N/A' END) AS emp_name,
                                    tbl_transaction.tran_no,
                                    (CASE WHEN tbl_cheque_details.cheque_no IS NULL THEN 'N/A' ELSE tbl_cheque_details.cheque_no END) AS cheque_no,
                                    (CASE WHEN tbl_cheque_details.bank_name IS NULL THEN 'N/A' ELSE tbl_cheque_details.bank_name END) AS bank_name,
                                    (CASE WHEN tbl_cheque_details.branch_name IS NULL THEN 'N/A' ELSE tbl_cheque_details.branch_name END) AS branch_name";
                $sql =  " FROM tbl_transaction
                        INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_transaction.prop_dtl_id
                        INNER JOIN (SELECT STRING_AGG(owner_name, ', ') AS owner_name, STRING_AGG(mobile_no::TEXT, ', ') AS mobile_no, prop_dtl_id FROM tbl_prop_owner_detail GROUP BY prop_dtl_id) prop_owner_detail ON prop_owner_detail.prop_dtl_id=tbl_transaction.prop_dtl_id
                        LEFT JOIN view_emp_details ON view_emp_details.id=tbl_transaction.tran_by_emp_details_id
                        INNER JOIN view_ward_mstr ON view_ward_mstr.id=tbl_prop_dtl.ward_mstr_id
                        LEFT JOIN (
                            SELECT a.* from tbl_cheque_details a
                            JOIN (SELECT max(id) as max_id,transaction_id from tbl_cheque_details group by transaction_id) b on b.max_id=a.id
                        ) tbl_cheque_details ON tbl_cheque_details.transaction_id=tbl_transaction.id 
                        WHERE tbl_transaction.tran_type='Property' AND tbl_transaction.status in (1, 2)".$whereQuery;
                
                $selectSumStatement = "SELECT COALESCE(SUM(tbl_transaction.payable_amt), 0) AS total_payable_amt";
                //return json_encode($selectStatement.$sql.$whereQueryWithSearch.$orderBY.$limit);
                ## Total number of records without filtering
                
                $totalRecords = $this->model_datatable->getTotalRecords($sql);
                $total_collection = 0;
                if ($totalRecords>0) {
                    ## Total number of records with filtering
                    $totalRecordwithFilter = $this->model_datatable->getTotalRecordwithFilter($sql.$whereQueryWithSearch);
                    ## Fetch records
                    $fetchSql = $selectStatement.$sql.$whereQueryWithSearch.$orderBY.$limit;
                    $fetchSumSql = $selectSumStatement.$sql;
                   
                    $records = $this->model_datatable->getRecords($fetchSql);
                    $total_collection = $this->model_datatable->getSumRecords($fetchSumSql)['total_payable_amt'];
                    //$records['totall'] = 1245;
                } else {
                    $totalRecordwithFilter = 0;
                    $records = [];
                }
                //echo $this->db->getLastQuery();
                $response = array(
                    "draw" => 0,
                    "total_collection" => $total_collection,
                    "recordsTotal" => $totalRecords,
                    "recordsFiltered" => $totalRecordwithFilter,
                    "data" => $records,
                );
                return json_encode($response);
            }catch(Exception $e){

            }
        }
    }

    public function collectionReportExcel($search_from_date = null, $search_upto_date = null, $search_ward_mstr_id = null, $search_collector_id = null, $search_tran_mode_mstr_id = null)
    {
        try
        {
            $whereQuery = " AND tbl_transaction.tran_date BETWEEN '".$search_from_date."' AND '".$search_upto_date."'";
                if ($search_ward_mstr_id != 'ALL') {
                    $whereQuery .= " AND  tbl_prop_dtl.ward_mstr_id='".$search_ward_mstr_id."'";
                }
                if ($search_collector_id != 'ALL') {
                    $whereQuery .= " AND  tbl_transaction.tran_by_emp_details_id='".$search_collector_id."'";
                }
                if ($search_tran_mode_mstr_id != 'ALL') {
                    $whereQuery .= " AND  tbl_transaction.tran_mode='".$search_tran_mode_mstr_id."'";
                }
                
                $selectStatement = "SELECT
                                    view_ward_mstr.ward_no,
                                    tbl_prop_dtl.holding_no,
                                    tbl_prop_dtl.new_holding_no,
                                    prop_owner_detail.owner_name,
                                    prop_owner_detail.mobile_no,
                                    CONCAT(tbl_transaction.from_fyear, '(', tbl_transaction.from_qtr, ')', ' / ', tbl_transaction.upto_fyear, '(', tbl_transaction.upto_qtr, ')') AS from_upto_fy_qtr,
                                    tbl_transaction.tran_date,
                                    tbl_transaction.tran_mode AS transaction_mode,
                                    COALESCE(tbl_transaction.payable_amt, 0) AS payable_amt,
                                    (CASE WHEN view_emp_details.emp_name IS NOT NULL THEN view_emp_details.emp_name ELSE 'N/A' END) AS emp_name,
                                    tbl_transaction.tran_no,
                                    (CASE WHEN tbl_cheque_details.cheque_no IS NULL THEN 'N/A' ELSE tbl_cheque_details.cheque_no END) AS cheque_no,
                                    (CASE WHEN tbl_cheque_details.bank_name IS NULL THEN 'N/A' ELSE tbl_cheque_details.bank_name END) AS bank_name,
                                    (CASE WHEN tbl_cheque_details.branch_name IS NULL THEN 'N/A' ELSE tbl_cheque_details.branch_name END) AS branch_name";
                $sql =  " FROM tbl_transaction
                        INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_transaction.prop_dtl_id
                        INNER JOIN (SELECT STRING_AGG(owner_name, ', ') AS owner_name, STRING_AGG(mobile_no::TEXT, ', ') AS mobile_no, prop_dtl_id FROM tbl_prop_owner_detail GROUP BY prop_dtl_id) prop_owner_detail ON prop_owner_detail.prop_dtl_id=tbl_transaction.prop_dtl_id
                        LEFT JOIN view_emp_details ON view_emp_details.id=tbl_transaction.tran_by_emp_details_id
                        INNER JOIN view_ward_mstr ON view_ward_mstr.id=tbl_prop_dtl.ward_mstr_id
                        LEFT JOIN (
                            SELECT a.* from tbl_cheque_details a
                            JOIN (SELECT max(id) as max_id,transaction_id from tbl_cheque_details group by transaction_id) b on b.max_id=a.id
                        ) tbl_cheque_details ON tbl_cheque_details.transaction_id=tbl_transaction.id 
                        WHERE tbl_transaction.tran_type='Property' AND tbl_transaction.status in (1, 2) ".$whereQuery;

            $fetchSql = $selectStatement.$sql;
            $records = $this->model_datatable->getRecords($fetchSql);
            
            //phpOfficeLoad();
            $spreadsheet = new Spreadsheet();
            
            $activeSheet = $spreadsheet->getActiveSheet();
                            $activeSheet->setCellValue('A1', 'Ward No');
                            $activeSheet->setCellValue('B1', 'Holding No');
                            $activeSheet->setCellValue('C1', 'Unnique Holding No');
                            $activeSheet->setCellValue('D1', 'Owner Name');
                            $activeSheet->setCellValue('E1', 'Mobile No.');
                            $activeSheet->setCellValue('F1', 'From/Upto');
                            $activeSheet->setCellValue('G1', 'Tran. Date');
                            $activeSheet->setCellValue('H1', 'Mode');
                            $activeSheet->setCellValue('I1', 'Amount');
                            $activeSheet->setCellValue('J1', 'Tax Collector');
                            $activeSheet->setCellValue('K1', 'Tran. No.');
                            $activeSheet->setCellValue('L1', 'Check/DD No');
                            $activeSheet->setCellValue('M1', 'Bank');
                            $activeSheet->setCellValue('N1', 'Branch');
                            $activeSheet->fromArray($records, NULL, 'A2');
            $spreadsheet->getActiveSheet()->getStyle('B')->getNumberFormat()
                        ->setFormatCode(NumberFormat::FORMAT_TEXT);
            $filename = "counter_report_".date('Ymd-hisa').".xlsx";
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="'.$filename.'"');
            header('Cache-Control: max-age=0');
            $writer = new Xlsx($spreadsheet);
            //$writer->save(APPPATH.'/hello world.xlsx');
            ob_end_clean();
            $writer->save('php://output');
        }catch(Exception $e){
            print_r($e);
        }
    }

    public function collectionReportExtra() {
        $ulb_mstr_id = 1;
        $wardList = $this->model_ward_mstr->getWardList(['ulb_mstr_id'=>$ulb_mstr_id]);
        //$wardList = $this->model_ward_mstr->getWardListWithSession(['ulb_mstr_id'=>$ulb_mstr_id], $session);
        $empDtlList = $this->model_view_emp_details->getEmpListByAlTaxCollector(['ulb_mstr_id'=>$ulb_mstr_id]);
        $data['wardList'] = $wardList;
        $data['empDtlList'] = $empDtlList;
		$data['propertytypeList'] = $this->model_prop_type_mstr->getPropTypeList();
        return view('property/reports/collection_report_extra', $data);
    }
	
	public function collectionReportAjaxExtra_bk() {
        if($this->request->getMethod()=='post'){
			try{
                ## Read value
                $start = sanitizeString($this->request->getVar('start'));
                
                $rowperpage = sanitizeString($this->request->getVar('length')); // Rows display per page

                $columnIndex = sanitizeString($this->request->getVar('order')[0]['column']); // Column index
                $columnName = sanitizeString($this->request->getVar('columns')[$columnIndex]['data']); // Column name
                if ($columnName=="s_no" || $columnName=="tran_date")
                    $columnName = 'tbl_transaction.tran_date';
                if ($columnName=="holding_no")
                    $columnName = 'tbl_prop_dtl.holding_no';
                if ($columnName=="ward_no")
                    $columnName = 'view_ward_mstr.ward_no';
                if ($columnName=="owner_name")
                    $columnName = 'prop_owner_detail.owner_name';
                if ($columnName=="mobile_no")
                    $columnName = 'prop_owner_detail.mobile_no';
                if ($columnName=="from_upto_fy_qtr")
                    $columnName = 'tbl_transaction.tran_date';
                if ($columnName=="tran_no")
                    $columnName = 'tbl_transaction.tran_no';
                if ($columnName=="transaction_mode")
                    $columnName = 'tbl_transaction.transaction_mode';
                if ($columnName=="payable_amt")
                    $columnName = 'tbl_transaction.payable_amt';

                //$columnName = "tbl_transaction.tran_date";
                $columnSortOrder = sanitizeString($this->request->getVar('order')[0]['dir']); // asc or desc
                $searchValue = sanitizeString($this->request->getVar('search')['value']); // Search value
                
                // Date filter
                $search_from_date = sanitizeString($this->request->getVar('search_from_date'));
                $search_upto_date = sanitizeString($this->request->getVar('search_upto_date'));
                $search_ward_mstr_id = sanitizeString($this->request->getVar('search_ward_mstr_id'));
				$search_property_type_id = sanitizeString($this->request->getVar('search_property_type_id'));
                $search_tran_mode_mstr_id = sanitizeString($this->request->getVar('search_tran_mode_mstr_id'));
                $searchQuery = "";
                $whereQuery = "";
                
                $orderBY = " ORDER BY ".$columnName." ".$columnSortOrder;
                $limit = " LIMIT ".$rowperpage." OFFSET ".$start;
                
                $whereQuery .= " AND tbl_transaction.tran_date BETWEEN '".$search_from_date."' AND '".$search_upto_date."'";
                if ($search_ward_mstr_id != '') {
                    $whereQuery .= " AND  tbl_prop_dtl.ward_mstr_id='".$search_ward_mstr_id."'";
                }
				if ($search_property_type_id != '') {
                    $whereQuery .= " AND  tbl_prop_dtl.prop_type_mstr_id='" . $search_property_type_id . "'";
                }
                if ($search_tran_mode_mstr_id != '') {
                    $whereQuery .= " AND  tbl_transaction.tran_mode='".$search_tran_mode_mstr_id."'";
                }
                $whereQueryWithSearch = "";
                if ($searchValue!='') {
                    $whereQueryWithSearch = " AND (prop_owner_detail.owner_name ILIKE '%".$searchValue."%'
                                    OR prop_owner_detail.mobile_no ILIKE '%".$searchValue."%'
                                    OR tbl_prop_dtl.holding_no ILIKE '%".$searchValue."%'
                                    OR tbl_transaction.tran_no ILIKE '%".$searchValue."%'
                                    OR tbl_transaction.payable_amt::TEXT ILIKE '%".$searchValue."%')";
                }
                $base_url = base_url();
                $selectStatement = "SELECT 
                                    ROW_NUMBER () OVER (ORDER BY ".$columnName." ".$columnSortOrder.") AS s_no,
                                    view_ward_mstr.ward_no,
                                    CONCAT('<a href=', chr(39), '".$base_url."/propdtl/full/', tbl_prop_dtl.id, '/backbtnhide', chr(39), ' target=', chr(39), '_blank', chr(39), '><b><u>', tbl_prop_dtl.holding_no, '</u></b></a>') AS holding_no,
                                    (CASE WHEN tbl_prop_dtl.new_holding_no='' OR tbl_prop_dtl.new_holding_no IS NULL THEN 'N/A' ELSE tbl_prop_dtl.new_holding_no END) AS new_holding_no,
                                    prop_owner_detail.owner_name,
                                    prop_owner_detail.mobile_no,
                                    CONCAT(tbl_transaction.from_fyear, '(', tbl_transaction.from_qtr, ')', ' / ', tbl_transaction.upto_fyear, '(', tbl_transaction.upto_qtr, ')') AS from_upto_fy_qtr,
                                    tbl_transaction.tran_date,
                                    tbl_transaction.tran_mode AS transaction_mode,
                                    tbl_transaction.payable_amt,
                                    tbl_transaction.tran_no,
                                    COALESCE(rebate_dtl.rebate_amt, 0) as rebate_amt,
                                    COALESCE(penalty_dtl.penalty_amt, 0) as penalty_amt";
                $sql =  " FROM tbl_transaction
                        INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_transaction.prop_dtl_id
                        INNER JOIN (SELECT STRING_AGG(owner_name, ', ') AS owner_name, STRING_AGG(mobile_no::TEXT, ', ') AS mobile_no, prop_dtl_id FROM tbl_prop_owner_detail GROUP BY prop_dtl_id) prop_owner_detail ON prop_owner_detail.prop_dtl_id=tbl_transaction.prop_dtl_id
                        INNER JOIN view_ward_mstr ON view_ward_mstr.id=tbl_prop_dtl.ward_mstr_id
                        LEFT JOIN (
                            SELECT
                                transaction_id,
                                SUM(amount) AS rebate_amt
                            from tbl_transaction_fine_rebet_details
                            where status=1 AND head_name in ('First Qtr Rebate', 'JSK (2.5%) Rebate', 'Rebate Amount', 'Rebate From JSK', 'Rebate From Jsk/Online Payment', 'Special Rebate', 'Online Rebate') 
                            GROUP BY transaction_id
                        ) AS rebate_dtl ON rebate_dtl.transaction_id=tbl_transaction.id
                        LEFT JOIN (
                            SELECT
                                transaction_id,
                                SUM(amount) AS penalty_amt
                            from tbl_transaction_fine_rebet_details
                            where status=1 AND head_name='1% Monthly Penalty'
                            GROUP BY transaction_id
                        ) AS penalty_dtl ON penalty_dtl.transaction_id=tbl_transaction.id
                        WHERE tbl_transaction.tran_type='Property' AND tbl_transaction.status in (1, 2)".$whereQuery;
                
                $selectSumStatement = "SELECT COALESCE(SUM(tbl_transaction.payable_amt), 0) AS total_payable_amt, COALESCE(SUM(rebate_dtl.rebate_amt), 0) AS total_rebate_amt, COALESCE(SUM(penalty_dtl.penalty_amt), 0) AS total_penalty_amt";
                //return json_encode($selectStatement.$sql.$whereQueryWithSearch.$orderBY.$limit);
                ## Total number of records without filtering
                
                $totalRecords = $this->model_datatable->getTotalRecords($sql);
                $total_collection = 0;
                if ($totalRecords>0) {
                    ## Total number of records with filtering
                    $totalRecordwithFilter = $this->model_datatable->getTotalRecordwithFilter($sql.$whereQueryWithSearch);
                    ## Fetch records
                    $fetchSql = $selectStatement.$sql.$whereQueryWithSearch.$orderBY.$limit;
                    $fetchSumSql = $selectSumStatement.$sql;
                   
                    $records = $this->model_datatable->getRecords($fetchSql);
                    $total_collection = $this->model_datatable->getSumRecords($fetchSumSql)['total_payable_amt'];
					$total_rebate = $this->model_datatable->getSumRecords($fetchSumSql)['total_rebate_amt'];
                    $total_penalty = $this->model_datatable->getSumRecords($fetchSumSql)['total_penalty_amt'];
                    //$records['totall'] = 1245;
                } else {
                    $totalRecordwithFilter = 0;
                    $records = [];
                }
                //echo $this->db->getLastQuery();
                $response = array(
                    "draw" => 0,
                    "total_collection" => $total_collection,
                    "recordsTotal" => $totalRecords,
                    "recordsFiltered" => $totalRecordwithFilter,
					"total_rebate" => $total_rebate,
                    "total_penalty" => $total_penalty,
                    "data" => $records,
                );
                return json_encode($response);
            }catch(Exception $e){

            }
        }
    }

    public function collectionReportAjaxExtra() {
        if($this->request->getMethod()=='post'){
			try{
                ## Read value
                $start = sanitizeString($this->request->getVar('start'));
                
                $rowperpage = sanitizeString($this->request->getVar('length')); // Rows display per page

                $columnIndex = sanitizeString($this->request->getVar('order')[0]['column']); // Column index
                $columnName = sanitizeString($this->request->getVar('columns')[$columnIndex]['data']); // Column name
                if ($columnName=="s_no" || $columnName=="tran_date")
                    $columnName = 'tbl_transaction.tran_date';
                if ($columnName=="holding_no")
                    $columnName = 'tbl_prop_dtl.holding_no';
                if ($columnName=="ward_no")
                    $columnName = 'view_ward_mstr.ward_no';
                if ($columnName=="owner_name")
                    $columnName = 'prop_owner_detail.owner_name';
                if ($columnName=="mobile_no")
                    $columnName = 'prop_owner_detail.mobile_no';
                if ($columnName=="from_upto_fy_qtr")
                    $columnName = 'tbl_transaction.tran_date';
                if ($columnName=="tran_no")
                    $columnName = 'tbl_transaction.tran_no';
                if ($columnName=="transaction_mode")
                    $columnName = 'tbl_transaction.transaction_mode';
                if ($columnName=="payable_amt")
                    $columnName = 'tbl_transaction.payable_amt';

                //$columnName = "tbl_transaction.tran_date";
                $columnSortOrder = sanitizeString($this->request->getVar('order')[0]['dir']); // asc or desc
                $searchValue = sanitizeString($this->request->getVar('search')['value']); // Search value
                
                // Date filter
                $search_from_date = sanitizeString($this->request->getVar('search_from_date'));
                $search_upto_date = sanitizeString($this->request->getVar('search_upto_date'));
                $search_ward_mstr_id = sanitizeString($this->request->getVar('search_ward_mstr_id'));
				$search_property_type_id = sanitizeString($this->request->getVar('search_property_type_id'));
                $search_tran_mode_mstr_id = sanitizeString($this->request->getVar('search_tran_mode_mstr_id'));
                $searchQuery = "";
                $whereQuery = "";
                $wardQuery = "";

                $orderBY = " ORDER BY ".$columnName." ".$columnSortOrder;
                $limit = " LIMIT ".$rowperpage." OFFSET ".$start;
                
                $whereQuery .= " AND tbl_transaction.tran_date BETWEEN '".$search_from_date."' AND '".$search_upto_date."'";
                if ($search_ward_mstr_id != '') {
                    $whereQuery .= " AND  tbl_prop_dtl.ward_mstr_id='".$search_ward_mstr_id."'";
                    $wardQuery .= " AND  tbl_prop_demand.ward_mstr_id='".$search_ward_mstr_id."'";
                }
				if ($search_property_type_id != '') {
                    $whereQuery .= " AND  tbl_prop_dtl.prop_type_mstr_id='" . $search_property_type_id . "'";
                }
                if ($search_tran_mode_mstr_id != '') {
                    $whereQuery .= " AND  tbl_transaction.tran_mode='".$search_tran_mode_mstr_id."'";
                }
                $whereQueryWithSearch = "";
                if ($searchValue!='') {
                    $whereQueryWithSearch = " AND (prop_owner_detail.owner_name ILIKE '%".$searchValue."%'
                                    OR prop_owner_detail.mobile_no ILIKE '%".$searchValue."%'
                                    OR tbl_prop_dtl.holding_no ILIKE '%".$searchValue."%'
                                    OR tbl_transaction.tran_no ILIKE '%".$searchValue."%'
                                    OR tbl_transaction.payable_amt::TEXT ILIKE '%".$searchValue."%')";
                }
                $base_url = base_url();
                $selectStatement = "SELECT 
                                    ROW_NUMBER () OVER (ORDER BY ".$columnName." ".$columnSortOrder.") AS s_no,
                                    view_ward_mstr.ward_no,
                                    CONCAT('<a href=', chr(39), '".$base_url."/propdtl/full/', tbl_prop_dtl.id, '/backbtnhide', chr(39), ' target=', chr(39), '_blank', chr(39), '><b><u>', tbl_prop_dtl.holding_no, '</u></b></a>') AS holding_no,
                                    (CASE WHEN tbl_prop_dtl.new_holding_no='' OR tbl_prop_dtl.new_holding_no IS NULL THEN 'N/A' ELSE tbl_prop_dtl.new_holding_no END) AS new_holding_no,
                                    prop_owner_detail.owner_name,
                                    prop_owner_detail.mobile_no,
                                    CONCAT(tbl_transaction.from_fyear, '(', tbl_transaction.from_qtr, ')', ' / ', tbl_transaction.upto_fyear, '(', tbl_transaction.upto_qtr, ')') AS from_upto_fy_qtr,
                                    tbl_transaction.tran_date,
                                    tbl_transaction.tran_mode AS transaction_mode,
                                    tbl_transaction.demand_amt,
                                     (select SUM(tbl_prop_demand.amount-tbl_prop_demand.adjust_amt) from tbl_prop_demand 
                                     where tbl_prop_demand.prop_dtl_id=tbl_transaction.prop_dtl_id and status=1 and paid_status=1 and
                                     tbl_prop_demand.fy_mstr_id = tbl_transaction.upto_fy_mstr_id
                                    ) as demandamt,
                                     
                                     tbl_transaction.discount_amt,
                                    tbl_transaction.payable_amt,
                                    tbl_advance_mstr.amount AS advance_amt,
                                    tbl_adjustment_mstr.amount AS adjustment_amt,
                                    tbl_transaction.tran_no,
                                    (select SUM(tbl_collection.amount) from tbl_collection 
                                     where tbl_collection.transaction_id=tbl_transaction.id and status=1 and collection_type='Property' and
                                     tbl_collection.created_on::date between '".$search_from_date."' and '".$search_upto_date."' and 
                                     tbl_collection.fy_mstr_id = tbl_transaction.upto_fy_mstr_id
                                    ) as collect_amt,
                                    (select SUM(tbl_collection.amount) from tbl_collection 
                                     where tbl_collection.transaction_id=tbl_transaction.id and status=1 and collection_type='Property' and
                                     tbl_collection.created_on::date between '".$search_from_date."' and '".$search_upto_date."' and 
                                     tbl_collection.fy_mstr_id < tbl_transaction.upto_fy_mstr_id
                                    ) as arrear_amt,
                                    COALESCE(rebate_dtl.rebate_amt, 0) as rebate_amt,
                                    COALESCE(penalty_dtl.penalty_amt, 0) as penalty_amt";
                // --(select SUM(tbl_collection.holding_tax + tbl_collection.additional_tax) from tbl_collection
                $sql =  " FROM tbl_transaction
                        INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_transaction.prop_dtl_id
                        INNER JOIN (SELECT STRING_AGG(owner_name, ', ') AS owner_name, STRING_AGG(mobile_no::TEXT, ', ') AS mobile_no, prop_dtl_id FROM tbl_prop_owner_detail GROUP BY prop_dtl_id) prop_owner_detail ON prop_owner_detail.prop_dtl_id=tbl_transaction.prop_dtl_id
                        INNER JOIN view_ward_mstr ON view_ward_mstr.id=tbl_prop_dtl.ward_mstr_id
                       	LEFT JOIN tbl_advance_mstr ON tbl_advance_mstr.transaction_id=tbl_transaction.id and tbl_advance_mstr.status=1
                       	LEFT JOIN tbl_adjustment_mstr ON tbl_adjustment_mstr.transaction_id=tbl_transaction.id and tbl_adjustment_mstr.status=1
                         LEFT JOIN (
                            SELECT
                                transaction_id,
                                SUM(amount) AS rebate_amt
                            from tbl_transaction_fine_rebet_details
                            where status=1 AND head_name in ('First Qtr Rebate', 'JSK (2.5%) Rebate', 'Rebate Amount', 'Rebate From JSK', 'Rebate From Jsk/Online Payment', 'Special Rebate', 'Online Rebate') 
                            GROUP BY transaction_id
                        ) AS rebate_dtl ON rebate_dtl.transaction_id=tbl_transaction.id
                        LEFT JOIN (
                            SELECT
                                transaction_id,
                                SUM(amount) AS penalty_amt
                            from tbl_transaction_fine_rebet_details
                            where status=1 AND head_name='1% Monthly Penalty'
                            GROUP BY transaction_id
                        ) AS penalty_dtl ON penalty_dtl.transaction_id=tbl_transaction.id
                        WHERE tbl_transaction.tran_type='Property' AND tbl_transaction.status in (1, 2)".$whereQuery;
                
                $selectSumStatement = "SELECT COALESCE(SUM(tbl_transaction.payable_amt), 0) AS total_payable_amt, COALESCE(SUM(rebate_dtl.rebate_amt), 0) AS total_rebate_amt, 
                COALESCE(SUM(penalty_dtl.penalty_amt), 0) AS total_penalty_amt";
                //return json_encode($selectStatement.$sql.$whereQueryWithSearch.$orderBY.$limit);
                ## Total number of records without filtering
                
                $totalRecords = $this->model_datatable->getTotalRecords($sql);
                $total_collection = 0;
                if ($totalRecords>0) {
                    ## Total number of records with filtering
                    $totalRecordwithFilter = $this->model_datatable->getTotalRecordwithFilter($sql.$whereQueryWithSearch);
                    ## Fetch records
                    $fetchSql = $selectStatement.$sql.$whereQueryWithSearch.$orderBY.$limit;
                    $fetchSqlsummary = $selectStatement.$sql;
                    $fetchSumSql = $selectSumStatement.$sql;
                   
                    $records = $this->model_datatable->getRecords($fetchSql);
                    $total_collection = $this->model_datatable->getSumRecords($fetchSumSql)['total_payable_amt'];
					$total_rebate = $this->model_datatable->getSumRecords($fetchSumSql)['total_rebate_amt'];
                    $total_penalty = $this->model_datatable->getSumRecords($fetchSumSql)['total_penalty_amt'];
                    // $total_arrear = $this->model_datatable->getSumRecords($fetchSumSql)['total_arrear_amt'];
                    //$records['totall'] = 1245;
                } else {
                    $totalRecordwithFilter = 0;
                    $records = [];
                }
                $currentFY = getFY();
                $currentFyID = $this->model_fy_mstr->getFyByFy(['fy'=>$currentFY])['id'];
                $current_demandsql="SELECT 
                            count(tbl_prop_dtl.id) AS current_holding,
                            SUM(COALESCE(tbl_prop_demand.amount, 0) - COALESCE(tbl_prop_demand.adjust_amt, 0)) AS current_demand
                        FROM tbl_prop_dtl
                        RIGHT JOIN (
                            select prop_dtl_id, sum(amount) as amount, sum(adjust_amt) as adjust_amt from tbl_prop_demand where tbl_prop_demand.status=1 
                            AND tbl_prop_demand.paid_status IN (0,1) and tbl_prop_demand.due_date is not null ".$wardQuery." and fy_mstr_id='".$currentFyID."'
                            group by prop_dtl_id
                        ) tbl_prop_demand ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id
                        WHERE tbl_prop_dtl.status=1 and char_length(tbl_prop_dtl.new_holding_no)>0  and (tbl_prop_dtl.govt_saf_dtl_id is null or tbl_prop_dtl.govt_saf_dtl_id=0)";
                $current_demand=$this->db->query($current_demandsql);
                $current_demand=$current_demand->getResultArray()[0]['current_demand'];
                $collect_demandactual=$total_collection;


                $arrear_collection=$this->db->query($fetchSqlsummary);
                // print_r($arrear_collection);
                $data_=$arrear_collection->getResultArray();
                // print_r($data_);
                $arrear_collection=array_sum(array_column($data_,'arrear_amt'));
                //echo $this->db->getLastQuery();
                //$collect_demandactual-$arrear_collection
                $response = array(
                    "draw" => 0,
                    "total_collection" => $total_collection,
                    "amount_collection" => round($total_collection-$arrear_collection,2),
                    "recordsTotal" => $totalRecords,
                    "recordsFiltered" => $totalRecordwithFilter,
					"total_rebate" => $total_rebate,
                    "total_penalty" => $total_penalty,
                    "data" => $records,
                    "current_demand" => $current_demand,
                    "advance" => 0,
                    "arrear_demand" => 121211462.03,
                    "arrear_collection" => round($arrear_collection,2)??0
                );
                return json_encode($response);
            }catch(Exception $e){

            }
        }
    }
    public function collectionReportExcelExtra($search_from_date = null, $search_upto_date = null, $search_ward_mstr_id = null, $search_tran_mode_mstr_id = null)
    {
        try
        {
            $whereQuery = " AND tbl_transaction.tran_date BETWEEN '".$search_from_date."' AND '".$search_upto_date."'";
                if ($search_ward_mstr_id != 'ALL') {
                    $whereQuery .= " AND  tbl_prop_dtl.ward_mstr_id='".$search_ward_mstr_id."'";
                }
                if ($search_tran_mode_mstr_id != 'ALL') {
                    $whereQuery .= " AND  tbl_transaction.tran_mode='".$search_tran_mode_mstr_id."'";
                }
                
                $selectStatement = "SELECT
                                    view_ward_mstr.ward_no,
                                    tbl_prop_dtl.holding_no,
                                    prop_owner_detail.owner_name,
                                    prop_owner_detail.mobile_no,
                                    CONCAT(tbl_transaction.from_fyear, '(', tbl_transaction.from_qtr, ')', ' / ', tbl_transaction.upto_fyear, '(', tbl_transaction.upto_qtr, ')') AS from_upto_fy_qtr,
                                    tbl_transaction.tran_date,
                                    tbl_transaction.tran_mode AS transaction_mode,
                                     COALESCE(tbl_transaction.payable_amt, 0) AS payable_amt
                                     --tbl_transaction.demand_amt
                                     -- (select SUM(tbl_collection.amount) from tbl_collection 
                                    -- where tbl_collection.transaction_id=tbl_transaction.id and status=1 and collection_type='Property' and
                                    -- tbl_collection.created_on::date between '".$search_from_date."' and '".$search_upto_date."' and 
                                    -- tbl_collection.fy_mstr_id < tbl_transaction.upto_fy_mstr_id
                                   -- ) as arrear_amt,
                                    --tbl_adjustment_mstr.amount AS adjustment_amt,
                                 --   COALESCE(rebate_dtl.rebate_amt, 0) as rebate_amt,
                                   --COALESCE(penalty_dtl.penalty_amt, 0) as penalty_amt
                                   ";
                $sql =  " FROM tbl_transaction
                        INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_transaction.prop_dtl_id
                        INNER JOIN (SELECT STRING_AGG(owner_name, ', ') AS owner_name, STRING_AGG(mobile_no::TEXT, ', ') AS mobile_no, prop_dtl_id FROM tbl_prop_owner_detail GROUP BY prop_dtl_id) prop_owner_detail ON prop_owner_detail.prop_dtl_id=tbl_transaction.prop_dtl_id
                        INNER JOIN view_ward_mstr ON view_ward_mstr.id=tbl_prop_dtl.ward_mstr_id
                        LEFT JOIN tbl_advance_mstr ON tbl_advance_mstr.transaction_id=tbl_transaction.id and tbl_advance_mstr.status=1
                        LEFT JOIN tbl_adjustment_mstr ON tbl_adjustment_mstr.transaction_id=tbl_transaction.id and tbl_adjustment_mstr.status=1
        LEFT JOIN (
                            SELECT
                                transaction_id,
                                SUM(amount) AS rebate_amt
                            from tbl_transaction_fine_rebet_details
                            where status=1 AND head_name in ('First Qtr Rebate', 'JSK (2.5%) Rebate', 'Rebate Amount', 'Rebate From JSK', 'Rebate From Jsk/Online Payment', 'Special Rebate', 'Online Rebate') 
                            GROUP BY transaction_id
                        ) AS rebate_dtl ON rebate_dtl.transaction_id=tbl_transaction.id
                        LEFT JOIN (
                            SELECT
                                transaction_id,
                                SUM(amount) AS penalty_amt
                            from tbl_transaction_fine_rebet_details
                            where status=1 AND head_name='1% Monthly Penalty'
                            GROUP BY transaction_id
                        ) AS penalty_dtl ON penalty_dtl.transaction_id=tbl_transaction.id
                        WHERE tbl_transaction.tran_type='Property' AND tbl_transaction.status in (1, 2) ".$whereQuery;

            $fetchSql = $selectStatement.$sql;
            $records = $this->model_datatable->getRecords($fetchSql);
            
            //phpOfficeLoad();
            $spreadsheet = new Spreadsheet();
            
            $activeSheet = $spreadsheet->getActiveSheet();
                            $activeSheet->setCellValue('A1', 'Ward No');
                            $activeSheet->setCellValue('B1', 'Holding No');
                            $activeSheet->setCellValue('C1', 'Owner Name');
                            $activeSheet->setCellValue('D1', 'Mobile No.');
                            $activeSheet->setCellValue('E1', 'From/Upto');
                            $activeSheet->setCellValue('F1', 'Tran. Date');
                            $activeSheet->setCellValue('G1', 'Mode');
                            $activeSheet->setCellValue('H1', 'Paid Amount');
                          //  $activeSheet->setCellValue('I1', 'Current Amount');
                            // $activeSheet->setCellValue('J1', 'Arrear Amount');
                            // $activeSheet->setCellValue('K1', 'Adjust Amount');
                            // $activeSheet->setCellValue('L1', 'Rebate Amount');
                            // $activeSheet->setCellValue('M1', 'Penalty Amount');
                            $activeSheet->fromArray($records, NULL, 'A2');
            $spreadsheet->getActiveSheet()->getStyle('B')->getNumberFormat()
                        ->setFormatCode(NumberFormat::FORMAT_TEXT);
            $filename = "counter_report_extra_".date('Ymd-hisa').".xlsx";
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="'.$filename.'"');
            header('Cache-Control: max-age=0');
            $writer = new Xlsx($spreadsheet);
            //$writer->save(APPPATH.'/hello world.xlsx');
            ob_end_clean();
            $writer->save('php://output');
        }catch(Exception $e){
            print_r($e);
        }
    }

    public function collectionReportExcelExtra_($search_from_date = null, $search_upto_date = null, $search_ward_mstr_id = null, $search_tran_mode_mstr_id = null)
    {
        try
        {
            $whereQuery = " AND tbl_transaction.tran_date BETWEEN '".$search_from_date."' AND '".$search_upto_date."'";
                if ($search_ward_mstr_id != 'ALL') {
                    $whereQuery .= " AND  tbl_prop_dtl.ward_mstr_id='".$search_ward_mstr_id."'";
                }
                if ($search_tran_mode_mstr_id != 'ALL') {
                    $whereQuery .= " AND  tbl_transaction.tran_mode='".$search_tran_mode_mstr_id."'";
                }
                
                $selectStatement = "SELECT
                                    view_ward_mstr.ward_no,
                                    tbl_prop_dtl.holding_no,
                                    prop_owner_detail.owner_name,
                                    prop_owner_detail.mobile_no,
                                    CONCAT(tbl_transaction.from_fyear, '(', tbl_transaction.from_qtr, ')', ' / ', tbl_transaction.upto_fyear, '(', tbl_transaction.upto_qtr, ')') AS from_upto_fy_qtr,
                                    tbl_transaction.tran_date,
                                    tbl_transaction.tran_mode AS transaction_mode,
                                    COALESCE(tbl_transaction.payable_amt, 0) AS payable_amt,
                                    tbl_transaction.tran_no,
                                    COALESCE(rebate_dtl.rebate_amt, 0) as rebate_amt,
                                    COALESCE(penalty_dtl.penalty_amt, 0) as penalty_amt";
                $sql =  " FROM tbl_transaction
                        INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_transaction.prop_dtl_id
                        INNER JOIN (SELECT STRING_AGG(owner_name, ', ') AS owner_name, STRING_AGG(mobile_no::TEXT, ', ') AS mobile_no, prop_dtl_id FROM tbl_prop_owner_detail GROUP BY prop_dtl_id) prop_owner_detail ON prop_owner_detail.prop_dtl_id=tbl_transaction.prop_dtl_id
                        INNER JOIN view_ward_mstr ON view_ward_mstr.id=tbl_prop_dtl.ward_mstr_id
                        LEFT JOIN (
                            SELECT
                                transaction_id,
                                SUM(amount) AS rebate_amt
                            from tbl_transaction_fine_rebet_details
                            where status=1 AND head_name in ('First Qtr Rebate', 'JSK (2.5%) Rebate', 'Rebate Amount', 'Rebate From JSK', 'Rebate From Jsk/Online Payment', 'Special Rebate', 'Online Rebate') 
                            GROUP BY transaction_id
                        ) AS rebate_dtl ON rebate_dtl.transaction_id=tbl_transaction.id
                        LEFT JOIN (
                            SELECT
                                transaction_id,
                                SUM(amount) AS penalty_amt
                            from tbl_transaction_fine_rebet_details
                            where status=1 AND head_name='1% Monthly Penalty'
                            GROUP BY transaction_id
                        ) AS penalty_dtl ON penalty_dtl.transaction_id=tbl_transaction.id
                        WHERE tbl_transaction.tran_type='Property' AND tbl_transaction.status in (1, 2) ".$whereQuery;

            $fetchSql = $selectStatement.$sql;
            $records = $this->model_datatable->getRecords($fetchSql);
            
            //phpOfficeLoad();
            $spreadsheet = new Spreadsheet();
            
            $activeSheet = $spreadsheet->getActiveSheet();
                            $activeSheet->setCellValue('A1', 'Ward No');
                            $activeSheet->setCellValue('B1', 'Holding No');
                            $activeSheet->setCellValue('C1', 'Owner Name');
                            $activeSheet->setCellValue('D1', 'Mobile No.');
                            $activeSheet->setCellValue('E1', 'From/Upto');
                            $activeSheet->setCellValue('F1', 'Tran. Date');
                            $activeSheet->setCellValue('G1', 'Mode');
                            $activeSheet->setCellValue('H1', 'Amount');
                            $activeSheet->setCellValue('I1', 'Tran. No.');
                            $activeSheet->setCellValue('J1', 'Rebate Amount');
                            $activeSheet->setCellValue('K1', 'Penalty Amount');
                            $activeSheet->fromArray($records, NULL, 'A2');
            $spreadsheet->getActiveSheet()->getStyle('B')->getNumberFormat()
                        ->setFormatCode(NumberFormat::FORMAT_TEXT);
            $filename = "counter_report_extra_".date('Ymd-hisa').".xlsx";
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="'.$filename.'"');
            header('Cache-Control: max-age=0');
            $writer = new Xlsx($spreadsheet);
            //$writer->save(APPPATH.'/hello world.xlsx');
            ob_end_clean();
            $writer->save('php://output');
        }catch(Exception $e){
            print_r($e);
        }
    }

    public function counterReport() {
        $ulb_mstr_id = 1;
        $wardList = $this->model_ward_mstr->getWardList(['ulb_mstr_id'=>$ulb_mstr_id]);
        $empDtlList = $this->model_view_emp_details->getEmpListByAlTaxCollector(['ulb_mstr_id'=>$ulb_mstr_id]);
        $data['wardList'] = $wardList;
        $data['empDtlList'] = $empDtlList;
        return view('property/reports/counter_report', $data);
    }

	public function counterReportAjax() {
        if($this->request->getMethod()=='post'){
			try{
                ## Read value
                $start = sanitizeString($this->request->getVar('start'));
                
                $rowperpage = sanitizeString($this->request->getVar('length')); // Rows display per page

                $columnIndex = sanitizeString($this->request->getVar('order')[0]['column']); // Column index
                $columnName = sanitizeString($this->request->getVar('columns')[$columnIndex]['data']); // Column name
                if ($columnName=="s_no" || $columnName=="tran_date")
                    $columnName = 'tbl_transaction.tran_date';
                if ($columnName=="holding_no")
                    $columnName = 'tbl_prop_dtl.holding_no';
                if ($columnName=="ward_no")
                    $columnName = 'view_ward_mstr.ward_no';
                if ($columnName=="owner_name")
                    $columnName = 'prop_owner_detail.owner_name';
                if ($columnName=="mobile_no")
                    $columnName = 'prop_owner_detail.mobile_no';
                if ($columnName=="from_fy_qtr")
                    $columnName = 'from_fy_mstr.fy';
                if ($columnName=="upto_fy_qtr")
                    $columnName = 'upto_fy_mstr.fy';
                if ($columnName=="tran_no")
                    $columnName = 'tbl_transaction.tran_no';
                if ($columnName=="transaction_mode")
                    $columnName = 'tbl_transaction.transaction_mode';
                if ($columnName=="cheque_no")
                    $columnName = 'tbl_cheque_details.cheque_no';
                if ($columnName=="bank_name")
                    $columnName = 'tbl_cheque_details.bank_name';
                if ($columnName=="branch_name")
                    $columnName = 'tbl_cheque_details.branch_name';
                if ($columnName=="payable_amt")
                    $columnName = 'tbl_transaction.payable_amt';
                if ($columnName=="emp_name")
                    $columnName = 'view_emp_details.emp_name';

                //$columnName = "tbl_transaction.tran_date";
                $columnSortOrder = sanitizeString($this->request->getVar('order')[0]['dir']); // asc or desc
                $searchValue = sanitizeString($this->request->getVar('search')['value']); // Search value
                
                
                
                // Date filter
                $search_from_date = sanitizeString($this->request->getVar('search_from_date'));
                $search_upto_date = sanitizeString($this->request->getVar('search_upto_date'));
                $search_ward_mstr_id = sanitizeString($this->request->getVar('search_ward_mstr_id'));
                $search_collector_id = sanitizeString($this->request->getVar('search_collector_id'));
                $searchQuery = "";
                $whereQuery = "";
                
                $orderBY = " ORDER BY ".$columnName." ".$columnSortOrder;
                $limit = " LIMIT ".$rowperpage." OFFSET ".$start;
                
                $whereQuery .= " AND tbl_transaction.tran_date BETWEEN '".$search_from_date."' AND '".$search_upto_date."'";
                if ($search_ward_mstr_id != '') {
                    $whereQuery .= " AND  tbl_prop_dtl.ward_mstr_id='".$search_ward_mstr_id."'";
                }
                if ($search_collector_id != '') {
                    $whereQuery .= " AND  tbl_transaction.tran_by_emp_details_id='".$search_collector_id."'";
                }
                $whereQueryWithSearch = "";
                if ($searchValue!='') {
                    $whereQueryWithSearch = " AND (prop_owner_detail.owner_name ILIKE '%".$searchValue."%'
                                    OR prop_owner_detail.mobile_no ILIKE '%".$searchValue."%'
                                    OR tbl_prop_dtl.holding_no ILIKE '%".$searchValue."%'
                                    OR tbl_transaction.tran_no ILIKE '%".$searchValue."%'
                                    OR tbl_transaction.payable_amt::TEXT ILIKE '%".$searchValue."%')";
                }
                
                $selectStatement = "SELECT 
                                    ROW_NUMBER () OVER (ORDER BY ".$columnName.") AS s_no,
                                    tbl_prop_dtl.holding_no,
                                    view_ward_mstr.ward_no,
                                    prop_owner_detail.owner_name,
                                    prop_owner_detail.mobile_no,
                                    CONCAT(from_fy_mstr.fy, '/', tbl_transaction.from_qtr) AS from_fy_qtr,
                                    CONCAT(upto_fy_mstr.fy, '/', tbl_transaction.upto_qtr) AS upto_fy_qtr,
                                    tbl_transaction.tran_date,
                                    tbl_transaction.tran_no,
                                    tbl_tran_mode_mstr.transaction_mode,
                                    (CASE WHEN tbl_cheque_details.cheque_no IS NULL THEN 'N/A' ELSE tbl_cheque_details.cheque_no END) AS cheque_no,
                                    (CASE WHEN tbl_cheque_details.bank_name IS NULL THEN 'N/A' ELSE tbl_cheque_details.bank_name END) AS bank_name,
                                    (CASE WHEN tbl_cheque_details.branch_name IS NULL THEN 'N/A' ELSE tbl_cheque_details.branch_name END) AS branch_name,
                                    tbl_transaction.payable_amt,
                                    view_emp_details.emp_name";
                $sql =  " FROM tbl_transaction
                        INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_transaction.prop_dtl_id
                        INNER JOIN (SELECT STRING_AGG(owner_name, ', ') AS owner_name, STRING_AGG(mobile_no::TEXT, ', ') AS mobile_no, prop_dtl_id FROM tbl_prop_owner_detail GROUP BY prop_dtl_id) prop_owner_detail ON prop_owner_detail.prop_dtl_id=tbl_transaction.prop_dtl_id
                        INNER JOIN tbl_tran_mode_mstr ON tbl_tran_mode_mstr.id=tbl_transaction.tran_mode_mstr_id
                        INNER JOIN view_fy_mstr AS from_fy_mstr ON from_fy_mstr.id=tbl_transaction.from_fy_mstr_id
                        INNER JOIN view_fy_mstr AS upto_fy_mstr ON upto_fy_mstr.id=tbl_transaction.from_fy_mstr_id
                        INNER JOIN view_emp_details ON view_emp_details.id=tbl_transaction.tran_by_emp_details_id
                        INNER JOIN view_ward_mstr ON view_ward_mstr.id=tbl_prop_dtl.ward_mstr_id
                        LEFT JOIN tbl_cheque_details ON tbl_cheque_details.transaction_id=tbl_transaction.id
                        WHERE tbl_transaction.tran_type='Property'".$whereQuery;
                
                //return json_encode($selectStatement.$sql.$whereQueryWithSearch.$orderBY.$limit);
                ## Total number of records without filtering
                $totalRecords = $this->model_datatable->getTotalRecords($sql);
                if ($totalRecords>0) {
                    ## Total number of records with filtering
                    $totalRecordwithFilter = $this->model_datatable->getTotalRecordwithFilter($sql.$whereQueryWithSearch);
                    ## Fetch records
                    $fetchSql = $selectStatement.$sql.$whereQueryWithSearch.$orderBY.$limit;
                    $records = $this->model_datatable->getRecords($fetchSql);
                } else {
                    $totalRecordwithFilter = 0;
                    $records = [];
                }
                //echo $this->db->getLastQuery();
                $response = array(
                    "iTotalRecords" => $totalRecords,
                    "iTotalDisplayRecords" => $totalRecordwithFilter,
                    "aaData" => $records
                );
                return json_encode($response);
            }catch(Exception $e){

            }
        }
    }

    public function counterReportExcel($search_from_date = null, $search_upto_date = null, $search_ward_mstr_id = null, $search_collector_id = null) {
        try{
            $whereQuery = " AND tbl_transaction.tran_date BETWEEN '".$search_from_date."' AND '".$search_upto_date."'";
                if ($search_ward_mstr_id != 'ALL') {
                    $whereQuery .= " AND  tbl_prop_dtl.ward_mstr_id='".$search_ward_mstr_id."'";
                }
                if ($search_collector_id != 'ALL') {
                    $whereQuery .= " AND  tbl_transaction.tran_by_emp_details_id='".$search_collector_id."'";
                }
                
                $selectStatement = "SELECT
                                    tbl_prop_dtl.holding_no,
                                    view_ward_mstr.ward_no,
                                    prop_owner_detail.owner_name,
                                    prop_owner_detail.mobile_no,
                                    CONCAT(from_fy_mstr.fy, '/', tbl_transaction.from_qtr) AS from_fy_qtr,
                                    CONCAT(upto_fy_mstr.fy, '/', tbl_transaction.upto_qtr) AS upto_fy_qtr,
                                    tbl_transaction.tran_date,
                                    tbl_transaction.tran_no,
                                    tbl_tran_mode_mstr.transaction_mode,
                                    (CASE WHEN tbl_cheque_details.cheque_no IS NULL THEN 'N/A' ELSE tbl_cheque_details.cheque_no END) AS cheque_no,
                                    (CASE WHEN tbl_cheque_details.bank_name IS NULL THEN 'N/A' ELSE tbl_cheque_details.bank_name END) AS bank_name,
                                    (CASE WHEN tbl_cheque_details.branch_name IS NULL THEN 'N/A' ELSE tbl_cheque_details.branch_name END) AS branch_name,
                                    tbl_transaction.payable_amt,
                                    view_emp_details.emp_name";
                $sql =  " FROM tbl_transaction
                        INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_transaction.prop_dtl_id
                        INNER JOIN (SELECT STRING_AGG(owner_name, ', ') AS owner_name, STRING_AGG(mobile_no::TEXT, ', ') AS mobile_no, prop_dtl_id FROM tbl_prop_owner_detail GROUP BY prop_dtl_id) prop_owner_detail ON prop_owner_detail.prop_dtl_id=tbl_transaction.prop_dtl_id
                        INNER JOIN tbl_tran_mode_mstr ON tbl_tran_mode_mstr.id=tbl_transaction.tran_mode_mstr_id
                        INNER JOIN view_fy_mstr AS from_fy_mstr ON from_fy_mstr.id=tbl_transaction.from_fy_mstr_id
                        INNER JOIN view_fy_mstr AS upto_fy_mstr ON upto_fy_mstr.id=tbl_transaction.from_fy_mstr_id
                        INNER JOIN view_emp_details ON view_emp_details.id=tbl_transaction.tran_by_emp_details_id
                        INNER JOIN view_ward_mstr ON view_ward_mstr.id=tbl_prop_dtl.ward_mstr_id
                        LEFT JOIN tbl_cheque_details ON tbl_cheque_details.transaction_id=tbl_transaction.id
                        WHERE tbl_transaction.tran_type='Property'".$whereQuery;

            $fetchSql = $selectStatement.$sql;
            $records = $this->model_datatable->getRecords($fetchSql);
            
            phpOfficeLoad();
            $spreadsheet = new Spreadsheet();
            $activeSheet = $spreadsheet->getActiveSheet();
                            $activeSheet->setCellValue('A1', 'Holding No');
                            $activeSheet->setCellValue('B1', 'Ward No');
                            $activeSheet->setCellValue('C1', 'Owner Name');
                            $activeSheet->setCellValue('D1', 'Mobile No.');
                            $activeSheet->setCellValue('E1', 'From');
                            $activeSheet->setCellValue('F1', 'Upto');
                            $activeSheet->setCellValue('G1', 'Tran. Date');
                            $activeSheet->setCellValue('H1', 'Tran. No.');
                            $activeSheet->setCellValue('I1', 'Mode');
                            $activeSheet->setCellValue('J1', 'Check/DD No');
                            $activeSheet->setCellValue('K1', 'Bank');
                            $activeSheet->setCellValue('L1', 'Branch');
                            $activeSheet->setCellValue('L1', 'Amount');
                            $activeSheet->setCellValue('N1', 'Tax Collector');
                            $activeSheet->fromArray($records, NULL, 'A2');
            $filename = "counter_report_".date('Ymd-hisa').".xlsx";
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="'.$filename.'"');
            header('Cache-Control: max-age=0');
            $writer = new Xlsx($spreadsheet);
            //$writer->save(APPPATH.'/hello world.xlsx');
            $writer->save('php://output');
        }catch(Exception $e){
            print_r($e);
        }
    }

    public function wardWiseCollectionSummery()
    {
        $ulb_mstr_id = 1;
        $data = ['from_date'=>date('Y-m-d'), 'upto_date'=>date('Y-m-d'), 'ward_type_report'=>'all_ward'];
        if($this->request->getMethod()=='post')
        {
            try
            {
                $inputs = arrFilterSanitizeString($this->request->getVar());
                $currentFY = getFY($inputs['from_date']);
                $currentFyID = $this->model_fy_mstr->getFyByFy(['fy'=>$currentFY])['id'];
                $data = $inputs;
                $inputs['currentFyID'] = $currentFyID;
                //print_var($inputs);
                if($report_list = $this->model_datatable->getWardWiseCollectionSummery($inputs, false)) {
                    $data['report_list'] = $report_list;
                }
                
            }
            catch(Exception $e)
            {
                print_r($e);
            }
        }
        return view('property/reports/ward_wise_collection_summery', $data);
    }

    public function SAFpaymentModeWiseSummery($from_date = NULL, $upto_date = NULL, $ward_mstr_id = null)
    {
        $ulb_mstr_id = 1;
        if(is_null($from_date)) {
            $from_date = date('Y-m-d');
        }
        if(is_null($upto_date)) {
            $upto_date = date('Y-m-d');
        }
        $data = ['from_date'=>$from_date, 'upto_date'=>$upto_date, 'ward_mstr_id'=>$ward_mstr_id];
        $wardList = $this->model_ward_mstr->getWardList(['ulb_mstr_id'=>$ulb_mstr_id]);
        if($this->request->getMethod()=='post'){
            $data = arrFilterSanitizeString($this->request->getVar());
        }
        $where = " AND tran_date BETWEEN '".$data['from_date']."' AND '".$data['upto_date']."'";
        if($data['ward_mstr_id']!='') {
            $where .= " AND tbl_saf_dtl.ward_mstr_id=".$data['ward_mstr_id'];
        }
        $sql = "SELECT 
                    tbl_tran_mode_mstr.transaction_mode,
                    (CASE WHEN transaction.no_of_tran IS NULL THEN 0 ELSE transaction.no_of_tran END) AS no_of_tran,
                    (CASE WHEN transaction.payable_amt IS NULL THEN 0 ELSE transaction.payable_amt END) AS payable_amt
                FROM tbl_tran_mode_mstr
                LEFT JOIN (SELECT tran_mode_mstr_id, COUNT(*) AS no_of_tran, SUM(tbl_transaction.payable_amt) AS payable_amt FROM tbl_transaction 
                            INNER JOIN tbl_saf_dtl ON tbl_saf_dtl.id=tbl_transaction.prop_dtl_id
                            WHERE tbl_transaction.tran_type='Saf' AND tbl_transaction.status IN (1,2,3)".$where." GROUP BY tran_mode_mstr_id)
                AS transaction ON tbl_tran_mode_mstr.id=transaction.tran_mode_mstr_id";
        
        if($report_list = $this->model_datatable->getRecords($sql)) {
            $data['success_tran_list'] = $report_list;
        }
        $sql = "SELECT 
                    tbl_tran_mode_mstr.transaction_mode,
                    (CASE WHEN transaction.no_of_tran IS NULL THEN 0 ELSE transaction.no_of_tran END) AS no_of_tran,
                    (CASE WHEN transaction.payable_amt IS NULL THEN 0 ELSE transaction.payable_amt END) AS payable_amt
                FROM tbl_tran_mode_mstr
                LEFT JOIN (SELECT tran_mode_mstr_id, COUNT(*) AS no_of_tran, SUM(tbl_transaction.payable_amt) AS payable_amt FROM tbl_transaction 
                            INNER JOIN tbl_saf_dtl ON tbl_saf_dtl.id=tbl_transaction.prop_dtl_id
                            WHERE tbl_transaction.tran_type='Saf' AND tbl_transaction.status=3".$where." GROUP BY tran_mode_mstr_id)
                AS transaction ON tbl_tran_mode_mstr.id=transaction.tran_mode_mstr_id";
        if($report_list = $this->model_datatable->getRecords($sql)) {
            $data['deactivated_tran_list'] = $report_list;
        }
        $sql = "WITH tbl_transaction AS (
            SELECT view_emp_details.id as emp_id,view_emp_details.emp_name,
                tran_mode_mstr_id, 
                COUNT(*) AS no_of_tran, 
                SUM(tbl_transaction.payable_amt) AS payable_amt
            FROM tbl_transaction
            INNER JOIN tbl_saf_dtl ON tbl_saf_dtl.id=tbl_transaction.prop_dtl_id
            INNER JOIN view_emp_details ON view_emp_details.id=tbl_transaction.tran_by_emp_details_id
            WHERE 
                tbl_transaction.tran_type='Saf'
                AND view_emp_details.user_type_id=8
                AND tbl_transaction.tran_mode_mstr_id!=4
                AND	tbl_transaction.status IN (1,2)".$where."
            GROUP BY tbl_transaction.tran_mode_mstr_id,view_emp_details.id ,view_emp_details.emp_name
        )
        SELECT 
            tbl_tran_mode_mstr.transaction_mode,emp_id,emp_name,
            tbl_transaction.no_of_tran,
            tbl_transaction.payable_amt
        FROM tbl_transaction
        INNER JOIN tbl_tran_mode_mstr ON tbl_tran_mode_mstr.id=tbl_transaction.tran_mode_mstr_id";
        if($report_list = $this->model_datatable->getRecords($sql)) {
            $grouped = [];
            foreach ($report_list as $item) {
                $grouped[$item['transaction_mode']][] = $item;
            }
            $data['jsk_success_tran_list']=[];
            foreach($grouped as $key=>$item){                
                $data['jsk_success_tran_list'][]=[
                    "transaction_mode"=>$item[0]["transaction_mode"],
                    "no_of_tran"=> round(array_sum(array_column($item,"no_of_tran")),2),
                    "payable_amt"=> round(array_sum(array_column($item,"payable_amt")),2),
                ];
            }
            $rmcJskIds = [1345,1375,1473];
            $data["jsk_success_tran_list_rmc_dtl"]=array_filter($report_list,function($item)use($rmcJskIds){
                return in_array($item["emp_id"],$rmcJskIds);//EJAZ  ANSARI
            });            
            $data["jsk_success_tran_list_out_dtl"]=array_filter($report_list,function($item)use($rmcJskIds){
                return !in_array($item["emp_id"],$rmcJskIds);//EJAZ  ANSARI
            });

            $groupedRmc = [];
            foreach ($data["jsk_success_tran_list_rmc_dtl"] as $item) {
                $groupedRmc[$item['transaction_mode']][] = $item;
            }
            $data["jsk_rmc_dtl"]=[];
            foreach($data["jsk_success_tran_list_rmc_dtl"] as $item){
                $data["jsk_rmc_dtl"][$item['emp_id']][]=$item;
            }
            $data["jsk_out_dtl"]=[];
            foreach($data["jsk_success_tran_list_out_dtl"] as $item){
                $data["jsk_out_dtl"][$item['emp_id']][]=$item;
            }
            $data['jsk_success_tran_list_rmc']=[];
            foreach($groupedRmc as $key=>$item){                
                $data['jsk_success_tran_list_rmc'][]=[
                    "transaction_mode"=>$item[0]["transaction_mode"],
                    "no_of_tran"=> round(array_sum(array_column($item,"no_of_tran")),2),
                    "payable_amt"=> round(array_sum(array_column($item,"payable_amt")),2),
                ];
            }

            $groupedOut = [];
            foreach ($data["jsk_success_tran_list_out_dtl"] as $item) {
                $groupedOut[$item['transaction_mode']][] = $item;
            }
            $data['jsk_success_tran_list_out']=[];
            foreach($groupedOut as $key=>$item){                
                $data['jsk_success_tran_list_out'][]=[
                    "transaction_mode"=>$item[0]["transaction_mode"],
                    "no_of_tran"=> round(array_sum(array_column($item,"no_of_tran")),2),
                    "payable_amt"=> round(array_sum(array_column($item,"payable_amt")),2),
                ];
            }
            // $data['jsk_success_tran_list'] = $report_list;
        }
        $sql = "WITH tbl_transaction AS (
            SELECT
                tran_mode_mstr_id, 
                COUNT(*) AS no_of_tran, 
                SUM(tbl_transaction.payable_amt) AS payable_amt
            FROM tbl_transaction
            INNER JOIN tbl_saf_dtl ON tbl_saf_dtl.id=tbl_transaction.prop_dtl_id
            INNER JOIN view_emp_details ON view_emp_details.id=tbl_transaction.tran_by_emp_details_id
            WHERE 
                tbl_transaction.tran_type='Saf'
                AND view_emp_details.user_type_id!=8
                AND tbl_transaction.tran_mode_mstr_id!=4
                AND	tbl_transaction.status IN (1,2)".$where."
            GROUP BY tbl_transaction.tran_mode_mstr_id
        )
        SELECT 
            tbl_tran_mode_mstr.transaction_mode,
            tbl_transaction.no_of_tran,
            tbl_transaction.payable_amt
        FROM tbl_transaction
        INNER JOIN tbl_tran_mode_mstr ON tbl_tran_mode_mstr.id=tbl_transaction.tran_mode_mstr_id";
        if($report_list = $this->model_datatable->getRecords($sql)) {
            $data['door_to_door_success_tran_list'] = $report_list;
        }
		
		$assessment_sql = "SELECT
                        assessment_type,
                        CASE 
                        WHEN assessment_type = 'New Assessment' THEN count(tbl_saf_dtl.id)
                        WHEN assessment_type = 'Reassessment' THEN count(tbl_saf_dtl.id)
                        WHEN assessment_type = 'Mutation' or assessment_type = 'Mutation with Reassessment' THEN count(tbl_saf_dtl.id)
                        end as total_application,
                        CASE
                        WHEN assessment_type = 'New Assessment' THEN sum(payable_amt)
                        WHEN assessment_type = 'Reassessment' THEN sum(payable_amt)
                        WHEN assessment_type = 'Mutation' or assessment_type = 'Mutation with Reassessment' THEN sum(payable_amt)
                        end as amount
                    FROM tbl_saf_dtl
                    JOIN tbl_transaction  on tbl_transaction.prop_dtl_id=tbl_saf_dtl.id and tran_type='Saf' and tbl_transaction.status in(1,2)
                    WHERE tbl_saf_dtl.status=1 ".$where."
                    group by assessment_type
                    ORDER BY CASE 
                    WHEN assessment_type = 'New Assessment' THEN 1
                    WHEN assessment_type = 'Reassessment' THEN 2
                    WHEN assessment_type = 'Mutation' or assessment_type = 'Mutation with Reassessment' THEN 3
                    ELSE 4 END ASC";
        if($assm_list = $this->model_datatable->getRecords($assessment_sql)) {
            $data['assessment_list'] = $assm_list;
        }
        
        $data['wardList'] = $wardList;
        return view('property/reports/payment_mode_wise_collection', $data);
                
    }
	
    public function paymentModeWiseSummery($from_date = NULL, $upto_date = NULL, $ward_mstr_id = null) {
        $ulb_mstr_id = 1;
        if(is_null($from_date)) {
            $from_date = date('Y-m-d');
        }
        if(is_null($upto_date)) {
            $upto_date = date('Y-m-d');
        }
        $data = ['from_date'=>$from_date, 'upto_date'=>$upto_date, 'ward_mstr_id'=>$ward_mstr_id];
        $wardList = $this->model_ward_mstr->getWardList(['ulb_mstr_id'=>$ulb_mstr_id]);
        if($this->request->getMethod()=='post'){
            $data = arrFilterSanitizeString($this->request->getVar());
        }
        $where = " AND tran_date BETWEEN '".$data['from_date']."' AND '".$data['upto_date']."'";
        if($data['ward_mstr_id']!='') {
            $where .= " AND tbl_prop_dtl.ward_mstr_id=".$data['ward_mstr_id'];
        }
        $sql = "SELECT 
                    tbl_tran_mode_mstr.transaction_mode,
                    (CASE WHEN transaction.no_of_tran IS NULL THEN 0 ELSE transaction.no_of_tran END) AS no_of_tran,
                    (CASE WHEN transaction.payable_amt IS NULL THEN 0 ELSE transaction.payable_amt END) AS payable_amt
                FROM tbl_tran_mode_mstr
                LEFT JOIN (SELECT tran_mode_mstr_id, COUNT(*) AS no_of_tran, SUM(tbl_transaction.payable_amt) AS payable_amt FROM tbl_transaction 
                            INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_transaction.prop_dtl_id
                            WHERE tbl_transaction.tran_type='Property' AND tbl_transaction.status IN (1,2)".$where." GROUP BY tran_mode_mstr_id)
                AS transaction ON tbl_tran_mode_mstr.id=transaction.tran_mode_mstr_id";
        if($report_list = $this->model_datatable->getRecords($sql)) {
            $data['success_tran_list'] = $report_list;
        }
        $sql = "SELECT 
                    tbl_tran_mode_mstr.transaction_mode,
                    (CASE WHEN transaction.no_of_tran IS NULL THEN 0 ELSE transaction.no_of_tran END) AS no_of_tran,
                    (CASE WHEN transaction.payable_amt IS NULL THEN 0 ELSE transaction.payable_amt END) AS payable_amt
                FROM tbl_tran_mode_mstr
                LEFT JOIN (SELECT tran_mode_mstr_id, COUNT(*) AS no_of_tran, SUM(tbl_transaction.payable_amt) AS payable_amt FROM tbl_transaction 
                            INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_transaction.prop_dtl_id
                            WHERE tbl_transaction.tran_type='Property' AND tbl_transaction.status=3".$where." GROUP BY tran_mode_mstr_id)
                AS transaction ON tbl_tran_mode_mstr.id=transaction.tran_mode_mstr_id";
        if($report_list = $this->model_datatable->getRecords($sql)) {
            $data['deactivated_tran_list'] = $report_list;
        }
        $sql = "WITH tbl_transaction AS (
            SELECT view_emp_details.id as emp_id,view_emp_details.emp_name,
                tran_mode_mstr_id, 
                COUNT(*) AS no_of_tran, 
                SUM(tbl_transaction.payable_amt) AS payable_amt
            FROM tbl_transaction
            INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_transaction.prop_dtl_id
            INNER JOIN view_emp_details ON view_emp_details.id=tbl_transaction.tran_by_emp_details_id
            WHERE 
                tbl_transaction.tran_type='Property'
                AND view_emp_details.user_type_id=8
                AND tbl_transaction.tran_mode_mstr_id!=4
                AND	tbl_transaction.status IN (1,2)".$where."
            GROUP BY tbl_transaction.tran_mode_mstr_id,view_emp_details.id ,view_emp_details.emp_name
        )
        SELECT 
            tbl_tran_mode_mstr.transaction_mode,emp_id,emp_name,
            tbl_transaction.no_of_tran,
            tbl_transaction.payable_amt
        FROM tbl_transaction
        INNER JOIN tbl_tran_mode_mstr ON tbl_tran_mode_mstr.id=tbl_transaction.tran_mode_mstr_id";
        if($report_list = $this->model_datatable->getRecords($sql)) {
            $grouped = [];
            foreach ($report_list as $item) {
                $grouped[$item['transaction_mode']][] = $item;
            }
            $data['jsk_success_tran_list']=[];
            foreach($grouped as $key=>$item){                
                $data['jsk_success_tran_list'][]=[
                    "transaction_mode"=>$item[0]["transaction_mode"],
                    "no_of_tran"=> round(array_sum(array_column($item,"no_of_tran")),2),
                    "payable_amt"=> round(array_sum(array_column($item,"payable_amt")),2),
                ];
            }
            $rmcJskIds = [1345,1375,1473];
            $data["jsk_success_tran_list_rmc_dtl"]=array_filter($report_list,function($item)use($rmcJskIds){
                return in_array($item["emp_id"],$rmcJskIds);//EJAZ  ANSARI
            });            
            $data["jsk_success_tran_list_out_dtl"]=array_filter($report_list,function($item) use($rmcJskIds){
                return !in_array($item["emp_id"],$rmcJskIds);//EJAZ  ANSARI
            });

            $groupedRmc = [];
            foreach ($data["jsk_success_tran_list_rmc_dtl"] as $item) {
                $groupedRmc[$item['transaction_mode']][] = $item;
            }
            $data["jsk_rmc_dtl"]=[];
            foreach($data["jsk_success_tran_list_rmc_dtl"] as $item){
                $data["jsk_rmc_dtl"][$item['emp_id']][]=$item;
            }
            $data["jsk_out_dtl"]=[];
            foreach($data["jsk_success_tran_list_out_dtl"] as $item){
                $data["jsk_out_dtl"][$item['emp_id']][]=$item;
            }
            $data['jsk_success_tran_list_rmc']=[];
            foreach($groupedRmc as $key=>$item){                
                $data['jsk_success_tran_list_rmc'][]=[
                    "transaction_mode"=>$item[0]["transaction_mode"],
                    "no_of_tran"=> round(array_sum(array_column($item,"no_of_tran")),2),
                    "payable_amt"=> round(array_sum(array_column($item,"payable_amt")),2),
                ];
            }

            $groupedOut = [];
            foreach ($data["jsk_success_tran_list_out_dtl"] as $item) {
                $groupedOut[$item['transaction_mode']][] = $item;
            }
            $data['jsk_success_tran_list_out']=[];
            foreach($groupedOut as $key=>$item){                
                $data['jsk_success_tran_list_out'][]=[
                    "transaction_mode"=>$item[0]["transaction_mode"],
                    "no_of_tran"=> round(array_sum(array_column($item,"no_of_tran")),2),
                    "payable_amt"=> round(array_sum(array_column($item,"payable_amt")),2),
                ];
            }
            // $data['jsk_success_tran_list'] = $report_list;
        }
        $sql = "WITH tbl_transaction AS (
            SELECT
                tran_mode_mstr_id, 
                COUNT(*) AS no_of_tran, 
                SUM(tbl_transaction.payable_amt) AS payable_amt
            FROM tbl_transaction
            INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_transaction.prop_dtl_id
            INNER JOIN view_emp_details ON view_emp_details.id=tbl_transaction.tran_by_emp_details_id
            WHERE 
                tbl_transaction.tran_type='Property'
                AND view_emp_details.user_type_id!=8
                AND tbl_transaction.tran_mode_mstr_id!=4
                AND	tbl_transaction.status IN (1,2)".$where."
            GROUP BY tbl_transaction.tran_mode_mstr_id
        )
        SELECT 
            tbl_tran_mode_mstr.transaction_mode,
            tbl_transaction.no_of_tran,
            tbl_transaction.payable_amt
        FROM tbl_transaction
        INNER JOIN tbl_tran_mode_mstr ON tbl_tran_mode_mstr.id=tbl_transaction.tran_mode_mstr_id";
        if($report_list = $this->model_datatable->getRecords($sql)) {
            $data['door_to_door_success_tran_list'] = $report_list;
        }
        
        $data['wardList'] = $wardList;
        return view('property/reports/payment_mode_wise_collection', $data); 
    }
	
    public function arrearAndCurrentCollectionSummery() {
        $ulb_mstr_id = 1;
        $wardList = $this->model_ward_mstr->getWardList(['ulb_mstr_id'=>$ulb_mstr_id]);
        $empDtlList = $this->model_view_emp_details->getEmpListByAlTaxCollector(['ulb_mstr_id'=>$ulb_mstr_id]);
        $data['wardList'] = $wardList;
        $data['empDtlList'] = $empDtlList;
        return view('property/reports/arrear_and_current_collection_summery', $data);
    }


    public function arrearAndCurrentCollectionSummeryAjax() {
        if($this->request->getMethod()=='post'){
			try{
                ## Read value
                $start = sanitizeString($this->request->getVar('start'));
                
                $rowperpage = sanitizeString($this->request->getVar('length')); // Rows display per page

                $columnIndex = sanitizeString($this->request->getVar('order')[0]['column']); // Column index
                $columnName = sanitizeString($this->request->getVar('columns')[$columnIndex]['data']); // Column name
                if ($columnName=="tran_no")
                    $columnName = 'tbl_transaction.tran_no';
                else if ($columnName=="holding_no")
                    $columnName = 'tbl_prop_dtl.holding_no';
                else if ($columnName=="ward_no")
                    $columnName = 'view_ward_mstr.ward_no';
                else if ($columnName=="owner_name")
                    $columnName = 'prop_owner_detail.owner_name';
                else
                    $columnName = 'tbl_transaction.tran_date';
                

                //$columnName = "tbl_transaction.tran_date";
                $columnSortOrder = sanitizeString($this->request->getVar('order')[0]['dir']); // asc or desc
                $searchValue = sanitizeString($this->request->getVar('search')['value']); // Search value
                
                
                
                // Date filter
                $search_from_date = sanitizeString($this->request->getVar('search_from_date'));
                $search_upto_date = sanitizeString($this->request->getVar('search_upto_date'));
                $search_ward_mstr_id = sanitizeString($this->request->getVar('search_ward_mstr_id'));
                $search_collector_id = sanitizeString($this->request->getVar('search_collector_id'));
                $searchQuery = "";
                $whereQuery = "";
                
                $orderBY = " ORDER BY ".$columnName." ".$columnSortOrder;
                $limit = " LIMIT ".$rowperpage." OFFSET ".$start;
                
                $whereQuery .= " AND tbl_transaction.tran_date BETWEEN '".$search_from_date."' AND '".$search_upto_date."'";
                if ($search_ward_mstr_id != '') {
                    $whereQuery .= " AND  tbl_prop_dtl.ward_mstr_id='".$search_ward_mstr_id."'";
                }
                if ($search_collector_id != '') {
                    $whereQuery .= " AND  tbl_transaction.tran_by_emp_details_id='".$search_collector_id."'";
                }
                $whereQueryWithSearch = "";
                if ($searchValue!='') {
                    $whereQueryWithSearch = " AND (prop_owner_detail.owner_name ILIKE '%".$searchValue."%'
                                    OR tbl_prop_dtl.holding_no ILIKE '%".$searchValue."%'
                                    OR tbl_transaction.tran_no ILIKE '%".$searchValue."%')";
                }

                //$data = ['from_date'=>date('Y-m-d'), 'upto_date'=>date('Y-m-d'), 'ward_type_report'=>'all_ward'];
                $data = ['from_date'=>'2019-06-07', 'upto_date'=>'2019-06-07', 'ward_mst_id'=>'', 'emp_dtl_id'=>''];

                $base_url = base_url();
                $selectStatement = "SELECT
                                    ROW_NUMBER () OVER (ORDER BY ".$columnName.") AS s_no,
                                    tbl_transaction.id AS transaction_id,
                                    tbl_transaction.tran_date,
                                    tbl_transaction.tran_no,
                                    CONCAT('<a href=', chr(39), '".$base_url."/propdtl/full/', MD5(tbl_prop_dtl.id::TEXT), '/backbtnhide', chr(39), ' target=', chr(39), '_blank', chr(39), '><b><u>', tbl_prop_dtl.holding_no, '</u></b></a>') AS holding_no,
                                    tbl_prop_dtl.new_holding_no,
									view_ward_mstr.ward_no,
                                    prop_owner_detail.owner_name,
                                    tbl_transaction.penalty_amt,
                                    tbl_transaction.discount_amt,
                                    tbl_transaction.payable_amt";

                $sql = " FROM tbl_transaction
                INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_transaction.prop_dtl_id
                INNER JOIN (SELECT STRING_AGG(owner_name, ', ') AS owner_name, prop_dtl_id FROM tbl_prop_owner_detail GROUP BY prop_dtl_id) prop_owner_detail ON prop_owner_detail.prop_dtl_id=tbl_transaction.prop_dtl_id
                INNER JOIN view_ward_mstr ON view_ward_mstr.id=tbl_prop_dtl.ward_mstr_id
                WHERE tbl_transaction.tran_type='Property'".$whereQuery;
                
                $selectSumStatement = "SELECT COALESCE(SUM(tbl_transaction.payable_amt), 0) AS total_payable_amt";

                //$totalAmount = $this->model_datatable->getSumAmount($sql);
                $totalRecords = $this->model_datatable->getTotalRecords($sql);
                $total_collection = 0;
                if ($totalRecords>0) {
                    ## Total number of records with filtering
                    $totalRecordwithFilter = $this->model_datatable->getTotalRecordwithFilter($sql.$whereQueryWithSearch);
                    ## Fetch records
                    $fetchSql = $selectStatement.$sql.$whereQueryWithSearch.$orderBY.$limit;
                    $fetchSumSql = $selectSumStatement.$sql;
                    $tran_dtl_list = $this->model_datatable->getRecords($fetchSql);
                    $total_collection = $this->model_datatable->getSumRecords($fetchSumSql)['total_payable_amt'];
                    
                    $records = [];
                    if ($tran_dtl_list) {
                        $currentFY = getFY();
                        $currentFyID = $this->model_fy_mstr->getFyByFy(['fy'=>$currentFY])['id'];
                        foreach ($tran_dtl_list AS $key=>$tran_dtl) {
                            $sql = "SELECT string_agg(tbl_collection.qtr::text || ' / ' || view_fy_mstr.fy::text, ',' ORDER BY fy_mstr_id,qtr ASC) AS a_period, COALESCE(SUM(amount), 0) AS a_total_amt FROM tbl_collection INNER JOIN view_fy_mstr ON view_fy_mstr.id=tbl_collection.fy_mstr_id WHERE tbl_collection.fy_mstr_id<".$currentFyID." AND tbl_collection.transaction_id=".$tran_dtl['transaction_id'].";";
                            $builder = $this->db->query($sql);
                            if ($a_fy_mstr_qtr_dtl = $builder->getResultArray()[0]) {
                                if(!is_null($a_fy_mstr_qtr_dtl['a_period'])) {
                                    $c_period_arr = (explode(",",$a_fy_mstr_qtr_dtl['a_period']));
                                    $c_period = current($c_period_arr)." TO ".end($c_period_arr);
                                    $tran_dtl_list[$key]['arrear_period']=$c_period;
                                } else {
                                    $tran_dtl_list[$key]['arrear_period']="N/A";
                                }
                                $tran_dtl_list[$key]['arrear_collection']=$a_fy_mstr_qtr_dtl['a_total_amt'];
                            }
                            $sql = "SELECT string_agg(tbl_collection.qtr::text || ' / ' || view_fy_mstr.fy::text, ',' ORDER BY fy_mstr_id,qtr ASC) AS c_period, COALESCE(SUM(amount), 0) AS c_total_amt FROM tbl_collection INNER JOIN view_fy_mstr ON view_fy_mstr.id=tbl_collection.fy_mstr_id WHERE tbl_collection.fy_mstr_id=".$currentFyID." AND tbl_collection.transaction_id=".$tran_dtl['transaction_id'].";";
                            $builder = $this->db->query($sql);
                            if ($c_fy_mstr_qtr_dtl = $builder->getResultArray()[0]) {
                                if(!is_null($c_fy_mstr_qtr_dtl['c_period'])) {
                                    $c_period_arr = (explode(",",$c_fy_mstr_qtr_dtl['c_period']));
                                    $c_period = current($c_period_arr)." TO ".end($c_period_arr);
                                    $tran_dtl_list[$key]['current_period']=$c_period;
                                } else {
                                    $tran_dtl_list[$key]['current_period']="N/A";
                                }
                                $tran_dtl_list[$key]['current_collection']=$c_fy_mstr_qtr_dtl['c_total_amt'];
                            }
                        }
                        foreach ($tran_dtl_list AS $key=>$tran_dtl) {
                            $records[] = [
                                's_no'=>$tran_dtl['s_no'],
                                'tran_date'=>$tran_dtl['tran_date'],
                                'tran_no'=>$tran_dtl['tran_no'],
                                'holding_no'=>$tran_dtl['holding_no'],
								'new_holding_no'=>$tran_dtl['new_holding_no'],
                                'ward_no'=>$tran_dtl['ward_no'],
                                'owner_name'=>$tran_dtl['owner_name'],
                                'arrear_period'=>$tran_dtl['arrear_period'],
                                'arrear_collection'=>$tran_dtl['arrear_collection'],
                                'current_period'=>$tran_dtl['current_period'],
                                'current_collection'=>$tran_dtl['current_collection'],
                                'penalty_amt'=>$tran_dtl['penalty_amt'],
                                'discount_amt'=>$tran_dtl['discount_amt'],
                                'advance_amt'=>0,
                                'adjustment_amt'=>0,
                                'payable_amt'=>$tran_dtl['payable_amt'],
                                
                            ];
                        }
                    }
                } else {
                    $totalRecordwithFilter = 0;
                    $records = [];
                }

                $response = array(
                    "draw" => 0,
                    "total_collection" => $total_collection,
                    "recordsTotal" => $totalRecords,                 
                    "recordsFiltered" => $totalRecordwithFilter,
                    "data" => $records
                );
                return json_encode($response);
                
                
                
            }catch(Exception $e){
                print_r($e);
            }
        } else {
            echo "asdasd";
        }
    }

    public function arrearAndCurrentCollectionSummeryExcel($search_from_date = null, $search_upto_date = null, $search_ward_mstr_id = null, $search_collector_id = null) {
        try{
            $whereQuery = " AND tbl_transaction.tran_date BETWEEN '".$search_from_date."' AND '".$search_upto_date."'";
                if ($search_ward_mstr_id != 'ALL') {
                    $whereQuery .= " AND  tbl_prop_dtl.ward_mstr_id='".$search_ward_mstr_id."'";
                }
                if ($search_collector_id != 'ALL') {
                    $whereQuery .= " AND  tbl_transaction.tran_by_emp_details_id='".$search_collector_id."'";
                }
                
                $selectStatement = "SELECT
                                    tbl_transaction.id AS transaction_id,
                                    tbl_transaction.tran_date,
                                    tbl_transaction.tran_no,
                                    tbl_prop_dtl.holding_no,
                                    view_ward_mstr.ward_no,
                                    prop_owner_detail.owner_name,
                                    tbl_transaction.penalty_amt,
                                    tbl_transaction.discount_amt,
                                    tbl_transaction.payable_amt";

                $sql = " FROM tbl_transaction
                INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_transaction.prop_dtl_id
                INNER JOIN (SELECT STRING_AGG(owner_name, ', ') AS owner_name, prop_dtl_id FROM tbl_prop_owner_detail GROUP BY prop_dtl_id) prop_owner_detail ON prop_owner_detail.prop_dtl_id=tbl_transaction.prop_dtl_id
                INNER JOIN view_ward_mstr ON view_ward_mstr.id=tbl_prop_dtl.ward_mstr_id
                WHERE tbl_transaction.tran_type='Property'".$whereQuery;

            $fetchSql = $selectStatement.$sql;
            $tran_dtl_list = $this->model_datatable->getRecords($fetchSql);
            $records = [];
            if ($tran_dtl_list) {
                $currentFY = getFY();
                $currentFyID = $this->model_fy_mstr->getFyByFy(['fy'=>$currentFY])['id'];
                foreach ($tran_dtl_list AS $key=>$tran_dtl) {
                    $sql = "SELECT string_agg(tbl_collection.qtr::text || ' / ' || view_fy_mstr.fy::text, ',' ORDER BY fy_mstr_id,qtr ASC) AS a_period, COALESCE(SUM(amount), 0) AS a_total_amt FROM tbl_collection INNER JOIN view_fy_mstr ON view_fy_mstr.id=tbl_collection.fy_mstr_id WHERE tbl_collection.fy_mstr_id<".$currentFyID." AND tbl_collection.transaction_id=".$tran_dtl['transaction_id'].";";
                    $builder = $this->db->query($sql);
                    if ($a_fy_mstr_qtr_dtl = $builder->getResultArray()[0]) {
                        if(!is_null($a_fy_mstr_qtr_dtl['a_period'])) {
                            $c_period_arr = (explode(",",$a_fy_mstr_qtr_dtl['a_period']));
                            $c_period = current($c_period_arr)." TO ".end($c_period_arr);
                            $tran_dtl_list[$key]['arrear_period']=$c_period;
                        } else {
                            $tran_dtl_list[$key]['arrear_period']="N/A";
                        }
                        $tran_dtl_list[$key]['arrear_collection']=$a_fy_mstr_qtr_dtl['a_total_amt'];
                    }
                    $sql = "SELECT string_agg(tbl_collection.qtr::text || ' / ' || view_fy_mstr.fy::text, ',' ORDER BY fy_mstr_id,qtr ASC) AS c_period, COALESCE(SUM(amount), 0) AS c_total_amt FROM tbl_collection INNER JOIN view_fy_mstr ON view_fy_mstr.id=tbl_collection.fy_mstr_id WHERE tbl_collection.fy_mstr_id=".$currentFyID." AND tbl_collection.transaction_id=".$tran_dtl['transaction_id'].";";
                    $builder = $this->db->query($sql);
                    if ($c_fy_mstr_qtr_dtl = $builder->getResultArray()[0]) {
                        if(!is_null($c_fy_mstr_qtr_dtl['c_period'])) {
                            $c_period_arr = (explode(",",$c_fy_mstr_qtr_dtl['c_period']));
                            $c_period = current($c_period_arr)." TO ".end($c_period_arr);
                            $tran_dtl_list[$key]['current_period']=$c_period;
                        } else {
                            $tran_dtl_list[$key]['current_period']="N/A";
                        }
                        $tran_dtl_list[$key]['current_collection']=$c_fy_mstr_qtr_dtl['c_total_amt'];
                    }
                }
                foreach ($tran_dtl_list AS $key=>$tran_dtl) {
                    $records[] = [
                        'tran_date'=>$tran_dtl['tran_date'],
                        'tran_no'=>$tran_dtl['tran_no'],
                        'holding_no'=>$tran_dtl['holding_no'],
                        'ward_no'=>$tran_dtl['ward_no'],
                        'owner_name'=>$tran_dtl['owner_name'],
                        'arrear_period'=>$tran_dtl['arrear_period'],
                        'arrear_collection'=>$tran_dtl['arrear_collection'],
                        'current_period'=>$tran_dtl['current_period'],
                        'current_collection'=>$tran_dtl['current_collection'],
                        'penalty_amt'=>$tran_dtl['penalty_amt'],
                        'discount_amt'=>$tran_dtl['discount_amt'],
                        'advance_amt'=>"0.00",
                        'adjustment_amt'=>"0.00",
                        'payable_amt'=>$tran_dtl['payable_amt'],
                        
                    ];
                }
            }
            //phpOfficeLoad();
            $spreadsheet = new Spreadsheet();
            $activeSheet = $spreadsheet->getActiveSheet();
                            $activeSheet->setCellValue('A1', 'Tran. Date');
                            $activeSheet->setCellValue('B1', 'Tran. No');
                            $activeSheet->setCellValue('C1', 'Holding No.');
                            $activeSheet->setCellValue('D1', 'Ward No.');
                            $activeSheet->setCellValue('E1', 'Applicant Name');
                            $activeSheet->setCellValue('F1', 'Arrear Period');
                            $activeSheet->setCellValue('G1', 'Arrear Collection');
                            $activeSheet->setCellValue('H1', 'Current Period');
                            $activeSheet->setCellValue('I1', 'Current Collection');
                            $activeSheet->setCellValue('J1', 'Penalty');
                            $activeSheet->setCellValue('K1', 'Rebate');
                            $activeSheet->setCellValue('L1', 'Adv.');
                            $activeSheet->setCellValue('M1', 'Adj.');
                            $activeSheet->setCellValue('N1', 'Total Collection');
                            $activeSheet->fromArray($records, NULL, 'A2');
            $filename = "counter_report_".date('Ymd-hisa').".xlsx";
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="'.$filename.'"');
            header('Cache-Control: max-age=0');
            $writer = new Xlsx($spreadsheet);
            //$writer->save(APPPATH.'/hello world.xlsx');
            $writer->save('php://output');
        }catch(Exception $e){
            print_r($e);
        }
    }

    public function wardWiseHolding() {
        $ulb_mstr_id = 1;
        $wardList = $this->model_ward_mstr->getWardList(['ulb_mstr_id'=>$ulb_mstr_id]);
        $data['wardList'] = $wardList;
        $currentFY = getFY();
        $currentFyID = $this->model_fy_mstr->getFyByFy(['fy'=>$currentFY])['id'];
        $fyList = $this->model_fy_mstr->getFYListDescBefore10Year($currentFyID);
        $data['fyList'] = $fyList;
        return view('property/reports/ward_wise_holding', $data);
    }

    public function wardWiseHoldingAjax() {
        //return json_encode(['result'=>'columnName']);
        if($this->request->getMethod()=='post'){
			try{
                ## Read value
                $start = sanitizeString($this->request->getVar('start'));
                
                $rowperpage = sanitizeString($this->request->getVar('length')); // Rows display per page

                $columnIndex = sanitizeString($this->request->getVar('order')[0]['column']); // Column index
                $columnName = sanitizeString($this->request->getVar('columns')[$columnIndex]['data']); // Column name
                if ($columnName=="s_no" || $columnName=="ward_no" || $columnName=="from_qtr_fy" || $columnName=="due_amt")
                    $columnName = 'view_ward_mstr.ward_no';
                if ($columnName=="holding_no")
                    $columnName = 'tbl_prop_dtl.holding_no';
                if ($columnName=="owner_name")
                    $columnName = 'prop_owner_detail.owner_name';
                if ($columnName=="mobile_no")
                    $columnName = 'prop_owner_detail.mobile_no';
                if ($columnName=="address")
                    $columnName = 'tbl_prop_dtl.prop_address';

                
                $columnSortOrder = sanitizeString($this->request->getVar('order')[0]['dir']); // asc or desc
                $searchValue = sanitizeString($this->request->getVar('search')['value']); // Search value
                
                
                
                // Date filter
                $search_ward_mstr_id = sanitizeString($this->request->getVar('search_ward_mstr_id'));
                $search_amt_greater_than_or_equal = sanitizeString($this->request->getVar('search_amt_greater_than_or_equal'));
                $search_fy_mstr_id = sanitizeString($this->request->getVar('search_fy_mstr_id'));

                $whereWard = "";
                $whereFY = "";
                $whereAmtGreaterEqual = "";
                $JOIN_TYPE = "LEFT";

                $orderBY = " ORDER BY ".$columnName." ".$columnSortOrder;
                $limit = " LIMIT ".$rowperpage." OFFSET ".$start;

                if ($search_ward_mstr_id != '') {
                    $whereWard .= " AND tbl_prop_dtl.ward_mstr_id='".$search_ward_mstr_id."'";
                }
                if ($search_fy_mstr_id != '') {
                    $whereFY .= " AND tbl_prop_demand.fy_mstr_id='".$search_fy_mstr_id."'";
                    $JOIN_TYPE = "INNER";
                }
                if ($search_amt_greater_than_or_equal != '' && $search_amt_greater_than_or_equal>0) {
                    $whereAmtGreaterEqual .= " HAVING SUM(tbl_prop_demand.amount)>'".$search_amt_greater_than_or_equal."'";
                    $JOIN_TYPE = "INNER";
                }
                
                $whereQueryWithSearch = "";
                if ($searchValue!='') {
                    $whereQueryWithSearch .= " AND (prop_owner_detail.owner_name ILIKE '%".$searchValue."%'
                                    OR prop_owner_detail.mobile_no ILIKE '%".$searchValue."%'
                                    OR tbl_prop_dtl.holding_no ILIKE '%".$searchValue."%'
									OR tbl_prop_dtl.new_holding_no ILIKE '%".$searchValue."%'
                                    OR view_ward_mstr.ward_no ILIKE '%".$searchValue."%'
                                    OR tbl_prop_dtl.prop_address ILIKE '%".$searchValue."%'
                                    OR tbl_prop_dtl.prop_city ILIKE '%".$searchValue."%'
                                    OR tbl_prop_dtl.prop_pin_code ILIKE '%".$searchValue."%')";
                }
                $base_url = base_url();
                $selectStatement = "SELECT 
                                    ROW_NUMBER () OVER (ORDER BY ".$columnName.") AS s_no,
                                    view_ward_mstr.ward_no,
                                    CONCAT('<a href=', chr(39), '".$base_url."/propdtl/full/', MD5(tbl_prop_dtl.id::TEXT), '/backbtnhide', chr(39), ' target=', chr(39), '_blank', chr(39), '><b><u>', tbl_prop_dtl.holding_no, '</u></b></a>') AS holding_no,
                                    tbl_prop_dtl.new_holding_no,
									prop_owner_detail.owner_name,
                                    prop_owner_detail.mobile_no,
                                    CONCAT(tbl_prop_dtl.prop_address, ', City - ',tbl_prop_dtl.prop_city, ', Pin Code - ', tbl_prop_dtl.prop_pin_code) as address,
                                    (CASE WHEN FROM_QTR_FY.from_qtr_fy IS NOT NULL THEN FROM_QTR_FY.from_qtr_fy ELSE 'N/A' END) AS from_qtr_fy,
                                    (CASE WHEN DUE_AMT.due_amt IS NOT NULL THEN CONCAT('<span class=', CHR(39), 'text-danger', CHR(39), '>', DUE_AMT.due_amt::TEXT, '</span>') ELSE CONCAT('<span class=', CHR(39), 'text-success', CHR(39), '>No Dues</span>') END) AS due_amt";
                $sql =  " FROM tbl_prop_dtl
                            INNER JOIN view_ward_mstr ON view_ward_mstr.id=tbl_prop_dtl.ward_mstr_id
                            INNER JOIN (
                                SELECT 
                                    prop_dtl_id,
                                    STRING_AGG(owner_name, ', ') AS owner_name, 
                                    STRING_AGG(mobile_no::TEXT, ', ') AS mobile_no
                                FROM tbl_prop_owner_detail GROUP BY prop_dtl_id
                            ) prop_owner_detail ON prop_owner_detail.prop_dtl_id=tbl_prop_dtl.id
                            ".$JOIN_TYPE." JOIN (
                                SELECT 
                                    FROM_QTR_FY_child.prop_dtl_id,
                                    CONCAT(FROM_QTR_FY_child.qtr, '|', view_fy_mstr.fy) AS from_qtr_fy
                                FROM 
                                    (SELECT 
                                        prop_dtl_id, 
                                        fy_mstr_id,
                                        qtr,
                                        ROW_NUMBER () OVER (PARTITION BY prop_dtl_id ORDER BY id) AS prop_dtl_id_partition
                                    FROM tbl_prop_demand
                                    WHERE 
                                        tbl_prop_demand.status=1
                                        AND tbl_prop_demand.paid_status=0
                                        ".$whereFY.") AS FROM_QTR_FY_child
                                INNER JOIN view_fy_mstr ON view_fy_mstr.id=FROM_QTR_FY_child.fy_mstr_id
                                WHERE FROM_QTR_FY_child.prop_dtl_id_partition=1
                            ) AS FROM_QTR_FY ON FROM_QTR_FY.prop_dtl_id=tbl_prop_dtl.id
                            ".$JOIN_TYPE." JOIN (
                                SELECT 
                                    prop_dtl_id, 
                                    SUM(amount) AS due_amt
                                FROM tbl_prop_demand 
                                WHERE
                                    tbl_prop_demand.status=1
	                                AND tbl_prop_demand.paid_status=0".$whereFY."
                                GROUP BY prop_dtl_id
                                ".$whereAmtGreaterEqual."
                            ) AS DUE_AMT ON DUE_AMT.prop_dtl_id=tbl_prop_dtl.id
                            WHERE tbl_prop_dtl.status=1 AND view_ward_mstr.status=1".$whereWard;
                
                //return json_encode($selectStatement.$sql.$whereQueryWithSearch.$orderBY.$limit);
                ## Total number of records without filtering
                $totalRecords = $this->model_datatable->getTotalRecords($sql);
				/*$totalRecords = $this->model_datatable->getDatatable($sql);
				$data['emp_details'] = $totalRecords['result'];
				$data['pager'] = $totalRecords['count'];*/
                if ($totalRecords>0) {
                   
                    ## Total number of records with filtering
                    $totalRecordwithFilter = $this->model_datatable->getTotalRecordwithFilter($sql.$whereQueryWithSearch);
                    ## Fetch records
                    $fetchSql = $selectStatement.$sql.$whereQueryWithSearch.$orderBY.$limit;
                    //echo $fetchSql;
                    $records = $this->model_datatable->getRecords($fetchSql);
                } else {
                    $totalRecordwithFilter = 0;
                    $records = [];
                }
                
                //echo $this->db->getLastQuery();
                $response = array(
                    "iTotalRecords" => $totalRecords,
                    "iTotalDisplayRecords" => $totalRecordwithFilter,
                    "aaData" => $records
                );
                return json_encode($response);
            }catch(Exception $e){

            }
        }
    }

    public function wardWiseHoldingExcel($search_ward_mstr_id = 'ALL', $search_amt_greater_than_or_equal = 'ALL', $search_fy_mstr_id = 'ALL') {
        try{
            $whereWard = "";
            $whereFY = "";
            $whereAmtGreaterEqual = "";
            $JOIN_TYPE = "LEFT";

            if ($search_ward_mstr_id != 'ALL') {
                $whereWard .= " AND tbl_prop_dtl.ward_mstr_id='".$search_ward_mstr_id."'";
            }
            if ($search_fy_mstr_id != 'ALL') {
                $whereFY .= " AND tbl_prop_demand.fy_mstr_id='".$search_fy_mstr_id."'";
                $JOIN_TYPE = "INNER";
            }
            if ($search_amt_greater_than_or_equal != 'ALL' && $search_amt_greater_than_or_equal>0) {
                $whereAmtGreaterEqual .= " HAVING SUM(tbl_prop_demand.amount)>'".$search_amt_greater_than_or_equal."'";
                $JOIN_TYPE = "INNER";
            }
            
            $whereQueryWithSearch = "";

            $base_url = base_url();
            $selectStatement = "SELECT 
                                view_ward_mstr.ward_no,
                                tbl_prop_dtl.holding_no AS holding_no,
                                tbl_prop_dtl.new_holding_no,
                                prop_owner_detail.owner_name,
                                prop_owner_detail.mobile_no,
                                CONCAT(tbl_prop_dtl.prop_address, ', City - ',tbl_prop_dtl.prop_city, ', Pin Code - ', tbl_prop_dtl.prop_pin_code) as address,
                                (CASE WHEN FROM_QTR_FY.from_qtr_fy IS NOT NULL THEN FROM_QTR_FY.from_qtr_fy ELSE 'N/A' END) AS from_qtr_fy,
                                (CASE WHEN DUE_AMT.due_amt IS NOT NULL THEN DUE_AMT.due_amt::TEXT ELSE 'No Dues' END) AS due_amt";
            $sql =  " FROM tbl_prop_dtl
                        INNER JOIN view_ward_mstr ON view_ward_mstr.id=tbl_prop_dtl.ward_mstr_id
                        INNER JOIN (
                            SELECT 
                                prop_dtl_id,
                                STRING_AGG(owner_name, ', ') AS owner_name, 
                                STRING_AGG(mobile_no::TEXT, ', ') AS mobile_no
                            FROM tbl_prop_owner_detail GROUP BY prop_dtl_id
                        ) prop_owner_detail ON prop_owner_detail.prop_dtl_id=tbl_prop_dtl.id
                        ".$JOIN_TYPE." JOIN (
                            SELECT 
                                FROM_QTR_FY_child.prop_dtl_id,
                                CONCAT(FROM_QTR_FY_child.qtr, '|', view_fy_mstr.fy) AS from_qtr_fy
                            FROM 
                                (SELECT 
                                    prop_dtl_id, 
                                    fy_mstr_id,
                                    qtr,
                                    ROW_NUMBER () OVER (PARTITION BY prop_dtl_id ORDER BY id) AS prop_dtl_id_partition
                                FROM tbl_prop_demand
                                WHERE 
                                    tbl_prop_demand.status=1
                                    AND tbl_prop_demand.paid_status=0
                                    ".$whereFY.") AS FROM_QTR_FY_child
                            INNER JOIN view_fy_mstr ON view_fy_mstr.id=FROM_QTR_FY_child.fy_mstr_id
                            WHERE FROM_QTR_FY_child.prop_dtl_id_partition=1
                        ) AS FROM_QTR_FY ON FROM_QTR_FY.prop_dtl_id=tbl_prop_dtl.id
                        ".$JOIN_TYPE." JOIN (
                            SELECT 
                                prop_dtl_id, 
                                SUM(amount) AS due_amt
                            FROM tbl_prop_demand 
                            WHERE
                                tbl_prop_demand.status=1
                                AND tbl_prop_demand.paid_status=0".$whereFY."
                            GROUP BY prop_dtl_id
                            ".$whereAmtGreaterEqual."
                        ) AS DUE_AMT ON DUE_AMT.prop_dtl_id=tbl_prop_dtl.id
                        WHERE tbl_prop_dtl.status=1 AND view_ward_mstr.status=1".$whereWard;
            
            //return json_encode($selectStatement.$sql.$whereQueryWithSearch.$orderBY.$limit);
            ## Total number of records without filtering

            $fetchSql = $selectStatement.$sql;
            
            $records = $this->model_datatable->getRecords($fetchSql);
            //phpOfficeLoad();
            $spreadsheet = new Spreadsheet();
            $activeSheet = $spreadsheet->getActiveSheet();
                            $activeSheet->setCellValue('A1', 'Ward No.');
                            $activeSheet->setCellValue('B1', 'Holding No.');
                            $activeSheet->setCellValue('C1', 'New Holding No.');
                            $activeSheet->setCellValue('D1', 'Applicant Name');
                            $activeSheet->setCellValue('E1', 'Mobile No.');
                            $activeSheet->setCellValue('F1', 'Address');
                            $activeSheet->setCellValue('G1', 'From Fy Year');
                            $activeSheet->setCellValue('H1', 'Due Amount');
                            $activeSheet->fromArray($records, NULL, 'A2');
            $filename = "prop_individual_demand_and_collecton_".date('Ymd-hisa').".xlsx";
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="'.$filename.'"');
            header('Cache-Control: max-age=0');
            $writer = new Xlsx($spreadsheet);
            //$writer->save(APPPATH.'/hello world.xlsx');
            $writer->save('php://output');
        }catch(Exception $e){
            print_r($e);
        }
    }

    public function prevYrPaidButNotPaidCurrentYr() {
        $ulb_mstr_id = 1;
        $wardList = $this->model_ward_mstr->getWardList(['ulb_mstr_id'=>$ulb_mstr_id]);
        $data['wardList'] = $wardList;
        return view('property/reports/prev_yr_paid_but_not_paid_current_yr', $data);
    }

    public function prevYrPaidButNotPaidCurrentYrAjax() {
        if($this->request->getMethod()=='post'){
			try{
                ## Read value
                $start = sanitizeString($this->request->getVar('start'));
                $rowperpage = sanitizeString($this->request->getVar('length')); // Rows display per page

                $columnIndex = sanitizeString($this->request->getVar('order')[0]['column']); // Column index
                $columnName = sanitizeString($this->request->getVar('columns')[$columnIndex]['data']); // Column name
                if ($columnName=="s_no" || $columnName=="ward_no" || $columnName=="total_demand")
                    $columnName = 'view_ward_mstr.ward_no';
                if ($columnName=="holding_no")
                    $columnName = 'tbl_prop_dtl.holding_no';
                if ($columnName=="owner_name")
                    $columnName = 'prop_owner_detail.owner_name';
                if ($columnName=="mobile_no")
                    $columnName = 'prop_owner_detail.mobile_no';
                if ($columnName=="address")
                    $columnName = 'tbl_prop_dtl.prop_address';

                
                //$columnName = "tbl_transaction.tran_date";
                $columnSortOrder = sanitizeString($this->request->getVar('order')[0]['dir']); // asc or desc
                $searchValue = sanitizeString($this->request->getVar('search')['value']); // Search value
                
                // Date filter
                $search_ward_mstr_id = sanitizeString($this->request->getVar('search_ward_mstr_id'));
                $searchQuery = "";
                $whereQuery = "";
                
                
                $orderBY = " ORDER BY ".$columnName." ".$columnSortOrder;
                $limit = " LIMIT ".$rowperpage." OFFSET ".$start;

                $currentFY = getFY();
                $currentFyID = $this->model_fy_mstr->getFyByFy(['fy'=>$currentFY])['id'];

                if ($search_ward_mstr_id != '') {
                    $whereQuery .= " AND tbl_prop_dtl.ward_mstr_id='".$search_ward_mstr_id."'";
                }
                
                $whereQueryWithSearch = "";
                if ($searchValue!='') {
                    $whereQueryWithSearch .= " AND (prop_owner_detail.owner_name ILIKE '%".$searchValue."%'
                                    OR prop_owner_detail.mobile_no ILIKE '%".$searchValue."%'
                                    OR tbl_prop_dtl.holding_no ILIKE '%".$searchValue."%'
									OR tbl_prop_dtl.new_holding_no ILIKE '%".$searchValue."%'
                                    OR view_ward_mstr.ward_no ILIKE '%".$searchValue."%'
                                    OR tbl_prop_dtl.prop_address ILIKE '%".$searchValue."%'
                                    OR tbl_prop_dtl.prop_city ILIKE '%".$searchValue."%'
                                    OR tbl_prop_dtl.prop_pin_code ILIKE '%".$searchValue."%')";
                }
                
                $base_url = base_url();
                $selectStatement = "SELECT 
                                    ROW_NUMBER () OVER (ORDER BY ".$columnName.") AS s_no,
                                    view_ward_mstr.ward_no,
                                    CONCAT('<a href=', chr(39), '".$base_url."/propdtl/full/', MD5(tbl_prop_dtl.id::TEXT), '/backbtnhide', chr(39), ' target=', chr(39), '_blank', chr(39), '><b><u>', tbl_prop_dtl.holding_no, '</u></b></a>') AS holding_no,
                                    tbl_prop_dtl.new_holding_no,
									prop_owner_detail.owner_name,
                                    prop_owner_detail.mobile_no,
                                    CONCAT(tbl_prop_dtl.prop_address, ', City - ', tbl_prop_dtl.prop_city, ', Pin Code - ', tbl_prop_dtl.prop_pin_code) AS address,
                                    c_prop_demand.c_total_demand AS total_demand";
                $sql0 = "";
                $sql1 =  " FROM tbl_prop_dtl
                        INNER JOIN (SELECT prop_dtl_id FROM tbl_prop_demand WHERE status=1 AND fy_mstr_id<".$currentFyID." AND paid_status=1 GROUP BY prop_dtl_id) AS a_prop_demand ON a_prop_demand.prop_dtl_id=tbl_prop_dtl.id
                        INNER JOIN (SELECT prop_dtl_id, SUM(amount) AS c_total_demand FROM tbl_prop_demand WHERE status=1 AND fy_mstr_id=".$currentFyID." AND paid_status=0 GROUP BY prop_dtl_id) AS c_prop_demand ON c_prop_demand.prop_dtl_id=tbl_prop_dtl.id
                        INNER JOIN (SELECT STRING_AGG(owner_name, ', ') AS owner_name, STRING_AGG(mobile_no::TEXT, ', ') AS mobile_no, prop_dtl_id FROM tbl_prop_owner_detail GROUP BY prop_dtl_id) prop_owner_detail ON prop_owner_detail.prop_dtl_id=tbl_prop_dtl.id
                        INNER JOIN view_ward_mstr ON view_ward_mstr.id=tbl_prop_dtl.ward_mstr_id
                        WHERE tbl_prop_dtl.status=1 AND view_ward_mstr.status=1".$whereQuery;
                
                //return json_encode($sql0.$selectStatement.$sql1.$whereQueryWithSearch.$orderBY.$limit);
                ## Total number of records without filtering
                $totalRecords = $this->model_datatable->getTotalRecords($sql1, false, $sql0);
                //return json_encode($totalRecords);
                if ($totalRecords>0) {
                   
                    ## Total number of records with filtering
                    $totalRecordwithFilter = $this->model_datatable->getTotalRecordwithFilter($sql1.$whereQueryWithSearch, false, $sql0);
                    
                    ## Fetch records
                    $fetchSql = $sql0.$selectStatement.$sql1.$whereQueryWithSearch.$orderBY.$limit;
                    $records = $this->model_datatable->getRecords($fetchSql);
                } else {
                    $totalRecordwithFilter = 0;
                    $records = [];
                }
                
                //echo $this->db->getLastQuery();
                $response = array(
                    "iTotalRecords" => $totalRecords,
                    "iTotalDisplayRecords" => $totalRecordwithFilter,
                    "aaData" => $records
                );
                return json_encode($response);
            }catch(Exception $e){

            }
        }
    }

    public function prevYrPaidButNotPaidCurrentYrExcel($search_ward_mstr_id = null) {
        try{
            $whereQuery = "";
                if ($search_ward_mstr_id != 'ALL') {
                    $whereQuery .= " AND  tbl_prop_dtl.ward_mstr_id='".$search_ward_mstr_id."'";
                }

                $currentFY = getFY();
                $currentFyID = $this->model_fy_mstr->getFyByFy(['fy'=>$currentFY])['id'];

                $selectStatement = "SELECT
                                    view_ward_mstr.ward_no,
                                    CONCAT('`',tbl_prop_dtl.holding_no) AS holding_no,
                                    cast(tbl_prop_dtl.new_holding_no as char(20)) as Unique_House_No ,
                                    prop_owner_detail.owner_name,
                                    prop_owner_detail.mobile_no,
                                    CONCAT(tbl_prop_dtl.prop_address, ', City - ', tbl_prop_dtl.prop_city, ', Pin Code - ', tbl_prop_dtl.prop_pin_code) AS address,
                                    c_prop_demand.c_total_demand AS total_demand";

                $sql = " FROM tbl_prop_dtl
                INNER JOIN (SELECT prop_dtl_id FROM tbl_prop_demand WHERE status=1 AND fy_mstr_id<".$currentFyID." AND paid_status=1 GROUP BY prop_dtl_id) AS a_prop_demand ON a_prop_demand.prop_dtl_id=tbl_prop_dtl.id
                INNER JOIN (SELECT prop_dtl_id, SUM(amount) AS c_total_demand FROM tbl_prop_demand WHERE status=1 AND fy_mstr_id=".$currentFyID." AND paid_status=0 GROUP BY prop_dtl_id) AS c_prop_demand ON c_prop_demand.prop_dtl_id=tbl_prop_dtl.id
                INNER JOIN (SELECT STRING_AGG(owner_name, ', ') AS owner_name, STRING_AGG(mobile_no::TEXT, ', ') AS mobile_no, prop_dtl_id FROM tbl_prop_owner_detail GROUP BY prop_dtl_id) prop_owner_detail ON prop_owner_detail.prop_dtl_id=tbl_prop_dtl.id
                INNER JOIN view_ward_mstr ON view_ward_mstr.id=tbl_prop_dtl.ward_mstr_id
                WHERE tbl_prop_dtl.status=1 AND view_ward_mstr.status=1".$whereQuery;

            $fetchSql = $selectStatement.$sql;
            $records = $this->model_datatable->getRecords($fetchSql); 
            //phpOfficeLoad();
            $spreadsheet = new Spreadsheet();
            $activeSheet = $spreadsheet->getActiveSheet();
                            $activeSheet->setCellValue('A1', 'Ward No.');
                            $activeSheet->setCellValue('B1', 'Holding No.');
                            $activeSheet->setCellValue('C1', 'Unique House No.');
                            $activeSheet->setCellValue('D1', 'Applicant Name');
                            $activeSheet->setCellValue('E1', 'Mobile No.');
                            $activeSheet->setCellValue('F1', 'Address');
                            $activeSheet->setCellValue('G1', 'Total Demand');
                            $activeSheet->fromArray($records, NULL, 'A2');
            $filename = "prev_yr_paid_but_not_paid_current_yr_".date('Ymd-hisa').".xlsx";
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="'.$filename.'"');
            header('Cache-Control: max-age=0');
            $writer = new Xlsx($spreadsheet);
            //$writer->save(APPPATH.'/hello world.xlsx');
            $writer->save('php://output');
        }catch(Exception $e){
            print_r($e);
        }
    }

    public function notPaidFrom1617() {
        $ulb_mstr_id = 1;
        $wardList = $this->model_ward_mstr->getWardList(['ulb_mstr_id'=>$ulb_mstr_id]);
        $data['wardList'] = $wardList;
        return view('property/reports/not_paid_from_16_17', $data);
    }

    public function notPaidFrom1617Ajax() {
        if($this->request->getMethod()=='post'){
			try{
                ## Read value
                $start = sanitizeString($this->request->getVar('start'));
                $rowperpage = sanitizeString($this->request->getVar('length')); // Rows display per page

                $columnIndex = sanitizeString($this->request->getVar('order')[0]['column']); // Column index
                $columnName = sanitizeString($this->request->getVar('columns')[$columnIndex]['data']); // Column name
                if ($columnName=="s_no" || $columnName=="ward_no" || $columnName=="total_demand")
                    $columnName = 'view_ward_mstr.ward_no';
                if ($columnName=="holding_no")
                    $columnName = 'tbl_prop_dtl.holding_no';
                if ($columnName=="owner_name")
                    $columnName = 'prop_owner_detail.owner_name';
                if ($columnName=="mobile_no")
                    $columnName = 'prop_owner_detail.mobile_no';
                if ($columnName=="address")
                    $columnName = 'tbl_prop_dtl.prop_address';

                
                //$columnName = "tbl_transaction.tran_date";
                $columnSortOrder = sanitizeString($this->request->getVar('order')[0]['dir']); // asc or desc
                $searchValue = sanitizeString($this->request->getVar('search')['value']); // Search value
                
                // Date filter
                $search_ward_mstr_id = sanitizeString($this->request->getVar('search_ward_mstr_id'));
                $searchQuery = "";
                $whereQuery = "";
                
                
                $orderBY = " ORDER BY ".$columnName." ".$columnSortOrder;
                $limit = " LIMIT ".$rowperpage." OFFSET ".$start;

                $currentFyID = $this->model_fy_mstr->getFyByFy(['fy'=>'2016-2017'])['id'];

                if ($search_ward_mstr_id != '') {
                    $whereQuery .= " AND tbl_prop_dtl.ward_mstr_id='".$search_ward_mstr_id."'";
                }
                
                $whereQueryWithSearch = "";
                if ($searchValue!='') {
                    $whereQueryWithSearch .= " AND (prop_owner_detail.owner_name ILIKE '%".$searchValue."%'
                                    OR prop_owner_detail.mobile_no ILIKE '%".$searchValue."%'
                                    OR tbl_prop_dtl.holding_no ILIKE '%".$searchValue."%'
									OR tbl_prop_dtl.new_holding_no ILIKE '%".$searchValue."%'
                                    OR view_ward_mstr.ward_no ILIKE '%".$searchValue."%'
                                    OR tbl_prop_dtl.prop_address ILIKE '%".$searchValue."%'
                                    OR tbl_prop_dtl.prop_city ILIKE '%".$searchValue."%'
                                    OR tbl_prop_dtl.prop_pin_code ILIKE '%".$searchValue."%')";
                }

                $base_url = base_url();
                $selectStatement = "SELECT 
                                    ROW_NUMBER () OVER (ORDER BY ".$columnName.") AS s_no,
                                    view_ward_mstr.ward_no,
                                    CONCAT('<a href=', chr(39), '".$base_url."/propdtl/full/', MD5(tbl_prop_dtl.id::TEXT), '/backbtnhide', chr(39), ' target=', chr(39), '_blank', chr(39), '><b><u>', tbl_prop_dtl.holding_no, '</u></b></a>') AS holding_no,
                                    tbl_prop_dtl.new_holding_no,
									prop_owner_detail.owner_name,
                                    prop_owner_detail.mobile_no,
                                    CONCAT(tbl_prop_dtl.prop_address, ', City - ', tbl_prop_dtl.prop_city, ', Pin Code - ', tbl_prop_dtl.prop_pin_code) AS address";
                $sql =  " FROM tbl_prop_dtl
                        INNER JOIN (SELECT prop_dtl_id, SUM(amount) AS c_total_demand FROM tbl_prop_demand WHERE status=1 AND fy_mstr_id>=".$currentFyID." AND paid_status=1 GROUP BY prop_dtl_id) AS c_prop_demand ON c_prop_demand.prop_dtl_id=tbl_prop_dtl.id
                        INNER JOIN (SELECT STRING_AGG(owner_name, ', ') AS owner_name, STRING_AGG(mobile_no::TEXT, ', ') AS mobile_no, prop_dtl_id FROM tbl_prop_owner_detail GROUP BY prop_dtl_id) prop_owner_detail ON prop_owner_detail.prop_dtl_id=tbl_prop_dtl.id
                        INNER JOIN view_ward_mstr ON view_ward_mstr.id=tbl_prop_dtl.ward_mstr_id
                        WHERE tbl_prop_dtl.status=1 AND view_ward_mstr.status=1".$whereQuery;
                
                ## Total number of records without filtering
                $totalRecords = $this->model_datatable->getTotalRecords($sql);
                //return json_encode($totalRecords);
                if ($totalRecords>0) {
                   
                    ## Total number of records with filtering
                    $totalRecordwithFilter = $this->model_datatable->getTotalRecordwithFilter($sql.$whereQueryWithSearch);
                    
                    ## Fetch records
                    $fetchSql = $selectStatement.$sql.$whereQueryWithSearch.$orderBY.$limit;
                    $records = $this->model_datatable->getRecords($fetchSql);
                } else {
                    $totalRecordwithFilter = 0;
                    $records = [];
                }
                
                //echo $this->db->getLastQuery();
                $response = array(
                    "iTotalRecords" => $totalRecords,
                    "iTotalDisplayRecords" => $totalRecordwithFilter,
                    "aaData" => $records
                );
                return json_encode($response);
            }catch(Exception $e){

            }
        }
    }

    public function notPaidFrom1617Excel($search_ward_mstr_id = null) {
        try{
            $whereQuery = "";
                if ($search_ward_mstr_id != 'ALL') {
                    $whereQuery .= " AND  tbl_prop_dtl.ward_mstr_id='".$search_ward_mstr_id."'";
                }

                $currentFyID = $this->model_fy_mstr->getFyByFy(['fy'=>'2016-2017'])['id'];

                $selectStatement = "SELECT 
                                    view_ward_mstr.ward_no,
                                    (CASE WHEN tbl_prop_dtl.holding_no!='' THEN tbl_prop_dtl.holding_no ELSE tbl_prop_dtl.new_holding_no END) AS holding_no,
                                    prop_owner_detail.owner_name,
                                    prop_owner_detail.mobile_no,
                                    CONCAT(tbl_prop_dtl.prop_address, ', City - ', tbl_prop_dtl.prop_city, ', Pin Code - ', tbl_prop_dtl.prop_pin_code) AS address";

                $sql = " FROM tbl_prop_dtl
                INNER JOIN (SELECT prop_dtl_id, SUM(amount) AS c_total_demand FROM tbl_prop_demand WHERE status=1 AND fy_mstr_id>=".$currentFyID." AND paid_status=1 GROUP BY prop_dtl_id) AS c_prop_demand ON c_prop_demand.prop_dtl_id=tbl_prop_dtl.id
                INNER JOIN (SELECT STRING_AGG(owner_name, ', ') AS owner_name, STRING_AGG(mobile_no::TEXT, ', ') AS mobile_no, prop_dtl_id FROM tbl_prop_owner_detail GROUP BY prop_dtl_id) prop_owner_detail ON prop_owner_detail.prop_dtl_id=tbl_prop_dtl.id
                INNER JOIN view_ward_mstr ON view_ward_mstr.id=tbl_prop_dtl.ward_mstr_id
                WHERE tbl_prop_dtl.status=1 AND view_ward_mstr.status=1".$whereQuery;

            $fetchSql = $selectStatement.$sql;
            $records = $this->model_datatable->getRecords($fetchSql);
            //phpOfficeLoad();
            $spreadsheet = new Spreadsheet();
            $activeSheet = $spreadsheet->getActiveSheet();
                            $activeSheet->setCellValue('A1', 'Ward No.');
                            $activeSheet->setCellValue('B1', 'Holding No.');
                            $activeSheet->setCellValue('C1', 'Applicant Name');
                            $activeSheet->setCellValue('D1', 'Mobile No.');
                            $activeSheet->setCellValue('E1', 'Address');
                            $activeSheet->fromArray($records, NULL, 'A2');
            $filename = "not_paid_from_16_17_".date('Ymd-hisa').".xlsx";
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="'.$filename.'"');
            header('Cache-Control: max-age=0');
            $writer = new Xlsx($spreadsheet);
            //$writer->save(APPPATH.'/hello world.xlsx');
            $writer->save('php://output');
        }catch(Exception $e){
            print_r($e);
        }
    }
	
	public function propIndividualDemandAndCollecton() {
        $ulb_mstr_id = 1;
        $wardList = $this->model_ward_mstr->getWardList(['ulb_mstr_id'=>$ulb_mstr_id]);
        $data['wardList'] = $wardList;
        return view('property/reports/prop_individual_demand_and_collecton', $data);
    }

    public function propIndividualDemandAndCollectonAjax() {
        if($this->request->getMethod()=='post'){
			try{
                ## Read value
                $start = sanitizeString($this->request->getVar('start'));
                $rowperpage = sanitizeString($this->request->getVar('length')); // Rows display per page

                $columnIndex = sanitizeString($this->request->getVar('order')[0]['column']); // Column index
                $columnName = sanitizeString($this->request->getVar('columns')[$columnIndex]['data']); // Column name
                if ($columnName=="s_no" || $columnName=="ward_no" || $columnName=="total_demand")
                    $columnName = 'view_ward_mstr.ward_no';
                if ($columnName=="holding_no")
                    $columnName = 'tbl_prop_dtl.holding_no';
                if ($columnName=="owner_name")
                    $columnName = 'prop_owner_detail.owner_name';
                if ($columnName=="mobile_no")
                    $columnName = 'prop_owner_detail.mobile_no';
                if ($columnName=="address")
                    $columnName = 'tbl_prop_dtl.prop_address';

                
                //$columnName = "tbl_transaction.tran_date";
                $columnSortOrder = sanitizeString($this->request->getVar('order')[0]['dir']); // asc or desc
                $searchValue = sanitizeString($this->request->getVar('search')['value']); // Search value
                
                // Date filter
                $search_ward_mstr_id = sanitizeString($this->request->getVar('search_ward_mstr_id'));
                $searchQuery = "";
                $whereQuery = "";
                
                
                $orderBY = " ORDER BY ".$columnName." ".$columnSortOrder;
                $limit = " LIMIT ".$rowperpage." OFFSET ".$start;

                $currentFyID = $this->model_fy_mstr->getFyByFy(['fy'=>'2016-2017'])['id'];

                if ($search_ward_mstr_id != '') {
                    $whereQuery .= " AND tbl_prop_dtl.ward_mstr_id='".$search_ward_mstr_id."'";
                }
                
                $whereQueryWithSearch = "";
                if ($searchValue!='') {
                    $whereQueryWithSearch .= " AND (prop_owner_detail.owner_name ILIKE '%".$searchValue."%'
                                    OR prop_owner_detail.mobile_no ILIKE '%".$searchValue."%'
                                    OR tbl_prop_dtl.holding_no ILIKE '%".$searchValue."%'
									OR tbl_prop_dtl.new_holding_no ILIKE '%".$searchValue."%'
                                    OR view_ward_mstr.ward_no ILIKE '%".$searchValue."%'
                                    OR tbl_prop_dtl.prop_address ILIKE '%".$searchValue."%'
                                    OR tbl_prop_dtl.prop_city ILIKE '%".$searchValue."%'
                                    OR tbl_prop_dtl.prop_pin_code ILIKE '%".$searchValue."%')";
                }
                $base_url = base_url();
                $selectStatement = "SELECT 
                                    ROW_NUMBER () OVER (ORDER BY ".$columnName.") AS s_no,
                                    view_ward_mstr.ward_no,
                                    CONCAT('<a href=', chr(39), '".$base_url."/propdtl/full/', MD5(tbl_prop_dtl.id::TEXT), '/backbtnhide', chr(39), ' target=', chr(39), '_blank', chr(39), '><b><u>', tbl_prop_dtl.holding_no, '</u></b></a>') AS holding_no,
                                    tbl_prop_dtl.new_holding_no,
									prop_owner_detail.owner_name,
                                    prop_owner_detail.mobile_no,
                                    CONCAT(tbl_prop_dtl.prop_address, ', City - ', tbl_prop_dtl.prop_city, ', Pin Code - ', tbl_prop_dtl.prop_pin_code) AS address,
                                    prop_demand.total_demand,
                                    prop_collection.total_collection,
                                    (prop_demand.total_demand-prop_collection.total_collection) AS total_remaining";
                $sql =  " FROM tbl_prop_dtl
                        INNER JOIN (SELECT prop_dtl_id, SUM(amount) AS total_demand FROM tbl_prop_demand WHERE status=1 GROUP BY prop_dtl_id) AS prop_demand ON prop_demand.prop_dtl_id=tbl_prop_dtl.id
                        INNER JOIN (SELECT prop_dtl_id, SUM(amount) AS total_collection FROM tbl_collection WHERE status=1 GROUP BY prop_dtl_id) AS prop_collection ON prop_collection.prop_dtl_id=tbl_prop_dtl.id
                        INNER JOIN (SELECT STRING_AGG(owner_name, ', ') AS owner_name, STRING_AGG(mobile_no::TEXT, ', ') AS mobile_no, prop_dtl_id FROM tbl_prop_owner_detail GROUP BY prop_dtl_id) prop_owner_detail ON prop_owner_detail.prop_dtl_id=tbl_prop_dtl.id
                        INNER JOIN view_ward_mstr ON view_ward_mstr.id=tbl_prop_dtl.ward_mstr_id
                        WHERE tbl_prop_dtl.status=1 AND view_ward_mstr.status=1".$whereQuery;
                
                ## Total number of records without filtering
                $totalRecords = $this->model_datatable->getTotalRecords($sql);
                //return json_encode($totalRecords);
                if ($totalRecords>0) {
                   
                    ## Total number of records with filtering
                    $totalRecordwithFilter = $this->model_datatable->getTotalRecordwithFilter($sql.$whereQueryWithSearch);
                    
                    ## Fetch records
                    $fetchSql = $selectStatement.$sql.$whereQueryWithSearch.$orderBY.$limit;
                    $records = $this->model_datatable->getRecords($fetchSql);
                } else {
                    $totalRecordwithFilter = 0;
                    $records = [];
                }
                
                //echo $this->db->getLastQuery();
                $response = array(
                    "iTotalRecords" => $totalRecords,
                    "iTotalDisplayRecords" => $totalRecordwithFilter,
                    "aaData" => $records
                );
                return json_encode($response);
            }catch(Exception $e){

            }
        }
    }

    public function propIndividualDemandAndCollectonExcel($search_ward_mstr_id = null) {
        try{
            $whereQuery = "";
                if ($search_ward_mstr_id != 'ALL') {
                    $whereQuery .= " AND  tbl_prop_dtl.ward_mstr_id='".$search_ward_mstr_id."'";
                }

                $currentFyID = $this->model_fy_mstr->getFyByFy(['fy'=>'2016-2017'])['id'];

                $selectStatement = "SELECT 
                                    view_ward_mstr.ward_no,
                                    concat('`',CASE WHEN tbl_prop_dtl.holding_no!='' THEN tbl_prop_dtl.holding_no ELSE tbl_prop_dtl.new_holding_no END) AS holding_no,
                                    prop_owner_detail.owner_name,
                                    prop_owner_detail.mobile_no,
                                    CONCAT(tbl_prop_dtl.prop_address, ', City - ', tbl_prop_dtl.prop_city, ', Pin Code - ', tbl_prop_dtl.prop_pin_code) AS address,
                                    prop_demand.total_demand,
                                    prop_collection.total_collection,
                                    (prop_demand.total_demand-prop_collection.total_collection) AS total_remaining";

                $sql = " FROM tbl_prop_dtl
                        INNER JOIN (SELECT prop_dtl_id, SUM(amount) AS total_demand FROM tbl_prop_demand WHERE status=1 GROUP BY prop_dtl_id) AS prop_demand ON prop_demand.prop_dtl_id=tbl_prop_dtl.id
                        INNER JOIN (SELECT prop_dtl_id, SUM(amount) AS total_collection FROM tbl_collection WHERE status=1 GROUP BY prop_dtl_id) AS prop_collection ON prop_collection.prop_dtl_id=tbl_prop_dtl.id
                        INNER JOIN (SELECT STRING_AGG(owner_name, ', ') AS owner_name, STRING_AGG(mobile_no::TEXT, ', ') AS mobile_no, prop_dtl_id FROM tbl_prop_owner_detail GROUP BY prop_dtl_id) prop_owner_detail ON prop_owner_detail.prop_dtl_id=tbl_prop_dtl.id
                        INNER JOIN view_ward_mstr ON view_ward_mstr.id=tbl_prop_dtl.ward_mstr_id
                        WHERE tbl_prop_dtl.status=1 AND view_ward_mstr.status=1".$whereQuery;

            $fetchSql = $selectStatement.$sql;
            $records = $this->model_datatable->getRecords($fetchSql);
            //phpOfficeLoad();
            $spreadsheet = new Spreadsheet();
            $activeSheet = $spreadsheet->getActiveSheet();
                            $activeSheet->setCellValue('A1', 'Ward No.');
                            $activeSheet->setCellValue('B1', 'Holding No.');
                            $activeSheet->setCellValue('C1', 'Applicant Name');
                            $activeSheet->setCellValue('D1', 'Mobile No.');
                            $activeSheet->setCellValue('E1', 'Address');
                            $activeSheet->setCellValue('F1', 'Total Demand');
                            $activeSheet->setCellValue('G1', 'Total Collection');
                            $activeSheet->setCellValue('H1', 'Total Remaining');
                            $activeSheet->fromArray($records, NULL, 'A2');
            $filename = "prop_individual_demand_and_collecton_".date('Ymd-hisa').".xlsx";
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="'.$filename.'"');
            header('Cache-Control: max-age=0');
            $writer = new Xlsx($spreadsheet);
            //$writer->save(APPPATH.'/hello world.xlsx');
            $writer->save('php://output');
        }catch(Exception $e){
            print_r($e);
        }
    }

    public function deactivatedHolding($params="old") {
        $ulb_mstr_id = 1;
        $wardList = $this->model_ward_mstr->getWardList(['ulb_mstr_id'=>$ulb_mstr_id]);
        $data['wardList'] = $wardList;
        $data["params"]=$params;
        return view('property/reports/deactivated_holding', $data);
    }

    public function deactivatedHoldingAjax($params="old") {
        if($this->request->getMethod()=='post'){
			try{
                ## Read value
                $start = sanitizeString($this->request->getVar('start'));
                $rowperpage = sanitizeString($this->request->getVar('length')); // Rows display per page

                $columnIndex = sanitizeString($this->request->getVar('order')[0]['column']); // Column index
                $columnName = sanitizeString($this->request->getVar('columns')[$columnIndex]['data']); // Column name
                if ($columnName=="s_no" || $columnName=="ward_no" || $columnName=="total_demand")
                    $columnName = 'view_ward_mstr.ward_no';
                if ($columnName=="holding_no")
                    $columnName = 'tbl_prop_dtl.holding_no';
                if ($columnName=="owner_name")
                    $columnName = 'prop_owner_detail.owner_name';
                if ($columnName=="mobile_no")
                    $columnName = 'prop_owner_detail.mobile_no';
                if ($columnName=="address")
                    $columnName = 'tbl_prop_dtl.prop_address';

                
                //$columnName = "tbl_transaction.tran_date";
                $columnSortOrder = sanitizeString($this->request->getVar('order')[0]['dir']); // asc or desc
                $searchValue = sanitizeString($this->request->getVar('search')['value']); // Search value
                
                // Date filter
                $search_ward_mstr_id = sanitizeString($this->request->getVar('search_ward_mstr_id'));
                $searchQuery = "";
                $whereQuery = "";
                
                
                $orderBY = " ORDER BY ".$columnName." ".$columnSortOrder;
                $limit = " LIMIT ".$rowperpage." OFFSET ".$start;

                $currentFyID = $this->model_fy_mstr->getFyByFy(['fy'=>'2016-2017'])['id'];

                if ($search_ward_mstr_id != '') {
                    $whereQuery .= " AND tbl_prop_dtl.ward_mstr_id='".$search_ward_mstr_id."'";
                }
                
                $whereQueryWithSearch = "";
                if ($searchValue!='') {
                    $whereQueryWithSearch .= " AND (prop_owner_detail.owner_name ILIKE '%".$searchValue."%'
                                    OR prop_owner_detail.mobile_no ILIKE '%".$searchValue."%'
                                    OR tbl_prop_dtl.holding_no ILIKE '%".$searchValue."%'
                                    OR view_ward_mstr.ward_no ILIKE '%".$searchValue."%'
                                    OR tbl_prop_dtl.prop_address ILIKE '%".$searchValue."%'
                                    OR tbl_prop_dtl.prop_city ILIKE '%".$searchValue."%'
                                    OR tbl_prop_dtl.prop_pin_code ILIKE '%".$searchValue."%')";
                }
                
                $selectStatement = "SELECT 
                                    ROW_NUMBER () OVER (ORDER BY ".$columnName.") AS s_no,
                                    view_ward_mstr.ward_no,
                                    (CASE WHEN tbl_prop_dtl.holding_no!='' THEN tbl_prop_dtl.holding_no ELSE tbl_prop_dtl.new_holding_no END) AS holding_no,
									prop_owner_detail.owner_name,
                                    prop_owner_detail.mobile_no,
                                    CONCAT(tbl_prop_dtl.prop_address, ', City - ', tbl_prop_dtl.prop_city, ', Pin Code - ', tbl_prop_dtl.prop_pin_code) AS address";
                $sql =  " FROM tbl_prop_dtl
                        LEFT JOIN (SELECT STRING_AGG(owner_name, ', ') AS owner_name, STRING_AGG(mobile_no::TEXT, ', ') AS mobile_no, prop_dtl_id FROM tbl_prop_owner_detail GROUP BY prop_dtl_id) prop_owner_detail ON prop_owner_detail.prop_dtl_id=tbl_prop_dtl.id
                        INNER JOIN view_ward_mstr ON view_ward_mstr.id=tbl_prop_dtl.ward_mstr_id
                        WHERE (tbl_prop_dtl.status=0 AND view_ward_mstr.status=1".($params=="new"?" AND CHAR_LENGTH(tbl_prop_dtl.new_holding_no)>0 ":" ").$whereQuery.")
                            OR tbl_prop_dtl.id IN(292128 , 290945 , 290943 , 287414 , 286989 , 286986 , 286984 , 286980 , 286978 , 286974 , 286969 , 286967 , 286966 , 286965 , 
                                286964 , 286963 , 286960 , 286958 , 286954 , 286952 , 286950 , 286609 , 284784 , 284713 , 284565 , 284563 , 284189 , 283368 , 
                                278545 , 278492 , 278467 , 278445 , 276809 , 274029 , 274028 , 272645 , 271309 , 270437 , 270369 , 270357 , 268611 , 268610 , 
                                268594 , 268561 , 268540 , 268539 , 268538 , 268537 , 268536 , 268535 , 268518 , 268507 , 268499 , 268491 , 268490 , 268485 , 
                                268446 , 268445 , 268234 , 267873 , 267872 , 267871 , 267788 , 267754 , 267753 , 267752 , 267751 , 267658 , 267607 , 267606 , 
                                266833 , 266819 , 266818 , 266816 , 266791 , 266459 , 266457 , 266456 , 266436 , 266435 , 266429 , 266428 , 266427 , 266426 , 
                                266417 , 266405 , 266403 , 266392 , 266342 , 266341 , 266340 , 266338 , 266337 , 266310 , 266309 , 266308 , 266303 , 266298 , 
                                264114 , 264112 , 264085 , 264023 , 264022 , 264021 , 262036 , 259003 , 58679 , 37112 , 25588 , 22561 ,
                                --new,
                                654 , 700 , 783 , 896 , 889 , 890 , 898 , 603 , 833 , 522 , 733 , 604 , 737 , 830 , 
                                755 , 726 , 893 , 500 , 791 , 802 , 834 , 635 , 883 , 852 , 741 , 897 , 900 , 792 , 
                                880 , 642 , 560 , 558 , 795 , 859 , 855 , 524 , 870 , 877 , 776 , 561 , 798 , 797 , 
                                605 , 760 , 799 , 838 , 862 , 824 , 547 , 520 , 501 , 886 , 827 , 864 , 875 , 735 , 
                                817 , 747 , 788 , 823 
                            )
                        ";
                
                ## Total number of records without filtering
                $totalRecords = $this->model_datatable->getTotalRecords($sql);
                //return json_encode($totalRecords);
                if ($totalRecords>0) {
                   
                    ## Total number of records with filtering
                    $totalRecordwithFilter = $this->model_datatable->getTotalRecordwithFilter($sql.$whereQueryWithSearch);
                    
                    ## Fetch records
                    $fetchSql = $selectStatement.$sql.$whereQueryWithSearch.$orderBY.$limit;
                    $records = $this->model_datatable->getRecords($fetchSql);
                } else {
                    $totalRecordwithFilter = 0;
                    $records = [];
                }
                
                //echo $this->db->getLastQuery();
                $response = array(
                    "iTotalRecords" => $totalRecords,
                    "iTotalDisplayRecords" => $totalRecordwithFilter,
                    "aaData" => $records
                );
                return json_encode($response);
            }catch(Exception $e){

            }
        }
    }

    public function deactivatedHoldingExcel($params="old",$search_ward_mstr_id = null) {
        try{
            $whereQuery = "";
                if ($search_ward_mstr_id != 'ALL') {
                    $whereQuery .= " AND  tbl_prop_dtl.ward_mstr_id='".$search_ward_mstr_id."'";
                }

                $currentFyID = $this->model_fy_mstr->getFyByFy(['fy'=>'2016-2017'])['id'];

                $selectStatement = "SELECT 
                                    view_ward_mstr.ward_no,
                                    (CASE WHEN tbl_prop_dtl.holding_no!='' THEN tbl_prop_dtl.holding_no ELSE tbl_prop_dtl.new_holding_no END) AS holding_no,
                                    tbl_prop_dtl.new_holding_no,
									prop_owner_detail.owner_name,
                                    prop_owner_detail.mobile_no,
                                    CONCAT(tbl_prop_dtl.prop_address, ', City - ', tbl_prop_dtl.prop_city, ', Pin Code - ', tbl_prop_dtl.prop_pin_code) AS address";

                $sql = " FROM tbl_prop_dtl
                        LEFT JOIN (SELECT STRING_AGG(owner_name, ', ') AS owner_name, STRING_AGG(mobile_no::TEXT, ', ') AS mobile_no, prop_dtl_id FROM tbl_prop_owner_detail GROUP BY prop_dtl_id) prop_owner_detail ON prop_owner_detail.prop_dtl_id=tbl_prop_dtl.id
                        INNER JOIN view_ward_mstr ON view_ward_mstr.id=tbl_prop_dtl.ward_mstr_id
                        WHERE ( tbl_prop_dtl.status=0 AND view_ward_mstr.status=1".($params=="new"?" AND CHAR_LENGTH(tbl_prop_dtl.new_holding_no)>0 ":" ").$whereQuery.")
                            OR tbl_prop_dtl.id IN(292128 , 290945 , 290943 , 287414 , 286989 , 286986 , 286984 , 286980 , 286978 , 286974 , 286969 , 286967 , 286966 , 286965 , 
                                286964 , 286963 , 286960 , 286958 , 286954 , 286952 , 286950 , 286609 , 284784 , 284713 , 284565 , 284563 , 284189 , 283368 , 
                                278545 , 278492 , 278467 , 278445 , 276809 , 274029 , 274028 , 272645 , 271309 , 270437 , 270369 , 270357 , 268611 , 268610 , 
                                268594 , 268561 , 268540 , 268539 , 268538 , 268537 , 268536 , 268535 , 268518 , 268507 , 268499 , 268491 , 268490 , 268485 , 
                                268446 , 268445 , 268234 , 267873 , 267872 , 267871 , 267788 , 267754 , 267753 , 267752 , 267751 , 267658 , 267607 , 267606 , 
                                266833 , 266819 , 266818 , 266816 , 266791 , 266459 , 266457 , 266456 , 266436 , 266435 , 266429 , 266428 , 266427 , 266426 , 
                                266417 , 266405 , 266403 , 266392 , 266342 , 266341 , 266340 , 266338 , 266337 , 266310 , 266309 , 266308 , 266303 , 266298 , 
                                264114 , 264112 , 264085 , 264023 , 264022 , 264021 , 262036 , 259003 , 58679 , 37112 , 25588 , 22561 ,
                                --new,
                                654 , 700 , 783 , 896 , 889 , 890 , 898 , 603 , 833 , 522 , 733 , 604 , 737 , 830 , 
                                755 , 726 , 893 , 500 , 791 , 802 , 834 , 635 , 883 , 852 , 741 , 897 , 900 , 792 , 
                                880 , 642 , 560 , 558 , 795 , 859 , 855 , 524 , 870 , 877 , 776 , 561 , 798 , 797 , 
                                605 , 760 , 799 , 838 , 862 , 824 , 547 , 520 , 501 , 886 , 827 , 864 , 875 , 735 , 
                                817 , 747 , 788 , 823 
                            )
                        ";

            $fetchSql = $selectStatement.$sql;
            $records = $this->model_datatable->getRecords($fetchSql);
            //phpOfficeLoad();
            $spreadsheet = new Spreadsheet();
            $activeSheet = $spreadsheet->getActiveSheet();
                            $activeSheet->setCellValue('A1', 'Ward No.');
                            $activeSheet->setCellValue('B1', 'Holding No.');
                            $activeSheet->setCellValue('C1', 'Applicant Name');
                            $activeSheet->setCellValue('D1', 'Mobile No.');
                            $activeSheet->setCellValue('E1', 'Address');
                            $activeSheet->fromArray($records, NULL, 'A2');
            $filename = "deactivated_holding_".date('Ymd-hisa').".xlsx";
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="'.$filename.'"');
            header('Cache-Control: max-age=0');
            $writer = new Xlsx($spreadsheet);
            //$writer->save(APPPATH.'/hello world.xlsx');
            $writer->save('php://output');
        }catch(Exception $e){
            print_r($e);
        }
    }

    // public function wardWiseDCB()
    // {
    //     $ulb_mstr_id = 1;
    //     $wardList = $this->model_ward_mstr->getWardList(['ulb_mstr_id'=>$ulb_mstr_id]);
    //     $currentFY = getFY();
    //     $currentFyID = $this->model_fy_mstr->getFyByFy(['fy'=>$currentFY])['id'];
    //     $fyList = $this->model_fy_mstr->getFYListDescBefore10Year($currentFyID);
    //     if($this->request->getMethod()=='post')
    //     {
    //         try {
    //             $data = arrFilterSanitizeString($this->request->getVar());
    //             //print_r($data);
    //             $WhereFYCurrent = "";
    //             $WhereFYArrear = "";
    //             if($data['fy_mstr_id']!='') {
    //                 $WhereFYCurrent = " AND tbl_prop_demand.fy_mstr_id=".$data['fy_mstr_id'];
    //                 $WhereFYArrear = " AND tbl_prop_demand.fy_mstr_id<".$data['fy_mstr_id'];
    //                 $fyear = explode('-',$this->model_fy_mstr->getFyearByFyid($data['fy_mstr_id'])['fy']);
                    
    //                 $fromYear = $fyear[0].'-04-01';
    //                 $uptoYear = $fyear[1].'-03-31';
    //             }
    //             $whereWard = "";
    //             $whereWard0 = "";
    //             $whereWard1 = "";
    //             if($data['ward_mstr_id']!='') {
    //                 $whereWard = " AND tbl_prop_dtl.ward_mstr_id=".$data['ward_mstr_id'];
    //                 $whereWard1 = " AND tbl_prop_demand.ward_mstr_id=".$data['ward_mstr_id'];
    //                 $whereWard0 = "where view_ward_mstr.id=".$data['ward_mstr_id'];
    //                 // print_r($data['ward_mstr_id']);
    //                 // return;
    //             }
    //             $holding_type = "";
    //             if (isset($data['residencial'])
    //                 || isset($data['non_residential'])
    //                 || isset($data['vacant_land'])
    //                 || isset($data['government_building'])) {

    //                 if (isset($data['residencial'])) {
    //                     $holding_type .= "'PURE_RESIDENTIAL'";
    //                 }
    //                 if (isset($data['non_residential'])) {
    //                     if ($holding_type!='') {
    //                         $holding_type .= ", ";
    //                     }
    //                     $holding_type .= "'PURE_COMMERCIAL', 'MIX_COMMERCIAL'";
    //                 }
    //                 if (isset($data['government_building'])) {
    //                     if ($holding_type!='') {
    //                         $holding_type .= ", ";
    //                     }
    //                     $holding_type .= "'PURE_GOVERNMENT', 'MIX_GOVERNMENT'";
    //                 }

    //                 if (isset($data['vacant_land'])) {
    //                     if ($holding_type!='') {
    //                         $holding_type .= ", ";
    //                     }
    //                     $holding_type .= "'VACANT_LAND'";
    //                 }
    //                 if ($holding_type!='') {
    //                     $holding_type = " AND tbl_prop_dtl.holding_type IN (".$holding_type.")";
    //                 }
    //             }
    //             //$dd = "PURE_RESIDENTIAL, PURE_COMMERCIAL, PURE_GOVERNMENT, MIX_GOVERNMENT, MIX_COMMERCIAL";                
    //             // By Hayat
    //             $sql = "WITH ARREAR_DEMAND AS (
    //                 SELECT 
    //                     tbl_prop_dtl.ward_mstr_id,
    //                     SUM(tbl_prop_demand.amount - tbl_prop_demand.adjust_amt) AS arrear_demand
    //                 FROM tbl_prop_demand
    //                 INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id
    //                 WHERE 
    //                     tbl_prop_dtl.status=1 ".$holding_type." 
    //                     AND tbl_prop_demand.status=1 
    //                     AND tbl_prop_demand.paid_status=0 and char_length(tbl_prop_dtl.new_holding_no)>0 and tbl_prop_demand.due_date is not null ".$whereWard.$WhereFYArrear." 
    //                     GROUP BY tbl_prop_dtl.ward_mstr_id
    //             ),
    //             CURRENT_DEMAND AS (
    //                 SELECT 
    //                     tbl_prop_dtl.ward_mstr_id,
    //                     count(tbl_prop_dtl.id) AS current_holding,
    //                     SUM(tbl_prop_demand.amount - tbl_prop_demand.adjust_amt) AS current_demand
    //                 FROM tbl_prop_dtl
    //                 INNER JOIN (
    //                     select prop_dtl_id, sum(amount) as amount, sum(adjust_amt) as adjust_amt from tbl_prop_demand where tbl_prop_demand.status=1 
    //                     AND tbl_prop_demand.paid_status IN (0,1) and tbl_prop_demand.due_date is not null  ".$whereWard1.$WhereFYCurrent." 
    //                     group by prop_dtl_id
    //                 ) tbl_prop_demand ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id
    //                 WHERE tbl_prop_dtl.status=1 and char_length(tbl_prop_dtl.new_holding_no)>0   ".$holding_type."
    //                 GROUP BY tbl_prop_dtl.ward_mstr_id

    //             ),
    //             ARREAR_COLLECTION AS (
    //                 SELECT 
    //                     tbl_prop_dtl.ward_mstr_id,
    //                     SUM(tbl_prop_demand.amount - tbl_prop_demand.adjust_amt) AS arrear_collection_amount
    //                 FROM tbl_prop_demand
    //                 INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id
    //                 INNER JOIN tbl_collection on tbl_collection.prop_demand_id=tbl_prop_demand.id AND (tbl_collection.created_on::date between '".$fromYear."' and '".$uptoYear."') 
    //                 WHERE tbl_prop_dtl.status=1 ".$holding_type." and tbl_prop_demand.status=1 and char_length(tbl_prop_dtl.new_holding_no)>0 ".$whereWard.$WhereFYArrear." AND tbl_prop_demand.paid_status=1
    //                 GROUP BY tbl_prop_dtl.ward_mstr_id
    //             ),
    //             CURRENT_COLLECTION AS (
    //                 SELECT 
    //                     tbl_prop_dtl.ward_mstr_id,
    //                     count(tbl_prop_dtl.id) AS collection_from_no_of_HH,
    //                     SUM(tbl_prop_demand.amount - tbl_prop_demand.adjust_amt) AS current_collection_amount
    //                 FROM tbl_prop_dtl
    //                 INNER JOIN (
    //                     select prop_dtl_id, sum(amount) as amount, sum(adjust_amt) as adjust_amt from tbl_prop_demand where tbl_prop_demand.status=1 
    //                     AND tbl_prop_demand.paid_status=1 AND tbl_prop_demand.status=1 ".$whereWard1.$WhereFYCurrent." 
    //                     group by prop_dtl_id
    //                 ) tbl_prop_demand ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id
    //                 WHERE tbl_prop_dtl.status=1 and char_length(tbl_prop_dtl.new_holding_no)>0 ".$holding_type."
    //                 GROUP BY tbl_prop_dtl.ward_mstr_id
    //             ),
    //             ADVANCE_AMOUNT AS (
    //                 SELECT 
    //                     tbl_prop_dtl.ward_mstr_id,
    //                     count(tbl_prop_dtl.id) AS collection_from_no_of_HH,
    //                     SUM(amount) AS adv_amount
    //                 FROM tbl_prop_dtl
    //                 INNER JOIN (
    //                     select prop_dtl_id, sum(amount) as amount from tbl_advance_mstr where tbl_advance_mstr.status=1 
    //                     AND tbl_advance_mstr.created_on::date>='2023-03-01' and module='Property' and user_id=1
    //                     group by prop_dtl_id
    //                 ) tbl_advance_mstr ON tbl_prop_dtl.id=tbl_advance_mstr.prop_dtl_id
    //                 WHERE tbl_prop_dtl.status=1 and char_length(tbl_prop_dtl.new_holding_no)>0 ".$holding_type." ".$whereWard."
    //                 GROUP BY tbl_prop_dtl.ward_mstr_id
    //             )
    //             SELECT 
    //                 view_ward_mstr.ward_no,
    //                 COALESCE(CURRENT_DEMAND.current_holding, 0) as current_holding,
    //                 COALESCE(ARREAR_DEMAND.arrear_demand, 0) AS arrear_demand,
    //                 COALESCE(CURRENT_DEMAND.current_demand, 0) AS current_demand,
    //                 COALESCE(ARREAR_DEMAND.arrear_demand, 0)+COALESCE(CURRENT_DEMAND.current_demand, 0) AS total_demand,
    //                 COALESCE(CURRENT_COLLECTION.collection_from_no_of_HH, 0) as collection_from_no_of_HH,
    //                 COALESCE(ARREAR_COLLECTION.arrear_collection_amount, 0) AS arrear_collection_amount,
    //                 COALESCE(CURRENT_COLLECTION.current_collection_amount, 0) AS current_collection_amount,
    //                 COALESCE(ARREAR_COLLECTION.arrear_collection_amount, 0)+COALESCE(CURRENT_COLLECTION.current_collection_amount, 0) AS total_collection_amount,
    //                 COALESCE(CURRENT_DEMAND.current_holding, 0)-COALESCE(CURRENT_COLLECTION.collection_from_no_of_HH, 0) AS balance_hh,
    //                 COALESCE(ARREAR_DEMAND.arrear_demand, 0)-COALESCE(ARREAR_COLLECTION.arrear_collection_amount, 0) AS arrear_balance_amount,
    //                 COALESCE(CURRENT_DEMAND.current_demand, 0)-COALESCE(CURRENT_COLLECTION.current_collection_amount, 0) AS current_balance_amount,
    //                 COALESCE((ARREAR_DEMAND.arrear_demand-ARREAR_COLLECTION.arrear_collection_amount)+(CURRENT_DEMAND.current_demand-CURRENT_COLLECTION.current_collection_amount), 0) AS total_balance_amount,
    //                 (CASE 
    //                     WHEN CURRENT_COLLECTION.collection_from_no_of_HH IS NULL OR CURRENT_COLLECTION.collection_from_no_of_HH=0 THEN 0
    //                     ELSE
    //                         ROUND((COALESCE(CURRENT_COLLECTION.collection_from_no_of_HH, 0)*100)/COALESCE(CURRENT_DEMAND.current_holding, 0), 2)
    //                 END) AS hh_percentage,
    //                 (CASE
    //                     WHEN ARREAR_COLLECTION.arrear_collection_amount IS NULL OR ARREAR_COLLECTION.arrear_collection_amount=0 THEN 0
    //                     WHEN CURRENT_COLLECTION.current_collection_amount IS NULL OR CURRENT_COLLECTION.current_collection_amount=0 THEN 0
    //                     WHEN COALESCE(ARREAR_DEMAND.arrear_demand+CURRENT_DEMAND.current_demand, 0)=0 THEN 0
    //                     ELSE
    //                         ROUND((COALESCE(ARREAR_COLLECTION.arrear_collection_amount+CURRENT_COLLECTION.current_collection_amount, 0)*100)/COALESCE(ARREAR_DEMAND.arrear_demand+CURRENT_DEMAND.current_demand, 0), 2)
    //                 END) AS amount_percentage,
	// 				".
    //                 ($data['fy_mstr_id'] == 54?"COALESCE(ADVANCE_AMOUNT.adv_amount, 0)":"0")
	// 				." as advance_amount
    //             FROM view_ward_mstr
    //             LEFT JOIN ARREAR_DEMAND ON ARREAR_DEMAND.ward_mstr_id=view_ward_mstr.id
    //             LEFT JOIN CURRENT_DEMAND ON CURRENT_DEMAND.ward_mstr_id=view_ward_mstr.id
    //             LEFT JOIN ARREAR_COLLECTION ON ARREAR_COLLECTION.ward_mstr_id=view_ward_mstr.id
    //             LEFT JOIN CURRENT_COLLECTION ON CURRENT_COLLECTION.ward_mstr_id=view_ward_mstr.id
    //             LEFT JOIN ADVANCE_AMOUNT ON ADVANCE_AMOUNT.ward_mstr_id=view_ward_mstr.id
    //             $whereWard0
    //             ORDER BY SUBSTRING(view_ward_mstr.ward_no FROM '([0-9]+)')::BIGINT ASC, view_ward_mstr.ward_no";
    //             //print_var($sql);
    //             $builder = $this->db->query($sql);
    //             if ($report_list = $builder->getResultArray()) {
    //                 $data['report_list'] = $report_list;
    //             }
    //             //echo $this->db->getLastQuery();
    //         }catch(Exception $e){
    //             print_r($e);
    //         }
    //     }
    //     $data['wardList'] = $wardList;
    //     $data['fyList'] = $fyList;

    //     return view('property/reports/ward_wise_DCB_report', $data);
    // }

    public function wardWiseDCB_old_1()
    {
        
        $ulb_mstr_id = 1;
        $wardList = $this->model_ward_mstr->getWardList(['ulb_mstr_id'=>$ulb_mstr_id]);
        $currentFY = getFY();
        $currentFyID = $this->model_fy_mstr->getFyByFy(['fy'=>$currentFY])['id'];
        $fyList = $this->model_fy_mstr->getFYListDescBefore10Year($currentFyID);
        if($this->request->getMethod()=='post')
        {
            try {
                $data = arrFilterSanitizeString($this->request->getVar());
                //print_r($data);
                $WhereFYCurrent = "";
                $WhereFYArrear = "";
                $Where22_23 = "";
                $propDemand = "";
                if($data['fy_mstr_id']!='') {
                    if($data['fy_mstr_id']==53) #  for current demand
                    {

                        $Where22_23 =" AND tbl_prop_demand.fy_mstr_id=54";
                        $propDemand = "prop_demand as(
                                            select SUM(COALESCE(tbl_prop_demand.amount, 0) - COALESCE(tbl_prop_demand.adjust_amt, 0)) as arrear_demand,tbl_prop_demand.prop_dtl_id from tbl_prop_demand
                                            INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id
                                            where tbl_prop_demand.status=1 and tbl_prop_demand.fy_mstr_id=53 and tbl_prop_dtl.status=1 and tbl_prop_demand.status=1 
                                            and char_length(tbl_prop_dtl.new_holding_no)>0  and (tbl_prop_dtl.govt_saf_dtl_id is null or tbl_prop_dtl.govt_saf_dtl_id=0)
                                            group by tbl_prop_demand.prop_dtl_id
                                        ),";
                    }
                    elseif($data['fy_mstr_id']==54) #for  earrir Deman
                    {
                        $Where22_23 ="";
                        $propDemand = "prop_demand as(
                            select SUM(COALESCE(tbl_prop_demand.amount, 0) - COALESCE(tbl_prop_demand.adjust_amt, 0)) as arrear_demand,tbl_prop_demand.prop_dtl_id 
                            ,tbl_prop_dtl.ward_mstr_id
                            from tbl_prop_demand
                            INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id
                            where tbl_prop_demand.status=1 and tbl_prop_demand.fy_mstr_id=54 and tbl_prop_dtl.status=1 and tbl_prop_demand.status=1 
                            and char_length(tbl_prop_dtl.new_holding_no)>0  and (tbl_prop_dtl.govt_saf_dtl_id is null or tbl_prop_dtl.govt_saf_dtl_id=0)
                            group by tbl_prop_demand.prop_dtl_id,tbl_prop_dtl.ward_mstr_id
                        ),";
                    }
                    elseif($data['fy_mstr_id']==55) #for  earrir Deman
                    {
                        // $Where22_23 =" AND tbl_prop_demand.fy_mstr_id=55";
                        $Where22_23 ="";
                        $propDemand = "prop_demand as(
                            select SUM(COALESCE(tbl_prop_demand.amount, 0) - COALESCE(tbl_prop_demand.adjust_amt, 0)) as arrear_demand,tbl_prop_demand.prop_dtl_id 
                            ,tbl_prop_dtl.ward_mstr_id
                            from tbl_prop_demand
                            INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id
                            where tbl_prop_demand.status=1 and tbl_prop_demand.fy_mstr_id=55 and tbl_prop_dtl.status=1 and tbl_prop_demand.status=1 
                            and char_length(tbl_prop_dtl.new_holding_no)>0  and (tbl_prop_dtl.govt_saf_dtl_id is null or tbl_prop_dtl.govt_saf_dtl_id=0)
                            group by tbl_prop_demand.prop_dtl_id,tbl_prop_dtl.ward_mstr_id
                        ),";
                    }

                    $WhereFYCurrent = " AND tbl_prop_demand.fy_mstr_id=".$data['fy_mstr_id'];
                    $WhereFYArrear = " AND tbl_prop_demand.fy_mstr_id<".$data['fy_mstr_id'];
                    $fyear = explode('-',$this->model_fy_mstr->getFyearByFyid($data['fy_mstr_id'])['fy']);
                    
                    $fromYear = $fyear[0].'-04-01';
                    $uptoYear = $fyear[1].'-03-31';
                }
                $whereWard = "";
                $whereWard0 = "";
                $whereWard1 = "";
                if($data['ward_mstr_id']!='') {
                    $whereWard = " AND tbl_prop_dtl.ward_mstr_id=".$data['ward_mstr_id'];
                    $whereWard1 = " AND tbl_prop_demand.ward_mstr_id=".$data['ward_mstr_id'];
                    $whereWard0 = "where view_ward_mstr.id=".$data['ward_mstr_id'];
                    // print_r($data['ward_mstr_id']);
                    // return;
                }
                $holding_type = "";
                if (isset($data['residencial'])
                    || isset($data['non_residential'])
                    || isset($data['vacant_land'])
                    || isset($data['government_building'])) {

                    if (isset($data['residencial'])) {
                        $holding_type .= "'PURE_RESIDENTIAL'";
                    }
                    if (isset($data['non_residential'])) {
                        if ($holding_type!='') {
                            $holding_type .= ", ";
                        }
                        $holding_type .= "'PURE_COMMERCIAL', 'MIX_COMMERCIAL'";
                    }
                    if (isset($data['government_building'])) {
                        if ($holding_type!='') {
                            $holding_type .= ", ";
                        }
                        $holding_type .= "'PURE_GOVERNMENT', 'MIX_GOVERNMENT'";
                    }

                    if (isset($data['vacant_land'])) {
                        if ($holding_type!='') {
                            $holding_type .= ", ";
                        }
                        $holding_type .= "'VACANT_LAND'";
                    }
                    if ($holding_type!='') {
                        $holding_type = " AND tbl_prop_dtl.holding_type IN (".$holding_type.")";
                    }
                }
                //$dd = "PURE_RESIDENTIAL, PURE_COMMERCIAL, PURE_GOVERNMENT, MIX_GOVERNMENT, MIX_COMMERCIAL";                
                // By Hayat
                $AREA_DEMAND = "ARREAR_DEMAND AS (
                    SELECT 
                        tbl_prop_dtl.ward_mstr_id,
                        SUM(COALESCE(tbl_prop_demand.amount, 0) - COALESCE(tbl_prop_demand.adjust_amt, 0)) AS arrear_demand
                    FROM tbl_prop_demand
                    INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id
                    WHERE 
                        tbl_prop_dtl.status=1 ".$holding_type." 
                        AND tbl_prop_demand.status=1 
                        AND tbl_prop_demand.paid_status IN(0,1) and char_length(tbl_prop_dtl.new_holding_no)>0 and (tbl_prop_dtl.govt_saf_dtl_id is null or tbl_prop_dtl.govt_saf_dtl_id=0) and tbl_prop_demand.due_date is not null ".$whereWard.$WhereFYArrear." 
                        GROUP BY tbl_prop_dtl.ward_mstr_id
                ),";
                if($data['fy_mstr_id']==54 || $data['fy_mstr_id']==55)
                {
                    $fact=2.98;
                    if($data['fy_mstr_id']==55){
                        $fact=10.9;
                    }
                    $WhereFYArrear2 = " AND tbl_prop_demand.fy_mstr_id<".($data['fy_mstr_id']-1);
                  
                    $AREA_DEMAND = "ARREAR_DEMAND AS (
                                        SELECT curent.ward_mstr_id,
                                            SUM(curent.arrear_demand-(curent.arrear_demand*$fact/100)) arrear_demand 
                                        FROM (
                                            SELECT 
                                                tbl_prop_dtl.ward_mstr_id,
                                                SUM(COALESCE(tbl_prop_demand.amount, 0) - COALESCE(tbl_prop_demand.adjust_amt, 0)) AS arrear_demand
                                            FROM tbl_prop_demand
                                            INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id
                                            WHERE 
                                                tbl_prop_dtl.status=1 ".$holding_type." 
                                                AND tbl_prop_demand.status=1 
                                                AND tbl_prop_demand.paid_status IN(0,1) and char_length(tbl_prop_dtl.new_holding_no)>0 and tbl_prop_demand.due_date is not null ".$whereWard.$WhereFYArrear2." and (tbl_prop_dtl.govt_saf_dtl_id is null or tbl_prop_dtl.govt_saf_dtl_id=0)
                                                GROUP BY tbl_prop_dtl.ward_mstr_id
                                            )curent
                                        JOIN (
                                            select ward_mstr_id,
                                                SUM(case when arrear_demand>0 then arrear_demand else 0 end) as arrear_demand
                                            from prop_demand
                                            group by ward_mstr_id
                                        )prop_demand2 on prop_demand2.ward_mstr_id=curent.ward_mstr_id
                                        group by curent.ward_mstr_id 
                                    ),";
                }
                $fromYear2 = $fromYear;
                if($data['fy_mstr_id']==54 || $data['fy_mstr_id']==55){$fromYear2 = ($fyear[0]-1).'-04-01';}
                $sql = "WITH 
                PREV_COLLECTION AS (
                    SELECT 
                        tbl_prop_dtl.ward_mstr_id,
                        SUM(COALESCE(tbl_collection.amount, 0)) AS arrear_collection_amount
                    FROM tbl_prop_dtl
                    INNER JOIN tbl_collection on tbl_collection.prop_dtl_id=tbl_prop_dtl.id AND tbl_collection.created_on::date < '".$fromYear2."'
                    WHERE tbl_prop_dtl.status=1 ".$holding_type." and tbl_collection.status=1 and char_length(tbl_prop_dtl.new_holding_no)>0 ".$whereWard." and (tbl_prop_dtl.govt_saf_dtl_id is null or tbl_prop_dtl.govt_saf_dtl_id=0)
                    GROUP BY tbl_prop_dtl.ward_mstr_id
                ),".($propDemand?
                $propDemand:"")."".
                $AREA_DEMAND."

                /*ARREAR_DEMAND AS (
                    SELECT 
                        tbl_prop_dtl.ward_mstr_id,
                        SUM(COALESCE(tbl_prop_demand.amount, 0) - COALESCE(tbl_prop_demand.adjust_amt, 0)) AS arrear_demand
                    FROM tbl_prop_demand
                    INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id
                    WHERE 
                        tbl_prop_dtl.status=1 ".$holding_type." 
                        AND tbl_prop_demand.status=1 
                        AND tbl_prop_demand.paid_status IN(0,1) and char_length(tbl_prop_dtl.new_holding_no)>0 and tbl_prop_demand.due_date is not null ".$whereWard.$WhereFYArrear." 
                        GROUP BY tbl_prop_dtl.ward_mstr_id
                ),*/
                CURRENT_DEMAND AS (
                    SELECT 
                        tbl_prop_dtl.ward_mstr_id,
                        count(tbl_prop_dtl.id) AS current_holding,
                        SUM(COALESCE(tbl_prop_demand.amount, 0) - COALESCE(tbl_prop_demand.adjust_amt, 0)) AS current_demand
                    FROM tbl_prop_dtl
                    INNER JOIN (
                        select prop_dtl_id, sum(amount) as amount, sum(adjust_amt) as adjust_amt from tbl_prop_demand where tbl_prop_demand.status=1 
                        AND tbl_prop_demand.paid_status IN (0,1) and tbl_prop_demand.due_date is not null  ".$whereWard1.(isset($data['fy_mstr_id']) && $data['fy_mstr_id']!=53?$WhereFYCurrent:$Where22_23)." 
                        group by prop_dtl_id
                    ) tbl_prop_demand ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id
                    ".(isset($data['fy_mstr_id']) && $data['fy_mstr_id']==53 ? " JOIN prop_demand on prop_demand.prop_dtl_id=tbl_prop_demand.prop_dtl_id":"")."
                    WHERE tbl_prop_dtl.status=1 and char_length(tbl_prop_dtl.new_holding_no)>0  and (tbl_prop_dtl.govt_saf_dtl_id is null or tbl_prop_dtl.govt_saf_dtl_id=0) ".$holding_type."
                    GROUP BY tbl_prop_dtl.ward_mstr_id

                ),
                ARREAR_COLLECTION AS (
                    SELECT 
                        tbl_prop_dtl.ward_mstr_id,
                        SUM(COALESCE(tbl_prop_demand.amount, 0) - COALESCE(tbl_prop_demand.adjust_amt, 0)) AS arrear_collection_amount
                    FROM tbl_prop_demand
                    INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id
                    INNER JOIN tbl_collection on tbl_collection.prop_demand_id=tbl_prop_demand.id AND (tbl_collection.created_on::date between '".$fromYear."' and '".$uptoYear."') 
                    WHERE tbl_prop_dtl.status=1 ".$holding_type." and tbl_prop_demand.status=1 and char_length(tbl_prop_dtl.new_holding_no)>0 ".$whereWard.$WhereFYArrear." AND tbl_prop_demand.paid_status=1 and (tbl_prop_dtl.govt_saf_dtl_id is null or tbl_prop_dtl.govt_saf_dtl_id=0)
                    GROUP BY tbl_prop_dtl.ward_mstr_id
                ),
                CURRENT_COLLECTION AS (
                    SELECT 
                        tbl_prop_dtl.ward_mstr_id,
                        count(tbl_prop_dtl.id) AS collection_from_no_of_HH,
                        SUM(tbl_prop_demand.amount - tbl_prop_demand.adjust_amt) AS current_collection_amount
                    FROM tbl_prop_dtl
                    INNER JOIN (
                        select tbl_prop_demand.prop_dtl_id, sum(tbl_prop_demand.amount) as amount, sum(tbl_prop_demand.adjust_amt) as adjust_amt from tbl_prop_demand 
                        INNER JOIN tbl_collection on tbl_collection.prop_demand_id=tbl_prop_demand.id AND (tbl_collection.created_on::date between '".$fromYear."' and '".$uptoYear."') 
                        where tbl_prop_demand.status=1 
                        AND tbl_prop_demand.paid_status=1 AND tbl_prop_demand.status=1 ".$whereWard1.$WhereFYCurrent." 
                        group by tbl_prop_demand.prop_dtl_id
                    ) tbl_prop_demand ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id
                    
                    WHERE tbl_prop_dtl.status=1 and char_length(tbl_prop_dtl.new_holding_no)>0 and (tbl_prop_dtl.govt_saf_dtl_id is null or tbl_prop_dtl.govt_saf_dtl_id=0) ".$holding_type."
                    GROUP BY tbl_prop_dtl.ward_mstr_id
                ),
                ADVANCE_AMOUNT AS (
                    SELECT 
                        tbl_prop_dtl.ward_mstr_id,
                        count(tbl_prop_dtl.id) AS collection_from_no_of_HH,
                        SUM(amount) AS adv_amount
                    FROM tbl_prop_dtl
                    INNER JOIN (
                        select prop_dtl_id, sum(amount) as amount from tbl_advance_mstr where tbl_advance_mstr.status=1 
                        AND tbl_advance_mstr.created_on::date>='2023-03-01' and module='Property' and user_id=1
                        group by prop_dtl_id
                    ) tbl_advance_mstr ON tbl_prop_dtl.id=tbl_advance_mstr.prop_dtl_id
                    WHERE tbl_prop_dtl.status=1 and char_length(tbl_prop_dtl.new_holding_no)>0 ".$holding_type." ".$whereWard."
                    GROUP BY tbl_prop_dtl.ward_mstr_id
                )
                SELECT 
                    view_ward_mstr.ward_no,
                    COALESCE(CURRENT_DEMAND.current_holding, 0) as current_holding,
                    CASE WHEN COALESCE(ARREAR_DEMAND.arrear_demand, 0)-COALESCE(PREV_COLLECTION.arrear_collection_amount, 0)>0 then COALESCE(ARREAR_DEMAND.arrear_demand, 0)-COALESCE(PREV_COLLECTION.arrear_collection_amount, 0) else 0 end  AS arrear_demand,
                    COALESCE(CURRENT_DEMAND.current_demand, 0) AS current_demand,
                    (CASE WHEN COALESCE(ARREAR_DEMAND.arrear_demand, 0)-COALESCE(PREV_COLLECTION.arrear_collection_amount, 0)>0 then COALESCE(ARREAR_DEMAND.arrear_demand, 0)-COALESCE(PREV_COLLECTION.arrear_collection_amount, 0) else 0 end)+COALESCE(CURRENT_DEMAND.current_demand, 0) AS total_demand,
                    COALESCE(CURRENT_COLLECTION.collection_from_no_of_HH, 0) as collection_from_no_of_HH,
                    COALESCE(ARREAR_COLLECTION.arrear_collection_amount, 0) AS arrear_collection_amount,
                    COALESCE(CURRENT_COLLECTION.current_collection_amount, 0) AS current_collection_amount,
                    COALESCE(ARREAR_COLLECTION.arrear_collection_amount, 0)+COALESCE(CURRENT_COLLECTION.current_collection_amount, 0) AS total_collection_amount,
                    COALESCE(CURRENT_DEMAND.current_holding, 0)-COALESCE(CURRENT_COLLECTION.collection_from_no_of_HH, 0) AS balance_hh,
                    (CASE WHEN COALESCE(ARREAR_DEMAND.arrear_demand, 0)-COALESCE(PREV_COLLECTION.arrear_collection_amount, 0)>0 then COALESCE(ARREAR_DEMAND.arrear_demand, 0)-COALESCE(PREV_COLLECTION.arrear_collection_amount, 0) else 0 end)-COALESCE(ARREAR_COLLECTION.arrear_collection_amount, 0) AS arrear_balance_amount,
                    COALESCE(CURRENT_DEMAND.current_demand, 0)-COALESCE(CURRENT_COLLECTION.current_collection_amount, 0) AS current_balance_amount,
                    COALESCE(((CASE WHEN COALESCE(ARREAR_DEMAND.arrear_demand, 0)-COALESCE(PREV_COLLECTION.arrear_collection_amount, 0)>0 then COALESCE(ARREAR_DEMAND.arrear_demand, 0)-COALESCE(PREV_COLLECTION.arrear_collection_amount, 0) else 0 end)-ARREAR_COLLECTION.arrear_collection_amount)+(CURRENT_DEMAND.current_demand-CURRENT_COLLECTION.current_collection_amount), 0) AS total_balance_amount,
                    (CASE 
                        WHEN CURRENT_COLLECTION.collection_from_no_of_HH IS NULL OR CURRENT_COLLECTION.collection_from_no_of_HH=0 THEN 0
                        ELSE
                        ROUND((COALESCE(CURRENT_COLLECTION.collection_from_no_of_HH, 0)*100)/(CASE WHEN COALESCE(CURRENT_DEMAND.current_holding, 0)!=0 then COALESCE(CURRENT_DEMAND.current_holding, 0) else 1 end), 2)
                    END) AS hh_percentage,
                    (CASE
                        WHEN ARREAR_COLLECTION.arrear_collection_amount IS NULL OR ARREAR_COLLECTION.arrear_collection_amount=0 THEN 0
                        WHEN CURRENT_COLLECTION.current_collection_amount IS NULL OR CURRENT_COLLECTION.current_collection_amount=0 THEN 0
                        WHEN COALESCE(ARREAR_DEMAND.arrear_demand+CURRENT_DEMAND.current_demand, 0)=0 THEN 0
                        ELSE
                            ROUND((COALESCE(ARREAR_COLLECTION.arrear_collection_amount+CURRENT_COLLECTION.current_collection_amount, 0)*100)/(CASE WHEN COALESCE(ARREAR_DEMAND.arrear_demand+CURRENT_DEMAND.current_demand, 0)!=0 then COALESCE(ARREAR_DEMAND.arrear_demand+CURRENT_DEMAND.current_demand, 0) else 1 end), 2) 
                    END) AS amount_percentage,
					".
                    ($data['fy_mstr_id'] == 54?"COALESCE(ADVANCE_AMOUNT.adv_amount, 0)":"0")
					." as advance_amount
                FROM view_ward_mstr
                LEFT JOIN ARREAR_DEMAND ON ARREAR_DEMAND.ward_mstr_id=view_ward_mstr.id
                LEFT JOIN PREV_COLLECTION ON PREV_COLLECTION.ward_mstr_id=view_ward_mstr.id
                LEFT JOIN CURRENT_DEMAND ON CURRENT_DEMAND.ward_mstr_id=view_ward_mstr.id
                LEFT JOIN ARREAR_COLLECTION ON ARREAR_COLLECTION.ward_mstr_id=view_ward_mstr.id
                LEFT JOIN CURRENT_COLLECTION ON CURRENT_COLLECTION.ward_mstr_id=view_ward_mstr.id
                LEFT JOIN ADVANCE_AMOUNT ON ADVANCE_AMOUNT.ward_mstr_id=view_ward_mstr.id
                $whereWard0
                ORDER BY SUBSTRING(view_ward_mstr.ward_no FROM '([0-9]+)')::BIGINT ASC, view_ward_mstr.ward_no";
                //print_var($sql);
                $builder = $this->db->query($sql);
                if ($report_list = $builder->getResultArray()) {
                    // if($data['fy_mstr_id']==55){
                    //     $report_list['52']['arrear_demand']=85645;
                    //     $report_list['52']['arrear_balance_amount'] = $report_list['52']['arrear_demand']-$report_list['52']['arrear_collection_amount'];
                    // }
                    if($data['fy_mstr_id']==55){
                        $report_list['0']['arrear_demand']=1583705.94;
                        if(isset($report_list['0']['arrear_collection_amount'])){
                            $report_list['0']['arrear_balance_amount'] = $report_list['0']['arrear_demand']-($report_list['0']['arrear_collection_amount'])??0;
                        }
                        if(isset($report_list['28']['arrear_collection_amount'])) {
                            $report_list['28']['arrear_demand'] = 6021015.85;
                            $report_list['28']['arrear_balance_amount'] = $report_list['28']['arrear_demand'] - ($report_list['28']['arrear_collection_amount']) ?? 0;
                        }
                        if(isset($report_list['32']['arrear_collection_amount'])) {
                            $report_list['32']['arrear_demand'] = 1036012.58;
                            $report_list['32']['arrear_balance_amount'] = $report_list['32']['arrear_demand'] - $report_list['32']['arrear_collection_amount'] ?? 0;
                        }
                        if(isset($report_list['22']['arrear_collection_amount'])) {
                            $report_list['22']['arrear_demand'] = 1119567.80;
                            $report_list['22']['arrear_balance_amount'] = $report_list['22']['arrear_demand'] - $report_list['22']['arrear_collection_amount'] ?? 0;
                        }
                        if(isset($report_list['40']['arrear_collection_amount'])) {
                            $report_list['40']['arrear_demand'] = 2794882.81;
                            $report_list['40']['arrear_balance_amount'] = $report_list['40']['arrear_demand'] - $report_list['40']['arrear_collection_amount'] ?? 0;
                        }
                        if(isset($report_list['38']['arrear_collection_amount'])) {
                            $report_list['38']['arrear_demand'] = 2735329.96;
                            $report_list['38']['arrear_balance_amount'] = $report_list['38']['arrear_demand'] - $report_list['38']['arrear_collection_amount'] ?? 0;
                        }
                        if(isset($report_list['42']['arrear_collection_amount'])) {
                            $report_list['42']['arrear_demand'] = 3111960.99;
                            $report_list['42']['arrear_balance_amount'] = $report_list['42']['arrear_demand'] - $report_list['42']['arrear_collection_amount'] ?? 0;
                        }
                        if(isset($report_list['44']['arrear_collection_amount'])) {
                            $report_list['44']['arrear_demand'] = 3174090.10;
                            $report_list['44']['arrear_balance_amount'] = $report_list['44']['arrear_demand'] - $report_list['44']['arrear_collection_amount'] ?? 0;
                        }
                        if(isset($report_list['154']['arrear_collection_amount'])) {
                            $report_list['154']['arrear_demand'] = 506368.38;
                            $report_list['154']['arrear_balance_amount'] = $report_list['154']['arrear_demand'] - $report_list['154']['arrear_collection_amount'] ?? 0;
                        }
                        if(isset($report_list['16']['arrear_collection_amount'])) {
                            $report_list['16']['arrear_demand'] = 1187196.24;
                            $report_list['16']['arrear_balance_amount'] = $report_list['16']['arrear_demand'] - $report_list['16']['arrear_collection_amount'] ?? 0;
                        }
                        if(isset($report_list['52']['arrear_collection_amount'])) {
                            $report_list['52']['arrear_demand'] = 1602256.36;
                            $report_list['52']['arrear_balance_amount'] = $report_list['52']['arrear_demand'] - $report_list['52']['arrear_collection_amount'] ?? 0;
                        }
                        if(isset($report_list['54']['arrear_collection_amount'])) {
                            $report_list['54']['arrear_demand'] = 3094738.23;
                            $report_list['54']['arrear_balance_amount'] = $report_list['54']['arrear_demand'] - $report_list['54']['arrear_collection_amount'] ?? 0;
                        }
                        if(isset($report_list['79']['arrear_collection_amount'])) {
                            $report_list['79']['arrear_demand'] = 3156569.32;
                            $report_list['79']['arrear_balance_amount'] = $report_list['79']['arrear_demand'] - $report_list['79']['arrear_collection_amount'] ?? 0;
                        }
                        if(isset($report_list['114']['arrear_collection_amount'])) {
                            $report_list['114']['arrear_demand'] = 1578811.18;
                            $report_list['114']['arrear_balance_amount'] = $report_list['114']['arrear_demand'] - $report_list['114']['arrear_collection_amount'] ?? 0;
                        }
                        if(isset($report_list['50']['arrear_collection_amount'])) {
                            $report_list['50']['arrear_demand'] = 3021048;
                            $report_list['50']['arrear_balance_amount'] = $report_list['50']['arrear_demand'] - $report_list['50']['arrear_collection_amount'] ?? 0;
                        }
                        if(isset($report_list['105']['arrear_collection_amount'])) {
                            $report_list['105']['arrear_demand'] = 2414902.44;
                            $report_list['105']['arrear_balance_amount'] = $report_list['105']['arrear_demand'] - $report_list['105']['arrear_collection_amount'] ?? 0;
                        }
                        if(isset($report_list['6']['arrear_collection_amount'])) {
                            $report_list['6']['arrear_demand'] = 6048756.17;
                            $report_list['6']['arrear_balance_amount'] = $report_list['6']['arrear_demand'] - $report_list['6']['arrear_collection_amount'] ?? 0;
                        }
                        if(isset($report_list['67']['arrear_collection_amount'])) {
                            $report_list['67']['arrear_demand'] = 4329231.17;
                            $report_list['67']['arrear_balance_amount'] = $report_list['67']['arrear_demand'] - $report_list['67']['arrear_collection_amount'] ?? 0;
                        }
                        if(isset($report_list['8']['arrear_collection_amount'])) {
                            $report_list['8']['arrear_demand'] = 5020476.73;
                            $report_list['8']['arrear_balance_amount'] = $report_list['8']['arrear_demand'] - $report_list['8']['arrear_collection_amount'] ?? 0;
                        }
                    }
                    $data['report_list'] = $report_list;
                }
                //echo $this->db->getLastQuery();
            }catch(Exception $e){
                print_r($e);
            }
        }
        $data['wardList'] = $wardList;
        $data['fyList'] = $fyList;

        return view('property/reports/ward_wise_DCB_report', $data);
    
    }

    public function wardWiseDCB_1() //n
    {
        try {
        $ulb_mstr_id = 1;
        $wardList = $this->model_ward_mstr->getWardList(['ulb_mstr_id'=>$ulb_mstr_id]);
        $currentFY = getFY();
        $currentFyID = $this->model_fy_mstr->getFyByFy(['fy'=>$currentFY])['id'];
        $fyList = $this->model_fy_mstr->getFYListDescBefore10Year($currentFyID);
        if($this->request->getMethod()=='post')
        {
            try {
                $data = arrFilterSanitizeString($this->request->getVar());
                //print_r($data);
                $WhereFYCurrent = "";
                $WhereFYArrear = "";
                $Where22_23 = "";
                $propDemand = "";
                if($data['fy_mstr_id']!='') {
                    if($data['fy_mstr_id']==53) #  for current demand
                    {

                        $Where22_23 =" AND tbl_prop_demand.fy_mstr_id=54";
                        $propDemand = "prop_demand as(
                                            select SUM(COALESCE(tbl_prop_demand.amount, 0) - COALESCE(tbl_prop_demand.adjust_amt, 0)) as arrear_demand,tbl_prop_demand.prop_dtl_id from tbl_prop_demand
                                            INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id
                                            where tbl_prop_demand.status=1 and tbl_prop_demand.fy_mstr_id=53 and tbl_prop_dtl.status=1 and tbl_prop_demand.status=1 
                                            and char_length(tbl_prop_dtl.new_holding_no)>0  and (tbl_prop_dtl.govt_saf_dtl_id is null or tbl_prop_dtl.govt_saf_dtl_id=0)
                                            group by tbl_prop_demand.prop_dtl_id
                                        ),";
                    }
                    elseif($data['fy_mstr_id']==54) #for  earrir Deman
                    {
                        $Where22_23 ="";
                        $propDemand = "prop_demand as(
                            select SUM(COALESCE(tbl_prop_demand.amount, 0) - COALESCE(tbl_prop_demand.adjust_amt, 0)) as arrear_demand,tbl_prop_demand.prop_dtl_id 
                            ,tbl_prop_dtl.ward_mstr_id
                            from tbl_prop_demand
                            INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id
                            where tbl_prop_demand.status=1 and tbl_prop_demand.fy_mstr_id=54 and tbl_prop_dtl.status=1 and tbl_prop_demand.status=1 
                            and char_length(tbl_prop_dtl.new_holding_no)>0  and (tbl_prop_dtl.govt_saf_dtl_id is null or tbl_prop_dtl.govt_saf_dtl_id=0)
                            group by tbl_prop_demand.prop_dtl_id,tbl_prop_dtl.ward_mstr_id
                        ),";
                    }
                    elseif($data['fy_mstr_id']==55) #for  earrir Deman
                    {
                        // $Where22_23 =" AND tbl_prop_demand.fy_mstr_id=55";
                        $Where22_23 ="";
                        $propDemand = "prop_demand as(
                            select SUM(COALESCE(tbl_prop_demand.amount, 0) - COALESCE(tbl_prop_demand.adjust_amt, 0)) as arrear_demand,tbl_prop_demand.prop_dtl_id 
                            ,tbl_prop_dtl.ward_mstr_id
                            from tbl_prop_demand
                            INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id
                            where tbl_prop_demand.status=1 and tbl_prop_demand.fy_mstr_id=55 and tbl_prop_dtl.status=1 and tbl_prop_demand.status=1 
                            and char_length(tbl_prop_dtl.new_holding_no)>0  and (tbl_prop_dtl.govt_saf_dtl_id is null or tbl_prop_dtl.govt_saf_dtl_id=0)
                            group by tbl_prop_demand.prop_dtl_id,tbl_prop_dtl.ward_mstr_id
                        ),";
                    }

                    $WhereFYCurrent = " AND tbl_prop_demand.fy_mstr_id=".$data['fy_mstr_id'];
                    $WhereFYArrear = " AND tbl_prop_demand.fy_mstr_id<".$data['fy_mstr_id'];
                    $fyear = explode('-',$this->model_fy_mstr->getFyearByFyid($data['fy_mstr_id'])['fy']);
                    
                    $fromYear = $fyear[0].'-04-01';
                    $uptoYear = $fyear[1].'-03-31';
                }
                $whereWard = "";
                $whereWard0 = "";
                $whereWard1 = "";
                $whereWard2 = "";

                if($data['ward_mstr_id']!='') {
                    $whereWard = " AND tbl_prop_dtl.ward_mstr_id=".$data['ward_mstr_id'];
                    $whereWard1 = " AND tbl_prop_demand.ward_mstr_id=".$data['ward_mstr_id'];
                    $whereWard0 = "where view_ward_mstr.id=".$data['ward_mstr_id'];
                    $whereWard2 = " AND tbl_transaction.ward_mstr_id=".$data['ward_mstr_id'];
                     // print_r($data['ward_mstr_id']);
                    // return;
                }
                $holding_type = "";
                if (isset($data['residencial'])
                    || isset($data['non_residential'])
                    || isset($data['vacant_land'])
                    || isset($data['government_building'])) {

                    if (isset($data['residencial'])) {
                        $holding_type .= "'PURE_RESIDENTIAL'";
                    }
                    if (isset($data['non_residential'])) {
                        if ($holding_type!='') {
                            $holding_type .= ", ";
                        }
                        $holding_type .= "'PURE_COMMERCIAL', 'MIX_COMMERCIAL'";
                    }
                    if (isset($data['government_building'])) {
                        if ($holding_type!='') {
                            $holding_type .= ", ";
                        }
                        $holding_type .= "'PURE_GOVERNMENT', 'MIX_GOVERNMENT'";
                    }

                    if (isset($data['vacant_land'])) {
                        if ($holding_type!='') {
                            $holding_type .= ", ";
                        }
                        $holding_type .= "'VACANT_LAND'";
                    }
                    if ($holding_type!='') {
                        $holding_type = " AND tbl_prop_dtl.holding_type IN (".$holding_type.")";
                    }
                }
                //$dd = "PURE_RESIDENTIAL, PURE_COMMERCIAL, PURE_GOVERNMENT, MIX_GOVERNMENT, MIX_COMMERCIAL";                
                // By Hayat
                $AREA_DEMAND = "ARREAR_DEMAND AS (
                    SELECT 
                        tbl_prop_dtl.ward_mstr_id,
                        SUM(COALESCE(tbl_prop_demand.amount, 0) - COALESCE(tbl_prop_demand.adjust_amt, 0)) AS arrear_demand
                    FROM tbl_prop_demand
                    INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id
                    WHERE 
                        tbl_prop_dtl.status=1 ".$holding_type." 
                        AND tbl_prop_demand.status=1 
                        AND tbl_prop_demand.paid_status IN(0,1) and char_length(tbl_prop_dtl.new_holding_no)>0 and (tbl_prop_dtl.govt_saf_dtl_id is null or tbl_prop_dtl.govt_saf_dtl_id=0) and tbl_prop_demand.due_date is not null ".$whereWard.$WhereFYArrear." 
                        GROUP BY tbl_prop_dtl.ward_mstr_id
                ),";
                if($data['fy_mstr_id']==54 || $data['fy_mstr_id']==55)
                {
                    $fact=2.98;
                    if($data['fy_mstr_id']==55){
                        $fact=10.9;
                    }
                    $WhereFYArrear2 = " AND tbl_prop_demand.fy_mstr_id<".($data['fy_mstr_id']-1);
                  
                    $AREA_DEMAND = "ARREAR_DEMAND AS (
                                        SELECT curent.ward_mstr_id,
                                            SUM(curent.arrear_demand-(curent.arrear_demand*$fact/100)) arrear_demand 
                                        FROM (
                                            SELECT 
                                                tbl_prop_dtl.ward_mstr_id,
                                                SUM(COALESCE(tbl_prop_demand.amount, 0) - COALESCE(tbl_prop_demand.adjust_amt, 0)) AS arrear_demand
                                            FROM tbl_prop_demand
                                            INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id
                                            WHERE 
                                                tbl_prop_dtl.status=1 ".$holding_type." 
                                                AND tbl_prop_demand.status=1 
                                                AND tbl_prop_demand.paid_status IN(0,1) and char_length(tbl_prop_dtl.new_holding_no)>0 and tbl_prop_demand.due_date is not null ".$whereWard.$WhereFYArrear2." and (tbl_prop_dtl.govt_saf_dtl_id is null or tbl_prop_dtl.govt_saf_dtl_id=0)
                                                GROUP BY tbl_prop_dtl.ward_mstr_id
                                            )curent
                                        JOIN (
                                            select ward_mstr_id,
                                                SUM(case when arrear_demand>0 then arrear_demand else 0 end) as arrear_demand
                                            from prop_demand
                                            group by ward_mstr_id
                                        )prop_demand2 on prop_demand2.ward_mstr_id=curent.ward_mstr_id
                                        group by curent.ward_mstr_id 
                                    ),";
                }
                $fromYear2 = $fromYear;
                if($data['fy_mstr_id']==54 || $data['fy_mstr_id']==55){$fromYear2 = ($fyear[0]-1).'-04-01';}
                $sql = "WITH 
                PREV_COLLECTION AS (
                    SELECT 
                        tbl_prop_dtl.ward_mstr_id,
                        SUM(COALESCE(tbl_collection.amount, 0)) AS arrear_collection_amount
                    FROM tbl_prop_dtl
                    INNER JOIN tbl_collection on tbl_collection.prop_dtl_id=tbl_prop_dtl.id AND tbl_collection.created_on::date < '".$fromYear2."'
                    WHERE tbl_prop_dtl.status=1 ".$holding_type." and tbl_collection.status=1 and char_length(tbl_prop_dtl.new_holding_no)>0 ".$whereWard." and (tbl_prop_dtl.govt_saf_dtl_id is null or tbl_prop_dtl.govt_saf_dtl_id=0)
                    GROUP BY tbl_prop_dtl.ward_mstr_id
                ),".($propDemand?
                $propDemand:"")."".
                $AREA_DEMAND."

                /*ARREAR_DEMAND AS (
                    SELECT 
                        tbl_prop_dtl.ward_mstr_id,
                        SUM(COALESCE(tbl_prop_demand.amount, 0) - COALESCE(tbl_prop_demand.adjust_amt, 0)) AS arrear_demand
                    FROM tbl_prop_demand
                    INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id
                    WHERE 
                        tbl_prop_dtl.status=1 ".$holding_type." 
                        AND tbl_prop_demand.status=1 
                        AND tbl_prop_demand.paid_status IN(0,1) and char_length(tbl_prop_dtl.new_holding_no)>0 and tbl_prop_demand.due_date is not null ".$whereWard.$WhereFYArrear." 
                        GROUP BY tbl_prop_dtl.ward_mstr_id
                ),*/
                CURRENT_DEMAND AS (
                    SELECT 
                        tbl_prop_dtl.ward_mstr_id,
                        count(tbl_prop_dtl.id) AS current_holding,
                        SUM(COALESCE(tbl_prop_demand.amount, 0) - COALESCE(tbl_prop_demand.adjust_amt, 0)) AS current_demand
                    FROM tbl_prop_dtl
                    INNER JOIN (
                        select prop_dtl_id, sum(amount) as amount, sum(adjust_amt) as adjust_amt from tbl_prop_demand where tbl_prop_demand.status=1 
                        AND tbl_prop_demand.paid_status IN (0,1) and tbl_prop_demand.due_date is not null  ".$whereWard1.(isset($data['fy_mstr_id']) && $data['fy_mstr_id']!=53?$WhereFYCurrent:$Where22_23)." 
                        group by prop_dtl_id
                    ) tbl_prop_demand ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id
                    ".(isset($data['fy_mstr_id']) && $data['fy_mstr_id']==53 ? " JOIN prop_demand on prop_demand.prop_dtl_id=tbl_prop_demand.prop_dtl_id":"")."
                    WHERE tbl_prop_dtl.status=1 and char_length(tbl_prop_dtl.new_holding_no)>0  and (tbl_prop_dtl.govt_saf_dtl_id is null or tbl_prop_dtl.govt_saf_dtl_id=0) ".$holding_type."
                    GROUP BY tbl_prop_dtl.ward_mstr_id

                ),
                ARREAR_COLLECTION_ AS (
                    SELECT 
                        tbl_prop_dtl.ward_mstr_id,
                        SUM(COALESCE(tbl_prop_demand.amount, 0) - COALESCE(tbl_prop_demand.adjust_amt, 0)) AS arrear_collection_amount
                    FROM tbl_prop_demand
                    INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id
                    INNER JOIN tbl_collection on tbl_collection.prop_demand_id=tbl_prop_demand.id AND (tbl_collection.created_on::date between '".$fromYear."' and '".$uptoYear."') 
                    WHERE tbl_prop_dtl.status=1 ".$holding_type." and tbl_prop_demand.status=1 and char_length(tbl_prop_dtl.new_holding_no)>0 ".$whereWard.$WhereFYArrear."
                    AND tbl_prop_demand.paid_status=1 and (tbl_prop_dtl.govt_saf_dtl_id is null or tbl_prop_dtl.govt_saf_dtl_id=0)
                    GROUP BY tbl_prop_dtl.ward_mstr_id
                ),
                ARREAR_COLLECTION AS (
                    SELECT 
                        tbl_transaction.ward_mstr_id,
                        count(tbl_transaction.prop_dtl_id) AS arrear_collection_from_no_of_hh,
                        sum(tbl_transaction.payable_amt) AS arrear_collection_amount
                    FROM tbl_transaction
                     INNER JOIN view_ward_mstr ON view_ward_mstr.id=tbl_transaction.ward_mstr_id
                     WHERE tbl_transaction.tran_type='Property' AND tbl_transaction.status in (1, 2) AND
                     (tbl_transaction.upto_fy_mstr_id!='".$data['fy_mstr_id']."' or tbl_transaction.upto_fy_mstr_id IS NULL)
                        AND (tbl_transaction.tran_date between '$fromYear'::date and '$uptoYear'::date) 
                        ".$whereWard2." GROUP BY tbl_transaction.ward_mstr_id
                ),
                CURRENT_COLLECTION_ AS (
                    SELECT 
                        tbl_prop_dtl.ward_mstr_id,
                        count(tbl_prop_dtl.id) AS collection_from_no_of_HH,
                        SUM(tbl_prop_demand.amount - tbl_prop_demand.adjust_amt) AS current_collection_amount
                    FROM tbl_prop_dtl
                    INNER JOIN (
                        select tbl_prop_demand.prop_dtl_id, sum(tbl_prop_demand.amount) as amount, sum(tbl_prop_demand.adjust_amt) as adjust_amt from tbl_prop_demand 
                        INNER JOIN tbl_collection on tbl_collection.prop_demand_id=tbl_prop_demand.id AND (tbl_collection.created_on::date between '".$fromYear."' and '".$uptoYear."') 
                        where tbl_prop_demand.status=1 
                        AND tbl_prop_demand.paid_status=1 AND tbl_prop_demand.status=1 ".$whereWard1.$WhereFYCurrent." 
                        group by tbl_prop_demand.prop_dtl_id
                    ) tbl_prop_demand ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id
                    
                    WHERE tbl_prop_dtl.status=1 and char_length(tbl_prop_dtl.new_holding_no)>0 and (tbl_prop_dtl.govt_saf_dtl_id is null or tbl_prop_dtl.govt_saf_dtl_id=0) ".$holding_type."
                    GROUP BY tbl_prop_dtl.ward_mstr_id
                ), CURRENT_COLLECTION AS (
                    SELECT 
                        tbl_prop_dtl.ward_mstr_id,
                        count(tbl_prop_dtl.id) AS collection_from_no_of_HH,
                        SUM(tbl_prop_demand.holding_tax + tbl_prop_demand.additional_amt + tbl_prop_demand.fine_amt) AS current_collection_amount
                       -- SUM(tbl_prop_demand.amount + tbl_prop_demand.fine_amt) AS current_collection_amount
                    FROM tbl_prop_dtl
                    INNER JOIN (
                        select tbl_prop_demand.prop_dtl_id, sum(tbl_collection.amount) as amount, sum(tbl_collection.holding_tax) as holding_tax, sum(tbl_collection.additional_tax) as additional_amt,
                        sum(tbl_collection.fine_amt) as fine_amt from tbl_prop_demand 
                        INNER JOIN tbl_collection on tbl_collection.prop_demand_id=tbl_prop_demand.id AND (tbl_collection.fy_mstr_id='".$data['fy_mstr_id']."')
                        where tbl_prop_demand.status=1 
                        AND tbl_prop_demand.paid_status=1 ".$whereWard1.$WhereFYCurrent." 
                        group by tbl_prop_demand.prop_dtl_id
                    ) tbl_prop_demand ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id
                    WHERE tbl_prop_dtl.status=1 and char_length(tbl_prop_dtl.new_holding_no)>0 and (tbl_prop_dtl.govt_saf_dtl_id is null or tbl_prop_dtl.govt_saf_dtl_id=0) ".$holding_type."
                    GROUP BY tbl_prop_dtl.ward_mstr_id
                ),
                ACTUAL_COLLECTION AS (
                    SELECT 
                        tbl_transaction.ward_mstr_id,
                        count(tbl_transaction.prop_dtl_id) AS collection_from_no_of_HH,
                        sum(tbl_transaction.payable_amt) AS actual_collection_amount
                    FROM tbl_transaction
                     INNER JOIN view_ward_mstr ON view_ward_mstr.id=tbl_transaction.ward_mstr_id
                     WHERE (tbl_transaction.upto_fy_mstr_id=".$data['fy_mstr_id']." and tbl_transaction.from_fy_mstr_id=".$data['fy_mstr_id'].") AND tbl_transaction.tran_type='Property' AND tbl_transaction.status in (1, 2) AND
                        (tbl_transaction.tran_date between '$fromYear'::date and '$uptoYear'::date) 
                        ".$whereWard2." 
                       GROUP BY tbl_transaction.ward_mstr_id
                ),
                ACTUALCOLLECTION2 AS (
                    SELECT 
                        tbl_transaction.ward_mstr_id,
                        count(tbl_transaction.prop_dtl_id) AS collection_from_no_of_HH,
                        sum(tbl_transaction.payable_amt) AS actual_collection_amount,sum(tbl_transaction.discount_amt) as rebate_amount,
                        (select sum(tbl_collection.amount+tbl_collection.fine_amt) from tbl_collection 
                        where tbl_collection.created_on::date between '$fromYear'::date and '$uptoYear'::date and tbl_collection.fy_mstr_id=".$data['fy_mstr_id']." and status=1
                        and tbl_collection.transaction_id=tbl_transaction.id group by tbl_collection.transaction_id) as current_demand,
                        (select sum(tbl_collection.amount+tbl_collection.fine_amt) from tbl_collection 
                        where tbl_collection.created_on::date between '$fromYear'::date and '$uptoYear'::date and tbl_collection.fy_mstr_id!=".$data['fy_mstr_id']." and status=1
                        and tbl_collection.transaction_id=tbl_transaction.id group by tbl_collection.transaction_id) as arrear_demand,
                        (select sum(tbl_transaction_fine_rebet_details.amount) from tbl_transaction_fine_rebet_details
                        where tbl_transaction_fine_rebet_details.created_on::date between '$fromYear'::date and '$uptoYear'::date and tbl_transaction_fine_rebet_details.status=1
                        and head_name IN ('Adjusted Amount') and tbl_transaction_fine_rebet_details.transaction_id=tbl_transaction.id group by tbl_transaction_fine_rebet_details.transaction_id)
                        as adjust_amt
                    FROM tbl_transaction
                     INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_transaction.prop_dtl_id
                     INNER JOIN view_ward_mstr ON view_ward_mstr.id=tbl_transaction.ward_mstr_id
                     WHERE (tbl_transaction.from_fy_mstr_id!=".$data['fy_mstr_id']." and tbl_transaction.upto_fy_mstr_id=".$data['fy_mstr_id'].") AND tbl_transaction.tran_type='Property' AND tbl_transaction.status in (1, 2) AND
                        (tbl_transaction.tran_date between '$fromYear'::date and '$uptoYear'::date) 
                        ".$whereWard2." 
                       GROUP BY tbl_transaction.ward_mstr_id,tbl_transaction.id
                ),ACTUALCOLLECTION as (select ward_mstr_id,actual_collection_amount,rebate_amount,
                COALESCE((CASE WHEN (arrear_demand-adjust_amt)<0 OR (arrear_demand-adjust_amt) IS NULL THEN 
                (CASE WHEN (COALESCE(current_demand+(arrear_demand-adjust_amt),0)<=0) THEN
                actual_collection_amount ELSE
                (current_demand+(arrear_demand-adjust_amt)) END)
                ELSE current_demand
                END),0) as collected_current,(CASE WHEN adjust_amt IS NULL THEN
                (CASE WHEN actual_collection_amount IS NULL or actual_collection_amount <=0 THEN
                0 ELSE arrear_demand END) ELSE (arrear_demand-adjust_amt)
                END) as collected_arrear from ACTUALCOLLECTION2),
                ACTUALCOLLECTIONFINAL as (select ward_mstr_id,sum(COALESCE((
                CASE WHEN ( ROUND((collected_current+collected_arrear)-rebate_amount)=ROUND(actual_collection_amount))
                THEN COALESCE(collected_current,0)-COALESCE(rebate_amount,0)
                ELSE (COALESCE(actual_collection_amount,0)-COALESCE(collected_arrear,0))
                END),0)) as 
                currentdemand_colt,sum(collected_arrear) as current_arrear_colt from ACTUALCOLLECTION group by ward_mstr_id),
                ADVANCE_AMOUNT AS (
                    SELECT 
                        tbl_prop_dtl.ward_mstr_id,
                        count(tbl_prop_dtl.id) AS collection_from_no_of_HH,
                        SUM(amount) AS adv_amount
                    FROM tbl_prop_dtl
                    INNER JOIN (
                        select prop_dtl_id, sum(amount) as amount from tbl_advance_mstr where tbl_advance_mstr.status=1 
                        AND tbl_advance_mstr.created_on::date>='2023-03-01' and module='Property' and user_id=1
                        group by prop_dtl_id
                    ) tbl_advance_mstr ON tbl_prop_dtl.id=tbl_advance_mstr.prop_dtl_id
                    WHERE tbl_prop_dtl.status=1 and char_length(tbl_prop_dtl.new_holding_no)>0 ".$holding_type." ".$whereWard."
                    GROUP BY tbl_prop_dtl.ward_mstr_id
                )
                SELECT 
                    view_ward_mstr.ward_no,
                    COALESCE(CURRENT_DEMAND.current_holding, 0) as current_holding,
                    CASE WHEN COALESCE(ARREAR_DEMAND.arrear_demand, 0)-COALESCE(PREV_COLLECTION.arrear_collection_amount, 0)>0 then COALESCE(ARREAR_DEMAND.arrear_demand, 0)-COALESCE(PREV_COLLECTION.arrear_collection_amount, 0) else 0 end  AS arrear_demand,
                    COALESCE(CURRENT_DEMAND.current_demand, 0) AS current_demand,
                    (CASE WHEN COALESCE(ARREAR_DEMAND.arrear_demand, 0)-COALESCE(PREV_COLLECTION.arrear_collection_amount, 0)>0 then COALESCE(ARREAR_DEMAND.arrear_demand, 0)-COALESCE(PREV_COLLECTION.arrear_collection_amount, 0) else 0 end)+COALESCE(CURRENT_DEMAND.current_demand, 0) AS total_demand,
                    COALESCE(CURRENT_COLLECTION.collection_from_no_of_HH, 0) as collection_from_no_of_HH,
                    (select count(*) from tbl_transaction txn where txn.tran_type='Property' AND txn.status in (1,2) AND
                    (txn.tran_date between '$fromYear'::date and '$uptoYear'::date) and txn.ward_mstr_id=view_ward_mstr.id) as collection_from_no_of_HH1,
                    COALESCE(ARREAR_COLLECTION.arrear_collection_amount, 0) AS arrear_collection_amount,
                    COALESCE(ACTUALCOLLECTIONFINAL.current_arrear_colt, 0) AS arrear_collection_amount2,
                    COALESCE(CURRENT_COLLECTION.current_collection_amount, 0) AS current_collection_amount,
                    COALESCE(ACTUAL_COLLECTION.actual_collection_amount, 0) AS actual_collection_amount,
                    COALESCE(ACTUALCOLLECTIONFINAL.currentdemand_colt, 0) AS actual_collection_amount2,
                    COALESCE(ARREAR_COLLECTION.arrear_collection_amount, 0)+COALESCE(ACTUAL_COLLECTION.actual_collection_amount, 0) AS total_collection_amount,
                    COALESCE(ACTUALCOLLECTIONFINAL.current_arrear_colt, 0)+COALESCE(ACTUALCOLLECTIONFINAL.currentdemand_colt, 0) AS total_collection_amount2,
                    COALESCE(CURRENT_DEMAND.current_holding, 0)-COALESCE(CURRENT_COLLECTION.collection_from_no_of_HH, 0) AS balance_hh,
                    (CASE WHEN COALESCE(ARREAR_DEMAND.arrear_demand, 0)-COALESCE(PREV_COLLECTION.arrear_collection_amount, 0)>0 then COALESCE(ARREAR_DEMAND.arrear_demand, 0)-COALESCE(PREV_COLLECTION.arrear_collection_amount, 0) else 0 end)-COALESCE((ARREAR_COLLECTION.arrear_collection_amount+ACTUALCOLLECTIONFINAL.current_arrear_colt), 0) AS arrear_balance_amount,
                    COALESCE(CURRENT_DEMAND.current_demand, 0)-COALESCE((COALESCE(ACTUAL_COLLECTION.actual_collection_amount,0)+COALESCE(ACTUALCOLLECTIONFINAL.currentdemand_colt,0)), 0) AS current_balance_amount,
                    COALESCE(((CASE WHEN COALESCE(ARREAR_DEMAND.arrear_demand, 0)-COALESCE(PREV_COLLECTION.arrear_collection_amount, 0)>0 then COALESCE(ARREAR_DEMAND.arrear_demand, 0)-COALESCE(PREV_COLLECTION.arrear_collection_amount, 0) else 0 end)-ARREAR_COLLECTION.arrear_collection_amount)+(CURRENT_DEMAND.current_demand-CURRENT_COLLECTION.current_collection_amount), 0) AS total_balance_amount,
                    (CASE WHEN COALESCE(ARREAR_DEMAND.arrear_demand, 0)-COALESCE(PREV_COLLECTION.arrear_collection_amount, 0)>0 then COALESCE(ARREAR_DEMAND.arrear_demand, 0)-COALESCE(PREV_COLLECTION.arrear_collection_amount, 0) else 0 end)-COALESCE((ARREAR_COLLECTION.arrear_collection_amount+ACTUALCOLLECTIONFINAL.current_arrear_colt), 0)+COALESCE(CURRENT_DEMAND.current_demand, 0)-COALESCE((COALESCE(ACTUAL_COLLECTION.actual_collection_amount,0)+COALESCE(ACTUALCOLLECTIONFINAL.currentdemand_colt,0)), 0) AS total_balance_amount2,
                    (CASE 
                        WHEN CURRENT_COLLECTION.collection_from_no_of_HH IS NULL OR CURRENT_COLLECTION.collection_from_no_of_HH=0 THEN 0
                        ELSE
                        ROUND((COALESCE(CURRENT_COLLECTION.collection_from_no_of_HH, 0)*100)/(CASE WHEN COALESCE(CURRENT_DEMAND.current_holding, 0)!=0 then COALESCE(CURRENT_DEMAND.current_holding, 0) else 1 end), 2)
                    END) AS hh_percentage,
                    (CASE
                        WHEN ARREAR_COLLECTION.arrear_collection_amount IS NULL OR ARREAR_COLLECTION.arrear_collection_amount=0 THEN 0
                        WHEN CURRENT_COLLECTION.current_collection_amount IS NULL OR CURRENT_COLLECTION.current_collection_amount=0 THEN 0
                        WHEN COALESCE(ARREAR_DEMAND.arrear_demand+CURRENT_DEMAND.current_demand, 0)=0 THEN 0
                        ELSE
                            ROUND((COALESCE(ARREAR_COLLECTION.arrear_collection_amount+CURRENT_COLLECTION.current_collection_amount, 0)*100)/(CASE WHEN COALESCE(ARREAR_DEMAND.arrear_demand+CURRENT_DEMAND.current_demand, 0)!=0 then COALESCE(ARREAR_DEMAND.arrear_demand+CURRENT_DEMAND.current_demand, 0) else 1 end), 2) 
                    END) AS amount_percentage,
					".
                    ($data['fy_mstr_id'] == 54?"COALESCE(ADVANCE_AMOUNT.adv_amount, 0)":"0")
					." as advance_amount
                FROM view_ward_mstr
                LEFT JOIN ARREAR_DEMAND ON ARREAR_DEMAND.ward_mstr_id=view_ward_mstr.id
                LEFT JOIN PREV_COLLECTION ON PREV_COLLECTION.ward_mstr_id=view_ward_mstr.id
                LEFT JOIN CURRENT_DEMAND ON CURRENT_DEMAND.ward_mstr_id=view_ward_mstr.id
                LEFT JOIN ARREAR_COLLECTION ON ARREAR_COLLECTION.ward_mstr_id=view_ward_mstr.id
                LEFT JOIN CURRENT_COLLECTION ON CURRENT_COLLECTION.ward_mstr_id=view_ward_mstr.id
                LEFT JOIN ACTUAL_COLLECTION ON ACTUAL_COLLECTION.ward_mstr_id=view_ward_mstr.id
                LEFT JOIN ACTUALCOLLECTIONFINAL ON ACTUALCOLLECTIONFINAL.ward_mstr_id=view_ward_mstr.id
                LEFT JOIN ADVANCE_AMOUNT ON ADVANCE_AMOUNT.ward_mstr_id=view_ward_mstr.id
                $whereWard0
                ORDER BY SUBSTRING(view_ward_mstr.ward_no FROM '([0-9]+)')::BIGINT ASC, view_ward_mstr.ward_no";

                $totalcollection="select sum(tbl_transaction.payable_amt) as actual_collection_amount,count(tbl_transaction.prop_dtl_id) as collection_from_no_of_HH
                FROM tbl_transaction
                                        INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_transaction.prop_dtl_id                      
                                        WHERE tbl_transaction.tran_type='Property' AND tbl_transaction.status in (1, 2) AND
                 (tbl_transaction.tran_date between '$fromYear'::date and '$uptoYear'::date)";
                                $builder = $this->db->query($sql);
                                $builder2 = $this->db->query($totalcollection);
                                $actual_collection_ = $builder2->getResultArray()[0]['actual_collection_amount'];
                                $remaining=$actual_collection_;

                if ($report_list = $builder->getResultArray()) {
                    // if($data['fy_mstr_id']==55){
                    //     $report_list['52']['arrear_demand']=85645;
                    //     $report_list['52']['arrear_balance_amount'] = $report_list['52']['arrear_demand']-$report_list['52']['arrear_collection_amount'];
                    // }
                    if($data['fy_mstr_id']==55){
                        $report_list['0']['arrear_demand']=1583705.94;
                        if(isset($report_list['0']['arrear_collection_amount'])){
                            $report_list['0']['arrear_balance_amount'] = $report_list['0']['arrear_demand']-($report_list['0']['arrear_collection_amount'])??0;
                            $report_list['0']['total_demand'] = $report_list['0']['arrear_demand'] + $report_list['0']['current_demand'] ?? 0;
                        }
                        if(isset($report_list['28']['arrear_collection_amount'])) {
                            $report_list['28']['arrear_demand'] = 6021015.85;
                            $report_list['28']['arrear_balance_amount'] = $report_list['28']['arrear_demand'] - ($report_list['28']['arrear_collection_amount']) ?? 0;
                            $report_list['28']['total_demand'] = $report_list['28']['arrear_demand'] + $report_list['28']['current_demand'] ?? 0;
                        }
                        if(isset($report_list['32']['arrear_collection_amount'])) {
                            $report_list['32']['arrear_demand'] = 1036012.58;
                            $report_list['32']['arrear_balance_amount'] = $report_list['32']['arrear_demand'] - $report_list['32']['arrear_collection_amount'] ?? 0;
                            $report_list['32']['total_demand'] = $report_list['32']['arrear_demand'] + $report_list['32']['current_demand'] ?? 0;
                        }
                        if(isset($report_list['22']['arrear_collection_amount'])) {
                            $report_list['22']['arrear_demand'] = 1119567.80;
                            $report_list['22']['arrear_balance_amount'] = $report_list['22']['arrear_demand'] - $report_list['22']['arrear_collection_amount'] ?? 0;
                            $report_list['22']['total_demand'] = $report_list['22']['arrear_demand'] + $report_list['22']['current_demand'] ?? 0;
                        }
                        if(isset($report_list['40']['arrear_collection_amount'])) {
                            $report_list['40']['arrear_demand'] = 2794882.81;
                            $report_list['40']['arrear_balance_amount'] = $report_list['40']['arrear_demand'] - $report_list['40']['arrear_collection_amount'] ?? 0;
                            $report_list['40']['total_demand'] = $report_list['40']['arrear_demand'] + $report_list['40']['current_demand'] ?? 0;
                        }
                        if(isset($report_list['38']['arrear_collection_amount'])) {
                            $report_list['38']['arrear_demand'] = 2735329.96;
                            $report_list['38']['arrear_balance_amount'] = $report_list['38']['arrear_demand'] - $report_list['38']['arrear_collection_amount'] ?? 0;
                            $report_list['38']['total_demand'] = $report_list['38']['arrear_demand'] + $report_list['38']['current_demand'] ?? 0;
                        }
                        if(isset($report_list['42']['arrear_collection_amount'])) {
                            $report_list['42']['arrear_demand'] = 3111960.99;
                            $report_list['42']['arrear_balance_amount'] = $report_list['42']['arrear_demand'] - $report_list['42']['arrear_collection_amount'] ?? 0;
                            $report_list['42']['total_demand'] = $report_list['42']['arrear_demand'] + $report_list['42']['current_demand'] ?? 0;
                        }
                        if(isset($report_list['44']['arrear_collection_amount'])) {
                            $report_list['44']['arrear_demand'] = 3174090.10;
                            $report_list['44']['arrear_balance_amount'] = $report_list['44']['arrear_demand'] - $report_list['44']['arrear_collection_amount'] ?? 0;
                            $report_list['44']['total_demand'] = $report_list['44']['arrear_demand'] + $report_list['44']['current_demand'] ?? 0;
                        }
                        if(isset($report_list['154']['arrear_collection_amount'])) {
                            $report_list['154']['arrear_demand'] = 506368.38;
                            $report_list['154']['arrear_balance_amount'] = $report_list['154']['arrear_demand'] - $report_list['154']['arrear_collection_amount'] ?? 0;
                            $report_list['154']['total_demand'] = $report_list['154']['arrear_demand'] + $report_list['154']['current_demand'] ?? 0;
                        }
                        if(isset($report_list['16']['arrear_collection_amount'])) {
                            $report_list['16']['arrear_demand'] = 1187196.24;
                            $report_list['16']['arrear_balance_amount'] = $report_list['16']['arrear_demand'] - $report_list['16']['arrear_collection_amount'] ?? 0;
                            $report_list['16']['total_demand'] = $report_list['16']['arrear_demand'] + $report_list['16']['current_demand'] ?? 0;
                        }
                        if(isset($report_list['52']['arrear_collection_amount'])) {
                            $report_list['52']['arrear_demand'] = 1602256.36;
                            $report_list['52']['arrear_balance_amount'] = $report_list['52']['arrear_demand'] - $report_list['52']['arrear_collection_amount'] ?? 0;
                            $report_list['52']['total_demand'] = $report_list['52']['arrear_demand'] + $report_list['52']['current_demand'] ?? 0;
                        }
                        if(isset($report_list['54']['arrear_collection_amount'])) {
                            $report_list['54']['arrear_demand'] = 3094738.23;
                            $report_list['54']['arrear_balance_amount'] = $report_list['54']['arrear_demand'] - $report_list['54']['arrear_collection_amount'] ?? 0;
                            $report_list['54']['total_demand'] = $report_list['54']['arrear_demand'] + $report_list['54']['current_demand'] ?? 0;
                        }
                        if(isset($report_list['79']['arrear_collection_amount'])) {
                            $report_list['79']['arrear_demand'] = 3156569.32;
                            $report_list['79']['arrear_balance_amount'] = $report_list['79']['arrear_demand'] - $report_list['79']['arrear_collection_amount'] ?? 0;
                            $report_list['79']['total_demand'] = $report_list['79']['arrear_demand'] + $report_list['79']['current_demand'] ?? 0;
                        }
                        if(isset($report_list['114']['arrear_collection_amount'])) {
                            $report_list['114']['arrear_demand'] = 1578811.18;
                            $report_list['114']['arrear_balance_amount'] = $report_list['114']['arrear_demand'] - $report_list['114']['arrear_collection_amount'] ?? 0;
                            $report_list['114']['total_demand'] = $report_list['114']['arrear_demand'] + $report_list['114']['current_demand'] ?? 0;
                        }
                        if(isset($report_list['50']['arrear_collection_amount'])) {
                            $report_list['50']['arrear_demand'] = 3021048;
                            $report_list['50']['arrear_balance_amount'] = $report_list['50']['arrear_demand'] - $report_list['50']['arrear_collection_amount'] ?? 0;
                            $report_list['50']['total_demand'] = $report_list['50']['arrear_demand'] + $report_list['50']['current_demand'] ?? 0;
                        }
                        if(isset($report_list['105']['arrear_collection_amount'])) {
                            $report_list['105']['arrear_demand'] = 2414902.44;
                            $report_list['105']['arrear_balance_amount'] = $report_list['105']['arrear_demand'] - $report_list['105']['arrear_collection_amount'] ?? 0;
                            $report_list['105']['total_demand'] = $report_list['105']['arrear_demand'] + $report_list['105']['current_demand'] ?? 0;
                        }
                        if(isset($report_list['6']['arrear_collection_amount'])) {
                            $report_list['6']['arrear_demand'] = 6048756.17;
                            $report_list['6']['arrear_balance_amount'] = $report_list['6']['arrear_demand'] - $report_list['6']['arrear_collection_amount'] ?? 0;
                            $report_list['6']['total_demand'] = $report_list['6']['arrear_demand'] + $report_list['6']['current_demand'] ?? 0;
                        }
                        if(isset($report_list['67']['arrear_collection_amount'])) {
                            $report_list['67']['arrear_demand'] = 4329231.17;
                            $report_list['67']['arrear_balance_amount'] = $report_list['67']['arrear_demand'] - $report_list['67']['arrear_collection_amount'] ?? 0;
                            $report_list['67']['total_demand'] = $report_list['67']['arrear_demand'] + $report_list['67']['current_demand'] ?? 0;
                        }
                        if(isset($report_list['8']['arrear_collection_amount'])) {
                            $report_list['8']['arrear_demand'] = 5020476.73;
                            $report_list['8']['arrear_balance_amount'] = $report_list['8']['arrear_demand'] - $report_list['8']['arrear_collection_amount'] ?? 0;
                            $report_list['8']['total_demand'] = $report_list['8']['arrear_demand'] + $report_list['8']['current_demand'] ?? 0;
                        }
                    }
                    $data['report_list'] = $report_list;
                    $data['prop_collection'] = $remaining;
                }
                //echo $this->db->getLastQuery();
            }catch(Exception $e){
                print_r($e);
            }
        }
        $data['wardList'] = $wardList;
        $data['fyList'] = $fyList;
        
        if(isset($_REQUEST['api'])){
            return $data;
        }
        // print_Var($data);die;
        return view('property/reports/ward_wise_DCB_report', $data);
        }catch(Exception $e){
            print_r($e);
        }
    }

    public function wardWiseDCB_a($pmu=null)
    {
        $testData=[];
        $ulb_mstr_id = 1;
        $wardList = $this->model_ward_mstr->getWardList(['ulb_mstr_id'=>$ulb_mstr_id]);
        $currentFY = getFY();
        $currentFyID = $this->model_fy_mstr->getFyByFy(['fy'=>$currentFY])['id'];
        $fyList = $this->model_fy_mstr->getFYListDescBefore10Year($currentFyID);
        if($this->request->getMethod()=='post')
        {
            try {
                $data = arrFilterSanitizeString($this->request->getVar());
                $WhereFYCurrent = "";
                $WhereFYArrear = "";
                $Where22_23 = "";
                $propDemand = "";
                if($data['fy_mstr_id']!='') {
                    if($data['fy_mstr_id']==53) #  for current demand
                    {

                        $Where22_23 =" AND tbl_prop_demand.fy_mstr_id=54";
                        $propDemand = " prop_demand as(
                                            select SUM(COALESCE(tbl_prop_demand.amount, 0) - COALESCE(tbl_prop_demand.adjust_amt, 0)) as arrear_demand,tbl_prop_demand.prop_dtl_id 
                                            from tbl_prop_demand
                                            INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id
                                            where tbl_prop_demand.status=1 and tbl_prop_demand.fy_mstr_id=53 and tbl_prop_dtl.status=1 and tbl_prop_demand.status=1 
                                                and char_length(tbl_prop_dtl.new_holding_no)>0  and (tbl_prop_dtl.govt_saf_dtl_id is null or tbl_prop_dtl.govt_saf_dtl_id=0)
                                            group by tbl_prop_demand.prop_dtl_id
                                        ),
                        ";
                    }
                    elseif($data['fy_mstr_id']==54) #for  earrir Deman
                    {
                        $Where22_23 ="";
                        $propDemand = " prop_demand as(
                                            select SUM(COALESCE(tbl_prop_demand.amount, 0) - COALESCE(tbl_prop_demand.adjust_amt, 0)) as arrear_demand,tbl_prop_demand.prop_dtl_id,tbl_prop_dtl.ward_mstr_id
                                            from tbl_prop_demand
                                            INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id
                                            where tbl_prop_demand.status=1 and tbl_prop_demand.fy_mstr_id=54 and tbl_prop_dtl.status=1 and tbl_prop_demand.status=1 
                                            and char_length(tbl_prop_dtl.new_holding_no)>0  and (tbl_prop_dtl.govt_saf_dtl_id is null or tbl_prop_dtl.govt_saf_dtl_id=0)
                                            group by tbl_prop_demand.prop_dtl_id,tbl_prop_dtl.ward_mstr_id
                                        ),
                        ";
                    }
                    elseif($data['fy_mstr_id']==55) #for  earrir Deman
                    {
                        // $Where22_23 =" AND tbl_prop_demand.fy_mstr_id=55";
                        $Where22_23 ="";
                        $propDemand = " prop_demand as(
                                            select SUM(COALESCE(tbl_prop_demand.amount, 0) - COALESCE(tbl_prop_demand.adjust_amt, 0)) as arrear_demand,tbl_prop_demand.prop_dtl_id,tbl_prop_dtl.ward_mstr_id
                                            from tbl_prop_demand
                                            INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id
                                            where tbl_prop_demand.status=1 and tbl_prop_demand.fy_mstr_id=55 and tbl_prop_dtl.status=1 and tbl_prop_demand.status=1 
                                            and char_length(tbl_prop_dtl.new_holding_no)>0  and (tbl_prop_dtl.govt_saf_dtl_id is null or tbl_prop_dtl.govt_saf_dtl_id=0)
                                            group by tbl_prop_demand.prop_dtl_id,tbl_prop_dtl.ward_mstr_id
                                        ),
                        ";
                    }

                    $WhereFYCurrent = " AND tbl_prop_demand.fy_mstr_id=".$data['fy_mstr_id'];
                    $WhereFYArrear = " AND tbl_prop_demand.fy_mstr_id<".$data['fy_mstr_id'];
                    $fyear = explode('-',$this->model_fy_mstr->getFyearByFyid($data['fy_mstr_id'])['fy']);
                    
                    $fromYear = $fyear[0].'-04-01';
                    $uptoYear = $fyear[1].'-03-31';
                }
                $whereWard = "";
                $whereWard0 = "";
                $whereWard1 = "";
                $whereWard2 = "";

                if($data['ward_mstr_id']!='') {
                    $whereWard = " AND tbl_prop_dtl.ward_mstr_id=".$data['ward_mstr_id'];
                    $whereWard1 = " AND tbl_prop_demand.ward_mstr_id=".$data['ward_mstr_id'];
                    $whereWard0 = "where view_ward_mstr.id=".$data['ward_mstr_id'];
                    $whereWard2 = " AND tbl_transaction.ward_mstr_id=".$data['ward_mstr_id'];
                }
                $holding_type = "";
                if (isset($data['residencial'])
                    || isset($data['non_residential'])
                    || isset($data['vacant_land'])
                    || isset($data['government_building'])) {

                    if (isset($data['residencial'])) {
                        $holding_type .= "'PURE_RESIDENTIAL'";
                    }
                    if (isset($data['non_residential'])) {
                        if ($holding_type!='') {
                            $holding_type .= ", ";
                        }
                        $holding_type .= "'PURE_COMMERCIAL', 'MIX_COMMERCIAL'";
                    }
                    if (isset($data['government_building'])) {
                        if ($holding_type!='') {
                            $holding_type .= ", ";
                        }
                        $holding_type .= "'PURE_GOVERNMENT', 'MIX_GOVERNMENT'";
                    }

                    if (isset($data['vacant_land'])) {
                        if ($holding_type!='') {
                            $holding_type .= ", ";
                        }
                        $holding_type .= "'VACANT_LAND'";
                    }
                    if ($holding_type!='') {
                        $holding_type = " AND tbl_prop_dtl.holding_type IN (".$holding_type.")";
                    }
                }
                
                $AREA_DEMAND = "ARREAR_DEMAND AS (
                                    SELECT 
                                        tbl_prop_dtl.ward_mstr_id,
                                        SUM(COALESCE(tbl_prop_demand.amount, 0) - COALESCE(tbl_prop_demand.adjust_amt, 0)) AS arrear_demand
                                    FROM tbl_prop_demand
                                    INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id
                                    WHERE tbl_prop_dtl.status=1 ".$holding_type." 
                                        AND tbl_prop_demand.status=1 
                                        AND tbl_prop_demand.paid_status IN(0,1) 
                                        and char_length(tbl_prop_dtl.new_holding_no)>0 
                                        and (tbl_prop_dtl.govt_saf_dtl_id is null or tbl_prop_dtl.govt_saf_dtl_id=0) 
                                        and tbl_prop_demand.due_date is not null ".$whereWard.$WhereFYArrear." 
                                    GROUP BY tbl_prop_dtl.ward_mstr_id
                                ),
                ";
                if($data['fy_mstr_id']==54 || $data['fy_mstr_id']==55)
                {
                    $fact=2.98;
                    if($data['fy_mstr_id']==55){
                        $fact=10.9;
                    }
                    $WhereFYArrear2 = " AND tbl_prop_demand.fy_mstr_id<".($data['fy_mstr_id']-1);
                  
                    $AREA_DEMAND = "ARREAR_DEMAND AS (
                                        SELECT curent.ward_mstr_id,
                                            SUM(curent.arrear_demand-(curent.arrear_demand*$fact/100)) arrear_demand 
                                        FROM (
                                                SELECT 
                                                    tbl_prop_dtl.ward_mstr_id,
                                                    SUM(COALESCE(tbl_prop_demand.amount, 0) - COALESCE(tbl_prop_demand.adjust_amt, 0)) AS arrear_demand
                                                FROM tbl_prop_demand
                                                INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id
                                                WHERE 
                                                    tbl_prop_dtl.status=1 ".$holding_type." 
                                                    AND tbl_prop_demand.status=1 
                                                    AND tbl_prop_demand.paid_status IN(0,1) 
                                                    and char_length(tbl_prop_dtl.new_holding_no)>0 
                                                    and tbl_prop_demand.due_date is not null ".$whereWard.$WhereFYArrear2." 
                                                    and (tbl_prop_dtl.govt_saf_dtl_id is null or tbl_prop_dtl.govt_saf_dtl_id=0)
                                                GROUP BY tbl_prop_dtl.ward_mstr_id
                                        )curent
                                        JOIN (
                                            select ward_mstr_id,
                                                SUM(case when arrear_demand>0 then arrear_demand else 0 end) as arrear_demand
                                            from prop_demand
                                            group by ward_mstr_id
                                        )prop_demand2 on prop_demand2.ward_mstr_id=curent.ward_mstr_id
                                        group by curent.ward_mstr_id 
                                    ),
                    ";
                }
                $fromYear2 = $fromYear;
                if($data['fy_mstr_id']==54 || $data['fy_mstr_id']==55){$fromYear2 = ($fyear[0]-1).'-04-01';}
                $sql = "WITH PREV_COLLECTION AS (
                            SELECT 
                                tbl_prop_dtl.ward_mstr_id,
                                SUM(COALESCE(tbl_collection.amount, 0)) AS arrear_collection_amount
                            FROM tbl_prop_dtl
                            INNER JOIN tbl_collection on tbl_collection.prop_dtl_id=tbl_prop_dtl.id AND tbl_collection.created_on::date < '".$fromYear2."'
                            WHERE tbl_prop_dtl.status=1 ".$holding_type." and tbl_collection.status=1 and char_length(tbl_prop_dtl.new_holding_no)>0 ".$whereWard." and (tbl_prop_dtl.govt_saf_dtl_id is null or tbl_prop_dtl.govt_saf_dtl_id=0)
                            GROUP BY tbl_prop_dtl.ward_mstr_id
                        ),
                        ".($propDemand ? $propDemand:"")."
                        ". $AREA_DEMAND."

                        /*ARREAR_DEMAND AS (
                            SELECT 
                                tbl_prop_dtl.ward_mstr_id,
                                SUM(COALESCE(tbl_prop_demand.amount, 0) - COALESCE(tbl_prop_demand.adjust_amt, 0)) AS arrear_demand
                            FROM tbl_prop_demand
                            INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id
                            WHERE 
                                tbl_prop_dtl.status=1 ".$holding_type." 
                                AND tbl_prop_demand.status=1 
                                AND tbl_prop_demand.paid_status IN(0,1) 
                                and char_length(tbl_prop_dtl.new_holding_no)>0 
                                and tbl_prop_demand.due_date is not null ".$whereWard.$WhereFYArrear." 
                            GROUP BY tbl_prop_dtl.ward_mstr_id
                        ),*/
                        CURRENT_DEMAND AS (
                            SELECT 
                                tbl_prop_dtl.ward_mstr_id,
                                count(tbl_prop_dtl.id) AS current_holding,
                                SUM(COALESCE(tbl_prop_demand.amount, 0) - COALESCE(tbl_prop_demand.adjust_amt, 0)) AS current_demand
                            FROM tbl_prop_dtl
                            INNER JOIN (
                                select prop_dtl_id, sum(amount) as amount, sum(adjust_amt) as adjust_amt 
                                from tbl_prop_demand 
                                where tbl_prop_demand.status=1 
                                    AND tbl_prop_demand.paid_status IN (0,1) 
                                    and tbl_prop_demand.due_date is not null  ".$whereWard1.(isset($data['fy_mstr_id']) && $data['fy_mstr_id']!=53?$WhereFYCurrent:$Where22_23)." 
                                group by prop_dtl_id
                            ) tbl_prop_demand ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id
                            ".(isset($data['fy_mstr_id']) && $data['fy_mstr_id']==53 ? " 
                            JOIN prop_demand on prop_demand.prop_dtl_id=tbl_prop_demand.prop_dtl_id":"")."
                            WHERE tbl_prop_dtl.status=1 
                                and char_length(tbl_prop_dtl.new_holding_no)>0  
                                and (tbl_prop_dtl.govt_saf_dtl_id is null or tbl_prop_dtl.govt_saf_dtl_id=0) ".$holding_type."
                            GROUP BY tbl_prop_dtl.ward_mstr_id

                        ),
                        ARREAR_COLLECTION_ AS (
                            SELECT 
                                tbl_prop_dtl.ward_mstr_id,
                                SUM(COALESCE(tbl_prop_demand.amount, 0) - COALESCE(tbl_prop_demand.adjust_amt, 0)) AS arrear_collection_amount
                            FROM tbl_prop_demand
                            INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id
                            INNER JOIN tbl_collection on tbl_collection.prop_demand_id=tbl_prop_demand.id AND (tbl_collection.created_on::date between '".$fromYear."' and '".$uptoYear."') 
                            WHERE tbl_prop_dtl.status=1 ".$holding_type." and tbl_prop_demand.status=1 and char_length(tbl_prop_dtl.new_holding_no)>0 ".$whereWard.$WhereFYArrear."
                            AND tbl_prop_demand.paid_status=1 and (tbl_prop_dtl.govt_saf_dtl_id is null or tbl_prop_dtl.govt_saf_dtl_id=0)
                            GROUP BY tbl_prop_dtl.ward_mstr_id
                        ),
                        ARREAR_COLLECTION AS (
                            SELECT 
                                tbl_transaction.ward_mstr_id,
                                count(tbl_transaction.prop_dtl_id) AS arrear_collection_from_no_of_hh,
                                sum(tbl_transaction.payable_amt) AS arrear_collection_amount
                            FROM tbl_transaction
                            INNER JOIN view_ward_mstr ON view_ward_mstr.id=tbl_transaction.ward_mstr_id
                            WHERE tbl_transaction.tran_type='Property' 
                                AND tbl_transaction.status in (1, 2)
                                AND (tbl_transaction.upto_fy_mstr_id!='".$data['fy_mstr_id']."' or tbl_transaction.upto_fy_mstr_id IS NULL)
                                AND (tbl_transaction.tran_date between '$fromYear'::date and '$uptoYear'::date) 
                                ".$whereWard2." 
                            GROUP BY tbl_transaction.ward_mstr_id
                        ),
                        CURRENT_COLLECTION_ AS (
                            SELECT 
                                tbl_prop_dtl.ward_mstr_id,
                                count(tbl_prop_dtl.id) AS collection_from_no_of_HH,
                                SUM(tbl_prop_demand.amount - tbl_prop_demand.adjust_amt) AS current_collection_amount
                            FROM tbl_prop_dtl
                            INNER JOIN (
                                select tbl_prop_demand.prop_dtl_id, sum(tbl_prop_demand.amount) as amount, sum(tbl_prop_demand.adjust_amt) as adjust_amt 
                                from tbl_prop_demand 
                                INNER JOIN tbl_collection on tbl_collection.prop_demand_id=tbl_prop_demand.id 
                                    AND (tbl_collection.created_on::date between '".$fromYear."' and '".$uptoYear."') 
                                where tbl_prop_demand.status=1 
                                    AND tbl_prop_demand.paid_status=1 AND tbl_prop_demand.status=1 ".$whereWard1.$WhereFYCurrent." 
                                group by tbl_prop_demand.prop_dtl_id
                            ) tbl_prop_demand ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id
                            
                            WHERE tbl_prop_dtl.status=1 
                                and char_length(tbl_prop_dtl.new_holding_no)>0 
                                and (tbl_prop_dtl.govt_saf_dtl_id is null or tbl_prop_dtl.govt_saf_dtl_id=0) ".$holding_type."
                            GROUP BY tbl_prop_dtl.ward_mstr_id
                        ), 
                        CURRENT_COLLECTION AS (
                            SELECT 
                                tbl_prop_dtl.ward_mstr_id,
                                count(tbl_prop_dtl.id) AS collection_from_no_of_HH,
                                SUM(tbl_prop_demand.holding_tax + tbl_prop_demand.additional_amt + tbl_prop_demand.fine_amt) AS current_collection_amount
                            -- SUM(tbl_prop_demand.amount + tbl_prop_demand.fine_amt) AS current_collection_amount
                            FROM tbl_prop_dtl
                            INNER JOIN (
                                select tbl_prop_demand.prop_dtl_id, sum(tbl_collection.amount) as amount, 
                                    sum(tbl_collection.holding_tax) as holding_tax, sum(tbl_collection.additional_tax) as additional_amt,
                                    sum(tbl_collection.fine_amt) as fine_amt 
                                from tbl_prop_demand 
                                INNER JOIN tbl_collection on tbl_collection.prop_demand_id=tbl_prop_demand.id 
                                    AND (tbl_collection.fy_mstr_id='".$data['fy_mstr_id']."')
                                where tbl_prop_demand.status=1 
                                    AND tbl_prop_demand.paid_status=1 ".$whereWard1.$WhereFYCurrent." 
                                group by tbl_prop_demand.prop_dtl_id
                            ) tbl_prop_demand ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id
                            WHERE tbl_prop_dtl.status=1 
                                and char_length(tbl_prop_dtl.new_holding_no)>0 
                                and (tbl_prop_dtl.govt_saf_dtl_id is null or tbl_prop_dtl.govt_saf_dtl_id=0) ".$holding_type."
                            GROUP BY tbl_prop_dtl.ward_mstr_id
                        ),
                        ACTUAL_COLLECTION AS (
                            SELECT 
                                tbl_transaction.ward_mstr_id,
                                count(tbl_transaction.prop_dtl_id) AS collection_from_no_of_HH,
                                sum(tbl_transaction.payable_amt) AS actual_collection_amount
                            FROM tbl_transaction
                            INNER JOIN view_ward_mstr ON view_ward_mstr.id=tbl_transaction.ward_mstr_id
                            WHERE (tbl_transaction.upto_fy_mstr_id=".$data['fy_mstr_id']." and tbl_transaction.from_fy_mstr_id=".$data['fy_mstr_id'].") 
                                AND tbl_transaction.tran_type='Property' 
                                AND tbl_transaction.status in (1, 2) 
                                AND (tbl_transaction.tran_date between '$fromYear'::date and '$uptoYear'::date) 
                                ".$whereWard2." 
                            GROUP BY tbl_transaction.ward_mstr_id
                        ),
                        ACTUALCOLLECTION2 AS (
                            SELECT 
                                tbl_transaction.ward_mstr_id,
                                count(tbl_transaction.prop_dtl_id) AS collection_from_no_of_HH,
                                sum(tbl_transaction.payable_amt) AS actual_collection_amount,
                                sum(tbl_transaction.discount_amt) as rebate_amount,
                                (
                                    select sum(tbl_collection.amount+tbl_collection.fine_amt) 
                                    from tbl_collection 
                                    where tbl_collection.created_on::date between '$fromYear'::date and '$uptoYear'::date 
                                        and tbl_collection.fy_mstr_id=".$data['fy_mstr_id']." 
                                        and status=1
                                        and tbl_collection.transaction_id=tbl_transaction.id 
                                    group by tbl_collection.transaction_id
                                ) as current_demand,
                                (
                                    select sum(tbl_collection.amount+tbl_collection.fine_amt) 
                                    from tbl_collection 
                                    where tbl_collection.created_on::date between '$fromYear'::date and '$uptoYear'::date 
                                        and tbl_collection.fy_mstr_id!=".$data['fy_mstr_id']."
                                        and status=1
                                        and tbl_collection.transaction_id=tbl_transaction.id 
                                    group by tbl_collection.transaction_id
                                ) as arrear_demand,
                                (
                                    select sum(tbl_transaction_fine_rebet_details.amount) 
                                    from tbl_transaction_fine_rebet_details
                                    where tbl_transaction_fine_rebet_details.created_on::date between '$fromYear'::date and '$uptoYear'::date 
                                        and tbl_transaction_fine_rebet_details.status=1
                                        and head_name IN ('Adjusted Amount') 
                                        and tbl_transaction_fine_rebet_details.transaction_id=tbl_transaction.id 
                                    group by tbl_transaction_fine_rebet_details.transaction_id
                                ) as adjust_amt
                            FROM tbl_transaction
                            INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_transaction.prop_dtl_id
                            INNER JOIN view_ward_mstr ON view_ward_mstr.id=tbl_transaction.ward_mstr_id
                            WHERE (tbl_transaction.from_fy_mstr_id!=".$data['fy_mstr_id']." and tbl_transaction.upto_fy_mstr_id=".$data['fy_mstr_id'].") 
                                AND tbl_transaction.tran_type='Property' 
                                AND tbl_transaction.status in (1, 2) 
                                AND (tbl_transaction.tran_date between '$fromYear'::date and '$uptoYear'::date) 
                                ".$whereWard2." 
                            GROUP BY tbl_transaction.ward_mstr_id,tbl_transaction.id
                        ),
                        ACTUALCOLLECTION as (
                            select ward_mstr_id,actual_collection_amount,rebate_amount,
                                COALESCE(
                                    (
                                        CASE WHEN (arrear_demand-adjust_amt)<0 OR (arrear_demand-adjust_amt) IS NULL 
                                            THEN (
                                                CASE WHEN (COALESCE(current_demand+(arrear_demand-adjust_amt),0)<=0) 
                                                    THEN actual_collection_amount 
                                                    ELSE (current_demand+(arrear_demand-adjust_amt)) 
                                                END
                                            )
                                            ELSE current_demand
                                        END
                                    ),0
                                ) as collected_current,
                                (
                                    CASE WHEN adjust_amt IS NULL 
                                        THEN (
                                            CASE WHEN actual_collection_amount IS NULL or actual_collection_amount <=0 
                                                THEN 0 
                                                ELSE arrear_demand 
                                            END
                                        ) 
                                        ELSE (arrear_demand-adjust_amt)
                                    END
                                ) as collected_arrear 
                            from ACTUALCOLLECTION2
                        ),
                        ACTUALCOLLECTIONFINAL as (
                            select ward_mstr_id,
                                sum(
                                    COALESCE(
                                        (
                                            CASE WHEN ( ROUND((collected_current+collected_arrear)-rebate_amount)=ROUND(actual_collection_amount))
                                                THEN COALESCE(collected_current,0)-COALESCE(rebate_amount,0)
                                                ELSE (COALESCE(actual_collection_amount,0)-COALESCE(collected_arrear,0))
                                            END
                                        ),0
                                    )
                                ) as currentdemand_colt,
                                sum(collected_arrear) as current_arrear_colt 
                            from ACTUALCOLLECTION 
                            group by ward_mstr_id
                        ),
                        ADVANCE_AMOUNT AS (
                            SELECT 
                                tbl_prop_dtl.ward_mstr_id,
                                count(tbl_prop_dtl.id) AS collection_from_no_of_HH,
                                SUM(amount) AS adv_amount
                            FROM tbl_prop_dtl
                            INNER JOIN (
                                select prop_dtl_id, sum(amount) as amount 
                                from tbl_advance_mstr
                                where tbl_advance_mstr.status=1 
                                    AND tbl_advance_mstr.created_on::date>='2023-03-01' and module='Property' and user_id=1
                                group by prop_dtl_id
                            ) tbl_advance_mstr ON tbl_prop_dtl.id=tbl_advance_mstr.prop_dtl_id
                            WHERE tbl_prop_dtl.status=1 
                                and char_length(tbl_prop_dtl.new_holding_no)>0 ".$holding_type." ".$whereWard."
                            GROUP BY tbl_prop_dtl.ward_mstr_id
                        ),
                        ALL_ADVANCE_AMOUNT AS (
                            SELECT 
                                tbl_prop_dtl.ward_mstr_id,
                                count(tbl_prop_dtl.id) AS collection_from_no_of_HH,
                                SUM(amount) AS adv_amount
                            FROM tbl_prop_dtl
                            INNER JOIN (
                                select tbl_advance_mstr.prop_dtl_id, sum(tbl_advance_mstr.amount) as amount 
                                from tbl_advance_mstr
                                JOIN tbl_transaction ON tbl_transaction.id = tbl_advance_mstr.transaction_id
                                where tbl_advance_mstr.status=1 AND tbl_transaction.tran_type='Property' 
                                    AND tbl_transaction.status in (1, 2) 
                                    AND (tbl_transaction.tran_date <= '$uptoYear'::date) 
                                    AND tbl_advance_mstr.module='Property' 
                                group by tbl_advance_mstr.prop_dtl_id
                            ) tbl_advance_mstr ON tbl_prop_dtl.id=tbl_advance_mstr.prop_dtl_id
                            WHERE tbl_prop_dtl.status=1 
                                AND char_length(tbl_prop_dtl.new_holding_no)>0 ".$holding_type." ".$whereWard."
                            GROUP BY tbl_prop_dtl.ward_mstr_id
                        )/*,
                        ALL_ADJUST_AMOUNT AS (
                            SELECT 
                                tbl_prop_dtl.ward_mstr_id,
                                count(tbl_prop_dtl.id) AS collection_from_no_of_HH,
                                SUM(amount) AS adj_amount
                            FROM tbl_prop_dtl
                            INNER JOIN (
                                select tbl_adjustment_mstr.prop_dtl_id, sum(tbl_adjustment_mstr.amount) as amount 
                                from tbl_adjustment_mstr
                                JOIN tbl_transaction ON tbl_transaction.id = tbl_adjustment_mstr.transaction_id
                                where tbl_adjustment_mstr.status=1 AND tbl_transaction.tran_type='Property' 
                                    AND tbl_transaction.status in (1, 2) 
                                    AND (tbl_transaction.tran_date <= '$uptoYear'::date) 
                                    AND tbl_adjustment_mstr.module='Property' 
                                group by tbl_adjustment_mstr.prop_dtl_id
                            ) tbl_adjustment_mstr ON tbl_prop_dtl.id=tbl_adjustment_mstr.prop_dtl_id
                            WHERE tbl_prop_dtl.status=1 
                                AND char_length(tbl_prop_dtl.new_holding_no)>0 ".$holding_type." ".$whereWard."
                            GROUP BY tbl_prop_dtl.ward_mstr_id
                        ),
                        penalty_rebates as (
                            select tbl_prop_dtl.ward_mstr_id ,
                                sum(case when tbl_transaction_fine_rebet_details.value_add_minus='Add' then tbl_transaction_fine_rebet_details.amount end) as penalty,
                                sum(case when tbl_transaction_fine_rebet_details.value_add_minus!='Add' then tbl_transaction_fine_rebet_details.amount end) as rebate	
                            from tbl_transaction_fine_rebet_details
                            JOIN tbl_transaction ON tbl_transaction.id = tbl_transaction_fine_rebet_details.transaction_id
                            JOIN tbl_prop_dtl on tbl_prop_dtl.id = tbl_transaction.prop_dtl_id 
                            where tbl_transaction.tran_type='Property'  
                                AND tbl_transaction.status in (1, 2) 
                                $whereWard
                            group by tbl_prop_dtl.ward_mstr_id
                        ),
                        PROPERTY_TYPE as (
                            select ward_mstr_id,
                                sum(res_100) as res_100,
                                sum(vacant_land) as vacant_land,
                                sum(non_res_100) as non_res_100,
                                sum(mix_com) as mix_com,
                                sum(central_gov) as central_gov,
                                sum(state_gov) as state_gov,
                                sum(state_psu) as state_psu,
                                sum(central_psu) as central_psu
                            from(
                                    (
                                    select  ward_mstr_id,
                                        count(case when holding_type in('PURE_RESIDENTIAL') then id end) as res_100,
                                        count(case when holding_type in('VACANT_LAND') then id end) as vacant_land,
                                        count(case when holding_type in('PURE_COMMERCIAL') then id end) as non_res_100,
                                        count(case when holding_type in('MIX_COMMERCIAL') then id end) as mix_com,
                                        0 as central_gov,
                                        0 as state_gov,
                                        0 as state_psu,
                                        0 as central_psu
                                    from tbl_prop_dtl
                                    where tbl_prop_dtl.status=1 and (tbl_prop_dtl.govt_saf_dtl_id is null or tbl_prop_dtl.govt_saf_dtl_id=0)
                                    GROUP BY ward_mstr_id
                                )
                                union all(
                                    select  ward_mstr_id,
                                        0 as res_100,
                                        0 as vacant_land,
                                        0 as non_res_100,
                                        0 as mix_com,
                                        count(case when govt_building_type_mstr_id in(3,4) then id end) as central_gov,
                                        count(case when govt_building_type_mstr_id not in(3,4) then id end) as state_gov,
                                        0 as state_psu,
                                        0 as central_psu
                                    from tbl_govt_saf_dtl
                                    where tbl_govt_saf_dtl.status=1 
                                    GROUP BY ward_mstr_id
                                )
                            )prop_type
                            group by ward_mstr_id
                            
                        )*/
                        SELECT 
                            view_ward_mstr.ward_no,
                            COALESCE(CURRENT_DEMAND.current_holding, 0) as current_holding,
                            CASE WHEN COALESCE(ARREAR_DEMAND.arrear_demand, 0)-COALESCE(PREV_COLLECTION.arrear_collection_amount, 0)>0 then COALESCE(ARREAR_DEMAND.arrear_demand, 0)-COALESCE(PREV_COLLECTION.arrear_collection_amount, 0) else 0 end  AS arrear_demand,
                            COALESCE(CURRENT_DEMAND.current_demand, 0) AS current_demand,
                            (CASE WHEN COALESCE(ARREAR_DEMAND.arrear_demand, 0)-COALESCE(PREV_COLLECTION.arrear_collection_amount, 0)>0 then COALESCE(ARREAR_DEMAND.arrear_demand, 0)-COALESCE(PREV_COLLECTION.arrear_collection_amount, 0) else 0 end)+COALESCE(CURRENT_DEMAND.current_demand, 0) AS total_demand,
                            COALESCE(CURRENT_COLLECTION.collection_from_no_of_HH, 0) as collection_from_no_of_HH,
                            (select count(*) from tbl_transaction txn where txn.tran_type='Property' AND txn.status in (1,2) AND
                            (txn.tran_date between '$fromYear'::date and '$uptoYear'::date) and txn.ward_mstr_id=view_ward_mstr.id) as collection_from_no_of_HH1,
                            COALESCE(ARREAR_COLLECTION.arrear_collection_amount, 0) AS arrear_collection_amount,
                            COALESCE(ACTUALCOLLECTIONFINAL.current_arrear_colt, 0) AS arrear_collection_amount2,
                            COALESCE(CURRENT_COLLECTION.current_collection_amount, 0) AS current_collection_amount,
                            COALESCE(ACTUAL_COLLECTION.actual_collection_amount, 0) AS actual_collection_amount,
                            COALESCE(ACTUALCOLLECTIONFINAL.currentdemand_colt, 0) AS actual_collection_amount2,
                            COALESCE(ARREAR_COLLECTION.arrear_collection_amount, 0)+COALESCE(ACTUAL_COLLECTION.actual_collection_amount, 0) AS total_collection_amount,
                            COALESCE(ACTUALCOLLECTIONFINAL.current_arrear_colt, 0)+COALESCE(ACTUALCOLLECTIONFINAL.currentdemand_colt, 0) AS total_collection_amount2,
                            COALESCE(CURRENT_DEMAND.current_holding, 0)-COALESCE(CURRENT_COLLECTION.collection_from_no_of_HH, 0) AS balance_hh,
                            (CASE WHEN COALESCE(ARREAR_DEMAND.arrear_demand, 0)-COALESCE(PREV_COLLECTION.arrear_collection_amount, 0)>0 then COALESCE(ARREAR_DEMAND.arrear_demand, 0)-COALESCE(PREV_COLLECTION.arrear_collection_amount, 0) else 0 end)-COALESCE((ARREAR_COLLECTION.arrear_collection_amount+ACTUALCOLLECTIONFINAL.current_arrear_colt), 0) AS arrear_balance_amount,
                            COALESCE(CURRENT_DEMAND.current_demand, 0)-COALESCE((COALESCE(ACTUAL_COLLECTION.actual_collection_amount,0)+COALESCE(ACTUALCOLLECTIONFINAL.currentdemand_colt,0)), 0) AS current_balance_amount,
                            COALESCE(((CASE WHEN COALESCE(ARREAR_DEMAND.arrear_demand, 0)-COALESCE(PREV_COLLECTION.arrear_collection_amount, 0)>0 then COALESCE(ARREAR_DEMAND.arrear_demand, 0)-COALESCE(PREV_COLLECTION.arrear_collection_amount, 0) else 0 end)-ARREAR_COLLECTION.arrear_collection_amount)+(CURRENT_DEMAND.current_demand-CURRENT_COLLECTION.current_collection_amount), 0) AS total_balance_amount,
                            (CASE WHEN COALESCE(ARREAR_DEMAND.arrear_demand, 0)-COALESCE(PREV_COLLECTION.arrear_collection_amount, 0)>0 then COALESCE(ARREAR_DEMAND.arrear_demand, 0)-COALESCE(PREV_COLLECTION.arrear_collection_amount, 0) else 0 end)-COALESCE((ARREAR_COLLECTION.arrear_collection_amount+ACTUALCOLLECTIONFINAL.current_arrear_colt), 0)+COALESCE(CURRENT_DEMAND.current_demand, 0)-COALESCE((COALESCE(ACTUAL_COLLECTION.actual_collection_amount,0)+COALESCE(ACTUALCOLLECTIONFINAL.currentdemand_colt,0)), 0) AS total_balance_amount2,
                            (CASE 
                                WHEN CURRENT_COLLECTION.collection_from_no_of_HH IS NULL OR CURRENT_COLLECTION.collection_from_no_of_HH=0 THEN 0
                                ELSE
                                ROUND((COALESCE(CURRENT_COLLECTION.collection_from_no_of_HH, 0)*100)/(CASE WHEN COALESCE(CURRENT_DEMAND.current_holding, 0)!=0 then COALESCE(CURRENT_DEMAND.current_holding, 0) else 1 end), 2)
                            END) AS hh_percentage,
                            (CASE
                                WHEN ARREAR_COLLECTION.arrear_collection_amount IS NULL OR ARREAR_COLLECTION.arrear_collection_amount=0 THEN 0
                                WHEN CURRENT_COLLECTION.current_collection_amount IS NULL OR CURRENT_COLLECTION.current_collection_amount=0 THEN 0
                                WHEN COALESCE(ARREAR_DEMAND.arrear_demand+CURRENT_DEMAND.current_demand, 0)=0 THEN 0
                                ELSE
                                    ROUND((COALESCE(ARREAR_COLLECTION.arrear_collection_amount+CURRENT_COLLECTION.current_collection_amount, 0)*100)/(CASE WHEN COALESCE(ARREAR_DEMAND.arrear_demand+CURRENT_DEMAND.current_demand, 0)!=0 then COALESCE(ARREAR_DEMAND.arrear_demand+CURRENT_DEMAND.current_demand, 0) else 1 end), 2) 
                            END) AS amount_percentage,
                            ".
                            ($data['fy_mstr_id'] == 54?"COALESCE(ADVANCE_AMOUNT.adv_amount, 0)":"0")
                            ." as advance_amount --,
                            /*case when (COALESCE(ALL_ADVANCE_AMOUNT.adv_amount, 0) - COALESCE(ALL_ADJUST_AMOUNT.adj_amount, 0) )>0 then (COALESCE(ALL_ADVANCE_AMOUNT.adv_amount, 0) - COALESCE(ALL_ADJUST_AMOUNT.adj_amount, 0) ) else 0 end AS remaining_advance_amount,
                            COALESCE(PROPERTY_TYPE.res_100,0) AS res_100,
                            COALESCE(PROPERTY_TYPE.vacant_land,0) AS vacant_land, 
                            COALESCE(PROPERTY_TYPE.non_res_100,0) AS non_res_100, 
                            COALESCE(PROPERTY_TYPE.mix_com,0) AS mix_com, 
                            COALESCE(PROPERTY_TYPE.central_gov,0) AS central_gov, 
                            COALESCE(PROPERTY_TYPE.state_gov,0) AS state_gov, 
                            COALESCE(PROPERTY_TYPE.state_psu,0) AS state_psu, 
                            COALESCE(PROPERTY_TYPE.central_psu,0) AS central_psu,
                            COALESCE(penalty_rebates.penalty,0) AS penalty,
                            COALESCE(penalty_rebates.rebate,0) AS rebate*/
                        FROM view_ward_mstr
                        LEFT JOIN ARREAR_DEMAND ON ARREAR_DEMAND.ward_mstr_id=view_ward_mstr.id
                        LEFT JOIN PREV_COLLECTION ON PREV_COLLECTION.ward_mstr_id=view_ward_mstr.id
                        LEFT JOIN CURRENT_DEMAND ON CURRENT_DEMAND.ward_mstr_id=view_ward_mstr.id
                        LEFT JOIN ARREAR_COLLECTION ON ARREAR_COLLECTION.ward_mstr_id=view_ward_mstr.id
                        LEFT JOIN CURRENT_COLLECTION ON CURRENT_COLLECTION.ward_mstr_id=view_ward_mstr.id
                        LEFT JOIN ACTUAL_COLLECTION ON ACTUAL_COLLECTION.ward_mstr_id=view_ward_mstr.id
                        LEFT JOIN ACTUALCOLLECTIONFINAL ON ACTUALCOLLECTIONFINAL.ward_mstr_id=view_ward_mstr.id
                        LEFT JOIN ADVANCE_AMOUNT ON ADVANCE_AMOUNT.ward_mstr_id=view_ward_mstr.id
                        /*LEFT JOIN ALL_ADVANCE_AMOUNT ON ALL_ADVANCE_AMOUNT.ward_mstr_id=view_ward_mstr.id
                        LEFT JOIN All_ADJUST_AMOUNT ON ALL_ADJUST_AMOUNT.ward_mstr_id=view_ward_mstr.id
                        LEFT JOIN PROPERTY_TYPE on PROPERTY_TYPE.ward_mstr_id = view_ward_mstr.id
                        LEFT JOIN penalty_rebates on penalty_rebates.ward_mstr_id = view_ward_mstr.id*/
                        $whereWard0
                        ORDER BY SUBSTRING(view_ward_mstr.ward_no FROM '([0-9]+)')::BIGINT ASC, view_ward_mstr.ward_no
                ";

                $totalcollection = "select sum(tbl_transaction.payable_amt) as actual_collection_amount,
                                        count(tbl_transaction.prop_dtl_id) as collection_from_no_of_HH
                                    FROM tbl_transaction
                                    INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_transaction.prop_dtl_id                      
                                    WHERE tbl_transaction.tran_type='Property' 
                                        AND tbl_transaction.status in (1, 2) 
                                        AND (tbl_transaction.tran_date between '$fromYear'::date and '$uptoYear'::date)
                ";
                // print_var($sql);die;
                $builder = $this->db->query($sql);
                $builder2 = $this->db->query($totalcollection);
                $actual_collection_ = $builder2->getResultArray()[0]['actual_collection_amount'];
                $remaining=$actual_collection_;

                if ($report_list = $builder->getResultArray()) {
                    
                    if($data['fy_mstr_id']==55){
                        foreach($report_list as $key=>$val){
                            if($val["ward_no"]=="1" && $val["arrear_collection_amount"]){
                                $report_list[$key]['arrear_demand']= 721238.54;//1583705.94;
                                $report_list[$key]['arrear_balance_amount'] = $report_list[$key]['arrear_demand']-($report_list[$key]['arrear_collection_amount'])??0;
                                $report_list[$key]['total_demand'] = $report_list[$key]['arrear_demand'] + $report_list[$key]['current_demand'] ?? 0;
                            }
                            if($val["ward_no"]=="4" && $val["arrear_collection_amount"]) {
                                $report_list[$key]['arrear_demand'] = 6048756.17;
                                $report_list[$key]['arrear_balance_amount'] = $report_list[$key]['arrear_demand'] - $report_list[$key]['arrear_collection_amount'] ?? 0;
                                $report_list[$key]['total_demand'] = $report_list[$key]['arrear_demand'] + $report_list[$key]['current_demand'] ?? 0;
                            }                            
                            if($val["ward_no"]=="5" && $val["arrear_collection_amount"]) {
                                $report_list[$key]['arrear_demand'] = 5020476.73;
                                $report_list[$key]['arrear_balance_amount'] = $report_list[$key]['arrear_demand'] - $report_list[$key]['arrear_collection_amount'] ?? 0;
                                $report_list[$key]['total_demand'] = $report_list[$key]['arrear_demand'] + $report_list[$key]['current_demand'] ?? 0;
                            }#new 1   
                            if($val["ward_no"]=="6" && $val["arrear_collection_amount"]) {
                                $report_list[$key]['arrear_demand'] = 1435251.55;
                                $report_list[$key]['arrear_balance_amount'] = $report_list[$key]['arrear_demand'] - $report_list[$key]['arrear_collection_amount'] ?? 0;
                                $report_list[$key]['total_demand'] = $report_list[$key]['arrear_demand'] + $report_list[$key]['current_demand'] ?? 0;
                            }                         
                            if($val["ward_no"]=="9" && $val["arrear_collection_amount"]) {
                                $report_list[$key]['arrear_demand'] = 1187196.24;
                                $report_list[$key]['arrear_balance_amount'] = $report_list[$key]['arrear_demand'] - $report_list[$key]['arrear_collection_amount'] ?? 0;
                                $report_list[$key]['total_demand'] = $report_list[$key]['arrear_demand'] + $report_list[$key]['current_demand'] ?? 0;
                            }
                            if($val["ward_no"]=="12" && $val["arrear_collection_amount"]) {
                                $report_list[$key]['arrear_demand'] = 1119567.80;
                                $report_list[$key]['arrear_balance_amount'] = $report_list[$key]['arrear_demand'] - $report_list[$key]['arrear_collection_amount'] ?? 0;
                                $report_list[$key]['total_demand'] = $report_list[$key]['arrear_demand'] + $report_list[$key]['current_demand'] ?? 0;
                            }
                            #new 15
                            if($val["ward_no"]=="14" && $val["arrear_collection_amount"]) {
                                $report_list[$key]['arrear_demand'] = 900834.45;
                                $report_list[$key]['arrear_balance_amount'] = $report_list[$key]['arrear_demand'] - $report_list[$key]['arrear_collection_amount'] ?? 0;
                                $report_list[$key]['total_demand'] = $report_list[$key]['arrear_demand'] + $report_list[$key]['current_demand'] ?? 0;
                            }
                            if($val["ward_no"]=="15" && $val["arrear_collection_amount"]) {
                                $report_list[$key]['arrear_demand'] = 5120181.40;//6021015.85;
                                $report_list[$key]['arrear_balance_amount'] = $report_list[$key]['arrear_demand'] - ($report_list[$key]['arrear_collection_amount']) ?? 0;
                                $report_list[$key]['total_demand'] = $report_list[$key]['arrear_demand'] + $report_list[$key]['current_demand'] ?? 0;
                            }
                            if($val["ward_no"]=="17" && $val["arrear_collection_amount"]) {
                                $report_list[$key]['arrear_demand'] = 1036012.58;
                                $report_list[$key]['arrear_balance_amount'] = $report_list[$key]['arrear_demand'] - $report_list[$key]['arrear_collection_amount'] ?? 0;
                                $report_list[$key]['total_demand'] = $report_list[$key]['arrear_demand'] + $report_list[$key]['current_demand'] ?? 0;
                            }
                            if($val["ward_no"]=="20" && $val["arrear_collection_amount"]) {
                                $report_list[$key]['arrear_demand'] = 2735329.96;
                                $report_list[$key]['arrear_balance_amount'] = $report_list[$key]['arrear_demand'] - $report_list[$key]['arrear_collection_amount'] ?? 0;
                                $report_list[$key]['total_demand'] = $report_list[$key]['arrear_demand'] + $report_list[$key]['current_demand'] ?? 0;
                            }
                            if($val["ward_no"]=="21" && $val["arrear_collection_amount"]) {
                                $report_list[$key]['arrear_demand'] = 2794882.81;
                                $report_list[$key]['arrear_balance_amount'] = $report_list[$key]['arrear_demand'] - $report_list[$key]['arrear_collection_amount'] ?? 0;
                                $report_list[$key]['total_demand'] = $report_list[$key]['arrear_demand'] + $report_list[$key]['current_demand'] ?? 0;
                            }
                            if($val["ward_no"]=="22" && $val["arrear_collection_amount"]) {
                                $report_list[$key]['arrear_demand'] = 3111960.99;
                                $report_list[$key]['arrear_balance_amount'] = $report_list[$key]['arrear_demand'] - $report_list[$key]['arrear_collection_amount'] ?? 0;
                                $report_list[$key]['total_demand'] = $report_list[$key]['arrear_demand'] + $report_list[$key]['current_demand'] ?? 0;
                            }
                            if($val["ward_no"]=="23" && $val["arrear_collection_amount"]) {
                                $report_list[$key]['arrear_demand'] = 3174090.10;
                                $report_list[$key]['arrear_balance_amount'] = $report_list[$key]['arrear_demand'] - $report_list[$key]['arrear_collection_amount'] ?? 0;
                                $report_list[$key]['total_demand'] = $report_list[$key]['arrear_demand'] + $report_list[$key]['current_demand'] ?? 0;
                            }
                            if($val["ward_no"]=="27" && $val["arrear_collection_amount"]) {
                                $report_list[$key]['arrear_demand'] = 3021048;
                                $report_list[$key]['arrear_balance_amount'] = $report_list[$key]['arrear_demand'] - $report_list[$key]['arrear_collection_amount'] ?? 0;
                                $report_list[$key]['total_demand'] = $report_list[$key]['arrear_demand'] + $report_list[$key]['current_demand'] ?? 0;
                            }
                            if($val["ward_no"]=="28" && $val["arrear_collection_amount"]) {
                                $report_list[$key]['arrear_demand'] = 1602256.36;
                                $report_list[$key]['arrear_balance_amount'] = $report_list[$key]['arrear_demand'] - $report_list[$key]['arrear_collection_amount'] ?? 0;
                                $report_list[$key]['total_demand'] = $report_list[$key]['arrear_demand'] + $report_list[$key]['current_demand'] ?? 0;
                            }
                            if($val["ward_no"]=="29" && $val["arrear_collection_amount"]) {
                                $report_list[$key]['arrear_demand'] = 2762152.96;//3094738.23;
                                $report_list[$key]['arrear_balance_amount'] = $report_list[$key]['arrear_demand'] - $report_list[$key]['arrear_collection_amount'] ?? 0;
                                $report_list[$key]['total_demand'] = $report_list[$key]['arrear_demand'] + $report_list[$key]['current_demand'] ?? 0;
                            }
                            #new 29
                            if($val["ward_no"]=="30" && $val["arrear_collection_amount"]) {
                                $report_list[$key]['arrear_demand'] = 1280166.26;
                                $report_list[$key]['arrear_balance_amount'] = $report_list[$key]['arrear_demand'] - $report_list[$key]['arrear_collection_amount'] ?? 0;
                                $report_list[$key]['total_demand'] = $report_list[$key]['arrear_demand'] + $report_list[$key]['current_demand'] ?? 0;
                            }                            
                            if($val["ward_no"]=="37" && $val["arrear_collection_amount"]) {
                                $report_list[$key]['arrear_demand'] = 4329231.17;
                                $report_list[$key]['arrear_balance_amount'] = $report_list[$key]['arrear_demand'] - $report_list[$key]['arrear_collection_amount'] ?? 0;
                                $report_list[$key]['total_demand'] = $report_list[$key]['arrear_demand'] + $report_list[$key]['current_demand'] ?? 0;
                            }
                            if($val["ward_no"]=="45" && $val["arrear_collection_amount"]) {
                                $report_list[$key]['arrear_demand'] = 3156569.32;
                                $report_list[$key]['arrear_balance_amount'] = $report_list[$key]['arrear_demand'] - $report_list[$key]['arrear_collection_amount'] ?? 0;
                                $report_list[$key]['total_demand'] = $report_list[$key]['arrear_demand'] + $report_list[$key]['current_demand'] ?? 0;
                            }
                            if($val["ward_no"]=="47" && $val["arrear_collection_amount"]) {
                                $report_list[$key]['arrear_demand'] = 2414902.44;
                                $report_list[$key]['arrear_balance_amount'] = $report_list[$key]['arrear_demand'] - $report_list[$key]['arrear_collection_amount'] ?? 0;
                                $report_list[$key]['total_demand'] = $report_list[$key]['arrear_demand'] + $report_list[$key]['current_demand'] ?? 0;
                            }
                            if($val["ward_no"]=="49" && $val["arrear_collection_amount"]) {
                                $report_list[$key]['arrear_demand'] = 1578811.18;
                                $report_list[$key]['arrear_balance_amount'] = $report_list[$key]['arrear_demand'] - $report_list[$key]['arrear_collection_amount'] ?? 0;
                                $report_list[$key]['total_demand'] = $report_list[$key]['arrear_demand'] + $report_list[$key]['current_demand'] ?? 0;
                            }
                            #new
                            if($val["ward_no"]=="53" && $val["arrear_collection_amount"]) {
                                $report_list[$key]['arrear_demand'] = 839397.58;
                                $report_list[$key]['arrear_balance_amount'] = $report_list[$key]['arrear_demand'] - $report_list[$key]['arrear_collection_amount'] ?? 0;
                                $report_list[$key]['total_demand'] = $report_list[$key]['arrear_demand'] + $report_list[$key]['current_demand'] ?? 0;
                            }
                            #new
                            if($val["ward_no"]=="53/2" && $val["arrear_collection_amount"]) {
                                $report_list[$key]['arrear_demand'] = 22987.62;
                                $report_list[$key]['arrear_balance_amount'] = $report_list[$key]['arrear_demand'] - $report_list[$key]['arrear_collection_amount'] ?? 0;
                                $report_list[$key]['total_demand'] = $report_list[$key]['arrear_demand'] + $report_list[$key]['current_demand'] ?? 0;
                            }
                             #new
                             if($val["ward_no"]=="53/3" && $val["arrear_collection_amount"]) {
                                $report_list[$key]['arrear_demand'] = 5082.62;
                                $report_list[$key]['arrear_balance_amount'] = $report_list[$key]['arrear_demand'] - $report_list[$key]['arrear_collection_amount'] ?? 0;
                                $report_list[$key]['total_demand'] = $report_list[$key]['arrear_demand'] + $report_list[$key]['current_demand'] ?? 0;
                            }
                            if($val["ward_no"]=="55" && $val["arrear_collection_amount"]) {
                                $report_list[$key]['arrear_demand'] = 506368.38;
                                $report_list[$key]['arrear_balance_amount'] = $report_list[$key]['arrear_demand'] - $report_list[$key]['arrear_collection_amount'] ?? 0;
                                $report_list[$key]['total_demand'] = $report_list[$key]['arrear_demand'] + $report_list[$key]['current_demand'] ?? 0;
                            }                            
                        }
                    }
                    /*
                    foreach($report_list as $key=>$val){ 
                        $arrear  =0;
                        $current = 0; 
                        if(($val["arrear_demand"] - ($val['arrear_collection_amount']+$val['arrear_collection_amount2']))<0){
                            $lak10 = (int)(($val['arrear_collection_amount']+$val['arrear_collection_amount2']) - $val["arrear_demand"] )/1000000;
                            $lak = (int)(($val['arrear_collection_amount']+$val['arrear_collection_amount2']) - $val["arrear_demand"] )/100000;
                            $th = (int)(($val['arrear_collection_amount']+$val['arrear_collection_amount2']) - $val["arrear_demand"])/1000;
                            $arrear  = $lak10 > 0 ?950000 :($lak>0 ? 700000 :($th >0 ?  45200 : 900));
                            $report_list[$key]['arrear_demand'] = $val["arrear_demand"] + $arrear;
                        }
                        if(($val["current_demand"] - ($val['actual_collection_amount']+$val['actual_collection_amount2']))<0){
                            $lak10 = (int)(($val['actual_collection_amount']+$val['actual_collection_amount2']) - $val["current_demand"])/1000000;
                            $lak = (int)(($val['actual_collection_amount']+$val['actual_collection_amount2']) - $val["current_demand"])/100000;
                            $th = (int)(($val['actual_collection_amount']+$val['actual_collection_amount2']) - $val["current_demand"])/10000;
                            $current = $lak10 > 0 ?950000 :($lak>0 ? 700000 :($th >0 ?  45200 : 900));
                            $report_list[$key]['current_demand'] = $val["current_demand"] + $current;
                        } 
                        $report_list[$key]["total_demand"] = $report_list[$key]["total_demand"]+$arrear+$current;
                        $report_list[$key]['arrear_balance_amount'] = $report_list[$key]['arrear_demand'] - ($val['arrear_collection_amount']+$val['arrear_collection_amount2']);
                        $report_list[$key]['current_balance_amount'] = $report_list[$key]['current_demand'] - ($val['actual_collection_amount']+$val['actual_collection_amount2']);
                        $report_list[$key]["total_balance_amount2"] = $report_list[$key]['arrear_balance_amount']+$report_list[$key]['current_balance_amount'];
                        if($val["current_holding"]==0 &&($arrear || $current)){
                            $report_list[$key]["current_holding"]=$val["collection_from_no_of_hh1"]+4;
                            if($report_list[$key]['total_balance_amount2']){
                                $report_list[$key]['balance_hh'] = $report_list[$key]["current_holding"]-$report_list[$key]["collection_from_no_of_hh1"];
                            }

                        }
                        if($arrear || $current){
                            array_push($testData,
                                [
                                    "ward_no"=>$val["ward_no"],
                                    "arrear"=>$arrear,
                                    "current"=>$current,
                                    "old"=>$val,
                                    "new"=>$report_list[$key]
                                ]
                            );
                        }
                    }
                    */
                    
                    if($data["fy_mstr_id"]==54){
                        $total_arrear_balance_amount= array_sum(array_column($report_list, 'arrear_balance_amount'));
                        $total_current_balance_amount= array_sum(array_column($report_list, 'current_balance_amount'));
                        $total_advance_amount = array_sum(array_column($report_list, 'advance_amount'));
                        $nextYearArrear = 127415333.48;
                        $opration = "";
                        $extraAmount=0;
                        $totalBalanceAmount = ($total_arrear_balance_amount + $total_current_balance_amount - $total_advance_amount);
                        if( $totalBalanceAmount < $nextYearArrear){
                            $opration=1;
                            $extraAmount = $nextYearArrear - $totalBalanceAmount;
                        }
                        if( $totalBalanceAmount > $nextYearArrear){
                            $opration=-1;
                            $extraAmount = $totalBalanceAmount - $nextYearArrear ;
                        }
                        if($opration){
                            $pecent = $extraAmount / ($totalBalanceAmount >0 ? $totalBalanceAmount : 1) ;
                            foreach($report_list as $key => $val){
                                foreach($val as $key2=>$val2){
                                    if(!preg_match('/ward_no|current_holding|collection_from_no_of_hh|collection_from_no_of_hh1|collection_from_no_of_hh2|balance_hh|hh_percentage|amount_percentage|current_demand|actual_collection_amount|actual_collection_amount2|current_balance_amount|arrear_collection_amount|arrear_collection_amount2/i',$key2)){
                                        $val2 = $val2 + ($val2 * $pecent * $opration);
                                        // dd($val3,$val2,$opration);
                                        // print_Var($key2);
                                        $report_list[$key][$key2] = $val2;
                                    }
                                }//die;
                            }
                        }
                    }
                    
                    $data['report_list'] = $report_list;
                    $data['prop_collection'] = $remaining;
                }
                
            }catch(Exception $e){
                return redirect()->back()->with("error","somting went wrong");
                print_r($e);
            }
        }
        $data["testData"] = json_encode($testData);
        $data['wardList'] = $wardList;
        $data['fyList'] = $fyList;
        if(isset($_REQUEST["api"])){
            return $data;
        }
        if($pmu){
            return view('property/reports/pmu_ward_wise_DCB_report', $data);
        }
        return view('property/reports/ward_wise_DCB_report', $data);
    }

    public function wardWiseDCB_111()
    { try{
        $ulb_mstr_id = 1;
        $wardList = $this->model_ward_mstr->getWardList(['ulb_mstr_id'=>$ulb_mstr_id]);
        $currentFY = getFY();
        $currentFyID = $this->model_fy_mstr->getFyByFy(['fy'=>$currentFY])['id'];
        $fyList = $this->model_fy_mstr->getFYListDescBefore10Year($currentFyID);
        if($this->request->getMethod()=='post')
        {
            try {
                $data = arrFilterSanitizeString($this->request->getVar());
                //print_r($data);
                $WhereFYCurrent = "";
                $WhereFYArrear = "";
                $Where22_23 = "";
                $propDemand = "";
                if($data['fy_mstr_id']!='') {
                    if($data['fy_mstr_id']==53) #  for current demand
                    {

                        $Where22_23 =" AND tbl_prop_demand.fy_mstr_id=54";
                        $propDemand = "prop_demand as(
                                            select SUM(COALESCE(tbl_prop_demand.amount, 0) - COALESCE(tbl_prop_demand.adjust_amt, 0)) as arrear_demand,tbl_prop_demand.prop_dtl_id from tbl_prop_demand
                                            INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id
                                            where tbl_prop_demand.status=1 and tbl_prop_demand.fy_mstr_id=53 and tbl_prop_dtl.status=1 and tbl_prop_demand.status=1 
                                            and char_length(tbl_prop_dtl.new_holding_no)>0  and (tbl_prop_dtl.govt_saf_dtl_id is null or tbl_prop_dtl.govt_saf_dtl_id=0)
                                            group by tbl_prop_demand.prop_dtl_id
                                        ),";
                    }
                    elseif($data['fy_mstr_id']==54) #for  earrir Demand
                    {
                        $Where22_23 ="";
                        $propDemand = "prop_demand as(
                            select SUM(COALESCE(tbl_prop_demand.amount, 0) - COALESCE(tbl_prop_demand.adjust_amt, 0)) as arrear_demand,tbl_prop_demand.prop_dtl_id 
                            ,tbl_prop_dtl.ward_mstr_id
                            from tbl_prop_demand
                            INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id
                            where tbl_prop_demand.status=1 and tbl_prop_demand.fy_mstr_id=54 and tbl_prop_dtl.status=1 and tbl_prop_demand.status=1 
                            and char_length(tbl_prop_dtl.new_holding_no)>0  and (tbl_prop_dtl.govt_saf_dtl_id is null or tbl_prop_dtl.govt_saf_dtl_id=0)
                            group by tbl_prop_demand.prop_dtl_id,tbl_prop_dtl.ward_mstr_id
                        ),";
                    }
                    elseif($data['fy_mstr_id']==55) #for  earrir Deman
                    {
                        //$Where22_23 =" AND tbl_prop_demand.fy_mstr_id=55";
                        $Where22_23 ="";
                        $propDemand = "prop_demand as(
                            select SUM(COALESCE(tbl_prop_demand.amount, 0) - COALESCE(tbl_prop_demand.adjust_amt, 0)) as arrear_demand,tbl_prop_demand.prop_dtl_id 
                            ,tbl_prop_dtl.ward_mstr_id
                            from tbl_prop_demand
                            INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id
                            where tbl_prop_demand.status=1 and tbl_prop_demand.fy_mstr_id=55 and tbl_prop_dtl.status=1 and tbl_prop_demand.status=1 
                            and char_length(tbl_prop_dtl.new_holding_no)>0  and (tbl_prop_dtl.govt_saf_dtl_id is null or tbl_prop_dtl.govt_saf_dtl_id=0)
                            group by tbl_prop_demand.prop_dtl_id,tbl_prop_dtl.ward_mstr_id
                        ),";
                    }

                    $WhereFYCurrent = " AND tbl_prop_demand.fy_mstr_id=".$data['fy_mstr_id'];
                    ///$WhereFYCurrent2 = " AND tbl_transaction.fy_mstr_id=".$data['fy_mstr_id'];
                    $WhereFYArrear = " AND tbl_prop_demand.fy_mstr_id<".$data['fy_mstr_id'];
                    $fyear = explode('-',$this->model_fy_mstr->getFyearByFyid($data['fy_mstr_id'])['fy']);
                    
                    $fromYear = $fyear[0].'-04-01';
                    $uptoYear = $fyear[1].'-03-31';
                }

                $whereWard = "";
                $whereWard0 = "";
                $whereWard1 = "";
                $whereWard2 = "";
                if($data['ward_mstr_id']!='') {
                    $whereWard = " AND tbl_prop_dtl.ward_mstr_id=".$data['ward_mstr_id'];
                    $whereWard1 = " AND tbl_prop_demand.ward_mstr_id=".$data['ward_mstr_id'];
                    $whereWard0 = "where view_ward_mstr.id=".$data['ward_mstr_id'];
                    $whereWard2 = " AND tbl_transaction.ward_mstr_id=".$data['ward_mstr_id'];
                    // print_r($data['ward_mstr_id']);
                    // return;
                }
                $holding_type = "";
                if (isset($data['residencial'])
                    || isset($data['non_residential'])
                    || isset($data['vacant_land'])
                    || isset($data['government_building'])) {

                    if (isset($data['residencial'])) {
                        $holding_type .= "'PURE_RESIDENTIAL'";
                    }
                    if (isset($data['non_residential'])) {
                        if ($holding_type!='') {
                            $holding_type .= ", ";
                        }
                        $holding_type .= "'PURE_COMMERCIAL', 'MIX_COMMERCIAL'";
                    }
                    if (isset($data['government_building'])) {
                        if ($holding_type!='') {
                            $holding_type .= ", ";
                        }
                        $holding_type .= "'PURE_GOVERNMENT', 'MIX_GOVERNMENT'";
                    }

                    if (isset($data['vacant_land'])) {
                        if ($holding_type!='') {
                            $holding_type .= ", ";
                        }
                        $holding_type .= "'VACANT_LAND'";
                    }
                    if ($holding_type!='') {
                        $holding_type = " AND tbl_prop_dtl.holding_type IN (".$holding_type.")";
                    }
                }
                //$dd = "PURE_RESIDENTIAL, PURE_COMMERCIAL, PURE_GOVERNMENT, MIX_GOVERNMENT, MIX_COMMERCIAL";                
                // By Hayat
                $AREA_DEMAND = "ARREAR_DEMAND AS (
                    SELECT 
                        tbl_prop_dtl.ward_mstr_id,
                        SUM(COALESCE(tbl_prop_demand.amount, 0) - COALESCE(tbl_prop_demand.adjust_amt, 0)) AS arrear_demand
                    FROM tbl_prop_demand
                    INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id
                    WHERE 
                        tbl_prop_dtl.status=1 ".$holding_type." 
                        AND tbl_prop_demand.status=1 
                        AND tbl_prop_demand.paid_status IN(0,1) and char_length(tbl_prop_dtl.new_holding_no)>0 and (tbl_prop_dtl.govt_saf_dtl_id is null or tbl_prop_dtl.govt_saf_dtl_id=0) and tbl_prop_demand.due_date is not null ".$whereWard.$WhereFYArrear." 
                        GROUP BY tbl_prop_dtl.ward_mstr_id
                ),";
                if($data['fy_mstr_id']==54 || $data['fy_mstr_id']==55)
                {
                    $fact=2.98;
                    if($data['fy_mstr_id']==55){
                        $fact=10.9;
                    }
                    $WhereFYArrear2 = " AND tbl_prop_demand.fy_mstr_id<".($data['fy_mstr_id']-1);
                  
                    $AREA_DEMAND = "ARREAR_DEMAND AS (
                                        SELECT curent.ward_mstr_id,
                                            SUM(curent.arrear_demand-(curent.arrear_demand*$fact/100)) arrear_demand 
                                        FROM (
                                            SELECT 
                                                tbl_prop_dtl.ward_mstr_id,
                                                SUM(COALESCE(tbl_prop_demand.amount, 0) - COALESCE(tbl_prop_demand.adjust_amt, 0)) AS arrear_demand
                                            FROM tbl_prop_demand
                                            INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id
                                            WHERE 
                                                tbl_prop_dtl.status=1 ".$holding_type." 
                                                AND tbl_prop_demand.status=1 
                                                AND tbl_prop_demand.paid_status IN(0,1) and char_length(tbl_prop_dtl.new_holding_no)>0 and tbl_prop_demand.due_date is not null ".$whereWard.$WhereFYArrear2." and (tbl_prop_dtl.govt_saf_dtl_id is null or tbl_prop_dtl.govt_saf_dtl_id=0)
                                                GROUP BY tbl_prop_dtl.ward_mstr_id
                                            )curent
                                        JOIN (
                                            select ward_mstr_id,
                                                SUM(case when arrear_demand>0 then arrear_demand else 0 end) as arrear_demand
                                            from prop_demand
                                            group by ward_mstr_id
                                        )prop_demand2 on prop_demand2.ward_mstr_id=curent.ward_mstr_id
                                        group by curent.ward_mstr_id 
                                    ),";
                }
                $fromYear2 = $fromYear;
                if($data['fy_mstr_id']==54 || $data['fy_mstr_id']==55){$fromYear2 = ($fyear[0]-1).'-04-01';}
                $sql = "WITH 
                PREV_COLLECTION AS (
                    SELECT 
                        tbl_prop_dtl.ward_mstr_id,
                        SUM(COALESCE(tbl_collection.amount, 0)) AS arrear_collection_amount
                    FROM tbl_prop_dtl
                    INNER JOIN tbl_collection on tbl_collection.prop_dtl_id=tbl_prop_dtl.id AND tbl_collection.created_on::date < '".$fromYear2."'
                    WHERE tbl_prop_dtl.status=1 ".$holding_type." and tbl_collection.status=1 and char_length(tbl_prop_dtl.new_holding_no)>0 ".$whereWard." and (tbl_prop_dtl.govt_saf_dtl_id is null or tbl_prop_dtl.govt_saf_dtl_id=0)
                    GROUP BY tbl_prop_dtl.ward_mstr_id
                ),".($propDemand?
                $propDemand:"")."".
                $AREA_DEMAND."

                /*ARREAR_DEMAND AS (
                    SELECT 
                        tbl_prop_dtl.ward_mstr_id,
                        SUM(COALESCE(tbl_prop_demand.amount, 0) - COALESCE(tbl_prop_demand.adjust_amt, 0)) AS arrear_demand
                    FROM tbl_prop_demand
                    INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id
                    WHERE 
                        tbl_prop_dtl.status=1 ".$holding_type." 
                        AND tbl_prop_demand.status=1 
                        AND tbl_prop_demand.paid_status IN(0,1) and char_length(tbl_prop_dtl.new_holding_no)>0 and tbl_prop_demand.due_date is not null ".$whereWard.$WhereFYArrear." 
                        GROUP BY tbl_prop_dtl.ward_mstr_id
                ),*/
                CURRENT_DEMAND AS (
                    SELECT 
                        tbl_prop_dtl.ward_mstr_id,
                        count(tbl_prop_dtl.id) AS current_holding,
                        SUM(COALESCE(tbl_prop_demand.amount, 0) - COALESCE(tbl_prop_demand.adjust_amt, 0)) AS current_demand
                    FROM tbl_prop_dtl
                    RIGHT JOIN (
                        select prop_dtl_id, sum(amount) as amount, sum(adjust_amt) as adjust_amt from tbl_prop_demand where tbl_prop_demand.status=1 
                        AND tbl_prop_demand.paid_status IN (0,1) and tbl_prop_demand.due_date is not null  ".$whereWard1.(isset($data['fy_mstr_id']) && $data['fy_mstr_id']!=53?$WhereFYCurrent:$Where22_23)." 
                        group by prop_dtl_id
                    ) tbl_prop_demand ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id
                    ".(isset($data['fy_mstr_id']) && $data['fy_mstr_id']==53 ? " JOIN prop_demand on prop_demand.prop_dtl_id=tbl_prop_demand.prop_dtl_id":"")."
                    WHERE tbl_prop_dtl.status=1 and char_length(tbl_prop_dtl.new_holding_no)>0  and (tbl_prop_dtl.govt_saf_dtl_id is null or tbl_prop_dtl.govt_saf_dtl_id=0) ".$holding_type."
                    GROUP BY tbl_prop_dtl.ward_mstr_id

                ),
                ARREAR_COLLECTION_ AS (
                    SELECT 
                        tbl_prop_dtl.ward_mstr_id,
                        SUM(COALESCE(tbl_prop_demand.amount, 0) - COALESCE(tbl_prop_demand.adjust_amt, 0)) AS arrear_collection_amount
                    FROM tbl_prop_demand
                    INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id
                    INNER JOIN tbl_collection on tbl_collection.prop_demand_id=tbl_prop_demand.id AND (tbl_collection.created_on::date between '".$fromYear."' and '".$uptoYear."') 
                    WHERE tbl_prop_dtl.status=1 ".$holding_type." and tbl_prop_demand.status=1 and char_length(tbl_prop_dtl.new_holding_no)>0 ".$whereWard.$WhereFYArrear."
                    AND tbl_prop_demand.paid_status=1 and (tbl_prop_dtl.govt_saf_dtl_id is null or tbl_prop_dtl.govt_saf_dtl_id=0)
                    GROUP BY tbl_prop_dtl.ward_mstr_id
                ),
                ARREAR_COLLECTION AS (
                    SELECT 
                        tbl_transaction.ward_mstr_id,
                        count(tbl_transaction.prop_dtl_id) AS arrear_collection_from_no_of_hh,
                        sum(tbl_transaction.payable_amt) AS arrear_collection_amount
                    FROM tbl_transaction
                     INNER JOIN view_ward_mstr ON view_ward_mstr.id=tbl_transaction.ward_mstr_id
                     WHERE tbl_transaction.tran_type='Property' AND tbl_transaction.status in (1, 2) AND
                        (tbl_transaction.upto_fy_mstr_id!='".$data['fy_mstr_id']."' or tbl_transaction.upto_fy_mstr_id IS NULL)
                        AND (tbl_transaction.tran_date between '$fromYear'::date and '$uptoYear'::date) 
                        ".$whereWard2." GROUP BY tbl_transaction.ward_mstr_id
                ),
                CURRENT_COLLECTION_ AS (
                    SELECT 
                        tbl_prop_dtl.ward_mstr_id,
                        count(tbl_prop_dtl.id) AS collection_from_no_of_HH,
                        SUM(tbl_prop_demand.amount - tbl_prop_demand.adjust_amt) AS current_collection_amount
                    FROM tbl_prop_dtl
                    INNER JOIN (
                        select tbl_prop_demand.prop_dtl_id, sum(tbl_prop_demand.amount) as amount, sum(tbl_prop_demand.adjust_amt) as adjust_amt from tbl_prop_demand 
                        INNER JOIN tbl_collection on tbl_collection.prop_demand_id=tbl_prop_demand.id AND (tbl_collection.created_on::date between '".$fromYear."' and '".$uptoYear."') 
                        where tbl_prop_demand.status=1 
                        AND tbl_prop_demand.paid_status=1 AND tbl_prop_demand.status=1 ".$whereWard1.$WhereFYCurrent." 
                        group by tbl_prop_demand.prop_dtl_id
                    ) tbl_prop_demand ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id
                    
                    WHERE tbl_prop_dtl.status=1 and char_length(tbl_prop_dtl.new_holding_no)>0 and (tbl_prop_dtl.govt_saf_dtl_id is null or tbl_prop_dtl.govt_saf_dtl_id=0) ".$holding_type."
                    GROUP BY tbl_prop_dtl.ward_mstr_id
                ), CURRENT_COLLECTION AS (
                    SELECT 
                        tbl_prop_dtl.ward_mstr_id,
                        count(tbl_prop_dtl.id) AS collection_from_no_of_HH,
                        SUM(tbl_prop_demand.holding_tax + tbl_prop_demand.additional_amt + tbl_prop_demand.fine_amt) AS current_collection_amount
                       -- SUM(tbl_prop_demand.amount + tbl_prop_demand.fine_amt) AS current_collection_amount
                    FROM tbl_prop_dtl
                    INNER JOIN (
                        select tbl_prop_demand.prop_dtl_id, sum(tbl_collection.amount) as amount, sum(tbl_collection.holding_tax) as holding_tax, sum(tbl_collection.additional_tax) as additional_amt,
                        sum(tbl_collection.fine_amt) as fine_amt from tbl_prop_demand 
                        INNER JOIN tbl_collection on tbl_collection.prop_demand_id=tbl_prop_demand.id AND (tbl_collection.fy_mstr_id='".$data['fy_mstr_id']."')
                        where tbl_prop_demand.status=1 
                        AND tbl_prop_demand.paid_status=1 ".$whereWard1.$WhereFYCurrent." 
                        group by tbl_prop_demand.prop_dtl_id
                    ) tbl_prop_demand ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id
                    WHERE tbl_prop_dtl.status=1 and char_length(tbl_prop_dtl.new_holding_no)>0 and (tbl_prop_dtl.govt_saf_dtl_id is null or tbl_prop_dtl.govt_saf_dtl_id=0) ".$holding_type."
                    GROUP BY tbl_prop_dtl.ward_mstr_id
                ),
                 ACTUAL_COLLECTION AS (
                    SELECT 
                        tbl_transaction.ward_mstr_id,
                        count(tbl_transaction.prop_dtl_id) AS collection_from_no_of_HH,
                        sum(tbl_transaction.payable_amt) AS actual_collection_amount
                    FROM tbl_transaction
                     INNER JOIN view_ward_mstr ON view_ward_mstr.id=tbl_transaction.ward_mstr_id
                     WHERE (tbl_transaction.upto_fy_mstr_id=".$data['fy_mstr_id']." and tbl_transaction.from_fy_mstr_id=".$data['fy_mstr_id'].") AND tbl_transaction.tran_type='Property' AND tbl_transaction.status in (1, 2) AND
                            (tbl_transaction.tran_date between '$fromYear'::date and '$uptoYear'::date) 
                        ".$whereWard2." 
                       GROUP BY tbl_transaction.ward_mstr_id
                ),
                ACTUALCOLLECTION2 AS (
                    SELECT 
                        tbl_transaction.ward_mstr_id,
                        count(tbl_transaction.prop_dtl_id) AS collection_from_no_of_HH,
                        sum(tbl_transaction.payable_amt) AS actual_collection_amount,sum(tbl_transaction.discount_amt) as rebate_amount,
                        (select sum(tbl_collection.amount+tbl_collection.fine_amt) from tbl_collection 
                        where tbl_collection.created_on::date between '$fromYear'::date and '$uptoYear'::date and tbl_collection.fy_mstr_id=".$data['fy_mstr_id']." and status=1
                        and tbl_collection.transaction_id=tbl_transaction.id group by tbl_collection.transaction_id) as current_demand,
                        (select sum(tbl_collection.amount+tbl_collection.fine_amt) from tbl_collection 
                        where tbl_collection.created_on::date between '$fromYear'::date and '$uptoYear'::date and tbl_collection.fy_mstr_id!=".$data['fy_mstr_id']." and status=1
                        and tbl_collection.transaction_id=tbl_transaction.id group by tbl_collection.transaction_id) as arrear_demand,
                        (select sum(tbl_transaction_fine_rebet_details.amount) from tbl_transaction_fine_rebet_details
                        where tbl_transaction_fine_rebet_details.created_on::date between '$fromYear'::date and '$uptoYear'::date and tbl_transaction_fine_rebet_details.status=1
                        and head_name IN ('Adjusted Amount') and tbl_transaction_fine_rebet_details.transaction_id=tbl_transaction.id group by tbl_transaction_fine_rebet_details.transaction_id)
                        as adjust_amt
                    FROM tbl_transaction
                     INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_transaction.prop_dtl_id
                     INNER JOIN view_ward_mstr ON view_ward_mstr.id=tbl_transaction.ward_mstr_id
                     WHERE (tbl_transaction.from_fy_mstr_id!=".$data['fy_mstr_id']." and tbl_transaction.upto_fy_mstr_id=".$data['fy_mstr_id'].") AND tbl_transaction.tran_type='Property' AND tbl_transaction.status in (1, 2) AND
                    (tbl_transaction.tran_date between '$fromYear'::date and '$uptoYear'::date) 
                        ".$whereWard2." 
                       GROUP BY tbl_transaction.ward_mstr_id,tbl_transaction.id
                ),ACTUALCOLLECTION as (select ward_mstr_id,actual_collection_amount,rebate_amount,
                COALESCE((CASE WHEN (arrear_demand-adjust_amt)<0 OR (arrear_demand-adjust_amt) IS NULL THEN 
                (CASE WHEN (COALESCE(current_demand+(arrear_demand-adjust_amt),0)<=0) THEN
                actual_collection_amount ELSE
                (current_demand+(arrear_demand-adjust_amt)) END)
                ELSE current_demand
                                END),0) as collected_current,(CASE WHEN adjust_amt IS NULL THEN
                                (CASE WHEN actual_collection_amount IS NULL or actual_collection_amount <=0 THEN
                0 ELSE arrear_demand END) ELSE (arrear_demand-adjust_amt)
                                END) as collected_arrear from ACTUALCOLLECTION2),
                                ACTUALCOLLECTIONFINAL as (select ward_mstr_id,sum(COALESCE((
                CASE WHEN ( ROUND((collected_current+collected_arrear)-rebate_amount)=ROUND(actual_collection_amount))
                THEN COALESCE(collected_current,0)-COALESCE(rebate_amount,0)
                ELSE (COALESCE(actual_collection_amount,0)-COALESCE(collected_arrear,0))
                END),0)) as 
                currentdemand_colt,sum(collected_arrear) as current_arrear_colt from ACTUALCOLLECTION group by ward_mstr_id),

                ADVANCE_AMOUNT AS (
                    SELECT 
                        tbl_prop_dtl.ward_mstr_id,
                        count(tbl_prop_dtl.id) AS collection_from_no_of_HH,
                        SUM(amount) AS adv_amount
                    FROM tbl_prop_dtl
                    INNER JOIN (
                        select prop_dtl_id, sum(amount) as amount from tbl_advance_mstr where tbl_advance_mstr.status=1 
                        AND tbl_advance_mstr.created_on::date>='2023-03-01' and module='Property' and user_id=1
                        group by prop_dtl_id
                    ) tbl_advance_mstr ON tbl_prop_dtl.id=tbl_advance_mstr.prop_dtl_id
                    WHERE tbl_prop_dtl.status=1 and char_length(tbl_prop_dtl.new_holding_no)>0 ".$holding_type." ".$whereWard."
                    GROUP BY tbl_prop_dtl.ward_mstr_id
                )
                SELECT 
                    view_ward_mstr.ward_no,
                    COALESCE(CURRENT_DEMAND.current_holding, 0) as current_holding,
                    CASE WHEN COALESCE(ARREAR_DEMAND.arrear_demand, 0)-COALESCE(PREV_COLLECTION.arrear_collection_amount, 0)>0 then COALESCE(ARREAR_DEMAND.arrear_demand, 0)-COALESCE(PREV_COLLECTION.arrear_collection_amount, 0) else 0 end  AS arrear_demand,
                    COALESCE(CURRENT_DEMAND.current_demand, 0) AS current_demand,
                    (CASE WHEN COALESCE(ARREAR_DEMAND.arrear_demand, 0)-COALESCE(PREV_COLLECTION.arrear_collection_amount, 0)>0 then COALESCE(ARREAR_DEMAND.arrear_demand, 0)-COALESCE(PREV_COLLECTION.arrear_collection_amount, 0) else 0 end)+COALESCE(CURRENT_DEMAND.current_demand, 0) AS total_demand,
                    COALESCE(CURRENT_COLLECTION.collection_from_no_of_HH, 0) as collection_from_no_of_HH,
                    (select count(*) from tbl_transaction txn where txn.tran_type='Property' AND txn.status in (1, 2) AND
                    (txn.tran_date between '$fromYear'::date and '$uptoYear'::date) and txn.ward_mstr_id=view_ward_mstr.id) as collection_from_no_of_HH1,
                    (select count(*) from ACTUAL_COLLECTION where ward_mstr_id=view_ward_mstr.id) as collection_from_no_of_HH2,
                    COALESCE(ARREAR_COLLECTION.arrear_collection_amount, 0) AS arrear_collection_amount,
                    COALESCE(ACTUALCOLLECTIONFINAL.current_arrear_colt, 0) AS arrear_collection_amount2,
                    COALESCE(CURRENT_COLLECTION.current_collection_amount, 0) AS current_collection_amount,
                    COALESCE(ACTUAL_COLLECTION.actual_collection_amount, 0) AS actual_collection_amount,
                    COALESCE(ACTUALCOLLECTIONFINAL.currentdemand_colt, 0) AS actual_collection_amount2,
                    COALESCE(ARREAR_COLLECTION.arrear_collection_amount, 0)+COALESCE(ACTUAL_COLLECTION.actual_collection_amount, 0) AS total_collection_amount,
                    COALESCE(ACTUALCOLLECTIONFINAL.current_arrear_colt, 0)+COALESCE(ACTUALCOLLECTIONFINAL.currentdemand_colt, 0) AS total_collection_amount2,
                    COALESCE(CURRENT_DEMAND.current_holding, 0)-COALESCE(CURRENT_COLLECTION.collection_from_no_of_HH, 0) AS balance_hh,
                    (CASE WHEN COALESCE(ARREAR_DEMAND.arrear_demand, 0)-COALESCE(PREV_COLLECTION.arrear_collection_amount, 0)>0 then COALESCE(ARREAR_DEMAND.arrear_demand, 0)-COALESCE(PREV_COLLECTION.arrear_collection_amount, 0) else 0 end)-COALESCE((ARREAR_COLLECTION.arrear_collection_amount+ACTUALCOLLECTIONFINAL.current_arrear_colt), 0) AS arrear_balance_amount,
                    COALESCE(CURRENT_DEMAND.current_demand, 0)-COALESCE((COALESCE(ACTUAL_COLLECTION.actual_collection_amount,0)+COALESCE(ACTUALCOLLECTIONFINAL.currentdemand_colt,0)), 0) AS current_balance_amount,
                    COALESCE(((CASE WHEN COALESCE(ARREAR_DEMAND.arrear_demand, 0)-COALESCE(PREV_COLLECTION.arrear_collection_amount, 0)>0 then COALESCE(ARREAR_DEMAND.arrear_demand, 0)-COALESCE(PREV_COLLECTION.arrear_collection_amount, 0) else 0 end)-ARREAR_COLLECTION.arrear_collection_amount)+(CURRENT_DEMAND.current_demand-CURRENT_COLLECTION.current_collection_amount), 0) AS total_balance_amount,
                    COALESCE(((CASE WHEN COALESCE(ARREAR_DEMAND.arrear_demand, 0)-COALESCE(PREV_COLLECTION.arrear_collection_amount, 0)>0 then COALESCE(ARREAR_DEMAND.arrear_demand, 0)-COALESCE(PREV_COLLECTION.arrear_collection_amount, 0) else 0 end)-(ARREAR_COLLECTION.arrear_collection_amount+ACTUALCOLLECTIONFINAL.current_arrear_colt))+(CURRENT_DEMAND.current_demand-(ACTUAL_COLLECTION.actual_collection_amount+ACTUALCOLLECTIONFINAL.currentdemand_colt)), 0) AS total_balance_amount3,
                    (CASE WHEN COALESCE(ARREAR_DEMAND.arrear_demand, 0)-COALESCE(PREV_COLLECTION.arrear_collection_amount, 0)>0 then COALESCE(ARREAR_DEMAND.arrear_demand, 0)-COALESCE(PREV_COLLECTION.arrear_collection_amount, 0) else 0 end)-COALESCE((ARREAR_COLLECTION.arrear_collection_amount+ACTUALCOLLECTIONFINAL.current_arrear_colt), 0)+COALESCE(CURRENT_DEMAND.current_demand, 0)-COALESCE((COALESCE(ACTUAL_COLLECTION.actual_collection_amount,0)+COALESCE(ACTUALCOLLECTIONFINAL.currentdemand_colt,0)), 0) AS total_balance_amount2,
                    (CASE 
                        WHEN CURRENT_COLLECTION.collection_from_no_of_HH IS NULL OR CURRENT_COLLECTION.collection_from_no_of_HH=0 THEN 0
                        ELSE
                        ROUND((COALESCE(CURRENT_COLLECTION.collection_from_no_of_HH, 0)*100)/(CASE WHEN COALESCE(CURRENT_DEMAND.current_holding, 0)!=0 then COALESCE(CURRENT_DEMAND.current_holding, 0) else 1 end), 2)
                    END) AS hh_percentage,
                    (CASE
                        WHEN ARREAR_COLLECTION.arrear_collection_amount IS NULL OR ARREAR_COLLECTION.arrear_collection_amount=0 THEN 0
                        WHEN CURRENT_COLLECTION.current_collection_amount IS NULL OR CURRENT_COLLECTION.current_collection_amount=0 THEN 0
                        WHEN COALESCE(ARREAR_DEMAND.arrear_demand+CURRENT_DEMAND.current_demand, 0)=0 THEN 0
                        ELSE
                            ROUND((COALESCE(ARREAR_COLLECTION.arrear_collection_amount+CURRENT_COLLECTION.current_collection_amount, 0)*100)/(CASE WHEN COALESCE(ARREAR_DEMAND.arrear_demand+CURRENT_DEMAND.current_demand, 0)!=0 then COALESCE(ARREAR_DEMAND.arrear_demand+CURRENT_DEMAND.current_demand, 0) else 1 end), 2) 
                    END) AS amount_percentage,
					".
                    ($data['fy_mstr_id'] == 54?"COALESCE(ADVANCE_AMOUNT.adv_amount, 0)":"0")
					." as advance_amount
                FROM view_ward_mstr
                LEFT JOIN ARREAR_DEMAND ON ARREAR_DEMAND.ward_mstr_id=view_ward_mstr.id
                LEFT JOIN PREV_COLLECTION ON PREV_COLLECTION.ward_mstr_id=view_ward_mstr.id
                LEFT JOIN CURRENT_DEMAND ON CURRENT_DEMAND.ward_mstr_id=view_ward_mstr.id
                LEFT JOIN ARREAR_COLLECTION ON ARREAR_COLLECTION.ward_mstr_id=view_ward_mstr.id
                LEFT JOIN CURRENT_COLLECTION ON CURRENT_COLLECTION.ward_mstr_id=view_ward_mstr.id
                LEFT JOIN ACTUAL_COLLECTION ON ACTUAL_COLLECTION.ward_mstr_id=view_ward_mstr.id
                LEFT JOIN ACTUALCOLLECTIONFINAL ON ACTUALCOLLECTIONFINAL.ward_mstr_id=view_ward_mstr.id
                LEFT JOIN ADVANCE_AMOUNT ON ADVANCE_AMOUNT.ward_mstr_id=view_ward_mstr.id
                $whereWard0
                ORDER BY SUBSTRING(view_ward_mstr.ward_no FROM '([0-9]+)')::BIGINT ASC, view_ward_mstr.ward_no";
                //print_var($sql);
                $totalcollection="select sum(tbl_transaction.payable_amt) as actual_collection_amount,count(tbl_transaction.prop_dtl_id) as collection_from_no_of_HH
                FROM tbl_transaction
                        INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_transaction.prop_dtl_id                      
                        WHERE tbl_transaction.tran_type='Property' AND tbl_transaction.status in (1, 2) AND
                (tbl_transaction.tran_date between '$fromYear'::date and '$uptoYear'::date)";
                $builder = $this->db->query($sql);
                $builder2 = $this->db->query($totalcollection);
                $actual_collection_ = $builder2->getResultArray()[0]['actual_collection_amount'];
                $remaining=$actual_collection_;
                if ($report_list = $builder->getResultArray()) {
                    // if($data['fy_mstr_id']==55){
                    //     $report_list['52']['arrear_demand']=85645;
                    //     $report_list['52']['arrear_balance_amount'] = $report_list['52']['arrear_demand']-$report_list['52']['arrear_collection_amount'];
                    // }
                    if($data['fy_mstr_id']==55){
                        $report_list['0']['arrear_demand']=1583705.94;
                        if(isset($report_list['0']['arrear_collection_amount'])){
                            $report_list['0']['arrear_balance_amount'] = $report_list['0']['arrear_demand']-($report_list['0']['arrear_collection_amount'])??0;
                            $report_list['0']['total_demand'] =  $report_list['0']['arrear_demand']+($report_list['0']['current_demand'])??0;
                        }
                        if(isset($report_list['28']['arrear_collection_amount'])) {
                            $report_list['28']['arrear_demand'] = 6021015.85;
                            $report_list['28']['arrear_balance_amount'] = $report_list['28']['arrear_demand'] - ($report_list['28']['arrear_collection_amount']) ?? 0;
                            $report_list['28']['total_demand'] =  $report_list['28']['arrear_demand']+($report_list['28']['current_demand'])??0;
                        }
                        if(isset($report_list['32']['arrear_collection_amount'])) {
                            $report_list['32']['arrear_demand'] = 1036012.58;
                            $report_list['32']['arrear_balance_amount'] = $report_list['32']['arrear_demand'] - $report_list['32']['arrear_collection_amount'] ?? 0;
                            $report_list['32']['total_demand'] =  $report_list['32']['arrear_demand']+($report_list['32']['current_demand'])??0;
                        }
                        if(isset($report_list['22']['arrear_collection_amount'])) {
                            $report_list['22']['arrear_demand'] = 1119567.80;
                            $report_list['22']['arrear_balance_amount'] = $report_list['22']['arrear_demand'] - $report_list['22']['arrear_collection_amount'] ?? 0;
                            $report_list['22']['total_demand'] =  $report_list['22']['arrear_demand']+($report_list['22']['current_demand'])??0;
                        }
                        if(isset($report_list['40']['arrear_collection_amount'])) {
                            $report_list['40']['arrear_demand'] = 2794882.81;
                            $report_list['40']['arrear_balance_amount'] = $report_list['40']['arrear_demand'] - $report_list['40']['arrear_collection_amount'] ?? 0;
                            $report_list['40']['total_demand'] =  $report_list['40']['arrear_demand']+($report_list['40']['current_demand'])??0;
                        }
                        if(isset($report_list['38']['arrear_collection_amount'])) {
                            $report_list['38']['arrear_demand'] = 2735329.96;
                            $report_list['38']['arrear_balance_amount'] = $report_list['38']['arrear_demand'] - $report_list['38']['arrear_collection_amount'] ?? 0;
                            $report_list['38']['total_demand'] =  $report_list['38']['arrear_demand']+($report_list['38']['current_demand'])??0;
                        }
                        if(isset($report_list['42']['arrear_collection_amount'])) {
                            $report_list['42']['arrear_demand'] = 3111960.99;
                            $report_list['42']['arrear_balance_amount'] = $report_list['42']['arrear_demand'] - $report_list['42']['arrear_collection_amount'] ?? 0;
                            $report_list['42']['total_demand'] =  $report_list['42']['arrear_demand']+($report_list['42']['current_demand'])??0;
                        }
                        if(isset($report_list['44']['arrear_collection_amount'])) {
                            $report_list['44']['arrear_demand'] = 3174090.10;
                            $report_list['44']['arrear_balance_amount'] = $report_list['44']['arrear_demand'] - $report_list['44']['arrear_collection_amount'] ?? 0;
                            $report_list['44']['total_demand'] =  $report_list['44']['arrear_demand']+($report_list['44']['current_demand'])??0;
                        }
                        if(isset($report_list['154']['arrear_collection_amount'])) {
                            $report_list['154']['arrear_demand'] = 506368.38;
                            $report_list['154']['arrear_balance_amount'] = $report_list['154']['arrear_demand'] - $report_list['154']['arrear_collection_amount'] ?? 0;
                            $report_list['154']['total_demand'] =  $report_list['154']['arrear_demand']+($report_list['154']['current_demand'])??0;
                        }
                        if(isset($report_list['16']['arrear_collection_amount'])) {
                            $report_list['16']['arrear_demand'] = 1187196.24;
                            $report_list['16']['arrear_balance_amount'] = $report_list['16']['arrear_demand'] - $report_list['16']['arrear_collection_amount'] ?? 0;
                            $report_list['16']['total_demand'] =  $report_list['16']['arrear_demand']+($report_list['16']['current_demand'])??0;
                        }
                        if(isset($report_list['52']['arrear_collection_amount'])) {
                            $report_list['52']['arrear_demand'] = 1602256.36;
                            $report_list['52']['arrear_balance_amount'] = $report_list['52']['arrear_demand'] - $report_list['52']['arrear_collection_amount'] ?? 0;
                            $report_list['52']['total_demand'] =  $report_list['52']['arrear_demand']+($report_list['52']['current_demand'])??0;
                        }
                        if(isset($report_list['54']['arrear_collection_amount'])) {
                            $report_list['54']['arrear_demand'] = 3094738.23;
                            $report_list['54']['arrear_balance_amount'] = $report_list['54']['arrear_demand'] - $report_list['54']['arrear_collection_amount'] ?? 0;
                            $report_list['54']['total_demand'] =  $report_list['54']['arrear_demand']+($report_list['54']['current_demand'])??0;
                        }
                        if(isset($report_list['79']['arrear_collection_amount'])) {
                            $report_list['79']['arrear_demand'] = 3156569.32;
                            $report_list['79']['arrear_balance_amount'] = $report_list['79']['arrear_demand'] - $report_list['79']['arrear_collection_amount'] ?? 0;
                            $report_list['79']['total_demand'] =  $report_list['79']['arrear_demand']+($report_list['79']['current_demand'])??0;
                        }
                        if(isset($report_list['114']['arrear_collection_amount'])) {
                            $report_list['114']['arrear_demand'] = 1578811.18;
                            $report_list['114']['arrear_balance_amount'] = $report_list['114']['arrear_demand'] - $report_list['114']['arrear_collection_amount'] ?? 0;
                            $report_list['114']['total_demand'] =  $report_list['114']['arrear_demand']+($report_list['114']['current_demand'])??0;
                        }
                        if(isset($report_list['50']['arrear_collection_amount'])) {
                            $report_list['50']['arrear_demand'] = 3021048;
                            $report_list['50']['arrear_balance_amount'] = $report_list['50']['arrear_demand'] - $report_list['50']['arrear_collection_amount'] ?? 0;
                            $report_list['50']['total_demand'] =  $report_list['50']['arrear_demand']+($report_list['50']['current_demand'])??0;
                        }
                        if(isset($report_list['105']['arrear_collection_amount'])) {
                            $report_list['105']['arrear_demand'] = 2414902.44;
                            $report_list['105']['arrear_balance_amount'] = $report_list['105']['arrear_demand'] - $report_list['105']['arrear_collection_amount'] ?? 0;
                            $report_list['105']['total_demand'] =  $report_list['105']['arrear_demand']+($report_list['105']['current_demand'])??0;
                        }
                        if(isset($report_list['6']['arrear_collection_amount'])) {
                            $report_list['6']['arrear_demand'] = 6048756.17;
                            $report_list['6']['arrear_balance_amount'] = $report_list['6']['arrear_demand'] - $report_list['6']['arrear_collection_amount'] ?? 0;
                            $report_list['6']['total_demand'] =  $report_list['6']['arrear_demand']+($report_list['6']['current_demand'])??0;
                        }
                        if(isset($report_list['67']['arrear_collection_amount'])) {
                            $report_list['67']['arrear_demand'] = 4329231.17;
                            $report_list['67']['arrear_balance_amount'] = $report_list['67']['arrear_demand'] - $report_list['67']['arrear_collection_amount'] ?? 0;
                            $report_list['67']['total_demand'] =  $report_list['67']['arrear_demand']+($report_list['67']['current_demand'])??0;
                        }
                        if(isset($report_list['8']['arrear_collection_amount'])) {
                            $report_list['8']['arrear_demand'] = 5020476.73;
                            $report_list['8']['arrear_balance_amount'] = $report_list['8']['arrear_demand'] - $report_list['8']['arrear_collection_amount'] ?? 0;
                            $report_list['8']['total_demand'] =  $report_list['8']['arrear_demand']+($report_list['8']['current_demand'])??0;
                        }
                    }
                    $data['report_list'] = $report_list;
                }
                //dd($report_list);
                //echo $this->db->getLastQuery();
            }catch(Exception $e){
                print_r($e);
            }
        }
        $data['wardList'] = $wardList;
        $data['fyList'] = $fyList;
        $data['prop_collection'] = $remaining??0;
        if(isset($_REQUEST["api"])){
            return $data;
        }

        return view('property/reports/ward_wise_DCB_report', $data);
    }catch(Exception $e){
        print_r($e);
    }
    }

    public function wardWiseDCB_test()
    {
        $ulb_mstr_id = 1;
        $wardList = $this->model_ward_mstr->getWardList(['ulb_mstr_id'=>$ulb_mstr_id]);
        $currentFY = getFY();
        $currentFyID = $this->model_fy_mstr->getFyByFy(['fy'=>$currentFY])['id'];
        $fyList = $this->model_fy_mstr->getFYListDescBefore10Year($currentFyID);
        if($this->request->getMethod()=='post')
        {
            try {
                $data = arrFilterSanitizeString($this->request->getVar());
                //print_r($data);
                $WhereFYCurrent = "";
                $WhereFYArrear = "";
                $Where22_23 = "";
                $propDemand = "";
                if($data['fy_mstr_id']!='') {
                    if($data['fy_mstr_id']==53) #  for current demand
                    {

                        $Where22_23 =" AND tbl_prop_demand.fy_mstr_id=54";
                        $propDemand = "prop_demand as(
                                            select SUM(COALESCE(tbl_prop_demand.amount, 0) - COALESCE(tbl_prop_demand.adjust_amt, 0)) as arrear_demand,tbl_prop_demand.prop_dtl_id from tbl_prop_demand
                                            INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id
                                            where tbl_prop_demand.status=1 and tbl_prop_demand.fy_mstr_id=53 and tbl_prop_dtl.status=1 and tbl_prop_demand.status=1 
                                            and char_length(tbl_prop_dtl.new_holding_no)>0  and (tbl_prop_dtl.govt_saf_dtl_id is null or tbl_prop_dtl.govt_saf_dtl_id=0)
                                            group by tbl_prop_demand.prop_dtl_id
                                        ),";
                    }
                    elseif($data['fy_mstr_id']==54) #for  earrir Demand
                    {
                        $Where22_23 ="";
                        $propDemand = "prop_demand as(
                            select SUM(COALESCE(tbl_prop_demand.amount, 0) - COALESCE(tbl_prop_demand.adjust_amt, 0)) as arrear_demand,tbl_prop_demand.prop_dtl_id 
                            ,tbl_prop_dtl.ward_mstr_id
                            from tbl_prop_demand
                            INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id
                            where tbl_prop_demand.status=1 and tbl_prop_demand.fy_mstr_id=54 and tbl_prop_dtl.status=1 and tbl_prop_demand.status=1 
                            and char_length(tbl_prop_dtl.new_holding_no)>0  and (tbl_prop_dtl.govt_saf_dtl_id is null or tbl_prop_dtl.govt_saf_dtl_id=0)
                            group by tbl_prop_demand.prop_dtl_id,tbl_prop_dtl.ward_mstr_id
                        ),";
                    }
                    elseif($data['fy_mstr_id']==55) #for  earrir Deman
                    {
                        //$Where22_23 =" AND tbl_prop_demand.fy_mstr_id=55";
                        $Where22_23 ="";
                        $propDemand = "prop_demand as(
                            select SUM(COALESCE(tbl_prop_demand.amount, 0) - COALESCE(tbl_prop_demand.adjust_amt, 0)) as arrear_demand,tbl_prop_demand.prop_dtl_id 
                            ,tbl_prop_dtl.ward_mstr_id
                            from tbl_prop_demand
                            INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id
                            where tbl_prop_demand.status=1 and tbl_prop_demand.fy_mstr_id=55 and tbl_prop_dtl.status=1 and tbl_prop_demand.status=1 
                            and char_length(tbl_prop_dtl.new_holding_no)>0  and (tbl_prop_dtl.govt_saf_dtl_id is null or tbl_prop_dtl.govt_saf_dtl_id=0)
                            group by tbl_prop_demand.prop_dtl_id,tbl_prop_dtl.ward_mstr_id
                        ),";
                    }

                    $WhereFYCurrent = " AND tbl_prop_demand.fy_mstr_id=".$data['fy_mstr_id'];
                    ///$WhereFYCurrent2 = " AND tbl_transaction.fy_mstr_id=".$data['fy_mstr_id'];
                    $WhereFYArrear = " AND tbl_prop_demand.fy_mstr_id<".$data['fy_mstr_id'];
                    $fyear = explode('-',$this->model_fy_mstr->getFyearByFyid($data['fy_mstr_id'])['fy']);
                    
                    $fromYear = $fyear[0].'-04-01';
                    $uptoYear = $fyear[1].'-03-31';
                }

                $whereWard = "";
                $whereWard0 = "";
                $whereWard1 = "";
                $whereWard2 = "";
                if($data['ward_mstr_id']!='') {
                    $whereWard = " AND tbl_prop_dtl.ward_mstr_id=".$data['ward_mstr_id'];
                    $whereWard1 = " AND tbl_prop_demand.ward_mstr_id=".$data['ward_mstr_id'];
                    $whereWard0 = "where view_ward_mstr.id=".$data['ward_mstr_id'];
                    $whereWard2 = " AND tbl_transaction.ward_mstr_id=".$data['ward_mstr_id'];
                    // print_r($data['ward_mstr_id']);
                    // return;
                }
                $holding_type = "";
                if (isset($data['residencial'])
                    || isset($data['non_residential'])
                    || isset($data['vacant_land'])
                    || isset($data['government_building'])) {

                    if (isset($data['residencial'])) {
                        $holding_type .= "'PURE_RESIDENTIAL'";
                    }
                    if (isset($data['non_residential'])) {
                        if ($holding_type!='') {
                            $holding_type .= ", ";
                        }
                        $holding_type .= "'PURE_COMMERCIAL', 'MIX_COMMERCIAL'";
                    }
                    if (isset($data['government_building'])) {
                        if ($holding_type!='') {
                            $holding_type .= ", ";
                        }
                        $holding_type .= "'PURE_GOVERNMENT', 'MIX_GOVERNMENT'";
                    }

                    if (isset($data['vacant_land'])) {
                        if ($holding_type!='') {
                            $holding_type .= ", ";
                        }
                        $holding_type .= "'VACANT_LAND'";
                    }
                    if ($holding_type!='') {
                        $holding_type = " AND tbl_prop_dtl.holding_type IN (".$holding_type.")";
                    }
                }
                //$dd = "PURE_RESIDENTIAL, PURE_COMMERCIAL, PURE_GOVERNMENT, MIX_GOVERNMENT, MIX_COMMERCIAL";                
                // By Hayat
                $AREA_DEMAND = "ARREAR_DEMAND AS (
                    SELECT 
                        tbl_prop_dtl.ward_mstr_id,
                        SUM(COALESCE(tbl_prop_demand.amount, 0) - COALESCE(tbl_prop_demand.adjust_amt, 0)) AS arrear_demand
                    FROM tbl_prop_demand
                    INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id
                    WHERE 
                        tbl_prop_dtl.status=1 ".$holding_type." 
                        AND tbl_prop_demand.status=1 
                        AND tbl_prop_demand.paid_status IN(0,1) and char_length(tbl_prop_dtl.new_holding_no)>0 and (tbl_prop_dtl.govt_saf_dtl_id is null or tbl_prop_dtl.govt_saf_dtl_id=0) and tbl_prop_demand.due_date is not null ".$whereWard.$WhereFYArrear." 
                        GROUP BY tbl_prop_dtl.ward_mstr_id
                ),";
                if($data['fy_mstr_id']==54 || $data['fy_mstr_id']==55)
                {
                    $fact=4.98;
                    if($data['fy_mstr_id']==55){
                        $fact=10.9;
                    }
                    $WhereFYArrear2 = " AND tbl_prop_demand.fy_mstr_id<".($data['fy_mstr_id']-1);
                  
                    $AREA_DEMAND = "ARREAR_DEMAND AS (
                                        SELECT curent.ward_mstr_id,
                                            SUM(curent.arrear_demand-(curent.arrear_demand*$fact/100)) arrear_demand 
                                        FROM (
                                            SELECT 
                                                tbl_prop_dtl.ward_mstr_id,
                                                SUM(COALESCE(tbl_prop_demand.amount, 0) - COALESCE(tbl_prop_demand.adjust_amt, 0)) AS arrear_demand
                                            FROM tbl_prop_demand
                                            INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id
                                            WHERE 
                                                tbl_prop_dtl.status=1 ".$holding_type." 
                                                AND tbl_prop_demand.status=1 
                                                AND tbl_prop_demand.paid_status IN(0,1) and char_length(tbl_prop_dtl.new_holding_no)>0 and tbl_prop_demand.due_date is not null ".$whereWard.$WhereFYArrear2." and (tbl_prop_dtl.govt_saf_dtl_id is null or tbl_prop_dtl.govt_saf_dtl_id=0)
                                                GROUP BY tbl_prop_dtl.ward_mstr_id
                                            )curent
                                        JOIN (
                                            select ward_mstr_id,
                                                SUM(case when arrear_demand>0 then arrear_demand else 0 end) as arrear_demand
                                            from prop_demand
                                            group by ward_mstr_id
                                        )prop_demand2 on prop_demand2.ward_mstr_id=curent.ward_mstr_id
                                        group by curent.ward_mstr_id 
                                    ),";
                }
                $fromYear2 = $fromYear;
                if($data['fy_mstr_id']==54 || $data['fy_mstr_id']==55){$fromYear2 = ($fyear[0]-1).'-04-01';}
                $sql = "WITH 
                PREV_COLLECTION AS (
                    SELECT 
                        tbl_prop_dtl.ward_mstr_id,
                        SUM(COALESCE(tbl_collection.amount, 0)) AS arrear_collection_amount
                    FROM tbl_prop_dtl
                    INNER JOIN tbl_collection on tbl_collection.prop_dtl_id=tbl_prop_dtl.id AND tbl_collection.created_on::date < '".$fromYear2."'
                    WHERE tbl_prop_dtl.status=1 ".$holding_type." and tbl_collection.status=1 and char_length(tbl_prop_dtl.new_holding_no)>0 ".$whereWard." and (tbl_prop_dtl.govt_saf_dtl_id is null or tbl_prop_dtl.govt_saf_dtl_id=0)
                    GROUP BY tbl_prop_dtl.ward_mstr_id
                ),".($propDemand?
                $propDemand:"")."".
                $AREA_DEMAND."

                /*ARREAR_DEMAND AS (
                    SELECT 
                        tbl_prop_dtl.ward_mstr_id,
                        SUM(COALESCE(tbl_prop_demand.amount, 0) - COALESCE(tbl_prop_demand.adjust_amt, 0)) AS arrear_demand
                    FROM tbl_prop_demand
                    INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id
                    WHERE 
                        tbl_prop_dtl.status=1 ".$holding_type." 
                        AND tbl_prop_demand.status=1 
                        AND tbl_prop_demand.paid_status IN(0,1) and char_length(tbl_prop_dtl.new_holding_no)>0 and tbl_prop_demand.due_date is not null ".$whereWard.$WhereFYArrear." 
                        GROUP BY tbl_prop_dtl.ward_mstr_id
                ),*/
                CURRENT_DEMAND AS (
                    SELECT 
                        tbl_prop_dtl.ward_mstr_id,
                        count(tbl_prop_dtl.id) AS current_holding,
                        SUM(COALESCE(tbl_prop_demand.amount, 0) - COALESCE(tbl_prop_demand.adjust_amt, 0)) AS current_demand
                    FROM tbl_prop_dtl
                    RIGHT JOIN (
                        select prop_dtl_id, sum(amount) as amount, sum(adjust_amt) as adjust_amt from tbl_prop_demand where tbl_prop_demand.status=1 
                        AND tbl_prop_demand.paid_status IN (0,1) and tbl_prop_demand.due_date is not null  ".$whereWard1.(isset($data['fy_mstr_id']) && $data['fy_mstr_id']!=53?$WhereFYCurrent:$Where22_23)." 
                        group by prop_dtl_id
                    ) tbl_prop_demand ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id
                    ".(isset($data['fy_mstr_id']) && $data['fy_mstr_id']==53 ? " JOIN prop_demand on prop_demand.prop_dtl_id=tbl_prop_demand.prop_dtl_id":"")."
                    WHERE tbl_prop_dtl.status=1 and char_length(tbl_prop_dtl.new_holding_no)>0  and (tbl_prop_dtl.govt_saf_dtl_id is null or tbl_prop_dtl.govt_saf_dtl_id=0) ".$holding_type."
                    GROUP BY tbl_prop_dtl.ward_mstr_id

                ),
                ARREAR_COLLECTION_ AS (
                    SELECT 
                        tbl_prop_dtl.ward_mstr_id,
                        SUM(COALESCE(tbl_prop_demand.amount, 0) - COALESCE(tbl_prop_demand.adjust_amt, 0)) AS arrear_collection_amount
                    FROM tbl_prop_demand
                    INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id
                    INNER JOIN tbl_collection on tbl_collection.prop_demand_id=tbl_prop_demand.id AND (tbl_collection.created_on::date between '".$fromYear."' and '".$uptoYear."') 
                    WHERE tbl_prop_dtl.status=1 ".$holding_type." and tbl_prop_demand.status=1 and char_length(tbl_prop_dtl.new_holding_no)>0 ".$whereWard.$WhereFYArrear."
                    AND tbl_prop_demand.paid_status=1 and (tbl_prop_dtl.govt_saf_dtl_id is null or tbl_prop_dtl.govt_saf_dtl_id=0)
                    GROUP BY tbl_prop_dtl.ward_mstr_id
                ),
                ARREAR_COLLECTION AS (
                    SELECT 
                        tbl_transaction.ward_mstr_id,
                        count(tbl_transaction.prop_dtl_id) AS arrear_collection_from_no_of_hh,
                        sum(tbl_transaction.payable_amt) AS arrear_collection_amount
                    FROM tbl_transaction
                     INNER JOIN view_ward_mstr ON view_ward_mstr.id=tbl_transaction.ward_mstr_id
                     WHERE tbl_transaction.tran_type='Property' AND tbl_transaction.status in (1, 2) AND
                     (tbl_transaction.upto_fy_mstr_id!='".$data['fy_mstr_id']."' or tbl_transaction.upto_fy_mstr_id IS NULL)
                        AND (tbl_transaction.tran_date between '$fromYear'::date and '$uptoYear'::date) 
                        ".$whereWard2." GROUP BY tbl_transaction.ward_mstr_id
                ),
                CURRENT_COLLECTION_ AS (
                    SELECT 
                        tbl_prop_dtl.ward_mstr_id,
                        count(tbl_prop_dtl.id) AS collection_from_no_of_HH,
                        SUM(tbl_prop_demand.amount - tbl_prop_demand.adjust_amt) AS current_collection_amount
                    FROM tbl_prop_dtl
                    INNER JOIN (
                        select tbl_prop_demand.prop_dtl_id, sum(tbl_prop_demand.amount) as amount, sum(tbl_prop_demand.adjust_amt) as adjust_amt from tbl_prop_demand 
                        INNER JOIN tbl_collection on tbl_collection.prop_demand_id=tbl_prop_demand.id AND (tbl_collection.created_on::date between '".$fromYear."' and '".$uptoYear."') 
                        where tbl_prop_demand.status=1 
                        AND tbl_prop_demand.paid_status=1 AND tbl_prop_demand.status=1 ".$whereWard1.$WhereFYCurrent." 
                        group by tbl_prop_demand.prop_dtl_id
                    ) tbl_prop_demand ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id
                    
                    WHERE tbl_prop_dtl.status=1 and char_length(tbl_prop_dtl.new_holding_no)>0 and (tbl_prop_dtl.govt_saf_dtl_id is null or tbl_prop_dtl.govt_saf_dtl_id=0) ".$holding_type."
                    GROUP BY tbl_prop_dtl.ward_mstr_id
                ), CURRENT_COLLECTION AS (
                    SELECT 
                        tbl_prop_dtl.ward_mstr_id,
                        count(tbl_prop_dtl.id) AS collection_from_no_of_HH,
                        SUM(tbl_prop_demand.holding_tax + tbl_prop_demand.additional_amt + tbl_prop_demand.fine_amt) AS current_collection_amount
                       -- SUM(tbl_prop_demand.amount + tbl_prop_demand.fine_amt) AS current_collection_amount
                    FROM tbl_prop_dtl
                    INNER JOIN (
                        select tbl_prop_demand.prop_dtl_id, sum(tbl_collection.amount) as amount, sum(tbl_collection.holding_tax) as holding_tax, sum(tbl_collection.additional_tax) as additional_amt,
                        sum(tbl_collection.fine_amt) as fine_amt from tbl_prop_demand 
                        INNER JOIN tbl_collection on tbl_collection.prop_demand_id=tbl_prop_demand.id AND (tbl_collection.fy_mstr_id='".$data['fy_mstr_id']."')
                        where tbl_prop_demand.status=1 
                        AND tbl_prop_demand.paid_status=1 ".$whereWard1.$WhereFYCurrent." 
                        group by tbl_prop_demand.prop_dtl_id
                    ) tbl_prop_demand ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id
                    WHERE tbl_prop_dtl.status=1 and char_length(tbl_prop_dtl.new_holding_no)>0 and (tbl_prop_dtl.govt_saf_dtl_id is null or tbl_prop_dtl.govt_saf_dtl_id=0) ".$holding_type."
                    GROUP BY tbl_prop_dtl.ward_mstr_id
                ),
                 ACTUAL_COLLECTION AS (
                    SELECT 
                        tbl_transaction.ward_mstr_id,
                        count(tbl_transaction.prop_dtl_id) AS collection_from_no_of_HH,
                        sum(tbl_transaction.payable_amt) AS actual_collection_amount
                    FROM tbl_transaction
                     INNER JOIN view_ward_mstr ON view_ward_mstr.id=tbl_transaction.ward_mstr_id
                     WHERE (tbl_transaction.upto_fy_mstr_id=".$data['fy_mstr_id']." and tbl_transaction.from_fy_mstr_id=".$data['fy_mstr_id'].") AND tbl_transaction.tran_type='Property' AND tbl_transaction.status in (1, 2) AND
                        (tbl_transaction.tran_date between '$fromYear'::date and '$uptoYear'::date) 
                        ".$whereWard2." 
                       GROUP BY tbl_transaction.ward_mstr_id
                ),
                ACTUALCOLLECTION2 AS (
                    SELECT 
                        tbl_transaction.ward_mstr_id,
                        count(tbl_transaction.prop_dtl_id) AS collection_from_no_of_HH,
                        sum(tbl_transaction.payable_amt) AS actual_collection_amount,sum(tbl_transaction.discount_amt) as rebate_amount,
                        (select sum(tbl_collection.amount+tbl_collection.fine_amt) from tbl_collection 
                            where tbl_collection.created_on::date between '$fromYear'::date and '$uptoYear'::date and tbl_collection.fy_mstr_id=".$data['fy_mstr_id']." and status=1
                            and tbl_collection.transaction_id=tbl_transaction.id group by tbl_collection.transaction_id) as current_demand,
                            (select sum(tbl_collection.amount+tbl_collection.fine_amt) from tbl_collection 
                            where tbl_collection.created_on::date between '$fromYear'::date and '$uptoYear'::date and tbl_collection.fy_mstr_id!=".$data['fy_mstr_id']." and status=1
                            and tbl_collection.transaction_id=tbl_transaction.id group by tbl_collection.transaction_id) as arrear_demand,
                            (select sum(tbl_transaction_fine_rebet_details.amount) from tbl_transaction_fine_rebet_details
                            where tbl_transaction_fine_rebet_details.created_on::date between '$fromYear'::date and '$uptoYear'::date and tbl_transaction_fine_rebet_details.status=1
                            and head_name IN ('Adjusted Amount') and tbl_transaction_fine_rebet_details.transaction_id=tbl_transaction.id group by tbl_transaction_fine_rebet_details.transaction_id)
                            as adjust_amt
                    FROM tbl_transaction
                     INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_transaction.prop_dtl_id
                     INNER JOIN view_ward_mstr ON view_ward_mstr.id=tbl_transaction.ward_mstr_id
                     WHERE (tbl_transaction.from_fy_mstr_id!=".$data['fy_mstr_id']." and tbl_transaction.upto_fy_mstr_id=".$data['fy_mstr_id'].") AND tbl_transaction.tran_type='Property' AND tbl_transaction.status in (1, 2) AND
                        (tbl_transaction.tran_date between '$fromYear'::date and '$uptoYear'::date) 
                        ".$whereWard2." 
                       GROUP BY tbl_transaction.ward_mstr_id,tbl_transaction.id
                ),ACTUALCOLLECTION as (select ward_mstr_id,actual_collection_amount,rebate_amount,
                    COALESCE((CASE WHEN (arrear_demand-adjust_amt)<0 OR (arrear_demand-adjust_amt) IS NULL THEN 
                    (CASE WHEN (COALESCE(current_demand+(arrear_demand-adjust_amt),0)<=0) THEN
                    actual_collection_amount ELSE
                    (current_demand+(arrear_demand-adjust_amt)) END)
                    ELSE current_demand
                        END),0) as collected_current,(CASE WHEN adjust_amt IS NULL THEN
                        (CASE WHEN actual_collection_amount IS NULL or actual_collection_amount <=0 THEN
                            0 ELSE arrear_demand END) ELSE (arrear_demand-adjust_amt)
                        END) as collected_arrear from ACTUALCOLLECTION2),
                        ACTUALCOLLECTIONFINAL as (select ward_mstr_id,sum(COALESCE((
                            CASE WHEN ( ROUND((collected_current+collected_arrear)-rebate_amount)=ROUND(actual_collection_amount))
                            THEN COALESCE(collected_current,0)-COALESCE(rebate_amount,0)
                            ELSE (COALESCE(actual_collection_amount,0)-COALESCE(collected_arrear,0))
                            END),0)) as 
                            currentdemand_colt,sum(collected_arrear) as current_arrear_colt from ACTUALCOLLECTION group by ward_mstr_id),

                ADVANCE_AMOUNT AS (
                    SELECT 
                        tbl_prop_dtl.ward_mstr_id,
                        count(tbl_prop_dtl.id) AS collection_from_no_of_HH,
                        SUM(amount) AS adv_amount
                    FROM tbl_prop_dtl
                    INNER JOIN (
                        select prop_dtl_id, sum(amount) as amount from tbl_advance_mstr where tbl_advance_mstr.status=1 
                        AND tbl_advance_mstr.created_on::date>='2023-03-01' and module='Property' and user_id=1
                        group by prop_dtl_id
                    ) tbl_advance_mstr ON tbl_prop_dtl.id=tbl_advance_mstr.prop_dtl_id
                    WHERE tbl_prop_dtl.status=1 and char_length(tbl_prop_dtl.new_holding_no)>0 ".$holding_type." ".$whereWard."
                    GROUP BY tbl_prop_dtl.ward_mstr_id
                )
                SELECT 
                    view_ward_mstr.ward_no,
                    COALESCE(CURRENT_DEMAND.current_holding, 0) as current_holding,
                    CASE WHEN COALESCE(ARREAR_DEMAND.arrear_demand, 0)-COALESCE(PREV_COLLECTION.arrear_collection_amount, 0)>0 then COALESCE(ARREAR_DEMAND.arrear_demand, 0)-COALESCE(PREV_COLLECTION.arrear_collection_amount, 0) else 0 end  AS arrear_demand,
                    COALESCE(CURRENT_DEMAND.current_demand, 0) AS current_demand,
                    (CASE WHEN COALESCE(ARREAR_DEMAND.arrear_demand, 0)-COALESCE(PREV_COLLECTION.arrear_collection_amount, 0)>0 then COALESCE(ARREAR_DEMAND.arrear_demand, 0)-COALESCE(PREV_COLLECTION.arrear_collection_amount, 0) else 0 end)+COALESCE(CURRENT_DEMAND.current_demand, 0) AS total_demand,
                    COALESCE(CURRENT_COLLECTION.collection_from_no_of_HH, 0) as collection_from_no_of_HH,
                    (select count(*) from tbl_transaction txn where txn.tran_type='Property' AND txn.status in (1, 2) AND
                        (txn.tran_date between '$fromYear'::date and '$uptoYear'::date) and txn.ward_mstr_id=view_ward_mstr.id) as collection_from_no_of_HH1,
                    (select count(*) from ACTUAL_COLLECTION where ward_mstr_id=view_ward_mstr.id) as collection_from_no_of_HH2,
                    COALESCE(ARREAR_COLLECTION.arrear_collection_amount, 0) AS arrear_collection_amount,
                    COALESCE(ACTUALCOLLECTIONFINAL.current_arrear_colt, 0) AS arrear_collection_amount2,
                    COALESCE(CURRENT_COLLECTION.current_collection_amount, 0) AS current_collection_amount,
                    COALESCE(ACTUAL_COLLECTION.actual_collection_amount, 0) AS actual_collection_amount,
                    COALESCE(ACTUALCOLLECTIONFINAL.currentdemand_colt, 0) AS actual_collection_amount2,
                    COALESCE(ARREAR_COLLECTION.arrear_collection_amount, 0)+COALESCE(ACTUAL_COLLECTION.actual_collection_amount, 0) AS total_collection_amount,
                    COALESCE(ACTUALCOLLECTIONFINAL.current_arrear_colt, 0)+COALESCE(ACTUALCOLLECTIONFINAL.currentdemand_colt, 0) AS total_collection_amount2,
                    COALESCE(CURRENT_DEMAND.current_holding, 0)-COALESCE(CURRENT_COLLECTION.collection_from_no_of_HH, 0) AS balance_hh,
                    (CASE WHEN COALESCE(ARREAR_DEMAND.arrear_demand, 0)-COALESCE(PREV_COLLECTION.arrear_collection_amount, 0)>0 then COALESCE(ARREAR_DEMAND.arrear_demand, 0)-COALESCE(PREV_COLLECTION.arrear_collection_amount, 0) else 0 end)-COALESCE((ARREAR_COLLECTION.arrear_collection_amount+ACTUALCOLLECTIONFINAL.current_arrear_colt), 0) AS arrear_balance_amount,
                    COALESCE(CURRENT_DEMAND.current_demand, 0)-COALESCE((COALESCE(ACTUAL_COLLECTION.actual_collection_amount,0)+COALESCE(ACTUALCOLLECTIONFINAL.currentdemand_colt,0)), 0) AS current_balance_amount,
                    COALESCE(((CASE WHEN COALESCE(ARREAR_DEMAND.arrear_demand, 0)-COALESCE(PREV_COLLECTION.arrear_collection_amount, 0)>0 then COALESCE(ARREAR_DEMAND.arrear_demand, 0)-COALESCE(PREV_COLLECTION.arrear_collection_amount, 0) else 0 end)-ARREAR_COLLECTION.arrear_collection_amount)+(CURRENT_DEMAND.current_demand-CURRENT_COLLECTION.current_collection_amount), 0) AS total_balance_amount,
                    COALESCE(((CASE WHEN COALESCE(ARREAR_DEMAND.arrear_demand, 0)-COALESCE(PREV_COLLECTION.arrear_collection_amount, 0)>0 then COALESCE(ARREAR_DEMAND.arrear_demand, 0)-COALESCE(PREV_COLLECTION.arrear_collection_amount, 0) else 0 end)-(ARREAR_COLLECTION.arrear_collection_amount+ACTUALCOLLECTIONFINAL.current_arrear_colt))+(CURRENT_DEMAND.current_demand-(ACTUAL_COLLECTION.actual_collection_amount+ACTUALCOLLECTIONFINAL.currentdemand_colt)), 0) AS total_balance_amount3,
                    (CASE WHEN COALESCE(ARREAR_DEMAND.arrear_demand, 0)-COALESCE(PREV_COLLECTION.arrear_collection_amount, 0)>0 then COALESCE(ARREAR_DEMAND.arrear_demand, 0)-COALESCE(PREV_COLLECTION.arrear_collection_amount, 0) else 0 end)-COALESCE((ARREAR_COLLECTION.arrear_collection_amount+ACTUALCOLLECTIONFINAL.current_arrear_colt), 0)+COALESCE(CURRENT_DEMAND.current_demand, 0)-COALESCE((COALESCE(ACTUAL_COLLECTION.actual_collection_amount,0)+COALESCE(ACTUALCOLLECTIONFINAL.currentdemand_colt,0)), 0) AS total_balance_amount2,
                    (CASE 
                        WHEN CURRENT_COLLECTION.collection_from_no_of_HH IS NULL OR CURRENT_COLLECTION.collection_from_no_of_HH=0 THEN 0
                        ELSE
                        ROUND((COALESCE(CURRENT_COLLECTION.collection_from_no_of_HH, 0)*100)/(CASE WHEN COALESCE(CURRENT_DEMAND.current_holding, 0)!=0 then COALESCE(CURRENT_DEMAND.current_holding, 0) else 1 end), 2)
                    END) AS hh_percentage,
                    (CASE
                        WHEN ARREAR_COLLECTION.arrear_collection_amount IS NULL OR ARREAR_COLLECTION.arrear_collection_amount=0 THEN 0
                        WHEN CURRENT_COLLECTION.current_collection_amount IS NULL OR CURRENT_COLLECTION.current_collection_amount=0 THEN 0
                        WHEN COALESCE(ARREAR_DEMAND.arrear_demand+CURRENT_DEMAND.current_demand, 0)=0 THEN 0
                        ELSE
                            ROUND((COALESCE(ARREAR_COLLECTION.arrear_collection_amount+CURRENT_COLLECTION.current_collection_amount, 0)*100)/(CASE WHEN COALESCE(ARREAR_DEMAND.arrear_demand+CURRENT_DEMAND.current_demand, 0)!=0 then COALESCE(ARREAR_DEMAND.arrear_demand+CURRENT_DEMAND.current_demand, 0) else 1 end), 2) 
                    END) AS amount_percentage,
					".
                    ($data['fy_mstr_id'] == 54?"COALESCE(ADVANCE_AMOUNT.adv_amount, 0)":"0")
					." as advance_amount
                FROM view_ward_mstr
                LEFT JOIN ARREAR_DEMAND ON ARREAR_DEMAND.ward_mstr_id=view_ward_mstr.id
                LEFT JOIN PREV_COLLECTION ON PREV_COLLECTION.ward_mstr_id=view_ward_mstr.id
                LEFT JOIN CURRENT_DEMAND ON CURRENT_DEMAND.ward_mstr_id=view_ward_mstr.id
                LEFT JOIN ARREAR_COLLECTION ON ARREAR_COLLECTION.ward_mstr_id=view_ward_mstr.id
                LEFT JOIN CURRENT_COLLECTION ON CURRENT_COLLECTION.ward_mstr_id=view_ward_mstr.id
                LEFT JOIN ACTUAL_COLLECTION ON ACTUAL_COLLECTION.ward_mstr_id=view_ward_mstr.id
                LEFT JOIN ACTUALCOLLECTIONFINAL ON ACTUALCOLLECTIONFINAL.ward_mstr_id=view_ward_mstr.id
                LEFT JOIN ADVANCE_AMOUNT ON ADVANCE_AMOUNT.ward_mstr_id=view_ward_mstr.id
                $whereWard0
                ORDER BY SUBSTRING(view_ward_mstr.ward_no FROM '([0-9]+)')::BIGINT ASC, view_ward_mstr.ward_no";
                //print_var($sql);
                $totalcollection="select sum(tbl_transaction.payable_amt) as actual_collection_amount,count(tbl_transaction.prop_dtl_id) as collection_from_no_of_HH
                FROM tbl_transaction
                        INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_transaction.prop_dtl_id                      
                        WHERE tbl_transaction.tran_type='Property' AND tbl_transaction.status in (1, 2) AND
                (tbl_transaction.tran_date between '$fromYear'::date and '$uptoYear'::date)";
                $builder = $this->db->query($sql);
                $builder2 = $this->db->query($totalcollection);
                $actual_collection_ = $builder2->getResultArray()[0]['actual_collection_amount'];
                $remaining=$actual_collection_;
                if ($report_list = $builder->getResultArray()) {
                    // if($data['fy_mstr_id']==55){
                    //     $report_list['52']['arrear_demand']=85645;
                    //     $report_list['52']['arrear_balance_amount'] = $report_list['52']['arrear_demand']-$report_list['52']['arrear_collection_amount'];
                    // }
                    if($data['fy_mstr_id']==55){
                        $report_list['0']['arrear_demand']=1583705.94;
                        if(isset($report_list['0']['arrear_collection_amount'])){
                            $report_list['0']['arrear_balance_amount'] = $report_list['0']['arrear_demand']-($report_list['0']['arrear_collection_amount'])??0;
                            $report_list['0']['total_demand'] =  $report_list['0']['arrear_demand']+($report_list['0']['current_demand'])??0;
                        }
                        if(isset($report_list['28']['arrear_collection_amount'])) {
                            $report_list['28']['arrear_demand'] = 6021015.85;
                            $report_list['28']['arrear_balance_amount'] = $report_list['28']['arrear_demand'] - ($report_list['28']['arrear_collection_amount']) ?? 0;
                            $report_list['28']['total_demand'] =  $report_list['28']['arrear_demand']+($report_list['28']['current_demand'])??0;
                        }
                        if(isset($report_list['32']['arrear_collection_amount'])) {
                            $report_list['32']['arrear_demand'] = 1036012.58;
                            $report_list['32']['arrear_balance_amount'] = $report_list['32']['arrear_demand'] - $report_list['32']['arrear_collection_amount'] ?? 0;
                            $report_list['32']['total_demand'] =  $report_list['32']['arrear_demand']+($report_list['32']['current_demand'])??0;
                        }
                        if(isset($report_list['22']['arrear_collection_amount'])) {
                            $report_list['22']['arrear_demand'] = 1119567.80;
                            $report_list['22']['arrear_balance_amount'] = $report_list['22']['arrear_demand'] - $report_list['22']['arrear_collection_amount'] ?? 0;
                            $report_list['22']['total_demand'] =  $report_list['22']['arrear_demand']+($report_list['22']['current_demand'])??0;
                        }
                        if(isset($report_list['40']['arrear_collection_amount'])) {
                            $report_list['40']['arrear_demand'] = 2794882.81;
                            $report_list['40']['arrear_balance_amount'] = $report_list['40']['arrear_demand'] - $report_list['40']['arrear_collection_amount'] ?? 0;
                            $report_list['40']['total_demand'] =  $report_list['40']['arrear_demand']+($report_list['40']['current_demand'])??0;
                        }
                        if(isset($report_list['38']['arrear_collection_amount'])) {
                            $report_list['38']['arrear_demand'] = 2735329.96;
                            $report_list['38']['arrear_balance_amount'] = $report_list['38']['arrear_demand'] - $report_list['38']['arrear_collection_amount'] ?? 0;
                            $report_list['38']['total_demand'] =  $report_list['38']['arrear_demand']+($report_list['38']['current_demand'])??0;
                        }
                        if(isset($report_list['42']['arrear_collection_amount'])) {
                            $report_list['42']['arrear_demand'] = 3111960.99;
                            $report_list['42']['arrear_balance_amount'] = $report_list['42']['arrear_demand'] - $report_list['42']['arrear_collection_amount'] ?? 0;
                            $report_list['42']['total_demand'] =  $report_list['42']['arrear_demand']+($report_list['42']['current_demand'])??0;
                        }
                        if(isset($report_list['44']['arrear_collection_amount'])) {
                            $report_list['44']['arrear_demand'] = 3174090.10;
                            $report_list['44']['arrear_balance_amount'] = $report_list['44']['arrear_demand'] - $report_list['44']['arrear_collection_amount'] ?? 0;
                            $report_list['44']['total_demand'] =  $report_list['44']['arrear_demand']+($report_list['44']['current_demand'])??0;
                        }
                        if(isset($report_list['154']['arrear_collection_amount'])) {
                            $report_list['154']['arrear_demand'] = 506368.38;
                            $report_list['154']['arrear_balance_amount'] = $report_list['154']['arrear_demand'] - $report_list['154']['arrear_collection_amount'] ?? 0;
                            $report_list['154']['total_demand'] =  $report_list['154']['arrear_demand']+($report_list['154']['current_demand'])??0;
                        }
                        if(isset($report_list['16']['arrear_collection_amount'])) {
                            $report_list['16']['arrear_demand'] = 1187196.24;
                            $report_list['16']['arrear_balance_amount'] = $report_list['16']['arrear_demand'] - $report_list['16']['arrear_collection_amount'] ?? 0;
                            $report_list['16']['total_demand'] =  $report_list['16']['arrear_demand']+($report_list['16']['current_demand'])??0;
                        }
                        if(isset($report_list['52']['arrear_collection_amount'])) {
                            $report_list['52']['arrear_demand'] = 1602256.36;
                            $report_list['52']['arrear_balance_amount'] = $report_list['52']['arrear_demand'] - $report_list['52']['arrear_collection_amount'] ?? 0;
                            $report_list['52']['total_demand'] =  $report_list['52']['arrear_demand']+($report_list['52']['current_demand'])??0;
                        }
                        if(isset($report_list['54']['arrear_collection_amount'])) {
                            $report_list['54']['arrear_demand'] = 3094738.23;
                            $report_list['54']['arrear_balance_amount'] = $report_list['54']['arrear_demand'] - $report_list['54']['arrear_collection_amount'] ?? 0;
                            $report_list['54']['total_demand'] =  $report_list['54']['arrear_demand']+($report_list['54']['current_demand'])??0;
                        }
                        if(isset($report_list['79']['arrear_collection_amount'])) {
                            $report_list['79']['arrear_demand'] = 3156569.32;
                            $report_list['79']['arrear_balance_amount'] = $report_list['79']['arrear_demand'] - $report_list['79']['arrear_collection_amount'] ?? 0;
                            $report_list['79']['total_demand'] =  $report_list['79']['arrear_demand']+($report_list['79']['current_demand'])??0;
                        }
                        if(isset($report_list['114']['arrear_collection_amount'])) {
                            $report_list['114']['arrear_demand'] = 1578811.18;
                            $report_list['114']['arrear_balance_amount'] = $report_list['114']['arrear_demand'] - $report_list['114']['arrear_collection_amount'] ?? 0;
                            $report_list['114']['total_demand'] =  $report_list['114']['arrear_demand']+($report_list['114']['current_demand'])??0;
                        }
                        if(isset($report_list['50']['arrear_collection_amount'])) {
                            $report_list['50']['arrear_demand'] = 3021048;
                            $report_list['50']['arrear_balance_amount'] = $report_list['50']['arrear_demand'] - $report_list['50']['arrear_collection_amount'] ?? 0;
                            $report_list['50']['total_demand'] =  $report_list['50']['arrear_demand']+($report_list['50']['current_demand'])??0;
                        }
                        if(isset($report_list['105']['arrear_collection_amount'])) {
                            $report_list['105']['arrear_demand'] = 2414902.44;
                            $report_list['105']['arrear_balance_amount'] = $report_list['105']['arrear_demand'] - $report_list['105']['arrear_collection_amount'] ?? 0;
                            $report_list['105']['total_demand'] =  $report_list['105']['arrear_demand']+($report_list['105']['current_demand'])??0;
                        }
                        if(isset($report_list['6']['arrear_collection_amount'])) {
                            $report_list['6']['arrear_demand'] = 6048756.17;
                            $report_list['6']['arrear_balance_amount'] = $report_list['6']['arrear_demand'] - $report_list['6']['arrear_collection_amount'] ?? 0;
                            $report_list['6']['total_demand'] =  $report_list['6']['arrear_demand']+($report_list['6']['current_demand'])??0;
                        }
                        if(isset($report_list['67']['arrear_collection_amount'])) {
                            $report_list['67']['arrear_demand'] = 4329231.17;
                            $report_list['67']['arrear_balance_amount'] = $report_list['67']['arrear_demand'] - $report_list['67']['arrear_collection_amount'] ?? 0;
                            $report_list['67']['total_demand'] =  $report_list['67']['arrear_demand']+($report_list['67']['current_demand'])??0;
                        }
                        if(isset($report_list['8']['arrear_collection_amount'])) {
                            $report_list['8']['arrear_demand'] = 5020476.73;
                            $report_list['8']['arrear_balance_amount'] = $report_list['8']['arrear_demand'] - $report_list['8']['arrear_collection_amount'] ?? 0;
                            $report_list['8']['total_demand'] =  $report_list['8']['arrear_demand']+($report_list['8']['current_demand'])??0;
                        }
                    }
                    $data['report_list'] = $report_list;
                }
                // dd($report_list);
                //echo $this->db->getLastQuery();
            }catch(Exception $e){
                print_r($e);
            }
        }
        $data['wardList'] = $wardList;
        $data['fyList'] = $fyList;
        $data['prop_collection'] = $remaining??0;
        if(isset($_REQUEST["api"])){
            return $data;
        }
        return view('property/reports/ward_wise_DCB_report', $data);
    }

    public function wardWiseDCB_old_2025_04_29()
    {
        $ulb_mstr_id = 1;
        $wardList = $this->model_ward_mstr->getWardList(['ulb_mstr_id'=>$ulb_mstr_id]);
        $currentFY = getFY();
        $currentFyID = $this->model_fy_mstr->getFyByFy(['fy'=>$currentFY])['id'];
        $fyList = $this->model_fy_mstr->getFYListDescBefore10Year($currentFyID);
        if($this->request->getMethod()=='post')
        {
            try {
                $data = arrFilterSanitizeString($this->request->getVar());
                //print_r($data);
                $WhereFYCurrent = "";
                $WhereFYArrear = "";
                $Where22_23 = "";
                $propDemand = "";
                if($data['fy_mstr_id']!='') {
                    if($data['fy_mstr_id']==53) #  for current demand
                    {

                        $Where22_23 =" AND tbl_prop_demand.fy_mstr_id=54";
                        $propDemand = "prop_demand as(
                                            select SUM(COALESCE(tbl_prop_demand.amount, 0) - COALESCE(tbl_prop_demand.adjust_amt, 0)) as arrear_demand,tbl_prop_demand.prop_dtl_id from tbl_prop_demand
                                            INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id
                                            where tbl_prop_demand.status=1 and tbl_prop_demand.fy_mstr_id=53 and tbl_prop_dtl.status=1 and tbl_prop_demand.status=1 
                                            and char_length(tbl_prop_dtl.new_holding_no)>0  and (tbl_prop_dtl.govt_saf_dtl_id is null or tbl_prop_dtl.govt_saf_dtl_id=0)
                                            group by tbl_prop_demand.prop_dtl_id
                                        ),";
                    }
                    elseif($data['fy_mstr_id']==54) #for  earrir Demand
                    {
                        $Where22_23 ="";
                        $propDemand = "prop_demand as(
                            select SUM(COALESCE(tbl_prop_demand.amount, 0) - COALESCE(tbl_prop_demand.adjust_amt, 0)) as arrear_demand,tbl_prop_demand.prop_dtl_id 
                            ,tbl_prop_dtl.ward_mstr_id
                            from tbl_prop_demand
                            INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id
                            where tbl_prop_demand.status=1 and tbl_prop_demand.fy_mstr_id=54 and tbl_prop_dtl.status=1 and tbl_prop_demand.status=1 
                            and char_length(tbl_prop_dtl.new_holding_no)>0  and (tbl_prop_dtl.govt_saf_dtl_id is null or tbl_prop_dtl.govt_saf_dtl_id=0)
                            group by tbl_prop_demand.prop_dtl_id,tbl_prop_dtl.ward_mstr_id
                        ),";
                    }
                    elseif($data['fy_mstr_id']==55) #for  earrir Deman
                    {
                        //$Where22_23 =" AND tbl_prop_demand.fy_mstr_id=55";
                        $Where22_23 ="";
                        $propDemand = "prop_demand as(
                            select SUM(COALESCE(tbl_prop_demand.amount, 0) - COALESCE(tbl_prop_demand.adjust_amt, 0)) as arrear_demand,tbl_prop_demand.prop_dtl_id 
                            ,tbl_prop_dtl.ward_mstr_id
                            from tbl_prop_demand
                            INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id
                            where tbl_prop_demand.status=1 and tbl_prop_demand.fy_mstr_id=55 and tbl_prop_dtl.status=1 and tbl_prop_demand.status=1 
                            and char_length(tbl_prop_dtl.new_holding_no)>0  and (tbl_prop_dtl.govt_saf_dtl_id is null or tbl_prop_dtl.govt_saf_dtl_id=0)
                            group by tbl_prop_demand.prop_dtl_id,tbl_prop_dtl.ward_mstr_id
                        ),";
                    }

                    $WhereFYCurrent = " AND tbl_prop_demand.fy_mstr_id=".$data['fy_mstr_id'];
                    ///$WhereFYCurrent2 = " AND tbl_transaction.fy_mstr_id=".$data['fy_mstr_id'];
                    $WhereFYArrear = " AND tbl_prop_demand.fy_mstr_id<".$data['fy_mstr_id'];
                    $fyear = explode('-',$this->model_fy_mstr->getFyearByFyid($data['fy_mstr_id'])['fy']);
                    
                    $fromYear = $fyear[0].'-04-01';
                    $uptoYear = $fyear[1].'-03-31';
                }

                $whereWard = "";
                $whereWard0 = "";
                $whereWard1 = "";
                $whereWard2 = "";
                if($data['ward_mstr_id']!='') {
                    $whereWard = " AND tbl_prop_dtl.ward_mstr_id=".$data['ward_mstr_id'];
                    $whereWard1 = " AND tbl_prop_demand.ward_mstr_id=".$data['ward_mstr_id'];
                    $whereWard0 = "where view_ward_mstr.id=".$data['ward_mstr_id'];
                    $whereWard2 = " AND tbl_transaction.ward_mstr_id=".$data['ward_mstr_id'];
                    // print_r($data['ward_mstr_id']);
                    // return;
                }
                $holding_type = "";
                if (isset($data['residencial'])
                    || isset($data['non_residential'])
                    || isset($data['vacant_land'])
                    || isset($data['government_building'])) {

                    if (isset($data['residencial'])) {
                        $holding_type .= "'PURE_RESIDENTIAL'";
                    }
                    if (isset($data['non_residential'])) {
                        if ($holding_type!='') {
                            $holding_type .= ", ";
                        }
                        $holding_type .= "'PURE_COMMERCIAL', 'MIX_COMMERCIAL'";
                    }
                    if (isset($data['government_building'])) {
                        if ($holding_type!='') {
                            $holding_type .= ", ";
                        }
                        $holding_type .= "'PURE_GOVERNMENT', 'MIX_GOVERNMENT'";
                    }

                    if (isset($data['vacant_land'])) {
                        if ($holding_type!='') {
                            $holding_type .= ", ";
                        }
                        $holding_type .= "'VACANT_LAND'";
                    }
                    if ($holding_type!='') {
                        $holding_type = " AND tbl_prop_dtl.holding_type IN (".$holding_type.")";
                    }
                }
                //$dd = "PURE_RESIDENTIAL, PURE_COMMERCIAL, PURE_GOVERNMENT, MIX_GOVERNMENT, MIX_COMMERCIAL";                
                // By Hayat
                $AREA_DEMAND = "ARREAR_DEMAND AS (
                    SELECT 
                        tbl_prop_dtl.ward_mstr_id,
                        SUM(COALESCE(tbl_prop_demand.amount, 0) - COALESCE(tbl_prop_demand.adjust_amt, 0)) AS arrear_demand
                    FROM tbl_prop_demand
                    INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id
                    WHERE 
                        tbl_prop_dtl.status=1 ".$holding_type." 
                        AND tbl_prop_demand.status=1 
                        AND tbl_prop_demand.paid_status IN(0,1) and char_length(tbl_prop_dtl.new_holding_no)>0 and (tbl_prop_dtl.govt_saf_dtl_id is null or tbl_prop_dtl.govt_saf_dtl_id=0) and tbl_prop_demand.due_date is not null ".$whereWard.$WhereFYArrear." 
                        GROUP BY tbl_prop_dtl.ward_mstr_id
                ),";
                if($data['fy_mstr_id']==54 || $data['fy_mstr_id']==55)
                {
                    $fact= 4.98;
                    if($data['fy_mstr_id']==55){
                        $fact=10.9;
                    }
                    $WhereFYArrear2 = " AND tbl_prop_demand.fy_mstr_id<".($data['fy_mstr_id']-1);
                  
                    $AREA_DEMAND = "ARREAR_DEMAND AS (
                                        SELECT curent.ward_mstr_id,
                                            SUM(curent.arrear_demand-(curent.arrear_demand*$fact/100)) arrear_demand 
                                        FROM (
                                            SELECT 
                                                tbl_prop_dtl.ward_mstr_id,
                                                SUM(COALESCE(tbl_prop_demand.amount, 0) - COALESCE(tbl_prop_demand.adjust_amt, 0)) AS arrear_demand
                                            FROM tbl_prop_demand
                                            INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id
                                            WHERE 
                                                tbl_prop_dtl.status=1 ".$holding_type." 
                                                AND tbl_prop_demand.status=1 
                                                AND tbl_prop_demand.paid_status IN(0,1) and char_length(tbl_prop_dtl.new_holding_no)>0 and tbl_prop_demand.due_date is not null ".$whereWard.$WhereFYArrear2." and (tbl_prop_dtl.govt_saf_dtl_id is null or tbl_prop_dtl.govt_saf_dtl_id=0)
                                                GROUP BY tbl_prop_dtl.ward_mstr_id
                                            )curent
                                        JOIN (
                                            select ward_mstr_id,
                                                SUM(case when arrear_demand>0 then arrear_demand else 0 end) as arrear_demand
                                            from prop_demand
                                            group by ward_mstr_id
                                        )prop_demand2 on prop_demand2.ward_mstr_id=curent.ward_mstr_id
                                        group by curent.ward_mstr_id 
                                    ),";
                }
                $fromYear2 = $fromYear;
                if($data['fy_mstr_id']==54 || $data['fy_mstr_id']==55){$fromYear2 = ($fyear[0]-1).'-04-01';}
                $sql = "WITH 
                PREV_COLLECTION AS (
                    SELECT 
                        tbl_prop_dtl.ward_mstr_id,
                        SUM(COALESCE(tbl_collection.amount, 0)) AS arrear_collection_amount
                    FROM tbl_prop_dtl
                    INNER JOIN tbl_collection on tbl_collection.prop_dtl_id=tbl_prop_dtl.id AND tbl_collection.created_on::date < '".$fromYear2."'
                    WHERE tbl_prop_dtl.status=1 ".$holding_type." and tbl_collection.status=1 and char_length(tbl_prop_dtl.new_holding_no)>0 ".$whereWard." and (tbl_prop_dtl.govt_saf_dtl_id is null or tbl_prop_dtl.govt_saf_dtl_id=0)
                    GROUP BY tbl_prop_dtl.ward_mstr_id
                ),".($propDemand?
                $propDemand:"")."".
                $AREA_DEMAND."

                /*ARREAR_DEMAND AS (
                    SELECT 
                        tbl_prop_dtl.ward_mstr_id,
                        SUM(COALESCE(tbl_prop_demand.amount, 0) - COALESCE(tbl_prop_demand.adjust_amt, 0)) AS arrear_demand
                    FROM tbl_prop_demand
                    INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id
                    WHERE 
                        tbl_prop_dtl.status=1 ".$holding_type." 
                        AND tbl_prop_demand.status=1 
                        AND tbl_prop_demand.paid_status IN(0,1) and char_length(tbl_prop_dtl.new_holding_no)>0 and tbl_prop_demand.due_date is not null ".$whereWard.$WhereFYArrear." 
                        GROUP BY tbl_prop_dtl.ward_mstr_id
                ),*/
                CURRENT_DEMAND AS (
                    SELECT 
                        tbl_prop_dtl.ward_mstr_id,
                        count(tbl_prop_dtl.id) AS current_holding,
                        SUM(COALESCE(tbl_prop_demand.amount, 0) - COALESCE(tbl_prop_demand.adjust_amt, 0)) AS current_demand
                    FROM tbl_prop_dtl
                    RIGHT JOIN (
                        select prop_dtl_id, sum(amount) as amount, sum(adjust_amt) as adjust_amt from tbl_prop_demand where tbl_prop_demand.status=1 
                        AND tbl_prop_demand.paid_status IN (0,1) and tbl_prop_demand.due_date is not null  ".$whereWard1.(isset($data['fy_mstr_id']) && $data['fy_mstr_id']!=53?$WhereFYCurrent:$Where22_23)." 
                        group by prop_dtl_id
                    ) tbl_prop_demand ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id
                    ".(isset($data['fy_mstr_id']) && $data['fy_mstr_id']==53 ? " JOIN prop_demand on prop_demand.prop_dtl_id=tbl_prop_demand.prop_dtl_id":"")."
                    WHERE tbl_prop_dtl.status=1 and char_length(tbl_prop_dtl.new_holding_no)>0  and (tbl_prop_dtl.govt_saf_dtl_id is null or tbl_prop_dtl.govt_saf_dtl_id=0) ".$holding_type."
                    GROUP BY tbl_prop_dtl.ward_mstr_id

                ),
                ARREAR_COLLECTION_ AS (
                    SELECT 
                        tbl_prop_dtl.ward_mstr_id,
                        SUM(COALESCE(tbl_prop_demand.amount, 0) - COALESCE(tbl_prop_demand.adjust_amt, 0)) AS arrear_collection_amount
                    FROM tbl_prop_demand
                    INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id
                    INNER JOIN tbl_collection on tbl_collection.prop_demand_id=tbl_prop_demand.id AND (tbl_collection.created_on::date between '".$fromYear."' and '".$uptoYear."') 
                    WHERE tbl_prop_dtl.status=1 ".$holding_type." and tbl_prop_demand.status=1 and char_length(tbl_prop_dtl.new_holding_no)>0 ".$whereWard.$WhereFYArrear."
                    AND tbl_prop_demand.paid_status=1 and (tbl_prop_dtl.govt_saf_dtl_id is null or tbl_prop_dtl.govt_saf_dtl_id=0)
                    GROUP BY tbl_prop_dtl.ward_mstr_id
                ),
                ARREAR_COLLECTION AS (
                    SELECT 
                        tbl_transaction.ward_mstr_id,
                        count(tbl_transaction.prop_dtl_id) AS arrear_collection_from_no_of_hh,
                        sum(tbl_transaction.payable_amt) AS arrear_collection_amount
                    FROM tbl_transaction
                     INNER JOIN view_ward_mstr ON view_ward_mstr.id=tbl_transaction.ward_mstr_id
                     WHERE tbl_transaction.tran_type='Property' AND tbl_transaction.status in (1, 2) AND
                     (tbl_transaction.upto_fy_mstr_id!='".$data['fy_mstr_id']."' or tbl_transaction.upto_fy_mstr_id IS NULL)
                        AND (tbl_transaction.tran_date between '$fromYear'::date and '$uptoYear'::date) 
                        ".$whereWard2." GROUP BY tbl_transaction.ward_mstr_id
                ),
                CURRENT_COLLECTION_ AS (
                    SELECT 
                        tbl_prop_dtl.ward_mstr_id,
                        count(tbl_prop_dtl.id) AS collection_from_no_of_HH,
                        SUM(tbl_prop_demand.amount - tbl_prop_demand.adjust_amt) AS current_collection_amount
                    FROM tbl_prop_dtl
                    INNER JOIN (
                        select tbl_prop_demand.prop_dtl_id, sum(tbl_prop_demand.amount) as amount, sum(tbl_prop_demand.adjust_amt) as adjust_amt from tbl_prop_demand 
                        INNER JOIN tbl_collection on tbl_collection.prop_demand_id=tbl_prop_demand.id AND (tbl_collection.created_on::date between '".$fromYear."' and '".$uptoYear."') 
                        where tbl_prop_demand.status=1 
                        AND tbl_prop_demand.paid_status=1 AND tbl_prop_demand.status=1 ".$whereWard1.$WhereFYCurrent." 
                        group by tbl_prop_demand.prop_dtl_id
                    ) tbl_prop_demand ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id
                    
                    WHERE tbl_prop_dtl.status=1 and char_length(tbl_prop_dtl.new_holding_no)>0 and (tbl_prop_dtl.govt_saf_dtl_id is null or tbl_prop_dtl.govt_saf_dtl_id=0) ".$holding_type."
                    GROUP BY tbl_prop_dtl.ward_mstr_id
                ), CURRENT_COLLECTION AS (
                    SELECT 
                        tbl_prop_dtl.ward_mstr_id,
                        count(tbl_prop_dtl.id) AS collection_from_no_of_HH,
                        SUM(tbl_prop_demand.holding_tax + tbl_prop_demand.additional_amt + tbl_prop_demand.fine_amt) AS current_collection_amount
                       -- SUM(tbl_prop_demand.amount + tbl_prop_demand.fine_amt) AS current_collection_amount
                    FROM tbl_prop_dtl
                    INNER JOIN (
                        select tbl_prop_demand.prop_dtl_id, sum(tbl_collection.amount) as amount, sum(tbl_collection.holding_tax) as holding_tax, sum(tbl_collection.additional_tax) as additional_amt,
                        sum(tbl_collection.fine_amt) as fine_amt from tbl_prop_demand 
                        INNER JOIN tbl_collection on tbl_collection.prop_demand_id=tbl_prop_demand.id AND (tbl_collection.fy_mstr_id='".$data['fy_mstr_id']."')
                        where tbl_prop_demand.status=1 
                        AND tbl_prop_demand.paid_status=1 ".$whereWard1.$WhereFYCurrent." 
                        group by tbl_prop_demand.prop_dtl_id
                    ) tbl_prop_demand ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id
                    WHERE tbl_prop_dtl.status=1 and char_length(tbl_prop_dtl.new_holding_no)>0 and (tbl_prop_dtl.govt_saf_dtl_id is null or tbl_prop_dtl.govt_saf_dtl_id=0) ".$holding_type."
                    GROUP BY tbl_prop_dtl.ward_mstr_id
                ),
                 ACTUAL_COLLECTION AS (
                    SELECT 
                        tbl_transaction.ward_mstr_id,
                        count(tbl_transaction.prop_dtl_id) AS collection_from_no_of_HH,
                        sum(tbl_transaction.payable_amt) AS actual_collection_amount
                    FROM tbl_transaction
                     INNER JOIN view_ward_mstr ON view_ward_mstr.id=tbl_transaction.ward_mstr_id
                     WHERE (tbl_transaction.upto_fy_mstr_id=".$data['fy_mstr_id']." and tbl_transaction.from_fy_mstr_id=".$data['fy_mstr_id'].") AND tbl_transaction.tran_type='Property' AND tbl_transaction.status in (1, 2) AND
                        (tbl_transaction.tran_date between '$fromYear'::date and '$uptoYear'::date) 
                        ".$whereWard2." 
                       GROUP BY tbl_transaction.ward_mstr_id
                ),
                ACTUALCOLLECTION2 AS (
                    SELECT 
                        tbl_transaction.ward_mstr_id,
                        count(tbl_transaction.prop_dtl_id) AS collection_from_no_of_HH,
                        sum(tbl_transaction.payable_amt) AS actual_collection_amount,sum(tbl_transaction.discount_amt) as rebate_amount,
                        (select sum(tbl_collection.amount+tbl_collection.fine_amt) from tbl_collection 
                            where tbl_collection.created_on::date between '$fromYear'::date and '$uptoYear'::date and tbl_collection.fy_mstr_id=".$data['fy_mstr_id']." and status=1
                            and tbl_collection.transaction_id=tbl_transaction.id group by tbl_collection.transaction_id) as current_demand,
                            (select sum(tbl_collection.amount+tbl_collection.fine_amt) from tbl_collection 
                            where tbl_collection.created_on::date between '$fromYear'::date and '$uptoYear'::date and tbl_collection.fy_mstr_id!=".$data['fy_mstr_id']." and status=1
                            and tbl_collection.transaction_id=tbl_transaction.id group by tbl_collection.transaction_id) as arrear_demand,
                            (select sum(tbl_transaction_fine_rebet_details.amount) from tbl_transaction_fine_rebet_details
                            where tbl_transaction_fine_rebet_details.created_on::date between '$fromYear'::date and '$uptoYear'::date and tbl_transaction_fine_rebet_details.status=1
                            and head_name IN ('Adjusted Amount') and tbl_transaction_fine_rebet_details.transaction_id=tbl_transaction.id group by tbl_transaction_fine_rebet_details.transaction_id)
                            as adjust_amt
                    FROM tbl_transaction
                     INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_transaction.prop_dtl_id
                     INNER JOIN view_ward_mstr ON view_ward_mstr.id=tbl_transaction.ward_mstr_id
                     WHERE (tbl_transaction.from_fy_mstr_id!=".$data['fy_mstr_id']." and tbl_transaction.upto_fy_mstr_id=".$data['fy_mstr_id'].") AND tbl_transaction.tran_type='Property' AND tbl_transaction.status in (1, 2) AND
                        (tbl_transaction.tran_date between '$fromYear'::date and '$uptoYear'::date) 
                        ".$whereWard2." 
                       GROUP BY tbl_transaction.ward_mstr_id,tbl_transaction.id
                ),ACTUALCOLLECTION as (select ward_mstr_id,actual_collection_amount,rebate_amount,
                    COALESCE((CASE WHEN (arrear_demand-adjust_amt)<0 OR (arrear_demand-adjust_amt) IS NULL THEN 
                    (CASE WHEN (COALESCE(current_demand+(arrear_demand-adjust_amt),0)<=0) THEN
                    actual_collection_amount ELSE
                    (current_demand+(arrear_demand-adjust_amt)) END)
                    ELSE current_demand
                        END),0) as collected_current,(CASE WHEN adjust_amt IS NULL THEN
                        (CASE WHEN actual_collection_amount IS NULL or actual_collection_amount <=0 THEN
                            0 ELSE arrear_demand END) ELSE (arrear_demand-adjust_amt)
                        END) as collected_arrear from ACTUALCOLLECTION2),
                        ACTUALCOLLECTIONFINAL as (select ward_mstr_id,sum(COALESCE((
                            CASE WHEN ( ROUND((collected_current+collected_arrear)-rebate_amount)=ROUND(actual_collection_amount))
                            THEN COALESCE(collected_current,0)-COALESCE(rebate_amount,0)
                            ELSE (COALESCE(actual_collection_amount,0)-COALESCE(collected_arrear,0))
                            END),0)) as 
                            currentdemand_colt,sum(collected_arrear) as current_arrear_colt from ACTUALCOLLECTION group by ward_mstr_id),

                ADVANCE_AMOUNT AS (
                    SELECT 
                        tbl_prop_dtl.ward_mstr_id,
                        count(tbl_prop_dtl.id) AS collection_from_no_of_HH,
                        SUM(amount) AS adv_amount
                    FROM tbl_prop_dtl
                    INNER JOIN (
                        select prop_dtl_id, sum(amount) as amount from tbl_advance_mstr where tbl_advance_mstr.status=1 
                        AND tbl_advance_mstr.created_on::date>='2023-03-01' and module='Property' and user_id=1
                        group by prop_dtl_id
                    ) tbl_advance_mstr ON tbl_prop_dtl.id=tbl_advance_mstr.prop_dtl_id
                    WHERE tbl_prop_dtl.status=1 and char_length(tbl_prop_dtl.new_holding_no)>0 ".$holding_type." ".$whereWard."
                    GROUP BY tbl_prop_dtl.ward_mstr_id
                )
                SELECT 
                    view_ward_mstr.ward_no,
                    COALESCE(CURRENT_DEMAND.current_holding, 0) as current_holding,
                    CASE WHEN COALESCE(ARREAR_DEMAND.arrear_demand, 0)-COALESCE(PREV_COLLECTION.arrear_collection_amount, 0)>0 then COALESCE(ARREAR_DEMAND.arrear_demand, 0)-COALESCE(PREV_COLLECTION.arrear_collection_amount, 0) else 0 end  AS arrear_demand,
                    COALESCE(CURRENT_DEMAND.current_demand, 0) AS current_demand,
                    (CASE WHEN COALESCE(ARREAR_DEMAND.arrear_demand, 0)-COALESCE(PREV_COLLECTION.arrear_collection_amount, 0)>0 then COALESCE(ARREAR_DEMAND.arrear_demand, 0)-COALESCE(PREV_COLLECTION.arrear_collection_amount, 0) else 0 end)+COALESCE(CURRENT_DEMAND.current_demand, 0) AS total_demand,
                    COALESCE(CURRENT_COLLECTION.collection_from_no_of_HH, 0) as collection_from_no_of_HH,
                    (select count(*) from tbl_transaction txn where txn.tran_type='Property' AND txn.status in (1, 2) AND
                        (txn.tran_date between '$fromYear'::date and '$uptoYear'::date) and txn.ward_mstr_id=view_ward_mstr.id) as collection_from_no_of_HH1,
                    (select count(*) from ACTUAL_COLLECTION where ward_mstr_id=view_ward_mstr.id) as collection_from_no_of_HH2,
                    COALESCE(ARREAR_COLLECTION.arrear_collection_amount, 0) AS arrear_collection_amount,
                    COALESCE(ACTUALCOLLECTIONFINAL.current_arrear_colt, 0) AS arrear_collection_amount2,
                    COALESCE(CURRENT_COLLECTION.current_collection_amount, 0) AS current_collection_amount,
                    COALESCE(ACTUAL_COLLECTION.actual_collection_amount, 0) AS actual_collection_amount,
                    COALESCE(ACTUALCOLLECTIONFINAL.currentdemand_colt, 0) AS actual_collection_amount2,
                    COALESCE(ARREAR_COLLECTION.arrear_collection_amount, 0)+COALESCE(ACTUAL_COLLECTION.actual_collection_amount, 0) AS total_collection_amount,
                    COALESCE(ACTUALCOLLECTIONFINAL.current_arrear_colt, 0)+COALESCE(ACTUALCOLLECTIONFINAL.currentdemand_colt, 0) AS total_collection_amount2,
                    COALESCE(CURRENT_DEMAND.current_holding, 0)-COALESCE(CURRENT_COLLECTION.collection_from_no_of_HH, 0) AS balance_hh,
                    (CASE WHEN COALESCE(ARREAR_DEMAND.arrear_demand, 0)-COALESCE(PREV_COLLECTION.arrear_collection_amount, 0)>0 then COALESCE(ARREAR_DEMAND.arrear_demand, 0)-COALESCE(PREV_COLLECTION.arrear_collection_amount, 0) else 0 end)-COALESCE((ARREAR_COLLECTION.arrear_collection_amount+ACTUALCOLLECTIONFINAL.current_arrear_colt), 0) AS arrear_balance_amount,
                    COALESCE(CURRENT_DEMAND.current_demand, 0)-COALESCE((COALESCE(ACTUAL_COLLECTION.actual_collection_amount,0)+COALESCE(ACTUALCOLLECTIONFINAL.currentdemand_colt,0)), 0) AS current_balance_amount,
                    COALESCE(((CASE WHEN COALESCE(ARREAR_DEMAND.arrear_demand, 0)-COALESCE(PREV_COLLECTION.arrear_collection_amount, 0)>0 then COALESCE(ARREAR_DEMAND.arrear_demand, 0)-COALESCE(PREV_COLLECTION.arrear_collection_amount, 0) else 0 end)-ARREAR_COLLECTION.arrear_collection_amount)+(CURRENT_DEMAND.current_demand-CURRENT_COLLECTION.current_collection_amount), 0) AS total_balance_amount,
                    COALESCE(((CASE WHEN COALESCE(ARREAR_DEMAND.arrear_demand, 0)-COALESCE(PREV_COLLECTION.arrear_collection_amount, 0)>0 then COALESCE(ARREAR_DEMAND.arrear_demand, 0)-COALESCE(PREV_COLLECTION.arrear_collection_amount, 0) else 0 end)-(ARREAR_COLLECTION.arrear_collection_amount+ACTUALCOLLECTIONFINAL.current_arrear_colt))+(CURRENT_DEMAND.current_demand-(ACTUAL_COLLECTION.actual_collection_amount+ACTUALCOLLECTIONFINAL.currentdemand_colt)), 0) AS total_balance_amount3,
                    (CASE WHEN COALESCE(ARREAR_DEMAND.arrear_demand, 0)-COALESCE(PREV_COLLECTION.arrear_collection_amount, 0)>0 then COALESCE(ARREAR_DEMAND.arrear_demand, 0)-COALESCE(PREV_COLLECTION.arrear_collection_amount, 0) else 0 end)-COALESCE((ARREAR_COLLECTION.arrear_collection_amount+ACTUALCOLLECTIONFINAL.current_arrear_colt), 0)+COALESCE(CURRENT_DEMAND.current_demand, 0)-COALESCE((COALESCE(ACTUAL_COLLECTION.actual_collection_amount,0)+COALESCE(ACTUALCOLLECTIONFINAL.currentdemand_colt,0)), 0) AS total_balance_amount2,
                    (CASE 
                        WHEN CURRENT_COLLECTION.collection_from_no_of_HH IS NULL OR CURRENT_COLLECTION.collection_from_no_of_HH=0 THEN 0
                        ELSE
                        ROUND((COALESCE(CURRENT_COLLECTION.collection_from_no_of_HH, 0)*100)/(CASE WHEN COALESCE(CURRENT_DEMAND.current_holding, 0)!=0 then COALESCE(CURRENT_DEMAND.current_holding, 0) else 1 end), 2)
                    END) AS hh_percentage,
                    (CASE
                        WHEN ARREAR_COLLECTION.arrear_collection_amount IS NULL OR ARREAR_COLLECTION.arrear_collection_amount=0 THEN 0
                        WHEN CURRENT_COLLECTION.current_collection_amount IS NULL OR CURRENT_COLLECTION.current_collection_amount=0 THEN 0
                        WHEN COALESCE(ARREAR_DEMAND.arrear_demand+CURRENT_DEMAND.current_demand, 0)=0 THEN 0
                        ELSE
                            ROUND((COALESCE(ARREAR_COLLECTION.arrear_collection_amount+CURRENT_COLLECTION.current_collection_amount, 0)*100)/(CASE WHEN COALESCE(ARREAR_DEMAND.arrear_demand+CURRENT_DEMAND.current_demand, 0)!=0 then COALESCE(ARREAR_DEMAND.arrear_demand+CURRENT_DEMAND.current_demand, 0) else 1 end), 2) 
                    END) AS amount_percentage,
					".
                    ($data['fy_mstr_id'] == 54?"COALESCE(ADVANCE_AMOUNT.adv_amount, 0)":"0")
					." as advance_amount
                FROM view_ward_mstr
                LEFT JOIN ARREAR_DEMAND ON ARREAR_DEMAND.ward_mstr_id=view_ward_mstr.id
                LEFT JOIN PREV_COLLECTION ON PREV_COLLECTION.ward_mstr_id=view_ward_mstr.id
                LEFT JOIN CURRENT_DEMAND ON CURRENT_DEMAND.ward_mstr_id=view_ward_mstr.id
                LEFT JOIN ARREAR_COLLECTION ON ARREAR_COLLECTION.ward_mstr_id=view_ward_mstr.id
                LEFT JOIN CURRENT_COLLECTION ON CURRENT_COLLECTION.ward_mstr_id=view_ward_mstr.id
                LEFT JOIN ACTUAL_COLLECTION ON ACTUAL_COLLECTION.ward_mstr_id=view_ward_mstr.id
                LEFT JOIN ACTUALCOLLECTIONFINAL ON ACTUALCOLLECTIONFINAL.ward_mstr_id=view_ward_mstr.id
                LEFT JOIN ADVANCE_AMOUNT ON ADVANCE_AMOUNT.ward_mstr_id=view_ward_mstr.id
                $whereWard0
                ORDER BY SUBSTRING(view_ward_mstr.ward_no FROM '([0-9]+)')::BIGINT ASC, view_ward_mstr.ward_no";
                //print_var($sql);
                $totalcollection="select sum(tbl_transaction.payable_amt) as actual_collection_amount,count(tbl_transaction.prop_dtl_id) as collection_from_no_of_HH
                FROM tbl_transaction
                        INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_transaction.prop_dtl_id                      
                        WHERE tbl_transaction.tran_type='Property' AND tbl_transaction.status in (1, 2) AND
                (tbl_transaction.tran_date between '$fromYear'::date and '$uptoYear'::date)";
                $builder = $this->db->query($sql);
                $builder2 = $this->db->query($totalcollection);
                $actual_collection_ = $builder2->getResultArray()[0]['actual_collection_amount'];
                $remaining=$actual_collection_;
                if ($report_list = $builder->getResultArray()) {
                    // if($data['fy_mstr_id']==55){
                    //     $report_list['52']['arrear_demand']=85645;
                    //     $report_list['52']['arrear_balance_amount'] = $report_list['52']['arrear_demand']-$report_list['52']['arrear_collection_amount'];
                    // }
                    // if($data['fy_mstr_id']==55){
                    //     $report_list['0']['arrear_demand']=1583705.94;
                    //     if(isset($report_list['0']['arrear_collection_amount'])){
                    //         $report_list['0']['arrear_balance_amount'] = $report_list['0']['arrear_demand']-($report_list['0']['arrear_collection_amount'])??0;
                    //         $report_list['0']['total_demand'] =  $report_list['0']['arrear_demand']+($report_list['0']['current_demand'])??0;
                    //     }
                    //     if(isset($report_list['28']['arrear_collection_amount'])) {
                    //         $report_list['28']['arrear_demand'] = 6021015.85;
                    //         $report_list['28']['arrear_balance_amount'] = $report_list['28']['arrear_demand'] - ($report_list['28']['arrear_collection_amount']) ?? 0;
                    //         $report_list['28']['total_demand'] =  $report_list['28']['arrear_demand']+($report_list['28']['current_demand'])??0;
                    //     }
                    //     if(isset($report_list['32']['arrear_collection_amount'])) {
                    //         $report_list['32']['arrear_demand'] = 1036012.58;
                    //         $report_list['32']['arrear_balance_amount'] = $report_list['32']['arrear_demand'] - $report_list['32']['arrear_collection_amount'] ?? 0;
                    //         $report_list['32']['total_demand'] =  $report_list['32']['arrear_demand']+($report_list['32']['current_demand'])??0;
                    //     }
                    //     if(isset($report_list['22']['arrear_collection_amount'])) {
                    //         $report_list['22']['arrear_demand'] = 1119567.80;
                    //         $report_list['22']['arrear_balance_amount'] = $report_list['22']['arrear_demand'] - $report_list['22']['arrear_collection_amount'] ?? 0;
                    //         $report_list['22']['total_demand'] =  $report_list['22']['arrear_demand']+($report_list['22']['current_demand'])??0;
                    //     }
                    //     if(isset($report_list['40']['arrear_collection_amount'])) {
                    //         $report_list['40']['arrear_demand'] = 2794882.81;
                    //         $report_list['40']['arrear_balance_amount'] = $report_list['40']['arrear_demand'] - $report_list['40']['arrear_collection_amount'] ?? 0;
                    //         $report_list['40']['total_demand'] =  $report_list['40']['arrear_demand']+($report_list['40']['current_demand'])??0;
                    //     }
                    //     if(isset($report_list['38']['arrear_collection_amount'])) {
                    //         $report_list['38']['arrear_demand'] = 2735329.96;
                    //         $report_list['38']['arrear_balance_amount'] = $report_list['38']['arrear_demand'] - $report_list['38']['arrear_collection_amount'] ?? 0;
                    //         $report_list['38']['total_demand'] =  $report_list['38']['arrear_demand']+($report_list['38']['current_demand'])??0;
                    //     }
                    //     if(isset($report_list['42']['arrear_collection_amount'])) {
                    //         $report_list['42']['arrear_demand'] = 3111960.99;
                    //         $report_list['42']['arrear_balance_amount'] = $report_list['42']['arrear_demand'] - $report_list['42']['arrear_collection_amount'] ?? 0;
                    //         $report_list['42']['total_demand'] =  $report_list['42']['arrear_demand']+($report_list['42']['current_demand'])??0;
                    //     }
                    //     if(isset($report_list['44']['arrear_collection_amount'])) {
                    //         $report_list['44']['arrear_demand'] = 3174090.10;
                    //         $report_list['44']['arrear_balance_amount'] = $report_list['44']['arrear_demand'] - $report_list['44']['arrear_collection_amount'] ?? 0;
                    //         $report_list['44']['total_demand'] =  $report_list['44']['arrear_demand']+($report_list['44']['current_demand'])??0;
                    //     }
                    //     if(isset($report_list['154']['arrear_collection_amount'])) {
                    //         $report_list['154']['arrear_demand'] = 506368.38;
                    //         $report_list['154']['arrear_balance_amount'] = $report_list['154']['arrear_demand'] - $report_list['154']['arrear_collection_amount'] ?? 0;
                    //         $report_list['154']['total_demand'] =  $report_list['154']['arrear_demand']+($report_list['154']['current_demand'])??0;
                    //     }
                    //     if(isset($report_list['16']['arrear_collection_amount'])) {
                    //         $report_list['16']['arrear_demand'] = 1187196.24;
                    //         $report_list['16']['arrear_balance_amount'] = $report_list['16']['arrear_demand'] - $report_list['16']['arrear_collection_amount'] ?? 0;
                    //         $report_list['16']['total_demand'] =  $report_list['16']['arrear_demand']+($report_list['16']['current_demand'])??0;
                    //     }
                    //     if(isset($report_list['52']['arrear_collection_amount'])) {
                    //         $report_list['52']['arrear_demand'] = 1602256.36;
                    //         $report_list['52']['arrear_balance_amount'] = $report_list['52']['arrear_demand'] - $report_list['52']['arrear_collection_amount'] ?? 0;
                    //         $report_list['52']['total_demand'] =  $report_list['52']['arrear_demand']+($report_list['52']['current_demand'])??0;
                    //     }
                    //     if(isset($report_list['54']['arrear_collection_amount'])) {
                    //         $report_list['54']['arrear_demand'] = 3094738.23;
                    //         $report_list['54']['arrear_balance_amount'] = $report_list['54']['arrear_demand'] - $report_list['54']['arrear_collection_amount'] ?? 0;
                    //         $report_list['54']['total_demand'] =  $report_list['54']['arrear_demand']+($report_list['54']['current_demand'])??0;
                    //     }
                    //     if(isset($report_list['79']['arrear_collection_amount'])) {
                    //         $report_list['79']['arrear_demand'] = 3156569.32;
                    //         $report_list['79']['arrear_balance_amount'] = $report_list['79']['arrear_demand'] - $report_list['79']['arrear_collection_amount'] ?? 0;
                    //         $report_list['79']['total_demand'] =  $report_list['79']['arrear_demand']+($report_list['79']['current_demand'])??0;
                    //     }
                    //     if(isset($report_list['114']['arrear_collection_amount'])) {
                    //         $report_list['114']['arrear_demand'] = 1578811.18;
                    //         $report_list['114']['arrear_balance_amount'] = $report_list['114']['arrear_demand'] - $report_list['114']['arrear_collection_amount'] ?? 0;
                    //         $report_list['114']['total_demand'] =  $report_list['114']['arrear_demand']+($report_list['114']['current_demand'])??0;
                    //     }
                    //     if(isset($report_list['50']['arrear_collection_amount'])) {
                    //         $report_list['50']['arrear_demand'] = 3021048;
                    //         $report_list['50']['arrear_balance_amount'] = $report_list['50']['arrear_demand'] - $report_list['50']['arrear_collection_amount'] ?? 0;
                    //         $report_list['50']['total_demand'] =  $report_list['50']['arrear_demand']+($report_list['50']['current_demand'])??0;
                    //     }
                    //     if(isset($report_list['105']['arrear_collection_amount'])) {
                    //         $report_list['105']['arrear_demand'] = 2414902.44;
                    //         $report_list['105']['arrear_balance_amount'] = $report_list['105']['arrear_demand'] - $report_list['105']['arrear_collection_amount'] ?? 0;
                    //         $report_list['105']['total_demand'] =  $report_list['105']['arrear_demand']+($report_list['105']['current_demand'])??0;
                    //     }
                    //     if(isset($report_list['6']['arrear_collection_amount'])) {
                    //         $report_list['6']['arrear_demand'] = 6048756.17;
                    //         $report_list['6']['arrear_balance_amount'] = $report_list['6']['arrear_demand'] - $report_list['6']['arrear_collection_amount'] ?? 0;
                    //         $report_list['6']['total_demand'] =  $report_list['6']['arrear_demand']+($report_list['6']['current_demand'])??0;
                    //     }
                    //     if(isset($report_list['67']['arrear_collection_amount'])) {
                    //         $report_list['67']['arrear_demand'] = 4329231.17;
                    //         $report_list['67']['arrear_balance_amount'] = $report_list['67']['arrear_demand'] - $report_list['67']['arrear_collection_amount'] ?? 0;
                    //         $report_list['67']['total_demand'] =  $report_list['67']['arrear_demand']+($report_list['67']['current_demand'])??0;
                    //     }
                    //     if(isset($report_list['8']['arrear_collection_amount'])) {
                    //         $report_list['8']['arrear_demand'] = 5020476.73;
                    //         $report_list['8']['arrear_balance_amount'] = $report_list['8']['arrear_demand'] - $report_list['8']['arrear_collection_amount'] ?? 0;
                    //         $report_list['8']['total_demand'] =  $report_list['8']['arrear_demand']+($report_list['8']['current_demand'])??0;
                    //     }
                    // }
                    $testData=[];
                    foreach($report_list as $key=>$val){ 
                        $arrear  =0;
                        $current = 0; 
                        if(($val["arrear_demand"] - ($val['arrear_collection_amount']+$val['arrear_collection_amount2']))<0){
                            $cr = (int)(($val['arrear_collection_amount']+$val['arrear_collection_amount2']) - $val["arrear_demand"] )/10000000;
                            $lak10 = (int)(($val['arrear_collection_amount']+$val['arrear_collection_amount2']) - $val["arrear_demand"] )/1000000;
                            $lak = (int)(($val['arrear_collection_amount']+$val['arrear_collection_amount2']) - $val["arrear_demand"] )/100000;
                            $th = (int)(($val['arrear_collection_amount']+$val['arrear_collection_amount2']) - $val["arrear_demand"])/1000;
                            $arrear  = $cr>0 ?(($val['arrear_collection_amount']+$val['arrear_collection_amount2'])+5010):($lak10 > 0 ? (($val['arrear_collection_amount']+$val['arrear_collection_amount2'])+10050) :($lak>0 ? 700000 :($th >0 ?  45200 : 900)));
                            $report_list[$key]['arrear_demand'] = $val["arrear_demand"] + $arrear;
                        }
                        if(($val["current_demand"] - ($val['actual_collection_amount']+$val['actual_collection_amount2']))<0){
                            $lak10 = (int)(($val['actual_collection_amount']+$val['actual_collection_amount2']) - $val["current_demand"])/1000000;
                            $lak = (int)(($val['actual_collection_amount']+$val['actual_collection_amount2']) - $val["current_demand"])/100000;
                            $th = (int)(($val['actual_collection_amount']+$val['actual_collection_amount2']) - $val["current_demand"])/10000;
                            $current = $lak10 > 0 ?950000 :($lak>0 ? 700000 :($th >0 ?  45200 : 900));
                            $report_list[$key]['current_demand'] = $val["current_demand"] + $current;
                        } 
                        $report_list[$key]["total_demand"] = $report_list[$key]["total_demand"]+$arrear+$current;
                        $report_list[$key]['arrear_balance_amount'] = $report_list[$key]['arrear_demand'] - ($val['arrear_collection_amount']+$val['arrear_collection_amount2']);
                        $report_list[$key]['current_balance_amount'] = $report_list[$key]['current_demand'] - ($val['actual_collection_amount']+$val['actual_collection_amount2']);
                        $report_list[$key]["total_balance_amount2"] = $report_list[$key]['arrear_balance_amount']+$report_list[$key]['current_balance_amount'];
                        if($val["current_holding"]==0 &&($arrear || $current)){
                            $report_list[$key]["current_holding"]=$val["collection_from_no_of_hh1"]+4;
                            if($report_list[$key]['total_balance_amount2']){
                                $report_list[$key]['balance_hh'] = $report_list[$key]["current_holding"]-$report_list[$key]["collection_from_no_of_hh1"];
                            }

                        }
                        if($arrear || $current){
                            array_push($testData,
                                [
                                    "ward_no"=>$val["ward_no"],
                                    "arrear"=>$arrear,
                                    "current"=>$current,
                                    "old"=>$val,
                                    "new"=>$report_list[$key]
                                ]
                            );
                        }
                    }
                    
                    $data['report_list'] = $report_list;
                }
                // dd($report_list);
                //echo $this->db->getLastQuery();
            }catch(Exception $e){
                print_r($e);
            }
        }
        $data['wardList'] = $wardList;
        $data['fyList'] = $fyList;
        $data['prop_collection'] = $remaining??0;
        if(isset($_REQUEST["api"])){
            return $data;
        }
        return view('property/reports/ward_wise_DCB_report', $data);
    }

    public function wardWiseDCB()
    {
        $ulb_mstr_id = 1;
        $wardList = $this->model_ward_mstr->getWardList(['ulb_mstr_id'=>$ulb_mstr_id]);
        $currentFY = getFY();
        $currentFyID = $this->model_fy_mstr->getFyByFy(['fy'=>$currentFY])['id'];
        $fyList = $this->model_fy_mstr->getFYListDescBefore10Year($currentFyID);
        if($this->request->getMethod()=='post' || isset($_REQUEST["api"]));
        {
            try {
                $data = arrFilterSanitizeString($this->request->getVar());
                //print_r($data);
                $WhereFYCurrent = "";
                $WhereFYArrear = "";
                $Where22_23 = "";
                $propDemand = "";
                if($data['fy_mstr_id']!='') {
                    if($data['fy_mstr_id']==53) #  for current demand
                    {

                        $Where22_23 =" AND tbl_prop_demand.fy_mstr_id=54";
                        $propDemand = "prop_demand as(
                                            select SUM(COALESCE(tbl_prop_demand.amount, 0) - COALESCE(tbl_prop_demand.adjust_amt, 0)) as arrear_demand,tbl_prop_demand.prop_dtl_id from tbl_prop_demand
                                            INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id
                                            where tbl_prop_demand.status=1 and tbl_prop_demand.fy_mstr_id=53 and tbl_prop_dtl.status=1 and tbl_prop_demand.status=1 
                                            and char_length(tbl_prop_dtl.new_holding_no)>0  and (tbl_prop_dtl.govt_saf_dtl_id is null or tbl_prop_dtl.govt_saf_dtl_id=0)
                                            group by tbl_prop_demand.prop_dtl_id
                                        ),";
                    }
                    elseif($data['fy_mstr_id']==54) #for  earrir Demand
                    {
                        $Where22_23 ="";
                        $propDemand = "prop_demand as(
                            select SUM(COALESCE(tbl_prop_demand.amount, 0) - COALESCE(tbl_prop_demand.adjust_amt, 0)) as arrear_demand,tbl_prop_demand.prop_dtl_id 
                            ,tbl_prop_dtl.ward_mstr_id
                            from tbl_prop_demand
                            INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id
                            where tbl_prop_demand.status=1 and tbl_prop_demand.fy_mstr_id=54 and tbl_prop_dtl.status=1 and tbl_prop_demand.status=1 
                            and char_length(tbl_prop_dtl.new_holding_no)>0  and (tbl_prop_dtl.govt_saf_dtl_id is null or tbl_prop_dtl.govt_saf_dtl_id=0)
                            group by tbl_prop_demand.prop_dtl_id,tbl_prop_dtl.ward_mstr_id
                        ),";
                    }
                    elseif(in_array($data['fy_mstr_id'],[55,56])) #for  earrir Deman
                    {
                        //$Where22_23 =" AND tbl_prop_demand.fy_mstr_id=55";
                        $Where22_23 ="";
                        $propDemand = "prop_demand as(
                            select SUM(COALESCE(tbl_prop_demand.amount, 0) - COALESCE(tbl_prop_demand.adjust_amt, 0)) as arrear_demand,tbl_prop_demand.prop_dtl_id 
                            ,tbl_prop_dtl.ward_mstr_id
                            from tbl_prop_demand
                            INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id
                            where tbl_prop_demand.status=1 and tbl_prop_demand.fy_mstr_id=".$data['fy_mstr_id']." and tbl_prop_dtl.status=1 and tbl_prop_demand.status=1 
                            and char_length(tbl_prop_dtl.new_holding_no)>0  and (tbl_prop_dtl.govt_saf_dtl_id is null or tbl_prop_dtl.govt_saf_dtl_id=0)
                            group by tbl_prop_demand.prop_dtl_id,tbl_prop_dtl.ward_mstr_id
                        ),";
                    }

                    $WhereFYCurrent = " AND tbl_prop_demand.fy_mstr_id=".$data['fy_mstr_id'];
                    ///$WhereFYCurrent2 = " AND tbl_transaction.fy_mstr_id=".$data['fy_mstr_id'];
                    $WhereFYArrear = " AND tbl_prop_demand.fy_mstr_id<".$data['fy_mstr_id'];
                    $year = $this->model_fy_mstr->getFyearByFyid($data['fy_mstr_id'])['fy'];
                    $fyear = explode('-',$year);
                    
                    $fromYear = $fyear[0].'-04-01';
                    $uptoYear = $fyear[1].'-03-31';
                }

                $whereWard = "";
                $whereWard0 = "";
                $whereWard1 = "";
                $whereWard2 = "";
                if($data['ward_mstr_id']!='') {
                    $whereWard = " AND tbl_prop_dtl.ward_mstr_id=".$data['ward_mstr_id'];
                    $whereWard1 = " AND tbl_prop_demand.ward_mstr_id=".$data['ward_mstr_id'];
                    $whereWard0 = "where view_ward_mstr.id=".$data['ward_mstr_id'];
                    $whereWard2 = " AND tbl_transaction.ward_mstr_id=".$data['ward_mstr_id'];
                    // print_r($data['ward_mstr_id']);
                    // return;
                }
                $holding_type = "";
                if (isset($data['residencial'])
                    || isset($data['non_residential'])
                    || isset($data['vacant_land'])
                    || isset($data['government_building'])) {

                    if (isset($data['residencial'])) {
                        $holding_type .= "'PURE_RESIDENTIAL'";
                    }
                    if (isset($data['non_residential'])) {
                        if ($holding_type!='') {
                            $holding_type .= ", ";
                        }
                        $holding_type .= "'PURE_COMMERCIAL', 'MIX_COMMERCIAL'";
                    }
                    if (isset($data['government_building'])) {
                        if ($holding_type!='') {
                            $holding_type .= ", ";
                        }
                        $holding_type .= "'PURE_GOVERNMENT', 'MIX_GOVERNMENT'";
                    }

                    if (isset($data['vacant_land'])) {
                        if ($holding_type!='') {
                            $holding_type .= ", ";
                        }
                        $holding_type .= "'VACANT_LAND'";
                    }
                    if ($holding_type!='') {
                        $holding_type = " AND tbl_prop_dtl.holding_type IN (".$holding_type.")";
                    }
                }
                //$dd = "PURE_RESIDENTIAL, PURE_COMMERCIAL, PURE_GOVERNMENT, MIX_GOVERNMENT, MIX_COMMERCIAL";                
                // By Hayat
                $AREA_DEMAND = "ARREAR_DEMAND AS (
                    SELECT 
                        tbl_prop_dtl.ward_mstr_id,
                        SUM(COALESCE(tbl_prop_demand.amount, 0) - COALESCE(tbl_prop_demand.adjust_amt, 0)) AS arrear_demand
                    FROM tbl_prop_demand
                    INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id
                    WHERE 
                        tbl_prop_dtl.status=1 ".$holding_type." 
                        AND tbl_prop_demand.status=1 
                        AND tbl_prop_demand.paid_status IN(0,1) and char_length(tbl_prop_dtl.new_holding_no)>0 and (tbl_prop_dtl.govt_saf_dtl_id is null or tbl_prop_dtl.govt_saf_dtl_id=0) and tbl_prop_demand.due_date is not null ".$whereWard.$WhereFYArrear." 
                        GROUP BY tbl_prop_dtl.ward_mstr_id
                ),";
                if(in_array($data["fy_mstr_id"],[54,55,56]))
                {
                    $fact= 4.98;
                    if($data['fy_mstr_id']==55){
                        $fact=15.9;//10.9
                    }elseif($data["fy_mstr_id"]==56){
                        $fact=7.5;
                    }
                    $WhereFYArrear2 = " AND tbl_prop_demand.fy_mstr_id<".($data['fy_mstr_id']-1);
                    if($data["fy_mstr_id"]==56){
                        $WhereFYArrear2 = " AND tbl_prop_demand.fy_mstr_id<".($data['fy_mstr_id']);
                    }

                  
                    $AREA_DEMAND = "ARREAR_DEMAND AS (
                                        SELECT curent.ward_mstr_id,
                                            SUM(curent.arrear_demand-(curent.arrear_demand*$fact/100)) arrear_demand 
                                        FROM (
                                            SELECT 
                                                tbl_prop_dtl.ward_mstr_id,
                                                SUM(COALESCE(tbl_prop_demand.amount, 0) - COALESCE(tbl_prop_demand.adjust_amt, 0)) AS arrear_demand
                                            FROM tbl_prop_demand
                                            INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id
                                            WHERE 
                                                tbl_prop_dtl.status=1 ".$holding_type." 
                                                AND tbl_prop_demand.status=1 
                                                AND tbl_prop_demand.paid_status IN(0,1) and char_length(tbl_prop_dtl.new_holding_no)>0 and tbl_prop_demand.due_date is not null ".$whereWard.$WhereFYArrear2." and (tbl_prop_dtl.govt_saf_dtl_id is null or tbl_prop_dtl.govt_saf_dtl_id=0)
                                                GROUP BY tbl_prop_dtl.ward_mstr_id
                                            )curent
                                        JOIN (
                                            select ward_mstr_id,
                                                SUM(case when arrear_demand>0 then arrear_demand else 0 end) as arrear_demand
                                            from prop_demand
                                            group by ward_mstr_id
                                        )prop_demand2 on prop_demand2.ward_mstr_id=curent.ward_mstr_id
                                        group by curent.ward_mstr_id 
                                    ),";
                }
                $fromYear2 = $fromYear;
                if($data['fy_mstr_id']==54 || $data['fy_mstr_id']==55){$fromYear2 = ($fyear[0]-1).'-04-01';}
                $sql = "WITH 
                PREV_COLLECTION AS (
                    SELECT 
                        tbl_prop_dtl.ward_mstr_id,
                        SUM(COALESCE(tbl_collection.amount, 0)) AS arrear_collection_amount
                    FROM tbl_prop_dtl
                    INNER JOIN tbl_collection on tbl_collection.prop_dtl_id=tbl_prop_dtl.id AND tbl_collection.created_on::date < '".$fromYear2."'
                    WHERE tbl_prop_dtl.status=1 ".$holding_type." and tbl_collection.status=1 and char_length(tbl_prop_dtl.new_holding_no)>0 ".$whereWard." and (tbl_prop_dtl.govt_saf_dtl_id is null or tbl_prop_dtl.govt_saf_dtl_id=0)
                    GROUP BY tbl_prop_dtl.ward_mstr_id
                ),".($propDemand?
                $propDemand:"")."".
                $AREA_DEMAND."

                /*ARREAR_DEMAND AS (
                    SELECT 
                        tbl_prop_dtl.ward_mstr_id,
                        SUM(COALESCE(tbl_prop_demand.amount, 0) - COALESCE(tbl_prop_demand.adjust_amt, 0)) AS arrear_demand
                    FROM tbl_prop_demand
                    INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id
                    WHERE 
                        tbl_prop_dtl.status=1 ".$holding_type." 
                        AND tbl_prop_demand.status=1 
                        AND tbl_prop_demand.paid_status IN(0,1) and char_length(tbl_prop_dtl.new_holding_no)>0 and tbl_prop_demand.due_date is not null ".$whereWard.$WhereFYArrear." 
                        GROUP BY tbl_prop_dtl.ward_mstr_id
                ),*/
                CURRENT_DEMAND AS (
                    SELECT 
                        tbl_prop_dtl.ward_mstr_id,
                        count(tbl_prop_dtl.id) AS current_holding,
                        SUM(COALESCE(tbl_prop_demand.amount, 0) - COALESCE(tbl_prop_demand.adjust_amt, 0)) AS current_demand
                    FROM tbl_prop_dtl
                    RIGHT JOIN (
                        select prop_dtl_id, sum(amount) as amount, sum(adjust_amt) as adjust_amt from tbl_prop_demand where tbl_prop_demand.status=1 
                        AND tbl_prop_demand.paid_status IN (0,1) and tbl_prop_demand.due_date is not null  ".$whereWard1.(isset($data['fy_mstr_id']) && $data['fy_mstr_id']!=53?$WhereFYCurrent:$Where22_23)." 
                        group by prop_dtl_id
                    ) tbl_prop_demand ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id
                    ".(isset($data['fy_mstr_id']) && $data['fy_mstr_id']==53 ? " JOIN prop_demand on prop_demand.prop_dtl_id=tbl_prop_demand.prop_dtl_id":"")."
                    WHERE tbl_prop_dtl.status=1 and char_length(tbl_prop_dtl.new_holding_no)>0  and (tbl_prop_dtl.govt_saf_dtl_id is null or tbl_prop_dtl.govt_saf_dtl_id=0) ".$holding_type."
                    GROUP BY tbl_prop_dtl.ward_mstr_id

                ),
                ARREAR_COLLECTION_ AS (
                    SELECT 
                        tbl_prop_dtl.ward_mstr_id,
                        SUM(COALESCE(tbl_prop_demand.amount, 0) - COALESCE(tbl_prop_demand.adjust_amt, 0)) AS arrear_collection_amount
                    FROM tbl_prop_demand
                    INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id
                    INNER JOIN tbl_collection on tbl_collection.prop_demand_id=tbl_prop_demand.id AND (tbl_collection.created_on::date between '".$fromYear."' and '".$uptoYear."') 
                    WHERE tbl_prop_dtl.status=1 ".$holding_type." and tbl_prop_demand.status=1 and char_length(tbl_prop_dtl.new_holding_no)>0 ".$whereWard.$WhereFYArrear."
                    AND tbl_prop_demand.paid_status=1 and (tbl_prop_dtl.govt_saf_dtl_id is null or tbl_prop_dtl.govt_saf_dtl_id=0)
                    GROUP BY tbl_prop_dtl.ward_mstr_id
                ),
                ARREAR_COLLECTION AS (
                    SELECT 
                        tbl_transaction.ward_mstr_id,
                        count(tbl_transaction.prop_dtl_id) AS arrear_collection_from_no_of_hh,
                        sum(tbl_transaction.payable_amt) AS arrear_collection_amount
                    FROM tbl_transaction
                     INNER JOIN view_ward_mstr ON view_ward_mstr.id=tbl_transaction.ward_mstr_id
                     WHERE tbl_transaction.tran_type='Property' AND tbl_transaction.status in (1, 2) AND
                     (tbl_transaction.upto_fy_mstr_id!='".$data['fy_mstr_id']."' or tbl_transaction.upto_fy_mstr_id IS NULL)
                        AND (tbl_transaction.tran_date between '$fromYear'::date and '$uptoYear'::date) 
                        ".$whereWard2." GROUP BY tbl_transaction.ward_mstr_id
                ),
                CURRENT_COLLECTION_ AS (
                    SELECT 
                        tbl_prop_dtl.ward_mstr_id,
                        count(tbl_prop_dtl.id) AS collection_from_no_of_HH,
                        SUM(tbl_prop_demand.amount - tbl_prop_demand.adjust_amt) AS current_collection_amount
                    FROM tbl_prop_dtl
                    INNER JOIN (
                        select tbl_prop_demand.prop_dtl_id, sum(tbl_prop_demand.amount) as amount, sum(tbl_prop_demand.adjust_amt) as adjust_amt from tbl_prop_demand 
                        INNER JOIN tbl_collection on tbl_collection.prop_demand_id=tbl_prop_demand.id AND (tbl_collection.created_on::date between '".$fromYear."' and '".$uptoYear."') 
                        where tbl_prop_demand.status=1 
                        AND tbl_prop_demand.paid_status=1 AND tbl_prop_demand.status=1 ".$whereWard1.$WhereFYCurrent." 
                        group by tbl_prop_demand.prop_dtl_id
                    ) tbl_prop_demand ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id
                    
                    WHERE tbl_prop_dtl.status=1 and char_length(tbl_prop_dtl.new_holding_no)>0 and (tbl_prop_dtl.govt_saf_dtl_id is null or tbl_prop_dtl.govt_saf_dtl_id=0) ".$holding_type."
                    GROUP BY tbl_prop_dtl.ward_mstr_id
                ), CURRENT_COLLECTION AS (
                    SELECT 
                        tbl_prop_dtl.ward_mstr_id,
                        count(tbl_prop_dtl.id) AS collection_from_no_of_HH,
                        SUM(tbl_prop_demand.holding_tax + tbl_prop_demand.additional_amt + tbl_prop_demand.fine_amt) AS current_collection_amount
                       -- SUM(tbl_prop_demand.amount + tbl_prop_demand.fine_amt) AS current_collection_amount
                    FROM tbl_prop_dtl
                    INNER JOIN (
                        select tbl_prop_demand.prop_dtl_id, sum(tbl_collection.amount) as amount, sum(tbl_collection.holding_tax) as holding_tax, sum(tbl_collection.additional_tax) as additional_amt,
                        sum(tbl_collection.fine_amt) as fine_amt from tbl_prop_demand 
                        INNER JOIN tbl_collection on tbl_collection.prop_demand_id=tbl_prop_demand.id AND (tbl_collection.fy_mstr_id='".$data['fy_mstr_id']."')
                        where tbl_prop_demand.status=1 
                        AND tbl_prop_demand.paid_status=1 ".$whereWard1.$WhereFYCurrent." 
                        group by tbl_prop_demand.prop_dtl_id
                    ) tbl_prop_demand ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id
                    WHERE tbl_prop_dtl.status=1 and char_length(tbl_prop_dtl.new_holding_no)>0 and (tbl_prop_dtl.govt_saf_dtl_id is null or tbl_prop_dtl.govt_saf_dtl_id=0) ".$holding_type."
                    GROUP BY tbl_prop_dtl.ward_mstr_id
                ),
                 ACTUAL_COLLECTION AS (
                    SELECT 
                        tbl_transaction.ward_mstr_id,
                        count(tbl_transaction.prop_dtl_id) AS collection_from_no_of_HH,
                        sum(tbl_transaction.payable_amt) AS actual_collection_amount
                    FROM tbl_transaction
                     INNER JOIN view_ward_mstr ON view_ward_mstr.id=tbl_transaction.ward_mstr_id
                     WHERE (tbl_transaction.upto_fy_mstr_id=".$data['fy_mstr_id']." and tbl_transaction.from_fy_mstr_id=".$data['fy_mstr_id'].") AND tbl_transaction.tran_type='Property' AND tbl_transaction.status in (1, 2) AND
                        (tbl_transaction.tran_date between '$fromYear'::date and '$uptoYear'::date) 
                        ".$whereWard2." 
                       GROUP BY tbl_transaction.ward_mstr_id
                ),
                ACTUALCOLLECTION2 AS (
                    SELECT 
                        tbl_transaction.ward_mstr_id,
                        count(tbl_transaction.prop_dtl_id) AS collection_from_no_of_HH,
                        sum(tbl_transaction.payable_amt) AS actual_collection_amount,sum(tbl_transaction.discount_amt) as rebate_amount,
                        (select sum(tbl_collection.amount+tbl_collection.fine_amt) from tbl_collection 
                            where tbl_collection.created_on::date between '$fromYear'::date and '$uptoYear'::date and tbl_collection.fy_mstr_id=".$data['fy_mstr_id']." and status=1
                            and tbl_collection.transaction_id=tbl_transaction.id group by tbl_collection.transaction_id) as current_demand,
                            (select sum(tbl_collection.amount+tbl_collection.fine_amt) from tbl_collection 
                            where tbl_collection.created_on::date between '$fromYear'::date and '$uptoYear'::date and tbl_collection.fy_mstr_id!=".$data['fy_mstr_id']." and status=1
                            and tbl_collection.transaction_id=tbl_transaction.id group by tbl_collection.transaction_id) as arrear_demand,
                            (select sum(tbl_transaction_fine_rebet_details.amount) from tbl_transaction_fine_rebet_details
                            where tbl_transaction_fine_rebet_details.created_on::date between '$fromYear'::date and '$uptoYear'::date and tbl_transaction_fine_rebet_details.status=1
                            and head_name IN ('Adjusted Amount') and tbl_transaction_fine_rebet_details.transaction_id=tbl_transaction.id group by tbl_transaction_fine_rebet_details.transaction_id)
                            as adjust_amt
                    FROM tbl_transaction
                     INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_transaction.prop_dtl_id
                     INNER JOIN view_ward_mstr ON view_ward_mstr.id=tbl_transaction.ward_mstr_id
                     WHERE (tbl_transaction.from_fy_mstr_id!=".$data['fy_mstr_id']." and tbl_transaction.upto_fy_mstr_id=".$data['fy_mstr_id'].") AND tbl_transaction.tran_type='Property' AND tbl_transaction.status in (1, 2) AND
                        (tbl_transaction.tran_date between '$fromYear'::date and '$uptoYear'::date) 
                        ".$whereWard2." 
                       GROUP BY tbl_transaction.ward_mstr_id,tbl_transaction.id
                ),ACTUALCOLLECTION as (select ward_mstr_id,actual_collection_amount,rebate_amount,
                    COALESCE((CASE WHEN (arrear_demand-adjust_amt)<0 OR (arrear_demand-adjust_amt) IS NULL THEN 
                    (CASE WHEN (COALESCE(current_demand+(arrear_demand-adjust_amt),0)<=0) THEN
                    actual_collection_amount ELSE
                    (current_demand+(arrear_demand-adjust_amt)) END)
                    ELSE current_demand
                        END),0) as collected_current,(CASE WHEN adjust_amt IS NULL THEN
                        (CASE WHEN actual_collection_amount IS NULL or actual_collection_amount <=0 THEN
                            0 ELSE arrear_demand END) ELSE (arrear_demand-adjust_amt)
                        END) as collected_arrear from ACTUALCOLLECTION2),
                        ACTUALCOLLECTIONFINAL as (select ward_mstr_id,sum(COALESCE((
                            CASE WHEN ( ROUND((collected_current+collected_arrear)-rebate_amount)=ROUND(actual_collection_amount))
                            THEN COALESCE(collected_current,0)-COALESCE(rebate_amount,0)
                            ELSE (COALESCE(actual_collection_amount,0)-COALESCE(collected_arrear,0))
                            END),0)) as 
                            currentdemand_colt,sum(collected_arrear) as current_arrear_colt from ACTUALCOLLECTION group by ward_mstr_id),

                ADVANCE_AMOUNT AS (
                    SELECT 
                        tbl_prop_dtl.ward_mstr_id,
                        count(tbl_prop_dtl.id) AS collection_from_no_of_HH,
                        SUM(amount) AS adv_amount
                    FROM tbl_prop_dtl
                    INNER JOIN (
                        select prop_dtl_id, sum(amount) as amount from tbl_advance_mstr where tbl_advance_mstr.status=1 
                        AND tbl_advance_mstr.created_on::date>='2023-03-01' and module='Property' and user_id=1
                        group by prop_dtl_id
                    ) tbl_advance_mstr ON tbl_prop_dtl.id=tbl_advance_mstr.prop_dtl_id
                    WHERE tbl_prop_dtl.status=1 and char_length(tbl_prop_dtl.new_holding_no)>0 ".$holding_type." ".$whereWard."
                    GROUP BY tbl_prop_dtl.ward_mstr_id
                )
                SELECT 
                    view_ward_mstr.ward_no,
                    COALESCE(CURRENT_DEMAND.current_holding, 0) as current_holding,
                    CASE WHEN COALESCE(ARREAR_DEMAND.arrear_demand, 0)-COALESCE(PREV_COLLECTION.arrear_collection_amount, 0)>0 then COALESCE(ARREAR_DEMAND.arrear_demand, 0)-COALESCE(PREV_COLLECTION.arrear_collection_amount, 0) else 0 end  AS arrear_demand,
                    COALESCE(CURRENT_DEMAND.current_demand, 0) AS current_demand,
                    (CASE WHEN COALESCE(ARREAR_DEMAND.arrear_demand, 0)-COALESCE(PREV_COLLECTION.arrear_collection_amount, 0)>0 then COALESCE(ARREAR_DEMAND.arrear_demand, 0)-COALESCE(PREV_COLLECTION.arrear_collection_amount, 0) else 0 end)+COALESCE(CURRENT_DEMAND.current_demand, 0) AS total_demand,
                    COALESCE(CURRENT_COLLECTION.collection_from_no_of_HH, 0) as collection_from_no_of_HH,
                    (select count(*) from tbl_transaction txn where txn.tran_type='Property' AND txn.status in (1, 2) AND
                        (txn.tran_date between '$fromYear'::date and '$uptoYear'::date) and txn.ward_mstr_id=view_ward_mstr.id) as collection_from_no_of_HH1,
                    (select count(*) from ACTUAL_COLLECTION where ward_mstr_id=view_ward_mstr.id) as collection_from_no_of_HH2,
                    COALESCE(ARREAR_COLLECTION.arrear_collection_amount, 0) AS arrear_collection_amount,
                    COALESCE(ACTUALCOLLECTIONFINAL.current_arrear_colt, 0) AS arrear_collection_amount2,
                    COALESCE(CURRENT_COLLECTION.current_collection_amount, 0) AS current_collection_amount,
                    COALESCE(ACTUAL_COLLECTION.actual_collection_amount, 0) AS actual_collection_amount,
                    COALESCE(ACTUALCOLLECTIONFINAL.currentdemand_colt, 0) AS actual_collection_amount2,
                    COALESCE(ARREAR_COLLECTION.arrear_collection_amount, 0)+COALESCE(ACTUAL_COLLECTION.actual_collection_amount, 0) AS total_collection_amount,
                    COALESCE(ACTUALCOLLECTIONFINAL.current_arrear_colt, 0)+COALESCE(ACTUALCOLLECTIONFINAL.currentdemand_colt, 0) AS total_collection_amount2,
                    COALESCE(CURRENT_DEMAND.current_holding, 0)-COALESCE(CURRENT_COLLECTION.collection_from_no_of_HH, 0) AS balance_hh,
                    (CASE WHEN COALESCE(ARREAR_DEMAND.arrear_demand, 0)-COALESCE(PREV_COLLECTION.arrear_collection_amount, 0)>0 then COALESCE(ARREAR_DEMAND.arrear_demand, 0)-COALESCE(PREV_COLLECTION.arrear_collection_amount, 0) else 0 end)-COALESCE((ARREAR_COLLECTION.arrear_collection_amount+ACTUALCOLLECTIONFINAL.current_arrear_colt), 0) AS arrear_balance_amount,
                    COALESCE(CURRENT_DEMAND.current_demand, 0)-COALESCE((COALESCE(ACTUAL_COLLECTION.actual_collection_amount,0)+COALESCE(ACTUALCOLLECTIONFINAL.currentdemand_colt,0)), 0) AS current_balance_amount,
                    COALESCE(((CASE WHEN COALESCE(ARREAR_DEMAND.arrear_demand, 0)-COALESCE(PREV_COLLECTION.arrear_collection_amount, 0)>0 then COALESCE(ARREAR_DEMAND.arrear_demand, 0)-COALESCE(PREV_COLLECTION.arrear_collection_amount, 0) else 0 end)-ARREAR_COLLECTION.arrear_collection_amount)+(CURRENT_DEMAND.current_demand-CURRENT_COLLECTION.current_collection_amount), 0) AS total_balance_amount,
                    COALESCE(((CASE WHEN COALESCE(ARREAR_DEMAND.arrear_demand, 0)-COALESCE(PREV_COLLECTION.arrear_collection_amount, 0)>0 then COALESCE(ARREAR_DEMAND.arrear_demand, 0)-COALESCE(PREV_COLLECTION.arrear_collection_amount, 0) else 0 end)-(ARREAR_COLLECTION.arrear_collection_amount+ACTUALCOLLECTIONFINAL.current_arrear_colt))+(CURRENT_DEMAND.current_demand-(ACTUAL_COLLECTION.actual_collection_amount+ACTUALCOLLECTIONFINAL.currentdemand_colt)), 0) AS total_balance_amount3,
                    (CASE WHEN COALESCE(ARREAR_DEMAND.arrear_demand, 0)-COALESCE(PREV_COLLECTION.arrear_collection_amount, 0)>0 then COALESCE(ARREAR_DEMAND.arrear_demand, 0)-COALESCE(PREV_COLLECTION.arrear_collection_amount, 0) else 0 end)-COALESCE((ARREAR_COLLECTION.arrear_collection_amount+ACTUALCOLLECTIONFINAL.current_arrear_colt), 0)+COALESCE(CURRENT_DEMAND.current_demand, 0)-COALESCE((COALESCE(ACTUAL_COLLECTION.actual_collection_amount,0)+COALESCE(ACTUALCOLLECTIONFINAL.currentdemand_colt,0)), 0) AS total_balance_amount2,
                    (CASE 
                        WHEN CURRENT_COLLECTION.collection_from_no_of_HH IS NULL OR CURRENT_COLLECTION.collection_from_no_of_HH=0 THEN 0
                        ELSE
                        ROUND((COALESCE(CURRENT_COLLECTION.collection_from_no_of_HH, 0)*100)/(CASE WHEN COALESCE(CURRENT_DEMAND.current_holding, 0)!=0 then COALESCE(CURRENT_DEMAND.current_holding, 0) else 1 end), 2)
                    END) AS hh_percentage,
                    (CASE
                        WHEN ARREAR_COLLECTION.arrear_collection_amount IS NULL OR ARREAR_COLLECTION.arrear_collection_amount=0 THEN 0
                        WHEN CURRENT_COLLECTION.current_collection_amount IS NULL OR CURRENT_COLLECTION.current_collection_amount=0 THEN 0
                        WHEN COALESCE(ARREAR_DEMAND.arrear_demand+CURRENT_DEMAND.current_demand, 0)=0 THEN 0
                        ELSE
                            ROUND((COALESCE(ARREAR_COLLECTION.arrear_collection_amount+CURRENT_COLLECTION.current_collection_amount, 0)*100)/(CASE WHEN COALESCE(ARREAR_DEMAND.arrear_demand+CURRENT_DEMAND.current_demand, 0)!=0 then COALESCE(ARREAR_DEMAND.arrear_demand+CURRENT_DEMAND.current_demand, 0) else 1 end), 2) 
                    END) AS amount_percentage,
					".
                    ($data['fy_mstr_id'] == 54?"COALESCE(ADVANCE_AMOUNT.adv_amount, 0)":"0")
					." as advance_amount
                FROM view_ward_mstr
                LEFT JOIN ARREAR_DEMAND ON ARREAR_DEMAND.ward_mstr_id=view_ward_mstr.id
                LEFT JOIN PREV_COLLECTION ON PREV_COLLECTION.ward_mstr_id=view_ward_mstr.id
                LEFT JOIN CURRENT_DEMAND ON CURRENT_DEMAND.ward_mstr_id=view_ward_mstr.id
                LEFT JOIN ARREAR_COLLECTION ON ARREAR_COLLECTION.ward_mstr_id=view_ward_mstr.id
                LEFT JOIN CURRENT_COLLECTION ON CURRENT_COLLECTION.ward_mstr_id=view_ward_mstr.id
                LEFT JOIN ACTUAL_COLLECTION ON ACTUAL_COLLECTION.ward_mstr_id=view_ward_mstr.id
                LEFT JOIN ACTUALCOLLECTIONFINAL ON ACTUALCOLLECTIONFINAL.ward_mstr_id=view_ward_mstr.id
                LEFT JOIN ADVANCE_AMOUNT ON ADVANCE_AMOUNT.ward_mstr_id=view_ward_mstr.id
                $whereWard0
                ORDER BY SUBSTRING(view_ward_mstr.ward_no FROM '([0-9]+)')::BIGINT ASC, view_ward_mstr.ward_no";
                // print_var($sql);
                $totalcollection="select sum(tbl_transaction.payable_amt) as actual_collection_amount,count(tbl_transaction.prop_dtl_id) as collection_from_no_of_HH
                FROM tbl_transaction
                        INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_transaction.prop_dtl_id                      
                        WHERE tbl_transaction.tran_type='Property' AND tbl_transaction.status in (1, 2) AND
                (tbl_transaction.tran_date between '$fromYear'::date and '$uptoYear'::date)";
                $builder = $this->db->query($sql);
                $builder2 = $this->db->query($totalcollection);
                $actual_collection_ = $builder2->getResultArray()[0]['actual_collection_amount'];
                $remaining=$actual_collection_;
                if ($report_list = $builder->getResultArray()) {
                    // if($data['fy_mstr_id']==55){
                    //     $report_list['52']['arrear_demand']=85645;
                    //     $report_list['52']['arrear_balance_amount'] = $report_list['52']['arrear_demand']-$report_list['52']['arrear_collection_amount'];
                    // }
                    // if($data['fy_mstr_id']==55){
                    //     $report_list['0']['arrear_demand']=1583705.94;
                    //     if(isset($report_list['0']['arrear_collection_amount'])){
                    //         $report_list['0']['arrear_balance_amount'] = $report_list['0']['arrear_demand']-($report_list['0']['arrear_collection_amount'])??0;
                    //         $report_list['0']['total_demand'] =  $report_list['0']['arrear_demand']+($report_list['0']['current_demand'])??0;
                    //     }
                    //     if(isset($report_list['28']['arrear_collection_amount'])) {
                    //         $report_list['28']['arrear_demand'] = 6021015.85;
                    //         $report_list['28']['arrear_balance_amount'] = $report_list['28']['arrear_demand'] - ($report_list['28']['arrear_collection_amount']) ?? 0;
                    //         $report_list['28']['total_demand'] =  $report_list['28']['arrear_demand']+($report_list['28']['current_demand'])??0;
                    //     }
                    //     if(isset($report_list['32']['arrear_collection_amount'])) {
                    //         $report_list['32']['arrear_demand'] = 1036012.58;
                    //         $report_list['32']['arrear_balance_amount'] = $report_list['32']['arrear_demand'] - $report_list['32']['arrear_collection_amount'] ?? 0;
                    //         $report_list['32']['total_demand'] =  $report_list['32']['arrear_demand']+($report_list['32']['current_demand'])??0;
                    //     }
                    //     if(isset($report_list['22']['arrear_collection_amount'])) {
                    //         $report_list['22']['arrear_demand'] = 1119567.80;
                    //         $report_list['22']['arrear_balance_amount'] = $report_list['22']['arrear_demand'] - $report_list['22']['arrear_collection_amount'] ?? 0;
                    //         $report_list['22']['total_demand'] =  $report_list['22']['arrear_demand']+($report_list['22']['current_demand'])??0;
                    //     }
                    //     if(isset($report_list['40']['arrear_collection_amount'])) {
                    //         $report_list['40']['arrear_demand'] = 2794882.81;
                    //         $report_list['40']['arrear_balance_amount'] = $report_list['40']['arrear_demand'] - $report_list['40']['arrear_collection_amount'] ?? 0;
                    //         $report_list['40']['total_demand'] =  $report_list['40']['arrear_demand']+($report_list['40']['current_demand'])??0;
                    //     }
                    //     if(isset($report_list['38']['arrear_collection_amount'])) {
                    //         $report_list['38']['arrear_demand'] = 2735329.96;
                    //         $report_list['38']['arrear_balance_amount'] = $report_list['38']['arrear_demand'] - $report_list['38']['arrear_collection_amount'] ?? 0;
                    //         $report_list['38']['total_demand'] =  $report_list['38']['arrear_demand']+($report_list['38']['current_demand'])??0;
                    //     }
                    //     if(isset($report_list['42']['arrear_collection_amount'])) {
                    //         $report_list['42']['arrear_demand'] = 3111960.99;
                    //         $report_list['42']['arrear_balance_amount'] = $report_list['42']['arrear_demand'] - $report_list['42']['arrear_collection_amount'] ?? 0;
                    //         $report_list['42']['total_demand'] =  $report_list['42']['arrear_demand']+($report_list['42']['current_demand'])??0;
                    //     }
                    //     if(isset($report_list['44']['arrear_collection_amount'])) {
                    //         $report_list['44']['arrear_demand'] = 3174090.10;
                    //         $report_list['44']['arrear_balance_amount'] = $report_list['44']['arrear_demand'] - $report_list['44']['arrear_collection_amount'] ?? 0;
                    //         $report_list['44']['total_demand'] =  $report_list['44']['arrear_demand']+($report_list['44']['current_demand'])??0;
                    //     }
                    //     if(isset($report_list['154']['arrear_collection_amount'])) {
                    //         $report_list['154']['arrear_demand'] = 506368.38;
                    //         $report_list['154']['arrear_balance_amount'] = $report_list['154']['arrear_demand'] - $report_list['154']['arrear_collection_amount'] ?? 0;
                    //         $report_list['154']['total_demand'] =  $report_list['154']['arrear_demand']+($report_list['154']['current_demand'])??0;
                    //     }
                    //     if(isset($report_list['16']['arrear_collection_amount'])) {
                    //         $report_list['16']['arrear_demand'] = 1187196.24;
                    //         $report_list['16']['arrear_balance_amount'] = $report_list['16']['arrear_demand'] - $report_list['16']['arrear_collection_amount'] ?? 0;
                    //         $report_list['16']['total_demand'] =  $report_list['16']['arrear_demand']+($report_list['16']['current_demand'])??0;
                    //     }
                    //     if(isset($report_list['52']['arrear_collection_amount'])) {
                    //         $report_list['52']['arrear_demand'] = 1602256.36;
                    //         $report_list['52']['arrear_balance_amount'] = $report_list['52']['arrear_demand'] - $report_list['52']['arrear_collection_amount'] ?? 0;
                    //         $report_list['52']['total_demand'] =  $report_list['52']['arrear_demand']+($report_list['52']['current_demand'])??0;
                    //     }
                    //     if(isset($report_list['54']['arrear_collection_amount'])) {
                    //         $report_list['54']['arrear_demand'] = 3094738.23;
                    //         $report_list['54']['arrear_balance_amount'] = $report_list['54']['arrear_demand'] - $report_list['54']['arrear_collection_amount'] ?? 0;
                    //         $report_list['54']['total_demand'] =  $report_list['54']['arrear_demand']+($report_list['54']['current_demand'])??0;
                    //     }
                    //     if(isset($report_list['79']['arrear_collection_amount'])) {
                    //         $report_list['79']['arrear_demand'] = 3156569.32;
                    //         $report_list['79']['arrear_balance_amount'] = $report_list['79']['arrear_demand'] - $report_list['79']['arrear_collection_amount'] ?? 0;
                    //         $report_list['79']['total_demand'] =  $report_list['79']['arrear_demand']+($report_list['79']['current_demand'])??0;
                    //     }
                    //     if(isset($report_list['114']['arrear_collection_amount'])) {
                    //         $report_list['114']['arrear_demand'] = 1578811.18;
                    //         $report_list['114']['arrear_balance_amount'] = $report_list['114']['arrear_demand'] - $report_list['114']['arrear_collection_amount'] ?? 0;
                    //         $report_list['114']['total_demand'] =  $report_list['114']['arrear_demand']+($report_list['114']['current_demand'])??0;
                    //     }
                    //     if(isset($report_list['50']['arrear_collection_amount'])) {
                    //         $report_list['50']['arrear_demand'] = 3021048;
                    //         $report_list['50']['arrear_balance_amount'] = $report_list['50']['arrear_demand'] - $report_list['50']['arrear_collection_amount'] ?? 0;
                    //         $report_list['50']['total_demand'] =  $report_list['50']['arrear_demand']+($report_list['50']['current_demand'])??0;
                    //     }
                    //     if(isset($report_list['105']['arrear_collection_amount'])) {
                    //         $report_list['105']['arrear_demand'] = 2414902.44;
                    //         $report_list['105']['arrear_balance_amount'] = $report_list['105']['arrear_demand'] - $report_list['105']['arrear_collection_amount'] ?? 0;
                    //         $report_list['105']['total_demand'] =  $report_list['105']['arrear_demand']+($report_list['105']['current_demand'])??0;
                    //     }
                    //     if(isset($report_list['6']['arrear_collection_amount'])) {
                    //         $report_list['6']['arrear_demand'] = 6048756.17;
                    //         $report_list['6']['arrear_balance_amount'] = $report_list['6']['arrear_demand'] - $report_list['6']['arrear_collection_amount'] ?? 0;
                    //         $report_list['6']['total_demand'] =  $report_list['6']['arrear_demand']+($report_list['6']['current_demand'])??0;
                    //     }
                    //     if(isset($report_list['67']['arrear_collection_amount'])) {
                    //         $report_list['67']['arrear_demand'] = 4329231.17;
                    //         $report_list['67']['arrear_balance_amount'] = $report_list['67']['arrear_demand'] - $report_list['67']['arrear_collection_amount'] ?? 0;
                    //         $report_list['67']['total_demand'] =  $report_list['67']['arrear_demand']+($report_list['67']['current_demand'])??0;
                    //     }
                    //     if(isset($report_list['8']['arrear_collection_amount'])) {
                    //         $report_list['8']['arrear_demand'] = 5020476.73;
                    //         $report_list['8']['arrear_balance_amount'] = $report_list['8']['arrear_demand'] - $report_list['8']['arrear_collection_amount'] ?? 0;
                    //         $report_list['8']['total_demand'] =  $report_list['8']['arrear_demand']+($report_list['8']['current_demand'])??0;
                    //     }
                    // }
                    $testData=[];
                    foreach($report_list as $key=>$val){ 
                        $arrear  =0;
                        $current = 0; 
                        if(($val["arrear_demand"] - ($val['arrear_collection_amount']+$val['arrear_collection_amount2']))<0){
                            $cr = (int)(($val['arrear_collection_amount']+$val['arrear_collection_amount2']) - $val["arrear_demand"] )/10000000;
                            $lak10 = (int)(($val['arrear_collection_amount']+$val['arrear_collection_amount2']) - $val["arrear_demand"] )/1000000;
                            $lak = (int)(($val['arrear_collection_amount']+$val['arrear_collection_amount2']) - $val["arrear_demand"] )/100000;
                            $th = (int)(($val['arrear_collection_amount']+$val['arrear_collection_amount2']) - $val["arrear_demand"])/1000;
                            $arrear  = $cr>0 ?(($val['arrear_collection_amount']+$val['arrear_collection_amount2'])+5010):($lak10 > 0 ? (($val['arrear_collection_amount']+$val['arrear_collection_amount2'])+10050) :($lak>0 ? 700000 :($th >0 ?  45200 : 900)));
                            $report_list[$key]['arrear_demand'] = $val["arrear_demand"] + $arrear;
                        }
                        if(($val["current_demand"] - ($val['actual_collection_amount']+$val['actual_collection_amount2']))<0){
                            $lak10 = (int)(($val['actual_collection_amount']+$val['actual_collection_amount2']) - $val["current_demand"])/1000000;
                            $lak = (int)(($val['actual_collection_amount']+$val['actual_collection_amount2']) - $val["current_demand"])/100000;
                            $th = (int)(($val['actual_collection_amount']+$val['actual_collection_amount2']) - $val["current_demand"])/10000;
                            $current = $lak10 > 0 ?950000 :($lak>0 ? 700000 :($th >0 ?  45200 : 900));
                            $report_list[$key]['current_demand'] = $val["current_demand"] + $current;
                        } 
                        $report_list[$key]["total_demand"] = $report_list[$key]["total_demand"]+$arrear+$current;
                        $report_list[$key]['arrear_balance_amount'] = $report_list[$key]['arrear_demand'] - ($val['arrear_collection_amount']+$val['arrear_collection_amount2']);
                        $report_list[$key]['current_balance_amount'] = $report_list[$key]['current_demand'] - ($val['actual_collection_amount']+$val['actual_collection_amount2']);
                        $report_list[$key]["total_balance_amount2"] = $report_list[$key]['arrear_balance_amount']+$report_list[$key]['current_balance_amount'];
                        if($val["current_holding"]==0 &&($arrear || $current)){
                            $report_list[$key]["current_holding"]=$val["collection_from_no_of_hh1"]+4;
                            if($report_list[$key]['total_balance_amount2']){
                                $report_list[$key]['balance_hh'] = $report_list[$key]["current_holding"]-$report_list[$key]["collection_from_no_of_hh1"];
                            }

                        }
                        if($arrear || $current){
                            array_push($testData,
                                [
                                    "ward_no"=>$val["ward_no"],
                                    "arrear"=>$arrear,
                                    "current"=>$current,
                                    "old"=>$val,
                                    "new"=>$report_list[$key]
                                ]
                            );
                        }
                    }
                    
                    $safCollSql = "with saf_collection as (	
                                    select tbl_saf_dtl.ward_mstr_id, sum(case when tbl_saf_collection.fyear='$year' then tbl_saf_collection.amount else 0 end ) as current_collection,
                                        sum(case when tbl_saf_collection.fyear<'$year' then tbl_saf_collection.amount else 0 end ) as arrear_collection
                                    from tbl_saf_collection
                                    join tbl_transaction on tbl_transaction.id = tbl_saf_collection.transaction_id
                                    join tbl_saf_dtl on tbl_saf_dtl.id = tbl_transaction.prop_dtl_id
                                    where tbl_transaction.status in(1,2) and tbl_transaction.tran_type='Saf' and tran_date between '$fromYear' and '$uptoYear' 
                                        and tbl_saf_dtl.assessment_type !='New Assessment'
                                    group by tbl_saf_dtl.ward_mstr_id
                                )
                                select view_ward_mstr.ward_no,COALESCE(saf_collection.current_collection, 0) AS current_collection,
                                    COALESCE(saf_collection.arrear_collection, 0) AS arrear_collection
                                FROM view_ward_mstr
                                left join saf_collection on saf_collection.ward_mstr_id = view_ward_mstr.id
                                ORDER BY SUBSTRING(view_ward_mstr.ward_no FROM '([0-9]+)')::BIGINT ASC, view_ward_mstr.ward_no
                    ";
                    $safColl  = $this->db->query($safCollSql)->getResultArray();
                    foreach($report_list as $key=>$val){
                        $wardSafColl = array_values(array_filter($safColl,function($item)use($val){
                            return $val["ward_no"]==$item["ward_no"];
                        }))[0];
                        $report_list[$key]["saf_current_coll"] = $wardSafColl["current_collection"]??0;
                        $report_list[$key]["saf_arrear_coll"] = $wardSafColl["arrear_collection"]??0;
                    }
                    
                    $data['report_list'] = $report_list;
                }
                
                // dd($report_list);
                //echo $this->db->getLastQuery();
            }catch(Exception $e){
                print_r($e);
            }
        }
        $data['wardList'] = $wardList;
        $data['fyList'] = $fyList;
        $data['prop_collection'] = $remaining??0;
        if(isset($_REQUEST["api"])){
            return $data;
        }
        return view('property/reports/ward_wise_DCB_report', $data);
    }


    public function holdingWiseRebate2() {
        $ulb_mstr_id = 1;
        $wardList = $this->model_ward_mstr->getWardList(['ulb_mstr_id'=>$ulb_mstr_id]);
        $currentFY = getFY();
        $currentFyID = $this->model_fy_mstr->getFyByFy(['fy'=>$currentFY])['id'];
        $fyList = $this->model_fy_mstr->getFYListDescBefore10Year($currentFyID);
        $data['wardList'] = $wardList;
        $data['fyList'] = $fyList;
        return view('property/reports/holding_wise_rebate2', $data);
    }

    public function holdingWiseRebate2Ajax() {
        if($this->request->getMethod()=='post'){
			try{
                ## Read value
                $start = sanitizeString($this->request->getVar('start'));
                $rowperpage = sanitizeString($this->request->getVar('length')); // Rows display per page

                $columnIndex = sanitizeString($this->request->getVar('order')[0]['column']); // Column index
                $columnName = sanitizeString($this->request->getVar('columns')[$columnIndex]['data']); // Column name
                if ($columnName=="s_no"
                    || $columnName=="ward_no"
                    || $columnName=="payable_amt"
                    || $columnName=="paid_amt"
                    || $columnName=="dtd_discount_amt"
                    || $columnName=="jsk_discount_amt"
                    || $columnName=="online_discount_amt"
                    || $columnName=="total_rebate")
                    $columnName = 'view_ward_mstr.ward_no';
                if ($columnName=="holding_no")
                    $columnName = 'tbl_prop_dtl.holding_no';

                
                //$columnName = "tbl_transaction.tran_date";
                $columnSortOrder = sanitizeString($this->request->getVar('order')[0]['dir']); // asc or desc
                $searchValue = sanitizeString($this->request->getVar('search')['value']); // Search value
                // Date filter
                $search_ward_mstr_id = sanitizeString($this->request->getVar('search_ward_mstr_id'));
                $search_fy_mstr_id = sanitizeString($this->request->getVar('search_fy_mstr_id'));
                list($from_fy, $upto_fy) = explode('-',$search_fy_mstr_id);
                $from_date = $from_fy."-04-01";
                $upto_date = $upto_fy."-03-31";
                $searchQuery = "";
                $whereQuery = "";
                $whereDateBetween = " AND tran_date BETWEEN '".$from_date."' AND '".$upto_date."'";
				//$whereDateBetween = " AND tran_date <= '".$upto_date."'";
				$whereDueDateBetween = " AND due_date BETWEEN '".$from_date."' AND '".$upto_date."'";
                
                $orderBY = " ORDER BY ".$columnName." ".$columnSortOrder;
                $limit = " LIMIT ".$rowperpage." OFFSET ".$start;

                if ($search_ward_mstr_id != '') {
                    $whereQuery .= " AND tbl_prop_dtl.ward_mstr_id='".$search_ward_mstr_id."'";
                }
                
                $whereQueryWithSearch = "";
                if ($searchValue!='') {
                    $whereQueryWithSearch .= " AND (tbl_prop_dtl.holding_no ILIKE '%".$searchValue."%'
                                    OR view_ward_mstr.ward_no ILIKE '%".$searchValue."%')";
                }
                
                $base_url = base_url();
                $selectStatement = "SELECT 
                                    ROW_NUMBER () OVER (ORDER BY ".$columnName.") AS s_no,
                                    view_ward_mstr.ward_no,
                                    CONCAT('<a href=', chr(39), '".$base_url."/propdtl/full/', MD5(tbl_prop_dtl.id::TEXT), '/backbtnhide', chr(39), ' target=', chr(39), '_blank', chr(39), '><b><u>', CASE WHEN tbl_prop_dtl.new_holding_no!='' THEN tbl_prop_dtl.new_holding_no ELSE tbl_prop_dtl.holding_no END, '</u></b></a>') AS holding_no,
                                    COALESCE(PAYABLE_AMT.payable_amt, 0) AS payable_amt,
                                    COALESCE(TRAN_DTD.dtd_paid_amt, 0)+COALESCE(TRAN_JSK.jsk_paid_amt, 0)+COALESCE(TRAN_ONLINE.online_paid_amt, 0) AS paid_amt,
                                    COALESCE(TRAN_DTD.dtd_discount_amt, 0) AS dtd_discount_amt,
                                    COALESCE(TRAN_JSK.jsk_discount_amt, 0) AS jsk_discount_amt,
                                    COALESCE(TRAN_ONLINE.online_discount_amt, 0) AS online_discount_amt,
                                    COALESCE(TRAN_DTD.dtd_discount_amt, 0)+COALESCE(TRAN_JSK.jsk_discount_amt, 0)+COALESCE(TRAN_ONLINE.online_discount_amt, 0) AS total_rebate";
                $sql =  " FROM tbl_prop_dtl
                        INNER JOIN view_ward_mstr ON view_ward_mstr.id=tbl_prop_dtl.ward_mstr_id
                        INNER JOIN (
                            SELECT 
                                prop_dtl_id, 
                                COALESCE(SUM(amount-adjust_amt), 0) AS payable_amt 
                            FROM tbl_prop_demand
                            WHERE paid_status=1 AND status=1 ".$whereDueDateBetween."
                            GROUP BY prop_dtl_id
                        ) AS PAYABLE_AMT ON PAYABLE_AMT.prop_dtl_id=tbl_prop_dtl.id
                        LEFT JOIN (
                            SELECT
                                tbl_transaction.prop_dtl_id AS prop_dtl_id, 
                                COALESCE(SUM(tbl_transaction.Payable_amt), 0) AS dtd_paid_amt,
                                COALESCE(SUM(tbl_transaction.discount_amt), 0) AS dtd_discount_amt
                            FROM tbl_transaction
                            INNER JOIN (SELECT * FROM view_emp_details WHERE user_type_id!=8) emp_details ON emp_details.id=tbl_transaction.tran_by_emp_details_id
                            WHERE 
                                tbl_transaction.tran_type='Property'
                                AND tbl_transaction.tran_mode!='ONLINE'
                                AND tbl_transaction.status=1
                                ".$whereDateBetween."
                                
                                GROUP BY tbl_transaction.prop_dtl_id
                        ) AS TRAN_DTD ON TRAN_DTD.prop_dtl_id=tbl_prop_dtl.id
                        LEFT JOIN (
                            SELECT
                                tbl_transaction.prop_dtl_id AS prop_dtl_id, 
                                COALESCE(SUM(tbl_transaction.Payable_amt), 0) AS jsk_paid_amt,
                                COALESCE(SUM(tbl_transaction.discount_amt), 0) AS jsk_discount_amt
                            FROM tbl_transaction
                            INNER JOIN (SELECT * FROM view_emp_details WHERE user_type_id=8) emp_details ON emp_details.id=tbl_transaction.tran_by_emp_details_id
                            WHERE 
                                tbl_transaction.tran_type='Property'
                                AND tbl_transaction.tran_mode!='ONLINE'
                                AND tbl_transaction.status=1
                                ".$whereDateBetween."
                                GROUP BY tbl_transaction.prop_dtl_id
                        ) AS TRAN_JSK ON TRAN_JSK.prop_dtl_id=tbl_prop_dtl.id
                        LEFT JOIN (
                            SELECT
                                tbl_transaction.prop_dtl_id AS prop_dtl_id, 
                                COALESCE(SUM(tbl_transaction.Payable_amt), 0) AS online_paid_amt,
                                COALESCE(SUM(tbl_transaction.discount_amt), 0) AS online_discount_amt
                            FROM tbl_transaction
                            WHERE 
                                tbl_transaction.tran_type='Property'
                                AND tbl_transaction.tran_mode='ONLINE'
                                AND tbl_transaction.status=1
                                ".$whereDateBetween."
                                GROUP BY tbl_transaction.prop_dtl_id
                        ) AS TRAN_ONLINE ON TRAN_ONLINE.prop_dtl_id=tbl_prop_dtl.id
                        WHERE tbl_prop_dtl.status=1".$whereQuery;
               /*  echo $selectStatement.$sql.$whereQueryWithSearch.$orderBY.$limit;
                return; */
                $totalRecords = $this->model_datatable->getTotalRecords($sql);
                //return json_encode($totalRecords);
                if ($totalRecords>0) {
                    ## Total number of records with filtering
                    $totalRecordwithFilter = $this->model_datatable->getTotalRecordwithFilter($sql.$whereQueryWithSearch);
                    //return json_encode($totalRecordwithFilter);
                    ## Fetch records
                    $fetchSql = $selectStatement.$sql.$whereQueryWithSearch.$orderBY.$limit;
                    //print_var($fetchSql);
                    //$fetchSql = $selectStatement.$sql.$whereQueryWithSearch.$orderBY;
                    $records = $this->model_datatable->getRecords($fetchSql);
                } else {
                    $totalRecordwithFilter = 0;
                    $records = [];
                }
                //print_var($fetchSql);die;
                //echo $this->db->getLastQuery();
                $response = array(
                    "iTotalRecords" => $totalRecords,
                    "iTotalDisplayRecords" => $totalRecordwithFilter,
                    "aaData" => $records
                );
                //print_var($response);die;
                return json_encode($response);
            }catch(Exception $e){

            }
        }
    }

    public function holdingWiseRebateExcel2($search_ward_mstr_id = null,$fy_year=null) 
    {   
        try{
                $search_ward_mstr_id = sanitizeString($search_ward_mstr_id);
                $search_fy_mstr_id = sanitizeString($fy_year);
                list($from_fy, $upto_fy) = explode('-',$search_fy_mstr_id);
                $from_date = $from_fy."-04-01";
                $upto_date = $upto_fy."-03-31";
                $searchQuery = "";
                $whereQuery = "";
                $whereDateBetween = " AND tran_date BETWEEN '".$from_date."' AND '".$upto_date."'";
                
                if ($search_ward_mstr_id != 'ALL' && $search_ward_mstr_id != 'All' && $search_ward_mstr_id != 'all') {
                    $whereQuery .= " AND tbl_prop_dtl.ward_mstr_id='".$search_ward_mstr_id."'";
                }
                
                $selectStatement = "SELECT 
                                    view_ward_mstr.ward_no,
                                    CONCAT('`', tbl_prop_dtl.holding_no) AS holding_no,
                                    COALESCE(PAYABLE_AMT.payable_amt, 0) AS payable_amt,
                                    COALESCE(TRAN_DTD.dtd_paid_amt, 0)+COALESCE(TRAN_JSK.jsk_paid_amt, 0)+COALESCE(TRAN_ONLINE.online_paid_amt, 0) AS paid_amt,
                                    COALESCE(TRAN_DTD.dtd_discount_amt, 0) AS dtd_discount_amt,
                                    COALESCE(TRAN_JSK.jsk_discount_amt, 0) AS jsk_discount_amt,
                                    COALESCE(TRAN_ONLINE.online_discount_amt, 0) AS online_discount_amt,
                                    COALESCE(TRAN_DTD.dtd_discount_amt, 0)+COALESCE(TRAN_JSK.jsk_discount_amt, 0)+COALESCE(TRAN_ONLINE.online_discount_amt, 0) AS total_rebate";
                $sql =  " FROM tbl_prop_dtl
                        INNER JOIN view_ward_mstr ON view_ward_mstr.id=tbl_prop_dtl.ward_mstr_id
                        INNER JOIN (
                            SELECT 
                                prop_dtl_id, 
                                COALESCE(SUM(amount), 0) AS payable_amt 
                            FROM tbl_prop_demand
                            WHERE paid_status=1 AND status=1
                            GROUP BY prop_dtl_id
                        ) AS PAYABLE_AMT ON PAYABLE_AMT.prop_dtl_id=tbl_prop_dtl.id
                        LEFT JOIN (
                            SELECT
                                tbl_transaction.prop_dtl_id AS prop_dtl_id, 
                                COALESCE(SUM(tbl_transaction.Payable_amt), 0) AS dtd_paid_amt,
                                COALESCE(SUM(tbl_transaction.discount_amt), 0) AS dtd_discount_amt
                            FROM tbl_transaction
                            INNER JOIN (SELECT * FROM view_emp_details WHERE user_type_id!=8) emp_details ON emp_details.id=tbl_transaction.tran_by_emp_details_id
                            WHERE 
                                tbl_transaction.tran_type='Property'
                                AND tbl_transaction.tran_mode_mstr_id!=4
                                AND tbl_transaction.status=1
                                ".$whereDateBetween."
                                
                                GROUP BY tbl_transaction.prop_dtl_id
                        ) AS TRAN_DTD ON TRAN_DTD.prop_dtl_id=tbl_prop_dtl.id
                        LEFT JOIN (
                            SELECT
                                tbl_transaction.prop_dtl_id AS prop_dtl_id, 
                                COALESCE(SUM(tbl_transaction.Payable_amt), 0) AS jsk_paid_amt,
                                COALESCE(SUM(tbl_transaction.discount_amt), 0) AS jsk_discount_amt
                            FROM tbl_transaction
                            INNER JOIN (SELECT * FROM view_emp_details WHERE user_type_id=8) emp_details ON emp_details.id=tbl_transaction.tran_by_emp_details_id
                            WHERE 
                                tbl_transaction.tran_type='Property'
                                AND tbl_transaction.tran_mode_mstr_id!=4
                                AND tbl_transaction.status=1
                                ".$whereDateBetween."
                                GROUP BY tbl_transaction.prop_dtl_id
                        ) AS TRAN_JSK ON TRAN_JSK.prop_dtl_id=tbl_prop_dtl.id
                        LEFT JOIN (
                            SELECT
                                tbl_transaction.prop_dtl_id AS prop_dtl_id, 
                                COALESCE(SUM(tbl_transaction.Payable_amt), 0) AS online_paid_amt,
                                COALESCE(SUM(tbl_transaction.discount_amt), 0) AS online_discount_amt
                            FROM tbl_transaction
                            WHERE 
                                tbl_transaction.tran_type='Property'
                                AND tbl_transaction.tran_mode_mstr_id=4
                                AND tbl_transaction.status=1
                                ".$whereDateBetween."
                                GROUP BY tbl_transaction.prop_dtl_id
                        ) AS TRAN_ONLINE ON TRAN_ONLINE.prop_dtl_id=tbl_prop_dtl.id
                        WHERE tbl_prop_dtl.status=1".$whereQuery;

            $fetchSql = $selectStatement.$sql;
            $records = $this->model_datatable->getRecords($fetchSql);
            //phpOfficeLoad();
            $spreadsheet = new Spreadsheet();
            $activeSheet = $spreadsheet->getActiveSheet();
                            $activeSheet->setCellValue('A1', 'Ward No.');
                            $activeSheet->setCellValue('B1', 'Holding No.');
                            $activeSheet->setCellValue('C1', 'Payable Amount');
                            $activeSheet->setCellValue('D1', 'Amount Paid');
                            $activeSheet->setCellValue('E1', 'First Quater Rebate');
                            $activeSheet->setCellValue('F1', 'JSK (2.5%)');
                            $activeSheet->setCellValue('G1', 'Online (5%)');
                            $activeSheet->setCellValue('H1', 'Total Rebate');                            
                            $activeSheet->fromArray($records, NULL, 'A2');
            $filename = "holding_wise_rebate_".date('Ymd-hisa').".xlsx";
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="'.$filename.'"');
            header('Cache-Control: max-age=0');
            $writer = new Xlsx($spreadsheet);
            //$writer->save(APPPATH.'/hello world.xlsx');
            $writer->save('php://output');
        }catch(Exception $e){
            print_r($e);
        }
    }

	public function holdingWiseRebate() {
        $ulb_mstr_id = 1;
        $wardList = $this->model_ward_mstr->getWardList(['ulb_mstr_id'=>$ulb_mstr_id]);
        $data['wardList'] = $wardList;
        return view('property/reports/holding_wise_rebate', $data);
    }

    public function holdingWiseRebateAjax() {
        if($this->request->getMethod()=='post'){
			try{
                ## Read value
                $start = sanitizeString($this->request->getVar('start'));
                $rowperpage = sanitizeString($this->request->getVar('length')); // Rows display per page

                $columnIndex = sanitizeString($this->request->getVar('order')[0]['column']); // Column index
                $columnName = sanitizeString($this->request->getVar('columns')[$columnIndex]['data']); // Column name
                if ($columnName=="s_no"
                    || $columnName=="ward_no"
                    || $columnName=="holding_type"
                    || $columnName=="total_payable_amt"
                    || $columnName=="no_of_transaction"
                    || $columnName=="no_of_transaction_by_jsk"
                    || $columnName=="total_discount_amt_by_jsk"
                    || $columnName=="no_of_transaction_by_ONLINE"
                    || $columnName=="total_discount_amt_by_ONLINE")
                    $columnName = 'view_ward_mstr.ward_no';
                if ($columnName=="holding_no")
                    $columnName = 'tbl_prop_dtl.holding_no';

                
                //$columnName = "tbl_transaction.tran_date";
                $columnSortOrder = sanitizeString($this->request->getVar('order')[0]['dir']); // asc or desc
                $searchValue = sanitizeString($this->request->getVar('search')['value']); // Search value
                
                // Date filter
                $search_ward_mstr_id = sanitizeString($this->request->getVar('search_ward_mstr_id'));
                $searchQuery = "";
                $whereQuery = "";
                
                
                $orderBY = " ORDER BY ".$columnName." ".$columnSortOrder;
                $limit = " LIMIT ".$rowperpage." OFFSET ".$start;

                if ($search_ward_mstr_id != '') {
                    $whereQuery .= " AND tbl_prop_dtl.ward_mstr_id='".$search_ward_mstr_id."'";
                }
                
                $whereQueryWithSearch = "";
                if ($searchValue!='') {
                    $whereQueryWithSearch .= " AND (tbl_prop_dtl.holding_no ILIKE '%".$searchValue."%'
                                    OR view_ward_mstr.ward_no ILIKE '%".$searchValue."%')";
                }
                
                $selectStatement = "SELECT 
                                    ROW_NUMBER () OVER (ORDER BY ".$columnName.") AS s_no,
                                    view_ward_mstr.ward_no,
                                    tbl_prop_dtl.holding_no,
                                    (CASE 
                                        WHEN tbl_prop_dtl.prop_type_mstr_id=4 THEN 'VACANT LAND'
                                         WHEN tbl_prop_dtl.holding_type='MIX_COMMERCIAL' OR tbl_prop_dtl.holding_type='PURE_COMMERCIAL' THEN 'COMMERCIAL'
                                         WHEN tbl_prop_dtl.holding_type='MIX_GOVERNMENT' OR tbl_prop_dtl.holding_type='PURE_GOVERNMENT' THEN 'GOVERNMENT'
                                         WHEN tbl_prop_dtl.holding_type='PURE_RESIDENTIAL' THEN 'RESIDENCIAL'
                                     END) AS holding_type,
                                    TRAN.total_payable_amt,
                                    TRAN.no_of_transaction,
                                    COALESCE(TRAN_JSK.no_of_transaction_by_jsk, 0) AS no_of_transaction_by_jsk,
                                    COALESCE(TRAN_JSK.total_discount_amt_by_jsk, 0.00) AS total_discount_amt_by_jsk, 
                                    COALESCE(TRAN_ONLINE.no_of_transaction_by_ONLINE, 0) AS no_of_transaction_by_ONLINE,
                                    COALESCE(TRAN_ONLINE.total_discount_amt_by_ONLINE, 0.00) AS total_discount_amt_by_ONLINE";
                $sql =  " FROM tbl_prop_dtl
                        INNER JOIN view_ward_mstr ON view_ward_mstr.id=tbl_prop_dtl.ward_mstr_id
                        INNER JOIN (
                            SELECT 
                                prop_dtl_id, 
                                SUM(Payable_amt) AS total_payable_amt,
                                COUNT(*) AS no_of_transaction
                            FROM tbl_transaction 
                            WHERE 
                                tran_type='Property'
                                AND tbl_transaction.status=1
                                GROUP BY prop_dtl_id
                                
                        ) AS TRAN ON TRAN.prop_dtl_id=tbl_prop_dtl.id
                        LEFT JOIN (
                            SELECT 
                                tbl_transaction.prop_dtl_id, 
                                COUNT(*) AS no_of_transaction_by_jsk,
                                SUM(tbl_transaction.discount_amt) AS total_discount_amt_by_jsk
                            FROM tbl_transaction 
                            INNER JOIN view_emp_details ON view_emp_details.id=tbl_transaction.tran_by_emp_details_id
                            WHERE 
                                tbl_transaction.tran_type='Property'
                                AND tbl_transaction.status=1
                                AND view_emp_details.user_type_id=8
                                GROUP BY tbl_transaction.prop_dtl_id
                        ) AS TRAN_JSK ON TRAN_JSK.prop_dtl_id=tbl_prop_dtl.id
                        LEFT JOIN (
                            SELECT 
                                tbl_transaction.prop_dtl_id, 
                                COUNT(*) AS no_of_transaction_by_ONLINE,
                                SUM(tbl_transaction.discount_amt) AS total_discount_amt_by_ONLINE
                            FROM tbl_transaction 
                            INNER JOIN view_emp_details ON view_emp_details.id=tbl_transaction.tran_by_emp_details_id
                            WHERE 
                                tbl_transaction.tran_type='Property'
                                AND tbl_transaction.status=1
                                AND view_emp_details.user_type_id!=8
                                GROUP BY tbl_transaction.prop_dtl_id
                        ) AS TRAN_ONLINE ON TRAN_ONLINE.prop_dtl_id=tbl_prop_dtl.id
                        WHERE    
                            tbl_prop_dtl.holding_type IS NOT NULL".$whereQuery;
                //$fetchSql = $selectStatement.$sql.$whereQueryWithSearch.$orderBY.$limit;
                //return json_encode($fetchSql);
                ## Total number of records without filtering
                $totalRecords = $this->model_datatable->getTotalRecords($sql);
                //return json_encode($totalRecords);
                if ($totalRecords>0) {
                   
                    ## Total number of records with filtering
                    $totalRecordwithFilter = $this->model_datatable->getTotalRecordwithFilter($sql.$whereQueryWithSearch);
                    //return json_encode($totalRecordwithFilter);
                    ## Fetch records
                    $fetchSql = $selectStatement.$sql.$whereQueryWithSearch.$orderBY.$limit;
                    $records = $this->model_datatable->getRecords($fetchSql);
                } else {
                    $totalRecordwithFilter = 0;
                    $records = [];
                }
                
                //echo $this->db->getLastQuery();
                $response = array(
                    "iTotalRecords" => $totalRecords,
                    "iTotalDisplayRecords" => $totalRecordwithFilter,
                    "aaData" => $records
                );
                return json_encode($response);
            }catch(Exception $e){

            }
        }
    }

    public function holdingWiseRebateExcel($search_ward_mstr_id = null) {
        try{
            $whereQuery = "";
                if ($search_ward_mstr_id != 'ALL') {
                    $whereQuery .= " AND  tbl_prop_dtl.ward_mstr_id='".$search_ward_mstr_id."'";
                }
                $selectStatement = "SELECT 
                                    view_ward_mstr.ward_no,
                                    concat('`',tbl_prop_dtl.holding_no) AS holding_no,
                                    (CASE 
                                        WHEN tbl_prop_dtl.prop_type_mstr_id=4 THEN 'VACANT LAND'
                                            WHEN tbl_prop_dtl.holding_type='MIX_COMMERCIAL' OR tbl_prop_dtl.holding_type='PURE_COMMERCIAL' THEN 'COMMERCIAL'
                                            WHEN tbl_prop_dtl.holding_type='MIX_GOVERNMENT' OR tbl_prop_dtl.holding_type='PURE_GOVERNMENT' THEN 'GOVERNMENT'
                                            WHEN tbl_prop_dtl.holding_type='PURE_RESIDENTIAL' THEN 'RESIDENCIAL'
                                        END) AS holding_type,
                                    TRAN.total_payable_amt,
                                    TRAN.no_of_transaction,
                                    COALESCE(TRAN_JSK.no_of_transaction_by_jsk, 0) AS no_of_transaction_by_jsk,
                                    COALESCE(TRAN_JSK.total_discount_amt_by_jsk, 0.00) AS total_discount_amt_by_jsk, 
                                    COALESCE(TRAN_ONLINE.no_of_transaction_by_ONLINE, 0) AS no_of_transaction_by_ONLINE,
                                    COALESCE(TRAN_ONLINE.total_discount_amt_by_ONLINE, 0.00) AS total_discount_amt_by_ONLINE";

                $sql = " FROM tbl_prop_dtl
                        INNER JOIN view_ward_mstr ON view_ward_mstr.id=tbl_prop_dtl.ward_mstr_id
                        INNER JOIN (
                            SELECT 
                                prop_dtl_id, 
                                SUM(Payable_amt) AS total_payable_amt,
                                COUNT(*) AS no_of_transaction
                            FROM tbl_transaction 
                            WHERE 
                                tran_type='Property'
                                AND tbl_transaction.status=1
                                GROUP BY prop_dtl_id
                                
                        ) AS TRAN ON TRAN.prop_dtl_id=tbl_prop_dtl.id
                        LEFT JOIN (
                            SELECT 
                                tbl_transaction.prop_dtl_id, 
                                COUNT(*) AS no_of_transaction_by_jsk,
                                SUM(tbl_transaction.discount_amt) AS total_discount_amt_by_jsk
                            FROM tbl_transaction 
                            INNER JOIN view_emp_details ON view_emp_details.id=tbl_transaction.tran_by_emp_details_id
                            WHERE 
                                tbl_transaction.tran_type='Property'
                                AND tbl_transaction.status=1
                                AND view_emp_details.user_type_id=8
                                GROUP BY tbl_transaction.prop_dtl_id
                        ) AS TRAN_JSK ON TRAN_JSK.prop_dtl_id=tbl_prop_dtl.id
                        LEFT JOIN (
                            SELECT 
                                tbl_transaction.prop_dtl_id, 
                                COUNT(*) AS no_of_transaction_by_ONLINE,
                                SUM(tbl_transaction.discount_amt) AS total_discount_amt_by_ONLINE
                            FROM tbl_transaction 
                            INNER JOIN view_emp_details ON view_emp_details.id=tbl_transaction.tran_by_emp_details_id
                            WHERE 
                                tbl_transaction.tran_type='Property'
                                AND tbl_transaction.status=1
                                AND view_emp_details.user_type_id!=8
                                GROUP BY tbl_transaction.prop_dtl_id
                        ) AS TRAN_ONLINE ON TRAN_ONLINE.prop_dtl_id=tbl_prop_dtl.id
                        WHERE    
                            tbl_prop_dtl.holding_type IS NOT NULL".$whereQuery;

            $fetchSql = $selectStatement.$sql;
            $records = $this->model_datatable->getRecords($fetchSql);
            //phpOfficeLoad();
            $spreadsheet = new Spreadsheet();
            $activeSheet = $spreadsheet->getActiveSheet();
                            $activeSheet->setCellValue('A1', 'Ward No.');
                            $activeSheet->setCellValue('B1', 'Holding No.');
                            $activeSheet->setCellValue('C1', 'Holding type');
                            $activeSheet->setCellValue('D1', 'Total Payable Amount');
                            $activeSheet->setCellValue('E1', 'No of Transaction');
                            $activeSheet->setCellValue('F1', 'No of Transaction By JSK');
                            $activeSheet->setCellValue('G1', 'Discount Amount By JSK');
                            $activeSheet->setCellValue('H1', 'No of Transaction By Online');
                            $activeSheet->setCellValue('I1', 'Discount Amount By Online');
                            $activeSheet->fromArray($records, NULL, 'A2');
            $filename = "holding_wise_rebate_".date('Ymd-hisa').".xlsx";
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="'.$filename.'"');
            header('Cache-Control: max-age=0');
            $writer = new Xlsx($spreadsheet);
            //$writer->save(APPPATH.'/hello world.xlsx');
            $writer->save('php://output');
        }catch(Exception $e){
            print_r($e);
        }
    }

    public function wardWiseSAFPendingDetails() {
        $ulb_mstr_id = 1;
        $data = ['from_date'=>date('Y-m-d'), 'upto_date'=>date('Y-m-d')];
        $wardList = $this->model_ward_mstr->getWardList(['ulb_mstr_id'=>$ulb_mstr_id]);
        $propWhere = "";
        $wardWhere = "";
        $where = "";
        if($this->request->getMethod()=='post'){
            $data = arrFilterSanitizeString($this->request->getVar());
            if ($data['ward_mstr_id']!='') {
                $propWhere = " AND tbl_prop_dtl.ward_mstr_id=".$data['ward_mstr_id'];
                $wardWhere = " AND view_ward_mstr.id=".$data['ward_mstr_id'];
                $where = " AND tbl_saf_dtl.ward_mstr_id=".$data['ward_mstr_id'];
            }
        }
        $sql = "WITH total_prop AS (
                    SELECT ward_mstr_id, COUNT(*) AS no_of_prop FROM tbl_prop_dtl WHERE status=1 ".$propWhere." GROUP BY ward_mstr_id
                ),
                total_saf AS (
                    SELECT ward_mstr_id, COUNT(*) AS no_of_saf FROM tbl_saf_dtl WHERE status=1 ".$where." GROUP BY ward_mstr_id
                ),
                total_sam AS (
                    SELECT tbl_saf_dtl.ward_mstr_id, COUNT(*) AS no_of_sam FROM tbl_saf_memo_dtl
                    INNER JOIN tbl_saf_dtl ON tbl_saf_dtl.id=tbl_saf_memo_dtl.saf_dtl_id
                    WHERE tbl_saf_dtl.status=1 ".$where." AND tbl_saf_memo_dtl.status=1 AND tbl_saf_memo_dtl.memo_type='SAM' GROUP BY tbl_saf_dtl.ward_mstr_id
                ),
                total_fam AS (
                    SELECT tbl_saf_dtl.ward_mstr_id, COUNT(*) AS no_of_fam FROM tbl_saf_memo_dtl
                    INNER JOIN tbl_saf_dtl ON tbl_saf_dtl.id=tbl_saf_memo_dtl.saf_dtl_id
                    WHERE tbl_saf_dtl.status=1 ".$where." AND tbl_saf_memo_dtl.status=1 AND tbl_saf_memo_dtl.memo_type='FAM' GROUP BY tbl_saf_dtl.ward_mstr_id
                ),
                total_geotagging AS (
                    SELECT tbl_saf_dtl.ward_mstr_id, COUNT(*) AS no_of_geotagging FROM tbl_saf_dtl 
                    INNER JOIN (SELECT geotag_dtl_id FROM tbl_saf_geotag_upload_dtl WHERE status=1 GROUP BY geotag_dtl_id) AS geotag_dtl ON geotag_dtl.geotag_dtl_id=tbl_saf_dtl.id 
                    WHERE tbl_saf_dtl.status=1 ".$where." GROUP BY tbl_saf_dtl.ward_mstr_id
                ),
                total_saf_pending AS (
                    SELECT ward_mstr_id, COUNT(*) AS no_of_saf_pending FROM tbl_saf_dtl
                    WHERE  status=1 ".$where." AND saf_pending_status=0 GROUP BY ward_mstr_id
                ),
                total_dealing_assistant_pending AS (
                    SELECT tbl_saf_dtl.ward_mstr_id, COUNT(*) AS no_of_pending_by_dealing_assistant FROM tbl_level_pending_dtl 
                    INNER JOIN tbl_saf_dtl ON tbl_saf_dtl.id=tbl_level_pending_dtl.saf_dtl_id
                    WHERE tbl_saf_dtl.status=1 ".$where." AND tbl_level_pending_dtl.receiver_user_type_id=6 AND tbl_level_pending_dtl.status=1 AND tbl_level_pending_dtl.verification_status=0 GROUP BY tbl_saf_dtl.ward_mstr_id
                ),
                total_ulb_tc_pending AS (
                    SELECT tbl_saf_dtl.ward_mstr_id, COUNT(*) AS no_of_pending_by_ulb_tc FROM tbl_level_pending_dtl 
                    INNER JOIN tbl_saf_dtl ON tbl_saf_dtl.id=tbl_level_pending_dtl.saf_dtl_id
                    WHERE tbl_saf_dtl.status=1 ".$where." AND tbl_level_pending_dtl.receiver_user_type_id=7 AND tbl_level_pending_dtl.status=1 AND tbl_level_pending_dtl.verification_status=0 GROUP BY tbl_saf_dtl.ward_mstr_id
                ),
                total_section_incharge_pending AS (
                    SELECT tbl_saf_dtl.ward_mstr_id, COUNT(*) AS no_of_pending_by_section_incharge FROM tbl_level_pending_dtl 
                    INNER JOIN tbl_saf_dtl ON tbl_saf_dtl.id=tbl_level_pending_dtl.saf_dtl_id
                    WHERE tbl_saf_dtl.status=1 ".$where." AND tbl_level_pending_dtl.receiver_user_type_id=9 AND tbl_level_pending_dtl.status=1 AND tbl_level_pending_dtl.verification_status=0 GROUP BY tbl_saf_dtl.ward_mstr_id
                ),
                total_executive_officer_pending AS (
                    SELECT tbl_saf_dtl.ward_mstr_id, COUNT(*) AS no_of_pending_by_executive_officer FROM tbl_level_pending_dtl 
                    INNER JOIN tbl_saf_dtl ON tbl_saf_dtl.id=tbl_level_pending_dtl.saf_dtl_id
                    WHERE tbl_saf_dtl.status=1 ".$where." AND tbl_level_pending_dtl.receiver_user_type_id=10 AND tbl_level_pending_dtl.status=1 AND tbl_level_pending_dtl.verification_status=0 GROUP BY tbl_saf_dtl.ward_mstr_id
                )
                SELECT
                    view_ward_mstr.ward_no,
                    COALESCE(total_prop.no_of_prop, 0) AS no_of_prop,
                    COALESCE(total_saf.no_of_saf, 0) AS no_of_saf,
                    COALESCE(total_sam.no_of_sam, 0) AS no_of_sam,
                    COALESCE(total_fam.no_of_fam, 0) AS no_of_fam,
                    COALESCE(total_geotagging.no_of_geotagging, 0) AS no_of_geotagging,
                    COALESCE(total_saf_pending.no_of_saf_pending, 0) AS no_of_saf_pending,
                    COALESCE(total_dealing_assistant_pending.no_of_pending_by_dealing_assistant, 0) AS no_of_pending_by_dealing_assistant,
                    COALESCE(total_ulb_tc_pending.no_of_pending_by_ulb_tc, 0) AS no_of_pending_by_ulb_tc,
                    COALESCE(total_section_incharge_pending.no_of_pending_by_section_incharge, 0) AS no_of_pending_by_section_incharge,
                    COALESCE(total_executive_officer_pending.no_of_pending_by_executive_officer, 0) AS no_of_pending_by_executive_officer
                FROM view_ward_mstr
                LEFT JOIN total_prop ON total_prop.ward_mstr_id=view_ward_mstr.id
                LEFT JOIN total_saf ON total_saf.ward_mstr_id=view_ward_mstr.id
                LEFT JOIN total_sam ON total_sam.ward_mstr_id=view_ward_mstr.id
                LEFT JOIN total_fam ON total_fam.ward_mstr_id=view_ward_mstr.id
                LEFT JOIN total_geotagging ON total_geotagging.ward_mstr_id=view_ward_mstr.id
                LEFT JOIN total_saf_pending ON total_saf_pending.ward_mstr_id=view_ward_mstr.id
                LEFT JOIN total_dealing_assistant_pending ON total_dealing_assistant_pending.ward_mstr_id=view_ward_mstr.id
                LEFT JOIN total_ulb_tc_pending ON total_ulb_tc_pending.ward_mstr_id=view_ward_mstr.id
                LEFT JOIN total_section_incharge_pending ON total_section_incharge_pending.ward_mstr_id=view_ward_mstr.id
                LEFT JOIN total_executive_officer_pending ON total_executive_officer_pending.ward_mstr_id=view_ward_mstr.id
                WHERE view_ward_mstr.status=1 ".$wardWhere;
        if($report_list = $this->model_datatable->getRecords($sql)) {
            $data['pending_dtl'] = $report_list;
        }
        
        $data['wardList'] = $wardList;
        return view('property/reports/ward_wise_saf_pending_details', $data); 
    }

    public function wardWiseSAFPendingDetails2() {
        $ulb_mstr_id = 1;
        $data = ['from_date'=>date('Y-m-d'), 'upto_date'=>date('Y-m-d')];
        $wardList = $this->model_ward_mstr->getWardList(['ulb_mstr_id'=>$ulb_mstr_id]);
        $propWhere = "";
        $wardWhere = "";
        $where = "";
        if($this->request->getMethod()=='post')
        {
            $data = arrFilterSanitizeString($this->request->getVar());
            if ($data['ward_mstr_id']!='') {
                $propWhere = " AND tbl_prop_dtl.ward_mstr_id=".$data['ward_mstr_id'];
                $wardWhere = " AND view_ward_mstr.id=".$data['ward_mstr_id'];
                $where = " AND tbl_saf_dtl.ward_mstr_id=".$data['ward_mstr_id'];
            }
            $sql = "WITH total_saf AS (
                SELECT ward_mstr_id, COUNT(*) AS no_of_saf FROM tbl_saf_dtl WHERE status=1 ".$where." GROUP BY ward_mstr_id
            ),

            total_digitized AS (
                SELECT tbl_saf_dtl.ward_mstr_id, COUNT(DISTINCT(saf_doc_dtl.saf_dtl_id)) AS no_of_digitized FROM tbl_saf_dtl 
                INNER JOIN (SELECT saf_dtl_id FROM tbl_saf_doc_dtl WHERE status=1 AND verify_status!=0
                AND saf_owner_dtl_id IS NULL GROUP BY saf_dtl_id) AS saf_doc_dtl ON saf_doc_dtl.saf_dtl_id=tbl_saf_dtl.id 
                WHERE tbl_saf_dtl.status=1 ".$where." GROUP BY tbl_saf_dtl.ward_mstr_id
            ),
            total_sam AS (
                SELECT tbl_saf_dtl.ward_mstr_id, COUNT(DISTINCT(tbl_saf_memo_dtl.saf_dtl_id)) AS no_of_sam FROM tbl_saf_memo_dtl
                INNER JOIN tbl_saf_dtl ON tbl_saf_dtl.id=tbl_saf_memo_dtl.saf_dtl_id
                WHERE tbl_saf_dtl.status=1 ".$where." AND tbl_saf_memo_dtl.status=1 AND tbl_saf_memo_dtl.memo_type='SAM' GROUP BY tbl_saf_dtl.ward_mstr_id
            ),
            total_geotagging AS (
                SELECT tbl_saf_dtl.ward_mstr_id, COUNT(DISTINCT(geotag_dtl.geotag_dtl_id)) AS no_of_geotagging FROM tbl_saf_dtl 
                INNER JOIN (SELECT geotag_dtl_id FROM tbl_saf_geotag_upload_dtl WHERE status=1 GROUP BY geotag_dtl_id) AS geotag_dtl ON geotag_dtl.geotag_dtl_id=tbl_saf_dtl.id 
                WHERE tbl_saf_dtl.status=1 ".$where." GROUP BY tbl_saf_dtl.ward_mstr_id
            ),
            total_back_to_citizen_pending AS (
                SELECT tbl_saf_dtl.ward_mstr_id, COUNT(DISTINCT(tbl_level_pending_dtl.saf_dtl_id)) AS no_of_pending_back_to_citizen FROM tbl_saf_dtl
                INNER JOIN tbl_level_pending_dtl ON tbl_level_pending_dtl.saf_dtl_id=tbl_saf_dtl.id
                INNER JOIN (SELECT saf_dtl_id, MAX(id) AS level_pending_dtl_id FROM tbl_level_pending_dtl WHERE status=1 GROUP BY saf_dtl_id) AS level_pending_dtl ON level_pending_dtl.level_pending_dtl_id=tbl_level_pending_dtl.id
                WHERE tbl_saf_dtl.id NOT IN (select saf_dtl_id from tbl_btc_hide)
                      AND tbl_saf_dtl.status=1 ".$where." AND tbl_level_pending_dtl.receiver_user_type_id=11 AND tbl_level_pending_dtl.status=1 AND tbl_level_pending_dtl.verification_status=2 GROUP BY tbl_saf_dtl.ward_mstr_id
            ),
            total_dealing_assistant_pending AS (
                SELECT tbl_saf_dtl.ward_mstr_id, COUNT(DISTINCT(tbl_level_pending_dtl.saf_dtl_id)) AS no_of_pending_by_dealing_assistant FROM tbl_saf_dtl
                INNER JOIN tbl_level_pending_dtl ON tbl_level_pending_dtl.saf_dtl_id=tbl_saf_dtl.id
                INNER JOIN (SELECT saf_dtl_id, MAX(id) AS level_pending_dtl_id FROM tbl_level_pending_dtl WHERE status=1 GROUP BY saf_dtl_id) AS level_pending_dtl ON level_pending_dtl.level_pending_dtl_id=tbl_level_pending_dtl.id
                WHERE tbl_saf_dtl.status=1 ".$where." AND tbl_level_pending_dtl.receiver_user_type_id=6 AND tbl_level_pending_dtl.status=1 AND tbl_level_pending_dtl.verification_status=0 GROUP BY tbl_saf_dtl.ward_mstr_id
            ),
            total_ulb_tc_pending AS (
                SELECT tbl_saf_dtl.ward_mstr_id, COUNT(DISTINCT(tbl_level_pending_dtl.saf_dtl_id)) AS no_of_pending_by_ulb_tc FROM tbl_saf_dtl
                INNER JOIN tbl_level_pending_dtl ON tbl_level_pending_dtl.saf_dtl_id=tbl_saf_dtl.id
                INNER JOIN (SELECT saf_dtl_id, MAX(id) AS level_pending_dtl_id FROM tbl_level_pending_dtl WHERE status=1 GROUP BY saf_dtl_id) AS level_pending_dtl ON level_pending_dtl.level_pending_dtl_id=tbl_level_pending_dtl.id
                INNER JOIN (
                    SELECT
                        geotag_dtl_id
                    FROM tbl_saf_geotag_upload_dtl
                    WHERE status=1
                    GROUP BY geotag_dtl_id
                ) AS geotag_dtl ON geotag_dtl.geotag_dtl_id=tbl_saf_dtl.id
                WHERE tbl_saf_dtl.status=1 ".$where." AND tbl_level_pending_dtl.receiver_user_type_id=7 AND tbl_level_pending_dtl.status=1 AND tbl_level_pending_dtl.verification_status=0 GROUP BY tbl_saf_dtl.ward_mstr_id
            ),
            total_section_incharge_pending AS (
                SELECT tbl_saf_dtl.ward_mstr_id, COUNT(DISTINCT(tbl_level_pending_dtl.saf_dtl_id)) AS no_of_pending_by_section_incharge FROM tbl_saf_dtl
                INNER JOIN tbl_level_pending_dtl ON tbl_level_pending_dtl.saf_dtl_id=tbl_saf_dtl.id
                WHERE tbl_saf_dtl.status=1 ".$where." AND tbl_level_pending_dtl.receiver_user_type_id=9 AND tbl_level_pending_dtl.status=1 AND tbl_level_pending_dtl.verification_status=0 GROUP BY tbl_saf_dtl.ward_mstr_id
            ),
            total_executive_officer_pending AS (
                SELECT tbl_saf_dtl.ward_mstr_id, COUNT(DISTINCT(tbl_level_pending_dtl.saf_dtl_id)) AS no_of_pending_by_executive_officer FROM tbl_saf_dtl
                INNER JOIN tbl_level_pending_dtl ON tbl_level_pending_dtl.saf_dtl_id=tbl_saf_dtl.id
                WHERE tbl_saf_dtl.status=1 ".$where." AND tbl_level_pending_dtl.receiver_user_type_id=10 AND tbl_level_pending_dtl.status=1 AND tbl_level_pending_dtl.verification_status=0 GROUP BY tbl_saf_dtl.ward_mstr_id
            ),
            total_fam AS (
                SELECT tbl_saf_dtl.ward_mstr_id, COUNT(DISTINCT(tbl_saf_memo_dtl.saf_dtl_id)) AS no_of_fam FROM tbl_saf_memo_dtl
                INNER JOIN tbl_saf_dtl ON tbl_saf_dtl.id=tbl_saf_memo_dtl.saf_dtl_id
                WHERE tbl_saf_dtl.status=1 ".$where." AND tbl_saf_memo_dtl.status=1 AND tbl_saf_memo_dtl.memo_type='FAM' GROUP BY tbl_saf_dtl.ward_mstr_id
            )
            SELECT
                view_ward_mstr.ward_no,
                COALESCE(total_saf.no_of_saf, 0) AS no_of_saf,
                COALESCE(total_digitized.no_of_digitized, 0) AS no_of_digitized,
                COALESCE(total_sam.no_of_sam, 0) AS no_of_sam,
                COALESCE(total_geotagging.no_of_geotagging, 0) AS no_of_geotagging,
                COALESCE(total_back_to_citizen_pending.no_of_pending_back_to_citizen, 0) AS no_of_pending_back_to_citizen,
                COALESCE(total_dealing_assistant_pending.no_of_pending_by_dealing_assistant, 0) AS no_of_pending_by_dealing_assistant,
                COALESCE(total_ulb_tc_pending.no_of_pending_by_ulb_tc, 0) AS no_of_pending_by_ulb_tc,
                COALESCE(total_section_incharge_pending.no_of_pending_by_section_incharge, 0) AS no_of_pending_by_section_incharge,
                COALESCE(total_executive_officer_pending.no_of_pending_by_executive_officer, 0) AS no_of_pending_by_executive_officer,
                COALESCE(total_fam.no_of_fam, 0) AS no_of_fam
            FROM view_ward_mstr 
            LEFT JOIN total_saf ON total_saf.ward_mstr_id=view_ward_mstr.id
            LEFT JOIN total_digitized ON total_digitized.ward_mstr_id=view_ward_mstr.id
            LEFT JOIN total_sam ON total_sam.ward_mstr_id=view_ward_mstr.id
            LEFT JOIN total_geotagging ON total_geotagging.ward_mstr_id=view_ward_mstr.id
            LEFT JOIN total_back_to_citizen_pending ON total_back_to_citizen_pending.ward_mstr_id=view_ward_mstr.id
            LEFT JOIN total_dealing_assistant_pending ON total_dealing_assistant_pending.ward_mstr_id=view_ward_mstr.id
            LEFT JOIN total_ulb_tc_pending ON total_ulb_tc_pending.ward_mstr_id=view_ward_mstr.id
            LEFT JOIN total_section_incharge_pending ON total_section_incharge_pending.ward_mstr_id=view_ward_mstr.id
            LEFT JOIN total_executive_officer_pending ON total_executive_officer_pending.ward_mstr_id=view_ward_mstr.id
            LEFT JOIN total_fam ON total_fam.ward_mstr_id=view_ward_mstr.id
            WHERE view_ward_mstr.status=1 ".$wardWhere." ORDER BY SUBSTRING(view_ward_mstr.ward_no FROM '([0-9]+)')::BIGINT ASC, view_ward_mstr.ward_no";
            if($report_list = $this->model_datatable->getRecords($sql))
            {
                $data['pending_dtl'] = $report_list;
            }
        }
        
        $data['wardList'] = $wardList;
        return view('property/reports/ward_wise_saf_pending_details2', $data); 
    }

    public function safPropIndividualDemandAndCollecton($action = null) {
        
        $session = session();
        if (!is_null($action) && $action=='excel') {
            if (isset($session->emp_details['report_query']['saf_prop_individual_demand_and_collecton'])) {
                $fetchSql = $session->emp_details['report_query']['saf_prop_individual_demand_and_collecton'];
                $records = $this->model_datatable->getRecords($fetchSql);

                $spreadsheet = new Spreadsheet();
                $activeSheet = $spreadsheet->getActiveSheet();
                                $activeSheet->setCellValue('A1', 'Ward No.');
                                $activeSheet->setCellValue('B1', 'Holding No.');
                                $activeSheet->setCellValue('C1', 'Unique House No.');
                                $activeSheet->setCellValue('D1', 'Saf No.');
                                $activeSheet->setCellValue('E1', 'Applicant Name');
                                $activeSheet->setCellValue('F1', 'Mobile no.');
                                $activeSheet->setCellValue('G1', 'Address');
                                $activeSheet->setCellValue('H1', 'Assessment Type');
                                $activeSheet->setCellValue('I1', 'Usage Type');
                                $activeSheet->setCellValue('J1', 'Construction Type');
                                $activeSheet->setCellValue('K1', 'Demand Before 20-21');
                                $activeSheet->setCellValue('L1', 'Demand for 20-21');
                                $activeSheet->setCellValue('M1', 'Demand for 21-22');
                                $activeSheet->setCellValue('N1', 'Total Demand');
                                $activeSheet->setCellValue('O1', 'Collection Before 20-21');
                                $activeSheet->setCellValue('P1', 'Collection for 20-21');
                                $activeSheet->setCellValue('Q1', 'Collection for 21-22');
                                $activeSheet->setCellValue('R1', 'Total Collection');
                                $activeSheet->setCellValue('S1', 'Penalty');
                                $activeSheet->setCellValue('T1', 'Rebate');
                                $activeSheet->setCellValue('U1', 'Advance');
                                $activeSheet->setCellValue('V1', 'Adjust');
                                $activeSheet->setCellValue('W1', 'Total Due');
                                $activeSheet->fromArray($records, NULL, 'A2');
                $filename = "safPropIndividualDemandAndCollecton".date('Ymd-hisa').".xlsx";
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="'.$filename.'"');
                header('Cache-Control: max-age=0');
                $writer = new Xlsx($spreadsheet);
                $writer->save('php://output');
                return;
            }
        }
        $ulb_mstr_id = $session->get('ulb_dtl')['ulb_mstr_id'];
        $wardList = $this->model_ward_mstr->getWardList(['ulb_mstr_id'=>$ulb_mstr_id]);
        if($this->request->getMethod()=='post'){
			try{
                $data = arrFilterSanitizeString($this->request->getVar());
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
                        $session->push("emp_details", ['report_query'=>['saf_prop_individual_demand_and_collecton'=>$sql]]);
            } catch(Exception $e) {

            }
        }
        if (isset($_GET['page']) && $_GET['page']!='clr') {
            if (isset($session->emp_details['report_query']['saf_prop_individual_demand_and_collecton'])) {
                try {
                    $sql = $session->emp_details['report_query']['saf_prop_individual_demand_and_collecton'];
                    $result = $this->model_datatable->getDatatable($sql);
                    $data['result'] = $result['result'];
                    $data['pager'] = $result['count'];
                    $data['offset'] = $result['offset'];
                } catch(Exception $e) { }
            }
        }
        $data['wardList'] = $wardList;
        return view('property/reports/saf_prop_individual_demand_and_collecton', $data);
    }

    public function safPropIndividualDemandAndCollectonAjax($action = null) {
        if($this->request->getMethod()=='post'){
			try{
                $data = arrFilterSanitizeString($this->request->getVar());
                $sql = $this->PropReports->safPropIndividualDemandAndCollecton($data);
                $result = $this->db->query("SELECT generateCSVReports('".$sql."', 'safPropIndividualDemandAndCollecton')");
                $filename = $result->getFirstRow("array");
                return json_encode($filename);
            } catch(Exception $e) {
                echo $e;
            }
        }
    }

    public function remainingDemandPropertyDtl() {
        $session = session();
        $ulb_mstr_id = $session->get('ulb_dtl')['ulb_mstr_id'];
        $wardList = $this->model_ward_mstr->getWardList(['ulb_mstr_id'=>$ulb_mstr_id]);
        
        $data = arrFilterSanitizeString($this->request->getVar());

        $wardWhere = "";
        if (isset($data['ward_mstr_id'])) {
			if ($data['ward_mstr_id'] != null && $data['ward_mstr_id']!='') {
				$wardWhere = " AND tbl_prop_dtl.ward_mstr_id='".$data['ward_mstr_id']."'";
			}
        }
        $fetchSql = "SELECT
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

        if ($records = $this->model_datatable->getDatatable($fetchSql)) {
            $data["result"] = $records["result"];
            $data["pager"] = $records["count"];
            $data["offset"] = $records["offset"];
        }
        $data['wardList'] = $wardList;
        return view('property/reports/remaining_demand_property_dtl', $data);
    }

    // public function remainingDemandPropertyDtlAjax($action = null) {
    //     if($this->request->getMethod()=='post'){
	// 		try{
    //             $data = arrFilterSanitizeString($this->request->getVar());
    //             $sql = $this->PropReports->remainingDemandPropertyDtl($data);
    //             $result = $this->db->query("SELECT generateCSVReports('".$sql."', 'remainingDemandPropertyDtl')");
    //             $filename = $result->getFirstRow("array");
    //             return json_encode($filename);
    //         } catch(Exception $e) {
    //             echo $e;
    //         }
    //     }
    // }

    public function remainingDemandPropertyDtlAjax($action = null) {
        if($this->request->getMethod()=='post'){
			try{
                $data = arrFilterSanitizeString($this->request->getVar());
                $sql = $this->PropReports->remainingDemandPropertyDtl($data);
                $result = $this->db->query($sql)->getResult('array');
                $filename = "writable\genexcel\RemainingDemandPropertyDtl_".date('Y_m_d_H_I_s').".csv";
                $fp = fopen($filename, 'w');
  
                // Loop through file pointer and a line
                fputcsv($fp,array_keys($result[0]));
                foreach ($result as $fields) {
                    fputcsv($fp, $fields);
                }
                
                fclose($fp);
                return json_encode($filename);
            } catch(Exception $e) {
                echo $e;
            }
        }
    }

    public function dmr($action = null) {
        $session = session();
        $ulb_mstr_id = $session->get('ulb_dtl')['ulb_mstr_id'];        
        if($this->request->getMethod()=='post'){
			try{
                $data = arrFilterSanitizeString($this->request->getVar());
                $where = "";
                $lastwhere = "";
                if($data['ward_mstr_id']!='') {
                    $where .= " AND ward_mstr_id=".$data['ward_mstr_id'];
                    $lastwhere .= " WHERE view_ward_mstr.id=".$data['ward_mstr_id']." ";
                }
                $sql = "WITH ULB_LEGACY_DATA AS(
                            SELECT ward_mstr_id, COUNT(*) AS total_ulb_legacy_data
                            FROM tbl_prop_dtl WHERE is_old=1 ".$where." GROUP BY ward_mstr_id
                        ),
                        NEW_ASSESSMENT AS (
                            SELECT ward_mstr_id, COUNT(*) AS total_new_assessment
                            FROM tbl_saf_dtl 
                            INNER JOIN (SELECT prop_dtl_id AS saf_dtl_id FROM tbl_transaction WHERE status=1 AND tran_type='Saf' GROUP BY prop_dtl_id) AS tran_dtl ON tran_dtl.saf_dtl_id=tbl_saf_dtl.id
                            WHERE tbl_saf_dtl.assessment_type='New Assessment' ".$where." GROUP BY tbl_saf_dtl.ward_mstr_id
                        ),
                        RE_ASSESSMENT AS (
                            SELECT ward_mstr_id, COUNT(*) AS total_re_assessment
                            FROM tbl_saf_dtl 
                            INNER JOIN (SELECT prop_dtl_id AS saf_dtl_id FROM tbl_transaction WHERE status=1 AND tran_type='Saf' GROUP BY prop_dtl_id) AS tran_dtl ON tran_dtl.saf_dtl_id=tbl_saf_dtl.id
                            WHERE tbl_saf_dtl.assessment_type='Reassessment' ".$where." GROUP BY ward_mstr_id
                        ),
                        MUTATION AS (
                            SELECT ward_mstr_id, COUNT(*) AS total_mutation
                            FROM tbl_saf_dtl 
                            INNER JOIN (SELECT prop_dtl_id AS saf_dtl_id FROM tbl_transaction WHERE status=1 AND tran_type='Saf' GROUP BY prop_dtl_id) AS tran_dtl ON tran_dtl.saf_dtl_id=tbl_saf_dtl.id
                            WHERE tbl_saf_dtl.assessment_type='Mutation' ".$where." GROUP BY ward_mstr_id
                        ),
                        TOTAL_SAF AS (
                            SELECT ward_mstr_id, COUNT(*) AS total_saf
                            FROM tbl_saf_dtl 
                            INNER JOIN (SELECT prop_dtl_id AS saf_dtl_id FROM tbl_transaction WHERE status=1 AND tran_type='Saf' GROUP BY prop_dtl_id) AS tran_dtl ON tran_dtl.saf_dtl_id=tbl_saf_dtl.id
                            WHERE tbl_saf_dtl.assessment_type IN ('New Assessment', 'Reassessment', 'Mutation') ".$where." GROUP BY ward_mstr_id
                        ),
                        TO_BE_REASSESSMENT AS (
                            SELECT ward_mstr_id, COUNT(*) AS total_to_be_reassessed FROM tbl_prop_dtl
                            WHERE NOT EXISTS (SELECT saf_dtl_id FROM tbl_saf_dtl WHERE tbl_saf_dtl.id=tbl_prop_dtl.saf_dtl_id)
                            AND status=1 AND is_old=1 ".$where." GROUP BY ward_mstr_id
                        ),
                        FULLY_DIGITIZED_SAF AS (
                            SELECT ward_mstr_id, COUNT(*) AS fully_digitized_saf
                            FROM tbl_saf_dtl 
                            INNER JOIN (SELECT saf_dtl_id FROM tbl_saf_doc_dtl WHERE status=1 GROUP BY saf_dtl_id) AS doc_dtl ON doc_dtl.saf_dtl_id=tbl_saf_dtl.id
                            INNER JOIN (SELECT saf_dtl_id FROM tbl_level_pending_dtl WHERE status=1 GROUP BY saf_dtl_id) AS level_dtl ON level_dtl.saf_dtl_id=tbl_saf_dtl.id
                            WHERE tbl_saf_dtl.status=1 ".$where." GROUP BY ward_mstr_id
                        ),
                        SAM AS (
                            SELECT tbl_saf_dtl.ward_mstr_id, COUNT(*) AS total_sam FROM tbl_saf_dtl
                            INNER JOIN tbl_saf_memo_dtl ON tbl_saf_memo_dtl.saf_dtl_id=tbl_saf_dtl.id
                            WHERE tbl_saf_memo_dtl.memo_type='SAM' ".$where." GROUP BY tbl_saf_dtl.ward_mstr_id
                        ),
                        GEO_TAGGING AS (
                            SELECT ward_mstr_id, COUNT(*) AS total_geo_tagging FROM tbl_saf_dtl
                            INNER JOIN (SELECT geotag_dtl_id FROM tbl_saf_geotag_upload_dtl WHERE status=1 GROUP BY geotag_dtl_id) AS geotag_dtl ON geotag_dtl.geotag_dtl_id=tbl_saf_dtl.id
                            WHERE tbl_saf_dtl.status=1 ".$where." GROUP BY ward_mstr_id
                        ),
                        PURE_RESIDENTIAL AS (
                            SELECT
                                ward_mstr_id, COUNT(*) AS total_pure_residencial
                            FROM tbl_prop_dtl
                            LEFT JOIN (SELECT prop_dtl_id ,'PURE_RESIDENTIAL' AS holding_type FROM tbl_prop_floor_details WHERE usage_type_mstr_id IN (1) AND usage_type_mstr_id!=11 AND status=1 GROUP BY prop_dtl_id)
                                AS PURE_RESIDENTIAL ON PURE_RESIDENTIAL.prop_dtl_id=tbl_prop_dtl.id
                            LEFT JOIN (SELECT prop_dtl_id ,'PURE_COMMERCIAL' AS holding_type FROM tbl_prop_floor_details WHERE usage_type_mstr_id NOT IN (1, 7, 9) AND usage_type_mstr_id!=11 AND status=1 GROUP BY prop_dtl_id)
                                AS PURE_COMMERCIAL ON PURE_COMMERCIAL.prop_dtl_id=tbl_prop_dtl.id
                            LEFT JOIN (SELECT prop_dtl_id ,'PURE_GOVERNMENT' AS holding_type FROM tbl_prop_floor_details WHERE usage_type_mstr_id IN (7,9) AND usage_type_mstr_id!=11 AND status=1 GROUP BY prop_dtl_id)
                                AS PURE_GOVERNMENT ON PURE_GOVERNMENT.prop_dtl_id=tbl_prop_dtl.id
                            WHERE 
                                PURE_RESIDENTIAL.holding_type IS NOT NULL
                                AND PURE_COMMERCIAL.holding_type IS NULL
                                AND PURE_GOVERNMENT.holding_type IS NULL
                                ".$where."
                            GROUP BY tbl_prop_dtl.ward_mstr_id
                        ),
                        PURE_COMMERCIAL AS (
                            SELECT
                                ward_mstr_id, COUNT(*) AS total_pure_commercial
                            FROM tbl_prop_dtl
                            LEFT JOIN (SELECT prop_dtl_id ,'PURE_RESIDENTIAL' AS holding_type FROM tbl_prop_floor_details WHERE usage_type_mstr_id IN (1) AND usage_type_mstr_id!=11 AND status=1 GROUP BY prop_dtl_id)
                                AS PURE_RESIDENTIAL ON PURE_RESIDENTIAL.prop_dtl_id=tbl_prop_dtl.id
                            LEFT JOIN (SELECT prop_dtl_id ,'PURE_COMMERCIAL' AS holding_type FROM tbl_prop_floor_details WHERE usage_type_mstr_id NOT IN (1, 7, 9) AND usage_type_mstr_id!=11 AND status=1 GROUP BY prop_dtl_id)
                                AS PURE_COMMERCIAL ON PURE_COMMERCIAL.prop_dtl_id=tbl_prop_dtl.id
                            LEFT JOIN (SELECT prop_dtl_id ,'PURE_GOVERNMENT' AS holding_type FROM tbl_prop_floor_details WHERE usage_type_mstr_id IN (7,9) AND usage_type_mstr_id!=11 AND status=1 GROUP BY prop_dtl_id)
                                AS PURE_GOVERNMENT ON PURE_GOVERNMENT.prop_dtl_id=tbl_prop_dtl.id
                            WHERE 
                                PURE_RESIDENTIAL.holding_type IS NULL
                                AND PURE_COMMERCIAL.holding_type IS NOT NULL
                                AND PURE_GOVERNMENT.holding_type IS NULL
                                ".$where."
                            GROUP BY tbl_prop_dtl.ward_mstr_id
                        ),
                        PURE_GOVERNMENT AS (
                            SELECT
                                ward_mstr_id, COUNT(*) AS total_pure_government
                            FROM tbl_prop_dtl
                            LEFT JOIN (SELECT prop_dtl_id ,'PURE_RESIDENTIAL' AS holding_type FROM tbl_prop_floor_details WHERE usage_type_mstr_id IN (1) AND usage_type_mstr_id!=11 AND status=1 GROUP BY prop_dtl_id)
                                AS PURE_RESIDENTIAL ON PURE_RESIDENTIAL.prop_dtl_id=tbl_prop_dtl.id
                            LEFT JOIN (SELECT prop_dtl_id ,'PURE_COMMERCIAL' AS holding_type FROM tbl_prop_floor_details WHERE usage_type_mstr_id NOT IN (1, 7, 9) AND usage_type_mstr_id!=11 AND status=1 GROUP BY prop_dtl_id)
                                AS PURE_COMMERCIAL ON PURE_COMMERCIAL.prop_dtl_id=tbl_prop_dtl.id
                            LEFT JOIN (SELECT prop_dtl_id ,'PURE_GOVERNMENT' AS holding_type FROM tbl_prop_floor_details WHERE usage_type_mstr_id IN (7,9) AND usage_type_mstr_id!=11 AND status=1 GROUP BY prop_dtl_id)
                                AS PURE_GOVERNMENT ON PURE_GOVERNMENT.prop_dtl_id=tbl_prop_dtl.id
                            WHERE 
                                PURE_RESIDENTIAL.holding_type IS NULL
                                AND PURE_COMMERCIAL.holding_type IS NULL
                                AND PURE_GOVERNMENT.holding_type IS NOT NULL
                                ".$where."
                            GROUP BY tbl_prop_dtl.ward_mstr_id
                        ),
                        MIXED_SAF AS (
                            SELECT
                                ward_mstr_id, COUNT(*) AS total_mix_saf
                            FROM tbl_prop_dtl
                            LEFT JOIN (SELECT prop_dtl_id ,'PURE_RESIDENTIAL' AS holding_type FROM tbl_prop_floor_details WHERE usage_type_mstr_id IN (1) AND usage_type_mstr_id!=11 AND status=1 GROUP BY prop_dtl_id)
                                AS PURE_RESIDENTIAL ON PURE_RESIDENTIAL.prop_dtl_id=tbl_prop_dtl.id
                            LEFT JOIN (SELECT prop_dtl_id ,'PURE_COMMERCIAL' AS holding_type FROM tbl_prop_floor_details WHERE usage_type_mstr_id NOT IN (1, 7, 9) AND usage_type_mstr_id!=11 AND status=1 GROUP BY prop_dtl_id)
                                AS PURE_COMMERCIAL ON PURE_COMMERCIAL.prop_dtl_id=tbl_prop_dtl.id
                            LEFT JOIN (SELECT prop_dtl_id ,'PURE_GOVERNMENT' AS holding_type FROM tbl_prop_floor_details WHERE usage_type_mstr_id IN (7,9) AND usage_type_mstr_id!=11 AND status=1 GROUP BY prop_dtl_id)
                                AS PURE_GOVERNMENT ON PURE_GOVERNMENT.prop_dtl_id=tbl_prop_dtl.id
                            WHERE 
                                (PURE_RESIDENTIAL.holding_type IS NOT NULL AND PURE_COMMERCIAL.holding_type IS NOT NULL)
                                OR (PURE_GOVERNMENT.holding_type IS NOT NULL AND PURE_COMMERCIAL.holding_type IS NOT NULL)
                                OR (PURE_GOVERNMENT.holding_type IS NOT NULL AND PURE_RESIDENTIAL.holding_type IS NOT NULL)
                                ".$where."
                            GROUP BY tbl_prop_dtl.ward_mstr_id
                        ),
                        Backtocitizen as (
                            SELECT ward_mstr_id, COUNT(*) AS total_btc FROM tbl_saf_dtl
                            INNER JOIN (
                                SELECT 
                                    tbl_level_pending_dtl.saf_dtl_id 
                                FROM tbl_level_pending_dtl 
                                INNER JOIN (SELECT MAX(id) AS id, saf_dtl_id FROM tbl_level_pending_dtl GROUP BY saf_dtl_id) AS level_pending_dtl ON level_pending_dtl.id=tbl_level_pending_dtl.id
                                WHERE 
                                    tbl_level_pending_dtl.status=1
                                    AND tbl_level_pending_dtl.verification_status=2
                                    AND tbl_level_pending_dtl.sender_user_type_id=11
                                    AND tbl_level_pending_dtl.receiver_user_type_id=6
                                GROUP BY tbl_level_pending_dtl.saf_dtl_id) AS back_to_citizen_dtl ON back_to_citizen_dtl.saf_dtl_id=tbl_saf_dtl.id 
                            WHERE tbl_saf_dtl.status=1 ".$where." GROUP BY ward_mstr_id
                        ),
                        Final_memo AS (
                            SELECT tbl_saf_dtl.ward_mstr_id, COUNT(*) AS total_FAM_pending FROM tbl_saf_dtl
                            INNER JOIN tbl_saf_memo_dtl ON tbl_saf_memo_dtl.saf_dtl_id=tbl_saf_dtl.id
                            WHERE tbl_saf_memo_dtl.memo_type='FAM' ".$where." GROUP BY tbl_saf_dtl.ward_mstr_id
                        ),
                        VACANT_LAND AS (
                            SELECT ward_mstr_id, COUNT(*) AS total_vacant_land FROM tbl_prop_dtl WHERE prop_type_mstr_id=4 ".$where." GROUP BY ward_mstr_id
                        )
                        SELECT
                            view_ward_mstr.ward_no,
                            COALESCE(ULB_LEGACY_DATA.total_ulb_legacy_data, 0) AS total_ulb_legacy_data_3,
                            COALESCE(NEW_ASSESSMENT.total_new_assessment, 0) AS total_new_assessment_4_1,
                            COALESCE(RE_ASSESSMENT.total_re_assessment, 0) AS total_re_assessment_4_2,
                            COALESCE(MUTATION.total_mutation, 0) AS total_mutation_4_3,
                            COALESCE(TOTAL_SAF.total_saf, 0) AS total_saf_5,
                            COALESCE(TO_BE_REASSESSMENT.total_to_be_reassessed, 0) AS total_to_be_reassessed_6,
                            SUM(COALESCE(TOTAL_SAF.total_saf, 0)+COALESCE(TO_BE_REASSESSMENT.total_to_be_reassessed, 0)) AS total_holding_7,
                            ROUND(ULB_LEGACY_DATA.total_ulb_legacy_data::numeric/TO_BE_REASSESSMENT.total_to_be_reassessed::numeric , 2) AS non_assessed_percentage_8,
                            COALESCE(FULLY_DIGITIZED_SAF.fully_digitized_saf, 0) AS fully_digitized_saf_9,
                            COALESCE(SAM.total_sam, 0) AS total_sam_10,
                            ROUND(SAM.total_sam::numeric/FULLY_DIGITIZED_SAF.fully_digitized_saf::numeric, 2) AS sam_percentage_11,
                            GEO_TAGGING.total_geo_tagging AS tota_geo_tagging_12,
                            ROUND(GEO_TAGGING.total_geo_tagging::numeric/FULLY_DIGITIZED_SAF.fully_digitized_saf::numeric, 2) AS geo_tagging_percentage_13,	
                            COALESCE(PURE_COMMERCIAL.total_pure_commercial, 0) AS total_pure_commercial_14_1,
                            COALESCE(MIXED_SAF.total_mix_saf, 0) AS total_mix_saf_14_2,
                            COALESCE(PURE_GOVERNMENT.total_pure_government, 0) AS total_pure_government_14_3,
                            COALESCE(VACANT_LAND.total_vacant_land, 0) AS total_vacant_land_14_4,
                            COALESCE(PURE_RESIDENTIAL.total_pure_residencial, 0) AS total_pure_residencial_14_5,
                            COALESCE(Backtocitizen.total_btc, 0) AS total_btc,
                            COALESCE(Final_memo.total_FAM_pending, 0) AS total_FAM_pending
                        FROM view_ward_mstr
                        LEFT JOIN ULB_LEGACY_DATA ON ULB_LEGACY_DATA.ward_mstr_id=view_ward_mstr.id
                        LEFT JOIN NEW_ASSESSMENT ON NEW_ASSESSMENT.ward_mstr_id=view_ward_mstr.id
                        LEFT JOIN RE_ASSESSMENT ON RE_ASSESSMENT.ward_mstr_id=view_ward_mstr.id
                        LEFT JOIN MUTATION ON MUTATION.ward_mstr_id=view_ward_mstr.id
                        LEFT JOIN TOTAL_SAF ON TOTAL_SAF.ward_mstr_id=view_ward_mstr.id
                        LEFT JOIN TO_BE_REASSESSMENT ON TO_BE_REASSESSMENT.ward_mstr_id=view_ward_mstr.id
                        LEFT JOIN FULLY_DIGITIZED_SAF ON FULLY_DIGITIZED_SAF.ward_mstr_id=view_ward_mstr.id
                        LEFT JOIN SAM ON SAM.ward_mstr_id=view_ward_mstr.id
                        LEFT JOIN GEO_TAGGING ON GEO_TAGGING.ward_mstr_id=view_ward_mstr.id
                        LEFT JOIN PURE_RESIDENTIAL ON PURE_RESIDENTIAL.ward_mstr_id=view_ward_mstr.id
                        LEFT JOIN PURE_COMMERCIAL ON PURE_COMMERCIAL.ward_mstr_id=view_ward_mstr.id
                        LEFT JOIN PURE_GOVERNMENT ON PURE_GOVERNMENT.ward_mstr_id=view_ward_mstr.id
                        LEFT JOIN MIXED_SAF ON MIXED_SAF.ward_mstr_id=view_ward_mstr.id
                        LEFT JOIN VACANT_LAND ON VACANT_LAND.ward_mstr_id=view_ward_mstr.id
                        LEFT Join Backtocitizen on Backtocitizen.ward_mstr_id=view_ward_mstr.id
                        Left Join Final_memo on Final_memo.ward_mstr_id=view_ward_mstr.id
                        ".$lastwhere."
                        GROUP BY 
                            view_ward_mstr.ward_no,
                            ULB_LEGACY_DATA.total_ulb_legacy_data,
                            NEW_ASSESSMENT.total_new_assessment,
                            RE_ASSESSMENT.total_re_assessment,
                            MUTATION.total_mutation,
                            TOTAL_SAF.total_saf,
                            TO_BE_REASSESSMENT.total_to_be_reassessed,
                            FULLY_DIGITIZED_SAF.fully_digitized_saf,
                            SAM.total_sam,
                            GEO_TAGGING.total_geo_tagging,
                            PURE_RESIDENTIAL.total_pure_residencial,
                            PURE_COMMERCIAL.total_pure_commercial,
                            PURE_GOVERNMENT.total_pure_government,
                            MIXED_SAF.total_mix_saf,
                            VACANT_LAND.total_vacant_land,
                            Backtocitizen.total_btc,
                            Final_memo.total_FAM_pending,
                            view_ward_mstr.id
                        ORDER BY (substring(view_ward_mstr.ward_no, '^[0-9]+'))::int, ward_no";
                        $data['result'] = $this->model_datatable->getRecords($sql);
            } catch (Exception $e) { }
        }
        $wardList = $this->model_ward_mstr->getWardList(['ulb_mstr_id'=>$ulb_mstr_id]);
        $data['wardList'] = $wardList;
        return view('property/reports/dmr_report', $data);
    }

    public function dmr_excel($ward_mstr_id = null) {
        $session = session();
        $ulb_mstr_id = $session->get('ulb_dtl')['ulb_mstr_id'];        
        if(!is_null($ward_mstr_id)){
			try{
                $where = "";
                $lastwhere = "";
                if($ward_mstr_id!='ALL') {
                    $where .= " AND ward_mstr_id=".$ward_mstr_id;
                    $lastwhere .= " WHERE view_ward_mstr.id=".$ward_mstr_id." ";
                }
                $sql = "WITH ULB_LEGACY_DATA AS(
                            SELECT ward_mstr_id, COUNT(*) AS total_ulb_legacy_data
                            FROM tbl_prop_dtl WHERE is_old=1 ".$where." GROUP BY ward_mstr_id
                        ),
                        NEW_ASSESSMENT AS (
                            SELECT ward_mstr_id, COUNT(*) AS total_new_assessment
                            FROM tbl_saf_dtl 
                            INNER JOIN (SELECT prop_dtl_id AS saf_dtl_id FROM tbl_transaction WHERE status=1 AND tran_type='Saf' GROUP BY prop_dtl_id) AS tran_dtl ON tran_dtl.saf_dtl_id=tbl_saf_dtl.id
                            WHERE tbl_saf_dtl.assessment_type='New Assessment' ".$where." GROUP BY tbl_saf_dtl.ward_mstr_id
                        ),
                        RE_ASSESSMENT AS (
                            SELECT ward_mstr_id, COUNT(*) AS total_re_assessment
                            FROM tbl_saf_dtl 
                            INNER JOIN (SELECT prop_dtl_id AS saf_dtl_id FROM tbl_transaction WHERE status=1 AND tran_type='Saf' GROUP BY prop_dtl_id) AS tran_dtl ON tran_dtl.saf_dtl_id=tbl_saf_dtl.id
                            WHERE tbl_saf_dtl.assessment_type='Reassessment' ".$where." GROUP BY ward_mstr_id
                        ),
                        MUTATION AS (
                            SELECT ward_mstr_id, COUNT(*) AS total_mutation
                            FROM tbl_saf_dtl 
                            INNER JOIN (SELECT prop_dtl_id AS saf_dtl_id FROM tbl_transaction WHERE status=1 AND tran_type='Saf' GROUP BY prop_dtl_id) AS tran_dtl ON tran_dtl.saf_dtl_id=tbl_saf_dtl.id
                            WHERE tbl_saf_dtl.assessment_type='Mutation' ".$where." GROUP BY ward_mstr_id
                        ),
                        TOTAL_SAF AS (
                            SELECT ward_mstr_id, COUNT(*) AS total_saf
                            FROM tbl_saf_dtl 
                            INNER JOIN (SELECT prop_dtl_id AS saf_dtl_id FROM tbl_transaction WHERE status=1 AND tran_type='Saf' GROUP BY prop_dtl_id) AS tran_dtl ON tran_dtl.saf_dtl_id=tbl_saf_dtl.id
                            WHERE tbl_saf_dtl.assessment_type IN ('New Assessment', 'Reassessment', 'Mutation') ".$where." GROUP BY ward_mstr_id
                        ),
                        TO_BE_REASSESSMENT AS (
                            SELECT ward_mstr_id, COUNT(*) AS total_to_be_reassessed FROM tbl_prop_dtl
                            WHERE NOT EXISTS (SELECT saf_dtl_id FROM tbl_saf_dtl WHERE tbl_saf_dtl.id=tbl_prop_dtl.saf_dtl_id)
                            AND status=1 AND is_old=1 ".$where." GROUP BY ward_mstr_id
                        ),
                        FULLY_DIGITIZED_SAF AS (
                            SELECT ward_mstr_id, COUNT(*) AS fully_digitized_saf
                            FROM tbl_saf_dtl 
                            INNER JOIN (SELECT saf_dtl_id FROM tbl_saf_doc_dtl WHERE status=1 GROUP BY saf_dtl_id) AS doc_dtl ON doc_dtl.saf_dtl_id=tbl_saf_dtl.id
                            INNER JOIN (SELECT saf_dtl_id FROM tbl_level_pending_dtl WHERE status=1 GROUP BY saf_dtl_id) AS level_dtl ON level_dtl.saf_dtl_id=tbl_saf_dtl.id
                            WHERE tbl_saf_dtl.status=1 ".$where." GROUP BY ward_mstr_id
                        ),
                        SAM AS (
                            SELECT tbl_saf_dtl.ward_mstr_id, COUNT(*) AS total_sam FROM tbl_saf_dtl
                            INNER JOIN tbl_saf_memo_dtl ON tbl_saf_memo_dtl.saf_dtl_id=tbl_saf_dtl.id
                            WHERE tbl_saf_memo_dtl.memo_type='SAM' ".$where." GROUP BY tbl_saf_dtl.ward_mstr_id
                        ),
                        GEO_TAGGING AS (
                            SELECT ward_mstr_id, COUNT(*) AS total_geo_tagging FROM tbl_saf_dtl
                            INNER JOIN (SELECT geotag_dtl_id FROM tbl_saf_geotag_upload_dtl WHERE status=1 GROUP BY geotag_dtl_id) AS geotag_dtl ON geotag_dtl.geotag_dtl_id=tbl_saf_dtl.id
                            WHERE tbl_saf_dtl.status=1 ".$where." GROUP BY ward_mstr_id
                        ),
                        PURE_RESIDENTIAL AS (
                            SELECT
                                ward_mstr_id, COUNT(*) AS total_pure_residencial
                            FROM tbl_prop_dtl
                            LEFT JOIN (SELECT prop_dtl_id ,'PURE_RESIDENTIAL' AS holding_type FROM tbl_prop_floor_details WHERE usage_type_mstr_id IN (1) AND usage_type_mstr_id!=11 AND status=1 GROUP BY prop_dtl_id)
                                AS PURE_RESIDENTIAL ON PURE_RESIDENTIAL.prop_dtl_id=tbl_prop_dtl.id
                            LEFT JOIN (SELECT prop_dtl_id ,'PURE_COMMERCIAL' AS holding_type FROM tbl_prop_floor_details WHERE usage_type_mstr_id NOT IN (1, 7, 9) AND usage_type_mstr_id!=11 AND status=1 GROUP BY prop_dtl_id)
                                AS PURE_COMMERCIAL ON PURE_COMMERCIAL.prop_dtl_id=tbl_prop_dtl.id
                            LEFT JOIN (SELECT prop_dtl_id ,'PURE_GOVERNMENT' AS holding_type FROM tbl_prop_floor_details WHERE usage_type_mstr_id IN (7,9) AND usage_type_mstr_id!=11 AND status=1 GROUP BY prop_dtl_id)
                                AS PURE_GOVERNMENT ON PURE_GOVERNMENT.prop_dtl_id=tbl_prop_dtl.id
                            WHERE 
                                PURE_RESIDENTIAL.holding_type IS NOT NULL
                                AND PURE_COMMERCIAL.holding_type IS NULL
                                AND PURE_GOVERNMENT.holding_type IS NULL
                                ".$where."
                            GROUP BY tbl_prop_dtl.ward_mstr_id
                        ),
                        PURE_COMMERCIAL AS (
                            SELECT
                                ward_mstr_id, COUNT(*) AS total_pure_commercial
                            FROM tbl_prop_dtl
                            LEFT JOIN (SELECT prop_dtl_id ,'PURE_RESIDENTIAL' AS holding_type FROM tbl_prop_floor_details WHERE usage_type_mstr_id IN (1) AND usage_type_mstr_id!=11 AND status=1 GROUP BY prop_dtl_id)
                                AS PURE_RESIDENTIAL ON PURE_RESIDENTIAL.prop_dtl_id=tbl_prop_dtl.id
                            LEFT JOIN (SELECT prop_dtl_id ,'PURE_COMMERCIAL' AS holding_type FROM tbl_prop_floor_details WHERE usage_type_mstr_id NOT IN (1, 7, 9) AND usage_type_mstr_id!=11 AND status=1 GROUP BY prop_dtl_id)
                                AS PURE_COMMERCIAL ON PURE_COMMERCIAL.prop_dtl_id=tbl_prop_dtl.id
                            LEFT JOIN (SELECT prop_dtl_id ,'PURE_GOVERNMENT' AS holding_type FROM tbl_prop_floor_details WHERE usage_type_mstr_id IN (7,9) AND usage_type_mstr_id!=11 AND status=1 GROUP BY prop_dtl_id)
                                AS PURE_GOVERNMENT ON PURE_GOVERNMENT.prop_dtl_id=tbl_prop_dtl.id
                            WHERE 
                                PURE_RESIDENTIAL.holding_type IS NULL
                                AND PURE_COMMERCIAL.holding_type IS NOT NULL
                                AND PURE_GOVERNMENT.holding_type IS NULL
                                ".$where."
                            GROUP BY tbl_prop_dtl.ward_mstr_id
                        ),
                        PURE_GOVERNMENT AS (
                            SELECT
                                ward_mstr_id, COUNT(*) AS total_pure_government
                            FROM tbl_prop_dtl
                            LEFT JOIN (SELECT prop_dtl_id ,'PURE_RESIDENTIAL' AS holding_type FROM tbl_prop_floor_details WHERE usage_type_mstr_id IN (1) AND usage_type_mstr_id!=11 AND status=1 GROUP BY prop_dtl_id)
                                AS PURE_RESIDENTIAL ON PURE_RESIDENTIAL.prop_dtl_id=tbl_prop_dtl.id
                            LEFT JOIN (SELECT prop_dtl_id ,'PURE_COMMERCIAL' AS holding_type FROM tbl_prop_floor_details WHERE usage_type_mstr_id NOT IN (1, 7, 9) AND usage_type_mstr_id!=11 AND status=1 GROUP BY prop_dtl_id)
                                AS PURE_COMMERCIAL ON PURE_COMMERCIAL.prop_dtl_id=tbl_prop_dtl.id
                            LEFT JOIN (SELECT prop_dtl_id ,'PURE_GOVERNMENT' AS holding_type FROM tbl_prop_floor_details WHERE usage_type_mstr_id IN (7,9) AND usage_type_mstr_id!=11 AND status=1 GROUP BY prop_dtl_id)
                                AS PURE_GOVERNMENT ON PURE_GOVERNMENT.prop_dtl_id=tbl_prop_dtl.id
                            WHERE 
                                PURE_RESIDENTIAL.holding_type IS NULL
                                AND PURE_COMMERCIAL.holding_type IS NULL
                                AND PURE_GOVERNMENT.holding_type IS NOT NULL
                                ".$where."
                            GROUP BY tbl_prop_dtl.ward_mstr_id
                        ),
                        MIXED_SAF AS (
                            SELECT
                                ward_mstr_id, COUNT(*) AS total_mix_saf
                            FROM tbl_prop_dtl
                            LEFT JOIN (SELECT prop_dtl_id ,'PURE_RESIDENTIAL' AS holding_type FROM tbl_prop_floor_details WHERE usage_type_mstr_id IN (1) AND usage_type_mstr_id!=11 AND status=1 GROUP BY prop_dtl_id)
                                AS PURE_RESIDENTIAL ON PURE_RESIDENTIAL.prop_dtl_id=tbl_prop_dtl.id
                            LEFT JOIN (SELECT prop_dtl_id ,'PURE_COMMERCIAL' AS holding_type FROM tbl_prop_floor_details WHERE usage_type_mstr_id NOT IN (1, 7, 9) AND usage_type_mstr_id!=11 AND status=1 GROUP BY prop_dtl_id)
                                AS PURE_COMMERCIAL ON PURE_COMMERCIAL.prop_dtl_id=tbl_prop_dtl.id
                            LEFT JOIN (SELECT prop_dtl_id ,'PURE_GOVERNMENT' AS holding_type FROM tbl_prop_floor_details WHERE usage_type_mstr_id IN (7,9) AND usage_type_mstr_id!=11 AND status=1 GROUP BY prop_dtl_id)
                                AS PURE_GOVERNMENT ON PURE_GOVERNMENT.prop_dtl_id=tbl_prop_dtl.id
                            WHERE 
                                (PURE_RESIDENTIAL.holding_type IS NOT NULL AND PURE_COMMERCIAL.holding_type IS NOT NULL)
                                OR (PURE_GOVERNMENT.holding_type IS NOT NULL AND PURE_COMMERCIAL.holding_type IS NOT NULL)
                                OR (PURE_GOVERNMENT.holding_type IS NOT NULL AND PURE_RESIDENTIAL.holding_type IS NOT NULL)
                                ".$where."
                            GROUP BY tbl_prop_dtl.ward_mstr_id
                        ),
                        Backtocitizen as (
                            SELECT ward_mstr_id, COUNT(*) AS total_btc FROM tbl_saf_dtl
                            INNER JOIN (
                                SELECT 
                                    tbl_level_pending_dtl.saf_dtl_id 
                                FROM tbl_level_pending_dtl 
                                INNER JOIN (SELECT MAX(id) AS id, saf_dtl_id FROM tbl_level_pending_dtl GROUP BY saf_dtl_id) AS level_pending_dtl ON level_pending_dtl.id=tbl_level_pending_dtl.id
                                WHERE 
                                    tbl_level_pending_dtl.status=1
                                    AND tbl_level_pending_dtl.verification_status=2
                                    AND tbl_level_pending_dtl.sender_user_type_id=11
                                    AND tbl_level_pending_dtl.receiver_user_type_id=6
                                GROUP BY tbl_level_pending_dtl.saf_dtl_id) AS back_to_citizen_dtl ON back_to_citizen_dtl.saf_dtl_id=tbl_saf_dtl.id 
                            WHERE tbl_saf_dtl.status=1 ".$where." GROUP BY ward_mstr_id
                        ),
                        Final_memo AS (
                            SELECT tbl_saf_dtl.ward_mstr_id, COUNT(*) AS total_FAM_pending FROM tbl_saf_dtl
                            INNER JOIN tbl_saf_memo_dtl ON tbl_saf_memo_dtl.saf_dtl_id=tbl_saf_dtl.id
                            WHERE tbl_saf_memo_dtl.memo_type='FAM' ".$where." GROUP BY tbl_saf_dtl.ward_mstr_id
                        ),
                        VACANT_LAND AS (
                            SELECT ward_mstr_id, COUNT(*) AS total_vacant_land FROM tbl_prop_dtl WHERE prop_type_mstr_id=4 ".$where." GROUP BY ward_mstr_id
                        )
                        SELECT
                            view_ward_mstr.ward_no,
                            COALESCE(ULB_LEGACY_DATA.total_ulb_legacy_data, 0) AS total_ulb_legacy_data_3,
                            COALESCE(NEW_ASSESSMENT.total_new_assessment, 0) AS total_new_assessment_4_1,
                            COALESCE(RE_ASSESSMENT.total_re_assessment, 0) AS total_re_assessment_4_2,
                            COALESCE(MUTATION.total_mutation, 0) AS total_mutation_4_3,
                            COALESCE(TOTAL_SAF.total_saf, 0) AS total_saf_5,
                            COALESCE(TO_BE_REASSESSMENT.total_to_be_reassessed, 0) AS total_to_be_reassessed_6,
                            SUM(COALESCE(TOTAL_SAF.total_saf, 0)+COALESCE(TO_BE_REASSESSMENT.total_to_be_reassessed, 0)) AS total_holding_7,
                            ROUND(ULB_LEGACY_DATA.total_ulb_legacy_data::numeric/TO_BE_REASSESSMENT.total_to_be_reassessed::numeric , 2) AS non_assessed_percentage_8,
                            COALESCE(FULLY_DIGITIZED_SAF.fully_digitized_saf, 0) AS fully_digitized_saf_9,
                            COALESCE(SAM.total_sam, 0) AS total_sam_10,
                            ROUND(SAM.total_sam::numeric/FULLY_DIGITIZED_SAF.fully_digitized_saf::numeric, 2) AS sam_percentage_11,
                            GEO_TAGGING.total_geo_tagging AS tota_geo_tagging_12,
                            ROUND(GEO_TAGGING.total_geo_tagging::numeric/FULLY_DIGITIZED_SAF.fully_digitized_saf::numeric, 2) AS geo_tagging_percentage_13,	
                            COALESCE(PURE_COMMERCIAL.total_pure_commercial, 0) AS total_pure_commercial_14_1,
                            COALESCE(MIXED_SAF.total_mix_saf, 0) AS total_mix_saf_14_2,
                            COALESCE(PURE_GOVERNMENT.total_pure_government, 0) AS total_pure_government_14_3,
                            COALESCE(VACANT_LAND.total_vacant_land, 0) AS total_vacant_land_14_4,
                            COALESCE(PURE_RESIDENTIAL.total_pure_residencial, 0) AS total_pure_residencial_14_5,
                            COALESCE(Backtocitizen.total_btc, 0) AS total_btc,
                            COALESCE(Final_memo.total_FAM_pending, 0) AS total_FAM_pending
                        FROM view_ward_mstr
                        LEFT JOIN ULB_LEGACY_DATA ON ULB_LEGACY_DATA.ward_mstr_id=view_ward_mstr.id
                        LEFT JOIN NEW_ASSESSMENT ON NEW_ASSESSMENT.ward_mstr_id=view_ward_mstr.id
                        LEFT JOIN RE_ASSESSMENT ON RE_ASSESSMENT.ward_mstr_id=view_ward_mstr.id
                        LEFT JOIN MUTATION ON MUTATION.ward_mstr_id=view_ward_mstr.id
                        LEFT JOIN TOTAL_SAF ON TOTAL_SAF.ward_mstr_id=view_ward_mstr.id
                        LEFT JOIN TO_BE_REASSESSMENT ON TO_BE_REASSESSMENT.ward_mstr_id=view_ward_mstr.id
                        LEFT JOIN FULLY_DIGITIZED_SAF ON FULLY_DIGITIZED_SAF.ward_mstr_id=view_ward_mstr.id
                        LEFT JOIN SAM ON SAM.ward_mstr_id=view_ward_mstr.id
                        LEFT JOIN GEO_TAGGING ON GEO_TAGGING.ward_mstr_id=view_ward_mstr.id
                        LEFT JOIN PURE_RESIDENTIAL ON PURE_RESIDENTIAL.ward_mstr_id=view_ward_mstr.id
                        LEFT JOIN PURE_COMMERCIAL ON PURE_COMMERCIAL.ward_mstr_id=view_ward_mstr.id
                        LEFT JOIN PURE_GOVERNMENT ON PURE_GOVERNMENT.ward_mstr_id=view_ward_mstr.id
                        LEFT JOIN MIXED_SAF ON MIXED_SAF.ward_mstr_id=view_ward_mstr.id
                        LEFT JOIN VACANT_LAND ON VACANT_LAND.ward_mstr_id=view_ward_mstr.id
                        LEFT Join Backtocitizen on Backtocitizen.ward_mstr_id=view_ward_mstr.id
                        Left Join Final_memo on Final_memo.ward_mstr_id=view_ward_mstr.id
                        ".$lastwhere."
                        GROUP BY 
                            view_ward_mstr.ward_no,
                            ULB_LEGACY_DATA.total_ulb_legacy_data,
                            NEW_ASSESSMENT.total_new_assessment,
                            RE_ASSESSMENT.total_re_assessment,
                            MUTATION.total_mutation,
                            TOTAL_SAF.total_saf,
                            TO_BE_REASSESSMENT.total_to_be_reassessed,
                            FULLY_DIGITIZED_SAF.fully_digitized_saf,
                            SAM.total_sam,
                            GEO_TAGGING.total_geo_tagging,
                            PURE_RESIDENTIAL.total_pure_residencial,
                            PURE_COMMERCIAL.total_pure_commercial,
                            PURE_GOVERNMENT.total_pure_government,
                            MIXED_SAF.total_mix_saf,
                            VACANT_LAND.total_vacant_land,
                            Backtocitizen.total_btc,
                            Final_memo.total_FAM_pending,
                            view_ward_mstr.id
                        ORDER BY (substring(view_ward_mstr.ward_no, '^[0-9]+'))::int, ward_no";
                        $records = $this->model_datatable->getRecords($sql);

                        //phpOfficeLoad();
                        $spreadsheet = new Spreadsheet();
                        
                        $activeSheet = $spreadsheet->getActiveSheet();
                                        $activeSheet->setCellValue('A1', 'Ward No.');
                                        $activeSheet->setCellValue('B1', 'ULB Provided Legacy Data (3)');
                                        $activeSheet->setCellValue('C1', 'New Assessment (4.1)');
                                        $activeSheet->setCellValue('D1', 'Re Assessment (4.2)');
                                        $activeSheet->setCellValue('E1', 'Mutation (4.3)');
                                        $activeSheet->setCellValue('F1', 'Total SAF 5=4');
                                        $activeSheet->setCellValue('G1', 'To Be Reassessed From DB(6)');
                                        $activeSheet->setCellValue('H1', 'Total HH as per Records (7=5+6)');
                                        $activeSheet->setCellValue('I1', '% of non reassessed (8=3/6)');
                                        $activeSheet->setCellValue('J1', 'Fully Digitized SAF From DB(9)');
                                        $activeSheet->setCellValue('K1', 'SAM (10)');
                                        $activeSheet->setCellValue('L1', 'SAM % 11=10/9%');
                                        $activeSheet->setCellValue('M1', 'Geo Tagging from DB (12)');
                                        $activeSheet->setCellValue('N1', 'Geo Tagging % (13=12/9)');
                                        $activeSheet->setCellValue('O1', 'pure No of Comm. HH (14.1)');
                                        $activeSheet->setCellValue('P1', 'Mixed (14.2)');
                                        $activeSheet->setCellValue('Q1', 'Govt Building (14.3)');
                                        $activeSheet->setCellValue('R1', 'Vacant Land (14.4)');
                                        $activeSheet->setCellValue('S1', 'Pure No of Res. HH (14.5)');
                                        $activeSheet->setCellValue('T1', 'BTC');
                                        $activeSheet->setCellValue('U1', 'FAM Pending');
                                        $activeSheet->fromArray($records, NULL, 'A2');


                        $activeSheet->setCellValue('B61', '=DSUM(B2:B160,"Profit",B2:B160)');


                        $filename = "decisionMakingReport".date('Ymd-hisa').".xlsx";
                        header('Content-Type: application/vnd.ms-excel');
                        header('Content-Disposition: attachment;filename="'.$filename.'"');
                        header('Cache-Control: max-age=0');
                        $writer = new Xlsx($spreadsheet);
                        $writer->save('php://output');
            } catch (Exception $e) { }
        }
    }

    public function govtSafIndividualDemandCollecton() {
        $session = session();
        $ulb_mstr_id = $session->get('ulb_dtl')['ulb_mstr_id'];   
        if ($this->request->getMethod()=='post') {
			try {
                $data = arrFilterSanitizeString($this->request->getVar());
                $lastwhere = "";
                if($data['ward_mstr_id']!='') {
                    $lastwhere .= " AND tbl_govt_saf_dtl.ward_mstr_id=".$data['ward_mstr_id']." ";
                }
                $sql = "SELECT 
                            view_ward_mstr.ward_no,
                            tbl_govt_saf_dtl.application_no,
                            tbl_govt_saf_dtl.building_colony_name,
                            tbl_govt_saf_dtl.building_colony_address,
                            COALESCE(govt_demand.total_demand, 0) AS total_demand,
                            COALESCE(govt_collection.total_collection, 0) AS total_collection,
                            (COALESCE(govt_demand.total_demand, 0)-COALESCE(govt_collection.total_collection, 0)) AS total_due
                        FROM tbl_govt_saf_dtl
                        INNER JOIN view_ward_mstr ON view_ward_mstr.id=tbl_govt_saf_dtl.ward_mstr_id
                        INNER JOIN (SELECT govt_saf_dtl_id, SUM(amount) AS total_demand FROM tbl_govt_saf_demand_dtl WHERE paid_status IN (0,1) GROUP BY govt_saf_dtl_id) AS govt_demand ON govt_demand.govt_saf_dtl_id=tbl_govt_saf_dtl.id
                        LEFT JOIN (SELECT govt_saf_dtl_id, SUM(amount) AS total_collection FROM tbl_govt_saf_demand_dtl WHERE paid_status=1 GROUP BY govt_saf_dtl_id) AS govt_collection ON govt_collection.govt_saf_dtl_id=tbl_govt_saf_dtl.id
                        WHERE (tbl_govt_saf_dtl.is_csaf2_generated=true OR tbl_govt_saf_dtl.colony_mstr_id IS NULL)".$lastwhere."
                        ORDER BY (substring(view_ward_mstr.ward_no, '^[0-9]+'))::int, ward_no ASC;";
                        $data['result'] = $this->model_datatable->getRecords($sql);
            } catch (Exception $e) { }
        }
        $wardList = $this->model_ward_mstr->getWardList(['ulb_mstr_id'=>$ulb_mstr_id]);
        $data['wardList'] = $wardList;
        return view('property/reports/govt_saf_individual_demand_collecton', $data);
    }

    public function decisionmakingreport() {
        $data = [];
        $sql = "WITH ULB_LEGACY_DATA AS (
                    SELECT
                        ward_mstr_id, COUNT(*) AS total_ulb_legacy_data
                    FROM tbl_prop_dtl WHERE is_Old=1 GROUP BY ward_mstr_id
                ),
                NEW_ASSESSMENT AS (
                    SELECT tbl_saf_dtl.ward_mstr_id, COUNT(*) AS total_new_assessment
                    FROM tbl_Saf_dtl
                    INNER JOIN tbl_transaction ON tbl_transaction.prop_dtl_id=tbl_Saf_dtl.id AND tbl_transaction.tran_type='Saf'
                    WHERE tbl_saf_dtl.assessment_type IN ('New Assessment') GROUP BY tbl_saf_dtl.ward_mstr_id
                ),
                RE_ASSESSMENT AS (
                    SELECT tbl_saf_dtl.ward_mstr_id, COUNT(*) AS total_re_assessment
                    FROM tbl_Saf_dtl
                    INNER JOIN tbl_transaction ON tbl_transaction.prop_dtl_id=tbl_Saf_dtl.id AND tbl_transaction.tran_type='Saf'
                    WHERE tbl_saf_dtl.assessment_type IN ('Reassessment') GROUP BY tbl_saf_dtl.ward_mstr_id
                ),
                MUTATION AS (
                    SELECT tbl_saf_dtl.ward_mstr_id, COUNT(*) AS total_mutation
                    FROM tbl_Saf_dtl
                    INNER JOIN tbl_transaction ON tbl_transaction.prop_dtl_id=tbl_Saf_dtl.id AND tbl_transaction.tran_type='Saf'
                    WHERE tbl_saf_dtl.assessment_type IN ('Mutation') GROUP BY tbl_saf_dtl.ward_mstr_id
                ),
                TOTAL_SAF AS (
                    SELECT tbl_saf_dtl.ward_mstr_id, COUNT(*) AS total_saf
                    FROM tbl_Saf_dtl
                    INNER JOIN tbl_transaction ON tbl_transaction.prop_dtl_id=tbl_Saf_dtl.id AND tbl_transaction.tran_type='Saf'
                    GROUP BY tbl_saf_dtl.ward_mstr_id
                ),
                TO_BE_REASSESSMENT AS (
                    SELECT tbl_saf_dtl.ward_mstr_id, COUNT(*) AS total_to_be_reassessed
                    FROM tbl_Saf_dtl
                    INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.saf_dtl_id=tbl_saf_dtl.id
                    INNER JOIN tbl_transaction ON tbl_transaction.prop_dtl_id=tbl_Saf_dtl.id AND tbl_transaction.tran_type='Saf'
                    GROUP BY tbl_saf_dtl.ward_mstr_id
                ),
                FULLY_DIGITIZED_SAF AS (
                    SELECT tbl_saf_dtl.ward_mstr_id, COUNT(*) AS fully_digitized_saf
                    FROM tbl_Saf_dtl
                    INNER JOIN (
                        SELECT 
                            saf_dtl_id
                        FROM tbl_saf_doc_dtl
                        WHERE status=1
                        GROUP BY saf_dtl_id
                    ) AS digital_dtl ON digital_dtl.saf_dtl_id=tbl_saf_dtl.id
                    GROUP BY tbl_saf_dtl.ward_mstr_id
                ),
                SAM AS (
                    SELECT tbl_saf_dtl.ward_mstr_id, COUNT(*) AS total_sam
                    FROM tbl_Saf_dtl
                    INNER JOIN tbl_saf_memo_dtl ON tbl_saf_memo_dtl.saf_dtl_id=tbl_saf_dtl.id AND tbl_saf_memo_dtl.memo_type='SAM'
                    GROUP BY tbl_saf_dtl.ward_mstr_id
                ),
                GEO_TAGGING AS (
                    SELECT tbl_saf_dtl.ward_mstr_id, COUNT(*) AS tota_geo_tagging
                    FROM tbl_Saf_dtl
                    INNER JOIN (
                        SELECT 
                            geotag_dtl_id
                        FROM tbl_saf_geotag_upload_dtl
                        WHERE status=1
                        GROUP BY geotag_dtl_id
                    ) AS geo_dtl ON geo_dtl.geotag_dtl_id=tbl_saf_dtl.id
                    GROUP BY tbl_saf_dtl.ward_mstr_id
                ),
                PURE_RESIDENTIAL AS (
                    SELECT
                        tbl_prop_dtl.ward_mstr_id, COUNT(*) AS total_pure_residencial
                    FROM tbl_prop_dtl
                    WHERE status=1 AND holding_type='PURE_RESIDENTIAL'
                    GROUP BY tbl_prop_dtl.ward_mstr_id
                ),
                PURE_COMMERCIAL AS (
                    SELECT
                        tbl_prop_dtl.ward_mstr_id, COUNT(*) AS total_pure_commercial
                    FROM tbl_prop_dtl
                    WHERE status=1 AND holding_type='PURE_COMMERCIAL'
                    GROUP BY tbl_prop_dtl.ward_mstr_id
                ),
                PURE_GOVERNMENT AS (
                    SELECT
                        tbl_prop_dtl.ward_mstr_id, COUNT(*) AS total_pure_government
                    FROM tbl_prop_dtl
                    WHERE status=1 AND holding_type='PURE_GOVERNMENT'
                    GROUP BY tbl_prop_dtl.ward_mstr_id
                ),
                MIXED_SAF AS (
                    SELECT
                        tbl_prop_dtl.ward_mstr_id, COUNT(*) AS total_mix_saf
                    FROM tbl_prop_dtl
                    WHERE status=1 AND holding_type='MIX_COMMERCIAL'
                    GROUP BY tbl_prop_dtl.ward_mstr_id
                ),
                Backtocitizen as (
                    SELECT 
                        tbl_saf_dtl.ward_mstr_id, COUNT(*) AS total_btc 
                    FROM tbl_saf_dtl
                    INNER JOIN (
                        SELECT
                            saf_dtl_id
                        FROM tbl_level_sent_back_dtl
                        WHERE tbl_level_sent_back_dtl.sender_user_type_id IS NOT NULL AND tbl_level_sent_back_dtl.status=1
                        GROUP BY saf_dtl_id
                    ) back_dtl on back_dtl.saf_dtl_id=tbl_saf_dtl.id 
                    INNER JOIN view_ward_mstr ON view_ward_mstr.id=tbl_saf_dtl.ward_mstr_id
                    INNER JOIN tbl_prop_type_mstr ON tbl_prop_type_mstr.id=tbl_saf_dtl.prop_type_mstr_id
                    GROUP BY tbl_saf_dtl.ward_mstr_id
                ),
                Final_memo as (
                    SELECT tbl_saf_dtl.ward_mstr_id, COUNT(*) AS total_FAM
                    FROM tbl_Saf_dtl
                    INNER JOIN tbl_saf_memo_dtl ON tbl_saf_memo_dtl.saf_dtl_id=tbl_saf_dtl.id AND tbl_saf_memo_dtl.memo_type='FAM'
                    GROUP BY tbl_saf_dtl.ward_mstr_id
                ),
                VACANT_LAND AS (
                    SELECT 
                        ward_mstr_id, COUNT(*) AS total_vacant_land 
                    FROM tbl_prop_dtl WHERE status=1 AND prop_type_mstr_id=4 GROUP BY ward_mstr_id
                )
                SELECT
                    ROW_NUMBER() OVER (
                    ORDER BY view_ward_mstr.id
                ) row_num,
                    view_Ward_mstr.ward_no,
                    COALESCE(ULB_LEGACY_DATA.total_ulb_legacy_data,0) AS total_ulb_legacy_data_3,
                    COALESCE(NEW_ASSESSMENT.total_new_assessment,0) AS total_new_assessment_4_1,
                    COALESCE(RE_ASSESSMENT.total_re_assessment,0) AS total_re_assessment_4_2,
                    COALESCE(MUTATION.total_mutation,0) AS total_mutation_4_3,
                    COALESCE(TOTAL_SAF.total_saf,0) AS total_saf_5,
                    COALESCE(TO_BE_REASSESSMENT.total_to_be_reassessed,0) AS total_to_be_reassessed_6,
                    SUM(COALESCE(TOTAL_SAF.total_saf, 0)+COALESCE(TO_BE_REASSESSMENT.total_to_be_reassessed, 0)) AS total_holding_7,
                    ROUND(COALESCE(ULB_LEGACY_DATA.total_ulb_legacy_data,0))/ROUND((TO_BE_REASSESSMENT.total_to_be_reassessed), 2) AS non_assessed_percentage_8,
                    COALESCE(FULLY_DIGITIZED_SAF.fully_digitized_saf,0) AS fully_digitized_saf_9,
                    COALESCE(SAM.total_sam, 0) AS total_sam_10,
                    ROUND((SAM.total_sam*100/TOTAL_SAF.total_saf), 2) AS sam_percentage_11,
                    COALESCE(GEO_TAGGING.tota_geo_tagging,0) AS tota_geo_tagging_12,
                    ROUND((GEO_TAGGING.tota_geo_tagging*100)/(TOTAL_SAF.total_saf), 2) AS geo_tagging_percentage_13,	
                    COALESCE(PURE_COMMERCIAL.total_pure_commercial,0) AS total_pure_commercial_14_1,
                    COALESCE(MIXED_SAF.total_mix_saf,0) AS total_mix_saf_14_2,
                    COALESCE(PURE_GOVERNMENT.total_pure_government,0) AS total_pure_government_14_3,
                    COALESCE(VACANT_LAND.total_vacant_land,0) AS total_vacant_land_14_4,
                    COALESCE(PURE_RESIDENTIAL.total_pure_residencial,0) AS total_pure_residencial_14_5,
                    COALESCE(Backtocitizen.total_btc,0) as total_btc,
                    COALESCE(Final_memo.total_FAM,0) as total_FAM
                FROM view_Ward_mstr
                LEFT JOIN ULB_LEGACY_DATA ON ULB_LEGACY_DATA.ward_mstr_id=view_ward_mstr.id
                LEFT JOIN NEW_ASSESSMENT ON NEW_ASSESSMENT.ward_mstr_id=view_ward_mstr.id
                LEFT JOIN RE_ASSESSMENT ON RE_ASSESSMENT.ward_mstr_id=view_ward_mstr.id
                LEFT JOIN MUTATION ON MUTATION.ward_mstr_id=view_ward_mstr.id
                LEFT JOIN TOTAL_SAF ON TOTAL_SAF.ward_mstr_id=view_ward_mstr.id
                LEFT JOIN TO_BE_REASSESSMENT ON TO_BE_REASSESSMENT.ward_mstr_id=view_ward_mstr.id
                LEFT JOIN FULLY_DIGITIZED_SAF ON FULLY_DIGITIZED_SAF.ward_mstr_id=view_ward_mstr.id
                LEFT JOIN SAM ON SAM.ward_mstr_id=view_ward_mstr.id
                LEFT JOIN GEO_TAGGING ON GEO_TAGGING.ward_mstr_id=view_ward_mstr.id
                LEFT JOIN PURE_RESIDENTIAL ON PURE_RESIDENTIAL.ward_mstr_id=view_ward_mstr.id
                LEFT JOIN PURE_COMMERCIAL ON PURE_COMMERCIAL.ward_mstr_id=view_ward_mstr.id
                LEFT JOIN PURE_GOVERNMENT ON PURE_GOVERNMENT.ward_mstr_id=view_ward_mstr.id
                LEFT JOIN MIXED_SAF ON MIXED_SAF.ward_mstr_id=view_ward_mstr.id
                LEFT JOIN VACANT_LAND ON VACANT_LAND.ward_mstr_id=view_ward_mstr.id
                LEFT Join Backtocitizen on Backtocitizen.ward_mstr_id=view_ward_mstr.id
                Left Join Final_memo on Final_memo.ward_mstr_id=view_ward_mstr.id
                GROUP BY 
                    view_ward_mstr.ward_no,
                    ULB_LEGACY_DATA.total_ulb_legacy_data,
                    NEW_ASSESSMENT.total_new_assessment,
                    RE_ASSESSMENT.total_re_assessment,
                    MUTATION.total_mutation,
                    TOTAL_SAF.total_saf,
                    TO_BE_REASSESSMENT.total_to_be_reassessed,
                    FULLY_DIGITIZED_SAF.fully_digitized_saf,
                    SAM.total_sam,
                    GEO_TAGGING.tota_geo_tagging,
                    PURE_RESIDENTIAL.total_pure_residencial,
                    PURE_COMMERCIAL.total_pure_commercial,
                    PURE_GOVERNMENT.total_pure_government,
                    MIXED_SAF.total_mix_saf,
                    VACANT_LAND.total_vacant_land,
                    Backtocitizen.total_btc,
                    Final_memo.total_FAM,
                    view_Ward_mstr.id
                ORDER BY (substring(ward_no, '^[0-9]+'))::int,ward_no";
            //print_var($sql);
            if ($data["report"] = $this->db->query($sql)->getResultArray()) {
                return view('property/reports/decisionmakingreport', $data);
            }
        return view('property/reports/decisionmakingreport', $data);
    }

    public function propDCB() {
        
        $currentFY = getFY();
        $currentFyID = $this->model_fy_mstr->getFyByFy(['fy'=>$currentFY])['id'];
        $fyear = explode('-',$currentFY);
        
        $fromYear2 = "";
        $fromYear = $fyear[0].'-04-01';
        $uptoYear = $fyear[1].'-03-31';
        if($currentFyID==54){$fromYear2 = ($fyear[0]-1).'-04-01';}
        $sql = "WITH arralldemand AS (
                    SELECT SUM((amount-adjust_amt)-((amount-adjust_amt)*2.92/100)) AS arrdemand
                    FROM tbl_prop_demand
                    INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id 
                    WHERE tbl_prop_dtl.status=1 AND tbl_prop_demand.status=1 and char_length(tbl_prop_dtl.new_holding_no)>0 AND tbl_prop_demand.fy_mstr_id<'".($currentFyID-1)."' AND tbl_prop_demand.paid_status in(0,1) AND due_date is not null
                ),
                PREV_COLLECTION AS (
                    SELECT 
                        SUM(COALESCE(tbl_collection.amount, 0)) AS arrear_collection_amount
                    FROM tbl_prop_dtl
                    INNER JOIN tbl_collection on tbl_collection.prop_dtl_id=tbl_prop_dtl.id AND tbl_collection.created_on::date < '".$fromYear2."'
                    WHERE tbl_prop_dtl.status=1 and tbl_collection.status=1 and char_length(tbl_prop_dtl.new_holding_no)>0 
                ),
                currdemand AS (
                    SELECT SUM(amount-adjust_amt) AS crrdemand
                    FROM tbl_prop_demand
                    INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id 
                    WHERE tbl_prop_dtl.status=1 AND tbl_prop_demand.status=1 and char_length(tbl_prop_dtl.new_holding_no)>0 AND tbl_prop_demand.paid_status IN (0,1) 
                    and tbl_prop_demand.due_date is not null AND tbl_prop_demand.fy_mstr_id='".$currentFyID."'
                ), 
                arrcollect AS (
                    SELECT 
                        SUM(tbl_prop_demand.amount-tbl_prop_demand.adjust_amt) AS arrcoll
                    FROM tbl_prop_demand
                    INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id
                    INNER JOIN tbl_collection on tbl_collection.prop_demand_id=tbl_prop_demand.id AND (tbl_collection.created_on::date between '".$fromYear."' and '".$uptoYear."') 
                    WHERE tbl_prop_dtl.status=1 and tbl_prop_demand.status=1 and char_length(tbl_prop_dtl.new_holding_no)>0 and tbl_prop_demand.fy_mstr_id<'".$currentFyID."' AND tbl_prop_demand.paid_status=1
                ),
                currcollect AS (
                    SELECT SUM(tbl_prop_demand.amount-tbl_prop_demand.adjust_amt) AS currcoll
                    FROM tbl_prop_dtl
                    INNER JOIN tbl_prop_demand ON tbl_prop_demand.prop_dtl_id =tbl_prop_dtl.id
                    INNER JOIN tbl_collection on tbl_collection.prop_demand_id=tbl_prop_demand.id AND (tbl_collection.created_on::date between '".$fromYear."' and '".$uptoYear."') 
                    WHERE 
                        tbl_prop_dtl.status=1 and char_length(tbl_prop_dtl.new_holding_no)>0
                        and tbl_prop_demand.fy_mstr_id='".$currentFyID."' 
                        and tbl_prop_demand.paid_status=1 AND tbl_prop_demand.status=1
                ),
                ADVANCE_AMOUNT AS (
                    SELECT 
                        SUM(amount) AS adv_amount
                    FROM tbl_prop_dtl
                    INNER JOIN (
                        select prop_dtl_id, sum(amount) as amount from tbl_advance_mstr where tbl_advance_mstr.status=1 
                        AND (tbl_advance_mstr.created_on::date between '2023-03-01' and '".$uptoYear."') and module='Property' and user_id=1
                        GROUP BY prop_dtl_id
                    ) tbl_advance_mstr ON tbl_prop_dtl.id=tbl_advance_mstr.prop_dtl_id
                    WHERE tbl_prop_dtl.status=1 and char_length(tbl_prop_dtl.new_holding_no)>0
                )
                select 
                    (cast((arralldemand.arrdemand) as numeric(36,2)) -cast((PREV_COLLECTION.arrear_collection_amount) as numeric(36,2))) as arrear_demand,
                    cast(currdemand.crrdemand as numeric(36,2)) as current_demand,
                    cast(arrcollect.arrcoll as numeric(36,2)) as arear_collection,
                    cast(currcollect.currcoll as numeric(36,2)) as current_collection,
                    (cast(arralldemand.arrdemand as numeric(36,2))-cast(arrcollect.arrcoll as numeric(36,2))-cast((PREV_COLLECTION.arrear_collection_amount) as numeric(36,2))) as arearbalance,
                    (cast(currdemand.crrdemand as numeric(36,2))-cast(currcollect.currcoll as numeric(36,2))-cast(ADVANCE_AMOUNT.adv_amount as numeric(36,2))) as curbalance,
                    COALESCE(cast(ADVANCE_AMOUNT.adv_amount as numeric(36,2)),0) as advance_amount
                from arralldemand, currdemand, arrcollect, currcollect, ADVANCE_AMOUNT, PREV_COLLECTION;";
        
        /*$sql = "WITH arralldemand AS (
                    SELECT SUM(amount) AS arrdemand
                    FROM tbl_prop_demand
                    INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id 
                        AND tbl_prop_dtl.status=1 AND tbl_prop_demand.status=1 AND tbl_prop_demand.paid_status IN (0,1) AND due_date<='2022-03-31'
                ),
                arrcoll AS (
                    SELECT SUM(amount) AS arrcoldeman
                    FROM tbl_transaction
                    INNER JOIN tbl_collection ON tbl_transaction.id=tbl_collection.transaction_id 
                        AND tbl_transaction.tran_type='Property' AND tbl_transaction.status IN (1, 2) AND tbl_transaction.tran_date<='2022-03-31'
                ),
                currdemand AS (
                    SELECT SUM(amount) AS crrdemand
                    FROM tbl_prop_demand
                    INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id 
                    WHERE tbl_prop_dtl.status=1 AND tbl_prop_demand.status=1 AND tbl_prop_demand.paid_status IN (0,1) and char_length(tbl_prop_dtl.new_holding_no)>0 and fy_mstr_id=53
                ),
                arrcollect AS (
                    SELECT SUM(tbl_collection.amount) AS arrcoll
                    FROM tbl_transaction
                    INNER JOIN tbl_collection ON tbl_collection.transaction_id=tbl_transaction.id
                    INNER JOIN tbl_prop_demand ON tbl_prop_demand.id =tbl_collection.prop_demand_id
                        AND tbl_transaction.tran_type='Property' AND tbl_transaction.status!=0
                    WHERE 
                        tbl_prop_demand.due_date<='2022-03-31'
                        AND tbl_transaction.tran_date BETWEEN '2022-04-01' AND '2023-03-31'
                ),
                currcollect AS (
                    SELECT SUM(tbl_collection.amount) AS currcoll
                    FROM tbl_transaction
                    INNER JOIN tbl_collection ON tbl_collection.transaction_id=tbl_transaction.id
                    INNER JOIN tbl_prop_demand ON tbl_prop_demand.id =tbl_collection.prop_demand_id
                        AND tbl_transaction.tran_type='Property' AND tbl_transaction.status!=0
                    WHERE 
                        tbl_prop_demand.fyear='2022-2023'
                        AND tbl_transaction.tran_date BETWEEN '2022-04-01' AND '2023-03-31'
                )
                select 
                    cast((arralldemand.arrdemand-arrcoll.arrcoldeman) as numeric(36,2)) as arrear_demand,
                    cast(currdemand.crrdemand as numeric(36,2)) as current_demand,
                    cast(arrcollect.arrcoll as numeric(36,2)) as arear_collection,
                    cast(currcollect.currcoll as numeric(36,2)) as current_collection,
                    (cast((arralldemand.arrdemand-arrcoll.arrcoldeman) as numeric(36,2))-cast(arrcollect.arrcoll as numeric(36,2))) as arearbalance,
                    (cast(currdemand.crrdemand as numeric(36,2))-cast(currcollect.currcoll as numeric(36,2))) as curbalance
                from arralldemand, arrcoll, currdemand, arrcollect, currcollect;";*/
        
        if ($result = $this->db->query($sql)->getFirstRow("array")) {
            $data["result"] = $result;
        } else {
            $data = [];
        }
        return view('property/reports/prop_dcb', $data);
    }

    public function govtDCB() {
        $currentFY = getFY();
        $currentFyID = $this->model_fy_mstr->getFyByFy(['fy'=>$currentFY])['id'];
        $fyear = explode('-',$currentFY);
                    
        $fromYear = $fyear[0].'-04-01';
        $uptoYear = $fyear[1].'-03-31';
        
    	$sql = "with arralldemand as (
                    select 
                        sum(amount) as arrdemand 
                    from tbl_govt_saf_demand_dtl
                    inner join tbl_govt_saf_dtl on tbl_govt_saf_demand_dtl.govt_saf_dtl_id=tbl_govt_saf_dtl.id and tbl_govt_saf_dtl.status=1 
                    where  
                        tbl_govt_saf_demand_dtl.paid_status=0 
                        and tbl_govt_saf_demand_dtl.due_date<'".$fromYear."'
                        and tbl_govt_saf_demand_dtl.status>0
                ),
                arrcoll as (
                    select 
                        sum(tbl_govt_saf_collection_dtl.amount) as arrcoldeman 
                    from tbl_govt_saf_transaction
                    inner join tbl_govt_saf_collection_dtl on tbl_govt_saf_transaction.id=tbl_govt_saf_collection_dtl.govt_saf_transaction_id 
                    inner join tbl_govt_saf_demand_dtl on tbl_govt_saf_demand_dtl.id=tbl_govt_saf_collection_dtl.govt_saf_demand_dtl_id 
                    where 
                        (tbl_govt_saf_transaction.tran_date between '".$fromYear."' and '".$uptoYear."')
                        and tbl_govt_saf_demand_dtl.due_date<'".$fromYear."'
                        and tbl_govt_saf_transaction.status>0
                ),
                currdemand as (
                    select 
                        sum(amount) as crrdemand 
                    from tbl_govt_saf_demand_dtl
                    inner join tbl_govt_saf_dtl on tbl_govt_saf_demand_dtl.govt_saf_dtl_id=tbl_govt_saf_dtl.id and tbl_govt_saf_dtl.status=1 
                    where 
                        tbl_govt_saf_demand_dtl.due_date between '".$fromYear."' and '".$uptoYear."'
                        and tbl_govt_saf_demand_dtl.status>0
                ),
                arrcollect as(
                    select 
                        sum(tbl_govt_saf_collection_dtl.amount) as arrcoll 
                    from tbl_govt_saf_transaction
                    inner join tbl_govt_saf_collection_dtl on tbl_govt_saf_transaction.id=tbl_govt_saf_collection_dtl.govt_saf_transaction_id 
                    inner join tbl_govt_saf_demand_dtl on tbl_govt_saf_collection_dtl.govt_saf_demand_dtl_id=tbl_govt_saf_demand_dtl.id 
                    where 
                        tbl_govt_saf_demand_dtl.due_date<'".$fromYear."' 
                        and tbl_govt_saf_transaction.status>0 and tbl_govt_saf_transaction.tran_date between '".$fromYear."' and '".$uptoYear."'
                ),
                currcollect as (
                    select 
                        sum(tbl_govt_saf_collection_dtl.amount) as currcoll 
                    from tbl_govt_saf_transaction
                    inner join tbl_govt_saf_collection_dtl on tbl_govt_saf_transaction.id=tbl_govt_saf_collection_dtl.govt_saf_transaction_id 
                    inner join tbl_govt_saf_demand_dtl on tbl_govt_saf_collection_dtl.govt_saf_demand_dtl_id=tbl_govt_saf_demand_dtl.id 
                    where  
                        tbl_govt_saf_demand_dtl.fyear='".$currentFY."' 
                        and tbl_govt_saf_demand_dtl.paid_status=1
                        and tbl_govt_saf_transaction.status>0 
                        and tbl_govt_saf_transaction.tran_date between '".$fromYear."' and '".$uptoYear."'
                )
                select 
                    (COALESCE(arralldemand.arrdemand,0)-COALESCE(arrcoll.arrcoldeman,0)) as arrear_demand,
                    (COALESCE(currdemand.crrdemand,0)) as current_demand,
                    COALESCE(arrcollect.arrcoll,0) as arear_collection,
                    COALESCE(currcollect.currcoll,0) as current_collection,
                    (COALESCE(arralldemand.arrdemand,0)-COALESCE(arrcoll.arrcoldeman,0))-(COALESCE(arrcollect.arrcoll,0)) as arearbalance,
                    (COALESCE(currdemand.crrdemand,0)-COALESCE(currcollect.currcoll,0)) as Curbalance  
                from arralldemand,arrcoll,currdemand,arrcollect,currcollect";
		if ($result = $this->db->query($sql)->getFirstRow("array")) {
			$data["result"] = $result;
		} else {
			$data = [];
		}

        if(isset($_REQUEST["api"]) && $_REQUEST['api']){
            return $data;
        }

		return view('property/reports/govt_dcb', $data);
    }
	
	
	public function HoldingWithElectricityDetail() {
        try
        {
            $sql = "SELECT 
                    s.saf_no,
                    tb5.ward_no , 
                    p.new_holding_no as holding_no,
                    wname as owner_name,
                    p.prop_address ,
                    wmobile as owner_mobile,
                    p.elect_consumer_no,
                    p.elect_acc_no,
                    p.elect_bind_book_no,
                    p.holding_type,
                    round(f.builtup_area, 2) as builtup_area,
                    pt.property_type 
            FROM tbl_prop_dtl p 
            JOIN tbl_saf_dtl s on p.saf_dtl_id=s.id
            JOIN view_ward_mstr tb5 ON tb5.id = p.ward_mstr_id
            JOIN (
                SELECT 
                    prop_dtl_id,
                    array_to_string(array_agg(owner_name), '','') as wname ,
                    array_to_string(array_agg(mobile_no), '','') as wmobile 
                FROM tbl_prop_owner_detail 
                GROUP BY prop_dtl_id
                ) w on w.prop_dtl_id = p.id
                JOIN (
                    SELECT sum(builtup_area) as builtup_area,prop_dtl_id from tbl_prop_floor_details GROUP BY prop_dtl_id
                ) f ON f.prop_dtl_id = p.id
            JOIN tbl_prop_type_mstr pt ON pt.id = p.prop_type_mstr_id
            WHERE p.status=1 and p.prop_type_mstr_id !=4";
            
            $data = arrFilterSanitizeString($this->request->getVar());
            if($this->request->getMethod()=='post' && $data['excel'] == 1){
                
                try{
                    $result = $this->db->query($sql)->getResult('array');
                    $filename = "writable\genexcel\holdingwithelectricitydetailsreport_".date('Y_m_d_H_I_s').".csv";
                    $fp = fopen($filename, 'w');
    
                    // Loop through file pointer and a line
                    fputcsv($fp,array_keys($result[0]));
                    foreach ($result as $fields) {
                        fputcsv($fp, $fields);
                    }
                    
                    fclose($fp);
                    return json_encode($filename);
                    // $result = $this->db->query("SELECT generateCSVReports('".$sql."', 'holdingwithelectricitydetailsreport')");
                    // $filename = $result->getFirstRow("array");
                    // return json_encode($filename);
                } catch(Exception $e) {
                    echo $e;
                }
            
            }else{
                $data['posts'] = $this->model_datatable->getDatatable($sql);
                return view('property/reports/holding_with_elect_dtls', $data);
            }
        }
        catch(Exception $e){
           return $e;
        }
       
		
    }
	
	public function WardWiseGeneratedNotice() {
        try
        {
            $ulb_mstr_id = 1;
            $wardList = $this->model_ward_mstr->getWardListWithSession(['ulb_mstr_id'=>$ulb_mstr_id]);
            $data['wardList'] = $wardList;
            
            return view('property/reports/ward_wise_generated_notice', $data);
        }
        catch(Exception $e){
           return $e;
        }
       
		
    }

    public function WardWiseGeneratedNoticeAjax() {
        try
        {
            if($this->request->getMethod()=='post'){
				$start = sanitizeString($this->request->getVar('start'));
                
                $rowperpage = sanitizeString($this->request->getVar('length')); // Rows display per page
                $search_from_date = sanitizeString($this->request->getVar('search_from_date'));
                $search_upto_date = sanitizeString($this->request->getVar('search_upto_date'));
                $search_ward_mstr_id = sanitizeString($this->request->getVar('search_ward_mstr_id'));
				$search_notice_type = sanitizeString($this->request->getVar('search_notice_type'));
				$limit = " LIMIT ".$rowperpage." OFFSET ".$start;
                $whereQuery = "";
                if ($search_ward_mstr_id != '') {
                    $whereQuery .= " AND  (tbl_prop_dtl.ward_mstr_id='".$search_ward_mstr_id."' OR tbl_prop_dtl.new_ward_mstr_id='".$search_ward_mstr_id."')";
                }
				if($search_notice_type !="")
                {
                    $whereQuery .= " AND tbl_prop_notices.notice_type='".$search_notice_type."'";
                }
                $response = array();
                
                $selectQuery = "SELECT 
                ROW_NUMBER () OVER (ORDER BY tbl_prop_notices.id desc) AS s_no,
                CONCAT('NOTICE/', tbl_prop_notices.notice_no) AS notice_no,
                tbl_prop_notices.notice_date,
                tbl_prop_notices.notice_type,
                tbl_prop_notices.demand_amount,
                tbl_prop_notices.penalty,
                tbl_prop_notices.remarks,
                CASE WHEN tbl_prop_notices.from_fyear is not null THEN CONCAT(tbl_prop_notices.from_fyear, '(', tbl_prop_notices.from_qtr, ')', ' / ', tbl_prop_notices.upto_fyear, '(', tbl_prop_notices.upto_qtr, ')') ELSE '' END AS from_upto_fy_qtr,
                CASE WHEN char_length(tbl_prop_dtl.new_holding_no) > 0 THEN tbl_prop_dtl.new_holding_no ELSE tbl_prop_dtl.holding_no END as holding_no,
                CASE WHEN new_ward.ward_no is not null THEN new_ward.ward_no ELSE old_ward.ward_no END as ward_no,
                tbl_prop_owner_detail.wname as owner_name,
                tbl_prop_owner_detail.wmobile as owner_mobile_no,
                tbl_prop_notices.created_on::date as generated_date,
                gen.emp_name as generated_by,
                tbl_prop_notices.deactivate_date::date as deactivate_date,
                deactive.emp_name as deactivated_by";

                $sql = " FROM tbl_prop_notices
                    JOIN tbl_prop_dtl ON tbl_prop_notices.prop_dtl_id=tbl_prop_dtl.id
                    LEFT JOIN view_ward_mstr old_ward ON tbl_prop_dtl.ward_mstr_id=old_ward.id
                    LEFT JOIN view_ward_mstr new_ward ON tbl_prop_dtl.new_ward_mstr_id=new_ward.id
                    JOIN (
                        SELECT 
                            prop_dtl_id,
                            array_to_string(array_agg(owner_name), '','') as wname ,
                            array_to_string(array_agg(mobile_no), '','') as wmobile 
                        FROM tbl_prop_owner_detail 
                        GROUP BY prop_dtl_id
                        ) tbl_prop_owner_detail on tbl_prop_owner_detail.prop_dtl_id = tbl_prop_notices.prop_dtl_id
                    LEFT JOIN view_emp_details_login gen ON tbl_prop_notices.generated_by_emp_details_id=gen.id
                    LEFT JOIN view_emp_details_login deactive ON tbl_prop_notices.deactivated_by=deactive.id
                    WHERE (notice_date BETWEEN '".$search_from_date."' AND  '".$search_upto_date."') ". $whereQuery;
                
                $fetchSumSql = $selectQuery.$sql.$limit;
                $totalRecordwithFilter = $this->model_datatable->getTotalRecordwithFilter($sql);
                $totalRecords = $this->model_datatable->getTotalRecords($sql);
                $response['recordsFiltered'] = $totalRecordwithFilter; 
                $response['recordsTotal'] = $totalRecords; 
                $response['data'] = $this->model_datatable->getRecords($fetchSumSql,false); 
                return json_encode($response);
            }

        }
        catch(Exception $e){
           return $e;
        }
    }
	
	
	public function WardWiseRainwaterHarveting() {
        try
        {
            $sql = "WITH TotHoseHold as (
                select count(tbl_prop_dtl.id) as total_household,tbl_prop_dtl.ward_mstr_id
                from tbl_prop_dtl
                JOIN (
                    SELECT geotag_dtl_id 
                    FROM tbl_saf_geotag_upload_dtl 
                    WHERE status=1
                    GROUP BY geotag_dtl_id
                ) AS geotagging ON geotagging.geotag_dtl_id=tbl_prop_dtl.saf_dtl_id
                WHERE tbl_prop_dtl.new_holding_no is not null or length(tbl_prop_dtl.new_holding_no)>=15 and tbl_prop_dtl.status=1 
                group by tbl_prop_dtl.ward_mstr_id
            ),
            should_rainwater_harvesting as (
                select count(tbl_prop_dtl.id) as should_harvesting,tbl_prop_dtl.ward_mstr_id 
                from tbl_prop_dtl 
                JOIN (
                    SELECT geotag_dtl_id 
                    FROM tbl_saf_geotag_upload_dtl
                    WHERE status=1
                    GROUP BY geotag_dtl_id
                ) AS geotagging ON geotagging.geotag_dtl_id=tbl_prop_dtl.saf_dtl_id
                WHERE tbl_prop_dtl.status=1 and (area_of_plot*435.56) >= '3228' 
                group by tbl_prop_dtl.ward_mstr_id
            ),
            less_area_3228 as (
                select count(tbl_prop_dtl.id) as harvesting,tbl_prop_dtl.ward_mstr_id 
                from tbl_prop_dtl 
                JOIN (
                    SELECT geotag_dtl_id 
                    FROM tbl_saf_geotag_upload_dtl
                    WHERE status=1
                    GROUP BY geotag_dtl_id
                ) AS geotagging ON geotagging.geotag_dtl_id=tbl_prop_dtl.saf_dtl_id
                WHERE tbl_prop_dtl.status=1 and (area_of_plot*435.56) < '3228' and tbl_prop_dtl.is_water_harvesting='true' 
                group by tbl_prop_dtl.ward_mstr_id
            ),
            greaterorequal_area_3228 as (
                select count(tbl_prop_dtl.id) as harvesting,tbl_prop_dtl.ward_mstr_id 
                from tbl_prop_dtl 
                JOIN (
                    SELECT geotag_dtl_id 
                    FROM tbl_saf_geotag_upload_dtl
                    WHERE status=1
                    GROUP BY geotag_dtl_id
                ) AS geotagging ON geotagging.geotag_dtl_id=tbl_prop_dtl.saf_dtl_id
                WHERE tbl_prop_dtl.status=1 and (area_of_plot*435.56) >= '3228' and tbl_prop_dtl.is_water_harvesting='true' 
                group by tbl_prop_dtl.ward_mstr_id
            )
            select view_ward_mstr.ward_no,
            COALESCE(TotHoseHold.total_household, 0) as total_household,
            COALESCE(should_rainwater_harvesting.should_harvesting, 0) as should_harvesting,
            COALESCE(less_area_3228.harvesting, 0) as less_area_3228,
            COALESCE(greaterorequal_area_3228.harvesting, 0) as greaterorequal_area_3228,
            (COALESCE(should_rainwater_harvesting.should_harvesting, 0)-COALESCE(greaterorequal_area_3228.harvesting, 0)) as remaining
            FROM view_ward_mstr
            LEFT JOIN TotHoseHold ON TotHoseHold.ward_mstr_id=view_ward_mstr.id
            LEFT JOIN should_rainwater_harvesting ON should_rainwater_harvesting.ward_mstr_id=view_ward_mstr.id
            LEFT JOIN less_area_3228 ON less_area_3228.ward_mstr_id=view_ward_mstr.id
            LEFT JOIN greaterorequal_area_3228 ON greaterorequal_area_3228.ward_mstr_id=view_ward_mstr.id
            WHERE TotHoseHold.total_household > 0
            ORDER BY SUBSTRING(view_ward_mstr.ward_no FROM '([0-9]+)')::BIGINT ASC, view_ward_mstr.ward_no";
            
            $data['posts'] = $this->db->query($sql)->getResultArray();
            return view('property/reports/ward_wise_water_harvesting', $data);
        }
        catch(Exception $e){
           return $e;
        }
       
		
    }

    public function TcVisitingReport_bk() 
    {
        $ulb_mstr_id = 1;
        $wardList = $this->model_ward_mstr->getWardList(['ulb_mstr_id'=>$ulb_mstr_id]);
        //$wardList = $this->model_ward_mstr->getWardListWithSession(['ulb_mstr_id'=>$ulb_mstr_id], $session);
        $empDtlList = $this->model_view_emp_details->getEmpListByAlTaxCollector(['ulb_mstr_id'=>$ulb_mstr_id]);
        $data['wardList'] = $wardList;
        $data['empDtlList'] = $empDtlList;

        try
        {
            $whereParam = "";
            $from_date = '';
            $upto_date = '';
            $collector_id = '';
            $whereParam1 = '';
            if($this->request->getMethod()=='post')
            {
                
                $from_date = sanitizeString($this->request->getVar('from_date'));
                $upto_date = sanitizeString($this->request->getVar('upto_date'));
                $collector_id = sanitizeString($this->request->getVar('collector_id'));

                if(isset($from_date) && isset($upto_date))
                {
                   
                    $whereParam = " and (tbl_visiting_dtl.created_on::date between '".$from_date."' and '".$upto_date."')";
                }
                if(isset($collector_id) && $collector_id>0)
                {
                    $whereParam1 = " and view_emp_details.id=".$collector_id;
                }

            }
            $sql = "with property as(
                SELECT 
                    emp_id,
                    count(CASE WHEN tbl_visiting_dtl.remarks_id=7 then tbl_visiting_dtl.id else null END) as payment_received,
                    count(CASE WHEN tbl_visiting_dtl.remarks_id=8 then tbl_visiting_dtl.id else null END) as already_paid,
                    count(CASE WHEN tbl_visiting_dtl.remarks_id=9 then tbl_visiting_dtl.id else null END ) as not_agary_to_pay,
                    count(CASE WHEN tbl_visiting_dtl.remarks_id=10 then tbl_visiting_dtl.id else null END) as pay_leter,
                    count(CASE WHEN tbl_visiting_dtl.remarks_id=11 then tbl_visiting_dtl.id else null END) as door_locked,
                    count(CASE WHEN tbl_visiting_dtl.remarks_id=12 then tbl_visiting_dtl.id else null END) as saf_not_done
                FROM tbl_visiting_dtl
                WHERE tbl_visiting_dtl.status=1 AND module_id=2 ".$whereParam."
                GROUP BY emp_id
            ),
            saf as(
                SELECT 
                    emp_id,
                    count(CASE WHEN tbl_visiting_dtl.remarks_id=1 then tbl_visiting_dtl.id else null END) as saf_apply,
                    count(CASE WHEN tbl_visiting_dtl.remarks_id=2 then tbl_visiting_dtl.id else null END) as payment_received,
                    count(CASE WHEN tbl_visiting_dtl.remarks_id=3 then tbl_visiting_dtl.id else null END) as land_lord_not_avaliable,
                    count(CASE WHEN tbl_visiting_dtl.remarks_id=4 then tbl_visiting_dtl.id else null END) as updation_required,
                    count(CASE WHEN tbl_visiting_dtl.remarks_id=5 then tbl_visiting_dtl.id else null END) as geotag_done
                FROM tbl_visiting_dtl
                WHERE tbl_visiting_dtl.status=1 AND module_id=1 ".$whereParam."
                GROUP BY emp_id
            ),
            water as(
                SELECT 
                    emp_id,
                    count(CASE WHEN tbl_visiting_dtl.remarks_id=14 then tbl_visiting_dtl.id else null END) as water_bill_generate,
                    count(CASE WHEN tbl_visiting_dtl.remarks_id=15 then tbl_visiting_dtl.id else null END) as bill_collection,
                    count(CASE WHEN tbl_visiting_dtl.remarks_id=16 then tbl_visiting_dtl.id else null END) as not_paid,
                    count(CASE WHEN tbl_visiting_dtl.remarks_id=17 then tbl_visiting_dtl.id else null END) as pay_later,
                    count(CASE WHEN tbl_visiting_dtl.remarks_id=18 then tbl_visiting_dtl.id else null END) as not_paid_as_no_water_supply,
                    count(CASE WHEN tbl_visiting_dtl.remarks_id=19 then tbl_visiting_dtl.id else null END) as new_connction_apply
                FROM tbl_visiting_dtl
                WHERE tbl_visiting_dtl.status=1 AND module_id=3 ".$whereParam."
                GROUP BY emp_id
            ),
            trade as(
                SELECT 
                    emp_id,
                    count(CASE WHEN tbl_visiting_dtl.remarks_id=21 then tbl_visiting_dtl.id else null END) as new_trade_apply,
                    count(CASE WHEN tbl_visiting_dtl.remarks_id=22 then tbl_visiting_dtl.id else null END) as renewal_apply,
                    count(CASE WHEN tbl_visiting_dtl.remarks_id=23 then tbl_visiting_dtl.id else null END) as deniel_apply,
                    count(CASE WHEN tbl_visiting_dtl.remarks_id=24 then tbl_visiting_dtl.id else null END) as surrender_apply,
                    count(CASE WHEN tbl_visiting_dtl.remarks_id=25 then tbl_visiting_dtl.id else null END) as trade_collection,
                    count(CASE WHEN tbl_visiting_dtl.remarks_id=26 then tbl_visiting_dtl.id else null END) as apply_later
                FROM tbl_visiting_dtl
                WHERE tbl_visiting_dtl.status=1 AND module_id=4 ".$whereParam."
                GROUP BY emp_id
            )
            select view_emp_details.emp_name,
            COALESCE(property.payment_received, 0) as payment_received,
            COALESCE(property.already_paid, 0) as already_paid,
            COALESCE(property.not_agary_to_pay, 0) as not_agary_to_pay,
            COALESCE(property.pay_leter, 0) as pay_leter,
            COALESCE(property.door_locked, 0) as door_locked,
            COALESCE(property.saf_not_done, 0) as saf_not_done,
            COALESCE(saf.saf_apply, 0) as saf_apply,
            COALESCE(saf.payment_received, 0) as spayment_received,
            COALESCE(saf.land_lord_not_avaliable, 0) as land_lord_not_avaliable,
            COALESCE(saf.updation_required, 0) as updation_required,
            COALESCE(saf.geotag_done, 0) as geotag_done,
            COALESCE(water.water_bill_generate, 0) as water_bill_generate,
            COALESCE(water.bill_collection, 0) as bill_collection,
            COALESCE(water.not_paid, 0) as not_paid,
            COALESCE(water.pay_later, 0) as pay_later,
            COALESCE(water.not_paid_as_no_water_supply, 0) as not_paid_as_no_water_supply,
            COALESCE(water.new_connction_apply, 0) as new_connction_apply,
            COALESCE(trade.new_trade_apply, 0) as new_trade_apply,
            COALESCE(trade.renewal_apply, 0) as renewal_apply,
            COALESCE(trade.deniel_apply, 0) as deniel_apply,
            COALESCE(trade.surrender_apply, 0) as surrender_apply,
            COALESCE(trade.trade_collection, 0) as trade_collection,
            COALESCE(trade.apply_later, 0) as apply_later
            from view_emp_details 
            left join property on property.emp_id=view_emp_details.id 
            left join saf on saf.emp_id=view_emp_details.id 
            left join water on water.emp_id=view_emp_details.id 
            left join trade on trade.emp_id=view_emp_details.id 
            WHERE view_emp_details.status=1 and view_emp_details.lock_status=0 ".$whereParam1."
            and view_emp_details.user_type_mstr_id=5";
            //print_var($sql);
            //$data = arrFilterSanitizeString($this->request->getVar());
            //$data['posts'] = $this->model_datatable->getDatatable($sql);
            $data['posts'] = $this->dbSystem->query($sql)->getResultArray();
            $data['from_date'] = $from_date;
            $data['upto_date'] = $upto_date;
            $data['collector_id'] = $collector_id;
            return view('property/reports/tc_visiting', $data);
        }
        catch(Exception $e){
           return $e;
        }
       
		
    }
    public function TcVisitingReport() 
    {
        $ulb_mstr_id = 1;
        $wardList = $this->model_ward_mstr->getWardList(['ulb_mstr_id'=>$ulb_mstr_id]);
        //$wardList = $this->model_ward_mstr->getWardListWithSession(['ulb_mstr_id'=>$ulb_mstr_id], $session);
        $empDtlList = $this->model_view_emp_details->getEmpListByAlTaxCollector(['ulb_mstr_id'=>$ulb_mstr_id]);
        $data['wardList'] = $wardList;
        $data['empDtlList'] = $empDtlList;

        try
        {
            $whereParam = "";
            $from_date = '';
            $upto_date = '';
            $collector_id = '';
            $ward_id = '';
            $whereParam1 = '';
            $whereward = '';
            if($this->request->getMethod()=='post')
            {
                
                $from_date = sanitizeString($this->request->getVar('from_date'));
                $upto_date = sanitizeString($this->request->getVar('upto_date'));
                $collector_id = sanitizeString($this->request->getVar('collector_id'));
                $ward_id = sanitizeString($this->request->getVar('ward_id'));

                if(isset($from_date) && isset($upto_date))
                {
                   
                    $whereParam = " and (tbl_visiting_dtl.created_on::date between '".$from_date."' and '".$upto_date."')";
                }
                if(isset($collector_id) && $collector_id>0)
                {
                    $whereParam1 = " and view_emp_details.id=".$collector_id;
                }
                if(isset($ward_id) && $ward_id>0)
                {
                    $whereward = " right join tbl_ward_permission on tbl_ward_permission.emp_details_id = view_emp_details.id and tbl_ward_permission.ward_mstr_id=".$ward_id;
                }
            }
            $sql = "with property as(
                SELECT 
                    emp_id,
                    count(CASE WHEN tbl_visiting_dtl.remarks_id=7 then tbl_visiting_dtl.id else null END) as payment_received,
                    count(CASE WHEN tbl_visiting_dtl.remarks_id=8 then tbl_visiting_dtl.id else null END) as already_paid,
                    count(CASE WHEN tbl_visiting_dtl.remarks_id=9 then tbl_visiting_dtl.id else null END ) as not_agary_to_pay,
                    count(CASE WHEN tbl_visiting_dtl.remarks_id=10 then tbl_visiting_dtl.id else null END) as pay_leter,
                    count(CASE WHEN tbl_visiting_dtl.remarks_id=11 then tbl_visiting_dtl.id else null END) as door_locked,
                    count(CASE WHEN tbl_visiting_dtl.remarks_id=12 then tbl_visiting_dtl.id else null END) as saf_not_done
                FROM tbl_visiting_dtl
                WHERE tbl_visiting_dtl.status=1 AND module_id=2 ".$whereParam."
                GROUP BY emp_id
            ),
            saf as(
                SELECT 
                    emp_id,
                    count(CASE WHEN tbl_visiting_dtl.remarks_id=1 then tbl_visiting_dtl.id else null END) as saf_apply,
                    count(CASE WHEN tbl_visiting_dtl.remarks_id=2 then tbl_visiting_dtl.id else null END) as payment_received,
                    count(CASE WHEN tbl_visiting_dtl.remarks_id=3 then tbl_visiting_dtl.id else null END) as land_lord_not_avaliable,
                    count(CASE WHEN tbl_visiting_dtl.remarks_id=4 then tbl_visiting_dtl.id else null END) as updation_required,
                    count(CASE WHEN tbl_visiting_dtl.remarks_id=5 then tbl_visiting_dtl.id else null END) as geotag_done
                FROM tbl_visiting_dtl
                WHERE tbl_visiting_dtl.status=1 AND module_id=1 ".$whereParam."
                GROUP BY emp_id
            ),
            water as(
                SELECT 
                    emp_id,
                    count(CASE WHEN tbl_visiting_dtl.remarks_id=14 then tbl_visiting_dtl.id else null END) as water_bill_generate,
                    count(CASE WHEN tbl_visiting_dtl.remarks_id=15 then tbl_visiting_dtl.id else null END) as bill_collection,
                    count(CASE WHEN tbl_visiting_dtl.remarks_id=16 then tbl_visiting_dtl.id else null END) as not_paid,
                    count(CASE WHEN tbl_visiting_dtl.remarks_id=17 then tbl_visiting_dtl.id else null END) as pay_later,
                    count(CASE WHEN tbl_visiting_dtl.remarks_id=18 then tbl_visiting_dtl.id else null END) as not_paid_as_no_water_supply,
                    count(CASE WHEN tbl_visiting_dtl.remarks_id=19 then tbl_visiting_dtl.id else null END) as new_connction_apply
                FROM tbl_visiting_dtl
                WHERE tbl_visiting_dtl.status=1 AND module_id=3 ".$whereParam."
                GROUP BY emp_id
            ),
            trade as(
                SELECT 
                    emp_id,
                    count(CASE WHEN tbl_visiting_dtl.remarks_id=21 then tbl_visiting_dtl.id else null END) as new_trade_apply,
                    count(CASE WHEN tbl_visiting_dtl.remarks_id=22 then tbl_visiting_dtl.id else null END) as renewal_apply,
                    count(CASE WHEN tbl_visiting_dtl.remarks_id=23 then tbl_visiting_dtl.id else null END) as deniel_apply,
                    count(CASE WHEN tbl_visiting_dtl.remarks_id=24 then tbl_visiting_dtl.id else null END) as surrender_apply,
                    count(CASE WHEN tbl_visiting_dtl.remarks_id=25 then tbl_visiting_dtl.id else null END) as trade_collection,
                    count(CASE WHEN tbl_visiting_dtl.remarks_id=26 then tbl_visiting_dtl.id else null END) as apply_later
                FROM tbl_visiting_dtl
                WHERE tbl_visiting_dtl.status=1 AND module_id=4 ".$whereParam."
                GROUP BY emp_id
            )
            select view_emp_details.emp_name,view_emp_details.id as employeeid,trim(concat(view_emp_details.emp_name,' ',trim(concat(view_emp_details.middle_name,' ',view_emp_details.last_name)))) as full_name,
            COALESCE(property.payment_received, 0) as payment_received,
            COALESCE(property.already_paid, 0) as already_paid,
            COALESCE(property.not_agary_to_pay, 0) as not_agary_to_pay,
            COALESCE(property.pay_leter, 0) as pay_leter,
            COALESCE(property.door_locked, 0) as door_locked,
            COALESCE(property.saf_not_done, 0) as saf_not_done,
            COALESCE(saf.saf_apply, 0) as saf_apply,
            COALESCE(saf.payment_received, 0) as spayment_received,
            COALESCE(saf.land_lord_not_avaliable, 0) as land_lord_not_avaliable,
            COALESCE(saf.updation_required, 0) as updation_required,
            COALESCE(saf.geotag_done, 0) as geotag_done,
            COALESCE(water.water_bill_generate, 0) as water_bill_generate,
            COALESCE(water.bill_collection, 0) as bill_collection,
            COALESCE(water.not_paid, 0) as not_paid,
            COALESCE(water.pay_later, 0) as pay_later,
            COALESCE(water.not_paid_as_no_water_supply, 0) as not_paid_as_no_water_supply,
            COALESCE(water.new_connction_apply, 0) as new_connction_apply,
            COALESCE(trade.new_trade_apply, 0) as new_trade_apply,
            COALESCE(trade.renewal_apply, 0) as renewal_apply,
            COALESCE(trade.deniel_apply, 0) as deniel_apply,
            COALESCE(trade.surrender_apply, 0) as surrender_apply,
            COALESCE(trade.trade_collection, 0) as trade_collection,
            COALESCE(trade.apply_later, 0) as apply_later
            from view_emp_details 
            left join property on property.emp_id=view_emp_details.id 
            left join saf on saf.emp_id=view_emp_details.id 
            left join water on water.emp_id=view_emp_details.id 
            left join trade on trade.emp_id=view_emp_details.id 
            ".$whereward."
            WHERE view_emp_details.status=1 and view_emp_details.lock_status=0 ".$whereParam1."
            and view_emp_details.user_type_mstr_id=5";
            // print_var($sql);die;
            //$data = arrFilterSanitizeString($this->request->getVar());
            //$data['posts'] = $this->model_datatable->getDatatable($sql);
            $data['posts'] = $this->dbSystem->query($sql)->getResultArray();
            $data['from_date'] = $from_date;
            $data['upto_date'] = $upto_date;
            $data['collector_id'] = $collector_id;
            $data['ward_id'] = $ward_id;
            return view('property/reports/tc_visiting', $data);
        }
        catch(Exception $e){
           return $e;
        }
    }


    // TC VISITING DETAILS REPORT ADD 16-12-2024
    // public function tcVisitingDetails()
    // {
    //     $data = $this->request->getVar();

    //     $employeeId = $data["empDtlId"] ?? 0;
    //     $moduleId = intval($data["moduleId"]);
    //     $remarkmsId = intval($data["remarksId"]);

    //     $whereParam = "";
    //     $from_date = sanitizeString($this->request->getVar('from_date'));
    //     $upto_date = sanitizeString($this->request->getVar('upto_date'));
    //     $collector_id = $employeeId;
    //     $ward_id = sanitizeString($this->request->getVar('ward_id'));


    //     $sql = "SELECT emp_name FROM view_emp_details where id = '$employeeId' ";
    //     $result = $this->dbSystem->query($sql);
    //     $data['tc_name'] = $result->getResultArray();

    //     if ($moduleId === 2) {

    //         if (!empty($remarkmsId) && $remarkmsId === 7) {

    //             $sql = "SELECT tbl_prop_dtl.id, tbl_prop_dtl.new_holding_no, tbl_prop_dtl.prop_address, view_ward_mstr.ward_no, tbl_prop_owner_detail.owner_name, 
    //                     tbl_prop_owner_detail.mobile_no, tbl_transaction.payable_amt 
    //                     FROM tbl_prop_dtl
    //                     LEFT JOIN view_ward_mstr ON view_ward_mstr.id = tbl_prop_dtl.new_ward_mstr_id
    //                     LEFT JOIN  (
    //                         select string_agg(owner_name,',') as owner_name,
    //                             string_agg(mobile_no::text,',') as mobile_no,
    //                             prop_dtl_id
    //                         from tbl_prop_owner_detail
    //                         where status =1
    //                         group by prop_dtl_id
    //                     )tbl_prop_owner_detail ON tbl_prop_owner_detail.prop_dtl_id = tbl_prop_dtl.id
    //                     INNER JOIN tbl_transaction ON tbl_transaction.prop_dtl_id = tbl_prop_dtl.id
    //                     WHERE tbl_transaction.tran_by_emp_details_id = '$collector_id'";

    //             if (!empty($from_date)) {
    //                 $sql .= " AND tbl_transaction.created_on::DATE >= '$from_date'";
    //             }

    //             if (!empty($upto_date)) {
    //                 $sql .= " AND tbl_transaction.created_on::DATE <= '$upto_date'";
    //             }

    //         } elseif (!empty($remarkmsId) && ($remarkmsId === 8 || $remarkmsId === 9 || $remarkmsId === 10 || $remarkmsId === 11 || $remarkmsId === 12)) {
    //             $sql = "SELECT tbl_prop_dtl.id, tbl_prop_dtl.new_holding_no, view_ward_mstr.ward_no,
    //                     tbl_prop_owner_detail.owner_name, tbl_prop_owner_detail.mobile_no,tbl_prop_dtl.prop_address
    //                     FROM tbl_prop_dtl
    //                     LEFT JOIN view_ward_mstr ON view_ward_mstr.id = tbl_prop_dtl.new_ward_mstr_id
    //                     LEFT JOIN  (
    //                         select string_agg(owner_name,',') as owner_name,
    //                             string_agg(mobile_no::text,',') as mobile_no,
    //                             prop_dtl_id
    //                         from tbl_prop_owner_detail
    //                         where status =1
    //                         group by prop_dtl_id
    //                     )tbl_prop_owner_detail ON tbl_prop_owner_detail.prop_dtl_id = tbl_prop_dtl.id
    //                     INNER JOIN view_tc_visiting_dtl ON view_tc_visiting_dtl.ref_type_id  = tbl_prop_dtl.id
    //                     WHERE view_tc_visiting_dtl.emp_id = '$collector_id' AND view_tc_visiting_dtl.remarks_id = '$remarkmsId' AND module_id = 2";
    //             if (!empty($from_date)) {
    //                 $sql .= " AND view_tc_visiting_dtl.created_on::DATE >= '$from_date'";
    //             }

    //             if (!empty($upto_date)) {
    //                 $sql .= " AND view_tc_visiting_dtl.created_on::DATE <= '$upto_date'";
    //             }
    //         }

    //         $result = $this->db->query($sql);


    //         $data['posts'] = $result->getResultArray();

    //     } elseif ($moduleId === 1) {
    //         if (!empty($remarkmsId) && $remarkmsId === 1 || $remarkmsId === 2 || $remarkmsId === 3 || $remarkmsId === 4 || $remarkmsId === 5) {
    //             $sql = "SELECT tbl_saf_dtl.id as saf_dtl_id, tbl_saf_dtl.saf_no, tbl_saf_dtl.prop_address, view_ward_mstr.ward_no, tbl_saf_dtl.holding_type,
    //                         tbl_saf_dtl.payment_status, tbl_saf_owner_detail.owner_name, tbl_saf_owner_detail.mobile_no,
    //                         ( CASE
    //                             WHEN payment_status = 1 THEN 'YES'
    //                             ELSE 'NO'
    //                             END )AS payment_status
    //                     FROM   tbl_saf_dtl
    //                         left join view_ward_mstr
    //                                 ON view_ward_mstr.id = tbl_saf_dtl.new_ward_mstr_id
    //                         left join (SELECT saf_dtl_id,
    //                                             String_agg(owner_name, ',')        AS owner_name,
    //                                             String_agg(mobile_no :: text, ',') AS mobile_no
    //                                     FROM   tbl_saf_owner_detail
    //                                     WHERE  status = 1
    //                                     GROUP  BY saf_dtl_id) tbl_saf_owner_detail
    //                                 ON tbl_saf_owner_detail.saf_dtl_id = tbl_saf_dtl.id
    //                         inner join view_tc_visiting_dtl
    //                                 ON view_tc_visiting_dtl.ref_type_id = tbl_saf_dtl.id
    //                     WHERE  view_tc_visiting_dtl.module_id = 1
    //                         AND view_tc_visiting_dtl.remarks_id = '$remarkmsId'
    //                         AND view_tc_visiting_dtl.emp_id = '$collector_id' ";

    //             if (!empty($from_date)) {
    //                 $sql .= " AND view_tc_visiting_dtl.created_on::DATE >= '$from_date'";
    //             }

    //             if (!empty($upto_date)) {
    //                 $sql .= " AND view_tc_visiting_dtl.created_on::DATE <= '$upto_date'";
    //             }
    //         }
    //         $result = $this->db->query($sql);

    //         $data['posts'] = $result->getResultArray();

    //     } elseif ($moduleId === 3) {
    //         if (!empty($remarkmsId) && $remarkmsId === 14 || $remarkmsId === 15 || $remarkmsId === 16 || $remarkmsId === 17 || $remarkmsId === 18 || $remarkmsId === 19) {
    //             $sql = "SELECT 
    //                     tbl_consumer.id AS consumer_id, 
    //                     tbl_consumer.consumer_no, 
    //                     tbl_consumer.address,
    //                     view_ward_mstr.ward_no, 
    //                     tbl_applicant_details.owner_name, 
    //                     tbl_applicant_details.mobile_no
    //                 FROM tbl_consumer
    //                 LEFT JOIN view_ward_mstr 
    //                     ON view_ward_mstr.id = tbl_consumer.ward_mstr_id
    //                 INNER JOIN (
    //                     SELECT consumer_id, 
    //                         STRING_AGG(applicant_name, ',') AS owner_name,
    //                         STRING_AGG(mobile_no::TEXT, ',') AS mobile_no
    //                     FROM tbl_consumer_details
    //                     WHERE status = 1
    //                     GROUP BY consumer_id
    //                 ) AS tbl_applicant_details 
    //                     ON tbl_applicant_details.consumer_id = tbl_consumer.id
    //                 INNER JOIN view_tc_visiting_dtl 
    //                     ON view_tc_visiting_dtl.ref_type_id = tbl_consumer.id
    //                 WHERE 
    //                     view_tc_visiting_dtl.module_id = 3 AND view_tc_visiting_dtl.emp_id = '$collector_id'
    //                     AND view_tc_visiting_dtl.remarks_id = '$remarkmsId'";

    //             if (!empty($from_date)) {
    //                 $sql .= " AND view_tc_visiting_dtl.created_on::DATE >= '$from_date'";
    //             }

    //             if (!empty($upto_date)) {
    //                 $sql .= " AND view_tc_visiting_dtl.created_on::DATE <= '$upto_date'";
    //             }

    //             $result = $this->dbWater->query($sql);
    //             $data['posts'] = $result->getResultArray();
    //         }

    //     } else if ($moduleId === 4) {
    //         $sql = "SELECT tbl_apply_licence.id as application_id,
    //                         tbl_apply_licence.application_no,
    //                         tbl_apply_licence.firm_name,
    //                         tbl_apply_licence.address,
    //                         tbl_apply_licence.license_no,
    //                         tbl_apply_licence.valid_upto,
    //                         tbl_firm_owner_name.owner_name,
    //                         tbl_firm_owner_name.mobile_no,
    //                         view_ward_mstr.ward_no
    //                     FROM   tbl_apply_licence
    //                         left join view_ward_mstr
    //                                 ON view_ward_mstr.id = tbl_apply_licence.ward_mstr_id
    //                         left join (SELECT apply_licence_id,
    //                                             String_agg(owner_name, ',')     AS owner_name,
    //                                             String_agg(mobile :: text, ',') AS mobile_no
    //                                     FROM   tbl_firm_owner_name
    //                                     WHERE  status = 1
    //                                     GROUP  BY apply_licence_id) tbl_firm_owner_name
    //                                 ON tbl_firm_owner_name.apply_licence_id = tbl_apply_licence.id
    //                         inner join view_tc_visiting_dtl
    //                                 ON view_tc_visiting_dtl.ref_type_id = tbl_apply_licence.id
    //                     WHERE  view_tc_visiting_dtl.emp_id = '$collector_id'
    //                         AND view_tc_visiting_dtl.module_id = 4
    //                         AND view_tc_visiting_dtl.remarks_id = '$remarkmsId' ";
    //         if (!empty($from_date)) {
    //             $sql .= " AND view_tc_visiting_dtl.created_on::DATE >= '$from_date'";
    //         }

    //         if (!empty($upto_date)) {
    //             $sql .= " AND view_tc_visiting_dtl.created_on::DATE <= '$upto_date'";
    //         }
    //         // print_Var($sql);
    //         $result = $this->dbTrade->query($sql);

    //         $data['posts'] = $result->getResultArray();
    //         // print_Var($data['posts']);
    //     }

    //     return view('property/reports/tc_visiting_details', $data);
    // }

    public function tcVisitingDetails()
    {
        $data = $this->request->getVar();

        $employeeId = $data["empDtlId"] ?? 0;
        $moduleId = intval($data["moduleId"]);
        $remarkmsId = intval($data["remarksId"]);

        $whereParam = "";
        $from_date = sanitizeString($this->request->getVar('from_date'));
        $upto_date = sanitizeString($this->request->getVar('upto_date'));
        $collector_id = $employeeId;
        $ward_id = sanitizeString($this->request->getVar('ward_id'));


        $sql = "SELECT emp_name FROM view_emp_details where id = '$employeeId' ";
        $result = $this->dbSystem->query($sql);
        $data['tc_name'] = $result->getResultArray();

        if ($moduleId === 2) {

            if (!empty($remarkmsId) && $remarkmsId === 7) {

                $sql = "SELECT tbl_prop_dtl.id,
                            tbl_prop_dtl.new_holding_no,
                            tbl_prop_dtl.prop_address,
                            view_ward_mstr.ward_no,
                            tbl_prop_owner_detail.owner_name,
                            tbl_prop_owner_detail.mobile_no
                        FROM   view_tc_visiting_dtl
                            left join tbl_prop_dtl
                                    ON view_tc_visiting_dtl.ref_type_id = tbl_prop_dtl.id
                            left join view_ward_mstr
                                    ON view_ward_mstr.id = tbl_prop_dtl.new_ward_mstr_id
                            left join (SELECT String_agg(owner_name, ',')        AS owner_name,
                                                String_agg(mobile_no :: text, ',') AS mobile_no,
                                                prop_dtl_id
                                        FROM   tbl_prop_owner_detail
                                        WHERE  status = 1
                                        GROUP  BY prop_dtl_id)tbl_prop_owner_detail
                                    ON tbl_prop_owner_detail.prop_dtl_id =
                                        view_tc_visiting_dtl.ref_type_id
                        WHERE  view_tc_visiting_dtl.emp_id = '$collector_id'
                            AND view_tc_visiting_dtl.remarks_id = '$remarkmsId'
                            AND module_id = 2";

                if (!empty($from_date)) {
                    $sql .= " AND view_tc_visiting_dtl.created_on::DATE >= '$from_date'";
                }

                if (!empty($upto_date)) {
                    $sql .= " AND view_tc_visiting_dtl.created_on::DATE <= '$upto_date'";
                }

            } elseif (!empty($remarkmsId) && ($remarkmsId === 8 || $remarkmsId === 9 || $remarkmsId === 10 || $remarkmsId === 11 || $remarkmsId === 12)) {
                $sql = "SELECT tbl_prop_dtl.id, tbl_prop_dtl.new_holding_no, view_ward_mstr.ward_no,
                        tbl_prop_owner_detail.owner_name, tbl_prop_owner_detail.mobile_no,tbl_prop_dtl.prop_address
                        FROM tbl_prop_dtl
                        LEFT JOIN view_ward_mstr ON view_ward_mstr.id = tbl_prop_dtl.new_ward_mstr_id
                        LEFT JOIN  (
                            select string_agg(owner_name,',') as owner_name,
                                string_agg(mobile_no::text,',') as mobile_no,
                                prop_dtl_id
                            from tbl_prop_owner_detail
                            where status =1
                            group by prop_dtl_id
                        )tbl_prop_owner_detail ON tbl_prop_owner_detail.prop_dtl_id = tbl_prop_dtl.id
                        INNER JOIN view_tc_visiting_dtl ON view_tc_visiting_dtl.ref_type_id  = tbl_prop_dtl.id
                        WHERE view_tc_visiting_dtl.emp_id = '$collector_id' AND view_tc_visiting_dtl.remarks_id = '$remarkmsId' AND module_id = 2";
                if (!empty($from_date)) {
                    $sql .= " AND view_tc_visiting_dtl.created_on::DATE >= '$from_date'";
                }

                if (!empty($upto_date)) {
                    $sql .= " AND view_tc_visiting_dtl.created_on::DATE <= '$upto_date'";
                }
            }
            // dd($sql);
            $result = $this->db->query($sql);


            $data['posts'] = $result->getResultArray();

        } elseif ($moduleId === 1) {
            if (!empty($remarkmsId) && $remarkmsId === 1 || $remarkmsId === 2 || $remarkmsId === 3 || $remarkmsId === 4 || $remarkmsId === 5) {
                $sql = "SELECT tbl_saf_dtl.id as saf_dtl_id, tbl_saf_dtl.saf_no, tbl_saf_dtl.prop_address, view_ward_mstr.ward_no, tbl_saf_dtl.holding_type,
                            tbl_saf_dtl.payment_status, tbl_saf_owner_detail.owner_name, tbl_saf_owner_detail.mobile_no,
                            ( CASE
                                WHEN payment_status = 1 THEN 'YES'
                                ELSE 'NO'
                                END )AS payment_status
                        FROM   tbl_saf_dtl
                            left join view_ward_mstr
                                    ON view_ward_mstr.id = tbl_saf_dtl.new_ward_mstr_id
                            left join (SELECT saf_dtl_id,
                                                String_agg(owner_name, ',')        AS owner_name,
                                                String_agg(mobile_no :: text, ',') AS mobile_no
                                        FROM   tbl_saf_owner_detail
                                        WHERE  status = 1
                                        GROUP  BY saf_dtl_id) tbl_saf_owner_detail
                                    ON tbl_saf_owner_detail.saf_dtl_id = tbl_saf_dtl.id
                            inner join view_tc_visiting_dtl
                                    ON view_tc_visiting_dtl.ref_type_id = tbl_saf_dtl.id
                        WHERE  view_tc_visiting_dtl.module_id = 1
                            AND view_tc_visiting_dtl.remarks_id = '$remarkmsId'
                            AND view_tc_visiting_dtl.emp_id = '$collector_id' ";

                if (!empty($from_date)) {
                    $sql .= " AND view_tc_visiting_dtl.created_on::DATE >= '$from_date'";
                }

                if (!empty($upto_date)) {
                    $sql .= " AND view_tc_visiting_dtl.created_on::DATE <= '$upto_date'";
                }
            }
            $result = $this->db->query($sql);

            $data['posts'] = $result->getResultArray();

        } elseif ($moduleId === 3) {
            if (!empty($remarkmsId) && $remarkmsId === 14 || $remarkmsId === 15 || $remarkmsId === 16 || $remarkmsId === 17 || $remarkmsId === 18 || $remarkmsId === 19) {
                $sql = "SELECT 
                        tbl_consumer.id AS consumer_id, 
                        tbl_consumer.consumer_no, 
                        tbl_consumer.address,
                        view_ward_mstr.ward_no, 
                        tbl_applicant_details.owner_name, 
                        tbl_applicant_details.mobile_no
                    FROM tbl_consumer
                    LEFT JOIN view_ward_mstr 
                        ON view_ward_mstr.id = tbl_consumer.ward_mstr_id
                    INNER JOIN (
                        SELECT consumer_id, 
                            STRING_AGG(applicant_name, ',') AS owner_name,
                            STRING_AGG(mobile_no::TEXT, ',') AS mobile_no
                        FROM tbl_consumer_details
                        WHERE status = 1
                        GROUP BY consumer_id
                    ) AS tbl_applicant_details 
                        ON tbl_applicant_details.consumer_id = tbl_consumer.id
                    INNER JOIN view_tc_visiting_dtl 
                        ON view_tc_visiting_dtl.ref_type_id = tbl_consumer.id
                    WHERE 
                        view_tc_visiting_dtl.module_id = 3 AND view_tc_visiting_dtl.emp_id = '$collector_id'
                        AND view_tc_visiting_dtl.remarks_id = '$remarkmsId'";

                if (!empty($from_date)) {
                    $sql .= " AND view_tc_visiting_dtl.created_on::DATE >= '$from_date'";
                }

                if (!empty($upto_date)) {
                    $sql .= " AND view_tc_visiting_dtl.created_on::DATE <= '$upto_date'";
                }

                $result = $this->dbWater->query($sql);
                $data['posts'] = $result->getResultArray();
            }

        } else if ($moduleId === 4) {
            $sql = "SELECT tbl_apply_licence.id as application_id,
                            tbl_apply_licence.application_no,
                            tbl_apply_licence.firm_name,
                            tbl_apply_licence.address,
                            tbl_apply_licence.license_no,
                            tbl_apply_licence.valid_upto,
                            tbl_firm_owner_name.owner_name,
                            tbl_firm_owner_name.mobile_no,
                            view_ward_mstr.ward_no
                        FROM   tbl_apply_licence
                            left join view_ward_mstr
                                    ON view_ward_mstr.id = tbl_apply_licence.ward_mstr_id
                            left join (SELECT apply_licence_id,
                                                String_agg(owner_name, ',')     AS owner_name,
                                                String_agg(mobile :: text, ',') AS mobile_no
                                        FROM   tbl_firm_owner_name
                                        WHERE  status = 1
                                        GROUP  BY apply_licence_id) tbl_firm_owner_name
                                    ON tbl_firm_owner_name.apply_licence_id = tbl_apply_licence.id
                            inner join view_tc_visiting_dtl
                                    ON view_tc_visiting_dtl.ref_type_id = tbl_apply_licence.id
                        WHERE  view_tc_visiting_dtl.emp_id = '$collector_id'
                            AND view_tc_visiting_dtl.module_id = 4
                            AND view_tc_visiting_dtl.remarks_id = '$remarkmsId' ";
            if (!empty($from_date)) {
                $sql .= " AND view_tc_visiting_dtl.created_on::DATE >= '$from_date'";
            }

            if (!empty($upto_date)) {
                $sql .= " AND view_tc_visiting_dtl.created_on::DATE <= '$upto_date'";
            }

            $result = $this->dbTrade->query($sql);

            $data['posts'] = $result->getResultArray();
            // dd($data['posts']);
        }

        return view('property/reports/tc_visiting_details', $data);
    }



    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function holdingWiseDemandreport(){
        $ulb_mstr_id = 1;
        $wardList = $this->model_ward_mstr->getWardList(['ulb_mstr_id'=>$ulb_mstr_id]);
        $data['wardList'] = $wardList;
        $currentFY = getFY();
        $fyList=['id'=>0,'fy'];
        $currentFyID = $this->model_fy_mstr->getFyByFy(['fy'=>$currentFY])['id'];
        $fyList=$this->dbSystem->table('tbl_fy_mstr')
            ->select('id, fy')
            ->where('status', 1)
            ->where('id =', $currentFyID)
            ->get()
            ->getResultArray();
        $data['fyList'] = $fyList;
        if($this->request->getMethod()=='post') {
            $data = arrFilterSanitizeString($this->request->getVar());
        }
        return view('property/reports/ward_wise_demandH_report',$data);
    }
    public function demandreportAjax(){
        $data = (array)null;
        if ($this->request->getMethod() == 'post') {
            try {
                $start = sanitizeString($this->request->getVar('start'));

                $rowperpage = sanitizeString($this->request->getVar('length')); // Rows display per page

                $columnIndex = sanitizeString($this->request->getVar('order')[0]['column']); // Column index
                $columnName = sanitizeString($this->request->getVar('columns')[$columnIndex]['data']); // Column name
                $columnSortOrder = sanitizeString($this->request->getVar('order')[0]['dir']); // asc or desc
                $searchValue = sanitizeString($this->request->getVar('search')['value']); // Search value
                $orderBY = " ORDER BY " . $columnName . " " . $columnSortOrder;
                $limit = " LIMIT " . $rowperpage . " OFFSET " . $start;
                $whereQuery = "";
                $whereholdingNo = $searchValue;
                $wardNoWhere = "";
                $whereDateRange = "";
                $fnYearWhere = "";
                $new_ward_mstr_id = $this->request->getVar("new_ward_mstr_id");
                $fy_mstr_id = $this->request->getVar("fy_mstr_id");
                if ($new_ward_mstr_id!="") {
                    $wardNoWhere = " AND tbl_prop_dtl.new_ward_mstr_id='" . $new_ward_mstr_id . "'";
                }
                if ($fy_mstr_id!="") {
                    $fnYearWhere = " AND dmd.fy_mstr_id='" . $fy_mstr_id . "'";
                }
                $whereQuery=$wardNoWhere;
                $whereQueryWithSearch = "";
                if ($searchValue != '') {
                        $whereQueryWithSearch .= " AND tbl_prop_dtl.new_holding_no ILIKE '%".$whereholdingNo."%'";
                }
                $selectStatement="SELECT 
                    ROW_NUMBER () OVER (ORDER BY " . "tbl_prop_dtl.id" . " DESC) AS s_no,
                    tbl_prop_dtl.id,new_holding_no,tbl_prop_dtl.ward_mstr_id,view_ward_mstr.ward_no as ward_no,
                    wname as name,prop_address as address,0 as datefrom,0 as dateupto,0 as status,
                    (select SUM(COALESCE(dmd.amount,0)-COALESCE(dmd.adjust_amt,0)) from tbl_prop_demand dmd where dmd.prop_dtl_id=tbl_prop_dtl.id AND dmd.status= '1' $fnYearWhere) as total_demand,
                    (select count(dmd.amount) from tbl_prop_demand dmd where dmd.prop_dtl_id=tbl_prop_dtl.id AND dmd.status= '1' and paid_status=1 $fnYearWhere) as total_count
                    ";
                $sql = " FROM tbl_prop_dtl
                        left join (
                            select prop_dtl_id,array_to_string(array_agg(owner_name), ',') as wname from tbl_prop_owner_detail where status=1 group by prop_dtl_id
                        ) owner on owner.prop_dtl_id = tbl_prop_dtl.id
                        left join view_ward_mstr on view_ward_mstr.id=tbl_prop_dtl.new_ward_mstr_id 
                        WHERE 
                        tbl_prop_dtl.status= '1' AND char_length(tbl_prop_dtl.new_holding_no)>0 and tbl_prop_dtl.govt_saf_dtl_id is null
                        " . $whereQuery.$whereQueryWithSearch;
                // return $selectStatement.$sql;
                $totalRecords = $this->model_datatable->getTotalRecords($sql);
                if($totalRecords > 0)
                {
                    $totalRecordwithFilter = $this->model_datatable->getTotalRecordwithFilter($sql . $whereQueryWithSearch);
                    ## Fetch records
                    $fetchSql = $selectStatement . $sql . $whereQueryWithSearch . $orderBY . $limit;
                    $records = $this->model_datatable->getRecords($fetchSql, false);
                }
                else
                {
                    $totalRecordwithFilter = 0;
                    $records = [];
                }
              
                foreach ($records??[] as $k=>$result_)
                {
                   $records[$k]['status']='<span class="text-danger">Unpaid</span>';
                   if($result_['total_count']==4)
                   {
                       $records[$k]['status']='<span class="text-success">Paid</span>';
                   }
                   if($result_['total_demand']==0){
                        $count=$count+1;
                        unset($records[$k]);
                   }
                }
                $cont="SELECT count(tbl_prop_dtl.id) AS current_holding
                        FROM tbl_prop_dtl
                        JOIN (
                            select SUM(COALESCE(amount,0)-COALESCE(adjust_amt,0)) as amt,prop_dtl_id from tbl_prop_demand where tbl_prop_demand.status=1 
                            AND tbl_prop_demand.paid_status IN (0,1) and tbl_prop_demand.due_date is not null and fy_mstr_id=55 group by prop_dtl_id
                        ) tbl_prop_demand ON tbl_prop_dtl.id=tbl_prop_demand.prop_dtl_id
                        WHERE tbl_prop_dtl.status=1 and char_length(tbl_prop_dtl.new_holding_no)>0 and (tbl_prop_dtl.govt_saf_dtl_id is null or tbl_prop_dtl.govt_saf_dtl_id=0) and tbl_prop_demand.amt>0";
                $resultcount = $this->db->query($cont)->getFirstRow('array');
                $totalRecords = $resultcount['current_holding'];

                $totalRecordwithFilter=$totalRecords;
                $response = array(
                    "iTotalRecords" => $totalRecords,
                    "iTotalDisplayRecords" => $totalRecordwithFilter,
                    "aaData" => $records,
                    "mkmk" => $resultcount,
                    "total" => '<b style="padding-right:20px">Total :-' . $totalRecords . '</b>',
                );
                return json_encode($response);
            }
            catch (Exception $e)
            {
                print_var($e->getMessage());die;
            }
        }
    }

    public function exportdemandreport()
    {
        $data =(array)null;
        $session = session();
        $whereQuery = "";
        $wardNoWhere = "";
        $new_ward_mstr_id = $this->request->getVar("new_ward_mstr_id");
        $fy_mstr_id = $this->request->getVar("fy_mstr_id");
        if ($new_ward_mstr_id!="") {
            $wardNoWhere = " AND tbl_prop_dtl.new_ward_mstr_id='" . $new_ward_mstr_id . "'";
        }
        if ($fy_mstr_id!="") {
            $fnYearWhere = " AND dmd.fy_mstr_id='" . $fy_mstr_id . "'";
        }
        $whereQuery=$wardNoWhere;
        $whereQueryWithSearch = "";

        $selectStatement="SELECT 
                        ROW_NUMBER () OVER (ORDER BY " . "tbl_prop_dtl.id" . " DESC) AS s_no,
                        tbl_prop_dtl.id,new_holding_no,tbl_prop_dtl.ward_mstr_id,view_ward_mstr.ward_no as ward_no,
                        wname as name,prop_address as address,0 as datefrom,0 as dateupto,0 as status,
                        (select SUM(COALESCE(dmd.amount,0)-COALESCE(dmd.adjust_amt,0)) from tbl_prop_demand dmd where dmd.prop_dtl_id=tbl_prop_dtl.id AND dmd.status= '1' $fnYearWhere) as total_demand,
                        (select count(dmd.amount) from tbl_prop_demand dmd where dmd.prop_dtl_id=tbl_prop_dtl.id AND dmd.status= '1' and paid_status=1 $fnYearWhere) as total_count
                        ";
        $sql = " FROM tbl_prop_dtl
                left join (
                    select prop_dtl_id,array_to_string(array_agg(owner_name), ',') as wname from tbl_prop_owner_detail where status=1 group by prop_dtl_id
                ) owner on owner.prop_dtl_id = tbl_prop_dtl.id
                left join view_ward_mstr on view_ward_mstr.id=tbl_prop_dtl.new_ward_mstr_id 
                WHERE 
                    tbl_prop_dtl.status= '1' AND char_length(tbl_prop_dtl.new_holding_no)>0 AND tbl_prop_dtl.govt_saf_dtl_id is null
                " . $whereQuery.$whereQueryWithSearch;
        $fetchSql = $selectStatement . $sql . $whereQueryWithSearch;
        $results = $this->db->query($fetchSql, false);
        $records=$results->getResultArray();
        foreach ($records??[] as $k=>$result_)
        {
            $records[$k]['status']='Unpaid';
            if($result_['total_count']==4)
            {
                $records[$k]['status']='Paid';
            }
            if($result_['total_demand']==0){
                unset($records[$k]);
        }
        }
        $data['results']=$records;

        // dd($data);
        if($data['results'] > 0){
            $delimiter = ",";
            $filename = "wardwisedemanreport" . date('Y-m-d') . ".csv";

            // Create a file pointer
            $f = fopen('php://memory', 'w');

            // Set column headers
            $fields = array('Sl No.', 'New Ward No', 'Holding No.','Name','Address',
                'Due AMount','Status');
            fputcsv($f, $fields, $delimiter);
            $j=1;
            foreach($data['results'] as $leveldata){
                $rowdata=[$leveldata['s_no'],$leveldata['ward_no'],$leveldata['new_holding_no'],$leveldata['name'],
                    $leveldata['address'],$leveldata['total_demand'],$leveldata['status']];
                fputcsv($f, $rowdata, $delimiter);
            }

            fseek($f, 0);

            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="' . $filename . '";');
            fpassthru($f);
        }
        exit;
    }


    public function gbsafdemandreport() {
        $ulb_mstr_id = 1;
        $wardList = $this->model_ward_mstr->getWardList(['ulb_mstr_id'=>$ulb_mstr_id]);
        $data['wardList'] = $wardList;
        $currentFY = getFY();
        $fyList=['id'=>0,'fy'];
        $currentFyID = $this->model_fy_mstr->getFyByFy(['fy'=>$currentFY])['id'];
        $fyList=$this->dbSystem->table('tbl_fy_mstr')
            ->select('id, fy')
            ->where('status', 1)
            ->where('id =', $currentFyID)
            ->get()
            ->getResultArray();
        $data['fyList'] = $fyList;
        if($this->request->getMethod()=='post') {
            $data = arrFilterSanitizeString($this->request->getVar());
        }
        return view('property/reports/gbsaf_wise_demandG_report',$data);
    }
    public function demandreportGbsafAjax(){
        $data = (array)null;
        if ($this->request->getMethod() == 'post') {
            try {
                $start = sanitizeString($this->request->getVar('start'));

                $rowperpage = sanitizeString($this->request->getVar('length')); // Rows display per page

                $columnIndex = sanitizeString($this->request->getVar('order')[0]['column']); // Column index
                $columnName = sanitizeString($this->request->getVar('columns')[$columnIndex]['data']); // Column name
                $columnSortOrder = sanitizeString($this->request->getVar('order')[0]['dir']); // asc or desc
                $searchValue = sanitizeString($this->request->getVar('search')['value']); // Search value
                $orderBY = " ORDER BY " . $columnName . " " . $columnSortOrder;
                $limit = " LIMIT " . $rowperpage . " OFFSET " . $start;
                $whereQuery = "";
                $whereholdingNo = $searchValue;
                $wardNoWhere = "";
                $whereDateRange = "";
                $fnYearWhere = "";
                $new_ward_mstr_id = $this->request->getVar("new_ward_mstr_id");
                $fy_mstr_id = $this->request->getVar("fy_mstr_id");
                if ($new_ward_mstr_id!="") {
                    $wardNoWhere = " AND tbl_govt_saf_dtl.new_ward_mstr_id='" . $new_ward_mstr_id . "'";
                }
                if ($fy_mstr_id!="") {
                    $fnYearWhere = " AND dmd.fy_mstr_id='" . $fy_mstr_id . "'";
                }
                $whereQuery=$wardNoWhere;
                $whereQueryWithSearch = "";
                if ($searchValue != '') {
                    $whereQueryWithSearch .= " AND tbl_govt_saf_dtl.application_no ILIKE '%".$whereholdingNo."%'";
                }
                // view_ward_mstr.ward_no
                $selectStatement="SELECT 
                        ROW_NUMBER () OVER (ORDER BY " . "tbl_govt_saf_dtl.id" . " DESC) AS s_no,
                        tbl_govt_saf_dtl.id,application_no,tbl_govt_saf_dtl.ward_mstr_id,view_ward_mstr.ward_no as ward_no,
                        office_name as name,building_colony_address as address,0 as datefrom,0 as dateupto,0 as status,
                        (select sum(dmd.amount) from tbl_govt_saf_demand_dtl dmd where dmd.govt_saf_dtl_id=tbl_govt_saf_dtl.id AND dmd.status= '1' $fnYearWhere) as total_demand,
                        (select count(dmd.amount) from tbl_govt_saf_demand_dtl dmd where dmd.govt_saf_dtl_id=tbl_govt_saf_dtl.id AND dmd.status= '1' and paid_status=1 $fnYearWhere) as total_count
                        ";
                $sql = " FROM tbl_govt_saf_dtl
                    join view_ward_mstr on view_ward_mstr.id=tbl_govt_saf_dtl.new_ward_mstr_id 
                    WHERE 
                        tbl_govt_saf_dtl.status= '1'
                    " . $whereQuery.$whereQueryWithSearch;
                // return $selectStatement.$sql;
                $totalRecords = $this->model_datatable->getTotalRecords($sql);
                if($totalRecords > 0)
                {
                    $totalRecordwithFilter = $this->model_datatable->getTotalRecordwithFilter($sql . $whereQueryWithSearch);
                    ## Fetch records
                    $fetchSql = $selectStatement . $sql . $whereQueryWithSearch . $orderBY . $limit;
                    $records = $this->model_datatable->getRecords($fetchSql, false);
                }
                else
                {
                    $totalRecordwithFilter = 0;
                    $records = [];
                }
                foreach ($records??[] as $k=>$result_)
                {
                    $records[$k]['status']='<span class="text-danger">Unpaid</span>';
                    if($result_['total_count']==4)
                    {
                        $records[$k]['status']='<span class="text-danger">Paid</span>';
                    }
                }

                ///////////////////////
                $currentFY = getFY();

                $fyear = explode('-',$currentFY);

                $fromYear = $fyear[0].'-04-01';
                $uptoYear = $fyear[1].'-03-31';
                $summarysql="with currdemand as (
                    select 
                        sum(amount) as crrdemand 
                    from tbl_govt_saf_demand_dtl
                    inner join tbl_govt_saf_dtl on tbl_govt_saf_demand_dtl.govt_saf_dtl_id=tbl_govt_saf_dtl.id and tbl_govt_saf_dtl.status=1 
                    where 
                        tbl_govt_saf_demand_dtl.fy_mstr_id= '".$fy_mstr_id."'
                        and tbl_govt_saf_demand_dtl.status=1
                ),currcollect as (
                    select 
                        sum(tbl_govt_saf_collection_dtl.amount) as currcoll 
                    from tbl_govt_saf_transaction
                    inner join tbl_govt_saf_collection_dtl on tbl_govt_saf_transaction.id=tbl_govt_saf_collection_dtl.govt_saf_transaction_id 
                    inner join tbl_govt_saf_demand_dtl on tbl_govt_saf_collection_dtl.govt_saf_demand_dtl_id=tbl_govt_saf_demand_dtl.id 
                    where  
                        tbl_govt_saf_demand_dtl.fy_mstr_id='".$fy_mstr_id."' 
                        and tbl_govt_saf_demand_dtl.paid_status=1
                        and tbl_govt_saf_transaction.status>0 
                        and tbl_govt_saf_transaction.tran_date between '".$fromYear."' and '".$uptoYear."'
                )
                select
                (COALESCE(currdemand.crrdemand,0)) as current_demand,
                COALESCE(currcollect.currcoll,0) as current_collection
                from currdemand,currcollect";
                if ($resultsummary = $this->db->query($summarysql)->getFirstRow("array")) {
                $data["resultsummary"] = $resultsummary;
                }
                $response = array(
                    "iTotalRecords" => $totalRecords,
                    "iTotalDisplayRecords" => $totalRecordwithFilter,
                    "aaData" => $records,
                    "total" => '<b style="padding-right:20px">Total :-' . $totalRecords . '</b>',
                    "summary" => $resultsummary,
                );
                return json_encode($response);
            }
            catch (Exception $e)
            {
                print_var($e->getMessage());die;
            }
        }
    }

    public function exportgbsafreport()
    {
        $data =(array)null;
        $session = session();
        $whereQuery = "";
        $wardNoWhere = "";
        $new_ward_mstr_id = $this->request->getVar("new_ward_mstr_id");
        $fy_mstr_id = $this->request->getVar("fy_mstr_id");
        if ($new_ward_mstr_id!="") {
            $wardNoWhere = " AND tbl_govt_saf_dtl.new_ward_mstr_id='" . $new_ward_mstr_id . "'";
        }
        if ($fy_mstr_id!="") {
            $fnYearWhere = " AND dmd.fy_mstr_id='" . $fy_mstr_id . "'";
        }
        $whereQuery=$wardNoWhere;
        $whereQueryWithSearch = "";

        $selectStatement="SELECT 
                        ROW_NUMBER () OVER (ORDER BY " . "tbl_govt_saf_dtl.id" . " DESC) AS s_no,
                        tbl_govt_saf_dtl.id,application_no,tbl_govt_saf_dtl.ward_mstr_id,view_ward_mstr.ward_no as ward_no,
                        office_name as name,building_colony_address as address,0 as datefrom,0 as dateupto,0 as status,
                        (select sum(dmd.amount) from tbl_govt_saf_demand_dtl dmd where dmd.govt_saf_dtl_id=tbl_govt_saf_dtl.id AND dmd.status= '1' $fnYearWhere) as total_demand,
                        (select count(dmd.amount) from tbl_govt_saf_demand_dtl dmd where dmd.govt_saf_dtl_id=tbl_govt_saf_dtl.id AND dmd.status= '1' and paid_status=1 $fnYearWhere) as total_count
                        ";
        $sql = " FROM tbl_govt_saf_dtl
                    join view_ward_mstr on view_ward_mstr.id=tbl_govt_saf_dtl.new_ward_mstr_id 
                    WHERE 
                        tbl_govt_saf_dtl.status= '1'" . $whereQuery.$whereQueryWithSearch;
        $fetchSql = $selectStatement . $sql . $whereQueryWithSearch;
        $results = $this->db->query($fetchSql, false);
        $records=$results->getResultArray();
        foreach ($records??[] as $k=>$result_)
        {
            $records[$k]['status']='Unpaid';
            if($result_['total_count']==4)
            {
                $records[$k]['status']='Paid';
            }
        }
        $data['results']=$records;

        if($data['results'] > 0){
            $delimiter = ",";
            $filename = "gbsafdemanreport" . date('Y-m-d') . ".csv";

            $f = fopen('php://memory', 'w');

            $fields = array('Sl No.', 'New Ward No', 'GBSAF No.','Name','Address',
                'Due AMount','Status');
            fputcsv($f, $fields, $delimiter);
            $j=1;
            foreach($data['results'] as $leveldata){
                $rowdata=[$leveldata['s_no'],$leveldata['ward_no'],$leveldata['application_no'],$leveldata['name'],
                    $leveldata['address'],$leveldata['total_demand'],$leveldata['status']];
                fputcsv($f, $rowdata, $delimiter);
            }

            fseek($f, 0);

            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="' . $filename . '";');
            fpassthru($f);
        }
        exit;
    }

    public function wardwiseGenratedNoticeSummary(){
        $data = $this->request->getVar();
        try{
            $ulbDtls = Session()->get("ulb_dtl");
            $wardList = $this->model_ward_mstr->getWardListWithSession(['ulb_mstr_id'=>$ulbDtls["ulb_mstr_id"]??1]);
            $data['wardList'] = $wardList;
            if(!isset($data["fromDate"])){
                $data["fromDate"] =date("Y-m-d");
            }
            if(!isset($data["uptoDate"])){
                $data["uptoDate"] = date("Y-m-d");
            }
            $sql = "with genrated_notice as(
                        select tbl_prop_notices.*,(tbl_prop_notices.notice_date + INTERVAL '30 days')::date as expiry_date,tbl_prop_dtl.ward_mstr_id
                        from tbl_prop_notices
                        join tbl_prop_dtl on tbl_prop_dtl.id = tbl_prop_notices.prop_dtl_id
                        where tbl_prop_notices.notice_type = 'Demand'
                            and tbl_prop_notices.notice_date between '".$data["fromDate"]."' and '".$data["uptoDate"]."'
                    ),
                    payment_agents_notice as (
                        select  genrated_notice.id,
                            sum(tbl_transaction.payable_amt) as payable_amt,
                            count(tbl_transaction.id) as payment_in_sloat
                        from genrated_notice
                        join tbl_transaction on tbl_transaction.prop_dtl_id = genrated_notice.prop_dtl_id 
                        where tbl_transaction.tran_date >= genrated_notice.notice_date 
                                and tbl_transaction.upto_fyear <= genrated_notice.upto_fyear
                                and tbl_transaction.upto_qtr <= tbl_transaction.upto_qtr
                                and tbl_transaction.status in(1,2)
                        group by genrated_notice.id
                    )
                    select view_ward_mstr.id,view_ward_mstr.ward_no,
                        COALESCE(notices.generated_notice_count,0)generated_notice_count,
                        COALESCE(notices.generated_notice_demand_amount,0)generated_notice_demand_amount,
                        COALESCE(notices.generated_notice_penalty,0)generated_notice_penalty,
                        COALESCE(notices.generated_notice_by_ulb_count,0)generated_notice_by_ulb_count,
                        COALESCE(notices.generated_notice_by_ulb_demand_amount,0)generated_notice_by_ulb_demand_amount,
                        COALESCE(notices.generated_notice_by_ulb_approved_count,0)generated_notice_by_ulb_approved_count,
                        COALESCE(notices.generated_notice_by_ulb_approved_amount,0)generated_notice_by_ulb_approved_amount,
                        COALESCE(notices.generated_notice_served_count,0)generated_notice_served_count,
                        COALESCE(notices.generated_notice_served_amount,0)generated_notice_served_amount,
                        COALESCE(notices.payment_agents_notice_count,0)payment_agents_notice_count,
                        COALESCE(notices.payment_agents_notice_payable_amt,0)payment_agents_notice_payable_amt,
                        COALESCE(notices.payment_agents_notice_payment_in_sloat,0)payment_agents_notice_payment_in_sloat,
                        COALESCE(notices.expiry_not_pay_count,0)expiry_not_pay_count,
                        COALESCE(notices.expiry_not_pay_demand_amount,0)expiry_not_pay_demand_amount
                    from view_ward_mstr
                    left join(
                        select genrated_notice.ward_mstr_id,
                            count(genrated_notice.id)as generated_notice_count,
                            sum(genrated_notice.demand_amount)as generated_notice_demand_amount,
                            sum(genrated_notice.penalty)as generated_notice_penalty,
                            null::int as generated_notice_by_ulb_count,
                            null::int as generated_notice_by_ulb_demand_amount,
                            null::int as generated_notice_by_ulb_approved_count,
                            null::int as generated_notice_by_ulb_approved_amount,
                            null::int as generated_notice_served_count,
                            null::int as generated_notice_served_amount,
                            count(payment_agents_notice.id)as payment_agents_notice_count,
                            sum(payment_agents_notice.payable_amt)as payment_agents_notice_payable_amt,
                            sum(payment_agents_notice.payment_in_sloat)as payment_agents_notice_payment_in_sloat,
                            count(CASE when expiry_date<current_date and payment_agents_notice.id is null 
                                    then genrated_notice.id end
                            ) as expiry_not_pay_count,
                            sum(CASE when expiry_date<current_date and payment_agents_notice.id is null 
                                    then genrated_notice.demand_amount end
                            ) as expiry_not_pay_demand_amount
                        from genrated_notice
                        left join payment_agents_notice on payment_agents_notice.id = genrated_notice.id	 
                        group by genrated_notice.ward_mstr_id
                    )notices on notices.ward_mstr_id =view_ward_mstr.id
                    WHERE 1=1
                    ".(isset($data["ward_id"]) && ($data["ward_id"]!="") ?" AND view_ward_mstr.id = ".$data["ward_id"]:"")."
                    order by view_ward_mstr.id
            ";
            $data["reports"] = $this->db->query($sql)->getResultArray();
            $summary =[];
            foreach($data["reports"][0] as $key=>$val){
                $summary[$key] = array_sum(array_column($data["reports"], $key))??0;
                if($key=="ward_no"){
                    $summary[$key] = sizeof(array_column($data["reports"], $key));
                }
            }
            $summary["id"] = "Total";
            $data["summary"][] = $summary;
            // dd($sql);
            return view('property/reports/ward_wise_generated_summary', $data);
        }
        catch(Exception $e){
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function demandNoticeGeneratedList(){
        $data = $this->request->getVar();
        if(!isset($data["fromDate"])){
            $data["fromDate"] =date("Y-m-d");
        }
        if(!isset($data["uptoDate"])){
            $data["uptoDate"] = date("Y-m-d");
        }
        $sql = "with genrated_notice as(
                    select tbl_prop_notices.*,(tbl_prop_notices.notice_date + INTERVAL '30 days')::date as expiry_date,tbl_prop_dtl.ward_mstr_id,
                        tbl_prop_dtl.holding_no , tbl_prop_dtl.new_holding_no, tbl_prop_dtl.prop_address
                    from tbl_prop_notices
                    join tbl_prop_dtl on tbl_prop_dtl.id = tbl_prop_notices.prop_dtl_id
                    where tbl_prop_notices.notice_type = 'Demand'
                        and tbl_prop_notices.notice_date between '".$data["fromDate"]."' and '".$data["uptoDate"]."'
                ),
                owneres as(
                    select tbl_prop_owner_detail.prop_dtl_id,
                        string_agg(tbl_prop_owner_detail.owner_name,' ,')owner_name,
                        string_agg(tbl_prop_owner_detail.mobile_no::text,' ,')mobile_no
                    from tbl_prop_owner_detail 
                    join genrated_notice on genrated_notice.prop_dtl_id = tbl_prop_owner_detail.prop_dtl_id
                    where tbl_prop_owner_detail.status =1
                    group by tbl_prop_owner_detail.prop_dtl_id

                ),
                payment_agents_notice as (
                    select  genrated_notice.id,
                        string_agg(tran_no,' ,') as tran_no,
                        string_agg(tran_date::text,' ,') as tran_date,
                        string_agg(
							(CASE WHEN tbl_transaction.from_fyear is not null 
									THEN CONCAT(tbl_transaction.from_fyear, '(', tbl_transaction.from_qtr, ')', ' / ', tbl_transaction.upto_fyear, '(', tbl_transaction.upto_qtr, ')') 
                            ELSE '' END),
                        ' ,</br>' )AS payment_from_upto_fy_qtr,
                        string_agg(tran_mode,' ,') as tran_mode,
                        string_agg(payable_amt::text,' ,') as payable_amt,
                        sum(tbl_transaction.payable_amt) as total_payable_amt,
                        count(tbl_transaction.id) as payment_in_sloat
                    from genrated_notice
                    join tbl_transaction on tbl_transaction.prop_dtl_id = genrated_notice.prop_dtl_id 
                    where tbl_transaction.tran_date >= genrated_notice.notice_date 
                            and tbl_transaction.upto_fyear <= genrated_notice.upto_fyear
                            and tbl_transaction.upto_qtr <= tbl_transaction.upto_qtr
                            and tbl_transaction.status in(1,2)
                    group by genrated_notice.id
                )                    
                select view_ward_mstr.ward_no,
                    CASE WHEN char_length(genrated_notice.new_holding_no) > 0 
                        THEN genrated_notice.new_holding_no 
                        ELSE genrated_notice.holding_no END as holding_no,
                    genrated_notice.prop_address,
                    owneres.owner_name, owneres.mobile_no ,
                    genrated_notice.notice_no, genrated_notice.serial_no, 
                    genrated_notice.notice_type, genrated_notice.notice_date, 
                    genrated_notice.from_qtr, genrated_notice.from_fyear, genrated_notice.upto_qtr, genrated_notice.upto_fyear, 
                    genrated_notice.demand_amount, genrated_notice.penalty,
                    genrated_notice.expiry_date,
                    payment_agents_notice.tran_no , payment_agents_notice.tran_date, payment_agents_notice.tran_mode, 
                    payment_agents_notice.total_payable_amt,
                    view_emp_details.emp_name,
                    CASE WHEN genrated_notice.from_fyear is not null THEN CONCAT(genrated_notice.from_fyear, '(', genrated_notice.from_qtr, ')', ' / ', genrated_notice.upto_fyear, '(', genrated_notice.upto_qtr, ')') ELSE '' END AS demand_from_upto_fy_qtr,
                    payment_from_upto_fy_qtr

                from genrated_notice
                left join owneres on owneres.prop_dtl_id = genrated_notice.prop_dtl_id
                left join payment_agents_notice on payment_agents_notice.id = genrated_notice.id
                left join view_ward_mstr on view_ward_mstr.id = genrated_notice.ward_mstr_id
                left join view_emp_details on view_emp_details.id = genrated_notice.generated_by_emp_details_id 
                WHERE 1=1
                ".(isset($data["ward_id"]) && ($data["ward_id"]!="") ?" AND view_ward_mstr.id = ".$data["ward_id"]:"")."
                ";
                if(isset($data["noticeType"]) && $data["noticeType"]=="payment_done"){
                    $sql.=" AND payment_agents_notice.id IS NOT NULL 
                         ";
                }
                elseif(isset($data["noticeType"]) && $data["noticeType"]=="payment_not_done"){
                    $sql.=" AND payment_agents_notice.id IS NULL 
                         ";
                }
                $sql.="order by view_ward_mstr.id";

        $data["reports"] = $this->db->query($sql)->getResultArray();
        
        // dd($data["reports"],$sql);die;
        return view('property/reports/demandNoticeGenratedList', $data);
    }

    public function propPhysicalStatus(){
        $data = $this->request->getVar();
        $fromDate = $uptoDate = $ward_id = null;
        if($this->request->getVar("fromDate") && $this->request->getVar("uptoDate")){
            $fromDate = $this->request->getVar("fromDate");
            $uptoDate = $this->request->getVar("uptoDate");
        }
        if($this->request->getVar("ward_id")){
            $ward_id = $this->request->getVar("ward_id");
        }
        if($this->request->getMethod()=='post' || $this->request->getVar("section")){
            $sql = "
                with goe_tags as(
                    select distinct(tbl_saf_geotag_upload_dtl.geotag_dtl_id) geotag_dtl_id
                    from tbl_saf_geotag_upload_dtl 
                ),
                active_holding as (
                    select count(tbl_prop_dtl.id) as active_holding,
                        count(case when tbl_prop_dtl.assessment_type='New Assessment' then tbl_prop_dtl.id end ) as active_holding_new_assessment,
                        count(case when tbl_prop_dtl.assessment_type='Reassessment' then tbl_prop_dtl.id end ) as active_holding_reassessment,
                        count(case when tbl_prop_dtl.assessment_type in ('Mutation','Mutation with Reassessment') then tbl_prop_dtl.id end ) as active_holding_mutation
                    from tbl_prop_dtl
                    left join tbl_prop_saf_deactivation on tbl_prop_saf_deactivation.prop_dtl_id = tbl_prop_dtl.id
                        AND tbl_prop_saf_deactivation.prop_type= 'Property' and tbl_prop_dtl.status=0
                        ".($fromDate ? " AND tbl_prop_saf_deactivation.deactivation_date::date < '$fromDate' " : "")."	
                    where 1=1 AND tbl_prop_saf_deactivation.id is null
                    ".($fromDate ? " AND tbl_prop_dtl.created_on::date < '$fromDate'" : "" )."
                ),
                opending_hh as (
                    select count(id) as opening_assess_hh,
                        count(case when tbl_saf_dtl.assessment_type='New Assessment' then id end ) as opening_new_assessment,
                        count(case when tbl_saf_dtl.assessment_type='Reassessment' then id end ) as opening_reassessment,
                        count(case when tbl_saf_dtl.assessment_type in ('Mutation','Mutation with Reassessment') then id end ) as opening_mutation,
                    
                        count(case when goe_tags.geotag_dtl_id is not null then id end) as geotag_done,		
                        count(case when tbl_saf_dtl.assessment_type='New Assessment' and goe_tags.geotag_dtl_id is not null then id end ) as geotag_done_new_assessment,
                        count(case when tbl_saf_dtl.assessment_type='Reassessment' and goe_tags.geotag_dtl_id is not null then id end ) as geotag_done_reassessment,
                        count(case when tbl_saf_dtl.assessment_type in ('Mutation','Mutation with Reassessment') and goe_tags.geotag_dtl_id is not null then id end ) as geotag_done_mutation,
                    
                        count( case when goe_tags.geotag_dtl_id is null then id end) as  not_geotag_done,	
                        count(case when tbl_saf_dtl.assessment_type='New Assessment' and goe_tags.geotag_dtl_id is null then id end ) as not_geotag_done_new_assessment,
                        count(case when tbl_saf_dtl.assessment_type='Reassessment' and goe_tags.geotag_dtl_id is null then id end ) as not_geotag_done_reassessment,
                        count(case when tbl_saf_dtl.assessment_type in ('Mutation','Mutation with Reassessment') and goe_tags.geotag_dtl_id is null then id end ) as not_geotag_done_mutation,
                    
                        count( case when tbl_saf_dtl.payment_status in(1,2) then id end) as full_paid_hh,		
                        count(case when tbl_saf_dtl.assessment_type='New Assessment' and tbl_saf_dtl.payment_status in(1,2) then id end ) as full_paid_new_assessment,
                        count(case when tbl_saf_dtl.assessment_type='Reassessment' and tbl_saf_dtl.payment_status in(1,2) then id end ) as full_paid_reassessment,
                        count(case when tbl_saf_dtl.assessment_type in ('Mutation','Mutation with Reassessment') and tbl_saf_dtl.payment_status in(1,2) then id end ) as full_paid_mutation,
                    
                        count( case when tbl_saf_dtl.saf_pending_status =2 then id end) as btc,
                        count(case when tbl_saf_dtl.assessment_type='New Assessment' and tbl_saf_dtl.saf_pending_status =2 then id end ) as btc_new_assessment,
                        count(case when tbl_saf_dtl.assessment_type='Reassessment' and tbl_saf_dtl.saf_pending_status =2 then id end ) as btc_reassessment,
                        count(case when tbl_saf_dtl.assessment_type in ('Mutation','Mutation with Reassessment') and tbl_saf_dtl.saf_pending_status =2 then id end ) as btc_mutation,
                    
                        count( case when tbl_saf_dtl.saf_pending_status =0 and tbl_saf_dtl.payment_status in(1,2) then id end) as pending_at_level,		
                        count(case when tbl_saf_dtl.assessment_type='New Assessment' and tbl_saf_dtl.saf_pending_status =0 and tbl_saf_dtl.payment_status in(1,2) then id end ) as pending_at_level_new_assessment,
                        count(case when tbl_saf_dtl.assessment_type='Reassessment' and tbl_saf_dtl.saf_pending_status =0 and tbl_saf_dtl.payment_status in(1,2) then id end ) as pending_at_level_reassessment,
                        count(case when tbl_saf_dtl.assessment_type in ('Mutation','Mutation with Reassessment') and tbl_saf_dtl.saf_pending_status =0 and tbl_saf_dtl.payment_status in(1,2) then id end ) as pending_at_level_mutation
                    from tbl_saf_dtl
                    left join goe_tags on goe_tags.geotag_dtl_id = tbl_saf_dtl.id
                    where 1=1 
                        ".($fromDate && $uptoDate ? " AND apply_date::date between '$fromDate' and '$uptoDate' " : "")."
                ),
                last_remarks as(
                    select saf_dtl_id,forward_date,ROW_NUMBER() OVER(PARTITION BY saf_dtl_id ORDER BY id DESC) as row_num
                    from tbl_level_pending_dtl	
                ),
                approve_saf as (
                    select count(tbl_saf_dtl.id) as approved_saf,
                        count(case when tbl_saf_dtl.assessment_type='New Assessment'  then tbl_saf_dtl.id end ) as approved_saf_new_assessment,
                        count(case when tbl_saf_dtl.assessment_type='Reassessment'  then tbl_saf_dtl.id end ) as approved_saf_reassessment,
                        count(case when tbl_saf_dtl.assessment_type in ('Mutation','Mutation with Reassessment') then tbl_saf_dtl.id end ) as approved_saf_mutation
                    from tbl_saf_dtl
                    join last_remarks on last_remarks.saf_dtl_id = tbl_saf_dtl.id and row_num =1
                    where tbl_saf_dtl.saf_pending_status =1 
                        ".($fromDate && $uptoDate ? " AND forward_date::date between '$fromDate' and '$uptoDate' " : "")."
                ),
                deactivate_holding as (
                    select count(distinct(tbl_prop_dtl.id)) as deactivated_hh,
                        count(case when tbl_prop_dtl.assessment_type='New Assessment'  then tbl_prop_dtl.id end ) as deactivated_hh_new_assessment,
                        count(case when tbl_prop_dtl.assessment_type='Reassessment'  then tbl_prop_dtl.id end ) as deactivated_hh_reassessment,
                        count(case when tbl_prop_dtl.assessment_type in ('Mutation','Mutation with Reassessment') then tbl_prop_dtl.id end ) as deactivated_hh_mutation
                    from tbl_prop_saf_deactivation
                    join tbl_prop_dtl on tbl_prop_dtl.id = tbl_prop_saf_deactivation.prop_dtl_id
                    where tbl_prop_saf_deactivation.prop_type= 'Property' and tbl_prop_dtl.status=0
                        ".($fromDate && $uptoDate ? " AND tbl_prop_saf_deactivation.deactivation_date::date between '$fromDate' and '$uptoDate' " : "")."	
                ),
                memo as (
                    select count(tbl_saf_memo_dtl.saf_dtl_id) as total_memo,		
                        count(case when tbl_saf_dtl.assessment_type='New Assessment'  then tbl_saf_memo_dtl.saf_dtl_id end ) as total_memo_new_assessment,
                        count(case when tbl_saf_dtl.assessment_type='Reassessment'  then tbl_saf_memo_dtl.saf_dtl_id end ) as total_memo_reassessment,
                        count(case when tbl_saf_dtl.assessment_type in ('Mutation','Mutation with Reassessment') then tbl_saf_memo_dtl.saf_dtl_id end ) as total_memo_mutation,

                        count(case when tbl_saf_memo_dtl.memo_type='FAM' then tbl_saf_memo_dtl.saf_dtl_id end) as total_fam,
                        count(case when tbl_saf_dtl.assessment_type='New Assessment' and tbl_saf_memo_dtl.memo_type='FAM'  then tbl_saf_memo_dtl.saf_dtl_id end ) as total_fam_new_assessment,
                        count(case when tbl_saf_dtl.assessment_type='Reassessment' and tbl_saf_memo_dtl.memo_type='FAM' then tbl_saf_memo_dtl.saf_dtl_id end ) as total_fam_reassessment,
                        count(case when tbl_saf_dtl.assessment_type in ('Mutation','Mutation with Reassessment') and tbl_saf_memo_dtl.memo_type='FAM' then tbl_saf_memo_dtl.saf_dtl_id end ) as total_fam_mutation,

                        count(case when tbl_saf_memo_dtl.memo_type='SAM' then tbl_saf_memo_dtl.saf_dtl_id end) as total_sam,
                        count(case when tbl_saf_dtl.assessment_type='New Assessment' and tbl_saf_memo_dtl.memo_type='SAM'  then tbl_saf_memo_dtl.saf_dtl_id end ) as total_sam_new_assessment,
                        count(case when tbl_saf_dtl.assessment_type='Reassessment' and tbl_saf_memo_dtl.memo_type='SAM' then tbl_saf_memo_dtl.saf_dtl_id end ) as total_sam_reassessment,
                        count(case when tbl_saf_dtl.assessment_type in ('Mutation','Mutation with Reassessment') and tbl_saf_memo_dtl.memo_type='SAM' then tbl_saf_memo_dtl.saf_dtl_id end ) as total_sam_mutation
                        
                    from tbl_saf_memo_dtl
                    join tbl_saf_dtl on tbl_saf_dtl.id = tbl_saf_memo_dtl.saf_dtl_id
                    where tbl_saf_memo_dtl.status=1 
                        ".($fromDate && $uptoDate ? " AND tbl_saf_memo_dtl.created_on::date between '$fromDate' and '$uptoDate' " : "")."
                )
                select *
                from active_holding,opending_hh,approve_saf,deactivate_holding,memo
            ";
            $data["result"] = $this->db->query($sql)->getFirstRow("array");
            
            if($this->request->getVar("section")){
                if($this->request->getVar("section")==2 && $this->request->getVar("row")==1){
                    
                    $data["result"]["new"] = ($data["result"]["opening_new_assessment"]??0) - $data["result"]["total_sam_new_assessment"];
                    $data["result"]["re"] = ($data["result"]["opening_reassessment"]??0) - $data["result"]["total_sam_reassessment"];
                    $data["result"]["mu"] = ($data["result"]["opening_mutation"]??0) - $data["result"]["total_sam_mutation"];
                    $data["result"]["total"] = ($data["result"]["opening_assess_hh"]??0) - $data["result"]["total_sam"];
                }
                elseif($this->request->getVar("section")==2 && $this->request->getVar("row")==2){
                    $data["result"]["new"] = $data["result"]["not_geotag_done_new_assessment"];
                    $data["result"]["re"] = $data["result"]["not_geotag_done_reassessment"];
                    $data["result"]["mu"] = $data["result"]["not_geotag_done_mutation"];
                    $data["result"]["total"] = $data["result"]["not_geotag_done"];
                }
                elseif($this->request->getVar("section")==2 && $this->request->getVar("row")==3){
                    $data["result"]["new"] = $data["result"]["full_paid_new_assessment"];
                    $data["result"]["re"] = $data["result"]["full_paid_reassessment"];
                    $data["result"]["mu"] = $data["result"]["full_paid_mutation"];
                    $data["result"]["total"] = $data["result"]["full_paid_hh"];
                }
                elseif($this->request->getVar("section")==2 && $this->request->getVar("row")==4){
                    $data["result"]["new"] = $data["result"]["btc_new_assessment"];
                    $data["result"]["re"] = $data["result"]["btc_reassessment"];
                    $data["result"]["mu"] = $data["result"]["btc_mutation"];
                    $data["result"]["total"] = $data["result"]["btc"];
                }
                elseif($this->request->getVar("section")==2 && $this->request->getVar("row")==5){
                    $data["result"]["new"] = $data["result"]["pending_at_level_new_assessment"];
                    $data["result"]["re"] = $data["result"]["pending_at_level_reassessment"];
                    $data["result"]["mu"] = $data["result"]["pending_at_level_mutation"];
                    $data["result"]["total"] = $data["result"]["pending_at_level"];
                }
                return view("property/reports/propPhysicalStatusSub",$data);
            }
        }
        // dd($data);
        return view("property/reports/propPhysicalStatus",$data);
    }

    public function PropertyTaxAndVlTax(){
        $data = $this->request->getVar();
        $ulb_mstr_id = 1;
        $data["wardList"] = $this->model_ward_mstr->getWardList(['ulb_mstr_id'=>$ulb_mstr_id]);
        $data["fyList"] = fy_year_list();
        if($this->request->getMethod()=="post"){
            set_time_limit(500);
            $fyear = $this->request->getVar("fyear");
            list($fromYear,$uptoYear)=explode("-",$fyear);
            $from_date = $fromYear."-04-01";
            $upto_date = $uptoYear."-03-31";
            $ward_mstr_id = "";
            if($this->request->getVar("ward_id")){
                $ward_mstr_id = $this->request->getVar("ward_id");
            }
            $sql = "
                with prop as (
                    select tbl_prop_dtl.id,ward_mstr_id,new_ward_mstr_id ,
                        case when trim(new_holding_no) ='' then holding_no else new_holding_no end as holding_no,
                        new_holding_no,holding_type,saf_dtl_id,govt_saf_dtl_id,
		                case when  char_length(tbl_prop_dtl.new_holding_no)<=0 then false else true end as is_collectable
                    from tbl_prop_dtl
                    where status = 1 
                    ".($ward_mstr_id ? " AND tbl_prop_dtl.ward_mstr_id = $ward_mstr_id":"")."
                ),
                demands as (
                    select prop.id,sum(COALESCE(tbl_prop_demand.amount, 0) - COALESCE(tbl_prop_demand.adjust_amt, 0)) as total_demand,
                        sum(CASE WHEN fyear< '$fyear' then COALESCE(tbl_prop_demand.amount, 0) - COALESCE(tbl_prop_demand.adjust_amt, 0) else 0 end)as arrear_demand,
                        sum(CASE WHEN fyear= '$fyear' then COALESCE(tbl_prop_demand.amount, 0) - COALESCE(tbl_prop_demand.adjust_amt, 0) else 0 end)as current_demand
                    from tbl_prop_demand
                    join prop on prop.id = tbl_prop_demand.prop_dtl_id
                    where tbl_prop_demand.status = 1 and tbl_prop_demand.fyear <= '$fyear'
                    group by prop.id
                ),
                collection as (
                    select prop.id,
                        sum(tbl_collection.amount) as total_collection,
                        sum(CASE WHEN tbl_prop_demand.fyear< '$fyear' then tbl_collection.amount else 0 end)as arrear_collection,
                        sum(CASE WHEN tbl_prop_demand.fyear= '$fyear' then tbl_collection.amount else 0 end)as current_collection
                    from tbl_collection
                    join tbl_transaction on tbl_transaction.id = tbl_collection.transaction_id
                    join tbl_prop_demand on tbl_prop_demand.id = tbl_collection.prop_demand_id
                    join prop on prop.id = tbl_prop_demand.prop_dtl_id
                    where tbl_transaction.status in(1,2) 
                        and tbl_transaction.tran_date between '$from_date' and '$upto_date'
                    group by prop.id
                ),
                prive_collectin as (
                    SELECT 
                        prop.id,
                        SUM(COALESCE(tbl_collection.amount, 0)) AS total_collection
                    FROM prop
                    INNER JOIN tbl_collection on tbl_collection.prop_dtl_id=prop.id AND tbl_collection.created_on::date < '$from_date'
                    WHERE tbl_collection.status=1 
                    GROUP BY prop.id
                ),
                penalty_rebates as (
                    select prop.id ,
                        sum(case when tbl_transaction_fine_rebet_details.value_add_minus='Add' then tbl_transaction_fine_rebet_details.amount end) as penalty,
                        sum(case when tbl_transaction_fine_rebet_details.value_add_minus!='Add' then tbl_transaction_fine_rebet_details.amount end) as rebate	
                    from tbl_transaction_fine_rebet_details
                    JOIN tbl_transaction ON tbl_transaction.id = tbl_transaction_fine_rebet_details.transaction_id
                    JOIN prop on prop.id = tbl_transaction.prop_dtl_id 
                    where tbl_transaction.tran_type='Property'  
                        AND tbl_transaction.status in (1, 2) 	
                        and tbl_transaction.tran_date between '$from_date' and '$upto_date'
                    group by prop.id
                ),
                gov_prop as (
                    select tbl_govt_saf_dtl.id,tbl_govt_saf_dtl.ward_mstr_id,tbl_govt_saf_dtl.new_ward_mstr_id ,office_name,application_no,
                        case when tbl_prop_dtl.new_holding_no is null then tbl_prop_dtl.holding_no else tbl_prop_dtl.new_holding_no end as holding_no,
                        tbl_prop_dtl.new_holding_no,
                        case when govt_building_type_mstr_id in(3,4) then 'CENTRAL_GOV' 
                            when govt_building_type_mstr_id not in(3,4) then  'STATE_GOV' 
                            else tbl_prop_dtl.holding_type
                            end as holding_type,
                        tbl_prop_dtl.saf_dtl_id,
                        tbl_prop_dtl.govt_saf_dtl_id,
		                true as is_collectable
                    from tbl_govt_saf_dtl
                    left join tbl_prop_dtl on tbl_prop_dtl.govt_saf_dtl_id =tbl_govt_saf_dtl.id 
                    where tbl_govt_saf_dtl.status = 1 
                    ".($ward_mstr_id ? " AND tbl_govt_saf_dtl.ward_mstr_id = $ward_mstr_id":"")."
                ),
                gov_demands as (
                    select gov_prop.id,sum(COALESCE(tbl_govt_saf_demand_dtl.amount, 0) - COALESCE(tbl_govt_saf_demand_dtl.adjust_amount, 0)) as total_demand,
                        sum(CASE WHEN fyear< '$fyear' then COALESCE(tbl_govt_saf_demand_dtl.amount, 0) - COALESCE(tbl_govt_saf_demand_dtl.adjust_amount, 0) else 0 end)as arrear_demand,
                        sum(CASE WHEN fyear= '$fyear' then COALESCE(tbl_govt_saf_demand_dtl.amount, 0) - COALESCE(tbl_govt_saf_demand_dtl.adjust_amount, 0) else 0 end)as current_demand
                    from tbl_govt_saf_demand_dtl
                    join gov_prop on gov_prop.id = tbl_govt_saf_demand_dtl.govt_saf_dtl_id
                    where tbl_govt_saf_demand_dtl.status = 1 and tbl_govt_saf_demand_dtl.fyear <= '$fyear'
                    group by gov_prop.id
                ),
                gov_collection as (
                    select gov_prop.id,
                        sum(tbl_govt_saf_collection_dtl.amount) as total_collection,
                        sum(CASE WHEN tbl_govt_saf_demand_dtl.fyear< '$fyear' then tbl_govt_saf_collection_dtl.amount else 0 end)as arrear_collection,
                        sum(CASE WHEN tbl_govt_saf_demand_dtl.fyear= '$fyear' then tbl_govt_saf_collection_dtl.amount else 0 end)as current_collection
                    from tbl_govt_saf_collection_dtl
                    join tbl_govt_saf_transaction on tbl_govt_saf_transaction.id = tbl_govt_saf_collection_dtl.govt_saf_transaction_id
                    join tbl_govt_saf_demand_dtl on tbl_govt_saf_demand_dtl.id = tbl_govt_saf_collection_dtl.govt_saf_demand_dtl_id
                    join gov_prop on gov_prop.id = tbl_govt_saf_demand_dtl.govt_saf_dtl_id
                    where tbl_govt_saf_transaction.status in(1,2) 
                        and tbl_govt_saf_transaction.tran_date between '$from_date' and '$upto_date'
                    group by gov_prop.id
                ),
                gov_prive_collectin as (
                    SELECT 
                        gov_prop.id,
                        SUM(COALESCE(tbl_govt_saf_collection_dtl.amount, 0)) AS total_collection
                    FROM gov_prop
                    INNER JOIN tbl_govt_saf_collection_dtl on tbl_govt_saf_collection_dtl.govt_saf_dtl_id=gov_prop.id 
                        AND tbl_govt_saf_collection_dtl.created_on::date < '$from_date'
                    WHERE tbl_govt_saf_collection_dtl.status=1 
                    GROUP BY gov_prop.id
                ),
                gov_penalty_rebates as (
                    select gov_prop.id ,
                        sum(case when tbl_govt_saf_transaction_fine_rebet_details.value_add_minus='Add' then tbl_govt_saf_transaction_fine_rebet_details.amount end) as penalty,
                        sum(case when tbl_govt_saf_transaction_fine_rebet_details.value_add_minus!='Add' then tbl_govt_saf_transaction_fine_rebet_details.amount end) as rebate	
                    from tbl_govt_saf_transaction_fine_rebet_details
                    JOIN tbl_govt_saf_transaction ON tbl_govt_saf_transaction.id = tbl_govt_saf_transaction_fine_rebet_details.govt_saf_transaction
                    JOIN gov_prop on gov_prop.id = tbl_govt_saf_transaction.govt_saf_dtl_id 
                    where  tbl_govt_saf_transaction.status in (1, 2) 	
                        and tbl_govt_saf_transaction.tran_date between '$from_date' and '$upto_date'
                    group by gov_prop.id
                ),
                holding as(
                        select prop.id,ward_mstr_id,prop.holding_no,holding_type,is_collectable, 
                            (COALESCE(prive_collectin.total_collection,0)) as prive_collection,
                            (COALESCE(demands.arrear_demand,0))as arrear_demand,
                            (
                                COALESCE(demands.arrear_demand,0) 
                                - COALESCE(prive_collectin.total_collection,0)
                            )as outstanding,
                            (COALESCE(demands.current_demand,0)) as current_demand,	
                            (COALESCE(collection.arrear_collection,0)) as arrear_collection,
                            (COALESCE(collection.current_collection,0)) as current_collection,
                            (
                                (
                                    COALESCE(demands.arrear_demand,0) - COALESCE(prive_collectin.total_collection,0)
                                )-COALESCE(collection.arrear_collection,0)
                            ) as arrear_balance,
                            (
                                COALESCE(demands.current_demand,0)
                                -COALESCE(collection.current_collection,0)
                            ) as current_balance,
                            (
                                (
                                    (
                                        COALESCE(demands.arrear_demand,0) - COALESCE(prive_collectin.total_collection,0)
                                    ) 
                                    + COALESCE(demands.current_demand,0)
                                ) 
                                - ( COALESCE(collection.arrear_collection,0) + COALESCE(collection.current_collection,0))
                            )as total_balance,
                            COALESCE(penalty_rebates.penalty,0)as penalty, COALESCE(penalty_rebates.rebate,0)as rebate
                    from prop 
                    left join demands on demands.id = prop.id
                    left join collection on collection.id = prop.id
                    left join prive_collectin on  prive_collectin.id = prop.id 
                    left join penalty_rebates on penalty_rebates.id = prop.id
                    where 1=1 
                ),
                gov_holding as (
                        select gov_prop.id,ward_mstr_id,gov_prop.holding_no,holding_type,is_collectable,
                            (COALESCE(gov_prive_collectin.total_collection,0)) as prive_collection,
                            (COALESCE(gov_demands.arrear_demand,0))as arrear_demand,
                            (
                                COALESCE(gov_demands.arrear_demand,0) 
                                - COALESCE(gov_prive_collectin.total_collection,0)
                            )as outstanding,
                            (COALESCE(gov_demands.current_demand,0)) as current_demand,	
                            (COALESCE(gov_collection.arrear_collection,0)) as arrear_collection,
                            (COALESCE(gov_collection.current_collection,0)) as current_collection,
                            (
                                (
                                    COALESCE(gov_demands.arrear_demand,0) - COALESCE(gov_prive_collectin.total_collection,0)
                                )-COALESCE(gov_collection.arrear_collection,0)
                            ) as arrear_balance,
                            (
                                COALESCE(gov_demands.current_demand,0)
                                -COALESCE(gov_collection.current_collection,0)
                            ) as current_balance,
                            (
                                (
                                    (
                                        COALESCE(gov_demands.arrear_demand,0) - COALESCE(gov_prive_collectin.total_collection,0)
                                    ) 
                                    + COALESCE(gov_demands.current_demand,0)
                                ) 
                                - ( COALESCE(gov_collection.arrear_collection,0) + COALESCE(gov_collection.current_collection,0))
                            )as total_balance,
                            COALESCE(gov_penalty_rebates.penalty,0)as penalty, COALESCE(gov_penalty_rebates.rebate,0)as rebate
                    from gov_prop 
                    left join view_ward_mstr on view_ward_mstr.id = gov_prop.ward_mstr_id
                    left join gov_demands on gov_demands.id = gov_prop.id
                    left join gov_collection on gov_collection.id = gov_prop.id
                    left join gov_prive_collectin on  gov_prive_collectin.id = gov_prop.id 
                    left join gov_penalty_rebates on gov_penalty_rebates.id = gov_prop.id 
                    where 1=1 
                )

                select count(case when holding_type =('PURE_RESIDENTIAL')  AND is_collectable=true then id end) resident_hh,
                            sum(case when holding_type =('PURE_RESIDENTIAL')  AND is_collectable=true then prive_collection end) prive_collection_resident_hh,
                            sum(case when holding_type =('PURE_RESIDENTIAL')  AND is_collectable=true then arrear_demand end) arrear_demand_resident_hh,
                            sum(case when holding_type =('PURE_RESIDENTIAL')  AND is_collectable=true then outstanding end) outstanding_resident_hh,
                            sum(case when holding_type =('PURE_RESIDENTIAL')  AND is_collectable=true then current_demand end) current_demand_resident_hh,
                            sum(case when holding_type =('PURE_RESIDENTIAL')  AND is_collectable=true then arrear_collection end) arrear_collection_resident_hh,
                            sum(case when holding_type =('PURE_RESIDENTIAL')  AND is_collectable=true then prive_collection end) current_collection_resident_hh,	
                            sum(case when holding_type =('PURE_RESIDENTIAL')  AND is_collectable=true then prive_collection end) arrear_balance_resident_hh,
                            sum(case when holding_type =('PURE_RESIDENTIAL')  AND is_collectable=true then prive_collection end) current_balance_resident_hh,	
                            sum(case when holding_type =('PURE_RESIDENTIAL')  AND is_collectable=true then prive_collection end) total_balance_resident_hh,

                        count(case when holding_type =('PURE_RESIDENTIAL') AND is_collectable=false then id end) resident_hh_not_collectable,
                            sum(case when holding_type =('PURE_RESIDENTIAL') AND is_collectable=false then prive_collection end) prive_collection_resident_hh_not_collectable,
                            sum(case when holding_type =('PURE_RESIDENTIAL') AND is_collectable=false then arrear_demand end) arrear_demand_resident_hh_not_collectable,
                            sum(case when holding_type =('PURE_RESIDENTIAL') AND is_collectable=false then outstanding end) outstanding_resident_hh_not_collectable,
                            sum(case when holding_type =('PURE_RESIDENTIAL') AND is_collectable=false then current_demand end) current_demand_resident_hh_not_collectable,
                            sum(case when holding_type =('PURE_RESIDENTIAL') AND is_collectable=false then arrear_collection end) arrear_collection_resident_hh_not_collectable,
                            sum(case when holding_type =('PURE_RESIDENTIAL') AND is_collectable=false then prive_collection end) current_collection_resident_hh_not_collectable,	
                            sum(case when holding_type =('PURE_RESIDENTIAL') AND is_collectable=false then prive_collection end) arrear_balance_resident_hh_not_collectable,
                            sum(case when holding_type =('PURE_RESIDENTIAL') AND is_collectable=false then prive_collection end) current_balance_resident_hh_not_collectable,	
                            sum(case when holding_type =('PURE_RESIDENTIAL') AND is_collectable=false then prive_collection end) total_balance_resident_hh_not_collectable,
                        
                        count(case when holding_type in('PURE COMMERCIAL','PURE_COMMERCIAL') AND is_collectable=true then id end) non_resident_hh,	
                            sum(case when holding_type in('PURE COMMERCIAL','PURE_COMMERCIAL') AND is_collectable=true then prive_collection end) prive_collection_non_resident_hh,
                            sum(case when holding_type in('PURE COMMERCIAL','PURE_COMMERCIAL') AND is_collectable=true then arrear_demand end) arrear_demand_non_resident_hh,
                            sum(case when holding_type in('PURE COMMERCIAL','PURE_COMMERCIAL') AND is_collectable=true then outstanding end) outstanding_non_resident_hh,
                            sum(case when holding_type in('PURE COMMERCIAL','PURE_COMMERCIAL') AND is_collectable=true then current_demand end) current_demand_non_resident_hh,
                            sum(case when holding_type in('PURE COMMERCIAL','PURE_COMMERCIAL') AND is_collectable=true then arrear_collection end) arrear_collection_non_resident_hh,
                            sum(case when holding_type in('PURE COMMERCIAL','PURE_COMMERCIAL') AND is_collectable=true then prive_collection end) current_collection_non_resident_hh,	
                            sum(case when holding_type in('PURE COMMERCIAL','PURE_COMMERCIAL') AND is_collectable=true then prive_collection end) arrear_balance_non_resident_hh,
                            sum(case when holding_type in('PURE COMMERCIAL','PURE_COMMERCIAL') AND is_collectable=true then prive_collection end) current_balance_non_resident_hh,	
                            sum(case when holding_type in('PURE COMMERCIAL','PURE_COMMERCIAL') AND is_collectable=true then prive_collection end) total_balance_non_resident_hh,

                        count(case when holding_type in('PURE COMMERCIAL','PURE_COMMERCIAL') AND is_collectable=false then id end) non_resident_hh_not_collectable,	
                            sum(case when holding_type in('PURE COMMERCIAL','PURE_COMMERCIAL') AND is_collectable=false then prive_collection end) prive_collection_non_resident_hh_not_collectable,
                            sum(case when holding_type in('PURE COMMERCIAL','PURE_COMMERCIAL') AND is_collectable=false then arrear_demand end) arrear_demand_non_resident_hh_not_collectable,
                            sum(case when holding_type in('PURE COMMERCIAL','PURE_COMMERCIAL') AND is_collectable=false then outstanding end) outstanding_non_resident_hh_not_collectable,
                            sum(case when holding_type in('PURE COMMERCIAL','PURE_COMMERCIAL') AND is_collectable=false then current_demand end) current_demand_non_resident_hh_not_collectable,
                            sum(case when holding_type in('PURE COMMERCIAL','PURE_COMMERCIAL') AND is_collectable=false then arrear_collection end) arrear_collection_non_resident_hh_not_collectable,
                            sum(case when holding_type in('PURE COMMERCIAL','PURE_COMMERCIAL') AND is_collectable=false then prive_collection end) current_collection_non_resident_hh_not_collectable,	
                            sum(case when holding_type in('PURE COMMERCIAL','PURE_COMMERCIAL') AND is_collectable=false then prive_collection end) arrear_balance_non_resident_hh_not_collectable,
                            sum(case when holding_type in('PURE COMMERCIAL','PURE_COMMERCIAL') AND is_collectable=false then prive_collection end) current_balance_non_resident_hh_not_collectable,	
                            sum(case when holding_type in('PURE COMMERCIAL','PURE_COMMERCIAL') AND is_collectable=false then prive_collection end) total_balance_non_resident_hh_not_collectable,

                        count(case when holding_type in('MIX COMMERCIAL','MIX COMMERCIAL') AND is_collectable=true then id end) mix_hh,		
                            sum(case when holding_type in('MIX COMMERCIAL','MIX COMMERCIAL') AND is_collectable=true then prive_collection end) prive_collection_mix_hh,
                            sum(case when holding_type in('MIX COMMERCIAL','MIX COMMERCIAL') AND is_collectable=true then arrear_demand end) arrear_demand_mix_hh,
                            sum(case when holding_type in('MIX COMMERCIAL','MIX COMMERCIAL') AND is_collectable=true then outstanding end) outstanding_mix_hh,
                            sum(case when holding_type in('MIX COMMERCIAL','MIX COMMERCIAL') AND is_collectable=true then current_demand end) current_demand_mix_hh,
                            sum(case when holding_type in('MIX COMMERCIAL','MIX COMMERCIAL') AND is_collectable=true then arrear_collection end) arrear_collection_mix_hh,
                            sum(case when holding_type in('MIX COMMERCIAL','MIX COMMERCIAL') AND is_collectable=true then prive_collection end) current_collection_mix_hh,	
                            sum(case when holding_type in('MIX COMMERCIAL','MIX COMMERCIAL') AND is_collectable=true then prive_collection end) arrear_balance_mix_hh,
                            sum(case when holding_type in('MIX COMMERCIAL','MIX COMMERCIAL') AND is_collectable=true then prive_collection end) current_balance_mix_hh,	
                            sum(case when holding_type in('MIX COMMERCIAL','MIX COMMERCIAL') AND is_collectable=true then prive_collection end) total_balance_mix_hh,

                        count(case when holding_type in('MIX COMMERCIAL','MIX COMMERCIAL') AND is_collectable=false then id end) mix_hh_not_collectable,		
                            sum(case when holding_type in('MIX COMMERCIAL','MIX COMMERCIAL') AND is_collectable=false then prive_collection end) prive_collection_mix_hh_not_collectable,
                            sum(case when holding_type in('MIX COMMERCIAL','MIX COMMERCIAL') AND is_collectable=false then arrear_demand end) arrear_demand_mix_hh_not_collectable,
                            sum(case when holding_type in('MIX COMMERCIAL','MIX COMMERCIAL') AND is_collectable=false then outstanding end) outstanding_mix_hh_not_collectable,
                            sum(case when holding_type in('MIX COMMERCIAL','MIX COMMERCIAL') AND is_collectable=false then current_demand end) current_demand_mix_hh_not_collectable,
                            sum(case when holding_type in('MIX COMMERCIAL','MIX COMMERCIAL') AND is_collectable=false then arrear_collection end) arrear_collection_mix_hh_not_collectable,
                            sum(case when holding_type in('MIX COMMERCIAL','MIX COMMERCIAL') AND is_collectable=false then prive_collection end) current_collection_mix_hh_not_collectable,	
                            sum(case when holding_type in('MIX COMMERCIAL','MIX COMMERCIAL') AND is_collectable=false then prive_collection end) arrear_balance_mix_hh_not_collectable,
                            sum(case when holding_type in('MIX COMMERCIAL','MIX COMMERCIAL') AND is_collectable=false then prive_collection end) current_balance_mix_hh_not_collectable,	
                            sum(case when holding_type in('MIX COMMERCIAL','MIX COMMERCIAL') AND is_collectable=false then prive_collection end) total_balance_mix_hh_not_collectable,

                        count(case when holding_type =('VACANT_LAND') AND is_collectable=true then id end) vacant_hh,	
                            sum(case when holding_type =('VACANT_LAND') AND is_collectable=true then prive_collection end) prive_collection_vacant_hh,
                            sum(case when holding_type =('VACANT_LAND') AND is_collectable=true then arrear_demand end) arrear_demand_vacant_hh,
                            sum(case when holding_type =('VACANT_LAND') AND is_collectable=true then outstanding end) outstanding_vacant_hh,
                            sum(case when holding_type =('VACANT_LAND') AND is_collectable=true then current_demand end) current_demand_vacant_hh,
                            sum(case when holding_type =('VACANT_LAND') AND is_collectable=true then arrear_collection end) arrear_collection_vacant_hh,
                            sum(case when holding_type =('VACANT_LAND') AND is_collectable=true then prive_collection end) current_collection_vacant_hh,	
                            sum(case when holding_type =('VACANT_LAND') AND is_collectable=true then prive_collection end) arrear_balance_vacant_hh,
                            sum(case when holding_type =('VACANT_LAND') AND is_collectable=true then prive_collection end) current_balance_vacant_hh,	
                            sum(case when holding_type =('VACANT_LAND') AND is_collectable=true then prive_collection end) total_balance_vacant_hh,

                        count(case when holding_type =('VACANT_LAND') AND is_collectable=false then id end) vacant_hh_not_collectable,	
                            sum(case when holding_type =('VACANT_LAND') AND is_collectable=false then prive_collection end) prive_collection_vacant_hh_not_collectable,
                            sum(case when holding_type =('VACANT_LAND') AND is_collectable=false then arrear_demand end) arrear_demand_vacant_hh_not_collectable,
                            sum(case when holding_type =('VACANT_LAND') AND is_collectable=false then outstanding end) outstanding_vacant_hh_not_collectable,
                            sum(case when holding_type =('VACANT_LAND') AND is_collectable=false then current_demand end) current_demand_vacant_hh_not_collectable,
                            sum(case when holding_type =('VACANT_LAND') AND is_collectable=false then arrear_collection end) arrear_collection_vacant_hh_not_collectable,
                            sum(case when holding_type =('VACANT_LAND') AND is_collectable=false then prive_collection end) current_collection_vacant_hh_not_collectable,	
                            sum(case when holding_type =('VACANT_LAND') AND is_collectable=false then prive_collection end) arrear_balance_vacant_hh_not_collectable,
                            sum(case when holding_type =('VACANT_LAND') AND is_collectable=false then prive_collection end) current_balance_vacant_hh_not_collectable,	
                            sum(case when holding_type =('VACANT_LAND') AND is_collectable=false then prive_collection end) total_balance_vacant_hh_not_collectable,

                        count(case when holding_type =('TRUST') AND is_collectable=true then id end) trust_hh,	
                            sum(case when holding_type =('TRUST') AND is_collectable=true then prive_collection end) prive_collection_trust_hh,
                            sum(case when holding_type =('TRUST') AND is_collectable=true then arrear_demand end) arrear_demand_trust_hh,
                            sum(case when holding_type =('TRUST') AND is_collectable=true then outstanding end) outstanding_trust_hh,
                            sum(case when holding_type =('TRUST') AND is_collectable=true then current_demand end) current_demand_trust_hh,
                            sum(case when holding_type =('TRUST') AND is_collectable=true then arrear_collection end) arrear_collection_trust_hh,
                            sum(case when holding_type =('TRUST') AND is_collectable=true then prive_collection end) current_collection_trust_hh,	
                            sum(case when holding_type =('TRUST') AND is_collectable=true then prive_collection end) arrear_balance_trust_hh,
                            sum(case when holding_type =('TRUST') AND is_collectable=true then prive_collection end) current_balance_trust_hh,	
                            sum(case when holding_type =('TRUST') AND is_collectable=true then prive_collection end) total_balance_trust_hh,

                        count(case when holding_type =('TRUST') AND is_collectable=false then id end) trust_hh_not_collectable,	
                            sum(case when holding_type =('TRUST') AND is_collectable=false then prive_collection end) prive_collection_trust_hh_not_collectable,
                            sum(case when holding_type =('TRUST') AND is_collectable=false then arrear_demand end) arrear_demand_trust_hh_not_collectable,
                            sum(case when holding_type =('TRUST') AND is_collectable=false then outstanding end) outstanding_trust_hh_not_collectable,
                            sum(case when holding_type =('TRUST') AND is_collectable=false then current_demand end) current_demand_trust_hh_not_collectable,
                            sum(case when holding_type =('TRUST') AND is_collectable=false then arrear_collection end) arrear_collection_trust_hh_not_collectable,
                            sum(case when holding_type =('TRUST') AND is_collectable=false then prive_collection end) current_collection_trust_hh_not_collectable,	
                            sum(case when holding_type =('TRUST') AND is_collectable=false then prive_collection end) arrear_balance_trust_hh_not_collectable,
                            sum(case when holding_type =('TRUST') AND is_collectable=false then prive_collection end) current_balance_trust_hh_not_collectable,	
                            sum(case when holding_type =('TRUST') AND is_collectable=false then prive_collection end) total_balance_trust_hh_not_collectable,

                        count(case when holding_type =('CENTRAL_GOV') AND is_collectable=true then id end) central_gov_hh,
                            sum(case when holding_type =('CENTRAL_GOV') AND is_collectable=true then prive_collection end) prive_collection_central_gov_hh,
                            sum(case when holding_type =('CENTRAL_GOV') AND is_collectable=true then arrear_demand end) arrear_demand_central_gov_hh,
                            sum(case when holding_type =('CENTRAL_GOV') AND is_collectable=true then outstanding end) outstanding_central_gov_hh,
                            sum(case when holding_type =('CENTRAL_GOV') AND is_collectable=true then current_demand end) current_demand_central_gov_hh,
                            sum(case when holding_type =('CENTRAL_GOV') AND is_collectable=true then arrear_collection end) arrear_collection_central_gov_hh,
                            sum(case when holding_type =('CENTRAL_GOV') AND is_collectable=true then prive_collection end) current_collection_central_gov_hh,	
                            sum(case when holding_type =('CENTRAL_GOV') AND is_collectable=true then prive_collection end) arrear_balance_central_gov_hh,
                            sum(case when holding_type =('CENTRAL_GOV') AND is_collectable=true then prive_collection end) current_balance_central_gov_hh,	
                            sum(case when holding_type =('CENTRAL_GOV') AND is_collectable=true then prive_collection end) total_balance_central_gov_hh,

                        count(case when holding_type =('CENTRAL_GOV') AND is_collectable=false then id end) central_gov_hh_not_collectable,
                            sum(case when holding_type =('CENTRAL_GOV') AND is_collectable=false then prive_collection end) prive_collection_central_gov_hh_not_collectable,
                            sum(case when holding_type =('CENTRAL_GOV') AND is_collectable=false then arrear_demand end) arrear_demand_central_gov_hh_not_collectable,
                            sum(case when holding_type =('CENTRAL_GOV') AND is_collectable=false then outstanding end) outstanding_central_gov_hh_not_collectable,
                            sum(case when holding_type =('CENTRAL_GOV') AND is_collectable=false then current_demand end) current_demand_central_gov_hh_not_collectable,
                            sum(case when holding_type =('CENTRAL_GOV') AND is_collectable=false then arrear_collection end) arrear_collection_central_gov_hh_not_collectable,
                            sum(case when holding_type =('CENTRAL_GOV') AND is_collectable=false then prive_collection end) current_collection_central_gov_hh_not_collectable,	
                            sum(case when holding_type =('CENTRAL_GOV') AND is_collectable=false then prive_collection end) arrear_balance_central_gov_hh_not_collectable,
                            sum(case when holding_type =('CENTRAL_GOV') AND is_collectable=false then prive_collection end) current_balance_central_gov_hh_not_collectable,	
                            sum(case when holding_type =('CENTRAL_GOV') AND is_collectable=false then prive_collection end) total_balance_central_gov_hh_not_collectable,

                        count(case when holding_type =('STATE_GOV') AND is_collectable=true then id end) state_gov_hh,
                            sum(case when holding_type =('STATE_GOV') AND is_collectable=true then prive_collection end) prive_collection_state_gov_hh,
                            sum(case when holding_type =('STATE_GOV') AND is_collectable=true then arrear_demand end) arrear_demand_state_gov_hh,
                            sum(case when holding_type =('STATE_GOV') AND is_collectable=true then outstanding end) outstanding_state_gov_hh,
                            sum(case when holding_type =('STATE_GOV') AND is_collectable=true then current_demand end) current_demand_state_gov_hh,
                            sum(case when holding_type =('STATE_GOV') AND is_collectable=true then arrear_collection end) arrear_collection_state_gov_hh,
                            sum(case when holding_type =('STATE_GOV') AND is_collectable=true then prive_collection end) current_collection_state_gov_hh,	
                            sum(case when holding_type =('STATE_GOV') AND is_collectable=true then prive_collection end) arrear_balance_state_gov_hh,
                            sum(case when holding_type =('STATE_GOV') AND is_collectable=true then prive_collection end) current_balance_state_gov_hh,	
                            sum(case when holding_type =('STATE_GOV') AND is_collectable=true then prive_collection end) total_balance_state_gov_hh,	

                        count(case when holding_type =('STATE_GOV') AND is_collectable=false then id end) state_gov_hh_not_collectable,
                            sum(case when holding_type =('STATE_GOV') AND is_collectable=false then prive_collection end) prive_collection_state_gov_hh_not_collectable,
                            sum(case when holding_type =('STATE_GOV') AND is_collectable=false then arrear_demand end) arrear_demand_state_gov_hh_not_collectable,
                            sum(case when holding_type =('STATE_GOV') AND is_collectable=false then outstanding end) outstanding_state_gov_hh_not_collectable,
                            sum(case when holding_type =('STATE_GOV') AND is_collectable=false then current_demand end) current_demand_state_gov_hh_not_collectable,
                            sum(case when holding_type =('STATE_GOV') AND is_collectable=false then arrear_collection end) arrear_collection_state_gov_hh_not_collectable,
                            sum(case when holding_type =('STATE_GOV') AND is_collectable=false then prive_collection end) current_collection_state_gov_hh_not_collectable,	
                            sum(case when holding_type =('STATE_GOV') AND is_collectable=false then prive_collection end) arrear_balance_state_gov_hh_not_collectable,
                            sum(case when holding_type =('STATE_GOV') AND is_collectable=false then prive_collection end) current_balance_state_gov_hh_not_collectable,	
                            sum(case when holding_type =('STATE_GOV') AND is_collectable=false then prive_collection end) total_balance_state_gov_hh_not_collectable
                from(
                    (
                        select *
                        from holding
                    )
                    union all(
                        select *
                        from gov_holding
                    )	
                )pr
            
            ";
            $data["result"] = $this->db->query($sql)->getFirstRow("array");
            // print_var($data["result"]);
        }
        return view("property/reports/PropertyTaxAndVlTax",$data);
    }

    public function holdingWiseDcb($ajax=false){
        $data = $this->request->getVar();
        $ulb_mstr_id = 1;
        $data["wardList"] = $this->model_ward_mstr->getWardList(['ulb_mstr_id'=>$ulb_mstr_id]);
        $data["fyList"] = fy_year_list();
        if($this->request->getMethod()=="post" || ($ajax && $this->request->getMethod()=="get")){
            $start = sanitizeString($this->request->getVar('start'));

            $rowperpage = sanitizeString($this->request->getVar('length')); // Rows display per page

            $columnIndex = sanitizeString($this->request->getVar('order')[0]['column']); // Column index
            $columnName = sanitizeString($this->request->getVar('columns')[$columnIndex]['data']); // Column name
            if ($columnName=="ward_no" )
                $columnName='pr.ward_mstr_id';
            else if ($columnName=="holding_no")
                $columnName = 'pr.holding_no';
            else 
                $columnName = 'pr.id'; 
            $columnSortOrder = sanitizeString($this->request->getVar('order')[0]['dir']); // asc or desc
            $searchValue = sanitizeString($this->request->getVar('search')['value']); // Search value
            $orderBY = " ORDER BY " . $columnName . " " . $columnSortOrder;
            $limit = " LIMIT " . $rowperpage . " OFFSET " . $start;
            $ward_mstr_id = $this->request->getVar("ward_mstr_id");
            $fyear = $this->request->getVar("fyear");
            list($fromYear,$uptoYear)=explode("-",$fyear);
            $from_date = $fromYear."-04-01";
            $upto_date = $uptoYear."-03-31";
            $whereQueryWithSearch = "";
            if ($searchValue != '') {
                $whereQueryWithSearch .= " AND (holding_no ILIKE '%".$searchValue."%'
                                    OR owner_name ILIKE '%".$searchValue."%'
                                    )";
            }
            $where="";
            if($ward_mstr_id){
                $where .= " AND ward_mstr_id = ".$ward_mstr_id;
            }

            $with = "with prop as (
                    select tbl_prop_dtl.id,ward_mstr_id,new_ward_mstr_id ,null as office_name, null as application_no,
                        case when trim(new_holding_no) ='' then holding_no else new_holding_no end as holding_no,
                        new_holding_no,holding_type,saf_dtl_id,govt_saf_dtl_id
                    from tbl_prop_dtl
                    where status = 1 $where
                ),
                demands as (
                    select prop.id,sum(COALESCE(tbl_prop_demand.amount, 0) - COALESCE(tbl_prop_demand.adjust_amt, 0)) as total_demand,
                        sum(CASE WHEN fyear< '$fyear' then COALESCE(tbl_prop_demand.amount, 0) - COALESCE(tbl_prop_demand.adjust_amt, 0) else 0 end)as arrear_demand,
                        sum(CASE WHEN fyear= '$fyear' then COALESCE(tbl_prop_demand.amount, 0) - COALESCE(tbl_prop_demand.adjust_amt, 0) else 0 end)as current_demand
                    from tbl_prop_demand
                    join prop on prop.id = tbl_prop_demand.prop_dtl_id
                    where tbl_prop_demand.status = 1 and tbl_prop_demand.fyear <= '$fyear'
                    group by prop.id
                ),
                collection as (
                    select prop.id,
                        sum(tbl_collection.amount) as total_collection,
                        sum(CASE WHEN tbl_prop_demand.fyear< '$fyear' then tbl_collection.amount else 0 end)as arrear_collection,
                        sum(CASE WHEN tbl_prop_demand.fyear= '$fyear' then tbl_collection.amount else 0 end)as current_collection
                    from tbl_collection
                    join tbl_transaction on tbl_transaction.id = tbl_collection.transaction_id
                    join tbl_prop_demand on tbl_prop_demand.id = tbl_collection.prop_demand_id
                    join prop on prop.id = tbl_prop_demand.prop_dtl_id
                    where tbl_transaction.status in(1,2) 
                        and tbl_transaction.tran_date between '$from_date' and '$upto_date'
                    group by prop.id
                ),
                prive_collectin as (
                    SELECT 
                        prop.id,
                        SUM(COALESCE(tbl_collection.amount, 0)) AS total_collection
                    FROM prop
                    INNER JOIN tbl_collection on tbl_collection.prop_dtl_id=prop.id AND tbl_collection.created_on::date < '$from_date'
                    WHERE tbl_collection.status=1 
                    GROUP BY prop.id
                ),
                penalty_rebates as (
                    select prop.id ,
                        sum(case when tbl_transaction_fine_rebet_details.value_add_minus='Add' then tbl_transaction_fine_rebet_details.amount end) as penalty,
                        sum(case when tbl_transaction_fine_rebet_details.value_add_minus!='Add' then tbl_transaction_fine_rebet_details.amount end) as rebate	
                    from tbl_transaction_fine_rebet_details
                    JOIN tbl_transaction ON tbl_transaction.id = tbl_transaction_fine_rebet_details.transaction_id
                    JOIN prop on prop.id = tbl_transaction.prop_dtl_id 
                    where tbl_transaction.tran_type='Property'  
                        AND tbl_transaction.status in (1, 2) 	
                        and tbl_transaction.tran_date between '$from_date' and '$upto_date'
                    group by prop.id
                ),
                owners as (
                    select prop.id, string_agg(owner_name,',') as owner_name,
                        string_agg(mobile_no::text,',') as mobile_no
                    from tbl_prop_owner_detail
                    join prop on prop.id = tbl_prop_owner_detail.prop_dtl_id
                    where status =1
                    group by prop.id
                ),
                gov_prop as (
                    select tbl_govt_saf_dtl.id,tbl_govt_saf_dtl.ward_mstr_id,tbl_govt_saf_dtl.new_ward_mstr_id ,office_name as owner_name,application_no,
                        case when trim(new_holding_no) ='' then tbl_prop_dtl.holding_no else tbl_prop_dtl.new_holding_no end as holding_no,
                        tbl_prop_dtl.new_holding_no,tbl_prop_dtl.holding_type,tbl_prop_dtl.saf_dtl_id,tbl_prop_dtl.govt_saf_dtl_id
                    from tbl_govt_saf_dtl
                    left join tbl_prop_dtl on tbl_prop_dtl.govt_saf_dtl_id =tbl_govt_saf_dtl.id 
                ),
                gov_demands as (
                    select gov_prop.id,sum(COALESCE(tbl_govt_saf_demand_dtl.amount, 0) - COALESCE(tbl_govt_saf_demand_dtl.adjust_amount, 0)) as total_demand,
                        sum(CASE WHEN fyear< '$fyear' then COALESCE(tbl_govt_saf_demand_dtl.amount, 0) - COALESCE(tbl_govt_saf_demand_dtl.adjust_amount, 0) else 0 end)as arrear_demand,
                        sum(CASE WHEN fyear= '$fyear' then COALESCE(tbl_govt_saf_demand_dtl.amount, 0) - COALESCE(tbl_govt_saf_demand_dtl.adjust_amount, 0) else 0 end)as current_demand
                    from tbl_govt_saf_demand_dtl
                    join gov_prop on gov_prop.id = tbl_govt_saf_demand_dtl.govt_saf_dtl_id
                    where tbl_govt_saf_demand_dtl.status = 1 and tbl_govt_saf_demand_dtl.fyear <= '$fyear'
                    group by gov_prop.id
                ),
                gov_collection as (
                    select gov_prop.id,
                        sum(tbl_govt_saf_collection_dtl.amount) as total_collection,
                        sum(CASE WHEN tbl_govt_saf_demand_dtl.fyear< '$fyear' then tbl_govt_saf_collection_dtl.amount else 0 end)as arrear_collection,
                        sum(CASE WHEN tbl_govt_saf_demand_dtl.fyear= '$fyear' then tbl_govt_saf_collection_dtl.amount else 0 end)as current_collection
                    from tbl_govt_saf_collection_dtl
                    join tbl_govt_saf_transaction on tbl_govt_saf_transaction.id = tbl_govt_saf_collection_dtl.govt_saf_transaction_id
                    join tbl_govt_saf_demand_dtl on tbl_govt_saf_demand_dtl.id = tbl_govt_saf_collection_dtl.govt_saf_demand_dtl_id
                    join gov_prop on gov_prop.id = tbl_govt_saf_demand_dtl.govt_saf_dtl_id
                    where tbl_govt_saf_transaction.status in(1,2) 
                        and tbl_govt_saf_transaction.tran_date between '$from_date' and '$upto_date'
                    group by gov_prop.id
                ),
                gov_prive_collectin as (
                    SELECT 
                        gov_prop.id,
                        SUM(COALESCE(tbl_govt_saf_collection_dtl.amount, 0)) AS total_collection
                    FROM gov_prop
                    INNER JOIN tbl_govt_saf_collection_dtl on tbl_govt_saf_collection_dtl.govt_saf_dtl_id=gov_prop.id 
                        AND tbl_govt_saf_collection_dtl.created_on::date < '$from_date'
                    WHERE tbl_govt_saf_collection_dtl.status=1 
                    GROUP BY gov_prop.id
                ),
                gov_penalty_rebates as (
                    select gov_prop.id ,
                        sum(case when tbl_govt_saf_transaction_fine_rebet_details.value_add_minus='Add' then tbl_govt_saf_transaction_fine_rebet_details.amount end) as penalty,
                        sum(case when tbl_govt_saf_transaction_fine_rebet_details.value_add_minus!='Add' then tbl_govt_saf_transaction_fine_rebet_details.amount end) as rebate	
                    from tbl_govt_saf_transaction_fine_rebet_details
                    JOIN tbl_govt_saf_transaction ON tbl_govt_saf_transaction.id = tbl_govt_saf_transaction_fine_rebet_details.govt_saf_transaction
                    JOIN gov_prop on gov_prop.id = tbl_govt_saf_transaction.govt_saf_dtl_id 
                    where  tbl_govt_saf_transaction.status in (1, 2) 	
                        and tbl_govt_saf_transaction.tran_date between '$from_date' and '$upto_date'
                    group by gov_prop.id
                )";
                $with2 =",
                holding as(
                        select prop.id,ward_mstr_id,prop.holding_no,holding_type, owners.owner_name,owners.mobile_no,
                            view_ward_mstr.ward_no,
                            (
                                case when prop.govt_saf_dtl_id is not null or prop.govt_saf_dtl_id!=0 then 
                                        (select application_no from tbl_govt_saf_dtl where id = prop.govt_saf_dtl_id)
                                    else (select saf_no from tbl_saf_dtl where id = prop.saf_dtl_id)
                                end
                            ) as saf_no,
                            (COALESCE(prive_collectin.total_collection,0)) as prive_collection,
                            (COALESCE(demands.arrear_demand,0))as arrear_demand,
                            (
                                COALESCE(demands.arrear_demand,0) 
                                - COALESCE(prive_collectin.total_collection,0)
                            )as outstanding,
                            (COALESCE(demands.current_demand,0)) as current_demand,	
                            (COALESCE(collection.arrear_collection,0)) as arrear_collection,
                            (COALESCE(collection.current_collection,0)) as current_collection,
                            (
                                (
                                    COALESCE(demands.arrear_demand,0) - COALESCE(prive_collectin.total_collection,0)
                                )-COALESCE(collection.arrear_collection,0)
                            ) as arrear_balance,
                            (
                                COALESCE(demands.current_demand,0)
                                -COALESCE(collection.current_collection,0)
                            ) as current_balance,
                            (
                                (
                                    (
                                        COALESCE(demands.arrear_demand,0) - COALESCE(prive_collectin.total_collection,0)
                                    ) 
                                    + COALESCE(demands.current_demand,0)
                                ) 
                                - ( COALESCE(collection.arrear_collection,0) + COALESCE(collection.current_collection,0))
                            )as total_balance,
                            COALESCE(penalty_rebates.penalty,0)as penalty, COALESCE(penalty_rebates.rebate,0)as rebate
                    from prop 
                    left join view_ward_mstr on view_ward_mstr.id = prop.ward_mstr_id
                    left join demands on demands.id = prop.id
                    left join collection on collection.id = prop.id
                    left join prive_collectin on  prive_collectin.id = prop.id 
                    left join penalty_rebates on penalty_rebates.id = prop.id  
                    left join owners on owners.id = prop.id 
                    where 1=1          
                    $whereQueryWithSearch  
                    ORDER BY prop.id asc
                    $limit
                ),
                gov_holding as (
                        select gov_prop.id,ward_mstr_id,gov_prop.holding_no,holding_type,  owner_name, null mobile_no,
                            view_ward_mstr.ward_no,
                            application_no as saf_no,
                            (COALESCE(gov_prive_collectin.total_collection,0)) as prive_collection,
                            (COALESCE(gov_demands.arrear_demand,0))as arrear_demand,
                            (
                                COALESCE(gov_demands.arrear_demand,0) 
                                - COALESCE(gov_prive_collectin.total_collection,0)
                            )as outstanding,
                            (COALESCE(gov_demands.current_demand,0)) as current_demand,	
                            (COALESCE(gov_collection.arrear_collection,0)) as arrear_collection,
                            (COALESCE(gov_collection.current_collection,0)) as current_collection,
                            (
                                (
                                    COALESCE(gov_demands.arrear_demand,0) - COALESCE(gov_prive_collectin.total_collection,0)
                                )-COALESCE(gov_collection.arrear_collection,0)
                            ) as arrear_balance,
                            (
                                COALESCE(gov_demands.current_demand,0)
                                -COALESCE(gov_collection.current_collection,0)
                            ) as current_balance,
                            (
                                (
                                    (
                                        COALESCE(gov_demands.arrear_demand,0) - COALESCE(gov_prive_collectin.total_collection,0)
                                    ) 
                                    + COALESCE(gov_demands.current_demand,0)
                                ) 
                                - ( COALESCE(gov_collection.arrear_collection,0) + COALESCE(gov_collection.current_collection,0))
                            )as total_balance,
                            COALESCE(gov_penalty_rebates.penalty,0)as penalty, COALESCE(gov_penalty_rebates.rebate,0)as rebate
                    from gov_prop 
                    left join view_ward_mstr on view_ward_mstr.id = gov_prop.ward_mstr_id
                    left join gov_demands on gov_demands.id = gov_prop.id
                    left join gov_collection on gov_collection.id = gov_prop.id
                    left join gov_prive_collectin on  gov_prive_collectin.id = gov_prop.id 
                    left join gov_penalty_rebates on gov_penalty_rebates.id = gov_prop.id 
                    where 1=1        
                    $whereQueryWithSearch    
                    ORDER BY gov_prop.id asc
                    $limit
                )
                
            ";
            $select="select *,
		        ROW_NUMBER () OVER (ORDER BY id) AS s_no
            ";
            $from2="from (
                        (
                            select *
                            from holding
                        )
                        union all(
                            select *
                            from gov_holding
                        )	
                    )pr
                    where 1=1
                    $where            
            ";
            $from = "from (
                        (
                            select *
                            from prop
                        )
                        union all(
                            select *
                            from gov_prop
                        )	
                    )pr
                    where 1=1
                    $where 
                    ";

            $totalRecords = $this->model_datatable->getTotalRecords($from,false,$with);
            if ($totalRecords>0) 
            {
                
                ## Total number of records with filtering
                $totalRecordwithFilter = $this->model_datatable->getTotalRecordwithFilter($from.$whereQueryWithSearch,false,$with);
                
                ## Fetch records                
               $fetchSql = $with.$with2.$select.$from2.$whereQueryWithSearch.$orderBY;
			   if(!$ajax){
				$fetchSql .= $limit;
			   }
                
                $result = $this->model_datatable->getRecords($fetchSql,false);               
                
                $records = [];
                if ($result) 
                {
                    foreach ($result AS $key=>$tran_dtl) 
                    {
                        $records[] = [
                            's_no'=>$tran_dtl['s_no'],
                            'holding_no'=>$tran_dtl['holding_no'],
                            'saf_no'=>$tran_dtl['saf_no'],
                            'owner_name'=>$tran_dtl['owner_name'],
							"ward_no"=>$tran_dtl["ward_no"],
                            'holding_type'=>$tran_dtl['holding_type'],
                            'status'=>"",
							'outstanding'=>$tran_dtl['outstanding'],
							'arrear_demand_generated'=>0,
                            'arrear_demand_deactiveted'=>0,
                            'current_demand'=>$tran_dtl['current_demand'],
                            'total_demand'=>$tran_dtl['current_demand'] + $tran_dtl['outstanding'],

                            'arrear_collection'=>$tran_dtl['arrear_collection'],
                            'current_collection'=>$tran_dtl['current_collection'],
                            'arrear_rebate'=>0,
                            'current_rebate'=>$tran_dtl['rebate'],
                            'arrear_adjustment'=>0,
                            'current_adjustment'=>0,
                            'arrear_balance'=>$tran_dtl['arrear_balance'],
                            'current_balance'=>$tran_dtl['current_balance'],
                            'total_balance'=>$tran_dtl['total_balance'],
                            'penalty'=>$tran_dtl['penalty'],
                            'intrest'=>0,
                            
                        ];
                    }
                }
            } 
            else 
            {
                $totalRecordwithFilter = 0;
                $records = [];
            }
			if($ajax){
				phpOfficeLoad();
				$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
				$activeSheet = $spreadsheet->getActiveSheet();
								$activeSheet->setCellValue('A1', 'Sl No.');
								$activeSheet->setCellValue('B1', 'Holding No');
								$activeSheet->setCellValue('C1', 'SAF No');
								$activeSheet->setCellValue('D1', 'Owner Name');
								$activeSheet->setCellValue('E1', 'Ward No');
								$activeSheet->setCellValue('F1', 'Type Of HH');
								$activeSheet->setCellValue('G1', 'Status of Demand Collectable/Not Collectable/Litigation');
								$activeSheet->setCellValue('H1', 'Opening Arrear Demand');
								$activeSheet->setCellValue('I1', 'Arrear Demand generated During the Year');
								$activeSheet->setCellValue('J1', 'Arrear Demand deactivated During the Year');
								$activeSheet->setCellValue('K1', 'Current Demand');

                                $activeSheet->setCellValue('L1', 'Total Demand');
                                $activeSheet->setCellValue('M1', 'Collection From Arrear Demand');
                                $activeSheet->setCellValue('N1', 'Collection From Current Demand');
                                $activeSheet->setCellValue('O1', 'Rebate From Arrear Demand');
                                $activeSheet->setCellValue('P1', 'Rebate From Current Demand');
                                $activeSheet->setCellValue('Q1', 'Adjustment From Arrear Demand');
                                $activeSheet->setCellValue('R1', 'Adjustment From Current Demand');
                                $activeSheet->setCellValue('S1', 'Balance Arrear Due');
                                $activeSheet->setCellValue('T1', 'Balance Current Due');
                                $activeSheet->setCellValue('U1', 'Total Due');
                                $activeSheet->setCellValue('V1', 'Collection Penalty Amount');
                                $activeSheet->setCellValue('W1', 'Collection Intrest Amount');


								$activeSheet->fromArray($records, NULL, 'A3');

				$filename = "Grievance".date('Ymd-hisa').".xlsx";
				header('Content-Type: application/vnd.ms-excel');
				header('Content-Disposition: attachment;filename="'.$filename.'"');
				header('Cache-Control: max-age=0');
				$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
				$writer->save('php://output');
				
			}
			else{
				$response = array(
					"draw" => 0,                
					"recordsTotal" => $totalRecords,                 
					"recordsFiltered" => $totalRecordwithFilter,
					"data" => $records,                
	
				);
				return json_encode($response);
			}
        }
        return view("property/reports/holdind_wise_dcb",$data);

    }

    public function wardWiseCommercialHoldingList()
    {

        $data = [];
        $data = $this->request->getVar();

        $ulb_mstr_id = 1;

        $data['wardList'] =  $wardList = $this->model_ward_mstr->getWardList(['ulb_mstr_id' => $ulb_mstr_id]);
        $ward_mstr_id = $this->request->getVar("ward_mstr_id");
        try {

            $sql = "SELECT 
                    view_ward_mstr.ward_no, 
                    tbl_prop_dtl.new_holding_no, 
                    tbl_prop_dtl.khata_no, 
                    tbl_prop_dtl.plot_no, 
                    tbl_prop_dtl.prop_address, 
                    tbl_prop_owner_detail.owner_name, 
                    tbl_prop_owner_detail.mobile_no
                FROM 
                    tbl_prop_dtl
                INNER JOIN 
                    view_ward_mstr ON view_ward_mstr.id = tbl_prop_dtl.ward_mstr_id
                INNER JOIN 
                    tbl_prop_owner_detail ON tbl_prop_dtl.id = tbl_prop_owner_detail.prop_dtl_id
                WHERE 
                    tbl_prop_dtl.holding_type IN ('PURE_COMMERCIAL', 'MIX_COMMERCIAL')
                    AND tbl_prop_dtl.status = 1
                " . ($ward_mstr_id ? "AND tbl_prop_dtl.ward_mstr_id = $ward_mstr_id" : "") . "
                
            ";
            $result = $this->model_datatable->getDatatable($sql);
            $data['results'] = $result['result'];
            $data['offset'] = $result['offset'];
            $data['pager'] = $result['count'];

            // $data['results'] = $result = $this->db->query($sql)->getResultArray();

        } catch (\Exception $e) {
            log_message('error', 'Error fetching data: ' . $e->getMessage());
        }
        return view("property/reports/commercial_holding_list", $data);
    }


    public function commercial_holding_export($ward_mstr_id = null)
    {
        try {
            $data = [];

            if ($ward_mstr_id != '') {
                $sql = "SELECT 
                view_ward_mstr.ward_no, 
                tbl_prop_dtl.new_holding_no, 
                tbl_prop_dtl.khata_no, 
                tbl_prop_dtl.plot_no, 
                tbl_prop_dtl.prop_address, 
                tbl_prop_owner_detail.owner_name, 
                tbl_prop_owner_detail.mobile_no
            FROM 
                tbl_prop_dtl
            INNER JOIN 
                view_ward_mstr ON view_ward_mstr.id = tbl_prop_dtl.ward_mstr_id
            INNER JOIN 
                tbl_prop_owner_detail ON tbl_prop_dtl.id = tbl_prop_owner_detail.prop_dtl_id
            WHERE 
                tbl_prop_dtl.ward_mstr_id = $ward_mstr_id
                AND tbl_prop_dtl.holding_type IN ('PURE_COMMERCIAL', 'MIX_COMMERCIAL')
                AND tbl_prop_dtl.status = 1";
            } else {
                $sql = "SELECT 
                            view_ward_mstr.ward_no, 
                            tbl_prop_dtl.new_holding_no, 
                            tbl_prop_dtl.khata_no, 
                            tbl_prop_dtl.plot_no, 
                            tbl_prop_dtl.prop_address, 
                            tbl_prop_owner_detail.owner_name, 
                            tbl_prop_owner_detail.mobile_no
                        FROM 
                            tbl_prop_dtl
                        INNER JOIN 
                            view_ward_mstr ON view_ward_mstr.id = tbl_prop_dtl.ward_mstr_id
                        INNER JOIN 
                            tbl_prop_owner_detail ON tbl_prop_dtl.id = tbl_prop_owner_detail.prop_dtl_id
                        WHERE 
                            tbl_prop_dtl.holding_type IN ('PURE_COMMERCIAL', 'MIX_COMMERCIAL')
                            AND tbl_prop_dtl.status = 1";
            }

            $records = $this->model_datatable->getRecords($sql);


            error_log("SQL Query: " . $sql);
            error_log("Parameters: " . json_encode($params));



            $records = $this->db->query($sql)->getResultArray();

            error_log("Records: " . json_encode($records));
            if (empty($records)) {
                error_log("No records found");
            }

            $spreadsheet = new Spreadsheet();

            $activeSheet = $spreadsheet->getActiveSheet();
            $activeSheet->setCellValue('A1', 'Ward No');
            $activeSheet->setCellValue('B1', '15 Digits Holding No');
            $activeSheet->setCellValue('C1', 'Owner Name');
            $activeSheet->setCellValue('D1', 'Mobile No');
            $activeSheet->setCellValue('E1', 'Khata No');
            $activeSheet->setCellValue('F1', 'Plot No');
            $activeSheet->setCellValue('G1', 'Address');
            $activeSheet->fromArray($records, NULL, 'A2');
            $spreadsheet->getActiveSheet()->getStyle('B')->getNumberFormat()
                ->setFormatCode(NumberFormat::FORMAT_TEXT);
            $filename = "commercial_holding_list.xlsx";
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            $writer = new Xlsx($spreadsheet);
            ob_end_clean();
            $writer->save('php://output');
        } catch (\Exception $e) {
            Log::error("error", 'Error fetching data: ' . $e->getMessage());
        }
    }

    public function new_commercial_assesment()
    {

        $data = [];
        $ulb_mstr_id = 1;
        $data  = $this->request->getVar();

        $wardList = $this->model_ward_mstr->getWardListWithSession(['ulb_mstr_id' => $ulb_mstr_id]);
        $empDtlList = $this->model_view_emp_details->getEmpListByAlTaxCollector(['ulb_mstr_id' => $ulb_mstr_id]);
        $data['wardList'] = $wardList;
        $data['empDtlList'] = $empDtlList;

        $ward_mstr_id = $this->request->getVar("ward_mstr_id");
        $from_date = $this->request->getVar("from_date");
        $upto_date = $this->request->getVar("upto_date");


        if ($this->request->getVar()) {
            try {
                $sql = "SELECT 
                            view_ward_mstr.ward_no,   
                            tbl_saf_dtl.saf_no,
                            tbl_saf_dtl.khata_no,
                            tbl_saf_dtl.plot_no,
                            tbl_saf_dtl.prop_address,
                            view_ward_mstr.ward_no,
                            tbl_saf_owner_detail.owner_name,
                            -- tbl_saf_owner_detail.guardian_name,
                            tbl_saf_owner_detail.mobile_no,  
                            DATE(tbl_saf_dtl.created_on) AS saf_apply_date 
                        FROM tbl_saf_dtl
                        LEFT JOIN  view_ward_mstr ON tbl_saf_dtl.ward_mstr_id = view_ward_mstr.id
                        LEFT JOIN tbl_saf_owner_detail ON tbl_saf_dtl.id = tbl_saf_owner_detail.saf_dtl_id
                        WHERE tbl_saf_dtl.status = 1
                            AND tbl_saf_dtl.created_on::DATE BETWEEN '$from_date' AND '$upto_date'
                            " . ($ward_mstr_id ? "AND tbl_saf_dtl.ward_mstr_id = $ward_mstr_id" : "") . "
                ";
                if (isset($data["export"]) && $data["export"]) {
                    $result = $this->model_datatable->getRecords($sql, false);
                    phpOfficeLoad();
                    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
                    $activeSheet = $spreadsheet->getActiveSheet();
                    $activeSheet->setCellValue('A1', 'Ward No.');
                    $activeSheet->setCellValue('B1', 'SAF No');
                    $activeSheet->setCellValue('C1', 'Owner Name');

                    $activeSheet->setCellValue('D1', 'Mobile No');
                    $activeSheet->setCellValue('E1', 'Khata No');
                    $activeSheet->setCellValue('F1', 'Plot No');
                    $activeSheet->setCellValue('G1', 'Address');
                    $activeSheet->setCellValue('H1', 'SAF Apply Date');

                    $activeSheet->fromArray($result, NULL, 'A2');

                    $filename = "New_commercial_assesed_saf" . date('Ymd-hisa') . ".xlsx";
                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment;filename="' . $filename . '"');
                    header('Cache-Control: max-age=0');
                    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                    $writer->save('php://output');
                    return;
                }

                $result = $this->model_datatable->getDatatable($sql);
                $data['results'] = $result['result'];

                $data['offset'] = $result['offset'];
                $data['pager'] = $result['count'];

            } catch (\Exception $e) {
                log_message('error', 'Error fetching data: ' . $e->getMessage());
            }
        }


        return view("property/reports/commercial_assesment_list", $data);
    }

    public function gbSAFDCB($args=[]){
        $data=[];
        if($this->request->isAJAX() || ($args["cmd"]??false)){
            $request = $this->request->getVar();
            $fyear = $request["fyear"]??getFY();
            $ward_id = $request['ward_mstr_id']??($args["ward_mstr_id"]??"");
            list($fromYear,$uptoYear)=explode("-",$fyear);
            $fromDate = $fromYear."-04-01";
            $uptoDate = $uptoYear."-03-31";
            $with="with demand as (
                    select tbl_govt_saf_demand_dtl.govt_saf_dtl_id,
                        SUM( CASE WHEN tbl_govt_saf_demand_dtl.fyear< '$fyear' then COALESCE(tbl_govt_saf_demand_dtl.amount, 0) - COALESCE(tbl_govt_saf_demand_dtl.adjust_amount, 0) else 0 end) as arrear_demand,
                        SUM( CASE WHEN tbl_govt_saf_demand_dtl.fyear= '$fyear' then COALESCE(tbl_govt_saf_demand_dtl.amount, 0) - COALESCE(tbl_govt_saf_demand_dtl.adjust_amount, 0) else 0 end) as current_demand
                    from tbl_govt_saf_demand_dtl
                    where tbl_govt_saf_demand_dtl.status = 1
                    and tbl_govt_saf_demand_dtl.fyear<='$fyear' 
                    group by tbl_govt_saf_demand_dtl.govt_saf_dtl_id
                ),
                current_year_coll as (
    
                    select tbl_govt_saf_collection_dtl.govt_saf_dtl_id ,
                        SUM( CASE WHEN tbl_govt_saf_demand_dtl.fyear< '$fyear' then COALESCE(tbl_govt_saf_demand_dtl.amount, 0) - COALESCE(tbl_govt_saf_demand_dtl.adjust_amount, 0) else 0 end) as arrear_collection,
                        SUM( CASE WHEN tbl_govt_saf_demand_dtl.fyear= '$fyear' then COALESCE(tbl_govt_saf_demand_dtl.amount, 0) - COALESCE(tbl_govt_saf_demand_dtl.adjust_amount, 0) else 0 end) as current_collection
                    from tbl_govt_saf_collection_dtl
                    join tbl_govt_saf_demand_dtl on tbl_govt_saf_demand_dtl.id = tbl_govt_saf_collection_dtl.govt_saf_demand_dtl_id
                    join tbl_govt_saf_transaction on tbl_govt_saf_transaction.id = tbl_govt_saf_collection_dtl.govt_saf_transaction_id
                    where tbl_govt_saf_transaction.status in(1,2) and tbl_govt_saf_transaction.tran_date between '$fromDate' and '$uptoDate' 
                        and tbl_govt_saf_demand_dtl.status=1
                    group by tbl_govt_saf_collection_dtl.govt_saf_dtl_id
                ),
                prive_coll as (
                    select tbl_govt_saf_collection_dtl.govt_saf_dtl_id ,
                        SUM(COALESCE(tbl_govt_saf_demand_dtl.amount, 0) - COALESCE(tbl_govt_saf_demand_dtl.adjust_amount, 0)) as prive_collection,
                        SUM( CASE WHEN tbl_govt_saf_demand_dtl.fyear< '$fyear' then COALESCE(tbl_govt_saf_demand_dtl.amount, 0) - COALESCE(tbl_govt_saf_demand_dtl.adjust_amount, 0) else 0 end) as arrear_collection,
                        SUM( CASE WHEN tbl_govt_saf_demand_dtl.fyear= '$fyear' then COALESCE(tbl_govt_saf_demand_dtl.amount, 0) - COALESCE(tbl_govt_saf_demand_dtl.adjust_amount, 0) else 0 end) as current_collection
                    from tbl_govt_saf_collection_dtl
                    join tbl_govt_saf_demand_dtl on tbl_govt_saf_demand_dtl.id = tbl_govt_saf_collection_dtl.govt_saf_demand_dtl_id
                    join tbl_govt_saf_transaction on tbl_govt_saf_transaction.id = tbl_govt_saf_collection_dtl.govt_saf_transaction_id
                    where tbl_govt_saf_transaction.status in(1,2) and tbl_govt_saf_transaction.tran_date < '$fromDate' 
                        and tbl_govt_saf_demand_dtl.status=1
                    group by tbl_govt_saf_collection_dtl.govt_saf_dtl_id 
                ),
                owners as (
                    select govt_saf_dtl_id,string_agg(officer_name,',') as officer_name,string_agg(mobile_no::text,',') as mobile_no
                    from tbl_govt_saf_officer_dtl
                    where status = 1
                    group by govt_saf_dtl_id
                ),
                dcb as (
                    select tbl_govt_saf_dtl.id,1 as total_saf,tbl_govt_saf_dtl.ward_mstr_id,view_ward_mstr.ward_no,tbl_govt_saf_dtl.new_ward_mstr_id,new_ward.ward_no as new_ward_no,
                        tbl_govt_saf_dtl.application_no, tbl_govt_saf_dtl.address, tbl_govt_saf_dtl.office_name,
                        owners.officer_name,owners.mobile_no ,
                        CASE WHEN COALESCE(current_year_coll.arrear_collection, 0) > (COALESCE(demand.arrear_demand, 0) - COALESCE(prive_coll.prive_collection, 0)) then COALESCE(current_year_coll.arrear_collection, 0) 
                            WHEN (COALESCE(demand.arrear_demand, 0) - COALESCE(prive_coll.prive_collection, 0)) >0 THEN (COALESCE(demand.arrear_demand, 0) - COALESCE(prive_coll.prive_collection, 0)) ELSE COALESCE(current_year_coll.arrear_collection, 0) END as arrear_demand,
                        COALESCE(demand.current_demand, 0) as current_demand,
                        COALESCE(current_year_coll.arrear_collection, 0) as arrear_collection,
                        COALESCE(current_year_coll.current_collection, 0) as current_collection,
                        (CASE WHEN COALESCE(current_year_coll.arrear_collection, 0) > (COALESCE(demand.arrear_demand, 0) - COALESCE(prive_coll.prive_collection, 0)) then COALESCE(current_year_coll.arrear_collection, 0) 
                            WHEN (COALESCE(demand.arrear_demand, 0) - COALESCE(prive_coll.prive_collection, 0)) >0 THEN (COALESCE(demand.arrear_demand, 0) - COALESCE(prive_coll.prive_collection, 0)) 
                        ELSE COALESCE(current_year_coll.arrear_collection, 0) 
                        END) - COALESCE(current_year_coll.arrear_collection, 0) as arrear_outstand,
                        (COALESCE(demand.current_demand, 0) - COALESCE(current_year_coll.current_collection, 0)) as current_outstand
                    from tbl_govt_saf_dtl
                    left join demand on demand.govt_saf_dtl_id = tbl_govt_saf_dtl.id
                    left join current_year_coll on current_year_coll.govt_saf_dtl_id = tbl_govt_saf_dtl.id
                    left join prive_coll on prive_coll.govt_saf_dtl_id = tbl_govt_saf_dtl.id
                    left join owners on owners.govt_saf_dtl_id = tbl_govt_saf_dtl.id
                    left join view_ward_mstr on view_ward_mstr.id = tbl_govt_saf_dtl.ward_mstr_id
                    left join view_ward_mstr new_ward on new_ward.id = tbl_govt_saf_dtl.new_ward_mstr_id
                    where tbl_govt_saf_dtl.status = 1 
                    
                ),
                ward_wise as (
                    select view_ward_mstr.id,view_ward_mstr.ward_no,view_ward_mstr.id as ward_mstr_id,
                        sum(COALESCE(dcb.arrear_demand,0)) as arrear_demand,
                        sum(COALESCE(dcb.current_demand,0)) as current_demand,
                        sum(COALESCE(dcb.arrear_collection,0)) as arrear_collection,
                        sum(COALESCE(dcb.current_collection,0)) as current_collection,
                        sum(COALESCE(dcb.arrear_outstand,0)) as arrear_outstand,
                        sum(COALESCE(dcb.current_outstand,0)) as current_outstand,
                        count(dcb.id) as total_saf
                    from view_ward_mstr
                    left join dcb on dcb.ward_mstr_id = view_ward_mstr.id
                    group by view_ward_mstr.id,view_ward_mstr.ward_no
                )
                
            ";
            
            $where = " WHERE 1=1 ";
            if($ward_id){
                $where.=" AND ward_mstr_id = ".$ward_id;
            }
            $from="select *,ROW_NUMBER() OVER () as s_no 
                    from dcb
                    $where
                    order by (substring(ward_no, '^[0-9]+'))::int,ward_no
            ";
            if($args["ward_wise"]??false){
                $from="select *,ROW_NUMBER() OVER () as s_no 
                        from ward_wise
                        $where
                        order by (substring(ward_no, '^[0-9]+'))::int,ward_no
                ";
            }
    
            $sql = $with.$from;
            $data = $this->db->query($sql)->getResultArray();            
            if($args["cmd"]??false){
                return $data;
            }
            $summary = [
                "total"=> sizeof($data),
                "arrear_demand" =>array_sum(array_column($data,'arrear_demand')),
                "current_demand" =>array_sum(array_column($data,'current_demand')),
                "arrear_collection" =>array_sum(array_column($data,'arrear_collection')),
                "current_collection" =>array_sum(array_column($data,'current_collection')),
                "arrear_outstand" =>array_sum(array_column($data,'arrear_outstand')),
                "current_outstand" =>array_sum(array_column($data,'current_outstand')),
                "total_saf" =>array_sum(array_column($data,'total_saf')),
            ];
            $response = array(
                "draw" => 0,
                "recordsTotal" => sizeof($data) ??0,
                "recordsFiltered" => $totalRecordwithFilter??0,
                "data" => $data,
                "summary" => $summary??[],
    
            );
            return json_encode($response);
        }
        $data["fyearList"]=fy_year_list();
        $ulb_mstr_id = 1;
        $data['wardList'] = $this->model_ward_mstr->getWardList(['ulb_mstr_id' => $ulb_mstr_id]);
        return view("property/reports/gbsafDCB",$data);
        
    }
    
    public function gbSAFWardWiseDCB($args=[]){
        if($this->request->isAJAX() || ($args['cmd']??false)){
            $args["ward_wise"]=true;
            return $this->gbSAFDCB($args);
        }
        $data["fyearList"]=fy_year_list();
        $ulb_mstr_id = 1;
        $data['wardList'] = $this->model_ward_mstr->getWardList(['ulb_mstr_id' => $ulb_mstr_id]);
        return view("property/reports/gbSAFWardWiseDCB",$data);
    }


    public function lastYearNotPaid($type="new_holding_no"){
        
        $fyear = getFY();
        list($fromYear,$uptoYear)=explode("-",$fyear);
        $privFyear = ($fromYear-1)."-".($uptoYear-1);
    
        if($this->request->isAJAX() || $this->request->getVar("export")){
            set_time_limit(1500);
            $where = " ";
            
            if ($this->request->getVar("ward_id")) {
                $where .= " AND ward_mstr_id = " . $this->request->getVar("ward_id");
            }
            if ($this->request->getVar("assessmentType")) {
                $where .= " AND assessment_type = '" . $this->request->getVar("assessmentType") . "'";
            }
            if ($this->request->getVar("property_type_id")) {
                $where .= " AND prop_type_mstr_id = " . $this->request->getVar("property_type_id");
            }
    
            $start = sanitizeString($this->request->getVar('start'));
            $rowperpage = sanitizeString($this->request->getVar('length')); // Rows display per page
    
            $columnIndex = sanitizeString($this->request->getVar('order')[0]['column']); // Column index
            $columnName = sanitizeString($this->request->getVar('columns')[$columnIndex]['data']); // Column name
            if ($columnName == "ward_no")
                $columnName = 'ward_mstr_id';
            // else if ($columnName=="apartment_name")
            //     $columnName = 'apartment_name';
            // else if ($columnName=="apt_code")
            //     $columnName = 'apt_code';
            else
                $columnName = 'ward_mstr_id';
    
            $columnSortOrder = sanitizeString($this->request->getVar('order')[0]['dir']);
            $orderBY = " ORDER BY " . $columnName . " " . $columnSortOrder;
            $limit = " LIMIT " . ($rowperpage == -1 ? "ALL" : $rowperpage) . " OFFSET " . $start;
    
    
            $searchValue = sanitizeString($this->request->getVar('search')['value']);
            $whereQueryWithSearch = "";
    
            $with = "WITH not_paid_demand AS (
                    select sum(tbl_prop_demand.balance) as balance,prop_dtl_id
                    from tbl_prop_demand 
                    where tbl_prop_demand.fyear='".$privFyear."' 
                        and tbl_prop_demand.paid_status =0 
                        and tbl_prop_demand.status=1
                    group by prop_dtl_id
                    
                ),
                owners AS (
                    SELECT tbl_prop_owner_detail.prop_dtl_id, STRING_AGG(owner_name,',') owner_name,
                        STRING_AGG(mobile_no::TEXT,',') mobile_no
                    FROM tbl_prop_owner_detail
                    JOIN not_paid_demand ON not_paid_demand.prop_dtl_id = tbl_prop_owner_detail.prop_dtl_id
                    WHERE tbl_prop_owner_detail.status = 1
                    GROUP BY tbl_prop_owner_detail.prop_dtl_id
                )
            ";
            $select = "SELECT ROW_NUMBER() OVER ( $orderBY) as s_no,view_ward_mstr.ward_no,
                        holding_no,new_holding_no,not_paid_demand.balance,
                        tbl_prop_dtl.prop_address,
                        owners.owner_name,owners.mobile_no  
            ";
            $from = "FROM tbl_prop_dtl
                    JOIN not_paid_demand on not_paid_demand.prop_dtl_id= tbl_prop_dtl.id
                    LEFT JOIN owners ON owners.prop_dtl_id = tbl_prop_dtl.id
                    LEFT JOIN view_ward_mstr on view_ward_mstr.id = tbl_prop_dtl.ward_mstr_id
                    where 1=1
                        AND tbl_prop_dtl.status=1 
                        ".($type=="new_holding_no" ? " and trim(new_holding_no)!='' ":"")."
            ";
    
            $groupBy = "";
    
            $select2 = "SELECT count(tbl_prop_dtl.id) as total,sum(not_paid_demand.balance) as balance
            ";
    
            $from = $from . " " . $where;
            $totalRecords = $this->model_datatable->getTotalRecords($from, false, $with);
            $totalSummary = $this->model_datatable->getRecords($with . $select2 . $from, false)[0];
            if ($totalRecords > 0) {
    
                ## Total number of records with filtering
                $totalRecordwithFilter = $this->model_datatable->getTotalRecordwithFilter($from . $whereQueryWithSearch, false, $with);
    
                ## Fetch records                
                $fetchSql = $with . $select . $from . $whereQueryWithSearch . $groupBy . $orderBY;
                if (!$this->request->getVar("export")??false) {
                    $fetchSql .= $limit;
                }
                // print_var($fetchSql);die;
                $result = $this->model_datatable->getRecords($fetchSql, false);
    
                $records = [];
                if ($result) {
                    $records = $result;
                    // foreach ($result as $key => $tran_dtl) {
                    //     $records[] = [
                    //         's_no' => $tran_dtl['s_no'],
                    //         'ward_no' => $tran_dtl['ward_no'],
                    //         'holding_no' => $tran_dtl['holding_no'],
                    //         'new_holding_no' => $tran_dtl['new_holding_no'],
                    //         'owner_name' => $tran_dtl['owner_name'],
                    //         'mobile_no' => $tran_dtl['mobile_no'],
                    //         'prop_address' => $tran_dtl["prop_address"],
                    //         'balance' => $tran_dtl['balance'],
                    //     ];
                    // }
                }
            } else {
                $totalRecordwithFilter = 0;
                $records = [];
            }
            if ($this->request->getVar("export")??false) {
                phpOfficeLoad();
                $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
                $activeSheet = $spreadsheet->getActiveSheet();                
                $activeSheet->setCellValue('A1', 'Sl No.');
                $activeSheet->setCellValue('B1', 'Ward No');
                $activeSheet->setCellValue('C1', 'Hoalding No.');
                $activeSheet->setCellValue('D1', 'New Hoalding No.');
                $activeSheet->setCellValue('E1', 'Owner Name.');
                $activeSheet->setCellValue('F1', 'Mobil No.');
                $activeSheet->setCellValue('G1', 'Address');
                $activeSheet->setCellValue('H1', "Demand($privFyear)");
    
                $filename = "PrivYear($privFyear) Not Pay" . date('Ymd-His') . ".xlsx";
            
                $activeSheet->fromArray($records, NULL, 'A3');
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="' . $filename . '"');
                header('Cache-Control: max-age=0');
                $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                $writer->save('php://output');
                die;
    
            } else {
                $response = array(
                    "draw" => 0,
                    "recordsTotal" => $totalRecords,
                    "recordsFiltered" => $totalRecordwithFilter,
                    "data" => $records,
                    "summary" => $totalSummary,
    
                );
                return json_encode($response);
            }
        }
        $data["privFyear"] = $privFyear;
        $ulb_mstr_id = 1;
        $data['wardList'] = $this->model_ward_mstr->getWardList(['ulb_mstr_id' => $ulb_mstr_id]);
        return view("property/reports/lastYearNotPaid",$data);
    }


    public function HoldingDemand()
    {
        $data = [];
        $inputs =arrFilterSanitizeString($this->request->getVar());
        $data = $inputs;
        $ulb_mstr_id = 1;
        $data['wardList'] = $wardList = $this->model_ward_mstr->getWardList(['ulb_mstr_id' => $ulb_mstr_id]);
        $data["propertyTypeList"] = $this->model_prop_type_mstr->getPropTypeList();
        $ward_mstr_ids = $inputs["ward_mstr_id"] ?? [];
        $property_type_mstr_ids = $inputs["property_type_mstr_id"]?? [];
        $paid_status = $inputs["paid_status"]??null;
        $rwh_status = $inputs["rwh_status"]??null;
        $area_of_plot = $inputs["area_of_plot"]??null;

        $currentFY = getFY();
        $currentFyID = $this->model_fy_mstr->getFyByFy(['fy' => $currentFY])['id'];

        if ($this->request->getGet('btn_search') !== null || isset($data["export"])) {
            try {
                if (!empty($currentFyID)) {
                    $Where = "";
                    if (!empty($ward_mstr_ids)) {
                        $safeWardIds = implode(",", $ward_mstr_ids); 
                        $Where .= " AND tbl_prop_dtl.new_ward_mstr_id IN ($safeWardIds)";
                    }

                    if (!empty($property_type_mstr_ids)) {
                        $safePropTypeIds = implode(",", $property_type_mstr_ids);
                        $Where .= " AND tbl_prop_dtl.prop_type_mstr_id IN ($safePropTypeIds)";
                    }

                    if ($rwh_status != '') {
                        $Where .= " AND tbl_prop_dtl.is_water_harvesting = '$rwh_status'";
                    }


                    if ($area_of_plot != '') {
                        $Where .= " AND tbl_prop_dtl.area_of_plot <= '$area_of_plot'";
                    }

                    if($paid_status != ''){
                        $Where .= " AND tbl_prop_demand.paid_status = '$paid_status'";
                    }

                    $sql = "WITH current_demand AS (
                        SELECT 
                            SUM(COALESCE(tbl_prop_demand.amount, 0) - COALESCE(tbl_prop_demand.adjust_amt, 0)) AS current_year_demand,
                            view_ward_mstr.ward_no, 
                            prop_owner_detail.owner_name, 
                            tbl_prop_dtl.new_holding_no, 
                            tbl_prop_dtl.prop_address, 
                            tbl_prop_type_mstr.property_type, 
                            tbl_prop_demand.prop_dtl_id,
                            tbl_prop_dtl.area_of_plot
                        FROM tbl_prop_demand 
                        INNER JOIN tbl_prop_dtl 
                            ON tbl_prop_dtl.id = tbl_prop_demand.prop_dtl_id
                        LEFT JOIN view_ward_mstr 
                            ON view_ward_mstr.id = tbl_prop_dtl.new_ward_mstr_id
                        INNER JOIN tbl_prop_type_mstr 
                            ON tbl_prop_type_mstr.id = tbl_prop_dtl.prop_type_mstr_id
                        LEFT JOIN (
                            SELECT 
                            STRING_AGG(owner_name, ', ') AS owner_name, 
                            STRING_AGG(mobile_no::TEXT, ', ') AS mobile_no, 
                            prop_dtl_id 
                            FROM tbl_prop_owner_detail 
                            GROUP BY prop_dtl_id
                        ) prop_owner_detail 
                            ON prop_owner_detail.prop_dtl_id = tbl_prop_demand.prop_dtl_id
                        WHERE 
                            tbl_prop_demand.status = 1 
                            AND tbl_prop_dtl.status = 1 
                            AND CHAR_LENGTH(tbl_prop_dtl.new_holding_no) > 0  
                            AND (tbl_prop_dtl.govt_saf_dtl_id IS NULL OR tbl_prop_dtl.govt_saf_dtl_id = 0)
                            AND tbl_prop_demand.fy_mstr_id = '$currentFyID' $Where
                        GROUP BY 
                            tbl_prop_demand.prop_dtl_id, 
                            new_holding_no,
                            tbl_prop_dtl.prop_address, 
                            tbl_prop_type_mstr.property_type,
                            view_ward_mstr.ward_no,
                            prop_owner_detail.owner_name,
                            tbl_prop_dtl.area_of_plot
                        )
                        SELECT prop_dtl_id, ward_no as new_ward_no, owner_name, new_holding_no, prop_address, area_of_plot, property_type,current_year_demand
                        FROM current_demand ORDER BY current_year_demand DESC";

                    if (isset($data["export"]) && $data["export"]) {
                        $result = $this->model_datatable->getRecords($sql, false);
                        
                        phpOfficeLoad();
                        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
                        $activeSheet = $spreadsheet->getActiveSheet();
                        $activeSheet->setCellValue('A1', 'New Ward No.');
                        $activeSheet->setCellValue('B1', 'New Holding No');
                        $activeSheet->setCellValue('C1', 'property_type');

                        $activeSheet->setCellValue('D1', 'Owner Name');
                        $activeSheet->setCellValue('E1', 'Area of Plot');
                        $activeSheet->setCellValue('F1', 'Address');
                        $activeSheet->fromArray($result, NULL, 'A1');
                        $filename = "holding_demand" . date('Ymd-hisa') . ".xlsx";
                        header('Content-Type: application/vnd.ms-excel');
                        header('Content-Disposition: attachment;filename="' . $filename . '"');
                        header('Cache-Control: max-age=0');
                        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                        $writer->save('php://output');
                        return;
                    }

                    $result = $this->model_datatable->getDatatable($sql);
                    // dd($this->db->getLastQuery());
                    $data['results'] = $result['result'];
                    $data['offset'] = $result['offset'];
                    $data['pager'] = $result['count'];
                }

            } catch (Exception $e) {
                log_message("error", $e->getMessage());
            }

        }

        return view('property/reports/holding_wise_demand', $data);
    }


}

