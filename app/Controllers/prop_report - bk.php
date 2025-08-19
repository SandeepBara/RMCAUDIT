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
        ini_set('memory_limit', '-1');
        error_reporting(-1);
        parent::__construct();
    	helper(['url', 'db_helper', 'validation_helper', 'validate_saf_helper', 'sms_helper', 'php_office_helper', 'utility_helper']);
        if($db_name = dbConfig("property")){  $this->db = db_connect($db_name); }
        if ($db_name = dbSystem()) { $this->dbSystem = db_connect($db_name); }
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
                            $activeSheet->setCellValue('C1', 'Address');
                            $activeSheet->setCellValue('D1', 'From/Upto');
                            $activeSheet->setCellValue('E1', 'Tran. Date');
                            $activeSheet->setCellValue('F1', 'Mode');
                            $activeSheet->setCellValue('G1', 'Amount');
                            $activeSheet->setCellValue('H1', 'Tax Collector');
                            $activeSheet->setCellValue('I1', 'Tran. No.');
                            $activeSheet->setCellValue('J1', 'Check/DD No');
                            $activeSheet->setCellValue('K1', 'Bank');
                            $activeSheet->setCellValue('L1', 'Branch');
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
            SELECT
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
            GROUP BY tbl_transaction.tran_mode_mstr_id
        )
        SELECT 
            tbl_tran_mode_mstr.transaction_mode,
            tbl_transaction.no_of_tran,
            tbl_transaction.payable_amt
        FROM tbl_transaction
        INNER JOIN tbl_tran_mode_mstr ON tbl_tran_mode_mstr.id=tbl_transaction.tran_mode_mstr_id";
        if($report_list = $this->model_datatable->getRecords($sql)) {
            $data['jsk_success_tran_list'] = $report_list;
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
            SELECT
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
            GROUP BY tbl_transaction.tran_mode_mstr_id
        )
        SELECT 
            tbl_tran_mode_mstr.transaction_mode,
            tbl_transaction.no_of_tran,
            tbl_transaction.payable_amt
        FROM tbl_transaction
        INNER JOIN tbl_tran_mode_mstr ON tbl_tran_mode_mstr.id=tbl_transaction.tran_mode_mstr_id";
        if($report_list = $this->model_datatable->getRecords($sql)) {
            $data['jsk_success_tran_list'] = $report_list;
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

    public function deactivatedHolding() {
        $ulb_mstr_id = 1;
        $wardList = $this->model_ward_mstr->getWardList(['ulb_mstr_id'=>$ulb_mstr_id]);
        $data['wardList'] = $wardList;
        return view('property/reports/deactivated_holding', $data);
    }

    public function deactivatedHoldingAjax() {
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
                        INNER JOIN (SELECT STRING_AGG(owner_name, ', ') AS owner_name, STRING_AGG(mobile_no::TEXT, ', ') AS mobile_no, prop_dtl_id FROM tbl_prop_owner_detail GROUP BY prop_dtl_id) prop_owner_detail ON prop_owner_detail.prop_dtl_id=tbl_prop_dtl.id
                        INNER JOIN view_ward_mstr ON view_ward_mstr.id=tbl_prop_dtl.ward_mstr_id
                        WHERE tbl_prop_dtl.status=0 AND view_ward_mstr.status=1".$whereQuery;
                
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

    public function deactivatedHoldingExcel($search_ward_mstr_id = null) {
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
                        INNER JOIN (SELECT STRING_AGG(owner_name, ', ') AS owner_name, STRING_AGG(mobile_no::TEXT, ', ') AS mobile_no, prop_dtl_id FROM tbl_prop_owner_detail GROUP BY prop_dtl_id) prop_owner_detail ON prop_owner_detail.prop_dtl_id=tbl_prop_dtl.id
                        INNER JOIN view_ward_mstr ON view_ward_mstr.id=tbl_prop_dtl.ward_mstr_id
                        WHERE tbl_prop_dtl.status=0 AND view_ward_mstr.status=1".$whereQuery;

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

    public function wardWiseDCB()
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
//                        $Where22_23 =" AND tbl_prop_demand.fy_mstr_id=55";
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
//                    if($data['fy_mstr_id']==55){
//                        $report_list['52']['arrear_demand']=85645;
//                        $report_list['52']['arrear_balance_amount'] = $report_list['52']['arrear_demand']-$report_list['52']['arrear_collection_amount'];
//                    }
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
                WHERE tbl_saf_dtl.status=1 ".$where." AND tbl_level_pending_dtl.receiver_user_type_id=11 AND tbl_level_pending_dtl.status=1 AND tbl_level_pending_dtl.verification_status=2 GROUP BY tbl_saf_dtl.ward_mstr_id
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
            ".$whereward."
            WHERE view_emp_details.status=1 and view_emp_details.lock_status=0 ".$whereParam1."
            and view_emp_details.user_type_mstr_id=5";
            //print_var($sql);
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
//                    return $selectStatement.$sql;
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

//        dd($data);
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
//                    return $selectStatement.$sql;
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

}

