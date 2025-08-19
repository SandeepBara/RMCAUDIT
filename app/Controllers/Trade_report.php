<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\model_ward_mstr;
use App\Models\TradeApplyLicenceModel;
use App\Models\model_trade_level_pending_dtl;
use App\Models\model_trade_transaction;
use App\Models\model_application_type_mstr;
use App\Models\TradeTransactionModel;

use App\Models\model_view_trade_licence;

//use App\Models\model_ward_mstr;
use App\Models\model_ward_permission;
use App\Models\model_view_emp_details;
use App\Models\model_fy_mstr;
use App\Models\model_tran_mode_mstr;
use App\Models\model_datatable;

use App\Models\model_trade_sms_log;
use App\Models\TradeLicenceRateModel;
use App\Models\model_trade_transaction_fine_rebet_details;
use App\Models\TradeChequeDtlModel;
use App\Controllers\tradeapplylicence;
use App\Models\Citizensw_trade_model;
use App\Models\model_visiting_dtl;
use Exception;


class Trade_report extends AlphaController
{
    protected $model_view_trade_licence;
    protected $Citizensw_trade_model;
    protected $model_visiting_dtl;

    public function __construct()
    {
        $session=session();
        $get_ulb_detail=$session->get('ulb_dtl');
        //print_r($get_ulb_detail);

        $this->ulb_id=$get_ulb_detail['ulb_mstr_id'];


        parent::__construct();
    	helper(['db_helper','form','form_helper','url_helper','utility_helper','qr_code_generator_helper']);
        helper(['url', 'db_helper', 'validation_helper', 'validate_saf_helper', 'sms_helper', 'php_office_helper', 'utility_helper']);
        if($db_name = dbConfig("property"))
        {
            $this->db = db_connect($db_name); 
        }
        if($db_name = dbConfig("trade"))
        {
            $this->trade = db_connect($db_name); 
        }
        if($db_system = dbSystem())
        {
            $this->dbSystem = db_connect($db_system); 
        }
       
        $this->ward_model=new model_ward_mstr($this->dbSystem);
        $this->TradeApplyLicenceModel=new TradeApplyLicenceModel($this->trade);
		$this->model_trade_level_pending_dtl=new model_trade_level_pending_dtl($this->trade);
        $this->model_trade_transaction=new model_trade_transaction($this->trade);
        $this->model_application_type_mstr = new model_application_type_mstr($this->db);
        $this->TradeTransactionModel = new TradeTransactionModel($this->trade);

        $this->model_view_trade_licence = new model_view_trade_licence($this->trade);



        $this->model_ward_mstr = new model_ward_mstr($this->dbSystem);
		$this->model_ward_permission = new model_ward_permission($this->dbSystem);
        $this->model_view_emp_details = new model_view_emp_details($this->dbSystem);
        $this->model_fy_mstr = new model_fy_mstr($this->dbSystem);
        $this->model_tran_mode_mstr = new model_tran_mode_mstr($this->db);
        //$this->model_datatable = new model_datatable($this->db);
        $this->model_datatable = new model_datatable($this->trade);

        $this->model_trade_sms_log = new model_trade_sms_log($this->trade);

        $this->tradelicenceratemodel =  new tradelicenceratemodel($this->trade);
        $this->model_trade_transaction_fine_rebet_details = new model_trade_transaction_fine_rebet_details($this->trade);
        $this->TradeChequeDtlModel = new TradeChequeDtlModel($this->trade);

        $this->con_tradeapplylicence= new tradeapplylicence();
        $this->Citizensw_trade_model = new Citizensw_trade_model($this->trade); 
        $this->model_visiting_dtl = new model_visiting_dtl($this->dbSystem);

    }
    
    public function index()
    {
        return redirect()->to('Dashboard/welcome');
    }

    public function application_against_denial()
    {
        $data=[];
        $data['ward']=array();
        $Session = Session();
        $data['report']=array();
        $from_date = date('Y-m-d');
        $to_date = date('Y-m-d');
        $ward_id='';
       //print_var($this->ulb_id);
        $ward_sql = " select * from view_ward_mstr where status = 1 and ulb_mstr_id = ? "; 
        $data['ward']=$this->model_view_trade_licence->row_query($ward_sql,array($this->ulb_id));
        $and_where = " ";
        if (isset($_GET['page']) && $_GET['page']== 'clr')
        {
            $Session->remove('tempData'); //echo('remove');
        }
        if(strtoupper($this->request->getMethod())==strtoupper("post"))
        {
            $inputs = filterSanitizeStringtoUpper($this->request->getVar());            
            $Session->set('tempData', $inputs);
            //print_var($_POST);
           
        }
        $tempData=$Session->get('tempData');
        if(!empty($tempData))
        {   
            $from_date=$tempData['from_date'];
            $to_date = $tempData['to_date'];
            $ward_id = $tempData['ward_id'];
            if($tempData['ward_id'] !='')
                $and_where=" and w.id = $tempData[ward_id]";
        }
        $report_sql="   with owner as(
                            select distinct (apply_licence_id) as apply_licence_id,
                                string_agg(owner_name::text,' ,'::text) as owner_name,
                                string_agg(guardian_name::text,' ,'::text) as guardian_name,
                                string_agg(mobile::text,' ,'::text) as mobile
                            from tbl_firm_owner_name
                            group by apply_licence_id
                        )
                        select n.notice_no,cast(n.created_on as date) as verify_date,
                            cast(dc.created_on as date) as apply_date,
                            l.application_no,l.firm_name,l.address,
                            ow.owner_name,ow.guardian_name,ow.mobile,
                            w.ward_no
                        from tbl_denial_notice n
                        join tbl_denial_consumer_dtl dc on dc.id=n.denial_id
                        join tbl_apply_licence l on l.id = n.apply_id
                        join view_ward_mstr w on w.id = l.ward_mstr_id
                        join owner ow on ow.apply_licence_id = l.id 
                        where cast(dc.created_on as date) >= '$from_date' and cast(dc.created_on as date) <= '$to_date' $and_where ";
        $result=$this->model_view_trade_licence->row_query2($report_sql);
        $data['report']=$result['result'];
        $data['count']=$result['count'];
        $data['offset']=$result['offset'];
        $data['from_date']=$from_date;
        $data['to_date']=$to_date;
        $data['ward_id']=$ward_id ;
        return view("Trade/Reports/application_against_denial", $data);
    }

    public function total_denial_apply_copy()
    {
        $data=[];
        $data['ward']=array();
        $Session = Session();
        $data['report']=array();
        $from_date = date('Y-m-d');
        $to_date = date('Y-m-d');
        $ward_id='';
       //print_var($this->ulb_id);
        $ward_sql = " select * from view_ward_mstr where status = 1 and ulb_mstr_id = ? "; 
        $data['ward']=$this->model_view_trade_licence->row_query($ward_sql,array($this->ulb_id));
        $and_where = " ";
        if (isset($_GET['page']) && $_GET['page']== 'clr')
        {
            $Session->remove('tempData'); //echo('remove');
        }
        if(strtoupper($this->request->getMethod())==strtoupper("post"))
        {
            $inputs = filterSanitizeStringtoUpper($this->request->getVar());            
            $Session->set('tempData', $inputs);
            //print_var($_POST);
           
        }
        $tempData=$Session->get('tempData');
        if(!empty($tempData))
        {   
            $from_date=$tempData['from_date'];
            $to_date = $tempData['to_date'];
            $ward_id = $tempData['ward_id'];
            if($tempData['ward_id'] !='')
                $and_where=" and w.id = $tempData[ward_id]";
        }
        $report_sql="   with owner as(
                            select distinct (apply_licence_id) as apply_licence_id,
                                string_agg(owner_name::text,' ,'::text) as owner_name,
                                string_agg(guardian_name::text,' ,'::text) as guardian_name,
                                string_agg(mobile::text,' ,'::text) as mobile
                            from tbl_firm_owner_name
                            group by apply_licence_id
                        )
                        select n.notice_no,cast(n.created_on as date) as verify_date,
                            cast(dc.created_on as date) as apply_date,
                            l.application_no,l.firm_name,l.address,
                            ow.owner_name,ow.guardian_name,ow.mobile,
                            w.ward_no
                        from tbl_denial_notice n
                        join tbl_denial_consumer_dtl dc on dc.id=n.denial_id
                        join tbl_apply_licence l on l.id = n.apply_id
                        join view_ward_mstr w on w.id = l.ward_mstr_id
                        join owner ow on ow.apply_licence_id = l.id 
                        where cast(dc.created_on as date) >= '$from_date' and cast(dc.created_on as date) <= '$to_date' $and_where ";


        $report_sql = " with owner as(
                            select distinct (apply_licence_id) as apply_licence_id,
                                string_agg(owner_name::text,' ,'::text) as owner_name,
                                string_agg(guardian_name::text,' ,'::text) as guardian_name,
                                string_agg(mobile::text,' ,'::text) as mobile
                            from tbl_firm_owner_name
                            group by apply_licence_id
                        )
                        select n.apply_id,n.notice_no,cast(n.created_on as date) as verify_date,
                            cast(dc.created_on as date) as apply_date,
                            l.application_no,
                            case when n.apply_id isnull then 'not apply'::text else l.apply_date::text end as licence_apply_date,	
                            case when n.apply_id isnull then dc.firm_name else l.firm_name end as firm_name,
                            case when n.apply_id isnull then dc.address else l.address end as address,
                            case when n.apply_id isnull then dc.applicant_name else ow.owner_name end as owner_name,
                            case when n.apply_id isnull then null else ow.guardian_name end as guardian_name,
                            case when n.apply_id isnull then dc.mob_no::text else ow.mobile end as mobile,
                            case when n.apply_id isnull then wl.ward_no else w.ward_no end as ward_no,
                            dc.ward_id,l.ward_mstr_id
                        from tbl_denial_notice n
                        join tbl_denial_consumer_dtl dc on dc.id=n.denial_id
                        left join tbl_apply_licence l on l.id = n.apply_id
                        left join view_ward_mstr w on w.id = l.ward_mstr_id
                        left join owner ow on ow.apply_licence_id = l.id 
                        left join view_ward_mstr wl on wl.id = dc.ward_id
                        where cast(dc.created_on as date) >= '$from_date' and cast(dc.created_on as date) <= '$to_date' 
                            $and_where
                        order by n.apply_id desc
        "; //print_var($report_sql);
        $result=$this->model_view_trade_licence->row_query2($report_sql);
        $data['report']=$result['result'];
        $data['count']=$result['count'];
        $data['offset']=$result['offset'];
        $data['from_date']=$from_date;
        $data['to_date']=$to_date;
        $data['ward_id']=$ward_id ;
        $sql_count = "  with owner as(
                            select distinct (apply_licence_id) as apply_licence_id,
                                string_agg(owner_name::text,' ,'::text) as owner_name,
                                string_agg(guardian_name::text,' ,'::text) as guardian_name,
                                string_agg(mobile::text,' ,'::text) as mobile
                            from tbl_firm_owner_name
                            group by apply_licence_id
                        )
                        select count(n.id) as total, count(case when n.apply_id isnull then n.id else null end) as not_apply,
                            count(case when n.apply_id notnull then n.id else null end) as apply
                        from tbl_denial_notice n
                        join tbl_denial_consumer_dtl dc on dc.id=n.denial_id
                        left join tbl_apply_licence l on l.id = n.apply_id
                        left join view_ward_mstr w on w.id = l.ward_mstr_id
                        left join owner ow on ow.apply_licence_id = l.id 
                        left join view_ward_mstr wl on wl.id = dc.ward_id
                        where cast(dc.created_on as date) >= '$from_date' and cast(dc.created_on as date) <= '$to_date' 
                            $and_where ";
       $count_apply=$this->model_view_trade_licence->row_query($sql_count);
        $data['total']=0;
        $data['not_apply']=0;
        $data['apply']=0;
        //print_var($count_apply);
        if($count_apply)
        {
            $data['total']=$count_apply[0]['total'];
            $data['not_apply']=$count_apply[0]['not_apply'];
            $data['apply']=$count_apply[0]['apply'];
        }
        return view("Trade/Reports/total_denial_apply", $data); 
    }

    public function total_notice_applyExcel($search_from_date = null, $search_upto_date = null, $search_ward_mstr_id = null)
    {       
        try{
                $whereQuery = " cast(dc.created_on as date) BETWEEN '".$search_from_date."' AND '".$search_upto_date."'";
            
                if ($search_ward_mstr_id != 'ALL') 
                {
                    $whereQuery .= " AND  l.ward_mstr_id='".$search_ward_mstr_id."'";
                }
                

                $selectStatement = "SELECT
                                        n.apply_id,n.notice_no,cast(n.created_on as date) as verify_date,
                                        cast(dc.created_on as date) as apply_date,
                                        l.application_no,
                                        case when n.apply_id isnull then 'not apply'::text else l.apply_date::text end as licence_apply_date,	
                                        case when n.apply_id isnull then dc.firm_name else l.firm_name end as firm_name,
                                        case when n.apply_id isnull then dc.address else l.address end as address,
                                        case when n.apply_id isnull then dc.applicant_name else ow.owner_name end as owner_name,
                                        case when n.apply_id isnull then null else ow.guardian_name end as guardian_name,
                                        case when n.apply_id isnull then dc.mob_no::text else ow.mobile end as mobile,
                                        case when n.apply_id isnull then wl.ward_no else w.ward_no end as ward_no,
                                        dc.ward_id,l.ward_mstr_id  
                                    ";

                $with_query =" with owner as(
                                    select distinct (apply_licence_id) as apply_licence_id,
                                        string_agg(owner_name::text,' ,'::text) as owner_name,
                                        string_agg(guardian_name::text,' ,'::text) as guardian_name,
                                        string_agg(mobile::text,' ,'::text) as mobile
                                    from tbl_firm_owner_name
                                    group by apply_licence_id
                                )";
                $sql ="from tbl_denial_notice n
                        join tbl_denial_consumer_dtl dc on dc.id=n.denial_id
                        left join tbl_apply_licence l on l.id = n.apply_id
                        left join view_ward_mstr w on w.id = l.ward_mstr_id
                        left join owner ow on ow.apply_licence_id = l.id 
                        left join view_ward_mstr wl on wl.id = dc.ward_id 
                        where                  
                        ".$whereQuery." order by n.apply_id desc ";

            $fetchSql = $with_query.$selectStatement.$sql;
            
            $result = $this->model_datatable->getRecords($fetchSql,false);
            $records = [];
            if ($result) 
            {
                foreach ($result AS $key=>$tran_dtl) 
                {
                    

                    $records[] = [
                        // 's_no'=>$tran_dtl['s_no'],
                        'application_no'=>$tran_dtl['application_no'],
                        'notice_no'=>$tran_dtl['notice_no'],
                        'firm_name'=>$tran_dtl['firm_name'],
                        'address'=>$tran_dtl['address'],
                        'ward_no'=>$tran_dtl['ward_no'],
                        'mobile'=>$tran_dtl['mobile'],
                        'verify_date'=>$tran_dtl['verify_date'],
                        'apply_date'=>$tran_dtl['apply_date'],
                        'licence_apply_date'=>$tran_dtl['licence_apply_date'],

                        
                    ];

                }
            }
            phpOfficeLoad();
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $activeSheet = $spreadsheet->getActiveSheet();
                            $activeSheet->setCellValue('A1', 'Application No.');
                            $activeSheet->setCellValue('B1', 'Notice No');
                            $activeSheet->setCellValue('C1', 'Firm Name');
                            $activeSheet->setCellValue('D1', 'Address');
                            $activeSheet->setCellValue('E1', 'Ward No.');
                            $activeSheet->setCellValue('F1', 'Mobile No');
                            $activeSheet->setCellValue('G1', 'Denial Approval Date');
                            $activeSheet->setCellValue('H1', 'Denial Apply Date');
                            $activeSheet->setCellValue('I1', 'Application Apply Date');
                            //$activeSheet->setCellValue('J1', 'Address');


                            $activeSheet->fromArray($records, NULL, 'A3');

            $filename = "Notice_APPLY_".date('Ymd-hisa').".xlsx";
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="'.$filename.'"');
            header('Cache-Control: max-age=0');
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            //$writer->save(APPPATH.'/hello world.xlsx');
            $writer->save('php://output');
        }
        catch(Exception $e)
        {
            print_r($e);
        }
    }
    public function total_notice_apply()
    {
        $data=[];
        $Session = session();
        $data['ulb_dtl']=$Session->get('ulb_dtl');
        $data['from_date']=date('Y-M-D');
        $data['to_date']=date('Y-M-D');
        $ward_sql = " select * from view_ward_mstr where status = 1 and ulb_mstr_id = ? "; 
        $data['ward']=$this->model_view_trade_licence->row_query($ward_sql,array($this->ulb_id));
        if($_POST)
        {
            
            try
                {
                    
                    ## Read value
                    $start = sanitizeString($this->request->getVar('start'));
                    
                    
                    $rowperpage = sanitizeString($this->request->getVar('length')); // Rows display per page
    
                    $columnIndex = sanitizeString($this->request->getVar('order')[0]['column']); // Column index
                    $columnName = sanitizeString($this->request->getVar('columns')[$columnIndex]['data']); // Column name
                    if ($columnName=="notice_no")
                        $columnName = 'n.notice_no';
                    else if ($columnName=="verify_date")
                        $columnName = 'cast(n.created_on as date)';
                    else if ($columnName=="apply_date")
                        $columnName = 'cast(dc.created_on as date)';
                    else if ($columnName=="application_no")
                        $columnName = 'l.application_no';
                    else if ($columnName=="firm_name")
                        $columnName = 'dc.firm_name , l.firm_name';
                    else if ($columnName=="owner_name")
                        $columnName = ' dc.applicant_name , ow.owner_name';
                    else if ($columnName=="mobile")
                        $columnName = 'dc.mob_no::text , ow.mobile';
                    else if ($columnName=="ward_no")
                        $columnName = ' wl.ward_no , w.ward_no';
                    else
                        $columnName = 'n.apply_id ';
                    
    
                    //$columnName = "tbl_transaction.tran_date";
                    $columnSortOrder = sanitizeString($this->request->getVar('order')[0]['dir']); // asc or desc
                    $searchValue = sanitizeString($this->request->getVar('search')['value']); // Search value
                    
                    
                    
                    // Date filter
                    $search_from_date = sanitizeString($this->request->getVar('search_from_date'));
                    $search_upto_date = sanitizeString($this->request->getVar('search_upto_date'));
                    $search_ward_mstr_id = sanitizeString($this->request->getVar('search_ward_mstr_id'));
                    // $search_collector_id = sanitizeString($this->request->getVar('search_collector_id'));
                    // $search_entry_type = sanitizeString($this->request->getVar('search_entry_type'));
                    $searchQuery = "";
                    $whereQuery = "";
                    
                    $orderBY = " ORDER BY ".$columnName." ".$columnSortOrder;
                    $limit = " LIMIT ".$rowperpage." OFFSET ".$start;
                    
                    $whereQuery .= "  cast(dc.created_on as date) BETWEEN '".$search_from_date."' AND '".$search_upto_date."'";
                    if ($search_ward_mstr_id != '') 
                    {
                        $whereQuery .= " AND  w.id='".$search_ward_mstr_id."'";
                    }
                   
                    $whereQueryWithSearch = "";
                    if ($searchValue!='') 
                    {
                        $whereQueryWithSearch = " AND (n.notice_no ILIKE '%".$searchValue."%'
                                        OR cast(n.created_on as date)::text ILIKE '%".$searchValue."%'
                                        OR w.ward_no ILIKE '%".$searchValue."%'
                                        OR dc.firm_name ILIKE '%".$searchValue."%' 
                                        OR l.firm_name ILIKE '%".$searchValue."%'
                                        OR dc.applicant_name ILIKE '%".$searchValue."%'
                                        OR ow.owner_name ILIKE '%".$searchValue."%'
                                        OR dc.mob_no::text ILIKE '%".$searchValue."%'
                                        OR ow.mobile ILIKE '%".$searchValue."%'
                                        OR cast(dc.created_on as date)::text ILIKE '%".$searchValue."%'
                                        OR l.application_no ILIKE'%".$searchValue."%' )";
                    }
                    //echo $whereQueryWithSearch;
                    $base_url = base_url();
                    $selectStatement = "SELECT
                                        ROW_NUMBER () OVER (ORDER BY ".$columnName.") AS s_no,
                                        n.apply_id,n.notice_no,cast(n.created_on as date) as verify_date,
                                            cast(dc.created_on as date) as apply_date,
                                            l.application_no,
                                            case when n.apply_id isnull then 'not apply'::text else l.apply_date::text end as licence_apply_date,	
                                            case when n.apply_id isnull then dc.firm_name else l.firm_name end as firm_name,
                                            case when n.apply_id isnull then dc.address else l.address end as address,
                                            case when n.apply_id isnull then dc.applicant_name else ow.owner_name end as owner_name,
                                            case when n.apply_id isnull then null else ow.guardian_name end as guardian_name,
                                            case when n.apply_id isnull then dc.mob_no::text else ow.mobile end as mobile,
                                            case when n.apply_id isnull then wl.ward_no else w.ward_no end as ward_no,
                                            CONCAT('<a href=', chr(39), '".$base_url."/Trade_report/view_notice/', MD5(n.id::TEXT), chr(39), ' target=', chr(39), 'blank', chr(39), '><b><u>','view' , '</u></b></a>') AS link,
                                            dc.ward_id,l.ward_mstr_id ";
                    $select_count ="select 
                                    count(n.id) as total, 
                                    count(case when n.apply_id isnull then n.id else null end) as not_apply,
                                    count(case when n.apply_id notnull then n.id else null end) as apply ";
                   
                    $with_query =" with owner as(
                                        select distinct (apply_licence_id) as apply_licence_id,
                                            string_agg(owner_name::text,' ,'::text) as owner_name,
                                            string_agg(guardian_name::text,' ,'::text) as guardian_name,
                                            string_agg(mobile::text,' ,'::text) as mobile
                                        from tbl_firm_owner_name
                                        group by apply_licence_id
                                    )";
                    $sql ="from tbl_denial_notice n
                            join tbl_denial_consumer_dtl dc on dc.id=n.denial_id
                            left join tbl_apply_licence l on l.id = n.apply_id
                            left join view_ward_mstr w on w.id = l.ward_mstr_id
                            left join owner ow on ow.apply_licence_id = l.id 
                            left join view_ward_mstr wl on wl.id = dc.ward_id 
                            where                  
                            ".$whereQuery;
                    
                    //$totalAmount = $this->model_datatable->getSumAmount($sql);
                    //print_var($sql);
                    $totalRecords = $this->model_datatable->getTotalRecords($sql,false,$with_query);
                    //print_var($totalRecords);die;
                    //print_var($columnName);
                    $total_collection = 0;
                    if ($totalRecords>0) 
                    {
                        
                        ## Total number of records with filtering
                        $totalRecordwithFilter = $this->model_datatable->getTotalRecordwithFilter($sql.$whereQueryWithSearch,false,$with_query);
                        ## Fetch records
                        //$fetchSql = $selectStatement.$sql.$whereQueryWithSearch.$orderBY.$limit;
                        $fetchSql = $with_query.$selectStatement.$sql.$whereQueryWithSearch.$orderBY.$limit;
                        //print_var($fetchSql);
                        //$fetchSumSql = $selectSumStatement.$sql;
                        $result = $this->model_datatable->getRecords($fetchSql,false);
                        $sql_count = $with_query.$select_count.$sql;
                        $coun_record = $this->model_datatable->getRecords($sql_count,false);
                        //$total_collection = $this->model_datatable->getSumRecords($fetchSumSql)['total_payable_amt'];
                        //print_var($result);
                        $records = [];
                        if ($result) 
                        {
                            foreach ($result AS $key=>$tran_dtl) 
                            {
                                $records[] = [
                                    's_no'=>$tran_dtl['s_no'],
                                    'application_no'=>$tran_dtl['application_no'],
                                    'notice_no'=>$tran_dtl['notice_no'],
                                    'firm_name'=>$tran_dtl['firm_name'],
                                    'address'=>$tran_dtl['address'],
                                    'ward_no'=>$tran_dtl['ward_no'],
                                    'mobile'=>$tran_dtl['mobile'],
                                    'verify_date'=>$tran_dtl['verify_date'],
                                    'apply_date'=>$tran_dtl['apply_date'],
                                    'licence_apply_date'=>$tran_dtl['licence_apply_date'],
                                    'link'=>$tran_dtl['link'],
                                    
                                ];
                            }
                        }
                    } 
                    else 
                    {
                        $totalRecordwithFilter = 0;
                        $records = [];
                    }
    
                    $response = array(
                        "draw" => 0,
                        "total_collection" => $total_collection,
                        "recordsTotal" => $totalRecords,                 
                        "recordsFiltered" => $totalRecordwithFilter,
                        "data" => $records,
                        "apply"=>$coun_record[0]['apply'] ?? 0,
                        "not_apply"=>$coun_record[0]['not_apply'] ?? 0,

                    );
                    return json_encode($response);                  
                    
                    
                }
                catch(Exception $e)
                {
                    echo"catch";
                    print_r($e);
                }
        }
        return view('Trade/Reports/total_notice_apply',$data);
    }

    public function entry_detail_report()
    {
        $data=[];
        $data['ward']=array();
        $Session = Session();
        $data['report']=array();
        $from_date = date('Y-m-d');
        $to_date = date('Y-m-d');
        $ward_id='';
        $entry_type='';
        $oprator_id='';
       //print_var($this->ulb_id);
        $ward_sql = " select * from view_ward_mstr where status = 1 and ulb_mstr_id = ? "; 
        $data['ward']=$this->model_view_trade_licence->row_query($ward_sql,array($this->ulb_id));
        $operator_sql=" select * from view_emp_details where user_type_id in (8,5,7) order by user_type";
        $data['oprator']=$this->model_view_trade_licence->row_query($operator_sql,array());
        $and_where = " ";
        if (isset($_GET['page']) && $_GET['page']== 'clr')
        {
            $Session->remove('tempData'); //echo('remove');
        }
        if(strtoupper($this->request->getMethod())==strtoupper("post"))
        {
            $inputs = filterSanitizeStringtoUpper($this->request->getVar());            
            $Session->set('tempData', $inputs);
            //print_var($_POST);
           
        }
        $tempData=$Session->get('tempData');
        if(!empty($tempData))
        {   
            $from_date=$tempData['from_date'];
            $to_date = $tempData['to_date'];
            $ward_id = $tempData['ward_id'];
            if($tempData['ward_id'] !='')
                $and_where.=" and w.id = $tempData[ward_id]";
            $entry_type=$tempData['entry_type'];
            $oprator_id=$tempData['oprator_id'];
            if($entry_type!='' && $entry_type=1)
                $and_where.=" and l.sspl_id isnull ";
            elseif($entry_type!='' && $entry_type=2)
                $and_where.=" and l.sspl_id notnull ";
            
            if($oprator_id!='')
                $and_where.=" and op.id =$oprator_id ";
        }
        $report_sql="   with operator as (
                            select * from view_emp_details where user_type_id in(8,5,7)
                        )
                        select l.sspl_id,l.id,l.application_no,l.address,l.apply_date,l.firm_name,
                            w.ward_no,
                            apt.application_type,
                            op.emp_name
                        from tbl_apply_licence l 
                        join operator op on op.id = l.emp_details_id
                        join view_ward_mstr w on w.id = l.ward_mstr_id
                        join tbl_application_type_mstr apt on apt.id = l.application_type_id    
                        where cast(l.apply_date as date) >= '$from_date' and cast(l.apply_date as date) <= '$to_date' $and_where 
                        order by cast(l.apply_date as date) ";
        $result=$this->model_view_trade_licence->row_query2($report_sql);
        $data['report']=$result['result'];
        $data['count']=$result['count'];
        $data['offset']=$result['offset'];
        $data['from_date']=$from_date;
        $data['to_date']=$to_date;
        $data['ward_id']=$ward_id ;
        $data['entry_type']=$entry_type ;
        $data['oprator_id']=$oprator_id ;
        $data['ulb_dtl']=$Session->get('ulb_dtl');
        return view("Trade/Reports/entry_detail_report", $data);

    }

    public function entry_detail_reportAjax_copy()
    {
        if($this->request->getMethod()=='post')
        {
             $inputs = filterSanitizeStringtoUpper($_POST);
             $and_where=' ';
            
            try{
                    $from_date=date('Y-m-d',strtotime($inputs['form_date']));
                    $to_date = date('Y-m-d',strtotime($inputs['to_date']));
                    $ward_id = $inputs['ward_id'];
                    if($inputs['ward_id'] !='')
                        $and_where.=" and w.id = $inputs[ward_id]";
                    $entry_type=$inputs['entry_type'];
                    $oprator_id=$inputs['oprator_id'];
                    if($entry_type!='' && $entry_type=1)
                        $and_where.=" and l.sspl_id isnull ";
                    elseif($entry_type!='' && $entry_type=2)
                        $and_where.=" and l.sspl_id notnull ";
                    
                    if($oprator_id!='')
                        $and_where.=" and op.id =$oprator_id ";

                    $sql="      with operator as (
                                    select * from view_emp_details where user_type_id in(8,5,7)
                                )
                                select l.application_no as ".'"Application No"'.",
                                    l.address as ".'"Address"'.",
                                    l.apply_date as ".'"Apply Date"'.",
                                    l.firm_name as ".'"Firm Name"'.",
                                    w.ward_no as ".'"Ward No."'.",
                                    apt.application_type as ".'"Application Type"'.",
                                    op.emp_name  as ".'"Operator Name"'."
                                from tbl_apply_licence l 
                                join operator op on op.id = l.emp_details_id
                                join view_ward_mstr w on w.id = l.ward_mstr_id
                                join tbl_application_type_mstr apt on apt.id = l.application_type_id    
                                where cast(l.apply_date as date) >= '$from_date' and cast(l.apply_date as date) <= '$to_date' $and_where 
                                order by cast(l.apply_date as date) ";
                    $data['result']=$this->model_view_trade_licence->row_query($sql,array());
                    //print_var($data);
                    exporttoexcel($data);
                    //  die;
                    //echo"hear";
                    $response = array(
                        "iTotalRecords" => 1,
                        "iTotalDisplayRecords" => 1,
                        "aaData" => 1
                    );
                    return json_encode($response);
                }
                catch(Exception $e)
                {
                    //echo"<script>alert()</script>";
                   
                }
        }
    }

   

    public function entry_detail_reportAjax()
    {
        //if($this->request->getMethod()=='POST')
        if($_POST)
        {
			try
            {
                ## Read value
                $start = sanitizeString($this->request->getVar('start'));
                
                
                $rowperpage = sanitizeString($this->request->getVar('length')); // Rows display per page

                $columnIndex = sanitizeString($this->request->getVar('order')[0]['column']); // Column index
                $columnName = sanitizeString($this->request->getVar('columns')[$columnIndex]['data']); // Column name
                if ($columnName=="emp_name")
                    $columnName = 'op.emp_name';
                else if ($columnName=="apply_date")
                    $columnName = 'cast(l.apply_date as date)';
                else if ($columnName=="ward_no")
                    $columnName = 'w.ward_no';
                else if ($columnName=="application_type")
                    $columnName = 'apt.application_type';
                else
                    $columnName = 'cast(l.apply_date as date)';
                

                //$columnName = "tbl_transaction.tran_date";
                $columnSortOrder = sanitizeString($this->request->getVar('order')[0]['dir']); // asc or desc
                $searchValue = sanitizeString($this->request->getVar('search')['value']); // Search value
                
                
                
                // Date filter
                $search_from_date = sanitizeString($this->request->getVar('search_from_date'));
                $search_upto_date = sanitizeString($this->request->getVar('search_upto_date'));
                $search_ward_mstr_id = sanitizeString($this->request->getVar('search_ward_mstr_id'));
                $search_collector_id = sanitizeString($this->request->getVar('search_collector_id'));
                $search_entry_type = sanitizeString($this->request->getVar('search_entry_type'));
                $searchQuery = "";
                $whereQuery = "";
                
                $orderBY = " ORDER BY ".$columnName." ".$columnSortOrder;
                $limit = " LIMIT ".$rowperpage." OFFSET ".$start;
                
                $whereQuery .= "  cast(l.apply_date as date) BETWEEN '".$search_from_date."' AND '".$search_upto_date."'";
                if ($search_ward_mstr_id != '') 
                {
                    $whereQuery .= " AND  l.ward_mstr_id='".$search_ward_mstr_id."'";
                }
                if ($search_collector_id != '') 
                {
                    $whereQuery .= " AND  op.id='".$search_collector_id."'";
                }
                if($search_entry_type!='')
                {
                    $whereQuery .= " AND  l.sspl_id ".($search_entry_type=='1' ? " isnull" :($search_entry_type=='2' ? "notnull":''));
                }
                $whereQueryWithSearch = "";
                if ($searchValue!='') 
                {
                    $whereQueryWithSearch = " AND (l.application_no ILIKE '%".$searchValue."%'
                                    OR l.firm_name ILIKE '%".$searchValue."%'
                                    OR w.ward_no ILIKE '%".$searchValue."%'
                                    OR op.emp_name ILIKE'%".$searchValue."%' )";
                }

                $base_url = base_url();
                $selectStatement = "SELECT
                                    ROW_NUMBER () OVER (ORDER BY ".$columnName.") AS s_no,
                                    l.sspl_id,
                                    l.id,
                                    l.application_no,
                                    l.address,
                                    l.apply_date,
                                    l.firm_name,
                                    w.ward_no,
                                    apt.application_type,
                                    op.emp_name";

                // $sql = " FROM tbl_transaction
                // INNER JOIN tbl_prop_dtl ON tbl_prop_dtl.id=tbl_transaction.prop_dtl_id
                // INNER JOIN (SELECT STRING_AGG(owner_name, ', ') AS owner_name, prop_dtl_id FROM tbl_prop_owner_detail GROUP BY prop_dtl_id) prop_owner_detail ON prop_owner_detail.prop_dtl_id=tbl_transaction.prop_dtl_id
                // INNER JOIN view_ward_mstr ON view_ward_mstr.id=tbl_prop_dtl.ward_mstr_id
                // WHERE tbl_transaction.tran_type='Property'".$whereQuery;

                $with = " with operator as (
                                select * from view_emp_details where user_type_id in(8,5,7)
                            ) ";

                $sql =" from tbl_apply_licence l 
                        join operator op on op.id = l.emp_details_id
                        join view_ward_mstr w on w.id = l.ward_mstr_id
                        join tbl_application_type_mstr apt on apt.id = l.application_type_id    
                        where                 
                        ".$whereQuery;
                
                //$totalAmount = $this->model_datatable->getSumAmount($sql);
                $totalRecords = $this->model_datatable->getTotalRecords($sql,false,$with);
                //print_var($totalRecords);
                //print_var($columnName);
                $total_collection = 0;
                if ($totalRecords>0) 
                {
                    
                    ## Total number of records with filtering
                    $totalRecordwithFilter = $this->model_datatable->getTotalRecordwithFilter($sql.$whereQueryWithSearch,false,$with);
                    ## Fetch records
                    //$fetchSql = $selectStatement.$sql.$whereQueryWithSearch.$orderBY.$limit;
                    $fetchSql = $with.$selectStatement.$sql.$whereQueryWithSearch.$orderBY.$limit;
                    //$fetchSumSql = $selectSumStatement.$sql;
                    $result = $this->model_datatable->getRecords($fetchSql,false);
                    //$total_collection = $this->model_datatable->getSumRecords($fetchSumSql)['total_payable_amt'];
                    
                    $records = [];
                    if ($result) 
                    {
                        foreach ($result AS $key=>$tran_dtl) 
                        {
                            $records[] = [
                                's_no'=>$tran_dtl['s_no'],
                                'emp_name'=>$tran_dtl['emp_name'],
                                'apply_date'=>$tran_dtl['apply_date'],
                                'application_type'=>$tran_dtl['application_type'],
								'application_no'=>$tran_dtl['application_no'],
                                'firm_name'=>$tran_dtl['firm_name'],
                                'ward_no'=>$tran_dtl['ward_no'],
                                'address'=>$tran_dtl['address'],
                                
                            ];
                        }
                    }
                } 
                else 
                {
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
                
                
                
            }
            catch(Exception $e)
            {
                echo"catch";
                print_r($e);
            }
        } 
        else 
        {
            echo "asdasd";
            print_var($_POST);
            //print_var($_GET);
        }
    }


    public function entry_detail_reportExcel($search_from_date = null, $search_upto_date = null, $search_ward_mstr_id = null, $search_collector_id = null,$entry_type=null)
    {
        try{
            $whereQuery = "  cast(l.apply_date as date) BETWEEN '".$search_from_date."' AND '".$search_upto_date."'";
            
                if ($search_ward_mstr_id != 'ALL') 
                {
                    $whereQuery .= " AND  l.ward_mstr_id='".$search_ward_mstr_id."'";
                }
                if ($search_collector_id != 'ALL') 
                {
                    $whereQuery .= " AND  opt.id='".$search_collector_id."'";
                }
                if ($entry_type != 'ALL') 
                {
                    $whereQuery .= " AND  l.sspl_id ".$entry_type;
                }

                $selectStatement = "SELECT
                                    l.sspl_id,
                                    l.id,
                                    l.application_no::text,
                                    l.address,
                                    l.apply_date,
                                    l.firm_name,
                                    w.ward_no,
                                    apt.application_type,
                                    op.emp_name 
                                    ";
                   
                $with = " with operator as (
                                select * from view_emp_details where user_type_id in(8,5,7)
                            ) ";


                $sql =" from tbl_apply_licence l 
                        join operator op on op.id = l.emp_details_id
                        join view_ward_mstr w on w.id = l.ward_mstr_id
                        join tbl_application_type_mstr apt on apt.id = l.application_type_id    
                        where                 
                        ".$whereQuery;

            $fetchSql = $with.$selectStatement.$sql;
            $result = $this->model_datatable->getRecords($fetchSql,false);
            $records = [];
            if ($result) 
            {
                foreach ($result AS $key=>$tran_dtl) 
                {
                    

                    $records[] = [
                        // 's_no'=>$tran_dtl['s_no'],
                        'emp_name'=>$tran_dtl['emp_name'],
                        'apply_date'=>$tran_dtl['apply_date'],
                        'application_type'=>$tran_dtl['application_type'],
                        'application_no'=>$tran_dtl['application_no'],
                        'firm_name'=>$tran_dtl['firm_name'],
                        'ward_no'=>$tran_dtl['ward_no'],
                        'address'=>$tran_dtl['address'],
                        
                    ];

                }
            }
            phpOfficeLoad();
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $activeSheet = $spreadsheet->getActiveSheet();
                            $activeSheet->setCellValue('A1', 'Operator Name');
                            $activeSheet->setCellValue('B1', 'Date');
                            $activeSheet->setCellValue('C1', 'Application Type');
                            $activeSheet->setCellValue('D1', 'Application No');
                            $activeSheet->setCellValue('E1', 'Firm Name');
                            $activeSheet->setCellValue('F1', 'Ward No.');
                            $activeSheet->setCellValue('G1', 'Address');


                            $activeSheet->fromArray($records, NULL, 'A2');
            $filename = "counter_report_".date('Ymd-hisa').".xlsx";
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="'.$filename.'"');
            header('Cache-Control: max-age=0');
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            //$writer->save(APPPATH.'/hello world.xlsx');
            $writer->save('php://output');
        }
        catch(Exception $e)
        {
            print_r($e);
        }
    }

    public function arrearAndCurrentCollectionSummeryAjax() 
    {
        if($this->request->getMethod()=='post')
        {
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
                if ($search_ward_mstr_id != '') 
                {
                    $whereQuery .= " AND  tbl_prop_dtl.ward_mstr_id='".$search_ward_mstr_id."'";
                }
                if ($search_collector_id != '') 
                {
                    $whereQuery .= " AND  tbl_transaction.tran_by_emp_details_id='".$search_collector_id."'";
                }
                $whereQueryWithSearch = "";
                if ($searchValue!='') 
                {
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
                if ($totalRecords>0) 
                {
                    ## Total number of records with filtering
                    $totalRecordwithFilter = $this->model_datatable->getTotalRecordwithFilter($sql.$whereQueryWithSearch);
                    ## Fetch records
                    $fetchSql = $selectStatement.$sql.$whereQueryWithSearch.$orderBY.$limit;
                    $fetchSumSql = $selectSumStatement.$sql;
                    $tran_dtl_list = $this->model_datatable->getRecords($fetchSql);
                    $total_collection = $this->model_datatable->getSumRecords($fetchSumSql)['total_payable_amt'];
                    
                    $records = [];
                    if ($tran_dtl_list) 
                    {
                        $currentFY = getFY();
                        $currentFyID = $this->model_fy_mstr->getFyByFy(['fy'=>$currentFY])['id'];
                        foreach ($tran_dtl_list AS $key=>$tran_dtl) 
                        {
                            $sql = "SELECT string_agg(tbl_collection.qtr::text || ' / ' || view_fy_mstr.fy::text, ',' ORDER BY fy_mstr_id,qtr ASC) AS a_period, COALESCE(SUM(amount), 0) AS a_total_amt FROM tbl_collection INNER JOIN view_fy_mstr ON view_fy_mstr.id=tbl_collection.fy_mstr_id WHERE tbl_collection.fy_mstr_id<".$currentFyID." AND tbl_collection.transaction_id=".$tran_dtl['transaction_id'].";";
                            $builder = $this->db->query($sql);
                            if ($a_fy_mstr_qtr_dtl = $builder->getResultArray()[0]) 
                            {
                                if(!is_null($a_fy_mstr_qtr_dtl['a_period'])) 
                                {
                                    $c_period_arr = (explode(",",$a_fy_mstr_qtr_dtl['a_period']));
                                    $c_period = current($c_period_arr)." TO ".end($c_period_arr);
                                    $tran_dtl_list[$key]['arrear_period']=$c_period;
                                } 
                                else 
                                {
                                    $tran_dtl_list[$key]['arrear_period']="N/A";
                                }
                                $tran_dtl_list[$key]['arrear_collection']=$a_fy_mstr_qtr_dtl['a_total_amt'];
                            }
                            $sql = "SELECT string_agg(tbl_collection.qtr::text || ' / ' || view_fy_mstr.fy::text, ',' ORDER BY fy_mstr_id,qtr ASC) AS c_period, COALESCE(SUM(amount), 0) AS c_total_amt FROM tbl_collection INNER JOIN view_fy_mstr ON view_fy_mstr.id=tbl_collection.fy_mstr_id WHERE tbl_collection.fy_mstr_id=".$currentFyID." AND tbl_collection.transaction_id=".$tran_dtl['transaction_id'].";";
                            $builder = $this->db->query($sql);
                            if ($c_fy_mstr_qtr_dtl = $builder->getResultArray()[0]) 
                            {
                                if(!is_null($c_fy_mstr_qtr_dtl['c_period'])) 
                                {
                                    $c_period_arr = (explode(",",$c_fy_mstr_qtr_dtl['c_period']));
                                    $c_period = current($c_period_arr)." TO ".end($c_period_arr);
                                    $tran_dtl_list[$key]['current_period']=$c_period;
                                } 
                                else 
                                {
                                    $tran_dtl_list[$key]['current_period']="N/A";
                                }
                                $tran_dtl_list[$key]['current_collection']=$c_fy_mstr_qtr_dtl['c_total_amt'];
                            }
                        }
                        foreach ($tran_dtl_list AS $key=>$tran_dtl) 
                        {
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
                } 
                else 
                {
                    $totalRecordwithFilter = 0;
                    $records = [];
                }

                $response = array(
                    "draw" => 0,
                    "total_collection" => $total_collection,
                    "recordsTotal" => $totalRecords,                 
                    "recordsFiltered" => $totalRecordwithFilter,
                    "data" => $records
                );//print_var($_POST);
                return json_encode($response);
                
                
                
            }
            catch(Exception $e)
            {
                print_r($e);
            }
        } 
        else 
        {
            echo "asdasd";
        }
    }

    public function arrearAndCurrentCollectionSummeryExcel($search_from_date = null, $search_upto_date = null, $search_ward_mstr_id = null, $search_collector_id = null) 
    {
        try{
            $whereQuery = " AND tbl_transaction.tran_date BETWEEN '".$search_from_date."' AND '".$search_upto_date."'";
                if ($search_ward_mstr_id != 'ALL') 
                {
                    $whereQuery .= " AND  tbl_prop_dtl.ward_mstr_id='".$search_ward_mstr_id."'";
                }
                if ($search_collector_id != 'ALL') 
                {
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
            if ($tran_dtl_list) 
            {
                $currentFY = getFY();
                $currentFyID = $this->model_fy_mstr->getFyByFy(['fy'=>$currentFY])['id'];
                foreach ($tran_dtl_list AS $key=>$tran_dtl) {
                    $sql = "SELECT string_agg(tbl_collection.qtr::text || ' / ' || view_fy_mstr.fy::text, ',' ORDER BY fy_mstr_id,qtr ASC) AS a_period, COALESCE(SUM(amount), 0) AS a_total_amt FROM tbl_collection INNER JOIN view_fy_mstr ON view_fy_mstr.id=tbl_collection.fy_mstr_id WHERE tbl_collection.fy_mstr_id<".$currentFyID." AND tbl_collection.transaction_id=".$tran_dtl['transaction_id'].";";
                    $builder = $this->db->query($sql);
                    if ($a_fy_mstr_qtr_dtl = $builder->getResultArray()[0]) 
                    {
                        if(!is_null($a_fy_mstr_qtr_dtl['a_period'])) 
                        {
                            $c_period_arr = (explode(",",$a_fy_mstr_qtr_dtl['a_period']));
                            $c_period = current($c_period_arr)." TO ".end($c_period_arr);
                            $tran_dtl_list[$key]['arrear_period']=$c_period;
                        } 
                        else 
                        {
                            $tran_dtl_list[$key]['arrear_period']="N/A";
                        }
                        $tran_dtl_list[$key]['arrear_collection']=$a_fy_mstr_qtr_dtl['a_total_amt'];
                    }
                    $sql = "SELECT string_agg(tbl_collection.qtr::text || ' / ' || view_fy_mstr.fy::text, ',' ORDER BY fy_mstr_id,qtr ASC) AS c_period, COALESCE(SUM(amount), 0) AS c_total_amt FROM tbl_collection INNER JOIN view_fy_mstr ON view_fy_mstr.id=tbl_collection.fy_mstr_id WHERE tbl_collection.fy_mstr_id=".$currentFyID." AND tbl_collection.transaction_id=".$tran_dtl['transaction_id'].";";
                    $builder = $this->db->query($sql);
                    if ($c_fy_mstr_qtr_dtl = $builder->getResultArray()[0]) 
                    {
                        if(!is_null($c_fy_mstr_qtr_dtl['c_period'])) 
                        {
                            $c_period_arr = (explode(",",$c_fy_mstr_qtr_dtl['c_period']));
                            $c_period = current($c_period_arr)." TO ".end($c_period_arr);
                            $tran_dtl_list[$key]['current_period']=$c_period;
                        } 
                        else 
                        {
                            $tran_dtl_list[$key]['current_period']="N/A";
                        }
                        $tran_dtl_list[$key]['current_collection']=$c_fy_mstr_qtr_dtl['c_total_amt'];
                    }
                }
                foreach ($tran_dtl_list AS $key=>$tran_dtl) 
                {
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
            phpOfficeLoad();
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
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
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            //$writer->save(APPPATH.'/hello world.xlsx');
            $writer->save('php://output');
        }
        catch(Exception $e)
        {
            print_r($e);
        }
    }


    public function ward_wise_ricipt_print()
    {   
        $data=array();
        $Session = session();
        $data['report']=array();
        $from_date = date('Y-m-d');
        $to_date = date('Y-m-d');
        $ward_sql = " select * from view_ward_mstr where status = 1 and ulb_mstr_id = ? "; 
        $data['ward']=$this->model_view_trade_licence->row_query($ward_sql,array($this->ulb_id));
        $data['ulb_dtl']=$Session->get('ulb_dtl');
        //print_var($data['ulb_dtl']);
        $ulb_mstr_id=$data['ulb_dtl']['ulb_mstr_id'];
        $data['ulb_id']=$this->ulb_id;
        if($this->request->getMethod()=='post')
        {
            $inputs = filterSanitizeStringtoUpper($this->request->getVar());            
            $from_date=$inputs['from_date'];
            $to_date = $inputs['to_date'];
            $data['from_date']=$from_date;
            $data['to_date']= $to_date;
            $ward_id = $inputs['ward_id'];
            $data['ward_id']=$ward_id;
            $and_where = ' ';
            if($ward_id!='')
            {
                $and_where = " and w.id = $ward_id ";
            }
            $sql_result= " select l.id as apply_id,
                                l.application_no,
                                l.address,
                                l.applicant_name,
                                l.father_name,
                                l.mobile_no,
                                w.ward_no,
                                t.id as transaction_id,
                                t.transaction_no,
                                t.transaction_date,
                                t.payment_mode,
                                t.paid_amount,
                                t.penalty,
                                ch.cheque_no, 
                                ch.cheque_date,
                                ch.bank_name,
                                ch.branch_name
                            from view_apply_licence_owner l 
                            join tbl_transaction t on t.related_id = l.id
                            join view_ward_mstr w on w.id = l.ward_mstr_id
                            left join tbl_cheque_dtl ch on ch.transaction_id = t.id
                            where  l.status=1 and t.status = 1
                                and t.transaction_date between '$from_date' and '$to_date'
                                $and_where
                        ";

                        // echo $sql_result;
            $data['records']=$this->model_view_trade_licence->row_query($sql_result);
            foreach ($data['records'] as $key=>$val)
            {
                $transaction_idmd5 = md5($val['transaction_id']);
                $applyidmd5 = md5($val['apply_id']);
                $delayApplyLicence='';
                $denialApply='';
                $data['rebet'] = $this->TradeTransactionModel->getRebetDetails($transaction_idmd5);
                //print_var($data['rebet']);
                if(sizeof($data['rebet'])>0)
                {
                    $delayApplyLicence = $data['rebet']['0']['amount']; 
                    $denialApply = isset($data['rebet']['1']['amount']) ? $data['rebet']['1']['amount'] : null;
                }
                $data['records'][$key]['delayApplyLicence'] =  $delayApplyLicence;
                $data['records'][$key]['denialApply'] = $denialApply;
                $data['records'][$key]['qur_path']=base_url('citizenPaymentReceipt/view_trade_transaction_receipt/'.$ulb_mstr_id.'/'.$applyidmd5.'/'.$transaction_idmd5);
                $data['records'][$key]['ss']=qrCodeGeneratorFun($data['records'][$key]['qur_path']);
            }
            // print_var($sql_result);
            // print_var($data['records']);
            // die;
        }
        return view("Trade/Reports/ward_wise_ricipt_print",$data);
    }

    public function area_sqt_ft_licence_dtl()
    {
        //echo "code hear";
        $data=[];
        $Session = session();
        $data['ulb_dtl']=$Session->get('ulb_dtl');
        $data['from_date']=date('Y-M-D');
        $data['to_date']=date('Y-M-D');
        $ward_sql = " select * from view_ward_mstr where status = 1 and ulb_mstr_id = ? "; 
        $data['ward']=$this->model_view_trade_licence->row_query($ward_sql,array($this->ulb_id));

        if($_POST)
        {
           
            // $sql=" select l.id as licence_id,
            //             l.prop_dtl_id,
            //             l.license_no,
            //             l.area_in_sqft,
            //             coalesce(l.area_in_sqft * 10.7639,0)as area_in_sqmt,
            //             l.holding_no,
            //             w.ward_no,
            //             vp.id as prop_id,
            //             vp.area_of_plot,
            //             vp.new_holding_no
            //         from tbl_apply_licence l 
            //         left join view_prop_detail vp on vp.id = l.prop_dtl_id
            //         join view_ward_mstr w on w.id = l.ward_mstr_id 
            //         where l.pending_status=5 
            //     ";

                try
                {
                    ## Read value
                    $start = sanitizeString($this->request->getVar('start'));
                    
                    
                    $rowperpage = sanitizeString($this->request->getVar('length')); // Rows display per page
    
                    $columnIndex = sanitizeString($this->request->getVar('order')[0]['column']); // Column index
                    $columnName = sanitizeString($this->request->getVar('columns')[$columnIndex]['data']); // Column name
                    if ($columnName=="license_no")
                        $columnName = 'l.license_no';
                    else if ($columnName=="area_in_sqft")
                        $columnName = 'l.area_in_sqft';
                    else if ($columnName=="area_in_sqmt")
                        $columnName = 'ROUND(coalesce(l.area_in_sqft * 10.7639,0),2)';
                    else if ($columnName=="holding_no")
                        $columnName = 'l.holding_no';
                    else if ($columnName=="ward_no")
                        $columnName = 'w.ward_no';
                    else if ($columnName=="area_of_plot")
                        $columnName = 'vp.area_of_plot';
                    // else if ($columnName=="holding_no")
                    //     $columnName = 'l.holding_no';
                    else
                        $columnName = 'cast(l.apply_date as date)';
                    
    
                    //$columnName = "tbl_transaction.tran_date";
                    $columnSortOrder = sanitizeString($this->request->getVar('order')[0]['dir']); // asc or desc
                    $searchValue = sanitizeString($this->request->getVar('search')['value']); // Search value
                    
                    
                    
                    // Date filter
                    $search_from_date = sanitizeString($this->request->getVar('search_from_date'));
                    $search_upto_date = sanitizeString($this->request->getVar('search_upto_date'));
                    $search_ward_mstr_id = sanitizeString($this->request->getVar('search_ward_mstr_id'));
                    // $search_collector_id = sanitizeString($this->request->getVar('search_collector_id'));
                    // $search_entry_type = sanitizeString($this->request->getVar('search_entry_type'));
                    $searchQuery = "";
                    $whereQuery = "";
                    
                    $orderBY = " ORDER BY ".$columnName." ".$columnSortOrder;
                    $limit = " LIMIT ".$rowperpage." OFFSET ".$start;
                    
                    $whereQuery .= "  l.pending_status = 5 and cast(l.apply_date as date) BETWEEN '".$search_from_date."' AND '".$search_upto_date."'";
                    if ($search_ward_mstr_id != '') 
                    {
                        $whereQuery .= " AND  l.ward_mstr_id='".$search_ward_mstr_id."'";
                    }
                   
                    $whereQueryWithSearch = "";
                    if ($searchValue!='') 
                    {
                        $whereQueryWithSearch = " AND (l.holding_no ILIKE '%".$searchValue."%'
                                        OR cast(vp.area_of_plot as varchar) ILIKE '%".$searchValue."%'
                                        OR w.ward_no ILIKE '%".$searchValue."%'
                                        OR cast(l.area_in_sqft as varchar) ILIKE '%".$searchValue."%'
                                        OR l.license_no ILIKE'%".$searchValue."%' )";
                    }
                    //echo $whereQueryWithSearch;
                    $base_url = base_url();
                    $selectStatement = "SELECT
                                        ROW_NUMBER () OVER (ORDER BY ".$columnName.") AS s_no,
                                        l.id as licence_id,
                                        l.prop_dtl_id,
                                        l.license_no,
                                        l.area_in_sqft,
                                        ROUND(coalesce(l.area_in_sqft / 10.7639,0),2)as area_in_sqmt,
                                        l.holding_no,
                                        w.ward_no,
                                        vp.id as prop_id,
                                        vp.area_of_plot,
                                        vp.new_holding_no";
                   
    
                    $sql =" from tbl_apply_licence l 
                            left join view_prop_detail vp on vp.id = l.prop_dtl_id
                            join view_ward_mstr w on w.id = l.ward_mstr_id 
                            where                  
                            ".$whereQuery;
                    
                    //$totalAmount = $this->model_datatable->getSumAmount($sql);
                    $totalRecords = $this->model_datatable->getTotalRecords($sql,false);
                    //print_var($totalRecords);
                    //print_var($columnName);
                    $total_collection = 0;
                    if ($totalRecords>0) 
                    {
                        
                        ## Total number of records with filtering
                        $totalRecordwithFilter = $this->model_datatable->getTotalRecordwithFilter($sql.$whereQueryWithSearch,false);
                        ## Fetch records
                        //$fetchSql = $selectStatement.$sql.$whereQueryWithSearch.$orderBY.$limit;
                        $fetchSql = $selectStatement.$sql.$whereQueryWithSearch.$orderBY.$limit;
                        //$fetchSumSql = $selectSumStatement.$sql;
                        $result = $this->model_datatable->getRecords($fetchSql,false);
                        //$total_collection = $this->model_datatable->getSumRecords($fetchSumSql)['total_payable_amt'];
                        
                        $records = [];
                        if ($result) 
                        {
                            foreach ($result AS $key=>$tran_dtl) 
                            {
                                $records[] = [
                                    's_no'=>$tran_dtl['s_no'],
                                    'holding_no'=>$tran_dtl['holding_no'],
                                    'area_of_plot'=>$tran_dtl['area_of_plot'],
                                    // 'area_of_plot'=>$tran_dtl['area_of_plot'],
                                    'ward_no'=>$tran_dtl['ward_no'],
                                    'license_no'=>$tran_dtl['license_no'],
                                    'area_in_sqft'=>$tran_dtl['area_in_sqft'],
                                    'area_in_sqmt'=>$tran_dtl['area_in_sqmt'],
                                    
                                ];
                            }
                        }
                    } 
                    else 
                    {
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
                    
                    
                }
                catch(Exception $e)
                {
                    echo"catch";
                    print_r($e);
                }
        }

        return view('Trade/Reports/area_sqt_ft_licence_dtl',$data);
    }
    
    public function area_sqt_ft_licence_dtlExcel($search_from_date = null, $search_upto_date = null, $search_ward_mstr_id = null)
    {
        try{
            $whereQuery = " l.pending_status =5 and cast(l.apply_date as date) BETWEEN '".$search_from_date."' AND '".$search_upto_date."'";
            
                if ($search_ward_mstr_id != 'ALL') 
                {
                    $whereQuery .= " AND  l.ward_mstr_id='".$search_ward_mstr_id."'";
                }
                

                $selectStatement = "SELECT
                                    l.id as licence_id,
                                    l.prop_dtl_id,
                                    l.license_no,
                                    l.area_in_sqft,
                                    ROUND(coalesce(l.area_in_sqft / 10.7639,0),2)as area_in_sqmt,
                                    l.holding_no,
                                    w.ward_no,
                                    vp.id as prop_id,
                                    vp.area_of_plot,
                                    vp.new_holding_no 
                                    ";
                

                $sql =" from tbl_apply_licence l 
                        left join view_prop_detail vp on vp.id = l.prop_dtl_id
                        join view_ward_mstr w on w.id = l.ward_mstr_id 
                        where                 
                        ".$whereQuery;

            $fetchSql = $selectStatement.$sql;
            
            $result = $this->model_datatable->getRecords($fetchSql,false);
            $records = [];
            if ($result) 
            {
                foreach ($result AS $key=>$tran_dtl) 
                {
                    

                    $records[] = [
                        // 's_no'=>$tran_dtl['s_no'],
                        'holding_no'=>$tran_dtl['holding_no'],
                        'area_of_plot'=>$tran_dtl['area_of_plot'],
                        'ward_no'=>$tran_dtl['ward_no'],
                        'license_no'=>$tran_dtl['license_no'],
                        'area_in_sqft'=>$tran_dtl['area_in_sqft'],
                        'area_in_sqmt'=>$tran_dtl['area_in_sqmt'],
                        // 'address'=>$tran_dtl['address'],
                        
                    ];

                }
            }
            phpOfficeLoad();
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $activeSheet = $spreadsheet->getActiveSheet();
                            $activeSheet->setCellValue('A1', 'Holding No.');
                            $activeSheet->setCellValue('B1', 'Holding Builtup Area (in Decimal)');
                            $activeSheet->setCellValue('C1', 'Ward No.');
                            $activeSheet->setCellValue('D1', 'License No.');
                            $activeSheet->setCellValue('E1', 'Area Sq. Feet');
                            $activeSheet->setCellValue('F1', 'Area Sq. Mtr');
                            // $activeSheet->setCellValue('G1', 'Address');


                            $activeSheet->fromArray($records, NULL, 'A3');
            $filename = "counter_report_".date('Ymd-hisa').".xlsx";
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="'.$filename.'"');
            header('Cache-Control: max-age=0');
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            //$writer->save(APPPATH.'/hello world.xlsx');
            $writer->save('php://output');
        }
        catch(Exception $e)
        {
            print_r($e);
        }
    }   


    public function daily_Collection_Register($menu=null)
    {
        //echo"Daily Collection Register";
        $data=[];      
        if($menu !=md5('clr'))
            $menu=null;  
        $data['from_date']=date('Y-m-d');
        $data['to_date']=date('Y-m-d');
        $ward_sql = " select * from view_ward_mstr where status = 1 and ulb_mstr_id = ? "; 
        $data['ward']=$this->model_view_trade_licence->row_query($ward_sql,array($this->ulb_id));
        $data['ulb_dtl']=session()->get('ulb_dtl');
        $sql_payment_mode =" select distinct(lower(payment_mode)) as payment_mode from tbl_transaction ";
        $data['payment_mode']= $this->model_view_trade_licence->row_query($sql_payment_mode,array($this->ulb_id));
        if($this->request->getMethod()=='post')
        {
            $inputs = filterSanitizeStringtoUpper($this->request->getVar());  
            //print_var($inputs);
            $from_date = $inputs['from_date'];
            $to_date = $inputs['to_date'];
            $ward_id = $inputs['ward_id'];
            $clr_date='';
            if($menu ==md5('clr'))
            {
                $clr_date=' cast(t.verified_on as date) as verified_on,';//echo$clr_date;
                $where= " where cast(t.verified_on as date) between '$from_date'  and ' $to_date'
                        and t.status = 1  and t.verify_status = 1 ";
                $join = "left join view_emp_details emp on emp.id = t.verified_by ";
            } 
            else
            {
                $menu=null;
                $where =" where t.transaction_date between '$from_date'  and ' $to_date'
                            and t.status = 1 ";
                $join = "left join view_emp_details emp on emp.id = t.emp_details_id ";
            }
            
            if($ward_id !='')
            {
                $where.= " and w.id = $ward_id ";
            }
            
            $data['from_date']= $from_date;
            $data['to_date']= $to_date;
            $data['ward_id']= $ward_id;
            $sql =" with owner as (
                        select distinct(apply_licence_id) as apply_licence_id,
                            string_agg(o.owner_name::text,' ,'::text) as owner_name,
                            string_agg(o.guardian_name::text,' ,'::text) as guardian_name,
                            string_agg(o.mobile::text,' ,'::text) as mobile,
                            case when count(o.id)>1 then 'joint' else 'single' end as type
                        from tbl_apply_licence l
                        join tbl_firm_owner_name o on o.apply_licence_id = l.id and o.status=1
                        group by o.apply_licence_id	
                    )
                    select t.id as transaction_id, t.paid_amount,t.penalty, 
                        t.payment_mode, t.transaction_date,t.transaction_no,$clr_date
                        ch.cheque_no,ch.cheque_date,ch.bank_name,
                        l.id as apply_id,l.application_no,l.firm_name,
                        w.ward_no,
                        t.emp_details_id,emp.emp_name,
                        apt.application_type,
                        o.owner_name,o.guardian_name,o.mobile	
                    from tbl_transaction t
                    join tbl_apply_licence l on l.id = t.related_id
                    join tbl_application_type_mstr apt on apt.id = l.application_type_id
                    left join owner o on o.apply_licence_id = l.id 
                    $join
                    join view_ward_mstr w on w.id = l.ward_mstr_id
                    left join tbl_cheque_dtl ch on ch.transaction_id = t.id
                    $where ";    

                    
            $data['records_application'] = $this->model_view_trade_licence->row_query($sql,array());
            //print_var($sql);
            //print_var($data['records_application']);
            $sql_tc_collection = "  select sum( case when upper(t.payment_mode) ='ONLINE' then t.paid_amount else 0 end ) as online ,
                                            sum(case when upper(t.payment_mode) ='ONLINE' then t.penalty else 0 end) as online_p, 
                                            sum( case when upper(t.payment_mode) ='CASH' then t.paid_amount else 0 end) as cash ,
                                            sum(case when upper(t.payment_mode) ='CASH' then t.penalty else 0 end) as cash_p, 
                                            sum( case when upper(t.payment_mode) ='CARD' then t.paid_amount else 0 end ) as card ,
                                            sum(case when upper(t.payment_mode) ='CARD' then t.penalty else 0 end ) as card_p, 
                                            sum( case when upper(t.payment_mode) ='CHEQUE' then t.paid_amount else 0 end ) as cheque ,
                                            sum(case when upper(t.payment_mode) ='CHEQUE' then t.penalty else 0 end) as cheque_p,		
                                            sum( case when upper(t.payment_mode) ='DEMAND DRAFT' then t.paid_amount else 0 end ) as ".'"demand draft"'." ,
                                            sum(case when upper(t.payment_mode) ='DEMAND DRAFT' then t.penalty else 0 end) as ".'"demand draft_p"'." ,
                                            sum( case when upper(t.payment_mode) ='SBI COLLECT' then t.paid_amount else 0 end ) as  ".'"sbi collect"'." ,
                                            sum(case when upper(t.payment_mode) ='SBI COLLECT' then t.penalty else 0 end) as  ".'"sbi collect_p"'." ,
                                            sum(t.paid_amount) as total, sum(t.penalty) as total_penalty,
                                        emp.id,emp.emp_name
                                    from tbl_transaction t
                                    join tbl_apply_licence l on l.id = t.related_id
                                    join tbl_application_type_mstr apt on apt.id = l.application_type_id
                                    $join
                                    join view_ward_mstr w on w.id = l.ward_mstr_id
                                    left join tbl_cheque_dtl ch on ch.transaction_id = t.id
                                    $where
                                    group by emp.id,emp.emp_name ";

                                    // "where t.transaction_date between '$from_date'  and '$to_date'
                                    // and t.status = 1 ";

            $data['tc_collection'] = $this->model_view_trade_licence->row_query($sql_tc_collection,array());
            //print_var($data['tc_collection']);

            $sql_pement_mode_transaction = "select  lower(t.payment_mode) as payment_mode,count(t.id) as count,
                                                sum( t.paid_amount) as amount
                                            from tbl_transaction t
                                            join tbl_apply_licence l on l.id = t.related_id
                                            join view_ward_mstr w on w.id = l.ward_mstr_id
                                            $where
                                            group by lower(t.payment_mode) ";
                                            
                                            // "where t.transaction_date between '$from_date'  and '$to_date'
                                            // and t.status = 1 ";
            $data['pement_mode_collection'] = $this->model_view_trade_licence->row_query($sql_pement_mode_transaction,array());

            //print_var($sql_pement_mode_transaction);
        }
        $data['menu']=$menu;
        return view('Trade/Reports/daily_Collection_Register',$data);
    }

    //  incomplite code 

    public function message_log()
    {
        //echo"message_log";
        
        //$sms = Trade(array('ammount'=>'100000','application_no'=>'APPLIC264683150322031257','ref_no'=>'TRANML073450920220332'),'Payment done');
        ////$sms = Trade(array('ammount'=>'500','application_no'=>'0000','pyament_mode'=>'Online','ref_no'=>'0332'),'Payment done');
        //$sms = Trade(array('licence_no'=>'DHA103072022264673','exp_date'=>date('d-m-Y'),'toll_free_no1'=>'18008904115, 0651-3500700','ulb_name'=>session()->get('ulb_dtl')['ulb_name']),'License expired');
        // $sms = Trade(array('application_no'=>'APPLIC264683150322031257','licence_no'=>'DHA103072022264673','ulb_name'=>session()->get('ulb_dtl')['ulb_name']),'Application Approved');
        // $sms = Trade(array('application_no'=>'APPLIC264683150322031254'),'sent back');
        // $sms= Trade();

        // $sms = Water(array('ammount'=>'100000','application_no'=>'APPLIC264683150322031257','ref_no'=>'TRANML073450920220332','ulb_name'=>session()->get('ulb_dtl')['ulb_name']),'Payment done');
        ////$sms = Trade(array('ammount'=>'500','application_no'=>'0000','pyament_mode'=>'Online','ref_no'=>'0332'),'Payment done');
        // $sms = Water(array('ammount'=>'4673','consumer_no'=>'DHA1200020089044','ref_no'=>'WTRAN520220120160702'),'consumer Payment');
        // $sms = Water(array('consumer_no'=>'DHA1200020089044'),'Application Approved');
        // $sms = Water(array('application_no'=>'APPLIC264683150322031254'),'sent back');
        // $sms = Water(array('timestampe'=>date('d-m-Y h:i:sa',strtotime('2022-03-03 '.'18:20:00'))),'Site inspection set');
        // $sms = Water(array(),'Site inspection cancelled');
        //echo$d = date('d-m-Y h:i:sa',strtotime('2022-03-03 '.'18:20:00'));
        //$sms= Water();
        // if($sms['status']==true)
        {
            // $message= $sms['sms'];
            // $templateid= $sms['temp_id'];
            // $message="Payment done with amount 500 for Application No 12345. RMC";
            // $templateid="1307162359745436093";
            // $res=SMSJHGOVT("7050180186", $message, $templateid);
            // print_var($res);
            //print_var();
            //print_var($this->trade);


            // $this->trade->transBegin();
            // $data = ['sms'=>'hello test'];
            // $id = insert_sms_log($this->trade,'test',$data);
            
            // if($id)
            // {
            //     $update=['statuswa'=>0];
            //     $where =['id'=>$id];
            //     $update = update_sms_log($this->trade,'test',$where,$update);
            // }
            // if($this->trade->transStatus() === FALSE)
            // {
            //     $this->trade->transRollback();
            //     echo"error";
                
            // }
            // else
            // {
            //     $this->trade->transCommit();
                
            // }
            
        }

        $this->trade->transBegin();
        $data = ['sms'=>'hello test'];
        $id = $this->model_trade_sms_log->insert_sms_log($data);
        
        if($id)
        {
            $update=['status'=>0];
            $where =['id'=>$id];
            $update = $this->model_trade_sms_log->update_sms_log($where,$update);
        }
        if($this->trade->transStatus() === FALSE)
        {
            $this->trade->transRollback();
            echo"error";
            
        }
        else
        {
            $this->trade->transCommit();
            
        }
        
        //print_var($sms);
        
    }

    public function view_notice($notice_id_md5=null)
    {
        $data =[];
        print_var(getDenialAmountTrade('2022-03-30'));
        if($notice_id_md5)
        {
            $sql=" select * from tbl_notice";
        }
        //return view('trade/Reports/denial_view',$data);
    }

    //  incomplite code end

    public function counter_report()
    {
        $data=[];
        $Session = session();
        $data['ulb_dtl']=$Session->get('ulb_dtl');
        $data['from_date']=date('Y-M-D');
        $data['to_date']=date('Y-M-D');
        $ward_sql = " select * from view_ward_mstr where status = 1 and ulb_mstr_id = ? "; 
        $data['ward']=$this->model_view_trade_licence->row_query($ward_sql,array($this->ulb_id));
        $where = '';
       

        if($_POST)
        {
                try
                {
                    ## Read value
                    $start = sanitizeString($this->request->getVar('start'));
                    
                    
                    $rowperpage = sanitizeString($this->request->getVar('length')); // Rows display per page
    
                    $columnIndex = sanitizeString($this->request->getVar('order')[0]['column']); // Column index
                    $columnName = sanitizeString($this->request->getVar('columns')[$columnIndex]['data']); // Column name
                    if ($columnName=="application_no")
                        $columnName = 'app.application_no';
                    else if ($columnName=="license_no")
                        $columnName = 'app.license_no';
                    else if ($columnName=="ward_no")
                        $columnName = 'w.ward_no';
                    else if ($columnName=="firm_name")
                        $columnName = 'app.firm_name';
                    else if ($columnName=="owner_name")
                        $columnName = 'ow.owner_name';
                    else if ($columnName=="application_type")
                        $columnName = 'apm.application_type';
                    else if ($columnName=="mobile")
                        $columnName = 'ow.mobile';
                    else if ($columnName=="holding_no")
                        $columnName = 'app.holding_no';
                    else if ($columnName=="ownership_type")
                        $columnName = 'owm.ownership_type';
                    else if ($columnName=="uper_owner_name")
                        $columnName = 'app.premises_owner_name';
                    else if ($columnName=="nature_of_bussiness")
                        $columnName = 'app.nature_of_bussiness';
                    else if ($columnName=="licence_for_years")
                        $columnName = 'app.licence_for_years';
                    else if ($columnName=="apply_date")
                        $columnName = 'app.apply_date';
                    else if ($columnName=="valid_upto")
                        $columnName = 'app.valid_upto';
                    else if ($columnName=="active_status")
                        $columnName = 'app.valid_upto';
                    else if ($columnName=="area_in_sqft")
                        $columnName = 'app.area_in_sqft';
                    else if ($columnName=="area_of_plot")
                        $columnName = 'prop.area_of_plot';
                    else if ($columnName=="transaction_date")
                        $columnName = 't.transaction_date';
                    else if ($columnName=="transaction_no")
                        $columnName = 't.transaction_no';
                    else if ($columnName=="payment_mode")
                        $columnName = 't.payment_mode';
                    else if ($columnName=="bounc_cheque")
                        $columnName = 't.bounc_cheque';
                    else if ($columnName=="c_cheque_no")
                        $columnName = 't.c_cheque_no';
                    else if ($columnName=="bank_name")
                        $columnName = 't.bank_name';
                    else if ($columnName=="branch_name")
                        $columnName = 't.branch_name';
                    else if ($columnName=="denial_fee")
                        $columnName = 'denial.amount';
                    else if ($columnName=="paid_amount")
                        $columnName = 't.paid_amount';
                    else if ($columnName=="emp_name")
                        $columnName = 'emp.emp_name';
                    else if ($columnName=="doc_status")
                        $columnName = ' app.document_upload_status';
                    else if ($columnName=="site_inspection")
                        $columnName = ' ins.app_id';

                    else
                        $columnName = 'cast(app.apply_date as date)';
                    
    
                    //$columnName = "tbl_transaction.tran_date";
                    $columnSortOrder = sanitizeString($this->request->getVar('order')[0]['dir']); // asc or desc
                    $searchValue = sanitizeString($this->request->getVar('search')['value']); // Search value
                    
                    
                    
                    // Date filter
                    // $search_from_date = sanitizeString($this->request->getVar('search_from_date'));
                    // $search_upto_date = sanitizeString($this->request->getVar('search_upto_date'));
                    $search_ward_mstr_id = sanitizeString($this->request->getVar('search_ward_mstr_id'));
                    // $search_collector_id = sanitizeString($this->request->getVar('search_collector_id'));
                    // $search_entry_type = sanitizeString($this->request->getVar('search_entry_type'));
                    $searchQuery = "";
                    $whereQuery = "";
                    //print_var($search_ward_mstr_id);print_var('llllllll');
                    $orderBY = " ORDER BY ".$columnName." ".$columnSortOrder;
                    $limit = " LIMIT ".$rowperpage." OFFSET ".$start;
                    
                    $whereQuery .= "  app.pending_status =5 and (app.update_status=0 or app.update_status isnull) 
                                            and app.status !=0 and app.license_no notnull ";
                    if ($search_ward_mstr_id != '') 
                    {
                        $whereQuery .= " AND  app.ward_mstr_id='".$search_ward_mstr_id."'";
                    }
                   
                    $whereQueryWithSearch = "";
                    if ($searchValue!='') 
                    {
                        $whereQueryWithSearch = " AND (app.holding_no ILIKE '%".$searchValue."%'
                                        OR cast(prop.area_of_plot as varchar) ILIKE '%".$searchValue."%'
                                        OR w.ward_no ILIKE '%".$searchValue."%'
                                        OR cast(app.area_in_sqft as varchar) ILIKE '%".$searchValue."%'
                                        OR app.application_no ILIKE '%".$searchValue."%'
                                        OR app.firm_name ILIKE '%".$searchValue."%'
                                        OR t.transaction_no  ILIKE '%".$searchValue."%'
                                        OR t.payment_mode  ILIKE '%".$searchValue."%'
                                        OR t.c_cheque_no  ILIKE '%".$searchValue."%'
                                        OR t.bounc_cheque  ILIKE '%".$searchValue."%'
                                        OR ow.owner_name  ILIKE '%".$searchValue."%'
                                        OR ow.mobile ILIKE '%".$searchValue."%'
                                        OR app.license_no ILIKE'%".$searchValue."%' )";
                    }
                    // echo $whereQueryWithSearch;
                    $base_url = base_url();
                    $selectStatement = "SELECT
                                        ROW_NUMBER () OVER (ORDER BY ".$columnName.") AS s_no,
                                        app.id,app.application_no,app.license_no,w.ward_no,app.firm_name,ow.owner_name,apm.application_type,
                                        ow.mobile, app.holding_no,owm.ownership_type,app.ownership_type_id,
                                        case when app.ownership_type_id in(2,3) then app.premises_owner_name else null end uper_owner_name,
                                        app.nature_of_bussiness , app.licence_for_years,app.apply_date, app.valid_upto,
                                        case when app.valid_upto<CURRENT_DATE then 'Expired' else 'Valid' end as active_status,
                                        app.area_in_sqft, prop.area_of_plot,
                                        t.transaction_date,t.transaction_no,t.payment_mode,t.bounc_cheque,t.c_cheque_no,t.bank_name,t.branch_name,
                                        t.licence_fee,coalesce(pen.amount,0) as leat_fine,coalesce(denial.amount,0) as denial_fee,
                                        t.paid_amount,emp.emp_name,
                                        case when app.document_upload_status=1  and app.doc_verify_status = 0 then 'doc_uploded but not verify' 
                                            when app.document_upload_status=1  and app.doc_verify_status = 1 then 'doc_uploded and  verify'
                                            when app.document_upload_status=0   then 'doc_uploded not uploded'
                                            end as doc_status,
                                        case when ins.app_id isnull then 'not done' else 'done' end as site_inspection  
                                        ";
                   
    
                    $sql =" from tbl_apply_licence app
                            join owner ow on ow.apply_licence_id = app.id
                            join view_ward_mstr w on w.id = app.ward_mstr_id and w.status = 1
                            join tbl_application_type_mstr apm on apm.id = app.application_type_id and apm.status =1
                            join tbl_ownership_type_mstr owm on owm.id = app.ownership_type_id and owm.status =1
                            left join view_prop_detail prop on prop.id = app.prop_dtl_id
                            left join transaction t on t.app_id = app.id
                            left join fine_rebet_details pen on pen.transaction_id = t.t_id and pen.row_num = 1
                            left join fine_rebet_details denial on denial.transaction_id = t.t_id and pen.row_num = 2
                            left join view_emp_details emp on emp.id = t.e_id
                            left join sit_ispection ins on ins.app_id = app.id
                            where                  
                            ".$whereQuery;

                    $with = " with owner as (
                                    select apply_licence_id,
                                        string_agg(owner_name,' ,') as owner_name,
                                        string_agg(guardian_name,' ,') as guardian_name,
                                        string_agg(emailid,' ,') as email_id,
                                        string_agg(mobile::text,' ,') as mobile
                                    from tbl_firm_owner_name 
                                    where status = 1
                                    group by apply_licence_id
                                ),
                                transaction_bounce as(
                                    select t.related_id as b_related_id,t.id as transaction_id,
                                        case when ch.status =3 then 'bounse' else null end b_status,
                                        t.payment_mode b_payment_mode,ch.status as b_status,
                                        ch.cheque_no as b_cheque_no,t.transaction_no as b_transaction_no,
                                        row_number() over(partition by ch.transaction_id order by ch.id desc) as row_num,
                                        t.transaction_date as b_transaction_date,
                                        (coalesce(t.paid_amount,0)-coalesce(t.penalty,0)) as b_licence_fee,
                                        t.emp_details_id
                                    from  tbl_transaction t
                                    left join tbl_cheque_dtl ch on ch.transaction_id = t.id
                                    where t.status = 3
                                    --order by t.related_id 
                                ),
                                transaction_clrear as(
                                    select t.related_id,t.id as transaction_id,
                                        case when ch.status =3 then 'bounse'
                                            when ch.status = 2 then 'not_clear'
                                        else 'clear' end c_status,
                                        t.payment_mode as c_payment_mode,ch.status as c_status,
                                        ch.cheque_no as c_cheque_no,t.transaction_no as c_transaction_no,
                                        t.transaction_date as c_transaction_date,ch.bank_name,ch.branch_name,
                                        (coalesce(t.paid_amount,0)-coalesce(t.penalty,0)) as c_licence_fee,coalesce(t.paid_amount,0) as paid_amount,
                                        t.emp_details_id
                                    from  tbl_transaction t
                                    left join tbl_cheque_dtl ch on ch.transaction_id = t.id
                                    where t.status not in (3,0)
                                ),
                                transaction as(
                                    select *,
                                        case when tc.related_id isnull then tb.transaction_id else tc.transaction_id end as t_id,
                                        case when tc.related_id isnull then tb.b_related_id else tc.related_id end as app_id,
                                        case when tc.related_id isnull then tb.b_transaction_date else tc.c_transaction_date end as transaction_date,
                                        case when tc.related_id isnull then tb.b_transaction_no else tc.c_transaction_no end as transaction_no,
                                        case when tc.related_id isnull then tb.b_payment_mode else tc.c_payment_mode end as payment_mode,
                                        tb.b_cheque_no as bounc_cheque,
                                        case when tc.related_id isnull then tb.b_licence_fee else tc.c_licence_fee end as licence_fee,
                                        case when tc.related_id isnull then tb.emp_details_id else tc.emp_details_id end as e_id
                                    from  transaction_bounce tb 
                                    right join transaction_clrear tc on tc.related_id=tb.b_related_id and tb.row_num in(1)		
                                ),
                                fine_rebet_details as (
                                    select *, 
                                        row_number() over(partition by transaction_id order by id desc) as row_num
                                    from tbl_transaction_fine_rebet_details 
                                ),
                                sit_ispection as(
                                    select distinct(apply_licence_id) as app_id
                                    from tbl_taxdaroga_document_verification
                                    where status = 1
                                )";
                    //print_var($sql);
                    //$totalAmount = $this->model_datatable->getSumAmount($sql);
                    $totalRecords = $this->model_datatable->getTotalRecords($sql,false,$with);
                    //print_var($totalRecords);
                    //print_var($columnName);
                    $total_collection = 0;
                    if ($totalRecords>0) 
                    {
                        
                        ## Total number of records with filtering
                        $totalRecordwithFilter = $this->model_datatable->getTotalRecordwithFilter($sql.$whereQueryWithSearch,false,$with);
                        ## Fetch records
                        //$fetchSql = $selectStatement.$sql.$whereQueryWithSearch.$orderBY.$limit;
                        $fetchSql = $with.$selectStatement.$sql.$whereQueryWithSearch.$orderBY.$limit;
                        //$fetchSumSql = $selectSumStatement.$sql;
                        $result = $this->model_datatable->getRecords($fetchSql,false);
                        //$total_collection = $this->model_datatable->getSumRecords($fetchSumSql)['total_payable_amt'];
                        
                        $records = [];
                        if ($result) 
                        {
                            foreach ($result AS $key=>$tran_dtl) 
                            {
                                $records[] = [
                                    's_no'=>$tran_dtl['s_no'],
                                    'application_no'=>$tran_dtl['application_no'],
                                    'license_no'=>$tran_dtl['license_no'],
                                    'ward_no'=>$tran_dtl['ward_no'],
                                    'firm_name'=>$tran_dtl['firm_name'],
                                    'owner_name'=>$tran_dtl['owner_name'],
                                    'application_type'=>$tran_dtl['application_type'],
                                    'mobile'=>$tran_dtl['mobile'],

                                    'holding_no'=>$tran_dtl['holding_no'],
                                    'ownership_type'=>$tran_dtl['ownership_type'],
                                    'uper_owner_name'=>$tran_dtl['uper_owner_name'],
                                    'nature_of_bussiness'=>$tran_dtl['nature_of_bussiness'],
                                    'licence_for_years'=>$tran_dtl['licence_for_years'],
                                    'apply_date'=>$tran_dtl['apply_date'],
                                    'valid_upto'=>$tran_dtl['valid_upto'],
                                    'active_status'=>$tran_dtl['active_status'],
                                    'pan_no'=>'',
                                    'area_in_sqft'=>$tran_dtl['area_in_sqft'],
                                    'area_of_plot'=>$tran_dtl['area_of_plot'],
                                    'transaction_date'=>$tran_dtl['transaction_date'],
                                    'transaction_no'=>$tran_dtl['transaction_no'],
                                    'payment_mode'=>$tran_dtl['payment_mode'],
                                    'bounc_cheque'=>$tran_dtl['bounc_cheque'],
                                    'c_cheque_no'=>$tran_dtl['c_cheque_no'],
                                    'bank_name'=>$tran_dtl['bank_name'],

                                    'branch_name'=>$tran_dtl['branch_name'],
                                    'denial_fee'=>$tran_dtl['denial_fee'],
                                    'licence_fee'=>$tran_dtl['licence_fee'],
                                    'paid_amount'=>$tran_dtl['paid_amount'],
                                    'emp_name'=>$tran_dtl['emp_name'],
                                    'doc_status'=>$tran_dtl['doc_status'],
                                    'site_inspection'=>$tran_dtl['site_inspection'],
                                    
                                    
                                ];
                            }
                        }
                    } 
                    else 
                    {
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
                    
                    
                }
                catch(Exception $e)
                {
                    echo"catch";
                    print_r($e);
                }
        }
        
        
        return view('trade/Reports/counter_report',$data);
    }
    
    public function counter_reportExcel($search_ward_mstr_id = null)
    {
        try{
            $whereQuery = " app.pending_status =5 and (app.update_status=0 or app.update_status isnull) 
                                and app.status !=0 and app.license_no notnull";
            
                if ($search_ward_mstr_id != 'ALL') 
                {
                    $whereQuery .= " AND  l.ward_mstr_id='".$search_ward_mstr_id."'";
                }
                

                $selectStatement = " SELECT
                                    app.id,app.application_no,app.license_no,w.ward_no,app.firm_name,ow.owner_name,apm.application_type,
                                    ow.mobile, app.holding_no,owm.ownership_type,app.ownership_type_id,
                                    case when app.ownership_type_id in(2,3) then app.premises_owner_name else null end uper_owner_name,
                                    app.nature_of_bussiness , app.licence_for_years,app.apply_date, app.valid_upto,
                                    case when app.valid_upto < CURRENT_DATE then 'Expired' else 'Valid' end as active_status,
                                    app.area_in_sqft, prop.area_of_plot,
                                    t.transaction_date,t.transaction_no,t.payment_mode,t.bounc_cheque,t.c_cheque_no,t.bank_name,t.branch_name,
                                    t.licence_fee,coalesce(pen.amount,0) as leat_fine,coalesce(denial.amount,0) as denial_fee,
                                    t.paid_amount,emp.emp_name,
                                    case when app.document_upload_status=1  and app.doc_verify_status = 0 then 'doc_uploded but not verify' 
                                        when app.document_upload_status=1  and app.doc_verify_status = 1 then 'doc_uploded and  verify'
                                        when app.document_upload_status=0   then 'doc_uploded not uploded'
                                        end as doc_status,
                                    case when ins.app_id isnull then 'not done' else 'done' end as site_inspection  
                                    ";
                
                //print_var($selectStatement);
                $sql =" from tbl_apply_licence app
                join owner ow on ow.apply_licence_id = app.id
                join view_ward_mstr w on w.id = app.ward_mstr_id and w.status = 1
                join tbl_application_type_mstr apm on apm.id = app.application_type_id and apm.status =1
                join tbl_ownership_type_mstr owm on owm.id = app.ownership_type_id and owm.status =1
                left join view_prop_detail prop on prop.id = app.prop_dtl_id
                left join transaction t on t.app_id = app.id
                left join fine_rebet_details pen on pen.transaction_id = t.t_id and pen.row_num = 1
                left join fine_rebet_details denial on denial.transaction_id = t.t_id and pen.row_num = 2
                left join view_emp_details emp on emp.id = t.e_id
                left join sit_ispection ins on ins.app_id = app.id
                where                  
                ".$whereQuery;

        $with = " with owner as (
                        select apply_licence_id,
                            string_agg(owner_name,' ,') as owner_name,
                            string_agg(guardian_name,' ,') as guardian_name,
                            string_agg(emailid,' ,') as email_id,
                            string_agg(mobile::text,' ,') as mobile
                        from tbl_firm_owner_name 
                        where status = 1
                        group by apply_licence_id
                    ),
                    transaction_bounce as(
                        select t.related_id as b_related_id,t.id as transaction_id,
                            case when ch.status =3 then 'bounse' else null end b_status,
                            t.payment_mode b_payment_mode,ch.status as b_status,
                            ch.cheque_no as b_cheque_no,t.transaction_no as b_transaction_no,
                            row_number() over(partition by ch.transaction_id order by ch.id desc) as row_num,
                            t.transaction_date as b_transaction_date,
                            (coalesce(t.paid_amount,0)-coalesce(t.penalty,0)) as b_licence_fee,
                            t.emp_details_id
                        from  tbl_transaction t
                        left join tbl_cheque_dtl ch on ch.transaction_id = t.id
                        where t.status = 3
                        --order by t.related_id 
                    ),
                    transaction_clrear as(
                        select t.related_id,t.id as transaction_id,
                            case when ch.status =3 then 'bounse'
                                when ch.status = 2 then 'not_clear'
                            else 'clear' end c_status,
                            t.payment_mode as c_payment_mode,ch.status as c_status,
                            ch.cheque_no as c_cheque_no,t.transaction_no as c_transaction_no,
                            t.transaction_date as c_transaction_date,ch.bank_name,ch.branch_name,
                            (coalesce(t.paid_amount,0)-coalesce(t.penalty,0)) as c_licence_fee,coalesce(t.paid_amount,0) as paid_amount,
                            t.emp_details_id
                        from  tbl_transaction t
                        left join tbl_cheque_dtl ch on ch.transaction_id = t.id
                        where t.status not in (3,0)
                    ),
                    transaction as(
                        select *,
                            case when tc.related_id isnull then tb.transaction_id else tc.transaction_id end as t_id,
                            case when tc.related_id isnull then tb.b_related_id else tc.related_id end as app_id,
                            case when tc.related_id isnull then tb.b_transaction_date else tc.c_transaction_date end as transaction_date,
                            case when tc.related_id isnull then tb.b_transaction_no else tc.c_transaction_no end as transaction_no,
                            case when tc.related_id isnull then tb.b_payment_mode else tc.c_payment_mode end as payment_mode,
                            tb.b_cheque_no as bounc_cheque,
                            case when tc.related_id isnull then tb.b_licence_fee else tc.c_licence_fee end as licence_fee,
                            case when tc.related_id isnull then tb.emp_details_id else tc.emp_details_id end as e_id
                        from  transaction_bounce tb 
                        right join transaction_clrear tc on tc.related_id=tb.b_related_id and tb.row_num in(1)		
                    ),
                    fine_rebet_details as (
                        select *, 
                            row_number() over(partition by transaction_id order by id desc) as row_num
                        from tbl_transaction_fine_rebet_details 
                    ),
                    sit_ispection as(
                        select distinct(apply_licence_id) as app_id
                        from tbl_taxdaroga_document_verification
                        where status = 1
                    )";
                    

            $fetchSql = $with.$selectStatement.$sql;
            
            $result = $this->model_datatable->getRecords($fetchSql,false);
            $records = [];
            if ($result) 
            {
                foreach ($result AS $key=>$tran_dtl) 
                {
                    

                    $records[] = [
                        // 's_no'=>$tran_dtl['s_no'],
                        'application_no'=>$tran_dtl['application_no'],
                        'license_no'=>$tran_dtl['license_no'],
                        'ward_no'=>$tran_dtl['ward_no'],
                        'firm_name'=>$tran_dtl['firm_name'],
                        'owner_name'=>$tran_dtl['owner_name'],
                        'application_type'=>$tran_dtl['application_type'],
                        'mobile'=>$tran_dtl['mobile'],
                        'pan_no'=>'',
                        'holding_no'=>$tran_dtl['holding_no'],
                        'ownership_type'=>$tran_dtl['ownership_type'],                        
                        'uper_owner_name'=>$tran_dtl['uper_owner_name'],
                        'another'=>'',
                        'nature_of_bussiness'=>$tran_dtl['nature_of_bussiness'],
                        'licence_for_years'=>$tran_dtl['licence_for_years'],
                        'apply_date'=>$tran_dtl['apply_date'],
                        'valid_upto'=>$tran_dtl['valid_upto'],
                        'active_status'=>$tran_dtl['active_status'],

                        'area_in_sqft'=>$tran_dtl['area_in_sqft'],
                        'area_of_plot'=>$tran_dtl['area_of_plot'],
                        'transaction_date'=>$tran_dtl['transaction_date'],
                        'transaction_no'=>$tran_dtl['transaction_no'],
                        'payment_mode'=>$tran_dtl['payment_mode'],
                        'bounc_cheque'=>$tran_dtl['bounc_cheque'],
                        'c_cheque_no'=>$tran_dtl['c_cheque_no'],
                        'bank_name'=>$tran_dtl['bank_name'],

                        'branch_name'=>$tran_dtl['branch_name'],
                        'denial_fee'=>$tran_dtl['denial_fee'],
                        'licence_fee'=>$tran_dtl['licence_fee'],
                        'paid_amount'=>$tran_dtl['paid_amount'],
                        ''=>'',
                        'emp_name'=>$tran_dtl['emp_name'],
                        'doc_status'=>$tran_dtl['doc_status'],
                        'site_inspection'=>$tran_dtl['site_inspection'],
                        
                        
                    ];

                }
            }
            phpOfficeLoad();
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $activeSheet = $spreadsheet->getActiveSheet();
                            $activeSheet->setCellValue('A1', 'App No.');
                            $activeSheet->setCellValue('B1', 'License No.');
                            $activeSheet->setCellValue('C1', 'Ward No.');
                            $activeSheet->setCellValue('D1', 'Firm Name');
                            $activeSheet->setCellValue('E1', 'Applicant Name');
                            $activeSheet->setCellValue('F1', 'Application Type');
                            $activeSheet->setCellValue('G1', 'Contact No.');

                            $activeSheet->setCellValue('H1', 'Pan No.');
                            $activeSheet->setCellValue('I1', 'Holding No.');
                            $activeSheet->setCellValue('J1', 'Occupancy type (Self/Tenanted)');
                            $activeSheet->setCellValue('K1', 'Name Of Owner in Case of Tenanted');
                            $activeSheet->setCellValue('L1', 'Owner Mobile No.');
                            $activeSheet->setCellValue('M1', 'Code of Trade Licence');
                            $activeSheet->setCellValue('N1', 'License year(time period)');

                            $activeSheet->setCellValue('O1', 'Applied Date');
                            $activeSheet->setCellValue('P1', 'Valid up to');
                            $activeSheet->setCellValue('Q1', 'Active Status(Expire/Valid)');
                            $activeSheet->setCellValue('R1', 'Area as per TL Form');
                            $activeSheet->setCellValue('S1', 'Build up area as per SAF');
                            $activeSheet->setCellValue('T1', 'Tran.Date');
                            $activeSheet->setCellValue('U1', 'Tran. No.');

                            $activeSheet->setCellValue('V1', 'Payment Mode');
                            $activeSheet->setCellValue('W1', 'Payment status(online/c Cheque/if any cancellation of Cheque)');
                            $activeSheet->setCellValue('X1', 'Check No/DD No');
                            $activeSheet->setCellValue('Y1', 'Bank');
                            $activeSheet->setCellValue('Z1', 'Branch');
                            $activeSheet->setCellValue('AA1', 'License Fee');
                            $activeSheet->setCellValue('AB1', 'Denial Amount');

                            $activeSheet->setCellValue('AC1', 'Amount');
                            $activeSheet->setCellValue('AD1', 'Annual Turnover');
                            $activeSheet->setCellValue('AE1', 'Payment Collector');
                            $activeSheet->setCellValue('AF1', 'File Status');
                            $activeSheet->setCellValue('AG1', 'Whether inspection done or normalizer_get_raw_decomposition');               
                            
                           


                            $activeSheet->fromArray($records, NULL, 'A3');
            $filename = "TRADE_counter_report_".date('Ymd-hisa').".xlsx";
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="'.$filename.'"');
            header('Cache-Control: max-age=0');
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            //$writer->save(APPPATH.'/hello world.xlsx');
            $writer->save('php://output');
        }
        catch(Exception $e)
        {
            print_r($e);
        }
    } 

    public function hhs_and_trade()
    {
        $data=array();
        //print_var(session()->get('ulb_dtl'));
        $data['ulb_dtl'] = session()->get('ulb_dtl');
        
        //print_var($data['ulb_dtl']); 
        $data['fy_list']=fy_year_list();
        $cur_fy = $data['fy_list'][0];
        
        if(!empty($_POST))
        {
            $cur_fy= $_POST['fy_year'];
        }
        $fy = explode('-',$cur_fy);
        $priv_from=($fy[0]-1).'-04-01';
        $data['priv_fy']=$fy[0]-1;
        //echo $priv_from;
        $from=$fy[0].'-04-01';
        $to = $fy[1].'-04-01';
        //print_var($from);die;
        $db_prop =  $data['ulb_dtl']['property'];
        $db_water =  $data['ulb_dtl']['water'];    
        $db_trade =  $data['ulb_dtl']['trade'];        
        $sql_prop = " select 
                            total,holding_type,ROW_NUMBER () OVER ( partition by holding_type ORDER BY holding_type)
                    from dblink('host=".getenv('db.pgsql.hname')." user=".getenv('db.pgsql.uname')." password=".getenv('db.pgsql.pass')." port=".getenv('db.pgsql.port')." dbname=$db_prop'::text,
                                'select count(id) as total, holding_type from tbl_prop_dtl
                                where status =1 and holding_type in (''MIX_COMMERCIAL'',''PURE_RESIDENTIAL'',''PURE_COMMERCIAL'') 
                                    and to_char(created_on::date,''YYYY-MM-DD'')<=''$to''
                                group by holding_type ')
                                tbl_prop_dtl (total bigint, holding_type text)";
        $data['property_dtl']= $this->model_view_trade_licence->row_query($sql_prop,array());       
        
        foreach($data['property_dtl'] as $val)
        {
            if($val['holding_type']=='MIX_COMMERCIAL')
                $data['property_dtl']['MIX_COMMERCIAL']=$val;
            elseif($val['holding_type']=='PURE_COMMERCIAL')
                $data['property_dtl']['PURE_COMMERCIAL']=$val;
            elseif($val['holding_type']=='PURE_RESIDENTIAL')
                $data['property_dtl']['PURE_RESIDENCIAL']=$val;
        }
        //print_var($data['property_dtl']);

        $sql_for_more_one_year = " select count(*) as total, ROW_NUMBER () OVER ( ORDER BY count(*))		
                                                from tbl_apply_licence app
                                                where app.status =1 and (app.update_status isnull or app.update_status =0)
                                                    and app.license_date  <'$to'
                                                    and app.pending_status=5 and app.licence_for_years > 1
                                               ";
        $data['for_more_one_year']= $this->model_view_trade_licence->row_query($sql_for_more_one_year,array())[0] ?? [];
        //print_var($data['for_more_one_year']);

        $sql_count_app_type = " select count(app.id) as total_app, app.application_type_id, atm.application_type,
                                                    ROW_NUMBER () OVER ( partition by application_type ORDER BY application_type)
                                                from tbl_apply_licence app
                                                join tbl_application_type_mstr atm on atm.id = app.application_type_id
                                                where app.status =1 and (app.update_status isnull or app.update_status =0 )
                                                    and app.pending_status=5 and atm.id in (1,2)
                                                    and app.license_date >= '$priv_from' and  app.license_date <'$from'
                                                group by app.application_type_id,atm.application_type
                                                ";
        $data['count_app_type']= $this->model_view_trade_licence->row_query($sql_count_app_type,array());
        foreach ($data['count_app_type'] as $val)
        {
            if($val['application_type']=='NEW LICENSE')
                $data['count_app_type']['NEW LICENSE']=$val;
            if($val['application_type']=='RENEWAL')
                $data['count_app_type']['RENEWAL']=$val;

        }
        //print_var($data['count_app_type']);
        $sql_renewal_trade_cy = " select count(*) as total,atm.application_type,app.application_type_id 
                                    from tbl_apply_licence app
                                    join tbl_application_type_mstr atm on atm.id = app.application_type_id
                                    where app.application_type_id = 2 and app.status =1 and (app.update_status isnull or app.update_status =0)
                                        and app.license_date >= '$from' and  app.license_date <'$to'
                                        and app.pending_status=5 
                                    group by atm.application_type,app.application_type_id";
        $data['renewal_trade_cy']= $this->model_view_trade_licence->row_query($sql_renewal_trade_cy,array());
        //print_var($data['renewal_trade_cy']);

        $sql_surrended_trade = " select count(*) total , atm.application_type,app.application_type_id
                                from tbl_apply_licence app
                                join tbl_application_type_mstr atm on atm.id = app.application_type_id
                                where app.application_type_id = 4 and app.status =1 and (app.update_status isnull or app.update_status =0)		
                                    and app.pending_status=5 and  app.license_date <'$to'
                                group by atm.application_type,app.application_type_id ";
        $data['surrended_trade']= $this->model_view_trade_licence->row_query($sql_surrended_trade,array())[0] ?? [];
        //print_var($data['surrended_trade']);


        $sql_close = " select count(*) total from tbl_apply_licence where status = 0 and  license_date <'$to'";

        $data['close']= $this->model_view_trade_licence->row_query($sql_close,array())[0] ?? [];
        //print_var($data['close']);

        $sql_renewal_till_now = " select count(*) total ,app.application_type_id
                                from tbl_apply_licence app
                                where app.application_type_id = 2 and app.status =1 and (app.update_status isnull or app.update_status =0)		
                                    and app.pending_status=5 and app.license_date <'$to'
                                group by app.application_type_id";
        $data['renewal_till_now']= $this->model_view_trade_licence->row_query($sql_renewal_till_now,array())[0] ?? [];
        //print_var($data);

        $sql_denail_till_now =" select count(*) total 
                                from tbl_denial_consumer_dtl
                                where status !=4 and to_char(created_on::date,'YYYY-MM-DD')<'$to'";
        
        $data['denail_till_now']= $this->model_view_trade_licence->row_query($sql_denail_till_now, array())[0] ?? [];
        //print_var($data);
        return view('trade/Reports/hhs_and_trade',$data);

    }

    public function check_bounce_payment($md5_licence_id = null)
    {   
        //$md5_licence_id = md5(264720);
        //print_var(session()->get('emp_details'));
        $emp_id = session()->get('emp_details')['user_mstr_id']??null;
        if($md5_licence_id)
        {
            $sql_licence = " select * from tbl_apply_licence where md5(id::text) = '$md5_licence_id' ";
            $licence = $this->model_view_trade_licence->row_query($sql_licence,array())[0]??[];
            //print_var($licence );exit;
            if(!empty($licence) && $licence['payment_status']==0)
            {
                $apptypeid = md5($licence['application_type_id']);
                $id = $licence['id'];
                $data=array();
                $session=session();
                $get_ulb_id=$session->get('ulb_dtl');
                $get_emp_details=$session->get('emp_details');
                
                if($apptypeid<>null and $id<>null)
                {
                    $data['licence_dtl']=$licence;

                }
                if($_POST)
                {
                    $this->db->transBegin();
                    $inputs = filterSanitizeStringtoUpper($this->request->getVar());
                    //print_var($inputs);die;
                    $sql_transection_dtl = " select * from tbl_transaction 
                                                where md5(related_id::text) = '$md5_licence_id' 
                                                order by id desc limit 1 ";
                    $priv_tran =  $this->model_view_trade_licence->row_query($sql_transection_dtl,array())[0]??[]; 
                    // print_var($priv_tran);exit;
                    if(!empty($priv_tran) && $priv_tran['status']=3)
                    {
                        $sql_penalty = " select * from tbl_bank_recancilation 
                                            where transaction_id = $priv_tran[id] and  related_id = $priv_tran[related_id] 
                                            order by id desc limit 1";
                        $banck_recanseletion = $this->model_view_trade_licence->row_query($sql_penalty,array())[0]??[]; 
                        //print_var($banck_recanseletion);
                    }
                    $ql_for_notice = "select * from tbl_denial_notice where apply_id = '$licence[id]' and status = 2 ";
                    $noticeDetails = $this->model_view_trade_licence->row_query($ql_for_notice,array())[0]??[]; 
                    //denialAmount calculation
                    $denialAmount = 0; 
                    if($noticeDetails)
                    {
                        $now = strtotime(date('Y-m-d H:i:s')); // todays date
                        $notice_date = strtotime($noticeDetails['created_on']); //notice date
                        $datediff = $now - $notice_date; //days difference in second
                        $totalDays =   ceil($datediff / (60 * 60 * 24)); // total no. of days
                        //$denialAmount=100+(($totalDays)*10);
                        $denialAmount=getDenialAmountTrade(date('Y-m-d',$notice_date),date('Y-m-d',$now));
                        $updat_dnial_sql = " update  tbl_denial_notice set fine_amount = $denialAmount where id = $noticeDetails[id]";
                        $this->model_view_trade_licence->row_query($updat_dnial_sql,array());//update status and fineAmount
                        // in tbl_denial_notice by $denialId

                    }//print_var($_POST);die;
                    $cal = array('areasqft'=>$licence['area_in_sqft'],
                                'applytypeid'=>$licence['application_type_id'],
                                'estdate'=>$licence['application_type_id']==1?$licence['establishment_date']:$licence['valid_from'],
                                'apply_licence_id'=>$licence['id'],
                                'tobacco_status'=>$licence['tobacco_status'],
                                'licensefor'=>$this->request->getVar('licence_for'),
                                'nature_of_business'=>$licence['nature_of_bussiness'],
                                );                                        
                    $charg = $this->getcharge( $cal);
                    $charg=json_decode( $charg);
                    //print_var($charg);die;
                    
                    $sql_application_type = " select * from tbl_application_type_mstr where id = $licence[application_type_id]";
                    $aplication_type = $this->model_view_trade_licence->row_query($sql_application_type,array())[0]??[];
                     //end
                    $totalCharge = $charg->total_charge + $denialAmount;

                    $transact_arr=array();
                    $transact_arr['related_id']=$licence['id'];
                    $transact_arr['ward_mstr_id']=$licence["ward_mstr_id"];
                    $transact_arr['transaction_type']=$aplication_type["application_type"];
                    $transact_arr['transaction_date']=date('Y-m-d');
                    $transact_arr['payment_mode']=$inputs['payment_mode'];                            
                    $transact_arr['paid_amount']=$totalCharge;

                    $transact_arr['penalty']=$charg->penalty+$denialAmount;
                    if($inputs['payment_mode']!='CASH')
                    {
                        $transact_arr['status']=2;
                    }                        
                    $transact_arr['emp_details_id']=$emp_id;                                    
                    $transact_arr['created_on']=date('Y-m-d H:i:s');
                    $transact_arr['ip_address']=getClientIp();
                    $transaction_id = $this->TradeTransactionModel->insertPayment($transact_arr); 
                    
                    $trafinerebate = array();  // penalty insert
                    $trafinerebate['transaction_id']=$transaction_id;
                    $trafinerebate['head_name']='Delay Apply License';
                    $trafinerebate['amount']=$charg->penalty;
                    $trafinerebate['value_add_minus']='Add';
                    $trafinerebate['created_on']=date('Y-m-d H:i:s');
                    $this->model_trade_transaction_fine_rebet_details->fine_rebet_details($trafinerebate);

                    if($denialAmount > 0)
                    {
                        $denial = array();  // denial insert
                        $denial['transaction_id']=$transaction_id;
                        $denial['head_name']='Denial Apply  ';
                        $denial['amount']=$denialAmount;
                        $denial['value_add_minus']='Add';
                        $denial['created_on']=date('Y-m-d H:i:s');
                        $this->model_trade_transaction_fine_rebet_details->fine_rebet_details($denial);
                    }


                    $payment_status=1;
                    if($inputs['payment_mode']!='CASH')
                    {
                        $chq_arr=array();
                        $chq_arr['transaction_id']=$transaction_id;
                        $chq_arr['cheque_no']=$inputs['chq_no'];
                        $chq_arr['cheque_date']=$inputs['chq_date'];
                        $chq_arr['bank_name']=$inputs['bank_name'];
                        $chq_arr['branch_name']=$inputs['branch_name'];
                        $chq_arr['emp_details_id']=$emp_id; 
                        $chq_arr['created_on']=date('Y-m-d H:i:s');
                        $payment_status=2;
                        $this->TradeChequeDtlModel->insertdata($chq_arr);
                    }
                    $sql_update_payment_status = " update tbl_apply_licence set payment_status =  $payment_status,licence_for_years=$inputs[licence_for] where id = $licence[id]";
                    $this->model_view_trade_licence->row_query($sql_update_payment_status,array());
                    /**********sms send testing code *************/
                    $transaction_no = $this->TradeTransactionModel->transaction_details(md5($transaction_id))['transaction_no'];
                    $sql_owner = "select * from tbl_firm_owner_name where apply_licence_id =$licence[id] ";
                    $owner_for_sms = $this->model_view_trade_licence->row_query($sql_owner,array());
                    $sms = Trade(array('ammount'=>$transact_arr['paid_amount'],'application_no'=>$licence['application_no'],'ref_no'=>$transaction_no),'Payment done');
                    if($sms['status']==true)
                    {
                        foreach($owner_for_sms as $val)
                        {
                            $message= $sms['sms'];
                            $templateid= $sms['temp_id'];
                            $sms_data = [
                                'emp_id'=>$emp_id,
                                'ref_id'=>$licence['id'],
                                'ref_type'=>'tbl_apply_licence',
                                'mobile_no'=>$val['mobile'],
                                'purpose'=>strtoupper($aplication_type['application_type'].'PAYMENT'.'_C')??null,
                                'template_id'=>$templateid,
                                'message'=>$message                                
                                ];
                            $sms_id = $this->model_trade_sms_log->insert_sms_log($sms_data);
                            if($sms_id)
                            {
                                //$res=SMSJHGOVT("7050180186", $message, $templateid);
                                $res=send_sms($val['mobile'], $message, $templateid);//print_var($res);
                                if($res)
                                {
                                    $update=[
                                        'response'=>$res['response'],
                                        'smgid'=>$res['msg'],
                                    ];
                                    $where =['id'=>$sms_id];
                                    $update = $this->model_trade_sms_log->update_sms_log($where,$update);
                                }
                            }

                            
                        }

                    }
                    // $this->db->transRollback();                    
                    // print_var($sms);die; 
                    /***********end sms send*********************/
                    #------------sws push------------------
                    $sws_whare = ['apply_license_id'=>$licence['id']];
                    $sws = $this->Citizensw_trade_model->getData($sws_whare);
                    //print_var($sws);
                    //die;
                    if($licence['apply_from']=='sws' && !empty($sws))
                    {                    
                        $sw = [];
                        $sw['sw_status']= 11 ; 
                        $sw['application_statge']= 11 ; 
                        $sw['amount']= $charg->total_charge; 
                        $sw['arrear_amount']= $charg->arear_amount;
                        $sw['denial_amount']= $denialAmount; 
                        $sw['rejection_fine']= $charg->penalty ; 
                        $sw['total_amount']= $charg->total_charge + $charg->arear_amount + $denialAmount + $charg->penalty ; 
                        $where_sw = ['apply_license_id'=>$licence['id'],'id'=> $sws['id']];                            
                        $this->Citizensw_trade_model->updateData($sw,$where_sw);

                        $push_sw=array();
                        $ulb_mstr_id = $_SESSION['ulb_dtl']['ulb_mstr_id']; 
                        $path= base_url('citizenPaymentReceipt/view_trade_transaction_receipt/' . $ulb_mstr_id . '/' . md5($licence['id']) . '/' . $transaction_id);
                        $push_sw['application_stage']=11;
                        $push_sw['Status']='Payment Paid status';
                        $push_sw['acknowledgment_no']=$licence['application_no'];
                        $push_sw['service_type_id']=$sws['service_id'];
                        $push_sw['caf_unique_no']=$sws['caf_no'];
                        $push_sw['department_id']=$sws['department_id'];
                        $push_sw['Swsregid']=$sws['cust_id'];
                        $push_sw['payable_amount ']=$transact_arr['paid_amount']+$transact_arr['penalty'];
                        $push_sw['payment_validity']='';
                        $push_sw['payment_other_details']='';
                        $push_sw['certificate_url']=$path;
                        $push_sw['approval_date']=date('Y-m-d H:i:s');
                        $push_sw['expire_date']=date('Y-m-d H:i:s');
                        $push_sw['licence_no']=$licence['license_no'];
                        $push_sw['certificate_no']=$licence['provisional_license_no'];
                        $push_sw['customer_id']=$sws['cust_id'];
                        $post_url = getenv('single_indow_push_url');
                        $http = getenv('single_indow_push_http');
                        $resp = httpPost($post_url,$push_sw,$http);
                        // print_var($resp);
                        $respons_data=[];
                        $respons_data['apply_license_id']=$licence['id'];
                        $respons_data['response_msg']= json_encode(['url'=>$http.'/'.$post_url,
                                                                    'data'=>$push_sw]);
                        $respons_data['tbl_single_window_id']=$sws['id'];
                        $respons_data['emp_id']=$emp_id;
                        $respons_data['response_status']=json_encode($resp);
                        $this->Citizensw_trade_model->insertResponse($respons_data);
                    }           
                    #--------------------------------------
                    $this->dbSystem->transBegin();
                    if((isset($transaction_id) && $transaction_id) && isset($inputs['apply_from']) && strtolower($inputs['apply_from'])=='tc')
                    {                        
                        $vistingRepostInput = tradeTranVisit($licence,$transaction_id,$this->request->getVar());           
                        $visiting_dtl_id = $this->model_visiting_dtl->insertdetail($vistingRepostInput);
                    }

                    if($this->db->transStatus() === FALSE)
                    {
                        $this->db->transRollback();
                        $this->dbSystem->transRollback();
                        flashToast("applylicence", "Something errordue to payment!!!");     
                        if(isset($inputs['apply_from']) && strtolower($inputs['apply_from'])=='tc')
                        {
                            return $this->response->redirect(base_url('tradeapplylicenceMobile/ApplyNewLicenseMobi/'.md5($licence['id']) ));
                        }                   
                        return $this->response->redirect(base_url('Trade_DA/view_application_details/'.md5($licence['id'])));
                        
                    }
                    else
                    {
                        $this->db->transCommit();
                        $this->dbSystem->transCommit();
                        if($aplication_type["id"]<>4)
                        {
                            if(isset($inputs['apply_from']) && strtolower($inputs['apply_from'])=='tc')
                            {
                                return $this->response->redirect(base_url('mobitradeapplylicence/view_transaction_receipt/'.md5($licence['id']).'/'.md5($transaction_id)));
                            }
                            return $this->response->redirect(base_url('tradeapplylicence/view_transaction_receipt/'.md5($licence['id']).'/'.md5($transaction_id)));
                        }
                        else
                        {
                            if(isset($inputs['apply_from']) && strtolower($inputs['apply_from'])=='tc')
                            {
                                return $this->response->redirect(base_url('mobitradeapplylicence/trade_licence_view/'.md5($licence['id'])));
                            }
                            return $this->response->redirect(base_url('tradeapplylicence/trade_licence_view/'.md5($licence['id'])));
                        }
                    }
                }
                
                else
                {
                    print_var($data);
                    //return view("trade/Reports/checque_bounce_payment",$data);
                }
            }
            

        }
        else
        {

        }
    }

    public function extraChargePayment($md5_licence_id){
        $sql_licence = " select * from tbl_apply_licence where md5(id::text) = '$md5_licence_id' ";
        $licence = $this->model_view_trade_licence->row_query($sql_licence, array())[0] ?? [];
        if($this->request->getMethod()=="post"){
            $inputs = $this->request->getVar();
            $emp_id = session()->get('emp_details')['user_mstr_id'] ?? null;
            if($licence["is_fild_verification_charge"]=="t"){
                $transact_arr = array();

                $transact_arr['related_id'] = $licence['id'];
                $transact_arr['ward_mstr_id'] = $licence["ward_mstr_id"];
                $transact_arr['transaction_type'] = "Charge For Increase Area";
                $transact_arr['transaction_date'] = date('Y-m-d');
                $transact_arr['payment_mode'] = $inputs['payment_mode'];
                $transact_arr['paid_amount'] = $licence["exrta_charge"];
                $transact_arr['penalty'] = 0;

                if (!in_array(strtoupper($inputs['payment_mode']),['CASH',"ONLINE","UPI","CARD"])) {
                    $transact_arr['status'] = 2;
                }
                $transact_arr['emp_details_id'] = $emp_id;
                $transact_arr['created_on'] = date('Y-m-d H:i:s');
                $transact_arr['ip_address'] = getClientIp();

                $trafinerebate = array();  // penalty insert
                $trafinerebate['head_name'] = 'Delay Apply License';
                $trafinerebate['amount'] = 0;
                $trafinerebate['value_add_minus'] = 'Add';
                $trafinerebate['created_on'] = date('Y-m-d H:i:s');

                $chq_arr = array();
                $chq_arr['cheque_no'] = $inputs['chq_no']??"";
                $chq_arr['cheque_date'] = $inputs['chq_date']??"";
                $chq_arr['bank_name'] = $inputs['bank_name']??"";
                $chq_arr['branch_name'] = $inputs['branch_name']??"";
                $chq_arr['emp_details_id'] = $emp_id;
                $chq_arr['created_on'] = date('Y-m-d H:i:s');
                
                $this->trade->transBegin();                
                $this->dbSystem->transBegin();

                $transaction_id = $this->TradeTransactionModel->insertPayment($transact_arr);
                
                $trafinerebate['transaction_id'] = $transaction_id;                
                $chq_arr['transaction_id'] = $transaction_id;

                $this->model_trade_transaction_fine_rebet_details->fine_rebet_details($trafinerebate);
                if ($transact_arr['status']  != 1) {                    
                    $this->TradeChequeDtlModel->insertdata($chq_arr);
                }
                $sql_update_payment_status = " update tbl_apply_licence set is_fild_verification_charge =  false,exrta_charge=0 where id = $licence[id]";
                $this->model_view_trade_licence->row_query($sql_update_payment_status);

                #--------------------------------------
                if ((isset($transaction_id) && $transaction_id) && isset($inputs['apply_from']) && strtolower($inputs['apply_from']) == 'tc') {
                    $vistingRepostInput = tradeTranVisit($licence, $transaction_id, $this->request->getVar());
                    $visiting_dtl_id = $this->model_visiting_dtl->insertdetail($vistingRepostInput);
                }

                if ($this->trade->transStatus() === FALSE) {
                    $this->trade->transRollback();
                    $this->dbSystem->transRollback();
                    flashToast("message", "Something errordue to payment!!!");
                    return redirect()->back();
                } else {
                    $this->trade->transCommit();
                    $this->dbSystem->transCommit();
                    flashToast("message","Payment Done;");
                    if ($licence["id"] <> 4) {
                        if (isset($inputs['apply_from']) && strtolower($inputs['apply_from']) == 'tc') {
                            return $this->response->redirect(base_url('mobitradeapplylicence/view_transaction_receipt/' . md5($licence['id']) . '/' . md5($transaction_id)));
                        }
                        return $this->response->redirect(base_url('tradeapplylicence/view_transaction_receipt/' . md5($licence['id']) . '/' . md5($transaction_id)));
                    } else {
                        if (isset($inputs['apply_from']) && strtolower($inputs['apply_from']) == 'tc') {
                            return $this->response->redirect(base_url('mobitradeapplylicence/trade_licence_view/' . md5($licence['id'])));
                        }
                        return $this->response->redirect(base_url('tradeapplylicence/trade_licence_view/' . md5($licence['id'])));
                    }
                }
            }else{
                flashToast("message","There is no any area increase charge");
                return redirect()->back();
            }
        }
        return redirect()->back();
    }


    public function getcharge($args=array())
    {
        return (new \App\Controllers\TradeCitizen())->getcharge($args);
        if(!empty($args) || $this->request->getMethod()=="post")
        {
            $data=array();
            if(!empty($args))
                $inputs = $args;
            else
                $inputs = arrFilterSanitizeString($this->request->getVar());  

            return $this->con_tradeapplylicence->getcharge($inputs);

            $data['area_in_sqft']=(float)$inputs['areasqft'];
            $data['application_type_id']=$inputs['applytypeid'];
            $data['firm_date']=$inputs['estdate'];
            
            
            # Date of firm establishment/expiry date
            
            
            $data['tobacco_status']=$inputs['tobacco_status'];
            $data['timeforlicense']=$inputs['licensefor'];
            $data['curdate']=date("Y-m-d");
            $denial_amount_month=0;
            $count = $this->tradelicenceratemodel->getrate($data);
            $rate=$count['rate']*$data['timeforlicense'];
            $vDiff = abs(strtotime($data['curdate']) - strtotime($data['firm_date'])); // here abs in case theres a mix in the dates
            $vMonths = ceil($vDiff / (30*60*60*24)); // number of seconds in a month of 30 days
            if($vMonths>0)
            {
                $denial_amount_month=100+(($vMonths)*20);
                # In case of ammendment no denial amount
                if($data['application_type_id']==3)
                $denial_amount_month=0;
                    
            }
            $total_denial_amount=$denial_amount_month+$rate;

            # Check If Any cheque bounce charges
            if(isset($inputs['apply_licence_id'], $inputs['apply_licence_id']))
            {
                $penalty=$this->TradeTransactionModel->getChequeBouncePenalty($inputs['apply_licence_id']);
                $denial_amount_month+=$penalty;
                $total_denial_amount+=$penalty;
            }

            if ($count)
            {
                $response = ['response'=>true, 'rate'=>$rate, 'penalty'=>$denial_amount_month, 'total_charge'=>$total_denial_amount , 'rate_id'=> $count['id']];            
            }
            else
            {
                $response = ['response'=>false];
            }
            //echo $count;
            return json_encode($response);
            
        }
    
    }


    public function tobaccogetcharge()
    {
        
        if($this->request->getMethod()=="post")
        {
            $data=array();
            $inputs = arrFilterSanitizeString($this->request->getVar());  
            $data['area_in_sqft']=(float)$inputs['areasqft'];
            $data['application_type_id']=$inputs['applytypeid'];
            $data['firm_date']=$inputs['estdate'];
            $data['tobacco_status']=1;
            $data['timeforlicense']=$inputs['licensefor'];
            $data['curdate']=date("Y-m-d");
            $denial_amount_month=0;
            $count = $this->tradelicenceratemodel->getrate($data);
            $rate=$count['rate']*$data['timeforlicense'];
            $vDiff = abs(strtotime($data['curdate']) - strtotime($data['firm_date'])); // here abs in case theres a mix in the dates
                    $vMonths = ceil($vDiff / (30*60*60*24)); // number of seconds in a month of 30 days
                if($vMonths>0)
                {
                    $denial_amount_month=100+(($vMonths)*20);
                    
                }
                
                    $total_denial_amount=$denial_amount_month+$rate;
            


            if ($count)
            {
            $response = ['response'=>true, 'rate'=>$rate, 'penalty'=>$denial_amount_month, 'total_charge'=>$total_denial_amount];            
            } 
            else 
            {
                $response = ['response'=>false];
            }
            //echo $count;
            return json_encode($response);
            
        }
    

    }

    public function dinial_charge($notice_date=null,$current_date=null)
    {
        if($this->request->getMethod()=="post")
        {

            $inputs = arrFilterSanitizeString($this->request->getVar());
            $notice_date =  isset($inputs['notice_date'])?date('Y-m-d',strtotime($inputs['notice_date'])):date('Y-m-d');
            $current_date= isset($inputs['current_date'])?date('Y-m-d',strtotime($inputs['current_date'])):date('Y-m-d');
            echo $amount=getDenialAmountTrade( $notice_date,$current_date);
            die();
            // return json_encode(['amount'=>$amount,'response'=>true]);
        }
        // return $amount=getDenialAmountTrade( $notice_date,$current_date);
    }


    public function individualUserPendingAppList()
    {
        $data=[];
        $sql_user=" select * 
                    FROM dblink('host=".env('db.pgsql.hname')." port=".env('db.pgsql.port')." user=".env('db.pgsql.uname')." password=".env('db.pgsql.pass')." dbname=db_system'::text,
                        'SELECT 
                                tbl_emp_details.emp_name,tbl_user_type_mstr.user_type,tbl_emp_details.id as emp_id
                        FROM  tbl_emp_details 
                        join tbl_user_mstr on tbl_user_mstr.id = tbl_emp_details.user_mstr_id
                        join tbl_user_type_mstr on tbl_user_type_mstr.id = tbl_emp_details.user_type_mstr_id 
                            and tbl_user_type_mstr.status=1
                        where tbl_emp_details.user_type_mstr_id in(20,19,18,17) 
                            and tbl_emp_details.status=1  and tbl_user_mstr.lock_status=0 
                        '::text) 
                        tbl_consumer_license 
                        (
                             emp_name character varying(255),
                            user_type text,emp_id bigint
                        )
        ";
        $data['user_list']=$this->model_view_trade_licence->row_query($sql_user);
        $sql_ward = "select * from view_ward_mstr where status = 1 ";
        $data['ward_list']=$this->model_view_trade_licence->row_query($sql_ward);
        $data['ward_mstr_id'] = $this->request->getVar('ward_mstr_id')??'';
        $data['emp_id'] = $this->request->getVar('emp_id')??'';
        $data['lecence_no'] = $this->request->getVar('lecence_no')??'';
        $where = "";
        $select = "";
        $join  ="";
        if($data['ward_mstr_id'])
        {
            $where.=" AND tbl_apply_licence.ward_mstr_id = ".$data['ward_mstr_id'];   
        }
        if($data['emp_id'])
        {
            $where.=" AND cl.emp_id = ".$data['emp_id']; 
        }
        if($data['lecence_no'])
        {
            $select = ",old.license_no ";
            $join  =" left join tbl_apply_licence old on old.update_status = tbl_apply_licence.id ";
            $where.=" AND old.license_no = ".$data['lecence_no'];
        }

        $sql_level_pending = " with cl as (
                                    select * 
                                    FROM dblink('host=".env('db.pgsql.hname')." port=".env('db.pgsql.port')." user=".env('db.pgsql.uname')." password=".env('db.pgsql.pass')." dbname=db_system'::text,
                                        'SELECT tbl_ward_permission.emp_details_id, tbl_ward_permission.ward_mstr_id, 
                                                tbl_emp_details.emp_name,tbl_user_type_mstr.user_type,
                                                tbl_emp_details.user_type_mstr_id,tbl_emp_details.id as emp_id
                                        FROM tbl_ward_permission 
                                        join tbl_emp_details on tbl_ward_permission.emp_details_id = tbl_emp_details.id 
                                        join tbl_user_mstr on tbl_user_mstr.id = tbl_emp_details.user_mstr_id
                                        join tbl_user_type_mstr on tbl_user_type_mstr.id = tbl_emp_details.user_type_mstr_id 
                                            and tbl_user_type_mstr.status=1 and tbl_user_mstr.lock_status=0 
                                        where tbl_emp_details.user_type_mstr_id in(20,19,18,17) 
                                            and tbl_emp_details.status=1 and tbl_ward_permission.status =1
                                        '::text) 
                                        tbl_consumer_license 
                                        (
                                            emp_details_id character varying(255), ward_mstr_id bigint, emp_name character varying(255),
                                            user_type text,user_type_mstr_id bigint,emp_id bigint
                                        )
                                    ),
                                level_pending as (
                                    select apply_licence_id, receiver_user_type_id
                                    from tbl_level_pending 
                                    join tbl_apply_licence on tbl_level_pending.apply_licence_id = tbl_apply_licence.id
                                    where tbl_level_pending.status=1 and tbl_apply_licence.status=1 
                                )
                                select
                                    count(distinct(level_pending.apply_licence_id)) as total_pending,
                                    cl.emp_name,
                                    cl.emp_id,
                                    cl.user_type $select
                                from level_pending 
                                join tbl_apply_licence on level_pending.apply_licence_id = tbl_apply_licence.id
                                join  cl  on cl.ward_mstr_id = tbl_apply_licence.ward_mstr_id 
                                    and cl.user_type_mstr_id =level_pending.receiver_user_type_id 
                                $join
                                where tbl_apply_licence.status=1  and tbl_apply_licence.update_status =0 $where
                                group by cl.emp_id, cl.emp_name,cl.user_type $select
                                order by cl.emp_id
                                "; 
        $data['level_pending'] = $this->model_datatable->getDatatable($sql_level_pending);
        // print_var($data['level_pending']);
        return view('trade/Reports/individualUserPendingAppList',$data);
    }


    public function individualUserPendingAppListExcel($ward_id = "ALL",$emp_id="ALL",$licence = "@@@")
    {
        try{
            $data['ward_mstr_id'] = $ward_id;
            $data['emp_id'] = $emp_id;
            $data['lecence_no'] = $licence;
            $where = "";
            $select = "";
            $join  ="";
            if($data['ward_mstr_id']!='ALL')
            {
                $where.=" AND tbl_apply_licence.ward_mstr_id = ".$data['ward_mstr_id'];   
            }
            if($data['emp_id']!='ALL')
            {
                $where.=" AND cl.emp_id = ".$data['emp_id']; 
            }
            if($data['lecence_no']!="@@@")
            {
                $select = ",old.license_no ";
                $join  =" left join tbl_apply_licence old on old.update_status = tbl_apply_licence.id ";
                $where.=" AND old.license_no = ".$data['lecence_no']; 
            }
    
            $sql_level_pending = " with cl as (
                                        select * 
                                        FROM dblink('host=".env('db.pgsql.hname')." port=".env('db.pgsql.port')." user=".env('db.pgsql.uname')." password=".env('db.pgsql.pass')." dbname=db_system'::text,
                                            'SELECT tbl_ward_permission.emp_details_id, tbl_ward_permission.ward_mstr_id, 
                                                    tbl_emp_details.emp_name,tbl_user_type_mstr.user_type,
                                                    tbl_emp_details.user_type_mstr_id,tbl_emp_details.id as emp_id
                                            FROM tbl_ward_permission 
                                            join tbl_emp_details on tbl_ward_permission.emp_details_id = tbl_emp_details.id 
                                            join tbl_user_mstr on tbl_user_mstr.id = tbl_emp_details.user_mstr_id
                                            join tbl_user_type_mstr on tbl_user_type_mstr.id = tbl_emp_details.user_type_mstr_id 
                                                and tbl_user_type_mstr.status=1
                                            where tbl_emp_details.user_type_mstr_id in(20,19,18,17)   and tbl_user_mstr.lock_status=0 
                                                and tbl_emp_details.status=1 and tbl_ward_permission.status =1
                                            '::text) 
                                            tbl_consumer_license 
                                            (
                                                emp_details_id character varying(255), ward_mstr_id bigint, emp_name character varying(255),
                                                user_type text,user_type_mstr_id bigint,emp_id bigint
                                            )
                                        ),
                                    level_pending as (
                                        select apply_licence_id, receiver_user_type_id
                                        from tbl_level_pending 
                                        join tbl_apply_licence on tbl_level_pending.apply_licence_id = tbl_apply_licence.id
                                        where tbl_level_pending.status=1 and tbl_apply_licence.status=1 
                                    )
                                    select
                                        count(distinct(level_pending.apply_licence_id)) as total_pending,
                                        cl.emp_name,
                                        cl.emp_id,
                                        cl.user_type $select
                                    from level_pending 
                                    join tbl_apply_licence on level_pending.apply_licence_id = tbl_apply_licence.id
                                    join  cl  on cl.ward_mstr_id = tbl_apply_licence.ward_mstr_id 
                                        and cl.user_type_mstr_id =level_pending.receiver_user_type_id 
                                    $join
                                    where tbl_apply_licence.status=1  and tbl_apply_licence.update_status =0 $where
                                    group by cl.emp_id, cl.emp_name,cl.user_type $select
                                    order by cl.emp_id
                                    ";            

            $fetchSql = $sql_level_pending;
            
            $result = $this->model_datatable->getRecords($fetchSql,false);
            $records = [];
            if ($result) 
            {
                foreach ($result AS $key=>$tran_dtl) 
                {
                    

                    $records[] = [                        
                        'emp_name'=>$tran_dtl['emp_name'],
                        'user_type'=>$tran_dtl['user_type'],
                        'total_pending'=>$tran_dtl['total_pending'],                        
                        
                    ];

                }
            }
            phpOfficeLoad();
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $activeSheet = $spreadsheet->getActiveSheet();
                            $activeSheet->setCellValue('A1', 'User Name');
                            $activeSheet->setCellValue('B1', 'User Type');
                            $activeSheet->setCellValue('C1', 'Total Pending');


                            $activeSheet->fromArray($records, NULL, 'A2');
            $filename = "UserWisePendingReport".date('Ymd-hisa').".xlsx";
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="'.$filename.'"');
            header('Cache-Control: max-age=0');
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            //$writer->save(APPPATH.'/hello world.xlsx');
            $writer->save('php://output');
        }
        catch (Exception $e)
        {
            echo $e->getMessage();
            die;
        }
    }

    public function licence_summery()
    {
        $data=[];
        $where="";
        $data['from_date']='';
        $data['to_date']='';
        if($this->request->getMethod()=='post')
        {
            $inputs = arrFilterSanitizeString($this->request->getVar());
            $data['from_date']=$inputs['from_date'];
            $data['to_date']=$inputs['to_date'];
            $where=" AND tbl_apply_licence.apply_date BETWEEN '".$inputs['from_date']."' AND '".$inputs['to_date']."'";

        }
        $sql = "select distinct(application_type) as application_type,
                    count(distinct(tbl_apply_licence.id)) as total,
                    count(case when tbl_apply_licence.valid_upto::date < now()::date 
                        then tbl_apply_licence.id end
                        ) as expire_licence,
                    count(case when tbl_apply_licence.valid_upto::date >= now()::date 
                        then tbl_apply_licence.id end
                        ) as valid_licence, 
                    count(case when tbl_apply_licence.valid_upto isnull 
                        then tbl_apply_licence.id end
                        ) as unknow_validity, 
                    count(case when tbl_apply_licence.holding_no isnull and tbl_apply_licence.valid_upto ISNull
                        then tbl_apply_licence.id end
                        ) as ano_holding_no,
                    count(case when tbl_apply_licence.holding_no NOTNULL and tbl_apply_licence.valid_upto ISNull
                        then tbl_apply_licence.id end
                        ) as aholding_no,
                    count(case when tbl_apply_licence.holding_no isnull and tbl_apply_licence.valid_upto NotNull
                        then tbl_apply_licence.id end
                       ) as no_holding_no,
                    count(case when tbl_apply_licence.holding_no NOTNULL  AND tbl_apply_licence.valid_upto NotNull
                        then tbl_apply_licence.id end
                       ) as holding_no,
                    count(case when tbl_apply_licence.tobacco_status = 1 
                        then tbl_apply_licence.id end
                        ) as tobacco_status
                from tbl_apply_licence
                join tbl_application_type_mstr on tbl_application_type_mstr.id = tbl_apply_licence.application_type_id
                where tbl_apply_licence.status = 1 and (update_status = 0 or update_status isnull)
                    $where
                group by tbl_application_type_mstr.application_type
        ";
        $data['application_type'] = $this->TradeChequeDtlModel->row_sql($sql);

        $sql="with temp as (
                    select case when category_type='Others' Or category_type isnull then 'Others' else  category_type end as category_type,
                            count(distinct(tbl_apply_licence.id)) as total_licences, 
                        count(case when tbl_apply_licence.holding_no isnull 
                            then tbl_apply_licence.id end
                            ) as no_holding_no,
                        count(case when tbl_apply_licence.holding_no NOTNULL 
                            then tbl_apply_licence.id end
                            ) as holding_no
                    from tbl_apply_licence
                    left join tbl_category_type on tbl_category_type.id = tbl_apply_licence.category_type_id
                    where tbl_apply_licence.status = 1 
                        and (update_status = 0 or update_status isnull) and tbl_apply_licence.valid_upto NotNull
                        $where
                    group by tbl_category_type.category_type
                )
                select distinct(category_type),
                    sum(total_licences) as total_licences,
                    sum(no_holding_no) as no_holding_no,
                    sum(holding_no) as holding_no
                from temp
                group by category_type
        ";
        $data['category_type'] = $this->TradeChequeDtlModel->row_sql($sql);

        $sql="select distinct(tbl_ownership_type_mstr.ownership_type) AS ownership_type,
                    count(distinct(tbl_apply_licence.id)) as total_licences
                from tbl_apply_licence
                left join tbl_ownership_type_mstr on tbl_ownership_type_mstr.id = tbl_apply_licence.ownership_type_id
                where tbl_apply_licence.status = 1 
                    and (update_status = 0 or update_status isnull) and tbl_apply_licence.valid_upto NotNull
                    $where
                group by tbl_ownership_type_mstr.ownership_type
        ";
        $data['ownership_type'] = $this->TradeChequeDtlModel->row_sql($sql);

        $sql = "select 
                    count(distinct(tbl_apply_licence.id)) as total_licences,
                    count( CASE WHEN tbl_apply_licence.pending_status = 1 
                        THEN  tbl_apply_licence.id
                        END ) AS pending_level,
                    count( CASE WHEN tbl_apply_licence.pending_status IN(0,2) OR tbl_apply_licence.pending_status ISNULL 
                        THEN  tbl_apply_licence.id
                        END ) AS pending_backoffice,
                    count( CASE WHEN tbl_apply_licence.pending_status =5  
                        THEN  tbl_apply_licence.id
                        END ) AS approved,
                        count( CASE WHEN tbl_apply_licence.pending_status =4  
                        THEN  tbl_apply_licence.id
                        END ) AS rejected
                from tbl_apply_licence
                where tbl_apply_licence.status = 1 
                    and (update_status = 0 or update_status isnull) and tbl_apply_licence.valid_upto NotNull
                    $where
        ";
        $data['level_pending'] = $this->TradeChequeDtlModel->row_sql($sql);
        // print_var($data);die;
        return view('trade/reports/licence_summery',$data);
    }
    public function BTCExportExcel($btn_search=null,$from_date=null,$to_date=null,$ward_id=null)
    {    
        $where=" WHERE 1=1 ";    
        if($from_date==null)
        {
            $from_date=date('Y-m-d');            
        }
        if($to_date==null)
        {
            $to_date=date('Y-m-d');            
        }
        if($ward_id==null || $ward_id=='ALL')
        {
            $ward_id='';            
        }
        try
        {
            if ($btn_search == 'BY') {
                $where.=" AND boc.forward_date between '".$from_date."' and '".$to_date."'";
                if ($ward_id != '') 
                {
                    $where .= " AND  boc.ward_id = ".$ward_id;
                }
            }
            $sql="SELECT 
                    boc.ward_no,
                    CONCAT(CHR(39),boc.application_no,CHR(39)) AS application_no,                                    
                    boc.firm_name,
                    tbl_application_type.application_type,
                    boc.forward_date,     
                    boc.remarks,
                    boc.applicant_name,
                    boc.mobile_no,
                    boc.address,
                    view_user_type_mstr.user_type
                FROM view_backtocitizenlist boc  
                JOIN view_user_type_mstr ON view_user_type_mstr.id = boc.sent_by_user_type_id
                INNER JOIN tbl_application_type_mstr tbl_application_type 
                    ON tbl_application_type.id=boc.application_type_id " . $where;   
            $result = $this->model_datatable->getRecords($sql,false);
            $records = [];
            if ($result) 
            {
                foreach ($result AS $key=>$tran_dtl) 
                {
                    

                    $records[] = [                        
                        'ward_no'=>$tran_dtl['ward_no'],
                        'application_no'=>$tran_dtl['application_no'], 
                        'firm_name'=>$tran_dtl['firm_name'],
                        'applicant_name'=>$tran_dtl['applicant_name'],
                        'mobile_no'=>$tran_dtl['mobile_no'],
                        'address'=>$tran_dtl['address'],
                        'application_type'=>$tran_dtl['application_type'],   
                        'forward_date'=>$tran_dtl['forward_date'],
                        'remarks'=>$tran_dtl['remarks'],  
                        'user_type'=>$tran_dtl['user_type'],                                           
                    ];

                }
            }
            phpOfficeLoad();
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $activeSheet = $spreadsheet->getActiveSheet();
                            $activeSheet->setCellValue('A1', 'Ward No.');
                            $activeSheet->setCellValue('B1', 'Application No.');
                            $activeSheet->setCellValue('C1', 'Firm Name');
                            $activeSheet->setCellValue('D1', 'Owner Name');
                            $activeSheet->setCellValue('E1', 'Mobile No');
                            $activeSheet->setCellValue('F1', 'Address');
                            $activeSheet->setCellValue('G1', 'Application Type');    
                            
                            $activeSheet->setCellValue('H1', 'Forward Date');
                            $activeSheet->setCellValue('I1', 'Remarks');
                            $activeSheet->setCellValue('J1', 'User Type');
                            
                            $activeSheet->fromArray($records, NULL, 'A3');
            $filename = "Trade_BTC_report_".date('Ymd-hisa').".xlsx";
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="'.$filename.'"');
            header('Cache-Control: max-age=0');
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            //$writer->save(APPPATH.'/hello world.xlsx');
            $writer->save('php://output');
        }
        catch(Exception $e)
        {
            print_r($e);
        }
    }
    public function levelwisependingReport(){
        $data=[];
        if (isset($this->request->getVar()['from_date']) && isset($this->request->getVar()['to_date'])) {
            ini_set('memory_limit', '-1');
            $from_date = $this->request->getVar("from_date");
            $to_date = $this->request->getVar("to_date");
            if ($from_date != "" && $to_date != "") {
                $whereDateRange = "AND date(tbl_apply_licence.apply_date) between '$from_date' AND '$to_date'";
                $whereDateRange2 = "AND date(al.apply_date) between '$from_date' AND '$to_date'";
            }
            $limit="";
            $whereQuery=$whereDateRange;
            //total application
            $totalcount="SELECT count(*) as totalcount from tbl_apply_licence where status=1 and pending_status NOT IN (2,4) and payment_status=1 $whereDateRange";
            $totalcount = $this->trade->query($totalcount);
            $totalcount = $totalcount->getRow()->totalcount;
            $data['from_date']=$from_date;
            $data['to_date']=$to_date;
            $data['total_app']=$totalcount;

            //total pending
            $totalcount="SELECT count(*) as totalcount from tbl_apply_licence where status=1 and pending_status NOT IN (2,4) and payment_status=1 $whereDateRange";
            $totalcount = $this->trade->query($totalcount);
            $totalcount = $totalcount->getRow()->totalcount;

            $pendingsql='WITH bo_pending AS (SELECT *,
                (select txn.transaction_date from tbl_transaction txn where txn.related_id=tbl_apply_licence.id AND txn.status= 1 limit 1) as payment_date
                    FROM "tbl_apply_licence" WHERE "status" = 1 AND
                    "pending_status" = 0 AND "payment_status" = 1  AND apply_from IN ('."'EXIST','JSK','OldData','TC','sws'".') AND "document_upload_status" = 0 '.$whereDateRange.'),
                    levelpending as (SELECT 
                        tbl_level_pending.id,tbl_level_pending.apply_licence_id,tbl_level_pending.sender_user_type_id,  
                        tbl_level_pending.receiver_user_type_id,tbl_level_pending.forward_date, 
                        tbl_level_pending.forward_time,LAG(forward_date) OVER (ORDER BY forward_date) AS prev_date,
                        forward_date- LAG(forward_date) OVER (ORDER BY forward_date) AS date_difference,
                        tbl_level_pending.created_on,tbl_level_pending.status,tbl_level_pending.remarks, 
                        tbl_level_pending.verification_status from tbl_level_pending),
                    dapending as (SELECT lp.* as da_total_pending FROM levelpending lp join tbl_apply_licence 
                    al on al.id=lp.apply_licence_id where lp.receiver_user_type_id=17 and lp.sender_user_type_id=0 and lp.forward_date IS NULL and al.status = 1 and al.pending_status != 5
                    '.$whereDateRange2.'),
                    tax_daroga AS (
                                    SELECT lp.* as td_total_pending FROM tbl_level_pending lp join tbl_apply_licence al on al.id=lp.apply_licence_id
                                    where lp.receiver_user_type_id=20 and lp.sender_user_type_id=17 
                                    and lp.forward_date IS NULL and al.status = 1 and al.pending_status != 5 '.$whereDateRange2.'),
                    section_head AS (
                    SELECT lp.* as sh_total_pending FROM tbl_level_pending lp join tbl_apply_licence al on al.id=lp.apply_licence_id
                    where lp.receiver_user_type_id=18 and lp.sender_user_type_id=20 
                    and lp.forward_date IS NULL and al.status = 1 and al.pending_status != 5 '.$whereDateRange2.'),
                    executive_officer AS (
                                    SELECT lp.* as eo_total_pending FROM tbl_level_pending lp join tbl_apply_licence al on al.id=lp.apply_licence_id
                                    where lp.receiver_user_type_id=19 and lp.sender_user_type_id=18 
                                    and lp.forward_date IS NULL and al.status = 1 and al.pending_status != 5 '.$whereDateRange2.')
                    
                    select (SELECT count(*) FROM ( SELECT (now()::date - bo_pending.payment_date) AS difference FROM bo_pending ) AS boprocess
                    WHERE boprocess.difference <= 2) as boprocesslist,
                    (SELECT count(*) FROM bo_pending) as bopendinglist,
                    (select (SELECT count(*) FROM ( SELECT (now()::date - dapending.created_on::date)
                    AS difference FROM dapending) AS daprocess WHERE daprocess.difference <= 3)) as daprocesslist,
                    (SELECT count(*) FROM dapending ) as dapendinglist,
                (select (SELECT count(*) FROM ( SELECT (now()::date - tax_daroga.created_on::date) AS difference FROM tax_daroga) 
                    AS tdpending WHERE tdpending.difference <= 10)) as tdprocesslist, (SELECT count(*) FROM tax_daroga) as tdpendinglist,
                (select (SELECT count(*) FROM ( SELECT (now()::date - section_head.created_on::date) AS difference FROM section_head) 
                    AS shpending WHERE shpending.difference <= 3)) as shprocesslist, (SELECT count(*) FROM section_head) as shpendinglist,
                    (select (SELECT count(*) FROM ( SELECT (now()::date - executive_officer.created_on::date) AS difference FROM executive_officer)
                    AS eopending WHERE eopending.difference <= 3)) as eoprocesslist, (SELECT count(*) FROM executive_officer) as eopendinglist
            ';
            $query = $this->trade->query($pendingsql);
            $totalcountdata = $query->getResultArray()[0];
            // dd($totalcountdata);
            // dd($totalcount_pending_ba);
            //BA
            $boprocesslist=$totalcountdata['boprocesslist'];
            $bopendinglist=$totalcountdata['bopendinglist'];
            $batotal=$bopendinglist;
            $data['batotal']=$bopendinglist-$boprocesslist;   //pending
            if($data['batotal']<0)
            {
                $data['batotal']=0;
            }        
            $data['baprocess']=$boprocesslist;
            $available=$data['badone']=$totalcount-($batotal);
            if($data['badone']<0)
            {
                $data['badone']=0;
            }    
            //DA
            $daprocesslist=$totalcountdata['daprocesslist'];
            $dapendinglist=$totalcountdata['dapendinglist'];
            $datotal=$dapendinglist;
            $data['datotal']=$dapendinglist-$daprocesslist;          //da pending
            $data['daprocess']=$daprocesslist;          //da process
            $available=$data['dadone']=$available-($datotal);

            //TD
            $tdprocesslist=$totalcountdata['tdprocesslist'];
            $tdpendinglist=$totalcountdata['tdpendinglist'];
            $tdtotal=$tdprocesslist;
            $data['tdtotal']=$tdpendinglist-$tdprocesslist;          //td pending
            $data['tdprocess']=$tdprocesslist;          //td process
            $available=$data['tddone']=$available-($tdtotal);

            //SH
            $shprocesslist=$totalcountdata['shprocesslist'];
            $shpendinglist=$totalcountdata['shpendinglist'];
            $shtotal=$shpendinglist;
            $data['shtotal']=$shpendinglist-$shprocesslist;          //td pending
            $data['shprocess']=$tdprocesslist;          //td process
            $available=$data['shdone']=$available-($shtotal);

            //EO
            $eoprocesslist=$totalcountdata['eoprocesslist'];
            $eopendinglist=$totalcountdata['eopendinglist'];
            $eototal=$eopendinglist;
            $data['eototal']=$eopendinglist-$eoprocesslist;          //td pending
            $data['eoprocess']=$eoprocesslist;          //td process
            $available=$data['eodone']=$available-($eototal);
        }
        return view('trade/Reports/levelwisetradepending',$data);
    }
    public function levelwisependingReportexport__(){
        if (isset($this->request->getVar()['from_date']) && isset($this->request->getVar()['to_date'])) {
            ini_set('memory_limit', '-1');
            $from_date = $this->request->getVar("from_date");
            $to_date = $this->request->getVar("to_date");
            $filter_type = $this->request->getVar("filter_type");
            if ($from_date != "" && $to_date != "") {
                $whereDateRange = "AND date(tbl_apply_licence.apply_date) between '$from_date' AND '$to_date'";
                $whereDateRange2 = "AND date(al.apply_date) between '$from_date' AND '$to_date'";
            }
            $with = "With levelpending as (SELECT 
            tbl_level_pending.id,tbl_level_pending.apply_licence_id,tbl_level_pending.sender_user_type_id,  
            tbl_level_pending.receiver_user_type_id,tbl_level_pending.forward_date, 
            tbl_level_pending.forward_time,LAG(forward_date) OVER (ORDER BY forward_date) AS prev_date,
            forward_date- LAG(forward_date) OVER (ORDER BY forward_date) AS date_difference,
            tbl_level_pending.created_on,tbl_level_pending.status,tbl_level_pending.remarks, 
            tbl_level_pending.verification_status from tbl_level_pending)";

            $whereQuery = $whereDateRange;
            if($filter_type=="Back Office"){
                $sql='WITH bo_pending AS (SELECT al.*,al.id as aid,view_ward_mstr.ward_no AS ward_no,
                   (select txn.transaction_date from tbl_transaction txn where txn.related_id=al.id AND txn.status= 1 limit 1) as payment_date
                    FROM "tbl_apply_licence" al
                     INNER JOIN view_ward_mstr ON view_ward_mstr.id=al.ward_mstr_id
                     WHERE al.status = 1 AND view_ward_mstr.status=1 AND
                    al.pending_status = 0 AND al.payment_status = 1 AND al.document_upload_status = 0 '.$whereDateRange2.')
                    select * from bo_pending;';
            }
            if($filter_type=="Dealing Assistant"){
                $sql=$with.',
                    dapending as (SELECT lp.* as da_total_pending,view_ward_mstr.ward_no AS ward_no,al.id as aid,al.application_no,al.apply_date
                     FROM levelpending lp join tbl_apply_licence al on al.id=lp.apply_licence_id
                     INNER JOIN view_ward_mstr ON view_ward_mstr.id=al.ward_mstr_id
                     where lp.receiver_user_type_id=17 and lp.sender_user_type_id=0
                     and lp.forward_date IS NULL '.$whereDateRange2.')
                     select * from dapending';
            }
            if($filter_type=="Tax Daroga"){
                $sql=$with.',
                    tax_daroga AS (
                        SELECT lp.* as td_total_pending,view_ward_mstr.ward_no AS ward_no,al.id as aid,al.application_no,al.apply_date
                        FROM tbl_level_pending lp join tbl_apply_licence al on al.id=lp.apply_licence_id
                        INNER JOIN view_ward_mstr ON view_ward_mstr.id=al.ward_mstr_id
                        where lp.receiver_user_type_id=20 and lp.sender_user_type_id=17 
                        and lp.forward_date IS NULL '.$whereDateRange2.')
                          select * from tax_daroga;';
            }
            if($filter_type=="Section Head"){
                $sql=$with.',
                    section_head AS (
                    SELECT lp.* as sh_total_pending,view_ward_mstr.ward_no AS ward_no,al.id as aid,al.application_no,al.apply_date 
                    FROM tbl_level_pending lp join tbl_apply_licence al on al.id=lp.apply_licence_id
                    INNER JOIN view_ward_mstr ON view_ward_mstr.id=al.ward_mstr_id
                    where lp.receiver_user_type_id=18 and lp.sender_user_type_id=20 
                    and lp.forward_date IS NULL '.$whereDateRange2.')
                     select * from section_head;';
            }
            if($filter_type=="Executive Officer"){
                $sql=$with.',
                    executive_officer AS (
                        SELECT lp.* as eo_total_pending,view_ward_mstr.ward_no AS ward_no,al.id as aid,al.application_no,al.apply_date 
                        FROM tbl_level_pending lp join tbl_apply_licence al on al.id=lp.apply_licence_id
                        INNER JOIN view_ward_mstr ON view_ward_mstr.id=al.ward_mstr_id
                        where lp.receiver_user_type_id=19 and lp.sender_user_type_id=18 
                        and lp.forward_date IS NULL '.$whereDateRange2.')
                        select * from executive_officer';
            }
            $records = $this->trade->query($sql);
            $records = $records->getResultArray();
        }
        $data['from_date']=$from_date;
        $data['to_date']=$to_date;
        $data['lists']=$records;
        return view('trade/Reports/pendinglisttrade',$data);
    }

    public function levelwisependingReportexport(){
        if (isset($this->request->getVar()['from_date']) && isset($this->request->getVar()['to_date'])) {
            ini_set('memory_limit', '-1');
            $from_date = $this->request->getVar("from_date");
            $to_date = $this->request->getVar("to_date");
            $filter_type = $this->request->getVar("filter_type");
            if ($from_date != "" && $to_date != "") {
                $whereDateRange = "AND date(tbl_apply_licence.apply_date) between '$from_date' AND '$to_date'";
                $whereDateRange2 = "AND date(al.apply_date) between '$from_date' AND '$to_date'";
            }
            $with = "With levelpending as (SELECT 
            tbl_level_pending.id,tbl_level_pending.apply_licence_id,tbl_level_pending.sender_user_type_id,  
            tbl_level_pending.receiver_user_type_id,tbl_level_pending.forward_date, 
            tbl_level_pending.forward_time,LAG(forward_date) OVER (ORDER BY forward_date) AS prev_date,
            forward_date- LAG(forward_date) OVER (ORDER BY forward_date) AS date_difference,
            tbl_level_pending.created_on,tbl_level_pending.status,tbl_level_pending.remarks, 
            tbl_level_pending.verification_status from tbl_level_pending)";

            $whereQuery = $whereDateRange;
            if($filter_type=="Back Office"){
                $sql='WITH bo_pending AS (SELECT al.*,al.id as aid,view_ward_mstr.ward_no AS ward_no,
                   (select txn.transaction_date from tbl_transaction txn where txn.related_id=al.id AND txn.status= 1 limit 1) as payment_date
                    FROM "tbl_apply_licence" al
                     INNER JOIN view_ward_mstr ON view_ward_mstr.id=al.ward_mstr_id
                     WHERE al.status = 1 AND view_ward_mstr.status=1  AND apply_from IN ('."'EXIST','JSK','OldData','TC','sws'".') AND
                    al.pending_status = 0 AND al.payment_status = 1 AND  al.document_upload_status = 0 '.$whereDateRange2.')
                     (select q.* from (SELECT * FROM ( SELECT *,(now()::date - bo_pending.created_on::date) AS difference FROM bo_pending)
        AS bopending WHERE bopending.difference > 2) as q )';
            }
            if($filter_type=="Dealing Assistant"){
                $sql=$with.',
                    dapending as (SELECT lp.* as da_total_pending,view_ward_mstr.ward_no AS ward_no,al.id as aid,al.application_no,al.apply_date,al.ward_mstr_id
                     FROM levelpending lp join tbl_apply_licence al on al.id=lp.apply_licence_id
                     INNER JOIN view_ward_mstr ON view_ward_mstr.id=al.ward_mstr_id
                     where lp.receiver_user_type_id=17 and lp.sender_user_type_id=0
                     and lp.forward_date IS NULL and al.status = 1 and al.pending_status != 5 '.$whereDateRange2.')
                     (select q.* from (SELECT * FROM ( SELECT *,(now()::date - dapending.created_on::date) AS difference FROM dapending)
        AS dapending WHERE dapending.difference > 3) as q )';
            }
            if($filter_type=="Tax Daroga"){
                $sql=$with.',
                    tax_daroga AS (
                        SELECT lp.* as td_total_pending,view_ward_mstr.ward_no AS ward_no,al.id as aid,al.application_no,al.apply_date,al.ward_mstr_id
                        FROM tbl_level_pending lp join tbl_apply_licence al on al.id=lp.apply_licence_id
                        INNER JOIN view_ward_mstr ON view_ward_mstr.id=al.ward_mstr_id
                        where lp.receiver_user_type_id=20 and lp.sender_user_type_id=17 
                        and lp.forward_date IS NULL and al.status = 1 and al.pending_status != 5 '.$whereDateRange2.')
                        (select q.* from (SELECT * FROM ( SELECT *,(now()::date - tax_daroga.created_on::date) AS difference FROM tax_daroga)
        AS txpending WHERE txpending.difference > 10) as q )';
            }
            if($filter_type=="Section Head"){
                $sql=$with.',
                    section_head AS (
                    SELECT lp.* as sh_total_pending,view_ward_mstr.ward_no AS ward_no,al.id as aid,al.application_no,al.apply_date ,al.ward_mstr_id
                    FROM tbl_level_pending lp join tbl_apply_licence al on al.id=lp.apply_licence_id
                    INNER JOIN view_ward_mstr ON view_ward_mstr.id=al.ward_mstr_id
                    where lp.receiver_user_type_id=18 and lp.sender_user_type_id=20 
                    and lp.forward_date IS NULL and al.status = 1 and al.pending_status != 5 '.$whereDateRange2.')
                     (select q.* from (SELECT * FROM ( SELECT *,(now()::date - section_head.created_on::date) AS difference FROM section_head)
        AS shpending WHERE shpending.difference > 3) as q )';
            }
            if($filter_type=="Executive Officer"){
                $sql=$with.',
                    executive_officer AS (
                        SELECT lp.* as eo_total_pending,view_ward_mstr.ward_no AS ward_no,al.id as aid,al.application_no,al.apply_date ,al.ward_mstr_id
                        FROM tbl_level_pending lp join tbl_apply_licence al on al.id=lp.apply_licence_id
                        INNER JOIN view_ward_mstr ON view_ward_mstr.id=al.ward_mstr_id
                        where lp.receiver_user_type_id=19 and lp.sender_user_type_id=18 
                        and lp.forward_date IS NULL and al.status = 1 and al.pending_status != 5 '.$whereDateRange2.')
                        (select q.* from (SELECT * FROM ( SELECT *,(now()::date - executive_officer.created_on::date) AS difference FROM executive_officer)
        AS eopending WHERE eopending.difference > 3) as q )
        ';
            }
            $records = $this->trade->query($sql);
            $records = $records->getResultArray();
        }
        $data['from_date']=$from_date;
        $data['to_date']=$to_date;
        $data['lists']=$records;
        return view('trade/Reports/pendinglisttrade',$data);
    }

    public function trade_license_list()
    {

        $data = [];
        $data = $data = $this->request->getVar();
        $Session = session();
        $data['ulb_dtl'] = $Session->get('ulb_dtl');
        $ward_sql = " select * from view_ward_mstr where status = 1 and ulb_mstr_id = ? ";
        $data['ward'] = $this->model_view_trade_licence->row_query($ward_sql, array($this->ulb_id));
        $business_sql = "SELECT *FROM tbl_trade_items_mstr where status = 1 order by id asc";
        $data['trade_items'] = $this->trade->query($business_sql)->getResultArray();

        $application_type_sql = "SELECT *FROM tbl_application_type_mstr WHERE status = 1 ORDER by id ASC";
        $data['application_list'] = $this->trade->query($application_type_sql)->getResultArray();

        $ward_mstr_id = $this->request->getVar('ward_mstr_id');
        $nature_of_bussiness = $this->request->getVar('nature_of_bussiness');
        $business_code = $this->request->getVar('business_code');
        $business_code = implode(",", array_map(function ($val) {
            return trim($val);
        }, explode(',', $business_code)));
        $applicaton_type = $this->request->getVar('applicaton_type');

        if ($this->request->getVar()) {
            try {

                $sql = "SELECT tbl_apply_licence.id, view_ward_mstr.ward_no,
                    tbl_apply_licence.license_no,
                    tbl_apply_licence.application_no, 
                    tbl_firm_owner_name.owner_name, 
                    tbl_apply_licence.address, 
                    tbl_firm_owner_name.mobile,
                    tbl_apply_licence.apply_date, 
                    tbl_apply_licence.firm_name, 
                    tbl_apply_licence.area_in_sqft, 
                    tbl_apply_licence.valid_upto,
                    tbl_application_type_mstr.application_type,
                    tbl_trade_items_mstr.trade_item
                FROM 
                    tbl_apply_licence
                LEFT JOIN (
                    select apply_licence_id,string_agg(owner_name,',')owner_name,
                    string_agg(mobile::text,',')mobile
                    from tbl_firm_owner_name
                    group by apply_licence_id
                )tbl_firm_owner_name ON tbl_apply_licence.id = tbl_firm_owner_name.apply_licence_id
                JOIN(
                    select tbl_apply_licence.id,string_agg(trade_code,',')trade_code,string_agg(trade_item,',')trade_item
                    from tbl_apply_licence
                    join tbl_trade_items_mstr on tbl_trade_items_mstr.id=ANY (string_to_array(tbl_apply_licence.nature_of_bussiness, ',')::int[])
                    where 1=1 
                    " . ($nature_of_bussiness && $business_code
                    ? (
                        " AND (
                            ($nature_of_bussiness = ANY (string_to_array(tbl_apply_licence.nature_of_bussiness, ',')::int[]))
                            OR tbl_trade_items_mstr.trade_code = ANY (string_to_array('$business_code', ',')::text[])
                        )"
                    )
                    : (
                        ($nature_of_bussiness ? " AND $nature_of_bussiness = ANY (string_to_array(tbl_apply_licence.nature_of_bussiness, ',')::int[]) " : "")
                        . (($business_code ? " AND tbl_trade_items_mstr.trade_code = ANY (string_to_array('$business_code', ',')::text[]) " : ""))
                    )) . "
                    
                    group by tbl_apply_licence.id
                )tbl_trade_items_mstr ON tbl_apply_licence.id = tbl_trade_items_mstr.id
                LEFT JOIN 
                    view_ward_mstr ON view_ward_mstr.id = tbl_apply_licence.ward_mstr_id
                LEFT JOIN
                    tbl_application_type_mstr ON tbl_apply_licence.application_type_id = tbl_application_type_mstr.id
                WHERE 
                    tbl_apply_licence.status = 1 
                    AND (case when tbl_apply_licence.application_type_id =1  and update_status = 0 then tbl_apply_licence.pending_status=5 
                            when tbl_apply_licence.application_type_id !=1 then update_status = 0 end)=true
                    -- AND tbl_apply_licence.application_type_id !=4  
                        " . ($applicaton_type ? " AND tbl_apply_licence.application_type_id = $applicaton_type " : "") . "                       
                        " . ($ward_mstr_id ? " AND tbl_apply_licence.ward_mstr_id = $ward_mstr_id " : "") . "
                ";

                if (isset($data["export"]) && $data["export"]) {
                    ini_set('memory_limit', '1024M');
                    $result = $this->model_datatable->getRecords($sql, false);

                    $recods = array_map(function ($val) {

                        return [
                            $val["ward_no"],
                            $val["license_no"],
                            $val["owner_name"],
                            $val["firm_name"],
                            $val["mobile"],
                            $val["apply_date"],
                            $val["area_in_sqft"],
                            $val["valid_upto"],
                            $val["application_type"],
                        ];
                    }, $result);

                    phpOfficeLoad();
                    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
                    $activeSheet = $spreadsheet->getActiveSheet();
                    $activeSheet->setCellValue('A1', 'Ward No.');
                    $activeSheet->setCellValue('B1', 'Licence No');
                    $activeSheet->setCellValue('C1', 'Owner Name');

                    $activeSheet->setCellValue('D1', 'Firm Name');
                    $activeSheet->setCellValue('E1', 'Mobile No');
                    $activeSheet->setCellValue('F1', 'Apply Date');
                    $activeSheet->setCellValue('G1', 'Area IN Sq. Ft.');
                    $activeSheet->setCellValue('H1', 'Valid Upto');
                    $activeSheet->setCellValue('I1', 'Applicaton Type');

                    $activeSheet->fromArray($recods, NULL, 'A2');

                    $filename = "Trade_licence_list" . date('Ymd-hisa') . ".xlsx";
                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment;filename="' . $filename . '"');
                    header('Cache-Control: max-age=0');
                    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                    $writer->save('php://output');
                    return;
                }

                $data['results'] = $this->model_datatable->getDatatable($sql);

                $data['offset'] = $data['results']['offset'];
                $data['pager'] = $data['results']['count'];

            } catch (\Exception $e) {
                error_log("error" . $e->getMessage());
            }
        }

        return view('trade/Reports/trade_license_list', $data);
    }

    public function trade_apply_list()
    {

        $data = [];
        $data = $data = $this->request->getVar();
        $Session = session();
        $data['ulb_dtl'] = $Session->get('ulb_dtl');
        $ward_sql = " select * from view_ward_mstr where status = 1 and ulb_mstr_id = ? ";
        $data['ward'] = $this->model_view_trade_licence->row_query($ward_sql, array($this->ulb_id));

        $category_sql = "SELECT *FROM tbl_category_type where status = 1 order by id asc";
        $data['category_list'] = $this->trade->query($category_sql)->getResultArray();

        $application_type_sql = "SELECT *FROM tbl_application_type_mstr WHERE status = 1 ORDER by id ASC";
        $data['application_list'] = $this->trade->query($application_type_sql)->getResultArray();

        $from_date = $this->request->getVar('from_date');
        $upto_date = $this->request->getVar('upto_date');
        $ward_mstr_id = $this->request->getVar('ward_mstr_id');
        $category = $this->request->getVar('category');
        $application_type = $this->request->getVar('application_type');

        if ($this->request->getVar()) {
            try {

                $sql = "SELECT  tbl_apply_licence.id,
                                    tbl_apply_licence.application_no,
                                    tbl_firm_type_mstr.firm_type,
                                    tbl_application_type_mstr.application_type,
                                    tbl_ownership_type_mstr.ownership_type,
                                    view_ward_mstr.ward_no,
                                    tbl_apply_licence.firm_name,
                                    tbl_apply_licence.payment_status,
                                    tbl_apply_licence.pending_status,
                                    tbl_apply_licence.address,
                                    tbl_apply_licence.licence_for_years,
                                    tbl_apply_licence.apply_date,
                                    tbl_category_type.category_type,
                                    tbl_apply_licence.license_no,
                                    tbl_apply_licence.license_date,
                                    tbl_apply_licence.apply_from,
                                    string_agg(tbl_firm_owner_name.owner_name, ',') firm_owner_name,
                                    tbl_apply_licence.holding_no,
                                    string_agg(tbl_firm_owner_name.mobile::text, ',') mobile,
                                    COUNT(*) OVER() as total_count,
                                    
                                    -- Fetch employee name based on conditions
                                    CASE 
                                        WHEN tbl_apply_licence.apply_from != 'ONLINE' 
                                            AND tbl_apply_licence.application_type_id = 1
                                        THEN view_emp_details.emp_name
                                        ELSE NULL
                                    END AS employee_name
                                    
                            FROM tbl_apply_licence
                                LEFT JOIN tbl_application_type_mstr ON tbl_apply_licence.application_type_id = tbl_application_type_mstr.id
                                LEFT JOIN tbl_firm_type_mstr ON tbl_apply_licence.firm_type_id = tbl_firm_type_mstr.id
                                LEFT JOIN tbl_category_type ON tbl_apply_licence.category_type_id = tbl_category_type.id
                                INNER JOIN view_ward_mstr ON tbl_apply_licence.ward_mstr_id = view_ward_mstr.id
                                LEFT JOIN tbl_firm_owner_name ON tbl_apply_licence.id = tbl_firm_owner_name.apply_licence_id
                                LEFT JOIN tbl_ownership_type_mstr ON tbl_apply_licence.ownership_type_id = tbl_ownership_type_mstr.id
                                -- Join with view_emp_details only when needed
                                LEFT JOIN view_emp_details ON tbl_apply_licence.emp_details_id = view_emp_details.id 
                                                            AND tbl_apply_licence.apply_from != 'ONLINE' 
                                                            AND tbl_apply_licence.application_type_id = 1
                                                            
                            WHERE tbl_apply_licence.status = 1 
                            " . ($ward_mstr_id ? "AND tbl_apply_licence.ward_mstr_id = $ward_mstr_id " : "") . "
                            " . ($application_type ? "AND tbl_apply_licence.application_type_id = $application_type" : "") . "
                            " . ($from_date ? "AND DATE(tbl_apply_licence.apply_date) BETWEEN '$from_date' AND '$upto_date' " : "") . "
                            " . ($category ? "AND tbl_apply_licence.category_type_id = $category" : "") . "

                            GROUP BY tbl_apply_licence.id, tbl_apply_licence.application_no, tbl_firm_type_mstr.firm_type,
                                    tbl_application_type_mstr.application_type,
                                    tbl_ownership_type_mstr.ownership_type,
                                    view_ward_mstr.ward_no,
                                    tbl_apply_licence.firm_name,
                                    tbl_apply_licence.payment_status,
                                    tbl_apply_licence.pending_status,
                                    tbl_apply_licence.address,
                                    tbl_apply_licence.licence_for_years,
                                    tbl_apply_licence.apply_date,
                                    tbl_category_type.category_type,
                                    tbl_apply_licence.license_no,
                                    tbl_apply_licence.license_date,
                                    tbl_apply_licence.apply_from,
                                    tbl_apply_licence.holding_no,
                                    view_emp_details.emp_name,
                                    tbl_apply_licence.application_type_id";

                if (isset($data["export"]) && $data["export"]) {
                    ini_set('memory_limit', '2048M');
                    $result = $this->model_datatable->getRecords($sql , false);

                    $recods = array_map(function ($val) {

                        return [
                            $val["ward_no"],
                            $val["application_no"],
                            $val["firm_owner_name"],
                            $val["firm_name"],
                            $val["application_type"],
                            $val["mobile"],
                            $val["apply_date"],
                            $val["holding_no"],
                            $val["apply_from"],
                            $val["employee_name"],
                        ];
                    }, $result);

                    phpOfficeLoad();
                    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
                    $activeSheet = $spreadsheet->getActiveSheet();
                    $activeSheet->setCellValue('A1', 'Ward No.');
                    $activeSheet->setCellValue('B1', 'Application No');
                    $activeSheet->setCellValue('C1', 'Firm Owner Name');

                    $activeSheet->setCellValue('D1', 'Firm Name');
                    $activeSheet->setCellValue('E1', 'Application Type');
                    $activeSheet->setCellValue('F1', 'Mobile No');
                    $activeSheet->setCellValue('G1', 'Apply Date');
                    $activeSheet->setCellValue('H1', 'Holding No');
                    $activeSheet->setCellValue('I1', 'Apply From');
                    $activeSheet->setCellValue('J1', 'Employee Name');

                    $activeSheet->fromArray($recods, NULL, 'A2');

                    $filename = "Trade_apply_licence_list" . date('Ymd-hisa') . ".xlsx";
                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment;filename="' . $filename . '"');
                    header('Cache-Control: max-age=0');
                    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                    $writer->save('php://output');
                    return;
                }

                $data['results'] = $this->model_datatable->getDatatable($sql);

                $data['offset'] = $data['results']['offset'];
                $data['pager'] = $data['results']['count'];

            } catch (\Exception $e) {
                error_log('error' . $e->getMessage());
            }
        }

        return view('trade/Reports/apply_trade_list', $data);
    }

}


