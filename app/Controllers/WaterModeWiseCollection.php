<?php 
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\Water_Transaction_Model;
use App\Models\WaterReportModel;
use App\Models\model_datatable;
use Exception;

class WaterModeWiseCollection extends AlphaController
{
    protected $water;
   // protected $db;
   // protected $dbSystem;
    protected $Water_Transaction_Model;
    protected $water_report_model;
    public function __construct(){
        parent::__construct();
        helper(['db_helper','form']);
        helper(['db_helper','form','form_helper','url_helper','utility_helper','qr_code_generator_helper']);
        helper(['url',  'validation_helper', 'validate_saf_helper', 'sms_helper', 'php_office_helper']);
        if($db_name = dbConfig("water")){
            $this->water = db_connect($db_name);            
        }
        /*if($db_name = dbSystem()){
            $this->dbSystem = db_connect($db_name); 
        }*/
         /*if($db_name = dbConfig("property")){
            $this->db = db_connect($db_name); 
        }*/
        $this->Water_Transaction_Model = new Water_Transaction_Model($this->water);
        $this->water_report_model=new WaterReportModel($this->water);
        $this->model_datatable = new model_datatable($this->water);
    }
    public function report()
    {
        $data =(array)null;
        if($this->request->getMethod()=='post')
        {
            $data['from_date'] = $this->request->getVar('from_date');
            $data['to_date'] = $this->request->getVar('to_date');
            $data['payment_mode'] = $this->request->getVar('payment_mode');
            if($data['payment_mode']!=""){

                $where=" where transaction_date between '".$data['from_date']."' and '".$data['to_date']."' and lower(payment_mode)='".strtolower($data['payment_mode'])."'";

                $data['total'] = $this->Water_Transaction_Model->getSumByMode($data);
               // $data['transactionList'] = $this->Water_Transaction_Model->getTransactionByDate($where);
                $data['demand_transactions']=$this->Water_Transaction_Model->getDemandTransactions($where);
                $data['new_connection_transactions']=$this->Water_Transaction_Model->getNewConnectionTransactions($where);
                
            }
            else
            {

                $where=" where transaction_date between '".$data['from_date']."' and '".$data['to_date']."'";

                $data['total'] = $this->Water_Transaction_Model->getSum($data);
                $data['demand_transactions']=$this->Water_Transaction_Model->getDemandTransactions($where);
                $data['new_connection_transactions']=$this->Water_Transaction_Model->getNewConnectionTransactions($where);
                //$data['transactionList'] = $this->Water_Transaction_Model->getTransaction($where);
            }
            $sql = "select * from view_new_connection_transactions $where";
            $results=$this->water_report_model->consumerWiseDCB2($sql);
            $data['transactionList']=$results['result'];
            $data["results"]=$results;            
            
        }
        return view('water/report/water_mode_collection',$data);
    }

    public function report_copy()
    {
        $data =(array)null;
        if(strtoupper($this->request->getMethod())=='POST')
        { 
            $data['from_date'] = $this->request->getVar('from_date');
            $data['to_date'] = $this->request->getVar('to_date');
            $data['payment_mode'] = $this->request->getVar('payment_mode');
            
            //print_var($data);die;
            if($data['payment_mode']!="")
            {

                $where=" where transaction_date between '".$data['from_date']."' and '".$data['to_date']."' and lower(payment_mode)='".strtolower($data['payment_mode'])."'";

                $data['total'] = $this->Water_Transaction_Model->getSumByMode($data);
               
                
            }
            else
            {

                $where=" where transaction_date between '".$data['from_date']."' and '".$data['to_date']."'";

                $data['total'] = $this->Water_Transaction_Model->getSum($data);
               
                //$data['transactionList'] = $this->Water_Transaction_Model->getTransaction($where);
            }            
            try
            {
                $start = sanitizeString($this->request->getVar('start'));
                    
                $rowperpage = sanitizeString($this->request->getVar('length')); // Rows display per page
    
                $columnIndex = sanitizeString($this->request->getVar('order')[0]['column']); // Column index
                $columnName = sanitizeString($this->request->getVar('columns')[$columnIndex]['data']); // Column name
                if ($columnName=="ward_no" )
                    $columnName='w.ward_no';
                else if ($columnName=="c_a_no")
                    $columnName = 'transection.transaction_type';
                else if ($columnName=="type")
                    $columnName = 'transection.transaction_type';    
                else if ($columnName=="emp_name")
                    $columnName = 'view_emp_details.emp_name';            
                else
                    $columnName = 'transection.id ';
    
                //$columnName = "tbl_transaction.tran_date";
                $columnSortOrder = sanitizeString($this->request->getVar('order')[0]['dir']); // asc or desc
                $searchValue = sanitizeString($this->request->getVar('search')['value']); // Search value
                
                // Date filterarchValue = sanitizeString($this->request->getVar('search')['value']);
                $orderBY = " ORDER BY ".$columnName." ".$columnSortOrder;
                $limit = " LIMIT ".$rowperpage." OFFSET ".$start; 
    
                $whereQueryWithSearch = "";
                if ($searchValue!='') 
                {
                    $whereQueryWithSearch = " AND (transection.transaction_no ILIKE '%".$searchValue."%'
                                    OR w.ward_no ILIKE '%".$searchValue."%'
                                    OR view_emp_details.emp_name ILIKE '%".$searchValue."%'
                                    OR transection.payment_mode ILIKE '%".$searchValue."%'
                                    OR tbl_cheque_details.cheque_no ILIKE '%".$searchValue."%'
                                    OR tbl_cheque_details.bank_name ILIKE '%".$searchValue."%'
                                    OR tbl_cheque_details.branch_name ILIKE '%".$searchValue."%'
                                     )";
                }
                $base_url = base_url();                
                
                $with = "with transection as (
                            select * 
                            from tbl_transaction 
                            $where and status = ANY (ARRAY[1, 2])
                        ),
                        apply_water_connection as (
                            select view_water_application_details.*, tbl_transaction.id as tr_id 
                            from view_water_application_details
                            join tbl_transaction on tbl_transaction.related_id = view_water_application_details.id		
                            $where 
                                and transaction_type in ('New Connection','Site Inspection') 
                                and tbl_transaction.status = ANY (ARRAY[1, 2])
                            
                        ),
                        consumer as (
                            select view_consumer_owner_details.*, tbl_transaction.id as tr_id
                            from view_consumer_owner_details
                            join tbl_transaction on tbl_transaction.related_id = view_consumer_owner_details.id	
                            $where 
                                and transaction_type in ('Demand Collection') 
                                and tbl_transaction.status = ANY (ARRAY[1, 2])
                            
                        )
                ";
                
                $select="SELECT ROW_NUMBER () OVER (ORDER BY ".$columnName.") AS s_no,
                            w.ward_no,
                            case when transection.transaction_type in ('Demand Collection') then consumer.consumer_no 
                                when transection.transaction_type in ('New Connection','Site Inspection') then apply_water_connection.application_no
                                else 'xxxxxxxxxx' end as c_a_no,
                            case when transection.transaction_type in ('Demand Collection') then consumer.applicant_name 
                                when transection.transaction_type in ('New Connection','Site Inspection') then apply_water_connection.applicant_name
                                else 'xxxxxxxxxx'  end as applicant_name,
                            case when transection.transaction_type in ('Demand Collection') then consumer.mobile_no 
                                when transection.transaction_type in ('New Connection','Site Inspection') then apply_water_connection.mobile_no
                                else 'xxxxxxxxxx'  end as mobile_no,
                            transection.transaction_no,
                            transection.transaction_date,
                            transection.payment_mode,
                            transection.paid_amount,
                            view_emp_details.emp_name,
                            tbl_cheque_details.cheque_no,
                            tbl_cheque_details.cheque_date,
                            tbl_cheque_details.bank_name,
                            tbl_cheque_details.branch_name,
                            case when transection.transaction_type in ('Demand Collection') then 'Consumer' 
                                when transection.transaction_type in ('New Connection','Site Inspection') then 'New connection'
                                else 'xxxxxxxxxx' end as type
                ";
                $from_tbl="from transection
                        join view_ward_mstr w on w.id = transection.ward_mstr_id
                        join view_emp_details on view_emp_details.id = transection.emp_details_id
                        left join apply_water_connection on transection.id=apply_water_connection.tr_id
                        left join consumer on transection.id=consumer.tr_id
                        left join tbl_cheque_details on tbl_cheque_details.transaction_id=transection.id
                        
                        $where                              
                ";
                //print_var($with.$select.$from_tbl.$whereQueryWithSearch.$orderBY.$limit);die;
                $totalRecords = $this->model_datatable->getTotalRecords($from_tbl,false,$with);
                if ($totalRecords>0) 
                {
                    
                    ## Total number of records with filtering
                    $totalRecordwithFilter = $this->model_datatable->getTotalRecordwithFilter($from_tbl.$whereQueryWithSearch,false,$with);
                    
                    ## Fetch records                
                    $fetchSql = $with.$select.$from_tbl.$whereQueryWithSearch.$orderBY.$limit;
                    
                    $result = $this->model_datatable->getRecords($fetchSql,false);                
                    
                    $records = [];
                    if ($result) 
                    {
                        foreach ($result AS $key=>$tran_dtl) 
                        {
                            $records[] = [
                                's_no'=>$tran_dtl['s_no'],
                                'ward_no'=>$tran_dtl['ward_no'],
                                'c_a_no'=>$tran_dtl['c_a_no'],
                                'type'=>$tran_dtl['type'],
                                'applicant_name'=>$tran_dtl['applicant_name'],
                                'mobile_no'=>$tran_dtl['mobile_no'],
                                'transaction_no'=>$tran_dtl['transaction_no'],
                                'transaction_date'=>$tran_dtl['transaction_date'],
                                'payment_mode'=>$tran_dtl['payment_mode'],
                                'paid_amount'=>$tran_dtl['paid_amount'],
                                'emp_name'=>$tran_dtl['emp_name'],
                                'cheque_no'=>$tran_dtl['cheque_no'],

                                'cheque_date'=>$tran_dtl['cheque_date'],
                                'bank_name'=>$tran_dtl['bank_name'],
                                'branch_name'=>$tran_dtl['branch_name'],
                                
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
                    "recordsTotal" => $totalRecords,                 
                    "recordsFiltered" => $totalRecordwithFilter,
                    "data" => $records,
                    "Total"=> isset($data['total'])?number_format($data['total']):0,                
    
                );
                return json_encode($response); 
                
            }
            catch(Exception $e)
            {
                print_r($e);
            }                       
            
        }
        else
         return view('water/report/water_mode_collection_copy',$data);
    }
    public function report_copyAjax()
    {
        if(strtoupper($this->request->getMethod())=='POST')
        { 
            $data['from_date'] = $this->request->getVar('from_date');
            $data['to_date'] = $this->request->getVar('to_date');
            $data['payment_mode'] = $this->request->getVar('payment_mode');
            //print_var($data);die;
            if($data['payment_mode']!="")
            {

                $where=" where transaction_date between '".$data['from_date']."' and '".$data['to_date']."' and lower(payment_mode)='".strtolower($data['payment_mode'])."'";

                $data['total'] = $this->Water_Transaction_Model->getSumByMode($data);
               
                
            }
            else
            {

                $where=" where transaction_date between '".$data['from_date']."' and '".$data['to_date']."'";

                $data['total'] = $this->Water_Transaction_Model->getSum($data);
               
                //$data['transactionList'] = $this->Water_Transaction_Model->getTransaction($where);
            }            
            try
            {
                $start = sanitizeString($this->request->getVar('start'));
                    
                $rowperpage = sanitizeString($this->request->getVar('length')); // Rows display per page
    
                $columnIndex = sanitizeString($this->request->getVar('order')[0]['column']); // Column index
                $columnName = sanitizeString($this->request->getVar('columns')[$columnIndex]['data']); // Column name
                if ($columnName=="ward_no" )
                    $columnName='w.ward_no';
                else if ($columnName=="c_a_no")
                    $columnName = 'transection.transaction_type';
                else if ($columnName=="type")
                    $columnName = 'transection.transaction_type';    
                else if ($columnName=="emp_name")
                    $columnName = 'view_emp_details.emp_name';            
                else
                    $columnName = 'transection.id ';
    
                //$columnName = "tbl_transaction.tran_date";
                $columnSortOrder = sanitizeString($this->request->getVar('order')[0]['dir']); // asc or desc
                $searchValue = sanitizeString($this->request->getVar('search')['value']); // Search value
                
                // Date filterarchValue = sanitizeString($this->request->getVar('search')['value']);
                $orderBY = " ORDER BY ".$columnName." ".$columnSortOrder;
                $limit = " LIMIT ".$rowperpage." OFFSET ".$start; 
    
                $whereQueryWithSearch = "";
                if ($searchValue!='') 
                {
                    $whereQueryWithSearch = " AND (transection.transaction_no ILIKE '%".$searchValue."%'
                                    OR w.ward_no ILIKE '%".$searchValue."%'
                                    OR view_emp_details.emp_name ILIKE '%".$searchValue."%'
                                    OR transection.payment_mode ILIKE '%".$searchValue."%'
                                    OR tbl_cheque_details.cheque_no ILIKE '%".$searchValue."%'
                                    OR tbl_cheque_details.bank_name ILIKE '%".$searchValue."%'
                                    OR tbl_cheque_details.branch_name ILIKE '%".$searchValue."%'
                                     )";
                }
                $base_url = base_url();                
                
                $with = "with transection as (
                            select * 
                            from tbl_transaction 
                            $where and status = ANY (ARRAY[1, 2])
                        ),
                        apply_water_connection as (
                            select view_water_application_details.*, tbl_transaction.id as tr_id 
                            from view_water_application_details
                            join tbl_transaction on tbl_transaction.related_id = view_water_application_details.id		
                            $where 
                                and transaction_type in ('New Connection','Site Inspection') 
                                and tbl_transaction.status = ANY (ARRAY[1, 2])
                            
                        ),
                        consumer as (
                            select view_consumer_owner_details.*, tbl_transaction.id as tr_id
                            from view_consumer_owner_details
                            join tbl_transaction on tbl_transaction.related_id = view_consumer_owner_details.id	
                            $where 
                                and transaction_type in ('Demand Collection') 
                                and tbl_transaction.status = ANY (ARRAY[1, 2])
                            
                        )
                ";
                
                $select="SELECT ROW_NUMBER () OVER (ORDER BY ".$columnName.") AS s_no,
                            w.ward_no,
                            case when transection.transaction_type in ('Demand Collection') then consumer.consumer_no 
                                when transection.transaction_type in ('New Connection','Site Inspection') then apply_water_connection.application_no
                                else 'xxxxxxxxxx' end as c_a_no,
                            case when transection.transaction_type in ('Demand Collection') then consumer.applicant_name 
                                when transection.transaction_type in ('New Connection','Site Inspection') then apply_water_connection.applicant_name
                                else 'xxxxxxxxxx'  end as applicant_name,
                            case when transection.transaction_type in ('Demand Collection') then consumer.mobile_no 
                                when transection.transaction_type in ('New Connection','Site Inspection') then apply_water_connection.mobile_no
                                else 'xxxxxxxxxx'  end as mobile_no,
                            transection.transaction_no,
                            transection.transaction_date,
                            transection.payment_mode,
                            transection.paid_amount,
                            view_emp_details.emp_name,
                            tbl_cheque_details.cheque_no,
                            tbl_cheque_details.cheque_date,
                            tbl_cheque_details.bank_name,
                            tbl_cheque_details.branch_name,
                            case when transection.transaction_type in ('Demand Collection') then 'Consumer' 
                                when transection.transaction_type in ('New Connection','Site Inspection') then 'New connection'
                                else 'xxxxxxxxxx' end as type
                ";
                $from_tbl="from transection
                        join view_ward_mstr w on w.id = transection.ward_mstr_id
                        join view_emp_details on view_emp_details.id = transection.emp_details_id
                        left join apply_water_connection on transection.id=apply_water_connection.tr_id
                        left join consumer on transection.id=consumer.tr_id
                        left join tbl_cheque_details on tbl_cheque_details.transaction_id=transection.id
                        
                        $where                              
                ";
                //print_var($with.$select.$from_tbl.$whereQueryWithSearch.$orderBY.$limit);die;
                $totalRecords = $this->model_datatable->getTotalRecords($from_tbl,false,$with);
                if ($totalRecords>0) 
                {
                    
                    ## Total number of records with filtering
                    $totalRecordwithFilter = $this->model_datatable->getTotalRecordwithFilter($from_tbl.$whereQueryWithSearch,false,$with);
                    
                    ## Fetch records                
                    $fetchSql = $with.$select.$from_tbl.$whereQueryWithSearch.$orderBY.$limit;
                    
                    $result = $this->model_datatable->getRecords($fetchSql,false);                
                    
                    $records = [];
                    if ($result) 
                    {
                        foreach ($result AS $key=>$tran_dtl) 
                        {
                            $records[] = [
                                's_no'=>$tran_dtl['s_no'],
                                'ward_no'=>$tran_dtl['ward_no'],
                                'c_a_no'=>$tran_dtl['c_a_no'],
                                'type'=>$tran_dtl['type'],
                                'applicant_name'=>$tran_dtl['applicant_name'],
                                'mobile_no'=>$tran_dtl['mobile_no'],
                                'transaction_no'=>$tran_dtl['transaction_no'],
                                'transaction_date'=>$tran_dtl['transaction_date'],
                                'payment_mode'=>$tran_dtl['payment_mode'],
                                'paid_amount'=>$tran_dtl['paid_amount'],
                                'emp_name'=>$tran_dtl['emp_name'],
                                'cheque_no'=>$tran_dtl['cheque_no'],

                                'cheque_date'=>$tran_dtl['cheque_date'],
                                'bank_name'=>$tran_dtl['bank_name'],
                                'branch_name'=>$tran_dtl['branch_name'],
                                
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
                    "recordsTotal" => $totalRecords,                 
                    "recordsFiltered" => $totalRecordwithFilter,
                    "data" => $records,
                    "Total"=> isset($data['total'])?number_format($data['total']):0,                
    
                );
                return json_encode($response); 
                
            }
            catch(Exception $e)
            {
                print_r($e);
            }                       
            
        }
    }
    public function report_copyExcel($from_date=null,$to_date=null,$payment_mode=null)
    {   
        try
        {    
            $data['from_date'] = $from_date;
            $data['to_date'] = $to_date;
            $data['payment_mode'] = $payment_mode;            
            if($data['payment_mode']!="ALL")
            {

                $where=" where transaction_date between '".$data['from_date']."' and '".$data['to_date']."' and lower(payment_mode)='".strtolower($data['payment_mode'])."'";

                $data['total'] = $this->Water_Transaction_Model->getSumByMode($data);               
                
            }
            else
            {

                $where=" where transaction_date between '".$data['from_date']."' and '".$data['to_date']."'";

                $data['total'] = $this->Water_Transaction_Model->getSum($data);               
                
            } 

            $with_query = "with transection as (
                        select * 
                        from tbl_transaction 
                        $where and status = ANY (ARRAY[1, 2])
                    ),
                    apply_water_connection as (
                        select view_water_application_details.*, tbl_transaction.id as tr_id 
                        from view_water_application_details
                        join tbl_transaction on tbl_transaction.related_id = view_water_application_details.id		
                        $where 
                            and transaction_type in ('New Connection','Site Inspection') 
                            and tbl_transaction.status = ANY (ARRAY[1, 2])
                        
                    ),
                    consumer as (
                        select view_consumer_owner_details.*, tbl_transaction.id as tr_id
                        from view_consumer_owner_details
                        join tbl_transaction on tbl_transaction.related_id = view_consumer_owner_details.id	
                        $where 
                            and transaction_type in ('Demand Collection') 
                            and tbl_transaction.status = ANY (ARRAY[1, 2])
                        
                    )
            ";
            
            $selectStatement="SELECT 
                        w.ward_no,
                        case when transection.transaction_type in ('Demand Collection') then consumer.consumer_no 
                            when transection.transaction_type in ('New Connection','Site Inspection') then apply_water_connection.application_no
                            else 'xxxxxxxxxx' end as c_a_no,
                        case when transection.transaction_type in ('Demand Collection') then consumer.applicant_name 
                            when transection.transaction_type in ('New Connection','Site Inspection') then apply_water_connection.applicant_name
                            else 'xxxxxxxxxx'  end as applicant_name,
                        case when transection.transaction_type in ('Demand Collection') then consumer.mobile_no 
                            when transection.transaction_type in ('New Connection','Site Inspection') then apply_water_connection.mobile_no
                            else 'xxxxxxxxxx'  end as mobile_no,
                        transection.transaction_no,
                        transection.transaction_date,
                        transection.payment_mode,
                        transection.paid_amount,
                        view_emp_details.emp_name,
                        tbl_cheque_details.cheque_no,
                        tbl_cheque_details.cheque_date,
                        tbl_cheque_details.bank_name,
                        tbl_cheque_details.branch_name,
                        case when transection.transaction_type in ('Demand Collection') then 'Consumer' 
                            when transection.transaction_type in ('New Connection','Site Inspection') then 'New connection'
                            else 'xxxxxxxxxx' end as type
            ";
            $sql="from transection
                    join view_ward_mstr w on w.id = transection.ward_mstr_id
                    join view_emp_details on view_emp_details.id = transection.emp_details_id
                    left join apply_water_connection on transection.id=apply_water_connection.tr_id
                    left join consumer on transection.id=consumer.tr_id
                    left join tbl_cheque_details on tbl_cheque_details.transaction_id=transection.id
                    
                    $where                              
            ";

            $fetchSql = $with_query.$selectStatement.$sql;
            
            $result = $this->model_datatable->getRecords($fetchSql,false);
            $records = [];
            if ($result) 
            {
                foreach ($result AS $key=>$tran_dtl) 
                {
                    

                    $records[] = [
                        // 's_no'=>$tran_dtl['s_no'],
                        'ward_no'=>$tran_dtl['ward_no'],
                        'c_a_no'=>$tran_dtl['c_a_no'],
                        'mobile_no'=>$tran_dtl['mobile_no'],
                        'transaction_no'=>$tran_dtl['transaction_no'],
                        'transaction_date'=>$tran_dtl['transaction_date'],
                        'payment_mode'=>$tran_dtl['payment_mode'],
                        'paid_amount'=>$tran_dtl['paid_amount'],
                        'cheque_no'=>$tran_dtl['cheque_no'],
                        'cheque_date'=>$tran_dtl['cheque_date'],
                        'bank_name'=>$tran_dtl['bank_name'],
                        'branch_name'=>$tran_dtl['branch_name'],

                        'emp_name'=>$tran_dtl['emp_name'],

                        
                    ];

                }
                
            }
            phpOfficeLoad();
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $activeSheet = $spreadsheet->getActiveSheet();
                            $activeSheet->setCellValue('A2', 'Ward No.');
                            $activeSheet->setCellValue('B2', 'Consumer/Application No.');
                            $activeSheet->setCellValue('C2', 'Mobile No.');
                            $activeSheet->setCellValue('D2', 'Transaction No.');
                            $activeSheet->setCellValue('E2', 'Transaction Date');
                            $activeSheet->setCellValue('F2', 'Payment Mode');
                            $activeSheet->setCellValue('G2', 'Amount');
                            $activeSheet->setCellValue('H2', 'Cheque No.');
                            $activeSheet->setCellValue('I2', 'Cheuqe Date');
                            $activeSheet->setCellValue('J2', 'Bank Name');
                            $activeSheet->setCellValue('K2', 'Branch Name');
                            $activeSheet->setCellValue('L2', 'Collected By');


                            $activeSheet->fromArray($records, NULL, 'A4');

            $filename = "Mode wise collection".date('Ymd-hisa').".xlsx";
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="'.$filename.'"');
            header('Cache-Control: max-age=0');
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            //$writer->save(APPPATH.'/hello world.xlsx');
            $writer->save('php://output');
        }
        catch(Exception $e)
        {   
            print_var($e);
        }
    }
}
?>
